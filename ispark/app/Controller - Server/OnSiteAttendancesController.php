<?php
class OnSiteAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','OnSiteAttendanceMaster','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','odapproval','oddisapproval','total_employees','markfield');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $fieldArr=array();
        $data = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'Status'=>1),'group' =>array('CostCenter')));
        
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
        
        if(isset($_REQUEST['CSN'])){
            $cosc = base64_decode($_REQUEST['CSN']);
            //$this->set('fieldArr',$this->Masjclrentry->find('all',array('conditions'=>array('CostCenter'=>$cosc,'EmpLocation'=>'OnSite'))));
            
            $fieldArr=array();
            $dar=$this->Masjclrentry->find('all',array('conditions'=>array('CostCenter'=>$cosc,'EmpLocation'=>'OnSite','BranchName'=>$branchName,'Status'=>1)));
            
            foreach($dar as $row){
                $exarr=$this->existMark($row['Masjclrentry']['EmpCode'],$branchName,$cosc);  
                $fieldArr[]=array(
                   'EmpCode'=>$row['Masjclrentry']['EmpCode'],
                   'EmpName'=>$row['Masjclrentry']['EmpName'],
                   'SalDays'=>$exarr['OnSiteAttendanceMaster']['SalDays'],
                );
            }
            
            $this->set('fieldArr',$fieldArr);
           
            $empArr=array(
                'BranchName'=>$branchName,
                'CostCenter'=>$cosc,
                'EmpLoc'=>'OnSite',
                'TotalEmp'=>$this->total_employees($cosc,$branchName),
            );
            
            $this->set('empArr',$empArr);
        }
        else if($this->request->is('Post')){
            $CostCenter=$this->request->data['CostCenter'];
            $BranchName=$this->request->data['BranchName'];
            $emcodeid=$this->request->data['emcodeid'];
   
            foreach($emcodeid as $emcod){
                $slday=$this->request->data[$emcod];
                $ename=$this->request->data[$emcod.'_name'];
                
                
                if($slday <=date('d', strtotime('last day of previous month'))){
                    $exist=$this->existMark($emcod,$BranchName,$CostCenter);
                    
                    $ProcessDate    =   date('Y-m', strtotime('last month'));
                    //$ProcessDate    =   "2018-01";
                    $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>$ProcessDate)));
                      
                    if($ProAttArr > 0){
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
                        $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter))));  
                    }
                    else{
                        if(empty($exist)){
                            $dataArr=array(
                            'EmpCode'=>$emcod,
                            'EmpName'=>$ename,
                            'BranchName'=>$BranchName,
                            'CostCenter'=>$CostCenter,
                            'MaxSalDays'=>date('d', strtotime('last day of previous month')),
                            'SalDays'=>$slday,
                            'SalMonth'=>date('Y-m', strtotime('last month')),
                            'UpdateDate'=>date('Y-m-d H:i:s'),
                        );

                        $this->OnSiteAttendanceMaster->saveAll($dataArr);
                        }
                        else{
                            $this->OnSiteAttendanceMaster->updateAll(array('SalDays'=>"'".$slday."'"),array('EmpCode'=>$emcod,'BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'SalMonth'=>date('Y-m', strtotime('last month'))));
                        }
                    }
                    
                    
                    
                }
            }
            
            $this->Session->setFlash('<span style="color:green;" >Your onsite attendance record save successfully.</span>');
            $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter))));   
        }
           
    }
    
    public function existMark($emcod,$BranchName,$CostCenter){
        return $this->OnSiteAttendanceMaster->find('first',array('conditions'=>array('EmpCode'=>$emcod,'BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'SalMonth'=>date('Y-m', strtotime('last month'))))); 
    }
    
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
    
    
    
}
?>