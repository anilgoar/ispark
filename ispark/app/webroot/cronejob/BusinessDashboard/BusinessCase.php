<?php
//include('report-send.php');
$con = mysql_connect("localhost",'root','Mas@1234');
$db = mysql_select_db("db_bill", $con);

$branch = $_GET['Branch'];

if($branch=='All')
{
    $branch_name = " and cm.Branch='$branch'";
}

 $DirectArrQry = "SELECT subhead.SubHeadingDesc,head.HeadingDesc,cm.Branch,SUM(eep.Amount) Amount,eem.FinanceMonth FROM expense_entry_master eem 
INNER JOIN expense_entry_particular eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
WHERE eem.FinanceYear='2018-19' $branch_name
GROUP BY eep.BranchId,eem.SubHeadId,eem.FinanceYear,eem.FinanceMonth";  

$DirectArr = mysql_query($DirectArrQry);

$branch = array();

  while($row =  mysql_fetch_assoc($DirectArr))
  {
    $Direct[$row['FinanceMonth']][$row['SubHeadingDesc']][$row['HeadingDesc']][$row['Branch']] = $row['Amount'];
    $BranchNArr[] = strtoupper($row['Branch']);
    $branch[] = $row['Branch'];
    $month[] = $row['FinanceMonth'];
    
  }
    
     //print_r($Direct); exit; 
       
       $UnDirectArrQry = "SELECT eem.EntryStatus,subhead.SubHeadingDesc,head.HeadingDesc,cm.Branch,SUM(eep.Amount) Amount,eem.FinanceMonth FROM expense_master eem 
INNER JOIN expense_particular eep ON eem.Id = eep.ExpenseId AND eep.ExpenseType='CostCenter'
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.SubHeadId = subhead.SubHeadingId
INNER JOIN cost_master cm ON eep.ExpenseTypeId = cm.id
WHERE eem.FinanceYear='2018-19' $branch_name
GROUP BY eep.BranchId,eem.SubHeadId,eem.FinanceYear,eem.FinanceMonth"; 
       $UnDirectArr = mysql_query($UnDirectArrQry);
       
       while($row =  mysql_fetch_assoc($UnDirectArr ))
       {
           if($row['EntryStatus']=='0')
           {
                $UnDirect[$row['Branch']][$row['FinanceMonth']] = [$row['FinanceMonth']][$row['SubHeadingDesc']][$row['HeadingDesc']][$row['Branch']];  
           }
           else
           {
                $UnDirect[$row['Branch']][$row['FinanceMonth']] = $row['Amount'];
           }
           $branch[] = $row['branch_name'];
           $month[] = $row['month'];
       }
       


       $Data = $UnDirect;


    $branchArr = array_unique($branch);
    $monthArr = array_unique($month);
    
    $html =  "<table border='2'>";
    $html .= "<tr>";
        $html .= '<th colspan="'.(count($monthArr)+2).'">DC & IDC  Business Case Dashboard Unprocess Amount</th>';
    $html .= "</tr>";    
    $html .= "<tr>";
        $html .= '<th>Branch</th>';
        foreach($monthArr as $month)
        {
            $html .= '<th>'.$month.'</th>';
        }
        $html .= '<th>Total</th>';
    $html .= "</tr>";    
    
    foreach($branchArr as $branch)
    {
        $html .= "<tr>";
            $html .= '<th>'.$branch.'</th>'; $TotalArr = 0;
            foreach($monthArr as $month)
            {
                $html .= '<th>'.$Data[$branch][$month].'</th>';
                $GTotal[$month] += $Data[$branch][$month];
                $TotalArr += $Data[$branch][$month];
            }
            $html .= '<th>'.$TotalArr.'</th>';
        $html .= "</tr>";    
    }
    
    $html .= '<th>Grand Total</th>'; $TotalArr = 0;
    foreach($monthArr as $month)
    {
        $html .= '<th>'.$GTotal[$month].'</th>';
        $TotalArr += $GTotal[$month];
    }
    $html .= '<th>'.$TotalArr.'</th>';
  echo  $html .= "</table>";  exit;
    

    
//    
//    $select2 ="SELECT * FROM `business_dashboard_mail` WHERE Branch in('".implode("','",$branchArr)."')";
//    $excute2 = mysql_query($select2);
//    while($Data = mysql_fetch_assoc($excute2))
//    {
//        $To = $Data['ReportTo'];
//        $Tos = explode(",",$To);
//        $AddTo = array();
//        $TosFlag = true;
//        if(is_array($Tos) && !empty($Tos))
//        {
//            foreach($Tos as $to)
//            {
//                if(!empty($to))
//                {
//                    if($TosFlag)
//                    {
//                        $To = $to;$TosFlag=false;
//                    }
//                    else
//                    {
//                        $AddTo[] = $to;
//                    }
//                }
//
//            }
//        }
//    
//	$CC = explode(",",$Data['ReportCC']);
//        $BCC = explode(",",$Data['ReportBCC']);
//    }
//    
//    if(!empty($AddCc))
//    {
//        $emaildata['AddCc'] =  $AddCc;
//    }
//    
//    if(!empty($AddTo))
//    {
//        $emaildata['AddTo'] =  $AddTo;
//    }
//    if(!empty($BCC))
//    {
//        $emaildata['AddBcc'] =  $BCC;
//    }
//    
//    if($count1 || $count2)
//    {
//        //$cc[] = "deepak.kashyap@teammas.in"; 
//    }
//    if(!empty($to))
//    {
//        $emaildata['ReceiverEmail']['Email'] = implode(",",$to);
//        $emaildata['SenderEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
//        $emaildata['ReplyEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
//        $emaildata['AddCc']['Email'] = implode(",",$cc);
//    }
//    
//    
//    $emaildata['Subject'] = "Revenue UnProcessed"; 
//    
//    $emaildata['EmailText'] =$html;
//    
//    try
//    {
//        $done = send_email( $emaildata);
//        //echo " asdfjlsdjf lsdjf lskdfj lksdjf sldfk";
//        //print_r($emaildata);
//    }
//    catch (Exception $e)
//    {
//        $error = $e.printStackTrace();
//    }
    

