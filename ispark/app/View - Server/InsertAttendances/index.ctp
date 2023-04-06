<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<style>
.bordered{
    border-color: red;
}
</style>
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
    $(".bordered").removeClass('bordered');
    if(BranchName ===""){
        $("#searchEmp" ).val('');
        $("#BranchName").addClass('bordered');
        $("#BranchName" ).after("<span id='msgerr' style='color:red;font-size:11px;' >Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>InsertAttendances/get_emp",{'EmpName':$.trim(EmpName),'BranchName':BranchName}, function(data) {
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

function validateInsAttendance(){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var BranchName=$("#BranchName").val();
    var EmpNameCode=$("#EmpNameCode").val();
    var EmpNameCode=$("#EmpNameCode").val();
    var FromDate=$("#FromDate").val();
    var ToDate=$("#ToDate").val();

    if(BranchName ===""){
        $("#BranchName").addClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpNameCode ===null){
        $("#EmpNameCode").addClass('bordered');
        $("#EmpNameCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp code.</span>");
        return false;
    }
    else if(EmpNameCode ===""){
        $("#EmpNameCode").addClass('bordered');
        $("#EmpNameCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp code.</span>");
        return false;
    }
    else if(FromDate ===""){
        $("#FromDate").addClass('bordered');
        $("#FromDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Select start date.</span>");
        return false;
    }
    else if(ToDate ===""){
        $("#ToDate").addClass('bordered');
        $("#ToDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Select end date.</span>");
        return false;
    }
    else if(checkDate(FromDate,ToDate) ==''){
        $("#FromDate").addClass('bordered');
        $("#ToDate").addClass('bordered');
        $("#FromDate").val('');
        $("#ToDate").val('');
        $("#FromDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date.</span>");
        return false;
    }
    else if(checkDateOfJoin(BranchName,EmpNameCode,FromDate,ToDate) ==''){
        $("#FromDate").addClass('bordered');
        $("#ToDate").addClass('bordered');
        $("#FromDate").val('');
        $("#ToDate").val('');
        $("#FromDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Select date according DOJ.</span>");
        return false;
    }
   
    else{
        return true;
    }
}

function existAttendance(BranchName,EmpNameCode,FromDate,ToDate){
	var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>InsertAttendances/exist_attendance",async: false,dataType: 'json',data: {BranchName:BranchName,EmpNameCode:EmpNameCode,FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
	return posts;
}

function checkDateOfJoin(BranchName,EmpNameCode,FromDate,ToDate){
	var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>InsertAttendances/check_doj",async: false,dataType: 'json',data: {BranchName:BranchName,EmpNameCode:EmpNameCode,FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
	return posts;
}

function checkDate(FromDate,ToDate){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>InsertAttendances/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,ToDate:ToDate},done: function(response) {return response;}}).responseText;	
    return posts;
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
                    <span>INSERT ATTENDANCE</span>
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
                <?php echo $this->Form->create('InsertAttendances',array('class'=>'form-horizontal','action'=>'index','onSubmit'=>'return validateInsAttendance()')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value)')); ?>
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
                        <select class="form-control" id="EmpNameCode" autocomplete="off" name="EmpNameCode" >
                        </select>
                    </div> 
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Attendance From</label>
                    <div class="col-sm-2">
                        <input type="text" name="FromDate" id="FromDate" placeholder="Start Date"  autocomplete="off" class="form-control"  >
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="ToDate" id="ToDate" placeholder="End Date" autocomplete="off" class="form-control" >
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-primary pull-right btn-new"  value="Submit">
                    </div>
                    
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>


