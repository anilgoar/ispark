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
                <div class="box-name"><span>Add P&L Records</span></div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
                <div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow: auto;"><h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                <?php echo $this->Form->create('Pnldetails',array('class'=>'form-horizontal'));  ?>
<!--                <div class="form-group">
                    <label class="col-sm-1 contro1-label">Description</label>
                    <label class="col-sm-2 control-label">Branch</label>
                    <label class="col-sm-2 control-label">Process</label>
                    <label class="col-sm-2 control-label">Year</label>
                    <label class="col-sm-1 control-label">Month</label>
                    <label class="col-sm-2 control-label">Remarks</label>
                    <label class="col-sm-1 control-label">Amount</label>
                    <label class="col-sm-1 control-label">Action</label>
                </div>-->
                <?php 
                $financemonth = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                foreach($PnlMaster as $pnl)
                {
                    
                ?>
                <div class="form-group">
                    <label class="col-sm-2 contro1-label">Description</label>
<!--                    Description-->
<label class="col-sm-6 control-label" style="text-align:left"><?php echo $pnl['PnlMaster']['Description']; ?></label>
                </div>
<!--                    Branch-->
                <?php if(in_array($pnl['PnlMaster']['ForPnlType'],array('Branch','Process','Both','MPR'))) { ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.Branch',array('label'=>false,'id'=>'Branch'.$pnl['PnlMaster']['PnlMasterId'],'options'=>$BranchMaster,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>"get_branch_costcenter(this.value,'".$pnl['PnlMaster']['EntryType']."','".$pnl['PnlMaster']['PnlMasterId']."')")); ?>
                    </div>
                </div>
                <?php } ?>
<!--                    Process-->
                
                    <?php if($pnl['PnlMaster']['EntryType']=='Process') { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Process</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.Process',array('label'=>false,'id'=>'Process'.$pnl['PnlMaster']['PnlMasterId'],'options'=>'','empty'=>'Select','required'=>true,'class'=>'form-control')); ?>
                        </div>
                    </div>
                    <?php } ?>
                    
                    
                

<!--                    Finance Year-->
                <div class="form-group">
                <label class="col-sm-2 control-label">Finance Year</label>    
                    <div class="col-sm-6">
                        <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.FinanceYear',array('label'=>false,'id'=>'FinanceYear'.$pnl['PnlMaster']['PnlMasterId'],'options'=>$finance_year,'empty'=>'Select','value'=>$FinanceYearLogin,'required'=>true,'class'=>'form-control')); ?>
                    </div>
                </div>    
<!--                    Finance Month-->
                    <div class="form-group">
                     <label class="col-sm-2 control-label">Finance Month</label>   
                    <div class="col-sm-6">
                        <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.FinanceMonth',array('label'=>false,'id'=>'FinanceMonth'.$pnl['PnlMaster']['PnlMasterId'],'options'=>$financemonth,'empty'=>'Select','required'=>true,'class'=>'form-control','onchange'=>"get_pnl_data('".$pnl['PnlMaster']['PnlMasterId']."','".$pnl['PnlMaster']['EntryType']."')")); ?>
                    </div>
                    </div>
<!--                    Remarks-->
                    <div class="form-group">
                     <label class="col-sm-2 control-label">Remarks</label>  
                    <div class="col-sm-6">
                        <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.Remarks',array('label'=>false,'id'=>'Remarks'.$pnl['PnlMaster']['PnlMasterId'],'placeholder'=>'Remarks','class'=>'form-control')); ?>
                    </div>
                    </div>
                    
                     <?php if($pnl['PnlMaster']['ForPnlType']=='MPR') { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Seat</label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.seat',array('label'=>false,'id'=>'seat'.$pnl['PnlMaster']['PnlMasterId'],'value'=>'','placeholder'=>'Seat','required'=>true,'class'=>'form-control','onBlur'=>"get_amt_calc({$pnl['PnlMaster']['PnlMasterId']})",'onKeypress'=>'return isNumberKey(event)')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Rate</label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.rate',array('label'=>false,'id'=>'rate'.$pnl['PnlMaster']['PnlMasterId'],'value'=>'','placeholder'=>'Rate','required'=>true,'class'=>'form-control','onBlur'=>"get_amt_calc({$pnl['PnlMaster']['PnlMasterId']})",'onKeypress'=>'return isNumberKey(event)')); ?>
                        </div>
                    </div>
                    <?php } ?>
                    
<!--                    Amount-->
<div class="form-group">
    <label class="col-sm-2 control-label">Amount</label> 
    <div class="col-sm-4">
                        <?php echo $this->Form->input($pnl['PnlMaster']['PnlMasterId'].'.Amount',array('label'=>false,'id'=>'Amount'.$pnl['PnlMaster']['PnlMasterId'],'placeholder'=>'Amount','required'=>true,'class'=>'form-control','onKeypress'=>'return isNumberKey(event)')); ?>
                    </div>
</div>
 <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-1">
                        <button type="button" name="button" id="button" class="btn btn-primary" onclick="save_records('<?php echo $pnl['PnlMaster']['PnlMasterId'];?>','<?php echo $pnl['PnlMaster']['EntryType'];?>')"  >Save</button>   
                    </div>
