
<?php echo $this->Form->create('Taxes',array('class'=>'form-horizontal','action'=>'billApproval')); ?>
						<?php  foreach ($cost_master as $post): ?>
						<?php $data=$post; ?>
						<?php endforeach; ?><?php //unset($CostCenterMaster); ?>
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
					<span>Invoice Entry</span>
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
					<!--	creating hide array for particulars table and hidden fields -->
					<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
							<td>Branch</td>
							<td>Cost Center</td>
							<td>Financial Year</td>
							<td>Month for</td>
						</tr>
						<tr align="center">
							<td class="info"><?php echo $data['branch']; $hide['branch_name']=$data['branch']; ?></td>
							<td class="danger"><?php echo $dataY['cost_center'];
						 $hide['cost_center']=$dataY['cost_center']; ?></td>
							<td class="info"><?php echo $dataY['finance_year'];
						 		$hide['finance_year']=$dataY['finance_year'];?>
							</td>
							<td class="danger"><?php echo $dataY['month'];
						 		$hide['month_for']=$dataY['month'];?>
							</td>
						</tr>
						<?php
							 $hide['cost_center_id']=$data['id'];
							 $date = $dataY['invoiceDate'];
							 $date=date_create($date);
							 $date=date_format($date,"Y-m-d");
							 $hide['invoiceDate']=$date;
							 $hide['app_tax_cal']=$dataY['app_tax_cal'];
							 $hide['invoiceDescription']=$dataY['invoiceDescription'];
						 ?>	
					</table>
					
				</div>
			</div>
		</div>
	</div>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Bill To</span>
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
			<div class="box-content no-padding">
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
						<tbody>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['bill_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['b_Address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['b_Address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['b_Address3'];?></th></tr>


						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['b_Address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['b_Address5'];?></th></tr>
						</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Ship To</span>
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
			<div class="box-content no-padding">
				
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['ship_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['a_address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['a_address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['a_address3'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['a_address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $data['a_address5'];?></th></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Invoice Entry</span>
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
				<h4 class="page-header">Date 
				<?php 
						$date=date_create($hide['invoiceDate']);
						echo date_format($date,"d-M-Y"); 
				?></h4>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-1 control-label">PO No.:</label>
						<div class="col-sm-2">
							<?php 
								$flag=false;
								if($data['po_required']=='Yes')
								 $flag=true;
							?>

							<?php	echo $this->Form->input('Taxes.po_no', array('label'=>false,'class'=>'form-control','required'=>$flag)); ?>
						</div>

						<label class="col-sm-1 control-label">JCC No.:</label>
						<div class="col-sm-2">
							<?php 
								$flag=false;
								if($data['jcc_no']=='Yes')
								 $flag=true;
							?>
							<?php	echo $this->Form->input('Taxes.jcc_no', array('label'=>false,'class'=>'form-control','required'=>$flag)); ?>
						</div>

						<label class="col-sm-1 control-label">GRN</label>
						<div class="col-sm-2">
							<?php 
								$flag=false;
								if($data['grn']=='Yes')
								 $flag=true;
							?>
							<?php	echo $this->Form->input('Taxes.grn', array('label'=>false,'class'=>'form-control','required'=>$flag,'readonly'=>true)); ?>
						</div>


						<label class="col-sm-1 control-label">Bill No.:</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('Taxes.bill_no', array('label'=>false,'class'=>'form-control','readonly'=>true)); ?>
						</div>

					</div>
				</div>
		   </div>
	</div>
</div>
			
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span  style="color:#FF0000">Add Particular</span>
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
			<div class="box-content no-padding">
			<?php // echo $this->Form->create('Add',array('url'=>array('controller'=>'AddInvParticulars','action'=>'index'))); ?>
				<table class="table table-striped">
						<thead>
							<tr>
								<th>SNo</th>
								<th>Particulars</th>
								<th>Qty</th>
								<th>Rate</th>
								<th>Amount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td><?php	echo $this->Form->input('AddInvParticular.particulars',
								 array('label'=>false,'class'=>'form-control','placeholder'=>'Particulars','id'=>'particulars')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.qty', array('label'=>false,'class'=>'form-control','placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.rate', array('label'=>false,'class'=>'form-control','placeholder'=>'Rate','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.amount', array('label'=>false,'class'=>'form-control','value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
								<td><?php echo $this->Js->submit('ADD', array('url' => array('controller' => 'AddInvParticulars','action' => 'index'
								),'update' => '#mm','complete' => 'getBlank()','class'=>'btn btn-success btn-label-left','data'=>'#particulars')); ?></td>
							</tr>
						</tbody>
				
				
				<?php //$this->Js->get('#performAjaxLink');
				//$this->Js->event('click',$this->Js->request(array('controller'=>'AddInvParticulars','action' => 'index'),array('async' => true, 'update' => '#mm')));
				?>
				<div id="xx"><input type='hidden' value='0' id='par_total' name='par_total'></div>
				
						<?php $idx=''; $i = 0; $total = 0;?>
						<?php  foreach ($tmp_particulars as $post): ?>
							<?php $idx.=$post['AddInvParticular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.particulars',array('label'=>false,'value'=>$post['AddInvParticular']['particulars'],'class'=>'form-control')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.qty',array('label'=>false,'value'=>$post['AddInvParticular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.rate',array('label'=>false,'value'=>$post['AddInvParticular']['rate'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.amount',array('label'=>false,'value'=>$post['AddInvParticular']['amount'],'class'=>'form-control','readonly'=>true)); ?></td>
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['AddInvParticular']['id']; ?>" onClick ="return deletes2(this.value)">Delete</button> </td>
							</tr>
							<?php $total += $post['AddInvParticular']['qty']*$post['AddInvParticular']['rate']; ?>
						<?php endforeach; ?><?php unset($AddInvParticular); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
				</table>
				<?php	echo $this->Form->input('AddInvParticular.username', 		array('label'=>false,'value'=>$username,'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.cost_center_id', 	array('label'=>false,'value'=>$hide['cost_center_id'],'type'=>'hidden')); ?>
				
				<?php	echo $this->Form->input('AddInvParticular.branch_name', 	array('label'=>false,'value'=>$hide['branch_name'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.cost_center', 	array('label'=>false,'value'=>$hide['cost_center'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.fin_year',		array('label'=>false,'value'=>$hide['finance_year'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.month_for', 		array('label'=>false,'value'=>$hide['month_for'],'type'=>'hidden')); ?>
				<?php // 	echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span style="color:#FF0000">Add Deduction</span>
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
			<div class="box-content no-padding">
			<?php //echo $this->Form->create('Add',array('url'=>array('controller'=>'AddInvParticulars','action'=>'deduct'))); ?>
				<table class="table table-striped">
						<thead>
							<tr>
								<th>SNo</th>
								<th>Particulars</th>
								<th>Qty</th>
								<th>Rate</th>
								<th>Amount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.particulars', array('label'=>false,'class'=>'form-control','placeholder'=>'Particulars')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.qty', array('label'=>false,'class'=>'form-control','placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.rate', array('label'=>false,'class'=>'form-control','placeholder'=>'Rate','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.amount', array('label'=>false,'class'=>'form-control','value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
								<td><?php 	echo $this->Js->submit('ADD', array('url' => array('controller' => 'AddInvParticulars','action' => 'deduct'),
								'update' => '#nn','complete' => 'getBlank1()','class'=>'btn btn-success btn-label-left')); ?></td>
							</tr>
						</tbody>
						<?php $idxd=''; ?>
						<?php  foreach ($tmp_deduct_particulars as $post): ?>
						<?php $idxd.=$post['AddInvDeductParticular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['AddInvDeductParticular']['id'].'.particulars',array('label'=>false,'value'=>$post['AddInvDeductParticular']['particulars'],'class'=>'form-control'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['AddInvDeductParticular']['id'].'.qty',array('label'=>false,'value'=>$post['AddInvDeductParticular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['AddInvDeductParticular']['id'].'.rate',array('label'=>false,'value'=>$post['AddInvDeductParticular']['rate'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)'));?></td>							
							<td><?php echo $this->Form->input('DeductParticular.'.$post['AddInvDeductParticular']['id'].'.amount',array('label'=>false,'value'=>$post['AddInvDeductParticular']['amount'],'class'=>'form-control','readOnly'=>true));?></td>
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['AddInvDeductParticular']['id']; ?>" onClick ="return deletes3(this.value)">Delete</button> </td>
							</tr>
						<?php 
							$total -= $post['AddInvDeductParticular']['qty']*$post['AddInvDeductParticular']['rate'];
						endforeach; ?>
						<?php unset($AddInvDeductParticular); ?>
						<?php echo $this->Form->input('a.idxd',array('label'=>false,'value'=>$idxd,'type'=>'hidden','id'=>'idxd')); ?>
				</table>
				<div id="yy"><input type='hidden' value='0' id='ded_total' name='ded_total'></div>
				<?php	echo $this->Form->input('AddInvDeductParticular.username', 			array('label'=>false,'value'=>$username,'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvDeductParticular.cost_center_id', 	array('label'=>false,'value'=>$hide['cost_center_id'],'type'=>'hidden')); ?>
				
				<?php	echo $this->Form->input('AddInvDeductParticular.branch_name', 		array('label'=>false,'value'=>$hide['branch_name'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvDeductParticular.cost_center', 		array('label'=>false,'value'=>$hide['cost_center'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvDeductParticular.fin_year',			array('label'=>false,'value'=>$hide['finance_year'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvDeductParticular.month_for', 		array('label'=>false,'value'=>$hide['month_for'],'type'=>'hidden')); ?>
				<?php   //echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					
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
			<div class="box-content no-padding">
				<table class="table table-striped">
						<tr><th>Total:</th><td><?php	echo $this->Form->input('Taxes.total', 			array('label'=>false,'value'=>'0','readonly'=>true)); ?></td></tr>
						<?php $taxex = 1; if($hide['app_tax_cal']=='1'){$taxex = 0.14;  ?>
						<tr><th>Service Tax @ 14.00%</th><td><?php	echo $this->Form->input('Taxes.tax', array('label'=>false,'value'=>round($total*0.14,0),'readonly'=>true)); ?></td></tr>
						<?php if(strtotime($dataY['invoiceDate']) > strtotime("2015-11-14")){ $taxex = 0.145; ?>
						<tr><th>SBC @ 0.5%</th><td><?php	echo $this->Form->input('Taxes.sbctax', array('label'=>false,'value'=>round($total*0.005,0),'readonly'=>true)); ?></td></tr>
						<?php }} ?>
						<tr><th>Grand Total:</th><td><?php	echo $this->Form->input('Taxes.grnd', 	array('label'=>false,'value'=>round($total*$taxex,0), 'readonly'=>true,'required'=>true)); ?></td></tr>
				</table>
				<div class="box-content">
					<div class="col-sm-2"><button type="submit" class="btn btn-success btn-label-left"><b>Submit</b></div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php	echo $this->Form->input('Taxes.branch_name', 	array('label'=>false,'value'=>$hide['branch_name'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.cost_center', 	array('label'=>false,'value'=>$hide['cost_center'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.finance_year',		array('label'=>false,'value'=>$hide['finance_year'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.month', 		array('label'=>false,'value'=>$hide['month_for'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.invoiceDate', 		array('label'=>false,'value'=>$hide['invoiceDate'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.app_tax_cal', 		array('label'=>false,'value'=>$hide['app_tax_cal'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('Taxes.invoiceDescription', 		array('label'=>false,'value'=>$hide['invoiceDescription'],'type'=>'hidden')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
