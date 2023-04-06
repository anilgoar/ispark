<?php //print_r($res); ?>
<?php echo $this->Form->create('Receipt',array('class'=>'form-horizontal','action'=>'add','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
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
                    <span>Upload Receipt</span>
		</div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                
                <div class="form-group has-feedback">
		<label class="col-sm-2 control-label">Company Name</label>
		<div class="col-sm-3">
						
		<?php
                    foreach($company_master as $post):
                        $data[$post['Addcompany']['company_name']] = $post['Addcompany']['company_name'];
                    endforeach; 
		?>
		<?php	echo $this->Form->input('CompanyName', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Company','required'=>true)); 
                        unset($data);
                ?>
		</div>

                <label class="col-sm-2 control-label">Branch</label>
                <div class="col-sm-3">
                    <div id="mm">
                    <?php
                        foreach($branch_master as $post):
                            $data[$post['Addbranch']['branch_name']] = $post['Addbranch']['branch_name'];
                        endforeach; 
                    ?>
                    <?php	echo $this->Form->input('BranchName', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Branch','required'=>true)); ?>
                    </div>
		</div>
		</div>
		<div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Financial Year</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('FinancialYear', array('options' => $finance_yearNew,'empty' => 'Select Year','label' => false, 'div' => false,'class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-2 control-label">Enter Invoice</label>
                    <div class="col-sm-3">
                        <?php	echo $this->Form->input('invoice', array('label'=>false,'class'=>'form-control','placeholder' => 'Enter Invoice','required'=>true,'onBlur'=>'get_Receipt(this.value)')); ?>
                    </div>
		</div>
					
		<div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Invoice No</label>
                    <div class="col-sm-3">
                        <div id="receiptdata"></div>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Submit Date</label>
                    <div class="col-sm-3">
                        <?php	echo $this->Form->input('SubmitedDates', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[Receipt][SubmitedDates]');",'required'=>true, 'type' => 'text')); ?>
                    </div>

                    <label class="col-sm-2 control-label">Submit To</label>
                    <div class="col-sm-3">
                        <?php	echo $this->Form->input('SubmitedTo', array('label'=>false,'class'=>'form-control','placeholder' => 'Enter Name','required'=>true,)); ?>
                    </div>
                </div>
					
                <div class="form-group has-feedback">
                <label class="col-sm-2 control-label">Expected Date Of Payment</label>
		<div class="col-sm-3">				
		<?php	echo $this->Form->input('ExpDatesPayment', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
			'onClick'=>"displayDatePicker('data[Receipt][ExpDatesPayment]');",'required'=>true, 'type' => 'text')); ?>
		</div>

                <label class="col-sm-2 control-label">Remarks</label>
                <div class="col-sm-3">
                    <?php	echo $this->Form->textarea('Remarks', array('label'=>false,'class'=>'form-control','placeholder' => 'Enter Remarks','required'=>true)); ?>
                </div>
                </div>
					
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Receipt File</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->file('ReceiptFile.', array('type'=>'file','label' => false,'mulitple'=>true, 'div' => false)); ?>
                    </div>
                </div>
					
                <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-label-left">Submit</button>
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
            <h4 class="page-header">Receipt</h4>
                <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                    <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Invoice No</th>
                    <th>Receipt Remarks</th>
                    <th>Submitted To</th>
                    <th>Submitted Date</th>
                    <th>Expected Date</th>
                    <th>Entry Date</th>
                    <th>Download</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php  
                    $case=array('info',''); $i=1; 
                    foreach ($receipt_master as $post): ?>
                <tr >
                <td><?php echo $i++;?></td>
                <td><?php echo $post['Receipt']['InvoiceNo']; ?></td>
                <td><?php echo $post['Receipt']['Remarks']; ?></td>
                <td><?php echo $post['Receipt']['SubmitedTo']; ?></td>
                <td><?php echo date('d-M-Y',strtotime($post['Receipt']['SubmitedDates'])); ?></td>
                <td><?php echo date('d-M-Y',strtotime($post['Receipt']['ExpDatesPayment'])); ?></td>
                <td><?php echo date('d-M-Y',strtotime($post['Receipt']['CreateDate'])); ?></td>
                <td>								
		<?php 
                if($post['Receipt']['ReceiptFile'] !='')
                {
                    $files=explode(',',$post['Receipt']['ReceiptFile']);
		
                    if(isset($files))
                    {
                        foreach($files as $links) : 
                ?>
		&nbsp; <a href="<?php echo $this->html->webroot('receipt_file'.DS.$links); ?>"><?php echo $this->Html->image('download.png', array('alt' => "download",'hieght'=>'15','width'=>'15','class' => 'img-rounded'));?> </a>
		<?php	 endforeach;
                    }
		}
		?>                
		</td>
                <td><?php
                        echo $this->Html->link('Edit',array('controller'=>'Receipts','action'=>'edit','?'=>array('id'=>$post['Receipt']['id'])));
                    ?>
                </td>
		</tr>
                
		<?php endforeach; ?>
                </tbody>
		</table>
            </div>
	</div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>  
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>