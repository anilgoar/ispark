<?php
	class CollectionReportsController extends AppController 
	{
		public $uses=array('Collection','Addbranch','Addcompany','OtherDeduction','CollectionParticulars','InitialInvoice','CostCenterMaster','Addclient');
		public $components = array('RequestHandler');
		public $helpers = array('Js');

		public function beforeFilter()
		{
        	parent::beforeFilter();
			
			$this->Auth->allow('get_branch');
			$this->Auth->deny('get_client');
			$this->Auth->deny('get_collectionReport');
			$this->Auth->deny('Other_Deduction');
			$this->Auth->deny('delete_other_deduction');
			$this->Auth->deny('get_bill_amount');
			$this->Auth->deny('back');
                        
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
				if(in_array('24',$roles)){$this->Auth->allow('get_branch','index');
			$this->Auth->allow('get_client');
			$this->Auth->allow('get_collectionReport');
			$this->Auth->allow('Other_Deduction');
			$this->Auth->allow('delete_other_deduction');
			$this->Auth->allow('get_bill_amount');
			$this->Auth->allow('back');
                        $this->Auth->allow('collectionDetails');
                        $this->Auth->allow('view_report');
                        $this->Auth->allow('view_report_performance');
                                }
			}			
			
    	}
		
    	public function index() 
		{
			$this->layout='home';
			$username = $this->Session->read('username');
                        
			//$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
			$this->set('company_master',$this->Addcompany->find('all',array('fields' =>array('company_name'))));
			//$this->set('company_master',$this->Addcompany->find('all',array('fields' =>array('company_name'))));
        }
		
public function get_branch()
{
    $this->layout = 'ajax';
    $result = $this->params->query;
                        
    $role = $this->Session->read('role');
    
    
    $conditions = array();
    
    if($result['company_name']=='All')
    {
        $conditions = array();
    }
    else 
    {
        $conditions = array('company_name'=>$result['company_name']);
    }
    
    $data = array('All'=>'All');
    if($role!='admin')
    {
        $conditions['branch'] =$this->Session->read('branch_name');
        $data = array();
    }
	
    $branch_master = $this->CostCenterMaster->find('all',array('conditions'=>$conditions,'fields'=>array('branch')));
    
    foreach($branch_master as $post):
        $data [$post['CostCenterMaster']['branch']] = $post['CostCenterMaster']['branch'];
    endforeach; unset($branch_master);
    
    $this->set('data',$data);			
}
		public function get_client()
		{
			$this->layout = 'ajax';
			$result = $this->params->query;
                        $conditions = array();
			if($result['branch_name']=='All')
			{
                            $conditions = array();
			}
			else
			{
                            $conditions = array('branch_name'=>$result['branch_name']);
			}
                        $client_master=$this->Addclient->find('all',array('conditions'=>$conditions,'fields'=>array('client_name')));
                        $data = array('All'=>'All');
                        foreach($client_master as $post):
                            $data [$post['Addclient']['client_name']] = $post['Addclient']['client_name'];
			endforeach; unset($branch_master);
			$this->set("data",$data);
		}
