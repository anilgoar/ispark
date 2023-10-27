<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#FromDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#ToDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>

<script>
function search_employees(EmpName){
    var BranchName=$("#BranchName").val();
    $("#msgerr").remove();  
    if(BranchName ===""){
        $("#searchEmp" ).val('');
        $("#BranchName" ).after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>LeaveApproval/get_emp",{'EmpName':$.trim(EmpName),'BranchName':BranchName}, function(data) {
            $("#EmpNameCode" ).html(data);
        });
    }
}

function getBranch(branch){
    if(branch !=""){
        $("#msgerr").remove(); 
        $("#searchEmp" ).val('');
    }
}

function validateOdApply(){
    
    //alert($("#Balel").val());return false;
    
    // $("#EL").val() > $("#Balel").val()

    $("#msgerr").remove();
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    var BranchName=$("#BranchName").val();
    var EmpNameCode=$("#EmpNameCode").val();
    var LeaveType = $("input:radio[name=LeaveType]:checked").val()
    var LeaveFor = $("input:radio[name=LeaveFor]:checked").val()
    var EmpNameCode=$("#EmpNameCode").val();
    var Purpose=$("#Purpose").val();
    var AddDurLeave=$("#AddDurLeave").val();
    var ContactNo=$("#ContactNo").val();
    
    var FromDate=$("#FromDate").val();
    var ToDate=$("#ToDate").val();

    if(BranchName ===""){
        $("#CL").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpNameCode ===null){
        $("#CL").focus();
        $("#EmpNameCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp code.</span>");
        return false;
    }
    else if(EmpNameCode ===""){
        $("#CL").focus();
        $("#EmpNameCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp code.</span>");
        return false;
    }
    else if(checkDate(FromDate,ToDate) ==''){
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date.</span>");
        return false;
    }
    else if(existAttendance(BranchName,EmpNameCode,FromDate,ToDate) ==''){
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>This attendance date not exist in database.</span>");
        return false;
    }
    else if(LeaveType ===undefined){
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please select type of leave.</span>");
        return false;
    }
    else if(LeaveType ==="CL" && $("#CL").val() ===""){
        $("#CL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter CL.</span>");
        return false;
    }
    else if(LeaveType ==="CL" && !RE.test($("#CL").val())){
        $("#CL").focus();
        $("#CL").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use number/decimal in CL.</span>");
        return false;
    }
    else if(LeaveType ==="CL" && parseInt($("#CL").val()) > 2){
        $("#CL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 2 cl.</span>");
        return false;
    }
    else if(LeaveType ==="CL" && parseInt($("#CL").val()) > parseInt($("#Balcl").val()) ){
        $("#CL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter leave according your balance.</span>");
        return false;
    }
    else if(LeaveType ==="ML" && $("#ML").val() ===""){
        $("#ML").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter ML.</span>");
        return false;
    }
    else if(LeaveType ==="ML" && !RE.test($("#ML").val())){
        $("#ML").focus();
        $("#ML").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use number/decimal in ML.</span>");
        return false;
    }
    else if(LeaveType ==="ML" && parseInt($("#ML").val()) > 2){
        $("#ML").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 2 ml.</span>");
        return false;
    }
    else if(LeaveType ==="ML" && parseInt($("#ML").val()) > $("#Balml").val() ){
        $("#ML").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter leave according your balance.</span>");
        return false;
    }
    else if(LeaveType ==="LWP" && $("#LWP").val() ===""){
        $("#LWP").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter LWP.</span>");
        return false;
    }
    else if(LeaveType ==="LWP" && !RE.test($("#LWP").val())){
        $("#LWP").focus();
        $("#LWP").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use only number/decimal in LWP.</span>");
        return false;
    }
    else if(LeaveType ==="LWP" && parseInt($("#LWP").val()) > 10){
        $("#LWP").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 10 lwp.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && $("#EL").val() ===""){
        $("#EL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter EL.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && !RE.test($("#EL").val())){
        $("#EL").focus();
        $("#EL").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use only number/decimal in EL.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && parseInt($("#EL").val()) > 18){
        $("#EL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 18 el.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && parseInt($("#EL").val()) < 3){
        $("#EL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Required apply minimum 3 el.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && parseInt($("#EL").val()) > parseInt($("#Balel").val()) ){
        $("#EL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter leave according your balance.</span>");
        return false;
    }
    else if(LeaveType ==="EL" && validateEl(BranchName,EmpNameCode) !=''){
        $("#EL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You have already used two time in this year.</span>");
        return false;
    }
    else if(LeaveType ==="PTRL" && $("#PTRL").val() ===""){
        $("#PTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter PTRL.</span>");
        return false;
    }
    else if(LeaveType ==="PTRL" && !RE.test($("#PTRL").val())){
        $("#PTRL").focus();
        $("#PTRL").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use only number/decimal in PTRL.</span>");
        return false;
    }
    else if(LeaveType ==="PTRL" && parseInt($("#PTRL").val()) > 4){
        $("#PTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 4 ptrl.</span>");
        return false;
    }
    else if(LeaveType ==="PTRL" && parseInt($("#PTRL").val()) > parseInt($("#Balpt").val()) ){
        $("#PTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter leave according your balance.</span>");
        return false;
    }
    else if(LeaveType ==="MTRL" && $("#MTRL").val() ===""){
        $("#MTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter MTRL.</span>");
        return false;
    }
    else if(LeaveType ==="MTRL" && !RE.test($("#MTRL").val())){
        $("#MTRL").focus();
        $("#MTRL").val('');
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Use only number/decimal in MTRL.</span>");
        return false;
    }
    else if(LeaveType ==="MTRL" && parseInt($("#MTRL").val()) > 180){
        $("#MTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>You can apply maximum 180 mtrl.</span>");
        return false;
    }
    else if(LeaveType ==="MTRL" && parseInt($("#MTRL").val()) > parseInt($("#Balmt").val()) ){
        $("#MTRL").focus();
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Please enter leave according your balance.</span>");
        return false;
    }
    else if(Purpose ===""){
        $("#Purpose").focus();
        $("#Purpose").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(AddDurLeave ===""){
        $("#AddDurLeave").focus();
        $("#AddDurLeave").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(ContactNo ===""){
        $("#ContactNo").focus();
        $("#ContactNo").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(ContactNo.length !=10){
        $("#ContactNo").focus();
        $("#ContactNo").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter correct mobile number.</span>");
        return false;
    }
    else if(validateDateType(LeaveType,LeaveFor,FromDate,ToDate) !=''){
        $("#Purpose").before("<span id='msgerr' style='color:red;font-size:11px;'>Leave day and date not match.</span>");
        return false;
    }
    else{
        return true;
    }
}


function validateEl(BranchName,EmpNameCode){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>LeaveApproval/validate_el",async: false,dataType: 'json',data: {BranchName:BranchName,EmpNameCode:EmpNameCode},done: function(response) {return response;}}).responseText;	
    return posts;
}


function existAttendance(BranchName,EmpNameCode,FromDate,ToDate){
	var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>LeaveApproval/exist_attendance",async: false,dataType: 'json',data: {BranchName:BranchName,EmpNameCode:EmpNameCode,FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
	return posts;
}

function checkDate(FromDate,ToDate){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>LeaveApproval/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
    return posts;
}

function validateDateType(LeaveType,LeaveFor,FromDate,ToDate){
    
    if(LeaveType ==="CL"){
        var LeaveNo=$("#CL").val();
    }
    else if(LeaveType ==="ML"){
        var LeaveNo=$("#ML").val();
    }
    else if(LeaveType ==="LWP"){
        var LeaveNo=$("#LWP").val();
    }
    else if(LeaveType ==="EL"){
        var LeaveNo=$("#EL").val();
    }
    else if(LeaveType ==="PTRL"){
        var LeaveNo=$("#PTRL").val();
    }
    else if(LeaveType ==="MTRL"){
        var LeaveNo=$("#MTRL").val();
    }
    
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>LeaveApproval/validate_leave_with_date",async: false,dataType: 'json',data: {LeaveType:LeaveNo,LeaveFor:LeaveFor,FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
    return posts;
}

function getEmployeesDetails(EmpCode){
    $("#LeaveTable").hide();
    var BranchName=$("#BranchName").val();
    
    $.post("<?php echo $this->webroot;?>LeaveApproval/get_gender",{'EmpCode':EmpCode,'BranchName':BranchName}, function(data) {
        $("#MtrlPtrl").html(data);
    });
    
    $.post("<?php echo $this->webroot;?>LeaveApproval/get_leave_details",{'EmpCode':EmpCode,'BranchName':BranchName}, function(data) {
        if(data !=""){
            $("#LeaveTable").show();
            $("#LeaveDetails").html(data);
        }
        else{
            $("#LeaveTable").hide();
        }
    });
}

function getEmployeesDetails_by_month(){
    $("#LeaveTable").hide();
    var  EmpCode = $("#EmpNameCode").val();
    var  FromDate= $("#FromDate").val();
    var  ToDate = $("#ToDate").val();
    var BranchName=$("#BranchName").val();
    
    $.post("<?php echo $this->webroot;?>LeaveApproval/get_gender",{'EmpCode':EmpCode,'BranchName':BranchName}, function(data) {
        $("#MtrlPtrl").html(data);
    });
    
    $.post("<?php echo $this->webroot;?>LeaveApproval/get_leave_details",{'EmpCode':EmpCode,'BranchName':BranchName,'FromDate':FromDate,'ToDate':ToDate}, function(data) {
        if(data !=""){
            $("#LeaveTable").show();
            $("#LeaveDetails").html(data);
        }
        else{
            $("#LeaveTable").hide();
        }
    });
}

function goBack(){
    window.location="<?php echo $this->webroot;?>LeaveApproval";  
}

function checkCharacter(e,t){
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

function ResetLeaveFor(){
    $("#CL").val('');
    $("#ML").val('');
    $("#LWP").val('');
    $("#EL").val('');
    $("#PTRL").val('');
    $("#MTRL").val('');
    
    $('input[name="LeaveType"]').removeAttr('checked');
    
    document.getElementById("CL").setAttribute("readonly", "readonly");
    document.getElementById("ML").setAttribute("readonly", "readonly");
    document.getElementById("LWP").setAttribute("readonly", "readonly");
    document.getElementById("EL").setAttribute("readonly", "readonly"); 
}

function showHideLeave(data){
    
    var LeaveFor = $("input:radio[name=LeaveFor]:checked").val()
    
    $("#CL").val('');
    $("#ML").val('');
    $("#LWP").val('');
    $("#EL").val('');
    $("#PTRL").val('');
    $("#MTRL").val('');
    
    document.getElementById("CL").setAttribute("readonly", "readonly");
    document.getElementById("ML").setAttribute("readonly", "readonly");
    document.getElementById("LWP").setAttribute("readonly", "readonly");
    document.getElementById("EL").setAttribute("readonly", "readonly");
    
    if(LeaveFor ==="Half Day" && data ==="CL"){
        $("#CL").val(.5);
    }
    else if(LeaveFor ==="Half Day" && data ==="ML"){
        $("#ML").val(.5);
    }
    else if(LeaveFor ==="Full Day" && data ==="CL"){
        $("#CL").focus();
        document.getElementById("CL").removeAttribute("readonly");
    }
    else if(LeaveFor ==="Full Day" && data ==="ML"){
        $("#ML").focus();
        document.getElementById("ML").removeAttribute("readonly");
    }
    else if(LeaveFor ==="Full Day" && data ==="LWP"){
        $("#LWP").focus();
        document.getElementById("LWP").removeAttribute("readonly");
    }
    else if(LeaveFor ==="Full Day" && data ==="EL"){
        $("#EL").focus();
        document.getElementById("EL").removeAttribute("readonly");
    }
    else if(LeaveFor ==="Full Day" && data ==="PTRL"){
        $("#PTRL").focus();
        document.getElementById("PTRL").removeAttribute("readonly");
    }
    else if(LeaveFor ==="Full Day" && data ==="MTRL"){
        $("#MTRL").focus();
        document.getElementById("MTRL").removeAttribute("readonly");
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
                    <span>LEAVE APPLICATION FORM</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            
            <div class="box-content box-con">
                <div style="margin-left: 170px;" ><?php echo $this->Session->flash(); ?></div>
                <?php echo $this->Form->create('LeaveApproval',array('class'=>'form-horizontal','action'=>'index','onSubmit'=>'return validateOdApply()')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name of Applicant</label>
                    <div class="col-sm-4">
                        <input type="text" name="searchEmp" autocomplete="off" placeholder="Search employees name" id="searchEmp" onkeyup="search_employees(this.value)" class="form-control"  >
                    </div>
                </div>
                
                 <div class="form-group">
                     <label class="col-sm-2 control-label">Employees Code</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="EmpNameCode" onchange="getEmployeesDetails(this.value);" autocomplete="off" name="EmpNameCode" required="" >
                        </select>
                    </div> 
                </div>
                
                
                <div class="form-group" style="display: none;" id="LeaveTable" >
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-4" id="LeaveDetails" ></div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Leave From</label>
                    <div class="col-sm-2">
                        <input type="text" name="FromDate" id="FromDate" onchange="getEmployeesDetails_by_month();" placeholder="Start Date"  autocomplete="off" class="form-control" required="" >
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="ToDate" id="ToDate" onchange="getEmployeesDetails_by_month();" placeholder="End Date" autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Leave For</label>
                    <div class="col-sm-4">
                         <input type="radio" name="LeaveFor" onclick="ResetLeaveFor(this.value)"  checked value="Full Day"> Full Day
                         <input type="radio" name="LeaveFor" onclick="ResetLeaveFor(this.value)"  value="Half Day"> Half Day
                    </div> 
                </div>
               
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type of Leave</label>
                    <div class="col-sm-10">
                        <input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="CL"  > CL 
                        <input type="text" name="CL" readonly="" id="CL" style="width:40px;" >
                        
                        <input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="ML"  > ML 
                        <input type="text" name="ML" readonly="" id="ML" style="width:40px;" >
            
                        <input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="LWP"  > LWP
                        <input type="text" name="LWP" readonly="" id="LWP" style="width:40px;" >
                        
                        <input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="EL" > EL
                        <input type="text" name="EL" readonly="" id="EL" style="width:40px;" >
                        
                     
                    </div> 
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Purpose of Leave</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="Purpose" id="Purpose"  autocomplete="off" required="" ></textarea>
                    </div> 
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Address During Leave</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="AddDurLeave" id="AddDurLeave"  autocomplete="off" required="" ></textarea>
                    </div> 
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact No</label>
                    <div class="col-sm-4">
                        <input type="text" name="ContactNo" id="ContactNo" maxlength="10" onkeypress="return checkCharacter(event,this)" class="form-control"  autocomplete="off" required="" >
                    </div> 
                </div>
               
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <input  type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-primary pull-right btn-new"  value="Submit">
                    </div> 
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>


