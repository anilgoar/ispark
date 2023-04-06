
function collection_validate(val)
{ 
	var company_name = 	document.getElementById('CollectionCompanyName').value;
	var branch_name = 	document.getElementById('CollectionBranchName').value;
	var finance_year = 	document.getElementById('CollectionFinancialYear').value;
	
	if(company_name == '')
	{
		alert('Please Select Company Name');
		return false;
	}
	
	else if(branch_name == '')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
	else if(finance_year == '')
	{
		alert('Please Select Finance Year');
		return false;
	}
	if(val=='RTGS')
	{
		//var x = document.getElementById('CollectionBankName');
		//var option = document.createElement("option");
    		//option.text = "RTGS";
    		//x.add(option);
		document.getElementById('CollectionBankName').value = 'RTGS';
                document.getElementById('CollectionPayTypeDates').value = '';
                document.getElementById('CollectionPayTypeDates').disabled = true;
		document.getElementById('CollectionBankName').disabled = true;
	}
	else
	{
		//var x = document.getElementById("CollectionBankName");
		//x.remove(x.selectedIndex);
		document.getElementById('CollectionBankName').value = '';
		document.getElementById('CollectionBankName').disabled = false;
                document.getElementById('CollectionPayTypeDates').disabled = false;
		
	}	
	collection_data(company_name,branch_name,finance_year,val);
	
}
function collection_data(company_name,branch_name,finance_year,type)
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
					 document.getElementById("nn").readOnly= true;
				     document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 
				}
				} 
				
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/get_collection_data/?company_name='+company_name+'&financial_year='+finance_year+'&pay_type='+type,true);
				xmlHttpReq.send(null);	
}

function get_check_disable()
{
var pay_types 		= 	document.getElementsByName('type');
var flag = false;
	for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].disabled)
		{
        		flag = true;
    		}
	}
	return flag;
}
function get_bill_amount(val)
{
	var xmlHttpReq = false;
	var branch_name = 	document.getElementById('CollectionBranchName').value;
        var finance_year = 	document.getElementById('CollectionFinancialYear').value;
        var company_name = 	document.getElementById('CollectionCompanyName').value;
	if(val =='') return;
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
						var amount=xmlHttpReq.responseText;
						if(amount == '')
						{
							alert("Please Enter Right Bill Number");
						}
						else if(parseInt(amount) == 0)
						{
							alert("Bill Already Paid");
							document.getElementById('CollectionAmount').value = '';
						}
						else if(parseInt(amount) == 1)
						{
							alert("Bill No. Already Added");
						}
						else
						{
					 //document.getElementById("nn").readOnly= true;
				     document.getElementById('CollectionAmount').value = xmlHttpReq.responseText;
 					get_bill_remark()
						}
				}
				} 
				if(get_check_disable()){
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/get_bill_amount/?bill_no='+val+'&branch_name='+branch_name+'&finance_year='+finance_year+'&company_name='+company_name,true);
				xmlHttpReq.send(null);	
				}
				else
				{alert('Please Click on Save Button First');}
	
}

function get_bill_remark()
{
	var xmlHttpReq = false;
	
	var branch_name = 	document.getElementById('CollectionBranchName').value;
	var bill_no = document.getElementById('CollectionBillNo').value;

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
					 //document.getElementById("nn").readOnly= true;
				     document.getElementById('CollectionRemarks').value = xmlHttpReq.responseText; 
						
				}
				} 
				if(get_check_disable()){
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/get_bill_remark/?bill_no='+bill_no+'&branch_name='+branch_name,true);
				xmlHttpReq.send(null);	
				}
				else
				{alert('Please Click on Save Button First');}
	
}

