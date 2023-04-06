<?php
class EditMasjclrsController extends AppController{
    public $uses = array('Jclr','User','Design','maspackage','masband','MasJclrMaster',
        'CostCenterMaster','Masattandance','Mastmpjclr','Masdocfile','MasRelation','Masjclrentry',
        'StateMaster','CityMaster','DepartmentNameMaster','DesignationNameMaster','BandNameMaster',
        'maspackage','NewjclrMaster','MasJclrentrydata','EmployeeSourceMasters','Masdocfile');

    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','view','get_band','update_cost','showpack','jclrapprove','get_design','get_package',
                'showctc','newemp','get_data','editjclr','newjclr','get_biocode','get_name','save_doc','get_status_data',
                'deletefile','saverelation','deleteemp','check_date','checkdoc','getcity','getdept','getdesg','getband','getctc',
                'getinhand','getpackage','jclrentry','deletejclr','editcity','editdept','get_biocode1','getsourcename','checkdoc1',
                'generateempcode');
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout = "home";
        $branchName = $this->Session->read('branch_name');
        $user = $this->Session->read('userid');
        
        $this->set('state',$this->StateMaster->find('list',array('fields'=>array('id','name'),'order'=>'name')));
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
        $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName,'active'=>1))));
                
	if ($this->request->is('post')){
            $data=$this->request->data;
            
            
           
            
           
            if($data['Sw']=="Father"){$Father=$data['Father'];}else{$Father=NULL;}
            if($data['Sw']=="Husband"){$Husband=$data['Husband'];}else{$Husband=NULL;}
            
            $DataArr=array(
                'Title'=>trim(addslashes($data['Title'])),
                'EmpType'=>trim(addslashes($data['EmpType'])),
                'EmpName'=>trim(addslashes($data['EmpName'])),
                'ParentType'=>trim(addslashes($data['Sw'])),
                'Father'=>trim(addslashes($Father)),
                'Husband'=>trim(addslashes($Husband)),
                'DOB'=>date('Y-m-d',strtotime($data['DOB'])),
                'DOJ'=>date('Y-m-d',strtotime($data['DOJ'])),
                'Gendar'=>trim(addslashes($data['Gendar'])),
                'BloodGruop'=>addslashes($data['BloodGruop']),
                'Adrress1'=>trim(addslashes($data['Adrress1'])),
                'Adrress2'=>trim(addslashes($data['Adrress2'])),
                'State'=>$this->statename($data['State']),
                'StateId'=>$data['State'],
                'State1'=>$this->statename($data['State1']),
                'State1Id'=>$data['State1'],
                'City'=>trim(addslashes($data['City'])),
                'City1'=>trim(addslashes($data['City1'])),
                'PinCode'=>trim(addslashes($data['PinCode'])),
                'PinCode1'=>trim(addslashes($data['PinCode1'])),
                'Dept'=>trim(addslashes($data['Dept'])),
                'Desgination'=>trim(addslashes($data['Desgination'])),
                'Band'=>trim(addslashes($data['Band'])),
                'package'=>trim(addslashes($data['package'])),
                'CTC'=>trim(addslashes($data['CTC'])),
                'NetInhand'=>trim(addslashes($data['NetInHand'])),
                'bs'=>trim(addslashes($data['bs'])),
                'conv'=>trim(addslashes($data['conv'])),
                'portf'=>trim(addslashes($data['portf'])),
                'ma'=>trim(addslashes($data['ma'])),
                'sa'=>trim(addslashes($data['sa'])),
                'oa'=>trim(addslashes($data['oa'])),
                'hra'=>trim(addslashes($data['hra'])),
                'Bonus'=>trim(addslashes($data['Bonus'])),
                'PLI'=>trim(addslashes($data['PLI'])),
                'Gross'=>trim(addslashes($data['Gross'])),
                'EPF'=>trim(addslashes($data['EPF'])),
                'ESIC'=>trim(addslashes($data['ESIC'])),
                'ProfessionalTax'=>trim(addslashes($data['ProfessionalTax'])),
                'EPFCO'=>trim(addslashes($data['EPFCO'])),
                'ESICCO'=>trim(addslashes($data['ESICCO'])),
                'AdminCharges'=>trim(addslashes($data['AdminCharges'])),
                'CostCenter'=>trim(addslashes($data['MasJclrMaster']['CostCenter'])),
                'BranchName'=>$branchName,
                'userid'=>$user, 
            );
            
            if ($this->NewjclrMaster->saveall($DataArr)){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;margin-left: 170px;" >Employee details save successfully.</span>'); 
                return $this->redirect(array('controller' => 'Masjclrs'));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Employee details does not save please try again later.</span>'); 
                return $this->redirect(array('controller' => 'Masjclrs'));
            }
        }
    }
    
    public function statename($id){
        $state=$this->StateMaster->find('first',array('fields'=>array('name'),'conditions'=>array('id'=>$id)));
        return $state['StateMaster']['name'];
    }
    
    public function check_date(){
        $FromDate   =   date("Y-m-d",strtotime($_REQUEST['FromDate']));
        $ToDate     =   date("Y-m-d",strtotime($_REQUEST['ToDate']));
        $CuDate     =   date("Y-m-d");
        
        $date1  =   strtotime($FromDate);
        $date2  =   strtotime($ToDate);
        $months =   0;
              
        while (strtotime('+1 MONTH', $date1) < $date2) {
            $months++;
            $date1 = strtotime('+1 MONTH', $date1);
        }
        
        $Year=$months/12;
        
        if($Year >=18){
            echo '1';die;
        }
        else{
            echo '';die;
        }
    }
    
    public function checkdoc(){
        $OfferNo        =   $_REQUEST['OfferNo'];
        $EmpType        =   $_REQUEST['EmpType'];
        $Desgination    =   $_REQUEST['Desgination'];
        $type           =   $_REQUEST['type'];
        
        //$check=$this->Masdocfile->query("select count(Id) as cnt from mas_docoments where `OfferNo`='$OfferNo' and DocType='$type'"); 
        $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo)));
        if($EmpType =="ONROLL"){
            if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
                $NC=9; 
            }
            else if($Desgination=="Office Assistant"){
                $NC=7; 
            }
            else{
              $NC=16;   
            }  
        }
        else if($EmpType =="MGMT. TRAINEE"){
            if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
                $NC=6; 
            }
            else if($Desgination=="Office Assistant"){
                $NC=4; 
            }
            else{
              $NC=13;   
            }
        }
        
        if($TotCnt >= $NC){
            echo '1';die;
        }
        else{
            echo '';die;
        } 
    }
    
    public function checkdoc1(){
        $OfferNo        =   $_REQUEST['OfferNo'];
        $EmpType        =   $_REQUEST['EmpType'];
        $Desgination    =   $_REQUEST['Desgination'];
        $type           =   $_REQUEST['type'];
        $pageno           =   $_REQUEST['pageno'];
 
        if($type =="Others"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Pancard"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Address Proof"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="ID Proof"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Proof of Education"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Photo"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Joining Form"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Contrat Form"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type,'fileno'=>$pageno)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Resume"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type,'fileno'=>$pageno)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Epf Declaration Form"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type,'fileno'=>$pageno)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Code Of Conduct"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type,'fileno'=>$pageno)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        else if($type =="Aadhar"){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$type,'fileno'=>$pageno)));
            if($TotCnt > 0){
                echo '1';die;
            }
            else{
               echo '';die; 
            }
        }
        
    }
    
    
    
    
    public function getcity(){
        if(isset($_REQUEST['state'])){ 
            $state=$this->CityMaster->find('list',array('fields'=>array('city_name'),'conditions'=>array('state_id'=>$_REQUEST['state']),'order'=>'city_name'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                echo "<option value='$val'>$val</option>";
            }      
        }
        die;
    }
    
    public function editcity(){
        if(isset($_REQUEST['state'])){ 
            $city=$_REQUEST['city'];
            $state=$this->CityMaster->find('list',array('fields'=>array('city_name'),'conditions'=>array('state_id'=>$_REQUEST['state']),'order'=>'city_name'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                if($city ==$val){$selected="selected='selected'";}else{$selected="";}
                echo "<option $selected value='$val'>$val</option>";
            }      
        }
        die;
    }

    public function getdept(){
        if(isset($_REQUEST['Department'])){ 
            $state=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation'),'conditions'=>array('Department'=>$_REQUEST['Department'],'Status'=>1),'order'=>'Designation','group'=>'Designation'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                echo "<option value='$val'>$val</option>";
            }      
        }
        die;
    }
    
    public function editdept(){
        if(isset($_REQUEST['Department'])){
            $Designation=$_REQUEST['Designation'];
            $state=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation'),'conditions'=>array('Department'=>$_REQUEST['Department'],'Status'=>1),'order'=>'Designation','group'=>'Designation'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                if($Designation ==$val){$selected="selected='selected'";}else{$selected="";}
                echo "<option $selected value='$val'>$val</option>";
            }      
        }
        die;
    }
     
    public function getdesg(){
        if(isset($_REQUEST['Designation'])){ 
            $Band=$this->DesignationNameMaster->find('all',array('fields'=>array('Band'),'conditions'=>array('Designation'=>$_REQUEST['Designation'],'Status'=>1)));
            echo "<option value=''>Select</option>";
            foreach($Band as $val){
                $BandName=$val['DesignationNameMaster']['Band'];
                $SlabArr=$this->BandNameMaster->find('first',array('fields'=>array('SlabFrom','SlabTo'),'conditions'=>array('Band'=>$BandName,'Status'=>1),'order'=>'Band'));
                
                $SlabFrom=$SlabArr['BandNameMaster']['SlabFrom'];
                $SlabTo=$SlabArr['BandNameMaster']['SlabTo'];
                
                echo "<option value='$BandName'>$BandName ($SlabFrom - $SlabTo)</option>";
            }
            die; 
        }
        die;
    }
    
    public function getband(){
        if(isset($_REQUEST['Band'])){ 
            $state=$this->maspackage->find('list',array('fields'=>array('PackageAmount'),'conditions'=>array('Band'=>$_REQUEST['Band']),'order'=>'Band'));
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                echo "<option value='$val'>$val</option>";
            }      
        }
        die;
    }
    
    public function getctc(){
        if(isset($_REQUEST['Package'])){ 
            $state=$this->maspackage->find('first',array('fields'=>array('CTC'),'conditions'=>array('PackageAmount'=>$_REQUEST['Package'])));
            echo $state['maspackage']['CTC']; 
        }
        die;
    }
    
    public function getinhand(){
        if(isset($_REQUEST['Package'])){ 
            $state=$this->maspackage->find('first',array('fields'=>array('NetInHand'),'conditions'=>array('PackageAmount'=>$_REQUEST['Package'])));
            echo $state['maspackage']['NetInHand']; 
        }
        die;
    }
    
    public function getpackage(){
        if(isset($_REQUEST['Package'])){ 
            $state=$this->maspackage->find('first',array('conditions'=>array('PackageAmount'=>$_REQUEST['Package'])));
            
            echo "<input type='hidden' name='bs' id='bs' value='{$state['maspackage']['Basic']}'>";
            echo "<input type='hidden' name='conv' id='conv' value='{$state['maspackage']['Conveyance']}'>";
            echo "<input type='hidden' name='portf' id='portf' value='{$state['maspackage']['Portfolio']}'>";
            echo "<input type='hidden' name='ma' id='ma' value='{$state['maspackage']['Medical']}'>";
            echo "<input type='hidden' name='sa' id='sa' value='{$state['maspackage']['Special']}'>";
            echo "<input type='hidden' name='oa' id='oa' value='{$state['maspackage']['OtherAllow']}'>";
            echo "<input type='hidden' name='hra' id='hra' value='{$state['maspackage']['HRA']}'>";
            echo "<input type='hidden' name='Bonus' id='Bonus' value='{$state['maspackage']['Bonus']}'>";
            echo "<input type='hidden' name='PLI' id='PLI' value='{$state['maspackage']['PLI']}'>";
            echo "<input type='hidden' name='Gross' id='Gross' value='{$state['maspackage']['Gross']}'>";
            echo "<input type='hidden' name='EPF' id='EPF' value='{$state['maspackage']['EPF']}'>";
            echo "<input type='hidden' name='ESIC' id='ESIC' value='{$state['maspackage']['ESIC']}'>";
            echo "<input type='hidden' name='ProfessionalTax' id='ProfessionalTax' value='{$state['maspackage']['Professional']}'>";
            echo "<input type='hidden' name='EPFCO' id='EPFCO' value='{$state['maspackage']['EPFCO']}'>";
            echo "<input type='hidden' name='ESICCO' id='ESICCO' value='{$state['maspackage']['ESICCO']}'>";
            echo "<input type='hidden' name='AdminCharges' id='AdminCharges' value='{$state['maspackage']['Admin']}'>";
            
            echo "<table class = 'table table-striped table-hover  responstable' style='margin-top:-5px;'  >";
            echo "<thead>";
            echo "<tr><th colspan='2'>Package Details</th></tr>";
            echo "</thead>";
            echo "<tbody>";
           
            echo "<tr><td>Package Amount</td><td style='text-align:center;' >{$state['maspackage']['PackageAmount']}</td></tr>";
            echo "<tr><td>Basic</td><td style='text-align:center;'>{$state['maspackage']['Basic']}</td></tr>";
            echo "<tr><td>HRA</td><td style='text-align:center;'>{$state['maspackage']['HRA']}</td></tr>";
            echo "<tr><td>Conv.</td><td style='text-align:center;'>{$state['maspackage']['Conveyance']}</td></tr>";
            if($state['maspackage']['Portfolio'] !=""){
                echo "<tr><td>Portfolio</td><td style='text-align:center;'>{$state['maspackage']['Portfolio']}</td></tr>";
            }
            if($state['maspackage']['Medical'] !=""){
            echo "<tr><td>Medical Allowance</td><td style='text-align:center;'>{$state['maspackage']['Medical']}</td></tr>";
            }
            if($state['maspackage']['Special'] !=""){
            echo "<tr><td>Special Allowance</td><td style='text-align:center;'>{$state['maspackage']['Special']}</td></tr>";
            }
            echo "<tr><td>Bonus</td><td style='text-align:center;'>{$state['maspackage']['Bonus']}</td></tr>";
            if($state['maspackage']['OtherAllow'] !=""){
            echo "<tr><td>Other Allowance</td><td style='text-align:center;'>{$state['maspackage']['OtherAllow']}</td></tr>";
            }
            echo "<tr><td>Gross</td><td style='text-align:center;'>{$state['maspackage']['Gross']}</td></tr>";
            echo "<tr><td>ESIC</td><td style='text-align:center;'>{$state['maspackage']['ESIC']}</td></tr>";
            echo "<tr><td>EPF</td><td style='text-align:center;'>{$state['maspackage']['EPF']}</td></tr>";
            if($state['maspackage']['Professional'] !=""){
            echo "<tr><td>Professional Tax</td><td style='text-align:center;'>{$state['maspackage']['Professional']}</td></tr>";
             }
            echo "<tr><td>In Hand</td><td style='text-align:center;'>{$state['maspackage']['NetInHand']}</td></tr>";
            echo "<tr><td>Employer EPF</td><td style='text-align:center;'>{$state['maspackage']['EPFCO']}</td></tr>";
            echo "<tr><td>Employer ESIC</td><td style='text-align:center;'>{$state['maspackage']['ESICCO']}</td></tr>";
            echo "<tr><td>Admin Charges</td><td style='text-align:center;'>{$state['maspackage']['Admin']}</td></tr>";
            if($state['maspackage']['PLI'] !=""){
            echo "<tr><td>PLI</td><td style='text-align:center;'>{$state['maspackage']['PLI']}</td></tr>";
            }
            echo "<tr><td>CTC</td><td style='text-align:center;'>{$state['maspackage']['CTC']}</td></tr>";
            echo "</tbody>";
            echo "</table>";
        }
        die;
    }
    
    public function jclrapprove(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Approve'=>NULL)))); 
        if($this->request->is('Post')){
            if(isset($this->request->data['Submit']) && $this->request->data['Submit'] !="Search"){
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
                        $UpdOffer="";
                        $OFNO1Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM masjclrentry");
                        $OFNO2Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM NewJclrMaster");

                        $OFNO1=$OFNO1Arr[0][0]['OFNO'];
                        $OFNO2=$OFNO2Arr[0][0]['OFNO'];

                        if($OFNO2 ==""){
                            $OfferNo=$OFNO1+1;
                        }
                        else{
                            if($OFNO2 > $OFNO1){
                                $OfferNo=$OFNO2+1;
                            }
                            else{
                                $OfferNo=$OFNO1+1;
                            }    
                        }
                        
                        foreach ($OdIdArr as $Id){
                            
                            if($SubmitType =="Approve"){
                                if($UpdOffer ==""){
                                    $this->NewjclrMaster->updateAll(array('OfferNo'=>"'".$OfferNo."'",'Approve'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$Id)); 
                                }
                                else{
                                    $this->NewjclrMaster->updateAll(array('OfferNo'=>"'".$UpdOffer."'",'Approve'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$Id));  
                                }
                                $UpdOffer=$OfferNo+1;
                            }
                            
                            if($SubmitType =="Not Approve"){
                                $this->NewjclrMaster->query("DELETE FROM NewJclrMaster WHERE id='$Id' AND BranchName='$branchName'");
                            }   
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;" >Please check option to approve/not approve record.</span>'); 
                        $this->redirect(array('controller'=>'Masjclrs','action'=>'jclrapprove'));
                    }
                     
                }
                $this->Session->setFlash('<span style="color:green;" >JCLR Approve successfully.</span>'); 
                $this->redirect(array('controller'=>'Masjclrs','action'=>'jclrapprove'));  
            }
            else{
                if($this->request->data['SearchName'] !=""){
                    $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'EmpName LIKE'=>$this->request->data['SearchName'].'%','Approve'=>NULL)))); 
                }
                else{
                    $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Approve'=>NULL)))); 
                }
            } 
            //$this->redirect(array('controller'=>'Masjclrs','action'=>'jclrapprove'));
        }
        
        
        
    }
    
    
    
    
    public function getofferno1(){
        $OFNO1Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM masjclrentry");
        return $OFNO1=$OFNO1Arr[0][0]['OFNO'];
        
    }
    
    public function getofferno2(){
        $OFNO2Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM NewJclrMaster");
        return $OFNO2=$OFNO2Arr[0][0]['OFNO'];
    }
    
    
    public function generateempcode(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Approve'=>'Yes','Approve1'=>'Yes')))); 
       
        if($this->request->is('Post')){
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
                        
                        //$i=0;
                        foreach ($OdIdArr as $Id){
                            /*
                            $data1 = $this->NewjclrMaster->query('select MAX(EmpCode) as EmpCode from masjclrentry where EmpCode is not null');
                            $i++;
                            if(empty($data1[0][0]['EmpCode'])){
                                $mas='MAS'.$i;
                            }
                            else{
                                $old= str_replace("MAS","",$data1[0][0]['EmpCode']);
                                $new=$old+$i;
                                $mas= str_replace($old,$new,$data1[0][0]['EmpCode']);
                            }
                            $this->Masjclrentry->updateAll(array('EmpCode'=>"'".$mas."'",'Approve'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branchName));
                            */
                            
                            if($SubmitType =="Approve"){
                                $OFNO1Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM masjclrentry");
                                $OFNO2Arr=$this->NewjclrMaster->query("SELECT MAX(CONVERT(OfferNo,UNSIGNED INTEGER)) AS OFNO FROM NewJclrMaster");
                                
                                $OFNO1=$OFNO1Arr[0][0]['OFNO'];
                                $OFNO2=$OFNO2Arr[0][0]['OFNO'];
                                
                                if($OFNO2 ==""){
                                    $OfferNo=$OFNO1+1;
                                }
                                else{
                                    if($OFNO2 > $OFNO1){
                                        $OfferNo=$OFNO2+1;
                                    }
                                    else{
                                        $OfferNo=$OFNO1+1;
                                    }    
                                }
                                
                                $this->NewjclrMaster->updateAll(array('OfferNo'=>"'".$OfferNo."'",'Approve'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$Id,'BranchName'=>$branchName));
                            }
                            
                            if($SubmitType =="Not Approve"){
                                $this->NewjclrMaster->query("DELETE FROM NewJclrMaster WHERE id='$Id' AND BranchName='$branchName'");
                            }   
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;" >Please check option to approve/not approve record.</span>'); 
                        $this->redirect(array('controller'=>'Masjclrs','action'=>'jclrapprove'));
                    }
                     
                }
                $this->Session->setFlash('<span style="color:green;" >JCLR Approve successfully.</span>'); 
                $this->redirect(array('controller'=>'Masjclrs','action'=>'jclrapprove'));  
            }
            
             
        }     
    }
    
    
    public function jclrentry(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $this->set('OdArr',$this->NewjclrMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'Approve'=>'Yes','Approve1'=>NULL))));      
    }
    
    public function deletejclr(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $branchName = $this->Session->read('branch_name');
            $this->NewjclrMaster->query("DELETE FROM NewJclrMaster WHERE id='{$_REQUEST['Id']}' AND BranchName='$branchName'");   
        }
        $this->redirect(array('controller'=>'Masjclrs','action'=>'jclrentry'));
    }
    
    
    public function getsourcename(){
        if(isset($_REQUEST['SourceType'])){ 
            $Source=$_REQUEST['SourceName'];
            $state=$this->EmployeeSourceMasters->find('list',array('fields'=>array('SourceName'),'conditions'=>array('BranchName'=>$_REQUEST['BranchName'],'SourceType'=>$_REQUEST['SourceType']),'order'=>'SourceName','group'=>'SourceName'));
            
        if($_REQUEST['SourceType'] =="EMPLOYEE REFERRAL"){
            echo '<input type="text" class="form-control" placeholder="Emp Code" autocomplete="off" name="Source" id="Source" value="'.$Source.'" >';
        }
        else{
            echo '<select class="form-control" autocomplete="off" name="Source" id="Source">';
            echo "<option value=''>Select</option>";
            foreach($state as $val){
                if($Source ==$val){$selected="selected='selected'";}else{$selected="";}
                echo "<option $selected value='$val'>$val</option>";  
            }
            echo "</select>";
        }  
            
        }
        die;
    }
    
    public function newjclr(){
        $this->layout='home';
        $user = $this->Session->read('userid');
        $id = $this->request->query['id'];
        
        $Jclr=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$id)));
        $branchName=$Jclr['Masjclrentry']['BranchName'];
        
        $this->set('state',$this->StateMaster->find('list',array('fields'=>array('id','name'),'order'=>'name')));
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
        $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName))));
        
        $this->set('Jclr',$Jclr);
        $this->set('source',$this->EmployeeSourceMasters->find('list',array('fields'=>array('SourceType'),'order'=>'SourceType','group'=>'SourceType')));
      
        $EmpID = $Jclr['Masjclrentry']['OfferNo'];
        $this->set('empid',$EmpID);
        $this->set('ID', $EmpID);
        
       
        $OfferNo        =   $Jclr['Masjclrentry']['OfferNo'];
        $EmpType        =   $Jclr['Masjclrentry']['EmpType'];
        $Desgination    =   $Jclr['Masjclrentry']['Desgination'];
       
        
        if($EmpType =="ONROLL"){
            if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
                $NC=10;
                $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Proof of Education'=>1,
                    'Code Of Conduct'=>2,
                    'Resume'=>1,
                    'Epf Declaration Form'=>3,   
                );
            }
            else if($Desgination=="Office Assistant"){
                $NC=8;
                $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Code Of Conduct'=>2,
                    'Epf Declaration Form'=>3, 
                );
            }
            else{
                $NC=17;
                $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Proof of Education'=>1,
                    'Code Of Conduct'=>2,
                    'Contrat Form'=>7,
                    'Resume'=>1,
                    'Epf Declaration Form'=>3, 
                );
            }  
        }
        else if($EmpType =="MGMT. TRAINEE"){
            if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
                $NC=7;
                $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Proof of Education'=>1,
                    'Code Of Conduct'=>2,
                    'Resume'=>1,
                );
            }
            else if($Desgination=="Office Assistant"){
                $NC=5; 
                $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Code Of Conduct'=>2,
                );
            }
            else{
              $NC=14;
              $mendArr=array(
                    'Aadhar'=>1,
                    'Address Proof'=>1,
                    'ID Proof'=>1,
                    'Proof of Education'=>1,
                    'Code Of Conduct'=>2,
                    'Contrat Form'=>7,
                    'Resume'=>1,
                );
            }
        }
        
        foreach($mendArr as $key=>$val){
            $TotCnt=$this->Masdocfile->find('count',array('fields'=>array('Id'),'conditions'=>array('OfferNo'=>$OfferNo,'DocType'=>$key)));
            $med=$val-$TotCnt;
            if($TotCnt >= $val){
                unset($mendArr[$key]);
            }
            else{
               $mendArr[$key]=$med;
            }
        }
        
        $this->set('mendatorydoc', $mendArr);
        
        
        
        $data1 = $this->Masdocfile->query("select Doctype from masdoc_option where `Docstatus` = '1' AND `parentid` IS NULL ORDER BY Doctype");
        $data12 = $this->Masdocfile->query("SELECT SUM(IF(DocType='Code Of Conduct',1,0)) AS coc, SUM(IF(DocType='Epf Declaration Form',1,0)) AS edf, SUM(IF(DocType='Contrat Form',1,0) )AS CF FROM `mas_docoments` WHERE OfferNo ='$EmpID'");
        $data122 = $this->Masdocfile->query("SELECT * FROM `mas_docoments` WHERE OfferNo ='$EmpID' and DocType !='PassBook' and `DocName`!='Bank Details'");
     
        $finish="";
        $check111=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='PassBook' and `DocName`='Bank Details'");
        if(empty($data122)){
            $finish="disabled ";  
        }
        IF(empty($check111)){
            $finish="disabled ";   
        }
        if($data12[0][0]['coc']>0 && $data12[0][0]['coc']<2  ){
           $finish="disabled ";
        }
        if($data12[0][0]['edf']>0 && $data12[0][0]['edf']<3  ){
           $finish="disabled";
        }
        if($data12[0][0]['CF']>0 && $data12[0][0]['CF']<7){
            $finish="disabled";
        }
        $this->set('Data1',$data1);
        $this->set('finish',$finish);
        
        $find= $this->Masdocfile->find('all',array('conditions'=>array('OfferNo'=>$EmpID)));
        $this->set('find',$find);
        $this->set('show',"Doc_File/".$EmpID."/"); 

        if($this->request->is('Post')){
            $EmpID = $this->request->data['OfferNo'];
            $dataArr=$this->request->data;
             
            $exp        =  explode("__", $dataArr['EditMasjclrs']['BioCode']);
            $biocode    = $exp[0];
           
                     
            $UpdArr=array(
                'EmpType'=>"'".trim(addslashes($dataArr['EditMasjclrs']['EmpType']))."'",
                'userid'=>"'".$user."'",
                //'BranchName'=>"'".trim(addslashes($dataArr['EditMasjclrs']['BranchName']))."'",
                'CostCenter'=>"'".trim(addslashes($dataArr['EditMasjclrs']['CostCenter']))."'",
                'EmpLocation'=>"'".trim(addslashes($dataArr['EditMasjclrs']['EmpLocation']))."'",
                //'BioCode'=>"'".$biocode."'",
                'Title'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Title']))."'",
                'EmpName'=>"'".trim(addslashes($dataArr['EditMasjclrs']['EmpName']))."'",
                'ParentType'=>"'".trim(addslashes($dataArr['Sw']))."'",
                'Father'=>"'".trim(addslashes($dataArr['Father']))."'",
                'Husband'=>"'".trim(addslashes($dataArr['Husband']))."'",
                'Gendar'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Gendar']))."'",
                'BloodGruop'=>"'".trim(addslashes($dataArr['EditMasjclrs']['BloodGruop']))."'",
                'NomineeName'=>"'".trim(addslashes($dataArr['EditMasjclrs']['NomineeName']))."'",
                'NomineeRelation'=>"'".trim(addslashes($dataArr['EditMasjclrs']['NomineeRelation']))."'",
                'NomineeDob'=>"'".date('Y-m-d',strtotime($dataArr['NomineeDob']))."'",
                'MaritalStatus'=>"'".trim(addslashes($dataArr['EditMasjclrs']['MaritalStatus']))."'",
                'Qualification'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Qualification']))."'",
                'DOB'=>"'".date('Y-m-d',strtotime($dataArr['DOB']))."'",
                'DOJ'=>"'".date('Y-m-d',strtotime($dataArr['DOJ']))."'",
                'Adrress1'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Adrress1']))."'",
                'Adrress2'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Adrress2']))."'",
                'City'=>"'".trim(addslashes($dataArr['City']))."'",
                'City1'=>"'".trim(addslashes($dataArr['City1']))."'",
                'State'=>"'".$this->statename($dataArr['State'])."'",
                'StateId'=>"'".$dataArr['State']."'",
                'State1'=>"'".$this->statename($dataArr['State1'])."'",
                'State1Id'=>"'".$dataArr['State1']."'",
                'PinCode'=>"'".trim(addslashes($dataArr['PinCode']))."'",
                'PinCode1'=>"'".trim(addslashes($dataArr['PinCode1']))."'",
                'Mobile'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Mobile']))."'",
                'Mobile1'=>"'".trim(addslashes($dataArr['EditMasjclrs']['Mobile1']))."'",
                'LandLine'=>"'".trim(addslashes($dataArr['EditMasjclrs']['LandLine']))."'",
                'LandLine1'=>"'".trim(addslashes($dataArr['EditMasjclrs']['LandLine1']))."'",
                'EmailId'=>"'".trim(addslashes($dataArr['EditMasjclrs']['EmailId']))."'",
                'OfficeEmailId'=>"'".trim(addslashes($dataArr['EditMasjclrs']['OfficeEmailId']))."'",
                'documentDone'=>"'".trim(addslashes($dataArr['documentDone']))."'",
                'PassportNo'=>"'".trim(addslashes($dataArr['EditMasjclrs']['PassportNo']))."'",
                'PanNo'=>"'".trim(addslashes($dataArr['EditMasjclrs']['PanNo']))."'",
                'AdharId'=>"'".trim(addslashes($dataArr['EditMasjclrs']['AdharId']))."'",
                'Dept'=>"'".trim(addslashes($dataArr['Dept']))."'",
                'Desgination'=>"'".trim(addslashes($dataArr['Desgination']))."'",
                'Profile'=>"'".trim(addslashes($dataArr['Profile']))."'",
                'SourceType'=>"'".trim(addslashes($dataArr['SourceType']))."'",
                'Source'=>"'".trim(addslashes($dataArr['Source']))."'",
                'KPI'=>"'".trim(addslashes($dataArr['KPI']))."'",
                'AcBank'=>"'".trim(addslashes($dataArr['AcBank']))."'",
                'AcBranch'=>"'".trim(addslashes($dataArr['AcBranch']))."'",
                'AccHolder'=>"'".trim(addslashes($dataArr['AccHolder']))."'",
                'AcNo'=>"'".trim(addslashes($dataArr['AcNo']))."'",
                'IFSCCode'=>"'".trim(addslashes($dataArr['IFSCCode']))."'",
                'AccType'=>"'".trim(addslashes($dataArr['AccType']))."'",
            );
            
           
            //$Approve1="Yes";
            //$UpdArr['Approve1']="'".$Approve1."'";
            //$UpdArr['ApproveDate1']="'".date('Y-m-d H:i:s')."'";
            
                    
            if($dataArr['type'] !=""){
                $type = $this->request->data['type'];
                $styp = $this->request->data['styp'];
                $BoxNo = $this->request->data['BoxNo'];
                $FileTye = $this->request->data['EditMasjclrs']['file']['type'];
                $info = explode(".",$this->request->data['EditMasjclrs']['file']['name']);
                $pageno = $this->request->data['pageno'];
                $fileno = $this->request->data['fileno'];
            
                $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='$type' and `DocName`='$styp' and fileno='$pageno'");
                
                if($pageno <=$fileno){
                    if($fileno!=0){  
                        $newfilename = $styp.'_'.$pageno. '.' . $info['1'];
                    }
                    else{
                        $newfilename = $styp.'.' . $info['1'];
                    }

                    if(!file_exists("Doc_File/".$EmpID)){ 
                        mkdir("Doc_File/".$EmpID); 
                    } 

                    $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                    fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                    fclose( $fp );

                    $temp = explode(".", $_FILES["file"]["name"]);

                    $target_file = "Doc_File/".$EmpID."/".basename($newfilename);
                    $FilePath = $this->request->data['EditMasjclrs']['file']['tmp_name'];

                    //$image = imagecreatefromjpeg($newfilename);
                    //imagejpeg($image, null, 10);
                    //imagejpeg($image, $this->request->data['EditMasjclrs']['file']['tmp_name'], 10);

                    if (move_uploaded_file($FilePath, $target_file)){
                        if(empty($check)){
                            $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='$type',`DocName`='$styp',`BoxNo`='$BoxNo',`userid`='$user',filename='$newfilename',fileno='$pageno',`saveDate`= now()");
                            if($pageno != ''){
                                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >'.$styp. " Page No ".$pageno ." Save Successfully  Out of ".$fileno.'</span>'); 
                                return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                            }
                            else {
                                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >'.$styp.' update Successfully.</span>');
                                return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                            } 
                        }
                        else{
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This document type already uploaded.</span>');
                            return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This document type not save please try again later.</span>');
                        return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                    }  
                }      
                else {
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Wrong page select.</span>');
                    return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                } 
            }
            
           
            if($this->request->data['EditMasjclrs']['CancelledChequeImage']['name'] !==''){
                $bankFileTye = $this->request->data['EditMasjclrs']['CancelledChequeImage']['type'];
                $bankfileinfo = explode(".",$this->request->data['EditMasjclrs']['CancelledChequeImage']['name']);
                
                $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='PassBook' and `DocName`='Bank Details'");
                $newfilename = 'PassBook.' . $bankfileinfo['1'];
          
                if(!file_exists("Doc_File/".$EmpID)){ 
                    mkdir("Doc_File/".$EmpID); 
                }
                
                $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                fclose( $fp );			   
		$temp = explode(".", $_FILES["file"]["name"]);
        
                $target_file = "Doc_File/".$EmpID."/".basename($newfilename);
                $FilePath = $this->request->data['EditMasjclrs']['CancelledChequeImage']['tmp_name']; 
   
                //$image = imagecreatefromjpeg($newfilename);
                //imagejpeg($image, null, 10);
                //imagejpeg($image, $this->request->data['EditMasjclrs']['CancelledChequeImage']['tmp_name'], 10);

                if(move_uploaded_file($FilePath, $target_file)){
                    if(empty($check)){
                        $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='PassBook',`DocName`='Bank Details',`userid`='$user',filename='$newfilename',`saveDate`= now()");
                        $data3 =  $this->Masdocfile->query("update `mas_jclr` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' where `Id`='$EmpID'");
                        $UpdArr['CancelledChequeImage']="'".$newfilename."'";
                    }
                    else{
                        $this->Session->setFlash(" File already exiest.");  
                        return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                    }
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >File  not Save.</span>'); 
                    return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                }
            }
            
            /*
            if(isset($dataArr['TabName']) && $dataArr['TabName'] =="tab4"){
                $Approve1="Yes";
                $UpdArr['Approve1']="'".$Approve1."'";
                $UpdArr['ApproveDate1']="'".date('Y-m-d H:i:s')."'";
                
                if ($this->Masjclrentry->updateAll($UpdArr,array('id'=>$dataArr['MasJclrsId']))){
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >JCLR submit successfully and empcode generat within 48 hours.</span>'); 
                    return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Employee details does not update please try again later.</span>'); 
                    return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
                }
            }
            */
            
            if ($this->Masjclrentry->updateAll($UpdArr,array('id'=>$dataArr['MasJclrsId']))){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Employee details update successfully.</span>'); 
                return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Employee details does not update please try again later.</span>'); 
                return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$dataArr['MasJclrsId'])));
            }
        }
    }
    
    /*
    
    public function get_biocode(){
        $branchName = $this->request->data['branch'];
        $this->NewjclrMaster->virtualFields = array('slab'=>'CONCAT(NewJclrMaster.BioCode,"-",NewJclrMaster.EmpName)');
        $this->set('bio',$this->NewjclrMaster->find('list',array('fields'=>array('NewJclrMaster.BioCode','NewJclrMaster.slab'),'conditions'=>array('BranchName'=>$branchName,'Approve'=>'Yes'))));
        $this->layout='ajax';
    }
    
    */
    
    public function get_biocode(){
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
        
        $dataArr=$this->MasJclrentrydata->find('all',array('fields'=>array('BioCode','EmpName'),'conditions'=>array('BranchName'=>$branchName,'TrainningStatus'=>'No')));
        
        if(!empty($dataArr)){
            echo "<option value=''>Select</option>";
            foreach($dataArr as $val){
                $k=$val['MasJclrentrydata']['BioCode'];
                $v=$val['MasJclrentrydata']['BioCode']."__".strtoupper($val['MasJclrentrydata']['EmpName']);
                echo "<option value='$v'>$v</option>";
            }
        }
        else{
            echo "<option value=''>Select</option>";
        }
        die;   
    }
    
    public function get_biocode1(){
        $this->layout='ajax';
        $branchName = $this->request->data['branch'];
        $biocode = $this->request->data['biocode'];
        
        $dataArr=$this->MasJclrentrydata->find('all',array('fields'=>array('BioCode','EmpName'),'conditions'=>array('BranchName'=>$branchName,'TrainningStatus'=>'No')));
        
        if(!empty($dataArr)){
            echo "<option value=''>Select</option>";
            foreach($dataArr as $val){
                $k=$val['MasJclrentrydata']['BioCode'];
                $v=$val['MasJclrentrydata']['BioCode']."__".strtoupper($val['MasJclrentrydata']['EmpName']);
                if($biocode ==$v){$selected="selected='selected'";}else{$selected="";}
                echo "<option $selected value='$v'>$v</option>";
            }
        }
        else{
            echo "<option value=''>Select</option>";
        }
        die;   
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    






    public function appointment_letter(){
        $this->layout='home';
        if($this->request->is('POST')){
            $empid=$this->request->data['empid'];
            $empArr = $this->Jclr->find('first',array('conditions'=>array('EmpCode'=>$empid))); 
            $this->set('data',$empArr);
        }
    }

    



    public function view()
    {
       $this->set('Jclr', $this->Jclr->query("select * from qual_employee where JCLRApprove = '0'"));
        $this->layout='home';
    }
    
    public function editjclr()
    {			
        $id = $this->request->query['id'];
        $branchName = $this->Session->read('branch_name');
        $this->set('Jclr', $this->MasJclrMaster->find('first',array('conditions'=>array('Id'=>$id))));
        $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName))));
        	 $this->set('Depart',$this->Design->find('list',array('fields'=>array('Department','Department'))));	
        $this->layout='home';
        $user = $this->Session->read('userid');
        if($this->request->is('Post')){
            
                $dataArr=$this->request->data['MasJclrMaster'];
                $dataArr['userid']=$user;
                 $dataArr['FatherName']=$this->request->data['Father'];
                                $dataArr['HusbandName']=$this->request->data['Husband'];
                                $dataArr['DOJ']=  date_format( date_create($dataArr['DOJ']),'Y-m-d');
                                $dataArr['DOB']=  date_format( date_create($dataArr['DOB']),'Y-m-d');
                                $dataArr['CreateDate']=date('Y-m-d H:i:s');
                                $dataArr['BranchName']=$branchName;
                                $dataArr['userid']=$user;
                
                
                foreach ($dataArr as $k=>$v)
                {
                    $ArrayData[$k]="'".$v."'";
                }
                $date1 = $dataArr['DOB'];
                  //print_r($dataArr);die;
 $date2 = $dataArr['DOJ'];

  $ts1 = strtotime($date1);
$ts2 = strtotime($date2);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
   
  $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
 
               if($diff >= 216)   
               {
                
                if ($this->MasJclrMaster->updateAll($ArrayData,array('Id'=>$id)))
				{
                                    $this->Session->setFlash("JCLR Update successfully"); 
                                    return $this->redirect(array('action'=>'newemp'));
                                }
 else {
    $this->Session->setFlash("JCLR is not Updated");  
 }
               }
               else{
                   $this->Session->setFlash("Employee Age is not 18 Plus.");  
               }
                
           
            }
    }

     

    
    public function save_doc(){
        $this->layout = "home";
        $EmpID = $this->request->query['id'];
        $this->set('empid',$EmpID);
        $this->set('ID', $EmpID);
        
        $data1 = $this->Masdocfile->query("select Doctype from masdoc_option where `Docstatus` = '1' AND `parentid` IS NULL");
        $data12 = $this->Masdocfile->query("SELECT SUM(IF(DocType='Code Of Conduct',1,0)) AS coc, SUM(IF(DocType='Epf Declaration Form',1,0)) AS edf, SUM(IF(DocType='Contrat Form',1,0) )AS CF FROM `mas_docoments` WHERE OfferNo ='$EmpID'");
        $data122 = $this->Masdocfile->query("SELECT * FROM `mas_docoments` WHERE OfferNo ='$EmpID' and DocType !='PassBook' and `DocName`!='Bank Details'");
     
      $finish="";
      $check111=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='PassBook' and `DocName`='Bank Details'");
       if(empty($data122)){
          $finish="disabled ";  
       }
       IF(empty($check111))
       {
        $finish="disabled ";   
       }
       if($data12[0][0]['coc']>0 && $data12[0][0]['coc']<7  )
       {
           $finish="disabled ";
       }
       if($data12[0][0]['edf']>0 && $data12[0][0]['edf']<3  )
       {
           $finish="disabled";
       }
        if($data12[0][0]['CF']>0 && $data12[0][0]['CF']<2)
       {
           $finish="disabled";
       }
       
        $this->set('Data1',$data1);
        
        
        $this->set('finish',$finish);
             
        if($this->request->is('POST')){   
        //print_r($this->request->data);die;
        $user = $this->Session->read('userid');
        
        $bankdetails= $this->request->data['bankdetails'];
        $submit= $this->request->data['submit']; 
         $AcNo= $this->request->data['Jclr']['AcNo'];
         $bank= $this->request->data['Jclr']['Bank'];
         $bankIFSC= $this->request->data['Jclr']['IFSC'];
         $ACType= $this->request->data['Jclr']['ACType'];
         $BankBranch= $this->request->data['Jclr']['BankBranch'];
         $bankFileTye = $this->request->data['Jclr']['bankfile']['type'];
            $bankfileinfo = explode(".",$this->request->data['Jclr']['bankfile']['name']);
        
        if($bankdetails=='bankdet'){
            
          
             // $EmpID= $this->request->data['Jclr']['EmpID'];
              // print_r($EmpID);die;
               $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='PassBook' and `DocName`='Bank Details'");
            
            
             
            
           $newfilename = 'PassBook.' . $bankfileinfo['1'];
              
           // $filename=$this->request->data['Save']['file']['name'];
           
       // print_r($filename);die;
         if(!file_exists("Doc_File/".$EmpID)) 
                      { 
                        mkdir("Doc_File/".$EmpID); 
                      }                        
                       $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                       fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                       fclose( $fp );
					   //$ins = mysql_query("Insert Into updqry Values ('File Name=$FileName And UserID=$UserId',now())", $this->db);
						$temp = explode(".", $_FILES["file"]["name"]);


        // print_r($info);die;
$target_file = "Doc_File/".$EmpID."/".basename($newfilename);
 $FilePath = $this->request->data['Jclr']['bankfile']['tmp_name']; 
if($bankfileinfo['1']=='jpg'){
    //$image = imagecreatefromjpeg($newfilename);

//imagejpeg($image, null, 10);
//imagejpeg($image, $this->request->data['Jclr']['bankfile']['tmp_name'], 10);
       if (move_uploaded_file($FilePath, $target_file)) {
          
            
           if(empty($check)){
        $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='PassBook',`DocName`='Bank Details',`userid`='$user',filename='$newfilename',`saveDate`= now()");
          $data3 =  $this->Masdocfile->query("update `mas_jclr` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' where `Id`='$EmpID'");
     $this->Session->setFlash($styp. "Bank Details Save Successfully.");
            
           return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           
           }
           else
           {
             $this->Session->setFlash(" File already exiest.");  
              return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           }
        }
        else{
           $this->Session->setFlash("File  not Save");
            return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
       }
}
       else{
           $this->Session->setFlash("Balnk File Type is not jpg"); 
            return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
       }
       
            
               
      
           
           
       }
      
      
       
       
       
       
        $type = $this->request->data['type'];
              $styp = $this->request->data['styp'];
               $BoxNo = $this->request->data['BoxNo'];
              $FileTye = $this->request->data['Save']['file']['type'];
            $info = explode(".",$this->request->data['Save']['file']['name']);
             $pageno = $this->request->data['pageno'];
               $fileno = $this->request->data['fileno'];
              
             $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='$type' and `DocName`='$styp' and fileno='$pageno'");
            
            
            if($pageno <=$fileno)
            {
              if($fileno!=0){  
            
           $newfilename = $styp.'_'.$pageno. '.' . $info['1'];
              }
              else
              {
                  $newfilename = $styp.'.' . $info['1'];
              }
           // $filename=$this->request->data['Save']['file']['name'];
           
       // print_r($filename);die;
         if(!file_exists("Doc_File/".$EmpID)) 
                      { 
                        mkdir("Doc_File/".$EmpID); 
                      }                        
                       $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                       fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                       fclose( $fp );
					   //$ins = mysql_query("Insert Into updqry Values ('File Name=$FileName And UserID=$UserId',now())", $this->db);
						$temp = explode(".", $_FILES["file"]["name"]);


        // print_r($info);die;
$target_file = "Doc_File/".$EmpID."/".basename($newfilename);
$FilePath = $this->request->data['Save']['file']['tmp_name'];
if($info['1']=='jpg'){
    //$image = imagecreatefromjpeg($newfilename);

//imagejpeg($image, null, 10);
//imagejpeg($image, $this->request->data['Save']['file']['tmp_name'], 10);
       if (move_uploaded_file($FilePath, $target_file)) {
          
           if(empty($check)){
        $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='$type',`DocName`='$styp',`BoxNo`='$BoxNo',`userid`='$user',filename='$newfilename',fileno='$pageno',`saveDate`= now()");
            if($pageno != '')
            {
           $this->Session->setFlash($styp. " Page No ".$pageno ." Save Successfully  Out of ".$fileno);
            }
 else {
     $this->Session->setFlash($styp. " Save Successfully.");
 }
           return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
           }
           else
           {
             $this->Session->setFlash(" File already exiest.");  
           }
        }
        else{
           $this->Session->setFlash("File  not Save"); 
       }
}
       else{
           $this->Session->setFlash("File Type is not jpg"); 
       }
       
            }
 else {
     $this->Session->setFlash("Wrong page select."); 
 }
          
    }
        $find= $this->Masdocfile->find('all',array('conditions'=>array('OfferNo'=>$EmpID)));
      //  print_r($find);die;
         $this->set('find',$find);
         $this->set('show',"Doc_File/".$EmpID."/"); 
     
    }
    
    
    
   public function saverelation()
   {
        $this->layout = "home";
        if($this->request->is('POST'))
        {   
             $user = $this->Session->read('userid');
     if($this->request->data['Submit']=='Finish'){
            $data['MasRelation']=$this->request->data['Masjclrs'];
           $id= $data['MasRelation']['OfferNo'];
                    $data['MasRelation']['RelDOB']=date_format( date_create($data['MasRelation']['RelDOB']),'Y-m-d');
                    $data['MasRelation']['userid']=$user;
            unset($data['MasRelation']['Submit']);
       // print_r($data);die;
         if ($this->MasRelation->saveall($data))
				{
                   // $id= $this->Jclr->getLastInsertId();
             $data2 = $this->Mastmpjclr->query("INSERT INTO `masjclrentry` (`OfferNo`,`EmpType`,`EmpCode`,`userid`,`BranchName`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`Father`,
`Husband`,`Gendar`,`BloodGruop`,`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`State1`,`PinCode`,
`PinCode1`,`Mobile`,`Mobile1`,`EmailId`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Profile`,`CostCenter`,`Source`,
`KPI`,`Band`,`CTC`,`EPFNo`,`ESICNo`,`Status`,`CreateDate`) SELECT
`OfferNo`,`EmpType`,`EmpCode`,`userid`,`BranchName`,`EmpLocation`,`BioCode`,`Title`,`EmpName`,`Father`,`Husband`,`Gendar`,`BloodGruop`,
`MaritalStatus`,`Qualification`,`DOB`,`DOJ`,`Adrress1`,`Adrress2`,`City`,`City1`,`State`,`State1`,`PinCode`,`PinCode1`,`Mobile`,`Mobile1`,
`EmailId`,`OfficeEmailId`,`PassportNo`,`PanNo`,`AdharId`,`Dept`,`Desgination`,`Profile`,`CostCenter`,`Source`,`KPI`,`Band`,`CTC`,`EPFNo`,
`ESICNo`,'1',NOW() FROM `mastmpjaclr` WHERE OfferNo = $id;");
                 $upd= $this->MasJclrMaster->query("update mas_jclr set JCLRStatus = '1' where Id='$id' ");
                  
                	$this->Session->setFlash(__('Insert Succussfully.'));
                	return $this->redirect(array('action' => 'newemp?id='.$id));
                  
            	}
                return $this->redirect(array('action' => 'save_doc?id='.$id));
                $this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
         
       }   
   }
   }
     public function get_status_data()
    {
       
         $this->layout = "ajax";
        if($this->request->is('POST'))
        {
          $data=  $this->request->data;
          
          $fileno = $this->request->data['fileno'];
           $selectchek12 = $this->Masdocfile->query("select Id from doc_option where `Docstatus` = '1' AND `Doctype` ='{$data['types']}'");
        //print_r($selectchek12);die;
         $selectchek1 = $this->Masdocfile->query("select Doctype from doc_option where `Docstatus` = '1' AND `parentid` ='{$selectchek12['0']['doc_option']['Id']}'");
         //$this->set('status',$selectchek12);
         ?>
<select name="styp" id="styp" class="form-control">
        <option value="">Select</option>
        <?php foreach($selectchek1 as $sek){ ?>
        <option value="<?php echo $sek['doc_option']['Doctype']; ?>"><?php echo $sek['doc_option']['Doctype']; ?></option>
        <?php } ?>
    </select>
<input type="hidden" name="fileno" value="<?php echo$fileno; ?>">
<?php
        
        }die;
    } 
      
    
    public function deletefile(){
        $this->layout = "ajax";
        $path = $this->request->query['path'];
        $EmpID = $this->request->query['EmpCode'];
        $fileno = $this->request->query['fileno'];
        $filename = $this->request->query['filename'];
	$this->Masdocfile->query("delete from mas_docoments where OfferNo= '$EmpID' and filename ='$filename'");
        unlink($path);
        return $this->redirect(array('controller' => 'EditMasjclrs','action'=>'newjclr','?'=>array('id'=>$_REQUEST['MasJclrId'])));
    }
    
    
   public function get_package()
    {
        $this->layout='ajax';
        $val = $this->request->data['desgn'];
        $this->set('packageData',$this->maspackage->find('all',array('conditions'=>array('PackageAmount'=>$val))));
    } 
    
     public function showctc()
    {
        $this->layout='ajax';
        $val = $this->request->data['desgn'];
        $this->set('TCT',$this->maspackage->find('list',array('fields'=>array('NetInHand'),'conditions'=>array('PackageAmount'=>$val))));
    } 
 public function get_design()
    {
        $this->layout='ajax';
        $val = $this->request->data['val'];
        
       $this->set('Desig',$this->Design->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Department'=>$val))));
       
    }   
     public function get_band()
    {
        $this->layout='ajax';
       $valer = $this->request->data['val'];
        $this->masband->virtualFields = array(
    'slab'=>'CONCAT(masband.BandName,"(",masband.SlabFrom,"-",masband.SlabTo,")")'
);
//       $Band= $this->masband->find('list',array('fields'=>array('BandName','slab'),'conditions'=>array('Designation'=>$val)));
//       
//                  $this->set('Desig',$Band);
                  
        //echo $valer;die;
        
        
        $query_options = array();
$query_options['fields'] = array('masband.BandName','masband.slab');
$query_options['conditions'] =array('deg.Designation '=>$valer);
$query_options['joins'] =array(array(
                        'table' => 'masdesignation',
                        'alias' => 'deg',
                        'type' => 'INNER',
                        'conditions' => array(
                        'masband.BandName = deg.Band'
                        )
                    )
                    );
                  $this->set('Desig',$this->masband->find('list',$query_options));
            
                  
       //$this->set('',$this->Design->find('list',array('fields'=>array('Band','Band'),'conditions'=>array('Designation'=>$val))));
       
    }  
    public function showpack()
    {
        $this->layout='ajax';
       $valer = $this->request->data['pack'];
       
//       $Band= $this->masband->find('list',array('fields'=>array('BandName','slab'),'conditions'=>array('Designation'=>$val)));
//       
//                  $this->set('Desig',$Band);
                  
        //echo $valer;die;
        
        
        $query_options = array();
$query_options['fields'] = array('maspackage.PackageAmount','maspackage.PackageAmount');
$query_options['conditions'] =array('maspackage.Band'=>$valer);
$query_options['joins'] =array(array(
                        'table' => 'mas_band',
                        'alias' => 'band',
                        'type' => 'INNER',
                        'conditions' => array(
                        'band.BandName = maspackage.Band'
                        )
                    )
                    );
                  $this->set('Des',$this->maspackage->find('list',$query_options));
            
                  
       //$this->set('',$this->Design->find('list',array('fields'=>array('Band','Band'),'conditions'=>array('Designation'=>$val))));
       
    }
    
    public function newemp(){
        $this->layout='home';
    }
  public function get_data(){
        $this->layout='ajax';
         $data1 = $this->MasJclrMaster->query('select * from mas_jclr where JCLRStatus = "0"');
        $this->set('masJclr',$data1);
    }
    
    
    
    
    
    
  
    
    public function deleteemp(){
        $id = $this->request->query['id'];
        $this->layout='ajax';
        $this->Masdocfile->query("delete from mas_jclr where Id= '$id'");
        
       
         return $this->redirect(array('action'=>'newemp'));
        
    }
    
    
    
    public function get_name(){
         $val = $this->request->data['vale'];
        
       
       
        $this->set('Emp',$this->MasJclrMaster->find('list',array('fields'=>array('MasJclrMaster.EmpName'),'conditions'=>array('BioCode'=>$val))));
        $this->layout='ajax';
    }
}

?>