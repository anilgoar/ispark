
<script>
function validateDeleteMark(){  
    $("#msgerr").remove();
    var BranchName = $('#BranchName').val();
    var CostCenter = $('#CostCenter').val();
    var DeleteDate = $('#DeleteDate').val();
   
    
    if(BranchName ===""){
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else if(CostCenter ===""){
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else if(DeleteDate ===""){
        $("#DeleteDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select an item.</span>");
        return false;
    }
    else{
      return true; 
    }   
}

function getCostCenter(BranchName){
    $.post("<?php echo $this->webroot;?>DeleteFieldAttendances/get_cost_center",{'BranchName':BranchName}, function(data) {
        if(data !=""){
            $("#CostCenter").html(data);
        }
        else{
            $("#CostCenter").html('');  
        }
    });
}

function addMark(){
    window.location.href="<?php echo $this->webroot;?>MarkFieldAttendances";
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
                    <span>DELETE FIELD ATTENDANCE</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('delete-field-attendance',array('class'=>'form-horizontal','onsubmit'=>'return validateDeleteMark();')); ?>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','id'=>'BranchName','onchange'=>'getCostCenter(this.value)','required'=>true)); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-2">
                        <select name="CostCenter"  id="CostCenter" required="" class="form-control">
                           
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-2"> 
                        <select  class="form-control" name="DeleteDate" id="DeleteDate" required=""  >
                            <option value="">Select Date</option>
                            <?php foreach($dateArr as $dt){?>
                            <option value="<?php echo $dt['Attandence']['AttandDate'];?>"><?php echo date('d-M-Y',strtotime($dt['Attandence']['AttandDate'])) ;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='button' onclick="addMark();" class="btn btn-primary pull-right btn-new" value="Add Mark" style="margin-left: 5px;" >
                        <input type='submit'  class="btn btn-primary pull-right btn-new" value="Delete" style="margin-left: 5px;" >
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>







