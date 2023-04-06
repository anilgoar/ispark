 <?php
class PendingSalarysController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster','SalarData','DesignationNameMaster','UploadDeductionMaster','IncomtaxMaster','LoanMaster','UploadIncentiveBreakup','OldAttendanceIssue');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export_report','total_employees','show_report','getcostcenter','delete_report');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
		
		if($this->request->is('Post')){
			
			$m			=	$_REQUEST['EmpMonth'];
            $y			=	$_REQUEST['EmpYear'];
            $mwd    	=   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
			
			$data   	=   $this->request->data;

			$branch_name        	=   $data['PendingSalarys']['branch_name'];
			$conditions['active']	=	1;
			
            if($branch_name !="ALL"){$conditions['branch_name']=$branch_name;}else{unset($conditions['branch_name']);}
			
			$BranchArray	=	$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions,'order'=>array('branch_name')));            
		
			$html .='<table class = "table table-striped table-hover  responstable">';
				$html .= '<thead>';
				
				$html .= '<tr>';
				$html .= '<th style="text-align:center;"></th>';
				$html .= '<th style="text-align:center;" colspan="6">Total Salary</th>';
				$html .= '<th style="text-align:center;" colspan="6">Pending</th>';
				$html .= '</tr>';
				
				$html .= '<tr>';
				$html .= '<th style="text-align:center;width:200px;">Branch Name</th>';
				$html .= '<th style="text-align:center;">Agent</th>';
				$html .= '<th style="text-align:center;">Agent Onsite</th>';
				$html .= '<th style="text-align:center;">BMC</th>';
				$html .= '<th style="text-align:center;">DSC</th>';
				//$html .= '<th style="text-align:center;">DSC Onsite</th>';
				$html .= '<th style="text-align:center;">Field</th>';
				$html .= '<th style="text-align:center;">Grand Total</th>';
				$html .= '<th style="text-align:center;">Agent</th>';
				$html .= '<th style="text-align:center;">Agent Onsite</th>';
				$html .= '<th style="text-align:center;">BMC</th>';
				$html .= '<th style="text-align:center;">DSC</th>';
				//$html .= '<th style="text-align:center;">DSC Onsite</th>';
				$html .= '<th style="text-align:center;">Field</th>';
				$html .= '<th style="text-align:center;">Grand Total</th>';
				$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
				
				$TotSal_Agent		=	0;
				$TotSal_AgentOnsite	=	0;
				$TotSal_Bmc			=	0;
				$TotSal_Dsc			=	0;
				$TotSal_DscOnsite	=	0;
				$TotSal_Field		=	0;
				$TotSal_Total		=	0;
				
				$TotPen_Agent		=	0;
				$TotPen_AgentOnsite	=	0;
				$TotPen_Bmc			=	0;
				$TotPen_Dsc			=	0;
				$TotPen_DscOnsite	=	0;
				$TotPen_Field		=	0;
				$TotPen_Total		=	0;
				
				foreach($BranchArray as $branch){

					$total_salary	=	$this->total_salary($SalayDay,$branch,'total_salary');
					$pending_salary	=	$this->total_salary($SalayDay,$branch,'pending_salary');
					
					
					$TotSal_Agent		=	$TotSal_Agent+$total_salary['Agent'];
					$TotSal_AgentOnsite	=	$TotSal_AgentOnsite+$total_salary['Agent Onsite'];
					$TotSal_Bmc			=	$TotSal_Bmc+$total_salary['BMC'];
					$TotSal_Dsc			=	$TotSal_Dsc+$total_salary['DSC'];
					$TotSal_DscOnsite	=	$TotSal_DscOnsite+$total_salary['DSC Onsite'];
					$TotSal_Field		=	$TotSal_Field+$total_salary['Field'];
					$TotSal_Total		=	$TotSal_Total+$total_salary['Total'];
					
					$TotPen_Agent		=	$TotPen_Agent+$pending_salary['Agent'];
					$TotPen_AgentOnsite	=	$TotPen_AgentOnsite+$pending_salary['Agent Onsite'];
					$TotPen_Bmc			=	$TotPen_Bmc+$pending_salary['BMC'];
					$TotPen_Dsc			=	$TotPen_Dsc+$pending_salary['DSC'];
					$TotPen_DscOnsite	=	$TotPen_DscOnsite+$pending_salary['DSC Onsite'];
					$TotPen_Field		=	$TotPen_Field+$pending_salary['Field'];
					$TotPen_Total		=	$TotPen_Total+$pending_salary['Total'];
					
					$html .= '<tr>';
					$html .= '<td style="text-align:center;">'.$branch.'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Agent'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Agent Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['BMC'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['DSC'].'</td>';
					//$html .= '<td style="text-align:center;">'.$total_salary['DSC Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Field'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Total'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Agent'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Agent Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['BMC'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['DSC'].'</td>';
					//$html .= '<td style="text-align:center;">'.$pending_salary['DSC Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Field'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Total'].'</td>';
					$html .= '</tr>';
				}
				
				$html .= '<tr>';
				$html .= '<td style="text-align:center;font-weight:bold;">Grand Total</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_Agent.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_AgentOnsite.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_Bmc.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_Dsc.'</td>';
				//$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_DscOnsite.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_Field.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotSal_Total.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_Agent.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_AgentOnsite.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_Bmc.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_Dsc.'</td>';
				//$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_DscOnsite.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_Field.'</td>';
				$html .= '<td style="text-align:center;font-weight:bold;">'.$TotPen_Total.'</td>';
				$html .= '</tr>';
				$html .= '</tbody>';
			$html .= '</table>';
			
			
			$this->Session->setFlash($html);
			$this->set('EmpMonth',$m);
			$this->set('EmpYear',$y);
			$this->set('branch_name',$branch_name);
		}
        
    }
    
	

    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
			
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Salary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
			
			
            $m			=	$_REQUEST['EmpMonth'];
            $y			=	$_REQUEST['EmpYear'];
            $mwd    	=   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
			$branch_name        	=   $_REQUEST['BranchName'];
			$conditions['active']	=	1;
			
            if($branch_name !="ALL"){$conditions['branch_name']=$branch_name;}else{unset($conditions['branch_name']);}
			
			$BranchArray	=	$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions,'order'=>array('branch_name')));            
		
			$html .='<table class = "table table-striped table-hover  responstable" border="1"  >';
				$html .= '<thead>';
				
				$html .= '<tr>';
				$html .= '<th style="text-align:center;"></th>';
				$html .= '<th style="text-align:center;" colspan="6">Total Salary</th>';
				$html .= '<th style="text-align:center;" colspan="6">Pending</th>';
				$html .= '</tr>';
				
				$html .= '<tr>';
				$html .= '<th style="text-align:center;">Branch Name</th>';
				$html .= '<th style="text-align:center;">Agent</th>';
				$html .= '<th style="text-align:center;">Agent Onsite</th>';
				$html .= '<th style="text-align:center;">BMC</th>';
				$html .= '<th style="text-align:center;">DSC</th>';
				//$html .= '<th style="text-align:center;">DSC Onsite</th>';
				$html .= '<th style="text-align:center;">Field</th>';
				$html .= '<th style="text-align:center;">Grand Total</th>';
				$html .= '<th style="text-align:center;">Agent</th>';
				$html .= '<th style="text-align:center;">Agent Onsite</th>';
				$html .= '<th style="text-align:center;">BMC</th>';
				$html .= '<th style="text-align:center;">DSC</th>';
				//$html .= '<th style="text-align:center;">DSC Onsite</th>';
				$html .= '<th style="text-align:center;">Field</th>';
				$html .= '<th style="text-align:center;">Grand Total</th>';
				$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
				
				$TotSal_Agent		=	0;
				$TotSal_AgentOnsite	=	0;
				$TotSal_Bmc			=	0;
				$TotSal_Dsc			=	0;
				$TotSal_DscOnsite	=	0;
				$TotSal_Field		=	0;
				$TotSal_Total		=	0;
				
				$TotPen_Agent		=	0;
				$TotPen_AgentOnsite	=	0;
				$TotPen_Bmc			=	0;
				$TotPen_Dsc			=	0;
				$TotPen_DscOnsite	=	0;
				$TotPen_Field		=	0;
				$TotPen_Total		=	0;
				
				foreach($BranchArray as $branch){

					$total_salary	=	$this->total_salary($SalayDay,$branch,'total_salary');
					$pending_salary	=	$this->total_salary($SalayDay,$branch,'pending_salary');
					
					
					$TotSal_Agent		=	$TotSal_Agent+$total_salary['Agent'];
					$TotSal_AgentOnsite	=	$TotSal_AgentOnsite+$total_salary['Agent Onsite'];
					$TotSal_Bmc			=	$TotSal_Bmc+$total_salary['BMC'];
					$TotSal_Dsc			=	$TotSal_Dsc+$total_salary['DSC'];
					$TotSal_DscOnsite	=	$TotSal_DscOnsite+$total_salary['DSC Onsite'];
					$TotSal_Field		=	$TotSal_Field+$total_salary['Field'];
					$TotSal_Total		=	$TotSal_Total+$total_salary['Total'];
					
					$TotPen_Agent		=	$TotPen_Agent+$pending_salary['Agent'];
					$TotPen_AgentOnsite	=	$TotPen_AgentOnsite+$pending_salary['Agent Onsite'];
					$TotPen_Bmc			=	$TotPen_Bmc+$pending_salary['BMC'];
					$TotPen_Dsc			=	$TotPen_Dsc+$pending_salary['DSC'];
					$TotPen_DscOnsite	=	$TotPen_DscOnsite+$pending_salary['DSC Onsite'];
					$TotPen_Field		=	$TotPen_Field+$pending_salary['Field'];
					$TotPen_Total		=	$TotPen_Total+$pending_salary['Total'];
					
					$html .= '<tr>';
					$html .= '<td style="text-align:center;">'.$branch.'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Agent'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Agent Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['BMC'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['DSC'].'</td>';
					//$html .= '<td style="text-align:center;">'.$total_salary['DSC Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Field'].'</td>';
					$html .= '<td style="text-align:center;">'.$total_salary['Total'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Agent'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Agent Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['BMC'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['DSC'].'</td>';
					//$html .= '<td style="text-align:center;">'.$pending_salary['DSC Onsite'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Field'].'</td>';
					$html .= '<td style="text-align:center;">'.$pending_salary['Total'].'</td>';
					$html .= '</tr>';
				}
				
				$html .= '<tr>';
				$html .= '<th style="text-align:center;">Grand Total</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_Agent.'</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_AgentOnsite.'</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_Bmc.'</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_Dsc.'</th>';
				//$html .= '<th style="text-align:center;">'.$TotSal_DscOnsite.'</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_Field.'</th>';
				$html .= '<th style="text-align:center;">'.$TotSal_Total.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_Agent.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_AgentOnsite.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_Bmc.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_Dsc.'</th>';
				//$html .= '<th style="text-align:center;">'.$TotPen_DscOnsite.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_Field.'</th>';
				$html .= '<th style="text-align:center;">'.$TotPen_Total.'</th>';
				$html .= '</tr>';
				$html .= '</tbody>';
			$html .= '</table>';
			
		echo $html;die;
		
		}
		
    }
	
	
	
	
    public function total_salary($SalayDay,$branch,$salaryType){
		
		$totalSalary=	array();
		
		
		if($salaryType =="pending_salary"){
			$dataArr   	=   $this->SalarData->query("SELECT NetSalary,EmpCode,AdvTaken FROM `salary_data` WHERE date(SalayDate)='$SalayDay' AND Branch='$branch' AND (ChequeNumber IS NULL OR ChequeNumber='')");
		}
		else{
			$dataArr   	=   $this->SalarData->query("SELECT NetSalary,EmpCode,AdvTaken FROM `salary_data` WHERE date(SalayDate)='$SalayDay' AND Branch='$branch'");
		}
				
		foreach($dataArr as $data){
			if($salaryType =="pending_salary"){
				$NetSalary	=	$data['salary_data']['NetSalary'];
			}
			else{
				$NetSalary	=	$data['salary_data']['NetSalary']+$data['salary_data']['AdvTaken'];
			}
			
			$EmpCode	=	$data['salary_data']['EmpCode'];
			
			
			$TypeArr	=   $this->SalarData->query("SELECT `Type_Of_Employee` FROM `masjclrentry` WHERE EmpCode='".$EmpCode."'");
			$Type		=	$TypeArr[0]['masjclrentry']['Type_Of_Employee'];
			
			$totalSalary[$Type][]=$NetSalary;
			
		}
		
		$Agent			=	array_sum($totalSalary['AGENT']);
		$AgentOnsite	=	array_sum($totalSalary['AGENT OS']);
		$BMC			=	array_sum($totalSalary['BMC']);
		$DSC			=	array_sum($totalSalary['DSC']);
		$DSCOnsite		=	array_sum($totalSalary['DSC OS']);
		$Field			=	array_sum($totalSalary['FIELD']);
		$Total			=	($Agent+$AgentOnsite+$BMC+$DSC+$DSCOnsite+$Field);
		
		return $result=array(
			'Agent'=>$Agent,
			'Agent Onsite'=>$AgentOnsite,
			'BMC'=>$BMC,
			'DSC'=>$DSC,
			'DSC Onsite'=>$DSCOnsite,
			'Field'=>$Field,
			'Total'=>$Total,
		);
    }
    
    
    
    public function getcostcenter(){
        
        $branchName =   $_REQUEST['BranchName'];
        $y          =   $_REQUEST['EmpYear'];
        $m          =   $_REQUEST['EmpMonth'];
            
        $data = $this->ProcessAttendanceMaster->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('BranchName'=>$branchName,'ProcessMonth'=>"$y-$m",'FinializeStatus'=>'Yes')));

        if(!empty($data)){
            echo "<option value=''>Select</option>";
            foreach ($data as $val){
                echo "<option value='$val'>$val</option>";
            }
            die;
        }
        else{
            echo "<option value=''>Select</option>";die;
        }
        
    }
    
    
}
?>