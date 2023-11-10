<?php
class PliSystemsController extends AppController {
    public $uses = array('AccessPages','CostCenterMaster','Addbranch','BusinessTickets','User','DepartmentNameMaster','PliWeitage','Masjclrentry','EmpOnService','OnboardLeaveAlert','PliRule','CustomerChat');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getempname','delete_rule','rule','create_weitage','update_weitage','delete_weitage','weitage_approval','view_weitage','create_achivement','achivement_approval','apply_rule','update_rule','getcostcenter',
        'get_approved_pli','get_weitage_achievment','export_approved_pli','get_reporting_name');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }

        $this->CustomerChat->useDbConfig = 'dialdee';
    }


    public function index(){
        $this->layout='home';

        $userid = $this->Session->read("userid");
        $email = $this->Session->read('email');
        $EmpCode = $this->Session->read('EmpCode');

        if($email=='nixon.sethi@teammas.in' || $email=='naresh.chauhan@teammas.in')
        {
            $pli_users = $this->User->query("SELECT user_id FROM pli_weitage WHERE weitage_approved_status='1' and achivement_approved_status='1'  GROUP BY user_id");
        }else{
            
            $pli_users = $this->User->query("SELECT user_id FROM pli_weitage WHERE weitage_approved_status='1' and achivement_approved_status='1'  GROUP BY user_id");
        }
        

        
        foreach($pli_users as $use)
        {
            
            $emp_code = $use['pli_weitage']['user_id'];
            $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code'");
            $users[] = $userInfo[0];
        }
        $curYear = date('Y');
        $this->set('curYear',$curYear);
        $this->set('users', $users);

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
                $growth_close_date = $data['growth_close_date'];
                $growth_approval_date = $data['growth_approval_date'];
                $basic_close_date = $data['basic_close_date'];
                $basic_approval_date = $data['basic_approval_date'];
                $deduction = $data['deduction'];

                $exist_rule = $this->PliRule->find('first',array('conditions'=>array('status'=>1)));
                if($exist_rule)
                {
                    $data['status'] = 0;
                }
                
                $data['target_date'] = $target_date;
                $data['growth_close_date'] = $growth_close_date;
                $data['growth_approval_date'] = $growth_approval_date;
                $data['basic_close_date'] = $basic_close_date;
                $data['basic_approval_date'] = $basic_approval_date;
                $data['deduction'] = $deduction;
                $data['created_at'] = date('Y-m-d H:i:s');
                #print_r($data);die;
                $this->PliRule->save($data);
                $this->Session->setFlash('Rule Added Successfully');
                $this->redirect(array('controller'=>'PliSystems','action'=>'rule'));
                

            }

        }     
    }

    public function create_weitage(){
        $this->layout='home';
        
        $userid = $this->Session->read("userid");
        $username = $this->Session->read('username');
        $email = $this->Session->read('email');
        #echo $email;die;
        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);

        if($email=='nixon.sethi@teammas.in' || $email=='naresh.chauhan@teammas.in')
        {

            $qry = "select EmpCode,EmpName from masjclrentry where pli_status=1";

        }else{
            
            $qry = "select EmpCode,EmpName from masjclrentry where pli_status=1 and EmpName='$username' union select EmpCode,EmpName from masjclrentry where pli_status=1 and Reporting_Manager_Name='$username'";
        }
        #echo $qry;die;

        $users = $this->User->query($qry);

        $flatUsers = [];
        foreach ($users as $user) {

            if($user['masjclrentry'])
            {
                $flatUsers[] = $user['masjclrentry'];
            }else{
                $flatUsers[] = $user[0];
            }
            
        }

        #print_r($flatUsers);die;

        $this->set('users', $flatUsers);

        if($this->request->is('post')){
            
            if(!empty($this->request->data))
            {  
                
                $data = $this->request->data;
                $obj = json_decode($data,true);

                $dataArr = array();
                foreach($obj as $key)
                {
                    $exist_weitage = $this->PliWeitage->find('first',array('conditions'=>array('user_id'=>$key['user'],'year'=>date('Y', strtotime($key['month'])),'month'=>date('m', strtotime($key['month'])))));
                    if($exist_weitage)
                    {
                        $data = array();

                    }else{

                        $data = array(
                            'year' => date('Y', strtotime($key['month'])),
                            'month' => date('m', strtotime($key['month'])),
                            'account_approval'=> $key['account_approval'],
                            'user_id' => $key['user'],
                            'type' => $key['type'],
                            'particular' => addslashes($key['particular']),
                            'weitage' => $key['weitage'],
                            'weitage_created_at' => date('Y-m-d H:i:s'),
                            'weitage_created_by' => $userid,
                        );

                    }
                    
                    $dataArr[] = $data;
                }
                #print_r($dataArr);die;

                $save = $this->PliWeitage->saveMany($dataArr);
                if($save)
                {
                    echo "Weitage Add Succesfully";
                }else{
                    echo "Weitage Already Add this Month";
                }die;
                #$this->Session->setFlash('Weitage Added Successfully');
            

            }

        }     
    }

    public function update_weitage(){
        $this->layout='home';
        
        $userid = $this->Session->read("userid");

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);

        $users = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE STATUS='1'  ORDER BY EmpName ");
        $this->set('users', $users);

        if($this->request->is('post')){
            
            if(!empty($this->request->data))
            {  
                
                $data = $this->request->data;
                $obj = json_decode($data,true);

                $dataArr = array();
                foreach($obj as $key)
                {
                    $this->PliWeitage->deleteAll(array('user_id' => $key['user'],'year' => date('Y', strtotime($key['month'])),'month' => date('m', strtotime($key['month']))));

                    $data = array(
                        'year' => date('Y', strtotime($key['month'])),
                        'month' => date('m', strtotime($key['month'])),
                        'account_approval'=> $key['account_approval'],
                        'user_id' => $key['user'],
                        'type' => $key['type'],
                        'particular' => addslashes($key['particular']),
                        'weitage' => $key['weitage'],
                        'weitage_created_at' => date('Y-m-d H:i:s'),
                        'weitage_created_by' => $userid,
                    );

                    
                    
                    $dataArr[] = $data;
                }
                #print_r($dataArr);die;

                $save = $this->PliWeitage->saveMany($dataArr);
                if($save)
                {
                    echo "Weitage Update Succesfully";
                }else{
                    echo "Please Try Again";
                }die;

            }

        }     
    }


    public function weitage_approval(){
        $this->layout='home';
        
        $userid = $this->Session->read("userid");
        $username = $this->Session->read('username');
        $email = $this->Session->read('email');

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);

        $users = array();

        $pli_users = $this->User->query("SELECT user_id,account_approval FROM pli_weitage WHERE weitage_approved_status='0'  GROUP BY user_id");
        foreach($pli_users as $use)
        {
           
            $emp_code = $use['pli_weitage']['user_id'];
            $account_approval = $use['pli_weitage']['account_approval'];

            if($email=='nixon.sethi@teammas.in' || $email=='naresh.chauhan@teammas.in')
            {
                if($account_approval== 'Yes')
                {
                    
                    $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' ");

                }else{
                    $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' and Reporting_Manager_Name='$username' ");
                }
                
            }else{
                $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' and Reporting_Manager_Name='$username'");
            }
            
            if(!empty($userInfo))
            {
                $users[] = $userInfo[0];
            }
            #print_r($users);
        }
        $this->set('users', $users);

        if($this->request->is('post')){
            
            if(!empty($this->request->data))
            {  
                
                $data = $this->request->data;
                $obj = json_decode($data,true);

                $dataArr = array();
                foreach($obj as $key)
                {
                    $id = $key['approval_data'];
                    $updArr=array('weitage_approved_by'=>"'".$userid."'",'weitage_approved_at'=>"'".date('Y-m-d H:i:s')."'",'weitage_approved_status'=>"'1'");	
                    $save = $this->PliWeitage->updateAll($updArr,array('id'=>$id)); 
                }
                if($save)
                {
                    echo "Approved Succesfully";
                }else{
                    echo "Please Try Again";
                    
                }die;

            }

        }     
    }

    


    public function create_achivement(){
        $this->layout='home';
        
        $userid = $this->Session->read("userid");
        $username = $this->Session->read('username');

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);
        $users = array();

        $pli_users = $this->User->query("SELECT user_id FROM pli_weitage WHERE weitage_approved_status='1' and achivement_approved_status='0'  GROUP BY user_id");
        //$users = $this->User->query("select EmpCode,EmpName from masjclrentry where pli_status=1 and EmpName='$username' union select EmpCode,EmpName from masjclrentry where pli_status=1 and Reporting_Manager_Name='$username'");
        foreach($pli_users as $use)
        {
            
            $emp_code = $use['pli_weitage']['user_id'];
            //$userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code'");
            $userInfo = $this->User->query("select EmpCode,EmpName from masjclrentry where EmpCode='$emp_code' and pli_status=1 and EmpName='$username' union select EmpCode,EmpName from masjclrentry where pli_status=1 and Reporting_Manager_Name='$username'");
            if(!empty($userInfo))
            {
                $users[] = $userInfo[0];
            }
            
        }
        $this->set('users', $users);

        if($this->request->is('post')){
            
            if(!empty($this->request->data))
            {  
                #print_r($this->request->data);die;
                $data = $this->request->data;
                $obj = json_decode($data,true);

                $dataArr = array();
                if(is_array($obj)) 
                {
                    foreach($obj as $key)
                    {
                      
                        $id = $key['id'];
                        $achievement = $key['achivement'];
                        $score = $key['score'];
            
                        $updArr=array('achivement_created_by'=>"'".$userid."'",'achivement_created_at'=>"'".date('Y-m-d H:i:s')."'",'achivement'=>"'".$achievement."'",'score'=>"'".$score."'");	
                        $save = $this->PliWeitage->updateAll($updArr,array('id'=>$id)); 
        
                    }
                    if($save)
                    {
                        echo "Achivement Add Succesfully";
                    }
                }else{
                    echo "Please Try Again";
                }die;
            

            }

        }     
    }


    public function achivement_approval(){
        $this->layout='home';
        
        $userid = $this->Session->read("userid");
        $username = $this->Session->read('username');
        $email = $this->Session->read('email');

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);
        $users = array();

        $pli_users = $this->User->query("SELECT user_id,account_approval FROM pli_weitage WHERE weitage_approved_status='1' and achivement_approved_status='0' and achivement is not null  GROUP BY user_id");
        foreach($pli_users as $use)
        {
            
            $emp_code = $use['pli_weitage']['user_id'];
            $account_approval = $use['pli_weitage']['account_approval'];

            if($email=='nixon.sethi@teammas.in' || $email=='naresh.chauhan@teammas.in')
            {
                if($account_approval== 'Yes')
                {
                    
                    $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' ");

                }else{
                    $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' and Reporting_Manager_Name='$username' ");
                }
            }else{
                $userInfo = $this->User->query("SELECT EmpCode,EmpName FROM masjclrentry WHERE EmpCode='$emp_code' and Reporting_Manager_Name='$username'");
            }   
            
            if(!empty($userInfo))
            {
                $users[] = $userInfo[0];
            }
            
        }
        $this->set('users', $users);

        if($this->request->is('post')){
            
            if(!empty($this->request->data))
            {  
                
                $data = $this->request->data;
                $obj = json_decode($data,true);

                $dataArr = array();
                foreach($obj as $key)
                {
                    $id = $key['approval_data'];
                    $updArr=array('achivement_approved_by'=>"'".$userid."'",'achivement_approved_at'=>"'".date('Y-m-d H:i:s')."'",'achivement_approved_status'=>"'1'");	
                    $save = $this->PliWeitage->updateAll($updArr,array('id'=>$id)); 
                }
                if($save)
                {
                    echo "Approved Succesfully";
                }else{
                    echo "Please Try Again";
                    
                }die;

            }
        }
   
    }

    public function view_weitage()
    {
        $this->layout='ajax';

        $email = $this->Session->read('email');

        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){
            $Month = $_REQUEST['Month'];
            $Approval = $_REQUEST['Approval'];

            $year = date('Y', strtotime($_REQUEST['Month']));
            $month = date('m', strtotime($_REQUEST['Month']));

            $EmpCode = $_REQUEST['EmpCode'];
            
            $data = $this->PliWeitage->find('all',array('conditions'=>array('user_id'=>$EmpCode,'year'=>$year,'month'=>$month))); 
            if(!empty($data)){

                $per_options = [];
                for ($i = 1; $i <= 100; $i++) {
                    $per_options[$i] = $i." %";
                }
                
                $total_weitage = 0;
                $approval_radio = "";
                $not_approval_radio = "";
                foreach($data as $key) {

                    $total_weitage += $key['PliWeitage']['weitage'];
                    $key['PliWeitage']['account_approval'];
                    if($key['PliWeitage']['account_approval'] == 'Yes')
                    {
                        $approval_radio = "checked";
                    }else if($key['PliWeitage']['account_approval'] == 'No'){

                        $not_approval_radio = "checked";
                    }
                    $flag= true;
                   if($key['PliWeitage']['weitage_approved_status']=='1')
                    { $flag= false;?>
                    <!-- <div class="box">
                        <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                        <h5 style="color:red;text-align:center;">This Month Pli Already Close</h5>
                        </div>
                    </div> -->
                    <?php } 

                    
                }
                $disable= "";
                $readonly= "";
                if(!$flag)
                {
                    $disable= "disabled";
                    $readonly= "readonly";
                    
                }

  
            ?>


            <div class="box">
                <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                    <h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;"><?php if($Approval==1){ echo "Weightage Approval"; }else{ echo "View Weightage"; }?></h4>
                        <?php //if($Approval!=1){?>
                        <div class="form-group pull-right" style="margin-right: 45px;">      
                            <label>Is Account Approval Require :</label><br>
                            <label>Yes</label>
                            <input type="radio" id="update_account_approval" name="update_account_approval"  value="Yes" <?php echo $approval_radio;?> <?php echo $disable; ?>>
                            <label>No</label>
                            <input type="radio" id="update_account_approval" name="update_account_approval"  value="No" <?php echo $not_approval_radio;?> <?php echo $disable; ?>>
                        </div>
                        <?php //} ?>
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="newRow1">
                        <div id="errorDiv" style="color: red;"></div>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Type</th>
                            <th>Particular</th>
                            <th id="update_tot_weitage">Percentage(%) <span style="color: green;">Total = <?php echo $total_weitage; ?>%</span></th>
                            <th>
                            <?php if($flag){ ?>
                                <div style="display: flex; align-items: center; justify-content: center;">
                                    <span style="margin-right: 10px;">Action</span>
                                    <a href="javascript:void(0);" onclick="add_row();">
                                        Add <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            <?php } ?>
                            </th>
                        </tr>
                        <?php $i = 1; foreach($data as $key) {?>
                            <tr id="row_<?php echo $key['PliWeitage']['id']; ?>">
                                <td><?php echo $i; ?></td>
                                <td>
                                    <label>Basic</label>
                                    <input type="radio" id="type<?php echo $i; ?>" name="update_type<?php echo $i; ?>"  value="Basic" <?php if($key['PliWeitage']['type'] == "Basic"){ echo "checked";} ?> <?php echo $disable; ?>>
                                    <label>Growth</label>
                                    <input type="radio" id="type<?php echo $i; ?>" name="update_type<?php echo $i; ?>"  value="Growth" <?php if($key['PliWeitage']['type'] == "Growth"){ echo "checked";} ?> <?php echo $disable; ?>>
                                </td>
                                <td><input type="text" name="update_particular<?php echo $i; ?>" class="form-control" id="particular<?php echo $i; ?>" placeholder="Particular <?php echo $i; ?>" value="<?php echo $key['PliWeitage']['particular']; ?>" <?php echo $readonly; ?>></td>
                                <td>
                                    <select name="update_weitage<?php echo $i; ?>" class="form-control" id="weitage<?php echo $i; ?>" required="required" onchange="getupdatedWeitageTotal(this.value);" <?php echo $disable; ?>>
                                        <option value="">Select</option>
                                        <?php foreach ($per_options as $key1 => $value) {?>
                                            <option value="<?php echo $key1; ?>" <?php if ($key1 == $key['PliWeitage']['weitage']) echo 'selected'; ?>><?php echo $value; ?></option>
                                        <?php }?>
                                    </select>
                                </td>
                                <td>
                                    <?php if($flag){ ?><input type="hidden" name="approve_id<?php echo $i; ?>" value="<?php echo $key['PliWeitage']['id']; ?>">
                                    <i title="Delete" onclick="remove_row('<?php echo $key['PliWeitage']['id']; ?>');" type="button" style="font-size:22px;cursor: pointer;color:red;" class="material-icons">delete_forever</i>
                                    <?php }?>
                                </td>
                                
                            </tr>
                        <?php $i++;} ?>
                        
                        </table>
                        <?php if($flag){?>
                        <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">
                                <input type="button" value="Update" name="Update" onclick="update_validate_data()"  class="btn btn-primary"  />
                            </div>
                            <?php if($Approval==1)
                            {?>
                                
                                <div class="col-sm-2">
                                    <input type="button" value="Approve" name="Approve" onclick="approve_data()"  class="btn btn-success"  />
                                </div>
                            
                                 
                            <?php   } ?>
                            <div class="col-sm-4"></div>
                            
                        </div>
                        <?php }else{ ?>
                            <h5 style="color:red;text-align:center;">This Month PLI Already Closed</h5>
                        <?php } ?>
                        
                </div>
            </div>
            
            <?php
 
            }
            else{
                echo "";
            }   
        }
        die;
    }


    public function get_weitage_achievment(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){
            $email = $this->Session->read('email');
            $Month = $_REQUEST['Month'];

            $year = date('Y', strtotime($_REQUEST['Month']));
            $month = date('m', strtotime($_REQUEST['Month']));

            $EmpCode = $_REQUEST['EmpCode'];
            $Approval = $_REQUEST['Approval'];
            
            $data = $this->PliWeitage->find('all',array('conditions'=>array('user_id'=>$EmpCode,'year'=>$year,'month'=>$month,'weitage_approved_status'=>1))); 
            if(!empty($data)){

                $total_score = 0;
                $approval_radio = "";
                foreach($data as $key) {
                    $total_score += $key['PliWeitage']['score'];
                    if($key['PliWeitage']['account_approval'] == 'Yes')
                    {
                        $approval_radio = "checked";
                    }
                    if($key['PliWeitage']['achivement_approved_status']=='1')
                    { 
                        ?>
                    <div class="box">
                        <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                        <h5 style="color:red;text-align:center;">This Month PLI Already Closed</h5>
                        </div>
                    </div>
                    <?php die;} 
                }
            ?>


            <div class="box">
                <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                    <h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;"><?php if($Approval==1){ echo "Achievment Approval"; }else{ echo "Achievment Entry"; }?></h4>
                        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="achivement_table">
                        <div id="errorDiv" style="color: red;"></div>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Type</th>
                            <th>Particular</th>
                            <th>Weitage(%)</th>
                            <th>Achievement</th>
                            <th id="total_score">Score  <?php if($total_score!=0){ ?><span style="color: green;">Total = <?php echo $total_score; ?>%</span><?php } ?></th>
                        </tr>
                        <?php $i = 1; foreach($data as $key) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $key['PliWeitage']['type']; ?></td>
                                <td><?php echo $key['PliWeitage']['particular']; ?></td>
                                <td><?php echo $key['PliWeitage']['weitage']; ?> %</td>
                                <td><input type="text" name="achivement<?php echo $i; ?>" id="achivement_<?php echo $key['PliWeitage']['id']; ?>" value="<?php echo $key['PliWeitage']['achivement']; ?>" class="form-control" placeholder="Achivement" onkeypress="return isNumberKey(event,this)" oninput="total_pli_score(this.value,'<?php echo $key['PliWeitage']['weitage']; ?>','<?php echo $i; ?>')" maxlength='3'></td>
                                <td>
                                    <input type="text" name="score<?php echo $i; ?>" id="score<?php echo $i; ?>" value="<?php echo $key['PliWeitage']['score']; ?>" class="form-control" readonly>
                                    <input type="hidden" name="approve_id<?php echo $i; ?>" value="<?php echo $key['PliWeitage']['id']; ?>">
                                </td>
                            </tr>
                        <?php $i++;} ?>
                        
                        </table>
                        <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">
                                <input type="button" value="Save" name="Save" onclick="save_validate_data()"  class="btn btn-primary"  />
                            </div>
                            <?php if($Approval==1)
                            { if($approval_radio != "checked"){?>
                                <div class="col-sm-2">
                                    <input type="button" value="Approve" name="Approve" onclick="approve_data()"  class="btn btn-success"  />
                                </div>
                            <?php }else{
                                if($email=='nixon.sethi@teammas.in' || $email=='naresh.chauhan@teammas.in')
                                { ?>
                                    <div class="col-sm-2">
                                        <input type="button" value="Approve" name="Approve" onclick="approve_data()"  class="btn btn-success"  />
                                    </div>
                                <?php }else{ ?>
                                    <div class="col-sm-2">
                                        <h5 style="color:green;">Only the accountant will approved</h5>
                                    </div>
                                <?php }
                            }
                        } ?>
                            <div class="col-sm-4"></div>
                            
                        </div>
                        
                        
                </div>
            </div>
            
            <?php
 
            }
            else{
                echo "";
            }   
        }
        die;  
    }

    

    public function delete_weitage($id)
    {
        if ($this->request->is('ajax')) {
            if ($this->PliWeitage->delete($id)) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
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

    public function get_reporting_name()
    {
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !="")
        {
            $EmpCode = $_REQUEST['EmpCode'];
            $reporing_arr = $this->User->query("SELECT Reporting_Manager_Name FROM masjclrentry WHERE EmpCode='$EmpCode'");
            if(!empty($reporing_arr))
            {
                $reporting_name  = $reporing_arr[0]['masjclrentry']['Reporting_Manager_Name'];
                echo '<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" style="color:green;"><tr><th>Reporting Manager Name</th></tr><tr><th> '.$reporting_name.'</th></tr></table>';
            }
            #print_r($reporing_arr);

        }die;
    }

    public function get_approved_pli(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){


            $EmpCode = $_REQUEST['EmpCode'];
            $dates = $_REQUEST['Month'];
            $y = date('Y', strtotime($dates));
            $m = date('m', strtotime($dates));

            $condition['user_id']=$EmpCode;
            $condition['weitage_approved_status']=1;
            $condition['achivement_approved_status']=1;

            if($dates !="All"){
                $condition['year']=$y;
                $condition['month']=$m;
            }else{
                unset($condition['year']);
                unset($condition['month']);
            }
            #print_r($condition);die;
            //$data = $this->PliWeitage->find('all',array('conditions'=>array('user_id'=>$EmpCode,'weitage_approved_status'=>1,'achivement_approved_status'=>1,'year'=>$y,'month'=>$m))); 
            $data = $this->PliWeitage->find('all',array('conditions'=>$condition));
            if(!empty($data)){

                $DataArr = array();
                foreach($data as $d)
                {
                    $empcode = $d['PliWeitage']['user_id'];

                    $qry_name = "select * from masjclrentry mas where EmpCode='$empcode'  limit 1";
                    $empdata   =   $this->PliWeitage->query($qry_name);
                    $process['emp_name'] = $empdata[0]['mas']['EmpName'];


                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }


  
            ?>


            <div class="box">
                <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                    <h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">View Approved Performance-linked incentive</h4>
                    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="achivement_table">
                        <div id="errorDiv" style="color: red;"></div>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Emp Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Type</th>
                            <th>Particular</th>
                            <th>Weitage(%)</th>
                            <th>Achivement</th>
                            <th>Score</th>
                        </tr>
                        <?php
                        $i = 1;
                        $total_weitage = 0;
                        $total_achivement = 0;
                        $total_score = 0;
                        $current_month = null; // Initialize the current month
                        foreach ($DataArr as $key) {
                            if ($current_month !== $key['PliWeitage']['month']) {
                                // Month changed, display the total row
                                if ($current_month !== null) { // Skip on the first iteration
                                    echo '<tr>';
                                    echo '<th colspan="6" style="text-align:center;">Total</th>';
                                    echo '<th>' . $total_weitage . ' %</th>';
                                    echo '<th></th>';
                                    echo '<th>' . number_format($total_score, 2) . ' %</th>';
                                    echo '</tr>';
                                }
                                // Reset totals for the new month
                                $total_weitage = 0;
                                $total_achivement = 0;
                                $total_score = 0;
                                $current_month = $key['PliWeitage']['month'];
                            }

                            $total_weitage += $key['PliWeitage']['weitage'];
                            $total_achivement += $key['PliWeitage']['achivement'];
                            $total_score += $key['PliWeitage']['score'];
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $key['emp_name']; ?></td>
                                <td><?php echo date("F Y", strtotime($key['PliWeitage']['year'] . '-' . $key['PliWeitage']['month'] . '-01')); ?></td>
                                <td><?php echo $key['PliWeitage']['year']; ?></td>
                                <td><?php echo $key['PliWeitage']['type']; ?></td>
                                <td><?php echo $key['PliWeitage']['particular']; ?></td>
                                <td><?php echo $key['PliWeitage']['weitage']; ?> %</td>
                                <td><?php echo $key['PliWeitage']['achivement']; ?></td>
                                <td><?php echo $key['PliWeitage']['score']; ?> %</td>
                            </tr>
                        <?php
                            $i++;
                        }

                        // Display the total row for the last month
                        if ($current_month !== null) {
                            echo '<tr>';
                            echo '<th colspan="6" style="text-align:center;">Total</th>';
                            echo '<th>' . $total_weitage . ' %</th>';
                            echo '<th></th>';
                            echo '<th>' . number_format($total_score, 2) . ' %</th>';
                            echo '</tr>';
                        }
                        ?>
                    </table>

                        
                </div>
            </div>
            
            <?php
 
            }
            else{
                echo "<span style='color:red;font-weight:bold;' >No Record Found.</span>";
            }   
        }
        die;  
    }

    public function export_approved_pli()
    {
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){


            $EmpCode = $_REQUEST['EmpCode'];
            $dates = $_REQUEST['Month'];
            $y = date('Y', strtotime($dates));
            $m = date('m', strtotime($dates));
            
            $condition['user_id']=$EmpCode;
            $condition['weitage_approved_status']=1;
            $condition['achivement_approved_status']=1;

            if($dates !="All"){
                $condition['year']=$y;
                $condition['month']=$m;
            }else{
                unset($condition['year']);
                unset($condition['month']);
            }
            #$data = $this->PliWeitage->find('all',array('conditions'=>array('user_id'=>$EmpCode,'weitage_approved_status'=>1,'achivement_approved_status'=>1,'year'=>$y,'month'=>$m))); 
            $data = $this->PliWeitage->find('all',array('conditions'=>$condition));
            if(!empty($data)){

                $DataArr = array();
                foreach($data as $d)
                {
                    $empcode = $d['PliWeitage']['user_id'];

                    $qry_name = "select * from masjclrentry mas where EmpCode='$empcode'  limit 1";
                    $empdata   =   $this->PliWeitage->query($qry_name);
                    $process['emp_name'] = $empdata[0]['mas']['EmpName'];


                    $DataArr[] = array_merge($d,$process);
                    // $DataArr[]['costcentername']=$process;
                }
                header("Content-Disposition: attachment; filename=approved_pli.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
            ?>


            <div class="box">
                <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="achivement_table" border='1'>
                        <div id="errorDiv" style="color: red;"></div>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Emp Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Type</th>
                            <th>Particular</th>
                            <th>Weitage(%)</th>
                            <th>Achivement</th>
                            <th>Score</th>
                        </tr>
                        <?php
                        $i = 1;
                        $total_weitage = 0;
                        $total_achivement = 0;
                        $total_score = 0;
                        $current_month = null; // Initialize the current month
                        foreach ($DataArr as $key) {
                            if ($current_month !== $key['PliWeitage']['month']) {
                                // Month changed, display the total row
                                if ($current_month !== null) { // Skip on the first iteration
                                    echo '<tr>';
                                    echo '<th colspan="6" style="text-align:center;">Total</th>';
                                    echo '<th>' . $total_weitage . ' %</th>';
                                    echo '<th></th>';
                                    echo '<th>' . number_format($total_score, 2) . ' %</th>';
                                    echo '</tr>';
                                }
                                // Reset totals for the new month
                                $total_weitage = 0;
                                $total_achivement = 0;
                                $total_score = 0;
                                $current_month = $key['PliWeitage']['month'];
                            }

                            $total_weitage += $key['PliWeitage']['weitage'];
                            $total_achivement += $key['PliWeitage']['achivement'];
                            $total_score += $key['PliWeitage']['score'];
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $key['emp_name']; ?></td>
                                <td><?php echo date("F Y", strtotime($key['PliWeitage']['year'] . '-' . $key['PliWeitage']['month'] . '-01')); ?></td>
                                <td><?php echo $key['PliWeitage']['year']; ?></td>
                                <td><?php echo $key['PliWeitage']['type']; ?></td>
                                <td><?php echo $key['PliWeitage']['particular']; ?></td>
                                <td><?php echo $key['PliWeitage']['weitage']; ?> %</td>
                                <td><?php echo $key['PliWeitage']['achivement']; ?></td>
                                <td><?php echo $key['PliWeitage']['score']; ?> %</td>
                            </tr>
                        <?php
                            $i++;
                        }

                        // Display the total row for the last month
                        if ($current_month !== null) {
                            echo '<tr>';
                            echo '<th colspan="6" style="text-align:center;">Total</th>';
                            echo '<th>' . $total_weitage . ' %</th>';
                            echo '<th></th>';
                            echo '<th>' . number_format($total_score, 2) . ' %</th>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                        
                </div>
            </div>
            
            <?php
 
            }
            else{
                echo "<span style='color:red;font-weight:bold;' >No Record Found.</span>";
            }   
        }
        die;
    }



      
}
?>