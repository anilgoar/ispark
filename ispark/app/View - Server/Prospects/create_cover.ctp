

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
<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>

  
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
        <?php  if($SC['ProspectClient']['IntroApprove']=='2' && $SC['ProspectClient']['MailBody']!=$SC2['ProspectClientHis']['MailBody']) { echo $style=" <style>
      .nicEdit-main{
          color: red;
      }
  </style>"; } else { $style=""; }
        echo $this->Form->textarea('MailBody',array('label' => false,'style'=>"width: 100%;",'placeholder'=>'Mail Body','rows'=>'10','value'=>$SC['ProspectClient']['MailBody'],"id"=>"MailBody")); ?>
        </div>
    </div>
<div class="form-group">
        <label class="col-sm-2 control-label">Cover Body</label>

        <div class="col-sm-4">
        <?php 
        if($SC['ProspectClient']['IntroApprove']=='2' && $SC['ProspectClient']['Cover']!=$SC2['ProspectClientHis']['Cover']) { echo $style="<style>
      .nicEdit-main{
          color: red;
      }
  </style>"; } else { $style=""; }
        echo $this->Form->textarea('Cover',array('label' => false,'style'=>"width: 500px;",'placeholder'=>'Cover','rows'=>'20','value'=>$SC['ProspectClient']['Cover'],"id"=>"cover")); ?>
        </div>
</div>

    <div class="form-group has-feedback">
        <label class="col-sm-2 control-label">Attachment 1</label>
        <div class="col-sm-3">
            <?php echo $this->Form->file('attachment1', array('type'=>'file','label' => false, 'div' => false,'accept'=>"application/pdf")); ?>
        </div>
        <?php $FileUniqueAddress = $SC['ProspectClient']['Id']; if(!empty($SC['ProspectClient']['attachment1']) &&  file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment1'])) { ?>
        <label class="col-sm-2 control-label"><a href="<?php  echo $this->webroot."prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment1']; ?>" download="" >Attachment 1</a></label>
        <?php } ?>
        <?php if(!empty($SC['ProspectClient']['attachment1']) &&  file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment1'])) { ?>
        <label class="col-sm-2 control-label"><a href="" onclick="remove_attachment('<?php echo $FileUniqueAddress;?>','1')" >Remove Attachment 1</a></label>
        <?php } ?>
    </div>
    <div class="form-group has-feedback">
        <label class="col-sm-2 control-label">Attachment 2</label>
        <div class="col-sm-3">
            <?php echo $this->Form->file('attachment2', array('type'=>'file','label' => false, 'div' => false,'accept'=>"application/pdf")); ?>
        </div>
        <?php if(!empty($SC['ProspectClient']['attachment2']) && file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment2'])) { ?>
        <label class="col-sm-2 control-label"><a href="<?php  echo $this->webroot."prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment2']; ?>" download="">Attachment 2</a></label>
        <?php } ?>
        
        <?php if(!empty($SC['ProspectClient']['attachment2']) &&  file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment2'])) { ?>
        <label class="col-sm-2 control-label"><a href="" onclick="remove_attachment('<?php echo $FileUniqueAddress;?>','2')" >Remove Attachment 2</a></label>
        <?php } ?>
    </div>
    <div class="form-group has-feedback">
        <label class="col-sm-2 control-label">Attachment 3</label>
        <div class="col-sm-3">
            <?php echo $this->Form->file('attachment3', array('type'=>'file','label' => false, 'div' => false,'accept'=>"application/pdf")); ?>
        </div>
        <?php if(!empty($SC['ProspectClient']['attachment3']) && file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment3'])) { ?>
        <label class="col-sm-2 control-label"><a href="<?php  echo $this->webroot."prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment3']; ?>" download="">Attachment 3</a></label>
        <?php } ?>
        
         <?php if(!empty($SC['ProspectClient']['attachment3']) &&  file_exists("/var/www/html/ispark/app/webroot/prospect_file/$FileUniqueAddress/".$SC['ProspectClient']['attachment3'])) { ?>
        <label class="col-sm-2 control-label"><a href="" onclick="remove_attachment('<?php echo $FileUniqueAddress;?>','3')" >Remove Attachment 3</a></label>
        <?php } ?>
        
    </div>

<div class="clearfix"></div>
<div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-2">
        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-label-left">
                Save
        </button>
    </div>
    <?php if(!empty($send_mail)) { if(empty($from)) { ?>
        
        <div class="col-sm-2">
        <button type="submit" name="Send" value="Send" class="btn btn-primary btn-label-left">
                Send
        </button>
        </div>
    <?php }  else { ?>
        <button type="submit" name="Send" value="SendToCustomer" class="btn btn-primary btn-label-left">
                Send To Customer
        </button>
        
    <?php } } ?>
</div>
<?php 
echo $this->Form->input('Id',array('label' => false,'type'=>'hidden','value'=>$SC['ProspectClient']['Id'],'class'=>'form-control')); 
echo $this->Form->end(); ?>
</div>
  <script>
      function remove_attachment(Id,attachmentno)
      {
          $.post("remove_attachment",
            {
             Id:Id,
             attachmentno:'attachment'+attachmentno
            },
            function(data,status){
               if(data==1)
               {
                   alert("Attachment Has Been Removed")
                   location.reload();
               }
               else
               {
                   alert("Some Internal Error.Please Try After Some Time.")
               }
            }); 
      }
      </script>