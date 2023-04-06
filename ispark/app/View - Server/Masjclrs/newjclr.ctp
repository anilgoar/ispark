<?php ?>
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
    
function Location(val){
    var branch =  document.getElementById('BranchName').value;
    if(val=='InHouse'){
        $.post("<?php echo $this->webroot;?>Masjclrs/get_biocode",{branch:branch},function(data){
            $("#BioCode").html(data);
        });
    }
    else{
        $("#BioCode").html('<option value="">Select</option>'); 
        $("#EN").html(''); 
        $("#EN").text('');  
        $("#EMN").val('');
    }
}

function Location1(val,biocode){
    var branch =  document.getElementById('BranchName').value;
    if(val=='InHouse'){
        $.post("<?php echo $this->webroot;?>Masjclrs/get_biocode1",{branch:branch,biocode:biocode},function(data){
            $("#BioCode").html(data);
        });
    }
}

function getEmpName(BioCode){
    var EmpName = BioCode.split("__");
    var branch =  document.getElementById('BranchName').value;
    
    if(BioCode !=""){
        $("#EN").text(EmpName[1].toUpperCase());  
        $("#EMN").val(EmpName[1].toUpperCase());
    }
    else{
        $("#EN").text('');  
        $("#EMN").val('');  
    }
    
    $.post("<?php echo $this->webroot;?>Masjclrs/get_certified_date",{branch:branch,biocode:EmpName[0]},function(data){
        if(data !=""){
            document.getElementById('DOJ').disabled = false;
            $("#DOJ").val(data);
        }
        else{
            document.getElementById('DOJ').disabled = false;
            $("#DOJ").val('<?php echo date_format(date_create($Jclr['NewjclrMaster']['DOJ']),'d-M-Y');?>');
        }
    });
    
    
}

