<?php //print_r($Data); die;?>
<?php //print_r($res); ?>
<?php 
	$fileName = "ExportTotalTime";
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
                		<th>S. No.</th>
                                <th>Employee Name</th>
                               
                    	<th>Branch</th>
                    	
                        <th>Total Time</th>
                     <th>Cost On Branch</th>
                        
                	</tr>
                       
                        
           
           


                

                 <?php $i =1; $case=array('');
              // print_r($find);die;
					 foreach($Data as $post):
//print_r($post);die;                  
 $net = $post['mj']['NetInHand'];
                                         $parsed=explode(':',$post['0']['TotalTime']);
                                        
                                       
                                         $seconds = $parsed[0] * 3600 + $parsed[1] * 60 + $parsed[2];
                                        $cost=round(($net/$H)*$seconds);
                                             //$imagepath=$show.$post['Docfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
                                                echo "<td>".$post['ad']['UserName']."</td>";
                                               echo "<td>".$post['ad']['Branch']."</td>";
						echo "<td>".$post['0']['TotalTime']."</td>";
						echo "<td>".$cost."</td>";
                                                echo "</tr>";
					 endforeach;
//						?>
                      

        </table>
                                </div>

        <?php } ?>

