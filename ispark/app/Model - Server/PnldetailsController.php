<?php
class PnldetailsController extends AppController 
{
    public $uses=array('PnlMaster','Addbranch');
    public function beforeFilter()
    {
        parent::beforeFilter();
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            
            if(in_array('166',$roles))
            {
                $this->Auth->allow('index');
            }
            else
            {
                $this->Auth->deny('index');
            } 
        }
    }

    public function index() 
    {
        $this->layout='home';
        if($this->request->is('POST'))
        {
            print_r($this->request->data); exit;
        }
    }
    

}

?>