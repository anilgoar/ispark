<?php ?>

<script>
  function TargetProcess(branch)
  {
      $.post("get_process",{branch:branch},function(data){
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
    <h4 class="page-header">Delete Aspirational Target</h4>
				
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Targets',array('class'=>'form-horizontal')); ?>
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
<!--        <div class="form-group">
            <label class="col-sm-2 control-label">Revenue</label>
            <div class="col-sm-2">
                    <?php //echo $this->Form->input('target',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Revenue',"onKeyPress"=>"return checkNumber(this.value,event)" ,'onpaste'=>"return false",'required'=>true)); ?>
            </div>
            <label class="col-sm-2 control-label">Amount in Rupees e.g. 3042725 </label>
        </div>-->
        
        <div class="form-group">
            <label class="col-sm-2 control-label">Finance Year</label>
            <div class="col-sm-2">
                <?php echo $this->Form->input('finance_year',array('label'=>false,'options'=>array('2020-21'=>'2020-21'),'empty'=>'Year','required'=>true,'class'=>'form-control','multiple'=>false )); ?>
            </div>
        </div>        
        <div class="form-group">
                <label class="col-sm-2 control-label">Finance Month</label>
                <div class="col-sm-2">
                        <?php 
$month = array("Jan"=>"Jan","Feb"=>"Feb","Mar"=>"Mar",
        "Apr"=>"Apr","May"=>"May","Jun"=>"Jun","Jul"=>"Jul",
        "Aug"=>"Aug","Sep"=>"Sep","Oct"=>"Oct","Nov"=>"Nov","Dec"=>"Dec");

echo $this->Form->input('month',array('label' => false,'options'=> $month,'empty' => 'Select Month','class'=>'form-control','onchange'=>"get_entry_form(this.value)",'required'=>true)); ?>
                </div>
        </div>
    
    <div id="entry_form_disp" style="overflow:auto"></div>
	<div class="clearfix"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
                <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-label-left">
                                Delete 
                        </button>
                </div>
        </div>
<?php echo $this->Form->end(); ?>
</div>