function save_collection()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;
	//var type 			=	arr[3] =	document.getElementById('type').value;	
	var pay_no 			=	arr[4] =	document.getElementById('CollectionPayNo').value;
	var pay_amount 		=	arr[5] =	document.getElementById('CollectionPayAmount').value;
	 
	var bank_name 	=	arr[6] =	document.getElementById('CollectionBankName').value;	

	if(type == 'RTGS')
		{
			bank_name 	=	arr[6] = 'RTGS';
			
		}

	deposit_bank 	=	arr[7] =	document.getElementById('CollectionDepositBank').value;	
	var pays_date 		=	arr[8] =	document.getElementById('CollectionPayDates').value;
	var no_of_bills 	=	arr[9] =	document.getElementById('CollectionNoOfBills').value;
        if(type == 'RTGS'){document.getElementById('CollectionPayTypeDates').value = ' ';}
        
	var pay_type_dates 	=	arr[10] =	document.getElementById('CollectionPayTypeDates').value;

	for(var i =0; i<11; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pays_date='+arr[8]+'&no_of_bills='+arr[9]+'&pay_type_dates='+pay_type_dates;
	
	saveCollection(url);
	return false;
}

function saveCollection(url)
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
					 //document.getElementById("oo").readOnly= true;
				     //document.getElementById('oo').innerHTML = xmlHttpReq.responseText; 
					 location.reload();
					
				}
				} 
				
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/get_collection_tmp_data/?'+url,true);
				xmlHttpReq.send(null);	
}

function add_collection()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	
	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;

	var pay_no 			=	arr[4] 	=	document.getElementById('CollectionPayNo').value;
	var pay_amount 		=	arr[5] 	=	document.getElementById('CollectionPayAmount').value; 
	var bank_name 		=	arr[6] 	=	document.getElementById('CollectionBankName').value;
	var deposit_bank 	=	arr[7] 	=	document.getElementById('CollectionDepositBank').value;	
	var pays_date 		=	arr[8] 	=	document.getElementById('CollectionPayDates').value;
	var no_of_bills 	=	arr[9] 	=	document.getElementById('CollectionNoOfBills').value;

	var bill_no 		= 	arr[10] =	document.getElementById('CollectionBillNo').value;
	var bill_amount 	= 	arr[11] = 	document.getElementById('CollectionAmount').value;
	var bill_passed 	= 	arr[12] = 	document.getElementById('CollectionBillPassed').value;
	var tds_ded 		= 	arr[13] = 	document.getElementById('CollectionTdsDed').value;
	var net_amt 		= 	arr[14] = 	document.getElementById('CollectionNetAmt').value;
	var deduct 			= 	arr[15] = 	document.getElementById('CollectionDeduction').value;
	var status			=	arr[16]	=	document.getElementById('CollectionStatus').value;
	var Remarks 		= 	arr[17] = 	document.getElementById('CollectionRemarks').value;
        if(type=='RTGS') document.getElementById('CollectionPayTypeDates').value =' ';
	var pay_type_dates  =   arr[18] = 	document.getElementById('CollectionPayTypeDates').value;


	for(var i =0; i<19; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pay_dates='+arr[8]+'&no_of_bills='+arr[9]+'&bill_no='+arr[10]+'&bill_amount='+arr[11]+'&bill_passed='+arr[12]+'&tds_ded='+arr[13]+'&net_amount='+arr[14]+'&deduction='+arr[15]+'&status='+arr[16]+'&remarks='+arr[17]+'&pay_type_dates='+pay_type_dates;
	
	if(validate_collection_data())
	{
		addCollection(url);
		blank_bill_fields();
	}
	return false;	
}

function blank_bill_fields()
{
document.getElementById('CollectionBillNo').value = '';
document.getElementById('CollectionAmount').value = '';
document.getElementById('CollectionBillPassed').value = '';
document.getElementById('CollectionTdsDed').value = '';
document.getElementById('CollectionNetAmt').value = '';
document.getElementById('CollectionDeduction').value = '';
document.getElementById('CollectionStatus').value = '';
document.getElementById('CollectionRemarks').value = '';
document.getElementById('CollectionOtherDeduction').value = '';
document.getElementById('CollectionOtherRemarks').value = '';
}
function addCollection(url)
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
					 document.getElementById("oo").readOnly= true;
				     document.getElementById('oo').innerHTML = xmlHttpReq.responseText; 
					 location.reload();
					
				}
				} 
				
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/get_collection_tmp_bill_data/?'+url,true);
				xmlHttpReq.send(null);	
	
}

