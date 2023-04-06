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
    var Interview_Total_Round     =   $("#Interview_Total_Round").val();
    
    
    var Read                =   checkLanguage('Read');
    var Write               =   checkLanguage('Write');
    var Speak               =   checkLanguage('Speak');

    if(Read.length ==0 && Write.length ==0 && Speak.length ==0){
        $("#lang").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select language.</span>");
        return false;
    }
    else if(Job_Position ===""){
        $("#Job_Position").focus();
        $("#Job_Position").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter job position.</span>");
        return false;
    }
    else if(Interview_Round ===""){
        $("#Interview_Round").focus();
        $("#Interview_Round").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter interview round.</span>");
        return false;
    }
    else if(Interview_Total_Round ==="" && Interview_Round !="1"){
        $("#Interview_Round").focus();
        $("#Interview_Round").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select first round.</span>");
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
    /*
    else if(Next_Interview_Date ===""){
        $("#Next_Interview_Date").focus();
        $("#Next_Interview_Date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter next interview date.</span>");
        return false;
    }*/
    else{
        return true;        
    }
}

function checkLanguage(InputName){
    var all_location_id = document.querySelectorAll('input[name="'+InputName+'[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
        aIds.push(all_location_id[x].value);
    }
    return aIds;
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

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function question(designation){
    
    var Dept            =   $("#Dept").val();
    var Interview_Round =   $("#Interview_Round").val();
    
    if(Interview_Round ===""){
        $("#Interview_Round").focus();
        $("#Interview_Round").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select interview round.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>HrVisitors/getquestion",{designation:$.trim(designation),Dept:Dept,Interview_Round:Interview_Round},function(data){
            $("#question_id").html(data);
        });
    }
}
</script>
<style>
.req{
    color:red;
}
</style>
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
                <div class="box-header"><div class="box-name"><span>Languages <span class='req'>*</span></div></div>
                <div id="lang"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <label class="col-sm-1 control-label">Read</label>
                    <label class="col-sm-1 control-label">Write</label>
                    <label class="col-sm-1 control-label">Speak</label>
                </div>
                
                <div class="form-group" style="overflow-y: scroll;height: 150px;">
                <?php foreach($language as $lang){ ?>
                    
                    
                    
                    <div class="col-sm-12"  >
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
				
				<div class="box-header"><div class="box-name"><span>System Confirmation </span></div></div>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">A) Phone</label>
					
                </div>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">Smart phone ( name of the brand) <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Phone_Brand_Name" id="Phone_Brand_Name" value="<?php echo isset($data['Phone_Brand_Name'])?$data['Phone_Brand_Name']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
					
					<label class="col-sm-2 control-label">RAM – 3GB and above <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Phone_Ram_Size" id="Phone_Ram_Size" value="<?php echo isset($data['Phone_Ram_Size'])?$data['Phone_Ram_Size']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
				
				<hr/>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">B) Laptop</label>
                </div>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">Model name <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Laptop_Model_Name" id="Laptop_Model_Name" value="<?php echo isset($data['Laptop_Model_Name'])?$data['Laptop_Model_Name']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
					
					<label class="col-sm-2 control-label">Configuration: <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Laptop_Configuration" id="Laptop_Configuration" value="<?php echo isset($data['Laptop_Configuration'])?$data['Laptop_Configuration']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
					
					<label class="col-sm-2 control-label">Serial number <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Laptop_Serial_Number" id="Laptop_Serial_Number" value="<?php echo isset($data['Laptop_Serial_Number'])?$data['Laptop_Serial_Number']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
				
				<hr/>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">C) Internet availability</label>
                </div>
				
				<div class="form-group">
                    <label class="col-sm-2 control-label">Broadband service provider ( please furnish the last bill) <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Internet_Service_Provider" id="Internet_Service_Provider" value="<?php echo isset($data['Internet_Service_Provider'])?$data['Internet_Service_Provider']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
					
					<label class="col-sm-2 control-label">Speed<span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Internet_Speed" id="Internet_Speed" value="<?php echo isset($data['Internet_Speed'])?$data['Internet_Speed']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                <div class="box-header"><div class="box-name"><span>Candidate Feedback & Salary Exp</span></div></div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Job Position <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Job_Position" id="Job_Position" value="<?php echo isset($data['Job_Position'])?$data['Job_Position']:''?>"  autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Interview Round <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <!--
                        <input type="text"  value="<?php //echo isset($data['Interview_Round'])?$data['Interview_Round']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)" >
                        -->

                        <input type="hidden" id="Interview_Total_Round" value="<?php echo isset($data['Interview_Total_Round'])?$data['Interview_Total_Round']:''?>" >
                        
                        <select name="Interview_Round" id="Interview_Round" class="form-control" required=""  >
                            <option value="">Select</option>
                            <option <?php echo $data['Interview_Round']=='1'?"selected='selected'":''?> value="1">1</option>
                            <option <?php echo $data['Interview_Round']=='2'?"selected='selected'":''?> value="2">2</option>
                            <option <?php echo $data['Interview_Round']=='3'?"selected='selected'":''?> value="3">3</option>
                        </select>
                    
                    </div>
                    <label class="col-sm-2 control-label">Candidate Salary Exp <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Candidate_Salar_Exp" id="Candidate_Salar_Exp" value="<?php echo isset($data['Candidate_Salar_Exp'])?$data['Candidate_Salar_Exp']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Salary Offer CTC <span class='req'>*</span></label>
                    <div class="col-sm-2">
                        <input type="text" name="Salary_Offer_CTC" id="Salary_Offer_CTC" value="<?php echo isset($data['Salary_Offer_CTC'])?$data['Salary_Offer_CTC']:''?>"  autocomplete="off" class="form-control" required="" onkeypress="return isNumberKey(event,this)">
                    </div>
                    
					<!--
                    <label class="col-sm-2 control-label">Salary Offer Net <span class='req'>*</span></label>
                    <div class="col-sm-2">
					-->
                        <input type="hidden" name="Salary_Offer_Net"  id="Salary_Offer_Net" value="<?php echo isset($data['Salary_Offer_Net'])?$data['Salary_Offer_Net']:''?>"  autocomplete="off" class="form-control" onkeypress="return isNumberKey(event,this)">
                    <!--
					</div>
                </div>
                -->
				
                <div class="form-group">
                    <label class="col-sm-2 control-label">Next Interview Date</label>
                    <div class="col-sm-2">
                        <input type="text" name="Next_Interview_Date" id="Next_Interview_Date" value="<?php echo isset($data['Next_Interview_Date'])?date("d-M-Y",strtotime($data['Next_Interview_Date'])):''?>"  autocomplete="off" class="form-control" >                    
                    </div>
                    
                </div>
                
                <div class="box-header"><div class="box-name"><span>Questionnaire Round</span></div></div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Department</label>
                    <div class="col-sm-2">
                        <select name="Dept" id="Dept" class="form-control" onchange="getdept(this.value,'Desgination')"   >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php echo $data['Dept']==$val?"selected='selected'":'';?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Designation</label>
                    <div class="col-sm-2">
                        <select name="Desgination" id="Desgination" class="form-control" onchange="question(this.value)"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>
                
                <div id="question_id"></div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Candidate Feedback <span class='req'>*</span></label>
                    <div class="col-sm-6">
                        <textarea name="Candidate_Feedback" id="Candidate_Feedback" autocomplete="off" class="form-control" required=""><?php echo isset($data['Candidate_Feedback'])?$data['Candidate_Feedback']:''?></textarea>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-8">
                        <input type="submit" name="Submit" value="Update" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                    
                <div class="form-group">
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $this->webroot;?>HrVisitors"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                        <input type="submit" name="Submit" value="Save" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

