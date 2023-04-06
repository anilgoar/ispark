<?php
$data = array('All'=>'All');

//print_r($cost_center); exit;

foreach($cost_center as $c )
{
    $data[$c['cm']['cost_center']] = $c['cm']['cost_center'];
}
//print_r($data);
  echo  $this->Form->input('Addcompany.cost_center',array('label'=>false,'options'=>$data,'empty'=>'Select Cost Center','class'=>'form-control'));


?>