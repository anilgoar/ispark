<?php
class DashReportsController extends AppController 
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
            if(in_array('49',$roles)){$this->Auth->allow('index','export','get_reportdash');}
	}
    }
		
    
    
    public function index()
    {
      $this->layout="home";
      $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin')
        {
            $this->set('branchName',array_merge(array('All'=>'All'),$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')))));
        }
        else if(count($branchName)>1)
        {
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else
        {
            $this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
        }

       
    }
    public function export()
    {
        $this->layout = "home";
        
    }
     public function get_reportdash()
    {
        $this->layout='ajax';
      //print_r($this->request->is('POST')); exit;
       $result = $this->params->query;	
       
		//$this->set("res",$result);
		$to_date = date_create($result['to_date']);
		$to_date1 = date_format($to_date,"Y-m-d");
		 $Tto_date = date_format($to_date,"m-y");
		$from_date = date_create($result['from_date']);
		 $from_date1 = date_format($from_date,"Y-m-d");
                 $Tfrom_date = date_format($from_date,"m-y");
		 $branch_name = $result['BranchName'];
		
		if($branch_name == 'All')
		{
                    
            $this->set("Data1",
           $this->AgreementParticular->query(
                    " SELECT f.*,dfp.branch, dfp.cost_center,dfp.tower FROM `cost_master` dfp RIGHT JOIN 
 (SELECT dt.branch, dt.branch_process , dt.target , dt.target_directCost, dt.target_IDC , DATE_FORMAT(dt.month,'%b-%y') target_month, 
 dd.commit, dd.direct_cost, dd.indirect_cost, DATE_FORMAT(dd.createdate, '%d-%b-%y') crdate,dd.cost_centerId FROM `dashboard_Target` dt 
 right JOIN `dashboard_data` dd ON dt.branch = dd.branch AND dt.cost_centerId = dd.cost_centerId AND 
 DATE_FORMAT(dt.month,'%b-%y') = DATE_FORMAT(dd.createdate,'%b-%y') WHERE 
 DATE(dd.createdate) BETWEEN '$to_date1' AND '$from_date1') f ON f.cost_centerId =dfp.id 
 ORDER BY dfp.branch"
                           ));
       
            
         }
 else {
     $this->set("Data1",
           $this->AgreementParticular->query(" SELECT f.*,dfp.branch, dfp.cost_center,dfp.tower FROM `cost_master` dfp RIGHT JOIN 
 (SELECT dt.branch, dt.branch_process , dt.target , dt.target_directCost, dt.target_IDC , DATE_FORMAT(dt.month,'%b-%y') target_month, 
 dd.commit, dd.direct_cost, dd.indirect_cost, DATE_FORMAT(dd.createdate, '%d-%b-%y') crdate,dd.cost_centerId FROM `dashboard_Target` dt 
 right JOIN `dashboard_data` dd ON dt.branch = dd.branch AND dt.cost_centerId = dd.cost_centerId AND 
 DATE_FORMAT(dt.month,'%b-%y') = DATE_FORMAT(dd.createdate,'%b-%y') WHERE  
 DATE(dd.createdate) BETWEEN '$to_date1' AND '$from_date1' AND dd.branch = '$branch_name') f ON f.cost_centerId =dfp.id 
 ORDER BY dfp.branch"));
 }
        
    }
}
?>