<?php
class AttendanceIssueSubmitionsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OdApplyMaster','BranchAttandIssueMaster','ProcessAttendanceMaster','LockUnlockMaster','User');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','get_attend_status','test');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        $branchName =   $this->Session->read('branch_name');
        $User_Id    =   $this->Session->read('userid');

        $User_Arr       =   $this->User->find('first',array('conditions'=>array('id'=>$User_Id)));
        $Access_Type    =   $User_Arr['User']['Access_Type']; 
        $Access_Rights  =   $User_Arr['User']['Access_Rights']; 
        
        if($this->Session->read('role')=='admin' && $this->Session->read('branch_name')=="HEAD OFFICE"){
            $this->set('PendingArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL,'date(AttandDate) >= date(DATE_SUB(concat(year(curdate()),\'-\',month(curdate()),\'-\',\'01\'), INTERVAL 1 MONTH))'))));
            $this->set('ClosedArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst !='=>NULL,'date(AttandDate) >= date(DATE_SUB(concat(year(curdate()),\'-\',month(curdate()),\'-\',\'01\'), INTERVAL 1 MONTH))'),'order'=>array('EmpCode')))); 
        }
        else{
            if($Access_Type =="Own"){
                $Code_Rights    =   $Access_Rights;
            }
            else if($Access_Type =="CostCentre"){
                $Code_Rights = $this->Masjclrentry->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$branchName,'CostCenter'=>explode(",",$Access_Rights)),'group' =>array('EmpCode')));
            }
    
            $this->set('PendingArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('EmpCode'=>$Code_Rights,'BranchName'=>$branchName,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL,'date(AttandDate) >= date(DATE_SUB(concat(year(curdate()),\'-\',month(curdate()),\'-\',\'01\'), INTERVAL 1 MONTH))'))));
            $this->set('ClosedArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('EmpCode'=>$Code_Rights,'BranchName'=>$branchName,'ApproveFirst !='=>NULL,'date(AttandDate) >= date(DATE_SUB(concat(year(curdate()),\'-\',month(curdate()),\'-\',\'01\'), INTERVAL 1 MONTH))'),'order'=>array('EmpCode'))));  
        }
          
        if($this->request->is('Post')){
            
            $data           =   $this->request->data;
            $Empcode        =   trim(addslashes($data['EmpCode']));
            $AttenDate      =   date('Y-m-d',strtotime(trim(addslashes($data['AttenDate']))));
            $CurStatus      =   trim(addslashes($data['CurStatus']));
            $ExpStatus      =   trim(addslashes($data['ExpStatus']));
            $Reason         =   trim(addslashes($data['Reason']));
            $OtherReason    =   trim(addslashes($data['OtherReason']));
            $curMonth       =   date('m');
            $AttMonth       =   date('m',strtotime(trim(addslashes($data['AttenDate']))));
            $ProcessDate      =   date('Y-m',strtotime(trim(addslashes($data['AttenDate']))));
            
            $count=$this->BranchAttandIssueMaster->query("SELECT * FROM `BranchWiseAttandanceIssue` 
                WHERE 
                BranchName='$branchName' 
                AND EmpCode='$Empcode' 
                AND DATE(AttandDate)='$AttenDate'
                AND (`ApproveFirst` IS NULL OR `ApproveFirst`='Yes')
                AND (`ApproveSecond` IS NULL OR `ApproveSecond`='Yes')
                limit 1");
            
            
            
            $lockcount=$this->LockUnlockMaster->find('count',array('conditions'=>array('BranchName'=>$branchName,'Exception'=>'Yes')));
            $lockdate   = date('Y-m-d',strtotime("-3 days"));
            
            
            
            
            if(!empty($count)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your attendance issue request already exist in database.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }/*
            else if($curMonth !=$AttMonth){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Attendance issue allow only for current month.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }*/
            else{
   
                if($Reason =="Others"){
                    $NewRes=$OtherReason;
                }
                else{
                   $NewRes=$Reason; 
                }
                //,'BranchName'=>$branchName
                $enmArr     =   $this->Masjclrentry->find('first',array('fields'=>array('EmpName','BioCode','CostCenter'),'conditions'=>array('EmpLocation'=>'InHouse','EmpCode'=>$Empcode)));
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
                
                $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>$ProcessDate)));
                //$ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>'HEAD OFFICE','CostCenter'=>'BSS-OTHERS','ProcessMonth'=>$ProcessDate)));
                
                if($ProAttArr > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
                    $this->redirect(array('action'=>'index'));
                }
                else if(($lockcount > 0) && ($AttenDate < $lockdate)){
                    $this->Session->setFlash('<span style="color:red;" >You can apply attendance issue of previous 3 day.</span>');
                    $this->redirect(array('action'=>'index'));   
                }
                else{
                    if($this->BranchAttandIssueMaster->save($dataArr)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your attendance issue request save scccessfully.</span>');      
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your attendance issue request failed try again later</span>');   
                    }
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
 
            if($this->Session->read('role')=='admin' && $this->Session->read('branch_name')=="HEAD OFFICE"){
                // echo $_REQUEST['EmpCode'];
                // echo $branchName;
                // echo $AttandDate;die;
                $data=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('EmpCode'=>trim($_REQUEST['EmpCode']),'BranchName'=>$branchName,'date(AttandDate)'=>$AttandDate)));
            }
            else{
                
                $User_Id        =   $this->Session->read('userid');
                $User_Arr       =   $this->User->find('first',array('conditions'=>array('id'=>$User_Id)));
                $Access_Type    =   $User_Arr['User']['Access_Type']; 
                $Access_Rights  =   $User_Arr['User']['Access_Rights'];
                $Branch_Name    =   $branchName;
                $Code_Rights    =   array();
                
                if($Access_Type =="Own"){
                    $Code_Rights[]=$Access_Rights;  
                }
                else if($Access_Type =="CostCentre"){
                    $Code_Rights = $this->Masjclrentry->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$Branch_Name,'CostCenter'=>explode(",",$Access_Rights)),'group' =>array('EmpCode'))); 
                    // print_r($Code_Rights);die; 
                }
                
                if(in_array($_REQUEST['EmpCode'],$Code_Rights)){

                    $data=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('EmpCode'=>trim($_REQUEST['EmpCode']),'BranchName'=>$branchName,'date(AttandDate)'=>$AttandDate)));  

                }  
            }
            
            
            
            


//$data=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('EmpCode'=>trim($_REQUEST['EmpCode']),'date(AttandDate)'=>$AttandDate))); 
            
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