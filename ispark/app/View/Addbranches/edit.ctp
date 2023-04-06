<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><?php echo $this->Html->link('Dashboard',array('controller'=>'Provisions','action'=>'dashboard')); ?></li>
			
		</ol>
		
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">Edit Branch</h4>
								
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Addbranch',array('class'=>'form-horizontal','action'=>'edit')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_name',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Name', 'value'=>$branch_master['Addbranch']['branch_name'])); ?>
							
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Type</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_type',array('label' => false,'options'=>array('Client'=>'Client','Internally'=>'Internally'),'value'=>$branch_master['Addbranch']['branch_type'],'class'=>'form-control','empty'=>'Branch Type',"required"=>true)); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_code',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code','value'=>$branch_master['Addbranch']['branch_code'])); ?>
							<?php echo $this->Form->input('branch_id',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code','value'=>$branch_master['Addbranch']['id'],'type'=>'hidden')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Address</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_address',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code','value'=>$branch_master['Addbranch']['branch_address'])); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">State</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state',array('label' => false,'class'=>'form-control','placeholder'=>'State','value'=>$branch_master['Addbranch']['state'])); ?>
							
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
						<?php echo $this->Form->input('active',array('label' => false,'class'=>'form-control','options'=>array('1'=>'Active','0'=>'Deactive'),'value'=>$branch_master['Addbranch']['active'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('tally_branch',array('label' => false,'class'=>'form-control','placeholder'=>'Tally Branch Name', 'value'=>$branch_master['Addbranch']['tally_branch'])); ?>
							
						</div>
					</div>
                                
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('tally_code',array('label' => false,'class'=>'form-control','placeholder'=>'Tally Code', 'value'=>$branch_master['Addbranch']['tally_code'])); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Company Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('company_name',array('label' => false,'class'=>'form-control','placeholder'=>'Company Name', 'value'=>$branch_master['Addbranch']['company_name'])); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch State</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_state',array('label' => false,'class'=>'form-control','placeholder'=>'Branch State', 'value'=>$branch_master['Addbranch']['branch_state'])); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch State Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state_code',array('label' => false,'class'=>'form-control','placeholder'=>'State Code', 'value'=>$branch_master['Addbranch']['state_code'])); ?>
							
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
							
								Save
							</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
