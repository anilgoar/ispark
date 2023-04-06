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
           
           
            <?php
            


                

                    foreach($Data as $da):
                        $arr[$da['tbl_book']['Status']][$da['0']['date']] = $da;
                        $date[] = $da['0']['date'];
                    $status[]= $da['tbl_book']['Status'];
                        $d = $da['tbl_book'];
//                         echo "<tr>";
//                        echo "<td>".$da[0]['date']."</td>";
//                                   
//                                    echo "<td>".$d['Particulars']."</td>";
//                                    echo "<td>".$d['VchType']."</td>";
//                                   echo "<td>".$d['Debit']."</td>";
//                                    echo "<td>".$d['Credit']."</td>";
//                                   echo "<td>".$d['Status']."</td>";
//                                   //echo "<td>".$da[0]['Importdate']."</td>";
//                        echo "</tr>";
                       
        endforeach;
        
        $status1=array_unique($status);
        $date1= array_unique($date);
       $count= array_count_values($date1);
       // print_r($arr);die;
      echo "<tr><th colspan='$count' >Day Book  ".$stardate." to ".$enddate. "</th></tr>";
        echo "<tr><th>Status</th>";
        foreach($date1 as $date)
        {
            echo "<th>".$date."</th>";
        }
        echo "<th>Total</th>";
        echo "</tr>";
        
        foreach($status1 as $st)
        {
            $total =0;
            echo "<tr><th>".$st."</th>";
            foreach($date1 as $date)
           { $total =$total+ $arr[$st][$date]['0']['Debit'];
             echo "<td>".$arr[$st][$date]['0']['Debit']."</td>";
             $sumDate[$date] += $arr[$st][$date]['0']['Debit'];
           }
           echo "<th>".$total."</th>";
           echo "</tr>";
        }
        echo "<tr><td>Grand Total</td>";
        foreach($date1 as $date)
        {
            echo "<th>".$sumDate[$date]."</th>";
        }
          echo "<tr>";            
?>
        </table>
                                </div>

        <?php } ?>

