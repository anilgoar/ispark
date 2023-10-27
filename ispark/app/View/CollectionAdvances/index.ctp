<script>
	
function collection_advance_validate(val)
{ 
	var company_name = 	document.getElementById('CollectionAdvanceCompanyName').value;
	var branch_name = 	document.getElementById('CollectionAdvanceBranchName').value;
	var finance_year = 	document.getElementById('CollectionAdvanceFinancialYear').value;
	
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

		document.getElementById('CollectionAdvanceBankName').value = 'RTGS';
                document.getElementById('CollectionAdvancePayTypeDates').value = '';
                document.getElementById('CollectionAdvancePayTypeDates').disabled = true;
		document.getElementById('CollectionAdvanceBankName').disabled = true;
	}
	else
	{
		//var x = document.getElementById("CollectionAdvanceBankName");
		//x.remove(x.selectedIndex);
		document.getElementById('CollectionAdvanceBankName').value = '';
		document.getElementById('CollectionAdvanceBankName').disabled = false;
                document.getElementById('CollectionAdvancePayTypeDates').disabled = false;
		
	}	
	collection_advance_data(company_name,branch_name,finance_year,val);
	
}
function collection_advance_data(company_name,branch_name,finance_year,type)
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
				
	xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/get_collection_data/?company_name='+company_name+'&financial_year='+finance_year+'&pay_type='+type,true);
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
	var branch_name = 	document.getElementById('CollectionAdvanceBranchName').value;
        var finance_year = 	document.getElementById('CollectionAdvanceFinancialYear').value;
        var company_name = 	document.getElementById('CollectionAdvanceCompanyName').value;
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
							document.getElementById('CollectionAdvanceAmount').value = '';
						}
						else if(parseInt(amount) == 1)
						{
							alert("Bill No. Already Added");
						}
						else
						{
					 //document.getElementById("nn").readOnly= true;
				     document.getElementById('CollectionAdvanceAmount').value = xmlHttpReq.responseText;
 					get_bill_remark()
						}
				}
				} 
				if(get_check_disable()){
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/get_bill_amount/?bill_no='+val+'&branch_name='+branch_name+'&finance_year='+finance_year+'&company_name='+company_name,true);
				xmlHttpReq.send(null);	
				}
				else
				{alert('Please Click on Save Button First');}
	
}

function get_bill_remark()
{
	var xmlHttpReq = false;
	
	var branch_name = 	document.getElementById('CollectionAdvanceBranchName').value;
	var bill_no = document.getElementById('CollectionAdvanceBillNo').value;

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
				     document.getElementById('CollectionAdvanceRemarks').value = xmlHttpReq.responseText; 
						
				}
				} 
				if(get_check_disable()){
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/get_bill_remark/?bill_no='+bill_no+'&branch_name='+branch_name,true);
				xmlHttpReq.send(null);	
				}
				else
				{alert('Please Click on Save Button First');}
	
}

function save_collection_advance()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionAdvanceCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionAdvanceBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionAdvanceFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;
	//var type 			=	arr[3] =	document.getElementById('type').value;	
	var pay_no 			=	arr[4] =	document.getElementById('CollectionAdvancePayNo').value;
	var pay_amount 		=	arr[5] =	document.getElementById('CollectionAdvancePayAmount').value;
	 
	var bank_name 	=	arr[6] =	document.getElementById('CollectionAdvanceBankName').value;	

	if(type == 'RTGS')
		{
			bank_name 	=	arr[6] = 'RTGS';
			
		}

	deposit_bank 	=	arr[7] =	document.getElementById('CollectionAdvanceDepositBank').value;	
	var pays_date 		=	arr[8] =	document.getElementById('CollectionAdvancePayDates').value;
	var no_of_bills 	=	arr[9] =	document.getElementById('CollectionAdvanceNoOfBills').value;
        if(type == 'RTGS'){document.getElementById('CollectionAdvancePayTypeDates').value = ' ';}
        
	var pay_type_dates 	=	arr[10] =	document.getElementById('CollectionAdvancePayTypeDates').value;

	for(var i =0; i<11; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pays_date='+arr[8]+'&no_of_bills='+arr[9]+'&pay_type_dates='+pay_type_dates;
	
	saveCollectionAdvance(url);
	return false;
}

function saveCollectionAdvance(url)
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
				
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/get_collection_tmp_data/?'+url,true);
				xmlHttpReq.send(null);	
}

