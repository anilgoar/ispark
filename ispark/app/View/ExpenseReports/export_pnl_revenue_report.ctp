<?php
    $costArr=array();
    foreach($Provision as $pr)
    {
        $provArr[$pr['pm']['cost_center']]['provision'] =  $pr['pm']['provision'];
        $provArr[$pr['pm']['cost_center']]['process_name'] =  $pr['cm']['process_name'];
        $provArr[$pr['pm']['cost_center']]['Amount'] =  $pr['0']['Amount'];
        $costArr[] = $pr['pm']['cost_center'];
    }
    
    foreach($Direct as $dr)
    {
        $DirectArr[$dr['head']['HeadingDesc']][$dr['cm']['cost_center']] =  $dr['0']['Amount'];
        $Header[] = $dr['head']['HeadingDesc'];
    }
    $Header = array_unique($Header);
    sort($Header);
    foreach($InDirect as $idr)
    {
        $InDirectArr[$idr['head']['HeadingDesc']][$idr['cm']['cost_center']] =  $idr['0']['Amount'];
        $IHeader[] = $idr['head']['HeadingDesc'];
    }
    $IHeader = array_unique($IHeader);
    sort($IHeader);
    
?>
<table border="1">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <?php
                    foreach($costArr as $cost)
                    {
                        echo "<td>".$cost."</td>";
                    }
            ?>
            <th rowspan="2">Total</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <?php
                    foreach($costArr as $cost)
                    {
                        echo "<td>".$provArr[$cost]['process_name']."</td>";
                    }
            ?>
        </tr>
        <tr>
            <th>Revenue</th>
            <th>Amount</th>
            <?php
            $TotProv = 0;
                    foreach($costArr as $cost)
                    {
                        echo "<td>".$provArr[$cost]['provision']."</td>";
                        $TotProv += $provArr[$cost]['provision'];
                    }
            ?>
            <th><?php echo $TotProv;?></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <?php
            
                    foreach($costArr as $cost)
                    {
                        echo "<td>".round(($provArr[$cost]['provision']*100)/($TotProv),2)."%</td>";
                    }
            ?>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        <tr>
            <th>Direct Expense</th>
            <td></td>
        </tr>
        <?php
        $TotDirCost = array();
            foreach($Header as $head)
            {
                echo "<tr>";
                echo "<td>".$head."</td>";
                echo "<td></td>";
                $DirTot = 0;
                foreach($costArr as $cost)
                {
                    echo "<td>".round($DirectArr[$head][$cost])."</td>";
                    $DirTot +=round($DirectArr[$head][$cost]);
                    $TotDirCost[$cost] += round($DirectArr[$head][$cost]);
                }
                
                echo "<td>".$DirTot."</td>";
                echo "</tr>";
            }        
        ?>
        <tr></tr>
        <tr><th>Total Direct Expense</th>
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>".round($TotDirCost[$cost])."</td>";
            }
           ?>
            <th><?php echo round(array_sum($TotDirCost)); ?></th>
        </tr>
        <tr><th>Total Direct Expense%</th> 
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>".round(($TotDirCost[$cost]*100)/$provArr[$cost]['provision'],2)."%</td>";
            }
           ?>
            <th><?php echo round((array_sum($TotDirCost)*100)/$TotProv,2); ?></th>
        </tr>
        <tr></tr>
        <tr>
            <th>InDirect Expense</th>
            <td></td>
        </tr>
        <?php
        $TotInDirCost = array();
            foreach($IHeader as $head)
            {
                echo "<tr>";
                echo "<td>".$head."</td>";
                echo "<td></td>";
                $IDirTot = 0;
                foreach($costArr as $cost)
                {
                    echo "<td>".round($InDirectArr[$head][$cost])."</th>";
                    $IDirTot += round($InDirectArr[$head][$cost]);
                    $TotInDirCost[$cost] += round($InDirectArr[$head][$cost]);
                }
                echo "<td>".$IDirTot."</td>";
                echo "</tr>";
            }        
        ?>
        <tr></tr>
        <tr><th>Total InDirect Expense</th>
            <th></th>
           <?php
            
            foreach($costArr as $cost)
                {
                    echo "<td>".round($TotInDirCost[$cost])."</td>";
                }
           ?>
            <th><?php echo round(array_sum($TotInDirCost)); ?></th>
        </tr>
        <tr><th>Total InDirect Expense%</th> 
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>".round(($TotInDirCost[$cost]*100)/$provArr[$cost]['provision'],2)."%</td>";
            }
           ?>
            <th><?php echo round((array_sum($TotInDirCost)*100)/$TotProv,2); ?></th>
        </tr>
        <tr></tr>
    </tbody>
    
</table>    
<?php

        $fileName = "PNL_Report".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>            
<?php exit; ?>		

		
					
		
           

