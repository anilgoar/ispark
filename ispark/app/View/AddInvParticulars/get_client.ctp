<?php //print_r($client_master); ?>
<?php foreach($client_master as $post): ?>
<?php $data[$post['Addclient']['client_name']]=$post['Addclient']['client_name']; ?>
<?php endforeach;?>
<?php unset($Addclient);?>
<?php	echo $this->Form->input('client', array('label'=>false,'name'=>'data[CostCenterMaster][client]','class'=>'form-control','options'=>$data,'empty' => 'Select Client')); ?>