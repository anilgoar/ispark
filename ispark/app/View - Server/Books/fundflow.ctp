<?php ?>




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

<div class="box-content">
				<h4 class="page-header">Create Fund Flow</h4>
				
					<span style="color:green"><?php echo $this->Session->flash(); ?></span>
					<?php echo $this->Form->create('Books',array('class'=>'form-horizontal', 'url'=>'add','enctype'=>'multipart/form-data')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">status Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Status',array('label'=>false,'options'=>$status,'empty'=>'Select status','required'=>true,'class'=>'form-control')); ?>
						</div>
                                                <label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-4">
							<?php 

$c= 1; 
                                                       $month = array(date('Y-m-1', mktime(0, 0, 0,$c, 1))=>date('M-y', mktime(0, 0, 0,$c, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 1, 1))=> date('M-y', mktime(0, 0, 0,$c + 1, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 2, 1))=>date('M-y', mktime(0, 0, 0,$c + 2, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 3, 1))=> date('M-y', mktime(0, 0, 0,$c + 3, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 4, 1))=>date('M-y', mktime(0, 0, 0,$c + 4, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 5, 1))=>date('M-y', mktime(0, 0, 0,$c + 5, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 6, 1))=>date('M-y', mktime(0, 0, 0,$c + 6, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 7, 1))=>date('M-y', mktime(0, 0, 0,$c + 7, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 8, 1))=>date('M-y', mktime(0, 0, 0,$c + 8, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 9, 1))=>date('M-y', mktime(0, 0, 0,$c + 9, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 10, 1))=>date('M-y', mktime(0, 0, 0,$c + 10, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 11, 1))=>date('M-y', mktime(0, 0, 0,$c + 11, 1)));
                            
                                        echo $this->Form->input('month',array('label' => false,'options'=> $month,'empty' => 'Select Month','class'=>'form-control' ,'required'=>true)); ?>
						</div>
					</div>
                                       
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Budget</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Budget',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Budget','required'=>true)); ?>

						</div>

						
                                                </div>

					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
							<span><i class="fa fa-clock-o"></i></span>
								Submit
							</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
