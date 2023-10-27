<?php ?>

<script>
  function get_det(GrnNo)
  {
      $.post("get_grn",{GrnNo:GrnNo},function(data){
        $('#disp_detail').html(data);});
  }
</script>



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

<div class="box-content">
    <h4 class="page-header">Delete GRN Request</h4>
				
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Gms',array('class'=>'form-horizontal')); ?>
        <div class="form-group">
                <label class="col-sm-2 control-label">Grn No.</label>
                <div class="col-sm-3">
                        <?php echo $this->Form->input('grn_no',array('label'=>false,'placeholder'=>'GRN No.','onblur'=>"get_det(this.value)",'required'=>true,'class'=>'form-control')); ?>
                </div>
                <label class="col-sm-2 control-label">Remarks</label>
                <div class="col-sm-3">
                        <?php echo $this->Form->textArea('remarks',array('label'=>false,'placeholder'=>'Remarks For Deletion','required'=>true,'class'=>'form-control')); ?>
                </div>
        </div>
        
        
        
    
    <div id="disp_detail" style="overflow:auto"></div>
	<div class="clearfix"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
                <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary btn-label-left">
                                Delete Request 
                        </button>
                    <a href="/ispark/Menuisps/sub?AX=NjA=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
                </div>
        </div>
<?php echo $this->Form->end(); ?>
</div>
