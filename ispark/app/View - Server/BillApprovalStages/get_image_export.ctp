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
        <td align='center'><b>&nbsp;Bill Passed&nbsp;</b></td>	
        <td align='center'><b>&nbsp;Payment Received&nbsp;</b></td>	
        <td align='center'><b>&nbsp;TDS&nbsp;</b></td>
        <td align='center'><b>&nbsp;Received On&nbsp;</b></td>
        <td align='center'><b>&nbsp;Cheque No&nbsp;</b></td>
        <td align='center'><b>&nbsp;Bill No Tally&nbsp;</b></td>
        <td align='center'><b>&nbsp;Payment File&nbsp;</b></td>
    </tr>
    <?php $i=1;
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
                    echo "<td align='center'>".$d['bpp']['BillPassed']."</td>";
                    echo "<td align='center'>".$d['bpp']['payReceived']."</td>";
                    echo "<td align='center'>".$d['bpp']['TDS']."</td>";
                    echo "<td align='center'>".$d['0']['ReceieveDate']."</td>";
                    echo "<td align='center'>".$d['0']['ChequeNo']."</td>";
                    echo "<td align='center'>".$d['ti']['BillTally']."</td>";
                    echo "<td align='center'>";
                    if(!empty($d['bpp']['PaymentFile']))
                    {echo '<a href="http://www.mascallnetnorth.in/'.$this->webroot.'app/webroot/CollectionImage/'.$d['bpp']['PaymentFile'].'" target="_blank">Payment File</a>';}
                    else { echo "";}
                    echo "</td>";
                echo "</tr>";
            endforeach;
    ?>
</table>
        
        <?php } ?>
