
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>View Envelope</span>
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
							<td align="center"><b>Envelope Name</b></td>
                                                        <td align="center"><b>Branch From</b></td>
							<td align="center"><b>Branch To</b></td>
                                                        <td align="center"><b> Download PDF </b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($dis as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $post['dis']['EnvelopeName']; ?></td>
                                                        <td align="center"><?php echo $post['bm1']['branch_name']; ?></td>
                                                        <td align="center"><?php echo $post['bm2']['branch_name']; ?></td>
							<td align="center"><?php echo $this->Html->link('print',array('controller'=>'Dispatches','action'=>'view_download','?'=>array('Id'=>base64_encode($post['dis']['Id'])),'full_base' => true)); ?></td>
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