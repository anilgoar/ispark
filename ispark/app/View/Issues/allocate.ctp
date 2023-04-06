<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $(".callbackdate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
$(function () {
    $("#callbackdate2").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});
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
                    <i class="fa fa-search"></i>
                    <span>View Issue Details</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <h1><?php echo $this->Session->flash(); ?></h1>
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" >
                    <tr align="center">
                        <td>Ticket No</td>
                        <td>Branch</td>
                        <td>Process Name</td>
                        <td>Ticket Desc</td>
                        <td>Allocate Date</td>
                        <td>Status</td>
                    </tr>
                                                
                    <tr align="center">
                        <td><?php echo $data['IssueTracker']['id'];?></td>
                        <td><?php echo $data['IssueTracker']['branch_name'];  ?></td>
                        <td><?php echo $data['IssueTracker']['process_name']; ?></td>
                        <td><?php echo $data['IssueTracker']['ticket_desc'];?></td>
                        <td><?php echo $data['IssueTracker']['createdate']; ?></td>
			<td>
                        <?php 
                        if($data['IssueTracker']['issue_status']=='0') echo "Open";
                        if($data['IssueTracker']['issue_status']=='1') echo "Hold";
                        if($data['IssueTracker']['issue_status']=='2') echo "In - Progress";
                        if($data['IssueTracker']['issue_status']=='5') echo "Reject";
                        if($data['IssueTracker']['issue_status']=='6') echo "Allocated";
                        //if($data['IssueTracker']['issue_status']==0){echo "open";}
                        //else if($data['IssueTracker']['issue_status']==1) {echo "Reopen";}
                        ?>
                        </td>				
                    </tr>
		</table>
                
                <table class="table-striped table-bordered table-hover table-heading no-border-bottom" style="width:100%;" >
                    <tr align="center">
                        <td>SrNo</td>
                        <td>Priority</td>
                        <td>Requirement</td>
                        <td>Description</td>
                        <td>Remarks</td>
                        <td>File</td>
                        <td>Status</td>
                        <td>Status Remarks</td>
                    </tr>
                    <?php
                    $i = 1; 
                    foreach($particular as $post): 
                    echo $this->Form->create('Issues',array('action'=>'alloted','onsubmit'=>'return ValidateForm('.$post['IssueParticular']['id'].')'));
                    ?>
                    <tr align="center" >    
                        <td><?php echo $i++;?></td>
                        <td><?php if($post['IssueParticular']['priority']==0){echo "Low";}else if($post['IssueParticular']['priority']==1){echo "Normal";}else {echo "Urgent";} ?></td>  
                        <td><?php if($post['IssueParticular']['requirment_type']=='0'){echo "Upgrade";} else if($post['IssueParticular']['requirment_type']=='1'){echo "New";}else if($post['IssueParticular']['requirment_type']=='2'){echo "Modification";}else{echo "Error";}?></td>
                        <td><?php echo $post['IssueParticular']['requirement_desc'];?></td>
                        <td><?php echo $post['IssueParticular']['remarks'];?></td>
                        <td>
                        <?php 
                        $files=explode(',',$post['IssueParticular']['attach_files']);
                        if(isset($files)){
                            foreach($files as $links) : 
                            ?>
                            &nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>"><?php echo $links; ?> </a>
                            <?php	 
                            endforeach;
                        }
                        ?>
                        </td>  
                        
                        <td>
                        <?php 
                        if($post['IssueParticular']['issue_status']=='3') { 
                            echo $this->Form->input('Issue.'.$post['IssueParticular']['id'].'.issue_status',array('label'=>false,'options'=>array('0'=>'Re-open'),'empty'=>"Select Status",'class' => 'form-control'));
                            
                        } 
			else {
                            if($post['IssueParticular']['issue_status']=='0') echo "Open";
                            if($post['IssueParticular']['issue_status']=='1') echo "Hold";
                            if($post['IssueParticular']['issue_status']=='2') echo "In - Progress";
                            if($post['IssueParticular']['issue_status']=='5') echo "Reject";
                            if($post['IssueParticular']['issue_status']=='6') echo "Allocated";
                                                                                        
                        } ?></td>
                        
                        <td><?php if($post['IssueParticular']['issue_status']=='1' || $post['IssueParticular']['issue_status']=='5') { echo $post['IssueParticular']['AllocateRemarks'];}?> </td>
                        
                    </tr> 
                    <tr>
                        <td colspan="8"> 
                            <div class="form-group has-success has-feedback"> 
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('AllocateStatus'.$post['IssueParticular']['id'],array('label'=>false,'options'=>array('Submit'=>'Allocate','Hold'=>'Hold','Reject'=>'Reject'),'empty'=>'Status','id'=>'AllocateStatus'.$post['IssueParticular']['id'],'onchange'=>"AllocateStatus(this.value,'".$post['IssueParticular']['id']."')",'required'=>true,'class'=>'form-control'));?>
                                    <?php echo $this->Form->input('AllocateRemarks'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Remarks','id'=>'AllocateRemarks'.$post['IssueParticular']['id'],'style'=>'display:none;width:476px;height:100px;','type'=>'textarea')); ?>
                                
                                </div>
                                
                                <!--
                                <div class="col-sm-2" style="display:none" id="AllocRemarks<?php //echo $post['IssueParticular']['id'];?>" >
                                    <?php //echo $this->Form->input('AllocateRemarks'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Remarks','id'=>'AllocateRemarks'.$post['IssueParticular']['id'],'style'=>'display:none')); ?>
                                </div>
                                -->
                                
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('process_type'.$post['IssueParticular']['id'] ,array('label' =>false,'options'=>array('Inbound'=>'Inbound','Outbound'=>'Outbound','InHouse'=>'InHouse','Blended'=>'Blended'),'empty'=>'Process','id'=>'process_type'.$post['IssueParticular']['id'],'class' => 'form-control')); ?>
                                </div>
                                
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('ToDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Start Date','id'=>'ToDate'.$post['IssueParticular']['id'],'class'=>'callbackdate','readonly'=>"")); ?>
                                </div>
                                
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('FromDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Close Date','id'=>'FromDate'.$post['IssueParticular']['id'],'class'=>'callbackdate','readonly'=>"")); ?>
                                </div>
                                
                                <div class="col-sm-2">
                                   <?php  
                                   
                        $arr=array();
			$key = array_keys($ITUsers);
							  
                        for($i=0;$i<count($key);$i++){
                            $arr[base64_encode($post['IssueParticular']['id'].','.$key[$i].",".$post['IssueParticular']['ticket_no'])]=$ITUsers[$key[$i]];   
                        }
                        echo $this->Form->input('user',array('label'=>false,'options'=>$arr,'id'=>'user'.$post['IssueParticular']['id'],'empty'=>'Executive','class'=>'form-control','onClick'=>''));
                        ?>
                                
                        <input type="hidden" name="IssuetrackerId" value="<?php echo $post['IssueParticular']['id'].','.$data['IssueTracker']['id'];?>" >        
                        </div>
                           
                            
                            <div class="col-sm-2">
                                    <?php echo $this->Form->input('TicketCategory'.$post['IssueParticular']['id'],array('label'=>false,'options'=>array('Hardware'=>'Hardware','Software'=>'Software','Blended'=>'Blended','Firmware'=>'Firmware','Network'=>'Network'),'empty'=>'Category','id'=>'TicketCategory'.$post['IssueParticular']['id'],'class'=>'form-control'));?>
                                </div>
                            
           
                            
                            
                            <div class="col-sm-1 pull-right">
                                <input type="submit"  value="Submit" class=" btn-primary" style="margin-bottom:5px;" />
                            </div>
                                 </div>
                        </td>
                    </tr>
                    
                    
                    <?php echo $this->Form->end();?>
                    <?php  endforeach; ?>
                
                    <tr>
                        <td colspan="8">                   
                            <div class="form-group has-success has-feedback">  
                                <div class="col-sm-1 pull-right">
                                    <?php echo $this->Html->link('Back',array('action'=>'issue_allocate'),array('class'=>'btn btn-primary')); ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