function capitalize(textboxid, str) {
    var res = str.toUpperCase();
    document.getElementById(textboxid).value =  res;
}
    
    
    function empname(val)
    {
        //alert(val);
         $.post("get_name",{vale:val},function(data)
            {$("#Empname").html(data);});
    }
        function getData(val)
        {
            var dept1 = $("#JclrDept").val();;
            $.post("get_package",{desgn:val},function(data)
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
            
            
            $.post("showpack",{pack:val2},function(data)
            {$("#data").html(data);});
           
        }
        
        function getNetData(val2)
        {
            
            
            $.post("Masjclrs/showctc",{desgn:val2},function(data)
            {$("#data12").html(data);});
        }
        
        
        
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
	
 function Design(val)
  {
      $.post("get_design",{val},function(data){
        $('#tower').html(data);});
  }
  function band(val)
  {
      $.post("get_band",{val},function(data){
        $('#band').html(data);});
  }


</script>
        
<script> 
$(document).ready(function(){
    Test('<?php echo $Jclr['NewjclrMaster']['ParentType'];?>');
    Location1('<?php echo $Jclr['NewjclrMaster']['EmpLocation'];?>','<?php echo $Jclr['NewjclrMaster']['BioCode'].'__'.$Jclr['NewjclrMaster']['EmpName'];?>');
    getEmpName('<?php echo $Jclr['NewjclrMaster']['BioCode'].'__'.$Jclr['NewjclrMaster']['EmpName'];?>');
    getSourceName('<?php echo $Jclr['NewjclrMaster']['SourceType'];?>','<?php echo $Jclr['NewjclrMaster']['Source'];?>');
    <?php if($Jclr['NewjclrMaster']['KPI'] !=""){ ?>
    window.scrollTo(0, 1000); 
    <?php } ?>
});
        
function backpage(){ 
    window.location="<?php echo $this->webroot;?>Masjclrs/jclrentry";
} 
    
function Test(val){
    var str ='';
    if(val=='Husband'){
        str +='<input type="text" name="Husband" id="CustomerNameNe" onkeyup="javascript:capitalize(this.id, this.value);" class="form-control" value="<?php echo $Jclr['NewjclrMaster']['Husband'];?>"  placeholder="Husband Name">';
    }
    else if(val=='Father'){
        str +='<input type="text" name="Father" id="CustomerNameNew" onkeyup="javascript:capitalize(this.id, this.value);"  class="form-control" value="<?php echo $Jclr['NewjclrMaster']['Father'];?>"  placeholder="Father Name">';    
    }
    document.getElementById('namerel').innerHTML=str;
}

$(document).ready(function(){
    editcity('<?php echo $Jclr['NewjclrMaster']['City'];?>','<?php echo $Jclr['NewjclrMaster']['StateId'];?>','City');
    editcity('<?php echo $Jclr['NewjclrMaster']['City1'];?>','<?php echo $Jclr['NewjclrMaster']['State1Id'];?>','City1');
    editdept('<?php echo $Jclr['NewjclrMaster']['Desgination'];?>','<?php echo $Jclr['NewjclrMaster']['Dept'];?>','Desgination');
    
    
});

function validate(tab){
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered'); 
    var EmpType=$("#EmpType").val();
    var BranchName=$("#BranchName").val();
    var EmpLocation=$("#EmpLocation").val();
    var BioCode=$("#BioCode").val();
    var Title=$("#Title").val();
    var EmpName=$("#EmpName").val();
    var EMN=$("#EMN").val();
    var radioValue = $("input[name='Sw']:checked").val();
    var CustomerNameNew=$("#CustomerNameNew").val();
    var Gendar=$("#Gendar").val();
    var BloodGruop=$("#BloodGruop").val();
    var MaritalStatus=$("#MaritalStatus").val();
    var Qualification=$("#Qualification").val(); 
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
    var Mobile=$("#Mobile").val();
    var Mobile1=$("#Mobile1").val();
    var EmailId=$("#EmailId").val();
    var OfficeEmailId=$("#OfficeEmailId").val();
    var AdharId=$("#AdharId").val();
    var Dept=$("#Dept").val();
    var Desgination=$("#Desgination").val();
    var Profile=$("#Profile").val();
    var SourceType=$("#SourceType").val();
    var Source=$("#Source").val();
    var CostCenter=$("#CostCenter").val();
    var NomineeName=$("#NomineeName").val();
    var NomineeDob=$("#NomineeDob").val();
    var KPI=$("#KPI").val();
    var AcBank=$("#AcBank").val();
    var AcBranch=$("#AcBranch").val();
    var AccHolder=$("#AccHolder").val();
    var AcNo=$("#AcNo").val();
    var IFSCCode=$("#IFSCCode").val();
    var AccType=$("#AccType").val();
    var CancelledChequeImage=$("#CancelledChequeImage").val();
    var type=$("#type").val();
    var styp=$("#styp").val();
    var pageno=$("#pageno").val();
    var file=$("#file").val();
    var OfferNo=$("#OfferNo").val();
    var mendatorydoc=$("#mendatorydoc").val();
    
    //alert(checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno));return false;
    
    if($.trim(EmpType) ===""){
        $("#EmpType").addClass('bordered'); 
        $("#EmpType").after("<span id='msgerr' class='msger'>Please enter employee type.</span>");
        return false;
    }
    else if($.trim(BranchName) ===""){
        $("#BranchName").addClass('bordered'); 
        $("#BranchName").after("<span id='msgerr' class='msger'>Please enter branch name.</span>");
        return false;
    }
    else if($.trim(EmpLocation) ===""){
        $("#EmpLocation").addClass('bordered'); 
        $("#EmpLocation").after("<span id='msgerr' class='msger'>Please enter employee location.</span>");
        return false;
    }
    else if($.trim(EmpLocation)=="InHouse" && $.trim(BioCode) ===""){
        $("#BioCode").addClass('bordered'); 
        $("#BioCode").after("<span id='msgerr' class='msger'>Please select biocode.</span>");
        return false;
    }
    else if($.trim(Title) ===""){
        $("#Title").addClass('bordered'); 
        $("#Title").after("<span id='msgerr' class='msger'>Please select title.</span>");
        return false;
    }
    else if($.trim(EmpName) ===""){
        $("#EmpName").addClass('bordered'); 
        $("#EmpName").after("<span id='msgerr' class='msger'>Please enter employee name.</span>");
        return false;
    }
    else if($.trim(EmpLocation)=="InHouse" && $.trim(EmpName) !=EMN){
        $("#BioCode").addClass('bordered'); 
        $("#BioCode").after("<span id='msgerr' class='msger'>Please select correct biocode.</span>");
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
    else if(Gendar ===""){
        $("#Gendar").addClass('bordered'); 
        $("#Gendar").after("<span id='msgerr' class='msger'>Please select gendar.</span>");
        return false;
    }
    else if(BloodGruop ===""){
        $("#BloodGruop").addClass('bordered'); 
        $("#BloodGruop").after("<span id='msgerr' class='msger'>Please select blood gruop.</span>");
        return false;
    }
    else if(MaritalStatus ===""){
        $("#MaritalStatus").addClass('bordered'); 
        $("#MaritalStatus").after("<span id='msgerr' class='msger'>Please select marital status.</span>");
        return false;
    }
    else if(Qualification ===""){
        $("#Qualification").addClass('bordered'); 
        $("#Qualification").after("<span id='msgerr' class='msger'>Please select qualification.</span>");
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
    else if(NomineeName ===""){
        $("#NomineeName").addClass('bordered'); 
        $("#NomineeName").after("<span id='msgerr' class='msger'>Please enter nominee name.</span>");
        return false;
    }
    else if(NomineeDob ===""){
        $("#NomineeDob").addClass('bordered'); 
        $("#NomineeDob").after("<span id='msgerr' class='msger'>Please select nominee date of birth.</span>");
        return false;
    }
    else if(NomineeRelation ===""){
        $("#NomineeRelation").addClass('bordered'); 
        $("#NomineeRelation").after("<span id='msgerr' class='msger'>Please enter nominee relation.</span>");
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
    else if(Mobile ===""){
        $("#Mobile").addClass('bordered'); 
        $("#Mobile").after("<span id='msgerr' class='msger'>Please enter mobile no.</span>");
        return false;
    }
    else if(Mobile1 ===""){
        $("#Mobile1").addClass('bordered'); 
        $("#Mobile1").after("<span id='msgerr' class='msger'>Please enter mobile no.</span>");
        return false;
    }
    /*
    else if(EmailId ===""){
        $("#EmailId").addClass('bordered'); 
        $("#EmailId").after("<span id='msgerr' class='msger'>Please enter email id.</span>");
        return false;
    }*/
    else if(EmailId !="" && !filter.test($.trim(EmailId))) {
        $("#EmailId").addClass('bordered'); 
        $("#EmailId").after("<span id='msgerr' class='msger'>Please enter correct email id.</span>");
        return false;
    }/*
    else if(OfficeEmailId ===""){
        $("#OfficeEmailId").addClass('bordered'); 
        $("#OfficeEmailId").after("<span id='msgerr' class='msger'>Please enter office email id.</span>");
        return false;
    }*/
    else if(OfficeEmailId !="" && !filter.test($.trim(OfficeEmailId))) {
        $("#OfficeEmailId").addClass('bordered'); 
        $("#OfficeEmailId").after("<span id='msgerr' class='msger'>Please enter correct office email id.</span>");
        return false;
    }
    else if(AdharId ===""){
        $("#AdharId").addClass('bordered'); 
        $("#AdharId").after("<span id='msgerr' class='msger'>Please enter adhar id.</span>");
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
    else if(Profile ===""){
        $("#Profile").addClass('bordered'); 
        $("#Profile").after("<span id='msgerr' class='msger'>Please select profile.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' class='msger'>Please select cost center.</span>");
        return false;
    }
    else if(SourceType ===""){
        $("#SourceType").addClass('bordered'); 
        $("#SourceType").after("<span id='msgerr' class='msger'>Please select source type.</span>");
        return false;
    }
    else if(Source ===""){
        $("#Source").addClass('bordered'); 
        $("#Source").after("<span id='msgerr' class='msger'>Please select source.</span>");
        return false;
    }
    else if(KPI ===""){
        $("#KPI").addClass('bordered'); 
        $("#KPI").after("<span id='msgerr' class='msger'>Please select kpi.</span>");
        return false;
    }
    else if(tab =="tab2"){
        $("#JCLRFORM").submit();
    }
    else if(mendatorydoc > 0){
    //else if(checkEmpDoc(OfferNo,EmpType,Desgination,type) ==""){
        if(type ===""){
            $("#type").addClass('bordered'); 
            $("#type").after("<span id='msgerr' class='msger'>Please upload all mendatory document.</span>");
            return false;
        }
        else if(styp ===""){
            $("#styp").addClass('bordered'); 
            $("#styp").after("<span id='msgerr' class='msger'>Please select doc name.</span>");
            return false;
        }
        else if(file ===""){
            $("#file").addClass('bordered'); 
            $("#file").after("<span id='msgerr' class='msger'>Please upload file.</span>");
            return false;
        }
        else if(checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno) !=""){
            $("#type").after("<span id='msgerr' class='msger'>This document already uploaded.</span>");
            return false;
        }
        $("#TabName").val('tab2');
        $("#JCLRFORM").submit();
    }
    else if(tab =="tab3"){
        $("#TabName").val('tab3');
        $("#JCLRFORM").submit();
    }
    else if(tab =="tab4"){
        $("#TabName").val('tab4');
        $("#JCLRFORM").submit();
    }
    else{
        $("#JCLRFORM").submit();
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

function checkEmpDoc(OfferNo,EmpType,Desgination,type){ 
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>Masjclrs/checkdoc",async: false,dataType: 'json',data: {OfferNo:OfferNo,EmpType:EmpType,Desgination:Desgination,type:type},done: function(response) {return response;}}).responseText;	
    return posts;
}

function checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno){ 
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>Masjclrs/checkdoc1",async: false,dataType: 'json',data: {OfferNo:OfferNo,EmpType:EmpType,Desgination:Desgination,type:type,pageno:pageno},done: function(response) {return response;}}).responseText;	
    return posts;
}

function getcity(state,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getcity",{'state':$.trim(state)},function(data){
        $("#"+id).html(data);
    });
}

function editcity(city,state,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/editcity",{'city':$.trim(city),'state':$.trim(state)},function(data){
        $("#"+id).html(data);
    });
}

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

function getdesg(Designation,id){
    $.post("<?php echo $this->webroot;?>Masjclrs/getdesg",{'Designation':$.trim(Designation)},function(data){
        $("#"+id).html(data);
    });
}

function getSourceName(SourceType,SourceName){
    var BranchName =  document.getElementById('BranchName').value;
    $.post("<?php echo $this->webroot;?>Masjclrs/getsourcename",{'SourceType':SourceType,BranchName:BranchName,SourceName:SourceName},function(data){
        $("#SourName").html(data);
    });
}

function checkPhoto(target) { 
    if(target.files[0].type.indexOf("image") == -1) {
        document.getElementById("file").value = "";
        alert('File not supported');
        return false;
    }
    if(target.files[0].size > 230400) {
        alert('Maximum size of document to upload is 225 kb.');
        document.getElementById("file").value = "";
        return false;
    }
    return true;
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
                    <span>EMPLOYEE DETAILS  <?php echo $TabName;?></span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            
            <div class="box-content box-con" >
                
                <?php echo $this->Form->create('Masjclrs',array('action'=>'newjclr','class'=>'form-horizontal','id'=>'JCLRFORM','enctype'=>'multipart/form-data')); ?>
                <input type="hidden" name="MasJclrsId" id="MasJclrsId" value="<?php echo $Jclr['NewjclrMaster']['id'];?>" >
                <input type="hidden" name="OfferNo" id="OfferNo" value="<?php echo $Jclr['NewjclrMaster']['OfferNo'];?>" >
                <input type="hidden" name="TabName" id="TabName">
                
               
               
                <div class="form-group has-info has-feedback">
                     <label class="col-sm-2 control-label">Employee Type <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpType', array('label'=>false,'class'=>'form-control','id'=>'EmpType','value'=>$Jclr['NewjclrMaster']['EmpType'],'readonly'=>true)); ?>
                    </div>

                    <label class="col-sm-3 control-label">Barnch Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('BranchName', array('label'=>false,'class'=>'form-control','id'=>'BranchName','value'=>$Jclr['NewjclrMaster']['BranchName'],'readonly'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Employee Location <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpLocation',array('label'=>false,'class'=>'form-control','id'=>'EmpLocation','value'=>$Jclr['NewjclrMaster']['EmpLocation'],'onChange'=>'Location(this.value)','options'=>array('InHouse'=>'InHouse','OnSite'=>'OnSite','Field'=>'Field'),'empty'=>'Select')); ?>
                    </div>

                    <label class="col-sm-3 control-label">Biometric Code <span class="req">*</span></label>
                    <div class="col-sm-3" >
                        <select name="data[Masjclrs][BioCode]" id="BioCode" class="form-control" onchange="getEmpName(this.value);" >
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Title <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php   echo $this->Form->input('Title', array('label'=>false,'class'=>'form-control','id'=>'Title','value'=>$Jclr['NewjclrMaster']['Title'],'options'=>array('MR.'=>'MR.','MS.'=>'MS.','MRS.'=>'MRS.'))); ?>
                    </div>
                    
                    <label class="col-sm-3 control-label">Name <span class="req">*</span></label>
                    <input type="hidden" id="EMN" >
                    <div class="col-sm-3" id="EN" > 
                    </div>
                    
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Emp Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmpName', array('label'=>false,'class'=>'form-control','id'=>'EmpName','onkeyup'=>'javascript:capitalize(this.id, this.value);','value'=>$Jclr['NewjclrMaster']['EmpName'])); ?>
                    </div>

                    <label class="col-sm-3 control-label">
                        <input type="radio" <?php if($Jclr['NewjclrMaster']['ParentType'] =="Father"){echo "checked";}?>  name="Sw" value="Father" onclick='Test(this.value);'  checked/> <strong>Father</strong>
                        <input type="radio" <?php if($Jclr['NewjclrMaster']['ParentType'] =="Husband"){echo "checked";}?> name="Sw" value="Husband" onclick='Test(this.value);'  /> <strong>Husband</strong> <span class="req">*</span>
                    </label>

                    <div class="col-sm-3" id="namerel"></div>
                </div>

                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Gendar <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('Gendar',array('label'=>false,'class'=>'form-control','id'=>'Gendar','value'=>$Jclr['NewjclrMaster']['Gendar'],'options'=>array('MALE'=>'MALE','FEMALE'=>'FEMALE')));?>  
                    </div>
                    
                    <label class="col-sm-3 control-label">Blood Group <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('BloodGruop', array('label'=>false,'class'=>'form-control','id'=>'BloodGruop','value'=>$Jclr['NewjclrMaster']['BloodGruop'],'options'=>array('A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','O+'=>'O-','AB+'=>'AB+','AB-'=>'AB-','NA'=>'NA'),'empty'=>'Select')); ?> 
                    </div>
                </div>
                
                
                
                <div class="form-group has-info has-feedback">
                   <label class="col-sm-2 control-label">Marital Status <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('MaritalStatus', array('label'=>false,'class'=>'form-control','id'=>'MaritalStatus','value'=>$Jclr['NewjclrMaster']['MaritalStatus'],'options'=>array('SINGLE'=>'SINGLE','MARRIED'=>'MARRIED','WIDOW'=>'WIDOW','DIVORCE'=>'DIVORCE'),'empty'=>'Select')); ?>
                    </div> 
                   
                    <label class="col-sm-3 control-label">Qualification <span class="req">*</span></label>
                    <div class="col-sm-3">                     
                        <?php echo $this->Form->input('Qualification', array('label'=>false,'class'=>'form-control','value'=>$Jclr['NewjclrMaster']['Qualification'],'id'=>'Qualification','options'=>array('UNDER GRADUATE'=>'UNDER GRADUATE','GRADUATE'=>'GRADUATE','POST GRADUATE'=>'POST GRADUATE','MASTER DEGREE'=>'MASTER DEGREE','ENGINEERING'=>'ENGINEERING'),'empty'=>'Select')); ?>
                    </div> 
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Date of Birth <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOB" id="DOB" class="form-control datepickers" value="<?php echo date_format(date_create($Jclr['NewjclrMaster']['DOB']),'d-M-Y');?>" autocomplete="off" readonly="" >
                    </div>

                    <label class="col-sm-3 control-label">Date of Joining <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="DOJ" id="DOJ" class="form-control datepickers" value="<?php echo date_format(date_create($Jclr['NewjclrMaster']['DOJ']),'d-M-Y');?>" autocomplete="off" readonly="" onchange="validatedob()" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Nominee Name <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('NomineeName', array('label'=>false,'class'=>'form-control','id'=>'NomineeName','onkeyup'=>'javascript:capitalize(this.id, this.value);','value'=>$Jclr['NewjclrMaster']['NomineeName'],'autocomplete'=>'off')); ?>
                    </div>
                   
                    <label class="col-sm-3 control-label">Nominee Date Of Birth<span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="NomineeDob" id="NomineeDob" autocomplete="off" class="form-control datepickers" value="<?php if($Jclr['NewjclrMaster']['NomineeDob'] !=""){ echo date_format(date_create($Jclr['NewjclrMaster']['NomineeDob']),'d-M-Y');}?>" autocomplete="off" readonly="" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Nominee Relation <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('NomineeRelation', array('label'=>false,'class'=>'form-control','id'=>'NomineeRelation','value'=>$Jclr['NewjclrMaster']['NomineeRelation'],'options'=>array('Father'=>'Father','Mother'=>'Mother','Brother'=>'Brother','Sister'=>'Sister','Son'=>'Son','Daughter'=>'Daughter','Husband'=>'Husband','Wife'=>'Wife'),'empty'=>'Select','autocomplete'=>'off')); ?>
                    </div>
                </div>

                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Permanent Address <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('Adrress1', array('type'=>'textarea','id'=>'Adrress1','label'=>false,'value'=>$Jclr['NewjclrMaster']['Adrress1'],'class'=>'form-control','style'=>'height:60px;')); ?>
                    </div>
                    
                    <label class="col-sm-3 control-label">Present Address <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('Adrress2', array('type'=>'textarea','id'=>'Adrress2','label'=>false,'value'=>$Jclr['NewjclrMaster']['Adrress2'],'class'=>'form-control','style'=>'height:60px;')); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">State <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="State" id="State" class="form-control" onchange="getcity(this.value,'City')"  >
                            <option value="" >Select</option>
                            <?php foreach($state as $key=>$val){?>
                            <option <?php if($Jclr['NewjclrMaster']['StateId'] ==$key){echo "selected='selected'";}?> value="<?php echo $key;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-3 control-label">State <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="State1" id="State1" class="form-control" onchange="getcity(this.value,'City1')"   >
                            <option value="" >Select</option>
                            <?php foreach($state as $key=>$val){?>
                            <option <?php if($Jclr['NewjclrMaster']['State1Id'] ==$key){echo "selected='selected'";}?>  value="<?php echo $key;?>" ><?php echo $val;?></option>
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
                    
                    <label class="col-sm-3 control-label">City <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="City1" id="City1" class="form-control"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">PinCode <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="PinCode" id="PinCode" class="form-control" autocomplete="off" value="<?php echo $Jclr['NewjclrMaster']['PinCode'];?>"  onKeyPress="return checkNumber(this.value,event)" maxlength="6" >
                    </div>
                    
                    <label class="col-sm-3 control-label">PinCode <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" name="PinCode1" id="PinCode1" autocomplete="off" class="form-control" value="<?php echo $Jclr['NewjclrMaster']['PinCode1'];?>"  onKeyPress="return checkNumber(this.value,event)" maxlength="6" >
                    </div>
                </div>
                     
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Mobile No <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('Mobile', array('label'=>false,'class'=>'form-control','id'=>'Mobile','autocomplete'=>'off','value'=>$Jclr['NewjclrMaster']['Mobile'],'maxlength'=>'10','minlenth'=>'10','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
                    </div>
                    <label class="col-sm-3 control-label">Mobile No <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('Mobile1', array('label'=>false,'class'=>'form-control','id'=>'Mobile1','autocomplete'=>'off','value'=>$Jclr['NewjclrMaster']['Mobile1'],'maxlength'=>'10','minlenth'=>'10','onKeyPress'=>'return checkNumber(this.value,event)')); ?>   
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback"><label class="col-sm-2 control-label">Land Line No</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('LandLine', array('label'=>false,'class'=>'form-control','id'=>'LandLine','autocomplete'=>'off','maxlength'=>'8','value'=>$Jclr['NewjclrMaster']['LandLine'],'onKeyPress'=>'return checkNumber(this.value,event)')); ?>
                    </div>
                    
                    <label class="col-sm-3 control-label">Land Line No</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('LandLine1', array('label'=>false,'class'=>'form-control','id'=>'LandLine1','autocomplete'=>'off','maxlength'=>'8','value'=>$Jclr['NewjclrMaster']['LandLine1'],'onKeyPress'=>'return checkNumber(this.value,event)')); ?>   
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Email Id <span class="req"></span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('EmailId', array('label'=>false,'class'=>'form-control','autocomplete'=>'off','id'=>'EmailId','value'=>$Jclr['NewjclrMaster']['EmailId'])); ?>   
                    </div>
                    
                    <label class="col-sm-3 control-label">Official Email Id <span class="req"></span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('OfficeEmailId', array('label'=>false,'class'=>'form-control','autocomplete'=>'off','id'=>'OfficeEmailId','value'=>$Jclr['NewjclrMaster']['OfficeEmailId'])); ?>  
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Passport No</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('PassportNo', array('label'=>false,'class'=>'form-control','value'=>$Jclr['NewjclrMaster']['PassportNo'],'autocomplete'=>'off','id'=>'PassportNo')); ?>   
                    </div>
                    
                    <label class="col-sm-3 control-label">PanNo</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('PanNo', array('label'=>false,'class'=>'form-control','value'=>$Jclr['NewjclrMaster']['PanNo'],'autocomplete'=>'off','id'=>'PanNo')); ?>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Adhar No <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('AdharId', array('label'=>false,'class'=>'form-control','id'=>'AdharId','value'=>$Jclr['NewjclrMaster']['AdharId'],'autocomplete'=>'off','maxlength'=>'12','onKeyPress'=>'return checkNumber(this.value,event)')); ?>
                    </div>
                </div>
                
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Department <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Dept" id="Dept" class="form-control" onchange="getdept(this.value,'Desgination')"   >
                            <option value="" >Select</option>
                            <?php foreach($dep as $val){?>
                            <option <?php if($Jclr['NewjclrMaster']['Dept'] ==$val){echo "selected='selected'";}?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-3 control-label">Desgination <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Desgination" id="Desgination" class="form-control" onchange="getdesg(this.value,'Band')"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Profile <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select name="Profile" id="Profile" class="form-control"  >
                            <option value="" >Select</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="VOICE"){echo "selected='selected'";}?> value="VOICE">VOICE</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="NON-VOICE"){echo "selected='selected'";}?> value="NON-VOICE">NON-VOICE</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="TRANSACTIONAL PROCESSING"){echo "selected='selected'";}?> value="TRANSACTIONAL PROCESSING">TRANSACTIONAL PROCESSING</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="BUSINESS DEVELOPMENT"){echo "selected='selected'";}?> value="BUSINESS DEVELOPMENT">BUSINESS DEVELOPMENT</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="SOFTWARE ENGINEER"){echo "selected='selected'";}?> value="SOFTWARE ENGINEER">SOFTWARE ENGINEER</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="HARDWARE ENGINEER"){echo "selected='selected'";}?> value="HARDWARE ENGINEER">HARDWARE ENGINEER</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="RECRUITMENT"){echo "selected='selected'";}?> value="RECRUITMENT">RECRUITMENT</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="TRAINING AND DEVELOPMENT"){echo "selected='selected'";}?> value="TRAINING AND DEVELOPMENT">TRAINING AND DEVELOPMENT</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="HR OPERATIONS"){echo "selected='selected'";}?> value="HR OPERATIONS">HR OPERATIONS</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="HR EMPLOYEE RELATION"){echo "selected='selected'";}?> value="HR EMPLOYEE RELATION">HR EMPLOYEE RELATION</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="HR GENERALISTIC"){echo "selected='selected'";}?> value="HR GENERALISTIC">HR GENERALISTIC</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="ACCOUNTS"){echo "selected='selected'";}?> value="ACCOUNTS">ACCOUNTS</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="FINANCE"){echo "selected='selected'";}?> value="FINANCE">FINANCE</option>
                            <option <?php if($Jclr['NewjclrMaster']['Profile'] =="FACILITY MGMT."){echo "selected='selected'";}?> value="FACILITY MGMT.">FACILITY MGMT.</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-3 control-label">Cost Center <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('CostCenter', array('label'=>false,'class'=>'form-control','id'=>'CostCenter','value'=>$Jclr['NewjclrMaster']['CostCenter'],'options'=>$tower1,'empty'=>'Select')); ?>
                    </div>

                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Source Type <span class="req">*</span></label>
                    <div class="col-sm-3">
                        <select class="form-control" autocomplete="off" onchange="getSourceName(this.value,'');"  name="SourceType" id="SourceType">
                            <option value="">Select</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="Source"){echo "selected='selected'";}?> value="Source">Source</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="CONSULTANT"){echo "selected='selected'";}?> value="CONSULTANT">CONSULTANT</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="NAUKRI.COM"){echo "selected='selected'";}?> value="NAUKRI.COM">NAUKRI.COM</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="EMPLOYEE REFERRAL"){echo "selected='selected'";}?> value="EMPLOYEE REFERRAL">EMPLOYEE REFERRAL</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="WALK IN"){echo "selected='selected'";}?> value="WALK IN">WALK IN</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="ADVERTISEMENT"){echo "selected='selected'";}?> value="ADVERTISEMENT">ADVERTISEMENT</option>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] =="OTHERS"){echo "selected='selected'";}?> value="OTHERS">OTHERS</option>
                            <!--
                            <?php foreach($source as $val){?>
                            <option <?php if($Jclr['NewjclrMaster']['SourceType'] ==$val){echo "selected='selected'";}?> value="<?php echo $val;?>" ><?php echo $val;?></option>
                            <?php }?>
                            -->
                        </select>
                    </div>
                    
                    <label class="col-sm-3 control-label">Source <span class="req">*</span></label>
                    <div class="col-sm-3" id="SourName" >
                        <select class="form-control" autocomplete="off" name="Source" id="Source">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">KPI <span class="req"></span></label>
                    <div class="col-sm-3">
                        <select name="KPI" id="KPI" class="form-control"  >
                            <option value="" >Select</option>
                            <option <?php if($Jclr['NewjclrMaster']['KPI'] =="146"){echo "selected='selected'";}?> value="146">HR3</option>
                            <option <?php if($Jclr['NewjclrMaster']['KPI'] =="147"){echo "selected='selected'";}?> value="147">HR2</option>
                            <option <?php if($Jclr['NewjclrMaster']['KPI'] =="148"){echo "selected='selected'";}?> value="148">HR1</option>
                            <option <?php if($Jclr['NewjclrMaster']['KPI'] =="149"){echo "selected='selected'";}?> value="149">F1</option>
                            <option <?php if($Jclr['NewjclrMaster']['KPI'] =="150"){echo "selected='selected'";}?> value="150">F5</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-11">
                        <input type='button' class="btn btn-info btn-new pull-right" value="Save Details" onclick="validate('tab2');" style="margin-left:5px;" >
                    </div>
                </div>
                
                
                
                <div class="box-header"  >
                    <div class="box-name">
                        <span>Documentation Details</span>
                    </div>
                </div>
                <span><?php echo $this->Session->flash();?></span>
                <div class="form-group" style="margin-top:30px;" >
                    <label class="col-sm-2 control-label">Doc Type<span class="req">*</span></label> 
                    <div class="col-sm-3"> 
                        <input type="hidden" name="documentDone" id="documentDone" value="<?php echo $Jclr['NewjclrMaster']['documentDone'];?>" >
                        <select name="type" id="type" class="form-control" onchange="return checkread(this.value);">
                            <option value="">Select</option>
                            <?php foreach ($Data1 as $d){?>
                                <option value="<?php echo $d['masdoc_option']['Doctype']; ?>"><?php echo $d['masdoc_option']['Doctype']; ?></option>
                            <?php } ?>
                        </select>
                    </div> 

                    <label class="col-sm-2 control-label">Doc Name<span class="req">*</span></label> 
                    <div class="col-sm-3">
                        <div id="mm">
                            <select name="styp" id="styp" class="form-control" >
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                     <label class="col-sm-2 control-label">Page No</label> 
                    <div id="typequery">
                        <div class="col-sm-3">
                            <select name="pageno" id="pageno" class="form-control" id="pageno" >
                                <option value="">Select</option>
                            </select>
                        </div> 
                    </div> 
                   

                    <label class="col-sm-2 control-label">File<span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php   echo $this->Form->input('file', array('label'=>false,'type' => 'file','id'=>'file','accept'=>'image/jpg','onchange'=>'checkPhoto(this)'));?>
                    </div>
                </div>
                
                
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Box No</label>
                    <div class="col-sm-3">
                        <input type="text" name="BoxNo" id="BoxNo"  class="form-control" value="">
                    </div>
                </div>
                
                <?php if(!empty($mendatorydoc)){?>
                <div class="form-group ">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-4">
                        <input type="hidden" id="mendatorydoc" value="<?php echo count($mendatorydoc);?>"  >
                        <table  class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
                            <thead>
                                <tr>                	
                                    <th >DOCUMENT</th>
                                    <th style="text-align:center;width:30px;">MENDATORY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mendatorydoc as $key=>$val){ ?>
                                <tr>
                                    <td style="background-color:red;color:white;text-align:left;" ><strong><?php echo $key;?></strong></td><td style="background-color:red;color:white;text-align:center;" ><strong><?php echo $val;?></strong></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }?>
                
                <?php if(!empty($find)){?>
                <div class="form-group ">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-8">
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
                        <thead>
                	<tr>                	
                        <th style="text-align:center;width:" >SNo.</th>
                    	<th style="text-align:center;width:">Offer latter No</th>
                    	<th style="text-align:center;width:">Doc Type</th>
                    	<th style="text-align:center;width:">Doc name</th>
                    	<th style="text-align:center;width:">Box No</th>
                        <th style="text-align:center;width:" >View</th>
                        <th style="text-align:center;width:">Delete</th>
                        
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
             
					 foreach($find as $post):
                    //print_r($Jclr['Jclr']['AccountApprove']);die;
                                             $imagepath=$show.$post['Masdocfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td style='text-align:center;' >".$i++."</td>";
						echo "<td style='text-align:center;' align=\"center\">".$post['Masdocfile']['OfferNo']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['DocType']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['DocName'].'_'.$post['Masdocfile']['fileno']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['BoxNo']."</td>";
						echo "<td style='text-align:center;' onclick=\"viewimage('$imagepath')\"><i style='font-size:20px;cursor: pointer;' title='view' class='material-icons'>pageview</i></td>";
                                               if($Jclr['Jclr']['AccountApprove']!= 0 && $post['Docfile']['DocType']=='PassBook' ) { if($Jclr['Jclr']['AccountApprove']== 1){echo "<td >"."Approved"."</td>";}else{echo "<td >"."DisAproved"."</td>";} }
                                              else{  echo "<td style='text-align:center;' onclick=\"deleteimage('$imagepath','{$post['Masdocfile']['OfferNo']}','{$post['Masdocfile']['filename']}','{$Jclr['NewjclrMaster']['id']}')\"><i  style='font-size:20px;cursor: pointer;' title='Delete' class='material-icons'>delete_forever</i></td>"; }
					 echo "</tr>";
					 endforeach;
				?>
                </tbody>
				</table>
                    </div>
                </div>
                
                <?php }?>
                
                
                
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-9">
                        <input type='button' class="btn btn-info btn-new pull-right" value="Save Document" onclick="validate('tab3');" style="margin-left:5px;" >
                    </div>
                </div>
                
                
                
               
                
                <div class="box-header"  >
                    <div class="box-name">
                        <span>Bank Details</span>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top:30px;" >
                    <label class="col-sm-2 control-label">Bank Name <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="AcBank" id="AcBank" value="<?php echo $Jclr['NewjclrMaster']['AcBank'];?>"   class="form-control" >
                    </div>
                    
                    <label class="col-sm-3 control-label">Branch Name <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="AcBranch" id="AcBranch" value="<?php echo $Jclr['NewjclrMaster']['AcBranch'];?>"  class="form-control" >
                    </div>

                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Account Holder Name <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="AccHolder" id="AccHolder" value="<?php echo $Jclr['NewjclrMaster']['AccHolder'];?>"  class="form-control" >
                    </div>
                    
                    <label class="col-sm-3 control-label">Account No <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="AcNo" id="AcNo" value="<?php echo $Jclr['NewjclrMaster']['AcNo'];?>"  onkeypress="return checkNumber(this.value,event);" class="form-control" >
                    </div>

                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">IFSC Code <span class="req"></span></label>
                    <div class="col-sm-3">
                        <input type="text" name="IFSCCode" id="IFSCCode" value="<?php echo $Jclr['NewjclrMaster']['IFSCCode'];?>" class="form-control" >
                    </div>
                    
                    <label class="col-sm-3 control-label">Account Type <span class="req"></span></label>
                    <div class="col-sm-3">
                        <select name="AccType" id="AccType" class="form-control"  >
                            <option value="">Select</option>
                            <option <?php if($Jclr['NewjclrMaster']['AccType'] =="SAVING"){echo "selected='selected'";}?> value="SAVING" >SAVING</option>
                            <option <?php if($Jclr['NewjclrMaster']['AccType'] =="CURRENT"){echo "selected='selected'";}?> value="CURRENT">CURRENT</option>
                        </select>
                    </div>

                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label">Cancelled Cheque Image <span class="req"></span></label>
                    <div class="col-sm-3">
                        <?php   echo $this->Form->input('CancelledChequeImage', array('label'=>false,'type' => 'file','id'=>'CancelledChequeImage','accept'=>'image/jpg'));?>
                    </div>
                </div>
                
                
                
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-11">
                        <input type='button' class="btn btn-info btn-new pull-right" value="Submit" onclick="validate('tab4');" style="margin-left:5px;" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-3" id="mm" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
function statustype(val){    
          
             var fileno = 0;
             if(val == 'Code Of Conduct'){
                   fileno= 2;
          
      }
      else if(val == 'Epf Declaration Form'){
                   fileno= 3;
          
      }
       else if(val == 'Contrat Form'){
                   fileno= 7;
          
      }
      else if(val == 'Resume'){
           fileno= 4;
                  
          
      }
      else
      {
         fileno = 0; 
      }
           
            $.post("get_status_data",{types:val,fileno:fileno},function(data)
            {
                  
                $("#mm").html(data);});

        }    
    
    
function checkread(val){
        var fileno = 0;
        statustype(val);
        if(val == 'Code Of Conduct'){
             fileno= 2;
              document.getElementById("pageno").disabled = false;

  }
  else if(val == 'Epf Declaration Form'){
        fileno= 3;
               document.getElementById("pageno").disabled = false;

  }
   else if(val == 'Contrat Form'){
       fileno= 7;
              document.getElementById("pageno").disabled = false;


  }
   else if(val == 'Resume'){
       fileno= 4;
              document.getElementById("pageno").disabled = false;

  }
  else
  {
     document.getElementById("pageno").disabled = true;
      document.getElementById("pageno").value = "";
  }



       if(fileno!=0)
       {
        var i =0;
        var order ='';
        order += "<div class='col-sm-3'><select name='pageno' class = 'form-control' required='' id = 'pageno'><option value=''>Select</option>";
        for(i=1;i<=fileno;i++)
        {



           order +="<option value='"+i+"'>"+i+"</option>";



    }
 order += "</select>";
        order +="</div>";
    document.getElementById("typequery").innerHTML=order;
    }

    }
    
         

function deleteimage(val,emp,file,MasJclrId){
    if(confirm('Are you sure you want to delete this record?')){
        window.location='<?php echo $this->webroot;?>Masjclrs/deletefile?path='+val+'&EmpCode='+emp+'&filename='+file+'&MasJclrId='+MasJclrId;
    }
}
        
function viewimage(val){   
    newwindow= window.open('<?php echo $this->webroot;?>'+val,'Image','height=500,width=600');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}
</script>