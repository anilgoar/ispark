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
                                        echo "<td>".$exp['em']['GrnNo']."</td>";
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
                                        echo "<td>JrnlImp</td>";
                                        echo "</tr>";
                                        ///////// Entry For SubHead End //////////////////
                                        
                                        $diff = $exp['0']['Amount'];
                                        
                                        
                                        
                                        
                                        
                                        
                                        echo "<tr>";
                                        echo "<td>".$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['0']['Dates']."</td>";
                                        echo "<td>".$exp['im']['TallyHead']."</td>";
                                        echo "<td>".round($exp['0']['Amount'],2)."</td>";   
                                        echo "<td>C</td>";
                                        echo "<td>".$exp['bm']['tally_branch']."</td>";
                                        echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
                                        echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                        echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
                                        echo "<td>JrnlImp</td>";
                                        echo "</tr>";
                                    
                                    
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

		
					
		
           