function add_collection_advance()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionAdvanceCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionAdvanceBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionAdvanceFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	
	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;

	var pay_no 			=	arr[4] 	=	document.getElementById('CollectionAdvancePayNo').value;
	var pay_amount 		=	arr[5] 	=	document.getElementById('CollectionAdvancePayAmount').value; 
	var bank_name 		=	arr[6] 	=	document.getElementById('CollectionAdvanceBankName').value;
	var deposit_bank 	=	arr[7] 	=	document.getElementById('CollectionAdvanceDepositBank').value;	
	var pays_date 		=	arr[8] 	=	document.getElementById('CollectionAdvancePayDates').value;
	var no_of_bills 	=	arr[9] 	=	document.getElementById('CollectionAdvanceNoOfBills').value;

	var bill_no 		= 	arr[10] =	document.getElementById('CollectionAdvanceCostCenter').value;
	var bill_amount 	= 	arr[11] = 	document.getElementById('CollectionAdvanceAmount').value;
	var bill_passed 	= 	arr[12] = 	bill_amount;
	var tds_ded 		= 	arr[13] = 	'0';
	var net_amt 		= 	arr[14] = 	bill_passed;
	var deduct 			= 	arr[15] = 	'0';
	var status			=	arr[16]	=	'Paid';
	var Remarks 		= 	arr[17] = 	document.getElementById('CollectionAdvanceRemarks').value;
        if(type=='RTGS') document.getElementById('CollectionAdvancePayTypeDates').value =' ';
	var pay_type_dates  =   arr[18] = 	document.getElementById('CollectionAdvancePayTypeDates').value;


	for(var i =0; i<19; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pay_dates='+arr[8]+'&no_of_bills='+arr[9]+'&bill_no='+arr[10]+'&bill_amount='+arr[11]+'&bill_passed='+arr[12]+'&tds_ded='+arr[13]+'&net_amount='+arr[14]+'&deduction='+arr[15]+'&status='+arr[16]+'&remarks='+arr[17]+'&pay_type_dates='+pay_type_dates;
	
	if(validate_collection_advance_data())
	{
		addCollectionAdvance(url);
		blank_bill_fields();
	}
	return false;	
}

function blank_bill_fields()
{
document.getElementById('CollectionAdvanceCostCenter').value = '';
document.getElementById('CollectionAdvanceAmount').value = '';
document.getElementById('CollectionAdvanceRemarks').value = '';

}
function addCollectionAdvance(url)
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
				
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/get_collection_tmp_bill_data/?'+url,true);
				xmlHttpReq.send(null);	
	
}

function deletesCollectionAdvance(val)
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
						//location.reload();
					 //document.getElementById("qq").readOnly= true;
				     //document.getElementById('qq').innerHTML = xmlHttpReq.responseText; 
					 //location.reload();
					
				}
				} 
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/delete_advance_particular/?id='+val,true);
				xmlHttpReq.send(null);	

	return false;
}
function add_other_collection_advance()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionAdvanceCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionAdvanceBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionAdvanceFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	
	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;

	var pay_no 			=	arr[4] 	=	document.getElementById('CollectionAdvancePayNo').value;
	var pay_amount 		=	arr[5] 	=	document.getElementById('CollectionAdvancePayAmount').value; 
	var bank_name 		=	arr[6] 	=	document.getElementById('CollectionAdvanceBankName').value;
	var deposit_bank 	=	arr[7] 	=	document.getElementById('CollectionAdvanceDepositBank').value;	
	var pays_date 		=	arr[8] 	=	document.getElementById('CollectionAdvancePayDates').value;
	var no_of_bills 	=	arr[9] 	=	document.getElementById('CollectionAdvanceNoOfBills').value;	

	var other_deduction = 	arr[10] =	document.getElementById('CollectionAdvanceOtherDeduction').value;
	var other_remarks 	= 	arr[11] = 	document.getElementById('CollectionAdvanceOtherRemarks').value;
	var pay_type_dates	=	arr[12] = 	document.getElementById('CollectionAdvancePayTypeDates').value;
	
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
				
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/other_deduction/?'+url,true);
				xmlHttpReq.send(null);	 
	
}
function otherBillDeduction(url)
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
				     document.getElementById('bqq').innerHTML = xmlHttpReq.responseText; 
					 location.reload();
					
				}
				} 
				
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/other_bill_deduction/?'+url,true);
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
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/delete_other_deduction/?id='+val,true);
				xmlHttpReq.send(null);	

	return false;
}
function deletesBillDeduction(val)
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
				xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/collection_advances/delete_bill_other_deduction/?id='+val,true);
				xmlHttpReq.send(null);	

	return false;
}

