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
		echo $this->Html->css('jquery-ui/jquery-ui.min');
		echo $this->Html->css('fancybox/jquery.fancybox');
		echo $this->Html->css('fullcalendar/fullcalendar');
		echo $this->Html->css('xcharts/xcharts.min');
		echo $this->Html->css('select2/select2');
		echo $this->Html->css('justified-gallery/justifiedGallery');
		echo $this->Html->css('chartist/chartist.min');
		//echo $this->Html->script('jquery/jquery.min');
		echo $this->Html->script('jquery-ui/jquery.min');		
		echo $this->Html->script('jquery-ui/jquery-ui.min');
		echo $this->Html->script('bootstrap/bootstrap.min');
		echo $this->Html->script('justified-gallery/jquery.justifiedGallery.min');
		echo $this->Html->script('tinymce/tinymce.min');
		echo $this->Html->script('tinymce/jquery.tinymce.min');
		echo $this->Html->script('js/devoops');
		echo $this->Html->script('js/datetimepicker_css');
		echo $this->Html->script('validate');
		//echo $this->Html->script('js/devoops.min');
	?>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
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
			
			<?php	echo $this->Html->link('ICE-Park','index.html'); ?>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-4">
						<a href="#" class="show-sidebar">
						  <i class="fa fa-bars"></i>
						</a>
						<div id="search">
							<input type="text" placeholder="search"/>
							<i class="fa fa-search"></i>
						</div>
					</div>
					<div class="col-xs-4 col-sm-8 top-panel-right">
						<ul class="nav navbar-nav pull-right panel-menu">
							<li class="hidden-xs">
								<a href="index.html" class="modal-link">
									<i class="fa fa-bell"></i>
									<span class="badge">7</span>
								</a>
							</li>
							<li class="hidden-xs">
								<a  href="ajax/calendar.html">
									<i class="fa fa-calendar"></i>
									<span class="badge">7</span>
								</a>
							</li>
							<li class="hidden-xs">
								<a href="ajax/page_messages.html" >
									<i class="fa fa-envelope"></i>
									<span class="badge">7</span>
								</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
									<div class="avatar">
										<?php echo $this->Html->image('avatar.jpg', array('alt' => "avatar",'class' => 'img-rounded'));?>
									</div>
									<i class="fa fa-angle-down pull-right"></i>
									<div class="user-mini pull-right">
										<span class="welcome"><?php echo $this->Session->read("username"); ?></span>
										<span>MAS CALL NET</span>
									</div>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">
											<i class="fa fa-user"></i>
											<span>Profile</span>
										</a>
									</li>
									<li>
										<a href="ajax/page_messages.html" >
											<i class="fa fa-envelope"></i>
											<span>Messages</span>
										</a>
									</li>
									<li>
										<a href="ajax/gallery_simple.html" >
											<i class="fa fa-picture-o"></i>
											<span>Albums</span>
										</a>
									</li>
									<li>
										<a href="ajax/calendar.html" >
											<i class="fa fa-tasks"></i>
											<span>Tasks</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="fa fa-cog"></i>
											<span>Settings</span>
										</a>
									</li>
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
<div id="main" class="container-fluid expanded-panel" >
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			<ul class="nav main-menu">
				<li>
						<?php if(in_array('10',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Dashboard<span>',array('controller'=>'InitialInvoices','action'=>'dashboard','full_base' => true),array('escape'=>false));} ?>
					
					
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">Masters</span>
					</a>
					
					<ul class="dropdown-menu">
						<li><?php if(in_array('1',$page_access)){echo $this->Html->link('Add Branch',array('controller'=>'Addbranches','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('2',$page_access)){echo $this->Html->link('Add Client',array('controller'=>'Addclients','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('3',$page_access)){echo $this->Html->link('Cost Center',array('controller'=>'costCenterMasters','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('8',$page_access)){echo $this->Html->link('Manage Access',array('controller'=>'Users','action'=>'manage_Access','full_base' => true));} ?></li>
<!--						<li><?php	echo $this->Html->link('Morris Charts','ajax/charts_morris.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('CoinDesk realtime','ajax/charts_coindesk.html',array('class'=>'ajax-link')); ?></li>
-->					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-table"></i>
						 <span class="hidden-xs">Invoice</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('4',$page_access)){echo $this->Html->link('Initial Invoice',array('controller'=>'InitialInvoices','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('5',$page_access)){echo $this->Html->link('View Invoice',array('controller'=>'InitialInvoices','action'=>'view','full_base' => true));} ?></li>
						<li><?php if(in_array('9',$page_access)){echo $this->Html->link('View Invoice',array('controller'=>'InitialInvoices','action'=>'branch_view','full_base' => true));} ?></li>
						<li><?php if(in_array('6',$page_access)){echo $this->Html->link('Invoice PO',array('controller'=>'InitialInvoices','action'=>'view_admin','full_base' => true));} ?></li>
						<li><?php if(in_array('7',$page_access)){echo $this->Html->link('Download Invoice',array('controller'=>'InitialInvoices','action'=>'download','full_base' => true));} ?></li>
					</ul>
			</li>
<!--				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Forms</span>
					</a>
					<ul class="dropdown-menu">
						<li><a  href="ajax/forms_elements.html">Elements</a></li>
						<li><?php	echo $this->Html->link('Elements','ajax/forms_elements.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Layouts','ajax/forms_layouts.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('File Uploader','ajax/forms_file_uploader.html',array('class'=>'ajax-link')); ?></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-desktop"></i>
						 <span class="hidden-xs">UI Elements</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php	echo $this->Html->link('Grid','ajax/ui_grid.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Buttons','ajax/ui_buttons.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Progress Bars','ajax/ui_progressbars.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Jquery UI','ajax/ui_jquery-ui.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Icons','ajax/ui_icons.html',array('class'=>'ajax-link')); ?></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-list"></i>
						 <span class="hidden-xs">Pages</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php	echo $this->Html->link('Login','ajax/page_login.html'); ?></li>
						<li><?php	echo $this->Html->link('Register','ajax/page_register'); ?></li>
						<li><?php	echo $this->Html->link('Locked Screen','ajax/page_locked.html',array('id'=>'locked-screen','class'=>'submenu')); ?></li>
						<li><?php	echo $this->Html->link('Contacts','ajax/page_contacts.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Feed','ajax/page_feed.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Messages','ajax/page_messages.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Pricing','ajax/page_pricing.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Invoice','ajax/page_invoice.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Search Results','ajax/page_search.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Error 404','ajax/page_404.html',array('class'=>'ajax-link')); ?></li>
						<li><?php	echo $this->Html->link('Error 500','ajax/ajax/page_500.html'); ?></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-map-marker"></i>
						<span class="hidden-xs">Maps</span>
					</a>
					<ul class="dropdown-menu">
						<li><a  href="ajax/maps.html">OpenStreetMap</a></li>
						<li><a  href="ajax/map_fullscreen.html">Fullscreen map</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-picture-o"></i>
						 <span class="hidden-xs">Gallery</span>
					</a>
					<ul class="dropdown-menu">
						<li><a  href="ajax/gallery_simple.html">Simple Gallery</a></li>
						<li><a  href="ajax/gallery_flickr.html">Flickr Gallery</a></li>
					</ul>
				</li>
				<li>
					 <a  href="ajax/typography.html">
						 <i class="fa fa-font"></i>
						 <span class="hidden-xs">Typography</span>
					</a>
				</li>
				 <li>
					<a  href="ajax/calendar.html">
						 <i class="fa fa-calendar"></i>
						 <span class="hidden-xs">Calendar</span>
					</a>
				 </li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-picture-o"></i>
						 <span class="hidden-xs">Multilevel menu</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php	echo $this->Html->link('First level menu','#'); ?></li>
						<li><?php	echo $this->Html->link('First level menu','#'); ?></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle">
								<i class="fa fa-plus-square"></i>
								<span class="hidden-xs">Second level menu group</span>
							</a>
							<ul class="dropdown-menu">
								<li><?php	echo $this->Html->link('Second level menu','#'); ?></li>
								<li><?php	echo $this->Html->link('Second level menu','#'); ?></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle">
										<i class="fa fa-plus-square"></i>
										<span class="hidden-xs">Three level menu group</span>
									</a>
									<ul class="dropdown-menu">
										<li><?php	echo $this->Html->link('Third level menu','#'); ?></li>
										<li><?php	echo $this->Html->link('Third level menu','#'); ?></li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle">
												<i class="fa fa-plus-square"></i>
												<span class="hidden-xs">Four level menu group</span>
											</a>
											<ul class="dropdown-menu">
												<li><?php	echo $this->Html->link('Four level menu','#'); ?></li>
												<li><?php	echo $this->Html->link('Four level menu','#'); ?></li>
												<li class="dropdown">
													<a href="#" class="dropdown-toggle">
														<i class="fa fa-plus-square"></i>
														<span class="hidden-xs">Five level menu group</span>
													</a>
													<ul class="dropdown-menu">
														<li><?php	echo $this->Html->link('Five level menu','#'); ?></li>
														<li><?php	echo $this->Html->link('Five level menu','#'); ?></li>
														<li class="dropdown">
															<a href="#" class="dropdown-toggle">
																<i class="fa fa-plus-square"></i>
																<span class="hidden-xs">Six level menu group</span>
															</a>
															<ul class="dropdown-menu">
																<li><?php	echo $this->Html->link('Six level menu','#'); ?></li>
																<li><?php	echo $this->Html->link('Six level menu','#'); ?></li>
															</ul>
														</li>
													</ul>
												</li>
											</ul>
										</li>
										<li><?php	echo $this->Html->link('Three level menu','#'); ?></li>
									</ul>
								</li>
							</ul>
						</li>
-->					</ul>
				</li>
			</ul>
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">
			<div class="preloader">
			<?php echo 	$this->Html->image('img/devoops_getdata.gif', array('alt' => 'preloader', 'border' => '0','class'=>'devoops-getdata')); ?>
						
						<?php echo $this->fetch('content'); ?>
			
			</div>
			<div id="ajax-content"></div>
		</div>
		<!--End Content-->
	</div>
</div>

</body>
</html>
