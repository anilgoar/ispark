<?php
class Category extends AppModel {
	public $useTable='category_master';
    public $validate = array(
        'category' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A process name is required'
            )
        )
    );
}

?>