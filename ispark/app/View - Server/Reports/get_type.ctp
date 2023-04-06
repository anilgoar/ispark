<?php //print_r($res); ?>
<?php $data['all'] = 'All'; ?>

<?php 	if($res ==1){ ?>

<?php 
foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
endforeach;
?>

<label class="col-sm-1 control-label"><b style="font-size:14px"> Branch </b></label>	
<div class="col-sm-3">
	<?php	echo $this->Form->input('Branch', array('label'=>false,'options'=>$data,'empty'=>'Select Branch','class'=>'form-control')); ?>
</div>

<?php } else {?>


<?php
foreach($client_master as $post) :
	$data[$post['CostCenterMaster']['client']]=$post['CostCenterMaster']['client'];
endforeach;
?>

<label class="col-sm-1 control-label"><b style="font-size:14px">Client </b></label>	
<div class="col-sm-3">
	<?php	echo $this->Form->input('Client', array('label'=>false,'options'=>$data,'empty'=>'Select Client','class'=>'form-control')); ?>
</div>

<?php } ?>