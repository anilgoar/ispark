<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>View & Approve Vendor</span>
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
                            <h4 style="color:green"><?php echo $this->Session->flash(); ?> </h4>
				<table class="table  table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
				<?php $case=array('primary',''); $i=0; ?>
					<thead>
						<tr class="active">
							<td>Sr. No.</td>
							<td>Vendor Name</td>
							<td>State</td>
                                                        <td>Reject Remarks</td>
                                                        <td>Action</td>
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($vendor_arr as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $post['VendorMaster']['vendor']; ?></td>
							<td><?php echo $post['VendorMaster']['state']; ?></td>
                                                        <td><?php if(!empty($post['VendorMaster']['RejectRemarks']))  echo '<font color="red">'.$post['VendorMaster']['RejectRemarks'].'</font>'; ?></td>
                                                        <td><code><?php if(!empty($post['VendorMaster']['RejectRemarks'])) echo $this->Html->link('Edit',array('controller'=>'Imprests','action'=>'tmp_edit_branch_vendor','?'=>array('Id'=>$post['VendorMaster']['Id']),'full_base' => true)); ?></code></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($Addclient); ?>
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