public function get_collectionReport()
{
$this->layout = 'ajax';
$result = $this->params->query;

$company = "";
if($result['company_name'] != 'All' )
{$company = "and tab.company_name = '".$result['company_name']."'";}  

$branch = $result['branch_name'] == 'All'?' where 1=1':"where tab.branch_name='".$result['branch_name']."'";
$client = $result['client_name'] == 'All' ?'':($result['branch_name'] == 'All'?"and tab.client='".$result['client_name']."'":"and tab.client='".$result['client_name']."'");

$fromDate =date_create($result['fromDate']);
$fromDate = date_format($fromDate,'Y-m-d');

$toDate = date_create($result['toDate']);
$toDate = date_format($toDate,'Y-m-d');

$date = "AND date(tab.createdate) between '$toDate' AND '$fromDate' ";
                        
$this->set('result1',$result);
    
if($result['report'] == 'amt_wise') {
    $data = $this->CollectionParticulars->query("SELECT *
 FROM (SELECT bpp.branch_name,bpp.pay_dates `createdate`, CONCAT(bpp.pay_type,bpp.pay_no) `ChequeNo`,
CONCAT(bpp.pay_type,bpp.pay_no,bpp.pay_amount) `ChequeNo1`,
cm.client `client`,bpp.financial_year,bpp.company_name `company_name`,
DATE_FORMAT(bpp.pay_dates,'%b %d %Y') `Dates`, 
SUM(IF(bpp.bill_amount IS NULL OR bpp.bill_amount ='',0,bpp.bill_amount)) `net_amount`,
SUM(IF(bpp.tds_ded IS NULL OR bpp.tds_ded ='',0,bpp.tds_ded)) `TDS`,
SUM(IF(bpp.deduction IS NULL OR bpp.deduction ='',0,bpp.deduction)) `Other Ded`,
SUM(IF(tab3.other_deduction_bill IS NULL OR tab3.other_deduction_bill ='',0,tab3.other_deduction_bill)) `other_deduction_bill`,
IF(bpp.pay_amount IS NULL OR bpp.pay_amount = '',0,bpp.pay_amount) `ChequeAmount`,
SUM((IF(bpp.bill_amount IS NULL OR bpp.bill_amount = '',0,bpp.bill_amount)-IF(bpp.tds_ded IS NULL OR bpp.tds_ded ='',0,bpp.tds_ded)-
IF(bpp.deduction IS NULL OR bpp.deduction ='',0,bpp.deduction))) `sdfsdf`
 FROM tbl_invoice ti 
 INNER JOIN cost_master cm ON cm.cost_center = ti.cost_center

 INNER JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars
) bpp 
 ON  bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1) AND bpp.financial_year = ti.finance_year AND bpp.company_name = cm.company_name AND bpp.branch_name = ti.branch_name
 
LEFT JOIN (SELECT company_name,branch_name,financial_year,CONCAT(pay_type,pay_no) `ChequeNo`,bill_no,other_remarks `other_remarks`,
SUM(IF(other_deduction IS NULL OR other_deduction = '',0,other_deduction)) `other_deduction_bill`
 FROM other_deductions_bill GROUP BY company_name,branch_name,financial_year,bill_no) AS tab3 ON 
SUBSTRING_INDEX(ti.bill_no,'/','1') = tab3.bill_no 
AND ti.finance_year = tab3.financial_year 
AND ti.branch_name = tab3.branch_name 
AND cm.company_name = tab3.company_name   
 GROUP BY CONCAT(pay_type,pay_no),bpp.pay_amount,bpp.branch_name,bpp.pay_type_dates 
 
 ORDER BY branch_name ) AS tab
  LEFT JOIN (SELECT CONCAT(pay_type,pay_no,pay_amount) `ChequeNo`,SUM(IF(other_deduction IS NULL OR other_deduction = '',0,other_deduction)) `other_deduction` FROM other_deductions GROUP BY CONCAT(pay_type,pay_no),pay_amount) AS tab2
  ON tab.ChequeNo1 = tab2.ChequeNo $branch $client $date $company order by tab.branch_name");
                        }
if($result['report'] == 'bill_wise') {
    $data = $this->CollectionParticulars->query("SELECT * FROM (SELECT cm.company_name `company_name`,tb.branch_name,tb.bill_no,bpp.bill_passed, bpp.tds_ded,deduction,bpp.net_amount,
CONCAT(pay_type,pay_no) `ChequeNo`,bpp.pay_amount `ChequeAmount`,cm.client `client`,bpp.pay_dates `createdate`,
DATE_FORMAT(tb.invoiceDate,'%b %d %Y' )`invoiceDate`,
tab2.other_remarks `other_remarks`,tab2.other_deduction `other_deduction`,tab3.other_deduction_bill `other_deduction_bill`
  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
INNER JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,
tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates 
FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,
bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,
remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  
GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) 
bpp ON SUBSTRING_INDEX(tb.bill_no,'/','1') = bpp.bill_no 
AND tb.finance_year = bpp.financial_year 
AND tb.branch_name = bpp.branch_name 
AND cm.company_name = bpp.company_name

LEFT JOIN (SELECT financial_year,CONCAT(pay_type,pay_no) `ChequeNo`,GROUP_CONCAT(other_remarks) `other_remarks`,
SUM(IF(other_deduction IS NULL OR other_deduction = '',0,other_deduction)) `other_deduction`
 FROM other_deductions GROUP BY CONCAT(pay_type,pay_no),pay_amount) AS tab2
ON tab2.ChequeNo = CONCAT(bpp.pay_type,bpp.pay_no) AND tab2.financial_year = bpp.financial_year

LEFT JOIN (SELECT company_name,branch_name,financial_year,CONCAT(pay_type,pay_no) `ChequeNo`,bill_no,other_remarks `other_remarks`,
SUM(IF(other_deduction IS NULL OR other_deduction = '',0,other_deduction)) `other_deduction_bill`
 FROM other_deductions_bill GROUP BY company_name,branch_name,financial_year) AS tab3 ON 
SUBSTRING_INDEX(tb.bill_no,'/','1') = tab3.bill_no 
AND tb.finance_year = tab3.financial_year 
AND tb.branch_name = tab3.branch_name 
AND cm.company_name = tab3.company_name

 ORDER BY bpp.branch_name,CONVERT(bpp.bill_no, UNSIGNED INTEGER)) AS tab
 $branch $client $date $company");
                        }                        
                        
                        $this->set('report',$result['report']);
			$this->set("result",$data);
			$this->set("type",$result['type']);
                        $this->set('query',$result);
}

