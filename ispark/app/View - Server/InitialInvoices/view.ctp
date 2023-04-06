<?php //print_r($branch_master); ?>
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>
<?php foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Invoice Approval</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
						<div class="btn-group" data-toggle="buttons">
						
							<b style="font-size:14px"> Select Branch </b>	<?php	echo $this->Form->input('branch_name', array('label'=>false,'options'=>$data,'empty'=>'Select Branch','class'=>'form-control','onChange'=>'getInvoices(this)')); ?>
							<?php
							$this->Js->event('change',    $this->Js->request(array('controller' => 'AddInvParticular','action'=>'view'),
							        array('async' => true, 'update' => '#mm')));
							 ?>
						</div>
					</h4>
					<div id="mm"></div>
					</div>
					</div>
				</div>
			</div>
<?php $this->Form->end();?>