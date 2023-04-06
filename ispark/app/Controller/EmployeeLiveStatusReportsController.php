<?php
class EmployeeLiveStatusReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','download');
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
            
            $branch_name    =   $this->request->data['EmployeeLiveStatusReports']['branch_name'];
            $Submit         =   $this->request->data['Submit'];
            $conditoin      =   array('Status'=>1);
           
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            $data = $this->Masjclrentry->find('all',array('fields'=>array('BranchName','CostCenter','SUM(IF(EmpLocation ="InHouse" ,1,0)) AS InHouse','SUM(IF(EmpLocation ="Field" ,1,0)) AS EmpField','SUM(IF(EmpLocation ="OnSite" ,1,0)) AS OnSite','SUM(IF(EmpLocation ="InHouse" ,1,0) + IF(EmpLocation ="Field" ,1,0) + IF(EmpLocation ="OnSite" ,1,0)) AS TotActive'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            $this->set('data',$data);  
       
            if($Submit =="View"){
                $this->set('data',$data);  
            }
            else{
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=EmployeeStatus.xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo '<table border="1">';
                echo '<tr>';
                echo '<th>Branch</th>';
                echo '<th>CostCenter</th>';
                echo '<th>Active Employee</th>';
                echo '<th>Field Employee</th>';
                echo '<th>InHouse Employee</th>';
                echo '<th>OnSite Employee</th>';
                echo '</tr>';
                foreach($data as $row){
                    echo '<tr>';
                    echo '<td>'.$row['Masjclrentry']['BranchName'].'</td>';
                    echo '<td>'.$row['Masjclrentry']['CostCenter'].'</td>';
                    echo '<td>'.$row[0]['TotActive'].'</td>';
                    echo '<td>'.$row[0]['EmpField'].'</td>';
                    echo '<td>'.$row[0]['InHouse'].'</td>';
                    echo '<td>'.$row[0]['OnSite'].'</td>';
                    echo '</tr>';
                }
                echo ' </table>';die;
            }
        }    
    }
    
    public function download(){
        if($_REQUEST['BranchName'] !="" && $_REQUEST['CostCenter'] !="" && $_REQUEST['EmpLoc'] !=""){
            
            $EmpLoc         =   $_REQUEST['EmpLoc'];
            $conditoin      =   array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'CostCenter'=>$_REQUEST['CostCenter']);
          
            if($EmpLoc !="ALL"){$conditoin['EmpLocation']=$EmpLoc;}else{unset($conditoin['EmpLocation']);}
            
            $data = $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpName','BranchName','CostCenter','EmpLocation'),'conditions'=>$conditoin));
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=MyFile.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>EmpCode</th>';
            echo '<th>EmpName</th>';
            echo '<th>Stream</th>';
            echo '<th>Process</th>';
            echo '<th>CostCenter</th>';
            echo '<th>Location</th>';
            echo '<th>EmpFor</th>';
            echo '</tr>';
            foreach($data as $row){
                echo '<tr>';
                echo '<td>'.$row['Masjclrentry']['EmpCode'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EmpName'].'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td>'.$row['Masjclrentry']['CostCenter'].'</td>';
                echo '<td>'.$row['Masjclrentry']['BranchName'].'</td>';
                echo '<td>'.$row['Masjclrentry']['EmpLocation'].'</td>';
                echo '</tr>';
            }
            echo ' </table>';die;  
        }      
    }
    
    
    
    
    
    
}
?>