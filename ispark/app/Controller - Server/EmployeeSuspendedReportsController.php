<?php
class EmployeeSuspendedReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','suspendeddetails','suspendedexport');
        
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
            $branch_name    =   $this->request->data['EmployeeSuspendedReports']['branch_name'];
           
            $conditoin=array('Status'=>1,'EmpLocation'=>'InHouse');
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            $data1          =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','CostCenter','EmpCode','EmpLocation'),'conditions'=>$conditoin,'order'=>'BranchName','group'=>'CostCenter'));
            $totalcount     =   $this->Masjclrentry->find('count',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'CostCenter'));
            
            $data=array();
            foreach($data1 as $row){
                $condition1=array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$row['Masjclrentry']['BranchName'],'CostCenter'=>$row['Masjclrentry']['CostCenter']);
                
                $ActiveCount  =   $this->Masjclrentry->find('count',array('conditions'=>$condition1));
                $AttSta=$this->get_attend_details($condition1);
                
                $Active=$ActiveCount;
                $suspen=$AttSta['Suspended'];
                $longle=$AttSta['LongLeave'];
                
                if($suspen > 0){
                    $Active=$ActiveCount-$suspen;
                }
                if($longle > 0){
                    if($suspen >= $longle){
                        $suspen=$suspen-$longle;
                    }
                    else{
                      $Active=$ActiveCount-$longle;  
                    }
                }

                $data[]=array(
                    'BranchName'=>$row['Masjclrentry']['BranchName'],
                    'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                    'Active'=>$Active,
                    'Suspended'=>$suspen,
                    'LongLeave'=>$longle,
                    'Total'=>($Active+$suspen+$longle),
                );
            }

            if($this->request->data['Submit'] =="Search"){
                $this->set('branchname',$branch_name);
                $this->set('CostCenter',$CostCenter);
                $this->set('empmonth',$StartDate);
                $this->set('data',$data);
                $this->set('totalcount',$totalcount);
            }
            else{
                $this->layout='ajax';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=EmployeeSuspendedReport.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
               ?>
               <table border="1"  >            
                    <tr>
                        <th style="text-align: center;">SNo</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">CostCenter</th>
                        <th style="text-align: center;">Active</th>
                        <th style="text-align: center;">Suspended</th>
                        <th style="text-align: center;">LongLeave</th>
                        <th style="text-align: center;">Total</th>
                    </tr>
                             
                    <?php
                    $n=1; 
                    $Active=0;
                    $Suspended=0;
                    $LongLeave=0;
                    $Total=0;

                    foreach ($data as $val){
                    $Active=$Active+$val['Active'];
                    $Suspended=$Suspended+$val['Suspended'];
                    $LongLeave=$LongLeave+$val['LongLeave'];
                    $Total=$Total+$val['Total'];

                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo $val['Active'];?></td>
                        <td style="text-align: center;"><?php echo $val['Suspended'];?></td>
                        <td style="text-align: center;"><?php echo $val['LongLeave'];?></td>
                        <td style="text-align: center;"><?php echo $val['Total'];?></td>
                    </tr>
                    <?php }?>
                    
                    <tr>
                        <td colspan="2"></td>
                        <td style="text-align: center;"><strong>Total</strong></td>
                        <td style="text-align: center;"><strong><?php echo $Active;?></strong></td>
                        <td style="text-align: center;"><strong><?php echo $Suspended;?></strong></td>
                        <td style="text-align: center;"><strong><?php echo $LongLeave;?></strong></td>
                        <td style="text-align: center;"><strong><?php echo $Total;?></strong></td>
                    </tr>
                            
                    </table>
               <?php
               die;
                
            }
            
        }  
    }
    
    
    
    
    public function suspendeddetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){ 
          
            $conditoin=array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$_REQUEST['BranchName'],'CostCenter'=>$_REQUEST['CostCenter']);    
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','CostCenter','EmpCode','EmpName'),'conditions'=>$conditoin,'order'=>'EmpName'));
            
            if(!empty($data)){  
            ?>
            <input type="button" value="Export" onclick="suspendedexport('<?php echo $_REQUEST['BranchName'];?>','<?php echo $_REQUEST['CostCenter'];?>','<?php echo $_REQUEST['Status'];?>')" class="btn pull-right btn-primary btn-new" >
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:30px;">SNo</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">CostCenter</th>
                        <th style="text-align: center;">EmpCode</th>
                        <th >EmpName</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $sus=0;$lon=0; $n=1; foreach ($data as $row){
                        $empArr  =   $this->get_attend_emp($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['EmpCode'],$_REQUEST['Status']);
                        $sus     =   $empArr['Suspended'];
                        $lon     =   $empArr['LongLeave'];  
                    ?>
                    <?php if($_REQUEST['Status'] =="Total"){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="Suspended" && $sus !=0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="LongLeave" && $lon !=0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="Active" && $sus ==0 && $lon ==0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php }?>
                </tbody>   
            </table>
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
    }
    
   
    public function suspendedexport(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){ 
            
            $this->layout='ajax';
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=suspendeddetails.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            
            $conditoin=array('Status'=>1,'EmpLocation'=>'InHouse','BranchName'=>$_REQUEST['BranchName'],'CostCenter'=>$_REQUEST['CostCenter']);    
            $data   =   $this->Masjclrentry->find('all',array('fields'=>array('BranchName','CostCenter','EmpCode','EmpName'),'conditions'=>$conditoin,'order'=>'EmpName'));
            
            
            
            ?>
            <table border="1" >     
                <thead>
                    <tr>
                        <th style="text-align: center;">SNo</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">CostCenter</th>
                        <th style="text-align: center;">EmpCode</th>
                        <th >EmpName</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $sus=0;$lon=0; $n=1; foreach ($data as $row){
                        $empArr  =   $this->get_attend_emp($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['EmpCode'],$_REQUEST['Status']);
                        $sus     =   $empArr['Suspended'];
                        $lon     =   $empArr['LongLeave'];  
                    ?>
                    <?php if($_REQUEST['Status'] =="Total"){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="Suspended" && $sus !=0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="LongLeave" && $lon !=0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if($_REQUEST['Status'] =="Active" && $sus ==0 && $lon ==0){ ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['BranchName']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['CostCenter']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td><?php echo $row['Masjclrentry']['EmpName']?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php }?>
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