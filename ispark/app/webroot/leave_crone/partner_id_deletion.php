<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');


$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="select * from emp_onboard_trigger_services where trigger_type='partner_id_req' and ticket_status='1' and type='leaver'";
$DataArr = mysqli_query($con,$qry);

$is_flag = false;

function send_part_mail($ticket_no,$EmpCode,$empname,$EmailId,$partner_id,$doj,$to,$cc,$bcc,$attempt,$con,$res_date,$variance,$costcenter_name)
{

    $TO = explode(',',$to);
    $CC = explode(',',$cc);
    $BCC = explode(',',$bcc);


    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    #$Subject        = "Mas Callnet : Partner ID deletion - $partner_id";

    if(!empty($variance))
    {
        $Subject   = "Escalation ".$attempt." Ticket Partner ID Delayed by ".$variance; 
        
    }else{

        $Subject   = "Mas Callnet : Partner ID deletion - $partner_id"; 
    }

    $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";

    if(!empty($variance))
    {
        $EmailText     .= "<p>Escalation ".$attempt." Ticket Partner ID Delayed by ".$variance."</p>";
    }

    $EmailText     .= "<p>A New Ticket <b>".$ticket_no."</b> has been assigned for delete the Partner ID. Employee Name - <b>".$empname."</b>,Employee Code - <b>".$EmpCode."</b>, Date of Joining (from Ispark) - <b>". date_format(date_create($doj),"d-M-Y")."</b>,Partner Id - <b>".$partner_id."</b>,Process Name - <b>".$costcenter_name."</b></p>";

    $EmailText     .= "<table border='1'>";
    $EmailText     .= "<tr>";
    $EmailText     .= "<th>Full Name</th>";
    $EmailText     .= "<th>Emp Id</th>";
    $EmailText     .= "<th>Analyst Onfido Email</th>";
    $EmailText     .= "<th>Office Location</th>";
    $EmailText     .= "<th>Reson Of Leaving</th>";
    $EmailText     .= "<th>Delete</th>";
    $EmailText     .= "<th>Last Working Date</th>";
    $EmailText     .= "</tr>";

    $EmailText     .= "<tr>";
    $EmailText     .= "<td>".$empname."</td>";
    $EmailText     .= "<td>".$EmpCode."</td>";
    $EmailText     .= "<td>".$partner_id."</td>";
    $EmailText     .= "<td>India_Noida</td>";
    $EmailText     .= "<td>Attrition</td>";
    $EmailText     .= "<td>Yes</td>";
    if(!empty($res_date))
    {
        $EmailText     .= "<td>".date_format(date_create($res_date),"d-M-Y")."</td>";
    }else{
        $EmailText     .= "<td></td>";
    }
    
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
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='partner_id_req',type='leaver',`to`='$to',cc='$cc',bcc='$bcc',mail_status='1',mail_resp='$status',mail_date=NOW()");

    }else{

        $status .= 'Fail';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='partner_id_req',type='leaver',`to`='$to',cc='$cc',bcc='$bcc',mail_status='0',mail_body='{$done}',mail_resp='$status',mail_date=NOW()");

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
        #$partner_id = $data['partner_id'];

        $last_mail_time = $data['last_mail_time'];

        $get_mail_qry = "SELECT MIN(mail_date) as first_mail_date FROM emp_onboard_trigger_services_log WHERE ticket_no = '$ticket_no' and type='leaver' LIMIT 1";
        $get_mail_log = mysqli_query($con,$get_mail_qry);
        $get_mail_arr = mysqli_fetch_assoc($get_mail_log);

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

        $get_partner_qry = "SELECT * FROM emp_onboard_trigger_services WHERE emp_code='$emp_code' and type='joiner' AND  trigger_type='partner_id_req' ";
        $get_partner = mysqli_query($con,$get_partner_qry);
        $get_partner_arr = mysqli_fetch_assoc($get_partner);

        $partner_id = $get_partner_arr['partner_id'];

        $bio_qry = "select BranchName,CostCenter,EmpCode,EmpName,EmailId,Doj,ResignationDate from masjclrentry where BranchName='{$branch}' and CostCenter='{$costcenter}' and EmpCode='{$emp_code}' limit 1";
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
        #$EmailId = $bio_arr['EmailId'];
        $doj = $bio_arr['Doj'];
        $res_date = $bio_arr['ResignationDate'];

        $get_email = "SELECT close_remarks FROM emp_onboard_trigger_services WHERE  type='leaver' AND  emp_code='$emp_code' AND  trigger_type ='email_id' AND ticket_status='0'";
        $get_mail_data = mysqli_query($con,$get_email);
        $get_mail_arr = mysqli_fetch_assoc($get_mail_data);

        $EmailId = $get_mail_arr['close_remarks'];



        send_part_mail($ticket_no,$EmpCode,$empname,$EmailId,$partner_id,$doj,$to,$cc,$bcc,$attempt,$con,$res_date,$variance,$costcenter_name);

    }




   

    
   
}

