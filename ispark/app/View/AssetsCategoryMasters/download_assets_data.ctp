<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
$(function (){
    $(".textdatepicker").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function validateForm(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name</span>");
        return false;
    }
    else{
        return true;
        //window.open('<?php //echo $this->webroot?>pdf/examples/label.php?B='+BranchName+'&P='+Product+'&S1='+S1+'&S2='+S2, '_blank');
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
                    <span>Download Assets Data</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'download_assets_data','class'=>'form-horizontal','onsubmit'=>'return validateForm()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>                  
                    <div class="col-sm-1" style="margin-top:-5px;">
                        <input type="submit" name="Submit"  value="Download"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>             
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
