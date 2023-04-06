<?php //print_r($data);?>
<?php  //print_r($particular);?>
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
					<!---	creating hide array for particulars table and hidden fields -->
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
                            <td><b>Ticket No</b></td>
							<td><b>Branch</b></td>
							<td><b>Process Name</b></td>
							<td><b>Ticket Desc</b></td>
                            <td><b>Status</b></td>
                           
						</tr>
						<tr align="center">
                                <td><?php echo $data['IssueTracker']['id'];?></td>
                                <td><?php echo $data['IssueTracker']['branch_name'];  ?></td>
                                <td><?php echo $data['IssueTracker']['process_name']; ?></td>
                                <td><?php echo $data['IssueTracker']['ticket_desc'];?></td>
                                <td><?php if($data['IssueTracker']['issue_status']==0){echo "open";}
												else if($data['IssueTracker']['issue_status']==1){echo "hold";}
												else if($data['IssueTracker']['issue_status']==2){echo "in-progress";}
												else if($data['IssueTracker']['issue_status']==3){echo "close";}
												else if($data['IssueTracker']['issue_status']==4){echo "Re-Open";}
								?>
							</td>
						0</tr>
				 </table>
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
                            <td>Sr. No.</td>
							<td>Priority</td>
							<td>Requirement Type</td>
							<td>Requieremt Desc</td>
                            <td>File</td>
                            <td>Status</td>
                            <td>Remarks</td>
                            <td>Action</td>
						</tr>
						
                        <?php $i = 1;  foreach($particular as $post):?>
                        <tr align="center">
                        <?php echo $this->Form->create('Issue',array('action'=>'view')); ?>
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
                            
                             <td><?php 
									$files=explode(',',$post['IssueParticular']['attach_files']);
									
									if(isset($files))
									{
										foreach($files as $links) : 
									?>
										&nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>">
										<?php echo $links; ?> </a>
									<?php	 endforeach;
									}
								?></td>
                                <td><?php if($post['IssueParticular']['issue_status']=='3') { echo $this->Form->input('Issue.'.$post['IssueParticular']['id'].'.issue_status',
													array('label'=>false,'options'=>array('0'=>'Re-open'),'empty'=>"Select Status",'class' => 'form-control'));} 
												else {if($post['IssueParticular']['issue_status']=='0') echo "Open";if($post['IssueParticular']['issue_status']=='1') echo "Hold";
											if($post['IssueParticular']['issue_status']=='2') echo "In - Progress";} ?></td>
                                <td><?php echo  $this->Form->input('remarks',array('label'=>false,'value'=>$post['IssueParticular']['remarks'])) ;?></td>
                                
                                <td> <?php if($post['IssueParticular']['issue_status']=='3') { ?>
                                <input type="submit" value="Submit"  action="update_issue_status" class="btn btn-info" /> 
								<?php } ?></td>
                            </tr>
                            <?php echo $this->Form->end(); ?>
                          <?php  endforeach; ?>
                </table>
                 <?php echo $this->Html->link('Back',array('action'=>'View_issue'),array('class'=>'btn btn-primary')); ?>
			  </div>
			</div>
		</div>
	</div>
            