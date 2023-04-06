<?php
class MenuIsparksController extends AppController {
    public $uses = array('Addbranch');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('profitloss');
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function profitloss(){
        $this->layout='home';  
    }   
}
?>