</div>
                <div id="Disp<?php echo $pnl['PnlMaster']['PnlMasterId'];?>"></div>
                <?php } ?>     
		<?php echo $this->Form->end();  ?>
            </div>
        </div>
    </div>
</div>

<script>
    function validate_pnl(value)
    {
        var options = '<option value="">Select</option>';
        if(value=='Process')
        {
           options += '<option value="Process">Process Wise</option>';
        }
        else if(value=='Branch')
        {
            options += '<option value="Branch">Branch Wise</option><option value="Process">Process Wise</option>';
        }
        else if(value=='Both')
        {
            options += '<option value="Process">Process Wise</option>';
        }
        $('#AddType').html(options);
    }
    function get_branch_costcenter(BranchId,EntryType,ReplaceId)
    {
        try{
        $('#Process'+ReplaceId).val('');
    }
    catch(err)
    {
        
    }
        $('#FinanceYear'+ReplaceId).val('');
        $('#FinanceMonth'+ReplaceId).val('');
        $('#Remarks'+ReplaceId).val('');
        $('#Amount'+ReplaceId).val('');
        
        if(EntryType=='Process')
        {
            $.post("getCostCenter",
            {
             BranchId: BranchId
            },
            function(data,status)
            {
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                $('#Process'+ReplaceId).empty();
                $('#Process'+ReplaceId).html(text);
            });
        }
    }
    
    function save_records(DescId,EntryType)
    {
        var BranchId,ProcessId,Remarks,Amount,FinanceYear,FinanceMonth,rate='',seat='',Flag=true;;
                
        if(DescId=='')
        {
            alert('Please Try Again');
        }
        else
        {
            BranchId = $('#Branch'+DescId).val();
            ProcessId = $('#Process'+DescId).val();
            Remarks = $('#Remarks'+DescId).val();
            seat = $('#seat'+DescId).val();
            rate = $('#rate'+DescId).val();
            Amount = $('#Amount'+DescId).val();
            FinanceYear = $('#FinanceYear'+DescId).val();
            FinanceMonth = $('#FinanceMonth'+DescId).val();
            
            if(BranchId=='')
            {
                alert('Please Select Branch');
                Flag = false;
            }
            else if(ProcessId=='' && EntryType=='Process')
            {
                alert('Please Select Process');
                Flag = false;
            }
            else if(FinanceYear=='')
            {
                alert('Please Select Finance Year');
                Flag = false;
            }
            else if(FinanceMonth=='')
            {
                alert('Please Select Finance Month');
                Flag = false;
            }
            else if(Amount=='')
            {
                alert('Please Fill Amount');
                Flag = false;
            }
            
            if(Flag)
            {
                $.post("save_record",
                {
                 DescId: DescId,
                 EntryType: EntryType,   
                 BranchId: BranchId,
                 ProcessId: ProcessId,
                 FinanceYear: FinanceYear,
                 FinanceMonth: FinanceMonth,
                 seat:seat,
                 rate:rate,
                 Remarks: Remarks,
                 Amount: Amount
                },
                function(data,status)
                {
                    try{
                            $('#Process'+DescId).val('');
                        }
                        catch(err)
                        {

                        }
                    $('#FinanceYear'+DescId).val('');
                    $('#FinanceMonth'+DescId).val('');
                    $('#Remarks'+DescId).val('');
                    $('#Amount'+DescId).val('');
        
                    $('#Disp'+DescId).html('');
                    $('#Disp'+DescId).html(data);
                });
            }
            
        }
    }
  
  function get_pnl_data(DescId,EntryType)
  {
        var BranchId,ProcessId,FinanceYear,FinanceMonth;
        BranchId = $('#Branch'+DescId).val();
        FinanceYear = $('#FinanceYear'+DescId).val();
        FinanceMonth = $('#FinanceMonth'+DescId).val();
        
        if(EntryType=='Branch')
        {    
            $.post("get_pnl_branch",
                {
                 DescId: DescId,
                 BranchId: BranchId,
                 FinanceYear: FinanceYear,
                 FinanceMonth: FinanceMonth
                },
                function(data,status)
                {
                    $('#Disp'+DescId).html('');
                    $('#Disp'+DescId).html(data);
                });
        }
        else if(EntryType=='Process')
        {
            ProcessId = $('#Process'+DescId).val();
            $.post("get_pnl_process",
                {
                 DescId: DescId,
                 BranchId: BranchId,
                 ProcessId: ProcessId,
                 FinanceYear: FinanceYear,
                 FinanceMonth: FinanceMonth
                },
                function(data,status)
                {
                    $('#Disp'+DescId).html('');
                    $('#Disp'+DescId).html(data);
                });
        }
  }
    
  function get_amt_calc(DecId)  
  {
      var seat = $('#seat'+DecId).val();
      var rate = $('#rate'+DecId).val();
      
      if(seat=='')
      {
          seat = 0;
      }
      if(rate=='')
      {
          rate = 0;
      }
      
      var amt = parseFloat(seat)*parseFloat(rate);
      $('#Amount'+DecId).val(amt);
      
  }
    
</script>