<?php
class IncrementExportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','Masjclrhistory');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','export_report');
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
    }
    
    public function show_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
           // $conditoin=array('Status'=>1,'MONTH(ResignationDate)'=>$_REQUEST['EmpMonth'],'YEAR(ResignationDate)'=>$_REQUEST['EmpYear']);
         
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'EmpName'));

            if(!empty($data)){  
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;" >SNo</th>
                            <th style="text-align: center;width:80px;">EmpCode</th>
                            <th >EmpName</th>
                            <th style="text-align: center;width:80px;">DOJ</th>
                            <th style="text-align: center;width:100px;">Designation</th>
                            <th style="text-align: center;width:120px;">Branch</th>
                            <th style="text-align: center;width:80px;">CostCenter</th>
                            <th style="text-align: center;width:50px;">Gross</th>
                            <th style="text-align: center;width:70px;">NetInHand</th>
                            <th style="text-align: center;width:70px;">CTCOffered</th>
                            <th style="text-align: center;width:80px;">LastUpdated</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $n=1; foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td ><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['Desgination'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['Gross'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['NetInhand'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['CTC'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['lastUpdated']));?></td>
                        </tr>
                        
                        
                        <?php 
                        $data1   =   $this->Masjclrhistory->find('all',array('conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'])));
                        ?>
                        <?php $m=$n; foreach($data1 as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $m++;?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['EmpCode'];?></td>
                            <td ><?php echo $val['Masjclrhistory']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrhistory']['DOJ']));?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['Desgination'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['Gross'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['NetInhand'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrhistory']['CTC'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrhistory']['lastUpdated']));?></td>
                        </tr>
                        <?php $n=$m; }?>
                        
                        <?php }?>
                    </tbody>   
                </table>
            </div>
            <?php   
            }
            else{
                echo "";
            }
            die;
        
        }
    }
    
    
    
    
    

    
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=IncrementExport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'EmpName'));
            ?>
            <table border="1"  >     
                <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>DOJ</th>
                            <th>Designation</th>
                            <th>Branch</th>
                            <th>CostCenter</th> 
                            <th >Basic</th>
                            <th >CONV</th>
                            <th >PORTF</th>
                            <th >MA</th>
                            <th >SA</th>
                            <th >OA</th>
                            <th >HRA</th>
                            <th >Bonus</th>
                            <th >PLI</th>
                            <th >Gross</th>
                            <th >EPF</th>
                            <th >ESIC</th>
                            <th >ProfessionalTax</th>
                            <th >NetInHand</th>
                            <th >EPFCO</th>
                            <th >ESICCO</th>
                            <th >AdminCharges</th>
                            <th >CTC</th>
                            <th >LastUpdated</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $n=1; foreach($data as $val){?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                            <td><?php echo $val['Masjclrentry']['Desgination'];?></td>
                            <td><?php echo $val['Masjclrentry']['BranchName'];?></td>
                            <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td><?php echo $val['Masjclrentry']['bs'];?></td>
                            <td><?php echo $val['Masjclrentry']['conv'];?></td>
                            <td><?php echo $val['Masjclrentry']['portf'];?></td>
                            <td><?php echo $val['Masjclrentry']['ma'];?></td>
                            <td><?php echo $val['Masjclrentry']['sa'];?></td>
                            <td><?php echo $val['Masjclrentry']['oa'];?></td>
                            <td><?php echo $val['Masjclrentry']['hra'];?></td>
                            <td><?php echo $val['Masjclrentry']['Bonus'];?></td>
                            
                            <td><?php echo $val['Masjclrentry']['PLI'];?></td>
                            <td><?php echo $val['Masjclrentry']['Gross'];?></td>
                            <td><?php echo $val['Masjclrentry']['EPF'];?></td>
                            <td><?php echo $val['Masjclrentry']['ESIC'];?></td>
                            <td><?php echo $val['Masjclrentry']['ProfessionalTax'];?></td>
                            <td><?php echo $val['Masjclrentry']['NetInhand'];?></td>
                            
                            <td><?php echo $val['Masjclrentry']['EPFCO'];?></td>
                            <td><?php echo $val['Masjclrentry']['ESICCO'];?></td>
                            <td><?php echo $val['Masjclrentry']['AdminCharges'];?></td>
                            <td><?php echo $val['Masjclrentry']['CTC'];?></td>
                            <td><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['lastUpdated']));?></td>
                        </tr>
                        
                        
                        <?php 
                        $data1   =   $this->Masjclrhistory->find('all',array('conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'])));
                        ?>
                        <?php $m=$n; foreach($data1 as $val){?>
                        <tr>
                            <td><?php echo $m++;?></td>
                            <td><?php echo $val['Masjclrhistory']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrhistory']['EmpName'];?></td>
                            <td><?php echo date('d-M-Y',strtotime($val['Masjclrhistory']['DOJ']));?></td>
                            <td><?php echo $val['Masjclrhistory']['Desgination'];?></td>
                            <td><?php echo $val['Masjclrhistory']['BranchName'];?></td>
                            <td><?php echo $val['Masjclrhistory']['CostCenter'];?></td>
                            <td><?php echo $val['Masjclrhistory']['bs'];?></td>
                            <td><?php echo $val['Masjclrhistory']['conv'];?></td>
                            <td><?php echo $val['Masjclrhistory']['portf'];?></td>
                            <td><?php echo $val['Masjclrhistory']['ma'];?></td>
                            <td><?php echo $val['Masjclrhistory']['sa'];?></td>
                            <td><?php echo $val['Masjclrhistory']['oa'];?></td>
                            <td><?php echo $val['Masjclrhistory']['hra'];?></td>
                            <td><?php echo $val['Masjclrhistory']['Bonus'];?></td>
                            
                            <td><?php echo $val['Masjclrhistory']['PLI'];?></td>
                            <td><?php echo $val['Masjclrhistory']['Gross'];?></td>
                            <td><?php echo $val['Masjclrhistory']['EPF'];?></td>
                            <td><?php echo $val['Masjclrhistory']['ESIC'];?></td>
                            <td><?php echo $val['Masjclrhistory']['ProfessionalTax'];?></td>
                            <td><?php echo $val['Masjclrhistory']['NetInhand'];?></td>
                            
                            <td><?php echo $val['Masjclrhistory']['EPFCO'];?></td>
                            <td><?php echo $val['Masjclrhistory']['ESICCO'];?></td>
                            <td><?php echo $val['Masjclrhistory']['AdminCharges'];?></td>
                            <td><?php echo $val['Masjclrhistory']['CTC'];?></td>
                            <td><?php echo date('d-M-Y',strtotime($val['Masjclrhistory']['lastUpdated']));?></td>
                        </tr>
                        <?php $n=$m; }?>
                        
                        <?php }?>
                    </tbody> 
            </table>
            <?php 
            die;
        }
        
    }
    
    
    
    
    
    public function total_sundays($month,$year){
        $sundays=0;
        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i=1;$i<=$total_days;$i++)
        if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
        $sundays++;
        return $sundays;
    }

    
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
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
    
    function dayCount($from, $to) {
        $first_date = strtotime($from);
        $second_date = strtotime($to);
        $offset = $second_date-$first_date; 
        return floor($offset/60/60/24);
    }
    
    function sundayCount($from, $to) {        
        $start = new DateTime($from);
        $end = new DateTime($to);
        $days = $start->diff($end, true)->days;

        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
        return $sundays;  
    }

    function HolydayCount($from,$to,$branch) {
        $hcnt   =   $this->HolidayMaster->query("SELECT COUNT(Id) AS TotHolyday FROM `HolidayMaster` WHERE DATE(HolydayDate) BETWEEN '$from' AND '$to' AND BranchName='$branch'"); 
        return $hcnt[0][0]['TotHolyday'];
    }
    
    
    
    
    
    
    
    
    
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=OldAttandanceIssueApproval.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
            
        $branchName = $this->Session->read('branch_name');
        $data=$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL))); 
        ?>
        <table border="1" >          
            <tr>
                <th>Emp Code</th>
                <th>Bio Code</th>
                <th>Emp Name</th>
                <th>Branch</th>
                <th>Attend Date</th>
                <th>Reason</th>
                <th>Current Status</th>
                <th>Expected Status</th>
                <th>Status</th>
            </tr>             
            <?php foreach ($data as $val){?>
            <tr>
                <td><?php echo $val['OldAttendanceIssue']['EmpCode'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['BioCode'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['EmpName'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['BranchName'];?></td>
                <td><?php echo date('d M y',strtotime($val['OldAttendanceIssue']['AttandDate'])) ;?></td>
                <td><?php echo $val['OldAttendanceIssue']['Reason'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['CurrentStatus'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['ExpectedStatus'];?></td>
                <td>
                    <?php 
                    if($val['OldAttendanceIssue']['ApproveFirst'] =="Yes"){
                        echo "Approve";
                    }
                    else if($val['OldAttendanceIssue']['ApproveFirst'] =="No"){
                        echo "Not Approve";
                    }
                    else{
                        echo "Pending"; 
                    }
                    ?>
                </td>
            </tr>
            <?php }?>    
       </table>
        <?php
        die;
    }
    
}
?>