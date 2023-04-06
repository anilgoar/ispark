<?php
class DeleteFieldAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OdApplyMaster','FieldAttendanceMaster','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_cost_center');
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
 
        $this->set('dateArr',$this->Masattandance->query("SELECT AttandDate FROM `Attandence` WHERE Attanddate IS NOT NULL AND (MONTH(AttandDate) = MONTH(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) OR  MONTH(AttandDate) = MONTH(CURDATE())) GROUP BY Attanddate ORDER BY Attanddate DESC"));
        
        if($this->request->is('Post')){
            $CostCenter=$this->request->data['CostCenter'];
            $DeleteDate=$this->request->data['DeleteDate'];
            $branch_name=$this->request->data['delete-field-attendance']['branch_name'];
            $exist=$this->FieldAttendanceMaster->find('count', array('conditions' => array('BranchName'=>$branch_name,'CostCenter'=>$CostCenter,'DATE(AttandDate)'=>$DeleteDate)));
            $ProcessDate      =   date('Y-m',strtotime(trim(addslashes($DeleteDate))));
            
            $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$branch_name,'CostCenter'=>$CostCenter,'ProcessMonth'=>$ProcessDate)));
            //$ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>'HEAD OFFICE','CostCenter'=>'BSS-OTHERS','ProcessMonth'=>'2018-01')));
             
            if($ProAttArr > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
                $this->redirect(array('action'=>'index'));
            }
            else{
                if($exist > 0){
                    $this->FieldAttendanceMaster->query("DELETE FROM `FieldAttandence` WHERE BranchName='$branch_name' AND CostCenter='$CostCenter' AND DATE(AttandDate)>='$DeleteDate'");
                    $this->Session->setFlash('<span style="color:green;" >This field attendance delete successfully.</span>'); 
                }
                else{
                    $this->Session->setFlash('<span style="color:red;" >This attendance mark not exist.</span>'); 
                }
            }
            
            $this->redirect(array('action'=>'index'));   
        }
    }
    
    public function get_cost_center(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){ 
            $branchName = $_REQUEST['BranchName'];
            $data=$this->Masjclrentry->find('list',array('fields'=>array('CostCenter'),'conditions'=>array('EmpLocation'=>'Field','BranchName'=>$branchName,'Status'=>1),'group' =>array('CostCenter'))); 
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach($data as $row){
                    echo "<option value='$row'>$row</option>";
                }
            }
            else{
                echo "";
            }
        }
        die;  
        
    } 
}
?>