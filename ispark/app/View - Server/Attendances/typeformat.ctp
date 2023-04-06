<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
       
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
                    
                    <span>Type Of Incentive</span>
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
        <label class="col-sm-2 control-label">Type of Data</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               ?>

                 <select name="ftype" class="form-control" onchange=" incentivstatustypeq(this.value);">
        <option value="">Type</option>
        <option value="Mannual">Mannual</option>
        <option value="Bulk">Bulk</option>
    </select>
                
                
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div>
                </div>
                
                
                <div id="dataformat">
                
                
                </div>

		
		</div>
            </div>
        </div>
    </div>
</div>

