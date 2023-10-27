<?php
	class CollectionReportsController extends AppController 
	{
		public $uses=array('Collection','BillMaster','Provision','Addbranch','Addcompany',
                    'OtherDeduction','CollectionParticulars','InitialInvoice','CostCenterMaster',
                    'Addclient','CollectionTrackingMatrix');
		public $components = array('RequestHandler');
		public $helpers = array('Js');

		public function beforeFilter()
		{
        	parent::beforeFilter();
			
			$this->Auth->allow('get_branch','add_eptp_date','get_eptp_track','report_eptp_tracking','collection_tracking_ptp_report'
                ,'get_coll_eptp_track','collection_tracking_matrix');
			$this->Auth->allow('get_client');
			$this->Auth->allow('get_collectionReport');
			$this->Auth->allow('Other_Deduction');
			$this->Auth->allow('delete_other_deduction');
			$this->Auth->allow('get_bill_amount');
			$this->Auth->allow('back');
                       
                        
                        
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'login'));
			}
			else
			{
				$role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
				
                                $this->Auth->allow('get_branch','index');
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
		
                $this->Auth->allow('report_collection_tracking','get_coll_track','add_eptp_date','get_prov_mnt');
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
SUM(bill_passed)bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
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
    
    $qry4 = "SELECT * FROM (SELECT bpp.*,ti.client,CONCAT(bpp.pay_type,bpp.pay_no) `ChequeNo`,DATE_FORMAT(bpp.pay_dates,'%b %d %Y') `Dates`,
        bpp.pay_amount ChequeAmount
        FROM (
    SELECT company_name,createdate,branch_name,financial_year,bill_no,SUM(bill_passed) bill_passed,SUM(tds_ded) tds_ded, 
    GROUP_CONCAT(pay_type)pay_type,GROUP_CONCAT(pay_no)pay_no,GROUP_CONCAT(pay_amount)pay_amount,pay_dates,net_amount 
    FROM bill_pay_advance tab WHERE 1=1  $date
     GROUP BY financial_year,company_name, branch_name,bill_no)bpp 
     LEFT JOIN 
     cost_master  ti ON bpp.company_name = ti.company_name 
     AND bpp.branch_name = ti.branch 
     AND ti.id = bpp.bill_no)tab 
     $branch $client $date $company"; 
    $data2 = $this->CollectionParticulars->query($qry4);
    
    
                        }
if($result['report'] == 'bill_wise') {

$qry = "SELECT * FROM (SELECT cm.company_name `company_name`,tb.branch_name,tb.bill_no,bpp.bill_passed, bpp.tds_ded,deduction,bpp.net_amount,
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
createdate pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,
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
 $branch $client $date $company";

  $qry2 = "SELECT * FROM (SELECT cm.company_name `company_name`,ti.branch_name,ti.bill_no,bpp.bill_no bill_no_bpp,bpp.bill_passed, bpp.tds_ded,
bpp.net_amount, CONCAT(pay_type,pay_no) `ChequeNo`,bpp.pay_amount `ChequeAmount`,cm.client `client`,bpp.pay_dates
 `createdate`, DATE_FORMAT(ti.invoiceDate,'%b %d %Y' )`invoiceDate` FROM cost_master cm 
 INNER JOIN tbl_invoice ti ON ti.cost_center = cm.cost_center 
 INNER JOIN (
 SELECT company_name,branch_name,financial_year,bill_no,SUM(bill_passed) bill_passed,SUM(tds_ded) tds_ded,
 GROUP_CONCAT(pay_type)pay_type,GROUP_CONCAT(pay_no)pay_no,GROUP_CONCAT(pay_amount)pay_amount,pay_dates,net_amount FROM 
 bill_pay_particulars tab where 1=1 $date GROUP BY financial_year,company_name,
 branch_name,bill_no ) 
 bpp ON bpp.company_name = cm.company_name AND bpp.branch_name = cm.branch
  AND bpp.financial_year = ti.finance_year AND SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no 
   )tab $branch $client $date $company";  

    $qry3 = "SELECT * FROM (SELECT bpp.*,ti.client FROM (
    SELECT company_name,createdate,branch_name,financial_year,bill_no,SUM(bill_passed) bill_passed,SUM(tds_ded) tds_ded, 
    GROUP_CONCAT(pay_type)pay_type,GROUP_CONCAT(pay_no)pay_no,GROUP_CONCAT(pay_amount)pay_amount,pay_dates,net_amount 
    FROM bill_pay_particulars tab WHERE 1=1  $date
     GROUP BY financial_year,company_name, branch_name,bill_no)bpp LEFT JOIN 
     ( SELECT ti.bill_no,ti.finance_year,cm.* FROM tbl_invoice ti 
     INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center) ti ON bpp.company_name = ti.company_name 
     AND bpp.branch_name = ti.branch AND bpp.financial_year = ti.finance_year 
     AND SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no)tab 
     $branch $client $date $company"; 

    
    $qry4 = "SELECT * FROM (SELECT bpp.*,ti.client FROM (
    SELECT company_name,createdate,branch_name,financial_year,bill_no,SUM(bill_passed) bill_passed,SUM(tds_ded) tds_ded, 
    GROUP_CONCAT(pay_type)pay_type,GROUP_CONCAT(pay_no)pay_no,GROUP_CONCAT(pay_amount)pay_amount,pay_dates,net_amount 
    FROM bill_pay_advance tab WHERE 1=1  $date
     GROUP BY financial_year,company_name, branch_name,bill_no)bpp 
     LEFT JOIN 
     cost_master  ti ON bpp.company_name = ti.company_name 
     AND bpp.branch_name = ti.branch 
     AND ti.id = bpp.bill_no)tab 
     $branch $client $date $company"; 
    
    $data = $this->CollectionParticulars->query($qry3);
    $data2 = $this->CollectionParticulars->query($qry4);
    
                        }                        
                        
            $this->set('report',$result['report']);
			$this->set("result",$data);
                        $this->set("result2",$data2);
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
           $branchName4 ="and branch_name='$branch'";
       }
       
       
       
       if($report=='test') {
           
           
           
           
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
       else if($report=='Performance') {
               $query_invoice = "SELECT ti.id,cm.id,ti.branch_name,ti.cost_center,ti.month,cm.company_name,cm.cost_center,
                ti.finance_year,ti.bill_no,ti.grnd,cm.branch,
                DATE(LAST_DAY(STR_TO_DATE(CONCAT('1-',ti.`month`),'%d-%b-%Y'))) mnt,
                DATE(STR_TO_DATE(CONCAT('1-',ti.month),'%d-%M-%Y')) crtMnt,
                DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH))start_date,
                DATE_FORMAT(DATE_SUB('$start_date',INTERVAL 1 MONTH),'%b-%y')mnt2,
DATE(DATE_SUB('$start_date',INTERVAL 1 MONTH))mntZero,
DATE(DATE_SUB('$start_date',INTERVAL 2 MONTH))mntOne,
DATE(DATE_SUB('$start_date',INTERVAL 3 MONTH))mntTwo,
DATE(DATE_SUB('$start_date',INTERVAL 4 MONTH))mntThree,    
    
IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),'PO Pending', 
IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_grn IS NULL),'GRN Pending','submitted'))
 `bill_status`
FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
INNER JOIN branch_master bm ON ti.branch_name = bm.branch_name and bm.active='1'
WHERE `status`='0' and ti.bill_no!='' and ti.grnd!=0 and ti.grnd!=1 and ti.finance_year in ('2016-17','2017-18','2018-19','2019-20','2020-21','2021-22')  $companyName4    $branchName3
  group by ti.id ";
            $data_invoice = $this->InitialInvoice->query($query_invoice);
            //print_r($data_invoice); exit;
            foreach($data_invoice as $inv)
            {
                $mnt = $inv['0']['mnt'];
                $start_date_mnt = $inv['0']['start_date'];
                $mnt2 = $inv['0']['mnt2'];
                $mntZero = $inv['0']['mntZero'];
                $mntOne = $inv['0']['mntOne'];
                $mntTwo = $inv['0']['mntTwo'];
                $mntThree = $inv['0']['mntThree'];
                $crtMnt = $inv['0']['crtMnt'];
                
                //For openingOS 
                $openingOS = 0; $RealisationOB = 0; $Total=0;$tds = 0;$Deduction = 0;$zero=0;
                if(strtotime($mnt)<strtotime($start_date_mnt))
                {
                    
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded,deduction FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'  limit 1";
                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    
                    if(empty($data_paid_status))
                    {
                        $openingOS += $inv['ti']['grnd'];
                    }
                    else
                    {
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,bill_passed,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                        
                         //print_r($data_part_status); exit;
                        
                        foreach($data_part_status as $bpp)
                        {
                            if(strtotime($bpp['0']['pay_dates'])>=strtotime($start_date) &&  strtotime($bpp['0']['pay_dates'])<=strtotime($end_date))
                            {
                                $openingOS +=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                               $RealisationOB += ($bpp['bpp']['bill_passed']); 
                               $tds += $bpp['bpp']['tds_ded'];
                               $Deduction += $bpp['bpp']['deduction'];
                               $Total += ($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']);
                            }
                            else
                            {
                                
                                
                            }
                        }
                            
                            //echo "$collection-$opening";
                            //exit;
                        
                    }
                }
                
                //For FreshBilling 
                $FreshBilling1 = 0;
                if(strtotime($mnt)>=strtotime($start_date_mnt))
                {
                    $FreshBilling1 = $inv['ti']['grnd'];
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded,deduction FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and bpp.status='paid' limit 1";
                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    
                    if(empty($data_paid_status))
                    {
                        
                    }
                    else
                    {
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                        
                       

                        if(!empty($data_part_status))
                        {
                             //print_r($data_part_status); exit;
                            foreach($data_part_status as $bpp)
                            {
                                if(strtotime($bpp['0']['pay_dates'])>=strtotime($start_date) &&  strtotime($bpp['0']['pay_dates'])<=strtotime($end_date))
                                {
                                   
                                }
                                else
                                {
                                     
                                }
                                $FreshBilling1 -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']);
                            }
                            
                            //echo "$collection-$opening";
                            //exit;
                        }
                    }
                }
                
                $RealisationFB = 0;
                $FreshBilling2 =0; $remarks2 = "";
                
                //New Code
                if(strtotime($crtMnt)>=strtotime($start_date) && strtotime($crtMnt)<=strtotime($end_date))
                {
                    $data_part_status = $this->CollectionParticulars->query("SELECT id,status,date(pay_dates)pay_dates,bill_passed,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");

                    foreach($data_part_status as $bpp)
                    {
                        if(strtotime($bpp['0']['pay_dates'])>=strtotime($start_date) &&  strtotime($bpp['0']['pay_dates'])<=strtotime($end_date))
                        {
                            $RealisationFB += ($bpp['bpp']['bill_passed']);
                        }
                    }


                }
                
                if($inv['ti']['month']==$mnt2)
                {
                    
                    $FreshBilling2 = $inv['ti']['grnd'];
                    
                    $data_part_status = $this->CollectionParticulars->query("SELECT id,status,date(pay_dates)pay_dates,bill_passed,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");

                    if(!empty($data_part_status))
                    {
                        foreach($data_part_status as $bpp)
                        {
                            if($inv['ti']['month']==$mnt2 && strtotime($bpp['0']['pay_dates'])>=strtotime($start_date) &&  strtotime($bpp['0']['pay_dates'])<=strtotime($end_date))
                            {
                                $RealisationFB += ($bpp['bpp']['bill_passed']);
                                $remarks2 .=$bpp['bpp']['id'].",";
                            }
                            if(strtotime($bpp['0']['pay_dates'])>=strtotime($start_date) &&  strtotime($bpp['0']['pay_dates'])<=strtotime($end_date))
                            {
                               
                               $Total += ($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']);
                               $tds += $bpp['bpp']['tds_ded'];
                               $Deduction += $bpp['bpp']['deduction'];
                            }
                            else
                            {
                                
                                $FreshBilling2 -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']);
                                 
                            }
                        }
                    }
                    else
                    {
                        //$Total += $inv['ti']['grnd'];
                    }
                }
                
                $zero = 0;
                if(strtotime($mntZero)<=strtotime($crtMnt))
                {
                    $zero = $inv['ti']['grnd'];
                    $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                          
                    if(!empty($data_part_status))
                    {
                         //print_r($data_part_status); exit;
                        foreach($data_part_status as $bpp)
                        {
                            $zero -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                        }
                    }
                }
                
                $one = 0;
                if(strtotime($mntOne)==strtotime($crtMnt))
                {
                    $one = $inv['ti']['grnd'];
                    $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                          
                    if(!empty($data_part_status))
                    {
                         //print_r($data_part_status); exit;
                        foreach($data_part_status as $bpp)
                        {
                            $one -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                        }
                    }
                }
                
                $two = 0;
                if(strtotime($mntTwo)==strtotime($crtMnt))
                {
                    $two = $inv['ti']['grnd'];
                    $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                          
                    if(!empty($data_part_status))
                    {
                         //print_r($data_part_status); exit;
                        foreach($data_part_status as $bpp)
                        {
                            $two -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                        }
                    }
                }
                
                $three = 0;
                if(strtotime($mntThree)==strtotime($crtMnt))
                {
                    $three = $inv['ti']['grnd'];
                    $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                          
                    if(!empty($data_part_status))
                    {
                         //print_r($data_part_status); exit;
                        foreach($data_part_status as $bpp)
                        {
                            $three -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                        }
                    }
                }
                
                $four = 0;
                
                //echo "strtotime($mntThree)>strtotime($mnt)"; exit;
                if(strtotime($mntThree)>strtotime($mnt))
                {
                    
                    $four = $inv['ti']['grnd'];
                    
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded,deduction FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'  limit 1";
                $data_paid_status = $this->CollectionParticulars->query($qr);
                
                
                
                    if(empty($data_paid_status))
                    {
                       // $four = 0;
                    }
                    else
                    {
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded,deduction FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                          
                    

                        foreach($data_part_status as $bpp)
                        {
                            $four -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']+$bpp['bpp']['deduction']); 
                        }
                        
                    }
                    
                    
                }
                
                
                $branch_master[] = $inv['ti']['branch_name'];
                //$data_branch[$inv['cm']['client']] = $inv['cm']['client'];
                $data_branch[$inv['ti']['branch_name']]['openingOS'] += $openingOS;
                $data_branch[$inv['ti']['branch_name']]['FreshBilling'] += ($FreshBilling1+$FreshBilling2);
                $data_branch[$inv['ti']['branch_name']]['RealisationOB'] += ($RealisationOB);
                $data_branch[$inv['ti']['branch_name']]['RealisationFB'] += ($RealisationFB);
                $data_branch[$inv['ti']['branch_name']]['Total'] += ($RealisationFB+$RealisationOB);
                $data_branch[$inv['ti']['branch_name']]['Deduction'] += ($Deduction);
                $data_branch[$inv['ti']['branch_name']]['TDS'] += ($tds);
                $data_branch[$inv['ti']['branch_name']]['zero'] += ($zero);
                $data_branch[$inv['ti']['branch_name']]['one'] += ($one);
                $data_branch[$inv['ti']['branch_name']]['two'] += ($two);
                $data_branch[$inv['ti']['branch_name']]['three'] += ($three);
                $data_branch[$inv['ti']['branch_name']]['four'] += ($four);
                $data_branch[$inv['ti']['branch_name']]['remarks'] .= $remarks2;
                
            }
       
            
            
            
            $data_other_deduction = $this->CollectionParticulars->query("SELECT branch_name,SUM(other_deduction) `other_deduction` FROM other_deductions ods WHERE DATE(pays_date) BETWEEN DATE('$start_date') AND DATE('$end_date') $companyName2 $branchName4 GROUP BY branch_name");
            foreach($data_other_deduction as $oth)
            {
                $data_branch[$oth['ods']['branch_name']]['other_deduction'] += ($oth['0']['other_deduction']);
                $branch_master[] = $oth['ods']['branch_name'];
            }
            
            $data_other_deduction_bill = $this->CollectionParticulars->query("SELECT branch_name,SUM(other_deduction) `other_deduction_bill` FROM other_deductions_bill odsb WHERE DATE(pays_date) BETWEEN DATE('$start_date') AND DATE('$end_date') $companyName2 $branchName4 GROUP BY branch_name");
            foreach($data_other_deduction_bill as $othb)
            {
                $data_branch[$othb['odsb']['branch_name']]['other_deduction_bill'] += ($othb['0']['other_deduction_bill']);
                $branch_master[] = $othb['odsb']['branch_name'];
            }
            
            $data_other_prov = $this->CollectionParticulars->query("SELECT pm.branch_name,SUM(pm.provision_balance) `Unprocess` FROM provision_master pm inner JOIN cost_master cm ON cm.cost_center=pm.cost_center $companyName4 $branchName3 GROUP BY branch_name");
            foreach($data_other_prov as $prov)
            {
                $data_branch[$prov['pm']['branch_name']]['Unprocess'] += ($prov['0']['Unprocess']*1.18);
                $branch_master[] = $prov['pm']['branch_name'];
            }
            
            $branch_master = array_unique($branch_master);
            sort($branch_master);
            $this->set('branch_master',$branch_master);
            $this->set('data_branch',$data_branch);
            
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

    public function report_collection_tracking()
    {
        if($this->request->is('POST'))
        {
            
            $data = $this->request->data['CollectionReports'];
            $month = $data['month'];
            $cost_center = $data['cost_center'];
            
           $entry_type = $data['entry_type']; 
            
            if($entry_type=='action_date')
            {
                $upd_arr = array();
                
                $action_date2 = $action_date = $data['action_date'];
                $remarks = addslashes($data['remarks']);
                $category = $data['category']; 

                $action_date2 = "'$action_date2'";
            
                $action_date_arr = explode('-',$action_date);
                $action_date_arr2[0] = $action_date_arr[2];
                $action_date_arr2[1] = $action_date_arr[1];
                $action_date_arr2[2] = $action_date_arr[0];

                $action_date = "'".implode('-',$action_date_arr2)."'";
                $remarks = "'$remarks'";
                $flag_add_action_date = '';
                
                if($category=='A')
                {
                    
                    $select = "SELECT ap.cost_center cost_center_id FROM `agreement_particulars` ap
INNER JOIN cost_master cm ON ap.cost_center = cm.id where ap.document_type='Agreement' and cm.cost_center = '$cost_center' and curdate() between ap.periodFrom and  ap.periodTo"; 
                    $ag_check = $this->InitialInvoice->query($select);
                    
                    if(empty($ag_check))
                    {
                        $select2 = "SELECT DATEDIFF(MAX(periodFrom),CURDATE()) periodFrom FROM `agreement_particulars` ap
INNER JOIN cost_master cm ON ap.cost_center = cm.id where ap.document_type='Agreement' and cm.cost_center = '$cost_center'"; 
                    $ag_check2 = $this->InitialInvoice->query($select2);
                        $periodFrom = $ag_check2['0']['0']['periodFrom'];
                        
                        if($periodFrom>60 && $periodFrom<91)
                        {
                            $column = 'col_61_90_after_lapsed';
                        }
                        else if($periodFrom>30 && $periodFrom<61)
                        {
                            $column = 'col_31_60_after_lapsed';
                        }
                        else if($periodFrom>15 && $periodFrom<31)
                        {
                            $column = 'col_16_30_after_lapsed';
                        }
                        else if($periodFrom>5 && $periodFrom<16)
                        {
                            $column = 'col_6_15_after_lapsed';
                        }
                        else if($periodFrom>=0 && $periodFrom<6)
                        {
                            $column = 'col_0_5_after_lapsed';
                        }
                        
                        $select3 = "SELECT $column FROM  `collection_tracking_matrix` WHERE Category='$category' limit 1"; 
                        $ag_check3 = $this->InitialInvoice->query($select3);
                        $days = $ag_check3['0']['collection_tracking_matrix'][$column];
                        //print_r($ag_check3); exit;
                        $select4 = "SELECT insert_act_agree_date FROM provision_master WHERE cost_center='$cost_center' AND `month` ='$month' 
AND IF(DATEDIFF(CURDATE(),insert_act_agree_date)>=$days,TRUE,FALSE) limit 1"; 
                        $ag_check4 = $this->InitialInvoice->query($select4);
                        
                        if(empty($ag_check4))
                        {
                            $entry_done = false;
                            $msg = "Entry Allready Done. You can Enter After $days";
                        }
                        else
                        {
                            $entry_done = true;
                        }
                    }
                    
                    $upd_arr['insert_act_agree_date'] ='now()';
                    $upd_arr['act_agree_date'] = $action_date;
                    $upd_arr['act_agree_remarks'] = $remarks;
                    $upd_arr['his_act_agree_date'] = "concat(if(his_act_agree_date is null,'',his_act_agree_date),',',$action_date2)";
                    $upd_arr['his_act_agree_remarks'] = "concat(if(his_act_agree_remarks is null,'',his_act_agree_remarks),',',$remarks)";
                }
                else if($category=='PO')
                {
                    $select = "SELECT cost_center cost_center_id FROM `po_number_particulars` ap
INNER JOIN cost_master cm ON ap.cost_center = cm.id where ap.document_type='Agreement' and cm.cost_center = '$cost_center' and curdate() between ap.periodFrom and  ap.periodTo"; 
                    $ag_check = $this->InitialInvoice->query($select);
                    
                    if(empty($ag_check))
                    {
                        $select2 = "SELECT DATEDIFF(MAX(periodFrom),CURDATE()) periodFrom FROM `po_number_particulars` ap
INNER JOIN cost_master cm ON ap.cost_center = cm.id where ap.document_type='Agreement' and cm.cost_center = '$cost_center'"; 
                    $ag_check2 = $this->InitialInvoice->query($select2);
                        $periodFrom = $ag_check2['0']['0']['periodFrom'];
                        
                        if($periodFrom>60 && $periodFrom<91)
                        {
                            $column = 'col_61_90_after_lapsed';
                        }
                        else if($periodFrom>30 && $periodFrom<61)
                        {
                            $column = 'col_31_60_after_lapsed';
                        }
                        else if($periodFrom>15 && $periodFrom<31)
                        {
                            $column = 'col_16_30_after_lapsed';
                        }
                        else if($periodFrom>5 && $periodFrom<16)
                        {
                            $column = 'col_6_15_after_lapsed';
                        }
                        else if($periodFrom>=0 && $periodFrom<6)
                        {
                            $column = 'col_0_5_after_lapsed';
                        }
                        
                        $select3 = "SELECT $column FROM  `collection_tracking_matrix` WHERE Category='$category' limit 1"; 
                        $ag_check3 = $this->InitialInvoice->query($select3);
                        $days = $ag_check3['0']['collection_tracking_matrix'][$column];
                        
                        $select4 = "SELECT insert_act_po_date FROM provision_master WHERE cost_center='$cost_center' AND `month` ='$month' 
AND IF(DATEDIFF(CURDATE(),insert_act_po_date)>=$days,TRUE,FALSE) limit 1"; 
                        $ag_check4 = $this->InitialInvoice->query($select4);
                        
                        if(empty($ag_check4))
                        {
                            $entry_done = false;
                            $msg = "Entry Allready Done. You can Enter After $days";
                        }
                        else
                        {
                            $entry_done = true;
                        }
                    }
                    else
                    {
                        $select2 = "SELECT DATEDIFF(CURDATE(),MAX(periodFrom)) periodFrom FROM `po_number_particulars` ap
INNER JOIN cost_master cm ON ap.cost_center = cm.id where ap.document_type='Agreement' and cm.cost_center = '$cost_center'"; 
                    $ag_check2 = $this->InitialInvoice->query($select2);
                        $periodFrom = $ag_check2['0']['0']['periodFrom'];
                        
                        if($periodFrom>=0 && $periodFrom<16)
                        {
                            $column = 'col_less_15_before_lapsed';
                        }
                        else if($periodFrom>30)
                        {
                            $column = 'col_less_30_before_lapsed';
                        }
                        $select3 = "SELECT $column FROM  `collection_tracking_matrix` WHERE Category='$category' limit 1"; 
                        $ag_check3 = $this->InitialInvoice->query($select3);
                        $days = $ag_check3['0']['collection_tracking_matrix'][$column];
                        
                        $select4 = "SELECT insert_act_po_date FROM provision_master WHERE cost_center='$cost_center' AND `month` ='$month' 
AND IF(DATEDIFF(CURDATE(),insert_act_po_date)>=$days,TRUE,FALSE) limit 1"; 
                        $ag_check4 = $this->InitialInvoice->query($select4);
                        
                        if(empty($ag_check4))
                        {
                            $entry_done = false;
                            $msg = "Entry Allready Done. You can Enter After $days";
                        }
                        else
                        {
                            $entry_done = true;
                        }
                    }
                    $upd_arr['insert_act_po_date'] ='now()';
                    $upd_arr['act_po_date'] = $action_date;
                    $upd_arr['act_po_remarks'] = $remarks;
                    $upd_arr['his_act_po_date'] = "concat(if(his_act_po_date is null,'',his_act_po_date),',',$action_date2)";
                    $upd_arr['his_act_po_remarks'] = "concat(if(his_act_po_remarks is null,'',his_act_po_remarks),',',$remarks)";
                }
                else if($category=='PTP')
                {
                    $upd_arr['insert_act_ptp_date'] ='now()';
                    $upd_arr['act_ptp_date'] = $action_date;
                    $upd_arr['act_ptp_remarks'] = $remarks;
                    $upd_arr['his_act_ptp_date'] = "concat(if(his_act_ptp_date is null,'',his_act_ptp_date),',',$action_date2)";
                    $upd_arr['his_act_ptp_remarks'] = "concat(if(his_act_ptp_remarks is null,'',his_act_ptp_remarks),',',$remarks)";
                }
                else if($category=='G')
                {
                    $upd_arr['insert_act_grn_date'] ='now()';
                    $upd_arr['act_grn_date'] = $action_date;
                    $upd_arr['act_grn_remarks'] = $remarks;
                    $upd_arr['his_act_grn_date'] = "concat(if(his_act_grn_date is null,'',his_act_grn_date),',',$action_date2)";
                    $upd_arr['his_act_grn_remarks'] = "concat(if(his_act_grn_remarks is null,'',his_act_grn_remarks),',',$remarks)";
                }
                else if($category=='R')
                {
                   $upd_arr['insert_act_receipt_date'] ='now()';
                   $upd_arr['act_receipt_date'] = $action_date; 
                   $upd_arr['act_receipt_remarks'] = $remarks;
                   $upd_arr['his_act_receipt_date'] = "concat(if(his_act_receipt_date is null,'',his_act_receipt_date),',',$action_date2)";
                   $upd_arr['his_act_receipt_remarks'] = "concat(if(his_act_receipt_remarks is null,'',his_act_receipt_remarks),',',$remarks)";
                }
                else if($category=='B')
                {
                   $upd_arr['insert_act_bill_ready_date'] ='now()';
                   $upd_arr['act_bill_ready_date'] = $action_date; 
                   $upd_arr['act_bill_ready_remarks'] = $remarks;
                   $upd_arr['his_act_bill_ready_date'] = "concat(if(his_act_bill_ready_date is null,'',his_act_bill_ready_date),',',$action_date2)";
                   $upd_arr['his_act_bill_ready_remarks'] = "concat(if(his_act_bill_ready_remarks is null,'',his_act_bill_ready_remarks),',',$remarks)";
                   
                   //print_r($cost_center); exit;
                   
                }
                
                if($this->Provision->updateAll($upd_arr,array('month'=>$month,'cost_center'=>$cost_center)))
                {
                    $this->Session->setFlash("Record Updated Successfully");
                }
                else
                {
                    $this->Session->setFlash("Record Not Updated");
                }
                $branch = $data['branchS'];
            $category = $data['categoryS'];
            $report_typeS = $data['report_typeS'];
            
            $this->set('branchS',$branch);
            $this->set('categoryS',$category);
            $this->set('report_typeS',$report_typeS);
            
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status` 
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  bill_pay_particulars bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"; 
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= $res['t1']['grnd'];
                    $prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                    $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    if(empty($ag_check) && ($category == 'Agreement Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'A';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_agree_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_agree_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_agree_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_agree_remarks'];
                       
                    }
                    if($res['0']['bill_status']=='PO Pending' && ($category == 'PO Pending' || $category == 'All'))
                    {
                       /*$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_po_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_po_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_po_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_po_remarks'];*/
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'P';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_bill_ready_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_bill_ready_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_bill_ready_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_bill_ready_remarks'];
                       
                    }
                    if($res['0']['bill_status']=='GRN Pending' && ($category == 'GRN Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'G';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_grn_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_grn_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_grn_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_grn_remarks'];
                       
                    }
                    if(($res['t1']['ReceiptStatus']=='0' || $res['t1']['ReceiptStatus']==0) && ($category == 'Receiving Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'R';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_receipt_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_receipt_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_receipt_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_receipt_remarks'];
                    }
                    if($res['0']['bill_status']=='submitted' && $res['t1']['ReceiptStatus']!='0' && $res['t1']['ReceiptStatus']!=0 && ($category == 'PTP Date Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PTP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_ptp_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_ptp_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_ptp_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_ptp_remarks'];
                    }
                    

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             
            $query_prov = "select * from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                if($category == 'Bill Ready' || $category == 'All')
                {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision_balance']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['act_bill_ready_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['act_bill_ready_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_act_bill_ready_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_act_bill_ready_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                }
             }
            
             //print_r($data_branch); exit;
             
             
             $branch_arr = array_unique($branch_arr);
             $cost_arr = array_unique($cost_arr);
             $month_arr = array_unique($month_arr);
             
             sort($branch_arr);
            
            $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
            $html =  '<table '.$class.'>';
            $html .= '<thead><tr>';
                $html .= '<th>Sr. No</th>';
                $html .= '<th>Cost Center</th>';
                $html .= '<th>Client</th>';
                $html .= '<th>Branch</th>';
                $html .= '<th>Invoice No</th>';
                $html .= '<th>Amount</th>';
                $html .= '<th>Category</th>';
                $html .= '<th>Month</th>';
                $html .= '<th>Action Date</th>';
                $html .= '<th>Remarks</th>';
                if($fetch_type!='export')
                {
                   $html .= '<th colspan="1">Action</th>';
                }  
             
            $html .= '</tr></thead>';
            $srNo = 1;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                            if(!empty($record[$mnt][$index]['Category']))
                            {
                                $html .= '<tr>';
                                $html .= '<td>'.$srNo++.'</td>';
                                $html .= '<td>'.$map_branch[$br][$cost_id].'</td>';
                                $html .= '<td>'.$record[$mnt][$index]['client'].'</td>';
                                $html .= '<td>'.$br.'</td>';
                                $html .= '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                $html .= '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                $html .= '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                $html .= '<td>'."'$mnt".'</td>';
                                if($fetch_type=='export')
                                {
                                    $html .= '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    $html .= '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //$html .= '<td></td>';
                                }
                                else
                                {
                                    if(empty($record[$mnt][$index]['action_date']))
                                    {
                                        $html .= '<td></td>';
                                    }
                                    else
                                    {
                                    $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                    $action_d1[0] = $action_d[2];
                                    $action_d1[1] = $action_d[1];
                                    $action_d1[2] = $action_d[0];
                                    
                                    $html .= '<td>'.implode('-',$action_d1).'</td>';
                                    }
                                    
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    
                                    $html .= '<td>'.$record[$mnt][$index]['remarks'].'</td>';
                                    $html .= '<td><a href="#" id="myBtn" onclick="get_add_action_date('."'{$map_branch[$br][$cost_id]}','$mnt','{$record[$mnt][$index]['Category']}'".')">Add Action Date</a</td>';
                                    //$html .= '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','{$record[$mnt][$index]['Category']}','$inv'".')">Add EPTP Date</a</td>';
                                }
                                $html .= '</tr>';
                            }
                        }
                    }
                }
            }
             $html .= '</table>';
             $this->set('htm',$html);
            }
            else
            {
                    $action_date2 = $action_date = $data['eptp_date'];
                    $remarks = addslashes($data['remarks']);
                    $action_date2 = "'$action_date2'";
                    $inv = $data['inv']; //exit;
                    
                    $action_date_arr = explode('-',$action_date);
                    $action_date_arr2[0] = $action_date_arr[2];
                    $action_date_arr2[1] = $action_date_arr[1];
                    $action_date_arr2[2] = $action_date_arr[0];

                    $action_date = "'".implode('-',$action_date_arr2)."'";
                    $remarks = "'$remarks'";
                
                    
                    $upd_arr = array();
                    $upd_arr['eptp_act_date'] = $action_date;
                    $upd_arr['eptp_act_remarks'] = $remarks;
                    $upd_arr['his_eptp_act_date'] = "concat(if(his_eptp_act_date is null,'',his_eptp_act_date),',',$action_date2)";
                    $upd_arr['his_eptp_act_remarks'] = "concat(if(his_eptp_act_remarks is null,'',his_eptp_act_remarks),',',$remarks)";
                    //print_r($inv); exit;
                    if($inv)
                    {
                        if($this->InitialInvoice->updateAll($upd_arr,array('bill_no'=>$inv,'cost_center'=>$cost_center)))
                        {
                            $this->Session->setFlash("Record Updated Successfully");
                        }
                        else
                        {
                            $this->Session->setFlash("Record Not Updated");
                        }
                    }
                    else
                    {
                        if($this->Provision->updateAll($upd_arr,array('month'=>$month,'cost_center'=>$cost_center)))
                        {
                            $this->Session->setFlash("Record Updated Successfully");
                        }
                        else
                        {
                            $this->Session->setFlash("Record Not Updated");
                        }
                    }
                $branch = $data['branchS'];
            $category = $data['categoryS'];
            $report_typeS = $data['report_typeS'];
            
            $this->set('branchS',$branch);
            //$this->set('categoryS',$category);
            $this->set('report_typeS',$report_typeS);
            
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t1.eptp_act_date,t1.eptp_act_remarks,t1.his_eptp_act_date,t1.his_eptp_act_remarks,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status` 
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  bill_pay_particulars bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)";
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= $res['t1']['grnd'];
                    
                    //$prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                    //$select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                              //  $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    
                    
                        $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];
                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             
            $query_prov = "select * from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                
             }

            $branch_arr = array_unique($branch_arr);
            $cost_arr = array_unique($cost_arr);
            $month_arr = array_unique($month_arr);
            sort($branch_arr); 
            
            $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             
             
             
            $htm = '<table '.$class.'>';
            $htm .= '<thead><tr>';
                $htm .= '<th>Sr. No</th>';
                $htm .= '<th>Cost Center</th>';
                $htm .= '<th>Client</th>';
                $htm .= '<th>Branch</th>';
                $htm .= '<th>Invoice No</th>';
                $htm .= '<th>Amount</th>';
                $htm .= '<th>Month</th>';
                if($fetch_type!='export')
                {
                   $htm .= '<th>Action</th>';
                }   
                   $htm .= '<th>EPTP Date</th>';
                $htm .= '<th>Remarks</th>';
                
            $htm .= '</tr></thead>';
            $srNo = 1;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                                $htm .= '<tr>';
                                $htm .= '<td>'.$srNo++.'</td>';
                                $htm .= '<td>'.$map_branch[$br][$cost_id].'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['client'].'</td>';
                                $htm .= '<td>'.$br.'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                //echo '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                $htm .= '<td>'."'$mnt".'</td>';
                                
                                if($fetch_type=='export')
                                {
                                    $htm .= '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    $htm .= '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //echo '<td></td>';
                                }
                                else
                                {
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    $htm .= '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','$inv'".')">Add EPTP Date</a</td>';
                                    if(empty($record[$mnt][$index]['action_date']))
                                    {
                                        $htm .= '<td></td>';
                                    }
                                    else
                                    {
                                        $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                        $action_d1[0] = $action_d[2];
                                        $action_d1[1] = $action_d[1];
                                        $action_d1[2] = $action_d[0];
                                    
                                        $htm .= '<td>'.implode('-',$action_d1).'</td>';
                                    }
                                    
                                $htm .= '<td>'.$record[$mnt][$index]['remarks'].'</td>';
                                }
                                $htm .= '</tr>';
                            
                        }
                    }
                }
            }
             $htm .= '</table>'; 
             $this->set('htm',$htm);
            }
             
        }
        
        $this->layout='home';
        $userid = $this->Session->read('userid');
        $branch_name = $this->Session->read('branch_name');
        $branch_name_d ='';
        if($this->Session->read('role')=='admin')
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"active='1'"));
            $branch_master = array('All'=>'All') + $branch_master;
        }
        else
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"branch_name ='$branch_name' and active='1'"));
            $branch_name_d=" and t1.branch_name='$branch_name'"; 
        }
                
        $query = "SELECT t1.id,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
    t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
    t1.invoiceDescription,t1.invoiceDate,t1.grn, 
    LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
    IF(bpp.status = 'part payment','part payment',
    IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
    IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

     ) `bill_status` 
    FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
    LEFT JOIN  bill_pay_particulars bpp ON 
     SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
     t1.branch_name = bpp.branch_name   
     WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name_d
     ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"; 
            $response_det=$this->InitialInvoice->query($query);
                        
                $sr = 1;
                foreach($response_det as $res)
                {
                     if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                     {
                        $prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                        $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                                    $ag_check = $this->InitialInvoice->query($select);

                        if(empty($ag_check))
                        {
                           $data_branch2['AP'] += 1;
                        }
                        if($res['0']['bill_status']=='PO Pending')
                        {
                           $data_branch2['POP'] += 1;  
                        }
                        if($res['0']['bill_status']=='GRN Pending')
                        {
                           $data_branch2['GP'] += 1;  
                        }
                        if(($res['t1']['ReceiptStatus']=='0' || $res['t1']['ReceiptStatus']==0))
                        {
                           $data_branch2['RP'] += 1;
                        }
                        if($res['0']['bill_status']=='submitted' && $res['t1']['ReceiptStatus']!='0' && $res['t1']['ReceiptStatus']!=0)
                        {
                           $data_branch2['PTP'] += 1;
                        }
                        $branch_arr2[] = $res['t2']['branch'];
                     }
                }

                $query_prov2 = "select * from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name_d"; 
                $response_det_prov2=$this->InitialInvoice->query($query_prov2);

                foreach($response_det_prov2 as $res)
                {
                    $data_branch2['BP'] += 1;
                    $branch_arr2[] = $res['t2']['branch'];
                }
                 $branch_arr2 = array_unique($branch_arr2);
                 //print_r($query); exit;
                 $this->set('data_branch2',$data_branch2);
                 $this->set('branch_arr2',$branch_arr2);
                 $this->set('branch_arr3',$branch_arr3);
        $this->set('branch_master',$branch_master);
        $this->set('category_master',$category_master);
        
    }
    
    public function get_coll_track()
    {
        if($this->request->is('POST'))
        {
            $branch = $this->request->data['branch'];
            $category = $this->request->data['category'];
            $report_type = $this->request->data['report_type'];
            $fetch_type = $this->request->data['fetch_type'];
        }
        else
        {
            $branch = $this->params->query['branch']; 
            $category = $this->params->query['category'];
            $report_type = $this->params->query['report_type'];
            $fetch_type = $this->request->query['fetch_type']; 
        }
        
        
        if($report_type=='Details')
        {
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status`,
 if(t2.CostCenterName is null,t2.process_name,t2.CostCenterName) ProcessName
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  bill_pay_particulars bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)"; 
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['ProcessName'] = $res['0']['ProcessName'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= $res['t1']['grnd'];
                    
                    $prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                    $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    if(empty($ag_check) && ($category == 'Agreement Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'A';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_agree_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_agree_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_agree_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_agree_remarks'];
                       
                    }
                    if($res['0']['bill_status']=='PO Pending' && ($category == 'PO Pending' || $category == 'All'))
                    {
                       /*$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_po_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_po_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_po_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_po_remarks'];*/
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'P';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_bill_ready_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_bill_ready_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_bill_ready_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_bill_ready_remarks'];
                    }
                    if($res['0']['bill_status']=='GRN Pending' && ($category == 'GRN Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'G';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_grn_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_grn_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_grn_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_grn_remarks'];
                       
                    }
                    if(($res['t1']['ReceiptStatus']=='0' || $res['t1']['ReceiptStatus']==0) && ($category == 'Receiving Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'R';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_receipt_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_receipt_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_receipt_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_receipt_remarks'];
                    }
                    if($res['0']['bill_status']=='submitted' && $res['t1']['ReceiptStatus']!='0' && $res['t1']['ReceiptStatus']!=0 && ($category == 'PTP Date Pending' || $category == 'All'))
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PTP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['act_ptp_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['act_ptp_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_act_ptp_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_act_ptp_remarks'];
                    }
                    

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             
            $query_prov = "select *,if(t2.CostCenterName is null,t2.process_name,t2.CostCenterName) ProcessName from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                if($category == 'Bill Ready' || $category == 'All')
                {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['ProcessName'] = $res['0']['ProcessName'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision_balance']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['act_bill_ready_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['act_bill_ready_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_act_bill_ready_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_act_bill_ready_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                }
             }
            
             //print_r($data_branch); exit;
             
             
             $branch_arr = array_unique($branch_arr);
             $cost_arr = array_unique($cost_arr);
             $month_arr = array_unique($month_arr);
             
             sort($branch_arr);
             
             
             if($fetch_type=='export')
             {
                $fileName = "collection_details";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             
             echo '<table '.$class.'>';
            echo '<thead><tr>';
                echo '<th>Sr. No</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Process</th>';
                echo '<th>Branch</th>';
                echo '<th>Invoice No</th>';
                echo '<th>Amount</th>';
                echo '<th>Category</th>';
                echo '<th>Month</th>';
                echo '<th>Action Date</th>';
                
                echo '<th>Remarks</th>';
                if($fetch_type!='export')
                {
                   echo '<th colspan="1">Action</th>';
                   
                }
             
            echo '</tr></thead>';
            $srNo = 1; $total = 0;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                            if(!empty($record[$mnt][$index]['Category']))
                            {
                                $braname    =   $br;
                                $braname1    =   strlen($braname) > 10 ? substr($braname,0,10)."..." : $braname;
                            
                            $rem_eptp    =   $record[$mnt][$index]['remarks'];
                            $rem_eptp1    =   strlen($rem_eptp) > 10 ? substr($rem_eptp,0,10)."..." : $rem_eptp;
                            $proname    =   $record[$mnt][$index]['ProcessName'];
                            $proname1    =   strlen($proname) > 10 ? substr($proname,0,10)."..." : $proname;    
                                
                                echo '<tr>';
                                echo '<td>'.$srNo++.'</td>';
                                echo '<td>'.$map_branch[$br][$cost_id].'</td>';
                                
                                if($fetch_type=='export')
                                {
                                    echo '<td>'.$record[$mnt][$index]['ProcessName'].'</td>';
                                    echo '<td>'.$br.'</td>';
                                }
                                else
                                {
                                    echo '<td>'.$proname1.'</td>';
                                    echo '<td>'.$braname1.'</td>';
                                }
                                echo '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                echo '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                $total +=$record[$mnt][$index]['amt'];
                                echo '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                echo '<td>'."'$mnt".'</td>';
                                if($fetch_type=='export')
                                {
                                    echo '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    echo '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //echo '<td></td>';
                                }
                                else
                                {
                                    if(empty($record[$mnt][$index]['action_date']))
                                    {
                                        echo '<td></td>';
                                    }
                                    else
                                    {
                                    $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                    $action_d1[0] = $action_d[2];
                                    $action_d1[1] = $action_d[1];
                                    $action_d1[2] = $action_d[0];
                                    
                                    echo '<td>'.implode('-',$action_d1).'</td>';
                                    }
                                    
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    
                                    echo '<td>'.$rem_eptp1.'</td>';
                                echo '<td><a href="#" id="myBtn" onclick="get_add_action_date('."'{$map_branch[$br][$cost_id]}','$mnt','{$record[$mnt][$index]['Category']}'".')">Action Date</a</td>';
                                //echo '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','{$record[$mnt][$index]['Category']}','$inv'".')">Add EPTP Date</a</td>';
                                }
                                echo '</tr>';
                            }
                        }
                    }
                }
            }
            
            echo '<tr>';
            echo '<th colspan="5">Total</th>';
            echo '<th>'.$total.'</th>';
            echo '<th colspan="5"></th>';
            echo '</tr>';
             echo '</table>';
             exit;
        }
        else if($report_type=='Summary')
        {
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and ti.branch_name='$branch'"; 
            }
            
            $query_invoice = "SELECT ti.id,cm.id,ti.branch_name,ti.cost_center,ti.month,cm.company_name,cm.cost_center,
                ti.finance_year,ti.bill_no,ti.grnd,cm.branch,LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) lst_day,
                LAST_DAY(STR_TO_DATE(CONCAT('10-',ti.`month`),'%d-%b-%Y')) mnt,
                DATE_FORMAT(ADDDATE(CURDATE(),INTERVAL 0 MONTH),'%b-%Y')mnt0,
                DATE_FORMAT(ADDDATE(CURDATE(),INTERVAL 1 MONTH),'%b-%Y')mnt1,
DATE_FORMAT(ADDDATE(CURDATE(),INTERVAL 2 MONTH),'%b-%Y')mnt2,
DATE_FORMAT(ADDDATE(CURDATE(),INTERVAL 3 MONTH),'%b-%Y')mnt3,
IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),'PO Pending', 
IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_grn IS NULL),'GRN Pending','submitted'))
 `bill_status`
FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
INNER JOIN branch_master bm ON ti.branch_name = bm.branch_name and bm.active='1'
WHERE   `status`='0' and ti.bill_no!='' and ti.grnd!=0 and ti.grnd!=1 and ti.CurrentInvoiceType!='Write-Off' and ti.CurrentInvoiceType!='Dispute'    $branch_name
  group by ti.id ";
            $data_invoice = $this->InitialInvoice->query($query_invoice);
            //print_r($data_invoice); exit;
            foreach($data_invoice as $inv)
            {
                $opening = 0; $current_month = 0;  $collection = 0;
                $mnt1 = $inv['0']['mnt0'];
                $mnt2 = $inv['0']['mnt1'];
                $mnt3 = $inv['0']['mnt2'];
                $branch_arr[] = $inv['cm']['branch'];
                $mnt_cnt1 = 0;
                $mnt_cnt2 = 0;
                $mnt_cnt3 = 0;
                $po_pending = 0;
                $grn_pending = 0;
                $ptp_pending = 0;
                $ag_pending = 0;
                $lst_day = $inv['0']['lst_day'];
                
                $gst_pending=0;
                //echo $inv['0']['lst_day']; exit; 
                if(strtotime($inv['0']['mnt'])<=strtotime($lst_day))
                {
                    $opening = $inv['ti']['grnd'];
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and bpp.status='paid' limit 1";
                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    
                    //print_r($data_paid_status); exit;

                    if(strtotime($data_paid_status['0']['0']['pay_dates'])>strtotime($lst_day))
                    {
                        $qr = "SELECT status,date(pay_dates) pay_dates,sum(net_amount)net_amount,sum(tds_ded)tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and date(pay_dates)>'$lst_day' limit 1";
                        $data_paid_status = $this->CollectionParticulars->query($qr);
                        
                       $collection +=  ($data_paid_status['0']['0']['net_amount']+$data_paid_status['0']['0']['tds_ded']);
                        $qr = "SELECT status,date(pay_dates) pay_dates,sum(net_amount)net_amount,sum(tds_ded)tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and date(pay_dates)<='$lst_day' limit 1";

                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    $opening -= ($data_paid_status['0']['0']['net_amount']+$data_paid_status['0']['0']['tds_ded']); 
                    
                    }
                    else if($data_paid_status['0']['bpp']['status']=='paid')
                    {
                        $opening = 0; 
                    }
                    else
                    {
                        
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                        
                       

                        if(!empty($data_part_status))
                        {
                             //print_r($data_part_status); exit;
                            $gst_pending = $inv['ti']['grnd'];
                            foreach($data_part_status as $bpp)
                            {
                                if(strtotime($bpp['0']['pay_dates'])>strtotime($lst_day))
                                {
                                   $collection +=  ($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']);
                                   
                                   
                                }
                                else
                                {
                                    $opening -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']); 
                                }
                                
                                $gst_pending -= ($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']);
                                
                            }
                            
                            //echo "$collection-$opening";
                            //exit;
                        }
                        else
                        {
                            
                            //////////////////// For PTP Date /////////////////
                            $data_ptp = $this->InitialInvoice->query("Select *,DATE_FORMAT(ExpDatesPayment,'%b-%Y') mnt from receipt_master rm "
                                    . "where Invoiceid='{$inv['ti']['id']}' limit 1");
                            if(!empty($data_ptp))
                            {
                                if($data_ptp['0']['0']['mnt']==$mnt1)
                                {
                                    $mnt_cnt1 = $opening;
                                }
                                else if($data_ptp['0']['0']['mnt']==$mnt2)
                                {
                                    $mnt_cnt2 = $opening;
                                }
                                else if($data_ptp['0']['0']['mnt']==$mnt3)
                                {
                                    $mnt_cnt3 = $opening;
                                }
                                else
                                {
                                    $ptp_pending = $opening;
                                }
                            }
                            else
                            {
                                $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$inv['0']['mnt']}' between periodTo and periodFrom and cost_center='{$inv['cm']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                                if(empty($ag_check))
                                {
                                    $ag_pending += $opening; 
                                    
                                }
                                else if($inv['0']['bill_status']=='PO Pending')
                                {
                                   $po_pending = $opening; 
                                }
                                else if($inv['0']['bill_status']=='GRN Pending')
                                {
                                   $grn_pending = $opening; 
                                }
                                
                                else
                                {
                                    $ptp_pending = $opening;
                                }
                            }   
                        }
                    }

                }
                else
                {
                    $current_month = $inv['ti']['grnd'];
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and bpp.status='paid' limit 1";
                $data_paid_status = $this->CollectionParticulars->query($qr);


                    //echo $data_paid_status['0']['0']['pay_dates']; exit;
                    if(!empty($data_paid_status) && strtotime($data_paid_status['0']['0']['pay_dates'])>strtotime($lst_day))
                    {
                        $qr = "SELECT status,date(pay_dates) pay_dates,sum(net_amount)net_amount,sum(tds_ded)tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and date(pay_dates)>'$lst_day' limit 1";
                        $data_paid_status = $this->CollectionParticulars->query($qr);
                        
                       $collection +=  ($data_paid_status['0']['0']['net_amount']+$data_paid_status['0']['0']['tds_ded']);
                        $qr = "SELECT status,date(pay_dates) pay_dates,sum(net_amount)net_amount,sum(tds_ded)tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and date(pay_dates)<='$lst_day' limit 1";

                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    $current_month -= ($data_paid_status['0']['0']['net_amount']+$data_paid_status['0']['0']['tds_ded']); 
                    }
                    else if($data_paid_status['0']['bpp']['status']=='paid')
                    {
                        $current_month = 0;
                    }
                    else
                    {
                        $cnt +=$inv['ti']['grnd']; 
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                        

                        

                        if(!empty($data_part_status))
                        {
                            foreach($data_part_status as $bpp)
                            {
                                if(strtotime($bpp['0']['pay_dates'])>strtotime($lst_day))
                                {
                                   $collection +=  ($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']);
                                }
                                else
                                {
                                    $current_month -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']);
                                }
                            }
                        }
                        else
                        {
                           // print_r($current_month); exit;
                            //////////////////// For PTP Date /////////////////
                            $data_ptp = $this->InitialInvoice->query("Select *,DATE_FORMAT(ExpDatesPayment,'%b-%Y') mnt from receipt_master rm "
                                    . "where Invoiceid='{$inv['ti']['id']}' limit 1");
                            if(!empty($data_ptp))
                            {
                                if($data_ptp['0']['0']['mnt']==$mnt1)
                                {
                                    $mnt_cnt1 = $current_month;
                                }
                                else if($data_ptp['0']['0']['mnt']==$mnt2)
                                {
                                    $mnt_cnt2 = $current_month;
                                }
                                else if($data_ptp['0']['0']['mnt']==$mnt3)
                                {
                                    $mnt_cnt3 = $current_month;
                                }
                                else
                                {
                                    $ptp_pending = $current_month;
                                }
                            }
                            else
                            {
                            
                                
                                
                                $ag_check = $this->InitialInvoice->query("SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$inv['0']['mnt']}' between periodTo and periodFrom and cost_center='{$inv['cm']['id']}'");
                                if(empty($ag_check))
                                {
                                    $ag_pending = $current_month; 
                                }
                                else if($inv['0']['bill_status']=='PO Pending')
                                {
                                   $po_pending = $current_month; 
                                }
                                else if($inv['0']['bill_status']=='GRN Pending')
                                {
                                   $grn_pending = $current_month; 
                                }
                                
                                else
                                {
                                    $ptp_pending = $current_month;
                                }
                            }
                        }
                    }

                }
                
                $data_branch[$inv['ti']['branch_name']]['open'] +=  $opening;
                $data_branch[$inv['ti']['branch_name']]['current_month'] +=  $current_month;
                $data_branch[$inv['ti']['branch_name']]['collection'] +=  $collection;
                $data_branch[$inv['ti']['branch_name']][$mnt1] +=  $mnt_cnt1;
                $data_branch[$inv['ti']['branch_name']][$mnt2] +=  $mnt_cnt2;
                $data_branch[$inv['ti']['branch_name']][$mnt3] +=  $mnt_cnt3;
                $data_branch[$inv['ti']['branch_name']]['po_pen'] +=  $po_pending;
                $data_branch[$inv['ti']['branch_name']]['grn_pen'] +=  $grn_pending;
                $data_branch[$inv['ti']['branch_name']]['ptp_pen'] +=  $ptp_pending;
                $data_branch[$inv['ti']['branch_name']]['ag_pen'] +=  $ag_pending;
                $data_branch[$inv['ti']['branch_name']]['gst_pending'] +=  $gst_pending;
                $data_branch[$inv['ti']['branch_name']]['Remarks'] .= $remarks ;
                
                
            }
            
            $query_prov = "select *,LAST_DAY(STR_TO_DATE(CONCAT('10-',ti.`month`),'%d-%b-%Y')) mnt,LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)) lst_day from provision_master ti "
                    . " inner join cost_master t2 on ti.cost_center = t2.cost_center "
                    . " inner join branch_master bm on ti.branch_name=bm.branch_name and bm.active='1' "
                    . " where ti.provision_balance!=0  $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);

            foreach($response_det_prov as $res)
            {
                
                $ag_check = $this->InitialInvoice->query("SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'");
                if(empty($ag_check) && strtotime($res['0']['mnt'])<=strtotime($res['0']['lst_day']))
                {
                    $data_branch[$res['t2']['branch']]['ag_pen'] += ($res['ti']['provision_balance']*1.18); 
                }
                
                
                if(strtotime($res['0']['mnt'])<=strtotime($res['0']['lst_day']))
                {
                    $data_branch[$res['t2']['branch']]['open'] +=  ($res['ti']['provision_balance']*1.18); 
                }
//                else if($res['t2']['grn']=='Yes')
//                {
//                    $data_branch[$res['t2']['branch']]['grn_pen'] +=  ($res['ti']['provision_balance']*1.18); 
//                }
                else
//                if(1==1)
                {
                    //$data_branch[$res['t2']['branch']]['current_month'] += ($res['ti']['provision_balance']*1.18); 
                }
                $branch_arr[] = $res['t2']['branch'];
            }
            
            //print_r($data_branch); exit;
            
            if($fetch_type=='export')
             {
                $fileName = "collection_summary";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
        
             $branch_arr = array_unique($branch_arr);
             sort($branch_arr);
             
             echo '<table '.$class.'>';
            echo '<thead style="text-align:center;text-valign:top"><tr>';
                echo '<th rowspan="2" id="brnch-align">Branches</th>';
                echo '<th rowspan="2">Opening Outstanding</th>';
                echo '<th rowspan="2">Current Month Bill Add</th>';
                echo '<th rowspan="2">Collection</th>';
                echo '<th rowspan="2">Net O/s - Till</th>';
                echo '<th colspan="3">PTP Month</th>';
                echo '<th rowspan="2">WIP(Work in process)</th>';
                echo '<th rowspan="2">Agreement Pending</th>';
                echo '<th rowspan="2">PO Pending</th>';
                echo '<th rowspan="2">GRN Pending</th>';
                echo '<th rowspan="2">PTP Pending</th>';
                
                echo '<th rowspan="2">GST Pending</th>';
                
                echo '<th rowspan="2">Bill Ready</th>';
                //echo '<th rowspan="2">Bill Ready</th>';
              echo '</tr>';
              
                echo '<tr>';
                echo '<th>'.$mnt1.'</th>';
                echo '<th>'.$mnt2.'</th>';
                echo '<th>'.$mnt3.'</th>';
                echo '</tr></thead>';
             
            foreach($branch_arr as $br)
            {
                echo '<tr>';
                echo '<td style="text-align:center">'.$br.'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['open']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['current_month']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['collection']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round(($data_branch[$br]['open']+$data_branch[$br]['current_month']-$data_branch[$br]['collection'])/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br][$mnt1]/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br][$mnt2]/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br][$mnt3]/100000,2).'</td>';
                echo '<td style="text-align:center">'.round(($data_branch[$br]['ag_pen']+$data_branch[$br]['po_pen']+$data_branch[$br]['grn_pen']+$data_branch[$br]['ptp_pen']+$data_branch[$br]['to_be_billed']-$data_branch[$br]['to_be_billed'])/100000,2).'</td>';
                
                echo '<td style="text-align:center">'.round($data_branch[$br]['ag_pen']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['po_pen']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['grn_pen']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round($data_branch[$br]['ptp_pen']/100000,2).'</td>';
                echo '<td style="text-align:center">'.round(($data_branch[$br]['gst_pending'])/100000,2).'</td>';
                //echo '<td style="text-align:center">'.round(($data_branch[$br]['to_be_billed'])/100000,2).'</td>';
                echo '<td style="text-align:center">'.round((($data_branch[$br]['open']+$data_branch[$br]['current_month']-$data_branch[$br]['gst_pending']-$data_branch[$br]['collection'])-($data_branch[$br][$mnt1]+$data_branch[$br][$mnt2]+$data_branch[$br][$mnt3])-($data_branch[$br]['ag_pen']+$data_branch[$br]['po_pen']+$data_branch[$br]['grn_pen']+$data_branch[$br]['ptp_pen']))/100000,2).'</td>';
                //echo '<td style="text-align:center">'.$data_branch[$br]['Remarks'].'</td>';
                echo '</tr>';
                
                $open_tot += round($data_branch[$br]['open']/100000,2);
                $current_mnt_tot += round($data_branch[$br]['current_month']/100000,2);
                $collection_tot += round($data_branch[$br]['collection']/100000,2);
                $mnt1_tot += round($data_branch[$br][$mnt1]/100000,2);
                $mnt2_tot += round($data_branch[$br][$mnt2]/100000,2);
                $mnt3_tot += round($data_branch[$br][$mnt3]/100000,2);
                $ag_pen_tot += round($data_branch[$br]['ag_pen']/100000,2);
                $po_pen_tot += round($data_branch[$br]['po_pen']/100000,2);
                $grn_pen_tot += round($data_branch[$br]['grn_pen']/100000,2);
                $ptp_pen_tot += round($data_branch[$br]['ptp_pen']/100000,2);
                //$to_be_billed_tot += round($data_branch[$br]['to_be_billed']/100000,2);
                $gst_pending +=round(($data_branch[$br]['gst_pending'])/100000,2);
                $to_be_billed_tot += round((($data_branch[$br]['open']+$data_branch[$br]['to_be_billed']+$data_branch[$br]['current_month']-$data_branch[$br]['gst_pending']-$data_branch[$br]['collection'])-($data_branch[$br][$mnt1]+$data_branch[$br][$mnt2]+$data_branch[$br][$mnt3])-($data_branch[$br]['ag_pen']+$data_branch[$br]['po_pen']+$data_branch[$br]['grn_pen']+$data_branch[$br]['ptp_pen']))/100000,2);
                
            }
            echo '<tr>';
                echo '<td style="text-align:center">Total</td>';
                echo '<td style="text-align:center">'.$open_tot.'</td>';
                echo '<td style="text-align:center">'.$current_mnt_tot.'</td>';
                echo '<td style="text-align:center">'.$collection_tot.'</td>';
                echo '<td style="text-align:center">'.($open_tot+$current_mnt_tot-$collection_tot).'</td>';
                echo '<td style="text-align:center">'.$mnt1_tot.'</td>';
                echo '<td style="text-align:center">'.$mnt2_tot.'</td>';
                echo '<td style="text-align:center">'.$mnt3_tot.'</td>';
                echo '<td style="text-align:center">'.($ag_pen_tot+$po_pen_tot+$grn_pen_tot+$ptp_pen_tot).'</td>';
                
                echo '<td style="text-align:center">'.$ag_pen_tot.'</td>';
                echo '<td style="text-align:center">'.$po_pen_tot.'</td>';
                echo '<td style="text-align:center">'.$grn_pen_tot.'</td>';
                echo '<td style="text-align:center">'.$ptp_pen_tot.'</td>';
                
                echo '<td style="text-align:center">'.$gst_pending."</td>";
                echo '<td style="text-align:center">'.$to_be_billed_tot.'</td>';
                echo '</tr></table>';
            exit;
        }
        
        else if($report_type=='NewSummary')
        {
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and ti.branch_name='$branch'"; 
            }
            
            $query_invoice = "SELECT ti.id,cm.id,ti.branch_name,ti.cost_center,ti.month,cm.company_name,cm.cost_center,
                ti.finance_year,ti.bill_no,ti.grnd,cm.branch,
IF(cm.po_required='Yes' AND (ti.approve_po = '' OR ti.approve_po IS NULL),'PO Pending', 
IF(cm.grn='Yes' AND (ti.approve_grn = '' OR ti.approve_grn IS NULL),'GRN Pending','submitted'))
 `bill_status`,
LAST_DAY(STR_TO_DATE(CONCAT('10-',ti.`month`),'%d-%b-%Y')) mnt,
if(cm.CostCenterName is null,cm.process_name,cm.CostCenterName) ProcessName
FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
INNER JOIN branch_master bm ON ti.branch_name = bm.branch_name and bm.active='1'
WHERE   `status`='0' and ti.bill_no!='' and ti.grnd!=0 and ti.grnd!=1 and ti.CurrentInvoiceType!='Write-Off' and ti.CurrentInvoiceType!='Dispute'    $branch_name
  group by ti.id ";
            $data_invoice = $this->InitialInvoice->query($query_invoice);
            //print_r($data_invoice); exit;
            $index_for_data = 0;
            foreach($data_invoice as $inv)
            {
                $branch_arr[] = $inv['cm']['branch'];
                $branch_cost_arr[$inv['cm']['branch']][] = $inv['cm']['cost_center'];
                $branch_month_arr[$inv['cm']['cost_center']][] = $inv['ti']['month'];
                
                
                $po_pending = '';
                $grn_pending = '';
                $ptp_pending = '';
                $ag_pending = '';
                $gst_pending='';
                $bill_ready = '';
                $opening = $inv['ti']['grnd'];
               
                
                
                
                
                
                $action_date = array();
                $remarks = array();
                
                    $qr = "SELECT status,date(pay_dates) pay_dates,net_amount,tds_ded FROM bill_pay_particulars bpp "
                            . "WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
AND bpp.company_name= '{$inv['cm']['company_name']}' 
AND  bpp.branch_name = '{$inv['cm']['branch']}' and bpp.status='paid' limit 1";
                    $data_paid_status = $this->CollectionParticulars->query($qr);
                    if($data_paid_status['0']['bpp']['status']=='paid')
                    {
                        
                    }
                    else
                    {
                        $data_part_status = $this->CollectionParticulars->query("SELECT status,date(pay_dates)pay_dates,net_amount,tds_ded FROM "
                                . "bill_pay_particulars bpp WHERE bpp.bill_no =SUBSTRING_INDEX('{$inv['ti']['bill_no']}','/','1') 
                                AND  bpp.financial_year='{$inv['ti']['finance_year']}' 
                                AND bpp.company_name= '{$inv['cm']['company_name']}' 
                                AND  bpp.branch_name = '{$inv['cm']['branch']}'");
                                
                                
                        if(!empty($data_part_status))
                        {
                            foreach($data_part_status as $bpp)
                            {
                                $opening -=($bpp['bpp']['net_amount']+$bpp['bpp']['tds_ded']);
                            }
                            if($opening>1)
                            {
                                $gst_pending = 'Part';
                            }
                        }
                        else
                        {
                                $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$inv['0']['mnt']}' between periodTo and periodFrom and cost_center='{$inv['cm']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                                if(empty($ag_check))
                                {
                                    $ag_pending = 'A';
                                    $action_date_select = "SELECT date_format(act_agree_date,'%d-%b-%Y') act_agree_date,act_agree_remarks FROM provision_master pm WHERE 
                                    branch_name='{$inv['ti']['branch_name']}' AND finance_year='{$inv['ti']['finance_year']}' and month='{$inv['ti']['month']}'
                                    AND cost_center='{$inv['cm']['cost_center']}' LIMIT 1";
                                    $action_date_rsc = $this->Provision->query($action_date_select);
                                    $action_date[] = $action_date_rsc['0']['0']['act_agree_date'];
                                    $remarks[] = $action_date_rsc['0']['pm']['act_agree_remarks'];
                                }
                                else if($inv['0']['bill_status']=='PO Pending')
                                {
                                   $po_pending = 'PO';
                                   $action_date_select = "SELECT date_format(act_po_date,'%d-%b-%Y') act_po_date,act_po_remarks FROM provision_master pm WHERE 
                                    branch_name='{$inv['ti']['branch_name']}' AND finance_year='{$inv['ti']['finance_year']}' and month='{$inv['ti']['month']}'
                                    AND cost_center='{$inv['cm']['cost_center']}' LIMIT 1";
                                    $action_date_rsc = $this->Provision->query($action_date_select);
                                    $action_date[] = $action_date_rsc['0']['0']['act_po_date'];
                                    $remarks[] = $action_date_rsc['0']['pm']['act_po_remarks'];
                                }
                                else if($inv['0']['bill_status']=='GRN Pending')
                                {
                                   $grn_pending = 'G';
                                   $action_date_select = "SELECT date_format(act_grn_date,'%d-%b-%Y') act_grn_date,act_grn_remarks FROM provision_master pm WHERE 
                                    branch_name='{$inv['ti']['branch_name']}' AND finance_year='{$inv['ti']['finance_year']}' and month='{$inv['ti']['month']}'
                                    AND cost_center='{$inv['cm']['cost_center']}' LIMIT 1";
                                    $action_date_rsc = $this->Provision->query($action_date_select);
                                    $action_date[] = $action_date_rsc['0']['0']['act_grn_date'];
                                    $remarks[] = $action_date_rsc['0']['pm']['act_grn_remarks'];
                                }
                                else
                                {
                                    $data_ptp = $this->InitialInvoice->query("Select *,DATE_FORMAT(ExpDatesPayment,'%b-%Y') mnt from receipt_master rm "
                                        . "where Invoiceid='{$inv['ti']['id']}' limit 1");
                                    if(!empty($data_ptp))    
                                    {
                                        $ptp_pending = 'P';
                                        $action_date_select = "SELECT date_format(act_ptp_date,'%d-%b-%Y') act_ptp_date,act_ptp_remarks FROM provision_master WHERE 
                                        branch_name='{$inv['ti']['branch_name']}' AND finance_year='{$inv['ti']['finance_year']}' and month='{$inv['ti']['month']}'
                                        AND cost_center='{$inv['ti']['cost_center']}' LIMIT 1";
                                        $action_date_rsc = $this->Provision->query($action_date_select);
                                        $action_date[] = $action_date_rsc['0']['0']['act_ptp_date'];
                                        $remarks[] = $action_date_rsc['0']['pm']['act_ptp_remarks'];
                                    }
                                    else
                                    {
                                        $bill_ready = 'B';
                                        $action_date_select = "SELECT date_format(act_bill_ready_date,'%d-%b-%Y')act_bill_ready_date,act_bill_ready_remarks FROM provision_master pm WHERE 
                                        branch_name='{$inv['ti']['branch_name']}' AND finance_year='{$inv['ti']['finance_year']}' and month='{$inv['ti']['month']}'
                                        AND cost_center='{$inv['cm']['cost_center']}' LIMIT 1";
                                        $action_date_rsc = $this->Provision->query($action_date_select);
                                        $action_date[] = $action_date_rsc['0']['0']['act_bill_ready_date'];
                                        $remarks[] = $action_date_rsc['0']['pm']['act_bill_ready_remarks'];
                                    }
                                }     
                        }
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['process']=  $inv['0']['ProcessName'];
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['amount']=  $opening;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['bill_no'] =  $inv['ti']['bill_no'];
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['action_date'] = implode(",",$action_date) ;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['remarks'] = implode(",",$remarks) ;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $po_pending;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $grn_pending;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $ptp_pending;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $ag_pending;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $bill_ready;
                        $data_branch[$inv['ti']['branch_name']][$inv['ti']['cost_center']][$inv['ti']['month']][$index_for_data]['cat'] .=  $gst_pending;
                    
                        $index_for_data++;
                    }
            }
           
            $query_prov = "select *,LAST_DAY(STR_TO_DATE(CONCAT('10-',ti.`month`),'%d-%b-%Y')) mnt,"
                    . "date_format(act_agree_date,'%d-%b-%Y'),"
                    . " if(t2.CostCenterName is null,t2.process_name,t2.CostCenterName) ProcessName,act_agree_date from provision_master ti "
                    . " inner join cost_master t2 on ti.cost_center = t2.cost_center "
                    . " inner join branch_master bm on ti.branch_name=bm.branch_name and bm.active='1' "
                    . " where ti.provision_balance!=0   $branch_name";  
            $response_det_prov=$this->InitialInvoice->query($query_prov);

            foreach($response_det_prov as $res)
            {
                $ag_check = $this->InitialInvoice->query("SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'");
                if(empty($ag_check))
                {
                    $proname    =   $res['0']['ProcessName'];
                            $proname1    =   strlen($proname) > 10 ? substr($proname,0,10)."..." : $proname;
                    $data_branch[$res['ti']['branch_name']][$res['ti']['cost_center']][$res['ti']['month']][$index_for_data]['process'] = $proname1; 
                    $data_branch[$res['ti']['branch_name']][$res['ti']['cost_center']][$res['ti']['month']][$index_for_data]['cat'] = 'A'; 
                    $data_branch[$res['ti']['branch_name']][$res['ti']['cost_center']][$res['ti']['month']][$index_for_data]['amount'] = $res['ti']['provision_balance'];
                    $data_branch[$res['ti']['branch_name']][$res['ti']['cost_center']][$res['ti']['month']][$index_for_data]['action_date'] = $res['0']['act_agree_date'];
                    $data_branch[$res['ti']['branch_name']][$res['ti']['cost_center']][$res['ti']['month']][$index_for_data]['remarks'] = $res['ti']['act_agree_remarks'];
                }
                
                $branch_arr[] = $res['t2']['branch'];
                $branch_cost_arr[$res['t2']['branch']][] = $res['t2']['cost_center'];
                $branch_month_arr[$res['t2']['cost_center']][] = $res['ti']['month'];
                $index_for_data++;
                
            }
            
            //print_r($branch_arr); exit;
            
            if($fetch_type=='export')
             {
                $fileName = "collection_tracking_summary2";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             $branch_arr = array_unique($branch_arr);
             sort($branch_arr);
             
                echo '<table '.$class.'>';
                echo '<thead style="text-align:center;text-valign:top"><tr>';
                echo '<th>Branches</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Process</th>';
                echo '<th>Months</th>';
                echo '<th>Invoice No</th>';
                echo '<th>Amount</th>';
                echo '<th>Category Pending</th>';
                echo '<th>Action Date</th>';
                echo '<th>Remarks</th>';
                echo '</tr></thead>';
             
                $total =0;
            foreach($branch_arr as $br)
            {
                $cost_arr = $branch_cost_arr[$br];
                $cost_arr = array_unique($cost_arr);
                foreach($cost_arr as $cost)
                {
                    $month_arr = $branch_month_arr[$cost];
                    $month_arr = array_unique($month_arr);
                    foreach($month_arr as $mnt)
                    {
                        $iterator = $data_branch[$br][$cost][$mnt];
                        foreach($iterator as $itr)
                        {
                            $braname    =   $br;
                            $braname1    =   strlen($braname) > 10 ? substr($braname,0,10)."..." : $braname;
                            
                            $rem_eptp    =   $itr['remarks'];
                            $rem_eptp1    =   strlen($rem_eptp) > 10 ? substr($rem_eptp,0,10)."..." : $rem_eptp;
                            
                            $proname    =   $itr['process'];
                            $proname1    =   strlen($proname) > 10 ? substr($proname,0,10)."..." : $proname;
                            
                            echo '<tr>';
                            if($fetch_type=='export')
                            {
                            echo '<td style="text-align:center">'.$br.'</td>';
                            echo '<td style="text-align:center">'.$cost.'</td>';
                            echo '<td style="text-align:center">'.$itr['process'].'</td>';
                            }
                            else
                            {
                                echo '<td style="text-align:center">'.$braname1.'</td>';
                                echo '<td style="text-align:center">'.$cost.'</td>';
                                echo '<td style="text-align:center">'.$proname1.'</td>';
                            }
                            
                            
                            echo '<td style="text-align:center">'.$mnt.'</td>';
                            echo '<td style="text-align:center">'.$itr['bill_no'].'</td>';
                            echo '<td style="text-align:center">'.$itr['amount'].'</td>';
                            $total += $itr['amount'];
                            echo '<td style="text-align:center">'.$itr['cat'].'</td>';
                            echo '<td style="text-align:center">'.$itr['action_date'].'</td>';
                            if($fetch_type=='export')
                            {
                            echo '<td style="text-align:center">'.$itr['remarks'].'</td>';
                            }
                            else
                            {
                                echo '<td style="text-align:center">'.$rem_eptp1.'</td>';
                            }
                            echo '</tr>';
                        }
                    }
                }
            }
            
            echo '<tr>';
                echo '<th colspan="5">Total</th>';
                echo '<td>';
                    echo $total;
                echo '</td>';
                echo '<td colspan="3"></td>';
            echo '</tr>';
            echo '</table>';
            exit;
        }
        
        else if($report_type=='EPTP')
        {
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t1.eptp_act_date,t1.eptp_act_remarks,t1.his_eptp_act_date,t1.his_eptp_act_remarks,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status`, if(t2.CostCenterName is null,t2.process_name,t2.CostCenterName) ProcessName 
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  (SELECT bill_no,company_name,branch_name,financial_year,pay_type,pay_no,bank_name,pay_dates,pay_amount,bill_amount,tds_ded,bill_passed,net_amount,deduction,
IF(bill_amount=(net_amount+tds_ded),'paid',IF(`status` LIKE '%paid%','paid','part payment'))`status`,remarks, pay_type_dates FROM 
(SELECT bill_no,company_name,branch_name,financial_year,GROUP_CONCAT(bpp.pay_type  ORDER BY id SEPARATOR '#') pay_type,
GROUP_CONCAT(bpp.pay_no  ORDER BY id SEPARATOR '#') pay_no,GROUP_CONCAT(bpp.bank_name  ORDER BY id SEPARATOR '#') bank_name,
GROUP_CONCAT(pay_dates  ORDER BY id SEPARATOR '#')pay_dates,GROUP_CONCAT(pay_amount  ORDER BY id SEPARATOR '#') pay_amount,bill_amount,
bill_passed,SUM(tds_ded) tds_ded,SUM(net_amount) net_amount,SUM(deduction) deduction,GROUP_CONCAT(`status` ORDER BY id) `status`,remarks,
pay_type_dates FROM `bill_pay_particulars` bpp  GROUP BY bpp.financial_year,bpp.company_name,bpp.branch_name,bpp.bill_no) bill_pay_particulars) bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 and t1.CurrentInvoiceType='OutStanding' and (bpp.status is null or bpp.status!='paid')  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)";
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['ProcessName'] = $res['0']['ProcessName'];
                    $amt_res = ($res['t1']['grnd']);
                    
                    if(!empty($res['bpp']['tds_ded']))
                    {
                        $amt_res -= $res['bpp']['tds_ded'];
                    }
                    if(!empty($res['bpp']['net_amount']))
                    {
                        $amt_res -= $res['bpp']['net_amount'];
                    }
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] = $amt_res; 
                    
                    //$prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                  //  $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                              //  $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];
                    

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             //print_r($data_branch); exit;
            $query_prov = "select *,if(t2.CostCenterName is null,t2.process_name,t2.CostCenterName) ProcessName  from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['ProcessName'] = $res['0']['ProcessName'];
                    //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision_balance']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                
             }
            
             
             
             
             $branch_arr = array_unique($branch_arr);
             $cost_arr = array_unique($cost_arr);
             $month_arr = array_unique($month_arr);
             
             sort($branch_arr);
             
             
             if($fetch_type=='export')
             {
                $fileName = "collection_details";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             
             echo '<table '.$class.'>';
            echo '<thead><tr>';
                echo '<th>Sr. No</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Process</th>';
                echo '<th>Branch</th>';
                echo '<th>Invoice No</th>';
                echo '<th>Amount</th>';
                echo '<th>Month</th>';
                if($fetch_type!='export')
                {
                   echo '<th>Action</th>';
                }   
                   echo '<th>EPTP Date</th>';
                echo '<th>Remarks</th>';
                
            echo '</tr></thead>';
            $srNo = 1;$total_eptp_sum =0;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                            $hisre_arr=explode(",",$record[$mnt][$index]['his_remarks']);
                            $hisdt_arr=explode(',',$record[$mnt][$index]['his_action_date']);
                            $date_his_dv = '<table>'; $i_rem = 0;
                            foreach($hisdt_arr as $h_dt)
                            {
                                $date_his_dv .= '<tr>';
                                    $date_his_dv .= '<td>'.trim($h_dt).'&nbsp;&nbsp;</td>';
                                    $date_his_dv .= '<td>'.trim($hisre_arr[$i_rem++]).'</td>';
                                    
                                $date_his_dv .= '</tr>';
                            }
                            $date_his_dv .='</table>';
                            
                            $rem = trim($record[$mnt][$index]['remarks']);
                            
                            if(empty($record[$mnt][$index]['action_date']))
                                    {
                                      $act_dat = '';  
                                    }
                                    else
                                    {
                                    $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                    $action_d1[0] = $action_d[2];
                                    $action_d1[1] = $action_d[1];
                                    $action_d1[2] = $action_d[0]; 
                                    
                                   $act_dat =  implode('-',$action_d1);
                                    }
                            
                                    
                                    $proname    =   $record[$mnt][$index]['ProcessName'];
                                    $proname1    =   strlen($proname) > 10 ? substr($proname,0,10)."..." : $proname;
                                    
                                    $branch_tr    =   $br;
                                    $branch_tr1    =   strlen($branch_tr) > 10 ? substr($branch_tr,0,10)."..." : $branch_tr;
                                    
                                    $eptp_rem    =   $record[$mnt][$index]['remarks'];
                                    $eptp_rem1    =   strlen($eptp_rem) > 10 ? substr($eptp_rem,0,10)."..." : $eptp_rem;
                                    
                                echo '<tr>';
                                echo '<td>'.$srNo++.'</td>';
                                echo '<td>'.$map_branch[$br][$cost_id].'</td>';
                                if($fetch_type=='export')
                                {
                                    echo '<td>'.$record[$mnt][$index]['ProcessName'].'</td>';
                                    echo '<td>'.$br.'</td>';
                                
                                }
                                else
                                {
                                   echo '<td>'.$proname1.'</td>';
                                echo '<td>'.$branch_tr1.'</td>';
                                }
                                echo '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                echo '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                $total_eptp_sum +=$record[$mnt][$index]['amt'];
                                //echo '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                echo '<td>'."'$mnt".'</td>';
                                
                                if($fetch_type=='export')
                                {
                                    echo '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    echo '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //echo '<td></td>';
                                }
                                else
                                {
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    echo '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','$inv','$rem','$act_dat','$date_his_dv','{$record[$mnt][$index]['ProcessName']}'".')">EPTP Date</a</td>';
                                    
                                    echo '<td>'.$act_dat.'</td>';
                                    
                                    
                                    
                                
                                echo '<td>'.$eptp_rem1.'</td>';
                                }
                                echo '</tr>';
                            
                        }
                    }
                }
            }
            
            echo '<tr>';
            echo '<th colspan="5">Total</th>';
            echo '<th>'.$total_eptp_sum.'</th>';
            echo '<th colspan="4"></th>';
            echo '</tr>';
             echo '</table>';
             exit;
        }
        
         exit;
    }
    
    
    public function collection_tracking_ptp_report()
    {
        $this->layout='home';
        $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"active='1'",'order'=>array('branch_name'=>'asc')));
        $finance_year = $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2019-20','2020-21','2021-22'),'active'=>'1')));
        $branch_master = array('All'=>'All')+$branch_master;
        $this->set('branch_master',$branch_master);
        $this->set('finance_year',$finance_year);
          
    }
    
    public function get_coll_eptp_track()
    {
        
         if($this->request->is('POST'))
         {
             $request = $this->request->data;
         }
         else
         {
             $request = $this->params->query;
         }
           $branch = $request['branch']; 
            $month = $request['finance_month'];
            $fetch_type = $request['fetch_type'];
            $finance_year = $request['finance_year'];
            $from_date = $request['from_date'];
            
            $fin_arr = explode('-',$finance_year);
            if(in_array($month,array('Jan','Feb','Mar')))
            {
                $fin_year = $fin_arr[1];
            }
            else
            {
                $fin_year = $fin_arr[0]-2000;
            }
            
            $cost_id = '';
            
            $before_date = date("Y-m-d",strtotime("01-$month-$fin_year")); 
            
            $from_date = date("Y-m-d",strtotime($from_date));
            
            
            
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and branch_name='$branch'"; 
            }
            
            $select_branch = "select id,branch_name from branch_master bm where 1=1 $branch_name";
            $branch_master = $this->InitialInvoice->query($select_branch);
            
            $data_record_arr = array();
            foreach($branch_master as $bm)
            {
                $branch = $bm['bm']['branch_name'];
                $cost_master_qry = "select id,company_name,cost_center,costcentername from cost_master cm where branch='$branch'";
                $cost_master = $this->InitialInvoice->query($cost_master_qry);
                $branch_arr[] = $branch;
                $data_record_arr[$branch]['branch'] = $branch;
                foreach($cost_master as $cost)
                {
                   $select_outstanding = "SELECT * FROM tbl_invoice ti 
                    WHERE ti.status = '0' and ti.bill_no!='' and ti.bill_no is not null and ti.grnd!=0 and ti.grnd!=1 and ti.CurrentInvoiceType='OutStanding' 
                    and finance_year='2019-20' and
                    branch_name='$branch' and
                    invoiceType='Revenue' AND 
                    cost_center='{$cost['cm']['cost_center']}' AND 
                    STR_TO_DATE(concat('01-',month),'%d-%b-%y') <'$before_date'"; 
                     
                    $invoice_master = $this->InitialInvoice->query($select_outstanding);
                    
                    foreach($invoice_master as $inv)
                    {
                        $company = $cost['cm']['company_name']; 
                        $branch = $inv['ti']['branch_name'];
                        $finance_year = $inv['ti']['finance_year'];
                        $bill_no_arr = explode('/',$inv['ti']['bill_no']);
                        $bill_no = $bill_no_arr[0];
                        
                       
                        
                        $sel_check_paid_status = "SELECT bpp.status,pay_dates,net_amount,tds_ded FROM bill_pay_particulars bpp 
                            WHERE status='paid' and company_name='$company' AND branch_name='$branch' AND 
                            financial_year='$finance_year' AND bill_no='$bill_no'"; //exit;
                       $collection_master = $this->InitialInvoice->query($sel_check_paid_status);
                       //continue;
                       
                       //print_r($collection_master); exit;
                       
                       if($collection_master['0']['bpp']['status']=='paid')
                       {
                            continue;
                       }
                       else
                       {
                           $data_record_arr[$branch]['inv'] .= $inv['ti']['bill_no'].',';
                           
                            $sel_check_paid_status = "SELECT bpp.status,pay_dates,net_amount,tds_ded FROM bill_pay_particulars bpp 
                                WHERE company_name='$company' AND branch_name='$branch' AND 
                                financial_year='$finance_year' AND bill_no='$bill_no'"; //exit;
                           $collection_master = $this->InitialInvoice->query($sel_check_paid_status);
                        
                            foreach($collection_master as $coll)
                            {

                                //print_r($coll); exit;
                                //total outstanding
                                //$data_record_arr[$branch]['outstanding'] -= ($coll['bpp']['net_amount']+$coll['bpp']['tds_ded']);
                                $data_record_arr[$branch]['amount'] .= ($coll['bpp']['pay_dates']).',';
                                $data_record_arr[$branch]['outstanding'] += $inv['ti']['grnd'];
                                
                                if(!empty($inv['ti']['eptp_act_date']))
                                {
                                    $data_record_arr[$branch]['month_commitment'] += ($inv['ti']['grnd']-$coll['bpp']['net_amount']-$coll['bpp']['tds_ded']);
                                }
                                
                                
                                
                                if(!empty($coll['bpp']['pay_dates']) && strtotime($coll['bpp']['pay_dates'])<strtotime($before_date))
                                {
                                    $data_record_arr[$branch]['outstanding'] -= ($coll['bpp']['net_amount']+$coll['bpp']['tds_ded']);
                                }
                                //total commitment by user


                                //committment outstanding till todays date 
                                if(!empty($inv['ti']['eptp_act_date']) &&  strtotime($inv['ti']['eptp_act_date'])<=strtotime($from_date))
                                {
                                    $data_record_arr[$branch]['commit_till_date'] += ($inv['ti']['grnd']-$coll['bpp']['net_amount']-$coll['bpp']['tds_ded']);
                                }

                                //collectioin as on date
                                if(!empty($inv['ti']['eptp_act_date']) &&  strtotime($inv['ti']['eptp_act_date'])<=strtotime($from_date) && strtotime($coll['bpp']['pay_dates'])<strtotime($before_date))
                                {
                                    $data_record_arr[$branch]['collection_till_date'] += $coll['bpp']['net_amount']+$coll['bpp']['tds_ded'];
                                }
                            }
                       }
                       
                        
                    }
                    
                }
               //print_r($data_record_arr); exit; 
            }
             // print_r($data_record_arr); exit;            
             if($fetch_type=='export')
             {
                $fileName = "collection_eptp_report";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             $branch_arr = array_unique($branch_arr);
             //print_r($branch_arr); exit;
             echo '<table border="2" '.$class.'>';
            echo '<thead><tr>';
                echo '<th>Sr. No</th>';
                echo '<th>Branches</th>';
                echo '<th>Opening Outstanding AS ON</th>';
                echo '<th>Total Commitment Coll Month Select</th>';
                echo '<th>Committed Coll.  As on Date----</th>';
                echo '<th>Collection  As on Date----</th>';
                echo '<th>Balance Collection as on date</th>';
                
                echo '<th>Balance Collection for the Month</th>';
                //echo '<th>inv</th>';
                //echo '<th>amt</th>';
            echo '</tr></thead>';
            $srNo = 1; 
            foreach($branch_arr as $br=>$branch_name)
            {
                
                //print_r($data_record_arr[$branch_name]); exit;
                echo '<tr>';
                echo '<td>'.$srNo++.'</td>';
                echo '<td>'.$branch_name.'</td>';
                echo '<td>'.$data_record_arr[$branch_name]['outstanding'].'</td>';
                echo '<td>'.$data_record_arr[$branch_name]['month_commitment'].'</td>';
                echo '<td>'.$data_record_arr[$branch_name]['commit_till_date'].'</td>';
                echo '<td>'.$data_record_arr[$branch_name]['collection_till_date'].'</td>';
                
                $bal_coll_till_date = round($data_record_arr[$branch_name]['commit_till_date']-$data_record_arr[$branch_name]['collection_till_date'],2);
                
                echo '<td>'.$bal_coll_till_date.'</td>';
                
                $bal_coll_for_month = round($data_record_arr[$branch_name]['month_commitment']-$data_record_arr[$branch_name]['collection_till_date'],2);
                echo '<td>'.$bal_coll_for_month.'</td>';
                //echo '<td>'.$data_record_arr[$branch_name]['inv'].'</td>';
                //echo '<td>'.$data_record_arr[$branch_name]['amount'].'</td>';
            }
            
            //echo '<tr>';
            
            
            
            
             echo '</table>';
             exit;
        }
    
    
    
    public function add_eptp_date()
    {
        $this->layout="home";
        $conditions = array('active'=>'1') ;
        $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions));
        $this->set('branch_master',$branch);
        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2019-20','2020-21','2021-22'),'active'=>'1'))));
        
        if($this->request->is('POST'))
        {
            $id = $this->request->data['CollectionReport']['month'];
            $category = $this->request->data['CollectionReport']['category'];
            $action_date = $this->request->data['CollectionReport']['action_date'];
            $remarks = addslashes($this->request->data['CollectionReport']['remarks']);
            
            if(empty($id))
            {
                $this->Session->setFlash("Please Select Month");   
            }
            else if(empty($category))
            {
                $this->Session->setFlash("Please Select Category");   
            }
            else if(empty($action_date))
            {
                $this->Session->setFlash("Please Select Action Date");   
            }
            else if(empty($remarks))
            {
                $this->Session->setFlash("Please Fill Remarks");   
            }
            else
            {
                $action_date_arr = explode('-',$action_date);
                $action_date_arr2[0] = $action_date_arr[2];
                $action_date_arr2[1] = $action_date_arr[1];
                $action_date_arr2[2] = $action_date_arr[0];
                
                $action_date = "'".implode('-',$action_date_arr2)."'";
                $remarks = "'$remarks'";
                
                $upd_arr = array();
                if($category=='Agreement Pending')
                {
                    $upd_arr['eptp_act_agree_date'] = $action_date;
                    $upd_arr['eptp_act_agree_remarks'] = $remarks;
                }
                else if($category=='PO Pending')
                {
                    $upd_arr['eptp_act_po_date'] = $action_date;
                    $upd_arr['eptp_act_po_remarks'] = $remarks;
                }
                else if($category=='GRN Pending')
                {
                    $upd_arr['eptp_act_grn_date'] = $action_date;
                    $upd_arr['eptp_act_grn_remarks'] = $remarks;
                }
                else if($category=='Receiving Pending')
                {
                   $upd_arr['eptp_act_receipt_date'] = $action_date; 
                   $upd_arr['eptp_act_receipt_remarks'] = $remarks;
                }
                
                if($this->Provision->updateAll($upd_arr,array('id'=>$id)))
                {
                    $this->Session->setFlash("Record Updated Successfully");
                }
                else
                {
                    $this->Session->setFlash("Record Not Updated");
                }
            }
            
            return $this->redirect(array('action'=>'add_eptp_date'));
        }
        
    }
    public function get_prov_mnt()
    {
        $this->layout = "ajax";
        $branch = $this->request->data['branch'];
        $cost_center = $this->request->data['cost_center'];
        $finance_year = $this->request->data['finance_year'];
        
        $mnt_arr =  $this->Provision->find('list',array('conditions'=>array('branch_name'=>$branch,'finance_year'=>$finance_year,'cost_center'=>$cost_center),
            'fields'=>array('id','month')));
        echo '<option value="">Select</option>';
        foreach($mnt_arr as $id=>$mnt)
        {
            echo '<option value="'.$id.'">'.$mnt.'</option>';
        }
        
        exit;
    }
    
    public function report_eptp_tracking()
    {
        if($this->request->is('POST'))
        {
            $data = $this->request->data['CollectionReports'];
            $month = $data['month'];
            $cost_center = $data['cost_center'];
            
            $entry_type = $this->request->data['entry_type'];
            
            if(1)
            {
                    $action_date2 = $action_date = $data['eptp_date'];
                    $remarks = addslashes($data['remarks']);
                    $action_date2 = "'$action_date2'";
                    $inv = $data['inv']; //exit;
                    
                    $action_date_arr = explode('-',$action_date);
                    $action_date_arr2[0] = $action_date_arr[2];
                    $action_date_arr2[1] = $action_date_arr[1];
                    $action_date_arr2[2] = $action_date_arr[0];

                    $action_date = "'".implode('-',$action_date_arr2)."'";
                    $remarks = "'$remarks'";
                
                    
                    $upd_arr = array();
                    $upd_arr['eptp_act_date'] = $action_date;
                    $upd_arr['eptp_act_remarks'] = $remarks;
                    $upd_arr['his_eptp_act_date'] = "concat(if(his_eptp_act_date is null,'',his_eptp_act_date),',',$action_date2)";
                    $upd_arr['his_eptp_act_remarks'] = "concat(if(his_eptp_act_remarks is null,'',his_eptp_act_remarks),',',$remarks)";
                    //print_r($this->request->data['CollectionReports']); exit;
                    if($inv)
                    {
                        if($this->InitialInvoice->updateAll($upd_arr,array('bill_no'=>$inv,'cost_center'=>$cost_center)))
                        {
                            $this->Session->setFlash("Record Updated Successfully");
                        }
                        else
                        {
                            $this->Session->setFlash("Record Not Updated");
                        }
                    }
                    else
                    {
                        if($this->Provision->updateAll($upd_arr,array('month'=>$month,'cost_center'=>$cost_center)))
                        {
                            $this->Session->setFlash("Record Updated Successfully");
                        }
                        else
                        {
                            $this->Session->setFlash("Record Not Updated");
                        }
                    }
                    
            }
            
            $branch = $data['branchS'];
            $category = $data['categoryS'];
            $report_typeS = $data['report_typeS'];
            
            $this->set('branchS',$branch);
            //$this->set('categoryS',$category);
            $this->set('report_typeS',$report_typeS);
            
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t1.eptp_act_date,t1.eptp_act_remarks,t1.his_eptp_act_date,t1.his_eptp_act_remarks,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status` 
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  bill_pay_particulars bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)";
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= $res['t1']['grnd'];
                    
                    $prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                    $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    if(empty($ag_check))
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'A';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                       
                    }
                    if($res['0']['bill_status']=='PO Pending')
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    if($res['0']['bill_status']=='GRN Pending')
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'G';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                       
                    }
                    if(($res['t1']['ReceiptStatus']=='0' || $res['t1']['ReceiptStatus']==0) )
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'R';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    if($res['0']['bill_status']=='submitted' && $res['t1']['ReceiptStatus']!='0' && $res['t1']['ReceiptStatus']!=0)
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PTP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             
            $query_prov = "select * from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                if($category == 'Bill Ready')
                {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                }
             }
            
             //print_r($data_branch); exit;
             
             
             $branch_arr = array_unique($branch_arr);
             $cost_arr = array_unique($cost_arr);
             $month_arr = array_unique($month_arr);
             
             sort($branch_arr);
             
             
             if($fetch_type=='export')
             {
                $fileName = "collection_details";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             
             $htm = '<table '.$class.'>';
            $htm .= '<thead><tr>';
                $htm .= '<th>Sr. No</th>';
                $htm .= '<th>Cost Center</th>';
                $htm .= '<th>Client</th>';
                $htm .= '<th>Branch</th>';
                $htm .= '<th>Invoice No</th>';
                $htm .= '<th>Amount</th>';
                $htm .= '<th>Month</th>';
                if($fetch_type!='export')
                {
                   $htm .= '<th>Action</th>';
                }   
                   $htm .= '<th>EPTP Date</th>';
                $htm .= '<th>Remarks</th>';
                
            $htm .= '</tr></thead>';
            $srNo = 1;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                                $htm .= '<tr>';
                                $htm .= '<td>'.$srNo++.'</td>';
                                $htm .= '<td>'.$map_branch[$br][$cost_id].'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['client'].'</td>';
                                $htm .= '<td>'.$br.'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                $htm .= '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                //echo '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                $htm .= '<td>'."'$mnt".'</td>';
                                
                                if($fetch_type=='export')
                                {
                                    $htm .= '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    $htm .= '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //echo '<td></td>';
                                }
                                else
                                {
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    $htm .= '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','$inv'".')">Add EPTP Date</a</td>';
                                    if(empty($record[$mnt][$index]['action_date']))
                                    {
                                        $htm .= '<td></td>';
                                    }
                                    else
                                    {
                                    $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                    $action_d1[0] = $action_d[2];
                                    $action_d1[1] = $action_d[1];
                                    $action_d1[2] = $action_d[0];
                                    
                                    $htm .= '<td>'.implode('-',$action_d1).'</td>';
                                    }
                                    
                                    
                                    
                                    
                                
                                $htm .= '<td>'.$record[$mnt][$index]['remarks'].'</td>';
                                }
                                $htm .= '</tr>';
                            
                        }
                    }
                }
            }
             $htm .= '</table>';
        $this->set('htm',$htm);
        
        //print_r($htm); exit;
        
        }  
        
        
        
        
        $this->layout='home';
        $userid = $this->Session->read('userid');
        $branch_name = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin')
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"active='1'"));
            $branch_master = array('All'=>'All') + $branch_master;
        }
        else
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"branch_name ='$branch_name' and active='1'"));
            $branch_master = array('All'=>'All') + $branch_master;
        }
        
        $this->set('branch_master',$branch_master);
        $this->set('category_master',$category_master);
        
    }
    
    public function get_eptp_track()
    {
        if($this->request->is('POST'))
        {
            $branch = $this->request->data['branch'];
            $report_type = $this->request->data['report_type'];
            $fetch_type = $this->request->data['fetch_type'];
        }
        else
        {
            $branch = $this->params->query['branch']; 
            $report_type = $this->params->query['report_type'];
            $fetch_type = $this->request->query['fetch_type']; 
        }
        
        if($report_type=='Details')
        {
            $branch_name ='';
            if($branch!='All') //for branches show only theire bills
            {
                $branch_name=" and t1.branch_name='$branch'"; 
            }
            
            $query = "SELECT t1.id,t1.eptp_act_date,t1.eptp_act_remarks,t1.his_eptp_act_date,t1.his_eptp_act_remarks,t2.id,t1.ReceiptStatus, t3.ExpDatesPayment,t2.company_name,t2.cost_center,t2.CostCenterName,t2.client, t1.bill_no,t2.branch,t1.finance_year, 
t1.month,t1.total,t1.tax,t1.sbctax,t1.krishi_tax,t1.igst,t1.cgst,t1.sgst, bpp.net_amount,bpp.tds_ded, t1.grnd,
t1.invoiceDescription,t1.invoiceDate,t1.grn, 
LAST_DAY(STR_TO_DATE(CONCAT('10-',t1.`month`),'%d-%b-%Y')) mnt,
IF(bpp.status = 'part payment','part payment',
IF(t2.po_required='Yes' AND (t1.approve_po = '' OR t1.approve_po IS NULL),'PO Pending', 
IF(t2.grn='Yes' AND (t1.approve_grn = '' OR t1.approve_grn IS NULL),'GRN Pending','submitted'))

 ) `bill_status` 
FROM tbl_invoice t1 INNER JOIN cost_master t2 ON t1.cost_center = t2.cost_center LEFT JOIN receipt_master t3 ON t1.id = t3.Invoiceid 
LEFT JOIN  bill_pay_particulars bpp ON 
 SUBSTRING_INDEX(t1.bill_no,'/','1')=bpp.bill_no AND t1.finance_year = bpp.financial_year AND t2.company_name=bpp.company_name AND 
 t1.branch_name = bpp.branch_name   
 WHERE t1.status = '0' and t1.bill_no!='' and t1.bill_no is not null and t1.grnd!=0 and t1.grnd!=1 AND bpp.status is null  $branch_name
 ORDER BY t1.finance_year, CONVERT (SUBSTRING_INDEX(t1.bill_no,'/','1'),UNSIGNED INT)";
        $response_det=$this->InitialInvoice->query($query);
                        
            //print_r($response_det); exit;
                        
            $sr = 1;
            foreach($response_det as $res)
            {
                 if($res['0']['bill_status']!='paid' && $res['bpp']['bill_status']!='paid')
                 {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['bill_no'] .= $res['t1']['bill_no'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= $res['t1']['grnd'];
                    
                    $prov_record = $this->Provision->find('first',array('conditions'=>"branch_name='{$res['t2']['branch']}' and cost_center='{$res['t2']['cost_center']}' and finance_year='{$res['t1']['finance_year']}' and month='{$res['t1']['month']}'"));
                    
                    $select = "SELECT cost_center cost_center_id FROM `agreement_particulars` where '{$res['0']['mnt']}' between periodTo and periodFrom and cost_center='{$res['t2']['id']}'"; 
                                $ag_check = $this->InitialInvoice->query($select);
                    
                    
                    if(empty($ag_check))
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'A';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                       
                    }
                    if($res['0']['bill_status']=='PO Pending')
                    {
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    if($res['0']['bill_status']=='GRN Pending')
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'G';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                       
                    }
                    if(($res['t1']['ReceiptStatus']=='0' || $res['t1']['ReceiptStatus']==0))
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'R';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    if($res['0']['bill_status']=='submitted' && $res['t1']['ReceiptStatus']!='0' && $res['t1']['ReceiptStatus']!=0)
                    {
                       //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'PTP';
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $prov_record['Provision']['eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $prov_record['Provision']['eptp_act_remarks'];
                       
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $prov_record['Provision']['his_eptp_act_date'];
                       $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $prov_record['Provision']['his_eptp_act_remarks'];
                    }
                    

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                 }
            }
             //print_r($data_branch); exit;
            $query_prov = "select * from provision_master t1 inner join cost_master t2 on t1.cost_center = t2.cost_center where t1.provision_balance!=0 $branch_name"; 
            $response_det_prov=$this->InitialInvoice->query($query_prov);
            
            foreach($response_det_prov as $res)
            {
                if($category == 'Bill Ready')
                {
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['client'] = $res['t2']['client'];
                    //$data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['Category'] = 'B';
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['amt'] .= round($res['t1']['provision']*1.18);
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['action_date'] = $res['t1']['eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['remarks'] = $res['t1']['eptp_act_remarks'];
                    
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_action_date'] = $res['t1']['his_eptp_act_date'];
                    $data_branch[$res['t2']['branch']][$res['t2']['id']][$res['t1']['month']][$sr]['his_remarks'] = $res['t1']['his_eptp_act_remarks'];

                    $branch_arr[] = $res['t2']['branch'];
                    $cost_arr[] = $res['t2']['id'];
                    $map_branch[$res['t2']['branch']][$res['t2']['id']] = $res['t2']['cost_center'];
                    $month_arr[] = $res['t1']['month'];
                    $sr++;
                }
             }
            
             
             
             
             $branch_arr = array_unique($branch_arr);
             $cost_arr = array_unique($cost_arr);
             $month_arr = array_unique($month_arr);
             
             sort($branch_arr);
             
             
             if($fetch_type=='export')
             {
                $fileName = "collection_details";
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                $class = "border = \"1\"";
             }
             else
             {
                 $class = "class = \"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
             }
             
             
             echo '<table '.$class.'>';
            echo '<thead><tr>';
                echo '<th>Sr. No</th>';
                echo '<th>Cost Center</th>';
                echo '<th>Client</th>';
                echo '<th>Branch</th>';
                echo '<th>Invoice No</th>';
                echo '<th>Amount</th>';
                echo '<th>Month</th>';
                if($fetch_type!='export')
                {
                   echo '<th>Action</th>';
                }   
                   echo '<th>EPTP Date</th>';
                echo '<th>Remarks</th>';
                
            echo '</tr></thead>';
            $srNo = 1;
            foreach($branch_arr as $br)
            {
                foreach($data_branch[$br] as $cost_id=>$record)
                {
                    foreach($month_arr as $mnt)
                    {
                        $indexes_arr = array_keys($record[$mnt]);
                        foreach($indexes_arr as $index)
                        {
                                echo '<tr>';
                                echo '<td>'.$srNo++.'</td>';
                                echo '<td>'.$map_branch[$br][$cost_id].'</td>';
                                echo '<td>'.$record[$mnt][$index]['client'].'</td>';
                                echo '<td>'.$br.'</td>';
                                echo '<td>'.$record[$mnt][$index]['bill_no'].'</td>';
                                echo '<td>'.$record[$mnt][$index]['amt'].'</td>';
                                //echo '<td style="text-align:center">'.$record[$mnt][$index]['Category'].'</td>';
                                echo '<td>'."'$mnt".'</td>';
                                
                                if($fetch_type=='export')
                                {
                                    echo '<td>'.$record[$mnt][$index]['his_action_date'].'</td>';
                                    echo '<td>'.$record[$mnt][$index]['his_remarks'].'</td>';
                                //echo '<td></td>';
                                }
                                else
                                {
                                    $inv=0;
                                    if(!empty($record[$mnt][$index]['bill_no']))
                                    {
                                        $inv=$record[$mnt][$index]['bill_no'];
                                    }
                                    echo '<td><a href="#" id="myBtn1" onclick="get_add_eptp_date('."'{$map_branch[$br][$cost_id]}','$mnt','$inv'".')">Add EPTP Date</a</td>';
                                    if(empty($record[$mnt][$index]['action_date']))
                                    {
                                        echo '<td></td>';
                                    }
                                    else
                                    {
                                    $action_d = explode("-",substr($record[$mnt][$index]['action_date'],0,10));
                                    $action_d1[0] = $action_d[2];
                                    $action_d1[1] = $action_d[1];
                                    $action_d1[2] = $action_d[0]; 
                                    
                                    echo '<td>'.implode('-',$action_d1).'</td>';
                                    }
                                    
                                    
                                    
                                    
                                
                                echo '<td>'.$record[$mnt][$index]['remarks'].'</td>';
                                }
                                echo '</tr>';
                            
                        }
                    }
                }
            }
             echo '</table>';
             exit;
        }
         exit;
    }
    
    public function collection_tracking_matrix()
    { 
        $this->layout="home";
        
        if($this->request->is('POST'))
        {
            $request = $this->request->data;
            $id_arr = explode(',',$request['id_arr']);
            foreach($id_arr as $id)
            {
                $upd_arr = array();
                $upd_arr['col_61_90_after_lapsed'] = "'".$request['col_61_90_after_lapsed'.$id]."'";
                $upd_arr['col_31_60_after_lapsed'] = "'".$request['col_31_60_after_lapsed'.$id]."'";
                $upd_arr['col_16_30_after_lapsed'] = "'".$request['col_16_30_after_lapsed'.$id]."'";
                $upd_arr['col_6_15_after_lapsed'] = "'".$request['col_6_15_after_lapsed'.$id]."'";
                $upd_arr['col_0_5_after_lapsed'] = "'".$request['col_0_5_after_lapsed'.$id]."'";
                $upd_arr['col_less_15_before_lapsed'] = "'".$request['col_less_15_before_lapsed'.$id]."'";
                $this->CollectionTrackingMatrix->updateAll($upd_arr,array('Id'=>$id));
                
                 $this->Session->setFlash("Record Updated Successfully");
                
            }
        }
        $data = $this->CollectionTrackingMatrix->find('all');
        $this->set('data',$data);
    }
    
}

?>