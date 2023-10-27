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
    var CostCenter=$("#CostCenter").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }else if(CostCenter ==="")
    {
        $("#CostCenter").focus();
        $("#CostCenter").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select Cost Center.</span>");
        return false;
    }

    else{

        $("#loder").show();
        $("#form1").submit();
        
    }
}

function showPopup(id,type,trigger)
{
    $("#close_id").val(id);
    $('#ad_id_form').remove();
    $('#email_id_form').remove();

    // alert(type);
    // alert(trigger);
    if(trigger == 'email_id' && type == 'joiner')
    {   

        var html1 = '';
        html1 += '<div class="form-group" id="email_id_form">';
        html1 += '<label class="col-sm-3 control-label">Email id</label>';
        html1 += '<div class="col-sm-6">';
        html1 += '<input type="text" name="email_id" class="form-control" placeholder="Email id" id="email_id" autocomplete="off" required/>';
        html1 += '</div>';
        html1 += '</div>';

        $('#ad_id_show').append(html);
        $('#email_show').append(html1);

    }else if(trigger == 'bgv'){

        var html = '';
        html += '<div class="form-group" id="ad_id_form">';
        html += '<label class="col-sm-3 control-label">BGV Color</label>';
        html += '<div class="col-sm-6">';
        // html += '<input type="text" name="ad_id" class="form-control" placeholder="Ad id" id="ad_id" autocomplete="off" required/>';
        html += '<select name="bgv_color" id="bgv_color" class="form-control" required>';
        html += '<option value="">Select</option>';
        html += '<option value="Red">Red</option>';
        html += '<option value="Green">Green</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';

        $('#ad_id_show').append(html);

    }
    else if(trigger == 'partner_id_req'){

        var html = '';
        html += '<div class="form-group" id="ad_id_form">';
        html += '<label class="col-sm-3 control-label">Partner Id</label>';
        html += '<div class="col-sm-6">';
        html += '<input type="text" name="partner_id" class="form-control" placeholder="Partner Id" id="partner_id" autocomplete="off" required/>';
        html += '</div>';
        html += '</div>';

        $('#ad_id_show').append(html);

    }
    else{
        $('#ad_id_form').remove();
        $('#email_id_form').remove();
    }

}


// $(document).ready(function(e){
//     $("#form_file").on('submit', function(e){
//         e.preventDefault();
//         $.ajax({
//             type: 'POST',
//             url: '/ispark/tickets/close_ticket',
//             data: new FormData(this),
            
//             contentType: false,
//             cache: false,
//             processData:false,
            
//             success: function(response){
                
// 				location.reload(true);
//                 alert('Ticket Close successfully.');
//             }
//         });
//     });
// });

