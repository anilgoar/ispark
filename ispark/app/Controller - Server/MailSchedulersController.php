<?php
class MailSchedulersController extends AppController 
{
    public $uses=array('Addbranch','MailSchedular');
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
            $this->Auth->allow('index','business_dashboard_schedular');
            
	}
    }
		
    public function index()
    {  }
    
    public function business_dashboard_schedular()
    {
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "BusinessDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'BusinessDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='BusinessDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
}
?>