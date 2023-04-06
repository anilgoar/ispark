<?php
class TrainingLiveReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','TrainingStatusMaster','TrainingAllocationMaster','TrainingBatchMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','export_report','getbatchcode');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }    
    }
    
    public function getbatchcode(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['BatchYear'],'MONTH(StartDate)'=>$_REQUEST['BatchMonth']);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            
            $data = $this->TrainingBatchMaster->find('list',array('fields'=>array('BatchCode','BatchCode'),'conditions'=>$conditoin));
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
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
    
    public function show_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BatchCode']) && $_REQUEST['BatchCode'] !=""){
            if($_REQUEST['BioCode'] !=""){$conditoin['BioCode']=$_REQUEST['BioCode'];}else{unset($conditoin['BioCode']);}
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin1['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin1['BranchName']);}
            if($_REQUEST['BatchCode'] !="ALL"){$conditoin['BatchCode']=$_REQUEST['BatchCode'];$conditoin1['BatchCode']=$_REQUEST['BatchCode'];}else{unset($conditoin['BatchCode']);unset($conditoin1['BatchCode']);}
            
           
            
            $data = $this->TrainingAllocationMaster->find('all',array('conditions'=>$conditoin));
            
              
            if(!empty($data)){   
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>BatchCode</th>
                            <th>BatchStatus</th>
                            <th>BioCode</th>
                            <th>EmpName</th>
                            <th>StatusName</th>
                            <th>SubStatus</th>
                            <th style="text-align: center;" >CertificationDate</th>
                            <th style="text-align: center;">CertificationScore</th>
                            <th style="text-align: center;">ReCertificationScore</th>
                            <th style="text-align: center;">ReCertificationDate</th>
                            <th style="text-align: center;">Remarks</th>
                            <th style="text-align: center;">HandOverDate</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        $TrainingBatchArr = $this->TrainingBatchMaster->find('first',array('fields'=>array('TrainingStatus'),'conditions'=>array('BatchCode'=>$val['TrainingAllocationMaster']['BatchCode'])));
                        $TrainingStatus=$TrainingBatchArr['TrainingBatchMaster']['TrainingStatus'];
                            
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['BatchCode'];?></td>
                            <td><?php echo $TrainingStatus;?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['BioCode'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['EmpName'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['Status'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['SubStatus'];?></td>
                            <td style="text-align: center;"><?php if($val['TrainingAllocationMaster']['CertificationDate'] !=""){echo date("d M Y",strtotime($val['TrainingAllocationMaster']['CertificationDate']));}?></td>
                            
                            <td style="text-align: center;"><?php echo $val['TrainingAllocationMaster']['CertificationScore'];?></td>
                            <td style="text-align: center;"><?php echo $val['TrainingAllocationMaster']['RecertificationScore'];?></td>
                            <td style="text-align: center;"><?php if($val['TrainingAllocationMaster']['AtritionDate'] !=""){echo date("d M Y",strtotime($val['TrainingAllocationMaster']['AtritionDate']));}?></td>
                            <td style="text-align: center;"><?php echo $val['TrainingAllocationMaster']['Remarks'];?></td>
                            <td style="text-align: center;"><?php if($val['TrainingAllocationMaster']['HandOverDate'] !=""){echo date("d M Y",strtotime($val['TrainingAllocationMaster']['HandOverDate']));}?></td>
                        </tr>
                        <?php }?>
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
        if(isset($_REQUEST['BatchCode']) && $_REQUEST['BatchCode'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=TrainingLiveStatus.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            if($_REQUEST['BioCode'] !=""){$conditoin['BioCode']=$_REQUEST['BioCode'];}else{unset($conditoin['BioCode']);}
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['BatchCode'] !="ALL"){$conditoin['BatchCode']=$_REQUEST['BatchCode'];}else{unset($conditoin['BatchCode']);}
            $data = $this->TrainingAllocationMaster->find('all',array('conditions'=>$conditoin));
              
            if(!empty($data)){   
            ?>
                <table border="1">     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>BatchCode</th>
                            <th>BatchStatus</th>
                            <th>BioCode</th>
                            <th>EmpName</th>
                            <th>StatusName</th>
                            <th>SubStatus</th>
                            <th>CertificationDate</th>
                            <th>CertificationScore</th>
                            <th>ReCertificationScore</th>
                            <th>ReCertificationDate</th>
                            <th>Remarks</th>
                            <th>HandOverDate</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['BatchCode'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['BatchCode'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['BioCode'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['EmpName'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['Status'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['SubStatus'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['CertificationDate'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['CertificationScore'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['RecertificationScore'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['AtritionDate'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['Remarks'];?></td>
                            <td><?php echo $val['TrainingAllocationMaster']['HandOverDate'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    
    
    
    
    public function total_sundays($month,$year){
        $sundays=0;
        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i=1;$i<=$total_days;$i++)
        if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
        $sundays++;
        return $sundays;
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
    
    function dayCount($from, $to) {
        $first_date = strtotime($from);
        $second_date = strtotime($to);
        $offset = $second_date-$first_date; 
        return floor($offset/60/60/24);
    }
    
    function sundayCount($from, $to) {        
        $start = new DateTime($from);
        $end = new DateTime($to);
        $days = $start->diff($end, true)->days;

        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
        return $sundays;  
    }

    function HolydayCount($from,$to,$branch) {
        $hcnt   =   $this->HolidayMaster->query("SELECT COUNT(Id) AS TotHolyday FROM `HolidayMaster` WHERE DATE(HolydayDate) BETWEEN '$from' AND '$to' AND BranchName='$branch'"); 
        return $hcnt[0][0]['TotHolyday'];
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