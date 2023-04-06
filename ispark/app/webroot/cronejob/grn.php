<?php
include('report-send.php');
$con = mysql_connect("localhost",'root','vicidialnow');
$db = mysql_select_db("db_bill", $con);


$select = "SELECT ti.branch_name, ti.bill_no,IF(DATEDIFF(CURDATE(),DATE(ti.po_createdate))>2 &&DATEDIFF(CURDATE(),DATE(ti.po_createdate))%3=0,1,0) `count1`,
IF(
DATEDIFF(CURDATE(),DATE(po_createdate))>3 && 
ROUND(DATEDIFF(CURDATE(),DATE(po_createdate))%3,0)=1 && 
DAYNAME(po_createdate) = 'monday',1,0) `count2` 
 FROM tbl_invoice ti INNER JOIN  cost_master cm ON ti.cost_center =cm.cost_center
 LEFT JOIN bill_pay_particulars bpp ON 
 CONCAT(bpp.company_name,bpp.branch_name,bpp.financial_year,bpp.bill_no) = 
 CONCAT(cm.company_name,ti.branch_name,ti.finance_year,SUBSTRING_INDEX(ti.bill_no,'/',1))
   WHERE CURDATE()>DATE(po_createdate) AND DAYNAME(CURDATE()) !='sunday' AND 
   IF(cm.grn='Yes',IF(ti.approve_grn !='Yes',TRUE,FALSE),FALSE) and bpp.bill_no is null";
$execute = mysql_query($select);

while($row=  mysql_fetch_assoc($execute))
{
    $branch = $row['branch_name'];
    $bill_no = $row['bill_no'];
    $count1 = $row[0]['count1'];
    $count2 = $row[0]['count2'];
    
    $select2 ="SELECT role,email FROM tbl_user WHERE work_type='account' AND branch_name='DELHI' AND role IN ('Process Manager','Branch Manager','Regional Manager')";
    $excute2 = mysql_query($select2);
    while($row = mysql_fetch_assoc($excute2))
    {
        $cc[] = array("nixonsethi@teammas.in","naresh.chauhan@teammas.in");
        if($row['role'] !='Regional Manager')
        {
            if(!empty($role['email']))
            $to[] =$role['email'];
        }
        else
        {
            if(!empty($role['email']))
            $cc[] =$role['email'];
        }
    }
    
    if($count1 || $count2)
    {
        //$cc[] = "deepak.kashyap@teammas.in"; 
    }
    if(!empty($to))
    {
        $emaildata['ReceiverEmail']['Email'] = implode(",",$to);
        $emaildata['SenderEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
        $emaildata['ReplyEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
        $emaildata['AddCc']['Email'] = implode(",",$cc);
    }
    
    
    $emaildata['Subject'] = "Grn Notification"; 
    
    $emaildata['EmailText'] ="<table><tr><td style=\"padding-left:12px;\">Please Submit the GRN For Invoice No $bill_no </td></tr></table>";
    
    try
    {
        $done = send_email( $emaildata);
        //echo " asdfjlsdjf lsdjf lskdfj lksdjf sldfk";
        //print_r($emaildata);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
    }
     
}
