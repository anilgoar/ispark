<?php
class IsparkDepartmentsController extends AppController {
    public $uses = array('IsparkDepartmentMaster','IsparkProcessMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','delete_department','process','delete_process');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $User_Id    = $this->Session->read('email');

        $this->set('DataArr',$this->IsparkDepartmentMaster->find('all'));
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->IsparkDepartmentMaster->find('first',array('conditions'=>array('Department_Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['IsparkDepartmentMaster']);  
        }

        if($this->request->is('Post')){
            $Department_Name    =   addslashes(trim($this->request->data['Department_Name']));
            $submit             =   trim($this->request->data['submit']);

            if($submit =="Update"){
                $Department_Id  =   trim($this->request->data['Department_Id']);
                $updArr=array(
                    'Department_Name'=>"'".$Department_Name."'",
                    'Update_At'=>"'".date('Y-m-d H:i:s')."'",
                    'Update_By'=>"'".$User_Id."'",
                    );
                
                if($this->IsparkDepartmentMaster->updateAll($updArr,array('Department_Id'=>$Department_Id))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department update successfully.</span>');
                    $this->redirect(array('controller'=>'IsparkDepartments')); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department not update please try again later.</span>');
                    $this->redirect(array('controller'=>'IsparkDepartments')); 
                }
            }
            else{
                $data=array(
                    'Department_Name'=>$Department_Name
                    );
                
                $row=$this->IsparkDepartmentMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This department already exist in database.</span>');
                    $this->redirect(array('controller'=>'IsparkDepartments')); 
                }
                else{
                    $data['Create_By']=$User_Id;
                    $this->IsparkDepartmentMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This department create successfully.</span>');
                    $this->redirect(array('controller'=>'IsparkDepartments'));   
                }
                     
            }  
        }  
    }
    
    public function process(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $User_Id    = $this->Session->read('email');

        $this->set('DataArr',$this->IsparkProcessMaster->find('all'));
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->IsparkProcessMaster->find('first',array('conditions'=>array('Process_Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['IsparkProcessMaster']);  
        }

        if($this->request->is('Post')){
            $Process_Name       =   addslashes(trim($this->request->data['Process_Name']));
            $submit             =   trim($this->request->data['submit']);

            if($submit =="Update"){
                $Process_Id  =   trim($this->request->data['Process_Id']);
                $updArr=array(
                    'Process_Name'=>"'".$Process_Name."'",
                    'Update_At'=>"'".date('Y-m-d H:i:s')."'",
                    'Update_By'=>"'".$User_Id."'",
                    );
                
                if($this->IsparkProcessMaster->updateAll($updArr,array('Process_Id'=>$Process_Id))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This process update successfully.</span>');
                    $this->redirect(array('action'=>'process')); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This process not update please try again later.</span>');
                    $this->redirect(array('action'=>'process')); 
                }
            }
            else{
                $data=array(
                    'Process_Name'=>$Process_Name
                    );
                
                $row=$this->IsparkProcessMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already exist in database.</span>');
                    $this->redirect(array('action'=>'process')); 
                }
                else{
                    $data['Create_By']=$User_Id;
                    $this->IsparkProcessMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This process create successfully.</span>');
                    $this->redirect(array('action'=>'process'));    
                }
                     
            }  
        }  
    }
    
    
    public function delete_department(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $Department_Id  =   base64_decode($_REQUEST['id']);
            $this->IsparkDepartmentMaster->query("DELETE FROM `Ispark_Department_Master` WHERE Department_Id='$Department_Id'");
            $this->redirect(array('action'=>'index')); 
        }
    }
    
    public function delete_process(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $Process_Id  =   base64_decode($_REQUEST['id']);
            $this->IsparkProcessMaster->query("DELETE FROM `Ispark_Process_Master` WHERE Process_Id='$Process_Id'");
            $this->redirect(array('action'=>'process')); 
        }
    }
    
    
    
    
}
?>