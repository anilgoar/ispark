<?php
class TrainingBatchsDetailsController extends AppController {
    public $uses = array('Addbranch','TrainingStatusMaster','TrainingAllocationMaster','TrainingBatchMaster','MasJclrentrydata');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getbatchcode','showtrainingdetails','viewupdateform','getsubstatus','editsubstatus',
        'updateallocationstatus','getstatus','closetrainingbatch','extenddate','extendnewbatchcount');
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
        
        $this->set('data',$this->TrainingStatusMaster->find('list',array('fields'=>array('Id','Name'),'conditions'=>array('ParentId'=>NULL))));
    }
    
    public function getbatchcode(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('YEAR(StartDate)'=>$_REQUEST['BatchYear'],'MONTH(StartDate)'=>$_REQUEST['BatchMonth']);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->TrainingBatchMaster->find('list',array('fields'=>array('BatchCode','BatchCode'),'conditions'=>$conditoin));
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
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

    public function showtrainingdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BatchCode']) && $_REQUEST['BatchCode'] !=""){
            $data=$this->TrainingBatchMaster->find('first',array('conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'])));
            if(!empty($data)){
            $dataArr        =   $this->TrainingAllocationMaster->find('all',array('conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'])));
            $StatusCount    =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'],'Status'=>NULL)));
            
           
            ?>
            <div class="col-sm-12">
                <table class = "table table-striped table-hover  responstable">     
                    <thead>
                        <tr>
                            <th style="text-align:center;" >Branch</th>
                            <th style="text-align:center;" >Trainer Name</th>
                            <th style="text-align:center;">Batch Code</th>
                            <th style="text-align:center;">Batch Count </th>
                            <th style="text-align:center;">Start Date</th>
                            <th style="text-align:center;">Actual Start Date</th>
                            <th style="text-align:center;">End Date</th>
                            <th style="text-align:center;">Actual End Date</th>
                            <th style="text-align:center;">Batch Status</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <td style="text-align:center;"><?php echo $data['TrainingBatchMaster']['BranchName']?></td>
                            <td style="text-align:center;"><?php echo $data['TrainingBatchMaster']['TrainerName']?></td>
                            <td style="text-align:center;"><?php echo $data['TrainingBatchMaster']['BatchCode']?></td>
                            <td style="text-align:center;"><a data-toggle="modal" data-target="#myModal1" style="cursor: pointer;"  ><?php echo $data['TrainingBatchMaster']['BatchCount']?></a></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['StartDate'] !=""){echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['StartDate']));}?></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['EndDate'] !=""){echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['EndDate']));}?></td>
                            <td style="text-align:center;">
                                <?php if($data['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['ActualEndDate']));}?><br/>
                                <?php if($data['TrainingBatchMaster']['TrainingStatus'] !="Closed"){?>
                                <a data-toggle="modal" data-target="#myModal" style="cursor: pointer;"  >Extend</a>
                                <?php }?>
                                <input type="hidden" id="BatchCode" value="<?php echo $_REQUEST['BatchCode'];?>" >
                            </td>
                            <td style="text-align:center;"><?php echo $data['TrainingBatchMaster']['TrainingStatus']?></td>
                        </tr>
                    </tbody>   
                </table>
            </div>
            <?php if(!empty($dataArr)){?>
            <div class="col-sm-6" style="overflow-y: scroll;height: 350px;" >
                <table class = "table table-striped table-hover  responstable">     
                    <thead>
                        <tr>
                            <th style="text-align:center;" >SNo</th>
                            <th style="text-align:center;" >Bio Code</th>
                            <th style="text-align:center;">Trainee Name</th>
                            <th style="text-align:center;">Status</th>
                            <?php if($data['TrainingBatchMaster']['TrainingStatus'] !="Closed"){?>
                            <th style="text-align:center;">Action</th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $i=1; foreach ($dataArr as $post){ ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i++; ?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['BioCode'];?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['EmpName'];?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['Status'];?></td>
                            <?php if($data['TrainingBatchMaster']['TrainingStatus'] !="Closed"){?>
                            <td style="text-align:center;"><i class="material-icons" onclick="AllocateDetails('<?php echo $post['TrainingAllocationMaster']['Id'];?>')" style="font-size:20px;cursor: pointer;">edit</i></td>
                            <?php }?>
                           
                        </tr>
                        <?php } ?>   
                    </tbody>   
                </table>
                <?php if($StatusCount ==0 && $data['TrainingBatchMaster']['TrainingStatus'] !="Closed"){?>
                <input type="button" onclick="CloseTraining('<?php echo $_REQUEST['BatchCode'];?>');" value="Close" class="btn pull-right btn-primary btn-new">  
                <?php } ?>
            </div>
            <?php } ?>
            <?php
            die;
            }else{
                echo "Record Not Found.";die;
            }
        }   
    }
    
    public function getsubstatus(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $data = $this->TrainingStatusMaster->find('list',array('fields'=>array('Name','Name'),'conditions'=>array('ParentId'=>$_REQUEST['Id'])));
            if(!empty($data)){
                echo "<option value=''>Select</option>";
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
    
    public function editsubstatus(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $SubStatus  =   $_REQUEST['SubStatus'];
            $data = $this->TrainingStatusMaster->find('list',array('fields'=>array('Name','Name'),'conditions'=>array('ParentId'=>$_REQUEST['Id'])));
            if(!empty($data)){
                echo "<option value=''>Select</option>";
                foreach ($data as $val){
                    if($SubStatus ==$val){$selected="selected='selected'";}else{$selected="";}
                    echo "<option $selected value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }   
        } 
    }
    
    public function viewupdateform(){
        $dataArr=$this->TrainingAllocationMaster->find('first',array('conditions'=>array('Id'=>$_REQUEST['Id'])));
        if(!empty($dataArr)){
            if($dataArr['TrainingAllocationMaster']['CertificationDate'] !=""){
                $dataArr['TrainingAllocationMaster']['CertificationDate']=date('d-M-Y',strtotime($dataArr['TrainingAllocationMaster']['CertificationDate']));
            }
            if($dataArr['TrainingAllocationMaster']['HandOverDate'] !=""){
                $dataArr['TrainingAllocationMaster']['HandOverDate']=date('d-M-Y',strtotime($dataArr['TrainingAllocationMaster']['HandOverDate']));
            }
            if($dataArr['TrainingAllocationMaster']['AtritionDate'] !=""){
                $dataArr['TrainingAllocationMaster']['AtritionDate']=date('d-M-Y',strtotime($dataArr['TrainingAllocationMaster']['AtritionDate']));
            }
            echo json_encode($dataArr['TrainingAllocationMaster']);die; 
        }
        else{
            echo "";die;
        }
        die;
    }
    
    public function updateallocationstatus(){
        if($_REQUEST['Id'] !=""){
            
            $Id                     =   $_REQUEST['Id'];
            $StatusId               =   $_REQUEST['Status'];
            $Status                 =   $this->getstatus($StatusId);
            $SubStatus              =   $_REQUEST['SubStatus'];
            if($_REQUEST['CertificationDate'] !=""){$CertificationDate = date('Y-m-d',strtotime($_REQUEST['CertificationDate']));}else{ $CertificationDate="";}
            $CertificationScore     =   $_REQUEST['CertificationScore'];
            $RecertificationScore   =   $_REQUEST['RecertificationScore'];
            if($_REQUEST['HandOverDate'] !=""){$HandOverDate = date('Y-m-d',strtotime($_REQUEST['HandOverDate']));}else{ $HandOverDate="";}
            if($_REQUEST['AtritionDate'] !=""){$AtritionDate = date('Y-m-d',strtotime($_REQUEST['AtritionDate']));}else{ $AtritionDate="";}
            $Remarks                =   $_REQUEST['Remarks'];
            
            $this->TrainingAllocationMaster->query("UPDATE TrainingAllocationMaster SET StatusId='$StatusId',Status='$Status',SubStatus='$SubStatus',CertificationDate='$CertificationDate',CertificationScore='$CertificationScore',RecertificationScore='$RecertificationScore',HandOverDate='$HandOverDate',AtritionDate='$AtritionDate',Remarks='$Remarks'  WHERE Id='$Id'");
            
            $TAM=$this->TrainingAllocationMaster->find('first',array('fields'=>array('BioCode'),'conditions'=>array('Id'=>$Id)));
            $BioCode=$TAM['TrainingAllocationMaster']['BioCode'];
            
            if($CertificationDate !=""){
                $this->MasJclrentrydata->query("UPDATE mas_Jclrentrydata SET CertifiedDate='$CertificationDate' WHERE BioCode='$BioCode'");
            }
            
            die;
        }
    }
    
    public function closetrainingbatch(){
        if($_REQUEST['BatchCode'] !=""){
            $BatchCode              =   $_REQUEST['BatchCode'];
            $ActualEndDate          =   date('Y-m-d');
            $TrainingStatus         =   "Closed";
            
            $BatchCountArr          =   $this->TrainingBatchMaster->find('first',array('fields'=>array('BatchCount'),'conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'])));
            $BatchCount             =   $BatchCountArr['TrainingBatchMaster']['BatchCount'];
            $AllocationCount        =   $this->TrainingAllocationMaster->find('count',array('conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'])));
            
            if($BatchCount ==$AllocationCount){
                $this->TrainingBatchMaster->query("UPDATE TrainingBatchMaster SET ActualEndDate='$ActualEndDate',TrainingStatus='$TrainingStatus' WHERE BatchCode='$BatchCode'");
                echo "1";die;
            }
            else{
                echo "";die;
            }
            
        }
    }
    
    public function extenddate(){
        if($_REQUEST['BatchCode'] !=""){
            $BatchCode      =   $_REQUEST['BatchCode'];
            $ActualEndDate  =   date('Y-m-d',strtotime($_REQUEST['ExtendDate']));
            $ExtendRemarks  =   $_REQUEST['ExtendRemarks'];
            
            $this->TrainingBatchMaster->query("UPDATE TrainingBatchMaster SET ActualEndDate='$ActualEndDate',ExtendRemarks='$ExtendRemarks' WHERE BatchCode='$BatchCode'");
            die;
        }
    }
    
    public function extendnewbatchcount(){
        if($_REQUEST['BatchCode'] !=""){
            $BatchCode      =   $_REQUEST['BatchCode'];
            $NewBatchcount  =   $_REQUEST['NewBatchcount'];
            
            $this->TrainingBatchMaster->query("UPDATE TrainingBatchMaster SET BatchCount='$NewBatchcount' WHERE BatchCode='$BatchCode'");
            die;
        }
    }
    
    






    public function getstatus($Id){
        $data = $this->TrainingStatusMaster->find('first',array('fields'=>array('Name'),'conditions'=>array('Id'=>$Id)));
        return $data['TrainingStatusMaster']['Name'];
    }
    
}
?>