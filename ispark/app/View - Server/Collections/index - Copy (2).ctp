<?php //print_r($payment_master); 
$pay_type = 'Cheque'; 
$flag1 =false;

if($payment_master['0']!='') {$flag1 =true;}

?>
<?php echo $this->Form->create('Collection',array('class'=>'form-horizontal','action'=>'add')); ?>
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
					<h4 class="page-header">
					<?php echo $this->Session->flash(); ?>
					</h4>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Select Company</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('company_name', array('options' => array('1'=>'Mas Call Net'),'label' => false, 'div' => false,'class'=>'form-control','selected' => $payment_master['1'],'onChange'=>'get_costcenter5(this)')); ?>
						</div>
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-2"><div id="mm">
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
						<div class="col-sm-2">
						
							<?php echo $this->Form->input('financial_year', array('options' => array('14-15'=>'2014-15'),'empty' => 'Select Year','label' => false, 'div' => false,'class'=>'form-control','selected' => $payment_master['2'])); ?>
						</div>
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
						<div class="col-sm-2"> <?php $flag = false; if($pay_type == 'RTGS') {$flag =true;}?>
							<?php	echo $this->Form->input('pay_no', array('label'=>false,'class'=>'form-control','value' => $payment_master['5'],'placeholder' => 'Cheque Number','required'=>true,'readonly' => $flag1)); ?>
						</div>
						

						<label class="col-sm-2 control-label"><?=$pay_type?> Amount</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('pay_amount', array('label'=>false,'class'=>'form-control','value' => $payment_master['8'],'placeholder' => 'Amount','required'=>true,'readonly'=>$flag1)); ?>
						</div>
                        </div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Payment Bank</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('bank_name', array('label'=>false,'class'=>'form-control','value' => $payment_master['6'],'placeholder'=>'Select Bank','required'=>true,'readonly'=>$flag1)); ?>
						</div>

						<label class="col-sm-2 control-label">Deposit Bank</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('deposit_bank', array('label'=>false,'class'=>'form-control','value' => $payment_master['9'],'placeholder'=>'Select Bank', 'required'=>true,'readonly'=>$flag1)); ?>
						</div>
					</div>

					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Payment Date</label>
						<div class="col-sm-2">
							<?php  
							$date = date_create($payment_master['7']);
							$date = date_format($date,'d-m-Y');
							?>	
							<?php	echo $this->Form->input('pay_dates', array('label'=>false,'class'=>'form-control','onClick'=>"displayDatePicker('data[Collection][pay_dates]');",'value' => $date,'placeholder' => 'Select Date','required'=>true,'readonly'=>$flag1)); ?>
						</div>
						<label class="col-sm-2 control-label">No Of Bills</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('no_of_bills', array('label'=>false,'class'=>'form-control','value' => $payment_master['10'],'placeholder' => 'No Of Bills','required'=>true,'readonly'=>$flag1)); ?>
						</div>
					</div>
                    
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">&nbsp;</label>
						<div class="col-sm-2">
							<button onclick="return save_collection()" class="btn btn-success btn-label-left">Save</button> &nbsp; &nbsp; &nbsp;	
                            <?php echo $this->Html->link('Back',array('action'=>'back'),array('class'=>'btn btn-danger')); ?>
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
						<th>Bill Passed</th>
						<th>TDS Ded</th>
						<th>Net Amt</th>
						<th>Deduction</th>
						<th>Status</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
					
					<tr>
						<th>1.</th>
						<th><?php echo $this->Form->input('bill_no' ,array('label' =>false,'placeholder' => 'Bill No','class' => 'form-control','onBlur'=>"get_bill_amount(this.value);")); ?></th>
						<th><?php echo $this->Form->input('amount' ,array('label' =>false,'placeholder' => 'Bill Amount','onBlur'=>'validate_collection_data();','class' => 'form-control','readOnly'=>true)); ?></th>
						<th><?php echo $this->Form->input('bill_passed' ,array('label' =>false,'placeholder' => 'Bill Passed','class' => 'form-control','onBlur'=>'validate_collection_data();')); ?></th>
						<th><?php echo $this->Form->input('tds_ded' ,array('label' =>false,'placeholder' => 'TDS Ded','class' => 'form-control','onBlur'=>'validate_collection_data();','onBlur'=>'get_netAmount();')); ?></th>
						<th><?php echo $this->Form->input('net_amt' ,array('label' =>false,'placeholder' => 'Net Amount','class' => 'form-control','onBlur'=>'validate_collection_data();','onBlur'=>'get_tds()')); ?></th>
						<th><?php echo $this->Form->input('deduction' ,array('label' =>false,'placeholder' => 'Deduction','class' => 'form-control','onChange'=>'validate_collection_data();','readOnly'=>true)); ?></th>
						<th><?php echo $this->Form->input('status' ,array('label' =>false,'options'=>array('paid'=>'paid','unpaid'=>'unpaid'),'empty' => 'status','class' => 'form-control','onChange'=>'validate_collection_data();')); ?></th>
						<th><?php echo $this->Form->input('remarks' ,array('label' =>false,'placeholder' => 'Remarks','class' => 'form-control','onBlur'=>'validate_collection_data();')); ?></th>
						<th><button onclick="return add_collection()"> ADD</button></th>
					</tr>
				</table> 
                		<div id="oo">
