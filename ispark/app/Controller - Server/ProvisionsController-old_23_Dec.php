<?php
class ProvisionsController extends AppController 
{
    public $uses=array('Provision','CostCenterMaster','TMPProvision','Addbranch','InitialInvoice');
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
            if(in_array('4',$roles)){$this->Auth->allow('index','view','add','edit','update','provision_check','provision_check_edit','uploadProvision','view_provision');}
            if(in_array('38',$roles)){$this->Auth->allow('dashboard','provisionDetails','showReport');}
	}
    }
		
    public function index() 
    {
       $this->layout="home";
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
    }
    
    public function add()
    {
        if($this->request->is('POST') && !empty($this->request->data))
        {
            $request = $this->request->data['Provision'];
            
            $data['branch_name'] = addslashes($request['branch_name']);
            $data['cost_center'] = addslashes($request['cost_center']);
            $data['finance_year'] = addslashes($request['finance_year']);
            $data['remarks'] = addslashes($request['remarks']); 
            $data['month'] = addslashes($request['month']);
            $monthArr = array('Jan','Feb','Mar');
            $split = explode('-',$data['finance_year']);
            //print_r($split); die;
            if(in_array($data['month'], $monthArr))
            {
                $data['month'] .= '-'.$split[1];
            }
            else
            {
                $data['month'] .= '-'.($split[1]-1);
            }
                    
            if($this->Provision->find('first',array('fields'=>array('id'),
                'conditions'=>array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month']))))
                {
                 $this->Session->setFlash("Provision Alread Exists");   
                }
            else
                {
                    $deduct = $this->InitialInvoice->find('list',array('conditions'=>array('cost_center'=>$data['cost_center'],'month'=>$data['month']),'fields'=>array('id','total')));
                    
                    $data['provision_balance'] = addslashes($request['provision']);
                    foreach($deduct as $d)
                    {
                       $data['provision_balance'] -=$d;
                    }
                    $data['provision'] = addslashes($request['provision']);
                    
                    $data['createdate'] = date('Y-m-d H:i:s');
                    
                    if($this->Provision->save($data))
                    {
                        $this->Session->setFlash("Provision Added to Cost Center ".$data['cost_center']." to ".$data['month']." For Financial Year ".$data['finance_year']);
                    }
                    unset($request); unset($data); unset($this->request->data);
                }
        }
        return $this->redirect(array('action'=>'view'));
    }
    
    public function view()
    {
       $this->layout = "home";
       $branch = array_merge(array('All'=>'All'),$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'))));
       $this->set('branch',$branch);
    }
    
    public function view_provision()
    {
       $this->layout = "ajax";
       $condition = array('branch_name' => $this->params->query['branch_name']);
       $condition2 = "WHERE branch_name ='".$this->params->query['branch_name']."'";
       if($this->params->query['branch_name']=='All')
       {
           $condition =array();
           $condition2 = "";
       }
       $this->set('provision',$this->Provision->query("SELECT * FROM provision_master Provision $condition2 ORDER BY STR_TO_DATE(CONCAT('1-',MONTH),'%d-%b-%Y') DESC"));
       //$this->set('provision',$this->Provision->find('all',array('conditions'=>$condition,'order'=>array("CONCAT('1-',month)"=>'desc')))); 
    }
    public function edit()
    {
       $this->layout = "home";
       $id = $this->request->query['id'];
       $branch = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
       $this->set('branch_master',$branch);
       $this->set('provision',$this->Provision->find('first',array('conditions'=>array('id'=>$id))));
       
    }
    
    public function update()
    {
       $this->layout = "home";
       if($this->request->is('POST') && !empty($this->request->data))
       {
           $data = $this->request->data['Provision'];
           $data['provision_balance'] = $data['provision'];
           
           $id = $this->request->data['Provision']['id'];
           
           $data=Hash::remove($data,'id');
           $keys = array_keys($data);
           
           for($i=0; $i<count($keys); $i++)
           {
               $data[$keys[$i]] = addslashes($data[$keys[$i]]);
           }
           
           $monthArr = array('Jan','Feb','Mar');
            $split = explode('-',$data['finance_year']);
            
            if(in_array($data['month'], $monthArr))
            {
                $data['month'] = $data['month'].'-'.$split[1];
            }
            else
            {
                $data['month'] .= '-'.($split[1]-1);
            }
            
            
           if($cost = $this->Provision->find('first',array('conditions'=>array('id'=>$id))))
           {
                $old_cost_center = $cost['Provision']['cost_center'];
                
                if($data['cost_center'] == $old_cost_center)
                {
                    $Initial = $this->InitialInvoice->find('all',array('conditions'=>
                        array('cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month'])));
                    foreach($Initial as $ini):
                        $data['provision_balance'] -= $ini['InitialInvoice']['total'];
                    endforeach;
                }
           }
           
           for($i=0; $i<count($keys); $i++)
           {
               $data[$keys[$i]] = "'".$data[$keys[$i]]."'";
           }
           
           //$this->Provision->query("INSERT INTO `his_provision_master`(cost_center,branch_name,finance_year,`month`,po_require,grn_require,agreement_require,acknowledgement_require,provision,provision_balance,`modify_provision`,`modify_provision_balance`) 
//SELECT cost_center,branch_name,finance_year,`month`,po_require,grn_require,agreement_require,acknowledgement_require,provision,provision_balance,'".$data['provision']."','".$data['provision_balance']."' FROM provision_master WHERE id = ".$id);
           if($this->Provision->updateAll(array('branch_name'=>$data['branch_name'],'cost_center'=>$data['cost_center'],'finance_year'=>$data['finance_year'],'month'=>$data['month'],'provision'=>$data['provision'],'provision_balance'=>$data['provision_balance']),array('id'=>$id)))
           {
              $this->Session->setFlash('<font color="green">Provision for Cost Center '.$data['cost_center'].' to Month'. $data['month'].' for Finance Year'. $data['finance_year'].' updated</font>');
           }
           else
           {
               $this->Session->setFlash('Not Updated');
           }
           
       }
       return $this->redirect(array('controller'=>'provisions','action'=>'view'));
    }
    public function provision_check()
    {
        $this->layout = "ajax";
        $result = $this->params->query;
        $monthArr = array('Jan','Feb','Mar');
        $split = explode('-',$result['finance_year']);
        
            if(in_array($result['month'], $monthArr))
            {
                $result['month'] .= '-'.$split[1];
            }
            else
            {
                $result['month'] .= '-'.($split[1]-1);
            }
            
        if($result['finance_year']=='2015-16' || $result['finance_year']=='2014-15' || $result['month']=='Jan-16' || $result['month']=='Feb-16' || $result['month']=='Mar-16' || $result['month']=='Apr-16')
        {
            $this->set('data','1-1');
        }
        
        else
        {
        
            
        if($this->Provision->find('first',array('conditions'=>array('cost_center'=>$result['cost_center'],'finance_year'=>$result['finance_year'],'month'=>$result['month'],"provision_balance = '".$result['total']."'"))))
        {
          $this->set('data','1-1');
        }
        else if($data = $this->Provision->find('first',array('conditions'=>array('cost_center'=>$result['cost_center'],'finance_year'=>$result['finance_year'],'month'=>$result['month'],"provision_balance > '".$result['total']."'"))))
        {
            $provision_balance = $data['Provision']['provision_balance'];
            $this->set('data','2-'.$provision_balance);
        }
        else
        {
            $this->set('data','0-0');
        }
        }
    }
    public function provision_check_edit()
    {
        $this->layout = "ajax";
        $result = $this->params->query;
        
        $month = explode('-',$result['month']);
        $result['month'] = $month[0];
        
        $monthArr = array('Jan','Feb','Mar');
        $split = explode('-',$result['finance_year']);
        
            if(in_array($result['month'], $monthArr))
            {
                $result['month'] .= '-'.$split[1];
            }
            else
            {
                $result['month'] .= '-'.($split[1]-1);
            }
        
        if($result['finance_year']=='2015-16' || $result['finance_year']=='2014-15' || $result['month']=='Jan-16' || $result['month']=='Feb-16' || $result['month']=='Mar-16' || $result['month']=='Apr-16')
        {
            $this->set('data','1');
        }
        else
        {
        $oldData = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center'),'conditions'=>array('id'=>$result['id'])));
        
        $provision = $this->Provision->find('first',array('fields'=>array('provision_balance'),'conditions'=>array('cost_center'=>$result['cost_center'],'finance_year'=>$result['finance_year'],'month'=>$result['month'])));
        
        $check = 1;
        if($oldData['InitialInvoice']['cost_center']==$result['cost_center'])
        {
            $check = (isset($provision['Provision']['provision_balance']) ? $provision['Provision']['provision_balance'] : 0) +$oldData['InitialInvoice']['total'] - $result['total'];
        }
        else
        {
            $check = (isset($provision['Provision']['provision_balance'])?$provision['Provision']['provision_balance']:0) - $result['total'];
        }
        if($check>=0)
        {
          $this->set('data','1');
        }
        else
        {
          $this->set('data','0');
        }
        }
    }
    
    public function dashboard()
    {
        $this->layout = "home";
        
        $role = $this->Session->read('role');
        $branch_name = $this->Session->read('branch_name');
        $branch = array();
        $conditions = array();
        if($role=='admin')
        {
            $branch = array("All"=>"All");
        }
        else
        {
          $conditions = array('branch_name'=>$branch_name);
        } 
        
	$branch2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions));
        $this->set('branch_master',array_merge($branch,$branch2));
    }
    
    public function showReport()
    {
        $this->layout = "ajax";

        
        $branch = $this->params->query['branch_name'];
		
		if($branch=='All')
		{
        
        
        $data = $this->Provision->query("SELECT branch_name,`month`,SUM(Provision) `Provision`,SUM(`Billing Pending`) `Billing Pending`, GROUP_CONCAT(cost_center1) `cost_center1`,
SUM(`PO Pending`) `PO Pending`, GROUP_CONCAT(cost_center2) `cost_center`, SUM(`GRN Pending`) `GRN Pending`,
GROUP_CONCAT(cost_center3) `cost_center3`,GROUP_CONCAT(`Agreement Pending`) `Agreement Pending`, GROUP_CONCAT(`Invoice No`) `Invoice No`, 
GROUP_CONCAT(`PTP Date`) `PTP Date`,SUM(Payment) `Payment`,SUM(InvoiceSubmit) `InvoiceSubmit`
FROM (SELECT 
'All' branch_name,tb.month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL)) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd,0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL))) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center))) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
UNION
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti RIGHT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
)AS tb1
LEFT JOIN bill_pay_particulars bpp ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE bpp.bill_no IS NULL
GROUP BY tb1.month
) AS tb
LEFT JOIN (SELECT cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master GROUP BY `month`)AS pm ON tb.finance_year = pm.finance_year AND
tb.month = pm.month

 UNION  ALL
  SELECT 'All' branch_name,'Previous' `month`,'0' `Provision`,'0' `Billing Pending`,
  GROUP_CONCAT(CASE WHEN IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no = '',TRUE,FALSE ),FALSE) THEN tb.bill_no END) `cost_center1`,
  SUM(IF(cm.po_required = 'Yes',IF(tb.approve_po!='Yes',tb.grnd,0),0)) `PO Pending`,
  GROUP_CONCAT(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center),NULL),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center))) `cost_center2`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0),0),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0))) `GRN Pending`,'' `cost_center3`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd),0),
 IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd))) `InvoiceSubmit`,'' `Agreement Pending`,'' `Invoice No`,'' `PTP Date`,
 '0' `payment`
  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
  LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name AND
  bpp.branch_name = tb.branch_name and bpp.financial_year = tb.finance_year and bpp.bill_no = substring_index(tb.bill_no,'/',1)
  WHERE tb.finance_year IN ('2015-16','2014-15') and bpp.bill_no is null AND (tb.bill_no IS NOT NULL AND tb.bill_no !='')) AS tab 
 GROUP BY `month` ORDER BY STR_TO_DATE(CONCAT('1-',`month`),'%d-%b-%Y') ");
        }
		else
		{
		
        
        $data = $this->Provision->query("SELECT branch_name,`month`,SUM(Provision) `Provision`,SUM(`Billing Pending`) `Billing Pending`,
GROUP_CONCAT(cost_center1) `cost_center1`,SUM(`PO Pending`) `PO Pending`, GROUP_CONCAT(cost_center2) `cost_center`,
SUM(`GRN Pending`) `GRN Pending`,GROUP_CONCAT(cost_center3) `cost_center3`,GROUP_CONCAT(`Agreement Pending`) `Agreement Pending`, 
GROUP_CONCAT(`Invoice No`) `Invoice No`, GROUP_CONCAT(`PTP Date`) `PTP Date`,SUM(Payment) `Payment`,SUM(InvoiceSubmit) `InvoiceSubmit`
 FROM (SELECT 
branch `branch_name`,tb.month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL) order by tb1.cost_center) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd,0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)) order by tb1.cost_center) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)) order by tb1.cost_center) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
UNION
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti RIGHT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
)AS tb1
LEFT JOIN bill_pay_particulars bpp ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE bpp.bill_no IS NULL 
GROUP BY tb1.month
) AS tb
LEFT JOIN (SELECT cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master WHERE branch_name='$branch' GROUP BY `month`)AS pm
 ON tb.finance_year = pm.finance_year AND
