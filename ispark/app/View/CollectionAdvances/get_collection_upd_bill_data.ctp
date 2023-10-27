<?php //print_r($result); ?>
<table>
	<?php  $i = 0; $idx ="";
		foreach ($result as $post): ?>
		<?php $idx.=$post['CollectionParticularsUpdate']['id'].','; ?>
		<tr <?php   $i++;?>>
		<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.bill_no',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_no'],'class'=>'form-control')); ?></td>
                            
			<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.bill_amount',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_amount'],'class'=>'form-control','readonly'=>true,'required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

			<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.status',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['status'],'class'=>'form-control','required'=>true,'readOnly'=>'true','onBlur'=>'validate_colleciton_amount();')); ?></td>
                            
			<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.bill_passed',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_passed'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
							
                          <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.tds_ded',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['tds_ded'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

                          <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.net_amount',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['net_amount'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

                          <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.deduction',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['deduction'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

                            
                           <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['id'].'.remarks',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['remarks'],'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

			<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['CollectionParticularsUpdate']['id']; ?>" onClick ="return deletesCollection(this.value)">Delete</button> </td>
				</tr>
				<?php endforeach; ?><?php unset($CollectionParticularsUpdate); ?>
				<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
</table>