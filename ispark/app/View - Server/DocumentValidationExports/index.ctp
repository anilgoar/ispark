<?php ?>
<script>
function attendanceReport(action){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var type=$("#type").val();
    var viewtype=$("#viewtype").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(action ==="show"){
            $.post("<?php echo $this->webroot;?>DocumentValidationExports/show_report",{BranchName:BranchName,type:type,viewtype:viewtype}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divAttendance").html(data);
                }
                else{
                    $("#divAttendance").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
        else if(action ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>DocumentValidationExports/export_report?BranchName="+BranchName+"&type="+type+"&viewtype="+viewtype;  
           
        }
    }
}

function AccountReport(BranchName,Type){
    window.location="<?php echo $this->webroot;?>DocumentValidationExports/account_report?BranchName="+BranchName+"&Type="+Type;  
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
                    <span>DOCUMENT VALIDATION REPORTS</span>
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
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <select id="type" class="form-control" >
                            <option value="branch" >Branch</option>
                            <option value="costcenter" >Cost Center</option>
                            <option value="data" >Employee</option>
                        </select>
                    </div>
                    <!--
                    <label class="col-sm-1 control-label">Result</label>
                    <div class="col-sm-2">
                        <select id="viewtype" class="form-control" >
                            <option value="mis" >MIS</option>
                            <option value="data" >Data</option>
                            
                        </select>
                    </div>
                    -->
                   <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="attendanceReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                         
                        <input type="button" onclick="attendanceReport('show');" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
               
                </div>
          
                
                <div class="form-group" id="divAttendance" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



