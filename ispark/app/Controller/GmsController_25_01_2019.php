<?php
	class GmsController extends AppController 
	{
		public $uses=array('Collection','Addbranch','BillMaster','TMPCollection','Addcompany','TMPCollectionParticulars','OtherTMPDeduction','OtherDeduction',
                    'CollectionParticulars','InitialInvoice','Bank','User','BillMaster','TmpExpenseMaster','Tbl_bgt_expenseheadingmaster','VendorMaster','VendorRelation',
                    'CostCenterMaster','ExpenseMaster','ExpenseEntryMaster','TmpExpenseEntryMaster','TmpExpenseEntryParticular','Tbl_bgt_expensesubheadingmaster',
                    'ExpenseEntryParticular','GrnBranchAccess','ExpenseEntryApproveParticular','ExpenseEntryApproveMaster','GrnPaymentProcessing');
		public $components = array('RequestHandler');
		public $helpers = array('Js');

		public function beforeFilter()
		{
                        parent::beforeFilter();
			
			$this->Auth->allow('get_collection_data','index');
                                    $this->Auth->allow('get_collection_tmp_data');
                                    $this->Auth->allow('get_collection_tmp_bill_data');
                                    $this->Auth->allow('delete_collection_particular');
                                    $this->Auth->allow('Other_Deduction');
                                    $this->Auth->allow('delete_other_deduction');
                                    $this->Auth->allow('get_bill_amount');
                                    $this->Auth->allow('back');
                                    $this->Auth->allow('add'); 
                                    $this->Auth->allow('index','get_bill_remark','get_branch','getCostCenter','get_budget','add_field_value',
                                            'add_grn_tmp','delete_grn','get_head','get_sub_heading','imprest_entry','get_sub_heading1','imprest_add',
                                            'back1','approve_grn','approve_grn_tmp','approve_imprest','approve_imprest_tmp','update_grn_tmp','save_payment_processing','save_payment_processing1',
                                            'approve_imprest_tmp1','update_grn_tmp','delete_grn1','add_field_value1','approve_imprest_tmp','payment_processing',
                                            'edit_imprest_branch','edit_imprest_tmp_branch','save_imprest_tmp1_branch','edit_grn_branch','view_imprest_tmp',
                                            'edit_grn_tmp_branch','save_grn_tmp1_branch','view_grn','view_grn_tmp','view_imprest','view_imprest_tmp','get_due_date','approve_grn2','approve_grn_tmp2');
                    
                                    
			if(!$this->Session->check("userid"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
				//$rdx=$this->Access->find('first',array('fields'=>array('page_access'),'conditions'=>array('user_type'=>$role)));
				//$roles=explode(',',$rdx['Access']['page_access']);
				
                            if(in_array('68',$roles))
                            {
                                    $this->Auth->allow('get_collection_data','index');
                                    $this->Auth->allow('get_collection_tmp_data');
                                    $this->Auth->allow('get_collection_tmp_bill_data');
                                    $this->Auth->allow('delete_collection_particular');
                                    $this->Auth->allow('Other_Deduction');
                                    $this->Auth->allow('delete_other_deduction');
                                    $this->Auth->allow('get_bill_amount');
                                    $this->Auth->allow('back');
                                    $this->Auth->allow('add'); 
                                    $this->Auth->allow('index','get_bill_remark','get_branch','getCostCenter','get_budget','add_field_value','add_grn_tmp','delete_grn','get_head','get_sub_heading','imprest_entry','get_sub_heading1','imprest_add','pending_grn');
                            }
			}			
			if ($this->request->is('ajax'))
			 {
				$this->render('contact-ajax-response', 'ajax');
			 }
    	}

	public function get_collection_data()
	{
		$this->layout = 'ajax';
		$this->set('data',$this->params->query);
                $conditions =$this->params->query;
		$this->set('RTGS',$this->Collection->find('first',array('fields' =>array('id','max(pay_no)'),'conditions'=>$conditions)));
	}
        public function get_branch()
        {
            $VendorId = $this->request->data['VendorId'];
            $role = $this->Session->read('role');
            $userid = $this->Session->read('userid');
            $branchName = "";
            if($role=='admin')
            {
                $condition=array('active'=>1);
            }
            else
            {
                $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));
                $branchName = " and bm.branch_name='".$this->Session->read("branch_name")."'";
                $join = "INNER JOIN (select * from tbl_grn_access group by UserId,BranchId) tga ON bm.Id = tga.BranchId ";
                $user = " AND tga.UserId='$userid'";
                
            }
            
            if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id $join
            WHERE tsgd.VendorId='$VendorId' $user GROUP BY branch_name"))
            {
                $branchArr = array();
                foreach($data as $v)
                {
                    $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                }
                echo json_encode($branchArr);
            }
            else
            {
                echo ''; exit;
            }
            exit;
        }
        public function get_head()
        {
            $VendorId = $this->request->data['VendorId'];
            if($data = $this->Tbl_bgt_expenseheadingmaster->query("SELECT head.HeadingId,head.HeadingDesc FROM `vendor_expense_relation` vr INNER JOIN `tbl_bgt_expenseheadingmaster` head ON vr.HeadId = head.HeadingId
WHERE VendorId = '$VendorId'  order by head.HeadingDesc"))
            {
                $HeadArr = array();
                foreach($data as $v)
                {
                    $HeadArr[$v['head']['HeadingId']] = $v['head']['HeadingDesc'];
                }
                echo json_encode($HeadArr);
            }
            else
            {
                echo ''; 
            }
            exit;
        }
        
        public function get_sub_heading()
        {
            $VendorId = $this->request->data['VendorId'];
            $HeadingId = $this->request->data['HeadingId'];
            if($data = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT subhead.SubHeadingId,subhead.SubHeadingDesc FROM `vendor_expense_relation` vr INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON vr.SubHeadId = subhead.SubHeadingId
WHERE vr.VendorId = '$VendorId' and vr.HeadId = '$HeadingId'  ORDER BY subhead.SubHeadingDesc"))
            {
                $SubHeadArr = array();
                foreach($data as $v)
                {
                    $SubHeadArr[$v['subhead']['SubHeadingId']] = $v['subhead']['SubHeadingDesc'];
                }
                echo json_encode($SubHeadArr);
            }
            else
            {
                echo ''; 
            }
            exit;
        }
        
        public function getCostCenter()
        {
            $BranchId = $this->request->data['BranchId'];
            $CompId = $this->request->data['CompId'];
            if($data = $this->CostCenterMaster->query("SELECT cm.Id,cm.cost_center FROM branch_master bm INNER JOIN cost_master cm ON bm.branch_name = cm.branch 
INNER JOIN company_master comp ON cm.company_name = comp.company_name
WHERE bm.Id = '$BranchId'"))
            {
                $costArr = array();
                foreach($data as $v)
                {
                    $costArr[$v['cm']['Id']] = $v['cm']['cost_center'];
                }
                echo json_encode($costArr);
            }
            else
            {
                echo ''; exit;
            }
            exit;
        }
        
        public function add_field_value()
        {
            $data['Particular'] = addslashes($this->request->data['Particular']);
            $data['ExpenseEntryType'] = $this->request->data['ExpenseEntryType'];
            $data['BranchId'] = $this->request->data['BranchId'];
            $data['CostCenterId'] = $this->request->data['CostCenter'];
            $data['Amount'] = $this->request->data['Amount'];
            $data['Rate'] = $this->request->data['Rate'];
            $data['Tax'] = round(($data['Amount']*$data['Rate'])/100,3);
            $data['Total'] = round($data['Amount']+$data['Tax'],3);
            $data['createdate'] = date("Y-m-d H:i:s");
            $data['userid'] = $this->Session->read("userid");
            
            if($this->TmpExpenseEntryParticular->save(array('TmpExpenseEntryParticular'=>$data)))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            exit;
        }
        
        public function get_budget()
        {
            $BranchId = $this->request->data['BranchId'];
            $FinanceYear = $this->request->data['FinanceYear'];
            $FinanceMonth = $this->request->data['FinanceMonth'];
            $HeadId = $this->request->data['HeadId'];
            $SubHeadId = $this->request->data['SubHeadId'];
            $VendorId = $this->request->data['VendorId'];
            $Budget = 0;
            $Consume = 0;
            $Balance = 0;
            if($data = $this->ExpenseMaster->find('first',array('conditions'=>array("BranchId"=>$BranchId,"FinanceYear"=>$FinanceYear,"FinanceMonth"=>$FinanceMonth,
                "HeadId"=>$HeadId,"SubHeadId"=>$SubHeadId))))
                {
                    if($data['ExpenseMaster']['Amount']==0 || $data['ExpenseMaster']['EntryStatus']==0)
                    {
                        echo "1";
                    }
                    else
                    {
                        $Budget = $data['ExpenseMaster']['Amount']?$data['ExpenseMaster']['Amount']:0;
                        $Expense = $this->ExpenseEntryMaster->query("SELECT SUM(eep.Amount)Total FROM expense_entry_master eem
INNER JOIN `expense_entry_particular` eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `cost_master` cm ON eep.CostCenterId = cm.Id
INNER JOIN branch_master bm ON cm.branch = bm.branch_name where "
                        . "eem.FinanceYear='$FinanceYear' AND eem.FinanceMonth='$FinanceMonth' AND bm.Id='$BranchId' AND eem.HeadId='$HeadId' AND eem.SubHeadId='$SubHeadId'");
                        

                        $Consume = empty($Expense['0']['0']['Total'])?0:$Expense['0']['0']['Total'];
                        $Balance = $Budget-$Consume;
                        
                        $VendorRelation = 0;
                        if($this->VendorRelation->find('first',array('conditions'=>array('VendorId'=>$VendorId,'BranchId'=>$BranchId,'GSTEnable'=>"1"))))
                        {
                            $VendorRelation = 1;
                        }
                        
                        if($Balance)
                        {
                            echo $Budget.",".$Consume.",".$Balance.",".$VendorRelation;
                        }
                        else
                        {
                            echo "1";
                        }
                    }
                }
            else
            {
                echo "0";
            }
            exit;
        }
		
		
		
		public function back()
		{
					$userid = $this->Session->read('userid');
					$this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
					$this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
					return $this->redirect(array('controller'=>'gms','action'=>'index'));
		}
                
                public function back1()
		{
					$userid = $this->Session->read('userid');
					$this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
					$this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
					return $this->redirect(array('controller'=>'gms','action'=>'imprest_entry'));
		}
                	
        public function add_grn_tmp()
        {
            $data['FinanceYear'] = $this->request->data['FinanceYear'];
            $data['FinanceMonth'] = $this->request->data['FinanceMonth'];
            $data['HeadId'] = $this->request->data['HeadId'];
            $data['SubHeadId'] = $this->request->data['SubHeadId'];
            $data['Vendor'] = $this->request->data['vendorId'];
            $data['bill_no'] = $this->request->data['BillNo'];
            $data['Amount'] = $this->request->data['Amount'];
            $data['Description'] = addslashes($this->request->data['description']);
            $data['ExpenseDate'] = date('d-m-Y');
            $data['EntryStatus'] = $this->request->data['entry_status'];
            $data['bill_date'] = $this->request->data['bill_date'];
            $data['CompId'] = $this->request->data['CompId'];
            $ExpenseEntryType = $this->request->data['ExpenseEntryType'];
            $due_date = explode('-',$this->request->data['due_date']);
            $new_due_date[0] = $due_date[2];
            $new_due_date[1] = $due_date[1];
            $new_due_date[2] = $due_date[0];
            $due_date2 = implode('-',$new_due_date);
            
            if($ExpenseEntryType !='Imprest')
            {
                $DueDateStatus = $this->Tbl_bgt_expensesubheadingmaster->find('first',array('conditions'=>array('SubHeadingId'=>$data['SubHeadId'])));
                $status = $DueDateStatus['Tbl_bgt_expensesubheadingmaster']['HeadType'];
            
                if($status=='A' && empty($this->request->data['due_date']))
                {
                    echo "2";
                    return;
                }
                else if(!empty($this->request->data['due_date']))
                {
                    $date_now = date("Y-m-d");
                    if(strtotime($due_date2)<=strtotime($date_now))
                    {
                        echo "3";
                        return;
                    }
                }
                //$data['due_date'] = implode('-',$new_due_date);
            }
            
            
            
            
            
            $data['multi_month_check'] = $this->request->data['multi_month_check'];
            $data['multi_month'] = implode(',',$this->request->data['multi_month']);
            
            $userid = $data['userid'] = $this->Session->read("userid");
            
            if(!$this->TmpExpenseEntryMaster->find('first',array('conditions'=>$data)))
            {
                if($this->TmpExpenseEntryMaster->save(array("TmpExpenseEntryMaster"=>$data)))
                {
                    echo "1";
                }
                else
                {
                    echo "0";
                }
            }
            else
            {
                $dataX = array();
                foreach($data as $k=>$v)
                {
                    $dataX[$k] = "'".$v."'";
                }
                
                if($this->TmpExpenseEntryMaster->updateAll($dataX,array('userid'=>$userid)))
                {
                    echo "2";
                }
                else
                {
                    echo "3";
                }   
            }
        }
        
        public function delete_grn()
        {
            $Id = $this->request->data['Id'];
            if($this->TmpExpenseEntryParticular->deleteAll(array('Id'=>$Id)))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            exit;
        }
        
        public function get_due_date()
        {
            $this->layout="ajax";
            $SubHeadingId = $this->request->data['SubHeadingId'];
            if($this->Tbl_bgt_expensesubheadingmaster->find('first',array('conditions'=>array('SubHeadingId'=>$SubHeadingId,'HeadType'=>'A'))))
            {
                echo "1"; //Display Due Date
            }
            else
            {
                echo "0";
            }
            exit;
        }
        
        
    	public function index() 
		{
                        $branchArr = "";
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        
                        
                        
			$this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
                        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
                        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));
                        
                        $VendorArr = $this->VendorMaster->query("SELECT tv.Id,tv.vendor,comp.comp_code,comp.Id FROM tbl_vendormaster tv INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` tscgd GROUP BY VendorId,BranchId,CompId)tscgd ON tv.Id = tscgd.VendorId
                        INNER JOIN `tbl_grn_access` tga ON tscgd.BranchId = tga.BranchId 
                        INNER JOIN `company_master` comp ON comp.Id = tscgd.CompId
                        WHERE UserId = '$userid' AND tv.Id>660 AND tv.vendor !='Previous Entry' and tv.active=1
                        GROUP BY tv.Id,tv.vendor,comp.id ORDER BY tv.vendor");
                        
                        $Vendor = array();
                        foreach($VendorArr as $post)
                        {
                            $Vendor[$post['tv']['Id'].'-'.$post['comp']['Id']] =  $post['tv']['vendor']."(".$post['comp']['comp_code'].")";
                        }
                        
                        $this->set('Vendor',$Vendor);
			$this->set('result',$this->TmpExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.userid='$userid'"));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        
			if($data = $this->TmpExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','multi_month_check','multi_month'),'conditions'=>array('userid'=>$userid))))
			{
                               $VendorId = $data['TmpExpenseEntryMaster']['Vendor'];
                               $HeadId = $data['TmpExpenseEntryMaster']['HeadId'];
                                $vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId)));
                                $CompId = $data['TmpExpenseEntryMaster']['CompId'];
                                
                                if($CompId==1)
                                {
                                    $Comp='(Mas)';
                                }
                                else {
                                    $Comp='(IDC)';
                                }
                                
                                foreach($vend as $k=>$v)
                                {
                                    $Vendor[$k.'-'.$data['TmpExpenseEntryMaster']['CompId']] = $v.$Comp;
                                    
                                }
                                $this->set('Vendor' ,$Vendor);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['TmpExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                $this->set('HeadType',$this->Tbl_bgt_expensesubheadingmaster->find('first',array('fields'=>array('HeadType'),
                                'conditions'=>array('SubHeadingId'=>$data['TmpExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['TmpExpenseEntryMaster']);
                                
				$this->set('ExpenseEntryMaster',$data);
                             
                                if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
                                WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
                                {
                                    
                                    foreach($data as $v)
                                    {
                                        $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                                    }
                                    $this->set('branchArr',$branchArr);
                                }
                                else
                                {
                                    $this->set('branchArr',"");
                                }
			}
			else
			{
				$data = array_fill(0,12,'');
				$data = array_values($data);
                                $this->set('head',"");
                                $this->set('SubHeading',"");
				$this->set('ExpenseEntryMaster',$data);
                                
			}
                }
	
        public function add()
        {
            $userid = $this->Session->read("userid");
            
            $ExpenTmp = $this->TmpExpenseEntryMaster->find('first',array('conditions'=>array('userid'=>$userid)));
            
            $data = $ExpenTmp['TmpExpenseEntryMaster'];
            $dataX['CompId'] = $CompId = $data['CompId'];
            $dataX['ExpenseEntryType'] = $ExpenseEntryType = "Vendor";
            $dataX['FinanceYear'] = $FinanceYear = $data['FinanceYear'];
            $dataX['FinanceMonth'] = $FinanceMonth = $data['FinanceMonth'];
            $dataX['HeadId'] = $HeadId = $data['HeadId'];
            $dataX['SubHeadId'] = $SubHeadId = $data['SubHeadId'];
            $dataX['Vendor'] = $Vendor = $data['Vendor'];
            $dataX['bill_no'] = $bill_no = $data['bill_no'];
            $dataX['bill_date'] = $bill_date = $data['bill_date'];
            $dataX['Amount'] = $Amount = $data['Amount'];
            $dataX['Description'] = $description = addslashes($data['Description']);
            $dataX['ExpenseDate'] = $ExpenseDate = date('d-m-Y');
            $dataX['EntryStatus'] = $EntryStatus = $data['EntryStatus'];
            $dataX['due_date'] = $data['due_date'];
            $dataX['multi_month_check'] = $data['multi_month_check'];
            $dataX['multi_month'] = $data['multi_month'];
            $dataX['createdate'] = date('Y-m-d H:i:s');
            $dataX['userid'] = $userid;
            $dataX['round_off'] = $this->request->data['Gms']['round_off'];
            
            if(empty($this->request->data['Gms']['grn_file']['0']['name']))
            {
                $this->Session->setFlash('Please Select Image');
                $this->redirect(array("controller"=>"Gms","action"=>"index"));
            }
             
            
            $BranchArr = $this->TmpExpenseEntryParticular->query("SELECT bm.Id,SUM(Total) Amt FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm ON teep.CostCenterId = cm.Id
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name WHERE teep.userid = '$userid' GROUP BY cm.branch ");
            $flag = true; $Amount = 0;
            foreach($BranchArr as $post)
            {
                $BranchId =$post['bm']['Id']; $TotalAmount = $post['0']['Amt'];
                $BudgetArr = $this->ExpenseMaster->find('first',array('fields'=>array('Id','Amount'),
                'conditions'=>array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
                
                
                
                $BalArr = $this->TmpExpenseEntryParticular->query("SELECT SUM(eep.Amount) BalAmt FROM `expense_entry_particular` eep
INNER JOIN `expense_entry_master` eem ON eep.ExpenseEntry = eem.Id
INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
WHERE bm.Id='$BranchId' AND eem.FinanceYear='$FinanceYear`' AND eem.FinanceMonth = '$FinanceMonth' AND eem.HeadId = '$HeadId' 
AND eem.SubHeadId='$SubHeadId'");
            
               $BalAmt = $BalArr['0']['0']['BalAmt']; 
               if(empty($BalAmt))
               {
                   $BalAmt = 0;
               }
                $Parent = $BudgetArr['ExpenseMaster']['Id'];
                $Budget = $BudgetArr['ExpenseMaster']['Amount'];
                
//                if(($BalAmt-$TotalAmount)<0)
//                {
//                    echo "ss";
//                    $flag = false;
//                    $this->Session->setFlash(__("Balance Amount Is Lest than Bill Amount. Reopen Business Case"));
//                    break;
//                }
                
                if(intval($Budget-$BalAmt-$TotalAmount)==0)
                {
                     $this->ExpenseMaster->updateAll(array('EntryStatus'=>'0'),array('Id'=>$Parent)); 
                }
                
                
                $Amount += $TotalAmount;
            }
            
            if($flag)
            {
                
                if($this->ExpenseEntryApproveMaster->save(array('ExpenseEntryApproveMaster'=>$dataX)))
                {
                     $Id = $this->ExpenseEntryApproveMaster->getLastInsertId();
                    $flag = true;
                    $Transaction = $this->ExpenseEntryApproveMaster->getDataSource(); //start transaction
                   
                    if(!empty($this->request->data['Gms']['grn_file']['0']['name']))
                    {
                        foreach($this->request->data['Gms']['grn_file'] as $files)
                        {
                         $file = $files;
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         move_uploaded_file($file['tmp_name'],WWW_ROOT."/GRN/".$Id.$file['name']);
                         $PaymentFile[] =$Id.$file['name'];
                        } 
                        $PaymentFile = implode(',',$PaymentFile); 
                         $this->ExpenseEntryApproveMaster->updateAll(array('grn_file'=>"'$PaymentFile'"),array('Id'=>$Id)); 
                    }
                    
                    
                    
                    //start blocking here///
                    
//                    $CntArr = $this->ExpenseEntryMaster->query("SELECT COUNT(1) cnt FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth'");
//                    $GrnCnt = $CntArr['0']['0']['cnt'];
//
//                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
//
//                    if($monthArray[$FinanceMonth]<=3) 
//                    {
//                        $FinanceYear1 = explode('-',$FinanceYear);
//                        $FinanceYear2 = $FinanceYear1[1]-1;
//                    }
//                    else
//                    {
//                        $FinanceYear1 = explode('-',$FinanceYear);
//                        $FinanceYear2 = $FinanceYear1[1];
//                    }
//
//                      if($CompId==1)
//                      {
//                          $Comp = "Mas";
//                      }
//                      else
//                      {
//                          $Comp = "IDC";
//                      }
//                    $GrnNO = $Comp.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";
//
//                    $flag = true;
                    
                    
                    
                        $this->Session->setFlash(__("GRN Moved To Approval Bucket"));
                    
                       //$TParticular = $this->find('all',array('fields'=>array(''),'conditions'=>array()));

                }
                    else
                    {
                        $flag = false;
                        $this->Session->setFlash(__("GRN Not Saved! Please Try Again"));    
                    }
                    
                    if($flag)
                    {
                        //echo "1";
                        if($this->TmpExpenseEntryParticular->updateAll(array("ExpenseEntryType"=>"'".$ExpenseEntryType."'","ExpenseEntry"=>$Id),array("userid"=>$userid)))
                        {
                            //echo "2";
                            $exp_part = $this->TmpExpenseEntryParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('userid'=>$userid)));
                            //print_r($exp_part); exit;
                            foreach($exp_part as $v)
                            {
                                $v['TmpExpenseEntryParticular']['Particular'] = addslashes($v['TmpExpenseEntryParticular']['Particular']);
                                $part = $v['TmpExpenseEntryParticular'];
                                if($this->ExpenseEntryApproveParticular->saveAll($part))
                                {
                                   // echo "3";
                                     $Transaction->commit();
                                }
                                else
                                {
                                    $Transaction->rollback();
                                }
                            }   
                            
                        }
                        else
                        {
                            $flag = false;
                           $Transaction->rollback(); 
                        }
                    }
                
            }
            $this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
            $this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
            $this->redirect(array("controller"=>"Gms","action"=>"index"));
            exit;
            
        }
        
        
        public function get_sub_heading1()
        {
            $HeadingId = $this->request->data['HeadingId'];
            
            if($data = $this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$HeadingId))))
            {
                
                echo json_encode($data);
            }
            else
            {
                echo ''; 
            }
            exit;
        }
        
        public function approve_grn()
        {
            $this->layout="home";
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,tu.username,cm.company_name,vm.vendor,eemApp.Amount FROM `expense_entry_master_approve` eemApp
            INNER JOIN tbl_vendormaster vm ON eemApp.vendor = vm.Id
            INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
            INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Vendor' and eemApp.reject='1' and ApprovalDate is null")); 
        }
        
        public function approve_grn_tmp()
        {
                       
            $this->layout='home';
            $userid = $this->Session->read('userid');
            $Id = $this->params->query('Id');
            $this->set('ExpenseId',$Id);
            if($data = $this->ExpenseEntryApproveMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file','Id','round_off','multi_month_check','multi_month'),'conditions'=>array('Id'=>$Id))))
            {

                $this->set('result',$this->ExpenseEntryApproveParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular_approve` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
            WHERE teep.ExpenseEntry='$Id'"));

                  $VendorId = $data['ExpenseEntryApproveMaster']['Vendor']; 
                   $HeadId = $data['ExpenseEntryApproveMaster']['HeadId'];
                   $SubHeadId = $data['ExpenseEntryApproveMaster']['SubHeadId'];
                   
                  
                   
                    $vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId)));
                    $CompId = $data['ExpenseEntryApproveMaster']['CompId'];

                    
                    $this->set('gstType',$this->ExpenseEntryApproveMaster->query("SELECT IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,tscgd.GSTEnable FROM `expense_entry_master_approve` 
                    em INNER JOIN expense_entry_particular_approve eep ON em.Id=eep.ExpenseEntry  
                    INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
                    INNER JOIN branch_master bm ON cm.branch = bm.branch_name
                    INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
                    INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd 
                    ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
                    WHERE  em.Id='$Id' 
                    GROUP BY cm.branch,em.Id"));

                    if($CompId==1)
                    {
                        $Comp='(Mas)';
                    }
                    else {
                        $Comp='(IDC)';
                    }

                    foreach($vend as $k=>$v)
                    {
                        $Vendor[$k.'-'.$data['ExpenseEntryApproveMaster']['CompId']] = $v.$Comp;

                    }
                    $this->set('Vendor' ,$Vendor);
                    $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));

                    $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                    'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryApproveMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));

                    $data = array_values($data['ExpenseEntryApproveMaster']);
                    $this->set('ExpenseEntryMaster',$data);

                    if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
                    WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
                    {

                        foreach($data as $v)
                        {
                            $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                        }
                        $this->set('branchArr',$branchArr);
                    }
                    else
                    {
                        $this->set('branchArr',"");
                    }
            }
			
            if($this->request->is('POST'))
            {

                if(!empty($this->request->data['Approve']))
                {
                    $ExpenseId = $this->request->data['ExpenseId'];
                    $ApprovalDate = date('Y-m-d H:i:s');
                    
                    if($this->ExpenseEntryApproveMaster->updateAll(array('ApprovalDate'=>"'$ApprovalDate'",'ApprovedBy'=>$userid),array('Id'=>$ExpenseId)))
                    {
                        $this->Session->setFlash(__("GRN has been approved. and moved for second approval"));
                        $this->redirect(array('action'=>'approve_grn'));
                    }
                    else
                    {
                        $this->Session->setFlash(__("GRN No. Not Approved Successfully! Please Try Again"));
                    }
                }
                else
                {
                    $RejectRemarks = addslashes($this->request->data['RejectRemarks']);
                    $ExpenseId = $this->request->data['ExpenseId'];
                    $ExpenTmp = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                    $dataY= $ExpenTmp['ExpenseEntryApproveMaster'];
                    $this->ExpenseEntryApproveMaster->updateAll(array('RejectRemarks'=>"'".$RejectRemarks."'",'Reject'=>'0','RejectDate'=>"'".date('Y-m-d H:i:s')."'",'RejectBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$ExpenseId));

                    $dataZ = $this->ExpenseEntryApproveParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$ExpenseId)));

                    $this->ExpenseEntryApproveMaster->query("INSERT INTO `expense_master_reject` SET ExpenseId='$ExpenseId',"
                            . "RejectRemarks='$RejectRemarks',CreateBy='".$dataY['userid']."',CreateDate='".$dataY['createdate']."',"
                            . "BranchId='".$dataZ['ExpenseEntryApproveParticular']['BranchId']."',FinanceMonth='".$dataY['FinanceMonth']."',FinanceYear='".$dataY['FinanceYear']."',CompId='".$dataY['CompId']."',RejectBy='$userid',RejectDate=now()");
                   $this->redirect(array('action'=>'approve_grn'));
                   $this->Session->setFlash(__("GRN Rejected and Moved To User Bucket")); 
                }
            }
                        
                        
                }

        
      public function approve_grn2()
        {
            $this->layout="home";
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,tu.username,cm.company_name,vm.vendor,eemApp.Amount FROM `expense_entry_master_approve` eemApp
            INNER JOIN tbl_vendormaster vm ON eemApp.vendor = vm.Id
            INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
            INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Vendor' and eemApp.reject='1' and ApprovedBy is not null")); 
        }
        
        public function approve_grn_tmp2()
        {
                       
            $this->layout='home';
            $userid = $this->Session->read('userid');
            $Id = $this->params->query('Id');
            $this->set('ExpenseId',$Id);
            if($data = $this->ExpenseEntryApproveMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file','Id','round_off','multi_month_check','multi_month'),'conditions'=>array('Id'=>$Id))))
            {

                $this->set('result',$this->ExpenseEntryApproveParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular_approve` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
            WHERE teep.ExpenseEntry='$Id'"));

                  $VendorId = $data['ExpenseEntryApproveMaster']['Vendor']; 
                   $HeadId = $data['ExpenseEntryApproveMaster']['HeadId'];
                   $SubHeadId = $data['ExpenseEntryApproveMaster']['SubHeadId'];
                   
                    $vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId)));
                    $CompId = $data['ExpenseEntryApproveMaster']['CompId'];

                    
                    $this->set('gstType',$this->ExpenseEntryApproveMaster->query("SELECT IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,tscgd.GSTEnable FROM `expense_entry_master_approve` 
                    em INNER JOIN expense_entry_particular_approve eep ON em.Id=eep.ExpenseEntry  
                    INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
                    INNER JOIN branch_master bm ON cm.branch = bm.branch_name
                    INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
                    INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd 
                    ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
                    WHERE  em.Id='$Id' 
                    GROUP BY cm.branch,em.Id"));

                    if($CompId==1)
                    {
                        $Comp='(Mas)';
                    }
                    else {
                        $Comp='(IDC)';
                    }

                    foreach($vend as $k=>$v)
                    {
                        $Vendor[$k.'-'.$data['ExpenseEntryApproveMaster']['CompId']] = $v.$Comp;

                    }
                    $this->set('Vendor' ,$Vendor);
                    $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));

                    $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                    'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryApproveMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));

                    $data = array_values($data['ExpenseEntryApproveMaster']);
                    $this->set('ExpenseEntryMaster',$data);

                    if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
                    WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
                    {

                        foreach($data as $v)
                        {
                            $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                        }
                        $this->set('branchArr',$branchArr);
                    }
                    else
                    {
                        $this->set('branchArr',"");
                    }
            }
			
            if($this->request->is('POST'))
            {

                if(!empty($this->request->data['Approve']))
                {
                    $ExpenseId = $this->request->data['ExpenseId'];
                    $userid = $this->Session->read("userid");
                    $ExpenTmp = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                    $dataY= $ExpenTmp['ExpenseEntryApproveMaster'];
                    $dataX = Hash::remove($dataY,'Id');
                    
                    $dater = explode(' ',$dataX['createdate']);
                    $dater1 = explode('-',$dater[0]);
                    $newDater[0] = $dater1[2];
                    $newDater[1] = $dater1[1];
                    $newDater[2] = $dater1[0];
                    $dataX['ExpenseDate'] = implode('-',$newDater);
                    
                    $FinanceYear = $dataX['FinanceYear'];
                    $HeadId = $dataY['HeadId'];
                    
                    $UNList = $this->ExpenseEntryMaster->query("SELECT HeadId,StartWith FROM `tbl_grn_unique_list` UNList where HeadId='$HeadId'"); 
                    
                    foreach($UNList as $un)
                    {
                        $NewList[$un['UNList']['HeadId']] = $NewList[$un['UNList']['StartWith']];
                    }
                    
                    if(!empty($NewList))
                    {
                        $UniqueHead = $NewList[$HeadId];
                        $autoArr = $this->ExpenseEntryMaster->query("Select max(uid_auto)uid_auto from expense_entry_master where HeadId='$HeadId'"); 
                    }
                    else
                    {
                        $UniqueHead = 'Vend';
                        $autoArr = $this->ExpenseEntryMaster->query("Select max(uid_auto)uid_auto from expense_entry_master where UniqueHead='Vend'"); 
                    }
                     $auto = $autoArr['0']['0']['uid_auto'];
                     if(empty($auto))
                     {
                         $auto = 1;
                     }
                     else
                     {
                         $auto++;
                     }
                     $len = strlen($auto);
                     
                     for($i=0;$i<=(3-$len);$i++)
                     {
                         $strzero .='0';
                     }
                     
                    if($FinanceYear=='2018-19')
                    {    
                        $dataX['uid_auto'] = $auto;
                        $autoVal =$UniqueHead. "/".'2018-19'.'/'.$strzero.$auto; 
                        $dataX['UniqueId'] = $autoVal;
                        $dataX['UniqueHead'] = $UniqueHead;
                    }
                    
                    $FinanceMonthMulti = $FinanceMonth = $dataX['FinanceMonth'];
                    $CompId =      $dataX['CompId'];
                    $Transaction = $this->ExpenseEntryMaster->getDataSource(); //start transaction
                    $CntArr = $this->ExpenseEntryMaster->query("SELECT SUBSTRING_INDEX(GrnNo,'/',-1) cnt FROM `expense_entry_master` em WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth' AND id IN (SELECT MAX(Id) FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth')");
                       
                    if($dataX['multi_month_check']!='1')
                    {
                        if($this->ExpenseEntryMaster->save(array('ExpenseEntryMaster'=>$dataX)))
                        {
                            $flag = true;   
                            $Id = $this->ExpenseEntryMaster->getLastInsertId(); 
                            $GrnCnt = intval($CntArr['0']['0']['cnt']); 
                            $GrnCnt = $GrnCnt+1;



                            $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

                            if($monthArray[$FinanceMonth]<=3) 
                            {
                                $FinanceYear1 = explode('-',$FinanceYear);
                                $FinanceYear2 = $FinanceYear1[1];
                            }
                            else
                            {
                                $FinanceYear1 = explode('-',$FinanceYear);
                                $FinanceYear2 = $FinanceYear1[1]-1;
                            }

                            if($CompId==1)
                            {
                                $Comp = "Mas";
                            }
                            else
                            {
                                $Comp = "IDC";
                            }
                            $GrnNO = $Comp.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";

                            if($this->ExpenseEntryMaster->updateAll(array('GrnNo'=> "'". addslashes($GrnNO)."'",'ApprovalDate'=>"'".date('Y-m-d H:i:s')."'",'ApprovedBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$Id)))
                            {
                                $this->Session->setFlash(__("GRN $GrnNO with Unique No. $autoVal has been Approved Successfully")); 
                            }
                            else
                            {

                            }
                    }
                        else
                        {
                            $flag = false;
                            $this->Session->setFlash(__("GRN Not Saved! Please Try Again"));    
                        }

                        if($flag)
                        {
                            //echo "1";
                            if($this->ExpenseEntryApproveParticular->updateAll(array("ExpenseEntry"=>$Id),array("ExpenseEntry"=>$ExpenseId)))
                            {
                                //echo "2";
                                $exp_part = $this->ExpenseEntryApproveParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('ExpenseEntry'=>$Id)));
                                //print_r($exp_part); exit;
                                foreach($exp_part as $v)
                                {
                                    $v['ExpenseEntryApproveParticular']['Particular'] = addslashes($v['ExpenseEntryApproveParticular']['Particular']);
                                    $part = $v['ExpenseEntryApproveParticular'];
                                    $part['ExpenseEntry'] = $Id;
                                    if($this->ExpenseEntryParticular->saveAll($part))
                                    {
                                       // echo "3";
                                         $Transaction->commit();
                                    }
                                    else
                                    {
                                        $Transaction->rollback();
                                    }
                                }   

                            }
                            else
                            {
                                $flag = false;
                               $Transaction->rollback(); 
                            }
                        }
                    }
                    else 
                    {
                        $months = explode(',',$dataX['multi_month']);

                        $monthArr = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun',
'7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');

                       foreach($months as $v)
                       {
                           if(!empty($monthArr[$v]))
                           {
                                $NewMonthArr[] =  $monthArr[$v];
                           }
                       }
                        $NewMonthArr[] = $dataX['FinanceMonth'];
                        $TotalMonth = count($NewMonthArr);  
                        //print_r($NewMonthArr); exit;

                                //echo "2";
                        $exp_part = $this->ExpenseEntryApproveParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('ExpenseEntry'=>$Id)));
                        //print_r($exp_part); exit;
                        $flag = true;;
                        foreach($NewMonthArr as $FinanceMonth)
                        {
                            $Amount = 0; $Cgst = 0; $Sgst = 0; $Igst=0;
                            $ParticularIds =array();
                            foreach($exp_part as $v)
                            {
                                $v['ExpenseEntryApproveParticular']['Particular'] = addslashes($v['ExpenseEntryApproveParticular']['Particular']);
                                $part = $v['ExpenseEntryApproveParticular'];
                                //$part['ExpenseEntry'] = $Id;

                                $part['Amount'] = round($part['Amount']/$TotalMonth,3);

                                $part['Tax'] = round(($part['Amount']*$part['Rate'])/100,3);
                                $part['Total'] = round($part['Amount']+$part['Tax'],3);
                               // print_r($part); exit;
                                if($this->ExpenseEntryParticular->saveAll($part))
                                {
                                    $Amount += $part['Total'];
                                    $ParticularIds[] =$this->ExpenseEntryParticular->getLastInsertId();
                                }
                                else
                                {
                                    $Transaction->rollback();
                                    $flag = false;
                                    break;
                                }
                                $dataPart[] = $part;
                            }
                            $dataX['Amount'] =  round($Amount,3);
                            $dataX['FinanceMonth'] =  $FinanceMonth;

                            if($this->ExpenseEntryMaster->saveAll(array('ExpenseEntryMaster'=>$dataX)))
                            {
                                $LastId = $this->ExpenseEntryMaster->getLastInsertId(); 
                                $DataIds[] = $LastId;
                                if($this->ExpenseEntryParticular->updateAll(array('ExpenseEntry'=>$LastId),array('Id'=>$ParticularIds)))
                                {

                                }
                                else
                                {
                                    $flag = false;
                                    $Transaction->rollback();
                                }
                            }
                            else
                            {
                                $flag = false;
                                $Transaction->rollback();
                                break;
                            }


                        }


                        if($flag)
                        {
                            $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

                            if($monthArray[$FinanceMonthMulti]<=3) 
                            {
                                $FinanceYear1 = explode('-',$FinanceYear);
                                $FinanceYear2 = $FinanceYear1[1];
                            }
                            else
                            {
                                $FinanceYear1 = explode('-',$FinanceYear);
                                $FinanceYear2 = $FinanceYear1[1]-1;
                            }

                            if($CompId==1)
                            {
                                $Comp = "Mas";
                            }
                            else
                            {
                                $Comp = "IDC";
                            }

                            $GrnCnt = intval($CntArr['0']['0']['cnt']); 
                            $GrnCnt = $GrnCnt+1;


                            $GrnNO = $Comp.'/'.$monthArray[$FinanceMonthMulti].'/'.$FinanceYear2."/"."$GrnCnt";

                            if($this->ExpenseEntryMaster->updateAll(array('GrnNo'=> "'". addslashes($GrnNO)."'",'ApprovalDate'=>"'".date('Y-m-d H:i:s')."'",'ApprovedBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$DataIds)))
                            {
                                $Transaction->commit();    
                                $this->Session->setFlash(__("GRN No. ".$GrnNO." With Unique $autoVal Approved Successfully"));
                            }
                            else
                            {
                                $Transaction->rollback();    
                            }
                        }

                    }
                    if($flag)
                    {
                        $this->Session->setFlash(__("GRN No. ".$GrnNO." With Unique $autoVal Approved Successfully"));
                    }
                    else
                    {
                        $this->Session->setFlash(__("GRN No. Not Approved Successfully! Please Try Again"));
                    }
                    
                    $this->ExpenseEntryApproveMaster->deleteAll(array('Id'=>$ExpenseId));
                    $this->ExpenseEntryApproveParticular->deleteAll(array('ExpenseEntry'=>$Id));
                    $this->redirect(array('action'=>'approve_grn2'));

                }
                else
                {
                    $RejectRemarks = addslashes($this->request->data['RejectRemarks']);
                    $ExpenseId = $this->request->data['ExpenseId'];
                    $ExpenTmp = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                    $dataY= $ExpenTmp['ExpenseEntryApproveMaster'];
                    $this->ExpenseEntryApproveMaster->updateAll(array('ApprovedBy'=>null,'ApprovalDate'=>null,'RejectRemarks'=>"'".$RejectRemarks."'",'Reject'=>'0','RejectDate'=>"'".date('Y-m-d H:i:s')."'",'RejectBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$ExpenseId));

                    $dataZ = $this->ExpenseEntryApproveParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$ExpenseId)));

                    $this->ExpenseEntryApproveMaster->query("INSERT INTO `expense_master_reject` SET ExpenseId='$ExpenseId',"
                            . "RejectRemarks='$RejectRemarks',CreateBy='".$dataY['userid']."',CreateDate='".$dataY['createdate']."',"
                            . "BranchId='".$dataZ['ExpenseEntryApproveParticular']['BranchId']."',FinanceMonth='".$dataY['FinanceMonth']."',FinanceYear='".$dataY['FinanceYear']."',CompId='".$dataY['CompId']."',RejectBy='$userid',RejectDate=now()");
                   $this->redirect(array('action'=>'approve_grn2'));
                   $this->Session->setFlash(__("GRN Rejected and Moved To User Bucket")); 
                }
            }
                        
                        
                }
                
                
    public function imprest_entry() 
    {
        $branchArr = "";
        $this->layout='home';
        $userid = $this->Session->read('userid');
        $role=$this->Session->read("role");
        $EntryType = $this->params->query['entryType'];

        if($role=='admin')
        {
            $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'), 'conditions'=>array('active'=>1),
            'order' => array('branch_name' => 'asc')))); 
        }
        else
        {
           $bm = $this->Addbranch->query("Select bm.Id,bm.branch_name from branch_master bm inner join tbl_grn_access tga on bm.Id = tga.BranchId where bm.active = 1");
           $branch_master = array();
           foreach($bm as $post)
           {
               $branch_master[$post['bm']['Id']] = $post['bm']['branch_name'];
           }
           $this->set('branch_master', $branch_master);
        }

 
        
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""),'order'=>array('HeadingDesc'=>'asc'))));
        $this->set('result',$this->TmpExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
        WHERE teep.userid='$userid'"));
        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));

        if($data = $this->TmpExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId'),'conditions'=>array('userid'=>$userid))))
        {
            $VendorId = $data['TmpExpenseEntryMaster']['Vendor'];
            $HeadId = $data['TmpExpenseEntryMaster']['HeadId'];
            $this->set('Vendor' , $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId))));
            
            
               // $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));

            $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
            'conditions'=>array('SubHeadingId'=>$data['TmpExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));

            $data = array_values($data['TmpExpenseEntryMaster']);
            $this->set('ExpenseEntryMaster',$data);

            if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
            WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
            {

                foreach($data as $v)
                {
                    $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                }
                $this->set('branchArr',$branchArr);
            }
            else
            {
                $this->set('branchArr',"");
            }
        }
        else
        {
            $data = array_fill(0,10,'');
            $data = array_values($data);
            
            //$this->set('head',"");
            $this->set('SubHeading',"");
            $this->set('ExpenseEntryMaster',$data);

        }
    }
                
    public function imprest_add()
    {
            $userid = $this->Session->read("userid");
            $ExpenTmp = $this->TmpExpenseEntryMaster->find('first',array('conditions'=>array('userid'=>$userid)));
            
            $data = $ExpenTmp['TmpExpenseEntryMaster'];
            $dataX['CompId'] = $CompId = $data['CompId'];
            $dataX['ExpenseEntryType'] = $ExpenseEntryType = "Imprest";
            $dataX['FinanceYear'] = $FinanceYear = $data['FinanceYear'];
            $dataX['FinanceMonth'] = $FinanceMonth = $data['FinanceMonth'];
            $dataX['HeadId'] = $HeadId = $data['HeadId'];
            $dataX['SubHeadId'] = $SubHeadId = $data['SubHeadId'];
            $dataX['bill_no'] = $bill_no = $data['bill_no'];
            $dataX['bill_date'] = $bill_date = $data['bill_date'];
            $dataX['Amount'] = $Amount = $data['Amount'];
            $dataX['Description'] = $description = $data['Description'];
            
            $dataX['EntryStatus'] = $EntryStatus = $data['EntryStatus'];
            $dataX['createdate'] = date('Y-m-d H:i:s');
            $dataX['userid'] = $userid;
            
            if(empty($this->request->data['Gms']['grn_file']['name']))
            {
                $this->Session->setFlash('Please Select Image');
                $this->redirect(array("controller"=>"Gms","action"=>"imprest_entry"));
            }
            
            
            $BranchArr = $this->TmpExpenseEntryParticular->query("SELECT bm.Id,SUM(Total) Amt FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm ON teep.CostCenterId = cm.Id
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name WHERE teep.userid = '$userid' GROUP BY cm.branch ");
            $flag = true; $Amount = 0;
            foreach($BranchArr as $post)
            {
                $BranchId =$post['bm']['Id']; $TotalAmount = $post['0']['Amt'];
                $BudgetArr = $this->ExpenseMaster->find('first',array('fields'=>array('Id','Amount'),
                'conditions'=>array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
                
                
                
                $BalArr = $this->TmpExpenseEntryParticular->query("SELECT SUM(eep.Amount) BalAmt FROM `expense_entry_particular` eep
                INNER JOIN `expense_entry_master` eem ON eep.ExpenseEntry = eem.Id
                INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
                INNER JOIN branch_master bm ON cm.branch = bm.branch_name
                WHERE bm.Id='$BranchId' AND eem.FinanceYear='$FinanceYear`' AND eem.FinanceMonth = '$FinanceMonth' AND eem.HeadId = '$HeadId' 
                AND eem.SubHeadId='$SubHeadId'");
            
               $BalAmt = $BalArr['0']['0']['BalAmt']; 
               if(empty($BalAmt))
               {
                   $BalAmt = 0;
               }
                $Parent = $BudgetArr['ExpenseMaster']['Id'];
                $Budget = $BudgetArr['ExpenseMaster']['Amount'];
                
//                if(($BalAmt-$TotalAmount)<0)
//                {
//                    echo "ss";
//                    $flag = false;
//                    $this->Session->setFlash(__("Balance Amount Is Lest than Bill Amount. Reopen Business Case"));
//                    break;
//                }
                if(intval($Budget-$BalAmt-$TotalAmount)==0)
                {
                     $this->ExpenseMaster->updateAll(array('EntryStatus'=>'0'),array('Id'=>$Parent)); 
                }
                $Amount += $TotalAmount;
            }
            
            if($flag)
            {
                echo "0";
                if($this->ExpenseEntryApproveMaster->save(array('ExpenseEntryApproveMaster'=>$dataX)))
                {
                    $Transaction = $this->ExpenseEntryApproveMaster->getDataSource(); //start transaction
                    $Id = $this->ExpenseEntryApproveMaster->getLastInsertId();
                    if(!empty($this->request->data['Gms']['grn_file']['name']))
                    {
                         $file = $this->request->data['Gms']['grn_file'];
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         move_uploaded_file($file['tmp_name'],WWW_ROOT."/GRN/".$Id.$file['name']);
                         $PaymentFile =addslashes($Id.$file['name']);
                         $this->ExpenseEntryApproveMaster->updateAll(array('grn_file'=>"'$PaymentFile'"),array('Id'=>$Id)); 
                    }
                    $this->Session->setFlash(__("Imprest Entry Moved To Approval Bucket"));   
                    
                    
//                    $CntArr = $this->ExpenseEntryMaster->query("SELECT COUNT(1) cnt FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth'");
//                    $GrnCnt = $CntArr['0']['0']['cnt'];
//
//                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
//
//                    if($monthArray[$FinanceMonth]<=3) 
//                    {
//                        $FinanceYear1 = explode('-',$FinanceYear);
//                        $FinanceYear2 = $FinanceYear1[1]-1;
//                    }
//                    else
//                    {
//                        $FinanceYear1 = explode('-',$FinanceYear);
//                        $FinanceYear2 = $FinanceYear1[1];
//                    }
//
//
//                    if($CompId==1)
//                      {
//                          $Comp = "Mas";
//                      }
//                      else
//                      {
//                          $Comp = "IDC";
//                      }
//                    $GrnNO = $Comp.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";
//
//                    $flag = true;
//                    if(!$this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id)))
//                    {
//                        $Transaction->rollback();
//                        $flag = false;
//                        $this->Session->setFlash(__("Grn No. Not Saved Successfully! Please Try Again"));
//                    }
//                    else
//                    {
//                        $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
//                    }
//                       //$TParticular = $this->find('all',array('fields'=>array(''),'conditions'=>array()));

                    }
                    else
                    {
                        $flag = false;
                        $this->Session->setFlash(__("Imprest Entry Not Saved. Please Try Again!"));    
                    }
                    
                    if($flag)
                    {
                        //echo "1";
                        if($this->TmpExpenseEntryParticular->updateAll(array("ExpenseEntryType"=>"'".$ExpenseEntryType."'","ExpenseEntry"=>$Id),array("userid"=>$userid)))
                        {
                            //echo "2";
                            $exp_part = $this->TmpExpenseEntryParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('userid'=>$userid)));
                            //print_r($exp_part); exit;
                            foreach($exp_part as $v)
                            {
                                $part = $v['TmpExpenseEntryParticular'];
                                if($this->ExpenseEntryApproveParticular->saveAll($part))
                                {
                                   // echo "3";
                                     $Transaction->commit();
                                }
                                else
                                {
                                    $Transaction->rollback();
                                }
                            }    
                        }
                        else
                        {
                            $flag = false;
                           $Transaction->rollback(); 
                        }
                    }
                
            }
            $this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
            $this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
            $this->redirect(array("controller"=>"Gms","action"=>"imprest_entry"));
            exit;
            
    }
    
    public function approve_imprest()
        {
            $this->layout="home";
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,tu.username,cm.company_name,eemApp.Amount,eemApp.ApprovalDate FROM `expense_entry_master_approve` eemApp
INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Imprest' and Reject = 1")); 
        }
    
        
     public function update_grn_tmp()
        {
            $ExpenseId = $this->request->data['ExpenseId'];
            $data['FinanceYear'] = $this->request->data['FinanceYear'];
            $data['FinanceMonth'] = $this->request->data['FinanceMonth'];
            $data['HeadId'] = $this->request->data['HeadId'];
            $data['SubHeadId'] = $this->request->data['SubHeadId'];
            $data['Vendor'] = $this->request->data['vendorId'];
            $data['bill_no'] =$this->request->data['BillNo'];
            $data['Amount'] = $this->request->data['Amount'];
            $data['Description'] = addslashes($this->request->data['description']);
            
            $data['EntryStatus'] = $this->request->data['entry_status'];
            $data['bill_date'] = $this->request->data['bill_date'];
            $data['CompId'] = $this->request->data['CompId'];
            
            
            $dataX = array();
                foreach($data as $k=>$v)
                {
                    $dataX[$k] = "'".$v."'";
                }
                
                if($this->ExpenseEntryApproveMaster->updateAll($dataX,array('Id'=>$ExpenseId)))
                {
                    echo "1";
                }
                else
                {
                    echo "0";
                } 
        }    
     
    public function delete_grn1()
        {
            $Id = $this->request->data['Id'];
            if($this->ExpenseEntryApproveParticular->deleteAll(array('Id'=>$Id)))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            exit;
        }    
    
        
    public function add_field_value1()
        {
            $data['Particular'] = addslashes($this->request->data['Particular']);
            $data['ExpenseEntryType'] = $this->request->data['ExpenseEntryType'];
            $data['BranchId'] = $this->request->data['BranchId'];
            $data['CostCenterId'] = $this->request->data['CostCenter'];
            $data['Amount'] = $this->request->data['Amount'];
            $data['Rate'] = $this->request->data['Rate'];
            $data['Tax'] = round(($data['Amount']*$data['Rate'])/100,3);
            $data['Total'] = round($data['Amount']+$data['Tax'],3);
            $data['ExpenseEntry'] = $this->request->data['ExpenseEntry'];
            $data['createdate'] = date("Y-m-d H:i:s");
            $data['userid'] = $this->Session->read("userid");
            
            if($this->ExpenseEntryApproveParticular->save(array('ExpenseEntryApproveParticular'=>$data)))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            exit;
        }    
        
    public function approve_imprest_tmp()
        {
                       
			$branchArr = "";
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        $ExpenseId = $this->params->query['Id'];
                        $this->set('ExpenseId',$ExpenseId);
                        
			$this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
                        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));
                        
                        
			$this->set('result',$this->ExpenseEntryApproveParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular_approve` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.ExpenseEntry='$ExpenseId'"));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        
			if($data = $this->ExpenseEntryApproveMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file'),'conditions'=>array('Id'=>$ExpenseId))))
			{
                               
                               $HeadId = $data['ExpenseEntryApproveMaster']['HeadId'];
                                
                                $CompId = $data['ExpenseEntryApproveMaster']['CompId'];
                                
                                if($CompId==1)
                                {
                                    $Comp='(Mas)';
                                }
                                else {
                                    $Comp='(IDC)';
                                }
                                
                               // $this->set('HeadId',$HeadId);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryApproveMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['ExpenseEntryApproveMaster']);
				$this->set('ExpenseEntryMaster',$data);
                             
                                
			}
			else
			{
				$data = array_fill(0,10,'');
				$data = array_values($data);
                                $this->set('head',"");
                                $this->set('SubHeading',"");
				$this->set('ExpenseEntryMaster',$data);
                                
			}
                }   
                
    public function approve_imprest_tmp1()
    {
        $userid = $this->Session->read("userid");
        if($this->request->is('POST'))
                        {
                            
                            if(!empty($this->request->data['Approve']))
                            {
                                $ExpenseId = $this->request->data['ExpenseId'];
                                
                                $ExpenTmp = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                                $dataY= $ExpenTmp['ExpenseEntryApproveMaster'];
                                $dataX = Hash::remove($dataY,'Id');
                                
                                 $dater = explode(' ',$dataX['createdate']);
                                $dater1 = explode('-',$dater[0]);
                                $newDater[0] = $dater1[2];
                                $newDater[1] = $dater1[1];
                                $newDater[2] = $dater1[0];
                                $dataX['ExpenseDate'] = implode('-',$newDater);
                                
                                
                                $FinanceYear = $dataX['FinanceYear'];
                                $FinanceMonth = $dataX['FinanceMonth'];
                                $CompId =      $dataX['CompId'];
                                $Transaction = $this->ExpenseEntryMaster->getDataSource(); //start transaction
                                $CntArr = $this->ExpenseEntryMaster->query("SELECT SUBSTRING_INDEX(GrnNo,'/',-1) cnt FROM `expense_entry_master` em WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth' AND id IN (SELECT MAX(Id) FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth')");
                                if($this->ExpenseEntryMaster->save(array('ExpenseEntryMaster'=>$dataX)))
                                {
                                    $flag = true;
                                    $Id = $this->ExpenseEntryMaster->getLastInsertId(); 
                                    
                                    $GrnCnt = intval($CntArr['0']['0']['cnt']); 
                                    $GrnCnt = $GrnCnt+1;

                                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

                                    if($monthArray[$FinanceMonth]<=3) 
                                    {
                                        $FinanceYear1 = explode('-',$FinanceYear);
                                        $FinanceYear2 = $FinanceYear1[1];
                                    }
                                    else
                                    {
                                        $FinanceYear1 = explode('-',$FinanceYear);
                                        $FinanceYear2 = $FinanceYear1[1]-1;
                                    }

                                    if($CompId==1)
                                    {
                                        $Comp = "Mas";
                                    }
                                    else
                                    {
                                        $Comp = "IDC";
                                    }
                                    $GrnNO = $Comp.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";
                                    
                                    if($this->ExpenseEntryMaster->updateAll(array('GrnNo'=> "'". addslashes($GrnNO)."'",'ApprovalDate'=>"'".date('Y-m-d H:i:s')."'",'ApprovedBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$Id)))
                                    {
                                        $this->Session->setFlash(__("Imprest Saved and Moved Approval Bucket")); 
                                    }
                                    else
                                    {
                                        
                                    }
                                }
                                else
                                {
                                    $flag = false;
                                    $this->Session->setFlash(__("Imprest Not Saved! Please Try Again"));    
                                }
                                
                                 if($flag)
                                    {
                                        //echo "1";
                                        if($this->ExpenseEntryApproveParticular->updateAll(array("ExpenseEntry"=>$Id),array("ExpenseEntry"=>$ExpenseId)))
                                        {
                                            //echo "2";
                                            $exp_part = $this->ExpenseEntryApproveParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('ExpenseEntry'=>$Id)));
                                            //print_r($exp_part); exit;
                                            foreach($exp_part as $v)
                                            {
                                                $v['ExpenseEntryApproveParticular']['Particular'] = addslashes($v['ExpenseEntryApproveParticular']['Particular']);
                                                $part = $v['ExpenseEntryApproveParticular'];
                                                
                                                if($this->ExpenseEntryParticular->saveAll($part))
                                                {
                                                   // echo "3";
                                                     $Transaction->commit();
                                                }
                                                else
                                                {
                                                    $Transaction->rollback();
                                                }
                                            }   

                                        }
                                        else
                                        {
                                            $flag = false;
                                           $Transaction->rollback(); 
                                        }
                                    }
                            if($flag)
                            {
                                $this->Session->setFlash(__("GRN No. ".$GrnNO.' Approved Successfully'));
                            }
                            else
                            {
                                $this->Session->setFlash(__("GRN No. Not Approved Successfully! Please Try Again"));
                            }
                           
                            $this->ExpenseEntryApproveMaster->deleteAll(array('Id'=>$ExpenseId));
                            $this->ExpenseEntryApproveParticular->deleteAll(array('ExpenseEntry'=>$Id));
                            $this->redirect(array('action'=>'approve_imprest'));
                                
                            }
                            else
                            {
                                $RejectRemarks = addslashes($this->request->data['RejectRemarks']);
                                $ExpenseId = $this->request->data['ExpenseId'];
                                $ExpenTmp = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                                $dataY= $ExpenTmp['ExpenseEntryApproveMaster'];
                               $this->ExpenseEntryApproveMaster->updateAll(array('RejectRemarks'=>"'".$RejectRemarks."'",'Reject'=>'0','RejectDate'=>"'".date('Y-m-d H:i:s')."'",'RejectBy'=>"'".$this->Session->read('userid')."'"),array('Id'=>$ExpenseId));
                                
                                $dataZ = $this->ExpenseEntryApproveParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$ExpenseId)));
                                
                                $this->ExpenseEntryApproveMaster->query("INSERT INTO `expense_master_reject` SET ExpenseId='$ExpenseId',"
                                        . "RejectRemarks='$RejectRemarks',CreateBy='".$dataY['userid']."',CreateDate='".$dataY['createdate']."',"
                                        . "BranchId='".$dataZ['ExpenseEntryApproveParticular']['BranchId']."',FinanceMonth='".$dataY['FinanceMonth']."',FinanceYear='".$dataY['FinanceYear']."',CompId='".$dataY['CompId']."',RejectBy='$userid',RejectDate=now()");
                               $this->redirect(array('action'=>'approve_imprest'));
                               $this->Session->setFlash(__("Imprest Rejected and Moved User Pending Bucket")); 
                            }
                        }
    }
    
    
    public function edit_imprest_branch()
        {
            $this->layout="home";
            $userid = $this->Session->read('userid');
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,tu.username,cm.company_name,eemApp.Amount,eemApp.RejectRemarks,eemApp.Reject FROM `expense_entry_master_approve` eemApp
INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Imprest' and eemApp.userid = '$userid'")); 
            
            
        }
        
    public function edit_imprest_tmp_branch()
        {
                       
			$branchArr = "";
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        $ExpenseId = $this->params->query['Id'];
                        $this->set('ExpenseId',$ExpenseId);
                        
			$this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
                        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));
                        
                        
			$this->set('result',$this->ExpenseEntryApproveParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular_approve` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.ExpenseEntry='$ExpenseId'"));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        
			if($data = $this->ExpenseEntryApproveMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file','RejectRemarks'),'conditions'=>array('Id'=>$ExpenseId,'userid'=>$userid))))
			{
                               
                                $HeadId = $data['ExpenseEntryApproveMaster']['HeadId'];
                                
                                $CompId = $data['ExpenseEntryApproveMaster']['CompId'];
                                
                                if($CompId==1)
                                {
                                    $Comp='(Mas)';
                                }
                                else {
                                    $Comp='(IDC)';
                                }
                                
                               // $this->set('HeadId',$HeadId);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryApproveMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['ExpenseEntryApproveMaster']);
				$this->set('ExpenseEntryMaster',$data);
                             
                                
			}
			else
			{
				$data = array_fill(0,10,'');
				$data = array_values($data);
                                $this->set('head',"");
                                $this->set('SubHeading',"");
				$this->set('ExpenseEntryMaster',$data);
                                
			}
                }       
   
    public function save_imprest_tmp1_branch()
    {
        if($this->request->is('POST'))
        {   
            $Id = $this->request->data['ExpenseId'];
            if(!empty($this->request->data['Gms']['grn_file']['name']))
                    {
                         $file = $this->request->data['Gms']['grn_file'];
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         move_uploaded_file($file['tmp_name'],WWW_ROOT."/GRN/".$Id.$file['name']);
                         $PaymentFile =addslashes($Id.$file['name']);
                         $this->ExpenseEntryApproveMaster->updateAll(array('grn_file'=>"'$PaymentFile'"),array('Id'=>$Id)); 
                    }
              $this->ExpenseEntryApproveMaster->updateAll(array('RejectRemarks'=>null,'Reject'=>"1"),array('Id'=>$Id));       
             $this->Session->setFlash(__("Imprest Saved and Moved Approval Bucket")); 
             
             $this->redirect(array('action'=>'edit_imprest_branch'));
        }
    }    
    
    public function edit_grn_branch()
        {
            $this->layout="home";
            $userid = $this->Session->read('userid');
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,tu.username,cm.company_name,eemApp.Amount,eemApp.RejectRemarks,eemApp.Reject,vm.vendor FROM `expense_entry_master_approve` eemApp
INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
left join tbl_vendormaster vm on eemApp.vendor = vm.Id
INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Vendor' and eemApp.userid = '$userid'")); 
            
            
        }
        
    public function edit_grn_tmp_branch()
        {
                       
			$branchArr = "";
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        $ExpenseId = $this->params->query['Id'];
                        $this->set('ExpenseId',$ExpenseId);
                        
			$this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
                        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));
                        
                        
			$this->set('result',$this->ExpenseEntryApproveParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular_approve` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.ExpenseEntry='$ExpenseId'"));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        
			if($data = $this->ExpenseEntryApproveMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file','RejectRemarks','round_off'),'conditions'=>array('Id'=>$ExpenseId,'userid'=>$userid))))
			{
                               $VendorId = $data['ExpenseEntryApproveMaster']['Vendor'];
                                $HeadId = $data['ExpenseEntryApproveMaster']['HeadId'];
                                //$vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>"Id>1052"));
                                
                                $VendorArr = $this->VendorMaster->query("SELECT tv.Id,tv.vendor,comp.comp_code,comp.Id FROM tbl_vendormaster tv INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` tscgd GROUP BY VendorId,BranchId,CompId)tscgd ON tv.Id = tscgd.VendorId
                        INNER JOIN `tbl_grn_access` tga ON tscgd.BranchId = tga.BranchId 
                        INNER JOIN `company_master` comp ON comp.Id = tscgd.CompId
                        WHERE UserId = '$userid' AND tv.Id>660 AND tv.vendor !='Previous Entry' and tv.active=1
                        GROUP BY tv.Id,tv.vendor,comp.id ORDER BY tv.vendor");
                        
                        $Vendor = array();
                        foreach($VendorArr as $post)
                        {
                            $Vendor[$post['tv']['Id'].'-'.$post['comp']['Id']] =  $post['tv']['vendor']."(".$post['comp']['comp_code'].")";
                        }
                                
                                
                                $this->set('Vendor' ,$Vendor);
                                
                               // $this->set('HeadId',$HeadId);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryApproveMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['ExpenseEntryApproveMaster']);
				$this->set('ExpenseEntryMaster',$data);
                             
                                
			}
			else
			{
				$data = array_fill(0,10,'');
				$data = array_values($data);
                                $this->set('head',"");
                                $this->set('SubHeading',"");
				$this->set('ExpenseEntryMaster',$data);
                                
			}
                }       
   
    public function save_grn_tmp1_branch()
    {
        if($this->request->is('POST'))
        {   
            $Id = $this->request->data['ExpenseId'];
            if(!empty($this->request->data['Gms']['grn_file']['name']))
                    {
                         $file = $this->request->data['Gms']['grn_file'];
                         $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                         move_uploaded_file($file['tmp_name'],WWW_ROOT."/GRN/".$Id.$file['name']);
                         $PaymentFile =addslashes($Id.$file['name']);
                         $this->ExpenseEntryApproveMaster->updateAll(array('grn_file'=>"'$PaymentFile'"),array('Id'=>$Id)); 
                    }
              $this->ExpenseEntryApproveMaster->updateAll(array('RejectRemarks'=>null,'Reject'=>"1"),array('Id'=>$Id));       
             $this->Session->setFlash(__("GRN Saved and Moved Approval Bucket")); 
             
             $this->redirect(array('action'=>'edit_grn_branch'));
        }
    }  
    
    
    public function view_grn()
        {
            $this->layout="home";
            $this->set('data',$this->ExpenseEntryMaster->query("SELECT eemApp.Id,eemApp.GrnNo,tu.username,cm.company_name,vm.vendor,eemApp.Amount,eemApp.FinanceYear,eemApp.FinanceMonth FROM `expense_entry_master` eemApp
INNER JOIN tbl_vendormaster vm ON eemApp.vendor = vm.Id
INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Vendor' and eemApp.reject='1'")); 
        }
        
        
        public function view_grn_tmp()
        {
                       
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        $Id = $this->params->query('Id');
			$this->set('ExpenseId',$Id);
			if($data = $this->ExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file','Id','round_off'),'conditions'=>array('Id'=>$Id))))
			{
                           
                            $this->set('result',$this->ExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.ExpenseEntry='$Id'"));
                            
                              $VendorId = $data['ExpenseEntryMaster']['Vendor']; 
                               $HeadId = $data['ExpenseEntryMaster']['HeadId'];
                                $vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId)));
                                $CompId = $data['ExpenseEntryMaster']['CompId'];
                                
                                if($CompId==1)
                                {
                                    $Comp='(Mas)';
                                }
                                else {
                                    $Comp='(IDC)';
                                }
                                
                                foreach($vend as $k=>$v)
                                {
                                    $Vendor[$k.'-'.$data['ExpenseEntryMaster']['CompId']] = $v.$Comp;
                                    
                                }
                                $this->set('Vendor' ,$Vendor);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['ExpenseEntryMaster']);
				$this->set('ExpenseEntryMaster',$data);
                             
                                if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
                                WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
                                {
                                    
                                    foreach($data as $v)
                                    {
                                        $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                                    }
                                    $this->set('branchArr',$branchArr);
                                }
                                else
                                {
                                    $this->set('branchArr',"");
                                }
			}
                }
    
    public function view_imprest()
        {
            $this->layout="home";
            $this->set('data',$this->ExpenseEntryApproveMaster->query("SELECT eemApp.Id,eemApp.GrnNo,tu.username,cm.company_name,eemApp.Amount,eemApp.FinanceYear,eemApp.FinanceMonth FROM `expense_entry_master` eemApp
INNER JOIN tbl_user tu ON eemApp.userid = tu.Id
INNER JOIN company_master cm ON eemApp.CompId = cm.Id Where eemApp.ExpenseEntryType='Imprest' order by SUBSTRING_INDEX(eemApp.GrnNo,'/',-1)")); 
        }            
      public function view_imprest_tmp()
        {
                       
			$branchArr = "";
			$this->layout='home';
			$userid = $this->Session->read('userid');
                        $ExpenseId = $this->params->query['Id'];
                        $this->set('ExpenseId',$ExpenseId);
                        
			$this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
                        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));
                        
                        
			$this->set('result',$this->ExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
                        WHERE teep.ExpenseEntry='$ExpenseId'"));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
                        
			if($data = $this->ExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
                            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId','grn_file'),'conditions'=>array('Id'=>$ExpenseId))))
			{
                               
                               $HeadId = $data['ExpenseEntryMaster']['HeadId'];
                                
                                $CompId = $data['ExpenseEntryMaster']['CompId'];
                                
                                if($CompId==1)
                                {
                                    $Comp='(Mas)';
                                }
                                else {
                                    $Comp='(IDC)';
                                }
                                
                               // $this->set('HeadId',$HeadId);
                                $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""))));
                                
                                $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                                'conditions'=>array('SubHeadingId'=>$data['ExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));
                                
				$data = array_values($data['ExpenseEntryMaster']);
				$this->set('ExpenseEntryMaster',$data);
                             
                                
			}
			else
			{
				$data = array_fill(0,10,'');
				$data = array_values($data);
                                $this->set('head',"");
                                $this->set('SubHeading',"");
				$this->set('ExpenseEntryMaster',$data);
                                
			}
                }
                
      public function payment_processing()
        {
            $this->layout="home";
            
            $this->set('data',$this->ExpenseEntryMaster->query("SELECT eem.Id,eem.grn_file,eem.GrnNo,bm.branch_name,head.HeadingDesc,subhead.SubHeadingDesc,SUM(eep.total) Total,due_date FROM expense_entry_master eem INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN branch_master bm ON bm.Id = eep.branchId 
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON head.HeadingId = eem.HeadId
 INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON subhead.SubHeadingId = eem.SubHeadId
 WHERE eem.ExpenseEntryType='vendor' and eem.payment_processing='0' AND due_date IS NOT NULL AND due_date !='' AND due_date!='0000-00-00'  and date(due_date)>curdate()
GROUP BY eem.GrnNo,bm.Id")); 
            
            $this->set('data1',$this->ExpenseEntryApproveMaster->query("SELECT eem.Id,eem.grn_file,bm.branch_name,head.HeadingDesc,subhead.SubHeadingDesc,SUM(eep.total) Total,due_date FROM expense_entry_master_approve eem INNER JOIN expense_entry_particular_approve eep ON eem.Id = eep.ExpenseEntry
INNER JOIN branch_master bm ON bm.Id = eep.branchId 
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON head.HeadingId = eem.HeadId
 INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON subhead.SubHeadingId = eem.SubHeadId
 WHERE eem.ExpenseEntryType='vendor' and eem.payment_processing='1' AND due_date IS NOT NULL AND due_date !='' AND due_date!='0000-00-00'  and date(due_date)>curdate()
GROUP BY eem.Id,bm.Id")); 
            
            $this->set('bank_master',$this->Bank->find('list',array('fields'=>array('bank_name','bank_name'),'order'=>array('bank_name'=>'asc'))));
        }
        
    public function save_payment_processing()
    {
        
           
            $data['GrnNo'] = addslashes($this->request->data['GrnNo']);
            $data['GrnId'] = $this->request->data['GrnId'];
            $data['PaymentMode'] = $this->request->data['PaymentMode'];
            $old_date = explode('-',$this->request->data['PaymentDate']);
            $new_date[0] = $old_date[2];
            $new_date[1] = $old_date[1];
            $new_date[2] = $old_date[0];
            $data['PaymentDate'] = implode('-',$new_date);
            $data['BankName'] = $this->request->data['BankName'];
            $data['TransactionId'] = $this->request->data['TransactionId'];
            $data['CreateDate'] = date("Y-m-d H:i:s");
            $data['CreateBy'] = $this->Session->read("userid");
            $dataX = $this->ExpenseEntryMaster->find('first',array('conditions'=>array('GrnNo'=>$data['GrnNo'])));
            $dataY = $dataX['ExpenseEntryMaster'];
            $dataZ = $this->ExpenseEntryParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$data['GrnId'])));
            $dataA = $dataX['ExpenseEntryParticular'];
//            if(empty($data['GrnNo']))
//            {
//                $dataX = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('GrnNo'=>$data['GrnNo'])));
//                $dataY = $dataX['ExpenseEntryApproveMaster'];
//            }
//            else
//            {
                
           // }
            $data['BranchId'] = $dataA['BranchId'];
            $data['Head'] = $dataY['HeadId'];
            $data['SubHead'] = $dataY['SubHeadId'];
            $data['DueAmount'] = $dataY['Amount'];
            $data['DueDate'] = $dataY['due_date'];
            
            if($this->GrnPaymentProcessing->save(array('GrnPaymentProcessing'=>$data)))
            {
                $this->ExpenseEntryMaster->updateAll(array('payment_processing'=>0),array('GrnNo'=>$data['GrnNo']));    
                echo "1";
            }
            else
            {
                echo "0";
            }
            exit;
    }
        
      public function save_payment_processing1()
        {
            
            $GrnA = $this->request->data['Gms']['GrnA'];
            $GrnB = $this->request->data['Gms']['GrnB'];
            $GrnA_arr = explode(',',$GrnA);
            $GrnB_arr = explode(',',$GrnB);
           
           //print_r($this->request->data); exit;
           
            foreach($GrnA_arr as $grn)
            {
                $data = array();
                $flag = true;
                $data['GrnId'] = $grn;
                if(empty($this->request->data['PaymentModeA'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['payment_dateA'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['bank_nameA'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['transidA'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['grn_noA'.$grn]))
                {
                    $flag = false;
                }
                
                
                if($flag)
                {
                    $data['GrnNo'] = addslashes($this->request->data['grn_noA'.$grn]);
                    $data['GrnId'] = $grn;
                    $data['PaymentMode'] = $this->request->data['PaymentModeA'.$grn];
                    $old_date = explode('/',$this->request->data['payment_dateA'.$grn]);
                    $new_date[2] = $old_date[1];
                    $new_date[1] = $old_date[0];
                    $new_date[0] = $old_date[2];
                    $data['PaymentDate'] = implode('-',$new_date);
                    $data['BankName'] = $this->request->data['bank_nameA'.$grn];
                    $data['TransactionId'] = $this->request->data['transidA'.$grn];
                    $data['CreateDate'] = date("Y-m-d H:i:s");
                    $data['CreateBy'] = $this->Session->read("userid");
                    $dataX = $this->ExpenseEntryMaster->find('first',array('conditions'=>array('GrnNo'=>$data['GrnNo'])));
                    $dataY = $dataX['ExpenseEntryMaster'];
                    $dataZ = $this->ExpenseEntryParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$data['GrnId'])));
                    $dataA = $dataZ['ExpenseEntryParticular']['BranchId'];
       
                    $data['BranchId'] = $dataA['BranchId'];
                    $data['Head'] = $dataY['HeadId'];
                    $data['SubHead'] = $dataY['SubHeadId'];
                    $data['DueAmount'] = $dataY['Amount'];
                    $data['DueDate'] = $dataY['due_date'];
                    
                    if($this->GrnPaymentProcessing->save(array('GrnPaymentProcessing'=>$data)))
                    {
                        $this->ExpenseEntryMaster->updateAll(array('payment_processing'=>0),array('GrnNo'=>$data['GrnNo']));    
                    }
                }
            }
           
           
            foreach($GrnB_arr as $grn)
            {
                $data = array();
                $flag = true;
                $data['GrnId'] = $grn;
                if(empty($this->request->data['PaymentModeB'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['payment_dateB'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['bank_nameB'.$grn]))
                {
                    $flag = false;
                }
                else if(empty($this->request->data['transidB'.$grn]))
                {
                    $flag = false;
                }
                
                if($flag)
                {   
                    $data['PaymentMode'] = $this->request->data['PaymentModeB'.$grn];
                    $old_date = explode('/',$this->request->data['payment_dateB'.$grn]);
                    $new_date[2] = $old_date[1];
                    $new_date[1] = $old_date[0];
                    $new_date[0] = $old_date[2];
                    $data['PaymentDate'] = implode('-',$new_date);
                    $data['BankName'] = $this->request->data['bank_nameB'.$grn];
                    $data['TransactionId'] = $this->request->data['transidB'.$grn];
                    $data['CreateDate'] = date("Y-m-d H:i:s");
                    $data['CreateBy'] = $this->Session->read("userid");
                    $dataX = $this->ExpenseEntryApproveMaster->find('first',array('conditions'=>array('Id'=>$data['GrnId'])));
                    $dataY = $dataX['ExpenseEntryApproveMaster'];
                    $dataZ = $this->ExpenseEntryApproveParticular->find('first',array('conditions'=>array('ExpenseEntry'=>$data['GrnId'])));
                    $dataA = $dataZ['ExpenseEntryApproveParticular']['BranchId'];
                    $data['BranchId'] = $dataA['BranchId'];
                    $data['Head'] = $dataY['HeadId'];
                    $data['SubHead'] = $dataY['SubHeadId'];
                    $data['DueAmount'] = $dataY['Amount'];
                    $data['DueDate'] = $dataY['due_date'];
                    
                    if($this->GrnPaymentProcessing->save(array('GrnPaymentProcessing'=>$data)))
                    {
                        $this->ExpenseEntryApproveMaster->updateAll(array('payment_processing'=>0),array('Id'=>$data['GrnId']));    
                    }
                }
            }
            
            $this->redirect(array('controller'=>"Gms","action"=>'payment_processing'));
                
        }
        
      
}

?>