<?php
class CostCenterActsController extends AppController 
{
    public $uses=array('DashboardData','DashboardProcess','Addbranch','CostCenterMaster');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout='home';
        if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));				
            if(in_array('59',$roles)){$this->Auth->allow('index');$this->Auth->allow('get_process');$this->Auth->allow('get_tower');$this->Auth->allow('add_process');$this->Auth->allow('get_dash_data');$this->Auth->allow('edit');}				
        }
    }
		
    public function index() 
    {
        $this->layout="home";
        $branchName = $this->Session->read('branch_name');
       
        if($this->Session->read('role')=='admin')
        {
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'))));
        }
        else if(count($branchName)>1)
        {
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else
        {
            $tower = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>array('branch'=>$branchName
            ,'active'=>'1',"(close>date(now()) or close is null)")));

           //print_r($tower); exit;
           if(!empty($tower))
           {
               foreach($tower as $tow)
               {
                   $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
               }
           }

               $this->set('tower1',$tower1);
        }
        
        
        if($this->request->is('Post'))
        {
            
            
            if(!empty($this->request->data))
            {
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['user_id'] = $this->Session->read('userid');
                foreach($this->request->data['Dashboard'] as $k=>$v)
                {
                    $data[$k] = addslashes($v);
                }
                
              echo  $data['date'] = date_format(date_create($data['date']), 'Y-m-d');
                    $data['date'] = "'".$data['date']."'";
                if($data['branch']=='')
                {
                    $data['branch']=$this->Session->read('branch_name');
                    if($data['branch']>1){
                    $data['branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                
                
                }
                $check = $this->CostCenterMaster->find('first',array('conditions'=>array('id'=>$data['cost_centerId'])));   
                $flag = FALSE;
              
                
                if($check['CostCenterMaster']['close']== null || $check['CostCenterMaster']['close']== '')
                {
                $select = $this->CostCenterMaster->updateAll(array('close'=>$data['date']),array('id'=>$data['cost_centerId'],'close' => null));
                $flag = TRUE;
                }
               // print_r($data['cost_centerId']); die;
                if($flag)
                {
                    $this->Session->setFlash("<font color='green'>Cost Center  close  after ". $data['date']." </font>");
                    $this->redirect(array('action'=>'index'));
                }
               
                else
                {
                    $this->Session->setFlash("<font color='red'>Cost center  Already  Closed </font>");
                }
            }
        }
     }

    public function get_process()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
       //$this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
        $processArr = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>"branch='$branchName'
     and active='1' and (close>date(now()) or close is null)"));
       if(!empty($processArr))
        {
            foreach($processArr as $tow)
            {
                $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
            }
        }
    
        $this->set('tower1',$tower1);
    }
   
    public function add_process()
    {
        $this->layout='home';
        $branchName = $this->request->data['branch'];
        if($this->Session->read('role')=='admin')
        {
            $this->set('process',$this->DashboardProcess->find('all',array('fields'=>array('id','Branch','branch_process'),'order'=>array('Branch'=>'Asc'))));
        }
        else
        {
            $this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','Branch','branch_process'),'conditions'=>array('Branch'=>$branchName),'order'=>array('Branch'=>'Asc'))));
        }   
    }
	 public function get_tower()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
       //$this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
        $processArr = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>"branch='$branchName'
     and active='1' and (close>date(now()) or close is null)"));
       if(!empty($processArr))
        {
            foreach($processArr as $tow)
            {
                $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
            }
        }
    
        $this->set('tower1',$tower1);
    }
    
}

?>