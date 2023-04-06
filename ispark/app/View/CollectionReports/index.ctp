<script>
    function collection_report_get_branch_new(company)
    {
        
$.get("CollectionReports/get_branch",
            {
             company_name:company
            },
            function(data,status){
               $('#branch').html(data);
            });

    }
    
    function collection_report_client_new(branch)
    {
        
$.get("CollectionReports/get_client",
            {
             branch_name:branch
            },
            function(data,status){
               $('#client').html(data);
            });

    }
    function get_collectionReport_new(val)
    {
        var	AddCompanyName = document.getElementById('AddCompanyName').value;
    var	AddBranchName = document.getElementById('AddBranchName').value;
    var	AddToDate = document.getElementById('AddToDate').value;
    var	AddFromDate = document.getElementById('AddFromDate').value;
    var	AddReportType = document.getElementById('AddReportType').value;
    var	AddClientName = document.getElementById('AddClientName').value;
	
	if(AddCompanyName == '')
	{
		alert("Please Select Company Name");
		return false;
	}
	else if(AddBranchName == '')
	{
		alert("Please Select Branch Name");
		return false;
	}
	else if(AddToDate == '')
	{
		alert("Please Select To Date");
		return false;
	}
	else if(AddFromDate == '')
	{
		alert("Please Select From Date");
		return false;
	}
	else if(AddReportType == '')
	{
		alert("Please Select Report Type");
		return false;
	}
	else if (AddClientName == '')
	{
		alert("Please Select Client Name");
		return false;
	}
        
        
        $.get("CollectionReports/get_collectionReport",
            {
             company_name:AddCompanyName,
             branch_name:AddBranchName,
             toDate:AddToDate,
             fromDate:AddFromDate,
             report:AddReportType,
             client_name:AddClientName,
             type:'show'
            },
            function(data,status){
               $('#data').html(data);
               
            });
       return false; 
    }
</script>
<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal')); ?>
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
					<span>Collection Reports</span>
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

<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>
<div class="box-content">
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Select Company</label>
						<div class="col-sm-3">
						<?php 
							$data=array("All"=>"All"); foreach ($company_master as $post): 
						 	$data[$post['Addcompany']['company_name']]= $post['Addcompany']['company_name']; 
						 	endforeach; ?><?php unset($Addcompany); 
						 ?>

							<?php echo $this->Form->input('company_name', array('options' => $data,'label' => false, 'div' => false,'class'=>'form-control','empty'=>'Select','onChange'=>'collection_report_get_branch_new(this.value)')); ?>
						</div>

						<label class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-3"><div id="branch">
							<?php	echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select','required'=>true)); ?></div>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">To Date</label>
						<div class="col-sm-3">
						
							<?php	echo $this->Form->input('toDate', array('label'=>false,'class'=>'form-control','required'=>true,"onclick"=>"displayDatePicker('data[Add][toDate]');")); ?>
						</div>

						<label class="col-sm-2 control-label">From Date</label>
						<div class="col-sm-3">
						<?php	echo $this->Form->input('fromDate', array('label'=>false,'class'=>'form-control','required'=>true,"onclick"=>"displayDatePicker('data[Add][fromDate]');")); ?>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
							<label class="col-sm-2 control-label">Report Type</label>
							<div class="col-sm-3">
                                                            <?php	echo $this->Form->input('report_type', array('label'=>false,'class'=>'form-control','options'=>array('bill_wise'=>'Bill Wise','amt_wise'=>'Cheque Wise'),'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');",'required'=>true)); ?>
							</div>

							<label class="col-sm-2 control-label">Client</label>
							<div class="col-sm-3">
                        		<div id="client"><?php	echo $this->Form->input('client_name', array('label'=>false,'class'=>'form-control','options'=>'','onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');",'required'=>true)); ?></div>
							</div>
						</div>
						<div class="form-group has-success has-feedback">
							<label class="col-sm-2 control-label"></label>
					
						<div class="col-sm-2">
							<button type="show" class="btn btn-success btn-label-left" onclick="return get_collectionReport_new(this.value)" value = "show"><b>Show</b></button>
                            <button type="show" class="btn btn-success btn-label-left" onclick="return get_collectionReport(this.value)" value = "export"><b>Export</b></button>
					
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
					
					<span>View Collection Reports</span>
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
				<div id="data"></div>           
			</div>
		</div>
	</div>
</div>