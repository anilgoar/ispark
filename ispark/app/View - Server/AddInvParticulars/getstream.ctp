<?php //print_r($res); ?>
<?php foreach($process_master as $post): ?>
<?php $data[$post['Addprocess']['process_name']]=$post['Addprocess']['process_name']; ?>
<?php endforeach;?>
<?php unset($Addprocess);?>
<?php	echo $this->Form->input('process', array('label'=>false,'name'=>'data[CostCenterMaster][process]','class'=>'form-control','options'=>$data,'empty' => 'Select Process')); ?>