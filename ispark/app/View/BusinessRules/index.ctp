<style>
     .contact {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 5px;
        }

        .contact:hover {
            background-color: #f0f0f0;
        }

        /* Style for the chat container */
        .chat-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Style for chat messages */
        .message {
            padding: 10px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            word-wrap: break-word;
            max-width: 70%; /* Adjust the width based on your design */
        }

        /* Style for request messages (right) */
        .message.request {
            background-color: #4CAF50; /* Green or any color you prefer */
            color: #fff;
            float: right;
            clear: both; /* Add to separate message pairs */
        }

        /* Style for response messages (left) */
        .message.response {
            background-color: #f1f1f1; /* Light gray or any color you prefer */
            float: left;
            clear: both; /* Add to separate message pairs */
        }



</style>
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#AttenDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
</script>
<script>
function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>Tickets/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function showdata(Type){ 
    $("#msgerr").remove();
    var BranchName=$("#BranchName").val();
    var department=$("#department").val();
    var status=$("#status").val();

    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }else if(department ==="")
    {
        $("#department").focus();
        $("#department").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select department.</span>");
        return false;
    }
    else if(status ==="")
    {
        $("#status").focus();
        $("#status").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select status.</span>");
        return false;
    }

    else{

        $("#loder").show();
        $("#form1").submit();
        
    }
}

function showPopup(id)
{
    $("#close_id").val(id);

}

function updateStatusCell(ticketId, newStatus) {
    const statusCell = document.getElementById(`status_${ticketId}`);
    if (statusCell) {
        if (newStatus === 'Close') {
            statusCell.innerHTML = '<span class="status-closed">Close</span>';
        } else {
            statusCell.innerHTML = '<span class="status-open">Open</span>';
        }
    }
}


function submitForm(form,path){

    var close_id = $("#close_id").val();
    var remarks = $("#remarks").val();


    if(remarks ===""){
        $("#remarks").focus();
        $("#remarks").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Remarks.</span>");
        return false;
    }


    $.post(path,{remarks:remarks,close_id:close_id}).done(function(data){
    
        //location.reload(true);
        if(data == "1")
        {
            alert('Ticket Close successfully.');
            updateStatusCell(close_id, 'Close');
        }else{
            alert('Please Try Again.');
        }
        

        
    });
    return true;
}

function tic_reopen(Id){
   
   if(confirm("Are you sure you want to open this ticket?")){
       window.location="<?php echo $this->webroot;?>BusinessRules/reopen_ticket?Id="+Id;
   }

}

function showchat(id)
{
    $("#msgerr").remove();
    $.post("<?php echo $this->webroot;?>BotReports/chat_history",{chat_id:id}, function(data) {
                
        if(data !=""){
            $("#chat_history").html(data);
        }
        else{
            $("#chat_history").html('<div class="col-sm-12" style="color:red;font-weight:bold;">Record not found.</div>');
        } 
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
            <div class="box-header">
                <div class="box-name">
                    <span>2GTHR@MAS</span>
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
                <?php echo $this->Form->create('BusinessRules',array('action'=>'index','class'=>'form-horizontal','id'=>'form1','enctype'=>'multipart/form-data')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>

                    <!-- <label class="col-sm-1 control-label">Department</label>
                    <div class="col-sm-2">
                    <?php //echo $this->Form->input('department',array('label' => false,'options'=>$department,'empty'=>'Select','class'=>'form-control','id'=>'department','required'=>true)); ?>
                    </div> -->

                    <label class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('status',array('label' => false,'options'=>['1'=>'Open','0' => 'Close'],'empty'=>'Select','class'=>'form-control','id'=>'status','required'=>true)); ?>
                    </div>

                </div>
                <div class="form-group">

                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                         
                        <input type="button" onclick="showdata();" value="Show" class="btn pull-right btn-primary btn-new">
                        
                    </div>
                    <div class="col-sm-1">
                        <img src="<?php $this->webroot;?>img/ajax-loader.gif" style="width:35px;display: none;" id="loder">
                    </div>

                    
                </div>
              <?php echo $this->Form->end(); ?>
                <?php if(!empty($data)){ ?>
                <table class = "table table-striped table-hover  responstable" style="margin-top:-100px;" >     
                    <thead>
                        <tr><th colspan="15" style="text-align: center;">Details</th></tr>
                        <tr>
                            <th style="text-align: center;">SNo.</th>
                            <th style="text-align: center;">Ticket No.</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Department</th>
                            <th style="text-align: center;">Type</th>
                            <th style="text-align: center;">Remarks</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Contact No</th>
                            <th style="text-align: center;">To</th>
                            <th style="text-align: center;">Cc</th>
                            <th style="text-align: center;">Create Date</th>
                        
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($data as $val){ 
                       ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['ticket_no'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['branch'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['department'];?></td> 
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['type'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['body'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['emp_code'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['emp_name'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['contact_no'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['to'];?></td>
                            <td style="text-align: center;"><?php echo $val['BusinessTickets']['cc'];?></td>
                            <td><?php echo date_format(date_create($val['BusinessTickets']['created_at']),"d-M-Y");?></td>
                            <td style="text-align: center;" id="status_<?php echo $val['BusinessTickets']['id']; ?>">
                            <?php if($val['BusinessTickets']['ticket_status'] == '1'  && ($val['BusinessTickets']['case_status'] == 'open' || $val['BusinessTickets']['case_status'] == 're-open')){?>
                               
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $val['BusinessTickets']['id'];?>')"> <label class="btn btn-xs btn-midnightblue btn-raised"><span class="status-open">Close</span><div class="ripple-container"></div></label></a>
                            <?php }else {?>
                                <h5>Already Close</h5>
                            <?php }?>
                            || <a href="#" data-toggle="modal" data-target="#show_chat" onclick="showchat('<?php echo $val['BusinessTickets']['chat_id'];?>')"> <label class="btn btn-xs btn-midnightblue btn-raised"><span class="status-open">Chat</span><div class="ripple-container"></div></label></a>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>   
                </table>                
                <?php }?>
                
            </div>
        </div>
    </div>	
</div>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                       
                    </div>
                    <div class="modal-body">
                        <p id="ecr-text-message"></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #436E90;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Close Ticket</h2>      
            </div>
            <?php echo $this->Form->create('BusinessRules',array('id'=>'form_file','enctype'=>"multipart/form-data","class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                             <div class="row"> 
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Remarks</label>
                                        <div class="col-sm-6">
                                        <?php echo $this->Form->input('close_id',array('label'=>false,'type'=>'hidden','class'=>'form-control','id'=>'close_id' ));?>
                                        <?php echo $this->Form->input('remarks',array('label'=>false,'type'=>'textarea','rows'=>'5','class'=>'form-control','id'=>'remarks' ));?>
                                        </div>
                                    </div>
                                </div> 
                                 
                             </div> 
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="submitForm(this.form,'<?php echo $this->webroot;?>BusinessRules/close_ticket')"  value="Submit" class="btn-web btn">
                    <!-- <input type="submit"   value="Submit" class="btn-web btn"> -->
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<div class="modal fade" id="show_chat"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #436E90;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Chat</h2>      
            </div>
                
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active"> 
                            <div class="row"> 
                            <div class="col-md-12" style="overflow-y:scroll;height:500px;"> 
                                <div class="chat-container" id="chat_history"></div>                                  
                            </div> 
                            </div> 
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>  
        </div>
    </div>
</div>






