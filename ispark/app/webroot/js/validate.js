function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }

/*       
function password_check()
{
    var password1 = document.getElementById("UserPassword").value;
    var password2 = document.getElementById("UserPassword2").value;
    
    if(password1 != password2)
    {
        alert("password did not match");
        return false;
    }
    return true;
}
*/
function get_costcenter(val)
{
	var branch=document.getElementById('InitialInvoiceBranchName').value;
	if(branch ==''){return false;}
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/get_costcenter/?branch='+branch,true);
				xmlHttpReq.send(null);
} 

function get_costcenter2(val)
{
	var branch=document.getElementById('InitialInvoiceBranchName').value;
	if(branch ==''){return false;}
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/get_costcenter/?branch='+branch,true);
                                
				xmlHttpReq.send(null);
}   
function get_costcenter3(val)
{
    if(val ==''){return false;}
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
            document.getElementById('ProvisionCostCenter').innerHTML = xmlHttpReq.responseText;
	}
    } 
    xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/get_costcenter/?branch='+val,true);
    
    xmlHttpReq.send(null);
}

function check_po_number()
{
    var po_number = document.getElementById('InitialInvoicePoNo').value;
    if(po_number !='')
    {
        var month = document.getElementById('InitialInvoiceMonth').value;
        var grnd = document.getElementById('InitialInvoiceTotal').value;
        var finance = document.getElementById('InitialInvoiceFinanceYear').value;
        var flag = true;

        var aj = $.ajax({type:"Post",async: false,cache:false,url: "check_po_number",
            data:{po_number:po_number,month:month,grnd:grnd,finance:finance}, success: function(data)
            {
                return data;
            }
        });
        var data = aj.responseText;
        var resArr=data.split("##");
        var msg = resArr[0]; 
        var status=resArr[1]; 

        if(msg=='OK' && status==1)
        {
            return true;
        }
        else
        {
            alert(msg);
            return false;
        }
    }
    else
    {
        return true;
    }
}


function check_provision()
{
    var xmlHttpReq = false;
    var	cost_center     = document.getElementById('InitialInvoiceCostCenter').value;
    var	finance_year    = document.getElementById('InitialInvoiceFinanceYear').value;
    var	month           = document.getElementById('InitialInvoiceMonth').value;
    var total            = document.getElementById('InitialInvoiceTotal').value;
    var url='http://122.160.5.130/ispark/provisions/provision_check?cost_center='+cost_center+'&finance_year='+finance_year+'&month='+month+'&total='+total;
    var flag=false;
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
            var str = xmlHttpReq.responseText;
            var res = str.split("-"); 
            if(res[0] == 1)
            {
                flag = true;
            }
            else if(res[0] == 2)
            {
                flag = true;
               //flag =  confirm("Provision Amount "+res[1]+" is greater than Bill Amount "+total+". Do You want to Continue");
            }
            else
            {
                flag = false;
                alert("Provision Amount is Less Than Bill Amount. Please contact to Admin");
            }
	}
    }
    xmlHttpReq.open('POST',url,false);
    
    xmlHttpReq.send(null);
    
    if(!flag)
    {
     return false;
    }
    else
    {  
      return check_po_number();
    }
}

function validate()
{
	//var check=document.getElementById('par_total').value;
	var idqty=document.getElementById('idx').value;
	var check1=document.getElementById('InitialInvoiceGrnd').value;
	var check2=document.getElementById('AddInvParticularRate').value;	
	var check3=document.getElementById('AddInvDeductParticularRate').value;
	
	check2=""+check2;
	check3=""+check3;
	
	if(check2!="")
	{
		alert("You did not added Particular amount, Please click Add Button");
		return false;
	}

	if(check1<0)
	{
		alert("Deduction Amount is not Greater Then Particulars Amount");
		return false;
	}	

	if(check3!="")
	{
		alert("You did not added Deduction amount, Please click Add Button");
		return false;
	}

	var i,total=0,amount = '';
	var str=idqty.split(",");
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		try{total+=parseInt(document.getElementById(amount).value);}
		catch(err)		
		{
			total += 0;
		}
	}

	if(total<1)
	{
		alert("Please Add Particular");
		return false;
	}
	return check_provision();
}

function check_provision_edit()
{
    var xmlHttpReq = false;
    var	cost_center     = document.getElementById('InitialInvoiceCostCenter').value;
    var	finance_year    = document.getElementById('InitialInvoiceFinanceYear').value;
    var	month           = document.getElementById('InitialInvoiceMonth').value;
    var total            = document.getElementById('InitialInvoiceTotal').value;
    var id            = document.getElementById('InitialInvoiceId').value;
    var url='http://122.160.5.130/ispark/provisions/provision_check_edit?cost_center='+cost_center+'&finance_year='+finance_year+'&month='+month+'&total='+total+'&id='+id;
    var flag;
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
            if(xmlHttpReq.responseText == 1)
            flag = true;
            else
            { flag = false;
                alert("Provision Amount is Less Than Bill Amount. Please contact to Admin");
            }
	}
    } 
    xmlHttpReq.open('POST',url,false);
    
    xmlHttpReq.send(null);
    return flag;
}

function validate_edit()
{
	var check1=document.getElementById('InitialInvoiceGrnd').value;
	if(check1<0)
	{
		alert("Deduction Amount is not Greater Then Particulars Amount");
		return false;
	}
	return check_provision_edit();
}
function getStream(val)
{
	var stream=document.getElementById('CostCenterMasterStream').value;
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
						//alert(val);
 				     var MRP = xmlHttpReq.responseText.split("#"); 

					 document.getElementById("process").readOnly= true;
					 	
				     document.getElementById('process').innerHTML = xmlHttpReq.responseText;
				}
				}  
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/getstream/?id='+stream,true);
                                
				xmlHttpReq.send(null);
}
function getClient(val)
{
	var branch=document.getElementById('CostCenterMasterBranch').value;
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
						//alert(val);
 				     var MRP = xmlHttpReq.responseText.split("#"); 

					 document.getElementById("client").readOnly= true;
					 	
				     document.getElementById('client').innerHTML = xmlHttpReq.responseText;
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/getClient/?branch_name='+branch,true);
                                
				xmlHttpReq.send(null);
}
function getDescription(val)
{
	var cost_center=document.getElementById('InitialInvoiceCostCenter').value;
	var month=document.getElementById('InitialInvoiceMonth').value;
	
	if(cost_center == ''){return false;}
	if(month == ''){return false;}	
	
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
						//alert(cost_center);
					 	
				    	document.getElementById('InitialInvoiceInvoiceDescription').value = xmlHttpReq.responseText+' - '+month;
					}
				} 
				xmlHttpReq.open('POST','AddInvParticulars/getdescription/?cost_center='+cost_center,true);
                                
				xmlHttpReq.send(null);
}

function getAmount(val)
{
	var rate=document.getElementById('AddInvParticularQty').value;
	var amount=document.getElementById('AddInvDeductParticularAmount').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('AddInvParticularAmount').value = (val*rate).toFixed(0);
	grandTotal();
}

