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
                    
                    <span><b>Add Payment</b></span>
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
		<?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal')); ?> 
                                <div class="form-group">
                                    <label class="col-sm-2">Select Vendor/Imprest</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('PaymentHead', array('label'=>false,'class'=>'form-control','options' => array('Vendor'=>'Vendor Name','Imprest'=>'Imprest Manager'),'empty' => 'Select','id'=>'PaymentHead','onChange'=>"get_displayI(this.value)")); ?>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none" id="bill">
                                    <label class="col-sm-2 control-label">Select Vendor</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('VendorId', array('label'=>false,'class'=>'form-control','options' => $Vendor,'empty' => 'Select','id'=>'VendorId')); ?>
                                    </div>
                                    <label class="col-sm-2 control-label">Payment Against</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('Payment', array('label'=>false,'class'=>'form-control','options' => array('Bill'=>'Bill','Advance'=>'Advance'),'empty' => 'Select','id'=>'Payment')); ?>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none" id="imprest">
                                    <label class="col-sm-2 control-label">Imprest Manager</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('Imprest', array('label'=>false,'class'=>'form-control','options' => $Imprest,'empty' => 'Select','id'=>'Imprest')); ?>
                                    </div>
                                </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('Amount', array('label'=>false,'class'=>'form-control','placeholder' => 'Amount','id'=>'Amount','required'=>true)); ?>
                                    </div>
                                    <label class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-2">
                                      <?php  echo $this->Form->input('Remarks', array('label'=>false,'class'=>'form-control','placeholder' => 'Remarks','id'=>'Remarks','required'=>true)); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-info">Save</button>
                                        <a href="/ispark/Menuisps/sub?AX=NjI=" class="btn btn-primary btn-label-left">Back</a> 
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?> 
                    </div>
		<div class="clearfix"></div>
		<div class="form-group">        
            </div>
        </div>
    </div>
</div>




<script>
    function get_displayI(val)
    {
        if(val=='Vendor')
        {
            $('#bill').show();
             $('#imprest').hide();
        }
        else if(val=='Imprest')
        {
             $('#bill').hide();
            $('#imprest').show();
        }
        else
        {
           $('#bill').hide();
           $('#imprest').hide();
        }
    }
</script>