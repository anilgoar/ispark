

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
    <h4 class="page-header">Create Cover</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>

  
<div class="form-group">
    <label class="col-sm-2 control-label">To</label>
    <div class="col-sm-4">
        <?php 
        if($SC['ProspectClient']['IntroApprove']==2 && $SC['ProspectClient']['EmailTo']!=$SC2['ProspectClientHis']['EmailTo']) { $style="color:red"; } else { $style=""; }
        echo $this->Form->input('EmailTo',array('label' => false,'class'=>'form-control','class'=>'form-control','value'=>$SC['ProspectClient']['Email'],'required'=>true,'style'=>$style)); ?>
    </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">CC</label>
        <div class="col-sm-4">
        <?php if($SC['ProspectClient']['IntroApprove']==2 && $SC['ProspectClient']['EmailCC']!=$SC2['ProspectClientHis']['EmailCC']) { $style="color:red"; } else { $style=""; }
        echo $this->Form->input('EmailCC',array('label' => false,'class'=>'form-control','placeholder'=>'CC','value'=>$SC['ProspectClient']['EmailCC'],'required'=>true,'style'=>$style)); ?>
        </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Subject</label>
    <div class="col-sm-4">
        <?php
        if($SC['ProspectClient']['IntroApprove']==2 && $SC['ProspectClient']['EmailSub']!=$SC2['ProspectClientHis']['EmailSub']) { $style="color:red"; } else { $style=""; }
        echo $this->Form->input('EmailSub',array('label' => false,'class'=>'form-control','class'=>'form-control','placeholder'=>'Subject','value'=>$SC['ProspectClient']['EmailSub'],"requred"=>true,'style'=>$style)); ?>
    </div>
</div>  
    <div class="form-group">
        <label class="col-sm-2 control-label">Introduction</label>
        <div class="col-sm-4">
        <?php
        if($SC['ProspectClient']['IntroApprove']==2 && $SC['ProspectClient']['Introduction']!=$SC2['ProspectClientHis']['Introduction']) { $style="color:red"; } else { $style=""; }
        echo $this->Form->input('Introduction',array('label' => false,'class'=>'form-control','options'=>array('EOI'=>'EOI','Commercial'=>'Commercial','others'=>'others','Revised proposal'=>'Revised proposal'),'empty'=>'Select','value'=>$SC['ProspectClient']['Introduction'],"required"=>true,'style'=>$style)); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Email Body</label>
        <div class="col-sm-4">
        <?php  if($SC['ProspectClient']['IntroApprove']=='2' && $SC['ProspectClient']['MailBody']!=$SC2['ProspectClientHis']['MailBody']) { $style=" <style>
      .nicEdit-main{
          color: red;
      }
  </style>"; } else { $style=""; }
        echo $this->Form->textarea('MailBody',array('label' => false,'style'=>"width: 100%;",'placeholder'=>'Mail Body','rows'=>'10','value'=>$SC['ProspectClient']['MailBody'],"id"=>"MailBody")); ?>
        </div>
    </div>
<div class="form-group">
        <label class="col-sm-1 control-label">Cover Body</label>
</div>
<div class="form-group has-success has-feedback">
        <div class="col-sm-12">
        <?php 
        if($SC['ProspectClient']['IntroApprove']=='2' && $SC['ProspectClient']['Cover']!=$SC2['ProspectClientHis']['Cover']) { $style="<style>
      .nicEdit-main{
          color: red;
      }
  </style>"; } else { $style=""; }
        echo $this->Form->textarea('Cover',array('label' => false,'style'=>"width: 100%;",'placeholder'=>'Cover','rows'=>'20','value'=>$SC['ProspectClient']['Cover'],"id"=>"cover")); ?>
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
