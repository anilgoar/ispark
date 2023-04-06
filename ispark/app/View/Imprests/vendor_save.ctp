<?php
?>
<style>
    table td{margin: 5px;}
</style>



<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span><b>Add New Vendor</b></span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 style="color:green"><?php echo $this->Session->flash(); ?> </h4>
		<div class="form-horizontal">
                 <?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>   
                    
                    <div class="form-group">
                            <label class="col-sm-2 control-label">Vendor Name</label>
                        <div class="col-sm-10">            
                        <?php echo $this->Form->input('Vendor',array('label' => false,'class'=>'form-control','id'=>'Vendor','placeholder'=>'Vendor Name')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-2 control-label">Expense Head</label>
                        <div class="col-sm-10">
                        <?php	
                            echo $this->Form->input('head', array('label'=>false,'class'=>'form-control','options' => $head,'empty' => 'Head','id'=>'head','onChange'=>"getSubHeading()",'required'=>true));
                        ?>
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-2 control-label">Expense Sub Head</label>
                        <div class="col-sm-10">
                            <?php	
                                echo $this->Form->input('subhead', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Sub Head','id'=>'subhead'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                       
                         
                    </div>
                         <div class="form-group">
                        
                        
                         </div> 
                    
                    
                    
                    
                     <div class="form-group">
                    <label class="col-sm-2 control-label">Address1</label>
                        <div class="col-sm-10">
                                <?php	
                                    echo $this->Form->textarea('Address1', array('label'=>false,'class'=>'form-control','id'=>'Address1','placeholder'=>'Address1','rows'=>'2'));
                                ?>
                        </div>
                     </div>
                    <div class="form-group">
                    <label class="col-sm-2 control-label">Address2</label>
                        <div class="col-sm-10">
                                <?php	
                                    echo $this->Form->textarea('Address2', array('label'=>false,'class'=>'form-control','id'=>'Address2','placeholder'=>'Address2','rows'=>'2'));
                                ?>
                        </div>
                     </div>
                    <div class="form-group">
                    <label class="col-sm-2 control-label">Address3</label>
                        <div class="col-sm-10">
                                <?php	
                                    echo $this->Form->textarea('Address3', array('label'=>false,'class'=>'form-control','id'=>'Address3','placeholder'=>'Address3','rows'=>'2'));
                                ?>
                        </div>
                     </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">GST Enable</label>
                        <div class="col-sm-4">
                            <?php	
                                echo $this->Form->input('GSTEnable', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'GSTEnable',"options"=>array("1"=>"Y","0"=>"N"),'empty'=>'Select','onchange'=>"getDisableGST(this.value)"));
                            ?>
                        </div>
                        <label class="col-sm-3 control-label">Company</label>
                        <div class="col-sm-3">
                            <?php foreach($company_master as $k=>$v) { ?>
                            <input type="checkbox" name="comp[]" value="<?php echo $k; ?>" id="comp1"  /> <?php echo $v; ?> &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php   }    ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Vendor State</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('State', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'State','empty'=>"State",'options'=>$state_list,'onchange'=>'getStateGSTCode()'));
                                ?>
                        </div>
                        <label class="col-sm-3 control-label">Vendor State GST Code</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('state_code', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'state_code','placeholder'=>"GST State Code",'readonly'=>true));
                                ?>
                        </div>
                     </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Vendor GST No.</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('VendorGST', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'VendorGST','placeholder'=>"GST No "));
                                ?>
                        </div>
                        
                    
                     </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Pan Card No</label>
                        <div class="col-sm-4">
                            <?php	
                                echo $this->Form->input('pancard', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'pancard','placeholder'=>'Pan Card No','onkeypress'=>'return validate_pancard(this.value,evt)'));
                            ?>
                        </div>
                        <label class="col-sm-3 control-label">Pin Code</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('pincode', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'Pincode','placeholder'=>"Pincode",'onkeypress'=>'return validate_pincode(event,this.value)'));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                       <label class="col-sm-2 control-label">Select File</label> 
                        <div class="col-sm-10">
                            <?php	
                                echo $this->Form->input('paymentFile', array('label'=>false,'type'=>'file','class'=>'form-control','required'=>true));
                            ?>
                        </div>  
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ship To</label>
                        <div class="col-sm-4">
                            <input type="checkbox" id="ship_to" name="data[Imprests][ship_to]" value="1" <?php if($vendor_arr['VendorMaster']['ship_to']=='1') echo "checked"; ?> /> Same As Bill To
                        </div>
                        <label class="col-sm-3 control-label">TDS Enabled</label>
                        <div class="col-sm-3">
                            <?php	
                                echo $this->Form->input('TDSEnabled', array('label'=>false,'class'=>'form-control','required'=>true,'id'=>'TDSEnabled','options'=>array('0'=>'No','1'=>'Yes'),'value'=>$vendor_arr['VendorMaster']['TDSEnabled'],'onchange'=>"getTDSDisplay(this.value)"));
                            ?>
                        </div>
                    </div>
                    <div id="TdsDisplay" <?php if($vendor_arr['VendorMaster']['TDSEnabled']=='1') { } else { echo 'style="display:none"';} ?> >
                        <div class="form-group">
                            <label class="col-sm-2 control-label">TDS Section</label>
                            <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('TDSSection', array('label'=>false,'class'=>'form-control','id'=>'TDSSection','empty'=>'Select','options'=>$TdsMaster,'onchange'=>'get_tds(this.value)'));
                                ?>
                            </div>
                            
                            <label class="col-sm-2 control-label">TDS RATE %</label>
                            <div class="col-sm-4">
                                    <?php
                                        echo $this->Form->input('TDS', array('label'=>false,'class'=>'form-control','id'=>'TDS','placeholder'=>"TDS%    e.g. 0.00",'value'=>'','readonly'=>true));
                                    ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Do You Want To Change (Y/N)</label>
                            <div class="col-sm-4">
                            <?php	
                                echo $this->Form->input('TDSChange', array('label'=>false,'class'=>'form-control','id'=>'TDSChange','options'=>array('No'=>'No','Yes'=>'YES'),'onchange'=>'getIncomeEnable(this.value)'));
                            ?>
                            <?php
                                    echo $this->Form->input('TDSNew', array('label'=>false,'class'=>'form-control','id'=>'TDSNew','placeholder'=>"TDS%    e.g. 0.00",'value'=>'','onkeypress'=>"return checkNumber(this.value,event)",'disabled'=>true));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-8 control-label">Please Make Sure That You Have Relevant Income Tax Exemption Certificate</label>
                            <div class="col-sm-2">
                            <?php	
                                echo $this->Form->input('IncomeCertificateCheck', array('label'=>false,'class'=>'form-control','id'=>'IncomeCertCheck','empty'=>"Select",'options'=>array('Yes'=>'Yes','No'=>'No'),'disabled'=>true,'onchange'=>'IncomeCertCheckEnable(this.value)'));
                            ?>
                            </div>
                        </div>
                    
                        
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <h4 class="page-header textClass"></h4>
                        </div>  
                    </div>
                    <div class="form-group">
                       
                       <?php foreach($branch_master as $k=>$v) {?>
                        <label class="col-sm-2 control-label"></label> 
                        <div class="col-sm-10">
                           <input type="checkbox" name="branch[]" value='<?php echo $k ?>' id='Branch<?php echo $k;?>' onclick="getEnableComp('<?php echo $k;?>')" /> <?php echo $v; ?>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <h4 class="page-header textClass" ></h4>
                        </div>  
                    </div>
                    
                    <div id="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <input type="submit" name="Submit" value="Save" onclick="return validate_vendor()" class="btn btn-primary pull-right" />
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?> 
                    </div>
                    </div>
		<div class="clearfix"></div>
		<div class="form-group">        
            </div>
        </div>
    </div>
</div>




<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("<?php echo $this->webroot;?>/ExpenseEntries/get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subhead").empty();
                $("#subhead").html(text);
                
            });  
}

