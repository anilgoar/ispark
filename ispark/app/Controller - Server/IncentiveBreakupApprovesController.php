<?php
class IncentiveBreakupApprovesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','UploadIncentiveBreakup','IncentiveNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','showbreakupdetails','getcostcenter','editdetails');
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
        
        if($this->request->is('Post')){ 
            $branch_name    =   $this->request->data['IncentiveBreakupApproves']['branch_name'];
            $CostCenter     =   $this->request->data['CostCenter'];
            $SalaryMonth    =   trim($this->request->data['SalaryMonth']);
            $ApproveStatus  =   "Approve";
            
            $y=date('Y',  strtotime($SalaryMonth));
            $m=date('m',  strtotime($SalaryMonth));
            
            $conditoin=array(
                'BranchName'=>$branch_name,
                'CostCenter'=>$CostCenter,
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                );
           
            if($this->UploadIncentiveBreakup->updateAll(array('ApproveStatus'=>"'".$ApproveStatus."'"),$conditoin)){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data approve successfully.</span>');
                $this->redirect(array('action'=>'index')); 
            }
            else{
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data not approve please try again later.</span>');
                $this->redirect(array('action'=>'index')); 
            }
        }  
    }

    public function showbreakupdetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){
            
            
           $y=date('Y',  strtotime($_REQUEST['SalaryMonth']));
           $m=date('m',  strtotime($_REQUEST['SalaryMonth']));
                    
            $conditoin=array(
                'BranchName'=>$_REQUEST['BranchName'],
                'CostCenter'=>$_REQUEST['CostCenter'],
                'MONTH(SalaryMonth)'=>$m,
                'YEAR(SalaryMonth)'=>$y,
                );
            
            $IncentiveNameArr   =   $this->IncentiveNameMaster->find('list',array('fields'=>'IncentiveName','conditions'=>array('BranchName'=>$_REQUEST['BranchName']),'order'=>'IncentiveName'));
            $data   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin,'order'=>'EmpCode','group'=>'EmpCode'));
            
            

            $storeIns=array();
            $dat1   =   $this->UploadIncentiveBreakup->find('all',array('conditions'=>$conditoin,'order'=>'EmpCode'));
            foreach($dat1 as $r1){
                if (!array_key_exists($r1['UploadIncentiveBreakup']['EmpCode'],$storeIns)){
                    $storeIns[$r1['UploadIncentiveBreakup']['EmpCode']][$r1['UploadIncentiveBreakup']['IncentiveType']]=$r1['UploadIncentiveBreakup']['Amount'];
                }
                else{
                   $storeIns[$r1['UploadIncentiveBreakup']['EmpCode']][$r1['UploadIncentiveBreakup']['IncentiveType']]=$r1['UploadIncentiveBreakup']['Amount']; 
                }
            }
            
            //echo "<pre>";
            //print_r($storeIns);
            //echo "</pre>";
            
            //$list   =   $this->IncentiveNameMaster->find('list',array('fields'=>array('IncentiveName'),'conditions'=>array('BranchName'=>$_REQUEST['BranchName'])));
            
            if(!empty($data)){
            $n=1;
            $TAM=0;
            $AS="";
            
            
            $inswise=array();
            ?>
            <div class="col-sm-12" style="overflow-x:scroll;"  <?php if($n > 10){ ?> style="overflow:scroll;height:550px; " <?php }?> >
                
                
                <table class = "table table-striped table-hover  responstable"   >     
                    <thead>
                        <tr>
                            <th >SNo</th>
                            <th  >EmpCode</th>
                            <th>EmpName</th>
                            <?php foreach($IncentiveNameArr as $row){?>
                            <th style="text-align:center;"><?php echo $row; ?></th>
                            <?php }?>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $tot1=0;   foreach ($data as $val){
                            
                            
                            
                            if($val['UploadIncentiveBreakup']['ApproveStatus'] !=""){
                                $AS=$val['UploadIncentiveBreakup']['ApproveStatus'];
                            }
                            
                            
                            
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['EmpCode'];?></td>
                            <td><?php echo $val['UploadIncentiveBreakup']['EmpName'];?></td>
                            <?php $tot=0; foreach($IncentiveNameArr as $row1){
                                
                               
                                
                                
                                
                                $tot=$tot+$storeIns[$val['UploadIncentiveBreakup']['EmpCode']][$row1];
                                
                                $inswise[$row1]+=$storeIns[$val['UploadIncentiveBreakup']['EmpCode']][$row1];
                                ?>
                                <td style="text-align: center;"><?php if($storeIns[$val['UploadIncentiveBreakup']['EmpCode']][$row1] !=""){ echo $storeIns[$val['UploadIncentiveBreakup']['EmpCode']][$row1];}else{echo 0;} ?></td>
                            <?php }?>
                                
                        <td style="text-align: center;"><?php  echo $tot;?></td>
                       
                        </tr>
                        <?php $tot1=$tot1+$tot; }?>
                       
                        <tr>
                            <td style="text-align: center;"><strong>TOTAL</strong></td>
                            <td></td>
                            <td></td>
                             <?php  foreach($IncentiveNameArr as $row3){?>
                                <td style="text-align: center;" ><?php echo $inswise[$row3];?></td>
                            <?php }?>
                            
                            <td style="text-align: center;"><strong><?php echo round($tot1,2);?></strong></td>
                        </tr>
                       
                    </tbody>   
                </table>
                
                
                
                <?php if($AS ==""){?>
                <input type="submit" value="Approve" class="btn pull-right btn-primary btn-new">
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
    
    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $data = $this->Masjclrentry->find('list',array('fields'=>array('CostCenter','CostCenter'),'conditions'=>array('BranchName'=>$_REQUEST['BranchName'],'Status'=>1),'group' =>array('CostCenter')));
            
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
    
    
}
?>