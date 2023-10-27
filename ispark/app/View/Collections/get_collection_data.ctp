<?php //print_r($data); ?>

<?php 
if($data['pay_type'] == 'Cheque')

{	$prints = "Cheque"; }
else if($data['pay_type'] == 'Cash')

{	$prints = "Cash"; }
else
{	$prints = "RTGS"; }

?>
<label class="col-sm-2 control-label"> <?php echo $prints; ?> No.</label>
<div class="col-sm-3">
	<?php	
		if($prints == 'RTGS') 
			echo $this->Form->input('pay_no', array('label'=>false,'class'=>'form-control','value'=> $RTGS['0']['max(pay_no)']+1,'required'=>true,'id'=>'CollectionPayNo','onkeypress'=>'return isNumberKey(event)','maxlength'=>'10','readonly' =>true)); 
		else if($prints == 'Cash')
			echo $this->Form->input('pay_no', array('label'=>false,'class'=>'form-control','value'=> $RTGS['0']['max(pay_no)']+1,'required'=>true,'id'=>'CollectionPayNo','onkeypress'=>'return isNumberKey(event)','maxlength'=>'10','readonly' =>true)); 
		else
			echo $this->Form->input('pay_no', array('label'=>false,'class'=>'form-control','placeholder' => 'Cheque Number','id'=>'CollectionPayNo','maxlength'=>'6','onkeypress'=>'return isNumberKey(event)','required'=>true)); 
	?>
</div>
						

<label class="col-sm-2 control-label"><?php echo $prints; ?> Amount</label>
<div class="col-sm-3">
	<?php	echo $this->Form->input('pay_amount', array('label'=>false,'class'=>'form-control','id'=>'CollectionPayAmount','placeholder' => 'Amount','onkeypress'=>'return isNumberKey(event)','maxlength'=>'12','required'=>true)); ?>
</div>


