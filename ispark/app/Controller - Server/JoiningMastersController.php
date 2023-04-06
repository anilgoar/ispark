<?php
class JoiningMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','ChangeDojMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','check_date','approveho','get_loan_details','check_date1');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';      
        $branchName = $this->Session->read('branch_name');
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $data       =   $this->request->data;
            $BranchName =   $data['JoiningMasters']['branch_name'];
            $EmpCode    =   $data['EmpCode'];
            $OldDoj     =   date('Y-m-d',strtotime($data['OldDoj']));
            $NewDoj     =   date('Y-m-d',strtotime($data['StartDate']));
            $Reason     =   $data['Reason'];
            $GarArr     =   $this->Masjclrentry->find('first',array('fields'=>array("EmpName"),'conditions'=>array('Status'=>1,'BranchName'=>$BranchName,'EmpCode'=>$EmpCode)));
            $EmpName    =   $GarArr['Masjclrentry']['EmpName'];
            $Num        =   $this->ChangeDojMaster->find('count',array('conditions'=>array('OldDOJ'=>$OldDoj,'BranchName'=>$BranchName,'EmpCode'=>$EmpCode,'ApproveStatus'=>NULL)));
            
            $dataArr=array(
                'BranchName'=>$BranchName,
                'EmpCode'=>$EmpCode,
                'EmpName'=>$EmpName,
                'OldDOJ'=>$OldDoj,
                'NewDOJ'=>$NewDoj,
                'Remarks'=>$Reason,
            );
            
            if($Num > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your change joining date request already exist in database.</span>'); 
                $this->redirect(array('action'=>'index')); 
            }
            else{
                if($this->ChangeDojMaster->save($dataArr)){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your change joining date request save scccessfully.</span>');      
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your change joining date request failed please try again later</span>');   
                }
                $this->redirect(array('action'=>'index'));  
            }
        }     
    }
    
    public function approveho(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $branch_name    =   $this->request->data['JoiningMasters']['branch_name'];
            $conditoin      =   array('ApproveStatus'=>NULL);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
                
            if(isset($this->request->data['Submit'])){
                $SubmitType=$this->request->data['Submit'];
                if($SubmitType !=""){
                    if($SubmitType =="Approve"){
                        $status="Yes";
                    }
                    else if($SubmitType =="Not Approve"){
                       $status="No";
                    }

                    if(isset($this->request->data['check'])){
                        $OdIdArr=$this->request->data['check'];
                        foreach ($OdIdArr as $Id){
                            $rowArr    =   $this->ChangeDojMaster->find('first',array('conditions'=>array('Id'=>$Id)));
                            $BName =   $rowArr['ChangeDojMaster']['BranchName'];
                            $ECode =   $rowArr['ChangeDojMaster']['EmpCode'];
                            $NeDOJ =   $rowArr['ChangeDojMaster']['NewDOJ'];
                            $Remarks =   $rowArr['ChangeDojMaster']['Remarks'];
                            
                            $this->ChangeDojMaster->updateAll(array('ApproveStatus'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id));
                            
                            if($status =="Yes"){
                                $this->Masjclrentry->updateAll(array('DOJ'=>"'".date('Y-m-d',strtotime($NeDOJ))."'",'remarks'=>"'".$Remarks."'"),array('BranchName'=>$BName,'EmpCode'=>$ECode));
                            }
                        }
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your request update successfully.</span>'); 
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please select option to approve/not approve request.</span>'); 
                    }
                }
            }
                    
            $this->set('OdArr',$this->ChangeDojMaster->find('all',array('conditions'=>$conditoin)));  
        }     
    }
    
    public function check_date(){ 
        $FromDate1   =   strtotime('-1 month', strtotime($_REQUEST['oldjoin']));
        $ToDate1     =   strtotime($_REQUEST['newjoin']); 
        
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['oldjoin']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['newjoin']));
        
        if($ToDate >=$FromDate){
            echo '1';die;
        }
        else if($ToDate1 <=$FromDate1){
            echo '1';die;
        }
        else{
            echo '';die;
        }
    }
    
    public function check_date1(){ 
        $cm     =   strtotime($_REQUEST['oldjoin']); 
        $nm     =   strtotime('+1 month', strtotime($_REQUEST['oldjoin']));
        $nd     =   strtotime(date('Y-m')); 
        
        //if($nd >= $cm &&  $nd <= $nm){
        
        if($nd >= $cm ||  $nd <= $nm){
            echo '';die;
        }
        else{
            echo '1';die;
        }
    }
    
    public function get_loan_details(){
        $this->layout='ajax';
        if(isset($_REQUEST['EmpCode']) && trim($_REQUEST['EmpCode']) !=""){ 
            $branchName = $_REQUEST['BranchName'];
            $EmpCode    = trim($_REQUEST['EmpCode']);
            $data = $this->Masjclrentry->find('first',array(
                'fields'=>array('EmpCode','EmpName','BranchName','BioCode','DOJ','Desgination','CostCenter'),
                'conditions'=>array(
                    'Status'=>1,
                    'BranchName'=>$branchName,
                    'EmpCode'=>$EmpCode,
                    )
                ));
            
            if(!empty($data)){?>
            <input type="hidden" id="oldjoin" name="OldDoj" value="<?php echo $data['Masjclrentry']['DOJ']; ?>" >
            <label class="col-sm-1 control-label"></label>
            <div class="col-sm-10">
            <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable"> 
                    <thead>
                        <tr>                              
                            <th style="text-align:center;width:80px;">EmpCode</th> 
                            <th >EmpName</th>        
                            <th style="text-align:center;width:100px;">Branch</th>        
                            <th style="text-align:center;">CostCenter</th>        
                            <th style="text-align:center;width:150px;">Designation</th>        
                            <th style="text-align:center;width:80px;">DOJ</th>        
                            <th style="text-align:center;width:60px;">Bio Code</th> 
                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <td style="text-align:center;"><?php echo $data['Masjclrentry']['EmpCode'];?></td>
                            <td ><?php echo $data['Masjclrentry']['EmpName'];?></td>
                            <td style="text-align:center;"><?php echo $data['Masjclrentry']['BranchName'];?></td>
                            <td style="text-align:center;"><?php echo $data['Masjclrentry']['CostCenter'];?></td>
                            <td style="text-align:center;"><?php echo $data['Masjclrentry']['Desgination'];?></td>
                            <td style="text-align:center;"><?php echo date('d-M-Y',strtotime($data['Masjclrentry']['DOJ']));?></td>
                            <td style="text-align:center;"><?php echo $data['Masjclrentry']['BioCode'];?></td>
                        </tr> 
                    </tbody>           
                </table> 
            </div>
            <?php      
            }
            else{
                echo "";
            }
        }
        die;  
    }
    
    

      
}
?>