function validate_collection_advance_data()
{
    var bill_amount		=	parseFloat(document.getElementById("CollectionAdvanceAmount").value);
    var bill_passed		=	parseFloat(document.getElementById("CollectionAdvanceAmount").value);
    var bill_tds		=	'0';
    var net_amt			=	parseFloat(document.getElementById("CollectionAdvanceAmount").value);		
    var bill_deduct		=	'0';
    var coll_payment    =	parseFloat(document.getElementById("CollectionAdvancePayAmount").value).toFixed(2);
    var status			=	'Paid';
    return true; 
}
function validate_advance_amount()
{
	total =  document.getElementById('CollectionAdvancePayAmount').value;
	idx 	= document.getElementById('idx').value;
	//idx2 	= document.getElementById('idx2').value;
	//idx3 	= document.getElementById('idx3').value;

	var str = idx.split(",");
	
	

	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionAdvanceParticulars"+str[i]+"BillAmount";
		bill_amount		=	parseFloat(document.getElementById(bill_amount).value).toFixed(2);
		
		bill_passed 	=	"TMPCollectionAdvanceParticulars"+str[i]+"BillPassed";
		bill_passed		=	parseFloat(document.getElementById(bill_passed).value).toFixed(2);
		
		bill_tds		=	"TMPCollectionAdvanceParticulars"+str[i]+"TdsDed";
		bill_tds		=	parseFloat(document.getElementById(bill_tds).value).toFixed(2);
		
		net_amt			=	"TMPCollectionAdvanceParticulars"+str[i]+"NetAmount";
		net_amt			=	parseFloat(document.getElementById(net_amt).value).toFixed(2);
		
		bill_deduct		=	"TMPCollectionAdvanceParticulars"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = parseFloat(bill_amount - bill_passed).toFixed(2);
		bill_deduct		=	parseFloat(document.getElementById(bill_deduct).value).toFixed(2);

		coll_payment	=	parseFloat(document.getElementById("CollectionAdvancePayAmount").value).toFixed(2);
		
		status			=	"TMPCollectionAdvanceParticulars"+str[i]+"Status";
		status			=	document.getElementById(status).value;
		
		if(parseFloat(bill_amount)<(parseFloat(bill_passed)))
		{
			alert('Bill Passed Amount is not More Than Bill Amount');
			break;
		}
		else if(parseInt(bill_amount)<parseInt(bill_tds))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			break;
		}
		
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds))
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = net_amt = parseFloat(bill_passed-bill_tds).toFixed(2);			
		}
		else if(!isNaN(net_amt) && status == 'paid')
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = bill_tds = parseFloat(bill_passed-net_amt).toFixed(2);
		}

		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=parseInt(bill_tds+net_amt))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+parseFloat(bill_tds).toFixed(2)+"net_amt="+parseFloat(net_amt).toFixed(2));
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"Deduction").value = 0;
		}
				
		else if(parseFloat(bill_amount).toFixed(2)<parseFloat(bill_deduct+bill_tds).toFixed(2))
		{
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			break;
		}

		else if(!isNaN(bill_passed))
		{
			if(parseFloat(bill_amount)!=parseFloat(bill_deduct+bill_passed).toFixed)
			{
			alert("Always(Bill Amount("+parseFloat(bill_amount).toFixed(2)+") - Bill Passed("+parseFloat(bill_passed).toFixed(2)+") = Deduction("+parseFloat(bill_deduct).toFixed(2)+"))");
			break;
			}
		}
		else if(parseFloat(coll_payment)<parseFloat(net_amt)) 
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			break;
		}

			bill_total +=net_amt;		
	}
	
	
       
}
 /// for other deduction entry input box
