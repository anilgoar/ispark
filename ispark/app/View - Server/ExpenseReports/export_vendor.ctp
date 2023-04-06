<?php
if($type=='Export')
{
$fileName = "Vendor_Report";
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
                            <th>Vendor Name</th>
                            <th>Company GST No</th>
                            <th>Vendor GST No</th>
                            <th>TDS %</th>
                            <th>TDS Section</th>
                            <th>Amount Deduction</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Pan No</th>
                            <th>Service Tax No</th>
                            <th>Expense Head</th>
                            <th>Expense Sub Head</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($VendorReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['bm']['branch_name']."</td>";
                                        echo "<td>".$exp['vm']['vendor']."</td>";
                                        echo "<td>".$exp['vm']['CompanyGST']."</td>";
                                        echo "<td>".$exp['vm']['VendorGST']."</td>";
                                        echo "<td>".$exp['vm']['TDS']."</td>";
                                        echo "<td>".$exp['vm']['TDSSection']."</td>";
                                        echo "<td>".$exp['vm']['AmountLimit']."</td>";
                                        echo "<td>".$exp['vm']['Address']."</td>";
                                        echo "<td>".$exp['vm']['ContactNo']."</td>";
                                        echo "<td>".$exp['vm']['PanNo']."</td>";
                                        echo "<td>".$exp['vm']['ServiceTaxNo']."</td>";
                                        echo "<td>".$exp['head']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
                                    echo "</tr>";
                                    
                                }
                                
                        ?>
                    </tbody>
                </table>    
            
		<?php exit; ?>

		
					
		
           

