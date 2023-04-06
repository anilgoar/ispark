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
    
function getBranch(branch){
    $("#showDetails").submit();
}

function goBack(){
    window.location="<?php echo $this->webroot;?>OdApprovalDisapprovals";  
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
                    <span>OD APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
               
                <?php echo $this->Form->create('OdApprovalDisapprovals',array('class'=>'form-horizontal','action'=>'odapproval','id'=>'showDetails')); ?>
                <input type="hidden" id="ApproveStatus" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>array_merge(array('ALL'=>'ALL'),$branchName),'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                
          
                
                
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                            <th  style="width:30px;">SNo</th>
                            <th style="width:30px;"><input type="checkbox" id="select_all"/></th>
                            <th style="width:70px;">Emp Code</th>
                            <th >Emp Name</th>
                            <th >Designation</th>
                            <th style="width: 150px;">Branch</th>
                            <th style="width:80px;">From Date</th>
                            <th style="width:80px;">To Date</th>
                            <th >Reason</th>
                            
                            <th style="width:40px;">Status</th>
                           
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><input class="checkbox" type="checkbox" value="<?php echo $val['OdApplyMaster']['Id'];?>" name="check[]"></span></td>
                        <td><?php echo $val['OdApplyMaster']['EmpCode'];?></td>
                        <td><?php echo $val['OdApplyMaster']['EmpName'];?></td>
                        <td><?php echo $val['OdApplyMaster']['Designation'];?></td>
                        <td style="text-align:center;"><?php echo $val['OdApplyMaster']['BranchName'];?></td>
                        <td><?php echo date('d-M-y',strtotime($val['OdApplyMaster']['StartDate'])) ;?></td>
                        <td><?php echo date('d-M-y',strtotime($val['OdApplyMaster']['EndDate'])) ;?></td>
                        <td><?php echo $val['OdApplyMaster']['Reason'];?></td>
                        <td style="text-align:center;"><?php echo end(explode("_", $val['OdApplyMaster']['CurrentStatus']));?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus/od"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php 
                        echo $this->Form->submit('Not Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new','style'=>'margin-left:10px;')); 
                        echo $this->Form->submit('Approve', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));                   
                        ?>
                    </div>
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
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



