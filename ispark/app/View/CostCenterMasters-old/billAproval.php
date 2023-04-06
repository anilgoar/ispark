<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'billApproval')); ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="../../Controller/index.html">Dashboard</a></li>
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
					<h4 class="page-header"><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary">
							<?php	echo $this->Form->checkbox('Initial Invoice', array('label'=>false)); ?>Initial Invoice</label></div></h4>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-1 control-label">Branch</label>
						<label class="col-sm-2 control-label"><?php echo $this->request->data['InitialInvoice']['branch']; ?></label>
							<?php	//echo $this->Form->input('company', array('label'=>false,'class'=>'form-control','options' => array(1, 2, 3, 4, 5),'empty' => 'Select Company')); ?>
							
						<label class="col-sm-2 control-label">Cost Center</label>
						<?php	//echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Branch')); ?>
						<label class="col-sm-1 control-label"><?php echo $this->request->data['InitialInvoice']['costCenter']; ?></label>
						<label class="col-sm-2 control-label">Financial Year</label>
						<label class="col-sm-1 control-label">2015-2016</label>
							<?php	//echo $this->Form->input('costCenter', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Cost Center')); ?>
						
						<label class="col-sm-2 control-label">Month for</label>
						<label class="col-sm-1 control-label">July</label>
							<?php	//echo $this->Form->input('financeYear', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Year')); ?>
						</div>						
					</div>
					</div>
				</div>
			</div>



			



