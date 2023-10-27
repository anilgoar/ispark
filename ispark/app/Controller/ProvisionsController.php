<?php
class ProvisionsController extends AppController 
{
    public $uses=array('Provision','CostCenterMaster','TMPProvision','Addbranch','InitialInvoice','CostCenterEmail',
        'ProvisionNextMonth','BillMaster','FreezeData','ProvisionEditRequest','ProvisionPartTmp','ProvisionPart');
    public $components =array('Session');
    public function beforeFilter()
    {
        parent::beforeFilter();
	
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
	else
	{
            $this->Auth->allow('index','add','uploadProvision','view','edit','update','provision_check',
                    'provision_check_edit','uploadProvision','view_provision','get_cost_center','get_provision_amt_field',
                    'get_cost_center_bill','bill_outsource','get_cost_bill','bill_outsource_master','get_cost_rev','add_outsource_record'
                    ,'bill_outsource_master_save','delete_bill_part','dashboard','provisionDetails','showReport',
                    'dashboard','provisionDetails','view_provision_change_request','bill_outsource_proc_upd',
                    'get_outsource_record','get_upd_proc_outsource');
            
            
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('index','add');
            $this->Auth->allow('index','add','uploadProvision');
            $this->Auth->allow('index','view','add','edit','update','provision_check',
                    'provision_check_edit','uploadProvision','view_provision','get_cost_center','get_provision_amt_field',
                    'get_cost_center_bill','bill_outsource','get_cost_bill','bill_outsource_master','get_cost_rev','add_outsource_record'
                    ,'bill_outsource_master_save','delete_bill_part');
            $this->Auth->allow('dashboard','provisionDetails','showReport');
            $this->Auth->allow('view','edit','update');
            $this->Auth->allow('dashboard','provisionDetails','view_provision_change_request');
            $this->Auth->allow('bill_outsource_proc_upd','get_outsource_record','get_upd_proc_outsource');
	}
    }
		
    public function index() 
    {
       $this->layout="home";
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'=>"asc")));
       $this->set('branch_master',$branch);
       $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
    }
    
    public function get_cost_center()
    {
        $Branch =  $this->request->data['Branch'];
        //$cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('id',"cost_center"),"conditions"=>"branch='$Branch'",'order'=>array("RIGHT(CostCenterMaster.cost_center,3) asc")));
        $cost_list = $this->CostCenterMaster->query("SELECT cost_center,process_name FROM cost_master cm"
                . " WHERE branch='$Branch' and active='1' ORDER BY  RIGHT(cost_center,3)");
        
        echo '<option value="">Select</option>';
        foreach($cost_list as $cl)
        {
            echo '<option value="'.$cl['cm']['cost_center'].'">'.$cl['cm']['process_name'].'-'.substr($cl['cm']['cost_center'],-3).'</option>';
        }
        //array_walk($cost_list,array($this,"make_select_options")); 
        exit; 
    }

    public function get_provision_amt_field()
    {
        
        $Cost_Center =  $this->request->data['CostCenter'];
        //$cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('id',"cost_center"),"conditions"=>"branch='$Branch'",'order'=>array("RIGHT(CostCenterMaster.cost_center,3) asc")));
        $amt_field_arr = $this->CostCenterMaster->query("SELECT Revenue,Billing FROM cost_master cm"
                . " WHERE cost_center='$Cost_Center' and active='1' limit 1");
        
        //print_r($amt_field_arr); exit;
        
        if(!empty($amt_field_arr))
        {
            if($amt_field_arr['0']['cm']['Revenue']=='1')
            {
                echo '<label class="col-sm-2 control-label">Revenue Amount</label>';
                echo "<div class=\"col-sm-2\">";
                echo '<input type="text" name="data[Provision][provision]" class="form-control" placeholder="Revenue" onkeypress="return isNumberKey(event)" required="" id="provision" maxlength="25">';
                echo "</div>";
            }
            if($amt_field_arr['0']['cm']['Billing']=='1')
            {
                echo '<label class="col-sm-2 control-label">Billing Amount</label>';
                echo "<div class=\"col-sm-2\">";
                echo '<input type="text" name="data[Provision][billing_amt]" class="form-control" placeholder="Billing" onkeypress="return isNumberKey(event)" required="" id="billing_amt" maxlength="25">';
                echo "</div>";
            }
        }
        else
        {
            echo "Not Found"; exit;
        }
        
        //array_walk($cost_list,array($this,"make_select_options")); 
        exit; 
    }
    
