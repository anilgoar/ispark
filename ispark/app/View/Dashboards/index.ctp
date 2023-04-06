<script>
  function DashboardProcess(branch)
  {
      $.post("Dashboards/get_process",{branch:branch},function(data){
        $('#process').html(data);});
        document.getElementById("dash").innerHTML="";
  }
function costcenter(tower)
  {
      $.post("Dashboards/get_tower",{tower},function(data){
        $('#tower').html(data);});
  } 
  
function get_freeze_data(cost_id)
{
    if(cost_id!='')
    {
        $.post("Dashboards/get_freeze_data",
    {cost_id},function(data){
       if(data=='NotFound')
       {
           alert("Please Add Aspirational Target First.");
           $('#Save').prop("disabled",true);
           location.reload();
       }
       else
       {
           var json = jQuery.parseJSON(data);
           for(var i in json)
            {
                //text += '<option value="'+i+'">'+json[i]+'</option>';
                $('#'+i).val(json[i]);
                if(json[i]!='')
                {
                    //$('#'+i).prop('readonly',true);
                }
            }
            

           $('#Save').prop("disabled",false);
           
       }
    });
    }
    else
    {
        location.reload();
    }
}
  function DashboardData(process)
  {
        var branch=''; var tower = '';
        try{
            branch = document.getElementById("DashboardBranch").value;
        }
        
        catch(err){}
        try{
            tower = document.getElementById("DashboardBranchProcess").value;
        }
        
        catch(err){}

        $.post("Dashboards/get_dash_data",{process:process,branch:branch},function(data){
        $('#dash').html(data); });
        costcenter(tower);
  }
function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode;
	
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

