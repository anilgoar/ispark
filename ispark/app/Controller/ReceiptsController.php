<?php
class ReceiptsController extends AppController 
{
    public $uses=array('Receipt','Addbranch','Addcompany','InitialInvoice','CostCenterMaster','User','BillMaster');
    public $components = array('RequestHandler');
    public $helpers = array('Js');

    public function beforeFilter()
    {
        	parent::beforeFilter();
			
			//$this->layout='home';
			$this->Auth->deny('index');
			$this->Auth->deny('get_receipt');
			$this->Auth->deny('add','edit','update');
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
				
				if(in_array('28',$roles)){$this->Auth->allow('index');
			$this->Auth->allow('get_receipt','edit','update');
			$this->Auth->allow('add');}
				
			}			
			if ($this->request->is('ajax'))
			 {
				$this->render('contact-ajax-response', 'ajax');
			 }
    	}
		
    	public function index() 
		{
			$this->layout='home';
                        $conditions =array();
                        $conditions2 =array();
                        if($this->Session->read('role')!='admin')
                        {
                            $conditions=array('branch_name'=>$this->Session->read('branch_name'));
                            $conditions2=array('BranchName'=>$this->Session->read('branch_name'));
                        }
			$this->set('company_master', $this->Addcompany->find('all',array('fields'=>array('company_name'))));
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'),'conditions'=>$conditions)));
			$this->set('receipt_master', $this->Receipt->find('all',array('order'=>array('id'=>'desc'),'conditions'=>$conditions2)));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array("finance_year"=>'14-15')))));
        }
		
		public function get_receipt()
		{
		 	$this->layout = 'ajax';
			$result = $this->params->query;
			$comp_name = $result['company_name'];
			$branch_name = $result['branch_name'];
			$financial_year = $result['financial_year'];
			$invoice = $result['invoice'];
			
			
			$data=$this->InitialInvoice->query("SELECT t1.id,t1.bill_no FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center WHERE ReceiptStatus='0' and t2.company_name='$comp_name' AND branch_name='$branch_name' AND finance_year='$financial_year' AND SUBSTRING_INDEX(t1.bill_no,'/',1)='$invoice'");
			
			//$conditions=array(''=>$data['Addclient']['client_name']);
			//$data=$this->Addclient->find('all',array('fields'=>array('client_name'),'conditions'=>array($conditions)));
			$this->set('invoice_master',$data);
			//$this->set('res',$data);
		}
		
