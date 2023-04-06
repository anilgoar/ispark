<?php
include("include/connection.php");
include("include/session-check.php");
$allocation=1;
$UserId         = $_SESSION['SESS_ID'];
$hid_agent_name = $_SESSION['SESS_NAME'];
$Id   		= base64_decode($_GET['id']);
$prof	= base64_decode($_GET['prof']);	
$PostId         = $_POST['PostId'];
$Msisdn         = $_GET['msisdn']; 
$Atemp          = base64_decode($_GET['Atmp']);
$Atemp1         = base64_decode($_GET['Atmp'])=='0'?"":" and CallStatus!='Contacted'";
$Attem3         = " AND (AgentId2 is null or AgentId2='$UserId')";
$Lang           = " AND Languages in ('hindi','english','bengali')";
$AtmpSrch       = $_GET['AtmpSrch'];
$Allocat        = $_GET['Allocat'];
$StatusX       = base64_decode($_GET['StatusX']);
$UserIdSch = $_POST['UserId'];



if($prof == 'Incomplete Profile')
{
    $profiling = "ProfilePercentage < 100";
}
elseif ($prof == 'Incomplete Profile') {
$profiling = "ProfilePercentage = 100";
}
if($Allocat == 'Incomplete Profile')
{
    $profiling = "ProfilePercentage < 100";
}
elseif ($Allocat == 'Incomplete Profile') {
$profiling = "ProfilePercentage = 100";
}

if($Atemp=='1')
{
  $TimeAttemptValidation =   " AND IF(DATE(CallDate)=CURDATE() && HOUR(TIMEDIFF(NOW(),CallDate))>5,TRUE,IF(DATEDIFF(CURDATE(),DATE(CallDate))>1,TRUE,FALSE))";
}
else if($Atemp=='2')
{
  $TimeAttemptValidation =   " AND IF(DATEDIFF(CURDATE(),DATE(CallDate))>2,TRUE,FALSE)";
}
else if($Atemp=='3')
{
  $TimeAttemptValidation =   " AND IF(DATEDIFF(CURDATE(),DATE(CallDate))>2,TRUE,FALSE)";
}



if($Id)
{
        $msSel = "select * from allocation_master where Id='$Id' limit 1"; 
	$msRsc = mysql_query($msSel);
	$msData = mysql_fetch_array($msRsc);
        $scholarSelect = "SELECT *,IF(CURDATE()>STR_TO_DATE(deadlineDate,'%d-%m-%Y'),0,1) stat FROM customer_relation cr INNER JOIN scholorship_details sd ON cr.nid = sd.nid  WHERE UserId='".$msData['UserId']."' and date(STR_TO_DATE(deadlineDate,'%d-%m-%Y'))>=curdate() order by STR_TO_DATE(deadlineDate,'%d-%m-%Y') ASC";
        $ExeScholar = mysql_query($scholarSelect);
        $scholarSelect1 = "SELECT *,IF(CURDATE()>STR_TO_DATE(deadlineDate,'%d-%m-%Y'),0,1) stat FROM customer_relation cr INNER JOIN scholorship_details sd ON cr.nid = sd.nid  WHERE UserId='".$msData['UserId']."' and date(STR_TO_DATE(deadlineDate,'%d-%m-%Y'))<curdate() order by STR_TO_DATE(deadlineDate,'%d-%m-%Y') DESC";
        $ExeScholar1 = mysql_query($scholarSelect1);
//        if(!mysql_num_rows($ExeScholar))
//        {
//            $scholarSelect = "SELECT *,IF(CURDATE()>STR_TO_DATE(deadlineDate,'%d-%m-%Y'),0,1) stat FROM scholorship_details sd order by title";
//            $ExeScholar = mysql_query($scholarSelect);
//        }
/*
        $his = "Select am.Branch,am.ImportDate,td.CallDate,td.CallStatus,
Issue1,Issue2,Issue3,
sc_title,Remark,
sc_title2,Remark2,
sc_title3,Remark3,
sc_title4,Remark4,
sc_title5,Remark5,
sc_title6,Remark6 from allocation_master am inner join tagged_data td on am.Id=td.DataId where am.UserId='".$msData['UserId']."'";
    $hisRsc = mysql_query($his);
 $Count  = mysql_num_fields($hisRsc); 
 */
 $CBALLQry = "SELECT FirstName,MSISDN, DATE_FORMAT(CallBackDate,'%d-%b-%Y %h:%i %p') CallBackTime FROM allocation_master WHERE DATE(CallBackDate) = CURDATE() AND AgentId='$UserId' ";
 $CBAllRsc = mysql_query($CBALLQry); 
  
}

