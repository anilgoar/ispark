<?php  


echo $this->Form->input('AddBranchName',array('label'=>false,'class'=>'form-control','options'=>$data,'empty'=>'Select Branch','onChange'=>'collection_report_client_new(this.value)'));
?>