function get_ebidta()
{
    
    var cost_center = $('#DashboardCostCenterId').val();
    $.post("Dashboards/get_os",{cost_center},function(data){
        $('#DashboardEVITAFreeze').val(data);
        //
    });
    
    
//    var ev = parseFloat((commit*1.83)/100).toFixed(2); 
//    $('#DashboardEVITA').val(ev);
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
		</div>
	</div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>Dashboard Actual Entry</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow: auto;"><h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
            <?php  
                    echo $this->Form->create('Dashboard',array('class'=>'form-horizontal')); 
                    
                    echo '<div class="form-group">';
                    echo '<label class="col-sm-3 control-label">Cost Center</label>';
                    echo '<div class="col-sm-6"><div id="tower">';
                    if(empty($tower1))
                    {$tower1='';}
                    echo $this->Form->input('cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>'get_freeze_data(this.value)'));
                    echo '</div></div></div>';
                    
                //if(strtotime("2019-01-17")>strtotime(date("Y-m-d")))
                //    {    
                    if(!empty($branchName))
                    {$countArr = array('direct_cost'=>'Direct Cost','indirect_cost'=>'Indirect Cost','EVITA'=>'Finance Cost');}
                    else
                    {
                        $countArr = array('direct_cost'=>'Direct Cost','indirect_cost'=>'Indirect Cost','EVITA'=>'Finance Cost');
                    }
                    $count = count($countArr); $float = true;
                    //$count = 2; 
                    $keys = array_keys($countArr); 
                    //print_r($keys); die;
                    $i=0;
                    $flag = true;
                    
                        for(; $i<$count; $i++)
                        {
                            $field = $keys[$i];
                            
                            if($keys[$i]=='branch')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Branch</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select','onChange'=>'DashboardProcess(this.value)','required'=>true,'class'=>'form-control'));
                                echo '</div></div>';
                            }
                            else if($keys[$i]=='branch_process')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Tower</label>';
//                                echo '<div class="col-sm-3">';
//                                echo $this->Form->input('aspirational_process',array('label'=>false,'options'=>$process,'empty'=>'Select','required'=>true,'onChange'=>'DashboardData(this.value)','class'=>'form-control','multiple'=>false));
//                                echo '</div>';
                                echo '<div class="col-sm-3"><div id="process">';
                                
                                if(empty($process))
                                {$process='';}
                                echo $this->Form->input('branch_process',array('label'=>false,'options'=>$process,'empty'=>'Select','required'=>true,'onChange'=>'DashboardData(this.value)','class'=>'form-control','multiple'=>false));
                                echo '</div></div></div>';
                            }
                            else if($keys[$i]=='cost_centerId')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Cost Center</label>';
                                echo '<div class="col-sm-3"><div id="tower">';
                                if(empty($tower1))
                                {$tower1='';}
                                echo $this->Form->input('cost_centerId',array('label'=>false,'options'=>$tower1,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>'get_freeze_data(this.value)'));
                                echo '</div></div></div>';
                            }
                            else if($keys[$i]=='date')
                            {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">'.$countArr[$keys[$i]].'</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input($field,array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>true,'onClick'=>"displayDatePicker('data[Dashboard][date]');",'class'=>'form-control'));
                                echo '</div></div>';
                            }
                            else if($keys[$i]=='EVITA')
                            {
                                
                                $readonly = false; 
                                if($keys[$i]=='EVITA')
                                {
                                    $req=false; $readonly = true; 
                                }
                                echo '<div class="form-group">';
                                
                                echo '<label class="col-sm-3 control-label">Finance Cost</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('Fin_Cost',array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>true,'class'=>'form-control','readonly'=>true));
                                echo '</div>';
                                
                                echo '<div class="col-sm-2">';
                                echo $this->Form->input('commit2',array('label'=>false,'placeholder'=>"Commitment",'required'=>true,'class'=>'form-control',"onKeyPress"=>"return checkNumber(this.value,event)"));
                                echo '</div>';
//                                echo '<div class="col-sm-2">';
//                                echo $this->Form->input('commit3',array('label'=>false,'placeholder'=>"Commitment",'required'=>true,'class'=>'form-control',"onKeyPress"=>"return checkNumber(this.value,event)"));
//                                echo '</div>';
                                echo '</div>';
                                
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">OutStanding As Per Last Month</label>';
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input('OS',array('label'=>false,'placeholder'=>'OutStanding','required'=>true,'class'=>'form-control','readonly'=>true));
                                echo '</div></div>';
                            }
                            else
                            {
                                $req=true;
                                if($float)
                                {
                                    $float = false;
                                    echo '<div class="form-group">';
                                    echo '<label class="col-sm-3 control-label"></label>';
                                    echo '<label class="col-sm-1 control-label">Aspirational</label>';
                                    echo '<label class="col-sm-3 control-label">Actual</label>';
                                    echo '<label class="col-sm-2 control-label">Commit</label>';
                                    echo '</div>';
                                }
                                
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">'.$countArr[$keys[$i]].'</label>';
                                
                                echo '<div class="col-sm-3">';
                                echo $this->Form->input($field.'_freeze',array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>$req,"onKeyPress"=>"return checkNumber(this.value,event)",'onpaste'=>"return false",'class'=>'form-control','disabled'=>true));
                                echo '</div>';
                                
                                
                                echo '<div class="col-sm-2">';
                                echo $this->Form->input($field,array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>$req,"onKeyPress"=>"return checkNumber(this.value,event)",'onblur'=>"get_ebidta()",'onpaste'=>"return false",'class'=>'form-control','readonly'=>$readonly));
                                echo '</div>';
                                
                                echo '<div class="col-sm-2">';
                                echo $this->Form->input($field.'_commit3',array('label'=>false,'placeholder'=>$countArr[$keys[$i]],'required'=>$req,"onKeyPress"=>"return checkNumber(this.value,event)",'onblur'=>"get_ebidta()",'onpaste'=>"return false",'class'=>'form-control','readonly'=>$readonly));
                                echo '</div>';
                                
                                
                                if($flag)
                                {
                                    //echo '<label class="col-sm-1 control-label">Amount in Rupees e.g. 3042725</label>';
                                    $flag = false;
                                }
                                echo '</div>'; 
                            }
                        }    
            //}   
                        $date_old_arr = array();
                        $m=1;
                        $curdate = explode("-",$date_cur_det);
                        for(; $m<=$curdate[0]; $m++)
                        {
                            $date_old_arr[$m] = $m.'-'.$curdate[1];
                        }
                        
                        //print_r($date_old_arr); exit;
                 ?>
                <style>
                    .tbl input{text-align: center !important;}
                    
                </style>
                <table border="2" class="tbl" >
                    <tr>
                        <th colspan="5" style="text-align: center;"> Aspirational Revenue - <input type="text" id="commitment" value="0" readonly="" /></th>
                    </tr>    
                    <tr>
                        <th style="width:120px; text-align: center;">Header</th>
                        <th><input type="text" id="cost_center_name" value="Cost Center" readonly="" /></th>
                        <th style="width:70px; text-align: center;">Forecast</th>
                        <th style="width:70px;text-align: center;">MTD</th>
                        <?php
                                $n = $m;
                                while($n!=1)
                                {
                                    echo '<th style="width:70px;text-align: center;" >';
                                    echo $date_old_arr[--$n];
                                    echo '<select name="set'.$n.'"><option value="1">1</option><option value="0">0</option></select>';
                                    echo "</th>";
                                }
                                $m--;
                        ?>
                    </tr>
                
                <?php
                            $data_rate = array();
                            $start_date = 0;
                            $today_date =  $curdate[0];
                            $end_date =  $end_date; 
                            
                            $calculation_days = $today_date-$start_date;
                            
                            
                        foreach($ParticularDetails as $parts)
                        {
                            $PartId = $parts['parts']['PartId'];
                            $mtd = 0;
                            $forecast = round($mtd*($end_date/$calculation_days),3);
                            echo '<tr><th style="width:150px; text-align: center;">'.str_replace(" ","&nbsp;",$parts['parts']['PartName'])."</th>";
                            echo "<th>";
                                echo '<input type="text" id="cost'.$PartId.'" name="cost['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" />';
                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="forcast'.$PartId.'" name="forcast['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" readonly="" style="width:70px"  />';
                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="mtd'.$PartId.'" name="mtd['.$PartId.']" style="width:70px" value="" onKeyPress="return checkNumber(this.value,event)" readonly=""  />';
                            echo "</th>";
//                            echo "<th>";
//                                echo '<input type="text" id="date'.$PartId.'" name="date['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_mtd('."'mtd$PartId','0',this.value,'$PartId'".')" '.($PartId==10?'readonly=""':"").' />';
//                            echo "</th>";
                            $n = $m; 
                                while($n!=0)
                                {   
                                    echo "<th>";
                                    echo '<input type="text" id="date'.$n.'_'.$PartId.'" name="date['.$n.'_'.$PartId.']"'.' value="0" style="width:80px" onKeyPress="return checkNumber(this.value,event)" '.($PartId==10?'readonly=""':"").' onblur="get_total_amt_dash()" />';
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
                            echo '<tr><th style="width:150px; text-align: center;">'.str_replace(" ","&nbsp;",$parts['parts']['PartName'])." Rate</th>";
                            echo "<th>";
                                echo '<input type="text" id="costRate'.$PartId.'" name="costRate['.$PartId.']" value="" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" />';
                            echo "</th>";
                            echo '<th style="width:60px">';

                            echo "</th>";
                            echo "<th>";
                                echo '<input type="text" id="mtdRate'.$PartId.'" name="mtdRate['.$PartId.']" value="" style="width:70px" onKeyPress="return checkNumber(this.value,event)" readonly="" />';
                            echo "</th>";
                            
                            
                            $n = $m; //$flag = true;
                            while($n!=0)
                            {
                                echo "<th>";
                                echo '<input type="text" id="dateRate'.$n.'_'.$PartId.'" name="dateRate['.$n.'_'.$PartId.']"'.'  value="0" style="width:80px" onKeyPress="return checkNumber(this.value,event)" onblur="get_total_amt_dash()" '.($PartId==10?'readonly=""':"").' />';
                                echo "</th>";
                               $n--;
                               $flag =false;
                            }
                                
                            echo "</tr>";
                        }
                ?>
                    <tr>
                        <th style="width:150px; text-align: center;">Amount</th>
                        <th><input type="text" id="costTotal" name="costTotal" value="" readonly="" style="text-align: center;" /></th>
                        <th><input type="text" id="ForecastTotal" name="ForecastTotal" style="width:70px" value="" readonly="" /></th>
                        <th><input type="text" id="MtdTotal" name="MtdTotal" value=""  style="width:70px" readonly="" /></th>
                        
                        <?php
                            $n = $m;
                            while($n!=0)
                                {?>   
                                <th><input type="text" id="DateTotal<?php echo $n; ?>"  value="<?php echo $cost_data['DateTotal'.$n];?>" style="width:80px" readonly="" /></th>
                                <?php 
                                   $n--;
                                }
                                
                                foreach($part_array as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
                          <?php }?>
                                <?php
                                foreach($rate_array as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
                          <?php }?>
                                    <?php
                                foreach($not_added_arr as $parts)
                                {?>
                                    <input type="hidden" id="HeadTotal<?php echo $parts; ?>"  value="0" readonly="" />
                          <?php }?>
                    </tr>
                    </table>
                <input type="hidden" id="mtd_old" name="mtd_old" value="0" />
                <input type="hidden" id="id_arr" name="id_arr" value="<?php echo implode(",",$part_array); ?>" />
                <input type="hidden" id="not_added_arr" name="not_added_arr" value="<?php echo implode(",",$not_added_arr); ?>" />
                <input type="hidden" id="id_arr_rate" name="id_arr_rate" value="<?php echo implode(",",$rate_array); ?>" />
                <input type="hidden" id="mnt_arr" name="mnt_arr" value="<?php echo $m; ?>" />
                <input type="hidden" id="end_date" name="end_date" value="<?php echo $end_date; ?>" />
                <input type="hidden" id="calculation_days" name="calculation_days" value="<?php echo round($end_date/$calculation_days,2); ?>" />
                <?php
                           echo '<div class="form-group">'; 
                           echo '<label class="col-sm-3 control-label">&nbsp;</label>';                            
                           echo '<div class="col-sm-3">';
                           echo '<input type="submit" id="Save" value="Save" class="btn btn-info" disabled="" >';
                           echo '</div></div>';
		 echo $this->Form->end(); 
                ?>
            </div>
            
            
            
            
        </div>
    </div>
</div>




<script>
function get_total_amt_dash()
{
    var mnt_arr = document.getElementById("mnt_arr").value;
    var rate_arr_str = document.getElementById("id_arr_rate").value;
    var other_arr_str = document.getElementById("id_arr").value;
    
    var rate_arr = rate_arr_str.split(",");
    var amount  = 0; 
    var date_amount = 0;
    
    var flag = true;
    
    for(var jj=1; jj<=parseFloat(mnt_arr);jj++)
    {
        var dateTotal = 0;
        for(var i=0; i<rate_arr.length; i++)
        {
            
        try{
                var cnt=0;
                cnt = document.getElementById("date"+jj+'_'+rate_arr[i]).value;
                if(cnt=='')
                {
                    cnt=0;
                }
                var rate = document.getElementById("dateRate"+jj+'_'+rate_arr[i]).value;   
                if(rate=='')
                {
                  rate=0;  
                }
                date_amount += cnt*rate;
                dateTotal += cnt*rate;
                if(jj==31 && rate_arr[i]=='1')
                {
                    //alert("qunt="+cnt+" rate="+rate +" total="+cnt*rate);
                }
                if(flag)
                {
                    
                    cnt = document.getElementById("cost"+rate_arr[i]).value;
                    if(cnt=='')
                    {
                        cnt=0;
                    }
                    rate = document.getElementById("costRate"+rate_arr[i]).value;   
                    if(rate==0)
                    {
                      rate=0;  
                    }
                    amount += cnt*rate;
                }
            }
            catch(err)
            {
                break;
            }
        }
        flag = false;
        document.getElementById("DateTotal"+jj).value = dateTotal;
    }
    var other_arr = other_arr_str.split(",");
    
    flag = true;
    
    for(var jj=1; jj<=parseFloat(mnt_arr);jj++)
    {
        var dateTotal = 0;
        for(var i=0; i<other_arr.length; i++)
        {
            var rate=0;
            if(flag)
            {
                
                
                rate =document.getElementById("cost"+other_arr[i]).value;
                if(rate=='')
                {
                    rate = 0;
                }
                if(other_arr[i]=='8')
                {
                    amount -= parseFloat(rate);
                }
                else
                {
                    amount += parseFloat(rate);
                }
            }
            rate =document.getElementById("date"+jj+'_'+other_arr[i]).value;
            
            if(rate=='')
            {
                rate = 0;
            }
            if(other_arr[i]=='8')
            {
                date_amount -= parseFloat(rate);
                dateTotal -= parseFloat(rate);
            }
            else
            {
                date_amount += parseFloat(rate);
                dateTotal += parseFloat(rate);
            }
        }
        flag = false;
        document.getElementById("DateTotal"+jj).value = dateTotal+parseFloat(document.getElementById("DateTotal"+jj).value);
    }
    document.getElementById("costTotal").value = amount;
    get_total_mtd();
}
    
function get_total_mtd()
{
        var mnt_arr = document.getElementById("mnt_arr").value;
    var rate_arr_str = document.getElementById("id_arr_rate").value;
    var other_arr_str = document.getElementById("id_arr").value;
    var end_date = document.getElementById("end_date").value;
    var calculation_days = document.getElementById("calculation_days").value;
    var rate_arr = rate_arr_str.split(",");
    
    var amount  = 0; 
    
    for(var i=0; i<rate_arr.length; i++)
    {
        var mtdTotal = 0;
        var mtdRateTotal =0;
        for(var jj=1; jj<=parseInt(mnt_arr);jj++)
        {   
        try{
                var cnt=0;
                cnt = document.getElementById("date"+jj+'_'+rate_arr[i]).value;
                if(cnt=='')
                {
                    cnt=0;
                }
                if(rate_arr[i]=='8')
                {
                    mtdTotal -= parseFloat(cnt);
                }
                else
                {
                    mtdTotal += parseFloat(cnt);
                }
                var rate = document.getElementById("dateRate"+jj+'_'+rate_arr[i]).value;   
                if(rate=='') 
                {
                  rate=0;  
                }
                amount += cnt*rate;
                if(rate_arr[i]=='8')
                {
                    mtdRateTotal -= parseFloat(rate);
                }
                else
                {
                    mtdRateTotal += parseFloat(rate);
                }
                
            }
            catch(err)
            {
                break;
            }
        }
        document.getElementById("mtd"+rate_arr[i]).value = mtdTotal;
        document.getElementById("forcast"+rate_arr[i]).value = parseFloat(calculation_days*mtdTotal).toFixed(2);
        //document.getElementById("mtdRate"+rate_arr[i]).value = parseFloat(mtdRateTotal/parseInt(mnt_arr)).toFixed(2);
        document.getElementById("mtdRate"+rate_arr[i]).value = '';
    }
    
    var other_arr = other_arr_str.split(",");
    
    
    for(var i=0; i<other_arr.length; i++)
    {
        var mtdTotal = 0;
        for(var jj=1; jj<=parseFloat(mnt_arr);jj++)
        {
            var rate=0;
            
            rate =document.getElementById("date"+jj+'_'+other_arr[i]).value;
            if(rate=='')
            {
                rate = 0;
            }
            if(other_arr[i]=='8')
            {
                amount -= parseFloat(rate);
                
            }
            else
            {
                amount += parseFloat(rate);
                
            }
            mtdTotal += parseFloat(rate);
        }
        document.getElementById("mtd"+other_arr[i]).value = mtdTotal;
        document.getElementById("forcast"+other_arr[i]).value = parseFloat(calculation_days*mtdTotal).toFixed(2);
    }
    document.getElementById("MtdTotal").value = amount;
    document.getElementById("ForecastTotal").value = parseFloat(calculation_days*amount).toFixed(2);;
}
    
    
    
    
</script>



