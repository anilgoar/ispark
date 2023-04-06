<?php
set_time_limit(0);
ini_set('display_error',1);

$con = mysql_connect("localhost",'root','vicidialnow');
$db = mysql_select_db("db_bill", $con);

$Fileurl = "http://bpsmis.ind.in/test.aspx?RequestType=Document&dtDate=15-Dec-2016";
$GetFile = file_get_contents("$Fileurl") or die("Failed to connect");

if($GetFile!='Failed to connect')
{

$SaveFile = file_put_contents("document.xls",$GetFile) or die("Faild to save"); 
}

$myfile = fopen("document.xls", "r") or die("Unable to open file!");
$AA = fread($myfile,filesize("document.xls"));

$AA = str_replace('<table cellspacing="0" rules="all" border="1" style="border-collapse:collapse;">', '', $AA);

$AA = str_replace('</th><th>', "','", $AA);
$AA = str_replace('<th>', "'", $AA);
$AA = str_replace('</th>', "'", $AA);
$AA = str_replace('</td><td>', "','", $AA);
$AA = str_replace('</td>', "'", $AA);
$AA = str_replace('<td>', "'", $AA);
$AA = str_replace('</tr><tr>', "),(", $AA);
$AA = str_replace('<tr>', '(', $AA);
$AA = str_replace('</tr>', ')', $AA);
$AA = str_replace('&nbsp;', '', $AA);

$AA = strip_tags($AA);
$AA = str_replace("(
		'SrNo','EmpSrno','DocumentType','DocumentUploaded','SaveBy','SaveDate','DocumentName','Status','Remarks'
	),", '', $AA);
$AA = "insert into tmp_document_master (SrNo,EmpSrno,DocumentType,DocumentUploaded,SaveBy,SaveDate,DocumentName,Status,Remarks)values".$AA;

$AAA = mysql_query($AA) or die(mysql_error());

$updData = "UPDATE document_master em INNER JOIN tmp_document_master tem SET em.SrNo=tem.SrNo,em.EmpSrno=tem.EmpSrno,em.DocumentType=tem.DocumentType,em.DocumentUploaded=tem.DocumentUploaded,em.SaveBy=tem.SaveBy,em.SaveDate=tem.SaveDate,em.DocumentName=tem.DocumentName,em.Status=tem.Status,em.Remarks=tem.Remarks
 WHERE em.SrNo=tem.SrNo";

$rscData = mysql_query($updData);


$delduplicate = "DELETE FROM tmp_document_master USING tmp_document_master INNER JOIN document_master WHERE document_master.SrNo=tmp_document_master.SrNo";
$delfinal     = mysql_query($delduplicate);

$Insert  = "insert into document_master select * from tmp_document_master";
$RscIns   = mysql_query($Insert);



print_r($AA);
fclose($myfile);

?>