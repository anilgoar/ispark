<?php
class Addprocess extends AppModel {
	public $useTable='process_master';
    public $validate = array(
        'process_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A process name is required'
            )
        ),
		'stream' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A stream name is required'
            )
        )		
		
    );
	public function getProcess($check)
	{
		$data=$this->find('first',array('fields'=>'Addprocess.process_name','conditions'=>array('stream'=>$check)));
		return $data;
	}
	public function getStream($check=array())
	{
		$data=$this->find('first',array('fields'=>'Addprocess.stream','conditions'=>array($check)));
		return $data;
	}
	
}

?>