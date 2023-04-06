<div class="box-content">
				<h4 class="page-header">Add Expense Head</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Expense Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('HeadingDesc',array('label' => false,'class'=>'form-control','placeholder'=>'Expense Head Name According To Tally')); ?>
						</div>
                                                <label class="col-sm-2 control-label">Expense Type</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('Cost',array('label' => false,'options'=>array('D'=>'Direct','I'=>'InDirect'),'empty'=>'Select','class'=>'form-control')); ?>
						</div>
                                                <div class="col-sm-2">
                                                    <button type="submit" name="submit" value="Add" class="btn btn-primary btn-label-left">Submit</button>
                                            </div>
					</div>
					<div class="clearfix"></div>
					
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Expense Head</span>
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
                            <?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=1; ?>
					<thead>
						<tr class="active">
							<td><b>Sr. No.</b></td>
							<td><b>Expense Head</b></td>
                                                        <td><b>Expense Type</b></td>
							<td><b>Ordering</b></td>
                                                        
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($head as $post){ ?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $post['Tbl_bgt_expenseheadingmaster']['HeadingDesc']; ?></td>
                                                        <td><?php echo $this->Form->input('Cost'.$post['Tbl_bgt_expenseheadingmaster']['HeadingId'],array('label' => false,'options'=>array('D'=>'Direct','I'=>'InDirect'),'value'=>$post['Tbl_bgt_expenseheadingmaster']['Cost'],'class'=>'form-control')); ?></td>
                                                        <td><?php echo $this->Form->input('OrderBy'.$post['Tbl_bgt_expenseheadingmaster']['HeadingId'],array('label' => false,'value'=>$post['Tbl_bgt_expenseheadingmaster']['OrderPriority'],'class'=>'form-control','placeholder'=>'Order')); ?></td>
						</tr>
                                                <?php $Arr[] = $post['Tbl_bgt_expenseheadingmaster']['HeadingId']; } ?>
						
					</tbody>
				</table>
                            <div class="form-group form-horizontal">
                                <label class="col-sm-6 control-label"></label>
                                <div class="col-sm-2">
                                    <button type="submit" name="submit" value="Update" class="btn btn-primary btn-label-left">Update</button>
                                </div>
                            </div>
                            <?php echo $this->Form->input('ExpenseHeadArr',array('label' => false,'type'=>'hidden','value'=>implode(',',$Arr)));
                            echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
