<?php
class AddInvDeductParticular extends AppModel {
	public $useTable='tmp_deduct_inv_particulars';
    public $validate = array(
		'username'=> array(),
		'cost_center_id'=>array(),
		'company_name'=>array(),
        'branch_name' => array(),
        'cost_center' => array(),
	    'fin_year' => array(),
        'month_for' => array(),
        'particulars' => array(),
		'rate' => array(),
		'qty' => array(),
		'amount' => array(),
		'initial_id' => array(),
		'createdate' => array()
    );
}

?>