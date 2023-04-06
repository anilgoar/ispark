<?php //print_r($data); ?>

<?php

  $data1 = array('All'=>'All');	
if(!empty($data))
{
	
  foreach($data as $post):
    $data1[$post['CostCenterMaster']['branch']]=$post['CostCenterMaster']['branch'];
  endforeach;
    
}
$data=$data1;
echo $this->Form->input('branch_name',array('label'=>false,'options'=>$data,'class' =>'form-control','id'=>'AddBranchName'));
?>