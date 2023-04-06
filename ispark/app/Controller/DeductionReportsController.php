<?php
class DeductionReportsController extends AppController {
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
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }     
    }
    
    public function show_report(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $conditoin=array('SalaryMonth'=>date('Y-m',strtotime($_REQUEST['StartDate'])));
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
        
            
            
            $cnt   =   $this->UploadDeductionMaster->find('count',array('conditions'=>$conditoin));
            $data   =   $this->UploadDeductionMaster->find('all',array('conditions'=>$conditoin));
            
            
            
            
            if(!empty($data)){   
            ?>
            <div class="col-sm-12" <?php if($cnt >=10){?> style="overflow-y:scroll;height:500px; " <?php }?> >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="width:30px;text-align: center;" >SNo</th>
                            <th style="width:40px;text-align: center;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="width:40px;text-align: center;">MobileDeduction</th>
                            <th style="width:40px;text-align: center;">ShortCollection</th>
                            <th style="width:40px;text-align: center;">AssetRecovery</th>
                            <th style="width:40px;text-align: center;">ProfessionalTax</th>
                            <th style="width:40px;text-align: center;">LeaveDeduction</th>
                            <th style="width:40px;text-align: center;">Insurance</th>
                            <th style="width:40px;text-align: center;">OtherDeduction</th>
                            <th >Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $MobDed=0;
                        $SotCol=0;
                        $AstRec=0;
                        $PofTax=0;
                        $LeaDed=0;
                        $Insura=0;
                        $OthDed=0;
                        $i=1; foreach($data as $val){
                            $MobDed=$MobDed+$val['UploadDeductionMaster']['MobileDeduction'];
                            $SotCol=$SotCol+$val['UploadDeductionMaster']['ShortCollection'];
                            $AstRec=$AstRec+$val['UploadDeductionMaster']['AssetRecovery'];
                            $PofTax=$PofTax+$val['UploadDeductionMaster']['ProfessionalTax'];
                            $LeaDed=$LeaDed+$val['UploadDeductionMaster']['LeaveDeduction'];
                            $Insura=$Insura+$val['UploadDeductionMaster']['Insurance'];
                            $OthDed=$OthDed+$val['UploadDeductionMaster']['OthersDeduction'];
                        ?>
                        <tr>
                            <td style="text-align: center;" ><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['EmpCode'];?></td>
                            <td><?php echo $val['UploadDeductionMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['MobileDeduction'] !=""){ echo $val['UploadDeductionMaster']['MobileDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ShortCollection'] !=""){ echo $val['UploadDeductionMaster']['ShortCollection'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['AssetRecovery'] !=""){ echo $val['UploadDeductionMaster']['AssetRecovery'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ProfessionalTax'] !=""){ echo $val['UploadDeductionMaster']['ProfessionalTax'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['LeaveDeduction'] !=""){ echo $val['UploadDeductionMaster']['LeaveDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['Insurance'] !=""){ echo $val['UploadDeductionMaster']['Insurance'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['OthersDeduction'] !=""){ echo $val['UploadDeductionMaster']['OthersDeduction'];}else{echo 0;}?></td>
                            
                            
                            <td><?php echo $val['UploadDeductionMaster']['Remarks'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: center;">Total</td>
                            <td style="text-align: center;"><?php echo $MobDed;?></td>
                            <td style="text-align: center;"><?php echo $SotCol;?></td>
                            <td style="text-align: center;"><?php echo $AstRec;?></td>
                            <td style="text-align: center;"><?php echo $PofTax;?></td>
                            <td style="text-align: center;"><?php echo $LeaDed;?></td>
                            <td style="text-align: center;"><?php echo $Insura;?></td>
                            <td style="text-align: center;"><?php echo $OthDed;?></td>
                            <td style="text-align: center;"><?php echo ($MobDed+$SotCol+$AstRec+$PofTax+$LeaDed+$Insura+$OthDed);?></td>
                        </tr>
                    </tbody>        
                </table>
            </div>
            <?php   
           
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    
    public function export_report(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=DeductionReports.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            
            $conditoin=array('SalaryMonth'=>date('Y-m',strtotime($_REQUEST['StartDate'])));
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
        
            
            
            $cnt   =   $this->UploadDeductionMaster->find('count',array('conditions'=>$conditoin));
            $data   =   $this->UploadDeductionMaster->find('all',array('conditions'=>$conditoin));
            

            ?>
                <table border="1"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;" >SNo</th>
                            <th style="text-align: center;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="text-align: center;">MobileDeduction</th>
                            <th style="text-align: center;">ShortCollection</th>
                            <th style="text-align: center;">AssetRecovery</th>
                            <th style="text-align: center;">ProfessionalTax</th>
                            <th style="text-align: center;">LeaveDeduction</th>
                            <th style="text-align: center;">Insurance</th>
                            <th style="text-align: center;">OtherDeduction</th>
                            <th style="text-align: center;">TotalDeduction</th>
                            <th >Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $MobDed=0;
                        $SotCol=0;
                        $AstRec=0;
                        $PofTax=0;
                        $LeaDed=0;
                        $Insura=0;
                        $OthDed=0;
                        $i=1; foreach($data as $val){
                            $MobDed=$MobDed+$val['UploadDeductionMaster']['MobileDeduction'];
                            $SotCol=$SotCol+$val['UploadDeductionMaster']['ShortCollection'];
                            $AstRec=$AstRec+$val['UploadDeductionMaster']['AssetRecovery'];
                            $PofTax=$PofTax+$val['UploadDeductionMaster']['ProfessionalTax'];
                            $LeaDed=$LeaDed+$val['UploadDeductionMaster']['LeaveDeduction'];
                            $Insura=$Insura+$val['UploadDeductionMaster']['Insurance'];
                            $OthDed=$OthDed+$val['UploadDeductionMaster']['OthersDeduction'];
                            
                            
                            $totalDed=(
                                    $val['UploadDeductionMaster']['MobileDeduction']+
                                    $val['UploadDeductionMaster']['ShortCollection']+
                                    $val['UploadDeductionMaster']['AssetRecovery']+
                                    $val['UploadDeductionMaster']['ProfessionalTax']+
                                    $val['UploadDeductionMaster']['LeaveDeduction']+
                                    $val['UploadDeductionMaster']['Insurance']+
                                    $val['UploadDeductionMaster']['OthersDeduction']
                                    );
                            
                        ?>
                        <tr>
                            <td style="text-align: center;" ><?php echo $i++;?></td>
                            <td style="text-align: center;"><?php echo $val['UploadDeductionMaster']['EmpCode'];?></td>
                            <td><?php echo $val['UploadDeductionMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['MobileDeduction'] !=""){ echo $val['UploadDeductionMaster']['MobileDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ShortCollection'] !=""){ echo $val['UploadDeductionMaster']['ShortCollection'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['AssetRecovery'] !=""){ echo $val['UploadDeductionMaster']['AssetRecovery'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['ProfessionalTax'] !=""){ echo $val['UploadDeductionMaster']['ProfessionalTax'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['LeaveDeduction'] !=""){ echo $val['UploadDeductionMaster']['LeaveDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['Insurance'] !=""){ echo $val['UploadDeductionMaster']['Insurance'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($val['UploadDeductionMaster']['OthersDeduction'] !=""){ echo $val['UploadDeductionMaster']['OthersDeduction'];}else{echo 0;}?></td>
                            <td style="text-align: center;"><?php if($totalDed !=""){ echo $totalDed;}else{echo 0;}?></td>
                            
                            <td><?php echo $val['UploadDeductionMaster']['Remarks'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: center;">Total</td>
                            <td style="text-align: center;"><?php echo $MobDed;?></td>
                            <td style="text-align: center;"><?php echo $SotCol;?></td>
                            <td style="text-align: center;"><?php echo $AstRec;?></td>
                            <td style="text-align: center;"><?php echo $PofTax;?></td>
                            <td style="text-align: center;"><?php echo $LeaDed;?></td>
                            <td style="text-align: center;"><?php echo $Insura;?></td>
                            <td style="text-align: center;"><?php echo $OthDed;?></td>
                            <td style="text-align: center;"><?php echo ($MobDed+$SotCol+$AstRec+$PofTax+$LeaDed+$Insura+$OthDed);?></td>
                            <td></td>
                        </tr>
                    </tbody>        
                </table>
            <?php   
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