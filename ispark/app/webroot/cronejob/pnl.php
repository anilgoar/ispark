<?php

$con = mysql_connect("localhost",'root','vicidialnow');
$db = mysql_select_db("db_bill", $con);
$month="1";
$branch = "HEAD OFFICE";
$company = "Mas Callnet India Pvt Ltd";
$finance_year = "2017";

$query="SELECT id,cost_center FROM cost_master WHERE branch='$branch' AND company_name='$company'";

$exe = mysql_query($query);

while($data = mysql_fetch_assoc($exe))
{
    //print_r($data);
   $cost_center = $data['cost_center'];
   $cost_centerArr[] = $data['cost_center'];
   $RevenueMasterSrNo = mysql_fetch_assoc(mysql_query("SELECT SrNo FROM tbl_RevenueMaster WHERE RevenueMonth = '$month' AND RevenueYear = '$finance_year' AND Branch = '$branch'"));
   $ExpenseMasterSrNo = mysql_query("SELECT SrNo FROM tbl_ExpenseMaster WHERE tbl_ExpenseMaster.Month = '$month' AND tbl_ExpenseMaster.Year = '$finance_year' AND Branch = '$branch'");
   
 $direct_process = $salary_process = mysql_fetch_assoc(mysql_query("SELECT CASE WHEN SUM(IFNULL(ED.ApprovedAmount,0)) > 0 THEN SUM(IFNULL(ED.ApprovedAmount,0)) ELSE SUM(IFNULL(ED.Amount,0))END
 `ProcessTotalSalary`
 FROM
  tbl_expensedetails ED JOIN tbl_expensemaster EM ON ED.MasterSrNo=EM.SrNo JOIN tbl_businesscasemaster BCM              
  ON ED.ExpenseHeadId=BCM.ExpenseHead AND ED.ExpenseSubHeadId=BCM.ExpenseSubHead AND EM.Month=BCM.Month AND EM.Year=BCM.Year AND EM.Branch=BCM.Branch              
 WHERE  EM.Branch = '$branch' AND   EM.Month = '$month' AND EM.Year = '$finance_year' AND ED.CostCenter='$cost_center';"));   

$array['SALARY'][$cost_center]['Process'] = $direct_process['ProcessTotalSalary'];
 
   $direct_unprocess= $salary_unprocess = mysql_fetch_assoc(mysql_query("SELECT IFNULL(CASE WHEN SUM(IFNULL(ED.ApprovedAmount,0)) > 0 THEN SUM(IFNULL(ED.ApprovedAmount,0)) ELSE SUM(IFNULL(ED.Amount,0))END,0)
 UnProcessProcessTotalSalary 
 FROM               
  tbl_expensedetails ED JOIN tbl_expensemaster EM ON ED.MasterSrNo=EM.SrNo JOIN tbl_businesscaseMaster BCM              
  ON ED.ExpenseHeadId=BCM.ExpenseHead AND ED.ExpenseSubHeadId=BCM.ExpenseSubHead AND EM.Month=BCM.Month AND EM.Year=BCM.Year AND EM.Branch=BCM.Branch              
 WHERE               
  ED.ExpenseHeadId='000000' AND EM.Branch = '$branch' AND   EM.Month = '$month' AND EM.Year = '$finance_year' AND BCM.Status<>'Closed' AND ED.CostCenter='$cost_center';"));
 
   
   $array['DirectCost'][$cost_center]['UnProcess'] = $direct_process['UnProcessProcessTotalSalary'];
   
$headerExtra= mysql_query("SELECT HeadingId,  UPPER(HeadingDesc) FROM tbl_bgt_expenseheadingmaster WHERE HeadingId<>000000");
    while($headerData = mysql_fetch_assoc($headerExtra))
    {
        //$array[$headerExtra][$cost_center] = '';
        $headerMaster[$headerData['HeadingDesc']] = $headerData['HeadingId'];
    }

   
$Total_Revenue =$processed = mysql_fetch_assoc(mysql_query("SELECT 
  CASE WHEN SUM(IFNULL(ED.ApprovedAmount,0)) > 0 THEN SUM(IFNULL(ED.ApprovedAmount,0)) ELSE SUM(IFNULL(ED.Amount,0))END Amount
  FROM tbl_expensedetails ED JOIN tbl_expensemaster EM ON ED.MasterSrNo=EM.SrNo JOIN tbl_businesscasemaster BCM                    
  ON ED.ExpenseHeadId=BCM.ExpenseHead AND ED.ExpenseSubHeadId=BCM.ExpenseSubHead AND EM.Month=BCM.Month AND EM.Year=BCM.Year AND             
  EM.Branch=BCM.Branch WHERE  BCM.Status='Closed' AND ED.CostCenter='$cost_center' and ED.Month = '$month' AND ED.Year = '$finance_year'  GROUP BY             
  BCM.ExpenseHead,BCM.Branch, BCM.Month, BCM.Year  "));
  
    
$Total_Salary = $unprocessed = mysql_fetch_assoc(mysql_query("Select Case When Sum(IFNULL(ED.ApprovedAmount,0)) > 0 then Sum(IFNULL(ED.ApprovedAmount,0)) else Sum(IFNULL(ED.Amount,0))end Amount
  from tbl_expensedetails ED join tbl_expensemaster EM on ED.MasterSrNo=EM.SrNo join tbl_businesscaseMaster BCM                    
  on ED.ExpenseHeadId=BCM.ExpenseHead and ED.ExpenseSubHeadId=BCM.ExpenseSubHead and EM.Month=BCM.Month and EM.Year=BCM.Year and             
  EM.Branch=BCM.Branch WHERE BCM.Level3ApprovalBy is not null and  BCM.Status<>'Closed' and ED.CostCenter='$cost_center' group by             
  BCM.ExpenseHead,BCM.Branch, BCM.Month, BCM.Year"));  

foreach($headerMaster as $Expense=>$head)
    {
        //$array[$Expense][$cost_center]['head'] = $head;
        $array[$Expense][$cost_center]['Process'] = $processed['Amount'];
        $array[$Expense][$cost_center]['UnProcess'] = $unprocessed['Amount'];
        $array['Total'][$cost_center]['Total'] = ($direct_process['ProcessTotalSalary']*100)/1;
        $array['Contribution'][$cost_center]['Total'] = 0-$direct_process['ProcessTotalSalary'];
        
        if(!empty($head)){
        $TotalExpense += $processed['Amount'];
        //$array['Total Indirect Cost'][$cost_center]['Total'] = $processed['Amount'];
        }
        
    }

    foreach($array as $k=>$v)
    {
        $v['Total Indirect Cost'] = $TotalExpense;
    }


}

//print_r($array); exit;

echo "<table>";
    echo "<tr><th>ExpenseHead</th><th>Particulars</th>";
    
        foreach($cost_centerArr as $cost)
        {
            echo "<th>".$cost."_Process</th>";
            echo "<th>".$cost."_UnProcess</th>";
            echo "<th>".$cost."_Total</th>";
        }
    echo "</tr>";
//echo "</table>";

foreach($array as $k=>$v)
{   echo "<tr><th>asdf</th>";
    echo "<th>".$k."</th>";
   foreach($cost_centerArr as $cost)
   {
    echo "<th>".$array[$k][$cost]['Process']."</th>";
    echo "<th>".$array[$k][$cost]['UnProcess']."</th>";
    echo "<th>".$array[$k][$cost]['Total']."</th>";
   }
   echo "</tr>";
}

echo "</table>";