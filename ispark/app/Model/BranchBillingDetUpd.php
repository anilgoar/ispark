<?php
class BranchBillingDetUpd extends AppModel {
	public $useTable='branch_billing_det_upd';
    public $validate = array(
        'FinanceYear' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Finance Year is required'
            )
        ),
       'FinanceMonth' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Finance Month is required'
            )
        ),
        'BranchId' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Branch Name is required'
            )
        ),
        'created_at' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Please contact to admin'
            )
        ),
        'created_by' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Your Session Has been expired.'
            )
        )
    );
}

?>