function other_deduct_validate()
{
	total =  document.getElementById('CollectionAdvancePayAmount').value; 
	idx 	= document.getElementById('idx').value;
	idx2 	= document.getElementById('idx2').value;
        idx3 	= document.getElementById('idx3').value;
	other_deduct2		=	parseInt(document.getElementById("CollectionAdvanceOtherDeduction").value);
	
	var str = idx.split(",");
	var str2 = idx2.split(",");
	var str3 = idx3.split(",");


	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionAdvanceParticulars"+str[i]+"BillAmount";
		bill_amount		=	parseInt(document.getElementById(bill_amount).value);
		
		bill_passed 	=	"TMPCollectionAdvanceParticulars"+str[i]+"BillPassed";
		bill_passed		=	parseInt(document.getElementById(bill_passed).value);
		
		bill_tds		=	"TMPCollectionAdvanceParticulars"+str[i]+"TdsDed";
		bill_tds		=	parseInt(document.getElementById(bill_tds).value);
		
		net_amt			=	"TMPCollectionAdvanceParticulars"+str[i]+"NetAmount";
		net_amt			=	parseInt(document.getElementById(net_amt).value);
		
		bill_deduct		=	"TMPCollectionAdvanceParticulars"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = bill_amount - bill_passed;
		bill_deduct		=	parseInt(document.getElementById(bill_deduct).value);

		coll_payment	=	parseInt(document.getElementById("CollectionAdvancePayAmount").value);
		
		status			=	"TMPCollectionAdvanceParticulars"+str[i]+"Status";
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
		
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds))
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = net_amt = bill_passed-bill_tds;			
		}
		else if(!isNaN(net_amt))
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = bill_tds = bill_passed-net_amt;
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
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"Deduction").value = 0;
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
		else if(parseInt(coll_payment)<parseInt(net_amt))
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
        for(i=0; i<str3.length-1; i++)	
	{		
		other_deduct 	=	"OtherBillTMPDeduction"+str3[i]+"OtherDeduction";
		other_deduct	=	parseFloat(document.getElementById(other_deduct).value).toFixed(2);
		
		OtherRemarks 	=	"OtherBillTMPDeduction"+str3[i]+"OtherRemarks";
		OtherRemarks	=	document.getElementById(OtherRemarks).value;
		
		if(parseFloat(other_deduct+total).toFixed(2)<parseFloat(bill_total).toFixed(2))
		{
			alert("Total of All Net Bill  And Other Deduction is Not More Than Cheque/RTGS Amount");
			break;
		}
		deduct_total +=other_deduct; 
		
	}

	
		return true;
}

