<?php
class DiscardAttendanceIssuesController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','BranchAttandIssueMaster','Masjclrentry','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','report');
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
            $branch_name=$this->request->data['DiscardAttendanceIssues']['branch_name'];
            
            if($branch_name !=""){
                $conditoin=array('ApproveSecond'=>'Yes');
                if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
               
                if(isset($this->request->data['Submit'])){
                    $SubmitType=$this->request->data['Submit'];
                    if($SubmitType !=""){
                        if($SubmitType =="Discard"){
                           $status="No";
                        }

                        if(isset($this->request->data['check'])){
                            $OdIdArr=$this->request->data['check'];
                            foreach ($OdIdArr as $Id){
                                
                                $data=$this->BranchAttandIssueMaster->find('first',array('conditions'=>array('Id'=>$Id)));
                                
                                $empcode=$data['BranchAttandIssueMaster']['EmpCode'];
                                $empbranch=$data['BranchAttandIssueMaster']['BranchName'];
                                $empAttandDate=$data['BranchAttandIssueMaster']['AttandDate'];
                                $empStatus=$data['BranchAttandIssueMaster']['CurrentStatus'];
                                
                                $ProcessDate      =   date('Y-m',strtotime(trim(addslashes($empAttandDate))));
                                $enmArr     =   $this->Masjclrentry->find('first',array('fields'=>array('CostCenter'),'conditions'=>array('Status'=>1,'EmpCode'=>$empcode)));
                                $CostCenter =   $enmArr['Masjclrentry']['CostCenter'];
                                
                                $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('BranchName'=>$empbranch,'CostCenter'=>$CostCenter,'ProcessMonth'=>$ProcessDate)));
                                
                                if($ProAttArr < 1){
                                    //$this->BranchAttandIssueMaster->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                                    $this->BranchAttandIssueMaster->query("UPDATE `Attandence` SET `Status`='$empStatus',OldStatus=NULL WHERE EmpCode='$empcode' AND BranchName='$empbranch' AND date(AttandDate)='$empAttandDate'");
                                    $this->BranchAttandIssueMaster->query("DELETE FROM `BranchWiseAttandanceIssue` WHERE Id='$Id';");
                                }
                                else{
                                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>'); 
                                    $this->redirect(array('action'=>'index')); 
                                }
                                
                            }
                            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your selected record update successfully.</span>'); 
                        }
                        else{
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to discard attendance issue.</span>'); 
                        }
                    }
                }
                
                
                $this->set('OdArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,AttandDate")));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select branch name.</span>');   
            }
            
            
           
        }     
    }
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=HoApprovalReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        if(isset($_REQUEST['branch_name'])){
            $branch_name    = $_REQUEST['branch_name'];
            
            $conditoin=array('ApproveSecond'=>'Yes');
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            
            $data=$this->BranchAttandIssueMaster->find('all',array('conditions'=>$conditoin));
            
            ?>
            <table border="1" >          
                <tr>
                    <th>Emp Code</th>
                    <th>Bio Code</th>
                    <th>Emp Name</th>
                    <th>Branch</th>
                    <th>Attend Date</th>
                    <th>Reason</th>
                    <th>Current Status</th>
                    <th>Expected Status</th>
                    <th>Status</th>
                </tr>             
                <?php foreach ($data as $val){?>
                <tr>
                    <td><?php echo $val['BranchAttandIssueMaster']['EmpCode'];?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['BioCode'];?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['EmpName'];?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['BranchName'];?></td>
                    <td><?php echo date('d M y',strtotime($val['BranchAttandIssueMaster']['AttandDate'])) ;?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['Reason'];?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['CurrentStatus'];?></td>
                    <td><?php echo $val['BranchAttandIssueMaster']['ExpectedStatus'];?></td>
                    <td>
                        <?php 
                        if($val['BranchAttandIssueMaster']['ApproveSecond'] =="Yes"){
                            echo "Approve";
                        }
                        else if($val['BranchAttandIssueMaster']['ApproveSecond'] =="No"){
                            echo "Not Approve";
                        }
                        else{
                            echo "Pending"; 
                        }
                        ?>
                    </td>
                </tr>
                <?php }?>    
            </table>
            <?php
            }
        die;
    }
    
}
?>