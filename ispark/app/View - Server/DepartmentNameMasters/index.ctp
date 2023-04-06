<?php ?>
<script>
function validateDepartment(){
    $("#msgerr").remove();
    var Department=$("#Department").val();
    
    if(Department ===""){
        $("#Department").focus();
        $("#Department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter department.</span>");
        return false;
    }
    else{
        return true;
    }
}
    
function addNew(){
    window.location="<?php echo $this->webroot;?>DepartmentNameMasters";
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
                    <span>DEPARTMENT MASTER</span>
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
                <?php echo $this->Form->create('DepartmentNameMasters',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return validateDepartment()')); ?>
                <div class="form-group"> 
                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-3">
                        <input type="text" id="Department" name="Department" value="<?php echo isset($row['Department'])?$row['Department']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    <?php if(isset($row)){?>
                    <div class="col-sm-2">
                        <select class="form-control" name="Status" >
                            <option <?php if($row['Status']=="1"){echo "selected='selected'";}?> value="1" >Active</option>
                            <option <?php if($row['Status']=="0"){echo "selected='selected'";}?> value="0" >Deactive</option>
                        </select>
                    </div>
                    <?php }?>
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php if(isset($row)){?>
                        <input type="hidden" name="DepartmentId" value="<?php echo isset($row['Id'])?$row['Id']:'';?>" >
                        <input type="button" onclick="addNew();"  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input type="submit" name="submit"  value="Update" class="btn pull-right btn-primary btn-new"  >
                        <?php }else{?>
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:50px;">SNo</th>
                                    <th>Department</th>
                                    <th style="width:50px;">Status</th>
                                    <th style="width:40px;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $val['DepartmentNameMaster']['Department'];?></td>
                                    <td><?php if($val['DepartmentNameMaster']['Status'] =="1"){echo "Active";}else{echo "Deactive";}?></td>
                                    <td>
                                        <a href="<?php $this->webroot;?>DepartmentNameMasters?id=<?php echo base64_encode($val['DepartmentNameMaster']['Id']);?>" ><span class='icon' ><i class="material-icons" style="font-size:20px;" >mode_edit</i></span></a>
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



