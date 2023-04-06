<?php
class CostCenterEmailsController extends AppController 
{
    public $uses=array('CostCenterEmail','CostCenterMaster','Addbranch');
    public function beforeFilter()
    {
        parent::beforeFilter();        	
			
	if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {   $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            if(in_array('58',$roles) || in_array('57',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('edit');}
            else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}  
        }

    }
		
    public function index() 
    {
        $data = $this->CostCenterMaster->query("SELECT Id,cost_center FROM cost_master cm 
WHERE NOT EXISTS (SELECT cost_center FROM cost_center_email cme WHERE cme.cost_center = cm.Id) 
ORDER BY CONVERT(SUBSTRING_INDEX(cost_center,'/',-1),UNSIGNED INT);");
        
        foreach($data as $cost)
        {
            $cost_center[$cost['cm']['Id']] = $cost['cm']['cost_center'];
        }
        $this->set('cost_center',$cost_center);
        $this->set('cost_center2', $this->CostCenterMaster->query("SELECT Id,branch,client,cost_center FROM cost_master cm 
WHERE EXISTS (SELECT cost_center FROM cost_center_email cme WHERE cme.cost_center = cm.Id) 
ORDER BY CONVERT(SUBSTRING_INDEX(cost_center,'/',-1),UNSIGNED INT);"));		
        $this->layout='home';
    }
	   
    public function add() 
    {
        if ($this->request->is('post')) 
        {
            if ($this->CostCenterEmail->save($this->request->data))
            {
                $this->Session->setFlash(__('The Emails has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('The Emails could not be saved. Please, try again.'));
	}
    }
    public function edit() 
    {
        $this->layout='home';
        if ($this->request->is('post')) 
	{
            
           //print_r($this->request->data); exit;
            $data = $this->request->data['CostCenterEmail'];
            $id = $data['id'];
            $data = Hash::Remove($data,'id');
            //print_r($id); exit;
            foreach($data as $key=>$post) //making query to update in temp cost_center
            {
                $dataX[$key] = "'".addslashes($post)."'";
            }
                
            if ($this->CostCenterEmail->updateAll($dataX,array('id'=>$id)))
            {
                $this->Session->setFlash(__("<h4 class=bg-success>".'The Cost Center Email has been updated successfully'."</h4>"));
                return $this->redirect(array('action' => 'index'));
            }
                $this->Session->setFlash(__("<h4 class=bg-danger>".'The Cost Center Email could not be updated. Please, try again.'."</h4>"));
	}
	else
	{
            $id  = $this->request->query['id'];
            $this->set('cost_center_email',$this->CostCenterEmail->find('first',array('conditions'=>array('cost_center'=>$id))));
            $this->set('cost_center',$this->CostCenterMaster->find('first',array('conditions'=>array('Id'=>$id))));
	}
    }
}

?>