tb.month = pm.month

UNION  ALL 
  SELECT tb.branch_name,'Previous' `month`,'0' `Provision`,'0' `Billing Pending`,
  GROUP_CONCAT(CASE WHEN IF(cm.po_required = 'Yes',IF(tb.po_no IS NULL OR tb.po_no = '',TRUE,FALSE ),FALSE) THEN tb.bill_no END) `cost_center1`,
  SUM(IF(cm.po_required = 'Yes' ,IF(tb.approve_po!='Yes',tb.grnd,0),0)) `PO Pending`,
  GROUP_CONCAT(IF(cm.po_required='Yes' ,IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center),NULL),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.cost_center,NULL),tb.cost_center))) `cost_center2`,
  SUM(IF(cm.po_required='Yes' ,IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0),0),
  IF(cm.grn='Yes',IF(tb.approve_grn!='Yes',tb.grnd,0),0))) `GRN Pending`,'' `cost_center3`,
  SUM(IF(cm.po_required='Yes',IF(tb.approve_po='Yes',IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd),0),
 IF(cm.grn='Yes',IF(tb.approve_grn='Yes',tb.grnd,0),tb.grnd)) ) `InvoiceSubmit`,'' `Agreement Pending`,'' `Invoice No`,'' `PTP Date`,
 '0' `payment`
  FROM tbl_invoice tb INNER JOIN cost_master cm ON tb.cost_center = cm.cost_center
