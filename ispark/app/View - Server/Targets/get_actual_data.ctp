<?php

if(!empty($Data))
{
    //print_r($NewData1); exit;
    $NewData = $Data[0];
    
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
    if(!empty($NewData1['FreezeData']['Rev_Act']))
    {
       $NewData['dd']['commit'] = $NewData1['FreezeData']['Rev_Act'];
       
    }
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
    $NewData = $Data[0];
    if(!empty($NewData1['FreezeData']['Dir_Act']))
    {
       $NewData['dd']['direct_cost'] = $NewData1['FreezeData']['Dir_Act'];
    }
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
    $NewData = $Data[0];
    if(!empty($NewData1['FreezeData']['InDir_Act']))
    {
       $NewData['dd']['indirect_cost'] = $NewData1['FreezeData']['InDir_Act'];
    }
    
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
<?php } ?>
<div class="form-group">
    <label class="col-sm-3 control-label">&nbsp;</label>
    <div class="col-sm-3">  
        <button name="button" id="button" value="Save" onclick="save_actual_data()" class="btn btn-primary" >Save</button>
        <input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
    </div>
    <div class="col-sm-3">  
        <div id="msg"></div>
    </div>
</div>    





<!--<table class="table table-hover table-bordered" border="1">
    <tr>
        <th colspan="5"> Dashboard Entry History</th>
    </tr>
    <tr>
        <th>Date</th>
        <th>Branch Name</th>
        <th>Commitment</th>
        <th>Direct Cost</th>
        <th>Indirect Cost</th>
    </tr>
    <?php
    
    if(!empty($NewData))
            {
                echo "<tr>";
                   echo "<td> Last Edit On ".$NewData1['FreezeData']['EntryDate']."</td>";
                   echo "<td>".$NewData1['FreezeData']['Branch']."</td>";
                  
                   echo "<td>".$NewData1['FreezeData']['Rev_Act']."</td>";
                   echo "<td>".$NewData1['FreezeData']['Dir_Act']."</td>";
                    echo "<td>".$NewData1['FreezeData']['InDir_Act']."</td>";
                   
                echo "</tr>";
            }
    
            foreach($Data as $d):
                
                echo "<tr>";
                   echo "<td>".$d[0]['EntryDate']."</td>";
                   echo "<td>".$d['dd']['branch']."</td>";
                  
                   echo "<td>".$d['dd']['commit']."</td>";
                   echo "<td>".$d['dd']['direct_cost']."</td>";
                    echo "<td>".$d['dd']['indirect_cost']."</td>";
                   
                echo "</tr>";
                 
            endforeach;
            
    ?>
</table>-->
    
<?php
echo $this->Form->input('id',array('type'=>'hidden','value'=>$NewData['dd']['id']));


}

else
{?>
    
    <div class="form-group">
    <label class="col-sm-1 control-label">Branch</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['dd']['branch']; ?></label>
    <label class="col-sm-2 control-label">Cost Center</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['cm']['cost_center']; ?></label>
    <label class="col-sm-2 control-label">Process Name</label>
    <label class="col-sm-2 control-label"><?php echo $NewData['cm']['process_name']; ?></label>
    </div>
<?php if($type=='revenue') {
    
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
            echo $this->Form->input('commit',array('label'=>false,'placeholder'=>'Commit','value'=>'0','required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>    
</div>    
<?php } elseif($type=='actual') {
    
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
            echo $this->Form->input('direct_cost',array('label'=>false,'placeholder'=>'Direct Cost','value'=>'0','required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>
    
</div>
<?php } else if($type=='indirect') {
    
    
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
            echo $this->Form->input('indirect_cost',array('label'=>false,'placeholder'=>'InDirect Cost','value'=>'0','required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>
    
</div>
<?php } ?>
<div class="form-group">
    <label class="col-sm-3 control-label">&nbsp;</label>
    <div class="col-sm-3">  
        <button name="button" id="button" value="Save" onclick="save_actual_data1()" class="btn btn-primary" >Save</button>
        <input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
        <input type="hidden" name="finyear11" id="finyear11" value="<?php echo $finyear;?>" />
        <input type="hidden" name="finmonth11" id="finmonth11" value="<?php echo $finmonth;?>" />
        <input type="hidden" name="cost_id11" id="cost_id11" value="<?php echo $cost_id;?>" />
    </div>
    <div class="col-sm-3">  
        <div id="msg"></div>
    </div>
</div>  
<?php   // echo "No Record Found";
}
