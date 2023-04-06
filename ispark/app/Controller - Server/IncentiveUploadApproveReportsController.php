<?php
class IncentiveUploadApproveReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','showbreakupdetails','getcostcenter','editdetails','exportbreakupdetails');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'))));
        }
        else if(count($branchName)>1){
            foreach($branchName as $b):
                $branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);            unset($branchName);
        }
        else{
           $this->set('branchName',array($branchName=>$branchName)); 
        }  
    }

    public function showbreakupdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){
            
            $y=date('Y',  strtotime($_REQUEST['SalaryMonth']));
            $m=date('m',  strtotime($_REQUEST['SalaryMonth']));
                 
            $conditoin=array(
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                );
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            $data   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin));
            
            $conditoin1=array(
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                'ApproveStatus'=>'Approve',
                );
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin1['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin1['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin1['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin1['CostCenter']);}
            
            $data1   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin1,'group'=>'CostCenter'));
            
            if(!empty($data)){
            $n=1;
            $TAM=0;
            $TAM1=0;
            ?>
            <div class="col-sm-12" >
                <div  style="overflow-y: scroll;height: 500px;" >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th colspan="7" style="text-align: left;">Uploaded</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">SNo</th>
                            <th style="text-align: center;">EmpCode</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">CostCenter</th>
                            <th style="text-align: center;">SalaryMonth</th>
                            <th style="text-align: center;">IncentiveType</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php foreach ($data as $val){
                            $TAM=$TAM+$val['UploadIncentiveBreakup']['Amount'];
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['EmpCode'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['UploadIncentiveBreakup']['SalaryMonth']));?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['IncentiveType'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['Amount'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                            <td style="text-align: center;"><strong><?php echo round($TAM,2);?></strong></td>
                        </tr>
                    </tbody>   
                </table>
                </div>
                <?php if(!empty($data1)){?>
                <table class = "table table-striped table-hover  responstable" style="width:500px;"  >     
                    <thead>
                        <tr>
                            <th colspan="5" style="text-align: left;">Approved</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">SNo</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">CostCenter</th>
                            <th style="text-align: center;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $k=1; foreach ($data1 as $val1){
                            $TotAmount=$this->getAmount($val1['UploadIncentiveBreakup']['BranchName'],$val1['UploadIncentiveBreakup']['CostCenter'],$y,$m);
                            $TAM1=$TAM1+$TotAmount;
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $k++;?></td>
                            <td style="text-align: center;"><?php echo $val1['UploadIncentiveBreakup']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val1['UploadIncentiveBreakup']['CostCenter'];?></td>
                            <td style="text-align: center;" ><?php echo $TotAmount;?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                            <td style="text-align: center;"><strong><?php echo round($TAM1,2);?></strong></td>
                        </tr>
                    </tbody>   
                </table>
            <?php }?>
                
            </div>
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    public function exportbreakupdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=UploadedApprovedReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $y=date('Y',  strtotime($_REQUEST['SalaryMonth']));
            $m=date('m',  strtotime($_REQUEST['SalaryMonth']));
                 
            $conditoin=array(
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                );
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            $data   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin));
            
            $conditoin1=array(
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                'ApproveStatus'=>'Approve',
                );
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin1['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin1['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin1['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin1['CostCenter']);}
            $data1   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin1,'group'=>'CostCenter'));
            
           
            $n=1;
            $TAM=0;
            $TAM1=0;
            ?>
            
                <table border="1"  >     
                    <thead>
                        <tr>
                            <th colspan="5" style="text-align: left;">Uploaded</th>
                        </tr>
                        <tr>
                            <th>SNo</th>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th >Branch</th>
                            <th >CostCenter</th>
                            <th style="text-align: center;">SalaryMonth</th>
                            <th>IncentiveType</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php foreach ($data as $val){
                            $TAM=$TAM+$val['UploadIncentiveBreakup']['Amount'];
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['EmpCode'];?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['EmpName'];?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['BranchName'];?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['UploadIncentiveBreakup']['SalaryMonth']));?></td>
                           
                            <td><?php echo $val['UploadIncentiveBreakup']['IncentiveType'];?></td>
                            <td style="text-align: center;"><?php echo $val['UploadIncentiveBreakup']['Amount'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td style="text-align: center;"><strong><?php echo round($TAM,2);?></strong></td>
                        </tr>
                    </tbody>   
                </table>
                
                <?php if(!empty($data1)){?>
                <br/>
                <table border="1" style="width:400px;"  >     
                    <thead>
                        <tr>
                            <th colspan="4" style="text-align: left;">Approved</th>
                        </tr>
                        <tr>
                            <th>SNo</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $k=1; foreach ($data1 as $val1){
                            $TotAmount=$this->getAmount($val1['UploadIncentiveBreakup']['BranchName'],$val1['UploadIncentiveBreakup']['CostCenter'],$y,$m);
                            $TAM1=$TAM1+$TotAmount;
                        ?>
                        <tr>
                            <td><?php echo $k++;?></td>
                            <td><?php echo $val1['UploadIncentiveBreakup']['BranchName'];?></td>
                            <td><?php echo $val1['UploadIncentiveBreakup']['CostCenter'];?></td>
                            <td><?php echo $TotAmount;?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td><strong><?php echo round($TAM1,2);?></strong></td>
                        </tr>
                    </tbody>   
                </table>
            <?php }?>
                
          
            <?php   
          
            die;
        }
        
    }
    
    
    public function getAmount($branch,$CostCenter,$y,$m){
        $data = $this->UploadIncentiveBreakup->query("SELECT SUM(amount) AS TotalAmount FROM `upload_incentive_breakup` WHERE BranchName='$branch' AND CostCenter='$CostCenter' AND YEAR(SalaryMonth)='$y' AND MONTH(SalaryMonth)='$m'");
        return $data[0][0]['TotalAmount']; 
    }

    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>$conditoin,'group' =>array('CostCenter')));
            
            if(!empty($data)){
                echo "<option value=''>Select</option>";
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
    
    
}
?>