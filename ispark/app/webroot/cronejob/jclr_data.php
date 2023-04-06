<?php
set_time_limit(0);
ini_set('display_error',1);

$con = mysql_connect("localhost",'root','vicidialnow');
$db = mysql_select_db("db_bill", $con);

$Fileurl = "http://bpsmis.ind.in/test.aspx?RequestType=JCLR&dtDate=15-Dec-2016";
$GetFile = file_get_contents("$Fileurl") or die("Failed to connect");

if($GetFile!='Failed to connect')
{

$SaveFile = file_put_contents("jclr.xls",$GetFile) or die("Faild to save"); 
}

$myfile = fopen("jclr.xls", "r") or die("Unable to open file!");
$AA = fread($myfile,filesize("jclr.xls"));

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
		'SrNo','EmpCode','EmpType','EmpName','Fname','Gender','DOB','DOJ','Desig','Depart','Stream','Process','Profile','Location','SubLocation','Qualification','MaritalStatus','BloodG','PAddress','PCity','PState','PpinCode','TAddress','TCity','TState','TPinCode','PMobNo','PLandLine','TMobNo','TLandLine','EmailId','documentDone','CTCOffered','AcNo','AcBank','AcBranch','PassPortNo','dlNo','EpfNo','EsiNo','EntryDate','Status','LeftDate','LeftRmks','EmpCodeDate','Pwd','Age','bs','hra','conv','da','portf','ma','lta','mob','sa','oa','panno','NewEpfNo','pfelig','esielig','moballow','mno','portfolio','nom1','nom2','dispens','remarks','CreateDate','EpfDate','Band','lastUpdated','BiometricCode','ClientName','CostCenter','EmpFor','UpdatedBy','IFSCCode','AccHolder','AccType','OfferNo','package','Bonus','Gross','ESIC','EPF','NetInHand','EPFCO','ESICCO','Gratuity','ProfessionalTax','AccountFlag','Title','EsicNo','AppointPrintDate','PayMode','AcValidationDate','AcValidatedBy','AdminCharges','SourceType','Source','BoxFileNo','AcRejectionRemarks','KPIId','AssignDate','RType','SalaryPaymentMode','AadharID','PLI','OfficialEmailID','UAN'
	),", '', $AA);
$AA = "insert into tmp_employee_master (SrNo,EmpCode,EmpType,EmpName,Fname,Gender,DOB,DOJ,Desig,Depart,Stream,Process,Profile,Location,SubLocation,Qualification,MaritalStatus,BloodG,PAddress,PCity,PState,PpinCode,TAddress,TCity,TState,TPinCode,PMobNo,PLandLine,TMobNo,TLandLine,EmailId,documentDone,CTCOffered,AcNo,AcBank,AcBranch,PassPortNo,dlNo,EpfNo,EsiNo,EntryDate,Status,LeftDate,LeftRmks,EmpCodeDate,Pwd,Age,bs,hra,conv,da,portf,ma,lta,mob,sa,oa,panno,NewEpfNo,pfelig,esielig,moballow,mno,portfolio,nom1,nom2,dispens,remarks,CreateDate,EpfDate,Band,lastUpdated,BiometricCode,ClientName,CostCenter,EmpFor,UpdatedBy,IFSCCode,AccHolder,AccType,OfferNo,package,Bonus,Gross,ESIC,EPF,NetInHand,EPFCO,ESICCO,Gratuity,ProfessionalTax,AccountFlag,Title,EsicNo,AppointPrintDate,PayMode,AcValidationDate,AcValidatedBy,AdminCharges,SourceType,Source,BoxFileNo,AcRejectionRemarks,KPIId,AssignDate,RType,SalaryPaymentMode,AadharID,PLI,OfficialEmailID,UAN)values".$AA;

$AAA = mysql_query($AA) or die(mysql_error());

