<?php
	class AddbranchesController extends AppController 
	{
		public function beforeFilter()
		{
        	parent::beforeFilter();
        	
			
			$this->layout='home';
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
				$this->Auth->allow('edit','view_head','view_expense_head');
				if(in_array('1',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('edit','view_head','view_expense_head');}
                                else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}
			}	
    	}
		
    	public function index() 
        {


                $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
                $this->layout='home';
        }
	   
	   public function add($branch_name=null) 
	   {
        	if ($this->request->is('post')) 
			{
				//print_r($this->request->data); exit;
                                foreach($this->request->data['Addbranch'] as $key=>$value)
                                {
                                    $data['Addbranch'][$key] = addslashes($value);
                                }
				$data['Addbranch']['branch_name'] = strtoupper($data['Addbranch']['branch_name']);
            	if ($this->Addbranch->save($data))
				{
                    
                        $url = array();
                        $url['req_id'] = '1';
                        $url['id']=$branch_id = $this->Addbranch->getLastInsertID();;
                        $url['branch_name']=$branch_name5 = $data['Addbranch']['branch_name'];
                        $url['branch_active'] = '1';
                        
                        $postdata = http_build_query($url);
                        
                        $this->Addbranch->query("INSERT INTO `tbl_voucher_entries` SET entry_type='branch',part_id='$branch_id',part_name='$branch_name5',createdate=NOW()");
                        
                           // print_r($postdata); exit; 
                       // $this->Addcompany->query("INSERT INTO `tm_tbl_invoice` SET branch_name = '$branch', new_cost_bps='".'http://bpsmis.ind.in/CostCenterLink.aspx?'.$postdata."'");
                        $opts = array('http' =>
                        array(
                                'method'  => 'GET',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            ));
                        
                        $context = stream_context_create($opts);
                        //$result  = file_get_contents("http://bpsmis.ind.in/CostCenterLink.aspx",false,$context);
                        $urlName = "http://103.70.201.196/qa/api_for_bill/api_for_bill.php?".$postdata; 
                        $ch = curl_init();
                        curl_setopt($ch,CURLOPT_URL,$urlName);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                //execute post
                        $result = curl_exec($ch);
                        curl_close($ch);
                    
                	$this->Session->setFlash(__('The Branch has been saved'));
                	return $this->redirect(array('action' => 'index'));
            	}
            	$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
        }
		public function edit() 
	   	{
        	if ($this->request->is('post')) 
			{
				//print_r($this->request->data); exit;
				$data=array('branch_name'=>"'".$this->request->data['Addbranch']['branch_name']."'",
				'branch_code'=>"'".$this->request->data['Addbranch']['branch_code']."'",
                                    'branch_address'=>"'".$this->request->data['Addbranch']['branch_address']."'",
                                    'state'=>"'".$this->request->data['Addbranch']['state']."'",
                                    'tally_branch'=>"'".$this->request->data['Addbranch']['tally_branch']."'",
                                    'tally_code'=>"'".$this->request->data['Addbranch']['tally_code']."'",
                                    'company_name'=>"'".$this->request->data['Addbranch']['company_name']."'",
                                    'branch_state'=>"'".$this->request->data['Addbranch']['branch_state']."'",
                                    'state_code'=>"'".$this->request->data['Addbranch']['state_code']."'",
                                    'active'=>$this->request->data['Addbranch']['active']);
                                    if ($this->Addbranch->updateAll($data,array('id'=>$this->request->data['Addbranch']['branch_id'])))
                                        {
                                        
                                        $url = array();
                        $url['req_id'] = '1';
                        $url['id'] = $this->request->data['Addbranch']['branch_id'];
                        $url['branch_name'] = $this->request->data['Addbranch']['branch_name'];
                        $url['branch_active'] = $this->request->data['Addbranch']['active'];
                        
                        $postdata = http_build_query($url);
                           // print_r($postdata); exit; 
                       // $this->Addcompany->query("INSERT INTO `tm_tbl_invoice` SET branch_name = '$branch', new_cost_bps='".'http://bpsmis.ind.in/CostCenterLink.aspx?'.$postdata."'");
                        $opts = array('http' =>
                        array(
                                'method'  => 'GET',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            ));
                        
                        $context = stream_context_create($opts);
                        //$result  = file_get_contents("http://bpsmis.ind.in/CostCenterLink.aspx",false,$context);
                        $urlName = "http://103.70.201.196/qa/api_for_bill/api_for_bill.php?".$postdata;
                        $ch = curl_init();
                        curl_setopt($ch,CURLOPT_URL,$urlName);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                //execute post
                        $result = curl_exec($ch);
                        curl_close($ch);
                                
                                        
                                $this->Session->setFlash(__("<h4 class=bg-success>".'The Branch has been updated successfully'."</h4>"));
                                return $this->redirect(array('action' => 'index'));
                        }
                        $this->Session->setFlash(__("<h4 class=bg-danger>".'The branch could not be updated. Please, try again.'."</h4>"));
                                }
                                else
                                {
                                                        $id  = $this->request->query['id'];
                                $this->set('branch_master',$this->Addbranch->find('first',array('conditions'=>array('id'=>$id))));

                                }
        }
        
        public function view_head() 
		{
        	$this->Addbranch->recursive = 0;
        	
			$this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
			$this->layout='home';
       }
       public function edit_head() 
	   	{
        	if ($this->request->is('post')) 
			{
				//print_r($this->request->data); exit;
				$data=array('branch_name'=>"'".$this->request->data['Addbranch']['branch_name']."'",
				'branch_code'=>"'".$this->request->data['Addbranch']['branch_code']."'",
                                    'branch_address'=>"'".$this->request->data['Addbranch']['branch_address']."'",
                                    'state'=>"'".$this->request->data['Addbranch']['state']."'",
                                    'active'=>$this->request->data['Addbranch']['active']);
                                    if ($this->Addbranch->updateAll($data,array('id'=>$this->request->data['Addbranch']['branch_id'])))
                                        {
                                $this->Session->setFlash(__("<h4 class=bg-success>".'The Branch has been updated successfully'."</h4>"));
                                return $this->redirect(array('action' => 'index'));
                        }
                        $this->Session->setFlash(__("<h4 class=bg-danger>".'The branch could not be updated. Please, try again.'."</h4>"));
                                }
                                else
                                {
                                                        $id  = $this->request->query['id'];
                                $this->set('branch_master',$this->Addbranch->find('first',array('conditions'=>array('id'=>$id))));

                                }
        }
        
       public function view_expense_head() 
		{
        	$this->Addbranch->recursive = 0;
        	
			$this->set('branch_master', $this->Addbranch->query("SELECT * FROM `tbl_bgt_expenseheadingmaster` head 
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON head.HeadingId =subhead.HeadingId
WHERE head.EntryBy='' order by HeadingDesc,SubHeadingDesc "));
			$this->layout='home';
       } 
}

?>