function deletesCollection(val)
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
						location.reload();
					 //document.getElementById("qq").readOnly= true;
				     //document.getElementById('qq').innerHTML = xmlHttpReq.responseText; 
					 //location.reload();
					
				}
				} 
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/delete_collection_particular/?id='+val,true);
				xmlHttpReq.send(null);	

	return false;
}
function add_other_collection()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	
	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;

	var pay_no 			=	arr[4] 	=	document.getElementById('CollectionPayNo').value;
	var pay_amount 		=	arr[5] 	=	document.getElementById('CollectionPayAmount').value; 
	var bank_name 		=	arr[6] 	=	document.getElementById('CollectionBankName').value;
	var deposit_bank 	=	arr[7] 	=	document.getElementById('CollectionDepositBank').value;	
	var pays_date 		=	arr[8] 	=	document.getElementById('CollectionPayDates').value;
	var no_of_bills 	=	arr[9] 	=	document.getElementById('CollectionNoOfBills').value;	

	var other_deduction = 	arr[10] =	document.getElementById('CollectionOtherDeduction').value;
	var other_remarks 	= 	arr[11] = 	document.getElementById('CollectionOtherRemarks').value;
	var pay_type_dates	=	arr[12] = 	document.getElementById('CollectionPayTypeDates').value;
	
	for(var i =0; i<13; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pays_date='+arr[8]+'&no_of_bills='+arr[9]+'&other_deduction='+arr[10]+'&other_remarks='+arr[11]+'&pay_type_dates='+pay_type_dates;
	
	if(other_deduct_validate())
	{
		otherDeduction(url);
		blank_bill_fields();
	}
	return false;	
}

function otherDeduction(url)
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
					 document.getElementById("qq").readOnly= true;
				     document.getElementById('qq').innerHTML = xmlHttpReq.responseText; 
					 location.reload();
					
				}
				} 
				
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/other_deduction/?'+url,true);
				xmlHttpReq.send(null);	
	
}

function deletesDeduction(val)
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
				{     
						location.reload();
					 //document.getElementById("qq").readOnly= true;
				     //document.getElementById('qq').innerHTML = xmlHttpReq.responseText; 
					 //location.reload();
					
				}
				} 
				xmlHttpReq.open('POST','https://122.176.84.97/ispark/Collections/delete_other_deduction/?id='+val,true);
				xmlHttpReq.send(null);	

	return false;
}

