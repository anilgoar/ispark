<?php ?>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>AttendanceExports/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function attendanceReport(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var LastEmpMonth=$("#LastEmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var CostCenter=$("#CostCenter").val();
    var EmpLocation=$("#EmpLocation").val();
    var EmpCode=$("#EmpCode").val();
    var EmpCtc=$("#EmpCtc").val();
    
    var all_location_id = document.querySelectorAll('input[name="BranchName[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
     aIds.push(all_location_id[x].value);
    }
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select first month.</span>");
        return false;
    }
    else if(LastEmpMonth ===""){
        $("#LastEmpMonth").focus();
        $("#LastEmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select last month.</span>");
        return false;
    }
    else if(LastEmpMonth < EmpMonth){
        $("#LastEmpMonth").focus();
        $("#LastEmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYear").focus();
        $("#EmpYear").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp year.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>WorkingDetails/show_report",{BranchName:aIds,EmpCtc:$.trim(EmpCtc),EmpMonth:EmpMonth,LastEmpMonth:LastEmpMonth,EmpYear:EmpYear,CostCenter:CostCenter,EmpLocation:EmpLocation,EmpCode:$.trim(EmpCode)}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divAttendance").html(data);
                }
                else{
                    $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>WorkingDetails/export_report?BranchName="+aIds+"&EmpCtc="+$.trim(EmpCtc)+"&EmpMonth="+EmpMonth+"&LastEmpMonth="+LastEmpMonth+"&EmpYear="+EmpYear+"&CostCenter="+CostCenter+"&EmpLocation="+EmpLocation+"&EmpCode="+$.trim(EmpCode);  
           
        }
    }
}

$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".check_branch").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.check_branch').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.check_branch:checked').length == $('.check_branch').length ){
            $("#select_all").prop('checked', true);
        }
    });
});
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
                    <span>WORKING HOURS</span>
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
                <?php echo $this->Form->create('AttendanceExports',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Branch</label>
                            <!--
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'style'=>'height:150px;','class'=>'form-control multiselect','id'=>'BranchName','onchange'=>'getBranch(this.value)','multiple'=>true,'required'=>true)); ?>
                            </div>
                            -->
                            <div class="col-sm-10" style="overflow-y: scroll;height: 150px;" >
                                <input type="checkbox" id="select_all"/> ALL <br/>
                                <?php foreach($branchName as $row){ ?>
                                <input type="checkbox" name="BranchName[]"  class="check_branch"  id="BranchName" value="<?php echo $row;?>" > <?php echo $row;?><br/>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">First Month</label>
                            <div class="col-sm-3">
                                <select id="EmpMonth" name="EmpMonth" autocomplete="off" class="form-control" >
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

                            <label class="col-sm-3 control-label">Last Month</label>
                            <div class="col-sm-3">
                                <select id="LastEmpMonth" name="LastEmpMonth" autocomplete="off" class="form-control" >
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
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Year</label>
                            <div class="col-sm-3">
                                <select id="EmpYear" name="EmpYear" autocomplete="off" class="form-control" >
                                    <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                                    <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
                                </select>
                            </div>

                            <!--
                            <label class="col-sm-3 control-label">EmpCode</label>
                            <div class="col-sm-3">
                                <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control" >
                            </div>
                            -->

                            <label class="col-sm-3 control-label">CTC Greater Than</label>
                            <div class="col-sm-3">
                                <input type="text" id="EmpCtc" name="EmpCtc" autocomplete="off" placeholder="CTC" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                                <input type="button" onclick="attendanceReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                                <input type="button" onclick="attendanceReport('show');" value="Show" class="btn pull-right btn-primary btn-new"> 
                            </div>
                        </div>
                    </div> 
                </div>
                
                <div class="form-group" id="divAttendance" ></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



