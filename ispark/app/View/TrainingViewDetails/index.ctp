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

function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>AttendanceExports/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}
 
function TrainingEmployee(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var BatchStatus=$("#BatchStatus").val();
    
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
    else if(BatchStatus ===""){
        $("#BatchStatus").focus();
        $("#BatchStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select batch code.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>TrainingViewDetails/showtrainingdetails",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,BatchStatus:BatchStatus}, function(data) {
                $("#divPendingTraining").html(data); 
            });
        }
    }
}

function AllocateDetails(BatchCode){
    $.post("<?php echo $this->webroot;?>TrainingViewDetails/viewupdateform",{BatchCode:BatchCode}, function(data) {
        $("#divAllocationDetails").html(data);
    });
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
                    <span>VIEW TRAINING BATCH DETAILS</span>
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
                <?php echo $this->Form->create('TrainingViewDetails',array('action'=>'index','class'=>'form-horizontal')); ?>
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
                    
                    <label class="col-sm-1 control-label">BatchStatus</label>
                    <div class="col-sm-2">
                        <select id="BatchStatus" name="BatchStatus" autocomplete="off" class="form-control " >
                            <option value="ALL">ALL</option>
                            <option value="Initiated">Initiated</option>
                            <option value="Proposed">Proposed</option>
                            <option value="Pending">Pending</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="TrainingEmployee('show');" value="Show" class="btn pull-right btn-primary btn-new">  
                    </div>
                </div>

                <div class="form-group">
                    <div id="divPendingTraining"></div>
                    <div id="divAllocationDetails" ></div>                      
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>





