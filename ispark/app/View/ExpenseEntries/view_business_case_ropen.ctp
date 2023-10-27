<script>
$(document).ready(function(){
    $("#Show").on('click',function(){
        var branch_id=$("#branchId").val();
        var finance_year=$("#finance_year").val();
        var finance_month=$("#finance_month").val();
        
        $.post("get_business_case_request",
            {
             branchId: branch_id,
             finance_year:finance_year,
             finance_month:finance_month
            },
            function(data,status)
            {
                $("#businessCase").empty();
                $("#businessCase").html(data);
            });
     });   
});

function checkAllBox()
{
    if($("#checkAll").prop('checked'))
    $('input:checkbox').add().prop('checked','checked');
    else
     $('input:checkbox').add().prop('checked',false);   
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

<?php echo $this->Form->create('ExpenseEntries',array('class'=>'form-horizontal')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Expense Entry</h4>
    <?php echo '<font color="green">'.$this->Session->flash().'</font>'; ?>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$financeYearArr,'class'=>'form-control','empty'=>'Select','value'=>$FinanceYearLogin,'id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('FinanceMonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'finance_month')); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-2">
            <div class="btn btn-info" id="Show">Show</div>
            <a href="/ispark/Menuisps/sub?AX=NTk=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
    </div>
    <div class="clearfix"></div>
    
   
</div>

<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">View Business Case Reopen Request</h4>
    <div class="form-group has-info has-feedback">
        <div id="businessCase"></div>
    </div>    
    <div class="form-group has-info has-feedback">
        <div class="col-sm-5"></div>
        <div class="col-sm-2">
        <button class="btn btn-info">Open</button>
        </div>
    </div>    
    <div class="clearfix"></div>
</div>
 <?php echo $this->Form->end(); ?>