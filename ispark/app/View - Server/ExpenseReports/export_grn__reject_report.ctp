

                <table border="1">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Total Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($GrnReject as $exp)
                                {
                                    echo "<tr>";
                                    echo "<td>".$exp['bm']['branch_name']."</td>";
                                    echo "<td>".$exp['0']['cnt']."</td>";
                                    echo "</tr>";
                                }
                                
                        ?>
                    </tbody>
                </table>    
<?php
if($type=='Export')
{
        $fileName = "GRN_Reject_Report_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
           

