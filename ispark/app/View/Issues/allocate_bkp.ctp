
<?php

//print_r($particular);

?>
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
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>View Issue Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
            <h1><?php echo $this->Session->flash(); ?></h1>
					<!---	creating hide array for particulars table and hidden fields -->
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
                            <td>Ticket No</td>
							<td>Branch</td>
							<td>Process Name</td>
							<td>Ticket Desc</td>
                            <td>Allocate Date</td>
                            <td>Status</td>
                          
						</tr>
						<tr align="center">
                            <td><?php echo $data['IssueTracker']['id'];?></td>
							<td><?php echo $data['IssueTracker']['branch_name'];  ?></td>
							<td><?php echo $data['IssueTracker']['process_name']; ?></td>
							<td><?php echo $data['IssueTracker']['ticket_desc'];?></td>
                             <td><?php echo $data['IssueTracker']['createdate']; ?></td>
							<td><?php if($data['IssueTracker']['issue_status']==0){echo "open";}
							else if($data['IssueTracker']['issue_status']==1) {echo "Reopen";}
							?></td>
							
						</tr>
				</table>
                <table class="table-striped table-bordered table-hover table-heading no-border-bottom">
                <tr align="center">
              
                            <td>Sr. No.</td>
							<td>Priority</td>
							<td>Requirement Type</td>
							<td>Requieremt Desc</td>
                            <td>Process Type</td>
                            <td>Start Date</td>
                            <td>Close Date</td>
                            <td>Remarks</td>
                            <td>File</td>
                            <td>Allocated Executive</td>
                            <td>Action</td>
                </tr>
                      <?php
						$i = 1; 
						foreach($particular as $post): echo $this->Form->create('Issues',array('action'=>'alloted'));
						 ?>
                        <tr align="center" >
                            <?php /*?><td> <?php
								?>
                            <input name="issue[]" value="<?php echo $post['IssueParticular']['id']; ?>" type="checkbox" onclick="return select_Date()" /></td><?php */?>
                            <td><?php echo $i++;?></td>
							<td><?php  
							if($post['IssueParticular']['priority']==0){echo "Low";}
						    else if($post['IssueParticular']['priority']==1){echo "Normal";}
						    else {echo "Urgent";} ?></td>
                            
							<td><?php if($post['IssueParticular']['requirment_type']=='0'){echo "Upgrade";} 
							else if($post['IssueParticular']['requirment_type']=='1'){echo "New";}
							else if($post['IssueParticular']['requirment_type']=='2'){echo "Modification";}
							else{echo "Error";}
							?></td>
                            
							<td><?php echo $post['IssueParticular']['requirement_desc'];?></td>
                            
                           
                           <td><?php echo $this->Form->input('process_type'.$post['IssueParticular']['id'] ,array('label' =>false,'options'=>array('Inbound'=>'Inbound','Outbound'=>'Outbound'),'empty'=>'Select Type','class' => 'form-control')); ?></td>

<!--                            <td><?php //echo $this->Form->input('ToDate'.$post['IssueParticular']['id'], array('label'=>false,'class'=>'form-control','placeholder'=>'Date','onClick'=>"displayDatePicker('data[Issues][ToDate".$post['IssueParticular']['id']."]');",'required'=>true)); ?></td>

-->							 <td><?php echo $this->Form->input('ToDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Date','id'=>'callbackdate','readonly'=>""
									 ,'onClick'=>"javascript:NewCssCal ('callbackdate','ddMMyyyy','arrow',false,'',true)" )); ?></td>
                                     
                                     <td><?php echo $this->Form->input('FromDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Date','id'=>'callbackdate2','readonly'=>""
									 ,'onClick'=>"javascript:NewCssCal ('callbackdate2','ddMMyyyy','arrow',false,'',true)" )); ?></td>

                            
                             <td><?php echo $post['IssueParticular']['remarks'];?></td>
                            <td><?php 
									$files=explode(',',$post['IssueParticular']['attach_files']);
									
									if(isset($files))
									{
										foreach($files as $links) : 
									?>
										&nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>"><?php echo $links; ?> </a>
									<?php	 endforeach;
									}
								?>
                               </td>
                               
                               <td><?php  
							   $arr=array();
							   $key = array_keys($ITUsers);
							  
							   for($i=0;$i<count($key);$i++)
							   {
								  $arr[base64_encode($post['IssueParticular']['id'].','.$key[$i].",".$post['IssueParticular']['ticket_no'])]=$ITUsers[$key[$i]];   
							   }
									echo $this->Form->input('user',array('label'=>false,'options'=>$arr,'class'=>'form-control','onClick'=>'')); ?>
                                    
                                  <?php //echo $this->Js->submit('Assign', array('url' => array('controller'=>'Issues','action' => 'alloted'),'complete'=>'refresh()','update' => '#nn','class'=>'btn btn-success btn-label-left')); ?>

                          </td>
                               <td> <input type="submit"  value="Submit" class="btn btn-info" /> <?php echo $this->Form->end(); ?>
                               </td>
