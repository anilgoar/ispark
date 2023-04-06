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
function getReason(type){
    $("#othres").html(''); 
    if(type ==="Others"){
        $("#othres").html('<div class="col-sm-2">Other Reason</div><div class="col-sm-2"><input type="text" name="OtherReason" id="OtherReason" autocomplete="off" required="" class="form-control"></div>');
    }
    else{
      $("#othres").html('');  
    }
}

function search_employees(EmpName){
    $.post("<?php echo $this->webroot;?>LoanMasters/get_emp",{'EmpName':$.trim(EmpName)}, function(data) {
        $("#GuarantorEmpCode" ).html(data);
    }); 
}

function search_empcode(EmpCode){
    $.post("<?php echo $this->webroot;?>LoanMasters/get_empcode",{'EmpCode':$.trim(EmpCode)}, function(data) {
        $("#EmpName").val(data);
    });
    
    $.post("<?php echo $this->webroot;?>LoanMasters/get_loan_details",{'EmpCode':$.trim(EmpCode)}, function(data) {
        $("#LoanHistory").html(data);
    });
    
}

function search_installments(insdata){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var StartDate=$("#StartDate").val();
    var Amount=$("#Amount").val();
    var Type=$("#Type").val();
    if(Type =="Advance" && insdata > 1){
        var installments=1;
        $("#Installments").val(1);
    }
    else{
        var installments=insdata;
    }
    
    
    
    if(Amount ===""){
        $("#Amount").addClass('bordered');
        $("#Amount").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter amount.</span>");
        $("#Installments").val('');
        return false;
    }
    else if(StartDate ===""){
        $("#StartDate").addClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select start date.</span>");
        $("#Installments").val('');
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>LoanMasters/get_month",{'installments':$.trim(installments),StartDate:StartDate}, function(data) {
            $("#EndDate").val(data);
        });
        
        var da=(Amount/installments);
        var EMI=parseFloat(da).toFixed(2);
        
        $("#DeductionPerMonth").val(EMI);
    }
    
    
}
/*
function checkexist(StartDate){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var StartDate=$("#StartDate").val();
    var EmpCode=$("#EmpCode").val();
    
    if(checkDate(StartDate,EmpCode) !=""){
        $("#EmpCode").addClass('bordered');
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>You have already apply loan for this month.</span>");
        return false;
    }
    else{
        return true;
    }
}*/

