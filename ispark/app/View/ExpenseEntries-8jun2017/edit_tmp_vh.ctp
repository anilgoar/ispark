<?php //print_r($data); exit; ?>
<script>
    tinymce.init({ selector:'textarea' });
$(document).ready(function(){
    //alertify.success('Success notification message.'); 
        $("#head").on('change',function(){
        
        $("#unitBox").hide();
        $("#costcenterBox").hide();
        $("#particularBox").hide();
        
        $("#costcenter").html('');
        $("#particular").html('');
        $("#unit").html('');
        
        checkHistoryExists();
     });
     
     $("#subHead").on('change',function(){
        
        $("#unitBox").hide();
        $("#costcenterBox").hide();
        $("#particularBox").hide();
        
        $("#costcenter").html('');
        $("#particular").html('');
        $("#unit").html('');
        
        
        
        checkHistoryExists2();
        
        
     });
});

function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                
                //alert(text);
                $("#subHead").empty();
                $("#subHead").html(data);
                
            });  
}

function OldHisDelete()
{
    var ExpenseId = $("#id").val();
    $.post("get_old_delete",
            {
                ExpenseId: ExpenseId
            },
            function(data,status){
                if(data=='1')
                    alertify.success('All Previous Expense has been deleted successfully');  
                else
                    alertify.error('Expense not deleted. Please try again');  
            });
    getSubHeading();
}

function checkHistoryExists()
{
  var ExpenseId = $("#id").val();  
    getSubHeading();
//  $.post("get_his_check",
//            {
//             ExpenseId: ExpenseId
//            },
//            function(data,status){
//                if(data=='1')
//                {
//                    alertify.confirm('Are you sure', 'Delete Old Previous Entry', function(){ OldHisDelete(); }
//                , function(){ $('#head').val('');return;});
//                }
//                else
//                {
//                    getSubHeading();
//                }
//            });   
}

function checkHistoryExists2()
{
  var ExpenseId = $("#id").val();
  var branch          = $('#branchId').val();
    var financeYear     = $('#financeYear').val();
    var financeMonth    = $('#financeMonth').val();
    var ExpenseHead     = $('#head').val();
    var ExpenseSubHead  = $('#subHead').val();
    
  $.post("get_his_check2",
            {
             ExpenseId: ExpenseId,
             branch:branch,
             financeYear:financeYear,
             financeMonth:financeMonth,
             ExpenseHead:ExpenseHead,
             ExpenseSubHead:ExpenseSubHead
            },
            function(data,status){
                if(data=='1')
                {
                    alertify.error("Entry Already Exist! Please Reopen Business Case");
                    $('#subHead').val('');
                }
                else if(data=='2')
                {
                    alertify.confirm('Are you sure', 'Delete Old Previous Entry', function(){ OldHisDelete(); }
                , function(){ $('#head').val('');return;});
                }
                else
                {
                    var HeadingId=ExpenseHead;
                    var SubHeadingID = ExpenseSubHead;
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
                }
            });   
}


function get_costcenter_breakup(branch,unitId)
{
    var ExpenseId = $("#id").val();
    
    $("#particularBox").hide();
    $("#particular").html('');
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
    
    workstation=workstation||0;
    mannual = mannual||0;
    revenue = revenue||0;
    //alert(ExpenseSubHead);
    //return false;
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
        alertify.success('Cost Center '+data+' Expense of '+Total+' has been Save Successfully'); 
    });
    return false;
}

function expense_check()
{
    var a = $('#TotalAmount').val();
    if(a>0)
    {return true; }
    else 
    {alert("Total Amount Should Not Be 0"); return false;}
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

<?php echo $this->Form->create('ExpenseEntries',array('class'=>'form-horizontal','action'=>'vh_final_save','enctype'=>'multipart/form-data')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Expense Entry Master</h4>
    <h4><?php echo $this->Session->flash();?></h4>
    <table class="table">
        <tr><th>Branch</th><th>Year</th><th>Month</th></tr>
        <tr><th><?php echo $data['Branch']; ?></th><th><?php echo $data['FinanceYear']; ?></th><th><?php echo $data['FinanceMonth']; ?></th></tr>
    </table>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Expense Head</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('head',array('label' => false,'options'=>$head,'empty'=>'Select','value'=>$data['HeadId'],'class'=>'form-control','id'=>'head')); ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Expense Sub Head</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('subHead',array('label' => false,'options'=>$Subhead,'value'=>$data['SubHeadId'],'class'=>'form-control','id'=>'subHead')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Total Amount</label>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $this->Form->input('TotalAmount',array('label' => false,'value'=>$data['Amount'],'class'=>'form-control','id'=>'TotalAmount')); ?>
                <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div> 
</div>

<div class="row">
    <div class="col-xs-12 col-sm-4" style="<?php if($div!='unit') echo "display: none;"?>" id="unitBox">
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
                <div id="unit">
                    <?php if($div!='costcenter') echo $html;?>
                </div>
                
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-4" style="<?php if($div!='costcenter') echo "display: none;";?>" id="costcenterBox">
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
                <div id="costcenter">
                     <?php if($div=='costcenter') echo $html;?>
                </div>
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
                <div id="particular">
                   
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box-content">
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label" for="form-styles">Upload Business Case</label>
        <div class="col-sm-4">
        <?php echo $this->Form->file('PaymentFile',array('label'=>false,'accept'=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel")); ?>
        </div>
        <?php if(!empty($data['PaymentFile'])) { ?> 
        <label class="col-sm-2 control-label" for="form-styles">Download Business Case</label> 
        <div class="col-sm-4">
        <a href="<?php echo $this->html->webroot('expense_file'.DS.$data['PaymentFile']); ?>"><?php echo $this->Html->image('download.png', array('alt' => "download",'hieght'=>'15','width'=>'15','class' => 'img-rounded'));?> Click Here</a>
        </div>
        <?php } ?>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label" for="form-styles">Background and Objective</label>
        <div class="col-sm-10">
        <?php echo $this->Form->textarea('objective',array('label'=>false,'class'=>'form-control','rows'=>'10','value'=>$data['objective'],'required'=>true)); ?>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label" for="form-styles">Payment Methodology & Validation process</label>
        <div class="col-sm-10">
        <?php echo $this->Form->textarea('Methodology',array('label'=>false,'class'=>'form-control','rows'=>'10','value'=>$data['Methodology'],'required'=>true)); ?>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <button name="FinalSave" onclick="return expense_check()" value="Save" class="btn btn-info">Approve</button> 
        </div>
        <div class="col-sm-2">
            <a href="<?php echo $this->webroot.'ExpenseEntries/discard?action=view&id='.$data['Id'];?>" class="btn btn-info">Disapprove</a>
        </div>
    </div>
    <div class="clearfix"></div> 
</div>
<?php 
echo $this->Form->input('id',array('label'=>false,'type'=>'hidden', 'value'=>$data['Id'],'id'=>'id'));
echo $this->Form->input('branchId',array('label'=>false,'type'=>'hidden', 'value'=>$data['BranchId'],'id'=>'branchId'));
echo $this->Form->input('branch',array('label'=>false,'type'=>'hidden', 'value'=>$data['Branch'],'id'=>'branch'));
echo $this->Form->input('financeYear',array('label'=>false,'type'=>'hidden', 'value'=>$data['FinanceYear'],'id'=>'financeYear'));
echo $this->Form->input('financeMonth',array('label'=>false,'type'=>'hidden', 'value'=>$data['FinanceMonth'],'id'=>'financeMonth'));
echo $this->Form->end(); ?>