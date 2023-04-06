<?php

if(!empty($Data))
{
    //print_r($NewData1); exit;
    $NewData = $Data[0];
    
    //print_r($NewData); exit;
    //print_r($cost_data); exit;
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
    
    echo $this->Form->create('Targets',array('class'=>'form-horizontal','style'=>'overflow:auto;','action'=>"save_actual_revenue")); 
    $NewData = $Data[0];
    if(!empty($NewData1['FreezeData']['Rev_Act']))
    {
       $NewData['dd']['commit'] = $NewData1['FreezeData']['Rev_Act'];
    }
    ?>

<div class="form-group">
    <label class="col-sm-3 control-label">Basic Revenue</label>
    <label class="col-sm-1 control-label"><?php echo $bas; ?></label>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Actual Revenue</label>
    <div class="col-sm-2">
        <?php	
            echo $this->Form->input('commit',array('label'=>false,'placeholder'=>'Actual','value'=>$NewData['dd']['commit'],'required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>    
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Commitment Revenue</label>
    <div class="col-sm-2">
       <?php	
            echo $this->Form->input('commit2',array('label'=>false,'placeholder'=>'Commit','value'=>$NewData['dd']['commit2'],'required'=>true,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control'));
        ?>
    </div>
</div>
<table border="2" >
                    <tr>
                        <th colspan="5" style="text-align: center;">Actual Revenue - <input type="text" id="commitment" value="<?php echo $asp;?>" readonly="" /></th>
                    </tr>    
                    <tr>
                        <th style="width:70px">Header</th>
                        <th style="width:70px">Cost Center</th>
                        <th style="width:70px">Forecast</th>
                        <th style="width:70px">MTD</th>
                        <?php
                        $date_old_arr = array();
                        $month_arr = array("Jan"=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>"08",'Sep'=>"09","Oct"=>"10","Nov"=>'11','Dec'=>'12');
                        $m=1;
                        //echo $finMonth;
                        $mnt = $month_arr[$finMonth];
                        if(in_array($mnt,array("01","02","03")))
                        {
                            $year = date("Y");
                        }
                        else
                        {
                            $year = date("Y")-1;
                        }
                        
                        $date_new =  explode("-",date('t',strtotime("01-$mnt-$year"))); 
                        for(; $m<=$date_new[0]; $m++)
                        {
                            $date_old_arr[$m] = "$m-$finMonth";
                        }
                        $m--;
                        $n = $m;
                        while($n!=0)
                        {
                            echo '<th  style="width:70px;text-align: center;">';
                            echo $date_old_arr[$n--];
                            echo "</th>";
                        }
                        ?>
                    </tr>
                
                <?php
                            $data_rate = array();
                            $start_date = 1;
                            $today_date =  date("t",strtotime("01-$mnt-$year"));
                            $end_date =  $today_date;
                            
                            $calculation_days = $today_date-$start_date;
                            
                            
                        foreach($ParticularDetails as $parts)
                        {
                            $PartId = $parts['parts']['PartId'];
                            $mtd = 0;
                            $forecast = round($mtd*($end_date/$calculation_days));
                            echo '<tr><th  style="width:70px">'.$parts['parts']['PartName']."</th>";
                            echo "<th>";
                                echo '<input type="text" id="cost'.$PartId.'" name="cost['.$PartId.']" value="'.$cost_data['cost'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()"  style="width:70px;text-align: center;" />';
                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="forcast'.$PartId.'" name="forcast['.$PartId.']" value="'.round($cost_data['HeadTotal'.$PartId]*($end_date/$calculation_days),2).'" onKeyPress="return checkNumber(this.value,event)" readonly=""  style="width:70px;text-align: center;" />';
                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="mtd'.$PartId.'" name="mtd['.$PartId.']" value="'.$cost_data['HeadTotal'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" readonly=""  style="width:70px;text-align: center;" />';
                            echo "</th>";
                            
                            $n = $m;
                                while($n!=0)
                                {
                                    echo "<th>";
                                    echo '<input type="text" id="date'.$n.'_'.$PartId.'" name="date['.$n.'_'.$PartId.']" value="'.$cost_data['date'.$n.'_'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()"  style="width:70px;text-align: center;" />';
                                    echo "</th>";
                                   $n--;
                                }
                            echo "</tr>";
                            
                            if(!empty($parts['parts']['AddRequired']) && empty($parts['parts']['RateRequired']))
                            {
                                $part_array[] = $PartId;
                            }
                            else if(!empty($parts['parts']['AddRequired']))
                            {
                                $data_rate[] = $parts;
                                $rate_array[] = $PartId;
                            }
                            else
                            {
                                $not_added_arr[] =  $PartId;
                            }
                        }
                        
                        foreach($data_rate as $parts)
                        {
                            $PartId = $parts['parts']['PartId'];
                            echo '<tr><th  style="width:70px">'.$parts['parts']['PartName']." Rate</th>";
                            echo "<th>";
                                echo '<input type="text" id="costRate'.$PartId.'" name="costRate['.$PartId.']" value="'.$cost_data['costRate'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()"  style="width:70px;text-align: center;" />';
                            echo "</th>";
                            echo '<th  style="width:70px">';

                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="mtdRate'.$PartId.'" name="mtdRate['.$PartId.']" value="'.$cost_data['HeadTotal'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" readonly=""  style="width:70px"  style="width:70px;text-align: center;" />';
                            echo "</th>";
                            
                            
                            $n = $m;
                            while($n!=0)
                            {
                                echo "<th>";
                                echo '<input type="text" id="dateRate'.$n.'_'.$PartId.'" name="dateRate['.$n.'_'.$PartId.']"  value="'.$cost_data['dateRate'.$n.'_'.$PartId].'" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()"  style="width:70px;text-align: center;" />';
                                echo "</th>";
                               $n--;
                            }
                            echo "</tr>";
                        }
                ?>
                    <tr>
                        <th>Amount</th>
                        <th><input type="text" id="costTotal" name="costTotal" value="<?php echo $cost_data['CostTotal'];?>" readonly=""  style="width:70px;text-align: center;" /></th>
                        <th><input type="text" id="ForecastTotal" name="ForecastTotal" value="<?php echo round($cost_data['mtd_old'],2);?>" readonly=""  style="width:70px;text-align: center;" /></th>
                        <th><input type="text" id="MtdTotal" name="MtdTotal" value="<?php echo $cost_data['mtd_old'];?>" readonly=""  style="width:70px;text-align: center;" /></th>
                        
                        <?php
                            $n = $m;
                            while($n!=0)
                                {?>   
                                <th><input type="text" id="DateTotal<?php echo $n; ?>"  value="<?php echo $cost_data['DateTotal'.$n];?>" readonly=""  style="width:70px;text-align: center;" /></th>
                                <?php 
                                   $n--;
                                }
                                
                                foreach($part_array as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly=""  style="width:70px;text-align: center;" />
                          <?php }?>
                                <?php
                                foreach($rate_array as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly=""  style="width:70px;text-align: center;" />
                          <?php }?>
                                    <?php
                                foreach($not_added_arr as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly=""  style="width:70px;text-align: center;"  />
                          <?php }?>
                    </tr>
                    </table>
                <input type="hidden" id="mtd_old" name="mtd_old" value="0" />
                <input type="hidden" id="id_arr" name="id_arr" value="<?php echo implode(",",$part_array); ?>" />
                <input type="hidden" id="not_added_arr" name="not_added_arr" value="<?php echo implode(",",$not_added_arr); ?>" />
                <input type="hidden" id="id_arr_rate" name="id_arr_rate" value="<?php echo implode(",",$rate_array); ?>" />
                <input type="hidden" id="mnt_arr" name="mnt_arr" value="<?php echo $m; ?>" />
                <input type="hidden" id="calculation_days" name="calculation_days" value="<?php echo round($end_date/$calculation_days,2); ?>" />
                <button name="submit" id="button" value="Save" class="btn btn-primary" >Save</button>
                <?php 
                echo $this->Form->input('cost_id',array('type'=>'hidden','value'=>$cost_id));
                echo $this->Form->input('finYear',array('type'=>'hidden','value'=>$finYear));
                echo $this->Form->input('finMonth',array('type'=>'hidden','value'=>$finMonth));
                echo $this->Form->input('id',array('type'=>'hidden','value'=>$NewData['dd']['id']));
                echo $this->Form->end();
 } elseif($type=='actual') {
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
        <?php if($type!='revenue') { ?>
        <button name="button" id="button" value="Save" onclick="save_actual_data()" class="btn btn-primary" >Save</button>
        <?php } ?>
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
{
    echo "No Record Found";
}