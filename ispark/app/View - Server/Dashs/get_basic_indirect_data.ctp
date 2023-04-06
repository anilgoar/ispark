<?php

if(!empty($Data))
{
?>
<table class="table table-hover table-bordered" border="1">
    <tr>
        <th>Process</th>
        <th>Cost Center</th>
        <th>Expense Head</th>
        <th>Expense SubHead</th>
        <th>Basic Amount</th>
        <th>New Basic Amount</th>
    </tr>
    <?php
    
            $cnt = count($Data);
//            echo "<tr>";
//            echo "<td colspan='4'></td>";
//            echo "<td>Actual Amount</td>";
//            echo "<td>$ActualAmount</td>";
//            echo "</tr>";
            foreach($Data as $d)
            {
                echo "<tr>";
                   echo "<td>".$d['cm']['process_name']."</td>";
                   echo "<td>".$d['cm']['cost_center']."</td>";
                  
                   echo "<td>".$d['hm']['HeadingDesc']."</td>";
                   echo "<td>".$d['sh']['SubHeadingDesc']."</td>";
                   echo "<td>".$d['ep']['Amount']."</td>";
                   if(empty($NewData[$d['ep']['id']]))
                   {
                        $NewAmount =   $d['ep']['Amount'];
                   }
                   else
                   {
                       $NewAmount = $NewData[$d['ep']['id']];
                   }
                   echo "<td>".$this->Form->input($d['ep']['id'].'.amount',array('label'=>false,'value'=>$NewAmount,"onKeyPress"=>"return checkNumber(this.value,event)",'onblur'=>"get_sum_new_basic()",'onpaste'=>"return false"))."</td>";
                echo "</tr>";
                
                 $total +=$d['ep']['Amount'];
                 $totalActual +=$NewData[$d['ep']['id']];
                 $NewIds[] = $d['ep']['id'];
            }
            
            if(empty($totalActual))
            {
                $msg = '<font color="red">Record Not Saved. Please Save Record</font>';
            }
            else if($totalActual!=$ActualAmount)
            {
                $msg = '<font color="red">Amount MisMatched. Actual Amount is not Same As New Basic Amount. If You Want To Change Actual Amount Then Please Save & Replace Actual Amount</font>';
            }
            else
            {
                $msg = '<font color="green">Amout Matched</font>';
            }
            
             echo "<tr>";
             echo "<td colspan='3'>$msg</td>";
             echo "<th>Total</th>";
             echo "<th>". $total ."</th>";
             echo '<th><div id="totalNewBasic">'. $totalActual ."</div></th>";
             
            echo "</tr>";
            
    ?>
</table>

<?php

}

else
{
    echo "No Record Found";
}