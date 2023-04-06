<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'M-yy'
    });
});
</script>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>IncentiveDiscards/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function descardReport(Type){ 
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    var StartDate=$("#StartDate").val();
    
    if(BranchName ===""){
        $(".BranchName").removeClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $(".CostCenter").removeClass('bordered');
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select costcenter.</span>");
        return false;
    }
    else if(StartDate ===""){
        $(".StartDate").removeClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>IncentiveDiscards/show_report",{BranchName:BranchName,StartDate:StartDate,CostCenter:CostCenter}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divDiscard").html(data);
                }
                else{
                    $("#divDiscard").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
    }
}
</script>



<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>INCENTIVE DISCARD</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('IncentiveDiscards',array('action'=>'index','class'=>'form-horizontal','id'=>'showDetails')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <input type="text" name="StartDate" id="StartDate"   autocomplete="off" readonly="" class="form-control datepik"  >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="descardReport('show');" value="Show" class="btn pull-right btn-primary btn-new">
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                    
                </div>
            
                
                <div class="form-group" id="divDiscard" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



