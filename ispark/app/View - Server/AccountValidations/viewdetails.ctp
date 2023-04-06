<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#ResignationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function goBack(){
    window.location="<?php echo $this->webroot;?>EmployeeDetails";  
}

function showdiv(id){
    $("#"+id).toggle();
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
                    <span>MAKER AND CHECKER</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AccountValidations',array('action'=>'viewdetails','class'=>'form-horizontal')); ?>
                <input type="hidden" name="EJEID" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <div class="form-group" style="position: relative;top:-10px;" >
                    <div class="col-sm-12">
                        <?php if( $data['Masjclrentry']['CancelledChequeImage'] !=""){?>
                            <img src="<?php echo $this->webroot.'Doc_File/'.$data['Masjclrentry']['OfferNo'].'/'.$data['Masjclrentry']['CancelledChequeImage'];?>" style="width:100%; height: 300px;" >
                        <?php  }else{ ?>
                            <hr/>
                            <h2 style="text-align: center;" >CANCEL CHEQUE NOT AVALIABLE</h2>
                            <hr/>
                        <?php  } ?>
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="col-sm-2 control-label">Employee Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="EmpName" id="EmpName" readonly="" class="form-control" value="<?php echo $data['Masjclrentry']['EmpName'];?>" required="" >
                    </div>
                    <label class="col-sm-2 control-label">Account Holder</label>
                    <div class="col-sm-3">
                        <input type="text" name="AccHolder" id="AccHolder" class="form-control" value="<?php echo $data['Masjclrentry']['AccHolder'];?>" required="" >
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="col-sm-2 control-label">Bank Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="AcBank" id="AcBank" class="form-control" value="<?php echo $data['Masjclrentry']['AcBank'];?>" required="" >
                    </div>
                    <label class="col-sm-2 control-label">Branch Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="AcBranch" id="AcBranch" class="form-control" value="<?php echo $data['Masjclrentry']['AcBranch'];?>" required="" >
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="col-sm-2 control-label">Account No</label>
                    <div class="col-sm-3">
                        <input type="text" name="AcNo" id="AcNo" class="form-control" value="<?php echo $data['Masjclrentry']['AcNo'];?>" required="" >
                    </div>
                    <label class="col-sm-2 control-label">IFSC Code</label>
                    <div class="col-sm-3">
                        <input type="text" name="IFSCCode" id="IFSCCode" class="form-control" value="<?php echo $data['Masjclrentry']['IFSCCode'];?>" required="" >
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="col-sm-2 control-label" >Account Type</label>
                    <div class="col-sm-3">
                        <select id="AccType" name="AccType" class="form-control" required="" >
                            <option value="">SELECT</option>
                            <option <?php if($data['Masjclrentry']['AccType'] =="SAVING"){echo "selected='selected'";}?>  value="SAVING">SAVING</option>
                            <option <?php if($data['Masjclrentry']['AccType'] =="CURRENT"){echo "selected='selected'";}?> value="CURRENT">CURRENT</option>
                        </select>
                    </div>
                    <label class="col-sm-2 control-label" >Rejection Remarks</label>
                    <div class="col-sm-3">
                        <textarea id="AcRejectionRemarks" name="AcRejectionRemarks" class="form-control"><?php echo $data['Masjclrentry']['AcRejectionRemarks'];?></textarea>
                    </div>
                </div>
                
                <div class="form-group" >
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $this->webroot;?>AccountValidations"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit"  value="Reject" class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit"  value="Validate" class="btn pull-right btn-primary btn-new" >
                    </div>
                </div>
                    
                <div class="form-group">  
                    <div class="col-sm-12"><?php echo $this->Session->flash();?> </div> 
                </div>
               
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
    </div>	
</div>



