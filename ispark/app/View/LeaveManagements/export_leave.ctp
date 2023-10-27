<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#FromDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#ToDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>    
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>LEAVE EXPORT</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            
            <div class="box-content box-con">
                <div style="margin-left: 170px;" ><?php echo $this->Session->flash(); ?></div>
                <?php echo $this->Form->create('LeaveManagements',array('class'=>'form-horizontal','action'=>'export_leave')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-4">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                
                
                
                
                
                
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-2">
                        <input type="text" name="FromDate" id="FromDate"  placeholder="Start Date"  autocomplete="off" class="form-control" required="" >
                    </div>
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <input type="text" name="ToDate" id="ToDate"  placeholder="End Date" autocomplete="off" class="form-control" required="" >
                    </div>
                </div>
                
                
               
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-primary pull-right btn-new"  value="Submit">
                    </div> 
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>