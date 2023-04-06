<?php
class WorkingDetailsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','SalarData','WorkingTimeMaster');
        
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
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }    
    }
    
    public function show_report(){
       
        
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && !empty($_REQUEST['BranchName'])){

            $First_Month    =   $_REQUEST['EmpMonth'];
            $Last_Month     =   $_REQUEST['LastEmpMonth'];
            $First_Year     =   $_REQUEST['EmpYear'];
            $EmployeeCode   =   $_REQUEST['EmpCode'];
              
            $_REQUEST['EmpCtc'] !=""?$whereCTC="AND  CurrentCTC >={$_REQUEST['EmpCtc']}":$whereCTC="";
            
            $WorkingData    =   $this->WorkingTimeMaster->find('all',array('conditions'=>array('BranchName'=>$_REQUEST['BranchName']),'order'=>array('BranchName','EmpName'),'group'=>'EmpCode')); 
            
            if(!empty($WorkingData)){
            ?>
            <style>
            .table tr th{text-align: center;}
            .table tr td{text-align: center;}
            </style>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Month</th>
                            <th>CTC</th>
                            <th>PerDay</th>
                            <th>SHF</th>
                            <th>M</th>
                            <th>W</th>
                            <th>H</th>
                            <th>WH</th>
                            <th>WD</th>
                            <th>Att.H</th>
                            <th>Att.D</th>
                            <th>A</th>
                            <th>HD</th>
                            <th>DH</th>
                            <th>FTP</th>
                            <th>P</th>
                            <th>OD</th>
                            <th>L</th>
                            <th>W</th>
                            <th>H</th>
                            <th>EarnDay</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($WorkingData as $RowVal){

                        $EmpCode    =   $RowVal['WorkingTimeMaster']['EmpCode'];
                        $BranchName =   $RowVal['WorkingTimeMaster']['BranchName'];
                        $Shift      =   $RowVal['WorkingTimeMaster']['ShiftHours'];

                        $fields     =   array('EmpCode','EmpName','Designation','Branch','WorkingDays','ActualDays','EarnedDays','Leave','SalDate','CurrentCTC','WeekOff','Holidays');
                        
                        $conditions=array("EmpCode='$EmpCode' AND MONTH(SalDate) >='$First_Month' AND MONTH(SalDate) <='$Last_Month' AND YEAR(SalDate) ='$First_Year' $whereCTC");
               
                        $RowDataArr    =   $this->SalarData->find('all',array('fields'=>$fields,'conditions'=>$conditions,'order'=>array('Branch','EmpCode','SalDate'))); 
                        
                        foreach($RowDataArr as $RowData){

                            $y              =   date("Y",strtotime($RowData['SalarData']['SalDate']));
                            $m              =   date("m",strtotime($RowData['SalarData']['SalDate']));
                            $WeekOff        =   $this->total_sundays($m,$y);
                            $MonthData      =   "$m-$y";
                            

                            $holidays       =   $this->HolidayMaster->find('count',array('conditions'=>array('BranchName'=>$BranchName,'YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m)));

                            $WorkDays       =   $RowData['SalarData']['WorkingDays']-($holidays+$WeekOff);
                            $WorkHours      =   $WorkDays*$Shift;
                            $ActualHours    =   $RowData['SalarData']['ActualDays']*$Shift;

                            $OD             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'Status'=>'OD','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $P             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'Status'=>'P','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $A              =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'A','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $HD             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'HD','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $DH             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'DH','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));                    
                            $F              =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'F','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            
                            $CTC            =   $RowData['SalarData']['CurrentCTC'];
                            $PerDaySalary   =   round($CTC/$RowData['SalarData']['WorkingDays']);
                            
                            $EmpWeekOff     =   $RowData['SalarData']['WeekOff'];
                            $EmpHolidays    =   $RowData['SalarData']['Holidays'];
                            
                            $EmpName    = strlen($RowData['SalarData']['EmpName']) > 10 ? substr($RowData['SalarData']['EmpName'],0,10)."..." : $RowData['SalarData']['EmpName'];
                            $Branch    = strlen($RowData['SalarData']['Branch']) > 10 ? substr($RowData['SalarData']['Branch'],0,10)."..." : $RowData['SalarData']['Branch'];
                        ?>
                        <tr>
                            <td><?php echo $EmpName;?></td>
                            <td><?php echo $Branch;?></td>
                            <td><?php echo $MonthData;?></td>
                            <td><?php echo $CTC;?></td>
                            <td><?php echo $PerDaySalary;?></td>
                            <td><?php echo $Shift;?></td>
                            <td><?php echo $RowData['SalarData']['WorkingDays']?></td>
                            <td><?php echo $WeekOff;?></td>
                            <td><?php echo $holidays;?></td>
                            <td><?php echo $WorkHours;?></td>
                            <td><?php echo $WorkDays;?></td>
                            <td><?php echo $ActualHours;?></td>
                            <td><?php echo $RowData['SalarData']['ActualDays']?></td>
                            <td><?php echo $A;?></td>
                            <td><?php echo ($HD/2);?></td>
                            <td><?php echo ($DH/2);?></td>
                            <td><?php echo ($F/2);?></td>
                            <td><?php echo $P;?></td>
                            <td><?php echo $OD;?></td>
                            <td><?php echo $RowData['SalarData']['Leave']?></td>
                            <td><?php echo $EmpWeekOff;?></td>
                            <td><?php echo $EmpHolidays;?></td>
                            <td><?php echo $RowData['SalarData']['EarnedDays']?></td>
                        </tr>
                        <?php }}?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo "";   
            }
        }
        else{
            echo "";
        }
        die;
    }
    
    public function export_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Export".$_REQUEST['EmpMonth']."-".$_REQUEST['EmpYear'].".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $_REQUEST['BranchName']=  explode(",", $_REQUEST['BranchName']);
            
            $First_Month    =   $_REQUEST['EmpMonth'];
            $Last_Month     =   $_REQUEST['LastEmpMonth'];
            $First_Year     =   $_REQUEST['EmpYear'];
            $EmployeeCode   =   $_REQUEST['EmpCode'];
              
            $_REQUEST['EmpCtc'] !=""?$whereCTC="AND  CurrentCTC >={$_REQUEST['EmpCtc']}":$whereCTC="";
            
            $WorkingData    =   $this->WorkingTimeMaster->find('all',array('conditions'=>array('BranchName'=>$_REQUEST['BranchName']),'order'=>array('BranchName','EmpName'),'group'=>'EmpCode')); 
            
            if(!empty($WorkingData)){
            ?>
            <style>
            .table tr th{text-align: center;}
            .table tr td{text-align: center;}
            </style>
            <div class="col-sm-12">
                <table class = "table table-striped table-hover  responstable" border="1"  >     
                    <thead>
                        <tr>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Month</th>
                            <th>CTC</th>
                            <th>PerDay</th>
                            <th>SHIFT</th>
                            <th>M</th>
                            <th>W</th>
                            <th>H</th>
                            <th>WH</th>
                            <th>WD</th>
                            <th>AH</th>
                            <th>AD</th>
                            <th>A</th>
                            <th>HD</th>
                            <th>DH</th>
                            <th>FTP</th>
                            <th>P</th>
                            <th>OD</th>
                            <th>L</th>
                            <th>W</th>
                            <th>H</th>
                            <th>EarnDay</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($WorkingData as $RowVal){

                        $EmpCode    =   $RowVal['WorkingTimeMaster']['EmpCode'];
                        $BranchName =   $RowVal['WorkingTimeMaster']['BranchName'];
                        $Shift      =   $RowVal['WorkingTimeMaster']['ShiftHours'];

                        $fields     =   array('EmpCode','EmpName','Designation','Branch','WorkingDays','ActualDays','EarnedDays','Leave','SalDate','CurrentCTC','WeekOff','Holidays');
                        
                        $conditions=array("EmpCode='$EmpCode' AND MONTH(SalDate) >='$First_Month' AND MONTH(SalDate) <='$Last_Month' AND YEAR(SalDate) ='$First_Year' $whereCTC");
               
                        $RowDataArr    =   $this->SalarData->find('all',array('fields'=>$fields,'conditions'=>$conditions,'order'=>array('Branch','EmpCode','SalDate'))); 
                        
                        foreach($RowDataArr as $RowData){

                            $y              =   date("Y",strtotime($RowData['SalarData']['SalDate']));
                            $m              =   date("m",strtotime($RowData['SalarData']['SalDate']));
                            $WeekOff        =   $this->total_sundays($m,$y);
                            $MonthData      =   "$m-$y";
                            

                            $holidays       =   $this->HolidayMaster->find('count',array('conditions'=>array('BranchName'=>$BranchName,'YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m)));

                            $WorkDays       =   $RowData['SalarData']['WorkingDays']-($holidays+$WeekOff);
                            $WorkHours      =   $WorkDays*$Shift;
                            $ActualHours    =   $RowData['SalarData']['ActualDays']*$Shift;

                            $OD             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'Status'=>'OD','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $P             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'Status'=>'P','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $A              =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'A','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $HD             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'HD','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            $DH             =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'DH','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));                    
                            $F              =   $this->Masattandance->find('count',array('conditions'=>array('EmpCode'=>$RowData['SalarData']['EmpCode'],'OldStatus'=>'F','YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m)));
                            
                            $CTC            =   $RowData['SalarData']['CurrentCTC'];
                            $PerDaySalary   =   round($CTC/$RowData['SalarData']['WorkingDays'],2);
                            
                            $EmpWeekOff     =   $RowData['SalarData']['WeekOff'];
                            $EmpHolidays    =   $RowData['SalarData']['Holidays'];
                        ?>
                        <tr>
                            <td><?php echo $RowData['SalarData']['EmpName']?></td>
                            <td><?php echo $RowData['SalarData']['Branch']?></td>
                            <td><?php echo $MonthData;?></td>
                            <td><?php echo $CTC;?></td>
                            <td><?php echo $PerDaySalary;?></td>
                            <td><?php echo $Shift;?></td>
                            <td><?php echo $RowData['SalarData']['WorkingDays']?></td>
                            <td><?php echo $WeekOff;?></td>
                            <td><?php echo $holidays;?></td>
                            <td><?php echo $WorkHours;?></td>
                            <td><?php echo $WorkDays;?></td>
                            <td><?php echo $ActualHours;?></td>
                            <td><?php echo $RowData['SalarData']['ActualDays']?></td>
                            <td><?php echo $A;?></td>
                            <td><?php echo ($HD/2);?></td>
                            <td><?php echo ($DH/2);?></td>
                            <td><?php echo ($F/2);?></td>
                            <td><?php echo $P;?></td>
                            <td><?php echo $OD;?></td>
                            <td><?php echo $RowData['SalarData']['Leave']?></td>
                            <td><?php echo $EmpWeekOff;?></td>
                            <td><?php echo $EmpHolidays;?></td>
                            <td><?php echo $RowData['SalarData']['EarnedDays']?></td>
                        </tr>
                        <?php }}?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo "";   
            }
        }
        else{
            echo "";
        }
        die;
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
            
            //$conditoin=array('Status'=>1);
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