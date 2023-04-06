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
        $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/getbatchcode",{BranchName:BranchName,BatchYear:BatchYear,BatchMonth:BatchMonth}, function(data){
            $("#BatchCode").html(data);
        }); 
    }  
}

function TrainingEmployee(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var CostCenter=$("#CostCenter").val();
    var EmpLocation=$("#EmpLocation").val();
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
            $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/showtrainingdetails",{BatchCode:BatchCode}, function(data) {
                $("#loder").hide();
                $("#divPendingTraining").html(data); 
            });
        }
    }
}

function getsubstatus(Id){
    $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/getsubstatus",{Id:Id}, function(data){
        $("#SubStatus").html(data);
        
        $("#div_CertificationDate").show();
        $("#div_CertificationScore").show();
        $("#div_RecertificationScore").show();
        $("#div_HandOverDate").show();
        $("#div_AtritionDate").show();
        $("#div_Remarks").show();   
        
        if(Id =="1" || Id =="4" || Id =="7"){
            $("#div_CertificationDate,#div_CertificationScore,#div_RecertificationScore,#div_HandOverDate,#div_AtritionDate").hide();
            $("#CertificationDate,#CertificationScore,#RecertificationScore,#HandOverDate,#AtritionDate").val('');
        }
        else if(Id =="2"){
            $("#div_RecertificationScore,#div_AtritionDate").hide();
            $("#RecertificationScore,#AtritionDate").val('');
        }
        else if(Id =="3"){
            $("#div_CertificationScore,#div_AtritionDate").hide();
            $("#CertificationScore,#AtritionDate").val('');
        }
        else if(Id =="5" || Id =="6"){
            $("#div_CertificationDate,#div_CertificationScore,#div_RecertificationScore,#div_HandOverDate").hide();
            $("#CertificationDate,#CertificationScore,#RecertificationScore,#HandOverDate").hide('');
        }
    });     
}

function AllocateDetails(Id){
    $("#divUpdateTraining").hide();
    $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/viewupdateform",{Id:Id}, function(data) {
        if(data !=""){
            var res = JSON.parse(data);
            $("#TrainingAllocId").val(res.Id);
            $("#Status").val(res.StatusId);
            $("#CertificationDate").val(res.CertificationDate);
            $("#CertificationScore").val(res.CertificationScore);
            $("#RecertificationScore").val(res.RecertificationScore);
            $("#HandOverDate").val(res.HandOverDate);
            $("#AtritionDate").val(res.AtritionDate);
            $("#Remarks").val(res.Remarks);
            $("#divUpdateTraining").show();
            
            getsubstatus(res.StatusId);
            
            $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/editsubstatus",{Id:res.StatusId,SubStatus:res.SubStatus}, function(data){
                $("#SubStatus").html(data);
            });    
        }
        else{
            $("#TrainingAllocId").val('');
            $("#Status").val('');
            $("#CertificationDate").val('');
            $("#CertificationScore").val('');
            $("#RecertificationScore").val('');
            $("#HandOverDate").val('');
            $("#AtritionDate").val('');
            $("#Remarks").val('');
            $("#divUpdateTraining").hide();
        }
    });
}

function UpdateTrainingAllocation(){ 
    $("#msgerr").remove();
    var TrainingAllocId=$("#TrainingAllocId").val();
    var Status=$("#Status").val();
    var SubStatus=$("#SubStatus").val();
    var CertificationDate=$("#CertificationDate").val();
    var CertificationScore=$("#CertificationScore").val();
    var RecertificationScore=$("#RecertificationScore").val();
    var HandOverDate=$("#HandOverDate").val();
    var AtritionDate=$("#AtritionDate").val();
    var Remarks=$("#Remarks").val();
    
    if(Status ===""){
        $("#Status").focus();
        $("#Status").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select status.</span>");
        return false;
    }
    else if(SubStatus ===""){
        $("#SubStatus").focus();
        $("#SubStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select sub status.</span>");
        return false;
    }
    else if(Status =="2" && CertificationDate ===""){
        $("#CertificationDate").focus();
        $("#CertificationDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select certification date.</span>");
        return false;
    }
    else if(Status =="2" && CertificationScore ===""){
        $("#CertificationScore").focus();
        $("#CertificationScore").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter certification score.</span>");
        return false;
    }
    else if(Status =="2" && HandOverDate ===""){
        $("#HandOverDate").focus();
        $("#HandOverDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select handover date.</span>");
        return false;
    }
    else if(Status =="3" && CertificationDate ===""){
        $("#CertificationDate").focus();
        $("#CertificationDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select certification date.</span>");
        return false;
    }
    else if(Status =="3" && RecertificationScore ===""){
        $("#RecertificationScore").focus();
        $("#RecertificationScore").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter recertification score.</span>");
        return false;
    }
    else if(Status =="3" && HandOverDate ===""){
        $("#HandOverDate").focus();
        $("#HandOverDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select handover date.</span>");
        return false;
    }
    else if(Status =="5" && AtritionDate ===""){
        $("#AtritionDate").focus();
        $("#AtritionDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select atrition date.</span>");
        return false;
    }
    else if(Status =="6" && AtritionDate ===""){
        $("#AtritionDate").focus();
        $("#AtritionDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select atrition date.</span>");
        return false;
    }
    else if(Remarks ===""){
        $("#Remarks").focus();
        $("#Remarks").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter remarks.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/updateallocationstatus",{Id:TrainingAllocId,Status:Status,SubStatus:SubStatus,CertificationDate:CertificationDate,CertificationScore:CertificationScore,RecertificationScore:RecertificationScore,HandOverDate:HandOverDate,AtritionDate:AtritionDate,Remarks:Remarks}, function(data) {
            $("#TrainingAllocId").val('');
            $("#Status").val('');
            $("#CertificationDate").val('');
            $("#CertificationScore").val('');
            $("#RecertificationScore").val('');
            $("#HandOverDate").val('');
            $("#AtritionDate").val('');
            $("#Remarks").val('');
            $("#divUpdateTraining").hide();
            TrainingEmployee('show');
        });
    }
}

