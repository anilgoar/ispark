<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');
//die;

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="select * from emp_onboard_trigger_services where trigger_type='ad_id' and ticket_status='1' and type='leaver'";
$DataArr = mysqli_query($con,$qry);

$is_flag = false;
$log_file_path = "/var/www/html/mascallnetnorth.in/ispark/app/webroot/leave_crone/";
function check_file_exist($log_file_path,$emp_code,$search_string)
{
    $return= array("status"=>false);
    $file_list = scandir("$log_file_path/log");
    
    foreach($file_list as $file_name)
    {
        if(empty($file_name) || $file_name=='.' || $file_name=='..')
        {
            continue;
        }
        
        $file_loc = "$log_file_path/log/$file_name";
        $content = strtolower(file_get_contents($file_loc));
        if (strpos($content, strtolower($emp_code)) !== false && strpos($content, strtolower($search_string)) !== false) {
                $return['status'] = true;
                $return['file_list'][] = $file_loc;
            }
           
    }

    return $return; 
}

function send_email_mail($ticket_no,$EmpCode,$empname,$EmailId,$doj,$to,$cc,$bcc,$attempt,$con,$attachments,$variance,$costcenter_name)
{

    $TO = explode(',',$to);
    $CC = explode(',',$cc);
    $BCC = explode(',',$bcc);


    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email');
    $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email');
    if(!empty($variance))
    {
        $Subject        = "Escalation ".$attempt." Ticket AD Id Delayed by ".$variance; 
        
    }else{

        $Subject        = "Escalation : Ad Id Deletion"; 
    }
    $EmailText      = "<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";

    if(!empty($variance))
    {
        $EmailText     .= "<p>Escalation ".$attempt." Ticket AD Id Delayed by ".$variance."</p>";
    }

    $EmailText     .= "<p>A New Ticket <b>".$ticket_no."</b> has been assigned for deleting AD Id. Employee Name - <b>".$empname."</b>,Employee Code - <b>".$EmpCode."</b>, Date of Joining (from Ispark) - <b>".date_format(date_create($doj),"d-M-Y")."</b>,Email Id - <b>".$EmailId."</b>,Process Name - <b>".$costcenter_name."</b></p>";
    $EmailText     .= "<p>This is auto genrated mail.</p>";
    $EmailText     .= "<p>REGARDS,</p>";
    $EmailText     .= "<p>TEAM MAS</p>";
    $EmailText     .= "</div>";
   

    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO,'AddCc'=>$CC,'AddBcc'=>$BCC,'Attachment'=>$attachments);
    $done = send_email($emaildata);
    $status = '';
    if($done == '1')
    {
        
        $status .= 'Success';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='email_id',type='leaver',`to`='$to',cc='$cc',bcc='$bcc',mail_status='1',mail_resp='$status',mail_date=NOW()");

    }else{

        $status .= 'Fail';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='{$ticket_no}',emp_code='$EmpCode',trigger_type='email_id',type='leaver',`to`='$to',cc='$cc',bcc='$bcc',mail_status='0',mail_body='{$done}',mail_resp='$status',mail_date=NOW()");

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

        $ticket_id = $data['id'];
        $search_string = "A user account was deleted.";
        $file_exist = check_file_exist($log_file_path,$emp_code,$search_string);
        if($file_exist['status'])
        {
            $files_list_string = "{{".implode("}}{{",$file_exist['file_list'])."}}";
            $files_list = addslashes($files_list_string);
            $upd_email = "update emp_onboard_trigger_services set ticket_status='0',log_file='$files_list',ticket_close_at=now() where id='$ticket_id' limit 1";
            $rsc = mysqli_query($con,$upd_email);
            continue;    
        }
        $search_string2 = "a user account was created.";
        $file_exist = check_file_exist($log_file_path,$emp_code,$search_string2);
        if($file_exist['status'])
        {
            $attachments = $file_exist['file_list'];
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
        $EmailId = $bio_arr['EmailId'];
        $doj = $bio_arr['Doj'];


        send_email_mail($ticket_no,$EmpCode,$empname,$EmailId,$doj,$to,$cc,$bcc,$attempt,$con,$attachments,$variance,$costcenter_name);

    }

   
}

