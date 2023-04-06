<!DOCTYPE html>
<html lang="en">
<body>
<?php foreach($branch_master as $post):
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
endforeach;unset($Addbranch);
?>
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
					</h4>
<?php echo $this->Form->create('User',array('action'=>'create_user')); ?>
<div class="container-fluid">
	<div id="page-login" class="row">
		<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="box">
				<div class="box-content">
					<div class="text-center">
						<h3 class="page-header">I - Spark</h3>
					</div>
					<div class="form-group">
						<label class="control-label">Email</label>
						<?php echo $this->Form->input('username',array('label'=>false,'class'=>'form-control','placeholder'=>'User Name')); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','placeholder'=>'Password')); ?>
					</div>
                                    	<div class="form-group">
						<label class="control-label">Confirm Password</label>
						<?php echo $this->Form->input('password2',array('label'=>false,'class'=>'form-control','placeholder'=>'Password','type'=>'password')); ?>
					</div>
                                        <div class="form-group">
						<label class="control-label">Department</label>
						<?php echo $this->Form->input('work_type',array('label'=>false,'class'=>'form-control','options'=>array('account'=>'Account','IT'=>'IT','HR'=>'HR','admin'=>'Admin'),'empty'=>'Select')); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Branch Name</label>
						<?php echo $this->Form->input('branch_name',array('label'=>false,'options'=>$data,'class'=>'form-control','empty'=>'Select Branch','required'=>true)); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Name</label>
						<?php echo $this->Form->input('emp_name',array('label'=>false,'class'=>'form-control','placeholder'=>'User Name')); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Process</label>
						<?php echo $this->Form->input('role',array('label'=>false,'class'=>'form-control',
                                                    'options'=>array('branch'=>'Billing','IT'=>'IT','IT Networking'=>'IT Networking','HR'=>'HR','Process Manager'=>'Process Manager','Branch Manager'=>'Branch Manager','IT Manager'=>'IT Manager','Regional Manager'=>'Regional Manager','Operation'=>'Operation'),'empty'=>'Select User Type')); ?>
					</div>
                                    	<div class="form-group">
						<label class="control-label">Process Head</label>
						<?php echo $this->Form->input('process_head',array('label'=>false,'class'=>'form-control','options'=>$process_manager,'empty'=>'Select Process Manager')); ?>
					</div>
					<div class="text-center">
                                            <input type="submit" class="btn btn-primary" onclick="return password_check()" value="Create User" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->Form->end();?>
</body>
</html>