function CloseTraining(BatchCode){
    $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/closetrainingbatch",{BatchCode:BatchCode}, function(data) {
        if(data ==""){
            alert("Please update training batch count before closing batch.");
        }
        
        $("#TrainingAllocId").val('');
        $("#Status").val('');
        $("#CertificationDate").val('');
        $("#CertificationScore").val('');
        $("#RecertificationScore").val('');
        $("#HandOverDate").val('');
        $("#AtritionDate").val('');
        $("#Remarks").val('');
        $("#divUpdateTraining").hide();
        TrainingEmployee('show');
    });
}

function extend(){
    $("#msgerr").remove();
    var BatchCode=$("#BatchCode").val();
    var ExtendDate=$("#ExtendDate").val();
    var ExtendRemarks=$("#ExtendRemarks").val();
    
    if(ExtendDate ===""){
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
        $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/extenddate",{BatchCode:BatchCode,ExtendDate:ExtendDate,ExtendRemarks:ExtendRemarks},function(data){
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

function extendbatchcount(){
    $("#msgerr").remove();
    var BatchCode=$("#BatchCode").val();
    var NewBatchcount=$("#NewBatchcount").val();
    
    if(NewBatchcount ===""){
        $("#NewBatchcount").focus();
        $("#NewBatchcount").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter new batch count.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchsDetails/extendnewbatchcount",{BatchCode:BatchCode,NewBatchcount:NewBatchcount},function(data){
            $("#NewBatchcount").val('');
            document.getElementById("CloseNewCountPopup").click();
            TrainingEmployee('show');
        });
    }
}

function CloseNewCountPopup(){
    $("#NewBatchcount").val('');
    document.getElementById("CloseNewCountPopup").click();
}

function substatusAction(substatus){
    $("#Remarks").val(substatus);
}

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
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
                    <span>TRAINING BATCH DETAILS</span>
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
                    <div class="col-sm-6" id="divUpdateTraining" style="display: none;" >
                        <input type="hidden" id="TrainingAllocId" name="TrainingAllocId" >
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-6">
                                <select id="Status" name="Status" onchange="getsubstatus(this.value)"  autocomplete="off" class="form-control" >
                                    <option value="">Select</option>
                                    <?php foreach($data as $key=>$val){?>
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">SubStatus</label>
                            <div class="col-sm-6">
                                <select id="SubStatus" name="SubStatus" onchange="substatusAction(this.value)"  autocomplete="off" class="form-control" >
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="div_CertificationDate" >
                            <label class="col-sm-3 control-label">CertificationDate</label>
                            <div class="col-sm-6">
                                <input type="text" id="CertificationDate" name="CertificationDate" readonly="" autocomplete="off" class="form-control DatePicker" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div id="div_CertificationScore" >
                                <label class="col-sm-3 control-label">CertificationScore%</label>
                                <div class="col-sm-3">
                                    <input type="text" id="CertificationScore" onkeypress="return isNumberKey(event,this)" name="CertificationScore" autocomplete="off" class="form-control" >
                                </div>
                            </div>
                            <div id="div_RecertificationScore" >
                               <label class="col-sm-3 control-label">RecertificationScore%</label>
                               <div class="col-sm-3">
                                   <input type="text" id="RecertificationScore" onkeypress="return isNumberKey(event,this)" name="RecertificationScore" autocomplete="off" class="form-control" >
                               </div>
                            </div>
                
                            <div id="div_HandOverDate" >
                                <label class="col-sm-3 control-label">HandOverDate</label>
                                <div class="col-sm-3">
                                    <input type="text" id="HandOverDate" name="HandOverDate" readonly="" autocomplete="off" class="form-control DatePicker" >
                                </div>
                            </div>
                            <div id="div_AtritionDate" >
                                <label class="col-sm-3 control-label">Atrition Date</label>
                                <div class="col-sm-3">
                                    <input type="text" id="AtritionDate" name="AtritionDate" readonly="" autocomplete="off" class="form-control DatePicker" >
                                </div>
                            </div>
                        </div>
                       
                       
                        
                        <div class="form-group" id="div_Remarks" >
                            <label class="col-sm-3 control-label">Remarks</label>
                            <div class="col-sm-9">
                                <textarea id="Remarks" name="Remarks" autocomplete="off" class="form-control" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="button" onclick="UpdateTrainingAllocation();" value="Update" class="btn pull-right btn-primary btn-new">  
                            </div>
                        </div> 
                    </div>
                      
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

<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              
              <h4 class="modal-title">Extend Date</h4>
            </div>
            <div class="modal-body" style="height:150px;" >
                <form>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">New Batch Count</label>
                        <div class="col-sm-8">
                            <input type="text" id="NewBatchcount" name="NewBatchcount" onkeypress="return isNumberKey(event,this)" maxlength="2" autocomplete="off" class="form-control" >
                        </div>
                    </div>
      
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-new" onclick="extendbatchcount()" >Submit</button>
                <button type="button" id="CloseNewCountPopup" onclick="CloseNewCountPopup()" class="btn btn-new" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
  




