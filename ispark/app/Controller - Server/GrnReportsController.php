<?php
App::uses('AppController', 'Controller');
class GrnReportsController  extends AppController 
{
    public $components = array('Session');
    public $uses = array('Addbranch','Addcompany','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','ExpenseEntryMaster','ExpenseParticular','ExpenseEntryParticular','BillMaster','ImprestManager','GrnBranchAccess','TallyInvoiceVoucherExport',
        'SalaryUpload','SalaryHead','SalaryUploadMaster','SalaryProfileType','Provision','InitialInvoice','PnlMaster','PnlBranchSave','PnlProcessSave');
	
public function beforeFilter() 
{
parent::beforeFilter();    
$this->Auth->allow('index','grn_reject_report','export_grn_reject_report','pnl_revenue_report','export_pnl_revenue_report',
        'grn_imprest_report','export_grn_imprest_report','inv_vch_report','grn_dashboard','export_grn_dashboard','grn_report',
        'export_grn_report','salary_upload','get_salary_voucher_export','salary_vch_report','get_dash','file_report','pnl_branch_wise_report',
        'get_pnl_report_branch_wise','grn_gst_report','export_grn_gst_report','get_pnl_report_month_wise_summary','pnl_summary_report');

}
    
  public function grn_reject_report()
  {
        $this->layout='home';
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2015-16','2016-17'))))));
        $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'order'=>array('branch_name')));
        $branchMaster = array('All'=>'All') + $branchMaster2;
        $this->set('branch_master',$branchMaster);
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
      
      $this->set('type',$Expense['type']);
      
      $qry = "1=1 ";
        if($Expense['comp_Name']!='All')
        {
            $qry .= " and emr.BranchId='".$Expense['comp_Name']."'";
        }
        if($Expense['year']!='All')
        {
            $qry .= " and emr.FinanceYear='".$Expense['year']."'";
        }
        if($Expense['month']!='All')
        {
            $qry .= " and emr.FinanceMonth='".$Expense['month']."'";
        }
        
       
       $this->set('GrnReject',$this->ExpenseEntryMaster->query("SELECT bm.branch_name,COUNT(emr.id) cnt FROM expense_master_reject emr
INNER JOIN tbl_user tu1 ON  emr.createby = tu1.Id
INNER JOIN tbl_user tu2 ON emr.rejectby = tu2.Id
INNER JOIN branch_master bm ON emr.BranchId = bm.id
where $qry
    GROUP BY bm.id
")); 
  }
  
  public function pnl_revenue_report()
  {
        $this->layout='home';
        $role = $this->Session->read('role');
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        
        if($role=='admin')
        {
            $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
            $branchMaster = array('All'=>'All') + $branchMaster2;
        }
        else
        {
            $branchMaster[$this->Session->read("branch_name")] = $this->Session->read("branch_name");
        }
    $this->set('branch_master',$branchMaster);
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
      
      $this->set('type',$Expense['type']);
      
      $qry = " 1=1 ";
      $qry1 = "  ";
      $qry3 = " ";
      $qry4 = " ";
      
      $sCost = array('1'=>'BO/AHMH','2'=>'BO/DEL','3'=>'BSS/BO/CORP/107','4'=>'BO/HYD','5'=>'BO/JPR','6'=>'BO/KNL','7'=>'BO/MRT','8'=>'BO/CHD','9'=>'BO/','12'=>'CM/BO/JPR/0103','13'=>'BSS/BO/QUAL/257');
      
        if($Expense['comp_Name']!='All')
        {
            $qry .= " and cm.branch='".$Expense['comp_Name']."'";
            $qry1 .= " and pm.branch_name='".$Expense['comp_Name']."'";
            $qry2 = " and BranchId='".$Expense['comp_Name']."'";
            
            $qry3 = " and FromBranch='".$Expense['comp_Name']."'";
            $qry4 = " and ToBranch='".$Expense['comp_Name']."'";
            $branchIdget = $this->Addbranch->find('first',array('conditions'=>"branch_name='{$Expense['comp_Name']}'"));
            $branchId = $branchIdget['Addbranch']['id'];
            $this->set('Scost',$sCost[$branchId]);
        }
        else
        {
            $this->set('Scost',$sCost);
        }
        if($Expense['year']!='All')
        {
            $qry .= " and eem.FinanceYear='".$Expense['year']."'";
            $qry1 .= " and pm.Finance_year='".$Expense['year']."'";
            $qry2 .= " and FinanceYear='".$Expense['year']."'";
            $qry3 = " and FinanceYear='".$Expense['year']."'";
            $qry4 = " and FinanceYear='".$Expense['comp_Name']."'";
        }
        if($Expense['month']!='All')
        {
            $finArr = explode('-',$Expense['year']);
            $monthArr = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            
            if($monthArr[$Expense['month']]>3)
            {
                $month = $Expense['month'].'-'.($finArr[1]-1);
            }
            else
            {
                $month = $Expense['month'].'-'.($finArr[1]);
            }
            
            $qry .= " and eem.FinanceMonth='".$Expense['month']."'";
            $qry1 .= " and pm.month='".$month."'";
              $qry2 .= " and FinanceMonth='".$Expense['month']."'";
            $qry3 = " and FinanceMonth='".$Expense['month']."'";  
            $qry4 = " and FinanceMonth='".$Expense['month']."'";
        }
        
        
        
        
        $this->set('Finmonth11',$month);
        $this->set('Salary',$this->SalaryUpload->query("SELECT * FROM salary_master_upload sm WHERE 1=1 $qry2")); 
        $this->set('Deduction',$this->SalaryUpload->query("SELECT * FROM cost_center_cost_transfer_master ccctm WHERE 1=1 $qry3")); 
        $this->set('Addition',$this->SalaryUpload->query("SELECT * FROM cost_center_cost_transfer_particular ccctm WHERE 1=1 $qry4")); 
                
       
        
       $this->set('Provision',$this->ExpenseEntryMaster->query("SELECT pm.cost_center,pm.branch_name,pm.month,pm.finance_year,pm.provision,cm.process_name,pm.provision,pm.out_source_amt 
FROM `provision_master` pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
inner join branch_master bm on pm.branch_name = bm.branch_name
WHERE  pm.invoiceType1='Revenue' $qry1")); 
       
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
    
  public function grn_imprest_report()
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
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    
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

public function export_grn_imprest_report()
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
            $qry .= " and em.FinanceYear='".$year."'";
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
            
            $qry .= " and DATE_FORMAT(em.ApprovalDate,'%b-%y')='".$NewMonth."'";
        }
        
        
        
        //print_r($qry);exit;
        

       $ExpenseReport= $this->ExpenseMaster->query("SELECT em.Id,SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(em.ApprovalDate,'%d-%b-%Y') Dates,head.HeadingDesc,
subhead.SubHeadingDesc,im.TallyHead,
SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,
cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,bm.state,bm.tally_code,bm.tally_branch,em.GrnNo FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
LEFT JOIN imprest_manager im ON em.userid = im.UserId AND bm.branch_name = im.Branch
WHERE  em.ExpenseEntryType='Imprest' $qry
GROUP BY em.Id,cm.branch,em.SubHeadId,im.TallyHead ORDER BY date(em.ApprovalDate),CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)");
       $this->set('ExpenseReport',$ExpenseReport);
    
       $userid = $this->Session->read('userid');
       $date = date('Y-m-d H:i:s');
       
    foreach($ExpenseReport as $post)
    {
      $this->ExpenseEntryMaster->updateAll(array('DownloadStatus'=>'0','DownloadBy'=>"'$userid'",'DownloadDate'=>"'$date'"),array('Id'=>$post['em']['Id']));
    }
     
}
  
   public function inv_vch_report()
   {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('username');
            $FileTye = $this->request->data['GrnReport']['file']['type'];
            $info = explode(".",$this->request->data['GrnReport']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['GrnReport']['file']['tmp_name'];
                $files = fopen($FilePath, "r");
                //$files = file_get_contents($FilePath);
                //echo $files;
                
               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
                $dataArr = array();
                $this->TallyInvoiceVoucherExport->query("Truncate table tbl_tally_row_invoice_data");
                while($row = fgetcsv($files,5000,","))
                {
                        $data['bill_no'] = $row[0];
                        $data['Pending'] = $row[1];
                        $data['Process_Code'] = $row[2];
                        $data['Company'] = $row[3];
                        $data['Branch'] = $row[4];
                        $data['Client'] = $row[5];
                        $data['FinancialYear'] = $row[6];
                        $data['month1'] = $row[7];
                        $data['PONo'] = $row[8];
                        $data['GRN_No'] = $row[9];
                        $data['InvoiceDate'] = $row[10];
                        $data['CompanyGSTNo'] = $row[12];
                        $data['VendorGSTNo'] = $row[13];
                        $data['Amount'] = $row[16];
                        $data['IGST'] = $row[20];
                        $data['CGST'] = $row[21];
                        $data['SGST'] = $row[22];
                        $data['GTotal'] = $row[23];
                        $data['Remarks'] = $row[24];
                        $data['status'] = $row[25];
                        $data['BillAmount'] = $row[26];
                        $data['BillPassed'] = $row[27];
                        $data['BillOtherDeduction'] = $row[28];
                        $data['Deduction'] = $row[29]+$rown[30];
                        $data['BillNoTally'] = $row[31];
                        $data['TDS'] = $row[32];
                        
                        $data['PaymentReceived'] = $row[33];
                        
                        $data['ReceivedOn'] = $row[34];
                        $data['ChequeNo'] = $row[35];
                        
                        $data['Month'] = $row[7];
                        
                        
                        
                        $data['createby'] = $this->Session->read('userid');
                        $data['createdate'] = date('Y-m-d H:i:s');
                        $dataArr[] = $data;
                }
                //print_r($dataArr);
                if($this->TallyInvoiceVoucherExport->saveAll($dataArr))
                {
                   $data = $this->TallyInvoiceVoucherExport->query("SELECT *,IF(cm.company_name='IDC','REVENUE-CALL CENTRE-ISPARK','REVENUE CALL CENTRE-MAS') ExpenseHead,
 IF(LEFT(bm.state_code,2) = LEFT(TallyInvoiceVoucherExport.CompanyGSTNo,2),UPPER(bm.tally_branch),UPPER(tsl.state_list)) tally_branch
  FROM tbl_tally_row_invoice_data TallyInvoiceVoucherExport 
INNER JOIN branch_master bm ON TallyInvoiceVoucherExport.branch = bm.branch_name
INNER JOIN tbl_state_list tsl ON LEFT(TallyInvoiceVoucherExport.CompanyGSTNo,2) = tsl.state_code
INNER JOIN cost_master cm ON TallyInvoiceVoucherExport.Process_Code = cm.cost_center
 WHERE TallyInvoiceVoucherExport.Id!=1"); 
                   $fileName = "Import";
                        header("Content-Type: application/vnd.ms-excel; name='excel'");
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=$fileName.xls");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                echo '<table border="1">';
                echo    '<thead>';
                echo        '<tr>';
                echo            '<th>Vch No</th>';
                echo            '<th>Date</th>';
                echo            '<th>Details</th>';
                echo            '<th>Amount</th>';
                echo            '<th>DebitCredit</th>';
                echo            '<th>Cost Category</th>';
                echo            '<th>Cost Centre</th>';
                echo            '<th>Narration for Each Entry</th>';
                echo            '<th>Narration</th>';
                echo            '<th>VchType</th>';
                echo        '</tr>';
                echo    '</thead>';
                echo    '<tbody>';
             $i=1; $Total=0;//print_r($ExpenseReport); exit;
                    foreach($data as $exp)
                    {
                        $FinanceYear = $exp['TallyInvoiceVoucherExport']['FinancialYear'];
                                    $FinanceMonthArr = explode('-',$exp['TallyInvoiceVoucherExport']['month1']);
                                    $FinanceMonth = $FinanceMonthArr[0];
                                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
                                    $FinanceMonthNum = $monthArray[$FinanceMonth];
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
                                       $FinanceMonth1 =  $monthArray[$FinanceMonth];
                                        if(strlen($FinanceMonth1)==1)
                                        {
                                            $FinanceMonth1 = '0'.$FinanceMonth1;
                                        }
                            /////////// Entry For SubHead    /////////////////
                                   $igst = 0; 
                                   $cgst = 0;
                                   $diff = 0;
                                   $GTotal = 0;
                                   
                            if(!empty($exp['TallyInvoiceVoucherExport']['Amount']))
                            {
                                echo "<tr>";
                                echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";

                                echo "<td>".$exp['0']['ExpenseHead']."</td>";
                                echo "<td>".number_format((float)$exp['TallyInvoiceVoucherExport']['Amount'], 2, '.', '')."</td>";
                                echo "<td>C</td>";
                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                echo "<td>";
                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                echo "</td>";
                                echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";

                                echo "<td>JrnlS</td>";
                                echo "</tr>";
                            }
                            ///////// Entry For SubHead End //////////////////

                            $diff = number_format((float)$exp['TallyInvoiceVoucherExport']['Amount'], 2, '.', '');

                           
                                /////////// Entry For GST Enable Tax      //////////////
                               if(!empty($exp['TallyInvoiceVoucherExport']['CGST']))
                               {   
                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Output CGST @ 9% (".$exp['bm']['state'].")"."</td>";
                                    
                                    if(!empty($exp['TallyInvoiceVoucherExport']['Amount']))
                                    {
                                        //echo "<td>".$exp['TallyInvoiceVoucherExport']['CGST']."</td>";
                                        $cgst = number_format((float)($exp['TallyInvoiceVoucherExport']['Amount']*0.09), 2, '.', '');
                                    }
                                    else 
                                    {
                                        $cgst = number_format((float)($exp['TallyInvoiceVoucherExport']['CGST']), 2, '.', '');
                                    }
                                    echo "<td>".$cgst."</td>";
                                    echo "<td>C</td>";
                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
                                    echo "<td>";
                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>JrnlS</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Output SGST @ 9% (".$exp['bm']['state'].")"."</td>";
                                    echo "<td>".$cgst."</td>";
                                    echo "<td>C</td>";
                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
                                    echo "<td>";
                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>JrnlS</td>";
                                    echo "</tr>";

                                    $diff += $cgst+$cgst;

                               }
                               else if(!empty($exp['TallyInvoiceVoucherExport']['IGST']))
                               {
                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Output IGST @ 18% (".$exp['bm']['state'].")"."</td>";
                                    if(!empty($exp['TallyInvoiceVoucherExport']['Amount']))
                                    {
                                        //echo "<td>".$exp['TallyInvoiceVoucherExport']['CGST']."</td>";
                                        $igst = number_format((float)($exp['TallyInvoiceVoucherExport']['Amount']*0.18), 2, '.', '');
                                    }
                                    else 
                                    {
                                        $igst = number_format((float)($exp['TallyInvoiceVoucherExport']['IGST']), 2, '.', '');
                                    }
                                    echo "<td>".$igst."</td>";
                                    echo "<td>C</td>";
                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
                                    echo "<td>";
                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>JrnlS</td>";
                                    echo "</tr>";
                                    $diff += $igst;
                               }

                               ////////// Entry For GST Disable Tax      //////////////
                            

                            echo "<tr>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                            if(!empty($exp['cm']['TallyHead']))
                            {    
                                echo "<td>".$exp['cm']['TallyHead']."</td>";
                            }
                            else
                            {
                                echo "<td>".$exp['cm']['client']."</td>";
                            }
                            
                            if(!empty($exp['TallyInvoiceVoucherExport']['Amount']))
                            {
                                
                                $GTotal = $exp['TallyInvoiceVoucherExport']['Amount']+$igst+$cgst+$cgst;
                            }
                            else
                            {
                                $GTotal = $exp['TallyInvoiceVoucherExport']['GTotal'];
                            }
                            echo "<td>".number_format((float)($GTotal), 2, '.', '')."</td>";
                            echo "<td>D</td>";
                            echo "<td>".$exp['bm']['tally_branch']."</td>";
                            echo "<td>";
                            echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                            echo "</td>";
                            
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>JrnlS</td>";
                            echo "</tr>";
                        echo "</tr>";

                        $diff = $GTotal-$diff;
                        if(!empty(round($diff,2)))
                        {
                            echo "<tr>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                            echo "<td>Short/Excess Written off</td>";
                            echo "<td>".number_format((float)($diff), 2, '.', '')."</td>";
                            if($diff>0)
                            {
                                echo "<td>C</td>";
                            }
                            else
                            {
                                echo "<td>D</td>";
                            }

                            echo "<td>".$exp['bm']['tally_branch']."</td>";
                            echo "<td>";
                            echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                            echo "</td>";
                            
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' Tally Bill NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>JrnlS</td>";
                            echo "</tr>";
                        }
                    }
                                
                    
                    echo '</tbody>';
                echo '</table>';    

                
                        
                

exit;

		
					
		
           


                    
                    $this->Session->setFlash('File uploaded Successfully');
                }
                else
                {
                    $this->Session->setFlash('Data Not Saved');
                }
            }
            else{
            $this->Session->setFlash('File Format not Valid');}
        }
    } 
    
    public function salary_upload()
   {
        ini_set('memory_limit','512M');
        $this->layout = "home";
        $wrongData = array();
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        
        $this->set('activity','upload');
        
        
        $branchArr = $this->Addbranch->find("list",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name')));
        $uploadSalCheck = $this->SalaryUpload->query("SELECT branchId,FinanceMonth FROM salary_master_upload sm
 WHERE FinanceMonth=DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 MONTH),'%b') GROUP BY BranchId");
        foreach($uploadSalCheck as $smc)
        {
            $SalArr[] = $smc['sm']['branchId'];
        }
 
        $SalaryCheck = array();
        foreach($branchArr as $k=>$v)
        {
            if(in_array($k,$SalArr))
            {
                $SalaryCheck[$v] = 'Yes';
            }
            else
            {
                $SalaryCheck[$v] = 'No';
            }
        }
        
        $this->set('SalaryCheck',$SalaryCheck); 
        if($this->request->is('POST'))
        {
           
            if($this->request->data['Submit']=='Upload')
            {
                $user = $this->Session->read('username');
                $FileTye = $this->request->data['GrnReport']['file']['type'];
                $info = explode(".",$this->request->data['GrnReport']['file']['name']);
                $BranchId = $this->request->data['GrnReport']['BranchId'];
                $Month = $this->request->data['Month'];
                $FinanceYear = $this->request->data['GrnReport']['FinanceYear'];
                $this->set('Month',$Month);
                $this->set('FinanceYear',$FinanceYear);
                $this->set('BranchId',$BranchId);
                
                
                $flagExistCheck = $this->SalaryUpload->query("select * from salary_master_upload where BranchId='$BranchId' and FinanceMonth='$Month' and FinanceYear='$FinanceYear'");
                
                if(empty($flagExistCheck))
                {
                    $TypeFromTable1 =  $this->SalaryProfileType->find('list',array('fields'=>array('Designation','Profile')));
//                    foreach($TypeFromTable as $tt)
//                    {
//                        $TypeFromTable1[$tt['SalaryProfileType']['Profile']][] = $tt['SalaryProfileType']['Designation'];
//                    }
                    //print_r($TypeFromTable1); exit;
                    foreach($TypeFromTable1 as $k=>$v)
                    {
                        $TypeFromTable[strtolower(trim($k))] = strtolower(trim($v));
                        $ProfileFromTable[strtolower(trim($v))] = $k;
                    }
                    //print_r($TypeFromTable); exit;
                    //$ProfileFromTable =  $this->SalaryProfileType->find('list',array('fields'=>array('Designation','Profile')));
                    $BMCcost1 = array('1'=>'BO/AHMH','2'=>'BO/DEL','3'=>'BSS/BO/CORP/107','4'=>'BO/HYD','5'=>'BO/JPR','6'=>'BO/KNL','7'=>'BO/MRT','8'=>'BO/CHD','9'=>'BO/','13'=>'BSS/BO/QUAL/257');
                    foreach($BMCcost1 as $k=>$v)
                    {
                        $BMCcost[strtolower(trim($k))] = strtolower(trim($v));
                    }
                if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
                {
                    $FilePath = $this->request->data['GrnReport']['file']['tmp_name'];
                    $files = fopen($FilePath, "r");
                    
                    $dataArr = array(); $i=0;
                    $this->TallyInvoiceVoucherExport->query("Truncate table salary_master");
                    $flagCheckUploadFormat = true; $flagCheck=true;$flagCheck1=true; $flagValueCheck=true;
                    $colNo = 1; $DesiArr = array_keys($TypeFromTable);
                        
                    while($row = fgetcsv($files,5000,","))
                    {
                        if($flagCheckUploadFormat)
                        {
                            $flagCheckUploadFormat = false;
                            $UploadFormatArr = array('EmpCode','EmpName','CostCenter','Designation','Branch','Basic','HRA','Bonus','Conv.','Portfolio','Medical Allowance','LTA',
                                'Special Allowance','Other Allowance','PLI1','Gross','Working Days','CTCOffered','Current CTC','Earned Days','ExtraDay','Leave','Basic1','HRA1',
                                'Bonus1','Conv','Portfolio1','Special Allowance1','Other Allowance1','Medical Allowance1','Gross1','ESIElig','PFELig','ESIC','EPF','IncomeTax',
                                'Adv Taken','Adv Paid',	'Loan Taken','Loan Ded','Incentive','ExtraDayIncentive','Arrear','PLI','Net Salary','ESIC Company','EPF Company','Admin Chrg',
                                'CTC','SHSH','Mobile Dedcution','ShortCollection','Asset Recovery','Insurance','Pro TaxDeduction','Leave Deduction','Other Deduction',
                                'Other Deduction Remarks','TotalDeduction','Sal Date','EPF No','ESIC No','ChequeNumber','ChequeDate','PrintDate','Left Status','TaxTotalGross',
                                'TaxSection10','TaxBalance','TaxUnderHd','DeductionUnder24','TaxGrossTotal','TaxAggofChapter6','TotalIncome','TaxOnTotalIncome','EduCess',
                                'TaxPayEduCess','TaxDeductedTillPreviousMonth',	'BalanceTax','SalaryPaymentMode','Company Name','Profile','Mandays Paid','Incentive','Actual CTC');
                            
                            foreach($UploadFormatArr as $column)
                            {
                                if(!in_array($column,$row))
                                {
                                    //echo $column; 
                                    //print_r($row); exit;
                                    //exit;
                                    
                                    $flagCheck1=false;
                                    break;
                                }
                            }
                            
                        }
                        else if(!empty($row))
                        { 
                            $colNo++;
                            $data=array();
                            $data['EmpCode']=trim($row['0']);
                            $data['EmpName']=trim($row['1']);
                            $data['CostCenter']=trim($row['2']);
                            $data['Designation']=trim($row['3']);
                            if(in_array(strtolower(trim($row['2'])), $BMCcost))
                            {
                                $data['Type']="BMC"; 
                            }
                            else if(in_array(strtolower(trim($row['3'])),$DesiArr ))
                            {
                                $data['Type']=$TypeFromTable[strtolower(trim($row['3']))]; 
                            }
                            else
                            {
                                
                            }
                            
                            $data['Branch']=trim($row['4']);
                            $data['Basic']=trim($row['5']);
                            $data['HRA']=trim($row['6']);
                            $data['Bonus']=trim($row['7']);
                            $data['Conv']=trim($row['8']);
                            $data['Portfolio']=trim($row['9']);
                            $data['MedicalAllowance']=trim($row['10']);
                            $data['LTA']=trim($row['11']);
                            $data['SpecialAllowance']=trim($row['12']);
                            $data['OtherAllowance']=trim($row['13']);
                            $data['PLI1']=trim($row['14']);
                            $data['Gross']=trim($row['15']);
                            
                            if(empty(trim($row['16'])) || is_numeric($row['16']) || is_float($row['16']))
                            {
                                $data['WorkingDays']=round(trim($row['16']));
                            }
                            else
                            {
                                $this->Session->setFlash("Please Use Numbers in WorkingDays Column, Row $colNo ");
                                $flagValueCheck=false;
                                break; 
                            }
                            $data['CTCOffered']=trim($row['17']);
                            $data['CurrentCTC']=trim($row['18']);
                            
                            if(empty(trim($row['19'])) || is_numeric($row['19']) || is_float($row['19']))
                            {
                                $data['EarnedDays']=round(trim($row['19']));
                            }
                            else
                            {
                                $this->Session->setFlash("Please Use Numbers in EarnedDays Column, Row $colNo ");
                                $flagValueCheck=false;
                                break; 
                            }
                            $data['MandaysPaid']=round(trim($row['16'])/trim($row['19']));
                            
                            $data['ExtraDay']=trim($row['20']);
                            $data['Leave']=trim($row['21']);
                            $data['Basic1']=trim($row['22']);
                            $data['HRA1']=trim($row['23']);
                            $data['Bonus1']=trim($row['24']);
                            $data['Conv2']=trim($row['25']);
                            $data['Portfolio1']=trim($row['26']);
                            $data['Special Allowance1']=trim($row['27']);
                            $data['OtherAllowance1']=trim($row['28']);
                            $data['MedicalAllowance1']=trim($row['29']);
                            $data['Gross1']=trim($row['30']);
                            $data['ESIElig']=trim($row['31']);
                            $data['PFELig']=trim($row['32']);
                            $data['ESIC']=trim($row['33']);
                            $data['EPF']=trim($row['34']);
                            $data['IncomeTax']=trim($row['35']);
                            $data['AdvTaken']=trim($row['36']);
                            $data['AdvPaid']=trim($row['37']);
                            $data['LoanTaken']=trim($row['38']);
                            $data['LoanDed']=trim($row['39']);
                            if(empty(trim($row['40'])) || is_numeric($row['40']) || is_float($row['40']))
                            {
                                $data['Incentive']=round(trim($row['40']));
                            }
                            else
                            {
                                $this->Session->setFlash("Please Use Numbers in Incentive Column, Row $colNo ");
                                $flagValueCheck=false;
                                break; 
                            }
                            
                            $data['ExtraDayIncentive']=trim($row['41']);
                            $data['Arrear']=trim($row['42']);
                            $data['PLI']=trim($row['43']);
                            $data['NetSalary']=trim($row['44']);
                            $data['ESICCompany']=trim($row['45']);
                            $data['EPFCompany']=trim($row['46']);
                            $data['AdminChrg']=trim($row['47']);
                            $data['CTC']=trim($row['48']);
                            if(empty(trim($row['84'])) || is_numeric($row['84']) || is_float($row['84']))
                            {
                                $data['ActualCTC']=round(trim($row['84']));
                            }
                            else
                            {
                                $this->Session->setFlash("Please Use Numbers in ActualCTC Column, Row $colNo ");
                                $flagValueCheck=false;
                                break; 
                            }
                            $data['SHSH']=trim($row['49']);
                            $data['MobileDedcution']=trim($row['50']);
                            $data['ShortCollection']=trim($row['51']);
                            $data['AssetRecovery']=trim($row['52']);
                            $data['Insurance']=trim($row['53']);
                            $data['ProTaxDeduction']=trim($row['54']);
                            $data['LeaveDeduction']=trim($row['55']);
                            $data['OtherDeduction']=trim($row['56']);
                            $data['OtherDeductionRemarks']=trim($row['57']);
                            $data['TotalDeduction']=trim($row['58']);
                            $data['SalDate']=trim($row['59']);
                            $data['EPFNo']=trim($row['60']);
                            $data['ESICNo']=trim($row['61']);
                            $data['ChequeNumber']=trim($row['62']);
                            $data['ChequeDate']=trim($row['63']);
                            $data['PrintDate']=trim($row['64']);
                            $data['LeftStatus']=trim($row['65']);
                            $data['TaxTotalGross']=trim($row['66']);
                            $data['TaxSection10']=trim($row['67']);
                            $data['TaxBalance']=trim($row['68']);
                            $data['TaxUnderHd']=trim($row['69']);
                            $data['DeductionUnder24']=trim($row['70']);
                            $data['TaxGrossTotal']=trim($row['71']);
                            $data['TaxAggofChapter6']=trim($row['72']);
                            $data['TotalIncome']=trim($row['73']);
                            $data['TaxOnTotalIncome']=trim($row['74']);
                            $data['EduCess']=trim($row['75']);
                            $data['TaxPayEduCess']=trim($row['76']);
                            $data['TaxDeductedTillPreviousMonth']=trim($row['77']);
                            $data['BalanceTax']=trim($row['78']);
                            $data['SalaryPaymentMode']=trim($row['79']);
                            $data['CompanyName']=trim($row['80']);
                            $data['BranchId']=$BranchId;
                            $data['FinanceMonth']=$Month;
                            $data['FinanceYear']=$FinanceYear;
                            $data['createby'] = trim($this->Session->read('userid'));
                            $data['createdate'] = trim(date('Y-m-d H:i:s'));
                            $dataAr[] = $data;
                        }    
                    }
                   
                    if($flagCheck && $flagValueCheck)
                    {
                        $this->set('activity','check');
                        $this->SalaryUpload->query("SET GLOBAL max_allowed_packet=64*1024*1024");
                        if($this->SalaryUpload->saveAll($dataAr))
                        {
                            $flagCheck =true;
                            $CheckBranchExist = $this->SalaryUpload->query("SELECT Branch from salary_master sm group by Branch");
                            foreach($CheckBranchExist as $CBE)
                            {
                                if(strtolower($CBE['sm']['Branch'])!=strtolower($branchArr[$BranchId]))
                                {
                                    $flagCheck=false;
                                    $this->set('error',"error");
                                    $this->Session->setFlash('Branch Not Matched');
                                    break;
                                }
                            }

                            ///////////////   Getting Cost Center From CostCenter Master ////////////////////
                            $CheckCostCenterExist = $this->SalaryUpload->query("SELECT CostCenter from salary_master sm  group by CostCenter");
                            $CostCenterArr = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array("branch"=>$branchArr[$BranchId])));

                            foreach($CostCenterArr as $cost)
                            {
                                $CostCenterArrNew[] = strtolower($cost);
                            }
                            ///////////////   Getting Cost Center From CostCenter Master Ends Here////////////////////


                            ///////////////   Checking Cost Center Exists Or Not////////////////////
                            
                            foreach($CheckCostCenterExist as $CBE)
                            {
                                if(!in_array(strtolower($CBE['sm']['CostCenter']),$CostCenterArrNew))
                                {
                                    $flagCheck=false;
                                    $NotMatchedCostCenterList[] = $CBE['sm']['CostCenter'];
                                    $this->set('error',"error");
                                    $this->Session->setFlash('CostCenter Not Matched With Branch');
                                    //print_r($CostCenterArrNew); 
                                    //echo strtolower($CBE['sm']['CostCenter']); exit;
                                    //break;
                                }
                            }
                            ///////////////   Checking Cost Center Exists Ends Here////////////////////

                            ///////////////   Checking Business Case Has Been Made For Salary Approval Starts From Here////////////////////
                            $ExpenseMasterArr = $this->ExpenseMaster->query("SELECT HeadId,SubHeadingDesc FROM expense_master em 
                            INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
                            WHERE em.BranchId='$BranchId' AND em.FinanceYear='$FinanceYear' AND em.FinanceMonth='$Month' AND em.HeadId='24'");

                            foreach($ExpenseMasterArr as $subhead)
                            {
                                $SubHeadArr[] = strtolower(trim($subhead['subhead']['SubHeadingDesc']));
                            }
                            
                            
                            $CheckDesignationExist = $this->SalaryUpload->query("SELECT `Type` from salary_master sm group by Type");
                            $flagDesiCheck = true;

                            foreach($CheckDesignationExist as $Desi)
                            {
                                if(!in_array(strtolower($Desi['sm']['Type']), $SubHeadArr))
                                {
                                    $flagDesiCheck = false;
                                    $NotMatchedSubHead[] = $Desi['sm']['Type'];
                                }
                            }
                            //////////////    Checking Business Case Ends From Here //////////////

                            ///////////////   Checking Business Case For CostCenter Starts From Here////////////////////

                            if($flagDesiCheck)
                            {
                                /*echo "SELECT em.HeadId,subhead.SubHeadingDesc,ep.ExpenseTypeName cost_center,SUM(ep.Amount) Amount FROM expense_master em 
                                INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
                                INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
                                WHERE em.BranchId='$BranchId' AND em.FinanceYear='$FinanceYear' AND em.FinanceMonth='$Month' AND em.HeadId='24' GROUP BY SubHeadingDesc,cost_center";
                                */
                                $ExpenseMasterCostArr = $this->ExpenseMaster->query("SELECT em.HeadId,subhead.SubHeadingDesc,ep.ExpenseTypeName cost_center,SUM(ep.Amount) Amount FROM expense_master em 
                                INNER JOIN expense_particular ep ON em.Id = ep.ExpenseId AND ep.ExpenseType='CostCenter'
                                INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
                                WHERE em.BranchId='$BranchId' AND em.FinanceYear='$FinanceYear' AND em.FinanceMonth='$Month' AND em.HeadId='24' GROUP BY SubHeadingDesc,cost_center");
                                //24 is a static id of Salary - ExpenseHead

                                foreach($ExpenseMasterCostArr as $cost)
                                {
                                    $ExpenseCostArr[] = strtolower(trim($cost['ep']['cost_center']));
                                    $ExpenseType[strtolower(trim($cost['subhead']['SubHeadingDesc']))] += $cost['0']['Amount'];
                                    $ExpenseCostAmtArr[strtolower(trim($cost['ep']['cost_center']))][strtolower(trim($cost['subhead']['SubHeadingDesc']))] +=$cost['0']['Amount'];   
                                }
                                $ExpenseCostArr = array_unique($ExpenseCostArr);
                                //$ExpenseType= array_unique($ExpenseType);

                                $CheckExpenseCostCenterExist = $this->SalaryUpload->query("SELECT CostCenter,`Type`,SUM(ActualCTC) Amount FROM `salary_master` sm  group by Type,CostCenter Order BY `Type`,CostCenter ");

                                $flagBusinessCaseMadeCheck = true;
                                $flagExpenseAmountNotMatchedCheck = true;

                                foreach($CheckExpenseCostCenterExist as $cost)
                                {
                                    $SalaryCostCenter[] = strtolower(trim($cost['sm']['CostCenter']));
                                    $SalaryType[] = strtolower(trim($cost['sm']['Type']));
                                    $SalaryTypeData[strtolower(trim($cost['sm']['Type']))] += $cost['0']['Amount'];
                                    $SalaryCostCenterData[strtolower(trim($cost['sm']['CostCenter']))][strtolower(trim($cost['sm']['Type']))] = strtolower(trim($cost['0']['Amount']));
                                }

                                //print_r($SalaryCostCenter); 

                                foreach($SalaryType as $type)
                                {
                                    if($SalaryTypeData[$type]>$ExpenseType[$type])
                                    {
                                        $flagBusinessCaseMadeCheck = false;
                                        $NotMatchedBusinessCase[$type][0] = $SalaryTypeData[$type];
                                        $NotMatchedBusinessCase[$type][1] = $ExpenseType[$type];
                                    }
                                }

                                foreach($SalaryCostCenter as $cost)
                                {
                                    foreach($SalaryType as $type)
                                    {
                                        if($ExpenseCostAmtArr[$cost][$type]<$SalaryCostCenterData[$cost][$type])
                                        {    
                                            $flagExpenseAmountNotMatchedCheck = false; 
                                            $NotMatchedAmount[$type][$cost] = $SalaryCostCenterData[$cost][$type];
                                        }

                                    }
                                }
                            }



                            ///////////////   Checking Business Case For CostCenter Ends From Here////////////////////



                          ////////////////   Printing Not Matched Fields Starts From Here       ///////////////////// 
                          if($flagCheck && $flagDesiCheck && $flagBusinessCaseMadeCheck && $flagExpenseAmountNotMatchedCheck)
                          {
                            $data = $this->SalaryUpload->query("SELECT * from salary_master sm"); 
                            $fileName = "Export Salary";
                            //header("Content-Type: application/vnd.ms-excel; name='excel'");
                            //header("Content-type: application/octet-stream");
                            //header("Content-Disposition: attachment; filename=$fileName.xls");
                            //header("Pragma: no-cache");
                            //header("Expires: 0");
                            $html =''; 
                            $html .= '<table border="1">';
                            $html .=    '<thead>';
                            $html .=        '<tr>';
                            $html .=            '<th>Details</th>';
                            $html .=            '<th>Sum of Mandays Paid</th>';
                            $html .=            '<th>Sum of Incentive</th>';
                            $html .=            '<th>Sum of Actual CTC</th>';
                            $html .=        '</tr>';
                            $html .=    '</thead>';
                            $html .=    '<tbody>';

                            $i=1; $Total=0;//print_r($ExpenseReport); exit;
                            $CostCenterArr = array();
                            foreach($data as $exp)
                            {

                                $BranchArr[] = $exp['sm']['Branch'];
                                $CostCenterArr[$exp['sm']['CostCenter']]['MainDays'] +=  $exp['sm']['MandaysPaid'];
                                $CostCenterArr[$exp['sm']['CostCenter']]['Incentive'] +=  $exp['sm']['Incentive'];
                                $CostCenterArr[$exp['sm']['CostCenter']]['ActualCTC'] +=  $exp['sm']['ActualCTC'];

                               $TypeArr[] =  $exp['sm']['Type'];
                               $DataModification[$exp['sm']['Branch']][$exp['sm']['CostCenter']][$exp['sm']['Type']]['MainDays'] += $exp['sm']['MandaysPaid'];
                               $DataModification[$exp['sm']['Branch']][$exp['sm']['CostCenter']][$exp['sm']['Type']]['Incentive'] += $exp['sm']['Incentive'];
                               $DataModification[$exp['sm']['Branch']][$exp['sm']['CostCenter']][$exp['sm']['Type']]['ActualCTC'] += $exp['sm']['ActualCTC'];
                               //print_r($DataModification); exit;
                            }

                            $BranchArr = array_unique($BranchArr);
                            //$CostCenterArr[] = array_unique($CostCenterArr);
                            $TypeArr = array_unique($TypeArr);


                            foreach($BranchArr as $br)
                            {
                                foreach($CostCenterArr as $cost=>$costValue)
                                {
                                    $html .= "<tr>";
                                    $html .= "<th>".$cost."</td>";
                                    $html .= "<th>".$costValue['MainDays']."</th>";
                                    $html .= "<th>".$costValue['Incentive']."</th>";
                                    $html .= "<th>".$costValue['ActualCTC']."</th>";
                                    $html .= "</tr>";

                                    foreach($TypeArr as $type)
                                    {
                                        if(empty($DataModification[$br][$cost][$type]['MainDays']) && empty($DataModification[$br][$cost][$type]['Incentive']) && empty($DataModification[$br][$cost][$type]['ActualCTC']))
                                        {

                                        }
                                        else    
                                        {    
                                            $html .= "<tr>";
                                            $html .= "<td>".$type."</td>";
                                            $html .= "<td>".$DataModification[$br][$cost][$type]['MainDays']."</td>";
                                            $html .= "<td>".$DataModification[$br][$cost][$type]['Incentive']."</td>";
                                            $html .= "<td>".$DataModification[$br][$cost][$type]['ActualCTC']."</td>";
                                            $html .= "</tr>";
                                            $MainDays +=$DataModification[$br][$cost][$type]['MainDays'];
                                            $Incentive +=$DataModification[$br][$cost][$type]['Incentive'];
                                            $ActualCTC +=$DataModification[$br][$cost][$type]['ActualCTC'];
                                        }
                                    }
                                }
                            }
                            $html .= "<tr>";
                            $html .= "<th>Grand Total</th>";
                            $html .= "<th>".$MainDays."</th>";
                            $html .= "<th>".$Incentive."</th>";
                            $html .= "<th>".$ActualCTC."</th>";
                            $html .= "</tr>";

                            $html .= '</tbody>';
                            $html .= '</table>'; 
                            $this->set('html',$html);

                            $this->Session->setFlash('File uploaded Successfully');
                          }
                          else
                          {
                                $html =''; 
                                if(!$flagCheck)
                                {
                                    $html .= '<table border="1">';
                                    $html .=    '<thead>';
                                    $html .=        '<tr>';
                                    $html .=            '<th>Cost Center Not Matched</th>';
                                    $html .=        '</tr>';
                                    $html .=    '</thead>';
                                    $html .=    '<tbody>';
                                    foreach($NotMatchedCostCenterList as $costCenterNot)
                                    {
                                        $html .=        '<tr>';
                                        $html .=        '<td>'.$costCenterNot.'</td>';
                                        $html .=        '</tr>';
                                    }
                                    $html .=    '</tbody>';
                                    $html .=    '</table>'; 
                                }

                                else if(!$flagDesiCheck)
                                {
                                    $html .= '<table border="1">';
                                    $html .=    '<thead>';
                                    $html .=        '<tr>';
                                    $html .=            '<th colspan="2">Business Case Not Found For '.$Month. ' '.$FinanceYear.' </th>';
                                    $html .=        '</tr>';
                                    $html .=        '<tr>';
                                    $html .=            '<th>Expense Head</th><th>Expense SubHead</th>';
                                    $html .=        '</tr>';
                                    $html .=    '</thead>';
                                    $html .=    '<tbody>';

                                    foreach($NotMatchedSubHead as $SubHead)
                                    {
                                        $html .=        '<tr>';
                                        $html .=        '<td>Salary</td><td>'.$SubHead.'</td>';
                                        $html .=        '</tr>';
                                    }
                                    $html .=    '</tbody>';
                                    $html .=    '</table>';  
                                }

                                else if(!$flagBusinessCaseMadeCheck)
                                {
                                    $html .= '<table border="1">';
                                    $html .=    '<thead>';
                                    $html .=        '<tr>';
                                    $html .=            '<th colspan="5">Business Case Amount is Less Than Salary Amount</th>';
                                    $html .=        '</tr>';
                                    $html .=        '<tr>';
                                    $html .=            '<th>Expense Head</th>';
                                    $html .=            '<th>Expense SubHead</th>';
                                    $html .=            '<th>Business Case Amount</th>';
                                    $html .=            '<th>Salary Amount</th>';
                                    $html .=            '<th>Difference Amount</th>';
                                    $html .=        '</tr>';
                                    $html .=    '</thead>';
                                    $html .=    '<tbody>';

                                    foreach($NotMatchedBusinessCase as $k=>$v)
                                    {
                                        $html .=        '<tr>';
                                        $html .=        '<td>Salary</td>';
                                        $html .=        '<td>'.$k.'</td>';
                                        $html .=        '<td>'.$v[0].'</td>';
                                        $html .=        '<td>'.$v[1].'</td>';
                                        $html .=        '<td>'.($v[0]-$v[1]).'</td>';
                                        $html .=        '</tr>';
                                    }
                                    $html .=    '</tbody>';
                                    $html .=    '</table>';  
                                }

                                else if(!$flagExpenseAmountNotMatchedCheck)
                                {
                                    $html .= '<table border="1">';
                                    $html .=    '<thead>';
                                    $html .=        '<tr>';
                                    $html .=            '<th colspan="3">Business Case Amount Not Matched</th>';
                                    $html .=        '</tr>';
                                    $html .=        '<tr>';
                                    $html .=            '<th>Type</th>';
                                    $html .=            '<th>CostCenter</th>';
                                    $html .=            '<th>Amount</th>';
                                    $html .=        '</tr>';
                                    $html .=    '</thead>';
                                    $html .=    '<tbody>';

                                    foreach($SalaryType as $type)
                                    {    
                                        foreach($NotMatchedAmount[$type] as $k=>$v)
                                        {
                                            $html .=        '<tr>';
                                            $html .=        '<td>'.$type.'</td>';
                                            $html .=        '<td>'.$k.'</td>';
                                            $html .=        '<td>'.$v.'</td>';
                                            $html .=        '</tr>';
                                        }

                                    }
                                    $html .=    '</tbody>';
                                    $html .=    '</table>';  
                                }
                                if(!$flagCheck1)
                                {
                                    $this->Session->setFlash('File Format Not Matched');
                                }
                                else if(!$flagCheck)
                                {
                                    $this->Session->setFlash('Cost Center Not Matched');
                                }
                                else if(!$flagDesiCheck)
                                {
                                    $this->Session->setFlash('Business Case Not Made');
                                }
                                else if(!$NotMatchedBusinessCase)
                                {
                                    $this->Session->setFlash('Business Case Amount Not Matched');
                                }

                                else if(!$flagCheck && $flagDesiCheck)
                                {
                                    $this->Session->setFlash('Cost Center Not Found and Business Case Not Made');
                                }
                                else if(!$flagCheck && !$flagDesiCheck && !$NotMatchedBusinessCase)
                                {
                                    $this->Session->setFlash('Cost Center Not Found and Business Case Not Made and Business Case Amount Not Matched');
                                }
                                $this->set('error','error');
                                $this->set('html',$html);
                          }
                        }
                        else
                        {
                            $this->Session->setFlash('Data Not Saved');
                        }
                    }
                    else if($flagCheck && !$flagValueCheck)
                    {
                        
                    }
                    else 
                    {
                        $this->Session->setFlash("File Format Not Matched");
                        $this->set('activity','upload');
                        $this->set('Month',$Month);
                        $this->set('FinanceYear',$FinanceYear);
                        $this->set('BranchId',$BranchId);
                    }
                }
                else
                {
                    $this->Session->setFlash('File Format not Valid');
                }
                }
                else
                {
                    $this->Session->setFlash('Records Allready Exist');
                }
                
            }
            else if($this->request->data['Submit']=='Save')
            {
                // GRN Making Works Starts From Here //////////////
                $Transaction = $this->ExpenseEntryMaster->getDataSource();
                $Transaction->begin(); //Transaction Starts From Here
                $GrnEntry = "SELECT sm.*,cm.id,com.comp_code,subhead.SubHeadingId FROM salary_master sm 
INNER JOIN cost_master cm ON sm.CostCenter = cm.cost_center
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON sm.Type = subhead.SubHeadingDesc
INNER JOIN company_master com ON cm.company_name = com.company_name";
                $GrnEntry = $this->SalaryUpload->query($GrnEntry);
                    $flagGRNCheck = true;
                foreach($GrnEntry as $grn)
                {
                    //Variable Creationg To Upload in expense_entry_master table and expense_entry_particular table
                    $SalaryExpenseEntryType = 'Salary';
                    $SalaryParticular = $grn['sm']['ChequeNumber'];
                    $SalaryCostCenterId = $grn['cm']['id'];
                    $SalaryAmount = $grn['sm']['ActualCTC'];
                    $SalaryCompCode = $grn['com']['comp_code'];
                    $SalaryBranchId     =   $grn['sm']['BranchId'];
                    $SalaryFinanceYear  =   $grn['sm']['FinanceYear'];
                    $SalaryFinanceMonth =   $grn['sm']['FinanceMonth'];
                    $SalaryHeadId = '24';
                    $SalarySubHeadId = $grn['subhead']['SubHeadingId'];
                    $SalaryDescription = 'Automated Salary Generation For EmpCode '.$grn['sm']['EmpCode'];
                    $SalaryApprovalDate = date('Y-m-d H:i:s');
                    //End Variable Creating

                    //Making Array To Upload in expense_entry_particular table
                    $ExpenseEntryParticular  = array();
                    $ExpenseEntryParticular['BranchId'] = $SalaryBranchId;
                    $ExpenseEntryParticular['ExpenseEntryType'] = $SalaryExpenseEntryType;
                    $ExpenseEntryParticular['Particular'] = $SalaryDescription;
                    $ExpenseEntryParticular['CostCenterId'] = $SalaryCostCenterId;
                    $ExpenseEntryParticular['Amount'] = $SalaryAmount;
                    $ExpenseEntryParticular['Rate'] = '0';
                    $ExpenseEntryParticular['Tax'] = '0';
                    $ExpenseEntryParticular['Total'] = $SalaryAmount;
                    $ExpenseEntryParticular['createdate'] = date('Y-m-d H:i:s');
                    $ExpenseEntryParticular['userid'] = $this->Session->read("userid");
                    //End Array To Upload in expense_entry_particular

                    //Making Array To Upload in expense_entry_master table
                    $ExpenseEntryMaster  = array();
                    $ExpenseEntryMaster['ExpenseEntryType'] = $SalaryExpenseEntryType;
                    $ExpenseEntryMaster['BranchId'] = $SalaryBranchId;
                    $ExpenseEntryMaster['FinanceYear'] = $SalaryFinanceYear;
                    $ExpenseEntryMaster['FinanceMonth'] = $SalaryFinanceMonth;
                    $ExpenseEntryMaster['HeadId'] = '24';
                    $ExpenseEntryMaster['SubHeadId'] = $SalarySubHeadId;
                    $ExpenseEntryMaster['Amount'] = $SalaryAmount;
                    $ExpenseEntryMaster['Description'] = $SalaryDescription;
                    $ExpenseEntryMaster['EntryStatus'] = 'Open';
                    $ExpenseEntryMaster['createdate'] = date('Y-m-d H:i:s');
                    $ExpenseEntryMaster['userid'] = $this->Session->read("userid");
                    $ExpenseEntryMaster['ApprovalDate'] = date('Y-m-d H:i:s');
                    $ExpenseEntryMaster['ApprovedBy'] = $this->Session->read("userid");
                    //End Array To Upload in expense_entry_master

                    if($this->ExpenseEntryMaster->saveAll(array('ExpenseEntryMaster'=>$ExpenseEntryMaster)))
                    { 
                        $MyLastIdArr[] = $this->ExpenseEntryMaster->getLastInsertID();;
                        $ExpenseEntryParticular['ExpenseEntry'] = $this->ExpenseEntryMaster->getLastInsertID();;
                        if($this->ExpenseEntryParticular->saveAll($ExpenseEntryParticular))
                        {

                        }
                        else
                        {
                            $flagGRNCheck = false;
                            $Transaction->rollback(); //rollback if any of records not inserted in table
                            $this->set('error',"error");
                            $this->Session->setFlash("GRN Particular Not Made. Please do it Again.");
                        }
                    }
                    else
                    {
                        $Transaction->rollback();//rollback if any of records not inserted in table
                        $this->set('error',"error");
                        $flagGRNCheck = false;
                        $this->Session->setFlash("GRN Salary Not Made. Please do it Again");
                    }
                }
                  
                if($flagGRNCheck)
                {
                    $cond = 1; //For running query again and again in array scenario
                
                foreach($MyLastIdArr as $LastId)
                {
                    $MaxGrnQry = "SELECT GrnNo,MAX(CONVERT(SUBSTRING_INDEX(GrnNo,'/',-1),UNSIGNED INTEGER)) MaxId FROM `expense_entry_master` WHERE $cond and FinanceYear='$SalaryFinanceYear' AND FinanceMonth='$SalaryFinanceMonth';";
                    $MaxGrnArr = $this->ExpenseEntryMaster->query($MaxGrnQry);
                    $MaxId = $MaxGrnArr['0']['0']['MaxId'];
                    if(empty($MaxId))
                    {
                        $MaxId = 1;
                    }
                    else
                    {
                        $MaxId += 1;
                    }

                    $MonthArr = array('Jan'=>'1','Feb'=>'2','Mar'=>'3','Apr'=>'4','May'=>'5','Jun'=>'6','Jul'=>'7','Aug'=>'8','Sep'=>'9','Oct'=>'10','Nov'=>'11','Dec'=>'12');
                    $SalaryMonthNo = $MonthArr[$SalaryFinanceMonth];

                    $SalaryFinanceYearArr = explode('-',$SalaryFinanceYear);
                    $SalaryFinanceYearNew = $SalaryFinanceYearArr[1];

                    if($SalaryMonthNo>3)
                    {
                        $SalaryFinanceYearNew -=1;
                    }

                    $NewGrnNo = "'$SalaryCompCode/$SalaryMonthNo/$SalaryFinanceYearNew/$MaxId'";
                    if(!$this->ExpenseEntryMaster->updateAll(array('GrnNo'=>$NewGrnNo),array('Id'=>$LastId)))
                    {
                        
                        $Transaction->rollback(); //rollback when GRN No Not Made
                        $this->set('error',"error");
                        $this->Session->setFlash("GRN No. Not Generated. Please do it Again");
                        $flagGRNCheck = false;
                    }

                    $cond ++;
                }
                    
//                $CheckSalaryGrnQry = "SELECT SUM(Amount)Total FROM expense_entry_master WHERE ExpenseEntryType='Salary' and FinanceYear='$SalaryFinanceYear' and FinanceMonth='$SalaryFinanceMonth';";
//                $CheckSalaryGrnPartQry = "SELECT SUM(Amount)Total FROM expense_entry_master WHERE ExpenseEntryType='Salary' and FinanceYear='$SalaryFinanceYear' and FinanceMonth='$SalaryFinanceMonth' ;";
//                $CheckSalaryFileUploadTotal = "SELECT SUM(ActualCTC)Total FROM salary_master;";
//
//                $TotalGrnSalary = $this->ExpenseEntryMaster->query($CheckSalaryGrnQry);
//                $TotalGrnSalaryPart = $this->ExpenseEntryParticular->query($CheckSalaryGrnPartQry);
//                $TotalSalaryFileUpload = $this->SalaryUpload->query($CheckSalaryFileUploadTotal);
//                    
//                if($TotalGrnSalary['0']['0']['Total']!=$TotalGrnSalaryPart['0']['0']['Total'] && $flagGRNCheck)
//                {
//                    $Transaction->rollback(); //GRN Not Made As Per Salary Uploaded
//                    $this->set('error',"error");
//                    $flagGRNCheck = false;
//                    $this->Session->setFlash("GRN Not Made As Per Salary Upload");
//                    
//                }
//                
//                if($TotalGrnSalary['0']['0']['Total']!=$TotalSalaryFileUpload['0']['0']['Total'] && $flagGRNCheck)
//                {
//                    $Transaction->rollback(); //GRN Not Made As Per Salary Uploaded
//                    $this->set('error',"error");
//                    $flagGRNCheck = false;
//                    $this->Session->setFlash("GRN Not Made As Per Salary Upload");
//                    
//                }
                
                $MoveData = $this->SalaryUpload->find('all');
                
                foreach($MoveData as $MD)
                {
                    $NewMoveData[] = $MD['SalaryUpload'];
                }
                //print_r($flagGRNCheck); exit;
                if($flagGRNCheck && $this->SalaryUploadMaster->saveAll($NewMoveData))
                {
                    $Transaction->commit();
                   $this->TallyInvoiceVoucherExport->query("Truncate table salary_master");
                    $this->set('activity','upload');
                     $this->Session->setFlash('Record Has been Saved');
                }
                else if($flagGRNCheck)
                {
                    $Transaction->rollback(); //Data Not Moved From salary_master to salary_master_upload
                    $flagGRNCheck = false;
                    $this->set('error',"error");
                     $this->Session->setFlash("File Records Not Moved Successfully. Please Try Again");
                }
                }
                    
//                    $this->SalaryUpload->query("INSERT INTO salary_master_upload(EmpCode,
//EmpName,
//CostCenter,
//Designation,
//`Type`,
//Branch,
//Basic,
//HRA,
//Bonus,
//`Conv`,
//Portfolio,
//MedicalAllowance,
//LTA,
//SpecialAllowance,
//OtherAllowance,
//PLI1,
//Gross,
//WorkingDays,
//CTCOffered,
//CurrentCTC,
//EarnedDays,
//MandaysPaid,
//ExtraDay,
//`Leave`,
//Basic1,
//HRA1,
//Bonus1,
//Conv2,
//Portfolio1,
//SpecialAllowance1,
//OtherAllowance1,
//MedicalAllowance1,
//Gross1,
//ESIElig,
//PFELig,
//ESIC,
//EPF,
//IncomeTax,
//AdvTaken,
//AdvPaid,
//LoanTaken,
//LoanDed,
//Incentive,
//ExtraDayIncentive,
//Arrear,
//PLI,
//NetSalary,
//ESICCompany,
//EPFCompany,
//AdminChrg,
//CTC,
//ActualCTC,
//SHSH,
//MobileDedcution,
//ShortCollection,
//AssetRecovery,
//Insurance,
//ProTaxDeduction,
//LeaveDeduction,
//OtherDeduction,
//OtherDeductionRemarks,
//TotalDeduction,
//SalDate,
//EPFNo,
//ESICNo,
//ChequeNumber,
//ChequeDate,
//PrintDate,
//LeftStatus,
//TaxTotalGross,
//TaxSection10,
//TaxBalance,
//TaxUnderHd,
//DeductionUnder24,
//TaxGrossTotal,
//TaxAggofChapter6,
//TotalIncome,
//TaxOnTotalIncome,
//EduCess,
//TaxPayEduCess,
//TaxDeductedTillPreviousMonth,
//BalanceTax,
//SalaryPaymentMode,
//createdate,
//FinanceYear,
//FinanceMonth,
//BranchId
//)
//SELECT EmpCode,
//EmpName,
//CostCenter,
//Designation,
//`Type`,
//Branch,
//Basic,
//HRA,
//Bonus,
//`Conv`,
//Portfolio,
//MedicalAllowance,
//LTA,
//SpecialAllowance,
//OtherAllowance,
//PLI1,
//Gross,
//WorkingDays,
//CTCOffered,
//CurrentCTC,
//EarnedDays,
//MandaysPaid,
//ExtraDay,
//`Leave`,
//Basic1,
//HRA1,
//Bonus1,
//Conv2,
//Portfolio1,
//SpecialAllowance1,
//OtherAllowance1,
//MedicalAllowance1,
//Gross1,
//ESIElig,
//PFELig,
//ESIC,
//EPF,
//IncomeTax,
//AdvTaken,
//AdvPaid,
//LoanTaken,
//LoanDed,
//Incentive,
//ExtraDayIncentive,
//Arrear,
//PLI,
//NetSalary,
//ESICCompany,
//EPFCompany,
//AdminChrg,
//CTC,
//ActualCTC,
//SHSH,
//MobileDedcution,
//ShortCollection,
//AssetRecovery,
//Insurance,
//ProTaxDeduction,
//LeaveDeduction,
//OtherDeduction,
//OtherDeductionRemarks,
//TotalDeduction,
//SalDate,
//EPFNo,
//ESICNo,
//ChequeNumber,
//ChequeDate,
//PrintDate,
//LeftStatus,
//TaxTotalGross,
//TaxSection10,
//TaxBalance,
//TaxUnderHd,
//DeductionUnder24,
//TaxGrossTotal,
//TaxAggofChapter6,
//TotalIncome,
//TaxOnTotalIncome,
//EduCess,
//TaxPayEduCess,
//TaxDeductedTillPreviousMonth,
//BalanceTax,
//SalaryPaymentMode,
//createdate,
//FinanceYear,
//FinanceMonth,
//BranchId FROM salary_master
//");
                 
      
                }
                else if($this->request->data['Submit']=='Reject')
                {
                    $this->TallyInvoiceVoucherExport->query("Truncate table salary_master");
                    $this->Session->setFlash('Files Records Has been Removed');
                }
                
        }
    } 
    
    
    public function grn_dashboard()
    {
        $this->layout = "home";
        $all = array();

        $role = $this->Session->read('role');
        if($role=='admin')
            {
                $condition=array('1'=>1);
                $all = array('All'=>'All');
            }


        $this->set('branch_master',array('All'=>'All')+$this->Addbranch->find('list',array('fields'=>array('Id','branch_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));

        
    }
    
    public function export_grn_dashboard()
    {
    $this->layout='ajax';
      if($this->request->is('POST'))
      {$result = $this->request->data;}
      else
      {
          $result = $this->params->query;
      }
      
      $this->set('type',$result['type']);
      
    $Branch = $result['Branch'];
    $Year =$result['FinanceYear'];
    $Month =$result['FinanceMonth'];
   
    
        //print_r($this->request->data); exit;
        //$qry = "Where 1=1";
        $Expense = $this->request->data['Expense'];
        
        if($Branch!='All')
        {
            $qryApr .= " and eep.BranchId='".$Branch."'";
            $qryRej .= " and BranchId='".$Branch."'";
            
        }
        
        if($year!='All')
        {
            $qryApr .= " and eem.FinanceYear='".$Year."'";
            $qryRej .= " and FinanceYear='".$Year."'";
        }
        
        if($month!='All')
        {
            $qryApr .= " and eem.FinanceMonth='".$Month."'";
            $qryRej .= " and FinanceMonth='".$Month."'";
        }
        
        
        
        
       $GrnApprove= $this->ExpenseMaster->query("SELECT branch_name,COUNT(1) cnt from (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master eem INNER JOIN expense_entry_particular eep ON eem.Id = eep.expenseEntry
INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE eem.ExpenseEntryType='Vendor' $qryApr
GROUP BY bm.id,eem.GrnNo)tab group by branch_name");
       
       $GrnReject= $this->ExpenseMaster->query("SELECT branch_name,COUNT(1) cnt FROM (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master_approve eem INNER JOIN expense_entry_particular_approve eep ON eem.Id = eep.expenseEntry
        INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE Reject=0 and eem.ExpenseEntryType='Vendor' $qryApr
        GROUP BY bm.id,eem.id)tab GROUP BY branch_name");
       
       $GrnPending= $this->ExpenseMaster->query("SELECT branch_name,COUNT(1) cnt FROM (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master_approve eem INNER JOIN expense_entry_particular_approve eep ON eem.Id = eep.expenseEntry
        INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE Reject=1 and eem.ExpenseEntryType='Vendor'  $qryApr
        GROUP BY bm.id,eem.id)tab GROUP BY branch_name");
       
       foreach($GrnApprove as $post)
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnApproveArr[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       foreach($GrnReject as $post)
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnRejectArr[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       foreach($GrnPending as $post)
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnPendingArr[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       
       
       //$this->set('data',$data);
     
       $GrnApproveImprest= $this->ExpenseMaster->query("select branch_name,COUNT(1) cnt from (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master eem INNER JOIN expense_entry_particular eep ON eem.Id = eep.expenseEntry
INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE eem.ExpenseEntryType='Imprest' $qryApr
GROUP BY bm.id,eem.GrnNo)tab group by branch_name");
       
       $GrnRejectImprest= $this->ExpenseMaster->query("SELECT branch_name,COUNT(1) cnt FROM (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master_approve eem INNER JOIN expense_entry_particular_approve eep ON eem.Id = eep.expenseEntry
        INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE Reject=0 and eem.ExpenseEntryType='Imprest' $qryApr
        GROUP BY bm.id,eem.id)tab GROUP BY branch_name");
       
       $GrnPendingImprest= $this->ExpenseMaster->query("SELECT branch_name,COUNT(1) cnt FROM (SELECT branch_name,COUNT(1) cnt FROM expense_entry_master_approve eem INNER JOIN expense_entry_particular_approve eep ON eem.Id = eep.expenseEntry
        INNER JOIN branch_master bm ON eep.BranchId = bm.id WHERE Reject=1 and eem.ExpenseEntryType='Imprest'  $qryApr
        GROUP BY bm.id,eem.id)tab GROUP BY branch_name");
       
       foreach($GrnApproveImprest as $post) 
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnApproveArrImprest[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       foreach($GrnRejectImprest as $post)
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnRejectArrImprest[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       foreach($GrnPendingImprest as $post)
       {
           $branchArr[] = $post['tab']['branch_name'];
           $GrnPendingArrImprest[$post['tab']['branch_name']] = $post['0']['cnt'];
       }
       
       //$branchArrImprest = array_unique($branchArrImprest);
       
//       $i=0;
//       foreach($branchArrImprest as $branch)
//       {
//           $dataImprest[$i]['Branch'] = $branch;
//           $dataImprest[$i]['Total'] = $GrnApproveArrImprest[$branch]+$GrnRejectArrImprest[$branch]+$GrnPendingArrImprest[$branch];
//           $dataImprest[$i]['Approve'] = $GrnApproveArrImprest[$branch];
//           $dataImprest[$i]['Reject'] = $GrnRejectArrImprest[$branch];
//           $dataImprest[$i++]['Pending'] = $GrnPendingArrImprest[$branch];
//       }
       $branchArr = array_unique($branchArr);
       
       $i=0;
       foreach($branchArr as $branch)
       {
           $data[$i]['Branch'] = $branch;
           $data[$i]['Total']['Vendor'] = $GrnApproveArr[$branch]+$GrnRejectArr[$branch]+$GrnPendingArr[$branch];
           $data[$i]['Approve']['Vendor'] = $GrnApproveArr[$branch];
           $data[$i]['Reject']['Vendor'] = $GrnRejectArr[$branch];
           $data[$i]['Pending']['Vendor'] = $GrnPendingArr[$branch];
           $data[$i]['Total']['Imprest'] = $GrnApproveArrImprest[$branch]+$GrnRejectArrImprest[$branch]+$GrnPendingArrImprest[$branch];
           $data[$i]['Approve']['Imprest'] = $GrnApproveArrImprest[$branch];
           $data[$i]['Reject']['Imprest'] = $GrnRejectArrImprest[$branch];
           $data[$i++]['Pending']['Imprest'] = $GrnPendingArrImprest[$branch];
       }
       //print_r($data); exit;
       $this->set('data',$data);      
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
            $qry .= " and em.FinanceYear='".$year."'";
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


        
        
       $ExpenseReport= $this->ExpenseMaster->query("SELECT IF(bm.branch_state=vm.state,'state','central')GSTType,tscgd.GSTEnable,vm.TDSEnabled,vm.TDS,vm.TDSSection,
vm.TallyHead,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,cm.Branch,vm.TDSTallyHead,
cm.Branch,em.FinanceYear,em.FinanceMonth,bm.state,bm.tally_code,bm.tally_branch,PanNo,GSTNo FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
WHERE  vm.TDSEnabled $qry
GROUP BY cm.branch   ORDER BY cm.branch"); 
       $this->set('ExpenseReport',$ExpenseReport); 
    
       $userid = $this->Session->read('userid');
       $date = date('Y-m-d H:i:s');
       
//    foreach($ExpenseReport as $post)
//    {
//      $this->ExpenseEntryMaster->updateAll(array('DownloadStatus'=>'0','DownloadBy'=>"'$userid'",'DownloadDate'=>"'$date'"),array('Id'=>$post['em']['Id']));
//    }
     
}

public function salary_vch_report()
  {
        $this->layout='home';
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));
        $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'order'=>array('branch_name')));
        $this->set('branch_master',$branchMaster);
    
        if($this->request->is('POST'))
        {
            //print_r($this->request->data); exit;
           $Request = $this->request->data['GrnReports'];
           $Branch = $Request['branch'];
           $FinanceYear = $Request['FinanceYear'];
           $FinanceMonth = $Request['FinanceMonth'];
            
            $HeadArr = $this->SalaryHead->find('all');
            $i=0;
            $data = array();
            $ColumnMapArr  = $this->SalaryHead->query("Select * from salary_columns");
            foreach($ColumnMapArr as $clm)
            {
                $ColumnMapArrNew[$clm['salary_columns']['ExcelCellName']] = trim($clm['salary_columns']['ColumnMap']);
            }
            //$ColumnMap = $ColumnMapArr['0']['salary_columns']['ColumnMap']; 
            //print_r($ColumnMapArrNew); exit;
            foreach($HeadArr as $head)
            {
                $columnMappedArr = array();
                $data[$i]['Head'] = $head['SalaryHead']['SalaryHead'];
                $MappedString =  $head['SalaryHead']['SalaryColumn']; 
                
                foreach($ColumnMapArrNew as $ColumnMap=>$value)
                {
                    $pos = strpos($MappedString, "'$ColumnMap'");
                    //echo $MappedString;
                   // echo "<br/>";
                   // echo "'$ColumnMap'";
                    //echo "<br/>";
                    if($pos!==false)
                    {
                        
                        $TotalSelect = "Select sum($value)Tot From salary_master_upload where BranchId='$Branch' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth'"; 
                        $Total =  $this->SalaryHead->query($TotalSelect);
                        
                        $MappedString = str_replace("'$ColumnMap'",$Total['0']['0']['Tot'] , $MappedString); 
                      //echo "<br/>";  
                    }
                    
                }
                
                // $MappedString.'<br/>'; 
                $data[$i++]['Value'] = eval('return '.$MappedString.';'); 
            }
           
            $fileName = "Export_Salary_Voucher_Report";
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$fileName.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo '<table border="1">';
            echo    '<thead>';
            echo        '<tr>';
            echo            '<th>Dr.</th>';
            echo            '<th>Amount</th>';
            echo        '</tr>';
            echo    '</thead>';
            echo    '<tbody>';

            foreach($data as $v)
            {
                echo "<tr><td>".$v['Head'].'</td>';
                echo "<td>".$v['Value'].'</td></tr>';
            }
            echo    '</tbody></table>';
            
            
            exit;
        }
  }

  function get_dash()
  {
      //print_r($this->request->data);
      //exit;
      $FinanceYear = $this->request->data['FinanceYear'];
      $FinanceMonth = $this->request->data['Month'];
      
      
      $branchArr = $this->Addbranch->find("list",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name'),'order'=>array('branch_name'=>'asc')));
        $uploadSalCheck = $this->SalaryUpload->query("SELECT branchId,FinanceMonth FROM salary_master_upload sm
 WHERE FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' GROUP BY BranchId");
        foreach($uploadSalCheck as $smc)
        {
            $SalArr[] = $smc['sm']['branchId'];
        }
 
        echo '<table border="2" style="text-align:center">';
        echo '<tr><th colspan="2" style="text-align:center"><b>'.$FinanceMonth.'-'.$FinanceYear.'</b></th></tr>';
        echo            '<tr>
                        <th style="text-align:center">Branch</th>
                        <th style="text-align:center">Status</th>
                    </tr>';
                    
        foreach($branchArr as $k=>$v)
        {
            echo "<tr>";
                echo "<td>".$v."</td>";
                echo "<td>";
            if(in_array($k,$SalArr))
            {
                echo '<font color="green"><b>Yes</b></font>';
            }
            else
            {
                echo '<font color="red">No</font>';
            }
            echo "</td>";
        }
        
        echo "</table>";
        
       exit;
  }
  
  public function file_report()
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
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2018-19'))));
    $this->set('branch_master',$branchMaster);
    $this->set('head',array_merge(array('All'=>'All'),$this->Tbl_bgt_expenseheadingmaster->find('list',array('conditions'=>array('EntryBy'=>""),'fields'=>array('HeadingId','HeadingDesc')))));
    
    if($this->request->is('POST'))
    {
        //print_r($this->request->data); exit;
        $qry = "Where (UniqueId!='' && UniqueId is not null) ";
        $Expense = $this->request->data['GrnReports'];
        
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
        if($Expense['UniqueId'])
        {
            $qry .= " and em.UniqueId like '%".$Expense['UniqueId']."'";
        }
        //print_r($qry); exit;
        
       $this->set('ExpenseReport',$this->ExpenseMaster->query("SELECT em.GrnNo,em.ExpenseEntryType,cm.branch,cm.cost_center,em.BranchId,em.Vendor,em.FinanceYear,em.FinanceMonth,HeadingDesc,SubHeadingDesc,eep.Amount,
em.UniqueId,em.Amount
FROM expense_entry_master em 
INNER JOIN (select * from expense_entry_particular group by ExpenseEntryType) eep ON em.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId 
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id $qry order by em.GrnNo"));
       
    }
    
     
}
public function pnl_branch_wise_report()
  {
        $this->layout='home';
        $role = $this->Session->read('role');
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        if($role=='admin')
        {
            $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
            $branchMaster = array('All'=>'All') + $branchMaster2;
        }
        else
        {
            $branchMaster[$this->Session->read("branch_name")] = $this->Session->read("branch_name");
        }
        
        $this->set('branch_master',$branchMaster);
  }
  
 public function get_pnl_report_branch_wise() 
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
         $qu1="and branch_name='$branch'";
         $qu4="and cm.OPBranch='$branch'";
         $qu2="and cm.branch='$branch'";
         $qu3=" pm.branch_name='$branch'";
         $qu5 = " and Branch='$branch'";
         $qu6 = " and OPBranch='$branch'";
         $this->set('branch_wise','branchwise');
      }
      $qry = " 1=1 ";
      $qry1 = " 1=1 ";
      $qry3 = " ";
      $qry4 = " ";
      $this->set('branch_master1',$this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"BranchPnl='A' $qu1")));
      $this->set('branch_master2',$this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"BranchPnl='B' $qu1")));
      $this->set('branch_master3',$this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"BranchPnl='C' $qu1")));
      $this->set('orderD',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'D'),'order'=>array('OrderPriority')))) ;
      $this->set('orderI',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'I'),'order'=>array('OrderPriority')))) ;
      
      
      
      /// Getting Provision Branch Wise as UnProcessed Provision in Gross Salary in p&l report From table provision_master
      $provisionArr = $this->Provision->query("SELECT OPBranch,SUM(pm.provision) provision,SUM(pm.out_source_amt) out_source FROM provision_master pm
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
 WHERE pm.invoiceType1='Revenue' and pm.finance_year='{$Expense['FinanceYear']}' AND pm.`month` = '$month' $qu4 GROUP BY cm.OPBranch;");
      foreach($provisionArr as $pro)
      {
        $provision_master[strtoupper($pro['cm']['OPBranch'])] = round($pro['0']['provision'],2)+round($pro['0']['out_source'],2);
      }
      
     
      
      $billingUnProcArrRsc = $this->Provision->query("SELECT OPBranch,SUM(pm.outsource_amt) billing_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource = cm.cost_center
INNER JOIN `provision_master` pm1
ON pm.FinanceYear = pm1.finance_year AND pm.FinanceMonth=pm1.month AND pm.Cost_Center=pm1.cost_center AND pm1.provision_balance!=0
 WHERE  pm.FinanceYear='{$Expense['FinanceYear']}' AND pm.`FinanceMonth` = '$month' $qu4 GROUP BY cm.OPBranch;");
      foreach($billingUnProcArrRsc as $pro)
      {
        $provision_master[strtoupper($pro['cm']['OPBranch'])] += $pro['0']['billing_amt'];
      }
      
//      $billingProcArr = $this->Provision->query("SELECT OPBranch,SUM(pm.outsource_amt) outsource_amt FROM `provision_particulars` pm
//INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
// WHERE pm.FinanceYear='{$Expense['FinanceYear']}' AND pm.`FinanceMonth` = '$month' $qu4 GROUP BY cm.OPBranch;");
//      foreach($billingProcArr as $pro)
//      {
//        $billingProcMaster[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['outsource_amt'];
//      }
      
      //print_r($provision_master); exit;
      $this->set('provision',$provision_master);
      
      /// Getting Sell Invoice as Processed Amount in Gros Salary in p&l report from table table_invoice 
      $InvoiceArr = $this->InitialInvoice->query("SELECT OPBranch,SUM(total) total FROM tbl_invoice ti 
 INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
 WHERE ti.invoiceType='Revenue' and ti.finance_year='{$Expense['FinanceYear']}' AND ti.`month`='$month' AND ti.`status`=0 $qu4 GROUP BY cm.OPBranch");
      foreach($InvoiceArr as $pro)
      {
          $inv_master[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['total'];
      }
      //print_r($inv_master); exit;
      $this->set('inv_master',$inv_master);
      
      
      // Revenue Reimbursement Grn Where HeadId=4 and SubHeadId=59 as Processed Revenue Reimbursement from table expense_master
      
//      echo "SELECT bm.branch_name,SUM(eep.Amount) Total FROM expense_master eem 
//INNER JOIN expense_particular eep
//ON eem.id = eep.ExpenseId
//INNER JOIN branch_master bm ON eep.BranchId = bm.id
// WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' AND eem.HeadId='4' AND eem.SubHeadId='59' $qu
//GROUP BY bm.id"; exit;
      
      
      
      // Revenue Reimbursement Grn Where HeadId=4 and SubHeadId=59 as Processed Revenue Reimbursement  from table expense_entry_master
      $Reimbursement = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(eep.Amount) Total FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.id = eep.ExpenseEntry
INNER JOIN cost_master cm ON eep.CostCenterId=cm.id
 WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' AND eem.HeadId='4' AND eem.SubHeadId='59' $qu4
GROUP BY cm.OPBranch");
    //print_r($Reimbursement); exit;
      foreach($Reimbursement as $pro)
      {
          $Reimbur_master[strtoupper($pro['cm']['OPBranch'])] += $pro['0']['Total'];
      }
      //print_r($Reimbur_master); exit;
      $ReimbursementUn = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(eep.Amount) Total,eem.EntryStatus FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN cost_master cm ON eep.ExpenseTypeName = cm.cost_center 
 WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth = '{$Expense['FinanceMonth']}' 
 AND eem.HeadId='4' AND eem.SubHeadId='59' $qu4
GROUP BY cm.OPBranch");
     //print_r($ReimbursementUn); exit;   
      foreach($ReimbursementUn as $pro)
      {
          //echo $Reimbur_master[strtoupper($pro['cm']['OPBranch'])];
          if($pro['eem']['EntryStatus']=='0')
          {
              $Reimbur_master_Up[strtoupper($pro['cm']['OPBranch'])] = $Reimbur_master[strtoupper($pro['cm']['OPBranch'])];
          }
          else
          {
            $Reimbur_master_Up[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['Total'];
          }
          $Reimbur_master_Up[strtoupper($pro['cm']['OPBranch'])]['1'] = $pro['eem']['EntryStatus'];
      }
      
      //print_r($Reimbur_master_Up); exit;
      $this->set('Reimbur_master_up',$Reimbur_master_Up);
      $this->set('Reimbur_master',$Reimbur_master);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(NetSalary-Incentive-ExtraDayIncentive-Arrear) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $NetSalary[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      
      $this->set('NetSalary',$NetSalary);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(Incentive+ExtraDayIncentive+Arrear) Incentive FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $Incentive[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['Incentive'];
      }
      $this->set('Incentive',$Incentive);
      
       
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(EPF+EPFCompany+AdminChrg) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $EPF[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('EPF',$EPF);
     
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ESIC+ESICCompany) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $ESIC[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('ESIC',$ESIC);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ProTaxDeduction) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $PT[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('PT',$PT);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(IncomeTax) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $TDS[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('TDS',$TDS);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ShortCollection) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $ShortColl[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('ShortColl',$ShortColl);
      //print_r($ShortColl); exit;
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(AdvPaid+LoanDed) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $Loan[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      
      $this->set('Loan',$Loan);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(SHSH) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
      foreach($SalaryUploadMaster as $pro)
      {
          $SHSH[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('SHSH',$SHSH);
      
      
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ActualCTC) NetSalary FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='{$Expense['FinanceMonth']}' $qu2
 GROUP BY cm.OPBranch");
    
      foreach($SalaryUploadMaster as $pro)
      {
          $ActualCTC[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      
      
      $SalaryBusiMaster = $this->ExpenseMaster->query("SELECT head.HeadingDesc,cm.OPBranch,eem.EntryStatus,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='{$Expense['FinanceMonth']}'  AND eem.HeadId='24'  $qu2
GROUP BY cm.OPBranch");
     
      foreach($SalaryBusiMaster as $pro)
      {
          if($pro['eem']['EntryStatus']=='0')
          {
              $ActualCTCBusi[strtoupper($pro['cm']['OPBranch'])] = $ActualCTC[strtoupper($pro['cm']['OPBranch'])];
          }
          else
          {
            $ActualCTCBusi[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['Amount'];
          }
      }
      
      $this->set('ActualCTC',$ActualCTC);
      $this->set('ActualCTCBusi',$ActualCTCBusi);
      
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ToAmount) NetSalary FROM `cost_center_cost_transfer_particular` prop
 INNER JOIN cost_master cm ON prop.ToCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='{$Expense['FinanceMonth']}' $qu4
  GROUP BY cm.OPBranch");
      
      foreach($SalaryUploadMaster as $pro)
      {
          $Adjust[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('Adjust',$Adjust);
      
      $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(FromAmount) NetSalary FROM `cost_center_cost_transfer_master` prop
 INNER JOIN cost_master cm ON prop.FromCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='{$Expense['FinanceMonth']}' $qu4
  GROUP BY cm.OPBranch");
      
      foreach($SalaryUploadMaster as $pro)
      {
          $Adjust2[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
      }
      $this->set('Adjust2',$Adjust2);
      
        
       
       $DirectArr = $this->ExpenseEntryMaster->query("SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' and eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'D' and eem.HeadId!='24' and eem.SubHeadId!='59' $qu4
GROUP BY eep.BranchId,subhead.SubHeadingDesc"); 
       
       foreach($DirectArr as $Dir)
       {
           $Direct[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Dir['0']['Amount'];
           $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
           $HeadDir[] = $Dir['head']['HeadingDesc'];
           $BranchNArr[] = strtoupper($Dir['cm']['OPBranch']);
       }
      
       
       $UnDirectArr = $this->ExpenseMaster->query("SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'D' AND eem.HeadId!='24' AND eem.SubHeadId!='59' $qu4
GROUP BY eep.BranchId,subhead.SubHeadingDesc"); 
       
        

       foreach($UnDirectArr as $Dir)
       {
           if($Dir['eem']['EntryStatus']=='0')
           {
                $UnDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Direct[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])];  
           }
           else
           {
                $UnDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Dir['0']['Amount'];
           }
           $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
           $HeadDir[] = $Dir['head']['HeadingDesc'];
           $BranchNArr[] = strtoupper($Dir['cm']['OPBranch']);
       }
       
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
       
       $this->set('Direct',$Direct);
       $this->set('UnDirect',$UnDirect);
       $this->set('SubHeadDir',  array_unique($HeadDir));
       
       
      
       
       
       $InDirectArr = $this->ExpenseEntryMaster->query("SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' and eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'I' and eem.HeadId!=24 and eem.SubHeadId!='59' $qu4
GROUP BY eep.BranchId,head.HeadingDesc,subhead.SubHeadingDesc");  
       
       foreach($InDirectArr as $InDir)
       {
           $InDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDir['0']['Amount'];
           $SubHeadInDir[] = $InDir['subhead']['SubHeadingDesc'];
           $HeadInDir[] = $InDir['head']['HeadingDesc'];
           $BranchInNArr[] = strtoupper($InDir['cm']['OPBranch']);
       }
        $UnInDirectArr = $this->ExpenseMaster->query("SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='{$Expense['FinanceMonth']}' AND head.Cost = 'I' AND eem.HeadId!='24' AND eem.SubHeadId!='59' $qu4
GROUP BY eep.BranchId,head.HeadingDesc,subhead.SubHeadingDesc"); 
       
    

       foreach($UnInDirectArr as $InDir)
       {
           if($InDir['eem']['EntryStatus']=='0')
           {
                $UnInDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])];
           }
           else
           {
                $UnInDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDir['0']['Amount'];
           }
           $SubHeadInDir[] = $InDir['subhead']['SubHeadingDesc'];
           $HeadInDir[] = $InDir['head']['HeadingDesc'];
           $BranchInNArr[] = strtoupper($InDir['cm']['OPBranch']);
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
       
       $PnlMaster = $this->PnlMaster->find('all',array('conditions'=>array('ForPnlType'=>'Branch')));
       $PnlDataBranch = array(); $PnlDataProcess = array();
       
       foreach($PnlMaster as $pnl)
       {
           if($pnl['PnlMaster']['EntryType']=='Branch')
           {
                $PnlBranchSave = $this->PnlBranchSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='{$pnl['PnlMaster']['PnlMasterId']}' $qu5"));
                
                foreach($PnlBranchSave as $branchRecords)
                {
                    $PnlDataBranch[$pnl['PnlMaster']['Description']][$branchRecords['PnlBranchSave']['Branch']]['proc'] += round($branchRecords['PnlBranchSave']['PnlAmount']);
                    $PnlDataBranch[$pnl['PnlMaster']['Description']][$branchRecords['PnlBranchSave']['Branch']]['unproc'] += round($branchRecords['PnlBranchSave']['PnlAmount']);
                }
                $PnlBranchHead[] = $pnl['PnlMaster']['Description'];
           }
           else if($pnl['PnlMaster']['EntryType']=='Process')
           {
                $PnlProcessSave = $this->PnlProcessSave->find('all',array('conditions'=>"FinanceYear='{$Expense['FinanceYear']}' and FinanceMonth='{$Expense['FinanceMonth']}' and pnlMasterId='{$pnl['PnlMaster']['PnlMasterId']}' $qu6"));
                
                
                foreach($PnlProcessSave as $processRecords)
                {
                    $PnlDataProcess[$pnl['PnlMaster']['Description']][$processRecords['PnlProcessSave']['OPBranch']]['proc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
                    $PnlDataProcess[$pnl['PnlMaster']['Description']][$processRecords['PnlProcessSave']['OPBranch']]['unproc'] += round($processRecords['PnlProcessSave']['PnlAmount']);
                }
                $PnlProcessHead[] = $pnl['PnlMaster']['Description'];
           }
       }
       $this->set('PnlBranchHead',$PnlBranchHead);
       $this->set('PnlDataBranch',$PnlDataBranch);
       $this->set('PnlProcessHead',$PnlProcessHead);
       $this->set('PnlDataProcess',$PnlDataProcess);
       
 }
 
 public function grn_gst_report()
{
    $this->layout = "home";
    $all = array();
    
    $role = $this->Session->read('role');
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
    
        
    $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','comp_code'))));
    $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    
    
    
     
}

public function export_grn_gst_report()
{
    $this->layout='ajax';
      if($this->request->is('POST'))
      {$result = $this->request->data;}
      else
      {
          $result = $this->params->query;
      }
      
      $this->set('type',$result['type']);
      $this->set('TaxType',$result['TaxType']);
      
        $comp_Name = $result['company_name'];
        $year =$result['FinanceYear'];
        $month =$result['FinanceMonth'];
        $TaxType =$result['TaxType'];
        
        
        
        
        if($comp_Name!='All')
        {
            $qry .= " and em.CompId='".$comp_Name."'";
        }
        
        if($year!='All')
        {
            $qry .= " and em.FinanceYear='".$year."'";
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
        if($TaxType=='All')
        {
            
        }
        else if($TaxType=='Taxable')
        {
            $qry .= " and eep.Rate!='' and eep.Rate is not null";
        }
        else if($TaxType=='Non-Taxable')
        {
            $qry .= " and (eep.Rate='' ||  eep.Rate is null)";
        }
        //print_r($qry);exit;


        
        
       $ExpenseReport= $this->ExpenseMaster->query("SELECT em.Id,SUBSTRING_INDEX(GrnNo,'/',-1) VchNo,DATE_FORMAT(LAST_DAY(em.approvalDate),'%d-%b-%y') Dates,head.HeadingDesc,
subhead.SubHeadingDesc,IF(vm.as_bill_to=1,'state',IF(bm.branch_state=vm.state,'state','central'))GSTType,tscgd.GSTEnable,em.bill_no,vm.TDSEnabled,vm.TDS,vm.TDSSection,vm.TDSChange,vm.TDSNew,
vm.TallyHead,SUM(eep.Amount)Amount,eep.Rate,SUM(eep.Tax) Tax,SUM(eep.Total) Total,''DebitCredit,cm.Branch CostCategory,em.ApprovalDate,bm.branch_name,
vm.TDSTallyHead,subhead.SubHeadTDSEnabled,subhead.SubHeadTds,td.description,td.TDS,
cm.Branch CostCenter,em.FinanceYear,em.FinanceMonth,eep.Particular NarrationEach,em.Description Narration,IF(vm.as_bill_to=1,vm.state,bm.state)state,em.GrnNo FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry  
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
INNER JOIN tbl_vendormaster vm ON vm.Id = em.vendor
INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` GROUP BY VendorId,BranchId) tscgd ON vm.Id = tscgd.VendorId AND  bm.id = tscgd.BranchId
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON em.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON em.SubHeadId = subhead.SubHeadingId
LEFT JOIN tds_master td ON subhead.SubHeadTdsSection = td.Id 
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


public function pnl_summary_report()
  {
        $this->layout='home';
        $role = $this->Session->read('role');
        $this->set('company_name',$this->Addcompany->find('list',array('fields'=>array('Id','company_name'))));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        if($role=='admin')
        {
            $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));
            $branchMaster = array('All'=>'All') + $branchMaster2;
        }
        else
        {
            $branchMaster[$this->Session->read("branch_name")] = $this->Session->read("branch_name");
        }
        
        $this->set('branch_master',$branchMaster);
  }


 public function get_pnl_report_month_wise_summary() 
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
    
    $FromMonth = $Expense['FromMonth'];
    $ToMonth = $Expense['ToMonth'];
    
    $dt=  explode('-',$Expense['FinanceYear']); //explode array
    //print_r($dt); exit;
    $branch_master = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'))); //branch master 
    
    foreach($branch_master as $brid=>$br)
    {
        $branch_master1[$brid] = strtoupper($br);
    }
    
    $branch_master = $branch_master1;
    
    $orderD=$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'D'),'order'=>array('OrderPriority'))) ;
    //print_r($branch_master); exit;
      $orderI=$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('OrderPriority','HeadingDesc'),'conditions'=>array('EntryBy'=>"",'Cost'=>'I'),'order'=>array('OrderPriority'))) ;
    $MonthArr = array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');  //get all month array to execute and fetch data
    $MontArrSwap = array_flip($MonthArr);  //flip array to find the data in series
    //print_r($MontArrSwap); exit;
    if($MontArrSwap[$FromMonth]>$MontArrSwap[$ToMonth])
    {
        $FromMonthNew = $ToMonth;
        $ToMonthNew = $FromMonth;
    }
    else
    {
        $FromMonthNew = $FromMonth;
        $ToMonthNew = $ToMonth;
    }
    //print_r($ToMonthNew); exit;
    $startFetchData = false; //false to check from where you have to start
    foreach($MonthArr as $FromMonth)
    {
        if($FromMonth==$FromMonthNew)
        {
            $startFetchData = true; //true when found where to start
        }
        
        
        
        if($startFetchData)
        {
            if(in_array($FromMonth,array('Jan','Feb','Mar')))
            {
              $month=$FromMonth.'-'.$dt[1]; 
            }
            else
            {
                $month=$FromMonth.'-'.($dt[1]-1); 
            }
           //print_r($month); exit; 
             /// Getting Provision Branch Wise as UnProcessed Provision in Gross Salary in p&l report From table provision_master
                $provisionArr = $this->Provision->query("SELECT OPBranch,SUM(pm.provision) provision,SUM(pm.out_source_amt) out_source FROM provision_master pm
          INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
           WHERE pm.invoiceType1='Revenue' and pm.finance_year='{$Expense['FinanceYear']}' AND pm.`month` = '$month'");
           
           //print_r($provisionArr); exit; 
           
            foreach($provisionArr as $pro)
            {
              $SummaryData[strtoupper($month)]['Revenue']['Unprocessed'] = round($pro['0']['provision'])+round($pro['0']['provision']);
            }
            //print_r($SummaryData); exit; 
            
            /// Getting Sell Invoice as Processed Amount in Gros Salary in p&l report from table table_invoice 
            $InvoiceArr = $this->InitialInvoice->query("SELECT OPBranch,SUM(total) total FROM tbl_invoice ti 
       INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
       WHERE ti.invoiceType='Revenue' and  ti.finance_year='{$Expense['FinanceYear']}' AND ti.`month`='$month' AND ti.`status`=0 ");
       
            //print_r($InvoiceArr); exit; 
            
            foreach($InvoiceArr as $pro)
            {
                $SummaryData[strtoupper($month)]['Revenue']['Processed'] = $pro['0']['total'];
            }
            //print_r($SummaryData); exit; 
            
            
            
            $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ActualCTC) ActualCTC FROM salary_master_upload smu 
INNER JOIN cost_master cm ON smu.CostCenter = cm.cost_center
WHERE FinanceYear='{$Expense['FinanceYear']}' AND FinanceMonth='$FromMonth' 
 GROUP BY cm.OPBranch");
        $ActualCTC = array();
        foreach($SalaryUploadMaster as $pro)
        {
            $ActualCTC[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['ActualCTC'];
        }
        //print_r($ActualCTC); exit;
        $SalaryBusiMaster = $this->ExpenseMaster->query("SELECT head.HeadingDesc,cm.OPBranch,eem.EntryStatus,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='$FromMonth'  AND eem.HeadId='24'  
GROUP BY cm.OPBranch");
     
        $ActualCTCBusi = array();
          foreach($SalaryBusiMaster as $pro)
          {
              if($pro['eem']['EntryStatus']=='0')
              {
                  $ActualCTCBusi[strtoupper($pro['cm']['OPBranch'])] = $ActualCTC[strtoupper($pro['cm']['OPBranch'])];

              }
              else
              {
                $ActualCTCBusi[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['Amount'];
              }
          }
        //print_r($ActualCTCBusi); exit;
       $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(ToAmount) AdjustTo FROM `cost_center_cost_transfer_particular` prop
 INNER JOIN cost_master cm ON prop.ToCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='$FromMonth' 
  GROUP BY cm.OPBranch");
      
        $Adjust = array();
        foreach($SalaryUploadMaster as $pro)
        {
            $Adjust[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['AdjustTo'];
        } 
        //print_r($Adjust); exit;
        $SalaryUploadMaster = $this->ExpenseEntryMaster->query("SELECT cm.OPBranch,SUM(FromAmount) NetSalary FROM `cost_center_cost_transfer_master` prop
 INNER JOIN cost_master cm ON prop.FromCostCenter = cm.cost_center
  WHERE prop.FinanceYear='{$Expense['FinanceYear']}' AND prop.FinanceMonth='$FromMonth' 
  GROUP BY cm.OPBranch");
      
        $Adjust2 = array();
        foreach($SalaryUploadMaster as $pro)
        {
            $Adjust2[strtoupper($pro['cm']['OPBranch'])] = $pro['0']['NetSalary'];
        }
        //print_r($Adjust2); exit;
        $TotCTC = 0;
        $TotActualCTCBus = 0;
        foreach($branch_master as $cost)
        {
            $TotCTC += round($ActualCTCBusi[strtoupper($cost)]-$ActualCTC[$cost]);
            $TotActualCTCBus +=round($ActualCTC[strtoupper($cost)]+$Adjust[$cost]-$Adjust2[$cost]);
        }
        //print_r($Adjust2); exit;
        $SummaryData[strtoupper($month)]['Salary']['Unprocessed'] =round($TotCTC);
        $SummaryData[strtoupper($month)]['Salary']['Processed'] =  round($TotActualCTCBus);
        //print_r($SummaryData); exit;
        
        
        $DirectArr = $this->ExpenseEntryMaster->query("SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' and eem.FinanceMonth='$FromMonth' AND head.Cost = 'D' and eem.HeadId!='24' and eem.SubHeadId!='59' 
GROUP BY eep.BranchId,subhead.SubHeadingDesc"); 
       
        $Direct = array();
        $SubHeadDir = array();
        $HeadDir = array();
        $BranchNArr = array();
        
        foreach($DirectArr as $Dir)
        {
            $Direct[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Dir['0']['Amount'];
            $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
            $HeadDir[] = $Dir['head']['HeadingDesc'];
            $BranchNArr[] = strtoupper($Dir['cm']['OPBranch']);
        }
      
        //print_r($Direct); exit;
        
        $UnDirectArr = $this->ExpenseMaster->query("SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='$FromMonth' AND head.Cost = 'D' AND eem.HeadId!='24' AND eem.SubHeadId!='59' 
GROUP BY eep.BranchId,subhead.SubHeadingDesc"); 
       
        
        $UnDirect = array();
        
        foreach($UnDirectArr as $Dir)
        {
            if($Dir['eem']['EntryStatus']=='0')
            {
                 $UnDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Direct[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])];  
            }
            else
            {
                 $UnDirect[$Dir['subhead']['SubHeadingDesc']][$Dir['head']['HeadingDesc']][strtoupper($Dir['cm']['OPBranch'])] = $Dir['0']['Amount'];
            }
            $SubHeadDir[] = $Dir['subhead']['SubHeadingDesc'];
            $HeadDir[] = $Dir['head']['HeadingDesc'];
            $BranchNArr[] = strtoupper($Dir['cm']['OPBranch']);
        }
        
        $HeadDir = array_unique($HeadDir);
        $SubHeadDir = array_unique($SubHeadDir);
        $BranchNArr = array_unique($BranchNArr);
       //print_r($BranchNArr); exit;
       
        
       
        $DataA = array();
        $DataB = array();
        
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
       //print_r($DataA); exit;
       
       $Direct = $DataA;
       $UnDirect = $DataB;
       $TotDirUnProc = 0;
       $TotDirProc = 0;
        sort($SubHeadDir);
        //print_r($orderD); exit;     
        //$orderD = array('Communication & Connectivity');
        //$branch_master = array('HYDERABAD'); 
        foreach($orderD as $Subhead)
        {
            if(!empty($branch_master)) 
            { 
                foreach($branch_master as $cost)
                {    
                    //echo round($Direct[$Subhead][$cost]);
                    //echo "<br/>";
                    $TotDirUnProc += round($UnDirect[$Subhead][$cost]);
                    $TotDirProc += round($Direct[$Subhead][$cost]);
                }                
            }  
        }
       //print_r($TotDirProc); exit;  
        $SummaryData[strtoupper($month)]['DirectExpense']['Unprocessed'] = round($TotDirUnProc-$TotDirProc);
        $SummaryData[strtoupper($month)]['DirectExpense']['Processed'] = round($TotDirProc);
        //print_r($SummaryData); exit;  
       $InDirectArr = $this->ExpenseEntryMaster->query("SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' and eem.FinanceMonth='$FromMonth' AND head.Cost = 'I' and eem.HeadId!=24 and eem.SubHeadId!='59' 
GROUP BY eep.BranchId,head.HeadingDesc,subhead.SubHeadingDesc");  
       
    $InDirect = array();
    $SubHeadInDir = array();
    $HeadInDir = array();
    $BranchInNArr = array();
    
       foreach($InDirectArr as $InDir)
       {
           $InDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDir['0']['Amount'];
           $SubHeadInDir[] = $InDir['subhead']['SubHeadingDesc'];
           $HeadInDir[] = $InDir['head']['HeadingDesc'];
           $BranchInNArr[] = strtoupper($InDir['cm']['OPBranch']);
       }
        //print_r($InDirect); exit;
     $UnInDirectArr = $this->ExpenseMaster->query("SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.OPBranch,SUM(eep.Amount) Amount FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='{$Expense['FinanceYear']}' AND eem.FinanceMonth='$FromMonth' AND head.Cost = 'I' AND eem.HeadId!='24' AND eem.SubHeadId!='59' 
GROUP BY eep.BranchId,head.HeadingDesc,subhead.SubHeadingDesc"); 
       
        $UnInDirect = array();
        
    
       foreach($UnInDirectArr as $InDir)
       {
           if($InDir['eem']['EntryStatus']=='0')
           {
                $UnInDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])];
           }
           else
           {
                $UnInDirect[$InDir['subhead']['SubHeadingDesc']][$InDir['head']['HeadingDesc']][strtoupper($InDir['cm']['OPBranch'])] = $InDir['0']['Amount'];
           }
           $SubHeadInDir[] = $InDir['subhead']['SubHeadingDesc'];
           $HeadInDir[] = $InDir['head']['HeadingDesc'];
           $BranchInNArr[] = strtoupper($InDir['cm']['OPBranch']);
       }
       //print_r($UnInDirect); exit;
       $HeadInDir = array_unique($HeadInDir);
       $SubHeadInDir = array_unique($SubHeadInDir);
       $BranchInNArr = array_unique($BranchInNArr);
       
       $DataC = array();
       $DataD = array();
       
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
       //print_r($UnInDirect); exit;
       //$SubHeadInDir = array_unique($SubHeadInDir);
        //sort($SubHeadInDir);
        
        $TotInDirUnProc = 0;
        $TotInDirProc = 0;
        
        foreach($orderI as $Subhead)
        {
            foreach($branch_master as $cost)
            {
                $TotInDirUnProc += round($UnInDirect[$Subhead][$cost]);
                $TotInDirProc += round($InDirect[$Subhead][$cost]);
            }
            $SummaryData[strtoupper($month)]['InDirectExpense']['Unprocessed'] = $TotInDirUnProc-$TotInDirProc;
            $SummaryData[strtoupper($month)]['InDirectExpense']['Processed'] = $TotInDirProc;
        }
       
        //print_r($SummaryData); exit;
       
        
          $SummaryMonth[$FromMonth] = strtoupper($month);   
        }
       if($FromMonth==$ToMonthNew)
        {
            $startFetchData = false; //false when finish.
        } 
    }
    
    //print_r($SummaryMonth); exit;
    $this->set('SummaryMonth',$SummaryMonth);  
    $this->set('SummaryData',$SummaryData); 
    
       
 }
 
}