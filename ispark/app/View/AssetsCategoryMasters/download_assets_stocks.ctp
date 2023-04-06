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

function print_label(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Product     =   $("#Product").val();
    var S1          =   $("#S1").val();
    var S2          =   $("#S2").val();
   
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
    else if(S1 ===""){
        $("#S1").focus();
        $("#S1").after("<span id='msgerr' style='color:red;'>Select first serial no.</span>");
        return false;
    }
    else if(S2 ===""){
        $("#S2").focus();
        $("#S2").after("<span id='msgerr' style='color:red;'>Select last serial no.</span>");
        return false;
    }
    else{
        window.open('<?php echo $this->webroot?>pdf/examples/label.php?B='+BranchName+'&P='+Product+'&S1='+S1+'&S2='+S2, '_blank');
    }   
}

function get_serial_no(){
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
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/print_label",{BranchName:BranchName,Product:Product}, function(data){
            $("#S1").html(data);
            $("#S2").html(data);
        });
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
                    <span>Download Assets Stocks</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'print_label','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                
                    <label class="col-sm-1 control-label">Product</label>
                    <div class="col-sm-3">
                        <select name="Product" id="Product" onchange="get_serial_no()" class="form-control">
                            <option value="">Select</option>
                            <option value="TFT">TFT</option>
                            <option value="CPU">CPU</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">First&nbsp;Serial&nbsp;No</label>
                    <div class="col-sm-3">
                        <select name="S1" id="S1" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Last&nbsp;Serial&nbsp;No</label>
                    <div class="col-sm-3">
                        <select name="S2" id="S2" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
          
                <div class="form-group">
                    <div class="col-sm-8">
                        <input type="button" onclick="print_label()"  value="Download"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
