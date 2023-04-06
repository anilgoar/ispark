<?php //print_r($data); ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>
<?php echo $this->Form->create('Issues',array('action'=>'submit','enctype'=>'multipart/form-data')); ?>


			<div class="box-content">
					<h4 class="page-header">
					<?php //echo $this->Session->flash(); ?>
					</h4>
                        
                     	
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-4">
								<?php $datat =  array();
									foreach($branch_master as $post):
									$datat[$post['Addbranch']['branch_name']] = $post['Addbranch']['branch_name'];
									endforeach; 
								?><?php unset($Addbranch); ?>
						
							<?php echo $this->Form->input('branch_name', array('label' => false,'options' => $datat,'value'=>$data[0],'empty' => 'Select Branch', 'div' => false,'class'=>'form-control','onChange' => 'get_process23(this.value)')); ?>
						</div>

						<label class="col-sm-2 control-label">Process Name</label>
						<div class="col-sm-4"><div id="mm">
                        <?php  $proc = array($data[1]=>$data[1]);
						  						?>
							<?php	echo $this->Form->input('process_name', array('label'=>false,'class'=>'form-control','options' => $proc,'value'=>$data[1], 'required'=>true )); ?>
                            </div>
						</div>
					
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Ticket No</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('ticket_no', array('label'=>false,'class'=>'form-control','placeholder' => 'Ticket No','required'=>true,'readOnly'=>true,'value'=>'0')); ?>
						</div>
						<label class="col-sm-2 control-label">Ticket Description</label>
						<div class="col-sm-4">
							<?php	echo $this->Form->input('ticket_desc', array('label'=>false,'class'=>'form-control','text'=>'area','value'=>isset($data[2])&& $data[2] !='0'?$data[2]:'','placeholder' => 'Description','required'=>true)); ?>
							</div>
						</div>
                             
   
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-content">
				<h4 class="page-header">Issue Particulars</h4>
				
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
                <?php $i = 1;
						foreach($tmp_issue as $post):
				if($i==1){echo $this->Form->input('process_desc',array('type'=> "hidden"));}
				?>
                		<th><?php echo $i++; ?></th>
                        
						<th><?php echo $this->Form->input('TmpIssueParticular.'.$post['TmpIssueParticular']['id'].'.priority' ,array('label' =>false,'placeholder' => 'Priority','options'=>array('Low','Normal','Urgent'),'selected'=>$post['TmpIssueParticular']['priority'],'class' => 'form-control')); ?></th>
                        
						<th><?php echo $this->Form->input('TmpIssueParticular.'.$post['TmpIssueParticular']['id'].'.requirment_type' ,array('label' =>false,'options'=>array('Upgrade','New','Modification','Error'),'selected'=>$post['TmpIssueParticular']['requirment_type'],'class' => 'form-control')); ?></th>
                        
						<th><?php echo $this->Form->input('TmpIssueParticular.'.$post['TmpIssueParticular']['id'].'.requirement_desc' ,array('label' =>false,'value' => $post['TmpIssueParticular']['requirement_desc'],'class' => 'form-control')); ?></th>
                        
                        <th><?php echo $this->Form->file('Files.'.$post['TmpIssueParticular']['id'].'.attach_files.', array('label'=>false,'type' => 'file','class' => 'form-control','multiple')); ?></th>
                        
                        <th><?php echo $this->Form->input('TmpIssueParticular.'.$post['TmpIssueParticular']['id'].'.issue_status',array('label'=>false,'options'=>array('Open','Re-open','In-progress','On-hold'),'selected'=>$post['TmpIssueParticular']['issue_status'],'class' => 'form-control')); ?></th>
                        
						<th><?php echo $this->Form->input('TmpIssueParticular.'.$post['TmpIssueParticular']['id'].'.remarks' ,array('label' =>false,'value' => $post['TmpIssueParticular']['remarks'],'class' => 'form-control')); ?></th>
                        
                        <td><?php echo $this->Html->link('Delete',array('controller'=>'Issues','action'=>'delete','?'=>array('id'=>$post['TmpIssueParticular']['id']),'full_base' => true)); ?>                                    
                </td>
                         
					</tr>                   
					
				
					<?php
						endforeach;
                	?>
                    </table>
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr>
						<th>Sr. No.</th>
						<th>Priority.</th>
						<th>Requirement Type</th>
						<th>Requirement Detail</th>
						<th>Attach Files</th>
						<th>Status</th>
                        <th>Remarks</th>
                       	<th>Action</th>
					</tr>
					
					<tr>
						<th>1.</th>
                        
						<td><?php echo $this->Form->input('Particular.priority' ,array('label' =>false,'placeholder' => 'Priority','options'=>array('Low','Normal','Urgent'),'empty'=>'Select Priority','class' => 'form-control')); ?></td>
						<td><?php echo $this->Form->input('Particular.requirement_type' ,array('label' =>false,'options'=>array('Upgrade','New','Modification','Error'),'empty'=>'Select type','class' => 'form-control')); ?></td>
						<td><?php echo $this->Form->input('Particular.requirement_desc' ,array('type' => 'textarea', 'rows' => '1','label' =>false,'placeholder' => 'Description','class' => 'form-control')); ?></td>
						<td> <?php //echo $this->Form->file('Particular.attach_files', array('label'=>false,'type' => 'file','class' => 'form-control','multiple')); ?></td>
                     	<td><?php echo $this->Form->input('Particular.status' ,array('label' =>false,'placeholder' => 'Status','options'=>array('Open'),'class' => 'form-control')); ?></td>
                        <td><?php echo $this->Form->input('Particular.remarks',array('label'=>false,'placeholder'=>'Remarks','class' => 'form-control')); ?></td>
                        
						    <th> <input type="button" onclick="AddIssue(),process_des()" value="Add" class="btn btn-success" /></th>
					</tr>                   
				</table>	
                <div>
                     
                     <input type="submit" value="submit" class="btn btn-info" onClick="return issue_add()"  />
                        
                </div>
                
               
				</div>
			</div>
		</div>
	
    <?php echo $this->Form->end(); ?>