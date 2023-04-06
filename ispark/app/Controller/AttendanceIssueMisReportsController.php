<?php
class AttendanceIssueMisReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getcostcenter');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'))));
        }
        else if(count($branchName)>1){
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else{
           $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ExportEmployeeDetails.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $branch_name        =   $this->request->data['ExportEmployeeDetails']['branch_name'];
            $CostCenter         =   $this->request->data['CostCenter'];
            $EmployeeType       =   $this->request->data['EmployeeType'];
            $EmployeeStatus     =   $this->request->data['EmployeeStatus'];
            
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            if($EmployeeType !="ALL"){$conditoin['EmpType']=$EmployeeType;}else{unset($conditoin['EmpType']);}
            if($EmployeeStatus !="ALL"){$conditoin['Status']=$EmployeeStatus;}else{unset($conditoin['Status']);}
            
            if(!empty($conditoin)){
                $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin));
            }
            else{
               $data   =   $this->Masjclrentry->find('all'); 
            }
            
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>EmpCode</th>';
            echo '<th>BioMetricCode</th>';
            echo '<th>EmpType</th>';
            echo '<th>EmpName</th>';
            echo '<th>Fname</th>';
            echo '<th>Gender</th>';
            echo '<th>DOB</th>';
            echo '<th>DOJ</th>';
            echo '<th>Desig</th>';
            echo '<th>Depart</th>';
            echo '<th>EmpFor</th>';
            echo '<th>Profile</th>';
            echo '<th>Location</th>';
            echo '<th>CostCenter</th>';
            echo '<th>Qualification</th>';
            echo '<th>MaritalStatus</th>';
            echo '<th>BloodG</th>';
            echo '<th>PAddress</th>';
            echo '<th>PCity</th>';
            echo '<th>PState</th>';
            echo '<th>PpinCode</th>';
            echo '<th>TAddress</th>';
            echo '<th>TCity</th>';
            echo '<th>TState</th>';
            echo '<th>TPinCode</th>';
            echo '<th>PMobNo</th>';
            echo '<th>PLandLine</th>';
            echo '<th>TMobNo</th>';
            echo '<th>TLandLine</th>';
            echo '<th>EmailId</th>';
            echo '<th>documentDone</th>';
            echo '<th>Gross</th>';
            echo '<th>CTCOffered</th>';
            echo '<th>NetInHand</th>';
            echo '<th>AcNo</th>';
            echo '<th>IFSCCode</th>';
            echo '<th>AcBank</th>';
            echo '<th>AcBranch</th>';
            echo '<th>PassPortNo</th>';
            echo '<th>dlNo</th>';
            echo '<th>EpfNo</th>';
            echo '<th>EsiNo</th>';
            echo '<th>EntryDate</th>';
            echo '<th>Status</th>';
            echo '<th>LeftDate</th>';
            echo '<th>LeftRmks</th>';
            echo '<th>SourceType</th>';
            echo '<th>Source</th>';
            echo '<th>BoxFileNo</th>';
            echo '<th>AadharID</th>';
            echo '<th>PanNo</th>';
            echo '</tr>';
            foreach($data as $row){
                echo '<tr>';
                echo '<td>'.$row['Masjclrentry']['EmpCode'].'</td>';
                echo '<td>'.$row['Masjclrentry']['BioCode'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EmpType'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EmpName'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Father'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Gendar'].'</td>';
                echo '<td>'.date('d-M-Y',strtotime($row['Masjclrentry']['DOB'])).'</td>';
                echo '<td>'.date('d-M-Y',strtotime($row['Masjclrentry']['DOJ'])).'</td>';
                echo '<td>'.$row['Masjclrentry']['Desgination'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Dept'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EmpLocation'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Profile'].'</td>';
                echo '<td>'.$row['Masjclrentry']['BranchName'].'</td>';
                echo '<td>'.$row['Masjclrentry']['CostCenter'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Qualification'].'</td>';
                echo '<td>'.$row['Masjclrentry']['MaritalStatus'].'</td>';
                echo '<td>'.$row['Masjclrentry']['BloodGruop'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Adrress1'].'</td>';
                echo '<td>'.$row['Masjclrentry']['City'].'</td>';
                echo '<td>'.$row['Masjclrentry']['State'].'</td>';
                echo '<td>'.$row['Masjclrentry']['PinCode'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Adrress2'].'</td>';
                echo '<td>'.$row['Masjclrentry']['City1'].'</td>';
                echo '<td>'.$row['Masjclrentry']['State1'].'</td>';
                echo '<td>'.$row['Masjclrentry']['PinCode1'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Mobile'].'</td>';
                echo '<td></td>';
                echo '<td>'.$row['Masjclrentry']['Mobile1'].'</td>';
                echo '<td></td>';
                echo '<td>'.$row['Masjclrentry']['EmailId'].'</td>';
                echo '<td></td>';
                echo '<td>'.$row['Masjclrentry']['Gross'].'</td>';
                echo '<td>'.$row['Masjclrentry']['CTC'].'</td>';
                echo '<td>'.$row['Masjclrentry']['NetInhand'].'</td>';
                echo '<td>'.$row['Masjclrentry']['AcNo'].'</td>';
                echo '<td>'.$row['Masjclrentry']['IFSCCode'].'</td>';
                echo '<td>'.$row['Masjclrentry']['AcBank'].'</td>';
                echo '<td>'.$row['Masjclrentry']['AcBranch'].'</td>';
                echo '<td>'.$row['Masjclrentry']['PassportNo'].'</td>';
                echo '<td>'.$row['Masjclrentry']['dlNo'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EPFNo'].'</td>';
                echo '<td>'.$row['Masjclrentry']['ESICNo'].'</td>';
                echo '<td>'.date('d-M-Y',strtotime($row['Masjclrentry']['CreateDate'])).'</td>';
                if($row['Masjclrentry']['Status'] =="0"){echo '<td>L</td>';}else{echo '<td></td>';}
                if($row['Masjclrentry']['DOL'] !=""){echo '<td>'.date('d-M-Y',strtotime($row['Masjclrentry']['DOL'])).'</td>';}else{echo '<td></td>';}
                echo '<td>'.$row['Masjclrentry']['LeftReason'].'</td>';
                echo '<td>'.$row['Masjclrentry']['SourceType'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Source'].'</td>';
                echo '<td>'.$row['Masjclrentry']['BoxFileNo'].'</td>';
                echo '<td>'.$row['Masjclrentry']['AdharId'].'</td>';
                echo '<td>'.$row['Masjclrentry']['PanNo'].'</td>';
                echo '</tr>';
            }
            echo ' </table>';
            
            die;
        }    
    }
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
        
        
    }
    
    
}
?>