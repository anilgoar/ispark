<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
function validateForm(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var FromDate    =   $("#FromDate").val();
    var ToDate      =   $("#ToDate").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name</span>");
        return false;
    }
    else if(FromDate ===""){
        $("#FromDate").focus();
        $("#FromDate").after("<span id='msgerr' style='color:red;'>Please select from date.</span>");
        return false;
    }
    else if(ToDate ===""){
        $("#ToDate").focus();
        $("#ToDate").after("<span id='msgerr' style='color:red;'>Please select to date.</span>");
        return false;
    }
    else{
        return true;
    }   
}

$(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
    });
});
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
                    <span>Ticket Solution Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'ticket_solution_report','class'=>'form-horizontal','onsubmit'=>'return validateForm()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>  
                    
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-2">
                        <input type="text" id="FromDate" name="FromDate" autocomplete="off" value="<?php echo isset($FromDate)?$FromDate:""?>" class="form-control datepik"  >
                    </div>
                    
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <input type="text" id="ToDate" name="ToDate" autocomplete="off" value="<?php echo isset($ToDate)?$ToDate:""?>" class="form-control datepik" >
                    </div>
                    
                    
                    <div class="col-sm-1" style="margin-top:-5px;">
                        <input type="submit" name="Submit"  value="Export"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>             
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
