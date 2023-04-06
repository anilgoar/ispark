<?php
$exp    =   explode("Menus?AX=", $_SERVER['HTTP_REFERER']);
$expid  =   end($exp);
if(isset($_REQUEST['backid'])){
    $backid=$_REQUEST['backid'];
    $backurl=$this->webroot."Menus?AX=".$_REQUEST['backid'];
}
else{
    $backid=$expid;
    $backurl=$this->webroot."Menus?AX=".$expid;
}
?>
<link href="<?php echo $this->webroot;?>css/jquery-ui.css" rel="stylesheet">
<script  src="<?php echo $this->webroot;?>js/jquery-ui.js" type="text/javascript" ></script>
<script language="javascript">
$(function () {
    $(".DatePicker").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function getbatchcode(BatchMonth){
    $("#msgerr").remove();
    var BranchName  =    $("#BranchName").val();
    var BatchYear   =    $("#EmpYear").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchReopens/getbatchcode",{BranchName:BranchName,BatchYear:BatchYear,BatchMonth:BatchMonth}, function(data){
            $("#BatchCode").html(data);
        }); 
    }  
}

function TrainingEmployee(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var BatchCode=$("#BatchCode").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYear").focus();
        $("#EmpYear").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select year.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }
    else if(BatchCode ===""){
        $("#BatchCode").focus();
        $("#BatchCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select batch code.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>TrainingBatchReopens/showtrainingdetails",{BatchCode:BatchCode}, function(data) {
                $("#loder").hide();
                $("#divPendingTraining").html(data); 
            });
        }
    }
}

function ReopenBatch(BatchCode){
    $("#msgerr").remove();
    var BatchStatus=$("#BatchStatus").val();
    var ReopenRemarks=$("#ReopenRemarks").val();
    
    if(BatchCode ===""){
        $("#BatchCode").focus();
        $("#BatchCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select batch code.</span>");
        return false;
    }
    else if(BatchStatus ===""){
        $("#BatchStatus").focus();
        $("#BatchStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select batch status.</span>");
        return false;
    }
    else if(ReopenRemarks ===""){
        $("#ReopenRemarks").focus();
        $("#ReopenRemarks").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter reopen remarks.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchReopens/reopenbatch",{BatchCode:BatchCode,BatchStatus:BatchStatus,ReopenRemarks:ReopenRemarks},function(data){
            $("#BatchStatus").val('');
            $("#ReopenRemarks").val('');
            TrainingEmployee('show');
        });
    }
}

function extend(){
    $("#msgerr").remove();
    var BatchCode=$("#BatchCode").val();
    var ExtendDate=$("#ExtendDate").val();
    var ExtendRemarks=$("#ExtendRemarks").val();
    
    if(BatchCode ===""){
        $("#BatchCode").focus();
        $("#BatchCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select date.</span>");
        return false;
    }
    else if(ExtendDate ===""){
        $("#ExtendDate").focus();
        $("#ExtendDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select date.</span>");
        return false;
    }
    else if(ExtendRemarks ===""){
        $("#ExtendRemarks").focus();
        $("#ExtendRemarks").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select remarks.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchReopens/extenddate",{BatchCode:BatchCode,ExtendDate:ExtendDate,ExtendRemarks:ExtendRemarks},function(data){
            $("#ExtendDate").val('');
            $("#ExtendRemarks").val('');
            document.getElementById("CloseExtendPopup").click();
            TrainingEmployee('show');
        });
    }
}

function CloseExtendPopup(){
    $("#ExtendDate").val('');
    $("#ExtendRemarks").val('');
    document.getElementById("CloseExtendPopup").click();
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
                    <span>REOPEN TRAINING BATCH</span>
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
                <?php echo $this->Form->create('TrainingAllocationBatchs',array('action'=>'index','class'=>'form-horizontal')); ?>
                <input type="hidden" name="backid" value="<?php echo $backid?>" >
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmpYear" name="EmpYear" autocomplete="off" class="form-control" >
                            <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonth" name="EmpMonth" onchange="getbatchcode(this.value)" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                            <option value="01">Jan</option>
                            <option value="02">Feb</option>
                            <option value="03">Mar</option>
                            <option value="04">Apr</option>
                            <option value="05">May</option>
                            <option value="06">Jun</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                            <option value="09">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">BatchCode</label>
                    <div class="col-sm-2">
                        <select id="BatchCode" name="BatchCode" autocomplete="off" class="form-control " >
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <div class="col-sm-10">
                        <img class="pull-right" src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="TrainingEmployee('show');" value="Show" class="btn pull-right btn-primary btn-new">  
                    </div>
                    
                    
                </div>

                <div class="form-group">
                    <div id="divPendingTraining"></div>   
                </div>
                
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              
              <h4 class="modal-title">Extend Date</h4>
            </div>
            <div class="modal-body" style="height:150px;" >
                <form>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Extend Date</label>
                        <div class="col-sm-8">
                            <input type="text" id="ExtendDate" name="ExtendDate" autocomplete="off" class="form-control DatePicker" >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Extend Remarks</label>
                        <div class="col-sm-8">
                            <textarea id="ExtendRemarks" name="ExtendRemarks" autocomplete="off" class="form-control" ></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-new" onclick="extend()" >Submit</button>
                <button type="button" id="CloseExtendPopup" onclick="CloseExtendPopup()" class="btn btn-new" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
  




