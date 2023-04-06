<?php
mysql_connect("localhost", "root", "vicidialnow")or die("cannot connect");
mysql_select_db("db_bill")or die("cannot select DB");    
$empqry = mysql_query("SELECT * FROM `qual_employee` WHERE EmpCode='{$_REQUEST['Empcode']}' LIMIT 1");
$empArr =mysql_fetch_assoc($empqry);

?>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php echo include_once('style.php');?>
</head>
<body>
<?php echo include_once('page1.php');?>
<?php echo include_once('page2.php');?>
</body>
</html>

