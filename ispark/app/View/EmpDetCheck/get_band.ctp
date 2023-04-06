<?php

echo $this->Form->input('MasJclrMaster.Band',array('label'=>false,'options'=>$Desig,'empty'=>'Select','style'=>'width:202px;','required'=>true,'class'=>'form-control','onChange'=>'getpackageData(this.value)'));
?>