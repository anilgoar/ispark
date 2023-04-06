// JavaScript Document
function get_branch3(val)
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
				{     
					 document.getElementById("mm").readOnly= true;
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
					  
				}
				} 
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/BillApprovalStages/get_branch/?company_name='+val,true);
				xmlHttpReq.send(null);
}
function validate_BillApprovalStages(val)
{
var	company_name = document.getElementById('AddCompanyName').value;
var	branchname = document.getElementById('AddBranchName').value;
var	selectreport = document.getElementById('AddSelectReport').value;
     
	 if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(branchname =='')
	{
		alert('Please Select Report Type');
		return false;
	}
			
	if(selectreport =='')
	{
		alert('Please Select To Date');
		return false;
	}

	if(val=='show')
	{
		get_reportBillApproval(company_name,branchname,selectreport,val);
	}
	else
	{
		var url='https://122.176.84.97/ispark/BillApprovalStages/get_report5/?company_name='+company_name+'&branchname='+branchname+'&selectreport='+selectreport+'&type='+val;
		window.location.href = url;

	}
}
 function get_reportBillApproval(company_name,branchname,selectreport,type)
{
	
	var xmlHttpReq = false;	
	//alert("check again!");
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
					 document.getElementById("nn").readOnly= true;
				     document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 
					 $('#table_id').dataTable();
					 $('#table_id1').dataTable();

				}
				} 
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/BillApprovalStages/get_report5/?company_name='+company_name+'&branchname='+branchname+'&selectreport='+selectreport+'&type='+type, true);
				xmlHttpReq.send(null);
}

function getExport(val)
{
var companyName = document.getElementById('company_name').value;
var BranchName = document.getElementById('branch_name').value;
var year = document.getElementById('finance_year').value;

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
else if(year == '')
{
    alert("Please Select Finance Year");
    return false;
}

		
var url = 'https://122.176.84.97/ispark/BillApprovalStages/get_export/?BranchName='+BranchName+'&company='+companyName+'&year='+year;
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