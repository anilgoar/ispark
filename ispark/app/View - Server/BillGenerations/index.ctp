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
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'get_bill_generation')); ?>

	<div class="form-group has-success has-feedback">
		<label class="col-sm-1 control-label"><b style="font-size:14px">From</b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('FromDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Add][FromDate]');",'required'=>true)); ?>
			</div>

		<label class="col-sm-1 control-label"><b style="font-size:14px">To</b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('ToDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Add][ToDate]');",'required'=>true)); ?>
			</div>
						
		<label class="col-sm-1 control-label"><b style="font-size:14px">Company</b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('company_name', array('label'=>false,'options'=>$data,'empty'=>'Select Company','class'=>'form-control','onChange'=>'get_branch2(this.value)')); ?>
			</div>
		</div>
		<div class="form-group has-success has-feedback">								
			
			<div id="mm">
                            <label class="col-sm-1 control-label"><b style="font-size:14px"> Branch </b></label>	
                            <div class="col-sm-3">							
                                <?php	echo $this->Form->input('status', array('label'=>false,'empty'=>'Select Status','options'=>'','class'=>'form-control')); ?>
                            </div>
			</div>
                        <label class="col-sm-1 control-label"><b style="font-size:14px"> Report </b></label>
			<div class="col-sm-3">
                            <?php echo $this->Form->input('reportType', array('label'=>false,'empty'=>'Report','class'=>'form-control','options'=>array('Details'=>'Details','Summary'=>'Summary'))); ?>
			</div>        
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" value = "show" onClick="show_billgeneration(this.value);">Show</button>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" value = "export" onClick="show_billgeneration(this.value);">Export</button>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-edit"></i>
					<span>Bill Edit Report</span>
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
