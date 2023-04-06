<?php ?>
<script>
function validateForm(){
    $("#msgerr").remove();
    var Department_Name =   $.trim($("#Department_Name").val());
    
    if(Department_Name ===""){
        $("#Department_Name").focus();
        $("#Department_Name").after("<span id='msgerr' style='color:red;font-size:11px;'>Enter department name.</span>");
        return false;
    }
    else{
        return true;
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
                <?php echo $this->Form->create('IsparkDepartments',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return validateForm()')); ?>
                <div class="form-group"> 
                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-3">
                        <input type="text" id="Department_Name" name="Department_Name" value="<?php echo isset($row['Department_Name'])?$row['Department_Name']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    
                    <div class="col-sm-2">
                        <?php if(isset($row)){?>
                        <input type="hidden" name="Department_Id" value="<?php echo isset($row['Department_Id'])?$row['Department_Id']:'';?>" >
                        <input type="button" onclick='return window.location="<?php echo $this->webroot;?>IsparkDepartments"'  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
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
                                    <th style="width:60px;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $val['IsparkDepartmentMaster']['Department_Name'];?></td>
                                    <td>
                                        <a href="<?php $this->webroot;?>IsparkDepartments?id=<?php echo base64_encode($val['IsparkDepartmentMaster']['Department_Id']);?>" ><span class='icon' ><i class="material-icons" style="font-size:20px;" >mode_edit</i></span></a>
                                        <a href="<?php $this->webroot;?>IsparkDepartments/delete_department?id=<?php echo base64_encode($val['IsparkDepartmentMaster']['Department_Id']);?>" onclick="return confirm('Are you sure you want to delete this item?');" ><span class='icon' ><i class="material-icons" style="font-size:20px;" >delete</i></span></a>
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



