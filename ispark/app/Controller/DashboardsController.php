<?php
class DashboardsController extends AppController 
{
    public $uses=array('DashboardData','DashboardRevenue','DashboardProcess','Addbranch','CostCenterMaster','Targets');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout='home';
        if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        
        else
        {
            $role=$this->Session->read("role"); $this->Auth->allow('get_os','get_data');
            $roles=explode(',',$this->Session->read("page_access"));				
            $this->Auth->allow('index','get_data');$this->Auth->allow('get_process');$this->Auth->allow('get_tower','get_freeze_data');$this->Auth->allow('add_process');$this->Auth->allow('get_dash_data');
        $this->Auth->allow('get_data');	$this->Auth->allow('get_table');$this->Auth->allow('getexpot');
        }
    }
		
    public function index() 
    {
        $this->layout="home";
        $branchName = $this->Session->read('branch_name');
        /*
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
            unset($branch);
            unset($branchName);
        }
        else
        {*/
            $date_cur_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 DAY),'%d-%b-%Y') currentdate FROM `dashboard_cost_parts` LIMIT 1");
            $date_cur_det =  $date_cur_det['0']['0']['currentdate'];
            $this->set('date_cur_det',$date_cur_det);
            
            $date_end_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 DAY)),'%d') enddate FROM `dashboard_cost_parts` LIMIT 1");
            $date_end_det =  $date_end_det['0']['0']['enddate']; 
            $this->set('end_date',$date_end_det);
            
            $ParticularDetails = $this->CostCenterMaster->query("SELECT * FROM `dashboard_cost_parts` parts ORDER BY Priority");
            $this->set('ParticularDetails',$ParticularDetails);
       
            $processArr = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>"branch='$branchName'
            and active='1' and (close>=date(now()) or close is null)"));
            
            if(!empty($processArr))
            {
                foreach($processArr as $tow)
                {
                   $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
                }
            }
    
            $this->set('tower1',$tower1);
        //}
        
        if($this->request->is('Post'))
        { 
            if(!empty($this->request->data))
            {
                //print_r($this->request->data); exit;
                $cost_center = $this->request->data['Dashboard']['cost_centerId'];
                $arrayMonth = array(
                    1=>'Jan','01'=>'Jan','1'=>'Jan',
                    '02'=>'Feb','2'=>'Feb',2=>'Feb',
                    '03'=>'Mar','3'=>'Mar',3=>'Mar',
                    '04'=>'Apr','4'=>'Apr',4=>'Apr',
                    '05'=>'May','5'=>'May',5=>'May',
                    '06'=>'Jun','6'=>'Jun',6=>'Jun',
                    '07'=>'Jul','7'=>'Jul',7=>'Jul',
                    '08'=>'Aug','8'=>'Aug',8=>'Aug',
                    '09'=>'Sep','9'=>'Sep',9=>'Sep',
                    '10'=>'Oct',10=>'Oct',
                    '11'=>'Nov',11=>'Nov',
                    '12'=>'Dec',12=>'Dec');
                $arrayMonth1 =  array('Jan'=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>'08','Sep'=>'09','Oct'=>'10','Nov'=>'11','Dec'=>'12');
                
                $date_cur_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 DAY),'%d-%b-%Y') currentdate FROM `dashboard_cost_parts` LIMIT 1");
                $date_cur_det =  $date_cur_det['0']['0']['currentdate'];
                $curdate = explode("-",$date_cur_det);
            
                $date_end_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 DAY)),'%d') enddate FROM `dashboard_cost_parts` LIMIT 1");
                $end_date =  $date_end_det['0']['0']['enddate']; 
            
                $today_date =  $curdate[0];
                $calculation_days = $today_date-$start_date;
                
                $MntArr = explode("-",$date_cur_det);
                //print_r($MntArr);
                $FinanceYear =  $MntArr[2];
                $FinanceMonth =  $MntArr[1];
                $branch = $this->Session->read('branch_name');
                //print_r($MntArr);
                
                if(in_array($FinanceMonth,array('Jan','1','Feb','2','Mar','3')))
                {
                    $NewFinanceYear = $FinanceYear-2000; 
                    $FinanceMonth1 = $FinanceMonth.'-'.$NewFinanceYear;
                    $NewFinanceYear = ($FinanceYear-1).'-'.($NewFinanceYear);
                }
                else
                {
                    $NewFinanceYear = $FinanceYear-2000;
                    $NewFinanceYear = $FinanceYear.'-'.($NewFinanceYear+1);
                }
                
                //$data['FinanceYear'] = $NewFinanceYear;
                //$data['FinanceMonth'] = $FinanceMonth;
                $cost = $this->request->data['cost'];
                $costRate = $this->request->data['costRate'];
                $date_val = $this->request->data['date'];
                //print_r($this->request->data); exit;
                $dateRate = $this->request->data['dateRate'];
                $Forecast = 0;

                
                //$select = $this->DashboardData->find('first',array('conditions'=>"cost_centerId='$cost_center' and date(createdate)=curdate()"));
                //if(empty($select))
                if(1)
                {
                    
                    
                    $header_sum_total = 0; $year = $MntArr[2];  $mnt_arr=$today_date;
                    $date_wise_sum = array();
                    $flag_add = false;
                    // sum of date_wise for checking entry is here or not.
                    
                    $insertMonth = $year.'-'.$arrayMonth1[$MntArr[1]];
                    $del  = $this->DashboardData->query("delete from dashboard_data where cost_centerId='$cost_center' and date(createdate)=curdate()");
                    $del2  = $this->DashboardData->query("delete from dashboard_data_revenue where CostCenterId='$cost_center' and date_format(insertDate,'%Y-%m')='$insertMonth'");
                    
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
                            $insertDate = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                                
                              if(!empty($date_wise_sum[$jj]))
                              {
                                  $insertDate1 = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
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
                            $new_data['insertDate'] = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $new_data['CostCenterId'] = $cost_center;
                            $new_data['HeaderId'] = $headerId;
                            $new_data['CostCenterMonthDet'] = $cost[$headerId];
                            $new_data['CostCenterMonthDetRate'] = 1;
                            $new_data['insertDateDet'] = $date_val["$jj"."_$headerId"]; 
                            $new_data['insertDateRate'] = 1;

                            $new_data['Mtd'] = $header_sum;
                            //$Mtd += $mtd_arr[$headerId];
                            $new_data['Forecast'] = round($header_sum*($end_date/$calculation_days),2);
                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            
                            if(empty($select_record_exist))
                            {
                                $data_arr[] = $new_data;
                            }
                            
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
                            $insertDate = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                              if(!empty($date_wise_sum[$jj]) && $flag_add)
                              {
                                  $insertDate1 = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
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
                            $new_data['CostCenterMonthDetRate'] = $costRate[$headerId];
                            $new_data['insertDateDet'] = $date_val["$jj"."_"."$headerId"];
                            $new_data['insertDateRate'] = $dateRate["$jj"."_"."$headerId"];
                            $new_data['Mtd'] = $header_sum/$mnt_arr;
                            $rate_sum += $date_val["$jj"."_"."$headerId"]*$dateRate["$jj"."_"."$headerId"];
                            $new_data['Forecast'] = round($header_sum*($end_date/$calculation_days),2);

                            $new_data['created_at'] = date('Y-m-d H:i:s');
                            $new_data['created_by'] = $this->Session->read('userid');
                            if(empty($select_record_exist))
                            {
                                $data_arr[] = $new_data;
                            }
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
                            
                            $insertDate = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
                            $select_record_exist = $this->DashboardRevenue->find('first',array('conditions'=>"CostCenterId='$cost_center' and date(insertDate)='$insertDate'"));
                            if(isset($this->request->data['set'.$jj]) && empty($select_record_exist))
                            {
                              if(!empty($date_wise_sum[$jj]) && $flag_add)
                              {
                                  $insertDate1 = $year.'-'.$arrayMonth1[$MntArr[1]].'-'.(strlen($jj)==1?'0'.$jj:$jj);
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
                            if(empty($select_record_exist))
                            {
                                $data_arr[] = $new_data;
                            }
                        }
                    }
                
                    
                    if($flag_add)
                    {
                        if($this->DashboardRevenue->saveAll($data_arr))
                        {
                            $data = array();
                            $data['FinanceYear'] = $NewFinanceYear;
                            $data['cost_centerId'] = $cost_center;
                            $data['FinanceMonth'] = $FinanceMonth;
                            $data['commit'] = round($header_sum_total*($end_date/$calculation_days),2);
                            $data['commit2'] = $this->request->data['Dashboard']['commit2'];
                            $data['direct_cost'] = $this->request->data['Dashboard']['direct_cost'];
                            $data['indirect_cost'] = $this->request->data['Dashboard']['indirect_cost'];
                            $data['EVITA'] = $this->request->data['Dashboard']['Fin_Cost'];
                            
                            $data['direct_cost_commit2'] = $this->request->data['Dashboard']['direct_cost_commit3'];
                            $data['indirect_cost_commit2'] = $this->request->data['Dashboard']['indirect_cost_commit3'];
                            
                            //$data['date'] = date('Y-m-d');
                            $data['createdate'] = date('Y-m-d H:i:s');
                            $data['user_id'] = $this->Session->read('userid');
                            $data['branch']=$this->Session->read('branch_name');
                            $this->DashboardData->save($data);

                            $this->Session->setFlash("<font color='green'> Record Saved Successfully </font>");
                            $this->redirect(array('action'=>'index'));
                        }
                        else
                        {
                            $this->Session->setFlash("<font color='red'>Record Not Saved, Please Try Again!</font>");
                        }
                    }
                    else 
                    {
                        $this->Session->setFlash("<font color='red'>Please Fill Value on $insertDate1</font>");
                    }
                    // print_r($data['cost_centerId']); die;
                    
                    
                }
                else
                {
                    $this->Session->setFlash("<font color='red'>Record Allready Exist. Please Try Again!</font>");
                }
            }
        }
     }

    public function get_process()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
        $this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName))));
    }
    
    public function get_freeze_data()
    {
        $this->layout='ajax';
         $cost_id = $this->request->data['cost_id']; 
         $cost_master_det = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),"conditions"=>"id='$cost_id'"));
         $cost_data = array();
         $cost_data['cost_center_name'] = $cost_master_det[$cost_id];
         
            $date_cur_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 DAY),'%d-%m-%Y') currentdate FROM `dashboard_cost_parts` LIMIT 1");
            $date_cur_det =  $date_cur_det['0']['0']['currentdate'];
            //$this->set('date_cur_det',$date_cur_det);
            
            $date_end_det = $this->CostCenterMaster->query("SELECT DATE_FORMAT(LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 DAY)),'%d') enddate FROM `dashboard_cost_parts` LIMIT 1");
            $date_end_det =  $date_end_det['0']['0']['enddate']; 
            //$this->set('end_date',$date_end_det);
             $dater =explode("-",$date_cur_det);
         $month = $dater[1];
        $year = $dater[2];
        //$date = date("Y-m-01");
        $date = $year.'-'.$month.'-01';
       
        
        
        $data = $this->Targets->find('first',array("conditions"=>"cost_centerId='$cost_id' and month='$date'"));
        //print_r($data); exit;
        //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
        if(!empty($data))
        {
            //echo $data['Targets']['target'].'##'.$data['Targets']['target_directCost'].'##'.$data['Targets']['target_IDC'].'##'.$data['Targets']['os'].'##'.$data['Targets']['finance_cost']; exit;
            $ParticularDetails = $this->CostCenterMaster->query("SELECT * FROM `dashboard_cost_parts` parts ORDER BY Priority");
            $costTotal = 0;
            
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                $data1 = $this->DashboardRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"));
                $cost_data['cost'.$headerId] = !empty($data1['DashboardRevenue']['CostCenterMonthDet'])?$data1['DashboardRevenue']['CostCenterMonthDet']:0;
                //print_r($data1); exit;
                //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                if($parts['parts']['RateRequired'])
                {
                    $cost_data['costRate'.$headerId] = !empty($data1['DashboardRevenue']['CostCenterMonthDetRate'])?$data1['DashboardRevenue']['CostCenterMonthDetRate']:0;
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
            
            /// For Old Month Details are here
            $m=(int)$dater[0];
            $n = 1; $mtd_old = 0; $count = 0; 
            while($m!=0)
            {
                
                $costTotal1 = 0; $headerTotalFlag = true;
                foreach($ParticularDetails as $parts)
                {
                    $headerId = $parts['parts']['PartId'];
                    
                    $data1 = $this->DashboardRevenue->find('first',array('conditions'=>"costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year' and (right(insertDate,2)='$m' || right(insertDate,2)='0$m')"));
                    if(!empty($data1) && $headerTotalFlag)
                    {
                        $count += 1; $headerTotalFlag = false;
                    }
                    $cost_data['date'.$m.'_'.$headerId] = !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
                    if(!empty($data1['DashboardRevenue']['Mtd']))
                    {
                        $cost_data['mtd'.$headerId] = !empty($data1['DashboardRevenue']['Mtd'])?$data1['DashboardRevenue']['Mtd']:0;
                        $cost_data['forcast'.$headerId] = !empty($data1['DashboardRevenue']['Forecast'])?$data1['DashboardRevenue']['Forecast']:0;
                    }
                    //$cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
                    //print_r($data1); exit;
                    //echo "costCenterId='$cost_id' and HeaderId='$headerId' and month(insertDate)='$month' and year(insertDate)='$year'"; exit;
                    $cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateDet'])?$data1['DashboardRevenue']['insertDateDet']:0;
                    if($parts['parts']['RateRequired'])
                    {
                        $cost_data['dateRate'.$m.'_'.$headerId] = !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        $cost_data['mtdRate'.$headerId] += !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        if($parts['parts']['AddRequired'])
                        {
                            if($headerId=='8')
                            {
                                $costTotal1 -= $data1['DashboardRevenue']['insertDateDet']*$data1['DashboardRevenue']['insertDateRate'];
                            }
                            else
                            {
                                $costTotal1 += $data1['DashboardRevenue']['insertDateDet']*$data1['DashboardRevenue']['insertDateRate'];
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
                            }
                            else
                            {
                                $costTotal1 += $data1['DashboardRevenue']['insertDateDet'];
                            }
                            //$cost_data['HeadTotal'.$headerId] += !empty($data1['DashboardRevenue']['insertDateRate'])?$data1['DashboardRevenue']['insertDateRate']:0;
                        }
                    }
                    
                }
                
                $cost_data['costTotal'] = !empty($costTotal)?$costTotal:0;
                $cost_data['DateTotal'.$m] = !empty($costTotal1)?$costTotal1:0;
                $mtd_old += !empty($costTotal1)?$costTotal1:0;
                $n++;
                $m--;
            }
            foreach($ParticularDetails as $parts)
            {
                $headerId = $parts['parts']['PartId'];
                $cost_data['mtdRate'.$headerId] = round($cost_data['mtdRate'.$headerId]/$count,2);
            }
            $cost_data['MtdTotal'] = $mtd_old;
            $cost_data['ForecastTotal'] = round($mtd_old*($date_end_det/($dater[0])),2);
            
            $data = $this->Targets->find('first',array("conditions"=>"cost_centerId='$cost_id' and month='$date'"));
            $cost_data['DashboardDirectCostFreeze'] = !empty($data['Targets']['target_directCost'])?$data['Targets']['target_directCost']:0;
            $cost_data['DashboardIndirectCostFreeze'] = !empty($data['Targets']['target_IDC'])?$data['Targets']['target_IDC']:0;
            $cost_data['DashboardFinCost'] = !empty($data['Targets']['finance_cost'])?$data['Targets']['finance_cost']:0;
            $cost_data['DashboardOS'] = !empty($data['Targets']['os'])?$data['Targets']['os']:0;
            $cost_data['commitment'] = !empty($data['Targets']['target'])?$data['Targets']['target']:0;
            
            echo json_encode($cost_data); exit;
        }
        else
        {
          echo "NotFound";  exit;
        }
        exit;
    }
    
    public function get_dash_data()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
        
        if(empty($branchName))
        {
            $branchName = $this->Session->read('branch_name');
        }
        
        $process = $this->request->data['process'];
        $this->set('dashboarddata',$this->DashboardData->query("SELECT DATE_FORMAT(dd.createdate,'%d-%b-%Y') `date`,dd.branch,dp.branch_process,dd.`commit`,dd.direct_cost,dd.indirect_cost,
            dd.EVITA,dd.direct_cost_commit2,dd.indirect_cost_commit2
FROM dashboard_data dd INNER JOIN dashboard_process dp ON dd.branch_process = dp.id
 WHERE dd.branch='$branchName' and dd.branch_process='$process'   ORDER BY dd.createdate DESC LIMIT 5"));
    }
    public function add_process()
    {
        $this->layout='home';
        $branchName = $this->request->data['branch'];
        if($this->Session->read('role')=='admin')
        {
            $this->set('process',$this->DashboardProcess->find('all',array('fields'=>array('id','Branch','branch_process'),'order'=>array('Branch'=>'Asc'))));
        }
        else
        {
            $this->set('process',$this->DashboardProcess->find('list',array('fields'=>array('id','Branch','branch_process'),'conditions'=>array('Branch'=>$branchName),'order'=>array('Branch'=>'Asc'))));
        }   
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
    
    //print_r($k);die;
    
    $tower = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>array('tower'=>$val,'branch'=>$k
     ,'active'=>'1',"(close>date(now()) or close is null)")));
    
    foreach($tower as $tow)
    {
        $tower1[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
    }
    
        $this->set('tower1',$tower1);
    }
     public function get_data()
    {
          $user_id = $this->Session->read('userid');
         $days=5;
    $format = 'd-M-Y';
     $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
       $date=date($format, mktime(0,0,0,$m,($de-$i),$y)); 
       if (date("D", strtotime($date)) == "Sun"){
           $i=$i+1;
    $date=date($format, mktime(0,0,0,$m,($de-$i),$y));
    $days=$days+1;
}
        $dateArray[] = $date; 
    } $dateArray= array_reverse($dateArray);
        $this->layout='home';
        //$branchName = $this->request->data['branch'];
        
       $branchName = $this->Session->read('branch_name');
       $role=$this->Session->read('role');
       $this->set('role',$role);
        if($role=='admin')
        { $br=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
            $this->set('branchName',$br);
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
        
        
        if($role=='admin')
        {
             $startdate=date('Y-m-d', strtotime('-5 days'));
       $endDate=date('Y-m-d');
       $cost=$br;
       unset($cost['HEAD OFFICE']);
          unset($cost['AHMEDABAD OTHERS']);
             unset($cost['JAIPUR IDC']);
 //print_r($cost);die;
      foreach($cost as $k=> $cc)
      {
          $data1=$this->DashboardData->query("SELECT dd.id, DATE_FORMAT(dd.createdate,'%d-%b-%Y') `date`,dp.branch as cost_center,dp.tower as branch_process,sum(dd.`commit`) as `commit`,sum(dd.direct_cost) as direct_cost,sum(dd.indirect_cost) as  indirect_cost,sum(dd.EVITA) as EVITA,dp.OwnerName as OwnerName
FROM dashboard_data dd INNER JOIN cost_master dp ON dd.cost_centerId = dp.id
 WHERE    date(dd.createdate) between '$startdate' and  '$endDate' and dd.branch='$cc' group by date(dd.createdate) ORDER BY date(dd.createdate)");
 $da[]=array('dp'=>array('cost_center'=>$cc,'cid'=>$k));
//print_r($da);
 foreach($data1 as $dd)
 {
     $data[]=$dd;
    //print_r($dd);
    ///$array[$dd[0]['date']][$dd['dd']['branch']]=$dd['dd']['branch'];
     //$array[$dd[0]['date']][$dd['dp']['branch_process']]=$dd['dp']['branch_process'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['commit']=$dd['0']['commit'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['direct_cost']=$dd['0']['direct_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['indirect_cost']=$dd['0']['indirect_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['id']=$dd['dd']['id'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['EVITA']=$dd['0']['EVITA'];
     //$array[$dd[0]['date']][$dd['dd']['direct_cost']]=$dd['dd']['direct_cost'];
      }}
        
       $this->set('dashddata',$da);
         $this->set('array',$array);
      
 }
        else{
        $tower=$this->DashboardProcess->find('list',array('fields'=>array('id','branch_process'),'conditions'=>array('Branch'=>$branchName)));
        $this->set('process',$tower);
        
       $startdate=date('Y-m-d', strtotime('-5 days'));
       $endDate=date('Y-m-d');
 $cost=$this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','CostCenterName','OwnerName'),'conditions'=>array('branch'=>$branchName
     ,'active'=>'1',"(close>date(now()) or close is null)")));
 //print_r($cost);die;
    //$this->set('dash',$cost);
      foreach($cost as  $cc)
      {//print_r($cc);die;
          
 $data1=$this->DashboardData->query("SELECT dd.id, DATE_FORMAT(dd.createdate,'%d-%b-%Y') `date`,dd.branch,dp.cost_center,dp.tower as branch_process,dd.`commit`,dd.direct_cost,dd.indirect_cost ,dd.EVITA,dp.OwnerName  as OwnerName
FROM dashboard_data dd INNER JOIN cost_master dp ON dd.cost_centerId = dp.id
 WHERE dd.branch='$branchName' and   date(dd.createdate) between '$startdate' and  '$endDate' and dp.cost_center = '{$cc['CostCenterMaster']['cost_center']}' ORDER BY dd.createdate");
 $da[]=array('dp'=>array('cost_center'=>$cc['CostCenterMaster']['cost_center'],'cid'=>$cc['CostCenterMaster']['id'],'Cname'=>$cc['CostCenterMaster']['CostCenterName'],'OwnerName'=>$cc['CostCenterMaster']['OwnerName']));
// print_r($data1);
// print_r($da); exit;
 foreach($data1 as $dd)
 {
     $data[]=$dd;
    //print_r($dd);
    ///$array[$dd[0]['date']][$dd['dd']['branch']]=$dd['dd']['branch'];
     //$array[$dd[0]['date']][$dd['dp']['branch_process']]=$dd['dp']['branch_process'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['commit']=$dd['dd']['commit'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['direct_cost']=$dd['dd']['direct_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['indirect_cost']=$dd['dd']['indirect_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['id']=$dd['dd']['id'];
  $array[$dd[0]['date']][$dd['dp']['cost_center']]['EVITA']=$dd['dd']['EVITA'];
     //$array[$dd[0]['date']][$dd['dd']['direct_cost']]=$dd['dd']['direct_cost'];
      } }//$data=array_merge($data,$da);
      //print_r($da);die;
      //$this->set('dash',$da);
      $this->set('branchName',$branchName);
        $this->set('dashddata',$da);
         $this->set('array',$array);
        }
         
          if($this->request->is('Post'))
        {
              $datain=$this->request->data;
              foreach($da as $Fetch){
                  for($i=0;$i<=4;$i++){
                        //print_r($datain['cost'.$dateArray[$i]. $Fetch['dp']['cost_center']]);
                  if( $datain['comit'.$dateArray[$i]. $Fetch['dp']['cost_center']]!='' && $datain['dc'.$dateArray[$i]. $Fetch['dp']['cost_center']]!='' &&  $datain['ic'.$dateArray[$i]. $Fetch['dp']['cost_center']]!=''){
                     //print_r($datain['Cost'.$dateArray[$i]. $Fetch['dp']['cost_center']]);
                     $date=date_create($dateArray[$i]);
$InDate= date_format($date,"Y-m-d");
               $CrDate= date_format($date,"Y-m-d H:i:s");      
                    $this->DashboardDataNew->query( "insert into dashboard_data set cost_centerId='{$datain['Cost'.$dateArray[$i]. $Fetch['dp']['cost_center']]}', branch='$branchName',`commit`='{$datain['comit'.$dateArray[$i]. $Fetch['dp']['cost_center']]}',direct_cost='{$datain['dc'.$dateArray[$i]. $Fetch['dp']['cost_center']]}',indirect_cost='{$datain['ic'.$dateArray[$i]. $Fetch['dp']['cost_center']]}',user_id='$user_id',InDate='$InDate',createdate='$CrDate'");
                    $flag=true; 
                    
                  }
                   }
              } 
           
             return $this->redirect(array('action'=>'get_data')); 
            
          }
 }
    
    
    public function get_table()
    {
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
        $startdate=date('Y-m-d', strtotime('-5 days'));
         $endDate=date('Y-m-d');
      $cost=$this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','CostCenterName'),'conditions'=>array('branch'=>$branchName
     ,'active'=>'1',"(close>date(now()) or close is null)")));
     foreach($cost as  $cc)
      {//print_r($cc);die;
          
 $data1=$this->DashboardData->query("SELECT dd.id, DATE_FORMAT(dd.createdate,'%d-%b-%Y') `date`,dd.branch,dp.cost_center,dp.tower as branch_process,dd.`commit`,dd.direct_cost,dd.indirect_cost 
FROM dashboard_data dd INNER JOIN cost_master dp ON dd.cost_centerId = dp.id
 WHERE dd.branch='$branchName' and   dd.createdate between '$startdate' and  '$endDate' and dp.cost_center = '{$cc['CostCenterMaster']['cost_center']}' ORDER BY dd.createdate");
 $da[]=array('dp'=>array('cost_center'=>$cc['CostCenterMaster']['cost_center'],'cid'=>$cc['CostCenterMaster']['id'],'Cname'=>$cc['CostCenterMaster']['CostCenterName'],'OwnerName'=>$cc['CostCenterMaster']['OwnerName']));
 
// print_r($da); exit;
 foreach($data1 as $dd)
 {
     $data[]=$dd;
    //print_r($dd);
    ///$array[$dd[0]['date']][$dd['dd']['branch']]=$dd['dd']['branch'];
     //$array[$dd[0]['date']][$dd['dp']['branch_process']]=$dd['dp']['branch_process'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['commit']=$dd['dd']['commit'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['direct_cost']=$dd['dd']['direct_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['indirect_cost']=$dd['dd']['indirect_cost'];
     $array[$dd[0]['date']][$dd['dp']['cost_center']]['id']=$dd['dd']['id'];
  
  
     //$array[$dd[0]['date']][$dd['dd']['direct_cost']]=$dd['dd']['direct_cost'];
      }} //$data=array_merge($data,$da);
      //print_r($array);die;
      //$this->set('dash',$da);
        $this->set('branchName',$branchName);
        $this->set('dashddata',$da);
         $this->set('array',$array);
    
    }
    public function get_os()
    {
       $this->layout='ajax'; 
       $cost_center = $this->request->data['cost_center'];
       $data = $this->Targets->query("SELECT cm.id,SUM(grnd) FROM tbl_invoice ti 
        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center WHERE ti.invoiceType='Revenue' and cm.id='$cost_id'"
               . " AND `month`=CONCAT(DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 MONTH),'%b'),'-',DATE_FORMAT(CURDATE(),'%y'))");
       
       echo ($data['0']['0']['grnd']*1.83/100); exit;
       
    }
    
    public function getexpot()
    {
       $this->layout='ajax'; 
    }
    
}

?>