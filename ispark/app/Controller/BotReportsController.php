<?php
class BotReportsController extends AppController {
    public $uses = array('AccessPages','CostCenterMaster','Addbranch','BusinessTickets','User','DepartmentNameMaster','CustomerChat','CustomerChatMsg','Masjclrentry','EmpOnService','OnboardLeaveAlert','BusinessRule');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','dashboard','getempname','delete_rule','rule','close_ticket','report','export_report','edit_rule',
        'update_rule','chat_history');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }

        $this->CustomerChat->useDbConfig = 'dialdee';
        $this->CustomerChatMsg->useDbConfig = 'dialdee';
    }


    public function index(){
        $this->layout='home';

        $userid = $this->Session->read("userid");

        $qry = "select * from chat_customer where client_id='533' and interaction_type='bot' and (customer_name IS Not NULL && customer_name!='') and  DATE(created_at)=CURDATE() order by customer_name";
        $customers_arr   =   $this->CustomerChat->query($qry);

        $this->set('customers_arr',$customers_arr);

        if($this->request->is('Post'))
        {
            
            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));
    
            $qry = "select * from emp_onboard_trigger_services where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag";
            $data_arr   =   $this->EmpOnService->query($qry);
            
            $qry = "select * from chat_customer where client_id='533' and interaction_type='bot' and (customer_name IS Not NULL && customer_name!='') and  DATE(created_at)>='$from' AND DATE(created_at)<='$to' order by customer_name";
            $customers_arr   =   $this->CustomerChat->query($qry);

            if(!empty($customers_arr))
            {
                $n=1; foreach($customers_arr as $customer){ ?>
                    
                    <div class="contact" onclick="showChat('<?php echo $customer['chat_customer']['id']; ?>')"><?php echo $customer['chat_customer']['customer_name']; ?></div>

                <?php }?>
                      

            <?php }die;
         
        }  
    }

    public function chat_history()
    {
        #echo "fgadfhgio;e";die;
        if(isset($_REQUEST['chat_id']) && $_REQUEST['chat_id'] !=""){
            
            //$conditoin=array('Status'=>1);
            $chat_id = $_REQUEST['chat_id'];
            
            $qry = "select * from chat_customermsg where contact_id='$chat_id' order by id asc";
            $data   =   $this->CustomerChatMsg->query($qry);

            if(!empty($data)){
                foreach($data as $row)
                {
                    $messageClass = ($row['chat_customermsg']['sender_type'] === 'customer') ? 'request' : 'response';
                    $msg_id = $row['chat_customermsg']['msg_id'];
                    $msg = $row['chat_customermsg']['msg'];
                    if($msg !='' && $row['chat_customermsg']['sender_type'] === 'customer')
                    {
                        echo "<div class='message $messageClass'>$msg</div>";
                    }
                    else if($msg_id !='' && $row['chat_customermsg']['sender_type'] === 'bot'){

                        $qry = "select * from dialdee_bot_master where id='$msg_id'";
                        $data   =   $this->CustomerChatMsg->query($qry);
                        #print_r($data);
                        $parent_id = $data['0']['dialdee_bot_master']['id'];
                        $qry1 = "select * from dialdee_bot_master where parent_id='$parent_id'";
                        $child_menu   =   $this->CustomerChatMsg->query($qry1);


                        $msg = $data['0']['dialdee_bot_master']['OptionName'];
                        echo "<div class='message $messageClass'>$msg</div>";

                        foreach($child_menu as $child)
                        {
                            $msg = $child['dialdee_bot_master']['OptionName'];
                            echo "<div class='message $messageClass'>$msg</div>";
                        }
                    }
                }
                
            }
            else{
                echo "";
            }die;
            
            
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
            
            $data = $this->Masjclrentry->find("list",array('fields'=>array('EmpName','EmpName'),"conditions"=>$conditoin,'order'=>array('EmpName')));
            
            $RolArr=array();
            $prArr = $this->BusinessRule->find('first',array('fields'=>array('emp_rights'),'conditions'=>array('branch'=>$branch,'department'=>$department)));
            if(!empty($prArr)){
                $RolArr= explode(',', $prArr['BusinessRule']['emp_rights']);
            }
            
            if(!empty($data)){
                foreach($data as $row){
                    if (in_array($row, $RolArr)){
                        echo "<input class='checkbox1' type='checkbox' value='$row' name='Empname[]' checked > $row <br/>";
                    }
                    else{
                         echo "<input class='checkbox1' type='checkbox' value='$row' name='Empname[]' > $row <br/>";
                    }
                }
            }
            else{
                echo "";
            }
            
            
        }die;
        
        
    }


}
?>