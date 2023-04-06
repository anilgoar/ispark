<?php
if(!empty($tower1))
{
    echo $this->Form->input('Dashboard.cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>'get_freeze_data(this.value)'));
}
else
{
    echo $this->Form->input('Dashboard.cost_centerId',array('label'=>false,'options'=>"",'empty'=>'No CostCenter Found','required'=>true,'class'=>'form-control'));
}

?>