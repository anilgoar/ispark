<?php ?>

<script>
  function TargetProcess(branch)
  {
      $.post("Targets/get_process",{branch:branch},function(data){
        $('#tower').html(data);});
  }
function costcenter(tower)
  {
      $.post("Targets/get_tower",{tower},function(data){
        $('#tower').html(data);
        //alert(data);
    });
  }

</script>
<script>
  function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode == 46)
        {            
		return false;
        }
//        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
//        {
//            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
//                 
//            }
//            else{
//               alert("please enter the value in Lakhs");
//                 return false; 
//           
//           
//        }
//        }
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
				<h4 class="page-header">Add Aspirational Target</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Targets',array('class'=>'form-horizontal', 'url'=>'add','enctype'=>'multipart/form-data')); ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select Branch','onChange'=>'TargetProcess(this.value)','required'=>true,'class'=>'form-control')); ?>
						</div>
                                                
                                        </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cost Center</label>
						<div class="col-sm-4"><div id="tower">
                                                <?php if(empty($tower1))
                                {$tower1='';} ?>
					<?php echo $this->Form->input('cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select Cost Center','required'=>true,'class'=>'form-control','multiple'=>false )); ?>
                                        </div></div>
                                </div>
                                        <div class="form-group">
                                        

						<label class="col-sm-2 control-label">Revenue</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('target',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Revenue',"onKeyPress"=>"return checkNumber(this.value,event)" ,'onpaste'=>"return false",'required'=>true)); ?>

						</div>
                                                <label class="col-sm-2 control-label">Amount in Rupees e.g. 3042725 </label>
					</div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Direct cost</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('target_directCost',array('label' => false,'class'=>'form-control','placeholder'=>'Direct Cost',"onKeyPress"=>"return checkNumber(this.value,event)",  'onpaste'=>"return false",'required'=>true)); ?>

						</div>

						
                                                
                                        </div>
                                        <div class="form-group">
                                           <label class="col-sm-2 control-label">Indirect cost</label>
                                            <div class="col-sm-2">
                                                    <?php echo $this->Form->input('target_IDC',array('label' => false,'class'=>'form-control','placeholder'=>'Indirect Cost', "onKeyPress"=>"return checkNumber(this.value,event)", 'onpaste'=>"return false",'required'=>true)); ?>

                                            </div> 
                                        </div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">Finance Month</label>
						<div class="col-sm-2">
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
                                            <label class="col-sm-3 control-label"></label>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Save 
							</button>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