function validate_collection_data()
{
		
		bill_amount		=	parseInt(document.getElementById("CollectionAmount").value);
		bill_passed		=	parseInt(document.getElementById("CollectionBillPassed").value);
		bill_tds		=	parseInt(document.getElementById("CollectionTdsDed").value);
		net_amt			=	parseInt(document.getElementById("CollectionNetAmt").value);		
		bill_deduct		=	parseInt(document.getElementById("CollectionDeduction").value);
		coll_payment	=	parseInt(document.getElementById("CollectionPayAmount").value);
		status			=	document.getElementById("CollectionStatus").value;
		var dedd = bill_amount - bill_passed;
		document.getElementById("CollectionDeduction").value = isNaN(dedd)?0:dedd;
		
		bill_deduct		=	parseInt(document.getElementById("CollectionDeduction").value);
				
		if(parseInt(bill_amount)<(parseInt(bill_passed)))
		{			
			alert('Bill Passed Amount is not Greater Than Bill Amount');
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_tds)))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(net_amt)))
		{
			alert("Net Amount is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)))
		{
			alert("Deduction is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_passed)<(parseInt(bill_tds)))
		{
			alert("TDS is Not Greater Than Bill Passed");
			return false;
		}
		else if(status == '')
		{
			alert("Please Select Status");
		}
		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=(parseInt(bill_tds)+parseInt(net_amt)))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+bill_tds+"net_amt="+net_amt);
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("CollectionDeduction").value = 0;
		}		
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)+parseInt(bill_tds)))
		{			
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_passed)<(parseInt(bill_deduct)+parseInt(bill_tds)))
		{
			alert("Deduction + TDS is Not Greater Than Bill Passed");
			return false;
		}
		else if(!isNaN(bill_passed))
		{
			if(parseInt(bill_amount)!=(parseInt(parseInt(bill_deduct)+parseInt(bill_passed))))
			{
			alert("Always(Bill Amount("+bill_amount+") - Bill Passed("+bill_passed+") = Deduction("+bill_deduct+"))");
			return false;
			}
		}
		else if(parseInt(coll_payment)<parseInt(bill_passed))
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			return false;
		}
		
		return true;
}
function validate_colleciton_amount()
{
	total =  document.getElementById('CollectionPayAmount').value;
	idx 	= document.getElementById('idx').value;
	idx2 	= document.getElementById('idx2').value;
	
	var str = idx.split(",");
	var str2 = idx2.split(",");
	
	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionParticulars"+str[i]+"BillAmount";
		bill_amount		=	parseInt(document.getElementById(bill_amount).value);
		
		bill_passed 	=	"TMPCollectionParticulars"+str[i]+"BillPassed";
		bill_passed		=	parseInt(document.getElementById(bill_passed).value);
		
		bill_tds		=	"TMPCollectionParticulars"+str[i]+"TdsDed";
		bill_tds		=	parseInt(document.getElementById(bill_tds).value);
		
		net_amt			=	"TMPCollectionParticulars"+str[i]+"NetAmount";
		net_amt			=	parseInt(document.getElementById(net_amt).value);
		
		bill_deduct		=	"TMPCollectionParticulars"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = bill_amount - bill_passed;
		bill_deduct		=	parseInt(document.getElementById(bill_deduct).value);

		coll_payment	=	parseInt(document.getElementById("CollectionPayAmount").value);
		
		status			=	"TMPCollectionParticulars"+str[i]+"Status";
		status			=	document.getElementById(status).value;
		
		if(parseInt(bill_amount)<(parseInt(bill_passed)))
		{
			alert('Bill Passed Amount is not Greater Than Bill Amount');
			break;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_tds)))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			break;
		}
		else if(parseInt(bill_amount)<(parseInt(net_amt)))
		{
			alert("Net Amount is Not Greater Than Bill Amount");
			break;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)))
		{
			alert("Deduction is Not Greater Than Bill Amount");
			break;
		}
		else if(parseInt(bill_passed)<(parseInt(bill_tds)))
		{
			alert("TDS is Not Greater Than Bill Passed");
			break;
		}
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = net_amt = bill_passed-bill_tds;			
		}
		else if(!isNaN(net_amt))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = bill_tds = bill_passed-net_amt;
		}

		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=(parseInt(bill_tds)+parseInt(net_amt)))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+bill_tds+"net_amt="+net_amt);
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"Deduction").value = 0;
		}
				
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)+parseInt(bill_tds)))
		{
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			break;
		}

		else if(!isNaN(bill_passed))
		{
			if(parseInt(bill_amount)!=(parseInt(parseInt(bill_deduct)+parseInt(bill_passed))))
			{
			alert("Always(Bill Amount("+bill_amount+") - Bill Passed("+bill_passed+") = Deduction("+bill_deduct+"))");
			break;
			}
		}
		else if(parseInt(coll_payment)<parseInt(bill_passed))
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			break;
		}

			bill_total +=net_amt;		
	}
	
	for(i=0; i<str2.length-1; i++)	
	{		
		other_deduct 	=	"OtherTMPDeduction"+str2[i]+"OtherDeduction";
		other_deduct	=	parseInt(document.getElementById(other_deduct).value);
		
		OtherRemarks 	=	"OtherTMPDeduction"+str2[i]+"OtherRemarks";
		OtherRemarks	=	parseInt(document.getElementById(OtherRemarks).value);
		
		if((parseInt(other_deduct)+total)<parseInt(bill_total))
		{
			alert("Total of All Net Bill  And Other Deduction is Not Greater Than Cheque/RTGS Amount");
			break;
		}
		deduct_total +=other_deduct; 
		
	}
}
 /// for other deduction entry input box
