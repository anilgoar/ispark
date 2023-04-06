<?php
class ImprestsController extends AppController 
{
    public $uses = array('Addbranch','User','CostCenterMaster','ExpenseMaster','ExpenseParticular','ImprestManager','ImprestAllotment','Bank','Tbl_bgt_expenseheadingmaster',
        'VendorMaster','Tbl_bgt_expenseunitmaster','Addcompany','HeadType','StateList','VendorRelation','TDSMaster','Tbl_bgt_expensesubheadingmaster','GRNPayment','VendorAddHead');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
                $this->Auth->allow('tmp_view_branch_vendor','tmp_edit_branch_vendor','grn_payment','grn_payment_salary','vendor_add_head','add_head','add_sub_head');
                                        if(1){$this->Auth->allow('index','imprest_save','imprest_manager_save','get_user','vendor_save','addunit','get_state_gst_code','view_vendor','vendor_save','edit_vendor','tmp_view_vendor','tmp_edit_vendor','add_tds_section','add_head_type','get_tds');}
            else if(1){$this->Auth->allow('index','imprest_save','imprest_manager_save','get_user','vendor_save','addunit','get_state_gst_code','view_vendor','vendor_save','edit_vendor','tmp_view_vendor','tmp_edit_vendor');}
        }
    }
    
    public function add_tds_section()
    {
        $this->layout="home";
        if($this->request->is('POST'))
        {
           //print_r($this->request->data); exit;
          $data = $this->request->data['Imprests'];
          $desc = $data['description'];
          if(!$this->TDSMaster->find('first',array('conditions'=>array('description'=>$desc))))
          {
            if($this->TDSMaster->save($data) )
            {
                $this->Session->setFlash(__('Record Has Been Saved'));
                $this->redirect(array('action'=>'add_tds_section'));
            }
            else
            {
                $this->Session->setFlash(__('Record Has Been Not Saved'));
            }
          }
          else
          {
              $this->Session->setFlash(__('Record Exists. Please Check'));
          }
        }
        $this->set('tds_master',$this->TDSMaster->find('all'));
    }
    
    public function add_head_type()
    {
        $this->layout="home";
        if($this->request->is('POST'))
        {
           //print_r($this->request->data); exit;
          $data = $this->request->data['Imprests'];
          $desc = $data['head_code'];
          
          if(!$this->HeadType->find('first',array('conditions'=>array('head_code'=>$desc))))
          {
            if($this->HeadType->save($data) )
            {
                $this->Session->setFlash(__('Record Has Been Saved'));
                $this->redirect(array('action'=>'add_head_type'));
            }
            else
            {
                $this->Session->setFlash(__('Record Has Been Not Saved'));
            }
          } 
          else
          {
              $this->Session->setFlash(__('Record Exists. Please Check'));
          }
        }
        $this->set('head_master',$this->HeadType->find('all'));
    }
    public function get_user()
    {
        $branchId = $this->request->data['BranchId'];
        $branchArray = $this->Addbranch->query("SELECT tu.id,tu.username FROM tbl_user tu INNER JOIN branch_master bm ON 
tu.branch_name = bm.branch_name WHERE bm.id='$branchId'");
        
        $html = '<option value="">Select</option>';
        foreach($branchArray as $arr)
        {
            $html .= '<option value="'.$arr['tu']['id'].'">'.$arr['tu']['username'].'</option>';
        }
        echo $html;
        
        exit;
    }
    public function imprest_save()
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $ImprestManager = $this->ImprestManager->find('all',array('conditions'=>array('active'=>1),'fields'=>array('Id','UserName','Branch'),
            'order' => array('UserName' => 'asc')));
        
        $imprest_master = array();
        foreach($ImprestManager as $im)
        {
            $imprest_master[$im['ImprestManager']['Id']] = $im['ImprestManager']['UserName'].'   -'.$im['ImprestManager']['Branch'];
        }
        
        $this->set('imprest_master', $imprest_master); //provide Imprest
        
        
        $this->set('bank', $this->Bank->find('list',array('fields'=>array('id','bank_name')))); //bank details
        
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
            
            $imprest = $this->request->data['Imprests'];
            
            $entry_date = explode('-',$imprest['EntryDate']);
            krsort($entry_date);
            $imprest['EntryDate'] = implode('-', $entry_date);
            $imprest['CreateDate'] = date('Y-m-d H:i:s');
            $imprest['UserId'] = $this->Session->read('userid');
            $this->ImprestAllotment->save($imprest);
            //print_r($imprest); exit;
            $this->Session->setFlash(__('New Allotment Has Been Saved'));
        }
        
    }
        
    public function imprest_manager_save()
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
                
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
            $imprest = $this->request->data['Imprests'];
            $userId = $imprest['UserId'];
            $branchId = $imprest['BranchId'];
            $TallyHead = $imprest['TallyHead'];
            if($this->ImprestManager->find('first',array('conditions'=>array('UserId'=>$userId))))
            {
                $this->Session->setFlash(__('User Already Exists'));
            }
            else
            {
               $user =  $this->User->find('first',array('conditions'=>array('id'=>$userId)));
               //print_r($user); exit;
               $data['UserId'] = $user['User']['id'];
               $data['UserName'] = $user['User']['emp_name'];
               $data['EmailId'] = $user['User']['email'];
               $data['TallyHead'] = $TallyHead;
               $data['BranchId'] = $branchId;
               $data['Branch'] = $user['User']['branch_name'];
               $data['CreateDate'] = date('Y-m-d H:i:s');
               
               $this->ImprestManager->save($data);
               $this->Session->setFlash(__('New Imprest Manager '.$user['User']['emp_name'].' Has Been Saved'));
            }
            //print_r($imprest); exit;
            $this->redirect(array('action'=>'imprest_manager_save'));
            
        }
        
    }    
    
   public function vendor_save()
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        
        $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
        $this->set('state_list', $this->StateList->find('list',array('fields'=>array('state_list','state_list'),
            'order' => array('state_list' => 'asc')))); //provide textbox and view branches
        
         $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
        $TdsMaster = $this->TDSMaster->find('all',array('fields'=>array('id','description','section'),'order'=>array('section'=>'asc')));
        foreach($TdsMaster as $tds)
        {
            $NewTdsmaster[$tds['TDSMaster']['id']] = $tds['TDSMaster']['description'].'-'.$tds['TDSMaster']['section'];
        }
        $this->set('TdsMaster',$NewTdsmaster);
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
            //print_r($this->request->data); exit;
            $imprest = $this->request->data['Imprests'];
            
            if($this->VendorMaster->find('first',array('conditions'=>"Vendor='".$imprest['Vendor']."' and Id>660")))
            {
               $this->Session->setFlash(__('Vendor '.$imprest['Vendor'].' Allready Exists')); 
            }
            else
            {
                $flag = true;
                if($imprest['Vendor']=='')
                {
                    $msg = "Please Fill Vendor Name";
                    $flag = false;
                }
                else if($imprest['Address1']=='')
                {
                    $msg = "Please Fill Address First Line";
                    $flag = false;
                }
                else if($imprest['State']=='')
                {
                    $msg = "Please Select State";
                    $flag = false;
                }
                else if($imprest['state_code']=='')
                {
                    $msg = "Please Select State Code";
                    $flag = false;
                }
                else if(substr ($imprest['VendorGST'], 0, 2)!=$imprest['state_code'] && $imprest['GSTEnable']=='1')
                {
                    $msg = "GST No. Not Matched With State Code";
                    $flag = false;
                }
                else if(strlen($imprest['pancard'])!=10 && $imprest['GSTEnable']=='1')
                {
                    $msg = "Pan Card Should be 10 Digits Only";
                    $flag = false;
                }
                else if(strlen($imprest['pincode'])!=6)
                {
                    $msg = "Pin Code Should be in 6 Digits Only";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='')
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='Yes' && empty($data['TDS']))
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['TDSNew']))
                {
                    $msg = "Please Fill Exemption TDS";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['IncomeCertificateCheck']))
                {
                    $msg = "Please Select Do You have Relevant Exemption Exemption Certificate";
                    $flag = false;
                }
