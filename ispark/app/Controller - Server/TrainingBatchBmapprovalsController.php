<?php
class TrainingBatchBmapprovalsController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','TrainingBatchMaster');
        
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
   
            $branch_name=$this->request->data['TrainingBatchBmapprovals']['branch_name'];
            $conditoin=array('ApproveFirst'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];
                $ApproveFirstRemarks=$this->request->data['ApproveFirstRemarks'];

                if($SubmitType !=""){

                    if($SubmitType =="Approve"){
                        $status="Yes";
                        $TrainingStatus="Initiated";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                       $TrainingStatus="Pending";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $this->TrainingBatchMaster->updateAll(array('ApproveFirstRemarks'=>"'".$ApproveFirstRemarks."'",'TrainingStatus'=>"'".$TrainingStatus."'",'ApproveFirst'=>"'".$status."'",'ApproveFirstDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-size:14px;" >Please check option to approve or not approve training batch.</span>'); 
                    }
                }
            }
            
            $this->set('OdArr',$this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin,'order'=>"CreateDate"))); 
            //$this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,CreateDate")));  
        }     
    }
    
}
?>