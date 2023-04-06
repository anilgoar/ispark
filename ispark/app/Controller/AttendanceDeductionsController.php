<?php
class AttendanceDeductionsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','UploadDeductionMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_cost_center','get_deduction');
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
        
        $fieldArr=array();
        
        if($this->request->is('Post')){
             
            $ActionFor      =   $this->request->data['AttendanceDeductions']['ActionFor'];
            $Action         =   $this->request->data['Action'];
            $branch_name    =   $this->request->data['AttendanceDeductions']['branch_name'];
            $CostCenter     =   $this->request->data['CostCenter'];
            $SalaryMonth    =   date('Y-m', strtotime('-1 month', time()));
            $check          =   $this->request->data['check'];
            $Submit         =   $this->request->data['Submit'];
                     
            if($ActionFor =="Deduction" && $Submit =="Save"){
                foreach($check as $row){
                    $this->UploadDeductionMaster->query("DELETE FROM `upload_deduction` WHERE `BranchName`='$branch_name' AND `CostCenter`='$row' AND `SalaryMonth`='$SalaryMonth'");
                }
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your record discard successfully.</span>');
                $this->redirect(array('action'=>'index'));
            }
            
              
        }   
    }
    
    public function get_deduction(){
        $this->layout='ajax';
        if(isset($_REQUEST['ActionFor']) && isset($_REQUEST['BranchName']) && isset($_REQUEST['CostCenter'])){
            
            $CostCenter=$_REQUEST['CostCenter'];
            $branch_name=$_REQUEST['BranchName'];
            $ActionFor=$_REQUEST['ActionFor'];
            $SalaryMonth=date('Y-m', strtotime('-1 month', time()));
             
            $conditoin=array('SalaryMonth'=>$SalaryMonth,'BranchName'=>$branch_name);
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            $data = $this->UploadDeductionMaster->find('all',array('conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            //print_r($data);die;
            
            if(!empty($data)){
                echo '<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >';
                echo '<thead>';
                echo '<tr>';
                echo '<th style="text-align:left;" >Check</th>';
                echo '<th>Branch</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Current Status</th>';
                echo '<th>Download Status</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($data as $val){
                    echo '<tr>';
                    echo '<td><input class="checkbox" type="checkbox" value="'.$val['UploadDeductionMaster']['CostCenter'].'" name="check[]"></td>';
                    echo '<td>'.$val['UploadDeductionMaster']['BranchName'].'</td>';
                    echo '<td>'.$val['UploadDeductionMaster']['CostCenter'].'</td>';
                     echo '<td>'.$val['UploadDeductionMaster']['ProcessStatus'].'</td>';
                    echo '<td>No</td>';
                    echo '</tr>';
                }
                echo '</tbody>';           
                echo '</table>';
            }
            else{
                echo "";
            }
        }
        die;    
    } 
    
    public function get_cost_center(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){ 
            $branchName = $_REQUEST['BranchName'];
            $data=$this->Masjclrentry->find('list',array('fields'=>array('CostCenter'),'conditions'=>array('EmpLocation'=>'InHouse','BranchName'=>$branchName,'Status'=>1),'group' =>array('CostCenter'))); 
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach($data as $row)
                    {
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