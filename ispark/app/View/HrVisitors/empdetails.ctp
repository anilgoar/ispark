<?php 
$Read   =   explode(",", $data['Read']);
$Write  =   explode(",", $data['Write']);
$Speak  =   explode(",", $data['Speak']);

echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#Next_Interview_Date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function ValidateForm(Type){ 
    $("#msgerr").remove();
    var Job_Position        =   $("#Job_Position").val();
    var Interview_Round     =   $("#Interview_Round").val();
    var Candidate_Salar_Exp =   $("#Candidate_Salar_Exp").val();
    var Salary_Offer_CTC    =   $("#Salary_Offer_CTC").val();
    var Salary_Offer_Net    =   $("#Salary_Offer_Net").val();
    var Candidate_Feedback  =   $("#Candidate_Feedback").val();
    var Next_Interview_Date =   $("#Next_Interview_Date").val();
    
    if(Job_Position ===""){
        $("#Job_Position").focus();
        $("#Job_Position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter job position.</span>");
        return false;
    }
    else if(Interview_Round ===""){
        $("#Interview_Round").focus();
        $("#Interview_Round").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter interview round.</span>");
        return false;
    }
    else if(Salary_Offer_CTC ===""){
        $("#Salary_Offer_CTC").focus();
        $("#Salary_Offer_CTC").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter salary offer ctc.</span>");
        return false;
    }
    else if(Salary_Offer_Net ===""){
        $("#Salary_Offer_Net").focus();
        $("#Salary_Offer_Net").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter salary offer net.</span>");
        return false;
    }
    else if(Candidate_Feedback ===""){
        $("#Candidate_Feedback").focus();
        $("#Candidate_Feedback").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter candidate feedback.</span>");
        return false;
    }
    else if(Next_Interview_Date ===""){
        $("#Next_Interview_Date").focus();
        $("#Next_Interview_Date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter next interview date.</span>");
        return false;
    }
    else{
        return true;        
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
                    <span>RECRUITER</span>
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
                <?php echo $this->Form->create('HrVisitors',array('action'=>'recruiter','class'=>'form-horizontal','onSubmit'=>'return ValidateForm()')); ?>
                <input type="hidden" name="Interview_Id" value="<?php echo isset($data['Interview_Id'])?$data['Interview_Id']:''?>">
                <div class="box-header"><div class="box-name"><span>Languages</span></div></div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <label class="col-sm-1 control-label">Read</label>
                    <label class="col-sm-1 control-label">Write</label>
                    <label class="col-sm-1 control-label">Speak</label>
                </div>
                
                <div class="form-group" style="overflow-y: scroll;height: 150px;">
                <?php foreach($language as $lang){ ?>
                    
                    
                    
                <div class="col-sm-12">
                    <label class="col-sm-2 control-label" style="margin-top:-5px;"><?php echo $lang['LanguageMaster']['Language_Name'];?></label>
                    <div class="col-sm-1" style="margin-left: 12px;margin-top:-5px;" >
                        <input type="checkbox" <?php if(in_array($lang['LanguageMaster']['Language_Name'],$Read)){echo "checked";} ?> name="Read[]" value="<?php echo $lang['LanguageMaster']['Language_Name'];?>" autocomplete="off" class="form-control" >
                    </div>
                    <div class="col-sm-1" style="margin-top:-5px;">
                        <input type="checkbox" <?php if(in_array($lang['LanguageMaster']['Language_Name'],$Write)){echo "checked";} ?> name="Write[]" value="<?php echo $lang['LanguageMaster']['Language_Name'];?>" autocomplete="off" class="form-control" >
                    </div>
                    <div class="col-sm-1" style="margin-top:-5px;">
                        <input type="checkbox" <?php if(in_array($lang['LanguageMaster']['Language_Name'],$Speak)){echo "checked";} ?> name="Speak[]" value="<?php echo $lang['LanguageMaster']['Language_Name'];?>" autocomplete="off" class="form-control" >
                    </div> 
                </div>
                <?php }?>
                </div>
                
                <div class="box-header"><div class="box-name"><span>Questionnaire Round</span></div></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Job Position</label>
                    <div class="col-sm-2">
                        <input type="text" name="Job_Position" id="Job_Position" value="<?php echo isset($data['Job_Position'])?$data['Job_Position']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Interview Round</label>
                    <div class="col-sm-2">
                        <input type="text" name="Interview_Round" id="Interview_Round" value="<?php echo isset($data['Interview_Round'])?$data['Interview_Round']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)" >
                    </div>
                    <label class="col-sm-2 control-label">Candidate Salary Exp</label>
                    <div class="col-sm-2">
                        <input type="text" name="Candidate_Salar_Exp" id="Candidate_Salar_Exp" value="<?php echo isset($data['Candidate_Salar_Exp'])?$data['Candidate_Salar_Exp']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Salary Offer CTC</label>
                    <div class="col-sm-2">
                        <input type="text" name="Salary_Offer_CTC" id="Salary_Offer_CTC" value="<?php echo isset($data['Salary_Offer_CTC'])?$data['Salary_Offer_CTC']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)">
                    </div>
                    
                    <label class="col-sm-2 control-label">Salary Offer Net</label>
                    <div class="col-sm-2">
                        <input type="text" name="Salary_Offer_Net"  id="Salary_Offer_Net" value="<?php echo isset($data['Salary_Offer_Net'])?$data['Salary_Offer_Net']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Candidate Feedback</label>
                    <div class="col-sm-6">
                        <textarea name="Candidate_Feedback" id="Candidate_Feedback" autocomplete="off" class="form-control" required=""><?php echo isset($data['Candidate_Feedback'])?$data['Candidate_Feedback']:''?></textarea>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Next Interview Date</label>
                    <div class="col-sm-2">
                        <input type="text" name="Next_Interview_Date" id="Next_Interview_Date" value="<?php echo isset($data['Next_Interview_Date'])?date("d-M-Y",strtotime($data['Next_Interview_Date'])):''?>"  autocomplete="off" class="form-control" required="" >                    
                    </div>
                    <div class="col-sm-1">
                        <input type="submit" name="Submit" value="Update" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $this->webroot;?>HrVisitors/hrapproval"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                        <input type="submit" name="Submit" value="Save" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

