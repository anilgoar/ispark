<!DOCTYPE html>
<html lang="en">
<body>
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
					</h4>
<?php echo $this->Form->create('User',array('action'=>'edit_user')); ?>
<div class="container-fluid">
	<div id="page-login" class="row">
		<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="box">
				<div class="box-content">
					<div class="text-center">
						<h3 class="page-header">I - Spark</h3>
					</div>
                                        <div class="form-group">
						<label class="control-label">Old Password</label>
						<?php echo $this->Form->input('oldpassword',array('label'=>false,'class'=>'form-control','placeholder'=>'Old Password')); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','placeholder'=>'New Password')); ?>
					</div>
					<div class="form-group">
						<label class="control-label">E-mail</label>
						<?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email - ID','value'=>$user_master['User']['email'])); ?>
					</div>
					<div class="text-center">
						<input type="submit" class="btn btn-primary" value="Save" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->Form->end();?>
</body>
</html>
