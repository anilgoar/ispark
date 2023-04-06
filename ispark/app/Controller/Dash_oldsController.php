<?php
class DashsController extends AppController 
{
    public $uses=array('Addbranch','Dash','AgreementParticular');
    public $components =array('Session');
    public function beforeFilter()
    {
        parent::beforeFilter();
	 
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
	else
	{
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            if(in_array('4',$roles)){$this->Auth->allow('index','view','add','edit','update','provision_check','provision_check_edit','uploadProvision','view_provision', 'export', 'get_report11');}
            if(in_array('38',$roles)){$this->Auth->allow('dashboard','provisionDetails','showReport','get_dash_data');}
	}
    }
		
    
    
    public function view()
    {
       $this->layout = "home";
       $this->set("Data",
           $this->AgreementParticular->query("SELECT dt.target,dt.target_directCost, dt.target_IDC,d.md, d.br, d.cmt, d.dc, d.idc,d.brc FROM (SELECT DATE_FORMAT(dd.createdate,'%b-%y') cd,DATE_FORMAT(tt.reatedate,'%d-%b-%y') md, tt.brcp brc, dd.branch br ,SUM(`commit`) cmt,SUM(`direct_cost`) dc, ROUND(SUM(`indirect_cost`),2) idc,
dd.branch_process bp FROM dashboard_data dd 
JOIN (SELECT MAX(pr.createdate) reatedate, dp.branch_process brcp FROM dashboard_data pr JOIN `dashboard_process` dp ON  pr.branch_process =dp.id GROUP BY pr.branch_process ) tt
ON dd.createdate =tt.reatedate GROUP BY dd.branch_process) d  RIGHT JOIN (SELECT DATE_FORMAT(`month`,'%b-%y') md, target, branch, branch_process,`target_directCost`,`target_IDC` FROM `dashboard_Target` GROUP BY branch_process) dt ON d.cd = dt.md AND dt.branch= d.br AND d.bp = dt.branch_process

UNION ALL
SELECT dt.target,dt.target_directCost, dt.target_IDC,d.md, d.br, d.cmt, d.dc, d.idc,d.brc FROM (SELECT DATE_FORMAT(dd.createdate,'%b-%y') cd,DATE_FORMAT(tt.reatedate,'%d-%b-%y') md, tt.brcp brc, dd.branch br ,SUM(`commit`) cmt,SUM(`direct_cost`) dc, ROUND(SUM(`indirect_cost`),2) idc,
dd.branch_process bp FROM dashboard_data dd 
JOIN (SELECT MAX(pr.createdate) reatedate, dp.branch_process brcp FROM dashboard_data pr JOIN `dashboard_process` dp ON  pr.branch_process =dp.id GROUP BY pr.branch_process ) tt
ON dd.createdate =tt.reatedate GROUP BY dd.branch_process) d  LEFT JOIN (SELECT DATE_FORMAT(`month`,'%b-%y') md, target, branch, branch_process,`target_directCost`,`target_IDC` FROM `dashboard_Target` GROUP BY branch_process) dt ON d.cd = dt.md AND dt.branch= d.br AND d.bp = dt.branch_process  "));
      
    }
    public function export()
    {
        $this->layout = "home";
        
    }
     public function get_report11()
    {
        $this->layout='ajax';
      //print_r($this->request->is('POST')); exit;
        $result = $this->params->query;
        $to_date = date_create($result['to_date']);
       $to_date = date_format($to_date,"Y-m-d");
      
            $this->set("Data1",
           $this->AgreementParticular->query("
SELECT f.*,dfp.Branch, dfp.branch_process FROM `dashboard_process` dfp RIGHT JOIN
(SELECT dt.branch, dt.branch_process , dt.target , dt.target_directCost, dt.target_IDC  , DATE_FORMAT(dt.month,'%b-%y') target_month,
 dd.commit, dd.direct_cost, dd.indirect_cost, DATE_FORMAT(dd.createdate, '%d-%b-%y') crdate FROM `dashboard_Target` dt 
LEFT JOIN `dashboard_data` dd ON dt.branch = dd.branch AND dt.branch_process = dd.branch_process 
AND DATE_FORMAT(dt.month,'%b-%y') = DATE_FORMAT(dd.createdate,'%b-%y') 
WHERE DATE_FORMAT(dt.month,'%b-%y')= DATE_FORMAT('$to_date','%b-%y') AND DATE_format(dd.createdate,'%Y-%m-%d') = DATE('$to_date'))  f ON  f.branch_process =dfp.id ORDER BY dfp.Branch, dfp.branch_process"));
       
            
       
        
    }
}
?>