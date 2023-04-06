<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function backpage(val)
   {
 window.location.href='http://192.168.137.230/ispark/Attendances/typeformat?Back='+val;
   } 
</script>

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
                    
                    <span>Incentive Upload</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <div valign="top" align="right"><input type="button" name="back" value="Back" class="btn btn-primary btn-label-left"  onclick="backpage('BACK')" /></a></div>
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                  <div class="form-group has-info has-feedback">
                 <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Salyear',array('label' => false,'options'=>array('2017'=>'2017','2018'=>'2018','2019'=>'2019'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
              
		
        
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php echo $this->Form->input('salmonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div></div>  </div>
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Upload Incentive </label>
                    <div class="col-sm-6">
                    <?php	
                    echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true,'accept'=>'.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'));
                    ?>
                    </div>
                </div>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="Upload" class="btn btn-primary btn-label-left">
                            Upload
			</button>
                    </div>
                     
		</div>
            </div>
        </div>
    </div>
</div>

