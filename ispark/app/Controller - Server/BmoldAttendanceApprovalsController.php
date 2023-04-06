<?php
class BmoldAttendanceApprovalsController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','OldAttendanceIssue');
        
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
        $this->set('OdArr',$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL)))); 
        
        if($this->request->is('Post')){
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
                            $this->OldAttendanceIssue->updateAll(array('ApproveFirst'=>"'".$status."'",'ApproveFirstBy'=>"'".$this->Session->read('email')."'",'ApproveFirstDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branchName));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve/not approve record.</span>'); 
                        $this->redirect(array('action'=>'index'));
                    }
                   $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your request save scccessfully.</span>');   
                }
               
            }
            
            $this->redirect(array('action'=>'index'));   
        }     
    }
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=OldAttandanceIssueApproval.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
            
        $branchName = $this->Session->read('branch_name');
        $data=$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL))); 
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
                    if($val['OldAttendanceIssue']['ApproveFirst'] =="Yes"){
                        echo "Approve";
                    }
                    else if($val['OldAttendanceIssue']['ApproveFirst'] =="No"){
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
        die;
    }
    
}
?>