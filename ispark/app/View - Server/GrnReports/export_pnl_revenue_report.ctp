<?php
    $costArr=array($Scost);
    
    //print_r($costArr); exit;
    
    foreach($Provision as $pr)
    {
        $provArr[$pr['pm']['cost_center']]['provision'] =  round($pr['pm']['provision'],2)+round($pr['pm']['out_source_amt'],2);
        $provArr[$pr['pm']['cost_center']]['process_name'] =  $pr['cm']['process_name'];
        $provArr[$pr['pm']['cost_center']]['Amount'] =  $pr['0']['Amount'];
        $costArr[] = trim($pr['pm']['cost_center']);
    }
    
    foreach($Direct as $dr)
    {
        $DirectCostCenterArr[] = $dr['cm']['cost_center'];
        $DirectArr[$dr['head']['HeadingDesc']][$dr['cm']['cost_center']] =  $dr['0']['Amount'];
        $Header[] = $dr['head']['HeadingDesc'];
    }
    $Header = array_unique($Header);
    sort($Header);
    foreach($InDirect as $idr)
    {
        $DirectCostCenterArr[] = $idr['cm']['cost_center'];
        $InDirectArr[$idr['head']['HeadingDesc']][$idr['cm']['cost_center']] =  $idr['0']['Amount'];
        $IHeader[] = $idr['head']['HeadingDesc'];
    }
    $IHeader = array_unique($IHeader);
    sort($IHeader);
    
    if(!empty($DirectCostCenterArr))
    {
        $costArr = array_unique(array_merge($costArr,$DirectCostCenterArr));
    }
    
    foreach($Salary as $sm)
    {
        
        $DCSalary[trim($sm['sm']['CostCenter'])][trim($sm['sm']['Type'])] += $sm['sm']['ActualCTC'];
        $MPSalary[$sm['sm']['CostCenter']][$sm['sm']['Type']] += $sm['sm']['MandaysPaid'];
        $IncSalary[$sm['sm']['CostCenter']][$sm['sm']['Type']] += $sm['sm']['Incentive'];
        //$CostSalary[$sm['sm']['CostCenter']] +=$sm['sm']['ActualCTC'];
        $TypeArr[] =  $sm['sm']['Type'];
        $costArrNew[] = trim($sm['sm']['CostCenter']);
        if(strtolower(trim($sm['sm']['Type']))=='agent' || strtolower(trim($sm['sm']['Type']))=='dsc')
        {
            $manPower[trim($sm['sm']['CostCenter'])]  += $sm['sm']['MandaysPaid'];
            $TotmanPower +=  $sm['sm']['MandaysPaid'];
        }
    }
    
    foreach($Deduction as $ded)
    {
        $AmountTransf[$ded['ccctm']['FromCostCenter']] += $ded['ccctm']['FromAmount'];
    }
    
    foreach($Addition as $ad)
    {
        $AmountGet[$ad['ccctm']['ToCostCenter']] += $ad['ccctm']['ToAmount'];
    }
    
    if(!empty($costArrNew))
    {
        $costArr = array_unique(array_merge(array($Scost),$costArr,$costArrNew));
      sort($costArr);  
    }
    //print_r($costArr); exit;
    $TypeArr = array_unique($TypeArr);
