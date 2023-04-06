<?php
class Type extends AppModel {
	public $useTable='type_master';
    public $validate = array(
        'type' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A type name is required'
            )
        )
    );
	public function getCodes($check)
	{
		$data=$this->find('first',array('fields'=>array('codes'),'conditions'=>array('type'=>$check)));
		return $data;
	}
}

?>