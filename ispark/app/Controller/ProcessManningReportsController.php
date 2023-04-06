<?php
class ProcessManningReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','EmployeeSourceMasters');
        
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
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){ 
           
            $branch_name    =   $this->request->data['ProcessManningReports']['branch_name'];
            $predate        =   date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
            $y              =   date('Y', strtotime('-1 day', strtotime(date('Y-m-d'))));
            $m              =   date('m', strtotime('-1 day', strtotime(date('Y-m-d'))));
            
            //$y              =   "2015";
            //$m              =   "11";
             
            $data1=$this->Masjclrentry->query("SELECT 
            cost_center AS CostCenter,
            SUM(IF(total_man_date IS NOT NULL,total_man_date,0)) AS ManDate,
            SUM(IF(shrinkage IS NOT NULL,shrinkage,0)) AS Shri,
            SUM(IF(attrition IS NOT NULL,attrition,0)) AS Attri,
            SUM(IF(target_mandate IS NOT NULL,target_mandate,0)) AS Target
            FROM `cost_master` 
            WHERE branch='$branch_name' AND YEAR(createdate)='$y' AND MONTH(createdate)='$m' AND active='1' GROUP BY cost_center;");
            
            $data=array();
            $total=array();
            $ManDate=0;
            $Shri=0;
            $Attri=0;
            $ActualAttri=0;
            $Target=0;
            $ActualMp=0;
            $LastPresent=0;
            
            foreach($data1 as $row){
               
                $emp_details=$this->emp_details($branch_name,$row['cost_master']['CostCenter'],$predate);
                
                $data[]=array(
                    'CostCenter'=>$row['cost_master']['CostCenter'],
                    'ManDate'=>$row[0]['ManDate'],
                    'Shri'=>$row[0]['Shri'],
                    'Attri'=>$row[0]['Attri'],
                    'ActualAttri'=>$emp_details['ActualAttr'],
                    'Target'=>$row[0]['Target'],
                    'ActualMp'=>$emp_details['Manpower'],
                    'LastPresent'=>$emp_details['LastPresent'],
                );

                $ManDate=$ManDate+$row[0]['ManDate'];
                $Shri=$Shri+$row[0]['Shri'];
                $Attri=$Attri+$row[0]['Attri'];
                $ActualAttri=$ActualAttri+$emp_details['ActualAttr'];
                $Target=$Target+$row[0]['Target'];
                $ActualMp=$ActualMp+$emp_details['Manpower'];
                $LastPresent=$LastPresent+$emp_details['LastPresent'];
            }
            
            $total=array(
                'TotalManDate'=>$ManDate,
                'TotalShri'=>$Shri,
                'TotalAttri'=>$Attri,
                'TotalActualAttri'=>$ActualAttri,
                'TotalTarget'=>$Target,
                'TotalActualMp'=>$ActualMp,
                'TotalLastPresent'=>$LastPresent
            );
            
            if($this->request->data['Submit'] =="Show"){
                $this->set('branchname',$branch_name);
                $this->set('data',$data); 
                $this->set('total',$total);
            }
            else{
                $this->layout='ajax';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=ProcessMiningReport.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
               ?>
               <table border="1"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">SrNo</th>
                            <th style="text-align: center;">CostCenter</th>
                            <th style="text-align: center;">ManDate</th>
                            <th style="text-align: center;">Shri</th>
                            <th style="text-align: center;">Attri</th>
                            <th style="text-align: center;">Actual Attr</th>
                            <th style="text-align: center;">Target</th>
                            <th style="text-align: center;">ActualMP</th>
                            <th style="text-align: center;">LastPresent</th>
                        </tr>
                    </thead>

                    <tbody> 
                        <?php $n=1;foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['ManDate'];?></td>
                            <td style="text-align: center;"><?php echo $val['Shri'];?></td>
                            <td style="text-align: center;"><?php echo $val['Attri'];?></td>
                            <td style="text-align: center;"><?php echo $val['ActualAttri'];?></td>
                            <td style="text-align: center;"><?php echo $val['Target'];?></td>
                            <td style="text-align: center;"><?php echo $val['ActualMp'];?></td>
                            <td style="text-align: center;"><?php echo $val['LastPresent'];?></td>
                        </tr>
                        <?php }?>

                        <tr>
                            <td></td>
                            <td style="text-align: center;font-weight: bold;">TOTAL</td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalManDate'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalShri'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalAttri'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalActualAttri'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalTarget'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalActualMp'];?></td>
                            <td style="text-align: center;font-weight: bold;"><?php echo $total['TotalLastPresent'];?></td>
                        </tr>

                    </tbody>   
                </table>
               <?php
               die;
                
            }
            
        }  
    }
    
    
    public function emp_details($BranchName,$CostCenter,$predate){
        $data  =   $this->Masjclrentry->find('all',array('fields'=>array('EmpCode','EmpLocation','Desgination'),'conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter)));
        
        $Year   =   date('Y',strtotime($predate));
        $Month  =   date('m',strtotime($predate));
        $OpenDate = "$Year-$Month-01";
        
        $LastPresent=0;
        $Manpower=0;
        $opening=0;
        $Joined=0;
        $LeftE=0;
        $Closing=0;
        foreach($data as $row){
            if($row['Masjclrentry']['EmpLocation']=="InHouse"){
                if($row['Masjclrentry']['Desgination']=="EXECUTIVE - VOICE" || $row['Masjclrentry']['Desgination']=="SR. EXECUTIVE - VOICE"){

                    $ArrData    =   $this->Masattandance->query("SELECT 
                    COUNT(Id) AS LastPresent
                    FROM `Attandence`
                    WHERE BranchName='$BranchName' AND EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DATE(AttandDate)='$predate' AND `Status` IS NOT NULL AND `Status` !='A';");  
                    
                    $total=$total+$ArrData[0][0]['LastPresent']; 
                    $Manpower++;
                    
                    $AttriArr=$this->EmployeeSourceMasters->query("SELECT 
                    SUM(IF(YEAR(DOJ)<YEAR('$OpenDate') AND `Status`= 1,1,IF(MONTH(DOJ)<MONTH('$OpenDate') AND `Status`= 1,1,0))) opening,
                    SUM(IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=1,1,0)) Joined,
                    SUM(IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=0,1,0)) LeftE,
                    SUM(IF(`Status`=1,1,0)) Closing
                    FROM masjclrentry jclr WHERE 1=1 AND EmpCode='{$row['Masjclrentry']['EmpCode']}' AND DATE(DOJ)<=LAST_DAY('$OpenDate')");
                      
                    $opening=$opening+$AttriArr[0][0]['opening'];
                    $Joined=$Joined+$AttriArr[0][0]['Joined'];
                    $LeftE=$LeftE+$AttriArr[0][0]['LeftE'];
                    $Closing=$Closing+$AttriArr[0][0]['Closing'];
                }  
            }
        }
        
        $opens=$opening;
        $joins=$Joined;
        $lefts=$LeftE;
        $close=$Closing-$LeftE;
        $total=($close+$opens)/2;
        $perse=($lefts*100)/$total;
        $ActualAttr= round($perse,2);

        return array('ActualAttr'=>$ActualAttr,'LastPresent'=>$LastPresent,'Manpower'=>$Manpower);
    }
  
}
?>