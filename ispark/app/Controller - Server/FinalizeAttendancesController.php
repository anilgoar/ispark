<?php
class FinalizeAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster','HolidayMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export_report','total_employees1','total_employees','show_report');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $fieldArr=array();
        
        $ProcessDate=date('Y', strtotime(date('Y-m')." -1 month"))."-".date('m', strtotime(date('Y-m')." -1 month"));

        $data = $this->ProcessAttendanceMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ProcessMonth'=>$ProcessDate),'group' =>array('CostCenter')));
        foreach($data as $val){
            $fieldArr[]=array(
                'Id'=>$val['ProcessAttendanceMaster']['Id'],
                'CostCenter'=>$val['ProcessAttendanceMaster']['CostCenter'],
                'Status'=>$this->process_status($val['ProcessAttendanceMaster']['CostCenter'],$branchName),
                'TotalEmp'=>($this->total_employees($val['ProcessAttendanceMaster']['CostCenter'],$branchName)+$this->total_employees1($val['ProcessAttendanceMaster']['CostCenter'],$branchName))
            );
        }
        $this->set('fieldArr',$fieldArr);
        
        if($this->request->is('Post')){
            $CostCenter=$this->request->data['CostCenter'];
            $Id=$this->request->data['Id'];
            $status="Yes";
            $dataArr=array(
                'FinializeStatus'=>"'".$status."'",
                'FinializeDate'=>"'".date('Y-m-d H:i:s')."'",
            );
             
            if($this->ProcessAttendanceMaster->updateAll($dataArr,array('Id'=>$Id))){
                $this->Session->setFlash('<span style="color:green;" >Your attendance Finalize successfully.</span>');
            }
            else{
                $this->Session->setFlash('<span style="color:red;" >Your attendance not Finalize please try again later.</span>');
            }
            $this->redirect(array('controller'=>'FinalizeAttendances','action'=>'index'));     
        }
        
        
    }
    
    public function show_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['CostCenter']) && $_REQUEST['CostCenter'] !=""){
            $branchName = $this->Session->read('branch_name');
            $m=date('m', strtotime(date('Y-m')." -1 month"));
            $y=date('Y', strtotime(date('Y-m')." -1 month"));
            
            //$conditoin  =   array('Status'=>1,'CostCenter'=>$_REQUEST['CostCenter']);
            
            $conditoin  =   array('Status'=>1,'CostCenter'=>$_REQUEST['CostCenter']);
            $conditoin1 =   array('MONTH(AttandDate)'=>$m,'YEAR(AttandDate)'=>$y);
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            
            //$data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode')); 
            
            $data_1     =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode')); 
            $data_2     =   $this->Masjclrentry->find('all', array('conditions' =>array('MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'CostCenter'=>$_REQUEST['CostCenter'],'Status'=>0)));
            
            $data       = array_merge($data_1,$data_2);
            
            
            $conditoin5 =   array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m,'BranchName'=>$branchName);
            $hcnt       =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5));
            
            
            $ProcessStatus  =   $this->process_status($_REQUEST['CostCenter'],$branchName);
            if(!empty($data)){
            ?>
            <div class="col-sm-12">
                <input type="hidden" name="CostCenter" value="<?php echo $_REQUEST['CostCenter']; ?>" >
                <input type="hidden" name="Id" value="<?php echo $_REQUEST['Id']; ?>" >
                <input type="button" onclick="ExportFinalize('<?php echo $_REQUEST['CostCenter']; ?>');" value="Export" class="btn pull-right btn-primary btn-new" style="margin-left:10px;" >
                <?php if($ProcessStatus ==0){ ?>
                <input type="submit" value="Process" class="btn pull-right btn-primary btn-new">
                <?php }?>
            </div>
            <div class="col-sm-12">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;">SNo</th>
                            <th style="text-align: center;;width:70px;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="text-align: center;width:70px;">EmpLocation</th>
                            <th style="text-align: center;width:50px;">TotalDays</th>
                            <th style="text-align: center;width:30px;">A</th>
                            <th style="text-align: center;width:30px;">P</th>
                            <th style="text-align: center;width:30px;">OD</th>
                            <th style="text-align: center;width:70px;">HD/DH/FTP</th>
                            <th style="text-align: center;width:30px;">L</th>
                            <th style="text-align: center;width:30px;">H</th>
                            <th style="text-align: center;width:30px;">W</th>
                            <th style="text-align: center;width:60px;">SalDays</th>
                            <th style="text-align: center;width:100px;">Total Process</th>
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
                            
                        
                        $conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
                        
                        if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
                            $AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                            $AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
                        }
                        else if($val['Masjclrentry']['EmpLocation'] =="Field"){
                            $AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                        }
                        else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                            $OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$y}-{$m}")));
                            $OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
                        }
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td style="text-align: center;" ><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                            <td style="text-align: center;" ><?php echo date('d', strtotime('last day of previous month'));?></td>
                            
                            
                            <?php
                            $DOJ            =   $val['Masjclrentry']['DOJ'];
                            $A=0;
                            $P=0;
                            $OD=0;
                            $HD=0;
                            $DH=0;
                            $F=0;
                            $L=0;
                            $TS=$this->total_sundays($m,$y);
                            for($j=1;$j<=$mwd;$j++){
                                
                                if(strtotime("$y-$m-$j") >= strtotime($DOJ)){
                                
                                $Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
                                
                                if(!empty($AttArrOld)){
                                    $OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
                                }
                                
                                if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
                                   //echo "<td></td>"; 
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
                                if($Status =="FL"){$L=$L+1;}
                                if($Status =="HDL"){$L=$L+1;}
                                if($Status =="DHL"){$L=$L+1;}
                                
                                if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                    //if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
                                }
                                else{
                                    //echo "<td>$OldStatus$Status</td>"; 
                                }
                                
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
                            <td style="text-align: center;"><?php echo $A;?></td>
                            <td style="text-align: center;"><?php echo $P;?></td>
                            <td style="text-align: center;"><?php echo $OD;?></td>
                            <td style="text-align: center;"><?php echo ($HD+$DH+$F);?></td>
                            <td style="text-align: center;"><?php echo $L;?></td>
                            <td style="text-align: center;"><?php echo $H;?></td>
                            <?php }?>
                            
                            <td style="text-align: center;"><?php echo $W;?></td>
                            <td style="text-align: center;"><?php echo $SalDay;?></td>
                            
                            
                            <?php
                            $branch_name    =   $val['Masjclrentry']['BranchName'];
                            $cost_center    =   $val['Masjclrentry']['CostCenter'];
                            $emp_desig      =   $val['Masjclrentry']['Desgination'];
                            
                            //$branch_name        =   "HYDERABAD";
                            //$cost_center        =   "CS/OB/HYD/021";
                            //$emp_Desgination    =   "EXECUTIVE - VOICE";
                            
                            $total_month_day    =   date('d', strtotime('last day of previous month'));
                            
                            $total_month_day    =   date('d', strtotime('last day of previous month'));
                            
                            if($Total > $total_month_day){
                                
                                $oversalArr=$this->Masjclrentry->query("SELECT over_saldays FROM `cost_master` WHERE branch='$branch_name' AND cost_center='$cost_center' AND active='1' limit 1");
                                
                                
                                
                                $over_salary_status=$oversalArr[0]['cost_master']['over_saldays'];
                                
                                if($val['Masjclrentry']['EmpLocation'] =="InHouse" && $over_salary_status =="Yes"){

                                    if($emp_desig=="EXECUTIVE - VOICE" || $emp_desig=="Executive - Voice" || $emp_desig=="Sr. Executive - Voice"  || $emp_desig=="SR. EXECUTIVE - VOICE"){
                                        $total_final_day=$Total;
                                    }
                                    else{
                                        $total_final_day=$total_month_day;
                                    }
                                }
                                else{
                                    $total_final_day=$total_month_day;
                                }
                            }
                            else{
                                $total_final_day=$Total;
                            }
                            ?>
                            
                            
                            <td style="text-align: center;"><center><input style="width: 50px;text-align: center;" type="text" value="<?php echo $total_final_day;?>" readonly="" ></center></td>
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
        if(isset($_REQUEST['CostCenter']) && $_REQUEST['CostCenter'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=FinalizeExport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $m=date('m', strtotime(date('Y-m')." -1 month"));
            $y=date('Y', strtotime(date('Y-m')." -1 month"));
            
            $conditoin  =   array('Status'=>1,'CostCenter'=>$_REQUEST['CostCenter']);
            $conditoin1 =   array('MONTH(AttandDate)'=>$m,'YEAR(AttandDate)'=>$y);
            
            $mwd    =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            
            //$data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode'));
            $data_1     =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'EmpCode')); 
            $data_2     =   $this->Masjclrentry->find('all', array('conditions' =>array('MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'CostCenter'=>$_REQUEST['CostCenter'],'Status'=>0)));
            
            $data       = array_merge($data_1,$data_2);
            
            $conditoin5 =   array('YEAR(HolydayDate)'=>$y,'MONTH(HolydayDate)'=>$m,'BranchName'=>$branchName);
            $hcnt       =   $this->HolidayMaster->find('count',array('conditions'=>$conditoin5));
            
            
            ?>
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>EmpLocation</th>
                        <th>TotalDays</th>
                        <th style="text-align: center;">A</th>
                        <th style="text-align: center;">P</th>
                        <th style="text-align: center;">OD</th>
                        <th style="text-align: center;">HD/DH/FTP</th>
                        <th style="text-align: center;">L</th>
                        <th style="text-align: center;">H</th>
                        <th style="text-align: center;">W</th>
                        <th style="text-align: center;">SalDays</th>
                        <th style="text-align: center;">Total Process</th>
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


                    $conditoin1['EmpCode']=$val['Masjclrentry']['EmpCode'];
                    

                    if($val['Masjclrentry']['EmpLocation'] =="InHouse"){
                        $AttArr   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                        $AttArrOld   =   $this->Masattandance->find('list',array('fields'=>array('AttandDate','OldStatus'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="Field"){
                        $AttArr   =   $this->FieldAttendanceMaster->find('list',array('fields'=>array('AttandDate','Status'),'conditions'=>$conditoin1));
                    }
                    else if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                        $OnSiteArr   =   $this->OnSiteAttendanceMaster->find('first',array('fields'=>array('SalDays'),'conditions'=>array('EmpCode'=>$val['Masjclrentry']['EmpCode'],'SalMonth'=>"{$y}-{$m}")));
                        $OSC=$OnSiteArr['OnSiteAttendanceMaster']['SalDays'];
                    }
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                        <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpLocation'];?></td>
                        <td style="text-align: center;"><?php echo date('d', strtotime('last day of previous month'));?></td>
                        
                        <?php
                        $DOJ            =   $val['Masjclrentry']['DOJ'];
                        $A=0;
                        $P=0;
                        $OD=0;
                        $HD=0;
                        $DH=0;
                        $F=0;
                        $L=0;
                        $TS=$this->total_sundays($m,$y);
                        for($j=1;$j<=$mwd;$j++){
                            
                            if(strtotime("$y-$m-$j") >= strtotime($DOJ)){
                            
                            $Status=$AttArr[date('Y-m-d',strtotime("$y-$m-$j"))];
                            
                            if(!empty($AttArrOld)){
                                $OldStatus=$AttArrOld[date('Y-m-d',strtotime("$y-$m-$j"))];
                            }
                            
                            if($emp_status ==0 && date('Y-m-d',strtotime("$y-$m-$j")) > $emp_regdat){
                               //echo "<td></td>"; 
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
                            if($Status =="FL"){$L=$L+1;}
                            if($Status =="HDL"){$L=$L+1;}
                            if($Status =="DHL"){$L=$L+1;}

                            if($val['Masjclrentry']['EmpLocation'] =="OnSite"){
                                //if($j <=$OSC){echo "<td>P</td>";}else{echo "<td></td>";}
                            }
                            else{
                                //echo "<td>$OldStatus$Status</td>"; 
                            }
                            
                            
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
                        <td style="text-align: center;"><?php echo $A;?></td>
                        <td style="text-align: center;"><?php echo $P;?></td>
                        <td style="text-align: center;"><?php echo $OD;?></td>
                        <td style="text-align: center;"><?php echo ($HD+$DH+$F);?></td>
                        <td style="text-align: center;"><?php echo $L;?></td>
                        <td style="text-align: center;"><?php echo $H;?></td>
                        <?php }?>

                        <td style="text-align: center;"><?php echo $W;?></td>
                        <td style="text-align: center;"><?php echo $SalDay;?></td>
                        
                        
                        <?php
                            $branch_name    =   $val['Masjclrentry']['BranchName'];
                        $cost_center    =   $val['Masjclrentry']['CostCenter'];
                        $emp_desig      =   $val['Masjclrentry']['Desgination'];

                        //$branch_name        =   "HYDERABAD";
                        //$cost_center        =   "CS/OB/HYD/021";
                        //$emp_Desgination    =   "EXECUTIVE - VOICE";

                        $total_month_day    =   date('d', strtotime('last day of previous month'));

                        if($Total > $total_month_day){

                            $oversalArr=$this->Masjclrentry->query("SELECT over_saldays FROM `cost_master` WHERE branch='$branch_name' AND cost_center='$cost_center' AND active='1' limit 1");
                            $over_salary_status=$oversalArr[0]['cost_master']['over_saldays'];

                            if($val['Masjclrentry']['EmpLocation'] =="InHouse" && $over_salary_status =="Yes"){
                                if($emp_desig=="EXECUTIVE - VOICE" || $emp_desig=="Executive - Voice" || $emp_desig=="Sr. Executive - Voice"  || $emp_desig=="SR. EXECUTIVE - VOICE"){
                                    $total_final_day=$Total;
                                }
                                else{
                                    $total_final_day=$total_month_day;
                                }
                            }
                            else{
                                $total_final_day=$total_month_day;
                            }
                        }
                        else{
                            $total_final_day=$Total;
                        }
                            ?>
                        
                        
                        <td style="text-align: center;"><?php echo $total_final_day;?></td>
                    </tr>
                    <?php }?>
                </tbody>   
            </table>
            <?php 
            die;
        }
        
    }
     
    public function existMark($emcod,$BranchName,$CostCenter){
        return $this->OnSiteAttendanceMaster->find('first',array('conditions'=>array('EmpCode'=>$emcod,'BranchName'=>$BranchName,'CostCenter'=>$CostCenter,'SalMonth'=>date('Y-m', strtotime(date('Y-m')." -1 month"))))); 
    }
    
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
    public function total_employees1($CostCenter,$branchName){
        $m=date('m', strtotime(date('Y-m')." -1 month"));
        $y=date('Y', strtotime(date('Y-m')." -1 month"));
        return $this->Masjclrentry->find('count', array('conditions' => array('BranchName'=>$branchName,'MONTH(ResignationDate) >='=>$m,'YEAR(ResignationDate)'=>$y,'CostCenter'=>$CostCenter,'Status'=>0)));
    }
    
    public function process_status($CostCenter,$branchName){
        $m=date('m', strtotime(date('Y-m')." -1 month"));
        $y=date('Y', strtotime(date('Y-m')." -1 month"));
        return $this->ProcessAttendanceMaster->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>"$y-$m",'FinializeStatus'=>'Yes')));
    }
    
    
    
    public function total_sundays($month,$year){
        $sundays=0;
        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i=1;$i<=$total_days;$i++)
        if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
        $sundays++;
        return $sundays;
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
    
    
}
?>