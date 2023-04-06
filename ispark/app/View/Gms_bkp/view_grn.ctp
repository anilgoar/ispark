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
					
					<span>View GRN Vendor</span>
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
                                                        <td>Grn No. </td>
							<td>User </td>
							<td>Company</td>
                                                        <td>Year</td>
                                                        <td>Month</td>
                                                        <td>Vendor</td>
                                                        <td>Amount</td>
                                                        <td>View</td>
                                                        
						</tr>
                                        </thead>
                                        <tbody>
						<?php foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td><?php echo $i; ?></td>
                                                        <td><code><?php echo $post['eemApp']['GrnNo']; ?></code></td>
							<td><code><?php echo $post['tu']['username']; ?></code></td>
							<td><code><?php echo $post['cm']['company_name']; ?></code></td>
                                                        <td><code><?php echo $post['eemApp']['FinanceYear']; ?></code></td>
                                                        <td><code><?php echo $post['eemApp']['FinanceMonth']; ?></code></td>
                                                        <td><code><?php echo $post['vm']['vendor']; ?></code></td>
                                                        <td><code><?php echo $post['eemApp']['Amount']; ?></code></td>
							<td><code ><?php echo $this->Html->link('View',array('controller'=>'Gms','action'=>'view_grn_tmp','?'=>array('Id'=>$post['eemApp']['Id']),'full_base' => true)); ?></code></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($data); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>