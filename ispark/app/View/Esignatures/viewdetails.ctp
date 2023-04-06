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
                    <span>Document Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="expand-link"><i class="fa fa-expand"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
				<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                
                <?php echo $this->Form->create('Esignatures',array('action'=>'update_esignature_status','class'=>'form-horizontal'));?>
                <div class="form-group" style="margin-top:-10px;" >
                    <div class="col-sm-5">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
								<tr>
									<th style="text-align: center;" colspan="2" >Without E-Signature
									</th>
									<th style="text-align: center;" colspan="2" >
									Aadhaar <input type="radio" name="aadhar_status" value="Aadhar_Sig" checked style="margin=left:10px;" >
									Electronic <input type="radio" name="aadhar_status" value="Esig" >
									</th>
                                </tr>
								
                                <tr>
                                    <th style="text-align: center;width:30px;" >SNo</th>
                                    <th style="text-align: center;width:100px;">Document</th>
									<th style="text-align: center;width:100px;">Action</th>
									<th style="text-align: center;width:30px;" >&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>        
                                <?php $n=1; foreach ($DocArr as $val){
								$imgpath	=	$this->webroot.'Doc_File/'.$val['Masdocfile']['OfferNo'].'/'.$val['Masdocfile']['filename'];
								$name 		= 	str_replace(' ', '', $val['Masdocfile']['DocType']);
								?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['Masdocfile']['DocType'];?></td>
									<td style="text-align: center;">
										<a class="image-link" target="_blank" href="<?php echo $this->webroot;?>app/webroot/appointment/examples/active_view.php?empcode=<?php echo base64_decode($_REQUEST['EC']);?>&tp=masdoc&imagename=<?php echo $val['Masdocfile']['DocType'];?>&imagelink=<?php echo base64_decode($_REQUEST['ON']);?>"><input type="button" value="View" ></a>
										
										<input type="button" onclick="send_to_esignature('<?php echo base64_decode($_REQUEST['EC']);?>','<?php echo base64_decode($_REQUEST['ON']);?>','<?php echo $val['Masdocfile']['DocType'];?>','row<?php echo $n;?>','document','<?php echo $this->Session->read('userid');?>')"  value="Send">
									</td>
									<td style="text-align: center;"><center><p class="loader" style="display:none;"  id="row<?php echo $n;?>" ></p></center></td>
                                </tr>
                                <?php }?>
								
								
								<tr>
                                    <td style="text-align: center;"><?php echo $n;?></td>
                                    <td style="text-align: center;">AssetsAllotment</td>
									<td style="text-align: center;">
										<a class="image-link" target="_blank" href="<?php echo $this->webroot;?>app/webroot/appointment/examples/active_view.php?empcode=<?php echo base64_decode($_REQUEST['EC']);?>&tp=assets"><input type="button" value="View" ></a>
										<input type="button" onclick="send_to_esignature('<?php echo base64_decode($_REQUEST['EC']);?>','','AssetsAllotment','row<?php echo $n;?>','assets','<?php echo $this->Session->read('userid');?>')"  value="Send">
									</td>
									<td style="text-align: center;"><center><p class="loader" style="display:none;"  id="row<?php echo $n;?>" ></p></center></td>
                                </tr>
								
								
								<tr>
                                    <td style="text-align: center;"><?php echo $n+1;?></td>
                                    <td style="text-align: center;">AppointmentLetter</td>
									<td style="text-align: center;">
										<a class="image-link" target="_blank" href="<?php echo $this->webroot;?>app/webroot/appointment/examples/active_view.php?empcode=<?php echo base64_decode($_REQUEST['EC']);?>&tp=appointment"><input type="button" value="View" ></a>
										<input type="button" onclick="send_to_esignature('<?php echo base64_decode($_REQUEST['EC']);?>','','AppointmentLetter','row<?php echo $n+1;?>','appointment','<?php echo $this->Session->read('userid');?>')"  value="Send">
									</td>
									<td style="text-align: center;"><center><p class="loader" style="display:none;"  id="row<?php echo $n+1;?>" ></p></center></td>
                                </tr>
								
								
                            </tbody>   
                        </table>
                    </div>
					
					<div class="col-sm-7">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
								<tr>
									<th style="text-align: center;" colspan="6" >With E-Signature</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;width:30px;" >SNo</th>
                                    <th style="text-align: center;width:100px;">Document</th>
									<th style="text-align: center;width:100px;">Date</th>
									<th style="text-align: center;width:100px;">Status</th>
									<th style="text-align: center;width:200px;">Action</th>
									<th style="text-align: center;width:30px;" >&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($data as $val){
								?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['Esignature_Document_Master']['DocName'];?></td>
									<td style="text-align: center;"><?php echo date("d-M-Y",strtotime($val['Esignature_Document_Master']['CreateDate']));?></td>
									<td style="text-align: center;"><?php echo $val['Esignature_Document_Master']['EsignatureStatus'];?></td>
									<td style="text-align: center;">
										
										<input type="button" onclick="receive_to_esignature('<?php echo $val['Esignature_Document_Master']['EmpCode'];?>','<?php echo $val['Esignature_Document_Master']['Id'];?>','<?php echo $val['Esignature_Document_Master']['DocId'];?>','<?php echo $val['Esignature_Document_Master']['DocName'];?>','receive<?php echo $n;?>')"  value="Receive">
										<?php if($val['Esignature_Document_Master']['Receive'] =="Yes"){?>
											<a class="image-link" href="<?php echo $this->webroot;?>appointment/examples/download_esignature.php?file=<?php echo $val['Esignature_Document_Master']['EsignaturePath'];?>"><input type="button" value="View" ></a>
										<?php }?>
										<input type="button" onclick="receive_done('<?php echo $val['Esignature_Document_Master']['Id'];?>','receive<?php echo $n;?>')"  value="Done">
									</td>
									<td style="text-align: center;"><center><p class="loader" style="display:none;"  id="receive<?php echo $n;?>" ></p></center></td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
						
						<?php echo $this->Session->flash();?>
						<div class="form-group">
							<div class="col-sm-6 pull-left" >E-Signature Validate Status</div>
							<div class="col-sm-6 pull-left">
								<select id="EsignatureValidateStatus" name='EsignatureValidateStatus' class="form-control" required >
									<option value=''>Select</option>
									<option <?php echo $EmpDetails['EsignatureValidateStatus'] =="Yes"?"selected='selected'":"";?> value='Yes'>Yes</option>
									<option <?php echo $EmpDetails['EsignatureValidateStatus'] =="No"?"selected='selected'":"";?> value='No'>No</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-6 pull-left" >E-Signature Validate Remarks</div>
							<div class="col-sm-6 pull-left">
								<textarea id="EsignatureValidateRemarks" name="EsignatureValidateRemarks" class="form-control" required ><?php echo $EmpDetails['EsignatureValidateRemarks'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-6 pull-left" >
							<input type='hidden' name='ON' value='<?php echo $_REQUEST['ON'];?>' >
							<input type='hidden' name='EC' value='<?php echo $_REQUEST['EC'];?>' >
							</div>
							<div class="col-sm-6 pull-left">
								<input type="submit" name="Submit"  value="Submit" style="width:80px;"  >
								<input type="button" name="Submit"  value="Back" style="width:80px;" onclick='return window.location="<?php echo $this->webroot;?>Esignatures"' >
							</div>
						</div>
						
                    </div>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



