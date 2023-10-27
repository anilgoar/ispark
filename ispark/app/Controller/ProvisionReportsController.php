<?php
App::uses('AppController', 'Controller');
class ProvisionReportsController  extends AppController {
public $components = array('Session');
public $uses=array('BillMaster','Provision','InitialInvoice','CostCenterMaster','Addbranch','Addcompany');
	
public function beforeFilter() 
{
parent::beforeFilter();    
$this->Auth->allow('index','getMonth','showReport','provision_edit_report','show_edit_report','pnl_basic','export_pnl_basic');
}

public function index()
{
    $this->layout = "home";
    $branchMaster1 = array('All'=>'All');
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
    $branchMaster = array_merge($branchMaster1,$branchMaster2);
    $this->set('branch_master',$branchMaster);
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
}

public function getMonth()
{
    $this->layout = "ajax";
    $branch = $this->params->query['branch_name'];
    
    $data = array('All'=>'All');
    if($branch != 'All')
    {
        $dataX =  $this->Provision->find('all',array('conditions'=>array('branch_name'=>$branch),'fields'=>array('month')));
        foreach($dataX as $d):
           //$Arr = explode('-', $d['Provision']['month']) ;
           //$data[$d['Provision']['month']]  =$Arr['0'];
            $data[$d['Provision']['month']] = $d['Provision']['month'];
        endforeach;
    }
    else
    {
        //$data = array('All'=>'All','Apr-16'=>"Apr",'May-16'=>"May",'Jun-16'=>"Jun",'Jul-16'=>"Jul",'Aug-16'=>"Aug",
        //   "Sep-16"=>"Sep", 'Oct-16'=>"Oct",'Nov-16'=>"Nov",'Dec-16'=>"Dec");
		$data = array_merge(array('All'=>'All'),$this->Provision->find('list',array('fields'=>array('month','month'))));
    }
    $this->set('data',$data);
}
public function showReport()
{
    $this->layout = "ajax";
    $branch = $this->params->query['branch_name'];
    $year = $this->params->query['finance_year'];
    $month = $this->params->query['month'];
    
//    if(in_array($month,array('Jan','Feb','Mar')))
//        {
//            $month=$month."-".$arr[1];
//        }
//        else
//        {
//            $month=$month."-".($arr[1]-1);
//        }
    
    
    if($this->params->query['type'] == 'Branch'){$reportType = 'pm.branch_name'; }
    else {$reportType = 'pm.month'; }
        
    $branch1 = $branch2 = $month1 = $month2 ="";
    if($branch != 'All')
    {  
        $branch1 = " and cm.OPBranch = '".$branch."'"; 
        $branch2 = " and cm2.OPBranch = '".$branch."'";
    }
    if($month != 'All') 
    {  
        $month1 = "and left(pm.month,3) = '".$month."'";
        $month2 = "and left(ti.month,3) = '".$month."'"; 
        $month3 = "and left(pp.FinanceMonth,3)= '".$month."'";
    }
     
   
    
    $data = $this->Provision->query("SELECT cm2.OPBranch,cm2.company_name,cm2.process_name,pm.month,pm.cost_center,SUM(pm.provision) provision, GROUP_CONCAT(IF(tab.billRaised IS NULL,'0',tab.billRaised)) billRaised, 
 SUM(pm.provision -IF(tab.grnd IS NULL OR tab.grnd='',0,tab.grnd))`balance`, GROUP_CONCAT(tab.ActionDate) ActionDate,
GROUP_CONCAT(tab.invoiceDateRaised) invoiceDate, 
 GROUP_CONCAT(tab.ActionRemarks) ActionRemarks FROM provision_master pm LEFT JOIN(SELECT ti.`cost_center`,ti.`month`,
 SUM(total)`grnd`, GROUP_CONCAT(total ORDER BY ti.id)`billRaised`,GROUP_CONCAT(ti.invoiceDate ORDER BY ti.id)`invoiceDateRaised`, GROUP_CONCAT(IF(cm.po_required ='Yes',
IF(ti.approve_po !='Yes',IF(ti.po_date IS NULL OR ti.po_date ='','PO Date Pending',IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),'')),IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),'')),IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),''))) `ActionDate`,
GROUP_CONCAT(ti.po_remarks,ti.grn_remarks) `ActionRemarks`
 FROM tbl_invoice ti 
 INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center WHERE ti.finance_year='$year' and left(ti.month,3)='$month' and ti.status='0' $branch1
  GROUP BY ti.`month`, ti.cost_center)AS tab ON pm.cost_center = tab.cost_center AND pm.month = tab.month
  INNER JOIN cost_master  cm2 ON pm.cost_center =  cm2.cost_center $branch2
  WHERE pm.finance_year='$year'  $month1 
   GROUP BY pm.branch_name,pm.month,pm.cost_center
 ORDER BY $reportType");
    
    
    $data1 = $this->Provision->query("select cm2.OPBranch,cm2.company_name,cm2.process_name,pp.FinanceMonth,pp.Cost_Center_OutSource,sum(outsource_amt)outsource_amt,sum(if(Processed='1',outsource_amt,0)) processed from `provision_particulars` pp inner join `provision_master` pm
on pp.FinanceYear = pm.finance_year and pp.FinanceMonth=pm.month and pp.Cost_Center=pm.cost_center 
inner join cost_master cm2 on pp.Cost_Center_OutSource = cm2.cost_center where 1=1 $branch2 $month3
    group by cm2.OPBranch,pp.FinanceMonth,pp.Cost_Center_OutSource
");
    
    $this->set('data',$data);
    $this->set('data1',$data1);
    $this->set('branch',$branch);
    $this->set('view',$this->params->query['view']);
}

