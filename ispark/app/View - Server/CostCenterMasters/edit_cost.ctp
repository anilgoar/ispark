<?php echo $this->Form->create('CostCenterMaster',array('class'=>'form-horizontal','action'=>'update_cost','onsubmit'=>"return val_cs()")); 
$cost = $cost_master['CostCenterMaster'];
//print_r($cost);

?>

<script>
    $(document).ready(function()
    {
        $("#GSTType").on('click',function()
        {
            var company_name = $('#CostCenterMasterCompanyName').val();
            var branch = $('#CostCenterMasterBranch').val();
            $.post("get_GST_Type",
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
            
            $.post("get_GST_Type",
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
		
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
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
							<?php	echo $this->Form->input('company_name', array('label'=>false,'class'=>'form-control','options' => $data,'value' => $cost['company_name'])); ?>
							</div>
                                                <label class="col-sm-2 control-label"><input type="checkbox" name="data[CostCenterMaster][Revenue]" id="Revenue" value="1" <?php if($cost['Revenue']=='1') { ?> checked="" <?php } ?> />Revenue</label>
                                                <label class="col-sm-1 control-label"><input type="checkbox" name="data[CostCenterMaster][Billing]" id="Billing" value="1" <?php if($cost['Billing']=='1') { ?>  checked="" <?php } ?> />Billing</label>
						</div>						
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-2">
						<?php $data=array();  foreach ($branch_master as $post): ?>
						<?php $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; ?>
						<?php endforeach; ?><?php unset($Addbranch); $OPBranch = $data; ?>
							<?php	echo $this->Form->input('branch', array('label'=>false,'class'=>'form-control','options' => $data,'value' => $cost['branch'],'onChange'=>'getClient(this)')); ?>
						</div>

						<label class="col-sm-2 control-label">Stream</label>
						<div class="col-sm-2">
						<?php $data=array(); 
								foreach ($process_master as $post): 
							 		$data[$post['Addprocess']['id']]= $post['Addprocess']['stream'];
						 		endforeach;  unset($Addprocess); 
								$flag = false;
								if($cost['stream']!=''){$flag = true;}
								?>
							<?php	echo $this->Form->input('stream',array('label'=>false,'options'=>$data,'empty'=>'Select Stream','value'=>$cost['stream'],'class'=>'form-control','onChange'=>'getStream(this)','required'=>false)); ?>
						</div>

						<label class="col-sm-1 control-label">Process</label>
						<div class="col-sm-2">
							<div id='process'><?php	echo $this->Form->input('process', array('label'=>false,'class'=>'form-control','options'=>array($cost['process']=>$cost['process']),'empty' => 'Select Process','value'=>$cost['process'])); ?></div>
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($category_master as $post): ?>
						<?php $data[$post['Category']['category']]= $post['Category']['category']; ?>
						<?php endforeach; ?><?php unset($Category); ?>
							<?php	echo $this->Form->input('category', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select category','value'=>$cost['category'],'required'=>true)); ?>
							
						</div>
						<label class="col-sm-2 control-label">Type</label>
						<div class="col-sm-2">
						<?php 
								$data=array(); 
								foreach ($type_master as $post): 
							 		$data[$post['Type']['type']]= $post['Type']['type']; 
								 endforeach; 
								 unset($Type); 
								 
								 $flag = false;
								 if($cost['type']!=''){$flag = true;}
						
						
						?>
							<?php	echo $this->Form->input('type', array('label'=>false,'class'=>'form-control','options' =>$data,'empty' => 'Select Type','value'=>$cost['type'],'required'=>true)); ?>


						</div>
						<label class="col-sm-1 control-label">Client</label>
						<div class="col-sm-2">
						<?php $data=array();						
								foreach ($client_master as $post): ?>
						<?php  $data[$post['Addclient']['client_name']]= $post['Addclient']['client_name']; ?>
						<?php endforeach; ?><?php unset($Addclient); ?>
						<div id='client'><?php	echo $this->Form->input('client', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Client','value'=>$cost['client'],'required'=>true)); ?></div>
							
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
							<label class="col-sm-2 control-label">Total Man Date</label>
							<div class="col-sm-2">

							<?php	echo $this->Form->input('total_man_date', array('label'=>false,'class'=>'form-control','value' => $cost['total_man_date'],'required'=>true)); ?>
							</div>
						<label class="col-sm-2 control-label">Shrinkage</label>
							<div class="col-sm-2">

							<?php	echo $this->Form->input('shrinkage', array('label'=>false,'class'=>'form-control','value'=>$cost['shrinkage'],'required'=>true)); ?>
							</div>
						<label class="col-sm-1 control-label">Attrition</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('attrition', array('label'=>false,'class'=>'form-control','value'=>$cost['attrition'],'required'=>true)); ?>
						</div>
						
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Shift</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('shift', array('label'=>false,'class'=>'form-control','options' => array('1'=>1,'2'=>2,'3'=>3),'empty' => 'Select Shift','value'=>$cost['shift'])); ?>
							
						</div>
						<label class="col-sm-2 control-label">Working Days</label>
						<div class="col-sm-2">

							<?php	echo $this->Form->input('working_days', array('label'=>false,'class'=>'form-control','options' => array('6'=>6,'7'=>7),'empty' => 'Working Days','value'=>$cost['working_days'])); ?>
						</div>
						<label class="col-sm-1 control-label">Target ManDate</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('target_mandate', array('label'=>false,'class'=>'form-control','placeholder' => 'Target Mandate','value'=>$cost['target_mandate'])); ?>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Over SalDays</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('over_saldays', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'value'=>$cost['over_saldays'],'required'=>true)); ?>
							
						</div>
						<label class="col-sm-2 control-label">Training Days</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('training_days', array('label'=>false,'class'=>'form-control','value'=>$cost['training_days'],'required'=>true)); ?>
						</div>
						<label class="col-sm-1 control-label">Incentive Allowed</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('incentive_allowed', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'value'=>$cost['incentive_allowed'],'required'=>true)); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Training Attrition</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('training_attrition', array('label'=>false,'class'=>'form-control','value'=>$cost['training_attrition'])); ?>
						</div>
						<label class="col-sm-2 control-label">Deduction Allowed</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('deduction_allowed', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'value' => $cost['deduction_allowed'],'required'=>true)); ?>
						</div>
						<label class="col-sm-1 control-label">Description</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('description', array('label'=>false,'class'=>'form-control','value' => $cost['description'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">OP Branch</label>
						<div class="col-sm-3">
							<?php	echo $this->Form->input('OPBranch', array('label'=>false,'class'=>'form-control','options'=>$OPBranch,'value'=>$cost['OPBranch'])); ?>
						</div>
						<label class="col-sm-2 control-label">SendToBPS</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('SendToBPS', array('label'=>false,'class'=>'form-control','options' => array('update'=>'Update','insert'=>'Insert'),'required'=>true)); ?>
						</div>
						
					</div>
					
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-4 control-label">Tally Client For This Cost Center</label>
						<div class="col-sm-4">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
						<?php	echo $this->Form->input('client_tally_name', array('label'=>false,'class'=>'form-control','value'=>$cost['client_tally_name'],'placeholder' => 'Client Tally Name For Cost Center','required'=>true)); ?>
						</div>
					</div>
					
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Process Manager</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('process_manager', array('label'=>false,'class'=>'form-control','value' => $cost['process_manager'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Email ID</label>
						<div class="col-sm-4">

						<?php	echo $this->Form->input('emailid', array('label'=>false,'class'=>'form-control','value' => $cost['emailid'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">HR Emails</label>
						<div class="col-sm-4">

						<?php	echo $this->Form->input('hremail', array('label'=>false,'class'=>'form-control','value' => $cost['hremail'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Contact No</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('contact_no', array('label'=>false,'class'=>'form-control','value' => $cost['contact_no'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Cost Center</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','value' => $cost['cost_center'],'readonly'=>true)); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('active', array('label'=>false,'options'=>array('1'=>'Active','0'=>'Deactive'),'value'=>$cost['active'],'class'=>'form-control')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Close Date</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('close', array('label'=>false,'value'=>$cost['close'],'class'=>'form-control')); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Process Name</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('process_name', array('label'=>false,'class'=>'form-control','value' => $cost['process_name'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Tower</label>
						<div class="col-sm-4">
						<?php	echo $this->Form->input('tower', array('label'=>false,'class'=>'form-control','value' => $cost['tower'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">GST</label>
                                            <div class="col-sm-4">
                                                <input type="radio" name="data[CostCenterMaster][GSTType]" id="GSTType1" value="Integrated" <?php if($cost['GSTType']=='Integrated') echo "checked"; ?> />InterState(IGST)
                                                <input type="radio" name ="data[CostCenterMaster][GSTType]" id="GSTType" value="Intrastate" <?php if($cost['GSTType']=='Intrastate') echo "checked"; ?>  />Intrastate(CGST,SGST)
                                            </div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">GST No.</label>
                                            <div class="col-sm-4" id="serv_no">
                                            <?php echo $this->Form->input('ServiceTaxNo', array('label'=>false,'options'=>array($cost['ServiceTaxNo']=>$cost['ServiceTaxNo']),'class'=>'form-control','empty' => 'GST No','value'=>$cost['ServiceTaxNo'],'required'=>true)); ?>
                                            </div>
					</div>
                                <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">HSN Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('HSNCode', array('label'=>false,'value'=>$cost['HSNCode'],'class'=>'form-control','placeholder' => 'HSN Code','required'=>true)); ?>
                                            </div>
					</div>
                                
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">SAC Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('SACCode', array('label'=>false,'class'=>'form-control','placeholder' => 'SAC Code','value'=>$cost['SACCode'],'required'=>true)); ?>
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
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">State Name</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('statenamecost', array('label'=>false,'class'=>'form-control','placeholder' => 'State Name','value'=>$cost['statenamecost'],'required'=>true)); ?>
                                            </div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">State Code</label>
                                            <div class="col-sm-4">
                                            <?php echo $this->Form->input('statecodecost', array('label'=>false,'class'=>'form-control','placeholder' => 'State Code','value'=>$cost['statecodecost'],'required'=>true)); ?>
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
						<?php	echo $this->Form->input('revenueType', array('label'=>false,'class'=>'form-control','options' => array('Fixed'=>'Fixed','Variable'=>'Variable'),'empty' => 'Select Revenue','value'=>$cost['revenueType'])); ?>
						</div>
                                                <label class="col-sm-2 control-label">Fixed</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('fixed', array('label'=>false,'class'=>'form-control','options' => array('Seat'=>'Seat','Fos'=>'Fos','Manpower'=>'Manpower'),'empty' => 'Select Fixed','value'=>$cost['fixed'])); ?>
						</div>

						<label class="col-sm-2 control-label">Variable Base</label>
						<div class="col-sm-2">						
						<?php	echo $this->Form->input('variableBase',array('label'=>false,'options'=>array('Hourly'=>'Hourly','Minute'=>'Minute','Case'=>'Case','Contact'=>'Contact'),'empty'=>'Select Variable','class'=>'form-control','value'=>$cost['variableBase'])); ?>
						</div>
					</div>						
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Agreement Req.</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('agreementReq', array('label'=>false,'class'=>'form-control','options'=>array(1=>'Yes',0=>'No'),'empty' => 'Select','value'=>$cost['agreementReq'])); ?>
						</div>
						<label class="col-sm-2 control-label">Payment Mode</label>
						<div class="col-sm-2">
                                                    <?php echo $this->Form->input('paymentMode', array('label'=>false,'class'=>'form-control','options' => array('Cheque'=>'Cheque','RTGS'=>'RTGS','Talk Time Trnsf.'=>'Talk Time Trnsf.'),'empty' => 'Select','value'=>$cost['paymentMode'])); ?>
						</div>
						<label class="col-sm-2 control-label">Payment Terms</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('paymentTerms', array('label'=>false,'class'=>'form-control','options' =>array('30'=>'30 Days','60'=>'60 Days','90'=>'90 Days','120'=>'120 Days'),'empty' => 'Select','value'=>$cost['paymentTerms'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Association Date</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('AssociationDate', array('label'=>false,'type'=>'text','class'=>'form-control date-picker','PlaceHolder' => 'Association Date','value'=>$cost['AssociationDate'])); ?>
						</div>
						<label class="col-sm-2 control-label">Go Live Date</label>
						<div class="col-sm-2">
                                                    <?php echo $this->Form->input('goLiveDate', array('label'=>false,'type'=>'text','class'=>'form-control date-picker','PlaceHolder' => 'goLiveDate','value'=>$cost['goLiveDate'])); ?>
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
                                                    <?php   echo $this->Form->input('UserName1', array('label'=>false,'class'=>'form-control','value'=>$cost['UserName1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation1', array('label'=>false,'class'=>'form-control','value'=>$cost['UserDesignation1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo1', array('label'=>false,'class'=>'form-control','value'=>$cost['UserContactNo1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId1', array('label'=>false,'class'=>'form-control','value'=>$cost['UserEmailId1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress1', array('label'=>false,'class'=>'form-control','value'=>$cost['UserAddress1'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                                <label class="col-sm-2 control-label" style="margin-left:-40px;">User Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserName2', array('label'=>false,'class'=>'form-control','value'=>$cost['UserName2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation2', array('label'=>false,'class'=>'form-control','value'=>$cost['UserDesignation2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo2', array('label'=>false,'class'=>'form-control','value'=>$cost['UserContactNo2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId2', array('label'=>false,'class'=>'form-control','value'=>$cost['UserEmailId2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress2', array('label'=>false,'class'=>'form-control','value'=>$cost['UserAddress2'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                             <label class="col-sm-2 control-label" style="margin-left:-40px;">User Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserName3', array('label'=>false,'class'=>'form-control','value'=>$cost['UserName3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserDesignation3', array('label'=>false,'class'=>'form-control','value'=>$cost['UserDesignation3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserContactNo3', array('label'=>false,'class'=>'form-control','value'=>$cost['UserContactNo3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserEmailId3', array('label'=>false,'class'=>'form-control','value'=>$cost['UserEmailId3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('UserAddress3', array('label'=>false,'class'=>'form-control','value'=>$cost['UserAddress2'])); ?>
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
                                                    <?php   echo $this->Form->input('SCMName1', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMName1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation1', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMDesignation1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo1', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMContactNo1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId1', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMEmailId1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress1', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMAddress1'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMName2', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMName2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation2', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMDesignation2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo2', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMContactNo2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId2', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMEmailId2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress2', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMAddress2'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">SCM Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMName3', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMName3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMDesignation3', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMDesignation3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMContactNo3', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMContactNo3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMEmailId3', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMEmailId3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('SCMAddress3', array('label'=>false,'class'=>'form-control','value'=>$cost['SCMAddress3'])); ?>
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
                                                    <?php   echo $this->Form->input('FinanceName1', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceName1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation1', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceDesignation1'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo1', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceContactNo1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId1', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceEmailId1'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress1', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceAddress1'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Level 2</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceName2', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceName2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation2', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceDesignation2'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo2', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceContactNo2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId2', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceEmailId2'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress2', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceAddress2'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label" style="margin-left:-40px;">Finance Level 3</label>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceName3', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceName3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceDesignation3', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceDesignation3'])); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceContactNo3', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceContactNo3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceEmailId3', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceEmailId3'])); ?>
						</div>
						<div class="col-sm-2">
                                                    <?php   echo $this->Form->input('FinanceAddress3', array('label'=>false,'class'=>'form-control','value'=>$cost['FinanceAddress3'])); ?>
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
					<i class="fa fa-search"></i>
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
							<?php	echo $this->Form->input('po_required', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'value'=>$cost['po_required'])); ?>		
						</div>
						<label class="col-sm-2 control-label">JCC No</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('jcc_no', array('label'=>false,'class'=>'form-control','options' => array('Yes'=>'Yes','No'=>'No'),'value'=>$cost['jcc_no'])); ?>
						</div>
						<label class="col-sm-1 control-label">GRN</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('grn', array('label'=>false,'class'=>'form-control','options' => array('No'=>'No','Yes'=>'Yes'),'value'=>$cost['grn'])); ?>
							
						</div>
						
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Bill To</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->textarea('bill_to', array('label'=>false,'class'=>'form-control','value'=>$cost['bill_to'])); ?>
							<?php $flag = false;
									if($cost['as_client']=='1'){$flag = true;}
							echo $this->Form->checkbox('as_client', array('checked' => $flag)); ?><b class="bg-info">As Client</b>
						</div>

						<label class="col-sm-2 control-label">Ship To</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->textarea('ship_to', array('label'=>false,'class'=>'form-control','value' => $cost['ship_to'])); ?>
							<?php $flag = false;
							if($cost['as_bill_to']=='1'){$flag = true;}
							echo $this->Form->checkbox('as_bill_to', array('checked' => $flag)); ?><b class="bg-info">As BillTo</b>
						</div>
					</div>
					
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 1</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address1', array('label'=>false,'class'=>'form-control','value' => $cost['b_Address1'])); ?>
						</div>

						<label class="col-sm-2 control-label">Address 1</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address1', array('label'=>false,'class'=>'form-control','value' => $cost['a_address1'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 2</label>
						<div class="col-sm-2">
							<?php //$data=array();foreach ($client_master as $post): ?>
							<?php  //$data[]= $post['Addclient']['client_name']; ?>
							<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address2', array('label'=>false,'class'=>'form-control','value' => $cost['b_Address2'])); ?>
						</div>

						<label class="col-sm-2 control-label">Address 2</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address2', array('label'=>false,'class'=>'form-control','value' => $cost['a_address2'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 3</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address3', array('label'=>false,'class'=>'form-control','value' => $cost['b_Address3'])); ?>
						</div>

						<label class="col-sm-2 control-label">Address 3</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address3', array('label'=>false,'class'=>'form-control','value' => $cost['a_address3'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 4</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address4', array('label'=>false,'class'=>'form-control','value' => $cost['b_Address4'])); ?>
						</div>

						<label class="col-sm-2 control-label">Address 4</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address4', array('label'=>false,'class'=>'form-control','value' => $cost['a_address4'])); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Address 5</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('b_Address5', array('label'=>false,'class'=>'form-control','value' => $cost['b_Address5'])); ?>
						</div>

						<label class="col-sm-2 control-label">Address 5</label>
						<div class="col-sm-2">
						<?php //$data=array();foreach ($client_master as $post): ?>
						<?php  //$data[]= $post['Addclient']['client_name']; ?>
						<?php //endforeach; ?><?php //unset($Addclient); ?>
							<?php	echo $this->Form->input('a_address5', array('label'=>false,'class'=>'form-control','value' => $cost['a_address5'])); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                            <label class="col-sm-2 control-label">Client GST No.</label>
                                            <div class="col-sm-4" id="serv_no">
                                                <?php echo $this->Form->input('VendorGSTNo', array('label'=>false,'value'=>'','class'=>'form-control','placeholder' => 'Vendor GST No','value'=>$cost['VendorGSTNo'],'required'=>true)); ?>
                                            </div>
					</div>
                                        
                                
				<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-sm-2">
                        <?php	echo $this->Form->input('id', array('label'=>false,'class'=>'form-control','type'=>'hidden','value' => $cost['id'])); ?>
							<button type="submit" class="btn btn-primary btn-label-left">							
								Update
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
