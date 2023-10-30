<?php
class PliSystemsController extends AppController {
    public $uses = array('AccessPages','CostCenterMaster','Addbranch','BusinessTickets','User','DepartmentNameMaster','BusinessCommunity','BusinessGratitude','Masjclrentry','EmpOnService','OnboardLeaveAlert','PliRule','CustomerChat');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getempname','delete_rule','rule','create_weitage','apply_rule','update_rule','getcostcenter');
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

    public function create_weitage(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");

        $per_options = [];
        for ($i = 1; $i <= 100; $i++) {
            $per_options[$i] = $i." %";
        }

        $this->set('per_options',$per_options);

        $curYear = date('Y');
        $this->set('curYear',$curYear);

        // $plirule_arr = $this->PliRule->find("all");

        // $this->set('plirule_arr',$plirule_arr);
        print_r($this->request->data);die;
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                
                $data = $this->request->data;

                $y   =   date('Y',strtotime($data['month']));
                $m   =   date('m',strtotime($data['month']));

                $dataArr = array();
                foreach($data as $d=>$k)
                {
                    $option[] = $d;
                    $dataArr[$d] = addslashes($k);
                }
                print_r($dataArr);die;

                $account_approval = $data['account_approval'];
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



      
}
?>