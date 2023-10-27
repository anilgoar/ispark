<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');

// $con = mysql_connect("localhost",'root','321*#LDtr!?*ktasb');
// $db = mysql_select_db("db_bill", $con);

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="SELECT * FROM `business_gratitude` WHERE is_sender_find ='1'";
$ticket_list = mysqli_query($con,$qry);

if(!empty($ticket_list))
{    
    while($ticket_det=mysqli_fetch_assoc($ticket_list))
    {

        $ticket_id = $ticket_det['id'];
        #$EmpCode = $ticket_det['emp_code'];
        $department = $ticket_det['department'];
        $branch = $ticket_det['branch'];
        $costcenter = $ticket_det['costcenter'];
        $type = $ticket_det['type'];
        $flag = true;
        
        $mail_find_query = "select `to`,cc,bcc from business_rule where `type`='gratitude' and branch = '$branch' and costcenter='$costcenter' limit 1"; 
        $mail_find_rsc = mysqli_query($con,$mail_find_query);
        $mail_find_det = mysqli_fetch_assoc($mail_find_rsc);
        #print_r($mail_find_det);exit;
        $create_date = date('Y-m-d H:i:s');
        
        if(!empty($mail_find_det['to']))
        {
            $to = $mail_find_det['to'];
            $cc = $mail_find_det['cc'];
            $bcc = $mail_find_det['bcc'];
            $upd = "update business_gratitude set is_sender_find='0',`to`='$to',cc='$cc',bcc='$bcc' where id='$ticket_id' limit 1";
            //echo $upd;
            $rsc_upd = mysqli_query($con,$upd);
            
        }
        continue;
    }

}

$qry_mail ="SELECT * FROM `business_gratitude` WHERE email_send_status ='1' and is_sender_find='0'";
$mail_list = mysqli_query($con,$qry_mail);


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
        $department = $mail_det['dept']; 
        $remarks = $mail_det['remarks']; 
        $attachment = $mail_det['attachment']; 
        
        $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
        $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
        $Subject   = "MASCARE Ticket No. $ticket_no $type For Employee -   $emp_code"; 
        $body = "New Ticket No. $ticket_no By $emp_name from $contact_no Generated For Branch $branch and Deparmtnet $department";
        
        $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
        $EmailText     .= "<p>$remarks</p>";
        if($attachment)
        {
            $EmailText     .= "<p>Please find the attachment by  <a href=\"$attachment\">clicking here</a></p>";
        }
        
        $EmailText     .= "<p>This is auto genrated mail.</p>";
        $EmailText     .= "<p>REGARDS,</p>";
        $EmailText     .= "<p>TEAM MAS</p>";
        $EmailText     .= "</div>";
        
        $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO,'AddCc'=>$CC);
        
        $done = send_email($emaildata);
        if($done)
        {
            $upd = "update business_gratitude set email_send_status='0' where id='$ticket_id' limit 1";
            $rsc_upd = mysqli_query($con,$upd);
        }
    }
    


    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    
    #$Subject        = "Mas Callnet : Email Creation"; 

    

    


    
    

}