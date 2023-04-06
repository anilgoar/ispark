<?php //print_r($Data); die;?>
<?php //print_r($res); ?>
<?php 
	

?>

             <?php   if($rrt == 'Output'){ 
                 $fileName = "ExportGSTOutputReport";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
                            if(!empty($Data))
        {                 
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
          
                        <tr>                	
                		<th style="background-color:#0F0C36; color:#FFFFFF">S. No.</th>
                                <th style="background-color:#0F0C36; color:#FFFFFF">Branch Name</th>
                                <th style="background-color:#0F0C36; color:#FFFFFF">Total Value</th>
                    	<th style="background-color:#0F0C36; color:#FFFFFF">IGST Amount</th>
                    	<th style="background-color:#0F0C36; color:#FFFFFF">IGST</th>
                    	<th style="background-color:#0F0C36; color:#FFFFFF">SGST Amount</th>
                    	<th style="background-color:#0F0C36; color:#FFFFFF">SGST</th>
                    	<th style="background-color:#0F0C36; color:#FFFFFF">CGST</th>
                        <th style="background-color:#0F0C36; color:#FFFFFF">TOtal</th>
                       
                        
                	</tr>
                       
                        
           
           


                

                 <?php $i =1; $case=array('');
              // print_r($find);die;
					 foreach($Data as $post):
                   $tatal=$post['0']['igtsamount']+$post['0']['igst']+$post['0']['sgtsamount']+$post['0']['sgst']+$post['0']['cgst'];
                                             //$imagepath=$show.$post['Docfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
                                                echo "<td>".$post['tbl_invoice']['branch_name']."</td>";
                                                echo "<td>".$post['0']['Tammount']."</td>";
						echo "<td>".$post['0']['igtsamount']."</td>";
						echo "<td>".$post['0']['igst']."</td>";
						echo "<td>".$post['0']['sgtsamount']."</td>";
						echo "<td>".$post['0']['sgst']."</td>";
                                                echo "<td>".$post['0']['cgst']."</td>";
						echo "<td>".$tatal."</td>";
                                                 
                                                echo "</tr>";
					 endforeach;
//						?>
                      

        </table>
                                </div>

             <?php } }
             
             else if($rrt=='Input'){
$fileName = "ExportGSTInputReport";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
                 
                 
               foreach($BranchMaster as $bm1)
                        {
                                       
                            foreach($VendorMasterNew as $vm1)
                            {  
                                
                               $rate[]= $dataX[$bm1][$vm1]['Rate'];
//                                echo '<th>Amount for'.$rate.'</th>';
//                                echo '<th>'.$rate.'</th>';
                            }
                        } 
 $rate = array_unique($rate);
if(!empty($dataX))
{
?>


                <table border="1">
                    <thead>
                        <tr>
                            <th style="background-color:#0F0C36; color:#FFFFFF">S.No.</th>
                            <th style="background-color:#0F0C36; color:#FFFFFF">Branch</th>
                           
                           
                            <th style="background-color:#0F0C36; color:#FFFFFF">Amount</th>
                           <?php  
                                       
                            foreach($rate as $rm1)
                            {  
                                
                                //$rate=$dataX[$bm1][$vm1]['Rate'];
                                echo '<th style="background-color:#0F0C36; color:#FFFFFF">Amount for '.$rm1.'%</th>';
                               ?>
                            <th style="background-color:#0F0C36; color:#FFFFFF">IGST</th>
                            
                            
                            <th style="background-color:#0F0C36; color:#FFFFFF">SGST</th>
                            <th style="background-color:#0F0C36; color:#FFFFFF">CGST</th>
                            <?php
                            }
                         ?>
                            
                            
                            <th style="background-color:#0F0C36; color:#FFFFFF">Total</th>
                             <th style="background-color:#0F0C36; color:#FFFFFF">TDS</th>
                            <th style="background-color:#0F0C36; color:#FFFFFF">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $GrandTotal = $tdsn = $igst = $sgst = $cgst = $amount = $Total=0;//print_r($ExpenseReport);$amo
                        foreach($BranchMaster as $bm)
                        { echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$bm."</td>";
                            foreach($VendorMasterNew as $vm)
                            {    $igst1 = 0; $sgst1 = 0; $cgst1 = 0; 
                                if(!empty($dataX[$bm][$vm]))
                                {
                                   if($bm== $oldbm )
                                       {  $igst1 = round($dataX[$bm][$vm]['IGST'],2);
                                         $sgst1 = round($dataX[$bm][$vm]['SGST'],2); 
                                            $cgst1 = round($dataX[$bm][$vm]['CGST'],2);
                                        $amount1 = round($dataX[$bm][$vm]['Amount'],2);
                                    if($dataX[$bm][$vm]['Rate']==$ort)
                                    {
                                        $newdata[$bm][$dataX[$bm][$vm]['Rate']]=$newdata[$bm][$dataX[$bm][$vm]['Rate']]+$amount1;
                                       
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']+$sgst1;
                                            $newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']+$igst1;
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']+$cgst1;
                                    }
                                    else{
                                        $ort=$dataX[$bm][$vm]['Rate'];
                                                 $newdata[$bm][$dataX[$bm][$vm]['Rate']]=$newdata[$bm][$dataX[$bm][$vm]['Rate']]+$amount1;
                                                $newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']+$sgst1;
                                            $newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']+$igst1;
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']+$cgst1;
                                    }
                                       $newdata[$bm]['tds']=$dataX[$bm][$vm]['TDS'];
                                        
                                        $newdata[$bm]['amt']=$newdata[$bm]['amt']+$amount1; 
                                        
                                       
                                        
                                            
                                            
                                            
                                            $total1 = round($amount1+$igst1+$sgst1+$cgst1,2);
                                        $tds1 = round($dataX[$bm][$vm]['tdsAmount'],2);
                                        $gtotal1 = $total1-$tds1;
                                        $newdata[$bm]['total1']= $newdata[$bm]['total1']+$total1;
                                         $newdata[$bm]['tds1']= $newdata[$bm]['tds1']+$tds1;
                                         $newdata[$bm]['gtotal1']= $newdata[$bm]['gtotal1']+round($gtotal1,2);
                                   
                                       } else{
                                       $tds=  $dataX[$bm][$vm]['TDS'];   $oldbm =$bm;
                                       $newdata[$bm]['tds']=$dataX[$bm][$vm]['TDS'];
                                        $igst1 = round($dataX[$bm][$vm]['IGST'],2);
                                         $sgst1 = round($dataX[$bm][$vm]['SGST'],2); 
                                            $cgst1 = round($dataX[$bm][$vm]['CGST'],2);
                                        $amount1 = round($dataX[$bm][$vm]['Amount'],2);
                                    if($dataX[$bm][$vm]['Rate']==$ort)
                                    {//echo $ort.'<br>';
                                        $newdata[$bm][$dataX[$bm][$vm]['Rate']]=$newdata[$bm][$dataX[$bm][$vm]['Rate']]+$amount1;
                                       
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']+$sgst1;
                                            $newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']+$igst1;
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']+$cgst1;
                                    }
                                    else{ 
                                        $ort=$dataX[$bm][$vm]['Rate'];
                                                 $newdata[$bm][$dataX[$bm][$vm]['Rate']]=$newdata[$bm][$dataX[$bm][$vm]['Rate']]+$amount1;
                                                 $newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'sgst1']+$sgst1;
                                            $newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'igst1']+$igst1;
                                           $newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']=$newdata[$bm][$dataX[$bm][$vm]['Rate'].'cgst1']+$cgst1;
                                    }
                                        $newdata[$bm]['amt']=$newdata[$bm]['amt']+$amount1; 
                                        //$newdat[$bm]=$newdata[$bm]+$amount1; 
                                        
                                        
                                       
                                        
                                           
                                           
                                           
                                            $total1 = round($amount1+$igst1+$sgst1+$cgst1,2);
                                        $tds1 = round($dataX[$bm][$vm]['tdsAmount']);
                                        $gtotal1 = $total1-$tds1;
                                        $newdata[$bm]['total1']= $newdata[$bm]['total1']+$total1;
                                         $newdata[$bm]['tds1']= $newdata[$bm]['tds1']+$tds1;
                                         $newdata[$bm]['gtotal1']= $newdata[$bm]['gtotal1']+round($gtotal1,2);
                                       }//echo $tds1.'<br>';
                                    $amount +=$amount1;
                                    $tdsn +=$tds1;
                                    $igst +=$igst1;
                                    $sgst += $sgst1;
                                    $cgst += $cgst1;
                                    $Total += $total1;
                                    $GrandTotal += $gtotal1;
                                    
                                }         
                            }
                            
                            
                             echo "<td>". $newdata[$bm]['amt']."</td>";
                              foreach($rate as $rm)
                            {  
                             echo "<td>". $newdata[$bm][$rm]."</td>";
                              echo "<td>". $newdata[$bm][$rm.'igst1']."</td>";
                             
                             echo "<td>". $newdata[$bm][$rm.'sgst1']."</td>";
                              echo "<td>". $newdata[$bm][$rm.'cgst1']."</td>";
                            }
                            // echo "<td>". $newdata[$bm]['amt']."</td>";
                              
                            
                               echo "<td>". $newdata[$bm]['total1']."</td>";
                               echo "<td>". $newdata[$bm]['tds1']."</td>";
                               echo "<td>". $newdata[$bm]['gtotal1']."</td>";
                            echo "</tr>";  
                                        
                                                     
                                    
                        } // print_r($newdata);die;   
                        
//                                echo "<tr>";
//                                echo "<td></td>";
//                                echo "<td><b>Grand Total</b></td>";
//                                echo "<td></td>";
//                                echo "<td><b>".$amount."</b></td>";
//                                 echo "<td></td>";
//                                  echo "<td></td>";
//                                   echo "<td></td>";
//                                echo "<td><b>".$igst."</b></td>";
//                                 echo "<td></td>";
//                                echo "<td><b>".$sgst."</b></td>";
//                                echo "<td><b>".$cgst."</b></td>";
//                                echo "<td><b>".$Total."</b></td>";
//                                echo "<td><b>".$tdsn."</b></td>";
//                                echo "<td><b>".$GrandTotal."</b></td>";
//                                echo "</tr>";
//                        
                        ?>
                    </tbody>
                </table>    
            
		<?php exit;
}
             }










            