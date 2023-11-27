<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#AttenDate").datepicker1({
        changeMonth: true,
        changeYear: true
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

function getAttendStatus(AttendDate){
    var EmpCode=$("#EmpCode").val();
    $.post("<?php echo $this->webroot;?>AttendanceIssueSubmitions/get_attend_status",{'EmpCode':$.trim(EmpCode),'AttendDate':$.trim(AttendDate)}, function(data) {
        if(data !=""){
            $("#CurStatus").val(data);
        }
        else{
            $("#CurStatus").val('');  
        }
    });
}

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
    else if(CurStatus == ExpStatus){
        $("#ExpStatus").after("<span id='msgerr' style='color:red;font-size:11px;'>Select correct exp status.</span>");
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
                    <span>ATTENDANCE ISSUE SUBMISSION</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('attendance-issue-submition',array('class'=>'form-horizontal','onsubmit'=>'return validateAttendIssue();')); ?>
                <div class="form-group">
                    <div class="col-sm-2">Emp Code</div>
                    <div class="col-sm-2">
                        <input type="text" name="EmpCode" id="EmpCode" autocomplete="off" required="" class="form-control" >
                    </div>
                    <div class="col-sm-2">AttenDate</div>
                    <div class="col-sm-2">
                        <input type="text" name="AttenDate" id="AttenDate" onChange="getAttendStatus(this.value)" autocomplete="off" required="" class="form-control"  >
                    </div>
                    <div class="col-sm-2">Current Status</div>
                    <div class="col-sm-2">
                        <input type="text" name="CurStatus" id="CurStatus" autocomplete="off" readonly required class="form-control" >
                    </div>
                </div>
                
                
                <div class="form-group">
                    <div class="col-sm-2">Expected Status</div>
                    <div class="col-sm-2">
                        <select name="ExpStatus" id="ExpStatus" autocomplete="off" required="" class="form-control" >
                            <option value="">Select</option>
                            <option value="P">P</option>
                            <option value="A">A</option>
                            <option value="HD">HD</option>
                            <option value="DH">DH</option>
                            <option value="F">F</option>
                            <option value="OD">OD</option>
                            <!-- <option value="WFH">WFH</option> -->
                        </select>
                    </div>
                    <div class="col-sm-2">Reason</div>
                    <div class="col-sm-2">
                        <select name="Reason" id="Reason" autocomplete="off" required="" class="form-control" onchange="getReason(this.value);" >
                            <option value="">Select</option>
                            <option value="Forgot To Punch">Forgot To Punch</option>
                            <option value="New Joining">New Joining</option>
                            <option value="Others">Others</option>
                            <option value="Power Failure">Power Failure</option>
                            <option value="Skin Problem">Skin Problem</option>
                            <option value="Work From Home">Work From Home</option>
                            <!-- new add reasons -->
                            <option value="PQ Training">PQ Training</option>
                            <option value="Training/Feedback">Training/Feedback</option>
                            <option value="Night Shift Issue">Night Shift Issue</option>
                            <option value="Present as per APR">Present as per APR</option>
                            <option value="Short Login Hour">Short Login Hour</option>
                            <option value="Present on 7:50 Hrs">Present on 7:50 Hrs</option>
                        </select>
                    </div>
                    <div id="othres"></div>
                    
                </div>
              
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
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
                
                <?php if(!empty($PendingArr)){ ?>
                <table class = "table table-striped table-hover  responstable" style="margin-top:-30px;" >     
                    <thead>
                        <tr><th colspan="7" style="text-align: left;" >PENDING ISSUES</th></tr>
                        <tr>
                            <th style="width:80px;" >Emp Code</th>
                            <th>Emp Name</th>
                            <th style="width:70px;text-align: center;">Attend Date</th>
                            <th style="width:100px;text-align: center;">Current Status</th>
                            <th style="width:100px;text-align: center;">Expected Status</th>
                            <th>Reason</th>
                            <th style="width:80px;text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($PendingArr as $val){?>
                    <tr>
                        <td><?php echo $val['BranchAttandIssueMaster']['EmpCode'];?></td>
                        <td><?php echo $val['BranchAttandIssueMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo date('d M y',strtotime($val['BranchAttandIssueMaster']['AttandDate'])) ;?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['CurrentStatus'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['ExpectedStatus'];?></td>
                        <td><?php echo $val['BranchAttandIssueMaster']['Reason'];?></td>
                        <td style="text-align: center;">Pending</td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>                
                <?php }?>
                
                <?php if(!empty($ClosedArr)){ ?>
                <table class = "table table-striped table-hover  responstable">     
                    <thead>
                        <tr><th colspan="7" style="text-align: left;" >CLOSED ISSUES</th></tr>
                        <tr>
                            <th style="width:80px;text-align: center;">Emp Code</th>
                            <th>Emp Name</th>
                            <th style="width:70px;text-align: center;">Attend Date</th>
                            <th style="width:100px;text-align: center;">Current Status</th>
                            <th style="width:100px;text-align: center;">Expected Status</th>
                            <th>Reason</th>
                            <th style="width:80px;text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($ClosedArr as $val){?>
                    <tr>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['EmpCode'];?></td>
                        <td><?php echo $val['BranchAttandIssueMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo date('d M y',strtotime($val['BranchAttandIssueMaster']['AttandDate'])) ;?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['CurrentStatus'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['ExpectedStatus'];?></td>
                        <td><?php echo $val['BranchAttandIssueMaster']['Reason'];?></td>
                        <td style="text-align: center;">
                        <?php 
                        if($val['BranchAttandIssueMaster']['ApproveSecond'] ==""){
                            if($val['BranchAttandIssueMaster']['ApproveFirst'] =="Yes"){
                                echo "BM Approved";
                            }
                            else if($val['BranchAttandIssueMaster']['ApproveFirst'] =="No"){
                                echo "BM Disapproved";
                            }
                        }
                        else{
                            if($val['BranchAttandIssueMaster']['ApproveSecond'] =="Yes"){
                                echo "HO Approved";
                            }
                            else if($val['BranchAttandIssueMaster']['ApproveSecond'] =="No"){
                                echo "HO Disapproved";
                            }  
                        }
                        ?>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                <?php }?>
            </div>
        </div>
    </div>	
</div>