function getBlank()
{
	document.getElementById('particulars').value = '';
	document.getElementById('AddInvParticularRate').value = '';
	document.getElementById('AddInvParticularQty').value = '';
	document.getElementById('AddInvParticularAmount').value = 0;
	location.reload();
}
function getBlank1()
{
	document.getElementById('AddInvDeductParticularParticulars').value = '';
	document.getElementById('AddInvDeductParticularRate').value = '';
	document.getElementById('AddInvDeductParticularQty').value = '';
	document.getElementById('AddInvDeductParticularAmount').value = 0;
	location.reload();
}

function getAmountDeduct(val)
{
	var rate=document.getElementById('AddInvDeductParticularQty').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('AddInvDeductParticularAmount').value = (val*rate).toFixed(0);
	grandTotal();
}
function grandTotal()
{
	var Part=document.getElementById('AddInvParticularAmount').value;
	var Ded=document.getElementById('AddInvDeductParticularAmount').value;
	var idqty=document.getElementById('idx').value;
	var iddqty=document.getElementById('idxd').value;	
	
	var str=idqty.split(",");
	var strd=iddqty.split(",");

	var i,total=0;
	
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		total+=parseInt(document.getElementById(amount).value);
	}
	for(i=0; i<strd.length-1; i++)
	{
		amount="DeductParticular"+strd[i]+"Amount";
		total-=parseInt(document.getElementById(amount).value);
	}
        var Total = 0,serviceTax=0,GST=0;
	try{
            serviceTax = document.getElementById('InitialInvoiceApplyServiceTax').value;
            
            if(serviceTax=='0')
            Total = document.getElementById('InitialInvoiceTotal').value = ((parseInt(total)+parseInt(Part))-parseInt(Ded)).toFixed(0);
            else
            {Total = ((parseInt(total)+parseInt(Part))-parseInt(Ded)).toFixed(0);}
        }
        catch(err){Total = ((parseInt(total)+parseInt(Part))-parseInt(Ded)).toFixed(0);}
        
	GST = document.getElementById('InitialInvoiceApplyGst').value;
        
        var IGST=0,CGST=0,SGST=0;
        
        if(GST==1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType=='Integrated')
           {
               try{ IGST = document.getElementById('InitialInvoiceIgst').value = ((Total*18)/100).toFixed(0); }	
                catch(err)
                { var IGST = 0; }
           }
           else
           {
               try{ CGST = document.getElementById('InitialInvoiceCgst').value = ((Total*9)/100).toFixed(0); }	
                catch(err)
                { var CGST = 0; }
                
                try{ SGST = document.getElementById('InitialInvoiceSgst').value = ((Total*9)/100).toFixed(0); }	
                catch(err)
                { var SGST = 0; }
           }
           if(serviceTax=='1')
        {Total = 0;}
           document.getElementById('InitialInvoiceGrnd').value = parseInt(Total)+parseInt(IGST)+parseInt(CGST)+parseInt(SGST);
        }
        else
        {
	var tax,sbctax,krishitax;
	try{ tax = document.getElementById('InitialInvoiceTax').value = ((Total*14)/100).toFixed(0); }	
	catch(err)
	{ var tax = 0; }
	try{ sbctax = document.getElementById('InitialInvoiceSbctax').value = ((Total*0.5)/100).toFixed(0); }	
	catch(err)
	{ var sbctax = 0; }
        
	try{ krishitax = document.getElementById('InitialInvoiceKrishiTax').value = ((Total*0.5/100)).toFixed(0); }	
	catch(err)
	{ var krishitax = 0; }
        if(serviceTax=='1')
        {Total = 0;}
        document.getElementById('InitialInvoiceGrnd').value = parseInt(Total)+parseInt(tax)+parseInt(sbctax)+parseInt(krishitax);
      }
	
	
}
function deletes2(val)
{
	var flag = delete_part2(val);
	return false;	
}

function delete_part2(id)
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
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/delete_particular2/?id='+id,true);
                                
				xmlHttpReq.send(null);
		
}
function deletes3(val)
{
	var flag = delete_part3(val);
	return false;
}

function delete_part3(id)
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
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/delete_particular3/?id='+id,true);
                               
				xmlHttpReq.send(null);
}

function getInvoices(val)
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/view/?branch_name='+''+branch_name,true);
                                
				xmlHttpReq.send(null);
}
function getInvoices1(val)
{
	var branch_name=document.getElementById('InitialInvoiceBranchName').value;
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/view_invoice/?branch_name='+''+branch_name,true);
                                
				xmlHttpReq.send(null);
}

function get_invoices_bybillno(val)
{
	var bill_no=document.getElementById('InitialInvoiceBillNo').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/view_invoice_bybillno/?bill_no='+bill_no,true);
                                
				xmlHttpReq.send(null);
}

function getAmount1(val)
{
	var rate=document.getElementById(val).value;
	
	var numberPattern=/\d+/g;
	val=val.match(numberPattern);
	
	var qty="Particular"+val+"Qty";
	qty=document.getElementById(qty).value;
	
	var amount="Particular"+val+"Amount";
	document.getElementById(amount).value=parseFloat(qty)*parseFloat(rate);
	getTotal1();
}

function getAmount2(val)
{
	rate=document.getElementById(val).value;

	var numberPattern=/\d+/g;
	val=val.match(numberPattern);
	
	qty="DeductParticular"+val+"Qty";
	qty=document.getElementById(qty).value;
	
	amount="DeductParticular"+val+"Amount";
	document.getElementById(amount).value=parseFloat(qty)*parseFloat(rate);
	//alert(qty);
	getTotal1();
}
function getAmount3(val)
{
	var rate=document.getElementById('AddInvParticularQty').value;
	//var amount=document.getElementById('AddInvDeductParticularAmount').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('AddInvParticularAmount').value = (val*rate).toFixed(0);
	getTotal1();
}
function getAmount4(val)
{
	var rate=document.getElementById('AddInvDeductParticularRate').value;
	//var amount=document.getElementById('AddInvDeductParticularAmount').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('AddInvDeductParticularAmount').value = (val*rate).toFixed(0);
	getTotal1();
}

