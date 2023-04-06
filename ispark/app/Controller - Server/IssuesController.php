<?php
  class IssuesController extends AppController 
  {
	  public $uses=array('IssueTracker','Addbranch','TmpIssueParticular','Process','IssueParticular','TmpsIssues','User','UserIssue');
	  public $components = array('RequestHandler');
	  public $helpers = array('Js');
	  public function beforeFilter()
 {
	 parent::beforeFilter();
	 $roles=explode(',',$this->Session->read("page_access"));
	 $this->Auth->allow('Check_issue_status','issue_submit','View_issue','View_user_issue',
	 'get_process','submit','addIssue','issue_allocate','view','allocate','alloted','delete','user_issue','process_des','submit_allocate',
	 'report','show_report','export_report','update_issue_status');
  	 if(in_array('31',$roles)){$this->Auth->allow('issue_submit'); $this->Auth->allow('process_des','get_process','submit','addIssue','delete','View_issue');}
	 if(in_array('33',$roles)){$this->Auth->allow('issue_allocate'); $this->Auth->allow('process_des','allocate','alloted','View_issue');}
	if(in_array('36',$roles)){$this->Auth->allow('View_user_issue','user_issue'); $this->Auth->allow('View_issue','View_user_issue');}
		  
  if(!$this->Session->check('username'))
  {
  		return $this->redirect(array('controller'=>'users','action' =>'login'));
  }
 	 else
  		{
	  		$role=$this->Session->read('role');
	  		$roles=explode(',',$this->Session->read('page_access'));
	  		$this->Auth->allow('addIssue');
  		} 
	}
  public function  View_user_issue()
 	 {
		  $this->layout='home';
		  $tickets=array();
		  $id=$this->Session->read('userid');
	
		  $data=$this->UserIssue->find('all',array('fields'=>array('ticket_number'),'conditions'=>array('allocate_id'=>$id,'not'=>array('issue_status'=>'3'))));
		  
		 // $data = $this->IssueParticular->find('all',array('conditions'=>array('or'=>array('allocate_to_id1'=>$id,'allocate_to_id2'=>$id),'not'=>array('issue_status'=>'3'))));
		 foreach($data as $post):	  
		 	 $tickets[] = $post['UserIssue']['ticket_number'];
	  	endforeach;

	 		 $data = $this->IssueTracker->find('all',array('conditions'=>array('id'=>$tickets)));

			  $this->set('data1',$tickets);
			  $this->set('data',$data);
			  
 	 }
  public function Issues() {

	}

  public function user_issue()
  {
		  $this->layout='home';
		  $branch_name=$this->Session->read('branch_name');
		  $id=$this->Session->read('userid');
		  $ticket  = $this->request->query['id'];
		  $this->set('main',$this->IssueTracker->find('first',array('conditions'=>array('id'=>$ticket))));
		  // $processtype='process_type'.$arr[0];
	//	  $pross_type=$data['Issues'][$processtype];
	//	   $UserIssue['process_type']=$pross_type;
	
		 $data= $this->UserIssue->find('all', array(
		'joins' => array(
			array(
				'table' => 'issue',
           		 'alias' => 'IssueTracker',
            		'type' => 'INNER',
            			'conditions' => array(
                'IssueTracker.id = UserIssue.issue_id'
            )
        )
    ),
    'conditions' => array(
        'UserIssue.allocate_id' => $id, 'UserIssue.ticket_number'=>$ticket
    ),
    'fields' => array('UserIssue.*', 'IssueTracker.*')
    
));
		
	 // $data = $this->IssueParticular->find('all',array('conditions'=>array('ticket_no'=>$ticket)));
	  //$data=$this->UserIssue->find('all',array('fields'=>array('ticket_number'),'conditions'=>array('allocate_id'=>$id)));
	  $this->set('data',$data);
	  				  $creator = $this->User->find('first',array('fields'=>array('email','process_head'),'conditions'=>array('id'=>$this->Session->read('userid'))));
					  $process_head = $this->User->find('first',array('fields'=>array('process_head'),'conditions'=>array('id'=>$this->Session->read('userid'))));
					  $itmang = $this->User->find('first',array('fields'=>array('email'),'conditions'=>array('role'=>'IT Manager')));
					  $email[] = $creator['User']['email'];
					  $email[] = $process_head['User']['process_head'];
					  $email[] = $itmang['User']['email'];
						//print_r($email); exit;
						App::uses('sendEmail', 'custom/Email');
						//$email = array('0'=>'shilpa.jain@teammas.in');
						  }
  
  public function view()
  { 
	  $this->layout='home';
	  if($this->request->is('Post'))
	  {
		  $data = $this->request->data;
		  $keys = array_keys($data['Issue']);		  
		  $this->IssueParticular->updateAll(array('issue_status'=>$data['Issue'][$keys['0']]['issue_status'],'remarks'=>"'".$data['Issue'][$keys['1']]."'"),array('id'=>$keys[0]));
		  
		  $ticket= $this->IssueParticular->find('first',array('conditions'=>array('id'=>$keys[0])));
			
	if(!$this->IssueParticular->find('first',array('conditions'=>array('ticket_no'=>$ticket['IssueParticular']['ticket_no'],'not'=>array('issue_status'=>'3')))))
		{
			$this->IssueTracker->updateAll(array('issue_status'=>'0'),array('id'=>$ticket['IssueParticular']['ticket_no']));
		}
		  print_r($ticket);die;
		  $this->redirect(array('action','?'=>array('id'=>$ticket['IssueParticular']['ticket_no'])));
	  }	 
	  $ticket = $this->request->query['id'];
	  $this->set('data',$this->IssueTracker->find('first',array('conditions'=>array('id'=>$ticket))));
	  $this->set('particular',$this->IssueParticular->find('all',array('conditions'=>array('ticket_no'=>$ticket))));
	  
	  

	   
  }
  
  public function Check_issue_status()
  {
	  $this->layout='home';
	  $username=$this->Session->read('username');
	  $branch_name=$this->Session->read('branch_name');
	  $result=$this->params->query;
	  $this->set('data',$this->IssueParticular->find('all',array('conditions' =>array('branch_name'=>$branch_name))));
  }
  
	public function update_issue_status()
	{
		$this->layout='home';
		
	  	$issueid=$this->request->data['IssueTracker'];
		

	  	$keys = array_keys($issueid);
		
		$id = $keys['0'];
		$issuestatus=$issueid[$id]['issue_status'];
		$status_check = $this->IssueParticular->find('first',array('conditions'=>array('id'=>$id)));

		if($issuestatus ==3 && $status_check['IssueParticular']['issue_status']==0)
		{
			$this->Session->setFlash("You can't close issue before In - Progress");
			$this->redirect(array('action' => 'View_user_issue'));
		}
		
		$this->IssueParticular->updateAll(array('issue_status'=>$issuestatus),array('id'=>$id));
		$this->UserIssue->updateAll(array('issue_status'=>$issuestatus),array('allocate_id'=>$this->Session->read('userid'),'issue_id'=>$id));
		$ticket = $this->IssueParticular->find('first',array('fields'=>array('ticket_no','user_id','branch_name','priority','requirment_type','requirement_desc','issue_status','remarks'),'conditions'=>array('id'=>$id)));
		
		$flag = false;
		if(!$this->IssueParticular->find('first',array('conditions'=>array('ticket_no'=>$ticket['IssueParticular']['ticket_no'],'not'=>array('issue_status'=>'3')))))
		{
			
			$this->IssueTracker->updateAll(array('issue_status'=>'3'),array('id'=>$ticket['IssueParticular']['ticket_no']));
			$flag = true;
		}
		
		$itmang = $this->User->find('first',array('fields'=>array('email'),'conditions'=>array('username'=>$ticket['IssueParticular']['user_id'])));
		
		$creator = $this->User->find('first',array('fields'=>array('email'),'conditions'=>array('id'=>$this->Session->read('userid'))));
		
		$process_head = $this->User->find('first',array('fields'=>array('process_head'),'conditions'=>array('id'=>$this->Session->read('userid'))));
		
                $flag = false;
                if(!empty($creator['User']['email'])){$flag = true;
                $email[] = $creator['User']['email'];}
                if(!empty($process_head['User']['process_head'])){$flag = true;
                $email[] = $process_head['User']['process_head']; }
                if(!empty($itmang['User']['email'])){$flag = true;
                $email[] = $itmang['User']['email'];}
		//print_r($email); exit;
		
		$username = $this->Session->read('username');
						$process_manager = $process_head['User']['process_head'];
						$issue_no = $keys['0'];
						$Priority = $ticket['IssueParticular']['priority'];
						$req_type = $ticket['IssueParticular']['requirment_type'];
						$details = $ticket['IssueParticular']['requirement_desc'];
						$status =  $ticket['IssueParticular']['issue_status'];
						$remarks = $ticket['IssueParticular']['remarks'];
						
						
						if($Priority==0){
							$pri='low';	
						}
						if($Priority==1){
							$pri='Normal';	
						}
						if($Priority==2){
							$pri='Urgent';	
						}	
						if($req_type==0){
							$req='Uppgrade';}
						if($req_type==1){
							$req='New';}
						if($req_type==2){
							$req='Modification';}
						if($req_type==3){
							$req='Error';}		
						
						if($status==3){
							$st='Close';}
						if($status==1){
							$st='Hold on';}
						if($status==2){
							$st='In-Progress';}
							
						
						$msg = "<html>
<head>
<style>
body{
margin: 0px;padding: 0px;
font-family: Verdana;color: #295594;
font-size: 12px;}.main{border: 1px solid #153b6e;}
p{margin: 0px;padding: 0px;font-size: 12px;white-space: nowrap;color: #456135;font-weight: bold;]
}.grid{border-left: 1px solid #1036a0;border-top: 1px solid #1036a0;
margin: 10px 0px;}.grid td{border-right: 1px solid #1036a0;
border-bottom: 1px solid #1036a0;
}.grid thead{background: #d0d4df;text-align: center;color: #df2c2f;font-weight: bold;
font-size: 12px;
}.grid tbody{text-align: center;
font-size: 11px;
}.header{background: #5ab3df;padding: 3px 10px;color: #024262;
font-weight: bold;height: 20px;
border-bottom: 1px solid #153b6e;
}.footer{background: #afd997;height: 20px;color: #225922;font-weight: bold;
padding: 3px 10px;border-top: 1px solid #153b6e;
}.detail{text-align: left;}</style>
</head>
<body>
<table cellpadding='0' cellspacing='0' border='0' align='center' width='95%' class='main'>
<tr>
<td class='header' colspan='2'>Issue ".$st.", ticket details as following</td>
</tr>
<tr>
<td colspan='2'>
<table cellpadding='3' cellspacing='3' border='0' align='center' width='100%' style='margin-top: 10px;'>
<tr><td><p>Issue Closed By :</p></td><td align='left'>".$username."</td></tr>
<tr><td><p>Reporting Manager:</p></td><td align='left'>".$process_manager."</td></tr>
<tr><td><p>Branch :</p></td><td align='left'>".$ticket['IssueParticular']['branch_name']."</td></tr>
<tr><td><p>Ticket No. :</p></td><td align='left'> ".$ticket['IssueParticular']['ticket_no']."</td></tr>
<tr><td colspan='2'>
</td></tr></table>
<table cellpadding='3' cellspacing='0' width='100%' border='0' align='center'  class='grid'>
<thead>
<tr>
<td width='12%'>Issue No.</td>
<td width='12%'>Priority</td>
<td width='12%'>Req. type</td>
<td width='28%'>Details</td>
<td width='12%'>Status</td>
<td width='12%'>Remarks</td>
</tr>
</thead>
<tr class='main'>
<td width='12%'>".$issue_no."</td>
<td width='12%'>".$pri."</td>
<td width='12%'>".$req."</td>
<td width='28%'>".$details."</td>
<td width='12%'>".$st."</td>
<td width='12%'>".$remarks."</td>
</tr>
</table>
<table>
    <tr><td class='footer' colspan='2'>
	Copyright Mas Infotainment Pvt. Ltd.  Website:-<a href='www.masinfo.co.in'>www.masinfo.co.in</a></td> </tr>
</table>
</tbody>
</body>
</html>";


		App::uses('sendEmail', 'custom/Email');
		//$email = array('0'=>'shilpa.jain@teammas.in');
		
		$sub = "issue status ".$st;
		if($flag) {$sub = "All Issue Closed";}		
		$mail = new sendEmail();
                if($flag){
                    try{
                    $mail-> to($email,$msg,$sub);}
                    catch(Exception $e){}
                    }
	  	$this->redirect(array('action' => 'View_user_issue'));
		$this->set("data1",$issueid);
		//print_r($msg);exit;
  	}
  
  public  function issue_allocate()
  {
	  $username=$this->Session->read('username');
	  $branch_name=$this->Session->read('branch_name'); 	
	  $this->layout='home';	
	  $result=$this->params->query;	
	  $this->set('data',$this->IssueTracker->find('all',array('conditions'=>array('not'=>array("issue_status"=>'3')))));	
  }	
 	
  public function allocate()	
  {	
	  $this->layout='home';	
	  $ticket = base64_decode($this->request->query['id']);
	  $this->set('data',$this->IssueTracker->find('first',array('conditions'=>array('id'=>$ticket))));	
	  $data = $this->IssueParticular->find('all',array('conditions'=>array('ticket_no'=>$ticket)));
	  foreach($data as $post):
	  //$user = $this->UserIssue->find('first',array('fields'=>array('issue_id'),'conditions'=>array('id'=>$post['IssueParticular']['allocate_id'])));
	  //$post['IssueParticular']['allocate_to_id1'] = isset($user['User']['username'])?$user['User']['username']:'';
	 // $user = $this->User->find('first',array('fields'=>array('username'),'conditions'=>array('id'=>$post['IssueParticular']['allocate_to_id2'])));
	 //$post['IssueParticular']['allocate_to_id2'] = isset($user['User']['username'])?$user['User']['username']:'';
	  $dataX[] = $post;
	  endforeach;
	  $data = $dataX;
	  unset($dataX);
	  $role=array('IT','IT Manager');
	  $this->set('particular',$data);
	  $this->set('ITUsers',$this->User->find('list',array('conditions'=>array('role'=>$role),'fields'=>array('username'))));
	  //$this->set('ITUsers',$this->User->find('list',array('conditions'=>array('or'=>array('role'=>'IT','role'=>'IT Manager')),'fields'=>array('username'))));
	  
  }	
 	
  public function alloted()
  {
	  
	  //print_r($this->request->data['Issues']['process_type']);die;
	  
	  $this->layout='ajax';
	  $data = $this->request->data;
	  //print_r($data);
	  
	  $arr=(explode(",",base64_decode($data['Issues']['user'])));
	  if(!$this->UserIssue->find('first',array('conditions'=>array('issue_id'=>$arr[0],'allocate_id'=>$arr[1]))))
	  {
		  //$this->request->$data['Issues']['process_type'];
		  
	  //echo $a[0];       
	 
	  $todate='ToDate'.$arr[0];
	  $fromdate='FromDate'.$arr[0];
	  $processtype='process_type'.$arr[0];
	  $pross_type=$data['Issues'][$processtype];
	  //if($pross =='1'){
		 // $pross_type="sdfsf";
		// }
	  //$processtype='process_type'.$arr[3];
	  $toDate = date_create($data['Issues'][$todate]);
	  $UserIssue['start_date'] = date_format($toDate,'Y-m-d H:i:s');
	  $fromDate = date_create($data['Issues'][$fromdate]);
	  $UserIssue['end_date'] = date_format($fromDate,'Y-m-d H:i:s');
	  $UserIssue['ticket_number']='';
	  //$UserIssue['process_type']='';
	  $UserIssue['issue_id']=$arr[0];
	  $UserIssue['allocate_id']=$arr[1];
	  $UserIssue['ticket_number']=$arr[2];
	 // $UserIssue['process_type']=$arr[3];
	  $UserIssue['process_type']=$pross_type;
	  $UserIssue['createdate']=date('Y-m-d H:i:s');
	  $this->UserIssue->save($UserIssue);
	  $this->Session->setFlash('<font color="#00CC33">allocated!</font>');
	  		
		  
		$allocate_it = $this->User->find('first',array('fields'=>array('email','username'),'conditions'=>array('id'=> $UserIssue['allocate_id'])));		
		$allocate_by = $this->User->find('first',array('fields'=>array('email','username'),'conditions'=>array('id'=>$this->Session->read('userid'))));
		$issue_create_id = $this->IssueParticular->find('first',array('fields'=>array('user_id'),'conditions'=>array('id'=>$UserIssue['issue_id'])));

		$issue_created_by_email = $this->User->find('first',array('fields'=>
		array('email','process_head'),'conditions'=>array('username'=>$issue_create_id['IssueParticular']['user_id'])));
		
                $flag = false;
                if(!empty($allocate_it['User']['email'])){ $flag = true;
                $email[] = $allocate_it['User']['email'];}
                if(!empty($allocate_by['User']['email'])){$flag = true;
                $email[] = $allocate_by['User']['email'];}
                
                if(!empty($issue_created_by_email['User']['email'])){$flag = true;
                $email[] = $issue_created_by_email['User']['email']; }
                
                if(!empty($issue_created_by_email['User']['process_head'])){$flag = true;
                $email[] = $issue_created_by_email['User']['process_head']; }

			$ticket=$this->IssueParticular->find('first',array('fields'=>array('id','issue_status','branch_name','priority','ticket_no','','user_id','priority','requirment_type','remarks','requirement_desc'),
			'conditions'=>array('id'=>$UserIssue['issue_id'])));
			
			$priority = $ticket['IssueParticular']['priority'];
			$req_type = $ticket['IssueParticular']['requirment_type'];
			$details = $ticket['IssueParticular']['requirement_desc'];
			$status =  $ticket['IssueParticular']['issue_status'];
			$remarks = $ticket['IssueParticular']['remarks'];
			$start_date = $UserIssue['start_date'];
			$end_date = $UserIssue['end_date'];
			$allocate_id = $ticket['IssueParticular']['id'];
			$processtype = $UserIssue['process_type'];
			
							
						if($priority==0){
							$pri='low';	
						}
						if($priority==1){
							$pri='Normal';	
						}
						if($priority==2){
							$pri='Urgent';	
						}	
						if($req_type==0){
							$req='Uppgrade';}
						if($req_type==1){
							$req='New';}
						if($req_type==2){
							$req='Modification';}
						if($req_type==3){
							$req='Error';}		
						
						if($status==0){
							$st='Close';}
						if($status==1){
							$st='Hold on';}
						if($status==2){
							$st='In-Progress';}
							
						
						$msg = "<html>
<head>
<style>
body{
margin: 0px;padding: 0px;
font-family: Verdana;color: #295594;
font-size: 12px;}.main{border: 1px solid #153b6e;}
p{margin: 0px;padding: 0px;font-size: 12px;white-space: nowrap;color: #456135;font-weight: bold;]
}.grid{border-left: 1px solid #1036a0;border-top: 1px solid #1036a0;
margin: 10px 0px;}.grid td{border-right: 1px solid #1036a0;
border-bottom: 1px solid #1036a0;
}.grid thead{background: #d0d4df;text-align: center;color: #df2c2f;font-weight: bold;
font-size: 12px;
}.grid tbody{text-align: center;
font-size: 11px;
}.header{background: #5ab3df;padding: 3px 10px;color: #024262;
font-weight: bold;height: 20px;
border-bottom: 1px solid #153b6e;
}.footer{background: #afd997;height: 20px;color: #225922;font-weight: bold;
padding: 3px 10px;border-top: 1px solid #153b6e;
}.detail{text-align: left;}</style>
</head>
<body>
<table cellpadding='0' cellspacing='0' border='0' align='center' width='95%' class='main'>
<tr>
<td class='header' colspan='2'>Allocation done for following ticket details</td>
</tr>
<tr>
<td colspan='2'>
<table cellpadding='3' cellspacing='3' border='0' align='center' width='100%' style='margin-top: 10px;'>
<tr><td><p>Issue Alloted By :</p></td><td align='left'>".$allocate_by['User']['username']."</td></tr>
<tr><td><p>Reporting Manager:</p></td><td align='left'>".$issue_created_by_email['User']['process_head']."</td></tr>
<tr><td><p>Branch :</p></td><td align='left'>".$ticket['IssueParticular']['branch_name']."</td></tr>
<tr><td><p>Ticket No. :</p></td><td align='left'> ".$ticket['IssueParticular']['ticket_no']."</td></tr>




<tr><td colspan='2'>
</td></tr></table>
<table cellpadding='3' cellspacing='0' width='100%' border='0' align='center'  class='grid'>
<thead>
<tr>
<td width='12%'>Issue No.</td>
<td width='12%'>Priority</td>
<td width='12%'>Req. type</td>
<td width='28%'>Details</td>
<td width='28%'>Allocated To</td>
<td width='28%'>Process Type</td>
<td width='28%'>Start Date</td>
<td width='28%'>End Date</td>
<td width='12%'>Status</td>
<td width='12%'>Remarks</td>
</tr>
</thead>
<tr class='main'>
<td width='12%'>".$UserIssue['issue_id']."</td>
<td width='12%'>".$pri."</td>
<td width='12%'>".$req."</td>
<td width='28%'>".$details."</td>
<td width='28%'>".$allocate_it['User']['username']."</td>
<td width='28%'>".$processtype."</td>
<td width='28%'>".$start_date."</td>
<td width='28%'>".$end_date."</td>
<td width='12%'>open</td>
<td width='12%'>".$remarks."</td>
</tr>
</table>
<table>
    <tr><td class='footer' colspan='2'>Copyright Mas Infotainment Pvt. Ltd. 
	   Website:-<a href='www.masinfo.co.in'>www.masinfo.co.in</a></td> </tr>
</table>
</tbody>
</body>
</html>";


		App::uses('sendEmail', 'custom/Email');
		//$email = array('0'=>'shilpa.jain@teammas.in');
		
		$sub = "issue allocated";
		
		$mail = new sendEmail();
                if($flag){try{
                $mail-> to($email,$msg,$sub);}
                catch(Exception $e){}
                }

}else {
		  $this->Session->setFlash('<font color="red"> already allocated!</font>');
		  
	}	
	
	  $this->redirect(array('action'=>'allocate','?'=>array('id'=>base64_encode($arr['2']))));
	  

  }
 	public function issue_submit()
	{ 
		$this->layout='home';
		$user_id = $this->Session->read('username');
		$roles=explode(',',$this->Session->read("page_access"));
		$branch_name=$this->Session->read('branch_name'); 
		 if(in_array('35',$roles))
 		{
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
		}
		else
		{
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'),'conditions'=>array('branch_name'=>$branch_name))));
		}
			$this->set('tmp_issue',$this->TmpIssueParticular->find('all',array('conditions'=>array('user_id'=>$user_id))));
				
		if($data = $this->TmpsIssues->find('first',array('fields'=>array('branch_name','process_name','ticket_no','ticket_desc'),'conditions'=>array('user_id'=>$user_id))))
		{
			foreach($data as $post):
			$dataX[] = $post;
			endforeach;
			$data = array_values($dataX[0]);
			$this->set('data',$data);
		}
		else
		{
			$data = array_fill(0,5,'');
			$this->set('data',$data);
		}

				
   }
   public function process_des()
   {
	  $this->layout='ajax';
	  $result = $this->params->query;
	  $user=$this->Session->read('username');
	  //$issue=$data['issue'];
	  //update & save
	 
	  if( $this->TmpsIssues->find('first',array('conditions'=>array('user_id'=>$user))))
	  {
		 // $result['user_id']="'".$result['user_id']."'";
		  $result['branch_name']="'".$result['branch_name']."'";
		  $result['process_name']="'".$result['process_name']."'";
		  $result['ticket_no']="'".$result['ticket_no']."'";
		  $result['ticket_desc']="'".$result['ticket_desc']."'";
		 // $result['process_type']="'".$result['process_type']."'";
		 // $result['process_desc']="'".$result['process_desc']."'";
		  $this->set('result',$result);	
		  $this->TmpsIssues->updateAll($result,array('user_id'=>$user));  
	  }
	  else{
		     $result['user_id']=$user;
		    $this->TmpsIssues->save($result);
		  }
	} 
 
 
  public function submit()	
  {	
      $this->layout='ajax';
	  $createdate=date('Y-m-d-H-i-s');
 	 if($this->request->is('Post'))	
  		{	
			  $data = $this->request->data;	
			  $username = $this->Session->read('username');	
			  $Files = $data['Files'];	
			  $branch_name="";$html = "";
			 
			  $tmp = $data['TmpIssueParticular'];
			  	
			  $data  = Hash::Remove($data,'Particular');	
			  $data  = Hash::Remove($data,'Files');	
			  $data  = Hash::Remove($data,'TmpIssueParticular');
			  $data['Issues']['createdate'] = $createdate;
			  $tmps = array();	
			  $key = array_keys($tmp); $i =0;	
			   $this->set('data',count($key));
			   //print_r();die;
			  for($i=0; $i<=count($key)-1; $i++)	
  				{		
 					 if(isset($Files[$key[$i]]))	
 						 {	
  								$tmps[$key[$i]] = $tmp[$key[$i]] + $Files[$key[$i]];
								
 						 }	
 							 else	
  							{ 	
 								 $tmps[$key[$i]] = $tmp[$key[$i]];	
  							}	
						 }	

							 $this->IssueTracker->save($data['Issues']); $i = 0;	
							$ticket = $this->IssueTracker->getLastInsertID();	
 						 if($ticket)	
  							{	
 	 							$flag = false; 	
								 
								  foreach($tmps as $post):
								 // $post['branch_name'] = "'".$post['branch_name']."'";
								   //$post['process_name'] = "'".$post['process_name']."'";
								   $priority = $post['priority']==0?'Low':($post['priority']==1?'Normal':'urgent');
								   $req_type = $post['requirment_type']==0?'updgrade':($post['requirment_type']==1?'New':($post['requirment_type']==2?'Modification':'Error'));
								   
								   $html .= "<tr><td width='28%'>".$priority."</td>";
								   $html .= "<td width='28%'>".$req_type."</td>";
								   
								   $html .= "<td width='28%'>".$post['requirement_desc']."</td>";
								   $html .= "<td width='28%'>"."open"."</td>";
								   $html .= "<td width='28%'>".$post['remarks']."</td></tr>";
								   
								  $post['requirement_desc'] = "'".$post['requirement_desc']."'";
								  $post['issue_status'] = "'".$post['issue_status']."'"; 
								  $post['ticket_no'] = $ticket;
								  $post['user_id'] = "'".$username."'";
								  $post['remarks']= "'".$post['remarks']."'";
								$filed = isset($post['attach_files'])?$post['attach_files']:'';
						 if(isset($filed))
 							 {
								  $post = Hash::Remove($post,'attach_files');
								  $filepath=''; 
  							if(is_array($filed) && !empty($filed['0']['name']))
 							 {
								  foreach($filed as $file):
								  $date=date('Y-m-d-H-i-s');
                                                                  
								  move_uploaded_file($file['tmp_name'],WWW_ROOT.'upload/'.$date.$file['name']);
								  $filepath .=$date.$file['name'].",";
								  endforeach;
							  }
							  else if(!empty ($filed['0']['name']))
							  { 
                                                                    $file = $filed;
								  $date=date('Y-m-d-H:i:s');
								  move_uploaded_file($file['tmp_name'],WWW_ROOT.'upload/'.$date.$file['name']);
								  $filepath .=$date.$file['name'].",";
 							 }
						  $post['attach_files'] = "'".$filepath."'";
 						 }
  
						  $this->TmpIssueParticular->updateAll($post,array('id'=>$key[$i++]));
						  endforeach; 
  
						  $data = $this->TmpIssueParticular->find('all',array('conditions'=>array('user_id'=>$username)));
						  foreach($data as $post):
						  $dataX[] =Hash::Remove($post['TmpIssueParticular'],'id');
						  $flag =true; 
  						endforeach; 
  
  					if($flag)
					  {
					  $this->IssueParticular->saveAll($dataX);
					  $this->IssueTracker->saveAll();
					  $this->TmpIssueParticular->deleteAll(array('user_id'=>$username));
					  $this->TmpsIssues->deleteAll(array('user_id'=>$username));
					  $branch_name=$this->Session->read('branch_name');

					  //session flash for tickt generate message.....
					  }
 					 }
 					  $creator = $this->User->find('first',array('fields'=>array('email'),'conditions'=>array('id'=>$this->Session->read('userid'))));
                                           
					  $process_head = $this->User->find('first',array('fields'=>array('process_head'),'conditions'=>array('id'=>$this->Session->read('userid'))));
                                          
					  //$itmang = $this->User->find('first',array('fields'=>array('email'),'conditions'=>array('role'=>'IT Manager')));
                                          $flag = false;
                                          if(!empty($creator['User']['email'])){$flag = true;
					  $email[] = $creator['User']['email'];
                                          }
                                          if(!empty($process_head['User']['process_head'])){$flag = true;
					  $email[] = $process_head['User']['process_head'];
                                          }
                                         
					 // $email[] = $itmang['User']['email'];
					  
						//print_r($email); exit;
						App::uses('sendEmail', 'custom/Email');
						//$email = array('0'=>'shilpa.jain@teammas.in');
												$msg = "<html>
<head>
<style>
body{
margin: 0px;padding: 0px;
font-family: Verdana;color: #295594;
font-size: 12px;}.main{border: 1px solid #153b6e;}
p{margin: 0px;padding: 0px;font-size: 12px;white-space: nowrap;color: #456135;font-weight: bold;]
}.grid{border-left: 1px solid #1036a0;border-top: 1px solid #1036a0;
margin: 10px 0px;}.grid td{border-right: 1px solid #1036a0;
border-bottom: 1px solid #1036a0;
}.grid thead{background: #d0d4df;text-align: center;color: #df2c2f;font-weight: bold;
font-size: 12px;
}.grid tbody{text-align: center;
font-size: 11px;
}.header{background: #5ab3df;padding: 3px 10px;color: #024262;
font-weight: bold;height: 20px;
border-bottom: 1px solid #153b6e;
}.footer{background: #afd997;height: 20px;color: #225922;font-weight: bold;
padding: 3px 10px;border-top: 1px solid #153b6e;
}.detail{text-align: left;}</style>
</head>
<body>
<table cellpadding='0' cellspacing='0' border='0' align='center' width='95%' class='main'>
<tr>
<td class='header' colspan='2'>Issue Created BY ".$branch_name." Ticket Number ".$ticket."</td>
</tr>
<tr>
<td colspan='2'>
<table cellpadding='3' cellspacing='3' border='0' align='center' width='100%' style='margin-top: 10px;'>
<tr><td><p>Branch :</p></td><td align='left'>".$branch_name."</td></tr>
<tr><td><p>Reporting Manager:</p></td><td align='left'>".$process_head['User']['process_head']."</td></tr>
<tr><td><p>Ticket No. :</p></td><td align='left'> ".$ticket."</td></tr>
<tr><td colspan='2'>
</td></tr></table>
<table cellpadding='3' cellspacing='0' width='100%' border='0' align='center'  class='grid'>
<thead>
<tr>
<td width='12%'>Priority</td>
<td width='12%'>Requirement type</td>
<td width='12%'>Requirement Detail</td>
<td width='12%'>Status</td>
<td width='28%'>Remarks</td>
</tr>
</thead>
 
$html
</table>
<table>
    <tr><td class='footer' colspan='2'>Copyright Mas Infotainment Pvt. Ltd. 
	   Website:-<a href='www.masinfo.co.in'>www.masinfo.co.in</a></td> </tr>
</table>
</tbody>
</body>
</html>";
						$sub = "Ticket Number  ".$ticket." ".'from branch'  .$branch_name." ".'Generated';
						$mail = new sendEmail();
                                                if($flag){
                                                    try{
                                                    $mail-> to($email,$msg,$sub);}
                                                    catch(Exception $e){}
                                                    }
										$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Generate Ticket '.$ticket.' for Branch  '.$branch_name.' Generated .'."</b></h4>"));
				return $this->redirect(array('controller'=>'Issues','action' => 'View_issue')); 
 				 }

 			 }
  public function View_issue()
 {
  	$username=$this->Session->read('username');
  	$branch_name=$this->Session->read('branch_name'); 
   
  	$this->layout='home';
  	$result=$this->params->query;
  	$roles=explode(',',$this->Session->read("page_access"));
 	if(in_array('35',$roles))
 	{
  		$this->set('data',$this->IssueTracker->find('all'));
 	}
 	else
 	{
  		$this->set('data',$this->IssueTracker->find('all',array('conditions' =>array('branch_name'=>$branch_name))));
 	}   
  }
 

 public function addIssue()
  {
  $this->layout='ajax';
  $result = $this->params->query;
  $result['user_id']=$this->Session->read('username');
  $this->TmpIssueParticular->save($result);
  $this->set('result',$result);
  }
   
  public function get_process()
  {
  $result = $this->params->query;
  $this->layout='ajax';
  $data = $this->Process->find('all',array('conditions'=>array($result)));
 //print_r($result);die;
  $this->set('process',$data);
  }
 
  public function delete()
  {
  $this->layout='home';
  $id  = $this->request->query['id'];
  $this->TmpIssueParticular->delete(array('id'=>$id));
  $this->redirect(array('action' => 'issue_submit'));
  }

 	public function report() 
	{
      $this->layout='home';
	  $user_id = $this->Session->read('username');
	  $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
	  $this->set('tmp_issue',$this->TmpIssueParticular->find('all',array('conditions'=>array('user_id'=>$user_id))));
 	}
   public function show_report()
   {
	    $this->layout="ajax";
		$result=$this->params->query;
	
		$this->set('result',$result);
		$todate = date_create($result['AddToDate']);
		$todate = date_format($to_date,"Y-m-d");
				
		$fromdate = date_create($result['AddFromDate']);
		$fromdate = date_format($from_date,"Y-m-d");
		
		//$branch = $result['AddBranchName'];
//		$process  = $result['AddProcessName'];

         $a=$this->IssueParticular->query("SELECT branch_name,user_id,process_name,ticket_desc,ticket_no,
		 process_type,process_desc,requirment_type,requirement_desc,createdate,tatstart_date,tatend_date FROM issue");
		 // print_r($a); die;
		$this->set("data",$a);
		
		
	
   }
	
	public function export_report()
	{
		$this->layout="ajax";
		$result=$this->params->query;
		$this->set('result',$result);
		$to_date=date_create($result['AddToDate']);
		$to_date=date_format($to_date,"d-m-Y");
		
		$from_date=date_create($result['AddFromDate']);
		$from_date=date_format($from_date,"Y-m-d");
		
		
		//$branch  = $result['AddBranchName'];
//	     $process  = $result['AddProcessName'];
     
	   $this->set("rest",$this->InitialInvoice->query("SELECT branch_name,user_id,process_name,ticket_desc,ticket_no,process_typ,process_desc,requirment_type, requirement_desc,
	   createdate from issue BETWEEN '$to_date' AND '$from_date'"));
	   
		
	}
	   
		
}
?>