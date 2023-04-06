<?php
include('report-send.php');
$con = mysql_connect("localhost",'root','vicidialnow');
$db = mysql_select_db("db_bill", $con);

$Qry = "SELECT data_id FROM
(SELECT id,data_id FROM agreement_particulars 
WHERE CURDATE()>periodTo AND DAYNAME(CURDATE())='monday' || DAYNAME(CURDATE())='thursday'
GROUP BY cost_center HAVING MAX(id)=id)AS tab
GROUP BY data_id";
$excuteQry = mysql_query($select);
{
    $data_id[] = $row['data_id'];
}
$data_id = explode(",",$data_id);

$select = "SELECT id,branch_name,cost_center,periodTo,image_upload FROM agreements WHERE CURDATE()>periodTo AND DAYNAME(CURDATE())='monday' || DAYNAME(CURDATE())='thursday' AND id in ($data_id)";
$excute = mysql_query($select);

while($row=  mysql_fetch_assoc($excute))
{
    $branch = $row['branch_name'];
    $cost_center = $row['cost_center'];
    $endDate = $row['periodTo'];
    $id = $row['id'];
    $file = explode(',',$row['image_upload']);
    
    foreach($file as $f)
    {
        $Attach[] = "/var/www/html/ispark/app/webroot/agreement/$id/".$f;
    }
    $select2 = "SELECT group_concat(cost_center) `cost_center` FROM cost_master WHERE id IN ($cost_center)";
    $excute2 = mysql_query($select2);
    $cost_center = mysql_fetch_assoc($excute2);
    $cost_center = $cost_center['cost_center'];
    
    $select3 = "SELECT * FROM `escalation_table` WHERE data_id='$id' AND esc_id=1;";
    $excute3 = mysql_query($select3);
    $mails = mysql_fetch_assoc($excute3);
    //print_r($mails); exit;
    $emaildata['ReceiverEmail']['Email'] = $mails['internal_to'];
    $emaildata['SenderEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
    $emaildata['ReplyEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
    
    if(!empty($mails['internal_cc']))
    {
        $emaildata['AddCc']['Email'] = $mails['internal_cc'];
    }
    else if(!empty($mails['internal_bc']))
    {
        $emaildata['AddBcc']['Email'] = $mails['internal_bc'];
    }
    
    $emaildata['Subject'] = "Agreement Notification"; 
    
    $emaildata['EmailText'] ="<table><tr><td style=\"padding-left:12px;\">The Agreement For Cost Center is $cost_center expired on $endDate. Please Submit "
            . "New Document</td></tr></table>";
    
    
    $Attachment = $Attach;
    
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
    
    if(!empty($mails['external_to']))
    {
        $emailexternal['ReceiverEmail']['Email'] = $mails['external_to'];
        $emailexternal['SenderEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
        $emailexternal['ReplyEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
    
        if(!empty($mails['external_cc']))
        {
            $emailexternal['AddCc']['Email'] = $mails['external_cc'];
        }
        else if(!empty($mails['external_bc']))
        {
            $emailexternal['AddBcc']['Email'] = $mails['external_bc'];
        }
    
        $emailexternal['Subject'] = "Agreement Notification"; 
    
        $emailexternal['EmailText'] ="<table><tr><td style=\"padding-left:12px;\">The Agreement For Cost Center is $cost_center will be expired on $endDate. Plese be sure New Agreement "
            . "will be submitted soon</td></tr></table>";
    
        $Attachment = $Attach;
    
        try
        {
        //$done = send_email( $emailexternal);
        //echo " asdfjlsdjf lsdjf lskdfj lksdjf sldfk";
        //print_r($emaildata);
        }
        catch (Exception $e)
        {
            $error = $e.printStackTrace();
        }   
    }
    //$filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".$filename.date('d_m_Y_H_i_s')."_Export.xls";
    //file_put_contents( $filename, $text); 
    
    
    
    //$ReceiverEmail=array('Email'=>$email);
    //$SenderEmail= array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    //$ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    //$Attachment = $Attach;
    //$Subject="Agreement Notification"; 
    
    //$emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    
    //try
    //{
    //    $done = send_email( $emaildata);
    //}
    //catch (Exception $e)
    //{
    //    $error = $e.printStackTrace();
    //}    
}
