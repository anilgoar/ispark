
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
		<?php $a=1; $To=0; ?>
		<tbody>
			<tr class="active">
				<td>Sr. No.</td>
				<td>Particulars</td>
				<td>Qty</td>
				<td>Rate</td>
				<td>Amount</td>
			</tr>
			<?php  foreach ($inv_particulars as $post): ?>
			<tr class="<?php  echo $case[$i%4]; $i++;?>">
				<td><?php echo $a++; ?></td>
				<td><?php echo $post['AddInvDeductParticular']['particulars']; ?></td>
				<td><?php echo $post['AddInvDeductParticular']['rate']; ?></td>
				<td><?php echo $post['AddInvDeductParticular']['qty']; ?></td>
				<td><?php echo $post['AddInvDeductParticular']['amount']; ?></td>
				<?php $To=$To+$post['AddInvDeductParticular']['amount']; ?>
			</tr>
			<?php endforeach; ?>
			<?php	echo $this->Form->input('ded_total', array('label'=>false,'value'=>$To,'id'=>'ded_total','type'=>'hidden')); ?>
			<?php unset($AddInvDeductParticular); ?>
		</tbody>
</table>