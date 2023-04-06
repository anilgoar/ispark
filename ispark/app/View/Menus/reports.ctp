<?php ?>
<script>   
function redirect(path){
    window.location="<?php echo $this->webroot;?>"+path;
}
</script>
<style>
    .form-group{
        font-size: 13px !important;
    }
</style>
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
                    <span>Reports</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('Menus',array('class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('DeleteBiocodeReports')"> Deleted BioCode Details</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('LeftemployeeExports')"> Left Employee Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('JoinemployeeExports')"> New Joinee Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('AccountvalidationExports')"> Validated Bank Account MIS</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('IncrementExports')"> Increment Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('EmployeeSourceMasters/source_export')"> Employee Source Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('EmployeeSourceMasters/attrition_export')"> Attrition Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('DocumentValidationExports')"> Document Validation Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('FnfMisReports')"> FNF MIS </div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('EmployeeSuspendedReports')"> Employee Suspended </div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('EmployeePendingReports')"> Employee Pending </div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('AttendanceMisReports')"> Attendance Issue MIS Reports </div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('ProcessManningReports')"> Process Wise Manning Details </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



