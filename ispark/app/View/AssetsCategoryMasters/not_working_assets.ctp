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

function ticket_creation(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Process     =   $("#Process").val();
    var Product     =   $("#Product").val();
    var Serial_No   =   $("#Serial_No").val();
    var Problem     =   $("#Problem").val();
    var Remarks     =   $("#Remarks").val();
    var Agent_Name  =   $("#Agent_Name").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Process").after("<span id='msgerr' style='color:red;'>Enter process name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").after("<span id='msgerr' style='color:red;'>Select product name.</span>");
        return false;
    }
    else if(Serial_No ===""){
        $("#Serial_No").focus();
        $("#Serial_No").after("<span id='msgerr' style='color:red;'>Select serial no.</span>");
        return false;
    }
    else if(Problem ===""){
        $("#Problem").focus();
        $("#Problem").after("<span id='msgerr' style='color:red;'>Select problem.</span>");
        return false;
    }
    else if(Remarks ===""){
        $("#Remarks").focus();
        $("#Remarks").after("<span id='msgerr' style='color:red;'>Enter remarks.</span>");
        return false;
    }
    else if(Agent_Name ===""){
        $("#Agent_Name").focus();
        $("#Agent_Name").after("<span id='msgerr' style='color:red;'>Enter agent name.</span>");
        return false;
    }
    else{
        return true;
    }   
}

function show_details(Id){
    $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/show_ticket_details",{Id:Id}, function(data){
        $("#show_details").html(data);
    });
}

function submitForm(form){
    $("#msgerr").remove();
    var formData                =   $(form).serialize();
    var Replacement_Serial_No   =   $("#Replacement_Serial_No").val();
    var Replacement_Reason      =   $("#Replacement_Reason").val();
    var Ticket_Status_Remarks   =   $("#Ticket_Status_Remarks").val();
        
    if(Replacement_Serial_No !="" && Replacement_Reason ==""){
        $("#Replacement_Reason").focus();
        $("#Replacement_Reason").after("<span id='msgerr' style='color:red;'>Enter replacement reason.</span>");
        return false;
    }
    else if(Ticket_Status_Remarks ===""){
        $("#Ticket_Status_Remarks").focus();
        $("#Ticket_Status_Remarks").after("<span id='msgerr' style='color:red;'>Enter ticket status remarks.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/ticket_solution",formData).done(function(data){
            $("#Replacement_Reason").after(data);
        });
    }
}

</script>

<style>
.modal-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1040;
    /*background-color: #000;*/
}
</style>
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
                    <span>Not Working Stocks</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">
                <div class="form-group form-horizontal" id="view_mail" >
                    <div class="col-sm-12" style="margin-top:-25px;">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Product</th>
                                    <th>Vender</th>
                                    <th>Process</th>
                                    <th>Serial No</th>
                                    <th>Upload Date</th>
                                    <th>Working Status</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($DataArr as $row){?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Product']?></td>
                                    <td><?php echo $row['Vender']?></td>
                                    <td><?php echo $row['Process']?></td>
                                    <td><?php echo $row['Serial_No']?></td>
                                    <td><?php echo $row['Create_At']?></td>
                                    <td><?php echo $row['Working_Status']?></td>
                                    <td><?php echo $row['Working_Remarks']?></td>
                                    <td><?php echo $row['Working_Date']?></td>
                                    <td>
                                        <a href="<?php echo $this->webroot;?>AssetsCategoryMasters/restore_not_working_assets?Id=<?php echo $row['Id']?>" onclick="return confirm('Are you sure you want to store this assets in assets stocks?');" title="Restore in stocks" ><i class="material-icons" style="font-size:20px;cursor: pointer;" >save</i></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal -->
                <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="top: 50px;" role="document">
                        <div class="modal-content">
                            <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'ticket_solution','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ticket Details</h5>  
                                </div>
                                  <div class="modal-body" id="show_details" >

                                </div>

                                <div class="modal-footer">
                                    <button type="button" onclick="return location.reload();" class="btn btn-primary btn-new" data-dismiss="modal">Close</button>
                                    <input type="button" onclick="submitForm(this.form)"  value="Submit"  class="btn pull-right btn-primary btn-new">
                                </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>	
</div>
