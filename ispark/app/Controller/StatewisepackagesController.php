<?php
class StatewisepackagesController extends AppController 
{
    public $uses = array('Addbranch','User','maspackage','maspackagestatewise','masband','Masjclrentry','MasJclrMaster','BandNameMaster','CostCenterMaster','StateMaster');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index','editstatewisepackage','get_state_wise_package_type_list','viewpackage','get_design','editpackage','uploadattend','exportsalary','get_design','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess','Savefile','showfile','discardsalary','discardincentive','salarybreakup','get_salary','updatesalary','getcostcenter');
          
        }
    }
	
    public function index(){
		$this->layout	=	"home";
        $branchName 	= 	$this->Session->read('branch_name');
		$this->set('branchName',$this->StateMaster->find('list',array('fields'=>array('name','name'),'order'=>'name')));
		$this->set('TypeList',array('Unskilled'=>'Unskilled','SEMI SKILLED'=>'SEMI SKILLED','SKILLED'=>'SKILLED','Highly skilled'=>'Highly skilled','Zone I - Unskilled'=>'Zone I - Unskilled','Zone I - Semi-skilled'=>'Zone I - Semi-skilled','Zone I - Skilled'=>'Zone I - Skilled','Zone II - Unskilled'=>'Zone II - Unskilled','Zone II - Semi-skilled'=>'Zone II - Semi-skilled','Zone II - Skilled'=>'Zone II - Skilled'));
		
        if($this->request->is('post')){
			$user	= 	$this->Session->read('userid');
			$data	=	$this->request->data;
			
			$StateName		=	$data['maspackagestatewise']['StateName'];
			$PackageType	=	$data['maspackagestatewise']['PackageType'];
			$PackageAmount	=	$data['maspackagestatewise']['PackageAmount'];
			
			$exist = $this->maspackagestatewise->find('count',array('conditions'=>array('StateName'=>$StateName,'PackageType'=>$PackageType,'PackageAmount'=>$PackageAmount)));
        
			if($exist > 0){
				$this->Session->setFlash("<span style='color:red;font-size:12px;font-weight:bold;'>This package already exist in database.</span>"); 
			}
			else{  
				$data['maspackagestatewise']['CreateBy']=$user;
				if($this->maspackagestatewise->saveall($data)){
					$this->Session->setFlash("<span style='color:green;font-size:12px;font-weight:bold;'>Package save successfully</span>"); 
				}
				else {
					$this->Session->setFlash("<span style='color:green;font-size:12px;font-weight:bold;'>Package is not Save</span>");  
					
				}
			}
			
			return $this->redirect(array('action'=>'index'));						
        }
       
    }
	
	public function editstatewisepackage(){
        $this->layout='home';
        $this->set('branchName',$this->StateMaster->find('list',array('fields'=>array('name','name'),'order'=>'name')));
		$this->set('TypeList',array('Unskilled'=>'Unskilled','SEMI SKILLED'=>'SEMI SKILLED','SKILLED'=>'SKILLED','Highly skilled'=>'Highly skilled','Zone I - Unskilled'=>'Zone I - Unskilled','Zone I - Semi-skilled'=>'Zone I - Semi-skilled','Zone I - Skilled'=>'Zone I - Skilled','Zone II - Unskilled'=>'Zone II - Unskilled','Zone II - Semi-skilled'=>'Zone II - Semi-skilled','Zone II - Skilled'=>'Zone II - Skilled'));
		
		
        if($this->request->is('Post')){ 
            $branch_name    =   $this->request->data['Statewisepackages']['branch_name'];
            $SearchType     =   $this->request->data['Statewisepackages']['PackageType'];
			
			if($SearchType !="ALL"){
				$data   =   $this->maspackagestatewise->find('all',array('conditions'=>array('StateName'=>$branch_name,'PackageType'=>$SearchType))); 
			}
			else{
				$data   =   $this->maspackagestatewise->find('all',array('conditions'=>array('StateName'=>$branch_name))); 
			}
			
			$this->set('statename',$branch_name);
			$this->set('statetype',$SearchType);
            $this->set('data',$data);
        }  
    }
	
	public function viewpackage(){
		$this->layout	=	"home";
         $branchName 	= 	$this->Session->read('branch_name');
		$this->set('branchName',$this->StateMaster->find('list',array('fields'=>array('name','name'),'order'=>'name')));
		$this->set('TypeList',array('A'=>'A','B'=>'B','C'=>'C'));
		
		if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
			$dataList   =   $this->maspackagestatewise->find('first',array('conditions'=>array('id'=>$_REQUEST['id']))); 
			$this->set('dt',$dataList['maspackagestatewise']);
		}
		
        if($this->request->is('post')){
			
            $user			= 	$this->Session->read('userid');
			$data			=	$this->request->data;
			$StateName		=	$data['maspackagestatewise']['StateName'];
			$PackageType	=	$data['maspackagestatewise']['PackageType'];
			$PackageAmount	=	$data['maspackagestatewise']['PackageAmount'];
			$id				=	$data['packageid'];
			
			$exist = $this->maspackagestatewise->find('count',array('conditions'=>array('id !='=>$id,'StateName'=>$StateName,'PackageType'=>$PackageType,'PackageAmount'=>$PackageAmount)));
        
			if($exist > 0){
				$this->Session->setFlash("<span style='color:red;font-size:12px;font-weight:bold;'>This package already exist in database.</span>"); 
			}
			else{ 
			
				foreach($data['maspackagestatewise'] as $key=>$val){
					$dataArr[$key]="'".$val."'";
				}
				
				$dataArr['updateDate']="'".date('Y-m-d H:i:s')."'";
				$dataArr['updateBy']="'".$user."'";
			
				if($this->maspackagestatewise->updateAll($dataArr,array('id'=>$id))){
					$this->Session->setFlash("<span style='color:green;font-size:12px;font-weight:bold;'>Package update successfully</span>"); 
				}
				else {
					$this->Session->setFlash("<span style='color:green;font-size:12px;font-weight:bold;'>Package is not update</span>");  
					
				}
			}
			
			return $this->redirect(array('action'=>'viewpackage','?'=>array('id'=>$id)));						
        }
    }
	
	public function get_state_wise_package_type_list(){
		
		$package_list	=	$this->maspackagestatewise->query("SELECT PackageType FROM `mas_package_type_state_wise` WHERE StateName='".$_REQUEST['StateName']."'");
		
		echo "<option value='' >Select</option>";
		foreach($package_list as $val){
			$PackageType	=	$val['mas_package_type_state_wise']['PackageType'];
			$Selected		=	$PackageType ==$_REQUEST['StateValue']?"selected='selected'":'';
			
			echo "<option $Selected value='$PackageType'>$PackageType</option>";
		}
		die;
	}
    
	
}

?>