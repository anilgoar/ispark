function showProvisionReports()
{
    var branch;

    branch = document.getElementById("ProvisionBranchName").value;
    
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
            document.getElementById("oo").readOnly= true;
            document.getElementById('oo').innerHTML = xmlHttpReq.responseText;
	}
    }
    xmlHttpReq.open('POST','https://192.168.1.231/ispark/Provisions/showReport/?'+url,true);
    xmlHttpReq.send(null);
}
function myFunction(value) {
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
    xmlHttpReq.open('POST','https://192.168.1.231/ispark/Provisions/provisionDetails/?'+url,true);
    xmlHttpReq.send(null);
    //document.getElementById("result").innerHTML = "sldfjsdlfj sdlfjsdlk fs";
}

function getRemark(value)
{
    if(document.getElementById(value).value!='')
    document.getElementById("User"+value+"PoRemarks").required = true;
    else
        document.getElementById("User"+value+"PoRemarks").required = false;
    
}

function getRemark2(value)
{
    if(document.getElementById(value).value!='')
    document.getElementById("User"+value+"GRNRemarks").required = true;
    else
        document.getElementById("User"+value+"GRNRemarks").required = false;
    
}

function getRemark2(value)
{
    if(document.getElementById(value).value!='')
    document.getElementById("User"+value+"GrnRemarks").required = true;
    else
        document.getElementById("User"+value+"GrnRemarks").required = false;
    
        
//document.getElementById(value).required;
}

function get_AllProvision(value)
{
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
            document.getElementById("provision").readOnly= true;
            document.getElementById('provision').innerHTML = xmlHttpReq.responseText;
            $(document).ready(function () {
        $('#table_id').dataTable();
    });
	}
    }
    xmlHttpReq.open('POST','http://192.168.1.231/ispark/Provisions/view_provision/?branch_name='+value,true);
    xmlHttpReq.send(null);  
}