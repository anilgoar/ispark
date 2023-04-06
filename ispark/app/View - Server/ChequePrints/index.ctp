<?php ?>
<script>
function costcenterList(BranchName){ 
    $.post("<?php echo $this->webroot;?>ChequePrints/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function printed(BranchName,CostCenter,EmpLocation,EmpCode,EmpMonth,EmpYear){            
    $.post("<?php echo $this->webroot;?>ChequePrints/printdetails",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,CostCenter:CostCenter,EmpLocation:EmpLocation,EmpCode:$.trim(EmpCode)}, function(data) {
        $("#divPrint").show();
        $("#divPrint").html(data);
        $("#divEmp").hide();
        $("#divEmp").html('');
    });
}

function salaryDetails(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var EmpMonth=$("#EmpMonth").val();
    var EmpYear=$("#EmpYear").val();
    var CostCenter=$("#CostCenter").val();
    var EmpLocation=$("#EmpLocation").val();
    var EmpCode=$("#EmpCode").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonth").focus();
        $("#EmpMonth").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYear").focus();
        $("#EmpYear").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp year.</span>");
        return false;
    }
    else{
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>ChequePrints/show_report",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,CostCenter:CostCenter,EmpLocation:EmpLocation,EmpCode:$.trim(EmpCode)}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#divEmp").show();
                    $("#divEmp").html(data);
                    $("#divPrint").hide(); 
                    $("#divPrint").html('');
                }
                else{
                    $("#divEmp").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
    }
}

function printCheque(type){
    $("#msgerr").remove();
    var PrintBranchName     =   $("#PrintBranchName").val();
    var PrintCostCenter     =   $("#PrintCostCenter").val();
    var PrintSalaryMonth    =   $("#PrintSalaryMonth").val();
    var PrintChequeDate     =   $("#PrintChequeDate").val();
    var PrintBankName       =   $("input:radio[name=PrintBankName]:checked").val()
    var PrintAccountPayee   =   $('#PrintAccountPayee:checked').val();
    var PrintAccountNumber  =   $('#PrintAccountNumber:checked').val();
    var PrintCount          =   $("#HiddenPrintCount").val();
    var PrintCheckFrom      =   $("#PrintCheckFrom").val();
    var PrintCheckTo        =   $("#PrintCheckTo").val();
    var CheckNoCount        =   (PrintCheckTo-PrintCheckFrom)+1;
    
    var all_location_id = document.querySelectorAll('input[name="PrintCheckAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
        aIds.push(all_location_id[x].value);
    }
    
    if(PrintCount <=0){
        alert('Employee count should be greater than 0 !');
        return false;
    }
    else if(PrintCheckFrom ===""){
        alert('Please enter first cheque no !');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else if(PrintCheckFrom.length !=6){
        alert('Please enter 6 digit cheque no !');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else if(PrintCheckTo ===""){
        alert('Please enter second cheque no !');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else if(PrintCheckTo.length !=6){
        alert('Please enter 6 digit cheque no !');
        $("#PrintCheckTo").focus();
        return false;
    }
    else if(PrintCheckTo < PrintCheckFrom){
        alert('Second cheque no should be greater then first cheque no !');
        $("#PrintCheckTo").focus();
        return false;
    }
    else if(PrintCount != CheckNoCount){
        alert('Employee count and cheque count mismatch!');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else if(chequeVerified() > 0){
        alert('Sorry verify or delete your privious cheque !');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else if(chequeNoExist(PrintCheckFrom,PrintCheckTo,PrintBankName) > 0){
        alert('Sorry this cheque no is already used !');
        $("#PrintCheckFrom").focus();
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>ChequePrints/insertchequedetails",{EmpCode:aIds,BranchName:PrintBranchName,CostCenter:PrintCostCenter,SalaryMonth:PrintSalaryMonth,CheckDate:PrintChequeDate,BankName:PrintBankName,PrintAccountPayee:PrintAccountPayee,PrintAccountNumber:PrintAccountNumber,PrintCheckFrom:PrintCheckFrom,PrintCheckTo:PrintCheckTo}, function(data) {
            window.open('<?php echo $this->webroot;?>app/webroot/checkprint/examples/exemple00.php', 'Print Cheque', 'width=750, height=300, top=0, left=0');
        });   
    } 
}

function chequeVerification(type){
    var result = confirm("Are you sure you want to "+type+"?");
    if (result) {
        $.post("<?php echo $this->webroot;?>ChequePrints/chequeverification",{type:type},function(data) {
            alert(data);
            if(type =="verify"){
                salaryDetails('show');
            }
        });
    }  
}

function chequeVerified(){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>ChequePrints/chequeverified",async: false,dataType: 'json',data: {Type:'Cheque'},done: function(response) {return response;}}).responseText;	
    return posts;
}

function chequeNoExist(PrintCheckFrom,PrintCheckTo,PrintBankName){
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>ChequePrints/chequenoexist",async: false,dataType: 'json',data: {PrintCheckFrom:PrintCheckFrom,PrintCheckTo:PrintCheckTo,PrintBankName:PrintBankName},done: function(response) {return response;}}).responseText;	
    return posts;
}

function totalCount(){
    var TotalCount =    $("#HiddenTotalCount").val();
    var all_location_id = document.querySelectorAll('input[name="PrintCheckAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
        aIds.push(all_location_id[x].value);
    }
    
    var Remaining   = (TotalCount - aIds.length);
    
    $("#HiddenPrintCount").val(aIds.length);
    $("#PrintTotalCount").text(aIds.length);
    $("#PrintTotalRemaining").text(Remaining);
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
                    <span>SALARY PRINT</span>
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
                <?php echo $this->Form->create('ChequePrints',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'costcenterList(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                     <label class="col-sm-1 control-label">EmpLocation</label>
                    <div class="col-sm-2">
                        <select id="EmpLocation" name="EmpLocation" autocomplete="off" class="form-control" >
                            <option value="ALL">ALL</option>
                            <option value="1">Active</option>
                            <option value="0">Left</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" id="EmpCode" name="EmpCode" autocomplete="off" placeholder="EmpCode" class="form-control" >
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonth" name="EmpMonth" autocomplete="off" class="form-control" readonly >
                            <option value="<?php echo date('m', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('M', strtotime(date('Y-m')." -1 month"));?></option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmpYear" name="EmpYear" autocomplete="off" class="form-control" readonly >
                            <option value="<?php echo date('Y', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('Y', strtotime(date('Y-m')." -1 month"));?></option>
                        </select>
                    </div>
                    
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="salaryDetails('show');" value="Show" class="btn pull-right btn-primary btn-new">
                    </div>
                    
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder"  >
                    </div>
                </div>
                
                <div class="form-group" id="divEmp" style="margin-top:-15px;" ></div>
                
                <div class="form-group" id="divPrint" ></div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



