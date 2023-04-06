<?php
App::uses('AppController', 'Controller');
class ExpenseReportsController  extends AppController 
{
    public $components = array('Session');
    public $uses = array('Addbranch','Addcompany','Provision','InitialInvoice','PnlMaster','PnlProcessSave','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','ExpenseEntryMaster','ExpenseEntryParticular','ExpenseParticular','BillMaster','ImprestManager','GrnBranchAccess','VendorMaster','Logx');
	
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
        'export_grn_report','export_grn_reject_report','pnl_revenue_report','export_pnl_revenue_report','get_budget','pnl_process_wise_report','get_pnl_report_process_wise','UserLog','voucher_new_report');
$this->Auth->allow('reject_report','get_proccesed','get_expense_cost_center','save_changes_to_cost_center','get_unproccesed',
        'get_budget_cost_center','save_changes_to_cost_center_budget');
}

public function pnl_process_wise_report()
  {
        $this->layout='home';
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19','2019-20','2020-21')))));
        $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
        $branchMaster = array('All'=>'All') + $branchMaster2;
        $this->set('branch_master',$branchMaster);
        
        $LastLog =   $this->UserLog("Finance Report/PnL/P&L Process Wise Report");
        $this->set('LastLog',$LastLog);
  }
  
 public function get_pnl_report_process_wise() 
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
    $dt=  explode('-',$Expense['FinanceYear']);
    if(in_array($Expense['FinanceMonth'],array('Jan','Feb','Mar')))
    {
      $month=$Expense['FinanceMonth'].'-'.$dt[1]; 
    }
    else
    {
        $month=$Expense['FinanceMonth'].'-'.($dt[1]-1); 
    }
     
    $this->set('month_report',$month);
     
      $this->set('type',$Expense['type']);
      $branch=$Expense['Company'];
      if($branch == 'All'){
          $qu='';
          $qu1='';
          $qu2='';
          $qu3=' 1=1';
         $qu4="";
         $qu5 = "";
         $qu6 = "";
      }
      else
      { 
         $qu= "and bm.branch_name='$branch'";
         $qu1="and branch='$branch'";
         $qu4="and cm.branch='$branch'";
         $qu2="and cm.branch='$branch'";
         $qu3=" pm.branch_name='$branch'";
         $qu5 = " and Branch='$branch'";
         $qu6 = " and branch='$branch'";
         $this->set('branch_wise','branchwise');
      }
      $qry = " 1=1 ";
      $qry1 = " 1=1 ";
      $qry3 = " ";
      $qry4 = " ";
      $this->set('cost_master',$this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>"active='1' $qu1")));
      $this->set('cost_name',$this->CostCenterMaster->find('list',array('fields'=>array('id','process_name'),'conditions'=>"active='1' $qu1")));
      $this->set('orderD',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'D',"close_status"=>"1"),'order'=>array('OrderPriority')))) ;
      $this->set('orderI',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'I',"close_status"=>"1"),'order'=>array('OrderPriority')))) ;
      $this->set('cost_Nbranch' , $this->CostCenterMaster->find('list',array('fields'=>array('id','branch'),'conditions'=>"active='1' $qu1",'order'=>array('branch'=>'asc'))));
      
      
      /// Getting Provision Branch Wise as UnProcessed Provision in Gross Salary in p&l report From table provision_master
      $provisionArr = $this->Provision->query("SELECT cm.id,SUM(pm.provision) provision,cm.Billing FROM provision_master pm
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
 WHERE pm.invoiceType1='Revenue' and pm.finance_year='{$Expense['FinanceYear']}' AND pm.`month` = '$month' $qu4 GROUP BY cm.id;");
      foreach($provisionArr as $pro)
      {
        $provision_master[$pro['cm']['id']] = $pro['0']['provision'];
        if($pro['cm']['Billing']=='1')
        {
            $billing_master_un[$pro['cm']['id']] = $pro['0']['provision'];
            
        }
      }
      
      $billingUnProc_select = "SELECT cm.id,SUM(pm.outsource_amt) billing_amt FROM provision_particulars pm 
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource = cm.cost_center 
 WHERE  pm.FinanceYear='{$Expense['FinanceYear']}' AND pm.`FinanceMonth1` = '{$Expense['FinanceMonth']}' $qu4 GROUP BY cm.id;";  
      $billingUnProcArrRsc = $this->Provision->query($billingUnProc_select);
 
      foreach($billingUnProcArrRsc as $pro)
      {
        //$provision_master[$pro['cm']['id']] += $pro['0']['billing_amt'];
        $billing[$pro['cm']['id']] = $pro['0']['billing_amt'];
        
      }
      $billingProc_select = "SELECT cm.id,SUM(pm.outsource_amt) billing_amt FROM provision_particulars pm 
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource = cm.cost_center 
 WHERE Processed='1' and pm.FinanceYear='{$Expense['FinanceYear']}' AND pm.`FinanceMonth1` = '{$Expense['FinanceMonth']}' $qu4 GROUP BY cm.id;"; 
      $billingProcArrRsc = $this->Provision->query($billingProc_select);
 
      foreach($billingProcArrRsc as $pro)
      {
        $billing_proc[strtoupper($pro['cm']['id'])] += $pro['0']['billing_amt'];
      }
      
      //print_r($billing); exit;
     
      $this->set('provision',$provision_master);
      $this->set('billing',$billing);
      $this->set('billing_proc',$billing_proc);
      //$this->set('billing_proc',$billing_proc);
      $this->set('billing_master_un',$billing_master_un);
      
      
      
      
      
      
      
      /// Getting Sell Invoice as Processed Amount in Gros Salary in p&l report from table table_invoice 
      $InvoiceArr = $this->InitialInvoice->query("SELECT cm.id,SUM(total) total,cm.Billing FROM tbl_invoice ti 
 INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
 WHERE ti.invoiceType='Revenue' and ti.finance_year='{$Expense['FinanceYear']}' AND left(ti.`month`,3)='{$Expense['FinanceMonth']}' AND ti.`status`=0 $qu4 GROUP BY cm.id");
      foreach($InvoiceArr as $pro)
      {
          $inv_master[$pro['cm']['id']] = $pro['0']['total'];
          if($pro['cm']['Billing']=='1')
        {
            $billing_master_proc[$pro['cm']['id']] = $pro['0']['total'];
        }
      }
      
      $this->set('inv_master',$inv_master);
      $this->set('billing_master_proc',$billing_master_proc);
      //print_r($inv_master); exit;
      // Revenue Reimbursement Grn Where HeadId=4 and SubHeadId=59 as Processed Revenue Reimbursement from table expense_master
      
//      echo "SELECT bm.branch_name,SUM(eep.Amount) Total FROM expense_master eem 
//INNER JOIN expense_particular eep
//ON eem.id = eep.ExpenseId
//INNER JOIN branch_master bm ON eep.BranchId = bm.id
// WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' AND eem.HeadId='4' AND eem.SubHeadId='59' $qu
//GROUP BY bm.id"; exit;
      
      
      
      // Revenue Reimbursement Grn Where HeadId=4 and SubHeadId=59 as Processed Revenue Reimbursement  from table expense_entry_master
//      $Reimbursement = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(eep.Amount) Total FROM expense_entry_master eem 
//INNER JOIN expense_entry_particular eep ON eem.id = eep.ExpenseEntry
//INNER JOIN cost_master cm ON eep.CostCenterId=cm.id
// WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' AND eem.HeadId='4' AND eem.SubHeadId='59' $qu4
//GROUP BY cm.id");
//    //print_r($Reimbursement); exit;
//      foreach($Reimbursement as $pro)
//      {
//          $Reimbur_master[$pro['cm']['id']] += $pro['0']['Total'];
//      }
//      //print_r($Reimbur_master); exit;
//      $ReimbursementUn = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(eep.Amount) Total,eem.EntryStatus FROM expense_master eem 
//INNER JOIN expense_particular eep ON eem.id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
//INNER JOIN cost_master cm ON eep.ExpenseTypeName = cm.cost_center 
// WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' 
// AND eem.HeadId='4' AND eem.SubHeadId='59' $qu4
//GROUP BY cm.id");
//     //print_r($ReimbursementUn); exit;   
//      foreach($ReimbursementUn as $pro)
//      {
//          //echo $Reimbur_master[strtoupper($pro['cm']['id'])];
//          if($pro['eem']['EntryStatus']=='0')
//          {
//              $Reimbur_master_Up[$pro['cm']['id']] = $Reimbur_master[$pro['cm']['id']];
//          }
//          else
//          {
//            $Reimbur_master_Up[$pro['cm']['id']] = $pro['0']['Total'];
//          }
//          $Reimbur_master_Up[$pro['cm']['id']]['1'] = $pro['eem']['EntryStatus'];
//      }
//      
//      //print_r($Reimbur_master_Up); exit;
//      $this->set('Reimbur_master_up',$Reimbur_master_Up);
//      $this->set('Reimbur_master',$Reimbur_master);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(NetSalary-Incentive-ExtraDayIncentive-Arrear) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $NetSalary[$pro['cm']['id']] = $pro['0']['NetSalary'];
      }
      
      $this->set('NetSalary',$NetSalary);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(Incentive+ExtraDayIncentive+Arrear) Incentive FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $Incentive[$pro['cm']['id']] = $pro['0']['Incentive'];
      }
      
      //print_r($Incentive); exit;
      
      $this->set('Incentive',$Incentive);
      
       
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(EPF+EPFCompany+AdminChrg) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $EPF[$pro['cm']['id']] = $pro['0']['NetSalary'];
      }
      $this->set('EPF',$EPF);
     
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(ESIC+ESICCompany) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $ESIC[$pro['cm']['id']] = $pro['0']['NetSalary'];
      }
      $this->set('ESIC',$ESIC);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(ProTaxDeduction) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $PT[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      $this->set('PT',$PT);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(IncomeTax) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $TDS[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      $this->set('TDS',$TDS);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(ShortCollection) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $ShortColl[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      $this->set('ShortColl',$ShortColl);
      //print_r($ShortColl); exit;
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(AdvPaid+LoanDed) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $Loan[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      
      $this->set('Loan',$Loan);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(SHSH) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
      foreach($SalaryUploadMaster as $pro)
      {
          $SHSH[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      $this->set('SHSH',$SHSH);
      
      
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(ActualCTC) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.id");
    
      foreach($SalaryUploadMaster as $pro)
      {
          $ActualCTC[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      
      
      $SalaryBusiMaster = $this->ExpenseMaster->query("SELECT head.HeadingDesc,cm.id,eem.EntryStatus,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='{$Expense['FinanceMonth']}'  AND eem.HeadId='24'  $qu2
GROUP BY cm.id");
     
      foreach($SalaryBusiMaster as $pro)
      {
          if($pro['eem']['EntryStatus']=='0')
          {
              $ActualCTCBusi[strtoupper($pro['cm']['id'])] = $ActualCTC[strtoupper($pro['cm']['id'])];
          }
          else
          {
            $ActualCTCBusi[strtoupper($pro['cm']['id'])] = $pro['0']['Amount'];
          }
      }
      
      $this->set('ActualCTC',$ActualCTC);
      $this->set('ActualCTCBusi',$ActualCTCBusi);
      
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(ToAmount) NetSalary FROM `cost_center_cost_transfer_particular` prop
 INNER JOIN cost_master cm ON prop.ToCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='{$Expense['FinanceMonth']}' $qu4
  GROUP BY cm.id");
      
      foreach($SalaryUploadMaster as $pro)
      {
          $Adjust[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
      }
      $this->set('Adjust',$Adjust);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.id,SUM(FromAmount) NetSalary FROM `cost_center_cost_transfer_master` prop
 INNER JOIN cost_master cm ON prop.FromCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='{$Expense['FinanceMonth']}' $qu4
  GROUP BY cm.id");
      
        foreach($SalaryUploadMaster as $pro)
        {
            $Adjust2[strtoupper($pro['cm']['id'])] = $pro['0']['NetSalary'];
        }
        $this->set('Adjust2',$Adjust2);
      
        $get_direct_head = "SELECT head.HeadingId,subhead.SubHeadingId FROM `tbl_bgt_expenseheadingmaster` head 
INNER JOIN 
`tbl_bgt_expensesubheadingmaster` subhead ON head.HeadingId = subhead.HeadingId
WHERE Cost='D' AND head.EntryBy!='Admin' AND head.HeadingId!='24' and head.HeadingId!='23'  ORDER BY head.HeadingId";
      
        $dir_expense_head_arr = $this->ExpenseMaster->query($get_direct_head);
      
        //print_r($dir_expense_head_arr); exit;
        
        foreach($dir_expense_head_arr as $exp_head_arr)
        {
            $expense_head = $exp_head_arr['head']['HeadingId'];
            $expense_subhead = $exp_head_arr['subhead']['SubHeadingId'];
            
            $check_busi_status = "Branch='$branch' and FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' AND HeadId='$expense_head' AND SubHeadId='$expense_subhead'";
            $status = $this->ExpenseMaster->find('first',array('conditions'=>$check_busi_status));
            
            //print_r($status); exit;
            
            if($status['ExpenseMaster']['EntryStatus']=='0')
            {
                $dir_unproc = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'D' AND eem.HeadId!='24' $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";  

            }
            else
            {
                $dir_unproc = "SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'D' AND eem.HeadId!='24'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";

            }
            $DirectUnArr = $this->ExpenseEntryMaster->query($dir_unproc); 
       
            foreach($DirectUnArr as $Dir)
            {
                $UnDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] += $Dir['0']['Amount'];
                $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
                $HeadDir[] = $Dir['head']['HeadingDesc'];
                $BranchNArr[] = strtoupper($Dir['cm']['id']);
            } 
            
            $dir_proc = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'D' AND eem.HeadId!='24'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";
            
            $DirectArr = $this->ExpenseEntryMaster->query($dir_proc); 
       
            foreach($DirectArr as $Dir)
            {
                $Direct[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] += $Dir['0']['Amount'];
                $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
                $HeadDir[] = $Dir['head']['HeadingDesc'];
                $BranchNArr[] = strtoupper($Dir['cm']['id']);
            } 

        }
                
       //print_r($Direct); exit;
//       echo '<br/>';
//       echo '<br/>';
//       print_r($UnDirect); exit;
       $HeadDir = array_unique($HeadDir);
       $SubHeadDir = array_unique($SubHeadDir);
       $BranchNArr = array_unique($BranchNArr);
       
       foreach($SubHeadDir as $sub)
       {
           foreach($HeadDir as $head)
            {
               foreach($BranchNArr as $br)
               {
                    $DataA[$head][$br] +=$Direct[$sub][$head][$br];
                    $DataB[$head][$br] +=$UnDirect[$sub][$head][$br];
               }        
            }
       }
       $Direct = $DataA;
       $UnDirect = $DataB;
       
       //print_r($Direct); exit;
       
       $this->set('Direct',$Direct);
       $this->set('UnDirect',$UnDirect);
       $this->set('SubHeadDir',  array_unique($HeadDir));
       
       $get_Indirect_head = "SELECT head.HeadingId,subhead.SubHeadingId FROM `tbl_bgt_expenseheadingmaster` head 
INNER JOIN 
`tbl_bgt_expensesubheadingmaster` subhead ON head.HeadingId = subhead.HeadingId
WHERE Cost='I' AND head.EntryBy!='Admin' AND head.HeadingId!='24'  ORDER BY head.HeadingId";
      
        $Indir_expense_head_arr = $this->ExpenseMaster->query($get_Indirect_head);
      
        foreach($Indir_expense_head_arr as $exp_head_arr)
        {
            $expense_head = $exp_head_arr['head']['HeadingId'];
            $expense_subhead = $exp_head_arr['subhead']['SubHeadingId'];
            
            $check_busi_status = "Branch='$branch' and FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' AND HeadId='$expense_head' AND SubHeadId='$expense_subhead'";
            $status = $this->ExpenseMaster->find('first',array('conditions'=>$check_busi_status));
            
            if($status['ExpenseMaster']['EntryStatus']=='0')
            {
                $Indir_unproc = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'I' AND eem.HeadId!='24'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc"; 

            }
            else
            {
                $Indir_unproc = "SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'I' AND eem.HeadId!='24'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";

            }
            $InDirectUnArr = $this->ExpenseEntryMaster->query($Indir_unproc); 
       
            foreach($InDirectUnArr as $Dir)
            {
                $UnInDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] = $Dir['0']['Amount'];
                $SubHeadInDir[] = $Dir['subhead']['SubHeadingDesc'];
                $HeadInDir[] = $Dir['head']['HeadingDesc'];
                $BranchInNArr[] = strtoupper($Dir['cm']['id']);
            } 
            
            $Indir_proc = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'I' AND eem.HeadId!='24'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";
            
            $InDirectArr = $this->ExpenseEntryMaster->query($Indir_proc); 
       
            foreach($InDirectArr as $Dir)
            {
                $InDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] = $Dir['0']['Amount'];
                $SubHeadInDir[] = $Dir['subhead']['SubHeadingDesc'];
                $HeadInDir[] = $Dir['head']['HeadingDesc'];
                $BranchInNArr[] = strtoupper($Dir['cm']['id']);
            } 

        }
      
       $billingUnProc_select_contract_fees = "SELECT cm.id,SUM(pm.outsource_amt) billing_amt FROM provision_particulars pm 
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
 WHERE  pm.FinanceYear='{$Expense['FinanceYear']}' AND pm.`FinanceMonth1` = '{$Expense['FinanceMonth']}' $qu4 GROUP BY cm.id;";  
      $billingUnProcArrRsc_fees = $this->Provision->query($billingUnProc_select_contract_fees);
      foreach($billingUnProcArrRsc_fees as $pro)
      {
          $InDirect['Process Outsourcing']['CONTRACT FEES'][$pro['cm']['id']] += $pro['0']['billing_amt'];
          $UnInDirect['Process Outsourcing']['CONTRACT FEES'][$pro['cm']['id']] += $pro['0']['billing_amt'];
        //$billing_proc[$pro['cm']['id']] = $pro['0']['billing_amt'];
        $SubHeadInDir[] = 'Process Outsourcing';
        $HeadInDir[] = 'CONTRACT FEES';
        $BranchInNArr[] = strtoupper($pro['cm']['id']);
      }
       
       
       $HeadInDir = array_unique($HeadInDir);
       $SubHeadInDir = array_unique($SubHeadInDir);
       $BranchInNArr = array_unique($BranchInNArr);
       
       foreach($SubHeadInDir as $sub)
       {
           foreach($HeadInDir as $head)
            {
               foreach($BranchInNArr as $br)
               {
                    $DataC[$head][$br] +=$InDirect[$sub][$head][$br];
                    $DataD[$head][$br] +=$UnInDirect[$sub][$head][$br];
               }        
            }
       }
       $InDirect = $DataC;
       $UnInDirect = $DataD;
       
       $this->set('UnInDirect',$UnInDirect);
       $this->set('InDirect',$InDirect);
       $this->set('SubHeadInDir',  array_unique($HeadInDir));
       
       $PnlMaster = $this->PnlMaster->find('all',array('conditions'=>array('ForPnlType'=>array('Process','MPR'),'ShowPnl'=>array('1','3'))));
       $PnlDataBranch = array(); $PnlDataProcess = array();
       
       foreach($PnlMaster as $pnl)
       {
            if($pnl['PnlMaster']['EntryType']=='Process')
            {
                $PnlProcessSave = $this->PnlProcessSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='{$pnl['PnlMaster']['PnlMasterId']}' $qu6"));
                
                
                foreach($PnlProcessSave as $processRecords)
                {
                    $PnlDataProcess[$pnl['PnlMaster']['Description']][$processRecords['PnlProcessSave']['CostCenterId']]['proc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
                    $PnlDataProcess[$pnl['PnlMaster']['Description']][$processRecords['PnlProcessSave']['CostCenterId']]['unproc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
                }
                
            }
            $PnlProcessHead[] = $pnl['PnlMaster']['Description'];
       }
       
       $PnlProcessSave = $this->PnlProcessSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='6' $qu5"));       
        foreach($PnlProcessSave as $processRecords)
        {
            $Futur_Revenue[$processRecords['PnlProcessSave']['CostCenterId']] += round($processRecords['PnlProcessSave']['PnlAmount']);
            //$Futur_Revenue[$processRecords['PnlProcessSave']['CostCenterId']]['unproc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
        }
        
        $PnlProcessSave = $this->PnlProcessSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='12' $qu5"));       
        foreach($PnlProcessSave as $processRecords)
        {
            $MPR_Seat[$processRecords['PnlProcessSave']['CostCenterId']] += round($processRecords['PnlProcessSave']['Seat']);
            $MPR_Rate[$processRecords['PnlProcessSave']['CostCenterId']] += round($processRecords['PnlProcessSave']['Rate']);
        }
       
       $this->set('PnlBranchHead',$PnlBranchHead);
       $this->set('PnlDataBranch',$PnlDataBranch);
       $this->set('PnlProcessHead',$PnlProcessHead);
       $this->set('PnlDataProcess',$PnlDataProcess);
       $this->set('Futur_Revenue',$Futur_Revenue);
       
       $this->set('MPR_Seat',$MPR_Seat);
       $this->set('MPR_Rate',$MPR_Rate);
       
       $get_finance_head = "SELECT head.HeadingId,subhead.SubHeadingId FROM `tbl_bgt_expenseheadingmaster` head 
INNER JOIN 
`tbl_bgt_expensesubheadingmaster` subhead ON head.HeadingId = subhead.HeadingId
WHERE  head.HeadingId='23' ORDER BY head.HeadingId";
      
        $dir_expense_head_arr = $this->ExpenseMaster->query($get_finance_head);
      
        //print_r($dir_expense_head_arr); exit;
        
        foreach($dir_expense_head_arr as $exp_head_arr)
        {
            $expense_head = $exp_head_arr['head']['HeadingId'];
            $expense_subhead = $exp_head_arr['subhead']['SubHeadingId'];
            
            $check_busi_status = "Branch='$branch' and FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' AND HeadId='$expense_head' AND SubHeadId='$expense_subhead'";
            $status = $this->ExpenseMaster->find('first',array('conditions'=>$check_busi_status));
            
            //print_r($status); exit;
            
            if($status['ExpenseMaster']['EntryStatus']=='0')
            {
                $dir_unproc = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}' $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";   

            }
            else
            {
                $dir_unproc = "SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}'  $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";

            }
            $FinanceUnArr = $this->ExpenseEntryMaster->query($dir_unproc); 
            
            //print_r($FinanceUnArr); exit;
            
            foreach($FinanceUnArr as $Dir)
            {
                $FinanceUN[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] = $Dir['0']['Amount'];
            }
            
            $dir_proc = "SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.id,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId AND head.close_status=1
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId AND subhead.sub_close_status=1
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE head.HeadingId='$expense_head' AND subhead.subHeadingId='$expense_subhead' AND eem.FinanceYear='{$Expense['FinanceYear']}' AND 
eem.FinanceMonth='{$Expense['FinanceMonth']}'   $qu4
GROUP BY cm.id,subhead.SubHeadingDesc";
            
            $FinanceArr = $this->ExpenseEntryMaster->query($dir_proc); 
            
            foreach($FinanceArr as $Dir)
            {
                $Finance[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][$Dir['cm']['id']] = $Dir['0']['Amount'];
            } 

        }
       
        $PnlProcessSave = $this->PnlProcessSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='8' $qu5"));       
        foreach($PnlProcessSave as $processRecords)
        {
            $Finance[$processRecords['PnlProcessSave']['CostCenterId']] += round($processRecords['PnlProcessSave']['PnlAmount']);
            $FinanceUN[$processRecords['PnlProcessSave']['CostCenterId']] += round($processRecords['PnlProcessSave']['PnlProcAmount']);
            //$Futur_Revenue[$processRecords['PnlProcessSave']['CostCenterId']]['unproc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
        }
       
       $this->set('FinanceProc',$Finance);
       $this->set('FinanceUN',$FinanceUN); 
        
       $out_qr = "SELECT * FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center=cm.cost_center 
WHERE bill_no!='' and `status`='0' and ti.grnd!='0' and ti.grnd!='1' $qu2 and DATE(STR_TO_DATE(CONCAT(right(ti.month,2),'-',left(ti.month,3),'-','01'),'%y-%b-%d'))<=
date(LAST_DAY(SUBDATE(STR_TO_DATE(CONCAT(YEAR(CURDATE()),'-','{$Expense['FinanceMonth']}','-','01'),'%Y-%b-%d'),INTERVAL 1 MONTH)))";
        $outstanding_proc_to_date = $this->InitialInvoice->query($out_qr);

        foreach($outstanding_proc_to_date as $out)
        {
            $FinanceYear_out = $out['ti']['finance_year'];
            $company_name_out = $out['cm']['company_name'];
            $branch_name_out = $out['cm']['branch'];
            $branch_name_out_opt = $out['cm']['id'];
            
            $bill_no_out_full = explode("/",$out['ti']['bill_no']);
            $bill_no_out = $bill_no_out_full[0];
            
            $bill_deduct = "SELECT sum(net_amount) netamt,sum(tds_ded) tds_ded FROM bill_pay_particulars bpp WHERE company_name = '$company_name_out' AND branch_name='$branch_name_out' 
            AND financial_year='$FinanceYear_out' AND bill_no='$bill_no_out'";  
            $pay_mas = $this->InitialInvoice->query($bill_deduct);
            
           
            
            if(empty($pay_mas))
            {
                $outstand_proc_data[$branch_name_out_opt] += $out['ti']['grnd']; 
            }
            else
            {
                 $calc = $out['ti']['grnd']-($pay_mas['0']['0']['netamt']+$pay_mas['0']['0']['tds_ded']);
                 if($calc=='1')
                 {
                     
                 }
                 else
                 {
                    $outstand_proc_data[$branch_name_out_opt] +=$calc;
                 }
            }
            
            
            $outstand_cost[$out['cm']['id']] += $out['ti']['total'];
            $outstand_branch[$out['cm']['id']] = $branch_name_out_opt;
        }
        
        
        //print_r($outstand_proc_data); exit;
       $rev_provision_qr = "SELECT * FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center 
WHERE 1=1 $qu2 and DATE(STR_TO_DATE(CONCAT(right(pm.month,2),'-',left(pm.month,3),'-','01'),'%y-%b-%d'))<=
LAST_DAY(SUBDATE(STR_TO_DATE(CONCAT(YEAR(CURDATE()),'-','{$Expense['FinanceMonth']}','-','01'),'%Y-%b-%d'),INTERVAL 1 MONTH))";  
       $outstanding_unproc_to_date = $this->InitialInvoice->query($rev_provision_qr);

        foreach($outstanding_unproc_to_date as $out)
        {
            $outstand_uncost[$out['cm']['id']] += $out['pm']['provision'];
            $outstand_branch[$out['cm']['id']] = $out['cm']['id'];
        }
        
        $outstand_cost_arr = array_keys($outstand_branch);
        
        //print_r($outstand_uncost); exit;
        foreach($outstand_cost_arr as $cost)
        {
            $outstand_unproc_data[$cost] += ($outstand_uncost[$cost] - $outstand_cost[$cost])*1.18;
            //print_r(($outstand_uncost[$cost] - $outstand_cost[$cost])); exit;
        }
        
        //print_r($outstand_unproc_cost); exit;
        
        $this->set('outstand_proc_data',$outstand_proc_data);
        $this->set('outstand_unproc_data',$outstand_unproc_data);
       
       
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
    {$result = $this->params->query;}
      
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
        
        
        
       $this->set('TotalUnProcess',$this->ExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(EntryStatus=0,'Closed','Approved')
`bus_status`,PaymentFile,'1' Action,em.HeadId,em.SubHeadId FROM expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry
UNION ALL
SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',if(Approve3 is null,'FH Pending','Approved'))) 
`bus_status`,PaymentFile,'1' Action,em.HeadId,em.SubHeadId FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry  and Active=1 order by Branch,HeadingDesc,SubHeadingDesc")); 
       
       
       
    $ProcessedMaster = $this->ExpenseEntryMaster->query("SELECT head.HeadingDesc,subhead.SubHeadingDesc,SUM(eep.amount)Total FROM expense_entry_master eem
INNER JOIN `expense_entry_particular` eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `cost_master` cm ON eep.CostCenterId = cm.Id
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name 
WHERE eem.FinanceYear='$year' AND eem.FinanceMonth='$month'
 AND bm.branch_name='$BranchName'  group by eem.HeadId,eem.SubHeadId");

    $Processed = array();
    foreach($ProcessedMaster as $processed)
    {
        $Processed[$processed['head']['HeadingDesc']][$processed['subhead']['SubHeadingDesc']] += $processed['0']['Total'];
    }
    
    //print_r($Processed);exit;
    $this->set('Processed',$Processed);
    
    
}

public function get_proccesed()
{
    $this->layout='ajax';
    if($this->request->is('POST'))
    {$result = $this->request->data;}
    else
    {$result = $this->params->query;}
    
    $BranchName = $result['BranchName'];
    $year =$result['year'];
    $month =$result['month'];
    $headid = $result['headid'];
    $subheadid =$result['subheadid'];
    
    $qry1 = "SELECT eem.id,eem.GrnNo,bm.branch_name,eem.BranchId,eem.FinanceYear,eem.FinanceMonth,hm.HeadingDesc,shm.SubHeadingDesc,eem.Amount
FROM expense_entry_master eem 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId 
INNER JOIN branch_master bm ON eem.branchId = bm.id
where bm.branch_name='$BranchName' and FinanceYear='$year' and FinanceMonth='$month' and eem.HeadId='$headid' and eem.SubHeadId='$subheadid'";// exit;
    $process_arr=$this->ExpenseMaster->query($qry1);
    
    echo '<table border="2">';
    echo '<tr>';
        echo '<th>Srno</th>';
        echo '<th>Branch</th>';
        echo '<th>Heading Desc</th>';
        echo '<th>SubHeadingDesc</th>';
        echo '<th>Amount</th>';
        echo '<th>Action</th>';
    echo '</tr>';
    $i=1;
    foreach($process_arr as $proc)
    {
        echo '<tr>';
            echo '<td>'.$srno.'</td>';
            echo '<td>'.$proc['bm']['branch_name'].'</td>';
            echo '<td>'.$proc['hm']['HeadingDesc'].'</td>';
            echo '<td>'.$proc['shm']['SubHeadingDesc'].'</td>';
            echo '<td>'.$proc['eem']['Amount'].'</td>';
            echo '<td>';
                     echo '<a href="#" onclick="get_cost_center('."'".$proc['bm']['branch_name']."','".$proc['eem']['id']."'".')">Edit</a>';
            echo '</td>';     
        echo '</tr>';
    }
    
    echo '</table>';
    exit;
}



public function get_expense_cost_center()
{
    $this->layout='ajax';
    if($this->request->is('POST'))
    {$result = $this->request->data;}
    else
    {$result = $this->params->query;}
    
    $BranchName = $result['BranchName'];
    $expenseId =$result['expenseId'];
    
    $qr = "select eem.GrnNo,eem.Vendor,sum(eep.Amount) Amount from expense_entry_master  eem "
            . "inner join expense_entry_particular eep on eem.id=eep.ExpenseEntry  where eem.id='$expenseId';";
    $process_arr=$this->ExpenseMaster->query($qr);
    
    $cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>"branch='$BranchName' and active='1'"));

    $vendorId = $process_arr['0']['eem']['Vendor'];
    
    $qr_gst_enable = "SELECT GSTEnable FROM `tbl_state_comp_gst_details` gst WHERE VendorId='$vendorId'";
    $gst_det= $this->ExpenseMaster->query($qr_gst_enable);
    $gst_enable = $gst_det['0']['gst']['GSTEnable'];
    
    
    echo '<form id="save_grn_cost" action="save_changes_to_cost_center" method="post">';
    echo '<table border="2">';
    echo '<tr>';
    echo '<th colspan="2">GRN No.</th>';
    echo '<th colspan="2">'.$process_arr['0']['eem']['GrnNo'].'</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th colspan="2">Amount</th>';
    echo '<th  colspan="2">'.$process_arr['0']['0']['Amount'].'</th>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th>Cost Center</th>';
    echo '<th>Amount</th>';
    echo '<th>GST</th>';
    echo '<th>Tax</th>';
    echo '<th>Total</th>';
    
    echo '</tr>';
    
    foreach($cost_list as $costId=>$cost_center)
    {
        echo '<tr>';
        echo '<td>'.$cost_center.'</td>';
        echo '<td>'.'<input type="text" class="cost" id="'.$costId.'" onkeypress="return isNumberKey(event)" name="cost_amount['.$costId.']'.'" onblur="getsum();"> </td>';
        echo '<td>';
            echo '<select onchange="getsum();" id="gst_per'.$costId.'" name="gst_per['.$costId.']"';
            if($gst_enable=='0')
            {
                echo 'disabled=""';
            }
            echo '>';
                echo '<option value="0">select</option>';
                echo '<option value="5">5%</option>';
                echo '<option value="12">12%</option>';
                echo '<option value="18">18%</option>';
                echo '<option value="28">28%</option>';
            echo '</select>';
        echo '</td>';
        echo '<td>'.'<input type="text" readonly="" id="tax'.$costId.'" onkeypress="return isNumberKey(event)" name="tax['.$costId.']'.'" > </td>';
        echo '<td>'.'<input type="text" readonly="" id="cost_total'.$costId.'" onkeypress="return isNumberKey(event)" name="cost_total['.$costId.']'.'" > </td>';
        
        echo '</tr>';
    }
    
    echo '<tr>';
            echo '<td>Total</td>';
            echo '<td>';
               echo '<input type="text" id="amount_grn_sum" value="0" readonly="">';
            echo '</td>';
            echo '<td></td>';
            echo '<td>';
               echo '<input type="text" id="tax_grn_sum" value="0" readonly="">';
            echo '</td>';
            echo '<td>';
               
               echo '<input type="text" id="grn_total" value="0" readonly="">';
            echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
            echo '<td colspan="2">';
                echo '<a href="#" onclick="save_grn_cost_center()" class="btn btn-primary">Save</a>';
            echo '</td>';
    echo '</tr>';
    
    echo  '</table>';
    echo '<input type="hidden" id="total_grn_sum" value="'.$process_arr['0']['0']['Amount'].'" >';
    echo '<input type="hidden" name="expenseId" value="'.$expenseId.'" >';
    echo '<input type="hidden" name="gst_enable" id="gst_enable" value="'.$gst_enable.'">';
    //echo '<input type="hidden" name="gst_enable" id="gst_enable" value="1">';
    
    echo '</form>';
     
    
    exit;
 }

function save_changes_to_cost_center()
{
    $this->layout="ajax";
    //print_r($this->request->data);
    //exit;
    $ExpenseId = $this->request->data['expenseId'];
    $ExpenseData = $this->ExpenseEntryMaster->find('first',array("conditions"=>"id='$ExpenseId'"));
    //if($this->ExpenseEntryParticular->deleteAll(array('Id'=>$ExpenseId)))
    if(1)
    {
        $cost_master = $this->request->data['cost_amount'];
        $gst_per = $this->request->data['gst_per'];
        $record_arr = array();
        foreach($cost_master as $costId=>$costValue)
        {
            if(!empty($costValue))
            {
                $cost_detail = $this->CostCenterMaster->find('first',array('conditions'=>"id='$costId'"));
            $branch = $cost_detail['CostCenterMaster']['branch'];
            $record = array();
            $branch_master = $this->Addbranch->find('first',array('conditions'=>"branch_name='$branch'"));
            $record['BranchId'] = $branch_master['Addbranch']['id'];
            $record['Particular'] = 'Change For P&L';
            $record['CostCenterId'] = $costId;
            $record['Amount'] = $costValue;
            $gst_per = $gst_per[$costId];
            if(!empty($gst_per))
            {
            $record['Rate'] = $gst_per[$costId];
            $record['Tax'] =$tax= round($costValue*$gst_per[$costId]/100,2);
            $record['Total'] = round($costValue + $tax,2);
            }
            else
            {
                $record['Total'] = $costValue;
            }
            $record['ExpenseEntryType'] = $ExpenseData['ExpenseEntryMaster']['ExpenseEntryType'];
            $record['ExpenseEntry'] = $ExpenseId;
            $record_arr[] = $record;
                
                
                
                
            }
            
            
        }
        
        //print_r($record_arr); exit;
        $this->ExpenseEntryParticular->deleteAll(array('ExpenseEntry'=>$ExpenseId));
        if($this->ExpenseEntryParticular->saveAll($record_arr))
        {
            
            $this->ExpenseEntryMaster->updateAll(array('pnl_change'=>"'0'",'pnl_change_date'=>'now()'),array('id'=>$ExpenseId));
            echo 'record updated successfully.';
        }
        else
        {
            echo 'record not updated successfully.';
        }
    }
    exit;
}
 
public function get_unproccesed()
{
    $this->layout='ajax';
    if($this->request->is('POST'))
    {$result = $this->request->data;}
    else
    {$result = $this->params->query;}
    
    $BranchName = $result['BranchName'];
    $year =$result['year'];
    $month =$result['month'];
    $headid = $result['headid'];
    $subheadid =$result['subheadid'];
    
    $qry1 = "SELECT eem.id,bm.branch_name,eem.BranchId,eem.FinanceYear,eem.FinanceMonth,hm.HeadingDesc,shm.SubHeadingDesc,eem.Amount
FROM expense_master eem 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId 
INNER JOIN branch_master bm ON eem.branchId = bm.id
where bm.branch_name='$BranchName' and FinanceYear='$year' and FinanceMonth='$month' and eem.HeadId='$headid' and eem.SubHeadId='$subheadid'";// exit;
    $process_arr=$this->ExpenseMaster->query($qry1);
    
    echo '<table border="2">';
    echo '<tr>';
        echo '<th>Srno</th>';
        echo '<th>Branch</th>';
        echo '<th>Heading Desc</th>';
        echo '<th>SubHeadingDesc</th>';
        echo '<th>Amount</th>';
        echo '<th>Action</th>';
    echo '</tr>';
    $i=1;
    foreach($process_arr as $proc)
    {
        echo '<tr>';
            echo '<td>'.$srno.'</td>';
            echo '<td>'.$proc['bm']['branch_name'].'</td>';
            echo '<td>'.$proc['hm']['HeadingDesc'].'</td>';
            echo '<td>'.$proc['shm']['SubHeadingDesc'].'</td>';
            echo '<td>'.$proc['eem']['Amount'].'</td>';
            echo '<td>';
                     echo '<a href="#" onclick="get_cost_center_un('."'".$proc['bm']['branch_name']."','".$proc['eem']['id']."'".')">Edit</a>';
            echo '</td>';     
        echo '</tr>';
    }
    
    echo '</table>';
    exit;
}

public function get_budget_cost_center()
{
    $this->layout='ajax';
    if($this->request->is('POST'))
    {$result = $this->request->data;}
    else
    {$result = $this->params->query;}
    
    $BranchName = $result['BranchName'];
    $expenseId =$result['expenseId'];
    
    $qr = "select * from expense_master  em "
            . "inner join expense_particular ep on em.id=ep.ExpenseId  where em.id='$expenseId';";
    $unprocess_arr=$this->ExpenseMaster->query($qr);
    
    $cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>"branch='$BranchName' and active='1'"));
        
    echo '<form id="save_grn_cost" action="save_changes_to_cost_center" method="post">';
    echo '<table border="2">';
    
    echo '<tr>';
    echo '<th>Amount</th>';
    echo '<th>'.$unprocess_arr['0']['em']['Amount'].'</th>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th>Cost Center</th>';
    echo '<th>Amount</th>';
    echo '</tr>';
    
    foreach($cost_list as $costId=>$cost_center)
    {
        echo '<tr>';
        echo '<td>'.$cost_center.'</td>';
        echo '<td>'.'<input type="text" class="cost" id="'.$costId.'" onkeypress="return isNumberKey(event)" name="cost_amount['.$costId.']'.'" onblur="getsum_un();"> </td>';
        echo '</tr>';
    }
    
    echo '<tr>';
            echo '<td>Total</td>';
            echo '<td>';
               echo '<input type="text" id="amount_grn_sum" value="0" readonly="">';
            echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
            echo '<td colspan="2">';
                echo '<a href="#" onclick="save_grn_cost_center_un()" class="btn btn-primary">Save</a>';
            echo '</td>';
    echo '</tr>';
    
    echo  '</table>';
    echo '<input type="hidden" id="total_grn_sum" value="'.$unprocess_arr['0']['em']['Amount'].'" >';
    echo '<input type="hidden" name="expenseId" value="'.$expenseId.'" >';
    
    
    echo '</form>';
     
    
    exit;
 }
 
function save_changes_to_cost_center_budget()
{
    $this->layout="ajax";
    //print_r($this->request->data);
    //exit;
    $ExpenseId = $this->request->data['expenseId'];
    $ExpenseData = $this->ExpenseMaster->find('first',array("conditions"=>"id='$ExpenseId'"));
    //if($this->ExpenseEntryParticular->deleteAll(array('Id'=>$ExpenseId)))
    if(1)
    {
        $cost_master = $this->request->data['cost_amount'];
        
        $record_arr = array();
        foreach($cost_master as $costId=>$costValue)
        {
            if(!empty($costValue))
            {
                $cost_detail = $this->CostCenterMaster->find('first',array('conditions'=>"id='$costId'"));
            $branch = $cost_detail['CostCenterMaster']['branch'];
            $record = array();
            $branch_master = $this->Addbranch->find('first',array('conditions'=>"branch_name='$branch'"));
            $record['BranchId'] = $branch_master['Addbranch']['id'];
            //$record['Particular'] = 'Change For P&L';
            $record['ExpenseTypeId'] = $costId;
            $record['ExpenseTypeName'] = $cost_detail['CostCenterMaster']['cost_center'];
            $record['Amount'] = $costValue;
            $record['ExpenseType'] = 'CostCenter';
            $record['ExpenseId'] = $ExpenseId;
            $record['FinanceYear'] = $ExpenseData['ExpenseMaster']['FinanceYear'];
            $record['FinanceMonth'] = $ExpenseData['ExpenseMaster']['FinanceMonth'];
            $record['HeadId'] = $ExpenseData['ExpenseMaster']['HeadId'];
            $record['SubHeadId'] = $ExpenseData['ExpenseMaster']['SubHeadId'];
            
            $record_arr[] = $record;
            }
            
            
        }
        
        //print_r($record_arr); exit;
        $this->ExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId));
        if($this->ExpenseParticular->saveAll($record_arr))
        {
            
            $this->ExpenseMaster->updateAll(array('pnl_change'=>"'0'",'pnl_change_date'=>'now()'),array('id'=>$ExpenseId));
            echo 'record updated successfully.';
        }
        else
        {
            echo 'record not updated successfully.';
        }
    }
    exit;
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
    $this->set('head',array('All'=>'All')+$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>"EntryBy=''")));
    
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
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT tu.username UserName,em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.Description,em.ExpenseDate ,em.EntryStatus,em.grn_file
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
LEFT JOIN tbl_user tu ON em.userid=tu.id
LEFT JOIN tbl_vendormaster vm ON em.Vendor=vm.Id $qry order by em.Id"));
       
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
         $qry1 = "SELECT tu.username UserName,date(em.ApprovalDate)ApprovalDate,date(em.bill_date) bill_date,em.GrnNo,em.ExpenseEntryType,cm.branch,vm.vendor,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.Description,em.ExpenseDate ,em.EntryStatus,
IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,
eep.Tax,eep.Total,em.grn_file
FROM expense_entry_master em 
INNER JOIN expense_entry_particular eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
Left JOIN tbl_user tu ON em.userid=tu.id
INNER JOIN branch_master bm ON cm.branch = bm.branch_name 
LEFT JOIN tbl_vendormaster vm ON em.Vendor=vm.Id $qry order by em.Id"; 
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query($qry1));
       
    
    
     
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
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18','2018-19','2019-20','2020-21'))));
    
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
             $select  = "SELECT em.Id,SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(LAST_DAY(em.approvalDate),'%d-%b-%y') Dates,head.HeadingDesc,
subhead.SubHeadingDesc,IF(em.Vendor_State_Code=em.Billing_State_Code,'state','central')GSTType,tscgd.GSTEnable,em.bill_no,vm.TDSEnabled,vm.TDS,vm.TDSSection,vm.TDSChange,vm.TDSNew,
vm.TallyHead,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,
vm.TDSTallyHead,subhead.SubHeadTDSEnabled,subhead.SubHeadTds,td.description,td2.description,td.TDS,
cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,
IF(vm.as_bill_to=1,vm.state,bm.state)state,
bm.tally_code,bm.tally_branch,em.GrnNo FROM `expense_entry_master` 
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
GROUP BY cm.branch,em.GrnNo,em.Id   ORDER BY STR_TO_DATE(em.approvalDate,'%d-%m-%Y'),CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)";  
               $ExpenseReport= $this->ExpenseMaster->query($select);
        

        //Changes as per date today 28 oct 2019
        
