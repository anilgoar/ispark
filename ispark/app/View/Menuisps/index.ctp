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
$result=array_intersect($listid,$access);
?>
<?php if(!empty($result)){?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span><?php echo $pagename;?></span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('Menus',array('class'=>'form-horizontal')); ?>
                
                <div class="form-group">
                    <?php foreach($pagelist as $row){?>
                        <?php if (in_array($row['pages_master_ispark']['id'], $access)){?>
						
							<?php if($row['pages_master_ispark']['page_url'] =="Menuisps/sub"){?>
                            <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('<?php echo $row['pages_master_ispark']['page_url'] ?>?AX=<?php echo base64_encode($row['pages_master_ispark']['id']);?>')"> <?php echo $row['pages_master_ispark']['page_name'] ?></div>
							<?php }else{?>   
							<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('<?php echo $row['pages_master_ispark']['page_url'] ?>')"> <?php echo $row['pages_master_ispark']['page_name'] ?></div>
							<?php }?>  
							
						<?php }?>   
                    <?php } ?>     
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>
<?php }else{?> 

<?php }?> 


