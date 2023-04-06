<?php echo $this->Form->create('CostCenterMaster',array('class'=>'form-horizontal','action'=>'add','onsubmit'=>"return val_cs()")); ?>
<script>
    $(document).ready(function()
    {
        $("#GSTType").on('click',function()
        {
            var company_name = $('#CostCenterMasterCompanyName').val();
            var branch = $('#CostCenterMasterBranch').val();
            $.post("CostCenterMasters/get_GST_Type",
            {
                company_name:company_name,
                branch : branch,
                type : 'Integrated'
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#CostCenterMasterServiceTaxNo").empty();
                $("#CostCenterMasterServiceTaxNo").html(text);
            });  
     });
     $("#GSTType1").on('click',function()
        {
            $("#CostCenterMasterServiceTaxNo").empty();
            var company_name = $('#CostCenterMasterCompanyName').val();
            
            $.post("CostCenterMasters/get_GST_Type",
            {
                company_name:company_name,
                type : 'Intrastate'
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#CostCenterMasterServiceTaxNo").empty();
                $("#CostCenterMasterServiceTaxNo").html(text);
            });  
        });
        
     $("#CostCenterMasterCompanyName").on('change',function()
        {
            $("#CostCenterMasterServiceTaxNo").empty();
        });
        
      $("#CostCenterMasterBranch").on('change',function()
        {
            $("#CostCenterMasterServiceTaxNo").empty();
        });  
        
    });
    
    function val_cs()
    {
        var Revenue = false;
        try{
            Revenue = document.querySelector('input[name = "data[CostCenterMaster][Revenue]"]:checked').value;
        }
        catch(err)
        {
            Revenue = false;
        }
        
        var Billing = false;
        try{
            Billing = document.querySelector('input[name = "data[CostCenterMaster][Billing]"]:checked').value;
        }
        catch(err)
        {
            Billing = false;
        }
        
        if(Revenue==false && Billing==false)
        {
            alert("Please Select Billing Type");
            return false;
        }
        return true;
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
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Process Details</span>
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
			<?php echo $this->Session->flash(); ?>
				<h4 class="page-header">Process Details </h4>

					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Company Name</label>
						<div class="col-sm-4">
						<?php $data=array(); foreach ($company_master as $post): ?>
						<?php $data[$post['Addcompany']['company_name']]= $post['Addcompany']['company_name']; ?>
						<?php endforeach; ?><?php unset($Addcompany); ?>
							<?php	echo $this->Form->input('company_name', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Company')); ?>
							</div>
                                                <label class="col-sm-2 control-label"><input type="checkbox" name="data[CostCenterMaster][Revenue]" id="Revenue" value="1" checked="" />Revenue</label>
                                                <label class="col-sm-1 control-label"><input type="checkbox" name="data[CostCenterMaster][Billing]" id="Billing" value="1" />Billing</label>
						</div>						
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($branch_master as $post): ?>
						<?php $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; ?>
						<?php endforeach; ?><?php unset($Addbranch); ?>
							<?php	echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Branch','onChange'=>'getClient(this)')); ?>
						</div>

						<label class="col-sm-2 control-label">Stream</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($process_master as $post): ?>
						<?php $data[$post['Addprocess']['id']]= $post['Addprocess']['stream']; ?>
						<?php endforeach; ?><?php unset($Addprocess); ?>
							<?php	echo $this->Form->input('stream',array('label'=>false,'options'=>$data,'empty'=>'Select Stream','class'=>'form-control','onChange'=>'getStream(this)')); ?>
						</div>

						<label class="col-sm-1 control-label">Process</label>
						<div class="col-sm-2">
							<div id='process'><?php	echo $this->Form->input('process', array('label'=>false,'class'=>'form-control','options'=>'','empty' => 'Select Process')); ?></div>
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($category_master as $post): ?>
						<?php $data[$post['Category']['category']]= $post['Category']['category']; ?>
						<?php endforeach; ?><?php unset($Category); ?>
							<?php	echo $this->Form->input('category', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select category')); ?>
							
						</div>
						<label class="col-sm-2 control-label">Type</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($type_master as $post): ?>
						<?php $data[$post['Type']['type']]= $post['Type']['type']; ?>
						<?php endforeach; ?><?php unset($Type); ?>
							<?php	echo $this->Form->input('type', array('label'=>false,'class'=>'form-control','options' =>$data,'empty' => 'Select Type')); ?>



						</div>
						<label class="col-sm-1 control-label">Client</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[$post['Addclient']['client_name']]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
						<div id='client'><?php	echo $this->Form->input('client', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Client','required'=>true)); ?></div>
							
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
							<label class="col-sm-2 control-label">Total Man Date</label>
							<div class="col-sm-2">

							<?php	echo $this->Form->input('total_man_date', array('label'=>false,'class'=>'form-control','placeholder' => 'Total Man Date','required'=>true)); ?>
							</div>
						<label class="col-sm-2 control-label">Shrinkage</label>
							<div class="col-sm-2">

							<?php	echo $this->Form->input('shrinkage', array('label'=>false,'class'=>'form-control','placeholder' => 'Shrinkage','required'=>true)); ?>
							</div>
						<label class="col-sm-1 control-label">Attrition</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('attrition', array('label'=>false,'class'=>'form-control','placeholder' => 'Attrition','required'=>true)); ?>
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Shift</label>
						<div class="col-sm-2">
						<?php $data=array();foreach ($client_master as $post): ?>
						<?php  $data[]= $post['Addclient']['client_name']; ?>
						<?php endforeach; ?><?php unset($Addclient); ?>
							<?php	echo $this->Form->input('shift', array('label'=>false,'class'=>'form-control','options' => array('1'=>1,'2'=>2,'3'=>3),'empty' => 'Select Shift')); ?>
							
						</div>
						<label class="col-sm-2 control-label">Working Days</label>
						<div class="col-sm-2">

							<?php	echo $this->Form->input('working_days', array('label'=>false,'class'=>'form-control','options' => array('6'=>6,'7'=>7),'empty' => 'Working Days')); ?>
						</div>
						<label class="col-sm-1 control-label">Target ManDate</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('target_mandate', array('label'=>false,'class'=>'form-control','placeholder' => 'Target Mandate')); ?>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Over SalDays</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('over_saldays', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'required'=>true)); ?>
							
						</div>
						<label class="col-sm-2 control-label">Training Days</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('training_days', array('label'=>false,'class'=>'form-control','placeholder' => 'Training Days','required'=>true)); ?>
						</div>
						<label class="col-sm-1 control-label">Incentive Allowed</label>
						<div class="col-sm-2">

							<?php	echo $this->Form->input('incentive_allowed', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'required'=>true)); ?>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Training Attrition</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('training_attrition', array('label'=>false,'class'=>'form-control','placeholder' => 'Training Attrition')); ?>
						</div>
						<label class="col-sm-2 control-label">Deduction Allowed</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('deduction_allowed', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'required'=>true)); ?>
						</div>
						<label class="col-sm-1 control-label">Description</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('description', array('label'=>false,'class'=>'form-control','placeholder' => 'Description')); ?>
						</div>
					</div>
					
					<div class="form-group has-success has-feedback">
						<label class="col-sm-4 control-label">Tally Client For This Cost Center</label>
						<div class="col-sm-4">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
						<?php	echo $this->Form->input('client_tally_name', array('label'=>false,'class'=>'form-control','placeholder' => 'Client Tally Name For Cost Center','required'=>true)); ?>
						</div>
					</div>
					
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Process Manager</label>
						<div class="col-sm-4">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
						<?php	echo $this->Form->input('process_manager', array('label'=>false,'class'=>'form-control','placeholder' => 'Process Manager')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Email ID</label>
						<div class="col-sm-4">

						<?php	echo $this->Form->input('emailid', array('label'=>false,'class'=>'form-control','placeholder' => 'Email Id')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">HR Emails</label>
						<div class="col-sm-4">

						<?php	echo $this->Form->input('hremail', array('label'=>false,'class'=>'form-control','placeholder' => 'HR Emails')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Contact No</label>
						<div class="col-sm-4">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
						<?php	echo $this->Form->input('contact_no', array('label'=>false,'class'=>'form-control','placeholder' => 'Contact No')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Process Name</label>
						<div class="col-sm-4">

						<?php	echo $this->Form->input('process_name', array('label'=>false,'class'=>'form-control','placeholder' => 'Process Name')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tower</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('tower', array('label'=>false,'class'=>'form-control','placeholder' => 'Tower')); ?>
						</div>
					</div>
                                     <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">GST Type</label>
                                            <div class="col-sm-4">
                                                <input type="radio" name="data[CostCenterMaster][GSTType]" id="GSTType1" value="Integrated"  />InterState(IGST)
                                                <input type="radio" name ="data[CostCenterMaster][GSTType]" id="GSTType" value="Intrastate"  />Intrastate(CGST,SGST)
                                            </div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">GST No.</label>
                                            <div class="col-sm-4" id="serv_no">
                                            <?php echo $this->Form->input('ServiceTaxNo', array('label'=>false,'options'=>'','class'=>'form-control','empty' => 'GST No.','required'=>true)); ?>
                                            </div>
					</div> 
                                <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">HSN Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('HSNCode', array('label'=>false,'value'=>'','class'=>'form-control','placeholder' => 'HSNCode','required'=>true)); ?>
                                            </div>
					</div> 
                                <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">SAC Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('SACCode', array('label'=>false,'value'=>'','class'=>'form-control','placeholder' => 'SACCode','required'=>true)); ?>
                                            </div>
					</div> 
                                <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">Vendor GST State</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('VendorGSTState', array('label'=>false,'class'=>'form-control','placeholder' => 'State Name','value'=>$cost['VendorGSTState'],'required'=>true)); ?>
                                            </div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">Vendor State Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('VendorStateCode', array('label'=>false,'class'=>'form-control','placeholder' => 'State Code','value'=>$cost['VendorStateCode'],'required'=>true)); ?>
                                            </div>
					</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Commercial Details</span>
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
				<h4 class="page-header">Commercial Details </h4>

					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Revenue Type</label>
						<div class="col-sm-2">
						<?php	echo $this->Form->input('revenueType', array('label'=>false,'class'=>'form-control','options' => array('Fixed'=>'Fixed','Variable'=>'Variable','Both'=>'Both'),'empty' => 'Select Revenue','onChange'=>'getRevenueValidation(this.value)')); ?>
						</div>
                                                <label class="col-sm-2 control-label">Fixed</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('fixed', array('label'=>false,'class'=>'form-control','options' => array('Seat'=>'Seat','Fos'=>'Fos','Manpower'=>'Manpower','Seat&Fos'=>'Seat & Fos'),'empty' => 'Select Fixed','onChange'=>'getFixed(this.value)','disabled'=>true)); ?>
						</div>

						<label class="col-sm-2 control-label">Variable Base</label>
						<div class="col-sm-2">						
						<?php	echo $this->Form->input('variableBase',array('label'=>false,'options'=>array('Hourly'=>'Hourly','Minute'=>'Minute','Case'=>'Case','Contact'=>'Contact'),'empty'=>'Select Variable','class'=>'form-control','onChange'=>'getFixed(this.value)','disabled'=>true)); ?>
						</div>
					</div>						
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Agreement Req.</label>
						<div class="col-sm-2">
							<div id='process'><?php	echo $this->Form->input('agreementReq', array('label'=>false,'class'=>'form-control','options'=>array(1=>'Yes',0=>'No'),'empty' => 'Select')); ?></div>
						</div>
						<label class="col-sm-2 control-label">Payment Mode</label>
						<div class="col-sm-2">
                                                    <?php echo $this->Form->input('paymentMode', array('label'=>false,'class'=>'form-control','options' => array('Cheque'=>'Cheque','RTGS'=>'RTGS','Talk Time Trnsf.'=>'Talk Time Trnsf.'),'empty' => 'Select')); ?>
						</div>
						<label class="col-sm-2 control-label">Payment Terms</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('paymentTerms', array('label'=>false,'class'=>'form-control','options' =>array('30'=>'30 Days','60'=>'60 Days','90'=>'90 Days','120'=>'120 Days'),'empty' => 'Select')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Association Date</label>
						<div class="col-sm-2">
							<div id='process'><?php	echo $this->Form->input('AssociationDate', array('label'=>false,'type'=>'text','class'=>'form-control date-picker','PlaceHolder' => 'Association Date')); ?></div>
						</div>
						<label class="col-sm-2 control-label">Go Live Date</label>
						<div class="col-sm-2">
                                                    <?php echo $this->Form->input('goLiveDate', array('label'=>false,'type'=>'text','class'=>'form-control date-picker','PlaceHolder' => 'goLiveDate')); ?>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Commercial Details</span>
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
				<h4 class="page-header">Particulars Details </h4>
                                <div id="Seat"></div>
                                <div id="SeatDetails"></div>
				<div id="Fos"></div>
                                <div id="FosDetails"></div>
                                <div id="variable"></div>
                                <div id="variableDetails"></div>
                                <div id="SeatCount"></div>
                                <div id="FosCount"></div>
                                <div id="variableCount"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Client Details</span>
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
				<h4 class="page-header">Client Details </h4>
					<div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">Level</label>
						<label class="col-sm-2 control-label" style="margin-left:-40px;">User Name</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Designation</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Contact No.</label>
						<label class="col-sm-2 control-label" style="margin-left:-5px;">Email Id</label>
						<label class="col-sm-2 control-label" style="margin-left:-10px;">Address</label>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">User Level 1</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserName1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'UserName1')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 1')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">User Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserName2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'UserName2')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 2')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                             <label class="col-sm-2 control-label" style="margin-left:-40px;">User Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserName3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'UserName3')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 3')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
                                   <div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">Level</label>
						<label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Name</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Designation</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Contact No.</label>
						<label class="col-sm-2 control-label" style="margin-left:-5px;">Email Id</label>
						<label class="col-sm-2 control-label" style="margin-left:-10px;">Address</label>
					</div>
                                        <div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Level 1</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMName1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'SCM1')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 1')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMName2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'SCM2')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 2')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMName3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'SCM3')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 3')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">Level</label>
						<label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Name</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Designation</label>
                                                <label class="col-sm-2 control-label" style="margin-left:-5px;">Contact No.</label>
						<label class="col-sm-2 control-label" style="margin-left:-5px;">Email Id</label>
						<label class="col-sm-2 control-label" style="margin-left:-10px;">Address</label>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Level 1</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceName1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Finance Name')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 1')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress1', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceName2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Finance Name')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 2')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress2', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceName3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Finance Name')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Designation Level 3')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Contact No')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Email Id')); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress3', array('label'=>false,'class'=>'form-control','PlaceHolder' => 'Address')); ?>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>

