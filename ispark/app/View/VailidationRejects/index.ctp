<script type="text/javascript">
function getCostCenter(branch)
{
 $.post("<?php echo $this->webroot;?>VailidationRejects/get_costcenter",{branch:branch},function(data){
$("#costcenter").html(data);});
}


</script>
<div class="row">
	<div id="breadcrumb" class="col-sm-12">
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


<div class="box-content" >
<?php //print_r($branch_master); ?>
<?php $this->Form->create('VailidationRejects',array('class'=>'form-horizontal', 'url'=>'add','enctype'=>'multipart/form-data')); ?>

	<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select Branch','required'=>true,'onchange'=>'getCostCenter(this.value)','class'=>'form-control')); ?>
						</div>
</div>
		
		<div class="form-group has-success has-feedback">								
			
			<label class="col-sm-2 control-label">Cost Center</label>
				<div class="col-sm-4" id="costcenter">
					<?php echo $this->Form->input('cost_center',array('label'=>false,'options'=>$costcenter,'empty'=>'Select Cost Center','required'=>true,'class'=>'form-control')); ?>
				</div>
			
			
				
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" onClick="get_Show22();;">Show</button>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
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
                            <span>View Dashboard Report</span>
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
                        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                        <div class="form-horizontal">
                        <div class="form-group">

                            <div class="col-sm-12">
                                <div id="nn">
                                    
                                    
                                </div>
                            </div>
                         </div>
        
                         </div>



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
