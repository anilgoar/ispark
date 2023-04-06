<?php
class ItWorkStatus extends AppModel {
	public $useTable='tbl_itwork';
    public $validate = array(
        'Category' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A Category name is required'
            )
        ),
		'Description' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A Description is required'
			)
		)		
    );
}

?>