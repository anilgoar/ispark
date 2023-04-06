<?php
class VailidationRejectsController extends AppController 
{
    public $uses=array('Addbranch','VailidationRejects','CostCenterMaster');
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
           if(in_array('60',$roles)){ $this->Auth->allow('index','get_costcenter','get_reportdash','get_reportdash_ex','get_reportdashcf','get_reportdashcoc','get_reportdashpoa','get_reportdashepf','get_reportdashepfstatus','get_reportdashpoe','get_reportdashresume');}
            $this->Auth->allow('dashboard','provisionDetails','showReport','get_dash_data','get_reportdash_Ex');
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
		$Branch =$this->params->query['branch']=='All'?"Group by Location":" and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		$this->set("res",$this->params->query['branch']);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                 "SELECT Location,
SUM(IF(dm.Resume_1='Reject',1,0)) `Resume` ,
SUM(IF(dm.POI='Reject',1,0)) POI,
SUM(IF(dm.POA='Reject',1,0)) POA,
SUM(IF(dm.POE='Reject',1,0)) POE,
SUM(IF(dm.CoC_1='Reject' OR dm.CoC_2='Reject',1,0)) CoC,
SUM(IF(dm.CF_1='Reject' OR dm.CF_2='Reject' OR dm.CF_3='Reject' OR dm.CF_4='Reject' OR dm.CF_5='Reject' OR dm.CF_6='Reject' OR dm.CF_7='Reject',1,0)) CF,
SUM(IF(dm.EPF='Reject' OR dm.EPF_1='Reject' OR dm.EPF_2='Reject' OR dm.EPF_3='Reject',1,0)) EPF,
SUM(IF(dm.POI='Reject' OR dm.POA='Reject' OR dm.POE='Reject' OR dm.Resume_1='Reject',1,0)) `status`
FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE IFNULL(em.Status,'')!='L' and Location is not null $CostCenter $Branch "
                         ));
      
            
        
    }
    public function get_reportdash_ex()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashcf()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashcoc()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashepf()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashepfstatus()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashpoa()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L'  $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashpoe()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L'  $Branch $CostCenter"
                           ));
      
            
        
    }
    public function get_reportdashresume()
    {
		$this->layout='ajax';
		//print_r($this->request->data);   
		$Branch =$this->params->query['branch']=='Total'?"":"and Location='".$this->params->query['branch']."'";
		$CostCenter =$this->params->query['costcenter']=='All'?"":" and CostCenter='".$this->params->query['costcenter']."'";
			
       
		//$this->set("res",$result);
		
		//$this->set("pp","SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L' and em.Location='$Branch' $CostCenter");
			      
            $this->set("Data1",
			$this->VailidationRejects->query(
                    "SELECT em.EmpCode,em.EmpName,em.Location,em.CostCenter,em.EmpFor,em.Desig,em.EmpType,em.DOJ,dm.* FROM employee_master em LEFT JOIN vw_document dm ON em.SrNo=dm.EmpSrno WHERE ifnull(em.Status,'')!='L'  $Branch $CostCenter"
                           ));
      
            
        
    }
}
?>
