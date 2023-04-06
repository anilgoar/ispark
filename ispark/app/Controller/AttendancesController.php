<?php
class AttendancesController extends AppController 
{
    public $uses = array('Jclr','User','Attendance','IncentivesManager','Salary','SaveFile','Leave');
        
    
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

            $this->Auth->allow('index','get_report11','export1','save_status','exportsalary','exportleave','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess','Savefile','showfile','discardsalary','discardincentive');
            //else{$this->Auth->deny('index','get_report11','export1','save_status','exportsalary','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','salaryprocess');}
        }
    }
    
    public function index(){
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST')){
            $user = $this->Session->read('userid');
            $FileTye = $this->request->data['upload']['file']['type'];
            $info = explode(".",$this->request->data['upload']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
		$FilePath = $this->request->data['upload']['file']['tmp_name'];
                $files = fopen($FilePath, "r");

                $dataArr = array();
                $flag = false; $Leave = array();
                while($row = fgetcsv($files,5000,",")){
                    if($flag){
                        $Leave['EmpCode'] = $data['EmpCode'] = $row[0];
                        $data['Present'] = $row[1];
                        $data['WO'] = $row[2];
                        $data['Holiday'] = $row[3];
                        $data['HalfDay'] = $row[4];
                        $data['Compoff'] = $row[5];
                        $Leave['PL'] = $data['EL'] = $row[6];
                        $Leave['CL'] = $data['CL'] = $row[7];
                        $Leave['SL'] = $data['SL'] = $row[8];
                        $data['ArrerDays'] = $row[9];
                        $data['OT'] = $row[10];
                        $Leave['LeaveMonth'] = $data['SalMonth'] = $this->request->data['upload']['month'];
                        $Leave['LeaveYear'] =  $data['SalYear'] = $this->request->data['upload']['finance_year'];
                        $Leave['LeaveStatus'] =  'DR';
                        $data['CreateDate']=date('Y-m-d H:i:s');
                        // print_r($data['month']);
                        $dataArr[] = $data;
                        $LeavArr[] = $Leave;

                    }
                    
                    else {$flag = true;}
                }
                
                $this->Attendance->saveMany($dataArr);
                //$leavedata= $this->Leave->saveMany($LeavArr);
                                    
                $finance_year = $Leave['LeaveYear'];
                $month = $Leave['LeaveMonth'];
                $monthName = date("m", strtotime($finance_year.'-'.$month));
                
                if($monthName >=1 && $monthName < 12){
                    $nextmonth = $monthName+1;
                }
                elseIF($monthName==12){
                   $nextmonth=1; 
                }
                else {
                   $nextmonth =12; 
                }
                
                $now = new \DateTime('now');
                $crrmonth = $now->format('m');
                $nextmonthName = date("M", strtotime($finance_year.'-'.$nextmonth));
                
                // echo $nextmonth ; exit;
                // if($crrmonth<=$nextmonth){
                
                $data2 = $this->Leave->query("SELECT EmpCode FROM qual_employee t1 ");
		   
           
                // print_r($data2);die;
		

		foreach($data2 as $d){
                    //  echo "SELECT EmpCode,SUM(PL) pl,SUM(CL) cl,SUM(SL) sl FROM qual_leave t2 WHERE EmpCode='".$d['t1']['EmpCode']."' and LeaveStatus='CR' and LeaveMonth = '$nextmonthName' and LeaveYear ='$finance_year'";die;		
                    $d2 = $this->Leave->query("SELECT EmpCode,SUM(PL) pl,SUM(CL) cl,SUM(SL) sl FROM qual_leave t1 WHERE EmpCode='".$d['t1']['EmpCode']."' and LeaveStatus='CR' and LeaveMonth = '$month' and LeaveYear ='$finance_year'  ");
                    $doj =  $this->Jclr->query("select * from qual_employee where EmpCode = '{$d['t1']['EmpCode']}' and Month(DOFJ) <= '$monthName' ");
                    $StatusEmp =  $this->Jclr->query("select Status, if(Status = '1','',DATE_FORMAT(Resignation,'%c')) ResignationMonth,IF(Status = '1','',DATE_FORMAT(Resignation,'%Y')) ResignationYear from qual_employee s1 where EmpCode = '{$d['t1']['EmpCode']}'");
                      
                      
                    if(($StatusEmp[0]['s1']['Status'] =='1' || ($StatusEmp[0]['0']['ResignationMonth'] > $monthName && $StatusEmp[0]['0']['ResignationYear'] == $finance_year )) ){
		   	$data1 = $this->Leave->query("SELECT EmpCode,SUM(PL) pl,SUM(CL) cl,SUM(SL) sl FROM qual_leave t2 WHERE EmpCode='".$d['t1']['EmpCode']."' and LeaveStatus='DR' and LeaveMonth = '$month' and LeaveYear ='$finance_year'");
                        $checkdata = $this->Leave->query("SELECT EmpCode,PL pl,CL cl,SL sl FROM qual_leave t2 WHERE EmpCode='".$d['t1']['EmpCode']."' and LeaveStatus='CR' and LeaveMonth = '$nextmonthName' and LeaveYear ='$finance_year'");
                        
                        if($nextmonthName=='Jan'){
                             $d2[0]['0']['cl']=0;
                             $d2[0]['0']['sl']=0;
                        }
                         
                        $emp= $d['t1']['EmpCode'];
                        $incsl=round(7/12,3);
                        $inccl=round(7/12,3);
                        $incpl=round(16/12,3);
			$pl= (($d2[0]['0']['pl']-$data1[0]['0']['pl'])+$incpl);
			$cl=  (($d2[0]['0']['cl']-$data1[0]['0']['cl'])+$inccl);
			$sl= (($d2[0]['0']['sl']-$data1[0]['0']['sl'])+$incsl);
                        
			if(empty($checkdata)){
                          //  Echo "insert into qual_leave set EmpCode= '$emp',PL='$pl',CL='$cl',SL='$sl',LeaveStatus='CR',LeaveMonth = '$nextmonthName' and LeaveYear ='$finance_year'";die;
                            $this->Leave->query("insert into qual_leave set EmpCode= '$emp',PL='$pl',CL='$cl',SL='$sl',LeaveStatus='CR',LeaveMonth = '$nextmonthName' , LeaveYear ='$finance_year'");
                        }
                        else{
                            $this->Leave->query("Update qual_leave set PL='$pl',CL='$cl',SL='$sl' where EmpCode= '$emp' and LeaveStatus='CR' and LeaveMonth = '$nextmonthName' and LeaveYear ='$finance_year'");
                        }	   
		   }
		}
                
                $this->Session->setFlash('Data Imported Successfully');
            }
            else{
                $this->Session->setFlash('File Format not Valid');
            }
        }
    }
    public function incentive(){
      $this->layout = "home";  
       $data1 = $this->Jclr->find('list',array('fields'=>array('EmpCode','EmpCode'),'group'=>array('EmpCode'),'order' =>'EmapName'));
        //$this->set('Data1',$data1);
         if ($this->request->is('post')) 
			{
				//$this->Jclr->create();
			
            $data=	$this->request->data;
            $dt['EmpCode']=$data['Attendances']['EmpCode'];
             $dt['Salyear']=$data['Attendances']['Salyear'];
              $dt['salmonth']=$data['Attendances']['salmonth'];
               $dt['incamt']=$data['Attendances']['incamt'];
               $dt['Remarks']=$data['Attendances']['Remarks'];
               $dt['userid']=$this->Session->read('userid');
               $dt['Importdate']=date('Y-m-d H:i:s');
            //print_r($dt);die;
            $data12 = $this->IncentivesManager->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('EmpCode'=>$dt['EmpCode'],'Salyear'=>$dt['Salyear'],'salmonth'=>$dt['salmonth'])));
            if(!empty($data12)){
                $this->Session->setFlash(__('Incentive Allready Saved for '.$dt['EmpCode'].' for  '.$dt['Salyear'].'-'.$dt['salmonth']));
            }
            elseif($dt['EmpCode']==$data1['Jclr']['EmpCode']){
            if( $this->IncentivesManager->saveAll($dt))
				{
                	$this->Session->setFlash(__('The data has been saved'));
                	return $this->redirect(array('action' => 'incentive'));
            	}
            	$this->Session->setFlash(__('The Data could not be saved. Please, try again.'));
            }
            else
            {
                $this->Session->setFlash(__('The EmpCode is not correct'));
            }
            
                                }
    }

    public function get_report11(){
       
               
    } 
    public function exportsalary() {
	$this->layout = "home";
        
      if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         //print_r($result); exit;
          
        $finance_year = $result['finance_year'];
        $month = $result['month'];
        //$monthNum = sprintf("%02s", $month);
$monthName = date("m", strtotime($finance_year.'-'.$month));
if($monthName >1 && $monthName <=12){
$premonth = $monthName-1;
}
else {
   $premonth =12; 
}
$d=cal_days_in_month(CAL_GREGORIAN,$monthName,$finance_year);
$pred=cal_days_in_month(CAL_GREGORIAN,$premonth,$finance_year);
//print_r($pred); die;
        date_format($date,"Y/m/d H:i:s");
           //echo ; exit;
           $data = $this->Attendance->query("select QualEmpCode,EmpCode,EmapName,FatherName,Dept,Desg,DOJ,Basic,HRA,`Conv`,OthAllw,Gross,Paiddays,OTDays,EBasic,EHRA,EConv,EOthAllw,ArrLM,Conv1,Incentive,overtime,TotalGross,PF,TDS,AdvDed,ESI,Netpay,EmplrPF,EmplrESI,EmplrIns,CTC,ESINo,UAN,PFNo,ESIS,PFS,PL,SL,CL,SalMonth,SalYear,SaveDate,UserId from qual_salary t1 where t1.SalYear = '$finance_year' and t1.SalMonth = '$month' ");
           
           			$fileName = "Manpower_salary";
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

		   echo "<table border=\"1\">";
		   echo "<tr>";
		   		echo "<td>QualEmpCode</td>";
		   		echo "<td>EmpCode</td>";
				echo "<td>EmapName</td>";
				echo "<td>FatherName</td>";
				echo "<td>Dept</td>";
				echo "<td>Desg</td>";
				echo "<td>DOJ</td>";
				echo "<td>Basic</td>";
				echo "<td>HRA</td>";
				echo "<td>Conv</td>";
				echo "<td>OthAllw</td>";
				echo "<td>Gross</td>";
				echo "<td>Paiddays</td>";
				echo "<td>OTDays</td>";
				echo "<td>E Basic</td>";
				echo "<td>E HRA</td>";
				echo "<td>E Conv</td>";
				echo "<td>E OthAllw</td>";
				echo "<td>Arr LM</td>";
				echo "<td>Incentive</td>";
				echo "<td>OT Incentive</td>";
				echo "<td>Total Gross</td>";
				echo "<td>PF</td>";
				echo "<td>TDS</td>";
				echo "<td>Adv/Ded</td>";
				echo "<td>ESI</td>";
				echo "<td>Net pay</td>";
				echo "<td>Emplr PF</td>";
				echo "<td>Emplr ESI</td>";
				echo "<td>Emplr Ins</td>";
				echo "<td>CTC</td>";
				echo "<td>ESI No</td>";
				echo "<td>UAN</td>";
				echo "<td>PF No</td>";
				echo "<td>ESI</td>";
				echo "<td>PF</td>";
			echo "</tr>";	
			

		   foreach($data as $d)
		   {
		   	
			  echo "<tr>";	
		      echo "<td>".$d['t1']['QualEmpCode']."</td>";
			  echo "<td>".$d['t1']['EmpCode']."</td>";
			  echo "<td>".$d['t1']['EmapName']."</td>";
			  echo "<td>".$d['t1']['FatherName']."</td>";
			  echo "<td>".$d['t1']['Dept']."</td>";
			  echo "<td>".$d['t1']['Desg']."</td>";
			  echo "<td>".$d['t1']['DOJ']."</td>";
			  echo "<td>".$d['t1']['Basic']."</td>";
			  echo "<td>".$d['t1']['HRA']."</td>";
			  echo "<td>".$d['t1']['Conv']."</td>";
			  echo "<td>".$d['t1']['OthAllw']."</td>";
			  echo "<td>".$d['t1']['Gross']."</td>";
			  echo "<td>".$d['t1']['Paiddays']."</td>";
			  echo "<td>".$d['t1']['OTDays']."</td>";
			  echo "<td>".$d['t1']['EBasic']."</td>";
			  echo "<td>".$d['t1']['EHRA']."</td>";
			  echo "<td>".$d['t1']['EConv']."</td>";
			  echo "<td>".$d['t1']['EOthAllw']."</td>";
			  echo "<td>".$d['t1']['ArrLM']."</td>";
			  echo "<td>".$d['t1']['Incentive']."</td>";
			  echo "<td>".$d['t1']['overtime']."</td>";
			  echo "<td>".$d['t1']['TotalGross']."</td>";
			  echo "<td>".$d['t1']['PF']."</td>";
			  echo "<td>".$d['t1']['TDS']."</td>";
			  echo "<td>".$d['t1']['AdvDed']."</td>";
			  echo "<td>".$d['t1']['ESI']."</td>";
			  echo "<td>".$d['t1']['Netpay']."</td>";
			  echo "<td>".$d['t1']['EmplrPF']."</td>";
			  echo "<td>".$d['t1']['EmplrESI']."</td>";
			  echo "<td>".$d['t1']['EmplrIns']."</td>";
			  echo "<td>".$d['t1']['CTC']."</td>";
			  echo "<td>".$d['t1']['ESINo']."</td>";
			  echo "<td>".$d['t1']['UAN']."</td>";
			  echo "<td>".$d['t1']['PFNo']."</td>";
			  echo "<td>".$d['t1']['ESIS']."</td>";
			  echo "<td>".$d['t1']['PFS']."</td>";
			  echo "</tr>"; 
		   }
		  //t1.,t1.,t1.PFS
		   echo "</table>";

		   exit;
		   }

	}
	
        
        
        public function exportincentive() {
	$this->layout = "home";
        
      if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         //print_r($result); exit;
          
        $finance_year = $result['finance_year'];
        $month = $result['month'];
        //$monthNum = sprintf("%02s", $month);

        date_format($date,"Y/m/d H:i:s");
             
           $data = $this->Attendance->query("select t1.*,t2.EmapName from qual_incentive t1 left join qual_employee t2 on t1.EmpCode = t2.EmpCode  where Salyear = '$finance_year' and salmonth = '$month' ");
           
           			$fileName = "Manpower_Incentive";
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

		   echo "<table border=\"1\">";
		   echo "<tr>";
		   		echo "<td>EmpCode</td>";
				echo "<td>EmapName</td>";
				echo "<td>year</td>";
				echo "<td>Month</td>";
				echo "<td>Incentive</td>";
                                echo "<td>Remarks</td>";
				
			echo "</tr>";	
			

		   foreach($data as $d)
		   {
		   	  echo "<tr>";	
		      echo "<td>".$d['t1']['EmpCode']."</td>";
			  echo "<td>".$d['t2']['EmapName']."</td>";
			  echo "<td>".$d['t1']['Salyear']."</td>";
			  echo "<td>".$d['t1']['salmonth']."</td>";
			  echo "<td>".$d['t1']['incamt']."</td>";
                           echo "<td>".$d['t1']['Remarks']."</td>";
			 
			 
			  echo "</tr>"; 
		   }
		  //t1.,t1.,t1.PFS
		   echo "</table>";

		   exit;
		   }

	}
        
        
