<?php
class ReportsController extends AppController 
{
    public $uses=array('CostCenterMaster','InitialInvoice','Addclient','Addbranch','Addcompany','Addprocess','Category','Type','BillMaster');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny('report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','ptp','get_report6','get_report6a','view');
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {   $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            if(1){$this->Auth->allow('report','get_type','get_report','get_report2','get_report3','get_report4','ptp','get_report6','get_report6a','view');}
            if(1){$this->Auth->allow('report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','ptp','get_report6','get_report6a','view');}
        }
    }
		
    public function index() 
    {
    }
    public function report() 
    {
        $this->layout='home';
        $this->set('company_master',$this->Addcompany->find('all',array('fields'=>array('company_name'))));
    }
    public function view()
    {
        $this->layout='home';
        $this->set('company_master',$this->Addcompany->find('all',array('field'=>array('company_name'))));
    }

    public function ptp()
    {
        $this->layout='home';
        $this->set('company_master',$this->Addcompany->find('all',array('field'=>array('company_name'))));	
    }
		
public function get_type() 
{
    $this->layout='ajax';
    $result = $this->params->query;
    $role = $this->Session->read('role');
    $branch_name=array("branch_name"=>$this->Session->read("branch_name"));
    $company =array();
                        
    if($role=='admin')
    {
        $branch_name=array();
        $company =array('company_name' => $result['company_name']);
    }
    else
    {
        $company =array('company_name' => $result['company_name'],'branch'=>$this->Session->read("branch_name"));
    }
    if($result['branch_name'] == 'Branch')
    {
        $this->set("res","1");
				
        if($result['company_name'] == 'All')
        {
            $this->set('branch_master',$this->Addbranch->find('all',array('fields'=>array('branch_name'),'conditions'=>array('active'=>'1'),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
            $this->set('branch_master',$this->Addbranch->find('all',array('fields'=>array('branch_name'),'conditions'=>array('active'=>'1'),'order'=>array('branch_name'=>'asc'))));
        }
    }
    else
    {
        $this->set("res","2");
				
        if($result['company_name'] == 'All')
        {
            $this->set('client_master',$this->CostCenterMaster->find('all',array('fields'=>array('client'),'conditions'=>$branch_name,'order'=>array('client'=>'asc'))));
        }
        else
        {
            $this->set('client_master',$this->CostCenterMaster->find('all',array('fields'=>array('client'),'conditions' =>$company,'order'=>array('client'=>'asc'))));
        }
    } 
			//$this->set('res',$result);
}

public function get_report()
{
    $this->layout='ajax';
    $result = $this->params->query;			
    $this->set('result',$result);			
    
    
    $Year = date('y'); 
    $next_year = $Year+1;
    $last_year = $Year-1;
    $NextYear = '20'.$Year.'-'.$next_year;
    $this->set('Year',$Year);
    
    $BillMaster = $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>"finance_year!='$NextYear'"));
    $AllYear = implode("','",$BillMaster);
    
    $this->set('type','2');
			
    $company = $result['company_name'] == 'All'?'':"and t2.company_name='".$result['company_name']."'";
    $branch = '';
				
    if($result['type'] == 'Client'){$branch = $result['wise']=='all'?'':"and t2.client = '".$result['wise']."'";} 
    else {$branch = $result['wise']=='all'?'':"and t1.branch_name = '".$result['wise']."'";}
				
    $status = $result['bill_status'] == 'all'?"":($result['bill_status'] == 'IAP'?"and t1.bill_no=''":($result['bill_status'] == 'TBB'?"":"and t1.bill_no != ''"));
	
    
    $branch_name ='';
    if($this->Session->read('role')!='admin') //for branches show only theire bills
    {
        $branch_name=" and t1.branch_name='".$this->Session->read("branch_name")."'"; 
    }
    
    
    $query = $company.' '.$branch.' '.$status.$branch_name; 
    $this->set('results',$query);
    if($result['report_type']=='outstanding')
    {	$this->set('report','1');				
//	echo "SELECT t1.id,t1.createdate, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client,t1.bill_no,t1.branch_name,t1.finance_year,
//   t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst,t1.grnd,t1.invoiceDescription,t1.invoiceDate,t1.grn,
//   (IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60',
//   '30-60',
//   IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90',
//   IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','>150 Days'))))))) `Ageing`,
//   (IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending',
//   IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_po IS NULL),'GRN Pending','submitted'))) `bill_status`
//    FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3
//     ON t1.id = t3.Invoiceid  left join bill_pay_particulars bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
//      WHERE t1.status = '0' and bpp.bill_no is null $query order by t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT) "; exit;
        $this->set("res",$this->InitialInvoice->query("SELECT t1.id,t1.eptp_act_date,t1.createdate, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client,
t1.bill_no,t1.branch_name,t1.finance_year, t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst,bpp.net_amount,bpp.tds_ded,
t1.grnd,t1.invoiceDescription,t1.invoiceDate,t1.grn, 
(IF(CurrentInvoiceType='Dispute','Disputed',IF(CurrentInvoiceType='Write-Off','Write-Off',IF(bpp.status = 'part payment','part payment',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days')))))))))) `Ageing`, 
IF(CurrentInvoiceType='Dispute','Disputed',IF(CurrentInvoiceType='Write-Off','Write-Off',IF(bpp.status = 'part payment','part payment',IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', IF(t2.grn='Yes' AND 
(t1.approve_grn = '' OR t1.approve_po IS NULL),'GRN Pending','submitted'))))) `bill_status` FROM tbl_invoice t1 
INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid  
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year 
AND t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name 
WHERE t1.grnd!=0 and t1.grnd!=1  and t1.status = '0' AND (bpp.status ='part payment' || bpp.status is null) $query  ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
	
    $this->set("res1",$this->InitialInvoice->query("select *,IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))Ageing from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $query1")); 
        
        
    }
    else if($result['report_type']=='outstandingBranchWise')
    {	$this->set('report','3');				
				
        $this->set("res",$this->InitialInvoice->query("SELECT 
t2.client,
t1.branch_name,
t1.finance_year,
t1.month,
t1.total,
t1.igst,
t1.cgst,
t1.sgst,
bpp.net_amount,
bpp.tds_ded,
t1.grnd,
(IF(t1.CurrentInvoiceType = 'Dispute','Disputed',IF(t1.CurrentInvoiceType='Write-Off' ,'Write-Off',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','0-30',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))))) `Ageing`

 FROM tbl_invoice t1 
INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid  
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year 
AND t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name 
WHERE  t1.status = '0' and t1.grnd!=0 and t1.grnd!=1 AND (bpp.status ='part payment' || bpp.status IS NULL) $QUERY  ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
        
   $this->set("res1",$this->InitialInvoice->query("SELECT t2.client,t1.branch_name,t1.finance_year,t1.month,t1.provision_balance,ROUND(provision_balance*0.18)igst,'' sgst,'' cgst,ROUND(provision_balance*1.18) grnd,
(IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','0-30',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))) Ageing FROM provision_master
 t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE t1.provision_balance!=0 $branch"));      
    }
    else
    {
        
        $this->set('report','2');
        $this->set("res",$this->InitialInvoice->query("SELECT * FROM (SELECT t1.branch_name,t2.client, SUM(CASE WHEN finance_year IN ('$AllYear')
  THEN t1.grnd ELSE '0' END)`total`, (CASE WHEN finance_year IN('$AllYear') THEN 'Previous' ELSE 'Previous' END)`month`,
   t1.finance_year FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center
   left join (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
    WHERE t1.status = '0' AND bpp.bill_no is null $query
    GROUP BY t2.client,t1.branch_name UNION 
    SELECT  t1.branch_name,t2.client,
     SUM(CASE WHEN finance_year NOT IN ('$AllYear') THEN t1.grnd ELSE '0' END)`total`,
      (CASE WHEN finance_year NOT IN ('$AllYear') THEN CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year') ELSE CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year') END)`month`, 
      t1.finance_year FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center
      left join (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
       WHERE t1.status = '0' AND bpp.bill_no is null $query
       GROUP BY CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year'),t2.client,t1.branch_name)AS tab WHERE total!=0 ORDER BY branch_name")); 
    }
						
}  
public function get_report2()
{
    $this->layout='ajax';
    $result = $this->params->query;			
    $this->set('result',$result);			
	
    $Year = date('y'); 
    $next_year = $Year+1;
    $last_year = $Year-1;
    $NextYear = '20'.$Year.'-'.$next_year;
    $this->set('Year',$Year);
    
    $BillMaster = $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>"finance_year!='$NextYear'"));
    $AllYear = implode("','",$BillMaster);
    
    $this->set('type','2');
    $this->set('report_name',$result['report_type']);
			
    $company = $result['company_name'] == 'All'?'':"and t2.company_name='".$result['company_name']."'";
    $branch = '';
				
    if($result['type'] == 'Client'){$branch = $result['wise']=='all'?'':"and t2.client = '".$result['wise']."'";} 
    else {$branch = $result['wise']=='all'?'':"and t1.branch_name = '".$result['wise']."'";}
				
    $status = $result['bill_status'] == 'all'?"":($result['bill_status'] == 'IAP'?"and t1.bill_no=''":($result['bill_status'] == 'TBB'?"":"and t1.bill_no != ''"));
	
    
    $branch_name ='';
    if($this->Session->read('role')!='admin') //for branches show only theire bills
    {
        $branch_name=" and t1.branch_name='".$this->Session->read("branch_name")."'"; 
    }
    
    
    $query = $company.' '.$branch.' '.$status.$branch_name; 
    $query1 = $company.' '.$branch.' '.$branch_name; 
    //$this->set('qry',$result);
    $this->set('results',$query);
    if($result['report_type']=='outstanding')
    {	$this->set('report','1');				
//	echo "SELECT t1.id,t1.createdate, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client,t1.bill_no,t1.branch_name,t1.finance_year,
//   t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst,t1.grnd,t1.invoiceDescription,t1.invoiceDate,t1.grn,
//   (IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60',
//   '30-60',
//   IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90',
//   IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','>150 Days'))))))) `Ageing`,
//   (IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending',
//   IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_po IS NULL),'GRN Pending','submitted'))) `bill_status`
//    FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3
//     ON t1.id = t3.Invoiceid  left join bill_pay_particulars bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
//      WHERE t1.status = '0' and bpp.bill_no is null $query order by t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT) "; exit;
        $this->set("res",$this->InitialInvoice->query("SELECT t1.id,
        t1.eptp_act_date,
        t1.createdate,
        t1.category,
        t3.ExpDatesPayment,
        t2.company_name, 
        t2.cost_center,
        t2.CostCenterName,
        t2.client,
t1.bill_no,t1.branch_name,t1.finance_year, t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst,bpp.net_amount,bpp.tds_ded,
t1.grnd,t1.invoiceDescription,t1.invoiceDate,t1.grn, 
(IF(CurrentInvoiceType='Dispute','Disputed',IF(CurrentInvoiceType='Write-Off','Write-Off',IF(bpp.status = 'part payment',
'part payment',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days')))))))))) `Ageing`, 
IF(CurrentInvoiceType='Dispute','Disputed',IF(CurrentInvoiceType='Write-Off','Write-Off',IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', IF(t2.grn='Yes' AND 
(t1.approve_grn = '' OR t1.approve_po IS NULL),'GRN Pending','submitted'))))) `bill_status` FROM tbl_invoice t1 
INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid  
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,
bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp 
ON SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year 
AND t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name 
WHERE t1.grnd!=0 and t1.grnd!=1 and t1.status = '0' AND (bpp.status ='part payment' || bpp.status is null) $query
  ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
	
    $this->set("res1",$this->InitialInvoice->query("select *,IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','&lt;30 Days',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))Ageing 
from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $query1")); 
        
        
    }
    else if($result['report_type']=='outstandingBranchWise')
    {	$this->set('report','3');				
				
        $this->set("res",$this->InitialInvoice->query("SELECT 
t2.client,
t1.branch_name,
t1.finance_year,
t1.month,
t1.total,
t1.igst,
t1.cgst,
t1.sgst,
t1.category,
bpp.net_amount,
bpp.tds_ded,
t1.grnd,
(IF(t1.CurrentInvoiceType = 'Dispute','Disputed',IF(t1.CurrentInvoiceType='Write-Off' ,'Write-Off',IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','0-30',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))))) `Ageing`

 FROM tbl_invoice t1 
INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid  
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year 
AND t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name 
WHERE  t1.status = '0' and t1.grnd!=0 and t1.grnd!=1 AND (bpp.status ='part payment' || bpp.status IS NULL) $query  ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
        $this->set("res1",$this->InitialInvoice->query("SELECT t2.client,t1.branch_name,t1.finance_year,t1.month,t1.provision_balance,ROUND(provision_balance*0.18)igst,'' sgst,'' cgst,ROUND(provision_balance*1.18) grnd,
(IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'30','0-30',
IF (DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60', '30-60', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'60','30-60',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'90','60-90', 
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'120','90-120',
IF(DATEDIFF(CURDATE(),ADDDATE(STR_TO_DATE(CONCAT('1-',`month`),'%d-%M-%Y'),INTERVAL 1 MONTH))<'150','120-150','&gt;150 Days'))))))) Ageing FROM provision_master
 t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center WHERE t1.provision_balance!=0 $branch")); 
    }
    else
    {
        $this->set('report','2');
        $this->set("res",$this->InitialInvoice->query("SELECT * FROM (SELECT t1.branch_name,t2.client, SUM(CASE WHEN finance_year IN ('$AllYear')
  THEN t1.grnd ELSE '0' END)`total`, (CASE WHEN finance_year IN('$AllYear') THEN 'Previous' ELSE 'Previous' END)`month`,
   t1.finance_year FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center
    left join (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
    WHERE t1.status = '0' AND bpp.bill_no is null $query
    GROUP BY t2.client,t1.branch_name UNION SELECT  t1.branch_name,t2.client,
     SUM(CASE WHEN finance_year NOT IN ('$AllYear') THEN t1.grnd ELSE '0' END)`total`,
      (CASE WHEN finance_year NOT IN ('$AllYear') THEN CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year') ELSE CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year') END)`month`, 
      t1.finance_year FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center
       left join (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp on SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no and t1.finance_year = bpp.financial_year and t2.company_name=bpp.company_name AND t1.branch_name = bpp.branch_name
        WHERE t1.status = '0' AND bpp.bill_no is null $query
       GROUP BY CONCAT(SUBSTRING_INDEX(`month`,'-',1),'-$Year'),t2.client,t1.branch_name)AS tab WHERE total!=0 ORDER BY branch_name"));
    }					
}

public function bill_genrate_report()
{
    $this->layout='home';
    $this->set('company_master',$this->Addcompany->find('all',array('fields'=>array('company_name'))));
}
public function get_report3()
{
$this->layout='ajax';
$result = $this->params->query;			
//$this->set("res",$result);
$to_date = date_create($result['to_date']);
$to_date = date_format($to_date,"Y-m-d");
		
$from_date = date_create($result['from_date']);
$from_date = date_format($from_date,"Y-m-d");
$company_name = $result['company_name'];
		
if($result['company_name'] == 'All')
{
    if($result['status'] == 'Invoice Date')
    {
	$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''    AND DATE(t1.invoiceDate) BETWEEN '$to_date' AND '$from_date'
  ORDER BY t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
    }
    else
    {
	$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !='' 
  AND DATE(t1.invoiceDate) BETWEEN '$to_date' AND '$from_date' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
    }
}
else
{
    if($result['status'] == 'Invoice Date')
    {
	$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''  AND DATE(t1.createdate) BETWEEN '$to_date' AND '$from_date' AND t2.company_name = '$company_name' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
    }
    else
    {
	$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''   AND DATE(t1.createdate) BETWEEN '$to_date' AND '$from_date' AND t2.company_name = '$company_name' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
    }
}
}
	public function get_report4()
	{
		$this->layout='ajax';
		$result = $this->params->query;			
		//$this->set("res",$result);
		$to_date = date_create($result['to_date']);
		$to_date = date_format($to_date,"Y-m-d");
		
		$from_date = date_create($result['from_date']);
		$from_date = date_format($from_date,"Y-m-d");
		$company_name = $result['company_name'];
		
		if($result['company_name'] == 'All')
		{
			if($result['status'] == 'Invoice Date')
			{
				$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''   AND DATE(t1.invoiceDate) BETWEEN '$to_date' AND '$from_date'
  ORDER BY t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
			}
			else
			{
				$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''  AND DATE(t1.invoiceDate) BETWEEN '$to_date' AND '$from_date' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
			}
		}
		else
		{
			if($result['status'] == 'Invoice Date')
			{
				$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''  AND DATE(t1.createdate) BETWEEN '$to_date' AND '$from_date' AND t2.company_name = '$company_name' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"));
			}
			else
			{
				$this->set("res",$this->InitialInvoice->query("SELECT t1.branch_name,t1.month,t2.client,t2.company_name, t1.total, t1.tax,t1.sbctax,t1.krishi_tax,
t1.igst,t1.cgst,t1.sgst,t1.grnd, t1.finance_year, t1.cost_center,
 t1.bill_no,t1.finance_year, t1.month, t1.invoiceDescription, t1.invoiceDate,
 IF(po_required='Yes',IF(po_no IS NULL OR po_no='','Pending','Received'),'NA') `po_status`,
 IF(t2.grn='Yes',IF(t1.grn IS NULL OR t1.grn='','Pending','Received'),'NA') `grn_status`,t1.createdate,
 IF(t4.status='paid','Received',if(t4.status='part payment','Part Payment','Pending')) `Payment Status`,
 t4.deduction,t5.bill_deduction,t6.other_deduction,t4.pay_type,t4.pay_no
  FROM tbl_invoice t1
  INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
  /* bill wise other deduction */  
  LEFT JOIN (
    SELECT bill_no,company_name,branch_name,financial_year,
SUM(other_deduction) bill_deduction FROM `other_deductions_bill`
GROUP BY financial_year,company_name,branch_name,bill_no)t5
   ON SUBSTRING_INDEX(t1.bill_no,'/',1) =t5.bill_no AND  t1.finance_year =t5.financial_year
  AND t2.company_name = t5.company_name AND t1.branch_name = t5.branch_name
  
/* Invoice Collection Status */  
  LEFT JOIN 
  (
    SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
  ) t4 ON t4.bill_no = SUBSTRING_INDEX(t1.bill_no,'/',1) AND t4.financial_year = t1.finance_year
  AND t2.company_name = t4.company_name AND t1.branch_name = t4.branch_name
  
 /* Collecton Other Deduction on Complete Payment */
 LEFT JOIN (SELECT company_name,branch_name,financial_year,pay_type,pay_no,SUM(other_deduction) other_deduction FROM  other_deductions
 GROUP BY company_name,branch_name,financial_year,pay_type,pay_no
 ) t6 ON t4.financial_year=t6.financial_year AND t4.company_name = t6.company_name AND t4.branch_name = t6.branch_name AND
  t4.pay_no = t6.pay_no AND t4.pay_type = t6.pay_type
   
  WHERE t1.status != '1' AND t1.bill_no IS NOT NULL AND t1.bill_no !=''  AND DATE(t1.invoiceDate) BETWEEN '$to_date' AND '$from_date' AND t2.company_name = '$company_name' order by t1.finance_year,CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT) "));
			}
		}
	}   
	
	public function get_report6()   //PTP REPORT
	{
	$this->layout="ajax";
	$result=$this->params->query;
	    $to_date = date_create($result['AddToDate']);
		$to_date = date_format($to_date,"Y-m-d");
		
		$from_date = date_create($result['AddFromDate']);
		$from_date = date_format($from_date,"Y-m-d");
	
	    $AddSelectReport  = $result['AddSelectReport'];
		$company = $result['AddCompanyName'];
	    $branch  = $result['AddBranchName'];
		$AddSelectReport  = $result['AddSelectReport'];

	
	
	
if($AddSelectReport=='Ptp Report')	
 {     
 	$this->set('Report','Ptp') ;
	if($result['AddCompanyName']=='All')    
	{
$this->set('data',$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
t1.invoiceDescription,t3.ExpDatesPayment FROM  tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
	}else{
			
			$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.ExpDatesPayment FROM  tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE  t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center  INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid  WHERE t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
			}
 }
else
{
	
	if($result['AddCompanyName']=='All')    
	{
	$this->set('Report','Submission') ;	
$this->set('data',$this->InitialInvoice->query("SELECT t2.company_name,t1.bill_no,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
t1.invoiceDescription,t3.SubmitedDates FROM  tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center 
INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
	}else{
			
			$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.bill_no,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.SubmitedDates FROM  tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE  t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center  INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid  WHERE t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
			}


	
}


 
}
public function get_report6a()
{
	$this->layout="ajax";
	$result=$this->params->query;
	$this->set('result',$result);
	
	    $to_date = date_create($result['AddToDate']);
		$to_date = date_format($to_date,"Y-m-d");
		
		$from_date = date_create($result['AddFromDate']);
		$from_date = date_format($from_date,"Y-m-d");
	
	$company = $result['AddCompanyName'];
	$branch  = $result['AddBranchName'];
	$AddSelectReport  = $result['AddSelectReport'];

if($AddSelectReport=='Ptp Report')	
 {     
 	$this->set('Report','Ptp') ;

	
	if($company =='All')    
	{
      $this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.ExpDatesPayment FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
		}else{
			
			$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.ExpDatesPayment FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' WHERE t2.company_name='$company' AND t1.branch_name='$branch' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center  INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid  WHERE t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(ExpDatesPayment) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
			}
 }
 else
 {
$this->set('Report','Submission') ;		 
	if($company =='All')    
	{
      $this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.bill_no,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.SubmitedDates FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(SubmitedDates) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
		}else{
			
			$this->set("data",$this->InitialInvoice->query("SELECT t2.company_name,t1.bill_no,t2.cost_center,t1.invoiceDate,t1.branch_name,t1.month,t1.total,
	  t1.invoiceDescription,t3.SubmitedDates FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid WHERE DATE(t3.SubmitedDates) BETWEEN '$to_date' AND '$from_date' WHERE t2.company_name='$company' AND t1.branch_name='$branch' ORDER BY t2.company_name"));
	  
	  $this->set("rest",$this->InitialInvoice->query("SELECT t2.cost_center, t1.branch_name, SUM(t1.total)'total' FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center=t2.cost_center  INNER JOIN receipt_master t3 ON t1.id=t3.Invoiceid  WHERE t2.company_name='$company' AND t1.branch_name='$branch'  AND DATE(SubmitedDates) BETWEEN '$to_date' AND '$from_date' GROUP BY cost_center"));
			}
	 
	 
	 
 }
 
 
	}

	
	
}
?>