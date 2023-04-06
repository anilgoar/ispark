<?php



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
        <script>
            document.addEventListener("contextmenu", function(e){
    e.preventDefault();
}, false);
        </script>
</head>
<body oncopy="return false" oncut="return false" onpaste="return false" >
<?php echo $this->fetch('content'); ?> 
</body>
</html>