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

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function view_mail(){ 
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
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/view_mail",{BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign}, function(data){
            $("#view_mail").html(data);
        });      
    }
}

function sendemail(){ 
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    var User_Name   =   $("#User_Name").val();
    var Email_Id    =   $("#Email_Id").val();
    var Mobile_No   =   $("#Mobile_No").val();

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
        $(".loader").show();
        $("#SendUserMail").hide();
        
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/sendmail",{BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign}, function(data){
            $(".loader").hide();
            $("#SendUserMail").show();
            alert(data);
            view_mail();
        });      
    }
}

function add_mail(form){ 
    $("#msgerr").remove();
    var formData    =   $(form).serialize();
    var filter      =   /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    var User_Name   =   $("#User_Name").val();
    var Email_Id    =   $("#Email_Id").val();
    var Mobile_No   =   $("#Mobile_No").val();

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
    else if(checkVideo(BranchName,Department,Desgination,Process,Campaign) ==""){
        $("#Campaign").after("<span id='msgerr' style='color:red;font-size:11px;'>The Branch/ Department/Designation/Process and Campaign you have selected does not have any Training set created.</span>");
        return false;
    }
    else if(User_Name ===""){
        $("#User_Name").focus();
        $("#User_Name").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter name.</span>");
        return false;
    }
    else if(Email_Id ===""){
        $("#Email_Id").focus();
        $("#Email_Id").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter email id.</span>");
        return false;
    }
    else if(!filter.test(Email_Id)){
        $("#Email_Id").focus();
        $("#Email_Id").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter correct email id.</span>");
        return false;
    }
    else if(Mobile_No ===""){
        $("#Mobile_No").focus();
        $("#Mobile_No").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter mobile no.</span>");
        return false;
    }
    else if(Mobile_No.length > 10 ||  Mobile_No.length < 10){
        $("#Mobile_No").focus();
        $("#Mobile_No").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter correct mobile no.</span>");
        return false;
    }
    else if(isAllSameDigit(Mobile_No) ==true){
        $("#Mobile_No").focus();
        $("#Mobile_No").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter correct mobile no.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/add_training_mail",formData).done(function(data){
            $("#showmsg").html(data);
            $("#User_Name,#Email_Id,#Mobile_No").val('');
            view_mail();
        });
    }
}

function checkVideo(BranchName,Department,Desgination,Process,Campaign){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>TrainingQuestionSets/check_video",async: false,dataType: 'json',data: {BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign},done: function(response) {return response;}}).responseText;	
    return posts;
}

function delete_mail(Id){
    if(confirm('Are you sure you want to delete this list item?')){
        window.location.href = "<?php echo $this->webroot;?>TrainingQuestionSets/delete_mail?Id="+Id;
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

function isAllSameDigit(number){
    for(var i = 0; i < number.length; i++){
        if(number[0] != number[i])
            return false;
    }
    return true;
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
                    <span>Send Training Mail</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('TrainingQuestionSets',array('action'=>'set_training_mail','class'=>'form-horizontal','onsubmit'=>'return add_mail()','enctype'=>'multipart/form-data')); ?>
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
                    
                 
                    
                </div>
                 <div class="form-group">
                     
                        <label class="col-sm-1 control-label">Designation</label>
                    <div class="col-sm-2">
                        <select name="Desgination" id="Desgination"  class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
               
                    <label class="col-sm-1 control-label">Process</label>
                    <div class="col-sm-2">
                        <select name="Process" id="Process" onclick="getCampaign(this.value,'')" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Campaign</label>
                    <div class="col-sm-2">
                        <select name="Campaign" id="Campaign" onchange="view_mail()" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                </div>
                
                <div class="box-header"><div class="box-name"><span>User Details</span></div></div>
                
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Name</label>
                    <div class="col-sm-2">
                        <input type="text" name="User_Name" id="User_Name"  autocomplete="off" class="form-control">
                    </div>
                    
                    <label class="col-sm-1 control-label">Email</label>
                    <div class="col-sm-2">
                        <input type="text" name="Email_Id" id="Email_Id"  autocomplete="off" class="form-control">
                    </div>
                    
                    <label class="col-sm-1 control-label">Mobile No</label>
                    <div class="col-sm-2">
                        <input type="text" name="Mobile_No" id="Mobile_No" onkeypress="return isNumberKey(event,this)" maxlength="10"  autocomplete="off" class="form-control">
                    </div>

                    <div class="col-sm-3">
                        <!--
                        <input onclick="sendmail()" type="button" value="Send Mail" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                        -->
                        <input onclick="return window.location='<?php echo $this->webroot;?>Menus?AX=MTMz'" type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                        <input type="button"  value="Send Mail" onclick="sendemail()" id="SendUserMail"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        
                        <input type="button" onclick="add_mail(this.form)"  name="Submit" value="Add"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <div class="loader" style="display:none;" ></div>
                    </div>
                </div>
                <span id="showmsg"><?php echo $this->Session->flash(); ?></span>
                
                <div class="form-group" id="view_mail">
                    <!--
                    <div class="col-sm-12" style="margin-top:-25px;">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Process</th>
                                    <th>Campaign</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Mail Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($DataArr as $val){$row=$val['TrainingEmailSet'];?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Department']?></td>
                                    <td><?php echo $row['Designation']?></td>
                                    <td><?php echo $row['Process']?></td>
                                    <td><?php echo $row['Campaign']?></td>
                                    <td><?php echo $row['User_Name'];?></td>
                                    <td><?php echo $row['Email_Id'];?></td>
                                    <td><?php echo $row['Mobile_No'];?></td>
                                    <td><?php echo $row['Mail_Status'];?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="delete_mail('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    -->
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
