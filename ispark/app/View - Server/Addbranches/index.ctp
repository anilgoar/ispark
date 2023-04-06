<div class="row">
    <div id="breadcrumb" class="col-xs-12">
            <a href="#" class="show-sidebar">
                    <i class="fa fa-bars"></i>
            </a>
            <ol class="breadcrumb pull-left">
            </ol>

    </div>
</div>

<div class="box-content">
				<h4 class="page-header">Add Branch</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Addbranch',array('class'=>'form-horizontal','action'=>'add')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_name',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Name')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Type</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_type',array('label' => false,'options'=>array('Client'=>'Client','Internally'=>'Internally'),'class'=>'form-control','empty'=>'Branch Type',"required"=>true)); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_code',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Code')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Address</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_address',array('label' => false,'class'=>'form-control','placeholder'=>'Branch Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">State  </label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state',array('label' => false,'class'=>'form-control','placeholder'=>'State')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">State  Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state_code',array('label' => false,'class'=>'form-control','placeholder'=>'State Code')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('tally_branch',array('label' => false,'class'=>'form-control','placeholder'=>'Tally Branch Name', 'value'=>'','required'=>true)); ?>
							
						</div>
					</div>
                                
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('tally_code',array('label' => false,'class'=>'form-control','placeholder'=>'Tally Code', 'value'=>'','required'=>true)); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Company Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('company_name',array('label' => false,'class'=>'form-control','placeholder'=>'Company Name', 'value'=>'','required'=>true)); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch State</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch_state',array('label' => false,'class'=>'form-control','placeholder'=>'Branch State', 'value'=>'','required'=>true)); ?>
							
						</div>
					</div>
                                <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tally Branch State Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('state_code',array('label' => false,'class'=>'form-control','placeholder'=>'State Code', 'value'=>'','required'=>true)); ?>
							
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
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>Branch Name</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=0; ?>
					<thead>
						<tr class="active">
							<td align="center"><b>Sr. No.</b></td>
							<td align="center"><b>Branch Name</b></td>
                                                        <td align="center"><b>Branch Address</b></td>
							<td align="center"><b>State</b></td>
                                                        <td align="center"><b>State Code</b></td>
                                                        <td align="center"><b>Edit</b></td>
                                                        <td align="center"><b>Status</b></td>
                                                        
						</tr>
					</thead>
                                        <tbody>

						<?php $i=1; foreach ($branch_master as $post){ ?>
						<tr class="">
							<td align="center"><?php echo $i++; ?></td>
                                                        <th align="center"><?php echo $post['Addbranch']['branch_name']; ?></th>
                                                        <td align="center"><?php echo $post['Addbranch']['branch_address']; ?></td>
                                                        <td align="center"><?php echo $post['Addbranch']['state']; ?></td>
                                                        <td align="center"><?php echo $post['Addbranch']['state_code']; ?></td>
							<td align="center"><?php echo $this->Html->link('Edit',array('controller'=>'Addbranches','action'=>'edit','?'=>array('id'=>$post['Addbranch']['id']),'full_base' => true)); ?></td>
                                                        <td align="center"><?php echo $post['Addbranch']['active']=='1'?'Active':'Deactive'; ?></td>
						</tr>
                                                <?php } ?>
						<?php unset($Addbranch); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

