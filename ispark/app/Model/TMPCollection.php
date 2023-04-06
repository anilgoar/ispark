<?php
class TMPCollection extends AppModel {
	public $useTable='tmp_tbl_payment';
    public $validate = array(
        'company_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A process name is required'
            )
        ),
		'branch_name' => array(
			'required' => array(
			'rule' => 'notBlank',
			'message' => 'A Branch Name is required'
			)),
		'financial_year' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Select Financial Year'
			)),
		'pay_type' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Select Cheque Or RTGS'
			)),
		'pay_no' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Fill No.'
			)),			
		'bank_name' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Select Bank Name'
			)),
		'pay_dates' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Select Bank Date'
			)),
		'pay_amount' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Fill Payment Amount'
			)),
		'deposit_bank' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Select Deposit Bank'
			)),
		'no_of_bills' => array(
			'required' => array(
			'rule'	=> 'notBlank',
			'message' => 'Please Fill No Of Bills Payment Received'
			))			
    );
}

?>