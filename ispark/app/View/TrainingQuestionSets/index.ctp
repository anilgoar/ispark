<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
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

function get_set_no(Campaign){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
   
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
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/get_set_no",{BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign}, function(data){
            $("#Set_No").html(data);
        });      
    }
}

function view_question_set(){ 
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    var Set_No      =   $("#Set_No").val();

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
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/view_question_pending_set",{BranchName:BranchName,Department:Department,Desgination:Desgination,Set_No:Set_No,Process:Process,Campaign:Campaign}, function(data){
            $("#view_question_pending_set").html(data);
        });
        
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/view_question_set",{BranchName:BranchName,Department:Department,Desgination:Desgination,Set_No:Set_No,Process:Process,Campaign:Campaign}, function(data){
            $("#view_question_set").html(data);
        });  
    }
}

function select_question(Set_No){ 
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var Department  =   $("#Department").val();
    var Desgination =   $("#Desgination").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $("#Campaign").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        $('#Set_No').find($('option')).attr('selected',false);
        return false;
    }
    else if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
        $('#Set_No').find($('option')).attr('selected',false);
        return false;
    }
    else if(Desgination ===""){
        $("#Desgination").focus();
        $("#Desgination").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select desgination.</span>");
        $('#Set_No').find($('option')).attr('selected',false);
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
        $('#Set_No').find($('option')).attr('selected',false);
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/select_question",{BranchName:BranchName,Department:Department,Desgination:Desgination,Set_No:Set_No,Process:Process,Campaign:Campaign}, function(data){
            $("#Question").html(data);
        });      
    }
}

function select_option(Id){ 

    var res = Id.split("#####");
    
    $.post("<?php echo $this->webroot;?>TrainingQuestionSets/select_option",{Id:res[0]}, function(data){
        $("#select_option").html(data);
    }); 
}

function update_answer(Answer,Parent_Id,Type){
    var Question        =   $("#Question").val();
    
    if(Type =="delete"){
        if(confirm('Are you sure you want to delete this list?')){
            $.post("<?php echo $this->webroot;?>TrainingQuestionSets/update_answer",{Answer:Answer,Parent_Id:Parent_Id,Type:Type}, function(data){
                select_option(Question);        
                view_question_set();
            });
        }
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/update_answer",{Answer:Answer,Parent_Id:Parent_Id,Type:Type}, function(data){
            select_option(Question);    
            view_question_set();
        });
    }
}

function add_question(){ 
    $("#msgerr").remove();
    
    var BranchName      =   $("#BranchName").val();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Process         =   $("#Process").val();
    var Campaign        =   $("#Campaign").val();
    var Set_No          =   $("#Set_No").val();
    var Add_Type        =   $("#Add_Type").val();
    var Question        =   $("#Question").val();
    var res             =   Question.split("#####");
    var Parent_Id       =   res[0];
    var question_option =   $("#question_option").val();

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
    else if(Add_Type ===""){
        $("#Add_Type").focus();
        $("#Add_Type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select add type.</span>");
        return false;
    }
    else if(Add_Type ==="Option" && Question ===""){
        $("#Question").focus();
        $("#Question").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select question.</span>");
        return false;
    }
    else if(question_option ===""){
        $("#question_option").focus();
        $("#question_option").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter question/option.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/add_question",{
            BranchName:BranchName,
            Department:Department,
            Desgination:Desgination,
            Process:Process,
            Campaign:Campaign,
            Set_No:Set_No,
            Question:question_option,
            Parent_Id:Parent_Id
        }, function(data){
            $("#question_option").val('');
            $("#question_option").after(data);
            select_option(Question);
            //Add_Type=="Question"?select_question(Set_No):$("#Question").html('<option value="">Select Question</option>');
            view_question_set(); 
        });      
    }
}

function submit_question(){ 
    $("#msgerr").remove();
    
    var BranchName      =   $("#BranchName").val();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Process         =   $("#Process").val();
    var Campaign        =   $("#Campaign").val();
    var Set_No          =   $("#Set_No").val();
    
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
    else if(checkOption(BranchName,Department,Desgination,Process,Campaign,Set_No) =="1"){
        $("#Question").after("<span id='msgerr' style='color:red;font-size:11px;'>Option field is pending of given question.</span>");
        return false;
    }
    else if(checkOption(BranchName,Department,Desgination,Process,Campaign,Set_No) =="2"){
        $("#Question").after("<span id='msgerr' style='color:red;font-size:11px;'>Please add minimum two option of given question.</span>");
        return false;
    }
    else if(checkOption(BranchName,Department,Desgination,Process,Campaign,Set_No) =="3"){
        $("#Question").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select answer of given option.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>TrainingQuestionSets/submit_question",{
            BranchName:BranchName,
            Department:Department,
            Desgination:Desgination,
            Process:Process,
            Campaign:Campaign,
            Set_No:Set_No
        }, function(data){
            $("#select_option").html('');
            view_question_set(); 
        });
    }
}

