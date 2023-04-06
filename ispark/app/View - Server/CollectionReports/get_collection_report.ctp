<?php //print_r($result1); 
if($type == 'export')
{
	$fileName = "CollectionReport";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
	$class = "border = \"1\"";	
}
else
{$class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";}
//print_r($query);
if($report == 'amt_wise')
{ 
?>

<table <?=$class?> style="font-size: 80%">
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
//echo $post['tab']['other_deduction_bill']; exit;
    $value = $post['tab']['branch_name'].'@@'.$post['tab']['ChequeNo'].'@@'.$post['tab']['Dates'].'@@'.$post['tab']['ChequeAmount'];
    ?>
	<tr>
        <td><?=$post['tab']['branch_name']?></td>
        <td><?=$post['tab']['ChequeNo']?></td>
        <td><?=$post['tab']['client']?></td>
        <td><?=$post['tab']['Dates']?></td>
        <td><?=$post['tab']['net_amount']?></td>
        <td><?=$post['tab']['TDS']?></td>
        <td><?=($post['tab']['Other Ded'] + $post['tab2']['other_deduction']+$post['tab']['other_deduction_bill']) ?></td>
        <td><?=$post['tab']['ChequeAmount']?></td>
        <td onclick="myFunction2('<?=$value?>',<?=$i?>)">Details</td>
        <td><div id="result<?=$i++?>"></div></td>
        </tr>
    
<?php

$totalAmount +=  $post['tab']['net_amount'];
$totalTds +=  $post['tab']['TDS'];
$totalOth +=  ($post['tab']['Other Ded']+$post['tab2']['other_deduction']+$post['tab']['other_deduction_bill']);
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

<?php } 
else { ?>
<table <?=$class?>>
	<tr>
        <th>Branch</th>
        <th>Invoice Date</th>
        <th>Invoice No.</th>
        <th>Payment Date</th>
        <th>Amount</th>
        <th>TDS Ded</th>
        <th>Deduction</th>
        <th>Other Ded</th>
        <th>Bill Wise Deduction</th>
        <th>Net Amount</th>
        <th>Cheque No</th>
        <th>Cheque Amount</th>
    </tr>
<?php
$bill_passed = $tds_ded = $deduction = $other_deduction = $net_amount = $ChequeAmount = 0;
$flag = true; $old = $new ='';
foreach($result as $post):
    
    if($flag){$old = $new = $post['tab']['other_deduction'].$post['tab']['ChequeNo'].$post['tab']['ChequeAmount'];}
    else{
            $old =$new;
            $new = $post['tab']['other_deduction'].$post['tab']['ChequeNo'].$post['tab']['ChequeAmount'];
            if($old == $new)
            {
                $post['tab']['other_deduction'] = 0;
            }
    }
    $flag = false;
    echo "<tr>";
        echo "<td>".$post['tab']['branch_name']."</td>";
        echo "<td>".$post['tab']['invoiceDate']."</td>";
        echo "<td>".$post['tab']['bill_no']."</td>";
        $date = date_create($post['tab']['createdate']);
        $date = date_format($date, 'd-M-Y');
        echo "<td>".$date."</td>";
        echo "<td>".$post['tab']['bill_passed']."</td>";
        echo "<td>".$post['tab']['tds_ded']."</td>";
        echo "<td>".$post['tab']['deduction']."</td>";
        echo "<td>".$post['tab']['other_deduction']."</td>";
        echo "<td>".$post['tab']['other_deduction_bill']."</td>";
        echo "<td>".$post['tab']['net_amount']."</td>";
        echo "<td>".$post['tab']['ChequeNo']."</td>";
        echo "<td>".$post['tab']['ChequeAmount']."</td>";
    echo "</tr>";
    
    
    
    $bill_passed += $post['tab']['bill_passed'];
    $tds_ded += $post['tab']['tds_ded'];
    $deduction += $post['tab']['deduction'];
    $other_deduction += $post['tab']['other_deduction'];
    $other_deduction_bill += $post['tab']['other_deduction_bill'];
    $net_amount += $post['tab']['net_amount'];
    //$ChequeAmount += $post['tab']['ChequeAmount'];
    
endforeach;
?>
    <tr>
        <th colspan="4">Total</th>
        <th><?=$bill_passed ?></th>
        <th><?=$tds_ded ?></th>
        <th><?=$deduction ?></th>
        <th><?=$other_deduction ?></th>
        <th><?=$other_deduction_bill ?></th>
        <th><?=$net_amount-$other_deduction-$other_deduction_bill  ?></th>
        <th></th>
        <th></th>
    </tr>
</table>
<?php } ?>
