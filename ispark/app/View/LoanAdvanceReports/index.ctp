<?php $backurl=$this->webroot."Menus?AX=MTA3";?>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>LoanAdvanceReports/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function LoanAdvanceReport(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var EmpCode=$("#EmpCode").val();
    var EmpLocation=$("#EmpLocation").val();
    var EmpMonthF=$("#EmpMonthF").val();
    var EmpYearF=$("#EmpYearF").val();
    var EmpMonthTo=$("#EmpMonthTo").val();
    var EmpYearTo=$("#EmpYearTo").val();
    
   
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select cost center.</span>");
        return false;
    }
    if(EmpMonthF ===""){
        $("#EmpMonthF").focus();
        $("#EmpMonthF").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select first month.</span>");
        return false;
    }
    if(EmpYearF ===""){
        $("#EmpYearF").focus();
        $("#EmpYearF").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select first year.</span>");
        return false;
    }
    else if(EmpMonthTo ===""){
        $("#EmpMonthTo").focus();
        $("#EmpMonthTo").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select last month.</span>");
        return false;
    }
    else if(EmpYearTo ===""){
        $("#EmpYearTo").focus();
        $("#EmpYearTo").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select last year.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>LoanAdvanceReports/show_report",{BranchName:BranchName,EmpMonthF:EmpMonthF,EmpYearF:EmpYearF,EmpMonthTo:EmpMonthTo,EmpYearTo:EmpYearTo,CostCenter:CostCenter,EmpLocation:EmpLocation,EmpCode:$.trim(EmpCode)}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divAttendance").html(data);
                }
                else{
                    $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>LoanAdvanceReports/export_report?BranchName="+BranchName+"&EmpMonthF="+EmpMonthF+"&EmpYearF="+EmpYearF+"&EmpMonthTo="+EmpMonthTo+"&EmpYearTo="+EmpYearTo+"&CostCenter="+CostCenter+"&EmpLocation="+EmpLocation+"&EmpCode="+$.trim(EmpCode);  
           
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
                    <span>LOAN ADVANCE REPORTS</span>
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
                <?php echo $this->Form->create('LoanAdvanceReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                
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
                        <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <select id="EmpLocation" name="EmpLocation" autocomplete="off" class="form-control" >
                            <option value="Loan">Loan</option>
                            <option value="Advance">Advance</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-2">
                        <select id="EmpMonthF" name="EmpMonthF" autocomplete="off" style="height: 27px;width:68px;" >
                            <option value="">Select</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='01'){echo "selected='selected'";}?> value="01">Jan</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='02'){echo "selected='selected'";}?> value="02">Feb</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='03'){echo "selected='selected'";}?> value="03">Mar</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='04'){echo "selected='selected'";}?> value="04">Apr</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='05'){echo "selected='selected'";}?> value="05">May</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='06'){echo "selected='selected'";}?> value="06">Jun</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='07'){echo "selected='selected'";}?> value="07">Jul</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='08'){echo "selected='selected'";}?> value="08">Aug</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='09'){echo "selected='selected'";}?> value="09">Sep</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='10'){echo "selected='selected'";}?> value="10">Oct</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='11'){echo "selected='selected'";}?> value="11">Nov</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='12'){echo "selected='selected'";}?> value="12">Dec</option>
                        </select>
                        <select id="EmpYearF" name="EmpYearF" autocomplete="off"  style="height: 27px;width:65px;" >
                            <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
							<option value="<?php echo date("Y",strtotime("-2 year")); ?>"><?php echo date("Y",strtotime("-2 year")); ?></option>
							<option value="<?php echo date("Y",strtotime("-3 year")); ?>"><?php echo date("Y",strtotime("-3 year")); ?></option>
                        </select>
                    </div>
                    
                    
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <select id="EmpMonthTo" name="EmpMonthTo" autocomplete="off" style="height: 27px;width:68px;" >
                            <option value="">Select</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='01'){echo "selected='selected'";}?> value="01">Jan</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='02'){echo "selected='selected'";}?> value="02">Feb</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='03'){echo "selected='selected'";}?> value="03">Mar</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='04'){echo "selected='selected'";}?> value="04">Apr</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='05'){echo "selected='selected'";}?> value="05">May</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='06'){echo "selected='selected'";}?> value="06">Jun</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='07'){echo "selected='selected'";}?> value="07">Jul</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='08'){echo "selected='selected'";}?> value="08">Aug</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='09'){echo "selected='selected'";}?> value="09">Sep</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='10'){echo "selected='selected'";}?> value="10">Oct</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='11'){echo "selected='selected'";}?> value="11">Nov</option>
                            <option <?php if(date("m",strtotime("-1 month")) =='12'){echo "selected='selected'";}?> value="12">Dec</option>
                        </select>
                        <select id="EmpYearTo" name="EmpYearTo" autocomplete="off"  style="height: 27px;width:65px;" >
                            <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
							<option value="<?php echo date("Y",strtotime("-2 year")); ?>"><?php echo date("Y",strtotime("-2 year")); ?></option>
							<option value="<?php echo date("Y",strtotime("-3 year")); ?>"><?php echo date("Y",strtotime("-3 year")); ?></option>
                        </select>
                    </div>
                    
                    <div class="col-sm-3 pull-right">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="LoanAdvanceReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="button" onclick="LoanAdvanceReport('show');" value="Show" class="btn pull-right btn-primary btn-new">  
                    </div>
                    
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                
                <div class="form-group" id="divAttendance" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



