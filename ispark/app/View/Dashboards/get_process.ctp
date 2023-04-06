<?php
echo $this->Form->input('Dashboard.branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select Process','required'=>true,'onChange'=>'costcenter(this.value)','class'=>'form-control'));
?>