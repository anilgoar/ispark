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
				<h4 class="page-header">Salary Head</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('SalaryHeads',array('class'=>'form-horizontal','action'=>'add')); ?>
					<div class="form-group">
						<label class="col-sm-1 control-label">Details</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('SalaryHead',array('label' => false,'class'=>'form-control','placeholder'=>'Details')); ?>
						</div>
                                                <label class="col-sm-3 control-label">Excel Column &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.g. 'A', 'E', 'AE'</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('SalaryColumn',array('label' => false,'class'=>'form-control','placeholder'=>'')); ?>
						</div>
                                                
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Save
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
					<span>Salary Head Details</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=0; ?>
					<thead>
						<tr class="active">
							<td align="center"><b>Sr. No.</b></td>
                                                        <td align="center"><b>Details</b></td>
							<td align="center"><b>Columns</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($SalaryHeadMaster as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $post['SalaryHead']['SalaryHead']; ?></td>
                                                        <td align="center"><?php echo $post['SalaryHead']['SalaryColumn']; ?></td>
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