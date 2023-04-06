<?php
if($type=='Export')
{
$fileName = "Budget_Export";
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
                            <th>Entry No</th>
                            <th>Finance Year</th>
                            <th>Finance Month</th>
                            <th>Head</th>
                            <th>Sub Head</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['0']['Branch']."</td>";
                                        echo "<td>".$exp['0']['EntryNo']."</td>";
                                        echo "<td>".$exp['0']['FinanceYear']."</td>";
                                        echo "<td>".$exp['0']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['0']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['Amount']."</td>";
                                        echo "<td>".$exp['0']['date']."</td>";
                                        if($exp['0']['bus_status']!='Closed')
                                        echo '<td style="background-color:red"><b>'.$exp['0']['bus_status']."</b></td>";
                                        else
                                         echo '<td style="background-color:green"><b>'.$exp['0']['bus_status']."</b></td>";   
                                    echo "</tr>";
                                     $Total += $exp['0']['Amount'];
                                }
                                echo "<tr>";
                                    echo '<td colspan="7"><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td colspan="2"></td>';
                                echo "</tr>";
                        ?>
                    </tbody>
                </table>    
            
		<?php exit; ?>

		
					
		
           

