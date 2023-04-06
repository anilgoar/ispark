<?php
if($type=='Export')
{
$fileName = "P&L_Export";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>
                <table border="2" class="table">
                    
                        <?php $i=1; //print_r($PNLreport); exit;
                        
                                foreach($PNLreport as $pnl)
                                {
                                    $costArray[] = $pnl['tab']['ExpenseTypeName']; 
                                    $costName[$pnl['tab']['ExpenseTypeName']] = $pnl['cm2']['CostCenterName']; 
                                    $HeadArray[] = $pnl['tab']['HeadingDesc'];
                                    
                                    $data[$pnl['tab']['HeadingDesc']][$pnl['tab']['ExpenseTypeName']] = $pnl;
                                }
                                //print_r($data); exit;
                                $costArray = array_unique($costArray);
                                $HeadArray = array_unique($HeadArray);
                                
                                echo '<thead><tr><th rowspan="2">S.No.</th><th rowspan="2">Expense Head</th>';
                                
                                foreach($costArray as $cost)
                                {
                                    echo '<th colspan="3">'.$costName[$cost].'</th>';
                                }
                                echo '<th colspan="3">Grand Total</th></tr><tr>';
                                
                                foreach($costArray as $cost)
                                {
                                    echo '<th>Processed</th>';
                                    echo '<th>UnProcessed</th>';
                                    echo '<th>Total</th>';
                                }
                                echo '<th>Total Processed</th><th>Total UnProcessed</th><th>Total_Total</th></tr></thead><tbody>';
                                //print_r($data); 
                                foreach($data as $head=>$v)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$head."</td>";
                                        $rowTotal = 0;
                                        $rowProcessed = 0;
                                        //print_r($v);
                                        foreach($costArray as $cost)
                                        {
                                            $process = !empty($v[$cost]['0']['Processed'])?$v[$cost]['0']['Processed']:0;
                                            $Total =  !empty($v[$cost]['0']['Total'])?$v[$cost]['0']['Total']:0;
                                            echo "<td>".$process.'</td>';
                                            echo "<td>".($Total-$process).'</td>';
                                            echo "<td>".($Total).'</td>';
                                            $rowTotal += $Total;
                                            $rowProcessed += $process;
                                            $dataCostArray[$cost]['Total'] +=  $Total;
                                            $dataCostArray[$cost]['Processed'] +=  $process;
                                        }
                                        echo "<td>".$rowProcessed;
                                        echo "<td>".($rowTotal-$rowProcessed).'</td>';
                                        echo "<td>".($rowTotal).'</td>';
                                    echo "</tr>";
                                }
                                
                                echo '<tr><th colspan="2">Grand Total</th>';
                                foreach($costArray as $cost)
                                {
                                    echo '<th>'.$dataCostArray[$cost]['Processed'].'</th>';
                                    echo '<th>'.($dataCostArray[$cost]['Total']-$dataCostArray[$cost]['Processed']).'</th>';
                                    echo '<th>'.($dataCostArray[$cost]['Total']).'</th>';
                                    $GrandTotalProcessed +=  $dataCostArray[$cost]['Processed'];
                                    $GrandTotalTotal +=  $dataCostArray[$cost]['Total'];
                                }
                                
                                echo '<th>'.$GrandTotalProcessed.'</th>';
                                    echo '<th>'.($GrandTotalTotal-$GrandTotalProcessed).'</th>';
                                    echo '<th>'.($GrandTotalTotal).'</th></tr>';
                        ?>
                    </tbody>
                </table>    
            
		