function other_deduct_validate()
{
	total =  document.getElementById('CollectionPayAmount').value;
	idx 	= document.getElementById('idx').value;
	idx2 	= document.getElementById('idx2').value;
	other_deduct2		=	parseInt(document.getElementById("CollectionOtherDeduction").value);
	
	var str = idx.split(",");
	var str2 = idx2.split(",");
	
	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionParticulars"+str[i]+"BillAmount";
		bill_amount		=	parseInt(document.getElementById(bill_amount).value);
		
		bill_passed 	=	"TMPCollectionParticulars"+str[i]+"BillPassed";
		bill_passed		=	parseInt(document.getElementById(bill_passed).value);
		
		bill_tds		=	"TMPCollectionParticulars"+str[i]+"TdsDed";
		bill_tds		=	parseInt(document.getElementById(bill_tds).value);
		
		net_amt			=	"TMPCollectionParticulars"+str[i]+"NetAmount";
		net_amt			=	parseInt(document.getElementById(net_amt).value);
		
		bill_deduct		=	"TMPCollectionParticulars"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = bill_amount - bill_passed;
		bill_deduct		=	parseInt(document.getElementById(bill_deduct).value);

		coll_payment	=	parseInt(document.getElementById("CollectionPayAmount").value);
		
		status			=	"TMPCollectionParticulars"+str[i]+"Status";
		status			=	document.getElementById(status).value;
		
		if(parseInt(bill_amount)<(parseInt(bill_passed)))
		{
			alert('Bill Passed Amount is not Greater Than Bill Amount');
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_tds)))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(net_amt)))
		{
			alert("Net Amount is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)))
		{
			alert("Deduction is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_passed)<(parseInt(bill_tds)))
		{
			alert("TDS is Not Greater Than Bill Passed");
			return false;
		}
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = net_amt = bill_passed-bill_tds;			
		}
		else if(!isNaN(net_amt))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = bill_tds = bill_passed-net_amt;
		}

		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=(parseInt(bill_tds)+parseInt(net_amt)))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+bill_tds+"net_amt="+net_amt);
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"Deduction").value = 0;
		}
				
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)+parseInt(bill_tds)))
		{
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			return false;
		}

		else if(!isNaN(bill_passed))
		{
			if(parseInt(bill_amount)!=(parseInt(parseInt(bill_deduct)+parseInt(bill_passed))))
			{
			alert("Always(Bill Amount("+bill_amount+") - Bill Passed("+bill_passed+") = Deduction("+bill_deduct+"))");
			return false;
			}
		}
		else if(parseInt(coll_payment)<parseInt(bill_passed))
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			return false;
		}

			bill_total +=net_amt;		
	}

	
	for(i=0; i<str2.length-1; i++)	
	{		
		other_deduct 	=	"OtherTMPDeduction"+str2[i]+"OtherDeduction";
		other_deduct	=	parseInt(document.getElementById(other_deduct).value);
		
		OtherRemarks 	=	"OtherTMPDeduction"+str2[i]+"OtherRemarks";
		OtherRemarks	=	parseInt(document.getElementById(OtherRemarks).value);
		
		if((parseInt(other_deduct)+total)<parseInt(bill_total))
		{
			alert("Total of All Bill passed And Other Deduction is Not Greater Than Cheque/RTGS Amount");
			return false;
		}
		deduct_total +=other_deduct; 
		
	}

	
		return true;
}

