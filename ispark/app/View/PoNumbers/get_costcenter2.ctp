<?php
echo $this->Form->input('Agreement.cost_center',array('label'=>false,'options'=>$cost,'empty'=>'select Cost Center','onClick'=>'getData(this.value)','class'=>'form-control'));
