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
    /*
    function checkdate(){
        var date1 = new Date('7/11/2010');
var date2 = new Date('12/12/2001');
var diffDays = date2 - date1; 
alert(diffDays)
    }
        function getData(val)
        {
            var dept1 = $("#JclrDept").val();;
            $.post("Masjclrs/get_package",{desgn:val},function(data)
            {$("#mm").html(data);});
             getNetData(val);
             getCTC(val);
        }
        
        function getCTC(val2)
        {
            document.getElementById('CTC').value=val2; 
        }
        function getpackageData(val2)
        {
            
            
            $.post("Masjclrs/showpack",{pack:val2},function(data)
            {$("#data").html(data);});
           
        }
        
        function getNetData(val2)
        {
            
            
            $.post("Masjclrs/showctc",{desgn:val2},function(data)
            {$("#data12").html(data);});
        }
        
        */
        
    function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            else{
              
                 return true; 
           
           
        }
        }
	
        
/*
 function Design(val)
  {
      $.post("Masjclrs/get_design",{val},function(data){
        $('#tower').html(data);});
  }
  function band(val)
  {
      $.post("Masjclrs/get_band",{val},function(data){
        $('#band').html(data);});
  }

*/
        </script>
        
<script> 
$(document).ready(function(){
    var str ='';
        var radioValue = $("input[name='Sw']:checked").val();
        if(radioValue){
            str +='<input type="text" name="Father" id ="CustomerNameNew" onkeyup="javascript:capitalize(this.id, this.value);" class="form-control" autocomplete="off"  placeholder="Father Name">';
        }
    $('#namerel').html(str);
});
    
    
function backpage(){ 
    window.location="<?php echo $this->webroot;?>Masjclrs/newemp";
} 
    
function Test(val) {
    var str ='';
    if(val=='Husband'){
       str +='<input type="text" name="Husband" id ="CustomerNameNew" onkeyup="javascript:capitalize(this.id, this.value);" class="form-control" autocomplete="off" placeholder="Husband Name">';
    }
    else if(val=='Father'){
        str +='<input type="text" name="Father" id="CustomerNameNew" onkeyup="javascript:capitalize(this.id, this.value);" class="form-control" autocomplete="off"  placeholder="Father Name">';       
    }
    document.getElementById('namerel').innerHTML=str;
}


function capitalize(textboxid, str) {
    var res = str.toUpperCase();
    document.getElementById(textboxid).value =  res;
}

function validateNewemployee(){

    $("#msgerr").remove();
    $(".bordered").removeClass('bordered'); 
    var EmpName=$("#EmpName").val();
    var radioValue = $("input[name='Sw']:checked").val();
    var CustomerNameNew=$("#CustomerNameNew").val();
    var DOB=$("#DOB").val();
    var DOJ=$("#DOJ").val();
    var Adrress1=$("#Adrress1").val();
    var Adrress2=$("#Adrress2").val();
    var State=$("#State").val();
    var State1=$("#State1").val();
    var City=$("#City").val();
    var City1=$("#City1").val();
    var PinCode=$("#PinCode").val();
    var PinCode1=$("#PinCode1").val();
    var Dept=$("#Dept").val();
    var Desgination=$("#Desgination").val();
    var Band=$("#Band").val();
    var Package=$("#Package").val();
    var CTC=$("#CTC").val();
    var CostCenter=$("#CostCenter").val();
    var NetInHand=$("#NetInHand").val();
    var EmpType=$("#EmpType").val();
    
    if($.trim(EmpName) ===""){
        $("#EmpName").addClass('bordered'); 
        $("#EmpName").after("<span id='msgerr' class='msger'>Please enter employee name.</span>");
        return false;
    }
    else if(radioValue =="Father" && CustomerNameNew ==""){
        $("#CustomerNameNew").addClass('bordered'); 
        $("#CustomerNameNew").after("<span id='msgerr' class='msger'>Please enter father name.</span>");
        return false;
    }
    else if(radioValue =="Husband" && CustomerNameNew ==""){
        $("#CustomerNameNew").addClass('bordered'); 
        $("#CustomerNameNew").after("<span id='msgerr' class='msger'>Please enter husband name.</span>");
        return false;
    }
    else if(DOB ===""){
         $("#DOB").addClass('bordered'); 
        $("#DOB").after("<span id='msgerr' class='msger'>Please select date of birth.</span>");
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
    else if(Adrress1 ===""){
        $("#Adrress1").addClass('bordered'); 
        $("#Adrress1").after("<span id='msgerr' class='msger'>Please enter permanent address.</span>");
        return false;
    }
    else if(Adrress2 ===""){
        $("#Adrress2").addClass('bordered'); 
        $("#Adrress2").after("<span id='msgerr' class='msger'>Please enter present address.</span>");
        return false;
    }
    else if(State ===""){
        $("#State").addClass('bordered'); 
        $("#State").after("<span id='msgerr' class='msger'>Please select state.</span>");
        return false;
    }
    else if(State1 ===""){
        $("#State1").addClass('bordered'); 
        $("#State1").after("<span id='msgerr' class='msger'>Please select state.</span>");
        return false;
    }
    else if(City ===""){
        $("#City").addClass('bordered'); 
        $("#City").after("<span id='msgerr' class='msger'>Please select city.</span>");
        return false;
    }
    else if(City1 ===""){
        $("#City1").addClass('bordered'); 
        $("#City1").after("<span id='msgerr' class='msger'>Please select city.</span>");
        return false;
    }
    else if(PinCode ===""){
        $("#PinCode").addClass('bordered'); 
        $("#PinCode").after("<span id='msgerr' class='msger'>Please select pin code.</span>");
        return false;
    }
    else if(PinCode1 ===""){
        $("#PinCode1").addClass('bordered'); 
        $("#PinCode1").after("<span id='msgerr' class='msger'>Please select pin code.</span>");
        return false;
    }
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
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' class='msger'>Please select cost center.</span>");
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

function getcity(state,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getcity",{'state':$.trim(state)},function(data){
        $("#"+id).html(data);
    });
}

