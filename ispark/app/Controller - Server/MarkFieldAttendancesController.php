<?php
class MarkFieldAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','markfield','savefieldmark','check_date');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $fieldArr=array();
        $branchName = $this->Session->read('branch_name');
        $data = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'Field','BranchName'=>$branchName,'Status'=>1),'group' =>array('CostCenter')));
           
        foreach($data as $val){
            $fieldArr[]=array(
                'ClientName'=>$val['Masjclrentry']['ClientName'],
                'CostCenter'=>$val['Masjclrentry']['CostCenter'],
                'TotalEmp'=>$this->total_employees($val['Masjclrentry']['CostCenter'],$branchName)
            );
        }
        $this->set('fieldArr',$fieldArr);
    }
    
    public function markfield(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $CostCenter = base64_decode($_REQUEST['CSN']);
        //$dt1=$this->Masattandance->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('Attanddate !='=>'','date(Attanddate) >'=>'2018-08-31'),'group' =>array('Attanddate')));
        $dt1=$this->Masattandance->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('Attanddate !='=>'','date(Attanddate) >'=>'2018-09-30'),'group' =>array('Attanddate')));
        $dt2=$this->FieldAttendanceMaster->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Attanddate !='=>'','date(Attanddate) >'=>'2018-08-31'),'group' =>array('Attanddate')));
        //print_r($dt1); exit;
        $dt3=  array_diff($dt1, $dt2);
        $this->set('dateArr',$dt3);
        
        $fieldArr=array();
        
        if(isset($_REQUEST['CSN']) || isset($_REQUEST['SFD'])){
            $cosc = base64_decode($_REQUEST['CSN']);
            if(isset($_REQUEST['SFD']) && $_REQUEST['SFD'] !=""){
                $search = base64_decode($_REQUEST['SFD']);
            }
            else{
                $search="";
            }
            
            if($search !=""){
                $data=$this->Masjclrentry->find('all',array('conditions' => array('Status'=>1,'EmpLocation'=>'Field','BranchName'=>$branchName,'CostCenter' =>$cosc,'or' => array('EmpCode' =>$search,'EmpName LIKE' =>$search.'%'))));
            }
            else{
                $data = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'Field','BranchName'=>$branchName,'CostCenter'=>$cosc,'Status'=>1))); 
            }
            
            //echo "<pre>";
            //print_r($data);die;
            
            $this->set('fieldArr',$data);
            $this->set('empArr',array('cost_center'=>$cosc,'TotalEmp'=>$this->total_employees($cosc,$branchName)));
        }
        else if($this->request->is('Post')){
            $this->set('search','search');
            $cosc=$this->request->data['CostCenter'];
            $SearchData=trim($this->request->data['SearchData']);
            $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($cosc),'SFD'=>base64_encode($SearchData),'STP'=>'search')));   
        }
           
    }
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        
        $branchName = $this->Session->read('branch_name');
        $dt1=$this->Masattandance->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('Attanddate !='=>'','date(Attanddate) >'=>'2018-08-31'),'group' =>array('Attanddate')));
        $dt2=$this->FieldAttendanceMaster->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$_REQUEST['CostCenter'],'Attanddate !='=>'','date(Attanddate) >'=>'2018-08-31'),'group' =>array('Attanddate')));
        $dt3=  array_diff($dt1, $dt2);
        
      
        if($FromDate !=min($dt3)){
            echo "Mark ".date('d-M-Y',strtotime(min($dt3)))." before ". date('d-M-Y',strtotime($FromDate)).".";die;
        }
        else{
            echo '';die; 
        }
    }  
    
    public function savefieldmark(){
        if($this->request->is('Post')){ 
            $branchName = $this->Session->read('branch_name');
            $CostCenter = $this->request->data['CostCenter'];
            $MarkDate   = date("Y-m-d",strtotime($this->request->data['MarkDate']));
            $Mark       = $this->request->data['check'];
            $MarkHd     = $this->request->data['checkHd'];
            $data       = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'Field','BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
            
           
            
            $result = array_intersect($Mark, $MarkHd);
            if(empty($result)){
            
                foreach($data as $row){
                    
                    if(strtotime($MarkDate) >= strtotime($row['Masjclrentry']['DOJ'])){

                    $empcode=$row['Masjclrentry']['EmpCode'];
                    if (in_array($empcode, $Mark)){
                        $status="P";
                    }
                    else if (in_array($empcode, $MarkHd)){
                        $status="HD";
                    }
                    else{
                       $status="A"; 
                    }

                    //$exist=$this->existMark($empcode,$MarkDate,$branchName);
                    //if(empty($exist)){
                        $dataArr=array(
                            'EmpCode'=>$empcode,
                            'EmpName'=>$row['Masjclrentry']['EmpName'],
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                            'Status'=>$status,
                            'AttandDate'=>$MarkDate,
                        );
                        
                        
                        $brname=$row['Masjclrentry']['BranchName'];
                        $Costce=$row['Masjclrentry']['CostCenter'];
                        $ProcessDate    =   date('Y-m',strtotime(trim(addslashes($MarkDate))));
                        $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$brname,'CostCenter'=>$Costce,'ProcessMonth'=>$ProcessDate)));
                      
                        if($ProAttArr > 0){
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
                        $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter)))); 
                    }
                    else{
                        
                            $this->FieldAttendanceMaster->saveAll($dataArr); 
                         }
                        
                        
                        
                    //}
                    //else{
                        //$this->FieldAttendanceMaster->updateAll(array('Status'=>"'".$status."'"),array('BranchName'=>$branchName,'EmpCode'=>$empcode,'date(Attanddate)'=>$MarkDate));
                    //} 
                         
                    }

                }
                $this->Session->setFlash('<span style="color:green;" >Your Field attendance save successfully.</span>'); 
                $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter)))); 
            }
            else{
            $this->Session->setFlash('<span style="color:red;" >Please do not select multiple attendance type for same Employees.</span>'); 
            $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter))));   
            }
        }
    }
    
    public function existMark($empcode,$markDate,$branchName){
        return $this->FieldAttendanceMaster->find('first',array('conditions'=>array('BranchName'=>$branchName,'EmpCode'=>$empcode,'date(Attanddate)'=>$markDate))); 
    }


    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('EmpLocation'=>'Field','BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
}
?>