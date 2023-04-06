<?php
class DeductionDiscardsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','UploadDeductionMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','export_report');
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
            if(isset($this->request->data['check'])){
                $OdIdArr=$this->request->data['check'];
                foreach ($OdIdArr as $Id){
                    $exp=  explode("##", $Id);
                    $br=$exp[0];
                    $co=$exp[1];
                    $sm=$exp[2];
                   
                    $this->UploadDeductionMaster->query("DELETE FROM `upload_deduction` WHERE BranchName='$br' AND CostCenter='$co' AND SalaryMonth='$sm'");
                   
                    //$this->UploadDeductionMaster->query("DELETE FROM `upload_deduction` WHERE Id='$Id'");  
                }
                $this->Session->setFlash('<span style="font-weight:bold;color:green;" >This month diduction discard successfully.</span>'); 
            }
            else{
                $this->Session->setFlash('<span style="font-weight:bold;color:red;" >Please select to discard diduction.</span>'); 
            } 
            $this->redirect(array('controller'=>'DeductionDiscards','action' => 'index'));
        }  
        
        
        
    }
    
    public function show_report(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $conditoin=array('SalaryMonth'=>date('Y-m',strtotime($_REQUEST['StartDate'])));
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
        
            
            
            $cnt   =   $this->UploadDeductionMaster->find('count',array('conditions'=>$conditoin,'group'=>'CostCenter'));
            $data   =   $this->UploadDeductionMaster->find('all',array('conditions'=>$conditoin,'group'=>'CostCenter'));
            
            
            
            
            if(!empty($data)){   
            ?>
            <div class="col-sm-7" <?php if($cnt >=10){?> style="overflow-y:scroll;height:500px; " <?php }?> >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">CostCenter</th>
                            <th style="text-align: center;">SalaryMonth</th>
                            <th style="text-align: center;">Current Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        ?>
                        <tr>
                            <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['UploadDeductionMaster']['BranchName']."##".$val['UploadDeductionMaster']['CostCenter']."##".$val['UploadDeductionMaster']['SalaryMonth'];?>" name="check[]"></center></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo date('M-Y',strtotime($val['UploadDeductionMaster']['SalaryMonth']));?></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['ProcessStatus'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
            </div>
            <div class="col-sm-12">
                <input type="Submit" name="Submit" value="Discard" class="btn btn-primary pull-right btn-new" >
            </div>
            <?php   
           
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
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