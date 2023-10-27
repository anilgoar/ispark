<?php
class ConnectivitiesController extends AppController 
{
    public $uses = array('Connectivitie','User','Docfile','Jclr','Addbranch','Mobiledata');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index','view','save_doc','mobile','export1','save_doc1','get_report11');
            //else{$this->Auth->deny('index');}
            $this->Auth->allow('index','view','save_doc','save_doc1','mobile','export1','get_report11');
        }
    }
    
    public function index()
    {
        
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
        
        
        $this->layout = "home";
		
		// print_r($mas); exit;
		    if ($this->request->is('post')) 
			{
                            $user = $this->Session->read('userid');
				//$this->Jclr->create();
				$data=$this->request->data;
                              if($data['Connectivitie']['Branch']=='')
                {
                    $data['Connectivitie']['Branch']=$this->Session->read('branch_name');
                    if($data['Connectivitie']['Branch']>1){
                    $data['Connectivitie']['Branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                                $data['Connectivitie']['BillDuedate']=  date_format( date_create($data['Connectivitie']['BillDuedate']),'Y-m-d');
                                $data['Connectivitie']['Billdate']=  date_format( date_create($data['Connectivitie']['Billdate']),'Y-m-d');
                                $data['Connectivitie']['saveDate']=date('Y-m-d H:i:s');
                                $data['Connectivitie']['userid']=$user;
                               // print_r($data); exit;
                                //print_r($this->Connect->saveall($data));die;
            	if ($this->Connectivitie->saveall($data))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	$this->Session->setFlash(__('The Details has been saved.'));
                	return $this->redirect(array('action' => 'index'));
                  
            	}
            	$this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
			}

    }
     public function view()
    {
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
        
        
        
        $this->layout = "home";
         if ($this->request->is('post')) 
			{
        $result = $this->request->data['Connectivities']; 
         
              if($result['Branch']=='')
                {
                    $result['Branch']=$this->Session->read('branch_name');
                    if($result['Branch']>1){
                    $result['Branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                                  else{
                                 $data1['Branch']=  $data['Connectivitie']['Branch'];
                                      }
        $start_date = $result['Sdate'];
        $end_date = $result['Edate'];
      
             if($result['rtype']=='HardWare'){
               
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `hardware` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y')  and BranchName = '{$result['Branch']}'"
                   );
             }
             
              else if($result['rtype']=='Connectivity'){
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `tbl_connectivity` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') and Branch = '{$result['Branch']}'"
                   );
             }
              else if($result['rtype']=='Mobile Data'){
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `tbl_mobile` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') and Branch = '{$result['Branch']}'"
                   );
             }
               // print_r($data); exit;
             $this->set('type',$result['rtype']); 
           $this->set('Data',$data);
        
                        }
        
    }
    
    public function save_doc()
            
    {	
        
         $branchName = $this->Session->read('branch_name');
       $dataValue = $this->Connectivitie->query("Select * from hardware where BranchName='$branchName'");
            $i=0;
            foreach($dataValue as $value)
            {
                $DataVal[$i]['Owner'] = $value['hardware']['Owner'];
                $DataVal[$i]['working'] = $value['hardware']['working'];
                $DataVal[$i]['Notworking'] = $value['hardware']['Notworking'];
                $DataVal[$i]['Damage'] = $value['hardware']['Damage'];
                $DataVal[$i]['Standby'] = $value['hardware']['Standby'];
                $DataVal[$i]['StandByNet'] = $value['hardware']['StandByNet'];
                $DataVal[$i]['remarks'] = $value['hardware']['Remarks'];
                $i++;
            }
            //print_r($DataVal); exit;
            $this->set('DataVal',$DataVal);
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
        if($this->Session->read('role')!='admin')
        {
             $branchNamenew = $this->Session->read('branch_name');
             $select12 = $this->Connectivitie->query("Select * from hardware where BranchName='{$data['Connectivitie']['branch']}'");
             $this->set('select12',$select12);
        }
        
        $data=array(
0=>'Computer (P4)MAS',
1=>'Computer (P4)Vendor',
2=>'Computer (DC)MAS',
3=>'Computer (DC)Vendor',
4=>'Computer (C2D)MAS',
5=>'Computer (C2D)Vendor',
6=>'Computer (Corei3)MAS',
7=>'Computer (Corei3)Vendor',
8=>'Computer (Corei5)MAS',
9=>'Computer (Corei5)Vendor',
10=>'Laptop MAS',
11=>'Laptop Vendor',
12=>'Server MAS',
13=>'Server Vendor',            
14=>'PRI card MAS',
15=>'PRI card Vendor',
16=>'PRI gateway MAS',
17=>'PRI gateway Vendor',
18=>'GSM gateway MAS',
19=>'GSM gateway Vendor',
20=>'Switch 8 port (Non POE) MAS',
21=>'Switch 8 port (Non POE) Vendor',
22=>'Switch 24 port (POE) MAS',
23=>'Switch 24 port (POE) Vendor',
24=>'Switch 24 port (Non POE) MAS',
25=>'Switch 24 port (Non POE) Vendor',
26=>'Switch 24 port (L3) MAS',
27=>'Switch 24 port (L3) Vendor',
28=>'Switch 48 port (POE) MAS',
29=>'Switch 48 port (POE) Vendor',
30=>'Switch 48 port (Non POE) MAS',
31=>'Switch 48 port (Non POE) Vendor',
32=>'Switch 48 port (L3) MAS',
33=>'Switch 48 port (L3) Vendor',
34=>'Router MAS',
35=>'Router Vendor',
36=>'Avaya MAS',
37=>'Avaya Vendor',
38=>'IP Phone MAS',
39=>'IP Phone Vendor',
40=>'DVR MAS',
41=>'DVR Vendor',
42=>'Camera MAS',
43=>'Camera Vendor',
44=>'VC camera MAS',
45=>'VC camera Vendor',
46=>'Cosec MAS',
47=>'Cosec Vendor',
48=>'Internal HDD MAS',
49=>'Internal HDD Vendor',
50=>'External HDD MAS',
51=>'External HDD Vendor',
52=>'Firewall MAS',
53=>'Firewall Vendor',
54=>'Projector MAS',
55=>'Projector Vendor',
56=>'ADSL router MAS',
57=>'ADSL router Vendor',
58=>'Server Rack MAS',
59=>'Server Rack Vendor',
60=>'Audio Codec MAS',
61=>'Audio Codec Vendor'            

);
    
     $user = $this->Session->read('userid');
    $saveDate = date('Y-m-d H:i:s');
        $this->set('data', $data);
        $this->layout='home';	
       if ($this->request->is('post')) 
			{
                           
				//$this->Jclr->create();
				$data=$this->request->data;
                               // print_r($data['Connectivitie']['branch']);die;
                                if($data['Connectivitie']['branch']=='')
                {
                    $data['Connectivitie']['branch']=$this->Session->read('branch_name');
                    if($data['Connectivitie']['branch']>1){
                    $data['Connectivitie']['branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                
               
                                for($i=0;$i<=61;$i++){
                                    $select1 = $this->Connectivitie->query("Select * from hardware where BranchName='{$data['Connectivitie']['branch']}' and DeviceName='{$data['devicename'.$i]}'");
                                     If(empty($select1)){
                                   // echo "insert into hardware set DeviceName='{$data['devicename'.$i]}',BranchName='{$data['Connectivitie']['branch']}',Owner='{$data['Owner'.$i]}',working='{$data['Working'.$i]}',NotWorking='{$data['Notworking'.$i]}',Damage='{$data['Damage'.$i]}',StandBy='{$data['Standby'.$i]}',StandByNet='{$data['notworking'.$i]}',saveDate='$saveDate',userid='$user'";die;
                                $this->Connectivitie->query("insert into hardware set DeviceName='{$data['devicename'.$i]}',BranchName='{$data['Connectivitie']['branch']}',Owner='{$data['Owner'.$i]}',working='{$data['Working'.$i]}',NotWorking='{$data['Notworking'.$i]}',Damage='{$data['Damage'.$i]}',StandBy='{$data['Standby'.$i]}',StandByNet='{$data['StandByNet'.$i]}',Remarks='{$data['remarks'.$i]}',saveDate='$saveDate',userid='$user'");
                                         }
                                         else {
     
     $this->Connectivitie->query("update hardware set DeviceName='{$data['devicename'.$i]}',BranchName='{$data['Connectivitie']['branch']}',Owner='{$data['Owner'.$i]}',working='{$data['Working'.$i]}',NotWorking='{$data['Notworking'.$i]}',Damage='{$data['Damage'.$i]}',StandBy='{$data['Standby'.$i]}',StandByNet='{$data['StandByNet'.$i]}',Remarks='{$data['remarks'.$i]}',saveDate='$saveDate' , userid='$user' where BranchName='{$data['Connectivitie']['branch']}' and DeviceName='{$data['devicename'.$i]}'");

    
     
     }
                
                                }
 
                              $select = $this->Connectivitie->query("Select * from hardware where userid='$user' and SaveDate='$saveDate'");
       if($select)
	   {
	      $this->Session->setFlash("<font color='green'>Data Save Successfully.</font>");
              return $this->redirect(array('action' => 'save_doc'));
	   }
	   else
	   {
	   	$this->Session->setFlash("<font color='red'>Data Not Save</font>");
	   } 
                        }
        	
    }

  public function mobile()
    {
        
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
        
        
        $this->layout = "home";
		
		// print_r($mas); exit;
		    if ($this->request->is('post')) 
			{
                            $user = $this->Session->read('userid');
				//$this->Jclr->create();
				$data=$this->request->data;
                              $data1[]='';
                                $data1['BillDuedate']=  date_format( date_create($data['Connectivitie']['BillDuedate']),'Y-m-d');
                                $data1['Billdate']=  date_format( date_create($data['Connectivitie']['Billdate']),'Y-m-d');
                                $data1['saveDate']=date('Y-m-d H:i:s');
                                $data1['userid']=$user;
                                
                                if($data['Connectivitie']['Branch']=='')
                {
                    $data1['Branch']=$this->Session->read('branch_name');
                    if($data1['Branch']>1){
                    $data1['Branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                                  else{
                                 $data1['Branch']=  $data['Connectivitie']['Branch'];
                                      }
                                $data1['ConnectivityType']=  $data['Connectivitie']['ConnectivityType'];
                                $data1['Cunsumercode']=$data['Connectivitie']['Cunsumercode'];
                                $data1['RelationshipNo']=$data['Connectivitie']['RelationshipNo'];
                                
                                 $data1['TariffPlan']=  $data['Connectivitie']['TariffPlan'];
                                $data1['BillingAddress']=  $data['Connectivitie']['BillingAddress'];
                                $data1['BillingPeriod']=$data['Connectivitie']['BillingPeriod'];
                                $data1['BillingType']=$data['Connectivitie']['BillingType'];
                                
                                 $data1['Bandwidth']=  $data['Connectivitie']['Bandwidth'];
                                $data1['PlanName']=  $data['Connectivitie']['PlanName'];
                                $data1['securitydeposit']=$data['Connectivitie']['securitydeposit'];
                                $data1['ContactPerson']=$data['Connectivitie']['ContactPerson'];
                                $data1['MobileNo']=  $data['Connectivitie']['MobileNo'];
                                $data1['Username']=  $data['Connectivitie']['Username'];
                                $data1['Ownership']=$data['Connectivitie']['Ownership'];
                                $data1['Rembursment']=$data['Connectivitie']['Rembursment'];
                                $data1['ActivePlan']=$data['Connectivitie']['ActivePlan'];
                                $data1['ApprovedAmount']=$data['Connectivitie']['ApprovedAmount'];
                              //  print_r($data1); exit;
                                //print_r($this->Connect->saveall($data));die;
            	if ($this->Mobiledata->saveall($data1))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	$this->Session->setFlash(__('The Details has been saved.'));
                	return $this->redirect(array('action' => 'mobile'));
                  
            	}
            	$this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
			}

    } 
   
    
    public function export1()
    {
         $branchName = $this->Session->read('branch_name');
       
        if($this->Session->read('role')=='admin')
        {
           // $branch1= array('All'=>'All');
            $branch2 =$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name')));
            
          
            $this->set('branchName',$branch2);
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
        
        
        
        $this->layout = "home";

               
    } 
    
     public function get_report11(){
        $this->layout = "ajax";
        
        $result1 = $this->request->data;
      $result = $this->request->data['Connectivities']; 
      
        //print_r($result1['Branch']);die;
              if($result1['Branch']=='')
                {
                  
                    $result1['Branch']=$this->Session->read('branch_name');
                     $selectedBranch = "and BranchName in ('{$result1['Branch']}')";
                   // print_r($result1['Branch']);die;
                    if($result['Branch']>1){
                    $result['Branch'] = implode(',',$this->Session->read('branch_name'));
                    }
                    
                }
                      
                
                 else{
                     
                      $bc1= "('".implode("','", $result1['Branch'])."')";
                     if($result['rtype']=='HardWare'){
                 $selectedBranch = "and BranchName in $bc1"; 
                     }
                      else if($result['rtype']=='Connectivity'){
                           $selectedBranch = "and Branch in $bc1";
                      }
                       else if($result['rtype']=='Mobile Data'){
                           $selectedBranch = "and Branch in $bc1";
                       }
                 }
                 
                                      
        $start_date = $result['Sdate'];
        $end_date = $result['Edate'];
             if($result['rtype']=='HardWare'){
               
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `hardware` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y')  $selectedBranch"
                   );
             }
             
              else if($result['rtype']=='Connectivity'){
                  
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `tbl_connectivity` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') $selectedBranch"
                   );
             }
              else if($result['rtype']=='Mobile Data'){
           $data = $this->Mobiledata->query("SELECT *,date_format(`saveDate`,'%d/%m/%Y')SaveDataDate FROM `tbl_mobile` WHERE DATE(`saveDate`) BETWEEN str_to_date('$start_date','%d-%m-%Y') AND str_to_date('$end_date','%d-%m-%Y') $selectedBranch"
                   );
             }
               // print_r($data); exit;
             $this->set('type',$result['rtype']); 
           $this->set('Data',$data);
               
    } 
    
    
     public function save_doc1()      
    {	
        
        $data=array(
0=>'Computer (P4)MAS',
1=>'Computer (P4)Vendor',
2=>'Computer (DC)MAS',
3=>'Computer (DC)Vendor',
4=>'Computer (C2D)MAS',
5=>'Computer (C2D)Vendor',
6=>'Computer (Corei3)MAS',
7=>'Computer (Corei3)Vendor',
8=>'Computer (Corei5)MAS',
9=>'Computer (Corei5)Vendor',
10=>'Laptop MAS',
11=>'Laptop Vendor',
12=>'Server MAS',
13=>'Server Vendor',            
14=>'PRI card MAS',
15=>'PRI card Vendor',
16=>'PRI gateway MAS',
17=>'PRI gateway Vendor',
18=>'GSM gateway MAS',
19=>'GSM gateway Vendor',
20=>'Switch 8 port (Non POE) MAS',
21=>'Switch 8 port (Non POE) Vendor',
22=>'Switch 24 port (POE) MAS',
23=>'Switch 24 port (POE) Vendor',
24=>'Switch 24 port (Non POE) MAS',
25=>'Switch 24 port (Non POE) Vendor',
26=>'Switch 24 port (L3) MAS',
27=>'Switch 24 port (L3) Vendor',
28=>'Switch 48 port (POE) MAS',
29=>'Switch 48 port (POE) Vendor',
30=>'Switch 48 port (Non POE) MAS',
31=>'Switch 48 port (Non POE) Vendor',
32=>'Switch 48 port (L3) MAS',
33=>'Switch 48 port (L3) Vendor',
34=>'Router MAS',
35=>'Router Vendor',
36=>'Avaya MAS',
37=>'Avaya Vendor',
38=>'IP Phone MAS',
39=>'IP Phone Vendor',
40=>'DVR MAS',
41=>'DVR Vendor',
42=>'Camera MAS',
43=>'Camera Vendor',
44=>'VC camera MAS',
45=>'VC camera Vendor',
46=>'Cosec MAS',
47=>'Cosec Vendor',
48=>'Internal HDD MAS',
49=>'Internal HDD Vendor',
50=>'External HDD MAS',
51=>'External HDD Vendor',
52=>'Firewall MAS',
53=>'Firewall Vendor',
54=>'Projector MAS',
55=>'Projector Vendor',
56=>'ADSL router MAS',
57=>'ADSL router Vendor',
58=>'Server Rack MAS',
59=>'Server Rack Vendor',
            60=>'Audio Codec MAS',
61=>'Audio Codec Vendor'  
);
    
     $user = $this->Session->read('userid');
    $saveDate = date('Y-m-d H:i:s');
        $this->set('data', $data);
        $this->layout='ajax';	
       $branch = $this->params->query['branch_name'];
                               // print_r($branch);die;
        $dataValue = $this->Connectivitie->query("Select * from hardware where BranchName='$branchName'");                     
        $i=0;
            foreach($dataValue as $value)
            {
                $DataVal[$i]['Owner'] = $value['hardware']['Owner'];
                $DataVal[$i]['working'] = $value['hardware']['working'];
                $DataVal[$i]['Notworking'] = $value['hardware']['Notworking'];
                $DataVal[$i]['Damage'] = $value['hardware']['Damage'];
                $DataVal[$i]['Standby'] = $value['hardware']['Standby'];
                $DataVal[$i]['StandByNet'] = $value['hardware']['StandByNet'];
                $DataVal[$i]['remarks'] = $value['hardware']['Remarks'];
                $i++;
            }
            //print_r($DataVal); exit;
            $this->set('DataVal',$DataVal);       
                               
                                    $select1 = $this->Connectivitie->query("Select * from hardware where BranchName='{$branch}'");
                                     
    //print_r($select1);die;
     
      $this->set('select1',$select1);
                
                              
                             
      
                      
        	
    }
    public function view_details()
    {
        $this->layout="home";
        
    }
    
}

?>