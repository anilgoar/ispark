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

<script>
function checkAllBox()
{
    if($("#checkAll").prop('checked'))
    $('input:checkbox').add().prop('checked','checked');
    else
     $('input:checkbox').add().prop('checked',false);   
}



</script>
<div class="box-content">
				<h4 class="page-header">Search Prospect</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Addproduct',array('class'=>'form-horizontal')); ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Product Name</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('ProductId',array('label' => false,'class'=>'form-control','options'=>$product_master,'empty'=>'Product Name')); ?>
						</div>
                                                <label class="col-sm-2 control-label">Client Name</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('ClientName',array('label' => false,'class'=>'form-control','placeholder'=>'Client Name')); ?>
						</div>
                                                
					</div>
                                        <div class="form-group">
                                            
                                            <label class="col-sm-2 control-label">Date To</label>
                                                <div class="col-sm-2">
							<?php echo $this->Form->input('ToDate',array('label' => false,'class'=>'form-control','placeholder'=>'Date To','onclick'=>"displayDatePicker('data[Addproduct][ToDate]');")); ?>
						</div>
                                            <label class="col-sm-2 control-label">Date From</label>
                                                <div class="col-sm-2">
							<?php echo $this->Form->input('FromDate',array('label' => false,'class'=>'form-control','placeholder'=>'Date From','onclick'=>"displayDatePicker('data[Addproduct][FromDate]');")); ?>
						</div>
                                            
                                        </div>
                                <div class="form-group">
                                       <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-2">
                                                <button type="submit" value="Search" name="Search" class="btn btn-primary btn-label-left">
								Search
							</button>
						</div>     
                                                
                                        </div>
					<div class="clearfix"></div>
					<div class="form-group">
						
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
            <form method="post">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Prospect List</span>
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

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=0; ?>
					<thead>
						<tr class="active">
                                                    <td align="center"><input type="checkbox" value="1" name="checkAll" id="checkAll" onclick="checkAllBox()" /><b>Sr. No.</b></td>
                                                        <td align="center"><b>Client Name</b></td>
							<td align="center"><b>Product Name</b></td>
                                                        <td align="center"><b>Introduction</b></td>
                                                        <td align="center"><b>Contact No</b></td>
                                                        <td align="center"><b>Email</b></td>
                                                        <td align="center"><b>Address</b></td>
                                                        <td align="center"><b>Remarks</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($sales_master as $post): ?>
						<tr class="<?php   $i++;?>">
                                                    <td align="center"><input type="checkbox" name="check[]" value="<?php echo $post['sc']['Id']; ?>" id="check<?php echo $post['sc']['Id']; ?>" /><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $this->Html->link($post['sc']['ClientName'],array('controller'=>'Addproducts','action'=>'create_cover','?'=>array('Id'=>$post['sc']['Id']),'full_base' => true)); ?></td>
							<td align="center"><?php echo $post['sp']['ProductName']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Introduction']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['ContactNo']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Email']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Address']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Remarks']; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($sales_master); ?>
					</tbody>
				</table>
			</div>
                    
                    
		</div>
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-4">
                            <button value="Approve" name="Approve" class="btn btn-primary">Approve</button>
                            <button value="DisApprove" name="DisApprove" class="btn btn-primary">DisApprove</button>
                        </div>
                        
                    </div>
            </form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>