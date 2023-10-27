<?php
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
$(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
    });
});
</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right"></div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>P&L File Upload </span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow: auto;">
                <h4 class="page-header" style="color:green;"><?php echo $this->Session->flash(); ?></h4>
                <?php echo $this->Form->create('PnlMannuals',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));  ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('Branch',array('label'=>false,'options'=>$branch_name,'empty'=>'Select','required'=>true,'class'=>'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Month</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('month',array('label'=>false,'id'=>'month','options'=>$new_month_master,'empty'=>'Select','required'=>true,'class'=>'form-control')); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Updated Date</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('updated_at',array('label'=>false,'id'=>'updated_at','required'=>true,'autocomplete'=>'off','class'=>'form-control datepik')); ?>
                    </div>
                </div>
       
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">File Upload</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->file('file_upload',array('required'=>true,'accept'=>".csv")); ?><br/>(CSV File Only)
                    </div>
                    
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" name="Save" value="Save" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="/ispark/Menuisps/sub?AX=MTM1" class="btn btn-primary">Back</a>
                    </div>
                </div>        
                <table border="2">
                    <tr>
                        <th>Cost Center</th>
                        <td><input type="text" id="cost_start" name="cost_start" value="<?php echo $pnl_head_arr['cost_start']['Col_Start'].$pnl_head_arr['cost_start']['RowNumber']; ?>" required=""  /></td>
                        <td><input type="text" id="cost_end" name="cost_end" value="<?php echo $pnl_head_arr['cost_start']['Col_End'].$pnl_head_arr['cost_start']['RowNumber']; ?>" onblur="colcopy()" required="" /></td>
                    </tr>
                    <tr>
                        <th>Net Revenue</th>
                        <td><input type="text" id="net_revenue_start" name="net_revenue_start" value="<?php echo $pnl_head_arr['net_revenue']['RowNumber']; ?>" onkeypress="return isNumberKey(event)" onblur="net_leftcopy('net_revenue_start',this.value)" required="" /></td>
                        <td><input type="text" id="net_revenue_end" name="net_revenue_end" value="<?php echo $pnl_head_arr['net_revenue']['Col_End'].$pnl_head_arr['net_revenue']['RowNumber']; ?>" readonly="" required="" /></td>
                    </tr>
                    <tr>
                        <th>Actual CTC</th>
                        <td><input type="text" id="actual_start" name="actual_start" value="<?php echo $pnl_head_arr['actual_salary']['RowNumber']; ?>" onblur="act_leftcopy('actual_start',this.value)" onkeypress="return isNumberKey(event)" required="" /></td>
                        <td><input type="text" id="actual_end" name="actual_end" value="<?php echo $pnl_head_arr['actual_salary']['Col_End'].$pnl_head_arr['actual_salary']['RowNumber']; ?>"  readonly="" onkeypress="return isNumberKey(event)" required="" /></td>
                    </tr>
                    <tr><th colspan="3" style="text-align: center;">Direct Expense</th></tr>
                    <?php
                    $id_arr = array();
                      foreach($direct_exp as $key=>$value)
                      {?>
                        <tr>
                          <th><?php echo $value; ?></th>
                          <td><input type="text" id="start_<?php echo $key; ?>" onblur="leftcopy('<?php echo $key; ?>',this.value)" name="start_<?php echo $key; ?>" value="<?php echo $pnl_head_arr[$key]['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                          <td><input type="text" id="end_<?php echo $key; ?>" name="end_<?php echo $key; ?>" readonly="" value="<?php echo $pnl_head_arr[$key]['Col_End'].$pnl_head_arr[$key]['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                        </tr>  
               <?php
                        $id_arr[] = "$key";
               
                      }
                    
                    ?>
                    <tr><th colspan="3" style="text-align: center;">InDirect Expense</th></tr>
                    
                    <tr>
                        <th>Future Revenue Adjustment</th>
                        <td><input type="text" id="start_future_revenue" name="start_future_revenue" onblur="leftcopy('future_revenue',this.value)"  value="<?php echo $pnl_head_arr['future_revenue']['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                        <td><input type="text" id="end_future_revenue"  name="end_future_revenue" readonly="" value="<?php echo $pnl_head_arr['future_revenue']['Col_End'].$pnl_head_arr['future_revenue']['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                    </tr> 
                    <?php

                      foreach($indirect_exp as $key=>$value)
                      {?>
                        <tr>
                          <th><?php echo $value; ?></th>
                          <td><input type="text" id="start_<?php echo $key; ?>" onblur="leftcopy('<?php echo $key; ?>',this.value)" name="start_<?php echo $key; ?>" value="<?php echo $pnl_head_arr[$key]['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                          <td><input type="text" id="end_<?php echo $key; ?>" name="end_<?php echo $key; ?>" readonly="" value="<?php echo $pnl_head_arr[$key]['Col_End'].$pnl_head_arr[$key]['RowNumber']; ?>" onkeypress="return isNumberKey(event)" required="" /></td>
                        </tr>  
               <?php $id_arr[] = "$key";  }
                    
                    ?>
                    
                    
                    
                    
                    
                    
                    
                </table>   
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" name="Save" value="Save" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="" class="btn btn-primary">Back</a>
                    </div>
                </div>  
                <input type="hidden" id="id_arr" name="id_arr"  value="<?php echo implode(',',$id_arr); ?>" />    
		<?php echo $this->Form->end();  ?>
            </div>
        </div>
    </div>
</div>

<script>
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : event.keyCode;
  if (charCode != 46 && charCode > 31 
    && (charCode < 48 || charCode > 57))
     return false;

  return true;
}

function colcopy()
{
    var cost_end = $('#cost_end').val();
    
    
    //var end = cost_end.match(/(\d+)/);
    var end = cost_end.replace(/[0-9]/g, '');
    try{
        $('#net_revenue_end').val(end[0]);
        $('#actual_end').val(end[0]);
        var idqty =  $('#id_arr').val();
        var str=idqty.split(",");

        for(var i=0; i<str.length; i++)
        {
           $('#end_'+str[i]).val(end[0]);
        }
    }
    catch(err)
    {
        $('#actual_end').val('');
        var idqty =  $('#id_arr').val();
        var str=idqty.split(",");
    
        for(var i=0; i<str.length; i++)
        {
           $('#end_'+str[i]).val('');
        }
        alert('Please Fill Right Column Name');
    }
    
}

function leftcopy(id,val)
{
    var cost_end = $('#cost_end').val();
    var end = cost_end.replace(/[0-9]/g, '');
    $('#end_'+id).val(end+""+val);    
}

function net_leftcopy(id,val)
{
    var cost_end = $('#cost_end').val();
    var end = cost_end.replace(/[0-9]/g, '');
    $('#net_revenue_end').val(end+""+val);    
}
function act_leftcopy(id,val)
{
    var cost_end = $('#cost_end').val();
    var end = cost_end.replace(/[0-9]/g, '');
    $('#actual_end').val(end+""+val);    
}
</script>    