// checking all data in collectin/payment entry on submit button
function validate_save_advance()
{
	total =  parseFloat(document.getElementById('CollectionAdvancePayAmount').value).toFixed(2);
	
	
	idx 	= document.getElementById('idx').value;
	idx2 	= document.getElementById('idx2').value;
	idx3 	= document.getElementById('idx3').value;

	var str = idx.split(",");
	var str2 = idx2.split(",");
	var str2 = idx3.split(",");

	var bill_total=0;
	var deduct_total =0;
	
	for(i=0; i<str.length-1; i++)
	{
		
		bill_amount 	=	"TMPCollectionBillAdvance"+str[i]+"BillAmount";
		bill_amount		=	parseFloat(document.getElementById(bill_amount).value).toFixed(2);
		
		bill_passed 	=	"TMPCollectionBillAdvance"+str[i]+"BillPassed";
		bill_passed		=	parseFloat(document.getElementById(bill_passed).value).toFixed(2);
		
		bill_tds		=	"TMPCollectionBillAdvance"+str[i]+"TdsDed";
		bill_tds		=	parseFloat(document.getElementById(bill_tds).value).toFixed(2);
		
		net_amt			=	"TMPCollectionBillAdvance"+str[i]+"NetAmount";
		net_amt			=	parseFloat(document.getElementById(net_amt).value).toFixed(2);
		
		bill_deduct		=	"TMPCollectionBillAdvance"+str[i]+"Deduction";
		//var bill_deduct_copy = bill_deduct;
		document.getElementById(bill_deduct).value = parseFloat(bill_amount - bill_passed).toFixed(2);
		bill_deduct		=	parseFloat(document.getElementById(bill_deduct).value).toFixed(2);

		coll_payment	=	parseFloat(document.getElementById("CollectionAdvancePayAmount").value).toFixed(2);
		
		status			=	"TMPCollectionBillAdvance"+str[i]+"Status";
		status			=	document.getElementById(status).value;
		
		if(parseFloat(bill_amount)<(parseFloat(bill_passed)))
		{
			alert('Bill Passed Amount is not Greater Than Bill Amount');
			return false;
		}
		else if(parseInt(bill_amount)<(parseInt(bill_tds)))
		{
			alert("Bill TDS is Not Greater Than Bill Amount");
			return false;
		}
		
		else if(status == '')
		{
			alert("Please Select Status");
		}

		else if(!isNaN(bill_tds) && status=='paid')
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = net_amt = parseFloat(bill_passed-bill_tds).toFixed(2);			
		}
		else if(!isNaN(net_amt)  && status=='paid')
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"NetAmount").value = bill_tds = parseFloat(bill_passed-net_amt).toFixed(2);
		}

		else if(status == 'paid' && !isNaN(bill_tds) && !isNaN(net_amt))
		{
			if(parseInt(bill_passed)!=parseInt(parseInt(bill_tds)+parseInt(net_amt)))
			{
				alert("Always(Bill Passed = Bill TDS + Net Amount) bill_tds="+bill_tds+"net_amt="+net_amt);
				return false;
			}
		}
		else if(status == 'part payment')
		{
			document.getElementById("TMPCollectionAdvanceParticulars"+str[i]+"Deduction").value = 0;
		}
				
		else if(parseInt(bill_amount)<=parseInt(parseInt(bill_deduct)+parseInt(bill_tds)))
		{
			alert("Deduction + TDS is Not Greater Than Bill Amount");
			return false;
		}

		else if(!isNaN(bill_passed))
		{
			if(parseFloat(bill_amount).toFixed(2)!=parseFloat(bill_deduct+bill_passed).toFixed(2))
			{
			alert("Always(Bill Amount("+bill_amount+") - Bill Passed("+bill_passed+") = Deduction("+bill_deduct+"))");
			return false;
			} 
		}
		else if(parseFloat(coll_payment)<parseFloat(net_amt))
		{
			alert("Bill Passed Amount is Not Greater Than Check Amount");
			return false;
		}

			bill_total +=net_amt;		
	}
	
	for(i=0; i<str2.length-1; i++)	
	{		
		other_deduct 	=	"OtherTMPDeduction"+str2[i]+"OtherDeduction";
		other_deduct	=	parseFloat(document.getElementById(other_deduct).value).toFixed(2);
		
		OtherRemarks 	=	"OtherTMPDeduction"+str2[i]+"OtherRemarks";
		OtherRemarks	=	parseFloat(document.getElementById(OtherRemarks).value).toFixed(2);
		
		deduct_total +=parseFloat(other_deduct).toFixed(2);
	}
	
	var str3 = idx3.split(",");
for(i=0; i<str3.length-1; i++)	
	{		
		other_deduct 	=	"OtherBillTMPDeduction"+str3[i]+"OtherDeduction";
		other_deduct	=	parseFloat(document.getElementById(other_deduct).value).toFixed(2);
		
		OtherRemarks 	=	"OtherBillTMPDeduction"+str3[i]+"OtherRemarks";
		OtherRemarks	=	document.getElementById(OtherRemarks).value;
		
		deduct_total +=parseFloat(other_deduct).toFixed(2);
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
    var status = document.getElementById("CollectionAdvanceStatus").value;
                if(status=='part payment')
                {
                    
                }
                else
                {
		bill_passed		=	parseFloat(document.getElementById("CollectionAdvanceBillPassed").value).toFixed(2);
		bill_tds		=	parseFloat(document.getElementById("CollectionAdvanceTdsDed").value).toFixed(2);
		var total = bill_passed - bill_tds;
		if(isNaN(total)){total =0;}
		document.getElementById("CollectionAdvanceNetAmt").value = total;
            }
}
function get_tds()
{
                var status = document.getElementById("CollectionAdvanceStatus").value;
                if(status=='part payment')
                {
                    
                }
                else
                {
                    bill_passed		=	parseFloat(document.getElementById("CollectionAdvanceBillPassed").value).toFixed(2);
		net_amt		=	parseFloat(document.getElementById("CollectionAdvanceNetAmt").value).toFixed(2);
		var total = parseFloat(bill_passed - net_amt).toFixed(2);
		if(isNaN(total)){total =0;}

		document.getElementById("CollectionAdvanceTdsDed").value = total;
                }
		
}


