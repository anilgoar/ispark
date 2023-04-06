<?php
$string=$ProductName;
$num=40;
$length = strlen($string);
$output[0] = substr($string, 0, $num);
$output[1] = substr($string, $num, $length );

?>

<html>
<head>
<style>
.pic1{
    background-image: url("<?php $webroot?>img/pdfimg/P1.jpg");   
    background-repeat: no-repeat;
    width:713px;
    height:900px;
    color:white;
    margin-left:7px; 
}
</style>
</head>
<body> 
    <div class="pic1" >
        <div style="margin-left:65%;margin-top:132px;">
            <p style="width:240px;"><?php echo $ProductName;?></p>
            <p><strong>To</strong></p>
            <p style="width:240px;"><?php echo $ClientName;?></p>
            <p><img src="<?php $webroot?>prospect_file/<?php echo "$Id/".$ProductLogo;?>" style="width:150px;" height="100px;" ></p>
            <p style="width:240px;"><?php echo $ProductAdd;;?></p>
        </div>
    </div>
    <div style="margin-left:7px;" >
        <img src="<?php echo $webroot?>img/pdfimg/P2.jpg" >
        <img src="<?php echo $webroot?>img/pdfimg/P3.jpg" >
        <img src="<?php echo $webroot?>img/pdfimg/P4.jpg" >
        <img src="<?php echo $webroot?>img/pdfimg/P5.jpg" >
        <div><?php echo $Cover;?></div>
        <img src="<?php echo $webroot?>img/pdfimg/P8.jpg" style=" width:713px;height:900px;" >
    </div>
</body>
</html>
