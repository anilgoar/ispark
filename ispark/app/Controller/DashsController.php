<?php
class DashsController extends AppController 
{
    public $uses=array('Addbranch','Dash','Targets','DashboardData','DashboardRevenue','BillMaster','CostCenterMaster','DashboardProcess','TMPdashboardTarget',
        'FreezeData','DashboardBusPart','Provision','ExpenseMaster','ExpenseMasterOld','ExpenseParticular','ExpenseParticularOld');
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
            $this->Auth->allow('dashboard','provisionDetails','showReport','get_dash_data','get_report11','view4','get_cost_center','view_process_report',
                    'view_process_report_freezed','get_actual_data','get_actual_data1','get_basic_direct_data','get_basic_indirect_data');
	}
    }
    
    
    public function view4()
    {
        $this->layout='ajax';
        $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
    }
    public function get_cost_center()
    {	
            $this->layout = "ajax";
            $branch = $this->request->data('branch');
            $tower = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>array('branch'=>$branch
     ,'active'=>'1',"(close>date(now()) or close is null)")));
    
    //print_r($tower); exit;
    if(!empty($tower))
    {
        foreach($tower as $tow)
        {
            $cost_arr[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
        }
    }
    
        $this->set('cost_arr',$cost_arr);
    }
    public function view()
    {	
        $this->layout = "home";
        $role = $this->Session->read('role');
        $branch_name = $this->Session->read('branch_name');
        $conditions = array('active'=>'1');
        if($role=='admin')
        {
            $branch = array("All"=>"All");
        }
        else
        {
          $conditions = array('branch_name'=>$branch_name,'active'=>'1');
        }
        $branch2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions));
        //$branch2 = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$conditions));
        if($role=='admin')
        {
            $branch2 = array('All'=>'All') +$branch2;
        }
        $this->set('branch_master', $branch2);
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    }
    
    
    public function get_dash_data()
    {
      $this->layout = "ajax";
      $result = $this->params->query; 
      $ReportType = $this->params->query['ReportType'];
      $finYear = $this->params->query['FinanceYear'];
      $finMonth = $this->params->query['FinanceMonth'];
      $Branch = $this->params->query['barnch'];
      $cost_id = $this->params->query['cost_center'];
      $type = $this->params->query['type'];
      //print_r($result); exit;
         $this->set("DataNew",$result);
         $this->set('ReportType',$ReportType);
         $this->set('Branch',$Branch);
         $this->set('FinanceYear',$finYear);
         $this->set('FinanceMonth',$finMonth);
         $this->set('type',$type);
         //echo strtolower($finMonth); exit;
         $YearArrRes = explode("-",$finYear);
         $currfinYear = $YearArrRes['1'];
         
        if(in_array(strtolower($finMonth),array('jan','feb','mar')))
        {
            $YearRes = $YearArrRes[0]+1;
        }
        else
        {
            $YearRes = $YearArrRes[0];
            $currfinYear = $YearArrRes[0]-2000;
        }
         
         
        // get values here 
       // echo $result['barnch'];exit;
         $revnue_table = 'provision_master';
         $budget_table_master = 'expense_master';
         $budget_table_particular = 'expense_particular';
         if($this->FreezeData->find('first',array('conditions'=>"Branch='$Branch' and FinanceYear='$finYear' and FinanceMonth='$finMonth' and ApproveStatus='2'")))
         {
            $revnue_table = 'dashboard_save_prov';
            $budget_table_master = 'expense_master_old';
            $budget_table_particular = 'expense_particular_old';
         }
         
        if($ReportType== 'Branch')
        {
            if($Branch!='All')
            {
                $BranchAll = " and cm.branch='$Branch'";
            }
            else
            {
                $BranchAll = '';
            }

               $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
    INNER JOIN cost_master cm ON dt.cost_centerId=cm.id and cm.active='1' $BranchAll
    WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth'   group by cost_centerId "; 

            $AspirationalData = $this->Targets->query($AspirationalQry);




            foreach($AspirationalData as $asp)
            {
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
                $cost_master[$asp['cm']['branch']][] = $asp['cm']['id'];
                $BranchArr[] =  $asp['cm']['branch'];
            }

            
            
            $Actual = $this->Targets->query("SELECT cm.id,cm.branch,dd.branch,cost_centerId,branch_process,
    `commit` Revenue,
    `commit2` commit2,
    direct_cost DirectCost,
    indirect_cost InDirectCost,
    direct_cost_commit2,
    indirect_cost_commit2
    FROM `dashboard_data` dd
    INNER JOIN cost_master cm ON dd.cost_centerId=cm.id and cm.active='1' $BranchAll
    WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth'   AND 
    dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
    AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth'  AND dd.cost_centerId=dd1.cost_centerId)");

            foreach($Actual as $bas)
            {
                    
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Commit']['revenue'] =  $bas['dd']['commit2'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];


                $cost_master[[$bas['cm']['branch']]][] = $bas['cm']['id'];
                $BranchArr[] =  $bas['cm']['branch'];
            }

            
           // print_r($NewData); exit;
    
           $NewFinanceMonth = $finMonth; 
        $monthArr = array('Jan','Feb','Mar'); 
            $split = explode('-',$finYear); 
            if(in_array($finMonth, $monthArr)) 
            {
                $NewFinanceMonth .= '-'.$split[1];    //Year from month
            }
            else
            {
                $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
            }

            $qr_revenue = "SELECT cm.id,cm.branch,pm.provision FROM $revnue_table pm
    LEFT JOIN 
    (
    SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center and cm.active='1' $BranchAll
     WHERE ti.invoiceType='Revenue' and ti.month='$finMonth-$currfinYear' and ti.finance_year='$finYear' group by cm.id) ti 
    ON pm.month = ti.month AND pm.cost_center = ti.cost_center
    INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center and cm.active='1' $BranchAll
    WHERE pm.invoiceType1='Revenue' and pm.month='$finMonth-$currfinYear' and pm.finance_year='$finYear'";

            $RevenueBasic = $this->Targets->query($qr_revenue);

            foreach($RevenueBasic as $rev_)
            {
                $NewData[$rev_['cm']['branch']][$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
                $cost_master[$rev_['cm']['branch']][] = $rev_['cm']['id'];
                $BranchArr[] =  $rev_['cm']['branch'];
            }
            $RevenuePart = $this->Targets->query("SELECT cm.id,cm.branch,pm.outsource_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center and cm.active='1' $BranchAll
WHERE   pm.FinanceMonth='$finMonth-$currfinYear' and pm.FinanceYear='$finYear'");
  
        foreach($RevenuePart as $rev_)
        {
            $NewData[$rev_['cm']['branch']][$rev_['cm']['id']]['Basic']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            //$NewData[$rev_['cm']['id']]['Actual']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
            //print_r($NewData); exit;

            //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
            //print_r($NewData); exit;
            $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM $budget_table_particular ep 
    INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ");

            foreach($DirectActualBusinessCase as $DirectBC)
            {
                $NewData[$DirectBC['cm']['branch']][$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];   
                $cost_master[$DirectBC['cm']['branch']][] = $DirectBC['cm']['id'];
                $BranchArr[] =  $DirectBC['cm']['branch'];
            }

            $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM $budget_table_particular ep 
    INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ");

            foreach($InDirectActualBusinessCase as $InDirectBC)
            {    
                $NewData[$InDirectBC['cm']['branch']][$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];    
                $cost_master[$InDirectBC['cm']['branch']][] = $InDirectBC['cm']['id'];
                $BranchArr[] =  $InDirectBC['cm']['branch'];
            }

            $BranchArr = array_unique($BranchArr);
            
            foreach($BranchArr as $Branch)
            {
                $NewCostMaster[$Branch] = array_unique($cost_master[$Branch]);
            }
            //$cost_master = $NewCostMaster;
            //$cost_master = array_unique($cost_master);
            $newCostMaster = array();
            foreach($NewCostMaster as $k=>$v)
            {
                $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$v)));
                

                foreach($cost_arr as $cost)
                {
                    $newCostMaster[$k][$cost['CostCenterMaster']['id']]['PrcoessName'] = $cost['CostCenterMaster']['process_name'];
                    $newCostMaster[$k][$cost['CostCenterMaster']['id']]['CostCenter'] = $cost['CostCenterMaster']['cost_center'];
                }
            }
            
            $BranchWisePnlDetails = "SELECT * FROM `pnl_branch_details` cm INNER JOIN `pnl_master` pm ON cm.pnlMasterId = pm.PnlMasterId
WHERE  FinanceYear='$finYear' AND FinanceMonth='$finMonth'";
            $BranchWisePnlDetailsRsc = $this->Targets->query($BranchWisePnlDetails);
         
            
            foreach($BranchWisePnlDetailsRsc as $branch_det)
            {
                $dataBranch_det[$branch_det['pm']['Description']] = round($branch_det['cm']['PnlAmount'],2);
                $dataBranch_opr[$branch_det['pm']['Description']] = $branch_det['pm']['pnl_operand'];
               
            }
            
           //print_r($dataBranch_opr); exit;
            
            sort($BranchArr);
            $this->set('BranchArr',$BranchArr);
            $this->set('CostCenter',$newCostMaster);
            $this->set('Data',$NewData); 
            $this->set('dataBranch_det',$dataBranch_det);
            $this->set('dataBranch_opr',$dataBranch_opr);
            
            ////////////////////////////////// For Freeze Request   ///////////////////////////////////////
        
        
        
        $Branch = $this->params->query['barnch'];
        $BranchQr = "";
        if($Branch!='All')
        {
            $BranchQr = "Branch='$Branch' AND";
        }
        
        $Select_Freeze_Data = "SELECT * FROM `dashboard_freeze_data_save` dfds WHERE $BranchQr FinanceYear='$finYear' AND FinanceMonth='$finMonth' and Freezed='2'";
        $Freeze_Data = $this->FreezeData->query($Select_Freeze_Data);
        $DataArr = array(); $BranchArr1 = array();
        foreach($Freeze_Data as $Freeze)
        {
            $BranchArr1[] = $Freeze['dfds']['Branch'];
            $DataArr[$Freeze['dfds']['Branch']][] = $Freeze;
        }
        
        $BranchArr1 = array_unique($BranchArr1);
        
        //print_r($BranchArr1); exit;
        
        $this->set('Freeze_Data1',$DataArr);
        $this->set('BranchArr1',$BranchArr1);

    }
        else if($ReportType== 'CostCenter')
        {
            if($cost_id!='All')
            {
                $cost_id = " and cm.id='$cost_id'";
            }
            else
            {
                $cost_id = '';
            }
            
           $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id and cm.active='1' $cost_id
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch'  group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        
        
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost,
direct_cost_commit2,
    indirect_cost_commit2
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id and cm.active='1' $cost_id
WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND  dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        
        foreach($Actual as $bas)
        {
            if(empty($TmpActual[$bas['dd']['cost_centerId']]))
            {
                $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
            }
            else
            {
                if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='') || $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['idc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                }
            }
            $cost_master[] = $bas['cm']['id'];
        }
        
//       $OSActual = $this->Targets->query("SELECT cm.id,SUM(total) os FROM tbl_invoice ti
//INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
//WHERE cm.id='' AND ti.bill_no!='' AND ti.status='0' AND ti.finance_year='2018-19' AND ti.month='Oct-18'
//GROUP BY ti.month,cm.id");
//        
//        foreach($OSActual as $os_)
//        {
//            $NewData[$os_['cm']['id']]['Processed']['revenue'] =  round($os_['0']['os']/100000,2);
//            $cost_master[] = $os_['cm']['id'];
//        } 
       $NewFinanceMonth = $finMonth; 
    $monthArr = array('Jan','Feb','Mar'); 
        $split = explode('-',$finYear); 
        if(in_array($finMonth, $monthArr)) 
        {
            $NewFinanceMonth .= '-'.$split[1];    //Year from month
        }
        else
        {
            $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
        }
       
        
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM $revnue_table pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center and cm.active='1' $cost_id
 WHERE ti.invoiceType='Revenue' and ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center and cm.active='1' $cost_id
WHERE pm.invoiceType1='Revenue' and pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        $RevenuePart = $this->Targets->query("SELECT cm.id,pm.outsource_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center  and cm.active='1'
WHERE  pm.Branch_OutSource='$Branch' and pm.FinanceMonth='$NewFinanceMonth'");
  
        foreach($RevenuePart as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            //$NewData[$rev_['cm']['id']]['Actual']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        //print_r($NewData); exit;
        
        //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($DirectActualBusinessCase as $DirectBC)
        {
            if(isset($NewBasicBusiness[$DirectBC['ep']['id']]))
            {
                
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $NewBasicBusiness[$DirectBC['ep']['id']];
            }
            else
            {
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];
            }
            $cost_master[] = $DirectBC['cm']['id'];
        }
        
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($InDirectActualBusinessCase as $InDirectBC)
        {
            if(isset($NewBasicBusiness[$InDirectBC['ep']['id']]))
            {
                //echo $NewBasicBusiness[$InDirectBC['ep']['id']]; exit;
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $NewBasicBusiness[$InDirectBC['ep']['id']];
            }
            else
            {
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];
            }
            $cost_master[] = $InDirectBC['cm']['id'];
        }
        
        
        $cost_master = array_unique($cost_master);
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
        foreach($cost_arr as $cost)
        {
            $cost_name = implode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = $cost['CostCenterMaster']['process_name'];
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $cost['CostCenterMaster']['cost_center'];
        }
        
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData); 
                    
        }
         

        
}
     public function get_report11()
    {
        $this->layout = "ajax";
      $result = $this->params->query; 
         $this->set("DataNew",$result);

        // get values here 
       // echo $result['barnch'];exit;
         if($result['select1']== 'Branch'){
              $this->set("Data",
           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md, SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,IF(mde IS NULL OR mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (
SELECT *,n.brac branch FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,ft.branch brac,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
 FROM dashboard_data dd 
JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,nd.branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.company_name,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
nd.cost_centerId = fdp.id WHERE   ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
   GROUP BY cost_centerId ) ft
ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId    GROUP BY dd.cost_centerId )n
LEFT JOIN
(
SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process bpro,ld.company_name,
SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId cci FROM `dashboard_Target` dt
JOIN
(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process,dp.id,dp.company_name FROM dashboard_data pr JOIN `cost_master` dp ON  
  pr.cost_centerId = dp.id and dp.active='1' WHERE   ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
GROUP BY pr.cost_centerId)ld
 ON DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
    AND dt.cost_centerId= ld.id  GROUP BY dt.cost_centerId  
)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId
)t GROUP BY branch ORDER BY branch"
                   ));
               
         }
         
