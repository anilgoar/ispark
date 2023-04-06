<?php
class EmployeeSourceMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','EmployeeSourceMasters');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource','source_export','show_detail','export_detail','export_detail_branch','show_detail_branch','attrition_export','show_detail_attr','export_detail_attr');
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
        
        $DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branchName)));
        $this->set('DataArr',$DataArr);
        
        if($this->request->is('Post')){ 
            $branch_name    =   $this->request->data['EmployeeSourceMasters']['branch_name'];
            $SourceType     =   $this->request->data['SourceType'];
            $SourceName     =   trim($this->request->data['SourceName']);
            
            if($this->request->data['Submit'] =="Submit"){
                $data=array(
                    'BranchName'=>$branch_name,
                    'SourceType'=>$SourceType,
                    'SourceName'=>$SourceName,
                );

                $cnt=$this->EmployeeSourceMasters->find('count',array('conditions'=>$data));
                if($cnt > 0){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your data already exist in database.</span>');
                    $this->redirect(array('action'=>'index'));   
                }
                else{
                    $this->EmployeeSourceMasters->save($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data save successfully.</span>');
                    $this->redirect(array('action'=>'index'));   
                }
            }
            else{
                $DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branch_name)));
                $this->set('DataArr',$DataArr);
            } 
        }  
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->EmployeeSourceMasters->query("DELETE FROM `employee_source_masters` WHERE Id='{$_REQUEST['id']}'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data delete successfully.</span>');
            $this->redirect(array('action'=>'index')); 
        }
     
    }
    
     public function source_export()
    {
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branchName)));
        $this->set('DataArr',$DataArr);
        
        if($this->request->is('Post'))
            { 
          
            
            $branch_name    =   $this->request->data['EmployeeSourceMasters']['branch_name'];
            //print_r($this->request->data); exit;
            if($branch_name!='ALL')
            {
               $branchQr = " BranchName='$branch_name' and "; 
            }
           $FromDate = date('Y-m-d',strtotime($this->request->data['FromDate']));
           $ToDate = date('Y-m-d',strtotime($this->request->data['ToDate']));
           $this->set('FromDate',$FromDate);
           $this->set('ToDate',$ToDate);
           $this->set("OldFrom",$this->request->data['FromDate']);
           $this->set("OldTo",$this->request->data['ToDate']);
           $status = $this->request->data['status'];
           if($status !='ALL')
           {
               $statusQr = " and Status='$status'";
           }
           $this->set('status',$this->request->data['status']);
            //$DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branch_name))); 
            $Sel = "SELECT BranchName,EmpCode,SourceType,DATEDIFF(CURDATE(),DOJ) Days,DOJ FROM masjclrentry EmployeeSourceMasters WHERE $branchQr  date(DOJ) BETWEEN '$FromDate' AND '$ToDate' $statusQr";  
            $DataArr=$this->EmployeeSourceMasters->query($Sel);
            
            foreach($DataArr as $val)
            {
                //print_r($dt); exit;
                $BranchArr[] = $val['EmployeeSourceMasters']['BranchName'];
                $SourceTypeArr[] = $val['EmployeeSourceMasters']['SourceType'];
                
                if($val['0']['Days']<30)
                {
                    $data[$val['EmployeeSourceMasters']['BranchName']][$val['EmployeeSourceMasters']['SourceType']]['0-30'] +=1;
                }
                else if($val['0']['Days']>30 && $val['0']['Days']<90)
                {
                    $data[$val['EmployeeSourceMasters']['BranchName']][$val['EmployeeSourceMasters']['SourceType']]['31-90'] +=1;
                }
                else if($val['0']['Days']>90 && $val['0']['Days']<180)
                {
                    $data[$val['EmployeeSourceMasters']['BranchName']][$val['EmployeeSourceMasters']['SourceType']]['90-180'] +=1;
                }
                else if($val['0']['Days']>180)
                {
                    $data[$val['EmployeeSourceMasters']['BranchName']][$val['EmployeeSourceMasters']['SourceType']]['180Above'] +=1;
                }
            }
            
            $BranchArr = array_unique($BranchArr);
            sort($BranchArr);
            $SourceTypeArr = array_unique($SourceTypeArr);
            sort($SourceTypeArr);

            if($this->request->data['Submit'] =="Show"){
                $this->set('BranchArr',$BranchArr);
                $this->set('SourceArr',$SourceTypeArr);
                $this->set('DataArr',$data);
            }
            else if($this->request->data['Submit'] =="Export"){
                $this->layout='ajax';
                
                $BranchArr=$BranchArr;
                $SourceArr=$SourceTypeArr;
                $DataArr=$data;
                
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=EmpSourceExport.xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                
                ?>
                <table border="1"  >     
                    <thead>
                                <tr>
                                    <th style="text-align: center;width:50px;">SNo.</th>
                                    <th style="text-align: center;width:150px;">Branch Name</th>
                                    <th style="text-align: center;">Source</th>
                                    <th style="text-align: center;">0-30</th>
                                    <th style="text-align: center;">30-90</th>
                                    <th style="text-align: center;">90-180</th>
                                    <th style="text-align: center;">180Above</th>
                                    <th style="text-align: center;">Total</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $inswise=array(); $n=1; $days=array("0-30","31-90","91-180","180Above"); 
                                foreach ($BranchArr as $br){?>
                                    <?php foreach($SourceArr as $source) {?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $n++;?></td>
                                        <td style="text-align: center;"><?php echo $br;?></td>
                                        <td style="text-align: center;"><?php echo $source;?></td>
                                    <?php $t1=0; foreach($days as $d){?>
                                        <td style="text-align: center;" >
                                            <?php if(!empty($DataArr[$br][$source][$d])){
                                                $t1=$t1+$DataArr[$br][$source][$d];
                                                
                                                $inswise[$d]+=$DataArr[$br][$source][$d];
                                            ?>
                                                <?php echo $DataArr[$br][$source][$d];  ?>
                                            <?php }else{echo 0;} ?>    
                                        </td>    
                                    <?php }?>
                                        <td style="text-align:center;" ><?php if($t1 !=0){ echo $t1;}else{echo 0;}?></td>
                                    </tr>
                                        <?php } ?>
                                
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;" ><strong>Total</strong></td>
                                    <?php $ft=0; foreach($days as $d){$ft=$ft+$inswise[$d];?>
                                    <td style="text-align: center;" ><strong><?php if($inswise[$d] !=""){echo $inswise[$d];}else{echo 0; }?></strong></td> 
                                    <?php }?>
                                    <td style="text-align:center;"><strong><?php echo $ft;?></strong></td>
                                </tr>
                            </tbody>   
                    </table>
                <?php
                die;
            }
            
            
            
            
            
            
            
             
        }  
    }
    
    public function show_detail()
    {
        $data = $this->request->data;
        $Branch = $data['Branch'];
        $Source = $data['Source'];
        $Day = $data['Day'];
        $FromDate = $data['FromDate'];
        $ToDate = $data['ToDate'];
        $status = $this->request->data['status'];
        if($status !='ALL')
        {
            $statusQr = " and Status='$status'";
        }
           
        if($Day=='0-30')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) between 0 and 30';
        }
        else if($Day=='30-90')
        {
            $NewDay =  ' and DATEDIFF(CURDATE(),DOJ) between 31 and 90';
        }
        else if($Day=='91-180')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) between 91 and 180';
        }
        else if($Day=='180Above')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) > 181 ';
        }
            
        
         $Sel = "SELECT BranchName,EmpCode,EmpName,Source,CostCenter,SourceType,DATEDIFF(CURDATE(),DOJ) Days,DOJ FROM masjclrentry EmployeeSourceMasters WHERE BranchName='$Branch' and SourceType='$Source' and date(DOJ) between '$FromDate' and '$ToDate' $NewDay $statusQr";  
         $DataArr=$this->EmployeeSourceMasters->query($Sel);
        if(!empty($DataArr)){?>
            <input type="button" onclick="export_details_source('<?php echo $Branch;?>','<?php echo $Source;?>','<?php echo $Day;?>','<?php echo $FromDate;?>','<?php echo $ToDate;?>','<?php echo $status;?>');" value="Export" class="btn pull-right btn-primary btn-new">
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:40px;">SNo</th>
                        <th style="text-align: center;width:100px;">Emp Code</th>
                        <th>Emp Name</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">Cost Center</th>
                        <th style="text-align: center;">DOJ</th>
                        <th style="text-align: center;">Referred By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n=1;
                        foreach($DataArr as $d)
                        {
                            echo "<tr>";
                                echo "<td style='text-align: center;'>".$n++.'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['EmpCode'].'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['EmpName'].'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['BranchName'].'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['CostCenter'].'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['DOJ'].'</td>';
                                echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['Source'].'</td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
             </table>              
       <?php }
        else
        {
            echo "Record Not Found";
        }
        
        exit;
        
        
        
        
        
        
        
        
        
    }
    
    public function export_detail(){
        $this->layout='ajax';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=exportdetails.xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 
       
        
        $Branch = $_REQUEST['Branch'];
        $Source = $_REQUEST['Source'];
        $Day = $_REQUEST['Day'];
        $FromDate = $_REQUEST['FromDate'];
        $ToDate = $_REQUEST['ToDate'];
        $status = $_REQUEST['status'];
       
        if($status !='ALL')
        {
            $statusQr = " and Status='$status'";
        }
           
        if($Day=='0-30')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) between 0 and 30';
        }
        else if($Day=='30-90')
        {
            $NewDay =  ' and DATEDIFF(CURDATE(),DOJ) between 31 and 90';
        }
        else if($Day=='91-180')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) between 91 and 180';
        }
        else if($Day=='180Above')
        {
            $NewDay = ' and DATEDIFF(CURDATE(),DOJ) > 181 ';
        }
            
        
         $Sel = "SELECT BranchName,EmpCode,EmpName,Source,CostCenter,SourceType,DATEDIFF(CURDATE(),DOJ) Days,DOJ FROM masjclrentry EmployeeSourceMasters WHERE BranchName='$Branch' and SourceType='$Source' and date(DOJ) between '$FromDate' and '$ToDate' $NewDay $statusQr";
         $DataArr=$this->EmployeeSourceMasters->query($Sel);
         
        
         
        ?>
            
        <table border="1">     
            <thead>
                <tr>
                    <th style="text-align: center;width:40px;">SNo</th>
                    <th style="text-align: center;width:100px;">Emp Code</th>
                    <th>Emp Name</th>
                    <th style="text-align: center;">Branch</th>
                    <th style="text-align: center;">Cost Center</th>
                    <th style="text-align: center;">DOJ</th>
                    <th style="text-align: center;">Referred By</th>
                </tr>
            </thead>
            <tbody>
                <?php $n=1;
                foreach($DataArr as $d){
                    echo "<tr>";
                        echo "<td style='text-align: center;'>".$n++.'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['EmpCode'].'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['EmpName'].'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['BranchName'].'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['CostCenter'].'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['DOJ'].'</td>';
                        echo "<td style='text-align: center;'>".$d['EmployeeSourceMasters']['Source'].'</td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
         </table>              
        <?php 
        die; 
        
           
    }
    
    
    public function show_detail_branch()
    {
        $data = $this->request->data;
        $Branch = $data['Branch'];
        $Source = $data['Source'];
        $FromDate = $data['FromDate'];
        $ToDate = $data['ToDate'];
        $status = $this->request->data['status'];
        if($status !='ALL')
        {
            $statusQr = " and Status='$status'";
        }
           
        
            
        
         $Sel = "SELECT BranchName,EmpCode,EmpName,Source,CostCenter,SourceType,DATEDIFF(CURDATE(),DOJ) Days,DOJ FROM masjclrentry EmployeeSourceMasters WHERE BranchName='$Branch' and SourceType='$Source' and date(DOJ) between '$FromDate' and '$ToDate' $statusQr";  
         $DataArr=$this->EmployeeSourceMasters->query($Sel);
        if(!empty($DataArr))
        {?>
            <input type="button" onclick="export_details_source_branch('<?php echo $Branch;?>','<?php echo $Source;?>','<?php echo $FromDate;?>','<?php echo $ToDate;?>','<?php echo $status;?>');" value="Export" class="btn pull-right btn-primary btn-new">
             <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:40px;">SNo</th>
                        <th style="text-align: center;width:100px;">Emp Code</th>
                        <th >Emp Name</th>
                        <th <th style="text-align: center;">Branch</th>
                        <th <th style="text-align: center;">Cost Center</th>
                        <th <th style="text-align: center;">DOJ</th>
                        <th <th style="text-align: center;">Referred By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n=1;
                        foreach($DataArr as $d)
                        {
                            echo "<tr>";
                                echo "<td style='text-align:center;' >".$n++.'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['EmpCode'].'</td>';
                                echo "<td>".$d['EmployeeSourceMasters']['EmpName'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['BranchName'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['CostCenter'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['DOJ'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['Source'].'</td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
             </table>              
       <?php }
        else
        {
            echo "Record Not Found";
        }
        
        exit;
    }
    
    
    public function export_detail_branch(){
        $this->layout='ajax';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=exportdetailsbranch.xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 
        
        $Branch = $_REQUEST['Branch'];
        $Source = $_REQUEST['Source'];
        $FromDate = $_REQUEST['FromDate'];
        $ToDate = $_REQUEST['ToDate'];
        $status = $_REQUEST['status'];
        
        if($status !='ALL')
        {
            $statusQr = " and Status='$status'";
        }
           
        
            
        
         $Sel = "SELECT BranchName,EmpCode,EmpName,Source,CostCenter,SourceType,DATEDIFF(CURDATE(),DOJ) Days,DOJ FROM masjclrentry EmployeeSourceMasters WHERE BranchName='$Branch' and SourceType='$Source' and date(DOJ) between '$FromDate' and '$ToDate' $statusQr";  
         $DataArr=$this->EmployeeSourceMasters->query($Sel);
       
        ?>
             <table border="1"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:40px;">SNo</th>
                        <th style="text-align: center;width:100px;">Emp Code</th>
                        <th>Emp Name</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">Cost Center</th>
                        <th style="text-align: center;">DOJ</th>
                        <th style="text-align: center;">Referred By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n=1;
                        foreach($DataArr as $d)
                        {
                            echo "<tr>";
                                echo "<td style='text-align:center;' >".$n++.'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['EmpCode'].'</td>';
                                echo "<td>".$d['EmployeeSourceMasters']['EmpName'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['BranchName'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['CostCenter'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['DOJ'].'</td>';
                                echo "<td style='text-align:center;'>".$d['EmployeeSourceMasters']['Source'].'</td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
             </table>              
       <?php 
       die;
        
       
    }
    
    public function attrition_export()
    {
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        $DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branchName)));
        $this->set('DataArr',$DataArr);
        
        if($this->request->is('Post'))
            { 
                $branch_name    =   $this->request->data['EmployeeSourceMasters']['branch_name'];
               //print_r($this->request->data); exit;
                
                    
                if($branch_name!='ALL')
                {
                   $branchQr = " and BranchName='$branch_name'  "; 
                }
                
                $Type = $this->request->data['Type'];
                $Month = $this->request->data['Month'];
                $Year = $this->request->data['Year'];
                $EmpType = $this->request->data['EmpType'];

                if($EmpType!='ALL')
                {
                    $EmpQr = " and EmpLocation='$EmpType' ";
                }

                $OpenDate = "$Year-$Month-01";
                
                if($Type=='Branch')
                {
                    $TypeQr = " group by BranchName order by BranchName";
                }
                else
                {
                    $TypeQr = " group by CostCenter,BranchName order by BranchName,CostCenter";
                }
           
                $this->set('Type',$this->request->data['Type']);
                $this->set('Month',$this->request->data['Month']);
                $this->set('Year',$this->request->data['Year']);
                $this->set('EmpType',$this->request->data['EmpType']);
                $this->set('Date',$OpenDate);
           
           
            //$DataArr=$this->EmployeeSourceMasters->find('all',array('conditions'=>array('BranchName'=>$branch_name))); 
           $Sel = "SELECT BranchName,CostCenter,ClientName,
SUM(IF(YEAR(DOJ)<YEAR('$OpenDate') AND `Status`= 1,1,IF(MONTH(DOJ)<MONTH('$OpenDate') AND `Status`= 1,1,0))) opening,
 SUM(IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=1,1,0)) Joined,
  SUM(IF(MONTH(ResignationDate)=MONTH('$OpenDate') AND YEAR(ResignationDate)=YEAR('$OpenDate') AND `Status`=0,1,0)) LeftE,
  SUM(IF(`Status`=1,1,0)) Closing
FROM masjclrentry jclr WHERE 1=1  $branchQr $EmpQr AND DATE(DOJ)<=LAST_DAY('$OpenDate') $TypeQr"; 


            $data=$this->EmployeeSourceMasters->query($Sel);
            
            if($this->request->data['Submit']=='Export')
                {
                $fileName = "Attrition_Report".date('Y_m_d_H_i_s');
                    header("Content-Type: application/vnd.ms-excel; name='excel'");
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=$fileName.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo '<table class = "table table-striped table-hover  responstable" border="2"  > ';
                    echo '<thead>
                                <tr>
                                    <th style="text-align: center;width:50px;">SNo</th>
                                    <th style="text-align: center;width:150px;">BranchName</th>';
                                    if($Type=='CostCenter') { 
                                    echo '<th>CostCenter</th>';
                                    } 
                                    echo '<th>Opening</th>
                                    <th>Joined</th>
                                    <th>Left</th>
                                    <th>Closing</th>
                                    <th>Attrition</th>
                                </tr>
                            </thead>';
                    echo '<tbody>';
                    
                    $total_opens=0;
                    $total_joins=0;
                    $total_lefts=0;
                    $total_close=0;
                    $total_perse=0;
                    
                    $n=1;
                    
                    foreach ($data as $dt)
                    {
                        
                        $opens=$dt['0']['opening'];
                        $joins=$dt['0']['Joined'];
                        $lefts=$dt['0']['LeftE'];
                        $close=$dt['0']['Closing']-$dt['0']['LeftE'];
                        $total=($close+$opens)/2;
                        $perse=($lefts*100)/$total;
                        
                        $total_opens=$total_opens+$opens;
                        $total_joins=$total_joins+$joins;
                        $total_lefts=$total_lefts+$lefts;
                        $total_close=$total_close+$close;
                        $total_perse=$total_perse+$perse;
                        
                        
                        echo '<tr><td style="text-align: center;">'.$n++.'</td>';
                        echo '<td style="text-align: center;">'.$dt['jclr']['BranchName'].'</td>';
                        if($Type=='CostCenter') 
                        { 
                            echo '<th>CostCenter</th>';
                        } 
                        echo '<td style="text-align: center;">'.$opens.'</td>';
                        echo '<td style="text-align: center;">'.$joins.'</td>';
                        echo '<td style="text-align: center;">'.$lefts.'</td>';
                        echo '<td style="text-align: center;">'.$close.'</td>';
                        echo '<td style="text-align: center;">'.round($perse,2).'%</td></tr>';
                    }
                    
                    echo '<tr>';
                    echo '<td style="text-align: center;font-weight:bold;">Total</td>';
                    echo '<td></td>';
                    if($Type=='CostCenter') { 
                    echo '<td></td>';
                    }
                    
                    echo '<td style="text-align: center;">'.$total_opens.'</td>';
                    echo '<td style="text-align: center;">'.$total_joins.'</td>';
                    echo '<td style="text-align: center;">'.$total_lefts.'</td>';
                    echo '<td style="text-align: center;">'.$total_close.'</td>';
                    echo '<td style="text-align: center;">'.round($total_perse,2).'</td>';
                    
                    echo '</tr>';
                    
                    echo '</tbody></table>';
                    
                    
                    exit;
                }
            
            $BranchArr = array_unique($BranchArr);
            sort($BranchArr);
            $SourceTypeArr = array_unique($SourceTypeArr);
            sort($SourceTypeArr);
            $this->set('BranchArr',$BranchArr);
            $this->set('SourceArr',$SourceTypeArr);
            $this->set('DataAr',$data);
            //print_r($data); exit;
             
        }  
    }
    
    public function show_detail_attr()
    {
                $branch_name    =   $this->request->data['Branch'];
                $CostCenter = $this->request->data['CostCenter'];
                $Type = $this->request->data['Type'];
                $OpenDate = $this->request->data['Date'];
                $EmpType = $this->request->data['EmpType'];
                $status = $this->request->data['status'];
                $exportQr = base64_encode($branch_name.'##'.$CostCenter.'##'.$Type.'##'.$OpenDate.'##'.$EmpType.'##'.$status);
                
                if($branch_name!='ALL')
                {
                   $branchQr = " and BranchName='$branch_name'  "; 
                }
                $EmpType = $this->request->data['EmpType'];

                if($EmpType!='ALL')
                {
                    $EmpQr = " and EmpLocation='$EmpType' ";
                }
                
                if($Type=='Branch')
                {
                    $TypeQr = " order by BranchName";
                }
                else
                {
                    $TypeQr = " order by BranchName,CostCenter";
                    $CostCenterQr = " and CostCenter='$CostCenter'";
                }
           
                if($status=='opening')
                {
                    $statusQr = " AND DATE(DOJ)<=LAST_DAY('$OpenDate') and IF(YEAR(DOJ)<YEAR('$OpenDate') AND `Status`= 1,true,IF(MONTH(DOJ)<MONTH('$OpenDate') AND `Status`= 1,true,false))";
                }
                else if($status=='Joined')
                {
                    $statusQr = " and IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=1,true,false)";
                }
                else if($status=='LeftE')
                {
                    $statusQr = " and IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=0,true,false)";
                }
                else if($status=='Closing')
                {
                    $statusQr = " and  `Status`=1";
                }
                
                
        
         $Sel = "SELECT *
FROM masjclrentry jclr WHERE 1=1  $branchQr $CostCenterQr $EmpQr  $statusQr $TypeQr"; 
         $data=$this->EmployeeSourceMasters->query($Sel);
        if(!empty($data))
        {?>
             <table class = "table table-striped table-hover  responstable"  >  
                 
                <thead>
                    <tr>
                        <td colspan="8" style="text-align: center;"><a href="<?php echo $this->webroot;?>EmployeeSourceMasters/export_detail_attr?qrt=<?php echo $exportQr;?>" class="btn pull-left btn-primary btn-new">Export</a></td>
                </tr>
                    <tr>
                        <th style="text-align: center;width:50px;">SNo</th>
                        <th style="text-align: center;">Cost Center</th>
                        <th style="text-align: center;width:150px;">Emp Code</th>
                        <th style="text-align: center;">DOJ</th>
                        <th style="text-align: center;">Designation</th>
                        <th style="text-align: center;">Department</th>
                        <th style="text-align: center;">Source Type</th>
                        <th style="text-align: center;">Source</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $n=1;
                        foreach($data as $d)
                        {
                            echo "<tr>";
                                echo "<td style='text-align:center;' >".$n++.'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['CostCenter'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['EmpCode'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['DOJ'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['Desgination'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['Dept'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['SourceType'].'</td>';
                                echo "<td style='text-align:center;'>".$d['jclr']['Source'].'</td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                
                
             </table>              
       <?php }
        else
        {
            echo "Record Not Found";
        }
        
        exit;
    }
    
    public function export_detail_attr()
    {
               $requestData = base64_decode($this->params->query['qrt']);
                $requestData = explode('##',$requestData);
                $branch_name    =   $requestData[0];
                $CostCenter = $requestData[1];
                $Type = $requestData[2];
                $OpenDate = $requestData[3];
                $EmpType = $requestData[4];
                $status = $requestData[5];
                
                if($branch_name!='ALL')
                {
                   $branchQr = " and BranchName='$branch_name'  "; 
                }
                //$EmpType = $this->request->data['EmpType'];

                if($EmpType!='ALL')
                {
                    $EmpQr = " and EmpLocation='$EmpType' ";
                }
                
                if($Type=='Branch')
                {
                    $TypeQr = " order by BranchName";
                }
                else
                {
                    $TypeQr = " order by BranchName,CostCenter";
                    $CostCenterQr = " and CostCenter='$CostCenter'";
                }
           
                if($status=='opening')
                {
                    $statusQr = " AND DATE(DOJ)<=LAST_DAY('$OpenDate') and IF(YEAR(DOJ)<YEAR('$OpenDate') AND `Status`= 1,true,IF(MONTH(DOJ)<MONTH('$OpenDate') AND `Status`= 1,true,false))";
                }
                else if($status=='Joined')
                {
                    $statusQr = " and IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=1,true,false)";
                }
                else if($status=='LeftE')
                {
                    $statusQr = " and IF(MONTH(DOJ)=MONTH('$OpenDate') AND YEAR(DOJ)=YEAR('$OpenDate') AND `Status`=0,true,false)";
                }
                else if($status=='Closing')
                {
                    $statusQr = " and  `Status`=1";
                }
                
                
        
         $Sel = "SELECT *
FROM masjclrentry jclr WHERE 1=1  $branchQr $CostCenterQr $EmpQr  $statusQr $TypeQr"; 
         $data=$this->EmployeeSourceMasters->query($Sel);
        if(!empty($data))
        {?>
             <table class = "table table-striped table-hover  responstable" border="2" >  
                 
                <thead>
                    <tr>
                        <th style="text-align: center;width:50px;">SNo</th>
                        <th>Cost Center</th>
                        <th style="text-align: center;width:150px;">Emp Code</th>
                        <th>DOJ</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Source Type</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $n=1; 
                        foreach($data as $d)
                        {
                            echo "<tr>";
                                echo "<td>".$n++.'</td>';
                                echo "<td>".$d['jclr']['CostCenter'].'</td>';
                                echo "<td>".$d['jclr']['EmpCode'].'</td>';
                                echo "<td>".$d['jclr']['DOJ'].'</td>';
                                echo "<td>".$d['jclr']['Desgination'].'</td>';
                                echo "<td>".$d['jclr']['Dept'].'</td>';
                                echo "<td>".$d['jclr']['SourceType'].'</td>';
                                echo "<td>".$d['jclr']['Source'].'</td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                
                
             </table>              
       <?php }
        else
        {
            echo "Record Not Found";
        }
        $fileName = "Attrition_Report_Export".date('Y_m_d_H_i_s');
                    header("Content-Type: application/vnd.ms-excel; name='excel'");
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=$fileName.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
        exit;
    }
    
    
}
?>