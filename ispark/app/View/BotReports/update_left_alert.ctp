<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>Tickets/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function SubmitForm()
{ 

    $("#msgerr").remove();

    var branch       = $("#BranchName").val();
    var costcenter       = $("#CostCenter").val();
    var to       = $("#to").val();
    var cc       = $("#cc").val();
    var bcc       = $("#bcc").val();
    var type       = $("#type").val();

    if(branch ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Branch.</span>");
        return false;
    }
    else if(costcenter ===""){
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Costcenter.</span>");
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

function Ticketaction(Id){
   
    if(confirm("Are you sure you want to delete this record?")){
        window.location="<?php echo $this->webroot;?>Tickets/delete_alert?Id="+Id;
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
                    <span>Employee OFF boarding Alert</span>
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
                <?php echo $this->Form->create('Tickets',array('action'=>'update_left_alert','class'=>'form-horizontal','id'=>'form1')); ?>

                <input type="hidden" name="left_alert_id" id="left_alert_id" value="<?php echo $onboard_alert['OnboardLeaveAlert']['id'];?>">

                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','value'=>$onboard_alert['OnboardLeaveAlert']['branch'],'required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control">
                            <option value="">Select</option>
                            <?php if(isset($onboard_alert['OnboardLeaveAlert']['cost_center']))
                            {
                                echo "<option value=".$onboard_alert['OnboardLeaveAlert']['cost_center']." selected>".$onboard_alert['OnboardLeaveAlert']['cost_center']."</option>";
                            } ?>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('trigger_type',array('label' => false,'options'=>['bio_id'=>'BioCode Deletion','partner_id_req' => 'Partner Deletion','email_id'=>'Email Deletion','ad_id' => 'Ad Id Deletion'],'empty'=>'Select','class'=>'form-control','id'=>'type','value'=>$onboard_alert['OnboardLeaveAlert']['trigger_type'],'required'=>true)); ?>
                    </div>
                </div> 
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('to',array('label' => false,'class'=>'form-control','id'=>'to','value'=>$onboard_alert['OnboardLeaveAlert']['to'],'required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">Cc</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('cc',array('label' => false,'class'=>'form-control','id'=>'cc','value'=>$onboard_alert['OnboardLeaveAlert']['cc'],'required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">Bcc</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('bcc',array('label' => false,'class'=>'form-control','id'=>'bcc','value'=>$onboard_alert['OnboardLeaveAlert']['bcc'])); ?>
                    </div>
              
                    <div class="col-sm-2">
                        <input type="button" onclick="SubmitForm();" value="Update" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Tickets/edit_left_alert"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
                 
            </div>

            
            
        </div>
    </div>	
</div>
