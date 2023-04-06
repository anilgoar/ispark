<?php
echo $this->Html->script('sample/datetimepicker_css');
?>
<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subHead").empty();
                $("#subHead").html(text);
                
            });  
}


function getAmountDetails()
{
    var BranchId=$("#branchId").val();
    var FinanceYear=$("#finance_year").val();
    var FinanceMonth=$("#finance_month").val();
    var HeadId=$("#head").val();
    var SubHeadId=$("#subHead").val();
    
    if(BranchId==''){ return;}
    if(FinanceYear==''){ return;}
    if(FinanceMonth==''){ return;}
    if(HeadId==''){ return;}
    if(SubHeadId==''){ return;}
    
    $.post("get_amount_desc",
            {
             BranchId: BranchId,
             FinanceYear: FinanceYear,
             FinanceMonth: FinanceMonth,
             HeadId: HeadId,
             SubHeadId: SubHeadId
            },
            function(data,status){
               
                var json = jQuery.parseJSON(data);
                var loop = 0;

                for(var i in json)
                {
                    $("#"+i).html(""+json[i]);
                    if(loop==3)
                    {
                        break;
                    }
                    else
                    {
                        loop++;
                    }
                }
                
                if(json['ApproveAmount']>0)
                {
                    getCostCenter();
                }
                else 
                {
                    alertify.error(json['error']);
                }
            });
}

function getCostCenter()
{
   var BranchId=$("#branchId").val();
   $.post("get_cost_center",
            {
             BranchId: BranchId
            },
            function(data,status){
              $('#costcenterBox').show();
              $('#costcenter').html(data);
            });
}

function getTotalCost(id)
{
    var Total = 0,costAmount; var cost=0;
    var costmaster = $('#costcenterIds').val().split(",");
    for(var i in costmaster)
    {
       costAmount = $('#cost'+costmaster[i]).val();
       if(costAmount!='')
       {
            cost = parseInt(costAmount);
            Total +=cost ||0;
       }
        else
            Total +=0;
    }
    if($('#BalanceAmount').html()<Total)
    {
        $('#cost'+id).val(0);
        alertify.error("Total Amount is not More Than Balance Amount or Reopen Business Case");
    }
    else
    {
        $('#Amount').val(Total);
    }
    
}
function validate_imprest()
{
    var entry_date = $('#entry_date').val();
    if(entry_date=='')
    {
        alert("Please Select Date");
        return false;
    }
    else if($('#Amount').val()=='' && $('#entry_status').val()=='Open')
    {
        alert("Amount is not empty");
        return false;
    }
    else
    {
        return true;
    }
    
}
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
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

<?php echo $this->Form->create('GrnEntries',array('class'=>'form-horizontal','action'=>'imprest_save')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Imprest Entry</h4>
    <h4 style="color:green"><?php echo $this->Session->flash(); ?></h4>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$FinanceYearArr,'class'=>'form-control','empty'=>'Select','id'=>'finance_year','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('FinanceMonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'finance_month','required'=>true)); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
        
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Expense Head</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('HeadId',array('label' => false,'options'=>$head,
                   'class'=>'form-control','empty'=>'Select','id'=>'head','onChange'=>"getSubHeading()",'required'=>true)); ?>
            
             <span class="input-group-addon"><i class="fa fa-group"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label">Expense Sub Head</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('SubHeadId',array('label' => false,'options'=>'',
                   'class'=>'form-control','empty'=>'Select','id'=>'subHead','onChange'=>"getAmountDetails()",'required'=>true)); ?>
            
             <span class="input-group-addon"><i class="fa fa-user"></i></span>  
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Approved Amount</label>
        <div class="col-sm-3">
            <div id="ApproveAmount">0.00</div>  
        </div>
        <label class="col-sm-2 control-label">Consumed Amount</label>
        <div class="col-sm-3">
              <div id="ConsumedAmount">0.00</div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-3">
             
        </div>
        <label class="col-sm-2 control-label">Balance Amount</label>
        <div class="col-sm-3">
            <div id="BalanceAmount">0.00</div>  
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Amount',array('label' => false,'value'=>'','placeholder'=>'Amount',
                   'class'=>'form-control','id'=>'Amount','onKeypress'=>'return isNumberKey(event)','required'=>true,'readonly'=>true)); ?>
               <span class="input-group-addon"><i class="fa fa-inr"></i></span> 
            </div>   
        </div>
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-3">
            
               <?php echo $this->Form->texArea('description',array('label' => false,'placeholder'=>'Description',
                   'class'=>'form-control','id'=>'description','required'=>true)); ?>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Dated</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('EntryDate',array('label' => false,
                   'class'=>'form-control','placeholder'=>'Date','id'=>'entry_date','onclick'=>"javascript:NewCssCal ('entry_date','ddMMyyyy','arrow',false,'24',false)",'readonly'=>true,'required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-calendar" onclick="javascript:NewCssCal ('entry_date','ddMMyyyy','arrow',false,'24',false)"></i></span> 
            </div>   
        </div>
        <label class="col-sm-2 control-label">Status</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('EntryStatus',array('label' => false,'options'=>array('Open'=>'Open','Close'=>'Close'),
                   'class'=>'form-control','id'=>'entry_status','required'=>true)); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
    </div>
    <div class="clearfix"></div>
    
   
</div>

<div class="row">
<div class="col-xs-12 col-sm-12" style="display: none;" id="costcenterBox">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Cost Center</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="costcenter"></div>
            </div>
        </div>
    </div>
</div>

<div class="box-content">
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <button name="FinalSave" value="Save" onclick="return validate_imprest()" class="btn btn-info">Submit</button> 
        </div>
        <div class="col-sm-2">
            <button name="Back" value="Back" class="btn btn-info">Back</button>
        </div>
    </div>
    <div class="clearfix"></div> 
</div>

 <?php echo $this->Form->end(); ?>