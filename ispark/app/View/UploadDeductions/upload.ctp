<?php ?>
<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function validatdFieldMark(){  
    var MarkDate = $('#MarkDate').val();
    if(MarkDate==''){
        alert("Please select date.");
        return false;
    }
    else{
      return true; 
    }   
}

function reload(url){
    window.location.href = url;
}

function goBack(){
    window.location="<?php echo $this->webroot;?>UploadDeductions";  
}

/*
function checkHd(chkall,chkhd){
    var ChkAl = $('#'+chkall).is(':checked');
    var ChkHd = $('#'+chkhd).is(':checked');
     
    if(ChkAl ==ChkHd){
        alert('Please select any one option.');
        $('input[type="checkbox"]').removeAttr('checked');
        return false;
    }
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
                    <span>DEDUCTION UPLOAD</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                
                
                <div style="position: relative;top:-28px;">
                <?php echo $this->Form->create('UploadDeductions',array('class'=>'form-horizontal','action'=>'upload','onsubmit'=>'return validatdFieldMark()','enctype'=>'multipart/form-data')); ?>
                    <input type="hidden" name="CostCenter" value="<?php echo isset($empArr['cost_center'])?$empArr['cost_center']:'' ?>" >
                    <div class="form-group" >
                        <div class="col-sm-2">Branch</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo $this->Session->read('branch_name');?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Cost Center</div>
                        <div class="col-sm-1">-</div>
                       <div class="col-sm-2"><?php echo $empArr['cost_center'];?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Salary Month</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo date('M-Y', strtotime(date('Y-m')." -1 month"));?></div>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <div class="col-sm-2">Upload Deduction File</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><input type="file" name="UploadDeduction" accept=".csv" required="" ></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <input style="margin-left:10px;"  type='submit' class="btn btn-primary pull-right btn-new" value="Submit">
                            <input type='button' onclick="goBack()" class="btn btn-info pull-right  btn-new" value="Back">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span><?php echo $this->Session->flash(); ?></span>
                        </div>
                    </div>
        
                <?php echo $this->Form->end(); ?> 
                  
                </div>
            </div>
        </div>
    </div>	
</div>




