public function add()
{
    $this->layout='home';
    if ($this->request->is('post'))
    {
        $id = $this->params['data']['id'];
        $invno = $this->params['data']['InvoiceNo'];
        $subdate = explode("-",$this->params['data']['Receipt']['SubmitedDates']);
        $subdate1[0] = $subdate[2];
        $subdate1[1] = $subdate[1];
        $subdate1[2] = $subdate[0];
        $subdate = implode("-",$subdate1);
        
        
        $expdate = explode("-",$this->params['data']['Receipt']['ExpDatesPayment']);
        $expdate1[0] = $expdate[2];
        $expdate1[1] = $expdate[1];
        $expdate1[2] = $expdate[0];
        $expdate = implode("-",$expdate1);
        
        $remarks = addslashes($this->params['data']['Receipt']['Remarks']);
        $b_name = '';
        $branch_name = $this->params['data']['Receipt']['BranchName'];
        $data = $this->params->data['Receipt'];
	
        if(isset($data['ReceiptFile']))
        {
            $files=$data['ReceiptFile'];
            //$data=Hash::remove($data['Receipt'],'ReceiptFile');
            $b_name = $this->params['data']['Receipt']['ReceiptFile']['name'];
        }
	
        $data['Invoiceid']=$id;
        $data['InvoiceNo']=$invno;
        $data['ReceiptFile']=$b_name;
        $data['SubmitedDates']=$subdate;
        $data['ExpDatesPayment']=$expdate;
        $data['CreateDate']=date('Y-m-d h:i:s');
        $data['Remarks'] = $remarks;
        
        //for eptp and ptp updation
        $eptp_act_date = $expdate;
        $eptp_act_remarks = $remarks;
        
		
        $this->set('res',$this->params->data);
        if(!empty($files))
        {
                    foreach($files as $file)
                    {
                        $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                        move_uploaded_file($file['tmp_name'],WWW_ROOT."/receipt_file/".$file['name']);
                        $ReceiptFile[] =$file['name'].",";
                    }
                    $fileName = implode(',',$ReceiptFile);
                    $data['ReceiptFile'] = $fileName;
        }
        
        if(!empty($data['Invoiceid']))
        {
            if(!$this->Receipt->find('first',array('conditions'=>array('Invoiceid'=>$data['Invoiceid']))))
                {
                    $this->Receipt->save($data);
                    App::uses('sendEmail', 'custom/Email');
                    $inv = $this->InitialInvoice->find('first',array('fields'=>array('cost_center','grnd','his_eptp_act_date','his_eptp_act_remarks'),
                    'conditions'=>array('id'=>$id)));
                    $grnd = $inv['InitialInvoice']['grnd'];
                    $cost_center = $inv['InitialInvoice']['cost_center'];
                    $cost = $this->CostCenterMaster->find('first',array('fields'=>array('client'),
                    'conditions'=>array('cost_center'=>$cost_center)));
                    $client = $cost['CostCenterMaster']['client'];

                    $his_eptp_act_date = addslashes($inv['InitialInvoice']['eptp_act_date']).','.$expdate;
                    $his_eptp_act_remarks = addslashes($inv['InitialInvoice']['eptp_act_remarks']).','.$remarks;
                    
                    $email = $this->InitialInvoice->query("SELECT cce.pm,cce.admin,cce.bm,cce.rm,cce.corp,cce.ceo FROM 
                    cost_master cm INNER JOIN cost_center_email cce ON cce.cost_center = cm.id WHERE cm.cost_center='$cost_center' LIMIT 1");
                                                    //print_r($client); exit; 
        
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

                                                            $expdate = date_format(date_create($expdate), "d-M-Y");
                                                            $sub = "PTP";
                                                            $msg ="Dear All,<br><br>"; 
                                                            $msg .= "$branch_name PTP for Bill no. $invno for Rs. $grnd of $client is $expdate";
                                                            $msg .="<br><br>"; 
                                                            $msg .="This is System Genrated mail, Please don't reply.<br>";
                                                            $msg .="Regards<br>"; 
                                                            $msg .="<b>I-Spark</b>"; 
                                                            $to = array_unique($to);
                                                            $cc = array_unique($cc);
                                                            $mail = new sendEmail();
                                                            if(!empty($to))
                                                            {
                                                                $mail-> multiple($to,$cc,$msg,$sub);
                                                            }
    }
    
    else {$this->Session->setFlash(__("Receipt Already Exists"));
    return $this->redirect(array('action'=>'index'));
    
    }}
                                
                                
				$this->InitialInvoice->updateAll(array('ReceiptStatus'=>'1','eptp_act_date'=>$eptp_act_date,'eptp_act_remarks'=>$eptp_act_remarks,'his_eptp_act_date'=>$his_eptp_act_date,'his_eptp_act_remarks'=>$his_eptp_act_remarks),array('id'=>$id));
				$this->Session->setFlash(__("Receipt uploaded succesfully"));
				return $this->redirect(array('action'=>'index'));					
			}
		}
public function edit() 
{
    $this->layout='home';
    $id = $this->params->query['id'];
    $this->set('company_master', $this->Addcompany->find('all',array('fields'=>array('company_name'))));
    $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
    $this->set('receipt_master', $this->Receipt->find('first',array('conditions'=>array('id'=>$id))));
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array("finance_year"=>'14-15')))));
}