<div class="row" >
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Billing Details</span>
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
				<h4 class="page-header">Billing Details</h4>
						
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">PO Required</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('po_required', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'))); ?>		
						</div>
						<label class="col-sm-2 control-label">JCC No</label>
						<div class="col-sm-2">

							<?php	echo $this->Form->input('jcc_no', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'))); ?>
						</div>
						<label class="col-sm-1 control-label">GRN</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('grn', array('label'=>false,'class'=>'form-control','options' => array('No'=>'No','Yes'=>'Yes'))); ?>
							
						</div>
						
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Bill To</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->textarea('bill_to', array('label'=>false,'class'=>'form-control','placeholder' => 'Bill To')); ?>
							<?php echo $this->Form->checkbox('as_client', array('hiddenField' => false)); ?><b class="bg-info">As Client</b>
						</div>

						<label class="col-sm-2 control-label">Ship To</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->textarea('ship_to', array('label'=>false,'class'=>'form-control','placeholder' => 'Ship To')); ?>
							<?php echo $this->Form->checkbox('as_bill_to', array('hiddenField' => false)); ?><b class="bg-info">As BillTo</b>
						</div>
					</div>
					
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 1</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address1', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 1')); ?>
						</div>

						<label class="col-sm-2 control-label">Address 1</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address1', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 1')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 2</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address2', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 2')); ?>
						</div>

						<label class="col-sm-2 control-label">Address 2</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address2', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 2')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 3</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address3', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 3')); ?>
						</div>

						<label class="col-sm-2 control-label">Address 3</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address3', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 3')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 4</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address4', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 4')); ?>
						</div>

						<label class="col-sm-2 control-label">Address 4</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address4', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 4')); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 5</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address5', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 5')); ?>
						</div>

						<label class="col-sm-2 control-label">Address 5</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address5', array('label'=>false,'class'=>'form-control','placeholder' => 'Address 5')); ?>
						</div>
					</div>
                                    
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">Client GST No.</label>
                                            <div class="col-sm-4" id="serv_no">
                                            <?php echo $this->Form->input('VendorGSTNo', array('label'=>false,'value'=>'','class'=>'form-control','placeholder' => 'Client GST No.','required'=>true)); ?>
                                            </div>
					</div> 
                                        
                                
				<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Submit
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo $this->Form->end(); ?>