function getTotal1()
{
	
	var idqty=document.getElementById('idx').value;
	

	var iddqty;
	try
	{
		iddqty=document.getElementById('idxd').value;
	}
	catch(err)
	{
		iddqty = 0;
	}
	var amt;
	try
	{
	 amt=parseInt(document.getElementById('AddInvParticularAmount').value);
	}
	catch(err)
	{
		amt = 0;
	}
	var ded;
	try
	{
	 ded=parseInt(document.getElementById('AddInvDeductParticularAmount').value);
	}
	catch(err)
	{
		ded = 0;
	}
	
	var str=idqty.split(",");
	var strd=iddqty.split(",");
	
	var i,total=0;
	
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		total+=parseInt(document.getElementById(amount).value);
	}

	for(i=0; i<strd.length-1; i++)
	{
		amount="DeductParticular"+strd[i]+"Amount";
		total-=parseInt(document.getElementById(amount).value);
	}
        
	total = total +amt - ded;
        
        var IGST=0,CGST=0,SGST=0;
        var GST = document.getElementById('InitialInvoiceApplyGst').value;
        if(GST==1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType=='Integrated')
           {
               try{ IGST = document.getElementById('InitialInvoiceIgst').value = ((total*18)/100).toFixed(0); }	
                catch(err)
                { var IGST = 0; }
           }
           else
           {
               try{ CGST = document.getElementById('InitialInvoiceCgst').value = ((total*9)/100).toFixed(0); }	
                catch(err)
                { var CGST = 0; }
                
                try{ SGST = document.getElementById('InitialInvoiceSgst').value = ((total*9)/100).toFixed(0); }	
                catch(err)
                { var SGST = 0; }
           }
           if(serviceTax=='1')
        {total = 0;}
           document.getElementById('InitialInvoiceGrnd').value = parseInt(total)+parseInt(IGST)+parseInt(CGST)+parseInt(SGST);
        }
        else {
	var tax,sbctax,krishi_tax;
	try{
		tax = document.getElementById('InitialInvoiceTax').value=(total*0.14).toFixed(0);
	   }
	catch(err)
	{
		tax =0;
	}
	try{
		sbctax = document.getElementById('InitialInvoiceSbctax').value=(total*0.005).toFixed(0);
	   }
	catch(err)
	{
		sbctax =0;
	}
	try{
		krishi_tax = document.getElementById('InitialInvoiceKrishiTax').value=(total*0.005).toFixed(0);
	   }
	catch(err)
	{
		krishi_tax =0;
	}
        var serviceTax="";
        try{
            serviceTax = document.getElementById('InitialInvoiceApplyServiceTax').value;
            if(serviceTax=='0')
            {      document.getElementById('InitialInvoiceTotal').value=total + amt - ded;}
            else
            {
                document.getElementById('InitialInvoiceTotal').value=0;
                total = 0;
            }   
        }
        catch(err)
        {}
	document.getElementById('InitialInvoiceGrnd').value=(parseInt(total)+parseInt(tax)+parseInt(sbctax)+parseInt(krishi_tax)).toFixed(0);
    }
}

function add_deduct_part(val)
{
	var initial_id = document.getElementById('InitialInvoiceId').value;
	var particular = document.getElementById('deductparticulars').value;
	var rate = document.getElementById('AddInvDeductParticularRate').value;
	var qty = document.getElementById('AddInvDeductParticularQty').value;
	var amount = document.getElementById('AddInvDeductParticularAmount').value;
	
	if(particular == '')
	{alert("Please Fill Particular");
		return false;
	}

	if(rate == '')
	{alert("Please Fill Rate");
		return false;
	}
	if(qty == '')
	{alert("Please Fill Quantity");
	return false;
	}

	if(amount == '')
	{alert("Please Click to Rate to Calculate");
	return false;
	}

	
	get_deduct_part(particular,rate,qty,amount,initial_id);
	return false;
}
function get_deduct_part(particular,rate,qty,amount,initial_id)
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
					 //document.getElementById("oo").readOnly= true;
				     //document.getElementById('oo').innerHTML = xmlHttpReq.responseText;
					 
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/add_deduct_part/?particular='+particular+'&rate='+rate+'&qty='+qty+'&amount='+amount+'&initial_id='+initial_id,true);
                               
				xmlHttpReq.send(null);
		
}

function check_po(val)
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/check_po/?branch_name='+branch_name,true);
                               
				xmlHttpReq.send(null);
}
function check_grn(val)
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/check_grn/?branch_name='+branch_name,true);
                                
				xmlHttpReq.send(null);
}
function download(val)
{
	var branch_name=document.getElementById('InitialInvoiceBranchName').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/download/?branch_name='+branch_name,true);
                               
				xmlHttpReq.send(null);
}
function download_bybillno(val)
{
	var bill_no=document.getElementById('InitialInvoiceBillNo').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/download_bybillno/?bill_no='+bill_no,true);
                                
				xmlHttpReq.send(null);
}

function download_client(val)
{
	var branch_name=document.getElementById('AddBranchName').value;
	var client_name=document.getElementById('client_name').value;
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/download_client/?branch='+branch_name+'&client='+client_name,true);
                               
				xmlHttpReq.send(null);
}


function download_grn(val)
{
	var branch_name=document.getElementById('InitialInvoiceBranchName').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/download_grn/?branch_name='+branch_name,true);
                               
				xmlHttpReq.send(null);
}

function download_grn_bill_no(val)
{
	var bill_no=document.getElementById('InitialInvoiceBillNo').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/download_grn_bill_no/?bill_no='+bill_no,true);
                                
				xmlHttpReq.send(null);
}

function approve_ahmd(val)
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/approve_ahmd/?branch_name='+branch_name,true);
				xmlHttpReq.send(null);
}

function approve_ahmd_bill_no(val)
{
	var bill_no = document.getElementById('AddBillNo').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/approve_ahmd_bill_no/?bill_no='+bill_no,true);
				xmlHttpReq.send(null);
}

function view_ahmd(val)
{
	var branch_name=document.getElementById('InitialInvoiceBranchName').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/view_ahmd/?branch_name='+branch_name,true);
				xmlHttpReq.send(null);
}
function view_ahmd_billno(val)
{
	var bill_no=document.getElementById('InitialInvoiceBillNo').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/view_ahmd_billno/?bill_no='+bill_no,true);
				xmlHttpReq.send(null);
}
function deletes(val)
{
	var initial_id=document.getElementById('InitialInvoiceId').value;
	var flag = delete_part(val,initial_id);
	return false;	
}
function deletes1(val)
{
	var initial_id=document.getElementById('InitialInvoiceId').value;
	//alert("welcome" + val + " initial " + initial_id);	
	var flag = delete_deduct_part(val,initial_id);
	return false;	
}

function delete_part(id,initial_id)
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
					 document.getElementById("mm").readOnly= true;
					 	
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/delete_particular/?id='+id+'&initial_id='+initial_id,true);
				xmlHttpReq.send(null);
		
}
function delete_deduct_part(id,initial_id)
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
					 //document.getElementById("mm").readOnly= true;
					 	
				     //document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/delete_deduct_particular/?id='+id+'&initial_id='+initial_id,true);
				xmlHttpReq.send(null);
		
}

function add_part(val)
{
	var initial_id = document.getElementById('InitialInvoiceId').value;
	var particular = document.getElementById('particulars').value;
	var rate = document.getElementById('AddInvParticularRate').value;
	var qty = document.getElementById('AddInvParticularQty').value;
	var amount = document.getElementById('AddInvParticularAmount').value;
	
	if(particular == '')
	{alert("Please Fill Particular");
		return false;
	}

	if(rate == '')
	{alert("Please Fill Rate");
		return false;
	}
	if(qty == '')
	{alert("Please Fill Quantity");
	return false;
	}

	if(amount == '')
	{alert("Please Click to Rate to Calculate");
	return false;
	}

	get_part(particular,rate,qty,amount,initial_id);
	return false;
}

