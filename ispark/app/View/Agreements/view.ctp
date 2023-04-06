<script>
function getCostCenter(val)
{
    $.post("get_costcenter2",{branch_name:val},function(data)
    {$("#cost_center").html(data);});
}
function getData(val)
{
var branchid = $("#branchid").val();   //alert(branchid);
 $.post("get_agreement_data",{branch_name:branchid,cost_center:val},function(data)
    {$("#mm").html(data);});
}
</script>

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
                    <span>Search Agreement</span>
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
		<h4 class="page-header"></h4>
                <?php echo $this->Form->create('Agreement',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group has-feedback">
                <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label'=>false,'options'=>$branch_master,'empty'=>
                    'Select Branch','class'=>'form-control','id'=>'branchid','required'=>true,'onChange'=>'getCostCenter(this.value)')); ?>
                    </div>
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3"> 
                        <div id='cost_center'>
                            <?php echo $this->Form->input('cost_center',array('label'=>false,'options'=>'','empty'=>
                            'Select Cost Center','All'=>'All','class'=>'form-control','required'=>true,'onChange'=>'getData(this.value)')); ?>
                        </div>
                    </div>
			<!--
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary btn-label-left">&nbsp;&nbsp;&nbsp;<b>Search</b>&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>-->
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
	</div>
    </div>
</div>
	
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>View & Download Agreement</span>
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
		<div id="mm"></div>
            </div>
	</div>
    </div>
</div>