public function update() 
{
    $this->layout='home';
    
    $receipt = $this->request->data['Receipt'];
    //print_r($receipt); exit;
    $data['CompanyName'] = addslashes($receipt['CompanyName']);
    $data['BranchName'] = addslashes($receipt['BranchName']);
    $data['FinancialYear'] = addslashes($receipt['FinancialYear']);
    
    $invoiceNo = addslashes($receipt['invoiceNo']);
    
    $subdate = explode("-",$this->params['data']['Receipt']['SubmitedDates']);
        $subdate1[0] = $subdate[2];
        $subdate1[1] = $subdate[1];
        $subdate1[2] = $subdate[0];
    $data['SubmitedDates'] = implode("-",$subdate1);    
    $data['SubmitedTo'] = addslashes($receipt['SubmitedTo']);
    
    $expdate = explode("-",addslashes($receipt['ExpDatesPayment']));
        $expdate1[0] = $expdate[2];
        $expdate1[1] = $expdate[1];
        $expdate1[2] = $expdate[0];
        $expdate = implode("-",$expdate1);
    $data['ExpDatesPayment'] = $expdate;    
    $data['Remarks'] = addslashes($receipt['Remarks']);
    $id = $receipt['id'];
    
    $receiptFile = $receipt['ReceiptFile'];
    $filepath = array();
    if(is_array($receiptFile))
    {
        foreach($receiptFile as $rec)
        {
            if(!empty($rec['name']))
            {
                //move_uploaded_file($filepath, $destination);
                $rec['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $rec['name']);
                move_uploaded_file($rec['tmp_name'],WWW_ROOT."/receipt_file/".$rec['name']);
                $filepath[] = $rec['name'];
            }
        }
    }
    else if(!empty($receiptFile['name']))
    {
        $receiptFile['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $receiptFile['name']);
        move_uploaded_file($receiptFile['tmp_name'],WWW_ROOT."/receipt_file/".$receiptFile['name']);
       $filepath[] = $receiptFile['name'];
    }
    
    if(!empty($filepath))
    {
     $data['ReceiptFile']=implode(",",$filepath);
    }
    
    
    
    foreach($data as $k=>$v)
    {
        $dataX[$k] = "'".addslashes($v)."'";
    }
    
    if($this->Receipt->updateAll($dataX,array('id'=>$id)))
    { 
        $this->Session->setFlash("Receipt Updated Successfully");
        App::uses('sendEmail', 'custom/Email');
        
        $inv = $this->InitialInvoice->query("SELECT cm.client,ti.grnd,ti.bill_no,ti.id,ti.branch_name FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
INNER JOIN receipt_master rm ON rm.Invoiceid = ti.id WHERE rm.id = '$id'");
        
        //print_r($inv); exit;
        $grnd = $inv[0]['ti']['grnd'];
        $invNo = $inv[0]['ti']['bill_no'];
        $billId = $inv[0]['ti']['id'];
        $b_name = $inv[0]['ti']['branch_name'];
                                        
        $email = $this->InitialInvoice->query("SELECT cce.pm,cce.admin,cce.bm,cce.rm,cce.corp,cce.ceo FROM tbl_invoice ti
                                                INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center
                                                INNER JOIN cost_center_email cce ON cce.cost_center = cm.id
                                                WHERE ti.id='$billId' limit 1");
        
        
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
        $date2 = date_format(date_create($date2),"d-M-Y");
        $sub = "Fresh PTP Entered";
        $msg ="Dear All,<br><br>"; 
        $msg .= "$b_name PTP for Bill no. $invNo for Rs. $grnd of $client has been revised to $date2";
        $msg .="<br><br>"; 
        $msg .="This is System Genrated mail, Please don't reply.<br>";
        $msg .="Regards<br>"; 
        $msg .="<b>I-Spark</b>"; 
        
        $to = array_unique($to);
        $cc = array_unique($cc);
        $mail = new sendEmail();
        if(!empty($to))
        {
            $mail-> multiple($to,$cc,$msg,$sub);
        }
    }
    else
    {
        $this->Session->setFlash("Receipt Not Updated. Please Try Again");
    }
    $this->redirect(array('controller'=>'receipts','action'=>'index'));
}
}

?>