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

<script>
function search_employees(EmpName){
    var BranchName=$("#BranchName").val();
    $("#msgerr").remove();  
    if(BranchName ===""){
        $("#searchEmp" ).val('');
        $("#BranchName" ).after("<span id='msgerr' >Please select branch name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>OdApplys/get_emp",{'EmpName':EmpName,'BranchName':BranchName}, function(data) {
            $("#EmpNameCode" ).html(data);
        });
    }
}

function getBranch(branch){
    if(branch !=""){
        $("#msgerr").remove(); 
        $("#searchEmp" ).val('');
    }
}

function validateOdApply(){
    $("#msgerr").remove();
    var FromDate=$("#FromDate").val();
    var ToDate=$("#ToDate").val();
    var cudat = new Date();
    var date1 = new Date(FromDate);
    var date2 = new Date(ToDate);
        
    if(date2 < date1){
        $("#ToDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date.</span>");
        return false;
    }
    else if(date1 > cudat){
        $("#ToDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date.</span>");
        return false;
    }
    else if(date2 > cudat){
        $("#ToDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date.</span>");
        return false;
    }
    else{
        return true;
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
                    <span>OD APPLICATION FORM</span>
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
                <?php echo $this->Form->create('OdApplys',array('class'=>'form-horizontal','action'=>'index','onSubmit'=>'return validateOdApply()')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','value'=>$this->Session->read('branch_name'),'empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name of Applicant</label>
                    <div class="col-sm-3">
                        <input type="text" name="searchEmp" autocomplete="off" placeholder="Search employees name" id="searchEmp" onkeyup="search_employees(this.value)" class="form-control" required="" >
                    </div>
                </div>
                
                 <div class="form-group">
                     <label class="col-sm-2 control-label">Employees Code</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="EmpNameCode" autocomplete="off"  name="EmpNameCode" required="" >
                        </select>
                    </div> 
                </div>
                
               
                
               
                <div class="form-group">
                    <label class="col-sm-2 control-label">OD Date</label>
                    <div class="col-sm-2">
                        <input type="text" name="FromDate" id="FromDate" placeholder="Start Date" required="" autocomplete="off" class="form-control" >
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="ToDate" id="ToDate" required="" placeholder="End Date" autocomplete="off" class="form-control" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Reason for OD</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="OdReason" required="" autocomplete="off" ></textarea>
                    </div> 
                </div>
                
               
                
                <div class="form-group">
                    <div class="col-sm-4">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='submit' class="btn btn-primary pull-right btn-new"  value="Submit">
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

