<?php
App::uses('AppController', 'Controller');
class ExpenseReportsController  extends AppController 
{
    public $components = array('Session');
    public $uses = array('Addbranch','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','ExpenseEntryMaster','ExpenseParticular','BillMaster','ImprestManager');
	
public function beforeFilter() 
{
parent::beforeFilter();    
$this->Auth->allow('index','imprest_report','pnl_report','imprest_detail','get_imprest_manager','export_budget','Export_imprest_report','export_imprest_detail','export_pnl_report');
}

public function get_imprest_manager()
{
    $this->layout="ajax";
        $ImperstList=array();
        $all = array();
        if($this->request->is("POST"))
        {
            $BranchId=$this->request->data['BranchId'];
            
            
            if($BranchId=='All')
            {
                $condition=array('Active'=>'1');
                $all = array('All'=>'All');
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
    $this->layout = "home";
    $role = $this->Session->read('role');
     $all = array();
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
    $branchMaster = $all + $branchMaster2;
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
    $this->set('branch_master',$branchMaster);
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
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
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending',IF(EntryStatus=0,'Closed','Approved')))) 
`bus_status` FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry and Approve3 is not null"));
       
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
        $Expense = $this->request->data['Expense'];
        
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
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending',IF(EntryStatus=0,'Closed','Approved')))) 
`bus_status` FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1"));
       
    
    
    
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
left JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN vendor_master vm ON em.Vendor=vm.Id $qry"));
       
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
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT tu.emp_name,em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.Description,em.ExpenseDate ,em.EntryStatus
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
left JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN vendor_master vm ON em.Vendor=vm.Id $qry"));
       
    
    
     
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
        $Expense = $this->request->data['Expense'];
        
        if($Expense['branch_name']!='All')
        {
            $qry .= " and em.BranchId='".$Expense['branch_name']."'";
        }
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
        
        
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
        //$Expense = $this->params->query;
        //print_r($this->params->query); exit;
        if($Expense['BranchId']!='All')
        {
            $qry .= " and em.BranchId='".$Expense['BranchId']."'";
        }
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and em.FinanceMonth='".$Expense['FinanceMonth']."'";
        }
                
        
       $this->set('PNLreport',$this->ExpenseMaster->query("SELECT tab.Branch,cm2.CostCenterName,HeadingDesc,ExpenseTypeName,SUM(Total) Total,SUM(Processed)Processed FROM 
(SELECT em.Branch,hm.HeadingDesc,ep.ExpenseTypeName,SUM(IF(EntryStatus=1,ep.Amount,0))`Total`,0 `Processed` FROM `expense_master` em
INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
$qry
GROUP BY em.HeadId,ep.ExpenseTypeId
UNION ALL
SELECT cm.branch,hm.HeadingDesc,cm.cost_center,SUM(IF(em2.EntryStatus=0,eep.Amount,0))`Total`, SUM(eep.Amount) `Processed` FROM 
`expense_entry_master` em INNER JOIN `expense_entry_particular` eep ON em.Id = eep.ExpenseEntry
INNER JOIN tbl_bgt_expenseheadingmaster hm ON em.HeadId = hm.HeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
INNER JOIN expense_master em2 ON em.Parent = em2.Id
$qry
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
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
    $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    //$imprest = $this->ImprestManager->find('list',array('fields'=>array('Id','UserName'),'order'=>array('UserName'))) ; 
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
       
       $TotalGrn =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalGrn from expense_entry_master where ExpenseEntryType='Imprest' and str_to_date(ExpenseDate,'%d-%m-%Y')<date('$FromDate') $branch $imprest1");
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
            WHERE ExpenseEntryType='Imprest' and str_to_date(eem.ExpenseDate,'%d-%m-%Y') = ADDDATE('$FromDate',INTERVAL $a DAY)
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
        
        $TotalAllotment =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalAllotment from imprest_allotment_master where date(EntryDate)<date('$FromDate') $branch $imprest");
       $TotalAllotment = $TotalAllotment['0']['0']['TotalAllotment'];
        
       $TotalGrn =  $this->ExpenseEntryMaster->query("select sum(Amount) TotalGrn from expense_entry_master where ExpenseEntryType='Imprest' and str_to_date(ExpenseDate,'%d-%m-%Y')<date('$FromDate') $branch $imprest1");
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
            WHERE ExpenseEntryType='Imprest' and str_to_date(eem.ExpenseDate,'%d-%m-%Y') = ADDDATE('$FromDate',INTERVAL $a DAY)
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