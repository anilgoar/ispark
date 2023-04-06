<?php
  class IssueReportsController extends AppController 
  {
	  public $uses=array('IssueTracker','Addbranch','TmpIssueParticular','Process','IssueParticular','TmpsIssues','User','Access');
	  public $components = array('RequestHandler');
	  public $helpers = array('Js');
	  public function beforeFilter()
 	 {
		  parent::beforeFilter();
		  $roles=explode(',',$this->Session->read("page_access"));
		  $this->Auth->allow('View_issue_report','show_issues_reports','export_issues_reports');
  		  if(in_array('31',$roles)){$this->Auth->allow('issue_submit'); $this->Auth->allow('process_des','get_process','submit','addIssue','delete','View_issue');}
		  if(in_array('33',$roles)){$this->Auth->allow('issue_allocate'); $this->Auth->allow('process_des','allocate','alloted','View_issue');}
		  if(in_array('36',$roles)){$this->Auth->allow('View_user_issue','user_issue'); $this->Auth->allow('View_issue','Submit1');}
		  if(in_array('37',$roles)){$this->Auth->allow('show_issue_status');}
		  
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
 	public function show_issue_status()
	{
		
		$username=$this->Session->read('username');
  	$branch_name=$this->Session->read('branch_name'); 
   
  	$this->layout='home';
  	$result=$this->params->query;
  	$roles=explode(',',$this->Session->read("page_access"));
 	if(in_array('37',$roles))
 	{
  		$this->set('data',$this->IssueTracker->find('all',array('conditions'=>array('issue_status'=>'3'))));
 	}
 	else
 	{
  		$this->set('data',$this->IssueTracker->find('all',array('conditions' =>array('branch_name'=>$branch_name))));
 	}   
	}
	
	
	
	//public function  View_issue_report(){
		//if($data = $this->TmpsIssues->find('first',array('fields'=>array('branch_name','process_name','ticket_no','ticket_desc'),'conditions'=>array('user_id'=>$user_id))))
//		{
//			foreach($data as $post):
//			$dataX[] = $post;
//			endforeach;
//			$data = array_values($dataX[0]);
//			$this->set('data',$data);
//		}
//		else
//		{
//			$data = array_fill(0,5,'');
//			$this->set('data',$data);
//		}
	public function  View_issue_report(){
		if(isset($this->request->query['branchid'])){
			$this->layout='ajax';
	    	$data = $this->request->data;
	    	echo $this->request->query['branchid'];
		}else{
		
		$this->layout='home';
		$branchRecord=array();
		$userRecord=array();
		$branch=$this->Addbranch->find('all',array('fields'=>array('id','branch_name')));
		$user=$this->User->find('all',array('fields'=>array('id','username'),'conditions'=>array('role'=>'IT')));
		//$submit=$this->Access->find('all',array('fields'=>array('user_type'),'conditions'=>array('Access.page_access LIKE'=>'%31%')));
		$branchRecord['All']='All';
		foreach($branch as $branchData){
			$brname=$branchData['Addbranch']['branch_name'];
			$brid=$branchData['Addbranch']['id'];
			$branchRecord[$brname]=$brname;
		}
		$userRecord['All']='All';
		foreach($user as $userData){
			$usname=$userData['User']['username'];
			$usid=$userData['User']['id'];
			$userRecord[$usid]=$usname;
		}
		//foreach($submit as $submitby){
//			$usertype=$submitby['Access']['user_type'];
//			$page=$submitby['Access']['page_access'];
//			$submitRecord[$page]=$page;
//			}
		$this->set('branch_master', $branchRecord);
		$this->set('user_master', $userRecord);
		//$this->set('page_access',$submitRecord);
		$datat=$this->Access->find('list',array('fields'=>array('user_type'),'conditions'=>array('Access.page_access LIKE'=>'%31%')));
		$datat['All'] = 'All';
		$this->set('output', $datat);
			//print_r($datat);die;
	  	$data = $this->IssueParticular->find('all');
	  	$this->set('data',$data);
		
//		$datat=$this->Access->find('first',array('fields'=>array('user_type'),'conditions'=>array('Access.page_access LIKE'=>'%31%')));
//		$this->set('output', $datat);
//
		}
  	}
  	public function show_issues_reports(){
		 $this->layout='ajax';
		
		 $allocate_id=$_REQUEST['HandleBy'];
		 $submitted_by = $_REQUEST['SubmitBy'];
		 $fdate = date_create($_REQUEST['fdate']);
		 $fdate = date_format($fdate,'Y-m-d');
		 $ldate = date_create($_REQUEST['ldate']);
		 $ldate = date_format($ldate,'Y-m-d');
		 $branch_name = $_REQUEST['branch_name'];
		 $process_name = $_REQUEST['process_name'];
		 $status = $_REQUEST['status'];
		 
		 
		 $qry = $branch_name == 'All'?"":" where iss.branch_name = '$branch_name'";
		 $qry .= $process_name == 'All'?"":($branch_name =='All'?" where process_name = '$process_name' ":" and process_name = '$process_name' ");
		 $qry .= $qry ==''?($status=='All'?'':" where ui.issue_status = '$status' "):($status=='All'?'':" and ui.issue_status = '$status' ");
		 $qry .= $qry ==''?($allocate_id=='All'?'':" where ui.allocate_id = '$allocate_id' "):($allocate_id=='All'?'':" and ui.allocate_id = '$allocate_id' ");
		 $qry .= $qry ==''?($submitted_by=='All'?'':" where iss.user_id = '$submitted_by' "):($submitted_by=='All'?'':" and iss.user_id = '$submitted_by' ");
		 $qry .= $qry ==''?" where date(iss.createdate) between '$fdate' and '$ldate' ":" and date(iss.createdate) between '$fdate' and '$ldate' ";
		 
			/* $arrData=array(
			'branch_name'=>$_REQUEST['branch_name'],
			'process_name'=>$_REQUEST['process_name'],

			'createdate between ? and ?'=>array(date_format($fdate,'Y-m-d'),date_format($ldate,'Y-m-d')),
			'issue_status'=>$_REQUEST['status'],
		);*/
 
		$data = $this->IssueParticular->query("SELECT iss.branch_name,process_name,start_date,end_date,ui.ticket_number,iss.requirement_desc,iss.createdate,iss.requirment_type,
		iss.ticket_desc,ui.issue_status,user_id `submitted by`,tu.username `handled by` FROM issue iss INNER JOIN user_issue
		 ui ON iss.id = ui.issue_id INNER JOIN tbl_user tu ON tu.id= ui.allocate_id $qry");
		/* $data = "SELECT iss.branch_name,process_name,start_date,end_date,
		ui.issue_status,user_id `submitted by`,tu.username `handled by` FROM issue iss INNER JOIN user_issue
		 ui ON iss.id = ui.issue_id INNER JOIN tbl_user tu ON tu.id= ui.allocate_id $qry";*/
		 
	  	$this->set('data',$data);
		
	}
	public function get_process()
	{
		$this->layout='ajax';
		$this->redirect(array('controller'=>'issues','action'=>'get_process','?'=>$this->params->query));
	}
	public function export_issues_reports()
	{
				 $this->layout='ajax';
		
		 $allocate_id=$_REQUEST['HandleBy'];
		 $submitted_by = $_REQUEST['SubmitBy'];
		 $fdate = date_create($_REQUEST['fdate']);
		 $fdate = date_format($fdate,'Y-m-d');
		 $ldate = date_create($_REQUEST['ldate']);
		 $ldate = date_format($ldate,'Y-m-d');
		 $branch_name = $_REQUEST['branch_name'];
		 $process_name = $_REQUEST['process_name'];
		 $status = $_REQUEST['status'];
		 
		 
		 $qry = $branch_name == 'All'?"":" where iss.branch_name = '$branch_name'";
		 $qry .= $process_name == 'All'?"":($branch_name =='All'?" where process_name = '$process_name' ":" and process_name = '$process_name' ");
		 $qry .= $qry ==''?($status=='All'?'':" where ui.issue_status = '$status' "):($status=='All'?'':" and ui.issue_status = '$status' ");
		 $qry .= $qry ==''?($allocate_id=='All'?'':" where ui.allocate_id = '$allocate_id' "):($allocate_id=='All'?'':" and ui.allocate_id = '$allocate_id' ");
		 $qry .= $qry ==''?($submitted_by=='All'?'':" where iss.user_id = '$submitted_by' "):($submitted_by=='All'?'':" and iss.user_id = '$submitted_by' ");
		 $qry .= $qry ==''?" where date(iss.createdate) between '$fdate' and '$ldate' ":" and date(iss.createdate) between '$fdate' and '$ldate' ";
		 
			/* $arrData=array(
			'branch_name'=>$_REQUEST['branch_name'],
			'process_name'=>$_REQUEST['process_name'],

			'createdate between ? and ?'=>array(date_format($fdate,'Y-m-d'),date_format($ldate,'Y-m-d')),
			'issue_status'=>$_REQUEST['status'],
		);*/
 
		$data = $this->IssueParticular->query("SELECT iss.branch_name,process_name,start_date,end_date,ui.ticket_number,iss.requirement_desc,iss.createdate,iss.requirment_type,
		iss.ticket_desc,ui.issue_status,user_id `submitted by`,tu.username `handled by` FROM issue iss INNER JOIN user_issue
		 ui ON iss.id = ui.issue_id INNER JOIN tbl_user tu ON tu.id= ui.allocate_id $qry");
		/* $data = "SELECT iss.branch_name,process_name,start_date,end_date,
		ui.issue_status,user_id `submitted by`,tu.username `handled by` FROM issue iss INNER JOIN user_issue
		 ui ON iss.id = ui.issue_id INNER JOIN tbl_user tu ON tu.id= ui.allocate_id $qry";*/
		 
	  	$this->set('data',$data);
		

		/*$this->layout='ajax';
		$result=$this->params->query;
		$this->set('result',$result);
		$to_date=date_create($result['AddFirstDate']);
		$to_date=date_format($to_date,"Y-m-d");
		$from_date=date_create($result['AddLastDate']);
		$from_date=date_format($from_date,"Y-m-d");
		$status = $result['status'];
		
		$branch  = $result['AddBranch'];
		$process = $result['IssuesProcessName'];
		 
		$arrData=array(
			branch_name'=>$branch,
			process_name'=>$process,
			date(createdate) between ? and ?'=>array($to_date,$from_date),
			issue_status'=>$status,
 			
		);
		$this->set('date',$process);
    	$data = $this->IssueParticular->find('all',array('conditions'=>array('branch_name'=>$branch,'process_name'=>$process,'issue_status'=>$status,'createdate between ? and ?'=>array($from_date,$to_date))));
		$data = $this->IssueParticular->find('all',array('conditions'=>array($arrData)));
		$this->set('data',$data);
	*/	
	}
}
?>