<?php
class PliSystemsController extends AppController {
    public $uses = array('AccessPages','CostCenterMaster','Addbranch','BusinessTickets','User','DepartmentNameMaster','BusinessCommunity','BusinessGratitude','Masjclrentry','EmpOnService','OnboardLeaveAlert','PliRule','CustomerChat');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','dashboard','dashboard_new','getempname','delete_rule','rule','close_ticket','report','export_report','apply_rule','business_community','export_business_community',
        'business_gratitude','export_business_gratitude','update_rule','getcostcenter');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }

        $this->CustomerChat->useDbConfig = 'dialdee';
    }


    public function index(){
        $this->layout='home';

        $userid = $this->Session->read("userid");
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
        
        $department=$this->DepartmentNameMaster->find('list',array('fields'=>array('Department','Department'),'conditions'=>array('Status'=>1),'order'=>array('Department')));
        
        $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        $this->set('department',array_merge(array('ALL'=>'ALL'),$department));


        if($this->request->is('Post'))
        {
            #print_r($this->request->data);die;
            $branch_name    =   $this->request->data['BusinessRules']['branch_name'];
            $department = $this->request->data['BusinessRules']['department'];
            $status = $this->request->data['BusinessRules']['status'];


            if($branch_name !="ALL"){$condition['branch']=$branch_name;}else{unset($condition['branch']);}
            if($department !="ALL" && $department !=""){$condition['department']=$department;}else{unset($condition['department']);}
            if($status != ""){$condition['ticket_status']=$status;}else{unset($condition['ticket_status']);}

            #print_r($condition);die;
            $data = $this->BusinessTickets->find('all',array('conditions'=>$condition,'order'=>array('branch','emp_code')));

            #print_r($data);die;

            if(!empty($data))
            {
                $DataArr = array();
                foreach($data as $d)
                {
                    
                    $empcode = $d['BusinessTickets']['emp_code'];

                    // $DataArr[]=$d;
                    $qry_name = "select * from masjclrentry mas where EmpCode='$empcode'  limit 1";
                    $empdata   =   $this->BusinessTickets->query($qry_name);
                    $process['emp_name'] = $empdata[0]['mas']['EmpName'];


                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }

            }
            

            $this->set('data',$DataArr);
            $this->set('costcenter',$CostCenter);
        }  
    }

    

    public function rule(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");


        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
        
        $department=$this->DepartmentNameMaster->find('list',array('fields'=>array('Department','Department'),'conditions'=>array('Status'=>1),'order'=>array('Department')));

        $options = [];
        for ($i = 1; $i <= 30; $i++) {
            $options[$i] = $i;
        }

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('options',$options);
        $this->set('per_options',$per_options);

        $this->set('department',$department);
        $this->set('branchName',$BranchArray);

        $plirule_arr = $this->PliRule->find("all");
        #print_r($plirule_arr);die;


        $this->set('plirule_arr',$plirule_arr);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
             
                $data = array();
                $mail = $this->request->data;
                #print_r($this->request->data);die;
                $data = $mail['PliSystem'];

                $target_date = $data['target_date'];
                $growth_date = $data['growth_date'];
                $basic_date = $data['basic_date'];
                $deduction = $data['deduction'];

                $exist_rule = $this->PliRule->find('first',array('conditions'=>array('status'=>1)));
                if($exist_rule)
                {
                    $data['status'] = 0;
                }
                
                $data['target_date'] = $target_date;
                $data['growth_date'] = $growth_date;
                $data['basic_date'] = $basic_date;
                $data['deduction'] = $deduction;
                $data['created_at'] = date('Y-m-d H:i:s');
                #print_r($data);die;
                $this->PliRule->save($data);
                $this->Session->setFlash('Rule Added Successfully');
                $this->redirect(array('controller'=>'PliSystems','action'=>'rule'));
                

            }

        }     
    }



    public function getempname(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            //$conditoin=array('Status'=>1);
            $branch = '';
            if($_REQUEST['BranchName'] !="ALL"){
                $conditoin['BranchName']=$_REQUEST['BranchName'];
                $branch = $_REQUEST['BranchName'];
            }else{
                unset($conditoin['BranchName']);
            }

            $department = $_REQUEST['department'];

            $conditoin['status'] = '1';
            
            $data = $this->Masjclrentry->find("list",array('fields'=>array('EmpCode','EmpName'),"conditions"=>$conditoin,'order'=>array('EmpName')));
            #print_r($data);die;
            
            $RolArr=array();
            $prArr = $this->BusinessRule->find('first',array('fields'=>array('emp_rights'),'conditions'=>array('branch'=>$branch,'department'=>$department)));
            if(!empty($prArr)){
                $RolArr= explode(',', $prArr['BusinessRule']['emp_rights']);
            }
            
            if(!empty($data)){
                foreach($data as $emp_code => $row){

                    if (in_array($emp_code, $RolArr)){
                        echo "<input class='checkbox1' type='checkbox' value='$emp_code' name='Empname[]' checked > $row <br/>";
                    }
                    else{
                         echo "<input class='checkbox1' type='checkbox' value='$emp_code' name='Empname[]' > $row <br/>";
                    }
                }
            }
            else{
                echo "";
            }
            
            
        }die;
        
        
    }

    public function delete_rule()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];
            #echo $id;die;
            
            $this->PliRule->query("DELETE FROM pli_rule WHERE id='{$_REQUEST['Id']}'");   
    
        }
        $this->redirect(array('controller'=>'PliSystems','action'=>'rule'));

    }



    public function close_ticket(){
        
        if(isset($_REQUEST['close_id']) && $_REQUEST['close_id'])
        {

            $tic_id = $_REQUEST['close_id'];
            $remarks = $_REQUEST['remarks'];

            $User_Id        =   $this->Session->read('userid');

            $dataArr=array(

                'ticket_close_by'=>"'".$User_Id."'",
                'ticket_close_at'=>"'".date('Y-m-d H:i:s')."'",
                'ticket_status'=>"'0'",
                'close_remarks'=>"'".$remarks."'"
            );

            #print_r($dataArr);die;

            $save = $this->BusinessTickets->updateAll($dataArr,array('id'=>$tic_id));
            if($save)
            {
                echo "1";
            }else{
                echo "0";
            }die;

            
        }

    }

    public function apply_rule(){

        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];
            #echo $id;die;
            $this->PliRule->query("UPDATE pli_rule SET status = 1 WHERE id = '{$id}'");
            $this->PliRule->query("UPDATE pli_rule SET status = 0 WHERE id != '{$id}'");
    
        }
        $this->redirect(array('controller'=>'PliSystems','action'=>'rule'));
    }

    public function update_rule(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));

        $this->set('branchName',$BranchArray);
        

        $id = $this->request->query['id'];
        
        $business_rule =$this->BusinessRule->find('first',array('conditions'=>array('id'=>$id)));

        $this->set('business_rule',$business_rule);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $data = array();
                $mail = $this->request->data;
                $mail = $mail['BusinessRules'];
                $update_id = $this->request->data['rule_id'];
                $branch_name = $mail['branch'];
                $costcenter = $this->request->data['costcenter'];
                $type_trigger = $mail['type'];

                foreach($mail as $k=>$v)
                {
                    
                    $data[$k] = "'".addslashes($v)."'";
                    
                }


                $data['costcenter'] = "'".$costcenter."'";
                $data['updated_by'] = "'".$userid."'";
                $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                $save = $this->BusinessRule->updateAll($data,array('id'=>$update_id));
                if($save){
                    $this->Session->setFlash('Rule Updated Successfully');
                    $this->redirect(array('controller'=>'BusinessRules','action'=>'rule'));
                }else{
                    $this->Session->setFlash('Rule Not Updated');
                    $this->redirect(array('controller'=>'BusinessRules','action'=>'update_rule'));
                }
                
            

            }

        }     
    }


    public function business_community(){
        $this->layout='home';

        $userid = $this->Session->read("userid");

        if($this->request->is('Post'))
        {
            $wheretag = '';
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
            #echo $qry;die;
            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from business_community where DATE(created_at)>='$from' AND DATE(created_at)<='$to'";
            $data_arr   =   $this->BusinessCommunity->query($qry);

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $empcode = $d['business_community']['emp_code'];

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Mobile No.</th>
                            <th>Remarks</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){ ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['business_community']['emp_code'];?></td>
                            <td><?php echo $data['empname'];?></td>
                            <td><?php echo $data['business_community']['branch'];?></td>
                            <td><?php echo $data['business_community']['department'];?></td>
                            <td><?php echo $data['business_community']['msisdn'];?></td>
                            <td><?php echo $data['business_community']['remarks'];?></td>
                            
                            <td><?php if(!empty($data['business_community']['created_at'])){ echo date_format(date_create($data['business_community']['created_at']),"d-M-Y"); } ?></td>
                            
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
         
        }  
    }

    public function export_business_community()
    {

        if(isset($_REQUEST['From']) && $_REQUEST['From'] !="")
        {
            $wheretag = '';
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
            #echo $qry;die;
            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from business_community where DATE(created_at)>='$from' AND DATE(created_at)<='$to'";
            $data_arr   =   $this->BusinessCommunity->query($qry);

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $empcode = $d['business_community']['emp_code'];

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }
                header("Content-Disposition: attachment; filename=business_community.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                ?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px;">
                <table class = "table table-striped table-hover  responstable" border='1' >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Mobile No.</th>
                            <th>remarks</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){ ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['business_community']['emp_code'];?></td>
                            <td><?php echo $data['empname'];?></td>
                            <td><?php echo $data['business_community']['branch'];?></td>
                            <td><?php echo $data['business_community']['department'];?></td>
                            <td><?php echo $data['business_community']['msisdn'];?></td>
                            <td><?php echo $data['business_community']['remarks'];?></td>
                            
                            <td><?php if(!empty($data['business_community']['created_at'])){ echo date_format(date_create($data['business_community']['created_at']),"d-M-Y"); } ?></td>
                            
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }else{

                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >No Record Found.</span>');
                return $this->redirect(array('controller'=>'BusinessRules','action' => 'business_community'));
            }
            die;
         
        }  

    }


    public function business_gratitude(){
        $this->layout='home';

        $userid = $this->Session->read("userid");

        if($this->request->is('Post'))
        {
            $wheretag = '';
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
            
            $qry = "select * from business_gratitude where DATE(created_at)>='$from' AND DATE(created_at)<='$to'";
            $data_arr   =   $this->BusinessGratitude->query($qry);

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $empcode = $d['business_gratitude']['emp_code'];

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Remarks</th>
                            <th>Attachment</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){?>
                            <tr> 
                                <td><?php echo $n++;?></td>
                                <td><?php echo $data['business_gratitude']['emp_code'];?></td>
                                <td><?php echo $data['empname'];?></td>
                                <td><?php echo $data['business_gratitude']['branch'];?></td>
                                <td><?php echo $data['business_gratitude']['dept'];?></td>
                                <td><?php echo $data['business_gratitude']['remarks'];?></td>
                                <?php if(!empty($data['business_gratitude']['attachment']) && $data['business_gratitude']['attachment']!=""){?>
                                <td><a target='_blank' href="<?php echo $data['business_gratitude']['attachment']; ?>">Attachment</a></td>
                                <?php }else {?>
                                    <td></td>
                                <?php }?>
                                <td><?php if(!empty($data['business_gratitude']['created_at'])){ echo date_format(date_create($data['business_gratitude']['created_at']),"d-M-Y"); } ?></td>
                            </tr>
                        <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
         
        }  
    }


    public function export_business_gratitude()
    {

        if(isset($_REQUEST['From']) && $_REQUEST['From'] !="")
        {
            $wheretag = '';
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
            #echo $qry;die;
            //print_r($condition);die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array($condition))); 
            $qry = "select * from business_gratitude where DATE(created_at)>='$from' AND DATE(created_at)<='$to'";
            $data_arr   =   $this->BusinessGratitude->query($qry);

            if(!empty($data_arr))
            {
                $DataArr = array();
                foreach($data_arr as $d)
                {
                    $empcode = $d['business_gratitude']['emp_code'];

                    $qry1 = "select * from masjclrentry where EmpCode='$empcode'  limit 1";
                    $employee_dt   =   $this->EmpOnService->query($qry1);

                    $process['empname'] = $employee_dt[0]['masjclrentry']['EmpName'];

                    // $DataArr[]=$d;

                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }
                header("Content-Disposition: attachment; filename=business_gratitude.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                ?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px;">
                <table class = "table table-striped table-hover  responstable" border="1">
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Remarks</th>
                            <th>Attachment</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($DataArr as $data){ ?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['business_gratitude']['emp_code'];?></td>
                            <td><?php echo $data['empname'];?></td>
                            <td><?php echo $data['business_gratitude']['branch'];?></td>
                            <td><?php echo $data['business_gratitude']['dept'];?></td>
                            <td><?php echo $data['business_gratitude']['remarks'];?></td>
                            <td><?php echo $data['business_gratitude']['attachment'];?></td>
                            
                            <td><?php if(!empty($data['business_gratitude']['created_at'])){ echo date_format(date_create($data['business_gratitude']['created_at']),"d-M-Y"); } ?></td>
                            
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }else{

            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >No Record Found.</span>');
            return $this->redirect(array('controller'=>'BusinessRules','action' => 'business_gratitude'));
            }
            die;
         
        }  

    }

    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            //$conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['branch']=$_REQUEST['BranchName'];}else{unset($conditoin['branch']);}

            $userid = $this->Session->read("userid");
            
            $conditoin['active'] = '1';

            $data = $this->CostCenterMaster->find("list",array('fields'=>array('cost_center','cost_center'),"conditions"=>$conditoin,'group'=>array('cost_center')));
            
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

    public function dashboard(){
        $this->layout='home';
    

        $department=$this->BusinessTickets->find('list',array('fields'=>array('department','department'),'order'=>array('department')));
        $this->set('department',array_merge(array('ALL'=>'ALL'),$department));


        #$qry = "select * from chat_customer where client_id='533' and interaction_type='bot' and (customer_name IS Not NULL && customer_name!='') and  DATE(created_at)=CURDATE() order by customer_name";
        $qry = "SELECT DATE(created_at) as date, COUNT(customer_no) as count FROM chat_customer WHERE interaction_type='bot' and client_id='533'
          AND DATE(created_at) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() GROUP BY DATE(created_at)";
        $customers_arr   =   $this->CustomerChat->query($qry);
        #print_r($customers_arr);die;
        $dates = [];
        $counts = [];

        foreach($customers_arr as $customer) {
            #print_r($customer);
            $dates[] = $customer[0]['date'];
            $counts[] = $customer[0]['count'];
        }
        #print_r($counts);die;

        $this->set('dates',$dates);
        $this->set('counts',$counts);

        $qry1 = "SELECT HOUR(created_at) AS HOUR, COUNT(*) AS COUNT FROM chat_customer  WHERE interaction_type = 'bot' and client_id='533' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY HOUR ORDER BY HOUR;";
        $customers_hour_arr   =   $this->CustomerChat->query($qry1);

        $hourlyCounts = array_fill(0, 24, 0);
        #$hourlyCounts = array();

        foreach($customers_hour_arr as $hour_arr) {

            $hour = $hour_arr[0]['HOUR'];
            $count = $hour_arr[0]['COUNT'];
            $hourlyCounts[$hour] = $count;
        }

        $this->set('hourlyCounts',$hourlyCounts);

        $where_tag1 = "";
        $where_tag2 = "";
        if($this->request->is('Post'))
        {
            $data = $this->request->data;
            #print_r($data);die;
            $metrics = $data['metrics'];
            $department = $data['department'];

            if($metrics !="ALL"){
                $where_tag1 .= "and DATE(created_at)=DATE_SUB(CURDATE(),INTERVAL {$metrics} DAY)";
                #$wheretag .= " and trigger_type = '{$_REQUEST['trigger_type']}'";
                
            }

            if($department !="ALL"){
                $where_tag2 .= "and  department = '{$department}'";
            
            }
            
        }
    
        $qry2 = "SELECT department, COUNT(*) AS ticket_count FROM business_tickets where 1=1 $where_tag2 $where_tag1 GROUP BY department;";
        $ticket_create_arr   =   $this->BusinessTickets->query($qry2);

        $departmentNames = [];
        $ticketCounts = [];

        $backgroundColors = [] ;
        $totalCount = 0;

        foreach($ticket_create_arr as $ticket) {
            #print_r($ticket);die;
            $departmentNames[] = $ticket['business_tickets']['department'];
            $ticketCounts[] = $ticket[0]['ticket_count'];
            $ticket_total = $ticket[0]['ticket_count'];

            $backgroundColors[] = "rgba(" . rand(0, 255) . "," . rand(0, 255) . "," . rand(0, 255) . ", 0.5)";
            $totalCount += $ticket_total;
        }


        $this->set('backgroundColors',$backgroundColors);
        $this->set('departmentNames',$departmentNames);
        $this->set('ticketCounts',$ticketCounts);
        $this->set('totalCount',$totalCount);


        $qry3 = "SELECT DATE(created_at) AS date, COUNT(ticket_no) AS count FROM `business_tickets` WHERE ticket_status='0' $where_tag1 $where_tag2 AND DATE(created_at) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() GROUP BY DATE(created_at)  ";
        $ticket_close_arr   =   $this->EmpOnService->query($qry3);
        #print_r($ticket_close_arr);die;
        $tic_close_dates = [];
        $tic_close_counts = [];
        $totalcloseCount = 0;
        foreach($ticket_close_arr as $tic_close) 
        {
            #print_r($tic_close);die;
            $tic_close_dates[] = $tic_close[0]['date'];
            $tic_close_counts[] = $tic_close[0]['count'];
            $ticket_close_total = $tic_close[0]['count'];
            $totalcloseCount += $ticket_close_total;
        }
        #print_r($tic_close_counts);die;

        $this->set('tic_close_dates',$tic_close_dates);
        $this->set('tic_close_counts',$tic_close_counts);
        $this->set('totalcloseCount',$totalcloseCount);

    }

    public function dashboard_new()
    {
        $this->layout='home';

        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
        $this->set('branchName',array_merge(array('ALL'=>'ALL LOCATIONS'),$BranchArray));

        $department=$this->BusinessTickets->find('list',array('fields'=>array('department','department'),'order'=>array('department')));
        $this->set('department',array_merge(array('ALL'=>'ALL'),$department));

        $where_tag1 = "";
        $where_tag2 = "";
        $where_tag_grati = "";
        if($this->request->is('Post'))
        {
            $data = $this->request->data;
   
            $branch = $data['branch'];
            $department = $data['department'];

            if($branch !="ALL"){

                $where_tag1 .= "and branch =  '{$branch}'";
                
            }

            if($department !="ALL"){

                $where_tag2 .= "and  department = '{$department}'";
                $where_tag_grati .= "and  dept = '{$department}'";
            
            }
            
        }

        // chart 1 start
    
            $mas_care_qry = "SELECT  COUNT(*) AS mas_care_count FROM business_tickets where 1=1 $where_tag2 $where_tag1;";
            $mas_care_arr   =   $this->BusinessTickets->query($mas_care_qry);

            $gratitude_qry = "SELECT  COUNT(*) AS gratitude_count FROM business_gratitude where 1=1 $where_tag_grati $where_tag1;";
            $gratitude_arr   =   $this->BusinessTickets->query($gratitude_qry);

            $community_qry = "SELECT  COUNT(*) AS community_count FROM business_community where 1=1  $where_tag1;";
            $community_arr   =   $this->BusinessTickets->query($community_qry);

            $this->set('mas_care_count',$mas_care_arr[0][0]['mas_care_count']);
            $this->set('gratitude_count',$gratitude_arr[0][0]['gratitude_count']);
            $this->set('community_count',$community_arr[0][0]['community_count']);

        //chart 1 end

        //chart 2 start

            $community_department = ["Carrier", "Health", "Financial", "Spiritual" ,"Others"];

            $intent_community_qry = "SELECT  * FROM business_community where 1=1  $where_tag1;";
            $intent_community_arr   =   $this->BusinessTickets->query($intent_community_qry);

            $departmentCounts = array();

            foreach($intent_community_arr as $intent_community)
            {
                $remarks = strtolower($intent_community['business_community']['remarks']);
                foreach ($community_department as $department) 
                {
                    $count = substr_count($remarks, strtolower($department));
                    $departmentCounts[$department] += $count;

                }
            }

            $this->set('intent_community_dep_label',json_encode(array_keys($departmentCounts)));
            $this->set('intent_community_dep_count',json_encode(array_values($departmentCounts)));

        //chart 2 end

        //chart 3 start

            $mascare_department = ["Complaint", "Enquiry", "Request"];

            $intent_mascare_qry = "SELECT * FROM business_tickets where 1=1 $where_tag1 $where_tag2 ;";
            $intent_mascare_arr   =   $this->BusinessTickets->query($intent_mascare_qry);

            $mascare_category_Counts = array();

            foreach($intent_mascare_arr as $intent_mascare) 
            {
                $remarks = strtolower($intent_mascare['business_tickets']['body']);
                foreach ($mascare_department as $department) 
                {
                    $count = substr_count($remarks, strtolower($department));
                    $mascare_category_Counts[$department] += $count;
                
                }
            }

            $this->set('mascare_dep_label',json_encode(array_keys($mascare_category_Counts)));
            $this->set('mascare_dep_count',json_encode(array_values($mascare_category_Counts)));

        //chart 3 end

        //chart 4 start

            $tickets_qry = "SELECT * FROM business_tickets where 1=1 $where_tag1 $where_tag2;";
            $ticket_arr   =   $this->BusinessTickets->query($tickets_qry);

            $tickets_count = array();

            foreach($ticket_arr as $ticket) 
            {
                if($ticket['business_tickets']['ticket_status'] == '0')
                {
                    $tickets_count['Close'] += 1;
                }else{
                    $tickets_count['Open'] += 1;
                }
            
            }

            $this->set('ticket_status',json_encode(array_keys($tickets_count)));
            $this->set('ticket_count',json_encode(array_values($tickets_count)));

        //chart 4 end

        //chart 5 start
            $tic_dep_qry = "SELECT department, COUNT(*) AS ticket_count FROM business_tickets where 1=1 $where_tag1 $where_tag2 GROUP BY department;";
            $tic_dep_arr   =   $this->BusinessTickets->query($tic_dep_qry);

            $departmentNames = [];
            $ticketCounts = [];

            foreach($tic_dep_arr as $ticket)
            {
                
                $departmentNames[] = $ticket['business_tickets']['department'];
                $ticketCounts[] = $ticket[0]['ticket_count'];
                $ticket_total = $ticket[0]['ticket_count'];
            }

            $this->set('departmentNames',$departmentNames);
            $this->set('ticketCounts',$ticketCounts);
        //chart 5 end

        //chart 6 start

            $ctic_dep_qry = "SELECT department, COUNT(*) AS ticket_count FROM business_tickets where 1=1 and ticket_status='0' $where_tag1 $where_tag2 GROUP BY department;";
            $ctic_dep_arr   =   $this->BusinessTickets->query($ctic_dep_qry);

            $cdepartmentNames = [];
            $cticketCounts = [];

            foreach($ctic_dep_arr as $cticket) {
                
                $cdepartmentNames[] = $cticket['business_tickets']['department'];
                $cticketCounts[] = $cticket[0]['ticket_count'];
                $ticket_total = $cticket[0]['ticket_count'];
            }

            $this->set('cdepartmentNames',$cdepartmentNames);
            $this->set('cticketCounts',$cticketCounts);

        //chart 6 end


        //chart 7 start

            $otic_dep_qry = "SELECT department, COUNT(*) AS ticket_count FROM business_tickets where 1=1 and ticket_status='1' $where_tag1 $where_tag2 GROUP BY department;";
            $otic_dep_arr   =   $this->BusinessTickets->query($otic_dep_qry);

            $odepartmentNames = [];
            $oticketCounts = [];

            foreach($otic_dep_arr as $oticket)
            {
                #print_r($oticket);
                $odepartmentNames[] = $oticket['business_tickets']['department'];
                $oticketCounts[] = $oticket[0]['ticket_count'];
                $ticket_total = $oticket[0]['ticket_count'];
            }
            #die;

            $this->set('odepartmentNames',$odepartmentNames);
            $this->set('oticketCounts',$oticketCounts);

        //chart 7 end
        

    }


      
}
?>