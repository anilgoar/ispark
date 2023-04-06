<?php //print_r($data); exit;
$class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
if($view=='Export')
{
  $fileName = "budget_report";
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
        foreach($data as $d)
        {
            
            $branch_m[$d['cm2']['Branch']]['revenue'] += $d[0]['provision'];
            $branch_arr[] = $d['cm2']['Branch'];
        }
        
        foreach($ExpenseReport as $exp)
        {
            if($exp['0']['bus_status']=='Approved')
            {
                $branch_m[$exp['0']['Branch']][$exp['0']['cost']]['Budget'] += round($exp['0']['Amount'],2);
            }
            else
            {
                //$branch_m[$exp['0']['Branch']][$exp['hm']['Cost']]['Pending'] = round($d['0']['Amount'],2);
                $branch_m[$exp['0']['Branch']]['Pending'] += round($exp['0']['Amount'],2);
            }
            $branch_arr[] = $exp['0']['Branch'];
        }
        
        $branch_arr = array_unique($branch_arr);
       //print_r($branch_m); exit; 
    ?>
    <tr>
        <th colspan="7">BUDGET REPORT FOR THE MONTH - <font color="red"><?php echo $new_date; ?></font></th>
    </tr>
    <tr>    
        <th>Branch</th>
        <th>Revenue</th>
        
        <th>DC</th>
        <th>IDC</th>
        
<!--        <th>DC Pending</th>
        <th>IDC Pending</th>-->
        <th>Pending</th>
        <th>OP</th>
        <th>OP%</th>
    </tr>
    <?php
    $TotalPro = $TotalBill = $TotalProBal = 0;
    foreach($branch_arr as $branchDet)
    {
    
    echo '<tr style="text-align:center">';
        echo '<td>'.$branchDet."</td>";
        echo '<td>'.$branch_m[$branchDet]['revenue']."</td>";
        echo '<td>'.$branch_m[$branchDet]['D']['Budget']."</td>";
        echo '<td>'.$branch_m[$branchDet]['I']['Budget']."</td>";
        
        //echo "<td>".$branch_m[$branchDet]['I']['Budget']."</td>";
        //echo "<td>".$branch_m[$branchDet]['I']['Pending']."</td>";
        echo "<td>".$branch_m[$branchDet]['Pending']."</td>";
        
        echo "<td>";
            echo round($branch_m[$branchDet]['revenue']-$branch_m[$branchDet]['D']['Budget']-$branch_m[$branchDet]['I']['Budget']-$branch_m[$branchDet]['Pending']);
        echo "</td>";
            
        echo "<td>";
            echo round(($branch_m[$branchDet]['revenue']-$branch_m[$branchDet]['D']['Budget']-$branch_m[$branchDet]['I']['Budget']-$branch_m[$branchDet]['Pending'])*100/$branch_m[$branchDet]['revenue']);
        echo "%</td>";    
        
        $grand_total['revenue'] += $branch_m[$branchDet]['revenue'];
        $grand_total['D']['Budget'] += $branch_m[$branchDet]['D']['Budget'];
        $grand_total['I']['Budget'] += $branch_m[$branchDet]['I']['Budget'];
        $grand_total['Pending'] += $branch_m[$branchDet]['Pending'];
        
    echo "</tr>";    
    
    
    }
    
    
    
    echo "<tr><th colspan='1'>Total</th>";
    echo "<th>".$grand_total['revenue']."</th>";
    echo "<th>".$grand_total['D']['Budget']."</th>";
    echo "<th>".$grand_total['I']['Budget']."</th>";
    echo "<th>".$grand_total['Pending']."</th>";
    echo "<th>";
            echo round($grand_total['revenue']-$grand_total['D']['Budget']-$grand_total['I']['Budget']-$grand_total['Pending']);
        echo "</th>";
            
        echo "<th>";
            echo round(($grand_total['revenue']-$grand_total['D']['Budget']-$grand_total['I']['Budget']-$grand_total['Pending'])*100/$branch_m[$branchDet]['revenue']);
        echo "%</th>";    
    echo "</tr>";
    }
    ?>
</table>