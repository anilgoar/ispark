
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

<?php 

echo $this->Form->create('Books',array('action'=>'get_report15')); 

?>
		<div class="form-group has-success has-feedback">								
			
			<label class="col-sm-2 control-label"><b style="font-size:14px"> Date To </b></label>
				<div class="col-sm-2">
					<?php	echo $this->Form->input('ToDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Books][ToDate]');",'required'=>true)); ?>
				</div>
			<label class="col-sm-2 control-label"><b style="font-size:14px"> Date From </b></label>
				<div class="col-sm-2">
					<?php	echo $this->Form->input('FromDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Books][FromDate]');",'required'=>true)); ?>
				</div>
			
				
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
                                <input type="submit" class="btn btn-info"  name='export' value="Export" >
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

