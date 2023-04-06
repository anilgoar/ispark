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
                    <span>TRAINING BATCH BM APPROVAL</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('TrainingBatchBmapprovals',array('class'=>'form-horizontal','action'=>'index','id'=>'showDetails')); ?>
                <input type="hidden" id="ApproveStatus" >
                <input type="hidden" name="backid" value="<?php echo $backid?>" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php 
                        //if($this->Session->read('role')=='admin'){$AllArr=array('ALL'=>'ALL');}else{$AllArr=array();}
                        echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); 
                        ?>
                    </div>
                     <div class="col-sm-1">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                <?php if(!empty($OdArr)){ ?>
                <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                    <thead>
                        <tr>
                            <th >SrNo</th>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th >BranchName</th>
                            <th >CostCenter</th>
                            <th style="width:40px;" >BatchCount</th>
                            <th style="width:40px;">DurationDays</th>
                            <th >BatchCode</th>
                            <th >TrainerName</th>
                            <th >TrainingRoom</th>
                            <th >Remarks</th>
                            <th >Start Date</th>
                            <th >End Date</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($OdArr as $val){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['TrainingBatchMaster']['Id'];?>" name="check[]"></center></td>
                        <td><?php echo $val['TrainingBatchMaster']['BranchName'];?></td>
                        <td><?php echo $val['TrainingBatchMaster']['CostCenter'];?></td>
                        <td style="text-align: center;" ><?php echo $val['TrainingBatchMaster']['BatchCount'];?></td>
                        <td style="text-align: center;" ><?php echo $val['TrainingBatchMaster']['DurationDays'];?></td>
                        <td><?php echo $val['TrainingBatchMaster']['BatchCode'];?></td>
                        <td><?php echo $val['TrainingBatchMaster']['TrainerName'];?></td>
                        <td><?php echo $val['TrainingBatchMaster']['TrainingRoom'];?></td>
                        <td><?php echo $val['TrainingBatchMaster']['Remarks'];?></td>
                        <td><?php echo date('d-M-y',strtotime($val['TrainingBatchMaster']['StartDate'])) ;?></td>
                        <td><?php echo date('d-M-y',strtotime($val['TrainingBatchMaster']['EndDate'])) ;?></td>
                    </tr>
                    <?php }?>
                </tbody>   
                </table>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-5">
                        <textarea name="ApproveFirstRemarks" id="ApproveFirstRemarks" class="form-control" required="" ></textarea>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <!--
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus/od"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        -->
                        
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



