<?php
class MasterNationalReportsController extends AppController 
{
    public $uses = array('Addbranch','CostCenterMaster','Addcompany');
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        
        $this->layout='home';
        if(!$this->Session->check("username"))
        {
                return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            if(in_array('62',$roles)){$this->Auth->allow('index','index2','get_master_process_brief_wise','get_master_event_brief_wise');$this->Auth->allow('get_master_day_wise');$this->Auth->allow('get_master_process_wise');}
            else{$this->Auth->deny('index');$this->Auth->deny('get_master_day_wise');$this->Auth->deny('get_master_process_wise');}
        }	
    }
	
    
    public function index() 
    {
        $this->layout='home';
        $role = $this->Session->read('role');
        
        if($role == 'admin')
        {
            $branch = '1=1 AND';
        }
        else 
        {
            $branch = "branch='". $this->Session->read("branch_name")."' AND";
        }
        $costArray = $this->CostCenterMaster->query("SELECT cost_center FROM cost_master WHERE $branch active=1");
        
        foreach($costArray as $br)
        {
            $costcenter = $br['cost_master']['cost_center'];
            
            $ToBeBilledArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(pm.provision_balance)balance FROM cost_master cm INNER JOIN provision_master pm 
            ON cm.cost_center = pm.cost_center AND pm.cost_center='$costcenter' AND pm.provision_balance!=0
            WHERE cm.cost_center='$costcenter' group by cm.branch;");
                        
            foreach($ToBeBilledArray as $TBBArray)
            {
                if(key_exists($TBBArray['cm']['branch'], $data))
                {
                    $data[$TBBArray['cm']['branch']]['Tobebilled'] += $TBBArray['0']['balance'];
                }
                else
                {
                    $data[$TBBArray['cm']['branch']]['Tobebilled'] = $TBBArray['0']['balance'];
                }
            }
            
            //print_r($data); exit;
            
            $InProcessingArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' 
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND DATE(rm.ExpDatesPayment)<CURDATE()
WHERE  cm.cost_center = '$costcenter' AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),TRUE,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),TRUE,FALSE))) group by cm.branch");
            
            foreach($InProcessingArray as $IPA)
            {
                    if(key_exists($IPA['cm']['branch'], $data))
                    {
                        $data[$IPA['cm']['branch']]['InProcess'] += $IPA['0']['grnd'];
                    }
                    else
                    {
                        $data[$IPA['cm']['branch']]['InProcess'] = $IPA['0']['grnd'];
                    }
            }
            //print_r($data);
            //exit;
            
            $PytReadyArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(if(DATE(rm.ExpDatesPayment)>CURDATE(),0,ti.grnd)) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' 
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid
WHERE cm.cost_center = '$costcenter' 
    AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.branch");
            
            foreach($PytReadyArray as $PRA)
            {
                if(key_exists($PRA['cm']['branch'], $data))
                {
                    $data[$PRA['cm']['branch']]['PytReady'] += $PRA['0']['grnd'];
                }
                else
                {
                    $data[$PRA['cm']['branch']]['PytReady'] = $PRA['0']['grnd'];
                }
            }
            //print_r($data);
            //exit;
            
            $PayForMonthArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = Month(curdate()) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE  cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.branch");
            
            foreach($PayForMonthArray as $PFMA)
            {
                if(key_exists($PFMA['cm']['branch'], $data))
                {
                        $data[$PFMA['cm']['branch']]['PayForMonth'] += $PFMA['0']['grnd'];
                }
                else
                {
                    $data[$PFMA['cm']['branch']]['PayForMonth'] = $PFMA['0']['grnd'];
                    //$data[$PFMA['cm']['company_name']]['branch'] = $PFMA['cm']['branch'];
                }
            }
            
            //print_r($data);
            //exit;
            
            
            $PostMonthArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+1) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
            foreach($PostMonthArray as $PMA)
            {
                if(key_exists($PMA['cm']['branch'], $data))
                {
                    $data[$PMA['cm']['branch']]['PostMonth'] += $PMA['0']['grnd'];
                }
                else
                {
                    $data[$PMA['cm']['branch']]['PostMonth'] = $PMA['0']['grnd'];
                    //$data[$PMA['cm']['company_name']]['branch'] = $PMA['cm']['branch'];
                }
            }
            
            
//            print_r($data);
//            exit;
            
            $Post2MonthArray = $this->CostCenterMaster->query("SELECT *,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2,
sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 15 AND 21,ti.grnd,0)) w3, sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 22 AND 28,ti.grnd,0)) w4,
sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 29 AND 31,ti.grnd,0)) w5
 FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+2) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
            foreach($Post2MonthArray as $P2MA)
            {
                if(key_exists($P2MA['cm']['company_name'], $data))
                {
                        $data[$P2MA['cm']['branch']]['w1'] += $P2MA['0']['w1'];
                        $data[$P2MA['cm']['branch']]['w2'] += $P2MA['0']['w2'];
                        $data[$P2MA['cm']['branch']]['w3'] += $P2MA['0']['w3'];
                        $data[$P2MA['cm']['branch']]['w4'] += $P2MA['0']['w4'];
                        $data[$P2MA['cm']['branch']]['w5'] += $P2MA['0']['w5'];
                }
                else
                {
                    $data[$P2MA['cm']['branch']]['w1'] = $P2MA['0']['w1'];
                    $data[$P2MA['cm']['branch']]['w2'] = $P2MA['0']['w2'];
                    $data[$P2MA['cm']['branch']]['w3'] = $P2MA['0']['w3'];
                    $data[$P2MA['cm']['branch']]['w4'] = $P2MA['0']['w4'];
                    $data[$P2MA['cm']['branch']]['w5'] = $P2MA['0']['w5'];
                }
            }
            