    public function add()
    {
        if($this->request->is('POST') && !empty($this->request->data))
        {
            $request = $this->request->data['Provision'];
            
            //print_r($this->request->data); exit;
            
            $data['branch_name'] = addslashes($request['branch_name']);
            $data['cost_center'] = addslashes(trim($request['cost_center'])); //exit;
            $data['finance_year'] = addslashes(trim($request['finance_year']));
            $data['remarks'] = addslashes($request['remarks']); 
            $data['month'] = addslashes(trim($request['month']));
            $monthArr = array('Jan','Feb','Mar');
            $split = explode('-',$data['finance_year']);
            //print_r($split); die;
            if(in_array($data['month'], $monthArr))
            {
                if($split[0]==date('Y') || $split[1]==date('y'))
                {
                    $monthNew = $data['month'] = $data['month'].'-'.date('y');
                }
                else
                {
                    $monthNew = $data['month'] .= '-'.$split[1];
                }  
                
            }
            else
            {
                $monthNew = $data['month'] .= '-'.($split[1]-1);
            }
              
            $amt_field_arr = $this->CostCenterMaster->query("SELECT Revenue,Billing FROM cost_master cm"
                . " WHERE cost_center='{$data['cost_center']}' and active='1' limit 1");
            
            if(empty($amt_field_arr))
            {
                $this->Session->setFlash("Cost Center Record Not Found");
                return $this->redirect(array('action'=>'index'));
            }
            if($amt_field_arr['0']['cm']['Revenue']=='1')
            {
                $data['revenue_active'] = '1';
                if($request['provision']=='')
                {
                    $this->Session->setFlash("Please Fill Revenue Amount");
                    return $this->redirect(array('action'=>'index'));
                }
            }
            if($amt_field_arr['0']['cm']['Billing']=='1')
            {
                $data['billing_active'] = '1';
                if($request['billing_amt']=='')
                {
                    $this->Session->setFlash("Please Fill Billing Amount");
                    return $this->redirect(array('action'=>'index'));
                }
            }
                
            if($this->FreezeData->find('first',array('conditions'=>"Freezed='2' and Branch='".$data['branch_name']."'  and FinanceYear='".$data['finance_year']."' and FinanceMonth='{$request['month']}'")))
            {
                $this->Session->setFlash("Record Has been Freezed. You can Not Create Provision");   
            }
            
             else if($this->Provision->find('first',array('fields'=>array('id'),
                'conditions'=>array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month']))))
                {
                 $this->Session->setFlash("Provision Allready Exists");   
                }
            else if($this->ProvisionNextMonth->find('first',array('fields'=>array('id'),
                'conditions'=>array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month']))))
                {
                 $this->Session->setFlash("Provision Allready Exists will be Added To Next Month");   
                }    
            else
                {
                    $deduct = $this->InitialInvoice->find('list',array('conditions'=>array('cost_center'=>$data['cost_center'],'month'=>$data['month']),'fields'=>array('id','total')));
                    
                    $data['provision_balance'] = addslashes($request['provision']);
                    foreach($deduct as $d)
                    {
                       $data['provision_balance'] -=$d;
                    }
                    $data['provision'] = addslashes($request['provision']);
                    $data['billing_bal'] = $data['billing_amt'] = addslashes($request['billing_amt']);
                    
                    $data['createdate'] = date('Y-m-d H:i:s');
                    
                    if($this->ProvisionNextMonth->query("SELECT 1 'move' FROM move_next_month_provision
                                WHERE MONTH(STR_TO_DATE(CONCAT('1-','$monthNew'),'%d-%b-%y'))>=MONTH(CURDATE()) limit 1"))
                        {
                            $flagSave = $this->ProvisionNextMonth->save($data);
                        }
                    else{ 
                            $flagSave = $this->Provision->save($data);
                        }
                      $flagSave = false;  
                    if($flagSave)
                    {
                        $cost_center = $data['cost_center'];
                        $email = $this->CostCenterEmail->query("SELECT cce.* FROM cost_master cm INNER JOIN 
cost_center_email cce ON cm.Id = cce.cost_center WHERE cm.cost_center = '$cost_center'");
                        
                        App::uses('sendEmail', 'custom/Email');
                        
                        
                        $pm = array(); $admin = array(); $bm = array(); $corp = array();
                         $rm = array(); $ceo = array();
                         if(!empty($email))
                         {
                            if(!empty($email[0]['cce']['pm']))
                            {
                                $pm =explode(",",$email[0]['cce']['pm']) ;
                                foreach($pm as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $to[] = trim($c); 
                                    }
                                }
                            }
                            if(!empty($email[0]['cce']['admin']))
                            {
                                $admin =explode(",",$email[0]['cce']['admin']) ;
                                foreach($admin as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $to[] = trim($c); 
                                    }
                                }
                            }
                            if(!empty($email[0]['cce']['bm']))
                            {
                                $bm =explode(",",$email[0]['cce']['bm']) ;
                                foreach($bm as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $to[] = trim($c); 
                                    }
                                }
                            }
                            if(!empty($email[0]['cce']['corp']))
                            {
                                $corp =explode(",",$email[0]['cce']['corp']) ;
                                foreach($corp as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $to[] = trim($c); 
                                    }
                                }
                            }
                            if(!empty($email[0]['cce']['rm']))
                            {
                                $rm =explode(",",$email[0]['cce']['rm']) ;
                                foreach($rm as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $cc[] = trim($c); 
                                    }
                                }
                            }
                            if(!empty($email[0]['cce']['ceo']))
                            {
                                //$cc[] = "anil.goar@teammas.in";
                                //$cc[] = "krishna.kumar@teammas.in";
                                $ceo =explode(",",$email[0]['cce']['ceo']) ;
                                foreach($ceo as $c)
                                {
                                    if(!empty($c))
                                    {
                                        $cc[] = trim($c); 
                                    }
                                }
                            }
                        }
                        
                        $sub = "Provision Added";
                        $msg ="Dear All,<br><br>"; 
                        $msg .= "Provision Added For Cost Center $cost_center";
                        $msg .="<br><br>";
                        $msg .="This is System Genrated mail, Please don't reply.<br>";
                        $msg .="Regards<br>"; 
                        $msg .="<b>I-Spark</b>"; 
                        $to = array_unique($to);
                        $cc = array_unique($cc);
                        $mail = new sendEmail();
                        //print_r($to); print_r($cc); 
                        if(!empty($to))
                        {
                            $mail-> multiple($to,$cc,$msg,$sub);
                        }
                        $this->Session->setFlash("Provision Added to Cost Center ".$data['cost_center']." to ".$data['month']." For Financial Year ".$data['finance_year']);
                    }
                    unset($request); unset($data); unset($this->request->data);
                }
        }
        
        return $this->redirect(array('action'=>'view'));
    }
    
    public function view()
    {
       $this->layout = "home";
       $branch_name=$this->Session->read("branch_name");
       $role = $this->Session->read("role");
        if($role=='admin')
        {
            $this->set('branch',array('All'=>'All') + $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
            $this->set('branch',array(''=>'Select') + $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
        }
       //$this->set('branch',$branch);
    }
    
    public function view_provision()
    {
       $this->layout = "ajax";
       $condition = array('branch_name' => $this->params->query['branch_name']);
       $condition2 = "WHERE branch_name ='".$this->params->query['branch_name']."'";
       $condition3 = "WHERE Branch_OutSource ='".$this->params->query['branch_name']."'";
       
       if($this->params->query['branch_name']=='All')
       {
           $condition =array();
           $condition2 = "";
           $condition3 = "";
       }
       $this->set('provision',$this->Provision->query("SELECT * FROM provision_master Provision $condition2 ORDER BY STR_TO_DATE(CONCAT('1-',MONTH),'%d-%b-%Y') DESC"));
       $this->set('provision_part',$this->Provision->query("SELECT Provision.*,sum(Provision.outsource_amt) outsource_amt FROM provision_particulars Provision inner join `provision_master` pm
on Provision.FinanceYear = pm.finance_year and Provision.FinanceMonth=pm.month and Provision.Cost_Center=pm.cost_center and pm.provision_balance!=0 $condition3 group by Provision.FinanceYear,Provision.FinanceMonth,Provision.Cost_Center_OutSource "
               . "ORDER BY STR_TO_DATE(CONCAT('1-',FinanceMonth),'%d-%b-%Y') DESC"));
       //$this->set('provision',$this->Provision->find('all',array('conditions'=>$condition,'order'=>array("CONCAT('1-',month)"=>'desc')))); 
    }
    public function edit()
    {
       $this->layout = "home";
       $id = $this->request->query['id'];
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
       $provision = $this->Provision->find('first',array('conditions'=>array('id'=>$id)));
       $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('cost_center'=>$provision['Provision']['cost_center'])));
       
       $this->set('cost',$cost_master);
       $this->set('provision',$provision);
       $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
    }
    
    public function update()
    {
       $this->layout = "home";
       if($this->request->is('POST') && !empty($this->request->data))
       {
           $data = $this->request->data['Provision'];
           $data['provision_balance'] = $data['provision'];
           
           $id = $this->request->data['Provision']['id'];
           $RevenueActive = $this->request->data['Provision']['revenue_active'];
           $billing_active = $this->request->data['Provision']['billing_active'];
           if($RevenueActive)
           {
                $data=Hash::remove($data,'id');
                $keys = array_keys($data);
           
                for($i=0; $i<count($keys); $i++)
                {
                    $data[$keys[$i]] = addslashes($data[$keys[$i]]);
                }

                $monthArr = array('Jan','Feb','Mar');
                $split = explode('-',$data['finance_year']);

                $MonthProvison = $data['month'];
                 $BranchProvision = '';
                 $FinanceYearProvision = $data['finance_year'];

                 if(in_array($data['month'], $monthArr))
                 {
                    if($split[0]==date('Y') || $split[1]==date('y'))
                    {
                        $data['month'] = $data['month'] . '-'.date('y');
                    }
                    else
                    {
                        $data['month'] = $data['month'].'-'.$split[1];
                    }
                 }
                 else
                 {
                     $data['month'] .= '-'.($split[1]-1);
                 }
            
                if($cost = $this->Provision->find('first',array('conditions'=>"id='$id'")))
                 {
                     $old_cost_center = $cost['Provision']['cost_center'];
                     $BranchProvision = $cost['Provision']['branch_name'];
                     if($data['cost_center'] == $old_cost_center)
                     {
                         $Initial = $this->InitialInvoice->find('all',array('conditions'=>
                             array('cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],
                                 'month'=>$data['month'],'status'=>'0')));
                         foreach($Initial as $ini):
                             $data['provision_balance'] -= $ini['InitialInvoice']['total'];
                         endforeach;
                     }
                 }
                if($data['provision_balance']<0)
                {
                    $this->Session->setFlash('Provision Amount Is Less Than Bill Made. Please Check.');
                     return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id)));
                }
           
                if($this->FreezeData->find('first',array('conditions'=>"Freezed='2' and Branch='".$data['branch_name']."'  and FinanceYear='".$data['finance_year']."' and FinanceMonth='{$request['month']}'")))
                {
                    $this->Session->setFlash("Record Has been Freezed. You can Not Edit Provision");   
                }
           
                $provisionForEditRequest = $this->Provision->find('first',array('conditions'=>"id='$id'"));
                //print_r($provisionForEditRequest); exit;
                $provisionForEditRequest = Hash::Remove($provisionForEditRequest['Provision'],'id');
                $provisionForEditRequest['ProvisionId'] = $id;
                $provisionForEditRequest['old_provision'] = $provisionForEditRequest['provision'];
                $provisionForEditRequest['provision'] = $data['provision'];
                $provisionForEditRequest['createdate'] = date('Y-m-d H:i:s');
                
                
                $provisionForEditRequest['provision_balance'] = $data['provision'];
                $provisionForEditRequest['remarks'] = addslashes($data['remarks']);
                $provisionForEditRequest['userid'] = $this->Session->read("userid");
                $userid = $this->Session->read("userid");
                //$provisionForEditRequest['ProvisionId'] = $id;
                
                if(!empty($this->ProvisionEditRequest->find('list',array('fields'=>array('id','cost_center'),'conditions'=>"ProvisionId='$id' and ApproveStatus='1'"))))
                {
                    
                    if($this->ProvisionEditRequest->updateAll(array('userid'=>$userid,'branch_name'=>"'".$data['branch_name']."'",'remarks'=>"'".addslashes($data['remarks'])."'",'cost_center'=>"'".$data['cost_center']."'",'finance_year'=>"'".$data['finance_year']."'",'month'=>"'".$data['month']."'",'provision'=>$data['provision'],'provision_balance'=>$data['provision']),array('ProvisionId'=>$id)))
                    {
                        $this->Session->setFlash("Provision Edit Request has been saved. Please check ");
                    }
                }
                else
                {
                    $ProvisionEditRequest['ProvisionEditRequest'] =   $provisionForEditRequest;
                    $this->ProvisionEditRequest->save($ProvisionEditRequest);
                }
                
                
           } 
           if($billing_active)
           {
               $data=Hash::remove($data,'id');
                $keys = array_keys($data);
           
                for($i=0; $i<count($keys); $i++)
                {
                    $data[$keys[$i]] = addslashes($data[$keys[$i]]);
                }

                $data['billing_bal'] =  $data['billing_amt'];
            
                if($cost = $this->Provision->find('first',array('conditions'=>"id='$id'")))
                 {
                     $old_cost_center = $cost['Provision']['cost_center'];
                     $BranchProvision = $cost['Provision']['branch_name'];
                     if($data['cost_center'] == $old_cost_center)
                     {
                         $Initial = $this->ProvisionPart->find('all',array('conditions'=>
                             array('Cost_Center'=>$data['cost_center'],'FinanceYear'=>$data['finance_year'],
                                 'FinanceMonth'=>$data['month'])));
                         foreach($Initial as $ini):
                             $data['billing_bal'] -= $ini['ProvisionPart']['outsource_amt'];
                         endforeach;
                     }
                 }
                if($data['billing_bal']<0)
                {
                    $this->Session->setFlash('Revenue Amount Is Less Than OutSource Made. Please Check.');
                     return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id)));
                }
           
                if($this->FreezeData->find('first',array('conditions'=>"Freezed='2' and Branch='".$data['branch_name']."'  and FinanceYear='".$data['finance_year']."' and FinanceMonth='{$request['month']}'")))
                {
                    $this->Session->setFlash("Record Has been Freezed. You can Not Edit Revenue");  
                    return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id)));
                }
                
               
                //$provisionForEditRequest['ProvisionId'] = $id;
                
                if($data['billing_bal']>=0)
                    {    
                        foreach($data as $k=>$v)
                        {
                            $data[$k] = "'".$v."'";
                        }
                        
                        if($this->Provision->updateAll(array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month'],'billing_amt'=>$data['billing_amt'],'billing_bal'=>$data['billing_amt'],'billing_active'=>'1'),array('id'=>$id)))
                        {
                           $this->Session->setFlash("Provision Has been Updated.");  
                            return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id))); 
                        }
                        else
                        {
                            $this->Session->setFlash("Provision Has been Updated.");  
                            return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id))); 
                        }
                    }
                    else
                    {   
                        $this->Session->setFlash("Provision Has been Updated.");  
                            return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id))); 
                    }
           }
            return $this->redirect(array('controller'=>'provisions','action'=>'view','?'=>array('id'=>$id)));
