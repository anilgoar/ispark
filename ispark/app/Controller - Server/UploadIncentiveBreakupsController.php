<?php
class UploadIncentiveBreakupsController extends AppController {
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

            $branchName     =   $this->request->data['UploadIncentiveBreakups']['branch_name'];
            $IncentiveType  =   $this->request->data['IncentiveType'];
            $SalaryMonth    =   date('Y-m-d',strtotime($this->request->data['SalaryMonth']));
            $y              =   date('Y',strtotime($this->request->data['SalaryMonth']));
            $m              =   date('m',strtotime($this->request->data['SalaryMonth']));
            
            $csv_file       =   $_FILES['UploadIncentive']['tmp_name'];
            $FileTye        =   $_FILES['UploadIncentive']['type'];
            $info           =   explode(".",$_FILES['UploadIncentive']['name']);
            $TotalExist     =   0;

            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn=count($filedata);
                    if($totalcolumn ==2){
              
                        $EmpArray=array();
                        $ExistEmpArray=array();
                        $ExistEmpArray1=array();
                        $ExistEmpArray2=array();
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            
                            if (in_array($data[0], $EmpArray)){
                                $ExistEmpArray[]=$data[0];
                            }
                            
                            $ExistCount = $this->UploadIncentiveBreakup->find('count',array('conditions'=>array('EmpCode'=>$data[0],'MONTH(SalaryMonth)'=>$m,'YEAR(SalaryMonth)'=>$y,'IncentiveType'=>$IncentiveType,'UploadType'=>'UploadIncentive','BranchName'=>$branchName)));
                            if ($ExistCount > 0){
                                $ExistEmpArray1[]=$data[0];
                            }
                            
                            
                            $EmpCode=$data[0];
                            $Amount=$data[1];
                           
                            $EmpDetails=$this->get_emp_details($data[0],$branchName);
                            if(empty($EmpDetails)){
                                $ExistEmpArray2[]=$data[0];
                            }
                            
                            $EmpName=$EmpDetails['EmpName'];
                            $CostCenter=$EmpDetails['CostCenter'];

                            if($list_value!=''){									
                                $list_value=$list_value.",('".$branchName."','".$CostCenter."','".$EmpCode."','".$EmpName."','".$IncentiveType."','".$Amount."','".$SalaryMonth."',NOW())";
                            }
                            else{
                                $list_value="('".$branchName."','".$CostCenter."','".$EmpCode."','".$EmpName."','".$IncentiveType."','".$Amount."','".$SalaryMonth."',NOW())";
                            } 
                                
                                $EmpArray[]=$data[0]; 
                        }
                        
                        if(!empty($ExistEmpArray)){
                            $imp=  implode(',',$ExistEmpArray);
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.$imp.' come multiple time.</span>');   
                        }
                        else if(!empty($ExistEmpArray2)){
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.implode(',',$ExistEmpArray2).' does not exist for selected branch.</span>');   
                        }
                        /*
                        else if(!empty($ExistEmpArray1)){
                            $imp1=  implode(',',$ExistEmpArray1);
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.$imp1.' already exist in database for '.date('M-Y',strtotime($SalaryMonth)).'.</span>');   
                        }*/
                        else{
                            $this->UploadIncentiveBreakup->query("DELETE FROM `upload_incentive_breakup` WHERE BranchName='$branchName' AND `IncentiveType`='$IncentiveType' AND DATE(SalaryMonth)='$SalaryMonth' AND `ApproveStatus` IS NULL");
                            $this->UploadIncentiveBreakup->query("INSERT INTO upload_incentive_breakup(`BranchName`,`CostCenter`,`EmpCode`,`EmpName`,`IncentiveType`,`Amount`,`SalaryMonth`,`ImportDate`) values $list_value"); 
                            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your csv upload successfully.</span>'); 
                        }  
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