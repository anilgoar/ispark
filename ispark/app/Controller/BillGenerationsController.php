<?php
class BillGenerationsController extends AppController 
{
    public $uses=array('CostCenterMaster','InitialInvoice','Addclient','Addbranch','BillMaster',
        'Addcompany','Addprocess','Category','Type','EditAmount','BranchBillingDetUpd');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny('index','report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','get_bill_generation');
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('index','report','get_type','get_report','get_report2','bill_genrate_report','get_report3','get_report4','get_bill_generation');
            
                $this->Auth->allow('get_update_bill_det');
            
                $this->Auth->allow('bill_det_up_report','get_report_bill_upd');
            
        }
    }
    
    public function get_update_bill_det()
    {
        $this->layout='home';
        $role = $this->Session->read('role');
        $FinanceYearLogin = $this->Session->read('FinanceYearLogin');
        $this->set('FinanceYearLogin',$FinanceYearLogin);
        if(strtolower($role)==strtolower('admin'))
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"),'active'=>1);
        }
        $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18','2018-19'))))));
        $this->set('branch_master',$branchMaster);
        
        
        
        
        
        if($this->request->is('POST'))
        {   
            $FinanceYear = $this->request->data['BillGenerations']['FinanceYear'];
            $FinanceMonth = $this->request->data['BillGenerations']['FinanceMonth'];
            $branch = $this->request->data['BillGenerations']['branch'];
            
            if(empty($this->request->data['Revenue_Provision']))
            {
                $Revenue_Provision = "0";
            }
            else
            {
                $Revenue_Provision = "1";
            }
            if(empty($this->request->data['Salary_Provision']))
            {
                $Salary_Provision = "0";
            }
            else
            {
                $Salary_Provision = "1";
            }
            if(empty($this->request->data['IDC_Provision']))
            {
                $IDC_Provision = "0";
            }
            else
            {
                $IDC_Provision = "1";
            }
            
            if(empty($this->request->data['Payment_Updation']))
            {
                $Payment_Updation = "0";
            }
            else
            {
                $Payment_Updation = "1";
            }
            
            if($this->BranchBillingDetUpd->find('first',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$branch'")))
            {
                $data_cond['FinanceYear'] =  $FinanceYear;
                $data_cond['FinanceMonth'] =  $FinanceMonth;
                $data_cond['BranchId'] =  $branch;
                $brach_fetch = $this->Addbranch->find('first',array('conditions'=>"id='$branch'"));
                $data['Branch'] =  "'".$brach_fetch['Addbranch']['branch_name']."'";
                $data['Revenue_Provision'] =  "'".$Revenue_Provision."'";
                $data['Salary_Provision'] =  "'".$Salary_Provision."'";
                $data['IDC_Provision'] =  "'".$IDC_Provision."'";
                $data['Payment_Updation'] =  "'".$Payment_Updation."'";
                $data['updated_at'] =  "'".date('Y-m-d H:i:s')."'";
                $data['updated_by'] =  "'".$this->Session->read('userid')."'";
                
                if($this->BranchBillingDetUpd->updateAll($data,$data_cond))
                {
                    $this->Session->setFlash("Record Updated Successfully.");
                }
                else
                {
                    $this->Session->setFlash("Record Not Updated. Please contact to Admin");
                }
                $this->redirect(array('action'=>'get_update_bill_det'));
            }
            else
            {
                $data['FinanceYear'] =  $FinanceYear;
                $data['FinanceMonth'] =  $FinanceMonth;
                $data['BranchId'] =  $branch;
                
                $brach_fetch = $this->Addbranch->find('first',array('conditions'=>"id='$branch'"));
                $data['Branch'] =  $brach_fetch['Addbranch']['branch_name'];
                $data['Revenue_Provision'] =  $Revenue_Provision;
                $data['Salary_Provision'] =  $Salary_Provision;
                $data['IDC_Provision'] =  $IDC_Provision;
                $data['Payment_Updation'] =  $Payment_Updation;
                $data['created_at'] =  date('Y-m-d H:i:s');
                $data['created_by'] =  $this->Session->read('userid');
                
                if($this->BranchBillingDetUpd->save(array('BranchBillingDetUpd'=>$data)))
                {
                    $this->Session->setFlash('<font color="green">Record Saved Successfully.</font>');
                }
                else
                {
                    $this->Session->setFlash('<font color="red">Record Not Saved. Please contact to Admin</font>');
                }
                $this->redirect(array('action'=>'get_update_bill_det'));
            }
        }
        
        $mnt = date('M'); 
        $year = date('Y'); 
        $branch_det = $this->Session->read("branch_name");
        $record = $this->BranchBillingDetUpd->find('first',array('conditions'=>"left(FinanceYear,4)='$year' and FinanceMonth='$mnt' and branch='$branch_det'"));
        //print_r($record); exit;
        $this->set('record',$record);
        $this->set('mnt',$mnt);
    }
        
    public function get_report_bill_upd()
    {
            $FinanceYear = $this->request->data['FinanceYear'];
            $FinanceMonth = $this->request->data['FinanceMonth'];
            $branch = $this->request->data['branch'];
            
            $record_arr = $this->BranchBillingDetUpd->find('all',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and branchId='$branch'"));
            
            echo '<table class="table  table-bordered table-hover table-heading no-border-bottom responstable" id="bill_table">';
            echo '<thead>
                    <tr>
                    <th>Branch</th>
                        <th>Revenue Provision</th>
                        <th>Salary Provision</th>
                        <th>IDC Provision</th>
                        <th>Payment Updation</th>
                        <th>Last Updated Date</th>
                    </tr>
                </thead><tbody>';
                
            foreach($record_arr as $record)
            {
                echo '<tr>';
                    echo '<td>';
                            echo $record['BranchBillingDetUpd']['Branch'];
                    echo '</td>';
                echo '<td>';
                            if(!empty($record['BranchBillingDetUpd']['Revenue_Provision']))
                            {
                                echo 'Updated';
                            }
                            else
                            {
                                echo 'Pending';
                            }
                    echo '</td>';
                    
                    echo '<td>';
                            if(!empty($record['BranchBillingDetUpd']['Salary_Provision']))
                            {
                                echo 'Updated';
                            }
                            else
                            {
                                echo 'Pending';
                            }
                    echo '</td>';
                    echo '<td>';
                            if(!empty($record['BranchBillingDetUpd']['IDC_Provision']))
                            {
                                echo 'Updated';
                            }
                            else
                            {
                                echo 'Pending';
                            }
                    echo '</td>';
                    echo '<td>';
                            if(!empty($record['BranchBillingDetUpd']['Payment_Updation']))
                            {
                                echo 'Updated';
                            }
                            else
                            {
                                echo 'Pending';
                            }
                    echo '</td>';
                    echo '<td>';
                           echo date('d-M-Y',$record['BranchBillingDetUpd']['updated_at']);
                    echo '</td>';
                    echo '</tr>';
            }
            echo "</tbody></table>";
            exit;
    }
    
    public function bill_det_up_report() 
    {
            $this->layout='home';
            
            $FinanceYearLogin = $this->Session->read('FinanceYearLogin');
    $this->set('FinanceYearLogin',$FinanceYearLogin);
        if($role=='admin')
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('branch_name'=>$this->Session->read("branch_name"),'active'=>1);
        }
        $branchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18','2018-19'))))));
        $this->set('branch_master',$branchMaster);
    }
        
        
        
        
    public function index() 
            {
            $this->layout='home';
             $this->set('company_master',$this->Addcompany->find('all',array('fields'=>array('company_name'))));
    }

public function get_bill_generation()
{
    $this->layout = 'ajax';
    $result = $this->params->query;
    $this->set('report','edit');
    $subdate = date_create($result['AddFromDate']);
    $Frmdate = date_format($subdate,'Y-m-d');
    $expdate = date_create($result['AddToDate']);
    $todate = date_format($expdate,'Y-m-d');
    $reportType = $result['ReportType'];
    
    $Comp_name ='';
    
    if($result['AddCompanyName']=='All')
    {
       $Comp_name ='';
    }
    else
    {
        
            $Comp_name ="and t3.company_name='".$result['AddCompanyName']."'";        
    }
    
    $branch_name = $result['Branch']=='all'?"":"and t2.branch_name='".$result['Branch']."'";
			
    if($reportType!='Details')
    {
        $dataX = $this->EditAmount->query("SELECT t2.branch_name,t2.cost_center,sum(t2.grnd) `amount` FROM amount_edit t1 INNER JOIN tbl_invoice t2 ON t1.initial_id=t2.id INNER JOIN cost_master t3 ON t2.cost_center=t3.cost_center WHERE DATE(t2.createdate) BETWEEN '$Frmdate' AND '$todate' $Comp_name $branch_name group by t2.cost_center");
        $this->set("result2",$dataX);
    }
    else
    {
        $data = $this->EditAmount->query("SELECT t2.branch_name,t2.cost_center,t2.invoiceDescription,t2.grnd,t2.invoiceDate,t2.month FROM amount_edit t1 INNER JOIN tbl_invoice t2 ON t1.initial_id=t2.id INNER JOIN cost_master t3 ON t2.cost_center=t3.cost_center WHERE DATE(t1.createdate) BETWEEN '$Frmdate' AND '$todate' $Comp_name $branch_name");
        $this->set("result",$data);
    }
    $this->set("type",$result['type']);
    $this->set("ReportType",$reportType);
    //$this->set('qry',"Select t2.branch_name,t2.cost_center,sum(t2.grnd) `amount`,t2.invoiceDate,t2.month from tbl_invoice t2 inner join cost_master t1 on t1.cost_center = t2.cost_center where t2.bill_no !='' AND t2.createdate between '$Frmdate' AND '$todate' $Comp_name $branch_name group by t2.cost_center");
}
	
}
?>