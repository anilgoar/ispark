<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Collection Payment Approve</span>
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
				<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
					<thead>
						<tr class="active">
							<td align="center"><b>Sr. No.</b></td>
							<td align="center"><b>company_name</b></td>
                                                        <td align="center"><b>Financial Year</b></td>
							<td align="center"><b>Payment Type</b></td>
                                                        <td align="center"><b>Cheque/RTGS No.</b></td>
                                                        <td align="center"><b>Bank Name</b></td>
                                                        <td align="center"><b>Paid Amount</b></td>
                                                        <td align="center"><b>Action</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($Data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><code><?php echo $post['Collection']['company_name']; ?></code></td>
                                                        <td align="center"><?php echo $post['Collection']['financial_year']; ?></td>
                                                        <td align="center"><?php echo $post['Collection']['pay_type']; ?></td>
                                                        <td align="center"><?php echo $post['Collection']['pay_no']; ?></td>
                                                        <td align="center"><?php echo $post['Collection']['bank_name']; ?></td>
                                                        <td align="center"><?php echo $post['Collection']['bank_name']; ?></td>
                                                        <td align="center"><?php echo $post['Collection']['pay_amount']; ?></td>
							<td align="center"><?php echo $this->Html->link('Approve',array('controller'=>'Collections','action'=>'edit_payment2','?'=>array('id'=>$post['Collection']['id']),'full_base' => true)); ?></td>
                                                        
						</tr>
						<?php endforeach; ?>
						<?php unset($Addbranch); ?>
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