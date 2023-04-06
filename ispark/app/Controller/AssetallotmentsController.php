<?php
class AssetallotmentsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','EmpAssetsAllotmentMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','viewdetails','getcostcenter','editdetails','export');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';

        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
			$this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
		
		if($this->request->is('Post')){ 
            $branch_name    =   $this->request->data['Assetallotments']['branch_name'];
            $SearchType     =   $this->request->data['SearchType'];
            $SearchValue    =   trim($this->request->data['SearchValue']);
            $CostCenter    =   trim($this->request->data['CostCenter']);
            
            $conditoin=array(
                'Status'=>1,
               	//'OR'=> array('documentDone is null', 'documentDone'=>'No','documentDone'=>''), 
            );
            
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            if($SearchType =="EmpName"){$conditoin['EmpName LIKE']=$SearchValue.'%';}else{unset($conditoin['EmpName LIKE']);}
            if($SearchType =="EmpCode"){$conditoin['EmpCode']=$SearchValue;}else{unset($conditoin['EmpCode']);}
            if($SearchType =="BioCode"){$conditoin['BioCode']=$SearchValue;}else{unset($conditoin['BioCode']);}
                
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('id','EmpCode','EmpName','DOJ','Desgination','EmpLocation'),'conditions'=>$conditoin)); 
            $this->set('data',$data);
        }
        
    } 
	
	public function viewdetails(){
        $this->layout='home';
		
        $this->set('assetList',array('Laptop','Computer','Keyboard','Mouse','Monitor','UPS','Dongle','Mobile','SimCard','Security'));
		$this->set('vendorList',array('Goodwill','A-One','Swastik','RMV','MAS','PMS'));
		$this->set('vendorList1',array('Vodafone','Airtel','JIO','Idea'));
		
        if(isset($_REQUEST['EC'])){
			$EmpCode 	= 	base64_decode($_REQUEST['EC']);
            $arr_data	=	$this->Masjclrentry->query("SELECT * FROM `Emp_Assets_Allotment_Master` WHERE EmpCode='$EmpCode' AND ReceiveStatus IS NULL");
            
			$AssArr		=	array();
			foreach($arr_data as $row){
				$AssArr[$row['Emp_Assets_Allotment_Master']['Assets']]=$row['Emp_Assets_Allotment_Master'];
			}
			
			$this->set('assetData',$AssArr);
			
			$AssetsExist	=	$this->EmpAssetsAllotmentMaster->find('list',array('fields'=>array('Assets'),'conditions'=>array('EmpCode'=>$EmpCode,'ReceiveStatus'=>NULL))); 
			$this->set('AssetsExist',$AssetsExist);
        }
		
		
		if($this->request->is('Post')){
		
			$user 					= 	$this->Session->read('userid');
			$EmpCode    			=   $_REQUEST['EmpCode'];
			$TotalCost     			=   $_REQUEST['TotalCost'];
			
			if(!empty($_REQUEST['assets'])){
				
				foreach($_REQUEST['assets'] as $key=>$val){
					
					$Assets     	=   $key;
					$Vendor  		=   isset($_REQUEST[$key.'Vendor'])?$_REQUEST[$key.'Vendor']:NULL;
					$AllocateDate  	=   isset($_REQUEST[$key.'AllocateDate'])?$_REQUEST[$key.'AllocateDate']:NULL;
					$ModelNo  		=   isset($_REQUEST[$key.'ModelNo'])?$_REQUEST[$key.'ModelNo']:NULL;
					$SerialNo  		=   isset($_REQUEST[$key.'SerialNo'])?$_REQUEST[$key.'SerialNo']:NULL;
					$Coniguration	=   isset($_REQUEST[$key.'Coniguration'])?$_REQUEST[$key.'Coniguration']:NULL;
					$Cost  			=   isset($_REQUEST[$key.'Cost'])?$_REQUEST[$key.'Cost']:NULL;
					
					$SimCardType  	=   isset($_REQUEST[$key.'Type'])?$_REQUEST[$key.'Type']:NULL;
					$SimCardNumber  =   isset($_REQUEST[$key.'Number'])?$_REQUEST[$key.'Number']:NULL;
					$SimCardLimit  	=   isset($_REQUEST[$key.'Limit'])?$_REQUEST[$key.'Limit']:NULL;
					$SimCardCost  	=   isset($_REQUEST[$key.'Cost'])?$_REQUEST[$key.'Cost']:NULL;
					
					$AccountHolder  =   isset($_REQUEST[$key.'AccountHolder'])?$_REQUEST[$key.'AccountHolder']:NULL;
					$BankName  		=   isset($_REQUEST[$key.'BankName'])?$_REQUEST[$key.'BankName']:NULL;
					$AccountNo  	=   isset($_REQUEST[$key.'AccountNo'])?$_REQUEST[$key.'AccountNo']:NULL;
					$ChequeNo  		=   isset($_REQUEST[$key.'ChequeNo'])?$_REQUEST[$key.'ChequeNo']:NULL;
					$ChequeAmount  	=   isset($_REQUEST[$key.'ChequeAmount'])?$_REQUEST[$key.'ChequeAmount']:NULL;
					

					/*
					$SimCardType  	=   $key=="SimCard"?$_REQUEST['SimCardType']:NULL;
					$SimCardNumber  =   $key=="SimCard"?$_REQUEST['SimCardNumber']:NULL;
					$SimCardLimit	=   $key=="SimCard"?$_REQUEST['SimCardLimit']:NULL;
					$SimCardCost	=   $key=="SimCard"?$_REQUEST['SimCardCost']:NULL;
					
					$AccountHolder  =   $key=="Security"?$_REQUEST['AccountHolder']:NULL;
					$BankName  		=   $key=="Security"?$_REQUEST['BankName']:NULL;
					$AccountNo		=   $key=="Security"?$_REQUEST['AccountNo']:NULL;
					$ChequeNo		=   $key=="Security"?$_REQUEST['ChequeNo']:NULL;
					$ChequeAmount	=   $key=="Security"?$_REQUEST['ChequeAmount']:NULL;
					*/
					
					if($val =="Allocate"){
						
						$exist	=	$this->EmpAssetsAllotmentMaster->find('first',array('fields'=>array('Id'),'conditions'=>array('EmpCode'=>$EmpCode,'Assets'=>$Assets,'ReceiveStatus'=>NULL))); 
		
						if(!empty($exist)){
							
							$Id	=	$exist['EmpAssetsAllotmentMaster']['Id'];
							
							$this->EmpAssetsAllotmentMaster->query("UPDATE `Emp_Assets_Allotment_Master` SET `Vendor`='$Vendor',`AllocateDate`='$AllocateDate',`ModelNo`='$ModelNo',`SerialNo`='$SerialNo',`Coniguration`='$Coniguration',`Cost`='$Cost',`SimCardType`='$SimCardType',`SimCardNumber`='$SimCardNumber',`SimCardLimit`='$SimCardLimit',`SimCardCost`='$SimCardCost',`AccountHolder`='$AccountHolder',`BankName`='$BankName',`AccountNo`='$AccountNo',`ChequeNo`='$ChequeNo',`ChequeAmount`='$ChequeAmount',`TotalCost`='$TotalCost',`UpdateDate`=NOW(),`UpdateBy`='$user' WHERE Id='$Id'");	
						}
						else{
							$this->EmpAssetsAllotmentMaster->query("INSERT INTO `Emp_Assets_Allotment_Master` SET `EmpCode`='$EmpCode',`Assets`='$Assets',`Vendor`='$Vendor',`AllocateDate`='$AllocateDate',`ModelNo`='$ModelNo',`SerialNo`='$SerialNo',`Coniguration`='$Coniguration',`Cost`='$Cost',`SimCardType`='$SimCardType',`SimCardNumber`='$SimCardNumber',`SimCardLimit`='$SimCardLimit',`SimCardCost`='$SimCardCost',`AccountHolder`='$AccountHolder',`BankName`='$BankName',`AccountNo`='$AccountNo',`ChequeNo`='$ChequeNo',`ChequeAmount`='$ChequeAmount',`TotalCost`='$TotalCost',`CreateBy`='$user'");
						}
					}
					else{
						
						$exist			=	$this->EmpAssetsAllotmentMaster->find('first',array('fields'=>array('Id'),'conditions'=>array('EmpCode'=>$EmpCode,'Assets'=>$Assets,'ReceiveStatus'=>NULL))); 
		
						if(!empty($exist)){
							
							$ReceiveDate  	=   isset($_REQUEST[$key.'ReceiveDate'])?$_REQUEST[$key.'ReceiveDate']:NULL;
							$ReceiveBy  	=   isset($_REQUEST[$key.'ReceiveBy'])?$_REQUEST[$key.'ReceiveBy']:NULL;
							$Remarks  		=   isset($_REQUEST[$key.'Remarks'])?$_REQUEST[$key.'Remarks']:NULL;
							$Id				=	$exist['EmpAssetsAllotmentMaster']['Id'];
							
							$this->EmpAssetsAllotmentMaster->query("UPDATE `Emp_Assets_Allotment_Master` SET `ReceiveDate`='$ReceiveDate',`ReceiveBy`='$ReceiveBy',`Remarks`='$Remarks',`ReceiveStatus`='Receive',`UpdateDate`=NOW(),`UpdateBy`='$user' WHERE Id='$Id'");	
						}
						
					}
					
				}
				$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Assets details save successflly.</span>');
			}
			else{
				$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select assets details.</span>');
			}
				
			$this->redirect(array('action'=>'viewdetails','?'=>array('EC'=>base64_encode($EmpCode))));
        }  
		
		/*
        if($this->request->is('Post')){
			
			$userid 	= 	$this->Session->read('userid');
			$EmpCode	=	$_REQUEST['EmpCode'];
			$PanNo		=	$_REQUEST['PanNo'];
			
			$result		=	$this->panapi($PanNo);
          
			$Response           =   json_decode($result,true);

			$id                 =   isset($Response['id'])?addslashes(utf8_decode($Response['id'])):NULL;
			$response_code      =   isset($Response['response_code'])?$Response['response_code']:NULL;
			$pan_number         =   isset($Response['data'][0]['pan_number'])?$Response['data'][0]['pan_number']:NULL;
			$pan_status         =   isset($Response['data'][0]['pan_status'])?$Response['data'][0]['pan_status']:NULL;
			$first_name         =   isset($Response['data'][0]['first_name'])?$Response['data'][0]['first_name']:NULL;
			$middle_name        =   isset($Response['data'][0]['middle_name'])?$Response['data'][0]['middle_name']:NULL;
			$last_name          =   isset($Response['data'][0]['last_name'])?$Response['data'][0]['last_name']:NULL;
			$pan_holder_title   =   isset($Response['data'][0]['pan_holder_title'])?$Response['data'][0]['pan_holder_title']:NULL;
			$pan_last_updated   =   isset($Response['data'][0]['pan_last_updated'])?addslashes(utf8_decode($Response['data'][0]['pan_last_updated'])):NULL;
			$pan_count          =   isset($Response['pan_count'])?$Response['pan_count']:NULL;
			$pan_success_count  =   isset($Response['pan_success_count'])?$Response['pan_success_count']:NULL;
			$transaction_status =   isset($Response['transaction_status'])?$Response['transaction_status']:NULL;
			$request_timestamp  =   isset($Response['request_timestamp'])?addslashes(utf8_decode($Response['request_timestamp'])):NULL;
			$response_timestamp =   isset($Response['response_timestamp'])?addslashes(utf8_decode($Response['response_timestamp'])):NULL;
			
			
			$exist=$this->PanVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$EmpCode)));
            if(empty($exist)){
				$data=array(
					'search_description'=>$result,
					'emp_code'=>$EmpCode,
					'search_data'=>$PanNo,
					'search_status'=>$pan_status,
					'id_no'=>$id,
					'response_code'=>$response_code,
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'pan_holder_title'=>$pan_holder_title,
					'pan_last_updated'=>$pan_last_updated,
					'pan_count'=>$pan_count,
					'pan_success_count'=>$pan_success_count,
					'transaction_status'=>$transaction_status,
					'request_timestamp'=>$request_timestamp,
					'response_timestamp'=>$response_timestamp,
					'created_by'=>$userid
				);

				$this->PanVerificationMaster->saveAll($data);
                
			}
			else{
				$data=array(
					'search_description'=>"'".$result."'",
					'search_data'=>"'".$PanNo."'",
					'search_status'=>"'".$pan_status."'",
					'id_no'=>"'".$id."'",
					'response_code'=>"'".$response_code."'",
					'first_name'=>"'".$first_name."'",
					'last_name'=>"'".$last_name."'",
					'pan_holder_title'=>"'".$pan_holder_title."'",
					'pan_last_updated'=>"'".$pan_last_updated."'",
					'pan_count'=>"'".$pan_count."'",
					'pan_success_count'=>"'".$pan_success_count."'",
					'transaction_status'=>"'".$transaction_status."'",
					'request_timestamp'=>"'".$request_timestamp."'",
					'response_timestamp'=>"'".$response_timestamp."'",
					'updated_at'=>"'".date('Y-m-d H:i:s')."'",
					'updated_by'=>"'".$userid."'"
				);

				$this->PanVerificationMaster->updateAll($data,array('emp_code'=>$EmpCode));  

			}
			
			$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Pan verification process successfully.</span>');	
			$this->redirect(array('action'=>'viewdetails','?'=>array('EC'=>base64_encode($EmpCode))));
        } 
		*/

		
    }
	
	public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
        
        
    }
	
	
	public function export(){
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
            header("Content-Disposition: attachment; filename=ExportAssetsAllotment.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
			
            $branch_name        =   $this->request->data['Assetallotments']['branch_name'];
			$EmployeeStatus     =   1;

            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
			if($EmployeeStatus !="ALL"){$conditoin['Status']=$EmployeeStatus;}else{unset($conditoin['Status']);}
           
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','EmpName','DOJ'),'conditions'=>$conditoin,'order'=>array('BranchName')));
			
            echo '<table border="1">';
            echo '<tr>';
            echo '<th style="text-align:center;">Emp Code</th>';
            echo '<th style="text-align:center;">Employee Name</th>';
			echo '<th style="text-align:center;">Branch</th>';
            echo '<th style="text-align:center;">Assets</th>';
            echo '<th style="text-align:center;">Vendor</th>';
			echo '<th style="text-align:center;">Modle No</th>';
			echo '<th style="text-align:center;">Serial No</th>';
			echo '<th style="text-align:center;">Configuration</th>';
			echo '<th style="text-align:center;">Cost</th>';
			echo '<th style="text-align:center;">Sim Card Type</th>';
			echo '<th style="text-align:center;">Sim Card Number</th>';
			echo '<th style="text-align:center;">Sim  Card  Limit</th>';
			echo '<th style="text-align:center;">Asset Allotment date</th>';
			echo '<th style="text-align:center;">Asset Return date</th>';
			echo '<th style="text-align:center;">Asset Hand over Persion</th>';
			echo '<th style="text-align:center;">Remarks</th>';
			echo '<th style="text-align:center;">Account Holder</th>';
			echo '<th style="text-align:center;">Bank Name</th>';
			echo '<th style="text-align:center;">Account No</th>';
			echo '<th style="text-align:center;">Cheque Number</th>';
			echo '<th style="text-align:center;">Cheque Amount</th>';
            echo '</tr>';
            foreach($data as $row){
				$AssetsArr	=	$this->EmpAssetsAllotmentMaster->find('all',array('conditions'=>array('EmpCode'=>$row['Masjclrentry']['EmpCode']),'order'=>array('EmpCode')));
				
				foreach($AssetsArr as $Assets){
					$asset	=	$Assets['EmpAssetsAllotmentMaster'];
					echo '<tr>';
					echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpCode'].'</td>';
					echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpName'].'</td>';
					echo '<td style="text-align:center;">'.$row['Masjclrentry']['BranchName'].'</td>';
					echo '<td style="text-align:center;">'.$asset['Assets'].'</td>';
					echo '<td style="text-align:center;">'.$asset['Vendor'].'</td>';
					echo '<td style="text-align:center;">'.$asset['ModelNo'].'</td>';
					echo '<td style="text-align:center;">'.$asset['SerialNo'].'</td>';
					echo '<td style="text-align:center;">'.$asset['Coniguration'].'</td>';
					echo '<td style="text-align:center;">'.$asset['Cost'].'</td>';
					echo '<td style="text-align:center;">'.$asset['SimCardType'].'</td>';
					echo '<td style="text-align:center;">'.$asset['SimCardNumber'].'</td>';
					echo '<td style="text-align:center;">'.$asset['SimCardLimit'].'</td>';
					echo '<td style="text-align:center;">'.$asset['AllocateDate'].'</td>';
					echo '<td style="text-align:center;">'.$asset['ReceiveDate'].'</td>';
					echo '<td style="text-align:center;">'.$asset['ReceiveBy'].'</td>';
					echo '<td style="text-align:center;">'.$asset['Remarks'].'</td>';
					echo '<td style="text-align:center;">'.$asset['AccountHolder'].'</td>';
					echo '<td style="text-align:center;">'.$asset['BankName'].'</td>';
					echo '<td style="text-align:center;">'.$asset['AccountNo'].'</td>';
					echo '<td style="text-align:center;">'.$asset['ChequeNo'].'</td>';
					echo '<td style="text-align:center;">'.$asset['ChequeAmount'].'</td>';
					
					echo '</tr>';
				}
				
            }
			
			
			
            echo ' </table>';
            die;
			
        } 
    }
	
	
}
?>