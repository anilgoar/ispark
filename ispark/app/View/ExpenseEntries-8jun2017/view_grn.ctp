<script>
function checkAllBox()
{
    if($("#checkAll").prop('checked'))
    $('input:checkbox').add().prop('checked','checked');
    else
     $('input:checkbox').add().prop('checked',false);   
}

function getGrnNos()
{
    var branchId = $('#branchId').val();
    var GrnNo = $('#grn_no').val();
    
    $.post("get_grn_no",
            {
             Branch: branchId,
             grn_no:GrnNo
            },
            function(data,status)
            {
                $("#grnNoIds").empty();
                $("#grnNoIds").html(data);
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
    <h4 class="page-header textClass">Delete GRN No</h4>
    <h4 style="color:green"><?php echo $this->Session->flash(); ?></h4>
    <div class="form-group">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('branchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Finance Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceYear',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'GRN No','id'=>'grn_no')); ?>
                <span class="input-group-addon"><i class="fa fa-Number"></i></span>  
            </div>    
        </div>
        <label class="col-sm-2 control-label">Finance Month</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceMonth',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'GRN No','id'=>'grn_no')); ?>
                <span class="input-group-addon"><i class="fa fa-Number"></i></span>  
            </div>    
        </div>
        <label class="col-sm-2 control-label">Expense Head</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('HeadId',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'GRN No','id'=>'grn_no')); ?>
                <span class="input-group-addon"><i class="fa fa-Number"></i></span>  
            </div>    
        </div>
        <label class="col-sm-2 control-label">Expense SubHead</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('SubHeadId',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'GRN No','id'=>'grn_no')); ?>
                <span class="input-group-addon"><i class="fa fa-Number"></i></span>  
            </div>    
        </div>
    </div>
   
    <div class="form-group">
        <div id="grnNoIds"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Remarks</label>
        <div class="col-sm-3">
        <?php echo $this->Form->textarea('Remarks',array('label' => false,'value'=>'','class'=>'form-control','placeholder'=>'Remarks','rows'=>'5','required'=>true)); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-5"></div>
        <div class="col-sm-2"><button type="submit" name="submit" value="Booked" class="btn btn-info">Booked</button></div>
    </div>    
    
       <div class="clearfix"></div>
</div>