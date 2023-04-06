<?php
class ProcessDeductionsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','UploadDeductionMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','process','report');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'))));
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
        $fieldArr1=array();
        $fieldArr2=array();
        
        if($this->request->is('Post')){
            
            $branch_name=$this->request->data['ProcessDeductions']['branch_name'];
            $data = $this->Masjclrentry->find('all',array('conditions'=>array('BranchName'=>$branch_name,'Status'=>1),'group' =>array('CostCenter')));
            $SalaryMonth=date('Y-m', strtotime(date('Y-m')." -1 month"));

            foreach($data as $val){
                $UploadStatus=$this->process_status($val['Masjclrentry']['CostCenter'],$SalaryMonth,$val['Masjclrentry']['BranchName']);
                if($UploadStatus ==""){
                    $fieldArr[]=array(
                        'ClientName'=>$val['Masjclrentry']['ClientName'],
                        'CostCenter'=>$val['Masjclrentry']['CostCenter'],
                        'TotalEmp'=>$this->total_employees($val['Masjclrentry']['CostCenter'],$val['Masjclrentry']['BranchName']),
                    );
                }
                if($UploadStatus =="Uploaded"){
                    $fieldArr1[]=array(
                        'BranchName'=>$val['Masjclrentry']['BranchName'],
                        'ClientName'=>$val['Masjclrentry']['ClientName'],
                        'CostCenter'=>$val['Masjclrentry']['CostCenter'],
                        'TotalEmp'=>$this->total_employees($val['Masjclrentry']['CostCenter'],$val['Masjclrentry']['BranchName']),
                    );
                }
                if($UploadStatus =="Processed"){
                    $fieldArr2[]=array(
                        'ClientName'=>$val['Masjclrentry']['ClientName'],
                        'CostCenter'=>$val['Masjclrentry']['CostCenter'],
                        'TotalEmp'=>$this->total_employees($val['Masjclrentry']['CostCenter'],$val['Masjclrentry']['BranchName']),
                    );
                }
            }   
        }
        
        $this->set('fieldArr',$fieldArr);
        $this->set('fieldArr1',$fieldArr1);
        $this->set('fieldArr2',$fieldArr2);
    }
    
    public function process(){
        $this->layout='home';
      
        if(isset($_REQUEST['CSN']) && isset($_REQUEST['BRN'])){
            
            $branchName = base64_decode($_REQUEST['BRN']);
            $CostCenter = base64_decode($_REQUEST['CSN']);
            
            $SalaryMonth=date('Y-m', strtotime(date('Y-m')." -1 month"));
          
            $data = $this->UploadDeductionMaster->find('all',array('conditions'=>array('ProcessStatus'=>'Uploaded','CostCenter'=>$CostCenter,'SalaryMonth'=>$SalaryMonth,'BranchName'=>$branchName)));
             
            $this->set('fieldArr',$data);
            $this->set('headArr',array('CostCenter'=>$CostCenter,'BranchName'=>$branchName));
        }
        
        if($this->request->is('Post')){
            
            $BranchName=$this->request->data['BranchName'];
            $CostCenter=$this->request->data['CostCenter'];
            $SalaryMonth=date('Y-m', strtotime(date('Y-m')." -1 month"));
            $Remarks=$this->request->data['Remarks'];
            $Submit=$this->request->data['Submit'];
            
            if($Submit =="Proceed"){
                $this->UploadDeductionMaster->query("UPDATE `upload_deduction` SET `ProcessStatus`='Processed',UpdateDate=NOW(),DeductionRemarks='$Remarks' WHERE `BranchName`='$BranchName' AND `CostCenter`='$CostCenter' AND `SalaryMonth`='$SalaryMonth' AND `ProcessStatus`='Uploaded'");
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your deduction update successfully</span>'); 
            }
            else if($Submit =="Reject"){
                $this->UploadDeductionMaster->query("DELETE FROM `upload_deduction` WHERE `BranchName`='$BranchName' AND `CostCenter`='$CostCenter' AND `SalaryMonth`='$SalaryMonth' AND `ProcessStatus`='Uploaded'");
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your deduction reject successfully</span>'); 
            }
            
            $this->redirect(array('action'=>'process','?'=>array('CSN'=>base64_encode($CostCenter),'BRN'=>base64_encode($BranchName))));   
        }     
    }
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=ProcessDeductonReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $branchName = base64_decode($_REQUEST['BRN']);
        $CostCenter = base64_decode($_REQUEST['CSN']);
        $SalaryMonth=date('Y-m', strtotime(date('Y-m')." -1 month"));
        
        $data = $this->UploadDeductionMaster->find('all',array('conditions'=>array('ProcessStatus'=>'Uploaded','CostCenter'=>$CostCenter,'SalaryMonth'=>$SalaryMonth,'BranchName'=>$branchName)));
        ?>
        <table border="1" >          
            <tr>
                <th>EmpCode</th>
                <th>EmpName</th>
                <th>MobileDeduction</th>
                <th>ShortCollection</th>
                <th>AssetRecovery</th>
                <th>ProfessionalTax</th>
                <th>LeaveDeduction</th>
                <th>Insurance</th>
                <th>OtherDeduction</th>
                <th>Remarks</th>
            </tr>             
            <?php
            $MobDed=0;
            $SotCol=0;
            $AstRec=0;
            $PofTax=0;
            $LeaDed=0;
            $Insura=0;
            $OthDed=0;
            foreach ($data as $val){
                $MobDed=$MobDed+$val['UploadDeductionMaster']['MobileDeduction'];
                $SotCol=$SotCol+$val['UploadDeductionMaster']['ShortCollection'];
                $AstRec=$AstRec+$val['UploadDeductionMaster']['AssetRecovery'];
                $PofTax=$PofTax+$val['UploadDeductionMaster']['ProfessionalTax'];
                $LeaDed=$LeaDed+$val['UploadDeductionMaster']['LeaveDeduction'];
                $Insura=$Insura+$val['UploadDeductionMaster']['Insurance'];
                $OthDed=$OthDed+$val['UploadDeductionMaster']['OthersDeduction'];
            ?>
            <tr>
                           
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['EmpCode'];?></td>
                            <td><?php echo $val['UploadDeductionMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['MobileDeduction'] !=""){ echo $val['UploadDeductionMaster']['MobileDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ShortCollection'] !=""){ echo $val['UploadDeductionMaster']['ShortCollection'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['AssetRecovery'] !=""){ echo $val['UploadDeductionMaster']['AssetRecovery'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ProfessionalTax'] !=""){ echo $val['UploadDeductionMaster']['ProfessionalTax'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['LeaveDeduction'] !=""){ echo $val['UploadDeductionMaster']['LeaveDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['Insurance'] !=""){ echo $val['UploadDeductionMaster']['Insurance'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['OthersDeduction'] !=""){ echo $val['UploadDeductionMaster']['OthersDeduction'];}else{echo 0;}?></td>
                            
                            
                            <td><?php echo $val['UploadDeductionMaster']['Remarks'];?></td>
                        </tr>
            <?php }?>
            <tr>
                <td></td>
                <td style="text-align: center;">Total</td>
                <td style="text-align: center;"><?php echo $MobDed;?></td>
                <td style="text-align: center;"><?php echo $SotCol;?></td>
                <td style="text-align: center;"><?php echo $AstRec;?></td>
                <td style="text-align: center;"><?php echo $PofTax;?></td>
                <td style="text-align: center;"><?php echo $LeaDed;?></td>
                <td style="text-align: center;"><?php echo $Insura;?></td>
                <td style="text-align: center;"><?php echo $OthDed;?></td>
                <td style="text-align: center;"><?php echo ($MobDed+$SotCol+$AstRec+$PofTax+$LeaDed+$Insura+$OthDed);?></td>
            </tr>
       </table>
        <?php
        die;
    }
    
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('Status'=>1,'BranchName'=>$branchName,'CostCenter'=>$CostCenter)));
    }
    
    public function process_status($CostCenter,$SalaryMonth,$branchName){
        $data = $this->UploadDeductionMaster->find('first',array('fields'=>array('ProcessStatus'),'conditions'=>array('CostCenter'=>$CostCenter,'SalaryMonth'=>$SalaryMonth,'BranchName'=>$branchName),'group' =>array('ProcessStatus')));
        return $data['UploadDeductionMaster']['ProcessStatus'];
    }
    
    public function get_cost_center($EmpCode,$branchName){
        $data=$this->Masjclrentry->find('first', array('fields'=>array('CostCenter'),'conditions' => array('EmpCode'=>$EmpCode,'Status'=>1,'BranchName'=>$branchName),'group' =>array('CostCenter')));
        return $data['Masjclrentry']['CostCenter'];
    }
    
}
?>