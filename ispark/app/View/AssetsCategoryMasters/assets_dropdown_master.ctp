<script language="javascript">
function get_sub_category(Parent_Id,Value){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_sub_category",{Parent_Id:Parent_Id,Value:Value}, function(data){
        $("#Category").html(data);
    });
    
}

function get_field_list(Category_Id,Value){
    $("#msgerr").remove();
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_field_list",{Category_Id:Category_Id,Value:Value}, function(data){
        $("#Field_Option").html(data);
    });  
}

function submitForm(form){
    $("#msgerr").remove();
    var formData    =   $(form).serialize();
    var Parent_Id   =   $("#Parent_Id").val();
    var Category    =   $("#Category").val();
    var Field_Option=   $("#Field_Option").val();
    var Option      =   $.trim($("#Option").val());
    
    if(Parent_Id ===""){
        $("#Parent_Id").focus();
        $("#Parent_Id").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    else if(Category ===""){
        $("#Category").focus();
        $("#Category").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    else if(Field_Option ===""){
        $("#Field_Option").focus();
        $("#Field_Option").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    else if(Option ===""){
        $("#Option").focus();
        $("#Option").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/assets_dropdown_master",formData).done(function(data){
        $("#Option").val('');
        $("#Parent_Id").after(data);
        view_option_master(Field_Option);
    });
}

function delete_option_master(Id,Assets_Form_Id){
    if(confirm('Are you sure you want to delete this list item?')){
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/delete_option_master",{Id:Id}, function(data){
            view_option_master(Assets_Form_Id);
        });
    }  
}

function view_option_master(Assets_Form_Id){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/view_option_master",{Assets_Form_Id:Assets_Form_Id}, function(data){
        $("#view_option_master").html(data);
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
                    <span>Assets Dropdown Master</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'assets_dropdown_master','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Category</label>
                    <div class="col-sm-2">
                        <select name="Parent_Id" id="Parent_Id" onchange="get_sub_category(this.value,'')" class="form-control" >
                            <option value=''>Select</option>
                            <?php foreach ($CategoryList as $key=>$val){?>
                            <option value='<?php echo $key;?>'><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Sub&nbsp;Category</label>
                    <div class="col-sm-2">
                        <select name="Category" id="Category" onchange="get_field_list(this.value,'')" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                
                    <label class="col-sm-1 control-label">Fields</label>
                    <div class="col-sm-2">
                        <select name="Field_Option" id="Field_Option" onchange="view_option_master(this.value)" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Option</label>
                    <div class="col-sm-2">
                        <input type="text" name="Option" id="Option" class="form-control" autocomplete="" >
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="button" onclick="submitForm(this.form)" name="Submit" value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>
                
                <div class="form-group form-horizontal">
                    <div class="col-sm-6" id="view_option_master" style="margin-top:-25px;"> </div>        
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
