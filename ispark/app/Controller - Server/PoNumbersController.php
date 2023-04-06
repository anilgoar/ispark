<?php
class PoNumbersController extends AppController 
{
    public $uses=array('Addbranch','CostCenterMaster','PONumber','PONumberParticular','Escalation2','Item');
    

    public function beforeFilter()
    {
        parent::beforeFilter();
        
        //$this->Auth->deny('index');
        $this->Auth->allow();
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
        else
        {   $role=$this->Session->read("role");
            $roles = explode(',',$role);
            if(in_array('46',$roles)){$this->Auth->allow('index','get_costcenter','item_save','delete_item',
                    'get_costcenter2','get_client','view','get_po_data','edit'); }
        }
    }
    
    
    public function index()
    {
        $this->layout="home";
        $branch = $this->Session->read('branch_name');
        $branchArr = explode(',',$this->Session->read('branch_name'));
        
        if($this->Session->read('role')=='admin')
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        }
        else if(count($branchArr)>1)
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branchArr),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
           $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branch),'order'=>array('branch_name'=>'asc')))); 
        }
        if ($this->request->is('post'))
        {
            $data= $this->request->data['PoNumber'];
            $esc[] = $this->request->data['esc1'];
            $esc[] = $this->request->data['esc2'];
            $esc[] = $this->request->data['esc3'];
            
            $periodTo = $data['periodTo'];
            $periodFrom = $data['periodFrom'];
            $b_name = $data['branch_name'];
            $data['periodTo'] = date_format(date_create($data['periodTo']),'Y-m-d'); //converting periodto to sql date format
            $data['periodFrom'] = date_format(date_create($data['periodFrom']),'Y-m-d'); //converting periodfrom sql date format
            $date = date('Y-m-d H:i:s');       //creating system date and time of insertion
            $data['user_id'] = $this->Session->read('userid'); //reading user_id from session
            $files=$data['image_upload'];       //taking reference variable to move file tmp to file location
            $cost_center = $data['cost_center']; //cost_center for storing in agreement particular cost_center wise
            
            $data=Hash::remove($data,'image_upload'); //removing array image_upload
            $data=Hash::remove($data,'cost_center'); //removing array cost_center because array not stored on sql
            
            foreach($data as $k=>$v)                                //replacing special charecters from all strings
            {
                $dataY[$k] = preg_replace('/[^A-Za-z0-9\- ]/', '', $v);
            }
            $data = $dataY;
            $data['createdate'] = $date;
            $data['cost_center'] = implode(',',$cost_center); //making comma separated cost_center from array to store in tables
            
            $dataSource = $this->PONumber->getDataSource(); //begin transaction
            $dataSource ->begin();
             
            $flag1 = $this->PONumber->saveAll($data); //saving agreement details
            //if($this->PONumber->saveAll($data)) 
            //{
            $id = $this->PONumber->getLastInsertID();  //get auto increment id to store in agreement particulars by cost_center wise
             
            mkdir(WWW_ROOT."/PO/".$id);
                
            if(!empty($files))
            {
                    foreach($files as $file)
                    {
                        $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                        move_uploaded_file($file['tmp_name'],WWW_ROOT."/PO/$id/".$file['name']);
                        $fileName[]= $file['name'];
                    }
                    $fileName = implode(',',$fileName);
            }
            
            $data['image_upload'] = $fileName;      //storing filename in agreement table
            $flag4 = $this->PONumber->updateAll(array("image_upload"=>"'".$fileName."'"),array('id'=>$id));
            $i=0; 
            foreach($cost_center as $c):
                $dataX[$i]['data_id'] = $id;
                foreach($data as $k=>$v):
                    $dataX[$i][$k] = $v;
                endforeach;
                $dataX[$i]['cost_center'] = $c;
                $dataX[$i++]['createdate'] = $date;
            endforeach;
            
            $flag2 = $this->PONumberParticular->saveAll($dataX);
            $i=1;$dataY = array();
            foreach($esc as $k=>$v)
            {
                $v['data_id'] = $id;
                $v['esc_id'] = $i++;
                $v['createdate'] = $date;
                $dataY[$k] =$v; 
            }
                
                //print_r($dataY); die;
            $flag3 = $this->Escalation2->saveAll($dataY);
            
            
            if($flag1 && $flag2 && $flag3)
            {
                $dataSource->commit();
                
                App::uses('sendEmail', 'custom/Email');
                $cost_center = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('id'=>$cost_center)));
                $cost_center = array_values($cost_center);
                
                $sub = "PO Number";
                $msg ="Dear All,<br><br>"; 
                $msg .= "$b_name PO for CostCenter ".implode(",",$cost_center)." is Entered For $periodTo To $periodFrom ";
                $msg .="<br><br>"; 
                $msg .="This is System Genrated mail, Please don't reply.<br>";
                $msg .="Regards<br>"; 
                $msg .="<b>I-Spark</b>"; 
                
                if(!empty($esc[0]['esc1']))
                {
                    if(!empty($esc[0]['esc1']['internal_to']))
                    {
                        $emailID[] = explode(",",$esc[0]['esc1']['internal_to']);
                    }
                    if(!empty($esc[0]['esc1']['internal_cc']))
                    {
                        $emailID[] = explode(",",$esc[0]['esc1']['internal_cc']);
                    }
                }
                if(!empty($emailID))
                {
                    $emailID = array_values($emailID);
                    $mail = new sendEmail();
                    $mail-> to($emailID,$msg,$sub);
                }
                
                $this->Session->setFlash("Record has been saved successfully.");
                $this->redirect(array('action'=>'item_save','?'=>array('id'=>$id)));
            }
            else
            {   $dataSource->rollback();
                $this->Session->setFlash("Record not saved. Please Try Again!");
            }
        }
    }
    
    public function item_save()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data;
            if($data['Add']=='Add')
            {
                $data = $data['Item'];
                $data['data_id'] = $id;
                $this->Item->save($data);
                $this->Session->setFlash("Item saved successfully.");
                $this->redirect(array('action'=>'item_save','?'=>array('id'=>$id)));
            }
            else if($data['submit']=='submit')
            {
                $this->PONumber->updateAll(array('amount'=>$data['Item']['Total'],'balAmount'=>$data['Item']['Total']),array("id"=>$id));
                $this->Session->setFlash("Item has been saved successfully.");
                $this->redirect(array('action'=>'index'));
            }
        }        
        $this->set('data',$this->Item->find("all",array('conditions'=>array('data_id'=>$id))));
    }
    public function delete_item()
    {
        $this->layout="ajax";
        $id = $this->request->data['id'];
        if($this->Item->deleteAll(array('id'=>$id)))
        {
            $this->set('response','1');
        }
        else
        {$this->set('response','0sfsdf');}
    }
    public function get_costcenter()
    {
        $this->layout='ajax';
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $branch = $this->request->data['branch_name'];
            $this->set("cost",
            $this->CostCenterMaster->find('list',array('conditions'=>array('branch'=>$branch,'active'=>1),'fields'=>array('id','cost_center'))));        
        }   
    }
    public function get_costcenter2()
    {
        $this->layout='ajax';
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $branch = $this->request->data['branch_name'];
            $this->set("cost",
            $this->CostCenterMaster->find('list',array('conditions'=>array('branch'=>$branch,'active'=>1),'fields'=>array('id','cost_center'))));
        }   
    }
    
    public function view()
    {        
        $this->layout="home"; 
        $branch = $this->Session->read('branch_name');
        $branchArr = explode(',',$this->Session->read('branch_name'));
        
        if($this->Session->read('role')=='admin')
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        }
        else if(count($branchArr)>1)
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branchArr),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
           $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branch),'order'=>array('branch_name'=>'asc')))); 
        }
        
        if($this->request->is('POST'))
        {
            $branchName = $this->request->data['PoNumber']['branch_name'];
            $cost_center = $this->request->data['Agreement']['cost_center'];
            //print_r($this->request->data); exit;
            $qry = "";
            if($branchName) {$qry = "AND t2.branch='$branchName'";}
            if($cost_center) {$qry .= "AND t1.cost_center='$cost_center'";}
            
            $this->set("Data",$this->PONumberParticular->query("SELECT t2.branch, t2.cost_center, t3.amount,t3.balAmount, IF(t1.periodFrom>CURDATE(),'Inforce','Lapsed')`Agri_status`,t1.periodFrom,t1.periodTo,t1.data_id,t1.image_upload,
t1.poNumber  FROM po_number_particulars t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.id INNER JOIN po_number t3 ON t1.data_id = t3.id
WHERE 1=1 $qry"));
        }
        
    }
    public function get_po_data()
    {
        $this->layout='ajax';
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $cost_center = $this->request->data['cost_center'];
            $this->set("Data",
            $this->PONumberParticular->query("SELECT t2.branch, t2.cost_center, t3.amount,t3.balAmount, IF(t1.periodFrom>CURDATE(),'Inforce','Lapsed')`Agri_status`,t1.periodFrom,t1.periodTo,t1.data_id,t1.image_upload,
t1.poNumber  FROM po_number_particulars t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.id inner join po_number t3 on t1.data_id = t3.id"));
        }
    }
    
    public function edit()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        $branch = $this->Session->read('branch_name');
        $branchArr = explode(',',$this->Session->read('branch_name'));
        
        if($this->Session->read('role')=='admin')
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        }
        else if(count($branchArr)>1)
        {
            $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branchArr),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
           $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branch),'order'=>array('branch_name'=>'asc')))); 
        }
        
        $data = $this->PONumber->find('first',array('conditions'=>array('id'=>$id)));
        //print_r($data); exit;
        $esc1 = $this->Escalation2->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>1)));
        $esc2 = $this->Escalation2->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>2)));
        $esc3 = $this->Escalation2->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>3)));
        
        $cost_center = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),
            'conditions'=>array('branch'=>$data['PONumber']['branch_name'])));
        
        $cost_center2 = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),
            'conditions'=>array('id'=>explode(',',$data['PONumber']['cost_center']))));
        //print_r($cost_center2); die;
        $this->set('data',$data['PONumber']);
        $this->set('esc1',$esc1);
        $this->set('esc2',$esc2);
        $this->set('esc3',$esc3);
        $this->set('cost_center',$cost_center);
        $this->set('cost_center2',implode(', ',array_values($cost_center2)));
        
        
        if($this->request->is('Post'))
        {
            $data= $this->request->data['PoNumber'];
            $esc[] = $this->request->data['esc1'];
            $esc[] = $this->request->data['esc2'];
            $esc[] = $this->request->data['esc3'];
            
            $data['periodFrom'] = date_format(date_create($data['periodFrom']),'Y-m-d'); //converting periodfrom sql date format
            $data['periodTo'] = date_format(date_create($data['periodTo']),'Y-m-d'); //converting periodto to sql date format
            $date = date('Y-m-d H:i:s');       //creating system date and time of insertion
            $data['modify_id'] = $this->Session->read('userid'); //reading user_id from session
            $files=$data['image_upload'];       //taking reference variable to move file tmp to file location
            
            $cost_center = $data['cost_center']; //cost_center for storing in agreement particular cost_center wise
            
            $data=Hash::remove($data,'image_upload'); //removing array image_upload
            $data=Hash::remove($data,'cost_center'); //removing array cost_center because array not stored on sql
            
            foreach($data as $k=>$v)                                //replacing special charecters from all strings
            {
                $dataY[$k] = preg_replace('/[^A-Za-z0-9 \- ]/', '', $v);
            }
            $data = $dataY;
            
            $data['modify_date'] = $date;
            $data['cost_center'] = implode(',',$cost_center); //making comma separated cost_center from array to store in tables
            
            $dataSource = $this->PONumber->getDataSource(); //begin transaction
            $dataSource ->begin();
            
            $poNumber = $data['poNumber'];
            $balanceAmountArr = $this->PONumber->query("SELECT SUM(total) `total` FROM tbl_invoice WHERE approve_po !='' and approve_po is not null   and po_no LIKE '%$poNumber%'");
            $data['balAmount'] = $data['amount'] - $balanceAmountArr['0']['0']['total'];
            //print_r($data); exit;
            foreach($data as $k=>$v)                                //replacing special charecters from all strings
            {
                $dataY1[$k] = "'".$v."'";
            }
            $data = $dataY1; unset($dataY1);
            
            
            
            
           // print_r($data); exit;
            $flag1 = $this->PONumber->updateAll($data,array('id'=>$id)); //saving agreement details
            mkdir(WWW_ROOT."/PO/".$id);
            $flag4 = true;
            if(!empty($files[0]['name']))
            {
                if(is_array($files)) //for storing multiple files
                {
                    foreach($files as $file)
                    {
                        $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                        move_uploaded_file($file['tmp_name'],WWW_ROOT."/PO/$id/".$file['name']);
                        $fileName[]= $file['name'];
                    }
                 
                    $fileName = implode(',',$fileName);
                }
                else
                {
                    $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']); //for storing single file
                    move_uploaded_file($files['tmp_name'],WWW_ROOT."/PO/$id/".$file['name']);
                    $fileName = $file['name'];
                }
            $data['image_upload']=$dataY['image_upload'] = $fileName;      //storing filename in agreement table
            $flag4 = $this->PONumber->updateAll(array("image_upload"=>"'".$fileName."'"),array('id'=>$id));
            unset($data);unset($fileName);
            }
            
            
            $this->PONumberParticular->deleteAll(array('data_id'=>$id));
            
            $i=0; $j=0;
            $dataY=Hash::remove($dataY,'branch_name');
            $dataY=Hash::remove($dataY,'modify_id');
            $dataY=Hash::remove($dataY,'modify_date');
            
            foreach($cost_center as $c):
                    $dataX[$i]['data_id'] = $id;
                    foreach($dataY as $k=>$v):
                        $dataX[$i][$k] = $v;
                    endforeach;
                    $dataX[$i]['cost_center'] = $c;
                    $dataX[$i++]['createdate'] = $date;
            endforeach;
            $flag2 = $this->PONumberParticular->saveAll($dataX);
            unset($dataX); unset($dataY);
            $i=1;
            //print_r($esc); exit;
            foreach($esc as $v)
            {
                $dataY = array();
                foreach($v as $k1=>$v1)
                {
                    $dataY[$k1] = "'".$v1."'";
                }
                //print_r($dataY); exit;
                $this->Escalation2->updateAll($dataY,array('data_id'=>$id,'esc_id'=>$i++));
            }
                
                //print_r($dataY); die;
            //$flag4 = $this->Escalation2->saveAll($dataY);
            
            
            if($flag1 && $flag2)
            {
                $dataSource->commit();
                $this->Session->setFlash("Record has been Updated successfully.");
                $this->redirect(array('action'=>'view'));
            }
            else
            {   $dataSource->rollback();
                $this->Session->setFlash("Record not Updated. Please Try Again!");
            }
        }
        
    }
    
}