function get_part(particular,rate,qty,amount,initial_id)
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
				{     alert(xmlHttpReq.responseText);
					 location.reload();
					 document.getElementById("oo").readOnly= true;
				     document.getElementById('oo').innerHTML = xmlHttpReq.responseText;
					 
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/AddInvParticulars/add_part/?particular='+particular+'&rate='+rate+'&qty='+qty+'&amount='+amount+'&initial_id='+initial_id,true);
				xmlHttpReq.send(null);
		
}

// Report Ajax Scriptiong Starts Here

function get_branch(val)
{
	company_name = document.getElementById('AddCompanyName').value;
	
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Reports/get_type/?company_name='+company_name+'&branch_name='+val,true);
				xmlHttpReq.send(null);
}

function report_validate()
{
var	report_type = document.getElementById('AddSelectReport').value;
var	company_name = document.getElementById('AddCompanyName').value;
var	type = document.getElementById('AddType').value;
var	bill_status = document.getElementById('AddStatus').value;
	
	if(report_type =='')
	{
		alert('Please Select Report Name');
		return false;
	}
	if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(type =='')
	{
		alert('Please Select Type');
		return false;
	}
	
	
	var client_name,branch_name;
	
	if(type == 'Client')
		{
			client_name = document.getElementById('Client').value;
			if(client_name == '')
			{
				alert('Please Select Client Name');
				return false;
			}
		}
	else
	{
		branch_name = document.getElementById('Branch').value;
		if(branch_name == '')
		{
			alert('Please Select Branch Name');
			return false;
		}		
	}
	
	
	if(bill_status =='')
	{
		alert('Please Select Invoice Status');
		return false;
	}
	if(type =='Client')
	{
		get_report(report_type,company_name,type,bill_status,client_name);
	}
	else
	{
		get_report(report_type,company_name,type,bill_status,branch_name);
	}
        var elements = document.getElementById('processing');
        elements.style.display = 'inline-block';
}
function get_report(report_type,company_name,type,bill_status,wise)
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
					// document.getElementById("nn").readOnly= true;
				     document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 
					
					 $('#table_id').dataTable();
					
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Reports/get_report/?report_type='+report_type+'&company_name='+company_name+'&type='+type+'&bill_status='+bill_status+'&wise='+wise,true);
				xmlHttpReq.send(null);
				
}

function report_validate2()
{
var	report_type = document.getElementById('AddSelectReport').value;
var	company_name = document.getElementById('AddCompanyName').value;
var	type = document.getElementById('AddType').value;
var	bill_status = document.getElementById('AddStatus').value;
	
	if(report_type =='')
	{
		alert('Please Select Report Name');
		return false;
	}
	if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(type =='')
	{
		alert('Please Select Type');
		return false;
	}
	
	
	var client_name,branch_name;
	
	if(type == 'Client')
		{
			client_name = document.getElementById('Client').value;
			if(client_name == '')
			{
				alert('Please Select Client Name');
				return false;
			}
		}
	else
	{
		branch_name = document.getElementById('Branch').value;
		if(branch_name == '')
		{
			alert('Please Select Branch Name');
			return false;
		}		
	}
	
	
	if(bill_status =='')
	{
		alert('Please Select Invoice Status');
		return false;
	}
	if(type =='Client')
	{
		get_report2(report_type,company_name,type,bill_status,client_name);
	}
	else
	{
		get_report2(report_type,company_name,type,bill_status,branch_name);
	}
        var elements = document.getElementById('processing');
        elements.style.display = 'inline-block';
}

function get_report2(report_type,company_name,type,bill_status,wise)
{
    var url='http://122.160.5.130/ispark/Reports/get_report2/?report_type='+report_type+'&company_name='+company_name+'&type='+type+'&bill_status='+bill_status+'&wise='+wise;
    window.location.href = url;
    //document.getElementById("processing").style.display = 'none';
}

function report_validate3()
{
var	company_name = document.getElementById('AddCompanyName').value;
var	status = document.getElementById('AddStatus').value;
var	to_date = document.getElementById('AddToDate').value;
var from_date = document.getElementById('AddFromDate').value;

	if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(status =='')
	{
		alert('Please Select Report Type');
		return false;
	}
			
	if(to_date =='')
	{
		alert('Please Select To Date');
		return false;
	}
	if(from_date =='')
	{
		alert('Please Select From Date');
		return false;
	}
	get_report3(company_name,status,to_date,from_date);
}
function get_report3(company_name,status,to_date,from_date)
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
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Reports/get_report3/?company_name='+company_name+'&status='+status+'&to_date='+to_date+'&from_date='+from_date,true);
				xmlHttpReq.send(null);	
}

function report_validate4()
{
var	company_name = document.getElementById('AddCompanyName').value;
var	status = document.getElementById('AddStatus').value;
var	to_date = document.getElementById('AddToDate').value;
var from_date = document.getElementById('AddFromDate').value;

	if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(status =='')
	{
		alert('Please Select Report Type');
		return false;
	}
			
	if(to_date =='')
	{
		alert('Please Select To Date');
		return false;
	}
	if(from_date =='')
	{
		alert('Please Select From Date');
		return false;
	}
	get_report4(company_name,status,to_date,from_date);
}

function get_report4(company_name,status,to_date,from_date)
{
	var url='http://122.160.5.130/ispark/Reports/get_report4/?company_name='+company_name+'&status='+status+'&to_date='+to_date+'&from_date='+from_date;
	window.location.href = url;
}



function report_validate12()
{
var	branch_Name = document.getElementById('AddBranch').value;

var	to_date = document.getElementById('AddToDate').value;
var from_date = document.getElementById('AddFromDate').value;

	if(branch_Name =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(to_date =='')
	{
		alert('Please Select To Date');
		return false;
	}
	if(from_date =='')
	{
		alert('Please Select From Date');
		return false;
	}
	get_report12(branch_Name,to_date,from_date);
}

function get_report12(branchName,to_date,from_date)
{
	var url='http://122.160.5.130/ispark/DashReports/get_reportdash/?BranchName='+branchName+'&to_date='+to_date+'&from_date='+from_date;
	window.location.href = url;
}
//Report Ajax Scripting Ends Here




function get_Receipt(val)
{
company_name = document.getElementById('ReceiptCompanyName').value;	
branch_name = document.getElementById('ReceiptBranchName').value;	
financial_year = document.getElementById('ReceiptFinancialYear').value;	

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
				{     if(xmlHttpReq.responseText=='')
						{
							document.getElementById("ReceiptSubmitedDates").readOnly=true;
							document.getElementById("ReceiptSubmitedTo").readOnly=true;
							document.getElementById("ReceiptExpDatesPayment").readOnly=true;
							document.getElementById("ReceiptRemarks").readOnly=true;
							document.getElementById("ReceiptInvoice").value='';
							document.getElementById('receiptdata').innerHTML = "<span style=\"color:#F00;font-size:10px\">Receipt Allready uploaded</span>";
						}
						else { 
							document.getElementById("ReceiptSubmitedDates").readOnly=false;
							document.getElementById("ReceiptSubmitedTo").readOnly=false;
							document.getElementById("ReceiptExpDatesPayment").readOnly=false;
							document.getElementById("ReceiptRemarks").readOnly=false;
							document.getElementById("receiptdata").readOnly= true;
							document.getElementById('receiptdata').innerHTML = xmlHttpReq.responseText;
						}
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Receipts/get_receipt/?company_name='+company_name+'&branch_name='+branch_name+'&financial_year='+financial_year+'&invoice='+val,true);
				//alert('http://122.160.5.130/ispark/Receipts/get_receipt/?company_name='+company_name+'&branch_name='+branch_name+'&financial_year='+financial_year+'&invoice='+val);
				xmlHttpReq.send(null);
}






function get_branch2(val)
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
				{    // alert(xmlHttpReq.responseText);
					 document.getElementById("mm").readOnly= true;
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
					  
				}
				} 
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Reports/get_type/?company_name='+val+'&branch_name='+'Branch',true);
				xmlHttpReq.send(null);
}


