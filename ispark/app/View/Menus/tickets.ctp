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
                    <span>Tickets</span>
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
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('Tickets')">Ticket Visualizer</div>
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('Tickets/join_alert')">Employee Onboarding Alert</div>
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('Tickets/left_alert')">Employee OFF boarding Alert</div>
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('Tickets/report')">Joiner and Leaver Insights</div>
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('EmpAttStatuses')">Attendance Navigator</div>
                    <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('EmpAttStatuses/mail_alert')">Employee AWOL (Absent Without Offical Leave)</div> 
                    <!-- <div class="col-sm-4"><input type="radio" name="MENU" onclick="redirect('Tickets/view_all_alert')">Alert Edit</div>  -->
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



