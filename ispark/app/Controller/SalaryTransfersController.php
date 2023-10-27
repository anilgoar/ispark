 <?php
class SalaryTransfersController extends AppController {
    public $helpers     = array('Html', 'Form','Js');
    public $components  = array('RequestHandler');  
    public $uses        = array('Addbranch','Masjclrentry','SalarData','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','view_salary','getcostcenter','salary_rejection','view_salary_rejection','update_salary_rejection');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchNameAll',$BranchArray);
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
    }
    
    
    public function view_salary(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){    
			$EmpType    =   $_REQUEST['EmpType'];
			$EmpStatus  =   $_REQUEST['EmpStatus'];
            $m          =   $_REQUEST['EmpMonth'];
            $y          =   $_REQUEST['EmpYear'];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
                     
            $where_bra  =   $_REQUEST['BranchName'] !="ALL"?" AND salary_data.Branch='{$_REQUEST['BranchName']}'":"";//AND SalaryDownload IS NULL
            $where_cos  =   $_REQUEST['CostCenter'] !="ALL"?" AND salary_data.CostCenter='{$_REQUEST['CostCenter']}'":"";
			
			$where_fnf  =   $_REQUEST['EmpStatus'] =="0"?" AND mjclr.FnfStatus='Validate'":"";
			
            //$query      =   "SELECT Id,EmpCode,EmpName,CostCenter,Designation,Branch,NetSalary FROM `salary_data` WHERE `Status`='$EmpStatus' $where_fnf AND NetSalary !='0' AND SalaryDownload IS NULL AND DATE(SalayDate)='$SalayDay' AND AcNo IS NOT NULL AND AcNo !='' AND AcNo !='0' AND (ChequeNumber IS NULL OR ChequeNumber='' OR ChequeNumber='0') $where_bra $where_cos";
            
            $query      =   "SELECT salary_data.Id,salary_data.EmpCode,salary_data.EmpName,salary_data.CostCenter,salary_data.Designation,salary_data.Branch,salary_data.NetSalary FROM `salary_data` 
INNER JOIN masjclrentry mjclr ON salary_data.EmpCode = mjclr.EmpCode
 WHERE mjclr.`Status`='$EmpStatus' $where_fnf AND salary_data.NetSalary !='0' AND salary_data.SalaryDownload IS NULL
  AND DATE(salary_data.SalayDate)='$SalayDay' AND salary_data.AcNo IS NOT NULL AND salary_data.AcNo !='' AND salary_data.AcNo !='0'
   AND (salary_data.ChequeNumber IS NULL OR salary_data.ChequeNumber='' OR salary_data.ChequeNumber='0') $where_bra $where_cos";
            
			
			$dataArr    =   $this->SalarData->query($query);
            ?>
            <div class="form-group" style="margin-top:-10px;margin-left: 100px;">
                <input type="hidden" name="Total_Amount" id="Total_Amount" value="0" >
                <input type="hidden" name="Total_count" id="Total_count" value="0" >
                <div class="col-sm-3 pull-right" id="Emp_Amount"> 
                    <span> <strong>No of Emp :</strong> 0 </span> | <span> <strong>Salary :</strong> 0 </span>
                </div>
            </div>
            <table class = "table table-striped table-hover  responstable"  >                 
                <thead>
                    <tr>
                        <th style="width:30px !important;">SrNo</th>
                        <th style="width:80px !important;">EmpCode</th>
                        <th style="width:200px !important;">EmpName</th>
                        <th style="width:200px !important;">CostCenter</th>
                        <th>Designation</th>
                        <th>Profile</th>
                        <th style="width:60px !important;">Emp Type</th>
                        <th style="width:60px !important;">NetSalary</th>
                        <th style="width:30px !important;w" >&#10004;</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($dataArr)){?>
                <?php  $i=1; foreach($dataArr as $row){
					
					$checktype	=   str_split($row['salary_data']['EmpCode'], 3);
					
					$laststring	=	substr($row['salary_data']['EmpCode'], -1); 



					if($checktype[0] =="IDC" && $laststring!="C"){
						$NewEmpType		=	"IDC";
					}
					else if($checktype[0] =="MAS" && $laststring!="C"){
						$NewEmpType		=	"MAS";
					}
					else{
						$NewEmpType		=	"MT";
					}
					
					if($NewEmpType ==$EmpType){
					
						$data           =   $row['salary_data'];
						$Id             =   $data['Id'];
						$EmpCode        =   $data['EmpCode'];
						$EmpName        =   $data['EmpName'];
						$Emp_Arr        =   $this->Masjclrentry->find('first',array('fields'=>array('Profile','EmpLocation'),'conditions'=>array('EmpCode'=>$EmpCode)));
						$Emp_Row        =   $Emp_Arr['Masjclrentry'];
						$Profile        =   $Emp_Row['Profile'];
						$EmpLocation    =   $Emp_Row['EmpLocation'];
						$branch         =   $data['Branch'];
						$costcenter     =   $data['CostCenter'];
						$designation    =   $data['Designation'];
						$NetSalary      =   $data['NetSalary'];
						
						$branch1        =   strlen($branch) > 6 ? substr($branch,0,6)."..." : $branch;
						$costcenter1    =   strlen($costcenter) > 6 ? substr($costcenter,0,6)."..." : $costcenter;
						$designation1   =   strlen($designation) > 10 ? substr($designation,0,10)."..." : $designation;
						$Profile1       =   strlen($Profile) > 10 ? substr($Profile,0,10)."..." : $designation;
						?>
						<tr>
							<td><?php echo $i++;?></td>
							<td><?php echo $EmpCode;?></td>
							<td><?php echo $EmpName;?></td>
							<td><?php echo $costcenter;?></td>
							<td><?php echo $designation;?></td>
							<td><?php echo $Profile;?></td>
							<td><?php echo $EmpLocation;?></td>
							<td><?php echo $NetSalary;?></td>
							<td><input type="checkbox" name="selectAll[]" class="checkbox" onclick="sumSalary('<?php echo $NetSalary;?>','<?php echo $Id;?>')" id="<?php echo $Id;?>" value="<?php echo $Id;?>" /></td>
						</tr>
					<?php }?>
				<?php }?>
                <?php }else{?>
                    <tr>
                        <td colspan="9" style="text-align: left;" >Record not found.</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <input type="submit"  value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
            <br/>
           <?php
           die;
        }
    }


    public function getcostcenter(){
        
        $branchName =   $_REQUEST['BranchName'];
        $y          =   $_REQUEST['EmpYear'];
        $m          =   $_REQUEST['EmpMonth'];
        
        $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $SalayDay   =   $y."-".$m."-".$mwd;
        
        
        if($branchName =="ALL"){
            echo "<option value='ALL'>ALL</option>";die;
        }
        else{
            $data = $this->SalarData->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('Branch'=>$branchName,'SalayDate'=>$SalayDay)));
            
            //$data = $this->ProcessAttendanceMaster->find('list', array('fields'=>array('CostCenter','CostCenter'),'conditions' => array('BranchName'=>$branchName,'ProcessMonth'=>"$y-$m",'FinializeStatus'=>'Yes')));
            
            if(!empty($data)){
                echo "<option value='ALL'>ALL</option>";
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "<option value=''>Select</option>";die;
            }
        }
    }
    
    public function salary_rejection(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchNameAll',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchNameAll',$BranchArray);
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchNameAll',array($branchName=>$branchName)); 
            $this->set('branchName',array($branchName=>$branchName)); 
        }
    }
    
    public function view_salary_rejection(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){        
            $m          =   $_REQUEST['EmpMonth'];
            $y          =   $_REQUEST['EmpYear'];
            $mwd        =   cal_days_in_month(CAL_GREGORIAN, $m, $y);
            $SalayDay   =   $y."-".$m."-".$mwd;
                     
            $where_bra  =   $_REQUEST['BranchName'] !="ALL"?" AND Branch='{$_REQUEST['BranchName']}'":"";
            $where_cos  =   $_REQUEST['CostCenter'] !="ALL"?" AND CostCenter='{$_REQUEST['CostCenter']}'":"";
            
            $query      =   "SELECT Id,EmpCode,EmpName,CostCenter,Designation,Branch,NetSalary FROM `salary_data` WHERE `Status`='1' AND SalaryDownload='YES' AND DATE(SalayDate)='$SalayDay' AND AcNo IS NOT NULL AND AcNo !='' AND AcNo !='0' AND (ChequeNumber IS NULL OR ChequeNumber='' OR ChequeNumber='0') $where_bra $where_cos";
            $dataArr    =   $this->SalarData->query($query);
            ?>
            <div class="form-group" style="margin-top:-10px;margin-left: 100px;">
                <input type="hidden" name="Total_Amount" id="Total_Amount" value="0" >
                <input type="hidden" name="Total_count" id="Total_count" value="0" >
                <div class="col-sm-3 pull-right" id="Emp_Amount"> 
                    <span> <strong>No of Emp :</strong> 0 </span> | <span> <strong>Salary :</strong> 0 </span>
                </div>
            </div>
            <table class = "table table-striped table-hover  responstable"  >                 
                <thead>
                    <tr>
                        <th style="width:30px !important;">SrNo</th>
                        <th style="width:80px !important;">EmpCode</th>
                        <th style="width:200px !important;">EmpName</th>
                        <th style="width:200px !important;">CostCenter</th>
                        <th>Designation</th>
                        <th>Profile</th>
                        <th style="width:60px !important;">Emp Type</th>
                        <th style="width:60px !important;">NetSalary</th>
                        <th style="width:120px !important;">Remarks</th>
                        <th style="width:30px !important;w" >&#10004;</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($dataArr)){?>
                <?php  $i=1; foreach($dataArr as $row){
                    $data           =   $row['salary_data'];
                    $Id             =   $data['Id'];
                    $EmpCode        =   $data['EmpCode'];
                    $EmpName        =   $data['EmpName'];
                    $Emp_Arr        =   $this->Masjclrentry->find('first',array('fields'=>array('Profile','EmpLocation'),'conditions'=>array('EmpCode'=>$EmpCode)));
                    $Emp_Row        =   $Emp_Arr['Masjclrentry'];
                    $Profile        =   $Emp_Row['Profile'];
                    $EmpLocation    =   $Emp_Row['EmpLocation'];
                    $branch         =   $data['Branch'];
                    $costcenter     =   $data['CostCenter'];
                    $designation    =   $data['Designation'];
                    $NetSalary      =   $data['NetSalary'];
                    
                    $branch1        =   strlen($branch) > 6 ? substr($branch,0,6)."..." : $branch;
                    $costcenter1    =   strlen($costcenter) > 6 ? substr($costcenter,0,6)."..." : $costcenter;
                    $designation1   =   strlen($designation) > 10 ? substr($designation,0,10)."..." : $designation;
                    $Profile1       =   strlen($Profile) > 10 ? substr($Profile,0,10)."..." : $designation;
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $EmpCode;?></td>
                        <td><?php echo $EmpName;?></td>
                        <td><?php echo $costcenter;?></td>
                        <td><?php echo $designation;?></td>
                        <td><?php echo $Profile;?></td>
                        <td><?php echo $EmpLocation;?></td>
                        <td><?php echo $NetSalary;?></td>
                        <td>
                            
                            <select id="RejectionRemarks<?php echo $Id;?>" name="RejectionRemarks<?php echo $Id;?>" style="width:100px;" >
                                <option value="">Select</option>
                                <option value="Incorrect IFSC Code">Incorrect IFSC Code</option>
                                <option value="Incorrect Bank Account Number">Incorrect Bank Account Number</option>
                                <option value="System GeneratedTransfer Date Crossed">System GeneratedTransfer Date Crossed</option>
                            </select>
                            
                        </td>
                        <td><input type="checkbox" name="selectAll[]" class="checkbox" onclick="sumSalary('<?php echo $NetSalary;?>','<?php echo $Id;?>')" id="<?php echo $Id;?>" value="<?php echo $Id;?>" /></td>
                    </tr>
                    <?php }?>
                    <?php }else{?>
                    <tr>
                        <td colspan="9" style="text-align: left;" >Record not found.</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <input type="submit" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;" >
             <br/>
           <?php
           die;
        }
    }
    
    public function update_salary_rejection(){           
        $data_array     =   $_REQUEST['selectAll'];
 
        foreach($data_array as $Id){
            $RejectionRemarks     =   $_REQUEST['RejectionRemarks'.$Id];
            $this->SalarData->query("UPDATE salary_data SET SalaryDownload=NULL,RejectionRemarks='$RejectionRemarks' WHERE Id='$Id'");
        }

        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your rejection process complete scccessfully.</span>'); 
        return $this->redirect(array('controller'=>'SalaryTransfers','action' => 'salary_rejection'));
        //$this->redirect(array('action'=>'markfield','?'=>array('CSN'=>base64_encode($CostCenter))));  
    }
    
}
?>