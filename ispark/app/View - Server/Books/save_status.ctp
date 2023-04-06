<?php

?>
<style>
    table td{margin: 5px;}
</style>

<script>
        function statustype(val,ide)
        {
            //alert(val);
            $.post("get_status_data",{types:val},function(data)
            {
                  
                $("#"+ide).html(data);});

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
<?php echo $this->Form->create('Save',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Save Status</span>
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
                
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Save Book Status</label>
                    </div>

                    <?php
foreach($particular as $d)
{	

?>

<div class="form-group has-success has-feedback">
  
 <label class="col-sm-1 control-label">Particular</label> 
<div class="col-sm-3"> 
    <input type="text" name="particulars[]" required="" readonly="" class="form-control" value="<?php echo $d['tbl_book']['Particulars'] ?>">
</div> <label class="col-sm-1 control-label">VCH Type</label>
<div class="col-sm-2">
     <input type="text" name="VchType[]" required="" readonly="" class="form-control" value="<?php echo $d['tbl_book']['VchType'] ?>">
 </div><div class="col-sm-2">
    <select name="stype[]" class="form-control" onchange="return statustype(this.value,'<?php echo 'mm'.$d['tbl_book']['id'] ?>');">
        <option value="">Type</option>
        <option value="New">New</option>
        <option value="Exist">Exist</option>
    </select>
</div>

<label class="col-sm-1 control-label">Status</label>
<div class="col-sm-2">
    <div id="<?php echo 'mm'.$d['tbl_book']['id'] ?>"></div>
    
 </div>
</div>
<?php
}
                    ?>
                   
                
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="Save" class="btn btn-primary btn-label-left">
                            save
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>

