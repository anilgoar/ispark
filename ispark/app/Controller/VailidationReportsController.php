<?php
class VailidationReportsController extends AppController 
{
    public $uses=array('Addbranch','VailidationReport','CostCenterMaster');
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
            $this->Auth->allow('index','get_costcenter','get_reportdash');
            $this->Auth->allow('dashboard','provisionDetails','showReport','get_dash_data');
	}
    }
		
    
    
    public function index()
    {
      $this->layout="home";
      $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin')
        {
            $this->set('branchName',array_merge(array('All'=>'All','JAIPUR IDC'=>'JAIPUR IDC'),$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')))));
        }
        else if(count($branchName)>1)
        {
            
        }
        else
        {
            $this->set('costcenter',$this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('Branch'=>$branchName))));
        }

       
    }
	
	public function get_costcenter()
	{
		$this->layout = "ajax";
		$branchName=$this->request->data['branch'];
		$this->set('costcenter',array_merge(array('All'=>'All'),$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('Branch'=>$branchName)))));
	}
	
    public function export()
    {
        $this->layout = "home";
        
    }
     public function get_reportdash()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch'];
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationReport->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter"
                           ));
      
            
        
    }
}
?>