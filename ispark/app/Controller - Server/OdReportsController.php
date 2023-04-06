<?php
class OdReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','OldAttendanceIssue','OdApplyMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export_report','show_report');
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
    
    public function show_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            if($_REQUEST['StartDate'] !=""){
                $conditoin['MONTH(StartDate)']=date('m',strtotime($_REQUEST['StartDate']));
                $conditoin['YEAR(StartDate)']=date('Y',strtotime($_REQUEST['StartDate']));
            }else{unset($conditoin['StartDate']);}
            
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            if($_REQUEST['Status'] =="Applied"){$conditoin['ApproveFirst']=NULL;}
            else if($_REQUEST['Status'] =="Approve BM"){$conditoin['ApproveFirst']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve BM"){$conditoin['ApproveFirst']='No';}
            else if($_REQUEST['Status'] =="Approve HO"){$conditoin['ApproveSecond']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve HO"){$conditoin['ApproveSecond']='No';}
            else{unset($conditoin['ApproveFirst']);unset($conditoin['ApproveSecond']);}
            
            $data   =   $this->OdApplyMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,StartDate"));
            
            if(!empty($data)){
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:50px;" >SNo</th>
                            <th style="text-align: center;width:80px;">EmpCode</th>
                            <th style="text-align: center;">EmpName</th>
                            <th style="text-align: center;width:150px;">Branch</th>
                            <th style="text-align: center;width:80px;">FromDate</th>
                            <th style="text-align: center;width:80px;">ToDate</th>
                            <th style="text-align: center;">Reason</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $n=1; foreach ($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['EmpCode'];?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['OdApplyMaster']['StartDate']));?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['OdApplyMaster']['EndDate']));?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['Reason'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
            </div>
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    public function export_report(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
           
          
               
               
           
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=OD_Report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            
            if($_REQUEST['StartDate'] !=""){
                $conditoin['MONTH(StartDate)']=date('m',strtotime($_REQUEST['StartDate']));
                $conditoin['YEAR(StartDate)']=date('Y',strtotime($_REQUEST['StartDate']));
            }else{unset($conditoin['StartDate']);}
            
            if($_REQUEST['EmpCode'] !=""){$conditoin['EmpCode']=$_REQUEST['EmpCode'];}else{unset($conditoin['EmpCode']);}
            
            if($_REQUEST['Status'] =="Applied"){$conditoin['ApproveFirst']=NULL;}
            else if($_REQUEST['Status'] =="Approve BM"){$conditoin['ApproveFirst']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve BM"){$conditoin['ApproveFirst']='No';}
            else if($_REQUEST['Status'] =="Approve HO"){$conditoin['ApproveSecond']='Yes';}
            else if($_REQUEST['Status'] =="Not Approve HO"){$conditoin['ApproveSecond']='No';}
            else{unset($conditoin['ApproveFirst']);unset($conditoin['ApproveSecond']);}
            
            $data   =   $this->OdApplyMaster->find('all',array('conditions'=>$conditoin,'order'=>"EmpCode,StartDate"));
            
            if(!empty($data)){
            ?>
            
                <table border="1" >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:50px;" >SNo</th>
                            <th style="text-align: center;width:80px;">EmpCode</th>
                            <th style="text-align: center;">EmpName</th>
                            <th style="text-align: center;width:150px;">Branch</th>
                            <th style="text-align: center;width:80px;">FromDate</th>
                            <th style="text-align: center;width:80px;">ToDate</th>
                            <th style="text-align: center;">Reason</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php $n=1; foreach ($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['EmpCode'];?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['OdApplyMaster']['StartDate']));?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['OdApplyMaster']['EndDate']));?></td>
                            <td style="text-align: center;"><?php echo $val['OdApplyMaster']['Reason'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
                </table>
           
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    

    
    

      
}
?>