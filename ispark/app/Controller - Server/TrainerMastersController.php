<?php
class TrainerMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','TrainerMaster','CostCenterMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource','get_emp','gettrainerdata','getcostcenter','getcostcenteredit','getprocessname');
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
       
        $this->set('data',$this->TrainerMaster->find('all',array('conditions'=>array('BranchName'=>$branchName))));
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $row=$this->TrainerMaster->find('first',array('conditions'=>array('Id'=>base64_decode($_REQUEST['id']))));
            $this->set('dataArr',$row['TrainerMaster']);  
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['TrainerMasters']['branch_name'];
            $CostCenter     =   $request['CostCenter'];
            $ProcessName    =   $request['ProcessName'];
            $EmpCode        =   $request['EmpCode'];
            $TrainerName    =   $request['TrainerName'];
            $Contact        =   $request['Contact'];
            $EmailId        =   $request['EmailId'];
            $Remarks        =   $request['Remarks'];
            $TrainerId      =   $request['Id'];
            $backid         =   $request['backid'];
            
            if($request['Submit'] =="Update"){
                $updArr=array(
                    'BranchName'=>"'".$Branch."'",
                    'CostCenter'=>"'".$CostCenter."'",
                    'ProcessName'=>"'".$ProcessName."'",
                    'EmpCode'=>"'".$EmpCode."'",
                    'TrainerName'=>"'".$TrainerName."'",
                    'Contact'=>"'".$Contact."'",
                    'EmailId'=>"'".$EmailId."'",
                    'Remarks'=>"'".$Remarks."'",
                    );
                
                
                
                $this->TrainerMaster->updateAll($updArr,array('Id'=>$TrainerId));
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This trainer details update successfully.</span>');
                $this->redirect(array('action'=>'index','?'=>array('backid'=>$backid)));
            }
            else{
                $exist=$this->TrainerMaster->find('first',array('conditions'=>array('BranchName'=>$Branch,'CostCenter'=>$CostCenter,'EmpCode'=>$EmpCode)));
                if(empty($exist)){
                    $data=array(
                        'BranchName'=>$Branch,
                        'CostCenter'=>$CostCenter,
                        'ProcessName'=>$ProcessName,
                        'EmpCode'=>$EmpCode,
                        'TrainerName'=>$TrainerName,
                        'Contact'=>$Contact,
                        'EmailId'=>$EmailId,
                        'Remarks'=>$Remarks,
                    );
                    
                    $this->TrainerMaster->saveAll($data);
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Trainer Details save successfully</span>');
                    $this->redirect(array('action'=>'index','?'=>array('backid'=>$backid)));
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Trainer Details already exist in database.</span>');
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
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            $this->TrainerMaster->query("DELETE FROM `TrainerMaster` WHERE Id='$id'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This Trainer details delete successfully.</span>');
            $this->redirect(array('action'=>'index','?'=>array('backid'=>$_REQUEST['backid'])));
        }
     
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
                    
                    
                    
                    echo "<option  value='$val'>$val</option>";
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
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
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
            
            $data=$this->TrainerMaster->find('all',array('conditions'=>$conditoin));
            if(!empty($data)){
            ?>
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>BranchName</th>
                        <th>CostCenter</th>
                        <th>EmpCode</th>
                        <th>TrainerName</th>
                        <th>Contact</th>
                        <th>EmailId</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $row['TrainerMaster']['BranchName']?></td>
                        <td><?php echo $row['TrainerMaster']['CostCenter']?></td>
                        <td><?php echo $row['TrainerMaster']['EmpCode']?></td>
                        <td><?php echo $row['TrainerMaster']['TrainerName']?></td>
                        <td><?php echo $row['TrainerMaster']['Contact']?></td>
                        <td><?php echo $row['TrainerMaster']['EmailId']?></td>
                        <td><?php echo $row['TrainerMaster']['Remarks']?></td>
                        <td style="text-align: center;">
                            <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainerMasters?id=<?php echo base64_encode($row['TrainerMaster']['Id']);?>','edit');" class="material-icons" style="font-size:20px;cursor: pointer;" >search</i></span>
                            <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainerMasters/deletesource?id=<?php echo base64_encode($row['TrainerMaster']['Id']);?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
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
    
}
?>