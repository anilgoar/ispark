<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
    });
});
</script>
<script>
function DeletedBiocodeReport(Type){ 
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    
    var StartDate=$("#StartDate").val();

    if(StartDate ===""){
        $(".StartDate").removeClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select date.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>DeleteBiocodeReports?StartDate="+StartDate;   
        }
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
                    <span>DELETED BIOCODE DETAILS </span>
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
                <?php echo $this->Form->create('DeleteBiocodeReports',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Select Date</label>
                    <div class="col-sm-2">
                        <input type="text" name="StartDate" id="StartDate"   autocomplete="off" readonly="" class="form-control datepik"  >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="DeletedBiocodeReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



