<?php
class OdApprovalDisapprovalsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OdApplyMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','odapproval','oddisapproval');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
    }
    
    public function odapproval(){
        
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            
            $branch_name=$this->request->data['OdApprovalDisapprovals']['branch_name'];
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
                            $this->OdApplyMaster->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                            
                            if($SubmitType =="Approve"){
                                $AttDetArr=$this->OdApplyMaster->find('first',array('fields'=>array('EmpCode','date(StartDate) as stdate','date(EndDate) as endate'),'conditions'=>array('Id'=>$Id,'ApproveSecond'=>'Yes')));
                                $emcode=$AttDetArr['OdApplyMaster']['EmpCode'];
                                $stdate=$AttDetArr[0]['stdate'];
                                $endate=$AttDetArr[0]['endate'];
                           
                                $this->OdApplyMaster->query("UPDATE `Attandence` SET `Status`='OD' WHERE EmpCode='$emcode' AND DATE(AttandDate) BETWEEN '$stdate' AND '$endate'");
                            }
                            
                        }
                        $this->Session->setFlash('You request save successfully.'); 
                    }
                    else{
                        $this->Session->setFlash('Please select to approve or not approve od.'); 
                    }
                }
            }
            
            $conditoin=array('ApproveFirst'=>'Yes','ApproveSecond'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            
            $this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,StartDate")));  
        }     
    }
    
    public function oddisapproval(){
        
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $searchEmp=$this->request->data['searchEmp'];
            $branch_name=$this->request->data['OdApprovalDisapprovals']['branch_name'];
            
            $DiscardReason= $this->request->data['DiscardReason'];
            
            if($this->request->data['Submit'] =="Discard"){
                $status="No";
                $status1="Yes";
                if(isset($this->request->data['check'])){
                    $OdIdArr=$this->request->data['check'];
                    foreach ($OdIdArr as $Id){
                        
                        $this->OdApplyMaster->updateAll(array('DiscardStatus'=>"'".$status1."'",'DiscardReason'=>"'".$DiscardReason."'",'DiscardDate'=>"'".date('Y-m-d H:i:s')."'",'ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branch_name));
                        
                        $AttDetArr=$this->OdApplyMaster->find('first',array('fields'=>array('CurrentStatus','EmpCode','date(StartDate) as stdate','date(EndDate) as endate'),'conditions'=>array('Id'=>$Id)));
                        $CurrentStatus=$AttDetArr['OdApplyMaster']['CurrentStatus'];
                        $emcode=$AttDetArr['OdApplyMaster']['EmpCode'];
                        $stdate=$AttDetArr[0]['stdate'];
                        $endate=$AttDetArr[0]['endate'];
                        
                        $expSt=  explode(',', $CurrentStatus);
                        
                        foreach($expSt as $val){
                            $StArr  =   explode('_', $val);
                            $stid   =   $StArr[0];
                            $stvl   =   $StArr[1];
                            
                            $this->OdApplyMaster->query("UPDATE `Attandence` SET `Status`='$stvl' WHERE EmpCode='$emcode' AND BranchName='$branch_name' 
                            AND DATE(AttandDate) BETWEEN '$stdate' AND '$endate' AND Id='$stid'");
                        }
                        
                        $this->OdApplyMaster->query("insert into od_apply_master_history select * from od_apply_master WHERE Id='$Id'");
                        $this->OdApplyMaster->query("DELETE FROM od_apply_master WHERE Id='$Id'");
                        
                    }
                    $this->Session->setFlash('<span style="color:green;" >You request save successfully.</span>'); 
                }
                else{
                    $this->Session->setFlash('<span style="color:red;" >Please select record to discard.</span>'); 
                }
            }
            
            $this->set('searchEmp',$searchEmp);
            $this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$searchEmp,'ApproveFirst'=>'Yes','ApproveSecond'=>'Yes'))));  
        }     
    }
    
}
?>