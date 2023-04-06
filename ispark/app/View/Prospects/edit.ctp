<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left">
                
                <li><a href="#">Tables</a></li>
                <li><a href="#">Simple Tables</a></li>
        </ol>
        
    </div>
</div>

<div class="box-content">
    <h4 class="page-header">Edit Product</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal','action'=>'edit')); ?>
    <div class="form-group">
						<label class="col-sm-2 control-label">Company</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Company',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$company_master,'value'=>$product_master['ProspectProduct']['company_id'],'required'=>"")); ?>
						</div>
                                                
					</div>
    <div class="form-group">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Branch',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$branch_master,'value'=>$product_master['ProspectProduct']['branch_id'],'required'=>"")); ?>
						</div>
                                                
					</div>
<div class="form-group">
	<label class="col-sm-2 control-label">Product Name</label>
	<div class="col-sm-4">
            <?php echo $this->Form->input('ProductName',array('label' => false,'class'=>'form-control','placeholder'=>'Product Name', 'value'=>$product_master['ProspectProduct']['ProductName'])); ?>
        </div>
</div>

<div class="form-group">
        <label class="col-sm-2 control-label">Status</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('active',array('label' => false,'class'=>'form-control','options'=>array('1'=>'Active','0'=>'Deactive'),'value'=>$product_master['ProspectProduct']['active'])); ?>
        </div>
</div>
<div class="clearfix"></div>
<div class="form-group">
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-label-left">
                Update
        </button>
    </div>
</div>
<?php echo $this->Form->input('Id',array('label'=>'false','type'=>'hidden','value'=>$product_master['ProspectProduct']['Id']));  
echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