//       $ExpenseReport= $this->ExpenseMaster->query("SELECT em.Id,SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(LAST_DAY(em.approvalDate),'%d-%b-%y') Dates,head.HeadingDesc,
//subhead.SubHeadingDesc,IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,tscgd.GSTEnable,em.bill_no,vm.TDSEnabled,vm.TDS,vm.TDSSection,vm.TDSChange,vm.TDSNew,
//vm.TallyHead,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,
//vm.TDSTallyHead,subhead.SubHeadTDSEnabled,subhead.SubHeadTds,td.description,td2.description,td.TDS,
//cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,
//IF(vm.as_bill_to=1,vm.state,bm.state)state,
//bm.tally_code,bm.tally_branch,em.GrnNo FROM `expense_entry_master` 
//em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
//INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
//INNER JOIN branch_master bm ON cm.branch = bm.branch_name
//INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
//INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
//INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
//INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
//LEFT JOIN tds_master td ON subhead.SubHeadTdsSection = td.Id
//LEFT JOIN tds_master td2 ON vm.TDSSection = td2.Id
//WHERE  1=1 $qry
//GROUP BY cm.branch,em.GrnNo,em.Id   ORDER BY STR_TO_DATE(em.approvalDate,'%d-%m-%Y'),CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)");
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
        $this->set('data',$this->ExpenseEntryMaster->query("SELECT GrnNo,eem.due_date,date(eem.ApprovalDate) ApprovalDate,date(eem.bill_date) bill_date,cm.branch,cm.cost_center,eem.ExpenseEntryType,eem.FinanceYear,eem.FinanceMonth,head.HeadingDesc,subhead.SubHeadingDesc,
            IF(eep.Total IS NULL,eep.Amount,eep.Total) Amount,ExpenseDate
            FROM `expense_entry_particular` eep INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
            INNER JOIN cost_master cm ON eep.CostCenterId= cm.Id 
            INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.headid = head.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.subheadid = subhead.SubHeadingId
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name $qry group by eem.Id"));
        
        
        
    }
    
  
}



