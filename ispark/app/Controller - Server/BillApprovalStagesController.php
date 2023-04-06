<?php
class BillApprovalStagesController extends AppController 
{
    public $uses=array('CostCenterMaster','InitialInvoice','Addclient','Addbranch','Addcompany','Addprocess','Category','Type','BillMaster');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny('index','invoice_export','get_export','get_report','get_report2','bill_genrate_report','get_report3','get_report4','view','get_branch','get_report5','get_report5a','get_report6','get_report6a','ptp','get_report7','get_report7a','bill_genrate_edit');
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
        else
        {   $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('index','invoice_export','get_export','get_report','get_report2','bill_genrate_report','get_report3','get_report4','view','get_branch','get_report5','get_report5a','get_report6','get_report6a','ptp','get_report7','get_report7a','bill_genrate_edit','invoice_image_export','get_image_export');       
        }
    }
		
    public function index() 
    {}
    public function view()
    {
        $this->layout='home';
	$this->set('company_master',$this->Addcompany->find('all',array('field'=>array('company_name'))));
    }
		
    public function get_branch()
    {
	$this->layout = "ajax";
	$result = $this->params->query;
	if($result['company_name'] == 'All')
	{$this->set('data',$this->CostCenterMaster->find('all',array('fields'=>array('branch'),'order'=>array('branch'=>'asc'))));}
	
        else
        {
            $this->set('data',$this->CostCenterMaster->find('all',array('fields'=>array('branch'),'conditions' =>$result,'order'=>array('branch'=>'asc'))));
        }	
    }
    public function get_report5()    //for all bills approval stages...
    {
	$this->layout="ajax";
	$result=$this->params->query;
	$this->set('result',$result);
	$this->set('type',$result['type']);
	$this->set('report',$result['selectreport']);
	
	if($result['company_name'] == 'All')
	{    
	    if($result['branchname']=='All')
            {
			
            if($result['selectreport']=='Bill Initiated Not Approve')
            {
                $this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.invoiceDate,t1.invoiceDescription,t2.cost_center, t1.branch_name, t1.bill_no, t1.month, if(t1.grnd!='',t1.grnd,t1.grnd) `total` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE  t1.bill_no ='' AND t1.status = '0'"));
                $this->set("summary",$this->InitialInvoice->query("SELECT cost_center,branch_name,SUM(grnd)'total' FROM tbl_invoice where bill_no = '' AND status = '0' GROUP BY cost_center"));
 		
            }
            if($result['selectreport']=='GRN Status')
            {
		$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.invoiceDescription,t2.cost_center,t1.branch_name,t1.bill_no,t1.month,(if(t1.grn !='',t1.grn,if(t2.grn != 'Yes','Required','Not Required'))) `Grn Available`, if(t1.grnd!='',t1.grnd,t1.grnd) `total` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center where t1.status = '0' AND t1.bill_no!=''"));
		
		$this->set("summary",$this->InitialInvoice->query("SELECT cost_center,branch_name,SUM(grnd)'total' FROM tbl_invoice where bill_no!='' AND status = '0' GROUP BY cost_center"));
            }
            if($result['selectreport']=='Bill Submission Status')
            { 
                $this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.invoiceDescription,t3.SubmitedDates,t2.cost_center, t1.branch_name, t1.month, t1.bill_no, if(t1.grnd!='',t1.grnd,t1.grnd)`total` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id = t3.InvoiceId ORDER BY t2.company_name,bill_no limit 5"));
                $this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center,tbl_invoice.branch_name,SUM(tbl_invoice.grnd)'total' FROM tbl_invoice inner join receipt_master t2 on tbl_invoice.id = t2.InvoiceId  GROUP BY tbl_invoice.cost_center"));
			
            }
            if($result['selectreport']=='Bill Generate Status'){
			
            $this->set("summary",$this->InitialInvoice->query("SELECT cost_center,branch_name,SUM(grnd)'total' FROM tbl_invoice where bill_no != '' AND status = '0' GROUP BY cost_center "));
			
            $this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.branch_name,t1.month,t1.bill_no,if(t1.grnd!= '',t1.grnd,t1.grnd) `total`, t1.invoiceDescription `desc`,t1.invoiceDate,t1.month FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE  t1.bill_no !='' AND status = '0'"));					
					}
            if($result['selectreport']=='PO Status')
            {  
                $this->set("summary",$this->InitialInvoice->query("SELECT cost_center,branch_name,SUM(grnd)'total' FROM tbl_invoice where po_no != '' AND status = '0' GROUP BY cost_center"));			
	$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.invoiceDescription, t2.cost_center, t1.branch_name, t1.bill_no,t1.month, t1.grn, if(t1.grnd='', t1.grnd,t1.grnd) `total`,if(t1.po_no != '',t1.po_no,if(t2.po_required = 'YES','Required','Not Required'))`po_no` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE  t1.po_no !='' AND t1.status = '0'"));
            }
	
            }
			
	}else { 
	$company = $result['company_name'];
	 $branch  = $result['branchname'];
	 
		    
			
	if($result['selectreport']=='Bill Initiated Not Approve'){
		
		$this->set("data",$this->InitialInvoice->query("SELECT t1.invoiceDescription, t2.cost_center, t1.bill_no, t1.branch_name, t1.bill_no, t1.month, if(t1.grnd!='',t1.grnd,t1.grnd) `total` FROM  tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE t1.bill_no ='' AND t2.company_name='$company' AND t1.branch_name='$branch' AND t1.status = '0' ORDER BY bill_no"));
 
	     $this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center,tbl_invoice.branch_name,SUM(grnd)'total' FROM tbl_invoice inner join cost_master t2 on tbl_invoice.cost_center = t2.cost_center where tbl_invoice.bill_no = '' and  tbl_invoice.branch_name='$branch' AND tbl_invoice.status = '0' GROUP BY cost_center"));
		} 
		if($result['selectreport']=='GRN Status')
		{
			$this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center, tbl_invoice.branch_name, SUM(tbl_invoice.grnd)'total' FROM tbl_invoice inner join cost_master t2 on tbl_invoice.cost_center = t2.cost_center where t2.company_name='$company' AND tbl_invoice.branch_name='$branch' AND tbl_invoice.bill_no!='' AND tbl_invoice.status = '0' GROUP BY tbl_invoice.cost_center"));
			
		$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.invoiceDescription,t2.cost_center, t1.bill_no, t1.branch_name, t1.bill_no, t1.month,t1.grn,(if(t1.grn !='' ,t1.grn,if(t2.grn = 'Yes','Required','Not Required'))) `Grn Available`, if(t1.grnd!='',t1.grnd,t1.grnd)`total` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE  t2.company_name='$company' AND t2.branch='$branch' AND t1.bill_no!='' AND t1.status = '0'"));
			} 
			if($result['selectreport']=='Bill Submission Status')
			{ 
			$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.invoiceDescription,t3.SubmitedDates,t1.branch_name, t1.month, t1.bill_no, if(t1.grnd!='',t1.grnd,t1.grnd) `total`, t3.SubmitedDates FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id = t3.Invoiceid  WHERE t2.company_name='$company' AND t1.branch_name='$branch' AND  t1.bill_no !='' AND t1.status = '0' "));
			$this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center, tbl_invoice.bill_no, tbl_invoice.branch_name, SUM(tbl_invoice.grnd)'total' FROM tbl_invoice INNER JOIN cost_master t2 ON tbl_invoice.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON tbl_invoice.id = t3.Invoiceid WHERE (tbl_invoice.bill_no != '') AND t2.company_name='$company' AND tbl_invoice.branch_name='$branch' GROUP BY tbl_invoice.cost_center"));
		     }
		     
		if($result['selectreport']=='Bill Generate Status'){
           $this->set("data",$this->InitialInvoice->query("SELECT t2.cost_center,t1.branch_name,t1.bill_no,if(t1.grnd!='',t1.grnd,t1.grnd)'total',t1.invoiceDescription `desc`,t1.invoiceDate,t1.month FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE t2.company_name='$company' AND t1.branch_name='$branch' AND t1.bill_no != '' AND t1.status = '0'"));	
		   $this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center,tbl_invoice.branch_name,SUM(tbl_invoice.grnd)'total' FROM tbl_invoice INNER JOIN cost_master t2 ON tbl_invoice.cost_center = t2.cost_center WHERE t2.company_name='$company' AND tbl_invoice.branch_name='$branch' AND tbl_invoice.bill_no != ''AND tbl_invoice.status = '0'  GROUP BY tbl_invoice.cost_center"));				
					}	
					 
		if($result['selectreport']=='PO Status'){  
	$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.bill_no,t1.invoiceDescription, t1.branch_name, t1.bill_no, t1.month, t1.grn, if(t1.grnd='',t1.grnd,t1.grnd) `total`, if(t1.po_no != '',t1.po_no,if(t2.po_required = 'YES','Required','Not Required'))`po_no` FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE t2.company_name='$company' AND t1.branch_name='$branch' AND  t1.bill_no !='' AND t1.status = '0' ORDER BY bill_no"));
		
			$this->set("summary",$this->InitialInvoice->query("SELECT tbl_invoice.cost_center,tbl_invoice.branch_name,SUM(tbl_invoice.grnd)'total' FROM tbl_invoice inner join cost_master t2 on tbl_invoice.cost_center = t2.cost_center where tbl_invoice.bill_no != '' and t2.company_name='$company' AND tbl_invoice.branch_name='$branch' AND tbl_invoice.status = '0' GROUP BY tbl_invoice.cost_center"));			
		
					} 
			}
	}
        
    public function invoice_export()
    {
        $this->layout="home";
        $role = $this->Session->read('role');
        $all = array();
        if($role=='admin')
        {
            $conditions = array('Active'=>'1');
            $all = array('All'=>'All');
        }
       else
       {
           $conditions = array('Active'=>'1','branch_name'=>$this->Session->read("branch_name"));
       }
       $branch = array_merge($all,$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions)));
       $this->set('branch_master',$branch);
       $this->set('finance_yearNew',array_merge(array('All'=>'All'),$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15'))))));
    }
    
    public function get_export()
    {
        $this->layout="ajax";
        $role = $this->Session->read('role');
        if($role=='admin')
        {
            $branch = $this->params->query['BranchName'];
        }
       else
       {
           $branch=$this->Session->read("branch_name");
       }
       $company = $this->params->query['company'];
       $finance = $this->params->query['year'];
       
       $branchName = $finance_year = $companyName ='';
       if($branch!='All')
       {
           $branchName = "AND ti.branch_name='$branch'";
       }
       
       if($finance!='All')
       {
           $finance_year = "AND ti.finance_year='$finance'";
       }
       
       if($company!='All')
       {
           $companyName = "AND cm.company_name='$company'";
       }
       
      $sel ="SELECT REPLACE(SUBSTRING_INDEX(ti.bill_no,'/',1),'-','')`BillNo`,ti.bill_no BillTally,IF(bpp.status IS NULL,'Pending',bpp.status)`Pending`,ti.cost_center `ProcessCode`,
cm.company_name,ti.branch_name `branch`,cm.client `client`,ti.finance_year `financialYear`,
DATE_FORMAT(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'),'%m-%Y') `month`,
ti.po_no `po_no`,ti.grn `grn`,
DATE_FORMAT(ti.invoiceDate,'%d-%M-%Y') `invoiceDate`,cm.GSTType GSTTYPE,cm.ServiceTaxNo CompanyGSTNo,cm.VendorGSTNo VendorGSTNo,cm.VendorGSTState,cm.VendorStateCode,ti.total `amount`,ti.tax `ServiceTax`,
ti.sbctax `SbcTax`,ti.krishi_tax `KrishiTax`,ti.igst,ti.cgst,ti.sgst,ti.grnd `GTotal`,ti.invoiceDescription `Remarks`,
IF(cm.po_required = 'Yes',IF(ti.approve_po IS NULL OR ti.approve_po = '','PO Pending',
IF(cm.grn='Yes',IF(ifnull(ti.approve_grn,'')='' OR ti.approve_po = '','GRN Pending','Submitted'),'NA')),
IF(cm.grn='Yes',IF(ifnull(ti.approve_grn,'')='' OR ti.approve_po = '','GRN Pending','Submitted'),'NA')
) `status`,bpp.bill_passed `BillPassed`,bpp.net_amount `payReceived`,bpp.tds_ded `TDS`,obd.other_deduction_bill,
bpp.pay_dates `ReceieveDate`,CONCAT(bpp.pay_type,bpp.pay_no) `ChequeNo`,bpp.bill_amount,bpp.deduction,bpp.PaymentFile,
od.other_deduction,bpp.pay_type,bpp.pay_no,bpp.net_amount_desc
FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
/* Invoice Collection Status */  
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,PaymentFile,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates,net_amount_desc FROM 
(SELECT bill_no,company_name,branch_name,financial_year,bpp.PaymentFile,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,GROUP_CONCAT(CONCAT(IF(net_amount IS NULL,0,net_amount),',',IF(tds_ded IS NULL,0,tds_ded),',',IF(pay_dates IS NULL,'',pay_dates),',',IF(pay_type IS NULL,'',pay_type),IF(pay_no IS NULL,'',pay_no),',',IF(PaymentFile IS NULL,'',PaymentFile))SEPARATOR '#') net_amount_desc,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no 
AND ti.branch_name = bpp.branch_name AND ti.finance_year = bpp.financial_year AND
cm.company_name = bpp.company_name

 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 )od ON bpp.financial_year=od.financial_year AND bpp.company_name = od.company_name AND bpp.branch_name = od.branch_name AND
  bpp.pay_no = od.pay_no AND bpp.pay_type = od.pay_type 
 
/* bill wise other deduction */  
LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,pay_amount,bank_name,deposit_bank,pays_date,pay_type_dates,bill_no,
SUM(other_deduction)other_deduction_bill,GROUP_CONCAT(other_remarks)other_remarks FROM `other_deductions_bill` GROUP BY company_name,financial_year,branch_name,bill_no)obd
ON obd.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1) AND obd.financial_year = ti.finance_year AND obd.company_name = cm.company_name AND obd.branch_name = ti.branch_name 
WHERE ti.status=0 and ti.bill_no IS NOT NULL and ti.bill_no !='' $branchName $finance_year $companyName
 ORDER BY CONVERT(REPLACE(SUBSTRING_INDEX(ti.bill_no,'/',1),'-',''),UNSIGNED INTEGER),ti.finance_year"; 
       
       
       
        $data = $this->InitialInvoice->query($sel);
        
       $this->set('data',$data); 
        
    }
    
    public function invoice_image_export()
    {
        $this->layout="home";
        $role = $this->Session->read('role');
        $all = array();
        if($role=='admin')
        {
            $conditions = array('Active'=>'1');
            $all = array('All'=>'All');
        }
       else
       {
           $conditions = array('Active'=>'1','branch_name'=>$this->Session->read("branch_name"));
       }
       $branch = array_merge($all,$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions)));
       $this->set('branch_master',$branch);
       $this->set('finance_yearNew',array_merge(array('All'=>'All'),$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15'))))));
    }
    
    public function get_image_export()
    {
        $this->layout="ajax";
        $role = $this->Session->read('role');
        if($this->request->is('POST'))
        {
            $param = $this->request->data;
        }
        else
        {
            $param = $this->params->query;
        }
        if($role=='admin')
        {
            $branch = $param['BranchName'];
        }
       else
       {
           $branch=$this->Session->read("BranchName");
       }
       $company = $param['companyName'];
       $finance = $param['year'];
       
       $branchName = $finance_year = $companyName ='';
       if($branch!='All')
       {
           $branchName = "AND ti.branch_name='$branch'";
       }
       
       if($finance!='All')
       {
           $finance_year = "AND ti.finance_year='$finance'";
       }
       
       if($company!='All')
       {
           $companyName = "AND cm.company_name='$company'";
       }
      
        $data = $this->InitialInvoice->query("SELECT REPLACE(SUBSTRING_INDEX(ti.bill_no,'/',1),'-','')`BillNo`,ti.bill_no BillTally,IF(bpp.bill_no IS NULL,'Pending',bpp.status)`Pending`,ti.cost_center `ProcessCode`,
cm.company_name,ti.branch_name `branch`,cm.client `client`,ti.finance_year `financialYear`,
DATE_FORMAT(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'),'%m-%Y') `month`,
ti.po_no `po_no`,ti.grn `grn`,
DATE_FORMAT(ti.invoiceDate,'%d-%M-%Y') `invoiceDate`,cm.GSTType GSTTYPE,cm.ServiceTaxNo CompanyGSTNo,cm.VendorGSTNo VendorGSTNo,cm.VendorGSTState,cm.VendorStateCode,ti.total `amount`,ti.tax `ServiceTax`,
ti.sbctax `SbcTax`,ti.krishi_tax `KrishiTax`,ti.igst,ti.cgst,ti.sgst,ti.grnd `GTotal`,ti.invoiceDescription `Remarks`,
IF(cm.po_required = 'Yes',IF(ti.approve_po IS NULL OR ti.approve_po = '','PO Pending',
IF(cm.grn='Yes',IF(ifnull(ti.approve_grn,'')='' OR ti.approve_po = '','GRN Pending','Submitted'),'NA')),
IF(cm.grn='Yes',IF(ifnull(ti.approve_grn,'')='' OR ti.approve_po = '','GRN Pending','Submitted'),'NA')
) `status`,bpp.bill_passed `BillPassed`,bpp.net_amount `payReceived`,bpp.tds_ded `TDS`,
DATE_FORMAT(bpp.pay_dates,'%d-%M-%Y') `ReceieveDate`,CONCAT(bpp.pay_type,bpp.pay_no) `ChequeNo`,bpp.PaymentFile
FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,PaymentFile,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,bpp.PaymentFile,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no 
AND ti.branch_name = bpp.branch_name AND ti.finance_year = bpp.financial_year AND
cm.company_name = bpp.company_name
WHERE ti.bill_no IS NOT NULL and ti.bill_no !='' $branchName $finance_year $companyName
 ORDER BY CONVERT(REPLACE(SUBSTRING_INDEX(ti.bill_no,'/',1),'-',''),UNSIGNED INTEGER),ti.finance_year");
        
       $this->set('data',$data); 
        
    }
}
?>