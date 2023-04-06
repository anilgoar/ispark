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
function loanReport(Type){ 
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var BranchName=$("#BranchName").val();
    var StartDate=$("#StartDate").val();
    var EndDate=$("#EndDate").val();
    var Status=$("#Status").val();
    var EmpCode=$("#EmpCode").val();
    
    if(BranchName ===""){
        $(".BranchName").removeClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(StartDate ===""){
        $(".StartDate").removeClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select start month.</span>");
        return false;
    }
    else if(EndDate ===""){
        $(".EndDate").removeClass('bordered');
        $("#EndDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select end month.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>LoanMasters/show_report",{BranchName:BranchName,StartDate:StartDate,EndDate:EndDate,Status:Status,EmpCode:$.trim(EmpCode)}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divLoan").html(data);
                }
                else{
                    $("#divLoan").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>LoanMasters/export_report?BranchName="+BranchName+"&StartDate="+StartDate+"&EndDate="+EndDate+"&Status="+Status+"&EmpCode="+$.trim(EmpCode);  
           
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
                    <span>LOAN DETAILS</span>
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
                <?php echo $this->Form->create('LoanMasters',array('action'=>'loandetails','class'=>'form-horizontal')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">FromMonth</label>
                    <div class="col-sm-2">
                        <input type="text" name="StartDate" id="StartDate"   autocomplete="off" readonly="" class="form-control datepik"  >
                    </div>
                    
                    
                    <label class="col-sm-1 control-label">ToMonth</label>
                    <div class="col-sm-2">
                        <input type="text" name="EndDate" id="EndDate"   autocomplete="off" readonly="" class="form-control datepik"  >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-2">
                        <select id="Status" name="Status" autocomplete="off" class="form-control" >
                            <option value="ALL">ALL</option>
                            <option value="Applied">Applied</option>
                            <option value="Approve BM">Approve BM</option>
                            <option value="Not Approve BM">Not Approve BM</option>
                            <option value="Approve HO">Approve HO</option>
                            <option value="Not Approve HO">Not Approve HO</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control" >
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus/loan"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="loanReport('Export');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                         
                        <input type="button" onclick="loanReport('show');" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                
                <div class="form-group" id="divLoan" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



