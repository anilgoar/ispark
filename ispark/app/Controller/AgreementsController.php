<?php
class AgreementsController extends AppController 
{
    public $uses=array('Addbranch','CostCenterMaster','Email','Addclient','Agreement','AgreementParticular','Escalation','User');
    //public $components = array('Session');

    public function beforeFilter()
    {
        parent::beforeFilter();
        
        //$this->Auth->deny('index');
        $this->Auth->allow('get_costcenter','get_costcenter2','get_client','view','get_agreement_data','edit');
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
        else
        {   $role=$this->Session->read("role");
            $roles = explode(',',$role);
            $this->Auth->allow('index'); 
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
            $data= $this->request->data['Agreement'];
            $esc[] = $this->request->data['esc1'];
            $esc[] = $this->request->data['esc2'];
            $esc[] = $this->request->data['esc3'];
            
            $periodTo = $data['periodTo'];
            $periodFrom = $data['periodFrom'];
            $data['periodTo'] = date_format(date_create($data['periodTo']),'Y-m-d'); //converting periodto to sql date format
            $data['periodFrom'] = date_format(date_create($data['periodFrom']),'Y-m-d'); //converting periodfrom sql date format
            $date = date('Y-m-d H:i:s');       //creating system date and time of insertion
            $data['user_id'] = $this->Session->read('userid'); //reading user_id from session
            $files=$data['image_upload'];       //taking reference variable to move file tmp to file location
            $cost_center = $data['cost_center']; //cost_center for storing in agreement particular cost_center wise
            $document = $data['document_type'];
            $b_name = $data['branch_name'];
            
            $data=Hash::remove($data,'image_upload'); //removing array image_upload
            $data=Hash::remove($data,'cost_center'); //removing array cost_center because array not stored on sql
            
            foreach($data as $k=>$v)                                //replacing special charecters from all strings
            {
                $dataY[$k] = preg_replace('/[^A-Za-z0-9\ -]/', '', $v);
            }
            $data = $dataY;
            $data['createdate'] = $date;
            $data['cost_center'] = implode(',',$cost_center); //making comma separated cost_center from array to store in tables
            
            $dataSource = $this->Agreement->getDataSource(); //begin transaction
            $dataSource ->begin();
             
            $flag1 = $this->Agreement->saveAll($data); //saving agreement details
            //if($this->Agreement->saveAll($data)) 
            //{
            $id = $this->Agreement->getLastInsertID();  //get auto increment id to store in agreement particulars by cost_center wise
            mkdir(WWW_ROOT."/Agreement/".$id);
                
            if(!empty($files))
            {
                if(is_array($files)) //for storing multiple files
                {
                    foreach($files as $file)
                    {
                        $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                        move_uploaded_file($file['tmp_name'],WWW_ROOT."/Agreement/$id/".$file['name']);
                        $fileName[]= $file['name'];
                    }
                
                    $fileName = implode(',',$fileName);
                }
                else
                {
                    $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']); //for storing single file
                    move_uploaded_file($files['tmp_name'],WWW_ROOT."/Agreement/$id/".$file['name']);
                    $fileName = $file['name'];
                }
            }
                
            $data['image_upload'] = $fileName;      //storing filename in agreement table
            $flag2 = $this->Agreement->updateAll(array("image_upload"=>"'".$fileName."'"),array('id'=>$id));
                
            $i=0; 
            foreach($cost_center as $c):
                $dataX[$i]['data_id'] = $id;
                foreach($data as $k=>$v):
                    $dataX[$i][$k] = $v;
                endforeach;
                $dataX[$i]['cost_center'] = $c;
                $dataX[$i++]['createdate'] = $date;
            endforeach;
            
            $flag3 = $this->AgreementParticular->saveAll($dataX);
            $i=1;$dataY = array();
            foreach($esc as $k=>$v)
            {
                $v['data_id'] = $id;
                $v['esc_id'] = $i++;
                $v['createdate'] = $date;
                $dataY[$k] =$v; 
            }
                
                //print_r($dataY); die;
            $flag4 = $this->Escalation->saveAll($dataY);
            
            
            if($flag1 && $flag2 && $flag3 && $flag4)
            {
                $dataSource->commit();
                
                App::uses('sendEmail', 'custom/Email');
                $cost_center = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('id'=>$cost_center)));
                $cost_center = array_values($cost_center);
                
                $sub = $document;
                $msg ="Dear All,<br><br>"; 
                $msg .= "$b_name $document for CostCenter ".implode(",",$cost_center)." is Uploaded For $periodTo To $periodFrom ";
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
                $this->redirect(array('action'=>'index'));
            }
            else
            {   $dataSource->rollback();
                $this->Session->setFlash("Record not saved. Please Try Again!");
            }
        }
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
    public function get_client()
    {
        $this->layout='ajax';
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $branch = $this->request->data['branch_name'];
            $this->set("client",
            $this->Addclient->find('list',array('conditions'=>array('branch_name'=>$branch,'client_status'=>1),'fields'=>array('id','client_name'))));        
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
        
    }
    public function get_agreement_data()
    {
        $this->layout='ajax';
        //print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
            $branch_name = $this->request->data['branch_name'];
			$cost_center = $this->request->data['cost_center'];
			if($cost_center=="") { $qr = "";  } else { $qr ="AND t2.cost_cnter='$cost_center'"; }
            $this->set("Data",
            $this->AgreementParticular->query("SELECT t2.branch, t2.cost_center,t1.document_type, IF(t1.periodFrom>CURDATE(),'Inforce','Lapsed')`Agri_status`,t1.periodFrom,t1.periodTo,t1.data_id,
t1.image_upload  FROM agreement_particulars t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.id WHERE t2.branch='$branch_name' $qr"));
        }
    }
    
    public function edit()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        $branch = $this->Session->read("branch_name");
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
        
        $data = $this->Agreement->find('first',array('conditions'=>array('id'=>$id)));
        $esc1 = $this->Escalation->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>1)));
        $esc2 = $this->Escalation->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>2)));
        $esc3 = $this->Escalation->find('first',array('conditions'=>array('data_id'=>$id,'esc_id'=>3)));
        
        $cost_center = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),
            'conditions'=>array('branch'=>$data['Agreement']['branch_name'])));
        
        $cost_center2 = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),
            'conditions'=>array('id'=>explode(',',$data['Agreement']['cost_center']))));
        //print_r($cost_center2); die; 
        $this->set('data',$data['Agreement']);
        $this->set('esc1',$esc1);
        $this->set('esc2',$esc2);
        $this->set('esc3',$esc3);
        $this->set('cost_center',$cost_center);
        $this->set('cost_center3',implode(', ',array_values(array_keys($cost_center2))));
        $this->set('cost_center2',implode(', ',array_values($cost_center2)));
        
        
        
        if($this->request->is('Post'))
        {
            $data= $this->request->data['Agreement'];
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
                $dataY[$k] = preg_replace('/[^A-Za-z0-9 \-]/', '', $v);
            }
            $data = $dataY;
            
            $data['modify_date'] = $date;
            $data['cost_center'] = implode(',',$cost_center); //making comma separated cost_center from array to store in tables
            
            $dataSource = $this->Agreement->getDataSource(); //begin transaction
            $dataSource ->begin();
            
            foreach($data as $k=>$v)                                //replacing special charecters from all strings
            {
                $dataY1[$k] = "'".$v."'";
            }
            $data = $dataY1; unset($dataY1);
            $flag1 = $this->Agreement->updateAll($data,array('id'=>$id)); //saving agreement details
            
            $flag2 = true;
            if(!empty($files[0]['name']))
            {
                if(is_array($files)) //for storing multiple files
                {
                    foreach($files as $file)
                    {
                        $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                        move_uploaded_file($file['tmp_name'],WWW_ROOT."/Agreement/$id/".$file['name']);
                        $fileName[]= $file['name'];
                    }
                
                    $fileName = implode(',',$fileName);
                }
                else
                {
                    $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']); //for storing single file
                    move_uploaded_file($files['tmp_name'],WWW_ROOT."/Agreement/$id/".$file['name']);
                    $fileName = $file['name'];
                }
            $data['image_upload']=$dataY['image_upload'] = $fileName;      //storing filename in agreement table
            $flag2 = $this->Agreement->updateAll(array("image_upload"=>"'".$fileName."'"),array('id'=>$id));
            unset($data);unset($fileName);
            }
            
            $this->AgreementParticular->deleteAll(array('data_id'=>$id));
            
            $i=0; $j=0;
            $dataY=Hash::remove($dataY,'branch_name');
            $dataY=Hash::remove($dataY,'modify_id');
            $dataY=Hash::remove($dataY,'modify_date');
            if(!key_exists('image_upload', $dataY))
            {
               $data2= $this->Agreement->find('first',array('fields'=>array('image_upload'),'conditions'=>array('id'=>$id)));
               $dataY['image_upload'] = $data2['Agreement']['image_upload'];
               unset($data2);
            }
            foreach($cost_center as $c):
                    $dataX[$i]['data_id'] = $id;
                    foreach($dataY as $k=>$v):
                        $dataX[$i][$k] = $v;
                    endforeach;
                    $dataX[$i]['cost_center'] = $c;
                    $dataX[$i++]['createdate'] = $date;
            endforeach;
            $flag3 = $this->AgreementParticular->saveAll($dataX);
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
                $this->Escalation->updateAll($dataY,array('data_id'=>$id,'esc_id'=>$i++));
            }
                
                //print_r($dataY); die;
            //$flag4 = $this->Escalation->saveAll($dataY);
            
            
            if($flag1 && $flag2 && $flag3)
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