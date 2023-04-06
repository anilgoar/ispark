<?php
if($type=='Export')
{
$fileName = "Imprest_Export";
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
                            <th>Exp. Type</th>
                            <th>Year Month</th>
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>Total</th>
                            <th>Grn Date</th>
                            <th>Approval Date</th>
                            <th>Bill Date</th>
                            <th>Due Date</th>
                            <th>status</th>
                            <th>username</th>
                            <?php if($type!='Export') { ?>
                            <th>GRN Image</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $amt=0; $sgst = 0; $cgst=0; $Total=0; //print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['eem']['GrnNo']."</td>";
                                        echo "<td>".$exp['bm']['branch_name']."</td>";
                                        if($exp['eem']['ExpenseEntryType']=='Vendor')
                                        {
                                            echo "<td>".$exp['vm']['vendor']."</td>";
                                        }
                                        else
                                        {
                                            echo "<td>".$exp['eem']['ExpenseEntryType']."</td>";
                                        }
                                        echo "<td>".$exp['eem']['FinanceYear'].'   '.$exp['em']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['head']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['eem']['description']."</td>";
                                        echo "<td>".round($exp['0']['Amount'],2)."</td>";
                                        if($exp['0']['GSTType']=='state')
                                        {
                                            echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                            echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                            echo "<td>0</td>";
                                            $sgst +=round($exp['0']['Tax']/2,2);
                                            $cgst +=round($exp['0']['Tax']/2,2);
                                        }
                                        else
                                        {
                                            echo "<td>0</td>";
                                            echo "<td>0</td>";
                                            echo "<td>".round($exp['0']['Tax'],2)."</td>";
                                            $igst +=round($exp['0']['Tax'],2);
                                        }
                                        echo "<td>".round($exp['0']['Total'],2)."</td>";
                                        echo "<td>".$exp['eem']['ExpenseDate']."</td>";
                                        echo "<td>".$exp['0']['ApprovalDate']."</td>";
                                        echo "<td>".$exp['eem']['bill_date']."</td>";
                                        echo "<td>".$exp['eem']['due_date']."</td>";
                                        echo "<td>".$exp['eem']['EntryStatus']."</td>";
                                        echo "<td>".$exp['tu']['emp_name']."</td>";
                                        
                                        if($type!='Export') {
                                            echo '<td>';
                                            if(!empty($exp['eem']['grn_file']))
                                            {
                                                echo '<a href="'.$this->webroot.'app/webroot/GRN/'.$exp['eem']['grn_file'].'" download>download</a>';
                                            }
                                        echo '</td>';
                                        }
                                    echo "</tr>";
                                    $amt += round($exp['0']['Amount'],2);
                                    $Total += round($exp['0']['Total'],2);
                                }
                                echo "<tr>";
                                    echo '<td colspan="8"><b>Total</b></td>';
                                    echo '<td><b>'.round($amt,2).'</b></td>';
                                    echo '<td><b>'.round($sgst,2).'</b></td>';
                                    echo '<td><b>'.round($cgst,2).'</b></td>';
                                    echo '<td><b>'.round($igst,2).'</b></td>';
                                    echo '<td><b>'.round($Total,2).'</b></td>';
                                    echo '<td colspan="7"></td>';
                                echo "</tr>"; 
                        ?>
                    </tbody>
                </table>    
            
<?php exit; ?>		

		
					
		
           

