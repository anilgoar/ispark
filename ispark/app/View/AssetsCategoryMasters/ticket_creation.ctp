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
    var TL_Name     =   $("#TL_Name").val();
    
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
    }/*
    else if(Serial_No ===""){
        $("#Serial_No").focus();
        $("#Serial_No").after("<span id='msgerr' style='color:red;'>Select serial no.</span>");
        return false;
    }
    else if(Problem ===""){
        $("#Problem").focus();
        $("#Problem").after("<span id='msgerr' style='color:red;'>Select problem.</span>");
        return false;
    }*/
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
    else if(TL_Name ===""){
        $("#TL_Name").focus();
        $("#TL_Name").after("<span id='msgerr' style='color:red;'>Enter TL name.</span>");
        return false;
    }
    else{
        return true;
    }   
}

function get_serial_no(){
    $("#msgerr").remove();
    
    var BranchName  =   $("#BranchName").val();
    var Process     =   $("#Process").val();
    var Product     =   $("#Product").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#Product").val('');
        $("#BranchName").after("<span id='msgerr' style='color:red;'>Select branch name.</span>");
        return false;
    }
    else if(Process ===""){
        $("#Process").focus();
        $("#Product").val('');
        $("#Process").after("<span id='msgerr' style='color:red;'>Enter process name.</span>");
        return false;
    }
    else if(Product ===""){
        $("#Product").focus();
        $("#Product").val('');
        $("#Product").after("<span id='msgerr' style='color:red;'>Select product name.</span>");
        return false;
    }
    else{
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_serial_no_list",{BranchName:BranchName,Process:Process,Product:Product}, function(data){
            $("#Serial_No").html(data);
        });
        
        $.post("<?php echo $this->webroot;?>AssetsCategoryMasters/get_assets_problem_list",{Product:Product}, function(data){
            $("#Problem").html(data);
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
                    <span>Ticket Creation</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('AssetsCategoryMasters',array('action'=>'ticket_creation','class'=>'form-horizontal','onsubmit'=>'return ticket_creation()','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$this->Session->read('branch_name'),'empty'=>'Select','onchange'=>'get_assets_process(this.value)','class'=>'form-control','id'=>'BranchName')); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Process</label>
                    <div class="col-sm-3">
                        <select name="Process" id="Process" class="form-control">
                            <option value="">Select</option>
                            <?php foreach($Process_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Product</label>
                    <div class="col-sm-3">
                        <select name="Product" id="Product" onchange="get_serial_no()" class="form-control">
                            <option value="">Select</option>
                            <?php foreach($Product_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Serial No</label>
                    <div class="col-sm-3">
                        <select name="Serial_No" id="Serial_No" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Problem</label>
                    <div class="col-sm-3">
                        <select name="Problem" id="Problem" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-3">
                        <input type="text" name="Remarks" id="Remarks" autocomplete="off" class="form-control" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Agent&nbsp;Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="Agent_Name" id="Agent_Name" autocomplete="off" class="form-control" >
                    </div>
                    
                    <label class="col-sm-1 control-label">TL&nbsp;Name</label>
                    <div class="col-sm-3">
                        <select name="TL_Name" id="TL_Name" class="form-control">
                            <option value="">Select</option>
                            <?php foreach($TL_List as $key=>$val){?>
                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-8">
                        <input type="submit"  value="Submit"  class="btn pull-right btn-primary btn-new" style="margin-left: 5px;">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $this->Session->flash(); ?>
                    </div>
                </div>
                
                <div class="form-group form-horizontal" id="view_mail" >
                    <div class="col-sm-12" style="margin-top:-25px;">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Product</th>
                                    <th>Process</th>
                                    <th>Serial No</th>
                                    <th>Problem</th>
                                    <th>Remarks</th>
                                    <th>Agent</th>
                                    <th>TL</th>
                                    <th>Status</th>
                                    <th>Create Date</th>
                                    <th>Update Date</th>
                                    <th>Update Status</th>
                                    <th>Replace</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($TicketArr as $row){?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['Product']?></td>
                                    <td><?php echo $row['Process']?></td>
                                    <td><?php echo $row['Serial_No']?></td>
                                    <td><?php echo $row['Problem']?></td>
                                    <td><?php echo $row['Remarks']?></td>
                                    <td><?php echo $row['Agent_Name']?></td>
                                    <td><?php echo $row['TL_Name']?></td>
                                    <td><?php echo $row['Ticket_Status']?></td>
                                    <td><?php echo $row['Create_At']?></td>
                                    <td><?php echo $row['Update_At']?></td>
                                    <td><?php echo $row['Ticket_Status_Remarks']?></td>
                                    <td>
                                        <?php echo $row['Replacement_Serial_No']?>
                                        <?php echo $row['Replacement_Reason']?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
