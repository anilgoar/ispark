<?php
class MasattendancesController extends AppController 
{
    public $uses = array('Addbranch','User','Masattandance','Masjclrentry','CostCenterMaster','Design','Leave','MasJclrentrydata','DepartmentNameMaster','DesignationNameMaster','ProcessAttendanceMaster');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index','discardattandence','pendingemp','uploadattend','exportsalary','get_design','incentive',
                    'get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess','Savefile',
                    'showfile','discardsalary','discardincentive','getdept','delete_empty_emply','sendattendance');
            //else{$this->Auth->deny('index','get_report11','export1','save_status','exportsalary','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess');}
        }
    }
    
    public function uploadattend(){
        $this->layout = "home";
        $wrongData = array();
        $branch= $this->Masjclrentry->find('list',array('fields'=>array('BranchName')));
       
        $d2 = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
        $date['CreateDate']=date('Y-m-d');
        $datec=$d2[0]['0']['AttandDate'];
        $nextdate1=date_create($d2[0]['0']['AttandDate']);
   
        date_add($nextdate1,date_interval_create_from_date_string("1 day"));
        $nextdate=   date_format($nextdate1,"Y-m-d");
        if(!empty($d2[0]['0']['AttandDate']) && $date['CreateDate']!= $d2[0]['0']['AttandDate']){
            $date1=date_create($d2[0]['0']['AttandDate']);
            date_add($date1,date_interval_create_from_date_string("1 day"));
            $d2[0]['0']['AttandDate']= date_format($date1,"Y-m-d");
            $optdate[] =$d2[0]['0']['AttandDate'];
        }
        elseif($d2[0]['0']['AttandDate']==$date['CreateDate']){ 
            $optdate[]='';   
        }
        else{
            $optdate[$date['CreateDate']] =$date['CreateDate'];
        }
        foreach($optdate as $k=>$v){
            $optdat[$v] =date_format(date_create($v),"d-M-y");
        }
        $this->set('AttandDate',$optdat);
    
        $checkbarnch=false;
        $sdate=  $this->request->query['date'];
        if(!empty($sdate)){
        $d2 = $this->Masattandance->query("SELECT * from Attandence where  (EmpCode is null or EmpCode = '') and date(AttandDate)= '$sdate' and EmpStatus='InHouse'");    
            $this->set('data',$d2);      
        }
        
        
        if($this->request->is('POST')){
            $user = $this->Session->read('userid');
            $FileTye = $this->request->data['Masattendance']['file']['type'];
            $info = explode(".",$this->request->data['Masattendance']['file']['name']);
            $AttandDate = $this->request->data['Masattendance']['AttandDate'];
            $startdate=  date_format(date_create($info[0]),'Y-m-d');
            if($AttandDate==$startdate){
                if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                    $FilePath = $this->request->data['Masattendance']['file']['tmp_name'];
                    $files = fopen($FilePath, "r");
                    $dataArr = array();
                    $flag = false;
                    $checkNa=true;
                    $checkstatus=true;
                    $Leave = array();
                    $checkbarnch=true;
                
                    while($row = fgetcsv($files,5000,",")){
                        $data = array();
                        if(!empty($row)){
                      
                    if($flag)
                    {
                         if(in_array(strtoupper($row[2]), $branch))       
                            {

                            }
 else {
                      // print_r($row[2]);
     $checkbarnch=false;
 }
 if($row[0]=='NA'||$row[1]=='NA'||$row[2]=='NA'||$row[3]=='NA'||$row[4]=='NA'||$row[5]=='NA'){
  $checkNa=false;   
 }
 if($row[5]=='A'||$row[5]=='P'||$row[5]=='DH'||$row[5]=='HD')
 {
     
 }
 else{
     $checkstatus=false;
 }
 
 
  $Emp = $this->Masattandance->query("SELECT EmpCode,CostCenter from masjclrentry where BioCode = '{$row[0]}' limit 1");
  //print_r($Emp);
  $pendings = $this->Masattandance->query("SELECT * from Attandence where PendingStatus = 1 and  BioCode= '{$row[0]}' limit 1");
                                    If(empty($d2[0]['Attandence']['PendingStatus'])){
                                        $data['PendingStatus']= '0';
                                    }
                                       else{         $data['PendingStatus']= '1';
                                       }
                                                $data['EmpCode']=$Emp[0]['masjclrentry']['EmpCode'];
                                                $data['CostCenter']=$Emp[0]['masjclrentry']['CostCenter'];
						$data['BioCode'] = $row[0];
                                                $data['EmpName'] = $row[1];
                                                $data['BranchName'] = $row[2]; 
						$data['Intime'] = $row[3];
						$data['OutTime'] = $row[4];
                                                
                                                if(($row[5] =="A" && empty($row[3]) && !empty($row[4])) || ($row[5] =="A" && !empty($row[3]) && empty($row[4])) ){
                                                   $data['Status'] = "F"; 
                                                }
                                                else{
                                                   $data['Status'] = $row[5]; 
                                                }
                                              
						//$data['Status'] = $row[5];
                                                $data['AttandDate'] = $AttandDate;
						$data['ImportDate']=date('Y-m-d H:i:s');
                                                $data['EmpStatus']='InHouse';
                                                $dataArr[] = $data;
                                                
                    }
                    
                    else {$flag = true;}
                  }
                } 
                
                if(empty($datec)||$nextdate==$data['AttandDate']){
                    if($checkbarnch){
                        if($checkNa){
                        if($checkstatus){
                            
                           
                            
            $this->Masattandance->saveMany($dataArr);
            
            if(isset($this->request->data['mail']) && $this->request->data['mail']="mail"){
                //$this->sendattendance($AttandDate);
                $MailDataArr    =   $this->Masattandance->query("SELECT Id FROM `AttendanceMailMaster` WHERE AttandDate='$AttandDate'");
                $AttandId       =   $MailDataArr[0]['AttendanceMailMaster']['Id'];
                    
                if(empty($MailDataArr)){
                    $this->Masattandance->query("INSERT INTO AttendanceMailMaster (AttandDate)VALUES ('$AttandDate')"); 
                }
                else{
                    $this->Masattandance->query("UPDATE AttendanceMailMaster SET MailDate=NULL,MailStatus=NULL where Id='$AttandId'");
                }
            }
            
                            
		   
           
           			
			

		  
                   
                        
              
                $this->Session->setFlash('Data Imported Successfully');
                return $this->redirect(array('action' => 'uploadattend?date='.$data['AttandDate']));
                        }
                else {$this->Session->setFlash('Data Not Imported Due to Status Value');}
                        }
                        else {$this->Session->setFlash('Data Not Imported Due to NA Value');}
                    }
                   
                        else {$this->Session->setFlash('Data Not Imported Due to Branch Name Not Match');}
                   
               
            }
            
            else{
            $this->Session->setFlash('Please select Only Next Date for Attandance');
            }
            
            }
            else{
            $this->Session->setFlash('File Format not Valid.');
            }
              }
 else {
     $this->Session->setFlash('Please Select The Correct File for '.$AttandDate);  
 }
    }
    }
    
    
    
    
    
    public function sendattendance($AttandDate){
        App::uses('sendEmail', 'custom/Email');

        $AttDate=date('Y-m-d',  strtotime($AttandDate));
        $emailArr=$this->CostCenterMaster->find('all',array('fields'=>array('branch','cost_center','emailid','hremail'),'conditions'=>array('active'=>1)));
        //$emailArr=$this->CostCenterMaster->find('all',array('fields'=>array('branch','cost_center','emailid','hremail'),'conditions'=>array('active'=>1,'branch'=>'HEAD OFFICE')));
        
        $flag = true;
        $depArray=array();
        $html="";
        
        /*
        echo "<pre>";
        print_r($emailArr);die;
        echo "</pre>";
        */
        
        foreach($emailArr AS $r1){
            if($data['emailid'] !=""){
            
            $data       =   $r1['CostCenterMaster'];
            $autoid     =   $data['id'];
            $branch     =   $data['branch'];
            $costcenter =   $data['cost_center'];
            $dataX = explode(',',$data['emailid']);
            
            foreach($dataX as $d)
            {
                if($flag)
                {
                   $emailid    = $d;
                    $flag = false;
                }
                else
                {
                    if(!empty($d))
                    $AddTo[]    = $d;
                }
            }
            
            $dataX    = explode(',',$data['hremail']);
            foreach($dataX as $d)
            {
                if(!empty($d))    $CC[]    = $d;
            }
          
            
            
            $AttArr=$this->Masattandance->find('all',array('conditions'=>array('date(AttandDate)'=>$AttDate,'BranchName'=>$branch,'EmpCode !='=>NULL)));
            
            $html .='<table border="1" width="800" >';
            $html .='<tr>';
            $html .='<th>SrNo</th>';
            $html .='<th>EmpCode</th>';
            $html .='<th>BiometricCode</th>';
            $html .='<th>EmpName</th>';
            $html .='<th>Department</th>';
            $html .='<th>Designation</th>';
            $html .='<th>EmpLocation</th>';
            $html .='<th>InTime</th>';
            $html .='<th>OutTime</th>';
            $html .='<th>'.date('Md',  strtotime($AttDate)).'</th>';
            $html .='</tr>';
            
            
            
            
            foreach($AttArr as $r2){
                $AttData        =   $r2['Masattandance'];
      
                $Id             =   $AttData['Id'];
                $EmpCode        =   $AttData['EmpCode'];
                $BioCode        =   $AttData['BioCode'];
                $EmpName        =   $AttData['EmpName'];
                $Department     =   "";
                $Designation    =   "";
                $EmpLocation    =   $AttData['EmpStatus'];
                $Intime         =   $AttData['Intime'];
                $OutTime        =   $AttData['OutTime'];
                $Status         =   $AttData['Status'];
                
                //echo $costcenter."<br/>";
                
                $EmpDeta=$this->getempdetails($EmpCode,$costcenter);
                
                
                
                if(!empty($EmpDeta)){
                    $Department     =   $EmpDeta['Masjclrentry']['Dept'];
                    $Designation    =   $EmpDeta['Masjclrentry']['Desgination'];
                    $depArray[]     =   $EmpDeta['Masjclrentry']['Dept'];
                    $html .='<tr>';
                    $html .='<td>'.$Id.'</td>';
                    $html .='<td>'.$EmpCode.'</td>';
                    $html .='<td>'.$BioCode.'</td>';
                    $html .='<td>'.$EmpName.'</td>';
                    $html .='<td>'.$Department.'</td>';
                    $html .='<td>'.$Designation.'</td>';
                    $html .='<td>'.$EmpLocation.'</td>';
                    $html .='<td>'.$Intime.'</td>';
                    $html .='<td>'.$OutTime.'</td>';
                    $html .='<td>'.$Status.'</td>';
                    $html .='</tr>';
                }   
            }
            
            
            
                   
            
            
            $html .='</table>';
            
            if(!empty($depArray)){
            
            $text=$html;
            $EmailText ='';
            //$filename="/var/www/html/ispark/app/webroot/csv_file/emp_attendance_".$autoid.rand(100000,999999).date('d_m_Y_H_i_s')."_report.xls";
            $fn=str_replace(' ', '-', $branch)."_".str_replace('/', '-', $costcenter)."_";
            $filename="/var/www/html/ispark/app/webroot/csv_file/$fn".$autoid.rand(100000,999999).date('dmYHis').".xls";
            file_put_contents( $filename, $text); 

            $mail = new sendEmail();

            $Attachment=array( $filename);
            $Subject="ISpark-Employees Attendance - ".date('d M Y',strtotime($AttDate)); 
            $EmailText .="<table>";
            $EmailText .="<tr><td style=\"padding-left:12px;\">Dear Sir/Maa'm</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td style=\"padding-left:12px;\">Please find the attached file for the Employees attendance uploaded on ISPARK for ".date('d M Y',strtotime($AttDate)).". Please Check and if any attendance related discrpency found, kindly submitted Attendance Issue on Ispark in Human Resource Section.</td></tr>"; 
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>Note-Attendance Related Issue will only be acceptable before previous Two days. Please ensure employees attendance are up to date on ISPARK.</td></tr>";
            $EmailText .="<tr><td>Thanks & Regards</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>ISPARK</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>&nbsp;</td></tr>";
            $EmailText .="<tr><td>This is System generated mail need not to reply.</td></tr>";
            $EmailText .="</table>";
            
            //$EmailText .=$text;
            
            if($data['emailid'] !=""){
                $done = $mail-> send_with_file($emailid,$AddTo,$CC,$EmailText,$Subject,$Attachment); 
            }
            
            }
            unset($depArray); 
            unset($html);
            
            
        }
        
    }
        
    }
    
    
    public function getempdetails($EmpCode,$costcenter){
        return $this->Masjclrentry->find('first',array('fields'=>array('Dept','Desgination','CostCenter'),'conditions'=>array('EmpCode'=>$EmpCode,'CostCenter'=>$costcenter)));  
    }



    public function discardattandence(){
        $this->layout = "home";
        $d2 = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
        $nextdate1=date_create($d2[0][0]['AttandDate']);
        
        //print_r($nextdate1);die;
        
        date_sub($nextdate1,date_interval_create_from_date_string("15 day"));
        
        $nextdate=   date_format($nextdate1,"Y-m-d");
        
        //print_r($nextdate);die;
        
  
        $this->set('datay', $this->Masattandance->find('list',array('fields'=>array('AttandDate','AttandDate'),'conditions'=>array('date(AttandDate)<='."'".$d2[0][0]['AttandDate']."'",'date(AttandDate)>='."'".$nextdate."'"),'order'=>'date(AttandDate)')));   
    
     
     if($this->request->is('POST'))
        {
           
        $AttandDate     =   $this->request->data['upload']['AttandDate'];
        $ProcessDate    =   date('Y-m',strtotime(trim(addslashes($AttandDate))));
       
        $ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('ProcessMonth'=>$ProcessDate)));
        
        //$ProAttArr = $this->ProcessAttendanceMaster->find('count',array('conditions'=>array('ProcessMonth'=>'2018-01')));
        
        if($ProAttArr > 0){
            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This month attendance already process please contact with admin.</span>');
            $this->redirect(array('action'=>'discardattandence'));
        }
        else{
           if($this->Masattandance->deleteAll(array('AttandDate >='=>$AttandDate)))
           {
                $this->Session->setFlash('Attendance Discard Successfully');
                return $this->redirect(array('action' => 'discardattandence'));  
           }
           else{
               $this->Session->setFlash('Attendance Not Discard Please Try Again.');
               return $this->redirect(array('action' => 'discardattandence')); 
           }
       
        }
       
       
       
       
     }
            }
            
            
            
            
            
            
            
            public function index() {
                 $this->layout = "home";
            }
            
            
            
    public function pendingemp(){
        $this->layout="home"; 
        $branchName = $this->Session->read('branch_name');
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
         
        if($this->request->is('POST')){
            
            $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
            
            $Branch = $this->request->data['Show']['branch'];
            $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$Branch,'active'=>1))));
              
            $Biometric = $this->request->data['Show']['BiometricCode'];
            $Bio= $Biometric==''?"":"and BioCode='$Biometric'";
            $Bio1= $Biometric==''?"":"and BioCode='$Biometric'";
            
            $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
            $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];

            $d2 = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$Branch' and date(AttandDate)='$MaxAttandDate' and EmpCode is null $Bio group by BioCode");
            $this->set('data',$d2);
            
            
            $chk = $this->request->data['check']; 
            $chk1 = $this->request->data['check1']; 
            
           
            $BioArr=array();
            if(!empty($chk)){
                
                foreach ($chk as $c){
                    $BioArr[]=$c;
                    
                    $d21 = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$Branch' and date(AttandDate)='$MaxAttandDate' and  EmpCode is null and BioCode='$c'");
                    $Data['BioCode']=$c;
                    $Data['CostCenter'] = $this->request->data['Show']['CostCenter'];
                    $Data['DepartMent'] = $this->request->data['Show']['Department'];
                    $Data['Degination']= $this->request->data['Show']['Designation'];
                    $Data['BranchName']= $Branch;
                    
                    if(in_array($c,$chk1)){
                        $Data['TrainningStatus']= "Yes";
                    }
                    else{
                        $Data['TrainningStatus']= "No"; 
                    }
                    
                    $Data['EmpName']= $d21[0]['Attandence']['EmpName'];
                    $Data['EntryDate']= date('Y-m-d H:i:s');
                    
                    $dataArr[]=$Data;
                    $dataArr1 =array('PendingStatus'=>1);
    
                    //$this->Masattandance->updateAll($dataArr1,array('BioCode'=>$Data['BioCode']));
                }

                if($this->MasJclrentrydata->saveMany($dataArr)){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;margin-left:5px;" >Data save successfully.</span>');  
                    //return $this->redirect(array('controller'=>'Masattendances','action' => 'pendingemp'));
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;margin-left:5px;" >Data not save successfully please try again later.</span>');   
                }
            }
            
            //$d21 = $this->Masattandance->query("SELECT * from Attandence a1 inner join mas_Jclrentrydata mj on a1.BioCode=mj.BioCode where a1.BranchName= '$Branch' and a1.EmpCode is null $Bio1 group by a1.BioCode");  
            //$d21 = $this->Masattandance->query("SELECT * from Attandence a1 inner join mas_Jclrentrydata mj on a1.BioCode=mj.BioCode where a1.BranchName= '$Branch' and a1.EmpCode is null $Bio1 group by a1.BioCode");  
            
            $d21 = $this->MasJclrentrydata->query("SELECT * from mas_Jclrentrydata where BranchName= '$Branch' $Bio1");
            $BioCodeArr=array();
            foreach($d21 as $row){
                $BioCodeArr[]=$row['mas_Jclrentrydata']['BioCode'];
            }

            $this->set('data1',$d21);
            $this->set('BioArr',$BioArr);
            $this->set('BioCodeArr',$BioCodeArr);
        }
    }
    
    public function delete_empty_emply(){
        $this->Masattandance->query("DELETE FROM mas_Jclrentrydata WHERE Id='{$_REQUEST['Id']}'"); 
        $this->Masattandance->query("UPDATE `Attandence` SET PendingStatus='0' WHERE BioCode='{$_REQUEST['BioCode']}'");
        return $this->redirect(array('controller'=>'Masattendances','action' =>'pendingemp'));    
    }
    
    
    public function getdept(){
        if(isset($_REQUEST['Department'])){ 
            $state=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation'),'conditions'=>array('Department'=>$_REQUEST['Department'],'Status'=>1),'order'=>'Designation','group'=>'Designation'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                echo "<option value='$val'>$val</option>";
            }      
        }
        die;
    }



public function get_design()
    {
        $this->layout='ajax';
        $val = $this->request->data['val'];
        
       $this->set('Desig',$this->Design->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Department'=>$val))));
       
    }
    } 
?>