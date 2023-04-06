<?php
echo $this->Form->input('MasJclrMaster.BioCode',array('label'=>false,'options'=>$bio,'empty'=>'Select BioCode','onchange'=>'empname(this.value);','required'=>true,'class'=>'form-control'));
?>