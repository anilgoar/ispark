<?php //print_r($result); ?>
<table>
						<?php  $i = 0; $idx ="";
						foreach ($result as $post): ?>
							<?php $idx.=$post['OtherTMPDeduction']['id'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('OtherTMPDeduction.'.$post['OtherTMPDeduction']['id'].'.other_deduction',array('label'=>false,'value'=>$post['OtherTMPDeduction']['other_deduction'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)')); ?></td>
                            
							<td><?php echo $this->Form->input('OtherTMPDeduction.'.$post['OtherTMPDeduction']['id'].'.other_remarks',array('label'=>false,'value'=>$post['OtherTMPDeduction']['other_remarks'],'class'=>'form-control')); ?></td>
                            
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['OtherTMPDeduction']['id']; ?>" onClick ="return deletesDeduction(this.value)">Delete</button> </td>
							</tr>
						<?php endforeach; ?><?php unset($OtherTMPDeduction); ?>
						<?php echo $this->Form->input('a.idx2',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx2')); ?>
</table>                        
