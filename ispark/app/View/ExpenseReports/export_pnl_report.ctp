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
                                    $costName[$pnl['tab']['ExpenseTypeName']] = $pnl['tab']['ExpenseTypeName']; 
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
                                            echo "<td>".round($process,2).'</td>';
                                            echo "<td>".round($Total-$process,2).'</td>';
                                            echo "<td>".round($Total,2).'</td>';
                                            $rowTotal += $Total;
                                            $rowProcessed += round($process,2);
                                            $dataCostArray[$cost]['Total'] +=  round($Total,2);
                                            $dataCostArray[$cost]['Processed'] +=  round($process,2);
                                        }
                                        echo "<td>".round($rowProcessed,2).'</td>';
                                        echo "<td>".round($rowTotal-$rowProcessed,2).'</td>';
                                        echo "<td>".round($rowTotal,2).'</td>';
                                    echo "</tr>";
                                }
                                
                                echo '<tr><th colspan="2">Grand Total</th>';
                                foreach($costArray as $cost)
                                {
                                    echo '<th>'.round($dataCostArray[$cost]['Processed'],2).'</th>';
                                    echo '<th>'.round($dataCostArray[$cost]['Total']-$dataCostArray[$cost]['Processed'],2).'</th>';
                                    echo '<th>'.round($dataCostArray[$cost]['Total'],2).'</th>';
                                    $GrandTotalProcessed +=  round($dataCostArray[$cost]['Processed'],2);
                                    $GrandTotalTotal +=  round($dataCostArray[$cost]['Total'],2);
                                }
                                
                                echo '<th>'.round($GrandTotalProcessed,2).'</th>';
                                    echo '<th>'.round($GrandTotalTotal-$GrandTotalProcessed,2).'</th>';
                                    echo '<th>'.round($GrandTotalTotal,2).'</th></tr>';
                        ?>
                    </tbody>
                </table>    
            
		

