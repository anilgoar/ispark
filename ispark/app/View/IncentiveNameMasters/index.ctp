<?php ?>
<script>
function validateIncentiveName(){
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var IncentiveName=$("#IncentiveName").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(IncentiveName ===""){
        $("#IncentiveName").focus();
        $("#IncentiveName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select incentive name.</span>");
        return false;
    }
    else{
        return true;
    }
}
    
function addNew(){
    window.location="<?php echo $this->webroot;?>IncentiveNameMasters";
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

function getBranch(branch){
     window.location="<?php echo $this->webroot;?>IncentiveNameMasters?branchname="+branch;
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
                    <span>INCENTIVE NAME MASTER</span>
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
                <?php echo $this->Form->create('IncentiveNameMasters',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return validateIncentiveName()')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>isset($row['BranchName'])?$row['BranchName']:'','value'=>$branch,'empty'=>'Select Branch','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">IncentiveName</label>
                    <div class="col-sm-3">
                        <input type="text" id="IncentiveName" name="IncentiveName" value="<?php echo isset($row['IncentiveName'])?$row['IncentiveName']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php if(isset($row)){?>
                        <input type="hidden" name="IncentiveId" value="<?php echo isset($row['Id'])?$row['Id']:'';?>" >
                        <input type="button" onclick="addNew();"  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input type="submit" name="submit"  value="Update" class="btn pull-right btn-primary btn-new"  >
                        <?php }else{?>
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-8">
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;">SNo</th>
                                    <th style="text-align: center;width:130px;">BranchName</th>
                                    <th style="text-align: center;width:150px;">IncentiveName</th>
                                    <th style="text-align: center;width:50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td style="text-align: center;" ><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['IncentiveNameMaster']['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['IncentiveNameMaster']['IncentiveName'];?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>IncentiveNameMasters?id=<?php echo base64_encode($val['IncentiveNameMaster']['Id']);?>','edit');" class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i></span>
                                        <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>IncentiveNameMasters/deletesource?id=<?php echo $val['IncentiveNameMaster']['Id'];?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                                        
                                        <!--
                                        <a href="<?php $this->webroot;?>IncentiveNameMasters/deletesource?id=<?php echo $val['IncentiveNameMaster']['Id'];?>" onclick="return confirm('Are you sure you want to delete this item?');" ><i  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></a>
                                        <a href="<?php $this->webroot;?>IncentiveNameMasters?id=<?php echo base64_encode($val['IncentiveNameMaster']['Id']);?>" ><i  class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i></a> 
                                        -->
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