function checkOption(BranchName,Department,Desgination,Process,Campaign,Set_No){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>TrainingQuestionSets/check_option",async: false,dataType: 'json',data: {BranchName:BranchName,Department:Department,Desgination:Desgination,Process:Process,Campaign:Campaign,Set_No:Set_No},done: function(response) {return response;}}).responseText;	
    return posts;
}


function delete_question(Id){
    $("#msgerr").remove();
    
    var BranchName      =   $("#BranchName").val();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Process         =   $("#Process").val();
    var Campaign        =   $("#Campaign").val();
    var Set_No          =   $("#Set_No").val();
    var Question        =   $("#Question").val();
    
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
        else if(Set_No ===""){
            $("#Set_No").focus();
            $("#Set_No").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select set no.</span>");
            return false;
        }
        else{
            $.post("<?php echo $this->webroot;?>TrainingQuestionSets/delete_question",{Id:Id}, function(data){
                select_question(Set_No)  
                select_option(Question);
                view_question_set();
            });
        }
    }
    
}

function upload_video(form){ 
    $("#msgerr").remove();
    
    var BranchName      =   $("#BranchName").val();
    var Department      =   $("#Department").val();
    var Desgination     =   $("#Desgination").val();
    var Set_No          =   $("#Set_No").val();
    var Percent         =   $("#Percent").val();
    var Video           =   $("#Video").val();
    
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
    else if(Video ===""){
        $("#Video").focus();
        $("#Video").after("<span id='msgerr' style='color:red;font-size:11px;'>Please upload video.</span>");
        return false;
    }
    else{
        var formData = new FormData($("#imageform")[0]);
        $.ajax({
            url: "<?php echo $this->webroot;?>TrainingQuestionSets/upload_video",
            type: "POST",            
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,     
            cache: false,           
            processData:false,      
            success: function(data){
                
            }
        });
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

function ShowHideQuestion(type){
    var Set_No          =   $("#Set_No").val();
    
    if(type =="Option"){
        select_question(Set_No);
    }
    else{
        $("#select_option").html('');
        $("#Question").html('<option value="">Select Question</option>');
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
                    <span>Set Training Question</span>
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
                <?php echo $this->Form->create('TrainingQuestionSets',array('action'=>'index','class'=>'form-horizontal','id'=>'imageform')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','onchange'=>'getProcess(this.value,"")','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-2">
                        <select name="Department" id="Department" class="form-control" onchange="getdept(this.value,'Desgination')" >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php echo $data['Dept']==$val?"selected='selected'":'';?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Designation</label>
                    <div class="col-sm-2">
                        <select name="Desgination" id="Desgination"  class="form-control">
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
                        <select name="Campaign" id="Campaign" onchange="get_set_no(this.value)" class="form-control">
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Set No</label>
                    <div class="col-sm-2">
                        <select name="Set_No" id="Set_No" onchange="view_question_set()" autocomplete="off" class="form-control">
                            <option value="">Set No</option>
                        </select>
                    </div>
                    
                    <!--
                    <div class="col-sm-1">
                        <input type="button" name="Submit" onclick="view_question_set()" value="&nbsp;&nbsp;View&nbsp;&nbsp;" class="btn pull-right btn-primary btn-new">
                    </div> 
                    -->
                </div>
                
                <div class="box-header"><div class="box-name"><span>Add Question</span></div></div>
                <div class="form-group">  
                    <div class="col-sm-2">
                        <select name="Add_Type" id="Add_Type" onchange="ShowHideQuestion(this.value)" autocomplete="off" class="form-control">
                            <option value="" >Add Type</option>
                            <option value="Question">Question</option>
                            <option value="Option">Option</option>
                        </select>
                    </div>

                    <div class="col-sm-4" id="Question_Div">
                        <select name="Question" id="Question" onchange="select_option(this.value)"  autocomplete="off" class="form-control">
                            <option value="">Select Question</option>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <input type="text" id="question_option" autocomplete="off" placeholder="Enter question/option" class="form-control">
                    </div>

                    <div class="col-sm-2">
                        <input onclick="return window.location='<?php echo $this->webroot;?>Menus?AX=MTMz'" type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                        <input type="button" onclick="submit_question()" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="button" onclick="add_question()" value="Add" class="btn pull-right btn-primary btn-new">
                        
                    </div>

                    <div class="col-sm-12" id="select_option"></div>  
                </div>
                
                <div class="form-group" id="view_question_pending_set"></div>
                <div class="form-group" id="view_question_set"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
