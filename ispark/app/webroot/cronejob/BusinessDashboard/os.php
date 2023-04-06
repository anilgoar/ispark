<?php
include('report-send.php');
$con = mysql_connect("localhost",'root','Mas@1234');
$db = mysql_select_db("db_bill", $con);

$branch = $_GET['Branch'];

if($branch!='All')
{
    $branch_name = " and t1.branch_name='$branch'"; 
}

$select = "SELECT t1.id,t1.createdate, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client,
t1.bill_no,t1.branch_name,t1.finance_year, t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst,bpp.net_amount,bpp.tds_ded,
t1.grnd,t1.invoiceDescription,t1.invoiceDate,t1.grn, 
IF(bpp.status = 'part payment','part payment',IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', IF(t2.grn='Yes' AND 
(t1.approve_grn = '' OR t1.approve_po IS NULL),'GRN Pending','submitted'))) `bill_status`,

IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', '') `po_status`,
IF(agr.branch_name IS NULL,'Lapsed', 'Inforce') `agr_status`,

IF(t2.grn='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'GRN Pending', '') `grn_status`,
IF(t1.bill_no IS NULL || t1.bill_no IS NULL,'', t1.grnd) `bill_submit`,
IF(t1.bill_no IS NULL || t1.bill_no IS NULL,'Pending', '') `bill_pending`



FROM tbl_invoice t1 
INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid  
LEFT JOIN agreements agr ON t2.id IN ('') AND t1.month  BETWEEN agr.periodTo AND agr.periodFrom

LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates 
FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year 
AND t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name 
WHERE t1.status = '0' AND (bpp.status ='part payment' || bpp.status IS NULL)   
$branch_name  ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)";
$execute = mysql_query($select);
$branch = array();
$html =  "<table border='2'>";
    $html .= "<tr>";
        $html .= '<th colspan="8">NOIDA-ISPARK Dashboard O/S /Agrement/PO/GRN/Bill Status</th>';
    $html .= "</tr>";    
    $html .= "<tr>";
        $html .= '<th>Branches</th>';
        $html .= '<th>Month</th>';
        $html .= '<th>os/Amt</th>';
        $html .= '<th>Agreement Status</th>';
        $html .= '<th>PO Pending</th>';
        $html .= '<th>GRN Pending</th>';
        $html .= '<th>Bill Submitted</th>';
        $html .= '<th>Bills Pending For Submission</th>';
        $html .= "</tr>";
        
        

while($row =  mysql_fetch_assoc($execute))
{
    //$branch[] = $row['branch_name'];
    //$month[] = $row['month'];
    //$Data[$row['branch_name']][$row['month']] = $row['unprocess'];
    $html .='<tr>';
    $html .='<th>'.$row['cost_center'].'</th>';
    $html .='<th>'.$row['month'].'</th>';
    $html .='<th>'.$row['grnd'].'</th>';
    $html .='<th>'.$row['agri_status'].'</th>';
    $html .='<th>'.$row['po_status'].'</th>';
    $html .='<th>'.$row['grn_status'].'</th>';
    $html .='<th>'.$row['bill_submit'].'</th>';
    $html .='<th>'.$row['bill_pending'].'</th>';
    $html .='</tr>';
    
}    
   echo $html .= "</table>"; exit;
    

    
    
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
    

