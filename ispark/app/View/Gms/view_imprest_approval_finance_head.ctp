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
            <a href="/ispark/Menuisps/sub?AX=NjI=" class="btn btn-info" >Back</a>
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>Reject Imprest Approval (Finance Head)</span>
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
							<td>User </td>
							<td>Company</td>
                                                        <td>Reject At</td>
                                                        <td>Reject Remarks</td>
                                                        <td>Reject By</td>
                                                        <td>Amount</td>
                                                        <td>Approve</td>
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
							<td><code><?php echo $post['tu']['username']; ?></td>
							<td><code><?php echo $post['cm']['company_name']; ?></td>
                                                        <td><?php  if($post['eemApp']['Reject']=='2') { echo "Reject From Second Level";} else  if($post['eemApp']['Reject']=='0') { echo "Reject From First Level";} else  if($post['eemApp']['Reject']=='4') { echo "Approved From Process Head";} ?></td>
                                                        <td><font color="red"><?php echo $post['eemApp']['RejectRemarks']; ?></font></td>
                                                        <td><code><?php echo $post['tuR']['RejectBy']; ?></td>
                                                        <td><code><?php echo $post['eemApp']['Amount']; ?></td>
                                                        <?php if($post['eemApp']['Reject']=='4') { ?>
							<td><code ><?php echo $this->Html->link('View/Approve',array('controller'=>'Gms','action'=>'edit_imprest_approval_finance_head','?'=>array('Id'=>$post['eemApp']['Id'],'action'=>'approve'),'full_base' => true)); ?></code></td>
                                                        <?php } ?>
                                                        <?php if($post['eemApp']['Reject']=='0' || $post['eemApp']['Reject']=='2' || $post['eemApp']['Reject']=='3') { ?>
							<td><code ><?php echo $this->Html->link('Process Head Approval Waiting',array('controller'=>'Gms','action'=>'edit_imprest_approval_finance_head','?'=>array('Id'=>$post['eemApp']['Id'],'action'=>'view'),'full_base' => true)); ?></code></td>
                                                        <?php } ?>
						</tr>
						<?php endforeach; ?>
						<?php unset($data); ?>
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