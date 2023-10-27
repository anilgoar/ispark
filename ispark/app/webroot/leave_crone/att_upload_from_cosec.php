<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');

$cosectserverName = "172.10.10.146\\sqlexpress";
$cosecdbInfo = array( "Database"=>"COSEC","UID"=>"su","PWD"=>"matrix_1");
$conn = sqlsrv_connect( $cosectserverName, $cosecdbInfo);
if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
//$con = sqlsrv_connect("172.10.10.146","su","matrix_1",'db_bill') or die("can not conncect");
//$Db = mysqli_select_db($con,"db_bill");




?>