<div class="row">
    <div id="breadcrumb" class="col-xs-12">
            <a href="#" class="show-sidebar">
                    <i class="fa fa-bars"></i>
            </a>
            <ol class="breadcrumb pull-left">
            </ol>
            
    </div>
</div>

<div class="box-content">
				<h4 class="page-header">Edit Email</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>
                                        
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Host</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Host',array('label' => false,'class'=>'form-control','value'=>$ProspectEmail['ProspectEmail']['Email_Host'],'required'=>"")); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Email Port</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Port',array('label' => false,'class'=>'form-control','value'=>$ProspectEmail['ProspectEmail']['Email_Port'],'required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Id</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Id',array('label' => false,'class'=>'form-control','value'=>$ProspectEmail['ProspectEmail']['Email_Id'],'required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Email Password</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Email_Password',array('label' => false,'type'=>'password','class'=>'form-control','value'=>$ProspectEmail['ProspectEmail']['Email_Password'],'required'=>"")); ?>
						</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"></label>
                                                <div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Update
							</button>
						</div>
					</div>
                                
					<div class="clearfix"></div>
					<div class="form-group">
						
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
