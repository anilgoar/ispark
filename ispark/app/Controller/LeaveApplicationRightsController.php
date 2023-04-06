<?php
class LeaveApplicationRightsController extends AppController {
    public $uses = array('UserMaster','CostCenterMaster','Masjclrentry','LeaveRightsMaster');
        
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow('index','get_cost_center','get_roles');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        
        /*
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
        */

        $this->set('UserMaster',$this->UserMaster->find('list',array('fields'=>array('username','emp_name'),'conditions'=>array('UserActive'=>1,'emp_name !='=>""),'order'=>array('emp_name'))));

        
        if($this->request->is('Post')){
            
            $UserId     =   trim($this->request->data['LeaveApplicationRights']['UserId']);
            $Roles      =   implode(",", $this->request->data['Roles']);
            $Process    =   implode(",", $this->request->data['Process']);
            
            $UserArr=$this->UserMaster->find('first',array('fields'=>array('branch_name','id'),'conditions'=>array('UserActive'=>1,'username'=>$UserId)));
            $branch_name=$UserArr['UserMaster']['branch_name'];
            $id=$UserArr['UserMaster']['id'];
            
            $cnt = $this->LeaveRightsMaster->find('count',array('conditions'=>array('BranchName'=>$branch_name,'UserId'=>$UserId,'Uid'=>$id)));
                
            if($cnt > 0){
                $updArr=array('Roles'=>"'".$Roles."'",'Process'=>"'".$Process."'");	
                $this->LeaveRightsMaster->updateAll($updArr,array('BranchName'=>$branch_name,'UserId'=>$UserId,'Uid'=>$id)); 
            }
            else{
                $dataArr=array(
                    'BranchName'=>$branch_name,
                    'Roles'=>$Roles,
                    'Process'=>$Process,
                    'Uid'=>$id,
                    'UserId'=>$UserId,
                );

                $this->LeaveRightsMaster->save($dataArr);
            }
            
            
            
            
            
            /*
            $lastid=$this->LeaveRightsMaster->getLastInsertId();
            $Process    =   $this->request->data['Process'];
            foreach($check as $row){
                $this->UploadDeductionMaster->query("DELETE FROM `upload_deduction` WHERE `BranchName`='$branch_name' AND `CostCenter`='$row' AND `SalaryMonth`='$SalaryMonth'");
            }
            */  
                  
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your record save successfully.</span>');
            $this->redirect(array('action'=>'index'));
          
            
              
        }   
    }
    
    public function get_deduction(){
        $this->layout='ajax';
        if(isset($_REQUEST['ActionFor']) && isset($_REQUEST['BranchName']) && isset($_REQUEST['CostCenter'])){
            
            $CostCenter=$_REQUEST['CostCenter'];
            $branch_name=$_REQUEST['BranchName'];
            $ActionFor=$_REQUEST['ActionFor'];
            $SalaryMonth=date('Y-m', strtotime('-1 month', time()));
             
            $conditoin=array('SalaryMonth'=>$SalaryMonth,'BranchName'=>$branch_name);
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            $data = $this->UploadDeductionMaster->find('all',array('conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            //print_r($data);die;
            
            if(!empty($data)){
                echo '<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >';
                echo '<thead>';
                echo '<tr>';
                echo '<th style="text-align:left;" >Check</th>';
                echo '<th>Branch</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Current Status</th>';
                echo '<th>Download Status</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($data as $val){
                    echo '<tr>';
                    echo '<td><input class="checkbox" type="checkbox" value="'.$val['UploadDeductionMaster']['CostCenter'].'" name="check[]"></td>';
                    echo '<td>'.$val['UploadDeductionMaster']['BranchName'].'</td>';
                    echo '<td>'.$val['UploadDeductionMaster']['CostCenter'].'</td>';
                     echo '<td>'.$val['UploadDeductionMaster']['ProcessStatus'].'</td>';
                    echo '<td>No</td>';
                    echo '</tr>';
                }
                echo '</tbody>';           
                echo '</table>';
            }
            else{
                echo "";
            }
        }
        die;    
    } 
    
    public function get_cost_center(){
        $this->layout='ajax';
        if(isset($_REQUEST['UserId']) && $_REQUEST['UserId'] !=""){
            $userid = $_REQUEST['UserId'];
            $branch_name=$this->getBranchName($userid);
            
            $RolArr=array();
            $prArr = $this->LeaveRightsMaster->find('first',array('fields'=>array('Process'),'conditions'=>array('BranchName'=>$branch_name,'UserId'=>$userid)));
            if(!empty($prArr)){
                $RolArr= explode(',', $prArr['LeaveRightsMaster']['Process']);
            }
           
            $data=$this->Masjclrentry->find('list',array('fields'=>array('CostCenter'),'conditions'=>array('BranchName'=>$branch_name,'Status'=>1),'group' =>array('CostCenter'))); 
     
            if(!empty($data)){
                foreach($data as $row){
                    if (in_array($row, $RolArr)){
                        echo "<input class='checkbox1' type='checkbox' value='$row' name='Process[]' checked > $row <br/>";
                    }
                    else{
                         echo "<input class='checkbox1' type='checkbox' value='$row' name='Process[]' > $row <br/>";
                    }
                }
            }
            else{
                echo "";
            }
        }
        die;  
        
    }
    
    public function get_roles(){
        $this->layout='ajax';
        if(isset($_REQUEST['UserId']) && $_REQUEST['UserId'] !=""){
            $userid = $_REQUEST['UserId'];
            $branch_name=$this->getBranchName($userid);
          
            $data = $this->LeaveRightsMaster->find('first',array('fields'=>array('Roles'),'conditions'=>array('BranchName'=>$branch_name,'UserId'=>$userid)));
           
            $RolArr= explode(',', $data['LeaveRightsMaster']['Roles']);
            
            $li="";
            $la="";
            $da="";
            $vl="";
            
            if (in_array("LEAVE ENTRY", $RolArr)){$li= "checked";}
            if (in_array("LEAVE APPROVAL", $RolArr)){$la= "checked";}
            if (in_array("DISCARD APPROVED LEAVES", $RolArr)){$da= "checked";}
            if (in_array("VIEW LEAVE DETAILS", $RolArr)){$vl= "checked";}
            
            echo "<input type='checkbox' value='LEAVE ENTRY' name='Roles[]' $li > LEAVE ENTRY <br/>";
            echo "<input type='checkbox' value='LEAVE APPROVAL' name='Roles[]' $la > LEAVE APPROVAL <br/>";
            echo "<input type='checkbox' value='DISCARD APPROVED LEAVES' name='Roles[]' $da > DISCARD APPROVED LEAVES <br/>";
            echo "<input type='checkbox' value='VIEW LEAVE DETAILS' name='Roles[]' $vl > VIEW LEAVE DETAILS <br/>"; 
            
        }
        else{
            echo "";
        }
        die;    
    }
    
    
  
    
    
    public function getBranchName($UserId){
        $dataArr=$this->UserMaster->find('first',array('fields'=>array('branch_name'),'conditions'=>array('UserActive'=>1,'username'=>$UserId)));
        return $dataArr['UserMaster']['branch_name'];
    }
}
?>