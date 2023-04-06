<script language="javascript">
function add_sub_category(){ 
    $("#msgerr").remove();
    var Parent_Id   =   $.trim($("#Parent_Id").val());
    var Category    =   $.trim($("#Category").val());

    if(Parent_Id ===""){
        $("#Parent_Id").focus();
        $("#Parent_Id").after("<span id='msgerr' style='color:red;'>Select category name.</span>");
        return false;
    }
    
    else if(Category ===""){
        $("#Category").focus();
        $("#Category").after("<span id='msgerr' style='color:red;'>Enter sub category.</span>");
        return false;
    }
    else{
        return true;    
    }
}

function delete_row(Id){
    if(confirm('Are you sure you want to delete this list item?')){
        window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters/delete_sub_row?Id="+Id;
    }  
}

function edit_row(Id){
    window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters/assets_sub_category?Id="+Id; 
}

function Add_New(){
    window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters/assets_sub_category"; 
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
                    <span>Add Assets Sub Category</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'assets_sub_category','class'=>'form-horizontal','onsubmit'=>'return add_sub_category()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Category</label>
                    <div class="col-sm-2">
                        <select name="Parent_Id" id="Parent_Id" class="form-control" >
                            <option value=''>Select</option>
                            <?php foreach ($CategoryList as $key=>$val){?>
                            <option <?php echo isset($data['Parent_Id']) && $data['Parent_Id']==$key?"selected='selected'":'';?> value='<?php echo $key;?>'><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Sub&nbsp;Category</label>
                    <div class="col-sm-2">
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
               
                
                <span><?php echo $this->Session->flash();?></span>
                
                <div class="form-group form-horizontal" id="view_mail" >
                    <div class="col-sm-12" style="margin-top:-25px;">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($DataArr as $row){//$row=$val['AssetsCategoryMasters'];?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Category']?></td>
                                    <td>
                                        <table class = "table"  width="100%"  >  
                                            <?php foreach($DataArr[$row['Id']]['Parent_Category'] as $val){?>
                                            <tr>
                                                <td style="text-align:left;border:none;background-color:#FFF;"><?php echo $val['Category'];?></td>
                                                <td style="text-align:right;border:none;background-color:#FFF;" >
                                                    <span class='icon' ><i onclick="edit_row('<?php echo $val['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >mode_edit</i></span>
                                                    <span class='icon' ><i onclick="delete_row('<?php echo $val['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i>
                                                </td>
                                            </tr>
                                            <?php } ?>     
                                        </table>
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