if($Msisdn)
{    
	$Sel1 = "SELECT * FROM allocation_master WHERE $profiling And MSISDN='$Msisdn' $Lang ";  
	$Rsc1 = mysql_query($Sel1);
	$Data1 = mysql_fetch_array($Rsc1);
        
	//header("location:test.php?id=".base64_encode($Data1['Id'])."&Aloo=".base64_encode($Data1['AllocationId'])."&Atmp=".base64_encode($AtmpSrch)."");
	header("location:test.php?id=".base64_encode($Data1['Id'])."&prof=".base64_encode('Incomplete Profile')."&Atmp=".base64_encode($AtmpSrch)."&StatusX=".base64_encode($StatusX1)."");
}
//$HistoryMsisdn = $msData[$MobileFiled]==''?$Msisdn:$msData[$MobileFiled];
//print_r($_POST);
if($_POST['Save'])
{
$ContactStatus  = addslashes($_POST['CallStatus']);

$Dispo1         = addslashes($_POST['Dispo1']);
$Dispo2 	= addslashes($_POST['Dispo2']);
$Dispo3         = addslashes($_POST['Dispo3']);
$Dispo4         = addslashes($_POST['Dispo4']);
$actionType     = explode("_",$_POST['actionType']);
$check          = $_POST['check'];
$nidArr         = explode(",",$_POST['nidsArr']); 
$Fdate          = $_POST['Dispo3'];


    $SelectMsisdn = "Select MSISDN,CallStatus from allocation_master where Id='$PostId' limit 1";
    $rscMsisdn = mysql_fetch_row(mysql_query($SelectMsisdn));
    $MobileNo = '91'.$rscMsisdn[0];
    $Selx = "INSERT INTO tagged_data SET DataId='$PostId',AllocationId='$allocation',AgentId='$UserId',CallDate=now(),CallBackDate='$Fdate',CallStatus='$ContactStatus',Issue1='$Issue1',Issue2='$Issue2',Issue3='$Issue3',Dispo1='$Dispo1',Dispo2='$Dispo2',Dispo3='$Dispo3',Dispo4='$Dispo4',CallTag='Outbound',Branch='NOIDA'"; 
    $Rscx = mysql_query($Selx);
    $LastId = mysql_insert_id();
    $flag = true;
    
    foreach($nidArr as $chk)
    {
        
        $Remarks = $_POST["remark".$chk];
        if(!empty($Remarks))
        {
        $SelY = mysql_fetch_row(mysql_query("Select title from scholorship_details where nid='$chk' limit 1"));
        
        $title = addslashes($SelY[0]);
       $Selx = "update tagged_data SET  nid$n='$chk',sc_title$n='$title', Remark$n='$Remarks' where id='$LastId'"; 
        $Rscx = mysql_query($Selx);
        //print_r($SelY); exit;
        if($flag)
        {
            $n=1;
            $flag =false;
        }
        $n++;
        } 
    }
     
    ///////////////////// check validate to close the case or not   //////////////////////////////////
    $validation = "SELECT SUM(IF(td.CallStatus='Contacted',1,0)) Contacted,
SUM(IF(td.CallStatus='Not Contacted',1,0)) NotContacted,
SUM(IF(td.CallStatus='Call Back',1,0)) CallBack
 FROM `allocation_master` am INNER JOIN `tagged_data` td ON am.Id = td.DataId
WHERE UserId = '$UserIdSch'";
    
    $rscValidation = mysql_query($validation);
    $countValidation = $rscValidation['Contacted']+$rscValidation['CallBack'];
    
    $CaseStatus = 0;
    
    if($countValidation>='2')
    {
        $CaseStatus = 1;
    }
    /////////////////// validation Completed
        
    $ForCallback = $Disposition=='Call Back'?'1':'0';

    $Upd = "UPDATE allocation_master SET LastId='$LastId',CallCount=CallCount+1,CallStatus='$ContactStatus',ForCallBack='$ForCallback',CallBackDate='$Fdate',AgentId='$UserId',CallDate=now(),CaseStatus='$CaseStatus' WHERE id='$PostId'";
    $UpdRsc = mysql_query($Upd);
    
    if($Fdate=='date')
    {
        $Fdate = $_POST[$actionType[1]];
        $updCallBack = "UPDATE allocation_master SET CallStatus='Call Back', ForCallBack='$ForCallback',Priority='1',AgentId2='$UserId' Where id='$PostId'";
        $updCallBackRsc = mysql_query($updCallBack);
    }
    else if($actionType[0]=='MobileNumber')
    {
        $AltMsisdn = $_POST[$actionType[1]];
        $ins = mysql_query("INSERT INTO allocation_master(AllocationId,Allocation,ImportDate,UserId,MSISDN,firstName,lastName,emails,mobileNo,altNumber,altNumber2,BirthDate,
       FamilyIncome,Languages,Branch,MissingFields,ProfilePercentage,Priority,AgentId2)
       SELECT AllocationId,Allocation,ImportDate,UserId,'$AltMsisdn',firstName,lastName,emails,mobileNo,altNumber,'$AltMsisdn',BirthDate,
       FamilyIncome,Languages,Branch,MissingFields,ProfilePercentage,'1','$UserId' FROM allocation_master WHERE id='$PostId'");
    }
    
	
    if($UpdRsc)
    {
        
                
        
        $dispose = http_build_query(
    array(
            'source'=>'test',
            'user'=>'admin',
            'pass'=>'vicidialnow',
            'agent_user'=>$hid_agent_name,
            'function'=>'external_status',
            'value'=>'A',
            'phone_code'=>'1',
            'search'=>'no',
            'preview'=>'NO',
            'focus'=>'no'
       )
    );
$pause = http_build_query(
    array(
            'source'=>'test',
            'user'=>'admin',
            'pass'=>'vicidialnow',
            'agent_user'=>$hid_agent_name,
            'function'=>'external_pause',
            'value'=>'PAUSE',
            'phone_code'=>'1',
            'search'=>'no',
            'preview'=>'NO',
            'focus'=>'no'
       )
    );


    $opts1 = array('http' =>
    array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $dispose
    )
    );
    
    $opts2 = array('http' =>
    array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $pause
    )
    );
    
    
    
            $context1 = stream_context_create($opts1);
            $result1  = file_get_contents("http://192.168.0.9/agc/api.php",false,$context1);   
            $context2 = stream_context_create($opts2);
            $result2  = file_get_contents("http://192.168.0.9/agc/api.php",false,$context2);   
            
            
             $Selx1 = "select * from allocation_master where  $profiling AND CaseStatus=0 and DATEDIFF(CURDATE(),DATE(Importdate))>13 AND AgentId2 is null  AND Priority=1 AND ScholorStatus=0 and CallCount='$Atemp' $TimeAttemptValidation $Atemp1 $Attem3 $Lang limit 1"; 
            $Rscx1 = mysql_query($Selx1);
            $Datax1 = mysql_fetch_array($Rscx1);
            if(empty($Datax1))
            {
                if($Atemp != '0')
                {
                    $Selx1 = "SELECT * FROM allocation_master WHERE $profiling  AND CaseStatus=0 and DATEDIFF(CURDATE(),DATE(Importdate))>13  AND ScholorStatus=0 and  CallCount='$Atemp' $Attem3 $Atemp1 $Lang $TimeAttemptValidation limit 1";
                }
                else 
                {
                 $Selx1 = "SELECT * FROM allocation_master WHERE $profiling AND CaseStatus=0 and DATEDIFF(CURDATE(),DATE(Importdate))>13 AND ScholorStatus=0 and CallCount='$Atemp' $Atemp1 $Attem3 $Lang $TimeAttemptValidation limit 1"; 
                }

                $Rscx1 = mysql_query($Selx1);
                $Datax1 = mysql_fetch_array($Rscx1);
            }
        
        
        if($Datax1['Id'])
        {
            $upd = mysql_query("update allocation_master set AgentId2='$UserId' Where Id='{$Datax1['Id']}'");
            header("location:test.php?id=".base64_encode($Datax1['Id'])."&prof=".base64_encode('Incomplete Profile')."&Atmp=".base64_encode($Atemp)."&StatusX=".base64_encode($StatusX)."");
            $_SESSION['msg']= "Data saved successfully";
            exit;
        }
        else
        {	
            header("location:agent-login.php?msg=unsucc");
            $_SESSION['msg']= "Data Not saved successfully";
            exit;
        }
    }
}	
?>
<!doctype html>
<html lang="en">
<head>
     <style>
        .pointer{cursor: pointer}
        .showRow{display: block}
        .hideRow{display:none}
    </style>
    <style>
