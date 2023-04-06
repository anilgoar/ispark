<?php
class DashboardsController extends AppController 
{
    public $uses=array('DashboardData','DashboardProcess','Addbranch','CostCenterMaster','Targets');
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
            if(in_array('42',$roles)){$this->Auth->allow('index','get_data');$this->Auth->allow('get_process');$this->Auth->allow('get_tower','get_freeze_data');$this->Auth->allow('add_process');$this->Auth->allow('get_dash_data');}
       if(in_array('138',$roles)){ $this->Auth->allow('get_data');	$this->Auth->allow('get_table');$this->Auth->allow('getexpot');}
        }
    }
		
    public function index() 
    {
        $this->layout="home";
        $branchName = $this->Session->read('branch_name');
       
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
        
        
        if($this->request->is('Post'))
        {
            
            
            if(!empty($this->request->data))
            {
                
                //print_r($this->request->data); exit;
                $arrayMonth = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
                $MntArr = explode("-",date("Y-m-d"));
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
                
                $data['FinanceYear'] = $NewFinanceYear;
                $data['FinanceMonth'] = $FinanceMonth;
                $data['EVITA'] = $this->request->data['Dashboard']['Fin_Cost'];
                
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['user_id'] = $this->Session->read('userid');
                foreach($this->request->data['Dashboard'] as $k=>$v)
                {
                    $data[$k] = addslashes($v);
                }
                
                $data['date'] = date_format(date_create($data['date']), 'Y-m-d');
                
                if($data['branch']=='')
                {
                    $data['branch']=$this->Session->read('branch_name');
                    if($data['branch']>1){
                    $data['branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                $select = $this->DashboardData->find('first',array('conditions'=>array('cost_centerId'=>$data['cost_centerId'],'date(DashboardData.createdate)=curdate()')));
                
               // print_r($data['cost_centerId']); die;
                if(!empty($select))
                {
                    $this->Session->setFlash("<font color='green'>Record Already Saved  for today</font>");
                    $this->redirect(array('action'=>'index'));
                }
                elseif($this->DashboardData->save($data))
                {
                    $this->Session->setFlash("<font color='green'>Record Saved Successfully</font>");
                    $this->redirect(array('action'=>'index'));
                }
                else
                {
                    $this->Session->setFlash("<font color='red'>Record Not Saved, Please Try Again!</font>");
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
        $date = date("Y-m-1");  
        $data = $this->Targets->find('first',array("conditions"=>"cost_centerId='$cost_id' and month='$date'"));
        //print_r($data); exit;
        if(!empty($data))
        {
            echo $data['Targets']['target'].'##'.$data['Targets']['target_directCost'].'##'.$data['Targets']['target_IDC'].'##'.$data['Targets']['os'].'##'.$data['Targets']['finance_cost']; exit;
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
        $this->set('dashboarddata',$this->DashboardData->query("SELECT DATE_FORMAT(dd.createdate,'%d-%b-%Y') `date`,dd.branch,dp.branch_process,dd.`commit`,dd.direct_cost,dd.indirect_cost,dd.EVITA 
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
        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center WHERE cm.id='$cost_id'"
               . " AND `month`=CONCAT(DATE_FORMAT(SUBDATE(CURDATE(),INTERVAL 1 MONTH),'%b'),'-',DATE_FORMAT(CURDATE(),'%y'))");
       
       echo ($data['0']['0']['grnd']*1.83/100); exit;
       
    }
    
    public function getexpot()
    {
       $this->layout='ajax'; 
    }
    
}

?>