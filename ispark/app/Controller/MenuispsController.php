<?php
class MenuispsController extends AppController {
    public $uses = array('PageMasterIspark');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('sub');
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        if($_REQUEST['AX']){
            $id         =   base64_decode($_REQUEST['AX']);
            $user       =   $this->Session->read('email');
            //parent_access AS
            $page_list  =   $this->PageMasterIspark->query("SELECT * FROM `pages_master_ispark` WHERE parent_id='$id'"); 
            $page_name  =   $this->PageMasterIspark->query("SELECT page_name FROM `pages_master_ispark` WHERE id='$id' limit 1");
            $access     =   $this->PageMasterIspark->query("SELECT access FROM `pages_ride_ispark` WHERE user_name='$user' limit 1");
		    $exp        =   explode(",", $access[0]['pages_ride_ispark']['access']);
			
            $listid=$this->PageMasterIspark->find('list',array('conditions'=>array('parent_id'=>$id)));
			
			            
            $this->set('pagename',$page_name[0]['pages_master_ispark']['page_name']);
            $this->set('pagelist',$page_list);
            $this->set('access',$exp);
            $this->set('listid',$listid);
        }
    }
	
	public function sub(){
        $this->layout='home';
		if($_REQUEST['AX']){
            $id         =   base64_decode($_REQUEST['AX']);
            $user       =   $this->Session->read('email');
            //parent_access AS
            $page_list  =   $this->PageMasterIspark->query("SELECT * FROM `pages_master_ispark` WHERE parent_id='$id' order by priority"); 
            $page_name  =   $this->PageMasterIspark->query("SELECT page_name FROM `pages_master_ispark` WHERE id='$id' limit 1");
            $access     =   $this->PageMasterIspark->query("SELECT access FROM `pages_ride_ispark` WHERE user_name='$user' limit 1");
		    $exp        =   explode(",", $access[0]['pages_ride_ispark']['access']);
		//print_r($page_list); exit;	
            $listid=$this->PageMasterIspark->find('list',array('conditions'=>array('parent_id'=>$id)));
			            
            $this->set('pagename',$page_name[0]['pages_master_ispark']['page_name']);
            $this->set('pagelist',$page_list);
            $this->set('access',$exp);
            $this->set('listid',$listid);
        }
    }
    
}
?>