function add_bill_other_collection_advance()
{
	var arr =[]; 
	var company_name	=	arr[0] =	document.getElementById('CollectionAdvanceCompanyName').value;	
	var branch_name 	=	arr[1] =	document.getElementById('CollectionAdvanceBranchName').value;	
	var finance_year 	=	arr[2] =	document.getElementById('CollectionAdvanceFinancialYear').value;	
	var pay_types 		= 	document.getElementsByName('type');
	var type;

	
	for(var i = 0; i < pay_types.length; i++){
    	if(pay_types[i].checked){
        	type = pay_types[i].value;
    		}
	}
	arr[3] = type;

	var pay_no 			=	arr[4] 	=	document.getElementById('CollectionAdvancePayNo').value;
	var pay_amount 		=	arr[5] 	=	document.getElementById('CollectionAdvancePayAmount').value; 
	var bank_name 		=	arr[6] 	=	document.getElementById('CollectionAdvanceBankName').value;
	var deposit_bank 	=	arr[7] 	=	document.getElementById('CollectionAdvanceDepositBank').value;	
	var pays_date 		=	arr[8] 	=	document.getElementById('CollectionAdvancePayDates').value;
	var no_of_bills 	=	arr[9] 	=	document.getElementById('CollectionAdvanceNoOfBills').value;	

        var bill_no_other       =    arr[10] 	=	document.getElementById('CollectionAdvanceBillNoOther').value;	

	var other_deduction = 	arr[11] =	document.getElementById('CollectionAdvanceBillOtherDeduction').value;
	var other_remarks 	= 	arr[12] = 	document.getElementById('CollectionAdvanceBillOtherRemarks').value;
	var pay_type_dates	=	arr[13] = 	document.getElementById('CollectionAdvancePayTypeDates').value;
	
	for(var i =0; i<14; i++)
	{
		if(arr[i] == '')
		{
			alert("Please fill All Fields"+arr[i]);
			return false;
		}
	}

	var url = 'company_name='+arr[0]+'&branch_name='+arr[1]+'&financial_year='+arr[2]+'&pay_type='+arr[3]+'&pay_no='+arr[4]+'&pay_amount='+arr[5]+'&bank_name='+arr[6]+'&deposit_bank='+arr[7]+'&pays_date='+arr[8]+'&no_of_bills='+arr[9]+'&other_deduction='+arr[11]+'&other_remarks='+arr[12]+'&pay_type_dates='+pay_type_dates+'&bill_no='+arr[10];
	
	if(other_deduct_validate())
	{
		otherBillDeduction(url);
		blank_bill_fields();
	}
	return false;	
}

// JavaScript Document// JavaScript Document// JavaScript Document// JavaScript Document
</script>

<?php //print_r($payment_master); 
$pay_type = 'Cheque'; 
$flag1 =false;

if($payment_master['0']!='') {$flag1 =true;}

