<?php
class DesignationNameMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','DepartmentNameMaster','DesignationNameMaster');
        
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
        $DepList=$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1)));
        $this->set('DepList',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1))));
        
        $DataArr=$this->DesignationNameMaster->find('all');
        $this->set('DataArr',$DataArr);
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->DesignationNameMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['DesignationNameMaster']);  
        }
        
        if($this->request->is('Post')){
            
            $Department         =   addslashes(trim($this->request->data['Department']));
            $Band               =   addslashes(trim($this->request->data['Band']));
            $Designation        =   addslashes(trim($this->request->data['Designation']));
            $OverSalDaysAllowed =   addslashes(trim($this->request->data['OverSalDaysAllowed']));
            $InsuranceAllowed   =   addslashes(trim($this->request->data['InsuranceAllowed']));
            $InsuranceAmount    =   addslashes(trim($this->request->data['InsuranceAmount']));
            $submit             =   trim($this->request->data['submit']);
            $DesignationId      =   trim($this->request->data['DesignationId']);
            $Status             =   trim($this->request->data['Status']);
            
            if($submit =="Update"){
                $updArr=array(
                    'Department'=>"'".$Department."'",
                    'Band'=>"'".$Band."'",
                    'Designation'=>"'".$Designation."'",
                    'OverSalDaysAllowed'=>"'".$OverSalDaysAllowed."'",
                    'InsuranceAllowed'=>"'".$InsuranceAllowed."'",
                    'InsuranceAmount'=>"'".$InsuranceAmount."'",
                    'Status'=>"'".$Status."'",
                    'UpdateDate'=>"'".date('Y-m-d H:i:s')."'",
                    );
                
                if($this->DesignationNameMaster->updateAll($updArr,array('Id'=>$DesignationId))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This designation update successfully.</span>');
                    $this->redirect(array('controller'=>'DesignationNameMasters')); 
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This designation not update please try again later.</span>');
                    $this->redirect(array('controller'=>'DesignationNameMasters')); 
                }
            }
            else{
                $data=array(
                    'Department'=>$Department,
                    'Band'=>$Band,
                    'Designation'=>$Designation,
                    'OverSalDaysAllowed'=>$OverSalDaysAllowed,
                    'InsuranceAllowed'=>$InsuranceAllowed,
                    'InsuranceAmount'=>$InsuranceAmount,
                );
                
                $row=$this->DesignationNameMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This designation already exist in database.</span>');
                    $this->redirect(array('controller'=>'DesignationNameMasters')); 
                }
                else{
                    $this->DesignationNameMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This designation create successfully.</span>');
                    $this->redirect(array('controller'=>'DesignationNameMasters'));   
                }
                     
            }  
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->DesignationNameMaster->query("DELETE FROM `incentive_name_master` WHERE Id='{$_REQUEST['id']}'");
            $this->redirect(array('action'=>'index')); 
        }
     
    }
    
    
}
?>