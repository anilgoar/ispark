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

<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal')); ?>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>ALL Users</span>
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
				<h4 class="page-header">Select User</h4>
										
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">User Name</label>
						<div class="col-sm-3">

							<?php echo $this->Form->input('user', array('options' => $user,'empty' => 'Select 

User','label' => false, 'div' => false,'class'=>'form-control')); ?>
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-success btn-label-left"><b>Go</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
						</div>

					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>