//           if($data['provision_balance']>=0)
//           {
//               for($i=0; $i<count($keys); $i++)
//                {
//                    $data[$keys[$i]] = "'".$data[$keys[$i]]."'";
//                }
//               
//                if($this->Provision->updateAll(array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month'],'provision'=>$data['provision'],'provision_balance'=>$data['provision_balance']),array('id'=>$id)))
//                {
//                    $cost_center = str_replace("'", '', $data['cost_center']);
//                    $branch = str_replace("'", '', $data['branch_name']);
//                    $month = str_replace("'", '',$data['month']);
//               
//                    $email = $this->CostCenterEmail->query("SELECT cce.* FROM cost_master cm INNER JOIN 
//       cost_center_email cce ON cm.Id = cce.cost_center WHERE cm.cost_center = '$cost_center'");
//                        
//                        App::uses('sendEmail', 'custom/Email');
//                        
//                        
//                    $pm = array(); $admin = array(); $bm = array(); $corp = array();
//                    $rm = array(); $ceo = array();
//                    if(!empty($email))
//                    {
//                       if(!empty($email[0]['cce']['pm']))
//                       {
//                           $pm =explode(",",$email[0]['cce']['pm']) ;
//                           foreach($pm as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $to[] = $c; 
//                               }
//                           }
//                       }
//                       if(!empty($email[0]['cce']['admin']))
//                       {
//                           $admin =explode(",",$email[0]['cce']['admin']) ;
//                           foreach($admin as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $to[] = $c; 
//                               }
//                           }
//                       }
//                       if(!empty($email[0]['cce']['bm']))
//                       {
//                           $bm =explode(",",$email[0]['cce']['bm']) ;
//                           foreach($bm as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $to[] = $c; 
//                               }
//                           }
//                       }
//                       if(!empty($email[0]['cce']['corp']))
//                       {
//                           $corp =explode(",",$email[0]['cce']['corp']) ;
//                           foreach($corp as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $to[] = $c; 
//                               }
//                           }
//                       }
//                       if(!empty($email[0]['cce']['rm']))
//                       {
//                           $rm =explode(",",$email[0]['cce']['rm']) ;
//                           foreach($rm as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $cc[] = $c; 
//                               }
//                           }
//                       }
//                       if(!empty($email[0]['cce']['ceo']))
//                       {
//                           //$cc[] = "anil.goar@teammas.in";
//                           //$cc[] = "krishna.kumar@teammas.in";
//                           $ceo =explode(",",$email[0]['cce']['ceo']) ;
//                           foreach($ceo as $c)
//                           {
//                               if(!empty($c))
//                               {
//                                   $cc[] = $c; 
//                               }
//                           }
//                       }
//                   }
//
//                    //$expdate = date_format(date_create($expdate), "d-M-Y");
//                    $sub = "Provision Edited";
//                    $msg ="Dear All,<br><br>"; 
//                    $msg .= "$branch Edited Provision to $cost_center For $month";
//                    $msg .="<br><br>";
//                    $msg .="This is System Genrated mail, Please don't reply.<br>";
//                    $msg .="Regards<br>"; 
//                    $msg .="<b>I-Spark</b>"; 
//                    $to = array_unique($to);
//                    $cc = array_unique($cc);
//                    $mail = new sendEmail();
//                    //print_r($to); print_r($cc); 
//                    if(!empty($to))
//                    {
//                        $mail-> multiple($to,$cc,$msg,$sub);
//                    }   
//                              
//                    $this->Session->setFlash('<font color="green">Provision for Cost Center '.$data['cost_center'].' to Month'. $data['month'].' for Finance Year'. $data['finance_year'].' updated</font>');
//                    return $this->redirect(array('controller'=>'provisions','action'=>'view'));
//                }
//                else
//                {
//                    $this->Session->setFlash('Not Updated');
//                    return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id)));
//                } 
//           }
//           else
//           {
//                $this->Session->setFlash('Invoice Amount Not Less Than Provision');
//                return $this->redirect(array('controller'=>'provisions','action'=>'edit','?'=>array('id'=>$id)));
//           }
       }
       
       
    }
    public function provision_check()
    {
        $this->layout = "ajax";
        $result = $this->params->query;
        $monthArr = array('Jan','Feb','Mar');
//        $split = explode('-',$result['finance_year']);
//        
//            if(in_array($result['month'], $monthArr))
//            {
//                if($split[0]==date('Y') || $split[1]==date('y'))
//                {
//                    $result['month'] .='-'.date('y');
//                }
//                else
//                {
//                    $result['month'] .= '-'.$split[1];
//                }
//            }
//            else
//            {
//                $result['month'] .= '-'.($split[1]-1);
//            }
            
        if($result['finance_year']=='2015-16' || $result['finance_year']=='2014-15' || $result['month']=='Jan-16' || $result['month']=='Feb-16' || $result['month']=='Mar-16' || $result['month']=='Apr-16')
        {
            $this->set('data','1-1');
        }
        
        else
        {
        
            
        if($this->Provision->find('first',array('conditions'=>"cost_center='".$result['cost_center']."' and finance_year='".$result['finance_year']."' and left(month,3)='".$result['month']."' and provision_balance='".$result['total']."'")))
        {
          $this->set('data','1-1');
        }
        else if($data = $this->Provision->find('first',array('conditions'=>"cost_center='".$result['cost_center']."' and finance_year='".$result['finance_year']."' and left(month,3)='".$result['month']."' and provision_balance>".$result['total']."")))
        {
            $provision_balance = $data['Provision']['provision_balance'];
            $this->set('data','2-'.$provision_balance);
        }
        else
        {
            $this->set('data','0-0');
        }
        }
    }
    public function provision_check_edit()
    {
        $this->layout = "ajax";
        $result = $this->params->query;
        
        $month = explode('-',$result['month']);
        $result['month'] = $month[0];
        
        $monthArr = array('Jan','Feb','Mar');
//        $split = explode('-',$result['finance_year']);
//        
//            if(in_array($result['month'], $monthArr))
//            {
//                $result['month'] .= '-'.$split[1];
//            }
//            else
//            {
//                $result['month'] .= '-'.($split[1]-1);
//            }
        
        if($result['finance_year']=='2015-16' || $result['finance_year']=='2014-15' || $result['month']=='Jan-16' || $result['month']=='Feb-16' || $result['month']=='Mar-16' || $result['month']=='Apr-16')
        {
            $this->set('data','1');
        }
        else
        {
        $oldData = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center'),'conditions'=>array('id'=>$result['id'])));
        
        $provision = $this->Provision->find('first',array('fields'=>array('provision_balance'),'conditions'=>array('cost_center'=>$result['cost_center'],'finance_year'=>$result['finance_year'],"left(month,3)='".$result['month']."'")));
        
        $check = 1;
        if($oldData['InitialInvoice']['cost_center']==$result['cost_center'])
        {
            $check = (isset($provision['Provision']['provision_balance']) ? $provision['Provision']['provision_balance'] : 0) +$oldData['InitialInvoice']['total'] - $result['total'];
        }
        else
        {
            $check = (isset($provision['Provision']['provision_balance'])?$provision['Provision']['provision_balance']:0) - $result['total'];
        }
        if($check>=0)
        {
          $this->set('data','1');
        }
        else
        {
          $this->set('data','0');
        }
        }
    }
    
    public function dashboard()
    {
        $this->layout = "home";
        
        $role = $this->Session->read('role');
        $branch_name = $this->Session->read('branch_name');
        $branch = array();
        $conditions = array();
        if($role=='admin')
        {
            $branch = array("All"=>"All");
        }
        else
        {
          $conditions = array('branch_name'=>$branch_name);
        } 
        
	$branch2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions));
        $this->set('branch_master',array_merge($branch,$branch2));
    }
    
    public function showReport()
    {
        $this->layout = "ajax";
        $Year = date('y'); 
    $next_year = $Year+1;
    $last_year = $Year-1;
    $NextYear = '20'.$Year.'-'.$next_year;
        
        $branch = $this->params->query['branch_name'];
		
		if($branch=='All')
		{
        
        
        $data = $this->Provision->query("SELECT branch_name,`month`,SUM(Provision) `Provision`,SUM(`Billing Pending`) `Billing Pending`, GROUP_CONCAT(cost_center1) `cost_center1`,
SUM(`PO Pending`) `PO Pending`, GROUP_CONCAT(cost_center2) `cost_center`, SUM(`GRN Pending`) `GRN Pending`,
GROUP_CONCAT(cost_center3) `cost_center3`,GROUP_CONCAT(`Agreement Pending`) `Agreement Pending`, GROUP_CONCAT(`Invoice No`) `Invoice No`, 
GROUP_CONCAT(`PTP Date`) `PTP Date`,SUM(Payment) `Payment`,SUM(InvoiceSubmit) `InvoiceSubmit`
FROM (SELECT 
'All' branch_name,tb.month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL)) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd-IF(bpp.net_amount IS NULL || bpp.net_amount='',0,bpp.net_amount),0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL))) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd-IF(bpp.net_amount IS NULL || bpp.net_amount='',0,bpp.net_amount),0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center))) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd-IF(bpp.net_amount IS NULL || bpp.net_amount='',0,bpp.net_amount),0),tb1.grnd-IF(bpp.net_amount IS NULL || bpp.net_amount='',0,bpp.net_amount))),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year  IN ('$NextYear') AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
UNION
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti RIGHT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year  IN ($NextYear) AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
)AS tb1
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_passed,net_amount,deduction,
IF(`status` LIKE '%paid%','paid','part payment')`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp
 ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE IF(bpp.status='paid',FALSE,TRUE)
GROUP BY tb1.month
) AS tb
LEFT JOIN (SELECT cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master GROUP BY `month`)AS pm ON tb.finance_year = pm.finance_year AND
tb.month = pm.month

 UNION  ALL
  SELECT 'All' branch_name,'Previous' `month`,'0' `Provision`,'0' `Billing Pending`,
  GROUP_CONCAT(CASE WHEN IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no = '',TRUE,FALSE ),FALSE) THEN tb.bill_no END) `cost_center1`,
  SUM(IF(cm.po_required = 'Yes',IF(tb.approve_po!='Yes',tb.grnd,0),0)) `PO Pending`,
  GROUP_CONCAT(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center),NULL),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center))) `cost_center2`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0),0),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0))) `GRN Pending`,'' `cost_center3`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd),0),
 IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd))) `InvoiceSubmit`,'' `Agreement Pending`,'' `Invoice No`,'' `PTP Date`,
 '0' `payment`
  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
  LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_passed,net_amount,deduction,
IF(`status` LIKE '%paid%','paid','part payment')`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON bpp.company_name = cm.company_name AND
  bpp.branch_name = tb.branch_name AND bpp.financial_year = tb.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb.bill_no,'/',1)
  WHERE IF(bpp.status='paid',FALSE,TRUE) AND  tb.finance_year not IN ('$NextYear') AND bpp.bill_no IS NULL AND (tb.bill_no IS NOT NULL AND tb.bill_no !='')) AS tab 
 GROUP BY `month` ORDER BY STR_TO_DATE(CONCAT('1-',`month`),'%d-%b-%Y') ");
        }
		else
		{
		
         $sel = "SELECT branch_name,`month`,SUM(Provision) `Provision`,SUM(`Billing Pending`) `Billing Pending`,
GROUP_CONCAT(cost_center1) `cost_center1`,SUM(`PO Pending`) `PO Pending`, GROUP_CONCAT(cost_center2) `cost_center`,
SUM(`GRN Pending`) `GRN Pending`,GROUP_CONCAT(cost_center3) `cost_center3`,GROUP_CONCAT(`Agreement Pending`) `Agreement Pending`, 
GROUP_CONCAT(`Invoice No`) `Invoice No`, GROUP_CONCAT(`PTP Date`) `PTP Date`,SUM(Payment) `Payment`,SUM(InvoiceSubmit) `InvoiceSubmit`
 FROM (SELECT 
branch `branch_name`,tb.month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL) order by tb1.cost_center) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd,0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)) order by tb1.cost_center) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)) order by tb1.cost_center) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year in ('$NextYear') AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
)AS tb1
LEFT JOIN bill_pay_particulars bpp ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE bpp.bill_no IS NULL 
GROUP BY tb1.month
) AS tb
LEFT JOIN (SELECT cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master WHERE branch_name='$branch' GROUP BY `month`)AS pm
 ON tb.finance_year = pm.finance_year AND
