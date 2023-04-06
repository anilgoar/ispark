<?php  //print_r($data); ?>

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
					<span>View Issue</span>
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
				<?php $case=array('class','active'); $i=0; ?>
					<tbody>
						<tr class="active">
							<th><center>Sr. No.</center></th>
                            <th><center>Ticket No.</center></th>
                            <th><center>Branch</center></th>
                            <th><center>Description</center></th>
                            <th><center>Create Date</center></th>
                            <th><center>Status</center></th>
							<th><center>Action</center></th>
						</tr>
						<?php foreach ($data as $post): ?>
						<tr class="<?php  echo $case[$i%2]; $i++;?>" align="center">
							<?php $id= base64_encode($post['IssueTracker']['id']); ?>
							<td><?php echo $i; ?></td>
                            <td><?php echo $post['IssueTracker']['id']; ?></td>
                            <td><?php echo $post['IssueTracker']['branch_name']; ?></td>
                            <td><?php echo $post['IssueTracker']['ticket_desc']; ?></td>
                            <td><?php echo $post['IssueTracker']['createdate']; ?></td>
							<td>
                                                        <?php
                                                        if($post['IssueTracker']['issue_status']=='0') echo "Open";
                                                        if($post['IssueTracker']['issue_status']=='1') echo "Hold";
                                                        if($post['IssueTracker']['issue_status']=='2') echo "In - Progress";
                                                        if($post['IssueTracker']['issue_status']=='5') echo "Reject";
                                                        if($post['IssueTracker']['issue_status']=='6') echo "Allocated";
                            
                                                        //if($post['IssueTracker']['issue_status']==0){echo "open";}
							//else if($post['IssueTracker']['issue_status']==1) {echo "reopen";} 
                                                        ?>
                                                        </td>
							<td>
								<code><?php echo $this->Html->link('Allocate',
							array('controller'=>'Issues','action'=>'allocate','?'=>array('id'=>$id),'full_base' => true)); ?></code>
						</td>
						</tr>
						<?php endforeach; ?>
						<?php unset($Issues); ?>
					</tbody>
				</table>						
					</div>
					</div>
				</div>
			</div>
