<?php
class IncentiveNameMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','IncentiveNameMaster');
        
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
        
        if(isset($_REQUEST['branchname']) && $_REQUEST['branchname'] !=""){
            
            $DataArr=$this->IncentiveNameMaster->find('all',array('conditions'=>array('BranchName'=>$_REQUEST['branchname'])));
            
           // print_r($DataArr);die;
           
            $this->set('branch',$_REQUEST['branchname']);
            $this->set('DataArr',$DataArr);
        }
        
        
        //$DataArr=$this->IncentiveNameMaster->find('all',array('conditions'=>array('BranchName'=>$branchName)));
        //$this->set('DataArr',$DataArr);
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->IncentiveNameMaster->find('first',array('conditions'=>array('BranchName'=>$branchName,'Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['IncentiveNameMaster']);  
        }
        
        if($this->request->is('Post')){
             
            $branch_name    =   $this->request->data['IncentiveNameMasters']['branch_name'];
            $IncentiveName  =   trim($this->request->data['IncentiveName']);
            $submit         =   trim($this->request->data['submit']);
            $IncentiveId    =   trim($this->request->data['IncentiveId']);
            
            if($submit =="Update"){
                $updArr=array(
                    'BranchName'=>"'".$branch_name."'",
                    'IncentiveName'=>"'".$IncentiveName."'",
                    );
                
                if($this->IncentiveNameMaster->updateAll($updArr,array('Id'=>$IncentiveId,'BranchName'=>$branch_name))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data update successfully.</span>');
                    $this->redirect(array('action'=>'index','?'=>array('id'=>base64_encode($IncentiveId)))); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data not update please try again later.</span>');
                    $this->redirect(array('action'=>'index','?'=>array('id'=>base64_encode($IncentiveId)))); 
                }
            }
            else{
                $data=array(
                    'BranchName'=>$branch_name,
                    'IncentiveName'=>$IncentiveName,
                );
                
                $row=$this->IncentiveNameMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This data already exist in database.</span>');
                    $this->redirect(array('action'=>'index')); 
                }
                else{
                    $this->IncentiveNameMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data save successfully.</span>');
                    $this->redirect(array('action'=>'index'));   
                }
                     
            }  
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->IncentiveNameMaster->query("DELETE FROM `incentive_name_master` WHERE Id='{$_REQUEST['id']}'");
            $this->redirect(array('action'=>'index')); 
        }
     
    }
    
    
}
?>