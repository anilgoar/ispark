<?php
class PnlReportsController extends AppController 
{
    public $uses=array('Collection','Addbranch','BillMaster','TMPCollection','Addcompany','TMPCollectionParticulars','OtherTMPDeduction','OtherDeduction',
        'CollectionParticulars','InitialInvoice','Bank','User','BillMaster','TmpExpenseMaster','Tbl_bgt_expenseheadingmaster','VendorMaster','VendorRelation',
        'CostCenterMaster','ExpenseMaster','ExpenseEntryMaster','TmpExpenseEntryMaster','TmpExpenseEntryParticular','Tbl_bgt_expensesubheadingmaster',
        'ExpenseEntryParticular','GrnBranchAccess','ExpenseEntryApproveParticular','ExpenseEntryApproveMaster','GrnPaymentProcessing','StateList','ExpenseEntryDelete');
    
    public $components = array('RequestHandler');
    public $helpers = array('Js');

    public function beforeFilter()
    {
        parent::beforeFilter();

        if(!$this->Session->check("userid"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('grn_report','export_grn_report');
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
WHERE bm.Id = '$BranchId' and cm.active='1'"))
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
        
        
    	public function index() 
	{
            
        }
	
    public function grn_report()
    {
    $this->layout = "home";
    $all = array();
    
    $role = $this->Session->read('role');
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $branchMaster = $all+$branchMaster2;
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
    $this->set('branch_master',$branchMaster);
    $this->set('head',array_merge(array('All'=>'All'),$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))));
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        
        if($Expense['branch_name']!='All')
        {
            $qry .= " and cm.branch='".$Expense['branch_name']."'";
        }
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
        
        if($Expense['head']!='All')
        {
            $qry .= " and em.HeadId='".$Expense['head']."'";
        }
        
        if($Expense['subhead']!='All' && !empty($Expense['subhead']))
        {
            $qry .= " and em.SubHeadId='".$Expense['subhead']."'";
        }
        
        if($Expense['expenseEntryType']!='All')
        {
            $qry .= " and em.ExpenseEntryType='".$Expense['expenseEntryType']."'";
        }
        
        if($Expense['GrnNo'])
        {
            $qry .= " and em.GrnNo like '%".$Expense['GrnNo']."'";
        }
        
        //print_r($qry); exit;
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT tu.emp_name,em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.Description,em.ExpenseDate ,em.EntryStatus
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
INNER JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN tbl_vendormaster vm ON em.Vendor=vm.Id $qry order by em.Id"));
       
    }
    
     
}

public function export_grn_report()
{
    $this->layout='ajax';
    if($this->request->is('POST'))
    {$result = $this->request->data;}
    else
    {
        $result = $this->params->query;
    }
      
    $this->set('type',$result['type']);
      
    $BranchName = $result['BranchName'];
    $year =$result['year'];
    $month =$result['month'];
    $head = $result['head'];
    
    $subhead =$result['subhead'];
    $ExpenseExpenseEntryType =$result['ExpenseExpenseEntryType'];
    $ExpenseGrnNo =$result['ExpenseGrnNo'];
    
    //print_r($this->request->data); exit;
    $qry = "Where 1=1";
    $Expense = $this->request->data['Expense'];

    if($BranchName!='All')
    {
        $qry .= " and cm.branch='".$BranchName."'";
    }

    if($year!='All')
    {
        $qry .= " and em.FinanceYear='".$year."'";
    }

    if($month!='All')
    {
        $qry .= " and em.FinanceMonth='".$month."'";
    }

    if($head!='All')
    {
        $qry .= " and em.HeadId='".$head."'";
    }

    if($subhead!='All' && !empty($subhead))
    {
        $qry .= " and em.SubHeadId='".$subhead."'";
    }

    if($ExpenseExpenseEntryType!='All')
    {
        $qry .= " and em.ExpenseEntryType='".$ExpenseExpenseEntryType."'";
    }

    if($ExpenseGrnNo)
    {
        $qry .= " and em.GrnNo like '%".$ExpenseGrnNo."'";
    }
        
        //print_r($qry);exit;
        
    $exp_data = $this->ExpenseMaster->query("SELECT tu.emp_name,date(em.ApprovalDate)ApprovalDate,date(em.bill_date) bill_date,
           em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,
           SubHeadingDesc,eep.Amount,IF(em.Vendor_State_Code=em.Billing_State_Code,'state','central')GSTType,em.gst_enable,eep.Tax,eep.Rate,
em.Description,em.ExpenseDate ,em.EntryStatus,eep.Total,em.bill_date
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
INNER JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN tbl_vendormaster vm ON em.Vendor=vm.Id $qry order by em.Id");
    //print_r($exp_data); exit;
    $this->set('ExpenseReport',$exp_data);
       
    
    
     
}
                
    public function payment_processing()
    {
          
        ini_set('memory_limit', '1024M'); 
        $this->layout="home";
        $page = $this->params->query['page'];

        if(empty($page))
        {
            $page1 = $page = 1;
            $limit = 50;
        }
        else
        {
            $page1 = ($page-1)*50;
            $limit = 50;
        }
            
            $this->set('data',$this->ExpenseEntryMaster->query("SELECT eem.Id,eem.grn_file,eem.GrnNo,bm.branch_name,head.HeadingDesc,subhead.SubHeadingDesc,SUM(eep.total) Total,due_date FROM expense_entry_master eem INNER JOIN (select branchId,ExpenseEntry,sum(total)total from expense_entry_particular group by ExpenseEntry) eep ON eem.Id = eep.ExpenseEntry
INNER JOIN branch_master bm ON bm.Id = eep.branchId 
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON head.HeadingId = eem.HeadId
 INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON subhead.SubHeadingId = eem.SubHeadId
 WHERE eem.ExpenseEntryType='vendor' and eem.payment_processing='1' AND due_date IS NOT NULL AND due_date !='' AND due_date!='0000-00-00'  and date(eem.createdate)>'2018-05-31'
GROUP BY eem.GrnNo,bm.Id order by head.HeadingDesc,subhead.SubHeadingDesc limit $page1,$limit")); 
            
            $this->set('data1',$this->ExpenseEntryApproveMaster->query("SELECT eem.Id,eem.grn_file,bm.branch_name,head.HeadingDesc,subhead.SubHeadingDesc,SUM(eep.total) Total,due_date FROM expense_entry_master_approve eem INNER JOIN (select branchId,ExpenseEntry,sum(total)total from expense_entry_particular_approve group by ExpenseEntry) eep ON eem.Id = eep.ExpenseEntry
INNER JOIN branch_master bm ON bm.Id = eep.branchId 
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON head.HeadingId = eem.HeadId
 INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON subhead.SubHeadingId = eem.SubHeadId
 WHERE eem.ExpenseEntryType='vendor' and eem.payment_processing='1' AND due_date IS NOT NULL AND due_date !='' AND due_date!='0000-00-00'  
GROUP BY eem.Id,bm.Id order by head.HeadingDesc,subhead.SubHeadingDesc limit $page1,$limit")); 
            
            $this->set('bank_master',$this->Bank->find('list',array('fields'=>array('bank_name','bank_name'),'order'=>array('bank_name'=>'asc'))));
            $this->set('page',$page);
        }
        
    public function save_payment_processing()
    {
            $data['GrnNo'] = addslashes($this->request->data['GrnNo']);
            $data['GrnId'] = $this->request->data['GrnId'];
            $data['PaymentMode'] = $this->request->data['PaymentMode'];
            $old_date = explode('/',$this->request->data['PaymentDate']);
            
            $new_date[0] = $old_date[2];
            $new_date[1] = $old_date[0];
            $new_date[2] = $old_date[1];
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
            ini_set('memory_limit', '1024M');
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
                    $new_date[2] = $old_date[2];
                    $new_date[1] = $old_date[0];
                    $new_date[0] = $old_date[1];
                    
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
                    $$new_date[2] = $old_date[2];
                    $new_date[1] = $old_date[0];
                    $new_date[0] = $old_date[1];
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
        
      public function delete_grn_request()
      {
        $roles=explode(',',$this->Session->read("page_access"));
        $userid = $this->Session->read('userid');
        if($this->Session->read("userid")=='' || !in_array('188',$roles))
        {
              return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
          
        $this->layout="home";
         if($this->request->is('POST'))
         {
             
             $GrnNo = $this->request->data['Gms']['grn_no'];
             $Remarks = $this->request->data['Gms']['remarks'];
             
             $data = $this->ExpenseEntryMaster->find('first',array('conditions'=>"GrnNo='$GrnNo'"));
             if($this->ExpenseEntryDelete->find('first',array('conditions'=>"GrnNo='$GrnNo'")))
             {
                $this->Session->setFlash("Record Allready Exist"); 
             }
             else if($this->ExpenseEntryDelete->save(array('ExpenseEntryDelete'=>
                 array('GrnNo'=>$GrnNo,
                     'ExpenseEntryId'=>$data['ExpenseEntryMaster']['Id'],
                     'Remarks'=>$Remarks,
                     'delete_request_by'=>$userid,
                     'delete_request_date'=>date('Y-m-d H:i:s'),
                     'delete_request'=>'1'
                         ))))
             {
                 $this->Session->setFlash("Request For Deletion of Grn No has been saved for Approval");
                 return $this->redirect(array('action'=>'delete_grn_request'));
             }
             
         }
      }
      
    public function get_grn()
  {
      $roles=explode(',',$this->Session->read("page_access"));
    if($this->Session->read("userid")=='' || !in_array('188',$roles))
    {
          return $this->redirect(array('controller'=>'users','action' => 'login'));
    }
    
    
    $this->layout="home";
    $GrnNo = $this->request->data['GrnNo'];
    
    $data = $this->ExpenseEntryMaster->query("SELECT * FROM  expense_entry_master eem 
INNER JOIN tbl_user tu ON tu.id=eem.userid 
LEFT JOIN tbl_bgt_expenseheadingmaster head ON eem.HeadId = head.HeadingId 
LEFT JOIN tbl_bgt_expensesubheadingmaster subhead ON eem.SubHeadId = subhead.SubHeadingId WHERE GrnNo='$GrnNo'");
    if(empty($data))
    {
        echo "No Records Found"; exit;
    }
    else 
    {
        echo '<table border="2">';
        echo "<tr>";
            echo '<th>Grn No.<th>';
            echo '<th>GRN Type<th>';
            echo '<th>Finance Year<th>';
            echo '<th>Finance Month<th>';
            echo '<th>Head<th>';
            echo '<th>Sub Head<th>';
            echo '<th>Amount<th>';
            echo '<th>Description<th>';
        echo "</tr>";

        echo "<tr>";
            echo '<td>'.$data['0']['eem']['GrnNo'].'<td>';
            echo '<td>'.$data['0']['eem']['ExpenseEntryType'].'<td>';
            echo '<td>'.$data['0']['eem']['FinanceYear'].'<td>';
            echo '<td>'.$data['0']['eem']['FinanceMonth'].'<td>';
            echo '<td>'.$data['0']['head']['HeadingDesc'].'<td>';
            echo '<td>'.$data['0']['subhead']['SubHeadingDesc'].'<td>';
            echo '<td>'.$data['0']['eem']['Amount'].'<td>';
            echo '<td>'.$data['0']['eem']['Description'].'<td>';
        echo "</tr>";

        echo "</table>";
    }
    
    exit;
    
  }
  
  public function delete_grn_request_approve()
      {
        $roles=explode(',',$this->Session->read("page_access"));
        $userid = $this->Session->read('userid');
        if($this->Session->read("userid")=='' || !in_array('188',$roles))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
          
        $this->layout="home";
        
        
        if($this->request->is('POST'))
        {
             
            $checkAll = $this->request->data['checkAll'];
             
            foreach($checkAll as $chk)
            {
                $delete_arr = $this->ExpenseEntryDelete->query("SELECT * FROM `expense_delete_request` edl WHERE Id='$chk' and edl.delete_request='1'");
                foreach($delete_arr as $del_arr)
                {
                    $grn_Id = $del_arr['edl']['ExpenseEntryId'];
                    $this->ExpenseEntryDelete->query("INSERT INTO `expense_entry_master_delete`(
Id,GrnNo,ExpenseEntryType,BranchId,Vendor,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Description,Remarks,ExpenseDate,
EntryStatus,createdate,Parent,userid,grn_status,book_by,book_date,dispatch,dispatchId,bill_no,bill_date,CGST,SGST,IGST,
DownloadStatus,DownloadBy,DownloadDate,CompId,multi_month_check,multi_month,RejectRemarks,Reject,RejectDate,RejectBy,ApprovalDate,
ApprovedBy,grn_file,round_off,due_date,payment_processing,UniqueId,uid_auto,UniqueHead,PendingGrn,gst_enable,Vendor_State_Code,
Billing_State_Code)

SELECT 
Id,GrnNo,ExpenseEntryType,BranchId,Vendor,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Description,Remarks,ExpenseDate,
EntryStatus,createdate,Parent,userid,grn_status,book_by,book_date,dispatch,dispatchId,bill_no,bill_date,CGST,SGST,IGST,
DownloadStatus,DownloadBy,DownloadDate,CompId,multi_month_check,multi_month,RejectRemarks,Reject,RejectDate,RejectBy,ApprovalDate,
ApprovedBy,grn_file,round_off,due_date,payment_processing,UniqueId,uid_auto,UniqueHead,PendingGrn,gst_enable,Vendor_State_Code,
Billing_State_Code FROM `expense_entry_master` WHERE Id='$grn_Id'");
                    
                    $this->ExpenseEntryDelete->query("INSERT INTO `expense_entry_particular_delete`(
Id,BranchId,ExpenseEntryType,Particular,ExpenseEntry,CostCenterId,Amount,Rate,Tax,Total,createdate,userid
)
SELECT 
Id,BranchId,ExpenseEntryType,Particular,ExpenseEntry,CostCenterId,Amount,Rate,Tax,Total,createdate,userid 
FROM `expense_entry_particular` WHERE ExpenseEntry='$grn_Id'");
                    
                    if($this->ExpenseEntryMaster->deleteAll(array('Id'=>$grn_Id)))
                    {
                        $this->ExpenseEntryDelete->updateAll(array('delete_by'=>$userid,'delete_request'=>"'2'",'delete_date'=>"'".date("Y-m-d H:i:s")."'"),array('Id'=>$chk));
                    }
                    
                }
            }
            
            $this->Session->setFlash("Grn Has been deleted successfully");
        }
         
         $select = "SELECT * FROM `expense_delete_request` edl "
                 . "LEFT JOIN expense_entry_master eem ON edl.GrnNo = eem.GrnNo "
                 . "inner join tbl_user tu on tu.id=edl.delete_request_by "
                 . "left join tbl_bgt_expenseheadingmaster head on eem.HeadId = head.HeadingId "
                 . "left join tbl_bgt_expensesubheadingmaster subhead on eem.SubHeadId = subhead.SubHeadingId WHERE edl.delete_request='1'"; 
        
         $delete_arr = $this->ExpenseEntryDelete->query($select);
         
        $this->set('delete_arr',$delete_arr);
         
      }
  
}

?>