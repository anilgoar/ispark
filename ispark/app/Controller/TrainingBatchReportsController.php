<?php
class TrainingBatchReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','TrainingStatusMaster','TrainingAllocationMaster','TrainingBatchMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','show_mis','getcostcenter','export_report','export_mis','getbatchcode');
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
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['EmpYear'],'MONTH(StartDate)'=>$_REQUEST['EmpMonth'],);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            $data = $this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
              
            if(!empty($data)){   
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align:center;" >SNo</th>
                            <th style="text-align:center;">Branch</th>
                            <th style="text-align:center;">Cost Center</th>
                            <th style="text-align:center;">Batch Status</th>
                            <th style="text-align:center;">Start Date</th>
                            <th style="text-align:center;">End Date</th>
                            <th style="text-align:center;">Actual Start Date</th>
                            <th style="text-align:center;">Actual End Date</th>
                            <th style="text-align:center;">Current Day</th>
                            <th style="text-align:center;">Traine Name</th>
                            <th style="text-align:center;">Batch Code</th>
                            <th style="text-align:center;">Day1 Count</th>
                            <th style="text-align:center;">Training Att</th>
                            <th style="text-align:center;">Attrition Percent</th>
                            <th style="text-align:center;">Total Cert</th>
                            <th style="text-align:center;">HandOver</th>
                            <th style="text-align:center;">Active In Training</th>
                            <th style="text-align:center;">First Attempt Percent</th>
                            <th style="text-align:center;">ThroughPut</th>
                            <th style="text-align:center;">HandOver Percent</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                            
                        $Day1Count      =   0;
                        $CerCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Certified')));
                        $RecCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Recertified')));
                        $Day1Count      =   $CerCount+$RecCount;
                        $ActiveInTra    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Intraining')));
                        
                        $TrainingAtt    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>"BatchCode='{$val['TrainingBatchMaster']['BatchCode']}' and Status!='Certified' and Status!='Recertified'"));
                        $TrainingAttPer =   ($TrainingAtt/$Day1Count*100);

                        if($CerCount == $val['TrainingBatchMaster']['BatchCount']){
                            $FAPER="100%";
                        }
                        else{
                            $FAPER="80%"; 
                        }
                        
                        $HandOver       =   $Day1Count;
                        $HandOverPer    =   ($HandOver/$Day1Count*100); 
                        $TotalCer       =   $Day1Count;
                        $ThroughPut     =   ($HandOver/$Day1Count*100);
                        
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $n++;?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['BranchName'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['CostCenter'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['TrainingStatus'];?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['StartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['StartDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['EndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['EndDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualEndDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){echo $this->dayCount($val['TrainingBatchMaster']['ActualStartDate'], date("Y-m-d"));}?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['TrainerName'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['BatchCode'];?></td>
                            <td style="text-align:center;"><?php echo $Day1Count;?></td>
                            <td style="text-align:center;"><?php echo $TrainingAtt;?></td>
                            <td style="text-align:center;"><?php echo $TrainingAttPer;?></td>
                            <td style="text-align:center;"><?php echo $TotalCer;?></td>
                            <td style="text-align:center;"><?php echo $HandOver;?></td>
                            <td style="text-align:center;"><?php echo $ActiveInTra;?></td>
                            <td style="text-align:center;"><?php echo $FAPER;?></td>
                            <td style="text-align:center;"><?php echo $ThroughPut;?></td>
                            <td style="text-align:center;"><?php echo $HandOverPer;?></td>
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
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=TrainingBatchReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['EmpYear'],'MONTH(StartDate)'=>$_REQUEST['EmpMonth'],);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            $data = $this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
              
            if(!empty($data)){   
            ?>
           
                <table border="1" >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Branch</th>
                            <th>Cost Center</th>
                            <th>Batch Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actual Start Date</th>
                            <th>Actual End Date</th>
                            <th>Current Day</th>
                            <th>Traine Name</th>
                            <th>Batch Code</th>
                            <th>Day1 Count</th>
                            <th>Training Att</th>
                            <th>Attrition Percent</th>
                            <th>Total Cert</th>
                            <th>HandOver</th>
                            <th>Active In Training</th>
                            <th>First Attempt Percent</th>
                            <th>ThroughPut</th>
                            <th>HandOver Percent</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                            
                        $Day1Count      =   0;
                        $CerCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Certified')));
                        $RecCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Recertified')));
                        $Day1Count      =   $CerCount+$RecCount;
                        $ActiveInTra    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Intraining')));
                        $TrainingAtt    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>"BatchCode='{$val['TrainingBatchMaster']['BatchCode']}' and Status!='Certified' and Status!='Recertified'"));
                        $TrainingAttPer =   ($TrainingAtt/$Day1Count*100);
                        
                        if($CerCount == $val['TrainingBatchMaster']['BatchCount']){
                            $FAPER="100%";
                        }
                        else{
                            $FAPER="80%"; 
                        }
                        
                        $HandOver       =   $Day1Count;
                        $HandOverPer    =   ($HandOver/$Day1Count*100); 
                        $TotalCer       =   $Day1Count;
                        $ThroughPut     =   ($HandOver/$Day1Count*100);
                        
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['TrainingBatchMaster']['BranchName'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['CostCenter'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['TrainingStatus'];?></td>
                            <td><?php if($val['TrainingBatchMaster']['StartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['StartDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['EndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['EndDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualEndDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){echo $this->dayCount($val['TrainingBatchMaster']['ActualStartDate'], date("Y-m-d"));}?></td>
                            <td><?php echo $val['TrainingBatchMaster']['TrainerName'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['BatchCode'];?></td>
                            <td><?php echo $Day1Count;?></td>
                            <td><?php echo $TrainingAtt;?></td>
                            <td><?php echo $TrainingAttPer;?></td>
                            <td><?php echo $TotalCer;?></td>
                            <td><?php echo $HandOver;?></td>
                            <td><?php echo $ActiveInTra;?></td>
                            <td><?php echo $FAPER;?></td>
                            <td><?php echo $ThroughPut;?></td>
                            <td><?php echo $HandOverPer;?></td>
                        </tr>
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
    
    public function show_mis(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['EmpYear'],'MONTH(StartDate)'=>$_REQUEST['EmpMonth'],);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            $data = $this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
              
            if(!empty($data)){
                
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align:center;">SNo</th>
                            <th style="text-align:center;">Branch</th>
                            <th style="text-align:center;">Cost Center</th>
                            <th style="text-align:center;">Batch Status</th>
                            <th style="text-align:center;">Start Date</th>
                            <th style="text-align:center;">End Date</th>
                            <th style="text-align:center;">Actual Start Date</th>
                            <th style="text-align:center;">Actual End Date</th>
                            <th style="text-align:center;">Current Day</th>
                            <th style="text-align:center;">Traine Name</th>
                            <th style="text-align:center;">Batch Code</th>
                            <th style="text-align:center;">Batch Count</th>
                            <th style="text-align:center;">Not Joined</th>
                            <th style="text-align:center;">RHR</th>
                            <th style="text-align:center;">Day1 Count</th>
                            <th style="text-align:center;">Voluntary</th>
                            <th style="text-align:center;">Involuntary</th>
                            <th style="text-align:center;">Training Attrition</th>
                            <th style="text-align:center;">Training Attrition%</th>
                            <th style="text-align:center;">Certifiedin Ist Attempt</th>
                            <th style="text-align:center;">Certified in IInd Attempt</th>
                            <th style="text-align:center;">Total Certified</th>
                            <th style="text-align:center;">HandOver</th>
                            <th style="text-align:center;">Active In Training</th>
                            <th style="text-align:center;">First Attemp %</th>
                            <th style="text-align:center;">ThroughPut</th>
                            <th style="text-align:center;">HandOver %</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        $Day1Count      =   0;
                        $CerCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Certified')));
                        $RecCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Recertified')));
                        $Day1Count      =   $CerCount+$RecCount;
                        $RHR            =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'RHR')));
                        $NotJoined      =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Not Joined')));
                        $ActiveInTra    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Intraining')));
                        $Voluntary      =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Voluntary Attrition')));
                        $Involuntary    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Non Voluntary Attrition')));
                        
                        $TrainingAtt    =   ($NotJoined+$RHR+$Voluntary+$Involuntary);
                        $TrainingAttPer =   ($TrainingAtt/$Day1Count*100);
                        
                        if($CerCount == $val['TrainingBatchMaster']['BatchCount']){
                            $FAPER="100%";
                        }
                        else{
                            $FAPER="80%"; 
                        }
                        
                        
                        

                        $HandOver       =   ($CerCount+$RecCount);
                        $HandOverPer    =   ($HandOver/$Day1Count*100); 
                        $TotalCer       =   $Day1Count;
                        $ThroughPut     =   ($HandOver/$Day1Count*100);
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $n++;?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['BranchName'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['CostCenter'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['TrainingStatus'];?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['StartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['StartDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['EndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['EndDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualEndDate']));}?></td>
                            <td style="text-align:center;"><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){echo $this->dayCount($val['TrainingBatchMaster']['ActualStartDate'], date("Y-m-d"));}?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['TrainerName'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['BatchCode'];?></td>
                            <td style="text-align:center;"><?php echo $val['TrainingBatchMaster']['BatchCount'];?></td>
                            <td style="text-align:center;"><?php echo $NotJoined;?></td>
                            <td style="text-align:center;"><?php echo $RHR;?></td>
                            <td style="text-align:center;"><?php echo $Day1Count;?></td>
                            <td style="text-align:center;"><?php echo $Voluntary;?></td>
                            <td style="text-align:center;"><?php echo $Involuntary;?></td>
                            <td style="text-align:center;"><?php echo $TrainingAtt;?></td>
                            <td style="text-align:center;"><?php echo $TrainingAttPer;?></td>
                            <td style="text-align:center;"><?php echo $CerCount;?></td>
                            <td style="text-align:center;"><?php echo $RecCount;?></td>
                            <td style="text-align:center;"><?php echo $TotalCer;?></td>
                            <td style="text-align:center;"><?php echo $HandOver;?></td>
                            <td style="text-align:center;"><?php echo $ActiveInTra;?></td>
                            <td style="text-align:center;"><?php echo $FAPER;?></td>
                            <td style="text-align:center;"><?php echo $ThroughPut;?></td>
                            <td style="text-align:center;"><?php echo $HandOverPer;?></td>
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
    
    public function export_mis(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=TrainingMisReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['EmpYear'],'MONTH(StartDate)'=>$_REQUEST['EmpMonth'],);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            $data = $this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
              
            if(!empty($data)){
                
            ?>
           
                <table border="1" >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Branch</th>
                            <th>Cost Center</th>
                            <th>Batch Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actual Start Date</th>
                            <th>Actual End Date</th>
                            <th>Current Day</th>
                            <th>Traine Name</th>
                            <th>Batch Code</th>
                            <th>Batch Count</th>
                            <th>Not Joined</th>
                            <th>RHR</th>
                            <th>Day1 Count</th>
                            <th>Voluntary</th>
                            <th>Involuntary</th>
                            <th>Training Attrition</th>
                            <th>Training Attrition%</th>
                            <th>Certifiedin Ist Attempt</th>
                            <th>Certified in IInd Attempt</th>
                            <th>Total Certified	</th>
                            <th>HandOver</th>
                            <th>Active In Training</th>
                            <th>First Attemp %</th>
                            <th>ThroughPut</th>
                            <th>HandOver %</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        $Day1Count      =   0;
                        $CerCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Certified')));
                        $RecCount       =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Recertified')));
                        $Day1Count      =   $CerCount+$RecCount;
                        $RHR            =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'RHR')));
                        $NotJoined      =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Not Joined')));
                        $ActiveInTra    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Intraining')));
                        $Voluntary      =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Voluntary Attrition')));
                        $Involuntary    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$val['TrainingBatchMaster']['BatchCode'],'Status'=>'Non Voluntary Attrition')));
                        
                        $TrainingAtt    =   ($NotJoined+$RHR+$Voluntary+$Involuntary);
                        $TrainingAttPer =   ($TrainingAtt/$Day1Count*100);
                        
                        if($CerCount == $val['TrainingBatchMaster']['BatchCount']){
                            $FAPER="100%";
                        }
                        else{
                            $FAPER="80%"; 
                        }
                        
                        $HandOver       =   ($CerCount+$RecCount);
                        $HandOverPer    =   ($HandOver/$Day1Count*100); 
                        $TotalCer       =   $Day1Count;
                        $ThroughPut     =   ($HandOver/$Day1Count*100);
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['TrainingBatchMaster']['BranchName'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['CostCenter'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['TrainingStatus'];?></td>
                            <td><?php if($val['TrainingBatchMaster']['StartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['StartDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['EndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['EndDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date("d M Y",strtotime($val['TrainingBatchMaster']['ActualEndDate']));}?></td>
                            <td><?php if($val['TrainingBatchMaster']['ActualStartDate'] !=""){echo $this->dayCount($val['TrainingBatchMaster']['ActualStartDate'], date("Y-m-d"));}?></td>
                            <td><?php echo $val['TrainingBatchMaster']['TrainerName'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['BatchCode'];?></td>
                            <td><?php echo $val['TrainingBatchMaster']['BatchCount'];?></td>
                            <td><?php echo $NotJoined;?></td>
                            <td><?php echo $RHR;?></td>
                            <td><?php echo $Day1Count;?></td>
                            <td><?php echo $Voluntary;?></td>
                            <td><?php echo $Involuntary;?></td>
                            <td><?php echo $TrainingAtt;?></td>
                            <td><?php echo $TrainingAttPer;?></td>
                            <td><?php echo $CerCount;?></td>
                            <td><?php echo $RecCount;?></td>
                            <td><?php echo $TotalCer;?></td>
                            <td><?php echo $HandOver;?></td>
                            <td><?php echo $ActiveInTra;?></td>
                            <td><?php echo $FAPER;?></td>
                            <td><?php echo $ThroughPut;?></td>
                            <td><?php echo $HandOverPer;?></td>
                        </tr>
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