public function provision_edit_report()
{
    $this->layout = "home";
    $branchMaster1 = array('All'=>'All');
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
    $branchMaster = array_merge($branchMaster1,$branchMaster2);
    $this->set('branch_master',$branchMaster);
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
}

public function show_edit_report()
{
    $this->layout = "ajax";
    $branch = $this->params->query['branch_name'];
    $FromDate = $this->params->query['FromDate'];
    $ToDate = $this->params->query['ToDate'];
            
    $branch1 = $branch2 = "";
    if($branch != 'All'){  $branch1 = " and cm.OPBranch = '".$branch."'"; $branch2 = " and cm2.OPBranch = '".$branch."'";} 
     
   $qry = "SELECT cm2.OPBranch,pm.month,pm.cost_center,pm.old_provision,pm.provision provision,pm.provision_balance prov_bal,
        pm.remarks ,tu.username
        FROM provision_master_edit_request pm
        inner join tbl_user tu on pm.userid = tu.id
  INNER JOIN cost_master  cm2 ON pm.cost_center =  cm2.cost_center $branch2
  WHERE date(pm.createdate) between str_to_date('$FromDate','%d-%m-%Y') and str_to_date('$ToDate','%d-%m-%Y')
   GROUP BY pm.branch_name,pm.month,pm.cost_center
 ORDER BY pm.cost_center ";
    
    $data = $this->Provision->query($qry);
    
    $this->set('data',$data);
    $this->set('branch',$branch);
    $this->set('view',$this->params->query['view']);
}


public function pnl_basic()
{
    $FinanceYearLogin = $this->Session->read('FinanceYearLogin');
    $this->set('FinanceYearLogin',$FinanceYearLogin);
    
    $this->layout = "home";
    $branchMaster1 = array('All'=>'All');
    //$branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array("pnl_active"=>'1'),'order'=>array('branch_name')));
    $branchMaster = array_merge($branchMaster1,$branchMaster2);
    $this->set('branch_master',$branchMaster);
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
}

public function export_pnl_basic()
{
    $this->layout = "ajax";
    $branch = $this->params->query['branch_name'];
    $year = $this->params->query['finance_year'];
    $month = $this->params->query['month'];
    $arr =explode('-',$year);
    
    if(in_array($month,array('Jan','Feb','Mar')))
    {
        $fin_month = strtoupper($month)."-".$arr[1];
        $new_date= strtoupper($month)."'".$arr[1];
    }
    else
    {
        $fin_month = strtoupper($month)."-".($arr[1]-1);
        $new_date = strtoupper($month)."'".($arr[1]-1);
    }
    $reportType = 'pm.branch_name'; 
        
    $branch1 = $branch2 = ""; $qry = "Where 1=1";
    if($branch != 'All'){ $branch2 = " and cm2.Branch = '".$branch."'"; $qry .= " and Branch='".$branch."'";} 
    else 
        {
        $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array("pnl_active"=>'1'),'order'=>array('branch_name')));
        $br_in = implode("','", $branchMaster2);
            $branch2 = " and cm2.Branch in ( '".$br_in."')"; 
            $qry .= " and Branch in ('".$br_in."')";
        }
    $qry .= " and FinanceYear='".$year."'";
    $qry .= " and FinanceMonth='".$month."'";
     
   
    
    $data = $this->Provision->query("SELECT cm2.Branch,pm.month,pm.cost_center,SUM(pm.provision) provision, GROUP_CONCAT(IF(tab.billRaised IS NULL,'0',tab.billRaised)) billRaised, 
 SUM(pm.provision -IF(tab.grnd IS NULL OR tab.grnd='',0,tab.grnd))`balance`, GROUP_CONCAT(tab.ActionDate) ActionDate, 
 GROUP_CONCAT(tab.ActionRemarks) ActionRemarks FROM provision_master pm LEFT JOIN(SELECT ti.`cost_center`,ti.`month`,
 SUM(total)`grnd`, GROUP_CONCAT(total ORDER BY ti.id)`billRaised`, GROUP_CONCAT(IF(cm.po_required ='Yes',
IF(ti.approve_po !='Yes',IF(ti.po_date IS NULL OR ti.po_date ='','PO Date Pending',IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),'')),IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),'')),IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',
IF(ti.grn_date IS NULL OR ti.grn_date ='','GRN Date Pending',ti.grn_date),''),''))) `ActionDate`,
GROUP_CONCAT(ti.po_remarks,ti.grn_remarks) `ActionRemarks`
 FROM tbl_invoice ti INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center WHERE ti.month='$fin_month' and ti.status='0' $branch1
  GROUP BY ti.`month`, ti.cost_center)AS tab ON pm.cost_center = tab.cost_center AND pm.month = tab.month
  INNER JOIN cost_master  cm2 ON pm.cost_center =  cm2.cost_center $branch2
  WHERE pm.month='$fin_month' 
   GROUP BY pm.branch_name,pm.month,pm.cost_center
 ORDER BY $reportType");
    
    $this->set('ExpenseReport',$this->Provision->query(
"SELECT em.Id,Branch,EntryNo,hm.cost,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(EntryStatus=0,'Approved','Approved')
`bus_status`,PaymentFile,'1' Action FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT em.Id,Branch,EntryNo,hm.cost,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'Pending',IF(Approve2 IS NULL,'Pending',if(Approve3 is null,'Pending','Approved'))) 
`bus_status`,PaymentFile,'1' Action FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1 order by Branch,HeadingDesc,SubHeadingDesc"));
    
    
    $this->set('data',$data);
    $this->set('branch',$branch);
    $this->set('new_date',$new_date);
    $this->set('view',$this->params->query['view']);
}


}