<?php
class InsertAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','LeaveManagementMaster','LeaveRightsMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','get_emp','exist_attendance','check_date','check_doj');
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
             
            $branch_name    =   $this->request->data['InsertAttendances']['branch_name'];
            $EmpCode        =   $this->request->data['EmpNameCode'];
            $FromDate       =   date("Y-m-d",strtotime($this->request->data['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($this->request->data['ToDate']));
            
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("EmpCode","BioCode","EmpName","BranchName","EmpLocation"),
                'conditions'=>array(
                    'Status'=>1,
                    'EmpCode'=>$EmpCode,
                    'BranchName'=>$branch_name,
                    )
                ));

            
            if(!empty($data)){
                $FromDate = $FromDate.' 00:00:00';
                $ToDate = $ToDate.' 00:00:00';
                while(strtotime($FromDate) <= strtotime($ToDate)){
                  
                    
                    $dataArr[]=array(
                        'BioCode'=>$data['Masjclrentry']['BioCode'],
                        'EmpCode'=>$data['Masjclrentry']['EmpCode'],
                        'EmpName'=>$data['Masjclrentry']['EmpName'],
                        'BranchName'=>$data['Masjclrentry']['BranchName'],
                        'Status'=>'A',
                        'AttandDate'=>date('Y-m-d',strtotime($FromDate)),
                        'EmpStatus'=>$data['Masjclrentry']['EmpLocation'],
                        'ImportDate'=>date('Y-m-d H:i:s')
                    );
                    
                    
                   $FromDate=date('Y-m-d H:i:s',strtotime($FromDate.'+1 day'));
                   
                }
                
                if($this->Masattandance->saveMany($dataArr)){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Attendance insert successfully.</span>');
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Attendance not insert try again later.</span>'); 
                }
               
                $this->redirect(array('action'=>'index'));  
                    
            }
        } 
    }
    
    public function get_emp(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpName']) && trim($_REQUEST['EmpName']) !=""){ 
            $data = $this->Masjclrentry->find('all',array(
                'fields'=>array("EmpCode","EmpName"),
                'conditions'=>array(
                    'Status'=>1,
                    'EmpLocation'=>'InHouse',
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                    'EmpLocation'=>'InHouse',
                    )
                ));
            
            if(!empty($data)){
                echo "<option value=''>Select Emp Code</option>";
                foreach ($data as $val){
                    $value=$val['Masjclrentry']['EmpCode'];
                    $label=$val['Masjclrentry']['EmpCode']." - ".$val['Masjclrentry']['EmpName'];
                    echo "<option value='$value'>$label</option>";
                }
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    public function exist_attendance(){
        $BranchName =   $_REQUEST['BranchName'];
        $EmpCode    =   $_REQUEST['EmpNameCode']; 
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
       
        $result=$this->Masattandance->query("SELECT * FROM `Attandence` WHERE EmpCode='$EmpCode' AND BranchName='$BranchName' AND DATE(AttandDate) BETWEEN '$FromDate' AND '$ToDate'");
          
        if(!empty($result)){echo '1';die;}
        else{echo '';die;}
    }
    
    
    public function check_doj(){
        $BranchName =   $_REQUEST['BranchName'];
        $EmpCode    =   $_REQUEST['EmpNameCode']; 
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("DOJ"),
                'conditions'=>array(
                    'BranchName'=>$BranchName,
                    'EmpCode'=>$EmpCode,
                    )
                ));

        $doj     =   date("Y-m-d",strtotime($data['Masjclrentry']['DOJ']));
        
        if($FromDate >=$doj){
            echo '1';die; 
        }
        //else if($ToDate >=$doj){
            //echo '';die;
        //}
        else{
             echo '';die;
        }
    }
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        if($ToDate >=$FromDate){
            echo '1';die;
        }
        else{
            echo '';die;
        }
    }  
    
}
?>