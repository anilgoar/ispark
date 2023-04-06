<?php //print_r($data1); ?>
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
					<span>View Issue By Users</span>
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
				<?php $case=array('classic',''); $i=0; ?>
					<tbody>
						<tr>
							
                            <td align="center"><b>Ticket No.</b></td>
                            <td align="center"><b>Branch</b></td>
                            <td align="center"><b>Description</b></td>
                            <td align="center"><b>Create Date</b></td>
                            
                            
						</tr>
                        
						<?php foreach ($data as $post): ?>                        
						<tr class="<?php  echo $case[$i%2]; $i++;?>" align="center">
							<td>
								<code><?php echo $this->Html->link($post['IssueTracker']['id'],
							array('controller'=>'Issues','action'=>'user_issue','?'=>array('id'=>$post['IssueTracker']['id']),'full_base' => true)); ?></code>
						</td>														
                            
                            <td><?php echo $post['IssueTracker']['branch_name']; ?></td>
                            <td><?php echo $post['IssueTracker']['ticket_desc']; ?></td>
                            <td><?php echo $post['IssueTracker']['createdate']; ?></td>
													</tr>
						<?php endforeach; ?>
						<?php unset($Issues); ?>
					</tbody>
				</table>						
					</div>
					</div>
				</div>
			</div>


