<?php
class BranchAttandIssueApprovalsController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','BranchAttandIssueMaster');
        
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
        $this->set('OdArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL),'order'=>"EmpCode,AttandDate")));
        //$this->set('OdArr',$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('ApproveFirst'=>NULL)))); 
        
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
                            $this->BranchAttandIssueMaster->updateAll(array('ApproveFirst'=>"'".$status."'",'ApproveFirstBy'=>"'".$this->Session->read('email')."'",'ApproveFirstDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve/not approve record.</span>'); 
                        $this->redirect(array('action'=>'index'));
                    }
                   $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your request update scccessfully.</span>');   
                }
               
            }
            
            $this->redirect(array('action'=>'index'));   
        }     
    }
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=AttandanceIssueApprovalReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
            
        $branchName = $this->Session->read('branch_name');
        $data=$this->BranchAttandIssueMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL))); 
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
                    if($val['BranchAttandIssueMaster']['ApproveFirst'] =="Yes"){
                        echo "Approve";
                    }
                    else if($val['BranchAttandIssueMaster']['ApproveFirst'] =="No"){
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