public function typeformat(){
      $this->layout = "home"; 
}
         public function get_status_data()
    {
         $this->layout = "ajax";
        if($this->request->is('POST'))
        {
         $data=  $this->request->data;
        //print_r($data['fortypes']);die;
         if($data['fortypes'] == 'Mannual'){
             
        return $this->redirect(array('controller'=>'Attendances','action' => 'incentive'));
    
         }
 else {
     ?>
<input type="text" name="status[]" required="" value="" class="form-control" >
<?php
 }
        }die;
    }
        
        
   public function importformat()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            $data=	$this->request->data;
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
                        
                        
                        $dt['EmpCode']=$row[0];
             $dt['Salyear']=$data['upload']['Salyear'];
              $dt['salmonth']=$data['upload']['salmonth'];
               $dt['incamt']=$row[1];
               $dt['Remarks']=$row[2];
               $dt['userid']=$this->Session->read('userid');
                        $dt['Importdate']=date('Y-m-d H:i:s');
                        
                    // print_r($data['month']);
                   $dataArr[]=$dt;
                    }
                    
                    else {$flag = true;}
                }
                
                
                
              if($this->IncentivesManager->saveAll($dataArr))
              {
              
              
              
                $this->Session->setFlash('Data Imported Successfully');
              }
               else{
            $this->Session->setFlash('Data Not Imported');
            }
              
            }
             else{
            $this->Session->setFlash('File Format not Valid');
            }
           
            
    }
    }
	
    public function salaryslip() {
	$this->layout = "home";
        $data1 = $this->Jclr->find('list',array('fields'=>array('Dept','Dept'),'group'=>array('Dept'),'order' =>'Dept'));
        $data1 = (array('All'=>'All')+$data1);
        $this->set('Data1',$data1);
	if($this->request->is('POST'))
        {
		//print_r($this->request->data); exit;
            $dept=$this->request->data['upload']['Dept'];
            
            $year =$this->request->data['upload']['finance_year'];
            $month = $this->request->data['upload']['month'];
		$arr = array('SalYear'=>$year,'SalMonth'=>$month);
                if($dept!='All')
                {
                    $arr['Dept'] = $dept;
                }
		$this->set('data',$this->Salary->find('all',array('all','conditions'=>$arr)));
                $this->set('finance_year',$year);
		$this->set('month',$month);
		
		}
	}	
          
    public function salaryprocess() {
	$this->layout = "home";
	if($this->request->is('POST'))
        {
		if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         //print_r($result); exit;
          
        $finance_year = $result['finance_year'];
        $month = $result['month'];
		$saveDate = date('Y-m-d H:i:s');
		$UserId = $this->Session->read('userid');
        //$monthNum = sprintf("%02s", $month);
$monthName = date("m", strtotime($finance_year.'-'.$month));
if($monthName >1 && $monthName <=12){
$premonth = $monthName-1;
}
else {
   $premonth =12; 
}
$d=cal_days_in_month(CAL_GREGORIAN,$monthName,$finance_year);
$pred=cal_days_in_month(CAL_GREGORIAN,$premonth,$finance_year);
//print_r($pred); die;
        date_format($date,"Y/m/d H:i:s");
           $data = $this->Attendance->query("INSERT INTO qual_salary (QualEmpCode,EmpCode,EmapName,FatherName,Dept,Desg,DOJ,Basic,HRA,`Conv`,OthAllw,Gross,Paiddays,OTDays,EBasic,EHRA,EConv,EOthAllw,ArrLM,Conv1,Incentive,overtime,TotalGross,PF,TDS,AdvDed,ESI,Netpay,EmplrPF,EmplrESI,EmplrIns,CTC,ESINo,UAN,PFNo,ESIS,PFS,PL,SL,CL,SalMonth,SalYear,SaveDate,Acno,Bank,UserId) SELECT t1.QualEmpCode,t1.EmpCode,t1.EmapName,t1.FatherName,t1.Dept,t1.Desg,t1.DOFJ,t1.Basic,t1.HRA,t1.Conv,t1.OthAllw,t1.Gross,
(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL) Paiddays,t2.OT
,ROUND(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))  `EBasic`,
CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL)) `EHRA`,
CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL)) `EConv`
,CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL)) `EOthAllw`,
CEIL(t1.Gross/$pred*t2.ArrerDays) `ArrLM`,
'0' `Conv`,IFNULL(t3.incamt,0) `Incentive`,
CEIL(t1.Gross/$d*t2.OT) overtime,
(ROUND(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT)) TGross,
ROUND(ROUND(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))/100*12) PF,
'0' TDS,'0' `AdvDed`,
IF(t1.ESIS='Yes',CEIL((CEIL(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))/100*0.75),'0') ESI,
((Round(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))-ROUND(ROUND(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))/100*12)-IF(t1.ESIS='Yes',CEIL((CEIL(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))/100*0.75),'0')) NetPay,
Round(Round(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))/100*13) EPF,
IF(t1.ESIS='Yes',CEIL((CEIL(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))/100*3.25),'0') EESI,
IF(t1.Dept='VODAFONE PNG','335','120') EmplrIns,
((ROUND(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))+Round(Round(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))/100*13)+IF(t1.ESIS='Yes',CEIL((CEIL(t1.Basic/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.HRA/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Conv/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.OthAllw/$d*(t2.Present+t2.WO+t2.Holiday+t2.HalfDay+t2.Compoff+t2.EL+t2.CL+t2.SL))+CEIL(t1.Gross/$pred*t2.ArrerDays)+0+IFNULL(t3.incamt,0)+CEIL(t1.Gross/$d*t2.OT))/100*3.25),'0')+IF(t1.Dept='VODAFONE PNG','335','120')) CTC
,t1.ESINo,t1.UAN,t1.PFNo,t1.ESIS,t1.PFS,t2.EL,t2.SL,t2.CL,'$month','$finance_year','$saveDate',t1.AcNo,t1.Bank,'$UserId'
FROM qual_employee t1 LEFT JOIN qual_attendance t2 ON t1.EmpCode=t2.EmpCode left join qual_incentive t3 on t1.EmpCode=t3.EmpCode and t3.SalYear='$finance_year' and t3.salmonth='$month' where t2.SalYear = '$finance_year' and t2.SalMonth = '$month'");

