<script>
var branch_id=$("#branch").val();
$.post({url:'getCostCenter',data:{branch:branch},success(data){
      $("#branch_cost_center").text(data);  
}});
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
    <h4 class="page-header textClass">Revenue Entry</h4>

    <?php echo $this->Form->create('ExpenseEntries',array('class'=>'form-horizontal','action'=>'add_grn')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branch')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Cost Center</label>
        <div class="col-sm-3">
            <div class="input-group">
                <div id="branch_cost_center">
                    <?php echo $this->Form->input('cost_center',array('label' => false,'options'=>'','class'=>'form-control','empty'=>'Select','id'=>'cost_center')); ?>
                </div>
            <span class="input-group-addon"><i class="fa fa-exchange"></i></span>    
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('finance_year',array('label' => false,'options'=>array('2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('month',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'month')); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Entry No.</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('entry_no',array('label' => false,'options'=>array('1'=>'1'),'class'=>'form-control','id'=>'EntryNo')); ?>
                <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
            </div>
        </div>
        <label class="col-sm-2 control-label">Remarks</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->textArea('remarks',array('label' => false,
                   'class'=>'form-control','placeholder'=>'Remarks','id'=>'remarks')); ?>
                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
            </div>
        </div>
    </div>
    
    <h4 class="page-header textClass">Unit +/</h4>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Type</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('case_type',array('label' => false,'options'=>array('1'=>'1'),'empty'=>'Select','class'=>'form-control','id'=>'case_type')); ?>
        </div>
        <label class="col-sm-1 control-label">Rate</label>
        <div class="col-sm-2">
            <div id="branch_cost_center">
               <?php echo $this->Form->input('remarks',array('label' => false,
                   'class'=>'form-control','placeholder'=>'Rate','id'=>'rate')); ?>
            </div>
        </div>
        <label class="col-sm-1 control-label">Count</label>
        <div class="col-sm-2">
            <div id="branch_cost_center">
               <?php echo $this->Form->input('count',array('label' => false,
                   'class'=>'form-control','placeholder'=>'Count','id'=>'count')); ?>
            </div>
        </div>
        <label class="col-sm-1 control-label">Total</label>
        <div class="col-sm-2">
            <div id="branch_cost_center">
               <?php echo $this->Form->input('Total',array('label' => false,'class'=>'form-control',
                   'placeholder'=>'Total','id'=>'Total','readonly'=>true,'required'=>true)); ?>
            </div>
        </div>
    </div>
    
    
    <h4 class="page-header textClass">Lump Sum+/-</h4>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-1 control-label">Description</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('description',array('label' => false,'options'=>array('1'=>'1'),'empty'=>'Select','class'=>'form-control','id'=>'description')); ?>
        </div>
        <label class="col-sm-1 control-label">Amount</label>
        <div class="col-sm-2">
            <div id="branch_cost_center">
               <?php echo $this->Form->input('amount',array('label' => false,
                   'class'=>'form-control','placeholder'=>'Amount','id'=>'Amount')); ?>
            </div>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <div id="branch_cost_center">
                <input type='submit' class="btn btn-info" value="Save">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>