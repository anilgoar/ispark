<?php
class HrVisitorsController extends AppController {
    public $uses = array('Addbranch','InterviewMaster','VisitorMaster','InterviewQuestionmaster',
        'interviewquestion','maspackage','BandNameMaster','DesignationNameMaster','masband','StateMaster',
        'DepartmentNameMaster','CostCenterMaster','NewjclrMaster','LanguageMaster','Masjclrentry','TrainerMaster',
        'HRVisitorRecruiter','HRLogin','HRMeetPurpose');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index','deleteinterview','interviewleveldetails','interviewquestion','deletequestion','getquestion','viewvisitor',
            'interviewreport','show_visitor','export_visitor','getdesg','deletevisitor','deletehremp','getband','recruiter',
            'hrapproval','hrupdate','empdetails','get_emp','gettrainerdata','getcostcenter','getcostcenteredit','getprocessname',
                'hr_recruiter_add','hr_recruiter_edit','hr_mobile_user_add','hr_mobile_user_edit','hr_add_purpose_to_meet',
                'hr_delete_purpose_to_meet','resendinterview'
        );
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        $this->set('branchName',$BranchArray);
        
        $this->set('data',$this->InterviewMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Recruiter_Status'=>NULL))));
    
        if($this->request->is('POST')){
            $request        =   $this->request->data;
              
            $Branch         =   $request['HrVisitors']['branch_name'];
            $Interview_Id   =   $request['Interview_Id'];
            
            $this->InterviewMaster->query("UPDATE `Interview_master` SET BranchName='$Branch' WHERE Interview_Id='$Interview_Id'");
            $this->redirect(array('action'=>'index'));
        }
        
    }
    
    public function resendinterview(){
        if(isset($_REQUEST['id'])){

            $Id             =   $_REQUEST['id'];
            $Mobile_No      =   $_REQUEST['no'];
            $Url            =   'Dear Candidate please click on below web-http://'.$_SERVER['HTTP_HOST'].'/hrvisitors/empdetails.php?url='.base64_encode($Id);
            
            $num['ReceiverNumber'] = $Mobile_No;
            $num['SmsText'] = $Url;
            $res = $this->send_sms($num); 
            $this->redirect(array("action"=>'index'));
        }
    }
    
    public function send_sms($smsdata){
      $ReceiverNumber=$smsdata['ReceiverNumber'];


      $SmsText=$smsdata['SmsText'];

      $postdata = http_build_query(
       array(
        'uname'=>'MasCall',
        'pass'=>'M@sCaLl@234',
        'send'=>'Ispark',
        'dest'=>$ReceiverNumber,
        'msg'=>$SmsText
       )
      );

      $opts = array('http' =>
       array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
       )
      );

      $context  = stream_context_create($opts);
      return $result = file_get_contents('http://www.unicel.in/SendSMS/sendmsg.php', false, $context);
     }
    
    


    public function interviewleveldetails(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        
        /*
        $branchName =   $this->Session->read('branch_name');
        $fields     =   array('Interview_Round_1','Interview_Round_2','Interview_Round_3');
        
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $data   =   $this->InterviewMaster->find('all',array('fields'=>$fields));   
        }
        else{
            $data    =   $this->InterviewMaster->find('all',array('fields'=>$fields,'conditions'=>$conditions=array('BranchName'=>$branchName)));
        }
        */
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            $Branch         =   $request['HrVisitors']['branch_name'];
            $Visitor_Id     =   $request['Visitor_Id'];
            $Submit         =   $request['Submit'];
            
            $WhereDate      =   "DATE(Create_Date) >='$FromDate' AND DATE(Create_Date) <='$ToDate'";
            $WhereBranch    =   $Branch !="ALL"?"AND BranchName='$Branch'":"";
            $conditoin      =   array("$WhereDate $WhereBranch");
            
            $data           =   $this->InterviewMaster->find('all',array('conditions'=>$conditoin));
            
            $Interview      =   array();
            foreach($data as $rowArr){
                
                $CanName              =     $rowArr['InterviewMaster']['Name'];
                $CanMobile            =     $rowArr['InterviewMaster']['Mobile_No'];
                
                $Interview_Round      =   $rowArr['InterviewMaster']['Interview_Round'];
                $Interview_Round_1    =   $rowArr['InterviewMaster']['Interview_Round_1'];
                $Interview_Round_2    =   $rowArr['InterviewMaster']['Interview_Round_2'];
                $Interview_Round_3    =   $rowArr['InterviewMaster']['Interview_Round_3'];

                $UserData1   =   $this->InterviewMaster->query("SELECT emp_name,email,branch_name FROM `tbl_user` WHERE id='$Interview_Round_1'");
                $row1        =   $UserData1[0]['tbl_user'];

                $UserData2   =   $this->InterviewMaster->query("SELECT emp_name,email,branch_name FROM `tbl_user` WHERE id='$Interview_Round_2'");
                $row2        =   $UserData2[0]['tbl_user'];

                $UserData3   =   $this->InterviewMaster->query("SELECT emp_name,email,branch_name FROM `tbl_user` WHERE id='$Interview_Round_3'");
                $row3        =   $UserData3[0]['tbl_user'];

                if($Interview_Round_1 !=""){
                    $Interview  []=   array(
                    'Interview_Round_1'=>array('BranchName'=>$row1['branch_name'],'EmpCode'=>'','CanName'=>$CanName,'CanMobile'=>$CanMobile,'EmpName'=>$row1['emp_name'],'MobileNo'=>'','EmialId'=>$row1['email']),
                    'Interview_Round_2'=>array('BranchName'=>$row2['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row2['emp_name'],'MobileNo'=>'','EmialId'=>$row2['email']),
                    'Interview_Round_3'=>array('BranchName'=>$row3['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row3['emp_name'],'MobileNo'=>'','EmialId'=>$row3['email']),
                    );  
                }
                else if($Interview_Round_2 !=""){
                    $Interview  []=   array(
                    'Interview_Round_1'=>array('BranchName'=>$row1['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row1['emp_name'],'MobileNo'=>'','EmialId'=>$row1['email']),
                    'Interview_Round_2'=>array('BranchName'=>$row2['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row2['emp_name'],'MobileNo'=>'','EmialId'=>$row2['email']),
                    'Interview_Round_3'=>array('BranchName'=>$row3['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row3['emp_name'],'MobileNo'=>'','EmialId'=>$row3['email']),
                    );
                }
                else if($Interview_Round_3 !=""){
                    $Interview  []=   array(
                    'Interview_Round_1'=>array('BranchName'=>$row1['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row1['emp_name'],'MobileNo'=>'','EmialId'=>$row1['email']),
                    'Interview_Round_2'=>array('BranchName'=>$row2['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row2['emp_name'],'MobileNo'=>'','EmialId'=>$row2['email']),
                    'Interview_Round_3'=>array('BranchName'=>$row3['branch_name'],'EmpCode'=>'','CanName'=>'','CanMobile'=>'','EmpName'=>$row3['emp_name'],'MobileNo'=>'','EmialId'=>$row3['email']),
                    );
                }
            }

            if($Submit =="View"){
                $this->set('data',$Interview);
                $this->set('FromDate',$request['FromDate']);
                $this->set('ToDate',$request['ToDate']);
            }/*
            else if($Submit =="Delete"){
                foreach($Visitor_Id as $id){
                    $this->VisitorMaster->query("DELETE FROM `hr_visitor` WHERE Visitor_Id='$id'");
                }
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
                $this->redirect(array('action'=>'viewvisitor'));
            }*/
            else if($Submit =="Export"){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=interview_level_export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table border="1">     
                <thead>
                    <tr>
                        <th colspan="6">Interview level 1 Details</th>
                        <th colspan="3">Interview level 2 Details</th>
                        <th colspan="3">Interview level 3 Details</th>
                    </tr>
                    <tr>
                        <th>Branches</th>
                        <!--
                        <th>Emp Code</th>
                        -->
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>User ID</th>
                        <th>Candidate Name</th>
                        <th>Candidate Mob Number</th>
                        <!--
                        <th>Branches</th>
                        
                        <th>Emp Code</th>
                        -->
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>User ID</th>
                        <!--
                        <th>Branches</th>
                        
                        <th>Emp Code</th>
                        -->
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>User ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($Interview as $row){?>
                    <tr>
                        <td><?php echo $row['Interview_Round_1']['BranchName'];?></td>
                        <!--
                        <td><?php echo $row['Interview_Round_1']['EmpCode'];?></td>
                        -->
                        <td><?php echo $row['Interview_Round_1']['EmpName'];?></td>
                        <td><?php echo $row['Interview_Round_1']['MobileNo'];?></td>
                        <td><?php echo $row['Interview_Round_1']['EmialId'];?></td>
                        <td><?php echo $row['Interview_Round_1']['CanName'];?></td>
                        <td><?php echo $row['Interview_Round_1']['CanMobile'];?></td>
                        <!--
                        <td><?php echo $row['Interview_Round_2']['BranchName'];?></td>
                        
                        <td><?php echo $row['Interview_Round_2']['EmpCode'];?></td>
                        -->
                        <td><?php echo $row['Interview_Round_2']['EmpName'];?></td>
                        <td><?php echo $row['Interview_Round_2']['MobileNo'];?></td>
                        <td><?php echo $row['Interview_Round_2']['EmialId'];?></td>
                        
                        
                        <!--
                        <td><?php echo $row['Interview_Round_3']['BranchName'];?></td>
                        
                        <td><?php echo $row['Interview_Round_3']['EmpCode'];?></td>
                        -->
                        <td><?php echo $row['Interview_Round_3']['EmpName'];?></td>
                        <td><?php echo $row['Interview_Round_3']['MobileNo'];?></td>
                        <td><?php echo $row['Interview_Round_3']['EmialId'];?></td>
                        
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
            }    
        }
 
          
    }
    
    public function interviewreport(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            $Branch         =   $request['HrVisitors']['branch_name'];
            $Visitor_Id     =   $request['Visitor_Id'];
            $Submit         =   $request['Submit'];
            
            $WhereDate      =   "DATE(Create_Date) >='$FromDate' AND DATE(Create_Date) <='$ToDate'";
            $WhereBranch    =   $Branch !="ALL"?"AND BranchName='$Branch'":"";
            $conditoin      =   array("$WhereDate $WhereBranch");
            
            $data           =   $this->InterviewMaster->find('all',array('conditions'=>$conditoin));
            
            if($Submit =="View"){
                $this->set('data',$data);
                $this->set('FromDate',$request['FromDate']);
                $this->set('ToDate',$request['ToDate']);
            }/*
            else if($Submit =="Delete"){
                foreach($Visitor_Id as $id){
                    $this->VisitorMaster->query("DELETE FROM `hr_visitor` WHERE Visitor_Id='$id'");
                }
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
                $this->redirect(array('action'=>'viewvisitor'));
            }*/
            else if($Submit =="Export"){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=interview_export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table border="1">     
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>BranchName</th>
                        <th>Source</th>
                        <th>Name</th>
                         <th>Designation</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                         <th>Date</th>
                        <th>Candidate Salary EXP</th>
                        <th>Mas salary offer CTC</th>
                        <th>Mas salary offer Net</th>
                        <th>Candidate Feedback</th>
                        <th>Read Language</th>
                        <th>Write Language</th>
                        <th>Speak Language</th>
						<th>Smart phone ( name of the brand)</th>
						<th>RAM – 3GB and above</th>
						<th>Laptop Model name</th>
						<th>Laptop Configuration</th>
						<th>Laptop Serial number</th>
						<th>Broadband service provider ( please furnish the last bill) </th>
						<th>Speed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $row['InterviewMaster']['BranchName'];?></td>
                        <td><?php echo $row['InterviewMaster']['Source_Of_Information'];?></td>
                        <td><?php echo $row['InterviewMaster']['Name'];?></td>
                        <td><?php echo $row['InterviewMaster']['Job_Position'];?></td>
                        <td><?php echo $row['InterviewMaster']['Mobile_No'];?></td>
                        <td><?php echo $row['InterviewMaster']['Address'];?></td>
                        <td ><?php echo $row['InterviewMaster']['Create_Date']!=""?date("d-M-Y",strtotime($row['InterviewMaster']['Create_Date'])):''?></td>
                        
                        <td><?php echo $row['InterviewMaster']['Candidate_Salar_Exp'];?></td>
                        <td><?php echo $row['InterviewMaster']['Salary_Offer_CTC'];?></td>
                        <td><?php echo $row['InterviewMaster']['Salary_Offer_Net'];?></td>
                        <td><?php echo $row['InterviewMaster']['Candidate_Feedback'];?></td>
                        <td><?php echo $row['InterviewMaster']['Read'];?></td>
                        <td><?php echo $row['InterviewMaster']['Write'];?></td>
                        <td><?php echo $row['InterviewMaster']['Speak'];?></td>
						<td><?php echo $row['InterviewMaster']['Phone_Brand_Name'];?></td>
						<td><?php echo $row['InterviewMaster']['Phone_Ram_Size'];?></td>
						<td><?php echo $row['InterviewMaster']['Laptop_Model_Name'];?></td>
						<td><?php echo $row['InterviewMaster']['Laptop_Configuration'];?></td>
						<td><?php echo $row['InterviewMaster']['Laptop_Serial_Number'];?></td>
						<td><?php echo $row['InterviewMaster']['Internet_Service_Provider'];?></td>
						<td><?php echo $row['InterviewMaster']['Internet_Speed'];?></td>
                        
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
            }    
        }
 
          
    }
    
    public function deleteinterview(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            $this->InterviewMaster->query("DELETE FROM `Interview_master` WHERE Interview_Id='$id'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
            $this->redirect(array('action'=>'index'));
        }
     
    }
    
    public  function recruiter(){
        $this->layout='home';
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
        
        if(isset($_REQUEST['url']) && $_REQUEST['url'] !=""){
            $row=$this->InterviewMaster->find('first',array('conditions'=>array('Interview_Id'=>base64_decode($_REQUEST['url']))));
            $this->set('data',$row['InterviewMaster']);  
            
            $this->set('language',$this->LanguageMaster->find('all'));  
        }
        
        if($this->request->is('POST')){
            $EmpCode                =   $this->Session->read('userid');
            $request                =   $this->request->data;
            
            $Interview_Id           =   $request['Interview_Id'];
            $Read                   =   implode(",", $request['Read']);
            $Write                  =   implode(",", $request['Write']);
            $Speak                  =   implode(",", $request['Speak']);
			
			$Phone_Brand_Name           =   $request['Phone_Brand_Name'];
			$Phone_Ram_Size           =   $request['Phone_Ram_Size'];
			$Laptop_Model_Name           =   $request['Laptop_Model_Name'];
			$Laptop_Configuration           =   $request['Laptop_Configuration'];
			$Laptop_Serial_Number           =   $request['Laptop_Serial_Number'];
			$Internet_Service_Provider           =   $request['Internet_Service_Provider'];
			$Internet_Speed           =   $request['Internet_Speed'];
			

            $Job_Position           =   $request['Job_Position'];
            $Interview_Round        =   $request['Interview_Round'];
            $Candidate_Salar_Exp    =   $request['Candidate_Salar_Exp'];
            $Salary_Offer_CTC       =   $request['Salary_Offer_CTC'];
            $Salary_Offer_Net       =   $request['Salary_Offer_Net'];
            $Candidate_Feedback     =   $request['Candidate_Feedback'];
            $Next_Interview_Date    =   date("Y-m-d",strtotime($request['Next_Interview_Date']));
            $SubmitType             =   $request['Submit'];
            $Recruiter_Save         =   "save";
            
            $data=array(
                'Read'=>"'".$Read."'",
                'Write'=>"'".$Write."'",
                'Speak'=>"'".$Speak."'",
				'Phone_Brand_Name'=>"'".$Phone_Brand_Name."'",
				'Phone_Ram_Size'=>"'".$Phone_Ram_Size."'",
				'Laptop_Model_Name'=>"'".$Laptop_Model_Name."'",
				'Laptop_Configuration'=>"'".$Laptop_Configuration."'",
				'Laptop_Serial_Number'=>"'".$Laptop_Serial_Number."'",
				'Internet_Service_Provider'=>"'".$Internet_Service_Provider."'",
				'Internet_Speed'=>"'".$Internet_Speed."'",
                'Job_Position'=>"'".$Job_Position."'",
                'Interview_Round'=>"'".$Interview_Round."'",
                'Candidate_Salar_Exp'=>"'".$Candidate_Salar_Exp."'",
                'Salary_Offer_CTC'=>"'".$Salary_Offer_CTC."'",
                'Salary_Offer_Net'=>"'".$Salary_Offer_Net."'",
                'Candidate_Feedback'=>"'".$Candidate_Feedback."'",
                'Next_Interview_Date'=>"'".$Next_Interview_Date."'",
                );
				
            $Interview_Round =='1'?$data['Interview_Total_Round']="'".$Interview_Round."'":"";
            $Interview_Round =='1'?$data['Interview_Round_1']="'".$EmpCode."'":"";
            $Interview_Round =='2'?$data['Interview_Round_2']="'".$EmpCode."'":"";
            $Interview_Round =='3'?$data['Interview_Round_3']="'".$EmpCode."'":"";
            
            $SubmitType=="Save"?$data['Recruiter_Status']="'".$Recruiter_Save."'":"";
                 
            $this->InterviewMaster->updateAll($data,array('Interview_Id'=>$Interview_Id));
            
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This details update successfully.</span>');
            if($SubmitType =="Save"){
                $this->redirect(array('controller'=>'HrVisitors')); 
            }
            else{
                $this->redirect(array('action'=>'recruiter','?'=>array('url'=>base64_encode($Interview_Id)))); 
            }  
        }
        
    }
    
    public  function interviewquestion(){
        $this->layout='home';
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
        $this->set('data',$this->InterviewQuestionmaster->find('all',array('order'=>'Round')));    
        if($this->request->is('POST')){
            $request                =   $this->request->data;
            $Department             =   $request['Department'];
            $Desgination            =   $request['Desgination'];
            $Round                  =   $request['Round'];
            $Question               =   addslashes(trim($request['Question']));
            $Answer                 =   addslashes(trim($request['Answer']));
            
            $data=array(
                'Department'=>$Department,
                'Designation'=>$Desgination,
                'Question'=>$Question,
                'Answer'=>$Answer,
                'Round'=>$Round,
                );
            
            if($this->InterviewQuestionmaster->save($data)){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This data save successfully.</span>');
                $this->redirect(array('action'=>'interviewquestion'));
            }
        }
        
    }
    
    public function deletequestion(){
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            $this->InterviewQuestionmaster->query("DELETE FROM `Interview_Question_master` WHERE Id='$id'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
            $this->redirect(array('action'=>'interviewquestion'));
        }
     
    }
    
    public function getquestion(){
        if(isset($_REQUEST['designation'])){ 
            $branchName         =   $this->Session->read('branch_name');
            $Dept               =   $_REQUEST['Dept'];
            $designation        =   $_REQUEST['designation'];
            $Interview_Round    =   $_REQUEST['Interview_Round'];
            
            $data               =   $this->InterviewQuestionmaster->find('all',array('conditions'=>array('Department'=>$Dept,'Designation'=>$designation,'Round'=>$Interview_Round)));
            
            $i=1; 
            foreach($data as $row){
                echo '<div class="form-group">';
                echo '<label class="col-sm-2 control-label">Q.'.$i++.'</label>'; 
                echo '<div class="col-sm-10">'.$row['InterviewQuestionmaster']['Question'].'</div>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label class="col-sm-2 control-label">Ans.</label>'; 
                echo '<div class="col-sm-10">'.$row['InterviewQuestionmaster']['Answer'].'</div>';
                echo '</div>';
            }
        }
        die;
    }
    
    public function hrapproval(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $this->set('data',$this->InterviewMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Recruiter_Status'=>'save','Empdetail_Status'=>'save','Documents_Status'=>'save','Education_Status'=>'save','Hrupdates_Status'=>NULL))));
    }
    
    public  function hrupdate(){
        $this->layout = "home";
        $branchName = $this->Session->read('branch_name');
        $user = $this->Session->read('userid');
        
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
        $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName,'active'=>1))));
                
        if(isset($_REQUEST['url']) && $_REQUEST['url'] !=""){
            $row=$this->InterviewMaster->find('first',array('conditions'=>array('Interview_Id'=>base64_decode($_REQUEST['url']))));
            $this->set('data',$row['InterviewMaster']);
        }
        
	if ($this->request->is('post')){
            $data=$this->request->data;
             
            $Interview_Id       =   $data['Interview_Id'];
            $InterviewMaster    =   $this->InterviewMaster->find('first',array('conditions'=>array('Interview_Id'=>$Interview_Id)));
            $Interview          =   $InterviewMaster['InterviewMaster'];
  
            if($Interview['Parent']=="Father"){$Father=$Interview['Father_Husband_Name'];}else{$Father=NULL;}
            if($Interview['Parent']=="Husband"){$Husband=$Interview['Father_Husband_Name'];}else{$Husband=NULL;}
            
            if(trim(addslashes($data['EPF'])) =="" || trim(addslashes($data['EPF'])) ==0){
                $pfelig="NO";
            }
            else{
                $pfelig="YES";
            }

            if(trim(addslashes($data['ESIC'])) =="" || trim(addslashes($data['ESIC'])) ==0){
                $esielig="NO";
            }
            else{
                $esielig="YES"; 
            }
            
            if($Interview['Alt_Mobile_Number'] ==""){
                $Mobile1=$Interview['Mobile_Number']; 
            }
            else{
                $Mobile1=$Interview['Alt_Mobile_Number'];
            }
            
            $DataArr=array(
                'Title'=>trim(addslashes($Interview['Title'])),
                'EmpType'=>trim(addslashes($data['EmpType'])),
                'EmpName'=>trim(addslashes($Interview['Employee_Name'])),
                'ParentType'=>trim(addslashes($Interview['Parent'])),
                'Father'=>trim(addslashes($Father)),
                'Husband'=>trim(addslashes($Husband)),
                'DOB'=>date('Y-m-d',strtotime($Interview['Date_Of_Birth'])),
                'DOJ'=>date('Y-m-d',strtotime($data['DOJ'])),
                'Gendar'=>trim(addslashes($Interview['Gender'])),
                'BloodGruop'=>addslashes($Interview['Blood_Group']),
                'Adrress1'=>trim(addslashes($Interview['Permanent_Address'])),
                'Adrress2'=>trim(addslashes($Interview['Present_Address'])),
                'State'=>addslashes($Interview['Permanent_State']),
                'StateId'=>addslashes($Interview['Permanent_State_Id']),
                'State1'=>addslashes($Interview['Present_State']),
                'State1Id'=>addslashes($Interview['Present_State_Id']),
                'City'=>trim(addslashes($Interview['Permanent_City'])),
                'City1'=>trim(addslashes($Interview['Present_City'])),
                'PinCode'=>trim(addslashes($Interview['Permanent_Pincode'])),
                'PinCode1'=>trim(addslashes($Interview['Present_Pincode'])),
                'Dept'=>trim(addslashes($data['Dept'])),
                'Desgination'=>trim(addslashes($data['Desgination'])),
                'Band'=>trim(addslashes($data['Band'])),
                'package'=>trim(addslashes($data['package'])),
                'CTC'=>trim(addslashes($data['CTC'])),
                'NetInhand'=>trim(addslashes($data['NetInHand'])),
                'bs'=>trim(addslashes($data['bs'])),
                'conv'=>trim(addslashes($data['conv'])),
                'portf'=>trim(addslashes($data['portf'])),
                'ma'=>trim(addslashes($data['ma'])),
                'sa'=>trim(addslashes($data['sa'])),
                'oa'=>trim(addslashes($data['oa'])),
                'hra'=>trim(addslashes($data['hra'])),
                'Bonus'=>trim(addslashes($data['Bonus'])),
                'PLI'=>trim(addslashes($data['PLI'])),
                'Gross'=>trim(addslashes($data['Gross'])),
                'EPF'=>trim(addslashes($data['EPF'])),
                'ESIC'=>trim(addslashes($data['ESIC'])),
                'pfelig'=>$pfelig,
                'esielig'=>$esielig,
                'ProfessionalTax'=>trim(addslashes($data['ProfessionalTax'])),
                'EPFCO'=>trim(addslashes($data['EPFCO'])),
                'ESICCO'=>trim(addslashes($data['ESICCO'])),
                'AdminCharges'=>trim(addslashes($data['AdminCharges'])),
                'CostCenter'=>trim(addslashes($data['HrVisitors']['CostCenter'])),
                'BranchName'=>$Interview['BranchName'],
                'MaritalStatus'=>$Interview['Marital_Status'],
                'Qualification'=>$Interview['Qualification'],
                'NomineeName'=>$Interview['Nominee'],
                'NomineeRelation'=>$Interview['Nominee_Relation'],
                'NomineeDob'=>$Interview['Nominee_Date_Of_Birth'],
                'Mobile'=>$Interview['Mobile_Number'],
                'Mobile1'=>$Mobile1,
                'EmailId'=>$Interview['Personal_Email_Id'],
                'OfficeEmailId'=>$Interview['Official_Email_Id'],
                'PanNo'=>$Interview['Pan_Number'],
                'AdharId'=>$Interview['Adhar_Number'],
                'SourceType'=>$Interview['Source_Type'],
                'Source'=>$Interview['Source'],
                'Profile'=>$data['Profile'],
                'Interview_Id'=>$Interview['Interview_Id'],
                'AcBank'=>$Interview['AcBank'],
                'AcBranch'=>$Interview['AcBranch'],
                'AccHolder'=>$Interview['AccHolder'],
                'AcNo'=>$Interview['AcNo'],
                'IFSCCode'=>$Interview['IFSCCode'],
                'AccType'=>$Interview['AccType'],
                'userid'=>$user,
                'Qualification_Details'=>$Interview['Qualification_Details'],
                'Passed_Out_Year'=>$Interview['Passed_Out_Year'],
                'Passed_Out_State_Id'=>$Interview['Passed_Out_State_Id'],
                'Passed_Out_State'=>$Interview['Passed_Out_State'],
                'Passed_Out_City'=>$Interview['Passed_Out_City'],
                'Passed_Out_Percent'=>$Interview['Passed_Out_Percent'],
                'Family_Annual_Income'=>$Interview['Family_Annual_Income'],
                'Count_Of_Dependents'=>$Interview['Count_Of_Dependents'],
                'Experience'=>$Interview['Experience'],
                'Experience_Year'=>$Interview['Experience_Year'],
                'Experience_Doc'=>$Interview['Experience_Doc'],
            );

            $this->InterviewMaster->query("UPDATE Interview_master SET `EmpType`='".trim(addslashes($data['EmpType']))."',
            `DOJ`='".date('Y-m-d',strtotime($data['DOJ']))."',`Dept`='".trim(addslashes($data['Dept']))."',
            `Desgination`='".trim(addslashes($data['Desgination']))."',`Band`='".trim(addslashes($data['Band']))."',
            `package`='".trim(addslashes($data['package']))."',`CTC`='".trim(addslashes($data['CTC']))."',
            `NetInhand`='".trim(addslashes($data['NetInHand']))."',`bs`='".trim(addslashes($data['bs']))."',
            `conv`='".trim(addslashes($data['conv']))."',`portf`='".trim(addslashes($data['portf']))."',
            `ma`='".trim(addslashes($data['ma']))."',`sa`='".trim(addslashes($data['sa']))."',`oa`='".trim(addslashes($data['oa']))."',
            `hra`='".trim(addslashes($data['hra']))."',`Bonus`='".trim(addslashes($data['Bonus']))."',`PLI`='".trim(addslashes($data['PLI']))."',
            `Gross`='".trim(addslashes($data['Gross']))."',`EPF`='".trim(addslashes($data['EPF']))."',`ESIC`='".trim(addslashes($data['ESIC']))."',
            `pfelig`='".$pfelig."',`esielig`='".$esielig."',`ProfessionalTax`='".trim(addslashes($data['ProfessionalTax']))."',
            `EPFCO`='".trim(addslashes($data['EPFCO']))."',`ESICCO`='".trim(addslashes($data['ESICCO']))."',
            `AdminCharges`='".trim(addslashes($data['AdminCharges']))."',`CostCenter`='".trim(addslashes($data['HrVisitors']['CostCenter']))."',
            `Profile`='".$data['Profile']."' WHERE Interview_Id='$Interview_Id'");
            
            if ($this->NewjclrMaster->saveall($DataArr)){
                $Hrupdates_Status   =   "save";
                $this->InterviewMaster->updateAll(array('Hrupdates_Status'=>"'".$Hrupdates_Status."'"),array('Interview_Id'=>$Interview_Id));
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This details update successfully.</span>');
                $this->redirect(array('controller'=>'HrVisitors','action'=>'hrapproval'));   
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This details does not save please try again later.</span>'); 
                $this->redirect(array('controller'=>'HrVisitors','action'=>'hrupdate','?'=>array('url'=>base64_encode($Interview_Id))));
            }
        }        
    }
    
    public  function empdetails(){
        $this->layout='home';
        if(isset($_REQUEST['url']) && $_REQUEST['url'] !=""){
            $row=$this->InterviewMaster->find('first',array('conditions'=>array('Interview_Id'=>base64_decode($_REQUEST['url']))));
            $this->set('data',$row['InterviewMaster']);  
            
            $this->set('language',$this->LanguageMaster->find('all'));  
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;

            $Interview_Id           =   $request['Interview_Id'];
            $Read                   =   implode(",", $request['Read']);
            $Write                  =   implode(",", $request['Write']);
            $Speak                  =   implode(",", $request['Speak']);
            $Job_Position           =   $request['Job_Position'];
            $Interview_Round        =   $request['Interview_Round'];
            $Candidate_Salar_Exp    =   $request['Candidate_Salar_Exp'];
            $Salary_Offer_CTC       =   $request['Salary_Offer_CTC'];
            $Salary_Offer_Net       =   $request['Salary_Offer_Net'];
            $Candidate_Feedback     =   $request['Candidate_Feedback'];
            $Next_Interview_Date    =   date("Y-m-d",strtotime($request['Next_Interview_Date']));
            $SubmitType             =   $request['Submit'];
            $Recruiter_Save         =   "save";
            
            $data=array(
                'Read'=>"'".$Read."'",
                'Write'=>"'".$Write."'",
                'Speak'=>"'".$Speak."'",
                'Job_Position'=>"'".$Job_Position."'",
                'Interview_Round'=>"'".$Interview_Round."'",
                'Candidate_Salar_Exp'=>"'".$Candidate_Salar_Exp."'",
                'Salary_Offer_CTC'=>"'".$Salary_Offer_CTC."'",
                'Salary_Offer_Net'=>"'".$Salary_Offer_Net."'",
                'Candidate_Feedback'=>"'".$Candidate_Feedback."'",
                'Next_Interview_Date'=>"'".$Next_Interview_Date."'",
                );
            
            $SubmitType=="Save"?$data['Recruiter_Status']="'".$Recruiter_Save."'":"";
                 
            $this->InterviewMaster->updateAll($data,array('Interview_Id'=>$Interview_Id));
            
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This details update successfully.</span>');
            $this->redirect(array('action'=>'recruiter','?'=>array('url'=>base64_encode($Interview_Id))));   
        }
        
    }
    
    public function getband(){
        if(isset($_REQUEST['Band'])){ 
            $branchName = $this->Session->read('branch_name');
            $package    = $_REQUEST['package'];
            $state=$this->maspackage->find('list',array('fields'=>array('PackageAmount'),'conditions'=>array('Band'=>$_REQUEST['Band'],'CostCenter'=>$_REQUEST['CostCenter'],'BranchName'=>$branchName),'order'=>'Band'));
            //$state=$this->maspackage->find('list',array('fields'=>array('PackageAmount'),'conditions'=>array('Band'=>$_REQUEST['Band'],'BranchName'=>$branchName),'order'=>'Band'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                if($package ==$val){$selected="selected='selected'";}else{$selected="";}
                echo "<option $selected value='$val'>$val</option>";
            }      
        }
        die;
    }
    
     public function getdesg(){
        if(isset($_REQUEST['Designation'])){ 
            $band    = $_REQUEST['band'];
			
            $Band=$this->DesignationNameMaster->find('all',array('fields'=>array('Band'),'conditions'=>array('Designation'=>$_REQUEST['Designation'],'Status'=>1),'group'=>'Band'));
            echo "<option value=''>Select</option>";
            foreach($Band as $val){
                
                $BandName=$val['DesignationNameMaster']['Band'];
                $SlabArr=$this->BandNameMaster->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$BandName,'Status'=>1),'order'=>'Band'));
                
                $SlabFrom=$SlabArr['BandNameMaster']['SlabFrom'];
                $SlabTo=$SlabArr['BandNameMaster']['SlabTo'];
                
                if($band ==$BandName){$selected="selected='selected'";}else{$selected="";}
                
                echo "<option $selected value='$BandName'>$BandName ($SlabFrom - $SlabTo)</option>";
            }
            die; 
        }
        die;
    }
    
    public function deletehremp(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $Interview_Id   = base64_decode($_REQUEST['id']);
    
            $dirPath="/var/www/html/ispark/app/webroot/Interview_File/".$Interview_Id."/";
            array_map('unlink', glob("$dirPath/*.*"));
            rmdir($dirPath);

            $this->InterviewMaster->query("DELETE FROM `mas_docoments` WHERE Interview_Id='$Interview_Id'");
            $this->InterviewMaster->query("DELETE FROM `Interview_master` WHERE Interview_Id='$Interview_Id'");
            
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
            $this->redirect(array('action'=>'hrapproval'));
        }
     
    }
    
    public function viewvisitor(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            $Branch         =   $request['HrVisitors']['branch_name'];
            $Visitor_Id     =   $request['Visitor_Id'];
            $Submit         =   $request['Submit'];
            
            $WhereDate      =   "DATE(createdate) >='$FromDate' AND DATE(createdate) <='$ToDate'";
            $WhereBranch    =   $Branch !="ALL"?"AND branch_name='$Branch'":"";
            $conditoin      =   array("$WhereDate $WhereBranch");
            
            $data           =   $this->VisitorMaster->find('all',array('conditions'=>$conditoin));
            
            if($Submit =="View"){
                $this->set('data',$data);
                $this->set('FromDate',$request['FromDate']);
                $this->set('ToDate',$request['ToDate']);
            }
            else if($Submit =="Delete"){
                foreach($Visitor_Id as $id){
                    $this->VisitorMaster->query("DELETE FROM `hr_visitor` WHERE Visitor_Id='$id'");
                }
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This record delete successfully.</span>');
                $this->redirect(array('action'=>'viewvisitor'));
            }
            else if($Submit =="Export"){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=visitor_export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table border="1">     
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <th>Branch</th>
                        <th>Source Name</th>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Purpose Of Meeting</th>
                        <th>Mobile No</th>
                        <th>Whom To Meet</th>
                        <th>Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($data as $rowArr){$row=$rowArr['VisitorMaster'];?>
                    <tr>
                        <td><?php echo $i++?></td>
                        <td><?php echo $row['branch_name']?></td>
                        <td><?php echo $row['Source_Of_Information']?></td>
                        <td><?php echo $row['visitor_name']?></td>
                        <td><?php echo $row['visitor_company']?></td>
                        <td><?php echo $row['visitor_purpose']?></td>
                        <td><?php echo $row['mobile_no']?></td>
                        <td><?php echo $row['wt_meet']?></td>
                        <td><?php echo $row['visitor_address']?></td>
                        <td><?php echo $row['createdate']!=""?date("d-M-Y",strtotime($row['createdate'])):''?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
            }    
        }
        
        
        
        
        
    }

    public function hr_recruiter_add()
    {
        $this->layout='home';
        
        
        if($this->request->is('POST'))
        {
            $mas_id = $this->request->data['mas_name'];
            $branch_name = $this->request->data['branch_name'];
            
            $Masjclrentry = $this->Masjclrentry->find('first',array('fields'=>array("id","EmpCode","BranchName","EmpName","Mobile","OfficeEmailId"),'conditions'=>array('id'=>$mas_id)));
            $hr_mas = $this->HRVisitorRecruiter->find('first',array('conditions'=>array('branch'=>$branch_name)));
            
            //print_r($this->request->data); exit;
            
            if(empty($branch_name))
            {
                $this->Session->setFlash("Please Select Branch"); 
            }
            else if(empty($hr_mas))
            {
                $save_data = array();
                $save_data['mas_code'] =$Masjclrentry['Masjclrentry']['EmpCode'];
                $save_data['mas_name'] =$Masjclrentry['Masjclrentry']['EmpName'];
                $save_data['mobile_no']=$Masjclrentry['Masjclrentry']['Mobile'];
                $save_data['office_mail_id']=$Masjclrentry['Masjclrentry']['OfficeEmailId'];
                $save_data['branch']= $branch_name;
                $save_data['created_at']=date("Y-m-d H:i:s");
                $save_data['created_by']=$this->Session->read('userid');
                //print_r($save_data); exit;
                if($this->HRVisitorRecruiter->save(array('HRVisitorRecruiter'=>$save_data)))
                {
                    $this->Session->setFlash("Record Has been Saved Successfully.");
                    $this->redirect(array("action"=>'hr_recruiter_add'));
                }
                else
                {
                    $this->Session->setFlash("Record Not Found");
                }
            }
            else
            {
              $this->Session->setFlash("Record Allready Exist.");  
            }
        }
        
        $branch_master = $this->Addbranch->find('list',array('conditions'=>array('active'=>'1'),
            'fields'=>array("branch_name","branch_name"),'order'=>array('branch_name'=>array('asc'))));
        $hr_recruit_master = $this->HRVisitorRecruiter->find('all',array('conditions'=>array('active_status'=>'1')));
        $Masjclrentry = $this->Masjclrentry->find('all',array('fields'=>array("id","EmpCode","BranchName","EmpName"),'conditions'=>array('Status'=>'1'),'order'=>array('EmpName'=>array('asc'))));
        
        $user_mas = array();
        foreach($Masjclrentry as $mas_enter)
        {
            $user_mas[$mas_enter['Masjclrentry']['id']] = $mas_enter['Masjclrentry']['EmpName'].'/'.$mas_enter['Masjclrentry']['EmpCode'].' ('.$mas_enter['Masjclrentry']['BranchName'].")";
        }
        
        //print_r($user_mas); exit;
        $this->set('branch_master',$branch_master);
        $this->set('hr_recruit_master',$hr_recruit_master);
        $this->set('user_mas',$user_mas);
    }
    
    public function hr_recruiter_edit()
    {
        $this->layout='home';
        $id = base64_decode($this->params->query['id']);
        $branch_master = $this->Addbranch->find('list',array('conditions'=>array('active'=>'1'),
            'fields'=>array("branch_name","branch_name"),'order'=>array('branch_name'=>array('asc'))));
        $hr_recruit_master = $this->HRVisitorRecruiter->find('first',array('conditions'=>array('active_status'=>'1','Id'=>$id)));
        
        if($this->request->is('POST'))
        {
            $id = base64_decode($this->params->query['id']);
            $branch_name = $this->request->data['branch_name'];
            $active_status = $this->request->data['active_status'];
            
            $hr_mas2 = $this->HRVisitorRecruiter->find('first',array('conditions'=>"Id='$id' "));
            $Masjclrentry = $this->Masjclrentry->find('first',array('fields'=>array("id","EmpCode","BranchName","EmpName","Mobile","OfficeEmailId"),'conditions'=>array('EmpCode'=>$hr_mas2['HRVisitorRecruiter']['mas_code'])));
            
			$hr_mas = $this->HRVisitorRecruiter->find('first',array('conditions'=>"branch='$branch_name' and Id!=$id and active_status='1'"));
            
            
            
            if(empty($branch_name))
            {
                $this->Session->setFlash("Please Select Branch"); 
            }
            else if(!empty($hr_mas))
            {
                $upd_data = array();
                $upd_data['mas_code'] ="'".$Masjclrentry['Masjclrentry']['EmpCode']."'";
                $upd_data['mas_name'] ="'".$Masjclrentry['Masjclrentry']['EmpName']."'";
                $upd_data['mobile_no']="'".$Masjclrentry['Masjclrentry']['Mobile']."'";
                $upd_data['office_mail_id']="'".$Masjclrentry['Masjclrentry']['OfficeEmailId']."'";
                $upd_data['branch']= "'".$branch_name."'";
                $upd_data['active_status']= "'".$active_status."'";
                $upd_data['updated_at']="'".date("Y-m-d H:i:s")."'";
                $upd_data['updated_by']="'".$this->Session->read('userid')."'";
                //print_r($save_data); exit;
                if($this->HRVisitorRecruiter->updateAll($upd_data,array('Id'=>$id)))
                {
                    $this->Session->setFlash("Record Has been Updated Successfully.");
                    $this->redirect(array("action"=>'hr_recruiter_add'));
                }
                else
                {
                    $this->Session->setFlash("Record Not Found");
                }
            }
            else
            {
              $this->Session->setFlash("Record Allready Exist.");  
            }
        }
        
        
        //print_r($user_mas); exit;
        $this->set('branch_master',$branch_master);
        $this->set('hr_recruit_master',$hr_recruit_master);
    }
    
    
    public function hr_mobile_user_add()
    {
        $this->layout='home';
        if($this->request->is('POST'))
        {
            $user_name = $this->request->data['user_name'];
            $password = $this->request->data['password'];
            $branch_name = $this->request->data['branch'];
            $OPBranch = $this->request->data['OPBranch'];
            
            $hr_mas = $this->HRLogin->find('first',array('conditions'=>array('user_name'=>$user_name)));
            
            
            
            if(empty($branch_name))
            {
                $this->Session->setFlash("Please Select Branch"); 
            }
            else if(empty($hr_mas))
            {
                $save_data = array();
                $save_data['User_Name'] =$user_name;
                $save_data['User_Password'] =$password;
                $save_data['Branch']= implode(",",$branch_name);
                $save_data['OPBranch']= $OPBranch;
                $save_data['created_at']=date("Y-m-d H:i:s");
                $save_data['created_by']=$this->Session->read('userid');
                
                
                
                if($this->HRLogin->save(array('HRLogin'=>$save_data)))
                {
                    $this->Session->setFlash("Record Has been Saved Successfully.");
                    $this->redirect(array("action"=>'hr_mobile_user_add'));
                }
                else
                {
                    $this->Session->setFlash("Record Not Found");
                }
            }
            else
            {
              $this->Session->setFlash("Record Allready Exist.");  
            }
        }
        $branch_master = $this->Addbranch->find('list',array('conditions'=>array('active'=>'1'),
            'fields'=>array("branch_name","branch_name"),'order'=>array('branch_name'=>array('asc'))));
        $hr_recruit_master = $this->HRLogin->find('all',array('conditions'=>array('active_status'=>'1')));
        
        
       
        
        //print_r($user_mas); exit;
        $this->set('branch_master',$branch_master);
        $this->set('hr_recruit_master',$hr_recruit_master);
    }
    
    public function hr_mobile_user_edit()
    {
        $this->layout='home';
        $id = base64_decode($this->params->query['id']);
        if($this->request->is('POST'))
        {
            $user_name = $this->request->data['user_name'];
            $password = $this->request->data['password'];
            $branch_name = $this->request->data['branch'];
            $OPBranch = $this->request->data['OPBranch'];
            
            $active_status = $this->request->data['active_status'];
            
            
            
            
            if(empty($branch_name))
            {
                $this->Session->setFlash("Please Select Branch"); 
            }
            else 
            {
                $save_data = array();
                $save_data['User_Password'] ="'".$password."'";
                $save_data['Branch']= "'".implode(",",$branch_name)."'";
                $save_data['OPBranch']= "'".$OPBranch."'";
                $save_data['updated_at']="'".date("Y-m-d H:i:s")."'";
                $save_data['updated_by']=$this->Session->read('userid');
                $save_data['active_status']=$active_status;
                
                
                if($this->HRLogin->updateAll($save_data,array('User_Name'=>$user_name)))
                {
                    $this->Session->setFlash("Record Has been Updated Successfully.");
                    $this->redirect(array("action"=>'hr_mobile_user_add'));
                }
                else
                {
                    $this->Session->setFlash("Record Not Found");
                }
            }
            
        }
        $branch_master = $this->Addbranch->find('list',array('conditions'=>array('active'=>'1'),
            'fields'=>array("branch_name","branch_name"),'order'=>array('branch_name'=>array('asc'))));
        $hr_recruit_master = $this->HRLogin->find('first',array('conditions'=>array('active_status'=>'1','HR_Id'=>$id)));
        
        
       
        
        //print_r($user_mas); exit;
        $this->set('branch_master',$branch_master);
        $this->set('hr_recruit_master',$hr_recruit_master);
    }
    
    public function hr_add_purpose_to_meet()
    {
        $this->layout='home';
        if($this->request->is('POST'))
        {
            $meet_purpose = $this->request->data['meet_purpose'];
            
            $hr_purpose = $this->HRMeetPurpose->find('first',array('conditions'=>array('meet_purpose'=>$meet_purpose)));
            
            
            
            if(empty($meet_purpose))
            {
                $this->Session->setFlash("Please Fill Purpose"); 
            }
            else if(empty($hr_purpose))
            {
                $save_data = array();
                $save_data['meet_purpose'] =$meet_purpose;
                $save_data['created_at']=date("Y-m-d H:i:s");
                $save_data['created_by']=$this->Session->read('userid');
                
                if($this->HRMeetPurpose->save(array('HRMeetPurpose'=>$save_data)))
                {
                    $this->Session->setFlash("Record Has been Saved Successfully.");
                    $this->redirect(array("action"=>'hr_add_purpose_to_meet'));
                }
                else
                {
                    $this->Session->setFlash("Record Not Found");
                }
            }
            else
            {
              $this->Session->setFlash("Record Allready Exist.");  
            }
        }
        
        $hr_purpose_master = $this->HRMeetPurpose->find('all');
        $this->set('hr_purpose_master',$hr_purpose_master);
    }
    
    public function hr_delete_purpose_to_meet()
    {
        $this->layout='home';
        $id = base64_decode($this->params->query['id']);
        
        if($this->HRMeetPurpose->deleteAll(array('Id'=>$id)))
        {
            $this->Session->setFlash("Record Has been Deleted Successfully"); 
        }
        else
        {
            $this->Session->setFlash("Record Not Deleted. Please Contact to Admin");
        }
        $this->redirect(array("action"=>'hr_add_purpose_to_meet'));
        exit;
    }
}
?>