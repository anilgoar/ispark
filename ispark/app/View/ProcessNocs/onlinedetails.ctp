<?php ?>
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<style>
.req{
    color:red;
    font-weight: bold;
    font-size: 16px;
}
.msger{
    color:red;
    font-size:11px;
}
.bordered{
    border-color: red;
}
.col-sm-2{margin-top:-12px !important;}
.col-sm-3{margin-top:-12px !important;}

</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
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
                    <span>FNF DETAILS</span>
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
                
                <?php echo $this->Form->create('ProcessNocs',array('action'=>'onlinedetails','class'=>'form-horizontal','id'=>'JCLRFORM','enctype'=>'multipart/form-data')); ?>
                <input type="hidden" name="MasJclrsId" id="MasJclrsId" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <input type="hidden" name="OfferNo" id="OfferNo" value="<?php echo $data['Masjclrentry']['OfferNo'];?>" >
                <input type="hidden" name="TabName" id="TabName">

                
                <div class="form-group" style="margin-top:30px;" >
                    <label class="col-sm-2 control-label" >Employee Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="EmpName" readonly="" id="EmpName" value="<?php echo $data['Masjclrentry']['EmpName'];?>" class="form-control" >
                    </div>
                </div>

                <div class="form-group" >    
                    <label class="col-sm-2 control-label" >Upload Filled NOC</label>
                    <div class="col-sm-2">
                        <?php   echo $this->Form->input('CancelledChequeImage', array('label'=>false,'type' => 'file','id'=>'CancelledChequeImage','accept'=>'image/jpg','required'=>true));?>
                    </div> 
                    <div class="col-sm-2">
                        <?php if($data['Masjclrentry']['FnfDoc'] !=""){?>
                        <img style="width:50px;" src="<?php echo $this->webroot;?>Doc_File/<?php echo $data['Masjclrentry']['OfferNo'];?>/<?php echo $data['Masjclrentry']['FnfDoc'];?>" >
                        <?php }?>
                    </div>
                </div>
            
                <div class="form-group" >
                     <label class="col-sm-2 control-label" >Reason of Leaving</label>
                    <div class="col-sm-3">
                        <textarea id="ReasonofLeaving" name="ReasonofLeaving" class="form-control" required ><?php echo $data['Masjclrentry']['ReasonofLeaving'];?></textarea>
                    </div>
                </div>
				
				<div class="form-group" >
                     <div class="col-sm-5">
                        <input onclick='return window.location="<?php echo $this->webroot;?>ProcessNocs"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-info btn-new pull-right" value="Submit" style="margin-left:5px;" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>