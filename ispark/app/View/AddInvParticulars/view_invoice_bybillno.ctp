<?php //print_r($res); ?>
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','active'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Invoice No.</th>
							<th>Amount</th>
							<th>PO No.</th>
							<th> GRN </th>
							<th>Description</th>
							<th colspan="2">Action</th>
						</tr>
						<?php if(isset($tbl_invoice))
						{ foreach($tbl_invoice as $post):?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
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
							</td>
						</tr>
						
						<?php endforeach; } unset($InitialInvoice); ?>
					</tbody>
				</table>						
</div>