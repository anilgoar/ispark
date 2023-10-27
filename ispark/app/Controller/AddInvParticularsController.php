<?php
	class AddInvParticularsController extends AppController 
	{
		public $uses=array('AddInvParticular','AddInvDeductParticular','Addprocess','Addclient','InitialInvoice',
		'CostCenterMaster','Addclient','Particular','DeductParticular','Provision','EditAmount');
		public function beforeFilter()
		{
			$this->Auth->allow('deduct');
			$this->Auth->allow('index');
			$this->Auth->allow('getstream');
			$this->Auth->allow('getClient');
			$this->Auth->allow('getdescription');
			$this->Auth->allow('view');
			$this->Auth->allow('check_po');
			$this->Auth->allow('check_grn');
			$this->Auth->allow('download');
			$this->Auth->allow('download_grn');
			$this->Auth->allow('approve_ahmd');
			$this->Auth->allow('view_ahmd');
			$this->Auth->allow('get_costcenter');
			$this->Auth->allow('download_client');
			$this->Auth->allow('download_bybillno');
			$this->Auth->allow('download_grn_bill_no');
			$this->Auth->allow('view_ahmd_billno');
			$this->Auth->allow('approve_ahmd_bill_no');
			$this->Auth->allow('view_invoice');
			$this->Auth->allow('view_invoice_bybillno');
			$this->Auth->allow('delete_particular');
			$this->Auth->allow('delete_deduct_particular');
			$this->Auth->allow('delete_particular2');
			$this->Auth->allow('delete_particular3');			
			$this->Auth->allow('add_part');
			$this->Auth->allow('add_deduct_part');
			
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
			}

		}
		
                public function index() 
		{
                    $this->layout = 'ajax';
                    $result = $this->request->data['AddInvParticular'];
                    $username = $this->request->data['AddInvParticular']['username'];

                    $result['particulars'] = addslashes($result['particulars']);

                    $this->AddInvParticular->create();
                    if($this->AddInvParticular->save($result))
                    {
                        $this->set('inv_particulars', $this->AddInvParticular->find('all',array('conditions'=>array('username'=>$username))));
                    }
                }

                public function add_part() 
		{
 			$this->layout = 'ajax';
			
			$initial_id = $this->params->query['initial_id'];
			$particular = addslashes($this->params->query['particular']);
			$rate = $this->params->query['rate'];
			$qty = $this->params->query['qty'];
			//$amount = $this->params->query['amount'];
                        $amount = round($rate*$qty);
			
			$username = $this->Session->read('username');
			if(empty($username))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));	
			}

			$userid = $this->Session->read('userid');
            $EditAmount = $this->EditAmount->query("insert into edit_inv_after_approval set inv_id='$initial_id',action='add',rate='$rate',qty='$qty',amount='$amount',inv_date=now(),userid='$userid',username='$username'");

			$data = $this->InitialInvoice->find('first',array('fields' => array('branch_name','invoiceDate','cost_center','finance_year','month','total','app_tax_cal','apply_gst','GSTType'),
														 		'conditions' => array('id' => $initial_id)
												 				));
			
			$dataX = array('username' => $this->Session->read('username'), 'branch_name' => $data['InitialInvoice']['branch_name'], 'cost_center' => $data['InitialInvoice']['cost_center'],'fin_year' => $data['InitialInvoice']['finance_year'],'month_for' => $data['InitialInvoice']['month'],'particulars' => $particular, 'rate' => $rate, 'qty' => $qty, 'amount' => $amount, 'initial_id' => $initial_id);
			
