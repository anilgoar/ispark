<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom table-fixed" >
                <thead>
                    
                    
                    
                    
                
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Devices Name</th>
<th>Owner</th>
<th>working</th>
<th>Not working</th>
<th>Damage</th>
<th>Stand by</th>
<th>Stand by but notworking</th>
<th>Remarks</th>


                        
                	</tr>
				</thead>
                <tbody>
                <?php 
     //  print_r($data);die;
                 
					 for($i =0;$i<=61;$i++){
  //print_r($select1[$i]['hardware']);die;
                                             if($i%2==0)
                                                $owner = "Mas";
                                             else
                                                 $owner = "vendor";
					 echo "<tr >";
					 	echo "<td>".$i."</td>";
						echo "<td >".$data[$i]."<input type='hidden' name ='devicename$i' value='$data[$i]' style ='width:80px'></td>";
                                                echo "<td><input type='text' name='Owner$i' value='$owner' readonly=''></td>";
						echo "<td><input type='text' name ='Working$i' value ='{$DataVal[$i]['working']}' style ='width:80px' onKeyPress='return checkNumber(this.value,event)' ></td>";
						echo "<td><input type='text' name ='Notworking$i' value ='{$DataVal[$i]['Notworking']}' style ='width:80px' onKeyPress='return checkNumber(this.value,event)' ></td>";
						echo "<td><input type='text' name ='Damage$i' value ='{$DataVal[$i]['Damage']}' style ='width:80px' ></td>";
                                                echo "<td><input type='text' name ='Standby$i' value ='{$DataVal[$i]['Standby']}' style ='width:80px' ></td>";
                                               echo "<td ><input type='text' name ='StandByNet$i' value ='{$DataVal[$i]['StandByNet']}' style ='width:80px' ></td>";
						echo "<td ><input type='text' name ='remarks$i' value ='{$DataVal[$i]['remarks']}' style ='width:80px' ></td>";
					 echo "</tr>"; 
                                         }
				?>
               
                </tbody>
				</table>