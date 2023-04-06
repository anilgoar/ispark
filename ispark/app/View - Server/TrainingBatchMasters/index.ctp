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
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#StartDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
$(document).ready(function(){
    <?php if(isset($dataArr['BranchName']) && $dataArr['BranchName'] !=""){ ?>   
        
        $.post("<?php echo $this->webroot;?>TrainingBatchMasters/getcostcenteredit",{BranchName:'<?php echo $dataArr['BranchName'];?>',CostCenter:'<?php echo $dataArr['CostCenter'];?>'}, function(data){
            $("#CostCenter").html(data);
        }); 
        
    <?php } ?>
        
    <?php if(isset($dataArr['TrainerName']) && $dataArr['TrainerName'] !=""){ ?>   
        
        $.post("<?php echo $this->webroot;?>TrainingBatchMasters/gettrainernameedit",{BranchName:'<?php echo $dataArr['BranchName'];?>',CostCenter:'<?php echo $dataArr['CostCenter'];?>',TrainerName:'<?php echo $dataArr['TrainerName'];?>'}, function(data){
            $("#TrainerName").html(data);
        }); 
        
    <?php } ?> 
        
});

 
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
        viewdata();
    });  
}

function getTrainerName(CostCenter){ 
    var BranchName=$("#BranchName").val();
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/gettrainername",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        $("#TrainerName").html(data);
    });  
}

function getProcessName(CostCenter){ 
    var BranchName=$("#BranchName").val();
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/getprocessname",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        $("#ProcessName").val(data);
    });  
}

function getDurationDay(CostCenter){
    $("#msgerr").remove();  
    var BranchName=$("#BranchName").val();
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/getdurationday",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        if(data ==="0"){
            $("#DurationDays" ).val('');
            $("#DurationDays" ).after("<span id='msgerr' style='color:red;font-size:11px;' >There is no training days.</span>");
            return false;
        }
        else{
            $("#DurationDays").val(data);
        }
    });  
}


function getEndDate(StartDate){
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var DurationDays=$("#DurationDays").val();
    
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/getenddate",{StartDate:StartDate,BranchName:BranchName,CostCenter:CostCenter,DurationDays:DurationDays}, function(data){
        $("#EndDate").val(data);
    });
}

function actionlist(path,action){
    if(action =="edit"){
        window.location=path;
    }
    else if(action =="delete"){
        if(confirm('Are you sure you want to delete this list?')){
            window.location=path;
        }
    }
}

function search_employees(EmpCode){
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    $("#msgerr").remove();  
    
    if(BranchName ===""){
        $("#TrainerName" ).val('');
        $("#BranchName" ).after("<span id='msgerr' style='color:red;font-size:11px;' >Please select branch name.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter" ).val('');
        $("#CostCenter" ).after("<span id='msgerr' style='color:red;font-size:11px;' >Please select cost center.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingBatchMasters/get_emp",{EmpCode:$.trim(EmpCode),BranchName:BranchName,CostCenter:CostCenter}, function(data) {
            $("#TrainerName" ).val(data);
        });
    }
}