//            print_r($data);
//            exit;
            
            
           $Post3MonthArray = $this->CostCenterMaster->query("SELECT *,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2
 FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+3) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
           foreach($Post3MonthArray as $P3MA)
           {
                if(key_exists($P3MA['cm']['branch'], $data))
                {
                    
                        $data[$P3MA['cm']['branch']]['month2']['w1'] += $P3MA['0']['w1'];
                        $data[$P3MA['cm']['branch']]['month2']['w2'] += $P3MA['0']['w2'];
                    
                    
                }
                else
                {
                   $data[$P3MA['cm']['branch']]['month2']['w1'] = $P3MA['0']['w1'];
                   $data[$P3MA['cm']['branch']]['month2']['w2'] = $P3MA['0']['w2'];
                   //$data[$P3MA['cm']['company_name']]['branch'] = $P3MA['cm']['branch'];
                }
           }
        }
        
        $this->set("master_report",$data);
    }
	 
    public function index2()
    {
        $branch = $this->request->data['branch'];
        //print_r($this->request->data['branch']); exit;
        $costArray = $this->CostCenterMaster->query("SELECT cost_center FROM cost_master WHERE branch='$branch' and active=1");
        $html = "";
        
        
        foreach($costArray as $br)
        {
            $costcenter = $br['cost_master']['cost_center'];
            
            $ToBeBilledArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(pm.provision_balance)balance FROM cost_master cm INNER JOIN provision_master pm 
            ON cm.cost_center = pm.cost_center AND pm.cost_center='$costcenter' AND pm.provision_balance!=0
            WHERE cm.cost_center='$costcenter' group by cm.company_name,cm.branch;");
                        
            foreach($ToBeBilledArray as $TBBArray)
            {
                if(key_exists($TBBArray['cm']['company_name'], $data))
                {
                    if(key_exists($TBBArray['cm']['branch'], $data[$TBBArray['cm']['company_name']]))
                    {
                        $data[$TBBArray['cm']['company_name']][$TBBArray['cm']['branch']]['Tobebilled'] += $TBBArray['0']['balance'];
                    }
                    else
                    {
                        $data[$TBBArray['cm']['company_name']][$TBBArray['cm']['branch']]['Tobebilled'] = $TBBArray['0']['balance'];
                    }
                }
                else
                {
                    $data[$TBBArray['cm']['company_name']][$TBBArray['cm']['branch']]['Tobebilled'] = $TBBArray['0']['balance'];
                }
            }
            
            //print_r($data); exit;
            
            $InProcessingArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name, sum(IF(po_required='Yes',IF(po_no IS NULL OR po_no='',ti.grnd,0),0)) `po_pending`,
sum(IF(po_required='Yes',IF(po_no IS NOT NULL AND po_no !='',
IF(cm.grn='Yes',IF(ti.grn IS NULL OR ti.grn='',ti.grnd,0),0),0),0))  `grn_pending`,
sum(IF(po_required='Yes',IF(po_no IS NOT NULL AND po_no !='',
IF(cm.grn='Yes',IF(ti.grn IS NOT NULL OR ti.grn !='',0,IF(ti.receiptstatus IS NULL,ti.grnd,0)),0),0),0))  `ptp_pending`
                FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=0
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND DATE(rm.ExpDatesPayment)<CURDATE()
WHERE  cm.cost_center = '$costcenter' AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),TRUE,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),TRUE,FALSE))) group by cm.company_name");
            
            foreach($InProcessingArray as $IPA)
            {
                if(key_exists($IPA['cm']['company_name'], $data))
                {
                    if(key_exists($IPA['cm']['branch'], $data[$IPA['cm']['company_name']]))
                    {
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['POPending'] += $IPA['0']['po_pending'];
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['GRNPending'] += $IPA['0']['grn_pending'];
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['PTPPending'] += $IPA['0']['ptp_pending'];
                    }
                    else
                    {
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['POPending']  = $IPA['0']['po_pending'];
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['GRNPending'] = $IPA['0']['grn_pending'];
                        $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['PTPPending'] = $IPA['0']['ptp_pending'];
                    }
                    
                }
                else
                {
                    $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['POPending']  = $IPA['0']['po_pending'];
                    $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['GRNPending'] = $IPA['0']['grn_pending'];
                    $data[$IPA['cm']['company_name']][$IPA['cm']['branch']]['PTPPending'] = $IPA['0']['ptp_pending'];
                }
            }
            //print_r($data);
            //exit;
            
            $PytReadyArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(if(DATE(rm.ExpDatesPayment)>CURDATE(),0,ti.grnd)) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' 
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid
WHERE cm.cost_center = '$costcenter' 
    AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name,cm.branch");
            
            foreach($PytReadyArray as $PRA)
            {
                if(key_exists($PRA['cm']['company_name'], $data))
                {
                    if(key_exists($PRA['cm']['branch'], $data[$PRA['cm']['company_name']]))
                    {
                        $data[$PRA['cm']['company_name']][$PRA['cm']['branch']]['PytReady'] += $PRA['0']['grnd'];
                    }
                    else
                    {
                        $data[$PRA['cm']['company_name']][$PRA['cm']['branch']]['PytReady'] = $PRA['0']['grnd'];
                    }
                }
                else
                {
                    $data[$PRA['cm']['company_name']][$PRA['cm']['branch']]['PytReady'] = $PRA['0']['grnd'];
                    //$data[$PRA['cm']['company_name']]['branch'] = $PRA['cm']['branch'];
                }
            }
            //print_r($data);
            //exit;
            
            $PayForMonthArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = Month(curdate()) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE  cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
            foreach($PayForMonthArray as $PFMA)
            {
                if(key_exists($PFMA['cm']['company_name'], $data))
                {
                    if(key_exists($PFMA['cm']['branch'], $data[$PFMA['cm']['company_name']]))
                    {
                        $data[$PFMA['cm']['company_name']][$PFMA['cm']['branch']]['PayForMonth'] += $PFMA['0']['grnd'];
                    }
                    else
                    {
                        $data[$PFMA['cm']['company_name']][$PFMA['cm']['branch']]['PayForMonth'] = $PFMA['0']['grnd'];
                    }
                }
                else
                {
                    $data[$PFMA['cm']['company_name']][$PFMA['cm']['branch']]['PayForMonth'] = $PFMA['0']['grnd'];
                    //$data[$PFMA['cm']['company_name']]['branch'] = $PFMA['cm']['branch'];
                }
            }
            
            //print_r($data);
            //exit;
            
            
            $PostMonthArray = $this->CostCenterMaster->query("SELECT cm.branch,cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+1) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
            foreach($PostMonthArray as $PMA)
            {
                if(key_exists($PMA['cm']['company_name'], $data))
                {
                    if(key_exists($PMA['cm']['branch'], $data[$PMA['cm']['company_name']]))
                    {$data[$PMA['cm']['company_name']][$PMA['cm']['branch']]['PostMonth'] += $PMA['0']['grnd'];}
                    else {
                        $data[$PMA['cm']['company_name']][$PMA['cm']['branch']]['PostMonth'] = $PMA['0']['grnd'];
                    }
                }
                else
                {
                    $data[$PMA['cm']['company_name']][$PMA['cm']['branch']]['PostMonth'] = $PMA['0']['grnd'];
                    //$data[$PMA['cm']['company_name']]['branch'] = $PMA['cm']['branch'];
                }
            }
            
            
//            print_r($data);
//            exit;
            
            $Post2MonthArray = $this->CostCenterMaster->query("SELECT *,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2,
sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 15 AND 21,ti.grnd,0)) w3, sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 22 AND 28,ti.grnd,0)) w4,
sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 29 AND 31,ti.grnd,0)) w5
 FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+2) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
            foreach($Post2MonthArray as $P2MA)
            {
                if(key_exists($P2MA['cm']['company_name'], $data))
                {
                    if(key_exists($P2MA['cm']['branch'], $data[$P2MA['cm']['company_name']]))
                    {
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w1'] += $P2MA['0']['w1'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w2'] += $P2MA['0']['w2'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w3'] += $P2MA['0']['w3'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w4'] += $P2MA['0']['w4'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w5'] += $P2MA['0']['w5'];
                    }
                    else
                    {
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w1'] += $P2MA['0']['w1'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w2'] += $P2MA['0']['w2'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w3'] += $P2MA['0']['w3'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w4'] += $P2MA['0']['w4'];
                        $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w5'] += $P2MA['0']['w5'];
                    }
                }
                else
                {
                    $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w1'] = $P2MA['0']['w1'];
                    $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w2'] = $P2MA['0']['w2'];
                    $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w3'] = $P2MA['0']['w3'];
                    $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w4'] = $P2MA['0']['w4'];
                    $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['w5'] = $P2MA['0']['w5'];
                   // $data[$P2MA['cm']['company_name']][$P2MA['cm']['branch']]['branch'] = $P2MA['cm']['branch'];
                }
            }
            
