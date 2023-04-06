<?php
if($type=='Export')
{
$fileName = "Imprest_report2";
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
                            <th>Branch</th>
                            <th>User</th>
                            <th>Entry Date</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Bank</th>
                            <th>Payment No</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($ImprestReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['im']['Branch']."</td>";
                                        echo "<td>".$exp['im']['UserName']."</td>";
                                        echo "<td>".$exp['0']['EntryDate']."</td>";
                                        echo "<td>".$exp['iam']['Amount']."</td>";
                                        echo "<td>".$exp['0']['PaymentMode']."</td>";
                                        echo "<td>".$exp['bnk']['Bank']."</td>";
                                        echo "<td>".$exp['iam']['PaymentNo']."</td>";
                                        echo "<td>".$exp['iam']['Remarks']."</td>";
                                    echo "</tr>";
                                     $Total += $exp['iam']['Amount'];
                                }
                                echo "<tr>";
                                    echo '<td colspan="3"></td><td><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td colspan="4"></td>';
                                echo "</tr>";
                        ?>
                    </tbody>
                </table>    
            
		<?php exit; ?>

		
					
		
           

