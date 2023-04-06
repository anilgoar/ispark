<?php ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch</th>
                            <th>Month</th>
                            <th>Vendor</th>
                            <th>TDS Section</th>
                            
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
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                        $diff =0;
                                        $tdsAmount = 0;
                                        /////////// Entry For SubHead    /////////////////
                                        echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['cm']['Branch']."</td>";
                                        echo "<td>".$exp['em']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['vm']['TallyHead']."</td>";
                                        echo "<td>".$exp['vm']['TDSSection']."</td>";
                                        echo "<td>".$exp['vm']['PanNo']."</td>";
                                        echo "<td>".$exp['vm']['GSTNo']."</td>";
                                        echo "<td>".$exp['vm']['CompanyGSTNo']."</td>";
                                        echo "<td>".$exp['vm']['TDS']."</td>";    
                                        echo "<td>".round($exp['0']['Amount'],2)."</td>";
                                        
                                        
                                        ///////// Entry For SubHead End //////////////////
                                        
                                        $diff = $exp['0']['Amount'];
                                        
                                        
                                        
                                        
                                        if($exp['em']['gst_enable']=='1' && !empty($exp['eep']['Rate']))
                                        {
                                            /////////// Entry For GST Enable Tax      //////////////
                                           if($exp['0']['GSTType']=='state')
                                           {   
                                                echo "<td></td>";
                                                echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                                echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                                
                                                
                                                 
                                                
                                                
                                                $diff += round($exp['0']['Tax']/2,2)+round($exp['0']['Tax']/2,2);
                                                
                                           }
                                           else 
                                           {
                                                
                                                echo "<td>".round($exp['0']['Tax'],2)."</td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                                $diff += round($exp['0']['Tax'],2);
                                           }
                                           
                                           ////////// Entry For GST Disable Tax      //////////////
                                        }
                                        echo "<td>".round($exp['0']['Total'],2)."</td>";
                                        
                                        if($exp['vm']['TDSEnabled']=='1')
                                        {
                                            $tdsAmount = round(($exp['vm']['TDS']*$exp['0']['Amount'])/100,2);
                                            echo "<td>".round($tdsAmount,2)."</td>";
                                        }
                                        echo "</tr>";
                                        
                                    
                                    
                                }
                                
                        ?>
                    </tbody>
                </table>    
<?php
if($type=='Export')
{
        $fileName = "GV_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
           

