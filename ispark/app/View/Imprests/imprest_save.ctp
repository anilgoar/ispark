<?php
echo $this->Html->script('sample/datetimepicker_css1');
?>
<script>
function validate_imprest()
{
    var mode = $('#paymentMode').val();
    var PaymentNo = $('#PaymentNo').val();;
    var BankId = $('#BankId').val();;
    
    if((mode==1 || mode ==3) && (PaymentNo=='' || BankId==''))
    {
        alertify.error("Payment No or Bank Should Not be Blank");
        return false;
    }
    else
    {
        return true;
    }
}

function getImprestHide()
{
    var mode =$('#paymentMode').val();
    if(mode==1 || mode ==3)
    {
       $('#PaymentNo').prop('disabled',false);
       $('#BankId').prop('disabled',false);
    }
    else
    {
        $('#PaymentNo').prop('disabled',true);
       $('#BankId').prop('disabled',true);
    }
}
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
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


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass"> Imprest Allotment</h4>
 <?php echo '<font color="green">'.$this->Session->flash().'</font>'; ?>
    <?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Dated</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('EntryDate',array('label' => false,'class'=>'form-control','placeholder'=>'Date','id'=>'EntryDate','onClick'=>"javascript:NewCssCal1('EntryDate','ddMMyyyy','arrow',false,'24',false)",'readonly'=>true,'required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Imprest Manager</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('ImprestManagerId',array('label' => false,'options'=>$imprest_master,
                   'class'=>'form-control','empty'=>'--Imprest Manager--','id'=>'imprestId','required'=>true)); ?>
             <span class="input-group-addon"><i class="fa fa-user"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label">Payment Mode</label>
        <div class="col-sm-3">
            <div class="input-group">
            <?php echo $this->Form->input('PaymentMode',array('label' => false,'options'=>array('1'=>'Cheque','2'=>'Cash','3'=>'Fund Transfer'),
                'class'=>'form-control','empty'=>'--Select--','id'=>'paymentMode','onChange'=>'getImprestHide()','required'=>true)); ?>
            
             <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>  
             </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Amount',array('label' => false,'value'=>'',
                   'class'=>'form-control','placeholder'=>'Amount','id'=>'Amount','onkeypress'=>"return ((event.charCode >= 45 && event.charCode <= 57) || event.charCode==45)",'required'=>true)); ?>
             <span class="input-group-addon"><i class="fa fa-inr"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label">Bank Name</label>
        <div class="col-sm-3">
            <div class="input-group">
            <?php echo $this->Form->input('BankId',array('label' => false,'options'=>$bank,
                   'class'=>'form-control','empty'=>'select','id'=>'BankId')); ?>
                   
             <span class="input-group-addon"><i class="fa fa-university"></i></span>  
            </div>
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Cheque/Transaction Id </label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('PaymentNo',array('label' => false,'value'=>'',
                   'class'=>'form-control','placeholder'=>'Cheque/Transaction No.','id'=>'PaymentNo')); ?>
             <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label">Remarks</label>
       <div class="col-sm-3">
            <div class="input-group">
            <?php echo $this->Form->textarea('Remarks',array('label' => false,'value'=>'',
                   'class'=>'form-control','placeholder'=>'Remarks','id'=>'remarks','required'=>true)); ?> 
             <span class="input-group-addon"><i class="fa fa-file-text"></i></span>  
            </div>
        </div>
    </div>
    
    
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-2">
            <button type='submit' class="btn btn-info" value="Save" onclick="return validate_imprest()">Submit</button>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
    </div> 
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>