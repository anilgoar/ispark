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
function validateTrainingRoom(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var Room=$("#Room").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select brancht.</span>");
        return false;
    }
    else if(Room ===""){
        $("#Room").focus();
        $("#Room").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter training room.</span>");
        return false;
    }
    else{
        return true;
    }
}

function getRoomDetails(BranchName){
    $("#msgerr").remove();
    $.post("<?php echo $this->webroot;?>TrainingRoomMasters/show_room",{BranchName:BranchName}, function(data){
        if(data !=""){
            $("#roomdetails").html(data);
        }
        else{
            $("#roomdetails").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
        } 
    });
}



function actionlist(path,action){
    if(action =="edit"){
        window.location=path;
    }
    else if(action =="delete"){
        if(confirm('Are you sure you want to delete this list?')){
            window.location=path;
        }
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
                    <span>TRAINING ROOM MASTER</span>
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
                <?php echo $this->Form->create('TrainingRoomMasters',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return validateTrainingRoom()')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','onchange'=>'getRoomDetails(this.value)','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">TrainingRoom</label>
                    <div class="col-sm-3">
                        <input type="text" id="Room" name="Room" required="" autocomplete="off" class="form-control" >
                    </div>
       
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $backurl;?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6" id="roomdetails" >
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:50px;">SNo</th>
                                    <th>BranchName</th>
                                    <th>Training Room</th>
                                    <th style="width:40px;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $val['TrainingRoomMaster']['BranchName'];?></td>
                                    <td><?php echo $val['TrainingRoomMaster']['Room'];?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingRoomMasters/deletesource?id=<?php echo base64_encode($val['TrainingRoomMaster']['Id']);?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



