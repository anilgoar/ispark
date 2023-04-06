<?php ?>
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
    
function getBranchIssue(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    if(BranchName ===""){
         $("#BranchIssue").val('');
         $("#BranchName").focus();
         $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else{
        $("#showDetails").submit();
    }
}

function downloadHoAttendApprovalReport(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var BranchIssue=$("#BranchIssue").val();
    
    
   
   
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else if(BranchIssue ===""){
        $("#BranchIssue").focus();
        $("#BranchIssue").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else{
        window.location="<?php echo $this->webroot;?>HooldAttendanceApprovals/report?branch_name="+BranchName+"&branch_issue="+BranchIssue;    
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
                    <span>OLD ATTENDANCE APPROVAL HO</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('HooldAttendanceApprovals',array('action'=>'index','class'=>'form-horizontal','id'=>'showDetails')); ?>
                <input type="hidden" id="ApproveStatus" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    <label class="col-sm-2 control-label">Issue</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_issue',array('label' => false,'options'=>array('ALL'=>'ALL','Forgot To Punch'=>'Forgot To Punch','New Joining'=>'New Joining','Others'=>'Others','Power Failure'=>'Power Failure','Skin Problem'=>'Skin Problem'),'class'=>'form-control','empty'=>'Select Issue','id'=>'BranchIssue','onchange'=>'getBranchIssue(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                    
                    
                </div>
                <div class="form-group">
                     <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;" ><input type="checkbox" id="select_all"/></th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Bio Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Attend Date</th>
                            <th style="text-align: center;">Reason</th>
                            <th style="text-align: center;">Current</th>
                            <th style="text-align: center;" >Expected</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($OdArr as $val){?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['OldAttendanceIssue']['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;width:80px;"><?php echo $val['OldAttendanceIssue']['EmpCode'];?></td>
                        <td style="text-align: center;width:80px;"><?php echo $val['OldAttendanceIssue']['BioCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['BranchName'];?></td>
                        <td style="text-align: center;width:100px;"><?php echo date('d M y',strtotime($val['OldAttendanceIssue']['AttandDate'])) ;?></td>
                        <td style="text-align: center;"><?php echo $val['OldAttendanceIssue']['Reason'];?></td>
                        <td style="text-align: center;width:100px;"><?php echo $val['OldAttendanceIssue']['CurrentStatus'];?></td>
                        <td style="text-align: center;width:100px;"><?php echo $val['OldAttendanceIssue']['ExpectedStatus'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus/attendance"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="downloadHoAttendApprovalReport();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                        <?php 
                        echo $this->Form->submit('Discard', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
                    </div>
                </div>
                
                <?php }else{?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                       <span>No Record for Approval.</span>
                    </div>
                </div>
                <?php }?>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



