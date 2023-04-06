<?php

?>
<style>
    table td{margin: 5px;}
</style>


<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<?php echo $this->Form->create('upload',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Incentive Discard</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
		<div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
              <?php echo $this->Form->input('finance_year',array('label' => false,'options'=>array('2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('month',array('label' => false,'options'=>$month,
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    </div>

		
    </div>


		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="Upload" class="btn btn-primary btn-label-left" onclick="return confirm('Do you really Discard Incentive?');">
                            Discard
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>

