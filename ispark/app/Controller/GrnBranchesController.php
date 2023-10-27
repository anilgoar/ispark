<?php
class GrnBranchesController extends AppController 
{
    public $uses = array('Addbranch','User','CostCenterMaster','ExpenseMaster','ExpenseParticular','ImprestManager','ImprestAllotment','Bank','Tbl_bgt_expenseheadingmaster',
        'VendorMaster','Tbl_bgt_expenseunitmaster','Addcompany','StateList','GrnBranchAccess');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        $this->Auth->allow('get_branch');
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index','imprest_save','imprest_manager_save','get_user','grn_branch_access','addunit','get_state_gst_code');
            $this->Auth->allow('index','imprest_save','imprest_manager_save','get_user','grn_branch_access','addunit','get_branch');
            $this->Auth->allow('grn_branch_access','get_state_gst_code','get_branch');
        }
    }
    
    
    public function index(){
        $this->layout='home';
    }
    public function get_branch()
    {
        $userid = $this->request->data['userid'];
        $branchArray = $this->Addbranch->query("Select * from branch_master bm where active=1");
        $BranchAccess = $this->GrnBranchAccess->find('list',array('fields'=>array('BranchId','BranchId'),'conditions'=>array('UserId'=>$userid)));
        
        foreach($branchArray as $post) {
            ?>  
                <input type="checkbox" name="branch[]" value="<?php echo $post['bm']['id']; ?>" <?php if(in_array($post['bm']['id'],$BranchAccess)) echo "Checked"; ?> /> <?php echo $post['bm']['branch_name']; ?> <br/>
        <?php } 
        
        exit;
    }
    public function grn_branch_access()
    {
        $this->layout='home';
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),'order' => array('branch_name' => 'asc')))); 
        $this->set('user_master', $this->User->find('list',array('conditions'=>array('UserActive'=>1),'fields'=>array('id','username'),'order' => array('username' => 'asc')))); 
        
        if($this->request->is('Post')){
            
            $branch = $this->request->data['branch'];
            $UserId = $this->request->data['GrnBranches']['UserId'];
            $this->GrnBranchAccess->deleteAll(array('UserId'=>$UserId));
            foreach($branch as $val){
                $data=array('UserId'=>$UserId,'BranchId'=>$val,'CreateBy'=>date('Y-m-d H:i:s'));
                $this->GrnBranchAccess->saveAll($data);
            }
            
            $this->Session->setFlash(__('GRN Branch Access add successfully.'));
            $this->redirect(array('action'=>'grn_branch_access'));
        }
        
    }
    
    public function get_user()
    {
        $branchId = $this->request->data['BranchId'];
        $branchArray = $this->Addbranch->query("SELECT tu.id,tu.username FROM tbl_user tu INNER JOIN branch_master bm ON 
tu.branch_name = bm.branch_name WHERE bm.id='$branchId'");
        
        $html = '<option value="">Select</option>';
        foreach($branchArray as $arr)
        {
            $html .= '<option value="'.$arr['tu']['id'].'">'.$arr['tu']['username'].'</option>';
        }
        echo $html;
        
        exit;
    }
    
    public function imprest_save()
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $ImprestManager = $this->ImprestManager->find('all',array('conditions'=>array('active'=>1),'fields'=>array('Id','UserName','Branch'),
            'order' => array('UserName' => 'asc')));
        
        $imprest_master = array();
        foreach($ImprestManager as $im)
        {
            $imprest_master[$im['ImprestManager']['Id']] = $im['ImprestManager']['UserName'].'   -'.$im['ImprestManager']['Branch'];
        }
        
        $this->set('imprest_master', $imprest_master); //provide Imprest
        
        
        $this->set('bank', $this->Bank->find('list',array('fields'=>array('id','bank_name')))); //bank details
        
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
            
            $imprest = $this->request->data['Imprests'];
            
            $entry_date = explode('-',$imprest['EntryDate']);
            krsort($entry_date);
            $imprest['EntryDate'] = implode('-', $entry_date);
            $imprest['CreateDate'] = date('Y-m-d H:i:s');
            $imprest['UserId'] = $this->Session->read('userid');
            $this->ImprestAllotment->save($imprest);
            //print_r($imprest); exit;
            $this->Session->setFlash(__('New Allotment Has Been Saved'));
        }
        
    }
        
    public function imprest_manager_save()
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
                
        $this->layout='home';
        
        if($this->request->is('Post'))
        {
            $imprest = $this->request->data['Imprests'];
            $userId = $imprest['UserId'];
            $branchId = $imprest['BranchId'];
            if($this->ImprestManager->find('first',array('conditions'=>array('UserId'=>$userId))))
            {
                $this->Session->setFlash(__('User Already Exists'));
            }
            else
            {
               $user =  $this->User->find('first',array('conditions'=>array('id'=>$userId)));
               //print_r($user); exit;
               $data['UserId'] = $user['User']['id'];
               $data['UserName'] = $user['User']['emp_name'];
               $data['EmailId'] = $user['User']['email'];
               $data['BranchId'] = $branchId;
               $data['Branch'] = $user['User']['branch_name'];
               $data['CreateDate'] = date('Y-m-d H:i:s');
               
               $this->ImprestManager->save($data);
               $this->Session->setFlash(__('New Imprest Manager '.$user['User']['emp_name'].' Has Been Saved'));
            }
            //print_r($imprest); exit;
            $this->redirect(array('action'=>'imprest_manager_save'));
            
        }
        
    }    
    
      
    
     public function addunit()
{
    $this->layout = "home";
    $all = array();
    
    $role = $this->Session->read('role');
    if($role=='admin')
        {
            $condition=array('1'=>1);
            $qry = "";
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"));
            $qry = "and tu.Branch='".$this->Session->read("branch_name")."'";
        }
    $branchMaster2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
    $branchMaster = $branchMaster2;
    
    $this->set('branch_master',$branchMaster);
    $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    
    if($this->request->is('POST'))
    {
        $Expense = $this->request->data['Imprests'];
       $data['Branch']= $Expense['branch_name'];
       $data['HeadingID']= $Expense['head'];
       $data['SubHeadingID']= $Expense['subhead'];
       $data['ExpenseUnit'] = $Expense['ExpenceUnit'];
        $data['SavedBy'] = $this->Session->read('username');
        $data['Status'] ='1';
         $data['EntryDate'] =  date("Y-m-d H:i:s");
         
        $check =  $this->Tbl_bgt_expenseunitmaster->query("SELECT * FROM `tbl_expenseunitmaster` WHERE `Branch` ='{$data['branch_name']}' AND `HeadingID`='{$data['head']}' AND `SubHeadingID` ='{$data['subhead']}' AND `ExpenseUnit` ='{$data['ExpenceUnit']}' ");
        if(empty($check))
        {
          $this->Tbl_bgt_expenseunitmaster->save($data);  
          $this->Session->setFlash(__($data['ExpenceUnit'].'Unit Saved successfully'));
        }
 else {
     $this->Session->setFlash(__($data['ExpenceUnit'].' Unit Allready Exists'));
 }
        
       
       
    } 
    
    $this->set('UnitReport', $this->Tbl_bgt_expenseunitmaster->query("SELECT Branch,HeadingDesc,SubHeadingDesc,ExpenseUnit FROM tbl_expenseunitmaster tu INNER JOIN
tbl_bgt_expenseheadingmaster hm ON tu.HeadingId = hm.HeadingId
INNER JOIN 
tbl_bgt_expensesubheadingmaster shm
ON shm.SubHeadingId = tu.SubHeadingId
WHERE tu.Status='1' $qry"));
}

    public function get_state_gst_code()
    {
       $this->layout="ajax";
       $Id = $this->request->data['Id'];
       $StateArr = $this->StateList->find('first',array('fields'=>array('state_code'),'conditions'=>array("Id"=>$Id)));
       echo $StateArr['StateList']['state_code']; exit;
    }   
}

?>