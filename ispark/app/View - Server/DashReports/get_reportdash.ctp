<?php //print_r($result); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "DataExportDateWise";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>
<table border = "1">
	<th >Date</th>
                        <th >Branch</th>
                        <th >Tower</th>
                        <th >Cost Center</th>
                        <th >Target Month</th>
                        <th >Target Revenue</th>
                        <th >Commit</th>
                        <th >Target DC</th>
                        <th >Target DC%</th>
                        <th >DC</th>
                        <th >DC%</th>
                        <th >Target IDC</th>
                        <th >Target IDC %</th>
                        <th >IDC</th>
                        <th  >IDC%</th>
                        <th  >Target OP</th>
                        <th  >Target OP%</th>
                        <th  >OP</th>
                        <th >OP%</th>
                        <th  >Diff</th>
                        <th >Diff%</th>
        
        
    </tr>
    <?php
     $k=0;
    $j = 0;
    $i1= 0;
    $d21 = 0;
     $c1 = 0;
     $t1 = 0;
     $tdc1 =0;
$tidc1 = 0;
    $to1 = 0;
    $op1 = 0; 
    $tdif1 = 0;
    
    
    
    
    $i= 0;
    $d2 = 0;
     $c = 0;
     $t = 0;
     $tdc =0;
$tidc = 0;
    $to = 0;
    $op = 0; 
    $tdif = 0;
    $flag = false;
    $flag1 = false;
   // print_r($Data1);exit;
            foreach($Data1 as $d):
                
                if($flag)
        {   $newBranch = $d['dfp']['branch'];
            if($oldBranch != $newBranch)
            { 

                $flag1 = TRUE;
                echo '<tr><th colspan="5" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". $c ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t)) ."%</td>";
                     echo "<td>". $d2 ."</td>";
                     echo "<td>".round(((100*$d2)/$c))."%</td>";

                     echo "<td>".$tidc."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t)) ."%</td>";
                     echo "<td>". $i ."</td>";
                      echo "<td>".round(((100*$i)/$c))."%</td>";
                     echo "<td>". $to ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t)) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i)/$c))."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$i)/$c))) ."%</td>";
        echo '</tr>';
                $i= 0;

            $d2 = 0;

             $c = 0;
             $t = 0;

             $tdc =0;

        $tidc = 0;

            $to = 0;
            $op = 0;

            $tdif = 0;

               $oldBranch = $newBranch;
            }
        }
        else
        {
            $newBranch = $oldBranch = $d['dfp']['branch'];
        }
        $flag = true;
                
                 $arr_diff[$j] = ROUND(100 - ((($d['f']['direct_cost'] * 100)/($d['f']['commit'])) + (($d['f']['indirect_cost'] * 100)/($d['f']['commit']))));
                      $arr_diff1[$j] = ROUND(100 - ((($d['f']['target_directCost'] * 100)/($d['f']['target'])) + (($d['f']['target_IDC'] * 100)/($d['f']['target'])) ));
                      $diff[$k] = $arr_diff1[$j] - $arr_diff[$j];
		 echo "<tr>";
                   echo "<td>".$d['f']['crdate']."</td>";
                   echo "<td>".$d['dfp']['branch']."</td>";
                   echo "<td>".$d['dfp']['tower']."</td>";
                   echo "<td>".$d['dfp']['cost_center']."</td>";
                   echo "<td>".$d['f']['target_month']."</td>";
                   echo "<td>".$d['f']['target']."</td>";
                   echo "<td>".$d['f']['commit']."</td>";
                   echo "<td>".$d['f']['target_directCost']."</td>";
                    echo "<td>".ROUND(($d['f']['target_directCost'] * 100)/($d['f']['target']))."%</td>";
                   echo "<td>".$d['f']['direct_cost']."</td>";
                   echo "<td>".ROUND(($d['f']['direct_cost'] * 100)/($d['f']['commit']))."%</td>";
                   echo "<td>".$d['f']['target_IDC']."</td>";
                    echo "<td>".ROUND(($d['f']['target_IDC'] * 100)/($d['f']['target']))."%</td>";
                    echo "<td>".$d['f']['indirect_cost']."</td>";
                    echo "<td>".ROUND(($d['f']['indirect_cost'] * 100)/($d['f']['commit']))."%</td>";
                   echo "<td>".ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']),2)."</td>";
                   echo "<td>".$arr_diff1[$j]."%</td>";
                   echo "<td>".ROUND($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost']),2)."</td>";
                   echo "<td>".$arr_diff[$j]."%</td>";
                   echo "<td>".ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost'])),2)."</td>";
                   echo "<td>".$diff[$k]."%</td>";
                echo "</tr>";
                 $k++;
                $j++;
                 $c = $c + $d['f']['commit'];
                 $tdc = $tdc + $d['f']['target_directCost'];
                    $d2 = $d2 + $d['f']['direct_cost'];
                    $tidc = $tidc + $d['f']['target_IDC'];
                    $i = $i + $d['f']['indirect_cost'];
                    $t= $t+ $d['f']['target'];
                    $to = $to + ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']),2);
                    $op = $op + ROUND($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost']),2);
                    $tdif = $tdif + ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost'])),2);
                    
                    
                    $c1 = $c1 + $d['f']['commit'];
                 $tdc1 = $tdc1 + $d['f']['target_directCost'];
                    $d21 = $d21 + $d['f']['direct_cost'];
                    $tidc1 = $tidc1 + $d['f']['target_IDC'];
                    $i1 = $i1 + $d['f']['indirect_cost'];
                    $t1= $t1+ $d['f']['target'];
                    $to1 = $to1 + ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']),2);
                    $op1 = $op1 + ROUND($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost']),2);
                    $tdif1 = $tdif1 + ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['commit'] - ($d['f']['direct_cost'] + $d['f']['indirect_cost'])),2);
            endforeach;
            if($flag1)
            {
	echo '<tr><th colspan="5" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". $c ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t)) ."%</td>";
                     echo "<td>". $d2 ."</td>";
                     echo "<td>".round(((100*$d2)/$c))."%</td>";

                     echo "<td>".$tidc."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t)) ."%</td>";
                     echo "<td>". $i ."</td>";
                      echo "<td>".round(((100*$i)/$c))."%</td>";
                     echo "<td>". $to ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t)) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i)/$c))."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$i)/$c))) ."%</td>";
            
                     echo '</tr>';
            }
            
            echo '<tr><th colspan="5" >Grand Total</th>';
                echo "<td>".$t1."</td>";

                     echo "<td>". $c1 ."</td>";
                        echo "<td>".$tdc1."</td>";
        echo "<td>".Round(((100 * $tdc1)/ $t1)) ."%</td>";
                     echo "<td>". $d21 ."</td>";
                     echo "<td>".round(((100*$d21)/$c1))."%</td>";

                     echo "<td>".$tidc1."</td>";
                     echo "<td>".Round(((100 * $tidc1)/ $t1)) ."%</td>";
                     echo "<td>". $i1 ."</td>";
                      echo "<td>".round(((100*$i1)/$c1))."%</td>";
                     echo "<td>". $to1 ."</td>";
                     echo "<td>".Round(((100 * $to1)/ $t1)) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i1)/$c1))."%</td>";
                     echo "<td>". $tdif1 ."</td>";
                     echo "<td>". round((((100 * $to1)/ $t1)-((100*$i1)/$c1))) ."%</td>";
            
                     echo '</tr>';
	?>
</table>