//                        $check_amount = $this->Provision->query("Select IF(pm.provision>= (pm.provision-IF('$amount'='','$amount','0')+IF(ti.total IS NULL,0,ti.total)),pm.provision,0) tab from  provision_master pm 
//                                left JOIN (SELECT cost_center,finance_year,`month`,SUM(total) total FROM tbl_invoice WHERE finance_year='".$data['InitialInvoice']['finance_year']."' AND month='".$data['InitialInvoice']['month']."' AND cost_center='".$data['InitialInvoice']['cost_center']."' GROUP BY cost_center,finance_year,`month`) ti
//                                ON pm.cost_center=ti.cost_center AND pm.finance_year=ti.finance_year AND pm.month=ti.month 
//                                WHERE pm.finance_year='".$data['InitialInvoice']['finance_year']."' AND pm.month='".$data['InitialInvoice']['month']."' AND pm.cost_center='".$data['InitialInvoice']['cost_center']."'");
                        
                        if(1)
                        {
                            if($this->Particular -> save($dataX))
                            {
                                    $Total = $this->Particular->query("select sum(amount) Total from inv_particulars tbl where initial_id='$initial_id'");

                                    $total = $Total['0']['0']['Total'];
                                    $tax = round($total *0.14,0);
                                    $grnd = $total + $tax;
                                    $sbctax = 0;
                                    $flag = false;
                                    if($data['InitialInvoice']['app_tax_cal'] == 1)
                                    {
                                        if($data['InitialInvoice']['apply_gst']=='1' && strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30"))
                                        {
                                          if($data['InitialInvoice']['GSTType']=='IntraState')
                                          {
                                              $sgst = $cgst = round($total*0.09,0);
                                          }
                                          else if($data['InitialInvoice']['GSTType']=='Integrated')
                                          {
                                              $igst = round($total*0.18,0);
                                          }
                                          $grnd = $total+$sgst+$cgst+$igst;
                                          $dataY = array('total'=>$total,'sgst' => $sgst,'cgst'=>$cgst,'igst'=>$igst, 'grnd' => $grnd,'sbctax'=>'null','krishi_tax'=>'null');
                                            $this->InitialInvoice->updateAll($dataY,array('id' =>$initial_id));
                                        }
                                        else
                                        {
                                            $krishi_tax = null; $sbctax = null;
                                            if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
                                            {
                                                $sbctax = round($total*0.005,0);
                                                $grnd = $total + $tax+$sbctax;
                                            }
                                            if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2016-05-30"))
                                            {
                                                $krishi_tax = round($total*0.005,0);
                                                $grnd = $grnd + $krishi_tax;
                                            }
                                            $dataY = array('total'=>$total,'tax' => $tax, 'grnd' => $grnd,'sbctax'=>$sbctax,'krishi_tax'=>$krishi_tax);
                                            $this->InitialInvoice->updateAll($dataY,array('id' =>$initial_id));
                                        }
                                    }
                                    else
                                    {
                                            $dataY = array('total'=>$total, 'grnd' => $total);
                                            $this->InitialInvoice->updateAll($dataY, array('id' => $initial_id));
                                    }
                            }
                        }  
                        else
                        {
                            echo '<script>alert("Provision Amount is Lest Than Invoice Amount")</script>';
                        }
        }
		
                public function add_deduct_part() 
		{
 			$this->layout = 'ajax';
			
			$initial_id = $this->params->query['initial_id'];
			$particular = addslashes($this->params->query['particular']);
			$rate = $this->params->query['rate'];
			$qty = $this->params->query['qty'];
			//$amount = $this->params->query['amount'];
			$amount = round($rate*$qty);
			$this->Session->read('username');

			$data = $this->InitialInvoice->find('first',array('fields' => array('branch_name','invoiceDate','cost_center','finance_year','month','total','app_tax_cal'),
														 		'conditions' => array('id' => $initial_id)
												 				));
			
			$dataX = array('username' => $this->Session->read('username'), 'branch_name' => $data['InitialInvoice']['branch_name'], 'cost_center' => $data['InitialInvoice']['cost_center'],'fin_year' => $data['InitialInvoice']['finance_year'],'month_for' => $data['InitialInvoice']['month'],'particulars' => $particular, 'rate' => $rate, 'qty' => $qty, 'amount' => $amount, 'initial_id' => $initial_id);
			
			if($this->DeductParticular -> save($dataX))
			{
				$total = $data['InitialInvoice']['total'] - $amount;
				$tax = round($total *0.14,0);
				$grnd = $total + $tax;
                                $sbctax = 0;
				$flag = false;
				if($data['InitialInvoice']['app_tax_cal'] == 1)
				{
                                    if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
                                    {
                                        $sbctax = round($total*0.005,0);
                                        $grnd = $total + $tax+$sbctax;
                                    }
					$dataY = array('total'=>$total,'tax' => $tax, 'grnd' => $grnd,'sbctax'=>$sbctax);
					$this->InitialInvoice->updateAll($dataY,array('id' =>$initial_id));
				}
				else
				{
					$dataY = array('total'=>$total, 'grnd' => $total);
					$this->InitialInvoice->updateAll($dataY, array('id' => $initial_id));
				}
			}
        }

		public function get_costcenter()
		{
		 	$this->layout = 'ajax';
			$result = $this->params->query;
			$result['active'] = 1;
			$data=$this->CostCenterMaster->find('all',array('fields'=>array('cost_center'),'conditions'=>array($result)));
			$this->set('cost_master',$data);
		}
			
		public function deduct() 
		{
		 	$this->layout = 'ajax';
			$result = $this->request->data['AddInvDeductParticular'];
			$username=$this->request->data['AddInvDeductParticular']['username'];
			$result['particulars'] = addslashes($result['particulars']);
			
			$this->AddInvDeductParticular->create();
			if($this->AddInvDeductParticular->save($result))
			{
				$this->set('inv_particulars', $this->AddInvDeductParticular->find('all',array('conditions'=>array('username'=>$username))));
			}
		}
		public function getClient()
		{
		 	$this->layout = 'ajax';
			$result = $this->params->query;
			
			$this->Addclient->create();
			$data=$this->Addclient->find('all',array('fields'=>array('client_name'),'conditions'=>$result));
			//$conditions=array(''=>$data['Addclient']['client_name']);
			//$data=$this->Addclient->find('all',array('fields'=>array('client_name'),'conditions'=>array($conditions)));
			$this->set('client_master',$data);
			//$this->set('res',$data);
		}		
		public function getstream()
		{
		 	$this->layout = 'ajax';
			$result = $this->params->query;
			
			$this->Addprocess->create();
			$data=$this->Addprocess->find('first',array('fields'=>array('stream'),'conditions'=>$result));
			$conditions=array('stream'=>$data['Addprocess']['stream']);
			$data=$this->Addprocess->find('all',array('fields'=>array('process_name'),'conditions'=>array($conditions)));
			$this->set('process_master',$data);
			//$this->set('res',$data);
		}
		public function getdescription()
		{
		 	$this->layout = 'ajax';
			$result = $this->params->query['cost_center'];
			
			$this->CostCenterMaster->create();
			$data=$this->CostCenterMaster->find('first',array('fields'=>array('process'),'conditions'=>array('cost_center'=>$result)));
			$this->set('process',$data);
/*			{
				$this->set('process',$data);
			}
			/*else
			{
				$this->set('process',$result);
			}*/
		}
		
		public function view()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>'','proforma_approve'=>1,$result,'status'=>0))));
		}
		
		public function view_invoice()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
                        $this->set('tbl_invoice', $this->InitialInvoice->query("SELECT ti.id,ti.branch_name,ti.bill_no,ti.total,ti.po_no,ti.grn,ti.invoiceDescription FROM tbl_invoice ti 
                            INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
                            LEFT JOIN bill_pay_particulars bpp ON 
                            SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no
                            AND ti.branch_name = bpp.branch_name
                            AND ti.finance_year = bpp.financial_year
                            AND cm.company_name = bpp.company_name
                            WHERE bpp.bill_no IS  NULL and ti.bill_no!='' and ti.branch_name='{$result['branch_name']}'"));
			
		}

		public function view_invoice_bybillno()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			$this->set('tbl_invoice', $this->InitialInvoice->query("SELECT ti.id,ti.branch_name,ti.bill_no,ti.total,ti.po_no,ti.grn,ti.invoiceDescription FROM tbl_invoice ti 
                            INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
                            LEFT JOIN bill_pay_particulars bpp ON 
                            SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no
                            AND ti.branch_name = bpp.branch_name
                            AND ti.finance_year = bpp.financial_year
                            AND cm.company_name = bpp.company_name
                            WHERE bpp.bill_no IS  NULL and ti.bill_no!='' and substring_index(ti.bill_no,'/',1)='{$result['bill_no']}'"));
		}

		public function check_po()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'','po_no'=>''),$result,'approve_po'=>'','status'=>0))));
		}
		public function check_grn()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'','po_no'=>'','grn'=>''), $result, 'approve_grn'=>'','approve_po'=>'Yes'))));
		}
		
		public function download()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));
			
			$this->set('client_master',$this->Addclient->find('all', array( 
																			'fields' => array('client_name'),
																			'conditions' => $result,
																		  )
																));

			if(in_array('18',$roles))
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array(
																'conditions' => array($result),
																'order' => array('id'=>"desc"),
																'limit' =>10,
																)));
			}
			else
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array( 'branch_name'=>$branch_name,$result),'order' => array('id'=>"desc"))));
			}
		}
		public function download_bybillno()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));
			
			if(in_array('18',$roles))
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array(
				'conditions' => array(
				"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no']))));
			}
			else
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('branch_name'=>$branch_name,"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no']))));
			}
		}
		
		/*public function download_client()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$roles=explode(',',$this->Session->read("page_access"));
			

			if(in_array('18',$roles))
			{
				$data= $this->CostCenterMaster->find('first',array(
																	'fields' => array('cost_center'),
																	'conditions'=>array($result)
																	)
													);
													
				foreach($data as $post):
				$dataX[]=$post['CostCenterMaster']['cost_center'];
				endforeach; unset(CostCenterMaster);
													
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array(
															'conditions'=>array(
																				'branch_name' => $result['branch'],
																				'in' => array ( 'cost_center' => $dataX),
																				'not' => array ( 'approve_po' => '' , 'po_no' => '', 'bill_no' => '')
																				)
																			)));
																		
			}
			else
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'','po_no'=>'','approve_po'=>''), 'username'=>$username,$result))));
			}
		}*/
		
		public function download_grn()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
                        $branch_name=$this->Session->read("branch_name");
                        $roles=explode(',',$this->Session->read("page_access"));
                        if(in_array('18',$roles))
			{
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),$result))));
                        }
                        else
                        {
                            $this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),'branch_name'=>$branch_name))));
                        }
                        
		}
		public function download_grn_bill_no()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
                        $branch_name=$this->Session->read("branch_name");
                        $roles=explode(',',$this->Session->read("page_access"));
                        if(in_array('18',$roles))
			{
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no']))));
                        }
                        else
                        {
                          $this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no'],'branch_name'=>$branch_name))));  
                        }
		}
		
		public function approve_ahmd()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>'','view_ahmedabad'=>'Yes'),$result))));
		}
		public function approve_ahmd_bill_no()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>'','view_ahmedabad'=>'Yes'),"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no']))));
		}
		
		public function view_ahmd()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),'view_ahmedabad'=>'Yes',$result))));
		}
		
		public function view_ahmd_billno()
		{	
			$this->layout = 'ajax';
			$result = $this->params->query;
			$username=$this->Session->read("username");
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'', 'po_no'=>'', 'approve_po'=>'', 'grn'=>'','approve_grn'=>''),'view_ahmedabad'=>'Yes',"SUBSTRING_INDEX(bill_no,'/','1')" => $result['bill_no']))));
		}
		
		public function delete_particular()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			
			$data = $this->InitialInvoice->find("first",array('fields' => array('total','app_tax_cal','invoiceDate'),'conditions'=> array('id'=>$result['initial_id'])));
			$amount = $this->Particular->find("first", array( "fields" => array("amount",'rate','qty'), "conditions" => $result));
			$rate = $amount['Particular']['rate'];
			$qty = $amount['Particular']['qty'];
			$amount1 = $amount['Particular']['amount'];
			$total = $data['InitialInvoice']['total'] - $amount['Particular']['amount'];
			$tax = round($total *0.14,0);
			$sbctax=0;
                        $grnd = $total + $tax+$sbctax;
			
			$flag = false;
			if($data['InitialInvoice']['app_tax_cal'] == 1)
			{
                            if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
                            {
                                $sbctax = round($total*0.005,0);
                                $grnd = $total + $tax+$sbctax;
                            }
				$dataX = array('total'=>$total,'tax' => $tax, 'grnd' => $grnd,'sbctax'=>$sbctax);
				$this->InitialInvoice->updateAll($dataX,array('id' =>$result['initial_id']));
				$flag = true;
			}
			else
			{
				$dataX = array('total'=>$total, 'grnd' => $total);
				$this->InitialInvoice->updateAll($dataX,array('id' => $result['initial_id']));
				$flag = true;
			}

			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
			}
			$username = $this->Session->read('username');
            $userid = $this->Session->read('userid');
			$initial_id = $result['initial_id'];
			$EditAmount = $this->EditAmount->query("insert into edit_inv_after_approval set inv_id='$initial_id',action='delete',rate='$rate',qty='$qty',amount='$amount1',inv_date=now(),userid='$userid',username='$username'");
			
			if($flag)
			{
				$this->Particular->deleteAll($result);
			}
		}
		public function delete_deduct_particular()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
			
			$data = $this->InitialInvoice->find("first",array('fields' => array('total','app_tax_cal','invoiceDate'),'conditions'=> array('id'=>$result['initial_id'])));
			$amount = $this->DeductParticular->find("first", array( "fields" => array("amount"), "conditions" => $result));
			
			$total = $data['InitialInvoice']['total'] + $amount['DeductParticular']['amount'];
			$tax = round($total *0.14,0);
			$sbctax=0;                        
                        $grnd = $total + $tax+$sbctax;
			
			$flag = false;
			if($data['InitialInvoice']['app_tax_cal'] == 1)
			{
                            if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
                            {
                                $sbctax = $total*0.005;
                                $grnd = $total + $tax+$sbctax;
                            }
				$dataX = array('total'=>$total,'tax' => $tax, 'grnd' => $grnd,'sbctax'=>$sbctax);
				$this->InitialInvoice->updateAll($dataX,array('id' =>$result['initial_id']));
				$flag = true;
			}
			else
			{
				$dataX = array('total'=>$total, 'grnd' => $total);
				$this->InitialInvoice->updateAll($dataX,array('id' => $result['initial_id']));
				$flag = true;
			}
			
			if($flag)
			{
				$this->DeductParticular->deleteAll($result);
			}
		}

		public function delete_particular2()
		{
			$result = $this->params->query;
			$this->AddInvParticular->deleteAll($result);
		}
		public function delete_particular3()
		{
			$result = $this->params->query;
			$this->AddInvDeductParticular->deleteAll($result);
		}

}

?>