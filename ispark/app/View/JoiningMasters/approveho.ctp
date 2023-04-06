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
    
function getBranchIssue(BranchName){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    if(BranchName ===""){
        $(".bordered").removeClass('bordered');
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else{
        $("#showDetails").submit();
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
                    <span>DOJ APPROVE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('JoiningMasters',array('action'=>'approveho','class'=>'form-horizontal','id'=>'showDetails')); ?>
                <input type="hidden" id="ApproveStatus" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranchIssue(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                
                <?php $i=1; if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" ><input type="checkbox" id="select_all"/></th>
                            <th style="width:30px;text-align: center;">SNo</th>
                            <th style="width:80px;text-align: center;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="width:150px;text-align: center;">Branch</th>
                            <th style="width:100px;text-align: center;">OldDOJ</th>
                            <th style="width:100px;text-align: center;">NewDOJ</th>
                            <th >Remarks</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php foreach ($OdArr as $val){?>
                    <tr>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['ChangeDojMaster']['Id'];?>" name="check[]"></center></td>
                        <td style="text-align: center;" ><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $val['ChangeDojMaster']['EmpCode'];?></td>
                        <td><?php echo $val['ChangeDojMaster']['EmpName'];?></td>
                        <td style="text-align: center;" ><?php echo $val['ChangeDojMaster']['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['ChangeDojMaster']['OldDOJ']));?></td>
                        <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['ChangeDojMaster']['NewDOJ']));?></td>
                        <td><?php echo $val['ChangeDojMaster']['Remarks'];?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <div class="col-sm-6">
                       <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus/changdoj"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <!--
                        <input type="button" onclick="downloadHoAttendApprovalReport();" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                        -->
                        <?php 
                        echo $this->Form->submit('Not Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
                    </div>
                </div>
                
                <?php }else{?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <span><?php echo $this->Session->flash(); ?></span><br/>
                        <span>No Record for Approval.</span>
                    </div>
                </div>
                <?php }?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



