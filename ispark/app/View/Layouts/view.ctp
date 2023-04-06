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
		echo $this->fetch('keyword');
		echo $this->fetch('viewport');
		echo $this->Html->css('bootstrap/bootstrap');
		//echo $this->Html->css('css/style_v2');
		echo $this->Html->css('css/style_v1');
	?>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container-fluid">
	<div id="page-login" class="row">
			<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="box">
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>