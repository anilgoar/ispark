<?php
class EmpcodeGeneratesController extends AppController {
    public $uses = array('Addbranch','MasJclrMaster','Masattandance','OdApplyMaster','NewjclrMaster','MasJclrentrydata');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','generatesupportstaff');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
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
			
            $branch_name=$this->request->data['EmpcodeGenerates']['branch_name'];
            
            $conditoin=array('Approve'=>'Yes','Approve1'=>'Yes');
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
             
            $MaxId=1;
            if(isset($this->request->data['Submit'])){
                if(isset($this->request->data['check'])){
                    $OdIdArr=$this->request->data['check'];
                    foreach ($OdIdArr as $Id){
                        /*
                        $qry="INSERT INTO `masjclrentry`(`EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`) 
                        SELECT `EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc` FROM `NewJclrMaster` WHERE id='$Id';";
                        */
                        
                        $qry="INSERT INTO `masjclrentry`(`EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Interview_Id`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`,`Type_Of_Employee`) 
                        SELECT `EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,NOW(),NOW(),`DownloadCount`,`Interview_Id`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`,`Type_Of_Employee` FROM `NewJclrMaster` WHERE id='$Id';";
                        
                    
                        
                        $data=$this->NewjclrMaster->find('first',array('conditions'=>array('id'=>$Id)));
                        
                        
                        $CompArr=$this->NewjclrMaster->query("SELECT company_name FROM `cost_master` WHERE cost_center='{$data['NewjclrMaster']['CostCenter']}' limit 1"); 
                        $CompanyName=$CompArr[0]['cost_master']['company_name'];
                        
                        $MAXENAR=$this->NewjclrMaster->query("SELECT MAX(CONVERT(EmpCodeNo,UNSIGNED INTEGER)) AS MAXEN FROM masjclrentry Where $MaxId");       
                        $MAXN=$MAXENAR[0][0]['MAXEN'];
                        $NEWMAXN=$MAXN+1;
                        $MaxId++;
                        
                        if($CompanyName =="Mas Callnet India Pvt Ltd"){
                            if($data['NewjclrMaster']['EmpType'] =="ONROLL"){
                                $EmpCode="MAS".$NEWMAXN;   
                            }
                            else if($data['NewjclrMaster']['EmpType'] =="MGMT. TRAINEE"){
                               $EmpCode=$NEWMAXN."C"; 
                            }   
                        }
                        else if($CompanyName =="IDC"){
                            if($data['NewjclrMaster']['EmpType'] =="ONROLL"){
                                $EmpCode="IDC".$NEWMAXN;   
                            }
                            else if($data['NewjclrMaster']['EmpType'] =="MGMT. TRAINEE"){
                               $EmpCode="IDC".$NEWMAXN."C"; 
                            }   
                        }
                        
                        $TraningStatus=$this->MasJclrentrydata->find('first',array('fields'=>array('TrainningStatus'),'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                        if($TraningStatus['MasJclrentrydata']['TrainningStatus'] =="No"){
                            $AttendArray=$this->Masattandance->find('first',array('fields'=>"MIN(AttandDate) as MINATTEND",'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                            $JoinDate=$AttendArray[0]['MINATTEND'];
                        }
                        else{
                          $AttendArray=$this->Masattandance->find('first',array('fields'=>"MIN(AttandDate) as MINATTEND",'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                          $JoinDate=$AttendArray[0]['MINATTEND'];
                        }
                       
                        if($data['NewjclrMaster']['EmpLocation'] =="InHouse"){
                            if($TraningStatus['MasJclrentrydata']['TrainningStatus'] =="No"){
                                $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'",'DOJ'=>"'".$JoinDate."'"),array('id'=>$Id));
                            }
                            else{
                                $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'"),array('id'=>$Id)); 
                            }
                        }
                        else{
                          $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'"),array('id'=>$Id));  
                        }
                        
                        $this->NewjclrMaster->query($qry);
                        
                        $branchName =   $data['NewjclrMaster']['BranchName'];
                        $BioCode    =   $data['NewjclrMaster']['BioCode'];
                        $CertifiedEmployee=$this->MasJclrentrydata->find('first',array('fields'=>array('CertifiedDate'),'conditions'=>array('BranchName'=>$branchName,'BioCode'=>$BioCode,'TrainningStatus'=>'Yes','CertifiedDate !='=>NULL)));
                        if(!empty($CertifiedEmployee)){
                            $CertifiedDate=$CertifiedEmployee['MasJclrentrydata']['CertifiedDate'];
                            $this->Masattandance->query("DELETE FROM Attandence WHERE BioCode='$BioCode' AND BranchName='$branchName' AND DATE(AttandDate) <= '$CertifiedDate'");
                        }
                        
                        $this->Masattandance->query("DELETE FROM mas_Jclrentrydata WHERE BioCode='{$data['NewjclrMaster']['BioCode']}'"); 
                        $this->Masattandance->query("UPDATE `Attandence` SET PendingStatus='0' WHERE BioCode='{$data['NewjclrMaster']['BioCode']}'");
                        
                        
                        $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
                        $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
                        
                        $this->Masattandance->query("UPDATE `Attandence` SET EmpCode='$EmpCode' WHERE BioCode='{$data['NewjclrMaster']['BioCode']}' and date(AttandDate) between '{$data['NewjclrMaster']['DOJ']}' and '$MaxAttandDate'");
                        
                        
                        $this->NewjclrMaster->query("DELETE FROM NewJclrMaster WHERE id='$Id'");
						
						if($data['NewjclrMaster']['EmpType'] =="ONROLL"){
							
							$home_branch_array	=	array(
													'RAJASTHAN'=>'JAIPUR',
													'UTTAR PRADESH'=>'NOIDA',
													'GUJARAT'=>'AHMEDABAD-JALDARSHAN',
												);
												
							$home_CTC 	=   $data['NewjclrMaster']['CTC'];
												
							$home_package	=	$this->NewjclrMaster->query("SELECT StateName FROM `mas_packagemaster_state_wise` WHERE CTC < $home_CTC ORDER BY CTC DESC LIMIT 1;");
												
							$home_branch	= 	!empty($home_package)?$home_branch_array[$home_package[0]['mas_packagemaster_state_wise']['StateName']]:$branchName;
							
							$this->NewjclrMaster->query("UPDATE `masjclrentry` SET Home_Branch='$home_branch' WHERE EmpCode='$EmpCode'");
							
						}
						else{
							$home_branch	= 	$branchName;
							$this->NewjclrMaster->query("UPDATE `masjclrentry` SET Home_Branch='$home_branch' WHERE EmpCode='$EmpCode'");
						}
						
                        
                           
                    }
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;margin-left:15px;" >Emp code generate successfully.</span>'); 
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;margin-left:15px;" >Please select record to create emp code.</span>'); 
                }  
            }
            $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>$conditoin)));  
        }     
    }
    
