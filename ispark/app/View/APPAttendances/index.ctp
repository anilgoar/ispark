<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
    /*
function searchEmployee(){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var SourceType=$("#SourceType").val();
    var SourceName=$("#SourceName").val();
    
    if(SourceType ===""){
        $("#SourceType").focus();
        $("#SourceType").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select SourceType.</span>");
        return false;
    }
    else if(SourceName ===""){
        $("#SourceName").focus();
        $("#SourceName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter SourceName.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>EmployeeDetails/show_employee",{BranchName:BranchName,SearchType:SearchType,SearchValue:$.trim(SearchValue)}, function(data) {
            if(data !=""){
                $("#divEmployee").html(data);
            }
            else{
                $("#divEmployee").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
            }
        });
    }
}
*/


   

</script>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
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
                    <span>Employee Attendance Export</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('APPAttendancesController',array('id'=>'export_attendance','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branch_master,'empty'=>'Select Branch','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">From Date</label>
                    <div class="col-sm-2">
                        <input type="text" id="FromDate" name="FromDate" autocomplete="off" value="<?php echo $OldFrom;?>" class="form-control datepik"  required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">To Date</label>
                    <div class="col-sm-2">
                        <input type="text" id="ToDate" name="ToDate" autocomplete="off" value="<?php echo $OldTo;?>" class="form-control datepik"  required="" >
                    </div>   
                </div>
                
                <div class="form-group">  
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" name="Submit" onclick="export_attendance()" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
<!--                        <input type="button" name="Submit1" onclick="view_attendance()" value="View" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">-->
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-9"  id="SourceDetails">
                </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

<script>
    
    
    
    
    
    function export_attendance()
    {
        
        var BranchName = $('#BranchName').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        window.location="<?php echo $this->webroot;?>APPAttendances/export_attendance?BranchName="+BranchName+"&FromDate="+FromDate+"&ToDate="+ToDate;
        
    }
    
    
</script>