body {font-family: "Lato", sans-serif;}

/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
	<meta charset="utf-8"/>
	<title><?php echo $title;?></title>
    <?php include("element/css_js.php"); ?>
	
        <script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
function CallToCallBack(val)
{
  document.getElementById('SrchMsisdn').value=val; 
}
</script>
	<script language="JavaScript" type="text/javascript">
            
<!-- AJAX Object -->
var XMLHttpFactories = [
    function () {return new XMLHttpRequest()},
    function () {return new ActiveXObject("Msxml2.XMLHTTP")},
    function () {return new ActiveXObject("Msxml3.XMLHTTP")},
    function () {return new ActiveXObject("Microsoft.XMLHTTP")}
];

function createXMLHTTPObject() {
    var xmlhttp = false;
    for (var i=0;i<XMLHttpFactories.length;i++) {
        try {
            xmlhttp = XMLHttpFactories[i]();
        }
        catch (e) {
            continue;
        }
        break;
    }
    return xmlhttp;
}
<!-- AJAX Object -->
function apicall(val)
{
    agent_user = document.getElementById('hid_agent_name').value;
	phone =   document.getElementById('phone').value;
     dialurl=    "source=test&user=admin&pass=vicidialnow&agent_user="+agent_user+"&function=external_dial&value="+phone+"&phone_code=1&search=no&preview=NO&focus=no";
	dropurl=  "source=test&user=admin&pass=vicidialnow&agent_user="+agent_user+"&function=external_hangup&value=1";
	 pauseurl="source=test&user=admin&pass=vicidialnow&agent_user="+agent_user+"&function=external_pause&value=PAUSE";
     resumeurl  ="source=test&user=admin&pass=vicidialnow&agent_user="+agent_user+"&function=external_pause&value=RESUME";

  if(val=='Status')
    { 
		var data = '';
		
		var dispos 	    = document.getElementById('select_status').value;
		var disposarr   = dispos.split('#');
		var disposition = disposarr[0]; 
		
		if(disposition == 'callback')
		{
			data = 'CALLBK';	
		}
		else if(disposition == 'open')
		{
			data = 'N';	
		}
		else if(disposition == 'close')
		{
			data = 'SALE';			
		}
		
        if(data!='')
	 	{
        	statusurl="source=test&user=admin&pass=vicidialnow&agent_user="+agent_user+"&function=external_status&value="+data;
	 	}
	 	else
	 	{
			alert("Invalid Status !");	
	 	}
    } 
    if(val=='Dial')
	{
       var  url = dialurl;
	} 
    else if(val=='Drop')
	{
        var  url = dropurl;
	}
	else if(val=='Pause')
        var  url = pauseurl;
    else if(val=='Resume')
        var  url = resumeurl;
    else if(val=='Status')
        var url = statusurl;

    var xmlhttp = createXMLHTTPObject();
	dial_query=url;//alert(url);
	xmlhttp.open('POST', 'api.php'); 
	xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	xmlhttp.send(dial_query); 
	xmlhttp.onreadystatechange = function() 
	{ 
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{
			Nactiveext = null;
			Nactiveext = xmlhttp.responseText;
			alert(xmlhttp.responseText);
			
		}
	}
}	
</script>