LEFT JOIN bill_pay_particulars bpp ON cm.company_name =  bpp.company_name AND
tb.branch_name = bpp.branch_name AND tb.finance_year = bpp.financial_year AND
SUBSTRING_INDEX(tb.bill_no,'/',1) = bpp.bill_no
    WHERE tb.finance_year IN ('2015-16','2014-15') AND tb.branch_name='$branch'
    AND bpp.bill_no IS NULL
    
UNION 

SELECT 
pm.branch_name `branch_name`,pm.month,pm.provision_balance `Provision`,
pm.provision_balance `Billing Pending`,
cost_center1 `cost_center1`,PO_Pending `PO Pending`,cost_center2 `cost_center2`,
GRN_Pending `GRN Pending`,cost_center3,InvoiceSubmit,'' `Agreement Pending`,
'' `Invoice No`, '' `PTP Date`,'' `payment`

FROM(SELECT tb1.company_name, tb1.branch, tb1.client, tb1.cost_center,tb1.finance_year,tb1.month,
GROUP_CONCAT(IF(tb1.po_required ='Yes',IF(tb1.approve_po!='Yes',tb1.cost_center,NULL),NULL) order by tb1.cost_center) `cost_center1`,
SUM(IF(tb1.po_required = 'Yes', IF(tb1.approve_po IS NULL OR tb1.approve_po ='',tb1.grnd,0),0)) `PO_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.cost_center,NULL),NULL)) order by tb1.cost_center) `cost_center2`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0)),
IF(tb1.grn='Yes',IF(tb1.approve_grn !='Yes',tb1.grnd,0),0))) `GRN_Pending`,
GROUP_CONCAT(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',NULL,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.cost_center,NULL),tb1.cost_center)) order by tb1.cost_center) `cost_center3`,
SUM(IF(tb1.po_required='Yes',IF(tb1.approve_po !='Yes',0,IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd)),
IF(tb1.grn='Yes',IF(tb1.approve_grn ='Yes',tb1.grnd,0),tb1.grnd))) `InvoiceSubmit`

FROM (
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti LEFT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
UNION
SELECT cm.company_name, cm.branch, cm.client, cm.po_required, cm.grn,cm.cost_center,
ti.finance_year,ti.month,ti.bill_no,ti.total,ti.grnd,ti.approve_po,ti.approve_grn
FROM tbl_invoice ti RIGHT JOIN cost_master cm ON ti.cost_center = cm.cost_center
WHERE ti.finance_year='2016-17' AND cm.branch='$branch' AND (ti.bill_no IS NOT NULL AND ti.bill_no !='')
)AS tb1
LEFT JOIN bill_pay_particulars bpp ON bpp.branch_name = tb1.branch AND bpp.company_name = tb1.company_name AND 
bpp.financial_year = tb1.finance_year AND bpp.bill_no = SUBSTRING_INDEX(tb1.bill_no,'/',1)
WHERE bpp.bill_no IS NULL 
GROUP BY tb1.month
) AS tb
RIGHT JOIN (SELECT branch_name,cost_center,`month`,finance_year,SUM(provision_balance) `provision_balance` FROM provision_master WHERE branch_name='$branch' GROUP BY `month`)AS pm
 ON tb.finance_year = pm.finance_year AND
tb.month = pm.month) AS tab GROUP BY `month` ORDER BY 
  STR_TO_DATE(CONCAT('1-',`month`),'%d-%b-%Y')
  
  ");		
		}
		$this->set('data',$data);
        $this->set('branch',$branch);
		
	
//        $this->set('data',$this->Provision->query("SELECT pm.provision `Billing Pending`,IF(pm.po_date IS NULL,'Action Date Pending','Received')`PO Pending`,
//IF(pm.grn_date IS NULL,'Pending','Received')`GRN Pending`,
//IF(pm.agreement IS NULL,'Pending','Received') `Agreement Pending`,
//GROUP_CONCAT(rm.InvoiceNo) `Invoice No`,GROUP_CONCAT(rm.ExpDatesPayment) `PTP Date`,
//SUM(tb.total) `Payment`
// FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
//INNER JOIN tbl_invoice tb  ON tb.cost_center = pm.cost_center 
//LEFT JOIN `receipt_master` rm ON rm.InvoiceNo = tb.bill_no
//WHERE (tb.finance_year NOT IN ('2014-15','2015-16') OR (tb.finance_year ='2016-17' AND SUBSTRING_INDEX(tb.month,'-',1) 
//NOT IN ('Jan','Feb','Mar'))) 
//AND pm.branch_name='$branch' GROUP BY pm.cost_center"));
    }
    
    public function provisionDetails()
    {
        $this->layout = "ajax";
        $branch = $this->params->query['branch'];
        
        $split = explode('@@', $this->params->query['branch']);
        $branch = $split['0'];
        $month = $split['1'];
        
        if($split['2']=='provision')
        {
		if($branch=='All') { $QryStr = "";} else { $QryStr = "AND pm.branch_name='$branch'"; }
                
        $data = $this->Provision->query("SELECT pm.branch_name,pm.cost_center `cost_center`, 
pm.provision `Provision`,
(pm.provision-pm.provision_balance) `Bill Raised`,
pm.provision_balance `balance`
 FROM provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center 
WHERE pm.provision_balance !=0 $QryStr and pm.month = '$month'");
        }
        else if($split['2']=='po')
        {
        if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
        
            if($month!='Previous')
			$data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE cm.po_required='Yes' AND (InitialInvoice.approve_po IS NULL OR InitialInvoice.approve_po = '') 
 $QryStr AND InitialInvoice.month = '$month' and bpp.bill_no is null AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
            else
            	$data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
        LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
    WHERE cm.po_required='Yes' AND (InitialInvoice.approve_po IS NULL OR InitialInvoice.approve_po = '') and InitialInvoice.finance_year IN ('2014-15','2015-16') and bpp.bill_no is null
    AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='') $QryStr
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
        }
        else if($split['2']=='grn')
        {
         if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
         
         if($month!='Previous')
		    $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE  InitialInvoice.finance_year='2016-17' $QryStr AND InitialInvoice.month = '$month' and bpp.bill_no is null 
 AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')   AND (
(cm.po_required='Yes' AND InitialInvoice.approve_po != ''
AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn =''))
OR
(cm.po_required = 'No' AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn ='')))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd ");
         else
            $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
 LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)               
WHERE  InitialInvoice.finance_year IN ('2014-15','2015-16') $QryStr and bpp.bill_no is null
 AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')   AND (
(cm.po_required='Yes' AND InitialInvoice.approve_po != ''
AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn =''))
OR
(cm.po_required = 'No' AND cm.grn = 'Yes' AND (InitialInvoice.approve_grn IS NULL OR InitialInvoice.approve_grn ='')))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");     
        }
        else if($split['2']=='invoice')
        {
         if($branch=='All') { $QryStr = "";} else { $QryStr = "AND InitialInvoice.branch_name = '$branch'"; }
         
         if($month!='Previous')
		    $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN provision_master pm ON pm.cost_center = InitialInvoice.cost_center AND pm.month = InitialInvoice.month
LEFT JOIN bill_pay_particulars bpp on bpp.company_name = cm.company_name and bpp.branch_name = InitialInvoice.branch_name AND
bpp.financial_year = InitialInvoice.finance_year and bpp.bill_no = substring_index(InitialInvoice.bill_no,'/',1)
WHERE  InitialInvoice.finance_year = '2016-17' AND bpp.bill_no IS NULL AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='')
$QryStr AND InitialInvoice.month = '$month' AND IF(cm.po_required='Yes',IF(InitialInvoice.approve_po='Yes',IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE),FALSE),
 IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");
         else
             $data = $this->InitialInvoice->query("SELECT InitialInvoice.* FROM tbl_invoice InitialInvoice INNER JOIN cost_master cm ON cm.cost_center = InitialInvoice.cost_center 
LEFT JOIN bill_pay_particulars bpp ON cm.company_name =  bpp.company_name AND
InitialInvoice.branch_name = bpp.branch_name AND InitialInvoice.finance_year = bpp.financial_year AND
SUBSTRING_INDEX(InitialInvoice.bill_no,'/',1) = bpp.bill_no
WHERE  InitialInvoice.finance_year = '2015-16' $QryStr AND bpp.bill_no IS NULL
AND (InitialInvoice.bill_no IS NOT NULL AND InitialInvoice.bill_no !='') AND IF(cm.po_required='Yes',IF(InitialInvoice.approve_po='Yes',IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE),FALSE),
 IF(cm.grn='Yes',IF(InitialInvoice.approve_grn='Yes',TRUE,FALSE),TRUE))
ORDER BY InitialInvoice.branch_name,DATE(STR_TO_DATE(CONCAT('1-',InitialInvoice.month),'%d-%b-%y')),InitialInvoice.grnd");
             ;
        }
        else
        {$data = "";}
        $this->set('report',$split['2']);
        $this->set('data',$data);
        $this->set('branch',$branch);
        $this->set('month',$month);
    }
    
    public function uploadProvision()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('username');
            $FileTye = $this->request->data['Provision']['file']['type'];
            $info = explode(".",$this->request->data['Provision']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['Provision']['file']['tmp_name'];
                $files = fopen($FilePath, "r");
                //$files = file_get_contents($FilePath);
                //echo $files;
                
               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
                $dataArr = array();
                $flag = false;
                while($row = fgetcsv($files,5000,","))
                {
                    if($flag)
                    {
                    $data['cost_center'] = $row[0];
                    $data['finance_year'] = $row[1];
                    $data['month'] = $row[2];
                    $data['provision'] = $row[3];
                    $data['provision_balance'] = $row[3];
                    $data['remarks'] = $row[4];
                    $dataArr[] = $data;
                    }
                    else {$flag = true;}
                }
                
                $this->TMPProvision->saveAll($dataArr);
                //print_r("LOAD DATA  INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center, finance_year, month, provision, remarks)"); die;
                $data = $this->TMPProvision->find('all',array('fields'=>array('branch_name','cost_center','provision','provision_balance','finance_year','month','remarks')));
		
                foreach($data as $a)
                {

                  $a['TMPProvision']['createdate'] = date('Y-m-d H:i:s');
                  
                if($a['TMPProvision']['finance_year'] == '')
                {
                    $a['TMPProvision']['Reasion'] = "finance year is blank";
                    $wrongData[] = $a;
                }
                else if($a['TMPProvision']['cost_center'] == '')
                {
                    $a['TMPProvision']['Reasion'] = "cost center is blank";
                    $wrongData[] = $a;
                }
                else if($a['TMPProvision']['month'] == '')
                { $a['TMPProvision']['Reasion'] = "cost center is blank"; $wrongData[] = $a; }
                   else if($this->Provision->find('first',array('conditions'=>
                       array('finance_year'=>$a['TMPProvision']['finance_year'],
                           'cost_center'=>$a['TMPProvision']['cost_center'],
                           'month'=>$a['TMPProvision']['month']))))
                    {$a['TMPProvision']['Reasion'] = "Provision Already Exists"; $wrongData[] = $a;}
                    else
                    {
                        $cost = $this->CostCenterMaster->find('first',array('fields'=>array('branch'),'conditions'=>array('cost_center'=>$a['TMPProvision']['cost_center'])));
                        if(!empty($cost))
                        {
                            $a['TMPProvision']['branch_name'] = $cost['CostCenterMaster']['branch']; 
                            if(!empty($a['TMPProvision']['branch_name']))
                            {
                                $a['TMPProvision']['provision_balance'] = $a['TMPProvision']['provision_balance'];
                                $this->Provision->saveAll($a['TMPProvision']);
                                unset($this->Provision);
                            }
                            else
                            {$a['TMPProvision']['Reasion'] = "Cost Center Not Found"; $wrongData[] = $a;}
                        }
                    }
                }
                $this->set('wrongData',$wrongData);
                $this->TMPProvision->query("truncate table tmp_provision_master");
                $this->Session->setFlash('File uploaded Success!');
               
            }
            $this->Session->setFlash('File Format not Valid');
            
    }
    }  
}

?>