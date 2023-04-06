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
<?php $data['All'] = 'All'; ?>
<?php foreach($company_master as $post) :
	$data[$post['Addcompany']['company_name']]=$post['Addcompany']['company_name'];
	endforeach;
?>

<div class="box-content">
<?php //print_r($branch_master); ?>
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>

	<div class="form-group has-success has-feedback">
		

		<label class="col-sm-2 control-label"><b style="font-size:14px"> Select Company </b></label>	
			<div class="col-sm-2">
				<?php	echo $this->Form->input('company_name', array('label'=>false,'options'=>$data,'empty'=>'Select Company','class'=>'form-control','onChange' => 'get_branch3(this.value)')); ?>
			</div>
			<label class="col-sm-2 control-label"><b style="font-size:14px"> Select Branch </b></label>	
			<div class="col-sm-2">
            <div id ="mm">
				<?php	echo $this->Form->input('branch_name', array('label'=>false,'options'=>$data,'empty'=>'Select Branch','class'=>'form-control','onChange'=>'get_report5(this.value')); ?>
                </div>
			</div>			
		
		</div>
		<div class="form-group has-success has-feedback">
        <div id="mm"></div>
        <label class="col-sm-2 control-label"><b style="font-size:14px"> Report Name </b></label>	
			<div class="col-sm-2">
				<?php	echo $this->Form->input('Select Report', array('label'=>false,'options'=>array('Bill Initiated Not Approve'=>'Bill Initiated Not Approve','GRN Status'=>'GRN Status','Bill Submission Status'=>'Bill Submission Status','Bill Generate Status'=>'Bill Generate Status','PO Status'=>'PO Status'),'empty'=>'Select Report','class'=>'form-control')); ?>
			</div>								
			<div id="mm"></div>
			
			<label class="col-sm-2 control-label"><b style="font-size:14px"> &nbsp; </b></label>
				<div class="col-sm-2">
							&nbsp;&nbsp;&nbsp;
				</div>
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" value = "show" onClick="validate_BillApprovalStages(this.value);">Show</button>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" value = "export" onClick="validate_BillApprovalStages(this.value);">Export</button>
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
					<span>Report Format
                   </span>
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
				<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
				<div id = "nn"></div>
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