?>
<table border="1">
    <thead>
        
        <tr></tr>
        <tr>
            <th><?php echo $Finmonth11; ?></th>
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
            <th>Work Station Utilize</th>
            <?php
            foreach($costArr as $cost)
                    {
                        echo "<td></td>";
                    }
            ?>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th>Seat</th>
            <?php
            foreach($costArr as $cost)
                    {
                        echo "<td></td>";
                    }
            ?>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th>Rate</th>
            <?php
            foreach($costArr as $cost)
                    {
                        echo "<td></td>";
                    }
            ?>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th>Amount</th>
            <?php
            foreach($costArr as $cost)
                    {
                        echo "<td></td>";
                    }
            ?>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th>%</th>
            <?php
            foreach($costArr as $cost)
                    {
                        echo "<td></td>";
                    }
            ?>
            <th></th>
        </tr>
        <tr>
            <th>DC</th>
            <th></th>
            <?php
            foreach($costArr as $cost)
            {
                echo "<td></td>";
            }
            ?>
            <th></th>
        </tr>
        
            
            <?php
            foreach($TypeArr as $Type)
            {
                echo "<tr><th></th><td>".$Type."</td>";
                $flag2 = true; $TotSalary = 0;
                foreach($costArr as $cost)
                {
                    
                    if(($cost==$Scost && strtolower($Type)=='bmc') || $flag1 )
                    {
                        $flag1 = true;
                        
                        if($flag2)
                        {
                            $TotalBoCostDiv = $DCSalary[$cost][$Type]-$AmountTransf[$cost]+$AmountGet[$cost];
                            echo "<td>".round($DCSalary[$cost][$Type]-$AmountTransf[$cost]+$AmountGet[$cost])."</td>";
                            $flag2 =false;
                            $TotSalary += 0;
                        }
                        else
                        {
                            echo "<td>".round($DCSalary[$cost][$Type]-$AmountTransf[$cost]+$AmountGet[$cost]+($TotalBoCostDiv*($manPower[$cost]/$TotmanPower)))."</td>";
                            $TotSalary +=round($DCSalary[$cost][$Type]-$AmountTransf[$cost]+$AmountGet[$cost]+($TotalBoCostDiv*($manPower[$cost]/$TotmanPower)));
                            $CostSalary[$cost] += round($DCSalary[$cost][$Type]-$AmountTransf[$cost]+$AmountGet[$cost]+($TotalBoCostDiv*($manPower[$cost]/$TotmanPower)));
                        }
                    }
                    else
                    {
                        echo "<td>".round($DCSalary[$cost][$Type])."</td>";
                        $TotSalary +=round($DCSalary[$cost][$Type]);
                        $CostSalary[$cost] += round($DCSalary[$cost][$Type]);
                    }
                     
                }
               
                 $flag1 = false;
                 
                echo "<td>".$TotSalary."</td></tr>";
             // print_r($CostSalary); exit; 
            }    
            ?>
        
        
            
            <?php
            foreach($TypeArr as $Type)
            {
                 $Salcont = 0;
                echo "<tr><th></th><td> Count - ".$Type."</td>";
                foreach($costArr as $cost)
                {
                   echo "<td>".round($MPSalary[$cost][$Type])."</td>";
                   $Salcont +=$MPSalary[$cost][$Type];
                }
                echo "<td>".round($Salcont)."</td></tr>";
            }    
            ?>
        
           
            <?php
            foreach($TypeArr as $Type)
            {
                $Inc = 0;
                echo "<tr><th></th><td>Incentive - ".$Type."</td>";
                foreach($costArr as $cost)
                {
                   echo "<td>".round($IncSalary[$cost][$Type])."</td>";
                   $Inc +=$IncSalary[$cost][$Type];
                }
                echo "<td>".round($Inc)."</td></tr>";
            }    
            ?>
        
        <tr>
            <th></th>
            <th>Total</th>
            <?php
                $CostTotalSal= 0;
                foreach($costArr as $cost)
                {
                   echo "<td>".$CostSalary[$cost]."</td>";
                   $CostTotalSal +=$CostSalary[$cost];
                }
               echo "<td>".$CostTotalSal."</td>"; 
               
            ?>
        </tr>
        
        <tr>
            <th></th>
            <th></th>
            <?php
            
                    foreach($costArr as $cost)
                    {
                        echo "<td>".round(($CostSalary[$cost]*100)/($provArr[$cost]['provision']),2)."%</td>";
                        $provTotalPer += $provArr[$cost]['provision'];
                    }
            ?>
            <th><?php echo round(($CostTotalSal*100)/($provTotalPer),2)."%"; ?></th>
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
        <tr><th>Total Cost</th> 
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>";
                    echo $cst = $CostSalary[$cost]+$TotDirCost[$cost]+$TotInDirCost[$cost];
                echo "</td>";
                $TotalCost += $cst;
            }
           ?>
            <th><?php echo $TotalCost;?></th>
        </tr>
        <tr>
            <th>Total Cost%</th> 
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>";
                     $cst = $CostSalary[$cost]+$TotDirCost[$cost]+$TotInDirCost[$cost];
                    echo $pr = round(($cst*100)/$provArr[$cost]['provision']).'%';
                echo "</td>";
            }
           ?>
            <th><?php echo round($TotalCost*100/$TotProv)."%"; ?></th>
        </tr>
        <tr>
            
        </tr>
        <tr>
            <th>Operation Profit</th> 
            <th></th>
           <?php
            
            foreach($costArr as $cost)
            {
                echo "<td>";
                    echo $cstOP =$provArr[$cost]['provision']-$CostSalary[$cost]-$TotDirCost[$cost]-$TotInDirCost[$cost]; 
                echo "</td>";
                $TotalCostOP += $cstOP;
            }
           ?>
            <th><?php echo $TotalCostOP; ?></th>
        </tr>
        
        <tr>
            <th>Operation Profit%</th> 
            <th></th>
           <?php
            foreach($costArr as $cost)
            {
                echo "<td>";
                    $cstOP =$provArr[$cost]['provision']-$CostSalary[$cost]-$TotDirCost[$cost]-$TotInDirCost[$cost]; 
                    echo $pr = round(($cstOP*100)/$provArr[$cost]['provision']).'%';
                echo "</td>";
            }
           ?>
             <th><?php echo round($TotalCostOP*100/$TotProv)."%"; ?></th>
        </tr>
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

		
					
		
           