//                else if($imprest['paymentFile']['size']==0)
//                {
//                    $msg = "Please Select File";
//                    $flag = false;
//                }
                $vendor = $data['vendor'] = $imprest['Vendor'];
                $data['PanNo'] = $imprest['pancard'];
                $data['ContactNo'] = $imprest['ContactNo'];
                $data['HeadId'] = $imprest['head'];
                $data['SubHeadId'] = $imprest['subhead'];
                $data['ServiceTaxNo'] = $imprest['sevicetax'];
                $data['VendorGST'] = $imprest['VendorGST'];
                $data['CompanyGST'] = $imprest['CompanyGST'];
                $data['TDS'] = $imprest['TDS'];
                $data['TDSSection'] = $imprest['TDSSection'];
                $data['AmountLimit'] = $imprest['AmountLimit'];
                $data['Address1'] = $imprest['Address1'];
                $data['Address2'] = $imprest['Address2'];
                $data['Address3'] = $imprest['Address3'];
                $data['Address4'] = $imprest['Address4'];
                $data['Address5'] = $imprest['Address5'];
                $data['state'] = $imprest['State'];
                $data['state_code'] = $imprest['state_code'];
                $data['VendorGST'] = $imprest['VendorGST'];
                $data['PanNo'] = $imprest['pancard'];
                $data['pincode'] = $imprest['pincode'];
                $data['TDSEnabled'] = $imprest['TDSEnabled'];
                
                $data['TDSSection'] = $imprest['TDSSection'];
                $data['TDS'] = $imprest['TDS'];
                $data['TDSChange'] = $imprest['TDSChange'];
                $data['TDSNew'] = $imprest['TDSNew'];
                $data['IncomeCertificateCheck'] = $imprest['IncomeCertificateCheck'];
                
                if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && $imprest['IncomeCertificateCheck']=='Yes')
                {
                    $data['TDS'] = $imprest['TDSNew'];
                }
                
                if(empty($this->request->data['as_bill_to']))
                {
                    $data['as_bill_to'] = 0;
                }
                else
                {
                    $data['as_bill_to'] = 1;
                }
                
                $data['createby'] = $this->Session->read('userid');
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['GSTEnabled'] = $imprest['GSTEnable'];
                $data['active'] = "0";
                $GSTEnable = $imprest['GSTEnable'];
                
                if($this->VendorMaster->save($data))
                {
                    $Transaction = $this->User->getDataSource();
                    $Transaction->begin();
                    
                    $VendorId = $this->VendorMaster->getLastInsertID(); 
                    
                    $this->VendorMaster->query("INSERT INTO `tbl_voucher_entries` SET entry_type='vendor',part_id='$VendorId',part_name='$vendor',createdate=NOW()");
                    
                    
                
                    $flag = false;
                    if(!empty($imprest['paymentFile']['name']))
                    {
                         $file = $imprest['paymentFile'];
                         //print_r($file); exit;
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         if(move_uploaded_file($file['tmp_name'],WWW_ROOT."/Vendor/".$VendorId.$file['name']))
                         {
                             $flag = true;
                            $PaymentFile =addslashes($VendorId.$file['name']);
                            $this->VendorMaster->updateAll(array('bill_file'=>"'$PaymentFile'"),array('Id'=>$VendorId));                             
                         }
                         else
                         {
                             $flag = false;
                            $this->Session->setFlash("Please Attach File");
                         }
                    }
                        
                
                    if($flag)
                    {
                        $Transaction->commit();
                        $BranchArr = $this->request->data['branch'];
                        $compArr = $this->request->data['comp'];
                        $this->VendorRelation->query("Insert into vendor_expense_relation set VendorId='$VendorId',HeadId='".$imprest['head']."',SubHeadId='".$imprest['subhead']."'");
                        foreach($BranchArr as $Br)
                        {
                            //$compArr = $this->request->data['comp'.$Br];
        //                    foreach($compArr as $comp)
        //                    {
        //                        $BrDetails = array(); $GSTEnable=0;$GSTType='';$GSTNo='';
        //                        
        //                        $GSTEnableArr = $this->request->data['GSTEnable'.$Br];
        //                        if(in_array($comp, $GSTEnableArr))
        //                        {
        //                            $GSTEnable = 1;
        //                            $GSTType = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTType'];
        //                            $GSTNo = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTNo'];                            
        //                        }
        //                        $BrDetails['VendorId'] = $VendorId;
        //                        $BrDetails['BranchId'] = $Br;
        //                        $BrDetails['CompId'] = $comp;
        //                        $BrDetails['GSTEnable'] = $GSTEnable;
        //                        $BrDetails['GSTType'] = $GSTType;
        //                        $BrDetails['GSTNo'] = $GSTNo;
        //                        $BrDetails['CreateDate'] = date('Y-m-d H:i:s');
        //                        $VendorRelation[] = $BrDetails;
        //                    }
                            foreach($compArr as $comp)
                            {
                                $VendorRelation[] = array("VendorId"=>$VendorId,"BranchId"=>$Br,"GSTEnable"=>$GSTEnable,'CompId'=>$comp,"CreateDate"=>date('Y-m-d H:i:s'));
                            }
                            
                        }
                        $this->VendorRelation->saveMany($VendorRelation);

                        $this->Session->setFlash(__('New vendor  '.$imprest['Vendor'].' Has Been Added. and moved to Approval Bucket'));
                        $this->redirect(array('action'=>'vendor_save'));
                    }
                    else
                    {
                        $Transaction->rollback();
                    }
                }
                
            }   
            
            
            
            
            //print_r($imprest); exit;
            
            
        }
        
    }   
    
    public function tmp_view_vendor()
    {
        $this->layout="home";
        $this->set("vendor_arr",$this->VendorMaster->find('all',array('conditions'=>"Id>660 and active=0 and Reject=1","order"=>array("vendor"=>"asc")))); 
        
    }
    public function tmp_edit_vendor()
     {
        $this->layout="home";
        $VendorId = $Id = $this->params->query['Id'];
        $vendor_arr = $this->VendorMaster->find('first',array('conditions'=>array('Id'=>$VendorId,'Reject'=>1,'active'=>0)));
        $this->set("vendor_arr",$vendor_arr); 
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide select box and view branches
        $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
        $this->set('state_list', $this->StateList->find('list',array('fields'=>array('state_list','state_list'),
            'order' => array('state_list' => 'asc')))); //provide textbox and view branches
        
        
         $TdsMaster = $this->TDSMaster->find('all',array('fields'=>array('id','description','section'),'order'=>array('section'=>'asc')));
        foreach($TdsMaster as $tds)
        {
            $NewTdsmaster[$tds['TDSMaster']['id']] = $tds['TDSMaster']['description'].'-'.$tds['TDSMaster']['section'];
        }
        $this->set('TdsMaster',$NewTdsmaster);
        
        
        $rel = $this->VendorRelation->query("SELECT * FROM vendor_expense_relation ver WHERE VendorId = '$VendorId'");
        $this->set('rel',$rel);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
        $this->set('subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array("HeadingId"=>$rel['0']['ver']['HeadId']),"order"=>array("SubHeadingDesc"=>"asc"))));
        $this->set('vendor_relation',$this->VendorRelation->find('list',array('fields'=>array('Id','BranchId'),'conditions'=>array('VendorId'=>$VendorId))));
        $this->set('relation_comp',$this->VendorRelation->find('list',array('fields'=>array('Id','CompId'),'conditions'=>array('VendorId'=>$VendorId))));
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
             
           if($this->request->data['Submit']=='Approve') 
           {
              $imprest = $this->request->data['Imprests'];
             $Id = $imprest['Id'];
            
            
            
                $flag = true;
                if($imprest['Vendor']=='')
                {
                    $msg = "Please Fill Vendor Name";
                    $flag = false;
                }
                if($imprest['TallyHead']=='')
                {
                    $msg = "Please Fill Vendor Name(Tally)";
                    $flag = false;
                }
                else if($imprest['Address1']=='')
                {
                    $msg = "Please Fill Address First Line";
                    $flag = false;
                }
                else if($imprest['State']=='')
                {
                    $msg = "Please Select State";
                    $flag = false;
                }
                else if($imprest['state_code']=='')
                {
                    $msg = "Please Select State Code";
                    $flag = false;
                }
                else if(substr ($imprest['VendorGST'], 0, 2)!=$imprest['state_code'])
                {
                    $msg = "GST No. Not Matched With State Code";
                    $flag = false;
                }
                else if(strlen($imprest['pancard'])!=10)
                {
                    $msg = "Pan Card Should be 10 Digits Only";
                    $flag = false;
                }
                else if(strlen($imprest['pincode'])!=6)
                {
                    $msg = "Pin Code Should be in 6 Digits Only";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='')
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='Yes' && empty($data['TDS']))
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['TDSNew']))
                {
                    $msg = "Please Fill Exemption TDS";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['IncomeCertificateCheck']))
                {
                    $msg = "Please Select Do You have Relevant Exemption Exemption Certificate";
                    $flag = false;
                }
