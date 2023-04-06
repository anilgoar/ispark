function showProvisionReports2(value)
{
    var branch = document.getElementById("branch_name").value;
    var month = document.getElementById("month").value;
    var type = document.getElementById("type").value;
    var url = 'branch_name='+branch+"&month="+month+"&type="+type+"&view="+value;
    //alert(url);
    var xmlHttpReq = false;
    if (window.XMLHttpRequest)
    {
        xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            document.getElementById("oo").readOnly= true;
            document.getElementById('oo').innerHTML = xmlHttpReq.responseText;
	}
    }
    
    if(value=='Export') window.location.href ='https://122.176.84.97/ispark/ProvisionReports/showReport/?'+url;
    else
    xmlHttpReq.open('POST','https://122.176.84.97/ispark/ProvisionReports/showReport/?'+url,true);
    
    xmlHttpReq.send(null);
}
function myFunction4(value) {
    //var value = document.getElementById("demo").value;
    var xmlHttpReq = false;
    if (window.XMLHttpRequest)
    {
        xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = 'branch='+value;
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            document.getElementById("result").readOnly= true;
            document.getElementById('result').innerHTML = xmlHttpReq.responseText;
	}
    }
    xmlHttpReq.open('POST','https://122.176.84.97/ispark/Provisions/provisionDetails/?'+url,true);
    xmlHttpReq.send(null);
    //document.getElementById("result").innerHTML = "sldfjsdlfj sdlfjsdlk fs";
}
function getRemark3(value)
{
    if(document.getElementById(value).value!='')
    document.getElementById("Home"+value+"PoRemarks").required = true;
    else
        document.getElementById("Home"+value+"PoRemarks").required = false;
    
        
//document.getElementById(value).required;
}

function getRemark4(value)
{
    if(document.getElementById(value).value!='')
    document.getElementById("Home"+value+"GrnRemarks").required = true;
    else
        document.getElementById("Home"+value+"GrnRemarks").required = false;
    
        
//document.getElementById(value).required;
}

function getMonth(branch)
{
    var branch;

    var url = 'branch_name='+branch;
    var xmlHttpReq = false;
    if (window.XMLHttpRequest)
    {
        xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            document.getElementById("monthID").readOnly= true;
            document.getElementById('monthID').innerHTML = xmlHttpReq.responseText;
	}
    }
    xmlHttpReq.open('POST','https://122.176.84.97/ispark/ProvisionReports/getMonth/?'+url,true);
    xmlHttpReq.send(null);
}
function sdfds(value)
{alert(value);}