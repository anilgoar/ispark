<?php //print_r($res); ?>

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
					<tbody>
						<tr class="active" align="center">
							<td>Sr. No.</td>
							<td>Branch Name</td>
							<td>Invoice No.</td>
							<td>Amount</td>
							<td>PO No.</td>
							<td>Description</td>
							<td colspan="2">Action</td>
						</tr>
						<?php foreach ($tbl_invoice as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							<?php $id= $post['InitialInvoice']['id']; ?>
							<td><code><?php echo $i; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['branch_name']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['bill_no']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['total']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['po_no']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['invoiceDescription']; ?></code></td>
							<td><code><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_pdf','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?></code>
							<td><code><?php echo $this->Html->link(__('Click here for Letter Head'), array('controller'=>'InitialInvoices','action' => 'view_pdf1','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?></code>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						
