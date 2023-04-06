<?php
	class InvoiceReportsController extends AppController 
	{
		public $uses=array('InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster','VendorMaster',
                    'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount','Provision','PONumber','NotificationMaster','ExpenseEntryMaster');
		public $components = array('RequestHandler');
		public $helpers = array('Js');
		
		
public function beforeFilter()
{
    parent::beforeFilter();
			
    if(!$this->Session->check("username"))
    {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
    }
    else 
    {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->deny('index','export1','get_report11');

            if(in_array('4',$roles)){$this->Auth->allow('index','get_costcenter','get_gst_type','get_service_no');$this->Auth->allow('add','check_po_number');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}
            $this->Auth->allow('get_report11');
            $this->Auth->allow('export1');
           
    }			
    if ($this->request->is('ajax'))
     {
            $this->render('contact-ajax-response', 'ajax');
    }
}
 public function export1() {
	$this->layout = "home";
        
        
	if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         print_r($result); exit;
         
//print_r($pred); die;
       

     }}
       
        
         public function get_report11()
    {
            $this->layout = "ajax";
            if($this->request->is("post"))  
      {
                $rtpe=$this->request->data['rtype'];
	  	$result = $this->request->data['InvoiceReports'];
                $date=date_create($result['ToDate']);
 $Fdate= date_format($date,"Y-m-d");
 $date1=date_create($result['FromDate']);
 $Sdate= date_format($date1,"Y-m-d");
        if($rtpe=='Output'){
         $find= $this->InitialInvoice->query("SELECT branch_name,SUM(total)Tammount,SUM(IF(igst IS NOT NULL and igst != 0 ,total,0))igtsamount,
SUM(igst)igst,SUM(IF((sgst IS NOT NULL AND cgst IS NOT NULL)and (sgst !=0 and cgst !=0) ,total,0))sgtsamount ,SUM(sgst)sgst,SUM(cgst)cgst  FROM tbl_invoice WHERE  date(`grn_createdate`) between '$Fdate' and '$Sdate' GROUP BY branch_name;");
//print_r($rtpe); die;
      $this->set('Data',$find); 
        }
        
       else if($rtpe=='Input'){
       
        $VendorMaster = $this->VendorMaster->find('all',array('conditions'=>"Id>661 and TDSEnabled=1"));
        
        
        foreach($VendorMaster as $vm)
        {
            
            $vendorId = $vm['VendorMaster']['Id'];
            $exp = $this->ExpenseEntryMaster->query("Select cm.company_name,cm.Branch,FinanceYear,FinanceMonth,em.ApprovalDate,eep.Amount,eep.Rate,eep.Tax FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry   
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
where em.vendor='$vendorId' and date(em.createdate) between '$Fdate' and '$Sdate'
    
");
            
            foreach($exp as $d)
            {
                $data = array();
                $BranchState = $this->Addbranch->find('first',array('fields'=>array('branch_state'),'conditions'=>array('branch_name'=>$d['cm']['Branch'])));
                $VendorGSTNo = $this->Addbranch->query("SELECT GSTNo FROM tbl_state_comp_gst_details tscgd WHERE VendorId='$vendorId' LIMIT 1");
                $CompanyGSTNo = $this->Addbranch->query("SELECT ServiceTaxNo FROM tbl_service_tax tst WHERE company_name='".$d['cm']['company_name']."' AND State='".$BranchState['Addbranch']['branch_state']."' AND branch='".$d['cm']['Branch']."' LIMIT 1");
                
                $BranchMaster[] = $d['cm']['Branch'];
                $VendorMasterNew[] = $vm['VendorMaster']['TallyHead'];
                if(strtolower($BranchState['Addbranch']['branch_state'])==strtolower($vm['VendorMaster']['state']))
                {
                    $GSTType = 'state';
                }
                else
                {
                    $GSTType = 'central';
                }
                $tds = round(($d['eep']['Amount']*$vm['VendorMaster']['TDS'])/100,3);
                $data['Branch'] = $d['cm']['Branch'];
                $data['FinanceMonth'] = $d['em']['FinanceMonth'];
                $data['FinanceYear'] = $d['em']['FinanceYear'];
                $data['GSTType'] = $GSTType;
                $data['GSTEnable'] = $vm['VendorMaster']['GSTEnabled'];
                $data['TDSEnabled'] = $vm['VendorMaster']['TDSEnabled'];
                $data['TDS'] = $vm['VendorMaster']['TDS'];
                $data['TDSSection'] = $vm['VendorMaster']['TDSSection'];
                $data['TDSTallyHead'] = $vm['VendorMaster']['TDSTallyHead'];
                $data['TallyHead'] = $vm['VendorMaster']['TallyHead'];
                $data['PanNo'] = $vm['VendorMaster']['PanNo'];
                $data['GSTNo'] = $VendorGSTNo['0']['tscgd']['GSTNo'];
                $data['CompanyGSTNo'] = $CompanyGSTNo['0']['tst']['ServiceTaxNo'];
                $data['Amount'] = $d['eep']['Amount'];
                $data['Rate'] = $d['eep']['Rate'];
                
                if($GSTType=='central')
                {
                    //$data['IGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100),3);
                    $data['IGST'] = round($d['eep']['Tax'],3);
                    $data['SGST'] = 0;
                    $data['CGST'] = 0;
                }
                else 
                {
                    $data['IGST'] = 0;
                    //$data['SGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    //$data['CGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    $data['SGST'] = round($d['eep']['Tax']/2,3);
                    $data['CGST'] = round($d['eep']['Tax']/2,3);
                }
                $data['tdsAmount'] = $tds;
                $dataZ[] = $data; 
            }
            
        }
        
        $dataX = array();
        
        $tds1=0;
    foreach($dataZ as $dd)
    {
        if(in_array($dd['Branch'],$dataX))
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
            $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
             $dataX[$dd['Branch']][$dd['TallyHead']]['Rate'] = $dd['Rate'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
            $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
            $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                 $dataX[$dd['Branch']][$dd['TallyHead']]['Rate'] = $dd['Rate'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
        else
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                 $dataX[$dd['Branch']][$dd['TallyHead']]['Rate'] = $dd['Rate'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['Amount'] += $dd['Amount'];
                 $dataX[$dd['Branch']][$dd['TallyHead']]['Rate'] = $dd['Rate'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TallyHead']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
    }
        
       $BranchMaster = array_unique($BranchMaster);
        sort($BranchMaster);
        $VendorMasterNew = array_unique($VendorMasterNew);
        sort($VendorMasterNew);
      //  print_r($dataX);die;
        $this->set('BranchMaster',$BranchMaster);
        $this->set('VendorMasterNew',$VendorMasterNew);
        $this->set('dataX',$dataX);
        
        //print_r($BranchMaster); exit;
      }
        
        
        
        
$this->set('rrt',$rtpe); 
    }
    
      }  
  
      
      public function export_section_tds()
{
    $this->layout='ajax';
      
      if($this->request->is('POST'))
      {
          $Expense = $this->request->data;
      }
      else
      {
          $Expense = $this->params->query;
      }
      $this->set('monthF',$Expense['FinanceMonth'].'-'.$Expense['FinanceYear']);
      $this->set('type',$Expense['type']); 
      $qry = " and em.CompId='1' ";
      
        if($Expense['CompId']!='All')
        {
            $qry .= " and em.CompId='".$Expense['CompId']."'";
        }
        if($Expense['BranchId']!='All')
        {
            $qry .= " and eep.BranchId='".$Expense['BranchId']."'";
        }
        if($Expense['FinanceYear']!='All')
        {
            $qry .= " and em.FinanceYear='".$Expense['FinanceYear']."'";
        }
        if($Expense['FinanceMonth']!='All')
        {
            $qry .= " and date_format(em.ApprovalDate,'%b')='".$Expense['FinanceMonth']."'";
        }
       
        $VendorMaster = $this->VendorMaster->find('all',array('conditions'=>"Id>661 and TDSEnabled=1"));
        
        
        foreach($VendorMaster as $vm)
        {
            
            $vendorId = $vm['VendorMaster']['Id'];
            
            $exp = $this->ExpenseEntryMaster->query("Select cm.company_name,cm.Branch,FinanceYear,FinanceMonth,em.ApprovalDate,eep.Amount,eep.Rate,eep.Tax FROM `expense_entry_master` 
em INNER JOIN expense_entry_particular eep ON em.Id=eep.ExpenseEntry   
INNER JOIN cost_master cm ON cm.Id = eep.CostCenterId
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
where em.vendor='$vendorId' $qry
    
");
            
            foreach($exp as $d)
            {
                $data = array();
                $BranchState = $this->Addbranch->find('first',array('fields'=>array('branch_state'),'conditions'=>array('branch_name'=>$d['cm']['Branch'])));
                $VendorGSTNo = $this->Addbranch->query("SELECT GSTNo FROM tbl_state_comp_gst_details tscgd WHERE VendorId='$vendorId' LIMIT 1");
                $CompanyGSTNo = $this->Addbranch->query("SELECT ServiceTaxNo FROM tbl_service_tax tst WHERE company_name='".$d['cm']['company_name']."' AND State='".$BranchState['Addbranch']['branch_state']."' AND branch='".$d['cm']['Branch']."' LIMIT 1");
                
                $BranchMaster[] = $d['cm']['Branch'];
                $VendorMasterNew[] = $vm['VendorMaster']['TDSSection'];
                if(strtolower($BranchState['Addbranch']['branch_state'])==strtolower($vm['VendorMaster']['state']))
                {
                    $GSTType = 'state';
                }
                else
                {
                    $GSTType = 'central';
                }
                $tds = round(($d['eep']['Amount']*$vm['VendorMaster']['TDS'])/100,3);
                $data['Branch'] = $d['cm']['Branch'];
                $data['FinanceMonth'] = $d['em']['FinanceMonth'];
                $data['FinanceYear'] = $d['em']['FinanceYear'];
                $data['GSTType'] = $GSTType;
                $data['GSTEnable'] = $vm['VendorMaster']['GSTEnabled'];
                $data['TDSEnabled'] = $vm['VendorMaster']['TDSEnabled'];
                $data['TDS'] = $vm['VendorMaster']['TDS'];
                $data['TDSSection'] = $vm['VendorMaster']['TDSSection'];
                $data['TDSTallyHead'] = $vm['VendorMaster']['TDSTallyHead'];
                $data['TallyHead'] = $vm['VendorMaster']['TallyHead'];
                $data['PanNo'] = $vm['VendorMaster']['PanNo'];
                $data['GSTNo'] = $VendorGSTNo['0']['tscgd']['GSTNo'];
                $data['CompanyGSTNo'] = $CompanyGSTNo['0']['tst']['ServiceTaxNo'];
                $data['Amount'] = $d['eep']['Amount'];
                $data['Rate'] = $d['eep']['Rate'];
                
                if($GSTType=='central')
                {
                    //$data['IGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100),3);
                    $data['IGST'] = round($d['eep']['Tax'],3);
                    $data['SGST'] = 0;
                    $data['CGST'] = 0;
                }
                else 
                {
                    $data['IGST'] = 0;
                    //$data['SGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    //$data['CGST'] = round((($d['eep']['Amount']*$d['eep']['Rate'])/100)/2,3);
                    $data['SGST'] = round($d['eep']['Tax']/2,3);
                    $data['CGST'] = round($d['eep']['Tax']/2,3);
                }
                $data['tdsAmount'] = $tds;
                $dataZ[] = $data; 
            }
            
        }
        
        $dataX = array();
        
        $tds1=0;
    foreach($dataZ as $dd)
    {
        if(in_array($dd['Branch'],$dataX))
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
            $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
            $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
            $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
        else
        {
            if(in_array($dd['TallyHead'],$dataX[$dd['Branch']]))
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
            else
            {
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceMonth'] = $dd['FinanceMonth'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['FinanceYear'] = $dd['FinanceYear'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDS'] = $dd['TDS'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSSection'] = $dd['TDSSection'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TDSTallyHead'] = $dd['TDSTallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['TallyHead'] = $dd['TallyHead'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['PanNo'] = $dd['PanNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['GSTNo'] = $dd['GSTNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CompanyGSTNo'] = $dd['ServiceTaxNo'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['Amount'] += $dd['Amount'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['IGST'] += $dd['IGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['SGST'] += $dd['SGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['CGST'] += $dd['CGST'];
                $dataX[$dd['Branch']][$dd['TDSSection']]['tdsAmount'] += $dd['tdsAmount'];
                $tds1 +=$dd['tdsAmount'];
            }
        }
    }
        
       $BranchMaster = array_unique($BranchMaster);
        sort($BranchMaster);
        $VendorMasterNew = array_unique($VendorMasterNew);
        sort($VendorMasterNew);
        
        $this->set('BranchMaster',$BranchMaster);
        $this->set('VendorMasterNew',$VendorMasterNew);
        $this->set('dataX',$dataX);
        
        //print_r($dataX); exit;
              
      
    }    
    
      
}
?>