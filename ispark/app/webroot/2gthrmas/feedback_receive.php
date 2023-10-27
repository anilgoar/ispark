<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');


$status = $_GET['status'];
$feed_id = $_GET['ticket_id'];
// $con = mysql_connect("localhost",'root','321*#LDtr!?*ktasb');
// $db = mysql_select_db("db_bill", $con);

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");

if($status=='no')
{
    $upd_reopen_qry = "update business_tickets set ticket_status='1',ticket_reopen_at=now(),case_status='re-open' where id='$feed_id' and case_status='open' and ticket_status='0' limit 1";
    $rsc_upd_ticket = mysqli_query($con,$upd_reopen_qry);
    $msg = "Your Ticket has been Re-Opened.";
    #writeToLog("no feedback.. ->$upd_reopen_qry");
}
else if($status=='yes')
{
    $upd_reopen_qry = "update business_tickets set ticket_status='0',ticket_fclose=now(),case_status='close' where id='$feed_id' limit 1";
    $rsc_upd_ticket = mysqli_query($con,$upd_reopen_qry);
     $msg = "Thanks For Your Feedback.";
     #writeToLog("yes feedback.. ->$upd_reopen_qry");
}
else
{
    echo "Please Create Another Ticket";
}

echo $msg;

//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="SELECT * FROM `business_tickets` WHERE id='$feed_id' and case_status='re-open' limit 1";
$mail_list = mysqli_query($con,$qry);



if($mail_list)
{   
    while($mail_det=mysqli_fetch_assoc($mail_list))
    {
        $ticket_id = $mail_det['id'];
        $to = $mail_det['to'];
        $cc = $mail_det['cc'];
        $bcc = $mail_det['bcc'];
        $TO = explode(',',$to);
        $CC = explode(',',$cc);
        #$BCC = explode(',',$bcc);
        $type = $mail_det['type'];
        $emp_name = $mail_det['emp_name']; 
        $contact_no = $mail_det['contact_no']; 
        $emp_code = $mail_det['contact_no']; 
        $ticket_no = $mail_det['ticket_no']; 
        $branch = $mail_det['branch']; 
        $department = $mail_det['department']; 
        
        $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
        $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
        $Subject   = "MASCARE Ticket No. $ticket_no $type For Employee -   $emp_code"; 
        $body = " Ticket No. $ticket_no By $emp_name from $contact_no Re-Opened For Branch $branch and Deparmtnet $department";
        
        $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
        $EmailText     .= "<p>$body</p>";
        $EmailText     .= "<p>This is auto genrated mail.</p>";
        $EmailText     .= "<p>REGARDS,</p>";
        $EmailText     .= "<p>TEAM MAS</p>";
        $EmailText     .= "</div>";
        
        $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO,'AddCc'=>$CC);
        
        $done = send_email($emaildata);
        if($done)
        {
            $upd = "update business_tickets set email_send_status='0' where id='$ticket_id' limit 1";
            $rsc_upd = mysqli_query($con,$upd);
        }
    }
    
    

    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    
    #$Subject        = "Mas Callnet : Email Creation"; 

    

    


    
    

}