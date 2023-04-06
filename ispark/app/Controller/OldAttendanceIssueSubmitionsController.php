<?php
class OldAttendanceIssueSubmitionsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OldAttendanceIssue','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','get_attend_status','test');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        $branchName = $this->Session->read('branch_name');
        $this->set('PendingArr',$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL))));
        $this->set('ClosedArr',$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst !='=>NULL)))); 
        
        if($this->request->is('Post')){
            $data           =   $this->request->data;
            $Empcode        =   trim(addslashes($data['EmpCode']));
            $AttenDate      =   date('Y-m-d',strtotime(trim(addslashes($data['AttenDate']))));
            $CurStatus      =   trim(addslashes($data['CurStatus']));
            $ExpStatus      =   trim(addslashes($data['ExpStatus']));
            $Reason         =   trim(addslashes($data['Reason']));
            $OtherReason    =   trim(addslashes($data['OtherReason']));
            
            $curMonth       =   date('m', strtotime('last month'));
            $AttMonth       =   date('m',strtotime(trim(addslashes($data['AttenDate']))));
            $ProcessDate      =   date('Y-m',strtotime(trim(addslashes($data['AttenDate']))));
            
            //$count=$this->OldAttendanceIssue->find('first',array('conditions'=>array('BranchName'=>$branchName,'EmpCode'=>$Empcode,'AttandDate'=>$AttenDate)));
            
            $count=$this->OldAttendanceIssue->query("SELECT * FROM `old_attendance_issue` 
                WHERE 
                BranchName='$branchName' 
                AND EmpCode='$Empcode' 
                AND DATE(AttandDate)='$AttenDate'
                AND (`ApproveFirst` IS NULL OR `ApproveFirst`='Yes')
                AND (`ApproveSecond` IS NULL OR `ApproveSecond`='Yes')
                limit 1");
            
            
            if(!empty($count)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your attendance issue request already exist in database.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }
            else if($curMonth <=$AttMonth){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Old attendance issue submition not allow for current month.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }
            else{
                
                if($Reason =="Others"){
                    $NewRes=$OtherReason;
                }
                else{
                   $NewRes=$Reason; 
                }

                $enmArr     =   $this->Masjclrentry->find('first',array('fields'=>array('EmpName','BioCode','CostCenter'),'conditions'=>array('EmpLocation'=>'InHouse','EmpCode'=>$Empcode,'BranchName'=>$branchName)));
                $EmpName    =   $enmArr['Masjclrentry']['EmpName'];
                $CostCenter =   $enmArr['Masjclrentry']['CostCenter'];
                $dataArr=array(
                    'BranchName'=>$branchName,
                    'EmpCode'=>$Empcode,
                    'BioCode'=>$enmArr['Masjclrentry']['BioCode'],
                    'EmpName'=>$EmpName,
                    'CurrentStatus'=>$CurStatus,
                    'ExpectedStatus'=>$ExpStatus,
                    'AttandDate'=>$AttenDate,
                    'IssueType'=>$Reason,
                    'Reason'=>$NewRes,
                    'SaveBy'=>$this->Session->read('email')
                );
                
                
                
                
                $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>$ProcessDate,'FinializeStatus'=>'Yes')));
                //$ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>'HEAD OFFICE','CostCenter'=>'BSS-OTHERS','ProcessMonth'=>$ProcessDate)));
                
                if($ProAttArr > 0){
                    if($this->OldAttendanceIssue->save($dataArr)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your attendance issue request save scccessfully.</span>');      
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your attendance issue request failed try again later</span>');   
                    }  
                }
                else{
                   $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance not process please contact with admin.</span>');
                   $this->redirect(array('action'=>'index'));  
                }
                
                
                
                
                $this->redirect(array('action'=>'index'));  
            }
           
            
             
        }     
    }
    
    public function get_attend_status(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode'])){ 
            $branchName = $this->Session->read('branch_name');
            $AttandDate=date('Y-m-d',strtotime(trim($_REQUEST['AttendDate'])));
            $data=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('EmpCode'=>trim($_REQUEST['EmpCode']),'BranchName'=>$branchName,'date(AttandDate)'=>$AttandDate))); 
            
            if(!empty($data)){
                echo $data['Masattandance']['Status'];
            }
            else{
                echo "";
            }
        }
        die;  
    }
      
}
?>