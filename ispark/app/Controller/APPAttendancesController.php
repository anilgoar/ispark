<?php
class APPAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export_attendance');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branch_name=$this->Session->read("branch_name");
        $role = $this->Session->read("role");
        
        if($role=='admin')
        {
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
        }
        else
        {
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
        }
        
        
        
    }
    
    public function export_attendance()
    {
        $this->layout='ajax';
         $start_date = $this->params->query['FromDate']; 
        $end_date = $this->params->query['ToDate'];
        $branch = $this->params->query['BranchName'];
        $fetch_type = $this->params->query['fetch_type']; 
        
        $qry = "SELECT mas_jclr.EmpCode,mas_jclr.EmpName,mas_jclr.BranchName,Lat,Lon,log_status,
            created_at,date(created_at) dater,mas_jclr.CostCenter
            FROM `masjclrentry` mas_jclr  
INNER JOIN `mas_daily_attndce_tracker` mas_attn ON mas_jclr.EmpCode = mas_attn.Mas_Code
WHERE date(created_at) BETWEEN STR_TO_DATE('$start_date','%d-%b-%Y')  AND STR_TO_DATE('$end_date','%d-%b-%Y')  AND BranchName='$branch'";
        
        $data = $this->Masjclrentry->query($qry);
        
        $qry1 = "select cost_center,process_name,CostCenterName from cost_master";
        $data1 = $this->Masjclrentry->query($qry1);
        
        $process_master = array();
        foreach($data1 as $process)
        {
            if(!empty($process['cost_master']['CostCenterName']))
            {
                $process_master[strtolower($process['cost_master']['cost_center'])] = $process['cost_master']['CostCenterName'];
            }
            else
            {
                $process_master[strtolower($process['cost_master']['cost_center'])] = $process['cost_master']['process_name'];
            }
        }
        
        //print_r($process_master); exit;
        
        $fileName = "attendance_export";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
        
        
        $emp_master  = array();
        foreach($data as $record)
        {
            $new_record = array();
            $emp_code =$record['mas_jclr']['EmpCode'];
            $new_record['EmpCode'] = $emp_code;
            $new_record['EmpName'] = $record['mas_jclr']['EmpName'];
            $new_record['BranchName'] = $record['mas_jclr']['BranchName'];
            $new_record['Lat'] = $record['mas_attn']['Lat'];
            $new_record['Lon'] = $record['mas_attn']['Lon'];
            $new_record['log_status'] = $record['mas_attn']['log_status'];
            $login_date = $record['mas_attn']['created_at'];
            $date = $record['0']['dater'];
            $status = $record['mas_attn']['log_status'];
            $cost_center = strtolower($record['mas_jclr']['CostCenter']);
            //if($record['0']['created'])
            if($status=='login' && empty($emp_master[$emp_code][$date]['login_time']))
            {
                $emp_master[$record['mas_jclr']['EmpCode']][$date]['login_time'] = $login_date;
            }
            if($status=='login' && strtotime($login_date)<$emp_master[$emp_code][$date]['login_time'])
            {
                $emp_master[$record['mas_jclr']['EmpCode']][$date]['login_time'] = $login_date;
            }
            
            if($status=='logout' &&  empty($emp_master[$emp_code][$date]['login_time']))
            {
                $emp_master[$record['mas_jclr']['EmpCode']][$date]['logout_time'] = $login_date; 
            }
            if($status=='logout' && strtotime($login_date)>$emp_master[$emp_code][$date]['logout_time'])
            {
                $emp_master[$record['mas_jclr']['EmpCode']][$date]['logout_time'] = $login_date;
            }
            //$emp_master[$record['mas_jclr']['EmpCode']][$date] = $new_record;
            
            $emp_code_master[] =  $emp_code;
            $emp_det_master[$emp_code]['EmpName'] =  $record['mas_jclr']['EmpName'];
            $emp_det_master[$emp_code]['BranchName'] =  $record['mas_jclr']['BranchName'];
            $emp_det_master[$emp_code]['process_name'] =  $process_master[$cost_center];
            $dater_master[] = $date;
             
        }
        
        $emp_code_master = array_unique($emp_code_master);
        $dater_master = array_unique($dater_master);
        sort($emp_code_master);
        sort($dater_master);
        
        echo '<table border = "2">';
        echo '<tr>';
        echo '<th rowspan="2" style="text-align: center;">Emp Code</th>';
        echo '<th rowspan="2" style="text-align: center;">Emp Name</th>';
        echo '<th rowspan="2" style="text-align: center;">Branch</th>';
        echo '<th rowspan="2" style="text-align: center;">Process</th>';
        
        foreach($dater_master as $dater)
        {
            echo '<th colspan="2" style="text-align: center;">';
            echo $dater;
            echo '</th>';
        }
        echo '</tr>';
        
                echo '<tr>';
        
        
        foreach($dater_master as $dater)
        {
            echo '<th style="text-align: center;">Login</th>';
            echo '<th style="text-align: center;">Logout</th>';
        }
        echo '</tr>';
        
        foreach($emp_code_master as $emp)
        {
            echo '<tr>';
            echo '<td style="text-align: center;">'.$emp.'</td>';
            echo '<td style="text-align: center;">'.$emp_det_master[$emp]['EmpName'].'</td>';
            echo '<td style="text-align: center;">'.$emp_det_master[$emp]['BranchName'].'</td>';
            echo '<td style="text-align: center;">'.$emp_det_master[$emp]['process_name'].'</td>';
            foreach($dater_master as $dater)
            {
                echo '<td style="text-align: center;">'.$emp_master[$emp][$dater]['login_time'].'</td>';
                echo '<td style="text-align: center;">'.$emp_master[$emp][$dater]['logout_time'].'</td>';
            }
            echo '</tr>';
        }
        
        
        echo '</table>';
        exit;
    }
    
    
    
    
    
    
}
?>