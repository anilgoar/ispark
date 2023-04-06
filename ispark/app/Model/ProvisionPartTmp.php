<?php
class ProvisionPartTmp extends AppModel {
	public $useTable='provision_particulars_tmp';
    public $validate = array(
        'cost_center' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A cost center is required'
            )
        ),
		'FinanceYear' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance year is required'
			)
		),
        	'FinanceMonth' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance year is required'
			)
		),
        'FinanceMonth1' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance month is required'
			)
		),
        
        'billing_id' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance month is required'
			)
		),
        'outsource_amt' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Please Fill Amount'
			)
		)
        
        
    );
}

?>