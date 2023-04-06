<?php
include("include/connection.php");
include("include/session-check.php");

$Fdate = $_GET['Fdate'];
$Tdate = $_GET['Tdate'];
$mode =  $_GET['mode'];
$RType =  $_GET['RType'];

if($RType=='History')
{
	$QueryStr = "T1.Id=T2.DataId and T1.AllocationId=T2.AllocationId";
}
if($RType=='WhtHistory')
{
	$QueryStr = "T1.LastId=T2.Id and T1.AllocationId=T2.AllocationId";
}


if($mode=='ImportDate')
{
	$filename = "Export_Data_".date("Y-m-d_h-i_s",time());  
	$Select = "SELECT AccountNo,T1.Code,T1.HierarchyId,T1.MSISDN,T1.ParentId,T1.AccountType,T1.AccCat,T1.Name,T1.Address,T1.City,T1.Plan,T1.Pincode,T1.Cycle,T1.Deposit,T1.0_30,T1.30_60,T1.60_90,T1.90_120,T1.120_150,T1.150_180,T1.180_210,T1.Gt210,T1.Due,T1.BKT,T1.Band,T1.Allocation,T1.CreditLimit,T1.CurrentCharges,T1.ActvationDate,T1.ActionDate,T1.Status,T1.AVCVStatus,T1.Otherremarks,T1.Landmark,T1.AlternateNo,T1.NoInvoices,T1.BillerType,T1.DistCode,T1.SalesSource,T1.North,T1.Remarks,T1.Exclusion,T1.ECS,T1.DISPATCH_METHOD,T1.EmailID,T1.RMs,T1.BillGenerationDate,T1.DDD,T1.BillerType1,T1.ASO,T1.CHQDropBox,T1.APP,T1.X_DIST_POS_NAME,T1.AdjustmentMTD,T1.AdjustmentFTD,T1.BAL,T2.CallStatus,T2.CallType,T2.VOC,T2.Remarks,T2.Waiver,T1.ImportDate,T2.CallDate,getuser(T1.AgentId) AgentName,getAllocation(T1.AllocationId) AllocationName,T1.PaymentAmt `DIV MTD`,T1.AdjustmentAmt `ADJ MTD`,T1.ReversalAmt `REV MTD`,(T1.Due-(T1.PaymentAmt+T1.AdjustmentAmt)+T1.ReversalAmt) `Net Remaining Balance` FROM allocation_master T1 LEFT JOIN tagged_data T2 ON $QueryStr WHERE DATE(ImportDate) BETWEEN '$Fdate' AND '$Tdate'";
}
if($mode=='CallDate')
{
	$filename = "Export_Data_".date("Y-m-d_h-i_s",time());
	 $Select = "SELECT AccountNo,T1.Code,T1.HierarchyId,T1.MSISDN,T1.ParentId,T1.AccountType,T1.AccCat,T1.Name,T1.Address,T1.City,T1.Plan,T1.Pincode,T1.Cycle,T1.Deposit,T1.0_30,T1.30_60,T1.60_90,T1.90_120,T1.120_150,T1.150_180,T1.180_210,T1.Gt210,T1.Due,T1.BKT,T1.Band,T1.Allocation,T1.CreditLimit,T1.CurrentCharges,T1.ActvationDate,T1.ActionDate,T1.Status,T1.AVCVStatus,T1.Otherremarks,T1.Landmark,T1.AlternateNo,T1.NoInvoices,T1.BillerType,T1.DistCode,T1.SalesSource,T1.North,T1.Remarks,T1.Exclusion,T1.ECS,T1.DISPATCH_METHOD,T1.EmailID,T1.RMs,T1.BillGenerationDate,T1.DDD,T1.BillerType1,T1.ASO,T1.CHQDropBox,T1.APP,T1.X_DIST_POS_NAME,T1.AdjustmentMTD,T1.AdjustmentFTD,T1.BAL,T2.CallStatus,T2.CallType,T2.VOC,T2.Remarks,T2.Waiver,T1.ImportDate,T2.CallDate,getuser(T1.AgentId) AgentName,getAllocation(T1.AllocationId) AllocationName,T1.PaymentAmt `DIV MTD`,T1.AdjustmentAmt `ADJ MTD`,T1.ReversalAmt `REV MTD`,(T1.Due-(T1.PaymentAmt+T1.AdjustmentAmt)+T1.ReversalAmt) `Net Remaining Balance` FROM allocation_master T1 LEFT JOIN tagged_data T2 ON $QueryStr WHERE DATE(T2.CallDate) BETWEEN '$Fdate' AND '$Tdate'";
}
if($mode=='PickUpdate')
{
	$filename = "Export_Data_".date("Y-m-d_h-i_s",time());
	 $Select = "SELECT DATE(T1.CallStatus) `Tagged Date`,getuser(T1.AgentId) `Tagged Agent Name`,T2.MSISDN,T2.Name,T2.AlternateNo `Alternate Number`,
T2.Address,T2.Due,T2.PaymentAmt Payment,T1.CallStatus,T1.CallType,T1.Voc,T1.Remarks,T1.PickUpDate FROM lead_master T1 JOIN allocation_master T2 ON 
T1.DataId=T2.Id WHERE DATE(PickUpDate) BETWEEN '$Fdate' AND '$Tdate'";
}


	$Query = mysql_query($Select);
	$Count  = mysql_num_fields($Query);

header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename."."xls");
header("Pragma: no-cache");
header("Expires: 0");

?>

<table cellspacing="0" border="1" width="100%">
	<tr class="head">
	<?php
	for ($i = 0; $i < $Count; $i++) 
	{
		$header = mysql_field_name($Query, $i);
		echo  "<th>".$header."</th>";
	}
	?>
	</tr>
	<?php
	while($Data = mysql_fetch_row($Query)) {
	echo "<tr>";
		foreach($Data as $value) 
		{
		echo  "<td>".$value."</td>" ;
		}
	echo "</tr>";
	}
	?>
</table>

<?php exit; ?>


