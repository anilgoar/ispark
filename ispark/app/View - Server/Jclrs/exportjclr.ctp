
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
<?php echo $this->Form->create('upload',array('class'=>'form-horizontal')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>JCLR Data Export</span>
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
<label class="col-sm-2 control-label">Dept</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               echo $this->Form->input('Dept',array('label' => false,'options'=>$Data1,'class'=>'form-control','empty'=>'Select','id'=>'Dept'));  ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div>
<label class="col-sm-2 control-label">Employee Status</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php
               //print_r($Data1);die;
              // foreach($Data as $d){
               echo $this->Form->input('Status',array('label' => false,'options'=>array('All'=>'All','1'=>'Active','0'=>'Left'),'class'=>'form-control','empty'=>'Select','id'=>'Status'));  ?>


                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div> </div>
</div>

<?php echo $this->Form->end(); ?>
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                       <input type="submit" class="btn btn-info"  name='export' value="Export" >
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>


