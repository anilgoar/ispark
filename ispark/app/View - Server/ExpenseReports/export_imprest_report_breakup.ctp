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
                            <th>Grn Date</th>
                            <th>Approval Date</th>
                            <th>status</th>
                            <th>username</th>
                            <?php if($type!='Export') { ?>
                            <th>GRN Image</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
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
                                        echo "<td>".$exp['eem']['ExpenseDate']."</td>";
                                        echo "<td>".$exp['0']['ApprovalDate']."</td>";
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
                                    $Total += $exp['0']['Amount'];
                                }
                                echo "<tr>";
                                    echo '<td colspan="8"><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td colspan="4"></td>';
                                echo "</tr>"; 
                        ?>
                    </tbody>
                </table>    
            
<?php exit; ?>		

		
					
		
           

