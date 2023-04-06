<?php ?>
<script>   
function redirect(path){
    window.location=path;
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
$sub_menu	=   base64_decode($_REQUEST['SM']);
$parrent_level=array(
	'Attendance'=>'2,3,15,9,11',
	'JCLR'=>'14,6,5,163',
	'Payroll'=>'7,4,107,13,12,102,17',
	'Document Validation'=>'10',
	'Master'=>'8',
	'Reports'=>'16',
	'Training Module'=>'108',
	'HR Visitors'=>'126',
	'Approval'=>'151,152',
	'Export Data'=>'150',
);

$list_arr   =   explode(",",$parrent_level[$sub_menu]);
?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span><?php echo $sub_menu;?></span>
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
                    <?php
					$drop_down = $this->Session->read('dd');				
					foreach ($drop_down as $key => $value){
					if (in_array($value['pages_master']['id'],$list_arr)){
					?> 
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('<?php echo $this->webroot.$value['pages_master']['page_url'].'?'.'AX='.base64_encode($value['pages_master']['id']);?>')"> <?php echo $value['pages_master']['page_name'];?></div>
                    <?php } } ?>    
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
			
        </div>
    </div>	
</div>




