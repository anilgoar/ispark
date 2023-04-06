<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script language="javascript">
$(function () {
    $("#HolydayDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

$(document).ready(function(){
    getProcess('<?php echo $data['Branch'];?>','<?php echo $data['Process'];?>');
    getCampaign('<?php echo $data['Process'];?>','<?php echo $data['Campaign'];?>');
    editdept('<?php echo $data['Designation'];?>','<?php echo $data['Department']?>','Desgination');
    
    $.post("<?php echo $this->webroot;?>TrainingQuestionSets/view_video_set",{BranchName:'<?php echo $data['Branch'];?>',Department:'<?php echo $data['Department']?>',Desgination:'<?php echo $data['Designation'];?>'}, function(data){
        $("#view_video_set").html(data);
    }); 
});

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function editdept(Designation,Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/editdept",{'Designation':$.trim(Designation),'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function view_video_set(){ 
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
        return false;
    }
    else if(Desgination ===""){
        $("#Desgination").focus();
        $("#Desgination").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select desgination.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Process").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select process.</span>");
        return false;
    }
    else if(Campaign ===""){
        $("#Campaign").focus();
        $("#Campaign").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select campaign.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/view_video_set",{BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign}, function(data){
            $("#view_video_set").html(data);
        });      
    }
}

function upload_video(){ 
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    var Set_No      =   $("#Set_No").val();
    var Percent     =   $("#Percent").val();
    var Video_Name  =   $("#Video_Name").val();
    var Video_File  =   $("#Video_File").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
        return false;
    }
    else if(Desgination ===""){
        $("#Desgination").focus();
        $("#Desgination").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select desgination.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Process").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select process.</span>");
        return false;
    }
    else if(Campaign ===""){
        $("#Campaign").focus();
        $("#Campaign").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select campaign.</span>");
        return false;
    }
    else if(Set_No ===""){
        $("#Set_No").focus();
        $("#Set_No").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select set no.</span>");
        return false;
    }
    else if(Percent ===""){
        $("#Percent").focus();
        $("#Percent").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter percent.</span>");
        return false;
    }
    else if(Video_Name ===""){
        $("#Video_Name").focus();
        $("#Video_Name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select video name.</span>");
        return false;
    }
    else if(Video_File ===""){
        $("#Video_File").focus();
        $("#Video_File").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select video file.</span>");
        return false;
    }
    else if(Video_File !="" && $('#Video_File')[0].files[0].size > 536870912) {
        $("#Video_File").focus();
        $("#Video_File").after("<span id='msgerr' style='color:red;font-size:11px;'>Allowed file size (Max. 512 MB)</span>");
        return false;
    }
    else{
        $("#showmsg").html("<span id='msgerr' style='color:red;font-weight:bold;'>Please be patient while the video is getting uploaded. Once the video gets uploaded, this message will change.</span>");
        $(".loader").show();
        return true;    
    }
}


function delete_video(Id){
    $("#msgerr").remove();
    
    var BranchName      =   $("#BranchName").val();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Process         =   $("#Process").val();
    var Campaign        =   $("#Campaign").val();
    var Set_No          =   $("#Set_No").val();
    
    if(confirm('Are you sure you want to delete this list item?')){
        if(BranchName ===""){
            $("#BranchName").focus();
            $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
            return false;
        }
        else if(Department ===""){
            $("#Department").focus();
            $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
            return false;
        }
        else if(Desgination ===""){
            $("#Desgination").focus();
            $("#Desgination").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select desgination.</span>");
            return false;
        }
        else if(Process ===""){
            $("#Process").focus();
            $("#Process").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select process.</span>");
            return false;
        }
        else if(Campaign ===""){
            $("#Campaign").focus();
            $("#Campaign").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select campaign.</span>");
            return false;
        }
        else{
            $.post("<?php echo $this->webroot;?>TrainingQuestionSets/delete_video",{Id:Id}, function(data){
                $("#showmsg").html(data);
                $("#Set_No").val('');
                view_video_set(); 
            });
        }
    }
    
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

function getProcess(Branch,Value){
    $.post("<?php echo $this->webroot;?>TrainingQuestionSets/process_list",{Branch:Branch,Value:Value}, function(data){
        $("#Process").html(data);
    });
}

function getCampaign(Process,Value){
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/campaign_list",{Process:Process,Branch:BranchName,Value:Value}, function(data){
            $("#Campaign").html(data);
        });
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
                    <span>Set Training Video</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('TrainingQuestionSets',array('action'=>'set_training_video','class'=>'form-horizontal','onsubmit'=>'return upload_video()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>isset($data['Branch'])?$data['Branch']:'','empty'=>'Select','onchange'=>'getProcess(this.value,"")','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-2">
                        <select name="Department" id="Department" class="form-control" onchange="getdept(this.value,'Desgination')" >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php echo $data['Department']==$val?"selected='selected'":'';?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Designation</label>
                    <div class="col-sm-2">
                        <select name="Desgination" id="Desgination" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Process</label>
                    <div class="col-sm-2">
                        <select name="Process" id="Process" onclick="getCampaign(this.value,'')" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Campaign</label>
                    <div class="col-sm-2">
                        <select name="Campaign" id="Campaign" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Set No</label>
                    <div class="col-sm-2">
                        <select name="Set_No" id="Set_No" autocomplete="off" class="form-control">
                            <option value="">Set No</option>
                            <?php for($i=1;$i<=20;$i++){?>
                            <option <?php echo $data['Set_No'] !="" && $data['Set_No']==$i?"selected='selected'":'';?> value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    
                    
                    <div class="col-sm-1">
                        <input type="button" name="Submit" onclick="view_video_set()" value="&nbsp;&nbsp;View&nbsp;&nbsp;" class="btn pull-right btn-primary btn-new">
                    </div> 
                </div>
                
                <div class="box-header"><div class="box-name"><span>Upload Video</span></div></div>
                
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Percent</label>
                    <div class="col-sm-2">
                        <input type="text" name="Percent" id="Percent" onkeypress="return isNumberKey(event,this)" maxlength="3" autocomplete="off" class="form-control">
                    </div>
                    
                    <label class="col-sm-1 control-label">Video&nbsp;Name</label>
                    <div class="col-sm-2">
                        <input type="text" name="Video_Name" id="Video_Name"  autocomplete="off" class="form-control">
                    </div>
                    
                    <label class="col-sm-1 control-label">Upload&nbsp;Video</label>
                    <div class="col-sm-3">
                        <input type="file" name="Video_File" id="Video_File" accept="video/*" autocomplete="off" class="form-control">
                    </div>

                    <div class="col-sm-2">
                        <input onclick="return window.location='<?php echo $this->webroot;?>Menus?AX=MTMz'" type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                        <input type="submit" name="Submit" value="Upload" class="btn pull-right btn-primary btn-new">
                        <div class="loader" style="display:none;" ></div>
                    </div>
                </div>
                <span id="showmsg" ><?php echo $this->Session->flash(); ?></span>
                
                <div class="form-group" id="view_video_set"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
