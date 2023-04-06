<?php
class ActivateEmployeesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','viewdetails','getcostcenter','editdetails');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('Post')){ 
            $branch_name    =   $this->request->data['ActivateEmployees']['branch_name'];
            $SearchType     =   $this->request->data['SearchType'];
            $SearchValue    =   trim($this->request->data['SearchValue']);
            $conditoin=array('Status'=>0);
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($SearchType =="EmpName"){$conditoin['EmpName LIKE']=$SearchValue.'%';}else{unset($conditoin['EmpName LIKE']);}
            if($SearchType =="EmpCode"){$conditoin['EmpCode']=$SearchValue;}else{unset($conditoin['EmpCode']);}
            if($SearchType =="BioCode"){$conditoin['BioCode']=$SearchValue;}else{unset($conditoin['BioCode']);}
                        
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin)); 
            $this->set('data',$data);
        }  
    }
    
    public function viewdetails(){
        $this->layout='home';
        
        if(isset($_REQUEST['EJEID'])){
            $EJEID = base64_decode($_REQUEST['EJEID']);
            $data=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$EJEID)));
            $this->set('data',$data);
        }
        
        if($this->request->is('Post')){
            $EJEID=$this->request->data['EJEID'];
            
            $data=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$EJEID)));
  
            $ResignationDate=NULL;
            $AuthenticationCode=NULL;
            $Reason1=$this->request->data['Reason'];
            
          
            
            
            $Reason=$data['Masjclrentry']['LeftReason'].';'.$this->Session->read('email').";".$Reason1.";".date('d-m-Y H:i:s');
            
            $Status=1;
            
            if($this->Masjclrentry->updateAll(array('lastUpdated'=>"'".date('Y-m-d H:i:s')."'",'ResignationDate'=>"'".$ResignationDate."'",'AuthenticationCode'=>"'".$AuthenticationCode."'",'LeftReason'=>"'".$Reason."'",'Status'=>"'".$Status."'"),array('id'=>$EJEID))){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your data save successfully.</span>');
                $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($EJEID))));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Your data does not save please try again later.</span>');
                $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($EJEID))));
            }
            
        }        
    }
    
    public function editdetails(){
        $this->layout='home';
        
        if(isset($_REQUEST['EJEID'])){
            $EJEID = base64_decode($_REQUEST['EJEID']);
            $data=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$EJEID)));
            $data1=$this->MasJclrMaster->find('first',array('conditions'=>array('EmpCode'=>$data['Masjclrentry']['EmpCode'])));
            $this->redirect(array('controller'=>'Masjclrs','action'=>'newjclr','?'=>array('id'=>$data1['MasJclrMaster']['Id'])));
        }        
    }

    public function show_employee(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName'])){
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['SearchType'] =="EmpName"){$conditoin['EmpName']=$_REQUEST['SearchValue'];}else{unset($conditoin['EmpName']);}
            if($_REQUEST['SearchType'] =="EmpCode"){$conditoin['EmpCode']=$_REQUEST['SearchValue'];}else{unset($conditoin['EmpCode']);}
            if($_REQUEST['SearchType'] =="BioCode"){$conditoin['BioCode']=$_REQUEST['SearchValue'];}else{unset($conditoin['BioCode']);}
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin)); 
            
            if(!empty($data)){  
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height: 200px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>EmpSrNo</th>
                            <th>EmpCode</th>
                            <th>BioCode</th>
                            <th>EmpName</th>
                            <th>FatherName</th>
                            <th>DOJ</th>
                            <th>DOB</th>
                            <th>Department</th>
                            <th>CostCenter</th>
                            <th>CTC</th>
                            <!--
                            <th>NetInHand</th>
                            <th>ReportingLevel</th>
                            -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>         
                        <?php
                        $n=1; foreach ($data as $val){
                        ?>
                        <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $val['Masjclrentry']['id'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['BioCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td><?php echo $val['Masjclrentry']['Father'];?></td>
                            <td><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                            <td><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOB']));?></td>
                            <td><?php echo $val['Masjclrentry']['Dept'];?></td>
                            <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td><?php echo $val['Masjclrentry']['CTC'];?></td>
                            <!--
                            <td></td>
                            <td></td>
                            -->
                            <?php 
                            if($val['Masjclrentry']['Status'] =="1"){echo "<td style='color:green;'>Active</td>";}else{echo "<td style='color:red;'>Left</td>";}
                            ?>
                            <td></td>
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
    
  
    
    
    
    public function total_sundays($month,$year){
        $sundays=0;
        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for($i=1;$i<=$total_days;$i++)
        if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
        $sundays++;
        return $sundays;
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