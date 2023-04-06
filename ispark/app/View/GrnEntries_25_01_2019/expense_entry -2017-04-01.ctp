<?php //print_r($data); exit; ?>
<script>
$(document).ready(function(){
    $("#head").on('change',function(){
        checkHistoryExists();
        alertify.confirm('Are you sure', 'Delete Old Previous Entry', function(){ deleteOld(); }
                , function(){ return;});
        
        
        
        
     });
     $("#subHead").on('change',function(){
        var HeadingId=$("#head").val();
        var SubHeadingID = $(this).val();
        var branchName = $("#branch").val();
        $.post("get_breakup",
            {
             HeadingId: HeadingId,
             SubHeadingID:SubHeadingID,
             branch: branchName
            },
            function(data,status){
                //$("#unit").html(data);
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    $("#"+i).html(json[i]);
                    $("#"+i+"Box").show();
                }
                
            });
     });
});

function deleteOld()
{
    var HeadingId=$("#head").val();
  $.post("get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text="";
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
function get_costcenter_breakup(branch,unitId)
{
    var ExpenseId = $("#id").val();
        $.post("get_costcenter_breakup",
            {
                branch: branch,
                unitId:unitId,
                ExpenseId:ExpenseId
            },
            function(data,status){
                $("#costcenter").html(data);
                $("#costcenterBox").show();
            });
}

function get_particular_breakup(costcenter)
{
    var unitId ='';
     var ExpenseId = $("#id").val();
    try{
        unitId=document.getElementById("unitId").value;
    }
    catch(err){unitId='';}
    
    $.post("get_particular_breakup",
    {
        costcenter:costcenter,
        unitId:unitId,
        ExpenseId:ExpenseId
    },
    function(data,status){
        $("#particular").html(data);
        $("#particularBox").show();
    });
}
function get_particular_check(cost_id)
{
    var unitId =''; 
    try{
        unitId=document.getElementById("unitId").value;
        //alert(unitId);
    }
    catch(err){unitId='';}
    
    var particular={'workstation':0,'mannual':0,'revenue':0},Total=0;
     particular.workstation = $('#amountparticular1').val();
     particular.mannual     = $('#amountparticular2').val();
     particular.revenue     = $('#amountparticular3').val();
    for(var i in particular)
    {
        if(particular[i]=='') 
        {
            particular[i] = 0;
        }
        else
        {
            Total += parseInt(particular[i]);
        }
    }
    
    var j=1;
    for(var i in particular)
    {
        if(particular[i]=='') {    $('#perparticular'+j++).val(0+"%");    }
        else    {   $('#perparticular'+j++).val((parseInt(particular[i])*100/Total)+"%"); }
    }
    $('#amountcostcenter'+cost_id).val(Total);
    
    
    costCenterSum(unitId);
    
}
function costCenterSum(unitId)
{
    var Total = 0,costAmount;
    var costmaster = $('#costmaster').val().split(",");
    for(var i in costmaster)
    {
       costAmount = $('#amountcostcenter'+costmaster[i]).val();
       if(costAmount!='')
            Total +=parseInt(costAmount);
        else
            Total +=0;
    }
    for(var i in costmaster)
    {
       costAmount = $('#amountcostcenter'+costmaster[i]).val();
       if(costAmount!='')
            $('#percostcenter'+costmaster[i]).val((parseInt(costAmount)*100/Total)+'%');
        
    }
    if(unitId!='')
    {
       $('#amountunit'+unitId).val(Total);
       unitSum();
    }
    else
    {
        $('#TotalAmount').val(Total);
    }    
}

function unitSum()
{
    var Total = 0,unitAmount;
    var unitmaster = $('#unitmaster').val().split(",");
    for(var i in unitmaster)
    {
       unitAmount = $('#amountunit'+unitmaster[i]).val();
       if(unitAmount!='')
            Total +=parseInt(unitAmount);
        else
            Total +=0;
    }
    
    for(var i in unitmaster)
    {
      unitAmount = $('#amountunit'+unitmaster[i]).val();
       if(unitAmount!='')
            $('#perunit'+unitmaster[i]).val((parseInt(unitAmount)*100/Total)+'%');
    }
    $('#TotalAmount').val(Total);
}

function ExpenseSave(cost_center)
{
    var unitId =''; 
    try{
        unitId=document.getElementById("unitId").value;
    }
    catch(err){unitId='';}
    
    var id = $('#id').val();
    var workstation     = $('#amountparticular1').val();
    var mannual         = $('#amountparticular2').val();
    var revenue         = $('#amountparticular3').val();
    var branch          = $('#branchId').val();
    var financeYear     = $('#financeYear').val();
    var financeMonth    = $('#financeMonth').val();
    var ExpenseHead     = $('#head').val();
    var ExpenseSubHead  = $('#subHead').val();
    
    var Total = parseInt(workstation)+parseInt(mannual)+parseInt(revenue);
    $.post("expense_save",
    {
     Id :id,
     workstation: workstation,
     mannual: mannual,
     revenue: revenue,
     cost_center: cost_center,
     unitId: unitId,
     branch: branch,
     financeYear: financeYear,
     financeMonth: financeMonth,
     ExpenseHead: ExpenseHead,
     ExpenseSubHead:ExpenseSubHead
    },
    function(data,status){
        //$("#costcenter").html(data);
        //$("#costcenterBox").show();
        alertify.success('Cost Center Expense of '+Total+' has been Save Successfully'); 
    });
    return false;
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

<?php echo $this->Form->create('GrnEntries',array('class'=>'form-horizontal','action'=>'add_grn')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Expense Entry Master</h4>    
    <table class="table">
        <tr><th>Branch</th><th>Year</th><th>Month</th></tr>
        <tr><th><?php echo $data['branch_name']; ?></th><th><?php echo $data['finance_year']; ?></th><th><?php echo $data['finance_month']; ?></th></tr>
    </table>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Expense Head</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('head',array('label' => false,'options'=>$head,'class'=>'form-control','id'=>'head')); ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Expense Sub Head</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('subHead',array('label' => false,'options'=>'','class'=>'form-control','id'=>'subHead')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Total Amount</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('TotalAmount',array('label' => false,'class'=>'form-control','id'=>'TotalAmount')); ?>
                <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div> 
</div>

<div class="row">
    <div class="col-xs-12 col-sm-4" style="display: none;" id="unitBox">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Unit Entry</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                            <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                            <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="unit"></div>
                
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-4" style="display: none;" id="costcenterBox">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Cost Center Entry</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                            <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                            <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="costcenter"></div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-4" style="display: none;" id="particularBox">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Particular</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                            <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                            <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <div id="particular"></div>
            </div>
        </div>
    </div>
</div>
<div class="box-content">
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <button name="FinalSave" value="Save" class="btn btn-info">Final Save</button> 
        </div>
        <div class="col-sm-2">
            <button name="Back" value="Back" class="btn btn-info">Back</button>
        </div>
    </div>
    <div class="clearfix"></div> 
</div>
<?php 
echo $this->Form->input('id',array('label'=>false,'type'=>'hidden', 'value'=>$data['id'],'id'=>'id'));
echo $this->Form->input('branchId',array('label'=>false,'type'=>'hidden', 'value'=>$data['branchId'],'id'=>'branchId'));
echo $this->Form->input('branch',array('label'=>false,'type'=>'hidden', 'value'=>$data['branch_name'],'id'=>'branch'));
echo $this->Form->input('financeYear',array('label'=>false,'type'=>'hidden', 'value'=>$data['finance_year'],'id'=>'financeYear'));
echo $this->Form->input('financeMonth',array('label'=>false,'type'=>'hidden', 'value'=>$data['finance_month'],'id'=>'financeMonth'));
echo $this->Form->end(); ?>