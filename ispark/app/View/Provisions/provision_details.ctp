<?php  $cost =array(); //print_r($qry); ?>

<?php if($report == 'provision') { 
    $sumProvision = 0;
    $sumBillRaised = 0;
    $balance = 0;
    ?>
<table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr><th colspan="2"><?php echo $branch.'('.$month.')'; ?></th><th colspan="2">Provision Pending</th></tr>
    <tr><th>Cost Center</th><th>Provision</th><th>Bill Raised</th><th>Bill To Be Raised</th></tr>
    <?php
            foreach($data as $p):
                    echo "<tr>";
                        echo "<td>".$p['pm']['cost_center']."</td>";
                        echo "<td>".$p['pm']['Provision']."</td>";
                        echo "<td>".$p['0']['Bill Raised']."</td>";
                        echo "<td>".$p['pm']['balance']."</td>";
                    echo "</tr>";
                    $sumProvision += $p['pm']['Provision'];
                    $sumBillRaised += $p['0']['Bill Raised'];
                    $balance += $p['pm']['balance'];
            endforeach;
    ?>
    <tr>
        <th>Total</th>
        <th><?php echo $sumProvision; ?></th>
        <th><?php echo $sumBillRaised; ?></th>
        <th><?php echo $balance; ?></th>
    </tr>
</table>

<?php } else if($report == 'po') { ?>
<table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <td colspan="2"><?php echo $branch." (".$month.")";  ?></td><td colspan="3">PO Pending List</td>
    </tr>
    <tr>
        <th>Invoice No</th>
        <th>Cost Center</th>
        <th>Remarks</th>
        <th>Amount</th>
        <th>Action Date</th>
        <th>Action Remarks</th>
    </tr>
<?php
    $total = 0;
        foreach($data as $d):
            echo "<tr>";
                echo "<th>".$d['InitialInvoice']['bill_no']."</th>";
                echo "<td>".$d['InitialInvoice']['cost_center']."</td>";
                echo "<td>".$d['InitialInvoice']['invoiceDescription']."</td>";
                echo "<td>".$d['0']['grnd']."</td>";
                $date = $d['InitialInvoice']['po_date'];
                if(!empty($date))
                {
                    $date = date_format(date_create($date), 'd-M-Y');
                }
                else
                    $date = "UnAllocated";
                echo "<td>".$date."</td>";
                echo "<td>".$d['InitialInvoice']['po_remarks']."</td>";
                
            echo "</tr>";
            $total +=$d['0']['grnd'];
        endforeach;
?>
    <tr>
        <th colspan="3">Total</th>
        <th><?php echo $total; ?></th>
    </tr>
</table>
    
<?php } else if($report == 'grn') { ?>
<table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <td colspan="2"><?php echo $branch." (".$month.")";  ?></td>
        <td colspan="3">GRN Pending List</td>
    </tr>
    <tr>
        <th>Invoice No</th>
        <th>Cost Center</th>
        <th>Remarks</th>
        <th>Amount</th>
        <th>Action Date</th>
        <th>Action Remarks</th>
    </tr>
<?php
    $total = 0;
        foreach($data as $d):
            echo "<tr>";
                echo "<th>".$d['InitialInvoice']['bill_no']."</th>";
                echo "<td>".$d['InitialInvoice']['cost_center']."</td>";
                echo "<td>".$d['InitialInvoice']['invoiceDescription']."</td>";
                echo "<td>".$d['0']['grnd']."</td>";
                $date = $d['InitialInvoice']['grn_date'];
                if(!empty($date))
                {
                    $date = date_format(date_create($date), 'd-M-Y');
                }
                else
                    $date = "UnAllocated";
                echo "<td>".$date."</td>";
                echo "<td>".$d['InitialInvoice']['grn_remarks']."</td>";
                
            echo "</tr>";
            $total +=$d['0']['grnd'];
        endforeach;
?>
    <tr>
        <th colspan="3">Total</th>
        <th><?php echo $total; ?></th>
    </tr>
</table>
<?php } else if($report == 'invoice') { ?>
<table id="table_id2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <td colspan="2"><?php echo $branch." (".$month.")";  ?></td>
        <td colspan="3">Invoices Submitted</td>
    </tr>
    <tr>
        <th>Invoice No</th>
        <th>Cost Center</th>
        <th>Remarks</th>
        <th>Amount</th>
    </tr>
<?php
    $total = 0;
        foreach($data as $d):
            echo "<tr>";
                echo "<th>".$d['InitialInvoice']['bill_no']."</th>";
                echo "<td>".$d['InitialInvoice']['cost_center']."</td>";
                echo "<td>".$d['InitialInvoice']['invoiceDescription']."</td>";
                echo "<td>".$d['0']['grnd']."</td>";
            echo "</tr>";
            $total +=$d['0']['grnd'];
        endforeach;
?>
    <tr>
        <th colspan="3">Total</th>
        <th><?php echo $total; ?></th>
    </tr>
</table>
<?php } else {} ?>

