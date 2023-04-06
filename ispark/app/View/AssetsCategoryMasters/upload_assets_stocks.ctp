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

.alert-danger {
    color: #a94442 !important;
    background-color: #f2dede !important;
    border-color: #ebccd1 !important;
    font-size: 16px !important;
    margin-left:15px;
    margin-right:5px;
    width:97%;
}
</style>
<script language="javascript">
$(document).ready(function(){
    get_category('<?php echo isset($data['Branch'])?$data['Branch']:'';?>','<?php echo isset($data['Parent_Id'])?$data['Parent_Id']:'';?>');
});

/*
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
}*/

function get_sub_category(Parent_Id,Value){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_sub_category",{Parent_Id:Parent_Id,Value:Value}, function(data){
        $("#Category").html(data);
    });
    
}

function uploadStocks(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    var UploadFile  =   $("#UploadFile").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").after("<span id='msgerr' style='color:red;'>Select Product name.</span>");
        return false;
    }
    else if(UploadFile ===""){
        $("#UploadFile").focus();
        $("#UploadFile").after("<span id='msgerr' style='color:red;'>Upload Product Stocks.</span><br/>");
        return false;
    }
    else{
        return true;
    }   
}


function downloadStocks(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").after("<span id='msgerr' style='color:red;'>Select Product name.</span>");
        return false;
    }
    else{
        window.location.href = "<?php echo $this->webroot;?>AssetsCategoryMasters/download_assets_stocks?b="+BranchName+"&p="+Product; 
    }   
}



function view_assets_master(Category_Id){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/view_assets_master",{Category_Id:Category_Id}, function(data){
        clear_form_elements('form-control');
        $("#view_assets_master").html(data);
    });
}

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
/*
function submitForm(form,Category_Id){
    $("#msgerr").remove();
    var formData = $(form).serialize(); 
    var Fixed_Line_Number=$("#Fixed_Line_Number").val();
    var Broadband_ID=$("#Broadband_ID").val();
    
    if(Fixed_Line_Number ===""){
        $("#Fixed_Line_Number").focus();
        $("#Fixed_Line_Number").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    if(Broadband_ID ===""){
        $("#Broadband_ID").focus();
        $("#Broadband_ID").after("<span id='msgerr' style='color:red;'>This field is required</span>");
        return false;
    }
    
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/add_assets_details",formData).done(function(data){
        view_assets_master(Category_Id);
    });
}
*/
   

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
                    <span>Upload Assets Stocks</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'upload_assets_stocks','onsubmit'=>'return uploadStocks()','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Product</label>
                    <div class="col-sm-3">
                        <select name="Product" id="Product" class="form-control">
                            <option value="">Select</option>
                            <?php foreach($Product_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Product</label>
                    <div class="col-sm-3">
                        <input type="file" name="UploadFile" id="UploadFile" class="form-control" accept=".csv"/>
                        <span>Note- Upload only csv file.</span>
                    </div>
                </div>
                <div class="form-group">    
                    <div class="col-sm-4">
                        <input type="button" onclick="downloadStocks()" value="Download"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                        <input type="submit" name="Submit" value="Upload"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
                <span><?php echo $this->Session->flash();?></span>
            </div>
        </div>
    </div>	
</div>
