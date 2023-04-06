<?php
class QualificationMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','DepartmentNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        /*
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
        
        
        $DataArr=$this->DepartmentNameMaster->find('all');
        $this->set('DataArr',$DataArr);
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->DepartmentNameMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['DepartmentNameMaster']);  
        }
        
         
        
        
        if($this->request->is('Post')){
            
            $Department     =   addslashes(trim($this->request->data['Department']));
            $submit         =   trim($this->request->data['submit']);
            $DepartmentId   =   trim($this->request->data['DepartmentId']);
            $Status         =   trim($this->request->data['Status']);
            
            
            
            if($submit =="Update"){
                $updArr=array(
                    'Department'=>"'".$Department."'",
                    'Status'=>"'".$Status."'",
                    'UpdateDate'=>"'".date('Y-m-d H:i:s')."'",
                    );
                
                if($this->DepartmentNameMaster->updateAll($updArr,array('Id'=>$DepartmentId))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department update successfully.</span>');
                    $this->redirect(array('controller'=>'DepartmentNameMasters')); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department not update please try again later.</span>');
                    $this->redirect(array('controller'=>'DepartmentNameMasters')); 
                }
            }
            else{
                $data=array(
                    'Department'=>$Department,
                );
                
                $row=$this->DepartmentNameMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This department already exist in database.</span>');
                    $this->redirect(array('controller'=>'DepartmentNameMasters')); 
                }
                else{
                    $this->DepartmentNameMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department create successfully.</span>');
                    $this->redirect(array('controller'=>'DepartmentNameMasters'));   
                }
                     
            }  
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->DepartmentNameMaster->query("DELETE FROM `incentive_name_master` WHERE Id='{$_REQUEST['id']}'");
            $this->redirect(array('action'=>'index')); 
        }
     
    }
    
    
}
?>