<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $(".datepickers").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
    
function checkNumber(val,evt){

    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            else{
              
                 return true; 
           
           
        }
}

function validateNewemployee(){

    $("#msgerr").remove();
    $(".bordered").removeClass('bordered'); 
   
    var DOJ=$("#DOJ").val();
    var Dept=$("#Dept").val();
    var Desgination=$("#Desgination").val();
    var Band=$("#Band").val();
    var Package=$("#Package").val();
    var CTC=$("#CTC").val();
    var CostCenter=$("#CostCenter").val();
    var NetInHand=$("#NetInHand").val();
    var EmpType=$("#EmpType").val();
    
    if(DOJ ===""){
        $("#DOJ").addClass('bordered'); 
        $("#DOJ").after("<span id='msgerr' class='msger'>Please select date of joining.</span>");
        return false;
    }
    /*
    else if(checkDob(DOB,DOJ) ==""){
        $("#DOB").addClass('bordered'); 
        $("#DOB").after("<span id='msgerr' class='msger'>Employee age should 18 year.</span>");
        return false;
    }*/
    else if(Dept ===""){
        $("#Dept").addClass('bordered'); 
        $("#Dept").after("<span id='msgerr' class='msger'>Please select department .</span>");
        return false;
    }
    else if(Desgination ===""){
        $("#Desgination").addClass('bordered'); 
        $("#Desgination").after("<span id='msgerr' class='msger'>Please select desgination.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' class='msger'>Please select cost center.</span>");
        return false;
    }
    else if(Band ===""){
        $("#Band").addClass('bordered'); 
        $("#Band").after("<span id='msgerr' class='msger'>Please select band.</span>");
        return false;
    }
    else if(Package ===""){
        $("#Package").addClass('bordered'); 
        $("#Package").after("<span id='msgerr' class='msger'>Please select package.</span>");
        return false;
    }
    else if(CTC ===""){
        $("#CTC").focus();
        $("#CTC").after("<span id='msgerr' class='msger'>Please select ctc.</span>");
        return false;
    }
    else if(NetInHand ===""){
        $("#NetInHand").focus();
        $("#NetInHand").after("<span id='msgerr' class='msger'>Please select net inhand.</span>");
        return false;
    }
    else if(EmpType ==="ONROLL" && CTC ==NetInHand){
        $("#Package").addClass('bordered'); 
        $("#Package").after("<span id='msgerr' class='msger'>Please select onroll package.</span>");
        return false;
    }
    else if(EmpType ==="MGMT. TRAINEE" && CTC !=NetInHand){
        $("#Package").addClass('bordered'); 
        $("#Package").after("<span id='msgerr' class='msger'>Please select mgmt trainee package.</span>");
        return false;
    }
    else{
        return true;
    }
}

function validatedob(){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var DOB=$("#DOB").val();
    var DOJ=$("#DOJ").val();
    
    if(DOB ===""){
        $("#DOJ").val('');
        $("#DOB").addClass('bordered'); 
        $("#DOB").after("<span id='msgerr' class='msger'>Please select date of birth.</span>");
        return false;
    }
    else if(checkDob(DOB,DOJ) ==""){
        $("#DOJ").val('');
        $("#DOB").addClass('bordered'); 
        $("#DOB").after("<span id='msgerr' class='msger'>Employee age should 18 year.</span>");
        return false;
    }
}

function checkDob(FromDate,ToDate){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>Masjclrs/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
    return posts;
}

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function getdesg(Designation,id,band){
    $.post("<?php echo $this->webroot;?>HrVisitors/getdesg",{'Designation':$.trim(Designation),'band':$.trim(band)},function(data){
        $("#"+id).html(data);
    });
}

function editdept(Designation,Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/editdept",{'Designation':$.trim(Designation),'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function getband(Band,id,package){
    $("#msgerr").remove();
    var CostCenter=$("#CostCenter").val();
    
    if(CostCenter ===""){
        $("#Band").val('');
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' class='msger'>Please select cost center.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>HrVisitors/getband",{'Band':$.trim(Band),CostCenter:CostCenter,'package':$.trim(package)},function(data){
            $("#"+id).html(data);
        });
    }
}

function getctc(Package,id){
     var CostCenter=$("#CostCenter").val();
    $("#"+id).val('');
    $("#NetInHand").val('');
     $("#mm").html('');
     
    if(Package !=""){
        $.post("<?php echo $this->webroot;?>Masjclrs/getctc",{'Package':$.trim(Package),CostCenter:CostCenter},function(data){
            $("#"+id).val(data);
        });

        $.post("<?php echo $this->webroot;?>Masjclrs/getinhand",{'Package':$.trim(Package),CostCenter:CostCenter},function(data){
            $("#NetInHand").val(data);
        });

        $.post("<?php echo $this->webroot;?>Masjclrs/getpackage",{'Package':$.trim(Package),CostCenter:CostCenter},function(data){
            $("#mm").html(data);
        });
    } 
}

$(document).ready(function(){
    editdept('<?php echo $data['Desgination'];?>','<?php echo $data['Dept'];?>','Desgination'); 
    getdesg('<?php echo $data['Desgination'];?>','Band','<?php echo $data['Band'];?>');
    getband('<?php echo $data['Band'];?>','Package','<?php echo $data['package'];?>');
    getctc('<?php echo $data['package'];?>','CTC');
});
</script>

<style>
.req{
    color:red;
    font-weight: bold;
    font-size: 16px;
}
.msger{
    color:red;
    font-size:11px;
}
.bordered{
    border-color: red;
}
.col-sm-2{margin-top:-12px !important;}
.col-sm-3{margin-top:-12px !important;}
</style>

