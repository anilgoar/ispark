<?php
//print_r($data); exit;
if(!empty($data))
{
?>
<table border="1" style="font-size: 90%">
    <tr>
        <td align="center">InvoiceNo</td>
        <td align="center">Remarks</td>
        <td align="center">Finance Year</td>
        <td align="center">Bill Amount</td>
        <td align="center">Bill Passed</td>
        <td align="center">TDS</td>
        <td align="center">Other Deduction</td>
        <td align="center">Bill Deduction</td>
        <td align="center">Net Amount</td>
        <td align="center">Cheque Amount</td>
    </tr>
    <?php 
        $a =  $b =  $c =  $d =  $e = $f = 0;
    foreach($data as $post): ?>
    <tr>
        <td align="center"><?=$post['bill_pay_particulars']['bill_no'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['remarks'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['financial_year'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['bill_amount'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['bill_passed'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['tds_ded'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['deduction'];?></td>
        <td align="center"><?=$post['odb']['bill_other_deduction'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['net_amount'];?></td>
        <td align="center"><?=$post['bill_pay_particulars']['pay_amount'];?></td>
        <?php  $a += $post['bill_pay_particulars']['bill_amount'];
                $b += $post['bill_pay_particulars']['bill_passed'];
                $c += $post['bill_pay_particulars']['tds_ded'];
                $d += $post['bill_pay_particulars']['deduction'];
                $bd += $post['odb']['bill_other_deduction'];
                $e += $post['bill_pay_particulars']['net_amount'];
                $f = $post['bill_pay_particulars']['pay_amount'];
        ?>
    </tr>
    <?php endforeach; ?>
    <?php 
    if(!empty($data1)){ ?>
        <?php
            foreach($data1 as $other):
                $d +=$other['other_deductions']['other_deduction'];
        ?>
        <tr>
        <td>other Deduction</td>
        <td colspan="1"><?=$other['other_deductions']['other_remarks']?></td>
        <td></td><td></td><td></td><td></td>
        <td colspan="1"><?=$other['other_deductions']['other_deduction']?></td>
        <td></td>
        <td></td>
        </tr>
        <?php    endforeach;
        ?>
<?php } ?>
        <tr>
        <th colspan="3"  align="center">Total</th>
        <th align="center"><?=$a?></th>
        <th align="center"><?=$b?></th>
        <th align="center"><?=$c?></th>
        
        <th align="center"><?=$d?></th>
        <th align="center"><?=$bd?></th>
        <th align="center"><?=$e?></th>
        <th align="center"><?=$f?></th>
    </tr>
    
</table>
<?php } ?>
