<?php
	class TaxesController extends AppController 
	{
		public $uses=array('InitialInvoice','CostCenterMaster','AddInvParticular','AddInvDeductParticular','Particular','DeductParticular','User');
		public function beforeFilter()
		{
        	parent::beforeFilter();
			$this->layout='home';
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));				
				if(in_array('4',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}				
			}
    	}
		
    	public function index() 
		{
       }
	   
	   public function add() 
	   {
		   $data = $this->params->query;
		   	$b_name=$data['branch_name'];
			$cost_center=$data['cost_center'];         	
			$username=$this->Session->read("username");
         	$this->InitialInvoice->recursive = 0;
			
			$dataX=$this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$cost_center)));
			
			if(empty($dataX))
			{
				$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Please first create Cost Master'."</b></h4>"));
				return $this->redirect(array('controller'=>'CostCenterMasters','action' => 'index'));
			}
			
			$this->set('cost_master',$dataX);
			$this->set('tmp_particulars',$this->AddInvParticular->find('all',array('conditions'=>array('username' => $username))));
			$this->set('tmp_deduct_particulars',$this->AddInvDeductParticular->find('all',array('conditions'=>array('username' => $username))));			
			$this->set('username', $this->Session->read("username"));

		   $this->set('dataY',$data);
		   
        }
	   public function billApproval() 
	   {
	   		$this->layout='home';
			$username=$this->Session->read("username");
			$roles=explode(',',$this->Session->read("page_access"));
			
        	if ($this->request->is('post')) 
			{		
				$checkTotal = 0;	
				$result=$this->request->data['Taxes'];
				$result['createdate'] = date('Y-m-d H:i:s');
				$b_name=$result['branch_name'];
				$desc = $result['invoiceDescription'];
				$invoiceDate = $result['invoiceDate'];
				$tax_call = $result['app_tax_cal'];
				$grnd = $result['grnd'];				
				$amount=$result['total'];
				$result['username']=$username;
				
				$this->InitialInvoice->create();
				
            	if ($this->InitialInvoice->save($result))
				{
                	$id=$this->InitialInvoice->getLastInsertID();
					$particular = $this->params['data']['Particular'];
					
					$flag=false;
					if(!isset($this->params['data']['DeductParticular']))
					{}
					else
					{
						$deductparticular = $this->params['data']['DeductParticular'];
						$flag=true;
					}

					$k=array_keys($particular);$i=0;
					
					foreach($particular as $post):
					$dataX['particulars']="'".addslashes($post['particulars'])."'";
					$checkTotal += $post['qty']*$post['rate'];
					$dataX['qty']="'".$post['qty']."'";
					$dataX['rate']="'".$post['rate']."'";
					$dataX['amount']="'".$post['amount']."'";					
					$this->AddInvParticular->updateAll($dataX,array('id'=>$k[$i++]));
					endforeach; unset($dataX);
					
					if($flag)
					{
						$k=array_keys($deductparticular);$i=0;
					
						foreach($deductparticular as $post):
						$dataX['particulars'] = "'".addslashes($post['particulars'])."'";
						$checkTotal -= $post['qty']*$post['rate'];
						$dataX['qty']="'".$post['qty']."'";
						$dataX['rate']="'".$post['rate']."'";
						$dataX['amount']="'".$post['amount']."'";
						$this->AddInvDeductParticular->updateAll($dataX,array('id'=>$k[$i++]));
						endforeach;
					}					
					
					$total =0;
					$sbctax = 0.5;
					$tax = 14;
					if($tax_call=='1')
					{
						$tax = round($checkTotal*0.14,0);
						$sbctax = 0;
					if(strtotime($invoiceDate) > strtotime("2015-11-14"))
					{$sbctax = round($checkTotal*0.005,0);}
					}

					$grnd = $total + $tax + $sbctax;
					$dataY = array('total'=>$total,'tax'=>$tax,'sbctax'=>$sbctax,'grnd'=>$grnd);
					$this->InitialInvoice->updateAll($dataY,array('id'=>$id));

					
					$res=$this->AddInvParticular->find('all',array('conditions'=>array('username'=>$username)));
						foreach ($res as $post):
						$post['AddInvParticular']['initial_id']=$id;
						$post['AddInvParticular']=Hash::remove($post['AddInvParticular'],'id');
						$this->Particular->saveAll($post['AddInvParticular']);						
						endforeach;
					$this->AddInvParticular->deleteAll(array('username'=>$username));
					
					$res=$this->AddInvDeductParticular->find('all',array('conditions'=>array('username'=>$username)));				
						foreach ($res as $post):
						$post['AddInvDeductParticular']['initial_id']=$id;
						$post['AddInvDeductParticular']=Hash::remove($post['AddInvDeductParticular'],'id');
						$this->DeductParticular->saveAll($post['AddInvDeductParticular']);
						endforeach;					
					$this->AddInvDeductParticular->deleteAll(array('username'=>$username));
					
					$msg = "Hi <br>".$b_name." has Initiatead Invoice for ".$desc." with Value of ".$grnd." on ".date("F j, Y, g:i a");
					$msg .= "<br><strong><b style=color:#FF0000>Kindly Approve </b></strong>";
					
					
					//$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>'','username'=>$username))));
					$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".' The Initial Invoice for amount '.$amount.' to '.$b_name.' has been saved'."</b></h4>"));
					
					App::uses('CakeEmail', 'Network/Email');
					$emailid = $this ->User->find("all",array('fields' => array('email'),'conditions' => array('OR' => array('branch_name' => $b_name,'role' => 'admin'),'not' => array('email' => ''))));

					foreach($emailid as $email1):
						$email2[] = $email1['User']['email'];
					endforeach;
					
					$tms = array(
        						'host' => 'smtp.teammas.in',
        						'port' => 25,
        						'username' => 'ispark@teammas.in',
        						'password' => 'abc@123#1',
        						'transport' => 'Smtp',
        						'tls' => true
    							);
					
					$Email = new CakeEmail();
					$Email -> config($tms);
					$Email-> from(array('ispark@teammas.in' => 'teammas.in'));
					$Email-> emailFormat('html');
					$Email-> to($email2);
					$Email-> subject('New Initial Invoice - '.$b_name );
					$Email-> send($msg);
					
					if(in_array('5',$roles))
					{															
					return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
					}
					else
					{return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));
					}
            	}
				else
            	$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'The Initial Invoice could not be saved. Please, try again.'."</b></h4>"));
			}
        }
		
		public function update() 
	   	{
		}
}

?>