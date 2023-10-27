<?php
class EmpAttStatusesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','User','ContinuouslyLeave','MailAlert','EmpOnService','OnboardLeaveAlert','AccessPages');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','left_emp','get_attend_status','test','log_report','show_report','export_report','mail_alert','delete_alert','edit_mail_alert','update_mail_alert');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        
        // $branchName = $this->Session->read('branch_name');
        // if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
        //     $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        //     $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        // }
        // else{
        //     $this->set('branchName',array($branchName=>$branchName)); 
        // }
        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        if($this->request->is('Post'))
        {
            //print_r($this->request->data);die;
            $branch_name    =   $this->request->data['EmpAttStatuses']['branch_name'];
            $CostCenter    =   trim($this->request->data['CostCenter']);

            $condition=array(
                'left_status'=>'0',
                'leave_status'=>'0'
            );

            if($branch_name !="ALL"){$condition['BranchName']=$branch_name;}else{unset($condition['BranchName']);}
            if($CostCenter !="ALL"){$condition['CostCenter']=$CostCenter;}else{unset($condition['CostCenter']);}

            if($this->request->data['from_date'] !="" && $this->request->data['to_date'] !="")
            {
                //echo "date";die;
                $from = date("Y-m-d",strtotime($this->request->data['from_date']));
                $to = date("Y-m-d",strtotime($this->request->data['to_date']));

                $condition['date(created_at) >=']=$from;
                $condition['date(created_at) <=']=$to;
            }else{
                unset($condition['date(created_at) >=']);
                unset($condition['date(created_at) <=']);
            }
            $data = $this->ContinuouslyLeave->find('all',array('conditions'=>$condition));
            $data = $this->ContinuouslyLeave->find('all',array('conditions'=>$condition,'order'=>array("BranchName","EmpName")));
            if(!empty($data))
            {
                $DataArr = array();
                foreach($data as $d)
                {
                    $costcenter = $d['ContinuouslyLeave']['CostCenter'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->ContinuouslyLeave->query($qry);
                    //print_r($costdata);die;
                    $process_name = $costdata[0]['cost_master']['process_name'];
                    if($process_name == '')
                    {
                        $process['costcentername'] = $costdata[0]['cost_master']['CostCenterName'];
                    }else{
                        $process['costcentername'] = $costdata[0]['cost_master']['process_name'];
                    }

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }
            }

            
            $this->set('fromdate',$from);
            $this->set('todate',$to);
            $this->set('costcenter',$CostCenter);
            $this->set('data',$DataArr);
        }

            
    }

    public function left_emp(){
        $this->layout='home';      
          
        if($this->request->is('Post')){
            
            $data           =   $this->request->data['EmpAttStatus'];
            $User_Id        =   $this->Session->read('userid');
            $id             =   $data['idx'];
            $tab            =   $data['tab'];
            $remarks        =   $data['remarks1'];
            $remarks2        =   $data['remarks2'];
            $remarks3        =   $data['remarks3'];

            $dataArr=array(
                'left_by'=>"'".$User_Id."'",
                'left_date'=>"'".date('Y-m-d')."'",
                'left_status'=>"'1'"
            );

            if($tab == 'form1')
            {
                $dataArr['remarks1']="'".$remarks."'";
            }
            else if($tab == 'form2')
            {
                $dataArr['remarks2']="'".$remarks2."'";
            }
            else{

                $dataArr['remarks3']="'".$remarks3."'";
            }

            //print_r($dataArr);die;

            // $exist_data = $this->ContinuouslyLeave->find('first',array('conditions'=>array('id'=>$id)));
            // print_r($exist_data);die;
            $save = $this->ContinuouslyLeave->updateAll($dataArr,array('id'=>$id));
            if($save)
            {
                //$this->ContinuouslyLeave->updateAll($dataArr,array('id'=>$id));
                $exist_data = $this->ContinuouslyLeave->find('first',array('conditions'=>array('id'=>$id)));

                $BranchName = $exist_data['ContinuouslyLeave']['BranchName'];
                $CostCenter = $exist_data['ContinuouslyLeave']['CostCenter'];
                $EmpCode = $exist_data['ContinuouslyLeave']['EmpCode'];

                $exist_alert_bio = $this->OnboardLeaveAlert->query("select * from emp_onboard_leave_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='bio_id'");
                
                $bio_to = $exist_alert_bio[0]['emp_onboard_leave_alert']['to'];
                $bio_cc = $exist_alert_bio[0]['emp_onboard_leave_alert']['cc'];
                $bio_bcc = $exist_alert_bio[0]['emp_onboard_leave_alert']['bcc'];
                $bio_type = $exist_alert_bio[0]['emp_onboard_leave_alert']['trigger_type'];
                $ticket_bio = "Bio$id";

                $exist_alert_email = $this->OnboardLeaveAlert->query("select * from emp_onboard_leave_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='email_id'");
                $email_to = $exist_alert_email[0]['emp_onboard_leave_alert']['to'];
                $email_cc = $exist_alert_email[0]['emp_onboard_leave_alert']['cc'];
                $email_bcc = $exist_alert_email[0]['emp_onboard_leave_alert']['bcc'];
                $email_type = $exist_alert_email[0]['emp_onboard_leave_alert']['trigger_type'];
                $ticket_email = "Email$id";

                $exist_alert_partner = $this->OnboardLeaveAlert->query("select * from emp_onboard_leave_alert where branch='$BranchName' and cost_center = '$CostCenter' and trigger_type ='partner_id_req'");
                $partner_to = $exist_alert_partner[0]['emp_onboard_leave_alert']['to'];
                $partner_cc = $exist_alert_partner[0]['emp_onboard_leave_alert']['cc'];
                $partner_bcc = $exist_alert_partner[0]['emp_onboard_leave_alert']['bcc'];
                $partner_type = $exist_alert_partner[0]['emp_onboard_leave_alert']['trigger_type'];
                $ticket_partner = "Partner$id";

                $create_date = date('Y-m-d');

                $type = 'leaver';

                $list_value="('".$id."','".$ticket_bio."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$bio_type."','".$bio_to."','".$bio_cc."','".$bio_bcc."','".$create_date."'),
                ('".$id."','".$ticket_email."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$email_type."','".$email_to."','".$email_cc."','".$email_bcc."','".$create_date."'),
                ('".$id."','".$ticket_partner."','".$EmpCode."','".$BranchName."','".$CostCenter."','".$type."','".$partner_type."','".$partner_to."','".$partner_cc."','".$partner_bcc."','".$create_date."')";
                $this->EmpOnService->query("INSERT INTO emp_onboard_trigger_services(`ticket_id`,`ticket_no`,`emp_code`,`branch`,`cost_center`,`type`,`trigger_type`,`to`,`cc`,`bcc`,`created_at`) values $list_value"); 
                //$this->LoanMaster->query("UPDATE `LoanMaster` SET RTGSNumber=NULL,RTGSDate=NULL,ChequeNumber='$ChequeNumber',ChequeBankName='$BankName',ChequeDate='$PrintDate',printby='$PrintBy',PrintDate='$PrintDate' WHERE Id='$Id'");

                $this->redirect(array('action'=>'index')); 
                $this->Session->setFlash('<span style="color:red;font-weight:bold;">Your Remarks Save Sucessfully.</span>'); 
            }

        }     
    }
    

    public function log_report()
    {
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

    public function show_report()
    {
        $this->layout='ajax';
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !="")
        {
            $wheretag = '';
            if($_REQUEST['BranchName'] !="ALL")
            {
                $condition['BranchName']=$_REQUEST['BranchName'];
                $wheretag .= " and BranchName = '{$_REQUEST['BranchName']}'";
            }
            else{

                unset($condition['BranchName']);
            }
            if($_REQUEST['EmpCode'] !="")
            {
                $condition['EmpCode']=$_REQUEST['EmpCode'];
                $wheretag .= " and EmpCode = '{$_REQUEST['EmpCode']}'";
            }
            else{
                unset($condition['EmpCode']);
            }
            if($_REQUEST['CostCenter'] !="ALL")
            {
                $condition['CostCenter']=$_REQUEST['CostCenter'];
                $wheretag .= " and CostCenter = '{$_REQUEST['CostCenter']}'";
            }else{

                unset($condition['CostCenter']);
            }
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));

            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from continuously_leave where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag";
            $data_arr   =   $this->ContinuouslyLeave->query($qry);
            //print_r($data_arr);die;

            if(!empty($data_arr))
            {?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>EmpLocation</th>
                            <th>Left Date</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($data_arr as $data){ ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['continuously_leave']['EmpCode'];?></td>
                            <td><?php echo $data['continuously_leave']['EmpName'];?></td>
                            <td><?php echo $data['continuously_leave']['BranchName'];?></td>
                            <td><?php echo $data['continuously_leave']['CostCenter'];?></td>
                            <td><?php echo $data['continuously_leave']['EmpStatus'];?></td>
                            <td><?php echo "From -" .date('d M y',strtotime($val['ContinuouslyLeave']['from_date']));echo "<br>"; echo "To- ".date('d M y',strtotime($val['ContinuouslyLeave']['to_date']));?></td>
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array_merge($condition,array('Status'=>1)))); 
        }

    }

    public function export_report()
    {
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !="")
        {
            $wheretag = '';
            if($_REQUEST['BranchName'] !="ALL")
            {
                $condition['BranchName']=$_REQUEST['BranchName'];
                $wheretag .= " and BranchName = '{$_REQUEST['BranchName']}'";
            }
            else{

                unset($condition['BranchName']);
            }
            if($_REQUEST['EmpCode'] !="")
            {
                $condition['EmpCode']=$_REQUEST['EmpCode'];
                $wheretag .= " and EmpCode = '{$_REQUEST['EmpCode']}'";
            }
            else{
                unset($condition['EmpCode']);
            }
            if($_REQUEST['CostCenter'] !="ALL")
            {
                $condition['CostCenter']=$_REQUEST['CostCenter'];
                $wheretag .= " and CostCenter = '{$_REQUEST['CostCenter']}'";
            }else{

                unset($condition['CostCenter']);
            }
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));

            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from continuously_leave where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag";
            $data_arr   =   $this->ContinuouslyLeave->query($qry);
            //print_r($data_arr);die;

            if(!empty($data_arr))
            {
                
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=log_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable" border="1" >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>EmpLocation</th>
                            <th>Remarks1</th>
                            <th>Remarks2</th>
                            <th>Remarks3</th>
                            <th>Left Date</th>
                            
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($data_arr as $data){ ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['continuously_leave']['EmpCode'];?></td>
                            <td><?php echo $data['continuously_leave']['EmpName'];?></td>
                            <td><?php echo $data['continuously_leave']['BranchName'];?></td>
                            <td><?php echo $data['continuously_leave']['CostCenter'];?></td>
                            <td><?php echo $data['continuously_leave']['EmpStatus'];?></td>
                            <td><?php echo $data['continuously_leave']['remarks1'];?></td>
                            <td><?php echo $data['continuously_leave']['remarks2'];?></td>
                            <td><?php echo $data['continuously_leave']['remarks3'];?></td>
                            <td><?php echo "From -" .date('d M y',strtotime($val['ContinuouslyLeave']['from_date']));echo "<br>"; echo "To- ".date('d M y',strtotime($val['ContinuouslyLeave']['to_date']));?></td>
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array_merge($condition,array('Status'=>1)))); 
        }

    }

    public function mail_alert(){
        $this->layout='home';
        
        // $mail_det = $this->MailAlert->find('first');      
        // $this->set('mail_alt',$mail_det['MailAlert']);

        // $branchName = $this->Session->read('branch_name');
        // if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
        //     $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        //     $this->set('branchName',$BranchArray);
        // }
        // else{
        //     $this->set('branchName',array($branchName=>$branchName)); 
        // }

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        $mail_arr = $this->MailAlert->find("all");
        $this->set('mail_arr',$mail_arr);
          
        if($this->request->is('Post')){
            

            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['EmpAttStatuses'];

                $branch_name = $mail['Branch'];
                $costcenter = $this->request->data['CostCenter'];

                $exist_mail = $this->MailAlert->find('first',array('conditions'=>array('Branch'=>$branch_name,'CostCenter'=>$costcenter)));

                foreach($mail as $k=>$v)
                {
                    if($exist_mail)
                    {
                        $data[$k] = "'".addslashes($v)."'";
                    }
                    else
                    {
                        $data[$k] = addslashes($v);
                    }
                }
                if(!empty($exist_mail))
                {
                    $this->Session->setFlash('Alert Already Exist');
                    $this->redirect(array('controller'=>'EmpAttStatuses','action'=>'mail_alert'));
                }
                else
                {
                    $data['CostCenter'] = $costcenter;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    //print_r($data);die;
                    $this->MailAlert->save($data);
                    $this->Session->setFlash('Alert Added Successfully');
                    $this->redirect(array('controller'=>'EmpAttStatuses','action'=>'mail_alert'));

                }

            }

        }     
    }

    public function delete_alert()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];
    
            $this->MailAlert->query("DELETE FROM mail_alert WHERE id='{$_REQUEST['Id']}'");   
    
        }
        $this->redirect(array('controller'=>'EmpAttStatuses','action'=>'mail_alert'));

    }

    public function edit_mail_alert(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        $mail_arr = $this->MailAlert->find("all");
        $this->set('mail_arr',$mail_arr);
               
    }

    public function update_mail_alert(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        $id = $this->request->query['id'];
        $mail_alert =$this->MailAlert->find('first',array('conditions'=>array('id'=>$id)));

        $this->set('mail_arr',$mail_alert);
          
        if($this->request->is('Post')){
            

            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['EmpAttStatuses'];

                $branch_name = $mail['Branch'];
                $costcenter = $this->request->data['CostCenter'];

                $mail_alert_id= $this->request->data['mail_alert_id'];


                foreach($mail as $k=>$v)
                {
                   
                    $data[$k] = "'".addslashes($v)."'";
                    
                }

                $data['CostCenter'] =  "'".$costcenter."'";
                $data['updated_at'] =  "'".date('Y-m-d H:i:s')."'";
                $data['updated_by'] =  "'".$userid."'";

                //print_r($data);die;
                $save  = $this->MailAlert->updateAll($data,array('id'=>$mail_alert_id));
                if($save)
                {
                    $this->Session->setFlash('Alert Updated Successfully');
                    $this->redirect(array('controller'=>'EmpAttStatuses','action'=>'edit_mail_alert'));
                }else{

                    $this->Session->setFlash('Alert updated Successfully');
                    $this->redirect(array('controller'=>'EmpAttStatuses','action'=>'update_mail_alert'));
                }

                

            }

        }     
    }
      
}
?>