<?php
class ProcessNocsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster',
        'HolidayMaster','UploadDeductionMaster','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','viewdetails');
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
    
    public function show_report(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $y  =   date('Y',strtotime($_REQUEST['StartDate']));
            $m  =   date('m',strtotime($_REQUEST['StartDate']));
            
            $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            
            $cnt   =   $this->Masjclrentry->find('count',array('conditions'=>$conditoin));
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin));
            
            if(!empty($data)){   
            ?>
            <div class="col-sm-12" <?php if($cnt >=10){?> style="overflow-y:scroll;height:500px; " <?php }?> >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;width:30px;">SNo</th>
                            <th style="text-align: center;width:80px;">EmpCode</th>
                            <th>EmpName</th>
                            <th style="text-align: center;width:100px;">Branch</th>
                            <th style="text-align: center;width:150px;">CostCenter</th>
                            <th style="text-align: center;width:60px;">DOB</th>
                            <th style="text-align: center;width:60px;">DOJ</th>
                            <th style="text-align: center;width:60px;">LeftDate</th>
                            <th>LeftRemark</th>
                            <th style="text-align: center;width:80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOB']));?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                            <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($val['Masjclrentry']['ResignationDate']));?></td>
           
                            <td><?php echo $val['Masjclrentry']['LeftReason'];?></td>
                            <td style="text-align: center;" ><a onclick="getLeftDetails('<?php echo $val['Masjclrentry']['id'];?>')" style="cursor: pointer;text-decoration: none;" >Process Noc</a></td> 
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
    
    public function viewdetails(){
        $this->layout='ajax'; 
        if(isset($_REQUEST['EJEID'])){
            $EJEID = $_REQUEST['EJEID'];
            $data=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$EJEID)));
            $this->set('data',$data);
            
            $offerNo    =   $data['Masjclrentry']['OfferNo'];
            $offerNo    =   "26246";
            $doc=$this->Masdocfile->find('all',array('conditions'=>array('OfferNo'=>$offerNo)));
            
            echo "<pre>";
            print_r($doc);die;
            
        } 
        
        /*
        if($this->request->is('Post')){
           
            $AuthArr=$this->User->find('first',array('conditions'=>array('id'=>$this->Session->read('userid'))));
            $AuthId=$AuthArr['User']['password'];
            
            $EJEID=$this->request->data['EJEID'];
            $ResignationDate=date('Y-m-d',strtotime($this->request->data['ResignationDate']));
            $AuthenticationCode=$this->request->data['AuthenticationCode'];
            $Reason=$this->request->data['Reason'];
            $Status=0;
            
            if($AuthenticationCode !=$AuthId){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your authentication code not match.</span>');
                $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($EJEID))));
            }
            else{
                if($this->Masjclrentry->updateAll(array('lastUpdated'=>"'".date('Y-m-d H:i:s')."'",'ResignationDate'=>"'".$ResignationDate."'",'AuthenticationCode'=>"'".$AuthenticationCode."'",'LeftReason'=>"'".$Reason."'",'Status'=>"'".$Status."'"),array('id'=>$EJEID))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data save successfully.</span>');
                    $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($EJEID))));
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your data does not save please try again later.</span>');
                    $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($EJEID))));
                }
            }
        
    }*/
    
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
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    public function report(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=OldAttandanceIssueApproval.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
            
        $branchName = $this->Session->read('branch_name');
        $data=$this->OldAttendanceIssue->find('all',array('conditions'=>array('BranchName'=>$branchName,'ApproveFirst'=>NULL))); 
        ?>
        <table border="1" >          
            <tr>
                <th>Emp Code</th>
                <th>Bio Code</th>
                <th>Emp Name</th>
                <th>Branch</th>
                <th>Attend Date</th>
                <th>Reason</th>
                <th>Current Status</th>
                <th>Expected Status</th>
                <th>Status</th>
            </tr>             
            <?php foreach ($data as $val){?>
            <tr>
                <td><?php echo $val['OldAttendanceIssue']['EmpCode'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['BioCode'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['EmpName'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['BranchName'];?></td>
                <td><?php echo date('d M y',strtotime($val['OldAttendanceIssue']['AttandDate'])) ;?></td>
                <td><?php echo $val['OldAttendanceIssue']['Reason'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['CurrentStatus'];?></td>
                <td><?php echo $val['OldAttendanceIssue']['ExpectedStatus'];?></td>
                <td>
                    <?php 
                    if($val['OldAttendanceIssue']['ApproveFirst'] =="Yes"){
                        echo "Approve";
                    }
                    else if($val['OldAttendanceIssue']['ApproveFirst'] =="No"){
                        echo "Not Approve";
                    }
                    else{
                        echo "Pending"; 
                    }
                    ?>
                </td>
            </tr>
            <?php }?>    
       </table>
        <?php
        die;
    }
    
}
?>