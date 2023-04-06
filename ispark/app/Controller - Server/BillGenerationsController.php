<?php
	class BillGenerationsController extends AppController 
	{
		public $uses=array('CostCenterMaster','InitialInvoice','Addclient','Addbranch','Addcompany','Addprocess','Category','Type','EditAmount');
		public function beforeFilter()
		{
        	parent::beforeFilter();
        	$this->Auth->deny('index','report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','get_bill_generation');
			if(!$this->Session->check("username"))
			{
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
			}
                        else
                        {       $role=$this->Session->read("role");
				$roles=explode(',',$this->Session->read("page_access"));
                                if(in_array('26',$roles)){$this->Auth->allow('index','report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','get_bill_generation');}
                                
                        }
    	}
		
    	public function index() 
		{
		$this->layout='home';
		 $this->set('company_master',$this->Addcompany->find('all',array('fields'=>array('company_name'))));
        }

public function get_bill_generation()
{
    $this->layout = 'ajax';
    $result = $this->params->query;
    $this->set('report','edit');
    $subdate = date_create($result['AddFromDate']);
    $Frmdate = date_format($subdate,'Y-m-d');
    $expdate = date_create($result['AddToDate']);
    $todate = date_format($expdate,'Y-m-d');
    $reportType = $result['ReportType'];
    
    $Comp_name ='';
    
    if($result['AddCompanyName']=='All')
    {
       $Comp_name ='';
    }
    else
    {
        
            $Comp_name ="and t3.company_name='".$result['AddCompanyName']."'";        
    }
    
    $branch_name = $result['Branch']=='all'?"":"and t2.branch_name='".$result['Branch']."'";
			
    if($reportType!='Details')
    {
        $dataX = $this->EditAmount->query("SELECT t2.branch_name,t2.cost_center,sum(t2.grnd) `amount` FROM amount_edit t1 INNER JOIN tbl_invoice t2 ON t1.initial_id=t2.id INNER JOIN cost_master t3 ON t2.cost_center=t3.cost_center WHERE DATE(t2.createdate) BETWEEN '$Frmdate' AND '$todate' $Comp_name $branch_name group by t2.cost_center");
        $this->set("result2",$dataX);
    }
    else
    {
        $data = $this->EditAmount->query("SELECT t2.branch_name,t2.cost_center,t2.invoiceDescription,t2.grnd,t2.invoiceDate,t2.month FROM amount_edit t1 INNER JOIN tbl_invoice t2 ON t1.initial_id=t2.id INNER JOIN cost_master t3 ON t2.cost_center=t3.cost_center WHERE DATE(t1.createdate) BETWEEN '$Frmdate' AND '$todate' $Comp_name $branch_name");
        $this->set("result",$data);
    }
    $this->set("type",$result['type']);
    $this->set("ReportType",$reportType);
    //$this->set('qry',"Select t2.branch_name,t2.cost_center,sum(t2.grnd) `amount`,t2.invoiceDate,t2.month from tbl_invoice t2 inner join cost_master t1 on t1.cost_center = t2.cost_center where t2.bill_no !='' AND t2.createdate between '$Frmdate' AND '$todate' $Comp_name $branch_name group by t2.cost_center");
}
	
}
?>