<?php ?>
<script>
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

function ExportProcess(){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchNameExp").val();
    var EmpMonth=$("#EmpMonthExp").val();
    var EmpYear=$("#EmpYearExp").val();
    
    if(BranchName ===""){
        $("#BranchNameExp").focus();
        $("#BranchNameExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonthExp").focus();
        $("#EmpMonthExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYearExp").focus();
        $("#EmpYearExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp year.</span>");
        return false;
    }
    else{
        window.location="<?php echo $this->webroot;?>ChequePrints/export_report?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&EmpYear="+EmpYear; 
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
                    <span>CANCEL CHEQUE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('ChequePrints',array('action'=>'chequecancel','class'=>'form-horizontal')); ?>
                
                <div class="form-group" style="border:2px solid #436e90;margin:0px;background-color: #c0d6e4;line-height:25px;">
                    <div class="col-sm-12" style="text-align: right;">
                        <select name="PrintSalaryMonth" id="PrintSalaryMonth"  readonly style="width:100px;" >
                            <option value="<?php echo date('Y-m', strtotime(date('Y-m')." -1 month"));?>"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></option>
                        </select> 
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3"><input type="radio" checked name="PrintBankName" value="SBI"  > SBI </div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName"  value="SBIIDC" > SBI IDC</div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName" value="ICICI" > ICICI </div>
                            <div class="col-sm-3"><input type="radio" name="PrintBankName" value="HDFC" > HDFC</div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">             
                            <div class="col-sm-3">ChequeFrom</div>
                            <div class="col-sm-3"> <input type="text" name="PrintCheckFrom" id="PrintCheckFrom" onkeypress="return isNumberKey(event,this)" maxlength="6"   required="" style="height:16px;width:100px;" ></div>
                            <div class="col-sm-3">ChequeTo</div>
                            <div class="col-sm-3"> <input type="text" name="PrintCheckTo" id="PrintCheckTo" onkeypress="return isNumberKey(event,this)" maxlength="6"  required="" style="height:16px;width:100px;margin-left:-7px;" ></div>
                        </div>  
                    </div>
                    
                    <div class="col-sm-12">
                         <textarea name="Reason" id="Reason" style="width:400px;" placeholder="Reason" required=""></textarea>
                    </div>
                    
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <button type="submit" class="btn btn-primary btn-new pull-right" >Submit</button>
                        
                    </div>
                    
                    <div class="col-sm-12">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    
                    
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            
            
            <div class="box-content box-con">
                
                <div class="box-header"  >
                <div class="box-name">
                    <span>CANCEL CHEQUE EXPORT</span>
		</div>
		<div class="no-move"></div>
            </div>
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('ProcessSalarys',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchNameAll,'empty'=>'Select','class'=>'form-control','id'=>'BranchNameExp','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonthExp" name="EmpMonthExp" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="01">Jan</option>
                            <option value="02">Feb</option>
                            <option value="03">Mar</option>
                            <option value="04">Apr</option>
                            <option value="05">May</option>
                            <option value="06">Jun</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                            <option value="09">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmpYearExp" name="EmpYearExp" autocomplete="off"   class="form-control" >
                            <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
                        </select>
                    </div>
              
                    <div class="col-sm-1">
                        <input type="button" onclick="ExportProcess();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
            </div>
            
        </div>
    </div>	
</div>



