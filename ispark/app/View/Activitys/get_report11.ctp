<?php //print_r($Data); die;?>
<?php //print_r($res); ?>
<?php 
	$fileName = "ExportTimesheet";
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
                                <th>Date</th>
                    	<th>Branch</th>
                    	<th>Group</th>
                    	<th>Client</th>
                    	<th>Project</th>
                    	<th>Module</th>
                        <th>Activity</th>
                        <th>Time</th>
                        <th>Remarks</th>
                        
                	</tr>
                       
                        
           
           


                

                 <?php $i =1; $case=array('');
              // print_r($find);die;
					 foreach($Data as $post):
//print_r($post);die;                   
                                             //$imagepath=$show.$post['Docfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
                                                echo "<td>".$post['Act']['UserName']."</td>";
                                                echo "<td>".$post['Act']['DataDate']."</td>";
						echo "<td>".$post['Act']['Branch']."</td>";
						echo "<td>".$post['Act']['Group']."</td>";
						echo "<td>".$post['Act']['Client']."</td>";
						echo "<td>".$post['Act']['Project']."</td>";
                                                echo "<td>".$post['Act']['Module']."</td>";
						echo "<td>".$post['Act']['Activity']."</td>";
                                                 echo "<td>".$post['Act']['SpentTime']."</td>";
						echo "<td>".$post['Act']['Remarks']."</td>";
                                                echo "</tr>";
					 endforeach;
//						?>
                      

        </table>
                                </div>

        <?php } ?>

