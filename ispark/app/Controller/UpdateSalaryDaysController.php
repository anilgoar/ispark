<?php
class UpdateSalaryDaysController extends AppController {
    public $uses = array('Addbranch','Masjclrentry');
        
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
            
            $SalMonth 	=   $this->request->data['UploadType'];
			$exp		=	explode("-",$SalMonth);
			$y			=	$exp[0];
			$m			=	$exp[1];
			$MaxSalDays	=   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $csv_file   =   $_FILES['UploadIncentive']['tmp_name'];
            $FileTye    =   $_FILES['UploadIncentive']['type'];
            $info       =   explode(".",$_FILES['UploadIncentive']['name']);
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 100000, ",");
                    $totalcolumn=count($filedata);
                    
                    if($totalcolumn ==5){
                        
                        while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
                            $EmpCode	=	$data[0];
							$EmpName	=	$data[1];
							$BranchName	=	$data[2];
							$CostCenter	=	$data[3];
							$SalDays	=	$data[4];
							
							$exist	=	$this->Masjclrentry->query("SELECT * FROM `Add_Testdata` WHERE EmpCode='$EmpCode' AND SalMonth='$SalMonth'");
			
							if(empty($exist)){
								$this->Masjclrentry->query("INSERT INTO `Add_Testdata` SET EmpCode='$EmpCode',EmpName='$EmpName',BranchName='$BranchName',CostCenter='$CostCenter',MaxSalDays='$MaxSalDays',SalDays='$SalDays',SalMonth='$SalMonth'");
							}
							else{
								$this->Masjclrentry->query("UPDATE `Add_Testdata` SET EmpCode='$EmpCode',EmpName='$EmpName',BranchName='$BranchName',CostCenter='$CostCenter',MaxSalDays='$MaxSalDays',SalDays='$SalDays',SalMonth='$SalMonth',UpdateDate=NOW() WHERE EmpCode='$EmpCode' AND SalMonth='$SalMonth'");
                                                                //$this->Masjclrentry->query("UPDATE `LoanMaster` SET `DeductedAmount`='0',`PendingAmount`=Amount,`LastUpdateDate`='' WHERE EmpCode='$EmpCode' and last_day(StartDate)='$UpdateId'");
							}
						}
                        
						$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Salary days save successfully.</span>'); 
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
    
        
}
?>