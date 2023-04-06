<?php
class TargetsController extends AppController 
{
    public $uses=array('Targets','BillMaster','Addbranch','month','process','DashboardProcess','CostCenterMaster','TMPdashboardTarget',
        'DashboardData','FreezeData','DashboardBusPart','Provision','ExpenseMaster','ExpenseMasterOld','ExpenseParticular','ExpenseParticularOld');
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
            $role=$this->Session->read("role"); $this->Auth->allow('view_freeze_request','freeze_request','save_freeze_data','get_actual_data',
                    'get_basic_direct_data','get_basic_direct_data1','get_basic_indirect_data','get_basic_indirect_data1','save_actual_data','view_freeze_request_for_approval','view_freeze_data',
                    'freeze_branch','save_basic_indirect','save_basic_indirect1','disapprove_feeze_request');
            $roles=explode(',',$this->Session->read("page_access"));
            if(in_array('4',$roles)){$this->Auth->allow('index','add','get_process');}
            if(in_array('38',$roles)){$this->Auth->allow('get_dash_data','index','add','get_process','get_tower','upload_target');}
	}
    }
		
    
    
    public function index() 
    {
       $this->layout="home";
       
      $branchName = $this->Session->read('branch_name');
      //print_r($this->Session->read('role')); exit;
        if($this->Session->read('role')=='admin')
        {
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'))));
        }
        else if(count($branchName)>1)
        {
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else
        {
            $this->set('branchName',array($branchName=>$branchName));
            $this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
        }

    }
     public function add()
    {
            $this->layout="home";
            $data['createdate'] = date('Y-m-d H:i:s');
            $data['user_id'] = $this->Session->read('userid');
            
            
        if($this->request->is('POST'))
        {
            $request = $this->request->data['Targets'];
            //print_r($request); exit;
            
            
            $arrayMonth = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
            $MntArr = explode("-",$request['month']);
            $FinanceYear =  $MntArr[0];
            $FinanceMonth =  $arrayMonth[$MntArr[1]]; 
            
            if(in_array($FinanceMonth,array('1','2','3')))
            {
                $NewFinanceYear = $FinanceYear-2000;
                $NewFinanceYear = $FinanceYear.'-'.($NewFinanceYear-1);
            }
            else
            {
                $NewFinanceYear = $FinanceYear-2000;
                $NewFinanceYear = $FinanceYear.'-'.($NewFinanceYear+1);
            }
            
           $CostCenterIdSelected = $cs = $request['cost_centerId']; 
            $branchSelected = $b= $request['branch']; 
            //$branchProcessSelected = $bp = $request['branch_process'];
            $monthSelected = $m = $request['month'];
            
            
            $save = true; $msg ="";
            
            $BranchDet = $this->Addbranch->find('first',array('conditions'=>"branch_name='$branchSelected' and active=1"));
            $BranchId = $BranchDet['Addbranch']['id']; //taking branch id from branch name
            
            $CostCenterDet = $this->CostCenterMaster->find('first',array('conditions'=>"id='$CostCenterIdSelected' and active=1 and (close>=date(now()) || close is null)"));
            $cost_center =  $CostCenterDet['CostCenterMaster']['cost_center'];
            $processName = $CostCenterDet['CostCenterMaster']['process_name'];
            if(empty($BranchDet))
            {
                $save = false;
                $msg ="<span style='color:red'> Branch Not Found or Not Active</span>";
            }
            else if(empty($CostCenterDet))
            {
                $save = false;
                $msg ="<span style='color:red'> Cost Center Not Active or Cost Center Not Found</span>";
            }
            else if($this->Targets->find('first', array(
                        'conditions' => array(
                            'Targets.cost_centerId'=>$CostCenterIdSelected,
                            'Targets.branch' => $branchSelected,
                            //'Targets.branch_process' => $branchProcessSelected,
                            'Targets.month' => $monthSelected
                        )
                    )))
            {
                $save = false;
                $msg ="<span style='color:red'>Entry Exist For Process '$cost_center-$processName'  To $FinanceMonth</span>";
            }
            //echo $msg; exit;
            if($save)
            {
                $data['branch'] = addslashes($request['branch']);
                $data['branch_process'] = addslashes($request['branch_process']);
                $data['cost_centerId'] = addslashes($request['cost_centerId']);
                $data['target'] = addslashes($request['target']);
                $data['target_directCost'] = addslashes($request['target_directCost']);
                $data['target_IDC'] = addslashes($request['target_IDC']); 
                $data['month'] = addslashes($request['month']);
                $data['FinanceYear'] = addslashes($NewFinanceYear);
                $data['FinanceMonth'] = addslashes($FinanceMonth);
                $data['branch_id'] = addslashes($BranchId);
                $data['cost_center'] = addslashes($cost_center);
                $data['cost_centerId'] = addslashes($CostCenterIdSelected);
                //print_r($data); exit;
                 $qryOS = "SELECT cm.id,SUM(grnd-if(net_amount is null || net_amount='',0,net_amount)-if(tds_ded is null || tds_ded='',0,tds_ded))grnd FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
LEFT JOIN bill_pay_particulars bpp ON cm.company_name = bpp.company_name
AND cm.branch = bpp.branch_name AND ti.finance_year = bpp.financial_year
AND SUBSTRING_INDEX(ti.bill_no,'/','1')=bpp.bill_no WHERE cm.id='$CostCenterIdSelected'"
               . " AND ti.month=DATE_FORMAT(SUBDATE('$monthSelected',INTERVAL 1 MONTH),'%b-%y')";  
                
                 
                $dataOS = $this->Targets->query($qryOS);
                //print_r($dataOS); exit;
                $qryOS = "SELECT cm.id,SUM(provision_balance*1.18)grnd FROM provision_master pm 
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
 WHERE cm.id='$CostCenterIdSelected'"
               . " AND pm.month=DATE_FORMAT(SUBDATE('$monthSelected',INTERVAL 1 MONTH),'%b-%y')";  
                
                 
                $dataPro = $this->Targets->query($qryOS);
                
               // print_r($dataOS); exit;
                if(empty($dataOS['0']['0']['grnd']))
                {
                    $dataOS['0']['0']['grnd'] = 0;
                }
                if(empty($dataPro['0']['0']['grnd']))
                {
                    $dataPro['0']['0']['grnd'] = 0;
                }
                
             $FinanceCost = (($dataOS['0']['0']['grnd']+$dataPro['0']['0']['grnd'])*(1.83/100)); 
             $data['finance_cost'] = round($FinanceCost,2);
             $data['os'] = round($dataOS['0']['0']['grnd']+$dataPro['0']['0']['grnd'],2);
             
                if($this->Targets->save($data))
                {   
                    $ca = $data['month'];
                    $d = new DateTime($ca);

                    $timestamp = $d->getTimestamp(); // Unix timestamp
                    $formatted_date = $d->format('M-y');
                    $this->Session->setFlash("<span style='color:green'>Aspirational Target set For ". $formatted_date."</span>");
                }
                else 
                {
                    
                    $ca = $data['month'];
                    $d = new DateTime($ca);

                    $timestamp = $d->getTimestamp(); // Unix timestamp
                    $formatted_date = $d->format('M-y');
                    $this->Session->setFlash("<span style='color:red'>Aspirational Target not set of". $formatted_date."</span>");
                }
           }
            else
            {
                
                $this->Session->setFlash($msg);
            }
            
           
            return $this->redirect(array('action'=>'index'));
        }
    }
     public function get_dash_data()
    {
        $this->layout='ajax';
      // print_r($this->request->data); exit;
        if($this->request->is('POST'))
        {
         $branch = $this->request->data('branch_name');  
         if($branch == 'All')
         {
            $this->set("Data",
           $this->AgreementParticular->query("SELECT DATE_FORMAT(dd.createdate,'%d-%b-%y') cd,dt.target, dd.branch,SUM(`commit`) cmt,SUM(`direct_cost`) dc,ROUND(SUM(`indirect_cost`),2) idc 
FROM dashboard_data dd 
JOIN (SELECT MAX(createdate) reatedate FROM dashboard_data GROUP BY branch,branch_process) tt
ON DATE(dd.createdate)=DATE(tt.reatedate) JOIN  `dashboard_Target` dt ON DATE_FORMAT(dd.createdate,'%b-%y') = DATE_FORMAT(dt.month,'%b-%y') GROUP BY branch"));
       
            
        }
 else {
      $this->set("Data",
           $this->AgreementParticular->query("SELECT DATE_FORMAT(dd.createdate,'%d-%b-%y') cd,dt.target, dd.branch,SUM(`commit`) cmt,SUM(`direct_cost`) dc,ROUND(SUM(`indirect_cost`),2) idc 
FROM dashboard_data dd 
JOIN (SELECT MAX(createdate) reatedate FROM dashboard_data GROUP BY branch,branch_process) tt
ON DATE(dd.createdate)=DATE(tt.reatedate) JOIN  `dashboard_Target` dt ON DATE_FORMAT(dd.createdate,'%b-%y') = DATE_FORMAT(dt.month,'%b-%y') WHERE dd.branch = '$branch' GROUP BY branch"));
 }
        }
    }
    public function get_process()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
       //$this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
        $processArr = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>"branch='$branchName'
     and active='1' and (close>date(now()) or close is null)"));
       if(!empty($processArr))
        {
            foreach($processArr as $tow)
            {
                $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
            }
        }
    
        $this->set('tower1',$tower1);
    }
     public function get_tower()
    {
        $this->layout='ajax';
        $id = $this->request->data('tower');
     $process =  $this->DashboardProcess->find('list',array('fields'=>array('branch','branch_process'),'conditions'=>array('id'=>$id)));
    // print_r($process);die;
    foreach ($process as $key => $value) {
         $val=$value;
         $k=$key;
    }
    // echo $val; exit;
    $tower = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>array('tower'=>$val,'branch'=>$k
     ,'active'=>'1',"(close>date(now()) or close is null)")));
    
    //print_r($tower); exit;
    if(!empty($tower))
    {
        foreach($tower as $tow)
        {
            $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
        }
    }
    
        $this->set('tower1',$tower1);
    
       
    }
    
    
     public function upload_target()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('userid');
            $FileTye = $this->request->data['upload']['file']['type'];
            $info = explode(".",$this->request->data['upload']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['upload']['file']['tmp_name'];
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
                        $data['createdate']=date('Y-m-d H:i:s');
                        $data['user_id']= $user;
                    $data['cost_center'] = $row[0];
                    $data['branch'] = $row[1];
                    
                    $data['target'] = $row[2];
                    $data['target_directCost'] = $row[3];
                    $data['target_IDC'] = $row[4];
                    $data['month1'] = date_create($row[5]);
                     $data['month']  = date_format($data['month1'], "Y-m-1");
                     
                     
                     
                     $arrayMonth = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
                    $MntArr = explode("-",$data['month']);
                    $FinanceYear =  $MntArr[0];
                    $FinanceMonth =  $arrayMonth[$MntArr[1]]; 

                    if(in_array($FinanceMonth,array('1','2','3')))
                    {
                        $NewFinanceYear = $FinanceYear-2000;
                        $NewFinanceYear = $FinanceYear.'-'.($NewFinanceYear-1);
                    }
                    else
                    {
                        $NewFinanceYear = $FinanceYear-2000;
                        $NewFinanceYear = $FinanceYear.'-'.($NewFinanceYear+1);
                    }
                     
                     $data['FinanceYear'] = addslashes($NewFinanceYear);
                    $data['FinanceMonth'] = addslashes($FinanceMonth);
                     
                     
                    // print_r($data['month']);
                    $cost = $this->CostCenterMaster->find('first',array('fields'=>array('id','tower'),'conditions'=>array('cost_center'=>$data['cost_center'],
                                        'and'=>array('branch'=>$data['branch']),
                                            )));
                    $data['cost_centerId'] = $cost['CostCenterMaster']['id'];
                   $data['tower'] = $cost['CostCenterMaster']['tower'];
                   $process =  $this->DashboardProcess->find('first',array('fields'=>array('id','branch_process'),'conditions'=>array('branch'=>$data['branch'], 'and'=>array('branch_process'=> $data['tower']) )));
                   $data['branch_process']= $process['DashboardProcess']['id'];
                   //print_r($data['branch_process']);
                    $dataArr[] = $data;
                    }
                    else {$flag = true;}
                }
                
               $this->TMPdashboardTarget->saveAll($dataArr);
                //print_r("LOAD DATA  INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center, finance_year, month, provision, remarks)"); die;
                $data = $this->TMPdashboardTarget->find('all',array('fields'=>array('user_id','cost_centerId','branch','cost_centerId','branch_process','target','target_directCost','target_IDC','createdate','month')));
		//print_r($data);
                foreach($data as $a)
                {
                   
                  
                   
                  
                // $a['TMPdashboardTarget']['month']= date_format($a['TMPdashboardTarget']['month'], "Y-m-1");
                  
                if($a['TMPdashboardTarget']['cost_centerId'] == '')
                {
                    $a['TMPdashboardTarget']['Reasion'] = "Cost center is blank";
                    $wrongData[] = $a;
                }
                elseif($a['TMPdashboardTarget']['branch'] == '')
                {  
                    $a['TMPdashboardTarget']['Reasion'] = "Branch is blank";
                    $wrongData[] = $a;
                }
                elseif($a['TMPdashboardTarget']['month'] == '')
                { $a['TMPdashboardTarget']['Reasion'] = "Target month is blank"; $wrongData[] = $a; }
                   elseif($this->Targets->find('first',array('conditions'=>
                       array('branch'=>$a['TMPdashboardTarget']['branch'],
                           'branch_process'=>$a['TMPdashboardTarget']['branch_process'],
                           'cost_centerId'=>$a['TMPdashboardTarget']['cost_centerId'],
                           'month'=>$a['TMPdashboardTarget']['month']))))
                    {$cost_cen = $this->CostCenterMaster->find('first',array('fields'=>array('cost_center'),'conditions'=>array('id'=>$a['TMPdashboardTarget']['cost_centerId']                   
                                            )));
                    $a['TMPdashboardTarget']['cost_centerId']=$cost_cen['CostCenterMaster']['cost_center']; 
                       
                       $a['TMPdashboardTarget']['Reasion'] = "Target Already Exists"; $wrongData[] = $a;}
                    else
                    {
                        
                        
                             
                            if(!empty($a['TMPdashboardTarget']['cost_centerId']))
                            {
                               
                                $this->Targets->saveAll($a['TMPdashboardTarget']);
                                unset($this->Targets);
                            }
                            else
                            {$a['TMPdashboardTarget']['Reasion'] = "Cost Center Not Found"; $wrongData[] = $a;}
                        
                    }
                }
                $this->set('wrongData',$wrongData);
                $this->TMPdashboardTarget->query("truncate table tmpdashboard_Target");
                $this->Session->setFlash('File uploaded Success!');
               
            }
            else{
            $this->Session->setFlash('File Format not Valid');
            }
            
    }
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
        $select = "Select *,date_format(dd.createdate,'%d-%b-%y')EntryDate from dashboard_data dd inner join cost_master cm on dd.cost_centerId = cm.id where FinanceYear='$finYear' and FinanceMonth='$finMonth' and cost_centerId='$cost_id' order by date(dd.createdate) desc";
        $this->set("Data",
           $this->DashboardData->query($select));   
        
        $this->set('bas',$bas);
        $this->set('asp',$asp);
        $this->set('act',$act);
        $this->set('type',$type);
    }
    public function save_actual_data()
    {
        $this->layout="ajax";
        //print_r($this->request->data); exit;
        $data =  $this->request->data;
        $commit = $data['commit'];
        $direct = $data['direct'];
        $indirect = $data['indirect'];
        $id = $data['id'];
        $userid = $this->Session->read("userid");
        $type=$data['type'];
        
        if($type=='revenue' && $commit=='')
        {
            echo "commit"; exit;
        }
        else if($type=='actual' && $direct=='')
        {
            echo "direct"; exit;
        }
        else if($type=='indirect' && $indirect=='')
        {
            echo "indirect"; exit;
        }
        else if($id=='')
        {
            echo "id"; exit;
        }
        
        $Details = $this->DashboardData->find('first',array('conditions'=>array('id'=>$id)));
        $cost_id = $Details['DashboardData']['cost_centerId'];
        $FinanceYear = $Details['DashboardData']['FinanceYear'];
        $FinanceMonth = $Details['DashboardData']['FinanceMonth'];
        $Branch = $Details['DashboardData']['branch'];
        $monthArr = array('Jan','Feb','Mar'); 
        $split = explode('-',$FinanceYear); 
        if(in_array($FinanceMonth, $monthArr)) 
        {
            $NewFinanceMonth .= '-'.$split[1];    //Year from month
        }
        else
        {
            $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
        }
        
        $upd = array('updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
        $save = array('FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
        if($type=='revenue')
        {
            //Provision Check
            $os = $this->Targets->query("SELECT cm.id,SUM(total) Total FROM tbl_invoice ti
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.id='$cost_id'
    WHERE ti.Finance_Year='$FinanceYear' AND ti.`Month`='$NewFinanceMonth' AND ti.status=0;");

            //print_r($provision); exit;
            $NewOS = round($os['0']['0']['Total'],2);

            $upd['Rev_Act'] = $commit;
            $save['Rev_Act'] = $commit;
            
            if(($commit)<$NewOS)
            {
                echo 'OS'; exit;
            }
        }
        if($type=='actual')
        {
            $DirectQry = "SELECT cm.id,sum(ep.Amount) Amount FROM expense_particular ep 
    INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and hm.EntryBy='' 
    INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$FinanceYear' AND em.FinanceMonth='$FinanceMonth' 
    AND ep.ExpenseTypeId='$cost_id' "; 

            $DirectData = $this->Targets->query($DirectQry);

            $NewDirect = round($DirectData['0']['0']['Amount'],2);

            $upd['Dir_Act'] = $direct;
            $save['Dir_Act'] = $direct;
            if(($direct)<$NewDirect)
            {
                echo 'directBas'; exit;
            }
        }
        
        if($type=='indirect')
        {
            $InDirectQry = "SELECT cm.id,sum(ep.Amount) Amount FROM expense_particular ep 
    INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and hm.EntryBy='' 
    INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$FinanceYear' AND em.FinanceMonth='$FinanceMonth' 
    AND ep.ExpenseTypeId='$cost_id' "; 
            $InDirectData = $this->Targets->query($InDirectQry);

            $NewInDirect = round($InDirectData['0']['0']['Amount'],2);
            $upd['InDir_Act'] = $indirect;
            $save['InDir_Act'] = $indirect;
            if(($indirect)<$NewInDirect)
            {
                echo 'IndirectBas'; exit;
            }
        }
        $FreezeData = $this->FreezeData->find('first',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and CostCenterId='$cost_id'"));
        if(!empty($FreezeData))
        {
            
            if($this->FreezeData->updateAll($upd,array('FreezeId'=>$FreezeData['FreezeData']['FreezeId'])))
            {
                $this->Session->setFlash('<font color="green">Record Updated Successfully</font>');
                echo "Updated"; exit;
            }
            else
            {
                echo "NotUpdated"; exit;
            }
        }
        else
        {
            if($this->FreezeData->save($save))
            {
                $this->Session->setFlash('<font color="green">Record Saved Successfully</font>');
                echo "Saved"; exit;
            }
            else
            {
                echo "NotSaved"; exit;
            }
        }
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
        
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' "; 
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
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' "; 
        $this->set("Data",
           $this->DashboardProcess->query($select));
        
        $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        $this->set('NewData',$NewData);
    }
   
    public function save_basic_indirect()
    {
        //print_r($this->request->data); exit;
        //DashboardBusPart
        $data = $this->request->data['Targets'];
        $finYear = $data['finYear']; 
        $finMonth = $data['finMonth'];
        $Branch = $data['Branch'];
        $cost_id = $data['cost_id'];
        $type = $data['type'];
        $data = Hash::Remove($data,'Branch');
        $data = Hash::Remove($data,'finYear');
        $data = Hash::Remove($data,'finMonth');
        $data = Hash::Remove($data,'cost_id');
        $data = Hash::Remove($data,'allIds');
        $NewArr = array(); $i=0;
        $save = $this->request->data['Save'];
        $userid = $this->Session->read("userid");
        if($save=='Replace')
        {
            
            foreach($data as $k=>$v)
            {
            $totalNewActual += $v['amount']; 
            }
            
            //print_r($totalNewActual); exit;
            if($type=='direct')
            {
                $upd = array('Dir_Act'=>"'$totalNewActual'",'updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
            $save = array('Dir_Act'=>"$totalNewActual",'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s')); 
            }
            else
            {
                $upd = array('InDir_Act'=>"'$totalNewActual'",'updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
                $save = array('InDir_Act'=>"$totalNewActual",'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));   
            }
            
            
            $FreezeData = $this->FreezeData->find('first',array('conditions'=>"FinanceYear='$finYear' and FinanceMonth='$finMonth' and CostCenterId='$cost_id'"));
            if(!empty($FreezeData))
            {

                if($this->FreezeData->updateAll($upd,array('FreezeId'=>$FreezeData['FreezeData']['FreezeId'])))
                {
                    
                }
                else
                {
                    $this->Session->setFlash('<font color="green">Actual Entry Not Updated</font>');
                }
            }
            else
            {
                if($this->FreezeData->save($save))
                {
                    //$this->Session->setFlash('<font color="green">Record Saved Successfully</font>');
                    //echo "Saved"; exit;
                }
                else
                {
                    $this->Session->setFlash('<font color="green">Actual Entry Not Updated</font>');
                }
            }
        }
        
        foreach($data as $k=>$v)
        {
            if($this->DashboardBusPart->find('first',array('conditions'=>"EpId='$k'")))
            {
                $this->DashboardBusPart->updateAll(array('Amount'=>"'".$v['amount']."'"),array("EpId"=>$k));
                $this->Session->setFlash("Amount Updated Successfully");
            }
            else
            {
                if(!empty($v['amount']))
                {
                    //$this->DashboardBusPart->saveAll();
                    $NewArr[$i++] = array('EpId'=>$k,'Amount'=>$v['amount'],'Branch'=>$Branch,'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'create_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
                }
                
            } 
        }
        
        //print_r($NewArr); exit;
        
        if(!empty($NewArr))
        {
            if($this->DashboardBusPart->saveAll($NewArr))
            {
                $this->Session->setFlash("Amount Updated Successfully");
            }
            else
                {
                    $this->Session->setFlash("Amount Not Updated Successfully");
                }
        }
        $this->Session->setFlash("Amount Updated Successfully"); 
        $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$finYear,'finMonth'=>$finMonth,'Branch'=>$Branch)));
    }
    
    public function view_freeze_request()
    {
        $this->layout = "home";
        $role = $this->Session->read('role');
        $all = array();
        if($role=='admin')
        {
            $condition=array('1'=>1);
            $all = array('All'=>'All');
        }
        else
        {
            $branch_name_new = $this->Session->read("branch_name");
            $condition=array('1'=>1);
            //$condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
     
        $branchMaster = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
        //$branchMaster = $all + $branchMaster2;
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));
        $this->set('branch_master',$branchMaster);
    
        
        
        $Disdata = $this->FreezeData->query("SELECT Branch,FinanceYear,FinanceMonth,
        SUM(Rev_Asp) Rev_Asp,
        SUM(Rev_Act) Rev_Act ,
        SUM(Rev_Bas) Rev_Bas ,

        SUM(Dir_Asp) Dir_Asp ,
        SUM(Dir_Act) Dir_Act ,
        SUM(Dir_Bas) Dir_Bas ,

        SUM(InDir_Asp) InDir_Asp ,
        SUM(InDir_Act) InDir_Act ,
        SUM(InDir_Bas) InDir_Bas,
        DisApproveRemarks

        FROM `dashboard_freeze_data_save` dfd WHERE Branch='$branch_name_new' and DisApprove_By is not null and Freezed=1 AND ApproveStatus=1 GROUP BY Branch,FinanceYear,FinanceMonth");
        
        $this->set('Disdata',$Disdata);
        
    if($this->request->is('Post'))
    {
        //print_r($this->request->data); exit;
        $NewBranch = $this->request->data['Targets']['branch_name'];
        $FinanceYear = $this->request->data['Targets']['FinanceYear'];
        $FinanceMonth = $this->request->data['Targets']['FinanceMonth'];
        $ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE Branch='$NewBranch' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' and Freezed=2";
        $ActualSavedData = $this->FreezeData->query($ActualSavedForTemp);
        
        $ActualSavedForTemp1 = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE Branch='$NewBranch' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' and Freezed=2 and Approve_By is not null";
        $ActualSavedData1 = $this->FreezeData->query($ActualSavedForTemp1);
        
        if(!empty($ActualSavedData1))
        {
            $this->Session->setFlash("Record Has Been Freezed Allready");
        }
        else if(empty($ActualSavedData))
        {
            $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$NewBranch)));
        }
        else
        {
            $this->Session->setFlash("Data Freezed Request Has been Allready Under Processed");
        }
    }
    
    }
    
    public function freeze_request()
    {
        $this->layout="home";
        $finYear = $this->params->query['finYear'];
        $finMonth = $this->params->query['finMonth'];
        $Branch = $this->params->query['Branch'];
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        
        $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id 
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch' group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        
        
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
        
        
        
        
        $ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE Branch='$Branch' AND FinanceYear='$finYear' AND FinanceMonth='$finMonth'";
        $ActualSavedData = $this->FreezeData->query($ActualSavedForTemp);
        $TmpActual = array();
        foreach($ActualSavedData as $tmp)
        {
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['revenue'] =  $tmp['dfs']['Rev_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['dc'] =  $tmp['dfs']['Dir_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['idc'] =  $tmp['dfs']['InDir_Act'];
        }
        
        //print_r($TmpActual); exit;
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id
WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
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
       
        
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM provision_master pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
 WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center
WHERE pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
        //print_r($NewData); exit;
        
        $NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
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
            $OldBasic[$DirectBC['ep']['id']]['dc'] +=  $DirectBC['ep']['Amount'];
        }
        
        
        
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
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
            $OldBasic[$DirectBC['ep']['id']]['idc'] +=  $DirectBC['ep']['Amount'];
        }
        
        $this->set('NewBasic',$NewBasicBusiness);
        $this->set('OldBasic',$OldBasic);
        
//        $DirectActualGRNMade = $this->Targets->query("SELECT cm.id,sum(eep.Amount) Amount FROM expense_entry_particular eep 
// INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
// INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
// INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId AND hm.Cost='D' 
// WHERE eem.FinanceYear='2018-19' and eem.FinanceMonth='Oct' and eep.CostCenterId=''");
//        
//        foreach($DirectActualGRNMade as $DirectGM)
//        {
//            $NewData[$DirectGM['cm']['id']]['Processed']['dc'] =  $DirectGM['0']['Amount'];
//            $cost_master[] = $DirectGM['cm']['id'];
//        }
//        //print_r($cost_master); exit;
//        
//        $InDirectActualGRNMade = $this->Targets->query("SELECT cm.id,sum(eep.Amount) Amount FROM expense_entry_particular eep 
// INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
// INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
// INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId AND hm.Cost!='D' 
// WHERE eem.FinanceYear='2018-19' and eem.FinanceMonth='Oct' and eep.CostCenterId=''");
//        
//        foreach($InDirectActualGRNMade as $InDirectBC)
//        {
//            $NewData[$InDirectBC['cm']['id']]['Processed']['idc'] =  $InDirectBC['0']['Amount'];
//            $cost_master[] = $InDirectBC['cm']['id'];
//        }
        
        $cost_master = array_unique($cost_master);
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
        foreach($cost_arr as $cost)
        {
            $cost_name = explode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].'/'.$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = substr($cost['CostCenterMaster']['process_name'],0,12);
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $new_cost_name;
        }
        
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData);
        
        
        
    }
    
    public function save_freeze_data()
    {
        $this->layout="ajax";
        
        $data = $this->request->data;
        $FinanceYear = $data['Targets']['FinanceYear'];
        $FinanceMonth = $data['Targets']['FinanceMonth'];
        $Branch = $data['Targets']['Branch'];
        $data = Hash::Remove($data['Targets'],'FinanceYear');
        $data = Hash::Remove($data,'FinanceMonth');
        //print_r($data); exit;
        $SaveData = array();
        $userid = $this->Session->read('userid');
        $i = 0;
        
        //delete old records
        $this->FreezeData->query("delete  from dashboard_freeze_data_save where FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and Branch='$Branch'");
        
        foreach($data as $k=>$v)
        {
            $NewData = array();
            $NewData['FinanceYear'] = $FinanceYear;
            $NewData['FinanceMonth'] = $FinanceMonth;
            $NewData['Branch'] = $Branch;
            $NewData['CostCenterId'] = $k;
            
            $NewData['Rev_Asp'] = $v['Rev_Asp'];
            $NewData['Rev_Act'] = $v['Rev_Act'];
            $NewData['Rev_Bas'] = $v['Rev_Bas'];
            
            $NewData['Dir_Asp'] = $v['Dir_Asp'];
            $NewData['Dir_Act'] = $v['Dir_Act'];
            $NewData['Dir_Bas'] = $v['Dir_Bas'];
            
            $NewData['InDir_Asp'] = $v['InDir_Asp'];
            $NewData['InDir_Act'] = $v['InDir_Act'];
            $NewData['InDir_Bas'] = $v['InDir_Bas'];
            
            $NewData['created_by'] = $userid;
            $NewData['created_at'] = date('Y-m-d H:i:s');
            
            $NewData['Freeze_by'] = $userid;
            $NewData['Freezed'] = '2';
            $NewData['Freeze_time'] = date('Y-m-d H:i:s');
            
            $SaveData[$i++] = $NewData;
            
        }
        
        
        if($this->FreezeData->SaveAll($SaveData))
        {
            
            $this->Session->setFlash('Record Has been Send For Approval.');
            $this->redirect(array('action'=>'view_freeze_request'));
        }
        else
        {
            $this->Session->setFlash('Record Has been Saved. Please Try Again Later');
        }
        
        exit;
    }
    
    public function view_freeze_request_for_approval()
    {
        $this->layout = "home";
        $data = $this->FreezeData->query("SELECT Branch,FinanceYear,FinanceMonth,
        SUM(Rev_Asp) Rev_Asp,
        SUM(Rev_Act) Rev_Act ,
        SUM(Rev_Bas) Rev_Bas ,

        SUM(Dir_Asp) Dir_Asp ,
        SUM(Dir_Act) Dir_Act ,
        SUM(Dir_Bas) Dir_Bas ,

        SUM(InDir_Asp) InDir_Asp ,
        SUM(InDir_Act) InDir_Act ,
        SUM(InDir_Bas) InDir_Bas 

        FROM `dashboard_freeze_data_save` dfd WHERE Freezed=2 AND ApproveStatus=1 GROUP BY Branch,FinanceYear,FinanceMonth");
        
        $this->set('data',$data);
        
    if($this->request->is('Post'))
    {
        //print_r($this->request->data); exit;
        $NewBranch = $this->request->data['Targets']['branch_name'];
        $FinanceYear = $this->request->data['Targets']['FinanceYear'];
        $FinanceMonth = $this->request->data['Targets']['FinanceMonth'];
        $ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE Branch='$NewBranch' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' and Freezed=2";
        $ActualSavedData = $this->FreezeData->query($ActualSavedForTemp);
        
        if(empty($ActualSavedData))
        {
            $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$NewBranch)));
        }
        else
        {
            $this->Session->setFlash("Data Freezed Request Has been Allready Under Processed");
        }
    }
    
    }
    
    public function get_basic_direct_data1()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $Branch = $this->request->data['Branch'];
        $cost_id = $this->request->data['cost_id'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        $this->set('cost_id',$cost_id);
        
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' "; 
        $this->set("Data",
           $this->DashboardProcess->query($select));
        
        $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        $this->set('NewData',$NewData);
    }
    
    public function get_basic_indirect_data1()
    {
        $this->layout="ajax";
        $finYear = $this->request->data['finyear'];
        $finMonth = $this->request->data['finmonth'];
        $Branch = $this->request->data['Branch'];
        $cost_id = $this->request->data['cost_id'];
        $ActualAmount =  $this->request->data['ActualAmount'];
        $this->set('ActualAmount',$ActualAmount);
        $this->set('cost_id',$cost_id);
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and hm.EntryBy='' 
INNER JOIN `tbl_bgt_expensesubheadingmaster` sh ON em.HeadId = sh.HeadingId and em.SubHeadId=sh.SubHeadingId 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND ep.ExpenseTypeId='$cost_id' "; 
        $this->set("Data",
           $this->DashboardProcess->query($select));
        
        $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        $this->set('NewData',$NewData);
    }
    
    public function save_basic_indirect1()
    {
        //print_r($this->request->data); exit;
        //DashboardBusPart
        $data = $this->request->data['Targets'];
        $finYear = $data['finYear']; 
        $finMonth = $data['finMonth'];
        $Branch = $data['Branch'];
        $cost_id = $data['cost_id'];
        $type = $data['type'];
        $data = Hash::Remove($data,'Branch');
        $data = Hash::Remove($data,'finYear');
        $data = Hash::Remove($data,'finMonth');
        $data = Hash::Remove($data,'cost_id');
        $data = Hash::Remove($data,'allIds');
        $NewArr = array(); $i=0;
        $save = $this->request->data['Save'];
        $userid = $this->Session->read("userid");
        if($save=='Replace')
        {
            
            foreach($data as $k=>$v)
            {
            $totalNewActual += $v['amount']; 
            }
            
            //print_r($totalNewActual); exit;
            if($type=='direct')
            {
                $upd = array('Dir_Act'=>"'$totalNewActual'",'updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
            $save = array('Dir_Act'=>"$totalNewActual",'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s')); 
            }
            else
            {
                $upd = array('InDir_Act'=>"'$totalNewActual'",'updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
                $save = array('InDir_Act'=>"$totalNewActual",'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));   
            }
            
            
            $FreezeData = $this->FreezeData->find('first',array('conditions'=>"FinanceYear='$finYear' and FinanceMonth='$finMonth' and CostCenterId='$cost_id'"));
            if(!empty($FreezeData))
            {

                if($this->FreezeData->updateAll($upd,array('FreezeId'=>$FreezeData['FreezeData']['FreezeId'])))
                {
                    
                }
                else
                {
                    $this->Session->setFlash('<font color="green">Actual Entry Not Updated</font>');
                }
            }
            else
            {
                if($this->FreezeData->save($save))
                {
                    //$this->Session->setFlash('<font color="green">Record Saved Successfully</font>');
                    //echo "Saved"; exit;
                }
                else
                {
                    $this->Session->setFlash('<font color="green">Actual Entry Not Updated</font>');
                }
            }
        }
        
        foreach($data as $k=>$v)
        {
            if($this->DashboardBusPart->find('first',array('conditions'=>"EpId='$k'")))
            {
                $this->DashboardBusPart->updateAll(array('Amount'=>"'".$v['amount']."'"),array("EpId"=>$k));
                $this->Session->setFlash("Amount Updated Successfully");
            }
            else
            {
                if(!empty($v['amount']))
                {
                    //$this->DashboardBusPart->saveAll();
                    $NewArr[$i++] = array('EpId'=>$k,'Amount'=>$v['amount'],'Branch'=>$Branch,'FinanceYear'=>$finYear,'FinanceMonth'=>$finMonth,'create_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
                }
                
            } 
        }
        
        //print_r($NewArr); exit;
        
        if(!empty($NewArr))
        {
            if($this->DashboardBusPart->saveAll($NewArr))
            {
                $this->Session->setFlash("Amount Updated Successfully");
            }
            else
                {
                    $this->Session->setFlash("Amount Not Updated Successfully");
                }
        }
        $this->Session->setFlash("Amount Updated Successfully"); 
        $this->redirect(array('action'=>'view_freeze_data','?'=>array('finYear'=>$finYear,'finMonth'=>$finMonth,'Branch'=>$Branch)));
    }
    public function view_freeze_data()
    {
        $this->layout="home";
        $finYear = $this->params->query['finYear'];
        $finMonth = $this->params->query['finMonth'];
        $Branch = $this->params->query['Branch'];
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        
        $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id 
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch' group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        
        
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
        
        
        $ActualSavedForTemp = "SELECT * FROM `dashboard_freeze_data_save` dfs WHERE Branch='$Branch' AND FinanceYear='$finYear' AND FinanceMonth='$finMonth'";
        $ActualSavedData = $this->FreezeData->query($ActualSavedForTemp);
        $TmpActual = array();
        foreach($ActualSavedData as $tmp)
        {
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['revenue'] =  $tmp['dfs']['Rev_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['dc'] =  $tmp['dfs']['Dir_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['idc'] =  $tmp['dfs']['InDir_Act'];
        }
        
        //print_r($TmpActual); exit;
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id
WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
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
       
        
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM provision_master pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
 WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center
WHERE pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
        //print_r($NewData); exit;
        
        $NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
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
        
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
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
        
        
        
//        $DirectActualGRNMade = $this->Targets->query("SELECT cm.id,sum(eep.Amount) Amount FROM expense_entry_particular eep 
// INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
// INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
// INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId AND hm.Cost='D' 
// WHERE eem.FinanceYear='2018-19' and eem.FinanceMonth='Oct' and eep.CostCenterId=''");
//        
//        foreach($DirectActualGRNMade as $DirectGM)
//        {
//            $NewData[$DirectGM['cm']['id']]['Processed']['dc'] =  $DirectGM['0']['Amount'];
//            $cost_master[] = $DirectGM['cm']['id'];
//        }
//        //print_r($cost_master); exit;
//        
//        $InDirectActualGRNMade = $this->Targets->query("SELECT cm.id,sum(eep.Amount) Amount FROM expense_entry_particular eep 
// INNER JOIN expense_entry_master eem ON eep.ExpenseEntry = eem.Id
// INNER JOIN cost_master cm ON eep.CostCenterId = cm.id
// INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId AND hm.Cost!='D' 
// WHERE eem.FinanceYear='2018-19' and eem.FinanceMonth='Oct' and eep.CostCenterId=''");
//        
//        foreach($InDirectActualGRNMade as $InDirectBC)
//        {
//            $NewData[$InDirectBC['cm']['id']]['Processed']['idc'] =  $InDirectBC['0']['Amount'];
//            $cost_master[] = $InDirectBC['cm']['id'];
//        }
        
        $cost_master = array_unique($cost_master);
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
        foreach($cost_arr as $cost)
        {
            $cost_name = explode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].'/'.$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = substr($cost['CostCenterMaster']['process_name'],0,12);
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $new_cost_name;
        }
        
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData);
        
        
        
    }

    public function freeze_branch()
    {
        $this->layout="ajax";
        $data = $this->request->data;
        //print_r($data); exit;
        $FinanceYear = $data['FinanceYear'];
        $FinanceMonth = $data['FinanceMonth'];
        $Branch = $data['Branch'];
        $Remarks= $data['Remarks'];
        $userid = $this->Session->read('userid');
        
        if($this->FreezeData->updateAll(array('ApprovalRemarks'=>"'".$Remarks."'",'ApproveStatus'=>'2','Approve_By'=>$userid,'Approve_time'=>"'".date("Y-m-d H:i:s")."'"),array('FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch)))
        {
            
            $NewProv = $this->FreezeData->find("list",array("conditions"=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and Branch='$Branch'",'fields'=>array('CostCenterId','Rev_Bas')));
            foreach($NewProv as $k=>$v)
            {
                $monthArr = array('Jan','Feb','Mar'); 
                $split = explode('-',$FinanceYear); 
                if(in_array($FinanceMonth, $monthArr)) 
                {
                    $NewFinanceMonth =$FinanceMonth. '-'.$split[1];    //Year from month
                }
                else
                {
                    $NewFinanceMonth = $FinanceMonth.'-'.($split[1]-1);    //Year from month
                }
                
                //echo "INSERT INTO `dashboard_save_prov`(id,cost_center,branch_name,finance_year,month,provision,provision_balance,agreement,acknowledgment,remarks,send_bps,userid,createdate) SELECT pm.id,pm.cost_center,pm.branch_name,pm.finance_year,pm.month,pm.provision,pm.provision_balance,pm.agreement,pm.acknowledgment,pm.remarks,pm.send_bps,pm.userid,pm.createdate FROM provision_master pm inner join cost_master cm on pm.cost_center=cm.cost_center and cm.id='$k'  WHERE pm.month='$NewFinanceMonth' AND pm.finance_year='$FinanceYear'"; 
                
                $this->Provision->query("INSERT INTO `dashboard_save_prov`(id,cost_center,branch_name,finance_year,month,provision,provision_balance,agreement,acknowledgment,remarks,send_bps,userid,createdate) SELECT pm.id,pm.cost_center,pm.branch_name,pm.finance_year,pm.month,pm.provision,pm.provision_balance,pm.agreement,pm.acknowledgment,pm.remarks,pm.send_bps,pm.userid,pm.createdate FROM provision_master pm inner join cost_master cm on pm.cost_center=cm.cost_center and cm.id='$k'  WHERE pm.month='$NewFinanceMonth' AND pm.finance_year='$FinanceYear'");
                
                $this->Provision->query("UPDATE provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center and cm.id='$k'"
                        . " SET pm.provision='$v' WHERE pm.month='$NewFinanceMonth' AND pm.finance_year='$FinanceYear'");
            }
            
            $NewData = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch)));
            $DataBudgetOld = $this->ExpenseMaster->find('all',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and Branch='$Branch'"));
            if(!empty($DataBudgetOld))
            {
                $iRow=0; $NewArrayBudget = array();  $PartOld= array();
                foreach($DataBudgetOld as $old_budget)
                {
                    $NewArrayBudget[$iRow++]['ExpenseMasterOld'] = $old_budget['ExpenseMaster'];
                    $DataBudgetOldPart = $this->ExpenseParticular->find('all',array('conditions'=>"ExpenseId='{$old_budget['ExpenseMaster']['Id']}'"));
                    
                    foreach($DataBudgetOldPart as $part_old)
                    {
                        $PartOld[$iRow++]['ExpenseParticularOld'] = $part_old['ExpenseParticular'];  
                    }
                }
                
                $this->ExpenseMasterOld->saveAll($NewArrayBudget);
                $this->ExpenseParticularOld->saveAll($PartOld);
            }
            
            
            foreach($NewData as $k=>$v)
            {
                $old_amt = $this->ExpenseParticular->query("SELECT ExpenseId,Amount FROM `expense_particular` ep WHERE Id='$k'");
                $NewAmount = $v - $old_amt['0']['ep']['Amount'];
                $ExpenseId = $old_amt['0']['ep']['ExpenseId'];
                
//                $BudgetCaseOld = $this->ExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId))); //get budget business case
//                $BudgetParticularOld = $this->ExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId))); //get budget business case
//                
//                $iRow=0; $PartOld = array();
//                foreach($BudgetParticularOld as $part_old)
//                {
//                    $PartOld[$iRow++]['ExpenseParticularOld'] = $part_old['ExpenseParticular']; 
//                }
//                
//                $this->ExpenseMasterOld->save(array('ExpenseMasterOld'=>$BudgetCaseOld['ExpenseMaster']));
//                $this->ExpenseParticularOld->saveAll($PartOld);
                
                if(empty($NewAmount))
                {
                    $NewAmount =0; 
                }
                
                $this->ExpenseParticular->query("update `expense_particular` set Amount='$v' WHERE Id='$k'");
                if($NewAmount)
                $this->ExpenseMaster->query("update `expense_master` set Amount=Amount+$NewAmount WHERE Id='$ExpenseId'");
                
                
            }
            
            echo "1"; exit;
        }
        else
        {
            echo "0"; exit;
        }
        
    }
    
    public function disapprove_feeze_request()
    {
        $this->layout="ajax";
        $this->layout="ajax";
        $data = $this->request->data;
        //print_r($data); exit;
        $FinanceYear = $data['FinanceYear'];
        $FinanceMonth = $data['FinanceMonth'];
        $Branch = $data['Branch'];
        $Remarks= $data['Remarks'];
        $userid = $this->Session->read('userid');
        if($this->FreezeData->updateAll(array('Freezed'=>'1','DisApproveRemarks'=>"'".$Remarks."'",'ApproveStatus'=>'1','DisApprove_By'=>$userid,'DisApprove_time'=>"'".date("Y-m-d H:i:s")."'"),array('FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch)))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
    }
}

?>