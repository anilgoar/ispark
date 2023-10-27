<?php //print_r($ExpenseMaster); 

echo $this->Html->script('sample/datetimepicker_css');

echo $this->Form->create('Gms',array('class'=>'form-horizontal','action'=>'approve_imprest_approval_finance_head','enctype'=>'multipart/form-data'));
if(!empty($ExpenseEntryMaster['0']))
{
    $readonly = true;
    //echo "<script>$('#GmsParticular').focus();</script>";
}
else
{
    $readonly = false;
}
?>

<div class="row">
<div id="breadcrumb" class="col-xs-12">
    <a href="#" class="show-sidebar">
    <i class="fa fa-bars"></i></a>
    <ol class="breadcrumb pull-left"></ol>
</div>
</div>

<div class="row">
<div class="col-xs-12 col-sm-12">
    <div class="box">
       
        <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
            <h4 class="page-header textClass" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">Approval Of Reject Imprest(Finance Head) <?php echo $this->Session->flash(); ?></h4>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Year</label>
                <div class="col-sm-4">
                    
                    <?php echo $this->Form->input('FinanceYear', array('options' => $finance_yearNew,'empty' => 'Select Year','value'=>$ExpenseEntryMaster['0'],'label' => false,'id'=>'FinanceYear', 'div' => false,'class'=>'form-control','selected' => $ExpenseMaster['1'],'readonly'=>$readonly)); ?>
                </div>
                <label class="col-sm-2 control-label">Month</label>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('Month', array('options' => array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                    'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'empty' => 'Select Month','value'=>$ExpenseEntryMaster[1],'label' => false,'id'=>'FinanceMonth', 'div' => false,'class'=>'form-control','selected' => $ExpenseMaster['1'],'readonly'=>$readonly)); ?>
                </div>        
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Head</label>
                <div class="col-sm-4"> 
                        <?php echo $this->Form->input('HeadId',array('label' => false,'options'=>$head,
                            'class'=>'form-control','empty'=>'Select','id'=>'head','onChange'=>"getSubHeading()",'value'=>$ExpenseEntryMaster[2],'required'=>true,'readonly'=>$readonly)); ?>
                </div>
                <label class="col-sm-2 control-label">Sub Head</label>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('SubHeadId',array('label' => false,'options'=>$SubHeading,
                        'class'=>'form-control','empty'=>'Select','id'=>'subHead','value'=>$ExpenseEntryMaster[3],'required'=>true,'readonly'=>$readonly)); ?>
                </div>        
            </div>
                        
            

       
            
        <div class="form-group has-info has-feedback">
            <label class="col-sm-2 control-label">Amount</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->input('Amount',array('label' => false,'value'=>'','placeholder'=>'Amount',
                       'class'=>'form-control','id'=>'Amount','value'=>$ExpenseEntryMaster[7],'onKeypress'=>'return isNumberKey(event)','required'=>true)); ?>
            </div>
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->texArea('description',array('label' => false,'placeholder'=>'Description',
                       'class'=>'form-control','id'=>'description','value'=>$ExpenseEntryMaster[8],'required'=>true,'readonly'=>$readonly)); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Company</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input('CompId',array('label' => false,'options'=>$company_master,
                   'class'=>'form-control','id'=>'CompId','value'=>$ExpenseEntryMaster[11],'required'=>true,'readonly'=>$readonly)); ?>
            </div>
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-4">
                   <?php echo $this->Form->input('EntryStatus',array('label' => false,'options'=>array('Open'=>'Open','Close'=>'Close'),
                       'class'=>'form-control','id'=>'entry_status','value'=>$ExpenseEntryMaster[10],'required'=>true,'readonly'=>$readonly)); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4" style="display: none">
                   <?php echo $this->Form->input('EntryDate',array('label' => false,
                       'class'=>'form-control','placeholder'=>'Date','value'=>$ExpenseEntryMaster[11],'id'=>'entry_date','onclick'=>"javascript:NewCssCal ('entry_date','ddMMyyyy','arrow',false,'24',false,'')",'readonly'=>true,'required'=>true,'readonly'=>true)); ?>
            </div>
            
        </div>
        <div class="form-group">						
            <label class="col-sm-2 control-label">&nbsp;</label>
            <div class="col-sm-2">
                <button onclick="return update_grn()" class="btn btn-primary btn-label-left">Update</button>
                <a href="view_imprest_approval_finance_head?year=<?php echo $year_param; ?>&branch=<?php echo $branchId_param; ?>" class="btn btn-info" >Back</a>
            </div>
        </div>
        </div>
    </div>
    </div>	
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
		<h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">Details Entry</h4>
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom ">
                     <tr>
                         <th colspan="2">Budget <span id="budget" style="position: relative;left:100px;" >0.00</span><span id="budget1" style="display: none;">0</span></th>
                        <th colspan="2">Consume<span id="Consume"  style="position: relative;left:200px;">0.00</span></th>
                        <th colspan="2">Balance<span id="Balance"  style="position: relative;left:108px;">0.00</span><span id="Balance1" style="display:none">0</span></th>
                        <th colspan="3">&nbsp;</th>
                    </tr>   
                    <tr>
                        <th>Sr. No.</th>
                        <th>Branch</th>
                        <th>Cost Center</th>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Tax </th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                        <?php if(empty($branchArr)) { $branchArr="";} ; ?>
                    <tr <?php  foreach ($result as $post): $GTotal1 += $post['teep']['Total']; endforeach; if($GTotal1==$ExpenseEntryMaster[7] && !empty($ExpenseEntryMaster)) { ?> style="display:none" <?php } ?> >
                        <th></th>
                        <th><div id="branchdrop"><?php echo $this->Form->input('branch_name' ,array('label' =>false,'options'=>$branch_master,'empty' => 'Select','style'=>'width:200px;','class' => 'form-control','onchange'=>"get_cost_center(this.value);")); ?></div></th>
                        <th><div id="costdrop"><?php echo $this->Form->input('cost_center' ,array('label' =>false,'placeholder' => 'Cost Center','options'=>'Select','style'=>'width:200px;','class' => 'form-control')); ?></div></th>
                        <th><?php echo $this->Form->input('Particular' ,array('label' =>false,'placeholder' => 'Details','class' => 'form-control')); ?></th>
                        <th><?php echo $this->Form->input('amount' ,array('label' =>false,'placeholder' => 'Amount','onBlur'=>"getTotalCostAmount()",'class' => 'form-control','onKeypress'=>'return isNumberKey(event)')); ?></th>
                        <th><?php echo $this->Form->input('Rate' ,array('label' =>false,'value'=>'0','placeholder' => 'Rate','class' => 'form-control','onChange'=>"getTotalCostAmount('1');",'onKeypress'=>'return isNumberKey(event)','readonly'=>true)); ?></th>
                        <th><div id="Tax">0</div></th>
                        <th><div id="BTotal">0</div></th>
                        <th><button onclick="return add_cost_value_grn()"> ADD</button></th>
                    </tr>
            
                    <?php  $i = 0; $idx ="";$Tot=0;$Tax = 0; $GTotal = 0; $CheckTotal=0;
                    foreach ($result as $post): 

                        $BranchTotal[$post['teep']['BranchId']] += $post['teep']['Amount'];
                        $CheckTotal += $post['teep']['Total'];
                        $idx.=$post['teep']['Id'].','; ?>
                            <tr <?php   $i++;?>>
                            <td><?php echo $i;?></td>
                            <td><?php echo $post['cm']['Branch'];?></td>
                            <td><?php echo $post['cm']['cost_center'];?></td>
                            <td><?php echo $post['teep']['Particular'];?></td>
                            <td><?php echo $post['teep']['Amount'];?></td>
                            <td><?php echo $post['teep']['Rate'];?></td>
                            <td><?php echo $post['teep']['Tax'];?></td>
                            <td><?php echo $post['teep']['Total'];?></td>
                            <td> 
                                <?php if($action=='approve') { ?>
                                <button name = Delete class="btn btn-primary" value="<?php echo $post['teep']['Id']; ?>" onClick ="return delete_grn(this.value)">Delete</button> 
                                <?php } ?>
                            </td>
                            </tr>
                    <?php $Tot+=$post['teep']['Amount']; $Tax += $post['teep']['Tax']; $GTotal += $post['teep']['Total'];
                    endforeach;  unset($result); 

                    echo $this->Form->input('checkTotal',array('label'=>false,'value'=>$CheckTotal,'type'=>'hidden','id'=>'checkTotal')); 
                    echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); 
                    if($Tot)
                    {
                    ?>
                            <tr>
                                <th colspan="4">Total</th>
                                <th><?php echo number_format((float)$Tot, 2, '.', ''); ?></th>
                                <th>Tax</th>
                                <th><?php echo number_format((float)$Tax, 2, '.', ''); ?></th>
                                <th><?php echo $GTotal = number_format((float)$GTotal, 2, '.', ''); //$GTotal = (int)($Tot+$Tax); ?></th>
                                <th></th>
                            </tr>

                    <?php } ?>
                    </table>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div class="form-group">
                        <div class="col-sm-3" id="image_preview">
                            <img id="previewing" src="<?php echo $this->webroot.'app/webroot/GRN/'.$ExpenseEntryMaster['12']; ?>" width="200" height="200" />
                            <br/>
                            <a href="<?php echo $this->webroot.'app/webroot/GRN/'.$ExpenseEntryMaster['12']; ?>">Click Here To View</a>
                        </div>
                        <label class="col-sm-2 control-label">Reject Remarks</label>
                        <div class="col-sm-3">
                            <textarea name="RejectRemarks" id="RejectRemarks" value="" class="form-control" ></textarea>
                        </div>
                    </div>
                <?php if($action=='approve') { ?>
                <div class="form-group">
                    <div id="BranchWiseTotal" style="display:none"><?php echo json_encode($BranchTotal); ?></div>;
                    <input type="hidden" value="<?php echo $GTotal;?>" name="BranchWiseTotal1" id="BranchWiseTotal1"   />
                    <input type="hidden" value="1" name="gstEnable" id="gstEnable" />
                    <input type="hidden" value="<?php echo $ExpenseId;?>" name="ExpenseId" id="ExpenseId" />
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" value="Approve" name="Approve" onclick="return validate_vendor()"  class="btn btn-primary pull-right"  />
                    </div>
                    <div class="col-sm-1">
                        <input type="submit" value="DisApprove" name="DisApprove"  class="btn btn-primary pull-right"  />
                    </div>
                </div>
                <?php } ?>
				</div>
			</div>
		</div>
</div>
<?php echo $this->Form->end();?>

<script>
    $(document).ready(function (e) {
        
$(function() {
        $("#GMSPaymentFile").change(function() {
			
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
    
    
    <script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
    //var vendorId=$("#vendorId").val();
  $.post("get_sub_heading1",
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
                    alertify.error("Budget Not Available");
                }
            });
}

function get_cost_center(BranchId)
{
   var FinanceYear=$("#FinanceYear").val();
   var FinanceMonth=$("#FinanceMonth").val();
   var HeadId=$("#head").val();
   var SubHeadId=$("#subHead").val();
   var BranchTotal = 0;
   var BranchWiseJson = $("#BranchWiseTotal").html();
   var VendorId=$("#vendorId").val();
   var CompId = $("#CompId").val();
   var CFlag = true;
   
   if(BranchId=='')
    {
        $('#GmsCostCenter').empty();
        return false;
    }
    
    $.post("getCostCenter",
       {
        BranchId: BranchId,
        CompId:CompId
       },
       function(data,status){
           var text='<option value="">Select</option>';
                   var json = jQuery.parseJSON(data);
                   for(var i in json)
                   {
                       text += '<option value="'+i+'">'+json[i]+'</option>';
                   }
         $('#GmsCostCenter').empty();
         $('#GmsCostCenter').html(text);
       });
   
    
    $.post("get_budget",
    {
     BranchId: BranchId,
     FinanceYear:FinanceYear,
     FinanceMonth:FinanceMonth,
     HeadId:HeadId,
     SubHeadId:SubHeadId,
     VendorId:VendorId
    },
    function(data,status){
      
      
      
      if(data=='0')
      {
          alert("Budget Not Made");
          $('#GmsCostCenter').empty();
      }
      else if(data=='1')
      {
          alert("Budget is Closed");
          $('#GmsCostCenter').empty();
      }
      
      var dataArr= data.split(",");
      
      var Budget = dataArr[0];
      var Consume = dataArr[1];
      var Balance = dataArr[2];
      var RateEnable = dataArr[3];
     
      if(parseInt(Balance)==0)
      {
          alert("Budget Closed! Please Reopen Business Cased");
          $('#GmsCostCenter').empty();
      }
      else
      {
          if(BranchWiseJson=='' || BranchWiseJson=='null')
          {
              
          }
          else
          {
              try{
                 
                var BranchArr = jQuery.parseJSON(BranchWiseJson);
                BranchTotal = parseInt(BranchArr[BranchId]);
                
                if(BranchTotal=='' || isNaN(BranchTotal))
                {
                    
                    BranchTotal = 0;
                }
                else
                {       
                    Balance = parseInt(Balance)- parseInt(BranchTotal);
                }
                 
            }
            catch(err)
            {
                BranchTotal = 0;
            }
          }
      }
     
      if(parseInt(Balance)<=0)
      {
          alert("Budget Balance is 0. You can't add more Amount.");
          //CFlag = false;
          $('#GmsCostCenter').empty();
          return false;
      }
      else
      {
        $('#budget').empty();
        $('#budget').html(Budget);

        $('#Consume').empty();
        $('#Consume').html(Consume);
        $('#Balance').empty();
        $('#Balance').html(Balance);
        $('#Balance1').empty();
        $('#Balance1').html(Balance);
        
      }
    });
   
    
}

function getTotalCostAmount(con)
{
    var Total = 0,costAmount; var cost=0;var BranchTotal = 0;
    var Balance = parseInt($("#Balance1").html());
    var Amount = parseInt($("#GmsAmount").val());
    var BranchWiseJson = $("#BranchWiseTotal").html();
    var MainAmount = $('#Amount').val();
    var BranchId = $('#GmsBranchName').val();
    var checkTotal = $('#checkTotal').val();
    
    if(BranchId=='')
    {
        alert("Please Select Branch Name");
        return false;
    }
    else
    {
        
            
    }
    
    if(parseInt(Amount)>parseInt(MainAmount))
    {
        alert("Amount is More Than Balance Amount");
        $("#GmsAmount").val('0');
        return false;
    }
    
    if((Balance-Amount)<0)
    {
        alert("Amount is Less Than Total Bill Amount");
        $("#GmsAmount").val('0');
        $("#Balance").html(Balance);
        return false;
    }
    else
    {
        
        Balance=Balance-Amount;

        var GmsRate =0;
        var Rate = parseInt($("#GmsRate").val());
        var tax = 0;
        
        //alert(parseInt(parseInt(Amount)+parseInt(checkTotal)));
        if(parseInt(parseInt(Amount)+parseInt(checkTotal))>parseInt(MainAmount))
            {
                //alert(parseInt(parseInt(Amount)+parseInt(checkTotal)));
                alert("Amount is More Than Balance Amount");
                $("#GmsAmount").val('0');
                return false;
            }
        con = $('#gstEnable').val();
       

        var Btot = parseFloat(+Amount+ +tax).toFixed(3);

        $("#Balance").html(Balance);
        $("#BTotal").html(Btot);
    }
    
    

    
}

function add_cost_value_grn()
{
    var Particular = $('#GmsParticular').val();
    var BranchId = $('#GmsBranchName').val();
    var CostCenter = $('#GmsCostCenter').val();
    var Amount = $('#GmsAmount').val();
    var Rate = $('#GmsRate').val();
    var con = $('#gstEnable').val();
    var ExpenseId = $("#ExpenseId").val();
    
    if(Particular=='')
    {
        alert("Please Fill Particular");
        $('#GmsParticular').focus();
        return false;
    }
    else if(BranchId=='')
    {
        alert("Please Select Branch");
        $('#GmsBranchName').focus();
        return false;
    }
    else if(CostCenter=='')
    {
        alert("Please Select Cost Center");
        $('#GmsCostCenter').focus();
        return false;
    }
    else if(Amount=='' || Amount=='0')
    {
        alert("Please Fill Amount");
        $('#GmsAmount').focus();
        return false;
    }
    
    
    
    $.post("add_field_value1",
            {
             ExpenseEntryType:'Vendor',
             Particular: Particular,
             BranchId:BranchId,
             CostCenter:CostCenter,
             Amount:Amount,
             Rate:Rate,
             ExpenseEntry:ExpenseId
            },
            function(data,status){
               if(data==1)
               {
                   alert("Record Added Successfully");
                   location.reload();
               }
               else
               {
                   alert("Record Not Saved! Please Try Again");
               }
            });  
    
    return false;
}

function validate_vendor()
{
    var checkTotal = $('#BranchWiseTotal1').val();
    var Total = $('#Amount').val();
     Total = parseFloat(Total).toFixed(2);
    
    
    if(checkTotal==Total)
    {
        return true;
    }
    else
    {
        alert("Amount is More Than Total Bill Amount");
        return false;
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
        }
	return true;
}


function update_grn()
{
    var FinanceYear=$("#FinanceYear").val();
    var FinanceMonth=$("#FinanceMonth").val();
    var HeadId=$("#head").val();
    var SubHeadId=$("#subHead").val();
    var vendorId = '';
    var Amount = $("#Amount").val();
    var description = $("#description").val();
    var entry_date = $("#entry_date").val();
    var entry_status = $("#entry_status").val();
    var CompId = $("#CompId").val();
    var ExpenseId = $("#ExpenseId").val();
    
    if(FinanceYear=='')
    {
        alert("Select Finance Year");
        $("#FinanceYear").focus()
        return false;
    }
    else if(FinanceMonth=='')
    {
        alert("Select Finance Month");
        $("#FinanceMonth").focus()
        return false;
    }
    else if(HeadId=='')
    {
        alert("Select Head");
        $("#head").focus()
        return false;
    }
    else if(SubHeadId=='')
    {
        alert("Select Sub Head");
        $("#subHead").focus()
        return false;
    }
    
    
    else if(Amount=='')
    {
        alert("Please Fill Amount");
        $("#Amount").focus()
        return false;
    }
    else if(description=='')
    {
        alert("Select Fill Description");
        $("#description").focus()
        return false;
    }
    else if(entry_date=='')
    {
        alert("Select Date");
        $("#entry_date").focus()
        return false;
    }
    else if(entry_status=='')
    {
        alert("Select Status");
        $("#entry_status").focus()
        return false;
    }
    else if(CompId=='')
    {
        alert("Select Company");
        $("#CompId").focus()
        return false;
    }
    $.post("update_grn_tmp",
            {
             FinanceYear:FinanceYear,
             FinanceMonth: FinanceMonth,
             HeadId:HeadId,
             SubHeadId:SubHeadId,
             BillNo:'',
             bill_date:'',
             Amount:Amount,
             description:description,
             entry_date:entry_date,
             CompId:CompId,
             entry_status:entry_status,
             ExpenseId:ExpenseId
            },
            function(data,status){
               if(data==1)
               {
                   alert("Details Has been Updated.");
                   location.reload();
               }
               else
               {
                   alert("Details Has Not been Updated. Please Try Again");
               }
            });
      
    
    return false;
    
}


function delete_grn(Id)
{   
    //alert(Id)
    $.post("delete_grn1",
            {
             Id:Id,
            },
            function(data,status){
               if(data==1)
               {
                   alert("Record has been Deleted");
               }
               else
               {
                   alert("Record Not Deleted");
               }
            });
      
    location.reload();
    return false;
}
</script>