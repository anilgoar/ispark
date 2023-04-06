<?php
class AppClientsController extends AppController 
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('add');
	}
	
	public function index() 
	{
		$this->AppClient->recursive = 0;
		$this->set('tbl_client_master', $this->paginate());
		$this->layout='home';
	}
   
	public function add() 
	{
		if ($this->request->is('post')) 
		{
			$this->AppClient->create();
			if ($this->AppClient->save($this->request->data))
			{
				$this->Session->setFlash(__('The Client has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
		}
	}
}
?>