function getStateGSTCode()
{
    var Id=$("#State").val();
    $.post("<?php echo $this->webroot;?>/Imprests/get_state_gst_code",
            {
             Id: Id
            },
            function(data,status){
                $("#state_code").val(data);
            });  
}
function validate_vendor()
{   
   var Branch = document.getElementsByName("branch[]");
   
   var Blength = Branch.length;
   var Vendor = $('#Vendor').val();
   var head = $('#head').val();
   var subhead = $('#subhead').val();
   var Address1 = $('#Address1').val();
   var State = $('#State').val();
   var state_code = $('#state_code').val();
   var VendorGST = $('#VendorGST').val();
   var GSTEnable = $('#GSTEnable').val();
   var pancard = $('#pancard').val();
   var servicetax = $('#servicetax').val();
   var pincode = $('#Pincode').val();
   var comp = document.getElementsByName("comp[]");
   var length = comp.length;
   
   var TDSEnabled = $('#TDSEnabled').val();
   var TDSSection = $('#TDSSection').val();
   var TDS = $('#TDS').val();
   var TDSChange = $('#TDSChange').val();
   var TDSNew = $('#TDSNew').val();
   var IncomeCertCheck = $('#IncomeCertCheck').val();
   
   
   if(Vendor=='')
   {
       alert("Please Fill Vendor Name");
       return false;
   }
   else if(head=='')
   {
       alert("Please Select Head");
       return false;
   }
   else if(subhead=='')
   {
       alert("Please Select Sub Head");
       return false;
   }
   else if(Address1=='')
   {
       alert("Please Select Address 1");
       return false;
   }
   else if(State=='')
   {
       alert("Please Select State");
       return false;
   }
   else if(state_code=='')
   {
       alert("Please Select State Code");
       return false;
   }
   else if(GSTEnable=='')
   {
       alert("Plese Select GST Enable Yes/No");
       return false;
   }
   
   else if(VendorGST=='' && GSTEnable=='1')
   {
       alert("Please Fill Vendor GST No");
       return false;
   }
   else if(VendorGST.length!=15  && GSTEnable=='1')
   {
       alert("Vendor GST No should be in 15 Chars");
       return false;
   }
   else if(VendorGST.substring(0,2)!=state_code && GSTEnable=='1')
   {
       alert("First 2 Digits of Vendor GST No. Not Matched with Vendor State Code");
       return false;
   }
   
   else if(pancard=='' && GSTEnable=='1')
   {
       alert("Please Fill Pan Card");
       return false;
   }
   else if(pancard.length!=10 && GSTEnable=='1')
   {
       alert("Pan Card No. Should be 10 Digits Only");
       return false;
   }
   
   else if(pincode=='')
   {
       alert("Please Select Pincode");
       return false;
   }
   else if(pincode.length!=6)
   {
       alert("Pin Code 6 Digits Only");
       return false;
   }
   else if(TDSEnabled=='1' && TDSSection=='')
   {
       alert("Please Select TDS Section");
       return false;
   }
   else if(TDSEnabled=='1' && TDSSection!='' && TDS=='')
   {
       alert("Please Select TDS Section");
       return false;
   }
   else if(TDSEnabled=='1' && TDSChange=='Yes' && TDSNew=='')
   {
       alert("Please Fill Exemption TDS");
       return false;
   }
   else if(TDSEnabled=='1' && TDSChange=='Yes' && TDSNew!='' && IncomeCertCheck=='')
   {
       alert("Please Select Do You Have Relevant Income Tax Certificate Y/N");
       return false;
   }
   
   
   var div="";var Bflag = false;var Bflag1 = false;
   for(var i=0; i<Blength; i++)
   {
       if(Branch[i].checked)
       {
           Bflag = true;
           break;
//           if($('#Mas'+Branch[i].value).prop('checked') || $('#IDC'+Branch[i].value).prop('checked'))
//           {
//                Bflag = true;
//                for(var j=1; j<=2;j++)
//                {
//                    div = 'GSTEnable'+j+'Branch'+Branch[i].value;
//                    
//                    var div1="",div2="";
//                    if($('#'+div).prop('checked'))
//                    {
//                        
//                        div1 = 'GSTTypeBranch'+Branch[i].value+'comp'+j;
//                        div2 = 'GSTNoBranch'+Branch[i].value+'comp'+j;
//                        var lenDiv2 = $('#'+div2).val();
//                        if($('#'+div1).val()=='')
//                        {
//                            alert("Please Select GST Type");
//                            return false;
//                        }
//                        else if($('#'+div2).val()=='' )
//                        {
//                            alert("Please Fill GST No.");
//                            return false;
//                        }
//                        else if(lenDiv2.length!='15')
//                        {
//                            alert("GST NO. Should be in 15 Digits Only");
//                            return false;
//                        }
//                    }
//                    else
//                    {
//                        return false;
//                    }
//                }
//           }
//           else
//           {
//                  alert("Please Select Company");
//                    return false;
//           }
           
       }
   }
   if(!Bflag)
   {
       alert("Please Select At Least One Branch");
       return false;
   }
   
   var Cflag=false;
   for(var i=0; i<length; i++)
   {
       if(comp[i].checked)
       {
           Cflag = true;
           break;
       }
   }
   if(!Cflag)
   {
       alert("Please Select At Least One Company");
       return false;
   }
   
   return true;
}

