<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>


<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->fetch('description');
		echo $this->fetch('author');
		echo $this->Html->css('bootstrap/bootstrap');
		//echo $this->Html->css('css/style_v2');
		echo $this->Html->css('css/style_v1');
                //echo $this->Html->css('css/mystyle');
		echo $this->Html->css('jquery-ui/jquery-ui.min');
		echo $this->Html->css('fancybox/jquery.fancybox');
		//echo $this->Html->css('fullcalendar/fullcalendar');
		echo $this->Html->css('xcharts/xcharts.min');
		//echo $this->Html->css('select2/select2');
		echo $this->Html->css('justified-gallery/justifiedGallery');
		echo $this->Html->css('chartist/chartist.min');
		echo $this->Html->css('date');
                
                
                 echo $this->Html->css('css/alertify');
                echo $this->Html->css('css/themes/bootstrap');
                echo $this->Html->css('css/themes/default');
                echo $this->Html->css('css/themes/semantic');
                //echo $this->Html->script('tinymce/tinymce.min');
		echo $this->Html->script('jquery/jquery-2.1.0.min');
		//echo $this->Html->script('jquery/jquery.min');		
		//echo $this->Html->script('jquery-ui/jquery-ui.min');
		echo $this->Html->script('bootstrap/bootstrap.min');
		//echo $this->Html->script('justified-gallery/jquery.justifiedGallery.min');
		//echo $this->Html->script('tinymce/tinymce.min');
		//echo $this->Html->script('tinymce/jquery.tinymce.min');
		echo $this->Html->script('js/devoops');
		echo $this->Html->script('js/dateback');
		//echo $this->Html->script('datetimepicker_css');
		echo $this->Html->script('validate');
		echo $this->Html->script('validate_collection');
		echo $this->Html->script('validate_collection_report');
		echo $this->Html->script('BillApprovalStages');
		echo $this->Html->script('IssueTracker');
                echo $this->Html->script('provision');
                echo $this->Html->script('provision_report');
		echo $this->Html->script('alertify');
	?>
	
<link href='https://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
          

</head>
<body>



<div id="screensaver">
	<canvas id="canvas"></canvas>
	<i class="fa fa-lock" id="screen_unlock"></i>
</div>
<div id="modalbox">
	<div class="devoops-modal">
		<div class="devoops-modal-header">
			<div class="modal-header-name">
				<span>Basic table</span>
			</div>
			<div class="box-icons">
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="devoops-modal-inner">
		</div>
		<div class="devoops-modal-bottom">
		</div>
	</div>
</div>
<header class="navbar">
	<div class="container-fluid ">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2">
			
			<?php	echo $this->Html->link('I-Spark',array('controller'=>'users','action'=>'view')); ?>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-1">
						
						
					</div>
                                        <div class="col-xs-6 col-sm-3">
                                            <div class="user-mini pull-right">
                                                <span class="welcome" style="font-size:16px;"><?php echo $this->Session->read('branch_name'); ?></span>
						</div>
					</div>
					<div class="col-xs-4 col-sm-8 top-panel-right">
						<ul class="nav navbar-nav pull-right panel-menu">
							
							
							<li class="dropdown">
								<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
									
									<i class="fa fa-angle-down pull-right"></i>
									<div class="user-mini pull-right">
										<span class="welcome"><?php echo $this->Session->read("username"); ?></span>
										<span>MAS CALLNET</span>
									</div>
								</a>
								<ul class="dropdown-menu">

                                    <li><?php echo $this->Html->link('<i class="fa fa-user"></i>'.'Setting',array('controller'=>'Users','action'=>'edit_user','full_base' => true),array('escape'=>false)); ?></li>

									<li><?php echo $this->Html->link('<i class="fa fa-power-off"></i>'.'Logout',array('controller'=>'Users','action'=>'logout','full_base' => true),array('escape'=>false)); ?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- Page Access Violation-->
