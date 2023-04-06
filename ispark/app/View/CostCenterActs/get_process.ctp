<?php
//if(!empty($process))
//{
//echo $this->Form->input('Targets.branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select','required'=>true,'onChange'=>'costcenter(this.value)','class'=>'form-control'));
//}
//else
//{
//    echo $this->Form->input('Targets.branch_process',array('label'=>false,'options'=>'','empty'=>'Records Not Found','required'=>true,'class'=>'form-control','readonly'=>true));
//}
?>

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