<?php //print_r($data); ?>

<?php
if(isset($data['company_name']))
{
  $data = array('All'=>'All');	
}else{
	
	          foreach($data as $post):
			  $data1[$post['CostCenterMaster']['branch']]=$post['CostCenterMaster']['branch'];
			  endforeach;
			  $data=$data1;
		}

echo $this->Form->input('branch_name',array('label'=>false,'options'=>$data,'class' =>'form-control','id'=>'AddBranchName'));
?>