function report_validate5() //PTP REPORT
{
var	AddSelectReport = document.getElementById('AddSelectReport').value;
var	AddCompanyName = document.getElementById('AddCompanyName').value;
var	AddBranchName = document.getElementById('AddBranchName').value;
var AddToDate = document.getElementById('AddToDate').value;
var AddFromDate = document.getElementById('AddFromDate').value;

    if(AddSelectReport =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	if(AddCompanyName =='')
	{
		alert('Please Select Report Type');
		return false;
	}
			
	if(AddBranchName =='')
	{
		alert('Please Select To Branch Name');
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
		
    get_report6(AddSelectReport,AddCompanyName,AddBranchName,AddToDate,AddFromDate);
}
function get_report6(AddSelectReport,AddCompanyName,AddBranchName,AddToDate,AddFromDate)
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
				}
				} 
				
				xmlHttpReq.open('POST','http://122.160.5.130/ispark/Reports/get_report6/?AddSelectReport='+AddSelectReport+'&AddCompanyName='+AddCompanyName+'&AddBranchName='+AddBranchName+'&AddToDate='+AddToDate+'&AddFromDate='+AddFromDate,true);
				xmlHttpReq.send(null);
}
function  report_set() //PTP REPORT
{
	var AddSelectReport = document.getElementById('AddSelectReport').value;
	var AddCompanyName = document.getElementById('AddCompanyName').value;
	var AddBranchName = document.getElementById('AddBranchName').value;
	var AddToDate = document.getElementById('AddToDate').value;
    var AddFromDate = document.getElementById('AddFromDate').value;

	if(AddSelectReport ==''){
		
		alert('Please Select Reoprt Type');
		return false;
	}
	if(AddCompanyName ==''){
		
		alert('Please Select Company');
		return false;
	}
if(AddBranchName ==''){
		
		alert('Please Select Branch Name');
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
      get_report6a(AddSelectReport,AddCompanyName,AddBranchName,AddToDate,AddFromDate);
}
 function get_report6a(AddSelectReport,AddCompanyName,AddBranchName,AddToDate,AddFromDate)
{
    var url='http://122.160.5.130/ispark/Reports/get_report6a/?AddSelectReport='+AddSelectReport+'&AddCompanyName='+AddCompanyName+'&AddBranchName='+AddBranchName+'&AddToDate='+AddToDate+'&AddFromDate='+AddFromDate;
	window.location.href = url;
}

function apply_service_tax()
{
    var id = document.getElementById("InitialInvoiceId").value;    
    var apply="";
    if(document.getElementById("InitialInvoiceApplyServiceTax").checked)
    {
        apply = "Yes";
    }
    else
    {
        apply = "No";
    }    
    url_fetch("http://122.160.5.130/ispark/InitialInvoices/apply_service_tax?id="+id+"&apply="+apply);
}

function apply_tax_cal()
{
    var id = document.getElementById("InitialInvoiceId").value;    
    var apply = "";
    if(document.getElementById("InitialInvoiceAppTaxCal").checked)
    {
        apply = "Yes";
    }
    else
    {
        apply = "No";
    } 
    url_fetch("http://122.160.5.130/ispark/InitialInvoices/apply_tax_cal?id="+id+"&apply="+apply);
}
function apply_krishi_tax()
{
    var id = document.getElementById("InitialInvoiceId").value;    
    var apply = "Yes";
    if(document.getElementById("InitialInvoiceApplyKrishiTax").checked)
    {
        apply = "Yes";
    }
    else
    {
        apply = "No";
    } 
    url_fetch("http://122.160.5.130/ispark/InitialInvoices/apply_krishi_tax?id="+id+"&apply="+apply);
}

function url_fetch(url)
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
             //document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
            //alert(xmlHttpReq.responseText);
            location.reload();
        }
    } 
    
    xmlHttpReq.open('POST',url,true);
    xmlHttpReq.send(null);
}
 function get_branchnew(val)
                         {
                             //alert('xcxcsdsd');
                             if(val=='CostCenter')
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
					// document.getElementById("nn").readOnly= true;
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText; 
					
					 
					
				}
                                
				} 
				var url='http://122.160.5.130/ispark/Dashs/view4/?';
                                xmlHttpReq.open('post',url,true);
				xmlHttpReq.send(null);
                            }
                             else
                            {
                               document.getElementById('mm').innerHTML = ""; 
                            }
                           
    
}

function get_Show11()
{
    //var	select = document.getElementById('ReportType').value;
    var select='Branch';
    var branchnew = document.getElementById('branch_name').value;
    var FinanceYear=document.getElementById('DashsFinanceYear').value;
    var FinanceMonth=document.getElementById('DashsFinanceMonth').value;

    if(branchnew=='')
    {
       alert('Please Select Branch');
        return false; 
    }
    //var cost_id = document.getElementById('cost_center').value;
    if(select == 'CostCenter' && cost_id=='')
    {
        alert('Please Select Cost Center');
        return false; 
    }
    
    if(select =='')
    {
        alert('Please Select Report Type');
        return false;
    }
    if(select =='')
    {
        alert('Please Select Report Type');
        return false;
    }


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
            // document.getElementById("nn").readOnly= true;
         document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 



    }
    } 


    var url='get_dash_data/?type=view&ReportType='+select+'&barnch='+branchnew+'&FinanceYear='+FinanceYear+'&FinanceMonth='+FinanceMonth;  

    xmlHttpReq.open('post',url,true);
    xmlHttpReq.send(null);
					
}

function report_validate11()
{
                //var	select = document.getElementById('ReportType').value;
                var select='Branch';
    var branchnew = document.getElementById('branch_name').value;
    var FinanceYear=document.getElementById('DashsFinanceYear').value;
    var FinanceMonth=document.getElementById('DashsFinanceMonth').value;

    if(branchnew=='')
    {
       alert('Please Select Branch');
        return false; 
    }
    //var cost_id = document.getElementById('cost_center').value;
    if(select == 'CostCenter' && cost_id=='')
    {
        alert('Please Select Cost Center');
        return false; 
    }
    if(select =='')
    {
        alert('Please Select Report Type');
        return false;
    }
    if(select =='')
    {
        alert('Please Select Report Type');
        return false;
    }
                            
      var url='http://122.160.5.130/ispark/Dashs/get_dash_data/?type=export&ReportType='+select+'&barnch='+branchnew+'&FinanceYear='+FinanceYear+'&FinanceMonth='+FinanceMonth;                       
	window.location.href = url;
}


