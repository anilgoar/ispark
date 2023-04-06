<?php
class UpdateEmpDetailsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_incentive_type');
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

        if($this->request->is('Post')){
            
            $UploadType =   $this->request->data['UploadType'];
            $csv_file   =   $_FILES['UploadIncentive']['tmp_name'];
            $FileTye    =   $_FILES['UploadIncentive']['type'];
            $info       =   explode(".",$_FILES['UploadIncentive']['name']);
            $TotalExist =   0;

            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn=count($filedata);
                    
                    if($totalcolumn ==2){
                        
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $EmpCode=$data[0];
                            $Value=$data[1];
                            
                            if($UploadType =="EPF"){
                                $Field="EPFNo";
                            }
                            else if($UploadType =="ESIC"){
                                $Field="ESICNo";
                            }
                            if($UploadType =="UAN"){
                                $Field="UAN";
                            }
                            
                            $this->Masjclrentry->query("update `masjclrentry` set `$Field`='$Value' where `EmpCode`='$EmpCode'");
                        }
                        
                        
                      $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Employee details update successfully.</span>'); 
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>'); 
                    }
		}
            }
            else{
		$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>'); 
            }
            $this->redirect(array('action'=>'index'));   
        }     
    }
    
    public function get_incentive_type(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){ 
            
            $data=$this->IncentiveNameMaster->find('list',array('fields'=>array('IncentiveName'),'conditions'=>array('BranchName'=>$_REQUEST['BranchName']))); 
            
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
    
    public function get_emp_details($EmpCode,$branchName){
        //$data=$this->Masjclrentry->find('first', array('fields'=>array('EmpName','CostCenter'),'conditions' => array('EmpLocation'=>'InHouse','EmpCode'=>$EmpCode,'Status'=>1,'BranchName'=>$branchName)));
        $data=$this->Masjclrentry->find('first', array('fields'=>array('EmpName','CostCenter'),'conditions' => array('EmpCode'=>$EmpCode,'BranchName'=>$branchName)));
        return $data['Masjclrentry'];
    }
        
}
?>