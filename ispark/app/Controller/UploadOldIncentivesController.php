<?php
class UploadOldIncentivesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_incentive_type','get_leave_details','old_incentive_details');
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
        
       $curYear = date('Y');
        $this->set('curYear',$curYear);
        
        
        if($this->request->is('Post')){
           
            $branchName         =   $this->request->data['UploadOldIncentives']['branch_name'];
            $EmpCode            =   $this->request->data['EmpCode'];
            $IncentiveType      =   $this->request->data['IncentiveType'];
            $IncentiveAmount    =   $this->request->data['IncentiveAmount'];
            //$SalaryMonth        =   date('Y-m-d',strtotime($this->request->data['IncentiveMonth']));
            //$IncentiveMonth     =   $this->request->data['IncentiveMonth'];
            $SalaryMonth    = $this->request->data['IncentiveMonth'];
            
            $date = DateTime::createFromFormat('d-M-Y', "01-$SalaryMonth");
            $SalaryMonth = $date->format('Y-m-t'); 
            
            
            
            
            $y                  =   date('Y',strtotime($this->request->data['IncentiveMonth']));
            $m                  =   date('m',strtotime($this->request->data['IncentiveMonth']));
            $Remarks            =   $this->request->data['Remarks'];
            $EmpDetails         =   $this->get_emp_details($EmpCode,$branchName);
            $EmpName            =   $EmpDetails['EmpName'];
            $CostCenter         =   $EmpDetails['CostCenter'];
            $ExistCount         =   $this->UploadIncentiveBreakup->find('count',array('conditions'=>array('EmpCode'=>$EmpCode,'MONTH(SalaryMonth)'=>$m,'YEAR(SalaryMonth)'=>$y,'IncentiveType'=>$IncentiveType,'UploadType'=>'OldIncentive','BranchName'=>$branchName)));
            
            if($ExistCount > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Emp Code '.$EmpCode.' already exist in database for '.date('M-Y',strtotime($SalaryMonth)).'.</span>');   
                $this->redirect(array('action'=>'index'));   
            }
            else{
                $data=array(
                    'BranchName'=>$branchName,
                    'CostCenter'=>$CostCenter,
                    'EmpCode'=>$EmpCode,
                    'EmpName'=>$EmpName,
                    'IncentiveType'=>$IncentiveType,
                    'Amount'=>$IncentiveAmount,
                    'SalaryMonth'=>$SalaryMonth,
                    'Remarks'=>$Remarks,
                    'UploadType'=>'OldIncentive',
                    'ImportDate'=>date('Y-m-d'),
                );
                
                if($this->UploadIncentiveBreakup->save($data)){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your record save successfully.</span>'); 
                    $this->redirect(array('action'=>'index'));  
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your record not save please try again later.</span>'); 
                    $this->redirect(array('action'=>'index')); 
                }
            }
        }     
    }
    
    public function get_leave_details(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $data = $this->Masjclrentry->find('first',array(
                'conditions'=>array(
                    'Status'=>0,
                    'EmpLocation'=>'InHouse',
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$_REQUEST['EmpCode'],
                    )
                )); 
          
            if(!empty($data)){
            ?>
            <table class = "table table-striped table-hover  responstable">     
                <thead>
                    <tr>
                        <th style="text-align: center;" >SNo</th>
                        <th style="text-align: center;">EmpCode</th>
                        <th style="text-align: center;">EmpName</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">CostCenter</th>
                        <th style="text-align: center;">DOJ</th>
                        <th style="text-align: center;">LeftDate</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: center;"><?php echo $data['Masjclrentry']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $data['Masjclrentry']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $data['Masjclrentry']['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo $data['Masjclrentry']['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($data['Masjclrentry']['DOJ'])) ;?></td>
                        <td style="text-align: center;"><?php echo isset($data['Masjclrentry']['ResignationDate'])? date('d-M-Y',strtotime($data['Masjclrentry']['ResignationDate'])):'';?></td>
                    </tr>
                </tbody>   
            </table>
            <?php   
            }
            else{
                echo "";
            }   
        }
        die;  
    }
    
    public function old_incentive_details(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $data = $this->UploadIncentiveBreakup->find('all',array(
                'conditions'=>array(
                    'UploadType'=>'OldIncentive',
                    'ApproveStatus'=>NULL,
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$_REQUEST['EmpCode'],
                    )
                )); 
          
            if(!empty($data)){
            ?>
            <table class = "table table-striped table-hover  responstable">     
                <thead>
                    <tr>
                        <th style="text-align: center;">SNo</th>
                        <th style="text-align: center;">EmpCode</th>
                        <th style="text-align: center;">EmpName</th>
                        <th style="text-align: center;">CostCenter</th>
                        <th style="text-align: center;">Incentive Month</th>
                        <th style="text-align: center;">Sal Month</th>
                        <th style="text-align: center;">Amount</th>
                        <th style="text-align: center;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($data as $row){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i++; ?></td>
                        <td style="text-align: center;"><?php echo $row['UploadIncentiveBreakup']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $row['UploadIncentiveBreakup']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $row['UploadIncentiveBreakup']['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo isset($row['UploadIncentiveBreakup']['SalaryMonth'])? date('d-M-Y',strtotime($row['UploadIncentiveBreakup']['SalaryMonth'])):'';?></td>
                        <td style="text-align: center;"><?php echo isset($row['UploadIncentiveBreakup']['SalaryMonth'])? date('d-M-Y',strtotime($row['UploadIncentiveBreakup']['SalaryMonth'])):'';?></td>
                        <td style="text-align: center;"><?php echo $row['UploadIncentiveBreakup']['Amount'];?></td>
                        <td style="text-align: center;"><?php echo $row['UploadIncentiveBreakup']['Remarks'];?></td>
                    </tr>
                    <?php } ?>
                </tbody>   
            </table>
            <?php   
            }
            else{
                echo "";
            }   
        }
        die;  
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
        $data=$this->Masjclrentry->find('first', array('fields'=>array('EmpName','CostCenter'),'conditions' => array('EmpLocation'=>'InHouse','EmpCode'=>$EmpCode,'Status'=>0,'BranchName'=>$branchName)));
        return $data['Masjclrentry'];
    }
        
}
?>