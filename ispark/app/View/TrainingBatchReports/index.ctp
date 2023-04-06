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
<script>
function trainingBatchReport(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var ReportType=$("#ReportType").val();
    var EmpYear=$("#EmpYear").val();
    var EmpMonth=$("#EmpMonth").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            
            if(ReportType =="Report"){
                $.post("<?php echo $this->webroot;?>TrainingBatchReports/show_report",{BranchName:BranchName,EmpYear:EmpYear,EmpMonth:EmpMonth}, function(data) {
                    $("#loder").hide();
                    if(data !=""){
                        $("#divAttendance").html(data);
                    }
                    else{
                        $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                    } 
                });
            }
            else if(ReportType =="MIS"){
                $.post("<?php echo $this->webroot;?>TrainingBatchReports/show_mis",{BranchName:BranchName,EmpYear:EmpYear,EmpMonth:EmpMonth}, function(data) {
                    $("#loder").hide();
                    if(data !=""){
                        $("#divAttendance").html(data);
                    }
                    else{
                        $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                    } 
                });
            }
             
        }
        else if(Type ==="Export"){
            if(ReportType =="Report"){
                $("#loder").hide();
                window.location="<?php echo $this->webroot;?>TrainingBatchReports/export_report?BranchName="+BranchName+"&EmpYear="+EmpYear+"&EmpMonth="+EmpMonth;  
            }
            else if(ReportType =="MIS"){
                $("#loder").hide();
                window.location="<?php echo $this->webroot;?>TrainingBatchReports/export_mis?BranchName="+BranchName+"&EmpYear="+EmpYear+"&EmpMonth="+EmpMonth;  
            }
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
                    <span>TRAINING BATCH REPORTS</span>
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
                <?php echo $this->Form->create('TrainingBatchReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">ReportType</label>
                    <div class="col-sm-2">
                        <select id="ReportType" name="ReportType" autocomplete="off" class="form-control" >
                            <option value="Report">Report</option>
                            <option value="MIS">MIS</option>
                        </select>
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
                        <select id="EmpMonth" name="EmpMonth" autocomplete="off" class="form-control" >
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
                </div>
                
                <div class="form-group">
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    <div class="col-sm-11">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="trainingBatchReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="button" onclick="trainingBatchReport('show');" value="Show" class="btn pull-right btn-primary btn-new">
                    </div>
                    
                </div>
                
                <div class="form-group" id="divAttendance" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



