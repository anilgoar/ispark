 <?php
class SalaryVouchersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster','SalarData','DesignationNameMaster','UploadDeductionMaster','IncomtaxMaster','LoanMaster','UploadIncentiveBreakup','OldAttendanceIssue');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export_report','getdownloadstatus');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            //$this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchNameAll',$BranchArray);
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
    }
    
    public function getdownloadstatus(){
        if(isset($_REQUEST['CompanyName']) && $_REQUEST['CompanyName'] !=""){
            $CompanyName    =   $_REQUEST['CompanyName'];
            $BranchName     =   $_REQUEST['BranchName'];
            
            $m              =   $_REQUEST['EmpMonth'];
            $y              =   $_REQUEST['EmpYear'];
            $mwd            =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            
            $SalDate        =   $y."-".$m."-".$mwd;
            
           
            
            $DataArr    =   $this->SalarData->query("
            SELECT DownloadVoucher
            FROM salary_data WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate' GROUP BY DownloadVoucher
            ");

            if($DataArr[0]['salary_data']['DownloadVoucher'] !=""){
                echo "Sorry you have already download this month salary voucher !";die;
            }
            else{
                echo "Congratulations to download this month salary voucher.";die;
            }
            
            
        }
    }
    
    public function export_report(){
        
        if(isset($_REQUEST['CompanyName']) && $_REQUEST['CompanyName'] !=""){
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=import.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            
            $CompanyName    =   $_REQUEST['CompanyName'];
            $BranchName     =   $_REQUEST['BranchName'];
            
            
            
            $m              =   $_REQUEST['EmpMonth'];
            $y              =   $_REQUEST['EmpYear'];
            $mwd            =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            
            $TDSSalYear     =   "$y-".date("y",strtotime("+1 year"));
            
            $SalDate        =   $y."-".$m."-".$mwd;
            $date           =   date('d-M-y',strtotime($SalDate));
            $Month          =   date('M',strtotime($SalDate));
            $FYM            =   date('ym',strtotime($SalDate));
            
            $this->SalarData->query("UPDATE  salary_data SET DownloadVoucher='YES' WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate'");
            
            $CountArr    =   $this->SalarData->query("
            SELECT COUNT(Id) as TotalCount
            FROM salary_data WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate';
            ");
            
            $Count      =   $CountArr[0][0]['TotalCount'];
            
            if($Count > 0){

            $dataArr    =   $this->SalarData->query("
            SELECT 
            SUM(Gross1)+SUM(Incentive)+SUM(Arrear)+SUM(PLI)+SUM(ExtraDayIncentive) AS GrossSalary,
            SUM(ESICCompany) AS EmployerContributionEsic,
            SUM(EPFCompany) AS EmployerContributionEpf,
            SUM(AdminChrg) AS EpfAdminChrg,
            SUM(NetSalary) AS SalaryPayableAC,
            SUM(ESIC)+SUM(ESICCompany) AS EsicPayable,
            SUM(EPF)+SUM(EPFCompany)+SUM(AdminChrg) AS EpfPayable,
            SUM(SHSH) AS SHSH,
            SUM(ShortCollection) AS ShortCollection,
            SUM(MobileDedcution) AS MobileDedcution,
            SUM(AssetRecovery) AS AssetRecovery,
            SUM(OtherDeduction) AS OtherDeduction,
            SUM(ProTaxDeduction) AS ProTaxDeduction,
            SUM(Insurance) AS Insurance,
            SUM(LeaveDeduction) AS LeaveDeduction,
            SUM(IncomeTax) AS IncomeTax

            FROM salary_data WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate';
            ");
            
            $LoanAdvance    =   $this->SalarData->query("
            SELECT AdvPaid,LoanDed,EmpCode,EmpName FROM salary_data 
            WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate'AND (AdvPaid !='0' OR LoanDed !='0')
            ");
            
            $VchNoArray     =   $this->SalarData->query("
            SELECT VchNo FROM salary_data 
            WHERE SUBSTRING(EmpCode, 1, 3)='$CompanyName' AND RIGHT(EmpCode,1)!='C' AND Branch='$BranchName' AND DATE(SalDate)='$SalDate' GROUP BY VchNo
            ");
            
            $TallyCode  =array(
                'AHMEDABAD HOUSE'=>'AHM',
                'AHMEDABAD OTHERS'=>'AHM',
                'DELHI'=>'DEL',
                'HEAD OFFICE'=>'HO',
                'HYDERABAD'=>'HYD',
                'JAIPUR'=>'JPR',
                'JAIPUR IDC'=>'JPR',
                'KARNAL'=>'KNL',
                'MAYAPURI'=>'MYPR',
                'MAYAPURI-MP'=>'MYPR',
                'MEERUT'=>'MRT',
                'MOHALI'=>'MOH',
                'NOIDA'=>'NOIDA',
                'NOIDA-DIALDESK'=>'NOIDA-DD',
                'NOIDA-ISPARK'=>'NOIDA-ISP',
                'VDF MANPOWER'=>'VDF',
            );
            
            $SalaryAdvanceCode  =array(
                'AHMEDABAD HOUSE'=>'AHM',
                'AHMEDABAD OTHERS'=>'AHM',
                'DELHI'=>'DELHI',
                'HEAD OFFICE'=>'HEAD OFFICE',
                'HYDERABAD'=>'HYD',
                'JAIPUR'=>'JAIPUR',
                'JAIPUR IDC'=>'JAIPUR',
                'KARNAL'=>'KARNAL',
                'MAYAPURI'=>'MAYAPURI',
                'MAYAPURI-MP'=>'MAYAPURI',
                'MEERUT'=>'MAYAPURI',
                'MOHALI'=>'MOHALI',
                'Noida'=>'NOIDA',
                'NOIDA-DIALDESK'=>'NOIDA-DIALDESK',
                'NOIDA-ISPARK'=>'NOIDA-ISPARK',
                'VDF MANPOWER'=>'VDF MANPOWER',
                
            );

            $data=$dataArr[0][0];
            
            $GrossSalary                    =   $data['GrossSalary'];
            $EmployerContributionEsic       =   $data['EmployerContributionEsic'];
            $EmployerContributionEpf        =   $data['EmployerContributionEpf'];
            $EpfAdminChrg                   =   $data['EpfAdminChrg'];
            $SalaryPayableAC                =   $data['SalaryPayableAC'];
            $EsicPayable                    =   $data['EsicPayable'];
            $EpfPayable                     =   $data['EpfPayable'];
            $SHSH                           =   $data['SHSH'];
            $ShortCollection                =   $data['ShortCollection'];
            $MobileDedcution                =   $data['MobileDedcution'];
            $AssetRecovery                  =   $data['AssetRecovery'];
            $OtherDeduction                 =   $data['OtherDeduction'];
            $ProTaxDeduction                =   $data['ProTaxDeduction'];
            $Insurance                      =   $data['Insurance'];
            $LeaveDeduction                 =   $data['LeaveDeduction'];
            $IncomeTax                      =   $data['IncomeTax'];
            $GrossAmounts                   =   ($MobileDedcution+$AssetRecovery+$OtherDeduction+$Insurance+$LeaveDeduction);
            $Debit                          =   "D";
            $Credit                         =   "C";
            $CostCenter                     =   $TallyCode[$BranchName]."/".$FYM;
            $NarrationEntry                 =   "Salary $Month Month";
            $Narration                      =   $NarrationEntry." Vch No:$VchNo";
            
            $VchType                        =   "JRNLSAL";
            $SalaryAdvance                  =   "Advance Against Salary (".$SalaryAdvanceCode[$BranchName].")";
            
            $BranchArray=   array('MEERUT','HYDERABAD','AHMEDABAD HOUSE','MOHALI','JAIPUR');
            
            $SCArray    =   array(
                'MEERUT'=>'VODAFONE SUPER CC DELHI',
                'HYDERABAD'=>'VODAFONE SALES (P2P) HYD',
                'AHMEDABAD HOUSE'=>'VODAFONE SALES (P2P) AHM',
                'MOHALI'=>'VODAFONE SALES (P2P) MOHALI',
                'JAIPUR'=>'VODAFONE SALES (P2P) JPR',
            );
            
            if($BranchName =="AHMEDABAD HOUSE"){
                $BranchName="AHMEDABAD";
            }
            
            $VchNo                          =   $BranchName."/".$VchNoArray[0]['salary_data']['VchNo'];
            ?> 
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>Vch No</th>
                        <th>Date</th>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>DebitCredit</th>
                        <th>Cost Category</th>
                        <th>Cost Centre</th>
                        <th>Narration for Each Entry</th>
                        <th>Narration</th>
                        <th>VchType</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>Gross Salary</td>
                        <td><?php echo $GrossSalary;?></td>
                        <td><?php echo $Debit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>Employer's Contribution to Esic</td>
                        <td><?php echo $EmployerContributionEsic;?></td>
                        <td><?php echo $Debit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>Employer's Contribution to Epf</td>
                        <td><?php echo $EmployerContributionEpf;?></td>
                        <td><?php echo $Debit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>EPF Admin Charges</td>
                        <td><?php echo $EpfAdminChrg;?></td>
                        <td><?php echo $Debit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>Salary Payable A/C</td>
                        <td><?php echo $SalaryPayableAC;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>ESIC Payable</td>
                        <td><?php echo $EsicPayable;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>EPF Payable</td>
                        <td><?php echo $EpfPayable;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    
                    <?php 
                    if(!empty($LoanAdvance)){
                    foreach($LoanAdvance as $val){
                        $AdvEmpCode     =   $val['salary_data']['EmpCode'];
                        $exp            =   explode(" ", $val['salary_data']['EmpName']); 
                        $AdvEmpName     =   $exp[0];
                        $AdvPaid        =   $val['salary_data']['AdvPaid'];
                        $LoanDed        =   $val['salary_data']['LoanDed'];
                        $SalAdv         =   round($AdvPaid+$LoanDed);
                    ?>
                    <tr>
                        <!--
                        <td><?php //echo $AdvEmpCode."/".$AdvEmpName."/".$VchNo;?></td>
                        -->
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td><?php echo $SalaryAdvance;?></td>
                        <td><?php echo $SalAdv;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <?php }} ?>
                    
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>STAY HEALTHY STAY HAPPY INSURANCE</td>
                        <td><?php echo $SHSH;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    
                    <?php if(in_array($BranchName, $BranchArray)){?>
                    
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td><?php echo $SCArray[$BranchName]?></td>
                        <td><?php echo $ShortCollection;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                
                    <?php  } ?>
                    
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>GROSS SALARY</td>
                        <td><?php echo $GrossAmounts;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>Professional Tax <?php echo $TDSSalYear;?></td>
                        <td><?php echo $ProTaxDeduction;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                    <tr>
                        <td><?php echo $VchNo;?></td>
                        <td><?php echo $date;?></td>
                        <td>TDS SALARY <?php echo $TDSSalYear;?></td>
                        <td><?php echo $IncomeTax;?></td>
                        <td><?php echo $Credit;?></td>
                        <td><?php echo $BranchName;?></td>
                        <td><?php echo $CostCenter;?></td>
                        <td><?php echo $NarrationEntry;?></td>
                        <td><?php echo $Narration;?></td>
                        <td><?php echo $VchType;?></td>
                    </tr>
                </tbody>
            </table>
           <?php
          
        }
        die;  
        }
    }
    
}
?>