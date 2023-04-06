<?php
class IncentiveDiscardsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','UploadDeductionMaster','UploadIncentiveBreakup');
        
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
        
        
        if($this->request->is('Post')){             
            if(isset($this->request->data['check'])){
                $OdIdArr=$this->request->data['check'];
                foreach ($OdIdArr as $Id){
                    //$this->UploadIncentiveBreakup->query("DELETE FROM `upload_incentive_breakup` WHERE CostCenter='$Id'");  
                    
                    $this->UploadIncentiveBreakup->query("update `upload_incentive_breakup` set ApproveStatus=NULL WHERE CostCenter='$Id' and UploadType='UploadIncentive'");  
                }
                $this->Session->setFlash('<span style="font-weight:bold;color:green;" >This month Incentive discard successfully.</span>'); 
            }
            else{
                $this->Session->setFlash('<span style="font-weight:bold;color:red;" >Please select to Incentive discard.</span>'); 
            } 
            $this->redirect(array('controller'=>'IncentiveDiscards','action' => 'index'));
        }  
        
        
        
    }
    
    public function show_report(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $conditoin=array('YEAR(SalaryMonth)'=>date('Y',strtotime($_REQUEST['StartDate'])),'MONTH(SalaryMonth)'=>date('m',strtotime($_REQUEST['StartDate'])),'ApproveStatus'=>'Approve','UploadType'=>'UploadIncentive');
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
        

            $data   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin,'group'=>'CostCenter'));
            
            
         
            
            if(!empty($data)){   
            ?>
            <div class="col-sm-7"  >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >&#10004;</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">CostCenter</th>
                          
                            <th style="text-align: center;width:100px;">Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        ?>
                        <tr>
                            <td><center><input class="checkbox" type="checkbox" value="<?php echo $val['UploadIncentiveBreakup']['CostCenter'];?>" name="check[]"></center></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['CostCenter'];?></td>
                            
                            <td style="text-align: center;">
                                <?php if($val['UploadIncentiveBreakup']['ApproveStatus'] =="Approve"){echo "Processed";} ;?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
            </div><br/>
            <div class="col-sm-7">
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