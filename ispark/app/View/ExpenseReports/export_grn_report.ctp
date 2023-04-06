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
                                    $monthArray=array('Jan'=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>'08','Sep'=>'09','Oct'=>10,'Nov'=>11,'Dec'=>12);
                                    $FinanceMonthNum = $monthArray[$FinanceMonth];
                                    if($monthArray[$FinanceMonth]<=3) 
                                        {
                                            $FinanceYear1 = explode('-',$FinanceYear);
                                            $FinanceYear2 = $FinanceYear1[1];
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
                                        echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                        
                                        $dater = explode('-',$exp['0']['Dates']);
                                        $dater[0] = '31' ;
                                        
                                        $newDate = implode('-',$dater);
                                        
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        
                                        
                                        
                                        echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
                                        echo "<td>".round($exp['0']['Amount'],2)."</td>";
                                        echo "<td>D</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>";
                                        echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                        echo "</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                        echo "<td>JrnlP</td>";
                                        echo "</tr>";
                                        ///////// Entry For SubHead End //////////////////
                                        
                                        $diff = $exp['0']['Amount'];
                                        
                                        
                                        
                                        
                                        if($exp['tscgd']['GSTEnable']=='1' && !empty($exp['eep']['Rate']))
                                        {
                                            /////////// Entry For GST Enable Tax      //////////////
                                           if($exp['0']['GSTType']=='state')
                                           {   
                                                echo "<tr>";
                                                echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input CGST @".($exp['eep']['Rate']/2)."%(".$exp['0']['state'].")"."</td>";
                                                echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                                echo "<td>JrnlP</td>";
                                                echo "</tr>";
                                                
                                                echo "<tr>";
                                                echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input SGST @".($exp['eep']['Rate']/2)."%(".$exp['0']['state'].")"."</td>";
                                                echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                                echo "<td>JrnlP</td>";
                                                echo "</tr>";
                                                
                                                $diff += round($exp['0']['Tax']/2,2)+round($exp['0']['Tax']/2,2);
                                                
                                           }
                                           else 
                                           {
                                                echo "<tr>";
                                                echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                                echo "<td>".$exp['0']['Dates']."</td>";
                                                echo "<td>Input IGST @".($exp['eep']['Rate'])."%(".$exp['0']['state'].")"."</td>";
                                                echo "<td>".round($exp['0']['Tax'],2)."</td>";
                                                echo "<td>D</td>";
                                                echo "<td>".$exp['bm']['tally_branch']."</td>";
                                                echo "<td>";
                                                echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
                                                echo "</td>";
                                                echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                                echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                                echo "<td>JrnlP</td>";
                                                echo "</tr>";
                                                $diff += round($exp['0']['Tax'],2);
                                           }
                                           
                                           ////////// Entry For GST Disable Tax      //////////////
                                        }
                                        
                                        echo "<tr>";
                                        echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>".$exp['vm']['TallyHead']."</td>";
                                        if($exp['vm']['TDSEnabled']=='1' || $exp['subhead']['SubHeadTDSEnabled']=='1')
                                        {
                                            if($exp['subhead']['SubHeadTDSEnabled']=='1' && ($exp['vm']['TDSChange']=='No' || empty($exp['vm']['TDSChange'])))
                                            {
                                                $tdsAmount = round(($exp['td']['TDS']*$exp['0']['Amount'])/100,2);
                                            }
                                            else if($exp['vm']['TDSChange']=='No' || empty($exp['vm']['TDSChange']))
                                            {
                                                $tdsAmount = round(($exp['vm']['TDS']*$exp['0']['Amount'])/100,2);
                                            }
                                            else
                                            {
                                                $tdsAmount = round(($exp['vm']['TDSNew']*$exp['0']['Amount'])/100,2);
                                            }
                                            
                                            echo "<td>".round($exp['0']['Total']-$tdsAmount,2)."</td>";
                                        }
                                        else
                                        {
                                            echo "<td>".round($exp['0']['Total'],2)."</td>";
                                        }    
                                        echo "<td>C</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                        echo "<td>JrnlP</td>";
                                        echo "</tr>";
                                    
                                    if($exp['vm']['TDSEnabled']=='1' || $exp['subhead']['SubHeadTDSEnabled']=='1' )
                                    {
                                        echo "<tr>";
                                        echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        if(!empty($exp['td']['description']))
                                        echo "<td>".$exp['td']['description']."</td>";
                                        else
                                        echo "<td>".$exp['td2']['description']."</td>";    
                                        echo "<td>".round($tdsAmount,2)."</td>";
                                        echo "<td>C</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                        echo "<td>JrnlP</td>";
                                        echo "</tr>";
                                        //$diff -= $tdsAmount;
                                    }
                                    
                                    $diff -= round($exp['0']['Total'],2);
                                    if(!empty(round($diff,2)))
                                    {
                                        echo "<tr>";
                                        echo "<td>".$exp['em']['bill_no'].'/'.$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>Short/Excess Written off</td>";
                                        echo "<td>".round(abs($diff),2)."</td>";
                                        if($diff>0)
                                        {
                                            echo "<td>C</td>";
                                        }
                                        else
                                        {
                                            echo "<td>D</td>";
                                        }
                                        
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo'].', Bill No'.$exp['em']['bill_no']."</td>";
                                        echo "<td>JrnlP</td>";
                                    }
                                }
                                
                        ?>
                    </tbody>
                </table>    
<?php
if($type=='Export')
{
        $fileName = "Import";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
            


