
<style>
.req{
    color:red;
    font-weight: bold;
    font-size: 16px;
}
.msger{
    color:red;
    font-size:11px;
}
.bordered{
    border-color: red;
}
.col-sm-2{margin-top:-12px !important;}
.col-sm-3{margin-top:-12px !important;}
</style>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
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
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Activity Report</span>
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
<div class="box-content box-con">
    <h4 class="textClass"><?php echo $this->Session->flash(); ?></h4>

<?php 

echo $this->Form->create('Activitys',array('class'=>'form-horizontal','action'=>'get_report11')); 

?>
		<div class="form-group">								
			
			<label class="col-sm-2 control-label"><b style="font-size:14px">Start Date </b></label>
				<div class="col-sm-3">
					<?php	echo $this->Form->input('ToDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Activitys][ToDate]');",'required'=>true)); ?>
				</div>
			<label class="col-sm-2 control-label"><b style="font-size:14px">End Date  </b></label>
				<div class="col-sm-3">
					<?php	echo $this->Form->input('FromDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Activitys][FromDate]');",'required'=>true)); ?>
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

