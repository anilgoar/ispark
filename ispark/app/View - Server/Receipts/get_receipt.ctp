<?php //print_r($invoice_master); ?>
<?php
	if(!empty($invoice_master['0']['t1']['bill_no']))
	{
	echo $invoice_master['0']['t1']['bill_no'];
	echo $this->Form->input('id', array('label'=>false,'value'=>$invoice_master['0']['t1']['id'],'type'=>'hidden')); 
	echo $this->Form->input('InvoiceNo', array('label'=>false,'value'=>$invoice_master['0']['t1']['bill_no'],'type'=>'hidden','required'=>'yes','message'=>'please enter your text'));
	}
	else
	{
		echo "" ;
	}
?>
