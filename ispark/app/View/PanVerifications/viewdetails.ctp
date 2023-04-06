<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
    $(function () {
    $("#ResignationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function showdiv(id){
    $("#"+id).toggle();
}

function send_to_esignature(empcode,imagelink,imagename,rowid,type,createby){
	
	var aadhar_status = $("input[name='aadhar_status']:checked").val();
	
	if (confirm("Are you sure you want to sent document to employee?")) {
		$("#"+rowid).show();
		$.get("<?php echo $this->webroot;?>appointment/examples/esignature.php",{empcode:empcode,imagelink:imagelink,imagename:imagename,type:type,createby:createby,aadhar_status:aadhar_status}, function(data){
			$("#"+rowid).hide();
			alert(data);
			location.reload();
		});
    }	
}



function receive_to_esignature(empcode,id,did,imagename,rowid){
	if (confirm("Are you sure you want to receive document?")) {
		$("#"+rowid).show();
		$.post("<?php echo $this->webroot;?>appointment/examples/get_esignature.php",{empcode:empcode,id:id,did:did,imagename:imagename}, function(data){
			$("#"+rowid).hide();
			alert(data);
			location.reload();
		});
    }	
}

function receive_done(id,rowid){
	if (confirm("Are you sure you want to done?")) {
		$("#"+rowid).show();
		$.post("<?php echo $this->webroot;?>Esignatures/updatestatus",{id:id}, function(data){
			$("#"+rowid).hide();
			location.reload();
		});
    }	
}



</script>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 10px;
  height: 10px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
                    <span>Pan Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="expand-link"><i class="fa fa-expand"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
				<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
              
                <?php echo $this->Form->create('PanVerifications',array('action'=>'viewdetails','class'=>'form-horizontal'));?>
					
						<?php echo $this->Session->flash();?>
						<div class="form-group">
							<div class="col-sm-1 pull-left" >Pan No</div>
							<div class="col-sm-2 pull-left">
								<input type='text' name='PanNo' id='PanNo' value="<?php echo isset($data['PanNo'])?$data['PanNo']:''?>" class="form-control" autocomplete='off' required="" readonly="" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-1 pull-left" >
							<input type='hidden' name='EmpCode' value='<?php echo base64_decode($_REQUEST['EC']);?>' >
							</div>
							<div class="col-sm-4 pull-left">
								<input type="submit" name="Submit"  value="Submit" style="width:80px;"  >
								<input type="button" name="Submit"  value="Back" style="width:80px;" onclick='return window.location="<?php echo $this->webroot;?>PanVerifications"' >
							</div>
						</div>
						
							<?php if(!empty($row)){?>
				

				<table class = "table table-striped table-hover  responstable">     
                <thead>
                    <tr>
                        <th style='text-align:center;'>EmpCode</th>
                        <th style='text-align:center;'>PanNo</th>
                        <th style='text-align:center;'>Status</th>
                        <th style='text-align:center;'>First Name</th>
                        <th style='text-align:center;'>Middle Name</th>
                        <th style='text-align:center;'>Last Name</th>
                        <th style='text-align:center;'>Pan Holder Title</th>
                        <th style='text-align:center;'>Date</th>
                    </tr> 
                </thead>
                <tbody>
                    
                    <tr>
                        <td style='text-align:center;'><?php echo $row['emp_code']?></td>
						<td style='text-align:center;'><?php echo $row['search_data']?></td>
                        <td style='text-align:center;'><?php echo $row['search_status']?></td>
                        <td style='text-align:center;'><?php echo $row['first_name']?></td>
                        <td style='text-align:center;'><?php echo $row['middle_name']?></td>
                        <td style='text-align:center;'><?php echo $row['last_name']?></td>
                        <td style='text-align:center;'><?php echo $row['pan_holder_title']?></td>
                        <td style='text-align:center;'><?php echo $row['created_at']!=""?date("d-M-Y H:i:s"):""?></td> 
                    </tr>
                    
                </tbody>
            </table>
			
			<?php }?>
                   
                </div>
				
			
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