//                else if($imprest['paymentFile']['size']==0)
//                {
//                    $msg = "Please Select File";
//                    $flag = false;
//                }
                $data['vendor'] = "'".$imprest['Vendor']."'";
                $data['TallyHead'] = "'".$imprest['TallyHead']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['ContactNo'] = "'".$imprest['ContactNo']."'";
                
                $data['ServiceTaxNo'] = "'".$imprest['sevicetax']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['Address1'] = "'".$imprest['Address1']."'";
                $data['Address2'] = "'".$imprest['Address2']."'";
                $data['Address3'] = "'".$imprest['Address3']."'";
                $data['state'] = "'".$imprest['State']."'";
                $data['state_code'] = "'".$imprest['state_code']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['pincode'] = "'".$imprest['pincode']."'";
                $data['GSTEnabled'] = "'".$imprest['GSTEnable']."'";
                $GSTEnable = $imprest['GSTEnable'];
                $data['ApproveBy'] = "'".$this->Session->read('userid')."'";
                $data['ApproveDate'] = "'".date('Y-m-d H:i:s')."'";
                $data['active'] = "1";
                $data['TDSEnabled'] = "'".$imprest['TDSEnabled']."'";
                
                $data['TDSSection'] = "'".$imprest['TDSSection']."'";
                $data['TDS'] = "'".$imprest['TDS']."'";
                $data['TDSChange'] = "'".$imprest['TDSChange']."'";
                $data['TDSNew'] = "'".$imprest['TDSNew']."'";
                $data['IncomeCertificateCheck'] = "'".$imprest['IncomeCertificateCheck']."'";
                
                if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && $imprest['IncomeCertificateCheck']=='Yes')
                {
                    $data['TDS'] = "'".$imprest['TDSNew']."'";
                }
                
                
                if($this->VendorMaster->updateAll($data,array('Id'=>$Id)))
                {
                   if(!empty($imprest['paymentFile']['name']))
                    {
                         $file = $imprest['paymentFile'];
                         //print_r($file); exit;
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         if(move_uploaded_file($file['tmp_name'],WWW_ROOT."/Vendor/".$VendorId.$file['name']))
                         {
                            $flag = true;
                            $PaymentFile =addslashes($VendorId.$file['name']);
                            $this->VendorMaster->updateAll(array('bill_file'=>"'$PaymentFile'"),array('Id'=>$VendorId));
                         }
                         else
                         {
                             $flag = false;
                            $this->Session->setFlash("Please Attach File");
                         }
                    }
                
                    //$VendorId = $this->VendorMaster->getLastInsertID(); 
                    $BranchArr = $this->request->data['branch'];
                    $compArr = $this->request->data['comp'];
                    $this->VendorRelation->deleteAll(array('VendorId'=>$VendorId));
                
                    $this->VendorRelation->query("update vendor_expense_relation set HeadId='".$imprest['head']."',SubHeadId='".$imprest['subhead']."' where VendorId = '$VendorId'");
                
                
                    foreach($BranchArr as $Br)
                    {
                        //$compArr = $this->request->data['comp'.$Br];
    //                    foreach($compArr as $comp)
    //                    {
    //                        $BrDetails = array(); $GSTEnable=0;$GSTType='';$GSTNo='';
    //                        
    //                        $GSTEnableArr = $this->request->data['GSTEnable'.$Br];
    //                        if(in_array($comp, $GSTEnableArr))
    //                        {
    //                            $GSTEnable = 1;
    //                            $GSTType = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTType'];
    //                            $GSTNo = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTNo'];                            
    //                        }
    //                        $BrDetails['VendorId'] = $VendorId;
    //                        $BrDetails['BranchId'] = $Br;
    //                        $BrDetails['CompId'] = $comp;
    //                        $BrDetails['GSTEnable'] = $GSTEnable;
    //                        $BrDetails['GSTType'] = $GSTType;
    //                        $BrDetails['GSTNo'] = $GSTNo;
    //                        $BrDetails['CreateDate'] = date('Y-m-d H:i:s');
    //                        $VendorRelation[] = $BrDetails;
    //                    }
                        foreach($compArr as $comp)
                        {
                            $VendorRelation[] = array("VendorId"=>$VendorId,"BranchId"=>$Br,"GSTEnable"=>$GSTEnable,'CompId'=>$comp,"CreateDate"=>date('Y-m-d H:i:s'));
                        }

                    }
                    $this->VendorRelation->saveAll($VendorRelation);
                    //print_r($VendorRelation); exit;

                    $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Has Been Approved Successfully'));
                    $this->redirect(array('action'=>'tmp_view_vendor'));  
                }
                else
                {
                    $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Not Saved. Please Try Again!'));
                } 
           }
           else
           {
               $imprest = $this->request->data['Imprests'];
               $Id = $imprest['Id']; 
              
               
               $RejectRemarks = addslashes($this->request->data['RejectRemarks']);
               $RejectDate = "'".date('Y-m-d H:i:s')."'";
               $RejectBy = $this->Session->read('userid');
               
               if($this->VendorMaster->updateAll(array('Reject'=>0,'RejectRemarks'=>"'".$RejectRemarks."'",'RejectDate'=>$RejectDate,'RejectBy'=>$RejectBy),array('Id'=>$Id)))
               {
                    $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Has Been DisApproved And Moved TO Branch Bucket Successfully'));
                    $this->redirect(array('action'=>'tmp_view_vendor'));  
               }
               else
               {
                   $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Has Been Not DisApproved. Please Try Again'));
                    $this->redirect(array('action'=>'tmp_view_vendor'));  
               }
           }
            
        }
    }
    
    public function tmp_view_branch_vendor()
    {
        $this->layout="home";
        $userid = $this->Session->read('userid');
        $this->set("vendor_arr",$this->VendorMaster->find('all',array('conditions'=>"Id>660 and active=0 and createby='$userid'","order"=>array("vendor"=>"asc")))); 
    }
    public function tmp_edit_branch_vendor()
     {
        $this->layout="home";
        $VendorId = $Id = $this->params->query['Id'];
        $vendor_arr = $this->VendorMaster->find('first',array('conditions'=>array('Id'=>$VendorId,'Reject'=>0,'active'=>0)));
        $this->set("vendor_arr",$vendor_arr); 
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide select box and view branches
        $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
        $this->set('state_list', $this->StateList->find('list',array('fields'=>array('state_list','state_list'),
            'order' => array('state_list' => 'asc')))); //provide textbox and view branches
        
        $rel = $this->VendorRelation->query("SELECT * FROM vendor_expense_relation ver WHERE VendorId = '$VendorId'");
        $this->set('rel',$rel);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
        $this->set('subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array("HeadingId"=>$rel['0']['ver']['HeadId']),"order"=>array("SubHeadingDesc"=>"asc"))));
        $this->set('vendor_relation',$this->VendorRelation->find('list',array('fields'=>array('Id','BranchId'),'conditions'=>array('VendorId'=>$VendorId))));
        $this->set('relation_comp',$this->VendorRelation->find('list',array('fields'=>array('Id','CompId'),'conditions'=>array('VendorId'=>$VendorId))));
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
             

              $imprest = $this->request->data['Imprests'];
             $Id = $imprest['Id'];
            
            
            
                $flag = true;
                if($imprest['Vendor']=='')
                {
                    $msg = "Please Fill Vendor Name";
                    $flag = false;
                }
                if($imprest['TallyHead']=='')
                {
                    $msg = "Please Fill Vendor Name(Tally)";
                    $flag = false;
                }
                else if($imprest['Address1']=='')
                {
                    $msg = "Please Fill Address First Line";
                    $flag = false;
                }
                else if($imprest['State']=='')
                {
                    $msg = "Please Select State";
                    $flag = false;
                }
                else if($imprest['state_code']=='')
                {
                    $msg = "Please Select State Code";
                    $flag = false;
                }
                else if(substr ($imprest['VendorGST'], 0, 2)!=$imprest['state_code'])
                {
                    $msg = "GST No. Not Matched With State Code";
                    $flag = false;
                }
                else if(strlen($imprest['pancard'])!=10)
                {
                    $msg = "Pan Card Should be 10 Digits Only";
                    $flag = false;
                }
                else if(strlen($imprest['pincode'])!=6)
                {
                    $msg = "Pin Code Should be in 6 Digits Only";
                    $flag = false;
                }
//                else if($imprest['paymentFile']['size']==0)
//                {
//                    $msg = "Please Select File";
//                    $flag = false;
//                }
                $data['vendor'] = "'".$imprest['Vendor']."'";
                $data['TallyHead'] = "'".$imprest['TallyHead']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['ContactNo'] = "'".$imprest['ContactNo']."'";
                
                $data['ServiceTaxNo'] = "'".$imprest['sevicetax']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['Address1'] = "'".$imprest['Address1']."'";
                $data['Address2'] = "'".$imprest['Address2']."'";
                $data['Address3'] = "'".$imprest['Address3']."'";
                $data['state'] = "'".$imprest['State']."'";
                $data['state_code'] = "'".$imprest['state_code']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['pincode'] = "'".$imprest['pincode']."'";
                $data['GSTEnabled'] = "'".$imprest['GSTEnable']."'";
               $data['RejectRemarks'] = "''";
               $data['Reject'] = "'1'";
               
                
                $GSTEnable = $imprest['GSTEnable'];
                
                if($this->VendorMaster->updateAll($data,array('Id'=>$Id)))
                {
                   if(!empty($imprest['paymentFile']['name']))
                    {
                       
                         $file = $imprest['paymentFile'];
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         if(move_uploaded_file($file['tmp_name'],WWW_ROOT."/Vendor/".$VendorId.$file['name']))
                         {
                            $flag = true;
                            $PaymentFile =addslashes($VendorId.$file['name']);
                            $this->VendorMaster->updateAll(array('bill_file'=>"'$PaymentFile'"),array('Id'=>$VendorId));
                         }
                         else
                         {
                             $flag = false;
                            $this->Session->setFlash("Please Attach File");
                         }
                    }
                    
                    //$VendorId = $this->VendorMaster->getLastInsertID(); 
                    $BranchArr = $this->request->data['branch'];
                    $compArr = $this->request->data['comp'];
                    $this->VendorRelation->deleteAll(array('VendorId'=>$VendorId));
                
                    $this->VendorRelation->query("update vendor_expense_relation set HeadId='".$imprest['head']."',SubHeadId='".$imprest['subhead']."' where VendorId = '$VendorId'");
                
                
                    foreach($BranchArr as $Br)
                    {
                        //$compArr = $this->request->data['comp'.$Br];
    //                    foreach($compArr as $comp)
    //                    {
    //                        $BrDetails = array(); $GSTEnable=0;$GSTType='';$GSTNo='';
    //                        
    //                        $GSTEnableArr = $this->request->data['GSTEnable'.$Br];
    //                        if(in_array($comp, $GSTEnableArr))
    //                        {
    //                            $GSTEnable = 1;
    //                            $GSTType = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTType'];
    //                            $GSTNo = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTNo'];                            
    //                        }
    //                        $BrDetails['VendorId'] = $VendorId;
    //                        $BrDetails['BranchId'] = $Br;
    //                        $BrDetails['CompId'] = $comp;
    //                        $BrDetails['GSTEnable'] = $GSTEnable;
    //                        $BrDetails['GSTType'] = $GSTType;
    //                        $BrDetails['GSTNo'] = $GSTNo;
    //                        $BrDetails['CreateDate'] = date('Y-m-d H:i:s');
    //                        $VendorRelation[] = $BrDetails;
    //                    }
                        foreach($compArr as $comp)
                        {
                            $VendorRelation[] = array("VendorId"=>$VendorId,"BranchId"=>$Br,"GSTEnable"=>$GSTEnable,'CompId'=>$comp,"CreateDate"=>date('Y-m-d H:i:s'));
                        }

                    }
                    $this->VendorRelation->saveAll($VendorRelation);
                    //print_r($VendorRelation); exit;

                    $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Has Been Moved For Approval'));
                    $this->redirect(array('action'=>'tmp_view_branch_vendor'));  
                }
                else
                {
                    $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Not Saved. Please Try Again!'));
                } 
           
           
            
        }
    }
    
    public function view_vendor()
    {
        $this->layout="home";
        $this->set("vendor_arr",$this->VendorMaster->find('all',array('conditions'=>"Id>660 and active=1 and vendor !='Previous Entry'","order"=>array("vendor"=>"asc"))));  
    }
    public function edit_vendor()
    {
        $this->layout="home";
        $VendorId = $Id = $this->params->query['Id'];
        $vendor_arr = $this->VendorMaster->find('first',array('conditions'=>array('Id'=>$VendorId,'active'=>1)));
        $this->set("vendor_arr",$vendor_arr); 
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide select box and view branches
        $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
        $this->set('state_list', $this->StateList->find('list',array('fields'=>array('state_list','state_list'),
            'order' => array('state_list' => 'asc')))); //provide textbox and view branches
        
        $rel = $this->VendorRelation->query("SELECT * FROM vendor_expense_relation ver WHERE VendorId = '$VendorId'");
        $this->set('rel',$rel);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
        $this->set('subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array("HeadingId"=>$rel['0']['ver']['HeadId']),"order"=>array("SubHeadingDesc"=>"asc"))));
        $this->set('vendor_relation',$this->VendorRelation->find('list',array('fields'=>array('Id','BranchId'),'conditions'=>array('VendorId'=>$VendorId))));
        $this->layout='home';
        $this->set('relation_comp',$this->VendorRelation->find('list',array('fields'=>array('Id','CompId'),'conditions'=>array('VendorId'=>$VendorId))));
         $TdsMaster = $this->TDSMaster->find('all',array('fields'=>array('id','description','section'),'order'=>array('section'=>'asc')));
        foreach($TdsMaster as $tds)
        {
            $NewTdsmaster[$tds['TDSMaster']['id']] = $tds['TDSMaster']['description'].'-'.$tds['TDSMaster']['section'];
        }
        $this->set('TdsMaster',$NewTdsmaster);
        
        if($this->request->is('Post'))
        {
            
            
            
            //print_r($this->request->data); exit;
            $imprest = $this->request->data['Imprests'];
            $Id = $imprest['Id'];
            $as_bill_to = $this->request->data['Imprests']['as_bill_to'];
            
            
                $flag = true;
                if($imprest['Vendor']=='')
                {
                    $msg = "Please Fill Vendor Name";
                    $flag = false;
                }
                else if($imprest['Address1']=='')
                {
                    $msg = "Please Fill Address First Line";
                    $flag = false;
                }
                else if($imprest['State']=='')
                {
                    $msg = "Please Select State";
                    $flag = false;
                }
                else if($imprest['state_code']=='')
                {
                    $msg = "Please Select State Code";
                    $flag = false;
                }
                else if(substr ($imprest['VendorGST'], 0, 2)!=$imprest['state_code'])
                {
                    $msg = "GST No. Not Matched With State Code";
                    $flag = false;
                }
                else if(strlen($imprest['pancard'])!=10)
                {
                    $msg = "Pan Card Should be 10 Digits Only";
                    $flag = false;
                }
                else if(strlen($imprest['pincode'])!=6)
                {
                    $msg = "Pin Code Should be in 6 Digits Only";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='')
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSSection']=='Yes' && empty($data['TDS']))
                {
                    $msg = "Please Select TDS Section";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['TDSNew']))
                {
                    $msg = "Please Fill Exemption TDS";
                    $flag = false;
                }
                else if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && empty($imprest['IncomeCertificateCheck']))
                {
                    $msg = "Please Select Do You have Relevant Exemption Exemption Certificate";
                    $flag = false;
                }
                
//                else if($imprest['paymentFile']['size']==0)
//                {
//                    $msg = "Please Select File";
//                    $flag = false;
//                }
                $data['vendor'] = "'".$imprest['Vendor']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['ContactNo'] = "'".$imprest['ContactNo']."'";
                
                $data['ServiceTaxNo'] = "'".$imprest['sevicetax']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['Address1'] = "'".$imprest['Address1']."'";
                $data['Address2'] = "'".$imprest['Address2']."'";
                $data['Address3'] = "'".$imprest['Address3']."'";
                $data['state'] = "'".$imprest['State']."'";
                $data['state_code'] = "'".$imprest['state_code']."'";
                $data['VendorGST'] = "'".$imprest['VendorGST']."'";
                $data['PanNo'] = "'".$imprest['pancard']."'";
                $data['pincode'] = "'".$imprest['pincode']."'";
                $data['bill_file'] = "'".$imprest['paymentFile']."'";
                $data['GSTEnabled'] = "'".$imprest['GSTEnable']."'";
                $data['TDSTallyHead'] = "'".$imprest['TDSTallyHead']."'";
                $data['active'] = "1";
                $data['TDSEnabled'] = "'".$imprest['TDSEnabled']."'";
                
                $data['TDSSection'] = "'".$imprest['TDSSection']."'";
                $data['TDS'] = "'".$imprest['TDS']."'";
                $data['TDSChange'] = "'".$imprest['TDSChange']."'";
                //print_r($imprest['as_bill_to']); exit;
                $data['as_bill_to'] = "'".$imprest['as_bill_to']."'";
                $data['TDSNew'] = "'".$imprest['TDSNew']."'";
                $data['IncomeCertificateCheck'] = "'".$imprest['IncomeCertificateCheck']."'";
                
                if($data['TDSEnabled']=='1' && $imprest['TDSChange']=='Yes' && $imprest['IncomeCertificateCheck']=='Yes')
                {
                    $data['TDS'] = "'".$imprest['TDSNew']."'";
                }
                
                
                if(empty($as_bill_to))
                {
                    $data['as_bill_to'] = 0;
                }
                else
                {
                    $data['as_bill_to'] = 1;
                }
                
                $GSTEnable = $imprest['GSTEnable'];;
                $this->VendorMaster->updateAll($data,array('Id'=>$Id));
                //$VendorId = $this->VendorMaster->getLastInsertID(); 
                $BranchArr = $this->request->data['branch'];
                //print_r($BranchArr); exit;
                $this->VendorRelation->deleteAll(array('VendorId'=>$VendorId));
                $compArr = $this->request->data['comp'];
                foreach($BranchArr as $Br)
                {
                    //$compArr = $this->request->data['comp'.$Br];
//                    foreach($compArr as $comp)
//                    {
//                        $BrDetails = array(); $GSTEnable=0;$GSTType='';$GSTNo='';
//                        
//                        $GSTEnableArr = $this->request->data['GSTEnable'.$Br];
//                        if(in_array($comp, $GSTEnableArr))
//                        {
//                            $GSTEnable = 1;
//                            $GSTType = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTType'];
//                            $GSTNo = $this->request->data['Branch'][$Br]['comp'][$comp]['GSTNo'];                            
//                        }
//                        $BrDetails['VendorId'] = $VendorId;
//                        $BrDetails['BranchId'] = $Br;
//                        $BrDetails['CompId'] = $comp;
//                        $BrDetails['GSTEnable'] = $GSTEnable;
//                        $BrDetails['GSTType'] = $GSTType;
//                        $BrDetails['GSTNo'] = $GSTNo;
//                        $BrDetails['CreateDate'] = date('Y-m-d H:i:s');
//                        $VendorRelation[] = $BrDetails;
//                    }
                    
                    foreach($compArr as $comp)
                        {
                            $VendorRelation[]['VendorRelation'] = array("VendorId"=>$VendorId,"BranchId"=>$Br,"GSTEnable"=>$GSTEnable,'CompId'=>$comp,"CreateDate"=>date('Y-m-d H:i:s'));
                            $this->VendorRelation->saveAll(array("VendorId"=>$VendorId,"BranchId"=>$Br,"GSTEnable"=>$GSTEnable,'CompId'=>$comp,"CreateDate"=>date('Y-m-d H:i:s')));
                        }
                }
                
                
                
                //print_r($VendorRelation); exit;
                
                 
                
                $this->Session->setFlash(__(' vendor  '.$imprest['Vendor'].' Has Been Update Successfully'));
                $this->redirect(array('action'=>'view_vendor'));
        }
    }
    
    
     public function addunit()
{
    $this->layout = "home";
    $all = array();
    
    $role = $this->Session->read('role');
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $qry = "";
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
            $qry = "and tu.Branch='".$this->Session->read("branch_name")."'";
        }
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $branchMaster = $branchMaster2;
    
    $this->set('branch_master',$branchMaster);
    $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    
    if($this->request->is('POST'))
    {
        $Expense = $this->request->data['Imprests'];
       $data['Branch']= $Expense['branch_name'];
       $data['HeadingID']= $Expense['head'];
       $data['SubHeadingID']= $Expense['subhead'];
       $data['ExpenseUnit'] = $Expense['ExpenceUnit'];
        $data['SavedBy'] = $this->Session->read('username');
        $data['Status'] ='1';
         $data['EntryDate'] =  date("Y-m-d H:i:s");
         
        $check =  $this->Tbl_bgt_expenseunitmaster->query("SELECT * FROM `tbl_expenseunitmaster` WHERE `Branch` ='{$data['branch_name']}' AND `HeadingID`='{$data['head']}' AND `SubHeadingID` ='{$data['subhead']}' AND `ExpenseUnit` ='{$data['ExpenceUnit']}' ");
        if(empty($check))
        {
          $this->Tbl_bgt_expenseunitmaster->save($data);  
          $this->Session->setFlash(__($data['ExpenceUnit'].'Unit Saved successfully'));
        }
 else {
     $this->Session->setFlash(__($data['ExpenceUnit'].' Unit Allready Exists'));
 }
        
       
       
    } 
    
    $this->set('UnitReport', $this->Tbl_bgt_expenseunitmaster->query("SELECT Branch,HeadingDesc,SubHeadingDesc,ExpenseUnit FROM tbl_expenseunitmaster tu INNER JOIN
tbl_bgt_expenseheadingmaster hm ON tu.HeadingId = hm.HeadingId
INNER JOIN 
tbl_bgt_expensesubheadingmaster shm
ON shm.SubHeadingId = tu.SubHeadingId
WHERE tu.Status='1' $qry"));
}
    
 public function get_state_gst_code()
{
   $this->layout="ajax";
   $Id = $this->request->data['Id'];
   $StateArr = $this->StateList->find('first',array('fields'=>array('state_code'),'conditions'=>array("state_list"=>$Id)));
   echo $StateArr['StateList']['state_code']; exit;
}   

public function grn_payment()
{
     $this->layout="home";
     $this->set('Vendor',$this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>"Id>660",'order'=>array('vendor'=>'asc')))) ;
     $this->set('Imprest',$this->ImprestManager->find('list',array('conditions'=>array('active'=>1),'fields'=>array('Id','UserName'),
            'order' => array('UserName' => 'asc'))));
     
     if($this->request->is('POST'))
     {
         
         $data['GRNPayment'] = $this->request->data['Imprests'];
         $data['GRNPayment']['createdate'] = date('Y-m-d H:i:s');
         $data['GRNPayment']['createby'] = $this->Session->read('userid');
         
         if($this->GRNPayment->save($data))
         {
             $this->Session->setFlash(__('Records Saved successfully'));
             $this->redirect(array('action'=>'grn_payment'));
         }
         else
         {
             $this->Session->setFlash(__('Record Not Saved. Please Try Again'));
         }
     }
}
public function grn_payment_approve()
{
     $this->layout="home";
     $this->set('Vendor',$this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>"Id>660",'order'=>array('vendor'=>'asc')))) ;
     $this->set('Imprest',$this->ImprestManager->find('list',array('conditions'=>array('active'=>1),'fields'=>array('Id','UserName'),
            'order' => array('UserName' => 'asc'))));
     
     if($this->request->is('POST'))
     {
         
         $data['GRNPayment'] = $this->request->data['Imprests'];
         $data['GRNPayment']['createdate'] = date('Y-m-d H:i:s');
         $data['GRNPayment']['createby'] = $this->Session->read('userid');
         
         if($this->GRNPayment->save($data))
         {
             $this->Session->setFlash(__('Records Saved successfully'));
             $this->redirect(array('action'=>'grn_payment'));
         }
         else
         {
             $this->Session->setFlash(__('Record Not Saved. Please Try Again'));
         }
     }
}

