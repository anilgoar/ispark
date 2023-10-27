<?php
class TicketsController extends AppController {
    public $uses = array('AccessPages','CostCenterMaster','Addbranch','Masattandance','User','ContinuouslyLeave','MailAlert','Masjclrentry','EmpOnService','OnboardLeaveAlert','OnboardJoinAlert');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','dashboard','show_data','save_ride','getcostcenter','delete_join_alert','delete_alert','left_alert','join_alert','close_ticket','report','export_report','edit_join_alert',
        'update_join_alert','edit_left_alert','update_left_alert');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }


    public function index(){
        $this->layout='home';
        
        // $branchName = $this->Session->read('branch_name');

         $userid = $this->Session->read("userid");
         $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("(ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1') and user_id='$userid'")));
         
            #echo $userid;die;
         #print_r($costid_list);die;

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            //print_r($BranchArray);die;
            $this->set('branchName',$BranchArray);
        }

        // $branchName = $this->Session->read('branch_name');
        // if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE")
        // {
        //     $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        //     $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        // }
        // else{
        //     $this->set('branchName',array($branchName=>$branchName)); 
        // }

        if($this->request->is('Post'))
        {
            //print_r($this->request->data);die;
            $branch_name    =   $this->request->data['Tickets']['branch_name'];
            $CostCenter    =   trim($this->request->data['CostCenter']);
            $type = $this->request->data['Tickets']['trigger_type'];
            $status = $this->request->data['Tickets']['status'];


            if($branch_name !="ALL"){$condition['branch']=$branch_name;}else{unset($condition['branch']);}
            if($CostCenter !="ALL"){$condition['cost_center']=$CostCenter;}else{unset($condition['cost_center']);}
            #if($type != ""){$condition['trigger_type']=$type;}else{unset($condition['trigger_type']);}
            if($type != "All"){$condition['trigger_type']=$type;}else{unset($condition['trigger_type']);}
            if($status != ""){$condition['ticket_status']=$status;}else{unset($condition['ticket_status']);}

            //$costid_list = $this->AccessPages->find('list',array('fields'=>array('cost_id','cost_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

            // $costid_list1 = $this->AccessPages->find('list',array('fields'=>array('ticket_email','ticket_email'),'conditions'=>array("ticket_email ='1' and user_id='$userid'")));
            // $costid_list2 = $this->AccessPages->find('list',array('fields'=>array('ticket_bio','ticket_bio'),'conditions'=>array("ticket_bio ='1' and user_id='$userid'")));
            // $costid_list3 = $this->AccessPages->find('list',array('fields'=>array('ticket_partner','ticket_partner'),'conditions'=>array("ticket_partner ='1' and user_id='$userid'")));
            // $costid_list4 = $this->AccessPages->find('list',array('fields'=>array('ticket_bgv','ticket_bgv'),'conditions'=>array("ticket_bgv ='1' and user_id='$userid'")));

            // if(!empty($costid_list1))
            // {
            //     $condition['trigger_type']= 'email_id';

            // } if(!empty($costid_list2))
            // {
            //     $condition['trigger_type']= 'bio_id';
            // }      
            // if(!empty($costid_list3))
            // {
            //     $condition['trigger_type']= 'partner_id_req';

            // } if(!empty($costid_list4))
            //     {
            //         $condition['trigger_type']= 'bgv';
            //     }     

            // print_r($costid_list1);
            // print_r($costid_list2);
            // print_r($costid_list3);
            // print_r($costid_list4);die;

            //$cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('id'=>$costid_list)));

            //$cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('id'=>$costid_list)));
            //print_r($cost_list);die;

            //$condition['cost_center'] = $cost_list;

            $data = $this->EmpOnService->find('all',array('conditions'=>$condition,'order'=>array('branch','emp_code')));

            if(!empty($data))
            {
                $DataArr = array();
                foreach($data as $d)
                {
                    $costcenter = $d['EmpOnService']['cost_center'];
                    $empcode = $d['EmpOnService']['emp_code'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->EmpOnService->query($qry);
                    //print_r($costdata);die;
                    $process_name = $costdata[0]['cost_master']['process_name'];
                    if($process_name == '')
                    {
                        $process['costcentername'] = $costdata[0]['cost_master']['CostCenterName'];
                    }else{
                        $process['costcentername'] = $costdata[0]['cost_master']['process_name'];
                    }

                    // $DataArr[]=$d;
                    $qry_name = "select * from masjclrentry mas where EmpCode='$empcode'  limit 1";
                    $empdata   =   $this->EmpOnService->query($qry_name);
                    $process['emp_name'] = $empdata[0]['mas']['EmpName'];


                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }

            }

            $this->set('data',$DataArr);
            $this->set('costcenter',$CostCenter);
        }  
    }

    


    public function left_alert(){
        $this->layout='home';

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("(ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1') and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        // $branchName = $this->Session->read('branch_name');
        // if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
        //     $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        //     $this->set('branchName',$BranchArray);
        // }
        // else{
        //     $this->set('branchName',array($branchName=>$branchName)); 
        // }

        $ticket_arr = $this->OnboardLeaveAlert->find("all");

        if(!empty($ticket_arr))
        {
            $DataArr = array();
                foreach($ticket_arr as $d)
                {
                    $costcenter = $d['OnboardLeaveAlert']['cost_center'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->OnboardLeaveAlert->query($qry);

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
        
        $this->set('ticket_arr',$DataArr);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['Tickets'];

                $branch_name = $mail['branch'];
                $costcenter = $this->request->data['CostCenter'];
                $type_trigger = $mail['trigger_type'];
                
                $exist_mail = $this->OnboardLeaveAlert->find('first',array('conditions'=>array('branch'=>$branch_name,'cost_center'=>$costcenter,'trigger_type'=>$type_trigger)));

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
                    $this->redirect(array('controller'=>'Tickets','action'=>'left_alert'));
                }
                else
                {
                    $data['cost_center'] = $costcenter;
                    $data['created_at'] = date('Y-m-d H:i:s');

                    $this->OnboardLeaveAlert->save($data);
                    $this->Session->setFlash('Alert Added Successfully');
                    $this->redirect(array('controller'=>'Tickets','action'=>'left_alert'));
                }

            }

        }     
    }

    public function join_alert(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("(ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1') and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        // $branchName = $this->Session->read('branch_name');
        // if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
        //     $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        //     $this->set('branchName',$BranchArray);
        // }
        // else{
        //     $this->set('branchName',array($branchName=>$branchName)); 
        // }

        $ticket_arr = $this->OnboardJoinAlert->find("all");

        if(!empty($ticket_arr))
        {
            $DataArr = array();
            foreach($ticket_arr as $d)
            {
                $costcenter = $d['OnboardJoinAlert']['cost_center'];

                $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                $costdata   =   $this->OnboardJoinAlert->query($qry);
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
        $this->set('ticket_arr',$DataArr);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['Tickets'];

                $branch_name = $mail['branch'];
                $costcenter = $this->request->data['CostCenter'];
                $type_trigger = $mail['trigger_type'];
                
                $exist_mail = $this->OnboardJoinAlert->find('first',array('conditions'=>array('branch'=>$branch_name,'cost_center'=>$costcenter,'trigger_type'=>$type_trigger)));

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
                    $this->redirect(array('controller'=>'Tickets','action'=>'join_alert'));
                }
                else
                {
                    $data['cost_center'] = $costcenter;
                    $data['created_at'] = date('Y-m-d H:i:s');

                    $this->OnboardJoinAlert->save($data);
                    $this->Session->setFlash('Alert Added Successfully');
                    $this->redirect(array('controller'=>'Tickets','action'=>'join_alert'));
                }

            }

        }     
    }



    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            //$conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['branch']=$_REQUEST['BranchName'];}else{unset($conditoin['branch']);}

            $userid = $this->Session->read("userid");
            $costid_list = $this->AccessPages->find('list',array('fields'=>array('cost_id','cost_id'),'conditions'=>array("(ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1') and user_id='$userid'")));
            $cost_list = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('id'=>$costid_list)));
            //print_r($cost_list);die;
            $conditoin['cost_center'] = $cost_list;
            $conditoin['active'] = '1';

            $data = $this->CostCenterMaster->find("list",array('fields'=>array('cost_center','cost_center'),"conditions"=>$conditoin,'group'=>array('cost_center')));
            //print_r($cost_master);die;
            
            //$data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));

            //print_r($data);die;


            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                //echo "<option value='ALL'>All</option>";
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

    public function delete_alert()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];
    
            $this->EmpOnService->query("DELETE FROM emp_onboard_leave_alert WHERE id='{$_REQUEST['Id']}'");   
    
        }
        $this->redirect(array('controller'=>'Tickets','action'=>'left_alert'));
    }

    public function delete_join_alert()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];
    
            $this->EmpOnService->query("DELETE FROM emp_onboard_join_alert WHERE id='{$_REQUEST['Id']}'");   
    
        }
        $this->redirect(array('controller'=>'Tickets','action'=>'join_alert'));

    }



    public function close_ticket(){
        $this->layout='home';
          //print_r($_REQUEST);die;
        if(isset($_REQUEST['data']['Tickets']) && $_REQUEST['data']['Tickets'])
        {
            $file = $_FILES['file'];
            //print_r($_FILES);die;
            $data = $_REQUEST['data']['Tickets'];

            $size = $_FILES['data']['size']['Tickets']['file'];
            //echo $size;die;
            $ext = pathinfo($_FILES['data']['name']['Tickets']['file'], PATHINFO_EXTENSION);

            $emp_tic       =   $this->EmpOnService->find('first',array('conditions'=>array('id'=>$data['close_id'])));
            $trigger_type    =   $emp_tic['EmpOnService']['trigger_type'];
            $type    =   $emp_tic['EmpOnService']['type']; 

            $emp_code = $emp_tic['EmpOnService']['emp_code']; 
            
            if($type == 'joiner')
            {
                $file_name = 'Create_'.date('Y-m-d-h-i-s').'.'.$ext;
                $search_string = "A user account was created.";

            }else{

                $file_name = 'Delete_'.date('Y-m-d-h-i-s').'.'.$ext;
                $search_string = "A user account was deleted.";
            }


            
            $path = '/var/www/html/mascallnetnorth.in/ispark/app/webroot/leave_crone/log/' .$file_name;
            move_uploaded_file($_FILES['data']['tmp_name']['Tickets']['file'], $path);

            $file_path = "http://mascallnetnorth.in/ispark/app/webroot/leave_crone/log/".$file_name;

            
            

            $tic_id = $data['close_id'];
            $log = $data['log'];
            $ad_id = $_REQUEST['ad_id'];

            $email_id = $_REQUEST['email_id'];
            $bgv_color = $_REQUEST['bgv_color'];
            $partner_id = $_REQUEST['partner_id'];
            

            $User_Id        =   $this->Session->read('userid');


            $dataArr=array(

                'ticket_close_by'=>"'".$User_Id."'",
                'ticket_close_at'=>"'".date('Y-m-d H:i:s')."'",
                'ticket_status'=>"'0'",
                'close_remarks'=>"'".$log."'",
                'log'=>"'".$file_path."'"
            );



            if($trigger_type == 'ad_id')
            {
                unset($dataArr['ticket_status']);
                unset($dataArr['ticket_close_at']);
                unset($dataArr['ticket_close_by']);
            }

            if($ad_id != "" && $email_id != "")
            {
                $dataArr['ad_id'] = "'".$ad_id."'";
                $dataArr['email_id'] = "'".$email_id."'";
            }

            if($bgv_color != "")
            {
                $dataArr['bgv_color'] = "'".$bgv_color."'";
            }

            if($partner_id != "")
            {
                $dataArr['partner_id'] = "'".$partner_id."'";
            }
            
            if($size >= 5242880)
            {
                $this->Session->setFlash('File Size too large');

            }
            if($trigger_type == 'ad_id')
            {
                $return = false;
                $log_file_path = "/var/www/html/mascallnetnorth.in/ispark/app/webroot/leave_crone/";
                $file_loc = "$log_file_path/log/$file_name";
                $content = strtolower(file_get_contents($file_loc));

                if (strpos($content, strtolower($emp_code)) !== false && strpos($content, strtolower($search_string)) !== false) {
                    $return = true;
                    //$return['file_list'][] = $file_loc;
                }
                if($return)
                {
                    $dataArr['ticket_status']= "'0'";
                    $dataArr['ticket_close_at']= "'".date('Y-m-d H:i:s')."'";
                    $dataArr['ticket_close_by']= "'".$User_Id."'";
                }else{
                    //$this->Session->setFlash('File Not Match With MASCODE');
                    echo "File Not Match With MASCODE";die;
                }
            }
            $save = $this->EmpOnService->updateAll($dataArr,array('id'=>$tic_id));
            echo "Ticket Close Successfully";die;

             //echo "1";die;
             //$this->redirect(array('controller'=>'Tickets','action'=>'index'));
            
        }

    }

    

    public function report()
    {
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
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("(ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1') and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !="")
        {

            //print_r($_REQUEST);die;
            $wheretag = '';
            if($_REQUEST['BranchName'] !="ALL")
            {
                $wheretag .= " and branch = '{$_REQUEST['BranchName']}'";
            }
           
            if($_REQUEST['EmpCode'] !="")
            {
                $wheretag .= " and emp_code = '{$_REQUEST['EmpCode']}'";
            }
     
            if($_REQUEST['CostCenter'] !="ALL" && $_REQUEST['CostCenter'] !="")
            {
                $wheretag .= " and cost_center = '{$_REQUEST['CostCenter']}'";

            }
            if($_REQUEST['status'] !="")
            {
                $wheretag .= " and ticket_status = '{$_REQUEST['status']}'";
            }
            if($_REQUEST['trigger_type'] !="" && $_REQUEST['trigger_type'] !="All")
            {
                $wheretag .= " and trigger_type = '{$_REQUEST['trigger_type']}'";
            }
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
            #echo $qry;die;
            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from emp_onboard_trigger_services where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag";
            $data_arr   =   $this->EmpOnService->query($qry);
            # print_r($data_arr);die;
            #echo $qry;die;
            #echo $_REQUEST['trigger_type'];die;

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $costcenter = $d['emp_onboard_trigger_services']['cost_center'];
                    $empcode = $d['emp_onboard_trigger_services']['emp_code'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->EmpOnService->query($qry);

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);
                    //print_r($costdata);die;
                    $process_name = $costdata[0]['cost_master']['process_name'];
                    if($process_name == '')
                    {
                        $process['costcentername'] = $costdata[0]['cost_master']['CostCenterName'];
                    }else{
                        $process['costcentername'] = $costdata[0]['cost_master']['process_name'];
                    }

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Ticket No</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>CostCenter Name</th>
                            <th>Type</th>
                            <th>To</th>
                            <th>Cc</th>
                            <th>Bcc</th>
                            <!-- <th>Mail Attempt</th>
                            <th>Last Mail Time</th>
                            <th>Last Mail Status</th> -->
                            <th>Ticket Status</th>
                            <th>Create Date</th>
                            <th>Ticket Close Date</th>
                            <th>Variance</th>
                            <th>Log</th>
                            <th>Ad Id</th>
                            <th>Email Id</th>
                            <th>Bgv Color</th>
                            <th>Partner Id</th>
                            <th>Log File</th>
                            
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){ 
                            $ticket_close_date = $data['emp_onboard_trigger_services']['ticket_close_at'];
                            if(!empty($ticket_close_date))
                            {

                                $create_date = date("Y-m-d",strtotime($data['emp_onboard_trigger_services']['created_at']));
                                $close_date = date("Y-m-d",strtotime($ticket_close_date));
                                $create_date1 = strtotime($create_date);
                                $close_date1 = strtotime($close_date);
                                #$variance = abs($difference/(60 * 60)/24);
                                $total_days = round(($close_date1 - $create_date1)/(24*60*60));

                                $variance  = $total_days." days.</p>";

                            }
                            
                            ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['ticket_no'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['emp_code'];?></td>
                            <td><?php echo $data['empname'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['branch'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['cost_center'];?></td>
                            <td><?php echo $data['costcentername'];?></td>
                            <td><?php if($data['emp_onboard_trigger_services']['type'] == 'leaver'){ echo "Leaver";}else{echo "Joiner";}?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['to'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['cc'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['bcc'];?></td>
                            <!-- <td><?php //echo $data['emp_onboard_trigger_services']['mail_attempt'];?></td>
                            <td><?php //if(!empty($data['emp_onboard_trigger_services']['last_mail_time'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['last_mail_time']),"d-M-Y H:i:s"); }?></td>
                            <td><?php //echo $data['emp_onboard_trigger_services']['last_mail_status'];?></td> -->
                            <td><?php if($data['emp_onboard_trigger_services']['ticket_status'] == '1'){ echo "Open";}else{ echo "Close";}?></td>
                            <td><?php if(!empty($data['emp_onboard_trigger_services']['created_at'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['created_at']),"d-M-Y"); } ?></td>
                            <td><?php if(!empty($data['emp_onboard_trigger_services']['ticket_close_at'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['ticket_close_at']),"d-M-Y"); }?></td>
                            <td><?php if(!empty($ticket_close_date)) { echo $variance; } ?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['close_remarks'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['ad_id'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['email_id'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['bgv_color'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['partner_id'];?></td>
                            <td><a target='_blank' href="<?php echo $data['emp_onboard_trigger_services']['log'];?>" download>Log File</a></td>
                            
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
                $wheretag .= " and branch = '{$_REQUEST['BranchName']}'";
            }
           
            if($_REQUEST['EmpCode'] !="")
            {
                $wheretag .= " and emp_code = '{$_REQUEST['EmpCode']}'";
            }
     
            if($_REQUEST['CostCenter'] !="ALL" && $_REQUEST['CostCenter'] !="")
            {
                $wheretag .= " and cost_center = '{$_REQUEST['CostCenter']}'";

            }
            if($_REQUEST['status'] !="")
            {
                $wheretag .= " and ticket_status = '{$_REQUEST['status']}'";
            }
            if($_REQUEST['trigger_type'] !="" && $_REQUEST['trigger_type'] !="All")
            {
                $wheretag .= " and trigger_type = '{$_REQUEST['trigger_type']}'";
            }
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));

            //print_r($_REQUEST);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from emp_onboard_trigger_services where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag";
            $data_arr   =   $this->EmpOnService->query($qry);
            // print_r($data_arr);die;

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $costcenter = $d['emp_onboard_trigger_services']['cost_center'];
                    $empcode = $d['emp_onboard_trigger_services']['emp_code'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->EmpOnService->query($qry);

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);
                    //print_r($costdata);die;
                    $process_name = $costdata[0]['cost_master']['process_name'];
                    if($process_name == '')
                    {
                        $process['costcentername'] = $costdata[0]['cost_master']['CostCenterName'];
                    }else{
                        $process['costcentername'] = $costdata[0]['cost_master']['process_name'];
                    }

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=ticket_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                ?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  border="1">     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Ticket No</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>CostCenter Name</th>
                            <th>Type</th>
                            <th>To</th>
                            <th>Cc</th>
                            <th>Bcc</th>
                            <!-- <th>Mail Attempt</th>
                            <th>Last Mail Time</th>
                            <th>Last Mail Status</th> -->

                            <th>Ticket Status</th>
                            <th>Create Date</th>
                            <th>Ticket Close Date</th>
                            <th>Variance</th>
                            <th>Log</th>
                            <th>Ad Id</th>
                            <th>Email Id</th>
                            <th>Bgv Color</th>
                            <th>Partner Id</th>
                            
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){ 
                            $ticket_close_date = $data['emp_onboard_trigger_services']['ticket_close_at'];
                            if(!empty($ticket_close_date))
                            {

                                $create_date = date("Y-m-d",strtotime($data['emp_onboard_trigger_services']['created_at']));
                                $close_date = date("Y-m-d",strtotime($ticket_close_date));
                                $create_date1 = strtotime($create_date);
                                $close_date1 = strtotime($close_date);
                                #$variance = abs($difference/(60 * 60)/24);
                                $total_days = round(($close_date1 - $create_date1)/(24*60*60));

                                $variance  = $total_days." days.</p>";

                            }
                            ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['ticket_no'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['emp_code'];?></td>
                            <td><?php echo $data['empname'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['branch'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['cost_center'];?></td>
                            <td><?php echo $data['costcentername'];?></td>
                            <td><?php if($data['emp_onboard_trigger_services']['type'] == 'leaver'){ echo "Leaver";}else{echo "Joiner";}?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['to'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['cc'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['bcc'];?></td>
                            <!-- <td><?php //echo $data['emp_onboard_trigger_services']['mail_attempt'];?></td>
                            <td><?php //if(!empty($data['emp_onboard_trigger_services']['last_mail_time'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['last_mail_time']),"d-M-Y H:i:s"); }?></td>
                            <td><?php //echo $data['emp_onboard_trigger_services']['last_mail_status'];?></td> -->
                            <td><?php if($data['emp_onboard_trigger_services']['ticket_status'] == '1'){ echo "Open";}else{ echo "Close";}?></td>
                            <td><?php if(!empty($data['emp_onboard_trigger_services']['created_at'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['created_at']),"d-M-Y"); } ?></td>
                            <td><?php if(!empty($data['emp_onboard_trigger_services']['ticket_close_at'])){ echo date_format(date_create($data['emp_onboard_trigger_services']['ticket_close_at']),"d-M-Y"); }?></td>
                            <td><?php if(!empty($ticket_close_date)) { echo $variance; } ?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['close_remarks'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['ad_id'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['email_id'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['bgv_color'];?></td>
                            <td><?php echo $data['emp_onboard_trigger_services']['partner_id'];?></td>
                            
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array_merge($condition,array('Status'=>1)))); 
        }

    }

    public function dashboard(){
        $this->layout='home';
        
        $active_emp = $this->Masjclrentry->query("SELECT COUNT(1) active_emp FROM `masjclrentry`  WHERE STATUS='1'");
        $this->set('active_emp',$active_emp[0][0]);

        $four_absent_emp = $this->ContinuouslyLeave->query("SELECT COUNT(1) absent_emp FROM `continuously_leave`  WHERE leave_status='0' and left_status='0'");
        $this->set('four_absent_emp',$four_absent_emp[0][0]);

        $total_dlt = $this->EmpOnService->query("SELECT SUM(IF(trigger_type ='bio_id',1,0)) `bio_deletion`,SUM(IF(trigger_type ='email_id',1,0)) `ad_deletion`
         FROM emp_onboard_trigger_services WHERE  TYPE='leaver'");
        $this->set('total_dlt',$total_dlt[0][0]);

        $total_bgv = $this->EmpOnService->query("SELECT COUNT(1) bgv_tic  FROM emp_onboard_trigger_services where trigger_type = 'bgv'");
        $this->set('total_bgv',$total_bgv[0][0]);

        $total_this_month_delete = $this->EmpOnService->query("SELECT SUM(IF(trigger_type ='bio_id',1,0)) `bio_del_month`,SUM(IF(trigger_type ='ad_id',1,0)) `ad_del_month`,SUM(IF(trigger_type ='partner_id_req',1,0)) `partner_del_month`  FROM emp_onboard_trigger_services where TYPE='leaver' and MONTH(created_at) = MONTH(CURRENT_DATE())");
        //print_r($total_this_month);die;
        $this->set('total_month_deletion',$total_this_month_delete[0][0]);

        $total_this_month_create = $this->EmpOnService->query("SELECT SUM(IF(trigger_type ='bgv',1,0)) `bgv_create_month`,SUM(IF(trigger_type ='ad_id',1,0)) `ad_del_month`,SUM(IF(trigger_type ='partner_id_req',1,0)) `partner_del_month`  FROM emp_onboard_trigger_services where TYPE='joiner' and MONTH(created_at) = MONTH(CURRENT_DATE())");
        $this->set('total_month_creation',$total_this_month_create[0][0]);       

        $bgv_api = $this->ContinuouslyLeave->query("SELECT COUNT(1) tot_bgv FROM `bgv_api`");
        $this->set('bgv_api',$bgv_api[0][0]);

        $bgv_green = $this->ContinuouslyLeave->query("SELECT COUNT(1) bgv_green FROM `bgv_api` where color='Green'");
        $this->set('bgv_green',$bgv_green[0][0]);
        //print_r($total_this_month_create);die;

        $tic_four_hour = $this->ContinuouslyLeave->query("SELECT COUNT(1) four_hour FROM emp_onboard_trigger_services
        WHERE created_at <=DATE_SUB(NOW(),INTERVAL 4 HOUR) AND created_at >=DATE_SUB(NOW(),INTERVAL 7 HOUR) AND ticket_status='1'");
        $this->set('four_hour',$tic_four_hour[0][0]);

        $tic_eight_hour = $this->ContinuouslyLeave->query("SELECT COUNT(1) eight_hour FROM emp_onboard_trigger_services
        WHERE created_at <=DATE_SUB(NOW(),INTERVAL 8 HOUR) AND created_at >=DATE_SUB(NOW(),INTERVAL 23 HOUR) AND ticket_status='1'");
        $this->set('eight_hour',$tic_eight_hour[0][0]);

        $tic_twentyfour_hour = $this->ContinuouslyLeave->query("SELECT COUNT(1) twenty_four_hour FROM emp_onboard_trigger_services
        WHERE created_at <=DATE_SUB(NOW(),INTERVAL 24 HOUR)  AND ticket_status='1'");
        $this->set('twentyfour_hour',$tic_twentyfour_hour[0][0]);
        
        

    }

    public function edit_join_alert(){

        $this->layout='home'; 
        
        $userid = $this->Session->read("userid");

        $ticket_arr = $this->OnboardJoinAlert->find("all");

        if(!empty($ticket_arr))
        {
            $DataArr = array();
            foreach($ticket_arr as $d)
            {
                $costcenter = $d['OnboardJoinAlert']['cost_center'];

                $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                $costdata   =   $this->OnboardJoinAlert->query($qry);
                //print_r($costdata);die;
                $process_name = $costdata[0]['cost_master']['process_name'];
                if($process_name == '')
                {
                    $process['costcentername'] = $costdata[0]['cost_master']['CostCenterName'];
                }else{
                    $process['costcentername'] = $costdata[0]['cost_master']['process_name'];
                }

 
                $DataArr[] = array_merge($d,$process);
        
            }
        }
        $this->set('ticket_arr',$DataArr);
    }

    public function update_join_alert(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }

        $id = $this->request->query['id'];
        
        $Onboard_alert =$this->OnboardJoinAlert->find('first',array('conditions'=>array('id'=>$id)));

        $this->set('onboard_alert',$Onboard_alert);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['Tickets'];
                $update_id = $this->request->data['join_alert_id'];
                $branch_name = $mail['branch'];
                $costcenter = $this->request->data['CostCenter'];
                $type_trigger = $mail['trigger_type'];

                foreach($mail as $k=>$v)
                {
                    
                    $data[$k] = "'".addslashes($v)."'";
                    
                }


                $data['cost_center'] = "'".$costcenter."'";
                $data['updated_by'] = "'".$userid."'";
                $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                $save = $this->OnboardJoinAlert->updateAll($data,array('id'=>$update_id));
                if($save){
                    $this->Session->setFlash('Alert Updated Successfully');
                    $this->redirect(array('controller'=>'Tickets','action'=>'edit_join_alert'));
                }else{
                    $this->Session->setFlash('Alert Not Updated');
                    $this->redirect(array('controller'=>'Tickets','action'=>'update_join_alert'));
                }
                
            

            }

        }     
    }

    public function edit_left_alert(){
        $this->layout='home';

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }

        $ticket_arr = $this->OnboardLeaveAlert->find("all");

        if(!empty($ticket_arr))
        {
            $DataArr = array();
                foreach($ticket_arr as $d)
                {
                    $costcenter = $d['OnboardLeaveAlert']['cost_center'];

                    $qry = "select * from cost_master where cost_center='$costcenter' and active ='1' limit 1";
                    $costdata   =   $this->OnboardLeaveAlert->query($qry);
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
        
        $this->set('ticket_arr',$DataArr);
           
    }

    public function update_left_alert(){
        $this->layout='home';

        $userid = $this->Session->read("userid");
        $costid_list = $this->AccessPages->find('list',array('fields'=>array('branch_id','branch_id'),'conditions'=>array("ticket_email ='1' or ticket_bio ='1' or ticket_partner ='1' or ticket_bgv ='1' and user_id='$userid'")));

        if(!empty($costid_list)){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'id'=>$costid_list),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }

        $id = $this->request->query['id'];
        
        $Onboard_alert =$this->OnboardLeaveAlert->find('first',array('conditions'=>array('id'=>$id)));
        //print_r($Onboard_alert);die;
        
        $this->set('onboard_alert',$Onboard_alert);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['Tickets'];

                $branch_name = $mail['branch'];
                $costcenter = $this->request->data['CostCenter'];
                $type_trigger = $mail['trigger_type'];

                $left_alert_id= $this->request->data['left_alert_id'];
                

                foreach($mail as $k=>$v)
                {
                
                    $data[$k] = "'".addslashes($v)."'";
                    
                }
               
                $data['cost_center'] = "'".$costcenter."'";
                $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                $data['updated_by'] = "'".$userid."'";
                
                // echo $left_alert_id;
                // print_r($data);die;
                //$this->OnboardLeaveAlert->save($data);
                $save = $this->OnboardLeaveAlert->updateAll($data,array('id'=>$left_alert_id));
                
                if($save){
                    $this->Session->setFlash('Alert Updated Successfully');
                    $this->redirect(array('controller'=>'Tickets','action'=>'edit_left_alert'));
                }else{
                    $this->Session->setFlash('Alert Not Updated');
                    $this->redirect(array('controller'=>'Tickets','action'=>'update_left_alert'));
                }
                

            }

        }     
    }

    
      
}
?>