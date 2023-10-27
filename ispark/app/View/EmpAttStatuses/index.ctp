<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#from_date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#to_date").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>Tickets/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function showdata(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var CostCenter=$("#CostCenter").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }

    else{
        $("#loder").show();
      
        $("#form1").submit();
        
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
            <div class="box-header">
                <div class="box-name">
                    <span>Attendance Navigator</span>
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
                <?php echo $this->Form->create('EmpAttStatuses',array('action'=>'index','class'=>'form-horizontal','id'=>'form1')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                        <?php if(isset($costcenter))
                            {
                                echo "<option value=".$costcenter.">".$costcenter."</option>";
                            }else{
                                echo "<option value=''>Select</option>";
                            }?>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">From Date</label>
                    <div class="col-sm-2">
                      <input type="text" name="from_date" id="from_date" value="<?php echo isset($fromdate)?date('d-M-Y',strtotime($fromdate)):'';?>" class="form-control" required=""  >
                    </div>

                    <label class="col-sm-1 control-label">To Date</label>
                    <div class="col-sm-2">
                        <input type="text" name="to_date" id="to_date" value="<?php echo isset($todate)?date('d-M-Y',strtotime($todate)):'';?>" autocomplete="off" class="form-control" required=""  >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                         
                        <input type="button" onclick="showdata();" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder">
                    </div>
                    
                </div>
              
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" style="margin-top:-100px;" >     
                    <thead>
                        <tr><th colspan="8" style="text-align: center;" >Details</th></tr>
                        <tr>
                            <th style="text-align: center;">SNo.</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Costcenter</th>
                            <th style="text-align: center;">Costcenter Name</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Leave Date</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($data as $val){
                        $EJEID = base64_encode($val['ContinuouslyLeave']['EmpCode']);
                        ?>

                        
                    <tr>
                        <td style="text-align: center;"><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $val['ContinuouslyLeave']['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo $val['ContinuouslyLeave']['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo $val['costcentername'];?></td>
                        <td style="text-align: center;"><?php echo $val['ContinuouslyLeave']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['ContinuouslyLeave']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo "From -" .date('d M y',strtotime($val['ContinuouslyLeave']['from_date']));echo "<br>"; echo "To- ".date('d M y',strtotime($val['ContinuouslyLeave']['to_date']));?></td>
                        <td style="text-align: center;">
                           <a href="<?php echo $this->webroot;?>LeaveManagements/leaveentry">Leave</a>
                           || 
                           <a href="<?php $this->webroot;?>EmployeeDetails/viewdetails?EJEID=<?php echo $EJEID;?>">Left</a>
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



