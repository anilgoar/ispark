<?php
class ChequePrintsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','SalarData','ChequeMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','printdetails','getcostcenter','export_report','printcheque','insertchequedetails',
                           'chequeverified','chequeverification','chequenoexist','chequecancel');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }    
    }
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=CancelChequeExport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $m=$_REQUEST['EmpMonth'];
            $y=$_REQUEST['EmpYear'];
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
            if($_REQUEST['BranchName'] !="ALL"){
                $condition=array('BranchName'=>$_REQUEST['BranchName'],'date(SalaryMonth)'=>$SalayDay,'CancelReason !='=>NULL);
            }
            else{
                $condition=array('date(SalaryMonth)'=>$SalayDay,'CancelReason !='=>NULL);
            }
            
            $dataArr   =   $this->ChequeMaster->find('all',array('conditions'=>$condition));
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>BranchName</th>
                        <th>CostCenter</th>
                        <th>Amount</th>
                        <th>SalaryMonth</th>
                        <th>ChequeDate</th>
                        <th>PrintDate</th>
                        <th>BankName</th>
                        <th>ChequeNumber</th>
                        <th>AccountPayCheque</th>
                        <th>PrintAccountNumber</th>
                        <th>PrintStatus</th>
                        <th>AcNo</th>
                        <th>AmountInRupees</th>
                        <th>AmountInWord</th>
                        <th>CancelReason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ ?>
                    <tr>
                        <td><?php echo $data['ChequeMaster']['EmpCode']?></td>
                        <td><?php echo $data['ChequeMaster']['EmpName']?></td>
                        <td><?php echo $data['ChequeMaster']['BranchName']?></td>
                        <td><?php echo $data['ChequeMaster']['CostCenter']?></td>
                        <td><?php echo $data['ChequeMaster']['Amount']?></td>
                        <td><?php echo $data['ChequeMaster']['SalaryMonth']?></td>
                        <td><?php echo $data['ChequeMaster']['ChequeDate']?></td>
                        <td><?php echo $data['ChequeMaster']['PrintDate']?></td>
                        <td><?php echo $data['ChequeMaster']['BankName']?></td>
                        <td><?php echo $data['ChequeMaster']['ChequeNumber']?></td>
                        <td><?php echo $data['ChequeMaster']['AccountPayCheque']?></td>
                        <td><?php echo $data['ChequeMaster']['PrintAccountNumber']?></td>
                        <td><?php echo $data['ChequeMaster']['PrintStatus']?></td>
                        <td><?php echo $data['ChequeMaster']['AcNo']?></td>
                        <td><?php echo $data['ChequeMaster']['AmountInRupees']?></td>
                        <td><?php echo $data['ChequeMaster']['AmountInWord']?></td>
                        <td><?php echo $data['ChequeMaster']['CancelReason']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
           <?php
           die;
        }
    }
    
    public function chequecancel(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $data               =   $this->request->data;
            $PrintSalaryMonth   =   explode("-", $data['PrintSalaryMonth']) ;
            $Year               =   $PrintSalaryMonth[0];
            $Month              =   $PrintSalaryMonth[1];
            $PrintBankName      =   $data['PrintBankName'];
            $PrintCheckFrom     =   $data['PrintCheckFrom'];
            $PrintCheckTo       =   $data['PrintCheckTo'];
            $Reason             =   $data['Reason'];

            $dataArr            =   $this->ChequeMaster->find('all',array('conditions'=>"ChequeNumber between '$PrintCheckFrom' AND '$PrintCheckTo' AND BankName='$PrintBankName' AND PrintStatus='YES' AND MONTH(SalaryMonth)='$Month' AND  YEAR(SalaryMonth)='$Year' "));
            
            if(!empty($dataArr)){
                foreach($dataArr as $row){
                    $Id             =   $row['ChequeMaster']['Id'];
                    $EmpCode        =   $row['ChequeMaster']['EmpCode'];
                    $SalaryMonth    =   $row['ChequeMaster']['SalaryMonth'];
                    $ChequeNumber   =   $row['ChequeMaster']['ChequeNumber'];
                    $ChequeDate     =   $row['ChequeMaster']['ChequeDate'];
                    $PrintDate      =   $row['ChequeMaster']['PrintDate'];

                    $this->SalarData->query("UPDATE salary_data SET ChequeNumber=NULL,ChequeDate=NULL,
                    PrintDate=NULL,SalaryReceiveStatus=NULL WHERE EmpCode='$EmpCode' AND date(SalDate)='$SalaryMonth' AND SalaryReceiveStatus='YES' ");

                    $this->ChequeMaster->query("UPDATE ChequePrintMaster SET CancelReason='$Reason' WHERE Id='$Id'");   
                }


                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your given cheque no cancel successfully.</span>'); 
                $this->redirect(array('action'=>'chequecancel')); 
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This cheque no does not exist for this bank !</span>'); 
                $this->redirect(array('action'=>'chequecancel')); 
            }
        }
    }
    
    public function show_report(){
        $this->layout='ajax';
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['Branch']=$_REQUEST['BranchName'];}else{unset($conditoin['Branch']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['Status']=$_REQUEST['EmpLocation'];}else{unset($conditoin['Status']);}
            if($_REQUEST['EmpMonth'] !=""){$conditoin['MONTH(SalDate)']=$_REQUEST['EmpMonth'];}else{unset($conditoin['MONTH(SalDate)']);}
            if($_REQUEST['EmpYear'] !=""){$conditoin['YEAR(SalDate)']=$_REQUEST['EmpYear'];}else{unset($conditoin['YEAR(SalDate)']);}
            
            $data     =   $this->SalarData->find('all',array('conditions'=>$conditoin,'group'=>'CostCenter'));
            
            if(!empty($data)){
            $count=count($data);
            ?>
            <div class="col-sm-8" <?php if($count > 15){?> style="overflow-y:scroll;height:483px;" <?php }?> >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="width:150px;text-align: center;">Branch</th>
                            <th style="width:200px;text-align: center;">Cost Center</th>
                            <th style="text-align: center;">Total Count</th>
                            <th style="text-align: center;">Printed</th>
                            <th style="text-align: center;">Remaining</th>
                            <th style="width:50px;" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalempcount=0;
                        $totalprintedcount=0;
                        $totalremainingcount=0;
                        foreach($data as $row){
                            $Branch         =   $row['SalarData']['Branch'];
                            $CostCenter     =   $row['SalarData']['CostCenter'];
                            $SalMonth       =   $row['SalarData']['SalayDate'];
                            $empcount       =   $this->empcount($conditoin,$CostCenter);
                            $printedcount   =   $this->printedcount($conditoin,$CostCenter);
                            $remainingcount =   $this->remainingcount($conditoin,$CostCenter); 
                            
                            $totalempcount      =   $totalempcount+$empcount;
                            $totalprintedcount  =   $totalprintedcount+$printedcount;
                            $totalremainingcount=   $totalremainingcount+$remainingcount;   
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $Branch;?></td>
                            <td style="text-align: center;"><?php echo $CostCenter;?></td>
                            <td style="text-align: center;"><?php echo $empcount;?></td>
                            <td style="text-align: center;"><?php echo $printedcount;?></td>
                            <td style="text-align: center;"><?php echo $remainingcount;?></td>
                            <td>
                                <span class="icon"><i style="font-size:20px;margin-left: -88px;cursor: pointer;" class="material-icons" onclick="printed('<?php echo $Branch;?>','<?php echo $CostCenter;?>','<?php echo $_REQUEST['EmpLocation'];?>','<?php echo $_REQUEST['EmpCode'];?>','<?php echo $_REQUEST['EmpMonth'];?>','<?php echo $_REQUEST['EmpYear'];?>');">mode_print</i></span>
                            </td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;font-weight: bold;">Total</td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $totalempcount;?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $totalprintedcount;?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $totalremainingcount;?></td>
                            <td></td>
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
    
    public function printdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $conditoin=array('SalaryReceiveStatus'=>NULL);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['Branch']=$_REQUEST['BranchName'];}else{unset($conditoin['Branch']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['Status']=$_REQUEST['EmpLocation'];}else{unset($conditoin['Status']);}
            if($_REQUEST['EmpMonth'] !=""){$conditoin['MONTH(SalDate)']=$_REQUEST['EmpMonth'];}else{unset($conditoin['MONTH(SalDate)']);}
            if($_REQUEST['EmpYear'] !=""){$conditoin['YEAR(SalDate)']=$_REQUEST['EmpYear'];}else{unset($conditoin['YEAR(SalDate)']);}
            
            $count     =   $this->SalarData->find('count',array('conditions'=>$conditoin));
            if($count > 0){
                $data           =   $this->SalarData->find('all',array('conditions'=>$conditoin,'order'=>array('NetSalary'=>'desc')));
                $Branch         =   $_REQUEST['BranchName'];
                $CostCenter     =   $_REQUEST['CostCenter'];
                $Status         =   $_REQUEST['EmpLocation'];
                $EmpMonth       =   $_REQUEST['EmpMonth'];
                $EmpYear        =   $_REQUEST['EmpYear'];
                $printtotalcount=   $this->remainingcount($conditoin,$CostCenter);
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
                                
                                <div class="col-sm-2" style="font-weight:bold;">SalaryMonth</div>
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
                                        <option value="<?php echo date('05 M Y');?>"><?php echo date('05-M-Y');?></option>
                                    </select>
                                </div>
                                
                                <input type="hidden" name="HiddenTotalCount" id="HiddenTotalCount" value="<?php echo $printtotalcount;?>">
                                <input type="hidden" name="HiddenPrintCount" id="HiddenPrintCount" value="<?php echo $printtotalcount;?>">
                                
                                <div class="col-sm-2" style="font-weight:bold;">Count</div>
                                <div class="col-sm-4" id="PrintTotalCount" ><?php echo $printtotalcount;?></div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">Remaining</div>
                                <div class="col-sm-4" id="PrintTotalRemaining" >0</div>
                                
                                <div class="col-sm-2" style="font-weight:bold;">Status</div>
                                <div class="col-sm-4">
                                    <?php 
                                        if($Status =="ALL"){
                                            echo "ALL";
                                        }
                                        else if($Status =="0"){
                                            echo "Left";
                                        }
                                        else if($Status =="1"){
                                            echo "Active";
                                        }
                                    ?>
                                </div>
                    
                                <div class="col-sm-12" style="border:1px solid #FFF;" >
                                    <div class="form-group">
                                        <div class="col-sm-2"><input type="radio" checked name="PrintBankName" value="SBI"  > SBI </div>
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName"  value="SBIIDC" > SBI IDC</div>
                                        <div class="col-sm-2">&nbsp;</div>
                                        <div class="col-sm-2">ChequeFrom</div>
                                        <div class="col-sm-4"> <input type="text" name="PrintCheckFrom" onkeypress="return isNumberKey(event,this)" maxlength="6"  id="PrintCheckFrom" style="height:16px;width:100px;" ></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName" value="ICICI" > ICICI </div>
                                        <div class="col-sm-2"><input type="radio" name="PrintBankName" value="HDFC" > HDFC</div>
                                        <div class="col-sm-2">&nbsp;</div>
                                        <div class="col-sm-2">ChequeTo</div>
                                        <div class="col-sm-2"> <input type="text" name="PrintCheckTo" onkeypress="return isNumberKey(event,this)" maxlength="6" id="PrintCheckTo" style="height:16px;width:100px;" ></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-6"><input type="checkbox" value="YES" name="PrintAccountPayee" id="PrintAccountPayee" checked > Account Payee Cheque </div>
                                        <div class="col-sm-6"><input type="checkbox" value="YES" name="PrintAccountNumber" id="PrintAccountNumber" checked > Print Account Number</div>      
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="button" onclick="chequeVerification('verify')" class="btn btn-primary btn-new" style="width:50px;">Verify</button>
                                            <button type="button" onclick="printCheque('print')" class="btn btn-primary btn-new" style="width:50px;">Print</button>
                                            <button type="button" onclick="chequeVerification('delete')" class="btn btn-primary btn-new" style="width:50px;">Delete</button>
                                            <button type="button" onclick="salaryDetails('show')" id="back" class="btn btn-primary btn-new" style="width:50px;">Back</button>
                                        </div>
                                    </div>
                                </div>
                                    
                                    
                                </div>
                            </div>
                        
                    
                        <div class="col-sm-5" <?php if($count > 18){?> style="overflow-y:scroll;height:522px;" <?php }?> >
                            <script>
                            $(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});
                            </script>
                            
                            <table class = "table table-striped table-hover  responstable">     
                                <thead>
                                    <tr>
                                        <th style="text-align: left;width:30px;" ><input type="checkbox" onchange="totalCount()" id="select_all" checked /></th>
                                        <th style="width:70px;">EmpCode</th>
                                        <th>EmpName</th>
                                        <th style="width:50px;" >Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data as $row){?>
                                    <tr>
                                        <?php if($row['SalarData']['NetSalary'] !=0){?>
                                        <td><input type="checkbox" onchange="totalCount()" class="checkbox"  name="PrintCheckAll[]" value="<?php echo $row['SalarData']['EmpCode'];?>" checked ></td>
                                        <td><?php echo $row['SalarData']['EmpCode'];?></td>
                                        <td><?php echo $row['SalarData']['EmpName'];?></td>
                                        <td><?php echo $row['SalarData']['NetSalary'];?></td>
                                        <?php }?>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
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
        if(isset($_REQUEST['EmpCode'])){
            
            $EmpBranchName      =   $_REQUEST['BranchName'];
            $EmpCostCenter      =   $_REQUEST['CostCenter'];
            $MonthArray         =   explode("-",$_REQUEST['SalaryMonth']);
            $Year               =   $MonthArray[0];
            $Month              =   $MonthArray[1];
            $PrintCheckFrom     =   $_REQUEST['PrintCheckFrom'];
            $PrintCheckTo       =   $_REQUEST['PrintCheckTo'];
            $ChequeNumber       =   $PrintCheckFrom;
            $ChequeDate         =   $_REQUEST['CheckDate'];
            $PrintDate          =   date('Y-m-d');
            $BankName           =   $_REQUEST['BankName'];
            
            if($_REQUEST['PrintAccountPayee'] =="YES"){
                $AccountPayCheque   =   addslashes("A/C PAYEE ONLY");
            }
            else{
                $AccountPayCheque      =   "";
            }
             
            $i=0;
            foreach($_REQUEST['EmpCode'] as $EmpMasCode){
               
                $dataArr=$this->SalarData->find('first',array(
                    'fields'=>array('EmpCode','EmpName','Branch','CostCenter','NetSalary','SalDate','AcNo'),
                    'conditions'=>array('Branch'=>$EmpBranchName,'CostCenter'=>$EmpCostCenter,'EmpCode'=>$EmpMasCode,'MONTH(SalDate)'=>$Month,'YEAR(SalDate)'=>$Year,'SalaryReceiveStatus'=>NULL)
                    ));
                
                $data           =   $dataArr['SalarData'];
                
                $EmpCode        =   $data['EmpCode'];
                $EmpName        =   $data['EmpName'];
                $BranchName     =   $data['Branch'];
                $CostCenter     =   $data['CostCenter'];
                $Amount         =   round($data['NetSalary']);
                $SalaryMonth    =   $data['SalDate'];
                
                $NewId          =   $PrintCheckFrom+$i;
                $len            =   strlen($NewId);
                $NewNumber      =   '000000';
                $NewNumber      =   substr_replace($NewNumber,'',0,$len);
                $ChequeNumber   =   $NewNumber.$NewId;
                $AcNo           =   $data['AcNo'];
                
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
                
                if($list_value!=''){									
                            $list_value=$list_value.",('".$EmpCode."','".$EmpName."','".$BranchName."','".$CostCenter."','".$Amount."','".$SalaryMonth."','".$ChequeDate."','".$PrintDate."','".$BankName."','".$ChequeNumber."','".$AccountPayCheque."','".$PrintAccountNumber."','".$AcNo."','".$AmountInRupees."','".$AmountInWord."')";
                        }
                        else{
                                         $list_value="('".$EmpCode."','".$EmpName."','".$BranchName."','".$CostCenter."','".$Amount."','".$SalaryMonth."','".$ChequeDate."','".$PrintDate."','".$BankName."','".$ChequeNumber."','".$AccountPayCheque."','".$PrintAccountNumber."','".$AcNo."','".$AmountInRupees."','".$AmountInWord."')";
                        }
                        
                $i++;        
                       
            }
            
            $this->SalarData->query("INSERT INTO ChequePrintMaster(`EmpCode`,`EmpName`,`BranchName`,`CostCenter`,`Amount`,`SalaryMonth`,
            `ChequeDate`,`PrintDate`,`BankName`,`ChequeNumber`,`AccountPayCheque`,`PrintAccountNumber`,`AcNo`,`AmountInRupees`,`AmountInWord`) values $list_value"); 
            
            die;
        } 
    }
    
    public function chequeverified(){
        echo $this->ChequeMaster->find('count',array('conditions'=>array('PrintStatus'=>NULL)));die;
    }
    
    public function chequenoexist(){ 
        $PrintCheckFrom =   $_REQUEST['PrintCheckFrom'];
        $PrintCheckTo   =   $_REQUEST['PrintCheckTo'];
        $PrintBankName  =   $_REQUEST['PrintBankName'];
        echo $this->ChequeMaster->find('count',array('conditions'=>"ChequeNumber between '$PrintCheckFrom' and '$PrintCheckTo' and BankName='$PrintBankName'"));die;
    }
    
    public function chequeverification(){
        if($_REQUEST['type'] =="verify"){
            $data=$this->ChequeMaster->find('all',array('conditions'=>array('PrintStatus'=>NULL)));
             
            foreach($data as $row){
                $Id             =   $row['ChequeMaster']['Id'];
                $EmpCode        =   $row['ChequeMaster']['EmpCode'];
                $SalaryMonth    =   $row['ChequeMaster']['SalaryMonth'];
                $ChequeNumber   =   $row['ChequeMaster']['ChequeNumber'];
                $ChequeDate     =   $row['ChequeMaster']['ChequeDate'];
                $PrintDate      =   $row['ChequeMaster']['PrintDate'];
        
                $this->SalarData->query("UPDATE salary_data SET ChequeNumber='$ChequeNumber',ChequeDate='$ChequeDate',
                PrintDate='$PrintDate',SalaryReceiveStatus='YES' WHERE EmpCode='$EmpCode' AND date(SalDate)='$SalaryMonth'");
                $this->ChequeMaster->query("UPDATE ChequePrintMaster SET PrintStatus='YES' WHERE Id='$Id'");
            }
            echo "Your selected data verify successfully.";die;
        }
        else if($_REQUEST['type'] =="delete"){
            $this->ChequeMaster->query("delete from ChequePrintMaster WHERE PrintStatus IS NULL"); 
            echo "Your selected data delete successfully.";die;
        }
    }

    public function empcount($conditoin,$CostCenter){
        $conditoin1=array('CostCenter'=>$CostCenter);
        return $this->SalarData->find('count',array('conditions'=>array_merge($conditoin,$conditoin1)));
    }
    
    public function printedcount($conditoin,$CostCenter){
       $conditoin1=array('SalaryReceiveStatus'=>'YES','CostCenter'=>$CostCenter);
       return $this->SalarData->find('count',array('conditions'=>array_merge($conditoin,$conditoin1)));
    }
    
    public function remainingcount($conditoin,$CostCenter){
       $conditoin1=array('SalaryReceiveStatus'=>NULL,'CostCenter'=>$CostCenter,'NetSalary !='=>'0');
       return $this->SalarData->find('count',array('conditions'=>array_merge($conditoin,$conditoin1)));
    }

    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
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