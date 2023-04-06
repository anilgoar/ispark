<?php
class EmployeePendingReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','TrainingAllocationMaster','MasJclrentrydata');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','pendingdetails','exportpendingdetails','pendingdata');
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            //$this->set('branchName',$BranchArray);
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $data=array();
        $data1      =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName'),'conditions'=>array('BranchName'=>$branchName),'group'=>'BranchName'));
        $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
        $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
        
        foreach($data1 as $row){
            
            $Branch=$row['Masjclrentry']['BranchName'];
            
            $AttSta         =   $this->get_attend_details(array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$Branch));
            
            $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
            $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
            $AssignAllocatArr   = $this->MasJclrentrydata->query("SELECT * FROM `mas_Jclrentrydata` WHERE BranchName='$Branch'");
           
            $BioCodeArr=array();
            foreach($AssignAllocatArr as $row){
                $BioCodeArr[]=$row['mas_Jclrentrydata']['BioCode'];
            }
            
            $TotalAttandArray   = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$Branch' and date(AttandDate)='$MaxAttandDate' and EmpCode is null group by BioCode");
            $PendingArray=array();
            foreach($TotalAttandArray as $row){
                if(!in_array($row['Attandence']['BioCode'], $BioCodeArr)){
                    $PendingArray[]=$TotalAttandArray['Attandence']['BioCode'];
                }
            }
            
            $PenJclr            =   count($PendingArray);

            $MarkInactive       =   $this->get_mark_inactive($Branch);
            $NotInBio           =   $this->get_upload_attandance($Branch,$MaxDate);
           
            $PenEmpCnt          =   $this->Masattandance->query("SELECT COUNT(Id) AS TotalRow FROM `mas_Jclrentrydata` WHERE BranchName='$Branch' AND TrainningStatus='No'");  
            $PenEmp1            =   $PenEmpCnt[0][0]['TotalRow'];
       
            $PenEmpCnt1         =   $this->Masattandance->query("SELECT COUNT(Id) AS TotalRow FROM `mas_Jclrentrydata` WHERE BranchName='$Branch' and TrainningStatus='Yes' and CertifiedDate is not null");  
            $PenEmp2            =   $PenEmpCnt1[0][0]['TotalRow'];
            $PenEmp             =   $PenEmp1+$PenEmp2;

            $data[]=array(
                'BranchName'=>$Branch,
                'PenAlloc'=>$PenJclr,
                'MarkInactive'=>$MarkInactive,
                'NotInBio'=>$NotInBio,
                'PenJclr'=>$PenEmp,
                'Suspended'=>$AttSta['Suspended'],
                'LasAttendanceDate'=>$MaxDate,
            );
        }
        
        
        $this->set('branchname',$branch_name);
        $this->set('CostCenter',$CostCenter);
        $this->set('empmonth',$StartDate);
        $this->set('data',$data);
        $this->set('totalcount',$totalcount);   
    }
    
    
    
    public function pendingdata(){
        $this->layout='ajax';
        $branchName = $_REQUEST['BranchName'];
 
        $data=array();
        if($branchName !="ALL"){
            $data1      =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName'),'conditions'=>array('BranchName'=>$branchName),'group'=>'BranchName'));
        }
        else{
            $data1      =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName'),'order'=>'BranchName','group'=>'BranchName'));
        }
        
        $maxDateAr      =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
        $MaxDate        =   $maxDateAr[0][0]['MaxDate'];
        
        foreach($data1 as $row){
            
            $Branch=$row['Masjclrentry']['BranchName'];
            
            $AttSta         =   $this->get_attend_details(array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$Branch));
            
            $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
            $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
            $AssignAllocatArr   = $this->MasJclrentrydata->query("SELECT * FROM `mas_Jclrentrydata` WHERE BranchName='$Branch'");
           
            $BioCodeArr=array();
            foreach($AssignAllocatArr as $row){
                $BioCodeArr[]=$row['mas_Jclrentrydata']['BioCode'];
            }
            
            $TotalAttandArray   = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$Branch' and date(AttandDate)='$MaxAttandDate' and EmpCode is null group by BioCode");
            $PendingArray=array();
            foreach($TotalAttandArray as $row){
                if(!in_array($row['Attandence']['BioCode'], $BioCodeArr)){
                    $PendingArray[]=$TotalAttandArray['Attandence']['BioCode'];
                }
            }
            
            $PenJclr            =   count($PendingArray);

            $MarkInactive       =   $this->get_mark_inactive($Branch);
            $NotInBio           =   $this->get_upload_attandance($Branch,$MaxDate);
           
            $PenEmpCnt          =   $this->Masattandance->query("SELECT COUNT(Id) AS TotalRow FROM `mas_Jclrentrydata` WHERE BranchName='$Branch' AND TrainningStatus='No'");  
            $PenEmp1            =   $PenEmpCnt[0][0]['TotalRow'];
       
            $PenEmpCnt1         =   $this->Masattandance->query("SELECT COUNT(Id) AS TotalRow FROM `mas_Jclrentrydata` WHERE BranchName='$Branch' and TrainningStatus='Yes' and CertifiedDate is not null");  
            $PenEmp2            =   $PenEmpCnt1[0][0]['TotalRow'];
            $PenEmp             =   $PenEmp1+$PenEmp2;

            $data[]=array(
                'BranchName'=>$Branch,
                'PenAlloc'=>$PenJclr,
                'MarkInactive'=>$MarkInactive,
                'NotInBio'=>$NotInBio,
                'PenJclr'=>$PenEmp,
                'Suspended'=>$AttSta['Suspended'],
                'LasAttendanceDate'=>$MaxDate,
            );
        }
        
        if(!empty($data)){
        ?>
        <table class = "table table-striped table-hover  responstable"  >     
            <thead>
                <tr>
                    <th style="text-align: center;">SNo</th>
                    <th style="text-align: center;width:150px;">Branch</th>
                    <th style="text-align: center;">Pending JCLR</th>
                    <th style="text-align: center;">Mark Inactive In Cosec</th>
                    <th style="text-align: center;">Not in Biometrics</th>
                    <th style="text-align: center;">Pending For Allocation</th>
                    <th style="text-align: center;">Suspended</th>
                    <th style="text-align: center;">Date</th>
                </tr>
            </thead>
            <tbody>         
                <?php
                $n=1; 
                $PenJclr=0;
                $MarkInactive=0;
                $NotInBio=0;
                $PenAlloc=0;
                $Suspended=0;

                foreach ($data as $val){
                $PenJclr=$PenJclr+$val['PenJclr'];
                $MarkInactive=$MarkInactive+$val['MarkInactive'];
                $NotInBio=$NotInBio+$val['NotInBio'];
                $PenAlloc=$PenAlloc+$val['PenAlloc'];
                $Suspended=$Suspended+$val['Suspended'];

                ?>
                <tr>
                    <td style="text-align: center;"><?php echo $n++;?></td>
                    <td style="text-align: center;width:150px;"><?php echo $val['BranchName'];?></td>
                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','PenAlloc')" href="#"><?php echo $val['PenJclr'];?></a></td>
                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','MarkInactive')" href="#"><?php echo $val['MarkInactive'];?></a></td>
                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','NotInBio')" href="#"><?php echo $val['NotInBio'];?></a></td>
                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','PenJclr')" href="#"><?php echo $val['PenAlloc'];?></a></td>
                    <td style="text-align: center;"><a onclick="getpending('<?php echo $val['BranchName'];?>','Suspended')" href="#"><?php echo $val['Suspended'];?></a></td>
                    <td style="text-align: center;"><?php if($val['LasAttendanceDate'] !=""){ echo date('d-M-Y',strtotime($val['LasAttendanceDate']));}?></td>
                </tr>
                <?php }?>
                <tr>
                    <td></td>
                    <td style="text-align: center;"><strong>Total</strong></td>
                    <td style="text-align: center;"><strong><?php echo $PenJclr;?></strong></td>
                    <td style="text-align: center;"><strong><?php echo $MarkInactive;?></strong></td>
                    <td style="text-align: center;"><strong><?php echo $NotInBio;?></strong></td>
                    <td style="text-align: center;"><strong><?php echo $PenAlloc;?></strong></td>
                    <td style="text-align: center;"><strong><?php echo $Suspended;?></strong></td>
                    <td></td>
                </tr>
            </tbody>   
        </table>
           
        <?php
        }
        else{
            echo "";
        }
        die;
        /*
        $this->set('branchname',$branch_name);
        $this->set('CostCenter',$CostCenter);
        $this->set('empmonth',$StartDate);
        $this->set('data',$data);
        $this->set('totalcount',$totalcount);  
        */
    }
    
    
    
    public function get_mark_inactive($BranchName){
        
        $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
        $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
        $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','BioCode','EmpName','ResignationDate'),'conditions'=>array('Status'=>0,'YEAR(ResignationDate)'=>date('Y'),'MONTH(ResignationDate)'=>date('m'),'EmpLocation'=>'InHouse','BranchName'=>$BranchName)));

        foreach($data1 as $row){
            $ina=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'BioCode'=>$row['Masjclrentry']['BioCode'])));

            if($ina > 0){
                $data[]=array('BioCode'=>$row['Masjclrentry']['BioCode']);
            }
        }
        
        return count($data);
    }
    
    public function get_upload_attandance($BranchName,$MaxDate){
        
        $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','BioCode','EmpName','EmpCode'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$BranchName)));  
        
        foreach($data1 as $row){
            
            $tc=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'EmpCode'=> $row['Masjclrentry']['EmpCode'])));

            if($tc ==0){
                $data[]=array(
                    'BioCode'=>$row['Masjclrentry']['BioCode'],
                ); 
            }

        } 
        
        return count($data);  
    }
    
    public function pendingdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){ 
            $BranchName=$_REQUEST['BranchName'];
            
            $data=array();
            if($_REQUEST['Status'] =="Suspended"){
               
                $data1   =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','BioCode','EmpName'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$BranchName),'order'=>'EmpName'));
                $sus     =  0;
                foreach($data1 as $row){
                    $empArr  =   $this->get_attend_emp($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['EmpCode'],$_REQUEST['Status']);
                    $sus     =   $empArr['Suspended'];
                    if($sus !=0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        );
                    }
                }
                
            }
            else if($_REQUEST['Status'] =="PenAlloc"){

                $data11  =   $this->Masattandance->query("SELECT BranchName,BioCode,EmpName from mas_Jclrentrydata where BranchName= '$BranchName' and TrainningStatus='No' group by BioCode");
                $data12  =   $this->Masattandance->query("SELECT BranchName,BioCode,EmpName from mas_Jclrentrydata where BranchName= '$BranchName' and TrainningStatus='Yes' and CertifiedDate is not null group by BioCode");  
                
                $data1=array_merge($data11,$data12);
                
                foreach($data1 as $row){
                    $data[]=array(
                        'BranchName'=>$row['mas_Jclrentrydata']['BranchName'],
                        'BioCode'=>$row['mas_Jclrentrydata']['BioCode'],
                        'EmpName'=>$row['mas_Jclrentrydata']['EmpName']
                    );
                }   
            }
            else if($_REQUEST['Status'] =="PenJclr"){
                
                
                $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
                $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
                $AssignAllocatArr   = $this->MasJclrentrydata->query("SELECT * FROM `mas_Jclrentrydata` WHERE BranchName='$BranchName'");

                $BioCodeArr=array();
                foreach($AssignAllocatArr as $row){
                    $BioCodeArr[]=$row['mas_Jclrentrydata']['BioCode'];
                }

                $TotalAttandArray   = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$BranchName' and date(AttandDate)='$MaxAttandDate' and EmpCode is null group by BioCode");
                $PendingArray=array();
                foreach($TotalAttandArray as $row){
                    if(!in_array($row['Attandence']['BioCode'], $BioCodeArr)){
                        
                        $data[]=array(
                            'BranchName'=>$row['Attandence']['BranchName'],
                            'BioCode'=>$row['Attandence']['BioCode'],
                            'EmpName'=>$row['Attandence']['EmpName']
                        );

                    }
                }   
                
            }
            else if($_REQUEST['Status'] =="NotInBio"){
                
                $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
                $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
                $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','BioCode','EmpName','EmpCode'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$BranchName),'order'=>'EmpName'));  
                foreach($data1 as $row){
                    $tc=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'EmpCode'=> $row['Masjclrentry']['EmpCode'])));

                    if($tc ==0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        ); 
                    }
                    
                }  
            }
            if($_REQUEST['Status'] =="MarkInactive"){
                $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
                $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
                $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','BioCode','EmpName','ResignationDate'),'conditions'=>array('Status'=>0,'YEAR(ResignationDate)'=>date('Y'),'MONTH(ResignationDate)'=>date('m'),'EmpLocation'=>'InHouse','BranchName'=>$BranchName)));
  
                foreach($data1 as $row){
                    $ina=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'BioCode'=>$row['Masjclrentry']['BioCode'])));
                   
                    if($ina > 0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        );
                    }
                }  
            }
           
            if(!empty($data)){  
            ?>
            <input type="button" value="Export" onclick="pendingexport('<?php echo $_REQUEST['BranchName'];?>','<?php echo $_REQUEST['Status'];?>')" class="btn pull-right btn-primary btn-new" >
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:30px;">SNo</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">BioCode</th>
                        <th style="text-align: center;">EmpName</th>
                        <?php if($_REQUEST['Status'] =="PenAlloc"){?>
                        <th style="text-align: center;">CertificationDate</th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody>         
                    <?php $i=1; foreach ($data as $row){
                        
                        $CertificationArr       =   $this->TrainingAllocationMaster->find('first',array('fields'=>array('CertificationDate'),'conditions'=>array('BioCode'=>$row['BioCode'])));
                        $CertificationDate      =   $CertificationArr['TrainingAllocationMaster']['CertificationDate'];
                        ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $row['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['BioCode']?></td>
                        <td style="text-align: center;"><?php echo $row['EmpName']?></td>
                        <?php if($_REQUEST['Status'] =="PenAlloc"){?>
                        <td style="text-align: center;"><?php if($CertificationDate !=""){ echo date("d M Y",strtotime($CertificationDate));}?></td>
                        <?php }?>
                    </tr>
                    <?php } ?>
                </tbody> 
            </table>
            <?php   
            die;
            }
            else{
               echo "";die;
            }
        }
    }
    
    
    public function exportpendingdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=EmployeePendingReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
                
            $BranchName=$_REQUEST['BranchName'];
            
            $data=array();
            if($_REQUEST['Status'] =="Suspended"){
               
                $data1   =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','BioCode','EmpName'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$BranchName),'order'=>'EmpName'));
                $sus     =  0;
                foreach($data1 as $row){
                    $empArr  =   $this->get_attend_emp($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['EmpCode'],$_REQUEST['Status']);
                    $sus     =   $empArr['Suspended'];
                    if($sus !=0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        );
                    }
                }
                
            }
            else if($_REQUEST['Status'] =="PenAlloc"){

                $data11  =   $this->Masattandance->query("SELECT BranchName,BioCode,EmpName from mas_Jclrentrydata where BranchName= '$BranchName' and TrainningStatus='No' group by BioCode");
                $data12  =   $this->Masattandance->query("SELECT BranchName,BioCode,EmpName from mas_Jclrentrydata where BranchName= '$BranchName' and TrainningStatus='Yes' and CertifiedDate is not null group by BioCode");  
                
                $data1=array_merge($data11,$data12);
                
                foreach($data1 as $row){
                    $data[]=array(
                        'BranchName'=>$row['mas_Jclrentrydata']['BranchName'],
                        'BioCode'=>$row['mas_Jclrentrydata']['BioCode'],
                        'EmpName'=>$row['mas_Jclrentrydata']['EmpName']
                    );
                }   
            }
            else if($_REQUEST['Status'] =="PenJclr"){

                $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
                $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
                $AssignAllocatArr   = $this->MasJclrentrydata->query("SELECT * FROM `mas_Jclrentrydata` WHERE BranchName='$BranchName'");

                $BioCodeArr=array();
                foreach($AssignAllocatArr as $row){
                    $BioCodeArr[]=$row['mas_Jclrentrydata']['BioCode'];
                }

                $TotalAttandArray   = $this->Masattandance->query("SELECT * from Attandence where BranchName= '$BranchName' and date(AttandDate)='$MaxAttandDate' and EmpCode is null group by BioCode");
                $PendingArray=array();
                foreach($TotalAttandArray as $row){
                    if(!in_array($row['Attandence']['BioCode'], $BioCodeArr)){
                        
                        $data[]=array(
                            'BranchName'=>$row['Attandence']['BranchName'],
                            'BioCode'=>$row['Attandence']['BioCode'],
                            'EmpName'=>$row['Attandence']['EmpName']
                        );

                    }
                }   
                
            }
            else if($_REQUEST['Status'] =="NotInBio"){
                
                $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
                $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
                $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','BioCode','EmpName','EmpCode'),'conditions'=>array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$BranchName),'order'=>'EmpName'));  
                foreach($data1 as $row){
                    $tc=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'EmpCode'=> $row['Masjclrentry']['EmpCode'])));

                    if($tc ==0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        ); 
                    }
                    
                }  
            }
            if($_REQUEST['Status'] =="MarkInactive"){
                $maxDateAr  =   $this->Masattandance->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
                $MaxDate    =   $maxDateAr[0][0]['MaxDate'];
                $data1      =  $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode','BioCode','EmpName','ResignationDate'),'conditions'=>array('Status'=>0,'YEAR(ResignationDate)'=>date('Y'),'MONTH(ResignationDate)'=>date('m'),'EmpLocation'=>'InHouse','BranchName'=>$BranchName)));
  
                foreach($data1 as $row){
                    $ina=$this->Masattandance->find('count',array('conditions'=>array('BranchName'=>$BranchName,'date(AttandDate)'=>$MaxDate,'BioCode'=>$row['Masjclrentry']['BioCode'])));
                   
                    if($ina > 0){
                        $data[]=array(
                            'BranchName'=>$row['Masjclrentry']['BranchName'],
                            'BioCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName']
                        );
                    }
                }  
            }
           
            ?>
            <table border="1" >     
                <thead>
                    <tr>
                        <th style="text-align: center;">SNo</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">BioCode</th>
                        <th style="text-align: center;">EmpName</th>
                        <th style="text-align: center;">CertificationDate</th>
                    </tr>
                </thead>
                <tbody>         
                     <?php $i=1; foreach ($data as $row){
                        
                        $CertificationArr    =   $this->TrainingAllocationMaster->find('first',array('fields'=>array('CertificationDate'),'conditions'=>array('BioCode'=>$row['BioCode'])));
                        $CertificationDate=$CertificationArr['TrainingAllocationMaster']['CertificationDate'];
                        ?>
                     <tr>
                        <td style="text-align: center;"><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $row['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['BioCode']?></td>
                        <td style="text-align: center;"><?php echo $row['EmpName']?></td>
                        <td style="text-align: center;"><?php if($CertificationDate !=""){ echo date("d M Y",strtotime($CertificationDate));}?></td>
                    </tr>
                    <?php } ?>
                </tbody> 
            </table>
            <?php   
            die;
           
            
        }
    }
    
    
     public function get_attend_emp($BranchName,$EmpCode,$Status){
        $maxDateAr=$this->Masjclrentry->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
        $maxdate=$maxDateAr[0][0]['MaxDate'];
        $start= date('Y-m-d', strtotime('-3 day', strtotime($maxdate)));

        $AttendArr=$this->Masjclrentry->query("
        SELECT
        SUM(IF(`Status` ='A',1,0)) > 3  AS Suspended,
        IF(SUM(IF(`Status` ='LWP',1,0)) > 0,1,0) AS LongLeave
        FROM `Attandence` 
        WHERE BranchName='$BranchName' AND EmpCode='$EmpCode'  AND DATE(AttandDate) BETWEEN '$start' AND '$maxdate'");

        return array('Suspended'=>$AttendArr[0][0]['Suspended'],'LongLeave'=>$AttendArr[0][0]['LongLeave']);    
    }

    
    public function get_attend_details($condition1){
        $maxDateAr=$this->Masjclrentry->query("SELECT MAX(AttandDate) AS MaxDate FROM Attandence");
        $maxdate=$maxDateAr[0][0]['MaxDate'];
        $start= date('Y-m-d', strtotime('-3 day', strtotime($maxdate)));
        
        $data  =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','EmpCode'),'conditions'=>$condition1));
        $ts=0;
        $tl=0;
        foreach($data as $val){
            $AttendArr=$this->Masjclrentry->query("
            SELECT
            IF(SUM(IF(`Status` ='A',1,0)) > 3,1,0)  AS Suspended,
            IF(SUM(IF(`Status` ='LWP',1,0)) > 0,1,0) AS LongLeave
            FROM `Attandence` 
            WHERE BranchName='{$val['Masjclrentry']['BranchName']}' AND EmpCode='{$val['Masjclrentry']['EmpCode']}'  AND DATE(AttandDate) BETWEEN '$start' AND '$maxdate'");
            
           $ts=$ts+$AttendArr[0][0]['Suspended'];
           $tl=$tl+$AttendArr[0][0]['LongLeave'];
        }  
        
        return array('Suspended'=>$ts,'LongLeave'=>$tl); 
    }
    
    
    
    
   
      
    
    
}
?>