?>
<?php echo $this->Form->create('CollectionAdvance',array('class'=>'form-horizontal','action'=>'add','enctype'=>'multipart/form-data')); ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>Advance Collection</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
					</h4>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Select Company</label>
						<?php
							$company = array();
							foreach($company_master as $post):
								$company[$post['Addcompany']['company_name']] = $post['Addcompany']['company_name'];
							endforeach;
						?>
						<div class="col-sm-3">
							<?php echo $this->Form->input('company_name', array('options' => $company,'label' => false, 'div' => false,'class'=>'form-control','selected' => $payment_master['1'],'onChange'=>'get_costcenter5(this)')); ?>
						</div>
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-3"><div id="mm">
								<?php
									foreach($branch_master as $post):
									$data[$post['Addbranch']['branch_name']] = $post['Addbranch']['branch_name'];
									endforeach; 
								?>
							<?php	echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Branch','selected' => $payment_master['3'],'required'=>true)); ?></div>
						</div>
                        
                        
                        
					</div>

						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Finance Year</label>
						<div class="col-sm-3">
						
							<?php echo $this->Form->input('financial_year', array('options' => $finance_yearNew,'empty' => 'Select Year','label' => false, 'div' => false,'class'=>'form-control','selected' => $payment_master['2'])); ?>						</div>
						<?php if($payment_master['4'] =='') { ?>
						<label class="col-sm-2 control-label">Select</label>
							
								<div class="col-sm-3">
									<div class="radio-inline">
										<label>
											<input type="radio" name="type" value = "Cheque" id = 'type' onClick="return collection_advance_validate(this.value)" checked>Cheque
											<i class="fa fa-circle-o"></i>
										</label>
									</div>
									<div class="radio-inline">
										<label>
											<input type="radio" name="type" value="RTGS" id="type"  onClick="return collection_advance_validate(this.value)" >RTGS
											<i class="fa fa-circle-o"></i>
										</label>
									</div>                                   
                        		</div>
                              <?php } else { ?>
						<label class="col-sm-2 control-label">Select</label>
								<div class="col-sm-3">
									<div class="radio-inline">
										<label>
											<input type="radio" name="type" value = "Cheque" id = 'type' onClick="collection_advance_validate(this.value)" <?php if($payment_master['4'] == 'Cheque') {echo "checked"; $pay_type = 'Cheque';} else {echo "disabled";} ?>>Cheque
											<i class="fa fa-circle-o"></i>
										</label>
									</div>
									<div class="radio-inline">
										<label>
											<input type="radio" name="type" value="RTGS" id="type"  onClick="return collection_advance_validate(this.value)" <?php if($payment_master['4'] == 'RTGS') { echo "checked"; $pay_type = 'RTGS';}else {echo "disabled";} ?>>RTGS
											<i class="fa fa-circle-o"></i>
										</label>
									</div>                                   
                        		</div>                              
                             <?php } ?>
                               
					</div>
						<div class="form-group has-success has-feedback">

							<div id="nn">
								<label class="col-sm-2 control-label"><?=$pay_type?> No.</label>
						<div class="col-sm-3"> <?php $flag = false; if($pay_type == 'RTGS') {$flag =true;}?>
							<?php	echo $this->Form->input('pay_no', array('label'=>false,'class'=>'form-control','value' => $payment_master['5'],'placeholder' => 'Cheque Number','required'=>true,'readonly' => $flag1,'onkeypress'=>'return isNumberKey(event)','maxlength'=>'6')); ?>
						</div>
						

						<label class="col-sm-2 control-label"><?=$pay_type?> Amount</label>
						<div class="col-sm-3">
							<?php	echo $this->Form->input('pay_amount', array('label'=>false,'class'=>'form-control','value' => $payment_master['8'],'placeholder' => 'Amount','required'=>true,'readonly'=>$flag1,'onkeypress'=>'return isNumberKey(event)','maxlength'=>'12')); ?>
						</div>
                        </div>
					</div>
                        <?php $disable = false;
						foreach($bank_master as $post):
						$bank[$post['Bank']['bank_name']] = $post['Bank']['bank_name'];
						endforeach;
						if($pay_type == 'RTGS'){$bank['RTGS'] = 'RTGS'; $disable = true;}
                        ?>
						<div class="form-group has-success has-feedback">
                        
 						<label class="col-sm-2 control-label">Cheque Date</label>
						<div class="col-sm-3">
                        	<?php  
							$date = date_create($payment_master['11']);
							$date = date_format($date,'d-m-Y');
							?>	

							<?php	echo $this->Form->input('pay_type_dates', array('label'=>false,'class'=>'form-control','type'=>'text','value' => $date,'required'=>true,'disabled'=>$disable,'readonly'=>$flag1, 'onClick'=>"displayDatePicker('data[CollectionAdvance][pay_type_dates]');")); ?>
						</div>                       
                        
						<label class="col-sm-2 control-label">Drawn Bank</label>
						<div class="col-sm-3">

							<?php	echo $this->Form->input('bank_name', array('label'=>false,'class'=>'form-control','value' => $payment_master['6'],'placeholder'=>'Select Bank','required'=>true,'readonly'=>$flag1,'disabled'=>$disable)); ?>
						</div>
					</div>

					<div class="form-group has-success has-feedback">

						<label class="col-sm-2 control-label">Deposit Bank</label>
						<div class="col-sm-3">
							<?php	echo $this->Form->input('deposit_bank', array('label'=>false,'class'=>'form-control','value' => $payment_master['9'],'options'=>$bank,'empty'=>'Select Bank', 'required'=>true,'readonly'=>$flag1,'disabled'=>$disable)); ?>
						</div>

						<label class="col-sm-2 control-label">Payment Date</label>
						<div class="col-sm-3">
							<?php  
							$date = date_create($payment_master['7']);
							$date = date_format($date,'d-m-Y');
							?>	
							<?php	echo $this->Form->input('pay_dates', array('label'=>false,'class'=>'form-control','onClick'=>"displayDatePicker('data[CollectionAdvance][pay_dates]');",'value' => $date,'placeholder' => 'Select Date','required'=>true,'readonly'=>$flag1,'disabled'=>$disable)); ?>
						</div>
					</div>
                    
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-3">
							<?php	echo $this->Form->input('no_of_bills', array('label'=>false,'class'=>'form-control','type'=>'hidden','value' => '0','required'=>true,'onkeypress'=>'return isNumberKey(event)','readonly'=>$flag1)); ?>
						</div>                    
						<label class="col-sm-2 control-label">&nbsp;</label>
						<div class="col-sm-2">
							<button onclick="return save_collection_advance()" class="btn btn-success btn-label-left">Save</button> &nbsp; &nbsp; &nbsp;	
                            <?php echo $this->Html->link('Refresh',array('action'=>'back'),array('class'=>'btn btn-danger')); ?>
						</div>
					</div>
                                       
					</div>
					</div>
				</div>
			</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-content">
				<h4 class="page-header">Advance Collection</h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr>
						<th>Sr. No.</th>
						<th>Cost Center</th>
						<th>Bill Amt</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
					
					<tr>
						<th></th>
						<th><?php echo $this->Form->input('cost_center' ,array('label' =>false,'options'=>$cost_list,'empty'=>'select','placeholder' => 'Cost Center','class' => 'form-control')); ?></th>
						<th><?php echo $this->Form->input('amount' ,array('label' =>false,'placeholder' => 'Bill Amount','onBlur'=>'','class' => 'form-control','value'=>'')); ?></th>
						<th><?php echo $this->Form->input('remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control','onBlur'=>'validate_advance_data();')); ?></th>
						<th><button onclick="return add_collection_advance()"> ADD</button></th>
					</tr>
				</table> 
                		<div id="oo">
