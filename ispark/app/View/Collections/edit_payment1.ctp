<script>
    function upd_collection()
    {
        var company_name,branch_name,financial_year,bill_no,pay_type,pay_no,bank_name,deposit_bank,pay_type_dates,
                pay_dates,bill_amt,status,bill_passed,tds_ded,net_amt,deduction,remarks,PaymentId;
        
        company_name = $('CollectionCompanyName').val();
        branch_name = $('CollectionBranchName').val();
        financial_year = $('CollectionFinancialYear').val();
        pay_type = $('CollectionPayNo').val();
        pay_no = $('CollectionPayAmount').val();
        pay_type_dates = $('CollectionPayTypeDates').val();
        bank_name = $('CollectionBankName').val();
        deposit_bank = $('CollectionDepositBank').val();
        pay_dates = $('CollectionPayDates').val();
        
        bill_no = $('CollectionBillNo').val();
        bill_amt = $('CollectionAmount').val();
        status = $('CollectionStatus').val();
        bill_passed = $('CollectionBillPassed').val();
        tds_ded = $('CollectionTdsDed').val();
        net_amt = $('CollectionNetAmt').val();
        deduction = $('CollectionDeduction').val();
        remarks = $('CollectionRemarks').val();
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
        var branch_name = 	document.getElementById('CollectionBranchName').value;
        var finance_year = 	document.getElementById('CollectionFinancialYear').value;
        var company_name = 	document.getElementById('CollectionCompanyName').value;
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
                        document.getElementById('CollectionAmount').value = '';
                }
                else if(parseInt(amount) == 1)
                {
                        alert("Bill No. Already Added");
                }
                else
                {
                    document.getElementById('CollectionAmount').value = amount;
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
<?php echo $this->Form->create('Collection',array('class'=>'form-horizontal','action'=>'update_payment','enctype'=>'multipart/form-data')); ?>
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
                                <span>Collection</span>
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
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value = "Cash" id = 'type' onClick="return collection_validate(this.value)" >Cash
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
                                                                <div class="radio-inline">
                                                                        <label>
                                                                                <input type="radio" name="type" value = "Cash" id = 'type' onClick="collection_validate(this.value)" <?php if($payment_master['4'] == 'Cash') {echo "checked"; $pay_type = 'Cash';} else {echo "disabled";} ?>>Cash
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

                                                    <?php	echo $this->Form->input('pay_type_dates', array('label'=>false,'class'=>'form-control','type'=>'text','value' => $date,'required'=>true,'readonly'=>$flag1, 'onClick'=>"displayDatePicker('data[Collection][pay_type_dates]');")); ?>
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
                                                <?php	echo $this->Form->input('pay_dates', array('label'=>false,'class'=>'form-control','onClick'=>"displayDatePicker('data[Collection][pay_dates]');",'value' => $date,'placeholder' => 'Select Date','required'=>true,'readonly'=>$flag1)); ?>
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
            <h4 class="page-header">Collection</h4>
            <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
                <tr>
                        <th>Sr. No.</th>
                        <th>Bill No.</th>
                        <th>Bill Amt</th>
                        <th>Status</th>
                        <th>Bill Passed</th>
                        <th>TDS Ded</th>
                        <th>Net Amt</th>
                        <th>Deduction</th>
                        <th>Remarks</th>
                        <th>Action</th>
                </tr>

                <tr>
                        <th>1.</th>
                        <th><?php echo $this->Form->input('bill_no' ,array('label' =>false,'placeholder' => 'Bill No','class' => 'form-control','onBlur'=>"get_bill_amount1(this.value);")); ?></th>
                        <th><?php echo $this->Form->input('amount' ,array('label' =>false,'placeholder' => 'Bill Amount','onBlur'=>'','class' => 'form-control','readOnly'=>true)); ?></th>
                        <th><?php echo $this->Form->input('status' ,array('label' =>false,'options'=>array('paid'=>'Paid','part payment'=>'Part Payment'),'empty' => 'status','class' => 'form-control','onChange'=>'validate_collection_data();')); ?></th>
                        <th><?php echo $this->Form->input('bill_passed' ,array('label' =>false,'placeholder' => 'Bill Passed','class' => 'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_collection_data();')); ?></th>
                        <th><?php echo $this->Form->input('tds_ded' ,array('label' =>false,'placeholder' => 'TDS Ded','class' => 'form-control','onBlur'=>'validate_collection_data();','onkeypress'=>'return isNumberKey(event)','onBlur'=>'get_netAmount();')); ?></th>
                        <th><?php echo $this->Form->input('net_amt' ,array('label' =>false,'placeholder' => 'Net Amount','class' => 'form-control','onBlur'=>'validate_collection_data();','onkeypress'=>'return isNumberKey(event)','onBlur'=>'get_tds()')); ?></th>
                        <th><?php echo $this->Form->input('deduction' ,array('label' =>false,'placeholder' => 'Deduction','class' => 'form-control','onChange'=>'validate_collection_data();','readOnly'=>true)); ?></th>
                        <th><?php echo $this->Form->input('remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control','onClick'=>'get_bill_remark();','onBlur'=>'validate_collection_data();')); ?></th>
                        <th><div onclick="return upd_particular()"> ADD</div></th>
                </tr>
        </table> 
        <div id="oo">