function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function getdesg(Designation,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdesg",{'Designation':$.trim(Designation)},function(data){
        $("#"+id).html(data);
    });
}

function getband(Band,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getband",{'Band':$.trim(Band)},function(data){
        $("#"+id).html(data);
    });
}

function getctc(Package,id){
    $("#"+id).val('');
    $("#NetInHand").val('');
     $("#mm").html('');
     
    if(Package !=""){
        $.post("<?php echo $this->webroot;?>Masjclrs/getctc",{'Package':$.trim(Package)},function(data){
            $("#"+id).val(data);
        });

        $.post("<?php echo $this->webroot;?>Masjclrs/getinhand",{'Package':$.trim(Package)},function(data){
            $("#NetInHand").val(data);
        });

        $.post("<?php echo $this->webroot;?>Masjclrs/getpackage",{'Package':$.trim(Package)},function(data){
            $("#mm").html(data);
        });
    } 
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
                    <span>JCLR NEW ENTRY</span>
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
                <?php echo $this->Form->create('MasJclrMaster',array('class'=>'form-horizontal','onSubmit'=>'return validateNewemployee()')); ?>
     
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-3">
                        <select name="Title" id="Title" class="form-control"  >
                            <option value="MR." >MR.</option>
                            <option value="MS." >MS.</option>
                            <option value="MRS." >MRS.</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Employee Type</label>
                    <div class="col-sm-3">
                        <select name="EmpType" id="EmpType" class="form-control"  >
                            <option value="ONROLL" >ONROLL</option>
                            <option value="MGMT. TRAINEE" >MGMT. TRAINEE</option>
                        </select>
                    </div>
                </div>
    
                <div class="form-group">
                    <label class="col-sm-2 control-label">Emp Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="EmpName" onkeyup="javascript:capitalize(this.id, this.value);" id="EmpName" class="form-control" autocomplete="off" >
                    </div>

                    <label class="col-sm-2 control-label">
                        <input type="radio" name="Sw" value="Father" onclick='Test(this.value);'  checked/> <strong>Father</strong>
                        <input type="radio" name="Sw" value="Husband" onclick='Test(this.value);'  /> <strong>Husband</strong> <span class="req">*</span>
                    </label>
                    <div class="col-sm-3" id="namerel" ></div>
                </div>
    
                <div class="form-group">
                    <label class="col-sm-2 control-label">Date of Birth <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOB" id="DOB" class="form-control datepickers" autocomplete="off" readonly="" >
                    </div>

                    <label class="col-sm-2 control-label">Date of Joining <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOJ" id="DOJ" class="form-control datepickers" autocomplete="off" readonly="" onchange="validatedob()" >
                    </div>
                </div>
    
                <div class="form-group">
                    <label class="col-sm-2 control-label">Gendar <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Gendar" id="Gendar" class="form-control"  >
                            <option value="MALE" >MALE</option>
                            <option value="FEMALE" >FEMALE</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Blood Group </label>
                    <div class="col-sm-3">
                        <select name="BloodGruop" id="BloodGruop" class="form-control"  >
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="NA">NA</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Permanent Address <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <textarea name="Adrress1" id="Adrress1" class="form-control" autocomplete="off" style="height:60px;" ></textarea>
                    </div>

                    <label class="col-sm-2 control-label">Present Address <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <textarea name="Adrress2" id="Adrress2" class="form-control" autocomplete="off" style="height:60px;" ></textarea>
                    </div>
                </div>
        
                <div class="form-group">
                    <label class="col-sm-2 control-label">State <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="State" id="State" class="form-control" onchange="getcity(this.value,'City')"  >
                            <option value="" >Select</option>
                            <?php foreach($state as $key=>$val){?>
                            <option value="<?php echo $key;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">State <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="State1" id="State1" class="form-control" onchange="getcity(this.value,'City1')"   >
                            <option value="" >Select</option>
                            <?php foreach($state as $key=>$val){?>
                            <option value="<?php echo $key;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">City <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="City" id="City" class="form-control"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">City <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="City1" id="City1" class="form-control"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">PinCode <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="PinCode" id="PinCode" class="form-control" autocomplete="off"  onKeyPress="return checkNumber(this.value,event)" maxlength="6" >
                    </div>
                    
                    <label class="col-sm-2 control-label">PinCode <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="PinCode1" id="PinCode1" autocomplete="off" class="form-control" onKeyPress="return checkNumber(this.value,event)" maxlength="6" >
                    </div>
                </div>
    
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Department <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Dept" id="Dept" class="form-control" onchange="getdept(this.value,'Desgination')"   >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label">Desgination <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Desgination" id="Desgination" class="form-control" onchange="getdesg(this.value,'Band')"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>

                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Band <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Band" id="Band" class="form-control" onchange="getband(this.value,'Package')" >
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
                
                    <label class="col-sm-2 control-label">Cost Center <span class="req">*</span></label></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('CostCenter', array('label'=>false,'class'=>'form-control','id'=>'CostCenter','options'=>$tower1,'empty'=>'Select')); ?>
                    </div>
                
                </div>
    
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Net In Hand <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="NetInHand" id="NetInHand" class="form-control" readonly="" >
                    </div>
                    <div class="col-sm-4">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
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
  
 