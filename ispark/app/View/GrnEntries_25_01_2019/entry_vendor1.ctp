<?php
echo $this->Html->script('sample/datetimepicker_css');
?>
<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("get_sub_heading",
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
                $("#subHead").empty();
                $("#subHead").html(text);
                
            });  
}
function getVendor()
{
    var HeadId=$("#head").val();
    var SubHeadId=$("#subHead").val();
    
  $.post("get_vendor",
            {
             HeadId: HeadId,
             SubHeadId:SubHeadId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#vendorId").empty();
                $("#vendorId").html(text);
                
            });  
}

function get_branch(vendorId)
{
    var HeadId=$("#head").val();
    var SubHeadId=$("#subHead").val();
    var FinanceYear=$("#finance_year").val();
    var FinanceMonth=$("#finance_month").val();
  $.post("get_branch_for_grn",
            {
             VendorId: vendorId,
             HeadId:HeadId,
             SubHeadId:SubHeadId,
             FinanceYear: FinanceYear,
             FinanceMonth: FinanceMonth
            },
            function(data,status){
                
                $("#branchDetails").html(data);
            });   
}

function getAmountDetails()
{
    
    var FinanceYear=$("#finance_year").val();
    var FinanceMonth=$("#finance_month").val();
    var HeadId=$("#head").val();
    var SubHeadId=$("#subHead").val();
    
    
    if(FinanceYear==''){ return;}
    if(FinanceMonth==''){ return;}
    if(HeadId==''){ return;}
    if(SubHeadId==''){ return;}
    
    $.post("get_amount_desc",
            {
             FinanceYear: FinanceYear,
             FinanceMonth: FinanceMonth,
             HeadId: HeadId,
             SubHeadId: SubHeadId
            },
            function(data,status){
               
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    $("#"+i).html(""+json[i]);
                }
                
                if(json['ApproveAmount']>0)
                {
                    getCostCenter();
                    getVendor();
                }
                else
                {
                    alertify.error("Expense Not Available");
                }
            });
}

function getCostCenter()
{
   var BranchId=$("#branchId").val();
   $.post("get_cost_center",
            {
             BranchId: BranchId
            },
            function(data,status){
              $('#costcenterBox').show();
              $('#costcenter').html(data);
            });
}

function getTotalCost(id)
{
    var Total = 0,costAmount; var cost=0;
    var costmaster = $('#costcenterIds').val().split(",");
    for(var i in costmaster)
    {
       costAmount = $('#cost'+costmaster[i]).val();
       if(costAmount!='')
       {
            cost = parseInt(costAmount);
            Total +=cost ||0;
       }
        else
            Total +=0;
    }
    if($('#BalanceAmount').html()<Total)
    {
        $('#cost'+id).val(0);
        alertify.error("Total Amount is not More Than Balance Amount or Reopen Business Case");
    }
    else
    {
        $('#Amount').val(Total);
    }
    
}
function validate_vendor()
{
    var entry_date = $('#entry_date').val();
    if(entry_date=='')
    {
        alert("Please Select Date");
        return false;
    }
    else if($('#Amount').val()=='' && $('#entry_status').val()=='Open')
    {
        alert("Amount is not empty");
        return false;
    }
    else
    {
        return true;
    }
    
}

