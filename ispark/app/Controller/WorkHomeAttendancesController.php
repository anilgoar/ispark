<?php
class WorkHomeAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','ProcessAttendanceMaster','WorkHomeAttandenceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','markfield','savefieldmark','check_date');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $fieldArr   =   array();
        $branchName =   "NOIDA-2";
        $costCenter =   "BSS/BO/NOIDA-2/576";
        $data       =   $this->Masjclrentry->find('all',array('fields'=>array('ClientName','CostCenter'),'conditions'=>array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter'=>$costCenter,'Status'=>1),'group' =>array('CostCenter')));
        
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
        $branchName =   "NOIDA-2";
        $CostCenter = base64_decode($_REQUEST['CSN']);
        
        $cur_month  =   date('m', strtotime(date('Y-m')." -0 month"));
        $cur_year   =   date('Y', strtotime(date('Y-m')." -0 month"));
        $cur_days   =   cal_days_in_month(CAL_GREGORIAN, $cur_month, $cur_year);
        
        $pre_month  =   date('m', strtotime(date('Y-m')." -1 month"));
        $pre_year   =   date('Y', strtotime(date('Y-m')." -1 month"));
        $pre_days   =   cal_days_in_month(CAL_GREGORIAN, $pre_month, $pre_year);
        
        $dt1        =   array();
        
		
        /*
        for($j=1;$j<=$pre_days;$j++){
           $dt1[]="$j-$pre_month-$pre_year"; 
        }*/
        $end_day = date("Y-m-t");
        $start_day = date('Y-m-t', strtotime($start_day . " -50 day")); 
        $dt2    =   $this->WorkHomeAttandenceMaster->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Attanddate !='=>'',"DATE(AttandDate)>='$start_day' and DATE(AttandDate)<='$end_day'"),'group' =>array('Attanddate')));
        
        //print_r($dt2); exit;
        
        while(strtotime($start_day)<=strtotime($end_day))
        {
            $dt1[] = $start_day;
            $start_day = date('Y-m-d', strtotime($start_day . " +1 day"));
        }
        
        
        /*for($i=1;$i<=$cur_days;$i++){
            if(strlen($i) ==1){
                $newDate=$cur_year."-".$cur_month."-0".$i;
            }
            else{
               $newDate=$cur_year."-".$cur_month."-".$i; 
            }
            
            $dt1[]=$newDate;  
        }*/

        $dt3    =  array_diff($dt1, $dt2);
        
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
                $data=$this->Masjclrentry->find('all',array('conditions' => array('Status'=>1,'EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter' =>$cosc,'or' => array('EmpCode' =>$search,'EmpName LIKE' =>$search.'%'))));
            }
            else{
                $data = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter'=>$cosc,'Status'=>1))); 
            }
            
            $this->set('fieldArr',$data);
            $this->set('empArr',array('BranchName'=>$branchName,'cost_center'=>$cosc,'TotalEmp'=>$this->total_employees($cosc,$branchName)));
            
            
            $EmpMarkStatus =   array();
            if(isset($_REQUEST['mark_date']) && $_REQUEST['mark_date'] !=""){
                
                $MarkDate       =   date("Y-m-d",strtotime($_REQUEST['mark_date']));
                $AttendanceList =   $this->WorkHomeAttandenceMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'date(Attanddate)'=>$MarkDate))); 
                
                foreach($AttendanceList as $row){
                    $EmpMarkStatus[$row['WorkHomeAttandenceMaster']['EmpCode']]=$row['WorkHomeAttandenceMaster']['Status'];
                }                
            }
            
            $this->set('EmpMarkStatus',$EmpMarkStatus);
            
        }
        
        else if($this->request->is('Post')){
            $this->set('search','search');
            $cosc       =   $this->request->data['CostCenter'];
            $SearchData =   trim($this->request->data['SearchData']);
            $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($cosc),'SFD'=>base64_encode($SearchData),'STP'=>'search')));   
        }
           
    }
    
    public function view_mark_list(){
        
    }
    
    
    
    
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        
        $branchName = $this->Session->read('branch_name');
        
        $dt1=$this->Masattandance->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('Attanddate !='=>'','date(Attanddate) >'=>'2019-11-31'),'group' =>array('Attanddate')));
        $dt2=$this->FieldAttendanceMaster->find('list',array('fields'=>array('Attanddate'),'conditions'=>array('BranchName'=>$branchName,'CostCenter'=>$_REQUEST['CostCenter'],'Attanddate !='=>'','date(Attanddate) >'=>'2019-11-31'),'group' =>array('Attanddate')));
        $dt3=  array_diff($dt1, $dt2);
        
      
        if($FromDate !=min($dt3)){
            echo "Mark ".date('d-M-Y',strtotime(min($dt3)))." before ". date('d-M-Y',strtotime($FromDate)).".";die;
            //echo '';die; 
        }
        else{
            echo '';die; 
        }
    }  
    
    public function savefieldmark(){ 
        if($this->request->is('Post')){
            
            $branchName = $this->request->data['BranchName'];
            $CostCenter = $this->request->data['CostCenter'];
            $mark_date  =   $this->request->data['MarkDate'];
            $MarkDate   = date("Y-m-d",strtotime($this->request->data['MarkDate']));
            $Mark       = $this->request->data['check'];
            $MarkHd     = $this->request->data['checkHd'];
            $data       = $this->Masjclrentry->find('all',array('conditions'=>array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));

            //echo "<pre>";
            //print_r($_REQUEST);die;
            
            $result     = array_intersect($Mark, $MarkHd);
            
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
                        
                        $brname         =   $row['Masjclrentry']['BranchName'];
                        $Costce         =   $row['Masjclrentry']['CostCenter'];
                        $ProcessDate    =   date('Y-m',strtotime(trim(addslashes($MarkDate))));
                        $ProAttArr      =   $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$brname,'CostCenter'=>$Costce,'ProcessMonth'=>$ProcessDate)));

                        if($ProAttArr > 0){
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
                            $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter),'mark_date'=>$mark_date))); 
                        }
                        else{
                            $exist  =   $this->existMark($empcode,$MarkDate,$branchName);
                            
                            
                            
                            if(empty($exist)){
                                $dataArr=array(
                                    'EmpCode'=>$empcode,
                                    'EmpName'=>$row['Masjclrentry']['EmpName'],
                                    'BranchName'=>$row['Masjclrentry']['BranchName'],
                                    'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                                    'Status'=>$status,
                                    'AttandDate'=>$MarkDate,
                                );
                                
                                $this->WorkHomeAttandenceMaster->saveAll($dataArr);
                            }
                            else{
                                $this->WorkHomeAttandenceMaster->updateAll(array('Status'=>"'".$status."'"),array('BranchName'=>$branchName,'EmpCode'=>$empcode,'date(Attanddate)'=>$MarkDate));
                            }
                        }
                    }
                }
                
                $this->Session->setFlash('<span style="color:green;" >Your work home attendance save successfully.</span>'); 
                $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter),'mark_date'=>$mark_date))); 
            }
            else{
                $this->Session->setFlash('<span style="color:red;" >Please do not select multiple attendance type for same Employees.</span>'); 
                $this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter),'mark_date'=>$mark_date)));   
            }
        }
    }
    
    public function existMark($empcode,$markDate,$branchName){
        return $this->WorkHomeAttandenceMaster->find('first',array('conditions'=>array('BranchName'=>$branchName,'EmpCode'=>$empcode,'date(Attanddate)'=>$markDate))); 
    }


    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('EmpLocation'=>'OnSite','BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
}
?>