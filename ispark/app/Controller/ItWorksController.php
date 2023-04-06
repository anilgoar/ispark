<?php
App::uses('AppController', 'Controller');

class ItWorksController extends AppController {
	public $uses=array('Pages','ItWorkStatus','Logx');
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check('Auth.User')){
            
            $this->Auth->allow('index','UserLog');     
        }
        else
        {
            $this->Auth->allow('index','UserLog');   
            //$this->redirect(array('controller'=>'Users','action' => 'index'));
        }
        
    }

    public function index(){
        $this->layout="home";
        
        
        $records_arr = $this->ItWorkStatus->query("SELECT *,date_format(Updatedate,'%d-%b-%Y') lst_day FROM `tbl_itwork`  tiw
WHERE tiw.Updatedate IN (SELECT MAX(Updatedate) FROM tbl_itwork)");
        
        foreach($records_arr as $record)
        {
            $CategoryMaster[] = $record['tiw'];
            $last_update_date = $record['0']['lst_day'];
        }
        
        $this->set('record_arr',$CategoryMaster);
        $this->set('last_date',$last_update_date);
        
        $LastLog =   $this->UserLog();
        $this->set('LastLog',$LastLog);
        
    }
    
    public function upload_file() {
   		
    }
    
    
    public function UserLog(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
        else
        $ipaddress = '';
	        
        $PageUrl    =   $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $PageName   =   "IT Management/Dashboard";
        $username   =   $this->Session->read('username');
        $NewDate    =   date('Y-m-d H:i:s');
        
        if($_REQUEST['Id'] ==""){
            $this->Logx->save(array('UserName' => "{$username}", 'IpAddress' => "{$ipaddress}", 'PageUrl' => "{$PageUrl}",'PageName' => "{$PageName}",'LogDate' => "{$NewDate}"));
            return $this->Logx->getLastInsertId();
        }
        else{
            $this->Logx->query("UPDATE user_log SET LastLogDate=NOW() WHERE Id='{$_REQUEST['Id']}' AND PageName='IT Management/Dashboard'");
        }
    }
   
}
?>