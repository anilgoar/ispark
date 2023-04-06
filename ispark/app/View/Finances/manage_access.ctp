<?php ?>
<script>   
function redirect(path){
    window.location="<?php echo $this->webroot;?>"+path;
}
</script>
<style>
    .form-group{
        font-size: 13px !important;
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
<?php

?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Manage Access</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('Finances',array('class'=>'form-horizontal')); ?>
                
                
                <?php  foreach($pages as $pageHeader=>$page_id) { 
                     ?>
                <div class="form-group">
                    
                    
                    
                
                    
                <?php  foreach($page_id as $k=>$value) { if(in_array($k,$page_access)) { ?>
                <div class="col-sm-4">
                        <input type="radio" name="MENU" onclick="redirect('<?php echo $value[0]; ?>')"> <?php echo $value[1]; ?>
                    </div>
                <?php } } ?> 
                    
                
                    
                </div>
                <hr/>
                <?php } ?>
                    
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>

<script>
    function get_disp(val)
    {
        $('#'+val).toggle();
    }
    
</script>