public function grn_payment_salary()
{
     $this->layout="home";
     $this->set('Vendor',$this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>"Id>660",'order'=>array('vendor'=>'asc')))) ;
     $this->set('Imprest',$this->ImprestManager->find('list',array('conditions'=>array('active'=>1),'fields'=>array('Id','UserName'),
            'order' => array('UserName' => 'asc'))));
     
     if($this->request->is('POST'))
     {
         
         $data['GRNPayment'] = $this->request->data['Imprests'];
         $data['GRNPayment']['createdate'] = date('Y-m-d H:i:s');
         $data['GRNPayment']['createby'] = $this->Session->read('userid');
         
         if($this->GRNPayment->save($data))
         {
             $this->Session->setFlash(__('Records Saved successfully'));
             $this->redirect(array('action'=>'grn_payment_salary'));
         }
         else
         {
             $this->Session->setFlash(__('Record Not Saved. Please Try Again'));
         }
     }
}

public function vendor_add_head()
    {
        $this->layout="home";
        
       
        $this->set('Vendor', $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>"Id>660 and vendor!='Previous Entry'",'order'=>array('vendor'=>'asc'))));
        if($this->request->is('Post'))
        {
            $data['VendorAddHead']['HeadId'] = $this->request->data['Imprest']['HeadingId'];
            $data['VendorAddHead']['SubHeadId'] = $this->request->data['Imprest']['SubHeadingId'];
            $data['VendorAddHead']['VendorId'] = $this->request->data['Imprest']['Vendor'];
            
            $qry = "Select Id from vendor_expense_relation where VendorId='".$data['VendorRelation']['VendorId']."' and HeadId='".$data['VendorRelation']['HeadId']."' and SubHeadId='".$data['VendorRelation']['SubHeadId']."'";
            if(!empty($this->VendorRelation->query($qry)))
            {
                $this->Session->setFlash(__('Vendor Sub Head Already Exists'));
            }
            else
            {
                if($this->VendorAddHead->save($data))
                {
                    $this->Session->setFlash(__(' Vendor Sub Head Successfully'));
                    $this->redirect(array('action'=>'vendor_add_head'));
                }
                else
                {
                    $this->Session->setFlash(__(' Vendor Sub Head Has Been Not Updated Successfully'));
                }
            }
        }
        $this->set('vendor',$this->VendorMaster->query("SELECT vm.vendor,HeadingDesc,SubHeadingDesc FROM tbl_vendormaster vm INNER JOIN `vendor_expense_relation` ver ON vm.Id = ver.VendorId
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON head.HeadingId = ver.HeadId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON ver.HeadId = subhead.HeadingId AND ver.SubHeadId = subhead.SubHeadingId where vm.Id>661 and vm.vendor !='Previous Entry'"));
        
        $this->set('head1',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
        $this->set('subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>"LENGTH(HeadingId)<3","order"=>array("SubHeadingDesc"=>"asc"))));
        
    }
 public function add_head()
    {
        $this->layout="home";
        if($this->request->is('Post'))
        {
            if($this->request->data['submit']=='Add')
            {
                $data['Tbl_bgt_expenseheadingmaster']['HeadingDesc'] = $this->request->data['Imprest']['HeadingDesc'];
                $data['Tbl_bgt_expenseheadingmaster']['Cost'] = $this->request->data['Imprest']['Cost'];
                
                $MaxArr = $this->Tbl_bgt_expenseheadingmaster->query("SELECT MAX(OrderPriority) maxId FROM `tbl_bgt_expenseheadingmaster` WHERE LENGTH(HeadingId)<3");
                
                $OrderPriority = round($MaxArr['0']['0']['maxId'])+1; 
                $data['Tbl_bgt_expenseheadingmaster']['OrderPriority'] = $OrderPriority;
                
                $MaxArr = $this->Tbl_bgt_expenseheadingmaster->query("SELECT MAX(CONVERT(HeadingId,UNSIGNED INTEGER)) maxId FROM `tbl_bgt_expenseheadingmaster` WHERE LENGTH(HeadingId)<3");
                //print_r($MaxArr); exit;
                $Id = round($MaxArr['0']['0']['maxId'])+1; 
                $data['Tbl_bgt_expenseheadingmaster']['HeadingId'] = $Id;

                if($this->Tbl_bgt_expenseheadingmaster->find('first',array('conditions'=>"HeadingDesc='".$data['Tbl_bgt_expenseheadingmaster']['HeadingDesc']."' and HeadingId>660")))
                {
                    $this->Session->setFlash(__(' Expense  Head '.$data['Tbl_bgt_expenseheadingmaster']['HeadingDesc'].' Already Exists'));
                }
                else
                {
                    if($this->Tbl_bgt_expenseheadingmaster->save($data))
                    {
                        $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expenseheadingmaster']['HeadingDesc'].' Has Been Added Successfully'));
                        $this->redirect(array('action'=>'add_head'));
                    }
                    else
                    {
                        $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expenseheadingmaster']['HeadingDesc'].' Has Been Not Updated Successfully'));
                    }
                }
            }
            else if($this->request->data['submit']=='Update')
            {
                
                $HeadArr = $this->request->data['Imprest'];
               // print_r($HeadArr); exit;
                $ArrHeadId = explode(',',$HeadArr['ExpenseHeadArr']);
                $Orderby =array();
                $flag = true;
                foreach($ArrHeadId as $HeadId)
                {
                    if(in_array($HeadArr['OrderBy'.$HeadId],$Orderby))
                    {
                        $flag = false;
                        echo $HeadArr['OrderBy'.$HeadId]; exit;
                        break;
                    }
                    else
                    {
                        $Orderby[] = $HeadArr['OrderBy'.$HeadId];
                    }
                }
                
                if($flag)
                {
                    foreach($ArrHeadId as $HeadId)
                    {  
                        $this->Tbl_bgt_expenseheadingmaster->query("update tbl_bgt_expenseheadingmaster set Cost='".$HeadArr['Cost'.$HeadId]."',OrderPriority='".$HeadArr['OrderBy'.$HeadId]."' where HeadingId='$HeadId'");                        
                    }
                    $this->Session->setFlash(__(' Expense Heads Has Been Updated Successfully'));
                    $this->redirect(array('action'=>'add_head'));
                }
                else
                {
                    $this->Session->setFlash(__(' Expense Head  Ordering MisMatched'));
                    $this->redirect(array('action'=>'add_head'));
                }
                
            }
            
        }
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('all',array('fields'=>array('HeadingId','HeadingDesc','Cost','OrderPriority'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
    }
    public function get_tds()
    {
        $sectionId = $this->request->data['SectionId'];
        $data = $this->TDSMaster->find('first',array('fields'=>array('TDS'),'conditions'=>array('Id'=>$sectionId)));
        echo $data['TDSMaster']['TDS']; exit;
    }
            
    public function add_sub_head()
    {
        $this->layout="home";
        if($this->request->is('Post'))
        { 
            $data['Tbl_bgt_expensesubheadingmaster']['HeadingId'] = $this->request->data['Imprest']['HeadingId'];
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'] = $this->request->data['Imprest']['SubHeadingDesc'];
            $data['Tbl_bgt_expensesubheadingmaster']['HeadType'] = $this->request->data['Imprest']['HeadType'];
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadTDSEnabled'] = $this->request->data['Imprest']['SubHeadTDSEnabled'];
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadTdsSection'] = $this->request->data['Imprest']['TDSSection'];
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadTds'] = $this->request->data['Imprest']['TDS'];
            
            $MaxArr = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT MAX(CONVERT(SubHeadingId,UNSIGNED INTEGER)) maxId FROM `tbl_bgt_expensesubheadingmaster` WHERE LENGTH(HeadingId)<3");
            //print_r($MaxArr); exit;
            $Id = round($MaxArr['0']['0']['maxId'])+1; 
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadingId'] = $Id;
            
            $condi = "SubHeadingDesc='".$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc']."' and HeadingId='".$data['Tbl_bgt_expensesubheadingmaster']['HeadingId']."' and Length(SubHeadingId)<=3";
            if($this->Tbl_bgt_expensesubheadingmaster->find('first',array('conditions'=>$condi)))
            {
                $this->Session->setFlash(__(' Expense Sub Head '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Already Exists'));
            }
            else
            {
                if($this->Tbl_bgt_expensesubheadingmaster->save($data))
                {
                    $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Has Been Added Successfully'));
                    $this->redirect(array('action'=>'add_sub_head'));
                }
                else
                {
                    $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Has Been Not Updated Successfully'));
                }
            }
        }
        $this->set('HeadType',array('A'=>'A','B'=>'B')+$this->HeadType->find('list',array('fields'=>array('head_code','head_code'),'order'=>array('head_code'=>'asc'))));
        
        //$this->set('TdsMaster',$this->TDSMaster->find('list',array('fields'=>array('id','section','description'),'order'=>array('section'=>'asc'))));
        $TdsMaster=$this->TDSMaster->find('all',array('fields'=>array('id','section','description'),'order'=>array('section'=>'asc')));
        $TdMas = array();
        foreach($TdsMaster as $td)
        {
            $TdMas[$td['TDSMaster']['id']] =$td['TDSMaster']['description'] .'-'.$td['TDSMaster']['section'];
        }
        
        $this->set('TdsMaster',$TdMas);
        
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->query("SELECT head.HeadingId,head.HeadingDesc,subhead.SubHeadingId,subhead.SubHeadingDesc,subhead.HeadType,subhead.SubHeadTdsSection,tm.section,subhead.SubHeadTds,tm.TDS FROM `tbl_bgt_expenseheadingmaster` head 
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON head.HeadingId = subhead.HeadingId Left Join tds_master tm on subhead.SubHeadTdsSection =tm.id
WHERE head.EntryBy='' ORDER BY head.HeadingDesc,subhead.SubHeadingDesc"));
        $this->set('head1',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
    }
    public function add_sub_head_edit()
    {
        $this->layout="home";
        if($this->request->is('Post'))
        {
            $data['Tbl_bgt_expensesubheadingmaster']['HeadingId'] = $this->request->data['Imprest']['HeadingId'];
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'] = $this->request->data['Imprest']['SubHeadingDesc'];
            
                        
            $MaxArr = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT MAX(CONVERT(SubHeadingId,UNSIGNED INTEGER)) maxId FROM `tbl_bgt_expensesubheadingmaster` WHERE LENGTH(HeadingId)<3");
            //print_r($MaxArr); exit;
            $Id = round($MaxArr['0']['0']['maxId'])+1; 
            $data['Tbl_bgt_expensesubheadingmaster']['SubHeadingId'] = $Id;
            
            if($this->Tbl_bgt_expensesubheadingmaster->find('first',array('conditions'=>array('SubHeadingDesc'=>$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'],'HeadingId'=>$data['Tbl_bgt_expensesubheadingmaster']['HeadingId']))))
            {
                $this->Session->setFlash(__(' Expense Sub Head '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Already Exists'));
            }
            else
            {
                if($this->Tbl_bgt_expensesubheadingmaster->save($data))
                {
                    $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Has Been Added Successfully'));
                    $this->redirect(array('action'=>'add_head'));
                }
                else
                {
                    $this->Session->setFlash(__(' Expense Head  '.$data['Tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'].' Has Been Not Updated Successfully'));
                }
            }
        }
        
        $this->set('head1',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array("EntryBy"=>""),"order"=>array("HeadingDesc"=>"asc"))));
    }
}

?>