    public function generatesupportstaff(){
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
            $branch_name=$this->request->data['EmpcodeGenerates']['branch_name'];
            
            $conditoin=array('Approve'=>'Yes','Approve1'=>'Yes');
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            
            $MaxId=1;
            if(isset($this->request->data['Submit'])){
                if(isset($this->request->data['check'])){
                    $OdIdArr=$this->request->data['check'];
                    foreach ($OdIdArr as $Id){
                        /*
                        $qry="INSERT INTO `masjclrentry`(`EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`) 
                        SELECT `EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc` FROM `NewJclrMaster` WHERE id='$Id';";
                        */
                        
                        $qry="INSERT INTO `masjclrentry`(`EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,`EntryDate`,`CreateDate`,`DownloadCount`,`Interview_Id`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`,`Type_Of_Employee`) 
                        SELECT `EmpType`,`EmpCode`,`EmpCodeNo`,`userid`,`BranchName`,`SubLocation`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`ParentType`,`Father`,`Husband`,`Gendar`,`BloodGruop`,`NomineeName`,`NomineeDob`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`StateId`,`State1`,`State1Id`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,`LandLine`,`LandLine1`,`EmailId`,`documentDone`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Stream`,`Process`,`Profile`,`ClientName`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,`CancelledChequeImage`,`AcNo`,`AcBank`,`AcBranch`,`dlNo`,`EmpCodeDate`,`Pwd`,`Age`,`bs`,`hra`,`conv`,`da`,`portf`,`ma`,`lta`,`mob`,`sa`,`oa`,`NewEpfNo`,`pfelig`,`esielig`,`moballow`,`mno`,`portfolio`,`nom1`,`nom2`,`dispens`,`remarks`,`EpfDate`,`EmpFor`,`UpdatedBy`,`IFSCCode`,`AccHolder`,`AccType`,`OfferNo`,`package`,`Bonus`,`Gross`,`NetInhand`,`ESIC`,`EPF`,`EPFCO`,`ESICCO`,`Gratuity`,`ProfessionalTax`,`AccountFlag`,`AppointPrintDate`,`PayMode`,`AcValidationDate`,`AcValidatedBy`,`AcRejectionRemarks`,`AdminCharges`,`SourceType`,`BoxFileNo`,`RType`,`SalaryPaymentMode`,`ESICNo`,`Approve`,`ApproveDate`,`Approve1`,`ApproveDate1`,`Status`,`ResignationDate`,`AuthenticationCode`,`LeftReason`,`UAN`,`PLI`,`AssignDate`,`lastUpdated`,NOW(),NOW(),`DownloadCount`,`Interview_Id`,`Billable_Status`,`Qualification_Details`,`Passed_Out_Year`,`Passed_Out_State_Id`,`Passed_Out_State`,`Passed_Out_City`,`Passed_Out_Percent`,`Family_Annual_Income`,`Count_Of_Dependents`,`Reporting_Manager_Name`,`Reporting_Manager_Mobile_No`,`Experience`,`Experience_Year`,`Experience_Doc`,`Type_Of_Employee` FROM `NewJclrMaster` WHERE id='$Id';";
                        
                        $data=$this->NewjclrMaster->find('first',array('conditions'=>array('id'=>$Id)));
                        
                        $CompArr=$this->NewjclrMaster->query("SELECT company_name FROM `cost_master` WHERE cost_center='{$data['NewjclrMaster']['CostCenter']}' limit 1"); 
                        $CompanyName=$CompArr[0]['cost_master']['company_name'];
                        
                        $MAXENAR=$this->NewjclrMaster->query("SELECT MAX(CONVERT(EmpCodeNo,UNSIGNED INTEGER)) AS MAXEN FROM masjclrentry Where $MaxId");       
                        $MAXN=$MAXENAR[0][0]['MAXEN'];
                        $NEWMAXN=$MAXN+1;
                        $MaxId++;
                        
                        if($CompanyName =="Mas Callnet India Pvt Ltd"){
                            if($data['NewjclrMaster']['EmpType'] =="ONROLL"){
                                $EmpCode="MAS".$NEWMAXN;   
                            }
                            else if($data['NewjclrMaster']['EmpType'] =="MGMT. TRAINEE"){
                               $EmpCode=$NEWMAXN."C"; 
                            }   
                        }
                        else if($CompanyName =="IDC"){
                            if($data['NewjclrMaster']['EmpType'] =="ONROLL"){
                                $EmpCode="IDC".$NEWMAXN;   
                            }
                            else if($data['NewjclrMaster']['EmpType'] =="MGMT. TRAINEE"){
                               $EmpCode="IDC".$NEWMAXN."C"; 
                            }   
                        }

                        $TraningStatus=$this->MasJclrentrydata->find('first',array('fields'=>array('TrainningStatus'),'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                        if($TraningStatus['MasJclrentrydata']['TrainningStatus'] =="No"){
                            $AttendArray=$this->Masattandance->find('first',array('fields'=>"MIN(AttandDate) as MINATTEND",'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                            $JoinDate=$AttendArray[0]['MINATTEND'];
                        }
                        else{
                          $AttendArray=$this->Masattandance->find('first',array('fields'=>"MIN(AttandDate) as MINATTEND",'conditions'=>array('BioCode'=>$data['NewjclrMaster']['BioCode']))); 
                          $JoinDate=$AttendArray[0]['MINATTEND'];
                        }
                       
                        if($data['NewjclrMaster']['EmpLocation'] =="InHouse"){
                            if($TraningStatus['MasJclrentrydata']['TrainningStatus'] =="No"){
                                $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'",'DOJ'=>"'".$JoinDate."'"),array('id'=>$Id));
                            }
                            else{
                                $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'"),array('id'=>$Id)); 
                            }
                        }
                        else{
                          $this->NewjclrMaster->updateAll(array('EmpCode'=>"'".$EmpCode."'",'EmpCodeNo'=>"'".$NEWMAXN."'"),array('id'=>$Id));  
                        }
                        
                        $this->NewjclrMaster->query($qry);
                        
                        $branchName =   $data['NewjclrMaster']['BranchName'];
                        $BioCode    =   $data['NewjclrMaster']['BioCode'];
                        $CertifiedEmployee=$this->MasJclrentrydata->find('first',array('fields'=>array('CertifiedDate'),'conditions'=>array('BranchName'=>$branchName,'BioCode'=>$BioCode,'TrainningStatus'=>'Yes','CertifiedDate !='=>NULL)));
                        if(!empty($CertifiedEmployee)){
                            $CertifiedDate=$CertifiedEmployee['MasJclrentrydata']['CertifiedDate'];
                            $this->Masattandance->query("DELETE FROM Attandence WHERE BioCode='$BioCode' AND BranchName='$branchName' AND DATE(AttandDate) <= '$CertifiedDate'");
                        }
                        
                        $this->Masattandance->query("DELETE FROM mas_Jclrentrydata WHERE BioCode='{$data['NewjclrMaster']['BioCode']}'"); 
                        $this->Masattandance->query("UPDATE `Attandence` SET PendingStatus='0' WHERE BioCode='{$data['NewjclrMaster']['BioCode']}'");
                        
                        $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
                        $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
                        
                        $this->Masattandance->query("UPDATE `Attandence` SET EmpCode='$EmpCode' WHERE BioCode='{$data['NewjclrMaster']['BioCode']}' and date(AttandDate) between '{$data['NewjclrMaster']['DOJ']}' and '$MaxAttandDate'");

                        $this->NewjclrMaster->query("DELETE FROM NewJclrMaster WHERE id='$Id'"); 
                    }
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;margin-left:15px;" >Emp code generate successfully.</span>'); 
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;margin-left:15px;" >Please select record to create emp code.</span>'); 
                }  
            }
            $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>$conditoin)));  
        }     
    }
    
}

?>