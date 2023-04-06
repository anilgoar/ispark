<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
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
<script language="javascript">
$(function () {
    $("#HolydayDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function add_campaign(){ 
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var Process     =   $("#Process").val();
    var Campaign    =   $.trim($("#Campaign").val());
    
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Process").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select process name.</span>");
        return false;
    }
    else if(Campaign ===""){
        $("#Campaign").focus();
        $("#Campaign").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter campaign name.</span>");
        return false;
    }
    else{
        return true;    
    }
}

function edit_campaign(Id){
    window.location.href = "<?php echo $this->webroot;?>TrainingQuestionSets/training_campaign?Id="+Id; 
}

function delete_campaign(Id){
    if(confirm('Are you sure you want to delete this list item?')){
        window.location.href = "<?php echo $this->webroot;?>TrainingQuestionSets/delete_campaign?Id="+Id;
    }  
}

function getProcess(Branch){
    $.post("<?php echo $this->webroot;?>TrainingQuestionSets/get_process_list",{Branch:Branch}, function(data){
        $("#Process").html(data);
    });
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
                    <span>Add Campaign</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('TrainingQuestionSets',array('action'=>'training_campaign','class'=>'form-horizontal','onsubmit'=>'return add_campaign()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>isset($data['Branch'])?$data['Branch']:'','empty'=>'Select','onchange'=>'getProcess(this.value)','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Process</label>
                    <div class="col-sm-2">
                        <select name="Process" id="Process"  autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                            <?php foreach ($Parent_List as $key=>$val){?>
                            <option <?php echo isset($data['Parent_Id']) && $data['Parent_Id']==$key?"selected='selected'":'';?>  value="<?php echo $key;?>" ><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Campaign</label>
                    <div class="col-sm-2">
                        <input type="text" name="Campaign" id="Campaign" value="<?php echo isset($data['Process'])?$data['Process']:'';?>"  autocomplete="off" class="form-control" >
                    </div>
                    
                    <div class="col-sm-3">
                        <input onclick="return window.location='<?php echo $this->webroot;?>Menus?AX=MTMz'" type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                        <?php if(empty($data)){?>
                            <input type="submit" name="Submit" value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <?php }else{?>
                            <input type="hidden" name="Id" value="<?php echo isset($data['Id'])?$data['Id']:'';?>" >
                            <input onclick="return window.location='<?php echo $this->webroot;?>TrainingQuestionSets/training_campaign'" type="button" value="Add New" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;">
                            <input type="submit" name="Submit" value="Update"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                       <?php }?>
                    </div>
                </div>
               
                
                <span><?php echo $this->Session->flash(); ?></span>
                
                <div class="form-group form-horizontal" id="view_mail" >
                    <div class="col-sm-12" style="margin-top:-25px;">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Branch</th>
                                    <th>Process</th>
                                    <th>Campaign</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($DataArr as $row){//$row=$val['TrainingProcess'];?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Branch']?></td>
                                    <td><?php echo $row['Process']?></td>
                                    <td><?php echo $row['Campaign']?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="edit_campaign('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >edit</i></span>
                                        <span class='icon' ><i onclick="delete_campaign('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
