<?php //print_r($data);?>
<script>
function viewData(){
    var	branchid    =   document.getElementById('AddBranch').value;
    if(branchid ===""){
        $("#AddBranch").focus();
        alert('Select branch');
        return false;
    }
    else{
        var     process     =   document.getElementById('IssuesProcessName').value;			
        var	status      =   document.getElementById('AddIssueStatus').value;
        var	fdate       =   document.getElementById('AddFirstDate').value;
        var	ldate       =   document.getElementById('AddLastDate').value;
        var     AddHandleBy =   document.getElementById('AddHandleBy').value;
        var     AddSubmitBy =   document.getElementById('AddSubmitBy').value;
        
        $.ajax({
            type:'post',
            url:'show_issues_reports',
            data:{branch_name:branchid,process_name:process,status:status,fdate:fdate,ldate:ldate,HandleBy:AddHandleBy,SubmitBy:AddSubmitBy},
            success : function(data){
                $("#nn").html(data);
            }
        });
    }
}

function exportData(){
    var	branchid    =   document.getElementById('AddBranch').value;
    if(branchid ===""){
        $("#AddBranch").focus();
        alert('Select branch');
        return false;
    }
    else{
        var     process     =   document.getElementById('IssuesProcessName').value;			
        var	status      =   document.getElementById('AddIssueStatus').value;
        var	fdate       =   document.getElementById('AddFirstDate').value;
        var	ldate       =   document.getElementById('AddLastDate').value;
        var     AddHandleBy =   document.getElementById('AddHandleBy').value;
        var     AddSubmitBy =   document.getElementById('AddSubmitBy').value;
        
        var url='export_issues_reports/?branch_name='+branchid+'&process_name='+process+'&status='+status+'&fdate='+fdate+'&ldate='+ldate+'&HandleBy='+AddHandleBy+'&+SubmitBy='+AddSubmitBy;
        window.location.href = url;
    }
}
</script>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>
<div class="box-content">
	<?php $this->Form->create('Add',array('controller'=>'IssueReports','action'=>'view')); ?>
	<div class="form-group has-success has-feedback">
            	<label class="col-sm-1 control-label"><?php echo $this->Form->label('Branch');?></label>
                <div class="col-sm-2">
                    <?php
                            echo $this->Form->input('branch',array('onChange'=>'get_process23(this.value)','label'=>false,'options'=>$branch_master,'empty'=>'Select Branch','class'=>'form-control')); 
                    ?>
               </div>
                 
                <label class="col-sm-1 control-label">Process</label>
                <div class="col-sm-2">
                <div id="mm">&nbsp;</div>
                </div>
       
       			<div>
            	<label class="col-sm-1 control-label"><?php echo $this->Form->label('To');?></label>
                <div class="col-sm-2">
					<?php echo $this->Form->input('first_date', 
						array('label'=>false,'class'=>'form-control','placeholder'=>'Start Date','onClick'=>"displayDatePicker('data[Add][first_date]');"));?>
                 </div>
                 </div>
                <label class="col-sm-1 control-label"><?php echo $this->Form->label('From');?></label>
                <div class="col-sm-2">
                <?php echo $this->Form->input('last_date', 
						array('label'=>false,'class'=>'form-control','placeholder'=>'End Date','onchange'=>'validate_date()','onClick'=>"displayDatePicker('data[Add][last_date]');"));?>
                </div>
        </div>
     	
        <div class="form-group has-success has-feedback">
     
     	
               <label class="col-sm-1 control-label"><?php echo $this->Form->label('Status');?></label>
                   
                <div class="col-sm-2">
					<?php	
					echo $this->Form->input('issue_status', array('label'=>false,
					'options'=>array('All'=>'All','0'=>'Open','1'=>'On-hold','2'=>'In-progress','3'=>'Close','5'=>'Reject'),'class'=>'form-control')); 
					?>
                 </div>
                <label class="col-sm-1 control-label"><?php echo $this->Form->label('Handle&nbsp;By');?></label>
                <div class="col-sm-2">
                <?php echo $this->Form->input('handle_by',array('label'=>false,'options'=>$user_master,'class'=>'form-control'));?>
                </div>
                 <label class="col-sm-1 control-label"><?php echo $this->Form->label('Create&nbsp;By');?></label>
                <div class="col-sm-2">
                <?php echo $this->Form->input('submit_by',array('label'=>false,'options'=>$output,'empty'=>'Select User','class'=>'form-control'));?>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-info btn-label-left pull-right" onClick="exportData();" style="margin-left: 5px;" >Export</button>
                    <button class="btn btn-info btn-label-left pull-right" value = "show" onClick="viewData();">Show</button>

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
                    <div class="box-content no-padding">
                        <div id = "nn"></div>
                    </div>
            
			<div class="box-content">
				
				
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                  
</div>