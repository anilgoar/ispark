<?php ?>

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
                    
                    <span>DISCARD ATTENDANCE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <div valign="top" align="right"></a></div>
               
                <?php echo $this->Session->flash(); ?>
		<div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                           <?php
                           foreach ($datay as $k=>$v){
                            $datef[$k]=date_format(date_create($v),"d-M-y");  
                           }
                           echo $this->Form->input('AttandDate',array('label' => false,'options'=>$datef,'class'=>'form-control','empty'=>'Select Date','id'=>'AttandDate')); ?> 
                        </div>    
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group">
                           <button type="Upload" class="btn btn-primary btn-label-left  btn-new" onclick="return confirm('Do you really Discard Attendance?');">
                            Discard
                        </button>
                            <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        </div>    
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

