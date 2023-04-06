<?php echo $this->Form->create('Receipt',array('class'=>'form-horizontal','action'=>'update','enctype'=>'multipart/form-data')); ?>
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
                    <span>Update Receipt</span>
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
		<?php	echo $this->Form->input('CompanyName', array('label'=>false,'class'=>'form-control','options' => $data,'value' => $receipt_master['Receipt']['CompanyName'],'required'=>true));
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
                    <?php	echo $this->Form->input('BranchName', array('label'=>false,'class'=>'form-control','options' => $data,'value' => $receipt_master['Receipt']['BranchName'],'required'=>true)); ?>
                    </div>
		</div>
		</div>
		<div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Financial Year</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('FinancialYear', array('options' => $finance_yearNew,'value' =>$receipt_master['Receipt']['FinancialYear'] ,'label' => false, 'div' => false,'class'=>'form-control')); ?>
                    </div>

                    <label class="col-sm-2 control-label">Enter Invoice</label>
                    <div class="col-sm-3">
                        <?php	echo $this->Form->input('invoiceNo', array('label'=>false,'class'=>'form-control','value' => $receipt_master['Receipt']['InvoiceNo'],'required'=>true)); ?>
                    </div>
		</div>
					
		
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Submit Date</label>
                    <div class="col-sm-3">
                        <?php	$date1 = date_create($receipt_master['Receipt']['SubmitedDates']);
                                $date1 = date_format($date1,'d-m-Y');
                        echo $this->Form->input('SubmitedDates', array('label'=>false,'class'=>'form-control','value'=>$date1,
							'onClick'=>"displayDatePicker('data[Receipt][SubmitedDates]');",'required'=>true, 'type' => 'text')); ?>
                    </div>

                    <label class="col-sm-2 control-label">Submit To</label>
                    <div class="col-sm-3">
                        <?php	echo $this->Form->input('SubmitedTo', array('label'=>false,'class'=>'form-control','value' => $receipt_master['Receipt']['SubmitedTo'],'required'=>true,)); ?>
                    </div>
                </div>
					
                <div class="form-group has-feedback">
                <label class="col-sm-2 control-label">Expected Date Of Payment</label>
		<div class="col-sm-3">				
		<?php	$date2 = date_create($receipt_master['Receipt']['ExpDatesPayment']);
                        $date2 = date_format($date2,'d-m-Y');
                echo $this->Form->input('ExpDatesPayment', array('label'=>false,'class'=>'form-control','value'=>$date2,
			'onClick'=>"displayDatePicker('data[Receipt][ExpDatesPayment]');",'required'=>true, 'type' => 'text')); ?>
		</div>

                <label class="col-sm-2 control-label">Remarks</label>
                <div class="col-sm-3">
                    <?php	echo $this->Form->textarea('Remarks', array('label'=>false,'class'=>'form-control','value' => $receipt_master['Receipt']['Remarks'],'required'=>true)); ?>
                </div>
                </div>
					
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Receipt File</label>
                    <div class="col-sm-3">
                        <?php //echo $this->Form->file('ReceiptFile.', array('type'=>'file','label' => false,'mulitple'=>true,'div' => false)); ?>
				<input type="file" name="data[Receipt][ReceiptFile][]" id="ReceiptReceiptFile" multiple>
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
<?php echo $this->Form->input('id',array('label'=>false,'class'=>'form-control','type'=>'hidden','value'=>$receipt_master['Receipt']['id'])); ?>
<?php echo $this->Form->end(); ?>
