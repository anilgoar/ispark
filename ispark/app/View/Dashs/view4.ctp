                                        

<?php
?>
                                            <label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-3">
								<?php
									foreach($branch_master as $post):
									$data[$post['Addbranch']['branch_name']] = $post['Addbranch']['branch_name'];
									endforeach; 
                                                                        
                                                                       
								?>
							<?php	echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Branch','selected' => $payment_master['3'],'required'=>true)); ?>
						</div>