// checking all data in collectin/payment entry on submit button
function validate_save_collection()
{
	total =  parseInt(document.getElementById('CollectionPayAmount').value);
	no_of_bills = parseInt(document.getElementById('CollectionNoOfBills').value);
	
	idx 	= document.getElementById('idx').value;
	idx2 	= document.getElementById('idx2').value;
	
	var str = idx.split(",");
	var str2 = idx2.split(",");
	
	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionParticulars"+str[i]+"BillAmount";
		bill_amount		=	parseInt(document.getElementById(bill_amount).value);
		
		bill_passed 	=	"TMPCollectionParticulars"+str[i]+"BillPassed";
		bill_passed		=	parseInt(document.getElementById(bill_passed).value);
		
		bill_tds		=	"TMPCollectionParticulars"+str[i]+"TdsDed";
		bill_tds		=	parseInt(document.getElementById(bill_tds).value);
		
		net_amt			=	"TMPCollectionParticulars"+str[i]+"NetAmount";
		net_amt			=	parseInt(document.getElementById(net_amt).value);
		
		bill_deduct		=	"TMPCollectionParticulars"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = bill_amount - bill_passed;
		bill_deduct		=	parseInt(document.getElementById(bill_deduct).value);

		coll_payment	=	parseInt(document.getElementById("CollectionPayAmount").value);
		
		status			=	"TMPCollectionParticulars"+str[i]+"Status";
		status			=	document.getElementById(status).value;
		
		if(parseInt(bill_amount)<(parseInt(bill_passed)))
		{
			alert('Bill Passed Amount is not Greater Than Bill Amount');
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_tds)))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(net_amt)))
		{
			alert("Net Amount is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)))
		{
			alert("Deduction is Not Greater Than Bill Amount");
			return false;
		}
		else if(parseInt(bill_passed)<(parseInt(bill_tds)))
		{
			alert("TDS is Not Greater Than Bill Passed");
			return false;
		}
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = net_amt = bill_passed-bill_tds;			
		}
		else if(!isNaN(net_amt))
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"NetAmount").value = bill_tds = bill_passed-net_amt;
		}

		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=(parseInt(bill_tds)+parseInt(net_amt)))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+bill_tds+"net_amt="+net_amt);
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("TMPCollectionParticulars"+str[i]+"Deduction").value = 0;
		}
				
		else if(parseInt(bill_amount)<(parseInt(bill_deduct)+parseInt(bill_tds)))
		{
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			return false;
		}

		else if(!isNaN(bill_passed))
		{
			if(parseInt(bill_amount)!=(parseInt(parseInt(bill_deduct)+parseInt(bill_passed))))
			{
			alert("Always(Bill Amount("+bill_amount+") - Bill Passed("+bill_passed+") = Deduction("+bill_deduct+"))");
			return false;
			}
		}
		else if(parseInt(coll_payment)<parseInt(bill_passed))
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			return false;
		}

			bill_total +=net_amt;		
	}
	
	for(i=0; i<str2.length-1; i++)	
	{		
		other_deduct 	=	"OtherTMPDeduction"+str2[i]+"OtherDeduction";
		other_deduct	=	parseInt(document.getElementById(other_deduct).value);
		
		OtherRemarks 	=	"OtherTMPDeduction"+str2[i]+"OtherRemarks";
		OtherRemarks	=	parseInt(document.getElementById(OtherRemarks).value);
		
		deduct_total +=other_deduct;
	}
	if((parseInt(deduct_total)+total)!=parseInt(bill_total))
	{	
		alert("Total of Cheque/RTGS And Other Deduction is Not Greater Than Net Bill Amount");
		alert("(Total Net Amount - Cheque/RTGS - Total Other Deduction = 0)\n "+bill_total+"-"+total+"-"+deduct_total+"="+(bill_total-total-deduct_total));
		return false;
	}
	

//	else if(parseInt(str.length-1)<parseInt(no_of_bills))
//	{
//		alert("Please Add At Least "+no_of_bills+" of bills");
//		return false;
//	}
	return true;
}

function get_netAmount()
{
		bill_passed		=	parseInt(document.getElementById("CollectionBillPassed").value);
		bill_tds		=	parseInt(document.getElementById("CollectionTdsDed").value);
		var total = bill_passed - bill_tds;
		if(isNaN(total)){total =0;}
		document.getElementById("CollectionNetAmt").value = total;
}
function get_tds()
{
		bill_passed		=	parseInt(document.getElementById("CollectionBillPassed").value);
		net_amt		=	parseInt(document.getElementById("CollectionNetAmt").value);
		var total = bill_passed - net_amt;
		if(isNaN(total)){total =0;}

		document.getElementById("CollectionTdsDed").value = total;
}
// JavaScript Document// JavaScript Document// JavaScript Document// JavaScript Document