tb.month = pm.month

UNION  ALL 
  SELECT tb.branch_name,'Previous' `month`,'0' `Provision`,'0' `Billing Pending`,
  GROUP_CONCAT(CASE WHEN IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no = '',TRUE,FALSE ),FALSE) THEN tb.bill_no END) `cost_center1`,
  SUM(IF(cm.po_required = 'Yes' ,IF(tb.approve_po IS NULL AND tb.approve_po='',tb.grnd,0),0)) `PO Pending`,
  GROUP_CONCAT(IF(cm.po_required='Yes' ,IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center),NULL),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center))) `cost_center2`,
  SUM(IF(cm.po_required='Yes' ,IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0),0),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0))) `GRN Pending`,'' `cost_center3`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd),0),
 IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd)) ) `InvoiceSubmit`,'' `Agreement Pending`,'' `Invoice No`,'' `PTP Date`,
 '0' `payment`
  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
LEFT JOIN bill_pay_particulars bpp ON cm.company_name =  bpp.company_name AND
tb.branch_name = bpp.branch_name AND tb.finance_year = bpp.financial_year AND
SUBSTRING_INDEX(tb.bill_no,'/',1) = bpp.bill_no
    WHERE tb.finance_year not IN ('$NextYear') AND tb.branch_name='$branch'
    AND bpp.bill_no IS NULL
    
UNION 

