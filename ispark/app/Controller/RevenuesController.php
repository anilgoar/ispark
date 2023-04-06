<?php
class RevenuesController extends AppController 
{
    public $uses =array("Addcompany","Addbranch","CostCenterMaster","Provision");
    public function beforeFilter()
    {
        parent::beforeFilter();
        
	$this->layout='home';
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
				
            if(in_array('51',$roles)){$this->Auth->allow('index');$this->Auth->allow('get_revenue_cost');$this->Auth->allow('send_bps');}
            if(in_array('52',$roles)){$this->Auth->allow('get_revenue_cost'); $this->Auth->allow('send_bps');}
            else{$this->Auth->deny('index');$this->Auth->deny('get_revenue_cost');$this->Auth->deny('edit');}
	}
    }
		
    public function index() 
    {
        $this->layout='home';
	$this->set('company_name', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));
        $this->set('branch_name', array_merge(array('All'=>'All'),$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')))));
        
        if($this->request->is('Post'))
        {
            //print_r($this->request->data); exit;
            $data = $this->request->data['Addcompany'];
            
            
            $company = $data['company_name'];
            $branch = $data['branch_name'];
            $finance = $data['finance_year'];
            $year = explode("-",$data['finance_year']);
            $month = $data['month'];
            $cost_center = $data['cost_center'];
            
            if(in_array($month, array('Jan','Feb','Mar')))
            {
                $month = $month.'-'.$year[1];
            }
            else 
            {
                $month = $month.'-'.($year[1]-1);
            }
            
            $str = '';
            $str2 = " 1=1"; 
            if($company!='All' && !empty($company)) { $str .= " and cm.company_name = '$company'"; $str2 .= ""; }
            if($branch!='All' && !empty($branch))  { $str .= " and cm.branch = '$branch'"; $str2 .= " and branch_name='$branch'"; }
            if($finance!='All' && !empty($finance)) { $str .= " and pm.finance_year = '$finance'"; $str2 .= " and finance_year='$finance'"; }
            if($month!='All' && !empty($month))   { $str .= " and pm.month = '$month'"; $str2 .= " and month='$month'"; }
            if($cost_center!='All' && !empty($cost_center))   { $str .= " and pm.cost_center = '$cost_center'"; $str2 .= " and cost_center='$cost_center'";}
            
            $data = $this->Provision->query("SELECT pm.Id,pm.branch_name,pm.cost_center,pm.`month`,provision,tab.Total `raised`,provision_balance FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
INNER JOIN 
(
SELECT cost_center, `month`,finance_year, SUM(total) `Total` FROM tbl_invoice Where $str2
GROUP BY cost_center,`month`,finance_year
) AS tab ON pm.cost_center = tab.cost_center AND pm.month = tab.month AND pm.finance_year = tab.finance_year
WHERE pm.send_bps = '0' $str");
           
//            echo "SELECT pm.Id,pm.branch_name,pm.cost_center,pm.`month`,provision,tab.Total `raised`,provision_balance FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
//INNER JOIN 
//(
//SELECT cost_center, `month`,finance_year, SUM(total) `Total` FROM tbl_invoice Where $str2
//GROUP BY cost_center,`month`,finance_year
//) AS tab ON pm.cost_center = tab.cost_center AND pm.month = tab.month AND pm.finance_year = tab.finance_year
//WHERE pm.send_bps = '0' $str"; exit;
            
            
            $this->set('data',$data);
            
        }
    }
	   
    public function get_revenue_cost() 
    {
        $this->layout="ajax";
        if ($this->request->is('post')) 
        {
            $company = $this->request->data['company'];
            $branch = $this->request->data['branch'];
            $finance = $this->request->data['finance'];
            $year = explode("-",$this->request->data['finance']);
            $month = $this->request->data['month'];
            
            if(in_array($month, array('Jan','Feb','Mar')))
            {
                $month = $month.'-'.$year[1];
            }
            else 
            {
                $month = $month.'-'.($year[1]-1);
            }
            
            $str = "1=1";
            
            if($company!='All' && !empty($company)) { $str .= " and cm.company_name = '$company'"; }
            if($branch!='All' && !empty($branch))  { $str .= " and cm.branch = '$branch'"; }
            if($finance!='All' && !empty($finance)) { $str .= " and pm.finance_year = '$finance'"; }
            if($month!='All' && !empty($month))   { $str .= " and pm.month = '$month'"; }
            
            $cost_center = $this->Provision->query("SELECT distinct(cm.cost_center) `cost_center` FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center"
                    . " Where $str ");
            $this->set('cost_center',$cost_center);
	}
    }
    
    public function send_bps() 
    {
        $this->layout="ajax"; 
        if ($this->request->is('post')) 
	{
            $data = $this->request->data['check'];
            $i=0;
            foreach($data as $d)
            {
                $ides = explode('#',$d);
                $id = $ides[0];
                $amount = $ides[1];
                $prov = $this->Provision->find('first',array('fields'=>array('branch_name','cost_center','finance_year','month','remarks'),'conditions'=>array('Id'=>$id)));
                $year = explode('-',$prov['Provision']['finance_year']);
                
                
                $monthArr = array('Jan'=>1,'Feb'=>2,'Mar'=>'3','Apr'=>'4','May'=>'5','Jun'=>'6','Jul'=>'7','Aug'=>'8','Sep'=>'9','Oct'=>'10','Nov'=>'11','Dec'=>'12');
                $month = explode("-",$prov['Provision']['month']);
                $monthNo = $monthArr[$month[0]];
                
                
                $dataX[$i]['Branch'] = $prov['Provision']['branch_name'];
                $dataX[$i]['CostCenter'] = $prov['Provision']['cost_center'];
                $dataX[$i]['Year'] = ($year[1]-1);
                $dataX[$i]['Month'] = $monthNo;
                $dataX[$i]['Amount'] = $amount;
                $dataX[$i]['Remarks'] = $data['Provision']['remarks'];
                $dataX[$i]['UserName'] = $this->Session->read("username");
                $i++;
                
                $upd ="update provision_master set send_bps='1' Where Id = '$id'";
                
                //$this->Provision->query($upd);
                
            }
            
            $this->set('data',$dataX);
            
            
	}
	else
	{
            $id  = $this->request->query['id'];
            $this->set('branch_master',$this->Addbranch->find('first',array('conditions'=>array('id'=>$id))));
	}
    }
}

?>