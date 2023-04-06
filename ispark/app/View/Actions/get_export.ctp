<?php //print_r($result1); 

	$fileName = "CollectionReport";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
	$class = "border = \"1\"";	

//print_r($query);
if($report == 'amt_wise')
{ 
?>

<table style="font-size: 80%">
	<tr>
        <th>Branch</th>
        <th>Cheque No.</th>
        <th>Client.</th>
        <th>Payment Date</th>
        <th>Gross Realisation</th>
        <th>TDS</th>
        <th>Other Ded</th>
        <th>Cheque Amount</th>
        <th>Details</th>
        <th></th>
    </tr>
<?php 
$flag = false;
        $i =0;
	$totalAmount = 0; $totalTds = 0; $totalOth = 0; $totalNet = 0;
foreach($result as $post): 
    
    if($flag)
{   $newBranch = $post['tab']['branch_name'];
    if($oldBranch != $newBranch)
    {
        echo '<tr><th colspan="4">TOTAL</th>';
        echo "<th>".$totalAmount."</th>";
        echo "<th>".$totalTds."</th>";
        echo "<th>".$totalOth."</th>";
        echo "<th>".$totalNet."</th></tr>";
       $totalAmount = 0; $totalTds = 0; $totalOth = 0; $totalNet = 0;
       $oldBranch = $newBranch;
    }
}
else
{
    $newBranch = $oldBranch = $post['tab']['branch_name'];
}
$flag = true;

    $value = $post['tab']['branch_name'].'@@'.$post['tab']['ChequeNo'].'@@'.$post['tab']['Dates'].'@@'.$post['tab']['ChequeAmount'];
    ?>
	<tr>
        <td><?=$post['tab']['branch_name']?></td>
        <td><?=$post['tab']['ChequeNo']?></td>
        <td><?=$post['tab']['client']?></td>
        <td><?=$post['tab']['Dates']?></td>
        <td><?=$post['tab']['net_amount']?></td>
        <td><?=$post['tab']['TDS']?></td>
        <td><?=($post['tab']['Other Ded'] + $post['tab2']['other_deduction']) ?></td>
        <td><?=$post['tab']['ChequeAmount']?></td>
        <td onclick="myFunction2('<?=$value?>',<?=$i?>)">Details</td>
        <td><div id="result<?=$i++?>"></div></td>
        </tr>
    
<?php

$totalAmount +=  $post['tab']['net_amount'];
$totalTds +=  $post['tab']['TDS'];
$totalOth +=  ($post['tab']['Other Ded']+$post['tab2']['other_deduction']);
$totalNet +=  $post['tab']['ChequeAmount'];
    endforeach; ?>
    
   <tr>
   <th colspan="4">Total</th>
   <th><?=$totalAmount?></th>
   <th><?=$totalTds?></th>
   <th><?=$totalOth?></th>
   <th><?=$totalNet?></th>
   <th colspan="2"></th>
   </tr>
</table>



<?php } ?>
