<?php
if(!empty($cost_arr))
{
echo $this->Form->input('Dashs.cost_centerId',array('label'=>false,'options'=>array_merge(array('All'=>'All'),$cost_arr),'empty'=>'Select','id'=>'cost_center','class'=>'form-control'));
}
else
{
    echo $this->Form->input('Dashs.cost_centerId',array('label'=>false,'options'=>'','empty'=>'No Records Found','required'=>true,'class'=>'form-control','readonly'=>true));
}
?>