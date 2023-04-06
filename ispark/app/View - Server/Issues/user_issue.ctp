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
				<?php $case=array('classic',''); $i=0; ?>
					<tbody>
						<tr>
							
                            <td align="center">Ticket No.</td>
                            <td align="center">Process Name</td>
                            <td align="center">Branch</td>
                            <td align="center">Description</td>
                            <td align="center" >Create Date</td>
                            
                            
						</tr>
                        
						
						<tr>
							<td align="center">
						<?php echo $main['IssueTracker']['id']; ?>
						</td>														
                            <td align="center"><?php echo $main['IssueTracker']['process_name']; ?></td>
                            <td align="center"><?php echo $main['IssueTracker']['branch_name']; ?></td>
                            <td align="center"><?php echo $main['IssueTracker']['ticket_desc']; ?></td>
                            <td align="center"><?php echo $main['IssueTracker']['createdate']; ?></td>
													</tr>
						
						<?php unset($Issues); ?>
					</tbody>
				</table>
                  <?php echo $this->Form->create('Issues',array('action'=>'update_issue_status')); ?>
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
                            

							<td>Description</td>
                            <td>Priority</td>
							<td>Req Type</td>
							<td>Req Desc</td>
                            <td>Process Type </td>
                            <td>StartDate</td>
                            <td>EndDate</td>
                            <td>Status &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</td>
                            <td>File</td>
                           
                            
                            
						</tr>
                        <?php foreach($data as $post): ?>
                        <?php ?>
						<tr align="center">
							<td><?php echo $post['IssueTracker']['ticket_desc'];?></td>
                            <td><?php  
							if($post['IssueTracker']['priority']==0){echo "Low";}
						    else if($post['IssueTracker']['priority']==1){echo "Normal";}
						    else {echo "Urgent";} ?></td>
                            
                            <td> <?php if($post['IssueTracker']['requirment_type']=='0'){echo "Upgrade";} 
							else if($post['IssueTracker']['requirment_type']=='1'){echo "New";}
							else if($post['IssueTracker']['requirment_type']=='2'){echo "Modification";}
							else{echo "Error";}
							?> </td>
                            
                            <td><?php echo $post['IssueTracker']['requirement_desc'];?></td>
                            <td><?php if($post['UserIssue']['process_type']==1){echo "Inbound";}
							else{echo "Outbound";}  ?></td>
							
							<td><?php  echo  isset($post['UserIssue']['start_date'])?date_format(date_create($post['UserIssue']['start_date']),'d-M-Y'):''; ?></td>
                            <td><?php  echo isset($post['UserIssue']['end_date'])?date_format(date_create($post['UserIssue']['end_date']),'d-M-Y'):''; ?></td>
                            
                            
							
                            <td>
							<?php echo $this->Form->input('IssueTracker.'.$post['IssueTracker']['id'].'.issue_status',array('label'=>false,'options'=>array('Select Status','3'=>'Close','2'=>'In-progress','1'=>'On-hold'),'selected'=>$post['IssueTracker']['issue_status'],'class' => 'form-control')); ?></td>
							
                           
                            <td><?php 
									$files=explode(',',$post['IssueTracker']['attach_files']);
									
									if(isset($files))
									{
										foreach($files as $links) : 
									?>
										&nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>"><?php echo $links; ?> </a>
									<?php	 endforeach;
									}
								?></td>
                               
                             </tr>
                            <?php endforeach;?>
                         </table>
                 <?php echo $this->Html->link('Back',array('action'=>'View_user_issue'),array('class'=>'btn btn-primary')); ?>

            	<input type="submit" value="submit" class="btn btn-info">
               <?php $this->Form->end("submit");?>
					   </div>
					</div>
				</div>
			</div>