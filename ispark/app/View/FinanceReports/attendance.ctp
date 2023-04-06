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
                    <span>ATTENDANCE</span>
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
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('Masattendances/uploadattend')"> Upload Attendance<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('Masattendances/discardattandence')"> Discard Attendance<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('AttendanceExports')"> Attendance Export<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('MarkFieldAttendances')"> Mark Field Attendance<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('delete-field-attendance')"> Delete Field Attendance<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('OnSiteAttendances')"> Onsite Attendance<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('attendance-issue-submition')"> Attendance Issue Submition<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('bm-attendance-approval')"> Attendance Approval BM<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('ho-attendance-approval')"> Attendance Approval HO<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('DiscardAttendanceIssues')"> Discard Attendance Issue<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('OldAttendanceIssueSubmitions')"> Old Attendance issue<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('BmoldAttendanceApprovals')"> Old Attendance Approval BM<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('HooldAttendanceApprovals')"> Old Attendance Approval HO<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('ProcessAttendances')"> Process Attendances<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('FinalizeAttendances')"> Finalize Attendances<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('DiscardAttendances')"> Discard Process Attendances<br></div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('InsertAttendances')"> Insert Attendances<br></div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



