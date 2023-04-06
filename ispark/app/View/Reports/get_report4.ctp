<?php //print_r($result); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "BillReportDaysWise";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>
<table border = "1">
	<tr>
		<th>S. No.</th>
		<th>Company</th>
		<th>Branch</th>
		<th>Cost Centre Code</th>
		<th>Invoice No.</th>
		<th>Finance Year</th>
		<th>Month Date</th>
		<th>Amount</th>
		<th>S. Tax</th>
                <th>SBC</th>
                <th>Krishi</th>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
		<th>G. Total</th>
		<th>Remarks</th>
		<th>Invoice Date</th>
                <th>PO Status</th>
                <th>GRN Status</th>
                <th>Payment Status</th>
                <th>Deducton</th>
                <th>Bill Deducton</th>
                <th>Other Deducton</th>
	</tr>
	<?php
		$i =1;$total=0;$tax =$sbctax =$krishitax = 0;$grnd = 0; $other_disp = array();
		foreach($res as $post) :
	?>
		<tr>
			<td><?= $i++ ?></td>
			<td><?= $post['t2']['company_name'] ?></td>
			<td><?= $post['t1']['branch_name'] ?></td>
			<td><?= $post['t1']['cost_center'] ?></td>
			<td><?= $post['t1']['bill_no'] ?></td>
			<td><?= $post['t1']['finance_year'] ?></td>
			<td><?= "=text(\"".$post['t1']['month']."\",\"mmm-yy\")" ?></td>
			<td><?= $post['t1']['total'] ?></td>
			<td><?= $post['t1']['tax'] ?></td>
                        <td><?= $post['t1']['sbctax'] ?></td>
                        <td><?= $post['t1']['krishi_tax'] ?></td>
                        <td><?= $post['t1']['igst'] ?></td>
                        <td><?= $post['t1']['cgst'] ?></td>
                        <td><?= $post['t1']['igst'] ?></td>
			<td><?= $post['t1']['grnd'] ?></td>
			<td><?= $post['t1']['invoiceDescription'] ?></td>
			<td>
			<?php $date = date_create($post['t1']['invoiceDate']);
				echo date_format($date,"d-M-Y");
			 ?>
			 </td>
                         <td><?= $post['0']['po_status'] ?></td>
                         <td><?= $post['0']['grn_status'] ?></td>
                         <td><?= $post['0']['Payment Status'] ?></td>
                         <td><?= $post['t4']['deduction'] ?></td>
                         <td><?= $post['t5']['bill_deduction'] ?></td>
                         <?php if(!in_array(($post['t4']['pay_type'].$post['t4']['pay_no']),$other_disp)) { ?>
                         <td><?php echo $post['t6']['other_deduction']; $other_disp[] = $post['t4']['pay_type'].$post['t4']['pay_no']; ?></td>
                         <?php } else {  ?>
                         <td>0</td>
                         <?php } ?>
		</tr>
	<?php 
		$total += 	$post['t1']['total'];
		$tax += 	$post['t1']['tax'];
                $sbctax += 	$post['t1']['sbctax'];
                $krishitax += 	$post['t1']['krishi_tax'];
                $igst = $post['t1']['igst'];
                $cgst = $post['t1']['cgst'];
                $sgst = $post['t1']['sgst'];
		$grnd += 	$post['t1']['grnd'];
                $deduction += $post['t4']['deduction'];
                $bill_deduction += $post['t5']['bill_deduction'];
                if(!in_array(($post['t4']['pay_type'].$post['t4']['pay_no']),$other_disp)) 
                {
                    $other_deduction += $post['t6']['other_deduction'];
                }
	endforeach; 
	?>
	<tr>
		<th> Total </th>
		<td colspan = "6"> </td>
		<th><?=$total ?></th>
		<th><?=$tax ?></th>
                <th><?=$sbctax ?></th>
                <th><?=$krishitax ?></th>
                <th><?=$igst ?></th>
                <th><?=$cgst ?></th>
                <th><?=$sgst ?></th>
		<th><?=$grnd ?></th>
		<td colspan = "5"></td>
                <td><?=$deduction ?></td>
                <td><?=$bill_deduction ?></td>
                <td><?=$other_deduction ?></td>
	</tr>
	
</table>

