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
$qry ="SELECT * FROM masjclrentry WHERE  date(CreateDate) = curdate()";
$DataArr = mysqli_query($con,$qry);

if(!empty($DataArr))
{
    
    $bio_value = '';
    $email_value = '';
    $bgv_value = '';
    $part_value = '';
    $ad_value = '';
    $flag = true;
    while($data=mysqli_fetch_assoc($DataArr))
    {

        $id = $data['id'];
        $EmpCode = $data['EmpCode'];
        $BranchName = $data['BranchName'];
        $CostCenter = $data['CostCenter'];

        $list_value='';
        $type = 'joiner';

        $exist_id = mysqli_query($con,"select * from emp_onboard_trigger_services where branch='$BranchName' and cost_center = '$CostCenter' and emp_code ='$EmpCode' and type='joiner'");
        $exist_data = mysqli_fetch_assoc($exist_id);

        $create_date = date('Y-m-d H:i:s');
        $exist_bio =mysqli_query($con,"select * from emp_onboard_join_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='bio_id'");
        $exist_alert_bio = mysqli_fetch_assoc($exist_bio);

        $bio_to = $exist_alert_bio['to'];
        $bio_cc = $exist_alert_bio['cc'];
        $bio_bcc = $exist_alert_bio['bcc'];
        $bio_type = $exist_alert_bio['trigger_type'];
        $ticket_bio = "Bio$id";

        if(!empty($exist_alert_bio) && empty($exist_data))
        {
            $bio_value .= "('".$id."','".$ticket_bio."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$bio_type."','".$bio_to."','".$bio_cc."','".$bio_bcc."','".$create_date."'),";
        }

        $exist_email =mysqli_query($con,"select * from emp_onboard_join_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='email_id'");
        $exist_alert_email = mysqli_fetch_assoc($exist_email);
        $email_to = $exist_alert_email['to'];
        $email_cc = $exist_alert_email['cc'];
        $email_bcc = $exist_alert_email['bcc'];
        $email_type = $exist_alert_email['trigger_type'];
        $ticket_email = "Email$id";

        if(!empty($exist_alert_email) && empty($exist_data))
        {
            $email_value .= "('".$id."','".$ticket_email."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$email_type."','".$email_to."','".$email_cc."','".$email_bcc."','".$create_date."'),";
        }

        $exist_bgv = mysqli_query($con,"select * from emp_onboard_join_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='bgv'");
        $exist_alert_bgv = mysqli_fetch_assoc($exist_bgv);
        $bgv_to = $exist_alert_bgv['to'];
        $bgv_cc = $exist_alert_bgv['cc'];
        $bgv_bcc = $exist_alert_bgv['bcc'];
        $bgv_type = $exist_alert_bgv['trigger_type'];
        $ticket_bgv = "Bgv$id";

        if(!empty($exist_alert_bgv) && empty($exist_data))
        {
            $bgv_value .= "('".$id."','".$ticket_bgv."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$bgv_type."','".$bgv_to."','".$bgv_cc."','".$bgv_bcc."','".$create_date."'),";
        }

        $exist_part = mysqli_query($con,"select * from emp_onboard_join_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='partner_id_req'");
        $exist_alert_part = mysqli_fetch_assoc($exist_part);
        $part_to = $exist_alert_part['to'];
        $part_cc = $exist_alert_part['cc'];
        $part_bcc = $exist_alert_part['bcc'];
        $part_type = $exist_alert_part['trigger_type'];
        $ticket_part = "Partner$id";

        if(!empty($exist_alert_part) && empty($exist_data))
        {
            $part_value .= "('".$id."','".$ticket_part."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$part_type."','".$part_to."','".$part_cc."','".$part_bcc."','".$create_date."'),";
            //mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $part_value"); 
        }

        $exist_ad = mysqli_query($con,"select * from emp_onboard_join_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='ad_id'");
        $exist_alert_ad = mysqli_fetch_assoc($exist_ad);
        $ad_to = $exist_alert_ad['to'];
        $ad_cc = $exist_alert_ad['cc'];
        $ad_bcc = $exist_alert_ad['bcc'];
        $ad_type = $exist_alert_ad['trigger_type'];
        $ticket_ad = "Ad$id";

        if(!empty($exist_alert_ad) && empty($exist_data))
        {
            $ad_value .= "('".$id."','".$ticket_ad."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$ad_type."','".$ad_to."','".$ad_cc."','".$ad_bcc."','".$create_date."'),";
            //mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $part_value"); 
        }


        
        // if(!empty($exist_data))
        // {
        //     $flag = false;

        // }else{
        //     $flag = true;
        // }
      
        
    }

    if($flag)
    {   
        $new_advalue =  rtrim($ad_value, ",");
        $new_biovalue =  rtrim($bio_value, ",");
        $new_partvalue =  rtrim($part_value, ",");
        $new_bgvvalue =  rtrim($bgv_value, ",");
        $new_emailvalue =  rtrim($email_value, ",");

        // mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $list_value"); 
        mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $new_biovalue");
        mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $new_partvalue");
        mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $new_bgvvalue");
        mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $new_emailvalue");
        mysqli_query($con,"INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $new_advalue");

    }


}
