                <table border="1" <?php if($type!='Export') { ?> class="table table-striped table-bordered table-hover table-heading no-border-bottom " <?php } ?>>
                    <thead>
                        <tr><th colspan="11" style="text-align: center">GRN Dashboard</th></tr>
                        <tr>
                            <th>Branch</th>
                            <th>Approved Vendor</th>
                            <th>Approved Imprest</th>
                            <th>Approved Total</th>
                            <th>Pending Vendor</th>
                            <th>Pending Imprest</th>
                            <th>Pending Total</th>
                            <th>Reject Vendor</th>
                            <th>Reject Imprest</th>
                            <th>Reject Total</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                               foreach($data as $post)
                               {
                                   $GTotal=0;
                                   echo "<tr>";
                                   echo "<td>".$post['Branch']."</td>";
                                   
                                   echo "<td>".$post['Approve']['Vendor']."</td>";
                                   echo "<td>".$post['Approve']['Imprest']."</td>";
                                   echo "<td>".($post['Approve']['Vendor']+$post['Approve']['Imprest'])."</td>";
                                   
                                   echo "<td>".$post['Pending']['Vendor']."</td>";
                                   echo "<td>".$post['Pending']['Imprest']."</td>";
                                   echo "<td>".($post['Pending']['Vendor']+$post['Pending']['Imprest'])."</td>";
                                   
                                   echo "<td>".$post['Reject']['Vendor']."</td>";
                                   echo "<td>".$post['Reject']['Imprest']."</td>";
                                   echo "<td>".($post['Reject']['Vendor']+$post['Reject']['Imprest'])."</td>";
                                   
                                   $GTotal +=$post['Approve']['Vendor']+$post['Approve']['Imprest']+$post['Pending']['Vendor']+$post['Pending']['Imprest']+$post['Reject']['Vendor']+$post['Reject']['Imprest'];
                                   echo "<th>".$GTotal."</th>";
                                   echo "</tr>";
                                   $ApproveVendor +=$post['Approve']['Vendor'];
                                   $PendingVendor +=$post['Pending']['Vendor'];
                                   $RejectVendor +=$post['Reject']['Vendor'];
                                   $ApproveImprest +=$post['Approve']['Imprest'];
                                   $PendingImprest +=$post['Pending']['Imprest'];
                                   $RejectImprest +=$post['Reject']['Imprest'];
                               }
                               echo "<tr><th>Grand Total</th>";
                               echo "<th>".$ApproveVendor."</th>";
                               echo "<th>".$ApproveImprest."</th>";
                               echo "<th>".($ApproveVendor+$ApproveImprest)."</th>";
                               
                               echo "<th>".$PendingVendor."</th>";
                               echo "<th>".$PendingImprest."</th>";
                               echo "<th>".($PendingVendor+$PendingImprest)."</th>";
                               
                               echo "<th>".$RejectVendor."</th>";
                               echo "<th>".$RejectImprest."</th>";
                               echo "<th>".($RejectVendor+$RejectImprest)."</th>";
                               
                               echo "<th>".($ApproveVendor+$ApproveImprest+$PendingVendor+$PendingImprest+$RejectVendor+$RejectImprest)."</th></tr>";
                               
                               
                        ?>
                        
                    </tbody>
                </table>    
                
<?php
if($type=='Export')
{
        $fileName = "GrnDashboard_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
           

