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
            <?PHP echo "<tr><th colspan='2' >Day Book  ".$stardate." to ".$enddate. "</th></tr>"; ?>
            <tr>
                       <th >Date </th>
                       <th >Particulars</th>
                       <th >VCH Type </th>
                       <th >VCH Number </th>
                       <th >Debit</th>
                        <th >Status </th>
                         <th >Credit </th>
                       <th >Impordate</th>
                        
            </tr>
            <?php
            


                
 //print_r($Data);die;
            $total = 0;
                    foreach($Data as $da):
                        
                        $d = $da['tbl_book'];
                   
                         echo "<tr>";
                                    echo "<td>".$d['date']."</td>";
                                    echo "<td>".$d['Particulars']."</td>";
                                    echo "<td>".$d['VchType']."</td>";
                                    echo "<td>".$d['VchNo']."</td>";
                                    echo "<td>".$d['Debit']."</td>";
                                    echo "<td>".$d['Credit']."</td>";
                                   echo "<td>".$d['Status']."</td>";
                                   echo "<td>".$d['Importdate']."</td>";
                                  $total = $total+$d['Debit'];
                       
                       
        endforeach;
                    echo "</tr>";
                        echo "<tr>";
                        echo "<th>Grand Total</th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<td>".$total."</td>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "</tr>";  
?>
        </table>
                                </div>

        <?php } ?>

