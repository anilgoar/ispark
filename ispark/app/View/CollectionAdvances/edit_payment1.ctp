<script>
    function upd_collection()
    {
        var company_name,branch_name,financial_year,bill_no,pay_type,pay_no,bank_name,deposit_bank,pay_type_dates,
                pay_dates,bill_amt,status,bill_passed,tds_ded,net_amt,deduction,remarks,PaymentId;
        
        company_name = $('CollectionAdvanceCompanyName').val();
        branch_name = $('CollectionAdvanceBranchName').val();
        financial_year = $('CollectionAdvanceFinancialYear').val();
        pay_type = $('CollectionAdvancePayNo').val();
        pay_no = $('CollectionAdvancePayAmount').val();
        pay_type_dates = $('CollectionAdvancePayTypeDates').val();
        bank_name = $('CollectionAdvanceBankName').val();
        deposit_bank = $('CollectionAdvanceDepositBank').val();
        pay_dates = $('CollectionAdvancePayDates').val();
        
        bill_no = $('CollectionAdvanceBillNo').val();
        bill_amt = $('CollectionAdvanceAmount').val();
        status = $('CollectionAdvanceStatus').val();
        bill_passed = $('CollectionAdvanceBillPassed').val();
        tds_ded = $('CollectionAdvanceTdsDed').val();
        net_amt = $('CollectionAdvanceNetAmt').val();
        deduction = $('CollectionAdvanceDeduction').val();
        remarks = $('CollectionAdvanceRemarks').val();
        PaymentId = $('#UpdateId').val();
        
        
        if(bill_no=='' || bill_no=='0')
        {
            alert("Please Fill Bill No");
            return false;
        }
        else if(bill_amt=='')
        {
            alert("Bill Amount should not be blank");
            return false;
        }
        else if(status=='')
        {
            alert("Please Select Status");
            return false;
        }
        else if(bill_passed=='')
        {
            alert("Please Fill Bill Passed Amount");
            return false;
        }
        else if(net_amt=='')
        {
            alert("Please Fill Net Amount");
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Remarks");
            return false;
        }
        
        $.post("get_collection_upd_bill_data",
            {
             collection_id:PaymentId,
             company_name: company_name,
             branch_name: branch_name,
             financial_year:financial_year,
             pay_type:pay_type,
             pay_no:pay_no,
             pay_amount:bank_name,
             bank_name:deposit_bank,
             deposit_bank:deposit_bank,
             pay_dates:pay_dates,
             no_of_bills:pay_type_dates,
             bill_no:bill_no,
             bill_amount:bill_amt,
             bill_passed:bill_passed,
             tds_ded:tds_ded,
             net_amount:net_amt,
             deduction:deduction,
             status:status,
             remarks:remarks,
             pay_type_dates:pay_type_dates
             
             
            },
            function(data,status)
            {
                
                
            });  

        
        
    }
    
    function del_collection(val)
    {
        $.post("delete_upd_particular",
            {
                Id:val
            },
            function(data,status)
            {
                if(data==1)
                {
                    alert("Record Deleted Successfully");
                    location.reload();
                }
                else
                {
                    alert("Record Not Deleted");
                }
            });  
        return false;
    }
    
    function get_bill_amount1(bill_no)
    {
        var branch_name = 	document.getElementById('CollectionAdvanceBranchName').value;
        var finance_year = 	document.getElementById('CollectionAdvanceFinancialYear').value;
        var company_name = 	document.getElementById('CollectionAdvanceCompanyName').value;
        var PaymentId = 	$('#UpdateId').val();
        
        
        $.get("get_bill_amount1",
            {
                bill_no:bill_no,
                branch_name:branch_name,
                finance_year:finance_year,
                company_name:company_name,
                collection_id:PaymentId
            },
            function(data,status)
            {
                var amount = data;
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
                    document.getElementById('CollectionAdvanceAmount').value = amount;
                    get_bill_remark();
                }
            });
    }
    
</script>
<?php //print_r($payment_master); 
$pay_type = 'Cheque'; 
$flag1 =false;

if($payment_master['0']!='') {$flag1 =true;}

