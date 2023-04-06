<?php
class OdApplysController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OdApplyMaster','LockUnlockMaster','User');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_emp');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
   
        if($this->request->is('Post')){
            
            $branch_name    =   $this->request->data['OdApplys']['branch_name'];
            $FromDate       =   date("Y-m-d",strtotime($this->request->data['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($this->request->data['ToDate']));
           
            
            $OdReason       =   $this->request->data['OdReason'];
            $exp            =   explode("#", $this->request->data['EmpNameCode']);
            $EmpCode        =   $exp[0];
            $EmpName        =   $exp[1];
           
            $AttendCount    =   $this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(AttandDate)'=>$ToDate)));
            
            
            
            $lockcount=$this->LockUnlockMaster->find('count',array('conditions'=>array('BranchName'=>$branch_name,'OD'=>'Yes')));
            
            
            
            
            $lockdate   = date('Y-m-d',strtotime("-3 days"));
            
            /*
            $exis1=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(EndDate) >='=>$FromDate,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL)));
            $exis2=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(EndDate) >='=>$FromDate,'ApproveFirst'=>'Yes','ApproveSecond'=>NULL)));
            $exis3=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(EndDate) >='=>$FromDate,'ApproveFirst'=>'Yes','ApproveSecond'=>'Yes'))); 
            */
            
            $exis1=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(StartDate) >='=>$FromDate,'date(EndDate) <='=>$ToDate,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL,'DiscardStatus'=>NULL)));
            $exis2=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(EndDate) >='=>$FromDate,'ApproveFirst'=>'Yes','ApproveSecond'=>NULL,'DiscardStatus'=>NULL)));
            $exis3=$this->OdApplyMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(EndDate) >='=>$FromDate,'ApproveFirst'=>'Yes','ApproveSecond'=>'Yes','DiscardStatus'=>NULL)));
            $exis4=$this->Masattandance->query("SELECT * FROM `Attandence` WHERE BranchName='$branch_name' AND EmpCode='$EmpCode' AND DATE(AttandDate) BETWEEN '$FromDate' AND '$ToDate' order by DATE(AttandDate);");
            
            $PresentAr=array();
            $AbsentsAr=array();
            foreach($exis4 as $row){
                if($row['Attandence']['Status'] =="P"){
                    $PresentAr[]=date('d M',strtotime($row['Attandence']['AttandDate']));
                }
                else{
                    $AbsentsAr[]=$row['Attandence']['Id']."_".$row['Attandence']['Status'];
                }
            }
            
            $EmpStatus=implode(",", $AbsentsAr);
            
            if($FromDate > $ToDate){
                $this->Session->setFlash('<span style="color:red;" >Please select correct date.</span>');
                $this->redirect(array('action'=>'index'));   
            }
            else if($AttendCount < 1){
                $this->Session->setFlash('<span style="color:red;" >This attendance date not exist in database.</span>');
                $this->redirect(array('action'=>'index'));   
            }
            else if(!empty($exis1) || !empty($exis2) || !empty($exis3)){
                $this->Session->setFlash('<span style="color:red;" >This od request already exist between given date in database.</span>');
                $this->redirect(array('action'=>'index'));
            }
            else if(!empty($PresentAr)){
                $this->Session->setFlash('<span style="color:red;" >'.implode(" , ", $PresentAr).' is already present please select correct attendance date.</span>');
                $this->redirect(array('action'=>'index'));
            }
            else if(($lockcount > 0) && ($FromDate < $lockdate)){
                $this->Session->setFlash('<span style="color:red;" >You can apply od of previous 3 day.</span>');
                $this->redirect(array('action'=>'index'));   
            }
            else if(($lockcount > 0) && ($ToDate < $lockdate)){
                $this->Session->setFlash('<span style="color:red;" >You can apply od of previous 3 day.</span>');
                $this->redirect(array('action'=>'index'));   
            }
            else{
                $brArr=$this->Masjclrentry->find('first',array('fields'=>array('Desgination'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$branch_name,'EmpCode'=>$EmpCode)));
                $AtArr=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(AttandDate)'=>$FromDate)));
                
                
                //$people = array("EXECUTIVE - VOICE", "SR. EXECUTIVE - VOICE", "EXECUTIVE - FIELD", "SR.EXECUTIVE - FIELD");
                $people = array();

                if (in_array($brArr['Masjclrentry']['Desgination'], $people)){
                    $this->Session->setFlash('<span style="color:red;" >OD not allow for executive voice & field.</span>');
                    $this->redirect(array('action'=>'index'));  
                }
                else{
                    $dataArr=array(
                        'BranchName'=>$branch_name,
                        'EmpCode'=>$EmpCode,
                        'EmpName'=>$EmpName,
                        'Designation'=>$brArr['Masjclrentry']['Desgination'],
                        'CurrentStatus'=>$EmpStatus,
                        'StartDate'=>$FromDate,
                        'EndDate'=>$ToDate,
                        'Reason'=>$OdReason,
                        'CreateDate'=>date('Y-m-d H:i:s')
                    );
                    
                    $this->OdApplyMaster->saveAll($dataArr);
                    $this->Session->setFlash('<span style="color:green;" >Your request save successfully.</span>');
                    $this->redirect(array('action'=>'index')); 
                }
            } 
        }     
    }
    
    
    public function get_emp(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpName']) && trim($_REQUEST['EmpName']) !=""){ 

            if($this->Session->read('role')=='admin' && $this->Session->read('branch_name')=="HEAD OFFICE"){
                $data = $this->Masjclrentry->find('all',array(
                    'fields'=>array("EmpCode","EmpName"),
                    'conditions'=>array(
                        'Status'=>1,
                        'BranchName'=>$_REQUEST['BranchName'],
                        'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                        'EmpLocation'=>'InHouse',
                        )
                    )); 
            }
            else{
                
                $User_Id        =   $this->Session->read('userid');
                $User_Arr       =   $this->User->find('first',array('conditions'=>array('id'=>$User_Id)));
                $Access_Type    =   $User_Arr['User']['Access_Type']; 
                $Access_Rights  =   $User_Arr['User']['Access_Rights'];
                $Branch_Name    =   $_REQUEST['BranchName'];
                
                if($Access_Type =="Own"){
                    $Code_Rights    =   $Access_Rights;
                }
                else if($Access_Type =="CostCentre"){
                    $Code_Rights = $this->Masjclrentry->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$Branch_Name,'CostCenter'=>explode(",",$Access_Rights)),'group' =>array('EmpCode')));
                }
                
                $data = $this->Masjclrentry->find('all',array(
                    'fields'=>array("EmpCode","EmpName"),
                    'conditions'=>array(
                        'EmpCode'=>$Code_Rights,
                        'Status'=>1,
                        'BranchName'=>$_REQUEST['BranchName'],
                        'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                        'EmpLocation'=>'InHouse',
                        )
                    )); 
            }
            
            
            
            if(!empty($data)){
                echo "<option value=''>Select Emp Code</option>";
                foreach ($data as $val){
                    $value=$val['Masjclrentry']['EmpCode']."#".$val['Masjclrentry']['EmpName'];
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

}
?>