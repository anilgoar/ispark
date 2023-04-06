<?php ?>

<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox1").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox1').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox1:checked').length == $('.checkbox1').length ){
            $("#select_all").prop('checked', true);
        }
    });
});
    
    
function getUserId(UserId){
    $("#chkall").hide();
    $("#CostCenterRow").hide();
    $("#RolesRow").hide();
    
    $("#EmailId").val('');
    $.post("<?php echo $this->webroot;?>LeaveApplicationRights/get_cost_center",{'UserId':UserId}, function(data) {
        if(data !=""){
            $("#CostCenterRow").show();
            $("#CostCenter").html(data);
            $("#EmailId").val(UserId);
        }
        else{
            $("#CostCenterRow").hide();
            $("#CostCenter").html(''); 
            $("#EmailId").val('');
        }
    });
    
    $.post("<?php echo $this->webroot;?>LeaveApplicationRights/get_roles",{'UserId':UserId}, function(data) {
        if(data !=""){
            $("#chkall").show();
            $("#RolesRow").show();
            $("#RolesData").html(data);
        }
        else{
            $("#RolesRow").hide();
            $("#RolesData").html(''); 
            
        }
    });
}





/*
function getDetails(CostCenter){
    $("#msgerr").remove();
    $("#save").hide();
    var ActionFor=$("#ActionFor").val();
    var BranchName=$("#BranchName").val();
    
    if(ActionFor ===""){
        $("#ActionFor").focus();
        $("#ActionFor").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else{
        
        if(ActionFor==="Deduction"){
            var url="<?php echo $this->webroot;?>AttendanceDeductions/get_deduction";
        
            $.post(url,{'ActionFor':ActionFor,'BranchName':BranchName,'CostCenter':CostCenter}, function(data) {
                if(data !=""){
                    $("#ActionDetails").html(data);
                    $("#save").show();
                }
                else{
                   $("#ActionDetails").html('');
                   $("#save").hide();
                }
            }); 
        }
        else{
            
            $("#ActionDetails").html("<span style='color:red;font-size:12px;font-weight:bold;margin-left:170px;'>Record not found.</span>"); 
        }
    }
}
*/
function validatdRights(){  
    $("#msgerr").remove();
    var roles=checkRecord('Roles');
    var Process=checkRecord('Process');
    
    if(roles==''){
        $("#UserId").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select roles record.</span>");
        return false;
    }
    else if(Process==''){
        $("#UserId").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select process.</span>");
        return false;
    }
    else{
      return true; 
    }   
}

function checkRecord(fieldName){
    var all_location_id = document.querySelectorAll('input[name="'+fieldName+'[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
     aIds.push(all_location_id[x].value);
    }
    
    return aIds;
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
                    <span>LEAVE APPLICATION RIGHTS</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('LeaveApplicationRights',array('action'=>'index','class'=>'form-horizontal','onsubmit'=>'return validatdRights()')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">User ID</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('UserId',array('label' => false,'options'=>$UserMaster,'class'=>'form-control','onchange'=>'getUserId(this.value)','empty'=>'Select','id'=>'UserId','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group" style="display: none" id="RolesRow"  >
                    <label class="col-sm-2 control-label">Roles</label>
                    <div class="col-sm-10" id="RolesData" >
                        
                    </div>
                </div>
                
                <div class="form-group" style="display: none" id="chkall"   >
                    <label class="col-sm-2 control-label">Check All</label>
                    <div class="col-sm-3"  >
                        <input type="checkbox" id="select_all"/>
                    </div>
                </div>
                <div class="form-group" style="display: none" id="CostCenterRow"  >
                    <label class="col-sm-2 control-label">Process</label>
                    <div class="col-sm-3" style="overflow-y:scroll; height:200px;" id="CostCenter"  >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email Id</label>
                    <div class="col-sm-3">
                        <input  type="text" class="form-control" name="EmailId" id="EmailId" readonly="" required="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-new" >
                        <!--
                        <input type="reset" name="submit" value="Clear" class="btn btn-primary btn-new" >
                        -->
                    </div>
                </div>
                
                <div class="form-group">
                     <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
     
               <?php echo $this->Form->end(); ?>
              
            </div>
        </div>
    </div>	
</div>
