<?php
class TargetsController extends AppController 
{
    public $uses=array('Targets','BillMaster','DashboardTargetRevenue','Addbranch','month','process','DashboardProcess','CostCenterMaster','TMPdashboardTarget',
        'DashboardData','FreezeData','DashboardBusPart','Provision','ExpenseMaster','ExpenseMasterOld','ExpenseParticular','ExpenseParticularOld',
        'DashboardRevenue','DashboardFreezeRevenue');
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
            $role=$this->Session->read("role"); $this->Auth->allow('view_freeze_request','freeze_request','save_actual_revenue','save_freeze_data','get_actual_data',
                    'get_basic_direct_data','get_basic_direct_data1','get_basic_indirect_data','get_basic_indirect_data1','save_actual_data','view_freeze_request_for_approval','view_freeze_data',
                    'freeze_branch','save_basic_indirect','save_basic_indirect1','disapprove_feeze_request','save_actual_data1','asp_delete');
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('index','add','get_process','get_entry_form');
            $this->Auth->allow('get_dash_data','index','get_entry_form','add','get_process','get_tower','upload_target');
	}
    }
		
    public function get_entry_form()
    {
        $this->layout="ajax";
        $month = $this->request->data['month'];
        $cost_id = $this->request->data['cost_id'];
        
        $date_last_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(LAST_DAY('$month'),'%d-%b-%Y') last_day FROM `dashboard_cost_parts` LIMIT 1");
        $date_last_det =  $date_last_det['0']['0']['last_day'];
        $this->set('date_last_det',$date_last_det);

        $ParticularDetails = $this->CostCenterMaster->query("SELECT * FROM `dashboard_cost_parts` parts ORDER BY Priority");
        $this->set('ParticularDetails',$ParticularDetails);

        $dater = explode("-",$date_last_det);
        $finYear = $dater['2'];
        $finMonth = $dater['1'];
        
        $select = "Select *,date_format(dd.createdate,'%d-%b-%y')EntryDate from dashboard_data dd "
                . " inner join cost_master cm on dd.cost_centerId = cm.id where "
                . " left(FinanceYear,4)='$finYear' and FinanceMonth='$finMonth' and cost_centerId='$cost_id' order by date(dd.createdate) desc";
        $this->set("Data",$this->DashboardData->query($select));
        
        
        $costTotal = 0; $MtdTotal=0; $Forecast = 0;
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                
                $data1 = $this->DashboardTargetRevenue->query("select * from dashboard_target_revenue DashboardRevenue where "
                        . " costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='$finYear-$finMonth' "
                        . " order by created_at desc limit 1");
                
                
                //print_r("costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='2019-$finMonth'"); exit;
                $cost_data['cost'.$headerId] = !empty($data1['0']['DashboardRevenue']['CostCenterMonthDet'])?$data1['0']['DashboardRevenue']['CostCenterMonthDet']:0;
                $MtdTotal = !empty($data1['0']['DashboardRevenue']['Mtd'])?$data1['0']['DashboardRevenue']['Mtd']:0;
                //$Forecast = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
                //if($headerId=='2') {print_r($data1); exit;}
                $cost_data['mtd'.$headerId] = !empty($data1['0']['DashboardRevenue']['Mtd'])?$data1['0']['DashboardRevenue']['Mtd']:0;
                if($headerId=='1')
                {
                    //echo "(SELECT MAX(insertDate) FROM dashboard_data_revenue where costCenterId='$cost_id' and HeaderId='$headerId' and date_format('%Y-%b',insertDate)='2019-$finMonth')"; exit;
                    //print_r($data1); exit;
                }
                if(empty($cost_data['forecast'.$headerId]))
                {
                $cost_data['forecast'.$headerId] = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
                }
                //print_r($data1); exit;
                //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                if($parts['parts']['RateRequired'])
                {
                    $cost_data['costRate'.$headerId] = !empty($data1['0']['DashboardRevenue']['CostCenterMonthDet'])?$data1['0']['DashboardRevenue']['CostCenterMonthDet']:0;
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        else
                        {
                            $costTotal += $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                    }
                }
                else
                {
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                        }
                        else
                        {
                        $costTotal += $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
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
                $year = $finYear;
            }
            else
            {
                $year = $finYear;
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
                    
                        $data1 = $this->DashboardTargetRevenue->query("select * from dashboard_target_revenue DashboardRevenue where costCenterId='$cost_id' and HeaderId='$headerId' and DATE_FORMAT(insertDate,'%Y-%b-%d')='$year-$finMonth-$day' limit 1");
                    
                    
                    if(!empty($data1) && $headerTotalFlag)
                    {
                        $count += 1;$headerTotalFlag=false;
                    }
                    if(!empty($data1['0']['DashboardRevenue']['CostCenterMonthDet']))
                    {
                        $cost_data['cost'.$headerId] = $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                    }
                    $cost_data['date'.(int)$m.'_'.$headerId] = !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
                    //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
                    //print_r($data1); exit;
                    //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                    
                    $cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
//                    if(!empty($data1['0']['DashboardRevenue']['Forecast']))
//                    {
//                        $cost_data['forecast'.$headerId] = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
//                    }
                    if($parts['parts']['RateRequired'])
                    {
                        $cost_data['HeadTotalRate'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        $cost_data['dateRate'.$m.'_'.$headerId] = !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        if(!empty($data1['0']['DashboardRevenue']['CostCenterMonthDetRate']))
                        {
                            $cost_data['costRate'.$headerId] = $data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['0']['DashboardRevenue']['insertDateDet']*$data1['0']['DashboardRevenue']['insertDateRate'];
                                $costTotal2 -= $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            else
                            {
                                $costTotal1 += $data1['0']['DashboardRevenue']['insertDateDet']*$data1['0']['DashboardRevenue']['insertDateRate'];
                                $costTotal2 += $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        }
                    }
                    else
                    {
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['0']['DashboardRevenue']['insertDateDet'];
                                $costTotal2 -= $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            else
                            {
                                $costTotal1 += $data1['0']['DashboardRevenue']['insertDateDet'];
                                $costTotal2 += $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
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
            $data['createby'] = $this->Session->read('userid');
            
        if($this->request->is('POST'))
        {
            $request = $this->request->data['Targets'];
            //print_r($request); exit;
            
            
            //$arrayMonth = array("Jan"=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>"08",'Sep'=>"09","Oct"=>"10","Nov"=>'11','Dec'=>'12');
            $arrayMonth = array("01"=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>"Aug",'09'=>"Sep","10"=>"Oct","11"=>'Nov','12'=>'Dec');
            $MntArr = explode("-",$request['month']);
            $FinanceYear =  $MntArr[0];
            $FinanceMonth =  $arrayMonth[$MntArr[1]]; 
            
            if(in_array($FinanceMonth,array('1','2','3',1,2,3,'01','02','03')))
            {
                $NewFinanceYear = $FinanceYear-2000;
                $NewFinanceYear = ($FinanceYear-1).'-'.($NewFinanceYear);
            }
            else
            {
                $NewFinanceYear = $FinanceYear-2000;
                $NewFinanceYear = ($FinanceYear).'-'.($NewFinanceYear+1); 
            }
            
            $CostCenterIdSelected = $cs = $request['cost_centerId']; 
            $branchSelected = $b= $request['branch']; 
            //$branchProcessSelected = $bp = $request['branch_process'];
            $monthSelected = $m = $request['month'];
            
            
            $save = true; $msg ="";
            
            $BranchDet = $this->Addbranch->find('first',array('conditions'=>"branch_name='$branchSelected' and active=1"));
            $BranchId = $BranchDet['Addbranch']['id']; //taking branch id from branch name
            $branch = $BranchDet['Addbranch']['branch_name'];
            
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
            
            $cost = $this->request->data['cost'];
            $costRate = $this->request->data['costRate'];
            $date_val = $this->request->data['date'];
            $dateRate = $this->request->data['dateRate'];
            $Forecast = 0;
            
            $targets_month = explode("-",$this->request->data['Targets']['month']);        
            $header_sum_total = 0; 
            $year = date("Y");  
            $mnt_arr = $this->request->data['mnt_arr'];
            
            $date_wise_sum = array();
            $flag_add = false;
            
            // sum of date_wise for checking entry is here or not.
            
            $insertMonth = $year.'-'.$targets_month[1];
            
                    
                    for($jj=1; $jj<=$mnt_arr;$jj++)
                    {
                        foreach(explode(",",$this->request->data['id_arr']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_$headerId"];
                        }
                        foreach(explode(",",$this->request->data['id_arr_rate']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_"."$headerId"]*$dateRate["$jj"."_"."$headerId"];
                        }
                        foreach(explode(",",$this->request->data['not_added_arr']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_$headerId"];
                        }
                    }
                    
                    foreach(explode(",",$this->request->data['id_arr']) as $headerId)
                    {
                        
                        $header_sum=0;
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $header_sum += $date_val["$jj"."_$headerId"];
                        }
                          
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {    
                            $insertDate = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            
                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['FinanceMonth1'] = $FinanceMonth.'-'.($year-2000);
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            //$new_data['insertDate'] = $year.'-'.'03'.'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['insertDate'] = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $CostCenterIdSelected;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = 1;
                            $new_data['insertDateDet'] = $date_val["$jj"."_$headerId"]; 
                            $new_data['insertDateRate'] = 1;

                            $new_data['Mtd'] = $header_sum;
                            //$Mtd += $mtd_arr[$headerId];
                            $new_data['Forecast'] = round($header_sum,2);
                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            $data_arr[] = $new_data;
                        }

                        if($headerId=='10')
                        {

                        }
                        else if($headerId=='8')
                        {
                            $header_sum_total -= $header_sum;
                        }
                        else
                        {
                            $header_sum_total += $header_sum;
                        }
                    }
            
                    foreach(explode(",",$this->request->data['id_arr_rate']) as $headerId)
                    {
                        $header_sum=0; $rate_sum = 0;
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $header_sum += $date_val["$jj"."_$headerId"];
                        }
                        
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $insertDate = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['FinanceMonth1'] = $FinanceMonth.'-'.($year-2000);
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            $new_data['insertDate'] = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $CostCenterIdSelected;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = $costRate[$headerId];
                            $new_data['insertDateDet'] = $date_val["$jj"."_"."$headerId"];
                            $new_data['insertDateRate'] = $dateRate["$jj"."_"."$headerId"];
                            $new_data['Mtd'] = $header_sum;
                            $rate_sum += $date_val["$jj"."_"."$headerId"]*$dateRate["$jj"."_"."$headerId"];
                            $new_data['Forecast'] = round($header_sum,2);

                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            
                            $data_arr[] = $new_data;
                        }
                        if($headerId=='10')
                        {

                        }
                        else if($headerId=='8')
                        {
                            $header_sum_total -= $rate_sum;
                        }
                        else
                        {
                            $header_sum_total += $rate_sum;
                        }
                    }
            
                    //for non added value
                    foreach(explode(",",$this->request->data['not_added_arr']) as $headerId)
                    {
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            
                            $insertDate = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);

                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['FinanceMonth1'] = $FinanceMonth.'-'.($year-2000);
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            $new_data['insertDate'] = $year.'-'.$targets_month[1].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $CostCenterIdSelected;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = 1;
                            $new_data['insertDateDet'] = $date_val["$jj"."_"."$headerId"];;
                            $new_data['insertDateRate'] = 1;

                            $new_data['Mtd'] = $mtd_arr[$headerId];
                            $Mtd = $mtd_arr[$headerId];

                            $new_data['Forecast'] = $forecast_arr[$headerId];
                            
                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            $data_arr[] = $new_data;
                        }
                    }
            
            
                    if($save)
                    {
                        if($this->DashboardTargetRevenue->saveAll($data_arr))
                        {
                            $data['branch'] = addslashes($request['branch']);
                            $data['branch_process'] = addslashes($request['branch_process']);
                            $data['cost_centerId'] = addslashes($request['cost_centerId']);
                            //$data['target'] = addslashes($request['target']);
                            $data['target'] =  $header_sum_total;
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
            AND SUBSTRING_INDEX(ti.bill_no,'/','1')=bpp.bill_no WHERE ti.invoiceType='Revenue' and cm.id='$CostCenterIdSelected'"
                           . " AND ti.month=DATE_FORMAT(SUBDATE('$monthSelected',INTERVAL 1 MONTH),'%b-%y')";  


                            $dataOS = $this->Targets->query($qryOS);
                            //print_r($dataOS); exit;
                            $qryOS = "SELECT cm.id,SUM(provision_balance*1.18)grnd FROM provision_master pm 
            INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
             WHERE pm.invoiceType1='Revenue' and  cm.id='$CostCenterIdSelected'"
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
                    $CostCenterIdSelected = $data['cost_center'] = $row[0]; 
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
                        $NewFinanceYear = ($FinanceYear-1).'-'.($NewFinanceYear);
                    }
                    else
                    {
                        $NewFinanceYear = $FinanceYear-2000;
                        $NewFinanceYear = ($FinanceYear-1).'-'.($NewFinanceYear);
                    }
                     
                     $data['FinanceYear'] = addslashes($NewFinanceYear); 
                    $monthSelected = $data['FinanceMonth'] = addslashes($FinanceMonth);
                     
                    $qryOS = "SELECT cm.id,SUM(grnd-if(net_amount is null || net_amount='',0,net_amount)-if(tds_ded is null || tds_ded='',0,tds_ded))grnd FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
LEFT JOIN bill_pay_particulars bpp ON cm.company_name = bpp.company_name
AND cm.branch = bpp.branch_name AND ti.finance_year = bpp.financial_year
AND SUBSTRING_INDEX(ti.bill_no,'/','1')=bpp.bill_no WHERE ti.invoiceType='Revenue' and cm.cost_center='$CostCenterIdSelected'"
               . " AND ti.month=DATE_FORMAT(SUBDATE('$monthSelected',INTERVAL 1 MONTH),'%b-%y')";  
                
                 
                $dataOS = $this->Targets->query($qryOS);
                //print_r($dataOS); exit;
                $qryOS = "SELECT cm.id,SUM(provision_balance*1.18)grnd FROM provision_master pm 
INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center
 WHERE pm.invoiceType1='Revenue' and  cm.cost_center='$CostCenterIdSelected'"
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
                //print_r($dataArr); exit;
               $this->TMPdashboardTarget->saveAll($dataArr); 
                //print_r("LOAD DATA  INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center, finance_year, month, provision, remarks)"); die;
                $data = $this->TMPdashboardTarget->find('all',array('fields'=>array('user_id','cost_centerId','branch','cost_centerId','branch_process','target','target_directCost','target_IDC','createdate','month','FinanceYear','FinanceMonth')));
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
                               $cost_center_name = $this->CostCenterMaster->find('first',array("fields"=>'cost_center',"conditions"=>"id='{$a['TMPdashboardTarget']['cost_centerId']}'"));
                               $a['TMPdashboardTarget']['cost_center'] = $cost_center_name['CostCenterMaster']['cost_center'];
                               
                               $cost_center_name = $this->Addbranch->find('first',array("fields"=>'id',"conditions"=>"branch_name='{$a['TMPdashboardTarget']['branch']}'"));
                               $a['TMPdashboardTarget']['branch_id'] = $cost_center_name['Addbranch']['id'];
                               $a['TMPdashboardTarget']['createby'] = $user;
                               
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
        $this->set("Data",$this->DashboardData->query($select));
        
        $ParticularDetails = $this->CostCenterMaster->query("SELECT * FROM `dashboard_cost_parts` parts ORDER BY Priority");
        $this->set('ParticularDetails',$ParticularDetails);
        
        $costTotal = 0; $MtdTotal=0; $Forecast = 0;
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                if(empty($this->DashboardFreezeRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and date_format(insertDate,'%Y-%b')='2019-$finMonth'"))))
                {
                    $data1 = $this->DashboardRevenue->query("select * from dashboard_data_revenue DashboardRevenue where costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='2019-$finMonth' order by created_at desc limit 1");
                }
                else
                {
                    $data1 = $this->DashboardFreezeRevenue->query("select * from dashboard_freeze_revenue DashboardRevenue where costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='2019-$finMonth' order by created_at desc limit 1");
                }
                
                //print_r("costCenterId='$cost_id' and HeaderId='$headerId' and date_format(insertDate,'%Y-%b')='2019-$finMonth'"); exit;
                $cost_data['cost'.$headerId] = !empty($data1['0']['DashboardRevenue']['CostCenterMonthDet'])?$data1['0']['DashboardRevenue']['CostCenterMonthDet']:0;
                $MtdTotal = !empty($data1['0']['DashboardRevenue']['Mtd'])?$data1['0']['DashboardRevenue']['Mtd']:0;
                //$Forecast = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
                //if($headerId=='2') {print_r($data1); exit;}
                $cost_data['mtd'.$headerId] = !empty($data1['0']['DashboardRevenue']['Mtd'])?$data1['0']['DashboardRevenue']['Mtd']:0;
                if($headerId=='1')
                {
                    //echo "(SELECT MAX(insertDate) FROM dashboard_data_revenue where costCenterId='$cost_id' and HeaderId='$headerId' and date_format('%Y-%b',insertDate)='2019-$finMonth')"; exit;
                    //print_r($data1); exit;
                }
                if(empty($cost_data['forecast'.$headerId]))
                {
                $cost_data['forecast'.$headerId] = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
                }
                //print_r($data1); exit;
                //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                if($parts['parts']['RateRequired'])
                {
                    $cost_data['costRate'.$headerId] = !empty($data1['0']['DashboardRevenue']['CostCenterMonthDet'])?$data1['0']['DashboardRevenue']['CostCenterMonthDet']:0;
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        else
                        {
                            $costTotal += $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                    }
                }
                else
                {
                    if($parts['parts']['AddRequired'])
                    {
                        if($headerId=='8')
                        {
                            $costTotal -= $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                        }
                        else
                        {
                        $costTotal += $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
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
                    if(empty($this->DashboardFreezeRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and date_format(insertDate,'%Y-%b')='2019-$finMonth'"))))
                    {
                        
                    $data1 = $this->DashboardRevenue->query("select * from dashboard_data_revenue DashboardRevenue where costCenterId='$cost_id' and HeaderId='$headerId' and DATE_FORMAT(insertDate,'%Y-%b-%d')='$year-$finMonth-$day' limit 1");
                    }
                    else
                    {
                        $data1 = $this->DashboardFreezeRevenue->query("select * from dashboard_freeze_revenue DashboardRevenue where costCenterId='$cost_id' and HeaderId='$headerId' and DATE_FORMAT(insertDate,'%Y-%b-%d')='$year-$finMonth-$day' limit 1");
                    }
                    
                    if(!empty($data1) && $headerTotalFlag)
                    {
                        $count += 1;$headerTotalFlag=false;
                    }
                    if(!empty($data1['0']['DashboardRevenue']['CostCenterMonthDet']))
                    {
                        $cost_data['cost'.$headerId] = $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                    }
                    $cost_data['date'.(int)$m.'_'.$headerId] = !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
                    //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
                    //print_r($data1); exit;
                    //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                    
                    $cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateDet'])?$data1['0']['DashboardRevenue']['insertDateDet']:0;
//                    if(!empty($data1['0']['DashboardRevenue']['Forecast']))
//                    {
//                        $cost_data['forecast'.$headerId] = !empty($data1['0']['DashboardRevenue']['Forecast'])?$data1['0']['DashboardRevenue']['Forecast']:0;
//                    }
                    if($parts['parts']['RateRequired'])
                    {
                        $cost_data['HeadTotalRate'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        $cost_data['dateRate'.$m.'_'.$headerId] = !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        if(!empty($data1['0']['DashboardRevenue']['CostCenterMonthDetRate']))
                        {
                            $cost_data['costRate'.$headerId] = $data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                        }
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['0']['DashboardRevenue']['insertDateDet']*$data1['0']['DashboardRevenue']['insertDateRate'];
                                $costTotal2 -= $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            else
                            {
                                $costTotal1 += $data1['0']['DashboardRevenue']['insertDateDet']*$data1['0']['DashboardRevenue']['insertDateRate'];
                                $costTotal2 += $data1['0']['DashboardRevenue']['CostCenterMonthDet']*$data1['0']['DashboardRevenue']['CostCenterMonthDetRate'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
                        }
                    }
                    else
                    {
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['0']['DashboardRevenue']['insertDateDet'];
                                $costTotal2 -= $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            else
                            {
                                $costTotal1 += $data1['0']['DashboardRevenue']['insertDateDet'];
                                $costTotal2 += $data1['0']['DashboardRevenue']['CostCenterMonthDet'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['0']['DashboardRevenue']['insertDateRate'])?$data1['0']['DashboardRevenue']['insertDateRate']:0;
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
    WHERE ti.invoiceType='Revenue' and ti.Finance_Year='$FinanceYear' AND ti.`Month`='$NewFinanceMonth' AND ti.status=0;");

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
    public function save_actual_data1()
    {
        $this->layout="ajax";
        //print_r($this->request->data); exit;
        $data =  $this->request->data;
        $commit = $data['commit'];
        $direct = $data['direct'];
        $indirect = $data['indirect'];
        $cost_id = $id = $data['cost_id'];
        $userid = $this->Session->read("userid");
        $type=$data['type'];
        $FinanceYear = $data['finyear11'];
        $FinanceMonth = $data['finmonth11'];
        
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
        
        
        $Details = $this->CostCenterMaster->find('first',array('conditions'=>array('id'=>$id)));
        
        
        $Branch = $Details['CostCenterMaster']['branch'];
        
        
        $upd = array('updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
        $save = array('FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
        if($type=='revenue')
        {
            //Provision Check
            $os = $this->Targets->query("SELECT cm.id,SUM(total) Total FROM tbl_invoice ti
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.id='$cost_id'
    WHERE ti.invoiceType='Revenue' and ti.Finance_Year='$FinanceYear' AND ti.`Month`='$NewFinanceMonth' AND ti.status=0;");

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
        
        $select = "SELECT ep.id,cm.process_name,cm.id,cm.cost_center,hm.HeadingDesc,sh.SubHeadingDesc,ep.Amount FROM expense_particular ep 
INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id 
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
            $condition=array('active'=>1);
            $all = array('All'=>'All');
            $branchMaster = $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>$condition,'order'=>array('branch_name')));
        }
        else
        {
            $branch_name_new = $this->Session->read("branch_name");
            $condition=array('1'=>1);
            $branchMaster = array($branch_name_new=>$branch_name_new);
            //$condition=array('branch_name'=>$this->Session->read("branch_name"));
        }
     
        
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
        
        $YearArrRes = explode("-",$finYear);
        if(in_array(strtolower($finMonth),array('jan','feb','mar')))
        {
            $YearRes = $YearArrRes[0]+1;
        }
        else
        {
            $YearRes = $YearArrRes[0];
        }
        
        $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id 
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch' group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        $NewData = array();
        
        
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
            
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['revenue'] =  $tmp['dfs']['Rev_Com'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['dc'] =  $tmp['dfs']['Dir_Com'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['idc'] =  $tmp['dfs']['InDir_Com'];
            
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['revenue'] =  $tmp['dfs']['Rev_Act'];
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['dc'] =  $tmp['dfs']['Dir_Act'];
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['idc'] =  $tmp['dfs']['InDir_Act']; 
            
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['revenue'] =  $tmp['dfs']['Rev_Com'];
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['dc'] =  $tmp['dfs']['Dir_Com'];
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['idc'] =  $tmp['dfs']['InDir_Com']; 
            
            
        }
        
        //print_r($TmpActual); exit;
        
        
        
        
        $Actual = $this->DashboardData->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost,
commit2 commit2,
direct_cost_commit2,
indirect_cost_commit2
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id
WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        
        
        
        foreach($Actual as $bas)
        {   
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']))
            {
                $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
            }
            else
            {
                //if($bas['cm']['id']=='178') { print_r($NewData); exit; exit;}
                if($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='' && $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['dc']))
            {
                $NewData[$bas['cm']['id']]['Actual']['dc'] =  $bas['dd']['DirectCost'];  
            }
            else
            {
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' && $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['cm']['id']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc']; 
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['idc']))
            {
                
                $NewData[$bas['cm']['id']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];//if($bas['cm']['id']=='175') {echo "asdf1"; exit;}; 
            }
            else
            {
                
                if(isset($TmpActual[$bas['cm']['id']]['Actual']['idc']) && $TmpActual[$bas['cm']['id']]['Actual']['idc']!='' && $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                     $NewData[$bas['cm']['id']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc']; //if($bas['cm']['id']=='175') {echo "asdf2"; exit; }
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Actual']['idc'] =  $bas['dd']['InDirectCost']; //if($bas['cm']['id']=='175') {echo $TmpActual[$bas['cm']['id']]['Actual']['idc']; exit; }
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['revenue']))
            {
                $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $bas['dd']['commit2'];
                //if($bas['cm']['id']=='178') { print_r($TmpActual); echo "asdf"; exit;}
            }
            else
            {
                //if($bas['cm']['id']=='178') { print_r($NewData); exit; exit;}
                if($TmpActual[$bas['cm']['id']]['Commit']['revenue']!='' && $TmpActual[$bas['cm']['id']]['Commit']['revenue']!=null)
                {
                    $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $TmpActual[$bas['cm']['id']]['Commit']['revenue'];
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $bas['dd']['commit2'];
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['dc']))
            {
                $NewData[$bas['cm']['id']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2'];  
            }
            else
            {
                if($TmpActual[$bas['cm']['id']]['Commit']['dc']!='' && $TmpActual[$bas['cm']['id']]['Commit']['dc']!=null)
                {
                    
                    $NewData[$bas['cm']['id']]['Commit']['dc'] =  $TmpActual[$bas['cm']['id']]['Commit']['dc']; 
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2']; 
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['idc']))
            {
                
                $NewData[$bas['cm']['id']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2'];//if($bas['cm']['id']=='175') {echo "asdf1"; exit;}; 
            }
            else
            {
                
                if(isset($TmpActual[$bas['cm']['id']]['Commit']['idc']) && $TmpActual[$bas['cm']['id']]['Commit']['idc']!='' && $TmpActual[$bas['cm']['id']]['Commit']['idc']!=null)
                {
                    
                     $NewData[$bas['cm']['id']]['Commit']['idc'] =  $TmpActual[$bas['cm']['id']]['Commit']['idc']; //if($bas['cm']['id']=='175') {echo "asdf2"; exit; }
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2']; //if($bas['cm']['id']=='175') {echo $TmpActual[$bas['cm']['id']]['Actual']['idc']; exit; }
                }
            }
            
            $cost_master[] = $bas['cm']['id'];
        }
        //print_r($NewData); exit;
        
        
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
 WHERE ti.invoiceType='Revenue' and left(ti.month,3)='$finMonth' and ti.finance_year='$finYear' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center
WHERE pm.invoiceType1='Revenue' and  pm.branch_name='$Branch' and left(pm.month,3)='$finMonth' and pm.finance_year='$finYear'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
        $RevenuePart = $this->Targets->query("SELECT cm.id,pm.outsource_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center $BranchAll
WHERE  pm.branch='$Branch' and left(pm.FinanceMonth,3)='$finMonth' and pm.FinanceYear='$finYear'");
  
        foreach($RevenuePart as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            //$NewData[$rev_['cm']['id']]['Actual']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
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
    
    public function save_actual_revenue()
    {
        if(!empty($this->request->data))
        {
            //print_r($this->request->data); exit;
            $cost_id = $cost_center = $this->request->data['Targets']['cost_id'];
            
            $commit2 = $this->request->data['Targets']['commit2'];
            
            $NewFinanceYear = $this->request->data['Targets']['finYear'];
            $FinanceMonth = $this->request->data['Targets']['finMonth'];
            $monthArr = array('Jan','Feb','Mar'); 
            $split = explode('-',$NewFinanceYear); 
            $FinanceMonth1 = "";
            if(in_array($FinanceMonth, $monthArr)) 
            {
                $FinanceMonth1 .= $FinanceMonth.'-'.$split[1];    //Year from month
            }
            else
            {
                $FinanceMonth1 .= $FinanceMonth.'-'.($split[1]-1);    //Year from month
            }
            $cost = $this->request->data['cost'];
            $branchArr = $this->CostCenterMaster->find('first',array('conditions'=>array("id"=>$cost_id)));
            $branch = $branchArr['CostCenterMaster']['branch'];
            $costRate = $this->request->data['costRate'];
            $date_val = $this->request->data['date'];
            //print_r($date_val); exit;
            
            $dateRate = $this->request->data['dateRate'];
            $mtd_arr = $this->request->data['mtd'];
            $forecast_arr = $this->request->data['forcast'];
            //$mnt_arr = $this->request->data['mnt_arr'];
            $Forecast = 0;$Mtd = 0;
            
            $month_arr = array("Jan"=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>"08",'Sep'=>"09","Oct"=>"10","Nov"=>'11','Dec'=>'12');
            $m=1;
            //echo $finMonth;
            $mnt = $month_arr[$FinanceMonth];
            if(in_array($mnt,array("01","02","03")))
            {
                $year = date("Y");
            }
            else
            {
                $year = date("Y")-1;
            }
                        
            $start_date = 1;
            $today_date =  date("t",strtotime("01-$mnt-$year"));
            $end_date =  $today_date;

            
                    $header_sum_total = 0;   $mnt_arr=$today_date;
                    $date_wise_sum = array();
                    $flag_add = false;
                    // sum of date_wise for checking entry is here or not.
                    
                    $insertMonth = $year.'-'.$month_arr[$FinanceMonth];
                    
                    
                    for($jj=1; $jj<=$mnt_arr;$jj++)
                    {
                        foreach(explode(",",$this->request->data['id_arr']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_$headerId"];
                        }
                        foreach(explode(",",$this->request->data['id_arr_rate']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_"."$headerId"]*$dateRate["$jj"."_"."$headerId"];
                        }
                        foreach(explode(",",$this->request->data['not_added_arr']) as $headerId)
                        {
                            $date_wise_sum[$jj] += $date_val["$jj"."_$headerId"];
                        }
                    }
                    
                    
                    
                    foreach(explode(",",$this->request->data['id_arr']) as $headerId)
                    {
                        
                        $header_sum=0;
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $header_sum += $date_val["$jj"."_$headerId"];
                        }
                        
                          
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {    
                            $insertDate = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj); 
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                                
                              if(!empty($date_wise_sum[$jj]))
                              {
                                  $insertDate1 = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                              }
                              else
                              {
                                  $flag_add = true;
                              }
                            }
                            
                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            //$new_data['insertDate'] = $year.'-'.'03'.'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['insertDate'] = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $cost_center;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = 1;
                            $new_data['insertDateDet'] = $date_val[(strlen($jj)==1?'0'.$jj:$jj)."_$headerId"]; 
                            
                            $new_data['insertDateRate'] = 1;

                            $new_data['Mtd'] = $header_sum;
                            //$Mtd += $mtd_arr[$headerId];
                            $new_data['Forecast'] = round($header_sum,2);
                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            
                            
                                $data_arr[] = $new_data;
                            
                            
                        }
                            
                        if($headerId=='10')
                        {

                        }
                        else if($headerId=='8')
                        {
                            $header_sum_total -= $header_sum;
                        }
                        else
                        {
                            $header_sum_total += $header_sum;
                        }
                    }
                    
                    foreach(explode(",",$this->request->data['id_arr_rate']) as $headerId)
                    {
                        $header_sum=0; $rate_sum = 0;
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $header_sum += $date_val["$jj"."_$headerId"];
                        }
                        
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            $insertDate = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                              if(!empty($date_wise_sum[$jj]) && $flag_add)
                              {
                                  $insertDate1 = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                              }
                              else
                              {
                                  $flag_add = true;
                              }
                            }
                            
                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            $new_data['insertDate'] = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $cost_center;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = $costRate[$headerId];
                            $new_data['insertDateDet'] = $date_val["$jj"."_"."$headerId"];
                            $new_data['insertDateRate'] = $dateRate["$jj"."_"."$headerId"];
                            $new_data['Mtd'] = $header_sum/$mnt_arr;
                             $rate_sum += $date_val["$jj"."_"."$headerId"]*$dateRate["$jj"."_"."$headerId"];
                            
                            $new_data['Forecast'] = round($header_sum*($end_date/$calculation_days),2);

                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            
                                $data_arr[] = $new_data;
                            
                        }
                        if($headerId=='10')
                        {

                        }
                        else if($headerId=='8')
                        {
                            $header_sum_total -= $rate_sum;
                        }
                        else
                        {
                            $header_sum_total += $rate_sum;
                        }
                    }
                    
                    //for non added value
                    foreach(explode(",",$this->request->data['not_added_arr']) as $headerId)
                    {
                        for($jj=1; $jj<=$mnt_arr;$jj++)
                        {
                            
                            $insertDate = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                              if(!empty($date_wise_sum[$jj]) && $flag_add)
                              {
                                  $insertDate1 = $year.'-'.$month_arr[$FinanceMonth].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                              }
                              else
                              {
                                  $flag_add = true;
                              }
                            }
                            
                            
                            
                            $new_data = array();
                            $new_data['FinanceYear'] = $NewFinanceYear;  
                            $new_data['FinanceMonth'] = $FinanceMonth;
                            $new_data['branch'] = $branch;
                            $new_data['FinanceMonth1'] = $FinanceMonth1;
                            $new_data['insertDate'] = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $cost_center;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = 1;
                            $new_data['insertDateDet'] = $date_val["$jj"."_"."$headerId"];;
                            $new_data['insertDateRate'] = 1;

                            $new_data['Mtd'] = $mtd_arr[$headerId];
                            $Mtd = $mtd_arr[$headerId];

                            $new_data['Forecast'] = $forecast_arr[$headerId];
                            $Forecast +=$forecast_arr[$headerId];
                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                           
                                $data_arr[] = $new_data;
                           
                        }
                    }
            
                        //echo $header_sum_total; exit;
            
             
            $select = $this->DashboardFreezeRevenue->find('first',array('conditions'=>"costcenterId='$cost_center' and FinanceYear='$NewFinanceYear' and FinanceMonth='$FinanceMonth'"));
            
            if(!empty($select))
            {
                //$this->DashboardData->deleteAll(array('cost_centerId'=>$cost_center,'FinanceYear'=>$NewFinanceYear,'FinanceMonth'=>$FinanceMonth));
                $this->DashboardFreezeRevenue->deleteAll(array('costcenterId'=>$cost_center,'FinanceYear'=>$NewFinanceYear,'FinanceMonth'=>$FinanceMonth));
            }
            
            //print_r($data_arr); exit;
            
            if($this->DashboardFreezeRevenue->saveAll($data_arr))
            {
                $datas =  $this->request->data['Targets'];
                
                //print_r($data); exit;
                //$commit = $data['commit'];

                $id = $datas['id'];
                $userid = $this->Session->read("userid");

                if($id=='')
                {
                   $this->Session->setFlash("Server Error. Please Try Again After Some Time");
                }

                $Details = $this->CostCenterMaster->find('first',array('conditions'=>array('id'=>$cost_id)));

                $FinanceYear = $NewFinanceYear;
                $FinanceMonth = $FinanceMonth;
                $Branch = $Details['CostCenterMaster']['branch'];

                $upd = array('updated_by'=>$userid,'updated_at'=>"'".date('Y-m-d H:i:s')."'");
                $save = array('FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'Branch'=>$Branch,'CostCenterId'=>$cost_id,'created_by'=>$userid,'created_at'=>date('Y-m-d H:i:s'));

                    //Provision Check
                    $os = $this->Targets->query("SELECT cm.id,SUM(total) Total FROM tbl_invoice ti
            INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.id='$cost_id'
            WHERE ti.Finance_Year='$FinanceYear' AND ti.`Month`='$FinanceMonth' AND ti.status=0;");

                    //print_r($provision); exit;
                    $NewOS = round($os['0']['0']['Total'],2);

                    $upd['Rev_Act'] = $header_sum_total;
                    $upd['Rev_Com'] = $commit2;
                    $save['Rev_Act'] = $header_sum_total;
                    $save['Rev_Com'] = $commit2;
                    }

                    $FreezeData = $this->FreezeData->find('first',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and CostCenterId='$cost_id'"));
                if(!empty($FreezeData))
                {

                    if($this->FreezeData->updateAll($upd,array('FreezeId'=>$FreezeData['FreezeData']['FreezeId'])))
                    {

                        $this->Session->setFlash('<font color="green">Record Updated Successfully</font>');
                        $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$branch))); 
                        echo "Updated"; exit;
                    }
                    else
                    {
                        $this->Session->setFlash('<font color="green">Record Not Updated</font>');
                        $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$branch))); 
                        echo "NotUpdated"; exit;
                    }
                }
                else
                {
                    //print_r($save); exit;
                    
                    if($this->FreezeData->save($save))
                    {
                        $this->Session->setFlash('<font color="green">Record Saved Successfully</font>');
                        $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$branch)));   
                        echo "Saved"; exit;
                    }
                    else
                    {
                        echo "reason"; exit;
                        $this->Session->setFlash('<font color="red">Record Not Saved</font>');
                        $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$branch)));   
                        echo "NotSaved"; exit;
                    }
                }
                 $this->redirect(array('action'=>'freeze_request','?'=>array('finYear'=>$FinanceYear,'finMonth'=>$FinanceMonth,'Branch'=>$branch)));   
                    exit;
        }
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
        
        
        $flag = true;
        $NewProv = $this->FreezeData->find("list",array("conditions"=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and Branch='$Branch'",'fields'=>array('CostCenterId','Rev_Act')));
        
            foreach($NewProv as $k=>$new_prov)
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
                                
                $provisionMade = $this->Provision->query("SELECT t1.cost_center,SUM(total) total FROM tbl_invoice t1 INNER JOIN cost_master cm ON t1.cost_center=cm.cost_center AND cm.id='$k'
 WHERE t1.status='0'and t1.bill_no!='' AND t1.month='$NewFinanceMonth' AND t1.finance_year='$FinanceYear'");
                
                $bill_made = 0;
                if(empty($provisionMade))
                {
                    $bill_made = 0;
                }
                else if(empty($provisionMade['0']['0']['total']))
                {
                    $bill_made = 0;
                }
                else
                {
                    $bill_made = $provisionMade['0']['0']['total'];
                }
                
                if($new_prov<$bill_made)
                {
                    $flag = false;
                    $error_msg = "Provision For Cost Center - {$provisionMade['t1']['cost_center']} is less then bill made";
                    break;
                }
                
            }
             
//            $DataBudgetOld = $this->ExpenseMaster->find('all',array('conditions'=>"FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and Branch='$Branch'"));
//            if(!empty($DataBudgetOld))
//            {
//                $iRow=0; $NewArrayBudget = array();  $PartOld= array();
//                foreach($DataBudgetOld as $old_budget)
//                {
//                    $NewArrayBudget[$iRow++]['ExpenseMasterOld'] = $old_budget['ExpenseMaster'];
//                    $DataBudgetOldPart = $this->ExpenseParticular->find('all',array('conditions'=>"ExpenseId='{$old_budget['ExpenseMaster']['Id']}'"));
//                    
//                    foreach($DataBudgetOldPart as $part_old)
//                    {
//                        $PartOld[$iRow++]['ExpenseParticularOld'] = $part_old['ExpenseParticular'];  
//                    }
//                }
//                
//                
//            }
            
            
        if($flag)
        {
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
                $NewData['Rev_Com'] = $v['Rev_Com'];

                
                $NewData['Dir_Asp'] = $v['Dir_Asp'];
                $NewData['Dir_Act'] = $v['Dir_Act'];
                $NewData['Dir_Bas'] = $v['Dir_Bas'];
                $NewData['Dir_Com'] = $v['Dir_Com'];
                

                $NewData['InDir_Asp'] = $v['InDir_Asp'];
                $NewData['InDir_Act'] = $v['InDir_Act'];
                $NewData['InDir_Bas'] = $v['InDir_Bas'];
                $NewData['InDir_Com'] = $v['InDir_Com'];
                

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
                $this->redirect(array('action'=>'view_freeze_request'));
            }
        }
        else
        {
            $this->Session->setFlash($error_msg);
            $this->redirect(array('action'=>'view_freeze_request'));
        }
        
        exit;
    }
    
    public function view_freeze_request_for_approval()
    {
        $this->layout = "home";
        $data = $this->FreezeData->query("SELECT Branch,FinanceYear,FinanceMonth,
        SUM(Rev_Asp) Rev_Asp,
        SUM(Rev_Act) Rev_Act ,
        SUM(Rev_Com) Rev_Com ,
        SUM(Rev_Bas) Rev_Bas ,

        SUM(Dir_Asp) Dir_Asp ,
        SUM(Dir_Act) Dir_Act ,
        SUM(Dir_Com) Dir_Com ,
        SUM(Dir_Bas) Dir_Bas ,

        SUM(InDir_Asp) InDir_Asp ,
        SUM(InDir_Act) InDir_Act ,
        SUM(InDir_Com) InDir_Com ,
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
AND ep.ExpenseTypeId='$cost_id' order by hm.HeadingDesc,sh.SubHeadingDesc"; 
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
AND ep.ExpenseTypeId='$cost_id' order by hm.HeadingDesc,sh.SubHeadingDesc"; 
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
        
        $YearArrRes = explode("-",$finYear);
        if(in_array(strtolower($finMonth),'jan','feb','mar'))
        {
            $YearRes = $YearArrRes[0]+1;
        }
        else
        {
            $YearRes = $YearArrRes[0];
        }
        
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
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['revenue'] =  $tmp['dfs']['Rev_Com'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['dc'] =  $tmp['dfs']['Dir_Com'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Commit']['idc'] =  $tmp['dfs']['InDir_Com'];
            
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['revenue'] =  $tmp['dfs']['Rev_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['dc'] =  $tmp['dfs']['Dir_Act'];
            $TmpActual[$tmp['dfs']['CostCenterId']]['Actual']['idc'] =  $tmp['dfs']['InDir_Act'];
            
            $NewData[$tmp['dfs']['CostCenterId']]['Basic']['revenue'] =  $tmp['dfs']['Rev_Bas'];
            $NewData[$tmp['dfs']['CostCenterId']]['Basic']['dc'] =  $tmp['dfs']['Dir_Bas'];
            $NewData[$tmp['dfs']['CostCenterId']]['Basic']['idc'] =  $tmp['dfs']['InDir_Bas']; 
            
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['revenue'] =  $tmp['dfs']['Rev_Act'];
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['dc'] =  $tmp['dfs']['Dir_Act'];
            $NewData[$tmp['dfs']['CostCenterId']]['Actual']['idc'] =  $tmp['dfs']['InDir_Act']; 
            
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['revenue'] =  $tmp['dfs']['Rev_Com'];
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['dc'] =  $tmp['dfs']['Dir_Com'];
            $NewData[$tmp['dfs']['CostCenterId']]['Commit']['idc'] =  $tmp['dfs']['InDir_Com']; 
            
        }
        
        //print_r($TmpActual); exit;
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost,
commit2 commit2,
direct_cost_commit2,
indirect_cost_commit2
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id
WHERE YEAR(dd.createdate)='$YearRes'  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)='$YearRes'  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        
        foreach($Actual as $bas)
        {   
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']))
            {
                $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                //if($bas['cm']['id']=='178') { print_r($TmpActual); echo "asdf"; exit;}
            }
            else
            {
                //if($bas['cm']['id']=='178') { print_r($NewData); exit; exit;}
                if($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='' && $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['cm']['id']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['dc']))
            {
                $NewData[$bas['cm']['id']]['Actual']['dc'] =  $bas['dd']['DirectCost'];  
            }
            else
            {
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' && $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['cm']['id']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc']; 
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Actual']['idc']))
            {
                $NewData[$bas['cm']['id']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
            }
            else
            {
                if(isset($TmpActual[$bas['cm']['id']]['Actual']['idc']) && $TmpActual[$bas['cm']['id']]['Actual']['idc']!='' && $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                    $NewData[$bas['cm']['id']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc'];
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                }
            }
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['revenue']))
            {
                $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $bas['dd']['commit2'];
                //if($bas['cm']['id']=='178') { print_r($TmpActual); echo "asdf"; exit;}
            }
            else
            {
                //if($bas['cm']['id']=='178') { print_r($NewData); exit; exit;}
                if($TmpActual[$bas['cm']['id']]['Commit']['revenue']!='' && $TmpActual[$bas['cm']['id']]['Commit']['revenue']!=null)
                {
                    $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $TmpActual[$bas['cm']['id']]['Commit']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['cm']['id']]['Commit']['revenue'] =  $bas['dd']['commit2'];
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['dc']))
            {
                $NewData[$bas['cm']['id']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2'];  
            }
            else
            {
                if($TmpActual[$bas['cm']['id']]['Commit']['dc']!='' && $TmpActual[$bas['cm']['id']]['Commit']['dc']!=null)
                {
                    
                    $NewData[$bas['cm']['id']]['Commit']['dc'] =  $TmpActual[$bas['cm']['id']]['Commit']['dc']; 
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Commit']['dc'] =  $bas['dd']['direct_cost_commit2']; 
                }
            }
            
            if(empty($TmpActual[$bas['cm']['id']]['Commit']['idc']))
            {
                
                $NewData[$bas['cm']['id']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2'];//if($bas['cm']['id']=='175') {echo "asdf1"; exit;}; 
            }
            else
            {
                
                if(isset($TmpActual[$bas['cm']['id']]['Commit']['idc']) && $TmpActual[$bas['cm']['id']]['Commit']['idc']!='' && $TmpActual[$bas['cm']['id']]['Commit']['idc']!=null)
                {
                    
                     $NewData[$bas['cm']['id']]['Commit']['idc'] =  $TmpActual[$bas['cm']['id']]['Commit']['idc']; //if($bas['cm']['id']=='175') {echo "asdf2"; exit; }
                }
                else
                {
                    $NewData[$bas['cm']['id']]['Commit']['idc'] =  $bas['dd']['indirect_cost_commit2']; //if($bas['cm']['id']=='175') {echo $TmpActual[$bas['cm']['id']]['Actual']['idc']; exit; }
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
 WHERE ti.invoiceType='Revenue' and  ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center
WHERE pm.invoiceType1='Revenue' and  pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            if(empty($NewData[$rev_['cm']['id']]['Basic']['revenue']))
            {
                $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            }
            $cost_master[] = $rev_['cm']['id'];
        }
        $RevenuePart = $this->Targets->query("SELECT cm.id,pm.outsource_amt FROM provision_particulars pm
INNER JOIN cost_master cm ON pm.Cost_Center_OutSource=cm.cost_center 
WHERE  pm.branch='$Branch' and pm.FinanceMonth='$NewFinanceMonth' ");
  
        foreach($RevenuePart as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
            //$NewData[$rev_['cm']['id']]['Actual']['revenue'] +=  round($rev_['pm']['outsource_amt'],2);
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
            if(empty($NewData[$DirectBC['cm']['id']]['Basic']['dc']))
            {
                if(isset($NewBasicBusiness[$DirectBC['ep']['id']]))
                {

                    $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $NewBasicBusiness[$DirectBC['ep']['id']];
                }
                else
                {
                    $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];
                }
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
            if(empty($NewData[$InDirectBC['cm']['id']]['Basic']['idc']))
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
            }
            $cost_master[] = $InDirectBC['cm']['id'];
        }
        
        
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
                
                $this->Provision->query("INSERT INTO `dashboard_save_prov`(id,invoiceType1,cost_center,branch_name,finance_year,month,provision,provision_balance,agreement,acknowledgment,remarks,send_bps,userid,createdate) SELECT pm.id,invoiceType1,pm.cost_center,pm.branch_name,pm.finance_year,pm.month,pm.provision,pm.provision_balance,pm.agreement,pm.acknowledgment,pm.remarks,pm.send_bps,pm.userid,pm.createdate FROM provision_master pm inner join cost_master cm on pm.cost_center=cm.cost_center and cm.id='$k'  WHERE pm.month='$NewFinanceMonth' AND pm.finance_year='$FinanceYear'");
                
                $this->Provision->query("UPDATE provision_master pm INNER JOIN cost_master cm ON pm.cost_center = cm.cost_center and cm.id='$k'"
                        . " SET pm.provision='$v',pm.provision_balance='$v' WHERE pm.invoiceType1='Revenue' and  pm.month='$NewFinanceMonth' AND pm.finance_year='$FinanceYear'");
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
    
    public function asp_delete() 
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
            foreach($branchName as $b){
                $branch[$b] = $b; 
            }
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else
        {
            $this->set('branchName',array($branchName=>$branchName));
        }
        
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data['Targets'];
            
            //print_r($data); exit;
            $branch=$data['branch'];
            $finance_year = $data['finance_year'];
            $month = $data['month'];
            $cost_centerId = $data['cost_centerId'];
            $userid = $this->Session->read('userid');
            
            $this->Targets->query("INSERT INTO dashboard_target_delete(
id,user_id,FinanceYear,FinanceMonth,cost_center,cost_centerId,branch,branch_id,branch_process,target,
target_directCost,target_IDC,actualCommitment,os,finance_cost,createdate,`month`,createby,updatedate,
updateby,deleteby,deletedate)
SELECT
id,user_id,FinanceYear,FinanceMonth,cost_center,cost_centerId,branch,branch_id,branch_process,target,
target_directCost,target_IDC,actualCommitment,os,finance_cost,createdate,`month`,createby,updatedate,
updateby,'$userId',NOW() FROM  dashboard_Target WHERE branch='$branch' and FinanceYear='$finance_year'"
                    . " AND FinanceMonth='$month' AND cost_centerId='$cost_centerId'");
            $random = rand(1000,100000);
            if($this->Targets->deleteAll(array('branch'=>$branch,'FinanceYear'=>$finance_year,'FinanceMonth'=>$month,'cost_centerId'=>$cost_centerId)))
            {
                $this->Targets->query("INSERT INTO dashboard_target_revenue_delete(
DelId,DashId,branch,FinanceYear,FinanceMonth,FinanceMonth1,insertDate,CostCenterId,HeaderId,CostCenterMonthDet,
CostCenterMonthDetRate,insertDateDet,insertDateRate,Forecast,Mtd,created_at,created_by,deleted_at,deleted_by)
SELECT 
'$random',DashId,branch,FinanceYear,FinanceMonth,FinanceMonth1,insertDate,CostCenterId,HeaderId,CostCenterMonthDet,
CostCenterMonthDetRate,insertDateDet,insertDateRate,Forecast,Mtd,created_at,created_by,'$userid',NOW()
FROM `dashboard_target_revenue` WHERE branch='$branch' and FinanceYear='$finance_year'"
                    . " AND FinanceMonth='$month' AND costcenterId='$cost_centerId'");
                
                if($this->DashboardTargetRevenue->deleteAll(array('branch'=>$branch,'FinanceYear'=>$finance_year,'FinanceMonth'=>$month,'costcenterId'=>$cost_centerId)))
                {
                    $this->Session->setFlash('<font color="red">Record Deleted Successfully</font>');
                }
                else
                {
                    $this->Session->setFlash('<font color="red">Record Not Deleted Successfully1</font>');
                }
            }
            else
            {
                $this->Session->setFlash('<font color="red">Record Not Deleted Successfully2</font>');
            }
                
            
            
        }

    }
    
    
}

?>