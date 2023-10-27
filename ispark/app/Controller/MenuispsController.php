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
            //print_r($_SERVER); exit;
            $id         =   base64_decode($_REQUEST['AX']);
            $user       =   $this->Session->read('email');
            $back_url = $_SERVER['REQUEST_URI'];
            //parent_access AS
            $page_list  =   $this->PageMasterIspark->query("SELECT * FROM `pages_master_ispark` WHERE parent_id='$id' "); 
            $page_name  =   $this->PageMasterIspark->query("SELECT page_name FROM `pages_master_ispark` WHERE id='$id' limit 1");
            $access     =   $this->PageMasterIspark->query("SELECT access FROM `pages_ride_ispark` WHERE user_name='$user' limit 1");
		    $exp        =   explode(",", $access[0]['pages_ride_ispark']['access']);
			
            $listid=$this->PageMasterIspark->find('list',array('conditions'=>array('parent_id'=>$id)));
			
			            
            $this->set('pagename',$page_name[0]['pages_master_ispark']['page_name']);
            $this->set('pagelist',$page_list);
            $this->set('access',$exp);
            $this->set('listid',$listid);
            $this->set('back_url',$back_url);
        }
    }
	
	public function sub(){
        $this->layout='home';
		if($_REQUEST['AX']){
            $id         =   base64_decode($_REQUEST['AX']);
            $back_url = base64_decode($_REQUEST['AY']);
            //$back_url = $_SERVER['REQUEST_URI'];
            $user       =   $this->Session->read('email');
            //parent_access AS
            $page_list  =   $this->PageMasterIspark->query("SELECT * FROM `pages_master_ispark` WHERE parent_id='$id' order by priority"); 
            $page_name  =   $this->PageMasterIspark->query("SELECT page_url,parent_id,page_name FROM `pages_master_ispark` WHERE id='$id' limit 1");
            $access     =   $this->PageMasterIspark->query("SELECT access FROM `pages_ride_ispark` WHERE user_name='$user' limit 1");
		    $exp        =   explode(",", $access[0]['pages_ride_ispark']['access']);
			
            $listid=$this->PageMasterIspark->find('list',array('conditions'=>array('parent_id'=>$id)));
	   
            $parent_id = $page_name[0]['pages_master_ispark']['parent_id'];
            $parent_name  =   $this->PageMasterIspark->query("SELECT page_url,parent_id,page_name FROM `pages_master_ispark` WHERE id='$parent_id' limit 1");
            
            $this->set('pagename',$page_name[0]['pages_master_ispark']['page_name']);
            $this->set('page_url',$parent_name[0]['pages_master_ispark']['page_url']);
            $this->set('parent_id',$parent_id);
            $this->set('pagelist',$page_list);
            $this->set('access',$exp);
            $this->set('listid',$listid);
            $this->set('back_url',$back_url);
        }
    }
    
}
?>