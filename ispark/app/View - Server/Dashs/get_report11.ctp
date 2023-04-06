<?php //print_r($result); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "ExportDataDaysWise";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>

             <?php      
                            if(!empty($Data))
        {                 
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                        <th >Date</th>     
                       <?php foreach($DataNew as $d1): ?>
                              <?php  if( $d1 == 'Process'){ ?>
                        <th >Branch</th>
                        <th >Tower</th>
                              <?php } 
                         elseif($d1 == 'Branch') { ?>
                        <th >Branch</th>
                       
                        <?php } elseif($d1 == 'CostCenter') { ?>
                             <th >Tower</th>
                        <th>process code</th>
                        <th >Process Name</th>
                      <?php  } ?>
                               <?php endforeach; ?>
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


                

                    foreach($Data as $d):
                        //print_r($d);die;
                        foreach($DataNew as $d3):
                        if( $d3 == 'Process'){
                        
            if($flag)
        {   $newBranch = $d['t']['branch'];
        
            if($oldBranch != $newBranch)
            { 


                echo '<tr><th colspan="4" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". $c ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t),2) ."%</td>";
                     echo "<td>". $d2 ."</td>";
                     echo "<td>".round(((100*$d2)/$c),2)."%</td>";

                     echo "<td>".$tidc."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t),2) ."%</td>";
                     echo "<td>". $i ."</td>";
                      echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $to ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t),2) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c)),2) ."%</td>";
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
            $newBranch = $oldBranch = $d['t']['branch'];
        }
        $flag = true;
                        }
        endforeach;
                       $arr_diff[$j] = ROUND(100 - ((($d['0']['dc'] * 100)/($d['0']['cmt'])) + (($d['0']['idc'] * 100)/($d['0']['cmt']))),2);
                      $arr_diff1[$j] = ROUND(100 - ((($d['0']['target_directCost'] * 100)/($d['0']['target'])) + (($d['0']['target_IDC'] * 100)/($d['0']['target'])) ),2);
                      $diff[$k] = $arr_diff1[$j] - $arr_diff[$j];
                        echo "<tr>";
                           echo "<td>".$d['t']['md']."</td>";
                           foreach($DataNew as $di2):
                               if( $di2 == 'Process'){
                                    echo "<td>".substr($d['t']['branch'],0,9)."</td>";
                                    echo "<td>".$d['t']['bp']."</td>";
                               }
                               elseif($di2 == 'Branch') {
                                   echo "<td>".substr($d['t']['branch'],0,9)."</td>";
                               }
                               elseif($di2 == 'CostCenter') {
                                   echo "<td>".$d['t']['bp']."</td>";
                                   echo "<td>".$d['t']['cc']."</td>";
                                   echo "<td>".substr($d['t']['process_name'],0,9)."</td>";
                           
                               }
                           endforeach;
                          
                           
                           
                            echo "<td>".$d['0']['mde']."</td>";
                           echo "<td>".ROUND($d['0']['target'],2)."</td>";
                           echo "<td>".ROUND($d['0']['cmt'],2)."</td>";
                           echo "<td>".ROUND($d['0']['target_directCost'],2)."</td>";
                           echo "<td>".ROUND(($d['0']['target_directCost'] * 100)/($d['0']['target']))."%</td>";
                           echo "<td>".ROUND($d['0']['dc'],2)."</td>";
                           echo "<td>".ROUND(($d['0']['dc'] * 100)/($d['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($d['0']['target_IDC'],2)."</td>";
                           echo "<td>".ROUND(($d['0']['target_IDC'] * 100)/($d['0']['target']),2)."%</td>";
                            echo "<td>".ROUND($d['0']['idc'],2)."</td>";
                            echo "<td>".ROUND(($d['0']['idc'] * 100)/($d['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC']),2)."</td>";
                           echo "<td>".$arr_diff1[$j]."%</td>";
                           echo "<td>".ROUND($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc']),2)."</td>";
                          echo "<td>".$arr_diff[$j]."%</td>";
                           echo "<td>".ROUND(($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC'])) - ($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc'])),2)."</td>";
                           echo "<td>".$diff[$k]."%</td>";
                        echo "</tr>";
                        $k++;
                        $j++;
                         $c = $c + $d['0']['cmt'];
                         $tdc = $tdc + $d['0']['target_directCost'];

                            $d2 = $d2 + $d['0']['dc'];

                            $tidc = $tidc + $d['0']['target_IDC'];

                            $i = $i + $d['0']['idc'];

                            $t= $t+ $d['0']['target'];

                            $to = $to + ROUND($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC']), 2);
                            $op = $op + ROUND($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc']));

                            $tdif = $tdif + ROUND(($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC'])) - ($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc'])));



                            $c1 = $c1 + $d['0']['cmt'];
                            $tdc1 = $tdc1 + $d['0']['target_directCost'];

                            $d21 = $d21 + $d['0']['dc'];

                            $tidc1 = $tidc1 + $d['0']['target_IDC'];

                            $i1 = $i1 + $d['0']['idc'];

                            $t1= $t1+ $d['0']['target'];

                            $to1 = $to1 + ROUND($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC']), 2);
                            $op1 = $op1 + ROUND($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc']));

                            $tdif1 = $tdif1 + ROUND(($d['0']['target']- ($d['0']['target_directCost'] + $d['0']['target_IDC'])) - ($d['0']['cmt'] - ($d['0']['dc'] + $d['0']['idc'])),2);

                            endforeach;
                       if(!empty($ItemIDC)) {    
                foreach($ItemIDC as $Id):
                        //print_r($d);die;
                        foreach($DataNew as $d3):
                        if( $d3 == 'Process'){
                        
            if($flag)
        {   $newBranch = $Id['t']['branch'];
        
            if($oldBranch != $newBranch)
            { 


                echo '<tr><th colspan="4" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". $c ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t),2) ."%</td>";
                     echo "<td>". $d2 ."</td>";
                     echo "<td>".round(((100*$d2)/$c),2)."%</td>";

                     echo "<td>".$tidc."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t),2) ."%</td>";
                     echo "<td>". $i ."</td>";
                      echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $to ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t),2) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c)),2) ."%</td>";
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
            $newBranch = $oldBranch = $Id['t']['branch'];
        }
        $flag = true;
                        }
        endforeach;
                       $arr_diff[$j] = ROUND(100 - ((($Id['0']['dc'] * 100)/($Id['0']['cmt'])) + (($Id['0']['idc'] * 100)/($Id['0']['cmt']))),2);
                      $arr_diff1[$j] = ROUND(100 - ((($Id['0']['target_directCost'] * 100)/($Id['0']['target'])) + (($Id['0']['target_IDC'] * 100)/($Id['0']['target'])) ),2);
                      $diff[$k] = $arr_diff1[$j] - $arr_diff[$j];
                        echo "<tr>";
                           echo "<td>".$Id['t']['md']."</td>";
                           foreach($DataNew as $di2):
                               if( $di2 == 'Process'){
                                    echo "<td>".substr($Id['t']['branch'],0,10)."</td>";
                                    echo "<td>".$Id['t']['bp']."</td>";
                               }
                               elseif($di2 == 'Branch') {
                                   echo "<td>".substr($Id['t']['branch'],0,10)."</td>";
                               }
                               elseif($di2 == 'CostCenter') {
                                   echo "<td>".$Id['t']['bp']."</td>";
                                   echo "<td>".$Id['t']['cc']."</td>";
                                   echo "<td>".substr($Id['t']['process_name'],0,10)."</td>";
                           
                               }
                           endforeach;
                          
                           
                           
                            echo "<td>".$Id['0']['mde']."</td>";
                           echo "<td>".ROUND($Id['0']['target'],2)."</td>";
                           echo "<td>".ROUND($Id['0']['cmt'],2)."</td>";
                           echo "<td>".ROUND($Id['0']['target_directCost'],2)."</td>";
                           echo "<td>".ROUND(($Id['0']['target_directCost'] * 100)/($Id['0']['target']))."%</td>";
                           echo "<td>".ROUND($Id['0']['dc'],2)."</td>";
                           echo "<td>".ROUND(($Id['0']['dc'] * 100)/($Id['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($Id['0']['target_IDC'],2)."</td>";
                           echo "<td>".ROUND(($Id['0']['target_IDC'] * 100)/($Id['0']['target']),2)."%</td>";
                            echo "<td>".ROUND($Id['0']['idc'],2)."</td>";
                            echo "<td>".ROUND(($Id['0']['idc'] * 100)/($Id['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC']),2)."</td>";
                           echo "<td>".$arr_diff1[$j]."%</td>";
                           echo "<td>".ROUND($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc']),2)."</td>";
                          echo "<td>".$arr_diff[$j]."%</td>";
                           echo "<td>".ROUND(($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC'])) - ($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc'])),2)."</td>";
                           echo "<td>".$diff[$k]."%</td>";
                        echo "</tr>";
                        $k++;
                        $j++;
                         $c = $c + $Id['0']['cmt'];
                         $tdc = $tdc + $Id['0']['target_directCost'];

                            $d2 = $d2 + $Id['0']['dc'];

                            $tidc = $tidc + $Id['0']['target_IDC'];

                            $i = $i + $Id['0']['idc'];

                            $t= $t+ $Id['0']['target'];

                            $to = $to + ROUND($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC']), 2);
                            $op = $op + ROUND($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc']));

                            $tdif = $tdif + ROUND(($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC'])) - ($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc'])));



                            $c1 = $c1 + $Id['0']['cmt'];
                            $tdc1 = $tdc1 + $Id['0']['target_directCost'];

                            $d21 = $d21 + $Id['0']['dc'];

                            $tidc1 = $tidc1 + $Id['0']['target_IDC'];

                            $i1 = $i1 + $Id['0']['idc'];

                            $t1= $t1+ $Id['0']['target'];

                            $to1 = $to1 + ROUND($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC']), 2);
                            $op1 = $op1 + ROUND($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc']));

                            $tdif1 = $tdif1 + ROUND(($Id['0']['target']- ($Id['0']['target_directCost'] + $Id['0']['target_IDC'])) - ($Id['0']['cmt'] - ($Id['0']['dc'] + $Id['0']['idc'])),2);

                            endforeach;
                       }
                       
                       
                       //-------------Jaipur---
                       
                       if(!empty($Item)) {    
                foreach($Item as $Im):
                        //print_r($d);die;
                        foreach($DataNew as $d3):
                        if( $d3 == 'Process'){
                        
            if($flag)
        {   $newBranch = $Im['t']['branch'];
        
            if($oldBranch != $newBranch)
            { 


                echo '<tr><th colspan="4" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". $c ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t),2) ."%</td>";
                     echo "<td>". $d2 ."</td>";
                     echo "<td>".round(((100*$d2)/$c),2)."%</td>";

                     echo "<td>".$tidc."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t),2) ."%</td>";
                     echo "<td>". $i ."</td>";
                      echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $to ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t),2) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$i)/$c),2)."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c)),2) ."%</td>";
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
            $newBranch = $oldBranch = $Im['t']['branch'];
        }
        $flag = true;
                        }
        endforeach;
                       $arr_diff[$j] = ROUND(100 - ((($Im['0']['dc'] * 100)/($Im['0']['cmt'])) + (($Im['0']['idc'] * 100)/($Im['0']['cmt']))),2);
                      $arr_diff1[$j] = ROUND(100 - ((($Im['0']['target_directCost'] * 100)/($Im['0']['target'])) + (($Im['0']['target_IDC'] * 100)/($Im['0']['target'])) ),2);
                      $diff[$k] = $arr_diff1[$j] - $arr_diff[$j];
                        echo "<tr>";
                           echo "<td>".$Im['t']['md']."</td>";
                           foreach($DataNew as $di2):
                               if( $di2 == 'Process'){
                                    echo "<td>".substr($Im['t']['branch'],0,10)."</td>";
                                    echo "<td>".$Im['t']['bp']."</td>";
                               }
                               elseif($di2 == 'Branch') {
                                   echo "<td>".substr($Im['t']['branch'],0,10)."</td>";
                               }
                               elseif($di2 == 'CostCenter') {
                                   echo "<td>".$Im['t']['bp']."</td>";
                                   echo "<td>".$Im['t']['cc']."</td>";
                                   echo "<td>".substr($Im['t']['process_name'],0,10)."</td>";
                           
                               }
                           endforeach;
                          
                           
                           
                            echo "<td>".$Im['0']['mde']."</td>";
                           echo "<td>".ROUND($Im['0']['target'],2)."</td>";
                           echo "<td>".ROUND($Im['0']['cmt'],2)."</td>";
                           echo "<td>".ROUND($Im['0']['target_directCost'],2)."</td>";
                           echo "<td>".ROUND(($Im['0']['target_directCost'] * 100)/($Im['0']['target']))."%</td>";
                           echo "<td>".ROUND($Im['0']['dc'],2)."</td>";
                           echo "<td>".ROUND(($Im['0']['dc'] * 100)/($Im['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($Im['0']['target_IDC'],2)."</td>";
                           echo "<td>".ROUND(($Im['0']['target_IDC'] * 100)/($Im['0']['target']),2)."%</td>";
                            echo "<td>".ROUND($Im['0']['idc'],2)."</td>";
                            echo "<td>".ROUND(($Im['0']['idc'] * 100)/($Im['0']['cmt']),2)."%</td>";
                           echo "<td>".ROUND($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC']),2)."</td>";
                           echo "<td>".$arr_diff1[$j]."%</td>";
                           echo "<td>".ROUND($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc']),2)."</td>";
                          echo "<td>".$arr_diff[$j]."%</td>";
                           echo "<td>".ROUND(($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC'])) - ($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc'])),2)."</td>";
                           echo "<td>".$diff[$k]."%</td>";
                        echo "</tr>";
                        $k++;
                        $j++;
                         $c = $c + $Im['0']['cmt'];
                         $tdc = $tdc + $Im['0']['target_directCost'];

                            $d2 = $d2 + $Im['0']['dc'];

                            $tidc = $tidc + $Im['0']['target_IDC'];

                            $i = $i + $Im['0']['idc'];

                            $t= $t+ $Im['0']['target'];

                            $to = $to + ROUND($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC']), 2);
                            $op = $op + ROUND($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc']));

                            $tdif = $tdif + ROUND(($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC'])) - ($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc'])));



                            $c1 = $c1 + $Im['0']['cmt'];
                            $tdc1 = $tdc1 + $Im['0']['target_directCost'];

                            $d21 = $d21 + $Im['0']['dc'];

                            $tidc1 = $tidc1 + $Im['0']['target_IDC'];

                            $i1 = $i1 + $Im['0']['idc'];

                            $t1= $t1+ $Im['0']['target'];

                            $to1 = $to1 + ROUND($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC']), 2);
                            $op1 = $op1 + ROUND($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc']));

                            $tdif1 = $tdif1 + ROUND(($Im['0']['target']- ($Im['0']['target_directCost'] + $Im['0']['target_IDC'])) - ($Im['0']['cmt'] - ($Im['0']['dc'] + $Im['0']['idc'])),2);

                            endforeach;
                       }
                       
                       
                       ///-------------------------------------------------
                       
                       
                    $per = $per * 100;
                    foreach($DataNew as $d4):
                        if( $d4 == 'Process'){
                echo '<tr><th colspan="4" >TOTAL</th>';
                echo "<td>".$t."</td>";

                     echo "<td>". ROUND($c,2) ."</td>";
                        echo "<td>".$tdc."</td>";
        echo "<td>".Round(((100 * $tdc)/ $t)) ."%</td>";
                     echo "<td>". ROUND($d2,2) ."</td>";
                     echo "<td>".round(((100*$d2)/$c))."%</td>";

                     echo "<td>".ROUND($tidc,2)."</td>";
                     echo "<td>".Round(((100 * $tidc)/ $t)) ."%</td>";
                     echo "<td>". ROUND($i,2) ."</td>";
                      echo "<td>".round(((100*$i)/$c))."%</td>";
                     echo "<td>". ROUND($to,2) ."</td>";
                     echo "<td>".Round(((100 * $to)/ $t),2) ."%</td>";
                     echo "<td>".$op."</td>";
                     echo "<td>".round(((100*$op)/$c),2)."%</td>";
                     echo "<td>". $tdif ."</td>";
                     echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c)),2) ."%</td>";
        echo '</tr>';
                        }
                        endforeach;
                        
                        
                        
                       echo'<tr>';
       foreach($DataNew as $d5):
           if( $d5 == 'Process'){
               echo '<th colspan="4" >Grand TOTAL</th>';
           }
          elseif($d5 == 'Branch') {
               echo '<th colspan="3" >Grand TOTAL</th>';
           }
           elseif($d5 == 'CostCenter') {
                echo '<th colspan="5" >Grand TOTAL</th>';
           }
       endforeach;
                      

                     echo "<td>".$t1."</td>";

                     echo "<td>". $c1 ."</td>";
                        echo "<td>".$tdc1."</td>";
        echo "<td>".Round(((100 * $tdc1)/ $t1),2) ."%</td>";
                     echo "<td>". $d21 ."</td>";
                      echo "<td>".round(((100*$d21)/$c1),2)."%</td>";
                     echo "<td>".ROUND($tidc1,2)."</td>";
        echo "<td>".Round(((100 * $tidc1)/ $t1),2) ."%</td>";
                     echo "<td>". ROUND($i1,2) ."</td>";
        echo "<td>".round(((100*$i1)/$c1),2)."%</td>";
                     echo "<td>". ROUND($to1,2) ."</td>";
        echo "<td>".Round(((100 * $to1)/ $t1),2) ."%</td>";
                     echo "<td>".ROUND($op1,2)."</td>";
        echo "<td>".round(((100*$op1)/$c1),2)."%</td>";
                     echo "<td>". ROUND($tdif1,2) ."</td>";
                    echo "<td>". round((((100 * $to1)/ $t1)-((100*$op1)/$c1)),2) ."%</td>";
                    echo "</tr>";
         ?>


        </table>
                                </div>

        <?php } ?>

