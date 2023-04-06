<?php
class Receipt extends AppModel {
	public $useTable='receipt_master';
    public $validate = array(
        'CompanyName' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Comapany name is required'
            )
        ),
        'branch_name' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Branch name is required'
			)
		),
		'financial_year' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Financial Year is required'
			)
		),
		'invoice_no' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Invoice No is required'
			)
		)
   );
}

?>