<?php /*?>                               
                               <td><?php echo $post['IssueParticular']['allocate_to_id1'];?></td>
                               <td><?php echo $post['IssueParticular']['allocate_to_id2'];?></td>
<?php */?>                           </tr>
                          <?php  endforeach; ?>
                       <?php /*?> <tr>
                        <td colspan = "9">
                           <div class="form-group has-success has-feedback">  
                           		<label class="col-sm-2 control-label">Select Executive</label>   
                           		<div class="col-sm-2">                  	
                           		<?php  
									echo $this->Form->input('user',array('label'=>false,'options'=>$ITUsers,'multiple'=>'multiple','class'=>'form-control','onClick'=>'return get_checkbox()')); ?>
                          		</div>
                            	<div class="col-sm-4">
                                
                                <?php echo $this->Js->submit('Assign', array('url' => array('controller'=>'Issues','action' => 'alloted'),'complete'=>'refresh()','update' => '#nn','class'=>'btn btn-success btn-label-left')); ?>
                            	</div>
                            </div>
                       </td>
                       </tr>       <?php */?>                 
					      <tr>
                       <td colspan="9" align="left">                   
                       		<div class="form-group has-success has-feedback">  
                           		<label class="col-sm-2 control-label"></label>   
                           		<div class="col-sm-4">
                                <?php echo $this->Html->link('Back',array('action'=>'issue_allocate'),array('class'=>'btn btn-primary')); ?>
                 					<!--<button class="btn btn-success btn-label-left">Submit</button>-->
                                    
                    </div>
					   </div>

                </table>
				<?php /*?><table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
                            <td>Select</td>
                            <td>Sr. No.</td>
							<td>Priority</td>
							<td>Requirement Type</td>
							<td>Requieremt Desc</td>
                            <td>Remarks</td>
                            <td>File</td>
                            <td>Allocated Executive (1)</td>
                            <td>Allocated Executive (2)</td>
                       </tr>
                        <?php
						$i = 1; 
						foreach($particular as $post):
						 ?>
                        <tr align="center">
                            <td> <?php
								?>
                            <input name="issue[]" value="<?php echo $post['IssueParticular']['id']; ?>" type="checkbox" onclick="return select_Date()" /></td>
                            <td><?php echo $i++;?></td>
							<td><?php  
							if($post['IssueParticular']['priority']==0){echo "Low";}
						    else if($post['IssueParticular']['priority']==1){echo "Normal";}
						    else {echo "Urgent";} ?></td>
							<td><?php if($post['IssueParticular']['requirment_type']=='0'){echo "Upgrade";} 
							else if($post['IssueParticular']['requirment_type']=='1'){echo "New";}
							else if($post['IssueParticular']['requirment_type']=='2'){echo "Modification";}
							else{echo "Error";}
							?></td>
							<td><?php echo $post['IssueParticular']['requirement_desc'];?></td>
                            <td><?php echo $post['IssueParticular']['remarks'];?></td>
                            <td><?php 
									$files=explode(',',$post['IssueParticular']['attach_files']);
									
									if(isset($files))
									{
										foreach($files as $links) : 
									?>
										&nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>"><?php echo $links; ?> </a>
									<?php	 endforeach;
									}
								?>
                               </td>
                               
                               <td><?php echo $post['IssueParticular']['allocate_to_id1'];?></td>
                               <td><?php echo $post['IssueParticular']['allocate_to_id2'];?></td>
                           </tr>
                          <?php  endforeach; ?>
                        <tr>
                        <td colspan = "9">
                           <div class="form-group has-success has-feedback">  
                           		<label class="col-sm-2 control-label">Select Executive</label>   
                           		<div class="col-sm-2">                  	
                           		<?php  
									echo $this->Form->input('user',array('label'=>false,'options'=>$ITUsers,'multiple'=>'multiple','class'=>'form-control','onClick'=>'return get_checkbox()')); ?>
                          		</div>
                            	<div class="col-sm-4">
                                
                                <?php echo $this->Js->submit('Assign', array('url' => array('controller'=>'Issues','action' => 'alloted'),'complete'=>'refresh()','update' => '#nn','class'=>'btn btn-success btn-label-left')); ?>
                            	</div>
                            </div>
                       </td>
                       </tr>                        
					      <tr>
                       <td colspan="9" align="left">                   
                       		<div class="form-group has-success has-feedback">  
                           		<label class="col-sm-2 control-label"></label>   
                           		<div class="col-sm-4">
                                <?php echo $this->Html->link('Back',array('action'=>'issue_allocate'),array('class'=>'btn btn-primary')); ?>
                 					<!--<button class="btn btn-success btn-label-left">Submit</button>-->
                                    
                    </div>
					   </div>
                       </table><?php */?>
                       <!--<div id ="nn"></div>-->
					</div>
				</div>
			</div>
            <?php 

			?>
            
		<?php echo $this->Js->writeBuffer(); ?>	