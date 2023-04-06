<?php
class SalaryBreakupsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','BandNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','viewdetails','getcostcenter','editdetails','salaryedit','update_salary','matchbandctc');
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
            $branch_name    =   $this->request->data['SalaryBreakups']['branch_name'];
            $SearchType     =   $this->request->data['SearchType'];
            $SearchValue    =   trim($this->request->data['SearchValue']);
           
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($SearchType =="EmpName"){$conditoin['EmpName LIKE']=$SearchValue.'%';}else{unset($conditoin['EmpName LIKE']);}
            if($SearchType =="EmpCode"){$conditoin['EmpCode']=$SearchValue;}else{unset($conditoin['EmpCode']);}
            if($SearchType =="BioCode"){$conditoin['BioCode']=$SearchValue;}else{unset($conditoin['BioCode']);}
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin)); 
            $this->set('data',$data);
            
        }  
    }
    
    public function salaryedit(){
        $this->layout='ajax';
        if(isset($_REQUEST['id'])){
            $BandList=array();
            $BandListArr= $this->BandNameMaster->find('all',array('fields'=>array('Band','SlabFrom','SlabTo'),'conditions'=>array('Status'=>1)));
        
            foreach($BandListArr as $list){
                $BandList[$list['BandNameMaster']['Band']]=$list['BandNameMaster']['Band']." (".$list['BandNameMaster']['SlabFrom']."-".$list['BandNameMaster']['SlabTo'].")";
            }
         
            $this->set('BandList',$BandList);
            $data   =  $this->Masjclrentry->find('first',array('conditions'=>array('id'=>$_REQUEST['id'])));
            $this->set('data',$data['Masjclrentry']);
        }
    }
    
    
    public function update_salary(){
        $this->layout='ajax';
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $dataArr=$this->request->data['SalaryBreakups'];
                
                if(trim(addslashes($dataArr['EPF'])) =="" || trim(addslashes($dataArr['EPF'])) ==0){
                    $pfelig="NO";
                }
                else{
                    $pfelig="YES";
                }
                
                if(trim(addslashes($dataArr['ESIC'])) =="" || trim(addslashes($dataArr['ESIC'])) ==0){
                    $esielig="NO";
                }
                else{
                    $esielig="YES"; 
                }

                $UpdArr=array(
                'Band'=>"'".trim(addslashes($dataArr['Band']))."'",
                'package'=>"'".trim(addslashes($dataArr['PackageAmount']))."'",
                'bs'=>"'".trim(addslashes($dataArr['Basic']))."'",
                'conv'=>"'".trim(addslashes($dataArr['Conveyance']))."'",
                'portf'=>"'".trim(addslashes($dataArr['Portfolio']))."'",
                'ma'=>"'".trim(addslashes($dataArr['Medical']))."'",
                'sa'=>"'".trim(addslashes($dataArr['Special']))."'",
                'oa'=>"'".trim(addslashes($dataArr['OtherAllow']))."'",
                'hra'=>"'".trim(addslashes($dataArr['HRA']))."'",
                'Bonus'=>"'".trim(addslashes($dataArr['Bonus']))."'",
                'PLI'=>"'".trim(addslashes($dataArr['PLI']))."'",
                'Gross'=>"'".trim(addslashes($dataArr['Gross']))."'",
                'EPF'=>"'".trim(addslashes($dataArr['EPF']))."'",
                'ESIC'=>"'".trim(addslashes($dataArr['ESIC']))."'",
                'pfelig'=>"'".$pfelig."'",
                'esielig'=>"'".$esielig."'",
                'ProfessionalTax'=>"'".trim(addslashes($dataArr['Professional']))."'",
                'NetInhand'=>"'".trim(addslashes($dataArr['NetInHand']))."'",
                'EPFCO'=>"'".trim(addslashes($dataArr['EPFCO']))."'",
                'ESICCO'=>"'".trim(addslashes($dataArr['ESICCO']))."'",
                'AdminCharges'=>"'".trim(addslashes($dataArr['Admin']))."'",
                'CTC'=>"'".trim(addslashes($dataArr['CTC']))."'",
                'lastUpdated'=>"'".date('Y-m-d H:i:s')."'",
                );
                
                $this->Masjclrentry->query("insert into his_masjsclrentry select * from masjclrentry where id='{$dataArr['id']}'");
                if($this->Masjclrentry->updateAll($UpdArr,array('id'=>$dataArr['id']))){
                    echo "1";die;
                }
                else{
                    echo "";die;
                }
                
            }
        }
        
    }
    
    
    public function matchbandctc(){
        $Band   =   $_REQUEST['Band'];
        $CTC    =   $_REQUEST['CTC'];
        
        $BandListArr= $this->BandNameMaster->find('first',array('fields'=>array('Band','SlabFrom','SlabTo'),'conditions'=>array('Status'=>1,'Band'=>$Band)));
        $SlabFrom=intval($BandListArr['BandNameMaster']['SlabFrom']);
        $SlabTo=intval($BandListArr['BandNameMaster']['SlabTo']);
        
        if($CTC >=$SlabFrom && $CTC <= $SlabTo){
            echo "1";die;
        }
        else{
            echo "";die;
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