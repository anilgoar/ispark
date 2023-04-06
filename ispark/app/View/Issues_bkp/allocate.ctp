<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#callbackdate").datepicker1({
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
			<td><?php if($data['IssueTracker']['issue_status']==0){echo "open";}else if($data['IssueTracker']['issue_status']==1) {echo "Reopen";}?></td>				
                    </tr>
		</table>
                
                <table class="table-striped table-bordered table-hover table-heading no-border-bottom">
                    <tr align="center">
                        <td>SrNo</td>
                        <td>Priority</td>
                        <td>Requirement</td>
                        <td>Description</td>
                        <td>Process</td>
                        <td>Start Date</td>
                        <td>Close Date</td>
                        <td>Remarks</td>
                        <td>File</td>
                    </tr>
                    <?php
                    $i = 1; 
                    foreach($particular as $post): 
                    echo $this->Form->create('Issues',array('action'=>'alloted'));
                    ?>
                    <tr align="center" >    
                        <td><?php echo $i++;?></td>
                        <td><?php if($post['IssueParticular']['priority']==0){echo "Low";}else if($post['IssueParticular']['priority']==1){echo "Normal";}else {echo "Urgent";} ?></td>  
                        <td><?php if($post['IssueParticular']['requirment_type']=='0'){echo "Upgrade";} else if($post['IssueParticular']['requirment_type']=='1'){echo "New";}else if($post['IssueParticular']['requirment_type']=='2'){echo "Modification";}else{echo "Error";}?></td>
                        <td><?php echo $post['IssueParticular']['requirement_desc'];?></td>
                        <td><?php echo $this->Form->input('process_type'.$post['IssueParticular']['id'] ,array('label' =>false,'options'=>array('Inbound'=>'Inbound','Outbound'=>'Outbound'),'empty'=>'Select Type','class' => 'form-control')); ?></td>
                        <td><?php echo $this->Form->input('ToDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Date','id'=>'callbackdate','readonly'=>"")); ?></td>     
                        <td><?php echo $this->Form->input('FromDate'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Date','id'=>'callbackdate2','readonly'=>"")); ?></td>
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
                        
                        </td>
                    </tr> 
                    
                    <tr>
                        <td colspan="9">                   
                            <div class="form-group has-success has-feedback"> 
                                <label class="col-sm-1">Executive</label>   
                                <div class="col-sm-2">
                                   <?php  
                        $arr=array();
			$key = array_keys($ITUsers);
							  
                        for($i=0;$i<count($key);$i++){
                            $arr[base64_encode($post['IssueParticular']['id'].','.$key[$i].",".$post['IssueParticular']['ticket_no'])]=$ITUsers[$key[$i]];   
                        }
                        echo $this->Form->input('user',array('label'=>false,'options'=>$arr,'class'=>'form-control','onClick'=>''));
                        ?>
                                </div>
                                <label class="col-sm-1">Category</label>   
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('TicketCategory'.$post['IssueParticular']['id'],array('label'=>false,'options'=>array('H/W'=>'H/W','S/W'=>'S/W','Firmware'=>'Firmware','Network'=>'Network'),'empty'=>'Select','class'=>'form-control'));?>
                                </div>
                                
                                <label class="col-sm-1">Status</label>   
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('AllocateStatus'.$post['IssueParticular']['id'],array('label'=>false,'options'=>array('Submit'=>'Submit','Hold'=>'Hold'),'empty'=>'Select','onchange'=>"AllocateStatus(this.value,'".$post['IssueParticular']['id']."')",'class'=>'form-control'));?>
                                </div>
                                
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('AllocateRemarks'.$post['IssueParticular']['id'], array('label'=>false,'placeholder'=>'Remarks','id'=>'AllocateRemarks'.$post['IssueParticular']['id'],'style'=>'display:none')); ?>
                                </div>
                                <div class="col-sm-1">
                                    <input type="submit"  value="Submit" id="Submit<?php echo $post['IssueParticular']['id'];?>"  class=" btn-primary" style="width:60px;display: none;" />
                                    <input type="submit"  value="Hold" id="Hold<?php echo $post['IssueParticular']['id'];?>" class=" btn-primary" style="width:60px;display: none;"  />
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <?php echo $this->Form->end();?>
                    <?php  endforeach; ?>
                
                    <tr>
                        <td colspan="9">                   
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
function AllocateStatus(Status,id){
    $("#Hold"+id).hide();
    $("#Submit"+id).hide();
    $("#AllocateRemarks"+id).hide();
        
    if(Status =="Submit"){
        $("#Submit"+id).show();
        $("#Hold"+id).hide();
        $("#AllocateRemarks"+id).hide();
    }
    else{
        $("#AllocateRemarks"+id).show();
        $("#Hold"+id).show();
        $("#Submit"+id).hide();  
    }
}
</script>
            <?php 

			?>
            
		<?php echo $this->Js->writeBuffer(); ?>	