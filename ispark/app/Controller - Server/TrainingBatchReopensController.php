<?php
class TrainingBatchReopensController extends AppController {
    public $uses = array('Addbranch','TrainingStatusMaster','TrainingAllocationMaster','TrainingBatchMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','getbatchcode','showtrainingdetails','extenddate','reopenbatch');
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
                            <td style="text-align:center;"><?php echo $data['TrainingBatchMaster']['BatchCount']?></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['StartDate'] !=""){echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['StartDate']));}?></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['ActualStartDate'] !=""){ echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['ActualStartDate']));}?></td>
                            <td style="text-align:center;"><?php if($data['TrainingBatchMaster']['EndDate'] !=""){echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['EndDate']));}?></td>
                            <td style="text-align:center;">
                                <?php if($data['TrainingBatchMaster']['ActualEndDate'] !=""){ echo date('d-M-Y',strtotime($data['TrainingBatchMaster']['ActualEndDate']));}?>
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
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $i=1; foreach ($dataArr as $post){ ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i++; ?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['BioCode'];?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['EmpName'];?></td>
                            <td style="text-align:center;"><?php echo $post['TrainingAllocationMaster']['Status'];?></td>
                        </tr>
                        <?php } ?>   
                    </tbody>   
                </table>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-sm-3 control-label">BatchStatus</label>
                    <div class="col-sm-6">
                        <select id="BatchStatus" name="BatchStatus"  autocomplete="off" class="form-control" >
                            <option value="">Select</option>
                            <option value="Initiated">Initiated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Remarks</label>
                    <div class="col-sm-9">
                        <textarea id="ReopenRemarks" name="ReopenRemarks" autocomplete="off" class="form-control" ></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="button" onclick="ReopenBatch('<?php echo $_REQUEST['BatchCode'];?>');" value="Submit" class="btn pull-right btn-primary btn-new">  
                    </div>
                </div> 
            </div>

            <?php } ?>
            <?php
            die;
            }else{
                echo "Record Not Found.";die;
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
    
    public function reopenbatch(){
        if($_REQUEST['BatchCode'] !=""){
            $BatchCode      =   $_REQUEST['BatchCode'];
            $BatchStatus    =   $_REQUEST['BatchStatus'];
            $ReopenRemarks  =   $_REQUEST['ReopenRemarks'];
            $ReopenDate     =   date('Y-m-d H:i:s');
            
            $this->TrainingBatchMaster->query("UPDATE TrainingBatchMaster SET TrainingStatus='$BatchStatus',ReopenRemarks='$ReopenRemarks',ReopenDate='$ReopenDate' WHERE BatchCode='$BatchCode'");
            die;
        }
    }
  
}
?>