4<?php
	class ActionsController extends AppController 
	{
            public $uses=array('InitialInvoice','Addbranch','AgreementParticular','');
		public $components = array('RequestHandler');
		public $helpers = array('Js');
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
				
                    if(in_array('57',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('edit','get_export');}
                    else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}
		}
            }
		
            public function index() 
            {
                $branchName = $this->Session->read('branch_name');
                
                if($this->Session->read('role')=='admin')
                {
                    $branchName = '';
                }
                else 
                    {
                        $branchName = "and cm.branch='$branchName'";
                    }
                
                $inv = $this->InitialInvoice->query("SELECT cm.Id,cm.branch,cm.cost_center,cm.client,cm.po_required,cm.grn,rm.invoiceNo,
                    (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),'PO Pending',
IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),'GRN Pending','Submitted'))) `bill_status`
 FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
 LEFT JOIN `receipt_master` rm ON rm.Invoiceid = ti.id
 LEFT JOIN bill_pay_particulars bpp ON cm.company_name = bpp.company_name AND
ti.finance_year = bpp.financial_year AND SUBSTRING_INDEX(ti.bill_no,'/','-1') = bpp.bill_no
WHERE bpp.bill_no IS NULL $branchName order by cm.branch");
                
                $data = array();   
                foreach($inv as $post)
                {
                    if(key_exists($post['cm']['cost_center'], $data))
                    {
                        if($data[$post['cm']['cost_center']]['po_required']=='NO')
                        {
                            $data[$post['cm']['cost_center']]['po_status'] = '&#x25CF';
                        }
                        else if($post['0']['bill_status']=='PO Pending')
                        {
                            $data[$post['cm']['cost_center']]['po_status'] = '&#x2573';
                        }

                        if($data[$post['cm']['cost_center']]['grn']=='NO')
                        {
                            $data[$post['cm']['cost_center']]['grn_status'] = '&#x25CF';
                        }
                        else if($post['0']['bill_status']=='GRN Pending')
                        {
                            $data[$post['cm']['cost_center']]['grn_status'] = '&#x2573';
                        }

                        if($post['0']['bill_status']!='Submitted')
                        {
                            $data[$post['cm']['cost_center']]['sub_status'] = '&#x2573';
                        }

                        if(empty($post['rm']['invoiceNo']))
                        {
                         $data[$post['cm']['cost_center']]['ptp_status']='&#x2573';
                        }
                    }
                    else
                    {
                        $data[$post['cm']['cost_center']]['branch']=$post['cm']['branch'];
                        $data[$post['cm']['cost_center']]['client']=$post['cm']['client'];
                        $data[$post['cm']['cost_center']]['po_required']=$post['cm']['po_required'];
                        $data[$post['cm']['cost_center']]['grn']=$post['cm']['grn'];
                        $data[$post['cm']['cost_center']]['bill_status']=$post['0']['bill_status'];
                        
                        $cost_id = $post['cm']['Id'];
                        $Agreement = $this->Addbranch->query("Select * from agreement_particulars where cost_center='$cost_id' and curdate() BETWEEN periodTo AND periodFrom limit 1");
                        if(!empty($Agreement))
                        $data[$post['cm']['cost_center']]['Agreement'] = '&radic;';
                        else
                            $data[$post['cm']['cost_center']]['Agreement'] = '&#x2573';
                        
                        $PO = $this->Addbranch->query("Select * from po_number_particulars where cost_center='$cost_id' and curdate() BETWEEN periodTo AND periodFrom limit 1");
                        if(!empty($PO))
                        $data[$post['cm']['cost_center']]['PO'] = '&radic;';
                        else
                            $data[$post['cm']['cost_center']]['PO'] = '&#x2573';
                        

                        if($post['cm']['po_required']=='No')
                        {
                            $data[$post['cm']['cost_center']]['po_status'] = '&#x25CF';
                        }
                        else if($post['0']['bill_status']=='PO Pending')
                        {
                            $data[$post['cm']['cost_center']]['po_status'] = '&#x2573';
                        }
                        else
                        {
                            $data[$post['cm']['cost_center']]['po_status'] = '&radic;';
                        }

                        if($post['cm']['grn']=='No')
                        {
                            $data[$post['cm']['cost_center']]['grn_status'] = '&#x25CF';
                        }
                        else if($post['0']['bill_status']=='GRN Pending')
                        {
                            $data[$post['cm']['cost_center']]['grn_status'] = '&#x2573';
                        }
                        else
                        {
                            $data[$post['cm']['cost_center']]['grn_status'] = '&radic;';
                        }

                        if($post['0']['bill_status']=='Submitted')
                        {
                            $data[$post['cm']['cost_center']]['sub_status'] = '&radic;';
                        }
                        else
                        {
                            $data[$post['cm']['cost_center']]['sub_status'] = '&#x2573;';
                        }

                        if(empty($post['rm']['invoiceNo']))
                        {
                         $data[$post['cm']['cost_center']]['ptp_status']='&#x2573';
                        }
                        else
                        {
                            $data[$post['cm']['cost_center']]['ptp_status']='&radic;';
                        }
                    }
                }
                
                
                
                $this->set('data',$data);
                
            }
	   
	   public function add($branch_name=null) 
	   {
        	if ($this->request->is('post')) 
		{
                    $this->Addbranch->create();
				
                    if ($this->Addbranch->save($this->request->data))
                    {
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
                    $this->Addbranch->create();
                    $data=array('branch_name'=>"'".$this->request->data['Addbranch']['branch_name']."'",
                    'branch_code'=>"'".$this->request->data['Addbranch']['branch_code']."'",'active'=>$this->request->data['Addbranch']['active']);
                    
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
            
            public function get_export()
            {
                $this->layout="ajax";
                $data = $this->Addbranch->query("SELECT *
 FROM (SELECT bpp.branch_name,bpp.pay_dates `createdate`, CONCAT(bpp.pay_type,bpp.pay_no) `ChequeNo`,CONCAT(bpp.pay_type,bpp.pay_no,bpp.pay_amount) `ChequeNo1`,
cm.client `client`,bpp.financial_year,bpp.company_name `company_name`,
DATE_FORMAT(bpp.pay_dates,'%b %d %Y') `Dates`, 
SUM(IF(bpp.bill_amount IS NULL OR bpp.bill_amount ='',0,bpp.bill_amount)) `net_amount`,
SUM(IF(bpp.tds_ded IS NULL OR bpp.tds_ded ='',0,bpp.tds_ded)) `TDS`,
SUM(IF(bpp.deduction IS NULL OR bpp.deduction ='',0,bpp.deduction)) `Other Ded`,
IF(bpp.pay_amount IS NULL OR bpp.pay_amount = '',0,bpp.pay_amount) `ChequeAmount`,
SUM((IF(bpp.bill_amount IS NULL OR bpp.bill_amount = '',0,bpp.bill_amount)-IF(bpp.tds_ded IS NULL OR bpp.tds_ded ='',0,bpp.tds_ded)-
IF(bpp.deduction IS NULL OR bpp.deduction ='',0,bpp.deduction))) `sdfsdf`
 FROM tbl_invoice ti INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center
 INNER JOIN bill_pay_particulars bpp 
 ON  bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1) AND bpp.financial_year = ti.finance_year AND bpp.company_name = cm.company_name AND bpp.branch_name = ti.branch_name 
 GROUP BY CONCAT(pay_type,pay_no),bpp.pay_amount,bpp.branch_name,bpp.pay_type_dates ORDER BY branch_name ) AS tab
  LEFT JOIN (SELECT CONCAT(pay_type,pay_no,pay_amount) `ChequeNo`,SUM(IF(other_deduction IS NULL OR other_deduction = '',0,other_deduction)) `other_deduction` FROM other_deductions GROUP BY CONCAT(pay_type,pay_no),pay_amount) AS tab2
  ON tab.ChequeNo1 = tab2.ChequeNo  order by tab.branch_name"); 
                
                $this->set("result",$data);
            }
}

?>