function viewdata(){ 
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    
    $.post("<?php echo $this->webroot;?>TrainingBatchMasters/gettrainerdata",{BranchName:BranchName,CostCenter:CostCenter}, function(data){
        $("#trainerdata").html(data);
    });  
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

/*
function ValidateTrainingBatch(Type){ 
    $("#msgerr").remove();
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var EmpCode=$("#EmpCode").val();
    var TrainerName=$("#TrainerName").val();
    var Contact=$("#Contact").val();
    var EmailId=$("#EmailId").val();
    
    
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select cost center.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#EmpCode").focus();
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter emply code.</span>");
        return false;
    }
    else if(TrainerName ===""){
        $("#TrainerName").focus();
        $("#TrainerName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter trainer name.</span>");
        return false;
    }
    else if(Contact ===""){
        $("#Contact").focus();
        $("#Contact").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter contact.</span>");
        return false;
    }
    else if(EmailId ===""){
        $("#EmailId").focus();
        $("#EmailId").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter email id.</span>");
        return false;
    }
    else if (!filter.test($.trim(EmailId))){
        $("#EmailId").focus();
        $("#EmailId").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter correct email id.</span>");
        return false;
    }
    else{
        return true;        
    }
}*/
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
                    <span>TRAINING BATCH MASTER</span>
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
                <?php echo $this->Form->create('TrainingBatchMasters',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return ValidateTrainingBatch()')); ?>
                <input type="hidden" name="Id" value="<?php echo isset($dataArr['Id'])?$dataArr['Id']:''?>" >
                <input type="hidden" name="backid" value="<?php echo $backid?>" >
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>isset($dataArr['BranchName'])?$dataArr['BranchName']:'','empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" id="CostCenter" onchange="viewdata(),getProcessName(this.value),getTrainerName(this.value),getDurationDay(this.value)" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label"> ProcessName </label>
                    <div class="col-sm-2">
                        <input type="text" id="ProcessName" name="ProcessName" readonly="" value="<?php echo isset($dataArr['ProcessName'])?$dataArr['ProcessName']:''?>" autocomplete="off" placeholder="ProcessName" class="form-control" >
                    </div>
                    
                    <label class="col-sm-1 control-label">BatchCount</label>
                    <div class="col-sm-2">
                        <input type="text" id="BatchCount" name="BatchCount" onkeypress="return isNumberKey(event,this)" value="<?php echo isset($dataArr['BatchCount'])?$dataArr['BatchCount']:''?>"  autocomplete="off" placeholder="Batch Count" class="form-control" required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">StartDate</label>
                    <div class="col-sm-2">
                        <input type="text" id="StartDate" name="StartDate" onchange="getEndDate(this.value)" value="<?php echo isset($dataArr['StartDate'])?date('d-M-Y',strtotime($dataArr['StartDate'])):''?>" autocomplete="off" placeholder="Start Date" class="form-control" required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">EndDate</label>
                    <div class="col-sm-2">
                        <input type="text" id="EndDate" name="EndDate"  value="<?php echo isset($dataArr['EndDate'])?date('d-M-Y',strtotime($dataArr['EndDate'])):''?>" autocomplete="off" placeholder="End Date" class="form-control" required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">Duration(Days):</label>
                    <div class="col-sm-2">
                        <input type="text" id="DurationDays" name="DurationDays" readonly=""  value="<?php echo isset($dataArr['DurationDays'])?$dataArr['DurationDays']:''?>" autocomplete="off" placeholder="Duration Days" class="form-control" required="" >
                    </div>
                    <!--
                    <label class="col-sm-1 control-label">BatchCode:</label>
                    <div class="col-sm-2">
                        <input type="text" id="BatchCode" name="BatchCode"  value="<?php echo isset($dataArr['BatchCode'])?$dataArr['BatchCode']:''?>" autocomplete="off" placeholder="Batch Code" class="form-control" required="" >
                    </div>
                    -->
                    <label class="col-sm-1 control-label">TrainerName</label>
                    <div class="col-sm-2">
                        <select id="TrainerName" name="TrainerName" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">TrainingRoom Availibility</label>
                    <div class="col-sm-2">
                        <select id="TrainingRoomAvailibility"  name="TrainingRoomAvailibility" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option <?php if(isset($dataArr['TrainingRoomAvailibility']) && $dataArr['TrainingRoomAvailibility'] =="Yes"){echo "selected='selected'";}?> value="Yes">Yes</option>
                            <option <?php if(isset($dataArr['TrainingRoomAvailibility']) && $dataArr['TrainingRoomAvailibility'] =="No"){echo "selected='selected'";}?> value="No">No</option>
                        </select>
                    </div>
    
                    <label class="col-sm-1 control-label">TrainingRoom</label>
                    <div class="col-sm-2">
                        <select id="TrainingRoom"  name="TrainingRoom" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <?php foreach($room as $val){?>
                            <option <?php if(isset($dataArr['TrainingRoom']) && $dataArr['TrainingRoom'] ==$val){echo "selected='selected'";}?> value="<?php echo $val;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
       
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-5">
                        <textarea name="Remarks" id="Remarks" class="form-control"  ><?php echo isset($dataArr['Remarks'])?$dataArr['Remarks']:''?></textarea>
                    </div>
             
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php if(empty($dataArr)){?>
                            <input type="submit" name="Submit" value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }else{?>
                            <a href="<?php echo $this->webroot;?>TrainingBatchMasters?backid=<?php echo $backid;?>">
                                <input type="button" value="Add New" class="btn pull-right btn-primary btn-new">
                            </a>
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12" id="trainerdata" >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>CostCenter</th>
                                    <th>ProcessName</th>
                                    <th>BatchCode</th>
                                    <th>TrainerName</th>
                                    <th>Count</th>
                                    <th>StartDate</th>
                                    <th>EndDate</th>
                                    <th>Status</th>
                                    <th>Room</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($data as $row){?>
                                <tr>
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['CostCenter']?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['ProcessName']?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['BatchCode']?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['TrainerName']?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['BatchCount']?></td>
                                    <td><?php echo date("d-M-Y",strtotime($row['TrainingBatchMaster']['StartDate'])); ?></td>
                                    <td><?php echo date("d-M-Y",strtotime($row['TrainingBatchMaster']['EndDate'])); ?></td>
                                     <td><?php echo $row['TrainingBatchMaster']['TrainingStatus']?></td>
                                    <td><?php echo $row['TrainingBatchMaster']['TrainingRoom']?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingBatchMasters?id=<?php echo base64_encode($row['TrainingBatchMaster']['Id']).'&backid='.$backid;?>','edit');" class="material-icons" style="font-size:20px;cursor: pointer;" >search</i></span>
                                        <?php if($row['TrainingBatchMaster']['ApproveFirst']==""){ ?>
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingBatchMasters/deletesource?id=<?php echo base64_encode($row['TrainingBatchMaster']['Id']).'&backid='.$backid;?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