function getRevenueValidation(value)
{
	if(value=='Fixed')
	{
                document.getElementById("SeatCount").innerHTML='';
                document.getElementById("FosCount").innerHTML='';
                document.getElementById("Seat").innerHTML='';
                document.getElementById("Fos").innerHTML='';
                document.getElementById("CostCenterMasterFixed").value = '';
		document.getElementById("CostCenterMasterFixed").disabled=false;
		document.getElementById("CostCenterMasterVariableBase").disabled=true;
	}
	else if(value=='Variable')
	{
                document.getElementById("Seat").innerHTML='';
                document.getElementById("Fos").innerHTML='';
                document.getElementById("CostCenterMasterVariableBase").value='';
		document.getElementById("CostCenterMasterVariableBase").disabled=false;
		document.getElementById("CostCenterMasterFixed").disabled=true;
	}
	else if(value=='Both')
	{
                
		document.getElementById("CostCenterMasterFixed").disabled=false;
		document.getElementById("CostCenterMasterVariableBase").disabled=false;
	}
	else
	{
                document.getElementById("SeatCount").innerHTML='';
                document.getElementById("FosCount").innerHTML='';
                document.getElementById("Seat").innerHTML='';
                document.getElementById("Fos").innerHTML='';
                document.getElementById("variableCount").innerHTML='';
                document.getElementById("variable").innerHTML='';
                document.getElementById("CostCenterMasterVariableBase").value='';
                document.getElementById("CostCenterMasterFixed").value = '';
		document.getElementById("CostCenterMasterFixed").disabled=true;
		document.getElementById("CostCenterMasterVariableBase").disabled=true;	
	}
}

function getFixed(value)
{ 
    var table1 = '<table class="table table-striped">';
    //table1 +='    <tr>';
    //table1 +='        <td align="center">Particulars</td>';
    //table1 +='        <td align="center">Qty.</td>';
    //table1 +='        <td align="center">Rate</td>';
    //table1 +='        <td align="center">Total</td>';
    //table1 +='        <td align="center">Action</td>';
    //table1 +='    </tr>';
    //table1 +='    <tr>';
    
    var seat='    <tr>';
    seat +='        <td align="center">Seat Particulars</td>';
    seat +='        <td align="center">Qty.</td>';
    seat +='        <td align="center">Rate</td>';
    seat +='        <td align="center">Total</td>';
    seat +='        <td align="center">Action</td>';
    seat +='    </tr>';
    seat +='    <tr>';
    seat +='        <td><input type="text" name="seatpart" id="seatpart" value="" placeholder="Particulars"></td>';
    seat +='        <td><input type="text" name="seatQty" id="seatQty" value="" placeholder="Quantity"></td>';
    seat +='        <td><input type="text" name="seatAmt" id="seatAmt" value="" placeholder="Total" onBlur="getSeatTotal(this.value)"></td>';
    seat +='        <td><div id="seatTotal"></div></td>';
    seat +='        <td><button onClick="return getAddSeat()">Add</button></td>';
    
    var fos ='    <tr>';
    fos +='        <td align="center">Fos Particulars</td>';
    fos +='        <td align="center">Qty.</td>';
    fos +='        <td align="center">Rate</td>';
    fos +='        <td align="center">Total</td>';
    fos +='        <td align="center">Action</td>';
    fos +='    </tr>';
    fos +='    <tr>';
    fos +='        <td><input type="text" id="fospart" name="fospart" value="" placeholder="Particulars"></td>';
    fos +='        <td><input type="text" id="fosQty" name="fosQty" value="" placeholder="Quantity"></td>';
    fos +='        <td><input type="text" id="fosAmt" name="fosAmt" value="" placeholder="Total" onBlur="getFosTotal(this.value)"></td>';
    fos +='        <td><div id="fosTotal"></div></td>';
    fos +='        <td><button onClick="return getAddFos()">Add</button></td>';
    
    var variable ='    <tr>';
    variable +='        <td align="center">Variable Particulars</td>';
    variable +='        <td align="center">Qty.</td>';
    variable +='        <td align="center">Rate</td>';
    variable +='        <td align="center">Total</td>';
    variable +='        <td align="center">Action</td>';
    variable +='    </tr>';
    variable +='    <tr>';
    variable +='        <td><input type="text" id="variablepart" name="variablepart" value="" placeholder="Particulars"></td>';
    variable +='        <td><input type="text" id="variableQty" name="variableQty" value="" placeholder="Quantity"></td>';
    variable +='        <td><input type="text" id="variableAmt" name="variableAmt" value="" placeholder="Total" onBlur="getVariableTotal(this.value)"></td>';
    variable +='        <td><div id="variableTotal"></div></td>';
    variable +='        <td><button onClick="return getAddVariable()">Add</button></td>';
    
    
    var table2 ='    </tr>';
    table2 +='    <tr>';
    table2 +='        <td colspan="2">Grand Total</td>';
    table2 +='        <td colspan="2"><div id="variableTotalAmt"></div></td>';
    table2 +='    </tr>';
    table2 +='</table>';
        
    if(value=='Seat')
    {
        document.getElementById("SeatCount").innerHTML='<input type="hidden" name="seat" id="seat" value="1">';
        document.getElementById("Seat").innerHTML=table1+seat+table2;
        document.getElementById("FosCount").innerHTML='';
        document.getElementById("Fos").innerHTML='';
    }
    else if(value=='Fos')
    {
        document.getElementById("FosCount").innerHTML='<input type="hidden" name="fos" id="fos" value="1">';
        document.getElementById("Fos").innerHTML=table1+fos+table2;
        document.getElementById("SeatCount").innerHTML='';
        document.getElementById("Seat").innerHTML='';
    }
    else if(value=='Seat&Fos')
    {
        document.getElementById("SeatCount").innerHTML='<input type="hidden" name="seat" id="seat" value="1">';
        document.getElementById("Seat").innerHTML=table1+seat+table2;
        document.getElementById("FosCount").innerHTML='<input type="hidden" name="fos" id="fos" value="1">';
        document.getElementById("Fos").innerHTML=table1+fos+table2;
    }
    else if(value=='Hourly' || value=='Minute' || value=='Case' || value=='Contact')
    {
        document.getElementById("variableCount").innerHTML='<input type="hidden" name="variable" id="variable" value="1">';
        document.getElementById("variable").innerHTML=table1+variable+table2;   
    }
    else
    {
        document.getElementById("SeatCount").innerHTML='';
        document.getElementById("FosCount").innerHTML='';
        document.getElementById("Seat").innerHTML='';
        document.getElementById("Fos").innerHTML='';
        document.getElementById("variableCount").innerHTML='';
        document.getElementById("variable").innerHTML='';
    }
}

function getSeatTotal(value)
{
    var qty = document.getElementById('seatQty').value;
    document.getElementById('seatTotal').innerHTML = value*qty;
}

