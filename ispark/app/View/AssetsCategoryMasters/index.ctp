<script language="javascript">
function add_category(){ 
    $("#msgerr").remove();
    var Category        =   $.trim($("#Category").val());
     
    if(Category ===""){
        $("#Category").focus();
        $("#Category").after("<span id='msgerr' style='color:red;'>Please select category name.</span>");
        return false;
    }
    else{
        return true;    
    }
}

function delete_row(Id){
    if(confirm('Are you sure you want to delete this list item?')){
        window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters/delete_row?Id="+Id;
    }  
}

function edit_row(Id){
    window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters?Id="+Id; 
}

function Add_New(){
    window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters"; 
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
                    <span>Assets Category Master</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'index','class'=>'form-horizontal','onsubmit'=>'return add_category()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Category</label>
                    <div class="col-sm-3">
                        <input type="text" name="Category" id="Category" value="<?php echo isset($data['Category'])?$data['Category']:'';?>"  autocomplete="off" class="form-control">
                    </div>
                    
                    <div class="col-sm-2">
                        <?php if(!empty($data)){?>
                        <input type="hidden" name="Id" value="<?php echo isset($data['Id'])?$data['Id']:'';?>" >
                        <input type="button" onclick="Add_New();" value="Add New"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit" value="Update"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <?php }else{?>
                        <input type="submit" name="Submit" value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
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
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($DataArr as $val){$row=$val['AssetsCategoryMasters'];?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Category']?></td>
                                    <td style="text-align: center;">
                                        <span class='icon' ><i onclick="edit_row('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i></span>
                                        <span class='icon' ><i onclick="delete_row('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i>
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
