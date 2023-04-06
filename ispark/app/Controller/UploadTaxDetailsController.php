<?php
class UploadTaxDetailsController extends AppController {
    public $uses = array('Addbranch','IncomtaxMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','discard_tax_details');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $data       =   $this->request->data;
            $TaxMonth   =   $data['EmpMonth'];
            $UserId     =   $this->Session->read('email');

            $csv_file   =   $_FILES['UploadEcs']['tmp_name'];
            $FileTye    =   $_FILES['UploadEcs']['type'];
            $info       =   explode(".",$_FILES['UploadEcs']['name']);
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if(($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                    $totalcolumn    =   count($filedata);
                    if($totalcolumn ==4){   
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $EmpCode    =   addslashes(trim($data[0]));
                            $EmpName    =   addslashes(trim($data[1]));
                            $BranchName =   addslashes(trim($data[2]));
                            $IncomTax   =   addslashes(trim($data[3]));
                            
                            $TaxMaster  =   $this->IncomtaxMaster->query("SELECT * FROM `IncomtaxMaster` WHERE EmpCode='$EmpCode' AND TaxMonth='$TaxMonth'");

                            if(empty($TaxMaster)){
                                $this->IncomtaxMaster->query("INSERT INTO `IncomtaxMaster` SET EmpType='OnRoll',EmpCode='$EmpCode',EmpName='$EmpName',BranchName='$BranchName',TaxMonth='$TaxMonth',IncomTax='$IncomTax',ImportBy='$UserId'");
                            }
                            else{
                                $this->IncomtaxMaster->query("UPDATE `IncomtaxMaster` SET IncomTax='$IncomTax',UpdateDate=NOW(),UpdateBy='$UserId' WHERE EmpCode='$EmpCode' AND TaxMonth='$TaxMonth'");
                            }
                        }
                        
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Tax details upload successfully.</span>'); 
                        $this->redirect(array('action'=>'index'));  
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your csv column does not match.</span>'); 
                        $this->redirect(array('action'=>'index'));  
                    }
		}
            }
            else{
		$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Please upload only csv file.</span>'); 
                $this->redirect(array('action'=>'index'));  
            }
        }
    }
    
    public function discard_tax_details(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){
            $data       =   $this->request->data;
            $TaxMonth   =   $data['EmpMonth'];
            $EmpCode    =   $data['EmpCode'];
            $UserId     =   $this->Session->read('email');
            
            $TaxMaster  =   $this->IncomtaxMaster->query("SELECT * FROM `IncomtaxMaster` WHERE EmpCode='$EmpCode' AND TaxMonth='$TaxMonth' limit 1");

            if(empty($TaxMaster)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This employee tax details not exist in database.</span>'); 
                $this->redirect(array('action'=>'discard_tax_details'));
            }
            else{
                $row    =   $TaxMaster[0]['IncomtaxMaster'];
                $this->IncomtaxMaster->query("INSERT INTO `IncomtaxMasterHistory` SET Id='{$row['Id']}',EmpType='OnRoll',EmpCode='{$row['EmpCode']}',EmpName='{$row['EmpName']}',BranchName='{$row['BranchName']}',TaxMonth='{$row['TaxMonth']}',IncomTax='{$row['IncomTax']}',ImportBy='{$row['ImportBy']}',DiscardBy='$UserId',DiscardDate=NOW()");
                
                $this->IncomtaxMaster->query("DELETE FROM `IncomtaxMaster` WHERE Id='{$row['Id']}'");
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This employee tax details discard successfully.</span>'); 
                $this->redirect(array('action'=>'discard_tax_details')); 
            }
        }  
    }
    

    
    /*
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=EcsNumber.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $CostCenter =   $_REQUEST['CostCenter'];
            $exp        =   explode("-", $_REQUEST['EmpMonth']);
            $m          =   $exp[1];
            $y          =   $exp[0];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
            
            $dataArr   =   $this->SalarData->find('all',array('conditions'=>array('Branch'=>$_REQUEST['BranchName'],'CostCenter'=>$CostCenter,'date(SalayDate)'=>$SalayDay)));
            ?>
                  
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>EmpName</th>
                        <th>Branch</th>
                        <th>CostCenter</th>
                        <th>ECS Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dataArr as $data){ ?>
                    <tr>
                        <td><?php echo $data['SalarData']['EmpCode'];?></td>
                        <td><?php echo $data['SalarData']['EmpName'];?></td>
                        <td><?php echo $data['SalarData']['Branch'];?></td>
                        <td><?php echo $data['SalarData']['CostCenter'];?></td>
                        <td><?php echo $data['SalarData']['ChequeNumber'];?></td>  
                    </tr>
                    <?php }?>
                </tbody>
            </table>
           <?php
           die;
        }
    }
    */
}
?>