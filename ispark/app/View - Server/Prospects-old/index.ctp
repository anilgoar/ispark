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
				<h4 class="page-header">Add Product</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal','action'=>'add')); ?>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Company</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Company',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$company_master,'required'=>"")); ?>
						</div>
                                                
					</div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Branch',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$branch_master,'required'=>"")); ?>
						</div>
                                                
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Product Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('ProductName',array('label' => false,'class'=>'form-control','placeholder'=>'Product Name','required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Lead Source</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('LeadSource',array('label' => false,'class'=>'form-control','placeholder'=>'Lead Source','required'=>"")); ?>
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
                                                        <td align="center"><b>Company</b></td>
                                                        <td align="center"><b>Branch</b></td>
							<td align="center"><b>Product Name</b></td>
							<td align="center"><b>Edit</b></td>
                                                        <td align="center"><b>Status</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($product_master as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
							<td align="center"><?php echo $post['0']['company']; ?></td>
                                                        <td align="center"><?php echo $post['0']['branch']; ?></td>
                                                        <td align="center"><?php echo $post['ProspectProduct']['ProductName']; ?></td>
							<td align="center"><?php echo $this->Html->link('Edit',array('controller'=>'Prospects','action'=>'edit','?'=>array('Id'=>$post['ProspectProduct']['Id']),'full_base' => true)); ?></td>
                                                        <td align="center"><?php echo $post['ProspectProduct']['active']=='1'?'Active':'Deactive'; ?></td>
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
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>