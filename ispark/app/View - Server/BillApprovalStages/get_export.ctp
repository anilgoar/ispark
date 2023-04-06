<?php

//print_r($result);
        $fileName = "Invoice_Export_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
	$class = "border = \"1\"";
        
        if(!empty($data)){
?>
<table border="1" style="font-size: 70%;">
    <tr>
        <td align='center'><b>&nbsp;Bill No&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Pending&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Process Code&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Company&nbsp;</b></td>
        <td align='center'><b>&nbsp;Branch&nbsp;</b></td>
        <td align='center'><b>&nbsp;Client&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Financial Year&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Month&nbsp;</b></td>
        <td align='center'><b>&nbsp;PO No.&nbsp;</b></td>
        <td align='center'><b>&nbsp;GRN No.&nbsp;</b></td>
        <td align='center'><b>&nbsp;Invoice Date&nbsp;</b></td>
        <td align='center'><b>&nbsp;GST Type&nbsp;</b></td>
        <td align='center'><b>&nbsp;Company GST No&nbsp;</b></td>
        <td align='center'><b>&nbsp;Vendor GST No&nbsp;</b></td>
        <td align='center'><b>&nbsp;Vendor GST State&nbsp;</b></td>
        <td align='center'><b>&nbsp;Vendor GST Code&nbsp;</b></td>
        <td align='center'><b>&nbsp;Amount&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Service Tax&nbsp;</b></td>	
        <td align='center'><b>&nbsp;SBC Tax&nbsp;</b></td>
        <td align='center'><b>&nbsp;Krishi Tax&nbsp;</b></td>
        <td align='center'><b>&nbsp;IGST&nbsp;</b></td>	
        <td align='center'><b>&nbsp;CGST&nbsp;</b></td>
        <td align='center'><b>&nbsp;SGST&nbsp;</b></td>
        <td align='center'><b>&nbsp;G Total&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Remarks&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Status&nbsp;</b></td>
        <td align='center'><b>&nbsp;Bill Amount&nbsp;</b></td>
        <td align='center'><b>&nbsp;Bill Passed&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Bill Other Deduction&nbsp;</b></td>
        <td align='center'><b>&nbsp;Other Deduction&nbsp;</b></td>
        <td align='center'><b>&nbsp;Deduction&nbsp;</b></td>
        <td align='center'><b>&nbsp;Bill No Tally&nbsp;</b></td>
        
        <td align='center'><b>&nbsp;TDS&nbsp;</b></td>
        <td align='center'><b>&nbsp;Payment Received&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Received On&nbsp;</b></td>
        
        <td align='center'><b>&nbsp;Cheque No&nbsp;</b></td>
        
        
         <td align='center'><b>&nbsp;Payment File&nbsp;</b></td>
    </tr>
    <?php $i=1; $other_disp=array();
            foreach($data as $d):
                echo "<tr>";
                    echo "<td align='center'>".$d['0']['BillNo']."</td>";
                    echo "<td align='center'>".$d['0']['Pending']."</td>";
                    echo "<td align='center'>".$d['ti']['ProcessCode']."</td>";
                    echo "<td align='center'>".$d['cm']['company_name']."</td>";
                    echo "<td align='center'>".$d['ti']['branch']."</td>";
                    echo "<td align='center'>".$d['cm']['client']."</td>";
                    echo "<td align='center'>".$d['ti']['financialYear']."</td>";
                    echo "<td align='center'>".$d['0']['month']."</td>";
                    echo "<td align='center'>".$d['ti']['po_no']."</td>";
                    echo "<td align='center'>".$d['ti']['grn']."</td>";
                    echo "<td align='center'>".$d['0']['invoiceDate']."</td>";
                    echo "<td align='center'>".$d['cm']['GSTType']."</td>";
                    echo "<td align='center'>".$d['cm']['CompanyGSTNo']."</td>";
                    echo "<td align='center'>".$d['cm']['VendorGSTNo']."</td>";
                    echo "<td align='center'>".$d['cm']['VendorGSTState']."</td>";
                    echo "<td align='center'>".$d['cm']['VendorStateCode']."</td>";
                    echo "<td align='center'>".$d['ti']['amount']."</td>";
                    echo "<td align='center'>".$d['ti']['ServiceTax']."</td>";
                    echo "<td align='center'>".$d['ti']['SbcTax']."</td>";
                    echo "<td align='center'>".$d['ti']['KrishiTax']."</td>";
                    echo "<td align='center'>".$d['ti']['igst']."</td>";
                    echo "<td align='center'>".$d['ti']['cgst']."</td>";
                    echo "<td align='center'>".$d['ti']['sgst']."</td>";
                    echo "<td align='center'>".$d['ti']['GTotal']."</td>";
                    echo "<td align='center'>".$d['ti']['Remarks']."</td>";
                    echo "<td align='center'>".$d['0']['status']."</td>";
                    echo "<td align='center'>".$d['bpp']['bill_amount']."</td>";
                    echo "<td align='center'>".$d['bpp']['BillPassed']."</td>";
                    echo "<td align='center'>".$d['obd']['other_deduction_bill']."</td>";
                    if(!in_array(($d['bpp']['pay_type'].$d['bpp']['pay_no']),$other_disp))
                    {
                        echo "<td align='center'>".$d['od']['other_deduction']."</td>";
                        $other_disp[] = $d['bpp']['pay_type'].$d['bpp']['pay_no'];
                    }
                    else
                    {
                        echo "<td align='center'>0</td>";
                    }
                    echo "<td align='center'>".$d['obd']['deduction']."</td>";
                    
                    echo "<td align='center'>".$d['ti']['BillTally']."</td>";

                        $net_desc = explode('#',$d['bpp']['net_amount_desc']); 
                        if(count($net_desc)>1)
                        {
                            $print_newRow = false;
                            foreach($net_desc as $pay_desc) 
                            {
                                if($print_newRow)
                                {
                                    echo '</tr><tr><td colspan="30"><td>';
                                    echo "<td align='center'>".$d['ti']['BillTally']."</td>";
                                }
                                $det = explode(',',$pay_desc);
                                echo "<td align='center'>".$det[1]."</td>";
                                echo "<td align='center'>".$det[0]."</td>";


                                echo "<td align='center'>".$det[2]."</td>";
                                echo "<td align='center'>".$det[3]."</td>";
                                echo '<td align="center">';
                                if(!empty($det[4]))
                                {echo '<a href="http://www.mascallnetnorth.in/'.$this->webroot.'app/webroot/CollectionImage/'.$det[4].'" target="_blank">Payment File</a>';}
                                else { echo "";}
                                echo "</td>";
                                $print_newRow = true;
                            }
                        }
                        else
                        {
                            $det = explode(',',$d['bpp']['net_amount_desc']);
                                echo "<td align='center'>".$det[1]."</td>";
                                echo "<td align='center'>".$det[0]."</td>";


                                echo "<td align='center'>".$det[2]."</td>";
                                echo "<td align='center'>".$det[3]."</td>";
                                echo "<td align='center'>";
                                if(!empty($det[4]))
                                {echo '<a href="http://www.mascallnetnorth.in/'.$this->webroot.'app/webroot/CollectionImage/'.$det[4].'" target="_blank">Payment File</a>';}
                                else { echo "";}
                                echo "</td>";
                        }
                        
                    
                echo "</tr>";
            endforeach;
    ?>
</table>
        
        <?php } ?>