function validateLoan(){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var Type=$("#Type").val();
    var EmpCode=$("#EmpCode").val();
    var EmpName=$("#EmpName").val();
    var Amount=$("#Amount").val();
    var StartDate=$("#StartDate").val();
    var EndDate=$("#EndDate").val();
    var Installments=$("#Installments").val();
    var DeductionPerMonth=$("#DeductionPerMonth").val();
    var GuarantorName=$("#GuarantorName").val();
    var GuarantorEmpCode=$("#GuarantorEmpCode").val();
    var Reason=$("#Reason").val();
    
    if(Type ===""){
        $("#Type").addClass('bordered');
        $("#Type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select Type.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#EmpCode").addClass('bordered');
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter employee code.</span>");
        return false;
    }
    else if(EmpName ===""){
        $("#EmpName").addClass('bordered');
        $("#EmpName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter employee name.</span>");
        return false;
    }
    else if(Amount ===""){
        $("#Amount").addClass('bordered');
        $("#Amount").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter amount.</span>");
        return false;
    }
    else if(StartDate ===""){
        $("#StartDate").addClass('bordered');
        $("#StartDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select start date.</span>");
        return false;
    }
    else if(Installments ===""){
        $("#Installments").addClass('bordered');
        $("#Installments").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter installments.</span>");
        return false;
    }
    else if(EndDate ===""){
        $("#EndDate").addClass('bordered');
        $("#EndDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select end date.</span>");
        return false;
    }
    else if(DeductionPerMonth ===""){
        $("#DeductionPerMonth").addClass('bordered');
        $("#DeductionPerMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter deduction</span>");
        return false;
    }
    else if(GuarantorName ===""){
        $("#GuarantorName").addClass('bordered');
        $("#GuarantorName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select guarantor name</span>");
        return false;
    }
    else if(GuarantorEmpCode ===""){
        $("#GuarantorEmpCode").addClass('bordered');
        $("#GuarantorEmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select guarantor emp code.</span>");
        return false;
    }
    else if(Reason ===""){
        $("#Reason").addClass('bordered');
        $("#Reason").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter reason.</span>");
        return false;
    }
    else if(Type =="Advance" && Installments > 1){
        $("#Installments").addClass('bordered');
        $("#Installments").after("<span id='msgerr' style='color:red;font-size:11px;'>Allow only one installments.</span>");
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

function checkDate(FromDate,EmpCode){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>LoanMasters/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,EmpCode:EmpCode},done: function(response) {return response;}}).responseText;	
    return posts;
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
                    <span> LOAN AND ADVANCE ENTRY </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('LoanMasters',array('action'=>'index','class'=>'form-horizontal','onsubmit'=>'return validateLoan();')); ?>
                <div class="form-group">
                    <div class="col-sm-2">Type</div>
                    <div class="col-sm-2">
                        <select id="Type" name="Type" class="form-control" >
                            <option value="" >Select</option>
                            <option value="Loan">Loan</option>
                            <option value="Advance">Advance</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-2">Employee Code</div>
                    <div class="col-sm-2">
                        <input type="text" name="EmpCode" id="EmpCode" onkeyup="search_empcode(this.value)" class="form-control" autocomplete="off"  >
                    </div>
                    
                    <div class="col-sm-2">Employee Name</div>
                    <div class="col-sm-2">
                        <input type="text" name="EmpName" id="EmpName" onkeyup="javascript:capitalize(this.id, this.value);" class="form-control" readonly="" autocomplete="off"  >
                    </div>
                </div>
                <div class="form-group">
                    
                    <div class="col-sm-2">Amount</div>
                    <div class="col-sm-2">
                        <input type="text" name="Amount" id="Amount" onkeypress="return isNumberDecimalKey(event,this)" class="form-control" autocomplete="off"  >
                    </div>
                    
                    <div class="col-sm-2">Start Date</div>
                    <div class="col-sm-2">
                        <?php 
                                $curYear = date('Y');
                        ?>
                        <select name="StartDate" id="StartDate" class="form-control" required="">
                            <option value="">Month</option>
                            <?php
                                    $TcurMonth = date('M');
                                    if($TcurMonth=='Jan')
                                    {?>
                                        <option value="Dec-<?php echo $curYear-1; ?>">Dec-<?php echo $curYear-1; ?></option>
                                    <?php }
                            ?>
                            <option value="Jan-<?php echo $curYear; ?>">Jan</option>
                            <option value="Feb-<?php echo $curYear; ?>">Feb</option>
                            <option value="Mar-<?php echo $curYear; ?>">Mar</option>
                            <option value="Apr-<?php echo $curYear; ?>">Apr</option>
                            <option value="May-<?php echo $curYear; ?>">May</option>
                            <option value="Jun-<?php echo $curYear; ?>">Jun</option>
                            <option value="Jul-<?php echo $curYear; ?>">Jul</option>
                            <option value="Aug-<?php echo $curYear; ?>">Aug</option>
                            <option value="Sep-<?php echo $curYear; ?>">Sep</option>
                            <option value="Oct-<?php echo $curYear; ?>">Oct</option>
                            <option value="Nov-<?php echo $curYear; ?>">Nov</option>
                            <option value="Dec-<?php echo $curYear; ?>">Dec</option>
                        </select>
<!--                        <input type="text" name="StartDate" id="StartDate"   autocomplete="off" readonly="" class="form-control datepik"  >-->
                    </div>
                    
                    <div class="col-sm-2">Installments</div>
                    <div class="col-sm-2">
                        <input type="text" name="Installments" id="Installments" onkeyup="search_installments(this.value)" onkeypress="return isNumberKey(event,this)" maxlength="2" class="form-control" autocomplete="off"  >
                    </div>
                    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2">End Date</div>
                    <div class="col-sm-2">
                        <input type="text" name="EndDate" id="EndDate"  autocomplete="off" readonly="" class="form-control"  >
                    </div>
                    
                    <div class="col-sm-2">Deduction Per Month</div>
                    <div class="col-sm-2">
                        <input type="text" name="DeductionPerMonth" id="DeductionPerMonth" onkeypress="return isNumberDecimalKey(event,this)" class="form-control" autocomplete="off"  >
                    </div>
                    
                    <div class="col-sm-2">Guarantor Name</div>
                    <div class="col-sm-2">
                        <input type="text" name="GuarantorName" id="GuarantorName" onkeyup="javascript:capitalize(this.id, this.value),search_employees(this.value)"  class="form-control" autocomplete="off"  >
                    </div>
    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2">Guarantor EmpCode</div>
                    <div class="col-sm-2">
                        <select name="GuarantorEmpCode" id="GuarantorEmpCode" class="form-control"  >
                            <option value="" >Select</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-2">Reason</div>
                    <div class="col-sm-4">
                        <textarea name="Reason" id="Reason" class="form-control" autocomplete="off" ></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
                
                <div class="form-group" id="LoanHistory" ></div>
                <?php echo $this->Form->end(); ?>
                
                
                
                
       

            </div>
        </div>
    </div>	
</div>



