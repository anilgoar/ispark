<?php
if($type=='Export')
{
    $fileName = "Imprest_Details";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}       
?>
                <table border="2" class="table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Date</th>
                            <th>GRN</th>
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>INFLOW</th>
                            <th>OUTFLOW</th>
                            <th>Balance</th>
                            <th>Mode</th>
                            <th>Chq No</th>
                            <th>Bank</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $TotalInflow = 0; $TotalOutflow=0;//print_r($ExpenseReport); exit;
                                foreach($data as $imp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$imp['date']."</td>";
                                        echo "<td>".$imp['grn']."</td>";
                                        echo "<td>".$imp['head']."</td>";
                                        echo "<td>".$imp['subhead']."</td>";
                                        echo "<td>".(!empty($imp['inflow'])?$imp['inflow']:0)."</td>";
                                        echo "<td>".(!empty($imp['outflow'])?$imp['outflow']:0)."</td>";
                                        echo "<td>".(!empty($imp['balance'])?$imp['balance']:0)."</td>";
                                        echo "<td>".$imp['PaymentMode']."</td>";
                                        echo "<td>".$imp['PaymentNo']."</td>";
                                        echo "<td>".$imp['BankId']."</td>";
                                        echo "<td>".$imp['remarks']."</td>";
                                    echo "</tr>";
                                    $TotalInflow +=  !empty($imp['inflow'])?$imp['inflow']:0;
                                    $TotalOutflow += !empty($imp['outflow'])?$imp['outflow']:0;
                                }
                                echo '<tr><td colspan="4"></td><td>Total</td><td>'.$TotalInflow.'</td><td>'.$TotalOutflow.'</td><td colspan="5"></td></tr>';
                        ?>
                    </tbody>
                </table>    
            
		<?php echo exit; ?>

		

