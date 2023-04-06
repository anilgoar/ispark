<?php
class ClientCategory extends AppModel {
	public $useTable='prospact_manage_user';
	public $virtualFields = array('name'=>"CONCAT(ecrName,'  Label-',Label)",'Ecr'=>"MAX(Label)");
}

?>