<?php
class EmployeeStatusReportsController extends AppController {
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
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            
            $branch_name    =   $this->request->data['EmployeeStatusReports']['branch_name'];
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
                echo '<th style="text-align: center;">Branch</th>';
                echo '<th style="text-align: center;">CostCenter</th>';
                echo '<th style="text-align: center;">Active Employee</th>';
                echo '<th style="text-align: center;">Field Employee</th>';
                echo '<th style="text-align: center;">InHouse Employee</th>';
                echo '<th style="text-align: center;">OnSite Employee</th>';
                echo '</tr>';
                
                $Field=0;
                $InHouse=0;
                $OnSite=0;
                $TotalActive=0;
                foreach($data as $row){
                    $Field=$Field+$row[0]['EmpField'];
                    $InHouse=$InHouse+$row[0]['InHouse'];
                    $OnSite=$OnSite+$row[0]['OnSite'];
                    $TotalActive=$TotalActive+$row[0]['TotActive'];
                    echo '<tr>';
                    echo '<td style="text-align: center;">'.$row['Masjclrentry']['BranchName'].'</td>';
                    echo '<td style="text-align: center;">'.$row['Masjclrentry']['CostCenter'].'</td>';
                    echo '<td style="text-align: center;">'.$row[0]['TotActive'].'</td>';
                    echo '<td style="text-align: center;">'.$row[0]['EmpField'].'</td>';
                    echo '<td style="text-align: center;">'.$row[0]['InHouse'].'</td>';
                    echo '<td style="text-align: center;">'.$row[0]['OnSite'].'</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                    echo '<td style="text-align: center;">Total</td>';
                    echo '<td style="text-align: center;"></td>';
                    echo '<td style="text-align: center;">'.$TotalActive.'</td>';
                    echo '<td style="text-align: center;">'.$Field.'</td>';
                    echo '<td style="text-align: center;">'.$InHouse.'</td>';
                    echo '<td style="text-align: center;">'.$OnSite.'</td>';
                    echo '</tr>';
                echo ' </table>';die;
            }
        }    
    }
    
    public function download(){
        if($_REQUEST['BranchName'] !="" && $_REQUEST['CostCenter'] !="" && $_REQUEST['EmpLoc'] !=""){
            
            $EmpLoc         =   $_REQUEST['EmpLoc'];
            $conditoin      =   array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'CostCenter'=>$_REQUEST['CostCenter']);
          
            if($EmpLoc !="ALL"){$conditoin['EmpLocation']=$EmpLoc;}else{unset($conditoin['EmpLocation']);}
            
            $data = $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpName','BranchName','CostCenter','EmpLocation','Stream','Process'),'conditions'=>$conditoin));
           
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
                echo '<td>'.$row['Masjclrentry']['Stream'].'</td>';
                echo '<td>'.$row['Masjclrentry']['Process'].'</td>';
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