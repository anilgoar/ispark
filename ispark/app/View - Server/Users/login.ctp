
<!-- File: /app/View/UserType/index.ctp -->
    


<div class="box-content">
					<div class="text-center">
						<h3 class="page-header">I-Spark</h3>
					</div>
                                        <?php
                                           echo  $this->Session->flash();
                                        ?>
					<?php echo $this->Form->create('User',array('action'=>'view','autocomplete'=>"off")); ?>
						<div class="form-group">
						<?php echo $this->Form->input('username',array('class'=>'form-control','value'=>'','required'=>true)); ?>
						</div>
						<div class="form-group">
						<?php echo $this->Form->input('password',array('class'=>'form-control','value'=>'','required'=>true)); ?>
						</div>
						<div class="text-center">
                                                    <input type="submit" name="submit" value="Login" class="btn btn-info" />
						</div>
                                        <?php echo $this->Form->end(); ?>
				</div>