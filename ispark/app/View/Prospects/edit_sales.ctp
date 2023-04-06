<script>
    $(document).ready(function() {
  $(".js-example-basic-single").select2({
      
  });
});
</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>

<div class="box-content">
    <h4 class="page-header">Save Prospect</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
    
  
<div class="form-group">
    <label class="col-sm-2 control-label">Product Name</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('ProductId',array('label' => false,'class'=>'form-control','class'=>'form-control js-example-basic-single','options'=>$product_master,'empty'=>'Select')); ?>
    </div>
</div>



<div class="form-group">
    <label class="col-sm-2 control-label">Client Name</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('ClientName',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Client Name')); ?>
    </div>
</div>  
    
<div class="form-group">
        <label class="col-sm-2 control-label">Contact No.</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('ContactNo',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Contact No.','onkeypress'=>'return isNumberKey(event)')); ?>
        </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">Email ID</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('Email',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Email','type'=>'email')); ?>
        </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">Sender Email Id</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('SenderEmail',array('label' => false,'class'=>'form-control','class'=>'form-control','empty'=>'Select','options'=>$email_master)); ?>
        </div>
</div>
    
<div class="form-group">
        <label class="col-sm-2 control-label">Lead Source</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('LeadSource',array('label' => false,'class'=>'form-control','class'=>'form-control','empty'=>'Select','options'=>$lead_source_master)); ?>
        </div>
</div>    
    
<div class="form-group">
        <label class="col-sm-2 control-label">Address 1</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Address1',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Address 1')); ?>
        </div>
</div>
<div class="form-group">
        <label class="col-sm-2 control-label">Address 2</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Address2',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Address 2')); ?>
        </div>
</div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Address 3</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Address3',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Address 3')); ?>
        </div>
</div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Address 4</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Address4',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Address 4')); ?>
        </div>
</div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Address 5</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Address5',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Address 5')); ?>
        </div>
</div>
<div class="form-group">
        <label class="col-sm-2 control-label">Remarks</label>
        <div class="col-sm-4">
        <?php echo $this->Form->textarea('Remarks',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Remarks','rows'=>'5')); ?>
        </div>
</div>
<div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Client Logo</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->file('logo_file', array('type'=>'file','label' => false, 'div' => false,'accept'=>'image/x-png,image/gif,image/jpeg')); ?>
                    </div>
</div>
    <div class="form-group has-feedback">
        <label class="col-sm-2 control-label">Attachment</label>
        <div class="col-sm-3">
            <?php echo $this->Form->file('attachment', array('type'=>'file','label' => false, 'div' => false,'accept'=>"application/pdf")); ?>
        </div>
    </div>
<div class="clearfix"></div>
<div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-label-left">
                Save
        </button>
    </div>
</div>
<?php 
echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
