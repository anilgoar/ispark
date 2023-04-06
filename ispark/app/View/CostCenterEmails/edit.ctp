<?php //print_r($cost_center_email); exit; ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Dashboard</a></li>
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

<div class="box-content">
    <h4 class="page-header">Edit Emails</h4>
        <?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('CostCenterEmail',array('class'=>'form-horizontal','action'=>'edit')); ?>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Cost Center</label>
                </div>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Process Manager</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->textArea('pm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['pm'])); ?>
                    </div>
                    <label class="col-sm-2 control-label">Admin</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->textArea('admin',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['admin'])); ?>
                    </div>
		</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Manager</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('bm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['bm'])); ?>
						</div>
                                                <label class="col-sm-2 control-label">Regional Manager</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('rm',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['rm'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Corp</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('corp',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['corp'])); ?>
						</div>
                                                <label class="col-sm-2 control-label">CEO</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('ceo',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['ceo'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Client Email</label>
						<div class="col-sm-4">
							<?php echo $this->Form->textArea('clientId',array('label' => false,'class'=>'form-control','placeholder'=>"Email ID'S",'value'=>$cost_center_email['CostCenterEmail']['clientId'])); ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
							<span><i class="fa fa-clock-o"></i></span>
								Save
							</button>
						</div>
					</div>
				<?php echo $this->Form->input('id',array('label'=>false,'type'=>'hidden','value'=>$cost_center_email['CostCenterEmail']['id']));  
                                echo $this->Form->end(); ?>
			</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
