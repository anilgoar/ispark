<?php
	class CollectionsController extends AppController 
	{
		public $uses=array('Collection','Addbranch','BillMaster','TMPCollection','Addcompany','TMPCollectionParticulars',
                    'OtherTMPDeduction','OtherDeduction','CollectionParticulars','InitialInvoice','Bank','User','BillMaster',
                    'CollectionUpdate','CollectionParticularsUpdate','OtherDeductionUpdate','OtherBillTMPDeduction','OtherBillDeduction','OtherBillDeductionUpdate');
		public $components = array('RequestHandler');
		public $helpers = array('Js');

		public function beforeFilter()
		{
        	parent::beforeFilter();
			
			$this->Auth->deny('get_collection_data','index');
			$this->Auth->deny('get_collection_tmp_data');
			$this->Auth->deny('get_collection_tmp_bill_data');
			$this->Auth->deny('delete_collection_particular');
			$this->Auth->deny('Other_Deduction');
			$this->Auth->deny('delete_other_deduction');
			$this->Auth->deny('get_bill_amount');
			$this->Auth->deny('back');
			$this->Auth->deny('get_bill_remark');
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
				//$rdx=$this->Access->find('first',array('fields'=>array('page_access'),'conditions'=>array('user_type'=>$role)));
				//$roles=explode(',',$rdx['Access']['page_access']);
				
				if(in_array('29',$roles)){$this->Auth->allow('get_collection_data','index');
                                $this->Auth->allow('get_collection_data','index');
                $this->Auth->allow('get_collection_tmp_data','other_bill_deduction');
                $this->Auth->allow('get_collection_tmp_bill_data');
                $this->Auth->allow('delete_collection_particular');
                $this->Auth->allow('Other_Deduction');
                $this->Auth->allow('delete_other_deduction','delete_bill_other_deduction');
                $this->Auth->allow('get_bill_amount');
                $this->Auth->allow('back');
                $this->Auth->allow('add');
                $this->Auth->allow('get_bill_remark');
                $this->Auth->allow('view_payment');
                $this->Auth->allow('get_bill_amount1','delete_upd_particular','edit_payment','edit_payment1','payment_tmp_update','payment_update_part','delete_upd_other_deduction','delete_upd_bill_other_deduction','Other_Bill_Deduction_Upd');
                $this->Auth->allow('update_payment','Other_Deduction_Upd');			
                $this->Auth->allow('approve_payment','edit_payment2','edit_payment3','update_payment2');			
			}			
			
                        }
    	}

	public function get_collection_data()
	{
		$this->layout = 'ajax';
		$this->set('data',$this->params->query);
                $conditions =$this->params->query;
		$this->set('RTGS',$this->Collection->find('first',array('fields' =>array('id','max(pay_no)'),'conditions'=>$conditions)));
	}	
		
		public function get_bill_amount()
		{
			$this->layout="ajax";
			$result = $this->params->query;
                        
                        //echo ; exit;
                        
                        
                        
                        $data = $this->InitialInvoice->query("SELECT IF(bpp.status IS NULL,ti.grnd,IF(bpp.status='paid',0,ti.grnd-bpp.net_amount-bpp.tds_ded)) status FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
                        LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=net_amount,'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
                        AND ti.branch_name = bpp.branch_name AND ti.finance_year = bpp.financial_year
                        AND cm.company_name = bpp.company_name
                        WHERE  SUBSTRING_INDEX(ti.bill_no,'/',1) = '".$result['bill_no']."' AND ti.finance_year='".$result['finance_year']."' 
                        AND cm.company_name ='".$result['company_name']."' and cm.branch='". $result['branch_name']."' 
                        ");
                       
			if($result['bill_no']=='') {$this->set('result','1');}
			
//			else if($data = $this->InitialInvoice->find('first',array('joins'=>array(array('table'=>'bill_pay_particulars','alias'=>'t1','type'=>'INNER', 'conditions' => array("t1.bill_no = SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1) AND InitialInvoice.finance_year=t1.financial_year"))),'conditions'=>
//                            array("SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1)"=>$result['bill_no'],'t1.company_name'=>$result['company_name'],'t1.status' => 'paid','InitialInvoice.finance_year'=>$result['finance_year']),'fields'=>array('t1.bill_no'))))
//			
//			{$this->set('result','0');}
//			
//			else if($data=$this->CollectionParticulars->find('first',array('conditions'=>array('bill_no'=>$result['bill_no'],'financial_year'=>$result['finance_year'],'company_name'=>$result['company_name']),'fields'=>array('sum(bill_passed)'),'group'=>'bill_no')))
//			{
//					$dataX  = $this->InitialInvoice->find('first',array('fields'=>array('grnd'),'conditions'=>array("SUBSTRING_INDEX(bill_no,'/','1')"=>$result['bill_no'])));
//					$total = $dataX['InitialInvoice']['grnd'] - $data['0']['sum(bill_passed)'];
//					$this->set('result',$total);
//			}
//			else if($data = $this->InitialInvoice->find('first',array('fields'=>array('grnd'),'conditions'=>array("SUBSTRING_INDEX(bill_no,'/','1')"=>$result['bill_no'],'branch_name' => $result['branch_name'],'finance_year'=>$result['finance_year'],'t1.company_name'=>$result['company_name']),
//                            'joins'=>array(array('table'=>'cost_master','alias'=>'t1','type'=>'INNER', 'conditions' => array("t1.cost_center = InitialInvoice.cost_center"))))))
//			{
//				$this->set('result',$data['InitialInvoice']['grnd']);
//			}
			else
			{$this->set('result',$data['0']['0']['status']);}
		}
                
                public function get_bill_amount1()
    {
        $this->layout="ajax";
        $result = $this->params->query;
        if($result['bill_no']=='') 
        {
            echo "1";
        }

        else if($data = $this->InitialInvoice->find('first',array('joins'=>array(array('table'=>'bill_pay_particulars','alias'=>'t1','type'=>'INNER',
            'conditions' => array("t1.bill_no = SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1) AND InitialInvoice.finance_year=t1.financial_year"))),'conditions'=>
            array("SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1)"=>$result['bill_no'],'t1.company_name'=>$result['company_name'],'InitialInvoice.branch_name'=>$result['branch_name'],'t1.status' => 'paid',
                'InitialInvoice.finance_year'=>$result['finance_year'],'not'=>array('t1.collection_id'=>$result['collection_id'])),'fields'=>array('t1.bill_no'))))

        {echo "0";}
        
        else if($data=$this->CollectionParticulars->find('first',array('conditions'=>array('bill_no'=>$result['bill_no'],'financial_year'=>$result['finance_year'],'branch_name'=>$result['branch_name'],'company_name'=>$result['company_name'],'not'=>array('collection_id'=>$result['collection_id'])),'fields'=>array('sum(bill_passed)'),'group'=>'bill_no')))
        {
                        $dataX  = $this->InitialInvoice->find('first',array('fields'=>array('grnd'),'conditions'=>array("SUBSTRING_INDEX(bill_no,'/','1')"=>$result['bill_no'])));
                        echo $total = trim($dataX['InitialInvoice']['grnd'] - $data['0']['sum(bill_passed)']); exit;                
        }
        else if($data = $this->InitialInvoice->find('first',array('fields'=>array('grnd'),'conditions'=>array("SUBSTRING_INDEX(bill_no,'/','1')"=>$result['bill_no'],'branch_name' => $result['branch_name'],'finance_year'=>$result['finance_year'],'t1.company_name'=>$result['company_name']),
            'joins'=>array(array('table'=>'cost_master','alias'=>'t1','type'=>'INNER', 'conditions' => array("t1.cost_center = InitialInvoice.cost_center"))))))
        {
                echo trim($data['InitialInvoice']['grnd']); exit;
        }
        else
        {echo ""; exit;}
        exit;
    }

		public function get_bill_remark()
		{
			$this->layout="ajax";
			$result = $this->params->query;
			if($result['bill_no']=='') {$this->set('result','');}
			
			else if($data = $this->InitialInvoice->find('first',array('fields'=>array('invoiceDescription'),'conditions'=>array("SUBSTRING_INDEX(bill_no,'/','1')"=>$result['bill_no'],'branch_name' => $result['branch_name']))))
			{
				$this->set('result',$data['InitialInvoice']['invoiceDescription']);
			}
			else
			{$this->set('result','');}
		}
		
		public function back()
		{
					$username = $this->Session->read('username');
					$this->TMPCollection->deleteAll(array('username'=>$username));
					$this->TMPCollectionParticulars->deleteAll(array('username'=>$username));
					$this->OtherTMPDeduction->deleteAll(array('username'=>$username));
                                        $this->OtherBillTMPDeduction->deleteAll(array('username'=>$username));
					return $this->redirect(array('controller'=>'collections','action'=>'index'));
		}
    	public function index() 
		{
			$this->layout='home';
			$username = $this->Session->read('username');
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
			$this->set('company_master',$this->Addcompany->find('all',array('fields' =>array('company_name'))));
			$this->set('result',$this->TMPCollectionParticulars->find('all',array('conditions'=>array('username'=>$username,'delete_status'=>'0'))));
			$this->set('result2',$this->OtherTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0'))));
                        $this->set('result3',$this->OtherBillTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0'))));
			$this->set('bank_master',$this->Bank->find('all',array('fields'=>array('bank_name'))));
                        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
			if($data = $this->TMPCollection->find('first',array('conditions'=>array('username'=>$username))))
			{
				$data = array_values($data['TMPCollection']);
				$this->set('payment_master',$data);
			}
			else
			{
				$data = array_fill(0,12,'');
				$data = array_values($data);
				$this->set('payment_master',$data);
			}
        }
		
		public function add()
		{
			$this->layout = "home";
			if($this->request->is("POST"))
			{ 
				$checkTotal = 0;
				$data = $this->params->data;
				$username = $this->Session->read('username');
				$TMPCollectionParticular = $data['TMPCollectionParticulars'];
                                
                                //print_r($TMPCollectionParticular); exit;
                                
                                
				$OtherTMPDeductions = array();
                                $OtherBillTMPDeductions = array();
				if(isset($data['OtherTMPDeduction'])){
				$OtherTMPDeductions = $data['OtherTMPDeduction'];}
                                if(isset($data['OtherBillTMPDeduction'])){
                                $OtherBillTMPDeductions = $data['OtherBillTMPDeduction'];}
                                
				//$this->set('data',$data['TMPCollectionParticulars']);
				$data = Hash::Remove($data,'TMPCollectionParticulars');
				$data = Hash::Remove($data,'OtherTMPDeduction');
                                $data = Hash::Remove($data,'OtherBillTMPDeduction');
                                
				$branch_name = $data['Collection']['branch_name'];
				$amount = $data['Collection']['pay_amount'];
                                $payDate = $data['Collection']['pay_dates'];
                                $finance_year =  $data['Collection']['financial_year'];
                                $company_name =  $data['Collection']['company_name'];
                                
				$dataX = $this->TMPCollection->find('first',array('conditions'=>array('username'=>$username)));
				$total = $dataX['TMPCollection']['pay_amount'];
				$dataX['TMPCollection'] = Hash::Remove($dataX['TMPCollection'],'id');
                                
				if($this->Collection->save($dataX['TMPCollection']))
				{ 
					$id = $this->Collection->getLastInsertID();		
                                        unset($dataX);		
				
					$TMPKey = array_keys($TMPCollectionParticular);				
					$i = 0;
					$netAmount =0;
                                        $deduction =0;
					foreach($TMPCollectionParticular as $post):
                                                $billNo[] = $post['bill_no'];
                                                $netAmount += $post['net_amount'];
                                                $deduction += $post['deduction'];
						$post['bill_no'] 		= 	"'".$post['bill_no']."'";
						$post['bill_amount'] 	= 	"'".$post['bill_amount']."'";
						$post['bill_passed'] 	= 	"'".$post['bill_passed']."'";
						$post['tds_ded'] 		= 	"'".$post['tds_ded']."'";
						$checkTotal += $post['net_amount'];						
						$post['net_amount'] 	= 	"'".$post['net_amount']."'";
						$post['deduction'] 		= 	"'".$post['deduction']."'";
						$post['status'] 		= 	"'".$post['status']."'";
						$post['remarks'] 		= 	"'".addslashes($post['remarks'])."'";
						$post['collection_id']	=	"'".$id."'";
                                                $this->TMPCollectionParticulars->updateAll($post,array('id' =>$TMPKey[$i++]));				
                                        endforeach; unset($TMPCollectionParticular);
				
                                        $OtherKey = array_keys($OtherTMPDeductions);
                                        $i = 0; $otherDeduction=0;
                                        foreach($OtherTMPDeductions as $post):
                                            $checkTotal -= $post['other_deduction'];
                                            $otherDeduction += $post['other_deduction'];
                                            $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                                            $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                                            $post['collection_id']		=	"'".$id."'";
                                            $this->OtherTMPDeduction->updateAll($post,array('id' =>$OtherKey[$i++]));				
                                        endforeach; unset($OtherTMPDeductions);
					
                                        $OtherBillKey = array_keys($OtherBillTMPDeductions);
                                        $i = 0; $otherBillDeduction=0;
                                        foreach($OtherBillTMPDeductions as $post):
                                            $checkTotal -= $post['other_deduction'];
                                            $otherBillDeduction += $post['other_deduction'];
                                            $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                                            $post['bill_no'] 		= 	"'".addslashes($post['bill_no'])."'";
                                            $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                                            $post['collection_id']		=	"'".$id."'";
                                            $this->OtherBillTMPDeduction->updateAll($post,array('id' =>$OtherBillKey[$i++]));				
                                        endforeach; unset($OtherBillTMPDeductions);
                                        
                                        //echo $total; exit;
                                        
                                        if(strval($total) != strval($checkTotal))
                                        {
                                                $this->Collection->deleteAll(array('id'=>$id));
                                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".' The Total of All Collection Net amount '.$checkTotal.' is not equal to '.$total.' Check Amount '."</b></h4>"));
                                                return $this->redirect(array('controller'=>'Collections','action' => 'index'));
                                        }
				          
					$dataY = $this->TMPCollectionParticulars->find('all',array('conditions'=>array('username'=>$username,'delete_status'=>'0')));
                                        
                                        if(!empty($this->request->data['Collection']['PaymentFile']['name']))
                                        {
                                             $file = $this->request->data['Collection']['PaymentFile'];
                                             $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                                             move_uploaded_file($file['tmp_name'],WWW_ROOT."/CollectionImage/".$id.$file['name']);
                                             $PaymentFile =addslashes($id.$file['name']);
                                             $this->Collection->updateAll(array('PaymentFile'=>"'$PaymentFile'"),array('id'=>$id)); 
                                        }
                                        
                                        
					foreach($dataY as $post):
                                            $post['TMPCollectionParticulars'] = Hash::Remove($post['TMPCollectionParticulars'],'id');
                                            if(!empty($PaymentFile))
                                            {
                                                $post['TMPCollectionParticulars']['PaymentFile'] = $PaymentFile;
                                            } 
                                            $dataZ[] = $post['TMPCollectionParticulars'];
                                            $company = $post['TMPCollectionParticulars']['company_name'];
                                            $branch = $post['TMPCollectionParticulars']['branch_name'];
                                            $finance_year = $post['TMPCollectionParticulars']['financial_year'];
                                            $bill_no = $post['TMPCollectionParticulars']['bill_no'];
                                                
                                                $dataEmail[] = $this->InitialInvoice->query("SELECT cce.pm,cce.admin,cce.bm,cce.rm,cce.corp,cce.ceo FROM tbl_invoice ti
                                                INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center
                                                INNER JOIN cost_center_email cce ON cce.cost_center = cm.id
                                                WHERE cm.company_name='$company' AND  ti.branch_name ='$branch' AND 
                                                finance_year='$finance_year' AND SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no' limit 1");
					endforeach;
					
					$this->CollectionParticulars->saveMany($dataZ); unset($dataY); unset($dataZ);
					
					$dataY = $this->OtherTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0')));
					$flag = false;
					foreach($dataY as $post):
						$post['OtherTMPDeduction'] = Hash::Remove($post['OtherTMPDeduction'],'id');
                                                if(!empty($PaymentFile))
                                                {
                                                    $post['OtherTMPDeduction']['PaymentFile'] = $PaymentFile;
                                                }   
						$dataZ[] = $post['OtherTMPDeduction'];
						$flag = true;
					endforeach;
					if($flag)
					{
                                            $this->OtherDeduction->saveMany($dataZ);
                                            unset($dataY); unset($dataZ);
					}
					
                                        $dataYA = $this->OtherBillTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0')));
                                        
					$flag = false;
					foreach($dataYA as $post):
						$post['OtherBillTMPDeduction'] = Hash::Remove($post['OtherBillTMPDeduction'],'id');
                                                if(!empty($PaymentFile))
                                                {
                                                    $post['OtherBillTMPDeduction']['PaymentFile'] = $PaymentFile;
                                                }   
						$dataZA[] = $post['OtherBillTMPDeduction'];
						$flag = true;
					endforeach;
                                        
                                        
					if($flag)
					{
                                            $this->OtherBillDeduction->saveMany($dataZA);
                                            unset($dataYA); unset($dataZA);
					}
                                        
					$this->TMPCollection->deleteAll(array('username'=>$username));
					$this->TMPCollectionParticulars->deleteAll(array('username'=>$username));
					$this->OtherTMPDeduction->deleteAll(array('username'=>$username));
                                        $this->OtherBillTMPDeduction->deleteAll(array('username'=>$username)); 
                                        
                                        App::uses('sendEmail', 'custom/Email');
                                        
                                        $bill = $billNo[0];
                                        $inv = $this->InitialInvoice->query("SELECT cm.client FROM tbl_invoice ti INNER JOIN cost_master cm ON 
                                        ti.cost_center = cm.cost_center WHERE SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill'
                                        AND company_name='$company_name' AND finance_year = '$finance_year' limit 1");
                                        
                                        $client = $inv[0]['cm']['client']; 
                                        
                                        $sub = "Payment Received $payDate";
                                        $msg = "Deal All,<br><br>";
                                        $msg .= "$branch_name <br>";
                                        $msg .= "Greetings <br><br>";
                                        $msg .= "Payment for bill no. ".implode(", ",$billNo)." of $client has been received for Rs. $checkTotal, deduction of $deduction ";
                                        $msg .= "and Other Deduction for Rs. $otherDeduction";
                                        $msg .= "<br><br>This is System Genrated mail, Please don't reply.";
                                        $msg .= "<br>Regards";
                                        $msg .= "<br><b>I-Spark</b>";
                                        
                                        foreach($dataEmail as $email)
                                            { $pm = array(); $admin = array(); $bm = array(); $corp = array();
                                            $rm = array(); $ceo = array();
                                                if(!empty($email))
                                                {
                                                    if(!empty($email[0]['cce']['pm']))
                                                    {
                                                        $pm =explode(",",$email[0]['cce']['pm']) ;
                                                        foreach($pm as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $to[] = $c; 
                                                            }
                                                        }
                                                    }
                                                    if(!empty($email[0]['cce']['admin']))
                                                    {
                                                        $admin =explode(",",$email[0]['cce']['admin']) ;
                                                        foreach($admin as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $to[] = $c; 
                                                            }
                                                        }
                                                    }
                                                    if(!empty($email[0]['cce']['bm']))
                                                    {
                                                        $bm =explode(",",$email[0]['cce']['bm']) ;
                                                        foreach($bm as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $to[] = $c; 
                                                            }
                                                        }
                                                    }
                                                    if(!empty($email[0]['cce']['corp']))
                                                    {
                                                        $corp =explode(",",$email[0]['cce']['corp']) ;
                                                        foreach($corp as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $to[] = $c; 
                                                            }
                                                        }
                                                    }
                                                    if(!empty($email[0]['cce']['rm']))
                                                    {
                                                        $rm =explode(",",$email[0]['cce']['rm']) ;
                                                        foreach($rm as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $cc[] = $c; 
                                                            }
                                                        }
                                                    }
                                                    if(!empty($email[0]['cce']['ceo']))
                                                    {
                                                        //$cc[] = "anil.goar@teammas.in";
                                                        //$cc[] = "krishna.kumar@teammas.in";
                                                        $ceo =explode(",",$email[0]['cce']['ceo']) ;
                                                        foreach($ceo as $c)
                                                        {
                                                            if(!empty($c))
                                                            {
                                                                $cc[] = $c; 
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        
                                        $to = array_unique($to);
                                        $cc = array_unique($cc);
                                        $mail = new sendEmail();
                                        if(!empty($to))
                                        {
                                            try{
                                            $mail-> multiple($to,$cc,$msg,$sub);
                                            }
                                            catch(SocketException $e)
                                            {
                                                $error = "Email Not Send";
                                            }
                                        }
					$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".' The Collection for amount '.$amount.' to '.$branch_name.' has been saved.'."$error</b></h4>"));
					return $this->redirect(array('controller'=>'Collections','action' => 'index'));
				}
			}
		}
		
		public function get_collection_tmp_data()
		{
			$this->layout='ajax';
			$data = $this->params->query;
			$username = $this->Session->read('username');
			$data['username'] = $this->Session->read('username');
			
			if($this->TMPCollection->find('first',array('conditions'=>array('username' => $username))))
			{
				$this->TMPCollection->deleteAll(array('username'=>$username));
			}
			
			$date = date_create($data['pays_date']);
			$date = date_format($date,'Y-m-d');
			$dates = date_create($data['pay_type_dates']);
			$dates = date_format($dates,'Y-m-d');
			
			$data['pays_date'] = $date;
			$data['pay_type_dates'] = $dates;
			$this->TMPCollection->save($data);
			$id = $this->TMPCollection->getLastInsertID();
			$this->TMPCollection->updateAll(array('createdate' => 'now()'),array('id'=>$id));
			$this->set('result',$data);
		}
		
		public function get_collection_tmp_bill_data()		
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$data = $this->params->query;

			$data['username'] =  $username;
			$date = date_create($data['pay_dates']);
			$date = date_format($date,'Y-m-d');
			$dates = date_create($data['pay_type_dates']);
			$dates = date_format($dates,'Y-m-d');
			
			$data['pay_dates'] = $date;
			$data['pay_type_dates'] = $dates;
			
			$this->TMPCollectionParticulars->save($data);
			$id = $this->TMPCollectionParticulars->getLastInsertID();
			$this->TMPCollectionParticulars->updateAll(array('createdate' => 'now()'),array('id'=>$id));			
			$this->set('result',$this->TMPCollectionParticulars->find('all',array('conditions'=>array('username'=>$username,'delete_status'=>'0'))));
		}
		
		public function delete_collection_particular()
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$id = $this->params->query['id'];
			$this->TMPCollectionParticulars->updateAll(array('delete_status' => '1'),array('id'=>$id));
			$this->set('result',$id = $this->params->query);
		}
		
		public function Other_Deduction()
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$data = $this->params->query;
			
			$data['username'] =  $username;
			$date = date_create($data['pays_date']);
			$date = date_format($date,'Y-m-d');
			$dates = date_create($data['pay_type_dates']);
			$dates = date_format($dates,'Y-m-d');
			
			$data['pays_date'] = $date;
			$data['pay_type_dates'] = $dates;

			$this->OtherTMPDeduction->save($data);
			$id = $this->OtherTMPDeduction->getLastInsertID();
			$this->OtherTMPDeduction->updateAll(array('createdate' => 'now()'),array('id'=>$id));			
			$this->set('result',$this->OtherTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0'))));
		}
		public function other_bill_deduction()
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$data = $this->params->query;
			
			$data['username'] =  $username;
			$date = date_create($data['pays_date']);
			$date = date_format($date,'Y-m-d');
			$dates = date_create($data['pay_type_dates']);
			$dates = date_format($dates,'Y-m-d');
			
			$data['pays_date'] = $date;
			$data['pay_type_dates'] = $dates;

			$this->OtherBillTMPDeduction->save($data);
			$id = $this->OtherBillTMPDeduction->getLastInsertID();
			$this->OtherBillTMPDeduction->updateAll(array('createdate' => 'now()'),array('id'=>$id));			
			$this->set('result',$this->OtherBillTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0'))));
		}
                
                
		public function delete_other_deduction()
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$id = $this->params->query['id'];
			$this->OtherTMPDeduction->updateAll(array('status' => '1'),array('id'=>$id));
			$this->set('result',$id = $this->params->query);
		}
                public function delete_bill_other_deduction()
		{
			$this->layout='ajax';
			$username = $this->Session->read('username');
			$id = $this->params->query['id'];
			$this->OtherBillTMPDeduction->updateAll(array('status' => '1'),array('id'=>$id));
			$this->set('result',$id = $this->params->query);
		}
                
                public function view_payment()
                {
                    $this->layout="home";
                    $this->set('Data',$this->Collection->find('all',array('conditions'=>"Id>700",'order'=>array('id'=>'desc'))));
                }
    
    public function get_collection_upd_bill_data()		
    {
        $this->layout='ajax';
        $username = $this->Session->read('username');
        $data = $this->request->data;

        $data['username'] =  $username;
        $date = date_create($data['pay_dates']);
        $date = date_format($date,'Y-m-d');
        $dates = date_create($data['pay_type_dates']);
        $dates = date_format($dates,'Y-m-d');

        $data['pay_dates'] = $date;
        $data['pay_type_dates'] = $dates;
        $Id = $data['collection_id']; 
        $this->CollectionParticularsUpdate->save($data);
        //$Id = $this->CollectionParticularsUpdate->getLastInsertID();
        $this->CollectionParticularsUpdate->updateAll(array('createdate' => 'now()'),array('id'=>$id));			
        $this->set('result',$this->CollectionParticularsUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
    }
    
    
    public function delete_upd_particular()
    {
        $this->layout='ajax';
       $Id = $this->request->data['Id']; 
        if($this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>"$Id")))
        {
            echo "1";
        }
        else 
        {
            echo "0";
        }
            
        exit;
    }
    public function delete_upd_other_deduction()
    {
        $this->layout='ajax';
        $Id = $this->request->data['Id']; 
        if($this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>"$Id")))
        {
            echo "1";
        }
        else 
        {
            echo "0";
        }
            
        exit;
    }
    public function Other_Deduction_Upd()
    {
            $this->layout='ajax';
            $username = $this->Session->read('username');
            $data = $this->request->data;

            $data['username'] =  $username;
            $date = date_create($data['pays_date']);
            $date = date_format($date,'Y-m-d');
            $dates = date_create($data['pay_type_dates']);
            $dates = date_format($dates,'Y-m-d');

            $data['pays_date'] = $date;
            $data['pay_type_dates'] = $dates;
            $data['createdate'] =  date('Y-m-d H:i:s');
            //print_r($data); exit;
            $this->OtherDeductionUpdate->save($data);
            $id = $this->OtherDeductionUpdate->getLastInsertID();
            if($this->OtherDeductionUpdate->updateAll(array('createdate' => 'now()'),array('id'=>$id)))
            {
              echo 1;  
            }
            else
            {
                echo 0;
            }
         exit;   
    }

    public function delete_upd_bill_other_deduction()
    {
        $this->layout='ajax';
        $Id = $this->request->data['Id']; 
        if($this->OtherBillDeductionUpdate->query("DELETE FROM `other_deductions_bill_update` WHERE PaymentId='$Id'"))
        {
            echo "1";
        }
        else 
        {
            echo "1";
        }
            
        exit;
    }
    
    public function Other_Bill_Deduction_Upd()
    {
            $this->layout='ajax';
            $username = $this->Session->read('username');
            $data = $this->request->data;

            $data['username'] =  $username;
            $date = date_create($data['pays_date']);
            $date = date_format($date,'Y-m-d');
            $dates = date_create($data['pay_type_dates']);
            $dates = date_format($dates,'Y-m-d');

            $data['pays_date'] = $date;
            $data['pay_type_dates'] = $dates;
            $data['createdate'] =  date('Y-m-d H:i:s');
            //print_r($data); exit;
            $this->OtherBillDeductionUpdate->save($data);
            $id = $this->OtherBillDeductionUpdate->getLastInsertID();
            if($this->OtherBillDeductionUpdate->updateAll(array('createdate' => 'now()'),array('id'=>$id)))
            {
              echo 1;  
            }
            else
            {
                echo 0;
            }
         exit;   
    }
    
    
    public function edit_payment() 
    {
        $this->layout='ajax';
        $Id = $this->params->query['id']; 
        $username = $this->Session->read('username');
        if(!empty($Id))
        {
            $payment  = $this->Collection->find('first',array('conditions'=>array('Id'=>$Id)));
            $collection['CollectionUpdate'] = $payment['Collection'];
            
            $this->CollectionUpdate->deleteAll(array('Id'=>$Id));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$Id));
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$Id));
            
            if($this->CollectionUpdate->save($collection))
            {
                $Parts = $this->CollectionParticulars->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataX = array();
                foreach($Parts as $post)
                {
                    $data = $post['CollectionParticulars'];
                    $dataX[] =  $data;
                }
                
                $OD = $this->OtherDeduction->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataY = array();
                foreach($OD as $post)
                {
                    $data = $post['OtherDeduction'];
                    $dataY[] =  $data;
                }
                if(!empty($dataY))
                {
                    $this->OtherDeductionUpdate->saveMany($dataY); 
                }
                
                
                $BOD = $this->OtherBillDeduction->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataZ = array();
                foreach($BOD as $post)
                {
                    $data = $post['OtherBillDeduction'];
                    $dataZ[] =  $data;
                }
                //print_r($dataZ); exit;
                if(!empty($dataX))
                {
                        $this->CollectionParticularsUpdate->saveMany($dataX);
           
                }
                
                
                
                if(!empty($dataZ))
                {
                    $this->OtherBillDeductionUpdate->saveMany($dataZ); 
                }
                
                $this->redirect(array('action'=>'edit_payment1','?'=>array('Id'=>$Id)));
                
                
                
            }
            else
            {
                $msg = "Please Try Again";
            }
        }
        else
        {
            $msg = "Please Try Again!";
        }
        
    }
    
    public function edit_payment1()
    {
        $this->layout='home';
        $Id = $this->params->query['Id']; 
        $this->set('updateId',$Id);
        $username = $this->Session->read('username');
        $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
        $this->set('company_master',$this->Addcompany->find('all',array('fields' =>array('company_name'))));
        $this->set('result3',$this->OtherBillTMPDeduction->find('all',array('conditions'=>array('username'=>$username,'status'=>'0'))));
        $this->set('bank_master',$this->Bank->find('all',array('fields'=>array('bank_name'))));
        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        if($data = $this->CollectionUpdate->find('first',array('conditions'=>array('Id'=>$Id))))
        {
            $data = array_values($data['CollectionUpdate']);
            
            $this->set('payment_master',$data);
            $this->set('result',$this->CollectionParticularsUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
            $this->set('result2',$this->OtherDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
            $this->set('result3',$this->OtherBillDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
        }
        else
        {
            $msg = "Please Try Again";
        }
    }
	
    public function payment_tmp_update()
    {
        $this->layout='ajax';
        $data = $this->request->data;
        $username = $this->Session->read('username');
        //$data['username'] = $this->Session->read('username');
        $PaymentId = $data['PaymentId'];
        $data = Hash::Remove($data,'PaymentId');

        $date = date_create($data['pays_date']);
        $date = date_format($date,'Y-m-d');
        $dates = date_create($data['pay_type_dates']);
        $dates = date_format($dates,'Y-m-d');

        $data['pays_date'] = $date;
        $data['pay_type_dates'] = $dates;
        
        foreach($data as $k=>$v)
        {
            $dataY[$k] = "'".$v."'";
        }
        
        if($this->CollectionUpdate->updateAll($dataY,array('id'=>$PaymentId)))
        {
            echo "1";
        }
        else 
        {
            echo "0";
        }
        exit;
    }
    
    public function payment_update_part()
    {
        $this->layout='ajax';
        $data = $this->request->data;
        
        $data['username'] = $this->Session->read('username');
        //$PaymentId = $data['PaymentId'];
        //$data = Hash::Remove($data,'PaymentId');

        $date = date_create($data['pay_dates']);
        $date = date_format($date,'Y-m-d');
        $dates = date_create($data['pay_type_dates']);
        $dates = date_format($dates,'Y-m-d');

        $data['pay_dates'] = $date;
        $data['pay_type_dates'] = $dates;
        
        $data['createdate'] = date('Y-m-d H:i:s');
        
        if($this->CollectionParticularsUpdate->save(array('CollectionParticularsUpdate'=>$data)))
        {
            echo "1";
        }
        else 
        {
            echo "0";
        }
        exit;
    }
    
    public function update_payment()
    {
        $this->layout = "ajax";
        if($this->request->is("POST"))
        { 
            $checkTotal = 0;
            $data = $this->params->data;
            //print_r($data); exit;
            $PaymentId = $this->request->data['Collection']['UpdateId'];
            
            
            $username = $this->Session->read('username');
            $TMPCollectionParticular = $data['CollectionParticularsUpdate'];
            $OtherTMPDeductions = array();
            if(isset($data['OtherDeductionUpdate'])){
            $OtherTMPDeductions = $data['OtherDeductionUpdate'];}
            
            if(isset($data['OtherBillDeductionUpdate'])){
            $OtherBillTMPDeductions = $data['OtherBillDeductionUpdate'];}
            
            
            
            //$this->set('data',$data['TMPCollectionParticulars']);
            $data = Hash::Remove($data,'TMPCollectionParticulars');
            $data = Hash::Remove($data,'OtherTMPDeduction');
            $data = Hash::Remove($data,'OtherBillDeductionUpdate');
            
            //print_r($OtherBillTMPDeductions); exit;
            
            $branch_name = $data['Collection']['branch_name'];
            $amount = $data['Collection']['pay_amount'];
            $payDate = $data['Collection']['pay_dates'];
            $finance_year =  $data['Collection']['financial_year'];
            $company_name =  $data['Collection']['company_name'];
            
            $dataX = $this->CollectionUpdate->find('first',array('conditions'=>array('id'=>$PaymentId)));
            $total = $dataX['CollectionUpdate']['pay_amount'];
            $dataX['CollectionUpdate'] = Hash::Remove($dataX['CollectionUpdate'],'id');
            $dataX['CollectionUpdate'] = Hash::Remove($dataX['CollectionUpdate'],'PaymentId');
            //print_r($OtherBillTMPDeductions); exit;
            
            
            
            
            if($this->Collection->save($dataX['CollectionUpdate']))
            { 
                $id = $this->Collection->getLastInsertID(); 
                $this->Collection->deleteAll(array('id'=>$PaymentId));
                
                $this->CollectionParticulars->deleteAll(array('collection_id'=>$PaymentId));
                $this->OtherDeduction->deleteAll(array('collection_id'=>$PaymentId));
                $this->OtherBillDeduction->deleteAll(array('collection_id'=>$PaymentId));
                
                
               
                

                $TMPKey = array_keys($TMPCollectionParticular);	
                //print_r($TMPCollectionParticular); exit;
                $i = 0;

                foreach($TMPCollectionParticular as $post):
                    $billNo[] = $post['bill_no'];
                    $netAmount += $post['net_amount'];
                    $deduction += $post['deduction'];
                    $post['bill_no'] 		= 	"'".$post['bill_no']."'";
                    $post['bill_amount'] 	= 	"'".$post['bill_amount']."'";
                    $post['bill_passed'] 	= 	"'".$post['bill_passed']."'";
                    $post['tds_ded'] 		= 	"'".$post['tds_ded']."'";
                    $post['pay_type'] 		= 	"'".$dataX['CollectionUpdate']['pay_type']."'";
                    $checkTotal += $post['net_amount'];						
                    $post['net_amount'] 	= 	"'".$post['net_amount']."'";
                    $post['deduction'] 		= 	"'".$post['deduction']."'";
                    $post['status'] 		= 	"'".$post['status']."'";
                    $post['remarks'] 		= 	"'".addslashes($post['remarks'])."'";
                    $post['collection_id']	=	$id;
                        $this->CollectionParticularsUpdate->updateAll($post,array('PaymentId' =>$TMPKey[$i++]));				
                endforeach; 
                unset($TMPCollectionParticular);
                    
              unset($dataX);		
                
                
                    //$OtherKey = array_keys($OtherTMPDeductions);
                $i = 0; $otherDeduction=0;
                foreach($OtherTMPDeductions as $OtherKey=>$post):
                    $checkTotal -= $post['other_deduction'];
                    $otherDeduction += $post['other_deduction'];
                    $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                    $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                    $post['collection_id']		=	"$id";
                    $this->OtherDeductionUpdate->updateAll($post,array('PaymentId' =>$OtherKey));				
                endforeach; unset($OtherTMPDeductions);
                
                                    //print_r($OtherBillTMPDeductions); exit;
                                        $i = 0; $otherBillDeduction=0; 
                                        foreach($OtherBillTMPDeductions as $OtherBillKey=>$post):
                                            $checkTotal -= $post['other_deduction'];
                                            $otherBillDeduction += $post['other_deduction'];
                                            $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                                            $post['bill_no'] 		= 	"'".addslashes($post['bill_no'])."'";
                                            $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                                            $post['collection_id']		=	"'".$id."'"; 
                                            $this->OtherBillDeductionUpdate->updateAll($post,array('PaymentId' =>$OtherBillKey));
                                            
                                        endforeach; unset($OtherBillTMPDeductions);
                 
                $dataY = $this->CollectionParticularsUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));

                if(!empty($this->request->data['Collection']['PaymentFile']['name']))
                {
                     $file = $this->request->data['Collection']['PaymentFile'];
                     $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                     move_uploaded_file($file['tmp_name'],WWW_ROOT."/CollectionImage/".$id.$file['name']);
                     $PaymentFile =addslashes($id.$file['name']);
                     $this->Collection->updateAll(array('PaymentFile'=>"'$PaymentFile'"),array('id'=>$id)); 
                }
                
                foreach($dataY as $post):
                        $post['CollectionParticularsUpdate'] = Hash::Remove($post['CollectionParticularsUpdate'],'id');
                        $post['CollectionParticularsUpdate'] = Hash::Remove($post['CollectionParticularsUpdate'],'PaymentId');
                        if(!empty($PaymentFile))
                        {
                            $post['CollectionParticularsUpdate']['PaymentFile'] = $PaymentFile;
                        }    
                        $dataZ[] = $post['CollectionParticularsUpdate'];
                        $company = $post['CollectionParticularsUpdate']['company_name'];
                        $branch = $post['CollectionParticularsUpdate']['branch_name'];
                        $finance_year = $post['CollectionParticularsUpdate']['financial_year'];
                        $bill_no = $post['CollectionParticularsUpdate']['bill_no'];

                        $inv = $this->InitialInvoice->query("update tbl_invoice ti INNER JOIN cost_master cm ON 
                        ti.cost_center = cm.cost_center set ti.PaymentStatus='1' WHERE SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no'
                        AND company_name='$company' AND finance_year = '$finance_year' and ti.branch_name='$branch' ");

                        $dataEmail[] = $this->InitialInvoice->query("SELECT cce.pm,cce.admin,cce.bm,cce.rm,cce.corp,cce.ceo FROM tbl_invoice ti
                        INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center
                        INNER JOIN cost_center_email cce ON cce.cost_center = cm.id
                        WHERE cm.company_name='$company' AND  ti.branch_name ='$branch' AND 
                        finance_year='$finance_year' AND SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no' limit 1");
                endforeach;

                   
                $this->CollectionParticulars->saveMany($dataZ); unset($dataY); unset($dataZ);



                $dataY = $this->OtherDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));
                
                //print_r($dataY); exit;
                
                $flag = false;
                foreach($dataY as $post):
                    $post['OtherDeductionUpdate'] = Hash::Remove($post['OtherDeductionUpdate'],'id');
                    $post['OtherDeductionUpdate'] = Hash::Remove($post['OtherDeductionUpdate'],'PaymentId');
                    if(!empty($PaymentFile))
                    {
                        $post['OtherDeductionUpdate']['PaymentFile'] = $PaymentFile;
                    }    
                    $dataZ[] = $post['OtherDeductionUpdate'];
                    $flag = true;
                endforeach;
                if($flag)
                {
                    $this->OtherDeduction->saveMany($dataZ);		
                    unset($dataY); unset($dataZ);
                }
              $dataYA = $this->OtherBillDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));
               //print_r($id); exit;                         
					$flag = false;
					foreach($dataYA as $post):
						$post['OtherBillDeductionUpdate'] = Hash::Remove($post['OtherBillDeductionUpdate'],'id');
                                                $post['OtherBillDeductionUpdate'] = Hash::Remove($post['OtherBillDeductionUpdate'],'PaymentId');
                                                if(!empty($PaymentFile))
                                                {
                                                    $post['OtherBillDeductionUpdate']['PaymentFile'] = $PaymentFile;
                                                }   
						$dataZA[] = $post['OtherBillDeductionUpdate'];
						$flag = true;
					endforeach;
                                        
                                        
					if($flag)
					{
                                            $this->OtherBillDeduction->saveMany($dataZA);
                                            unset($dataYA); unset($dataZA);
					}
                
              $this->CollectionUpdate->query("truncate table bill_pay_particulars_update");  
              $this->CollectionUpdate->query("truncate table other_deductions_update");  
              $this->CollectionUpdate->query("truncate table other_deductions_bill_update");  
              $this->CollectionUpdate->query("truncate table tbl_payment_update");  
            $this->CollectionUpdate->deleteAll(array('id'=>$PaymentId));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$PaymentId));
            $this->CollectionUpdate->deleteAll(array('id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>$PaymentId));
            
            $this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>$id)); 
            $this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>$PaymentId)); 
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$id)); 
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$PaymentId)); 
            
            $this->OtherBillDeductionUpdate->deleteAll(array('PaymentId'=>$id)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('PaymentId'=>$PaymentId)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('collection_id'=>$id)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('collection_id'=>$PaymentId)); 
            
              return $this->redirect(array('controller'=>'Collections','action' => 'view_payment'));  
                    //App::uses('sendEmail', 'custom/Email');

                $bill = $billNo[0];
                $inv = $this->InitialInvoice->query("SELECT cm.client FROM tbl_invoice ti INNER JOIN cost_master cm ON 
                ti.cost_center = cm.cost_center WHERE SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill'
                AND company_name='$company_name' AND finance_year = '$finance_year' limit 1");

                $client = $inv[0]['cm']['client']; 

                $sub = "Payment Update $payDate";
                $msg = "Deal All,<br><br>";
                $msg .= "$branch_name <br>";
                $msg .= "Greetings <br><br>";
                $msg .= "Payment for bill no. ".implode(", ",$billNo)." of $client has been received for Rs. $checkTotal, deduction of $deduction ";
                $msg .= "and Other Deduction for Rs. $otherDeduction";
                $msg .= "<br><br>This is System Genrated mail, Please don't reply.";
                $msg .= "<br>Regards";
                $msg .= "<br><b>I-Spark</b>";

                foreach($dataEmail as $email)
                { 
                    $pm = array(); $admin = array(); $bm = array(); $corp = array();
                    $rm = array(); $ceo = array();
                    if(!empty($email))
                    {
                        if(!empty($email[0]['cce']['pm']))
                        {
                            $pm =explode(",",$email[0]['cce']['pm']) ;
                            foreach($pm as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['admin']))
                        {
                            $admin =explode(",",$email[0]['cce']['admin']) ;
                            foreach($admin as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['bm']))
                        {
                            $bm =explode(",",$email[0]['cce']['bm']) ;
                            foreach($bm as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['corp']))
                        {
                            $corp =explode(",",$email[0]['cce']['corp']) ;
                            foreach($corp as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['rm']))
                        {
                            $rm =explode(",",$email[0]['cce']['rm']) ;
                            foreach($rm as $c)
                            {
                                if(!empty($c))
                                {
                                    $cc[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['ceo']))
                        {
                            //$cc[] = "anil.goar@teammas.in";
                            //$cc[] = "krishna.kumar@teammas.in";
                            $ceo =explode(",",$email[0]['cce']['ceo']) ;
                            foreach($ceo as $c)
                            {
                                if(!empty($c))
                                {
                                    $cc[] = $c; 
                                }
                            }
                        }
                    }
                }

            $to = array_unique($to);
            $cc = array_unique($cc);
            $mail = new sendEmail();
            if(!empty($to))
            {
                try{
                //$mail-> multiple($to,$cc,$msg,$sub);
                }
                catch(SocketException $e)
                {
                    $error = "Email Not Send";
                }
            }
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".' The Collection for amount '.$amount.' to '.$branch_name.' has been saved.'."$error</b></h4>"));
            return $this->redirect(array('controller'=>'Collections','action' => 'view_payment'));  
            }
        }
    }
    
    public function approve_payment()
    {
        $this->layout="home";
        $this->set('Data',$this->Collection->find('all',array('conditions'=>"Id>700 and Approve_Payment=0")));
    }
    public function edit_payment2() 
    {
        $this->layout='ajax';
        $Id = $this->params->query['id']; 
        $username = $this->Session->read('username');
        if(!empty($Id))
        {
            $payment  = $this->Collection->find('first',array('conditions'=>array('Id'=>$Id)));
            $collection['CollectionUpdate'] = $payment['Collection'];
            
            $this->CollectionUpdate->deleteAll(array('Id'=>$Id));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$Id));
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$Id));
            
            if($this->CollectionUpdate->save($collection))
            {
                $Parts = $this->CollectionParticulars->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataX = array();
                foreach($Parts as $post)
                {
                    $data = $post['CollectionParticulars'];
                    $dataX[] =  $data;
                }
                
                $OD = $this->OtherDeduction->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataY = array();
                foreach($OD as $post)
                {
                    $data = $post['OtherDeduction'];
                    $dataY[] =  $data;
                }
                
                $BOD = $this->OtherBillDeduction->find('all',array('conditions'=>array('collection_id'=>$Id)));
                
                $dataZ = array();
                foreach($BOD as $post)
                {
                    $data = $post['OtherBillDeduction'];
                    $dataZ[] =  $data;
                }
                
                if(!empty($dataX))
                {
                        $this->CollectionParticularsUpdate->saveMany($dataX);
           
                }
                
                if(!empty($dataY))
                {
                    
                    $this->OtherDeductionUpdate->saveMany($dataY); 
                }
                
                if(!empty($dataZ))
                {
                    $this->OtherBillDeductionUpdate->saveMany($dataZ); 
                }
                
                
                $this->redirect(array('action'=>'edit_payment3','?'=>array('Id'=>$Id)));
                
                
                
            }
            else
            {
                $msg = "Please Try Again";
            }
        }
        else
        {
            $msg = "Please Try Again!";
        }
        
    }
    
    public function edit_payment3()
    {
        $this->layout='home';
        $Id = $this->params->query['Id']; 
        $this->set('updateId',$Id);
        $username = $this->Session->read('username');
        $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
        $this->set('company_master',$this->Addcompany->find('all',array('fields' =>array('company_name'))));
        
        $this->set('bank_master',$this->Bank->find('all',array('fields'=>array('bank_name'))));
        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
        if($data = $this->CollectionUpdate->find('first',array('conditions'=>array('Id'=>$Id))))
        {
            $data = array_values($data['CollectionUpdate']);
            
            $this->set('payment_master',$data);
            $this->set('result',$this->CollectionParticularsUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
            $this->set('result2',$this->OtherDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
            //$this->set('result3',$this->OtherBillDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$Id))));
            print_r($this->OtherBillDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$Id)))); exit;
        }
        else
        {
            $msg = "Please Try Again";
        }
    }
    
     public function update_payment2()
    {
        $this->layout = "ajax";
        if($this->request->is("POST"))
        { 
            $checkTotal = 0;
            $data = $this->params->data;
            //print_r($data); exit;
            $PaymentId = $this->request->data['Collection']['UpdateId'];
            
            
            $username = $this->Session->read('username');
            $TMPCollectionParticular = $data['CollectionParticularsUpdate'];
            $OtherTMPDeductions = array();
            if(isset($data['OtherDeduction'])){
            $OtherTMPDeductions = $data['OtherDeduction'];}

            if(isset($data['OtherBillDeductionUpdate'])){
            $OtherBillTMPDeductions = $data['OtherBillDeductionUpdate'];}
            
            //$this->set('data',$data['TMPCollectionParticulars']);
            $data = Hash::Remove($data,'TMPCollectionParticulars');
            $data = Hash::Remove($data,'OtherTMPDeduction');
            $data = Hash::Remove($data,'OtherBillDeductionUpdate');
            
            $branch_name = $data['Collection']['branch_name'];
            $amount = $data['Collection']['pay_amount'];
            $payDate = $data['Collection']['pay_dates'];
            $finance_year =  $data['Collection']['financial_year'];
            $company_name =  $data['Collection']['company_name'];
            
            $dataX = $this->CollectionUpdate->find('first',array('conditions'=>array('id'=>$PaymentId)));
            $total = $dataX['CollectionUpdate']['pay_amount'];
            $dataX['CollectionUpdate'] = Hash::Remove($dataX['CollectionUpdate'],'id');
            $dataX['CollectionUpdate'] = Hash::Remove($dataX['CollectionUpdate'],'PaymentId');
            $dataX['Approve_Payment'] = 1;

            if($this->Collection->save($dataX['CollectionUpdate']))
            { 
                $this->Collection->deleteAll(array('id'=>$PaymentId));
                
                $this->CollectionParticulars->deleteAll(array('collection_id'=>$PaymentId));
                $this->OtherDeduction->deleteAll(array('collection_id'=>$PaymentId));
               $id = $this->Collection->getLastInsertID(); 
                

                $TMPKey = array_keys($TMPCollectionParticular);	
                //print_r($TMPCollectionParticular); exit;
                $i = 0;

                foreach($TMPCollectionParticular as $post):
                    $billNo[] = $post['bill_no'];
                    $netAmount += $post['net_amount'];
                    $deduction += $post['deduction'];
                    $post['bill_no'] 		= 	"'".$post['bill_no']."'";
                    $post['bill_amount'] 	= 	"'".$post['bill_amount']."'";
                    $post['bill_passed'] 	= 	"'".$post['bill_passed']."'";
                    $post['tds_ded'] 		= 	"'".$post['tds_ded']."'";
                    $post['pay_type'] 		= 	"'".$dataX['CollectionUpdate']['pay_type']."'";
                    $checkTotal += $post['net_amount'];						
                    $post['net_amount'] 	= 	"'".$post['net_amount']."'";
                    $post['deduction'] 		= 	"'".$post['deduction']."'";
                    $post['status'] 		= 	"'".$post['status']."'";
                    $post['remarks'] 		= 	"'".addslashes($post['remarks'])."'";
                    $post['collection_id']	=	$id;
                        $this->CollectionParticularsUpdate->updateAll($post,array('PaymentId' =>$TMPKey[$i++]));				
                endforeach; 
                unset($TMPCollectionParticular);
                    
              unset($dataX);		
                
                $OtherKey = array_keys($OtherTMPDeductions);
                $i = 0; $otherDeduction=0;
                foreach($OtherTMPDeductions as $post):
                    $checkTotal -= $post['other_deduction'];
                    $otherDeduction += $post['other_deduction'];
                    $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                    $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                    $post['collection_id']		=	$id;
                    $this->OtherDeductionUpdate->updateAll($post,array('PaymentId' =>$OtherKey[$i++]));				
                endforeach; unset($OtherTMPDeductions);
                    
                $OtherBillKey = array_keys($OtherBillTMPDeductions);
                                        $i = 0; $otherBillDeduction=0;
                                        foreach($OtherBillTMPDeductions as $post):
                                            $checkTotal -= $post['other_deduction'];
                                            $otherBillDeduction += $post['other_deduction'];
                                            $post['other_deduction'] 	= 	"'".$post['other_deduction']."'";
                                            $post['bill_no'] 		= 	"'".addslashes($post['bill_no'])."'";
                                            $post['other_remarks'] 		= 	"'".addslashes($post['other_remarks'])."'";
                                            $post['collection_id']		=	"'".$id."'";
                                            $this->OtherBillDeductionUpdate->updateAll($post,array('id' =>$OtherBillKey[$i++]));				
                                        endforeach; unset($OtherBillTMPDeductions);
                 
                $dataY = $this->CollectionParticularsUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));

                if(!empty($this->request->data['Collection']['PaymentFile']['name']))
                {
                     $file = $this->request->data['Collection']['PaymentFile'];
                     $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                     move_uploaded_file($file['tmp_name'],WWW_ROOT."/CollectionImage/".$id.$file['name']);
                     $PaymentFile =addslashes($id.$file['name']);
                     $this->Collection->updateAll(array('PaymentFile'=>"'$PaymentFile'"),array('id'=>$id)); 
                }
                
                foreach($dataY as $post):
                        $post['CollectionParticularsUpdate'] = Hash::Remove($post['CollectionParticularsUpdate'],'id');
                        $post['CollectionParticularsUpdate'] = Hash::Remove($post['CollectionParticularsUpdate'],'PaymentId');
                        if(!empty($PaymentFile))
                        {
                            $post['CollectionParticularsUpdate']['PaymentFile'] = $PaymentFile;
                        }    
                        $dataZ[] = $post['CollectionParticularsUpdate'];
                        $company = $post['CollectionParticularsUpdate']['company_name'];
                        $branch = $post['CollectionParticularsUpdate']['branch_name'];
                        $finance_year = $post['CollectionParticularsUpdate']['financial_year'];
                        $bill_no = $post['CollectionParticularsUpdate']['bill_no'];

                        $inv = $this->InitialInvoice->query("update tbl_invoice ti INNER JOIN cost_master cm ON 
                        ti.cost_center = cm.cost_center set ti.PaymentStatus='1' WHERE SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no'
                        AND company_name='$company' AND finance_year = '$finance_year' and ti.branch_name='$branch' ");

                        $dataEmail[] = $this->InitialInvoice->query("SELECT cce.pm,cce.admin,cce.bm,cce.rm,cce.corp,cce.ceo FROM tbl_invoice ti
                        INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center
                        INNER JOIN cost_center_email cce ON cce.cost_center = cm.id
                        WHERE cm.company_name='$company' AND  ti.branch_name ='$branch' AND 
                        finance_year='$finance_year' AND SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no' limit 1");
                endforeach;

                   
                $this->CollectionParticulars->saveMany($dataZ); unset($dataY); unset($dataZ);



                $dataY = $this->OtherDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));
                $flag = false;
                foreach($dataY as $post):
                    $post['OtherDeductionUpdate'] = Hash::Remove($post['OtherDeductionUpdate'],'id');
                    $post['OtherDeductionUpdate'] = Hash::Remove($post['OtherDeductionUpdate'],'PaymentId');
                    if(!empty($PaymentFile))
                    {
                        $post['OtherDeductionUpdate']['PaymentFile'] = $PaymentFile;
                    }    
                    $dataZ[] = $post['OtherDeductionUpdate'];
                    $flag = true;
                endforeach;
                if($flag)
                {
                    $this->OtherDeduction->saveMany($dataZ);		
                    unset($dataY); unset($dataZ);
                }
                
                $dataYA = $this->OtherBillDeductionUpdate->find('all',array('conditions'=>array('collection_id'=>$id)));
                                        
					$flag = false;
					foreach($dataYA as $post):
						$post['OtherBillDeductionUpdate'] = Hash::Remove($post['OtherBillDeductionUpdate'],'id');
                                                if(!empty($PaymentFile))
                                                {
                                                    $post['OtherBillDeductionUpdate']['PaymentFile'] = $PaymentFile;
                                                }   
						$dataZA[] = $post['OtherBillDeductionUpdate'];
						$flag = true;
					endforeach;
                                print_r($dataZA); exit;        
                                        
					if($flag)
					{
                                            $this->OtherBillDeduction->saveMany($dataZA);
                                            unset($dataYA); unset($dataZA);
					}
             
            $this->CollectionUpdate->query("truncate table bill_pay_particulars_update");  
            $this->CollectionUpdate->query("truncate table other_deductions_update");  
            $this->CollectionUpdate->query("truncate table other_deductions_bill_update");
            $this->CollectionUpdate->query("truncate table tbl_payment_update");
            
            $this->CollectionUpdate->deleteAll(array('id'=>$PaymentId));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$PaymentId));
            $this->CollectionUpdate->deleteAll(array('id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>$PaymentId));
            
            $this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>$id)); 
            $this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>$PaymentId)); 
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$id)); 
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$PaymentId)); 
            $this->CollectionUpdate->deleteAll(array('id'=>$PaymentId));
            $this->CollectionUpdate->deleteAll(array('id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$id));
            $this->CollectionParticularsUpdate->deleteAll(array('PaymentId'=>$TMPKey));
            $this->CollectionParticularsUpdate->deleteAll(array('collection_id'=>$PaymentId));
            
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$id)); 
            $this->OtherDeductionUpdate->deleteAll(array('PaymentId'=>$OtherKey)); 
            $this->OtherDeductionUpdate->deleteAll(array('collection_id'=>$PaymentId)); 
            
            $this->OtherBillDeductionUpdate->deleteAll(array('PaymentId'=>$id)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('PaymentId'=>$PaymentId)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('collection_id'=>$id)); 
            $this->OtherBillDeductionUpdate->deleteAll(array('collection_id'=>$PaymentId)); 
            
              return $this->redirect(array('controller'=>'Collections','action' => 'view_payment2'));  
                    //App::uses('sendEmail', 'custom/Email');

                $bill = $billNo[0];
                $inv = $this->InitialInvoice->query("SELECT cm.client FROM tbl_invoice ti INNER JOIN cost_master cm ON 
                ti.cost_center = cm.cost_center WHERE SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill'
                AND company_name='$company_name' AND finance_year = '$finance_year' limit 1");

                $client = $inv[0]['cm']['client']; 

                $sub = "Payment Approved $payDate";
                $msg = "Deal All,<br><br>";
                $msg .= "$branch_name <br>";
                $msg .= "Greetings <br><br>";
                $msg .= "Payment for bill no. ".implode(", ",$billNo)." of $client has been Approved For received for Rs. $checkTotal, deduction of $deduction ";
                $msg .= "and Other Deduction for Rs. $otherDeduction";
                $msg .= "<br><br>This is System Genrated mail, Please don't reply.";
                $msg .= "<br>Regards";
                $msg .= "<br><b>I-Spark</b>";

                foreach($dataEmail as $email)
                { 
                    $pm = array(); $admin = array(); $bm = array(); $corp = array();
                    $rm = array(); $ceo = array();
                    if(!empty($email))
                    {
                        if(!empty($email[0]['cce']['pm']))
                        {
                            $pm =explode(",",$email[0]['cce']['pm']) ;
                            foreach($pm as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['admin']))
                        {
                            $admin =explode(",",$email[0]['cce']['admin']) ;
                            foreach($admin as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['bm']))
                        {
                            $bm =explode(",",$email[0]['cce']['bm']) ;
                            foreach($bm as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['corp']))
                        {
                            $corp =explode(",",$email[0]['cce']['corp']) ;
                            foreach($corp as $c)
                            {
                                if(!empty($c))
                                {
                                    $to[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['rm']))
                        {
                            $rm =explode(",",$email[0]['cce']['rm']) ;
                            foreach($rm as $c)
                            {
                                if(!empty($c))
                                {
                                    $cc[] = $c; 
                                }
                            }
                        }
                        if(!empty($email[0]['cce']['ceo']))
                        {
                            //$cc[] = "anil.goar@teammas.in";
                            //$cc[] = "krishna.kumar@teammas.in";
                            $ceo =explode(",",$email[0]['cce']['ceo']) ;
                            foreach($ceo as $c)
                            {
                                if(!empty($c))
                                {
                                    $cc[] = $c; 
                                }
                            }
                        }
                    }
                }

            $to = array_unique($to);
            $cc = array_unique($cc);
            $mail = new sendEmail();
            if(!empty($to))
            {
                try{
                //$mail-> multiple($to,$cc,$msg,$sub);
                }
                catch(SocketException $e)
                {
                    $error = "Email Not Send";
                }
            }
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".' The Collection for amount '.$amount.' to '.$branch_name.' has been Approved.'."$error</b></h4>"));
            return $this->redirect(array('controller'=>'Collections','action' => 'view_payment2'));  
            }
        }
    }
}

?>