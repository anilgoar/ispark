<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function (){
    $("#SalaryMonth").datepicker1({
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
</script>
<script>
function validateAttendIssue(){
    $("#msgerr").remove();
    var EmpCode=$("#EmpCode").val();
    var AttenDate=$("#AttenDate").val();
    var CurStatus=$("#CurStatus").val();
    var ExpStatus=$("#ExpStatus").val();
    var Reason=$("#Reason").val();
    var OtherReason=$("#OtherReason").val();
    
    if(EmpCode ===""){
        $("#EmpCode").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(AttenDate ===""){
        $("#AttenDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(CurStatus ===""){
        $("#CurStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else if(ExpStatus ===""){
        $("#ExpStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else if(Reason ===""){
        $("#Reason").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else if(Reason ==="Others" && OtherReason ===""){
        $("#OtherReason").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else{
        return true;
    }
}
</script>
<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function validatdFieldMark(){  
    var MarkDate = $('#MarkDate').val();
    if(MarkDate==''){
        alert("Please select date.");
        return false;
    }
    else{
      return true; 
    }   
}

function reload(url){
    window.location.href = url;
}

function goBack(){
    window.location="<?php echo $this->webroot;?>UploadDeductions";  
}

/*
function checkHd(chkall,chkhd){
    var ChkAl = $('#'+chkall).is(':checked');
    var ChkHd = $('#'+chkhd).is(':checked');
     
    if(ChkAl ==ChkHd){
        alert('Please select any one option.');
        $('input[type="checkbox"]').removeAttr('checked');
        return false;
    }
}
*/
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
                    <span>INCENTIVE UPLOAD</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                <?php echo $this->Form->create('UploadIncentiveBreakups',array('class'=>'form-horizontal','action'=>'index','onsubmit'=>'return validatdFieldMark()','enctype'=>'multipart/form-data')); ?>
                      
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getincentive(this.value)','required'=>true)); ?>
                    </div>
                   
                    <label class="col-sm-2 control-label">Salary Month</label>
                    <div class="col-sm-2">
                        
                        <select name="SalaryMonth" id="SalaryMonth" class="form-control" required="">
                            <option value="">Month</option>
                            <?php
                                    $TcurMonth = date('M');
                                    if($TcurMonth=='Jan')
                                    {?>
                                        <option value="Dec-<?php echo $curYear-1; ?>">Dec</option>
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
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Incentive Type</label>
                    <div class="col-sm-2">
                        <select name="IncentiveType" id="IncentiveType" autocomplete="off" required="" class="form-control" ></select>
                    </div>
                   
                    <label class="col-sm-2 control-label">Incentive File</label>
                    <div class="col-sm-2">
                        <div class="col-sm-2"><input type="file" name="UploadIncentive" accept=".csv" required="" ></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?> 
            </div>
        </div>
    </div>	
</div>



























