<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <th>Sr No.</th>
        <th>Particulars</th>
        <th>Qty.</th>
        <th>Rate</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
<?php
$i=1;

foreach($data as $k=>$v)
{
    echo "<tr><td>".$i."</td>";
    echo "<td>".$v['TmpCostParticular']['remarks']."</td>";
    echo "<td>".$v['TmpCostParticular']['qty']."</td>";
    echo "<td>".$v['TmpCostParticular']['rate']."</td>";
    echo "<td>".$v['TmpCostParticular']['total']."</td>";
    echo "<td><div onClick=\"delTmpCost(".$v['TmpCostParticular']['Id'].",'".$v['TmpCostParticular']['revenueType']."')\">Delete</div></td></tr>";
    $i++;
}

?>
</table>