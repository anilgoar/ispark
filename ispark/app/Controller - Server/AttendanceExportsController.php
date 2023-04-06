<?php
class AttendanceExportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster');
        
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
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
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
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['EmpLocation']=$_REQUEST['EmpLocation'];}else{unset($conditoin['EmpLocation']);}
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            
            
            //$data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
            
            
           
            
            $data_1     =   $this->Masjclrentry->find('all',array('conditions'=>array_merge($conditoin,array('Status'=>1)),'group'=>'EmpCode')); 
            $data_2     =   $this->Masjclrentry->find('all', array('conditions' =>array_merge($conditoin,array('MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'Status'=>0))));
            $data       =   array_merge($data_1,$data_2);
            
           
          
            
            
            
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
                                    $HolDay=$hcnt;
                                    $TotDay=$mwd-($TS+$HolDay);
                                    $FinDay=round(80*$TotDay/100);
                                    
                                    /*
                                    $mwd1=0;
                                    $sund=0;
                                    $HolDay=0;
                                    
                                    $TotDay=$mwd1-($sund+$HolDay);
                                    $FinDay=round(80*$TotDay/100); 
                                    */
                                }
                            }
                            
                            //$TotPre=$P+$OD+$L+($HD+$DH+$F)/2;
                            $TotPre=$P+$OD+$L+($HD+$DH+$F);
                            
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
    
    
    
    
    

    
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AttendanceExport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            if($_REQUEST['EmpMonth'] !=""){$m=$_REQUEST['EmpMonth'];$conditoin1['MONTH(AttandDate)']=$_REQUEST['EmpMonth'];}else{$m=date('m');unset($conditoin1['MONTH(AttandDate)']);}
            if($_REQUEST['EmpYear'] !=""){$y=$_REQUEST['EmpYear'];$conditoin1['YEAR(AttandDate)']=$_REQUEST['EmpYear'];}else{$y=date('Y');unset($conditoin1['YEAR(AttandDate)']);}
            
            //$conditoin=array('Status'=>1);
            $conditoin5=array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m);
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            if($_REQUEST['EmpLocation'] !="ALL"){$conditoin['EmpLocation']=$_REQUEST['EmpLocation'];}else{unset($conditoin['EmpLocation']);}
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            //$data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
            
            $data_1     =   $this->Masjclrentry->find('all',array('conditions'=>array_merge($conditoin,array('Status'=>1)),'group'=>'EmpCode')); 
            $data_2     =   $this->Masjclrentry->find('all', array('conditions' =>array_merge($conditoin,array('MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'Status'=>0))));
            $data       =   array_merge($data_1,$data_2);
            
            
            
            
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
                                $HolDay=$hcnt;
                                $TotDay=$mwd-($TS+$HolDay);
                                $FinDay=round(80*$TotDay/100);
                                
                                /*
                                $mwd1=0;
                                $sund=0;
                                $HolDay=0;

                                $TotDay=$mwd1-($sund+$HolDay);
                                $FinDay=round(80*$TotDay/100); 
                                */
                                
                            }
                        }

                        //$TotPre=$P+$OD+$L+($HD+$DH+$F)/2;
                        
                        $TotPre=$P+$OD+$L+($HD+$DH+$F);

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
            
            //$conditoin=array('Status'=>1);
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