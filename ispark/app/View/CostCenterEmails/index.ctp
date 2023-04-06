<?php //print_r($cost_center2); ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
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
				<h4 class="page-header">Add Email</h4>
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('CostCenterEmail',array('class'=>'form-horizontal','action'=>'add')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Cost Center</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('cost_center',array('label' => false,'options'=>$cost_center,'empty'=>'Select Cost Center','class'=>'form-control')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Process Manager</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('pm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
                                                <label class="col-sm-2 control-label">Admin</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('admin',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Manager</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('bm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
                                                <label class="col-sm-2 control-label">Regional Manager</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('rm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Corp</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('corp',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
                                                <label class="col-sm-2 control-label">CEO</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('ceo',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Client Email</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('clientId',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S")); ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Submit
							</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Client Name</span>
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
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
				<?php $case=array('class',''); $i=0; ?>
					<thead>
						<tr class="active">
							<td>Sr. No.</td>
							<td>Branch Name</td>
                                                        <td>Client Name</td>
                                                        <td>Cost Center</td>
							<td>Action</td>
                                                        
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($cost_center2 as $cost): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $cost['cm']['branch']; ?></td>
							<td><?php echo $cost['cm']['client']; ?></td>
                                                        <td><?php echo $cost['cm']['cost_center']; ?></td>
							<td><?php echo $this->Html->link('Edit',array('controller'=>'CostCenterEmails','action'=>'edit','?'=>array('id'=>$cost['cm']['Id']),'full_base' => true)); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>