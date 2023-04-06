<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function backpage(val)
   {
 window.location.href='http://192.168.137.230/ispark/Attendances/typeformat?BranchId='+val;
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
<?php echo $this->Form->create('Attendances',array('class'=>'form-horizontal','action'=>'incentive','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Incentive Update</span>
                    

		</div>
                
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
                
            </div>
            <div class="box-content">
                <div valign="top" align="right"><input type="button" name="back" value="Back" class="btn btn-info"  onclick="backpage('BACK')" /></a></div>
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Employe</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               echo $this->Form->input('EmpCode',array('label' => false,'class'=>'form-control','value'=>'','placeholder'=>'EmpCode','required'=>true));  ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div>
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Salyear',array('label' => false,'options'=>array('2017'=>'2017','2018'=>'2018'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
                </div>
		<div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                 <?php echo $this->Form->input('salmonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div></div>
            <label class="col-sm-2 control-label">Incentive</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('incamt', array('label'=>false,'class'=>'form-control','value'=>'','placeholder'=>'Incentive','required'=>true)); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
    </div>
                <div class="form-group has-info has-feedback">
                 <label class="col-sm-2 control-label">Remarks</label>
                <div class="col-sm-3">
                    <?php	echo $this->Form->textarea('Remarks', array('label'=>false,'class'=>'form-control','value'=>'','placeholder' => 'Enter Remarks','required'=>true)); ?>
                </div>

                </div>

		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                       <input type="submit" class="btn btn-info"  name='Save' value="Save" >
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>

