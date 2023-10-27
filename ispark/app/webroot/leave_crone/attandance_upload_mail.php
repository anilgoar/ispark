<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');


$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="select * from mail_alert";
$DataArr = mysqli_query($con,$qry);

$is_flag = false;

function send_attandance_mail($branch,$costcenter,$mail)
{
    $last_day_date = date('d/m/Y',strtotime("-1 days"));   

    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "ATTENDANCE UPLOAD"; 
    $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText     .= "<h5>PLEASE UPLOAD ATTENDANCE of Branch $branch and Costcenter $costcenter - $last_day_date </h5>";
    $EmailText     .= "<p>REGARDS,</p>";
    $EmailText     .= "<p>TEAM MAS</p>";
    $EmailText     .= "</div>";
   
    echo $EmailText;
    $to = array('bhanu.singh@teammas.in');
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to);
    $done = send_email($emaildata);
 
}

if(!empty($DataArr))
{
    
    while($data=mysqli_fetch_assoc($DataArr))
    {
        $branch = $data['Branch'];
        $cost_center = $data['CostCenter'];

        $atnd = "SELECT * FROM `Attandence` WHERE BranchName='$branch' and CostCenter ='$cost_center' and DATE(AttandDate)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
        $attendace_data = mysqli_query($con,$atnd);
        $att_arr = mysqli_fetch_assoc($attendace_data);

        if(empty($att_arr))
        {
            $branchname = $cost_arr['branch'];
            $cost_email = $cost_arr['emailid'];
            $costcenter_name = $cost_arr['cost_center'];

            $is_flag = true; 
            if($is_flag)
            {
                send_attandance_mail($branchname,$costcenter_name,$cost_email);
            }

        }

        
        
    }

   

    
   
}

