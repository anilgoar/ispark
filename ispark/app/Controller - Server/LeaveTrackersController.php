<?php
class LeaveTrackersController extends AppController 
{
    public $uses = array('Jclr','User','Attendance','LeavesManager');
        
    
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

            if(in_array('1',$roles)){$this->Auth->allow('index','get_report11','export1','save_status','exportsalary','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','empcode');}
            else{$this->Auth->deny('index','get_report11','export1','save_status','exportsalary','incentive','get_status_data','typeformat','importformat','salaryslip','exportincentive','empcode');}
        }
    }
    
   
    public function index(){
      $this->layout = "home";  
       $data1 = $this->Jclr->find('list',array('fields'=>array('EmpCode','EmpCode'),'group'=>array('EmpCode'),'order' =>'EmapName'));
       $data2 = $this->Jclr->find('list',array('fields'=>array('EmapName','EmapName'),'group'=>array('EmpCode'),'order' =>'EmapName'));
       $this->set('Data1',$data1);
        $this->set('Data2',$data2);
         if ($this->request->is('post')) 
			{
				//$this->Jclr->create();
			
            $data=	$this->request->data;
            $dt['EmpCode']=$data['LeaveTrackers']['EmpCode'];
             $dt['leavedate']=$data['LeaveTrackers']['leavedate'];
              
               $dt['Remarks']=$data['LeaveTrackers']['Remarks'];
               $dt['userid']=$this->Session->read('userid');
               $dt['Importdate']=date('Y-m-d H:i:s');
            //print_r($dt);die;
            $data12 = $this->LeavesManager->find('list',array('fields'=>array('EmpCod'),'conditions'=>array('EmpCode'=>$dt['EmpCode'],'LeaveDate'=>$dt['leavedate'])));
           //print_r($data12);die;
            $this->set('leave',$data12);
           
                                }
    }

    public function empcode(){
       $this->layout = "ajax";
       if($this->request->is('POST'))
        {
         $data=  $this->request->data;
       
         $emp =$data['EmapName'];
        $data2 = $this->Jclr->find('list',array('fields'=>array('EmpCode','EmpCode'),'conditions'=>array('EmapName'=>$emp)));
        // print_r($data2);die;
        $this->set('Data2',$data2);
        }
       
               
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
             
           $data = $this->Attendance->query("SELECT t1.EmpCode,t1.EmapName,t1.FatherName,t1.Dept,t1.Desg,t1.DOFJ,t1.Basic,t1.HRA,t1.Conv,t1.OthAllw,t1.Gross,t2.Paiddays,t2.OTDays 
,ROUND(t1.Basic/$d*t2.Paiddays) `EBasic`,ROUND(t1.HRA/$d*t2.Paiddays) `EHRA`,ROUND(t1.Conv/$d*t2.Paiddays) `EConv`
,ROUND(t1.OthAllw/$d*t2.Paiddays) `EOthAllw`,ROUND(t1.Gross/$pred*t2.ArrDays) `ArrLM`,'0' `Conv`,'0' `Incentive`,ROUND(t1.Gross/$d*t2.OTDays) overtime,
(ROUND(t1.Basic/$d*t2.Paiddays)+ROUND(t1.HRA/$d*t2.Paiddays)+ROUND(t1.Conv/$d*t2.Paiddays)+ROUND(t1.OthAllw/$d*t2.Paiddays)+ROUND(t1.Gross/$pred*t2.ArrDays)+0+0+ROUND(t1.Gross/$d*t2.OTDays)) TGross,
ROUND(ROUND(t1.Basic/$d*t2.Paiddays)/100*12) PF,'0' TDS,'0' `AdvDed`,
IF(t1.ESIS='Yes',ROUND((ROUND(t1.Basic/$d*t2.Paiddays)+ROUND(t1.HRA/$d*t2.Paiddays)+ROUND(t1.Conv/$d*t2.Paiddays)+ROUND(t1.OthAllw/$d*t2.Paiddays)+ROUND(t1.Gross/$pred*t2.ArrDays)+0+0+ROUND(t1.Gross/$d*t2.OTDays))/100*1.75),'0') ESI,
ROUND(ROUND(t1.Basic/$d*t2.Paiddays)/100*13.16) EPF,
IF(t1.ESIS='Yes',ROUND((ROUND(t1.Basic/$d*t2.Paiddays)+ROUND(t1.HRA/$d*t2.Paiddays)+ROUND(t1.Conv/$d*t2.Paiddays)+ROUND(t1.OthAllw/$d*t2.Paiddays)+ROUND(t1.Gross/$pred*t2.ArrDays)+0+0+ROUND(t1.Gross/$d*t2.OTDays))/100*4.75),'0') EESI,
IF(t1.Dept='VODAFONE PNG','335','120') EmplrIns,t1.ESINo,t1.UAN,t1.PFNo,t1.ESIS,t1.PFS
FROM qual_employee t1 LEFT JOIN qual_attendance t2 ON t1.EmpCode=t2.EmpCode where t2.SalYear = '$finance_year' and t2.SalMonth = '$month' ");
           
           			$fileName = "qualtouch_salary";
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

		   echo "<table border=\"1\">";
		   echo "<tr>";
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
				echo "<td>Conv</td>";
				echo "<td>Incentive</td>";
				echo "<td>overtime</td>";
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
		      echo "<td>".$d['t1']['EmpCode']."</td>";
			  echo "<td>".$d['t1']['EmapName']."</td>";
			  echo "<td>".$d['t1']['FatherName']."</td>";
			  echo "<td>".$d['t1']['Dept']."</td>";
			  echo "<td>".$d['t1']['Desg']."</td>";
			  echo "<td>".$d['t1']['DOFJ']."</td>";
			  echo "<td>".$d['t1']['Basic']."</td>";
			  echo "<td>".$d['t1']['HRA']."</td>";
			  echo "<td>".$d['t1']['Conv']."</td>";
			  echo "<td>".$d['t1']['OthAllw']."</td>";
			  echo "<td>".$d['t1']['Gross']."</td>";
			  echo "<td>".$d['t2']['Paiddays']."</td>";
			  echo "<td>".$d['t2']['OTDays']."</td>";
			  echo "<td>".$d['0']['EBasic']."</td>";
			  echo "<td>".$d['0']['EHRA']."</td>";
			  echo "<td>".$d['0']['EConv']."</td>";
			  echo "<td>".$d['0']['EOthAllw']."</td>";
			  echo "<td>".$d['0']['ArrLM']."</td>";
			  echo "<td>".$d['0']['Conv']."</td>";
			  echo "<td>".$d['0']['Incentive']."</td>";
			  echo "<td>".$d['0']['overtime']."</td>";
			  echo "<td>".$d['0']['TGross']."</td>";
			  echo "<td>".$d['0']['PF']."</td>";
			  echo "<td>".$d['0']['TDS']."</td>";
			  echo "<td>".$d['0']['AdvDed']."</td>";
			  echo "<td>".$d['0']['ESI']."</td>";
			  echo "<td>".($d['0']['TGross']-$d['0']['PF']-$d['0']['ESI'])."</td>";
			  echo "<td>".$d['0']['EPF']."</td>";
			  echo "<td>".$d['0']['EESI']."</td>";
			  echo "<td>".$d['0']['EmplrIns']."</td>";
			  echo "<td>".($d['0']['TGross']+$d['0']['EPF']+$d['0']['EESI']+$d['0']['EmplrIns'])."</td>";
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
           
           			$fileName = "qualtouch_Incentive";
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
             $dt['Salyear']=$row[1];
              $dt['salmonth']=$row[2];
               $dt['incamt']=$row[3];
               $dt['Remarks']=$row[4];
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
	}	
          
	
	
}

?>