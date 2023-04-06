<?php
class MenusController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','PageMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'attendance','holiday','loan','employeedetails','empcodegenerate','incentivedetails','master','od','changdoj',
            'docvalidation','processdeduction','lockunlock','processnoc','jclr','leavemanagement','reports','investmentdeclaraton','uploadepfesicuna','sub'
        );
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        if($_REQUEST['AX']){
            $id         =   base64_decode($_REQUEST['AX']);
            $user       =   $this->Session->read('email');
            
            $page_list  =   $this->Masjclrentry->query("SELECT * FROM `pages_master` WHERE parent_id='$id'"); 
            $page_name  =   $this->Masjclrentry->query("SELECT page_name FROM `pages_master` WHERE id='$id' limit 1");
            $access     =   $this->Masjclrentry->query("SELECT access FROM `pages_ride` WHERE user_name='$user' limit 1");
            $exp        =   explode(",", $access[0]['pages_ride']['access']);
            
            $listid=$this->PageMaster->find('list',array('conditions'=>array('parent_id'=>$id)));
            
            $this->set('pagename',$page_name[0]['pages_master']['page_name']);
            $this->set('pagelist',$page_list);
            $this->set('access',$exp);
            $this->set('listid',$listid);
        }
    }
	
	public function sub(){
        $this->layout='home';
    }
    
    public function attendance(){
        $this->layout='home';  
    }
    
    public function holiday(){
        $this->layout='home';  
    }
    
    public function loan(){
        $this->layout='home';  
    }
    
    public function employeedetails(){
        $this->layout='home';  
    }
    
    public function empcodegenerate(){
        $this->layout='home';  
    }
    
    public function incentivedetails(){
        $this->layout='home';  
    }
    
    public function master(){
        $this->layout='home';  
    }
    
    public function od(){
        $this->layout='home';  
    }
    
    public function changdoj(){
        $this->layout='home';  
    }
    
    public function docvalidation(){
        $this->layout='home';  
    }
    
    public function processdeduction(){
        $this->layout='home';  
    }
    
    public function lockunlock(){
        $this->layout='home';  
    }
    
    public function processnoc(){
        $this->layout='home';  
    }
    
    public function jclr(){
        $this->layout='home';  
    }
    
    public function leavemanagement(){
        $this->layout='home';  
    }
    
    public function reports(){
        $this->layout='home';  
    }
    
    public function investmentdeclaraton(){
        $this->layout='home';  
    }
    
    public function uploadepfesicuna(){
        $this->layout='home';  
    }
    
    
    
    
    
    
    
}
?>