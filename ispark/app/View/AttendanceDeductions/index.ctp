<?php ?>

<script>
function getBranch(BranchName){
    $.post("<?php echo $this->webroot;?>AttendanceDeductions/get_cost_center",{'BranchName':BranchName}, function(data) {
        if(data !=""){
            $("#CostCenter").html(data);
        }
        else{
            $("#CostCenter").html('');  
        }
    });
}
function getDetails(CostCenter){
    $("#msgerr").remove();
    $("#save").hide();
    var ActionFor=$("#ActionFor").val();
    var BranchName=$("#BranchName").val();
    
    if(ActionFor ===""){
        $("#ActionFor").focus();
        $("#ActionFor").after("<span id='msgerr' style='color:red;font-size:11px;'>Please fill out this field.</span>");
        return false;
    }
    else{
        
        if(ActionFor==="Deduction"){
            var url="<?php echo $this->webroot;?>AttendanceDeductions/get_deduction";
        
            $.post(url,{'ActionFor':ActionFor,'BranchName':BranchName,'CostCenter':CostCenter}, function(data) {
                if(data !=""){
                    $("#ActionDetails").html(data);
                    $("#save").show();
                }
                else{
                   $("#ActionDetails").html('');
                   $("#save").hide();
                }
            }); 
        }
        else{
            
            $("#ActionDetails").html("<span style='color:red;font-size:12px;font-weight:bold;margin-left:170px;'>Record not found.</span>"); 
        }
    }
}

function validatdDeduction(){  
    $("#msgerr").remove();
    var all_location_id = document.querySelectorAll('input[name="check[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
      
    if(aIds==''){
        $("#Action").after("<span id='msgerr' style='color:red;font-size:11px;'>Please check record.</span>");
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
                    <span>ATTENDANCE DEDUCTIONS</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AttendanceDeductions',array('action'=>'index','class'=>'form-horizontal','onsubmit'=>'return validatdDeduction()')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Action For</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('ActionFor',array('label' => false,'options'=>array('Attendance'=>'Attendance','Incentive'=>'Incentive','Deduction'=>'Deduction'),'class'=>'form-control','empty'=>'Select','id'=>'ActionFor','required'=>true)); ?>
                    </div>
                    <label class="col-sm-2 control-label">Salary Date</label>
                    <div class="col-sm-3" style="color:red;"><?php echo date('M-Y', strtotime('-1 month', time()));?></div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select','id'=>'BranchName','onchange'=>'getBranch(this.value);','required'=>true)); ?>
                    </div>
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3" >
                        <select name="CostCenter" id="CostCenter" class="form-control" onchange="getDetails(this.value);" required=""  >    
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Action</label>
                    <div class="col-sm-3" >
                        <select name="Action" id="Action" class="form-control" required=""  > 
                            <option value="">Select</option>
                            <option value="Discard">Discard</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-3" >
                        <?php echo $this->Form->submit('Save', array('div'=>false, 'name'=>'Submit','id'=>'save','style'=>'display:none;','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                  
                </div>

                <div class="form-group">
                    <div class="col-sm-12" id="ActionDetails" ></div>
                </div>
                
                <div class="form-group">
                     <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
     
               <?php echo $this->Form->end(); ?>
              
            </div>
        </div>
    </div>	
</div>
