<?php
/*
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
*/
$backurl=$this->webroot."Menus?AX=MTA3";
?>

<script>
function getBranch(BranchName){
    $("#msgerr").remove();
    
    var EmpMonth = $("#EmpMonth").val();
	var BranchName=$("#BranchName").val();
    
	if(EmpMonth ===""){
		$("#BranchName").val('')
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>ImportEcsNumbers/getcostcenter",{BranchName:BranchName,EmpMonth:EmpMonth}, function(data){
            $("#CostCenter").html(data);
        });  
    }
}

function attendanceReport(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var UploadEcs=$("#UploadEcs").val();
    var CostCenter=$("#CostCenter").val();
    
	if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
	else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select cost center.</span>");
        return false;
    }
    else if(UploadEcs ===""){
        $("#UploadEcs").focus();
        $("#UploadEcs").after("<span id='msgerr' style='color:red;font-size:11px;'>Please upload file.</span>");
        return false;
    }
    else{
        $("#loder").hide();
        return true;
    }
}

function attendanceExport(Type){ 
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var EmpMonth    =   $("#EmpMonth").val();
    var CostCenter  =   $("#CostCenter").val();
    
	if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select cost center.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>ImportEcsNumbers/export_report?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&CostCenter="+CostCenter;
        }
        else if(Type ==="Delete"){
            if(confirm("Are you sure you want to delete this process ?")){
                $("#loder").hide();
                window.location="<?php echo $this->webroot;?>ImportEcsNumbers/delete_report?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&CostCenter="+CostCenter; 
            }
            else{
                $("#loder").hide();  
            }
            
        }
    }
}

function rereshBranch(){
	$("#BranchName").val('');
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
                    <span>UPLOAD ECS NUMBER (pre month)</span>
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
                <?php echo $this->Form->create('ImportEcsNumbers',array('action'=>'pre_month_ecsno','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                
                
                <div class="form-group">
					<label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonth" name="EmpMonth" autocomplete="off" class="form-control" required="" onchange="rereshBranch();" >
							<option value="">Select</option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." 0 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." 0 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -2 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -2 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -3 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -3 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -4 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -4 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -5 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -5 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -6 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -6 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -7 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -7 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -8 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -8 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -9 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -9 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -10 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -10 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -11 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -11 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -12 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -12 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -13 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -13 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -14 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -14 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -15 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -15 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -16 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -16 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -17 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -17 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -18 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -18 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -19 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -19 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -20 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -20 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -21 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -21 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -22 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -22 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -23 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -23 month"));?></option>
							<option value="<?php echo date('Y-m', strtotime(date('Y-m')." -24 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -24 month"));?></option>
                        </select>
                    </div>
				
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    
                    
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">UploadFile</label>
                    <div class="col-sm-2">
                        <input type="file" name="UploadEcs" id="UploadEcs"  accept=".csv">
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <!--
                        <input type="button" onclick="attendanceExport('Delete');" value="Delete" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        -->
                        <input type="button" onclick="attendanceExport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="submit" onclick="attendanceReport('Process');" value="Import" class="btn pull-right btn-primary btn-new">
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                
                <div class="form-group">
                    <!--
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">EmpLocation</label>
                    <div class="col-sm-2">
                        <select id="EmpLocation" name="EmpLocation" autocomplete="off" class="form-control" >
                            <option value="ALL">ALL</option>
                            <option value="InHouse">InHouse</option>
                            <option value="Field">Field</option>
                            <option value="OnSite">OnSite</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control" >
                    </div>
                   
                    
                    <div class="col-sm-3">
                        
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                       
                        <input type="button" onclick="attendanceReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="button" onclick="attendanceReport('Process');" value="Process" class="btn pull-right btn-primary btn-new">
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    -->
                </div>
                
                <div class="form-group" id="divAttendance" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>





<!--
<script>
function ViewFinalize(CostCenter,Id){
    $("#loder").show();
    $("#msgerr").remove();
   
    $.post("<?php echo $this->webroot;?>FinalizeAttendances/show_report",{CostCenter:CostCenter,Id:Id}, function(data) {
        $("#loder").hide();
        if(data !=""){
            $("#processAttend").html(data);
        }
        else{
            $("#processAttend").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
        } 
    }); 
}

function ExportFinalize(CostCenter){
    $("#loder").show();
    $("#loder").hide();
    window.location="<?php echo $this->webroot;?>FinalizeAttendances/export_report?CostCenter="+CostCenter;   
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
                    <span>FINALIZE ATTENDANCE </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('FinalizeAttendances',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                            <thead>
                                <tr>                	
                                    <th style="width: 30px;">SNo</th>
                                    <th >Cost Center</th>
                                    <th style="text-align: center; width:120px;" >Total Employee</th>
                                    <th style="text-align: center;width:100px;" >Status</th>
                                    <th style="text-align: center;width: 40px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total=0;
                                $i=1; foreach ($fieldArr as $val){
                                $total=$total+$val['TotalEmp'];
                                $cosc = base64_encode($val['CostCenter']);
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                                    <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                                    <?php if($val['Status'] > 0){?>
                                    <td style="text-align: center;color: green;"><?php echo "FINALIZE"?></td>
                                    <?php }else{?>
                                    <td style="text-align: center;color: red;"><?php echo "PROCESS";?></td>
                                    <?php }?>
                                    <td style="text-align: center;" ><i onclick="ViewFinalize('<?php echo $val['CostCenter'];?>','<?php echo $val['Id'];?>');" style="cursor:pointer;" class="material-icons">pageview</i></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td style="text-align: center;font-weight: bold;"><?php echo $total;?></td>
                                    <td></td>
                                </tr>
                            </tbody>           
                        </table>
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:25px;display: none;position:relative;top:25px;" id="loder"  >
                    </div>
                     <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=Mg%3D%3D"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="position:relative;top:25px;" />
                    </div>
                </div>
           
                <div class="form-group" style="position: relative;top:-60px;" id="processAttend" ></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
-->


