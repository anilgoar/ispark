<script>
  function TargetProcess(branch)
  {
      $.post("Targets/get_process",{branch:branch},function(data){
        $('#process').html(data);});
  }

</script>
<script>
  function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
        {
            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
                 
            }
            else{
               alert("please enter the value in Lakhs");
                 return false; 
           
           
        }
        }
	return true;
}
</script>



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
				<h4 class="page-header">Add Target</h4>
				
					<span style="color:green"><?php echo $this->Session->flash(); ?></span>
					<?php echo $this->Form->create('Targets',array('class'=>'form-horizontal', 'url'=>'add','enctype'=>'multipart/form-data')); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select Branch','onChange'=>'TargetProcess(this.value)','required'=>true,'class'=>'form-control')); ?>
						</div>
					<label class="col-sm-2 control-label">Process</label>
						<div class="col-sm-4"><div id="process">
                                                <?php if(empty($process))
                                {$process='';} ?>
					<?php echo $this->Form->input('branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select Process','required'=>true,'class'=>'form-control','multiple'=>false )); ?>
                                        </div></div>
</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Target Revanue</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('target',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Target','maxlength'=>'5',"onKeyPress"=>"return checkNumber(this.value,event)" ,'onpaste'=>"return false",'required'=>true)); ?>

						</div>

					
						<label class="col-sm-2 control-label">Target Direct cost</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('target_directCost',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Target Direct Cost',"onKeyPress"=>"return checkNumber(this.value,event)", 'maxlength'=>'5' ,'onpaste'=>"return false",'required'=>true)); ?>

						</div>
</div>
<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Target Indirect cost</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('target_IDC',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Target Indirect Cost', "onKeyPress"=>"return checkNumber(this.value,event)", 'maxlength'=>'5' ,'onpaste'=>"return false",'required'=>true)); ?>

						</div>

					
						<label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-4">
							<?php 

$c= date(m); 
                                                       $month = array(date("Y-m-1")=>date("M-y"),date('Y-m-1', mktime(0, 0, 0,$c + 1, 1))=> date('M-y', mktime(0, 0, 0,$c + 1, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 2, 1))=>date('M-y', mktime(0, 0, 0,$c + 2, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 3, 1))=> date('M-y', mktime(0, 0, 0,$c + 3, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 4, 1))=>date('M-y', mktime(0, 0, 0,$c + 4, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 5, 1))=>date('M-y', mktime(0, 0, 0,$c + 5, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 6, 1))=>date('M-y', mktime(0, 0, 0,$c + 6, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 7, 1))=>date('M-y', mktime(0, 0, 0,$c + 7, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 8, 1))=>date('M-y', mktime(0, 0, 0,$c + 8, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 9, 1))=>date('M-y', mktime(0, 0, 0,$c + 9, 1)),date('Y-m-1', mktime(0, 0, 0,$c + 10, 1))=>date('M-y', mktime(0, 0, 0,$c + 10, 1)),
                date('Y-m-1', mktime(0, 0, 0,$c + 11, 1))=>date('M-y', mktime(0, 0, 0,$c + 11, 1)));
                            
                                        echo $this->Form->input('month',array('label' => false,'options'=> $month,'empty' => 'Select Month','class'=>'form-control' ,'required'=>true)); ?>
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
