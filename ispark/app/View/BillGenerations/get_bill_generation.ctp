<?php 

//print_r($qry);
$class = "class=\"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
 if($type=='export') 
{
	$class = "border = \"1\"";
	
	$fileName = "BillGenerationsEdited";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 		 
}

if($ReportType=='Details')
{
?>

<table <?=$class?> id = "table_id">
<thead>
    <tr>
        <th>S. No.</th>
        <th>Branch Name</th>
        <th>Process Code</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Bill Date</th>
        <th>Billing Month</th>
    </tr>
</thead>
<tbody>
<?php
    $i =1;$total=0;$tax = 0;$grnd = 0;
    foreach($result as $post) :
?>
<tr>
<td><?= $i++ ?></td>
<td><?= $post['t2']['branch_name'] ?></td>
<td><?= $post['t2']['cost_center'] ?></td>
<td><?= $post['t2']['invoiceDescription'] ?></td>
<td><?= $post['t2']['grnd'] ?></td>
<td><?php $date = date_create($post['t2']['invoiceDate']); echo date_format($date , 'd-M-Y'); ?></td>
<td><?= $post['t2']['month'] ?></td>
</tr>
					
<?php
    $grnd += 	$post['t2']['grnd'];
    endforeach;
?>
</tbody>
<tr>
    <th> Total </th>
    <td colspan = "3"> </td>
    <td><?=$grnd ?></td>
    <td colspan = "2"></td>
</tr>
</table>

		
<?php } else { ?>
<table <?=$class?> id = "table_id1">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Branch</th>
        <th>Process Code</th>
        <th>Amount</th>
    </tr>
</thead>
<tbody>
<?php 
$i=1; 
$summtotal = 0;
    foreach($result2 as $post): ?>
    <tr>
        <td><?=$i++ ?></td>
        <td><?=$post['t2']['branch_name']?></td>
        <td><?=$post['t2']['cost_center']?></td>
        <td><?=$post['0']['amount']?></td>
    </tr>
<? 
$summtotal += $post['0']['amount'];
endforeach;
?>
</tbody>
<tr>
<th>Total</th>
<td colspan="2"></td>
<td><?=$summtotal?></td>
</tr>
</table>
<?php } exit; ?>
