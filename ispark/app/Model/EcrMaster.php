<?php
class EcrMaster extends AppModel{
	public $useTable='prospact_manage_user';
	public $virtualFields = array('ecr'=>"group_concat(ecrName)",'grp'=>"GROUP_CONCAT( CONCAT(id,'=>',ecrName) SEPARATOR '==>')");
	
}
?>