<script type="text/javascript">
function SerachData1()
{ 
//alert()
var Msisdn = document.getElementById('SrchMsisdn').value;
alert(Msisdn);
 window.location.href='test.php?msisdn='+Msisdn+'&AtmpSrch='+'<?php echo $Atemp?>'+'&Allocat='+'<?php echo $prof?>';	
//alert(ff);
}

function checkLogout()
{ 
	NewMsisdn = document.getElementById('phone').value;
	if(NewMsisdn!='')
	{
		window.location.href='logout.php';	
	}
	else
	{
		alert('Please Dispose the call before logout');	
	}
}

function ShowHis()
{
		document.getElementById('showhis').style.display='block';
		document.getElementById('hidetabs').style.display='block';
		document.getElementById('showtabs').style.display='none';
}

function HideHis()
{
	document.getElementById('showhis').style.display='none';
	document.getElementById('showtabs').style.display='block';
	document.getElementById('hidetabs').style.display='none';
}
</script>


<script type="text/javascript">
 function buildDisplayResults()
    {
         $.ajax({
             method: 'get',
             cache: false,
             url : 'run2minute.php?pkid=<?php echo $prof?>&atemp=<?php echo $Atemp?>',
             dataType : 'text',
             success: function (text) { $('#showresults').html(text); 
			 }
         });
    }