function getFosTotal(value)
{
    var qty = document.getElementById('fosQty').value;
    document.getElementById('fosTotal').innerHTML = value*qty;
}

function getVariableTotal(value)
{
    var qty = document.getElementById('variableQty').value;
    document.getElementById('variableTotal').innerHTML = value*qty;
}

function getAddSeat()
{
    var remarks = document.getElementById('seatpart').value;
    var qty = document.getElementById('seatQty').value;
    var rate = document.getElementById('seatAmt').value;
    
    document.getElementById('seatpart').value='';
    document.getElementById('seatQty').value='';
    document.getElementById('seatAmt').value='';
    document.getElementById('seatTotal').innerHTML='';
    
    if(remarks=='')
    {
       alert("Seat Particular is empty");
       return ;
    }
    else if(qty=='')
    {
        alert("Seat Quantity is empty"); 
        return ;
    }
    else if(rate=='')
    {
        alert("Seat Amount is empty"); 
        return ;
    }
    
    $.post("costCenterMasters/add_particulars",{revenueType:"Seat",remarks:remarks,qty:qty,rate:rate},function(data)
      {$('#SeatDetails').html(data);});
    return false;
}

function getAddFos()
{
    var remarks = document.getElementById('fospart').value;
    var qty = document.getElementById('fosQty').value;
    var rate = document.getElementById('fosAmt').value;
    
    document.getElementById('fospart').value='';
    document.getElementById('fosQty').value='';
    document.getElementById('fosAmt').value='';
    document.getElementById('fosTotal').innerHTML='';
    
    if(remarks=='')
    {
       alert("Fos Particular is empty");
       return ;
    }
    else if(qty=='')
    {
        alert("Fos Quantity is empty"); 
        return ;
    }
    else if(rate=='')
    {
        alert("Fos Amount is empty"); 
        return ;
    }
    
    $.post("costCenterMasters/add_particulars",{revenueType:"Fos",remarks:remarks,qty:qty,rate:rate},function(data)
      {$('#FosDetails').html(data);});
    return false;
}

function getAddVariable()
{
    var remarks = document.getElementById('variablepart').value;
    var qty = document.getElementById('variableQty').value;
    var rate = document.getElementById('variableAmt').value;
    
    document.getElementById('variablepart').value='';
    document.getElementById('variableQty').value='';
    document.getElementById('fosAmt').value='';
    document.getElementById('variableAmt').innerHTML='';
    
    if(remarks=='')
    {
       alert("Variable Particular is empty");
       return ;
    }
    else if(qty=='')
    {
        alert("Variable Quantity is empty"); 
        return ;
    }
    else if(rate=='')
    {
        alert("Variable Amount is empty"); 
        return ;
    }
    
    $.post("costCenterMasters/add_particulars",{revenueType:"Variable",remarks:remarks,qty:qty,rate:rate},function(data)
      {$('#variableDetails').html(data);});
    return false;
}


function delTmpCost(id,type)
{
    var div = '#'+type+'Details';
   $.post("costCenterMasters/delete_particulars",{id:id,revenueType:type},function(data)
      {$(div).html(data);}); 
}

function report_validate50()
{
    var Branch = document.getElementById('VailidationReportsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationReportsCostCenter').value;//alert(CostCent);	

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationReports/get_reportdash/?'+url;
    
    xmlHttpReq.send(null);
}



function report_validate52(Branch)
{
    
   
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdash_ex/?'+url;
    
    xmlHttpReq.send(null);
}

function report_validate53(Branch)
{
    
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashpoa/?'+url;
    
    xmlHttpReq.send(null);
}



function report_validate54(Branch)
{
    //var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashpoe/?'+url;
    
    xmlHttpReq.send(null);
}
function report_validate55(Branch)
{
   // var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashcoc/?'+url;
    
    xmlHttpReq.send(null);
}
function report_validate56(Branch)
{
    //var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashcf/?'+url;
    
    xmlHttpReq.send(null);
}
function report_validate57(Branch)
{
   // var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashepf/?'+url;
    
    xmlHttpReq.send(null);
}
function report_validate58(Branch)
{
   // var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashresume/?'+url;
    
    xmlHttpReq.send(null);
}
function report_validate59(Branch)
{
    //var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);

    var url = 'branch='+Branch+'&costcenter='+CostCent;
   // alert(url);
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
    
     window.location.href ='http://122.160.5.130/ispark/VailidationRejects/get_reportdashepfstatus/?'+url;
    
    xmlHttpReq.send(null);
}
function get_Show22()
{
  
                    var Branch = document.getElementById('VailidationRejectsBranch').value; //alert(Branch);
var CostCent = document.getElementById('VailidationRejectsCostCenter').value;//alert(CostCent);	

    var url = 'branch='+Branch+'&costcenter='+CostCent;
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
					// document.getElementById("nn").readOnly= true;
				     document.getElementById('nn').innerHTML = xmlHttpReq.responseText; 
					
					 
					
				}
				} 
                               var url ='http://122.160.5.130/ispark/VailidationRejects/get_reportdash/?'+url;
     
                            xmlHttpReq.open('post',url,true);
				xmlHttpReq.send(null);
				
 
    
    
	
	
}

function ajax51(url,uqry,div)
{
   var xmlhttp = false;
	
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{  // alert(xmlhttp.responseText);
		    var str = xmlhttp.responseText;
                    document.getElementById(div).innerHTML = xmlhttp.responseText; 
		}
	}
	
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(uqry);
}



