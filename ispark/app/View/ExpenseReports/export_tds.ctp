<?php
if($type=='Export')
{
$fileName = "TDS_Report";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
if(!empty($dataX))
{
?>


                <table border="1">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch</th>
                            <th>Vendor</th>
                            <th>TDS Section</th>
                            <th>TDS Description</th>
                            <th>Pan No</th>
                            <th>Vendor GST NO.</th>
                            <th>Company GST NO.</th>
                            <th>TDS%</th>
                            <th>Amount</th>
                            <th>IGST</th>
                            <th>SGST</th>
                            <th>CGST</th>
                            <th>Total</th>
                             <th>TDS</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $GrandTotal = $tds = $igst = $sgst = $cgst = $amount = $Total=0;//print_r($ExpenseReport);$amount
//                                foreach($TDSReport as $exp)
//                                {
//                                    echo "<tr>";
//                                        echo "<td>".$i++."</td>";
//                                        echo "<td>".$exp['tab']['Branch']."</td>";
//                                        echo "<td>".$exp['tab']['TallyHead']."</td>";
//                                        echo "<td>".$exp['tab']['TDSSection']."</td>";
//                                        
//                                        
//                                        
//                                        echo "<td>".$exp['tab']['PanNo']."</td>";
//                                        echo "<td>".$exp['tab']['GSTNo']."</td>";
//                                        echo "<td>".$exp['tab']['CompanyGSTNo']."</td>";
//                                        $tdsper1 = $exp['tab']['TDS'];
//                                        echo "<td>".$exp['tab']['TDS']."%</td>";
//                                        $amount1 = round($exp['tab']['Amount'],2);
//                                        echo "<td>".$amount1."</td>"; 
//                                        
//                                        $igst1 = 0; $sgst1 = 0; $cgst1 = 0;
//                                        if($exp['tab']['GSTType']=='central')
//                                        {
//                                            $igst1 = round($exp['tab']['Tax'],2);
//                                            echo "<td>".$igst1."</td>";
//                                            echo "<td>0</td>";
//                                            echo "<td>0</td>";
//                                        }
//                                        else
//                                        {
//                                            $sgst1 = round($exp['tab']['Tax']/2,2); $cgst1 = round($exp['tab']['Tax']/2,2);
//                                            echo "<td>0</td>";
//                                            echo "<td>".$sgst1."</td>";
//                                            echo "<td>".$cgst1."</td>";
//                                        }
//                                        $total1 = round($amount1+$igst1+$sgst1+$cgst1,2);
//                                        $tds1 = round(($amount1*$tdsper1)/100,2);
//                                        $gtotal1 = $total1-$tds1;
//                                        echo "<td>".$total1."</td>";
//                                        echo "<td>".$tds1."</td>";
//                                        echo "<td>".round($gtotal1,2)."</td>";
//                                        
//                                        
//                                    echo "</tr>";
//                                    $amount +=$amount1;
//                                    $tds +=$tds1;
//                                    $igst +=$igst1;
//                                    $sgst += $sgst1;
//                                    $cgst += $cgst1;
//                                    $Total += $total1;
//                                    $GrandTotal += $gtotal1;
//                                }
//                                
//                                echo "<tr>";
//                                echo "<td colspan='8'></td>";
//                                echo "<td><b>Grand Total</b></td>";
//                                echo "<td><b>".$amount."</b></td>";
//                                
//                                echo "<td><b>".$igst."</b></td>";
//                                echo "<td><b>".$sgst."</b></td>";
//                                echo "<td><b>".$cgst."</b></td>";
//                                echo "<td><b>".$Total."</b></td>";
//                                echo "<td><b>".$tds."</b></td>";
//                                echo "<td><b>".$GrandTotal."</b></td>";
//                                echo "</tr>";
                        
                        foreach($BranchMaster as $bm)
                        {
                            foreach($VendorMasterNew as $vm)
                            {
                                if(!empty($dataX[$bm][$vm]))
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$bm."</td>";
                                        echo "<td>".$vm."</td>";
                                        echo "<td>".$dataX[$bm][$vm]['TDSSection']."</td>";
                                        echo "<td>".$dataX[$bm][$vm]['TDSTallyHead']."</td>";
                                        
                                        
                                        
                                        echo "<td>".$dataX[$bm][$vm]['PanNo']."</td>";
                                        echo "<td>".$dataX[$bm][$vm]['GSTNo']."</td>";
                                        echo "<td>".$dataX[$bm][$vm]['CompanyGSTNo']."</td>";
                                       // $tdsper1 = $dataX['TDS'];
                                        echo "<td>".$dataX[$bm][$vm]['TDS']."%</td>";
                                        $amount1 = round($dataX[$bm][$vm]['Amount'],2);
                                        echo "<td>".$amount1."</td>"; 
                                        
                                        $igst1 = 0; $sgst1 = 0; $cgst1 = 0;
                                        
                                            $igst1 = round($dataX[$bm][$vm]['IGST'],2);
                                            echo "<td>".$igst1."</td>";
                                            $sgst1 = round($dataX[$bm][$vm]['SGST'],2); 
                                            $cgst1 = round($dataX[$bm][$vm]['CGST'],2);
                                            echo "<td>".$sgst1."</td>";
                                            echo "<td>".$cgst1."</td>";
                                            $total1 = round($amount1+$igst1+$sgst1+$cgst1,2);
                                        $tds1 = round($dataX[$bm][$vm]['tdsAmount'],2);
                                        $gtotal1 = $total1-$tds1;
                                        echo "<td>".$total1."</td>";
                                        echo "<td>".$tds1."</td>";
                                        echo "<td>".round($gtotal1,2)."</td>";
                                    echo "</tr>";
                                    
                                    $amount +=$amount1;
                                    $tds +=$tds1;
                                    $igst +=$igst1;
                                    $sgst += $sgst1;
                                    $cgst += $cgst1;
                                    $Total += $total1;
                                    $GrandTotal += $gtotal1;
                                    
                                }           
                            }
                                        
                                    
                                    //                                    echo "</tr>";
                                    
                        }
                        
                                echo "<tr>";
                                echo "<td colspan='8'></td>";
                                echo "<td><b>Grand Total</b></td>";
                                echo "<td><b>".$amount."</b></td>";
                                
                                echo "<td><b>".$igst."</b></td>";
                                echo "<td><b>".$sgst."</b></td>";
                                echo "<td><b>".$cgst."</b></td>";
                                echo "<td><b>".$Total."</b></td>";
                                echo "<td><b>".$tds."</b></td>";
                                echo "<td><b>".$GrandTotal."</b></td>";
                                echo "</tr>";
                        
                        ?>
                    </tbody>
                </table>    
            
		<?php exit;
} else
{
    echo "<table border='2'><tr><th colspan='10'>Data Not Exist For $monthF</th></tr></table>"; exit;
}
                ?>

		
					
		
           

