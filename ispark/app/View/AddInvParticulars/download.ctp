<?php //print_r($res); ?>

<div id="nn">
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','active'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Invoice No.</th>
							<th>Amount</th>
							<th>PO No.</th>
							<th>Description</th>
							<th colspan="2">Action</th>
						</tr>
						<?php foreach ($tbl_invoice as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							<?php $id= $post['InitialInvoice']['id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['InitialInvoice']['branch_name']; ?></td>
							<td><?php echo $post['InitialInvoice']['bill_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['total']; ?></td>
							<td><?php echo $post['InitialInvoice']['po_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['invoiceDescription']; ?></td>
							<td><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_pdf','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?>
							<td><?php echo $this->Html->link(__('Letter Head'), array('controller'=>'InitialInvoices','action' => 'view_pdf1','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						
</div>