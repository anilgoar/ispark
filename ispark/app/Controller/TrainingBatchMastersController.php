<?php
class TrainingBatchMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','TrainerMaster','CostCenterMaster','TrainingBatchMaster','HolidayMaster','TrainingRoomMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource','get_emp','gettrainerdata','getcostcenter','getcostcenteredit','getprocessname',
        'gettrainername','gettrainernameedit','getenddate','sundayCount','getdurationday');
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
        
        $this->set('room',$this->TrainingRoomMaster->find('list',array('fields'=>array('Room','Room'),'conditions'=>array('BranchName'=>$branchName),'order'=>array('Room'))));
        $this->set('data',$this->TrainingBatchMaster->find('all',array('conditions'=>array('BranchName'=>$branchName))));
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->TrainingBatchMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('dataArr',$row['TrainingBatchMaster']);  
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            
            $Branch                     =   $request['TrainingBatchMasters']['branch_name'];
            $CostCenter                 =   $request['CostCenter'];
            $ProcessName                =   $request['ProcessName'];
            $BatchCount                 =   $request['BatchCount'];
            $DurationDays               =   $request['DurationDays'];
            $StartDate                  =   date('Y-m-d',strtotime($request['StartDate']));
            $EndDate                    =   date('Y-m-d',strtotime($request['EndDate']));
            //$BatchCode                  =   $request['BatchCode'];
            
            $TrainerName                =   $request['TrainerName'];
            $TrainingRoomAvailibility   =   $request['TrainingRoomAvailibility'];
            $TrainingRoom               =   $request['TrainingRoom'];
            $Remarks                    =   $request['Remarks'];
            $TrainingBatchId            =   $request['Id'];
            $BatchCode                  =   $CostCenter."/B-".$TrainingBatchId;
            $backid                     =   $request['backid'];
            
            if($request['Submit'] =="Update"){
                
                $updArr=array(
                    'BranchName'=>"'".$Branch."'",
                    'CostCenter'=>"'".$CostCenter."'",
                    'ProcessName'=>"'".$ProcessName."'",
                    'BatchCount'=>"'".$ProcessName."'",
                    'DurationDays'=>"'".$ProcessName."'",
                    'StartDate'=>"'".$ProcessName."'",
                    'EndDate'=>"'".$ProcessName."'",
                    'TrainerName'=>"'".$TrainerName."'",
                    'TrainingRoomAvailibility'=>"'".$TrainingRoomAvailibility."'",
                    'TrainingRoom'=>"'".$TrainingRoom."'",
                    'Remarks'=>"'".$Remarks."'",
                    'BatchCode'=>"'".$BatchCode."'",
                    );
                
                $this->TrainingBatchMaster->updateAll($updArr,array('Id'=>$TrainingBatchId));
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Training batch update successfully.</span>');
                $this->redirect(array('action'=>'index','?'=>array('backid'=>$backid)));
                
            }
            else{

                $exist=$this->TrainingBatchMaster->find('first',array('conditions'=>array('BranchName'=>$Branch,'date(EndDate) >='=>$StartDate,'TrainingRoom'=>$TrainingRoom,'TrainingStatus !='=>'Closed')));
                
                if(empty($exist)){
                    $data=array(
                        'BranchName'=>$Branch,
                        'CostCenter'=>$CostCenter,
                        'ProcessName'=>$ProcessName,
                        'BatchCount'=>$BatchCount,
                        'DurationDays'=>$DurationDays,
                        'StartDate'=>$StartDate,
                        'EndDate'=>$EndDate,
                        'TrainerName'=>$TrainerName,
                        'TrainingRoomAvailibility'=>$TrainingRoomAvailibility,
                        'TrainingRoom'=>$TrainingRoom,
                        'Remarks'=>$Remarks,
                        'TrainingStatus'=>'Proposed',
                        
                    );
                    
                    $this->TrainingBatchMaster->saveAll($data);
                    $lastid     =   $this->TrainingBatchMaster->getLastInsertId();
                    $BatchCode  =   $CostCenter."/B-".$lastid;
                    $this->TrainingBatchMaster->updateAll(array('BatchCode'=>"'".$BatchCode."'",),array('Id'=>$lastid));
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Training batch created successfully</span>');
                    $this->redirect(array('action'=>'index','?'=>array('backid'=>$backid)));
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Training batch already exist in database.</span>');
                    $this->redirect(array('action'=>'index','?'=>array('backid'=>$backid)));
                }
            }
            
        }
   
    }
    
    public function get_emp(){
        $this->layout='ajax';
        
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array("EmpName"),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$_REQUEST['BranchName'],
                    'EmpCode'=>$_REQUEST['EmpCode'],
                    'CostCenter'=>$_REQUEST['CostCenter'],
                    )
                ));
             
            if(!empty($data)){
                echo $data['Masjclrentry']['EmpName'];die;
            }
            else{
                echo "";die;
            }
        }
    }
    
    public function getprocessname(){
        $this->layout='ajax';
        
        if(isset($_REQUEST['BranchName']) && trim($_REQUEST['BranchName']) !=""){ 
            
            $data = $this->CostCenterMaster->find('first',array(
                'fields'=>array("process_name"),
                'conditions'=>array(
                    'active'=>1,
                    'branch'=>$_REQUEST['BranchName'],
                    'cost_center'=>$_REQUEST['CostCenter'],
                    )
                ));

            if(!empty($data)){
                echo $data['CostCenterMaster']['process_name'];die;
            }
            else{
                echo "";die;
            }
        }
    }
    
    public function getdurationday(){
        $this->layout='ajax';
        
        if(isset($_REQUEST['BranchName']) && trim($_REQUEST['BranchName']) !=""){ 
            
            $data = $this->CostCenterMaster->find('first',array(
                'fields'=>array("training_days"),
                'conditions'=>array(
                    'active'=>1,
                    'branch'=>$_REQUEST['BranchName'],
                    'cost_center'=>$_REQUEST['CostCenter'],
                    )
                ));

            if(!empty($data)){
                echo $data['CostCenterMaster']['training_days'];die;
            }
            else{
                echo "";die;
            }
        }
    }
    
    public function getenddate(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['StartDate']) && trim($_REQUEST['StartDate']) !=""){ 
            $day        =   $_REQUEST['DurationDays'];
            $firstDate  =   date("Y-m-d",strtotime($_REQUEST['StartDate']));
            $lastDate   =   date('Y-m-d',strtotime('+ '.$day.' days',strtotime($_REQUEST['StartDate'])));
            $holiday    =  $this->HolidayMaster->query("SELECT COUNT(Id) AS holiday FROM `HolidayMaster`  WHERE BranchName='{$_REQUEST['BranchName']}' AND DATE(HolydayDate) BETWEEN '$firstDate' AND '$lastDate'");
            $TH         =  $holiday[0][0]['holiday'];
            $TS         =  $this->sundayCount($firstDate, $lastDate);
            $newday     =  ($day+$TH+$TS);
            echo    date('d-M-Y',strtotime('+ '.$newday.' days',strtotime($_REQUEST['StartDate'])));die;  
        }
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            
            $this->TrainingBatchMaster->query("DELETE FROM `TrainingBatchMaster` WHERE Id='$id'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This Trainer details delete successfully.</span>');
            $this->redirect(array('action'=>'index','?'=>array('backid'=>$_REQUEST['backid'])));
        }
     
    }
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
            /*
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            */
            $conditoin=array('active'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['branch']=$_REQUEST['BranchName'];}else{unset($conditoin['branch']);}
            
            $data = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>$conditoin,'group' =>array('cost_center')));
        
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    
                    
                    
                    echo "<option  value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            } 
        }  
    }
    
    public function gettrainername(){
        if(isset($_REQUEST['BranchName']) && isset($_REQUEST['CostCenter'])){
            
            $CostCenter =   $_REQUEST['CostCenter'];
            $BranchName =   $_REQUEST['BranchName'];

            $data = $this->TrainerMaster->find('list',array('fields'=>array('TrainerName','TrainerName'),'conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter)));
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach ($data as $val){
                    echo "<option  value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            } 
        }  
    }
    
    public function gettrainernameedit(){
        if(isset($_REQUEST['BranchName']) && isset($_REQUEST['CostCenter'])){
            
            $CostCenter =   $_REQUEST['CostCenter'];
            $BranchName =   $_REQUEST['BranchName'];
            $TrainerName =   $_REQUEST['TrainerName'];
            
            
            $data = $this->TrainerMaster->find('list',array('fields'=>array('TrainerName','TrainerName'),'conditions'=>array('BranchName'=>$BranchName,'CostCenter'=>$CostCenter)));
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach ($data as $val){
                     if($TrainerName ==$val){$selected="selected='selected'";}else{$selected='';}
                    echo "<option $selected  value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            } 
        }  
    }
    
    public function getcostcenteredit(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $CostCenter =   $_REQUEST['CostCenter'];
            
            /*
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            */
            
            $conditoin=array('active'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['branch']=$_REQUEST['BranchName'];}else{unset($conditoin['branch']);}
            
            $data = $this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>$conditoin,'group' =>array('cost_center')));
            
            
            if(!empty($data)){
                //echo "<option value=''>Select</option>";
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    if($CostCenter ==$val){$selected="selected='selected'";}else{$selected='';}
                    echo "<option $selected value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            } 
        }  
    }
    
    
    public function gettrainerdata(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            
            $data=$this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
            if(!empty($data)){
            ?>
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>CostCenter</th>
                        <th>ProcessName</th>
                        <th>BatchCode</th>
                        <th>TrainerName</th>
                        <th>Count</th>
                        <th>StartDate</th>
                        <th>EndDate</th>
                        <th>Status</th>
                        <th>Room</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $row['TrainingBatchMaster']['CostCenter']?></td>
                        <td><?php echo $row['TrainingBatchMaster']['ProcessName']?></td>
                        <td><?php echo $row['TrainingBatchMaster']['BatchCode']?></td>
                        <td><?php echo $row['TrainingBatchMaster']['TrainerName']?></td>
                        <td><?php echo $row['TrainingBatchMaster']['BatchCount']?></td>
                        <td><?php echo date("d-M-Y",strtotime($row['TrainingBatchMaster']['StartDate'])); ?></td>
                        <td><?php echo date("d-M-Y",strtotime($row['TrainingBatchMaster']['EndDate'])); ?></td>
                         <td><?php echo $row['TrainingBatchMaster']['TrainingStatus']?></td>
                        <td><?php echo $row['TrainingBatchMaster']['TrainingRoom']?></td>
                        <td style="text-align: center;">
                            <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingBatchMasters?id=<?php echo base64_encode($row['TrainingBatchMaster']['Id']);?>','edit');" class="material-icons" style="font-size:20px;cursor: pointer;" >search</i></span>
                            <?php if($row['TrainingBatchMaster']['ApproveFirst']==""){ ?>
                            <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingBatchMasters/deletesource?id=<?php echo base64_encode($row['TrainingBatchMaster']['Id']);?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            <?php }?>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
            }else{
                echo "Record Not Found.";die;
            }
        }
    }
    
    function sundayCount($from, $to) {        
        $start = new DateTime($from);
        $end = new DateTime($to);
        $days = $start->diff($end, true)->days;

        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
        return $sundays;  
    }
    
}
?>