function imrest_validate12(val)
{
var	branch_Name = document.getElementById('ExpenseBranchName').value;

var	year = document.getElementById('ExpenseFinanceYear').value;
var month = document.getElementById('ExpenseFinanceMonth').value;
var	head = document.getElementById('head').value;

var	subhead = document.getElementById('subhead').value;
var ExpenseExpenseEntryType = document.getElementById('ExpenseExpenseEntryType').value;
var ExpenseGrnNo = document.getElementById('ExpenseGrnNo').value;

	if(branch_Name =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(year =='')
	{
		alert('Please Select Year');
		return false;
	}
	if(month =='')
	{
		alert('Please Select From Month');
		
	}
        
        
        if(head =='')
	{
		alert('Please Select Expense Head');
		return false;
	}
	
			
	
	if(ExpenseExpenseEntryType =='')
	{
		alert('Please Select Expense Mode');
		
	}
        
       if(val=='Export') 
       {        
	return imrestreport12(branch_Name,year,month,head,subhead,ExpenseExpenseEntryType,ExpenseGrnNo,val);
        }
    else
    {
        var url='http://122.160.5.130/ispark/ExpenseReports/Export_imprest_report';
        var uqry='BranchName='+branch_Name+'&year='+year+'&month='+month+'&head='+head+'&subhead='+subhead+'&ExpenseExpenseEntryType='+ExpenseExpenseEntryType+'&ExpenseGrnNo='+ExpenseGrnNo+'&type='+val;
        ajax51(url,uqry,'data');
        return false;
    }
}

function imrestreport12(branchName,year,month,head,subhead,ExpenseExpenseEntryType,ExpenseGrnNo,val)
{
	var url='http://122.160.5.130/ispark/ExpenseReports/Export_imprest_report/?BranchName='+branchName+'&year='+year+'&month='+month+'&head='+head+'&subhead='+subhead+'&ExpenseExpenseEntryType='+ExpenseExpenseEntryType+'&ExpenseGrnNo='+ExpenseGrnNo+'&type='+val;
	window.location.href = url;
        return false;
}

function imrest_detail_validate(val)
{
var	branchId = document.getElementById('BranchId').value;

var	ImprestManagerId = document.getElementById('ImprestManagerId').value;
var ExpenseDateFrom = document.getElementById('ExpenseDateFrom').value;
var	DateTo = document.getElementById('DateTo').value;



	if(branchId =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(ImprestManagerId =='')
	{
		alert('Please Select Imprest Manager');
		return false;
	}
	if(ExpenseDateFrom =='')
	{
		alert('Please Select From Date');
		
	}
        
        
        if(DateTo =='')
	{
		alert('Please Select To Date');
		return false;
	}
        
        if(val=='Export')
        {
            return imrest_details(branchId,ImprestManagerId,ExpenseDateFrom,DateTo,val);
        }
        else
        {
            var url='http://122.160.5.130/ispark/ExpenseReports/export_imprest_detail?';
            var uqry='BranchId='+branchId+'&ImprestManagerId='+ImprestManagerId+'&DateFrom='+ExpenseDateFrom+'&DateTo='+DateTo+'&type='+val;
            ajax51(url,uqry,'data');
            return false;
        }
}

function imrest_details(branchId,ImprestManagerId,ExpenseDateFrom,DateTo,val)
{
	var url='http://122.160.5.130/ispark/ExpenseReports/export_imprest_detail?BranchId='+branchId+'&ImprestManagerId='+ImprestManagerId+'&DateFrom='+ExpenseDateFrom+'&DateTo='+DateTo+'&type='+val;
	window.location.href = url;
        return false;
}


function export_pnl_validate(val)
{
var	branch_name = document.getElementById('ExpenseBranchName').value;

var	FinanceYear = document.getElementById('ExpenseFinanceYear').value;
var FinanceMonth = document.getElementById('ExpenseFinanceMonth').value;




	if(branch_name =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(FinanceYear =='')
	{
		alert('Please Select Finance Year');
		return false;
	}
	
        if(val=='Export')        
	{return export_pnl(branch_name,FinanceYear,FinanceMonth,val);}
    else
    {
        var url='http://122.160.5.130/ispark/ExpenseReports/export_pnl_report';
        var uqry='BranchId='+branch_name+'&FinanceYear='+FinanceYear+'&FinanceMonth='+FinanceMonth+'&type='+val;
        ajax51(url,uqry,'data');
        return false;
    }
}

function export_pnl(branch_name,FinanceYear,FinanceMonth,val)
{
	var url='http://122.160.5.130/ispark/ExpenseReports/export_pnl_report?BranchId='+branch_name+'&FinanceYear='+FinanceYear+'&FinanceMonth='+FinanceMonth+'&type='+val;
	window.location.href = url;
        return false;
}


function imprest_report2_validate(val)
{
var	branchId = document.getElementById('BranchId').value;

var	ImprestManagerId = document.getElementById('ImprestManagerId').value;
var ExpenseDateFrom = document.getElementById('ExpenseDateFrom').value;
var	DateTo = document.getElementById('DateTo').value;



	if(branchId =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	
			
	if(ImprestManagerId =='')
	{
		alert('Please Select Imprest Manager');
		return false;
	}
	if(ExpenseDateFrom =='')
	{
		alert('Please Select From Date');
		
	}
        
        
        if(DateTo =='')
	{
		alert('Please Select To Date');
		return false;
	}
        
        if(val=='Export')
        {
            return imprest_report2(branchId,ImprestManagerId,ExpenseDateFrom,DateTo,val);
        }
        else
        {
            var url='http://122.160.5.130/ispark/ExpenseReports/export_imprest_report2?';
            var uqry='BranchId='+branchId+'&ImprestManagerId='+ImprestManagerId+'&DateFrom='+ExpenseDateFrom+'&DateTo='+DateTo+'&type='+val;
            ajax51(url,uqry,'data');
            return false;
        }
}

function imprest_report2(branchId,ImprestManagerId,ExpenseDateFrom,DateTo,val)
{
	var url='http://122.160.5.130/ispark/ExpenseReports/export_imprest_report2?BranchId='+branchId+'&ImprestManagerId='+ImprestManagerId+'&DateFrom='+ExpenseDateFrom+'&DateTo='+DateTo+'&type='+val;
	window.location.href = url;
        return false;
}
 function incentivstatustypeq(val)
        {
           if(val=='Mannual'){
               
              var url='http://122.160.5.130/ispark/Attendances/incentive'; 
              
           }
          else if(val=='Bulk')
          {
            var url='http://122.160.5.130/ispark/Attendances/importformat';   
          }
          else
          {
              
          }
         window.location.href = url;
        return false; 
        }
function revenue_pnal_validate(val)
{
var	comp_Name = document.getElementById('ExpenseCompanyName').value;

var	year = document.getElementById('ExpenseFinanceYear').value;
var month = document.getElementById('ExpenseFinanceMonth').value;


	if(comp_Name =='')
	{
		alert('Please Select Company Name');
		return false;
	}
	
			
	if(year =='')
	{
		alert('Please Select Year');
		return false;
	}
	if(month =='')
	{
		alert('Please Select Month');
		
	}
        
        
        
        
       if(val=='Export') 
       {        
	return export_revenue_pnl_report(comp_Name,year,month,val);
       }
    
}

function export_revenue_pnl_report(comp_Name,year,month,val)
{
    var url='http://122.160.5.130/ispark/GrnReports/export_pnl_revenue_report?comp_Name='+comp_Name+'&year='+year+'&month='+month+'&type='+val;
    window.location.href = url;
    return false;
}
function grn_gst_report(val)
{
var company_name = document.getElementById('company_name').value;
var FinanceYear = document.getElementById('FinanceYear').value;
var FinanceMonth = document.getElementById('FinanceMonth').value;
var Type = document.getElementById('Type').value;



	if(company_name =='')
	{
		alert('Please Select Company Name');
		return false;
	}		
	else if(FinanceYear =='')
	{
		alert('Please Select Finance Year');
		return false;
	}
        else if(FinanceMonth =='')
	{
		alert('Please Select Finance Month');
		return false;
	}
        else if(Type =='')
	{
		alert('Please Select Type');
		return false;
	}
	
        if(val=='Export')        
	{return export_grn_gst_report(company_name,FinanceYear,FinanceMonth,Type,val);}
    
}

function export_grn_gst_report(company_name,FinanceYear,FinanceMonth,Type,val)
{
	var url='http://122.160.5.130/ispark/GrnReports/export_grn_gst_report?company_name='+company_name+'&FinanceYear='+FinanceYear+'&FinanceMonth='+FinanceMonth+'&TaxType='+Type+'&type='+val;
	window.location.href = url;
        return false;
}
// JavaScript Document