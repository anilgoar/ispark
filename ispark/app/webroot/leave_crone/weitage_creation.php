<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


// --------------- weitage creation --------------------
$qry ="SELECT * FROM `pli_weitage` WHERE mail_status='1'";
$DataArr = mysqli_query($con,$qry);

$processedCombinations = array();
if(!empty($DataArr))
{

    while($data=mysqli_fetch_assoc($DataArr))
    {

        $year = $data['year'];
        $month = $data['month'];
        $emp_code = $data['user_id'];
        $to =$data['reporting_head'];

        $combinationKey = "{$year}-{$month}-{$emp_code}";
        if (!isset($processedCombinations[$combinationKey])) 
        {
        
            $processedCombinations[$combinationKey] = true;

            $bio_qry = "select EmpName from masjclrentry where  EmpCode='{$emp_code}' limit 1";
            $bio_data = mysqli_query($con,$bio_qry);
            $bio_arr = mysqli_fetch_assoc($bio_data);

            $empname = $bio_arr['EmpName'];

            send_mail($year,$month,$emp_code,$empname,$to,$con);
        }


    }


   
}

function send_mail($year,$month,$emp_code,$empname,$to,$con)
{

    $TO = explode(',',$to);

    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 

    $dateTime = new DateTime("$year-$month-01");
    $monthName = $dateTime->format('F');
    #$Email->subject("Weitage Created for $currentMonthYear");
    $Subject        = "Weitage Created for $monthName $year"; 

    $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";

    $EmailText     .= "<p>We are pleased to inform you that a new Weitage has been created for the month of $monthName $year,and it is named $empname.</p><p>This Weitage represents our progress and achievements. Please review the details and let us know if you have any feedback or questions.</p>";
    $EmailText     .= "<p>REGARDS,</p>";
    $EmailText     .= "<p>TEAM MAS</p>";
    $EmailText     .= "</div>";

   
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO);
    $done = send_email($emaildata);
    $status = '';
    if($done == '1')
    {
        
        $status .= 'Success';
        mysqli_query($con,"update pli_weitage SET mail_status=0 WHERE year='{$year}' and month='$month' and user_id='$emp_code' and mail_status='1'");

    }


}







