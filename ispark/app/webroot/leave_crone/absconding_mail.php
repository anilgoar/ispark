<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');


$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$Db = mysqli_select_db($con,"db_bill");

//exit;
//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="select * from mail_alert";
$DataArr = mysqli_query($con,$qry);

$is_flag = false;

function send_attandance_mail($to,$cc,$bcc,$html,$con)
{
    $TO = explode(',',$to);
    $CC = explode(',',$cc);
    $BCC = explode(',',$bcc); 
    #$TO = array('bhanu.singh@teammas.in');

    // $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    // $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $SenderEmail    = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    $ReplyEmail     = array('Email'=>'mcnemployeetransitionalert@teammas.in','Name'=>'Mas_Email'); 
    $Subject        = "Absconding/Absent"; 
    $EmailText      ="<div style='width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText     .= "<table cellspacing='0' border='1'>
                        <tr>
                        <th>Branch</th>
                        <th>CostCenter</th>
                        <th>EmpName</th>
                        <th>EmpCode</th>
                        <th>Leave From Date</th>
                        <th>Leave To Date</th>
                        </tr>";
    $EmailText     .= $html;
    $EmailText     .= "</html>";
    $EmailText     .= "<p>REGARDS,</p>";
    $EmailText     .= "<p>TEAM MAS</p>";
    $EmailText     .= "</div>";
   

    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $TO,'AddCc'=>$CC,'AddBcc'=>$BCC);
    //print_r($emaildata);
    $done = send_email($emaildata);

    $email_json = addslashes(json_encode($emaildata)) ;

    if($done == '1')
    {
        
        $status .= 'Success';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='absconding',trigger_type='absconding',`to`='$to',cc='$cc',bcc='$bcc',mail_status='1',mail_resp='{$email_json}',mail_date=NOW()");

    }else{

        $status .= 'Fail';
        mysqli_query($con,"insert emp_onboard_trigger_services_log SET ticket_no='absconding',trigger_type='absconding',`to`='$to',cc='$cc',bcc='$bcc',mail_body='{$done}',mail_status='0',mail_resp='{$email_json}',mail_date=NOW()");

    }
 
}

if(!empty($DataArr))
{
    
    while($data=mysqli_fetch_assoc($DataArr))
    {
        $branch = $data['Branch'];
        $cost_center = $data['CostCenter'];
        $to = $data['to'];
        $cc = $data['cc'];
        $bcc = $data['bcc'];

        $atnd = "SELECT * FROM `continuously_leave` WHERE BranchName='$branch' and CostCenter = '$cost_center' and leave_status = '0' and left_status='0' and MONTH(from_date) = MONTH(NOW())";

        $attendace_data = mysqli_query($con,$atnd);

        if(!empty($attendace_data))
        {   
            $html = '';
            while($att_arr = mysqli_fetch_assoc($attendace_data))
            {
                if(!empty($att_arr))
                {
                    $is_flag = true; 
                    $from_leave = date_format(date_create($att_arr['from_date']),'d-M-Y');
                    $to_leave = date_format(date_create($att_arr['to_date']),'d-M-Y');
                    $html .= "<tr>
                        <td>{$att_arr['BranchName']}</td>
                        <td>{$att_arr['CostCenter']}</td>
                        <td>{$att_arr['EmpName']}</td>
                        <td>{$att_arr['EmpCode']}</td>
                        <td>{$from_leave}</td>
                        <td>{$to_leave}</td>
                    </tr>";
                }
                
            }
 
            
            if($is_flag)
            {
                send_attandance_mail($to,$cc,$bcc,$html,$con);
            }

        }

        
        
    }

   

    
   
}

