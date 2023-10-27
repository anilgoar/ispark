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

</script>
        
<script> 

        
function backpage(){ 
    window.location="<?php echo $this->webroot;?>ChangeDojs/jclrentry";
} 
    

function validate(tab){
    
    var EmpLocation=  $("#EmpLocation").val();
    var BioCode    =  $("#BioCode").val();
    var DOB        =  $("#DOB").val();
    var DOJ        =  $("#DOJ").val();

    if(EmpLocation ===""){
         $("#EmpLocation").addClass('bordered'); 
        $("#EmpLocation").after("<span id='msgerr' class='msger'>Please select Emp Location.</span>");
        return false;
    }
    else if(BioCode ===""){
         $("#BioCode").addClass('bordered'); 
        $("#BioCode").after("<span id='msgerr' class='msger'>Please Enter Bio Code.</span>");
        return false;
    }
    else if(DOJ ===""){
         $("#DOJ").addClass('bordered'); 
        $("#DOJ").after("<span id='msgerr' class='msger'>Please select date of joining.</span>");
        return false;
    }
    else if(checkDob(DOB,DOJ) ==""){
         $("#DOB").addClass('bordered'); 
        $("#DOB").after("<span id='msgerr' class='msger'>Employee age should 18 year.</span>");
        return false;
    }

    else{
       
        if (confirm('Are you sure you want to submit this form?')) {         
            $("#JCLRFORM").submit();       
        } else {
            return false;
        }
        
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

    //var posts1  = $.ajax({type: 'POST',url:"<?php //echo $this->webroot;?>JoiningMasters/check_date1",async: false,dataType: 'json',data: {oldjoin:oldjoin,newjoin:newjoin},done: function(response) {return response;}}).responseText;
   
   
}

function checkDob(FromDate,ToDate){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>ChangeDojs/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
    return posts;
}


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
                    <span>EMPLOYEE DETAILS</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            
            <div class="box-content box-con" >
                
                <?php echo $this->Form->create('ChangeDojs',array('action'=>'newjclr','class'=>'form-horizontal','id'=>'JCLRFORM')); ?>
                <input type="hidden" name="MasJclrsId" id="MasJclrsId" value="<?php echo $Jclr['Masjclrentry']['id'];?>" >
               
                <div class="form-group has-info has-feedback">
                     <label class="col-sm-2 control-label">Employee Type <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpType', array('label'=>false,'class'=>'form-control','id'=>'EmpType','value'=>$Jclr['Masjclrentry']['EmpType'],'readonly'=>true)); ?>
                    </div>

                    <label class="col-sm-3 control-label">Branch Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('BranchName', array('label'=>false,'class'=>'form-control','id'=>'BranchName','value'=>$Jclr['Masjclrentry']['BranchName'],'readonly'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Employee Location <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpLocation',array('label'=>false,'class'=>'form-control','id'=>'EmpLocation','value'=>$Jclr['Masjclrentry']['EmpLocation'],'onChange'=>'Location(this.value)','options'=>array('InHouse'=>'InHouse','OnSite'=>'OnSite','Field'=>'Field'),'empty'=>'Select')); ?>
                    </div>

                    <label class="col-sm-3 control-label">Biometric Code <span class="req">*</span></label>
                    <div class="col-sm-3" >
                        <input type="text" name="BioCode" id="BioCode" value="<?php echo $Jclr['Masjclrentry']['BioCode'];?>" class="form-control">
                    </div>
                </div>

                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Emp Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpName', array('label'=>false,'class'=>'form-control','id'=>'EmpName','onkeyup'=>'javascript:capitalize(this.id, this.value);','value'=>$Jclr['Masjclrentry']['EmpName'],'readonly'=>true)); ?>
                    </div>

                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Date of Birth <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOB" id="DOB" class="form-control" value="<?php echo date_format(date_create($Jclr['Masjclrentry']['DOB']),'d-M-Y');?>" autocomplete="off" readonly="" >
                    </div>

                    <label class="col-sm-3 control-label">Date of Joining <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOJ" id="DOJ" class="form-control datepickers" value="<?php echo date_format(date_create($Jclr['Masjclrentry']['DOJ']),'d-M-Y');?>" autocomplete="off" onchange="validatedob()" >
                    </div>
                </div>
               
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-11">
                        <input type='button' class="btn btn-info btn-new pull-right" value="Save Details" onclick="validate();" style="margin-left:5px;" >
                    </div>
                </div>
                
                <span><?php echo $this->Session->flash();?></span>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