SELECT 
pm.branch_name `branch_name`,'Previous' month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL) order by tb1.cost_center) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd,0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)) order by tb1.cost_center) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)) order by tb1.cost_center) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year not in ('$NextYear') AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')


)AS tb1
LEFT JOIN bill_pay_particulars bpp ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE bpp.bill_no IS NULL 
GROUP BY tb1.month
) AS tb
RIGHT JOIN (SELECT branch_name,cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master WHERE branch_name='$branch' AND finance_year NOT IN ('$NextYear') GROUP BY `month`)AS pm
 ON tb.finance_year = pm.finance_year AND
tb.month = pm.month) AS tab GROUP BY `month` ORDER BY 
  STR_TO_DATE(CONCAT('1-',`month`),'%d-%b-%Y')
  
  "; 
        $data = $this->Provision->query($sel);		
		}
		$this->set('data',$data);
        $this->set('branch',$branch);
		
	
//        $this->set('data',$this->Provision->query("SELECT pm.provision `Billing Pending`,IF(pm.po_date IS NULL,'Action Date Pending','Received')`PO Pending`,
//IF(pm.grn_date IS NULL,'Pending','Received')`GRN Pending`,
//IF(pm.agreement IS NULL,'Pending','Received') `Agreement Pending`,
//GROUP_CONCAT(rm.InvoiceNo) `Invoice No`,GROUP_CONCAT(rm.ExpDatesPayment) `PTP Date`,
//SUM(tb.total) `Payment`
// FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
//INNER JOIN tbl_invoice tb  ON tb.cost_center = pm.cost_center 
//LEFT JOIN `receipt_master` rm ON rm.InvoiceNo = tb.bill_no
//WHERE (tb.finance_year NOT IN ('2014-15','2015-16') OR (tb.finance_year ='2016-17' AND SUBSTRING_INDEX(tb.month,'-',1) 
//NOT IN ('Jan','Feb','Mar'))) 
//AND pm.branch_name='$branch' GROUP BY pm.cost_center"));
    }
    
    public function provisionDetails()
    {
        $this->layout = "ajax";
        $branch = $this->params->query['branch'];
        $Year = date('y'); 
    $next_year = $Year+1;
    $last_year = $Year-1;
    $NextYear = '20'.$Year.'-'.$next_year;
        $split = explode('@@', $this->params->query['branch']);
        $branch = $split['0'];
        $month = $split['1'];
        
        if($split['2']=='provision')
        {
		if($branch=='All') { $QryStr = "";} else { $QryStr = "AND pm.branch_name='$branch'"; }
                if($split['1']!='Previous')
                {
        $data = $this->Provision->query("SELECT pm.branch_name,pm.cost_center `cost_center`, 
pm.provision `Provision`,
(pm.provision-pm.provision_balance) `Bill Raised`,
pm.provision_balance `balance`
 FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
WHERE pm.provision_balance !=0 $QryStr and pm.month = '$month'");
                }
                else
                {
                     $sel ="SELECT pm.branch_name,pm.cost_center `cost_center`, 
pm.provision `Provision`,
(pm.provision-pm.provision_balance) `Bill Raised`,
pm.provision_balance `balance`
 FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
WHERE pm.provision_balance !=0 and pm.finance_year!='$NextYear' $QryStr "; 
                    $data = $this->Provision->query($sel);
                }
        }
        else if($split['2']=='po')
        {
        if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
        
            if($month!='Previous')
			$data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE cm.po_required='Yes' AND (InitialInvoice.approve_po IS NULL OR InitialInvoice.approve_po = '') 
 $QryStr AND InitialInvoice.month = '$month' and bpp.bill_no is null AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
            else
            	$data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
        LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
    WHERE cm.po_required='Yes' AND (InitialInvoice.approve_po IS NULL OR InitialInvoice.approve_po = '')  and bpp.bill_no is null
    AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='') and InitialInvoice.finance_year !='$NextYear' $QryStr
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
        }
        else if($split['2']=='grn')
        {
         if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
         
         if($month!='Previous')
		    $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE  1=1 $QryStr AND InitialInvoice.month = '$month' and bpp.bill_no is null 
 AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')   AND (
(cm.po_required='Yes' AND InitialInvoice.approve_po != ''
AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn =''))
OR
(cm.po_required = 'No' AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn ='')))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
         else
            $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
 LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)               
WHERE  1=1 and InitialInvoice.finance_year !='$NextYear' $QryStr and bpp.bill_no is null
 AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')   AND (
