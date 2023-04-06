<?php
App::uses('AppController', 'Controller');

class ProcessController extends AppController {
	public $uses=array('Pages');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add_process');
    }

    public function login() {
        $this->User->recursive = 0;
        if($this->Session->check('Auth.User')){
            $this->redirect(array('action' => 'view'));      
        }
		$this->layout='view';
    }
    public function logout() {
   		$this->Session->delete('username');
   		$this->Session->destroy();
        $this->redirect(array('action'=>'login'));
    }
   
   
}
?>