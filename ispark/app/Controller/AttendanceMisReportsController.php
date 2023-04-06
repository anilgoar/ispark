<?php
class AttendanceMisReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','BranchAttandIssueMaster','OldAttendanceIssue');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
       
        
        $this->Auth->allow('index','getcostcenter','CostCenterWiseExpDetails');
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){ 

            $branch_name    =   $this->request->data['AttendanceMisReports']['branch_name'];
            $CostCenter     =   trim($this->request->data['CostCenter']);
            $IssueType      =   $this->request->data['IssueType'];
            $ReportType     =   $this->request->data['ReportType'];
            $StartDate      =   $this->request->data['StartDate'];
            
            $y  =   date('Y',strtotime($StartDate));
            $m  =   date('m',strtotime($StartDate));
             
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
                   
            $headArr=array(
                'branch'=>array('Branch','CostCenter','Manpower','ForgotToPunch','NewJoining','Others','PowerFailure','SkinProblem','MTD'),
                'costcenter'=>array('Branch','CostCenter','Manpower','ForgotToPunch','NewJoining','Others','PowerFailure','SkinProblem','MTD'),
                'data'=>array('EmpCode','BiometricCode','EmpName','Branch','CostCenter','Date','Reason','OriginalStatus','ExpectedStatus','Remarks'), 
            );
            
            $fields=array('BranchName','CostCenter','EmpCode','BioCode','EmpName');
            
            if($ReportType =="branch"){
                $data1   =   $this->Masjclrentry->find('all',array('fields'=>$fields,'conditions'=>$conditoin,'order'=>'BranchName','group'=>'BranchName'));
            }
            else if($ReportType =="costcenter"){
                $data1   =   $this->Masjclrentry->find('all',array('fields'=>$fields,'conditions'=>$conditoin,'order'=>'BranchName','group'=>'CostCenter'));
            }
            else if($ReportType =="data"){
                $data1   =   $this->Masjclrentry->find('all',array('fields'=>$fields,'conditions'=>$conditoin,'order'=>'BranchName','group'=>'EmpCode'));
            }
           
            $data=array();
            $total=array();
            $Manpo=0;
            $ForgotToPunch=0;
            $NewJoining=0;
            $Others=0;
            $PowerFailure=0;
            $SkinProblem=0;
            $MTD=0;

            foreach($data1 as $row){
                if($ReportType =="branch"){
                    
                    if($CostCenter =="ALL"){
                        $condition_new=array('EmpLocation'=>'InHouse','BranchName'=>$row['Masjclrentry']['BranchName']);
                        $CostCenter_new="";
                    }
                    else{
                        $condition_new=array('EmpLocation'=>'InHouse','BranchName'=>$row['Masjclrentry']['BranchName'],'CostCenter'=>$CostCenter);
                        $CostCenter_new=$CostCenter;
                    }
                    
                    $Manpower  =   $this->Masjclrentry->find('count',array('conditions'=>$condition_new));
                    $expArr=$this->CostCenterWiseExpDetails($row['Masjclrentry']['BranchName'],$CostCenter_new,$y,$m,$IssueType);
                   
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$CostCenter,
                        'Manpower'=>$Manpower,
                        'ForgotToPunch'=>$expArr['ForgotToPunch'],
                        'NewJoining'=>$expArr['NewJoining'],
                        'Others'=>$expArr['Others'],
                        'PowerFailure'=>$expArr['PowerFailure'],
                        'SkinProblem'=>$expArr['SkinProblem'],
                        'MTD'=>$expArr['MTD'],
                    );
                    
                    $Manpo=$Manpo+$Manpower;
                    $ForgotToPunch=$ForgotToPunch+$expArr['ForgotToPunch'];
                    $NewJoining=$NewJoining+$expArr['NewJoining'];
                    $Others=$Others+$expArr['Others'];
                    $PowerFailure=$PowerFailure+$expArr['PowerFailure'];
                    $SkinProblem=$SkinProblem+$expArr['SkinProblem'];
                    $MTD=$MTD+$expArr['MTD'];
                    
                    
                    
                }
                else if($ReportType =="costcenter"){
                    
                    $Manpower  =   $this->Masjclrentry->find('count',array('conditions'=>array('EmpLocation'=>'InHouse','BranchName'=>$row['Masjclrentry']['BranchName'],'CostCenter'=>$row['Masjclrentry']['CostCenter'])));
                    $expArr=$this->CostCenterWiseExpDetails($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],$y,$m,$IssueType);
                   
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                        'Manpower'=>$Manpower,
                        'ForgotToPunch'=>$expArr['ForgotToPunch'],
                        'NewJoining'=>$expArr['NewJoining'],
                        'Others'=>$expArr['Others'],
                        'PowerFailure'=>$expArr['PowerFailure'],
                        'SkinProblem'=>$expArr['SkinProblem'],
                        'MTD'=>$expArr['MTD'],
                    );
                    
                    $Manpo=$Manpo+$Manpower;
                    $ForgotToPunch=$ForgotToPunch+$expArr['ForgotToPunch'];
                    $NewJoining=$NewJoining+$expArr['NewJoining'];
                    $Others=$Others+$expArr['Others'];
                    $PowerFailure=$PowerFailure+$expArr['PowerFailure'];
                    $SkinProblem=$SkinProblem+$expArr['SkinProblem'];
                    $MTD=$MTD+$expArr['MTD'];
                   
                }
                else if($ReportType =="data"){
                    if($IssueType =="new"){
                        $model="BranchAttandIssueMaster";
                    }
                    else{
                       $model="OldAttendanceIssue"; 
                    }
                    
                    $IssueArr  =   $this->$model->find('all',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'],'EmpCode'=>$row['Masjclrentry']['EmpCode'],'YEAR(AttandDate)'=>$y,'MONTH(AttandDate)'=>$m),'order'=>'EmpName'));
                    foreach($IssueArr as $v){
                        $data[]=array(
                            'EmpCode'=>$row['Masjclrentry']['EmpCode'],
                            'BiometricCode'=>$row['Masjclrentry']['BioCode'],
                            'EmpName'=>$row['Masjclrentry']['EmpName'],
                            'Branch'=>$row['Masjclrentry']['BranchName'],
                            'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                            'Date'=>date('d-m-Y',strtotime($v[$model]['AttandDate'])),
                            'Reason'=>$v[$model]['Reason'],
                            'OriginalStatus'=>$v[$model]['CurrentStatus'],
                            'ExpectedStatus'=>$v[$model]['ExpectedStatus'],
                            'Remarks'=>"Save By ".$v[$model]['SaveBy']." ".$v[$model]['CreateDate']." Approve First By ".$v[$model]['ApproveFirstBy']." ".$v[$model]['ApproveFirstDate']." Approve Second By ".$v[$model]['ApproveSecondBy']." ".$v[$model]['ApproveSecondDate'],
                        );
                    }
                    
                    
                }
            }
            
            $total=array(
                'Manpower'=>$Manpo,
                'ForgotToPunch'=>$ForgotToPunch,
                'NewJoining'=>$NewJoining,
                'Others'=>$Others,
                'PowerFailure'=>$PowerFailure,
                'SkinProblem'=>$SkinProblem,
                'MTD'=>$MTD
            );
            
            
            if($this->request->data['Submit'] =="Search"){
                $this->set('branchname',$branch_name);
                $this->set('CostCenter',$CostCenter);
                $this->set('ReportType',$ReportType);
                $this->set('IssueType',$IssueType);
                $this->set('empmonth',$StartDate);
                $this->set('headArr',$headArr);
                $this->set('data',$data); 
                $this->set('total',$total);
            }
            else{
                $this->layout='ajax';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=AttendanceMisReport.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
               ?>
               <table border="1"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">SrNo</th>
                            <?php foreach($headArr[$ReportType] as $hed){?>
                                <th style="text-align: center;"><?php echo $hed;?></th>
                            <?php }?>
                        </tr>
                    </thead>

                    <tbody> 
                        <?php $n=1;foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <?php foreach($headArr[$ReportType] as $hed){?>
                                <td style="text-align: center;"><?php echo $val[$hed];?></td>
                            <?php }?>
                        </tr>
                        <?php }?>
                        <?php if($ReportType !="data"){ ?>
                        <tr>
                            <td style="text-align: center;font-weight: bold;">TOTAL</td>
                            <?php foreach($headArr[$ReportType] as $hed){?>
                                <td style="text-align: center;"><?php echo $total[$hed];?></td>
                            <?php }?>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
               <?php
               die;
                
            }
            
        }  
    }
    
    
    public function CostCenterWiseExpDetails($BranchName,$CostCenter,$y,$m,$IssueType){
        if($CostCenter !=""){
            $data  =   $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpLocation'),'conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter)));
        }
        else{
          $data  =   $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpLocation'),'conditions'=>array('BranchName'=>$BranchName)));  
        }
        
        $ForgotToPunch=0;
        $NewJoining=0;
        $Others=0;
        $PowerFailure=0;
        $SkinProblem=0;
        foreach($data as $row){
            if($row['Masjclrentry']['EmpLocation']=="InHouse"){
                if($IssueType =="new"){
                    $ArrData    =   $this->BranchAttandIssueMaster->query("SELECT 
                    SUM(IF(IssueType ='Forgot To Punch',1,0)) AS ForgotToPunch,
                    SUM(IF(IssueType ='New Joining',1,0)) AS NewJoining,
                    SUM(IF(IssueType ='Others',1,0)) AS Others,
                    SUM(IF(IssueType ='Power Failure',1,0)) AS PowerFailure,
                    SUM(IF(IssueType ='Skin Problem',1,0)) AS SkinProblem
                    FROM `BranchWiseAttandanceIssue` 
                    WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND YEAR(AttandDate)='$y' AND MONTH(AttandDate)='$m';");
                }
                else{
                    $ArrData    =   $this->OldAttendanceIssue->query("SELECT 
                    SUM(IF(IssueType ='Forgot To Punch',1,0)) AS ForgotToPunch,
                    SUM(IF(IssueType ='New Joining',1,0)) AS NewJoining,
                    SUM(IF(IssueType ='Others',1,0)) AS Others,
                    SUM(IF(IssueType ='Power Failure',1,0)) AS PowerFailure,
                    SUM(IF(IssueType ='Skin Problem',1,0)) AS SkinProblem
                    FROM `old_attendance_issue` 
                    WHERE EmpCode='{$row['Masjclrentry']['EmpCode']}' AND YEAR(AttandDate)='$y' AND MONTH(AttandDate)='$m';");
                }

                $ForgotToPunch=$ForgotToPunch+$ArrData[0][0]['ForgotToPunch'];
                $NewJoining=$NewJoining+$ArrData[0][0]['NewJoining'];
                $Others=$Others+$ArrData[0][0]['Others'];
                $PowerFailure=$PowerFailure+$ArrData[0][0]['PowerFailure'];
                $SkinProblem=$SkinProblem+$ArrData[0][0]['SkinProblem'];
            }
        }
        
        $mtd=($ForgotToPunch+$NewJoining+$Others+$PowerFailure+$SkinProblem);
        
        return array(
            'ForgotToPunch'=>$ForgotToPunch,
            'NewJoining'=>$NewJoining,
            'Others'=>$Others,
            'PowerFailure'=>$PowerFailure,
            'SkinProblem'=>$SkinProblem,
            'MTD'=>$mtd,
        ); 
    }
    

    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $CostCenter=$_REQUEST['CostCenter'];
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    if($CostCenter ==$val){$Select="selected='selected'";}else{$Select="";}
                    echo "<option $Select value='$val'>$val</option>";
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