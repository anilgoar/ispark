 <?php
class EpfEsicAutomationsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster','SalarData','DesignationNameMaster','UploadDeductionMaster','IncomtaxMaster','LoanMaster','UploadIncentiveBreakup','OldAttendanceIssue');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('epf','esic','epf_report','esic_report');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function epf(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchNameAll',$BranchArray);
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
    }
    
    public function epf_report(){
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=epf.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $Company    =   $_REQUEST['Company'];
        $m          =   $_REQUEST['EmpMonthExp'];
        $y          =   $_REQUEST['EmpYearExp'];
        $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $SalayDay   =   $y."-".$m."-".$mwd;
        
        echo "<table border='1'>";
        echo "<tr>
                <th>Emp code</th>
                <th>UAN Number</th>
                <th>Emp Name</th>
                <th>Paybal Gross</th>
                <th>Basic</th>
                <th>Pension Salary</th>
                <th>Pension Salary</th>
                <th>12 % of basic</th>
                <th>8.33% of pension</th>
                <th>basic-pension</th>
                <th>NCP Date</th>
                <th>0</th>
            </tr>";
            
            $dataArr    =   $this->SalarData->query("
            SELECT t1.EmpCode,t1.EmpName,t1.Branch,t1.Gross1,t1.Basic1,t1.WorkingDays,
            t1.EarnedDays,t1.ESICNo,t2.UAN,t2.ResignationDate
            FROM `salary_data` t1,masjclrentry t2
            WHERE t1.EmpCode=t2.EmpCode AND DATE(t1.SalayDate)='$SalayDay' 
            AND SUBSTRING(t1.EmpCode,1,3)='$Company' AND t2.EmpType='ONROLL' AND t1.PFELig='YES'");
            
            foreach($dataArr as $data){
                
                $PensionSalary  =   $data['t1']['Basic1'] > 15000?15000:$data['t1']['Basic1'];
                $basicPer       =   round($data['t1']['Basic1']*12/100);
                $pensionPer     =   round($PensionSalary*8.33/100);
                $basicPension   =   ($basicPer-$pensionPer);
                $ncpDate        =   ($data['t1']['WorkingDays']-$data['t1']['EarnedDays']);
                
                echo "<tr>
                        <td>".$data['t1']['EmpCode']."</td>
                        <td>".$data['t2']['UAN']."</td>
                        <td>".$data['t1']['EmpName']."</td>
                        <td>".$data['t1']['Gross1']."</td>
                        <td>".$data['t1']['Basic1']."</td>
                        <td>$PensionSalary</td>
                        <td>$PensionSalary</td>
                        <td>$basicPer</td>
                        <td>$pensionPer</td>
                        <td>$basicPension</td>
                        <td>$ncpDate</td>
                        <td>0</td>
                    </tr>";
            }
       
        echo "</table>";  
        die; 
    }
    
    
    public function esic(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchNameAll',$BranchArray);
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
    }
    
    public function esic_report(){
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=esic.xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 
        
        
        $Company    =   $_REQUEST['Company'];
        $Branch     =   $_REQUEST['branch_name'];
        $m          =   $_REQUEST['EmpMonthExp'];
        $y          =   $_REQUEST['EmpYearExp'];
        $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $SalayDay   =   $y."-".$m."-".$mwd;
        
        echo "<table border='1'>";
        echo "<tr>
                <th>IP Number</th>
                <th>IP Name( Only alphabets and space )</th>
                <th>No of Days for which wages paid/payable during the month</th>
                <th>Total Monthly Wages</th>
                <th>Reason Code for Zero workings days(numeric only; provide 0 for all other reasons- Click on the link for reference)</th>
                <th>Last Working Day( Format DD/MM/YYYY  or DD-MM-YYYY)</th>
            </tr>";
        
        foreach($Branch as $branchname){
            
            $dataArr    =   $this->SalarData->query("
            SELECT t1.EmpCode,t1.EmpName,t1.Branch,t1.Gross1,t1.Basic1,t1.WorkingDays,
            t1.EarnedDays,t1.ESICNo,t2.UAN,t2.ResignationDate
            FROM `salary_data` t1,masjclrentry t2
            WHERE t1.EmpCode=t2.EmpCode AND  t1.Branch='$branchname' AND DATE(t1.SalayDate)='$SalayDay' 
            AND SUBSTRING(t1.EmpCode,1,3)='$Company' AND t2.EmpType='ONROLL' AND t1.ESIElig='YES'");
           
            foreach($dataArr as $data){
                $LeftDate       =   ($data['t2']['ResignationDate'] !=""?'=TEXT("'.$data['t2']['ResignationDate'].'","dd-mm-YYYY")':"");
                $LeftReasion    =   $LeftDate !=""?2:"";
                
                echo "<tr>
                        <td>'".$data['t1']['ESICNo']."</td>
                        <td>".$data['t1']['EmpName']."</td>
                        <td>'".$data['t1']['EarnedDays']."</td>
                        <td>'".$data['t1']['Gross1']."</td>

                        <td>$LeftReasion</td>
                        <td>$LeftDate</td>
                    </tr>";
            }
        }
        echo "</table>";  
        
        die; 
    }
    
}
?>