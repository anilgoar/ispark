<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
$(function (){
    $("#SalaryMonth").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function incentiveBreakupDetails(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var SalaryMonth=$("#SalaryMonth").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select CostCenter.</span>");
        return false;
    }
    else if(SalaryMonth ===""){
        $("#SalaryMonth").focus();
        $("#SalaryMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select SalaryMonth.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AttendanceIssueMisReports/showbreakupdetails",{BranchName:BranchName,CostCenter:CostCenter,SalaryMonth:SalaryMonth}, function(data) {
            if(data !=""){
                $("#divBreakupDetails").html(data);
            }
            else{
                $("#divBreakupDetails").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
            }
        });
    }
    
}

function getBranch(BranchName){
    $.post("<?php echo $this->webroot;?>AttendanceIssueMisReports/getcostcenter",{BranchName:BranchName}, function(data) {
        $("#CostCenter").html(data);
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
                    <span>ATTENDANCE ISSUE MIS REPORT</span>
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
                <?php echo $this->Form->create('AttendanceIssueMisReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" required="" >
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">IssueType</label>
                    <div class="col-sm-2">
                        <select id="IssueType" name="IssueType" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                        </select>
                    </div>
                    
                </div>
                <div class="form-group">  
                   
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmployeeStatus" name="EmployeeStatus" autocomplete="off" class="form-control" required="" >
                            <option value="">Month</option>
                            <option value="2017" >2017</option>
                            <option value="2018" >2018</option>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmployeeStatus" name="EmployeeStatus" autocomplete="off" class="form-control" required="" >
                            <option value="">Month</option>
                            <option value="2017" >2017</option>
                            <option value="2018" >2018</option>
                        </select>
                    </div>
                  
                  
                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <select id="EmployeeStatus" name="EmployeeStatus" autocomplete="off" class="form-control" required="" >
                            <option value="" >Select</option>
                            <option value="MIS">MIS</option>
                            <option value="DATA">DATA</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-1">
                        <input type="submit" value="Export" class="btn pull-right btn-primary btn-new">
                    </div>
                </div> 
                
                <div class="form-group" id="divBreakupDetails" ></div>
                
                <?php echo $this->Form->end(); ?>
                
               
               
            </div>
        </div>
    </div>	
</div>



