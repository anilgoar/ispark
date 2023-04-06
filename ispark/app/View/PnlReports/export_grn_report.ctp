<?php
if($type=='Export')
{
$fileName = "GRN_GST_Report";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>

                <table border="1">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>GRN</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>Exp. Type</th>
                            <th>Year Month</th>
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>G. Total</th>
                            <th>Grn Date</th>
                            <th>Approval Date</th>
                            <th>Bill Date</th>
                            <th>status</th>
                            <th>username</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['em']['GrnNo']."</td>";
                                        echo "<td>".$exp['cm']['branch']."</td>";
                                        echo "<td>".$exp['cm']['cost_center']."</td>";
                                        if($exp['em']['ExpenseEntryType']=='Vendor')
                                        {
                                            echo "<td>".$exp['vm']['vendor']."</td>";
                                        }
                                        else
                                        {
                                        echo "<td>".$exp['em']['ExpenseEntryType']."</td>";
                                        }
                                        echo "<td>".$exp['em']['FinanceYear'].'   '.$exp['em']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['hm']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['shm']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['em']['Description']."</td>";
                                        echo "<td>".round($exp['eep']['Amount'],2)."</td>";
                                        if($exp['em']['gst_enable']=='1' && !empty($exp['eep']['Rate']))
                                        {
                                            if($exp['0']['GSTType']=='state')
                                            {
                                                echo "<td>".round($exp['eep']['Tax']/2,2)."</td>";
                                                echo "<td>".round($exp['eep']['Tax']/2,2)."</td>";
                                                $sgst += round($exp['eep']['Tax']/2,2);
                                                $cgst += round($exp['eep']['Tax']/2,2);
                                                echo '<td>0</td>';
                                            }
                                            else
                                            {
                                                echo '<td>0</td>';
                                                echo '<td>0</td>';
                                                echo "<td>".round($exp['eep']['Tax'],2)."</td>";
                                                $igst +=round($exp['eep']['Tax'],2);
                                            }
                                        }
                                        else
                                        {
                                            echo "<td>0</td>";
                                            echo "<td>0</td>";
                                            echo "<td>0</td>";
                                        }
                                        echo "<td>".round($exp['eep']['Total'],2)."</td>";
                                        echo "<td>".$exp['em']['ExpenseDate']."</td>";
                                        echo "<td>".$exp['0']['ApprovalDate']."</td>";
                                        echo "<td>".$exp['em']['bill_date']."</td>";
                                        echo "<td>".$exp['em']['EntryStatus']."</td>";
                                        echo "<td>".$exp['tu']['emp_name']."</td>";
                                    echo "</tr>";
                                    $Total += round($exp['eep']['Amount'],2);
                                    $g_Total += round($exp['eep']['Total'],2);
                                }
                                echo "<tr>";
                                    echo '<td colspan="9"><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td><b>'.$cgst.'</b></td>';
                                    echo '<td><b>'.$sgst.'</b></td>';
                                    echo '<td><b>'.$igst.'</b></td>';
                                    echo '<td><b>'.$g_Total.'</b></td>';
                                    echo '<td colspan="4"></td>';
                                echo "</tr>"; 
                        ?>
                    </tbody>
                </table>    
            
<?php exit; ?>		

		
					
		
           

