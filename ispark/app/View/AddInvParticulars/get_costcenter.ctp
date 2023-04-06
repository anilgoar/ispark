<?php //print_r($res); ?>
<?php foreach($cost_master as $post): ?>
<?php $data[$post['CostCenterMaster']['cost_center']]=$post['CostCenterMaster']['cost_center']; ?>
<?php endforeach;?>
<?php unset($CostCenterMaster);?>
<?php	echo $this->Form->input('InitialInvoice.cost_center', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Cost Center','required'=>true)); ?>