<?php //print_r($tbl_invoice); ?>

			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active',''); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Bill No.</th>
							<th>Amount</th>
							<th>PO No.</th>
							<th>GRN</th>
							<th>Description</th>
							<th>Action</th>
						</tr>
						<?php foreach ($tbl_invoice as $post): ?>
						<tr class="<?php  echo $case[$i%3]; $i++;?>" align="center">
							<?php $id= $post['ti']['id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['ti']['branch_name']; ?></td>
							<td><?php echo $post['ti']['bill_no']; ?></td>
							<td><?php echo $post['ti']['total']; ?></td>
							<td><?php echo $post['ti']['po_no']; ?></td>
							<td><?php echo $post['ti']['grn']; ?></td>
							<td><?php echo $post['ti']['invoiceDescription']; ?></td>
							<td>
								<?php echo $this->Html->link('Edit',
							array('controller'=>'InitialInvoices','action'=>'edit_invoice','?'=>array('id'=>$id),'full_base' => true)); ?>
															<?php echo $this->Html->link('Reject',
							array('controller'=>'InitialInvoices','action'=>'reject_invoice','?'=>array('id'=>$id),'full_base' => true)); ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						
