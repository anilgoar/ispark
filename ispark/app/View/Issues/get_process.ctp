<?php //print_r($data); ?>

<?php
/*
			$data = array();	
	          foreach($process as $post):
			  $data[$post['Process']['process_name']]=$post['Process']['process_name'];
			  endforeach;
                          */
echo $this->Form->input('Issues.process_name',array('label'=>false,'options'=>$data,'class' =>'form-control','id'=>'IssuesProcessName'));
?>