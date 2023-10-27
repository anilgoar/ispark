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
        window.location="<?php echo $this->webroot;?>EmpAttStatuses/delete_alert?Id="+Id;
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
                    <span>Edit Employee AWOL (Absent Without Offical Leave)</span>
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
                <?php echo $this->Form->create('EmpAttStatuses',array('action'=>'update_mail_alert','class'=>'form-horizontal','id'=>'form1')); ?>

                <input type="hidden" name="mail_alert_id" id="mail_alert_id" value="<?php echo $mail_arr['MailAlert']['id'];?>">

                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('Branch',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','value'=>$mail_arr['MailAlert']['Branch'],'required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control">
                            <option value="">Select</option>
                            <?php if(isset($mail_arr['MailAlert']['CostCenter']))
                            {
                                echo "<option value=".$mail_arr['MailAlert']['CostCenter']." selected>".$mail_arr['MailAlert']['CostCenter']."</option>";
                            } ?>
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('to',array('label' => false,'class'=>'form-control','id'=>'to','value'=>$mail_arr['MailAlert']['to'],'required'=>true)); ?>
                    </div>

                </div> 
                
                <div class="form-group">

                    <label class="col-sm-1 control-label">Cc</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('cc',array('label' => false,'class'=>'form-control','id'=>'cc','value'=>$mail_arr['MailAlert']['cc'],'required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">Bcc</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('bcc',array('label' => false,'class'=>'form-control','value'=>$mail_arr['MailAlert']['bcc'],'id'=>'bcc')); ?>
                    </div>

                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('remarks',array('label' => false,'class'=>'form-control','type'=>'textarea','value'=>$mail_arr['MailAlert']['remarks'])); ?>
                    </div>
              
                    <div class="col-sm-2">
                        <input type="button" onclick="SubmitForm();" value="Update" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input onclick='return window.location="<?php echo $this->webroot;?>EmpAttStatuses/edit_mail_alert"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>                 
                <?php echo $this->Form->end(); ?>
                 
            </div>

            
            
        </div>
    </div>	
</div>
