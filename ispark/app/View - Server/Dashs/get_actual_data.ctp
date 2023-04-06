<?php

if(!empty($NewData1))
{
    //print_r($NewData1); exit;
    $NewData = $NewData1[0];
    
    //print_r($NewData); exit;
?>

<div class="form-group">
    <label class="col-sm-1 control-label">Branch</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['dd']['branch']; ?></label>
    <label class="col-sm-2 control-label">Cost Center</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['cm']['cost_center']; ?></label>
    <label class="col-sm-2 control-label">Process Name</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['cm']['process_name']; ?></label>
</div>
<?php if($type=='revenue') {
    $NewData = $Data[0];
    $NewData['dd']['commit'] = $NewData1['FreezeData']['Rev_Act'];
    
    ?>
<div class="form-group">
    <label class="col-sm-3 control-label">Aspirational Revenue</label>
    <label class="col-sm-1 control-label"><?php echo $asp; ?></label>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">Basic Revenue</label>
    <label class="col-sm-1 control-label"><?php echo $bas; ?></label>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Actual Revenue</label>
    <div class="col-sm-2">
        <?php	
            echo $this->Form->input('commit',array('label'=>false,'placeholder'=>'Commit','value'=>$NewData['dd']['commit'],'required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>    
</div>    
<?php } elseif($type=='actual') {
    
       $NewData['dd']['direct_cost'] = $NewData1['FreezeData']['Dir_Act'];
    
    ?>

<div class="form-group">
    <label class="col-sm-3 control-label">Aspirational Direct Cost</label>
    <label class="col-sm-1 control-label"><?php echo $asp; ?></label>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">Basic Direct Cost</label>
    <label class="col-sm-1 control-label"><?php echo $bas; ?></label>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">Actual Direct Cost</label>
    <div class="col-sm-2">
        <?php	
            echo $this->Form->input('direct_cost',array('label'=>false,'placeholder'=>'Direct Cost','value'=>$NewData['dd']['direct_cost'],'required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>
    
</div>
<?php } else if($type=='indirect') {
    
       $NewData['dd']['indirect_cost'] = $NewData1['FreezeData']['InDir_Act'];
    
    
    ?>
<div class="form-group">
    <label class="col-sm-3 control-label">Aspirational InDirect Cost</label>
    <label class="col-sm-1 control-label"><?php echo $asp; ?></label>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">Basic InDirect Cost</label>
    <label class="col-sm-1 control-label"><?php echo $bas; ?></label>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">InDirect Cost</label>
    <div class="col-sm-2">
        <?php	
            echo $this->Form->input('indirect_cost',array('label'=>false,'placeholder'=>'InDirect Cost','value'=>$NewData['dd']['indirect_cost'],'required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>
    
</div>
<?php } 
}
else
{
    echo "No Record Found";
}
