<?php
class CostCenterMastersController extends AppController 
{
    public $uses=array('TmpCostCenterMaster','CostCenterMaster','CostParticular','TmpCostParticular',
        'Addclient','Addbranch','Addcompany','Addprocess','Category','Type','BillMaster','CostCenterEmail');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        	
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {   $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->deny('index','add','view','edit','update','view_tmp','tmp_edit_cost','tmp_update_cost','get_GST_Type');
            if(in_array('3',$roles)){$this->Auth->allow('index');$this->Auth->allow('add','get_GST_Type');
            $this->Auth->allow('view');$this->Auth->allow('add_particulars');}
            if(in_array('30',$roles)){$this->Auth->allow('edit','edit_cost','disable_cost','get_GST_Type');$this->Auth->allow('update_cost');}
            if(in_array('53',$roles) ||in_array('56',$roles)){$this->Auth->allow('tmp_view','tmp_edit_cost','tmp_update_cost','delete_particulars','get_GST_Type');}
            if(in_array('54',$roles)){$this->Auth->allow('tmp_view','tmp_edit_cost','tmp_update_cost','delete_particulars','get_GST_Type');}
            if(in_array('175',$roles)){$this->Auth->allow('tmp_view_pending','tmp_edit_cost_pending','tmp_update_cost_pending','delete_particulars','get_GST_Type');}
        }
    }
		
    public function index() 
    {
        $this->set('cost_master', $this->CostCenterMaster->find('all'));
        $this->set('company_master', $this->Addcompany->find('all'));
        $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        $this->set('client_master', $this->Addclient->find('all',array('conditions'=>array('client_status'=>1),'order'=>array('client_name'=>'asc'))));
        $this->set('category_master', $this->Category->find('all'));
        $this->set('type_master', $this->Type->find('all'));
        $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));
        $this->layout='home';
    }
	   
    public function add() 
    {
        if ($this->request->is('post')) 
        {
            $data=$this->request->data['CostCenterMaster'];
            $data['OPBranch'] = $data['branch'];
            $data['createBy'] = $this->Session->read('userid');
            $data['createdate'] = date('Y-m-d H:i:s');

            //print_r($data);  exit;
            
            if(empty($data['Revenue']) && empty($data['Billing']))
            {
                $this->Session->setFlash(__("<h4 class=bg-success>".'Please Select on option of Billing Type'."</h4>"));
                return $this->redirect(array('action' => 'index'));	
            }
            
            if(empty($data['Revenue']))
            {
                $data['Revenue']=0;
            }
            else
            {
                $data['Revenue']=1;
            }
            if(empty($data['Billing']))
            {
                $data['Billing']=0;
            }
            else
            {
                $data['Billing']=1;
            }

            if ($this->TmpCostCenterMaster->save($data))
            {
                //exit;
                $id = $this->TmpCostCenterMaster->getLastInsertID();
                $this->TmpCostParticular->updateAll(array('cost_master_id'=>$id),array('userid'=>$this->Session->read('userid')));

                $branch = $data['branch'];
                $client = $data['client'];
                
                $email = $this->TmpCostCenterMaster->query("SELECT * FROM `add_cost_center_email` acce WHERE branch = '$branch'");

                //print_r($email); exit;
                App::uses('sendEmail', 'custom/Email');

                $toA = array(); $tcc = array(); $tbcc = array(); $corp = array();

                if(!empty($email))
                {
                    if(!empty($email[0]['acce']['to']))
                    {
                        $toA =explode(",",$email[0]['acce']['to']) ;
                        foreach($toA as $c)
                        {
                            if(!empty($c))
                            {
                                $to[] = $c; 
                            }
                        }
                    }

                    if(!empty($email[0]['acce']['cc']))
                    {
                        $tcc =explode(",",$email[0]['acce']['cc']) ;
                        foreach($tcc as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = $c; 
                            }
                        }
                    }
                    if(!empty($email[0]['acce']['bcc']))
                    {
                        $tbcc =explode(",",$email[0]['acce']['bcc']) ;
                        foreach($tbcc as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = $c; 
                            }
                        }
                    }
                    if(!empty($email[0]['acce']['corp']))
                    {
                        $corp =explode(",",$email[0]['acce']['corp']) ;
                        foreach($corp as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = $c; 
                            }
                        }
                    }
                }

                $sub = "New Cost Center Added To $branch";
                $msg ="Dear All,<br><br>"; 
                $msg .= "A New Cost Center Added To $branch for $client And moved to Admin Bucket For First Level Approval";
                $msg .="<br><br>";
                $msg .="This is System Genrated mail, Please don't reply.<br>";
                $msg .="Regards<br>"; 
                $msg .="<b>I-Spark</b>"; 
                $to = array_unique($to);
                $cc = array_unique($cc);
                $mail = new sendEmail();

                if(!empty($to))
                {
                    $mail-> multiple($to,$cc,$msg,$sub);
                } 

                $this->Session->setFlash(__("<h4 class=bg-success>".'The Cost Master has been Created And send To Admin Bucket For Approval'."</h4>"));
                return $this->redirect(array('action' => 'index'));			
            }
            $this->Session->setFlash(__("<h4 class=bg-danger>".'The cost Master could not be saved. Please, try again.'."</h4>"));
        }
    }
		
    public function view()
    {
       $this->set('cost_master', $this->CostCenterMaster->query("SELECT *,'deactive' `cost_status` FROM cost_master  cm
        ORDER BY CONVERT(SUBSTRING_INDEX(`cost_center`,'/',-1),UNSIGNED INT) DESC"));
        $this->layout='home';			
    }
     public function disable_cost()
    {
        $this->layout="ajax";
        $id=$this->request->data['cost_id'];
        $cost_status=$this->request->data['cost_status'];
        $userid = $this->Session->read('userid');
        $dataSource = $this->CostCenterMaster->getDataSource();
        $dataSource->begin();
        
        if($cost_status=='deactive')
        {
            if(!$this->CostCenterMaster->query("SELECT * FROM cost_master cm INNER JOIN 
                    tbl_invoice ti ON cm.cost_center = ti.cost_center AND cm.id='$id'
                    LEFT JOIN bill_pay_particulars bpp
                    ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
                    AND cm.company_name = bpp.company_name AND ti.finance_year = bpp.financial_year
                    WHERE bpp.bill_no IS NULL"))
                {
                    if(!$this->CostCenterMaster->query("INSERT INTO cost_master_disable SELECT * FROM cost_master WHERE id='$id'"))
                    {
                        
                        if(!$this->CostCenterMaster->query("INSERT INTO cost_master_history(
                            cost_id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,editBy,editDate,createdate,DisApprove,active,
                            `close`,disable_time,userid,CostCenterName,hremail)
                            SELECT id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,editBy,editDate,createdate,DisApprove,active,
                            `close`,NOW(),'$userid',CostCenterName,hremail FROM cost_master WHERE id='$id'"))
                            
                        {
                            if(!$this->CostCenterMaster->query("DELETE FROM cost_master WHERE id=$id"))
                            {
                                if($dataSource->commit())
                                {
                                    $msg = "Cost Center has been disabled successfully";
                                }
                                else
                                {
                                    $dataSource->rollback();
                                    $msg = "Cost Center disable has been failed. Please Contact to Admin";
                                }
                            }
                            else
                            {
                                $dataSource->rollback();
                                 $msg = "Cost Center Already Disabled.1";
                            }
                        }
                        else
                        {
                                $dataSource->rollback();
                                 $msg = "Cost Center Already Disabled.3";
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                        $msg = "Cost Center Already Disabled.2";
                    }
                }
            else
            {
                $msg = "Payment Not Submitted of bill generated by this cost center. Please upload Payment or disapprove Invoices mapped with this Cost Center";
            }
        }
        else 
        {
            if(!$this->CostCenterMaster->query("SELECT * FROM cost_master where id='$id'"))
                {
                    if(!$this->CostCenterMaster->query("INSERT INTO cost_master SELECT * FROM cost_master_disable WHERE id='$id'"))
                    {
                        
                        if(!$this->CostCenterMaster->query("INSERT INTO cost_master_history(
                            cost_id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,createdate,DisApprove,active,
                            `close`,enable_time,userid,CostCenterName,hremail,editBy,editDate)
                            SELECT id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,createdate,DisApprove,active,
                            `close`,NOW(),'$userid','CostCenterName',hremail,'$userid',NOW() FROM cost_master_disable WHERE id='$id'"))
                        {
                            if(!$this->CostCenterMaster->query("DELETE FROM cost_master_disable WHERE id=$id"))
                            {
                                if($dataSource->commit())
                                {
                                    $msg = "Cost Center has been enabled successfully";
                                }
                                else
                                {
                                    $dataSource->rollback();
                                    $msg = "Cost Center enable has been failed. Please Contact to Admin";
                                }
                            }
                            else
                            {
                                $dataSource->rollback();
                                $msg = "Cost Center enable has been failed. Please Contact to Admin";
                            }
                        }
                        else
                        {
                            $dataSource->rollback();
                            $msg = "Cost Center enable has been failed. Please Contact to Admin";
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                        $msg = "Cost Center Already Exist.";
                    }
                }
            else
            {
                $this->CostCenterMaster->rollback();
                $msg = "Cost Master Already Exist.";
            }
        }
        echo $msg;
        exit;
    }
    public function edit_cost()
    {			
        $id = $this->request->query['id'];
        $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('id'=>$id))));
        $this->set('company_master', $this->Addcompany->find('all'));
        $this->set('branch_master', $this->Addbranch->find('all'));			
        $this->set('client_master', $this->Addclient->find('all'));			
        $this->set('category_master', $this->Category->find('all'));
        $this->set('type_master', $this->Type->find('all'));
        $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));			
        $this->layout='home';		
    }

    public function update_cost()
    {
        if ($this->request->is('post')) 
        {
            $data = $this->request->data;
            $id = $data['CostCenterMaster']['id'];
            $SendToBps = $data['CostCenterMaster']['SendToBPS'];
            $data = Hash::Remove($data['CostCenterMaster'],'id');
            $data = Hash::Remove($data,'SendToBPS');
            
            //print_r($data);
            //$data['CostCenterMaster'][''] = "'".$data['CostCenterMaster']['']."'";
            $userid = $this->Session->read('userid');
            
            $this->CostCenterMaster->query("INSERT INTO cost_master_history(
                            cost_id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,createdate,DisApprove,active,
                            `close`,disable_time,userid,CostCenterName,hremail,client_tally_name)
                            SELECT id,company_name,branch,stream,`process`,process_name,tower,category,`type`,`client`,total_man_date,shrinkage,attrition,shift,
                            working_days,target_mandate,over_saldays,training_days,incentive_allowed,training_attrition,deduction_allowed,description,process_manager,
                            emailid,contact_no,po_required,jcc_no,grn,bill_to,as_client,b_Address1,b_Address2,b_Address3,b_Address4,b_Address5,ship_to,as_bill_to,
                            a_address1,a_address2,a_address3,a_address4,a_address5,revenueType,`fixed`,variableBase,agreementReq,paymentMode,paymentTerms,AssociationDate,
                            goLiveDate,UserName1,UserName2,UserName3,UserDesignation1,UserDesignation2,UserDesignation3,UserContactNo1,UserContactNo2,UserContactNo3,
                            UserEmailId1,UserEmailId2,UserEmailId3,UserAddress1,UserAddress2,UserAddress3,SCMName1,SCMName2,SCMName3,SCMDesignation1,
                            SCMDesignation2,SCMDesignation3,SCMContactNo1,SCMContactNo2,SCMContactNo3,SCMEmailId1,SCMEmailId2,SCMEmailId3,SCMAddress1,SCMAddress2,
                            SCMAddress3,FinanceName1,FinanceName2,FinanceName3,FinanceDesignation1,FinanceDesignation2,FinanceDesignation3,FinanceContactNo1,
                            FinanceContactNo2,FinanceContactNo3,FinanceEmailId1,FinanceEmailId2,FinanceEmailId3,FinanceAddress1,FinanceAddress2,FinanceAddress3,
                            cost_center,approve1,approve2,approveDate1,approveDate2,approveBy1,approveBy2,createBy,createdate,DisApprove,active,
                            `close`,NOW(),'$userid',CostCenterName,hremail,client_tally_name FROM cost_master WHERE id='$id'");
            
            
            
            $key = array_keys($data);$i =0;
            foreach($data as $post)
            {
                    $dataX[$key[$i++]] = "'".addslashes($post)."'";
            }
            if($this->CostCenterMaster->updateAll($dataX,array('id'=>$id)))
            {

                $QryArr = Array('Branch'=>'branch','CostCenter'=>'cost_center',
                'Stream'=>'stream','Process'=>'process','Category'=>'category','Type'=>'type','Client'=>'client',
                'Description'=>'description','SalDays'=>'over_saldays','Incentive'=>'incentive_allowed',
                'ManDate'=>'total_man_date','Attrition'=>'attrition',
                'Shrinkage'=>'shrinkage','TargetMandate'=>'target_mandate','Shift'=>'shift','WorkingDays'=>'working_days',
                'ProcessManagerName'=>'process_manager','EmailId'=>'emailid','ContactNo'=>'contact_no','PORequired'=>'po_required',
                'TrainingDaysCount'=>'training_days','TrainingAttrition'=>'training_attrition','CompanyName'=>'company_name',
                'Billto'=>'bill_to','Shipto'=>'ship_to','JCCNo'=>'jcc_no','GRN'=>'grn','BillToAdd1'=>'b_Address1',
                'BillToAdd2'=>'b_Address2','BillToAdd3'=>'b_Address3','BillToAdd4'=>'b_Address4','BillToAdd5'=>'b_Address5',
                'ShipToAdd1'=>'a_address1','ShipToAdd2'=>'a_address2','ShipToAdd3'=>'a_address3','ShipToAdd4'=>'a_address4',
                'ShipToAdd5'=>'a_address5','Deduction'=>'deduction_allowed','HREmailId'=>'hremail'); 

                $url = array('ActionType'=>$SendToBps,'CostCenterType'=>'Revenue','User_Name'=>$this->Session->read('username')); 

                foreach($QryArr as $k=>$v)
                {
                    if($v=='shrinkage' ||$v=='attrition') 
                        {
                            if(empty($data[$v])) {$data[$v] = 0;}
                        $url[$k] = filter_var($data[$v], FILTER_SANITIZE_NUMBER_INT).'.0';
                        
                        }
                    else if ($v=='target_mandate' || $v=='total_man_date') 
                        { 
                            if(!empty($data[$v]))
                            {
                                $url[$k] = $data[$v];
                            }
                            else
                            {
                                $url[$k] = 0;
                            }
                        }    
                    else if ($v=='training_attrition') 
                        { if($data[$v]=='No') {$url[$k] = 0;} else {$url[$k] = 1;}}
                    else if($v=='company_name') {if($data[$v]=='Mas Callnet India Pvt Ltd') {$url[$k]='MAS';} else {$url[$k]=$data[$v];}}
                    else if($v=='branch') {
                        if($data[$v]=='AHMEDABAD HOUSE')
                            {$url[$k]='AHEMDABAD HOUSE';} 
                        else if ($data[$v]=='AHMEDABAD OTHERS'){ $url[$k]='AHEMDABAD OTHERS';}
                        else {$url[$k]=$data[$v];}
                    }
                    else if($v=='stream') 
                        {
                            $stream = $this->Addbranch->query("SELECT stream FROM process_master WHERE id ='".$data[$v]."' limit 1");
                            $url[$k] = trim($stream[0]['process_master']['stream']);
                            //echo "SELECT stream FROM process_master WHERE id ='".$data[$v]."' limit 1";
                        }
                    else
                    {$url[$k] = trim($data[$v]);}
                }

             $postdata = http_build_query($url); 
              // print_r($postdata); exit;
//                $opts = array('http' =>
//                array(
//                    'method'  => 'GET',
//                    'header'  => 'Content-type: application/x-www-form-urlencoded',
//                    'content' => $postdata
//                ));
                
//                foreach($url as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
//                rtrim($fields_string, '&');

                //$context = stream_context_create($opts);
                //$result  = file_get_contents("http://bpsmis.ind.in/CostCenterLink.aspx",false,$context);
                $urlName = "http://bpsmis.ind.in/CostCenterLink.aspx?";
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$urlName.$postdata);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                //execute post
                $result = curl_exec($ch);
                curl_close($ch);
                //print_r($result);
                //exit;
                
                
                $cost_center = $data['cost_center'];
                $email = $this->CostCenterEmail->query("SELECT cce.* FROM cost_master cm INNER JOIN 
cost_center_email cce ON cm.Id = cce.cost_center WHERE cm.cost_center = '$cost_center'");
                
            //print_r($email); exit; exit;
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

                    $sub = "Cost Center Updated";
                    $msg ="Dear All,<br><br>"; 
                    $msg .= "Cost Center $cost_center Updated";
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

                unset($data);unset($key);
                $this->Session->setFlash(__("<h4 class=bg-success>".'The cost Master has been updated.'."</h4>"));
                return $this->redirect(array('action'=>'view'));
            }
            else
            {
                    $this->Session->setFlash(__("<h4 class=bg-success>".'The cost Master could not be updated. Please Try Againg!'."</h4>"));
                    return $this->redirect(array('action'=>'view'));					
            }
            $this->set('data',$dataX);
        }
    }

    public function tmp_view()
    {
        $role=$this->Session->read("role");
        $roles=explode(',',$this->Session->read("page_access"));

        if(in_array('53',$roles))
        {
          $condition = array('approve1'=>0,'or'=>array('DisApprove'=>null,'DisApprove'=>''));  
          $condition = "approve1='0' and (DisApprove is null or DisApprove ='')";
          $approve = 53; 
        }
        else if(in_array('54',$roles))
        {
            //$condition = array('approve2'=>'0','approve1'=>'1','or'=>array('DisApprove'=>null,'DisApprove'=>''));
            $condition = "approve2='0' and approve1='1' and (DisApprove is null or DisApprove ='')";
            $approve = 54;
        }
        else
        {
            //$condition = array('approve1'=>'-1','or'=>array('DisApprove'=>null,'DisApprove'=>''));
            $condition = "approve1='-1' and (DisApprove is null or DisApprove ='')";
            $approve = 56;
        }
        //print_r($condition); exit;
            $this->set('cost_master', $this->TmpCostCenterMaster->find('all',array('conditions'=>$condition,'order'=>array("Branch"=>'desc'))));
            $this->set('approve',$approve);
            $this->layout='home';		
    }
    public function tmp_edit_cost()
    {			
            $id = $this->request->query['id'];
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            if(in_array('53',$roles))
            {
                $condition = array('approve1'=>'0');  
                $approve = 53;
            }
            else if(in_array('54',$roles))
            {
                $condition = array('approve2'=>'0');  
                $approve = 54;
            }
            else
            {
                $condition = array('approve2'=>'0');  
                $approve = 56;
            }
             $this->set('approve',$approve);
             $roles=explode(',',$this->Session->read("page_access"));

            $this->set('cost_master', $this->TmpCostCenterMaster->find('first',array('conditions'=>array('Id'=>$id))));
            $this->set('company_master', $this->Addcompany->find('all'));
            $this->set('branch_master', $this->Addbranch->find('all'));			
            $this->set('client_master', $this->Addclient->find('all'));			
            $this->set('category_master', $this->Category->find('all'));
            $this->set('type_master', $this->Type->find('all'));
            $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));			
            $this->layout='home';		
    }
    public function tmp_update_cost()
    {
        if ($this->request->is('post')) 
        {
            $submit = $this->request->data['submit'];
            $data = $this->request->data['CostCenterMaster'];
                //print_r($data); exit;
            $id = $data['id']; 
            $approve = $data['approve'];
            $branch = $data['branch'];
            $client = $data['client'];
            $data = Hash::Remove($data,'id');
            $data = Hash::Remove($data,'approve');
            $msg ="Dear All,<br><br>";
            
            if($submit=='Approve')
            {
                if($approve=='53')
                {
                    $data['approve1'] = '1';
                    $data['approveby1'] = $this->Session->read('userid');
                    $data['approveDate1'] = date('Y-m-d H:i:s');
                    $sub = "Cost Center Approved At First Level For ".$branch.' For Client '.$client;
                    $msg .= $sub;
                }
                else
                {
                    $data['OPBranch'] = $data['branch'];
                    $data['approve2'] = '1';
                    $data['approveby2'] = $this->Session->read('userid');
                    $data['approveDate2'] = date('Y-m-d H:i:s');
                       
                    $dataX=$this->Addbranch->find('first',array('conditions'=>array('Addbranch.branch_name'=>$data['branch'])));
                    $b_name=$dataX['Addbranch']['branch_code'];

                    $stream=$data['stream'];
                    $type=$data['type'];

                    $stream=$this->Addprocess->getStream($stream);
                    $type=$this->Type->getCodes($type);

                    $strs='';
                    $str=explode(" ",$stream['Addprocess']['stream']);

                    foreach ($str as $post):
                        $strs.=substr($post,0,1);
                    endforeach;

                    $str=$strs.'/'.$type['Type']['codes'].'/'.$b_name.'/';

                    $BillMaster = $this->BillMaster->find('first',array('fields'=>'cost_center','conditions'=>array('id'=>'1')));

                    $str.=$BillMaster['BillMaster']['cost_center'];
                    
                    $cost_center = $data['cost_center']=preg_replace('/\s+/','',$str);

                    $sub = "Cost Center ".$cost_center." Approved At Second Level For ".$branch.' For Client '.$client;
                    $msg .= $sub;
                }

                $dataX = array();
                $key = array_keys($data);$i =0;
                foreach($data as $post)
                {
                        $dataX[$key[$i++]] = "'".addslashes($post)."'";
                }

                if($this->TmpCostCenterMaster->updateAll($dataX,array('id'=>$id)))
                {
                    $flash = "<h4>".'<b>The Cost Center  has been Moved For Second Approval.</b>'."</h4>";

                    if($approve=='54')
                    {

                        $QryArr = Array('Branch'=>'branch','CostCenter'=>'cost_center',
                        'Stream'=>'stream','Process'=>'process','Category'=>'category','Type'=>'type','Client'=>'client',
                        'Description'=>'description','SalDays'=>'over_saldays','Incentive'=>'incentive_allowed',
                        'ManDate'=>'total_man_date','Attrition'=>'attrition',
                        'Shrinkage'=>'shrinkage','TargetMandate'=>'target_mandate','Shift'=>'shift','WorkingDays'=>'working_days',
                        'ProcessManagerName'=>'process_manager','EmailId'=>'emailid','ContactNo'=>'contact_no','PORequired'=>'po_required',
                        'TrainingDaysCount'=>'training_days','TrainingAttrition'=>'training_attrition','CompanyName'=>'company_name',
                        'Billto'=>'bill_to','Shipto'=>'ship_to','JCCNo'=>'jcc_no','GRN'=>'grn','BillToAdd1'=>'b_Address1',
                        'BillToAdd2'=>'b_Address2','BillToAdd3'=>'b_Address3','BillToAdd4'=>'b_Address4','BillToAdd5'=>'b_Address5',
                        'ShipToAdd1'=>'a_address1','ShipToAdd2'=>'a_address2','ShipToAdd3'=>'a_address3','ShipToAdd4'=>'a_address4',
                        'ShipToAdd5'=>'a_address5','Deduction'=>'deduction_allowed','HREmailId'=>'hremail');

                        $url = array('ActionType'=>'insert','CostCenterType'=>'Revenue','User_Name'=>$this->Session->read('username'));
 
                        foreach($QryArr as $k=>$v)
                        {
                            if($v=='shrinkage' ||$v=='attrition') 
                                {
                                if(empty($data[$v]))
                                    $url[$k] = 0;
                                else
                                $url[$k] = filter_var($data[$v], FILTER_SANITIZE_NUMBER_INT).'.0';
                                
                                }
                            else if($v=='total_man_date' || $v=='shift' || $v=='training_days' || $v=='training_attrition' || $v =='TargetMandate' || $v =='working_days') { $url[$k] = 0;}    
                            else if ($v=='training_attrition') { if($data[$v]=='No') {$url[$k] = 0;} else {$url[$k] = 1;}}
                            else if($v=='company_name') {if($data[$v]=='Mas Callnet India Pvt Ltd') {$url[$k]='MAS';} else {$url[$k]=$data[$v];}}
                            else if($v=='branch') 
                                {
                                    if($data[$v]=='AHMEDABAD HOUSE')
                                        {$url[$k]='AHEMDABAD HOUSE';} 
                                    else if ($data[$v]=='AHMEDABAD OTHERS')
                                        { $url[$k]='AHEMDABAD OTHERS';}
                                    else 
                                        {$url[$k]=$data[$v];}
                    }
                            else if($v=='stream') 
                                {
                                    $stream = $this->Addbranch->query("SELECT stream FROM process_master WHERE id ='".$data[$v]."' limit 1");
                                    //print_r($stream); exit;
                                    $url[$k] = $stream[0]['process_master']['stream'];
                                }
                                
                            else
                            {$url[$k] = $data[$v];}
                        }

                        $postdata = http_build_query($url);
                           // print_r($postdata); exit; 
                       // $this->Addcompany->query("INSERT INTO `tm_tbl_invoice` SET branch_name = '$branch', new_cost_bps='".'http://bpsmis.ind.in/CostCenterLink.aspx?'.$postdata."'");
                        $opts = array('http' =>
                        array(
                                'method'  => 'GET',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            ));
                        
                        $context = stream_context_create($opts);
                        //$result  = file_get_contents("http://bpsmis.ind.in/CostCenterLink.aspx",false,$context);
                        $urlName = "http://bpsmis.ind.in/CostCenterLink.aspx?";
                        $ch = curl_init();
                        curl_setopt($ch,CURLOPT_URL,$urlName.$postdata);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                //execute post
                        $result = curl_exec($ch);
                        curl_close($ch);
                        
                        $tmp = $this->TmpCostCenterMaster->find('first',array('conditions'=>array('id'=>$id)));

                        $tmp = Hash::Remove($tmp['TmpCostCenterMaster'],'id');

                        if($this->CostCenterMaster->save($tmp))
                        {
                            $newid = $this->CostCenterMaster->getLastInsertID();
                            $this->BillMaster->updateAll(array('cost_center'=>'cost_center+1'),array('id'=>'1'));
                            $this->CostParticular->query("INSERT INTO tmp_cost_master_particulars(cost_master_id,remarks,revenueType,qty,rate,total,userid,createdate) "
                                    . " SELECT '$newid',remarks,revenueType,qty,rate,total,userid,createdate FROM `tmp_cost_master_particulars` WHERE cost_master_id='$id'");
                        }
                        $flash = "<h4>".'<b>The Cost Center '.$cost_center.' For <b>'.$branch
                        .'</b>  has been Created.'."</h4>";
                }
                        $msg .= "";
                }
                else
                {
                    $flash = "<h4>".'The cost Master could not be updated. Please Try Again!'."</h4>";
                }
                $this->set('data',$dataX);
            }
            else if($submit=='DisApprove')
            {
               if($approve=='53')
               {
                   $DisApprove = 1;
                   $approveValue = null;
               }
               else
               {
                   $DisApprove = 2;
                   $approveValue = 1;
               }
               $sub = "Cost Center Disapproved At Level $DisApprove For ".$branch.' For Client '.$client;
               $msg .= $flash = $sub;
               
               $this->TmpCostCenterMaster->updateAll(array("approve1"=>$approveValue,'DisApprove'=>$DisApprove),array('id'=>$id));
            }
            else 
            {
                $dataX = array('approve1'=>'0');
                $key = array_keys($data);$i =0;
                foreach($data as $post)
                {
                        $dataX[$key[$i++]] = "'".addslashes($post)."'";
                }
                
                if($this->TmpCostCenterMaster->updateAll($dataX,array('id'=>$id)))
                {
                    $flash = "<h4>".'The cost Master Edited.'."</h4>";
                    $sub = "Cost Center Edited For ".$branch.' For Client '.$client.'';
                    $msg .= $flash = $sub;
                    $msg .= 'And Moved to Admin Bucket For Level 1 Approval';
                }
            }
            
            $email = $this->TmpCostCenterMaster->query("SELECT * FROM `add_cost_center_email` acce WHERE branch = '$branch'");

                    //print_r($email); exit;
            App::uses('sendEmail', 'custom/Email');

            $toA = array(); $tcc = array(); $tbcc = array(); $corp = array();

                if(!empty($email))
                {
                    if(!empty($email[0]['acce']['to']))
                    {
                        $toA =explode(",",$email[0]['acce']['to']) ;
                        foreach($toA as $c)
                        {
                            if(!empty($c))
                            {
                                $to[] = trim($c); 
                            }
                        }
                    }

                    if(!empty($email[0]['acce']['cc']))
                    {
                        $tcc =explode(",",$email[0]['acce']['cc']) ;
                        foreach($tcc as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = trim($c); 
                            }
                        }
                    }
                    if(!empty($email[0]['acce']['bcc']))
                    {
                        $tbcc =explode(",",$email[0]['acce']['bcc']) ;
                        foreach($tbcc as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = trim($c); 
                            }
                        }
                    }
                    if(!empty($email[0]['acce']['corp']))
                    {
                        $corp =explode(",",$email[0]['acce']['corp']) ;
                        foreach($corp as $c)
                        {
                            if(!empty($c))
                            {
                                $cc[] = trim($c); 
                            }
                        }
                    }
                }
                
                $msg .="<br><br>";
                $msg .="This is System Genrated mail, Please don't reply.<br>";
                $msg .="Regards<br>"; 
                $msg .="<b>I-Spark</b>"; 
                
                $to = array_unique($to);
                $cc = array_unique($cc);
                $mail = new sendEmail();

                if(!empty($to))
                {
                    $mail-> multiple($to,$cc,$msg,$sub);
                }
        $this->Session->setFlash(__($flash));
        return $this->redirect(array('action'=>'tmp_view'));
        }
    }

    public function add_particulars()
    {
        $this->layout="ajax";
        $userid = $this->Session->read("userid");
        $data = $this->request->data;
        $data['total'] = $data['qty']*$data['rate'];
        $data['createdate'] = date('Y-m-d H:i:s');
        $revenueType = $data['revenueType'];  
        $data['userid'] = $userid;
        $this->TmpCostParticular->save($data);
        $this->set("data",$this->TmpCostParticular->find('all',array('conditions'=>
            array('revenueType'=>$revenueType,'cost_master_id'=>null,'userid'=>$userid))));

    }
    public function delete_particulars()
    {
        $this->layout="ajax";
        $userid = $this->Session->read("userid");
        $id = $this->request->data['id'];
        $revenueType = $this->request->data['revenueType']; 
        $this->set('data1',$this->request->data);
        $this->TmpCostParticular->query("DELETE FROM `tmp_cost_master_particulars` WHERE Id = '$id'");

        $this->set("data",$this->TmpCostParticular->find('all',array('conditions'=>
            array('revenueType'=>$revenueType,'cost_master_id'=>null,'userid'=>$userid))));

    }
    
    public function get_GST_Type()
    {
        $this->layout="ajax";
        $company_name = $this->request->data['company_name'];
        $branch = $this->request->data['branch'];
        $type = $this->request->data['type'];
        
        if($type=='Integrated')
        {
            $data = $this->TmpCostParticular->query("SELECT ServiceTaxNo,branch FROM tbl_service_tax ti WHERE company_name = '$company_name' AND branch='$branch' and ServiceTaxNo is not null And ServiceTaxNo !=''");
        }
        else
        {
            $data = $this->TmpCostParticular->query("SELECT ServiceTaxNo,branch FROM tbl_service_tax ti WHERE company_name = '$company_name' and ServiceTaxNo is not null And ServiceTaxNo !='' order by branch");
        }
        
        foreach($data as $d)
        {
            $json[$d['ti']['ServiceTaxNo']] = $d['ti']['branch'].'-'.$d['ti']['ServiceTaxNo'];
        }
        
        echo json_encode($json);
        exit;
    }
    
    public function tmp_view_pending()
    {
        $role=$this->Session->read("role");
        $roles=explode(',',$this->Session->read("page_access"));

        if($role=='admin')
            {
                $conditions2 = array('not'=>array('DisApprove'=>null,'DisApprove'=>''));
            }
            else
            {
                
                $conditions2 = array("branch"=>$this->Session->read("branch_name"),'not'=>array('DisApprove'=>null,'DisApprove'=>''));
            }
        
          
          
        
        

            $this->set('cost_master', $this->TmpCostCenterMaster->find('all',array('conditions'=>$conditions2,'order'=>array("Branch"=>'desc'))));
            $this->set('approve',$approve);
            $this->layout='home';		
    }
    public function tmp_edit_cost_pending()
    {			
            $id = $this->request->query['id'];
            $role = $this->Session->read('role');
            
            

            $this->set('cost_master', $this->TmpCostCenterMaster->find('first',array('conditions'=>array('Id'=>$id))));
            $this->set('company_master', $this->Addcompany->find('all'));
            $this->set('branch_master', $this->Addbranch->find('all'));			
            $this->set('client_master', $this->Addclient->find('all'));			
            $this->set('category_master', $this->Category->find('all'));
            $this->set('type_master', $this->Type->find('all'));
            $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));			
            $this->layout='home';		
    }
    public function tmp_update_cost_pending()
    {
        if ($this->request->is('post')) 
        {
            $submit = $this->request->data['submit'];
            $data = $this->request->data['CostCenterMaster'];
                //print_r($data); exit;
            $id = $data['id']; 
            
            $client = $data['client'];
            $data = Hash::Remove($data,'id');
            $data = Hash::Remove($data,'approve');
            
            
            
           
                $dataX = array('approve1'=>'0','approve2'=>'0','DisApprove'=>null);
                $key = array_keys($data);$i =0;
                foreach($data as $post)
                {
                        $dataX[$key[$i++]] = "'".addslashes($post)."'";
                }
                
                if($this->TmpCostCenterMaster->updateAll($dataX,array('id'=>$id)))
                {
                    $flash = "<h4>".'The cost Master Edited.'."</h4>";
                }
           
            
            
            $this->Session->setFlash(__($flash));
            return $this->redirect(array('action'=>'tmp_view'));
        }
    }
    
}
?>