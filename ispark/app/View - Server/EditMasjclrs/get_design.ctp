<?php

echo $this->Form->input('MasJclrMaster.Desgination',array('label'=>false,'options'=>$Desig,'empty'=>'Select ','required'=>true,'class'=>'form-control','style'=>'width:202px;','onChange'=>'band(this.value)'));
?>