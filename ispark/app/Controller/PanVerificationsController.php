<?php
class PanVerificationsController extends AppController {
    public $uses = array('PanVerificationMaster','Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','viewdetails','panapi','export','getcostcenter');
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
            $branch_name    =   $this->request->data['PanVerifications']['branch_name'];
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
			$dataArr=	array();
			foreach($data as $row){
				$arr_data	=	$this->PanVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$row['Masjclrentry']['EmpCode'])));

				$dataArr[]=	array(
					'id'=>$row['Masjclrentry']['id'],
					'EmpCode'=>$row['Masjclrentry']['EmpCode'],
					'EmpName'=>$row['Masjclrentry']['EmpName'],
					'DOJ'=>$row['Masjclrentry']['DOJ'],
					'Desgination'=>$row['Masjclrentry']['Desgination'],
					'EmpLocation'=>$row['Masjclrentry']['EmpLocation'],
					'Status'=>$arr_data['PanVerificationMaster']['search_status'],
				);
			}
			
            $this->set('data',$dataArr);
        }  
		
    }
    
    public function viewdetails(){
        $this->layout='home';
        
		
        if(isset($_REQUEST['EC'])){
			$EmpCode 	= 	base64_decode($_REQUEST['EC']);
            $arr_data	=	$this->PanVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$EmpCode)));
            $this->set('row',$arr_data['PanVerificationMaster']);
			
			$data   =   $this->Masjclrentry->find('first',array('fields'=>array('PanNo'),'conditions'=>array('EmpCode'=>$EmpCode))); 
			$this->set('data',$data['Masjclrentry']);
        }

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
    }
	
	public function panapi($pan_no){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://preprod.aadhaarapi.com/pan",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\r\n\t\"pan\":\"$pan_no\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: e608dffe-1ee9-7f8f-eef4-479a59a3e2ef",
            "qt_agency_id: 2fae9f4e-0b86-4d47-a74a-35bd400b9839",
            "qt_api_key: ed2ec6e0-a7bd-4d57-97fc-3a4b2962f512"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
          return $response;
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
            header("Content-Disposition: attachment; filename=ExportPanVerification.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
			
            
            $branch_name        =   $this->request->data['PanVerifications']['branch_name'];
			$EmployeeStatus     =   1;

            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
			if($EmployeeStatus !="ALL"){$conditoin['Status']=$EmployeeStatus;}else{unset($conditoin['Status']);}
           
            
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','EmpName','DOJ'),'conditions'=>$conditoin,'order'=>array('BranchName')));
            
            echo '<table border="1">';
            echo '<tr>';
			echo '<th style="text-align:center;">BranchName</th>';
            echo '<th style="text-align:center;">EmpCode</th>';
            echo '<th style="text-align:center;">EmpName</th>';
            echo '<th style="text-align:center;">DOJ</th>';
            echo '<th style="text-align:center;">Status</th>';
            echo '</tr>';
            foreach($data as $row){
				
				$status	=	$this->PanVerificationMaster->find('first',array('fields'=>array('search_status'),'conditions'=>array('emp_code'=>$row['Masjclrentry']['EmpCode'])));
				$status	=	$status['PanVerificationMaster']['search_status'] !=""?$status['PanVerificationMaster']['search_status']:"";
				
                echo '<tr>';
				echo '<td style="text-align:center;">'.$row['Masjclrentry']['BranchName'].'</td>';
                echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpCode'].'</td>';
                echo '<td style="text-align:center;">'.$row['Masjclrentry']['EmpName'].'</td>';
                echo '<td style="text-align:center;">'.date('d-M-Y',strtotime($row['Masjclrentry']['DOJ'])).'</td>';
				echo '<td style="text-align:center;">'.$status.'</td>';
                echo '</tr>';
            }
            echo ' </table>';
            die;
        } 
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
	
	

    
    
 
    
}
?>