<?php //print_r($result); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "ExportDataDayBook";
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
            <?PHP echo "<tr><th colspan='2' >Day Book  ".$stardate." to ".$enddate. "</th></tr>"; ?>
            <tr>
                       
                        <th >Status </th>
                       <th >Debit</th>
                        <th >Budget</th>
                        <th >Balance</th>
                        
            </tr>
            <?php
            


                
 //print_r($Data);die;
            $total = 0;
            $totalbud =0;
            $totalbal =0;
                    foreach($Data as $da):
                        
                        $d = $da['tbl_book'];
                   
                         echo "<tr>";
                       
                                   echo "<td>".$da['tb']['Status']."</td>";
                                   echo "<td>".$da['0']['Debit']."</td>";
                                   echo "<td>".$da['ff']['Budget']."</td>";
                                   echo "<td>".($da['ff']['Budget']-$da['0']['Debit'])."</td>";
                                   $totalbud =$totalbud+$da['ff']['Budget'];
                                  $total= $total+$da['0']['Debit'];
                                  $totalbal=$totalbal+($da['ff']['Budget']-$da['0']['Debit']);
                       
        endforeach;
                    echo "</tr>";
                        echo "<tr>";
                        echo "<td>Grand Total</td>";
                        echo "<td>".$total."</td>";
                        echo "<td>".$totalbud."</td>";
                        echo "<td>".$totalbal."</td>";
                        echo "</tr>";  
?>
        </table>
                                </div>

        <?php } ?>

