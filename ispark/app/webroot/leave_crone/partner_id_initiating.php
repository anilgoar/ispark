<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');

//die;
$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="select * from emp_onboard_trigger_services where trigger_type='partner_id_req' and ticket_status='1' and type='joiner'";
$DataArr = mysqli_query($con,$qry);


function send_email_mail($ticket_no,$EmpCode,$empname,$doj,$EmailId,$to,$cc,$bcc,$attempt,$con,$partner_id,$variance,$costcenter_name)
{

    $TO = explode(',',$to);
    $CC = explode(',',$cc);


    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    #$Subject        = "Mas Callnet : Partner Id Initiating"; 

    if(!empty($variance))
    {
        $Subject   = "Escalation ".$attempt." Ticket Partner ID Delayed by ".$variance; 
        
    }else{

        $Subject   = "Mas Callnet : Partner ID Initiating"; 
    }

    $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";

    if(!empty($variance))
    {
        $EmailText     .= "<p>Escalation ".$attempt." Ticket Partner Id Delayed by ".$variance."</p>";
    }

    $EmailText     .= "<p>A New Ticket <b>".$ticket_no."</b> has been assigned for initiaing Partner ID request. Employee Name - <b>".$empname."</b>,Employee Code - <b>".$EmpCode."</b>, Date of Joining (from Ispark) - <b>". date_format(date_create($doj),"d-M-Y")."</b>,Email Id - <b>".$EmailId."</b>,Process Name - <b>".$costcenter_name."</b></p>";
    $EmailText     .= "<table border='1'>";
    $EmailText     .= "<tr>";
    $EmailText     .= "<th>Full Name</th>";
    $EmailText     .= "<th>Emp Id</th>";
    #$EmailText     .= "<th>Analyst Onfido Email</th>";
    $EmailText     .= "<th>Office Location</th>";
    $EmailText     .= "<th>Joining Day</th>";
    $EmailText     .= "</tr>";

    $EmailText     .= "<tr>";
    $EmailText     .= "<td>".$empname."</td>";
    $EmailText     .= "<td>".$EmpCode."</td>";
    #$EmailText     .= "<td>".$partner_id."</td>";
    $EmailText     .= "<td>India_Noida</td>";
    $EmailText     .= "<td>".date_format(date_create($doj),"d-M-Y")."</td>";
    $EmailText     .= "</tr>";

    $EmailText     .= "</table>";

    $EmailText     .= "<p>This is auto genrated mail.</p>";
    $EmailText     .= "<p>REGARDS,</p>";
    $EmailText     .= "<p>TEAM MAS</p>";
    $EmailText     .= "</div>";
   
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO,'AddCc'=>$CC,'AddBcc'=>$BCC);
    $done = send_email($emaildata);
    $status = '';
    if($done == '1')
    {
        
        $status .= 'Success';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='partner_id_req',type='joiner',`to`='$to',cc='$cc',bcc='$bcc',mail_status='1',mail_resp='$status',mail_date=NOW()");

    }else{

        $status .= 'Fail';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='partner_id_req',type='joiner',`to`='$to',cc='$cc',bcc='$bcc',mail_status='0',mail_body='{$done}',mail_resp='$status',mail_date=NOW()");

    }

    $attempt = $attempt+1;
    mysqli_query($con,"update emp_onboard_trigger_services SET last_mail_time=NOW(),mail_attempt='$attempt',last_mail_status='$status' WHERE ticket_no='{$ticket_no}' and ticket_status='1'");

 
}

if(!empty($DataArr))
{

    while($data=mysqli_fetch_assoc($DataArr))
    {
        
        $ticket_no = $data['ticket_no'];
        $branch = $data['branch'];
        $costcenter = $data['cost_center'];
        $emp_code = $data['emp_code'];
        $to =$data['to'];
        $cc = $data['cc'];
        $attempt = $data['mail_attempt'];

        $ticket_id = $data['ticket_id'];

        $partner_id = $data['partner_id'];

        $last_mail_time = $data['last_mail_time'];

        $get_mail_qry = "SELECT MIN(mail_date) as first_mail_date FROM emp_onboard_trigger_services_log WHERE ticket_no = '$ticket_no' and type='joiner' LIMIT 1";
        $get_mail_log = mysqli_query($con,$get_mail_qry);
        $get_mail_arr = mysqli_fetch_assoc($get_mail_log);

        $get_mail_close_qry = "SELECT * FROM emp_onboard_trigger_services WHERE  type='joiner' AND  emp_code='$emp_code' AND  (trigger_type ='email_id' || trigger_type='ad_id') AND ticket_status='0'";
        $get_mail_close = mysqli_query($con,$get_mail_close_qry);
        $get_close_arr = mysqli_fetch_assoc($get_mail_close);

        $is_flag = false;
        if(!empty($get_close_arr))
        {
            $is_flag = true;
        }

        $first_mail_time = $get_mail_arr['first_mail_date'];

        $variance = '';
        $bcc = '';
        if(!empty($first_mail_time))
        {
            $current_time = strtotime("now");
            $first_mail_time = strtotime($first_mail_time);   
            $variance =  round(abs($current_time - $first_mail_time) / 3600). " Hours";
            $bcc = $data['bcc'];
        }



        $bio_qry = "select BranchName,CostCenter,EmpCode,EmpName,EmailId,Doj from masjclrentry where BranchName='{$branch}' and CostCenter='{$costcenter}' and EmpCode='{$emp_code}' limit 1";
        $bio_data = mysqli_query($con,$bio_qry);
        $bio_arr = mysqli_fetch_assoc($bio_data);


        $get_process_qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
        $get_process = mysqli_query($con,$get_process_qry);
        $process_arr = mysqli_fetch_assoc($get_process);

        $process_name = $process_arr['process_name'];

        $costcenter_name = '';

        if($process_name == '')
        {
            $costcenter_name .= $process_arr['CostCenterName'];

        }else{
            $costcenter_name .= $process_arr['process_name'];
        }


        $EmpCode = $bio_arr['EmpCode'];
        $empname = $bio_arr['EmpName'];
        $doj = $bio_arr['Doj'];
        #$EmailId = $bio_arr['EmailId'];


        $get_email = "SELECT close_remarks FROM emp_onboard_trigger_services WHERE  type='joiner' AND  emp_code='$emp_code' AND  trigger_type ='email_id' AND ticket_status='0'";
        $get_mail_data = mysqli_query($con,$get_email);
        $get_mail_arr = mysqli_fetch_assoc($get_mail_data);

        $EmailId = $get_mail_arr['close_remarks'];

        if($is_flag)
        {
            send_email_mail($ticket_no,$EmpCode,$empname,$doj,$EmailId,$to,$cc,$bcc,$attempt,$con,$partner_id,$variance,$costcenter_name);
        }

            


        

    }




   

    
   
}

