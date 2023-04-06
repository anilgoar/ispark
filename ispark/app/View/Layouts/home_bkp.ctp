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
                echo $this->Html->css('css/mystyle');
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
	
	<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
     
        <link rel="stylesheet" href="<?php echo $this->webroot;?>date-picker/jquery-ui.css">
        <script src="<?php echo $this->webroot;?>date-picker/jquery-ui.min.js"></script>
        <script>
            $(document).ready(function () {
                $(".date-picker").datepicker({dateFormat: "yy-mm-dd"});
            });
        </script>

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
                            <li class="drop-down">
                                
						<?php if(in_array('10',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Invoice Status<span>',array('controller'=>'InitialInvoices','action'=>'dashboard','full_base' => true),array('escape'=>false));} ?>
                                                <?php if(in_array('57',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Action Window<span>',array('controller'=>'Actions','action'=>'index','full_base' => true),array('escape'=>false));} ?>
                                                <?php if(in_array('38',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Outstanding Dashboard<span>',array('controller'=>'Provisions','action'=>'dashboard','full_base' => true),array('escape'=>false));} ?>
                                                <?php if($this->Session->read('role')!='admin' && in_array('42',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Business Dashboard<span>',array('controller'=>'Dashboards','action'=>'index','full_base' => true),array('escape'=>false));}
                                                       else if(in_array('43',$page_access)) {echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>Business Dashboard<span>',array('controller'=>'Dashs','action'=>'view','full_base' => true),array('escape'=>false));}
                                                ?>
                                                <?php if(in_array('124',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>GRN Dashboard<span>',array('controller'=>'GrnReports','action'=>'grn_dashboard','full_base' => true),array('escape'=>false));} ?>
                                <?php if(in_array('138',$page_access)){echo $this->Html->link('<i class="fa fa-dashboard"></i>'.'<span>New Business Dashboard<span>',array('controller'=>'Dashboards','action'=>'get_data','full_base' => true),array('escape'=>false));} ?>
				</li>
                          <?php  if(in_array('1',$page_access) || in_array('2',$page_access) || in_array('3',$page_access) || in_array('8',$page_access) || in_array('15',$page_access) || in_array('40',$page_access)
                                  || in_array('41',$page_access) || in_array('44',$page_access) || in_array('45',$page_access) || in_array('46',$page_access) || in_array('47',$page_access)
                                  || in_array('53',$page_access) || in_array('54',$page_access)) { ?>   
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">Masters</span> 
					</a>

					<ul class="dropdown-menu"> 
						<li><?php if(in_array('1',$page_access)){echo $this->Html->link('Add Branch',array('controller'=>'Addbranches','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('127',$page_access)){echo $this->Html->link('Tally Heads',array('controller'=>'Addbranches','action'=>'view_head','full_base' => true));} ?></li>
                                                <li><?php if(in_array('128',$page_access)){echo $this->Html->link('Expense Head/SubHead',array('controller'=>'Addbranches','action'=>'view_expense_head','full_base' => true));} ?></li>
						<li><?php if(in_array('2',$page_access)){echo $this->Html->link('Add Client',array('controller'=>'Addclients','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('3',$page_access)){echo $this->Html->link('Cost Center',array('controller'=>'costCenterMasters','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('3',$page_access)){echo $this->Html->link('Edit Cost Center',array('controller'=>'costCenterMasters','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('54',$page_access)){echo $this->Html->link('Cost Center Approval',array('controller'=>'cost_center_masters','action'=>'tmp_view','full_base' => true));} ?></li>
						<li><?php if(in_array('8',$page_access)){echo $this->Html->link('Manage Access',array('controller'=>'Users','action'=>'view_access','full_base' => true));} ?></li>
						<li><?php if(in_array('15',$page_access)){echo $this->Html->link('Create User',array('controller'=>'Users','action'=>'Create_user','full_base' => true));} ?></li>
						<li><?php if(in_array('40',$page_access)){echo $this->Html->link('View User',array('controller'=>'Users','action'=>'view_users','full_base' => true));} ?></li>
                                                <li><?php if(in_array('41',$page_access)){echo $this->Html->link('Create Provision',array('controller'=>'Provisions','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('41',$page_access)){echo $this->Html->link('View Provision',array('controller'=>'Provisions','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('41',$page_access)){echo $this->Html->link('Upload Provision',array('controller'=>'Provisions','action'=>'uploadProvision','full_base' => true));} ?></li>
                                                <li><?php if(in_array('44',$page_access)){echo $this->Html->link('Upload Agreement',array('controller'=>'Agreements','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('45',$page_access)){echo $this->Html->link('View Agreement',array('controller'=>'Agreements','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('46',$page_access)){echo $this->Html->link('Create PO',array('controller'=>'PoNumbers','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('47',$page_access)){echo $this->Html->link('View PO',array('controller'=>'PoNumbers','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('52',$page_access)){echo $this->Html->link('Provision To BPS',array('controller'=>'Revenues','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('53',$page_access) || in_array('54',$page_access) || in_array('56',$page_access) ){echo $this->Html->link('Cost Center Approval',array('controller'=>'cost_center_masters','action'=>'tmp_view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('57',$page_access) || in_array('54',$page_access) || in_array('58',$page_access) ){echo $this->Html->link('Cost Center Email',array('controller'=>'CostCenterEmails','action'=>'index','full_base' => true));} ?></li>
                                                
                                                
					</ul> 
				</li>
                              <?php  } ?>
                                <?php  if(in_array('4',$page_access) || in_array('5',$page_access) || in_array('6',$page_access) || in_array('20',$page_access) || in_array('9',$page_access)
                                        || in_array('11',$page_access) || in_array('7',$page_access) || in_array('12',$page_access) || in_array('13',$page_access) || in_array('14',$page_access)
                                        || in_array('16',$page_access) || in_array('17',$page_access)) { ?>   
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-table"></i>
						 <span class="hidden-xs">Invoice</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('4',$page_access)){echo $this->Html->link('Initial Invoice/Proforma',array('controller'=>'InitialInvoices','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('137',$page_access)){echo $this->Html->link('View/Download Proforma Invoice',array('controller'=>'InitialInvoices','action'=>'download_proforma_branch','full_base' => true));} ?></li>
                                                <li><?php if(in_array('20',$page_access)){echo $this->Html->link('View & Approve Proforma Invoice',array('controller'=>'InitialInvoices','action'=>'download_proforma','full_base' => true));} ?></li>
						<li><?php if(in_array('5',$page_access)){echo $this->Html->link('View And Approve Invoice',array('controller'=>'InitialInvoices','action'=>'view','full_base' => true));} ?></li>
                                                
						<li><?php if(in_array('6',$page_access)){echo $this->Html->link('View Initial Invoice',array('controller'=>'InitialInvoices','action'=>'branch_view','full_base' => true));} ?></li>
                                                
						<li><?php if(in_array('20',$page_access)){echo $this->Html->link('Edit Invoices',array('controller'=>'InitialInvoices','action'=>'view_invoice','full_base' => true));} ?></li>

						<li><?php if(in_array('9',$page_access)){echo $this->Html->link('ADD PO',array('controller'=>'InitialInvoices','action'=>'view_admin','full_base' => true));} ?></li>
						<li><?php if(in_array('11',$page_access)){echo $this->Html->link('Approve PO ',array('controller'=>'InitialInvoices','action'=>'check_po','full_base' => true));} ?></li>
						<li><?php if(in_array('7',$page_access)){echo $this->Html->link('Download Invoice',array('controller'=>'InitialInvoices','action'=>'download','full_base' => true));} ?></li>
						<li><?php if(in_array('12',$page_access)){echo $this->Html->link('ADD GRN',array('controller'=>'InitialInvoices','action'=>'view_grn','full_base' => true));} ?></li>
						<li><?php if(in_array('13',$page_access)){echo $this->Html->link('Approve GRN',array('controller'=>'InitialInvoices','action'=>'check_grn','full_base' => true));} ?></li>
						<li><?php if(in_array('14',$page_access)){echo $this->Html->link('Download PDF',array('controller'=>'InitialInvoices','action'=>'download_grn','full_base' => true));} ?></li>
						<li><?php if(in_array('16',$page_access)){echo $this->Html->link('Submit to Client',array('controller'=>'InitialInvoices','action'=>'approve_ahmd','full_base' => true));} ?></li>
						<li><?php if(in_array('17',$page_access)){echo $this->Html->link('View Invoice To Ahmedabad',array('controller'=>'InitialInvoices','action'=>'view_ahmd','full_base' => true));} ?></li>
					</ul>
			</li>
                         <?php  } ?>
                            <?php if(in_array('31', $page_access) || in_array('34', $page_access) || in_array('36', $page_access) || in_array('33', $page_access) || in_array('32', $page_access) || in_array('37', $page_access)) { ?>
            			<li class="dropdown">
                        	<a href="#" class="dropdown-toggle">
                            	<i class="fa fa-pencil-square-o"></i>
                                	<span class="hidden-xs">Issue Trackers</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                    <li>
                                 <?php if(in_array('31',$page_access)){echo $this->Html->link('Create Issue',array('controller'=>'Issues','action'=>'issue_submit'));} ?>
                                    </li>
                                     <li>
                                  <?php if(in_array('34',$page_access)){echo $this->Html->link('View issue',array('controller'=>'Issues','action'=>'View_issue'));} ?>
                                    </li>
                                     <li>
                                  <?php if(in_array('36',$page_access)){echo $this->Html->link('Issue Opened',array('controller'=>'Issues','action'=>'view_user_issue'));} ?>
                                    </li>
                                    
                                     <li>
                                 <?php if(in_array('33',$page_access)){echo $this->Html->link('Issue View And Allocate',array('controller'=>'Issues','action'=>'issue_allocate'));} ?>
                                    </li>
                                     <li>
                                <?php // if(in_array('22',$page_access)){echo $this->Html->link('Report',array('controller'=>'Issues','action'=>'report'));} ?>
                                    </li>
	 				<li>
	                         <?php if(in_array('37',$page_access)){echo $this->Html->link('Show Status',array('controller'=>'IssueReports','action'=>'show_issue_status'));} ?>
                               
                                    </li>

                                     <li>
                                <?php if(in_array('32',$page_access)){echo $this->Html->link('Issue Report',array('controller'=>'IssueReports','action'=>'view_issue_report'));} ?>
                                
                                    </li>
				
                                   
                                    
                                 </ul>   
                            </li><?php } ?>
                            
                            <?php if(in_array('22', $page_access) || in_array('23', $page_access) || in_array('24', $page_access) || in_array('25', $page_access) || in_array('26', $page_access) || in_array('27', $page_access)) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Reports</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('22',$page_access)){echo $this->Html->link('OutStanding Reports',array('controller'=>'Reports','action'=>'report','full_base' => true));} ?></li>
						<li><?php if(in_array('23',$page_access)){echo $this->Html->link('Invoice Reports',array('controller'=>'Reports','action'=>'bill_genrate_report','full_base' => true));} ?></li>
						<li><?php if(in_array('24',$page_access)){echo $this->Html->link('Collection Reports',array('controller'=>'CollectionReports','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('25',$page_access)){echo $this->Html->link('Bill Stages',array('controller'=>'BillApprovalStages','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('26',$page_access)){echo $this->Html->link('Bill Edited',array('controller'=>'BillGenerations','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('26',$page_access)||in_array('82',$page_access)){echo $this->Html->link('Invoice Export',array('controller'=>'BillApprovalStages','action'=>'invoice_export','full_base' => true));} ?></li>
                                                <li><?php if(in_array('26',$page_access)||in_array('82',$page_access)){echo $this->Html->link('Invoice View',array('controller'=>'BillApprovalStages','action'=>'invoice_image_export','full_base' => true));} ?></li>
                                                <li><?php if(in_array('27',$page_access)){echo $this->Html->link('Ptp Report',array('controller'=>'Reports','action'=>'ptp','full_base' => true));} ?></li>	
                                                <li><?php if(in_array('27',$page_access)){echo $this->Html->link('Provision Report',array('controller'=>'ProvisionReports','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('39',$page_access)){echo $this->Html->link('Collection Planning',array('controller'=>'CollectionReports','action'=>'view_report','full_base' => true));} ?></li>
						      <li><?php if(in_array('55',$page_access)){echo $this->Html->link('Doc Validation Details',array('controller'=>'VailidationReports','action'=>'index','full_base' => true));} ?></li>
                                                      <li><?php if(in_array('60',$page_access)){echo $this->Html->link('Doc Rejected Mis',array('controller'=>'VailidationRejects','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('61',$page_access)){echo $this->Html->link('Master Branch Report',array('controller'=>'MasterBranchReports','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('62',$page_access)){echo $this->Html->link('Master National Report',array('controller'=>'MasterNationalReports','action'=>'index','full_base' => true));} ?></li>
                                                
					</ul>
			</li>
                            <?php } ?>
                        <?php if(in_array('29', $page_access) || in_array('28', $page_access)) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Collection</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('29',$page_access)){echo $this->Html->link('Collection',array('controller'=>'Collections','action'=>'index','full_base' => true));} ?></li>
						<li><?php if(in_array('28',$page_access)){echo $this->Html->link('Receipts',array('controller'=>'Receipts','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('123',$page_access)){echo $this->Html->link('View Collection',array('controller'=>'Collections','action'=>'view_payment','full_base' => true));} ?></li>
                                                <li><?php if(in_array('123',$page_access)){echo $this->Html->link('Approve Collection',array('controller'=>'Collections','action'=>'approve_payment','full_base' => true));} ?></li>
					</ul>
				</li>
                                
			<?php } ?>
                        <?php if(in_array('48', $page_access)) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Business Target</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('48',$page_access)){echo $this->Html->link('Add Target',array('controller'=>'Targets','action'=>'index','full_base' => true));} ?></li>

					
                                   
						<li><?php if(in_array('50',$page_access)){echo $this->Html->link('Upload Target',array('controller'=>'Targets','action'=>'upload_target','full_base' => true));} ?></li>
                                                <li><?php if(in_array('59',$page_access)){echo $this->Html->link('Close Cost Center for Bussiness Dashboard',array('controller'=>'CostCenterActs','action'=>'index','full_base' => true));} ?></li>
                                                    </ul>
					
				</li>
                                <li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Business Report</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('49',$page_access)){echo $this->Html->link('Business Report',array('controller'=>'DashReports','action'=>'index','full_base' => true));} ?></li>

					</ul>
				</li>
                                
			<?php } ?>
                                
                         <?php if(
                                 in_array('63', $page_access) || in_array('64', $page_access) ||
                                 in_array('65', $page_access) || in_array('66', $page_access) ||
                                 in_array('67', $page_access) || in_array('68', $page_access) ||
                                 in_array('69', $page_access) || in_array('70', $page_access) ||
                                 in_array('73', $page_access) || in_array('70', $page_access) ||
                                 in_array('75', $page_access) || in_array('77', $page_access) ||
                                 in_array('78', $page_access) || in_array('79', $page_access) ||
                                 in_array('80', $page_access) || in_array('84', $page_access) ||
                                 in_array('119', $page_access) || in_array('129', $page_access) ||
                                 in_array('125', $page_access) || in_array('126', $page_access) || in_array('130', $page_access)
                                 ) 
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Finance</span>
					</a>
					<ul class="dropdown-menu">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Business Case</span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><?php if(in_array('141',$page_access)){echo $this->Html->link('Add Head Type',array('controller'=>'Imprests','action'=>'add_head_type','full_base' => true));} ?></li>        
                                                <li><?php if(in_array('142',$page_access)){echo $this->Html->link('Add TDS Section',array('controller'=>'Imprests','action'=>'add_tds_section','full_base' => true));} ?></li>
                                                <li><?php if(in_array('133',$page_access)){echo $this->Html->link('Add Expense Head',array('controller'=>'Imprests','action'=>'add_head','full_base' => true));} ?></li>    
                                                <li><?php if(in_array('134',$page_access)){echo $this->Html->link('Add Expense Sub Head',array('controller'=>'Imprests','action'=>'add_sub_head','full_base' => true));} ?></li>
                                                    <li><?php if(in_array('84',$page_access)){echo $this->Html->link('Add Unit',array('controller'=>'Imprests','action'=>'addunit','full_base' => true));} ?></li>
                                                <li><?php if(in_array('63',$page_access)){echo $this->Html->link('Budget Entry',array('controller'=>'ExpenseEntries','action'=>'initial_branch','full_base' => true));} ?></li>
                                                <li><?php if(in_array('63',$page_access)){echo $this->Html->link('Pending Business Case',array('controller'=>'ExpenseEntries','action'=>'view','full_base' => true));} ?></li>
                                                <li><?php if(in_array('74',$page_access)){echo $this->Html->link('Business Case Re-Open Request',array('controller'=>'ExpenseEntries','action'=>'business_case_ropen','full_base' => true));} ?></li>
                                                <li><?php if(in_array('75',$page_access)){echo $this->Html->link('View Business Case Re-Open Request',array('controller'=>'ExpenseEntries','action'=>'view_business_case_ropen','full_base' => true));} ?></li>
						<li><?php if(in_array('64',$page_access)){echo $this->Html->link('Approve Bus. Case(BM)',array('controller'=>'ExpenseEntries','action'=>'view_bm','full_base' => true));} ?></li>
                                                <li><?php if(in_array('65',$page_access)){echo $this->Html->link('Approve Bus. Case(VH)',array('controller'=>'ExpenseEntries','action'=>'view_vh','full_base' => true));} ?></li>
                                                <li><?php if(in_array('66',$page_access)){echo $this->Html->link('Approve Bus. Case(FH)',array('controller'=>'ExpenseEntries','action'=>'view_fh','full_base' => true));} ?></li>
                                                
                                                </ul>
                                            </li>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">GRN</span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                
                                                
                                                <li><?php if(in_array('63',$page_access)){echo $this->Html->link('Manage Access',array('controller'=>'GrnBranches','action'=>'grn_branch_access','full_base' => true));} ?></li>    
                                                <li><?php if(in_array('68',$page_access)){echo $this->Html->link('GRN Processing',array('controller'=>'GrnEntries','action'=>'select_entry','full_base' => true));} ?></li>
                                                <li><?php if(in_array('68',$page_access)){echo $this->Html->link('GRN Pending',array('controller'=>'Gms','action'=>'edit_grn_branch','full_base' => true));} ?></li>
                                                <li><?php if(in_array('68',$page_access)){echo $this->Html->link('Imprest Pending',array('controller'=>'Gms','action'=>'edit_imprest_branch','full_base' => true));} ?></li>
                                                <li><?php if(in_array('119',$page_access)){echo $this->Html->link('Grn Vendor First Approval',array('controller'=>'Gms','action'=>'approve_grn','full_base' => true));} ?></li>
                                                <li><?php if(in_array('143',$page_access)){echo $this->Html->link('Grn Vendor Second Approval',array('controller'=>'Gms','action'=>'approve_grn2','full_base' => true));} ?></li>
                                                <li><?php if(in_array('119',$page_access)){echo $this->Html->link('Approve Grn Imprest',array('controller'=>'Gms','action'=>'approve_imprest','full_base' => true));} ?></li>
                                                <li><?php if(in_array('125',$page_access)){echo $this->Html->link('View GRN Vendor',array('controller'=>'Gms','action'=>'view_grn','full_base' => true));} ?></li>
                                                <li><?php if(in_array('126',$page_access)){echo $this->Html->link('View GRN Imprest',array('controller'=>'Gms','action'=>'view_imprest','full_base' => true));} ?></li>
                                                <li><?php if(in_array('129',$page_access)){echo $this->Html->link('Payment Processing',array('controller'=>'Gms','action'=>'payment_processing','full_base' => true));} ?></li>
<!--                                                <li><?php if(in_array('81',$page_access)){echo $this->Html->link('Delete GRN',array('controller'=>'GrnEntries','action'=>'view_grn','full_base' => true));} ?></li>-->
                                                
                                                </ul>
                                            </li>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">GRN Vendor</span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                <li><?php if(in_array('77',$page_access)){echo $this->Html->link('Add Vendor',array('controller'=>'Imprests','action'=>'vendor_save','full_base' => true));} ?></li>
                                                <li><?php if(in_array('77',$page_access)){echo $this->Html->link('Pending Vendor',array('controller'=>'Imprests','action'=>'tmp_view_branch_vendor','full_base' => true));} ?></li>
                                                <li><?php if(in_array('118',$page_access)){echo $this->Html->link('Approve Vendor',array('controller'=>'Imprests','action'=>'tmp_view_vendor','full_base' => true));} ?></li>
                                                <li><?php if(in_array('77',$page_access)){echo $this->Html->link('View Vendor',array('controller'=>'Imprests','action'=>'view_vendor','full_base' => true));} ?></li>  
                                                <li><?php if(in_array('135',$page_access)){echo $this->Html->link('Add Head/SubHead To Vendor',array('controller'=>'Imprests','action'=>'vendor_add_head','full_base' => true));} ?></li>    
                                                </ul>
                                            </li>
                                                
						<li class="dropdown">
                                                <a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Book GRN</span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                <li><?php if(in_array('78',$page_access)){echo $this->Html->link('Book GRN',array('controller'=>'GrnEntries','action'=>'book_grn_no','full_base' => true));} ?></li>
                                                <li><?php if(in_array('79',$page_access)){echo $this->Html->link('Dispatch GRN',array('controller'=>'Dispatches','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('80',$page_access)){echo $this->Html->link('Receive GRN',array('controller'=>'Dispatches','action'=>'received','full_base' => true));} ?></li>
                                                <li><?php if(in_array('130',$page_access)){echo $this->Html->link('Envelope Print',array('controller'=>'Dispatches','action'=>'view_envelope','full_base' => true));} ?></li>
                                                <li><?php if(in_array('130',$page_access)){echo $this->Html->link('Pending GRN',array('controller'=>'GrnEntries','action'=>'get_pending_grn','full_base' => true));} ?></li>
                                                </ul>
                                            </li>
                                                
                                                
                                               <li class="dropdown">
                                                <a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Imprest Allotment</span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                <li><?php if(in_array('73',$page_access)){echo $this->Html->link('Imprest Allotment',array('controller'=>'Imprests','action'=>'imprest_save','full_base' => true));} ?></li>
                                                <li><?php if(in_array('75',$page_access)){echo $this->Html->link('Add Imprest Manager',array('controller'=>'Imprests','action'=>'imprest_manager_save','full_base' => true));} ?></li>
                                                <li><?php if(in_array('131',$page_access)){echo $this->Html->link('Payment Processing Vendor/Imprest',array('controller'=>'Imprests','action'=>'grn_payment','full_base' => true));} ?></li>
                                                <li><?php if(in_array('132',$page_access)){echo $this->Html->link('Payment Processing Salary',array('controller'=>'Imprests','action'=>'grn_payment_salary','full_base' => true));} ?></li>
                                                </ul>
                                            </li>
					</ul>
				</li>
                                
			<?php } ?>
                        <?php if(
                                 in_array('67', $page_access) || in_array('64', $page_access) || in_array('71', $page_access) || in_array('77', $page_access) ||
                                 in_array('69', $page_access) || in_array('66', $page_access) || in_array('72', $page_access) || in_array('83', $page_access) ||
                                 in_array('85', $page_access) || in_array('122', $page_access) || in_array('117', $page_access) || in_array('120', $page_access) ||
                                 in_array('121', $page_access) || in_array('124', $page_access)
                                 )
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Finance Reports</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('67',$page_access)){echo $this->Html->link('View Budget',array('controller'=>'ExpenseReports','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('69',$page_access)){echo $this->Html->link('GRN Report',array('controller'=>'ExpenseReports','action'=>'imprest_report_breakup','full_base' => true));} ?></li>
                                                <li><?php if(in_array('69',$page_access)){echo $this->Html->link('GRN Report  Process Wise',array('controller'=>'ExpenseReports','action'=>'imprest_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('117',$page_access)){echo $this->Html->link('GRN Voucher Report',array('controller'=>'ExpenseReports','action'=>'grn_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('117',$page_access)){echo $this->Html->link('GRN Imprest Voucher',array('controller'=>'GrnReports','action'=>'grn_imprest_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('117',$page_access)){echo $this->Html->link('GRN Filing Report',array('controller'=>'GrnReports','action'=>'file_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('122',$page_access)){echo $this->Html->link('Invoice Voucher Report',array('controller'=>'GrnReports','action'=>'inv_vch_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('120',$page_access)){echo $this->Html->link('GRN Reject Report',array('controller'=>'GrnReports','action'=>'grn_reject_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('121',$page_access)){echo $this->Html->link('P&L ProcessWise',array('controller'=>'GrnReports','action'=>'pnl_revenue_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('71',$page_access)){echo $this->Html->link('P&L Report',array('controller'=>'ExpenseReports','action'=>'pnl_report','full_base' => true));} ?></li>
                                                <li><?php if(in_array('72',$page_access)){echo $this->Html->link('Imprest Detail',array('controller'=>'ExpenseReports','action'=>'imprest_detail','full_base' => true));} ?></li>
                                                <li><?php if(in_array('83',$page_access)){echo $this->Html->link('Imprest Report',array('controller'=>'ExpenseReports','action'=>'imprest_report2','full_base' => true));} ?></li>
                                                
                                                <li><?php if(in_array('85',$page_access)){echo $this->Html->link('Export TDS',array('controller'=>'ExpenseReports','action'=>'view_tds','full_base' => true));} ?></li>
                                                <li><?php if(in_array('85',$page_access)){echo $this->Html->link('Section Wise TDS',array('controller'=>'ExpenseReports','action'=>'view_section_tds','full_base' => true));} ?></li>
					</ul>
				</li>
                                
			<?php } ?> 
                                <?php if(
                                 in_array('139', $page_access) || in_array('140', $page_access) 
                                 )
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Salary Master</span>
					</a>
					<ul class="dropdown-menu">
                                            <li><?php if(in_array('140',$page_access)){echo $this->Html->link('Salary Head',array('controller'=>'SalaryHeads','action'=>'index','full_base' => true));} ?></li>
                                            <li><?php if(in_array('139',$page_access)){echo $this->Html->link('Salary Upload',array('controller'=>'GrnReports','action'=>'salary_upload','full_base' => true));} ?></li>
                                            <li><?php if(in_array('140',$page_access)){echo $this->Html->link('Salary Voucher Report',array('controller'=>'GrnReports','action'=>'salary_vch_report','full_base' => true));} ?></li>
                                            <li><?php if(in_array('140',$page_access)){echo $this->Html->link('Salary Proportionate',array('controller'=>'SalaryHeads','action'=>'proportionate_cost_distribution','full_base' => true));} ?></li>
					</ul>
				</li>
                                
			<?php } ?> 
                                
                                <?php if($this->Session->read('userid')=='19' || $this->Session->read('userid')=='17' || $this->Session->read('userid')=='20' || $this->Session->read('userid')=='16')
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Book Details</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link('Day Book Upload',array('controller'=>'Books','action'=>'index','full_base' => true)); ?></li>
                                                <li><?php echo $this->Html->link('Day Book Export',array('controller'=>'Books','action'=>'export1','full_base' => true)); ?></li> 
                                                 <li><?php echo $this->Html->link('Day Book BreakUp Export',array('controller'=>'Books','action'=>'export12','full_base' => true)); ?></li>
                                                <li><?php echo $this->Html->link('Day Book summary',array('controller'=>'Books','action'=>'export11','full_base' => true)); ?></li> 
                                                <li><?php echo $this->Html->link('Day Book Status Update',array('controller'=>'Books','action'=>'save_status','full_base' => true)); ?></li> 
                                                 <li><?php echo $this->Html->link('Create Fund Flow',array('controller'=>'Books','action'=>'fundflow','full_base' => true)); ?></li> 
							<li><?php echo $this->Html->link('View Fund Flow',array('controller'=>'Books','action'=>'viewfundflow','full_base' => true)); ?></li>                                                
                                               
					</ul>
				</li> 
                                
			<?php } ?>

			 <?php if(
                                 in_array('93', $page_access) || in_array('94', $page_access) || in_array('95', $page_access) || in_array('96', $page_access) ||
                                 in_array('97', $page_access) || in_array('98', $page_access) || in_array('99', $page_access) || in_array('100', $page_access) ||
                                 in_array('101', $page_access) ||
                                 in_array('102', $page_access) || in_array('103', $page_access) || in_array('104', $page_access) || in_array('105', $page_access) ||
                                 in_array('106', $page_access) || in_array('107', $page_access)|| in_array('108', $page_access)
                                 )
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">HR Management</span>
					</a>
					<ul class="dropdown-menu">
                                                <li><?php if(in_array('93',$page_access)){ echo $this->Html->link('JCLR Entry',array('controller'=>'Jclrs','action'=>'index','full_base' => true)); }?></li>
                                                 <li><?php if(in_array('116',$page_access)){ echo $this->Html->link('Appointment Letter',array('controller'=>'Jclrs','action'=>'appointment_letter','full_base' => true)); }?></li>
                                                 
                                            
                        <li><?php if(in_array('94',$page_access)){ echo $this->Html->link('Upload Attendance',array('controller'=>'Attendances','action'=>'index','full_base' => true)); } ?></li>	
			<li><?php if(in_array('95',$page_access)){ echo $this->Html->link('Salary Process',array('controller'=>'Attendances','action'=>'salaryprocess','full_base' => true));} ?></li>			
				                        <li><?php if(in_array('96',$page_access)){ echo $this->Html->link('Export Salary',array('controller'=>'Attendances','action'=>'exportsalary','full_base' => true));} ?></li>  
                        <li><?php if(in_array('97',$page_access)){ echo $this->Html->link('Incentive Entry',array('controller'=>'Attendances','action'=>'typeformat','full_base' => true)); } ?></li>
						<li><?php if(in_array('98',$page_access)){ echo $this->Html->link('Salary Slip',array('controller'=>'Attendances','action'=>'salaryslip','full_base' => true));} ?></li>					                      
                                                <li><?php if(in_array('99',$page_access)){ echo $this->Html->link('Export Incentive',array('controller'=>'Attendances','action'=>'exportincentive','full_base' => true));} ?></li>
 <li><?php if(in_array('100',$page_access)){ echo $this->Html->link('Export Leave',array('controller'=>'Attendances','action'=>'exportleave','full_base' => true)); } ?></li>
 <li><?php if(in_array('101',$page_access)){ echo $this->Html->link('Save Attendance File',array('controller'=>'Attendances','action'=>'savefile','full_base' => true)); } ?></li>					                      
 <li><?php if(in_array('102',$page_access)){ echo $this->Html->link('Douwnload Attendance File',array('controller'=>'Attendances','action'=>'showfile','full_base' => true));} ?></li>
 <li><?php if(in_array('103',$page_access)){ echo $this->Html->link('Salary BreakUp',array('controller'=>'Jclrs','action'=>'view','full_base' => true)); } ?></li>
  <li><?php if(in_array('104',$page_access)){ echo $this->Html->link('Employee Details',array('controller'=>'Jclrs','action'=>'viewdoc','full_base' => true));} ?></li>
 <li><?php if(in_array('105',$page_access)){ echo $this->Html->link('Discard Attendances',array('controller'=>'Attendances','action'=>'discardsalary','full_base' => true)); } ?></li>
 <li><?php if(in_array('106',$page_access)){ echo $this->Html->link('Discard Incentive',array('controller'=>'Attendances','action'=>'discardincentive','full_base' => true));} ?></li>
 <li><?php if(in_array('107',$page_access)){ echo $this->Html->link('Export JCLR Data',array('controller'=>'Jclrs','action'=>'exportjclr','full_base' => true));} ?></li>
 <li><?php if(in_array('108',$page_access)){ echo $this->Html->link('Account Validation',array('controller'=>'Jclrs','action'=>'account','full_base' => true));} ?></li>
					</ul>
				</li> 
                                
			<?php } ?>   
                                
                                
                                 <?php if(
                                 in_array('86', $page_access) || in_array('87', $page_access) ||
                                 in_array('88', $page_access) || in_array('89', $page_access)|| in_array('91', $page_access)
                                 )
                                  
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">IT Assets</span>
					</a>
					<ul class="dropdown-menu">
						<li><?php if(in_array('86',$page_access)){echo $this->Html->link('Connectivity Details',array('controller'=>'Connectivities','action'=>'index','full_base' => true));} ?></li>
                                                <li><?php if(in_array('87',$page_access)){echo $this->Html->link('Mobile Details',array('controller'=>'Connectivities','action'=>'mobile','full_base' => true));} ?></li>
                                                <li><?php if(in_array('88',$page_access)){echo $this->Html->link('Hardware Details',array('controller'=>'Connectivities','action'=>'save_doc','full_base' => true));} ?></li>
                                                <li><?php if(in_array('89',$page_access)){echo $this->Html->link('IT Assets Reports',array('controller'=>'Connectivities','action'=>'export1','full_base' => true));} ?></li>
                                                 <li><?php if(in_array('91',$page_access)){echo $this->Html->link('IT View',array('controller'=>'Connectivities','action'=>'view','full_base' => true));} ?></li>
						
					</ul>
				</li>
                                
			<?php } ?>
                        <?php if(in_array('90',$page_access))
                                 { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-pencil-square-o"></i>
						 <span class="hidden-xs">Prospect Management</span>
					</a>
					<ul class="dropdown-menu">
                                                <?php if(in_array('90',$page_access) ||  in_array('109',$page_access)) { ?><li><?php echo $this->Html->link('Add Product',array('controller'=>'prospects','action'=>'index','full_base' => true)); ?></li> <?php } ?>
                        <?php if(in_array('90',$page_access) ||  in_array('110',$page_access)) { ?><li><?php echo $this->Html->link('Create Prospect',array('controller'=>'prospects','action'=>'save_sales','full_base' => true)); ?></li><?php } ?>
			<?php if(in_array('90',$page_access) ||  in_array('112',$page_access)) { ?><li><?php echo $this->Html->link('View Prospect',array('controller'=>'prospects','action'=>'view_sales','full_base' => true)); ?></li><?php } ?>
				                       
                        <?php if(in_array('90',$page_access) ||  in_array('111',$page_access)) { ?><li><?php echo $this->Html->link('Approve Prospect',array('controller'=>'prospects','action'=>'view_approve_sales','full_base' => true)); ?></li><?php } ?> 
                        <?php if(in_array('90',$page_access) ||  in_array('110',$page_access)) { ?><li><?php echo $this->Html->link('Follow Up',array('controller'=>'prospects','action'=>'view_follow','full_base' => true)); ?></li><?php } ?> 
                        <?php if(in_array('90',$page_access) ||  in_array('114',$page_access)) { ?><li><?php echo $this->Html->link('User Management',array('controller'=>'Ecrs','action'=>'index','full_base' => true)); ?></li> <?php } ?> 
                        <?php if(in_array('90',$page_access) ||  in_array('115',$page_access)) { ?><li><?php echo $this->Html->link('Prospect Report',array('controller'=>'prospects','action'=>'view_report','full_base' => true)); ?></li><?php } ?>  
						
					</ul>
				</li> 
                                
			<?php } ?>   
                                
                                
                                
                                
                                
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="material-icons">settings</i>
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
                                    <li><?php echo $this->Html->link('User Management',array('controller'=>'Acces','action'=>'index','full_base' => true));?></li>
                                <?php } ?>
                            </ul>
                        </li>
                                
                        
                                
                                
                                
                                
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

<script type="text/javascript" src="<?php echo $this->webroot;?>newstyle/application.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>newstyle/material-icon.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>newstyle/newstyle.css"/>