<table>
<?php  $i = 0; $idx ="";$net=0;
foreach ($result as $post): ?>
        <?php $idx.=$post['CollectionParticularsUpdate']['PaymentId'].','; ?>
        <tr <?php   $i++;?>>
        <td><?php echo $i;?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.bill_no',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_no'],'class'=>'form-control','required'=>true)); ?></td>

        <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.bill_amount',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_amount'],'readOnly'=>true,'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();','onkeypress'=>'return isNumberKey(event)')); ?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.status',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['status'],'class'=>'form-control','required'=>true,'readOnly'=>'true','onBlur'=>'validate_colleciton_amount();')); ?></td>

        <td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.bill_passed',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['bill_passed'],'class'=>'form-control','onBlur'=>'getAmount1(this.id)','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.tds_ded',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['tds_ded'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.net_amount',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['net_amount'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.deduction',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['deduction'],'class'=>'form-control','required'=>true,'readOnly'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

<td><?php echo $this->Form->input('CollectionParticularsUpdate.'.$post['CollectionParticularsUpdate']['PaymentId'].'.remarks',array('label'=>false,'value'=>$post['CollectionParticularsUpdate']['remarks'],'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

        <td> <button name = "Delete" class="btn btn-primary" value="<?php echo $post['CollectionParticularsUpdate']['PaymentId']; ?>" onClick ="return delete_upd_particular(this.value)">Delete</button> </td>
        </tr>
<?php $net+=$post['CollectionParticularsUpdate']['net_amount'];
endforeach; ?><?php unset($TMPCollectionParticulars); ?>
<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
</table>                        
                        </div>                
				</div>
			</div>
		</div>
	</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-content">
                            <h4 class="page-header">Other Deduction Bill Wise</h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr>
						<th>Sr. No.</th>
                                                <th>Bill No</th>
						<th>Other Deductions</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
					
					<tr>
						<th>1.</th>
                                                <th><?php echo $this->Form->input('bill_no_other' ,array('label' =>false,'placeholder' => 'Bill No','class' => 'form-control')); ?></th>
						<th><?php echo $this->Form->input('bill_other_deduction' ,array('label' =>false,'placeholder' => 'Fill Other Deduction','class' => 'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'bill_other_deduct_validate();')); ?></th>
						<th><?php echo $this->Form->input('bill_other_remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control')); ?></th>
						<th><div onclick="return Other_Bill_Deduction_Upd()"> ADD</div></th>
					</tr>
				</table> 
                		<div id="bqq">
<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
						<?php  $i = 0; $idx1 ="";$billdeduct =0; 
						foreach ($result3 as $post): ?>
							<?php $idx1.=$post['OtherBillDeductionUpdate']['PaymentId'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('OtherBillDeductionUpdate.'.$post['OtherBillDeductionUpdate']['PaymentId'].'.bill_no',array('label'=>false,'value'=>$post['OtherBillDeductionUpdate']['bill_no'],'class'=>'form-control')); ?></td>
                                                        <td><?php echo $this->Form->input('OtherBillDeductionUpdate.'.$post['OtherBillDeductionUpdate']['PaymentId'].'.other_deduction',array('label'=>false,'value'=>$post['OtherBillDeductionUpdate']['other_deduction'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
                            
							<td><?php echo $this->Form->input('OtherBillDeductionUpdate.'.$post['OtherBillDeductionUpdate']['PaymentId'].'.other_remarks',array('label'=>false,'value'=>$post['OtherBillDeductionUpdate']['other_remarks'],'class'=>'form-control')); ?></td>
                            
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['OtherBillDeductionUpdate']['PaymentId']; ?>" onClick ="return delete_upd_other_bill_deduction(this.value)">Delete</button> </td>
							</tr>
						<?php 
						$billdeduct += $post['OtherBillDeductionUpdate']['other_deduction'];
                                                $net_bill_deduct += $post['OtherBillDeductionUpdate']['other_deduction'];
						endforeach; ?><?php unset($OtherBillDeductionUpdate); ?>
						<?php echo $this->Form->input('a.idx3',array('label'=>false,'value'=>$idx1,'type'=>'hidden','id'=>'idx3')); ?>
</table>                        
                        </div>
				<h4 class="page-header">Other Deduction</h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr>
						<th>Sr. No.</th>
						<th>Other Deductions</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
					
					<tr>
						<th>1.</th>
						<th><?php echo $this->Form->input('other_deduction' ,array('label' =>false,'placeholder' => 'Fill Other Deduction','class' => 'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'other_deduct_validate();')); ?></th>
						<th><?php echo $this->Form->input('other_remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control')); ?></th>
						<th><button onclick="return Other_Deduction_Upd()"> ADD</button></th>
					</tr>
				</table> 
                		<div id="qq">
<table>
						<?php  $i = 0; $idx ="";$deduct =0;
						foreach ($result2 as $post): ?>
							<?php $idx.=$post['OtherDeductionUpdate']['PaymentId'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('OtherDeductionUpdate.'.$post['OtherDeductionUpdate']['PaymentId'].'.other_deduction',array('label'=>false,'value'=>$post['OtherDeductionUpdate']['other_deduction'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>                            
                            <td><?php echo $this->Form->input('OtherDeductionUpdate.'.$post['OtherDeductionUpdate']['PaymentId'].'.other_remarks',array('label'=>false,'value'=>$post['OtherDeductionUpdate']['other_remarks'],'class'=>'form-control')); ?></td>
                            
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['OtherDeductionUpdate']['PaymentId']; ?>" onClick ="return delete_upd_other_deduction(this.value)">Delete</button> </td>
							</tr>
						<?php 
						$deduct += $post['OtherDeductionUpdate']['other_deduction'];
						endforeach; ?><?php unset($OtherTMPDeduction); ?>
						<?php echo $this->Form->input('a.idx2',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx2')); ?>
</table>                        
                                </div>
                        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        	<tr><th>Net Amount</th><td><?=($net)?></td></tr>
                            <tr><th>Cheque/RTGS</th><td><?=$payment_master['8']?></tr>
                            <tr><th>Deduction</th><td><?=$deduct?></tr>
                            <tr><th>Bill Other Deduction</th><td><?=$net_bill_deduct?></tr>
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
    
<script>
    function update_collection()
    {
    var PaymentId=$("#UpdateId").val();
    var FinanceYear=$("#CollectionFinancialYear").val();
    var BranchName=$("#CollectionBranchName").val();
    var CompanyName=$("#CollectionCompanyName").val();
    //var type=$("#type").val();
    var PayNo = $("#CollectionPayNo").val();
    
    var PayAmount = $("#CollectionPayAmount").val();
    var PayTypeDates = $("#CollectionPayTypeDates").val();
    var BankName = $("#CollectionBankName").val();
    var DepositBank = $("#CollectionDepositBank").val();
    var PayDates = $("#CollectionPayDates").val();
       
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
        var FinanceYear=$("#CollectionFinancialYear").val();
        var BranchName=$("#CollectionBranchName").val();
        var CompanyName=$("#CollectionCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionPayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
            var pay_types 		= 	document.getElementsByName('type');
        var PayAmount = $("#CollectionPayAmount").val();
        var PayTypeDates = $("#CollectionPayTypeDates").val();
        var BankName = $("#CollectionBankName").val();
        var DepositBank = $("#CollectionDepositBank").val();
        var PayDates = $("#CollectionPayDates").val();
        
        var bill_no = $("#CollectionBillNo").val();
        var bill_amount = $("#CollectionAmount").val();
        var status =    $("#CollectionStatus").val();
        var bill_passed = $("#CollectionBillPassed").val();
        var tds_ded = $("#CollectionTdsDed").val();
        var net_amount = $("#CollectionNetAmt").val();
        var deduction = $("#CollectionDeduction").val();
        var remarks = $("#CollectionRemarks").val();
        
        
       
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
            $("#CollectionBillNo").focus();
            return false;
        }
        else if(bill_amount=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionAmount").focus();
            return false;
        }
        else if(status=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionStatus").focus();
            return false;
        }
        else if(bill_passed=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionBillPassed").focus();
            return false;
        }
        else if(tds_ded=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionTdsDed").focus();
            return false;
        }
        else if(net_amount=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionNetAmt").focus();
            return false;
        }
        else if(deduction=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Bill No");
            $("#CollectionRemarks").focus();
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
        var FinanceYear=$("#CollectionFinancialYear").val();
        var BranchName=$("#CollectionBranchName").val();
        var CompanyName=$("#CollectionCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionPayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
        
        var PayAmount = $("#CollectionPayAmount").val();
        var PayTypeDates = $("#CollectionPayTypeDates").val();
        var BankName = $("#CollectionBankName").val();
        var DepositBank = $("#CollectionDepositBank").val();
        var PayDates = $("#CollectionPayDates").val();
        var deduction = $("#CollectionOtherDeduction").val();
        var remarks = $("#CollectionOtherRemarks").val();
        
        
       
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
            $("#CollectionDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Remarks");
            $("#CollectionRemarks").focus();
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
        var FinanceYear=$("#CollectionFinancialYear").val();
        var BranchName=$("#CollectionBranchName").val();
        var CompanyName=$("#CollectionCompanyName").val();
        var pay_types 		= 	document.getElementsByName('type');
        var type = '';
        var PayNo = $("#CollectionPayNo").val();
        for(var i = 0; i < pay_types.length; i++)
	{
    		if(pay_types[i].checked)
		{
                    type = pay_types.value;
    		}
	}
        
        
        
        var PayAmount = $("#CollectionPayAmount").val();
        var PayTypeDates = $("#CollectionPayTypeDates").val();
        var BankName = $("#CollectionBankName").val();
        var DepositBank = $("#CollectionDepositBank").val();
        var PayDates = $("#CollectionPayDates").val();
        var deduction = $("#CollectionBillOtherDeduction").val();
        var remarks = $("#CollectionBillOtherRemarks").val();
        var bill_no = $("#CollectionBillNoOther").val();
        
       
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
            $("#CollectionDeduction").focus();
            return false;
        }
        else if(remarks=='')
        {
            alert("Please Fill Remarks");
            $("#CollectionRemarks").focus();
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