<!DOCTYPE html>
<html lang="en">
<body>
<?php echo $this->Form->create('User',array('action'=>'change_password')); ?>
<div class="container-fluid">
    <div id="page-login" class="row">    
        <div class="box-content">
            <div class="text-center">
                <h3 class="page-header">I-Spark</h3>
            </div>
            <div class="text-center">
                <h3 class="page-header">Password Change</h3>
            </div>
            <div class="text-center">
                <h6 class="page-header"><?php echo $this->Session->flash(); ?></h6>
            </div>
            <div class="form-group">
                <label class="control-label">Old Password</label>
                <?php echo $this->Form->input('old_password',array('label'=>false,'class'=>'form-control','placeholder'=>'Old Password','type'=>'password')); ?>
            </div>
            <div class="form-group">
                <label class="control-label">New Password</label>
                <?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','placeholder'=>'New Password','type'=>'password')); ?>
            </div>
            <div class="form-group">
                <label class="control-label">Confirm New Password</label>
                <?php echo $this->Form->input('password2',array('label'=>false,'class'=>'form-control','placeholder'=>'Confirm New Password','type'=>'password')); ?>
            </div>
            <div class="form-group">
                <label class="col-sm-4"></label>
                <input type="submit" name="submit" value="Change Password" class="btn btn-primary" />
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Form->input('otp',array('label'=>false,'class'=>'form-control','value'=>$otp,'type'=>'hidden'));
echo $this->Form->input('ukey',array('label'=>false,'class'=>'form-control','value'=>$ukey,'type'=>'hidden'));
echo $this->Form->end();?>
</body>
</html>
