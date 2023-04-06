<?php
if(!empty($tower1))
{
echo $this->Form->input('Targets.cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select','required'=>true,'class'=>'form-control'));
}
else
{
    echo $this->Form->input('Targets.cost_centerId',array('label'=>false,'options'=>'','empty'=>'No Records Found','required'=>true,'class'=>'form-control','readonly'=>true));
}
?>