<script>
    function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode == 46)
        {            
		return false;
        }
        
	return true;
}

    

</script>    
<?php

if(!empty($Data))
{
    echo $this->Form->create('Targets',array('url'=>'save_basic_indirect1'));
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
    $i= 0;
    $d2 = 0;
     $c = 0;
     $cnt = 0;
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
                $msg = "Record Not Saved. Please Save Record";
            }
            else if($totalActual!=$ActualAmount)
            {
                $msg = '<font color="red">Amount MisMatched. Actual Amount is not Same As New Basic Amount. If You Want To Change Actual Amount Then Please Save & Replace Actual Amount</font>';
            }
            else
            {
                $msg = '<font color="green">Amount Matched</font>';
            }
            
             echo "<tr>";
             echo "<td colspan='3'>$msg</td>";
             echo "<th>Total</th>";
            
             
             
             echo "<th>". $total ."</th>";
             echo '<th><div id="totalNewBasic">'. $totalActual ."</div></th>";
              
            echo "</tr>";
            
    ?>
</table>
<div class="form-group">
    <label class="col-sm-4 control-label"></label>
    <div class="col-sm-4">
        <button name="Save" id="Save" value="Save" class="btn btn-primary" >Save</button>
        <button name="Save" id="Save" value="Replace" class="btn btn-primary" >Save & Replace Actual</button>
        
    </div>
    <label class="col-sm-3 control-label">Actual Dashboard Amount</label>
    <label class="col-sm-1 control-label"><?php echo $ActualAmount; ?></label>
</div>
<?php
echo $this->Form->input('finYear',array('type'=>'hidden','value'=>$finYear));
echo $this->Form->input('finMonth',array('type'=>'hidden','value'=>$finMonth));
echo $this->Form->input('Branch',array('type'=>'hidden','value'=>$Branch));
echo $this->Form->input('cost_id',array('type'=>'hidden','value'=>$cost_id));
echo $this->Form->input('type',array('type'=>'hidden','value'=>'direct'));
echo $this->Form->input('allIds',array('type'=>'hidden','id'=>'allIds','value'=>implode(",",$NewIds)));
echo $this->Form->end();
}

else
{
    echo "No Record Found";
}