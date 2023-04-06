<?php
class HrInterviewsController extends AppController {
    public $uses = array('Addbranch','InterviewMaster','VisitorMaster','InterviewQuestionmaster',
        'interviewquestion','maspackage','BandNameMaster','DesignationNameMaster','masband','StateMaster',
        'DepartmentNameMaster','CostCenterMaster','NewjclrMaster','LanguageMaster','Masjclrentry','TrainerMaster',
        'HRVisitorRecruiter','HRLogin','HRMeetPurpose');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('onlineinterviewlink','send_online_interview_link','manual_send_online_interview_link','send_sms');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
	
	public function onlineinterviewlink(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
		
		if($this->request->is('POST')){
            $request        =   $this->request->data;
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            $Branch         =   $request['HrInterviews']['branch_name'];
            $Visitor_Id     =   $request['Visitor_Id'];
            $Submit         =   $request['Submit'];
            
            $data           =   $this->InterviewMaster->query("SELECT * FROM Interview_Online_Link_Status WHERE `Branch_Name`='$Branch' AND DATE(Create_Date) BETWEEN '$FromDate' AND '$ToDate'");
            
            if($Submit =="View"){
                $this->set('data',$data);
                $this->set('FromDate',$request['FromDate']);
                $this->set('ToDate',$request['ToDate']);
            }
            else if($Submit =="Export"){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table border="1">     
                <thead>
                    <tr>
                        <th>BranchName</th>
                        <th>Mobile No</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){?>
                    <tr>
                        <td><?php echo $row['Interview_Online_Link_Status']['Branch_Name'];?></td>
                        <td><?php echo $row['Interview_Online_Link_Status']['Mobile_No'];?></td>
                        <td><?php echo $row['Interview_Online_Link_Status']['Status'];?></td>
                        <td ><?php echo $row['Interview_Online_Link_Status']['Create_Date']!=""?date("d-M-Y",strtotime($row['Interview_Online_Link_Status']['Create_Date'])):''?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
            }    
        }
		
		
	}
	
    
    public function send_online_interview_link(){
		
		if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['HrInterviews']['branch_name'];
			$user 			= 	$this->Session->read('userid');

            $csv_file		=	$_FILES['uploadFile']['tmp_name'];
            $FileTye		=	$_FILES['uploadFile']['type'];
            $info			=	explode(".",$_FILES['uploadFile']['name']);

            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 100000, ",");
                    $totalcolumn=count($filedata);
                    if($totalcolumn ==2){
                       
						$NumArr		=	array();
                        while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
							
							$Mobile_No		=   $data[0];
							$EmailId		=   $data[1];
							$Url			=	"http://mascallnetnorth.in/hrinterview?XYZ=".base64_encode($Branch);
							
							$num['ReceiverNumber'] 	=	$Mobile_No;
							$num['SmsText'] 		=	$Url;
							
							$res = $this->send_sms($num); 
							
							if($res){
								$status="Send";
							}
							else{
								$status="Pending";
							}
							
							if($EmailId !=""){
								App::uses('sendEmail', 'custom/Email');
								$sub = "Mas Callnet Interview Process";
								$msg = "Hi <br/><br/>Please click or copy this link to online hr interview process.";
								$msg .= "<br/><br/>$Url<br/><br/>";
								$mail = new sendEmail();
								$mail_status = $mail-> to($EmailId,$msg,$sub);
							}
							
							$this->InterviewMaster->query("INSERT INTO `Interview_Online_Link_Status` SET `Branch_Name`='$Branch',`Mobile_No`='$Mobile_No',`Status`='$status',`Created_By`='$user'");
                        }
                          
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>'); 
                    }
				}
            }
            else{
				$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>'); 
            }
	
			$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Interview link send successfully on given mobile no.</span>'); 
		
			$this->redirect(array("action"=>'onlineinterviewlink'));

		}
    }
	
	public function manual_send_online_interview_link(){
		
		if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['HrInterviews']['branch_name'];
			$user 			= 	$this->Session->read('userid');
			$Mobile_No		=   $_REQUEST['uploadFile'];
			$EmailId		=   $_REQUEST['EmailId'];
			$Url			=	"http://mascallnetnorth.in/hrinterview?XYZ=".base64_encode($Branch);
							
			$num['ReceiverNumber'] 	=	$Mobile_No;
			$num['SmsText'] 		=	$Url;
							
			$res = $this->send_sms($num); 
							
			if($res){
				$status="Send";
			}
			else{
				$status="Pending";
			}
			
			if($EmailId !=""){
				App::uses('sendEmail', 'custom/Email');
				$sub = "Mas Callnet Interview Process";
				$msg = "Hi <br/><br/>Please click or copy this link to online hr interview process.";
				$msg .= "<br/><br/>$Url<br/><br/>";
				$mail = new sendEmail();
				$mail_status = $mail-> to($EmailId,$msg,$sub);
			}
			
			$this->InterviewMaster->query("INSERT INTO `Interview_Online_Link_Status` SET `Branch_Name`='$Branch',`Mobile_No`='$Mobile_No',`Status`='$status',`Created_By`='$user'");
		
			$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Interview link send successfully on given mobile no.</span>'); 
		
			$this->redirect(array("action"=>'onlineinterviewlink'));

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
    
    
        
      
}
?>