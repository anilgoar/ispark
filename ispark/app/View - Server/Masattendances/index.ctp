<?php ?>
<script>   
function redirectUrl(){
    $("#msgerror").remove();
    var radioValue = $("input[name='od']:checked").val();
    if(radioValue ==="UploadAttendance"){
        window.location="<?php echo $this->webroot;?>Masattendances/uploadattend";
    }
    else if(radioValue ==="DiscardAttendance"){
        window.location="<?php echo $this->webroot;?>Masattendances/discardattandence";  
    }
    else{
        $("#Next").after('<span id="msgerror" style="color:red;" ><br/>Note - pleace select anyone option.</span>');
        return false;
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
                    <span>Upload/Discard Attendance</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
                <?php echo $this->Form->create('OdApprovalDisapprovals',array('class'=>'form-horizontal','action'=>'odapproval','id'=>'showDetails')); ?>
                <div class="form-group">
                    <div class="col-sm-3">
                        <input type="radio" name="od" required="" value="UploadAttendance"> Upload Attendance<br>
                        <input type="radio" name="od" required="" value="DiscardAttendance"> Discard Attendance<br>                       
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $this->Form->submit('Next', array('div'=>false, 'id'=>'Next','type'=>'button','onclick'=>'redirectUrl()','class'=>'btn btn-primary btn-new','style'=>'margin-left:10px;'));?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

