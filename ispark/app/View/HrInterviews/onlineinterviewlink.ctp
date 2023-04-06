<?php
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
function validateForm(){ 
    $("#msgerr").remove();
    var BranchName  =   $("#BranchName").val();
    var FromDate    =   $("#FromDate").val();
    var ToDate      =   $("#ToDate").val();
    
    if(BranchName ===""){
        $("#BranchName").focus();
        $("#BranchName").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select branch name.</span>");
        return false;
    }
    else if(FromDate ===""){
        $("#FromDate").focus();
        $("#FromDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select from date.</span>");
        return false;
    }
    else if(ToDate ===""){
        $("#ToDate").focus();
        $("#ToDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select to date.</span>");
        return false;
    }
    else{
        /*
        $("#loder").show();
        if(Type ==="show"){
            $.post("<?php echo $this->webroot;?>HrVisitors/show_visitor",{BranchName:BranchName,EmpMonth:EmpMonth,EmpYear:EmpYear}, function(data) {
                $("#loder").hide();
                if(data !=""){
                    $("#report").html(data);
                }
                else{
                    $("#report").html('<div class="col-sm-12" style="color:red;font-weight:bold;" >Record not found.</div>');
                } 
            });
        }
        else if(Type ==="Export"){
            $("#loder").hide();
            window.location="<?php echo $this->webroot;?>HrVisitors/export_visitor?BranchName="+BranchName+"&EmpMonth="+EmpMonth+"&EmpYear="+EmpYear;  
           
        }
        */
    }
}    

function actionlist(path){
    if(confirm('Are you sure you want to delete this list?')){
            window.location=path;
    }
}
$(function () {
    $(".datepik").datepicker1({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'd-M-yy'
    });
});


function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
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
            <div class="box-header"  >
                <div class="box-name">
                    <span>Send Online Interview Link</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('HrInterviews',array('action'=>'send_online_interview_link','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
					
					<label class="col-sm-1 control-label">Upload&nbsp;File</label>
                    <div class="col-sm-3">
                        <input type="file" id="uploadFile" name="uploadFile" autocomplete="off"  class="form-control"  required="" >
						<span>Note - Upload only CSV file</span>
                    </div>
					
					<div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTI2"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
				
				
				<div class="box-header"  >
					<div class="box-name">
						<span>Manual Send Online Interview Link</span>
					</div>
				</div>
			
				
				<?php echo $this->Form->create('HrInterviews',array('action'=>'manual_send_online_interview_link','class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','required'=>true)); ?>
                    </div>
					
					<label class="col-sm-1 control-label">Mobile No</label>
                    <div class="col-sm-2">
                        <input type="text" name="uploadFile" autocomplete="off"  class="form-control" maxlength='10'  required="" onkeypress="return isNumberKey(event,this)" >
                    </div>
					
					<label class="col-sm-1 control-label">Email Id</label>
                    <div class="col-sm-3">
                        <input type="text" name="EmailId" autocomplete="off"  class="form-control" placeholder="Email id not required."   >
                    </div>
					
					
					<div class="col-sm-1">
                        <input type="submit" name="Submit" value="Submit" class="btn pull-right btn-primary btn-new" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
				
                
            </div>
        </div>
    </div>	
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Export Online Interview Link Status</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">

                <?php echo $this->Form->create('HrInterviews',array('action'=>'onlineinterviewlink','class'=>'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-2">
                        <input type="text" id="FromDate" name="FromDate" autocomplete="off" value="<?php echo isset($FromDate)?$FromDate:""?>" class="form-control datepik"  required="" >
                    </div>
                    
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <input type="text" id="ToDate" name="ToDate" autocomplete="off" value="<?php echo isset($ToDate)?$ToDate:""?>" class="form-control datepik"  required="" >
                    </div> 
                    
                    <div class="col-sm-3">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTI2"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit" name="Submit" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
                        <input type="submit" name="Submit" value="View" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                
                <?php if(!empty($data)){?>
                <div class="form-group">
                    <div class="col-sm-6 " id="trainerdata" >
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Sr.No</th>
									<th style='text-align:center;'>BranchName</th>
									<th style='text-align:center;'>Mobile No</th>
									<th style='text-align:center;'>Status</th>
									<th style='text-align:center;'>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($data as $row){?>
                                <tr>
                                    <td style='text-align:center;'><?php echo $i++;?></td>
									<td style='text-align:center;'><?php echo $row['Interview_Online_Link_Status']['Branch_Name'];?></td>
									<td style='text-align:center;'><?php echo $row['Interview_Online_Link_Status']['Mobile_No'];?></td>
									<td style='text-align:center;'><?php echo $row['Interview_Online_Link_Status']['Status'];?></td>
									<td style='text-align:center;'><?php echo $row['Interview_Online_Link_Status']['Create_Date']!=""?date("d-M-Y",strtotime($row['Interview_Online_Link_Status']['Create_Date'])):''?></td>		
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }else{?>
                <span>Record Not Found.</span>
                <?php }?>
            </div>
        </div>
    </div>	
</div>

