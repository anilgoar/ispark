<?php //print_r($result); ?>
<table>
	<?php  $i = 0; $idx ="";
		foreach ($result as $post): ?>
		<?php $idx.=$post['TMPCollectionParticulars']['id'].','; ?>
		<tr <?php   $i++;?>>
		<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_no',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_no'],'class'=>'form-control')); ?></td>
                            
			<td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_amount',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_amount'],'class'=>'form-control','readonly'=>true,'required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

			<td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.status',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['status'],'class'=>'form-control','required'=>true,'readOnly'=>'true','onBlur'=>'validate_colleciton_amount();')); ?></td>
                            
			<td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_passed',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_passed'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
							
                          <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.tds_ded',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['tds_ded'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

                          <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.net_amount',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['net_amount'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

                          <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.deduction',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['deduction'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

                            
                           <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.remarks',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['remarks'],'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

			<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['TMPCollectionParticulars']['id']; ?>" onClick ="return deletesCollection(this.value)">Delete</button> </td>
				</tr>
				<?php endforeach; ?><?php unset($TMPCollectionParticulars); ?>
				<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
</table>