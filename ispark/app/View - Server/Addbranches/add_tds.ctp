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
				<h4 class="page-header">Add Branch</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Addbranch',array('class'=>'form-horizontal','action'=>'add')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_name',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Name')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_code',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Address</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_address',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">State  </label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state',array('label' => false,'class'=>'form-control','placeholder'=>'State')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">State  Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state_code',array('label' => false,'class'=>'form-control','placeholder'=>'State Code')); ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
							<span><i class="fa fa-clock-o"></i></span>
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
					<span>Branch Name</span>
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

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
					<thead>
						<tr class="active">
							<td align="center"><b>Sr. No.</b></td>
							<td align="center"><b>Branch Name</b></td>
                                                        <td align="center"><b>Branch Address</b></td>
							<td align="center"><b>State</b></td>
                                                        <td align="center"><b>State Code</b></td>
                                                        <td align="center"><b>Edit</b></td>
                                                        <td align="center"><b>Status</b></td>
                                                        
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($branch_master as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><code><?php echo $post['Addbranch']['branch_name']; ?></code></td>
                                                        <td align="center"><?php echo $post['Addbranch']['branch_address']; ?></td>
                                                        <td align="center"><?php echo $post['Addbranch']['state']; ?></td>
                                                        <td align="center"><?php echo $post['Addbranch']['state_code']; ?></td>
							<td align="center"><?php echo $this->Html->link('Edit',array('controller'=>'Addbranches','action'=>'edit','?'=>array('id'=>$post['Addbranch']['id']),'full_base' => true)); ?></code></td>
                                                        <td align="center"><?php echo $post['Addbranch']['active']=='1'?'Active':'Deactive'; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($Addbranch); ?>
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