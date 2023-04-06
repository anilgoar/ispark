<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><?php echo $this->Html->link('Dashboard',array('controller'=>'Provisions','action'=>'dashboard')); ?></li>
			<li><a href="#">Tables</a></li>
			<li><a href="#">Simple Tables</a></li>
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">Edit Branch</h4>
								
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Addbranch',array('class'=>'form-horizontal','action'=>'edit')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_name',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Name', 'value'=>$branch_master['Addbranch']['branch_name'])); ?>
							
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_code',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code','value'=>$branch_master['Addbranch']['branch_code'])); ?>
							<?php echo $this->Form->input('branch_id',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code','value'=>$branch_master['Addbranch']['id'],'type'=>'hidden')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
						<?php echo $this->Form->input('active',array('label' => false,'class'=>'form-control','options'=>array('1'=>'Active','0'=>'Deactive'),'value'=>$branch_master['Addbranch']['active'])); ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
							<span><i class="fa fa-clock-o"></i></span>
								Save
							</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
