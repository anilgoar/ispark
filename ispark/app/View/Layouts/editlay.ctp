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
		
		echo $this->Html->script('js/datetimepicker_css');
		echo $this->Html->script('validate');
		//echo $this->Html->script('js/devoops.min');
	?>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
</head>
<body >



<!-- Page Access Violation-->
<div id="main"   >
	<div class="row">
		
		<!--Start Content-->
		<div  class="col-xs-12 col-sm-12">
			<div >
			
						
						<?php echo $this->fetch('content'); ?>
			
			</div>
			<div id="ajax-content"></div>
		</div>
		<!--End Content-->
	</div>
</div>

</body>
</html>
