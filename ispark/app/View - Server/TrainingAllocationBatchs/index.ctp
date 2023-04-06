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

function AllocateInBatch(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var BatchCode=$("#BatchCode").val();

    var all_location_id = document.querySelectorAll('input[name="AllocateBioCode[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
        
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
    else if(aIds ==""){
        alert('Please select at least one record for allocate training.');
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/allocateinbatch",{BatchCode:BatchCode,BioCode:aIds}, function(result) {
            if(result !=""){
                alert(result);
                return false;
            }
            else{
                $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showtrainingemployee",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data) {
                    $("#divPendingTraining").html(data);
                });
                
                $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showallocatetraining",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,BatchCode:BatchCode}, function(data) {
                    $("#divAllocatedTraining").html(data);
                });
            }
        });
    }   
}

function RemoveFromBatch(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var BatchCode=$("#BatchCode").val();

    var all_location_id = document.querySelectorAll('input[name="RemoveBioCode[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
        
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
    else if(aIds ==""){
        alert('Please select at least one record form allocated training.');
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/removefrombatch",{BatchCode:BatchCode,BioCode:aIds}, function(result) {
            if(result !=""){
                alert(result);
                return false;
            }
            else{
                $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showtrainingemployee",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data) {
                    $("#divPendingTraining").html(data);
                });
                
                $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showallocatetraining",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,BatchCode:BatchCode}, function(data) {
                    $("#divAllocatedTraining").html(data);
                });
            }
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
            $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showtrainingemployee",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data) {
                $("#loder").hide();
                $("#divPendingTraining").html(data); 
            });
            
            $.post("<?php echo $this->webroot;?>TrainingAllocationBatchs/showallocatetraining",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,BatchCode:BatchCode}, function(data) {
                $("#divAllocatedTraining").html(data);
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>AttendanceExports/export_report?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&EmpYear="+EmpYear+"&CostCenter="+CostCenter+"&EmpLocation="+EmpLocation+"&EmpCode="+$.trim(EmpCode);
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
                    <span>TRAINING ALLOCATION TO BATCH</span>
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
                        <select id="BatchCode" name="BatchCode" autocomplete="off" class="form-control" >
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
                
                <div class="form-group"  >
                    
                    <div class="col-sm-5">
                        <div class="box-header"><div class="box-name"><span> ALLOCATION PENDING FOR TRAINING </span></div></div>
                        <div id="divPendingTraining"  >
                            <table class = "table table-striped table-hover  responstable">     
                                <thead>
                                    <tr>
                                        <th style="text-align: center;width:30px;" >&#10004;</th>
                                        <th style="text-align:center;" >SNo</th>
                                        <th style="text-align:center;" >BioCode</th>
                                        <th style="text-align:center;">EmpName</th>
                                        <th style="text-align:center;">TrainningRequired</th>
                                    </tr>
                                </thead>
                                <tbody>         
                                </tbody>   
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div style="margin-top: 25px;" ></div>
                        <div><i class="material-icons" onclick="AllocateInBatch()" style="font-size:50px;cursor: pointer;">arrow_right</i></div>
                    </div>
                    <div class="col-sm-1">
                        <div style="margin-top: 25px;" ></div>
                        <div><i class="material-icons" onclick="RemoveFromBatch()" style="font-size:50px;cursor: pointer;">arrow_left</i></div>
                    </div>
                    
                    <div class="col-sm-5" >
                        <div class="box-header"><div class="box-name"><span> ALLOCATE TRAINING IN BATCH </span></div></div>
                        <div id="divAllocatedTraining"  >
                            <table class = "table table-striped table-hover  responstable">     
                                <thead>
                                    <tr>
                                        <th style="text-align: center;width:30px;" >&#10004;</th>
                                        <th style="text-align:center;" >SNo</th>
                                        <th style="text-align:center;" >BioCode</th>
                                        <th style="text-align:center;">EmpName</th>
                                        <th style="text-align:center;">TrainningRequired</th>
                                    </tr>
                                </thead>
                                <tbody>         
                                </tbody>   
                            </table>
                        </div>
                    </div>
                    
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



