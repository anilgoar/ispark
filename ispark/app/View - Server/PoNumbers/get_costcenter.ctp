<?php
echo $this->Form->input('PoNumber.cost_center',array('label'=>false,'options'=>$cost,'empty'=>'select Cost Center','multiple'=>true,'onClick'=>'write_costcenter()','class'=>'form-control'));
