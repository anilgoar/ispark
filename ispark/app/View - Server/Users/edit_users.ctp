<!DOCTYPE html>
<html lang="en">
<body>
<h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
<?php echo $this->Form->create('User',array('action'=>'edit_users')); ?>
<div class="container-fluid">
	<div id="page-login" class="row">
		<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="box">
				<div class="box-content">
					<div class="text-center">
						<h3 class="page-header">I - Spark</h3>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','value'=>$user_master['User']['password'])); ?>
					</div>
					<div class="form-group">
						<label class="control-label">E-mail</label>
						<?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email - ID','value'=>$user_master['User']['email'],'required'=>TRUE)); ?>
					</div>
                                        <div class="form-group">
						<label class="control-label">Branch Name</label>
						<?php echo $this->Form->input('branch_name',array('label'=>false,'class'=>'form-control','options'=>$branch_master,'value'=>$user_master['User']['branch_name'],'required'=>TRUE)); ?>
					</div>
                                    
                                        <div class="form-group">
                                            <label class="control-label">Process</label>
                                            <?php echo $this->Form->input('role',array('label'=>false,'class'=>'form-control','value'=>$user_master['User']['role'],'required'=>TRUE)); ?>
					</div>
                                    
                                        <div class="form-group">
                                            <label class="control-label">HR Eligible</label>
                                            <?php echo $this->Form->input('hr_eligible',array('label'=>false,'class'=>'form-control','options'=>array('Yes'=>'Yes','No'=>'No'),'empty'=>'Select','value'=>$user_master['User']['hr_eligible'])); ?>
					</div>
                                    
                                        <div class="form-group">
						<label class="control-label">Process Head</label>
						<?php echo $this->Form->input('process_head',array('label'=>false,'class'=>'form-control','options'=>$process_manager,'value'=>$user_master['User']['process_head'],'required'=>TRUE)); ?>
					</div>
                                        
                                        <div class="form-group">
						<label class="control-label">User Status</label>
						<?php echo $this->Form->input('UserActive',array('label'=>false,'class'=>'form-control','options'=>array('1'=>'Active','0'=>'Deactive'),'required'=>TRUE)); ?>
					</div>
                                    
					<div class="text-center">
						<input type="submit" class="btn btn-primary" value="Save" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
echo $this->Form->input('id',array('type'=>'hidden','value'=>$user_master['User']['id']));
$this->Form->end();
?>
</body>
</html>
