<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#from_date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#to_date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>AttendanceExports/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function logReport(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var From=$("#from_date").val();
    var To=$("#to_date").val();
    var CostCenter=$("#CostCenter").val();
    var EmpCode=$("#EmpCode").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }

    else if(From ===""){
        $("#from_date").focus();
        $("#from_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select from date.</span>");
        return false;
    }
    else if(To ===""){
        $("#to_date").focus();
        $("#to_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select to date.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>EmpAttStatuses/show_report",{BranchName:BranchName,From:From,To:To,CostCenter:CostCenter,EmpCode:$.trim(EmpCode)}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divAttendance").html(data);
                }
                else{
                    $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;">Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>EmpAttStatuses/export_report?BranchName="+BranchName+"&From="+From+"&To="+To+"&CostCenter="+CostCenter+"&EmpCode="+$.trim(EmpCode);  
           
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
            <div class="box-header">
                <div class="box-name">
                    <span>LOG EXPORT</span>
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
                <?php echo $this->Form->create('EmpAttStatuses',array('action'=>'show_report','class'=>'form-horizontal')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control">
                    </div>
                    
                </div>
                
                <div class="form-group">
                    
                    <label class="col-sm-1 control-label">From Date</label>
                    <div class="col-sm-2">
                      <input type="text" name="from_date" id="from_date" value="<?php echo isset($fromdate)?date('d-M-Y',strtotime($fromdate)):'';?>" class="form-control" required=""  >
                    </div>

                    <label class="col-sm-1 control-label">To Date</label>
                    <div class="col-sm-2">
                    <input type="text" name="to_date" id="to_date" value="<?php echo isset($todate)?date('d-M-Y',strtotime($todate)):'';?>" autocomplete="off" class="form-control" required=""  >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="logReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                         
                        <input type="button" onclick="logReport('show');" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder">
                    </div>
                </div>
                
                <div class="form-group" id="divAttendance"></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