public function collectionDetails()
{
    $this->layout = "ajax";
    $data = $this->params->query['id'];
    
    $field = explode('@@', $data);
    $branch = $field[0];
    $chequeNo = $field[1];
    $payment_date = $field[2];
    $chequeAmount = $field[3];
    
    
    
    $this->set('data',$this->Collection->query("SELECT bill_pay_particulars.*,odb.other_deduction bill_other_deduction FROM bill_pay_particulars 
left JOIN 

(SELECT company_name,branch_name,financial_year,bill_no,
SUM(IF(other_deduction IS NULL ||other_deduction='' ,0,other_deduction))other_deduction  FROM `other_deductions_bill` GROUP BY company_name,branch_name,financial_year,bill_no)  odb
ON bill_pay_particulars.bill_no=odb.bill_no
AND bill_pay_particulars.company_name=odb.company_name
AND bill_pay_particulars.branch_name=odb.branch_name
AND bill_pay_particulars.financial_year=odb.financial_year 
WHERE bill_pay_particulars.branch_name = '$branch' AND 
CONCAT(bill_pay_particulars.pay_type,bill_pay_particulars.pay_no) = '$chequeNo' 
AND bill_pay_particulars.pay_amount = '$chequeAmount' 
AND DATE_FORMAT(bill_pay_particulars.pay_dates,'%b %d %Y') = '$payment_date'"));
    $this->set('data1',$this->Collection->query("select * from other_deductions where branch_name = '$branch' and concat(pay_type,pay_no) = '$chequeNo' and pay_amount = '$chequeAmount' and  DATE_FORMAT(pays_date,'%b %d %Y') = '$payment_date'"));    
}

    public function other_deduction() 
    {
       $this->layout="home";
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
    }

    public function view_report() 
    {
       $this->layout="home";
       $conditions = array() ;
       $all = array('All'=>'All');
       if($this->Session->read('role')!='admin')
       {
         $conditions = array('branch_name'=>$this->Session->read('branch_name'));
         $all = array();
       }
       
      $branch = array_merge($all,$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions)));
      $this->set('branch_master',$branch);
    }
    public function view_report_performance() 
    {
       $this->layout="ajax";
       $this->set('result',$this->params->query);
       
       $start_date = $this->params->query['start_date'];
       $end_date = $this->params->query['end_date'];
       $company = $this->params->query['company'];
       $branch = $this->params->query['BranchName'];
       $report = $this->params->query['report'];
       
       
       $d1 = explode('-',$end_date);
       $d1 = $d1[0];
       
       $start_date = date_create($start_date);
       $start_date = date_format($start_date, 'Y-m-d');
       
       $end_date = date_create($end_date);
       $end_date = date_format($end_date, 'Y-m-d');
       $d2 = date("Y-m-t", strtotime($end_date));
       $d2 = explode('-',$d2);
       $d2 = $d2[2];
       
       
       $companyName1 = $companyName2 = '';
       if($company !='All')
       {
           $companyName1 = "WHERE cm.company_name ='$company'";
           $companyName2 ="and company_name='$company'";
	   $companyName3 ="where ti.company_name='$company'";
           $companyName4 = "and cm.company_name ='$company'";
       }
       
       $branchName = '';$branchName2='';$branchName3='';
       if($branch !='All')
       {
           $branchName = "and tab.branch_name ='$branch'";
           $branchName2 ="and pm.branch_name='$branch'";
           $branchName3 ="and cm.branch='$branch'";
       }
       
       if($report=='Performance') {
           
           
           
           
       $data = $this->InitialInvoice->query("SELECT tab.cost_center,tab.branch_name,tab.`client`,tab.openingOS,tab.FreshBilling,tab.RealisationOB,
tab.RealisationFB,tab.Total,tab.TDS,tab.Deduction,oth.other_deduction,obd.other_deduction_bill,
(tab.Total-tab.TDS-tab.Deduction-IF(oth.other_deduction IS NULL,0,oth.other_deduction)) `NetOS`,
(tab.zero+tab.one+tab.two+tab.three+tab.four) `closingOS`,tab.zero,tab.one,tab.two,tab.three,tab.four,prov.Unprocess,
(tab.zero+tab.one+tab.two+tab.three+tab.four+prov.Unprocess) `TotalOS`
FROM
(SELECT ti.cost_center,ti.branch_name,cm.client,
SUM(IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)),
IF(bpp.bill_no IS NULL,ti.grnd,0),0)+IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) AND
 DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',ti.grnd,0)) `openingOS`,
     
SUM(IF(DATE(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y')) >= DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) ,IF(bpp.bill_no IS NULL,ti.grnd,0),0)+
IF(ti.month = DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y') AND DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date' ,ti.grnd,0))
 `FreshBilling`,
 
SUM(IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) AND
 DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',IF(bpp.net_amount IS NULL OR bpp.net_amount='',0,ti.grnd),0)) `RealisationOB`,
SUM(IF(ti.month = DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y') AND DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date' ,
IF(bpp.net_amount IS NULL OR bpp.net_amount='',0,ti.grnd),0)) `RealisationFB`,

SUM(IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) AND
 DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',IF(bpp.net_amount IS NULL OR bpp.net_amount='',0,ti.grnd),0) +
IF(ti.month = DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y') AND DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date' ,
IF(bpp.net_amount IS NULL OR bpp.net_amount='',0,ti.grnd),0)) `Total`,

SUM(IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) AND
 DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',IF(bpp.tds_ded IS NULL OR bpp.tds_ded='',0,bpp.tds_ded),0) +
IF(ti.month = DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y') AND DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date' ,
IF(bpp.tds_ded IS NULL OR bpp.tds_ded='',0,bpp.tds_ded),0)) `TDS`,

SUM(IF(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y'))<DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) AND
 DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',IF(bpp.deduction IS NULL OR bpp.deduction='',0,bpp.deduction),0) +
IF(ti.month = DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y') AND DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date' ,
IF(bpp.deduction IS NULL OR bpp.deduction='',0,bpp.deduction),0)) `Deduction`,



SUM(IF(DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH)) <= DATE(STR_TO_DATE(CONCAT('1-',ti.month),'%d-%M-%Y')) AND bpp.bill_no IS NULL,ti.grnd,0)) `zero`,
SUM(IF(DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 2 MONTH),'%b-%y') = ti.month AND bpp.bill_no IS NULL,ti.grnd,0)) `one`,
SUM(IF(DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 3 MONTH),'%b-%y') = ti.month AND bpp.bill_no IS NULL,ti.grnd,0)) `Two`,
SUM(IF(DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 4 MONTH),'%b-%y') = ti.month AND bpp.bill_no IS NULL,ti.grnd,0)) `three`,
SUM(IF(DATE(DATE_SUB('$start_date',INTERVAL 4 MONTH)) > LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%M-%Y')) AND bpp.bill_no IS NULL,ti.grnd,0)) `four`
 FROM  tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
LEFT JOIN (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no AND
bpp.financial_year = ti.finance_year AND bpp.branch_name = ti.branch_name AND cm.company_name = bpp.company_name 
WHERE ti.bill_no is not null and ti.bill_no !='' $companyName4
 GROUP BY cm.branch 
)AS tab
LEFT JOIN (SELECT branch_name,SUM(other_deduction) `other_deduction` FROM other_deductions where date(pays_date) between date('$start_date') and date('$end_date') $companyName2 GROUP BY branch_name)AS oth
ON oth.branch_name = tab.branch_name
LEFT JOIN (SELECT branch_name,SUM(other_deduction) `other_deduction_bill` FROM other_deductions_bill where date(pays_date) between date('$start_date') and date('$end_date') $companyName2 GROUP BY branch_name)AS obd
ON obd.branch_name = tab.branch_name
LEFT JOIN (SELECT pm.branch_name,SUM(pm.provision_balance) `Unprocess` FROM provision_master pm left join cost_master cm on cm.cost_center=pm.cost_center $companyName1 GROUP BY branch_name)AS prov
ON tab.branch_name = prov.branch_name where 1=1 $branchName");
       }
 else {
        $data = $this->InitialInvoice->query("SELECT tab.branch,tab.`client`,SUM(collected) `collected`,SUM(`Not_Allocated`) `Not_Allocated`,
    SUM(`PTP_Break`)`PTP_Break`, SUM(`Pmt_Month`)`Pmt_Month`,SUM(`Post_PTP`)`Post_PTP`,SUM(pm.unprocess) `UnProcessed`,
(SUM(`Not_Allocated`)+SUM(`PTP_Break`)+SUM(`Pmt_Month`)+SUM(`Post_PTP`))`Total`,GROUP_CONCAT(`range` ORDER BY `range`) `range`
FROM (SELECT cost_center,ti.branch,ti.client,
SUM(IF(DATE(bpp.pay_dates) BETWEEN '$start_date' AND '$end_date',ti.grnd,0)) `collected`,
SUM(IF(rm.Invoiceid IS NULL AND bpp.bill_no IS NULL,ti.grnd,0)) `Not_Allocated`,
SUM(IF(DATE(rm.ExpDatesPayment) <= DATE('$end_date') AND bpp.bill_no IS NULL,ti.grnd,0)) `PTP_Break`,
SUM(IF(DATE(rm.ExpDatesPayment) > LAST_DAY(DATE('$end_date')) AND bpp.bill_no IS NULL,ti.grnd,0)) `Post_PTP`,
SUM(
IF(DATE(rm.ExpDatesPayment) BETWEEN DATE_ADD(DATE('$end_date'),INTERVAL 1 DAY) AND LAST_DAY(DATE('$start_date')) 
AND rm.InvoiceNo IS NOT NULL AND bpp.bill_no IS NULL,grnd,0)) `Pmt_Month`,
GROUP_CONCAT(IF(DATE(rm.ExpDatesPayment) BETWEEN DATE_ADD(DATE('$end_date'),INTERVAL 1 DAY) AND LAST_DAY(DATE('$start_date')) 
AND rm.InvoiceNo IS NOT NULL AND bpp.bill_no is null,CONCAT(DATE_FORMAT(rm.ExpDatesPayment,'%d'),'-',ti.grnd),NULL) ORDER BY DATE(rm.ExpDatesPayment)) `range`
 FROM (SELECT cm.company_name,cm.client,cm.branch,cm.cost_center,cm.po_required,cm.grn,td.id,td.finance_year,td.bill_no,
td.po_no,td.approve_po,td.grn `grn2`,td.approve_grn,td.total,td.grnd,td.po_date,td.po_remarks,td.grn_date,td.grn_remarks
FROM cost_master cm LEFT JOIN tbl_invoice td ON cm.cost_center = td.cost_center WHERE if(td.cost_center is null,true,td.bill_no IS NOT NULL and td.bill_no !='') 
$branchName3
) ti 
LEFT JOIN bill_pay_particulars bpp ON ti.company_name = bpp.company_name AND ti.branch = bpp.branch_name
AND ti.finance_year = bpp.financial_year AND 
SUBSTRING_INDEX(ti.bill_no,'/',1) = bpp.bill_no
LEFT JOIN (SELECT * FROM `receipt_master`)AS rm ON rm.Invoiceid=ti.id
$companyName3
GROUP BY cost_center
)AS tab
LEFT JOIN (SELECT branch_name,cost_center,SUM(provision) `provision`,SUM(provision_balance)`unprocess` FROM provision_master GROUP BY cost_center)AS pm ON pm.cost_center = tab.cost_center
GROUP BY tab.client,tab.branch
ORDER BY tab.branch");
       }
       $this->set('data',$data);
       $this->set('report',$report);
       $this->set('start_date',$d1);
       $this->set('end_date',$d2);
	$this->set('qry',"");
       
    }

}

?>