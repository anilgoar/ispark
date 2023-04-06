<?php echo $this->Form->create('IssueTracker',array('class'=>'form-horizontal','action'=>'edit')); ?>
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
					<span>Check Issue Status</span>
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
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
					</h4>
						<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
					<tbody>
						<tr class="active" align="center">
							<td>Sr. No.</td>
                            <td>Ticket No.</td>
                            <td>Ticket Description</td>
                            <td>Priority</td>
                            <td>Process Type</td>
                            <td>Requirement Type</td>
                            <td>Requirement Description</td>
			               	<td>Create Date</td>
                            <td>Status</td>
							<td>Remarks</td>
                            
						</tr>
						<?php foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							
							<td><code><?php echo $i; ?></code></td>
                            <td><code><?php echo $post['IssueParticular']['ticket_no']; ?></code></td>
                          
                            <td><code><?php echo $post['IssueParticular']['ticket_desc'];?></code></td>
                           
                            <td><code><?php  
							if($post['IssueParticular']['priority']==0){echo "Low";}
						    else if($post['IssueParticular']['priority']==1){echo "Normal";}
						    else {echo "Urgent";} ?></code></td>
                            
                            <td><code><?php echo $post['IssueParticular']['process_type']; ?></code></td>
                            <td><code><?php if($post['IssueParticular']['requirment_type']=='0'){echo "Upgrade";} 
							else if($post['IssueParticular']['requirment_type']=='1'){echo "New";}
							else if($post['IssueParticular']['requirment_type']=='2'){echo "Modification";}
							else{echo "Error";}
							?></code></td>
                            <td><code><?php echo $post['IssueParticular']['requirement_desc']; ?></code></td>
                            <td><code><?php echo $post['IssueParticular']['createdate']; ?></code></td>
							<td><code><?php if($post['IssueParticular']['issue_status']==0){echo "close";}
							else if($post['IssueParticular']['issue_status']==1) {echo "open";} ?></code></td>
							<td><code><?php echo $post['IssueParticular']['remarks']; ?></code></td>
							
						</tr>
						<?php endforeach; ?>
						<?php unset($Issues); ?>
					</tbody>
				</table>						
					</div>
					</div>
				</div>
			</div>
