<?php
	class AddclientsController extends AppController 
	{
		public $uses=array('Addclient','Addbranch');
		public function beforeFilter()
		{
        	parent::beforeFilter();        	
			
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
			}
                        else
                        {       $role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
                                if(in_array('2',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('edit');}
                                else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}  
                        }

    	}
		
    	public function index() 
		{
        	$this->Addclient->recursive = 0;
			$this->set('client_master', $this->Addclient->find('all',array('conditions'=>array('client_status'=>1),'order' => array('client_name' => 'asc'))));
			$this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
			
			$this->layout='home';
       }
	   
	   public function add() 
	   {
        	if ($this->request->is('post')) 
			{
                            //print_r($this->request->data); exit;
            	if ($this->Addclient->save($this->request->data))
				{
                	$this->Session->setFlash(__('The Client has been saved'));
                	return $this->redirect(array('action' => 'index'));
            	}
            	$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
        }
		public function edit() 
	   	{
                    $this->layout='home';
        	if ($this->request->is('post')) 
			{
				$this->Addclient->create();
                                //print_r($this->request->data); exit;
                                
				$data=array('client_name'=>"'".$this->request->data['Addclient']['client_name']."'",
				'branch_name'=>"'".$this->request->data['Addclient']['branch_name']."'",'client_status'=>$this->request->data['Addclient']['client_status']);
            	if ($this->Addclient->updateAll($data,array('id'=>$this->request->data['Addclient']['id'])))
				{
                	$this->Session->setFlash(__("<h4 class=bg-success>".'The client has been updated successfully'."</h4>"));
                	return $this->redirect(array('action' => 'index'));
            	}
            	$this->Session->setFlash(__("<h4 class=bg-danger>".'The client could not be updated. Please, try again.'."</h4>"));
			}
			else
			{
                            $id  = $this->request->query['id'];
                            $this->set('client_master',$this->Addclient->find('first',array('conditions'=>array('id'=>$id))));
                            $this->set('branch_master',$this->Addbranch->find('all'));
			}
        }
}

?>