<?php
class LoanAdvanceReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','LoanMaster');
        
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
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }    
    }
    
    public function show_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){

            $FM=$_REQUEST['EmpMonthF'];
            $FY=$_REQUEST['EmpYearF'];
            $LM=$_REQUEST['EmpMonthTo'];
            $LY=$_REQUEST['EmpYearTo'];

            $conditoin=array(
                'Type'=>$_REQUEST['EmpLocation'],
                'TransationStatus'=>'YES',
                'MONTH(StartDate) >='=>$FM,'YEAR(StartDate)'=>$FY,
                'MONTH(StartDate) <='=>$LM,'YEAR(StartDate)'=>$LY,
            );
            
           
            
 
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            $data     =   $this->LoanMaster->find('all',array('conditions'=>$conditoin)); 

            if(!empty($data)){   
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style='text-align:center;' >SNo</th>
                            <th style='text-align:center;' >EmpCode</th>
                            <th style='text-align:center;width:200px;' >EmpName</th>
                            <th style='text-align:center;width:140px;' >Branch</th>
                            <th style='text-align:center;' >Type</th>
                            <th style='text-align:center;' >From</th>
                            <th style='text-align:center;' >To</th>
                            <th style='text-align:center;' >EMI</th>
                            <th style='text-align:center;' >Loan</th>
                            <th style='text-align:center;' >Deducted</th>
                            <th style='text-align:center;' >Pending</th>
                            <th style='text-align:center;' >ChequeNo</th>
                            
                            <th style='text-align:center;' >RTGSNo</th>
                           
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $n=1; foreach ($data as $val){?>
                        <tr>
                            <td style='text-align:center;' ><?php echo $n++;?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['EmpCode'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['EmpName'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['BranchName'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Type'];?></td>
                            <td style='text-align:center;' ><?php echo date('d-M-Y',strtotime($val['LoanMaster']['StartDate']));?></td>
                            <td style='text-align:center;' ><?php echo date('d-M-Y',strtotime($val['LoanMaster']['EndDate']));?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Installments'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Amount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['DeductedAmount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['PendingAmount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['ChequeNumber'];?></td>
                          
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['RTGSNumber'];?></td>
                           
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
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=LoanAdvanceReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $FM=$_REQUEST['EmpMonthF'];
            $FY=$_REQUEST['EmpYearF'];
            $LM=$_REQUEST['EmpMonthTo'];
            $LY=$_REQUEST['EmpYearTo'];

            $conditoin=array(
                'Type'=>$_REQUEST['EmpLocation'],
                'TransationStatus'=>'YES',
                'MONTH(StartDate) >='=>$FM,'YEAR(StartDate)'=>$FY,
                'MONTH(StartDate) <='=>$LM,'YEAR(StartDate)'=>$LY,
            );

            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            $data     =   $this->LoanMaster->find('all',array('conditions'=>$conditoin)); 

            if(!empty($data)){   
            ?>
           
                <table border="1"  >     
                    <thead>
                        <tr>
                            <th style='text-align:center;' >SNo</th>
                            <th style='text-align:center;' >EmpCode</th>
                            <th style='text-align:center;' >EmpName</th>
                            <th style='text-align:center;' >Branch</th>
                            <th style='text-align:center;' >LoanType</th>
                            <th style='text-align:center;' >LoanFrom</th>
                            <th style='text-align:center;' >LoanTo</th>
                            <th style='text-align:center;' >Installments</th>
                            <th style='text-align:center;' >LoanAmount</th>
                            <th style='text-align:center;' >DeductedAmount</th>
                            <th style='text-align:center;' >PendingAmount</th>
                            <th style='text-align:center;' >ChequeNumber</th>
                            <th style='text-align:center;' >ChequeBankName</th>
                            <th style='text-align:center;' >Chequedate</th>
                            <th style='text-align:center;' >RTGSNumber</th>
                            <th style='text-align:center;' >RTGSDate</th>
                            <th style='text-align:center;' >printby</th>
                            <th style='text-align:center;' >PrintDate</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $n=1; foreach ($data as $val){?>
                        <tr>
                            <td style='text-align:center;' ><?php echo $n++;?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['EmpCode'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['EmpName'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['BranchName'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Type'];?></td>
                            <td style='text-align:center;' ><?php echo date('d-M-Y',strtotime($val['LoanMaster']['StartDate']));?></td>
                            <td style='text-align:center;' ><?php echo date('d-M-Y',strtotime($val['LoanMaster']['EndDate']));?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Installments'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['Amount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['DeductedAmount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['PendingAmount'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['ChequeNumber'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['ChequeBankName'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['ChequeDate'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['RTGSNumber'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['RTGSDate'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['printby'];?></td>
                            <td style='text-align:center;' ><?php echo $val['LoanMaster']['PrintDate'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
           
            <?php   
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