<table>
						<?php  $i = 0; $idx ="";
						foreach ($result as $post): ?>
							<?php $idx.=$post['TMPCollectionParticulars']['id'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_no',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_no'],'class'=>'form-control','required'=>true)); ?></td>
                            
							<td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_amount',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_amount'],'readOnly'=>true,'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();','onkeypress'=>'return isNumberKey(event)')); ?></td>
                            
							<td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.bill_passed',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['bill_passed'],'class'=>'form-control','onBlur'=>'getAmount1(this.id)','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
							
                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.tds_ded',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['tds_ded'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.net_amount',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['net_amount'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.deduction',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['deduction'],'class'=>'form-control','required'=>true,'onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>

                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.status',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['status'],'class'=>'form-control','required'=>true,'readOnly'=>'true','onBlur'=>'validate_colleciton_amount();')); ?></td>
                            
                            <td><?php echo $this->Form->input('TMPCollectionParticulars.'.$post['TMPCollectionParticulars']['id'].'.remarks',array('label'=>false,'value'=>$post['TMPCollectionParticulars']['remarks'],'class'=>'form-control','required'=>true,'onBlur'=>'validate_colleciton_amount();')); ?></td>

							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['TMPCollectionParticulars']['id']; ?>" onClick ="return deletesCollection(this.value)">Delete</button> </td>
							</tr>
						<?php endforeach; ?><?php unset($TMPCollectionParticulars); ?>
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
						<th><button onclick="return add_other_collection()"> ADD</button></th>
					</tr>
				</table> 
                		<div id="qq">
<table>
						<?php  $i = 0; $idx ="";
						foreach ($result2 as $post): ?>
							<?php $idx.=$post['OtherTMPDeduction']['id'].','; ?>
							<tr <?php   $i++;?>>
							<td><?php echo $i;?></td>
							
                            <td><?php echo $this->Form->input('OtherTMPDeduction.'.$post['OtherTMPDeduction']['id'].'.other_deduction',array('label'=>false,'value'=>$post['OtherTMPDeduction']['other_deduction'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)','onBlur'=>'validate_colleciton_amount();')); ?></td>
                            
							<td><?php echo $this->Form->input('OtherTMPDeduction.'.$post['OtherTMPDeduction']['id'].'.other_remarks',array('label'=>false,'value'=>$post['OtherTMPDeduction']['other_remarks'],'class'=>'form-control')); ?></td>
                            
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['OtherTMPDeduction']['id']; ?>" onClick ="return deletesDeduction(this.value)">Delete</button> </td>
							</tr>
						<?php endforeach; ?><?php unset($OtherTMPDeduction); ?>
						<?php echo $this->Form->input('a.idx2',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx2')); ?>
</table>                        
                        </div> 
          				<button onclick="return validate_save_collection();" class="btn btn-success btn-label-left">Submit </button>              
                        
				</div>
			</div>
		</div>
	</div>