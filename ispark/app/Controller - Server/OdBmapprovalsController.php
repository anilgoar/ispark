<?php
class OdBmapprovalsController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $branch_name=$this->request->data['OdBmapprovals']['branch_name'];
            $conditoin=array('ApproveFirst'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];

                if($SubmitType !=""){

                    if($SubmitType =="Approve"){
                        $status="Yes";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $this->OdApplyMaster->updateAll(array('ApproveFirst'=>"'".$status."'",'ApproveFirstDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                        }
                    }
                    else{
                        $this->Session->setFlash('Please select to approve or not approve od.'); 
                    }
                }
            }
            
            $this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,StartDate")));  
        }     
    }
    
}
?>