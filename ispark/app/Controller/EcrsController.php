<?php
class EcrsController extends AppController {
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ClientCategory','EcrMaster','UserMaster');
   
    public function beforeFilter(){
        parent::beforeFilter();
        $this->layout='home';
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else{
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            
                $this->Auth->allow(
                    'index','create_category','update_category','create_type','get_label2','create_sub_type1','get_label3',
                    'create_sub_type2','get_label4','create_sub_type3','delete_ecr','edit_label2','edit_label2_sub1',
                    'edit_label2_sub2','edit_label3','edit_label3_sub1','edit_label3_sub2','edit_label4_sub1'
                );
                $this->Auth->allow('add');
                $this->Auth->allow('edit');
            
               
            
        }	
    }
	
    public function index(){
        $this->layout='home';
        $this->set('user', $this->UserMaster->find('list',array('fields'=>array('username','username'),'conditions'=>array('UserActive'=>1),'order' => array('username' => 'asc'))));
        $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'))));
        $this->set('Category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('label'=>'1'))));
       
        if(isset($_REQUEST['cms']) && $_REQUEST['cms'] !=""){
            $this->set('cms',$_REQUEST['cms']);
        }
        
        $ecdata =$this->EcrMaster->find('list',array('fields'=>array("Label","grp"),'group'=>'Label'));
        $this->set('ecrcat1',$ecdata);
        $this->set('ecrcat2',$ecdata);
        $this->set('ecrcat3',$ecdata);
        $this->set('ecrcat4',$ecdata);
        $this->set('ecrcat5',$ecdata);    
    }
    
    
   
    public function create_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data['Label'] = '1';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['ecrName'] = addslashes($this->request->data['Ecr']['category']);
                
                $exist=$this->existScenario($data['ecrName'],$data['Label'],NULL);
                
                if(empty($exist)){
                    $this->ClientCategory->save($data);
                    $this->Session->setFlash("<span style='color:green;'>User created successfully.</span>");
                }
                else{
                    $this->Session->setFlash("<span style='color:red;'>User allready exist in database.</span>"); 
                }
            }
            $this->redirect(array('action'=>'index','?'=>array('cms'=>'0')));
           
        }
    }
    
   
    public function update_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data = $this->request->data['Ecrs'];
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
        }
        echo "1";die;
    }
	
    
    public function create_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data['Label'] = '2';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $this->Session->read('companyid');
                $data['ecrName'] = addslashes($this->request->data['Ecr']['type']);
                $data['parent_id'] = addslashes($this->request->data['Ecr']['category']);
                
                $exist=$this->existScenario($data['ecrName'],$data['Label'],$data['parent_id']);
                
                if(empty($exist)){
                    $this->ClientCategory->save($data);
                    $this->Session->setFlash("<span style='color:green;'>User created successfully.</span>");
                }
                else{
                    $this->Session->setFlash("<span style='color:red;'>User allready exist in database.</span>"); 
                }
            }
            $this->redirect(array('action'=>'index','?'=>array('cms'=>'1')));
        }
    }
    
    
    public function update_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data = $this->request->data['Ecrs']; 
                $this->set('data',$this->request->data);
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
        }
        echo "1";die;
    }

	public function create_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '3';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Ecr']['Type']);			
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type1']);
                        
                        $exist=$this->existScenario($data['ecrName'],$data['Label'],$data['parent_id']);
                
                        if(empty($exist)){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("<span style='color:green;'>User created successfully.</span>");
                        }
                        else{
                            $this->Session->setFlash("<span style='color:red;'>User allready exist in database.</span>"); 
                        }
  
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'2')));
		}
	}
       
        public function update_sub_type1()
	{
            if($this->request->is("POST"))
            {
                if(!empty($this->request->data))
                {
                    $data = $this->request->data['Ecrs'];
                    $this->set('data',$this->request->data);
                    $keys = array_keys($data);
                    $count = count($data);
                    for($i = 0; $i<$count; $i++)
                    {
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                    }
                }

            }
        echo "1";die;
	}

	public function create_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '4';
			$data['createdate'] = date('Y-m-d H:i:s');			
			$data['Client'] = $this->Session->read('companyid');
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type2']);
			$data['parent_id'] = addslashes($this->request->data['Ecr']['sub_type1']);
                        
                        $exist=$this->existScenario($data['ecrName'],$data['Label'],$data['parent_id']);
                
                        if(empty($exist)){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("<span style='color:green;'>User created successfully.</span>");
                        }
                        else{
                            $this->Session->setFlash("<span style='color:red;'>User allready exist in database.</span>"); 
                        }
			
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'3')));
		}
	}
       
        public function update_sub_type2(){
            if($this->request->is("POST")){
                if(!empty($this->request->data)){
                        $data = $this->request->data['Ecrs'];
                        $this->set('data',$this->request->data);
                        $keys = array_keys($data);
                        $count = count($data);
                        for($i = 0; $i<$count; $i++)
                        {
                            $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                        }
                }
            }
            echo "1";die;
	}

	public function create_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '5';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Ecr']['sub_type2']);			
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type3']);
                        
                        $exist=$this->existScenario($data['ecrName'],$data['Label'],$data['parent_id']);
                
                        if(empty($exist)){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("<span style='color:green;'>User created successfully.</span>");
                        }
                        else{
                            $this->Session->setFlash("<span style='color:red;'>User allready exist in database.</span>"); 
                        }
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'4')));
		}
	}
        
        public function update_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Ecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
            echo "1";die;
	}
	
	public function get_label2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
      
        public function edit_label2()
	{
            $this->layout="ajax";
            if($this->request->is('POST'))
            {
                if(!empty($this->request->data))
                {
                $conditions['Label'] = '2';
                $conditions['parent_id'] = $this->request->data['parent_id'];
                $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                $this->set('data',$subType); 

                }
            }
	}
        
        public function edit_label2_sub1(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '2';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
        
        public function edit_label2_sub2(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '3';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
        
        

	public function get_label3()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
      
        public function edit_label3(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '3';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                }
            }
	}
        
        public function edit_label3_sub1(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '4';
                    $conditions['parent_id'] = $this->request->data['parent_id'];			
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 			
                }
            }
	}
        
        public function edit_label3_sub2(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '4';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
	
	public function get_label4()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '4';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
       
        public function edit_label4_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '5';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			}
		}
	}
	
        
    public function delete_ecr(){
        $id=$this->request->query['id'];
        if($this->ClientCategory->deleteAll(array('id'=>$id))){
            $this->ClientCategory->deleteAll(array('parent_id'=>$id));
        }
        $this->redirect(array('action' => 'index'));
    }
   
    public function existScenario($name,$label,$parentid){
        return $exist = $this->EcrMaster->find('first',array('fields'=>'id','conditions'=>array('ecrName'=>$name,'Label'=>$label,'parent_id'=>$parentid)));      
    }   

}

?>