?>
<?php echo $this->Form->create('CollectionAdvance',array('class'=>'form-horizontal','action'=>'update_payment','enctype'=>'multipart/form-data')); ?>
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
                                <i class="fa fa-search"></i>
                                <span>Collection Advance</span>
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
                                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
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

                                                <?php echo $this->Form->input('financial_year', array('options' => $finance_yearNew,'empty' => 'Select Year','label' => false, 'div' => false,'class'=>'form-control','value' => $payment_master['2'])); ?>						</div>
                                        <?php if($payment_master['4'] =='') { ?>
                                        <label class="col-sm-2 control-label">Select</label>

                                                        <div class="col-sm-3">
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value = "Cheque" id = 'type' onClick="return collection_validate(this.value)" checked>Cheque
                                                                                <i class="fa fa-circle-o"></i>
                                                                        </label>
                                                                </div>
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value="RTGS" id="type"  onClick="return collection_validate(this.value)" >RTGS
                                                                                <i class="fa fa-circle-o"></i>
                                                                        </label>
                                                                </div>                                   
                                </div>
                      <?php } else { ?>
                                        <label class="col-sm-2 control-label">Select</label>
                                                        <div class="col-sm-3">
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value = "Cheque" id = 'type' onClick="collection_validate(this.value)" <?php if($payment_master['4'] == 'Cheque') {echo "checked"; $pay_type = 'Cheque';} else {echo "disabled";} ?>>Cheque
                                                                                <i class="fa fa-circle-o"></i>
                                                                        </label>
                                                                </div>
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value="RTGS" id="type"  onClick="return collection_validate(this.value)" <?php if($payment_master['4'] == 'RTGS') { echo "checked"; $pay_type = 'RTGS';}else {echo "disabled";} ?>>RTGS
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
                                                <?php	echo $this->Form->input('pay_amount', array('label'=>false,'class'=>'form-control','value' => $payment_master['8'],'placeholder' => 'Amount','required'=>true,'onkeypress'=>'return isNumberKey(event)','maxlength'=>'9')); ?>
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

                                                    <?php	echo $this->Form->input('pay_type_dates', array('label'=>false,'class'=>'form-control','type'=>'text','value' => $date,'required'=>true,'readonly'=>$flag1, 'onClick'=>"displayDatePicker('data[CollectionAdvance][pay_type_dates]');")); ?>
                                            </div>
                                            <label class="col-sm-2 control-label">Drawn Bank</label>
                                            <div class="col-sm-3">

                                                    <?php	echo $this->Form->input('bank_name', array('label'=>false,'class'=>'form-control','value' => $payment_master['6'],'placeholder'=>'Select Bank','required'=>true,'readonly'=>$flag1)); ?>
                                            </div>
                                    </div>

                                    <div class="form-group has-success has-feedback">
                                        <label class="col-sm-2 control-label">Deposit Bank</label>
                                        <div class="col-sm-3">
                                                <?php	echo $this->Form->input('deposit_bank', array('label'=>false,'class'=>'form-control','value' => $payment_master['9'],'options'=>$bank,'empty'=>'Select Bank', 'required'=>true,'readonly'=>$flag1)); ?>
                                        </div>

                                        <label class="col-sm-2 control-label">Payment Date</label>
                                        <div class="col-sm-3">
                                                <?php  
                                                $date = date_create($payment_master['7']);
                                                $date = date_format($date,'d-m-Y');
                                                ?>	
                                                <?php	echo $this->Form->input('pay_dates', array('label'=>false,'class'=>'form-control','onClick'=>"displayDatePicker('data[CollectionAdvance][pay_dates]');",'value' => $date,'placeholder' => 'Select Date','required'=>true,'readonly'=>$flag1)); ?>
                                        </div>
                                    </div>


                                <div class="form-group has-success has-feedback">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-3">
                                            <?php	echo $this->Form->input('no_of_bills', array('label'=>false,'class'=>'form-control','type'=>'hidden','value' => '0','required'=>true,'onkeypress'=>'return isNumberKey(event)','readonly'=>$flag1)); ?>
                                    </div>                    
                                    <label class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-2">
                                        <button onclick="return update_collection()" name="button" value="button" class="btn btn-success btn-label-left">Update</button> &nbsp; &nbsp; &nbsp;	
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
            <h4 class="page-header">Collection Advance</h4>
            <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
                

               <!-- <tr>
						<th></th>
						<th><?php //echo $this->Form->input('cost_center' ,array('label' =>false,'options'=>$cost_list,'empty'=>'select','placeholder' => 'Cost Center','class' => 'form-control')); ?></th>
						<th><?php //echo $this->Form->input('amount' ,array('label' =>false,'placeholder' => 'Bill Amount','onBlur'=>'','class' => 'form-control','value'=>'')); ?></th>
						<th><?php //echo $this->Form->input('remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control','onBlur'=>'validate_advance_data();')); ?></th>
						<th><button onclick="return add_collection_advance()"> ADD</button></th>
					</tr>!-->
        </table> 
        <div id="oo">
<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
<tr>
                        <th>Sr. No.</th>
                        <th>Cost Center</th>
                        <th>Bill Amt</th>
                        
                        <th>Remarks</th>
                        <!--<th>Action</th>!-->
                </tr>
<?php  $i = 0; $idx ="";$net=0;
foreach ($result as $post): ?>
        <?php $idx.=$post['CollectionBillAdvanceUpdate']['PaymentId'].','; ?>
        <tr <?php   $i++;?>>
        <td><?php echo $i;?></td>

<td><?php echo $this->Form->input('CollectionBillAdvanceUpdate.'.$post['CollectionBillAdvanceUpdate']['PaymentId'].'.bill_no',
array('label'=>false,'value'=>$post['CollectionBillAdvanceUpdate']['bill_no'],'options'=>$cost_list,'class'=>'form-control','required'=>true)); ?></td>
<td><?php echo $this->Form->input('CollectionBillAdvanceUpdate.'.$post['CollectionBillAdvanceUpdate']['PaymentId'].'.bill_passed',array('label'=>false,'value'=>$post['CollectionBillAdvanceUpdate']['bill_passed'],'class'=>'form-control','onBlur'=>'getAmount1(this.id)','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
<td><?php echo $this->Form->input('CollectionBillAdvanceUpdate.'.$post['CollectionBillAdvanceUpdate']['PaymentId'].'.remarks',array('label'=>false,'value'=>$post['CollectionBillAdvanceUpdate']['remarks'],'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

        <!--<td> <button name = "Delete" class="btn btn-primary" value="<?php echo $post['CollectionBillAdvanceUpdate']['PaymentId']; ?>" onClick ="return delete_upd_particular(this.value)">Delete</button> </td>!-->
        </tr>
<?php $net+=$post['CollectionBillAdvanceUpdate']['bill_passed'];
endforeach; ?><?php unset($TMPCollectionAdvanceParticulars); ?>
<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
</table>                        
                        </div>                
				</div>
			</div>
		</div>
	</div>


				
                		
                        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        	<tr><th>Net Amount</th><td><?=($net)?></td></tr>
                            <tr><th>Cheque/RTGS</th><td><?=$payment_master['8']?></tr>
                            <?php $gTotal =$net-$net_bill_deduct-$payment_master['8']-$deduct; ?>
                            <tr><th>Always (Net Amount - Cheque/RTGS - Deduction) = 0</th><td class="<?php if((round($gTotal))!=0){echo "bg-danger";} else{ echo "bg-success";} ?>"><?php echo '<font color="black">'.round($gTotal).'</font>';?></tr>
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
                        <?php echo $this->Form->input('UpdateId',array('type'=>'hidden','id'=>'UpdateId','value'=>$updateId)); ?>        
          		<button onclick="return validate_save_collection();" class="btn btn-success btn-label-left">Submit </button>
				</div>
			</div>
		</div>
	</div>
<script>
    $(document).ready(function (e) {
        
$(function() {
        $("#CollectionAdvancePaymentFile").change(function() {
			
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
    function update_collection()
    {
    var PaymentId=$("#UpdateId").val();
    var FinanceYear=$("#CollectionAdvanceFinancialYear").val();
    var BranchName=$("#CollectionAdvanceBranchName").val();
    var CompanyName=$("#CollectionAdvanceCompanyName").val();
    //var type=$("#type").val();
    var PayNo = $("#CollectionAdvancePayNo").val();
    
    var PayAmount = $("#CollectionAdvancePayAmount").val();
    var PayTypeDates = $("#CollectionAdvancePayTypeDates").val();
    var BankName = $("#CollectionAdvanceBankName").val();
    var DepositBank = $("#CollectionAdvanceDepositBank").val();
    var PayDates = $("#CollectionAdvancePayDates").val();
       
    if(FinanceYear=='')
    {
        alert("Please Select Finance Year");
        $("#FinanceYear").focus();
        return false;
    }
    else if(BranchName=='')
    {
        alert("Please Select Branch");
        $("#FinanceMonth").focus();
        return false;
    }
    else if(CompanyName=='')
    {
        alert("Please Select Company");
        $("#head").focus();
        return false;
    }
    else if(PayNo=='')
    {
        alert("Please Fill Cheque/RTSS No");
        $("#subHead").focus();
        return false;
    }
    else if(PayAmount=='')
    {
        alert("Please Fill Amount");
        $("#vendorId").focus();
        return false;
    }
    else if(PayTypeDates=='')
    {
        alert("Please Select Cheque/RTGS No");
        $("#BillNo").focus();
        return false;
    }
    else if(BankName=='')
    {
        alert("Please Select Bank Name");
        $("#bill_date").focus();
        return false;
    }
    else if(DepositBank=='')
    {
        alert("Please Select Bank");
        $("#Amount").focus();
        return false;
    }
    else if(PayDates=='')
    {
        alert("Select Select Payment Date");
        $("#description").focus();
        return false;
    }
    
    
    $.post("payment_tmp_update",
            {
             PaymentId:PaymentId,
             financial_year:FinanceYear,
             branch_name: BranchName,
             company_name:CompanyName,
             pay_no: PayNo,
             pay_amount:PayAmount,
             pay_type_dates:PayTypeDates,
             bank_name:BankName,
             deposit_bank:DepositBank,
             pays_date:PayDates,
            },
            function(data,status){
               if(data==1)
               {
                   alert("Record Has been Saved.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Saved");
               }
            });
      
    
    return false;
    
}

    function upd_particular()
    {
        var collection_id=$("#UpdateId").val();
        var FinanceYear=$("#CollectionAdvanceFinancialYear").val();
        var BranchName=$("#CollectionAdvanceBranchName").val();
        var CompanyName=$("#CollectionAdvanceCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionAdvancePayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
            var pay_types 		= 	document.getElementsByName('type');
        var PayAmount = $("#CollectionAdvancePayAmount").val();
        var PayTypeDates = $("#CollectionAdvancePayTypeDates").val();
        var BankName = $("#CollectionAdvanceBankName").val();
        var DepositBank = $("#CollectionAdvanceDepositBank").val();
        var PayDates = $("#CollectionAdvancePayDates").val();
        
        var bill_no = $("#CollectionAdvanceBillNo").val();
        var bill_amount = $("#CollectionAdvanceAmount").val();
        var status =    $("#CollectionAdvanceStatus").val();
        var bill_passed = $("#CollectionAdvanceBillPassed").val();
        var tds_ded = $("#CollectionAdvanceTdsDed").val();
        var net_amount = $("#CollectionAdvanceNetAmt").val();
        var deduction = $("#CollectionAdvanceDeduction").val();
        var remarks = $("#CollectionAdvanceRemarks").val();
        
        
       
        if(FinanceYear=='')
        {
            alert("Please Select Finance Year");
            $("#FinanceYear").focus();
            return false;
        }
        else if(BranchName=='')
        {
            alert("Please Select Branch");
            $("#FinanceMonth").focus();
            return false;
        }
        else if(CompanyName=='')
        {
            alert("Please Select Company");
            $("#head").focus();
            return false;
        }
        else if(PayNo=='')
        {
            alert("Please Fill Cheque/RTSS No");
            $("#subHead").focus();
            return false;
        }
        else if(PayAmount=='')
        {
            alert("Please Fill Amount");
            $("#vendorId").focus();
            return false;
        }
        else if(PayTypeDates=='')
        {
            alert("Please Select Cheque/RTGS No");
            $("#BillNo").focus();
            return false;
        }
        else if(BankName=='')
        {
            alert("Please Select Bank Name");
            $("#bill_date").focus();
            return false;
        }
        else if(DepositBank=='')
        {
            alert("Please Select Bank");
            $("#Amount").focus();
            return false;
        }
        else if(PayDates=='')
        {
            alert("Select Select Payment Date");
            $("#description").focus();
            return false;
        }
        else if(bill_no=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceBillNo").focus();
            return false;
        }
        else if(bill_amount=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceAmount").focus();
            return false;
        }
        else if(status=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceStatus").focus();
            return false;
        }
        else if(bill_passed=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceBillPassed").focus();
            return false;
        }
        else if(tds_ded=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceTdsDed").focus();
            return false;
        }
        else if(net_amount=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceNetAmt").focus();
            return false;
        }
        else if(deduction=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAdvanceRemarks").focus();
            return false;
        }
    
    $.post("payment_update_part",
            {
                collection_id:collection_id,
                financial_year:FinanceYear,
                branch_name: BranchName,
                company_name:CompanyName,
                pay_no: PayNo,
                bank_name:BankName,
                pay_amount:PayAmount,
                pay_type_dates:PayTypeDates,
                deposit_bank:DepositBank,
                pay_dates:PayDates,
                bill_no:bill_no,
                bill_amount:bill_amount,
                status:status,
                bill_passed:bill_passed,
                tds_ded:tds_ded,
                net_amount:net_amount,
                deduction:deduction,
                pay_type:type,
                remarks:remarks
            },
            function(data,status1)
            {
               if(data==1)
               {
                   alert("Record Has been Saved.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Saved");
               }
            });
      
    
    return false;
    }
function delete_upd_particular(val)
{
    $.post("delete_upd_particular",
            {
                Id:val
                
            },
            function(data,status)
            {
               if(data==1)
               {
                   alert("Record Has been Deleted.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Deleted");
               }
            });
            return false;
}

function delete_upd_other_deduction(val)
{
    $.post("delete_upd_other_deduction",
            {
                Id:val
                
            },
            function(data,status)
            {
               if(data==1)
               {
                   alert("Record Has been Deleted.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Deleted");
               }
            });
            return false;
}

function Other_Deduction_Upd()
{
        var collection_id=$("#UpdateId").val();
        var FinanceYear=$("#CollectionAdvanceFinancialYear").val();
        var BranchName=$("#CollectionAdvanceBranchName").val();
        var CompanyName=$("#CollectionAdvanceCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionAdvancePayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
        
        var PayAmount = $("#CollectionAdvancePayAmount").val();
        var PayTypeDates = $("#CollectionAdvancePayTypeDates").val();
        var BankName = $("#CollectionAdvanceBankName").val();
        var DepositBank = $("#CollectionAdvanceDepositBank").val();
        var PayDates = $("#CollectionAdvancePayDates").val();
        var deduction = $("#CollectionAdvanceOtherDeduction").val();
        var remarks = $("#CollectionAdvanceOtherRemarks").val();
        
        
       
        if(FinanceYear=='')
        {
            alert("Please Select Finance Year");
            $("#FinanceYear").focus();
            return false;
        }
        else if(BranchName=='')
        {
            alert("Please Select Branch");
            $("#FinanceMonth").focus();
            return false;
        }
        else if(CompanyName=='')
        {
            alert("Please Select Company");
            $("#head").focus();
            return false;
        }
        else if(PayNo=='')
        {
            alert("Please Fill Cheque/RTSS No");
            $("#subHead").focus();
            return false;
        }
        else if(PayAmount=='')
        {
            alert("Please Fill Amount");
            $("#vendorId").focus();
            return false;
        }
        else if(PayTypeDates=='')
        {
            alert("Please Select Cheque/RTGS No");
            $("#BillNo").focus();
            return false;
        }
        else if(BankName=='')
        {
            alert("Please Select Bank Name");
            $("#bill_date").focus();
            return false;
        }
        else if(DepositBank=='')
        {
            alert("Please Select Bank");
            $("#Amount").focus();
            return false;
        }
        else if(PayDates=='')
        {
            alert("Select Select Payment Date");
            $("#description").focus();
            return false;
        }
        
        else if(deduction=='')
        {
            alert("Please Fill Amount");
            $("#CollectionAdvanceDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Remarks");
            $("#CollectionAdvanceRemarks").focus();
            return false;
        }
    
    $.post("Other_Deduction_Upd",
            {
                collection_id:collection_id,
                financial_year:FinanceYear,
                branch_name: BranchName,
                company_name:CompanyName,
                pay_no: PayNo,
                bank_name:BankName,
                pay_amount:PayAmount,
                pay_type_dates:PayTypeDates,
                deposit_bank:DepositBank,
                pays_date:PayDates,
                pay_type:type,
                other_remarks:remarks,
                other_deduction:deduction
            },
            function(data,status)
            {
               if(data==1)
               {
                   alert("Record Has been Saved.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Saved");
               }
            });
      
    
    return false;
}

function delete_upd_other_bill_deduction(val)
{
    $.post("delete_upd_bill_other_deduction",
            {
                Id:val
                
            },
            function(data,status)
            {
               if(data==1)
               {
                   alert("Record Has been Deleted.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Deleted");
               }
            });
            return false;
}

function Other_Bill_Deduction_Upd()
{
        var collection_id=$("#UpdateId").val();
        var FinanceYear=$("#CollectionAdvanceFinancialYear").val();
        var BranchName=$("#CollectionAdvanceBranchName").val();
        var CompanyName=$("#CollectionAdvanceCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionAdvancePayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
        
        var PayAmount = $("#CollectionAdvancePayAmount").val();
        var PayTypeDates = $("#CollectionAdvancePayTypeDates").val();
        var BankName = $("#CollectionAdvanceBankName").val();
        var DepositBank = $("#CollectionAdvanceDepositBank").val();
        var PayDates = $("#CollectionAdvancePayDates").val();
        var deduction = $("#CollectionAdvanceBillOtherDeduction").val();
        var remarks = $("#CollectionAdvanceBillOtherRemarks").val();
        var bill_no = $("#CollectionAdvanceBillNoOther").val();
        
       
        if(FinanceYear=='')
        {
            alert("Please Select Finance Year");
            $("#FinanceYear").focus();
            return false;
        }
        else if(BranchName=='')
        {
            alert("Please Select Branch");
            $("#FinanceMonth").focus();
            return false;
        }
        else if(CompanyName=='')
        {
            alert("Please Select Company");
            $("#head").focus();
            return false;
        }
        else if(PayNo=='')
        {
            alert("Please Fill Cheque/RTSS No");
            $("#subHead").focus();
            return false;
        }
        else if(PayAmount=='')
        {
            alert("Please Fill Amount");
            $("#vendorId").focus();
            return false;
        }
        else if(PayTypeDates=='')
        {
            alert("Please Select Cheque/RTGS No");
            $("#BillNo").focus();
            return false;
        }
        else if(BankName=='')
        {
            alert("Please Select Bank Name");
            $("#bill_date").focus();
            return false;
        }
        else if(DepositBank=='')
        {
            alert("Please Select Bank");
            $("#Amount").focus();
            return false;
        }
        else if(PayDates=='')
        {
            alert("Select Select Payment Date");
            $("#description").focus();
            return false;
        }
        
        else if(deduction=='')
        {
            alert("Please Fill Amount");
            $("#CollectionAdvanceDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Remarks");
            $("#CollectionAdvanceRemarks").focus();
            return false;
        }
    
    $.post("Other_Bill_Deduction_Upd",
            {
                collection_id:collection_id,
                financial_year:FinanceYear,
                branch_name: BranchName,
                company_name:CompanyName,
                pay_no: PayNo,
                bank_name:BankName,
                pay_amount:PayAmount,
                pay_type_dates:PayTypeDates,
                deposit_bank:DepositBank,
                pays_date:PayDates,
                pay_type:type,
                bill_no:bill_no,
                other_remarks:remarks,
                other_deduction:deduction
            },
            function(data,status)
            {
               if(data==1)
               {
                   alert("Record Has been Saved.");
                   location.reload();
               }
               else
               {
                   alert("Record Not Saved");
               }
            });
      
    
    return false;
}
</script>        