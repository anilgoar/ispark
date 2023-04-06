<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
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
            <div class="box-header"  >
                <div class="box-name">
                    <span>UPLOAD Salary Days</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                <?php echo $this->Form->create('UpdateSalaryDays',array('class'=>'form-horizontal','action'=>'index','enctype'=>'multipart/form-data')); ?>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Select Month</label>
                    <div class="col-sm-2">
                        <select name="UploadType" id="UploadType" autocomplete="off" required="" class="form-control" >
                            <option value="">Select</option>
                            <option value="<?php echo date('Y-m', strtotime(date('Y-m')." -2 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -2 month"));?></option>
                            <option value="<?php echo date('Y-m', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></option>
                        </select>
                    </div>
                   
                    <label class="col-sm-2 control-label">Upload File</label>
                    <div class="col-sm-2">
                        <div class="col-sm-2"><input type="file" name="UploadIncentive" accept=".csv" required="" ></div>
                    </div>
                    
                    <div class="col-sm-3">
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
              
                <div class="form-group">
                    <div class="col-sm-12">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?> 
            </div>
        </div>
    </div>	
</div>




























