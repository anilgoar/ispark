<?php
class LockUnlocksController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','LockUnlockMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        
        $this->layout='home';
        
        
        
        $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'))));
        
        /*
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'))));
        }
        else if(count($branchName)>1){
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else{
           $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        */
        
        $lockArr=array();
        $dataArr=$this->LockUnlockMaster->find('all');
        
        foreach($dataArr as $val){
            $lockArr[$val['LockUnlockMaster']['BranchName']]=array(
                //'Leave'=>$val['LockUnlockMaster']['Leave'],
                'OD'=>$val['LockUnlockMaster']['OD'],
                'Exception'=>$val['LockUnlockMaster']['Exception'],
                //'OverrideLeave'=>$val['LockUnlockMaster']['OverrideLeave'],
                //'OverrideOD'=>$val['LockUnlockMaster']['OverrideOD'],
                //'OverrideException'=>$val['LockUnlockMaster']['OverrideException'],
            );
        }
        $this->set('lokulok',$lockArr); 
        
        if($this->request->is('Post')){
            
            if(isset($this->request->data['BranchName'])){
                $BranchArr  =   $this->request->data['BranchName'];
                
                foreach ($BranchArr as $branch){
                    
                    isset($this->request->data['Leave'][$branch])?$Leave=$this->request->data['Leave'][$branch]:$Leave=NULL;
                    isset($this->request->data['OD'][$branch])?$OD=$this->request->data['OD'][$branch]:$OD=NULL;
                    isset($this->request->data['Exception'][$branch])?$Exception=$this->request->data['Exception'][$branch]:$Exception=NULL;
                    isset($this->request->data['OverrideLeave'][$branch])?$OverrideLeave=$this->request->data['OverrideLeave'][$branch]:$OverrideLeave=NULL;
                    isset($this->request->data['OverrideOD'][$branch])?$OverrideOD=$this->request->data['OverrideOD'][$branch]:$OverrideOD=NULL;
                    isset($this->request->data['OverrideException'][$branch])?$OverrideException=$this->request->data['OverrideException'][$branch]:$OverrideException=NULL;
                    
                    $count=$this->LockUnlockMaster->find('count',array('conditions'=>array('BranchName'=>$branch)));
                    
                    if($count > 0){
                        
                        $updArr=array(
                            //'Leave'=>"'".$Leave."'",
                            'OD'=>"'".$OD."'",
                            'Exception'=>"'".$Exception."'",
                            //'OverrideLeave'=>"'".$OverrideLeave."'",
                            //'OverrideOD'=>"'".$OverrideOD."'",
                            //'OverrideException'=>"'".$OverrideException."'",
                        );
                        
                        $this->LockUnlockMaster->updateAll($updArr,array('BranchName'=>$branch));  
                    }
                    else{
                        
                        $addArr=array(
                            'BranchName'=>$branch,
                            //'Leave'=>$Leave,
                            'OD'=>$OD,
                            'Exception'=>$Exception,
                            //'OverrideLeave'=>$OverrideLeave,
                            //'OverrideOD'=>$OverrideOD,
                            //'OverrideException'=>$OverrideException,
                        );

                        $this->LockUnlockMaster->saveAll($addArr);
                    
                    }
                      
                }
            }
            $this->Session->setFlash('<span style="color:green;" >This request update successfully.</span>'); 
            $this->redirect(array('controller'=>'LockUnlocks','action' => 'index'));
        }     
    }
    
}
?>