$select = $this->Attendance->query("Select * from qual_salary where UserId='$UserId' and SaveDate='$saveDate'");
       if($select)
	   {
	      $this->Session->setFlash("<font color='green'>Salary Process For $month of $finance_year has been done</font>"); 
	   }
	   else
	   {
	   	$this->Session->setFlash("<font color='red'>Salary Process For $month of $finance_year could not done. Please Try Again!</font>");
	   }    
	}	
		}
	}	
	

        
        
        public function savefile()
    {
            
        $this->layout = "home";
         if($this->request->is('POST'))
        {
        $user = $this->Session->read('userid');
           // print_r($this->request->data);die;
             $year = $this->request->data['upload']['file_year'];
              $month = $this->request->data['upload']['month'];
              $FileTye = $this->request->data['upload']['file']['type'];
            $info = explode(".",$this->request->data['upload']['file']['name']);
            $filename=$this->request->data['upload']['file']['name'];
            //echo $filename; die;
          $check =  $this->SaveFile->find('list',array('fields'=>array('FileName','FileName'),'conditions'=>array('FileName'=>$filename)));
        // print_r($check);die;
          if(empty($check))
          {
            $target_dir = "uploads_File/";
$target_file = $target_dir . basename($this->request->data['upload']['file']['name']);
          
                $FilePath = $this->request->data['upload']['file']['tmp_name'];
                 if (move_uploaded_file($FilePath, $target_file)) {
                     $this->SaveFile->query("insert into qual_savefile set FileName = '$filename',userid ='$user',fileyear='$year',filemonth='$month',savedate = now()");
                     $this->Session->setFlash("The file ". $this->request->data['upload']['file']['name']. " has been uploaded.");
                        
    } else {
         $this->Session->setFlash("Sorry, there was an error uploading your file.");
       // echo "Sorry, there was an error uploading your file.";
    }
            
        } 
 else {
     $this->Session->setFlash("Sorry, This FIle Allready Exiest.");
 }
    }
        
     
    }
      public function showfile() {
	$this->layout = "home";
       
	if($this->request->is('POST'))
        {
		//print_r($this->request->data); exit;
            $dept=$this->request->data['upload']['Dept'];
            
            $Year =$this->request->data['upload']['file_year'];
            $month = $this->request->data['upload']['month'];
            
		$conditions = array('filemonth' =>$month,'fileyear'=>$Year);
               
		$this->set('data',$this->SaveFile->find('all',array('all','conditions'=>$conditions)));
                $this->set('finance_year',$year);
		$this->set('month',$month);
		
		}
	}	    
        
     public function exportleave() {
	$this->layout = "home";
          $date2 = date('Y-m-d H:i:s');
$monthName1=	date("m", strtotime($date2));
for($i=1;$i<=$monthName1;$i++)
{
    $pdate = date('Y-'.$i.'-d ');
$mp=  date("M",strtotime($pdate));
$datam[$mp]=$mp;
}
$this->set('month',$datam);
      if($this->request->is("post"))  
      {
	  	$result = $this->request->data['upload']; 
         //print_r($result); exit;
          
        $finance_year = $result['finance_year'];
        $month = $result['month'];
           
         $monthName = date("m", strtotime($finance_year.'-'.$month));
         //echo $monthName; exit;
           $data = $this->Leave->query("SELECT t1.EmpCode,t2.QualEmpCode,t2.EmapName,t2.DOFJ,t2.Dept,'".$month."' as LeaveMonth,t1.LeaveYear,SUM(PL) pl,SUM(CL) cl,SUM(SL) sl FROM qual_leave t1 inner join qual_employee t2 on t1.EmpCode=t2.EmpCode WHERE LeaveStatus='CR' and `LeaveMonth` ='$month' and `LeaveYear` = '$finance_year' and  if(Status = '1','1=1',DATE_FORMAT(Resignation,'%c')='$monthName') and IF(STATUS = '1','1=1',DATE_FORMAT(Resignation,'%Y')='$finance_year')   GROUP BY EmpCode");
		//print_r($data);die;
              
           if(empty($data) && $monthName1==$monthName)
           {
               
               if($monthName >1 && $monthName <=12){
$premonth = $monthName-1;
}
else {
   $premonth =12; 
}
$pdatea = date('Y-'.$premonth.'-d ');
$mpa=  date("M",strtotime($pdateaa));
$incsl=round(7/12,3);
                         $inccl=round(7/12,3);
                          $incpl=round(16/12,3);
                   //echo "SELECT t1.EmpCode,t2.QualEmpCode,t2.EmapName,t2.DOFJ,'".$month."' as LeaveMonth,t1.LeaveYear,SUM(PL+$incpl) pl,SUM(CL+$inccl) cl,SUM(SL+$incsl) sl FROM qual_leave t1 inner join qual_employee t2 on t1.EmpCode=t2.EmpCode WHERE LeaveStatus='CR' and `LeaveMonth` ='$mpa' and `LeaveYear` = '$finance_year' and  if(Status = '1','1=1',DATE_FORMAT(Resignation,'%c')='$monthName') and IF(STATUS = '1','1=1',DATE_FORMAT(Resignation,'%Y')='$finance_year')   GROUP BY EmpCode";die;      
           $data = $this->Leave->query("SELECT t1.EmpCode,t2.QualEmpCode,t2.EmapName,t2.DOFJ,t2.Dept,'".$month."' as LeaveMonth,t1.LeaveYear,SUM(PL+$incpl) pl,SUM(CL+$inccl) cl,SUM(SL+$incsl) sl FROM qual_leave t1 inner join qual_employee t2 on t1.EmpCode=t2.EmpCode WHERE LeaveStatus='CR' and `LeaveMonth` ='$mpa' and `LeaveYear` = '$finance_year' and  if(Status = '1','1=1',DATE_FORMAT(Resignation,'%c')='$monthName') and IF(STATUS = '1','1=1',DATE_FORMAT(Resignation,'%Y')='$finance_year')   GROUP BY EmpCode");    
           
          // print_r($data);die;
           
                          
}
           			$fileName = "ManPower_Leave";
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

		   echo "<table border=\"1\">";
		   echo "<tr>";
		   		echo "<td>EmpCode</td>";
                                echo "<td>QualEmpCode</td>";
                                echo "<td>EmpName</td>";
                                echo "<td>Dept</td>";
                                echo "<td>DOJ</td>";
				echo "<td>PL</td>";
				echo "<td>CL</td>";
				echo "<td>SL</td>";
                                echo "<td>Leave Month</td>";
                                echo "<td>Leave Year</td>";
			echo "</tr>";	
			
                        if(!empty($data)){
		   foreach($data as $d)
		   {
		  // print_r($d['t1']['EmpCode']);
			
			  echo "<tr>";	
			  echo "<td>".$d['t1']['EmpCode']."</td>";
                          echo "<td>".$d['t2']['QualEmpCode']."</td>";
                          echo "<td>".$d['t2']['EmapName']."</td>";
                          echo "<td>".$d['t2']['Dept']."</td>";
                          echo "<td>".$d['t2']['DOFJ']."</td>";
			  echo "<td>".round($d['0']['pl'],2)."</td>";
			  echo "<td>".round($d['0']['cl'],2)."</td>";
			  echo "<td>".round($d['0']['sl'],2)."</td>";
                           echo "<td>".$d['0']['LeaveMonth']."</td>";
                            echo "<td>".$d['t1']['LeaveYear']."</td>";
			  echo "</tr>"; 
		   }
                        }
		  //t1.,t1.,t1.PFS
		   echo "</table>";

		   exit;
		   }

	}
       
     
        
        
         public function discardsalary()
    {
        $this->layout = "home";
        
        $date = date('Y-m-d H:i:s');
$monthName=	date("m", strtotime($date));
$yearcur=	date("Y", strtotime($date));
        //$monthNum = sprintf("%02s", $month);
$mt= date("M", strtotime($date));
if($monthName >1 && $monthName <=12){
$premonth = $monthName-1;
}
else {
   $premonth =12; 
}
$pdate = date('Y-'.$premonth.'-d ');
$mp=  date("M",strtotime($pdate));
$datam[$mp]=$mp;
$datam[$mt]=$mt;
$datay[$yearcur]=$yearcur;
$this->set('month',$datam);
$this->set('datay',$datay);   
        
        
        
        $wrongData = array();
        if($this->request->is('POST'))
        {
           
						
						$Leave['LeaveMonth'] = $data['SalMonth'] = $this->request->data['upload']['month'];
						$Leave['LeaveYear'] =  $data['SalYear'] = $this->request->data['upload']['finance_year'];
						$Leave['LeaveStatus'] =  'DR';
						
                  $dataArr[] = $data;
				   $LeavArr[] = $Leave;
//                                   if (in_array($data['SalMonth'], $datam) && in_array($data['SalYear'], $datay)) {
				if($this->Attendance->deleteAll(array('SalMonth'=>$data['SalMonth'],'SalYear'=>$data['SalYear'])))
                                {
				$this->Leave->deleteAll(array('LeaveMonth'=>$Leave['LeaveMonth'],'LeaveYear'=>$Leave['LeaveYear'],'LeaveStatus'=>$Leave['LeaveStatus']));
                                $this->Attendance->query("delete from qual_salary where SalMonth= '{$data['SalMonth']}' and SalYear= '{$data['SalYear']}'");
                                $this->Session->setFlash('Salary Discard  for '.$data['SalMonth'].'-'.$data['SalYear']);
                                }
              else{
                $this->Session->setFlash('Salary Not Discard ');
              }
//            }
//            else{
//                $this->Session->setFlash('Wrong Month Selected ');
//            }
        }
    }
    
     public function discardincentive()
    {
        $this->layout = "home";
        
         $date = date('Y-m-d H:i:s');
$monthName=	date("m", strtotime($date));
$yearcur=	date("Y", strtotime($date));
        //$monthNum = sprintf("%02s", $month);
$mt= date("M", strtotime($date));
if($monthName >1 && $monthName <=12){
$premonth = $monthName-1;
}
else {
   $premonth =12; 
}
$pdate = date('Y-'.$premonth.'-d ');
$mp=  date("M",strtotime($pdate));
$datam[$mp]=$mp;
$datam[$mt]=$mt;
$datay[$yearcur]=$yearcur;
$this->set('month',$datam);
$this->set('datay',$datay); 
        $wrongData = array();
        if($this->request->is('POST'))
        {
           
						
						$Leave['LeaveMonth'] = $data['SalMonth'] = $this->request->data['upload']['month'];
						$Leave['LeaveYear'] =  $data['SalYear'] = $this->request->data['upload']['finance_year'];
						$Leave['LeaveStatus'] =  'DR';
						
                  $dataArr[] = $data;
				   $LeavArr[] = $Leave;
//                                   if (in_array($data['SalMonth'], $datam) && in_array($data['SalYear'], $datay)) {
				if($this->IncentivesManager->deleteAll(array('SalMonth'=>$data['SalMonth'],'SalYear'=>$data['SalYear'])))
                                        
                                {
				
                                $this->Attendance->query("delete from qual_salary where SalMonth= '{$data['SalMonth']}' and SalYear= '{$data['SalYear']}'");
                                $this->Session->setFlash('Incentive Discard  for '.$data['SalMonth'].'-'.$data['SalYear']);
                                }
              else{
                $this->Session->setFlash('Incentive Not Discard ');
            }
//                                   }
//            else
//            {
//                 $this->Session->setFlash('Wrong Month Selected ');
//            }
        }
            
    }
        
    }


?>