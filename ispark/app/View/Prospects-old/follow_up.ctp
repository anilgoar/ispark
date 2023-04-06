<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        
        
        
    </div>
</div>
 <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  //]]>
  </script>
<div class="box-content">
    <h4 class="page-header">Follow Up</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>

  
<div class="form-group">
    <label class="col-sm-2 control-label">To</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('EmailTo',array('label' => false,'class'=>'form-control','class'=>'form-control','value'=>$SC['ProspectClient']['Email'],"readonly"=>true)); ?>
    </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('EmailCC',array('label' => false,'class'=>'form-control','placeholder'=>'CC','value'=>$SC['ProspectClient']['EmailCC'],"readonly"=>true)); ?>
        </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Subject</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('EmailSub',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Subject','value'=>$SC['ProspectClient']['EmailSub'],"readonly"=>true)); ?>
    </div>
</div>  
<div class="form-group">
    <label class="col-sm-2 control-label">Lead Status</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('LeadStatus',array('label' => false,'class'=>'form-control','options'=>array('HOT'=>'HOT','WARM'=>'WARM','Cold'=>'Cold','NI'=>'NI'),'empty'=>'Select','value'=>$SC['ProspectClient']['LeadStatus'])); ?>
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-2 control-label">Date Time</label>
    <div class="col-sm-4">
        <?php echo $this->Form->input('FollowDate',array('label' => false,'class'=>'form-control','value'=>'','placeholder'=>'Follow up Date','onclick'=>"displayDatePicker('data[prospects][FollowDate]');",'value'=>$SC['ProspectClient']['FollowDate'],"readonly"=>true)); ?>
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-2 control-label">Remarks</label>
    <div class="col-sm-4">
        <?php echo $this->Form->textarea('Remarks',array('label' => false,'class'=>'form-control','value'=>'','placeholder'=>'Remarks','value'=>$SC['ProspectClient']['Remarks'],'rows'=>'8')); ?>
    </div>
</div>     



<div class="clearfix"></div>
<div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-2">
        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-label-left">
                Save
        </button>
    </div>
    <?php if(!empty($send_mail)) { ?>
        <div class="col-sm-2">
        <?php echo $this->Html->link(__('PDF'), array('controller'=>'prospects','action' => 'view_pdf','?'=>array('Id'=>$SC['ProspectClient']['Id']), 'ext' => 'pdf', 'DownloadPdf')); ?> 
        </div>
        <div class="col-sm-2">
        <button type="submit" name="Send" value="Send" class="btn btn-primary btn-label-left">
                Send
        </button>
        </div>
    <?php } ?>
</div>
<?php 
echo $this->Form->input('Id',array('label' => false,'type'=>'hidden','value'=>$SC['ProspectClient']['Id'],'class'=>'form-control')); 
echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