function submitForm(form,path){

    var log = $("#log").val();
    var log = $("#log").val();

    if(log ===""){
        $("#log").focus();
        $("#log").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Log.</span>");
        return false;
    }

    // if(log ===""){
    //     $("#log").focus();
    //     $("#log").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Enter Log.</span>");
    //     return false;
    // }

    var formData = $(form).serialize();
    //var file_data = $('#log_file').prop('files')[0];
    $("#form_file").on('submit', function(e){
    $.ajax({
            type: 'POST',
            url: '/ispark/tickets/close_ticket1',
            data: new FormData(this),
            
            contentType: false,
            cache: false,
            processData:false,
            
            success: function(response){
				location.reload(true);
                alert(response);
            }
            
        });
        e.preventDefault();
    });

    // $.post(path,formData).done(function(data){
    //     alert(data);
    //     return false;
    //     location.reload(true);
    //     alert('Ticket Close successfully.');

        
    // });
    //return true;
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
                    <span>Ticket Visualizer</span>
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
                <?php echo $this->Form->create('Tickets',array('action'=>'index1','class'=>'form-horizontal','id'=>'form1','enctype'=>'multipart/form-data')); ?>
                
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">CostCenter</label>
                    <div class="col-sm-2">
                        <select id="CostCenter" name="CostCenter" autocomplete="off" class="form-control" >
                            <?php if(isset($costcenter))
                            {
                                echo "<option value=".$costcenter.">".$costcenter."</option>";
                            }else{
                                echo "<option value=''>Select</option>";
                            }?>
                            
                        </select>
                    </div>

                    <label class="col-sm-1 control-label">Type</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('trigger_type',array('label' => false,'options'=>['bio_id'=>'BioCode','partner_id_req' => 'Partner','email_id'=>'Email','bgv'=>'BGV','ad_id' => 'Ad Id'],'empty'=>'Select','class'=>'form-control','id'=>'type','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-2">
                       <?php echo $this->Form->input('status',array('label' => false,'options'=>['1'=>'Open','0' => 'Close'],'empty'=>'Select','class'=>'form-control','id'=>'type','required'=>true)); ?>
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
                        <tr><th colspan="15" style="text-align: center;" >Details</th></tr>
                        <tr>
                            <th style="text-align: center;">SNo.</th>
                            <th style="text-align: center;">Ticket No.</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Costcenter</th>
                            <th style="text-align: center;">Costcenter Name</th>
                            <th style="text-align: center;">Type</th>
                            <th style="text-align: center;">Trigger Type</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">To</th>
                            <th style="text-align: center;">Cc</th>
                            <th style="text-align: center;">Mail Attempt</th>
                            <th style="text-align: center;">Last Mail Status</th>
                            <th style="text-align: center;">Last Mail Date</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php $i=1; foreach ($data as $val){
                       ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['ticket_no'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['branch'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['cost_center'];?></td>
                            <td style="text-align: center;"><?php echo $val['costcentername'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['type']; ?></td>
                            <td style="text-align: center;"><?php if($val['EmpOnService']['trigger_type']== 'bio_id'){ echo "BioCode";}else if($val['EmpOnService']['trigger_type']=='partner_id_req'){ echo "Partner Id";}else if($val['EmpOnService']['trigger_type']=='bgv'){ echo "BGV";}else if($val['EmpOnService']['trigger_type'] == 'ad_id'){ echo "Ad Id";}else{ echo "Email Id";};?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['emp_code'];?></td>
                            <td style="text-align: center;"><?php echo $val['emp_name'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['to'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['cc'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['mail_attempt'];?></td>
                            <td style="text-align: center;"><?php echo $val['EmpOnService']['last_mail_status'];?></td>
                            <td style="text-align: center;"><?php if(!empty($val['EmpOnService']['last_mail_time'])){echo date('d M y H:i:s',strtotime($val['EmpOnService']['last_mail_time']));}?></td>
                            <td style="text-align: center;">
                            <?php if($val['EmpOnService']['ticket_status'] == '1'){?>
                                <!-- <a href="<?php //echo $this->webroot;?>Tickets/close_ticket?id=<?php //echo $val['EmpOnService']['id'];?>">Close</a> -->
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $val['EmpOnService']['id'];?>','<?php echo $val['EmpOnService']['type']; ?>','<?php echo $val['EmpOnService']['trigger_type'] ?>')"> <label class="btn btn-xs btn-midnightblue btn-raised">Close<div class="ripple-container"></div></label></a>
                            <?php }else{?>
                                <h5>Already Close</h5>
                            <?php }?>
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
            <?php echo $this->Form->create('Tickets',array('id'=>'form_file','enctype'=>"multipart/form-data","class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                             <div class="row"> 
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Log</label>
                                        <div class="col-sm-6">
                                        <?php echo $this->Form->input('close_id',array('label'=>false,'type'=>'hidden','class'=>'form-control','id'=>'close_id' ));?>
                                        <?php echo $this->Form->input('log',array('label'=>false,'type'=>'textarea','rows'=>'5','class'=>'form-control','id'=>'log' ));?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12" id="ad_id_show"></div>
                                <div class="col-md-12" id="email_show"></div>
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Attach File</label>
                                        <div class="col-sm-6">
                                        <?php echo $this->Form->input('file',array('label'=>false,'type'=>'file','class'=>'form-control','id'=>'log_file','required'=>true));?>
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
                    <input type="submit" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Tickets/close_ticket1')"  value="Submit" class="btn-web btn">
                    <!-- <input type="submit"   value="Submit" class="btn-web btn"> -->
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>