//setInterval(buildDisplayResults,10000);

function toggle(val) {
    $("#"+val).toggle();
}

function validate_sch()
{
  nids= document.getElementById("nidsArr").value;
  //alert(nids);
  var nidArr=nids.split(",");
  var str;
  var flag = false;
    
	for(i=0; i<nidArr.length; i++)
	{
            str="remark"+nidArr[i];
            
            if(document.getElementById(str).value!='')
            {
                
              flag=  true;
             
            }
	}
        if(!flag)
        {
            alert("Please Enter Remarks");
        }
        return flag;
}


</script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
	<?php include("element/header.php"); ?>

<div class="add-sub-header">
</div>
	    <div class="content-wrapper bgimg" >
        <section class="content-header" >
          <h1>
           Agent Selection Page For Calling<div id ="as"></div>
            <small></small>
          </h1>
        </section>
	        <div class="col-md-12">
              <!-- Horizontal Form -->
              <div class="box box-info">
		<div class="box-header with-border">
                    <h3 class="box-title">				  
                        <input type="button" name="History" value="Show History" class="btn btn-info" onClick="ShowHis()" id="showtabs">
                        <input type="button" name="History" value="Hide History" class="btn btn-info" onClick="HideHis()" id="hidetabs" style="display:none">
                    </h3>
                <div class="box-tools pull-right">
                    <input type="text" name="SrchMsisdn" id="SrchMsisdn" value="" class="form-horizontal">
                    <input type="button" name="SearchMs" value="Search" class="btn btn-info" onClick="SerachData1()">
                    <input type="button" name="Dial" value="Dial" class="btn btn-info" onClick="apicall('Dial')">
                    <input type="button" name="Drop" value="Drop" class="btn btn-info" onClick="apicall('Drop')">
                    <input type="button" name="Resume" value="Resume" class="btn btn-info" onClick="apicall('Resume')">
                    <input type="button" name="Pause" value="Pause" class="btn btn-info" onClick="apicall('Pause')"> 

                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
		
                  <div class="box-body">
                      <div class="tab">
  <button class="tablinks" onClick="openCity(event, 'Details')" id="defaultOpen">Details</button>
  <button class="tablinks" onClick="openCity(event, 'History')">History</button>
  <button class="tablinks" onClick="openCity(event, 'CallBack')">Call Back History</button>
  
