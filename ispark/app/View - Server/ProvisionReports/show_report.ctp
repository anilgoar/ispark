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
        <th>Provision</th>
        <th>Bill Raised</th>
        <th>Provision Balance</th>
        <th>Action Date</th>
        <th>Acton Remarks</th>
    </tr>
    <?php
    $TotalPro = $TotalBill = $TotalProBal = 0;
    foreach($data as $d):
    
    echo "<tr>";
        echo "<td>".$d['cm2']['OPBranch']."</td>";
        echo "<td>".$d['pm']['month']."</td>";
        echo "<td>".$d['pm']['cost_center']."</td>";
        echo "<td>".$d[0]['provision']."</td>";
        $Arr = explode(',',$d[0]['billRaised']);
        echo "<td>";
        foreach($Arr as $v)
        {
            echo $v."<BR>";
            $TotalBill += $v;
        }
        echo "</td>";
        
        echo "<td>".$d[0]['balance']."</td>";
        
        $Arr = explode(',',$d[0]['ActionDate']);
        echo "<td>";
        foreach($Arr as $v)
        {
            echo $v."<BR>";
        }
        echo "</td>";
        $Arr = explode(',',$d[0]['ActionRemarks']);
        echo "<td>";
        foreach($Arr as $v)
        {
            echo $v."<BR>";
        }
        echo "</td>";
    echo "</tr>";    
    
    $TotalPro +=$d[0]['provision'];
    $TotalProBal += $d[0]['balance'];
    endforeach;
    
    foreach($data1 as $d):
    
    echo "<tr>";
        echo "<td>".$d['cm2']['OPBranch']."</td>";
        echo "<td>".$d['pp']['FinanceMonth']."</td>";
        echo "<td>".$d['pp']['Cost_Center_OutSource']."</td>";
        echo "<td>".$d['0']['outsource_amt']."</td>";
        
        echo "<td>";
        
        echo "</td>";
        
        echo "<td>".$d['0']['outsource_amt']."</td>";
        
        
        echo "<td>";
        
        echo "</td>";
        
        echo "<td>";
        echo "Branch Out-Source";
        echo "</td>";
    echo "</tr>";    
    
    $TotalPro +=$d['0']['outsource_amt'];
    $TotalProBal += $d['0']['outsource_amt'];
    endforeach;
    
    echo "<tr><th colspan='3'>Total</th>";
    echo "<th>".$TotalPro."</th>";
    echo "<th>".$TotalBill."</th>";
    echo "<th>".$TotalProBal."</th>";
    echo "<td colspan='2'></td>";
    echo "</tr>";
    }
    ?>
</table>