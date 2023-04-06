function ajax(id,url)
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
					 document.getElementById(id).readOnly= true;
				     document.getElementById(id).innerHTML = xmlHttpReq.responseText; 
				}
				} 
				
				xmlHttpReq.open('POST',url,true);
				xmlHttpReq.send(null);	
}
function collection_report_get_branch(val)
{
	if(val == '')
	{
				document.getElementById('AddBranchName').value = '';
				document.getElementById('AddBranchName').disabled = true;
	}
	else
	{
		url = 'https://122.176.84.97/ispark/CollectionReports/get_branch/?company_name='+val;
		ajax('branch',url);
		//window.location(url);
	}
}
function collection_report_client(val)
{
	if(val == '')
	{}
	else
	{
		url = 'https://122.176.84.97/ispark/CollectionReports/get_client/?branch_name='+val;
		ajax('client',url);		
	}
}

function get_collectionReport(val)
{
    var	AddCompanyName = document.getElementById('AddCompanyName').value;
    var	AddBranchName = document.getElementById('AddBranchName').value;
    var	AddToDate = document.getElementById('AddToDate').value;
    var	AddFromDate = document.getElementById('AddFromDate').value;
    var	AddReportType = document.getElementById('AddReportType').value;
    var	AddClientName = document.getElementById('AddClientName').value;
	
	if(AddCompanyName == '')
	{
		alert("Please Select Company Name");
		return false;
	}
	else if(AddBranchName == '')
	{
		alert("Please Select Branch Name");
		return false;
	}
	else if(AddToDate == '')
	{
		alert("Please Select To Date");
		return false;
	}
	else if(AddFromDate == '')
	{
		alert("Please Select From Date");
		return false;
	}
	else if(AddReportType == '')
	{
		alert("Please Select Report Type");
		return false;
	}
	else if (AddClientName == '')
	{
		alert("Please Select Client Name");
		return false;
	}
	
		url = 'https://122.176.84.97/ispark/CollectionReports/get_collectionReport/?company_name='+AddCompanyName+'&branch_name='+AddBranchName+'&toDate='+AddToDate+'&fromDate='+AddFromDate+'&report='+AddReportType+'&client_name='+AddClientName+'&type='+val;
		if(val == 'show')
		{
			ajax('data',url);
		}
		else
		{
			window.location.href = url;
		}
		return false;
}

function show_billgeneration(val)
{

var AddFromDate = document.getElementById('AddFromDate').value;
var AddToDate = document.getElementById('AddToDate').value;
var AddCompanyName = document.getElementById('AddCompanyName').value;
var Branch = document.getElementById('Branch').value;

var ReportType = document.getElementById('AddReportType').value;

	url = 'https://122.176.84.97/ispark/BillGenerations/get_bill_generation/?AddFromDate='+AddFromDate+'&AddToDate='+AddToDate+'&AddCompanyName='+AddCompanyName+'&Branch='+Branch+'&type='+val+'&ReportType='+ReportType;
	if(val == 'show')
	{
		ajax('nn',url);
		 $('#table_id').dataTable();
		 $('#table_id1').dataTable();

	}
	else
	{
		window.location.href = url;
	}
}
function myFunction2(value,id) {
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
    
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            var i = 0;
            for(i = 0; i<=1000; i++) 
            {
                try{
                document.getElementById('result'+i).innerHTML = '';
            }
            catch(err){}
            }
            document.getElementById("result"+id).readOnly= true;
            document.getElementById('result'+id).innerHTML = xmlHttpReq.responseText;
	}
    }
    xmlHttpReq.open('POST','https://122.176.84.97/ispark/CollectionReports/collectionDetails/?id='+value,true);
    xmlHttpReq.send(null);
    //document.getElementById("result").innerHTML = "sldfjsdlfj sdlfjsdlk fs";
}


function get_otherdeduction(val)
{
	AddBranchName = document.getElementById('branch_name').value;
	finance_year = document.getElementById('finance_year').value;
	month = document.getElementById('month').value;
	
	if(AddBranchName == '')
	{
		alert("Please Select Branch Name");
		return false;
	}
	else if(finance_year == '')
	{
		alert("Please Select To Financial Year");
		return false;
	}
	else if(month == '')
	{
		alert("Please Select Month");
		return false;
	}
		
		url = 'https://122.176.84.97/ispark/CollectionReports/get_otherdeduction/?AddBranchName ='+AddBranchName+'&finance_year='+finance_year+'&month='+month;
		if(val == 'show')
		{
			ajax('data',url);
		}
		else
		{
			window.location.href = url;
		} 
		return false;
}
function getPerformance(val)
{
var companyName = document.getElementById('company_name').value;
var BranchName = document.getElementById('branch_name').value;
var start_date = document.getElementById('start_date').value;
var end_date = document.getElementById('end_date').value;
var report  = document.getElementById('type').value;


if(companyName == '')
{
    alert("Please Select Company Name");
    return false;
}
else if(BranchName == '')
{
    alert("Please Select Branch Name");
    return false;
}
else if(start_date == '')
{
    alert("Please Select To Date");
    return false;
}
else if(end_date == '')
{
    alert("Please Select From Date");
    return false;
}
else if(report == '')
{
    alert("Please Select Report Type");
    return false;
}
		
var url = 'https://122.176.84.97/ispark/CollectionReports/view_report_performance/?BranchName='+BranchName+'&company='+companyName+'&start_date='+start_date+'&end_date='+end_date+'&report='+report;
if(val == 'show')
{
    ajax('data',url);
}
else
{
    window.location.href = url;
} 
    return false;
}