//    elseif($result['select1']== 'Process'){
//       $this->set("Data",
//           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md , SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,if(mde is null or mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (
//SELECT *,n.brac branch FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
//SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,ft.branch brac,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
// FROM dashboard_data dd 
//JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,nd.branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.company_name,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
//nd.cost_centerId = fdp.id WHERE  ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
//   GROUP BY cost_centerId ) ft
//ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId    GROUP BY dd.cost_centerId )n
//LEFT JOIN
//(
//SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process bpro,ld.company_name,
//SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId cci FROM `dashboard_Target` dt
//JOIN
//(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process,dp.id,dp.company_name FROM dashboard_data pr JOIN `cost_master` dp ON  
//  pr.cost_centerId = dp.id  WHERE   ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
//GROUP BY pr.cost_centerId)ld
// ON  DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
//    AND dt.cost_centerId= ld.id  GROUP BY dt.cost_centerId  
//)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId
//)t GROUP BY branch_process ORDER BY branch
//
// 
//
//"));
       
      
  //  }
    else if($result['barnch1']!=''&&$result['select1']== 'CostCenter')
    {     // echo $result['barnch'];exit;
        
 
     
    
     
     
     
     
      $this->set("Data",
           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md , SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,if(mde is null or mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (SELECT * FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,dd.branch,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
 FROM dashboard_data dd 
JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,fdp.Branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
nd.cost_centerId = fdp.id and fdp.active='1' AND ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
 WHERE fdp.branch = '{$result['barnch1']}' GROUP BY cost_centerId ) ft
ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId WHERE  dd.branch = '{$result['barnch1']}' GROUP BY dd.cost_centerId )n
LEFT JOIN
(
SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process `bpro`,
SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId `cci` FROM `dashboard_Target` dt
JOIN
(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process ,dp.id FROM dashboard_data pr JOIN `cost_master` dp ON  
  pr.cost_centerId = dp.id and dp.active='1' WHERE pr.branch = '{$result['barnch1']}' AND ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
GROUP BY pr.cost_centerId)ld
 ON  DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
    AND dt.cost_centerId= ld.id WHERE dt.branch = '{$result['barnch1']}' GROUP BY dt.cost_centerId  
)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId)t GROUP BY cost_centerId

"));  
    
        
    }
       
        
    }
    
    public function view_process_report()
    {
        $this->layout="home";
        $finYear = $this->params->query['finYear'];
        $finMonth = $this->params->query['finMonth'];
        $Branch = $this->params->query['Branch'];
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        
        $YearArrRes = explode("-",$finYear);
        if(in_array(strtolower($finMonth),array('jan','feb','mar')))
        {
            $YearRes = $YearArrRes[0]+1;
        }
        else
        {
            $YearRes = $YearArrRes[0];
        }
        $currfinYear = $YearArrRes['1'];
        $revnue_table = 'provision_master';
         $budget_table_master = 'expense_master';
         $budget_table_particular = 'expense_particular';
//         if($this->FreezeData->find('first',array('conditions'=>"Branch='$Branch' and FinanceYear='$finYear' and FinanceMonth='$finMonth' and ApproveStatus='2'")))
//         {
//            $revnue_table = 'dashboard_save_prov';
//            $budget_table_master = 'expense_master_old';
//            $budget_table_particular = 'expense_particular_old';
//         }
        
        
        
             
            
           $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id and cm.active='1' $cost_id
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch'  group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        //print_r($AspirationalData); exit;
       
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
      
//        echo "SELECT cm.id,dd.branch,cost_centerId,branch_process,
//`commit` Revenue,
//`commit2` commit2,
//direct_cost DirectCost,
//indirect_cost InDirectCost,
//direct_cost_commit2,
//    indirect_cost_commit2,
//DATE_FORMAT(dd.createdate,'%d-%b-%y') LastUpdateDate
//FROM `dashboard_data` dd
//INNER JOIN cost_master cm ON dd.cost_centerId=cm.id and cm.active='1' $cost_id
//WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND  dd.branch='$Branch'  AND 
//dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
//AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)"; exit;
        
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
`commit2` commit2,
direct_cost DirectCost,
indirect_cost InDirectCost,
direct_cost_commit2,
    indirect_cost_commit2,
DATE_FORMAT(dd.createdate,'%d-%b-%y') LastUpdateDate
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id and cm.active='1' $cost_id
WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND  dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        $LastUpdatedDate = array();
        foreach($Actual as $bas)
        {
            $LastUpdatedDate[$bas['dd']['cost_centerId']] = $bas['0']['LastUpdateDate'];
            if(empty($TmpActual[$bas['dd']['cost_centerId']]))
            {
                $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                
            }
            else
            {
                if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='') || $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['idc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                }
            }
            
            $NewData[$bas['dd']['cost_centerId']]['Commit']['revenue'] =  $bas['dd']['commit2'];
            $NewData[$bas['dd']['cost_centerId']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2'];
            $NewData[$bas['dd']['cost_centerId']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2'];
            $cost_master[] = $bas['cm']['id'];
        }
        $this->set('LastUpdatedDate',$LastUpdatedDate);
        
        //print_r($cost_master); exit;
        
        
//       $OSActual = $this->Targets->query("SELECT cm.id,SUM(total) os FROM tbl_invoice ti
//INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
//WHERE cm.id='' AND ti.bill_no!='' AND ti.status='0' AND ti.finance_year='2018-19' AND ti.month='Oct-18'
//GROUP BY ti.month,cm.id");
//        
//        foreach($OSActual as $os_)
//        {
//            $NewData[$os_['cm']['id']]['Processed']['revenue'] =  round($os_['0']['os']/100000,2);
//            $cost_master[] = $os_['cm']['id'];
//        } 
       $NewFinanceMonth = $finMonth; 
    $monthArr = array('Jan','Feb','Mar'); 
        $split = explode('-',$finYear); 
        if(in_array($finMonth, $monthArr)) 
        {
            $NewFinanceMonth .= '-'.$split[1];    //Year from month
        }
        else
        {
            $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
        }
       
//       echo "SELECT cm.id,pm.provision FROM $revnue_table pm
//LEFT JOIN 
//(
//SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
//INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center and cm.active='1' $cost_id
// WHERE ti.invoiceType='Revenue' and left(ti.month,3)='$finMonth' and ti.finance_year='$finYear' group by cm.id) ti 
//ON pm.month = ti.month AND pm.cost_center = ti.cost_center
//INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center and cm.active='1' $cost_id
//WHERE pm.invoiceType1='Revenue' and pm.branch_name='$Branch' and left(pm.month,3)='$finMonth' and pm.finance_year='$finYear'"; exit; 
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM $revnue_table pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center and cm.active='1' $cost_id
 WHERE ti.invoiceType='Revenue' and ti.month='$finMonth-$currfinYear' and ti.finance_year='$finYear' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center and cm.active='1' $cost_id
WHERE pm.invoiceType1='Revenue' and pm.branch_name='$Branch' and pm.month='$finMonth-$currfinYear' and pm.finance_year='$finYear'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
//        echo "SELECT cm.id,pm.outsource_amt FROM provision_particulars pm
//INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center  and cm.active='1'
//WHERE  pm.Branch_OutSource='$Branch' and left(pm.FinanceMonth,3)='$finMonth' and pm.FinanceYear='$finYear'"; exit;
        
        $RevenuePart = $this->Targets->query("SELECT cm.id,pm.outsource_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center  and cm.active='1'
WHERE  pm.Branch_OutSource='$Branch' and pm.FinanceMonth='$finMonth-$currfinYear' and pm.FinanceYear='$finYear'");
  
        foreach($RevenuePart as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            //$NewData[$rev_['cm']['id']]['Actual']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        //print_r($cost_master); exit;
        //print_r($NewData); exit;
        
        //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
//        echo "SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
//INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
//INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id and cm.active='1'
//INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
//WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
//AND em.branch='$Branch' "; exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id and cm.active='1'
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($DirectActualBusinessCase as $DirectBC)
        {
            if(isset($NewBasicBusiness[$DirectBC['ep']['id']]))
            {
                
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $NewBasicBusiness[$DirectBC['ep']['id']];
            }
            else
            {
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];
            }
            $cost_master[] = $DirectBC['cm']['id'];
        }
        
//        echo "SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
//INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
//INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $cost_id
//INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
//WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
//AND em.branch='$Branch' "; exit;
        
       // print_r($cost_master); exit;
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1' $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($InDirectActualBusinessCase as $InDirectBC)
        {
            if(isset($NewBasicBusiness[$InDirectBC['ep']['id']]))
            {
                //echo $NewBasicBusiness[$InDirectBC['ep']['id']]; exit;
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $NewBasicBusiness[$InDirectBC['ep']['id']];
            }
            else
            {
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];
            }
            $cost_master[] = $InDirectBC['cm']['id'];
        }
        
        
        $cost_master = array_unique($cost_master);
        //print_r($cost_master); exit;
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
       // print_r($cost_arr); exit;
        
        
        foreach($cost_arr as $cost)
        {
            $cost_name = explode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].'/'.$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = substr($cost['CostCenterMaster']['process_name'],0,12);
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $new_cost_name;
        }
        //print_r($NewData); exit;
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData); 
                    
        
        
        
        
    }
     
    
    public function view_process_report_freezed()
    {
        $this->layout="home";
        $finYear = $this->params->query['finYear'];
        $finMonth = $this->params->query['finMonth'];
        $Branch = $this->params->query['Branch'];
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
         
        $Select_Freeze_Data = "SELECT dfds.*,cm.cost_center,cm.process_name FROM `dashboard_freeze_data_save` dfds inner join cost_master cm on dfds.CostCenterId=cm.id and cm.active='1' WHERE dfds.Branch='$Branch' AND dfds.FinanceYear='$finYear' AND dfds.FinanceMonth='$finMonth'";
        $Freeze_Data = $this->FreezeData->query($Select_Freeze_Data);
        
        
        
        $this->set('Freeze_Data',$Freeze_Data);
        //$this->set('BranchArr1',$BranchArr1);    
                    
        
        
        
        
    }
    
    
    public function get_actual_data()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $cost_id = $this->request->data['cost_id'];
        $bas = $this->request->data['bas'];
        $asp = $this->request->data['asp'];
        $act = $this->request->data['act'];
        $type = $this->request->data['type'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        
        //$ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE ";
        $NewData1 = $this->FreezeData->find('first',array('conditions'=>"CostCenterId='$cost_id' AND FinanceYear='$finYear' AND FinanceMonth='$finMonth'"));
        $this->set('NewData1',$NewData1);
        
        
        $this->set('bas',$bas);
        $this->set('asp',$asp);
        $this->set('act',$act);
        $this->set('type',$type);
    }
    
    public function get_actual_data1()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $cost_id = $this->request->data['cost_id'];
        $bas = $this->request->data['bas'];
        $asp = $this->request->data['asp'];
        $act = $this->request->data['act'];
        $type = $this->request->data['type'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        
        //$ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE ";
        $NewData1 = $this->FreezeData->find('first',array('conditions'=>"CostCenterId='$cost_id' AND FinanceYear='$finYear' AND FinanceMonth='$finMonth'"));
        $this->set('NewData1',$NewData1);
        $select = "Select *,date_format(dd.createdate,'%d-%b-%y')EntryDate from dashboard_data dd inner join cost_master cm on dd.cost_centerId = cm.id and cm.active='1' where FinanceYear='$finYear' and FinanceMonth='$finMonth' and cost_centerId='$cost_id' order by date(dd.createdate) desc";
        $this->set("Data",$this->DashboardData->query($select));
        
        $ParticularDetails = $this->CostCenterMaster->query("SELECT * FROM `dashboard_cost_parts` parts ORDER BY Priority");
        $this->set('ParticularDetails',$ParticularDetails);
        
        $costTotal = 0; $MtdTotal=0; $Forecast = 0;
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                $data1 = $this->DashboardRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='2019-$finMonth'","order"=>array("created_at"=>"desc")));
                $cost_data['cost'.$headerId] = !empty($data1['DashboardRevenue']['CostCenterMonthDet'])?$data1['DashboardRevenue']['CostCenterMonthDet']:0;
                $MtdTotal = !empty($data1['DashboardRevenue']['Mtd'])?$data1['DashboardRevenue']['Mtd']:0;
                //$Forecast = !empty($data1['DashboardRevenue']['Forecast'])?$data1['DashboardRevenue']['Forecast']:0;
                //if($headerId=='2') {print_r($data1); exit;}
                $cost_data['mtd'.$headerId] = !empty($data1['DashboardRevenue']['Mtd'])?$data1['DashboardRevenue']['Mtd']:0;
                if($headerId=='1')
                {
                    //echo "(SELECT MAX(insertDate) FROM dashboard_data_revenue where costCenterId='$cost_id' and HeaderId='$headerId' and date_format('%Y-%b',insertDate)='2019-$finMonth')"; exit;
                    //print_r($data1); exit;
                }
                if(empty($cost_data['forecast'.$headerId]))
                {
                $cost_data['forecast'.$headerId] = !empty($data1['DashboardRevenue']['Forecast'])?$data1['DashboardRevenue']['Forecast']:0;
                }
                //print_r($data1); exit;
                //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                if($parts['parts']['RateRequired'])
                {
                    $cost_data['costRate'.$headerId] = !empty($data1['DashboardRevenue']['CostCenterMonthDet'])?$data1['DashboardRevenue']['CostCenterMonthDet']:0;
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['DashboardRevenue']['CostCenterMonthDet']*$data1['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        else
                        {
                            $costTotal += $data1['DashboardRevenue']['CostCenterMonthDet']*$data1['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                    }
                }
                else
                {
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['DashboardRevenue']['CostCenterMonthDet'];
                        }
                        else
                        {
                        $costTotal += $data1['DashboardRevenue']['CostCenterMonthDet'];
                        }
                    }
                }
            }
            
            $month_arr = array("Jan"=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>"08",'Sep'=>"09","Oct"=>"10","Nov"=>'11','Dec'=>'12');
            $m=1;
            //echo $finMonth;
            $mnt = $month_arr[$finMonth];
            if(in_array($mnt,array("01","02","03")))
            {
                $year = date("Y");
            }
            else
            {
                $year = date("Y");
            }
                        
            $date_new =  explode("-",date('t',strtotime("01-$mnt-$year"))); 
            $m=$date_new[0];
            $m++;
            $n = 1; $mtd_old = 0; $count = 0;
            while($m!=0)
            {
                $m--; 
                $costTotal1 = 0; $costTotal2=0; $headerTotalFlag = true;
                foreach($ParticularDetails as $parts)
                {
                    $headerId = $parts['parts']['PartId'];
                    $day = 00;
                    if(strlen($m)==1)
                    {
                        $day = '0'.$m;
                    }
                    else
                    {
                        $day = $m;
                    }
                    $data1 = $this->DashboardRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and HeaderId='$headerId' and DATE_FORMAT(insertDate,'%Y-%b-%d')='$year-$finMonth-$day'"));
                    if(!empty($data1) && $headerTotalFlag)
                    {
                        $count += 1;$headerTotalFlag=false;
                    }
                    if(!empty($data1['DashboardRevenue']['CostCenterMonthDet']))
                    {
                        $cost_data['cost'.$headerId] = $data1['DashboardRevenue']['CostCenterMonthDet'];
                    }
                    $cost_data['date'.(int)$m.'_'.$headerId] = !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
                    //$cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
                    //print_r($data1); exit;
                    //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                    
                    $cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
//                    if(!empty($data1['DashboardRevenue']['Forecast']))
//                    {
//                        $cost_data['forecast'.$headerId] = !empty($data1['DashboardRevenue']['Forecast'])?$data1['DashboardRevenue']['Forecast']:0;
//                    }
                    if($parts['parts']['RateRequired'])
                    {
                        $cost_data['HeadTotalRate'.$headerId] += !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        $cost_data['dateRate'.$m.'_'.$headerId] = !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        if(!empty($data1['DashboardRevenue']['CostCenterMonthDetRate']))
                        {
                            $cost_data['costRate'.$headerId] = $data1['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['DashboardRevenue']['insertDateDet']*$data1['DashboardRevenue']['insertDateRate'];
                                $costTotal2 -= $data1['DashboardRevenue']['CostCenterMonthDet']*$data1['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            else
                            {
                                $costTotal1 += $data1['DashboardRevenue']['insertDateDet']*$data1['DashboardRevenue']['insertDateRate'];
                                $costTotal2 += $data1['DashboardRevenue']['CostCenterMonthDet']*$data1['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        }
                    }
                    else
                    {
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['DashboardRevenue']['insertDateDet'];
                                $costTotal2 -= $data1['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            else
                            {
                                $costTotal1 += $data1['DashboardRevenue']['insertDateDet'];
                                $costTotal2 += $data1['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        }
                    }
                }
                
                $cost_data['DateTotal'.(int)$m] = !empty($costTotal1)?$costTotal1:0;
                $mtd_old += !empty($costTotal1)?$costTotal1:0;
                $n++;
            }
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                $cost_data['HeadTotalRate'.$headerId] = round($cost_data['HeadTotalRate'.$headerId]/$count,2);
            }
            $cost_data['mtd_old'] = $mtd_old;
            $cost_data['MtdTotal'] = $MtdTotal;
            $cost_data['CostTotal'] = $costTotal;
            $cost_data['ForecastTotal'] = $Forecast;
            $this->set('cost_data',$cost_data);
            
        $this->set('bas',$bas);
        $this->set('asp',$asp);
        $this->set('act',$act);
        $this->set('type',$type);
        $this->set('cost_id',$cost_id);
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
    }
    
    public function get_basic_direct_data()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $Branch = $this->request->data['Branch'];
        $cost_id = $this->request->data['cost_id'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        $this->set('cost_id',$cost_id);
        
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular_old ep 
INNER JOIN expense_master_old em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1'
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' order by hm.HeadingDesc,sh.SubHeadingDesc"; 
        $this->set("Data",
           $this->DashboardProcess->query($select));
        
        $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        $this->set('NewData',$NewData);
    }
    
    public function get_basic_indirect_data()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $Branch = $this->request->data['Branch'];
        $cost_id = $this->request->data['cost_id'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        $this->set('cost_id',$cost_id);
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular_old ep 
INNER JOIN expense_master_old em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id and cm.active='1'
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' order by hm.HeadingDesc,sh.SubHeadingDesc"; 
        $this->set("Data",
           $this->DashboardProcess->query($select));
        
        $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        $this->set('NewData',$NewData);
    }
    
    
    
    
    
       
    }

?>