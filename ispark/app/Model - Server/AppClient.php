<?php
class Addbranch extends AppModel {
	public $useTable='tbl_client_master';
    public $validate = array(
        'client_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A client name is required'
            )
        )		
    );
}

?>