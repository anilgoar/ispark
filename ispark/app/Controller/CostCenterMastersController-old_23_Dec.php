<?php
class CostCenterMastersController extends AppController 
    {
    public $uses=array('TmpCostCenterMaster','CostCenterMaster','CostParticular','TmpCostParticular',
        'Addclient','Addbranch','Addcompany','Addprocess','Category','Type','BillMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        	
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
            {   $role=$this->Session->read("role");
                $roles=explode(',',$this->Session->read("page_access"));
                $this->Auth->deny('index','add','view','edit','update','view_tmp','tmp_edit_cost','tmp_update_cost');
                $this->Auth->allow('index');$this->Auth->allow('add');
                $this->Auth->allow('view');$this->Auth->allow('add_particulars');
                $this->Auth->allow('edit','edit_cost');$this->Auth->allow('update_cost');
                $this->Auth->allow('tmp_view','tmp_edit_cost','tmp_update_cost','delete_particulars');
                $this->Auth->allow('tmp_view','tmp_edit_cost','tmp_update_cost','delete_particulars');
            }
    }
		
    	public function index() 
	{
            $this->set('cost_master', $this->CostCenterMaster->find('all'));
            $this->set('company_master', $this->Addcompany->find('all'));
            $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
            $this->set('client_master', $this->Addclient->find('all',array('conditions'=>array('client_status'=>1),'order'=>array('client_name'=>'asc'))));
            $this->set('category_master', $this->Category->find('all'));
            $this->set('type_master', $this->Type->find('all'));
            $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));
            $this->layout='home';
        }
	   
	   public function add() 
	    {
        	if ($this->request->is('post')) 
		{
                    $data=$this->request->data['CostCenterMaster'];
                    $data['createBy'] = $this->Session->read('userid');
                    $data['createdate'] = date('Y-m-d H:i:s');
                    if ($this->TmpCostCenterMaster->save($data))
                    {
                        $id = $this->TmpCostCenterMaster->getLastInsertID();
                        $this->TmpCostParticular->updateAll(array('cost_master_id'=>$id),array('userid'=>$this->Session->read('userid')));
                        $this->Session->setFlash(__("<h4 class=bg-success>".'The Cost Master has been Created And send To Admin Bucket For Approval'."</h4>"));
                        return $this->redirect(array('action' => 'index'));			
                    }
                    $this->Session->setFlash(__("<h4 class=bg-danger>".'The cost Master could not be saved. Please, try again.'."</h4>"));
		}
            }
		
	public function view()
	{
            $this->set('cost_master', $this->CostCenterMaster->find('all',array('order'=>array("substring_index(`cost_center`,'/',-1)"=>'desc'))));
            $this->layout='home';		
	}
		
	public function edit_cost()
	{			
            $id = $this->request->query['id'];
            $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('id'=>$id))));
            $this->set('company_master', $this->Addcompany->find('all'));
            $this->set('branch_master', $this->Addbranch->find('all'));			
            $this->set('client_master', $this->Addclient->find('all'));			
            $this->set('category_master', $this->Category->find('all'));
            $this->set('type_master', $this->Type->find('all'));
            $this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));			
            $this->layout='home';		
	}

	public function update_cost()
	{
            if ($this->request->is('post')) 
			{
				$data = $this->request->data;
				$id = $data['CostCenterMaster']['id'];
				$data = Hash::Remove($data['CostCenterMaster'],'id');
				//$data['CostCenterMaster'][''] = "'".$data['CostCenterMaster']['']."'";
				$key = array_keys($data);$i =0;
				foreach($data as $post)
				{
					$dataX[$key[$i++]] = "'".$post."'";
				}
				if($this->CostCenterMaster->updateAll($dataX,array('id'=>$id)))
				{
					unset($data);unset($key);
					$this->Session->setFlash(__("<h4 class=bg-success>".'The cost Master has been updated.'."</h4>"));
					return $this->redirect(array('action'=>'view'));
				}
				else
				{
					$this->Session->setFlash(__("<h4 class=bg-success>".'The cost Master could not be updated. Please Try Againg!'."</h4>"));
					return $this->redirect(array('action'=>'view'));					
				}
				$this->set('data',$dataX);
			}
		}
                
                public function tmp_view()
		{
                    $role=$this->Session->read("role");
                    $roles=explode(',',$this->Session->read("page_access"));
                        
                    if(in_array('53',$roles))
                    {
                      $condition = array('approve1'=>'0');  
                      $approve = 53;
                    }
                    else
                    {
                        $condition = array('approve2'=>'0');  
                        $approve = 54;
                    }
                    
			$this->set('cost_master', $this->TmpCostCenterMaster->find('all',array('conditions'=>$condition,'order'=>array("Branch"=>'desc'))));
                        $this->set('approve',$approve);
			$this->layout='home';		
		}
                public function tmp_edit_cost()
		{			
			$id = $this->request->query['id'];
                        $role=$this->Session->read("role");
                        $roles=explode(',',$this->Session->read("page_access"));
                        
                        if(in_array('53',$roles))
                        {
                            $condition = array('approve1'=>'0');  
                            $approve = 53;
                        }
                        else
                        {
                            $condition = array('approve2'=>'0');  
                            $approve = 54;
                        }
                         $this->set('approve',$approve);
                         $roles=explode(',',$this->Session->read("page_access"));
                        
			$this->set('cost_master', $this->TmpCostCenterMaster->find('first',array('conditions'=>array('Id'=>$id))));
			$this->set('company_master', $this->Addcompany->find('all'));
			$this->set('branch_master', $this->Addbranch->find('all'));			
			$this->set('client_master', $this->Addclient->find('all'));			
			$this->set('category_master', $this->Category->find('all'));
			$this->set('type_master', $this->Type->find('all'));
			$this->set('process_master', $this->Addprocess->find('all',array('fields'=>array('stream','id'),'group'=>'stream')));			
			$this->layout='home';		
		}
                public function tmp_update_cost()
		{
			if ($this->request->is('post')) 
			{
				$data = $this->request->data['CostCenterMaster'];
                                //print_r($data); exit;
				$id = $data['id']; 
                                $approve = $data['approve'];
				$data = Hash::Remove($data,'id');
                                $data = Hash::Remove($data,'approve');
                                
                                if($approve=='53')
                                {
                                    $data['approve1'] = '1';
                                    $data['approveby1'] = $this->Session->read('userid');
                                    $data['approveDate1'] = date('Y-m-d H:i:s');
                                }
                                else
                                {
                                    
                                    $data['approve2'] = '1';
                                    $data['approveby2'] = $this->Session->read('userid');
                                    $data['approveDate2'] = date('Y-m-d H:i:s');
                                    $branch = $data['branch'];
                                
                                     $dataX=$this->Addbranch->find('first',array('conditions'=>array('Addbranch.branch_name'=>$data['branch'])));
                                     $b_name=$dataX['Addbranch']['branch_code'];
				
                                     $stream=$data['stream'];
                                     $type=$data['type'];

                                     $stream=$this->Addprocess->getStream($stream);
                                     $type=$this->Type->getCodes($type);
                                
                                     $strs='';
                                     $str=explode(" ",$stream['Addprocess']['stream']);
                                     foreach ($str as $post):
                                        $strs.=substr($post,0,1);
                                     endforeach;
				
                                     $str=$strs.'/'.$type['Type']['codes'].'/'.$b_name.'/';
                                     
                                     $BillMaster = $this->BillMaster->find('first',array('fields'=>'cost_center','conditions'=>array('id'=>'1')));
                                
					$str.=$BillMaster['BillMaster']['cost_center'];
					
					$cost_center = $data['cost_center']=preg_replace('/\s+/','',$str);
                                }
				
				$QryArr = Array('Branch'=>'branch','CostCenter'=>'cost_center',
                                        'Stream'=>'stream','Process'=>'process','Category'=>'category','Type'=>'type','Client'=>'client',
                                        'Description'=>'description','SalDays'=>'over_saldays','Incentive'=>'incentive_allowed',
                                        'ManDate'=>'total_man_date','Attrition'=>'attrition',
                                        'Shrinkage'=>'shrinkage','TargetMandate'=>'target_mandate','Shift'=>'shift','WorkingDays'=>'working_days',
                                        'ProcessManagerName'=>'process_manager','EmailId'=>'emailid','ContactNo'=>'contact_no','PORequired'=>'po_required',
                                        'TrainingDaysCount'=>'training_days','TrainingAttrition'=>'training_attrition','CompanyName'=>'company_name',
                                        'Billto'=>'bill_to','Shipto'=>'ship_to','JCCNo'=>'jcc_no','GRN'=>'grn','BillToAdd1'=>'b_Address1',
                                        'BillToAdd2'=>'b_Address2','BillToAdd3'=>'b_Address3','BillToAdd4'=>'b_Address4','BillToAdd5'=>'b_Address5',
                                        'ShipToAdd1'=>'a_address1','ShipToAdd2'=>'a_address2','ShipToAdd3'=>'a_address3','ShipToAdd4'=>'a_address4',
                                        'ShipToAdd5'=>'a_address5','Deduction'=>'deduction_allowed');
                                
                                $dataX = array();
				$key = array_keys($data);$i =0;
				foreach($data as $post)
				{
					$dataX[$key[$i++]] = "'".$post."'";
				}
				if($this->TmpCostCenterMaster->updateAll($dataX,array('id'=>$id)))
				{
                                    $flash = "<h4>".'<b>The Cost Center  has been Moved For Second Approval.</b>'."</h4>";
                                        if($approve=='54')
                                        {
                                            $url = array('ActionType'=>'insert','CostCenterType'=>'Revenue','User_Name'=>$this->Session->read('username'));
                                            foreach($QryArr as $k=>$v)
                                            {
                                                if($v=='shrinkage' ||$v=='attrition') {$url[$k] = filter_var($data[$v], FILTER_SANITIZE_NUMBER_INT).'.0';}
                                                else if ($v=='training_attrition') { if($data[$v]=='No') {$url[$k] = 0;} else {$url[$k] = 1;}}
                                                else
                                                {$url[$k] = $data[$v];}
                                                
                                            }
                                            
                                            $postdata = http_build_query($url);
                                                //print_r($postdata); exit;
                                            $opts = array('http' =>
                                            array(
                                                    'method'  => 'POST',
                                                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                                                    'content' => $postdata
                                                ));
		  
                                            $context = stream_context_create($opts);
                                            $result  = file_get_contents("http://bpsmis.ind.in/CostCenterLink.aspx",false,$context);
                                            
                                            
                                            
                                            $tmp = $this->TmpCostCenterMaster->find('first',array('conditions'=>array('id'=>$id)));
                                            
                                            $tmp = Hash::Remove($tmp['TmpCostCenterMaster'],'id');
                                            
                                            if($this->CostCenterMaster->save($tmp))
                                            {
                                                $newid = $this->CostCenterMaster->getLastInsertID();
                                                $this->BillMaster->updateAll(array('cost_center'=>'cost_center+1'),array('id'=>'1'));
                                                $this->CostParticular->query("INSERT INTO tmp_cost_master_particulars(cost_master_id,remarks,revenueType,qty,rate,total,userid,createdate) "
                                                        . " SELECT '$newid',remarks,revenueType,qty,rate,total,userid,createdate FROM `tmp_cost_master_particulars` WHERE cost_master_id='$id'");
                                                
                                                
                                            }
                                            $flash = "<h4>".'<b>The Cost Center '.$cost_center.' For <b>'.$branch
                                                    .'</b>  has been Created.'."</h4>";
                                        }
					$this->Session->setFlash(__($flash));
					return $this->redirect(array('action'=>'tmp_view'));
				}
				else
				{
					$this->Session->setFlash(__("<h4 class=bg-success>".'The cost Master could not be updated. Please Try Againg!'."</h4>"));
					return $this->redirect(array('action'=>'tmp_view'));					
				}
				$this->set('data',$dataX);
			}
		}
                
                public function add_particulars()
                {
                    $this->layout="ajax";
                    $userid = $this->Session->read("userid");
                    $data = $this->request->data;
                    $data['total'] = $data['qty']*$data['rate'];
                    $data['createdate'] = date('Y-m-d H:i:s');
                    $revenueType = $data['revenueType'];  
                    $data['userid'] = $userid;
                    $this->TmpCostParticular->save($data);
                    $this->set("data",$this->TmpCostParticular->find('all',array('conditions'=>
                        array('revenueType'=>$revenueType,'cost_master_id'=>null,'userid'=>$userid))));
                    
                }
                public function delete_particulars()
                {
                    $this->layout="ajax";
                    $userid = $this->Session->read("userid");
                    $id = $this->request->data['id'];
                    $revenueType = $this->request->data['revenueType']; 
                    $this->set('data1',$this->request->data);
                    $this->TmpCostParticular->query("DELETE FROM `tmp_cost_master_particulars` WHERE Id = '$id'");
                    
                    $this->set("data",$this->TmpCostParticular->find('all',array('conditions'=>
                        array('revenueType'=>$revenueType,'cost_master_id'=>null,'userid'=>$userid))));
                    
                }
}
?>