<?php
$backurl=$this->webroot."Menus?AX=MTA3";
?>
<script>
function validateTax(){ 
    $("#msgerr").remove();
    var EmpMonth    =   $.trim($("#EmpMonth").val());
    var EmpCode     =   $.trim($("#EmpCode").val());
    
    if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#UploadEcs").focus();
        $("#UploadEcs").after("<span id='msgerr' style='color:red;font-size:11px;'>Please upload file.</span>");
        return false;
    }
    else{
        if(confirm("Are you sure want to discard this employee tax.")){
            return true;
        }
        else{
           return false; 
        }
        
    }
}
</script>

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
                    <span>DISCARD TAX DETAILS</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('UploadTaxDetails',array('action'=>'discard_tax_details','onSubmit'=>"return validateTax()",'class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonth" name="EmpMonth" autocomplete="off" class="form-control" required="" >
                            <option value="<?php echo date('Y-m', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Emp Code</label>
                    <div class="col-sm-2">
                        <input type="text" name="EmpCode" id="EmpCode" autocomplete="off" class="form-control" required="" >
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <!--
                        <input type="button" onclick="validateTax('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        -->
                        <input type="submit" value="Submit" class="btn pull-right btn-primary btn-new">
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
