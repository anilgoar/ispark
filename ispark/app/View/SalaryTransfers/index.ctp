<?php
$exp    =   explode("Menus?AX=", $_SERVER['HTTP_REFERER']);
$expid  =   end($exp);
if(isset($_REQUEST['backid'])){
    $backid=$_REQUEST['backid'];
    $backurl=$this->webroot."Menus?AX=".$_REQUEST['backid'];
}
else{
    $backid=$expid;
    $backurl=$this->webroot."Menus?AX=".$expid;
}
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
<script>
function exportSalary(){ 
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchNameExp").val();
    var Total_Amount=   $("#Total_Amount").val();
    var Total_count =   $("#Total_count").val();
    
    var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
     aIds.push(all_location_id[x].value);
    }
    
    if(aIds ==""){
        alert('Please select salary record.');
        return false;
    }
    else{
        if(confirm("Total Amount "+Total_Amount+" and Count "+Total_count+" is ready to disburse. \n Are you sure you want to submit selected record.") == true){
            return true;
        }else { 
            return false;
        }
    }
}

function viewSalary(){ 
    $("#msgerr").remove();

    var EmpMonth=$("#EmpMonthExp").val();
    var EmpYear=$("#EmpYearExp").val();
    var BranchName=$("#BranchNameExp").val();
    var CostCenter=$("#CostCenter").val();
	var EmpType 	= 	$("#EmpType").val();
	var EmpStatus 	= 	$("#EmpStatus").val();

    if(EmpMonth ===""){
        $("#EmpMonthExp").focus();
        $("#EmpMonthExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYearExp").focus();
        $("#EmpYearExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp year.</span>");
        return false;
    }
	else if(EmpType ===""){
        $("#EmpType").focus();
        $("#EmpType").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp type.</span>");
        return false;
    }
	else if(EmpStatus ===""){
        $("#EmpStatus").focus();
        $("#EmpStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp status.</span>");
        return false;
    }
    else if(BranchName ===""){
        $("#BranchNameExp").focus();
        $("#BranchNameExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $(".loader").show();
        $.post("<?php echo $this->webroot;?>SalaryTransfers/view_salary",{BranchName:BranchName,CostCenter:CostCenter,EmpMonth:EmpMonth,EmpYear:EmpYear,EmpType:EmpType,EmpStatus:EmpStatus}, function(data){
            $("#view_salary_data").html(data);
            $(".loader").hide();
        }); 
    }
}

function sumSalary(Amount,Id){
    
    var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
        aIds.push(all_location_id[x].value);
    }
    
    var a   =   Number(document.getElementById("Total_Amount").value);
    var b   =   Number(Amount);

    var checkBox = document.getElementById(Id);

    if (checkBox.checked == true){
        var c   = a + b;
    }else {
        var c   = a - b;
    }

    $("#Total_Amount").val(c);
    $("#Total_count").val(aIds.length);
    
    $("#Emp_Amount").html("<span> <strong>No of Emp :</strong> "+aIds.length+" </span> | <span> <strong>Salary :</strong> "+c+" </span>");
}

function getBranch(BranchName){
    $("#msgerr").remove();
    
    var EmpMonth	= 	$("#EmpMonthExp").val();
    var EmpYear 	= 	$("#EmpYearExp").val();
	var EmpType 	= 	$("#EmpType").val();
	var EmpStatus 	= 	$("#EmpStatus").val();
	
    if(EmpMonth ===""){
        $("#EmpMonthExp").focus();
        $("#EmpMonthExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYearExp").focus();
        $("#EmpYearExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp year.</span>");
        return false;
    }
	else if(EmpType ===""){
        $("#EmpType").focus();
        $("#EmpType").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp type.</span>");
        return false;
    }
	else if(EmpStatus ===""){
        $("#EmpStatus").focus();
        $("#EmpStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp status.</span>");
        return false;
    }
    else if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>SalaryTransfers/getcostcenter",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data){
            $("#CostCenter").html(data);
        });  
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
                    <span>SALARY TRANSFER</span>
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
                <form method="post" action="<?php echo $this->webroot;?>app/webroot/salarytransfer/excel.php" onsubmit="return exportSalary()" class="form-horizontal" >
                <div class="form-group">
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonthExp" name="EmpMonthExp" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="01">Jan</option>
                            <option value="02">Feb</option>
                            <option value="03">Mar</option>
                            <option value="04">Apr</option>
                            <option value="05">May</option>
                            <option value="06">Jun</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                            <option value="09">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmpYearExp" name="EmpYearExp" autocomplete="off"   class="form-control"  required="" >
                            <option value="">Select</option>
                            <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
                        </select>
                    </div>
					
					<label class="col-sm-1 control-label">EmpType</label>
                    <div class="col-sm-2">
                        <select id="EmpType" name="EmpType" autocomplete="off"   class="form-control"  required="" >
                            <option value="">Select</option>
                            <option value="MAS">ONROLL MAS</option>
                            <option value="IDC">ONROLL IDC</option>
							<option value="MT">MANAGEMENT TRAINEE</option>
                        </select>
                    </div>
					
					
                    
                </div> 
                    
                <div class="form-group">
				
					<label class="col-sm-1 control-label">EmpStatus</label>
                    <div class="col-sm-2">
                        <select id="EmpStatus" name="EmpStatus" autocomplete="off"   class="form-control"  required="" >
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="0">Left</option>
						</select>
                    </div>
				
					<label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <select id="BranchNameExp" name="BranchNameExp" onchange="getBranch(this.value)"  autocomplete="off" class="form-control" required=""  >
                            <option value="">Select</option>
                            <?php foreach($branchNameAll as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                        </select>
                    </div>
				
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTA3"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        
                        <input type="button" onclick="viewSalary();" value="View" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
					
					<div class="col-sm-1" ><div class="loader pull-right" style="display:none;" ></div></div>
                </div>
                    
                <div class="form-group"><div class="col-sm-12" id="view_salary_data" ></div></div>
                
                </form>
            </div>            
        </div>
    </div>	
</div>
