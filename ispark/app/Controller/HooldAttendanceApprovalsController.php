<?php
class HooldAttendanceApprovalsController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OldAttendanceIssue');
        
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
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'))));
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
            $branch_name=$this->request->data['HooldAttendanceApprovals']['branch_name'];
            $branch_issue=$this->request->data['HooldAttendanceApprovals']['branch_issue'];
            
            if($branch_name !="" && $branch_issue !="" ){
                $conditoin=array('ApproveFirst'=>'Yes','ApproveSecond'=>NULL);
                if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
                if($branch_issue !="ALL"){$conditoin['IssueType']=$branch_issue;}else{unset($conditoin['IssueType']);}

                if(isset($this->request->data['Submit'])){
                    $SubmitType=$this->request->data['Submit'];
                    if($SubmitType !=""){
                        if($SubmitType =="Approve"){
                            $status="Yes";
                        }
                        else if($SubmitType =="Discard"){
                           $status="No";
                        }

                        if(isset($this->request->data['check'])){
                            $OdIdArr=$this->request->data['check'];
                            foreach ($OdIdArr as $Id){
                                
                                $data=$this->OldAttendanceIssue->find('first',array('conditions'=>array('Id'=>$Id)));
                                $empcode=$data['OldAttendanceIssue']['EmpCode'];
                                $empbranch=$data['OldAttendanceIssue']['BranchName'];
                                $empFromDate=$data['OldAttendanceIssue']['StartDate'];
                                $empToDate=$data['OldAttendanceIssue']['EndDate'];
                                $empStatus=$data['OldAttendanceIssue']['ExpectedStatus'];
                      
                                $this->OldAttendanceIssue->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondBy'=>"'".$this->Session->read('email')."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                                //$this->OldAttendanceIssue->query("UPDATE `Attandence` SET `Status`='$empStatus' WHERE EmpCode='$empcode' AND BranchName='$empbranch' AND date(AttandDate) between '$empFromDate' and '$empToDate' ");
                                
                            }
                            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your selected record update successfully.</span>'); 
                        }
                        else{
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve/not approve attendance issue.</span>'); 
                        }
                    }
                }
                
                
                $this->set('OdArr',$this->OldAttendanceIssue->find('all',array('conditions'=>$conditoin)));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select branch name/issue.</span>');   
            }
             
        }     
    }
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=HoOldAttendanceApproval.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        if(isset($_REQUEST['branch_name']) && isset($_REQUEST['branch_issue'])){
            $branch_name    = $_REQUEST['branch_name'];
            $branch_issue   = $_REQUEST['branch_issue'];   
            
            $conditoin=array('ApproveFirst'=>'Yes','ApproveSecond'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($branch_issue !="ALL"){$conditoin['IssueType']=$branch_issue;}else{unset($conditoin['IssueType']);}

            $data=$this->OldAttendanceIssue->find('all',array('conditions'=>$conditoin));
            
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
                    <td><?php echo $val['OldAttendanceIssue']['EmpCode'];?></td>
                    <td><?php echo $val['OldAttendanceIssue']['BioCode'];?></td>
                    <td><?php echo $val['OldAttendanceIssue']['EmpName'];?></td>
                    <td><?php echo $val['OldAttendanceIssue']['BranchName'];?></td>
                    <td><?php echo date('d M y',strtotime($val['OldAttendanceIssue']['AttandDate'])) ;?></td>
                    <td><?php echo $val['OldAttendanceIssue']['Reason'];?></td>
                    <td><?php echo $val['OldAttendanceIssue']['CurrentStatus'];?></td>
                    <td><?php echo $val['OldAttendanceIssue']['ExpectedStatus'];?></td>
                    <td>
                        <?php 
                        if($val['OldAttendanceIssue']['ApproveSecond'] =="Yes"){
                            echo "Approve";
                        }
                        else if($val['OldAttendanceIssue']['ApproveSecond'] =="No"){
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