function AddIssue()
{
	var branch = document.getElementById("IssuesBranchName").value;
	if(branch=='') { alert("Please fill Branch Name"); return false; }
	var process = document.getElementById("IssuesProcessName").value;
	if(process=='') { alert("Please fill process Name"); return false; }
	var ticket_no = document.getElementById("IssuesTicketNo").value;
	if(ticket_no=='') { return false;}
	var issue_desc = document.getElementById("IssuesTicketDesc").value;
	if(issue_desc=='') {alert("Please fill issue_desc Name"); return false;}
	var priority = document.getElementById("ParticularPriority").value;
        if(priority=='') { alert("Please fill Priority");return false;}
	var req_type = document.getElementById("ParticularRequirementType").value;
	if(req_type=='') { alert("Please fill Requirement Type");return false;}
	var req_det = document.getElementById("ParticularRequirementDesc").value;
	if(req_det=='') { alert("Please fill Requirment Description");return false;}
	var status = document.getElementById("ParticularStatus").value;
	if(status=='') { alert("Please select Status");return false;}
	var remarks = document.getElementById("ParticularRemarks").value;
        if(remarks=='') { alert("Please fill Remarks");return false;}
	//alert('test');

	//var file = document.getElementById("ParticularAttachFiles").value0;
	//alert(file);

	url = "addIssue/?process_name="+process+'&branch_name='+branch+'&ticket_no='+ticket_no+'&ticket_desc='+issue_desc+'&priority='+priority+'&requirment_type='+req_type+'&requirement_desc='+req_det+'&issue_status='+status+'&remarks='+remarks;
	addIssue(url);
	return false;
}

function addIssue(url)
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
					   location.reload();
					 document.getElementById("nn").readOnly= true;
				     document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 
				}
				} 
				xmlHttpReq.open('POST',url,true);
				xmlHttpReq.send(null);	
}

function issue_add()
{
	var pri="";
	try
	{ 
		pri = document.getElementById("IssuesProcessDesc").value;
		return true;
	}
	catch(err)
	{
		alert("Please Add Issue first");
		 return false;
	}

	return true;
}
function process_des()
{
	var branch = document.getElementById("IssuesBranchName").value;
	var process = document.getElementById("IssuesProcessName").value;
	var ticket_no = document.getElementById("IssuesTicketNo").value;
	var issue_desc = document.getElementById("IssuesTicketDesc").value;	
	//var process_type = document.getElementById("IssuesProcessType").value;
	//var process_desc = document.getElementById("IssuesProcessDesc").value;	
	
    url = "process_des/?process_name="+process+"&branch_name="+branch+"&ticket_no="+ticket_no+"&ticket_desc="+issue_desc;
	addIssue(url);
}
function get_process23(val)
{
	//alert(val);
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
					 document.getElementById("mm").readOnly= true;
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText; 
				}
				}
				xmlHttpReq.open('POST','get_process/?branch_name='+val,true);
				xmlHttpReq.send(null);
}

function download_file(val)
{
    var branch_name=document.getElementById('AddBranchName').value;
	
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
					 document.getElementById("mm").readOnly= true;
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
				}
				} 
				xmlHttpReq.open('POST','download_file/?branch_name='+branch_name,true);
				xmlHttpReq.send(null); 	
}
function select_Date()
{
	var todate=document.getElementById('IssuesToDate').value;
	var fromdate=document.getElementById('IssuesFromDate').value;
	if(todate == '')
	{alert("Please Enter To Date");
		return false;
	}

	if(fromdate == '')
	{alert("Please Select From Date");
		return false;
	}
	return true;
}
function get_checkbox()
{
	//var todate = document.getElementById('IssuesToDate');
	//var fromdate =  document.getElementById('IssuesFromDate');
	var check = document.getElementsByName("issue[]");	

	var flag = 0;
	for (var i = 0; i< check.length; i++) 
	{
		if(check[i].checked)
		{
			flag ++;
		}
	}
		
	if (flag == 0) 
	{
		alert ("Please Checked CheckBox First");
		document.getElementById('AssignUser').value = '';
		return false;
	}
	return true;
}
function refresh()
{
	location.reload();
}


