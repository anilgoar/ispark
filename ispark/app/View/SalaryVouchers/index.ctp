<?php
$exp    =   explode("Menus?AX=", $_SERVER['HTTP_REFERER']);
$expid  =   end($exp);
if(isset($_REQUEST['backid'])){
    $backid=$_REQUEST['backid'];
    $backurl=$this->webroot."Menus?AX=".$_REQUEST['backid'];
}
else{
    $backid=$expid;
    $backurl=$this->webroot."Menus?AX=".$expid;
}
?>
<script>
function ExportProcess(){ 
    $("#msgerr").remove();
    var CompanyName=$("#CompanyExp").val();
    var BranchName=$("#BranchNameExp").val();
    var EmpMonth=$("#EmpMonthExp").val();
    var EmpYear=$("#EmpYearExp").val();
    
    if(CompanyName ===""){
        $("#CompanyExp").focus();
        $("#CompanyExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select company.</span>");
        return false;
    }
    else if(BranchName ===""){
        $("#BranchNameExp").focus();
        $("#BranchNameExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(EmpMonth ===""){
        $("#EmpMonthExp").focus();
        $("#EmpMonthExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select month.</span>");
        return false;
    }
    else if(EmpYear ===""){
        $("#EmpYearExp").focus();
        $("#EmpYearExp").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select year.</span>");
        return false;
    }
    else{
        
        $.post("<?php echo $this->webroot;?>SalaryVouchers/getdownloadstatus",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear,CompanyName:CompanyName}, function(result){
            alert(result);
            window.location="<?php echo $this->webroot;?>SalaryVouchers/export_report?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&EmpYear="+EmpYear+"&CompanyName="+CompanyName; 
        });
        
        
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
                    <span>SALARY VOUCHER EXPORT</span>
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
                <?php echo $this->Form->create('SalaryVouchers',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Company</label>
                    <div class="col-sm-2">
                        <select id="CompanyExp" name="CompanyExp" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="MAS">Mas Callnet India Pvt Ltd</option>
                            <option value="IDC">ISPARK Dataconnect Pvt Ltd</option>
                        </select>
                    </div>
                    
                    
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
              
                    <div class="col-sm-12">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="ExportProcess();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>                  
                <?php echo $this->Form->end(); ?>
            </div>

            
        </div>
    </div>	
</div>
