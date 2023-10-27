 <?php
class WorkHomeProcessSalarysController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster','SalarData','DesignationNameMaster','UploadDeductionMaster','IncomtaxMaster','LoanMaster','UploadIncentiveBreakup','OldAttendanceIssue');
        

    public function index(){
        $this->layout='home';
       
		$branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
			
            $req   			=   $this->request->data;
            $branch 		=   $req['WorkHomeProcessSalarys']['branch_name'];
            $m      		=   $_REQUEST['EmpMonthExp'];
            $y      		=   $_REQUEST['EmpYearExp'];
		
			$usersalmonth	=	$y."-".$m;
			$where_branch  	=   $branch !="ALL"?"AND BranchName='$branch'":"";
			$EmployeeSalary	=	$this->Masjclrentry->query("SELECT * FROM `Add_Testdata` WHERE SalMonth='$usersalmonth'  $where_branch");
        
			foreach($EmployeeSalary as $data){
				
				
				$addnewid				=	$data['Add_Testdata']['Id'];

				$eid				=	$data['Add_Testdata']['EmpCode'];
				$BranchCostcenter	=	$data['Add_Testdata']['CostCenter'];
				$branch 			=   $data['Add_Testdata']['BranchName'];
				$maxsaldays 		=   $data['Add_Testdata']['MaxSalDays'];
				$saldays 			=   $data['Add_Testdata']['SalDays'];
				$exp_data			=	explode("-",$data['Add_Testdata']['SalMonth']);
				$m      			=   $exp_data[1];
				$y      			=   $exp_data[0];
				//'Status'=>0,
				$conditoin  		=   array('BranchName'=>$branch,'CostCenter'=>$BranchCostcenter,'EmpCode'=>$eid);
				$conditoin1 		=   array('MONTH(AttandDate)'=>$m,'YEAR(AttandDate)'=>$y);

				$mwd    			=   cal_days_in_month(CAL_GREGORIAN, $m, $y);
				$SalayDay       	=   $y."-".$m."-".$mwd;
				$existsalayday  	=   $this->SalarData->find('first',array('conditions'=>array('Branch'=>$branch,'CostCenter'=>$data['CostCenter'],'date(SalayDate)'=>$SalayDay,'EmpCode'=>$eid)));
				
				$val     		=   $this->Masjclrentry->find('first',array('conditions'=>$conditoin)); 
			   // $data_2     		=   $this->Masjclrentry->find('first', array('conditions' =>array('MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'BranchName'=>$branch,'CostCenter'=>$data['CostCenter'],'Status'=>0,'EmpCode'=>$eid)));
				
				//$val                        = 	array_merge($data_1,$data_2);
				
				$conditoin5 		=   array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m,'BranchName'=>$branch);
				$hcnt       		=   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5));
				
				$OldStatus			=	"";
				$AttArrOld			=	array();
				$OnSiteArr			=	array();
				
				
				
				//print_r($val);die;
				
				$n=1; 
			

					$emp_status		=	$val['Masjclrentry']['Status'];
					$emp_regdat		=	$val['Masjclrentry']['ResignationDate'];
					
					$val['Masjclrentry']['EmpLocation']="OnSite";	
					$conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
					
					if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
						$AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
						$AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
					}
					else if($val['Masjclrentry']['EmpLocation'] =="Field"){
						$AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
					}
					else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
						//$OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$y}-{$m}")));
						//$OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
						$OSC=$saldays;
					}
							  
					$EmpType        =   $val['Masjclrentry']['EmpType'];
					$EmpCode        =   $val['Masjclrentry']['EmpCode'];
					$EmpName        =   $val['Masjclrentry']['EmpName'];
					$CostCenter     =   $val['Masjclrentry']['CostCenter'];
					$Desgination    =   $val['Masjclrentry']['Desgination'];
					$Dept           =   $val['Masjclrentry']['Dept'];
					$BranchName     =   $val['Masjclrentry']['BranchName'];
					$Basic          =   $val['Masjclrentry']['bs'];
					$hra            =   $val['Masjclrentry']['hra'];
					$Bonus          =   $val['Masjclrentry']['Bonus'];
					$conv           =   $val['Masjclrentry']['conv'];
					$Portfolio      =   $val['Masjclrentry']['portf'];
					$MedicalAllow   =   $val['Masjclrentry']['ma'];
					$lta            =   $val['Masjclrentry']['lta'];
					$SpecialAllow   =   $val['Masjclrentry']['sa'];
					$OtherAllow     =   $val['Masjclrentry']['oa'];
					$PLI            =   $val['Masjclrentry']['PLI'];
					$Gross          =   $val['Masjclrentry']['Gross'];
					$DOJ            =   $val['Masjclrentry']['DOJ'];
							
					  
						   
							$A=0;
								$P=0;
								$OD=0;
								$HD=0;
								$DH=0;
								$F=0;
								$L=0;
								$ADAY=0;
								$TS=$this->total_sundays($m,$y);
								for($j=1;$j<=$mwd;$j++){
									
									if(strtotime("$y-$m-$j") >= strtotime($DOJ)){
									
									$Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
									
									if(!empty($AttArrOld)){
										$OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
									}
									
									if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
									   //echo "<td></td>"; 
									}
									else{
									
									if($Status =="A"){$A=$A+1;}
									if($Status =="P"){$P=$P+1;}
									if($Status =="OD"){$OD=$OD+1;}
									if($Status =="HD"){$HD=$HD+1;}
									if($Status =="DH"){$DH=$DH+1;}
									if($Status =="F"){$F=$F+1;}
									if($Status =="L"){$L=$L+1;}
									if($Status =="HL"){$L=$L+0.5;}
									if($Status =="FL"){$L=$L+1;}
									if($Status =="HDL"){$L=$L+1;}
									if($Status =="DHL"){$L=$L+1;}
									
									if($Status =="P" && $OldStatus==""){$ADAY=$ADAY+1;}
									if($Status =="P" && $OldStatus =="DH"){$ADAY=$ADAY+0.5;}
									if($Status =="P" && $OldStatus =="HD"){$ADAY=$ADAY+0.5;}
									if($Status =="P" && $OldStatus =="F"){$ADAY=$ADAY+0.5;}
									if($Status =="HD"){$ADAY=$ADAY+0.5;}
									if($Status =="DH"){$ADAY=$ADAY+0.5;}
									if($Status =="F"){$ADAY=$ADAY+0.5;}
									if($Status =="FL"){$ADAY=$ADAY+0.5;}
									if($Status =="HDL"){$ADAY=$ADAY+0.5;}
									if($Status =="DHL"){$ADAY=$ADAY+0.5;}
									
									if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
										//if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
									}
									else{
										//echo "<td>$OldStatus$Status</td>"; 
									}
									
									}
									
								}
									
									
								}
								
								
								 $AMY=strtotime("$y-$m");
								if($val['Masjclrentry']['Status']==1){
									if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['DOJ'])))==$AMY){
										$mwd1=$this->dayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
										$sund=$this->sundayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
										$HolDay=$this->HolydayCount($val['Masjclrentry']['DOJ'],"$y-$m-$mwd",$val['Masjclrentry']['BranchName']);
										
										$TotDay=$mwd1-($sund+$HolDay);
										$FinDay=round(80*$TotDay/100); 
									}
									else{
										$HolDay=$hcnt;
										$TotDay=$mwd-($TS+$HolDay);
										$FinDay=round(80*$TotDay/100);   
									}   
								}
								else{
									if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['ResignationDate'])))==$AMY){
										$mwd1=$this->dayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
										$sund=$this->sundayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
										$HolDay=$this->HolydayCount("$y-$m-1",$val['Masjclrentry']['ResignationDate'],$val['Masjclrentry']['BranchName']);
										
										$TotDay=$mwd1-($sund+$HolDay);
										$FinDay=round(80*$TotDay/100);  
									}
									else{
										
										if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['DOJ'])))==$AMY){
										$mwd1=$this->dayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
										$sund=$this->sundayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
										$HolDay=$this->HolydayCount($val['Masjclrentry']['DOJ'],"$y-$m-$mwd",$val['Masjclrentry']['BranchName']);
										
										$TotDay=$mwd1-($sund+$HolDay);
										$FinDay=round(80*$TotDay/100); 
										}
										else{

											$HolDay=$hcnt;
											$TotDay=$mwd-($TS+$HolDay);
											$FinDay=round(80*$TotDay/100);
										}
										
										/*
										$HolDay=$hcnt;
										$TotDay=$mwd-($TS+$HolDay);
										$FinDay=round(80*$TotDay/100); 
										*/

										/*
										$mwd1=0;
										$sund=0;
										$HolDay=0;

										$TotDay=$mwd1-($sund+$HolDay);
										$FinDay=round(80*$TotDay/100); 
										*/
									}
								}
								
								//$TotPre=$P+$OD+$L+($HD+$DH+$F)/2;
								$TotPre=$P+$OD+$L+($HD+$DH+$F);
								
								if($TotPre >= $FinDay){
									$Holiday=$HolDay;  
								}
								else{
									$Holiday=0;
								}
								
								
							$H=$Holiday;
							$W=0;
							if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
								$SalDay=$OSC;
								$ActualDays=$OSC;  
							}
							else{
								$SalDay=$P+$OD+$L+$H+($HD+$DH+$F)/2;
								$ActualDays=$ADAY;
							}

							if($SalDay >=7){$W=1;}
							if($SalDay >=12){$W=2;}
							if($SalDay >=18){$W=3;}
							if($SalDay >=24){$W=4;}
							if($TS ==5 && $mwd ==31 && $SalDay >=26){$W=5;}
							if($TS ==5 && $mwd ==30 && $SalDay >=25){$W=5;}
							
							if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
								$W=0;
							}
								
							$Total=($SalDay+$W);

							if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
								$Leave              =   0;
								
								/*
								echo "<td style='text-align:center;' >0</td>";
								echo "<td style='text-align:center;' >$OSC</td>";
								echo "<td style='text-align:center;' >0</td>";
								echo "<td style='text-align:center;' >0</td>";
								echo "<td style='text-align:center;' >0</td>";
								echo "<td style='text-align:center;' >0</td>";
								 
								 */
							}
							else{
								$Leave              =   $L;
							?>
							<!--
							<td style="text-align: center;"><?php echo $A;?></td>
							<td style="text-align: center;"><?php echo $P;?></td>
							<td style="text-align: center;"><?php echo $OD;?></td>
							<td style="text-align: center;"><?php echo ($HD+$DH+$F);?></td>
							<td style="text-align: center;"><?php echo $L;?></td>
							<td style="text-align: center;"><?php echo $H;?></td>
							-->
							<?php }?>
							
							<!--
							<td style="text-align: center;"><?php echo $W;?></td>
							<td style="text-align: center;"><?php echo $SalDay;?></td>
							-->
							
							<?php
							   $branch_name    =   $val['Masjclrentry']['BranchName'];
								$cost_center    =   $val['Masjclrentry']['CostCenter'];
								$emp_desig      =   $val['Masjclrentry']['Desgination'];
								$emp_profile    =   $val['Masjclrentry']['Profile'];
								
								//$branch_name        =   "HYDERABAD";
								//$cost_center        =   "CS/OB/HYD/021";
								//$emp_Desgination    =   "EXECUTIVE - VOICE";
								
								
					  
								
							   
								$total_month_day    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
								///$total_month_day    =   date('d', strtotime('last day of previous month'));
								
								if($Total > $total_month_day){
									
									$oversalArr=$this->Masjclrentry->query("SELECT over_saldays FROM `cost_master` WHERE branch='$branch_name' AND cost_center='$cost_center' AND active='1' limit 1");
									
								   
									
									$over_salary_status=$oversalArr[0]['cost_master']['over_saldays'];
									
									if($val['Masjclrentry']['EmpLocation'] =="InHouse" && $over_salary_status =="Yes"){
										//if($emp_desig=="EXECUTIVE - VOICE" || $emp_desig=="Executive - Voice" || $emp_desig=="Sr. Executive - Voice"  || $emp_desig=="SR. EXECUTIVE - VOICE"){
										if($emp_desig=="EXECUTIVE" && $emp_profile=="VOICE"){
											$total_final_day=$Total;
										}
										else{
											$total_final_day=$total_month_day;
										}
									}
									else{
										$total_final_day=$total_month_day;
									}
								}
								else{
									$total_final_day=$Total;
								}
								?>
							
							<!--
							<td style="text-align: center;"><?php echo $total_final_day;?></td>
						</tr>
						-->
						<?php 
						
							$WorkingDay         =   $mwd;
							$CTCOffered         =   $val['Masjclrentry']['CTC'];
							$CurrentCTC         =   $val['Masjclrentry']['CTC'];
							
							
							
							if($total_final_day > $WorkingDay){
								$ExtraDay       =   $total_final_day-$WorkingDay;
							}
							else{
								$ExtraDay       =   0; 
							}
							
							if($total_final_day >= 1){
								if($ExtraDay !=0){
									$EarnedDays         =   $total_final_day-($total_final_day-$WorkingDay);
								}
								else{
								   $EarnedDays         =   $total_final_day;
								}
							}
							else{
								$EarnedDays         =   0;
							}
							
							
							$Basic1             =   round($Basic/$WorkingDay*$EarnedDays);
							$HRA1               =   round($hra/$WorkingDay*$EarnedDays);
							$Bonus1             =   round($Bonus/$WorkingDay*$EarnedDays);
							$Conv1              =   round($conv/$WorkingDay*$EarnedDays);
							$Portfolio1         =   round($Portfolio/$WorkingDay*$EarnedDays);
							$SpecialAllowance1  =   round($SpecialAllow/$WorkingDay*$EarnedDays);
							$OtherAllowance1    =   round($OtherAllow/$WorkingDay*$EarnedDays);
							$MedicalAllowance1  =   round($MedicalAllow/$WorkingDay*$EarnedDays);
							$Gross1             =   round($Basic1+$HRA1+$Bonus1+$Conv1+$Portfolio1+$SpecialAllowance1+$OtherAllowance1+$MedicalAllowance1);
							$ESIElig            =   $val['Masjclrentry']['esielig'];
							$PFELig             =   $val['Masjclrentry']['pfelig'];
							
							if($ESIElig =="YES"){
							$ESIC               =   round($Gross1*0.75/100);
							$ESICCompany        =   ceil($Gross1*3.25/100);
							}
							else{
							$ESIC               =   0; 
							$ESICCompany        =   0;  
							}
							
							if($PFELig =="YES"){
							$EPF               =   round($Basic1*12/100);
							$EPFCompany        =   round($Basic1*12/100);
							$AdminChrg         =   round($Basic1*1/100);
							}
							else{
							$EPF               =   0; 
							$EPFCompany        =   0;  
							$AdminChrg         =   0;  
							}
							   
							$LoanEndDate        =   "$y-$m-01";     
							$LoanArr            =   $this->LoanMaster->find('first',array('fields'=>array('Id','Amount','DeductionPerMonth','DeductedAmount','PendingAmount','LastUpdateDate'),'conditions'=>array('BranchName'=>$BranchName,'EmpCode'=>$EmpCode,'DATE(EndDate) >='=>$LoanEndDate,'TransationStatus'=>'YES','ApproveFirst'=>'Yes','ApproveSecond'=>'Yes','Type'=>'Loan','PendingAmount !='=>0)));
                                                        $this->LoanMaster->query("UPDATE `LoanMaster` SET `PendingAmount`=DeductedAmount,`DeductedAmount`='0',`LastUpdateDate`='' WHERE BranchName='$BranchName' and  EmpCode='$EmpCode' and DATE(EndDate) >='$LoanEndDate' and TransationStatus='Yes' and ApproveFirst='Yes' and ApproveSecond='Yes' and Type='Advance' and PendingAmount='0'");
							$AdvaArr            =   $this->LoanMaster->find('first',array('fields'=>array('Id','Amount','DeductionPerMonth','DeductedAmount','PendingAmount','LastUpdateDate'),'conditions'=>array('BranchName'=>$BranchName,'EmpCode'=>$EmpCode,'DATE(EndDate) >='=>$LoanEndDate,'TransationStatus'=>'YES','ApproveFirst'=>'Yes','ApproveSecond'=>'Yes','Type'=>'Advance','PendingAmount !='=>0)));
							$InceArr            =   $this->UploadIncentiveBreakup->find('all',array('fields'=>array('Amount'),'conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'EmpCode'=>$EmpCode,'YEAR(SalaryMonth)'=>$y,'MONTH(SalaryMonth)'=>$m,'ApproveStatus'=>'Approve','UploadType'=>'UploadIncentive')));
							
							if(!empty($LoanArr)){
								$LTD=$LoanArr['LoanMaster']['Amount'];
								$LPD=$LoanArr['LoanMaster']['DeductionPerMonth'];
								
								$UpdateId       =   $LoanArr['LoanMaster']['Id'];
								$DeductedAmount =   ($LoanArr['LoanMaster']['DeductedAmount']+$LPD);
								$PendingAmount  =   ($LoanArr['LoanMaster']['PendingAmount']-$LPD);
								$lastupdatedate =   date("Y-m",strtotime($LoanArr['LoanMaster']['LastUpdateDate']));
								$curupdatedate  =   date("Y-m");
								
								if($LoanArr['LoanMaster']['DeductedAmount'] ==""){
									$this->LoanMaster->query("UPDATE `LoanMaster` SET `DeductedAmount`='$DeductedAmount',`PendingAmount`='$PendingAmount',`LastUpdateDate`=NOW() WHERE Id='$UpdateId'");
								}
								else{
									if($curupdatedate !=$lastupdatedate){
										$this->LoanMaster->query("UPDATE `LoanMaster` SET `DeductedAmount`='$DeductedAmount',`PendingAmount`='$PendingAmount',`LastUpdateDate`=NOW() WHERE Id='$UpdateId'");
									}
								}
								
	   
							}
							else{
								$LTD=0;
								$LPD=0;
							}
							
							if(!empty($AdvaArr)){
								$ATD=$AdvaArr['LoanMaster']['Amount'];
								$APD=$AdvaArr['LoanMaster']['DeductionPerMonth'];

								$UpdateId       =   $AdvaArr['LoanMaster']['Id'];
								$DeductedAmount =   ($AdvaArr['LoanMaster']['DeductedAmount']+$ATD);
								$PendingAmount  =   ($AdvaArr['LoanMaster']['PendingAmount']-$APD);
								$lastupdatedate =   date("Y-m",strtotime($AdvaArr['LoanMaster']['LastUpdateDate']));
								$curupdatedate  =   date("Y-m");
								
								
								if($AdvaArr['LoanMaster']['DeductedAmount'] ==""){
									$this->LoanMaster->query("UPDATE `LoanMaster` SET `DeductedAmount`='$DeductedAmount',`PendingAmount`='$PendingAmount',`LastUpdateDate`=NOW() WHERE Id='$UpdateId'");
								}
								else{
									if($curupdatedate !=$lastupdatedate){
										$this->LoanMaster->query("UPDATE `LoanMaster` SET `DeductedAmount`='$DeductedAmount',`PendingAmount`='$PendingAmount',`LastUpdateDate`=NOW() WHERE Id='$UpdateId'");
									}
								}
								   
							}
							else{
								$ATD=0;
								$APD=0;
							}
							
							if(!empty($InceArr)){
								$INS=0;
								foreach($InceArr AS $insrow){
									$INS=$INS+$insrow['UploadIncentiveBreakup']['Amount']; 
								} 
							}
							else{
								$INS=0;
							}
							
							//OldAttendanceIssue
							
							$AdvTaken           =   $ATD;
							$AdvPaid            =   $APD;
							$LoanTaken          =   $LTD;
							$LoanDed            =   $LPD;
							$Incentive          =   $INS;
							$ExtraDayIncentive  =   round($val['Masjclrentry']['NetInhand']/$WorkingDay*$ExtraDay); 
							$Arrear             =   0;
							
							$PLI                =   round($val['Masjclrentry']['PLI']/$WorkingDay*$EarnedDays);
							$dedsalarymonth     =   $y."-".$m;
							$ShshArr            =   $this->DesignationNameMaster->find('first',array('fields'=>array('InsuranceAmount'),'conditions'=>array('Department'=>$Dept,'Designation'=>$Desgination)));
							$SHSHAmount         =   $ShshArr['DesignationNameMaster']['InsuranceAmount'];
							$DedArr             =   $this->UploadDeductionMaster->find('first',array('conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'EmpCode'=>$EmpCode,'SalaryMonth'=>$dedsalarymonth,'ProcessStatus'=>'Processed')));
						   
							$IncometaxArr       =   $this->IncomtaxMaster->find('first',array('conditions'=>array('BranchName'=>$BranchName,'EmpCode'=>$EmpCode,'TaxMonth'=>$dedsalarymonth)));
							
							if($IncometaxArr['IncomtaxMaster']['IncomTax'] !=""){
								$IncomeTax      =   $IncometaxArr['IncomtaxMaster']['IncomTax'];
							}
							else{
							   $IncomeTax       =   0; 
							}
							
							$CTC                =   round($Gross1+$Incentive+$ExtraDayIncentive+$Arrear+$PLI+$ESICCompany+$EPFCompany+$AdminChrg);
							
							if($SHSHAmount !="" && $CTC > $SHSHAmount){
							$SHSH               =   $SHSHAmount;
							}
							else{
							$SHSH               =   0;
							}
							
							$MobileDedcution    =   $DedArr['UploadDeductionMaster']['MobileDeduction'];
							$ShortCollection    =   $DedArr['UploadDeductionMaster']['ShortCollection'];
							$AssetRecovery      =   $DedArr['UploadDeductionMaster']['AssetRecovery'];
							$ProTaxDeduction    =   $DedArr['UploadDeductionMaster']['ProfessionalTax'];
							$LeaveDeduction     =   $DedArr['UploadDeductionMaster']['LeaveDeduction'];
							$OtherDeduction     =   $DedArr['UploadDeductionMaster']['OthersDeduction'];
							$Insurance          =   $DedArr['UploadDeductionMaster']['Insurance'];
							
							$OtherDeductionRemarks  = $DedArr['UploadDeductionMaster']['Remarks'];
							$TotalDeduction         =   round($SHSH+$MobileDedcution+$ShortCollection+$AssetRecovery+$ProTaxDeduction+$LeaveDeduction+$Insurance+$OtherDeduction);
							
							$NetSalary          =   round(($Gross1+$Incentive+$ExtraDayIncentive+$Arrear+$PLI)-($ESIC+$EPF+$IncomeTax+$AdvPaid+$LoanDed+$SHSH+$MobileDedcution+$ShortCollection+$AssetRecovery+$ProTaxDeduction+$LeaveDeduction+$OtherDeduction+$Insurance));
							$SalDate            =   $SalayDay;
							$EPFNo              =   $val['Masjclrentry']['EPFNo'];
							$ESICNo             =   $val['Masjclrentry']['ESICNo'];
							$ChequeNumber       =   "";
							$ChequeDate         =   "";
							$PrintDate          =   "";
							$LeftStatus         =   $val['Masjclrentry']['ResignationDate'];
							$EmpStatus          =   $val['Masjclrentry']['Status'];
							$AcNo               =   $val['Masjclrentry']['AcNo'];
							
							//'CostCenter'=>$CostCenter,
							
							$existprocess       =   $this->SalarData->find('first',array('fields'=>array('Id','ChequeNumber','ChequeDate','PrintDate'),'conditions'=>array('EmpCode'=>$EmpCode,'Branch'=>$BranchName,'date(SalDate)'=>$SalDate)));
							if(!empty($existprocess)){
								$SalaryProcessId    =   $existprocess['SalarData']['Id'];
								$ChequeNumber       =   $existprocess['SalarData']['ChequeNumber'];
								$ChequeDate         =   $existprocess['SalarData']['ChequeDate'];
								$PrintDate          =   $existprocess['SalarData']['PrintDate'];
								
								
								$this->SalarData->query("UPDATE salary_data SET `EmpName`='$EmpName',`CostCenter`='$CostCenter',`Designation`='$Desgination',`Branch`='$BranchName',`Basic`='$Basic',`HRA`='$hra',`Bonus`='$Bonus',`Conv`='$conv',`Portfolio`='$Portfolio',`MedicalAllowance`='$MedicalAllow',`LTA`='$lta',`SpecialAllowance`='$SpecialAllow',`OtherAllowance`='$OtherAllow',`PLI1`='$PLI',`Gross`='$Gross',`WorkingDays`='$WorkingDay',`CTCOffered`='$CTCOffered',`CurrentCTC`='$CurrentCTC',`ActualDays`='$ActualDays',`WeekOff`='$W',`Holidays`='$H',`EarnedDays`='$EarnedDays',`ExtraDay`='$ExtraDay',`Leave`='$Leave',`Basic1`='$Basic1',`HRA1`='$HRA1',`Bonus1`='$Bonus1',`Conv1`='$Conv1',`Portfolio1`='$Portfolio1',`SpecialAllowance1`='$SpecialAllowance1',`OtherAllowance1`='$OtherAllowance1',`MedicalAllowance1`='$MedicalAllowance1',`Gross1`='$Gross1',`ESIElig`='$ESIElig',`PFELig`='$PFELig',`ESIC`='$ESIC',`EPF`='$EPF',`IncomeTax`='$IncomeTax',`AdvTaken`='$AdvTaken',`AdvPaid`='$AdvPaid',`LoanTaken`='$LoanTaken',`LoanDed`='$LoanDed',`Incentive`='$Incentive',`ExtraDayIncentive`='$ExtraDayIncentive',`Arrear`='$Arrear',`PLI`='$PLI',`NetSalary`='$NetSalary',`ESICCompany`='$ESICCompany',`EPFCompany`='$EPFCompany',`AdminChrg`='$AdminChrg',`CTC`='$CTC',`SHSH`='$SHSH',`MobileDedcution`='$MobileDedcution',`ShortCollection`='$ShortCollection',`AssetRecovery`='$AssetRecovery',`Insurance`='$Insurance',`ProTaxDeduction`='$ProTaxDeduction',`LeaveDeduction`='$LeaveDeduction',`OtherDeduction`='$OtherDeduction',`OtherDeductionRemarks`='$OtherDeductionRemarks',`TotalDeduction`='$TotalDeduction',`SalDate`='$SalDate',`EPFNo`='$EPFNo',`ESICNo`='$ESICNo',`ChequeNumber`='$ChequeNumber',`ChequeDate`='$ChequeDate',`PrintDate`='$PrintDate',`Status`='$EmpStatus',`LeftStatus`='$LeftStatus',`SalayDate`='$SalayDay',`AcNo`='$AcNo' WHERE Id='$SalaryProcessId'"); 
								//$this->SalarData->query("UPDATE salary_data SET `EmpName`='$EmpName',`CostCenter`='$CostCenter',`Designation`='$Desgination',`Branch`='$BranchName',`Basic`='$Basic',`HRA`='$hra',`Bonus`='$Bonus',`Conv`='$conv',`Portfolio`='$Portfolio',`MedicalAllowance`='$MedicalAllow',`LTA`='$lta',`SpecialAllowance`='$SpecialAllow',`OtherAllowance`='$OtherAllow',`PLI1`='$PLI',`Gross`='$Gross',`WorkingDays`='$WorkingDay',`CTCOffered`='$CTCOffered',`CurrentCTC`='$CurrentCTC',`ActualDays`='$ActualDays',`WeekOff`='$W',`Holidays`='$H',`EarnedDays`='$EarnedDays',`ExtraDay`='$ExtraDay',`Leave`='$Leave',`Basic1`='$Basic1',`HRA1`='$HRA1',`Bonus1`='$Bonus1',`Conv1`='$Conv1',`Portfolio1`='$Portfolio1',`SpecialAllowance1`='$SpecialAllowance1',`OtherAllowance1`='$OtherAllowance1',`MedicalAllowance1`='$MedicalAllowance1',`Gross1`='$Gross1',`ESIElig`='$ESIElig',`PFELig`='$PFELig',`ESIC`='$ESIC',`EPF`='$EPF',`IncomeTax`='$IncomeTax',`Incentive`='$Incentive',`ExtraDayIncentive`='$ExtraDayIncentive',`Arrear`='$Arrear',`PLI`='$PLI',`NetSalary`='$NetSalary',`ESICCompany`='$ESICCompany',`EPFCompany`='$EPFCompany',`AdminChrg`='$AdminChrg',`CTC`='$CTC',`SHSH`='$SHSH',`MobileDedcution`='$MobileDedcution',`ShortCollection`='$ShortCollection',`AssetRecovery`='$AssetRecovery',`Insurance`='$Insurance',`ProTaxDeduction`='$ProTaxDeduction',`LeaveDeduction`='$LeaveDeduction',`OtherDeduction`='$OtherDeduction',`OtherDeductionRemarks`='$OtherDeductionRemarks',`TotalDeduction`='$TotalDeduction',`SalDate`='$SalDate',`EPFNo`='$EPFNo',`ESICNo`='$ESICNo',`ChequeNumber`='$ChequeNumber',`ChequeDate`='$ChequeDate',`PrintDate`='$PrintDate',`Status`='$EmpStatus',`LeftStatus`='$LeftStatus',`SalayDate`='$SalayDay',`AcNo`='$AcNo' WHERE Id='$SalaryProcessId'"); 
								$this->SalarData->query("UPDATE `Add_Testdata` SET UpdateRow='2' WHERE Id='$addnewid'");
							}
							else{
								if($list_value!=''){									
									$list_value=$list_value.",('".$EmpCode."','".$EmpName."','".$CostCenter."','".$Desgination."','".$BranchName."','".$Basic."','".$hra."','".$Bonus."','".$conv."','".$Portfolio."','".$MedicalAllow."','".$lta."','".$SpecialAllow."','".$OtherAllow."','".$PLI."','".$Gross."','".$WorkingDay."','".$CTCOffered."','".$CurrentCTC."','".$ActualDays."','".$W."','".$H."','".$EarnedDays."','".$ExtraDay."','".$Leave."','".$Basic1."','".$HRA1."','".$Bonus1."','".$Conv1."','".$Portfolio1."','".$SpecialAllowance1."','".$OtherAllowance1."','".$MedicalAllowance1."','".$Gross1."','".$ESIElig."','".$PFELig."','".$ESIC."','".$EPF."','".$IncomeTax."','".$AdvTaken."','".$AdvPaid."','".$LoanTaken."','".$LoanDed."','".$Incentive."','".$ExtraDayIncentive."','".$Arrear."','".$PLI."','".$NetSalary."','".$ESICCompany."','".$EPFCompany."','".$AdminChrg."','".$CTC."','".$SHSH."','".$MobileDedcution."','".$ShortCollection."','".$AssetRecovery."','".$Insurance."','".$ProTaxDeduction."','".$LeaveDeduction."','".$OtherDeduction."','".$OtherDeductionRemarks."','".$TotalDeduction."','".$SalDate."','".$EPFNo."','".$ESICNo."','".$ChequeNumber."','".$ChequeDate."','".$PrintDate."','".$EmpStatus."','".$LeftStatus."','".$SalayDay."','".$AcNo."')";
								}
								else{
												 $list_value="('".$EmpCode."','".$EmpName."','".$CostCenter."','".$Desgination."','".$BranchName."','".$Basic."','".$hra."','".$Bonus."','".$conv."','".$Portfolio."','".$MedicalAllow."','".$lta."','".$SpecialAllow."','".$OtherAllow."','".$PLI."','".$Gross."','".$WorkingDay."','".$CTCOffered."','".$CurrentCTC."','".$ActualDays."','".$W."','".$H."','".$EarnedDays."','".$ExtraDay."','".$Leave."','".$Basic1."','".$HRA1."','".$Bonus1."','".$Conv1."','".$Portfolio1."','".$SpecialAllowance1."','".$OtherAllowance1."','".$MedicalAllowance1."','".$Gross1."','".$ESIElig."','".$PFELig."','".$ESIC."','".$EPF."','".$IncomeTax."','".$AdvTaken."','".$AdvPaid."','".$LoanTaken."','".$LoanDed."','".$Incentive."','".$ExtraDayIncentive."','".$Arrear."','".$PLI."','".$NetSalary."','".$ESICCompany."','".$EPFCompany."','".$AdminChrg."','".$CTC."','".$SHSH."','".$MobileDedcution."','".$ShortCollection."','".$AssetRecovery."','".$Insurance."','".$ProTaxDeduction."','".$LeaveDeduction."','".$OtherDeduction."','".$OtherDeductionRemarks."','".$TotalDeduction."','".$SalDate."','".$EPFNo."','".$ESICNo."','".$ChequeNumber."','".$ChequeDate."','".$PrintDate."','".$EmpStatus."','".$LeftStatus."','".$SalayDay."','".$AcNo."')";
								}
								
								$this->SalarData->query("UPDATE `Add_Testdata` SET UpdateRow='1' WHERE Id='$addnewid'");
							}
						   
							
							
							
						
						 }	
						 
						 
						 
						 //echo $list_value;die;
							  
								
								
							if($list_value !=""){
								$this->SalarData->query("INSERT INTO salary_data(`EmpCode`,`EmpName`,`CostCenter`,`Designation`,`Branch`,`Basic`,`HRA`,`Bonus`,`Conv`,`Portfolio`,`MedicalAllowance`,`LTA`,`SpecialAllowance`,`OtherAllowance`,`PLI1`,`Gross`,`WorkingDays`,`CTCOffered`,`CurrentCTC`,`ActualDays`,`WeekOff`,`Holidays`,`EarnedDays`,`ExtraDay`,`Leave`,`Basic1`,`HRA1`,`Bonus1`,`Conv1`,`Portfolio1`,`SpecialAllowance1`,`OtherAllowance1`,`MedicalAllowance1`,`Gross1`,`ESIElig`,`PFELig`,`ESIC`,`EPF`,`IncomeTax`,`AdvTaken`,`AdvPaid`,`LoanTaken`,`LoanDed`,`Incentive`,`ExtraDayIncentive`,`Arrear`,`PLI`,`NetSalary`,`ESICCompany`,`EPFCompany`,`AdminChrg`,`CTC`,`SHSH`,`MobileDedcution`,`ShortCollection`,`AssetRecovery`,`Insurance`,`ProTaxDeduction`,`LeaveDeduction`,`OtherDeduction`,`OtherDeductionRemarks`,`TotalDeduction`,`SalDate`,`EPFNo`,`ESICNo`,`ChequeNumber`,`ChequeDate`,`PrintDate`,`Status`,`LeftStatus`,`SalayDate`,`AcNo`) values $list_value"); 
								
								/* Start Generate Voucher */
								$MaxVoucher     =   $this->SalarData->query("SELECT MAX(VchId) AS VoucherId FROM salary_data");
								$MaxVoucherId   =   $MaxVoucher[0][0]['VoucherId'];
								$VoucherArray   =   $this->SalarData->find('first',array('fields'=>array('VchId'),'conditions'=>array('Branch'=>$branch,'date(SalDate)'=>$SalayDay)));
								$CheckVoucherId =   $VoucherArray['SalarData']['VchId'];

								if($CheckVoucherId !=""){
									$VchId  =   $CheckVoucherId;
								}
								else{
									$VchId  =   $MaxVoucherId+1;
								}

								$MasVoucherNo   =   'MAS/'.date('m/y',strtotime($SalayDay)).'/'.$VchId;
								$IDCVoucherNo   =   'IDC/'.date('m/y',strtotime($SalayDay)).'/'.$VchId;

								$this->SalarData->query("UPDATE  salary_data SET VchId='$VchId' WHERE Branch='$branch' AND DATE(SalDate)='$SalayDay'");
								$this->SalarData->query("UPDATE  salary_data SET VchNo='$MasVoucherNo' WHERE SUBSTRING(EmpCode, 1, 3)='MAS' AND Branch='$branch' AND DATE(SalDate)='$SalayDay'");
								$this->SalarData->query("UPDATE  salary_data SET VchNo='$IDCVoucherNo' WHERE SUBSTRING(EmpCode, 1, 3)='IDC' AND Branch='$branch' AND DATE(SalDate)='$SalayDay'");

								/* End Generate Voucher */
							}
							
							
				/* Start Generate Voucher */
				$MaxVoucher     =   $this->SalarData->query("SELECT MAX(VchId) AS VoucherId FROM salary_data");
				$MaxVoucherId   =   $MaxVoucher[0][0]['VoucherId'];
				$VoucherArray   =   $this->SalarData->find('first',array('fields'=>array('VchId'),'conditions'=>array('Branch'=>$branch,'date(SalDate)'=>$SalayDay)));
				$CheckVoucherId =   $VoucherArray['SalarData']['VchId'];

				if($CheckVoucherId !=""){
					$VchId  =   $CheckVoucherId;
				}
				else{
					$VchId  =   $MaxVoucherId+1;
				}

				$MasVoucherNo   =   'MAS/'.date('m/y',strtotime($SalayDay)).'/'.$VchId;
				$IDCVoucherNo   =   'IDC/'.date('m/y',strtotime($SalayDay)).'/'.$VchId;

				$this->SalarData->query("UPDATE  salary_data SET VchId='$VchId' WHERE Branch='$branch' AND DATE(SalDate)='$SalayDay'");
				$this->SalarData->query("UPDATE  salary_data SET VchNo='$MasVoucherNo' WHERE SUBSTRING(EmpCode, 1, 3)='MAS' AND Branch='$branch' AND DATE(SalDate)='$SalayDay'");
				$this->SalarData->query("UPDATE  salary_data SET VchNo='$IDCVoucherNo' WHERE SUBSTRING(EmpCode, 1, 3)='IDC' AND Branch='$branch' AND DATE(SalDate)='$SalayDay'");

				$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Salary successfully process for '.$branch.'  branch.</span>'); 
				//$this->redirect(array('action'=>'index'));
				$url=$this->webroot.'WorkHomeProcessSalarys';
                echo "<script>window.location.href = '$url';</script>";die;
		}
		

		
    }
    

    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Salary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $m=$_REQUEST['EmpMonth'];
            $y=$_REQUEST['EmpYear'];
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
            if($_REQUEST['BranchName'] !="ALL"){
                $condition=array('Branch'=>$_REQUEST['BranchName'],'date(SalayDate)'=>$SalayDay);
            }
            else{
                $condition=array('date(SalayDate)'=>$SalayDay);
            }
            
            $dataArr   =   $this->SalarData->find('all',array('conditions'=>$condition));
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>CostCenter</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Profile</th>
                        <th>Employee For</th>
                        <th>Billable</th>
                        <th>Branch</th>
                        <th>Basic</th>
                        <th>HRA</th>
                        <th>Bonus</th>
                        <th>Conv</th>
                        <th>Portfolio</th>
                        <th>MedicalAllowance</th>
                        <th>LTA</th>
                        <th>SpecialAllowance</th>
                        <th>OtherAllowance</th>
                        <th>PLI1</th>
                        <th>Gross</th>
                        <th>WorkingDays</th>
                        <th>CTCOffered</th>
                        <th>CurrentCTC</th>
                        <th>ActualDays</th>
                        <th>EarnedDays</th>
                        <th>ExtraDay</th>
                        <th>Leave</th>
                        <th>Basic1</th>
                        <th>HRA1</th>
                        <th>Bonus1</th>
                        <th>Conv1</th>
                        <th>Portfolio1</th>
                        <th>SpecialAllowance1</th>
                        <th>OtherAllowance1</th>
                        <th>MedicalAllowance1</th>
                        <th>Gross1</th>
                        <th>ESIElig</th>
                        <th>PFELig</th>
                        <th>ESIC</th>
                        <th>EPF</th>
                        <th>IncomeTax</th>
                        <th>AdvTaken</th>
                        <th>AdvPaid</th>
                        <th>LoanTaken</th>
                        <th>LoanDed</th>
                        <th>Incentive</th>
                        <th>ExtraDayIncentive</th>
                        <th>Arrear</th>
                        <th>PLI</th>
                        <th>NetSalary</th>
                        <th>ESICCompany</th>
                        <th>EPFCompany</th>
                        <th>AdminChrg</th>
                        <th>CTC</th>
                        <th>SHSH</th>
                        <th>MobileDedcution</th>
                        <th>ShortCollection</th>
                        <th>AssetRecovery</th>
                        <th>Insurance</th>
                        <th>ProTaxDeduction</th>
                        <th>LeaveDeduction</th>
                        <th>OtherDeduction</th>
                        <th>OtherDeductionRemarks</th>
                        <th>TotalDeduction</th>
                        <th>SalDate</th>
                        <th>UAN</th>
                        <th>EPFNo</th>
                        <th>ESICNo</th>
                        <th>ChequeNumber</th>
                        <th>ChequeDate</th>
                        <th>PrintDate</th>
                        <th>LeftStatus</th>
                        <th>TaxTotalGross</th>
                        <th>TaxSection10</th>
                        <th>TaxBalance</th>
                        <th>TaxUnderHd</th>
                        <th>DeductionUnder24</th>
                        <th>TaxGrossTotal</th>
                        <th>TaxAggofChapter6</th>
                        <th>TotalIncome</th>
                        <th>TaxOnTotalIncome</th>
                        <th>EduCess</th>
                        <th>TaxPayEduCess</th>
                        <th>TaxDeductedTillPreviousMonth</th>
                        <th>BalanceTax</th>
                        <th>SalaryPaymentMode</th>
                        <th>AcNo</th>
                        <th>IFSCCode</th>
                        <th>AcBank</th>
                        <th>AcBranch</th>
                        <!--
                        <th>SalaryBranch</th>
                        <th>SalayDate</th>
                        -->

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ 
                    $Emp_Arr    =   $this->Masjclrentry->find('first',array('fields'=>array('Dept','Profile','EmpLocation','Billable_Status','AcNo','IFSCCode','AcBank','AcBranch','UAN'),'conditions'=>array('EmpCode'=>$data['SalarData']['EmpCode'])));
                    $Emp_Row    =   $Emp_Arr['Masjclrentry'];
                    ?>
                    <tr>
                        <td><?php echo $data['SalarData']['EmpCode']?></td>
                        <td><?php echo $data['SalarData']['EmpName']?></td>
                        <td><?php echo $data['SalarData']['CostCenter']?></td>
                        <td><?php echo $Emp_Row['Dept']?></td>
                        <td><?php echo $data['SalarData']['Designation']?></td>
                        <td><?php echo $Emp_Row['Profile']?></td>
                        <td><?php echo $Emp_Row['EmpLocation']?></td>
                        <td><?php echo $Emp_Row['Billable_Status']?></td>
                        <td><?php echo $data['SalarData']['Branch']?></td>
                        <td><?php echo $data['SalarData']['Basic']?></td>
                        <td><?php echo $data['SalarData']['HRA']?></td>
                        <td><?php echo $data['SalarData']['Bonus']?></td>
                        <td><?php echo $data['SalarData']['Conv']?></td>
                        <td><?php echo $data['SalarData']['Portfolio']?></td>
                        <td><?php echo $data['SalarData']['MedicalAllowance']?></td>
                        <td><?php echo $data['SalarData']['LTA']?></td>
                        <td><?php echo $data['SalarData']['SpecialAllowance']?></td>
                        <td><?php echo $data['SalarData']['OtherAllowance']?></td>
                        <td><?php echo $data['SalarData']['PLI1']?></td>
                        <td><?php echo $data['SalarData']['Gross']?></td>
                        <td><?php echo $data['SalarData']['WorkingDays']?></td>
                        <td><?php echo $data['SalarData']['CTCOffered']?></td>
                        <td><?php echo $data['SalarData']['CurrentCTC']?></td>
                        <td><?php echo $data['SalarData']['ActualDays']?></td>
                        <td><?php echo $data['SalarData']['EarnedDays']?></td>
                        <td><?php echo $data['SalarData']['ExtraDay']?></td>
                        <td><?php echo $data['SalarData']['Leave']?></td>
                        <td><?php echo $data['SalarData']['Basic1']?></td>
                        <td><?php echo $data['SalarData']['HRA1']?></td>
                        <td><?php echo $data['SalarData']['Bonus1']?></td>
                        <td><?php echo $data['SalarData']['Conv1']?></td>
                        <td><?php echo $data['SalarData']['Portfolio1']?></td>
                        <td><?php echo $data['SalarData']['SpecialAllowance1']?></td>
                        <td><?php echo $data['SalarData']['OtherAllowance1']?></td>
                        <td><?php echo $data['SalarData']['MedicalAllowance1']?></td>
                        <td><?php echo $data['SalarData']['Gross1']?></td>
                        <td><?php echo $data['SalarData']['ESIElig']?></td>
                        <td><?php echo $data['SalarData']['PFELig']?></td>
                        <td><?php echo $data['SalarData']['ESIC']?></td>
                        <td><?php echo $data['SalarData']['EPF']?></td>
                        <td><?php echo $data['SalarData']['IncomeTax']?></td>
                        <td><?php echo $data['SalarData']['AdvTaken']?></td>
                        <td><?php echo $data['SalarData']['AdvPaid']?></td>
                        <td><?php echo $data['SalarData']['LoanTaken']?></td>
                        <td><?php echo $data['SalarData']['LoanDed']?></td>
                        <td><?php echo $data['SalarData']['Incentive']?></td>
                        <td><?php echo $data['SalarData']['ExtraDayIncentive']?></td>
                        <td><?php echo $data['SalarData']['Arrear']?></td>
                        <td><?php echo $data['SalarData']['PLI']?></td>
                        <td><?php echo $data['SalarData']['NetSalary']?></td>
                        <td><?php echo $data['SalarData']['ESICCompany']?></td>
                        <td><?php echo $data['SalarData']['EPFCompany']?></td>
                        <td><?php echo $data['SalarData']['AdminChrg']?></td>
                        <td><?php echo $data['SalarData']['CTC']?></td>
                        <td><?php echo $data['SalarData']['SHSH']?></td>
                        <td><?php echo $data['SalarData']['MobileDedcution']?></td>
                        <td><?php echo $data['SalarData']['ShortCollection']?></td>
                        <td><?php echo $data['SalarData']['AssetRecovery']?></td>
                        <td><?php echo $data['SalarData']['Insurance']?></td>
                        <td><?php echo $data['SalarData']['ProTaxDeduction']?></td>
                        <td><?php echo $data['SalarData']['LeaveDeduction']?></td>
                        <td><?php echo $data['SalarData']['OtherDeduction']?></td>
                        <td><?php echo $data['SalarData']['OtherDeductionRemarks']?></td>
                        <td><?php echo $data['SalarData']['TotalDeduction']?></td>
                        <td><?php echo $data['SalarData']['SalDate']?></td>
                        <td><?php echo $Emp_Row['UAN']?></td>
                        <td><?php echo $data['SalarData']['EPFNo']?></td>
                        <td><?php echo $data['SalarData']['ESICNo']?></td>
                        <td><?php echo $data['SalarData']['ChequeNumber']?></td>
                        <td><?php echo $data['SalarData']['ChequeDate']?></td>
                        <td><?php echo $data['SalarData']['PrintDate']?></td>
                        <td><?php echo $data['SalarData']['LeftStatus']?></td>
                        <td><?php echo $data['SalarData']['TaxTotalGross']?></td>
                        <td><?php echo $data['SalarData']['TaxSection10']?></td>
                        <td><?php echo $data['SalarData']['TaxBalance']?></td>
                        <td><?php echo $data['SalarData']['TaxUnderHd']?></td>
                        <td><?php echo $data['SalarData']['DeductionUnder24']?></td>
                        <td><?php echo $data['SalarData']['TaxGrossTotal']?></td>
                        <td><?php echo $data['SalarData']['TaxAggofChapter6']?></td>
                        <td><?php echo $data['SalarData']['TotalIncome']?></td>
                        <td><?php echo $data['SalarData']['TaxOnTotalIncome']?></td>
                        <td><?php echo $data['SalarData']['EduCess']?></td>
                        <td><?php echo $data['SalarData']['TaxPayEduCess']?></td>
                        <td><?php echo $data['SalarData']['TaxDeductedTillPreviousMonth']?></td>
                        <td><?php echo $data['SalarData']['BalanceTax']?></td>
                        <td><?php echo $data['SalarData']['SalaryPaymentMode']?></td>
                        <td><?php echo $Emp_Row['AcNo'] !=""?"'".$Emp_Row['AcNo']:"Pending";?></td>
                        <td><?php echo $Emp_Row['IFSCCode']!=""?$Emp_Row['IFSCCode']:"Pending";?></td>
                        <td><?php echo $Emp_Row['AcBank']!=""?$Emp_Row['AcBank']:"Pending";?></td>
                        <td><?php echo $Emp_Row['AcBranch']!=""?$Emp_Row['AcBranch']:"Pending";?></td>
                        
                        <!--
                        <td><?php echo $data['SalarData']['SalaryBranch']?></td>
                        <td><?php echo $data['SalarData']['SalayDate']?></td>
                        -->
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
            
            $m=$_REQUEST['EmpMonth'];
            $y=$_REQUEST['EmpYear'];
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
        
            $this->SalarData->query("DELETE FROM `salary_data` WHERE Branch='{$_REQUEST['BranchName']}' AND DATE(SalayDate)='$SalayDay'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This branch salary process delete successfully.</span>'); 
            //$this->redirect(array('controller'=>'ProcessSalarys','action'=>'index')); 
            $url=$this->webroot.'ProcessSalarys?AX=MTA3';
            echo "<script>window.location.href = '$url';</script>";die;
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
        $y          =   $_REQUEST['EmpYear'];
        $m          =   $_REQUEST['EmpMonth'];
            
        $data = $this->ProcessAttendanceMaster->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('BranchName'=>$branchName,'ProcessMonth'=>"$y-$m",'FinializeStatus'=>'Yes')));

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
    
    
}
?>