(cm.po_required='Yes' AND InitialInvoice.approve_po != ''
AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn =''))
OR
(cm.po_required = 'No' AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn ='')))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");     
        }
        else if($split['2']=='invoice')
        {
         if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
         
         if($month!='Previous')
		    $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE  InitialInvoice.finance_year in ('$NextYear') AND bpp.bill_no IS NULL AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')
$QryStr AND InitialInvoice.month = '$month' AND IF(cm.po_required='Yes',IF(InitialInvoice.approve_po='Yes',IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE),FALSE),
 IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");
         else
             $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN bill_pay_particulars bpp ON cm.company_name =  bpp.company_name AND
InitialInvoice.branch_name = bpp.branch_name AND InitialInvoice.finance_year = bpp.financial_year AND
SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1) = bpp.bill_no
WHERE  InitialInvoice.finance_year != '$NextYear' $QryStr AND bpp.bill_no IS NULL
AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='') AND IF(cm.po_required='Yes',IF(InitialInvoice.approve_po='Yes',IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE),FALSE),
 IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");
             ;
        }
        else
        {$data = "";}
        $this->set('report',$split['2']);
        $this->set('data',$data);
        $this->set('branch',$branch);
        $this->set('month',$month);
    }
    
    public function uploadProvision()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('username');
            $FileTye = $this->request->data['Provision']['file']['type'];
            $info = explode(".",$this->request->data['Provision']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['Provision']['file']['tmp_name'];
                $files = fopen($FilePath, "r");
                //$files = file_get_contents($FilePath);
                //echo $files;
                $this->TMPProvision->query("truncate table tmp_provision_master");
               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
                $dataArr = array();
                $flag = false;
                while($row = fgetcsv($files,5000,","))
                {
                    if($flag)
                    {
                    $data['cost_center'] = trim($row[0]);
                    if(!empty($data['cost_center'])){
                    $data['finance_year'] = trim($row[1]);
                    $data['month'] = trim($row[2]);
                    $data['provision'] = trim($row[3]);
                    $data['provision_balance'] = trim($row[3]);
                    $data['remarks'] = $row[4];
                    $data['invoiceType1'] = $row[5];
                    $data['billing_amt'] = trim($row[3]);
                    $data['billing_bal'] = trim($row[3]);
                    $dataArr[] = $data;
                    }
                    
                    }
                    else {$flag = true;}
                }
                //print_r($dataArr);
                
                $this->TMPProvision->saveAll($dataArr);
                
                //print_r("LOAD DATA  INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center, finance_year, month, provision, remarks)"); die;
                $data = $this->TMPProvision->find('all',array('fields'=>array('branch_name','cost_center','provision','provision_balance','billing_bal','billing_amt','finance_year','month','remarks')));
		//print_r($data); exit;
                foreach($data as $a)
                {
                    $month = $a['TMPProvision']['month'];
                    $costcenterTo = $a['TMPProvision']['cost_center'];
                    $financeYear = $a['TMPProvision']['finance_year'];
                  $a['TMPProvision']['createdate'] = date('Y-m-d H:i:s');
                 $monthCheckPattern = explode("-",$month);
                    if(!is_numeric($monthCheckPattern[1]))
                    {
                        $a['TMPProvision']['Reasion'] = "Month Format IS not valid";
                        $wrongData[] = $a;
                    }  
                  
                else if($a['TMPProvision']['finance_year'] == '')
                {
                    $a['TMPProvision']['Reasion'] = "finance year is blank";
                    $wrongData[] = $a;
                }
                else if($a['TMPProvision']['cost_center'] == '')
                {
                    $a['TMPProvision']['Reasion'] = "cost center is blank";
                    $wrongData[] = $a;
                }
                else if($a['TMPProvision']['month'] == '')
                { $a['TMPProvision']['Reasion'] = "Month is blank"; $wrongData[] = $a; }
                   else if($this->Provision->find('first',array('conditions'=>
                       array('finance_year'=>$a['TMPProvision']['finance_year'],
                           'cost_center'=>$a['TMPProvision']['cost_center'],
                           'month'=>$a['TMPProvision']['month']))))
                    {$a['TMPProvision']['Reasion'] = "Provision Already Exists"; $wrongData[] = $a;}
                    
                else if($this->ProvisionNextMonth->query("SELECT 1 'move' FROM move_next_month_provision
                                WHERE month='$month' and cost_center='$costcenterTo' and finance_year='$financeYear' "
                        . "and MONTH(STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y'))>=MONTH(CURDATE()) limit 1")) 
                    {
                        $a['TMPProvision']['Reasion'] = "cost center Exist and added To next month";
                        $wrongData[] = $a;
                    }    
                    else
                    {
                        $cost = $this->CostCenterMaster->find('first',array('fields'=>array('branch'),'conditions'=>array('cost_center'=>$a['TMPProvision']['cost_center'])));
                        if(!empty($cost))
                        {
                            $a['TMPProvision']['branch_name'] = $cost['CostCenterMaster']['branch'];
                            
                            if(!empty($a['TMPProvision']['branch_name']))
                            {
                                if($this->ProvisionNextMonth->query("SELECT 1 'move' FROM move_next_month_provision
                                WHERE MONTH(STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y'))>=MONTH(CURDATE()) limit 1"))
                                    {
                                        $a['TMPProvision']['provision_balance'] = $a['TMPProvision']['provision_balance'];
                                        $this->ProvisionNextMonth->saveAll($a['TMPProvision']);
                                        if(!empty($a['TMPProvision']['cost_center']))
                                        {$cost_center[] = $a['TMPProvision']['cost_center'];}
                                        unset($this->ProvisionNextMonth);
                                    }
                                    else
                                    {
                                    $a['TMPProvision']['provision_balance'] = $a['TMPProvision']['provision_balance'];
                                    $this->Provision->saveAll($a['TMPProvision']);
                                    if(!empty($a['TMPProvision']['cost_center'])) { $cost_center[] = $a['TMPProvision']['cost_center'];}
                                    unset($this->Provision);
                                    }
                            }
                            else
                            {$a['TMPProvision']['Reasion'] = "Cost Center Not Found"; $wrongData[] = $a;}
                        }
                        
                        // sending email to server
                        
//                       foreach($cost_center as $cost)     
//                       {
//                           $email = $this->CostCenterEmail->query("SELECT cce.*,cm.branch FROM cost_master cm INNER JOIN 
//cost_center_email cce ON cm.Id = cce.cost_center WHERE cm.cost_center = '$cost' limit 1");
//                        
//                        App::uses('sendEmail', 'custom/Email');
//                        
//                        $pm = array(); $admin = array(); $bm = array(); $corp = array();
//                        $rm = array(); $ceo = array(); $to = array(); $cc = array();
//         
//                        if(!empty($email))
//                        {
//                            $branch = $email[0]['cm']['branch'];
//                            if(!empty($email[0]['cce']['pm']))
//                            {
//                                $pm =explode(",",$email[0]['cce']['pm']) ;
//                                foreach($pm as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $to[] = $c; 
//                                    }
//                                }
//                            }
//                            
//                            if(!empty($email[0]['cce']['admin']))
//                            {
//                                $admin =explode(",",$email[0]['cce']['admin']) ;
//                                foreach($admin as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $to[] = $c; 
//                                    }
//                                }
//                            }
//                            if(!empty($email[0]['cce']['bm']))
//                            {
//                                $bm =explode(",",$email[0]['cce']['bm']) ;
//                                foreach($bm as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $to[] = $c; 
//                                    }
//                                }
//                            }
//                            if(!empty($email[0]['cce']['corp']))
//                            {
//                                $corp =explode(",",$email[0]['cce']['corp']) ;
//                                foreach($corp as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $to[] = $c; 
//                                    }
//                                }
//                            }
//                            if(!empty($email[0]['cce']['rm']))
//                            {
//                                $rm =explode(",",$email[0]['cce']['rm']) ;
//                                foreach($rm as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $cc[] = $c; 
//                                    }
//                                }
//                            }
//                            if(!empty($email[0]['cce']['ceo']))
//                            {
//                                //$cc[] = "anil.goar@teammas.in";
//                                //$cc[] = "krishna.kumar@teammas.in";
//                                $ceo =explode(",",$email[0]['cce']['ceo']) ;
//                                foreach($ceo as $c)
//                                {
//                                    if(!empty($c))
//                                    {
//                                        $cc[] = $c; 
//                                    }
//                                }
//                            }
//                        }
//                        
//                        $sub = "New Provision Uploaded";
//                        $msg ="Dear All,<br><br>"; 
//                        $msg .= "Provision Uploaded For Cost Center $cost";
//                        $msg .="<br><br>";
//                        $msg .="This is System Genrated mail, Please don't reply.<br>";
//                        $msg .="Regards<br>"; 
//                        $msg .="<b>I-Spark</b>"; 
//                        $to = array_unique($to);
//                        $cc = array_unique($cc);
//                        $mail = new sendEmail();
//
//                        if(!empty($to))
//                        {
//                            $mail-> multiple($to,$cc,$msg,$sub); 
//                        } 
//                        
//                       }
                      //  sending email close
                    }
                }
                
                $this->set('wrongData',$wrongData);
                $this->TMPProvision->query("truncate table tmp_provision_master");
                $this->Session->setFlash('File uploaded Success!');
            }
            else{
            $this->Session->setFlash('File Format not Valid');}
        }
    }
    public function view_provision_change_request()
    {
        $this->layout="home";
        
        if($this->request->is('POST'))
        {
            $Post_Data = $this->request->data['check'];
            //print_r($Post_Data); exit;
            if(!empty($this->request->data['Approve']))
            {
                $this->Session->setFlash("Provision Has been Updated Successfully");
                foreach($Post_Data as $id)
                {
                    $data = array();
                    $dataN = $this->ProvisionEditRequest->find('first',array('conditions'=>array('id'=>$id)));
                    //print_r($dataN); exit;
                    $data = $dataN['ProvisionEditRequest'];
                    $ProvisionId = $dataN['ProvisionEditRequest']['ProvisionId'];
                    
                    if($cost = $this->ProvisionEditRequest->find('first',array('conditions'=>array('id'=>$id))))
                    {
                        //print_r($cost); exit;
                         $old_cost_center = $cost['ProvisionEditRequest']['cost_center'];
                         $BranchProvision = $cost['ProvisionEditRequest']['branch_name'];
                         if($data['cost_center'] == $old_cost_center)
                         {
                             $Initial = $this->InitialInvoice->find('all',array('conditions'=>
                                 array('cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],
                                     'month'=>$data['month'],'status'=>'0')));
                             
                             //print_r($Initial); exit;
                            foreach($Initial as $ini)
                            {
                                $data['provision_balance'] -= $ini['InitialInvoice']['total'];
                            }
                         }
                    }
                    //print_r($data); exit;
                    if($data['provision_balance']>=0)
                    {    
                        foreach($data as $k=>$v)
                        {
                            $data[$k] = "'".$v."'";
                        }
                        
                        if($this->Provision->updateAll(array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month'],'provision'=>$data['provision'],'provision_balance'=>$data['provision_balance']),array('id'=>$ProvisionId)))
                        {
                            $this->ProvisionEditRequest->updateAll(array('ApproveStatus'=>"2","ApproveBy"=>$this->Session->read("userid"),'ApproveDate'=>"'".date("Y-m-d")."'"),array('id'=>$id));
                        }
                    }
                    else
                    {
                        $NotUpdatedCost[] = $data['cost_center'];
                        
                    }
                }
                
                if(!empty($NotUpdatedCost))
                {
                    $this->Session->setFlash("Provision is Less Than Invoice Made For Cost Center ".implode(",",$NotUpdatedCost));
                }
                else
                {
                    $this->Session->setFlash("Provision Updated Successfully.");
                }
                return $this->redirect(array('action'=>'view_provision_change_request'));
            }
            else if(!empty($this->request->data['DisApprove']))
            {
                
                foreach($Post_Data as $id)
                {
                    $this->ProvisionEditRequest->updateAll(array('ApproveStatus'=>"3","ApproveBy"=>$this->Session->read("userid"),'ApproveDate'=>"'".date("Y-m-d")."'"),array('id'=>$id));
                }
                return $this->redirect(array('action'=>'view_provision_change_request'));
            }
            $this->Session->setFlash("Provision Request Discarded Successfully.");
            return $this->redirect(array('action'=>'view_provision_change_request'));
            
        }
        
        $data = $this->ProvisionEditRequest->query("Select *,date_format(pre.createdate,'%d-%b-%Y') entrydate,tu.emp_name,cm.process_name from provision_master_edit_request pre left join cost_master cm on pre.cost_center=cm.cost_center inner join tbl_user tu on pre.userid=tu.id"
                . " where ApproveStatus='1'");
        $this->set('data',$data);
    }
    
    public function get_cost_center_bill()
    {
        $FinanceYear = $this->request->data['FinanceYear'];
        $monthNew = $FinanceMonth = $this->request->data['FinanceMonth'];
        $Branch = $this->request->data['Branch'];
        $monthArr = array('Jan','Feb','Mar');
//        $split = explode('-',$FinanceYear);
//            //print_r($split); die;
//            if(in_array($FinanceMonth, $monthArr))
//            {
//                $monthNew = $FinanceMonth .= '-'.$split[1];
//            }
//            else
//            {
//                $monthNew = $FinanceMonth .= '-'.($split[1]-1);
//            }
        
        $Branch =  $this->request->data['Branch'];
        $qry = "SELECT cm.id,cm.process_name,cm.cost_center,pm.provision billing_amt,pm.billing_bal FROM provision_master pm
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center AND cm.active='1'
WHERE  cm.Billing='1' AND pm.branch_name='$Branch' 
      AND pm.finance_year='$FinanceYear' AND left(pm.month,3)='$monthNew' ORDER BY  RIGHT(cm.cost_center,3)"; 
        
        $cost_list = $this->CostCenterMaster->query($qry);
        
        echo '<option value="">Select</option>';
        foreach($cost_list as $cl)
        {
            echo '<option value="'.$cl['cm']['cost_center'].'">'.$cl['cm']['process_name'].'-'.substr($cl['cm']['cost_center'],-3).'</option>';
        }
        //array_walk($cost_list,array($this,"make_select_options")); 
        exit; 
    }
    
    public function get_cost_bill()
    {
        $FinanceYear = $this->request->data['FinanceYear'];
        $monthNew = $FinanceMonth = $this->request->data['FinanceMonth'];
        $Branch = $this->request->data['Branch'];
        $CostCenter = $this->request->data['cost_center'];
        $monthArr = array('Jan','Feb','Mar');
//        $split = explode('-',$FinanceYear);
//            //print_r($split); die;
//            if(in_array($FinanceMonth, $monthArr))
//            {
//                $monthNew = $FinanceMonth .= '-'.$split[1];
//            }
//            else
//            {
//                $monthNew = $FinanceMonth .= '-'.($split[1]-1);
//            }
        
        $Branch =  $this->request->data['Branch'];
       $qry = "SELECT pm.billing_amt,pm.provision FROM provision_master pm
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center AND cm.active='1'
WHERE  pm.branch_name='$Branch' and pm.cost_center='$CostCenter'
      AND pm.finance_year='$FinanceYear' AND left(pm.month,3)='$monthNew' ORDER BY  RIGHT(cm.cost_center,3)";  
        
        $cost_list = $this->CostCenterMaster->query($qry);
        
        if(empty($cost_list))
        {
            echo "0";
        }
        else
        {
            echo $cost_list['0']['pm']['provision'];
        }
        
        
        
        exit; 
    }
    
    public function bill_outsource()
    {
       $this->layout = "home";
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
       $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
    }
    
    public function get_cost_rev()
    {
        
        $Branch =  $this->request->data['Branch'];
        //$cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('id',"cost_center"),"conditions"=>"branch='$Branch'",'order'=>array("RIGHT(CostCenterMaster.cost_center,3) asc")));
        $cost_list = $this->CostCenterMaster->query("SELECT cost_center,process_name FROM cost_master cm"
                . " WHERE branch='$Branch'  and active='1' ORDER BY  RIGHT(cost_center,3)");
        
        echo '<option value="">Select</option>';
        foreach($cost_list as $cl)
        {
            echo '<option value="'.$cl['cm']['cost_center'].'">'.$cl['cm']['process_name'].'-'.substr($cl['cm']['cost_center'],-3).'</option>';
        }
        
        
        
        exit; 
    }
    
    public function bill_outsource_master()
    {
       //print_r($this->request->data); exit; 
       $provision = $this->request->data['Provision'];
       $monthArr = array('Jan','Feb','Mar');
       $monthNew = $provision['month'];
       //print_r($this->request->data); exit;
//            $split = explode('-',$provision['finance_year']);
//            //print_r($split); die;
//            if(in_array($provision['month'], $monthArr))
//            {
//                $monthNew = $provision['month'] .= '-'.$split[1];
//            }
//            else
//            {
//                $monthNew = $provision['month'] .= '-'.($split[1]-1);
//            }
       
       $qry = "SELECT pm.id,pm.provision billing_amt,pm.billing_bal FROM provision_master pm
INNER JOIN cost_master cm ON pm.cost_center = pm.cost_center AND cm.active='1'
WHERE  cm.Billing='1' AND pm.branch_name='{$provision['branch_name']}' and pm.cost_center='{$provision['cost_center']}'
      AND pm.finance_year='{$provision['finance_year']}' AND left(pm.month,3)='$monthNew' ORDER BY  RIGHT(cm.cost_center,3) limit 1"; 
        
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
      
        $prov_billing = $this->Provision->query($qry);
        
        $table_arr = $this->ProvisionPart->query("SELECT * FROM `provision_particulars` ppt WHERE Branch='{$provision['branch_name']}' AND FinanceYear='{$provision['finance_year']}' AND left(FinanceMonth,3)='{$provision['month']}' and Cost_Center='{$provision['cost_center']}'");
        $this->set('table_arr',$table_arr);
        $this->set('provision_bill',$prov_billing);
        $this->set('provision',$provision);
        $this->layout="home";
        
        
        
        
    }
    
    public function bill_outsource_master_save()
    {
        
        
                             $this->Session->setFlash("Revenue Out-Source Saved Successfully");
                             return $this->redirect(array('action'=>'bill_outsource'));
        
//        $Transaction = $this->ProvisionPart->getDataSource();
//        $Transaction->begin();
//        $provision = $this->request->data['Provision'];
//         $qry = "SELECT * FROM `provision_particulars` ppt "
//                . "WHERE Branch='{$provision['branch_name']}' AND FinanceYear='{$provision['finance_year']}' AND FinanceMonth='{$provision['month']}' "
//                . "and Cost_Center='{$provision['cost_center']}'"; 
//        
//        $record_exist_check = $this->ProvisionPart->query($qry);
//        
//        //print_r($record_exist_check); exit;
//        
//        if(empty($record_exist_check))
//        {
//            $qry = "SELECT * FROM `provision_particulars_tmp` ppt "
//                . "WHERE Branch='{$provision['branch_name']}' AND FinanceYear='{$provision['finance_year']}' AND FinanceMonth='{$provision['month']}' "
//                . "and Cost_Center='{$provision['cost_center']}'";  
//           $datacheck =  $this->ProvisionPartTmp->query($qry);
//            
//           //print_r($datacheck); exit;
//           $total = 0;
//           foreach($datacheck as $dater)
//           {
//               $total += $dater['ppt']['outsource_amt'];
//           }
//           
//           $total = round($total,2);
//           
//           $billing_amt = $this->Provision->query("Select billing_amt from provision_master pm where id='{$provision['provision_id']}'");
//           $billing = round($billing_amt['0']['pm']['billing_amt'],2);
//           
//           $flag = true;
////         //  if($total==$billing)
////         //  {
////                foreach($datacheck as $dater)
////                {
////                    $prov_exist = $this->Provision->find('first',array('conditions'=>"branch_name='{$provision['branch_name']}' and finance_year='{$provision['finance_year']}' "
////                    . "and month='{$provision['month']}' and cost_center='{$provision['cost_center']}'"));
////                  //  $out_amt = round($dater['ppt']['outsource_amt'],2);
////                 //   $out_amt += round($prov_exist['Provision']['out_source_amt'],2);
////                    
////                   // if(!empty($prov_exist))
////                 //   {
////                //        $check_update = $this->Provision->updateAll(array("out_source_amt"=>"$out_amt"), array("branch_name"=>$dater['ppt']['Branch_OutSource'],'finance_year'=>$provision['finance_year'],
////                //    "month"=>$provision['month'],"cost_center"=>$provision['cost_center']));
////                //        $dater['ppt']['provision_id'] = $prov_exist['Provision']['id'];
////                    
////               //     }
////              //      else
////              //      {
////               //         $check_update = $this->Provision->save(array('Provision'=>array('out_source_amt'=>$out_amt,'provision'=>0,
////              //              'provision_balance'=>'0','branch_name'=>$dater['ppt']['Branch_OutSource'],'finance_year'=>$provision['finance_year'],
////              //                  'month'=>$provision['month'],'cost_center'=>$dater['ppt']['Cost_Center_OutSource'])));
////              //          $dater['ppt']['provision_id'] = $this->Provision->getLastInsertID();
////              //      }
////                    
////                        $del_arr[] = $dater['ppt']['provision_part_id'];
////                        $dater = Hash::Remove($dater['ppt'],'provision_part_id');
////                       $data_part[] = $dater;
////                        
////                   
////                }
////                //print_r($data_part); exit;
////                if($this->ProvisionPart->saveAll($data_part))
////                {
////                            $this->ProvisionPart->query("delete from provision_particulars_tmp where provision_part_id in (".implode($del_arr).")");
////                             $this->Session->setFlash("Revenue Out-Source Saved Successfully");
////                             return $this->redirect(array('action'=>'bill_outsource'));
////                       
////                }
////                    else
////                    {
////                        
////                        $this->Session->setFlash("Revenue Out-Source Not Saved Successfully");
////                         $Transaction->rollback();
////                         return $this->redirect(array('action'=>'bill_outsource'));
////                    }
////                
////           //}
////          // else
////         //  {
////        //        $Transaction->rollback();
////        //   }
//           
//        }
//        else
//        {
//             $Transaction->rollback();
//            $this->Session->setFlash("Billing Allready Exists");
//        }
//        exit;
    }
    
    public function delete_bill_part()
    {
        $id =  $this->request->data['id'];
        if($this->ProvisionPart->query("delete from provision_particulars where provision_part_id='$id'"))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        exit;
    }
    
    public function add_outsource_record()
    {
        $gBranch =  $this->request->data['gBranch'];
        $year =  $this->request->data['FinanceYear'];
        $month =  $this->request->data['FinanceMonth'];
        $cost_center =  $this->request->data['cost_center'];
        $billing =  $this->request->data['billing'];
        $branch1 =  $this->request->data['branch1'];
        $cost_center1 =  $this->request->data['cost_center1'];
        $billing1 =  $this->request->data['billing1'];
        $provision_id =  $this->request->data['provision_id'];
        
        $flag = true;
        if(empty($gBranch))
        {
            $msg = "Please Select Branch Name";
            $flag = false; 
        }
        else if(empty($year))
        {
           $msg = "Please Select Year"; 
           $flag = false; 
        }
        else if(empty($month))
        {
            $msg = "Please Select Month"; 
            $flag = false; 
        }
        else if(empty($cost_center))
        {
            $msg = "Please Select Cost Center"; 
            $flag = false; 
        }
        else if(empty($billing))
        {
            $msg = "Please Select Billing"; 
            $flag = false; 
        }
        else if(empty($cost_center1))
        {
            $msg = "Please Select Cost Center"; 
            $flag = false; 
        }
        else if(empty($billing1))
        {
            $msg = "Please Select Billing"; 
            $flag = false; 
        }
        
        if($flag)
        {
            $dataArray['billing_id'] =  $provision_id;
            $dataArray['Branch'] =  $gBranch;
            $dataArray['FinanceYear'] =  $year;
            $dataArray['FinanceMonth'] =  $month.'-'.date('y');
            $dataArray['FinanceMonth1'] =  substr($month,0,3);
            $dataArray['Cost_Center'] =  $cost_center;
            $dataArray['outsource_amt'] =  $billing;
            $dataArray['Branch_OutSource'] =  $branch1;
            $dataArray['Cost_Center_OutSource'] =  $cost_center1;
            $dataArray['outsource_amt'] =  $billing1;
            $dataArray['create_date'] =  date('Y-m-d H:i:s');
            $dataArray['create_by'] =  $this->Session->read("userid");
           
            //print_r($dataArray); exit;
            
            $record_exist_check = $this->ProvisionPart->query("SELECT * FROM `provision_particulars` ppt WHERE Branch='$gBranch' AND FinanceYear='$year' AND FinanceMonth='$month' and Cost_Center='$cost_center' and  Cost_Center_OutSource='$cost_center1'");        
                    
            if(empty($record_exist_check))
            {
               if($this->ProvisionPart->save(array('ProvisionPart'=>$dataArray)))
                {
                    echo "Record Saved Successfully";
                }
                else
                {
                    echo "Record Not Saved. Please Try Again";
                } 
            }
            else
            {
                echo 'Record Already Exist';
            }
            
            
//            $table_arr = $this->ProvisionPartTmp->query("SELECT * FROM `provision_particulars_tmp` ppt WHERE Branch='$gBranch' AND FinanceYear='$year' AND FinanceMonth='$month'");
//            $i = 1;
//            foreach($table_arr as $table)
//            {
//                echo "<tr>";
//                echo "<td>".$i++."</th>";
//                    echo "<td>".$table['ppt']['Branch_OutSource']."</td>";
//                    echo "<td>".$table['ppt']['Cost_Center_OutSource']."</td>";
//                    echo "<td>".$table['ppt']['outsource_amt']."</td>";
//                    echo '<td><input type="button" value="Delete" class="btn btn-danger" onclick="delete('."'".$table['ppt']['provision_part_id']."'".')"></td>';
//                echo "</tr>";
//            }
            
        }
        else
        {
            echo $msg;
        }
        exit;
    }
    
    
    public function get_outsource_record()
    {
        $FinanceYear = $this->request->data['FinanceYear'];
        $monthNew = $FinanceMonth = $this->request->data['FinanceMonth'];
        $Branch = $this->request->data['Branch'];
        $CostCenter = $this->request->data['cost_center'];
        
        
        $Branch =  $this->request->data['Branch'];
        $qry = "SELECT * FROM `provision_particulars` pp
INNER JOIN cost_master cm ON pp.Cost_Center_OutSource = cm.cost_center
WHERE  pp.Branch='$Branch' and pp.cost_center='$CostCenter'
      AND pp.financeyear='$FinanceYear' AND left(pp.FinanceMonth,3)='$monthNew' ORDER BY  RIGHT(cm.cost_center,3)";   
        
        $cost_list_arr = $this->ProvisionPart->query($qry);
        
        if(empty($cost_list_arr))
        {
            echo "No Record Found";
        }
        else
        {
            echo '<table class="table">';
            echo '<tr>';
                echo '<th>'.'SrNo.'.'</th>';
                echo '<th>'.'Branch'.'</th>';
                echo '<th>'.'CostCenter'.'</th>';
                echo '<th>'.'FinanceYear'.'</th>';
                echo '<th>'.'FinanceMonth'.'</th>';
                echo '<th>'.'OutSource Amount'.'</th>';
                echo '<th>'.'Action'.'</th>';
            echo '</tr>';
            $i = 1;
            foreach($cost_list_arr as $cost_list)
            {
                echo '<tr>';
                    echo '<td>';
                        echo $i;
                    echo '</td>';
                    echo '<td>';
                        echo $cost_list['cm']['branch'];
                    echo '</td>';
                    echo '<td>';
                        echo $cost_list['cm']['cost_center'];
                    echo '</td>';
                    echo '<td>';
                        echo $cost_list['pp']['FinanceYear'];
                    echo '</td>';
                    echo '<td>';
                        echo $cost_list['pp']['FinanceMonth'];
                    echo '</td>';
                    echo '<td>';
                        echo $cost_list['pp']['outsource_amt'];
                    echo '</td>';
                    echo '<td><div id="out_'.$cost_list['pp']['provision_part_id'].'">';
                        if($cost_list['pp']['Processed']=='1')
                        {
                            echo 'Process';
                        }
                        else
                        {
                            echo '<a href="#" onclick="get_upd_proc('."'".$cost_list['pp']['provision_part_id']."'".')">'.'UnProcess'.'</a>';
                        }
                    echo '</div></td>';
                echo '</tr>';
            }
        }
        
        exit; 
    }
    
    public function get_upd_proc_outsource()
    {
        $proc_id = $this->request->data['proc_id'];
        if( $this->ProvisionPart->updateAll(array('Processed'=>"'1'"),array('provision_part_id'=>$proc_id)))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
        exit;
    }
    
    public function bill_outsource_proc_upd()
    {
       $this->layout = "home";
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
       $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
           'conditions'=>array('active'=>'1'))));
    }
    
}

?>