function validate_pincode(evt,val)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

    if(val.length<=5)
    {
        return true;
    }
    else
    {
        return false;
    } 
}

function getDisplayGSTEnable(id,val,div)
{
    //alert(div);
    if($('#'+id).prop("checked"))
    {
        $('#'+div).show();
    }
    else
    {
        $('#'+div).hide();
    }
}

function getEnableComp(val)
{
    $('#Mas'+val).prop('disabled',false);
    $('#IDC'+val).prop('disabled',false);
    //document.getElementById('Mas''+val).disabled=false;
    
}

function getDisableGST(val)
{
    if(val=='1')
    var VendorGST = $('#VendorGST').prop('disabled',false);
   else
       
       var VendorGST = $('#VendorGST').prop('disabled',true);
}

function getTDSDisplay(val)
{
    if(val=='1')
    {
        $('#TDS').prop('required',true);
        $('#TDSSection').prop('required',true);
        $('#TDSTallyHead').prop('required',true);
        $('#TdsDisplay').show();
    }
    else
    {
        $('#TDS').prop('required',false);
        $('#TDSSection').prop('required',false);
        $('#TDSTallyHead').prop('required',false);
        $('#TdsDisplay').hide();
    }
}

function get_tds(val)
{
    
    $.post("<?php echo $this->webroot;?>/Imprests/get_tds",
            {
             SectionId: val
            },
            function(data,status){
                $("#TDS").val(data);
            });  
}
function getIncomeEnable(value)
{
  if(value=='Yes')
  {
      $('#TDSNew').prop("disabled",false);
$('#TDSNew').val("");
      $('#IncomeCertCheck').prop("disabled",false);
  }
  else
  {
      $('#IncomeCertCheck').val("");
      $('#TDSNew').prop("disabled",true);
      $('#TDSNew').val(""); 
      $('#IncomeCertCheck').prop("disabled",true);
  }
}



function IncomeCertCheckEnable(value)
{
    if(value=='No')
    {
        $('#TDSNew').prop("disabled",true);
        $('#TDSNew').val("");
        $('#IncomeCertCheck').prop("disabled",true);
        $('#IncomeCertCheck').val("");
        $('#TDSChange').val("No");
        alert("Without Relevant Income Tax Exemption Certificate TDS Will Not Change");
    }
}
function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
        {
            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
                 
            }
            else{
               alert("TDS Should Not More Than 100");
                 return false; 
           
           
        }
        }
	return true;
}

</script>