function  report_show() // REPORT
{
	
	var AddBranchName = document.getElementById('AddBranchName').value;
	var AddProcessName = document.getElementById('AddProcessName').value;
	var AddToDate = document.getElementById('AddToDate').value;
   	 var AddFromDate = document.getElementById('AddFromDate').value;


	if(AddBranchName ==''){
		
		alert('Please Select Branch');
		return false;
	}
if(AddProcessName ==''){
		
		alert('Please Select Process Name');
		return false;
	}
	if(AddToDate =='')
	{
		alert('Please Add to date');
		return false;
		}
		if(AddFromDate =='')
		{
			alert('Please Add From Date');
			return false;
			}
			alert (AddSelectReport+AddCompanyName+AddBranchName+AddToDate+AddFromDate);
            return false;
      export_report(AddBranchName,AddProcessName,AddToDate,AddFromDate);
}
 function export_report(AddBranchName,AddProcessName,AddToDate,AddFromDate)
{
    var url='export_report/?AddBranchName='+AddBranchName+'&AddProcessName='+AddProcessName+'&AddToDate='+AddToDate+'&AddFromDate='+AddFromDate;
	window.location.href = url;
}
function exp_report() //REPORT
{

var AddBranchName = document.getElementById('AddBranchName').value;
var AddProcessName = document.getElementById('AddProcessName').value;
var AddToDate = document.getElementById('AddToDate').value;
var AddFromDate = document.getElementById('AddFromDate').value;
var AddSubmitBy = document.getElementById('AddSubmitBy').value;

   
	if(AddBranchName =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
			
	if(AddProcessName =='')
	{
		alert('Please Select To Process Name');
		return false;
	}
	if(AddToDate =='')
	{
		alert('Please Add to date');
		return false;
	}
		
		if(AddFromDate =='')
		{
			alert('Please Add From Date');
			return false;
		}
		
		
		if(AddFromDate =='')
		{
			alert('Please Add From Date');
			return false;
		}
   export_report(AddBranchName,AddProcessName,AddToDate,AddFromDate);
}
function export_report(AddBranchName,AddProcessName,AddToDate,AddFromDate)
{
	var xmlHttpReq = false;	
	alert("check again!");
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
				}
				} 
				xmlHttpReq.open('POST','export_report/?AddBranchName='+AddBranchName+'&AddProcessName='+AddProcessName+'&AddFromDate='+AddFromDate+'&AddToDate='+AddToDate,true);
				xmlHttpReq.send(null);
}

function view_issue_report(){
	
	var	branchid = document.getElementById('AddBranch').value;
	var process = document.getElementById('IssuesProcessName').value;			
	var	status   = document.getElementById('AddIssueStatus').value;
	var	fdate    = document.getElementById('AddFirstDate').value;
	var	ldate    = document.getElementById('AddLastDate').value;
	var AddHandleBy = document.getElementById('AddHandleBy').value;
	var AddSubmitBy = document.getElementById('AddSubmitBy').value;
	//url = "show_issues_reports/branch_name="+branchid+'&process_name='+process+'&userid='+userid+'&status='+status+'&fdate='+fdate+'&ldate='+ldate+'&AddHandleBy='+AddHandleBy;
	//addIssue(url);
	
	//alert("data-send");
	$.ajax({
		type:'post',
		url:'show_issues_reports',
		data:{branch_name:branchid,process_name:process,status:status,fdate:fdate,ldate:ldate,HandleBy:AddHandleBy,SubmitBy:AddSubmitBy},
		success : function(data){
			$("#nn").html(data);
		}
	});
}
function validate_date()
{
    var fdate=$("#IssuesToDate").val();
    var ldate=$("#IssuesFromDate").val(); 
	     var arr = fdate.split('-');
		 fdate =  arr[1]+"-"+arr[0]+"-"+arr[2];
		 arr =  ldate.split('-');
		 ldate =  arr[1]+"-"+arr[0]+"-"+arr[2];
		   //alert(fdate);
		  // alert(new Date(fdate));
		 if ((new Date(fdate).getTime()) <= (new Date(ldate).getTime())) 
		 {
			 
		} 
		else
		{
			alert("Please Select Valid Date");
			document.getElementById("IssuesFromDate").value="";
		}
}

function checkform()
    {
    var dateString = document.purchase.txndt.value;
    var fdate = new Date(dateString);
    var ldate = new Date();
         if (document.purchase.txndt.value == "")
          { 
          //something is wrong
          alert('REQUIRED FIELD ERROR: Please enter date in field!')
          return false;
          }
          else if (fdate>ldate)
          { 
          //something else is wrong
            alert('You cannot enter a date in the future!')
            return false;
          }
          // if script gets this far through all of your fields
          // without problems, it's ok and you can submit the form
          return true;
    }

function exp_report()
{
var	branchid = document.getElementById('AddBranch').value;
	var process = document.getElementById('IssuesProcessName').value;			
	var	status   = document.getElementById('AddIssueStatus').value;
	var	fdate    = document.getElementById('AddFirstDate').value;
	var	ldate    = document.getElementById('AddLastDate').value;
	var AddHandleBy = document.getElementById('AddHandleBy').value;
	var AddSubmitBy = document.getElementById('AddSubmitBy').value;
	//url = "show_issues_reports/branch_name="+branchid+'&process_name='+process+'&userid='+userid+'&status='+status+'&fdate='+fdate+'&ldate='+ldate+'&AddHandleBy='+AddHandleBy;
	//addIssue(url);
	
	//alert("data-send");
	$.ajax({
		type:'post',
		url:'show_issues_reports',
		data:{branch_name:branchid,process_name:process,status:status,fdate:fdate,ldate:ldate,HandleBy:AddHandleBy,SubmitBy:AddSubmitBy},
		success : function(data){
			$("#nn").html(data);
		}
	});

    var url='export_issues_reports/?branch_name='+branchid+'&process_name='+process+'&status='+status+'&fdate='+fdate+'&ldate='+ldate+'&HandleBy='+AddHandleBy+'&+SubmitBy='+AddSubmitBy;
	window.location.href = url;
}
