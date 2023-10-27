<?php
class LeaveApprovalController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','LeaveManagementMaster','LeaveRightsMaster','FieldAttendanceMaster','User','LeaveApproval');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('upload_leave','all_emp_leave_details','get_emp_leave_details','index','leaveentry',
                'leaveapproval','discardapprovedleave','leavedetails','get_emp','get_gender','get_leave_details',
                'exist_attendance','check_date','validate_leave_with_date','validate_el','export_leave');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    // public function index(){
    //     $this->layout='home';
    //     $Roles=array();
    //     $branch_name = $this->Session->read('branch_name');
    //     $dataArr = $this->LeaveRightsMaster->find('first',array('fields'=>array('Roles'),'conditions'=>array('BranchName'=>$branch_name,'Uid'=>$this->Session->read('userid'))));
    //     if(!empty($dataArr)){
    //         $Roles=  explode(',', $dataArr['LeaveRightsMaster']['Roles']); 
    //     }
    //     $this->set('LeaveRights',$Roles);  
    // }
    
    
    public function isValidDate($date)
    {
        if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\\/(0[1-9]|1[0-2])\\/[0-9]{4}$/",$date))
        {
            return  true;
        }else{
            return false;
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
            
            $branch_name    =   $this->request->data['LeaveManagements']['branch_name'];
            $EmpCode        =   $this->request->data['EmpNameCode'];
            $FromDate       =   date("Y-m-d",strtotime($this->request->data['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($this->request->data['ToDate']));
            $LeaveFor       =   $this->request->data['LeaveFor'];
            $LeaveType      =   $this->request->data['LeaveType'];
            $CL             =   $this->request->data['CL'];
            $ML             =   $this->request->data['ML'];
            $LWP            =   $this->request->data['LWP'];
            $EL             =   $this->request->data['EL'];
            $PTRL           =   isset($this->request->data['PTRL'])?$this->request->data['PTRL']:NULL;
            $MTRL           =   isset($this->request->data['MTRL'])?$this->request->data['MTRL']:NULL;
            $Purpose        =   $this->request->data['Purpose'];
            $AddDurLeave    =   $this->request->data['AddDurLeave'];
            $ContactNo      =   $this->request->data['ContactNo'];
              
                //$exist=$this->LeaveManagementMaster->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(LeaveTo) >='=>$FromDate)));

                $exist=$this->LeaveApproval->query("SELECT * FROM `leave_approval` 
                WHERE 
                BranchName='$branch_name' 
                AND EmpCode='$EmpCode' 
                AND DATE(LeaveTo) >='$FromDate' and DATE(LeaveTo) <='$ToDate'
                AND (`Status` IS NULL OR `Status`='Approved') limit 1");
              
                $TotElArr=$this->LeaveManagementMaster->query("
                SELECT COUNT(Id) AS TotalEl FROM `leave_approval` 
                WHERE 
                BranchName='$branch_name' 
                AND EmpCode='$EmpCode' 
                AND  YEAR(LeaveTo) = YEAR(CURDATE()) 
                AND LeaveType='EL' 
                AND (`Status` IS NULL OR `Status`='Approved')
                AND (`EL` IS NOT NULL OR `EL`!='' OR `EL`!='0')
                ");
                
                //$AtArr=$this->Masattandance->find('first',array('fields'=>array('Status'),'conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode,'date(AttandDate)'=>$FromDate)));
                //$CurrentStatus=$AtArr['Masattandance']['Status'];
                
                $exis4=$this->Masattandance->query("SELECT * FROM `Attandence` WHERE BranchName='$branch_name' AND EmpCode='$EmpCode' AND DATE(AttandDate) BETWEEN '$FromDate' AND '$ToDate' order by DATE(AttandDate);");
            
                //$LeaveFor="Full Day" &&
                
                $PresentAr=array();
                $HafPresAr=array();
                $FulPresAr=array();
                $AbsentsAr=array();
                foreach($exis4 as $row){
                    
                    if($row['Attandence']['Status'] =="P"){
                        $PresentAr[]=date('d M',strtotime($row['Attandence']['AttandDate']));
                    }
                    else if($LeaveFor=="Full Day" && $row['Attandence']['Status'] !="A" ){
                        if($row['Attandence']['Status'] !="P"){
                            $HafPresAr[]=date('d M',strtotime($row['Attandence']['AttandDate']));
                        }
                    }
                    /*
                    else if($LeaveFor =="Half Day" && $row['Attandence']['Status'] !="P" ){
                        $FulPresAr[]=date('d M',strtotime($row['Attandence']['AttandDate']));
                    }*/
                    else{
                        $AbsentsAr[]=$row['Attandence']['Id']."_".$row['Attandence']['Status'];
                    }
                }
                
                
                
                $CurrentStatus=implode(",", $AbsentsAr);
                
                if(!empty($PresentAr)){
                    $this->Session->setFlash('<span style="color:red;" >'.implode(",", $PresentAr).' is already present please select correct attendance date.</span>');
                    $this->redirect(array('action'=>'leaveentry'));
                }
                else if(!empty($HafPresAr)){
                    $this->Session->setFlash('<span style="color:red;" >'.implode(",", $HafPresAr).' is already half day please select correct attendance date.</span>');
                    $this->redirect(array('action'=>'leaveentry'));
                }
                /*
                else if(!empty($FulPresAr)){
                    $this->Session->setFlash('<span style="color:red;" >'.implode(",", $FulPresAr).' is already full day please select correct attendance date.</span>');
                    $this->redirect(array('action'=>'leaveentry'));
                }*/
                else if($LeaveType =="EL" && ($TotElArr[0][0]['TotalEl'] >= 3)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >You have already used three time in this year.</span>');
                    $this->redirect(array('action'=>'index'));
                }
                else if(!empty($exist)){
                    $sd=date('d M Y',strtotime($exist[0]['[leave_management']['LeaveFrom']));
                    $ed=date('d M Y',strtotime($exist[0]['leave_management']['LeaveTo']));
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your leave request already exist from ( '.date('d M Y',strtotime($exist[0]['leave_management']['LeaveFrom'])).'  to  '.date('d M Y',strtotime($exist[0]['leave_management']['LeaveTo'])).' ) </span>');
                    $this->redirect(array('action'=>'index'));
                }
                else{
                    
                    $Process=array();
                    $ProArr = $this->LeaveRightsMaster->find('first',array('fields'=>array('Process'),'conditions'=>array('BranchName'=>$branch_name,'Uid'=>$this->Session->read('userid'))));
                    if(!empty($ProArr)){
                        $Process=  explode(',', $ProArr['LeaveRightsMaster']['Process']); 
                    }
                    
                    $EmpArr=$this->Masjclrentry->find('first',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$EmpCode)));
                
                    $EmpName=$EmpArr['Masjclrentry']['EmpName'];
                    $CostCenter=$EmpArr['Masjclrentry']['CostCenter'];
                    $EmpLocation=$EmpArr['Masjclrentry']['EmpLocation'];
                    
                    if(in_array($CostCenter,$Process)){
                   
                        $TotalLeave=0;
                        $dataArr=array(
                            'BranchName'=>$branch_name,
                            'EmpCode'=>$EmpCode,
                            'EmpLocation'=>$EmpLocation,
                            'EmpName'=>$EmpName,
                            'CostCenter'=>$CostCenter,
                            'LeaveFrom'=>$FromDate,
                            'LeaveTo'=>$ToDate,
                            'LeaveFor'=>$LeaveFor,
                            'LeaveType'=>$LeaveType,
                            'CurrentStatus'=>$CurrentStatus,
                            'Purpose'=>$Purpose,
                            'Address'=>$AddDurLeave,
                            'Contact'=>$ContactNo,
                            'CL'=>$CL,
                            'ML'=>$ML,
                            'EL'=>$EL,
                            'MTRL'=>$MTRL,
                            'PTRL'=>$PTRL,
                            'LWP'=>$LWP,
                            'TotalLeave'=>$TotalLeave,
                            'CreateDate'=>date('Y-m-d H:i:s')
                        );

                        $this->LeaveApproval->saveAll($dataArr);
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;">Your request save successfully.</span>');
                        $this->redirect(array('action'=>'index'));
                    
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please allocate rights of this cost center.</span>');
                        $this->redirect(array('action'=>'index'));
                    }   
                }
                
                  
        } 
    }
    
    
    public function leavedetails(){
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
           
            $branch_name=$this->request->data['LeaveManagements']['branch_name'];
            $LeaveFrom=date("Y-m-d",strtotime($this->request->data['LeaveFrom']));
            $LeaveTo=date("Y-m-d",strtotime($this->request->data['LeaveTo']));
            $TypeName=$this->request->data['TypeName'];
            $Submit=$this->request->data['Submit'];
            
            if($this->Session->read('role')=='admin' && $this->Session->read('branch_name')=="HEAD OFFICE"){

                if($branch_name !="ALL"){
                    $condition=array('Status !='=>NULL,'BranchName'=>$branch_name,'date(LeaveFrom) >='=>$LeaveFrom,'date(LeaveTo) <='=>$LeaveTo);
                }
                else{
                    $condition=array('Status !='=>NULL,'date(LeaveFrom) >='=>$LeaveFrom,'date(LeaveTo) <='=>$LeaveTo); 
                }
            }
            else{
                
                $User_Id        =   $this->Session->read('userid');
                $User_Arr       =   $this->User->find('first',array('conditions'=>array('id'=>$User_Id)));
                $Access_Type    =   $User_Arr['User']['Access_Type']; 
                $Access_Rights  =   $User_Arr['User']['Access_Rights'];
                $Branch_Name    =   $branch_name;
                
                if($Access_Type =="Own"){
                    $Code_Rights    =   $Access_Rights;
                }
                else if($Access_Type =="CostCentre"){
                    $Code_Rights = $this->Masjclrentry->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$Branch_Name,'CostCenter'=>explode(",",$Access_Rights)),'group' =>array('EmpCode')));
                }
                
                if($branch_name !="ALL"){
                    $condition=array('EmpCode'=>$Code_Rights,'Status !='=>NULL,'BranchName'=>$branch_name,'date(LeaveFrom) >='=>$LeaveFrom,'date(LeaveTo) <='=>$LeaveTo);
                }
                else{
                    $condition=array('EmpCode'=>$Code_Rights,'Status !='=>NULL,'date(LeaveFrom) >='=>$LeaveFrom,'date(LeaveTo) <='=>$LeaveTo); 
                }
            }
           
            if($TypeName =="Approved"){
                $condition['Status']='Approved';
            }
            else if($TypeName =="Not Approved"){
                $condition['Status']='Not Approved';
            }
            else{
                unset($condition['Status']);
            }
            
            if($LeaveTo >=  $LeaveFrom){
                $this->set('fromdate',$LeaveFrom);
                $this->set('todate',$LeaveTo);
                $this->set('typename',$TypeName);
                
                
                
                
                
                if($Submit =="View"){
                    $this->set('data',$this->LeaveManagementMaster->find('all',array('conditions'=>$condition))); 
                }
                else if($Submit =="Export"){
                    $this->layout='ajax';
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=LeaveReport.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    
                    $data=$this->LeaveManagementMaster->find('all',array('conditions'=>$condition));
                    ?>
                    <table border="1" >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Emp Code</th>
                            <th style="text-align: center;">Emp Name</th>
                            <th style="text-align: center;">Process</th>
                            <th style="text-align: center;">Leave From</th>
                            <th style="text-align: center;">Leave To</th>
                            <th style="text-align: center;">Purpose</th>
                            <th style="text-align: center;">Address</th>
                            <th style="text-align: center;">Contact No</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">CL</th>
                            <th style="text-align: center;">ML</th>
                           
                            <th style="text-align: center;">EL</th>
                            <th style="text-align: center;">MTRL</th>
                            <th style="text-align: center;">PTRL</th>
                            <th style="text-align: center;">LWP</th>
                            <th style="text-align: center;">Total Leave</th>
                        </tr>
                    </thead>
                    <tbody>         
                    <?php 
                    foreach ($data as $val){
                        $row=$val['LeaveManagementMaster'];
                        $total=($row['CL']+$row['ML']+$row['DL']+$row['EL']+$row['MTRL']+$row['PTRL']+$row['LWP']);
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $row['BranchName'];?></td>
                        <td style="text-align: center;"><?php echo $row['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $row['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $row['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveFrom'])) ;?></td>
                        <td style="text-align: center;"><?php echo date('d-M-y',strtotime($row['LeaveTo'])) ;?></td>
                        <td style="text-align: center;"><?php echo $row['Purpose'];?></td>
                        <td style="text-align: center;"><?php echo $row['Address'];?></td>
                        <td style="text-align: center;"><?php echo $row['Contace'];?></td>
                        <td style="text-align: center;"><?php echo $row['Status'];?></td>
                        <td style="text-align: center;"><?php if($row['CL'] !=""){ echo $row['CL'];}else{echo 0;}?></td>
          
                     <td style="text-align: center;"><?php if($row['ML'] !=""){ echo $row['ML'];}else{echo 0;}?></td>
                      
                        <td style="text-align: center;"><?php if($row['EL'] !=""){ echo $row['EL'];}else{echo 0;}?></td>
                      
                        <td style="text-align: center;"><?php if($row['MTRL'] !=""){ echo $row['MTRL'];}else{echo 0;}?></td>
                   
                        <td style="text-align: center;"><?php if($row['PTRL'] !=""){ echo $row['PTRL'];}else{echo 0;}?></td>
                    
                        <td style="text-align: center;"><?php if($row['LWP'] !=""){ echo $row['LWP'];}else{echo 0;}?></td>
                        <td style="text-align: center;"><?php echo $total;?></td>  
                    </tr>
                    <?php }?>
                </tbody>   
                </table>

                    <?php
                   die;
                }
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select correct date.</span>');
                $this->redirect(array('action'=>'leavedetails'));   
            }
            
        } 
    }
    
    
   
    
    
    public function get_emp(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpName']) && trim($_REQUEST['EmpName']) !=""){

            if($this->Session->read('role')=='admin' && $this->Session->read('branch_name')=="HEAD OFFICE"){
                $data = $this->Masjclrentry->find('all',array(
                    'fields'=>array("EmpCode","EmpName"),
                    'conditions'=>array(
                        'Status'=>1,
                        'BranchName'=>$_REQUEST['BranchName'],
                        'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                        )
                    ));
            }
            else{
                $User_Id        =   $this->Session->read('userid');
                $User_Arr       =   $this->User->find('first',array('conditions'=>array('id'=>$User_Id)));
                $Access_Type    =   $User_Arr['User']['Access_Type']; 
                $Access_Rights  =   $User_Arr['User']['Access_Rights'];
                $Branch_Name    =   $_REQUEST['BranchName'];
                
                if($Access_Type =="Own"){
                    $Code_Rights    =   $Access_Rights;
                }
                else if($Access_Type =="CostCentre"){
                    $Code_Rights = $this->Masjclrentry->find('list',array('fields'=>array('EmpCode'),'conditions'=>array('BranchName'=>$Branch_Name,'CostCenter'=>explode(",",$Access_Rights)),'group' =>array('EmpCode')));
                }
  
                $data = $this->Masjclrentry->find('all',array(
                    'fields'=>array("EmpCode","EmpName"),
                    'conditions'=>array(
                        'EmpCode'=>$Code_Rights,
                        'Status'=>1,
                        'BranchName'=>$_REQUEST['BranchName'],
                        'EmpName LIKE'=>$_REQUEST['EmpName'].'%',
                        )
                    ));
            }
            
            
            
            
            
            
            
            if(!empty($data)){
                echo "<option value=''>Select Emp Code</option>";
                foreach ($data as $val){
                    $value=$val['Masjclrentry']['EmpCode'];
                    $label=$val['Masjclrentry']['EmpCode']." - ".$val['Masjclrentry']['EmpName'];
                    echo "<option value='$value'>$label</option>";
                }
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    public function get_gender(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("Gendar"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$_REQUEST['EmpCode'],
                    )
                )); 
            
            if(!empty($data)){
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    echo '<input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="PTRL"  > PTRL';
                    echo '<input type="text" readonly="" name="PTRL" id="PTRL" style="width:40px;" >';   
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    echo '<input type="radio" name="LeaveType" onclick="showHideLeave(this.value)" value="MTRL"  > MTRL';
                    echo '<input type="text" readonly="" name="MTRL" id="MTRL" style="width:40px;" >';
                }
            }
            else{
                echo "";
            }   
        }
        die;  
    }
    
    public function get_leave_details(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){
            $FromDateSearch = $_REQUEST['FromDate'];
            $ToDateSearch = $_REQUEST['ToDate'];
            
            $YearSearch="";
            if(!empty($FromDateSearch) && !empty($ToDateSearch))
            {
                //echo "Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1"; exit;
                $str_to_date = $this->Masjclrentry->query("Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1");
                $FromDateSearch = $str_to_date['0']['0']['from_date'];
                $ToDateSearch = $str_to_date['0']['0']['to_date'];
                
                $YearSearch="and Year('$ToDateSearch')= Year(LeaveTo);";  
            }
            else
            {
                $ToDateSearch = date('Y-m-d');
            }
            
            
            $EmpCode1 = $EmpCode = $_REQUEST['EmpCode'];
            $selPrevYear = $this->Masjclrentry->query("SELECT YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) prev_year FROM `salary_data`");
            $PrevYear = $selPrevYear[0][0]['prev_year'];
            
            if( ($PrevYear=='2022' || $PrevYear==2022))
            {
                $ma = $this->Masjclrentry->query("Select preEmpCode from masjclrentry where EmpCode='$EmpCode' limit 1 ");
                
                if(!empty($ma[0]['masjclrentry']['preEmpCode']))
                {
                    $EmpCode1 = $ma[0]['masjclrentry']['preEmpCode'];
                }
            }
            
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("DOJ","Gendar","EmpCode"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$EmpCode1,
                    )
                )); 
            #print_r($data);
            if(!empty($data)){

                $doj    =   $data['Masjclrentry']['DOJ'];
                $mnth   =   date("m");
                if(!empty($FromDateSearch) && !empty($ToDateSearch))
                {
                    $mnth   =   date("m",strtotime($ToDateSearch));
                    $date1  =   strtotime($doj);
                    $date2  = strtotime($ToDateSearch);
                }
                else
                {
                    $date1  =   strtotime($doj);
                    $date2  =   strtotime(date('Y-m-d'));
                }
                $months =   0;
                
                
               
                //$mnth   =   03;
                #print("$date1-$date2");
                while (strtotime('+1 MONTH', $date1) < $date2) {
                    $months++;
                    $date1 = strtotime('+1 MONTH', $date1);
                }
                #print($months);exit;
                //echo $months, ' month, ', ($date2 - $date1) / (60*60*24), ' days';
                
                //echo $months;die;
                
                
                if($months >=$mnth){
                    $mnth=$mnth;
                }
                else{
                    $mnth=$months;
                }
                
                $IntNo=1;  
                if($_REQUEST['FromDate'] !=""){
                
                    $PreYear    =   date("Y",strtotime($_REQUEST['FromDate']));
                    $CurYear    =   date("Y");

                    if($PreYear < $CurYear){
                        $IntNo=$IntNo+($CurYear-$PreYear);
                    }
                    else{
                        $IntNo=1;  
                    }
                }

                //if($months >=12){
                    
                    $EarnDayQry = $this->Masjclrentry->query("SELECT ROUND(SUM(EarnedDays)) TotalEarnDays FROM `salary_data` 
                    WHERE EmpCode='$EmpCode1' AND YEAR(SalayDate) = YEAR(DATE_SUB(CURDATE(), INTERVAL $IntNo YEAR));");
                    
                    $TotalEarnDays=$EarnDayQry[0][0]['TotalEarnDays'];
                    
                    $YearWiseMonth=$TotalEarnDays;
                    //print_r($TotalEarnDays);exit;
                //}
                //else{
                    //$YearWiseMonth =0;
                    
                    /*
                    if($months > 6){
                        $YearWiseMonth =$months;
                    }
                    else{
                        $YearWiseMonth =0;
                    }
                    */
                //}
                
                $emptyemp=substr($data['Masjclrentry']['EmpCode'], -1);
                
                $cl=  round(7/12*$mnth);
                $ml=  round(5/12*$mnth);
                $el=    0;
                //echo "round(5/12*$mnth)";
                if($emptyemp !="C"){
                    $el=  round(18/365*$YearWiseMonth);
                }
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $LeaveType="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $LeaveType="MTRL";
                }
                
                
                
                $TOTCNT=$this->LeaveManagementMaster->query("SELECT COUNT(Id) AS TOTCNT,MAX(LeaveTo) AS MAXDAT FROM `leave_management` WHERE BranchName='{$_REQUEST['BranchName']}' AND EmpCode='{$EmpCode}' AND LeaveType='$LeaveType' AND `Status`='Approved' $YearSearch");
                $TOPTMT=$TOTCNT[0][0]['TOTCNT'];
                $MAXDAT=$this->getmonth($TOTCNT[0][0]['MAXDAT']);
                
                if($TOPTMT ==0 && $months >=12){
                    $pt=4;
                    $mt=180;
                }
                else if($TOPTMT ==1 && $MAXDAT >= 36){
                    $pt=4;
                    $mt=180;
                }
                else{
                    $pt=0;
                    $mt=0;
                }
                
                $resArr=$this->LeaveManagementMaster->query("
                SELECT
                SUM(CL) AS TotCl,
                SUM(ML) AS TotMl,
                SUM(EL) AS TotEl,
                SUM(PTRL) AS TotPtrl,
                SUM(MTRL) AS TotMtrl
                FROM `leave_management` 
                WHERE BranchName='{$_REQUEST['BranchName']}' AND EmpCode='$EmpCode' AND  YEAR(LeaveTo) = YEAR('$ToDateSearch') AND (`Status` IS NULL OR `Status`='Approved') ");
                $takArr=$resArr[0][0];
                 
                $takcl=$takArr['TotCl'];
                $takml=$takArr['TotMl'];
                $takel=$takArr['TotEl'];
                $takpt=$takArr['TotPtrl'];
                $takmt=$takArr['TotMtrl'];
                
                if($pt !=0){$balpt=($pt-$takpt);}else{$balpt=0;}
                if($mt !=0){$balmt=($mt-$takmt);}else{$balmt=0;}
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $takptmt=$takArr['TotPtrl'];
                    $ptmt=  round($pt);
                    $labe="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $takptmt=$takArr['TotMtrl'];
                    $ptmt=  round($mt);
                    $labe="MTRL";
                }
  
            ?>


            <table class = "table table-striped table-hover  responstable">     
                <thead>
                    <tr>
                        <th style="text-align: center;" >Type</th>
                        <th style="text-align: center;">CL</th>
                        <th style="text-align: center;">ML</th>
                        <th style="text-align: center;">EL</th>
                        <th style="text-align: center;"><?php echo $labe;?></th>
                    </tr>
                </thead>
                <tbody>         
                    <tr>
                        <td style="text-align: center;">Eligible</td>
                        <td style="text-align: center;"><?php echo $cl;?></td>
                        <td style="text-align: center;"><?php echo $ml;?></td>
                        <td style="text-align: center;"><?php echo $el;?></td>
                        <td style="text-align: center;"><?php echo $ptmt;?></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Taken</td>
                        <td style="text-align: center;"><?php echo $takcl;?></td>
                        <td style="text-align: center;"><?php echo $takml;?></td>
                        <td style="text-align: center;"><?php echo $takel;?></td>
                        <td style="text-align: center;"><?php echo $takptmt;?></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Balance</td>
                        <td style="text-align: center;"><?php echo ($cl-$takcl);?></td>
                        <td style="text-align: center;"><?php echo ($ml-$takml);?></td>
                        <td style="text-align: center;"><?php echo ($el-$takel);?></td>
                        <td style="text-align: center;"><?php if($ptmt !=0){echo ($ptmt-$takptmt);}else{echo 0;}?></td>
                    </tr>
                </tbody>   
            </table>
            

            <input type="hidden" id="Balcl" value="<?php echo ($cl-$takcl);?>" >
            <input type="hidden" id="Balml" value="<?php echo ($ml-$takml);?>" >
            <input type="hidden" id="Balel" value="<?php echo ($el-$takel);?>" >
            <input type="hidden" id="Balpt" value="<?php echo $balpt;?>" >
            <input type="hidden" id="Balmt" value="<?php echo $balmt;?>" >
            <?php
                
                /*
                if($data['Masjclrentry']['Gendar'] =="Male"){
                    echo '<input type="radio" name="LeaveType" value="PTRL"  > PTRL';
                    echo '<input type="text" name="PTRL" id="PTRL" style="width:40px;" >';   
                }
                else if($data['Masjclrentry']['Gendar'] =="Female"){
                    echo '<input type="radio" name="LeaveType" value="MTRL"  > MTRL';
                    echo '<input type="text" name="MTRL" id="MTRL" style="width:40px;" >';
                }*/
                
            }
            else{
                echo "";
            }   
        }
        die;  
    }
    
    
    public function exist_attendance(){

        $BranchName =   $_REQUEST['BranchName'];
        $EmpCode    =   $_REQUEST['EmpNameCode']; 
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        $OnsitFrom  =   date("Y-m",strtotime($_REQUEST['FromDate']));
        $OnsitTo    =   date("Y-m",strtotime($_REQUEST['ToDate']));
        
        //EmpCode='$EmpCode' AND
        
        $EmpLocArr  =   $this->Masjclrentry->query("SELECT EmpLocation FROM masjclrentry WHERE EmpCode='$EmpCode'");
        $EmpLoc     =   $EmpLocArr[0]['masjclrentry']['EmpLocation'];
        
        if($EmpLoc =="InHouse"){
            $result=$this->Masattandance->query("SELECT * FROM `Attandence` WHERE BranchName='$BranchName' AND EmpCode='$EmpCode' AND DATE(AttandDate) BETWEEN '$FromDate' AND '$ToDate'");
        }
        if($EmpLoc =="OnSite"){
            
            $result=$this->Masattandance->query("SELECT * FROM `OnSiteAttendance` WHERE BranchName='$BranchName' AND EmpCode='$EmpCode' AND SalMonth BETWEEN '$OnsitFrom' AND '$OnsitTo'");
        }
        if($EmpLoc =="Field"){
            $result=$this->Masattandance->query("SELECT * FROM `FieldAttandence` WHERE BranchName='$BranchName' AND EmpCode='$EmpCode' AND DATE(AttandDate) BETWEEN '$FromDate' AND '$ToDate'");
        }

        if(!empty($result)){echo '1';die;}
        else{echo '';die;}
    }
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        if($ToDate >=$FromDate){
            echo '1';die;
        }
        else{
            echo '';die;
        }
    }
    
    public function getmonth($doj){
        $date1  =   strtotime($doj);
        $date2  =   strtotime(date('Y-m-d'));
        $months =   0;
     
        while (strtotime('+1 MONTH', $date1) < $date2) {
            $months++;
            $date1 = strtotime('+1 MONTH', $date1);
        }
        
        return $months;
    }

    

    public function validate_el(){
        $branch_name =   $_REQUEST['BranchName'];
        $EmpCode    =   $_REQUEST['EmpNameCode']; 
       
        $TotElArr=$this->LeaveManagementMaster->query("
        SELECT COUNT(Id) AS TotalEl FROM `leave_management` 
        WHERE 
        BranchName='$branch_name' 
        AND EmpCode='$EmpCode' 
        AND  YEAR(LeaveTo) = YEAR(CURDATE())
        AND LeaveType='EL' 
        AND (`Status` IS NULL OR `Status`='Approved')
        AND (`EL` IS NOT NULL OR `EL`!='' OR `EL`!='0')
        ");
          
        if($TotElArr[0][0]['TotalEl'] >= 3){
            echo '1';die; 
        }
        else{
            echo '';die;
        }
        
       
        
    }
    
    public function validate_leave_with_date(){
        $LeaveType  =   $_REQUEST['LeaveType'];
        $LeaveFor   =   $_REQUEST['LeaveFor'];
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        
        $TotalDay=round(abs(strtotime($FromDate)-strtotime($ToDate))/86400)+1;
        
        if($LeaveFor=="Half Day" && $TotalDay != 1){
            echo '1';die; 
        }
        else if($LeaveFor=="Full Day" && $TotalDay != $LeaveType){
            echo '1';die; 
        }
        else{
           echo '';die; 
        }
        die;
    }
    
    
    
    
    
    
    
    

    public function odapproval(){
        
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name'))));
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
            
            $branch_name=$this->request->data['OdApprovalDisapprovals']['branch_name'];
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];

                if($SubmitType !=""){

                    if($SubmitType =="Approve"){
                        $status="Yes";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $this->OdApplyMaster->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branch_name));
                            
                            if($SubmitType =="Approve"){
                                $AttDetArr=$this->OdApplyMaster->find('first',array('fields'=>array('EmpCode','date(StartDate) as stdate','date(EndDate) as endate'),'conditions'=>array('Id'=>$Id,'BranchName'=>$branch_name,'ApproveSecond'=>'Yes')));
                                $emcode=$AttDetArr['OdApplyMaster']['EmpCode'];
                                $stdate=$AttDetArr[0]['stdate'];
                                $endate=$AttDetArr[0]['endate'];
                           
                                $this->OdApplyMaster->query("UPDATE `Attandence` SET `Status`='P' WHERE EmpCode='$emcode' AND BranchName='HEAD OFFICE' 
                                AND DATE(AttandDate) BETWEEN '$stdate' AND '$endate'");
                            }
                            
                        }
                        $this->Session->setFlash('You request save successfully.'); 
                    }
                    else{
                        $this->Session->setFlash('Please select to approve or not approve od.'); 
                    }
                }
            }
            
            $this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>array('BranchName'=>$branch_name,'ApproveFirst'=>'Yes','ApproveSecond'=>NULL))));  
        }     
    }
    
    public function oddisapproval(){
        
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1))));
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
            $searchEmp=$this->request->data['searchEmp'];
            $branch_name=$this->request->data['OdApprovalDisapprovals']['branch_name'];
            
            if($this->request->data['Submit'] =="Discard"){
                $status="No";
                if(isset($this->request->data['check'])){
                    $OdIdArr=$this->request->data['check'];
                    foreach ($OdIdArr as $Id){
                        $this->OdApplyMaster->updateAll(array('ApproveSecond'=>"'".$status."'",'ApproveSecondDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branch_name));

                        $AttDetArr=$this->OdApplyMaster->find('first',array('fields'=>array('CurrentStatus','EmpCode','date(StartDate) as stdate','date(EndDate) as endate'),'conditions'=>array('Id'=>$Id,'BranchName'=>$branch_name,'ApproveSecond'=>'No')));
                        $CurrentStatus=$AttDetArr['OdApplyMaster']['CurrentStatus'];
                        $emcode=$AttDetArr['OdApplyMaster']['EmpCode'];
                        $stdate=$AttDetArr[0]['stdate'];
                        $endate=$AttDetArr[0]['endate'];

                        $this->OdApplyMaster->query("UPDATE `Attandence` SET `Status`='$CurrentStatus' WHERE EmpCode='$emcode' AND BranchName='$branch_name' 
                        AND DATE(AttandDate) BETWEEN '$stdate' AND '$endate'");
                        
                    }
                    $this->Session->setFlash('<span style="color:green;" >You request save successfully.</span>'); 
                }
                else{
                    $this->Session->setFlash('<span style="color:red;" >Please select record to discard.</span>'); 
                }
            }
            
            $this->set('searchEmp',$searchEmp);
            $this->set('OdArr',$this->OdApplyMaster->find('all',array('conditions'=>array('BranchName'=>$branch_name,'EmpCode'=>$searchEmp,'ApproveFirst'=>'Yes','ApproveSecond'=>'Yes'))));  
        }     
    }




public function all_emp_leave_details()
    {
        $emp_master = $this->Masjclrentry->find('all',array('conditions'=>" Status='1'"));
        //print_r($emp_master); exit;
        $leave_master = array();
        foreach($emp_master as $emp)
        {
            $empcode= $emp['Masjclrentry']['EmpCode'];
            $_R_BranchName = $emp['Masjclrentry']['BranchName'];
            $leave_master[$empcode]['emp_det'] = $emp['Masjclrentry'];
            $leave_master[$empcode]['leave_det'] = $this->get_emp_leave_details($empcode,$_R_BranchName);
        }
        
        header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=LeaveReport.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
        
        $leave_array = array('CL','ML','EL','PTRL','MTRL');
        echo '<table border="2">';
            echo "<tr>";
                
                echo '<th rowspan="2">Emp Code</th>';
                echo '<th rowspan="2">Emp Name</th>';
                echo '<th rowspan="2">Branch Name</th>';
                echo '<th rowspan="2">DOJ</th>';
                echo '<th colspan="5">Balance</th>';
                echo '<th colspan="5">Eligible</th>';
                echo '<th colspan="5">Taken</th>';
                
            echo '</tr>';
            echo "<tr>";
            for($i = 1; $i<=3; $i++)
            {
                foreach($leave_array as $lv)
                {
                    echo '<th>'.$lv.'</th>';
                }
            }
            echo '</tr>';
            
            foreach($leave_master as $code=>$emp_det)
            {
                //print_r($emp_det);exit;
                
                echo '<tr>';
                echo '<td>'.$emp_det['emp_det']['EmpCode'].'</td>';
                echo '<td>'.$emp_det['emp_det']['EmpName'].'</td>';
                echo '<td>'.$emp_det['emp_det']['BranchName'].'</td>';
                echo '<td>'.$emp_det['emp_det']['DOJ'].'</td>';
                
                foreach($leave_array as $lv)
                {
                    echo '<td>'.$emp_det['leave_det']['Balance'][$lv].'</td>';
                }
                
                foreach($leave_array as $lv)
                {
                    echo '<td>'.$emp_det['leave_det']['Eligible'][$lv].'</td>';
                }
                
                foreach($leave_array as $lv)
                {
                    echo '<td>'.$emp_det['leave_det']['Taken'][$lv].'</td>';
                }
                
                echo '</tr>';
            }
            echo '</table>';

            

            
            exit;
        //print_r($leave_master); exit;
    }
    
    public function get_emp_leave_details($EmpCode,$_R_BranchName){
        //$this->layout='ajax';
        
        $return_leave_det = array();
        
        if(isset($EmpCode) && trim($EmpCode) !=""){
            //$FromDateSearch = $_REQUEST['FromDate'];
            //$ToDateSearch = $_REQUEST['ToDate'];
            
            $YearSearch="";
            if(!empty($FromDateSearch) && !empty($ToDateSearch))
            {
                //echo "Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1"; exit;
                $str_to_date = $this->Masjclrentry->query("Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1");
                $FromDateSearch = $str_to_date['0']['0']['from_date'];
                $ToDateSearch = $str_to_date['0']['0']['to_date'];
                
                $YearSearch="and Year('$ToDateSearch')= Year(LeaveTo);";  
            }
            else
            {
                $ToDateSearch = date('Y-m-d');
            }
            
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("DOJ","Gendar","EmpCode"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$_R_BranchName,
                    'EmpCode'=>$EmpCode,
                    )
                )); 

            
            
            if(!empty($data)){

                $doj    =   $data['Masjclrentry']['DOJ'];
                $mnth   =   date("m");
                if(!empty($FromDateSearch) && !empty($ToDateSearch))
                {
                    $mnth   =   date("m",strtotime($ToDateSearch));
                    $date1  =   strtotime($doj);
                    $date2  = strtotime($ToDateSearch);
                }
                else
                {
                    $date1  =   strtotime($doj);
                    $date2  =   strtotime(date('Y-m-d'));
                }
                $months =   0;
                
                
               
                //$mnth   =   03;

                while (strtotime('+1 MONTH', $date1) < $date2) {
                    $months++;
                    $date1 = strtotime('+1 MONTH', $date1);
                }
                
                //echo $months, ' month, ', ($date2 - $date1) / (60*60*24), ' days';
                
                //echo $months;die;
                
                
                if($months >=$mnth){
                    $mnth=$mnth;
                }
                else{
                    $mnth=$months;
                }
                
                $IntNo=1;  
                if($_REQUEST['FromDate'] !=""){
                
                    $PreYear    =   date("Y",strtotime($_REQUEST['FromDate']));
                    $CurYear    =   date("Y");

                    if($PreYear < $CurYear){
                        $IntNo=$IntNo+($CurYear-$PreYear);
                    }
                    else{
                        $IntNo=1;  
                    }
                }

                //if($months >=12){
                    
                    $EarnDayQry = $this->Masjclrentry->query("SELECT ROUND(SUM(EarnedDays)) TotalEarnDays FROM `salary_data` 
                    WHERE EmpCode='{$data['Masjclrentry']['EmpCode']}' AND YEAR(SalayDate) = YEAR(DATE_SUB(CURDATE(), INTERVAL $IntNo YEAR));");
                    
                    $TotalEarnDays=$EarnDayQry[0][0]['TotalEarnDays'];
                    
                    $YearWiseMonth=$TotalEarnDays;
                //}
                //else{
                    //$YearWiseMonth =0;
                    
                    /*
                    if($months > 6){
                        $YearWiseMonth =$months;
                    }
                    else{
                        $YearWiseMonth =0;
                    }
                    */
                //}
                
                $emptyemp=substr($data['Masjclrentry']['EmpCode'], -1);
                
                $cl=  round(7/12*$mnth);
                $ml=  round(5/12*$mnth);
                $el=    0;
                
                if($emptyemp !="C"){
                    $el=  round(18/365*$YearWiseMonth);
                }
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $LeaveType="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $LeaveType="MTRL";
                }
                
                
                
                $TOTCNT=$this->LeaveManagementMaster->query("SELECT COUNT(Id) AS TOTCNT,MAX(LeaveTo) AS MAXDAT FROM `leave_management` WHERE BranchName='{$_R_BranchName}' AND EmpCode='{$EmpCode}' AND LeaveType='$LeaveType' AND `Status`='Approved' $YearSearch");
                $TOPTMT=$TOTCNT[0][0]['TOTCNT'];
                $MAXDAT=$this->getmonth($TOTCNT[0][0]['MAXDAT']);
                
                if($TOPTMT ==0 && $months >=12){
                    $pt=4;
                    $mt=180;
                }
                else if($TOPTMT ==1 && $MAXDAT >= 36){
                    $pt=4;
                    $mt=180;
                }
                else{
                    $pt=0;
                    $mt=0;
                }
                
                $resArr=$this->LeaveManagementMaster->query("
                SELECT
                SUM(CL) AS TotCl,
                SUM(ML) AS TotMl,
                SUM(EL) AS TotEl,
                SUM(PTRL) AS TotPtrl,
                SUM(MTRL) AS TotMtrl
                FROM `leave_management` 
                WHERE BranchName='{$_R_BranchName}' AND EmpCode='{$EmpCode}' AND  YEAR(LeaveTo) = YEAR('$ToDateSearch') AND (`Status` IS NULL OR `Status`='Approved') ");
                $takArr=$resArr[0][0];
                 
                $takcl=$takArr['TotCl'];
                $takml=$takArr['TotMl'];
                $takel=$takArr['TotEl'];
                $takpt=$takArr['TotPtrl'];
                $takmt=$takArr['TotMtrl'];
                
                if($pt !=0){$balpt=($pt-$takpt);}else{$balpt=0;}
                if($mt !=0){$balmt=($mt-$takmt);}else{$balmt=0;}
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $takptmt=$takArr['TotPtrl'];
                    $ptmt=  round($pt);
                    $labe="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $takptmt=$takArr['TotMtrl'];
                    $ptmt=  round($mt);
                    $labe="MTRL";
                }
                    
                    $return_leave_det['Eligible']['CL'] = $cl;
                    $return_leave_det['Eligible']['ML'] = $ml;
                    $return_leave_det['Eligible']['EL'] = $el;
                    $return_leave_det['Eligible'][$labe] = $ptmt;
                    
                    $return_leave_det['Taken']['CL'] = $takcl;
                    $return_leave_det['Taken']['ML'] = $takml;
                    $return_leave_det['Taken']['EL'] = $takel;
                    $return_leave_det['Taken'][$labe] = $takptmt;
                    
                    $return_leave_det['Balance']['CL'] = ($cl-$takcl);
                    $return_leave_det['Balance']['ML'] = ($ml-$takml);
                    $return_leave_det['Balance']['EL'] = ($el-$takel);
                    if($ptmt !=0)
                    {$return_leave_det['Balance'][$labe] =  ($ptmt-$takptmt);}
                    else{$return_leave_det['Balance'][$labe] = 0;}
                    
                    return $return_leave_det;
            
                
            }
            else{
                return $return_leave_det;
            }   
        }
        return $return_leave_det;  
    }
    
    

    public function export_leave()
    {
        $this->layout="home";
        $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        if($_POST)
        {
        $FromDateSearch_str = $FromDateSearch = $_REQUEST['FromDate'];
        $ToDateSearch_str = $ToDateSearch = $_REQUEST['ToDate'];
            
            $BranchName = $this->request->data['LeaveManagements']['branch_name'];  
            $where_qry = "";
            if($BranchName!="" && $BranchName!='ALL')
            {
                $where_qry = "BranchName='$BranchName' and";
            }
            
            $mas_arr = $this->Masjclrentry->query("Select * from masjclrentry where $where_qry status='1' order by EmpName");
            //print_r($mas_arr);exit;
            header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=leave_export.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
            
        ?>
            <table border = "1">  
                <thead>
                    <tr>
                        <th colspan="3">Emp Details</th>
                        <th colspan="4">Current Leave</th>
                        <th colspan="4">Leave Taken</th>
                        <th colspan="4">Leave Remain</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;">EmpCode</th>
                        <th style="text-align: center;">EmpName</th>
                        <th style="text-align: center;">BranchName</th>
                        <th style="text-align: center;">CL</th>
                        <th style="text-align: center;">ML</th>
                        <th style="text-align: center;">EL</th>
                        <th style="text-align: center;">PTL</th>
                        
                        <th style="text-align: center;"> CL</th>
                        <th style="text-align: center;"> ML</th>
                        <th style="text-align: center;"> EL</th>
                        <th style="text-align: center;"> PTL</th>
                        
                        <th style="text-align: center;">CL</th>
                        <th style="text-align: center;">ML</th>
                        <th style="text-align: center;">EL</th>
                        <th style="text-align: center;">PTL</th>
                    </tr>
                </thead>
                <tbody>
        <?php   
        
        foreach($mas_arr as $ma)
        {
            $FromDateSearch_str = $FromDateSearch = $_REQUEST['FromDate'];
            $ToDateSearch_str = $ToDateSearch = $_REQUEST['ToDate'];
            
            $EmpCode1 = $EmpCode = $ma['masjclrentry']['EmpCode'];
            $EmpName = $ma['masjclrentry']['EmpName'];
            
            $selPrevYear = $this->Masjclrentry->query("SELECT YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) prev_year FROM `salary_data`");
            $PrevYear = $selPrevYear[0][0]['prev_year'];
            
            if(!empty($ma['masjclrentry']['preEmpCode']) && ($PrevYear=='2022' || $PrevYear==2022))
            {
                $EmpCode1 = $ma['masjclrentry']['preEmpCode'];
            }
            
            $str_to_date = "";
            $doj ="";
            $mnth = "";
            $date1 = "";
            $date2 = "";
            $months = 0;
            
            $IntNo = 0;
            $PreYear = "";
            $CurYear = "";
            $TotalEarnDays = "";
            $YearWiseMonth = "";
            $YearWiseMonth = "";
            $LeaveType = "";
            $pt = 0;
            $mt = 0;
            $balpt = 0;
            $balmt = 0;
            $takptmt = 0;
            $ptmt = 0;
            $labe = 0;
            
            
            $YearSearch="";
            if(!empty($FromDateSearch) && !empty($ToDateSearch))
            {
                //echo "Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1"; exit;
                $str_to_date = $this->Masjclrentry->query("Select str_to_date('$FromDateSearch','%d-%b-%Y') from_date,str_to_date('$ToDateSearch','%d-%b-%Y') to_date from leave_management limit 1");
                $FromDateSearch = $str_to_date['0']['0']['from_date'];
                $ToDateSearch = $str_to_date['0']['0']['to_date'];
                
                $YearSearch="and Year('$ToDateSearch')= Year(LeaveTo);";  
            }
            else
            {
                $ToDateSearch = date('Y-m-d');
            }
            
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("DOJ","Gendar","EmpCode"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$BranchName,
                    'EmpCode'=>$EmpCode1,
                    )
                )); 

            if(!empty($data)){

                $doj    =   $data['Masjclrentry']['DOJ'];
                $mnth   =   date("m");
                if(!empty($FromDateSearch) && !empty($ToDateSearch))
                {
                    $mnth   =   date("m",strtotime($ToDateSearch));
                    $date1  =   strtotime($doj);
                    $date2  = strtotime($ToDateSearch);
                }
                else
                {
                    $date1  =   strtotime($doj);
                    $date2  =   strtotime(date('Y-m-d'));
                }
                $months =   0;
                
                
               
                //$mnth   =   03;

                while (strtotime('+1 MONTH', $date1) < $date2) {
                    $months++;
                    $date1 = strtotime('+1 MONTH', $date1);
                }
                
                //echo $months, ' month, ', ($date2 - $date1) / (60*60*24), ' days';
                
                //echo $months;die;
                
                
                if($months >=$mnth){
                    $mnth=$mnth;
                }
                else{
                    $mnth=$months;
                }
                
                $IntNo=1;  
                if($FromDateSearch_str !=""){
                
                    $PreYear    =   date("Y",strtotime($FromDateSearch_str));
                    $CurYear    =   date("Y");

                    if($PreYear < $CurYear){
                        $IntNo=$IntNo+($CurYear-$PreYear);
                    }
                    else{
                        $IntNo=1;  
                    }
                }
                
                

                //if($months >=12){
                    
                    $EarnDayQry = $this->Masjclrentry->query("SELECT ROUND(SUM(EarnedDays)) TotalEarnDays FROM `salary_data` 
                    WHERE EmpCode='$EmpCode1' AND YEAR(SalayDate) = YEAR(DATE_SUB(CURDATE(), INTERVAL $IntNo YEAR));");
                    
                    $TotalEarnDays=$EarnDayQry[0][0]['TotalEarnDays'];
                    
                    $YearWiseMonth=$TotalEarnDays;
                //}
                //else{
                    //$YearWiseMonth =0;
                    
                    /*
                    if($months > 6){
                        $YearWiseMonth =$months;
                    }
                    else{
                        $YearWiseMonth =0;
                    }
                    */
                //}
                
                $emptyemp=substr($data['Masjclrentry']['EmpCode'], -1);
                
                $cl=  round(7/12*$mnth);
                $ml=  round(5/12*$mnth);
                $el=    0;
                
                if($emptyemp !="C"){
                    $el=  round(18/365*$YearWiseMonth);
                }
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $LeaveType="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $LeaveType="MTRL";
                }
                
                
                
                $TOTCNT=$this->LeaveManagementMaster->query("SELECT COUNT(Id) AS TOTCNT,MAX(LeaveTo) AS MAXDAT FROM `leave_management` WHERE BranchName='{$BranchName}' AND EmpCode='{$EmpCode}' AND LeaveType='$LeaveType' AND `Status`='Approved' $YearSearch");
                $TOPTMT=$TOTCNT[0][0]['TOTCNT'];
                $MAXDAT=$this->getmonth($TOTCNT[0][0]['MAXDAT']);
                
                if($TOPTMT ==0 && $months >=12){
                    $pt=4;
                    $mt=180;
                }
                else if($TOPTMT ==1 && $MAXDAT >= 36){
                    $pt=4;
                    $mt=180;
                }
                else{
                    $pt=0;
                    $mt=0;
                }
                
                $resArr=$this->LeaveManagementMaster->query("
                SELECT
                SUM(CL) AS TotCl,
                SUM(ML) AS TotMl,
                SUM(EL) AS TotEl,
                SUM(PTRL) AS TotPtrl,
                SUM(MTRL) AS TotMtrl
                FROM `leave_management` 
                WHERE BranchName='{$BranchName}' AND EmpCode='{$EmpCode}' AND  YEAR(LeaveTo) = YEAR('$ToDateSearch') AND (`Status` IS NULL OR `Status`='Approved') ");
                $takArr=$resArr[0][0];
                 
                $takcl=$takArr['TotCl'];
                $takml=$takArr['TotMl'];
                $takel=$takArr['TotEl'];
                $takpt=$takArr['TotPtrl'];
                $takmt=$takArr['TotMtrl'];
                
                if($pt !=0){$balpt=($pt-$takpt);}else{$balpt=0;}
                if($mt !=0){$balmt=($mt-$takmt);}else{$balmt=0;}
                
                if(strcasecmp($data['Masjclrentry']['Gendar'], "Male") == 0){
                    $takptmt=$takArr['TotPtrl'];
                    $ptmt=  round($pt);
                    $labe="PTRL";
                }
                else if(strcasecmp($data['Masjclrentry']['Gendar'], "Female") == 0){
                    $takptmt=$takArr['TotMtrl'];
                    $ptmt=  round($mt);
                    $labe="MTRL";
                }
                
  
            ?>


               
                
                         
                    <tr>
                        <td style="text-align: center;"><?php echo $EmpCode; ?></td>
                        <td style="text-align: center;"><?php echo $EmpName; ?></td>
                        
                        <td style="text-align: center;"><?php echo $BranchName; ?></td>
                        <td style="text-align: center;"><?php echo $cl;?></td>
                        <td style="text-align: center;"><?php echo $ml;?></td>
                        <td style="text-align: center;"><?php echo $el;?></td>
                        <td style="text-align: center;"><?php echo $ptmt;?></td>
                    
                    
                        
                        <td style="text-align: center;"><?php echo $takcl;?></td>
                        <td style="text-align: center;"><?php echo $takml;?></td>
                        <td style="text-align: center;"><?php echo $takel;?></td>
                        <td style="text-align: center;"><?php echo $takptmt;?></td>
                    
                    
                        
                        <td style="text-align: center;"><?php echo ($cl-$takcl);?></td>
                        <td style="text-align: center;"><?php echo ($ml-$takml);?></td>
                        <td style="text-align: center;"><?php echo ($el-$takel);?></td>
                        <td style="text-align: center;"><?php if($ptmt !=0){echo ($ptmt-$takptmt);}else{echo 0;}?></td>
                    </tr>
                  
            
            

           
            
            
            
            <?php    
            }
                
            }
            ?>
                </tbody></table>
        <?php   
        

            exit;
    }
    
    
}
}
?>