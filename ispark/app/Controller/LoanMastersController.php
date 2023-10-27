<?php
class LoanMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OldAttendanceIssue','LoanMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
                'index','get_attend_status','test','check_date','get_emp','get_empcode','get_month','approvebm','approveho',
                'export_report','show_report','loandetails','get_loan_details','printdetails','insertchequedetails',
                'convertNumberToWordsForIndia','moneyFormatIndia','chequeverification','rtgsdetails'
        );
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        $branchName = $this->Session->read('branch_name');
        $this->set('fieldArr',$this->LoanMaster->find('all',array('conditions'=>array('BranchName'=>$branchName))));
        $this->set('ClosedArr',$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst !='=>NULL)))); 
        

        if($this->request->is('Post')){
            $data   =   $this->request->data;
            $GarArr = $this->Masjclrentry->find('first',array('fields'=>array("EmpName","CostCenter"),'conditions'=>array('Status'=>1,'BranchName'=>$branchName,'EmpCode'=>$data['GuarantorEmpCode'])));
            
            $fdate=date('Y-m-d',  strtotime($data['StartDate']));
            $tdate=date('Y-m-d',  strtotime($data['EndDate']));
            
            
            $dataArr=array(
                'Type'=>$data['Type'],
                'EmpCode'=>$data['EmpCode'],
                'BranchName'=>$branchName,
                'CostCenter'=>$GarArr['Masjclrentry']['CostCenter'],
                'EmpName'=>$data['EmpName'],
                'Amount'=>$data['Amount'],
                'StartDate'=>$fdate,
                'EndDate'=>$tdate,
                'Installments'=>$data['Installments'],
                'DeductionPerMonth'=>$data['DeductionPerMonth'],
                'PendingAmount'=>$data['Amount'],
                'GuarantorName'=>$GarArr['Masjclrentry']['EmpName'],
                'GuarantorEmpCode'=>$data['GuarantorEmpCode'],
                'Reason'=>$data['Reason'],
            );
            
            $cntArr=$this->LoanMaster->query("SELECT * FROM LoanMaster WHERE Id = (SELECT MAX(Id) FROM LoanMaster WHERE Type='{$data['Type']}' AND BranchName='$branchName' AND EmpCode='{$data['EmpCode']}')");
            $exist=$cntArr[0]['LoanMaster'];
            
            if($exist['ApproveFirst'] =="Yes" && strtotime($exist['EndDate']) >= strtotime($data['StartDate'])){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This employee loan request already exist in database for this month.</span>'); 
                $this->redirect(array('action'=>'index'));  
            }
            else if($exist['ApproveSecond'] =="Yes" && strtotime($exist['EndDate']) >= strtotime($data['StartDate'])){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This employee loan request already exist in database for this month.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }
            else{
                if($this->LoanMaster->save($dataArr)){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your loan request save scccessfully.</span>');      
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your loan request failed please try again later</span>');   
                }
                $this->redirect(array('action'=>'index'));  
            }   
        }     
    }
    
    public function approvebm(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $this->set('data',$this->LoanMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL,'ApproveSecond'=>NULL),'order'=>'EmpName')));
        
        if($this->request->is('Post')){
            
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];

                if($SubmitType !=""){

                    if($SubmitType =="Approve"){
                        $status="Yes";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $this->LoanMaster->updateAll(array('ApproveFirst'=>"'".$status."'",'ApproveFirstDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branchName));   
                        }
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your request save successfully.</span>'); 
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve or not approve record.</span>'); 
                    }
                }
                $this->redirect(array('action'=>'approvebm'));   
            }  
        }
    }
    
    public function approveho(){
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
            $branch_name=$this->request->data['LoanMasters']['branch_name'];
            
            $conditoin=array('ApproveFirst'=>'Yes','ApproveSecond'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
                
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];
                if($SubmitType !=""){
                    if($SubmitType =="Approve"){
                        $status="Yes";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $this->LoanMaster->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                        }
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your request save successfully.</span>'); 
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve/not approve loan.</span>'); 
                    }
                }
            }
                    
            $this->set('OdArr',$this->LoanMaster->find('all',array('conditions'=>$conditoin)));  
        }     
    }
    
    
    public function loandetails(){
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
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['StartDate'] !=""){$conditoin['StartDate BETWEEN ? and ?']=array(date('Y-m-d',strtotime('01-'.$_REQUEST['StartDate'])),date('Y-m-t',strtotime('01-'.$_REQUEST['EndDate'])));}else{unset($conditoin['StartDate']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            if($_REQUEST['Status'] =="Applied"){$conditoin['ApproveFirst']=NULL;}
            else if($_REQUEST['Status'] =="Approve BM"){$conditoin['ApproveFirst']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve BM"){$conditoin['ApproveFirst']='No';}
            else if($_REQUEST['Status'] =="Approve HO"){$conditoin['ApproveSecond']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve HO"){$conditoin['ApproveSecond']='No';}
            else{unset($conditoin['ApproveFirst']);unset($conditoin['ApproveSecond']);} 
            
            //print_r($conditoin); exit; 
            
            $data   =   $this->LoanMaster->find('all',array('conditions'=>$conditoin));
            
            
            
            if(!empty($data)){
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:300px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>EMI</th>
                            <th>LoanFrom</th>
                            <th>Loan To</th>
                            <th>Reason</th>
                            <!--
                            <th>Guarantor</th>
                            -->
                            <th>Print</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $n=1; foreach ($data as $val){?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['LoanMaster']['EmpCode'];?></td>
                            <td><?php echo $val['LoanMaster']['EmpName'];?></td>
                            <td><?php echo $val['LoanMaster']['BranchName'];?></td>
                            <td><?php echo $val['LoanMaster']['Amount'];?></td>
                            <td><?php echo $val['LoanMaster']['Type'];?></td>
                            <td><?php echo $val['LoanMaster']['Installments'];?></td>
                            <td><?php echo date('M-Y',strtotime($val['LoanMaster']['StartDate']));?></td>
                            <td><?php echo date('M-Y',strtotime($val['LoanMaster']['EndDate']));?></td>
                            <td><?php echo $val['LoanMaster']['Reason'];?></td>
                            <!--
                            <td><?php echo $val['LoanMaster']['GuarantorName'];?></td>
                            -->
                            <td>
                                <?php if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] =="Yes" && $val['LoanMaster']['TransationStatus'] !="YES"){?>
                                <span class="icon">
                                    <i onclick="printed('<?php echo $val['LoanMaster']['Id'];?>');" class="material-icons" style="font-size:20px;margin-left: -88px;cursor: pointer;">mode_print</i>
                                </span>
                                <?php }?>
                            </td>
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
    
    public function printdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $data     =   $this->LoanMaster->find('first',array('conditions'=>array('Id'=>$_REQUEST['Id'])));
            
            if(!empty($data)){
                $Branch         =   $data['LoanMaster']['BranchName'];
                $CostCenter     =   $data['LoanMaster']['CostCenter'];
                $EmpMonth       =   date("m",strtotime($data['LoanMaster']['StartDate']));
                $EmpYear        =   date("Y",strtotime($data['LoanMaster']['StartDate']));
                
            ?>
            <div class="col-sm-12">
                <div class="box-header"  >
                    <div class="box-name">
                        <span>PRINT DETAILS</span>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group" style="margin-top:0px;" >
                        <div class="col-sm-7">
                            <div class="form-group" style="border:2px solid #436e90;padding:5px;background-color: #c0d6e4;line-height:25px;">
                                
                                <div class="col-sm-2" style="font-weight:bold;">Branch</div>
                                <div class="col-sm-4">
                                    <input type="text" name="PrintBranchName" id="PrintBranchName" value="<?php echo $Branch;?>" readonly="" style="height:18px;" > 
                                </div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">L/A Month</div>
                                <div class="col-sm-4">
                                    <select name="PrintSalaryMonth" id="PrintSalaryMonth"  readonly style="width:100px;" >
                                        <option value="<?php echo date('Y-m',strtotime("$EmpYear-$EmpMonth"));?>"><?php echo date('M-Y',strtotime("$EmpYear-$EmpMonth"));?></option>
                                    </select> 
                                </div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">CostCenter</div>
                                <div class="col-sm-4">
                                    <input type="text" name="PrintCostCenter" id="PrintCostCenter" value="<?php echo $CostCenter;?>" style="height:18px;" readonly="" > 
                                </div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">ChequeDate</div>
                                <div class="col-sm-4">
                                    <select name="PrintChequeDate" id="PrintChequeDate"  readonly style="width:100px;" >
                                        <option value="<?php echo date('d M Y');?>"><?php echo date('d-M-Y');?></option>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">PayType</div>
                                <div class="col-sm-4">
                                    <select name="PrintPayType" id="PrintPayType" style="width:134px;">
                                        <option value="">Select</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="RTGS">RTGS</option>
                                    </select>  
                                </div>

                                <div class="col-sm-12" style="border:1px solid #FFF;" >
                                    <div class="form-group">
                                        <div class="col-sm-2"><input type="radio" checked name="PrintBankName" value="SBI"  > SBI </div>
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName"  value="SBIIDC" > SBI IDC</div>
                                        <div class="col-sm-2">&nbsp;</div>
                                        <div class="col-sm-2">ChequeNo</div>
                                        <div class="col-sm-4"> <input type="text" name="PrintCheckFrom" onkeypress="return isNumberKey(event,this)" maxlength="6"  id="PrintCheckFrom" style="height:16px;width:100px;" ></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName" value="ICICI" > ICICI </div>
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName" value="HDFC" > HDFC</div> 
                                        <div class="col-sm-2">&nbsp;</div>
                                        <div class="col-sm-2">RTGSNo</div>
                                        <div class="col-sm-4"> <input type="text" name="PrintCheckTo" id="PrintCheckTo" style="height:16px;width:100px;" ></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-6"><input type="checkbox" value="YES" name="PrintAccountPayee" id="PrintAccountPayee" checked > Account Payee Cheque </div>
                                        <div class="col-sm-6"><input type="checkbox" value="YES" name="PrintAccountNumber" id="PrintAccountNumber" checked > Print Account Number</div>      
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="button" onclick="chequeVerification('verify','<?php echo $data['LoanMaster']['Id'];?>')" id="back" class="btn btn-primary btn-new" style="width:50px;">Verify</button>
                                            <button type="button" onclick="printCheque('<?php echo $data['LoanMaster']['Id'];?>')" class="btn btn-primary btn-new" style="width:50px;">Print</button>
                                            <button type="button" onclick="chequeVerification('delete','<?php echo $data['LoanMaster']['Id'];?>')" id="back" class="btn btn-primary btn-new" style="width:50px;">Delete</button>
                                            <button type="button" onclick="loanReport('show')" id="back" class="btn btn-primary btn-new" style="width:50px;">Back</button>
                                        </div>
                                    </div>
                                </div>
                                    
                                    
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
                            
            <?php   
            }
            else{
                echo "";
            }
            die;
        }  
    }
    
    public function insertchequedetails(){
        if(isset($_REQUEST['Id'])){
            $PrintBy          =   $this->Session->read('username');
            $Id               =   $_REQUEST['Id'];
            $ChequeNumber     =   $_REQUEST['PrintCheckFrom'];
            $RTGSNumber       =   $_REQUEST['PrintCheckTo'];
            $ChequeDate       =   $_REQUEST['CheckDate'];
            $PrintDate        =   date('Y-m-d');
            $BankName         =   $_REQUEST['BankName'];
            
            if($_REQUEST['PrintAccountPayee'] =="YES"){
                $AccountPayCheque   =   addslashes("A/C PAYEE ONLY");
            }
            else{
                $AccountPayCheque      =   "";
            }
            
            $dataArr  =   $this->LoanMaster->find('first',array('conditions'=>array('Id'=>$Id)));
            $data     =   $dataArr['LoanMaster']; 
            
            $EmpCode        =   $data['EmpCode'];
            $EmpName        =   $data['EmpName'];
            $BranchName     =   $data['BranchName'];
            $CostCenter     =   $data['CostCenter'];
            $Amount         =   round($data['Amount']);
            $SalaryMonth    =   $PrintDate;
            
            $EmpArr         =   $this->Masjclrentry->find('first',array('fields'=>array('AcNo'),'conditions'=>array('EmpCode'=>$EmpCode)));
            $AcNo           =   $EmpArr['Masjclrentry']['AcNo'];
                
            if($AcNo !=""){
                if($_REQUEST['PrintAccountNumber'] =="YES"){
                    $PrintAccountNumber      =   addslashes("A/c No - ".$AcNo);
                }
                else{
                    $PrintAccountNumber      =   "";
                }
            }
            else{
                $PrintAccountNumber      =   "";
            }

            $AmountInRupees =   addslashes($this->moneyFormatIndia($Amount));
            $AmountInWord   =   addslashes($this->convertNumberToWordsForIndia($Amount));
       
            $list_value="('".$EmpCode."','".$EmpName."','".$BranchName."','".$CostCenter."','".$Amount."','".$SalaryMonth."','".$ChequeDate."','".$PrintDate."','".$BankName."','".$ChequeNumber."','".$AccountPayCheque."','".$PrintAccountNumber."','".$AcNo."','".$AmountInRupees."','".$AmountInWord."')";
           
            $this->LoanMaster->query("TRUNCATE TABLE LoanPrintChequeMaster");
            $this->LoanMaster->query("INSERT INTO LoanPrintChequeMaster(`EmpCode`,`EmpName`,`BranchName`,`CostCenter`,`Amount`,`SalaryMonth`,
            `ChequeDate`,`PrintDate`,`BankName`,`ChequeNumber`,`AccountPayCheque`,`PrintAccountNumber`,`AcNo`,`AmountInRupees`,`AmountInWord`) values $list_value"); 
            $this->LoanMaster->query("UPDATE `LoanMaster` SET RTGSNumber=NULL,RTGSDate=NULL,ChequeNumber='$ChequeNumber',ChequeBankName='$BankName',ChequeDate='$PrintDate',printby='$PrintBy',PrintDate='$PrintDate' WHERE Id='$Id'");
            die;
        } 
    }
    
    public function rtgsdetails(){
        if(isset($_REQUEST['Id'])){
            $PrintBy          =   $this->Session->read('username');
            $Id               =   $_REQUEST['Id'];
            $ChequeNumber     =   $_REQUEST['PrintCheckFrom'];
            $RTGSNumber       =   $_REQUEST['PrintCheckTo'];
            $ChequeDate       =   $_REQUEST['CheckDate'];
            $PrintDate        =   date('Y-m-d');
            $BankName         =   $_REQUEST['BankName'];
  
            $this->LoanMaster->query("UPDATE `LoanMaster` SET ChequeNumber=NULL,ChequeDate=NULL,RTGSNumber='$RTGSNumber',ChequeBankName='$BankName',RTGSDate='$PrintDate',printby='$PrintBy',PrintDate='$PrintDate' WHERE Id='$Id'");
            die;
        } 
    }
    
    public function chequeverification(){
        $Id     =   $_REQUEST['Id'];
        if($_REQUEST['type'] =="verify"){
            $this->LoanMaster->query("UPDATE `LoanMaster` SET TransationStatus='YES' WHERE Id='$Id'");
            echo "Your selected data verify successfully.";die;
        }
        else if($_REQUEST['type'] =="delete"){
            $this->LoanMaster->query("UPDATE `LoanMaster` SET ChequeNumber=NULL,ChequeBankName=NULL,ChequeDate=NULL,printby=NULL,PrintDate=NULL,RTGSNumber=NULL,RTGSDate=NULL WHERE Id='$Id'");
            echo "Your selected data delete successfully.";die;
        }
    }
    
    
    public function export_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=LoanDetails.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['StartDate'] !=""){$conditoin['StartDate BETWEEN ? and ?']=array(date('Y-m-d',strtotime($_REQUEST['StartDate'])),date('Y-m-d',strtotime($_REQUEST['EndDate'])));}else{unset($conditoin['StartDate']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            if($_REQUEST['Status'] =="Applied"){$conditoin['ApproveFirst']=NULL;}
            else if($_REQUEST['Status'] =="Approve BM"){$conditoin['ApproveFirst']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve BM"){$conditoin['ApproveFirst']='No';}
            else if($_REQUEST['Status'] =="Approve HO"){$conditoin['ApproveSecond']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve HO"){$conditoin['ApproveSecond']='No';}
            else{unset($conditoin['ApproveFirst']);unset($conditoin['ApproveSecond']);}
            
            $data   =   $this->LoanMaster->find('all',array('conditions'=>$conditoin));
            
            ?>
            <table border="1"  >    
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>Branch</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Installments</th>
                        <th>LoanFrom</th>
                        <th>Loan To</th>
                        <th>Reason</th>
                        <th>Guarantor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $n=1; foreach ($data as $val){?>
                    <tr>
                        <td><?php echo $n++;?></td>
                        <td><?php echo $val['LoanMaster']['EmpCode'];?></td>
                        <td><?php echo $val['LoanMaster']['EmpName'];?></td>
                        <td><?php echo $val['LoanMaster']['BranchName'];?></td>
                        <td><?php echo $val['LoanMaster']['Amount'];?></td>
                        <td><?php echo $val['LoanMaster']['Type'];?></td>
                        <td><?php echo $val['LoanMaster']['Installments'];?></td>
                        <td><?php echo date('M-Y',strtotime($val['LoanMaster']['StartDate']));?></td>
                        <td><?php echo date('M-Y',strtotime($val['LoanMaster']['EndDate']));?></td>
                        <td><?php echo $val['LoanMaster']['Reason'];?></td>
                        <td><?php echo $val['LoanMaster']['GuarantorName'];?></td>
                        <td style="text-align:center;">
                                <?php 
                                if($val['LoanMaster']['ApproveFirst'] =="" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM PENDING";
                                }
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM APPROVE";
                                }
                                else if($val['LoanMaster']['ApproveFirst'] =="No" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM DISAPPROVE";
                                } 
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] =="Yes"){
                                    echo "HO APPROVE";
                                } 
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] =="No"){
                                    echo "HO DISAPPROVE";
                                } 
                              
                                ?>
                            </td>
                    </tr>
                    <?php }?>
                </tbody>   
            </table>
            <?php   
            die;
        }
        
    }
    
    public function get_month(){
        $StartDate      =   date("Y-m",strtotime($_REQUEST['StartDate']));
        $installments   =   trim($_REQUEST['installments']);
        
        echo  $newdt= date('M-Y', strtotime($_REQUEST['StartDate'].' +'.($installments-1).' month'));die;
    }
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        if($ToDate >=$FromDate){
            echo '1';die;
        }
        else{
            echo '';die;
        }
    }
    
    public function get_emp(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpName']) && trim($_REQUEST['EmpName']) !=""){ 
            $branchName = $this->Session->read('branch_name');
            $data = $this->Masjclrentry->find('all',array(
                'fields'=>array("EmpCode","EmpName"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$branchName,
                    'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                    )
                ));
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach ($data as $val){
                    $value=$val['Masjclrentry']['EmpCode'];
                    $label=$val['Masjclrentry']['EmpCode']." - ".$val['Masjclrentry']['EmpName'];
                    echo "<option value='$value'>$label</option>";
                }
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    public function get_empcode(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $branchName = $this->Session->read('branch_name');
            $EmpCode    = trim($_REQUEST['EmpCode']);
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("EmpName"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$branchName,
                    'EmpCode'=>$EmpCode,
                    )
                ));
            
            if(!empty($data)){
                echo $data['Masjclrentry']['EmpName'];
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    public function get_loan_details(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $branchName = $this->Session->read('branch_name');
            $EmpCode    = trim($_REQUEST['EmpCode']);
            $data = $this->LoanMaster->find('all',array(
                'conditions'=>array(
                    'BranchName'=>$branchName,
                    'EmpCode'=>$EmpCode,
                    )
                ));
            
            if(!empty($data)){
                
            ?>
            <div class="col-sm-12">
            <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable"> 
                    <thead>
                        <tr>   
                            <th style="text-align:center;width:30px;">SNo</th>                                
                            <th style="text-align:center;width:80px;">EmpCode</th> 
                            <th >EmpName</th>        
                            <th style="text-align:center;width:80px;">Amount</th>        
                            <th style="text-align:center;width:80px;">Type</th>        
                            <th style="text-align:center;width:80px;">Installments</th>        
                            <th style="text-align:center;width:80px;">From</th>        
                            <th style="text-align:center;width:80px;">To</th> 
                            <th >Reason</th>    
                            <th style="text-align:center;width:100px;">Status</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($data as $val){?>
                        <tr>
                            <td style="text-align:center;" ><?php echo $i++;?></td>
                            <td style="text-align:center;"><?php echo $val['LoanMaster']['EmpCode'];?></td>
                            <td ><?php echo $val['LoanMaster']['EmpName'];?></td>
                            <td style="text-align:center;"><?php echo $val['LoanMaster']['Amount'];?></td>
                            <td style="text-align:center;"><?php echo $val['LoanMaster']['Type'];?></td>
                            <td style="text-align:center;"><?php echo $val['LoanMaster']['Installments'];?></td>
                            <td style="text-align:center;"><?php echo date('M-Y',strtotime($val['LoanMaster']['StartDate']));?></td>
                            <td style="text-align:center;"><?php echo date('M-Y',strtotime($val['LoanMaster']['EndDate']));?></td>
                            <td ><?php echo $val['LoanMaster']['Reason'];?></td>
                            <td style="text-align:center;">
                                <?php 
                                if($val['LoanMaster']['ApproveFirst'] =="" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM PENDING";
                                }
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM APPROVE";
                                }
                                else if($val['LoanMaster']['ApproveFirst'] =="No" && $val['LoanMaster']['ApproveSecond'] ==""){
                                    echo "BM DISAPPROVE";
                                } 
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] =="Yes"){
                                    echo "HO APPROVE";
                                } 
                                else if($val['LoanMaster']['ApproveFirst'] =="Yes" && $val['LoanMaster']['ApproveSecond'] =="No"){
                                    echo "HO DISAPPROVE";
                                } 
                              
                                ?>
                            </td>
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
        }
        die;  
    }
    
    public function convertNumberToWordsForIndia($strnum){
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');
		
		//echo $strnum = "2070000"; 
		 $len = strlen($strnum);
		 $numword = "";
		while($len!=0)
		{
			if($len>=8 && $len<= 9)
			{
				$val = "";
				
				
				if($len == 9)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 7;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 8)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =7;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Crore ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Crores ";
				}
				
			}
			if($len>=6 && $len<= 7)
			{
				$val = "";
				
				
				if($len == 7)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 5;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 6)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =5;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Lakh ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Lakhs ";
				}
				
			}
		
			if($len>=4 && $len<= 5)
			{
				$val = "";
				if($len == 5)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 3;
					$strnum =   substr($strnum,2,4);
				}
				if($len== 4)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =3;
					$strnum =   substr($strnum,1,3);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Thousand ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Thousand ";
				}
			}
			if($len==3)
			{
				$val = "";
				$value = substr($strnum,0,1);

				$val  = $value;
				$numword.= $words["$value"]." ";
				$len = 2;
				$strnum =   substr($strnum,1,2);

				if($val == 1)
				{
					$numword.=  "Hundred ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Hundred ";
				}
			}
			if($len>=1 && $len<= 2)
			{
				if($len ==2)
				{
				$value = substr($strnum,0,1);
				$value = $value *10;
				$value1 = $value;
				$strnum =   substr($strnum,1,1);
				$value2 = substr($strnum,0,1);
				$value =$value1 + $value2;				
				}
				if($len ==1)
				{	
					$value = substr($strnum,0,1);
					
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
					$len =0;
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
					$len =0;
				}
				$numword.=  "Only ";

			}
			
			break;
		}
		return ucwords(strtolower($numword));

    }

    public function moneyFormatIndia($num){
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash.".00"; // writes the final format where $currency is the currency symbol.
    }
    

      
}
?>