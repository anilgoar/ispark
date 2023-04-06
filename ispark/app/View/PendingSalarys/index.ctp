<?php  ?>
<script>
function viewPendingSalary(){ 
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
		$(".loader").show();
		$.post("<?php echo $this->webroot;?>app/webroot/corephp/view-pending-sapary.php",{branch_name:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data){
            $(".loader").hide();
			$("#view_pending_salary").html(data);
        });  
       
    }
}
</script>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
                    <span>Pending Salary</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
			
            <div class="box-content box-con">
			
				<form method="post" action="<?php echo $this->webroot;?>app/webroot/corephp/export-pending-sapary.php" class="form-horizontal">

                <?php //echo $this->Form->create('PendingSalarys',array('action'=>'index','class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchNameAll,'value'=>$branch_name,'empty'=>'Select','class'=>'form-control','id'=>'BranchNameExp','required'=>true)); ?>
                    </div>
                    
					
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <select id="EmpMonthExp" name="EmpMonth" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option <?php echo $EmpMonth=="01"?'selected="selected"':'';?> value="01">Jan</option>
                            <option <?php echo $EmpMonth=="02"?'selected="selected"':'';?> value="02">Feb</option>
                            <option <?php echo $EmpMonth=="03"?'selected="selected"':'';?> value="03">Mar</option>
                            <option <?php echo $EmpMonth=="04"?'selected="selected"':'';?> value="04">Apr</option>
                            <option <?php echo $EmpMonth=="05"?'selected="selected"':'';?> value="05">May</option>
                            <option <?php echo $EmpMonth=="06"?'selected="selected"':'';?> value="06">Jun</option>
                            <option <?php echo $EmpMonth=="07"?'selected="selected"':'';?> value="07">Jul</option>
                            <option <?php echo $EmpMonth=="08"?'selected="selected"':'';?> value="08">Aug</option> 
                            <option <?php echo $EmpMonth=="09"?'selected="selected"':'';?> value="09">Sep</option>
                            <option <?php echo $EmpMonth=="10"?'selected="selected"':'';?> value="10">Oct</option>
                            <option <?php echo $EmpMonth=="11"?'selected="selected"':'';?> value="11">Nov</option>
                            <option <?php echo $EmpMonth=="12"?'selected="selected"':'';?> value="12">Dec</option>
                        </select>
                    </div>
                    
					
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="EmpYearExp" name="EmpYear" autocomplete="off"   class="form-control" >
                            <option <?php echo $EmpYear==date("Y")?'selected="selected"':'';?> value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                            <option <?php echo $EmpYear==date("Y",strtotime("-1 year"))?'selected="selected"':'';?> value="<?php echo date("Y",strtotime("-1 year")); ?>"><?php echo date("Y",strtotime("-1 year")); ?></option>
                        </select>
                    </div>
					
					<div class="col-sm-1"><div class="loader" style="display:none;"></div></div>
			  
                    <div class="col-sm-2">
						 <input onclick='return window.location="Menus?AX=MTA3"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name='submit'  value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
						 <input type="button" onclick="viewPendingSalary()"  value="Show" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>
				
				<div class="form-group">
					<div class="col-sm-12" id="view_pending_salary"></div>
				</div>

				
				
				</form>
				
                <?php //echo $this->Form->end(); ?>
            </div>
            
        </div>
    </div>	
</div>
