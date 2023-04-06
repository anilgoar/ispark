<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
       
    });
});
</script>


<script>
function search_empcode(EmpCode){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var BranchName=$("#BranchName").val();
    
    if(BranchName ===""){
        $("#EmpCode").val('');
        $("#BranchName").addClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>JoiningMasters/get_loan_details",{BranchName:BranchName,EmpCode:$.trim(EmpCode)}, function(data) {
            $("#showemp").html(data);
        });
    }
    
}

function checkJoinDate(newjoin){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var oldjoin=$("#oldjoin").val();
    var posts   = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>JoiningMasters/check_date",async: false,dataType: 'json',data: {oldjoin:oldjoin,newjoin:newjoin},done: function(response) {return response;}}).responseText;	
    var posts1  = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>JoiningMasters/check_date1",async: false,dataType: 'json',data: {oldjoin:oldjoin,newjoin:newjoin},done: function(response) {return response;}}).responseText;	
    
    if(posts !=""){
        $("#StartDate").val('');
        $("#StartDate").addClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Select correct join date.</span>");
        return false;
    }
    else if(posts1 !=""){
        $("#StartDate").val('');
        $("#StartDate").addClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Join date change allow only for current & next month.</span>");
        return false;
    }
    else{
        return true;
    }
}

function validateDoj(){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var BranchName=$("#BranchName").val();
    var EmpCode=$("#EmpCode").val();
    var StartDate=$("#StartDate").val();
    var Reason=$("#Reason").val();
    
    if(BranchName ===""){
        $("#BranchName").addClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#EmpCode").addClass('bordered');
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter empcode.</span>");
        return false;
    }
    else if(StartDate ===""){
        $("#StartDate").addClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select actual DOJ.</span>");
        return false;
    }
    else if(Reason ===""){
        $("#Reason").addClass('bordered');
        $("#Reason").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter reason.</span>");
        return false;
    }
    else{
        return true;
    }
}

function capitalize(textboxid, str) {
    var res = str.toUpperCase();
    document.getElementById(textboxid).value =  res;
}

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}

</script>
<style>
.bordered{
    border-color: red;
}
</style>
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
                    <span>DOJ CHANGE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('JoiningMasters',array('action'=>'index','class'=>'form-horizontal','onsubmit'=>'return validateDoj();')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value)')); ?>
                    </div>
                    
                    <label class="col-sm-2 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" name="EmpCode" id="EmpCode" onkeyup="search_empcode(this.value)" class="form-control" autocomplete="off"  >
                    </div> 
                </div>
                
                <div class="form-group" id="showemp" ></div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">ActualDOJ</label>
                    <div class="col-sm-2">
                        <input type="text" onchange="checkJoinDate(this.value)" name="StartDate" id="StartDate"   autocomplete="off" readonly="" class="form-control datepik"  >
                    </div>
                </div>
                
     
                
                <div class="form-group">
                   <label class="col-sm-2 control-label">Remarks</label>
                   
                    <div class="col-sm-4">
                        <textarea name="Reason" id="Reason" class="form-control" autocomplete="off" ></textarea>
                    </div>
                   <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    
                </div>
                
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>	
</div>



