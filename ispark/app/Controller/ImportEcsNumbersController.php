<?php
class ImportEcsNumbersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster','SalarData','DesignationNameMaster','UploadDeductionMaster','IncomtaxMaster','LoanMaster','UploadIncentiveBreakup','OldAttendanceIssue');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','pre_month_ecsno','export_report','total_employees','show_report','getcostcenter','delete_report','export_ecs','export_ecs_details');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $data       =   $this->request->data;
           
            $branch     =   $data['ImportEcsNumbers']['branch_name'];
            $exp        =   explode("-", $data['EmpMonth']);
            $m          =   $exp[1];
            $y          =   $exp[0];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            $CostCenter =   $data['CostCenter'];
            
            $csv_file=$_FILES['UploadEcs']['tmp_name'];
            $FileTye=$_FILES['UploadEcs']['type'];
            $info=explode(".",$_FILES['UploadEcs']['name']);
            $PrintDate=date('Y-m-d');
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn=count($filedata);
                    if($totalcolumn ==5){   
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $EmpCode    =   $data[0];
                            $EmpName    =   $data[1];
                            $EcsNumb    =   $data[2];
                            $EcsDate    = date('Y-m-d',strtotime($data[3]));
                            $EcsBranch  =   $data[4];
                            
                            // AND CostCenter='$CostCenter'
                            
                            if($branch !=$EcsBranch){
                                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Branch name mismatch please select correct branch.</span>'); 
                                $this->redirect(array('action'=>'index'));  
                            }
                            else{
                                if($EcsNumb !=""){
                                    $this->SalarData->query("UPDATE salary_data SET ChequeNumber='$EcsNumb',ChequeDate='$EcsDate',PrintDate='$PrintDate',SalaryReceiveStatus='YES' WHERE Branch='$branch' AND EmpCode='$EmpCode' AND date(SalayDate)='$SalayDay'"); 
                                }
                                else{
                                    $this->SalarData->query("UPDATE salary_data SET ChequeNumber=NULL,ChequeDate=NULL,PrintDate=NULL,SalaryReceiveStatus=NULL WHERE Branch='$branch' AND EmpCode='$EmpCode' AND date(SalayDate)='$SalayDay'");
                                }
                                
                            }
                        }
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >ECS Number update successfully for this branch.</span>'); 
                        $this->redirect(array('action'=>'index'));  
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>'); 
                        $this->redirect(array('action'=>'index'));  
                    }
		}
            }
            else{
		$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>'); 
                $this->redirect(array('action'=>'index'));  
            }

 
        }
    }
    
    public function pre_month_ecsno(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $data       =   $this->request->data;
           
            $branch     =   $data['ImportEcsNumbers']['branch_name'];
            $exp        =   explode("-", $data['EmpMonth']);
            $m          =   $exp[1];
            $y          =   $exp[0];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            $CostCenter =   $data['CostCenter'];
            
            $csv_file=$_FILES['UploadEcs']['tmp_name'];
            $FileTye=$_FILES['UploadEcs']['type'];
            $info=explode(".",$_FILES['UploadEcs']['name']);
            $PrintDate=date('Y-m-d');
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn=count($filedata);
                    if($totalcolumn ==5){   
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $EmpCode    =   $data[0];
                            $EmpName    =   $data[1];
                            $EcsNumb    =   $data[2];
                            $EcsDate    = date('Y-m-d',strtotime($data[3]));
                            $EcsBranch  =   $data[4];
                            
                            // AND CostCenter='$CostCenter'
                            
                            if($branch !=$EcsBranch){
                                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Branch name mismatch please select correct branch.</span>'); 
                                $this->redirect(array('action'=>'pre_month_ecsno'));  
                            }
                            else{
                                if($EcsNumb !=""){
                                    $this->SalarData->query("UPDATE salary_data SET ChequeNumber='$EcsNumb',ChequeDate='$EcsDate',PrintDate='$PrintDate',SalaryReceiveStatus='YES' WHERE Branch='$branch' AND EmpCode='$EmpCode' AND date(SalayDate)='$SalayDay'"); 
                                }
                                else{
                                    $this->SalarData->query("UPDATE salary_data SET ChequeNumber=NULL,ChequeDate=NULL,PrintDate=NULL,SalaryReceiveStatus=NULL WHERE Branch='$branch' AND EmpCode='$EmpCode' AND date(SalayDate)='$SalayDay'");
                                }
                                
                            }
                        }
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >ECS Number update successfully for this branch.</span>'); 
                        $this->redirect(array('action'=>'pre_month_ecsno'));  
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>'); 
                        $this->redirect(array('action'=>'pre_month_ecsno'));  
                    }
		}
            }
            else{
		$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>'); 
                $this->redirect(array('action'=>'pre_month_ecsno'));  
            }

 
        }
    }
    

    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=EcsNumber.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $CostCenter =   $_REQUEST['CostCenter'];
            $exp        =   explode("-", $_REQUEST['EmpMonth']);
            $m          =   $exp[1];
            $y          =   $exp[0];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
            $dataArr   =   $this->SalarData->find('all',array('conditions'=>array('Branch'=>$_REQUEST['BranchName'],'CostCenter'=>$CostCenter,'date(SalayDate)'=>$SalayDay)));
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>Branch</th>
                        <th>CostCenter</th>
                        <th>ECS Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ ?>
                    <tr>
                        <td><?php echo $data['SalarData']['EmpCode'];?></td>
                        <td><?php echo $data['SalarData']['EmpName'];?></td>
                        <td><?php echo $data['SalarData']['Branch'];?></td>
                        <td><?php echo $data['SalarData']['CostCenter'];?></td>
                        <td><?php echo $data['SalarData']['ChequeNumber'];?></td>  
                    </tr>
                    <?php }?>
                </tbody>
            </table>
           <?php
           die;
        }
    }
    
    public function delete_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $CostCenter =   $_REQUEST['CostCenter'];
            $exp        =   explode("-", $_REQUEST['EmpMonth']);
            $m          =   $exp[1];
            $y          =   $exp[0];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
        
            $this->SalarData->query("update `salary_data` set ChequeNumber=NULL WHERE Branch='{$_REQUEST['BranchName']}' AND CostCenter='$CostCenter' AND DATE(SalayDate)='$SalayDay'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This branch ecs number delete successfully.</span>'); 
            $this->redirect(array('action'=>'index'));  
            //$url=$this->webroot.'ProcessSalarys?AX=MTA3';
            //echo "<script>window.location.href = '$url';</script>";die;
        }
    }
    
     
    public function existMark($emcod,$BranchName,$CostCenter){
        return $this->OnSiteAttendanceMaster->find('first',array('conditions'=>array('EmpCode'=>$emcod,'BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'SalMonth'=>date('Y-m', strtotime(date('Y-m')." -1 month"))))); 
    }
    
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
    public function total_employees1($CostCenter,$branchName){
        $m=date('m', strtotime(date('Y-m')." -1 month"));
        $y=date('Y');
        return $this->Masjclrentry->find('count', array('conditions' => array('BranchName'=>$branchName,'MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'CostCenter'=>$CostCenter,'Status'=>0)));
    }
    
    public function process_status($CostCenter,$branchName){
        $m=date('m', strtotime(date('Y-m')." -1 month"));
        $y=date('Y');
        return $this->ProcessAttendanceMaster->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>"$y-$m")));
    }
    
    public function total_sundays($month,$year){
        $sundays=0;
        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i=1;$i<=$total_days;$i++)
        if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
        $sundays++;
        return $sundays;
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
    
    
    public function getcostcenter(){
        
        $branchName =   $_REQUEST['BranchName'];
        $exp=  explode("-", $_REQUEST['EmpMonth']);
        
        $y          = $exp[0];
        $m          = $exp[1];
		
		
		$mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $SalayDay   =   $y."-".$m."-".$mwd;
        
        // Manju@123
        
        // hr.meerut@teammas.in
		
		$data = $this->SalarData->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('Branch'=>$branchName,'SalayDate'=>$SalayDay)));
            
        
        //$data = $this->ProcessAttendanceMaster->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('BranchName'=>$branchName,'ProcessMonth'=>"$y-$m",'FinializeStatus'=>'Yes')));

        if(!empty($data)){
            echo "<option value=''>Select</option>";
            foreach ($data as $val){
                echo "<option value='$val'>$val</option>";
            }
            die;
        }
        else{
            echo "<option value=''>Select</option>";die;
        }
        
    }
    
    
    public function export_ecs(){
        $this->layout='home';
    }
    
    public function export_ecs_details(){
        
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=EcsNumber.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
    
        $dataArr   =  $this->SalarData->query("select EmpCode,EmpName,Branch,CostCenter,ChequeNumber,ChequeDate,SalayDate,PrintDate,NetSalary from  salary_data where date(PrintDate) between '$FromDate' and '$ToDate' AND SalaryReceiveStatus='YES' AND PrintDate IS NOT NULL order by Branch"); 
         
        //echo "<pre>";
        //print_r($dataArr);die;
        
        //EmpCode,EmpName,Branch,CostCenter,ChequeNumber,ChequeDate,SalayDate,PrintDate,NetSalary
            
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>Branch</th>
                        <th>CostCenter</th>
                        <th>NetSalary</th>
                        <th>SalaryDate</th>
                        <th>ECS Number</th>
                        <th>ECS Date</th>
                        <th>Upload Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ ?>
                    <tr>
                        <td><?php echo $data['salary_data']['EmpCode'];?></td>
                        <td><?php echo $data['salary_data']['EmpName'];?></td>
                        <td><?php echo $data['salary_data']['Branch'];?></td>
                        <td><?php echo $data['salary_data']['CostCenter'];?></td>
                        <td><?php echo $data['salary_data']['NetSalary'];?></td>
                        <td><?php echo $data['salary_data']['SalayDate'] !=""?date("d-m-Y",strtotime($data['salary_data']['SalayDate'])):"";?></td>
                        <td><?php echo $data['salary_data']['ChequeNumber'];?></td>
                        <td><?php echo $data['salary_data']['ChequeDate'] !=""?date("d-m-Y",strtotime($data['salary_data']['ChequeDate'])):"";?></td>
                        <td><?php echo $data['salary_data']['PrintDate'] !=""?date("d-m-Y",strtotime($data['salary_data']['PrintDate'])):"";?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
           <?php
           die;
        }
    
    
    
}
?>