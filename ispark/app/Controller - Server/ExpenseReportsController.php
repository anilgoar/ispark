<?php
App::uses('AppController', 'Controller');
class ExpenseReportsController  extends AppController 
{
    public $components = array('Session');
    public $uses = array('Addbranch','Addcompany','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','ExpenseEntryMaster','ExpenseParticular','BillMaster','ImprestManager','GrnBranchAccess','VendorMaster');
	
public function beforeFilter() 
{
parent::beforeFilter();    

if(!$this->Session->check("username"))
{
        return $this->redirect(array('controller'=>'users','action' => 'login'));
}

$this->Auth->allow('index','imprest_report','pnl_report','imprest_detail','get_imprest_manager','export_budget','Export_imprest_report',
        'export_imprest_detail','export_pnl_report','imprest_report2','export_imprest_report2','imprest_report_breakup',
        'Export_imprest_report_breakup','view_vendor','export_vendor','view_tds','export_tds','view_section_tds','export_section_tds','grn_report',
        'export_grn_report','export_grn_reject_report','pnl_revenue_report','export_pnl_revenue_report','get_budget');
$this->Auth->allow('reject_report');
}


public function get_budget()
        {
            $Branch = $this->request->data['Branch'];
            $FinanceYear = $this->request->data['FinanceYear'];
            $FinanceMonth = $this->request->data['FinanceMonth'];
            $HeadingDesc = $this->request->data['Head'];
            $SubHeadDesc = $this->request->data['SubHead'];
            $Expense = $this->ExpenseEntryMaster->query("SELECT SUM(eep.Amount)Total FROM expense_entry_master eem
INNER JOIN `expense_entry_particular` eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `cost_master` cm ON eep.CostCenterId = cm.Id
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name 
WHERE eem.FinanceYear='$FinanceYear' AND eem.FinanceMonth='$FinanceMonth'
 AND bm.branch_name='$Branch' AND head.HeadingDesc='$HeadingDesc' AND
  subhead.SubHeadingDesc='$SubHeadDesc'");
            if(empty($Expense['0']['0']['Total']))
            {
                echo 0;
            }
            else
            {
                echo round($Expense['0']['0']['Total']);
            }
            exit;
        }

public function get_imprest_manager()
{
    $this->layout="ajax";
        $ImperstList=array();
        $all = array();
        $userid = $this->Session->read('userid');
        if($this->request->is("POST"))
        {
            $BranchId=$this->request->data['BranchId'];
            $role = $this->Session->read('role');
            
            
            if($BranchId=='All' && $role=='admin')
            {
                $condition=array('Active'=>'1');
                $all = array('All'=>'All');
            }
            else if($BranchId=='All')
            {
                $condition=array('Active'=>'1','UserId'=>$userid);
            }
            else
            {
                $condition=array('Active'=>'1','BranchId'=>$BranchId);
            }
            $ImperstList = $this->ImprestManager->find('list',array('fields'=>array('Id','UserName'),'conditions'=>$condition));
            $ImperstList = $all+$ImperstList;
            echo json_encode($ImperstList);
        }
        exit;
}

public function index()
{
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     if($role=='admin')
        {
            $condition=array('active'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"),'active'=>1);
        }
     
    
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $branchMaster = $all + $branchMaster2;
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    $this->set('branch_master',$branchMaster);
    
    if($this->request->is('POST'))
    {
        if(empty($this->request->data['submit']))
        {
            $qry = "Where 1=1";
            $Expense = $this->request->data['Expense'];

            if($Expense['branch_name']!='All')
            {
                $qry .= " and Branch='".$Expense['branch_name']."'";
            }

            if($Expense['FinanceYear']!='All')
            {
                $qry .= " and FinanceYear='".$Expense['FinanceYear']."'";
            }

            if($Expense['FinanceMonth']!='All')
            {
                $qry .= " and FinanceMonth='".$Expense['FinanceMonth']."'";
            }

          $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(EntryStatus=0,'Closed','Approved')
`bus_status`,PaymentFile,'1' Action FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status`,PaymentFile,'1' Action FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1 order by Branch,HeadingDesc,SubHeadingDesc")); 
        }
        else if($this->request->data['submit']=='CloseBusinessCase')
        {
            
            $qry = "Where 1=1";
            $Expense = $this->request->data['Expense'];

            if($Expense['BranchQry']!='All')
            {
                $qry .= " and Branch='".$Expense['BranchQry']."'";
            }

            if($Expense['YearQry']!='All')
            {
                $qry .= " and FinanceYear='".$Expense['YearQry']."'";
            }

            if($Expense['MonthQry']!='All')
            {
                $qry .= " and FinanceMonth='".$Expense['MonthQry']."'";
            }
            if(!empty($this->request->data['Expense']['ExpenseId']))
            {
                $ExpenseId = $this->request->data['Expense']['ExpenseId'];
                $ExpenseRemarks = addslashes($this->request->data['Expense']['remarks']);
                if($this->ExpenseMaster->updateAll(array('EntryStatus'=>'0','BusinessCaseCloseRemarks'=>"'$ExpenseRemarks'"),array('Id'=>$ExpenseId)))
                {
                    echo "<script>alert('Business Case Close Successfully.');</script>";
                }
                $this->set('FinBranch',$Expense['BranchQry']);
                $this->set('FinYear',$Expense['YearQry']);
                $this->set('FinMonth',$Expense['MonthQry']);
                
               $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(EntryStatus=0,'Closed','Approved')
`bus_status`,PaymentFile,'1' Action FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status`,PaymentFile,'1' Action FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1 order by Branch,HeadingDesc,SubHeadingDesc"));  
            }
        }
    }
}

public function export_budget()
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
		
    
    
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        
        
        if($BranchName!='All')
        {
            $qry .= " and Branch='".$BranchName."'";
        }
        
        if($year!='All')
        {
            $qry .= " and FinanceYear='".$year."'";
        }
        
        if($month!='All')
        {
            $qry .= " and FinanceMonth='".$month."'";
        }
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(EntryStatus=0,'Closed','Approved')
`bus_status`,PaymentFile,'1' Action FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status`,PaymentFile,'1' Action FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1 order by Branch,HeadingDesc,SubHeadingDesc")); 
       
    
    
    
}

public function imprest_report()
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
    
        
    $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "";
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
        
//       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(STR_TO_DATE(ExpenseDate,'%d-%m-%Y'),'%d-%b-%Y') Dates,head.HeadingDesc,
//subhead.SubHeadingDesc,IF(bm.state=vm.state,'state','central')GSTType,tscgd.GSTEnable,
//vm.Vendor,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,
//cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,bm.state FROM `expense_entry_master` 
//em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
//INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
//INNER JOIN branch_master bm ON cm.branch = bm.branch_name
//INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
//INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
//INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
//INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
//WHERE  em.Id>83619 $qry
//GROUP BY cm.branch,em.GrnNo,eep.Particular,em.Id   ORDER BY STR_TO_DATE(CONCAT('1-',FinanceMonth,DATE_FORMAT(CURDATE(),'-%Y')),'%d-%b-%Y'),CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)"));
//       
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
      
    $comp_Name = $result['comp_Name'];
		$year =$result['year'];
                $month =$result['month'];
   
    
        //print_r($this->request->data); exit;
        //$qry = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        
        if($comp_Name!='All')
        {
            $qry .= " and em.CompId='".$comp_Name."'";
        }
        
        if($year!='All')
        {
            //$qry .= " and em.FinanceYear='".$year."'";
        }
        
        if($month!='All')
        {
            $FinanceYear = explode('-',$year);
            $monthArr = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            $monthId = $monthArr[$month];
            
            if($monthId>3)
            {
                $NewMonth = $month.'-'.($FinanceYear[1]-1);
            }
            else
            {
                $NewMonth = $month.'-'.($FinanceYear[1]);
            }
            
            $qry .= " and DATE_FORMAT(em.approvalDate,'%b-%y')='".$NewMonth."'";
        }
        
        
        
        //print_r($qry);exit;


        
        
       $ExpenseReport= $this->ExpenseMaster->query("SELECT em.Id,SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(LAST_DAY(em.approvalDate),'%d-%b-%y') Dates,head.HeadingDesc,
subhead.SubHeadingDesc,IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,tscgd.GSTEnable,em.bill_no,vm.TDSEnabled,vm.TDS,vm.TDSSection,vm.TDSChange,vm.TDSNew,
vm.TallyHead,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,
vm.TDSTallyHead,subhead.SubHeadTDSEnabled,subhead.SubHeadTds,td.description,td2.description,td.TDS,
cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,IF(vm.as_bill_to=1,vm.state,bm.state)state,bm.tally_code,bm.tally_branch,em.GrnNo FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
LEFT JOIN tds_master td ON subhead.SubHeadTdsSection = td.Id
LEFT JOIN tds_master td2 ON vm.TDSSection = td2.Id
WHERE  1=1 $qry
GROUP BY cm.branch,em.GrnNo,em.Id   ORDER BY STR_TO_DATE(em.approvalDate,'%d-%m-%Y'),CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)");
       $this->set('ExpenseReport',$ExpenseReport);
    
       $userid = $this->Session->read('userid');
       $date = date('Y-m-d H:i:s'); 
       
//    foreach($ExpenseReport as $post)
//    {
//      $this->ExpenseEntryMaster->updateAll(array('DownloadStatus'=>'0','DownloadBy'=>"'$userid'",'DownloadDate'=>"'$date'"),array('Id'=>$post['em']['Id']));
//    }
     
}




public function imprest_report_breakup()
{
    $this->layout = "home";
    $all = array();
    $userid = $this->Session->read("userid");
    $role = $this->Session->read('role');
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $BranchArr = $this->GrnBranchAccess->find('list',array('fields'=>array('Id','BranchId'),'conditions'=>array('userid'=>$userid)));
            $condition=array('Id'=>$BranchArr);
        }
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $branchMaster = $all+$branchMaster2;
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    $this->set('branch_master',$branchMaster);
    $this->set('head',array('All'=>'All')+$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>"EntryBy=''",'order'=>array('HeadingDesc'=>'asc'))));
    
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
            $qry .= " and eem.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and eem.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
        
        if($Expense['head']!='All')
        {
            $qry .= " and eem.HeadId='".$Expense['head']."'";
        }
        
        if($Expense['subhead']!='All' && !empty($Expense['subhead']))
        {
            $qry .= " and eem.SubHeadId='".$Expense['subhead']."'";
        }
        
        if($Expense['expenseEntryType']!='All')
        {
            $qry .= " and eem.ExpenseEntryType='".$Expense['expenseEntryType']."'";
        }
        
        if($Expense['GrnNo'])
        {
            $qry .= " and eem.GrnNo like '%".$Expense['GrnNo']."'";
        }
        
        //print_r($qry); exit;
        $this->set('data',$this->ExpenseEntryMaster->query("SELECT GrnNo,date(eem.ApprovalDate) ApprovalDate,cm.branch,cm.cost_center,eem.ExpenseEntryType,eem.FinanceYear,eem.FinanceMonth,head.HeadingDesc,subhead.SubHeadingDesc,
            IF(eep.Total IS NULL,eep.Amount,eep.Total) Amount,ExpenseDate
            FROM `expense_entry_particular` eep INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
            INNER JOIN cost_master cm ON eep.CostCenterId= cm.Id 
            INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.headid = head.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.subheadid = subhead.SubHeadingId
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name $qry group by eem.Id"));
        
        
        
    }
    
  
}

public function Export_imprest_report()
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
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT tu.emp_name,date(em.ApprovalDate)ApprovalDate,em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.Description,em.ExpenseDate ,em.EntryStatus
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
INNER JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN tbl_vendormaster vm ON em.Vendor=vm.Id $qry order by em.Id"));
       
    
    
     
}

public function Export_imprest_report_breakup()
{
    $this->layout='ajax';
      if($this->request->is('POST'))
      {$Expense = $this->request->data;}
      else
      {
          $Expense = $this->params->query;
      }
      
      $this->set('type',$Expense['type']);
      
    $BranchName = $Expense['BranchName'];
		$year =$Expense['year'];
                $month =$Expense['month'];
   $head = $Expense['head'];
		$subhead =$Expense['subhead'];
                $ExpenseExpenseEntryType =$Expense['ExpenseExpenseEntryType'];
    $ExpenseGrnNo =$result['ExpenseGrnNo'];
    
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        
        
        if($Expense['BranchName']!='All')
        {
            $qry .= " and cm.branch='".$Expense['BranchName']."'";
        }
        
        if($Expense['year']!='All')
        {
            $qry .= " and eem.FinanceYear='".$Expense['year']."'";
        }
        
        if($Expense['month']!='All')
        {
            $qry .= " and eem.FinanceMonth='".$Expense['month']."'";
        }
        
        if($Expense['head']!='All')
        {
            $qry .= " and eem.HeadId='".$Expense['head']."'";
        }
        
        if($Expense['subhead']!='All' && !empty($Expense['subhead']))
        {
            $qry .= " and eem.SubHeadId='".$Expense['subhead']."'";
        }
        
        if($ExpenseExpenseEntryType!='All')
        {
            $qry .= " and eem.ExpenseEntryType='$ExpenseExpenseEntryType'";
        }
        
        if($Expense['GrnNo'])
        {
            $qry .= " and eem.GrnNo like '%".$Expense['GrnNo']."'";
        }
        
        
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT GrnNo,date(eem.ApprovalDate)ApprovalDate,cm.branch,bm.branch_name,cm.cost_center,eem.ExpenseEntryType,eem.FinanceYear,eem.FinanceMonth,head.HeadingDesc,subhead.SubHeadingDesc,
            sum(eep.Amount) Amount,ExpenseDate,vm.vendor,tu.emp_name,eem.description,eem.EntryStatus,eem.grn_file
            FROM `expense_entry_particular` eep INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
            INNER JOIN cost_master cm ON eep.CostCenterId= cm.Id 
            INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.headid = head.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.subheadid = subhead.SubHeadingId
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name 
            left JOIN `tbl_vendormaster` vm ON eem.vendor = vm.id
            
            INNER JOIN tbl_user tu ON eem.userid = tu.id $qry group by eem.Id"));
       
    
    
     
}

public function pnl_report()
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
    
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'order'=>array('branch_name')));
    $branchMaster = $all+$branchMaster2;
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
    $this->set('branch_master',$branchMaster);
    $this->set('head',array_merge(array('All'=>'All'),$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))));
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        $qry2 = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        
        if($Expense['branch_name']!='All')
        {
            $qry .= " and em.BranchId='".$Expense['branch_name']."'";
            $qry2 .= " and eep.BranchId='".$Expense['branch_name']."'";
        }
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
            $qry2 .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
            $qry2 .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
        
//        echo "SELECT tab.Branch,cm2.CostCenterName,HeadingDesc,ExpenseTypeName,SUM(Total) Total,SUM(Processed)Processed FROM 
//(SELECT em.Branch,hm.HeadingDesc,ep.ExpenseTypeName,SUM(ep.Amount)`Total`,0 `Processed` FROM `expense_master` em
//INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
//INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
//$qry
//GROUP BY em.HeadId,ep.ExpenseTypeId
//UNION ALL
//SELECT cm.branch,hm.HeadingDesc,cm.cost_center,0 `Total`, SUM(eep.Amount) `Processed` FROM 
//`expense_entry_master` em INNER JOIN `expense_entry_particular` eep ON em.Id = eep.ExpenseEntry
//INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
//INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
//$qry
//GROUP BY em.HeadId,eep.CostCenterId)tab
//inner join cost_master cm2 on tab.ExpenseTypeName = cm2.cost_center
//GROUP BY HeadingDesc,ExpenseTypeName"; exit;
//        
       $this->set('PNLreport',$this->ExpenseMaster->query("SELECT tab.Branch,cm2.CostCenterName,HeadingDesc,ExpenseTypeName,SUM(Total) Total,SUM(Processed)Processed FROM 
(SELECT em.Branch,hm.HeadingDesc,ep.ExpenseTypeName,SUM(ep.Amount)`Total`,0 `Processed` FROM `expense_master` em
INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
$qry
GROUP BY em.HeadId,ep.ExpenseTypeId
UNION ALL
SELECT cm.branch,hm.HeadingDesc,cm.cost_center,0 `Total`, SUM(eep.Amount) `Processed` FROM 
`expense_entry_master` em INNER JOIN `expense_entry_particular` eep ON em.Id = eep.ExpenseEntry
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
$qry
GROUP BY em.HeadId,eep.CostCenterId)tab
inner join cost_master cm2 on tab.ExpenseTypeName = cm2.cost_center
GROUP BY HeadingDesc,ExpenseTypeName"));
       
    }
    
     
}

public function export_pnl_report()
{
    $this->layout = "ajax";
        if($this->request->is('POST'))
      {$Expense = $this->request->data;}
      else
      {
          $Expense = $this->params->query;
      }
      
      $this->set('type',$Expense['type']);
        $qry = "Where 1=1";
        $qry2 = "Where 1=1";
        //$Expense = $this->params->query;
        //print_r($this->params->query); exit;
        if($Expense['BranchId']!='All')
        {
            
            $qry .= " and em.BranchId='".$Expense['BranchId']."'";
            $qry2 .= " and eep.BranchId='".$Expense['BranchId']."'";
        }
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
            $qry2 .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
            $qry2 .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
                
        
       $this->set('PNLreport',$this->ExpenseMaster->query("SELECT tab.Branch,cm2.CostCenterName,HeadingDesc,ExpenseTypeName,SUM(Total) Total,SUM(Processed)Processed FROM 
(SELECT em.Branch,hm.HeadingDesc,ep.ExpenseTypeName,SUM(IF(EntryStatus=1,ep.Amount,0))`Total`,0 `Processed` FROM `expense_master` em
INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
$qry
GROUP BY em.HeadId,ep.ExpenseTypeId
UNION ALL
SELECT cm.branch,hm.HeadingDesc,cm.cost_center,SUM(eep.Amount)`Total`, SUM(eep.Amount) `Processed` FROM 
`expense_entry_master` em INNER JOIN `expense_entry_particular` eep ON em.Id = eep.ExpenseEntry
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
$qry2
GROUP BY em.HeadId,eep.CostCenterId)tab
inner join cost_master cm2 on tab.ExpenseTypeName = cm2.cost_center
GROUP BY HeadingDesc,ExpenseTypeName"));
       
    
    
     
}

public function imprest_detail()
{
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     
    $branchName ='';
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
            $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
        }
        else
        {
            $userid = $this->Session->read('userid');
            $branch4 = $this->Addbranch->query("SELECT BranchId,bm.branch_name FROM `tbl_grn_access` tga inner join branch_master bm on tga.BranchId = bm.id  WHERE tga.UserId='$userid'");
            $all = array('All'=>'All');
            foreach($branch4 as $br)
            {
                $branchMaster[$br['tga']['BranchId']] = $br['bm']['branch_name'];
            }
        }
    
    //$imprest = $this->ImprestManager->find('list',array('fields'=>array('Id','UserName'),'order'=>array('UserName'))) ; 
        if(!empty($branchMaster))
    $this->set('branch_master',$all+$branchMaster);
        
    //$this->set('imprest',  array_merge($all,$imprest));
    
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        $branch = '';
        if($Expense['BranchId']!='All')
        {
            $branch = " and BranchId='".$Expense['BranchId']."'";
            $branch1 = " and eem.BranchId='".$Expense['BranchId']."'";
            $branch2 = " and iam.BranchId='".$Expense['BranchId']."'";
        }
        
        
        if($Expense['BranchId']!='All')
        {
            $branchArray = $this->Addbranch->find('first',array('fields'=>'branch_name','conditions'=>array('id'=>$Expense['BranchId'])));
            $this->set('Branch',$branchArray['Addbranch']['branch_name']);
        }
        else
        {
            $this->set('Branch','All');
        }
        
        if($Expense['ImprestManagerId']!='All')
        {
            $ImprestManagerArray = $this->ImprestManager->find('first',array('fields'=>array('UserName','UserId'),'conditions'=>array('Id'=>$Expense['ImprestManagerId'])));
            $imprest = " and ImprestManagerId='".$Expense['ImprestManagerId']."'";
            $imprest1 = " and userid='".$ImprestManagerArray['ImprestManager']['UserId']."'";
            $this->set('ImprestManager',$ImprestManagerArray['ImprestManager']['UserName']);
        }
        else
        {
            $this->set('ImprestManager','All');
        }
        $FromDate1 = explode('-',$Expense['DateFrom']);
        krsort($FromDate1);
        $FromDate  = implode('-',$FromDate1);
        
        $ToDate1 = explode('-',$Expense['DateTo']);
        krsort($ToDate1);
        $ToDate  = implode('-',$ToDate1);
        
        //echo "select sum(Amount) TotalAllotment from imprest_allotment_master where date(EntryDate)<date('$FromDate') $branch $imprest"; exit;
       $TotalAllotment =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalAllotment from imprest_allotment_master where date(EntryDate)<date('$FromDate') $branch $imprest");
       $TotalAllotment = $TotalAllotment['0']['0']['TotalAllotment'];
       //$this->set('opening'.$TotalAllotment);
       
       $TotalGrn =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalGrn from expense_entry_master where ExpenseEntryType='Imprest' and date(createdate)<date('$FromDate') $branch $imprest1");
       $TotalGrn = $TotalGrn['0']['0']['TotalGrn'].'<br>';
       
       $Balance = $TotalAllotment - $TotalGrn;
       $this->set('opening',$Balance);
       //print_r($Balance); exit;
       $dateLoop = $this->ExpenseEntryMaster->query("SELECT DATEDIFF('$ToDate','$FromDate') count FROM `expense_entry_master` LIMIT 1"); 
       $count = $dateLoop['0']['0']['count'].'<br>';
       
       $i=0;
       for($a = 0; $a<=$count; $a++)
       {
           
           
           
           $inflowArr = $this->ExpenseEntryMaster->query("SELECT *,DATE_FORMAT(STR_TO_DATE(eem.ExpenseDate,'%d-%m-%Y'),'%d-%b-%Y')grndate FROM `expense_entry_master` eem
            INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId
            WHERE ExpenseEntryType='Imprest' and eem.CreateDate = ADDDATE('$FromDate',INTERVAL $a DAY)
            AND ADDDATE('$FromDate',INTERVAL $a DAY)<='$ToDate' $branch1 $imprest1"); 
           
           $listCount1 = $listCount2 = 0;
           $flagDetails = false;
           if(!empty($inflowArr))
           {
               $flagDetails = true;
               $listCount1 = count($inflowArr); 
               foreach($inflowArr as $inflow)
               {
                    $data = array();
                $data['date'] = $inflow['0']['grndate'];
                $data['grn'] = $inflow['eem']['GrnNo'];
                $data['head'] = $inflow['hm']['HeadingDesc'];
                $data['subhead'] = $inflow['shm']['SubHeadingDesc'];
                $data['outflow'] = $inflow['eem']['Amount'];
                $Balance = $Balance-$inflow['eem']['Amount'];
                $data['balance'] = $Balance;
                $data['remarks'] = $inflow['eem']['Description'];
                $dataArray[$i++] = $data;
                
               }
           }
           
           $outflowArr = $this->ExpenseEntryMaster->query("SELECT *,DATE_FORMAT(EntryDate,'%d-%b-%Y') `EntryDate` FROM `imprest_allotment_master` iam
LEFT JOIN `tbl_bank` tu ON iam.BankId = tu.Id
            WHERE DATE(EntryDate) = ADDDATE('$FromDate',INTERVAL $a DAY)
            AND ADDDATE('$FromDate',INTERVAL $a DAY)<='$ToDate' $branch2 $imprest"); 
           
           if(!empty($outflowArr))
           {
               $listCount2 = count($outflowArr);$b=0;
               foreach($outflowArr as $outflow)
               {
//                   if( $b<($listCount1-$listCount2))
//                   {
//                        $dataArray[$i-$listCount1+$b]['inflow'] = $outflow['iam']['Amount'];
//                        $Balance = $Balance+$outflow['iam']['Amount'];
//                        $dataArray[$i-$listCount1+$b]['balance'] = $Balance;
//                   }
//                   else
//                   {
                       $data = array();
                       
                       $data['inflow'] = $outflow['iam']['Amount'];
                       $data['date'] = $outflow['0']['EntryDate'];
                       $Balance = $Balance+$outflow['iam']['Amount'];
                       $data['balance'] = $Balance;
                       if($outflow['iam']['PaymentMode']=='1'){$data['PaymentMode']='Cheque';}
                       else if($outflow['iam']['PaymentMode']=='2'){$data['PaymentMode']='Cash';}
                       else if($outflow['iam']['PaymentMode']=='3'){$data['PaymentMode']='Fund Transfer';}
                       $data['PaymentNo'] = $outflow['iam']['PaymentNo'];
                       $data['BankId'] = $outflow['tu']['bank_name'];
                       $data['remarks'] = $outflow['iam']['Remarks'];
                       $dataArray[$i++] = $data;
                       //print_r($data); exit;
//                   }
               }
           }
       }
       $this->set('closing',$Balance);
       $this->set('data',$dataArray);
       
    }
    
     
}

public function export_imprest_detail()
{
    $this->layout='ajax';
      
       if($this->request->is('POST'))
      {$Expense = $this->request->data;}
      else
      {
          $Expense = $this->params->query;
      }
      
      $this->set('type',$Expense['type']);
     
    
        //print_r($this->params->query); exit;
        $qry = "Where 1=1";
        //$Expense = $this->request->data['Expense'];
        $branch = '';
        if($Expense['BranchId']!='All')
        {
            $branch = " and BranchId='".$Expense['BranchId']."'";
            $branch1 = " and eem.BranchId='".$Expense['BranchId']."'";
            $branch2 = " and iam.BranchId='".$Expense['BranchId']."'";
            $userid = $this->Session->read('userid');
            $branch4 = $this->Addbranch->query("SELECT GROUP_CONCAT(BranchId) BranchArr FROM `tbl_grn_access` WHERE UserId='$userid'");
            $branch5 = $branch4[0][0]['BranchArr'];
            $branch3 = " and eep.BranchId in ($branch5)";
            $branch6 = " and eep.BranchId='".$Expense['BranchId']."'";
            
        }
        
        if($Expense['BranchId']!='All')
        {
            $branchArray = $this->Addbranch->find('first',array('fields'=>'branch_name','conditions'=>array('id'=>$Expense['BranchId'])));
            $this->set('Branch',$branchArray['Addbranch']['branch_name']);
        }
        else
        {
            $this->set('Branch','All');
        }
        
        if($Expense['ImprestManagerId']!='All')
        {
            $ImprestManagerArray = $this->ImprestManager->find('first',array('fields'=>array('UserName','UserId'),'conditions'=>array('Id'=>$Expense['ImprestManagerId'])));
            $imprest = " and ImprestManagerId='".$Expense['ImprestManagerId']."'";
            $imprest1 = " and userid='".$ImprestManagerArray['ImprestManager']['UserId']."'";
            $imprest2 = " and eem.userid='".$ImprestManagerArray['ImprestManager']['UserId']."'";
            $this->set('ImprestManager',$ImprestManagerArray['ImprestManager']['UserName']);
        }
        else
        {
            $this->set('ImprestManager','All');
        }
        $FromDate1 = explode('-',$Expense['DateFrom']);
        krsort($FromDate1);
        $FromDate  = implode('-',$FromDate1);
        
        $ToDate1 = explode('-',$Expense['DateTo']);
        krsort($ToDate1);
        $ToDate  = implode('-',$ToDate1);
        
        $TotalAllotment =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalAllotment from imprest_allotment_master where date(EntryDate)<date('$FromDate') $branch $imprest");
         $TotalAllotment = $TotalAllotment['0']['0']['TotalAllotment'];  
        //echo "select sum(Amount) TotalGrn from expense_entry_master where ExpenseEntryType='Imprest' and str_to_date(ExpenseDate,'%d-%m-%Y')<date('$FromDate') $branch $imprest1";exit;
        
       $TotalGrn =  $this->ExpenseEntryMaster->query("SELECT SUM(eep.Amount) TotalGrn FROM expense_entry_master eem
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
 WHERE eem.ExpenseEntryType='Imprest'  and str_to_date(eem.ExpenseDate,'%d-%m-%Y')<date('$FromDate') $branch6 $imprest2");
       $TotalGrn = $TotalGrn['0']['0']['TotalGrn'].'<br>'; 
       
       $Balance = $TotalAllotment - $TotalGrn;
       $this->set('opening',$Balance);
       //print_r($Balance); exit;
       $dateLoop = $this->ExpenseEntryMaster->query("SELECT DATEDIFF('$ToDate','$FromDate') count FROM `expense_entry_master` LIMIT 1"); 
       $count = $dateLoop['0']['0']['count'].'<br>';
       
       $i=0;
       for($a = 0; $a<=$count; $a++)
       {
           
           $inflowArr = $this->ExpenseEntryMaster->query("SELECT *,DATE_FORMAT(STR_TO_DATE(eem.ExpenseDate,'%d-%m-%Y'),'%d-%b-%Y')grndate FROM `expense_entry_master` eem
		INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
            INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId
            WHERE eem.ExpenseEntryType='Imprest' and str_to_date(eem.ExpenseDate,'%d-%m-%Y') = ADDDATE('$FromDate',INTERVAL $a DAY)
            AND ADDDATE('$FromDate',INTERVAL $a DAY)<='$ToDate' $branch3 $imprest2 group by eem.Id"); 
           
           $listCount1 = $listCount2 = 0;
           $flagDetails = false;
           if(!empty($inflowArr))
           {
               $flagDetails = true;
               $listCount1 = count($inflowArr); 
               foreach($inflowArr as $inflow)
               {
                    $data = array();
                $data['date'] = $inflow['0']['grndate'];
                $data['grn'] = $inflow['eem']['GrnNo'];
                $data['head'] = $inflow['hm']['HeadingDesc'];
                $data['subhead'] = $inflow['shm']['SubHeadingDesc'];
                $data['outflow'] = $inflow['eem']['Amount'];
                $Balance = $Balance-$inflow['eem']['Amount'];
                $data['balance'] = $Balance;
                $data['remarks'] = $inflow['eem']['Description'];
                $dataArray[$i++] = $data;
                
               }
           }
           
           $outflowArr = $this->ExpenseEntryMaster->query("SELECT *,DATE_FORMAT(EntryDate,'%d-%b-%Y') `EntryDate` FROM `imprest_allotment_master` iam
LEFT JOIN `tbl_bank` tu ON iam.BankId = tu.Id
            WHERE DATE(EntryDate) = ADDDATE('$FromDate',INTERVAL $a DAY)
            AND ADDDATE('$FromDate',INTERVAL $a DAY)<='$ToDate' $branch2 $imprest"); 
           
           if(!empty($outflowArr))
           {
               $listCount2 = count($outflowArr);$b=0;
               foreach($outflowArr as $outflow)
               {
//                   if( $b<($listCount1-$listCount2))
//                   {
//                        $dataArray[$i-$listCount1+$b]['inflow'] = $outflow['iam']['Amount'];
//                        $Balance = $Balance+$outflow['iam']['Amount'];
//                        $dataArray[$i-$listCount1+$b]['balance'] = $Balance;
//                   }
//                   else
//                   {
                       $data = array();
                       
                       $data['inflow'] = $outflow['iam']['Amount'];
                       $data['date'] = $outflow['0']['EntryDate'];
                       $Balance = $Balance+$outflow['iam']['Amount'];
                       $data['balance'] = $Balance;
                       if($outflow['iam']['PaymentMode']=='1'){$data['PaymentMode']='Cheque';}
                       else if($outflow['iam']['PaymentMode']=='2'){$data['PaymentMode']='Cash';}
                       else if($outflow['iam']['PaymentMode']=='3'){$data['PaymentMode']='Fund Transfer';}
                       $data['PaymentNo'] = $outflow['iam']['PaymentNo'];
                       $data['BankId'] = $outflow['tu']['bank_name'];
                       $data['remarks'] = $outflow['iam']['Remarks'];
                       $dataArray[$i++] = $data;
                       //print_r($data); exit;
//                   }
               }
           }
       }
       $this->set('closing',$Balance);
       $this->set('data',$dataArray);
       
    }

public function imprest_report2()
{
  $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     
    $branchName ='';
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    //$imprest = $this->ImprestManager->find('list',array('fields'=>array('Id','UserName'),'order'=>array('UserName'))) ; 
    $this->set('branch_master',$all+$branchMaster);
    //$this->set('imprest',  array_merge($all,$imprest));  
}

public function export_imprest_report2()
{
    $this->layout='ajax';
      
       if($this->request->is('POST'))
      {$Expense = $this->request->data;}
      else
      {
          $Expense = $this->params->query;
      }
      
       $this->set('type',$Expense['type']); 
        $FromDate1 = explode('-',$Expense['DateFrom']);
        krsort($FromDate1);
        $FromDate  = implode('-',$FromDate1);
        
        $ToDate1 = explode('-',$Expense['DateTo']);
        krsort($ToDate1);
        $ToDate  = implode('-',$ToDate1);
        
        $qry = "Where 1=1";
        //$Expense = $this->request->data['Expense'];
        $branch = '';
        
        if($Expense['BranchId']!='All')
        {
            $qry .= " and iam.BranchId='".$Expense['BranchId']."'";
        }
        
        if($Expense['ImprestManagerId']!='All')
        {
            $qry .= " and iam.ImprestManagerId='".$Expense['ImprestManagerId']."'";
        }
        
        if(!empty($FromDate) && !empty($ToDate))
        {
           $qry .= " and iam.EntryDate BETWEEN '$FromDate' AND '$ToDate'";
        }
                
       $this->set('ImprestReport',$this->ImprestManager->query("SELECT im.Branch,im.UserName,DATE_FORMAT(iam.EntryDate,'%d-%b-%Y')EntryDate,Amount,if(PaymentMode='1','Cheque',if(PaymentMode='2','Cash','Fund Transfer'))PaymentMode,bank_name Bank,PaymentNo,Remarks FROM `imprest_allotment_master` iam
INNER JOIN `imprest_manager` im ON iam.ImprestManagerId = im.Id AND iam.BranchId = im.BranchId
left join tbl_bank bnk on iam.bankid = bnk.Id
$qry
ORDER BY im.Branch,im.UserName")); 
    }
    
public function view_vendor()
{
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     
    $branchName ='';
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster = $this->Addbranch->find('list',array('fields'=>array('Id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    //$imprest = $this->ImprestManager->find('list',array('fields'=>array('Id','UserName'),'order'=>array('UserName'))) ; 
    $this->set('branch_master',$all+$branchMaster);
    //$this->set('imprest',  array_merge($all,$imprest));
}

public function export_vendor()
{
    $this->layout='ajax';
      
       if($this->request->is('POST'))
      {$Expense = $this->request->data;}
      else
      {
          $Expense = $this->params->query;
      }
      
       $this->set('type',$Expense['type']); 
        
        
        $qry = "";
        
        
        
        if($Expense['BranchId']!='All')
        {
            $qry .= " and BranchId='".$Expense['BranchId']."'";
        }
        
        
                
       $this->set('VendorReport',$this->ImprestManager->query("SELECT * FROM `vendor_master` vm INNER JOIN `tbl_bgt_expenseheadingmaster` head ON vm.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON vm.HeadId = subhead.HeadingId AND vm.SubHeadId = subhead.SubHeadingId inner join branch_master bm on vm.branchId=bm.id 
WHERE vm.active = '1' $qry ORDER BY branch_name  
")); 
    }

public function view_tds()
{
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     
    $branchName ='';
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster = $this->Addbranch->find('list',array('fields'=>array('Id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    $this->set('branch_master',$all+$branchMaster);
    $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
    
}
 
public function export_tds()
{
    $this->layout='ajax';
      
      if($this->request->is('POST'))
      {
          $Expense = $this->request->data;
      }
      else
      {
          $Expense = $this->params->query;
      }
      $this->set('monthF',$Expense['FinanceMonth'].'-'.$Expense['FinanceYear']);
      $this->set('type',$Expense['type']); 
      $qry = " and em.CompId='1' ";
      
        if($Expense['CompId']!='All')
        {
            $qry .= " and em.CompId='".$Expense['CompId']."'";
        }
        if($Expense['BranchId']!='All')
        {
            $qry .= " and eep.BranchId='".$Expense['BranchId']."'";
        }
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and date_format(em.ApprovalDate,'%b')='".$Expense['FinanceMonth']."'";
        }
       
        $VendorMaster = $this->VendorMaster->find('all',array('conditions'=>"Id>=661 and TDSEnabled=1"));
        
        
        foreach($VendorMaster as $vm)
        {
            
            $vendorId = $vm['VendorMaster']['Id'];
            $exp = $this->ExpenseEntryMaster->query("SELECT cm.company_name,cm.Branch,FinanceYear,FinanceMonth,em.ApprovalDate,eep.Amount,eep.Rate,eep.Tax,
subhead.SubHeadTDSEnabled,td.description,td.section,td.TDS
 FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
LEFT JOIN tds_master td ON subhead.SubHeadTdsSection = td.Id
WHERE em.vendor='$vendorId' and subhead.SubHeadTDSEnabled='1' $qry   
");
            
            foreach($exp as $d)
            {
                $data = array();
                $BranchState = $this->Addbranch->find('first',array('fields'=>array('branch_state'),'conditions'=>array('branch_name'=>$d['cm']['Branch'])));
                $VendorGSTNo = $this->Addbranch->query("SELECT GSTNo FROM tbl_state_comp_gst_details tscgd WHERE VendorId='$vendorId' LIMIT 1");
                $CompanyGSTNo = $this->Addbranch->query("SELECT ServiceTaxNo FROM tbl_service_tax tst WHERE company_name='".$d['cm']['company_name']."' AND State='".$BranchState['Addbranch']['branch_state']."' AND branch='".$d['cm']['Branch']."' LIMIT 1");
                
                $BranchMaster[] = $d['cm']['Branch'];
                $VendorMasterNew[] = $vm['VendorMaster']['TallyHead'];
                if(strtolower($BranchState['Addbranch']['branch_state'])==strtolower($vm['VendorMaster']['state']))
                {
                    $GSTType = 'state';
                }
                else
                {
                    $GSTType = 'central';
                }
                $tds = round(($d['eep']['Amount']*$d['td']['TDS'])/100,3);
                $data['Branch'] = $d['cm']['Branch'];
                $data['FinanceMonth'] = $d['em']['FinanceMonth'];
                $data['FinanceYear'] = $d['em']['FinanceYear'];
                $data['GSTType'] = $GSTType;
                $data['GSTEnable'] = $vm['VendorMaster']['GSTEnabled'];
                $data['TDSEnabled'] = $d['subhead']['SubHeadTDSEnabled'];
                $data['TDS'] = $d['td']['TDS'];
                $data['TDSSection'] = $d['td']['section'];
                $data['TDSTallyHead'] = $d['td']['description'];
                $data['TallyHead'] = $vm['VendorMaster']['TallyHead'];
                $data['PanNo'] = $vm['VendorMaster']['PanNo'];
                $data['GSTNo'] = $VendorGSTNo['0']['tscgd']['GSTNo'];
                $data['CompanyGSTNo'] = $CompanyGSTNo['0']['tst']['ServiceTaxNo'];
                $data['Amount'] = $d['eep']['Amount'];
                $data['Rate'] = $d['eep']['Rate'];
                
                if($GSTType=='central')
                {
                    //$data['IGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100),3);
                    $data['IGST'] = round($d['eep']['Tax'],3);
                    $data['SGST'] = 0;
                    $data['CGST'] = 0;
                }
                else 
                {
                    $data['IGST'] = 0;
                    //$data['SGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    //$data['CGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    $data['SGST'] = round($d['eep']['Tax']/2,3);
                    $data['CGST'] = round($d['eep']['Tax']/2,3);
                }
                $data['tdsAmount'] = $tds;
                $dataZ[] = $data; 
            }
            
        }
        
        $dataX = array();
        
        $tds1=0;
    foreach($dataZ as $dd)
    {
        if(in_array($dd['Branch'],$dataX))
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
            $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
            $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
        else
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
    }
        
       $BranchMaster = array_unique($BranchMaster);
        sort($BranchMaster);
        $VendorMasterNew = array_unique($VendorMasterNew);
        sort($VendorMasterNew);
        
        $this->set('BranchMaster',$BranchMaster);
        $this->set('VendorMasterNew',$VendorMasterNew);
        $this->set('dataX',$dataX);
        
        //print_r($dataX); exit;
              
      
    }   
    
public function view_section_tds()
{
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
     
    $branchName ='';
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster = $this->Addbranch->find('list',array('fields'=>array('Id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    $this->set('branch_master',$all+$branchMaster);
    $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('id','comp_code'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
}

public function export_section_tds()
{
    $this->layout='ajax';
      
      if($this->request->is('POST'))
      {
          $Expense = $this->request->data;
      }
      else
      {
          $Expense = $this->params->query;
      }
      $this->set('monthF',$Expense['FinanceMonth'].'-'.$Expense['FinanceYear']);
      $this->set('type',$Expense['type']); 
      $qry = " and em.CompId='1' ";
      
        if($Expense['CompId']!='All')
        {
            $qry .= " and em.CompId='".$Expense['CompId']."'";
        }
        if($Expense['BranchId']!='All')
        {
            $qry .= " and eep.BranchId='".$Expense['BranchId']."'";
        }
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and date_format(em.ApprovalDate,'%b')='".$Expense['FinanceMonth']."'";
        }
       
        $VendorMaster = $this->VendorMaster->find('all',array('conditions'=>"Id>661 and TDSEnabled=1"));
        
        
        foreach($VendorMaster as $vm)
        {
            
            $vendorId = $vm['VendorMaster']['Id'];
            
            $exp = $this->ExpenseEntryMaster->query("SELECT cm.company_name,cm.Branch,FinanceYear,FinanceMonth,em.ApprovalDate,eep.Amount,eep.Rate,eep.Tax,
subhead.SubHeadTDSEnabled,td.description,td.section,td.TDS
 FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
LEFT JOIN tds_master td ON subhead.SubHeadTdsSection = td.Id
WHERE em.vendor='$vendorId' and subhead.SubHeadTDSEnabled='1' $qry   
");
            
            foreach($exp as $d)
            {
                $data = array();
                $BranchState = $this->Addbranch->find('first',array('fields'=>array('branch_state'),'conditions'=>array('branch_name'=>$d['cm']['Branch'])));
                $VendorGSTNo = $this->Addbranch->query("SELECT GSTNo FROM tbl_state_comp_gst_details tscgd WHERE VendorId='$vendorId' LIMIT 1");
                $CompanyGSTNo = $this->Addbranch->query("SELECT ServiceTaxNo FROM tbl_service_tax tst WHERE company_name='".$d['cm']['company_name']."' AND State='".$BranchState['Addbranch']['branch_state']."' AND branch='".$d['cm']['Branch']."' LIMIT 1");
                
                $BranchMaster[] = $d['cm']['Branch'];
                $VendorMasterNew[] = $d['td']['section'];
                if(strtolower($BranchState['Addbranch']['branch_state'])==strtolower($vm['VendorMaster']['state']))
                {
                    $GSTType = 'state';
                }
                else
                {
                    $GSTType = 'central';
                }
                $tds = round(($d['eep']['Amount']*$d['td']['TDS'])/100,3);
                $data['Branch'] = $d['cm']['Branch'];
                $data['FinanceMonth'] = $d['em']['FinanceMonth'];
                $data['FinanceYear'] = $d['em']['FinanceYear'];
                $data['GSTType'] = $GSTType;
                $data['GSTEnable'] = $vm['VendorMaster']['GSTEnabled'];
                $data['TDSEnabled'] = $d['subhead']['SubHeadTDSEnabled'];
                $data['TDS'] = $d['td']['TDS'];
                $data['TDSSection'] = $d['td']['section'];
                $data['TDSTallyHead'] = $d['td']['description'];
                $data['TallyHead'] = $vm['VendorMaster']['TallyHead'];
                $data['PanNo'] = $vm['VendorMaster']['PanNo'];
                $data['GSTNo'] = $VendorGSTNo['0']['tscgd']['GSTNo'];
                $data['CompanyGSTNo'] = $CompanyGSTNo['0']['tst']['ServiceTaxNo'];
                $data['Amount'] = $d['eep']['Amount'];
                $data['Rate'] = $d['eep']['Rate'];
                
                if($GSTType=='central')
                {
                    //$data['IGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100),3);
                    $data['IGST'] = round($d['eep']['Tax'],3);
                    $data['SGST'] = 0;
                    $data['CGST'] = 0;
                }
                else 
                {
                    $data['IGST'] = 0;
                    //$data['SGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    //$data['CGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    $data['SGST'] = round($d['eep']['Tax']/2,3);
                    $data['CGST'] = round($d['eep']['Tax']/2,3);
                }
                $data['tdsAmount'] = $tds;
                $dataZ[] = $data; 
            }
            
        }
        
        $dataX = array();
        
        $tds1=0;
    foreach($dataZ as $dd)
    {
        if(in_array($dd['Branch'],$dataX))
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
            $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
            $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
        else
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
    }
        //print_r($dataX); exit;
       $BranchMaster = array_unique($BranchMaster);
        sort($BranchMaster);
        $VendorMasterNew = array_unique($VendorMasterNew);
        sort($VendorMasterNew);
        
        $this->set('BranchMaster',$BranchMaster);
        $this->set('VendorMasterNew',$VendorMasterNew);
        $this->set('dataX',$dataX);
        
        //print_r($dataX); exit;
              
      
    }    
    
  public function reject_report()
  {
        $this->layout='home';
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
  }
  
  public function export_grn_reject_report()
  {
      
      $this->layout='ajax';
      
      if($this->request->is('POST'))
      {
          $Expense = $this->request->data;
      }
      else
      {
          $Expense = $this->params->query;
      }
      
      $this->set('type',$result['type']);
      
      $qry = "1=1 ";
        if($Expense['BranchId']!='All')
        {
            $qry .= " and eep.BranchId='".$Expense['BranchId']."'";
        }
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and eem.FinanceYear='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and eem.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
        
                
       $this->set('GrnReject',$this->ExpenseEntryMaster->query("SELECT bm.branch_name,COUNT(eem.id) cnt FROM expense_master_reject emr
INNER JOIN  expense_entry_master eem  ON emr.ExpenseId = eem.Id
INNER JOIN  (SELECT * FROM expense_entry_particular GROUP BY ExpenseEntry) eep  ON eem.Id = eep.ExpenseEntry
INNER JOIN tbl_user tu1 ON  emr.createby = tu1.Id
INNER JOIN tbl_user tu2 ON emr.rejectby = tu2.Id
INNER JOIN branch_master bm ON eep.BranchId = bm.id
Where $qry
GROUP BY bm.id

")); 
  }
  
  public function pnl_revenue_report()
  {
        $this->layout='home';
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
  }
  
  public function export_pnl_revenue_report()
  {
      
      $this->layout='ajax';
      
      if($this->request->is('POST'))
      {
          $Expense = $this->request->data;
      }
      else
      {
          $Expense = $this->params->query;
      }
      
      $this->set('type',$result['type']);
      
      $qry = "Where 1=1 ";
      $qry1 = "Where 1=1 ";
        if($Expense['BranchId']!='All')
        {
            $qry .= " and eep.BranchId='".$Expense['BranchId']."'";
            $qry1 .= " and bm.Id='".$Expense['BranchId']."'";
        }
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and eem.FinanceYear='".$Expense['FinanceYear']."'";
            $qry1 .= " and pm.Finance_year='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $finArr = explode('-',$Expense['FinanceYear']);
            $monthArr = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            
            if($monthArr[$Expense['FinanceMonth']]>3)
            {
                $month = $Expense['FinanceMonth'].'-'.$finArr[1];
            }
            else
            {
                $month = $Expense['FinanceMonth'].'-'.$finArr[0];
            }
            
            $qry .= " and eem.FinanceMonth='".$Expense['FinanceMonth']."'";
            $qry1 .= " and pm.month='".$month."'";
        }
        
                
       $this->set('Provision',$this->ExpenseEntryMaster->query("SELECT pm.cost_center,pm.branch_name,pm.month,pm.finance_year,pm.provision,cm.process_name,pm.provision 
FROM `provision_master` pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
inner join branch_master bm on pm.branch_name = bm.branch_name
WHERE pm.Finance_year = '2017-18' AND pm.month='Dec-17'")); 
       
       $this->set('Direct',$this->ExpenseEntryMaster->query("SELECT head.HeadingDesc,cm.cost_center,cm.process_name,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE $qry AND head.Cost = 'D'
GROUP BY eep.CostCenterId,eem.HeadId")); 
       
       $this->set('InDirect',$this->ExpenseEntryMaster->query("SELECT head.HeadingDesc,cm.cost_center,cm.process_name,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE $qry AND head.Cost = 'I'
GROUP BY eep.CostCenterId,eem.HeadId")); 
       
  }
    
}