$updData = "UPDATE employee_master em INNER JOIN tmp_employee_master tem SET em.SrNo=tem.SrNo,em.EmpCode=tem.EmpCode,em.EmpType=tem.EmpType,em.EmpName=tem.EmpName,em.Fname=tem.Fname,em.Gender=tem.Gender,em.DOB=tem.DOB,em.DOJ=tem.DOJ,em.Desig=tem.Desig,em.Depart=tem.Depart,em.Stream=tem.Stream,em.Process=tem.Process,em.Profile=tem.Profile,em.Location=tem.Location,em.SubLocation=tem.SubLocation,em.Qualification=tem.Qualification,em.MaritalStatus=tem.MaritalStatus,em.BloodG=tem.BloodG,em.PAddress=tem.PAddress,em.PCity=tem.PCity,em.PState=tem.PState,em.PpinCode=tem.PpinCode,em.TAddress=tem.TAddress,em.TCity=tem.TCity,em.TState=tem.TState,em.TPinCode=tem.TPinCode,em.PMobNo=tem.PMobNo,em.PLandLine=tem.PLandLine,em.TMobNo=tem.TMobNo,em.TLandLine=tem.TLandLine,em.EmailId=tem.EmailId,em.documentDone=tem.documentDone,em.CTCOffered=tem.CTCOffered,em.AcNo=tem.AcNo,em.AcBank=tem.AcBank,em.AcBranch=tem.AcBranch,em.PassPortNo=tem.PassPortNo,em.dlNo=tem.dlNo,em.EpfNo=tem.EpfNo,em.EsiNo=tem.EsiNo,em.EntryDate=tem.EntryDate,em.Status=tem.Status,em.LeftDate=tem.LeftDate,em.LeftRmks=tem.LeftRmks,em.EmpCodeDate=tem.EmpCodeDate,em.Pwd=tem.Pwd,em.Age=tem.Age,em.bs=tem.bs,em.hra=tem.hra,em.conv=tem.conv,em.da=tem.da,em.portf=tem.portf,em.ma=tem.ma,em.lta=tem.lta,em.mob=tem.mob,em.sa=tem.sa,em.oa=tem.oa,em.panno=tem.panno,em.NewEpfNo=tem.NewEpfNo,em.pfelig=tem.pfelig,em.esielig=tem.esielig,em.moballow=tem.moballow,em.mno=tem.mno,em.portfolio=tem.portfolio,em.nom1=tem.nom1,em.nom2=tem.nom2,em.dispens=tem.dispens,em.remarks=tem.remarks,em.CreateDate=tem.CreateDate,em.EpfDate=tem.EpfDate,em.Band=tem.Band,em.lastUpdated=tem.lastUpdated,em.BiometricCode=tem.BiometricCode,em.ClientName=tem.ClientName,em.CostCenter=tem.CostCenter,em.EmpFor=tem.EmpFor,em.UpdatedBy=tem.UpdatedBy,em.IFSCCode=tem.IFSCCode,em.AccHolder=tem.AccHolder,em.AccType=tem.AccType,em.OfferNo=tem.OfferNo,em.package=tem.package,em.Bonus=tem.Bonus,em.Gross=tem.Gross,em.ESIC=tem.ESIC,em.EPF=tem.EPF,em.NetInHand=tem.NetInHand,em.EPFCO=tem.EPFCO,em.ESICCO=tem.ESICCO,em.Gratuity=tem.Gratuity,em.ProfessionalTax=tem.ProfessionalTax,em.AccountFlag=tem.AccountFlag,em.Title=tem.Title,em.EsicNo=tem.EsicNo,em.AppointPrintDate=tem.AppointPrintDate,em.PayMode=tem.PayMode,em.AcValidationDate=tem.AcValidationDate,em.AcValidatedBy=tem.AcValidatedBy,em.AdminCharges=tem.AdminCharges,em.SourceType=tem.SourceType,em.Source=tem.Source,em.BoxFileNo=tem.BoxFileNo,em.AcRejectionRemarks=tem.AcRejectionRemarks,em.KPIId=tem.KPIId,em.AssignDate=tem.AssignDate,em.RType=tem.RType,em.SalaryPaymentMode=tem.SalaryPaymentMode,em.AadharID=tem.AadharID,em.PLI=tem.PLI,em.OfficialEmailID=tem.OfficialEmailID,em.UAN=tem.UAN
 WHERE em.EmpCode=tem.EmpCode";

$rscData = mysql_query($updData);


$delduplicate = "DELETE FROM tmp_employee_master USING tmp_employee_master INNER JOIN employee_master WHERE employee_master.EmpCode=tmp_employee_master.EmpCode";
$delfinal     = mysql_query($delduplicate);

$Insert  = "insert into employee_master select * from tmp_employee_master";
$RscIns   = mysql_query($Insert);

print_r($AA);
fclose($myfile);

?>