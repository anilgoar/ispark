<?php //print_r($data);
$cost =array(); if(!empty($data)) { ?>
<?php

foreach($data as $post):
    if(!empty($post['tab']['branch_name']) && !empty($post['tab']['month']))
    {
    $monthArr[] = $post['tab']['month'];
    $branchArr[] = $post['tab']['branch_name'];
    $row[$post['tab']['branch_name']][$post['tab']['month']] = $post;
    }
endforeach;

    $monthArr = array_values(array_unique($monthArr));
//print_r($row);
?>


<table id="table_id" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <th colspan="<?php echo count($monthArr)+1; ?>"><?php echo $branch; ?></th>
    </tr>
<tr>
    <th>Billing Pending</th>
    <?php
    $sumArr = array();
            for($i = 0; $i<count($monthArr); $i++)
            {
                echo "<th>".$monthArr[$i]."</th>";
                $sumArr[$monthArr[$i]] = 0;
            }
            echo "<th>Total</th>";
    ?>
</tr>

<?php $sumProvision = 0;$sumPO = 0;$sumGRN = 0;$sumInitiated = 0; $sumInvoice=0;
        foreach($row as $k=>$v)
        {
            echo "<tr>";
                echo "<td align='center'>Bill To Be Raised</td>";
            foreach($monthArr as $m)
            {
                    echo '<td onClick="myFunction(\''.$k.'@@'.$m.'@@provision'.'\')">'.$v[$m]['0']['Provision'].'</td>';
                    $sumProvision += $v[$m]['0']['Provision'];
                    $sumArr[$m] += $v[$m]['0']['Provision'];
            }
            echo "<th>".$sumProvision."</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>PO Pending</td>";
            foreach($monthArr as $m)
            {
                if(empty($v[$m]['0']['PO Pending'])) $v[$m]['0']['PO Pending'] = 0;
                    echo '<td onClick="myFunction(\''.$k.'@@'.$m.'@@po'.'\')">'.$v[$m]['0']['PO Pending'].'</td>';
                    $sumPO += $v[$m]['0']['PO Pending'];
                    $sumArr[$m] += $v[$m]['0']['PO Pending'];
            }
            echo "<th>".$sumPO."</th>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td align='center'>GRN Pending</td>";
            foreach($monthArr as $m)
            {
                    echo '<td onClick="myFunction(\''.$k.'@@'.$m.'@@grn'.'\')">'.$v[$m]['0']['GRN Pending'].'</td>';
                    $sumGRN += $v[$m]['0']['GRN Pending'];
                    $sumArr[$m] += $v[$m]['0']['GRN Pending'];
            }
            echo "<th>".$sumGRN."</th>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td align='center'>Invoice Submitted</td>"; 
            foreach($monthArr as $m)
            {
                    echo '<td onClick="myFunction(\''.$k.'@@'.$m.'@@invoice'.'\')">'.$v[$m]['0']['InvoiceSubmit'].'</td>';
                    $sumInvoice += $v[$m]['0']['InvoiceSubmit'];
                    $sumArr[$m] += $v[$m]['0']['InvoiceSubmit'];
            }
            echo "<th>".$sumInvoice."</th>";
            echo "</tr>";
        }
?>
<tr>
    <th>Total</th>
    <?php $sumTotal = 0;
    foreach($monthArr as $m)
            {
                    echo '<th>'.$sumArr[$m].'</th>';
                    $sumTotal += $sumArr[$m];
            }
            echo "<th>".$sumTotal."</th>";
    ?>
</tr>
    <?php
   //         foreach($data as $pro):
   //             echo "<tr>";
   //                     echo "<td>".$pro['pm']['Billing Pending']."</td>";
   //                     echo "<td>".$pro['0']['PO Pending']."</td>";
   //                     echo "<td>".$pro['0']['GRN Pending']."</td>";
   //                     echo "<td>".$pro['0']['Agreement Pending']."</td>";
   //                     echo "<td>".$pro['0']['Invoice No']."</td>";
   //                     echo "<td>".$pro['0']['PTP Date']."</td>";
   //                     echo "<td>".$pro['0']['Payment']."</td>";
   //             echo "</tr>";
   //         endforeach;
    ?>

</table>

<div id="result">

    </div>

<?php } ?>