<?php
class TrainingViewDetailsController extends AppController {
    public $uses = array('Addbranch','TrainingStatusMaster','TrainingAllocationMaster','TrainingBatchMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','showtrainingdetails','viewupdateform');
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
    
    public function showtrainingdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('MONTH(StartDate)'=>$_REQUEST['EmpMonth'],'YEAR(StartDate)'=>$_REQUEST['EmpYear']);           
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['BatchStatus'] !="ALL"){$conditoin['TrainingStatus']=$_REQUEST['BatchStatus'];}else{unset($conditoin['TrainingStatus']);}

            $dataRes=$this->TrainingBatchMaster->find('all',array('conditions'=>$conditoin));
            if(!empty($dataRes)){
            ?>
            <div class="col-sm-12">
                <table class = "table table-striped table-hover  responstable">     
                    <thead>
                        <tr>
                            <th style="text-align:center;" >SNo</th>
                            <th style="text-align:center;" >Branch</th>
                            <th style="text-align:center;" >Trainer Name</th>
                            <th style="text-align:center;">Batch Code</th>
                            <th style="text-align:center;">Batch Count </th>
                            <th style="text-align:center;">Start Date</th>
                            <th style="text-align:center;">Actual Start Date</th>
                            <th style="text-align:center;">End Date</th>
                            <th style="text-align:center;">Actual End Date</th>
                            <th style="text-align:center;">Batch Status</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n=1; foreach ($dataRes as $data){ ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $n++; ?></td>
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
                            <td style="text-align:center;"><i class="material-icons" onclick="AllocateDetails('<?php echo $data['TrainingBatchMaster']['BatchCode'];?>')" style="font-size:20px;cursor: pointer;">edit</i></td>
                        </tr>
                         <?php }?>
                    </tbody>   
                </table>
            </div>
        
            <?php
            die;
            }else{
                echo '<div class="col-sm-6" style="overflow-y: scroll;height: 350px;" >Record Not Found.</div>';die;
            }
        }   
    }
    
 
    public function viewupdateform(){
        $dataArr        =   $this->TrainingAllocationMaster->find('all',array('conditions'=>array('BatchCode'=>$_REQUEST['BatchCode'])));
        if(!empty($dataArr)){
        ?>  
        <div class="col-sm-6">
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
        <?php die; }else{
            echo '<div class="col-sm-6"  >Record Not Found.</div>';die;
        }
    }
}
?>