<?php
class ExportEsignaturesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','summary','get_esin_details');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
		
        if($this->request->is('Post')){
			
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ExportEsignature.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $branch_name        =   $this->request->data['ExportEsignatures']['branch_name'];
			$EmployeeStatus     =   1;
			
            //$CostCenter         =   $this->request->data['CostCenter'];
            //$EmployeeType       =   $this->request->data['EmployeeType'];
            //$EmployeeStatus     =   $this->request->data['EmployeeStatus'];
            
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
			if($EmployeeStatus !="ALL"){$conditoin['Status']=$EmployeeStatus;}else{unset($conditoin['Status']);}
            //if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            //if($EmployeeType !="ALL"){$conditoin['EmpType']=$EmployeeType;}else{unset($conditoin['EmpType']);}
            
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','EmpName','DOJ'),'conditions'=>$conditoin,'order'=>array('BranchName')));
            
            echo '<table border="1">';
            echo '<tr>';
			echo '<th style="text-align:center;">BranchName</th>';
            echo '<th style="text-align:center;">EmpCode</th>';
            echo '<th style="text-align:center;">EmpName</th>';
            echo '<th style="text-align:center;">DOJ</th>';
            echo '<th style="text-align:center;">EPF Declaration</th>';
            echo '<th style="text-align:center;">Aadhar</th>';
            echo '<th style="text-align:center;">AppointmentLetter</th>';
            echo '<th style="text-align:center;">AssetsAllotment</th>';
            echo '<th style="text-align:center;">Cancelled Cheque</th>';
            echo '<th style="text-align:center;">Code Of Conduct</th>';
			echo '<th style="text-align:center;">Contrat Form</th>';
            echo '<th style="text-align:center;">POA</th>';
            echo '<th style="text-align:center;">POE</th>';
            echo '<th style="text-align:center;">POI</th>';
            echo '<th style="text-align:center;">Resume</th>';
            echo '<th style="text-align:center;">Total E-Sign Sent</th>';
            echo '<th style="text-align:center;">Total E-Sign received</th>';
            echo '<th style="text-align:center;">Total E-Sing Pending</th>';
            echo '</tr>';
            foreach($data as $row){
				
				$EpfDeclarationForm   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='EpfDeclarationForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$Aadhar   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Aadhar' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$AppointmentLetter   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AppointmentLetter' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$AssetsAllotment   	    =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AssetsAllotment' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$CancelledChequeImage   =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CancelledChequeImage' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$CodeOfConduct   		=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CodeOfConduct' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$ContratForm   			=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ContratForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$POA   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AddressProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$POE   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ProofofEducation' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$POI   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='IDProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				$Resume   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Resume' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
				
				$Pending	=	0;
				$Received	=	0;
				$TotalSend	=	0;
				
				if($EpfDeclarationForm=="Pending"){$Pending=$Pending+1;}
				if($EpfDeclarationForm=="Received"){$Received=$Received+1;}
				
				if($Aadhar=="Pending"){$Pending=$Pending+1;}
				if($Aadhar=="Received"){$Received=$Received+1;}
				
				if($AppointmentLetter=="Pending"){$Pending=$Pending+1;}
				if($AppointmentLetter=="Received"){$Received=$Received+1;}
				
				if($AssetsAllotment=="Pending"){$Pending=$Pending+1;}
				if($AssetsAllotment=="Received"){$Received=$Received+1;}
				
				if($CancelledChequeImage=="Pending"){$Pending=$Pending+1;}
				if($CancelledChequeImage=="Received"){$Received=$Received+1;}
				
				if($CodeOfConduct=="Pending"){$Pending=$Pending+1;}
				if($CodeOfConduct=="Received"){$Received=$Received+1;}
				
				if($ContratForm=="Pending"){$Pending=$Pending+1;}
				if($ContratForm=="Received"){$Received=$Received+1;}
				
				if($POA=="Pending"){$Pending=$Pending+1;}
				if($POA=="Received"){$Received=$Received+1;}
				
				if($POE=="Pending"){$Pending=$Pending+1;}
				if($POE=="Received"){$Received=$Received+1;}
				
				if($POI=="Pending"){$Pending=$Pending+1;}
				if($POI=="Received"){$Received=$Received+1;}
				
				if($Resume=="Pending"){$Pending=$Pending+1;}
				if($Resume=="Received"){$Received=$Received+1;}
				
				$TotalSend=	($Pending+$Received);

                echo '<tr>';
				echo '<td style="text-align:center;">'.$row['Masjclrentry']['BranchName'].'</td>';
                echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpCode'].'</td>';
                echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpName'].'</td>';
                echo '<td style="text-align:center;">'.date('d-M-Y',strtotime($row['Masjclrentry']['DOJ'])).'</td>';
                echo '<td style="text-align:center;">'.$EpfDeclarationForm.'</td>';
				echo '<td style="text-align:center;">'.$Aadhar.'</td>';
				echo '<td style="text-align:center;">'.$AppointmentLetter.'</td>';
				echo '<td style="text-align:center;">'.$AssetsAllotment.'</td>';
				echo '<td style="text-align:center;">'.$CancelledChequeImage.'</td>';
				echo '<td style="text-align:center;">'.$CodeOfConduct.'</td>';
				echo '<td style="text-align:center;">'.$ContratForm.'</td>';
				echo '<td style="text-align:center;">'.$POA.'</td>';
				echo '<td style="text-align:center;">'.$POE.'</td>';
				echo '<td style="text-align:center;">'.$POI.'</td>';
				echo '<td style="text-align:center;">'.$Resume.'</td>';
				echo '<td style="text-align:center;">'.$TotalSend.'</td>';
				echo '<td style="text-align:center;">'.$Received.'</td>';
				echo '<td style="text-align:center;">'.$Pending.'</td>';
                echo '</tr>';
            }
            echo ' </table>';
            die;
        } 
    }
	
	
	public function summary(){
        $this->layout='home';
		
		$branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
		
		if($this->request->is('Post')){			
			$branch_name        	=   $this->request->data['ExportEsignatures']['branch_name'];
			$conditions['active']	=	1;
			
            if($branch_name !="ALL"){$conditions['branch_name']=$branch_name;}else{unset($conditions['branch_name']);}
			
			$BranchArray	=	$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions,'order'=>array('branch_name')));            
		
			$html .='<table class = "table table-striped table-hover  responstable"  >';
				$html .= '<thead>';
				$html .= '<tr>';
				$html .= '<th style="text-align:center;width:200px;">Branch Name</th>';
				$html .= '<th style="text-align:center;">Total EMP</th>';
				$html .= '<th style="text-align:center;">E-Sign Sent</th>';
				$html .= '<th style="text-align:center;">E-Sign to be sent</th>';
				$html .= '<th style="text-align:center;">E-Sign Received</th>';
				$html .= '<th style="text-align:center;">E-Sign Receiving Pending</th>';
				$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
				foreach($BranchArray as $branch){
					$total_emp		=	$this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$branch,'Status'=>1)));
					$result			=	$this->get_esin_count($branch);
					
					$TotalSend		=	$result['TotalSend'];
					$NotSend		=	($total_emp-$TotalSend);
					$Received		=	$result['Received'];
					$Pending		=	$result['Pending'];
					
					$html .= '<tr>';
					$html .= '<td style="text-align:center;"><span  style="color:blue;cursor:pointer;" onclick="get_esin_details('."'$branch'".')">'.$branch.'</span></td>';
					$html .= '<td style="text-align:center;">'.$total_emp.'</td>';
					$html .= '<td style="text-align:center;">'.$TotalSend.'</td>';
					$html .= '<td style="text-align:center;">'.$NotSend.'</td>';
					$html .= '<td style="text-align:center;">'.$Received.'</td>';
					$html .= '<td style="text-align:center;">'.$Pending.'</td>';
					$html .= '</tr>';
				}
				$html .= '</tbody>';
			$html .= ' </table>';
			
			
			$this->Session->setFlash($html);
			$this->set('branch',$branch_name);
		}
		
    }
	
	public function get_esin_count($branch_name){
			    
        $data   	=   $this->Masjclrentry->find('all',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$branch_name,'Status'=>1)));
            
		$Pending	=	0;
		$Received	=	0;
		$TotalSend	=	0;
		
		foreach($data as $row){
			
			$EpfDeclarationForm   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='EpfDeclarationForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$Aadhar   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Aadhar' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$AppointmentLetter   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AppointmentLetter' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$AssetsAllotment   	    =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AssetsAllotment' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$CancelledChequeImage   =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CancelledChequeImage' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$CodeOfConduct   		=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CodeOfConduct' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$ContratForm   			=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ContratForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POA   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AddressProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POE   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ProofofEducation' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POI   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='IDProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$Resume   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Resume' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			
			if($EpfDeclarationForm=="Pending"){$Pending=$Pending+1;}
			if($EpfDeclarationForm=="Received"){$Received=$Received+1;}
			
			if($Aadhar=="Pending"){$Pending=$Pending+1;}
			if($Aadhar=="Received"){$Received=$Received+1;}
			
			if($AppointmentLetter=="Pending"){$Pending=$Pending+1;}
			if($AppointmentLetter=="Received"){$Received=$Received+1;}
			
			if($AssetsAllotment=="Pending"){$Pending=$Pending+1;}
			if($AssetsAllotment=="Received"){$Received=$Received+1;}
			
			if($CancelledChequeImage=="Pending"){$Pending=$Pending+1;}
			if($CancelledChequeImage=="Received"){$Received=$Received+1;}
			
			if($CodeOfConduct=="Pending"){$Pending=$Pending+1;}
			if($CodeOfConduct=="Received"){$Received=$Received+1;}
			
			if($ContratForm=="Pending"){$Pending=$Pending+1;}
			if($ContratForm=="Received"){$Received=$Received+1;}
			
			if($POA=="Pending"){$Pending=$Pending+1;}
			if($POA=="Received"){$Received=$Received+1;}
			
			if($POE=="Pending"){$Pending=$Pending+1;}
			if($POE=="Received"){$Received=$Received+1;}
			
			if($POI=="Pending"){$Pending=$Pending+1;}
			if($POI=="Received"){$Received=$Received+1;}
			
			if($Resume=="Pending"){$Pending=$Pending+1;}
			if($Resume=="Received"){$Received=$Received+1;}
			
			$TotalSend	=	($Pending+$Received);
		}
		
		return  $result	=	array(
					'TotalSend'=>$TotalSend,
					'Received'=>$Received,
					'Pending'=>$Pending,
				);
           		
	}
	
	public function get_esin_details(){
		
		if($_REQUEST['type']=="export"){
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=ExportEsignatureMis.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}

		$conditions['BranchName']	=	$_REQUEST['branch_name'];
		$conditions['Status']	=	1;

		$data   =   $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpName','DOJ'),'conditions'=>$conditions));
		
		echo '<table class = "table table-striped table-hover  responstable" border="1"  >';
		echo '<thead>';
		echo '<tr>';
		echo '<th style="text-align:center;">SrNo</th>';
		echo '<th style="text-align:center;">EmpCode</th>';
		echo '<th style="text-align:center;">EmpName</th>';
		echo '<th style="text-align:center;">DOJ</th>';
		echo '<th style="text-align:center;">EPF Declaration</th>';
		echo '<th style="text-align:center;">Aadhar</th>';
		echo '<th style="text-align:center;">AppointmentLetter</th>';
		echo '<th style="text-align:center;">AssetsAllotment</th>';
		echo '<th style="text-align:center;">Cancelled Cheque</th>';
		echo '<th style="text-align:center;">Code Of Conduct</th>';
		echo '<th style="text-align:center;">Contrat Form</th>';
		echo '<th style="text-align:center;">POA</th>';
		echo '<th style="text-align:center;">POE</th>';
		echo '<th style="text-align:center;">POI</th>';
		echo '<th style="text-align:center;">Resume</th>';
		echo '<th style="text-align:center;">Total E-Sign Sent</th>';
		echo '<th style="text-align:center;">Total E-Sign received</th>';
		echo '<th style="text-align:center;">Total E-Sing Pending</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$i=1;
		
		$Total_Pending	=	0;
		$Total_Received	=	0;
		$Total_Sending	=	0;
		
		foreach($data as $row){
			
			$EpfDeclarationForm   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='EpfDeclarationForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$Aadhar   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Aadhar' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$AppointmentLetter   	=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AppointmentLetter' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$AssetsAllotment   	    =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AssetsAllotment' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$CancelledChequeImage   =   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CancelledChequeImage' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$CodeOfConduct   		=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='CodeOfConduct' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$ContratForm   			=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ContratForm' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POA   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='AddressProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POE   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='ProofofEducation' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$POI   					=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='IDProof' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			$Resume   				=   $this->Masjclrentry->query("SELECT EsignatureStatus FROM `Esignature_Document_Master` WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DocName='Resume' ORDER BY Id DESC LIMIT 1")[0]['Esignature_Document_Master']['EsignatureStatus'];
			
			$Pending	=	0;
			$Received	=	0;
			$TotalSend	=	0;
			
			if($EpfDeclarationForm=="Pending"){$Pending=$Pending+1;}
			if($EpfDeclarationForm=="Received"){$Received=$Received+1;}
			
			if($Aadhar=="Pending"){$Pending=$Pending+1;}
			if($Aadhar=="Received"){$Received=$Received+1;}
			
			if($AppointmentLetter=="Pending"){$Pending=$Pending+1;}
			if($AppointmentLetter=="Received"){$Received=$Received+1;}
			
			if($AssetsAllotment=="Pending"){$Pending=$Pending+1;}
			if($AssetsAllotment=="Received"){$Received=$Received+1;}
			
			if($CancelledChequeImage=="Pending"){$Pending=$Pending+1;}
			if($CancelledChequeImage=="Received"){$Received=$Received+1;}
			
			if($CodeOfConduct=="Pending"){$Pending=$Pending+1;}
			if($CodeOfConduct=="Received"){$Received=$Received+1;}
			
			if($ContratForm=="Pending"){$Pending=$Pending+1;}
			if($ContratForm=="Received"){$Received=$Received+1;}
			
			if($POA=="Pending"){$Pending=$Pending+1;}
			if($POA=="Received"){$Received=$Received+1;}
			
			if($POE=="Pending"){$Pending=$Pending+1;}
			if($POE=="Received"){$Received=$Received+1;}
			
			if($POI=="Pending"){$Pending=$Pending+1;}
			if($POI=="Received"){$Received=$Received+1;}
			
			if($Resume=="Pending"){$Pending=$Pending+1;}
			if($Resume=="Received"){$Received=$Received+1;}
			
			$TotalSend=	($Pending+$Received);
			
			
			$Total_Pending	=	$Total_Pending+$Pending;
			$Total_Received	=	$Total_Received+$Received;
			$Total_Sending	=	$Total_Sending+$TotalSend;

			echo '<tr>';
			echo '<td style="text-align:center;">'.$i++.'</td>';
			echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpCode'].'</td>';
			echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpName'].'</td>';
			echo '<td style="text-align:center;">'.date('d-M-Y',strtotime($row['Masjclrentry']['DOJ'])).'</td>';
			echo '<td style="text-align:center;">'.$EpfDeclarationForm.'</td>';
			echo '<td style="text-align:center;">'.$Aadhar.'</td>';
			echo '<td style="text-align:center;">'.$AppointmentLetter.'</td>';
			echo '<td style="text-align:center;">'.$AssetsAllotment.'</td>';
			echo '<td style="text-align:center;">'.$CancelledChequeImage.'</td>';
			echo '<td style="text-align:center;">'.$CodeOfConduct.'</td>';
			echo '<td style="text-align:center;">'.$ContratForm.'</td>';
			echo '<td style="text-align:center;">'.$POA.'</td>';
			echo '<td style="text-align:center;">'.$POE.'</td>';
			echo '<td style="text-align:center;">'.$POI.'</td>';
			echo '<td style="text-align:center;">'.$Resume.'</td>';
			echo '<td style="text-align:center;">'.$TotalSend.'</td>';
			echo '<td style="text-align:center;">'.$Received.'</td>';
			echo '<td style="text-align:center;">'.$Pending.'</td>';
			echo '</tr>';
		}
		echo '<tr>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;"></td>';
			echo '<td style="text-align:center;font-weight:bold;">Total</td>';
			echo '<td style="text-align:center;font-weight:bold;">'.$Total_Sending.'</td>';
			echo '<td style="text-align:center;font-weight:bold;">'.$Total_Received.'</td>';
			echo '<td style="text-align:center;font-weight:bold;">'.$Total_Pending.'</td>';
			echo '</tr>';
		echo '<tbody>';
		echo ' </table>';

		die;
        
    }
	
    
    
    
    
}
?>