function ValidateForm(id){
    $("#msgerror").remove();
    if($("#AllocateStatus"+id).val() =="Hold" || $("#AllocateStatus"+id).val() =="Reject"){
        
        if($("#AllocateRemarks"+id).val() ==""){
            $("#AllocateRemarks"+id).after('<span id="msgerror" style="color:red;" >Select remarks</span>');
            return false;
        }
        else{
            return true;
        }
    }
    else{
        if($("#process_type"+id).val() ==""){
            $("#process_type"+id).after('<span id="msgerror" style="color:red;" >Select process</span>');
            return false;
        }
        else if($("#ToDate"+id).val() ==""){
            $("#ToDate"+id).after('<span id="msgerror" style="color:red;" >Select start date</span>');
            return false;
        }
        else if($("#FromDate"+id).val() ==""){
            $("#FromDate"+id).after('<span id="msgerror" style="color:red;" >Select close date</span>');
            return false;
        }
        else if($("#user"+id).val() ==""){
            $("#user"+id).after('<span id="msgerror" style="color:red;" >Select executive</span>');
            return false;
        }
        else if($("#TicketCategory"+id).val() ==""){
            $("#TicketCategory"+id).after('<span id="msgerror" style="color:red;" >Select category</span>');
            return false;
        }
        else{
            return true;
        }
    }
}

function AllocateStatus(Status,id){
    if(Status =="Hold" || Status =="Reject"){
        $("#AllocRemarks"+id).show();
        $("#AllocateRemarks"+id).show();
    }
    else{
        $("#AllocRemarks"+id).hide();
        $("#AllocateRemarks"+id).hide(); 
    }
}
</script>
<?php echo $this->Js->writeBuffer();?>	