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
                       
                               
                        <th >Particulars</th>
                        <th >VchType</th>
                        <th >Debit</th>
                        <th >Credit</th>
                        <th >Status </th>
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['tbl_book'];
                         echo "<tr>";
                        echo "<td>".$da[0]['date']."</td>";
                                   
                                    echo "<td>".$d['Particulars']."</td>";
                                    echo "<td>".$d['VchType']."</td>";
                                   echo "<td>".$d['Debit']."</td>";
                                    echo "<td>".$d['Credit']."</td>";
                                   echo "<td>".$d['Status']."</td>";
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>

        <?php } ?>

