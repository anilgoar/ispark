<?php
echo $this->Form->input('Dashboard.branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select Process','onChange'=>'DashboardData(this.value)','required'=>true,'class'=>'form-control'));
?>