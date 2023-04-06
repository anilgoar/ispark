<?php //print_r($data);
$class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
if($view=='Export')
{
  $fileName = "ProvisionReport";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");  
        $class = "border='1'";
}

?>
<table <?php echo $class; ?>>
    <?php 
    if(empty($data))
    {
        echo "<tr><th>No Record Found</th></tr>";
    }
    else
    {
    ?>
        <tr>
            <th>Branch</th>
            <th>Month</th>
            <th>Cost Center</th>
           
            <th>Old Provision</th>
            <th>New Provision</th>
            <th>Difference</th>
            <th>Remarks</th>
            <th>Request By</th>
        </tr>
        <?php
        $TotalPro = $TotalBill = $TotalProBal = 0;
        foreach($data as $d)
        {
            echo "<tr>";
                echo "<td>".$d['cm2']['OPBranch']."</td>";
                echo "<td>".$d['pm']['month']."</td>";
                echo "<td>".$d['pm']['cost_center']."</td>";
                
                echo "<td>".$d['pm']['old_provision']."</td>";
                echo "<td>".$d['pm']['provision']."</td>";
                echo "<td>".($d['pm']['provision']-$d['pm']['old_provision'])."</td>";
                echo "<td>".$d['pm']['remarks']."</td>";
                echo "<td>".$d['tu']['username']."</td>";
            echo "</tr>";    

            $TotalPro +=$d[0]['provision'];
            $TotalProBal += $d[0]['balance'];
        }
    
    }
    ?>
</table>