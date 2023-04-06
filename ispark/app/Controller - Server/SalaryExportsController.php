<?php
class SalaryExportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','SalarData');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','export_report','salary_process');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }    
    }
    
    public function show_report(){
        $this->layout='ajax';
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            if($_REQUEST['EmpMonth'] !=""){$m=$_REQUEST['EmpMonth'];$conditoin1['MONTH(AttandDate)']=$_REQUEST['EmpMonth'];}else{$m=date('m');unset($conditoin1['MONTH(AttandDate)']);}
            if($_REQUEST['EmpYear'] !=""){$y=$_REQUEST['EmpYear'];$conditoin1['YEAR(AttandDate)']=$_REQUEST['EmpYear'];}else{$y=date('Y');unset($conditoin1['YEAR(AttandDate)']);}
            
            //$conditoin=array('Status'=>1);
            $conditoin5=array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m);
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            
            //if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            //if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            //if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['EmpLocation']=$_REQUEST['EmpLocation'];}else{unset($conditoin['EmpLocation']);}
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
            
            $hcnt   =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5)); 
            
            if(!empty($data)){
               
                
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>BioCode</th>
                            <th>EmpName</th>
                            <th>EmpLocation</th>
                            <th>Designation</th>
                            <th>CostCenter</th>
                            <?php for($i=1;$i<=$mwd;$i++){echo "<th>".date('M-d',strtotime("$y-$m-$i"))."</th>";}?>
                            <th style='text-align:center;'>A</th>
                            <th style='text-align:center;'>P</th>
                            <th style='text-align:center;'>OD</th>
                            <th style='text-align:center;'>HD/DH/FTP</th>
                            <th style='text-align:center;'>L</th>
                            <th style='text-align:center;'>H</th>
                            <th style='text-align:center;'>W</th>
                            <th style='text-align:center;'>SalDays</th>
                            <th style='text-align:center;'>Total</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $OldStatus="";
                        $AttArrOld=array();
                        $OnSiteArr=array();
                        $n=1; foreach ($data as $val){
                            
                        $emp_status=$val['Masjclrentry']['Status'];
                        $emp_regdat=$val['Masjclrentry']['ResignationDate'];
                            
                        
                            
                        if($_REQUEST['BranchName'] =="ALL"){
                           $conditoin5['BranchName']=$val['Masjclrentry']['BranchName'];
                           $hcnt   =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5)); 
                        }
                            
                            
                        $conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
                        
                        if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
                            $AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                            $AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
                        }
                        else if($val['Masjclrentry']['EmpLocation'] =="Field"){
                            $AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                        }
                        else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$_REQUEST['EmpYear']}-{$_REQUEST['EmpMonth']}")));
                            $OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
                        }
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                            <td><?php echo $val['Masjclrentry']['Desgination'];?></td>
                            <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <?php
                            $OLS=0;
                            $A=0;
                            $P=0;
                            $OD=0;
                            $HD=0;
                            $DH=0;
                            $F=0;
                            $L=0;
                            $TS=$this->total_sundays($m,$y);
                            for($j=1;$j<=$mwd;$j++){
                                $Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
                                
                                if(!empty($AttArrOld)){
                                   $OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
                                }
                                
                                
                                
                                //echo $OLS."<br/>";
                                
                                
                                
                                if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
                                   echo "<td></td>"; 
                                }
                                else{
                                    
                                    if($Status =="A"){$A=$A+1;}
                                    if($Status =="P"){$P=$P+1;}
                                    if($Status =="OD"){$OD=$OD+1;}
                                    if($Status =="HD"){$HD=$HD+1;}
                                    if($Status =="DH"){$DH=$DH+1;}
                                    if($Status =="F"){$F=$F+1;}
                                    if($Status =="L"){$L=$L+1;}
                                    if($Status =="HL"){$L=$L+0.5;}
                                    if($Status =="FL"){$L=$L+1;$OLS=$OLS+1;}
                                    if($Status =="HDL"){$L=$L+1;$OLS=$OLS+1;}
                                    if($Status =="DHL"){$L=$L+1;$OLS=$OLS+1;}
                                    
                                    
                                    if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                        if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
                                    }
                                    else{
                                        echo "<td>$OldStatus$Status</td>"; 
                                        //echo "<td>$Status</td>"; 
                                    }
                                }
                                
                                
                            }
                            
                            
                            $AMY=strtotime("$y-$m");
                            if($val['Masjclrentry']['Status']==1){
                                if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['DOJ'])))==$AMY){
                                    $mwd1=$this->dayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                    $sund=$this->sundayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                    $HolDay=$this->HolydayCount($val['Masjclrentry']['DOJ'],"$y-$m-$mwd",$val['Masjclrentry']['BranchName']);
                                    
                                    $TotDay=$mwd1-($sund+$HolDay);
                                    $FinDay=round(80*$TotDay/100); 
                                }
                                else{
                                    $HolDay=$hcnt;
                                    $TotDay=$mwd-($TS+$HolDay);
                                    $FinDay=round(80*$TotDay/100);   
                                }   
                            }
                            else{
                                if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['ResignationDate'])))==$AMY){
                                    $mwd1=$this->dayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                    $sund=$this->sundayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                    $HolDay=$this->HolydayCount("$y-$m-1",$val['Masjclrentry']['ResignationDate'],$val['Masjclrentry']['BranchName']);
                                    
                                    $TotDay=$mwd1-($sund+$HolDay);
                                    $FinDay=round(80*$TotDay/100);  
                                }
                                else{
                                    $mwd1=0;
                                    $sund=0;
                                    $HolDay=0;
                                    
                                    $TotDay=$mwd1-($sund+$HolDay);
                                    $FinDay=round(80*$TotDay/100);
                                }
                            }
                            
                            $TotPre=$P+$OD+$L+($HD+$DH+$F)/2;
                            
                            if($TotPre >= $FinDay){
                                $Holiday=$HolDay;  
                            }
                            else{
                                $Holiday=0;
                            }
                            
                            $H=$Holiday;
                            $W=0;
                            if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                $SalDay=$OSC;
                            }
                            else{
                                $SalDay=$P+$OD+$L+$H+($HD+$DH+$F)/2;
                            }
                            
                            if($SalDay >=7){$W=1;}
                            if($SalDay >=12){$W=2;}
                            if($SalDay >=18){$W=3;}
                            if($SalDay >=24){$W=4;}
                            if($TS ==5 && $mwd ==31 && $SalDay >=26){$W=5;}
                            if($TS ==5 && $mwd ==30 && $SalDay >=25){$W=5;}
                            
                            if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                $W=0;
                            }
                           
                            $Total=($SalDay+$W);
                            
                            if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                echo "<td style='text-align:center;' >0</td>";
                                echo "<td style='text-align:center;' >$OSC</td>";
                                echo "<td style='text-align:center;' >0</td>";
                                echo "<td style='text-align:center;' >0</td>";
                                echo "<td style='text-align:center;' >0</td>";
                                echo "<td style='text-align:center;' >0</td>";
                            }
                            else{
                            ?>
                            <td style='text-align:center;'><?php echo $A;?></td>
                            <td style='text-align:center;'><?php echo $P;?></td>
                            <td style='text-align:center;'><?php echo $OD;?></td>
                            <td style='text-align:center;'><?php echo ($HD+$DH+$F)+$OLS;?></td>
                            <td style='text-align:center;'><?php echo $L-($OLS/2);?></td>
                            <td style='text-align:center;'><?php echo $H;?></td>
                            <?php }?>
                            
                            <td style='text-align:center;'><?php echo $W;?></td>
                            <td style='text-align:center;'><?php echo $SalDay;?></td>
                            <td style='text-align:center;'><?php echo $Total;?></td>
                        </tr>
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
    
    
    
    public function salary_process(){
        
        if($this->request->is('Post')){ 
            $postReq=$this->request->data;
            $list_value="";

            if($postReq['EmpMonth'] !=""){$m=$postReq['EmpMonth'];$conditoin1['MONTH(AttandDate)']=$postReq['EmpMonth'];}else{$m=date('m');unset($conditoin1['MONTH(AttandDate)']);}
            if($postReq['EmpYear'] !=""){$y=$postReq['EmpYear'];$conditoin1['YEAR(AttandDate)']=$postReq['EmpYear'];}else{$y=date('Y');unset($conditoin1['YEAR(AttandDate)']);}
            
            //$conditoin=array('Status'=>1);
            $conditoin5=array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m);
            
            if($postReq['SalaryExports']['branch_name'] !="ALL"){$conditoin['BranchName']=$postReq['SalaryExports']['branch_name'];$conditoin5['BranchName']=$postReq['SalaryExports']['branch_name'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            
            $mwd            =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay       =   $y."-".$m."-".$mwd;
            $existsalayday  =   $this->SalarData->find('first',array('conditions'=>array('Branch'=>$postReq['SalaryExports']['branch_name'],'date(SalayDate)'=>$SalayDay)));
            $attendanceday  =   $this->Masattandance->find('first',array('conditions'=>array('BranchName'=>$postReq['SalaryExports']['branch_name'],'date(AttandDate)'=>$SalayDay)));
            
            if(!empty($existsalayday)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Sorry this month salary already processed.</span>'); 
                $this->redirect(array('controller'=>'SalaryExports','action' => 'index'));
            }
            else if(empty($attendanceday)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Sorry this month salary process not allow.</span>'); 
                $this->redirect(array('controller'=>'SalaryExports','action' => 'index'));
            }
            else{
                $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
                $hcnt   =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5)); 
            ?>
             <!--
            <table border="1"  >     
                <thead>
                    <tr>
                       
                        <th>SNo</th>
                        <th>EmpCode</th>
                        <th>BioCode</th>
                        <th>EmpName</th>
                        <th>EmpLocation</th>
                        <th>Designation</th>
                        <th>CostCenter</th>
                        -->
                        
                        <?php //for($i=1;$i<=$mwd;$i++){echo "<th>".date('M-d',strtotime("$y-$m-$i"))."</th>";}?>
                        <!--
                        <th style='text-align:center;'>A</th>
                        <th style='text-align:center;'>P</th>
                        <th style='text-align:center;'>OD</th>
                        <th style='text-align:center;'>HD/DH/FTP</th>
                        <th style='text-align:center;'>L</th>
                        <th style='text-align:center;'>H</th>
                        <th style='text-align:center;'>W</th>
                        <th style='text-align:center;'>SalDays</th>
                        <th style='text-align:center;'>Total</th>
                    </tr>
                </thead>
                <tbody> 
                -->
                    
                    <?php
                    $OldStatus="";
                    $AttArrOld=array();
                    $OnSiteArr=array();
                    $n=1; foreach ($data as $val){
                    $conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
                    
                    $emp_status=$val['Masjclrentry']['Status'];
                    $emp_regdat=$val['Masjclrentry']['ResignationDate'];

                    if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
                        $AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                        $AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="Field"){
                        $AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                        $OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$postReq['EmpYear']}-{$postReq['EmpMonth']}")));
                        $OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
                    }
                    ?>
                    <tr>
                        <!--
                        <td><?php echo $n++;?></td>
                        <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                        <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                        <td><?php echo $val['Masjclrentry']['Desgination'];?></td>
                        <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                        -->
                        <?php
                        
                        $EmpCode        =   $val['Masjclrentry']['EmpCode'];
                        $EmpName        =   $val['Masjclrentry']['EmpName'];
                        $CostCenter     =   $val['Masjclrentry']['CostCenter'];
                        $Desgination    =   $val['Masjclrentry']['Desgination'];
                        $BranchName     =   $val['Masjclrentry']['BranchName'];
                        $Basic          =   $val['Masjclrentry']['bs'];
                        $hra            =   $val['Masjclrentry']['hra'];
                        $Bonus          =   $val['Masjclrentry']['Bonus'];
                        $conv           =   $val['Masjclrentry']['conv'];
                        $Portfolio      =   $val['Masjclrentry']['portf'];
                        $MedicalAllow   =   $val['Masjclrentry']['ma'];
                        $lta            =   $val['Masjclrentry']['lta'];
                        $SpecialAllow   =   $val['Masjclrentry']['sa'];
                        $OtherAllow     =   $val['Masjclrentry']['oa'];
                        $PLI            =   $val['Masjclrentry']['PLI'];
                        $Gross          =   $val['Masjclrentry']['Gross'];
                        
                        $OLS=0;
                        $A=0;
                        $P=0;
                        $OD=0;
                        $HD=0;
                        $DH=0;
                        $F=0;
                        $L=0;
                        $TS=$this->total_sundays($m,$y);
                        
                        for($j=1;$j<=$mwd;$j++){
                            $Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
                            
                            if(!empty($AttArrOld)){
                                $OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
                            }
                            
                            if($Status =="A"){$A=$A+1;}
                            if($Status =="P"){$P=$P+1;}
                            if($Status =="OD"){$OD=$OD+1;}
                            if($Status =="HD"){$HD=$HD+1;}
                            if($Status =="DH"){$DH=$DH+1;}
                            if($Status =="F"){$F=$F+1;}
                            if($Status =="L"){$L=$L+1;}
                            if($Status =="HL"){$L=$L+0.5;}
                            if($Status =="FL"){$L=$L+1;$OLS=$OLS+1;}
                            if($Status =="HDL"){$L=$L+1;$OLS=$OLS+1;}
                            if($Status =="DHL"){$L=$L+1;$OLS=$OLS+1;}

                            if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
                                //echo "<td></td>"; 
                            }
                            else{
                                if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                    //if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
                                }
                                else{
                                    //echo "<td>$OldStatus$Status</td>"; 
                                    //echo "<td>$Status</td>"; 
                                }
                            }
                        }
                        

                        $AMY=strtotime("$y-$m");
                        if($val['Masjclrentry']['Status']==1){
                            if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['DOJ'])))==$AMY){
                                $mwd1=$this->dayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                $sund=$this->sundayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                $HolDay=$this->HolydayCount($val['Masjclrentry']['DOJ'],"$y-$m-$mwd",$val['Masjclrentry']['BranchName']);

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100); 
                            }
                            else{
                                $HolDay=$hcnt;
                                $TotDay=$mwd-($TS+$HolDay);
                                $FinDay=round(80*$TotDay/100);   
                            }   
                        }
                        else{
                            if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['ResignationDate'])))==$AMY){
                                $mwd1=$this->dayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                $sund=$this->sundayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                $HolDay=$this->HolydayCount("$y-$m-1",$val['Masjclrentry']['ResignationDate'],$val['Masjclrentry']['BranchName']);

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100);  
                            }
                            else{
                                $mwd1=0;
                                $sund=0;
                                $HolDay=0;

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100);
                            }
                        }

                        $TotPre=$P+$OD+$L+($HD+$DH+$F)/2;

                        if($TotPre >= $FinDay){
                            $Holiday=$HolDay;  
                        }
                        else{
                            $Holiday=0;
                        }
                            
                        $H=$Holiday;
                        $W=0;
                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $SalDay=$OSC;
                        }
                        else{
                            $SalDay=$P+$OD+$L+$H+($HD+$DH+$F)/2;
                        }

                        if($SalDay >=7){$W=1;}
                        if($SalDay >=12){$W=2;}
                        if($SalDay >=18){$W=3;}
                        if($SalDay >=24){$W=4;}
                        if($TS ==5 && $mwd ==31 && $SalDay >=26){$W=5;}
                        if($TS ==5 && $mwd ==30 && $SalDay >=25){$W=5;}
                        
                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $W=0;
                        }
                           
                        $Total=($SalDay+$W);

                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            /*
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >$OSC</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            */
                            $EmpLeave=0;
                        }
                        else{
                           
                        ?>
                        <!--
                        <td style='text-align:center;'><?php echo $A;?></td>
                        <td style='text-align:center;'><?php echo $P;?></td>
                        <td style='text-align:center;'><?php echo $OD;?></td>
                        <td style='text-align:center;'><?php echo ($HD+$DH+$F)+$OLS;?></td>
                        <td style='text-align:center;'><?php echo $L-($OLS/2);?></td>
                        <td style='text-align:center;'><?php echo $H;?></td>
                        -->
                        
                        
                        
                        
                        <?php 
                        $EmpLeave=$L-($OLS/2);
                        
                        }?>
                        <!--
                        <td style='text-align:center;'><?php echo $W;?></td>
                        <td style='text-align:center;'><?php echo $SalDay;?></td>
                        <td style='text-align:center;'><?php echo $Total;?></td>
                        -->
                  <!--  </tr> -->
                    <?php
 
                    $WorkingDay         =   $mwd;
                    $CTCOffered         =   $val['Masjclrentry']['CTC'];
                    $CurrentCTC         =   $val['Masjclrentry']['CTC'];
                    $EarnedDays         =   $SalDay;
                    $ExtraDay           =   "";
                    $Leave              =   $EmpLeave;
                    $Basic1             =   $Basic/$WorkingDay*$EarnedDays;
                    $HRA1               =   $hra/$WorkingDay*$EarnedDays;
                    $Bonus1             =   $Bonus/$WorkingDay*$EarnedDays;
                    $Conv1              =   $Bonus/$WorkingDay*$EarnedDays;
                    $Portfolio1         =   $Portfolio/$WorkingDay*$EarnedDays;
                    $SpecialAllowance1  =   $SpecialAllow/$WorkingDay*$EarnedDays;
                    $OtherAllowance1    =   $OtherAllow/$WorkingDay*$EarnedDays;
                    $MedicalAllowance1  =   $MedicalAllow/$WorkingDay*$EarnedDays;
                    $Gross1             =   $Basic1+$HRA1+$Bonus1+$Conv1+$Portfolio1+$SpecialAllowance1;
                    $ESIElig            =   $val['Masjclrentry']['esielig'];
                    $PFELig             =   $val['Masjclrentry']['pfelig'];
                    $ESIC               =   $val['Masjclrentry']['ESIC'];
                    $EPF                =   $val['Masjclrentry']['EPF'];
                    $IncomeTax          =   "";
                    $AdvTaken           =   "";
                    $AdvPaid            =   "";
                    $LoanTaken          =   "";
                    $LoanDed            =   "";
                    $Incentive          =   "";
                    $ExtraDayIncentive  =   "";
                    $Arrear             =   "";
                    $PLI                =   $val['Masjclrentry']['PLI'];
                    $NetSalary          =   $val['Masjclrentry']['NetInhand'];
                    $ESICCompany        =   $val['Masjclrentry']['ESICCO'];
                    $EPFCompany         =   $val['Masjclrentry']['EPFCO'];
                    $AdminChrg          =   $val['Masjclrentry']['AdminCharges'];
                    $CTC                =   $val['Masjclrentry']['CTC'];
                    $SHSH               =   "";
                    $MobileDedcution    =   "";
                    $ShortCollection    =   "";
                    $AssetRecovery      =   "";
                    $Insurance          =   "";
                    $ProTaxDeduction    =   "";
                    $LeaveDeduction     =   "";
                    $OtherDeduction     =   "";
                    $OtherDeductionRemarks ="";
                    $TotalDeduction     =   "";
                    $SalDate            =   $SalayDay;
                    $EPFNo              =   $val['Masjclrentry']['EPFNo'];
                    $ESICNo             =   $val['Masjclrentry']['ESICNo'];
                    $ChequeNumber       =   "";
                    $ChequeDate         =   "";
                    $PrintDate          =   "";
                    $LeftStatus         =   $val['Masjclrentry']['ResignationDate'];
                    
                    
        
                    if($list_value!=''){									
                        $list_value=$list_value.",('".$EmpCode."','".$EmpName."','".$CostCenter."','".$Desgination."','".$BranchName."','".$Basic."','".$hra."','".$Bonus."','".$conv."','".$Portfolio."','".$MedicalAllow."','".$lta."','".$SpecialAllow."','".$OtherAllow."','".$PLI."','".$Gross."','".$WorkingDay."','".$CTCOffered."','".$CurrentCTC."','".$EarnedDays."','".$ExtraDay."','".$Leave."','".$Basic1."','".$HRA1."','".$Bonus1."','".$Conv1."','".$Portfolio1."','".$SpecialAllowance1."','".$OtherAllowance1."','".$MedicalAllowance1."','".$Gross1."','".$ESIElig."','".$PFELig."','".$ESIC."','".$EPF."','".$IncomeTax."','".$AdvTaken."','".$AdvPaid."','".$LoanTaken."','".$LoanDed."','".$Incentive."','".$ExtraDayIncentive."','".$Arrear."','".$PLI."','".$NetSalary."','".$ESICCompany."','".$EPFCompany."','".$AdminChrg."','".$CTC."','".$SHSH."','".$MobileDedcution."','".$ShortCollection."','".$AssetRecovery."','".$Insurance."','".$ProTaxDeduction."','".$LeaveDeduction."','".$OtherDeduction."','".$OtherDeductionRemarks."','".$TotalDeduction."','".$SalDate."','".$EPFNo."','".$ESICNo."','".$ChequeNumber."','".$ChequeDate."','".$PrintDate."','".$LeftStatus."','".$SalayDay."')";
                    }
                    else{
                                     $list_value="('".$EmpCode."','".$EmpName."','".$CostCenter."','".$Desgination."','".$BranchName."','".$Basic."','".$hra."','".$Bonus."','".$conv."','".$Portfolio."','".$MedicalAllow."','".$lta."','".$SpecialAllow."','".$OtherAllow."','".$PLI."','".$Gross."','".$WorkingDay."','".$CTCOffered."','".$CurrentCTC."','".$EarnedDays."','".$ExtraDay."','".$Leave."','".$Basic1."','".$HRA1."','".$Bonus1."','".$Conv1."','".$Portfolio1."','".$SpecialAllowance1."','".$OtherAllowance1."','".$MedicalAllowance1."','".$Gross1."','".$ESIElig."','".$PFELig."','".$ESIC."','".$EPF."','".$IncomeTax."','".$AdvTaken."','".$AdvPaid."','".$LoanTaken."','".$LoanDed."','".$Incentive."','".$ExtraDayIncentive."','".$Arrear."','".$PLI."','".$NetSalary."','".$ESICCompany."','".$EPFCompany."','".$AdminChrg."','".$CTC."','".$SHSH."','".$MobileDedcution."','".$ShortCollection."','".$AssetRecovery."','".$Insurance."','".$ProTaxDeduction."','".$LeaveDeduction."','".$OtherDeduction."','".$OtherDeductionRemarks."','".$TotalDeduction."','".$SalDate."','".$EPFNo."','".$ESICNo."','".$ChequeNumber."','".$ChequeDate."','".$PrintDate."','".$LeftStatus."','".$SalayDay."')";
                    } 
                            
                    
                     
                    
                    }
                    
                    $this->SalarData->query("INSERT INTO salary_data(`EmpCode`,`EmpName`,`CostCenter`,`Designation`,`Branch`,`Basic`,`HRA`,`Bonus`,`Conv`,`Portfolio`,`MedicalAllowance`,`LTA`,`SpecialAllowance`,`OtherAllowance`,`PLI1`,`Gross`,`WorkingDays`,`CTCOffered`,`CurrentCTC`,`EarnedDays`,`ExtraDay`,`Leave`,`Basic1`,`HRA1`,`Bonus1`,`Conv1`,`Portfolio1`,`SpecialAllowance1`,`OtherAllowance1`,`MedicalAllowance1`,`Gross1`,`ESIElig`,`PFELig`,`ESIC`,`EPF`,`IncomeTax`,`AdvTaken`,`AdvPaid`,`LoanTaken`,`LoanDed`,`Incentive`,`ExtraDayIncentive`,`Arrear`,`PLI`,`NetSalary`,`ESICCompany`,`EPFCompany`,`AdminChrg`,`CTC`,`SHSH`,`MobileDedcution`,`ShortCollection`,`AssetRecovery`,`Insurance`,`ProTaxDeduction`,`LeaveDeduction`,`OtherDeduction`,`OtherDeductionRemarks`,`TotalDeduction`,`SalDate`,`EPFNo`,`ESICNo`,`ChequeNumber`,`ChequeDate`,`PrintDate`,`LeftStatus`,`SalayDate`) values $list_value"); 
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This month salary process successfully.</span>'); 
                    $url=$this->webroot.'SalaryExports?AX=MTA3';
                    echo "<script>window.location.href = '$url';</script>";die;
                    
                    
                    
                    
                    
                    
        }
        
                    
                    
                    
        }
        
    }
    
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Salary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $m=$_REQUEST['EmpMonth'];
            $y=$_REQUEST['EmpYear'];
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
            $dataArr   =   $this->SalarData->find('all',array('conditions'=>array('Branch'=>$_REQUEST['BranchName'],'date(SalayDate)'=>$SalayDay)));
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>CostCenter</th>
                        <th>Designation</th>
                        <th>Branch</th>
                        <th>Basic</th>
                        <th>HRA</th>
                        <th>Bonus</th>
                        <th>Conv</th>
                        <th>Portfolio</th>
                        <th>MedicalAllowance</th>
                        <th>LTA</th>
                        <th>SpecialAllowance</th>
                        <th>OtherAllowance</th>
                        <th>PLI1</th>
                        <th>Gross</th>
                        <th>WorkingDays</th>
                        <th>CTCOffered</th>
                        <th>CurrentCTC</th>
                        <th>EarnedDays</th>
                        <th>ExtraDay</th>
                        <th>Leave</th>
                        <th>Basic1</th>
                        <th>HRA1</th>
                        <th>Bonus1</th>
                        <th>Conv1</th>
                        <th>Portfolio1</th>
                        <th>SpecialAllowance1</th>
                        <th>OtherAllowance1</th>
                        <th>MedicalAllowance1</th>
                        <th>Gross1</th>
                        <th>ESIElig</th>
                        <th>PFELig</th>
                        <th>ESIC</th>
                        <th>EPF</th>
                        <th>IncomeTax</th>
                        <th>AdvTaken</th>
                        <th>AdvPaid</th>
                        <th>LoanTaken</th>
                        <th>LoanDed</th>
                        <th>Incentive</th>
                        <th>ExtraDayIncentive</th>
                        <th>Arrear</th>
                        <th>PLI</th>
                        <th>NetSalary</th>
                        <th>ESICCompany</th>
                        <th>EPFCompany</th>
                        <th>AdminChrg</th>
                        <th>CTC</th>
                        <th>SHSH</th>
                        <th>MobileDedcution</th>
                        <th>ShortCollection</th>
                        <th>AssetRecovery</th>
                        <th>Insurance</th>
                        <th>ProTaxDeduction</th>
                        <th>LeaveDeduction</th>
                        <th>OtherDeduction</th>
                        <th>OtherDeductionRemarks</th>
                        <th>TotalDeduction</th>
                        <th>SalDate</th>
                        <th>EPFNo</th>
                        <th>ESICNo</th>
                        <th>ChequeNumber</th>
                        <th>ChequeDate</th>
                        <th>PrintDate</th>
                        <th>LeftStatus</th>
                        <th>TaxTotalGross</th>
                        <th>TaxSection10</th>
                        <th>TaxBalance</th>
                        <th>TaxUnderHd</th>
                        <th>DeductionUnder24</th>
                        <th>TaxGrossTotal</th>
                        <th>TaxAggofChapter6</th>
                        <th>TotalIncome</th>
                        <th>TaxOnTotalIncome</th>
                        <th>EduCess</th>
                        <th>TaxPayEduCess</th>
                        <th>TaxDeductedTillPreviousMonth</th>
                        <th>BalanceTax</th>
                        <th>SalaryPaymentMode</th>
                        <!--
                        <th>SalaryBranch</th>
                        <th>SalayDate</th>
                        -->

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ ?>
                    <tr>
                        <td><?php echo $data['SalarData']['EmpCode']?></td>
                        <td><?php echo $data['SalarData']['EmpName']?></td>
                        <td><?php echo $data['SalarData']['CostCenter']?></td>
                        <td><?php echo $data['SalarData']['Designation']?></td>
                        <td><?php echo $data['SalarData']['Branch']?></td>
                        <td><?php echo $data['SalarData']['Basic']?></td>
                        <td><?php echo $data['SalarData']['HRA']?></td>
                        <td><?php echo $data['SalarData']['Bonus']?></td>
                        <td><?php echo $data['SalarData']['Conv']?></td>
                        <td><?php echo $data['SalarData']['Portfolio']?></td>
                        <td><?php echo $data['SalarData']['MedicalAllowance']?></td>
                        <td><?php echo $data['SalarData']['LTA']?></td>
                        <td><?php echo $data['SalarData']['SpecialAllowance']?></td>
                        <td><?php echo $data['SalarData']['OtherAllowance']?></td>
                        <td><?php echo $data['SalarData']['PLI1']?></td>
                        <td><?php echo $data['SalarData']['Gross']?></td>
                        <td><?php echo $data['SalarData']['WorkingDays']?></td>
                        <td><?php echo $data['SalarData']['CTCOffered']?></td>
                        <td><?php echo $data['SalarData']['CurrentCTC']?></td>
                        <td><?php echo $data['SalarData']['EarnedDays']?></td>
                        <td><?php echo $data['SalarData']['ExtraDay']?></td>
                        <td><?php echo $data['SalarData']['Leave']?></td>
                        <td><?php echo $data['SalarData']['Basic1']?></td>
                        <td><?php echo $data['SalarData']['HRA1']?></td>
                        <td><?php echo $data['SalarData']['Bonus1']?></td>
                        <td><?php echo $data['SalarData']['Conv1']?></td>
                        <td><?php echo $data['SalarData']['Portfolio1']?></td>
                        <td><?php echo $data['SalarData']['SpecialAllowance1']?></td>
                        <td><?php echo $data['SalarData']['OtherAllowance1']?></td>
                        <td><?php echo $data['SalarData']['MedicalAllowance1']?></td>
                        <td><?php echo $data['SalarData']['Gross1']?></td>
                        <td><?php echo $data['SalarData']['ESIElig']?></td>
                        <td><?php echo $data['SalarData']['PFELig']?></td>
                        <td><?php echo $data['SalarData']['ESIC']?></td>
                        <td><?php echo $data['SalarData']['EPF']?></td>
                        <td><?php echo $data['SalarData']['IncomeTax']?></td>
                        <td><?php echo $data['SalarData']['AdvTaken']?></td>
                        <td><?php echo $data['SalarData']['AdvPaid']?></td>
                        <td><?php echo $data['SalarData']['LoanTaken']?></td>
                        <td><?php echo $data['SalarData']['LoanDed']?></td>
                        <td><?php echo $data['SalarData']['Incentive']?></td>
                        <td><?php echo $data['SalarData']['ExtraDayIncentive']?></td>
                        <td><?php echo $data['SalarData']['Arrear']?></td>
                        <td><?php echo $data['SalarData']['PLI']?></td>
                        <td><?php echo $data['SalarData']['NetSalary']?></td>
                        <td><?php echo $data['SalarData']['ESICCompany']?></td>
                        <td><?php echo $data['SalarData']['EPFCompany']?></td>
                        <td><?php echo $data['SalarData']['AdminChrg']?></td>
                        <td><?php echo $data['SalarData']['CTC']?></td>
                        <td><?php echo $data['SalarData']['SHSH']?></td>
                        <td><?php echo $data['SalarData']['MobileDedcution']?></td>
                        <td><?php echo $data['SalarData']['ShortCollection']?></td>
                        <td><?php echo $data['SalarData']['AssetRecovery']?></td>
                        <td><?php echo $data['SalarData']['Insurance']?></td>
                        <td><?php echo $data['SalarData']['ProTaxDeduction']?></td>
                        <td><?php echo $data['SalarData']['LeaveDeduction']?></td>
                        <td><?php echo $data['SalarData']['OtherDeduction']?></td>
                        <td><?php echo $data['SalarData']['OtherDeductionRemarks']?></td>
                        <td><?php echo $data['SalarData']['TotalDeduction']?></td>
                        <td><?php echo $data['SalarData']['SalDate']?></td>
                        <td><?php echo $data['SalarData']['EPFNo']?></td>
                        <td><?php echo $data['SalarData']['ESICNo']?></td>
                        <td><?php echo $data['SalarData']['ChequeNumber']?></td>
                        <td><?php echo $data['SalarData']['ChequeDate']?></td>
                        <td><?php echo $data['SalarData']['PrintDate']?></td>
                        <td><?php echo $data['SalarData']['LeftStatus']?></td>
                        <td><?php echo $data['SalarData']['TaxTotalGross']?></td>
                        <td><?php echo $data['SalarData']['TaxSection10']?></td>
                        <td><?php echo $data['SalarData']['TaxBalance']?></td>
                        <td><?php echo $data['SalarData']['TaxUnderHd']?></td>
                        <td><?php echo $data['SalarData']['DeductionUnder24']?></td>
                        <td><?php echo $data['SalarData']['TaxGrossTotal']?></td>
                        <td><?php echo $data['SalarData']['TaxAggofChapter6']?></td>
                        <td><?php echo $data['SalarData']['TotalIncome']?></td>
                        <td><?php echo $data['SalarData']['TaxOnTotalIncome']?></td>
                        <td><?php echo $data['SalarData']['EduCess']?></td>
                        <td><?php echo $data['SalarData']['TaxPayEduCess']?></td>
                        <td><?php echo $data['SalarData']['TaxDeductedTillPreviousMonth']?></td>
                        <td><?php echo $data['SalarData']['BalanceTax']?></td>
                        <td><?php echo $data['SalarData']['SalaryPaymentMode']?></td>
                        <!--
                        <td><?php echo $data['SalarData']['SalaryBranch']?></td>
                        <td><?php echo $data['SalarData']['SalayDate']?></td>
                        -->
                    </tr>
                    <?php }?>
                </tbody>
            </table>
           <?php
           die;
        }
    }
    
    
    
    public function export_report1(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            /*
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AttendanceExport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
             
            */
            
            if($_REQUEST['EmpMonth'] !=""){$m=$_REQUEST['EmpMonth'];$conditoin1['MONTH(AttandDate)']=$_REQUEST['EmpMonth'];}else{$m=date('m');unset($conditoin1['MONTH(AttandDate)']);}
            if($_REQUEST['EmpYear'] !=""){$y=$_REQUEST['EmpYear'];$conditoin1['YEAR(AttandDate)']=$_REQUEST['EmpYear'];}else{$y=date('Y');unset($conditoin1['YEAR(AttandDate)']);}
            
            //$conditoin=array('Status'=>1);
            $conditoin5=array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m);
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['EmpLocation']=$_REQUEST['EmpLocation'];}else{unset($conditoin['EmpLocation']);}
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
            $hcnt   =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5)); 
            ?>
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>EmpCode</th>
                        <th>BioCode</th>
                        <th>EmpName</th>
                        <th>EmpLocation</th>
                        <th>Designation</th>
                        <th>CostCenter</th>
                        <?php for($i=1;$i<=$mwd;$i++){echo "<th>".date('M-d',strtotime("$y-$m-$i"))."</th>";}?>
                        <th style='text-align:center;'>A</th>
                        <th style='text-align:center;'>P</th>
                        <th style='text-align:center;'>OD</th>
                        <th style='text-align:center;'>HD/DH/FTP</th>
                        <th style='text-align:center;'>L</th>
                        <th style='text-align:center;'>H</th>
                        <th style='text-align:center;'>W</th>
                        <th style='text-align:center;'>SalDays</th>
                        <th style='text-align:center;'>Total</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php
                    $OldStatus="";
                    $AttArrOld=array();
                    $OnSiteArr=array();
                    $n=1; foreach ($data as $val){
                    $conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
                    
                    $emp_status=$val['Masjclrentry']['Status'];
                    $emp_regdat=$val['Masjclrentry']['ResignationDate'];

                    if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
                        $AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                        $AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="Field"){
                        $AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                        $OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$_REQUEST['EmpYear']}-{$_REQUEST['EmpMonth']}")));
                        $OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $n++;?></td>
                        <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                        <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                        <td><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                        <td><?php echo $val['Masjclrentry']['Desgination'];?></td>
                        <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                        <?php
                        $OLS=0;
                        $A=0;
                        $P=0;
                        $OD=0;
                        $HD=0;
                        $DH=0;
                        $F=0;
                        $L=0;
                        $TS=$this->total_sundays($m,$y);
                        for($j=1;$j<=$mwd;$j++){
                            $Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
                            
                            if(!empty($AttArrOld)){
                                $OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
                            }
                            
                            if($Status =="A"){$A=$A+1;}
                            if($Status =="P"){$P=$P+1;}
                            if($Status =="OD"){$OD=$OD+1;}
                            if($Status =="HD"){$HD=$HD+1;}
                            if($Status =="DH"){$DH=$DH+1;}
                            if($Status =="F"){$F=$F+1;}
                            if($Status =="L"){$L=$L+1;}
                            if($Status =="HL"){$L=$L+0.5;}
                            if($Status =="FL"){$L=$L+1;$OLS=$OLS+1;}
                            if($Status =="HDL"){$L=$L+1;$OLS=$OLS+1;}
                            if($Status =="DHL"){$L=$L+1;$OLS=$OLS+1;}

                            if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
                                   echo "<td></td>"; 
                                }
                                else{
                                    if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                        if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
                                    }
                                    else{
                                        echo "<td>$OldStatus$Status</td>"; 
                                        //echo "<td>$Status</td>"; 
                                    }
                                }
                        }

                        $AMY=strtotime("$y-$m");
                        if($val['Masjclrentry']['Status']==1){
                            if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['DOJ'])))==$AMY){
                                $mwd1=$this->dayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                $sund=$this->sundayCount($val['Masjclrentry']['DOJ'], "$y-$m-$mwd");
                                $HolDay=$this->HolydayCount($val['Masjclrentry']['DOJ'],"$y-$m-$mwd",$val['Masjclrentry']['BranchName']);

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100); 
                            }
                            else{
                                $HolDay=$hcnt;
                                $TotDay=$mwd-($TS+$HolDay);
                                $FinDay=round(80*$TotDay/100);   
                            }   
                        }
                        else{
                            if(strtotime(date('Y-m',strtotime($val['Masjclrentry']['ResignationDate'])))==$AMY){
                                $mwd1=$this->dayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                $sund=$this->sundayCount("$y-$m-1", $val['Masjclrentry']['ResignationDate']);
                                $HolDay=$this->HolydayCount("$y-$m-1",$val['Masjclrentry']['ResignationDate'],$val['Masjclrentry']['BranchName']);

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100);  
                            }
                            else{
                                $mwd1=0;
                                $sund=0;
                                $HolDay=0;

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100);
                            }
                        }

                        $TotPre=$P+$OD+$L+($HD+$DH+$F)/2;

                        if($TotPre >= $FinDay){
                            $Holiday=$HolDay;  
                        }
                        else{
                            $Holiday=0;
                        }
                            
                        $H=$Holiday;
                        $W=0;
                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $SalDay=$OSC;
                        }
                        else{
                            $SalDay=$P+$OD+$L+$H+($HD+$DH+$F)/2;
                        }

                        if($SalDay >=7){$W=1;}
                        if($SalDay >=12){$W=2;}
                        if($SalDay >=18){$W=3;}
                        if($SalDay >=24){$W=4;}
                        if($TS ==5 && $mwd ==31 && $SalDay >=26){$W=5;}
                        if($TS ==5 && $mwd ==30 && $SalDay >=25){$W=5;}
                        
                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $W=0;
                        }
                           
                        $Total=($SalDay+$W);

                        if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >$OSC</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                            echo "<td style='text-align:center;' >0</td>";
                        }
                        else{
                        ?>
                        <td style='text-align:center;'><?php echo $A;?></td>
                        <td style='text-align:center;'><?php echo $P;?></td>
                        <td style='text-align:center;'><?php echo $OD;?></td>
                        <td style='text-align:center;'><?php echo ($HD+$DH+$F)+$OLS;?></td>
                            <td style='text-align:center;'><?php echo $L-($OLS/2);?></td>
                        <td style='text-align:center;'><?php echo $H;?></td>
                        <?php }?>

                        <td style='text-align:center;'><?php echo $W;?></td>
                        <td style='text-align:center;'><?php echo $SalDay;?></td>
                        <td style='text-align:center;'><?php echo $Total;?></td>
                    </tr>
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