<table>
						<?php  $i = 0; $idx ="";$net=0;
						foreach ($result as $post): ?>
							<?php $idx.=$post['TMPCollectionBillAdvance']['id'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('TMPCollectionBillAdvance.'.$post['TMPCollectionBillAdvance']['id'].'.bill_no',
							array('label'=>false,'options'=>$cost_list,'value'=>$post['TMPCollectionBillAdvance']['bill_no'],'class'=>'form-control','required'=>true)); ?></td>
                            
							<td><?php echo $this->Form->input('TMPCollectionBillAdvance.'.$post['TMPCollectionBillAdvance']['id'].'.bill_amount',
							array('label'=>false,'value'=>$post['TMPCollectionBillAdvance']['bill_amount'],'class'=>'form-control',
							'required'=>true,'onBlur'=>'validate_advance_amount();','onkeypress'=>'return isNumberKey(event)')); ?></td>                            
                                                        
                            <td><?php echo $this->Form->input('TMPCollectionBillAdvance.'.$post['TMPCollectionBillAdvance']['id'].'.remarks',
							array('label'=>false,'value'=>$post['TMPCollectionBillAdvance']['remarks'],'class'=>'form-control','required'=>true,
							'onBlur'=>'validate_advance_amount();')); ?></td>

							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['TMPCollectionBillAdvance']['id']; ?>" 
							onClick ="return deletesCollectionAdvance(this.value)">Delete</button> </td>
							</tr>
						<?php $net+=$post['TMPCollectionBillAdvance']['net_amount'];
						endforeach; ?><?php unset($TMPCollectionBillAdvance); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
</table>                        
                        </div>                
				</div>
			</div>
		</div>
	</div>


                            
                            
				
                        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        	<tr><th>Net Amount</th><td><?=$net?></td></tr>
                            <tr><th>Cheque/RTGS</th><td><?=$payment_master['8']?></tr>
                        </table> 
                        <div class="form-group">
                            <div class="col-sm-3" id="image_preview">
                                <img id="previewing" src="app/webroot/img/noimage.png" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                Select File <?php echo $this->Form->input('PaymentFile', array('label'=>false,'type' => 'file')); ?>
                            </div>   
			</div>
                        
          				<button onclick="return validate_save_advance();" class="btn btn-success btn-label-left">Submit </button>              
                        
				</div>
			</div>
		</div>
	</div>

<script>
    $(document).ready(function (e) {
        
$(function() {
        $("#CollectionPaymentFile").change(function() {
			
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg"];	
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
			{
			$('#previewing').attr('src','noimage.png');
			$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
			}
            else
			{
                var reader = new FileReader();	
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }		
        });
    });
	function imageIsLoaded(e) { 
		$("#file").css("color","green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '250px');
		$('#previewing').attr('height', '230px');
	};
});
    </script>