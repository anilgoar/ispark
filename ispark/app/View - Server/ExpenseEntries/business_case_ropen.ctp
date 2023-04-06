<script>
$(document).ready(function(){
    $("#finance_month").on('change',function(){
        var branch_id=$("#branchId").val();
        var finance_year=$("#finance_year").val();
        var finance_month=$("#finance_month").val();
        
        $.post("get_business_case",
            {
             branchId: branch_id,
             finance_year:finance_year,
             finance_month:finance_month
            },
            function(data,status){
                //alert(data);
                var text="<option value=''>Select</option>";
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#busi_id").empty();
                $("#busi_id").html(text);
            });
     });   
});

function getExpneseAmount(ExpenseId)
{
    $.post("get_expense_amount",
            {
             
             ExpenseId:ExpenseId
            },
            function(data,status){
                $("#busi_amount").empty();
                $("#busi_amount").html(data);
            });
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


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Business Case Reopen Request</h4>
    <?php echo '<font color="green">'.$this->Session->flash().'</font>'; ?>
    <?php echo $this->Form->create('ExpenseEntries',array('class'=>'form-horizontal')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-3 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$financeYearArr,'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-3 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('FinanceMonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'finance_month')); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
        
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label">Business Case Reopen</label>
        <div class="col-sm-9">
            <div class="input-group">
               <?php echo $this->Form->input('ExpenseId',array('label' => false,'options'=>'',
                   'class'=>'form-control','empty'=>'Select','id'=>'busi_id','onChange'=>"getExpneseAmount(this.value)",'required'=>true)); ?>
             <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>  
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label">Approved Amount</label>
        <div class="col-sm-3">
            <div id="busi_amount"></div>
        </div>
        <label class="col-sm-3 control-label">Additional Amount</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('AdditionalAmount',array('label' => false,'value'=>'',
                   'class'=>'form-control','placeholder'=>'Additional Amount','id'=>'add_amount','onkeypress'=>"return ((event.charCode >= 45 && event.charCode <= 57) || event.charCode==45)",'required'=>true)); ?>
             <span class="input-group-addon"><i class="fa fa-inr"></i></span>  
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label">Description</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('Description',array('label' => false,'options'=>array('Provision not made'=>'Provision not made',
                   'Wrong estimate done'=>'Wrong estimate done','Immediate requirement'=>'Immediate requirement','Previous Month Expense'=>'Previous Month Expense',
                   'Business case closed wrongly'=>'Business case closed wrongly'),
                   'class'=>'form-control','empty'=>'Select','id'=>'description','required'=>true)); ?>
        </div>
        <label class="col-sm-3 control-label">remarks</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->textarea('Remarks',array('label' => false,'value'=>'',
                   'class'=>'form-control','placeholder'=>'remarks','id'=>'remarks','required'=>true)); ?>
             <span class="input-group-addon"><i class="fa fa-list"></i></span>  
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-3">
            <button type='submit' class="btn btn-info" value="Save">Make Request</button>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>