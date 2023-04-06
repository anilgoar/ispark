<?php
class UploadDeductionsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','UploadDeductionMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','upload');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $fieldArr=array();
        //,'Status'=>1
        $branchName = $this->Session->read('branch_name');
        $data = $this->Masjclrentry->find('all',array('conditions'=>array('BranchName'=>$branchName),'group' =>array('CostCenter')));
        $SalaryMonth=date('Y-m', strtotime('-1 month', time()));
        foreach($data as $val){
            $fieldArr[]=array(
                'ClientName'=>$val['Masjclrentry']['ClientName'],
                'CostCenter'=>$val['Masjclrentry']['CostCenter'],
                'TotalEmp'=>$this->total_employees($val['Masjclrentry']['CostCenter'],$branchName),
                'Status'=>$this->process_status($val['Masjclrentry']['CostCenter'],$SalaryMonth,$branchName),
            );
        }
        $this->set('fieldArr',$fieldArr);
    }
    
    public function upload(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');

        if(isset($_REQUEST['CSN'])){
            $cosc = base64_decode($_REQUEST['CSN']);
            $this->set('empArr',array('cost_center'=>$cosc,'TotalEmp'=>$this->total_employees($cosc,$branchName)));
        }
        
        if($this->request->is('Post')){
            
            $CostCenter=$this->request->data['CostCenter'];

            $csv_file=$_FILES['UploadDeduction']['tmp_name'];
            $FileTye=$_FILES['UploadDeduction']['type'];
            $info=explode(".",$_FILES['UploadDeduction']['name']);

            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn=count($filedata);
                    if($totalcolumn ==9){
                        $SalaryMonth=date('Y-m', strtotime(date('Y-m')." -1 month"));
                        //$SalaryMonth=date('Y-m', strtotime('-1 month', time()));
                        $ExistCount = $this->UploadDeductionMaster->find('count',array('conditions'=>array('CostCenter'=>$CostCenter,'SalaryMonth'=>$SalaryMonth,'BranchName'=>$branchName)));
                        
                        if($ExistCount < 1){
                            $ExistCost=array();
                            $EmpArray=array();
                            $ExistEmpArray=array();
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $cs=$this->get_cost_center($data[0],$branchName);
                                 
                                if($cs !=$CostCenter){
                                    $ExistCost[]=$data[0];
                                }
                                
                                if (in_array($data[0], $EmpArray)){
                                    $ExistEmpArray[]=$data[0];
                                }

                                $EmpCode=$data[0];
                                $field1=$data[1];
                                $field2=$data[2];
                                $field3=$data[3];
                                $field4=$data[4];
                                $field5=$data[5];
                                $field6=$data[6];
                                $field7=$data[7];
                                $field8=$data[8];
                                $EmpName=$this->get_emp_name($data[0],$branchName,$CostCenter);
                                
                                if($list_value!=''){									
                                    $list_value=$list_value.",('".$branchName."','".$CostCenter."','".$EmpCode."','".$EmpName."','".$SalaryMonth."','".$field1."','".$field2."','".$field3."','".$field4."','".$field5."','".$field6."','".$field7."','".$field8."','Uploaded',NOW())";
                                }
                                else{
                                    $list_value="('".$branchName."','".$CostCenter."','".$EmpCode."','".$EmpName."','".$SalaryMonth."','".$field1."','".$field2."','".$field3."','".$field4."','".$field5."','".$field6."','".$field7."','".$field8."','Uploaded',NOW())";
                                } 
                                
                                $EmpArray[]=$data[0]; 
                            }
                            
                            if(!empty($ExistCost)){
                                $imp=  implode(',',$ExistCost);
                                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.$imp.' not exist in this cost center.</span>');   
                            }
                            else if(!empty($ExistEmpArray)){
                                $imp1=  implode(',',$ExistEmpArray);
                                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.$imp1.' come multiple time in this cost center.</span>');   
                            }
                            else{
                                $this->UploadDeductionMaster->query("INSERT INTO upload_deduction(`BranchName`,`CostCenter`,`EmpCode`,`EmpName`,`SalaryMonth`,`MobileDeduction`,`ShortCollection`,`AssetRecovery`,`Insurance`,`ProfessionalTax`,`LeaveDeduction`,`OthersDeduction`,`Remarks`,`ProcessStatus`,`ImportDate`) values $list_value"); 
                                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your csv upload successfully.</span>'); 
                            }
                              
                        }
                        else{
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This csv data already exists in database.</span>'); 
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
            $this->redirect(array('action'=>'upload','?'=>array('CSN'=>base64_encode($CostCenter))));   
        }     
    }
    
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('Status'=>1,'BranchName'=>$branchName,'CostCenter'=>$CostCenter)));
    }
    
    public function process_status($CostCenter,$SalaryMonth,$branchName){
        $data = $this->UploadDeductionMaster->find('first',array('fields'=>array('ProcessStatus'),'conditions'=>array('CostCenter'=>$CostCenter,'SalaryMonth'=>$SalaryMonth,'BranchName'=>$branchName)));
        return $data['UploadDeductionMaster']['ProcessStatus'];
    }
    
    // 'Status'=>1,
    public function get_cost_center($EmpCode,$branchName){
        $data=$this->Masjclrentry->find('first', array('fields'=>array('CostCenter'),'conditions' => array('EmpCode'=>$EmpCode,'BranchName'=>$branchName),'group' =>array('CostCenter')));
        return $data['Masjclrentry']['CostCenter'];
    }
    //'Status'=>1,
    public function get_emp_name($EmpCode,$branchName,$CostCenter){
        $data=$this->Masjclrentry->find('first', array('fields'=>array('EmpName'),'conditions' => array('EmpCode'=>$EmpCode,'BranchName'=>$branchName,'CostCenter'=>$CostCenter)));
        return $data['Masjclrentry']['EmpName'];
    }
    
}
?>