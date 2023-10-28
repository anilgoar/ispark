<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>BusinessRules/getcostcenter",{BranchName:BranchName}, function(data){
        $("#costcenter").html(data);
    });  
}

function SubmitForm()
{ 

    $("#msgerr").remove();

    var branch       = $("#BranchName").val();
    var department       = $("#department").val();
    var to       = $("#to").val();
    var cc       = $("#cc").val();
    var bcc       = $("#bcc").val();
    var type       = $("#type").val();
    var checked = $("input[name='Empname[]']:checked").length;

    if(branch ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Branch.</span>");
        return false;
    }
    else if(department ===""){
        $("#department").focus();
        $("#department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Department.</span>");
        return false;
    }
    else if(type ===""){
        $("#type").focus();
        $("#type").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Type.</span>");
        return false;
    }
    else if(to ===""){
        $("#to").focus();
        $("#to").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter To.</span>");
        return false;
    }
    else if(cc ===""){
        $("#cc").focus();
        $("#cc").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Cc.</span>");
        return false;
    }
    
    else{

        $("#form1").submit();
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
            <div class="box-header">
                <div class="box-name">
                    <span>Edit Business Rules</span>
		        </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
		    <div class="no-move"></div>
        </div>
           
            
            
            
            <div class="box-content box-con">
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('BusinessRules',array('action'=>'update_rule','class'=>'form-horizontal','id'=>'form1')); ?>

                <input type="hidden" name="rule_id" id="rule_id" value="<?php echo $business_rule['BusinessRule']['id'];?>">

                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','value'=>$business_rule['BusinessRule']['branch'],'required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="costcenter" name="costcenter" autocomplete="off" class="form-control">
                            <option value="">Select</option>
                            <?php if(isset($business_rule['BusinessRule']['costcenter']))
                            {
                                echo "<option value=".$business_rule['BusinessRule']['costcenter']." selected>".$business_rule['BusinessRule']['costcenter']."</option>";
                            } ?>
                            
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('department',array('label' => false,'class'=>'form-control','id'=>'department','required'=>true,'placeholder'=>'department','value'=>$business_rule['BusinessRule']['department'])); ?>

                    </div>

                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                        <?php $type = ['Community'=>'Community','Gratitude'=>'Gratitude','MASCARE'=>'MASCARE']?>
                        <?php echo $this->Form->input('type',array('label' => false,'empty'=>'Select','options'=>$type,'class'=>'form-control','id'=>'type','required'=>true,'value'=>$business_rule['BusinessRule']['type'])); ?>
                    </div>
                </div> 
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('to',array('label' => false,'class'=>'form-control','id'=>'to','required'=>true,'value'=>$business_rule['BusinessRule']['to'])); ?>
                    </div>
                    <label class="col-sm-1 control-label">Cc</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('cc',array('label' => false,'class'=>'form-control','id'=>'cc','required'=>true,'value'=>$business_rule['BusinessRule']['cc'])); ?>
                    </div>
                    <label class="col-sm-1 control-label">Escalation Email</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('bcc',array('label' => false,'class'=>'form-control','id'=>'bcc','value'=>$business_rule['BusinessRule']['bcc'])); ?>
                    </div>
              
                    <div class="col-sm-2">
                        <input type="button" onclick="SubmitForm();" value="Update" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input onclick='return window.location="<?php echo $this->webroot;?>BusinessRules/rule"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
                 
            </div>

            
            
        </div>
    </div>	
</div>