</div>
                      
                      <div id="Details" class="tabcontent">  
                          <form name="frm" method="post" enctype="multipart/form-data" onSubmit="return ImportValidate();" class="form-horizontal">
                        
                        <div class="form-group">
                            <input type="hidden" name="phone" id="phone" value="<?php echo $msData['MSISDN']; ?>">
                            <input type="hidden" name="profil"  id="profil" value="<?php echo $prof;?>">
                            <input type="hidden" id="hid_agent_name" name="hid_agent_name" value="<?php echo $hid_agent_name; ?>" />
                            <input type="hidden" id="srch_alloc" name="srch_prof" value="<?php echo $prof; ?>" />
                            <label for="inputEmail3" class="col-sm-2 control-label">User ID</label>
                            <div class="col-sm-2">
                                <?php echo $msData['UserId'];?>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-2">
                                <?php echo $msData['firstName'];?>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-2">
                                <?php echo $msData['lastName'];?>
                            </div>
                        </div>
                    <div class="form-group">
					  <label for="inputEmail3" class="col-sm-2 control-label">MSISDN</label>
                      <div class="col-sm-2"><?php echo $msData['MSISDN'];?></div>
					  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-2"><?php echo $msData['emails'];?></div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alt Number</label>
                        <div class="col-sm-2"><?php echo $msData['altNumber'];?></div>		  
                    </div>
                    <div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Birth Date</label>
                      <div class="col-sm-2"><?php echo $msData['BirthDate'];?></div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Language</label>
                      <div class="col-sm-2"><?php echo $msData['Languages'];?></div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alt Number2</label>
                      <div class="col-sm-2"><?php echo $msData['altNumber2'];?></div>
                    </div>
                    <div class="form-group">
			
                    </div>          
                    <br/>
                    <?php if(mysql_num_rows($ExeScholar)) { ?>
                    <table class="table table-hover">
                          <tr>
                              <th>S.r No.</th>
                              <th>Title</th>
                              <th>Announcement Date</th>
                              <th>DeadLine Date</th>
                              <th>Online DeadLine</th>
                              <th>Offline DeadLine</th>
                              <th>Applicable For</th>
                              <th>Download</th>
                          </tr>
                          <?php $i=1; $curDate = date('Y-m-d'); $nidArray=array(); 
                            while($row = mysql_fetch_assoc($ExeScholar)) 
                            { 
                                echo '<tbody><tr>';
                                    echo "<td>".$i++."</td>";
                                    $date = explode("-",trim($row['deadlineDate']));
                                    $date1[0] = $date[2];
                                    $date1[1] = $date[1];
                                    $date1[2] = $date[0];
                                    $date1 = implode("-",$date1);
                                    
                                    
                                    if($row['stat'])
                                    {
                                        echo "<td class='pointer' onclick=\"toggle('".$row['nid']."') \"><font color=\"blue\">".$row['title']."</font></td>";
                                    }
                                    else
                                    {
                                        echo "<td class='pointer'><font color=\"grey\">".$row['title']."</font> - <font color=\"red\">Comming Soon</font></td>";
                                    }
                                    echo "<td>".$row['announcementDate']."</td>";
                                    echo "<td>".$row['deadlineDate']."</td>";
                                    echo "<td>".$row['onlineDeadline']."</td>";
                                    echo "<td>".$row['offlineDeadline']."</td>";
                                    echo "<td>".$row['applicableFor']."</td>";
                                    $selDoc = mysql_query("select * from doc_master where nid='{$row['nid']}'");
                                    echo "<td>";
                                    while($doc = mysql_fetch_assoc($selDoc))
                                    {
                                        echo '<a href="'.$doc['doc_url'].'">'.$doc['doc_title'].'</a><br/>';
                                    }
                                    
                                    echo "</td>";
                                echo "</tr></tbody>";
                                echo '<tbody id="'.$row['nid'].'" onclick="toggle('.$row['nid'].')" style="display:none">';
                                echo '<tr>';
                                    echo "<th>Contact Details</th>";
                                    echo "<td  colspan='6'>".$row['contactDetails']."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th>Eligibility</th>";
                                    echo "<td colspan='6'>".$row['eligibility']."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th>Benefits</th>";
                                    echo "<td colspan='6'>".$row['purposeAwards']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>How To Apply</th>";
                                    echo "<td colspan='6'>".$row['howToApply']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Introduction</th>";
                                    echo "<td colspan='6'>".$row['introduction']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Faq</th>";
                                    echo "<td colspan='6'>".$row['faq']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>More Details</th>";
                                    echo "<td colspan='6'>".$row['moreDetails']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Expert Comments</th>";
                                    echo "<td colspan='6'>".$row['expertComments']."</td>";
                                echo "</tr></tbody>";    
                               $nidArray[$row['nid']] = $row['scholarshipName'] ; 
                           }
                           
                           while($row = mysql_fetch_assoc($ExeScholar1)) 
                            { 
                                echo '<tbody><tr>';
                                    echo "<td>".$i++."</td>";
                                    $date = explode("-",trim($row['deadlineDate']));
                                    $date1[0] = $date[2];
                                    $date1[1] = $date[1];
                                    $date1[2] = $date[0];
                                    $date1 = implode("-",$date1);
                                    
                                    
                                    if($row['stat'])
                                    {
                                        echo "<td class='pointer' onclick=\"toggle('".$row['nid']."') \"><font color=\"blue\">".$row['title']."</font></td>";
                                    }
                                    else
                                    {
                                        echo "<td class='pointer'><font color=\"grey\">".$row['title']."</font> - <font color=\"red\">Comming Soon</font></td>";
                                    }
                                    echo "<td>".$row['announcementDate']."</td>";
                                    echo "<td>".$row['deadlineDate']."</td>";
                                    echo "<td>".$row['onlineDeadline']."</td>";
                                    echo "<td>".$row['offlineDeadline']."</td>";
                                    echo "<td>".$row['applicableFor']."</td>";
                                    $selDoc = mysql_query("select * from doc_master where nid='{$row['nid']}'");
                                    echo "<td>";
                                    while($doc = mysql_fetch_assoc($selDoc))
                                    {
                                        echo '<a href="'.$doc['doc_url'].'">'.$doc['doc_title'].'</a><br/>';
                                    }
                                    
                                    echo "</td>";
                                echo "</tr></tbody>";
                                echo '<tbody id="'.$row['nid'].'" onclick="toggle('.$row['nid'].')" style="display:none">';
                                echo '<tr>';
                                    echo "<th>Contact Details</th>";
                                    echo "<td  colspan='6'>".$row['contactDetails']."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th>Eligibility</th>";
                                    echo "<td colspan='6'>".$row['eligibility']."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th>Benefits</th>";
                                    echo "<td colspan='6'>".$row['purposeAwards']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>How To Apply</th>";
                                    echo "<td colspan='6'>".$row['howToApply']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Introduction</th>";
                                    echo "<td colspan='6'>".$row['introduction']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Faq</th>";
                                    echo "<td colspan='6'>".$row['faq']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>More Details</th>";
                                    echo "<td colspan='6'>".$row['moreDetails']."</td>";
                                echo "</tr>";
                                echo "<tr>";    
                                    echo "<th>Expert Comments</th>";
                                    echo "<td colspan='6'>".$row['expertComments']."</td>";
                                echo "</tr></tbody>";    
                               $nidArray[$row['nid']] = $row['scholarshipName'] ; 
                           }
                           
                           
                           ?>                          
                      </table> 
                    <?php } else { ?>
                    <table class="table table-hover">
                        <tr><th colspan="2">Profile Incomplete</th></tr>
                        <tr>
                            <th>Incomplete Fields</th>
                            <th>Profile Complete %</th>
                        </tr>
                        <tr>
                        <?php
                            echo "<td>".$msData['MissingFields']."</td>";
                            echo "<td>".$msData['ProfilePercentage']."</td>";
                        ?>
                        </tr>
                    </table>
                    <?php } ?>
                  </form>
                      </div>
                      
                      <div id="History" class="tabcontent">
                          <table cellspacing="0" border="1" width="100%">
	<tr class="head">
	<?php
	for ($i = 0; $i < $Count; $i++) 
	{
		$header = mysql_field_name($hisRsc, $i);
		echo  "<th>".$header."</th>";
	}
	?>
	</tr>
	<?php
	while($Data = mysql_fetch_row($hisRsc)) {
	echo "<tr>";
		foreach($Data as $value) 
		{
		echo  "<td>".$value."</td>" ;
		}
	echo "</tr>";
	}
	?>
        </table>
                      </div>  
                      <div id="CallBack" class="tabcontent" style="background-color:white">
                          <table cellspacing="0" border="1" width="100%" >
                            <tr>
                                <th>First Name</th>
                                <th>MSISDN</th>
                                <th>CallBack Time</th>
                            </tr>
                            <?php
                            while($Data = mysql_fetch_assoc($CBAllRsc)) 
                            {
                                echo '<tr style="background-color:white">';
                                    echo  "<td>".$Data['FirstName']."</td>" ;
                                    
                                    echo  "<td>".$Data['MSISDN']."</td>" ;
                                    echo  "<td>".$Data['CallBackTime']."</td>" ;
                                echo "</tr>";
                            }
                            ?>
                            </table>
                      </div>  
                  </div><!-- /.box-body -->
                  
                  <div class="box-footer">
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->
          </div>  
		  
	<div class="col-md-12">
              <div class="box box-info">
				<div class="box-header with-border">
                  <h3 class="box-title">Agent Tagging Details</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    
                  </div>
                </div>
		<form name="frm1" method="post" enctype="multipart/form-data" onSubmit="return ImportValidate();" class="form-horizontal">
                  <div class="box-body">
                    <div class="form-group">
			<input type="hidden" name="PostId" value="<?php echo $msData['Id'];?>">
                        <label for="inputEmail3" class="col-sm-2 control-label">Call Status<font color="red">*</font></label>
                        <div class="col-sm-2">
                            <select name="CallStatus" id="CallStatus" required="yes" onChange="ShowDispo1(this.value)" class="form-control" required="">
                                <option value="">-Select-</option>
                                <?php
                                $Selvoc = "SELECT DISTINCT(CallStatus) CallStatus FROM voc";
                                $Rscvoc = mysql_query($Selvoc);
                                while($Datavoc = mysql_fetch_array($Rscvoc))
                                {
                                ?>
                                <option value="<?php echo $Datavoc['CallStatus'];?>"><?php echo $Datavoc['CallStatus'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="showdispo1"></div>    
			<div id="showdispo2"></div>
                    </div>
                     <div class="form-group">
                        <div id="showdispo3"></div> 
			<div id="showdispo4"></div>
                    </div>
                       
                      
                      <?php foreach($nidArray as $k=>$v)
                      { ?>
                          <div class="form-group">
                              <div class="col-sm-6"><?php echo $v;?></div>
                              <div class="col-sm-6">
                                  <textarea name="remark<?php echo $k;?>" id="remark<?php echo $k;?>" placeholder="<?php echo $v; ?>" class="form-control"></textarea>
                              </div>
                          </div>    
                      <?php } if(empty($nidArray)) { ?>
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Remark</label>
                              <div class="col-sm-6">
                                  <textarea name="remark" id="remark" class="form-control"></textarea>
                              </div>
                          </div>    
                      <?php } ?>
                    <div class="form-group">
					  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                      <div class="col-sm-2"><input type="submit" name="Save" value="Save" class="btn btn-info"></div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    
                  </div><!-- /.box-footer -->
				<input type="hidden" id="nidsArr" name="nidsArr" value="<?php echo implode(",",  array_keys($nidArray)); ?>" />
                                <input type="hidden" id="profil" name="profil" value="<?php echo $prof; ?>" />
                </form>
				<div id="showresults"></div>
              </div><!-- /.box -->
			  
          </div>	   
		  
		  </div>
</body>
</html>
<script>
    document.getElementById("defaultOpen").click();
    //apicall('Dial');
</script>