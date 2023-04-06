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
function get_entry_form(month)
  {
      var cost_center = $('#TargetsCostCenterId').val();
      $.post("Targets/get_entry_form",{month:month,cost_id:cost_center},function(data){
        $('#entry_form_disp').html(data);});
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
<!--        <div class="form-group">
            <label class="col-sm-2 control-label">Revenue</label>
            <div class="col-sm-2">
                    <?php //echo $this->Form->input('target',array('label' => false,'class'=>'form-control','placeholder'=>'Enter Revenue',"onKeyPress"=>"return checkNumber(this.value,event)" ,'onpaste'=>"return false",'required'=>true)); ?>
            </div>
            <label class="col-sm-2 control-label">Amount in Rupees e.g. 3042725 </label>
        </div>-->
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

echo $this->Form->input('month',array('label' => false,'options'=> $month,'empty' => 'Select Month','class'=>'form-control','onchange'=>"get_entry_form(this.value)",'required'=>true)); ?>
                </div>
        </div>
    
    <div id="entry_form_disp" style="overflow:auto"></div>
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
        document.getElementById("forcast"+rate_arr[i]).value = parseFloat(mtdTotal).toFixed(2);
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
        document.getElementById("forcast"+other_arr[i]).value = parseFloat(mtdTotal).toFixed(2);
    }
    document.getElementById("MtdTotal").value = amount;
    document.getElementById("ForecastTotal").value = parseFloat(amount).toFixed(2);;
}
    
    
    
    
</script>