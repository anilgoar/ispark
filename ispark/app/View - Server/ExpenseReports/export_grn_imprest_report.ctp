                <table border="1">
                    <thead>
                        <tr>
                            <th>Vch No</th>
                            <th>Date</th>
                            <th>Details</th>
                            <th>Amount</th>
                            <th>DebitCredit</th>
                            <th>Cost Category</th>
                            <th>Cost Centre</th>
                            <th>Narration for Each Entry</th>
                            <th>Narration</th>
                            <th>VchType</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                    $FinanceYear = $exp['em']['FinanceYear'];
                                    $FinanceMonth = $exp['em']['FinanceMonth'];
                                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
                                    $FinanceMonthNum = $monthArray[$FinanceMonth];
                                    if($monthArray[$FinanceMonth]<=3) 
                                        {
                                            $FinanceYear1 = explode('-',$FinanceYear);
                                            $FinanceYear2 = $FinanceYear1[1]-1;
                                        }
                                        else
                                        {
                                            $FinanceYear1 = explode('-',$FinanceYear);
                                            $FinanceYear2 = $FinanceYear1[1]-1;
                                        }
                                       $FinanceMonth1 =  $monthArray[$FinanceMonth];
                                        $diff =0;
                                        $tdsAmount = 0;
                                        
                                        
                                        /////////// Entry For SubHead    /////////////////
                                        echo "<tr>";
                                        echo "<td>".$exp['0']['VchNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
                                        echo "<td>".round($exp['0']['Amount'],2)."</td>";
                                        echo "<td>D</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>";
                                        echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                        echo "</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                        echo "<td>Journal</td>";
                                        echo "</tr>";
                                        ///////// Entry For SubHead End //////////////////
                                        
                                        $diff = $exp['0']['Amount'];
                                        
                                        
                                        
                                        
                                        if($exp['tscgd']['GSTEnable']=='1' && !empty($exp['eep']['Rate']))
                                        {
                                            /////////// Entry For GST Enable Tax      //////////////
                                           if($exp['0']['GSTType']=='state')
                                           {   
                                                echo "<tr>";
                                                echo "<td>".$exp['0']['VchNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input CGST @".($exp['eep']['Rate']/2)."%(".$exp['bm']['state'].")"."</td>";
                                                echo "<td>".($exp['0']['Tax']/2)."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].$exp['em']['GrnNo']."</td>";
                                                echo "<td>Journal</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                echo "<td>".$exp['0']['VchNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input SGST @".($exp['eep']['Rate']/2)."%(".$exp['bm']['state'].")"."</td>";
                                                echo "<td>".($exp['0']['Tax']/2)."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                                echo "<td>Journal</td>";
                                                echo "</tr>";
                                                
                                                $diff += $exp['0']['Tax'];
                                                
                                           }
                                           else 
                                           {
                                                echo "<tr>";
                                                echo "<td>".$exp['0']['VchNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input IGST @".($exp['eep']['Rate'])."%(".$exp['bm']['state'].")"."</td>";
                                                echo "<td>".($exp['0']['Tax'])."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                                echo "<td>Journal</td>";
                                                echo "</tr>";
                                                $diff += $exp['0']['Tax'];
                                           }
                                           
                                           ////////// Entry For GST Disable Tax      //////////////
                                        }
                                        
                                        echo "<tr>";
                                        echo "<td>".$exp['0']['VchNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>".$exp['vm']['TallyHead']."</td>";
                                        if($exp['vm']['TDSEnabled']=='1')
                                        {
                                            $tdsAmount = round(($exp['vm']['TDS']*$exp['0']['Amount'])/100,2);
                                            echo "<td>".($exp['0']['Total']-$tdsAmount)."</td>";
                                        }
                                        else
                                        {
                                            echo "<td>".$exp['0']['Total']."</td>";
                                        }    
                                        echo "<td>C</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                        echo "<td>Journal</td>";
                                        echo "</tr>";
                                    
                                    if($exp['vm']['TDSEnabled']=='1')
                                    {
                                        echo "<tr>";
                                        echo "<td>".$exp['0']['VchNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>TDS RENT (".$exp['em']['FinanceYear'].")</td>";
                                        echo "<td>".$tdsAmount."</td>";
                                        echo "<td>C</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                        echo "<td>Journal</td>";
                                        echo "</tr>";
                                        $diff -= $tdsAmount;
                                    }
                                    
                                    $diff -= $exp['0']['Total'];
                                    if($diff!=0)
                                    {
                                        echo "<tr>";
                                        echo "<td>".$exp['0']['VchNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>Short/Excess Written off</td>";
                                        echo "<td>".round(abs($diff),2)."</td>";
                                        if($diff>0)
                                        {
                                            echo "<td>D</td>";
                                        }
                                        else
                                        {
                                            echo "<td>C</td>";
                                        }
                                        
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                        echo "<td>Journal</td>";
                                    }
                                }
                                
                        ?>
                    </tbody>
                </table>    
<?php
if($type=='Export')
{
        $fileName = "GV_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
           