public function Export_imprest_report_breakup()
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
        
         $select = "SELECT GrnNo,date(eem.ApprovalDate)ApprovalDate,eem.due_date,IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,eem.bill_date,cm.branch,bm.branch_name,cm.cost_center,
             eem.ExpenseEntryType,eem.FinanceYear,eem.FinanceMonth,head.HeadingDesc,subhead.SubHeadingDesc,
            sum(eep.Amount) Amount,sum(eep.Tax) Tax,sum(eep.Total) Total,ExpenseDate,vm.vendor,tu.emp_name,eem.description,eem.EntryStatus,eem.grn_file
            FROM `expense_entry_particular` eep INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
            INNER JOIN cost_master cm ON eep.CostCenterId= cm.Id 
            INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.headid = head.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.subheadid = subhead.SubHeadingId
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name 
            left JOIN `tbl_vendormaster` vm ON eem.vendor = vm.id
            
            INNER JOIN tbl_user tu ON eem.userid = tu.id $qry group by eem.Id"; 
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query($select));
       
    
    
     
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
    $this->set('head',array('All'=>'All')+$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    
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
           
           $inflowArrStr = "SELECT *,DATE_FORMAT(eem.ApprovalDate,'%d-%b-%Y')grndate FROM `expense_entry_master` eem
		INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
            INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId
            INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId
            WHERE eem.ExpenseEntryType='Imprest' and str_to_date(eem.ExpenseDate,'%d-%m-%Y') = ADDDATE('$FromDate',INTERVAL $a DAY)
            AND ADDDATE('$FromDate',INTERVAL $a DAY)<='$ToDate' $branch3 $imprest2 group by eem.Id";
           $inflowArr = $this->ExpenseEntryMaster->query($inflowArrStr); 
           
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
            //$qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $FinanceYear = explode('-',$Expense['FinanceYear']);
            $monthArr = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            $monthId = $monthArr[$Expense['FinanceMonth']];
            
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
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2020-21'))));
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
  
  public function UserLog($PageName){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
        else
        $ipaddress = '';
	        
        $PageUrl    =   $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        //$PageName   =   "IT Management/Dashboard";
        $username   =   $this->Session->read('username');
        $NewDate    =   date('Y-m-d H:i:s');
        
        if($_REQUEST['Id'] ==""){
            $this->Logx->save(array('UserName' => "{$username}", 'IpAddress' => "{$ipaddress}", 'PageUrl' => "{$PageUrl}",'PageName' => "{$PageName}",'LogDate' => "{$NewDate}"));
            return $this->Logx->getLastInsertId();
        }
        else{
            $this->Logx->query("UPDATE user_log SET LastLogDate=NOW() WHERE Id='{$_REQUEST['Id']}'");
        }
    }
  
    
    public function voucher_new_report()
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
    $this->set('head',array('All'=>'All')+$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        $FinanceYear = $Expense['FinanceYear'];
        $FinanceMonth = $Expense['FinanceMonth'];
        
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and YEAR(createdate)='$FinanceYear'"; 
        }
        
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and DATE_FORMAT(createdate,'%b') = '$FinanceMonth'";
        }
        
        
        
        //print_r($qry); exit;
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT * FROM `tbl_voucher_entries` $qry"));
       
    } 
    
     
}


    
    
    
}