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

function allocate_stocks(form){
    $("#msgerr").remove();
    var formData    =   $(form).serialize();
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    var Process     =   $("#Process").val();
    
    var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
        aIds.push(all_location_id[x].value);
    }
    
   
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").after("<span id='msgerr' style='color:red;'>Select product name.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Process").after("<span id='msgerr' style='color:red;'>Enter process name.</span>");
        return false;
    }
    else if(aIds ==''){
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select record for allocate stocks.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/allocate_assets_stocks",formData).done(function(data){
            view_assets_stocks();
        });
    }   
}

function view_assets_stocks(){
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#Product").val('');
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").val('');
        $("#Product").after("<span id='msgerr' style='color:red;'>Select product name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/view_assets_stocks",{BranchName:BranchName,Product:Product}, function(data){
            $("#view_assets_stocks").html(data);
        });
    }
}

function get_assets_process(BranchName){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_assets_process",{BranchName:BranchName}, function(data){
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
                    <span>Allocate Assets Stocks</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'allocate_assets_stocks','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','onchange'=>'get_assets_process(this.value)','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Product</label>
                    <div class="col-sm-3">
                        <select name="Product" id="Product" onchange="view_assets_stocks()" class="form-control">
                            <option value="">Select</option>
                            <?php foreach($Product_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Process</label>
                    <div class="col-sm-3">
                        <select name="Process" id="Process" class="form-control" >
                            <option value="">Select</option>
                            <?php foreach($Process_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
     
                <div class="form-group form-horizontal" id="view_assets_stocks"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
