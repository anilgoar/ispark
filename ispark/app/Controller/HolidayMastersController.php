<?php
class HolidayMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','DepartmentNameMaster','HolidayMaster','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource');
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

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->HolidayMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('row',$row['HolidayMaster']);  
        }
        
        if(isset($_REQUEST['BRANCH']) && $_REQUEST['BRANCH'] !=""){
            
            $conditoin=array('year(HolydayDate)'=>$_REQUEST['YEAR']);
            
            if($_REQUEST['BRANCH'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BRANCH'];}else{unset($conditoin['BranchName']);}
            
            $DataArr=$this->HolidayMaster->find('all',array('conditions'=>$conditoin));
            $this->set('DataArr',$DataArr);
            $this->set('brname',array('BranchName'=>$_REQUEST['BRANCH'])); 
        }
       
        if($this->request->is('Post')){ 
            $BranchName     =   trim(addslashes($this->request->data['HolidayMasters']['branch_name']));
            $HolydayDay     =   date('l',strtotime($this->request->data['HolydayDate']));
            $HolydayDate    =   date('Y-m-d',  strtotime($this->request->data['HolydayDate']));
            $Occasion       =   trim(addslashes($this->request->data['Occasion']));
            $Restricted     =   trim(addslashes($this->request->data['Restricted']));
            $submit         =   $this->request->data['submit'];
            $HolidayListId  =   $this->request->data['HolidayListId'];
            $YearName       =   $this->request->data['YearName'];
            $currentdate    =   date('Y-m-d');
            
            if($submit =="Update"){
                $updArr=array(
                    'BranchName'=>"'".$BranchName."'",
                    'HolydayDay'=>"'".$HolydayDay."'",
                    'HolydayDate'=>"'".$HolydayDate."'",
                    'Occasion'=>"'".$Occasion."'",
                    'Restricted'=>"'".$Restricted."'",
                    );
                
                if($this->HolidayMaster->updateAll($updArr,array('Id'=>$HolidayListId))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This holiday list update successfully.</span>');
                    $this->redirect(array('controller'=>'HolidayMasters','?'=>array('BRANCH'=>$BranchName,'YEAR'=>$YearName)));  
                }
                else{
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This holiday list not update please try again later.</span>');
                    $this->redirect(array('controller'=>'HolidayMasters','?'=>array('BRANCH'=>$BranchName,'YEAR'=>$YearName)));  
                }
            }
            else{
                $data=array(
                    'BranchName'=>$BranchName,
                    'HolydayDay'=>$HolydayDay,
                    'HolydayDate'=>$HolydayDate,
                    'Occasion'=>$Occasion,
                    'Restricted'=>$Restricted,
                );
                
                
                $row=$this->HolidayMaster->find('count',array('conditions'=>$data));
                if($row > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This holiday list already exist in database.</span>');
                    $this->redirect(array('controller'=>'HolidayMasters','?'=>array('BRANCH'=>$BranchName,'YEAR'=>$YearName)));  
                }
                else if($HolydayDate < $currentdate){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >You can only add  holiday list of after current day.</span>');
                    $this->redirect(array('controller'=>'HolidayMasters','?'=>array('BRANCH'=>$BranchName,'YEAR'=>$YearName)));  
                }
                else{
                    $this->HolidayMaster->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This holiday list create successfully.</span>');
                    $this->redirect(array('controller'=>'HolidayMasters','?'=>array('BRANCH'=>$BranchName,'YEAR'=>$YearName)));  
                }
                     
            }
            
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            
            $row=$this->HolidayMaster->find('first',array('conditions'=>array('Id'=>$id)));
            $branchName=$row['HolidayMaster']['BranchName'];
            $ProcessDate      =   date('Y-m',strtotime($row['HolidayMaster']['HolydayDate']));
            
            $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$branchName,'ProcessMonth'=>$ProcessDate)));
            
            if($ProAttArr > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This holiday already process please contact with admin.</span>');
                $this->redirect(array('action'=>'index')); 
            }
            else{
                $this->HolidayMaster->query("DELETE FROM `HolidayMaster` WHERE Id='$id'");
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This holiday list delete successfully.</span>');
                $this->redirect(array('action'=>'index')); 
            }
        }
     
    }
    
    
}
?>