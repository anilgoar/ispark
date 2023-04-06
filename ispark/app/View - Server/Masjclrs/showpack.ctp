<?php

echo $this->Form->input('MasJclrMaster.Package',array('label'=>false,'options'=>$Des,'empty'=>'Select','required'=>true,'class'=>'form-control','style'=>'width:202px;','onChange'=>'getData(this.value)'));
?>