<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
$(function (){
    $(".textdatepicker").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

function get_sub_category(Parent_Id,Value){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_sub_category",{Parent_Id:Parent_Id,Value:Value}, function(data){
        $("#Category").html(data);
    });
}

function get_form(Category_Id,Row_Id){
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var Parent_Id    =   $("#Parent_Id").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#Category").val('');
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    if(Parent_Id ===""){
        $("#Parent_Id").focus();
        $("#Category").val('');
        $("#Parent_Id").after("<span id='msgerr' style='color:red;'>Select category name.</span>");
        return false;
    }
    else if(Category_Id ===""){
        $("#Category").focus();
        $("#Category").after("<span id='msgerr' style='color:red;'>Enter sub category.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_form",{BranchName:BranchName,Category_Id:Category_Id,Row_Id:Row_Id}, function(data){
            $("#Category_Form").html(data);
        });
        
        view_assets_master(Category_Id,BranchName);
    }   
}

function view_assets_master(Category_Id,BranchName){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/view_assets_master",{Category_Id:Category_Id,BranchName:BranchName}, function(data){
        $("#view_assets_master").html(data);
    });
}

function delete_assets_details_master(Id,BranchName,Category_Id){
    if(confirm('Are you sure you want to delete this list item?')){
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/delete_assets_details_master",{Id:Id}, function(data){
            view_assets_master(Category_Id,BranchName);
        });
    }  
}

function edit_assets_details_master(Id,Category_Id){
    get_form(Category_Id,Id);
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
                    <span>Assets Details Master</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'add_assets_details','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Category</label>
                    <div class="col-sm-3">
                        <select name="Parent_Id" id="Parent_Id" onchange="get_sub_category(this.value,'')" class="form-control" >
                            <option value=''>Select</option>
                            <?php foreach ($CategoryList as $key=>$val){?>
                            <option value='<?php echo $key;?>'><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Sub&nbsp;Category</label>
                    <div class="col-sm-3">
                        <select name="Category" id="Category" onchange="get_form(this.value,'')" class="form-control" >
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group form-horizontal" id="Category_Form"></div>
                
                <div class="form-group form-horizontal">
                    <div class="col-sm-12" id="view_assets_master" style="margin-top:-25px;"> </div>        
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