function getAmountTotal(branch)
{
    var branchmaster = $('#BranchArr').val().split(",");
    var branchId = "";var GTotal = 0;
    for(var jjj in branchmaster)
    {
        branchId = branchmaster[jjj];
        var Total = 0,costAmount; var cost=0;
        var costmaster = $('#CostIdArray'+branchId).val().split(",");

        for(var i in costmaster)
        {

           costAmount = $('#Branch'+branchId+'cost'+costmaster[i]).val(); 
           //alert('#Branch'+branchId+'cost'+costmaster[i]).val()); 
           if(costAmount!='')
            {
                cost = parseInt(costAmount);
                Total +=cost ||0;
            }
           else
            {   
                Total +=0;
            }     
        }
        
        if($('#GSTEnable'+branchId).val()=='1')
        {
            var tax = $('#Tax'+branchId).val();
            if(tax==0 || tax=='')
            {
                tax="0.18";
            }
            else
            {
                tax = tax/100;
            }
            //$('#Amount').val(Total);
            //tax = tax*0.01;
            $('#TAmt'+branchId).html(Total);
            if($('#GSTType'+branchId).val()=='Integrated')
            {
                var IGST = Total*tax;
                Total = Total+IGST;
                $('#IGST'+branchId).html(IGST);
            }
            else
            {
                var SGST = Total*(tax/2);
                var CGST = SGST;
                Total = Total+SGST+SGST;
                $('#SGST'+branchId).html(SGST);
                $('#CGST'+branchId).html(CGST);
            }
        }
        if($('#BalAmt'+branchId).html()<Total)
        {
            $('#Branch'+branchId+'cost'+costmaster[i]).val(0);
            alertify.error("Total Amount is not More Than Balance Amount or Reopen Business Case");
        }
        else
        {
            $('#TotAmt'+branchId).html(Total);
        }
        GTotal +=Total;
    }
    $('#Amount').val(GTotal);
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
        }
	return true;
}

</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
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

<?php echo $this->Form->create('GrnEntries',array('class'=>'form-horizontal','action'=>'vendor_save1')); ?>
<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Vendor Entry</h4>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('FinanceYear',array('label' => false,'options'=>$FinanceYearArr,'class'=>'form-control','empty'=>'Select','id'=>'finance_year','required'=>true)); ?>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('FinanceMonth',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'finance_month','required'=>true)); ?>
            
             
            </div>   
        </div>
    </div>
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Expense Head</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('HeadId',array('label' => false,'options'=>$head,
                   'class'=>'form-control','empty'=>'Select','id'=>'head','onChange'=>"getSubHeading()",'required'=>true)); ?>
            
             
            </div>   
        </div>
        <label class="col-sm-2 control-label">Expense Sub Head</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('SubHeadId',array('label' => false,'options'=>'',
                   'class'=>'form-control','empty'=>'Select','id'=>'subHead','onChange'=>"getVendor()",'required'=>true)); ?>
            
             
            </div>   
        </div>
    </div>
    
    
    
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Vendor</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('Vendor',array('label' => false,'options'=>'','class'=>'form-control','empty'=>'Select','id'=>'vendorId','onChange'=>'get_branch(this.value)','required'=>true)); ?>
                
            </div>    
        </div>
    </div>
    
   <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Bill No.</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Bill_No',array('label' => false,'value'=>'','placeholder'=>'Bil No',
                   'class'=>'form-control','id'=>'BillNo','onKeypress'=>'return isNumberKey(event)','value'=>'','required'=>true)); ?>
               
            </div>   
        </div>
        <label class="col-sm-2 control-label">Bill Date</label>
        <div class="col-sm-3">
            
               <?php echo $this->Form->input('bill_date',array('label' => false,'placeholder'=>'Bill Date',
                   'class'=>'form-control','id'=>'bill_date','onclick'=>"javascript:NewCssCal ('bill_date','ddMMyyyy','arrow',false,'24',false)",'required'=>true)); ?>
        </div>
    </div>
    
    
    
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('Amount',array('label' => false,'value'=>'','placeholder'=>'Amount',
                   'class'=>'form-control','id'=>'Amount','onKeypress'=>'return isNumberKey(event)','value'=>'','required'=>true)); ?>
               
            </div>   
        </div>
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-3">
            
               <?php echo $this->Form->texArea('description',array('label' => false,'placeholder'=>'Description',
                   'class'=>'form-control','id'=>'description','required'=>true)); ?>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Status</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('EntryStatus',array('label' => false,'options'=>array('Open'=>'Open','Close'=>'Close'),
                   'class'=>'form-control','id'=>'entry_status','required'=>true)); ?>
            </div>   
        </div>
    </div>
    
    <div class="clearfix"></div>
    
   
</div>



<div class="box-content">
    <div class="form-group has-info has-feedback">
        <div class="col-sm-2">
            <button name="FinalSave" value="Save" onclick="return validate_vendor()" class="btn btn-info">Submit</button> 
        </div>
        <div class="col-sm-2">
            <div id="resion"></div>   
        </div>
    </div>
    <div class="clearfix"></div> 
</div>

 <?php echo $this->Form->end(); ?>