<style>
            
            .table-scroll {
    height: 10em;
    width: 100em;
}

.table-scroll table {
    display: flex;
    flex-flow: column;
    height: 600%;
    width: 100%;
    
}
.table-scroll table thead {
    /* head takes the height it requires, 
    and it's not scaled when table is resized */
    flex: 0 0 auto;
    width: calc(100% - 0.9em);
}
.table-scroll table tbody {
    /* body takes all the remaining available space */
    flex: 1 1 auto;
    display: block;
    max-height: 300%;
    overflow-y: scroll;
    
}
.table-scroll table tbody tr {
    width: 100%;
}
.table-scroll table thead,
.table-scroll table tbody tr {
    display: table;
    table-layout: fixed;
}
/* decorations */
.table-scroll table-container {
    
    padding: 0.3em;
}
.table-scroll table {
    border: 1px solid lightgrey;
}
.table-scroll table td, table th {
    padding: 0.3em;
    border: 1px solid lightgrey;
}
.table-scroll table th {
    border: 1px solid grey;
}
        </style>
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
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-usd"></i>
					<span>Reports</span>
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
<?php $data['All'] = 'All'; ?>
<?php foreach($company_master as $post) :
	$data[$post['Addcompany']['company_name']]=$post['Addcompany']['company_name'];
	endforeach;
?>

<div class="box-content">
<?php //print_r($branch_master); ?>
<?php echo $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view','class'=>'form-horizontal')); ?>

	<div class="form-group">
		<label class="col-sm-1 control-label"><b style="font-size:14px"> Report</b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('Select Report', array('label'=>false,'options'=>array('outstanding'=>'OutStanding Details','outstandingBranchWise'=>'Outstanding Summary'),'empty'=>'Select Report','class'=>'form-control')); ?>
			</div>

		<label class="col-sm-1 control-label"><b style="font-size:14px">Company </b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('company_name', array('label'=>false,'options'=>$data,'empty'=>'Select Company','class'=>'form-control')); ?>
			</div>
						
		<label class="col-sm-1 control-label"><b style="font-size:14px">Type </b></label>	
			<div class="col-sm-3">
				<?php	echo $this->Form->input('type', array('label'=>false,'options'=>array('Branch'=>'Branch Wise','Client' => 'Client Wise'),'empty'=>'Select Branch/Client','class'=>'form-control','onChange'=>'get_branch(this.value)')); ?>
			</div>
		</div>
		<div class="form-group">								
			<div id="mm"></div>
			<label class="col-sm-1 control-label"><b style="font-size:14px">Status</b></label>	
				<div class="col-sm-3">							
					<?php	echo $this->Form->input('status', array('label'=>false,'options'=>array('All'=>'All','IAP' => 'Initial Approval pending','FAP' => 'Final Approval pending'),'empty'=>'Select Status','class'=>'form-control')); ?>								
				</div>
			<label class="col-sm-1 control-label"><b style="font-size:14px"> &nbsp; </b></label>
				<div class="col-sm-3">
							&nbsp;&nbsp;&nbsp;
				</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-2">
				<input type="hidden" value="0" id="pageNo" name="pageNo" />
                                <label class="col-sm-2 control-label"><b style="font-size:14px"</b></label>	
				<div class="btn btn-info btn-label-left" value = "show" onClick="report_validate();">Show</div>
			</div>
			<div class="col-sm-2">
				<div class="btn btn-info btn-label-left" onClick="report_validate2();">Export</div>
			</div>
		</div>
    <div class="clearfix"></div>
	<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div> 
</div>   
<div class="row">
	<div class="col-xs-12">
		<div class="box">			
			<div class="box-content no-padding">
            	
                    
                    
                        
                        <div id = "nn">
                            <div id='processing' style="display: none">
                            <?php echo $this->Html->image('processing.gif', array('alt' => "",'width' => '600','height'=>'150'));?>
                            </div>
                        </div>

		
			</div>
       </div>
	</div>
</div>
        <script  src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>