<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left">
                
                <li><a href="#">Tables</a></li>
                <li><a href="#">Simple Tables</a></li>
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
    <h4 class="page-header">Edit Product</h4>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('Addproduct',array('class'=>'form-horizontal','action'=>'edit')); ?>
<div class="form-group has-success has-feedback">
	<label class="col-sm-2 control-label">Product Name</label>
	<div class="col-sm-4">
            <?php echo $this->Form->input('ProductName',array('label' => false,'class'=>'form-control','placeholder'=>'Product Name', 'value'=>$product_master['Addproduct']['ProductName'])); ?>
        </div>
</div>

<div class="form-group has-success has-feedback">
        <label class="col-sm-2 control-label">Status</label>
        <div class="col-sm-4">
        <?php echo $this->Form->input('active',array('label' => false,'class'=>'form-control','options'=>array('1'=>'Active','0'=>'Deactive'),'value'=>$product_master['Addproduct']['active'])); ?>
        </div>
</div>
<div class="clearfix"></div>
<div class="form-group">
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-label-left">
                Save
        </button>
    </div>
</div>
<?php echo $this->Form->input('Id',array('label'=>'false','type'=>'hidden','value'=>$product_master['Addproduct']['Id']));  
echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
