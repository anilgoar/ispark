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
   
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else{
        window.location="<?php echo $this->webroot;?>DiscardAttendanceIssues/report?branch_name="+BranchName;    
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
                    <span>DISCARD ATTENDANCE ISSUE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('DiscardAttendanceIssues',array('action'=>'index','class'=>'form-horizontal','id'=>'showDetails')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranchIssue(this.value)','required'=>true)); ?>                     
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width: 30px;" ><input type="checkbox" id="select_all"/></th>
                            <th style="text-align: center;width: 80px;">Emp Code</th>
                            <th style="text-align: center;width: 80px;">Bio Code</th>
                            <th>Emp Name</th>
                            <th style="text-align: center;width: 100px;">Branch</th>
                            <th style="text-align: center;width: 80px;">Attend Date</th>
                            <th>Reason</th>
                            <th style="text-align: center;width: 30px;">Current</th>
                            <th style="text-align: center;width: 30px;">Expected</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($OdArr as $val){?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['BranchAttandIssueMaster']['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['BioCode'];?></td>
                        <td ><?php echo $val['BranchAttandIssueMaster']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo date('d M y',strtotime($val['BranchAttandIssueMaster']['AttandDate'])) ;?></td>
                        <td ><?php echo $val['BranchAttandIssueMaster']['Reason'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['CurrentStatus'];?></td>
                        <td style="text-align: center;"><?php echo $val['BranchAttandIssueMaster']['ExpectedStatus'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="button" onclick="downloadHoAttendApprovalReport();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                        <?php 
                        echo $this->Form->submit('Discard', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
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



