<?php
class BankAccountVerificationsController extends AppController {
    public $uses = array('PanVerificationMaster','BankAccountVerificationMaster','Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','viewdetails','export','getcostcenter');
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
            $branch_name    =   $this->request->data['BankAccountVerifications']['branch_name'];
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
				$arr_data	=	$this->BankAccountVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$row['Masjclrentry']['EmpCode'])));

				$dataArr[]=	array(
					'id'=>$row['Masjclrentry']['id'],
					'EmpCode'=>$row['Masjclrentry']['EmpCode'],
					'EmpName'=>$row['Masjclrentry']['EmpName'],
					'DOJ'=>$row['Masjclrentry']['DOJ'],
					'Desgination'=>$row['Masjclrentry']['Desgination'],
					'EmpLocation'=>$row['Masjclrentry']['EmpLocation'],
					'Status'=>$arr_data['BankAccountVerificationMaster']['cf_status'],
				);
			}
			
			
		
            $this->set('data',$dataArr);
        }  
		
    }
    
    public function viewdetails(){
        $this->layout='home';

        if(isset($_REQUEST['EC'])){
			$EmpCode 	= 	base64_decode($_REQUEST['EC']);
            $arr_data	=	$this->BankAccountVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$EmpCode)));
            $this->set('row',$arr_data['BankAccountVerificationMaster']);
			
			$data   =   $this->Masjclrentry->find('first',array('fields'=>array('AccHolder','Mobile','AcNo','IFSCCode'),'conditions'=>array('EmpCode'=>$EmpCode))); 
			$this->set('data',$data['Masjclrentry']);
			
        }

        if($this->request->is('Post')){
			
			$userid 		= 	$this->Session->read('userid');
			$EmpCode		=	$_REQUEST['EmpCode'];
			
			$ben_name   	= 	$bank_details['name']           = $_REQUEST['ben_name'];
			$phone      	= 	$bank_details['phone']          = $_REQUEST['ben_phone'];
			$acc_no     	= 	$bank_details['bankAccount']    = $_REQUEST['bank_account'];
			$ifsc     		= 	$bank_details['ifsc']           = $_REQUEST['ifsc'];
			$created_by     =   $userid;
			
			//$web = "https://payout-gamma.cashfree.com";
			$web = "https://payout-api.cashfree.com";
			
			$api_url_bank_validate 	= 	$web."/payout/v1/validation/bankDetails";
			$Head_Master 			= 	$this->get_token($web);
			$cf_verify_ifsc_json 	= 	$this->call_curl_get_params($api_url_bank_validate,$Head_Master,$bank_details);
			$cf_verify_ifsc_status 	= 	json_decode(trim($cf_verify_ifsc_json),true);
			$cf_status 				= 	$cf_verify_ifsc_status['status'];
		
			$exist=$this->BankAccountVerificationMaster->find('first',array('conditions'=>array('emp_code'=>$EmpCode)));
            if(empty($exist)){
				$data=array(
					'emp_code'=>$EmpCode,
					'ben_name'=>$ben_name,
					'phone'=>$phone,
					'acc_no'=>$acc_no,
					'ifsc'=>$ifsc,
					'cf_status'=>$cf_status,
					'description'=>$cf_verify_ifsc_json,
					'created_by'=>$userid
				);

				$this->BankAccountVerificationMaster->saveAll($data);
                
			}
			else{
				$data=array(
					'emp_code'=>"'".$EmpCode."'",
					'ben_name'=>"'".$ben_name."'",
					'phone'=>"'".$phone."'",
					'acc_no'=>"'".$acc_no."'",
					'ifsc'=>"'".$ifsc."'",
					'cf_status'=>"'".$cf_status."'",
					'description'=>"'".$cf_verify_ifsc_json."'",
					'created_by'=>"'".$userid."'",
					'updated_at'=>"'".date('Y-m-d H:i:s')."'",
					'updated_by'=>"'".$userid."'"
				);

				$this->BankAccountVerificationMaster->updateAll($data,array('emp_code'=>$EmpCode));  

			}
			
			
			if($cf_verify_ifsc_status['status']=='SUCCESS'){
				$this->Session->setFlash('<span style="color:green;font-weight:bold;" >'.$cf_verify_ifsc_status['message'].'</span>');	
			}
			else{
				$this->Session->setFlash('<span style="color:red;font-weight:bold;" >'.$cf_verify_ifsc_status['message'].'</span>');	
			}
			
			$this->redirect(array('action'=>'viewdetails','?'=>array('EC'=>base64_encode($EmpCode))));
        }        
    }
	
	public function get_token($web){
		$api_url = $web."/payout/v1/authorize";
       
		$Head_Master=array(
			'X-Client-Id'=>'CF38795BMO5EAC1SIS2IMA',
			'X-Client-Secret'=>'eebe030339cee6940f3a0d9b9cebf04fc0758327',
		);
		
       $token_output = json_decode($this->call_curl($api_url,$Head_Master,null),true);
	   
        return array('Authorization'=>'Bearer '.$token_output['data']['token']);
            
        exit;
    }
	
	public function call_curl($api_url,$Head_Master,$post_fields){
        $url = $api_url;
        $ch = curl_init();
        //$Head_Master['Content-Type'] = 'application/json';
        $head_json = array();
        $head_json[] = 'Content-Type: application/json';
        foreach($Head_Master as $key=>$value)
        {
            $head_json[] = "$key:$value";
        }
        //$head_json = json_encode($Head_Master);
        
        $params_query = json_encode($post_fields);
        //print_r($params_query); exit;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head_json);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params_query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
        
        //print_r($response); exit;
        //print_r($_SERVER); exit;
        return $response;
        
    }
	
	public function call_curl_get_params($api_url,$Head_Master,$post_fields){
        $url = $api_url;
        $ch = curl_init();
        //$Head_Master['Content-Type'] = 'application/json';
        $head_json = array();
        $head_json[] = 'Content-Type: application/json';
        foreach($Head_Master as $key=>$value)
        {
            $head_json[] = "$key:$value";
        }
        //$head_json = json_encode($Head_Master);
        
        $params_query = http_build_query($post_fields);
        //print_r($params_query); exit;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head_json);
        curl_setopt($ch, CURLOPT_URL,$url.'?'.$params_query);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
        
        //print_r($response); exit;
        //print_r($_SERVER); exit;
        return $response;
        
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
            header("Content-Disposition: attachment; filename=ExportBankAccountVerification.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
			
            
            $branch_name        =   $this->request->data['BankAccountVerifications']['branch_name'];
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
				
				$status	=	$this->BankAccountVerificationMaster->find('first',array('fields'=>array('cf_status'),'conditions'=>array('emp_code'=>$row['Masjclrentry']['EmpCode'])));
				$status	=	$status['BankAccountVerificationMaster']['cf_status'] !=""?$status['BankAccountVerificationMaster']['cf_status']:"";
				
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