//            print_r($data);
//            exit;
            
            
           $Post3MonthArray = $this->CostCenterMaster->query("SELECT *,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2
 FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+3) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) group by cm.company_name");
            
           foreach($Post3MonthArray as $P3MA)
           {
                if(key_exists($P3MA['cm']['branch'], $data[$P3MA['cm']['company_name']]))
                {
                    if(key_exists($P2MA['cm']['branch'], $data[$P2MA['cm']['company_name']]))
                    {
                        $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w1'] += $P3MA['0']['w1'];
                        $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w2'] += $P3MA['0']['w2'];
                    }
                    else
                    {
                        $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w1'] = $P3MA['0']['w1'];
                        $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w2'] = $P3MA['0']['w2'];
                    }    
                    
                }
                else
                {
                   $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w1'] = $P3MA['0']['w1'];
                   $data[$P3MA['cm']['company_name']][$P3MA['cm']['branch']]['month2']['w2'] = $P3MA['0']['w2'];
                   //$data[$P3MA['cm']['company_name']]['branch'] = $P3MA['cm']['branch'];
                }
           }
        }
        
        $html .= '</br></br><table border="1"  id="table_id">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Branch</b></td>
            <td align="center"><b>Company</b></td>
            <td align="center"><b>Bill to be raise</b></td>
            <td align="center"><b>PO Pend</b></td>
            <td align="center"><b>GRN Pend</b></td>
            <td align="center"><b>PTP Pend</b></td>
            <td align="center"><b>Pyt. for month</b></td>
            <td align="center"><b>W1</b></td>
            <td align="center"><b>W2</b></td>
            <td align="center"><b>W3</b></td>
            <td align="center"><b>W4</b></td>
            <td align="center"><b>W5</b></td>
            <td align="center"><b>W/2 1</b></td>
            <td align="center"><b>W/2 2</b></td>
            <td align="center"><b>Total</b></td>
        </tr>
    </thead>
    <tbody>';
        
        $i=1; foreach ($data as $com=>$comp): 
                        foreach($comp as $branch=>$mr):
            $html .= '<tr class="">';
            $html .= '<td align="center">'.$i++.'</td>';
            $html .= '<td align="center">'.$branch.'</td>';
            $html .= '<td align="center" class="MasterProcess '.$branch.'##'.$com.'"><a href="#">'.$com.'</a></td>';
            $html .= '<td align="center">'.(!empty($mr['Tobebilled'])?round($mr['Tobebilled']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['POPending'])?round($mr['POPending']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['GRNPending'])?round($mr['GRNPending']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['PTPPending'])?round($mr['PTPPending']/100000,2):0).'</td>';
            //$html .= '<td align="center">'.(!empty($mr['PytReady'])?$mr['PytReady']:0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['PayForMonth'])?round($mr['PayForMonth']/100000,2):0).'</td>';
            //$html .= '<td align="center">'.(!empty($mr['PostMonth'])?$mr['PostMonth']:0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['w1'])?round($mr['w1']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['w2'])?round($mr['w2']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['w3'])?round($mr['w3']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['w2'])?round($mr['w4']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['w3'])?round($mr['w5']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['month2']['w1'])?round($mr['month2']['w1']/100000,2):0).'</td>';
            $html .= '<td align="center">'.(!empty($mr['month2']['w2'])?round($mr['month2']['w2']/100000,2):0).'</td>';
            
            $Total = $mr['Tobebilled']+$mr['InProcess']+$mr['InProcess']+$mr['PytReady']+$mr['PayForMonth']
                    +$mr['w1']+$mr['w2']+$mr['w3']+$mr['w4']+$mr['w5']+$mr['month2']['w1']+$mr['month2']['w2'];
                    
                    
                    $GTotal += $Total; 
                    $GTTobebilled += $mr['Tobebilled'];
                    $GTPOPending += $mr['POPending'];
                    $GTGRNPending += $mr['GRNPending'];
                    $GTPTPPending += $mr['PTPPending'];
                    $GTPytReady += $mr['PytReady'];
                    $GTPayForMonth += $mr['PayForMonth'];
                    $GTPostMonth += $mr['PostMonth'];
                    $GTw1 += $mr['w1'];
                    $GTw2 += $mr['w2'];
                    $GTw3 += $mr['w1'];
                    $GTw4 += $mr['w4'];
                    $GTw5 += $mr['w5'];
                    $GTMw1 += $mr['month2']['w1'];
                    $GTMw2 += $mr['month2']['w2'];
                    $html .= '<td align="center">'.round($Total/100000,2).'</td></tr>';
        endforeach;    endforeach;
        
        $html .= '</tbody><tr>';
        $html .= '<td colspan="3"><b>Total</b></td>
                <td align="center">'.round($GTTobebilled/100000,2).'</td>
                <td align="center">'.round($GTPOPending/100000,2).'</td>
                <td align="center">'.round($GTGRNPending/100000,2).'</td>
                <td align="center">'.round($GTPTPPending/100000,2).'</td>
                <td align="center">'.round($GTPytReady/100000,2).'</td>
                
                <td align="center">'.round($GTw1/100000,2).'</td>
                <td align="center">'.round($GTw2/100000,2).'</td>
                <td align="center">'.round($GTw3/100000,2).'</td>
                <td align="center">'.round($GTw4/100000,2).'</td>
                <td align="center">'.round($GTw5/100000,2).'</td>
                <td align="center">'.round($GTMw1/100000,2).'</td>
                <td align="center">'.round($GTMw2/100000,2).'</td>
                <td align="center"><b>'.round($GTotal/100000,2).'</b></td></tr></table>';
        
        echo $html; exit;
    }
    
    public function get_master_day_wise()  //add branch to table
    {
        //print_r($this->request->data); exit;
        if ($this->request->is('post')) 
        {
            $branch = $this->request->data['branch'];
            $company = $this->request->data['company'];
            
            $costArray = $this->CostCenterMaster->query("SELECT cost_center FROM cost_master WHERE branch = '$branch' and company_name='$company'  AND active=1");
            
            foreach($costArray as $br)
            {
               $costcenter = $br['cost_master']['cost_center'];

                $ToBeBilledArray = $this->CostCenterMaster->query("SELECT cm.cost_center,pm.provision_balance balance FROM cost_master cm INNER JOIN provision_master pm 
                ON cm.cost_center = pm.cost_center AND pm.cost_center='$costcenter' AND pm.provision_balance!=0
                WHERE cm.cost_center='$costcenter'");

                foreach($ToBeBilledArray as $TBBArray)
                {
                    if(key_exists($TBBArray['cm']['cost_center'], $data))
                    {
                        $data[$TBBArray['cm']['cost_center']]['Tobebilled'] += $TBBArray['0']['balance'];
                    }
                    else
                    {
                        $data[$TBBArray['cm']['cost_center']]['Tobebilled'] = $TBBArray['0']['balance'];
                    }
                }

                //print_r($data); exit;

                $InProcessingArray = $this->CostCenterMaster->query("SELECT cm.cost_center,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=0
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND DATE(rm.ExpDatesPayment)<CURDATE()
    WHERE cm.cost_center = '$costcenter' AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),TRUE,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),TRUE,FALSE)))");

                foreach($InProcessingArray as $IPA)
                {
                    if(key_exists($IPA['cm']['cost_center'], $data))
                    {
                       $data[$IPA['cm']['cost_center']]['InProcess'] += $IPA['0']['grnd'];
                    }
                    else
                    {
                       $data[$IPA['cm']['cost_center']]['InProcess'] = $IPA['0']['grnd'];
                    }
                }
                
                $PytReadyArray = $this->CostCenterMaster->query("SELECT cm.cost_center,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=0
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND DATE(rm.ExpDatesPayment)<CURDATE()
    WHERE  cm.cost_center = '$costcenter' AND rm.Invoiceid is null
        AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) ");

                foreach($PytReadyArray as $PRA)
                {
                    if(key_exists($PRA['cm']['cost_center'], $data))
                    {
                        $data[$PRA['cm']['cost_center']]['PytReady'] += $PRA['0']['grnd'];
                    }
                    else
                    {
                        $data[$PRA['cm']['cost_center']]['PytReady'] = $PRA['0']['grnd'];
                    }
                }
                //print_r($data);
                //exit;

                $PayForMonthArray = $this->CostCenterMaster->query("SELECT cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = Month(curdate()) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
    WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) ");

                foreach($PayForMonthArray as $PFMA)
                {
                    if(key_exists($PFMA['cm']['cost_center'], $data))
                    {
                        $data[$PFMA['cm']['cost_center']]['PayForMonth'] += $PFMA['0']['grnd'];
                    }
                    else
                    {
                        $data[$PFMA['cm']['cost_center']]['PayForMonth'] = $PFMA['0']['grnd'];
                    }
                }

                //print_r($data);
                //exit;


                $PostMonthArray = $this->CostCenterMaster->query("SELECT cm.company_name,sum(ti.grnd) grnd FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+1) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
    WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) ");

                foreach($PostMonthArray as $PMA)
                {
                    if(key_exists($PMA['cm']['cost_center'], $data))
                    {
                        $data[$PMA['cm']['cost_center']]['PostMonth'] += $PMA['0']['grnd'];
                    }
                    else
                    {
                        $data[$PMA['cm']['cost_center']]['PostMonth'] = $PMA['0']['grnd'];
                    }
                }


    //            print_r($data);
    //            exit;

                $Post2MonthArray = $this->CostCenterMaster->query("SELECT cm.cost_center,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2,
    sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 15 AND 21,ti.grnd,0)) w3, sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 22 AND 28,ti.grnd,0)) w4,
    sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 29 AND 31,ti.grnd,0)) w5
     FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+2) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
    WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) ");

                foreach($Post2MonthArray as $P2MA)
                {
                    if(key_exists($P2MA['cm']['cost_center'], $data))
                    {
                        $data[$P2MA['cm']['cost_center']]['w1'] += $P2MA['0']['w1'];
                        $data[$P2MA['cm']['cost_center']]['w2'] += $P2MA['0']['w2'];
                        $data[$P2MA['cm']['cost_center']]['w3'] += $P2MA['0']['w3'];
                        $data[$P2MA['cm']['cost_center']]['w4'] += $P2MA['0']['w4'];
                        $data[$P2MA['cm']['cost_center']]['w5'] += $P2MA['0']['w5'];
                    }
                    else
                    {
                        $data[$P2MA['cm']['cost_center']]['w1'] = $P2MA['0']['w1'];
                        $data[$P2MA['cm']['cost_center']]['w2'] = $P2MA['0']['w2'];
                        $data[$P2MA['cm']['cost_center']]['w3'] = $P2MA['0']['w3'];
                        $data[$P2MA['cm']['cost_center']]['w4'] = $P2MA['0']['w4'];
                        $data[$P2MA['cm']['cost_center']]['w5'] = $P2MA['0']['w5'];
                    }
                }

    //            print_r($data);
    //            exit;


               $Post3MonthArray = $this->CostCenterMaster->query("SELECT cm.cost_center,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 1 AND 7,ti.grnd,0)) w1,sum(IF(DAY(rm.ExpDatesPayment) BETWEEN 8 AND 14,ti.grnd,0)) w2
     FROM cost_master cm INNER JOIN tbl_invoice ti
    ON cm.cost_center = ti.cost_center AND ti.cost_center='$costcenter' AND ti.ReceiptStatus=1
    LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    LEFT JOIN receipt_master rm ON ti.id = rm.Invoiceid AND Month(rm.ExpDatesPayment) = (Month(curdate())+3) AND YEAR(rm.ExpDatesPayment) = YEAR(curdate())
    WHERE cm.cost_center = '$costcenter' AND rm.Invoiceid is not null AND bpp.bill_no IS NULL AND (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),false,
       IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),false,true))) ");

               foreach($Post3MonthArray as $P3MA)
               {
                    if(key_exists($P3MA['cm']['cost_center'], $data))
                    {
                        $data[$P3MA['cm']['cost_center']]['month2']['w1'] += $P3MA['0']['w1'];
                        $data[$P3MA['cm']['cost_center']]['month2']['w2'] += $P3MA['0']['w2'];
                    }
                    else
                    {
                       $data[$P3MA['cm']['cost_center']]['month2']['w1'] = $P3MA['0']['w1'];
                       $data[$P3MA['cm']['cost_center']]['month2']['w2'] = $P3MA['0']['w2'];
                    }
               }
            }
            
            $i=0; $j=1; $html = '</br></br>';
            $html .= '<table border="1"  id="master_day_wise">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Branch</b></td>
            <td align="center"><b>Company</b></td>
            <td align="center"><b>To be billed</b></td>
            <td align="center"><b>In Processing</b></td>
            <td align="center"><b>Ready for payment or billed</b></td>
            <td align="center"><b>Pyt. for month</b></td>
            <td align="center"><b>Post month</b></td>
            <td align="center"><b>W1</b></td>
            <td align="center"><b>W2</b></td>
            <td align="center"><b>W3</b></td>
            <td align="center"><b>W4</b></td>
            <td align="center"><b>W5</b></td>
            <td align="center"><b>W/2 1</b></td>
            <td align="center"><b>W/2 2</b></td>
            <td align="center"><b>Total</b></td>
        </tr>
    </thead><tbody>';
            foreach ($data as $com=>$mr):
                $Total = $mr['Tobebilled']+$mr['InProcess']+$mr['PytReady']+$mr['PayForMonth']
                    +$mr['PostMonth']+$mr['w1']+$mr['w2']+$mr['w3']+$mr['w4']+$mr['w5']+$mr['month2']['w1']+$mr['month2']['w2'];
            
            
                    $GTotal += $Total; 
                    $GTTobebilled += $mr['Tobebilled'];
                    $GTInProcess += $mr['InProcess'];
                    $GTPytReady += $mr['PytReady'];
                    $GTPayForMonth += $mr['PayForMonth'];
                    $GTPostMonth += $mr['PostMonth'];
                    $GTw1 += $mr['w1'];
                    $GTw2 += $mr['w2'];
                    $GTw3 += $mr['w1'];
                    $GTw4 += $mr['w4'];
                    $GTw5 += $mr['w5'];
                    $GTMw1 += $mr['month2']['w1'];
                    $GTMw2 += $mr['month2']['w2'];
            
                $html .= '<tr><td align="center">'.$j++.'</td>';
                $html .= '<td align="center">'.$branch.'</td>';
                $html .= '<td align="center">'.$company.'</td>';
                $html .= '<td align="center">'.(!empty($mr['Tobebilled'])?round($mr['Tobebilled']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['InProcess'])?round($mr['InProcess']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['PytReady'])?round($mr['PytReady']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['PayForMonth'])?round($mr['PayForMonth']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['PostMonth'])?round($mr['PostMonth']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['w1'])?round($mr['w1']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['w2'])?round($mr['w2']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['w3'])?round($mr['w3']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['w4'])?round($mr['w4']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['w5'])?round($mr['w5']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['month2']['w1'])?round($mr['month2']['w1']/100000,2):0).'</td>';
                $html .= '<td align="center">'.(!empty($mr['month2']['w2'])?round($mr['month2']['w2']/100000,2):0).'</td>';
                $html .= '<td align="center">'.round($Total/100000,2).'</td></tr>';            
            endforeach;
            
            $html .='</tbody>';
            $html .='<tr><td colspan="3" align="right"><b>Total</b></td>';
            $html .='<td align="center">'.round($GTTobebilled/100000,2).'</td>';
            $html .='<td align="center">'.round($GTInProcess/100000,2).'</td>';
            $html .='<td align="center">'.round($GTPytReady/100000,2).'</td>';
            $html .='<td align="center">'.round($GTPayForMonth/100000,2).'</td>';
            $html .='<td align="center">'.round($GTPostMonth/100000,2).'</td>';
            $html .='<td align="center">'.round($GTw1/100000,2).'</td>';
            $html .='<td align="center">'.round($GTw2/100000,2).'</td>';
            $html .='<td align="center">'.round($GTw3/100000,2).'</td>';
            $html .='<td align="center">'.round($GTw4/100000,2).'</td>';
            $html .='<td align="center">'.round($GTw5/100000,2).'</td>';
            $html .='<td align="center">'.round($GTMw1/100000,2).'</td>';
            $html .='<td align="center">'.round($GTMw2/100000,2).'</td>';
            $html .='<td align="center">'.round($GTotal/100000,2).'</td></tr>';
            $html .='</table>';
            
            echo $html; exit;
        }
    }
    
    public function get_master_process_wise()  //add branch to table
    {
        //print_r($this->request->data); exit;
        if ($this->request->is('post')) 
        if ($this->request->is('post')) 
        {
            $branch = $this->request->data['branch'];
            $company = $this->request->data['company'];
            
            $i=0; $j=1; $html = '</br></br>';
            $html .= '<table border="1"  id="master_day_wise">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Cost Center</b></td>
            <td align="center"><b>Process Name</b></td>
            <td align="center"><b>Client Name</b></td>
            <td align="center"><b>Bill Month</b></td>
            <td align="center"><b>Amt</b></td>
            <td align="center"><b>Bucket</b></td>
        </tr>
    </thead><tbody>';
               
    $processArray =   $this->CostCenterMaster->query("SELECT cost_center,process_name,`client`,`month`,grnd,IF(bucket<30,'30',IF(bucket<60,'60',IF(bucket<90,'90',IF(bucket<120,'120',IF(bucket<150,'150','180'))))) bucket
FROM 
(
SELECT cost_center, process_name,`client`,`month`,SUM(grnd) grnd,bucket FROM (
SELECT cm.cost_center, cm.process_name,cm.client,ti.month,ti.grnd,
DATEDIFF(SUBDATE(CURDATE(),INTERVAL 1 MONTH),STR_TO_DATE(CONCAT('1-',ti.month),'%d-%M-%Y')) `bucket`
FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center and branch = '$branch' and company_name = '$company'
LEFT JOIN bill_pay_particulars bpp
ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
    AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
    WHERE bpp.bill_no IS NULL
UNION ALL
SELECT pm.cost_center,process_name,`client`,pm.month,provision_balance,
DATEDIFF(SUBDATE(CURDATE(),INTERVAL 1 MONTH),STR_TO_DATE(CONCAT('1-',pm.month),'%d-%M-%Y')) `bucket`
FROM cost_master cm INNER JOIN  provision_master pm ON cm.cost_center = pm.cost_center 
AND  branch = '$branch' and company_name = '$company' and pm.provision_balance !=0 
) AS tab2 GROUP BY tab2.month,tab2.cost_center
)AS tab ORDER BY CONVERT(bucket, UNSIGNED INT)
");
                
             foreach($processArray as $pra)
             {
                $html .= '<tr><td align="center">'.$j++.'</td>';
                $html .= '<td align="center">'.$pra['tab']['cost_center'].'</td>';
                $html .= '<td align="center">'.$pra['tab']['process_name'].'</td>';
                $html .= '<td align="center">'.$pra['tab']['client'].'</td>';
                $html .= '<td align="center">'.$pra['tab']['month'].'</td>';
                $html .= '<td align="center" class="MasterProcessBrief '.$pra['tab']['cost_center'].'##'.$pra['tab']['month'].'"><a href="#">'.round($pra['tab']['grnd']/100000,2).'</a></td>';
                $html .= '<td align="center">';
                if($pra['0']['bucket']=='180')
                {   $html .='&gt150';}
                else
                {
                    $html .=($pra['0']['bucket']-30).'-'.$pra['0']['bucket'].'</td></tr>';
                }        
                
                $Total += $pra['tab']['grnd'];
             }
               
             if(empty($processArray)) { $html .= "<tr><td colspan='4'>No Events Found</td></tr>";}
             $html .='</tbody>'; 
             
             $html .= '<tr><td colspan="4"></td>';
             $html .= '<td>Total</td>';
             $html .= '<td><b>'.round($Total/100000,2).'</b></td>';
             $html .= '<td>0.00</td></tr></table>';
             
            echo $html; exit;
            //$this->set("master_report",$data);
            
            
        }
    }
    
    public function get_master_process_brief_wise()  //add branch to table
    {
        //print_r($this->request->data); exit;
        if ($this->request->is('post')) 
        {
            $cost_center = $this->request->data['cost_center'];
            $month = $this->request->data['month'];
            
            $i=0; $j=1; $html = '</br></br>';
            $html .= '<table border="1"  id="master_event_wise_brief">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Client</b></td>
            <td align="center"><b>Amt</b></td>
            <td align="center"><b>Events.</b></td>
            <td align="center"><b>Bill Status</b></td>
            <td align="center"><b>Action Date</b></td>
            <td align="center"><b>Actionable Remarks</b></td>
        </tr>
    </thead><tbody>';
               
    $processArray =   $this->CostCenterMaster->query("SELECT ti.id,cm.client,ti.grnd,COUNT(hadm.Id) eventss,
(IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),'PO Pending',
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),'GRN Pending',
	IF(rm.invoiceId IS NULL,'Waiting For PTP',IF(CURDATE()>ExpDatesPayment,'PTP Broken','PTP Date'))
   ))) `bill_status`,
 (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),date_format(ti.po_date,'%d-%b-%Y'),
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),date_format(ti.grn_date,'%d-%b-%Y'),
	date_format(rm.ExpDatesPayment,'%d-%b-%Y')
   ))) `bill_action`, 
  (IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),ti.po_remarks,
   IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_po IS NULL),ti.grn_remarks,
	rm.remarks
   ))) `bill_remarks` 
FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center AND cm.cost_center = '$cost_center' AND ti.month='$month'
LEFT JOIN bill_pay_particulars bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
AND ti.finance_year = bpp.financial_year AND cm.company_name = bpp.company_name
LEFT JOIN `his_action_date_master` hadm ON ti.id = hadm.invoiceId
LEFT JOIN receipt_master rm ON ti.id = rm.invoiceId
    WHERE bpp.bill_no IS NULL GROUP BY ti.id
    UNION
    SELECT 'provision',`client`,provision_balance,'0' eventss,'ToBeBilled' ToBeBilled,'' action_date,'' action_remarks
FROM cost_master cm INNER JOIN  provision_master pm ON cm.cost_center = pm.cost_center AND pm.cost_center = '$cost_center' AND pm.month='$month'
AND pm.provision_balance !=0
");
                
             foreach($processArray as $pra)
             {
                $html .= '<tr><td align="center">'.$j++.'</td>';
                $html .= '<td align="center">'.$pra['0']['client'].'</td>';
                $html .= '<td align="center">'.round($pra['0']['grnd']/100000,2).'</td>';
                $html .= '<td align="center" class="MasterEventsBrief '.$pra['0']['id'].'"><a href="#">'.$pra['0']['eventss'].'</a></td>';
                $html .= '<td align="center">'.$pra['0']['bill_status'].'</td>';
                $html .= '<td align="center">'.$pra['0']['bill_action'].'</td>';
                $html .= '<td align="center">'.$pra['0']['bill_remarks'].'</td></tr>';
                $Total +=$pra['0']['grnd'];
                $TotalEvents +=$pra['0']['eventss'];
             }
               
             if(empty($processArray)) { $html .= "<tr><td colspan='4'>No Events Found</td></tr>";}
             $html .='</tbody><tr><td colspan="2" align="right">Total</td><td align="center">'.round($Total/100000,2).'</td><td align="center">'.$TotalEvents.'</td><td colspan="3"></td></tr>';
             $html .='</table>';
             
            echo $html; exit;
            //$this->set("master_report",$data);
            
            
        }
    }
    
    
    public function get_master_event_brief_wise()  //add branch to table
    {
        //print_r($this->request->data); exit;
        if ($this->request->is('post')) 
        {
            $id = $this->request->data['id'];
            
            $i=0; $j=1; $html = '</br></br>';
            $html .= '<table border="1"  id="master_day_wise_brief">
    <thead>
        <tr class="active">
            <td align="center"><b>Sr. No.</b></td>
            <td align="center"><b>Process</b></td>
            <td align="center"><b>Action Date</b></td>
            <td align="center"><b>Actionable remarks</b></td>
        </tr>
    </thead><tbody>';
               
    $processArray =   $this->CostCenterMaster->query("SELECT cm.process_name,IF(actionType='grn_date',hadm.grn_date,hadm.po_date) `actionDate`,
IF(actionType='grn_date',hadm.grn_remarks,hadm.po_remarks) `remarks`
FROM cost_master cm INNER JOIN tbl_invoice ti
ON cm.cost_center = ti.cost_center 
INNER JOIN `his_action_date_master` hadm ON ti.id = hadm.invoiceId
WHERE ti.id='$id'
");
                
             foreach($processArray as $pra)
             {
                $html .= '<tr><td align="center">'.$j++.'</td>';
                $html .= '<td align="center">'.$pra['cm']['process_name'].'</td>';
                $html .= '<td align="center">'.$pra['0']['actionDate'].'</td>';
                $html .= '<td align="center">'.$pra['0']['remarks'].'</td></tr>';
             }
             
             
             if(empty($processArray)) { $html .= "<tr><td colspan='4'>No Events Found</td></tr>";}
             $html .='</tbody></table>'; 
             
             
             echo $html; exit; 
            //$this->set("master_report",$data); 
        }
    }

}

?>