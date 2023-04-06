<?php //print_r($result); ?>
<?php //print_r($res); ?>

<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id='table_id'>
    <thead>
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
                <th>KKC</th>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
		<th>G. Total</th>
		<th>Remarks</th>
		<th>invoiceDate</th>
                <th>PO Status</th>
                <th>GRN Status</th>
                <th>Payment Status</th>
                <th>Deducton</th>
                <th>Bill Deducton</th>
                <th>Other Deducton</th>
	</tr>
    </thead>    
	<?php
		$i =1;$total=0;$tax =$sbctax =$krishitax = 0;$grnd = 0; $other_disp = array();
		foreach($res as $post) :
	?>
    <tbody>
		<tr>
			<td><?= $i++ ?></td>
			<td><?= $post['t2']['company_name'] ?></td>
			<td><?= $post['t1']['branch_name'] ?></td>
			<td><?= $post['t1']['cost_center'] ?></td>
			<td><?= $post['t1']['bill_no'] ?></td>
			<td><?= $post['t1']['finance_year'] ?></td>
			<td><?= $post['t1']['month'] ?></td>
			<td><?= $post['t1']['total'] ?></td>
			<td><?= $post['t1']['tax'] ?></td>
                        <td><?= $post['t1']['sbctax'] ?></td>
                        <td><?= $post['t1']['krishi_tax'] ?></td>
                        <td><?= $post['t1']['igst'] ?></td>
                        <td><?= $post['t1']['cgst'] ?></td>
                        <td><?= $post['t1']['sgst'] ?></td>
			<td><?= $post['t1']['grnd'] ?></td>
			<td><?= $post['t1']['invoiceDescription'] ?></td>
			<td>
			<?php $date = date_create($post['t1']['invoiceDate']);
				echo date_format($date,"d-m-Y");
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
    </tbody>
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
		<td><?=$total ?></td>
		<td><?=$tax ?></td>
                <td><?=$sbctax ?></td>
                <td><?=$krishitax ?></td>
                <td><?=$igst ?></td>
                <td><?=$cgst ?></td>
                <td><?=$sgst ?></td>
		<td><?=$grnd ?></td>
		<td colspan = "5"></td>
                <td><?=$deduction ?></td>
                <td><?=$bill_deduction ?></td>
                <td><?=$other_deduction ?></td>
	</tr>
	
</table>


