<?php ?>
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
                    <i class="fa fa-user"></i><span>Create User</span>
                </div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('User',array('action'=>'create_user','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-2">User Id</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('username',array('label'=>false,'class'=>'form-control','autocomplete'=>'off')); ?>
                    </div>
                    
                    <div class="col-sm-2">Password</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','autocomplete'=>'off')); ?>
                    </div>
                    
                    <div class="col-sm-2">Confirm Password</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('password2',array('label'=>false,'class'=>'form-control','placeholder'=>'Password','type'=>'password','autocomplete'=>'off','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <div class="col-sm-2">Department</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('work_type',array('label'=>false,'class'=>'form-control','options'=>$Department_List,'empty'=>'Select','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-2">Branch Name</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label'=>false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-2">Emp Code</div>
                    <div class="col-sm-2">
                        <input type="text" id="EmpCode" name="data[User][emp_code]" autocomplete="off" class="form-control" onkeyup="search_employees(this.value)" required="true" >
                    </div>
                    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2">Emp Name</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('emp_name',array('label'=>false,'class'=>'form-control','placeholder'=>'User Name','id'=>'EmpName','readonly'=>true,'required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-2">Process</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('role',array('label'=>false,'class'=>'form-control','options'=>$Process_List,'empty'=>'Select','required'=>true)); ?>
                    </div>
                    
                    <div class="col-sm-2">Process Head</div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('process_head',array('label'=>false,'class'=>'form-control','options'=>$process_manager,'empty'=>'Select','required'=>true)); ?>
                    </div>
    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2">HR Eligible</div>
                    <div class="col-sm-2" id="HrEligibleDiv">
                        <?php echo $this->Form->input('hr_eligible',array('label'=>false,'class'=>'form-control','options'=>array('Yes'=>'Yes','No'=>'No'),'empty'=>'Select','onchange'=>'getAccessType(this.value)','required'=>true)); ?>
                    </div>  
                </div>
                
                <div class="form-group" id="Access_Type_div"></div>
                
                <div class="form-group" id="Access_Rights_div"></div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <input type="submit" class="btn btn-primary pull-right btn-new" onclick="return password_check()" value="Create User" />
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
<script>
function search_employees(EmpCode){
    var BranchName=$("#BranchName").val();
    if(BranchName ===""){
        $("#EmpName" ).val('');
        alert('Please select branch name');
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>Users/get_emp",{'EmpCode':$.trim(EmpCode),'BranchName':BranchName}, function(data) {
            $("#EmpName").val(data);
        });
    }
}

function getAccessType(hr_eligible){
    $("#Access_Rights_div,#Access_Type_div").html('');
    if(hr_eligible =="Yes"){
        $("#Access_Type_div").html(
                    '<div class="col-sm-2">Access Type</div>'+
                    '<div class="col-sm-2">'+
                        '<select name="data[User][Access_Type]" id="Access_Type" onchange="getAccessRights(this.value)" class="form-control" required="true" >'+
                            '<option value="">Select</option>'+
                            '<option value="Own">Own</option>'+
                            '<option value="CostCentre">Cost Centre</option>'+
                        '</select>'+
                    '</div>'
                    );
    }
}

function getAccessRights(type){
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var EmpCode     =   $("#EmpCode").val();
    
    if(BranchName ===""){
        $("#Access_Type").val('');
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#Access_Type").val('');
        $("#EmpCode").focus();
        $("#EmpCode").after("<span id='msgerr' style='color:red;'>Enter empcode.</span>");
        return false;
    }
    else{
        if(type =="CostCentre"){
            $.post("<?php echo $this->webroot;?>Users/getcostcenter",{BranchName:BranchName,Access_Rights:''}, function(data) {
                $("#Access_Rights_div").html(data);
            });
        }
        else{
            $("#Access_Rights_div").html('');
        }
    } 
}




function password_check(){
    $("#msgerr").remove();
    var password1       =   document.getElementById("UserPassword").value;
    var password2       =   document.getElementById("UserPassword2").value;
    var UserHrEligible  =   document.getElementById("UserHrEligible").value;
    var Access_Type     =   document.getElementById("Access_Type").value;
    
    var all_location_id = document.querySelectorAll('input[name="Access_Rights[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
     aIds.push(all_location_id[x].value);
    }
    
    if(password1 != password2){
        $("#UserPassword2").focus();
        $("#UserPassword2").after("<span id='msgerr' style='color:red;'>Password not match.</span>");
        return false;
    }
    else if(UserHrEligible =="Yes" && Access_Type =="CostCentre" && aIds ==""){
        $("#Access_Type").after("<span id='msgerr' style='color:red;'>Select costcenter.</span>");
        return false;
    }
    return true;
}


</script>
