<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left">
                
                <li><a href="#">Tables</a></li>
                <li><a href="#">Simple Tables</a></li>
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
 <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  //]]>
  </script>
<div class="box-content">
    <h4 class="page-header">Create Cover</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('Addproduct',array('class'=>'form-horizontal')); ?>
    
  
<div class="form-group">
    <label class="col-sm-2 control-label">To</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('EmailTo',array('label' => false,'class'=>'form-control','class'=>'form-control','value'=>$SC['SalesClient']['Email'])); ?>
    </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('EmailCC',array('label' => false,'class'=>'form-control','placeholder'=>'CC')); ?>
        </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Subject</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('EmailSub',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Subject')); ?>
    </div>
</div>  
<div class="form-group">
    <label class="col-sm-2 control-label">Lead Status</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('LeadStatus',array('label' => false,'class'=>'form-control','options'=>array('HOT'=>'HOT','WARM'=>'WARM','Cold'=>'Cold','NI'=>'NI'),'empty'=>'Select')); ?>
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-2 control-label">Date Time</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('FollowDate',array('label' => false,'class'=>'form-control','value'=>'','placeholder'=>'Follow up Date')); ?>
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-2 control-label">Remarks</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('Remarks',array('label' => false,'class'=>'form-control','value'=>'','placeholder'=>'Remarks')); ?>
    </div>
</div>     
<div class="form-group">
        <label class="col-sm-1 control-label">Cover Body</label>
</div>
<div class="form-group has-success has-feedback">    
        <div class="col-sm-12">
        <?php echo $this->Form->textarea('Cover',array('label' => false,'style'=>'width: 100%;','placeholder'=>'Cover','rows'=>'20')); ?>
        </div>
</div>



    
<div class="clearfix"></div>
<div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-label-left">
                Send
        </button>
    </div>
</div>
<?php 
echo $this->Form->input('Id',array('label' => false,'type'=>'hidden','value'=>$SC['SalesClient']['Id'],'class'=>'form-control')); 
echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
