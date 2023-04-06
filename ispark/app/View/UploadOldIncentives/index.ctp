<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function (){
    $("#IncentiveMonth").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function getincentive(BranchName){
    $.post("<?php echo $this->webroot;?>UploadIncentiveBreakups/get_incentive_type",{'BranchName':BranchName}, function(data) {
        if(data !=""){
            $("#IncentiveType").html(data);
        }
        else{
            $("#IncentiveType").html('');  
        }
    });
}

function getEmpDetails(){ 
    $("#msgerr").remove();
    $("#LeaveTable").hide();
    $("#LeaveTable1").hide();
    
    var BranchName=$("#BranchName").val();
    var EmpCode=$("#EmpCode").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch.</span>");
        return false;
    }
    else if(EmpCode ===""){
        $("#EmpCode").focus();
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select emp code.</span>");
        return false;
    }
    else{
    
        $.post("<?php echo $this->webroot;?>UploadOldIncentives/get_leave_details",{'EmpCode':EmpCode,'BranchName':BranchName}, function(data) {
            if(data !=""){
                $("#LeaveTable").show();
                $("#LeaveDetails").html(data);
            }
            else{
                $("#LeaveTable").hide();
            }
        });

        $.post("<?php echo $this->webroot;?>UploadOldIncentives/old_incentive_details",{'EmpCode':EmpCode,'BranchName':BranchName}, function(data) {
            if(data !=""){
                $("#LeaveTable1").show();
                $("#LeaveDetails1").html(data);
            }
            else{
                $("#LeaveTable1").hide();
            }
        });
    
    }  
}

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
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
                    <span>UPLOAD OLD INCENTIVE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                <?php echo $this->Form->create('UploadOldIncentives',array('class'=>'form-horizontal','action'=>'index','onsubmit'=>'return validatdFieldMark()','enctype'=>'multipart/form-data')); ?>
                      
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getincentive(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-2 control-label">EmpCode</label>
                    <div class="col-sm-2">
                        <input type="text" name="EmpCode" id="EmpCode" autocomplete="off" required="" class="form-control"  >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" value="Search" class="btn btn-primary pull-right btn-new" onclick="getEmpDetails();" >
                    </div>
                </div>
                
                <div class="form-group" style="display: none;" id="LeaveTable" >    
                    <div class="col-sm-10" id="LeaveDetails" ></div>
                </div>
                
                <div class="form-group"><hr/></div>
                  
                <div class="form-group">
                    <label class="col-sm-2 control-label">Incentive Type</label>
                    <div class="col-sm-2">
                        <select name="IncentiveType" id="IncentiveType" autocomplete="off" required="" class="form-control" ></select>
                    </div>
    
                    <label class="col-sm-2 control-label">Incentive Amount</label>
                    <div class="col-sm-2">
                        <input type="text" name="IncentiveAmount" id="IncentiveAmount" onkeypress="return isNumberDecimalKey(event)" autocomplete="off" required="" class="form-control"  >
                    </div>
                    
                   
                    
                </div>
                
                <div class="form-group">
<!--                    <label class="col-sm-2 control-label">Incentive Month</label>
                    <div class="col-sm-2">
                        <input type="text" name="IncentiveMonth" id="IncentiveMonth" autocomplete="off" required="" class="form-control"  >
                    </div>-->
                    
                    
                    <label class="col-sm-2 control-label"> Month</label>
                    <div class="col-sm-2">
                        
                        <select name="IncentiveMonth" id="IncentiveMonth" class="form-control" required="">
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
                    </div>
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-2">
                        <textarea name="Remarks" id="Remarks" autocomplete="off" required="" class="form-control"  ></textarea>
                       
                    </div>
                    <div class="col-sm-1">
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
                
                <div class="form-group" style="display: none;" id="LeaveTable1" >    
                    <div class="col-sm-10" id="LeaveDetails1" ></div>
                </div>

                <?php echo $this->Form->end(); ?> 
            </div>
        </div>
    </div>	
</div>




























