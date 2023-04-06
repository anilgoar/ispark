<?php
$clientId   = $_REQUEST['ClientId'];
$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

$ClientInfo = mysql_fetch_assoc(mysql_query("select * from `registration_master` where company_id='$clientId' limit 1"));
$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='$clientId' limit 1"));

//$html .='<img src="logo.jpg" style="width:170px;margin-left:200px;">';
//$html .='<div style="text-align:center;font-size:11px;">A UNIT OF ISPARK DATA CONNECT PVT LTD</div><br/>';

if($BalanceMaster['PlanId'] !=""){
    $PlanDetails = mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));

    // Inbound Call duration details
    $InDuration=mysql_query("SELECT Duration FROM `billing_master` WHERE clientId='$clientId' AND Duration !='' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId");
    $inTotalSumaryUnit=0;
    while($InDurArr=mysql_fetch_assoc($InDuration)){
        if($InDurArr['Duration'] >30){
            $callLength = $InDurArr['Duration'];
            $unit = ceil($callLength/60);
        }
        else{
           $callLength =0;
           $unit =0; 
        }
        
        $amount = 0; 
        if($PlanDetails['InboundCallMinute']=='Flat'){
            $unit = 1;
            $amount = $PlanDetails['InboundCallCharge'];
        }
        else{
            $perMinute = $PlanDetails['InboundCallMinute']*60;
            $unit = ceil($callLength/$perMinute);
            $amount = $unit*$PlanDetails['InboundCallCharge'];
        }

        $inTotalSumaryUnit = $inTotalSumaryUnit+$unit;
    }

    // Outbound Call duration details
    $OutDuration=mysql_query("SELECT Duration FROM `billing_master` WHERE clientId='$clientId' AND Duration !='' AND DedType='Outbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId");
    
    $OutTotalSumaryUnit=0;
    while($OutDurArr=mysql_fetch_assoc($OutDuration)){
        $callLength = $OutDurArr['Duration'];
        $amount = 0; 
        $unit = ceil($callLength/60);
        if($PlanDetails['OutboundCallMinute']=='Flat'){
            $unit = 1;
            $amount = $PlanDetails['OutboundCallCharge'];
        }
        else{
            $perMinute = $PlanDetails['OutboundCallMinute']*60;
            $unit = ceil($callLength/$perMinute);
            $amount = $unit*$PlanDetails['OutboundCallCharge'];
        }

        $OutTotalSumaryUnit = $OutTotalSumaryUnit+$unit;
    }


    $data = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' and date(CallDate) between '$FromDate' AND '$ToDate'"));
    
    $VFO = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $SMS =  mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $Email = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $InboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND Duration !='' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId order by Id");
    $OutboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND Duration !='' AND DedType='Outbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId order by Id");
    $SMSDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");
    $EmailDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");
    $VFODetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");

    $html .="
            <table border='0' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
                <tr>
                    <td colspan='2' rowspan='4' ></td><td colspan='4' rowspan='4'>
                        <img src='http://dialdesk.in/dialdesk/app/webroot/billing_statement/logo.jpg'>
                    </td>
                </tr>
            </table>
    ";
    
    $html .="
            <table border='0' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
                <tr>
                    <td colspan='2' rowspan='2' ></td><td colspan='4' rowspan='2' >
                        A UNIT OF ISPARK DATA CONNECT PVT LTD
                    </td>
                </tr>
            </table>
    ";
    
    
    $html .="
            <table border='1' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
            <tr><td colspan='7' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Client Details</td></tr>
            <tr>
                <th>Company</th>
                <th colspan='3' >Address</th>
                <th>registered Mobile No</th>
                <th>Registered Email Id</th>
                <th>authorised person</th>
            </tr>
            <tr>
                <td>{$ClientInfo['company_name']}</td>
                <td colspan='3' >{$ClientInfo['reg_office_address1']}</td>
                <td>{$ClientInfo['phone_no']}</td>
                <td>{$ClientInfo['email']}</td>
                <td>{$ClientInfo['auth_person']}</td>
            </tr>
            </table>
    ";

    $html .="<table><tr><td>&nbsp;</td></tr></table>";
    
    $TotUseBalance=(round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)+round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)+round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)+round($SMS['Unit']*$PlanDetails['SMSCharge'],2)+round($Email['Unit']*$PlanDetails['EmailCharge'],2));

    $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
    $html .="<tr><td colspan='7' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Plan Details</td></tr>";
    
    $html .="<tr>";
    $html .="<th>Plan Name</th>";
    $html .="<th>Start Date</th>";
    $html .="<th>End Date</th>";
    $html .="<th>Validity</th>";
    $html .="<th>Balance</th>";
    $html .="<th>Available</th>";
    $html .="<th>Used</th>";
    $html .="</tr>";
    $html .="<tr>";
    $html .="<td>{$PlanDetails['PlanName']}</td>";
    $html .="<td>{$BalanceMaster['start_date']}</td>";
    $html .="<td>{$BalanceMaster['end_date']}</td>";
    $html .="<td>".$PlanDetails['RentalPeriod'].' '.$PlanDetails['PeriodType']."</td>";
    $html .="<td>".$BalanceMaster['MainBalance']."</td>";
    
    if(intval($BalanceMaster['Used']) >= intval($BalanceMaster['MainBalance'])){
        $html .="<td>0</td>";
    }
    else{
        $html .="<td>".$BalanceMaster['Balance']."</td>";
    }
    
    
    $html .="<td>".$BalanceMaster['Used']."</td>";
    $html .="</tr>";
    $html .="</table>";
    
    $TinAmount=0;
    $TouAmount=0;
    $TvfAmount=0;
    $TsmAmount=0;
    $TemAmount=0;
    
    
    
    $html .="<table><tr><td>&nbsp;</td></tr></table>";
    
    if($inTotalSumaryUnit !="" || $OutTotalSumaryUnit !="" || $VFO['Unit'] !="" || $SMS['Unit'] !="" || $Email['Unit'] !="") {
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr><td colspan='5' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Summary</td></tr>";
        $html .="<tr>";
        $html .="<th>Description</th>";
        $html .="<th>Vol./Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="<th colspan='2' >Amount</th>";
        $html .="</tr>";
    }
    
    if($inTotalSumaryUnit !="") {
        $TinAmount=round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2);
        $html .="<tr>";
        $html .="<td>ICB</td>";
        $html .="<td>{$inTotalSumaryUnit}</td>";
        $html .="<td>{$PlanDetails['InboundCallCharge']}  Rs./ {$PlanDetails['InboundCallMinute']} Min</td>";
        $html .="<td colspan='2'>".round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)."</td>";
        $html .="</tr>";
    }

    if($OutTotalSumaryUnit !="") {
        $TouAmount=round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2);
        $html .="<tr>";
        $html .="<td>OCB</td>";
        $html .="<td>{$OutTotalSumaryUnit}</td>";
        $html .="<td>{$PlanDetails['OutboundCallCharge']}  Rs./ {$PlanDetails['OutboundCallMinute']} Min</td>";
        $html .="<td colspan='2'>".round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($VFO['Unit'])) {
        $TvfAmount=round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2);
        $html .="<tr>";
        $html .="<td>VFO</td>";
        $html .="<td>{$VFO['Unit']}</td>";
        $html .="<td>{$PlanDetails['VFOCallCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($SMS['Unit'])) {
        $TsmAmount=round($SMS['Unit']*$PlanDetails['SMSCharge'],2);
        $html .="<tr>";
        $html .="<td>SMS</td>";
        $html .="<td>{$SMS['Unit']}</td>";
        $html .="<td>{$PlanDetails['SMSCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($SMS['Unit']*$PlanDetails['SMSCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($Email['Unit'])) {
        $TemAmount=round($Email['Unit']*$PlanDetails['EmailCharge'],2);
        $html .="<tr>";
        $html .="<td>Email</td>";
        $html .="<td>{$Email['Unit']}</td>";
        $html .="<td>{$PlanDetails['EmailCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($Email['Unit']*$PlanDetails['EmailCharge'],2)."</td>";
        $html .="</tr>";
    }
    
    $html .="<tr>";
    $html .="<td>TOTAL ({$FromDate} / {$ToDate})</td>";
    $html .="<td></td>";
    $html .="<td></td>";
    $html .="<td colspan='2'>".($TinAmount+$TouAmount+$TvfAmount+$TsmAmount+$TemAmount)."</td>";
    $html .="</tr>";

    $html .="</table>";

    //$html .="<br/>";

    if(mysql_num_rows($InboundDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (INBOUND)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";

        $InTotalPulse  =0;
        $InTotalAmount =0;
        while($inb = mysql_fetch_assoc($InboundDetails)){
            if($inb['Duration'] >30){
                $callLength = $inb['Duration'];
                $unit = ceil($callLength/60);
            }
            else{
                $callLength =0;
                $unit =0;  
            }
            
            $amount = 0; 

            if($PlanDetails['InboundCallMinute']=='Flat'){
                $unit = 1;
                $amount = $PlanDetails['InboundCallCharge'];
            }
            else{
                $perMinute = $PlanDetails['InboundCallMinute']*60;
                $unit = ceil($callLength/$perMinute);
                $amount = $unit*$PlanDetails['InboundCallCharge'];
            }

            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$unit."</td>";
            $html .="<td>".round($unit*$PlanDetails['InboundCallCharge'],2)."</td>";
            $html .="</tr>";

            $InTotalPulse = $InTotalPulse+$unit;
            $InTotalAmount = $InTotalAmount+$amount;
        }
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";
        $html .="<tr><td colspan='5' ><b>Total Vol {$InTotalPulse}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";

   
    if(mysql_num_rows($OutboundDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (OUTBOUND)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";

        $OutTotalPulse  =0;
        $OutTotalAmount =0;
        while($inb = mysql_fetch_assoc($OutboundDetails)){
           
            $callLength = $inb['Duration'];
            $amount = 0; 
            $unit   = ceil($callLength/60);
            if($PlanDetails['OutboundCallMinute']=='Flat'){
                $unit = 1;
                $amount = $PlanDetails['OutboundCallCharge'];
            }
            else{
                $perMinute = $PlanDetails['OutboundCallMinute']*60;
                $unit = ceil($callLength/$perMinute);
                $amount = $unit*$PlanDetails['OutboundCallCharge'];
            }

            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$unit."</td>";
            $html .="<td>".round($unit*$PlanDetails['OutboundCallCharge'],2)."</td>";
            $html .="</tr>";

            $OutTotalPulse = $OutTotalPulse+$unit;
            $OutTotalAmount = $OutTotalAmount+$amount;
        }
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$OutTotalPulse}</b></td><td><b>{$OutTotalAmount}</b></td></tr>";
        $html .="<tr><td colspan='5' ><b>Total Vol {$OutTotalPulse}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";


    if(mysql_num_rows($SMSDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (SMS)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $SMSTotal = 0;
        while($inb = mysql_fetch_assoc($SMSDetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['SMSCharge'],2)."</td>";
            $html .="</tr>";
            $SMSTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$SMSTotal}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";

    if(mysql_num_rows($EmailDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (EMAIL)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $EmailTotal = 0;
        while($inb = mysql_fetch_assoc($EmailDetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['EmailCharge'],2)."</td>";
            $html .="</tr>";
            $EmailTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$EmailTotal}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";



    if(mysql_num_rows($VFODetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (VFO)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $VFOTotal = 0;
        while($inb = mysql_fetch_assoc($VFODetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['VFOCallCharge'],2)."</td>";
            $html .="</tr>";
            $VFOTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$VFOTotal}</b></td></tr>";
        $html .="</table>";
    }
}



$fileName = "billing".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html ;die;