<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
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
                    <span>HR UPDATE DETAILS</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <span><?php echo $this->Session->flash();?></span>
                <?php echo $this->Form->create('HrVisitors',array('action'=>'hrupdate','class'=>'form-horizontal','onSubmit'=>'return validateNewemployee()')); ?>
                <input type="hidden" name="Interview_Id" value="<?php echo isset($data['Interview_Id'])?$data['Interview_Id']:''?>" >
                <input type="hidden" name="DOB" id="DOB" value="<?php echo isset($data['Date_Of_Birth'])?$data['Date_Of_Birth']:''?>" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">Employee Type</label>
                    <div class="col-sm-3">
                        <select name="EmpType" id="EmpType" class="form-control"  >
                            <option <?php echo $data['EmpType']=="ONROLL"?"selected='selected'":'';?> value="ONROLL" >ONROLL</option>
                            <option <?php echo $data['EmpType']=="MGMT. TRAINEE"?"selected='selected'":'';?> value="MGMT. TRAINEE" >MGMT. TRAINEE</option>
                        </select>
                    </div>
         
                    <label class="col-sm-2 control-label">Date of Joining <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOJ" id="DOJ" value="<?php echo isset($data['DOJ'])?date('d-M-Y',strtotime($data['DOJ'])):''; ?>"  class="form-control datepickers" autocomplete="off" readonly="" onchange="validatedob()" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Profile <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Profile" id="Profile" class="form-control"  >
                            <option value="" >Select</option>
                            <option <?php if($data['Profile'] =="VOICE"){echo "selected='selected'";}?> value="VOICE">VOICE</option>
                            <option <?php if($data['Profile'] =="NON-VOICE"){echo "selected='selected'";}?> value="NON-VOICE">NON-VOICE</option>
                            <option <?php if($data['Profile'] =="TRANSACTIONAL PROCESSING"){echo "selected='selected'";}?> value="TRANSACTIONAL PROCESSING">TRANSACTIONAL PROCESSING</option>
                            <option <?php if($data['Profile'] =="BUSINESS DEVELOPMENT"){echo "selected='selected'";}?> value="BUSINESS DEVELOPMENT">BUSINESS DEVELOPMENT</option>
                            <option <?php if($data['Profile'] =="SOFTWARE ENGINEER"){echo "selected='selected'";}?> value="SOFTWARE ENGINEER">SOFTWARE ENGINEER</option>
                            <option <?php if($data['Profile'] =="HARDWARE ENGINEER"){echo "selected='selected'";}?> value="HARDWARE ENGINEER">HARDWARE ENGINEER</option>
                            <option <?php if($data['Profile'] =="RECRUITMENT"){echo "selected='selected'";}?> value="RECRUITMENT">RECRUITMENT</option>
                            <option <?php if($data['Profile'] =="TRAINING AND DEVELOPMENT"){echo "selected='selected'";}?> value="TRAINING AND DEVELOPMENT">TRAINING AND DEVELOPMENT</option>
                            <option <?php if($data['Profile'] =="HR OPERATIONS"){echo "selected='selected'";}?> value="HR OPERATIONS">HR OPERATIONS</option>
                            <option <?php if($data['Profile'] =="HR EMPLOYEE RELATION"){echo "selected='selected'";}?> value="HR EMPLOYEE RELATION">HR EMPLOYEE RELATION</option>
                            <option <?php if($data['Profile'] =="HR GENERALISTIC"){echo "selected='selected'";}?> value="HR GENERALISTIC">HR GENERALISTIC</option>
                            <option <?php if($data['Profile'] =="ACCOUNTS"){echo "selected='selected'";}?> value="ACCOUNTS">ACCOUNTS</option>
                            <option <?php if($data['Profile'] =="FINANCE"){echo "selected='selected'";}?> value="FINANCE">FINANCE</option>
                            <option <?php if($data['Profile'] =="FACILITY MGMT."){echo "selected='selected'";}?> value="FACILITY MGMT.">FACILITY MGMT.</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Department <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Dept" id="Dept" class="form-control" onchange="getdept(this.value,'Desgination')"   >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php echo $data['Dept']==$val?"selected='selected'":'';?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Desgination <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Desgination" id="Desgination" class="form-control" onchange="getdesg(this.value,'Band','')"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Cost Center <span class="req">*</span></label></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('CostCenter', array('label'=>false,'class'=>'form-control','id'=>'CostCenter','value'=>$data['CostCenter'],'options'=>$tower1,'empty'=>'Select')); ?>
                    </div>
                    
                    
                </div>
    
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Band <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Band" id="Band" class="form-control" onchange="getband(this.value,'Package','')" >
                            <option value="" >Select</option>
                        </select>
                    </div>  
                    
                    <label class="col-sm-2 control-label">Package <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="package" id="Package" class="form-control" onchange="getctc(this.value,'CTC')" >
                            <option value="" >Select</option>
                        </select>
                    </div> 
                </div>
    
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">CTC Offered <span class="req"></span></label></label>
                    <div class="col-sm-3">
                        <input type="text" name="CTC" id="CTC" class="form-control" readonly="" >
                    </div>
                
                    <label class="col-sm-2 control-label">Net In Hand <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="NetInHand" id="NetInHand" class="form-control" readonly="" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $this->webroot;?>HrVisitors/hrapproval"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-info btn-new pull-right" value="Submit" style="margin-left:5px;" >
                    </div>
                </div>
    
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-3 control-label"> </label>
                    <div class="col-sm-3 " id="mm" style="margin-left: 170px;" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
  
 