<?php 
$page_access=explode(',',$this->Session->read("page_access"));
?>
<div id="main" class="container-fluid expanded-panel" style = "overflow:auto">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			
		  
		
		
			<ul class="nav main-menu">
			
			

			
			
                               
                               
                                
                          
                               
                            
                            
                            
                        
                        
                                
                              

			  
                                
                                
                                
                        
                               
                       
						
			<li class="dropdown">
				<a href="#" class="dropdown-toggle">
					<i class="material-icons">settings</i>
					<span class="hidden-xs">Ispark Management</span>
				</a>        

				<ul class="dropdown-menu">
					<?php  
					$dd_ispark = $this->Session->read('dd_ispark');
					foreach ($dd_ispark as $key => $value){
					?> 
					<li><?php echo $this->Html->link($value['pages_master_ispark']['page_name'],array('controller'=>$value['pages_master_ispark']['page_url'],'?'=>array('AX'=>base64_encode($value['pages_master_ispark']['id'])),'full_base' => true));?></li>
					<?php } ?>
					
					<?php if($this->Session->read('role')=='admin' && $this->Session->read('branch_name') =="HEAD OFFICE"){?>
						<li><?php echo $this->Html->link('User Rights',array('controller'=>'AccesIspark','action'=>'index','full_base' => true));?></li>
					<?php } ?>
				</ul>
			</li>
                  

					   
					   
                                
                        <!--
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-pencil-square-o"></i>
                                <span class="hidden-xs">HR Management</span>
                            </a>        

                            <ul class="dropdown-menu">
                                <?php  
                                $drop_down = $this->Session->read('dd');
								
								
                                foreach ($drop_down as $key => $value){
                                ?> 
                                <li><?php echo $this->Html->link($value['pages_master']['page_name'],array('controller'=>$value['pages_master']['page_url'],'?'=>array('AX'=>base64_encode($value['pages_master']['id'])),'full_base' => true));?></li>
                                <?php } ?>
                                
                                <?php if($this->Session->read('role')=='admin' && $this->Session->read('branch_name') =="HEAD OFFICE"){?>
                                    <li><?php echo $this->Html->link('User Rights',array('controller'=>'Acces','action'=>'index','full_base' => true));?></li>
                                <?php } ?>
                            </ul>
                        </li>
                        -->
                        
                        <style>
                            .level1{
                                position:relative !important;
                                top:4px !important;
                                right:5px !important;
                            }
                            .name1{
                                position:relative !important;
                                right:72px !important;
                            }
                            .level2{
                                position:relative !important;
                                top:4px !important;
                                left:5px !important;
                            }
                            .name2{
                                position:relative !important;
                                right:58px !important;
                            }
                        </style>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="material-icons">settings</i>
                                <span class="hidden-xs">HR Management</span>
                            </a>
                            
                            <ul class="dropdown-menu">
                                <?php
                                $parrent_level=array(
                                    '2,3,15,9,11'=>'Attendance',
                                    '14,6,5,163'=>'JCLR',
                                    '7,4,107,13,12,102,17'=>'Payroll',
                                    '10'=>'Document Validation',
                                    '8'=>'Master',
                                    '16'=>'Reports',
                                    '108'=>'Training Module',
                                    '126'=>'HR Visitors',
                                    '151,152'=>'Approval',
                                    '150'=>'Export Data',
                                    '183'=>'Employee Transition Manager',
									 //'195'=>'2GTHR@MAS',
									 '195'=>'2GTHR@MAS',
									 '203'=>'Performance-linked incentive System',
                                );

								//print_r($parrent_level);die;
								
                                foreach($parrent_level as $key=>$val){
									
									$list_arr   =   explode(",",$key);

									//print_r($list_arr);die;
									
									if(count($list_arr) > 1){
										$sub_url	=	$this->webroot.'Menus/sub?SM='.base64_encode($val);
									}
									else{
										$sub_url	=	"#";
										$drop_down 	= $this->Session->read('dd');
										//print_r($drop_down);die;
										foreach ($drop_down as $key => $value){
											if (in_array($value['pages_master']['id'],$list_arr)){
												$sub_url	=	$this->webroot.$value['pages_master']['page_url'].'?'.'AX='.base64_encode($value['pages_master']['id']);
											}
											
										}
									}
									?>
									<li class="dropdown">
										<a href="<?php echo $sub_url; ?>" class="dropdown-toggle">
											<i class="material-icons level1">arrow_right_alt</i>
											<span class="hidden-xs name1"><?php echo $val;?></span>
										</a>
										<!--
										<ul class="dropdown-menu">
											<?php
											//$drop_down = $this->Session->read('dd');
											//foreach ($drop_down as $key => $value){
											//if (in_array($value['pages_master']['id'],$list_arr)){
											?> 
											<li class="dropdown"><a href="<?php //echo $this->webroot.$value['pages_master']['page_url'].'?'.'AX='.base64_encode($value['pages_master']['id']);?>" class="dropdown-toggle"><i class="material-icons level2" style="">arrow_right_alt</i><span class="hidden-xs name2" ><?php //echo $value['pages_master']['page_name'];?></span></a></li>
											<?php //} } ?>
										</ul>
										-->
									</li> 
                                <?php } ?>
                                
                      
                              
                                    
                                <?php if($this->Session->read('role')=='admin' && $this->Session->read('branch_name') =="HEAD OFFICE"){?>
                                <li class="dropdown"><a href="<?php echo $this->webroot;?>Acces" class="dropdown-toggle"><i class="material-icons level1" style="">arrow_right_alt</i><span class="hidden-xs name1" >User Rights</span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        
                        
                        
                        <?php if(in_array('184',$page_access) || in_array('216',$page_access)){ ?>
                            <li class="drop-down">
                                <a href="#" class="dropdown-toggle">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <span class="hidden-xs">Business Analytics</span> 
                                </a>
                                <ul class="dropdown-menu">
                                    <li><?php if(in_array('184',$page_access)) {echo $this->Html->link('<span>Working Hours<span>',array('controller'=>'WorkingDetails','action'=>'index','full_base' => true),array('escape'=>false));} ?></li>
                                    <li><?php if(in_array('216',$page_access)) {echo $this->Html->link('<span>Profit Loss<span>',array('controller'=>'MenuIsparks','action'=>'profitloss','full_base' => true),array('escape'=>false));} ?></li>
                                </ul>
                            </li>
                            <?php } ?>
                        
                                
                                
					</ul>
				
			
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">
			
			
						
						<?php echo $this->fetch('content'); ?>
			
			
			
		</div>
		<!--End Content-->
	</div>
</div>

</body>
</html>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>newstyle/material-icon.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>newstyle/newstyle.css"/>

<!--
<link rel="stylesheet" href="<?php echo $this->webroot;?>date-picker/jquery-ui.css">
<script src="<?php echo $this->webroot;?>date-picker/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $(".date-picker").datepicker({dateFormat: "yy-mm-dd"});
    });
</script>
-->