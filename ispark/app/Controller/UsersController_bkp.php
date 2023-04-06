<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public $uses=array('PageMaster','User','Access','Pages','Addbranch','InitialInvoice','CostCenterMaster','Provision','Logx','HisAction','NotificationMaster','EcrMaster','Masjclrentry');
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('add','view','assign_Access','view_access','edit_User','add_date','getMessage','getMessageDisplay','updateMessage','user_array','puser_array','change_password','get_emp','sendmail');
        $pages = explode(',',$this->Session->read("page_access"));
        if(in_array('40', $pages))
        {$this->Auth->allow('view_users','edit_users');}
        if(in_array('8', $pages))
        {
            $this->Auth->allow('manage_access','view_access');
        }
        if(in_array('15', $pages))
        {
            $this->Auth->allow('create_User');
        }
    }

    public function login() 
    {
        $this->User->recursive = 0;
       if($this->Session->check('Auth.User'))
       {
            $this->redirect(array('action' => 'view')); 
       }
	$this->layout='view';
    }
    public function logout() 
    {
   	$this->Session->delete('username');
   	$this->Session->destroy();
        $this->redirect(array('action'=>'login'));
    }
public function view() 
{
    if ($this->request->is('post')) 
    {
        $pdata=$this->params['data']['User'];//reading user name
        $pdata['UserActive'] = '1';
        $rdata=$this->User->find('first',array('conditions'=>$pdata)); //finding username in table
        $rdata=$rdata['User'];		//returns array
        
        
        
        if (!empty($rdata))		//checking if array is not empty
	{
            
            if($this->User->query("SELECT * FROM `tbl_user` WHERE username='$pUser' AND PASSWORD='$pPass' AND DATEDIFF(CURDATE(),DATE(pass_change_date))>30")) //checking password not changed from last 30 days
            {
                $otp = rand(100000, 999999);
                $unique_key = uniqid('', true);
                $rdata=$this->User->find('first',array('conditions'=>$pdata));
                if($this->User->updateAll(array("otp"=>"'".$otp."'","unique_key"=>"'".$unique_key."'",'otp_send_date'=>"now()"),array("Id"=>$rdata['User']['id'])))
                {
                    App::uses('sendEmail', 'custom/Email');

                    $ukeyArr['ukey'] = base64_encode($unique_key);
                    $ukeyArr['otp'] = base64_encode($otp);
                    $ukeyStr = base64_encode(json_encode($ukeyArr));


                    $sub = "Ispark Link For Password Change";
                    $msg = "Your Link For Password Change will be expired in 24 Hrs.";
                    $msg .= "<br/><br/><br/><br/>";
                    $url = "http://mascallnetnorth.in/ispark/users/change_password?ukey=".$ukeyStr;
                    $msg .= 'Please <a href="'.$url.'">click here</a> to change password.<br/>';


                    $mail = new sendEmail();
                    $mail_status = $mail-> to($rdata['User']['email'],$msg,$sub);
                    if($mail_status)
                    {
                        echo "<script>alert('Url Sent To Your Registered Email-Id For Reset Password');</script>";
                        $this->Session->setFlash('<font color="green">Url Sent To Your Registered Mail-Id For Reset Password.</font>');
                        return $this->redirect(array('action'=>'login'));
                    }
                    else
                    {
                        $this->Session->setFlash("Mail Id Not Found! Please Contact To Admin");
                        return $this->redirect(array('action'=>'login'));
                    }
                }
                else 
                {
                    $this->Session->setFlash("Internet Problem! Please Try Again");
                    return $this->redirect(array('action'=>'login'));
                }
                return $this->redirect(array('action'=>'login'));
            }
            
            $ProspectUser = $this->EcrMaster->find('first',array('fields'=>array('id','ecrName','parent_id','Label'),
            'conditions'=>array('ecrName'=>$rdata['username'])));
            //print_r($ProspectUser); exit;
            if(!empty($ProspectUser))
            {
                $childUser = $this->user_array($ProspectUser['EcrMaster']['Label'],$ProspectUser['EcrMaster']['id'],array()); 
                $parentUser = $this->puser_array($ProspectUser['EcrMaster']['Label'],$ProspectUser['EcrMaster']['parent_id'],array()); 
            }
                $childUser[] = $rdata['username'];
                //print_r($parentUser); exit;
            
            $this->Session->write("username",ucwords(str_replace('.', ' ', $rdata['emp_name']))); 	//creating session for user
            $this->Session->write("email",$rdata['email']); 	//creating session for user
            $this->Session->write("userid",$rdata['id']);	//creating session for user
            $this->Session->write("role",$rdata['role']);			//creating access voilation for user
            $this->Session->write("branch_name",$rdata['branch_name']); ////creating access voilation for user
            $this->Session->write("childUser",$childUser);
            $this->Session->write("parentUser",$parentUser);
            $page=$this->Access->find('first',array('conditions'=>array('id'=>$rdata['id'])));  //find page access links for user in database
            //print_r($page); exit;
            $page_access=$page['Access']['page_access'];
            $this->Session->write("page_access",$page_access);  //setting page access links for user in session
            $this->set('username',$rdata['emp_name']);
            
            $username = $this->Session->read('username');
            $branch_name = $this->Session->read('branch_name');
            $LastBill = $this->InitialInvoice->find('first',array(
            'fields' => array( 'id','bill_no', 'InvoiceDescription', 'branch_name', 'month','cost_center'),
            'conditions'=> array( 'not' => array('bill_no' =>''),'id >' => '179','branch_name'=>$branch_name),
            'order' => array("id"=>'desc')
            ));
            
            $client = $this->CostCenterMaster->find('first',array(
            'fields'=>array('client'),
            'conditions'=>array('cost_center' => $LastBill['InitialInvoice']['cost_center'])
            ));
            
            $LastBill['InitialInvoice']['client'] = $client [ 'CostCenterMaster' ][ 'client' ];
            $this->set('LastBill',$LastBill);
            
            if($this->Session->read('role')=='branch')
                {
                    $this->set('provision',$this->InitialInvoice->query("SELECT  tb.id,tb.branch_name,tb.cost_center,tb.finance_year,tb.month,tb.bill_no,tb.invoiceDescription,tb.total,tb.grnd FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
        WHERE IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no ='',IF(tb.po_date IS NULL OR tb.po_date ='',TRUE,FALSE),FALSE),FALSE) AND tb.finance_year = '2016-17' AND tb.branch_name ='$branch_name' order by tb.branch_name"));

                   $this->set('provision2',$this->InitialInvoice->query("SELECT  tb.id,tb.branch_name,tb.cost_center,tb.finance_year,tb.month,tb.bill_no,tb.invoiceDescription,tb.total,tb.grnd  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
        WHERE (cm.po_required ='Yes' AND IF(tb.po_no IS NOT NULL AND tb.po_no !='',TRUE,(tb.po_date IS NOT NULL AND tb.po_date !=''))) 
        AND tb.id IN (SELECT  tb2.id  FROM tbl_invoice tb2 INNER JOIN cost_master cm2 ON tb2.cost_center = cm2.cost_center 
        WHERE IF(cm.grn='Yes' AND (tb.grn IS NULL OR tb.grn ='') AND (tb.grn_date IS NULL OR tb.grn_date = ''),TRUE,FALSE)) AND finance_year='2016-17' AND tb.branch_name ='$branch_name' order by tb.branch_name")); 

                   $this->set('provision3',$this->Provision->query("SELECT * FROM provision_master Provision LEFT JOIN cost_master cm ON Provision.cost_center=cm.cost_center
                    WHERE Provision.provision_balance!=0 AND Provision.branch_name='$branch_name'"));
                   
                   
                   
               }
                else
                {
                    $this->set('provision','');
                    $this->set('provision2','');
                    $this->set('provision3','');
                }
        }
        else {   
                $this->Session->setFlash(__('<font color="red">Invalid User Name Or Password</font>'));
                $this->redirect(array('action' => 'login'));
            }
    }
		
if($this->Session->read('username'))
{
               $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = '';
		

	   
	   
	    $this->set('username',$this->Session->read('username'));
        $LastBill = $this->InitialInvoice->find('first',array(
        'fields' => array( 'id','bill_no', 'InvoiceDescription', 'branch_name', 'month','cost_center'),
        'conditions'=> array( 'not' => array('bill_no' =>''),'id >'=>'179'),
        'order' => array("id"=>'desc')
	));
        $client = $this->CostCenterMaster->find('first',array(
        'fields'=>array('client'),
	'conditions'=>array('cost_center' => $LastBill['InitialInvoice']['cost_center'])
	));
        $LastBill['InitialInvoice']['client'] = $client [ 'CostCenterMaster' ][ 'client' ];
	$this->set('LastBill',$LastBill);
        $username = $this->Session->read('username');
        $branch_name = $this->Session->read('branch_name');
		
		$NewDate = date('Y-m-d H:i:s'); 
		$this->Logx->save(array('UserName' => "{$username}", 'IpAddress' => "{$ipaddress}", 'LogDate' => "{$NewDate}"));

        
        if($this->Session->read('role')=='branch'){
        $this->set('provision',$this->InitialInvoice->query("SELECT  tb.id,tb.branch_name,tb.cost_center,tb.finance_year,tb.month,tb.bill_no,tb.invoiceDescription,tb.total,tb.grnd FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
WHERE IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no ='',IF(tb.po_date IS NULL OR tb.po_date ='',TRUE,FALSE),FALSE),FALSE) AND tb.finance_year = '2016-17' AND tb.branch_name ='$branch_name' order by tb.branch_name"));
        
        $this->set('provision2',$this->InitialInvoice->query("SELECT  tb.id,tb.branch_name,tb.cost_center,tb.finance_year,tb.month,tb.bill_no,tb.invoiceDescription,tb.total,tb.grnd  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
WHERE (cm.po_required ='Yes' AND IF(tb.po_no IS NOT NULL AND tb.po_no !='',TRUE,(tb.po_date IS NOT NULL AND tb.po_date !=''))) 
AND tb.id IN (SELECT  tb2.id  FROM tbl_invoice tb2 INNER JOIN cost_master cm2 ON tb2.cost_center = cm2.cost_center
WHERE IF(cm.grn='Yes' AND (tb.grn IS NULL OR tb.grn ='') AND (tb.grn_date IS NULL OR tb.grn_date = ''),TRUE,FALSE)) AND finance_year='2016-17' AND tb.branch_name ='$branch_name' order by tb.branch_name"));
        
        $this->set('provision3',$this->Provision->query("SELECT * FROM provision_master Provision LEFT JOIN cost_master cm ON Provision.cost_center=cm.cost_center
                    WHERE Provision.provision_balance!=0 AND Provision.branch_name='$branch_name'"));
        }
        else if($this->Session->read('userid')=='19')
        {
            $this->set('provision3',$this->Provision->query("SELECT * FROM provision_master Provision LEFT JOIN cost_master cm ON Provision.cost_center=cm.cost_center
WHERE Provision.provision_balance!=0 ORDER BY Provision.branch_name ASC,provision_balance DESC"));
        }
        else
        {
            $this->set('provision','');
            $this->set('provision2','');
            $this->set('provision3','');
        }
}



$email_id = $this->Session->read('email');
$obj = $this->PageMaster->query("SELECT parent_access FROM pages_ride WHERE user_name='$email_id'");

$arr = explode(",",$obj[0]['pages_ride']['parent_access']);

$query ="SELECT id,page_name,page_url FROM pages_master WHERE (";

foreach($arr as $ot){
    $query.="id='$ot' OR ";
}
$query = substr($query,0,-4);        
//echo $query.") AND parent_id='0'"; die();

$dd = $this->PageMaster->query($query.") AND parent_id='0' ORDER BY page_name");
$this->set('dd',$dd);;
$this->Session->write("dd",$dd);
//$this->loadModel('CommonData');
//$this->CommonData->getMenu();


$this->layout='home';		
}
	public  function manage_Access()
	{
		 if ($this->request->is('post')) 
		 {
		 	$conditions=array_keys($this->request->data);
							$i=0;
			foreach($this->request->data as $post):
                            $str='';
                            foreach($post as $key=>$value)
                            {	
                                    if($value!=0)
                                    {
                                            $str[]=$key;
                                    }
                            }
                            $data[$i]=implode(',',$str);
                            $i++;		
                            //$this->set('res',$data);
			endforeach;
			
			for($j=0; $j<$i; $j++)
			{
                            $this->Access->updateAll(array('page_access'=>"'".$data[$j]."'"),array('id'=>$conditions[$j]));
			}
			$this->redirect(array('action'=>'view_access'));
		 }


		$this->layout='home';
		$id  = $this->request->query['id'];
		$this->set('pages',$this->Pages->find('all',array('order'=>array('page_name'=>'asc'))));
		$this->set('access',$this->Access->find('all',array('conditions'=>array("id"=>$id))));
		//$this->set('res','data not post');
	}
	public function view_access()
	{
		if($this->request->is("POST"))		
		{
			$user=$this->request->data['InitialInvoice']['user'];
			$this->redirect(array('action'=>'manage_Access','?'=>array('id'=>$user)));
			
		}
		$this->layout='home';		
		$this->set('user',$this->Access->find('list',array('fields'=>array('user_type'),'conditions'=>array('UserActive'=>1),'order'=>array('user_type'))));
                //$this->set('page',$this->Session->read('page_access'));
	}

public  function create_User()
{
    $this->layout='home';
    
    $this->set('branch_master',$this->Addbranch->find('all',array('fields'=>array('branch_name'))));
    $this->set('process_manager',$this->User->find('list',array('fields'=>array('email','username'),'conditions'=>array('role'=>array('Process Manager','IT Manager','admin')))));
    if($this->request->is('Post'))
    {
        if($this->User->find('first',array('conditions'=>array('username'=>$this->request->data['User']['username'],'branch_name'=>$this->request->data['User']['branch_name']))))
        {
            $this->Session->setFlash(__('The User '.$this->request->data['User']['username'].' Already Exist'));
        }
        else
        {
            $data = $this->request->data;
            $data['User']['createdby'] = $this->Session->read('user_id');
            $data['User']['email'] = $data['User']['username'];
            $data['User']['created'] = date('Y-m-d H:i:s');
            $data['User']['pass_change_date'] = date('Y-m-d H:i:s');
            
            $usremail   =   $data['User']['username']; 
            $password   =   $data['User']['password'];
            $name       =   $data['User']['emp_name'];
            
            if($this->User->save($data))
            {
                $this->sendmail($usremail,$password,$name);
                $this->Access->save(array('user_type'=>$this->request->data['User']['username']));
                $this->Session->setFlash(__('The User '.$this->request->data['User']['username'].' Created'));
                $this->redirect(array('action'=>'Create_user'));
            }
        }
        $this->redirect(array('action'=>'Create_user'));
    }
}
        
public  function edit_User()
{
    $this->layout='home';
    $this->set('user_master',$this->User->find('first',array('conditions'=>array('id'=>$this->Session->read('userid')))));
    if($this->request->is('Post'))
            {
                $dataX = array();
                $data = $this->request->data['User'];
                $dataY['password'] = $data['password'];
                $dataY['email'] = $data['email'];
                $dataY['username'] = $data['email'];
                $dataY['pass_change_date'] = date("Y-m-d H:i:s");
                $oldPassword = $data['oldpassword'];
                
                //print_r($data); exit;
                foreach($dataY as $k=>$v)
                {
                    $dataX[$k]="'".$v."'";
                }
                
                
                if($this->User->updateAll($dataX,array('id'=>$this->Session->read('userid'),'password'=>$oldPassword)))
		{
                    $this->Session->setFlash(__('The Password Saved'));
		}
                else
                {
                    $this->Session->setFlash(__('The Password Not Saved'));
                }
                unset($data); unset($dataX);
            }
}
        
        public function view_users()
        {
            $this->layout="home";
            $this->set('User',$this->User->find('all',array('conditions'=>array('UserActive'=>'1'),'order'=>array('username'=>'Asc'))));
        }
        
        public  function edit_Users()
	{
	$this->layout='home';
        $id = $this->params->query['id'];
	$this->set('user_master',$this->User->find('first',array('conditions'=>array('id'=>$id))));
        $this->set('branch_master',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'))));
        $this->set('process_manager',$this->User->find('list',array('fields'=>array('email','username'),'conditions'=>array('role'=>array('Process Manager','IT Manager','admin')))));
	if($this->request->is('Post'))
	{
            $data = $this->request->data['User'];
            $id = $data['id'];
            $data = Hash::remove($data,'id');
            $dataX = array('modifyby'=>"'".$this->Session->read('username')."'",'modified'=>"'".date('Y-m-d H:i:s')."'",'pass_change_date'=>"now()");
            $status = $data['UserActive'];
            foreach($data as $k=>$v)
            {
                $dataX[$k]="'".$v."'";
            }
            //print_r($dataX); die;
            if($this->User->updateAll($dataX,array('id'=>$id)))
            {
                $this->Access->updateAll(array('UserActive'=>$status),array('id'=>$id));
                $this->Session->setFlash(__('The Changes Saved'));
            }
            unset($data); unset($dataX);
        }
	}
public function add_date()
{
        if($this->request->is('POST'))
        {
            $data = $this->request->data['User'];
            $keys = array_keys($data);
            
            for($i = 0; $i<count($data); $i++)
            {
                if(!empty($data[$keys[$i]]))
                {
                    $keys2 = array_keys($data[$keys[$i]]);
                
                    if(!empty($data[$keys[$i]][$keys2[0]]))
                    {
                        $date = date_format(date_create($data[$keys[$i]][$keys2[0]]),'Y-m-d');
                        $date .= " ".date('H:i:s');
                        
                        $abc = $this->HisAction->saveAll(array('actionType'=>''.$keys2[0],'invoiceId'=>$keys[$i],''.$keys2[0]=>$date,''.$keys2[1]=>$data[$keys[$i]][$keys2[1]],
                            'userid'=>$this->Session->read('userid'),'createdate'=>date('Y-m-d H:i:s')));
                        
                        $this->InitialInvoice->updateAll(array($keys2[0]=>"'".$date."'",$keys2[1]=>"'".$data[$keys[$i]][$keys2[1]]."'"),array('id'=>$keys[$i]));
                    }
                }
            }
        }
        $this->redirect(array('action'=>'view'));
}

public function getMessage()
{
        if($this->request->is('POST'))
        {
            $arr = $this->NotificationMaster->find('all',array('fields'=>'id','conditions'=>array('readStatus'=>'0','userid'=>$this->Session->read('userid'))));
            echo $count = count($arr); 
        }
        else
        {
           echo 0; 
        }
        exit;
}

public function getMessageDisplay()
{
    $DropDown = '';
        if($this->request->is('POST'))
        {
            $arr = $this->NotificationMaster->find('all',array('conditions'=>array('userid'=>$this->Session->read('userid')),'order'=>array('readStatus')));
            
            //$DropDown .='<ul class="dropdown-menu">';
            foreach($arr as $disp)
                {
                    if(!$disp['NotificationMaster']['readStatus'])
                    {$DropDown .='<li style="font-size: 16px;line-height:20px; "  onclick="updateNotify('.$disp['NotificationMaster']['id'].')"><i class="fa fa-envelope-open" escape=false></i><b>'.strip_tags(str_replace('"','',$disp['NotificationMaster']['msg'])).'</b></li>';}
                    else
                    {{$DropDown .='<li  style="font-size: 16px; line-height:20px;"><i class="fa fa-envelope-close" escape=false></i>'.strip_tags(str_replace('"','',$disp['NotificationMaster']['msg'])).'</li>';}}
                    $DropDown .='<hr style="margin-top:0px;margin-bottom:5px">';
                }
            //$DropDown .='</ul>';     
                echo $DropDown;
        }
        else
        {
            echo $DropDown;
        }
        exit;
}

public function updateMessage()
{
        if($this->request->is('POST'))
        {
            $id = $this->request->data['id'];
            $this->NotificationMaster->updateAll(array('readStatus'=>1),array("id"=>$id));
        }
        exit;
}
public function user_array($label,$paren_id,$arr)
{
    if($label=='5')
    {
        return $arr;
    }
    else
    {
        $data = $this->EcrMaster->find('all',array('fields'=>array('id','ecrName','parent_id','Label'),'conditions'=>array('parent_id'=>$paren_id,'Label'=>$label+1)));
        foreach($data as $d)
        {
            $arr[]=$d['EcrMaster']['ecrName'];
            return $arr+$this->user_array($d['EcrMaster']['Label'],$d['EcrMaster']['id'],$arr);
        }
    }
}

public function puser_array($label,$paren_id,$arr)
{
    if($label=='1')
    {
        return $arr;
    }
    else
    {
        $data = $this->EcrMaster->find('all',array('fields'=>array('id','ecrName','parent_id','Label'),'conditions'=>array('id'=>$paren_id,'Label'=>$label-1)));
        foreach($data as $d)
        {
            $arr[]=$d['EcrMaster']['ecrName'];
            return $arr+$this->puser_array($d['EcrMaster']['Label'],$d['EcrMaster']['parent_id'],$arr);
        }
    }
}

public function change_password()
{
    $this->layout="view";
    $ukeyStr = $this->params->query['ukey'];
    $this->set("ukey",$ukeyStr);
    
    if($this->request->is('Post'))
    {
        $ukeyStr =$this->request->data['User']['ukey']; 
        $ukeyJson = json_decode(base64_decode($ukeyStr));
        //print_r($ukeyJson); exit;
        $ukey = base64_decode($ukeyJson->ukey); 
        $otp = base64_decode($ukeyJson->otp);
        $old_pass = $this->request->data['User']['old_password'];
        $pass = $this->request->data['User']['password'];
        $pass2 = $this->request->data['User']['password2'];

        if( $this->User->find('first',array('conditions'=>"unique_key ='$ukey' and NOW() <= ADDDATE(otp_send_date,INTERVAL 1 DAY)")))
        {        
            if($pass==$pass2)
            {
                $UserData = $this->User->find('first',array('conditions'=>array("password"=>$old_pass,"unique_key"=>$ukey)));
                if(!empty($UserData))
                {
                    if($UserData = $this->User->find('first',array('conditions'=>array("password"=>$old_pass,"unique_key"=>$ukey,"otp"=>$otp))))
                    {
                        if($pass!=$old_pass)
                        {
                            if($this->User->updateAll(array('password'=>"'$pass'",'otp'=>null,'otp_send_date'=>null,'unique_key'=>null,'pass_change_date'=>"now()"),array('id'=>$UserData['User']['id'])))
                            {
                                echo "<script>alertify.success('Password has been Changed. Please Login Again');</script>";
                                $this->Session->setFlash('<font color="green">Password has been Changed. Please Login.</font>');
                                return $this->redirect(array('action'=>'login'));
                            }
                            else
                            {
                                echo '<script>alert("Password Not Changed! Please Try Again");</script>';
                                $this->Session->setFlash('<font color="red">Password Not Changed! Please Try Again</font>');
                            }
                        }
                        else
                        {
                            echo '<script>alert("Enter a New Password");</script>';
                            $this->Session->setFlash('<font color="red">Enter a New Password</font>');
                        }
                    }
                    else 
                    {
                        echo '<script>alert("OTP Not Matched");</script>';
                        $this->Session->setFlash('<font color="red">OTP Not Matched</font>');
                    }
                }
                else
                {
                        echo '<script>alert("Old Password did not Matched");</script>';
                        $this->Session->setFlash('<font color="red">Old Password did not Matched</font>');
                }
            }
            else
            {
                echo '<script>alert("Old & New Password Not Matched")</script>';
                $this->Session->setFlash('<font color="red">Old & New Password Not Matched</font>');
            }
        }
        else
        {
            $this->Session->setFlash('<font color="red">Url has been Expired</font>');
            return $this->redirect(array('action'=>'login'));
        }
        return $this->redirect(array('action'=>'change_password','?'=>array('ukey'=>$ukeyStr)));
    }
}

    public function get_emp(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("EmpName"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$_REQUEST['EmpCode'],
                    'EmpLocation'=>'InHouse',
                    )
                ));
            
            if(!empty($data)){
                echo $data['Masjclrentry']['EmpName'];
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    public function sendmail($email,$password,$empname){
        App::uses('sendEmail', 'custom/Email');
        $mail   =   new sendEmail();
        $exp    =   explode(" ", $empname);
        $name   =   $exp[0];
        
        $EmailText ='';
        $Subject="ISpark Employee Login Details"; 
        $EmailText .="<table>";
        $EmailText .="<tr><td>Dear $name</td></tr>";
        $EmailText .="<tr><td>&nbsp;</td></tr>";
        $EmailText .="<tr><td>Your ispark account create successfully.</td></tr>"; 
        $EmailText .="<tr><td>&nbsp;</td></tr>";
        $EmailText .="<tr><td >User Id   - $email</td></tr>"; 
        $EmailText .="<tr><td s>Password  - $password</td></tr>"; 
        $EmailText .="<tr><td>&nbsp;</td></tr>";
        $EmailText .="<tr><td>Thanks & Regards</td></tr>";
        $EmailText .="<tr><td>&nbsp;</td></tr>";
        $EmailText .="<tr><td>ISPARK</td></tr>";
        $EmailText .="</table>";
        
        $mail->to($email,$EmailText,$Subject);
    }
    

}
?>