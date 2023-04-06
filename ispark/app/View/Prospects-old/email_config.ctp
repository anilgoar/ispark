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
				<h4 class="page-header">Add Email</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>
                                        
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Host</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Host',array('label' => false,'class'=>'form-control','placeholder'=>'Fill Host Of The Domain','required'=>"")); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email Port</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Port',array('label' => false,'class'=>'form-control','placeholder'=>'Port No From Where Email Send','required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Id</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Id',array('label' => false,'class'=>'form-control','placeholder'=>'Email From Where Mail Send','required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Password</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Password',array('label' => false,'class'=>'form-control','placeholder'=>'Password From Where Mail Send','required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"></label>
                                                <div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Add
							</button>
						</div>
					</div>
                                
					<div class="clearfix"></div>
					<div class="form-group">
						
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Product List</span>
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
                                                        <td align="center"><b>Email Id</b></td>
                                                        <td align="center"><b>Host</b></td>
							<td align="center"><b>Port</b></td>
							<td align="center"><b>Edit</b></td>
                                                        <td align="center"><b>Status</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($email_master as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
							<td align="center"><?php echo $post['ProspectEmail']['Email_Id']; ?></td>
                                                        <td align="center"><?php echo $post['ProspectEmail']['Email_Host']; ?></td>
                                                        <td align="center"><?php echo $post['ProspectEmail']['Email_Port']; ?></td>
							<td align="center"><?php echo $this->Html->link('Edit',array('controller'=>'Prospects','action'=>'email_config_edit','?'=>array('Id'=>$post['ProspectEmail']['Id']),'full_base' => true)); ?></td>
                                                        <td align="center"><?php echo $post['ProspectEmail']['active']=='1'?'Active':'Deactive'; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($product_master); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js">
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>