<?php
class EmpDetCheckController extends AppController 
{
    public $uses = array('Jclr','User','Design','maspackage','masband','MasJclrMaster','CostCenterMaster','Masattandance','Mastmpjclr','Masdocfile','MasRelation','Masjclrentry','Addbranch');
        
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            if(in_array('77',$roles)){$this->Auth->allow('index','view','get_band','get_design','showpack','get_package','jclrapprove','showctc','newemp','get_data');}
            else{$this->Auth->deny('index');}
            $this->Auth->allow('index','view','get_band','update_cost','showpack','jclrapprove','get_design','get_package','showctc','newemp','get_data','editjclr','newjclr','get_biocode','get_name','save_doc','get_status_data','deletefile','saverelation','deleteemp');
            
            $this->Auth->allow('index','get_cost_center','get_band','update_cost','showpack','jclrapprove','get_design','get_package','showctc','newemp','get_data','editjclr','newjclr','get_biocode','get_name','save_doc','get_status_data','deletefile','saverelation','deleteemp');
        }
    }
    
    public function get_cost_center()
    {
        $this->layout="ajax";
        $branchId = $this->request->data['branchId'];
        if($branchId!='All')
        {
            $qry = " and bm.id='$branchId'";
        }
        else
        {
            $qry = " ";
        }
        $costArr = $this->CostCenterMaster->query("SELECT cost_center FROM branch_master bm INNER JOIN cost_master cm ON bm.branch_name = cm.branch WHERE bm.active=1 AND cm.active=1 $qry");
        
        $cost_str = '';
        foreach($costArr as $cost)
        {
            $cost_str .= '<option value="'.$cost['cm']['cost_center'].'">'.$cost['cm']['cost_center'].'</option>';
        }
        
        
        echo $cost_str; exit;
    }
    
    
    public function index()
    {
        $this->layout = "home";
        $branchName = $this->Session->read('branch_name');
        $user = $this->Session->read('userid');
        
        $role = $this->Session->read('role');
        if($role=='admin')
        {
            $branchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('active'=>1)));
            $branchArr = array('All'=>'All')+$branchArr;
        }
        else
        {
            $branchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('branch_name'=>$branchName,'active'=>1)));
        }
        
        
        $this->set('bm',$branchArr); 
         $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName))));
        $this->set('Depart',$this->Design->find('list',array('fields'=>array('Department','Department'))));
                   
		//$data1 = $this->masband->query('select MAX(EmpCode) as EmpCode from qual_employee');
               $old= str_replace("MAS","",$data1[0][0]['EmpCode']);
                $new=$old+1;
               $mas= str_replace($old,$new,$data1[0][0]['EmpCode']);
		
		    if ($this->request->is('post')) 
			{
                        // print_r($this->request->is('post')); exit;
				//$this->Jclr->create();
				$data=$this->request->data;
                                //print_r($data);die;
                               $data['Masjclrentry']['FatherName']=$data['Father'];
                                $data['Masjclrentry']['HusbandName']=$data['Husband'];
                                $data['Masjclrentry']['DOJ']=  date_format( date_create($data['MasJclrMaster']['DOJ']),'Y-m-d');
                                $data['Masjclrentry']['DOB']=  date_format( date_create($data['MasJclrMaster']['DOB']),'Y-m-d');
                                $data['Masjclrentry']['CreateDate']=date('Y-m-d H:i:s');
                                $data['Masjclrentry']['BranchName']=$branchName;
                                $data['Masjclrentry']['userid']=$user;
                              // print_r($data['MasJclrMaster']);die;
                                // print_r($this->Jclr->saveall($data));die;
                                
                                 $date1 = $data['MasJclrMaster']['DOB'];
                  print_r($data);die;
 $date2 = $data['MasJclrMaster']['DOJ'];

  $ts1 = strtotime($date1);
$ts2 = strtotime($date2);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
   
  $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
 
               if($diff >= 216)   
               {
                                if(($data['MasJclrMaster']['EmpType']=='OnRoll' && !empty($data['MasJclrMaster']['EPF'])&& !empty($data['MasJclrMaster']['ESIC'])) || ($data['MasJclrMaster']['EmpType']=='Mgmt. Trainee' && empty($data['MasJclrMaster']['EPF'])&& empty($data['MasJclrMaster']['ESIC'])))
                                {
            	if ($this->Masjclrentry->saveall($data))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	$this->Session->setFlash(__('Insert Succussfully.'));
                	return $this->redirect(array('action' => 'index'));
                  
            	}
                $this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
                                }
 else {
    $this->Session->setFlash(__('Please Select Right Package According To Employee Type.')); 
 }
               }
               else
               {
                  $this->Session->setFlash(__('Employee Age is not 18 Plus.'));   
               }
            	
			}

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

     public function jclrapprove(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        $this->set('OdArr',$this->Masjclrentry->find('all',array('conditions'=>array('BranchName'=>$branchName,'Approve'=>NULL)))); 
       
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
                        $i=0;
                        foreach ($OdIdArr as $Id){
                             $data1 = $this->Masjclrentry->query('select MAX(EmpCode) as EmpCode from masjclrentry where EmpCode is not null');
                            // print_r($data1);
                             
        $i++;
        if(empty($data1[0][0]['EmpCode'])){
            $mas='MAS'.$i;
        }
        else{
                $old= str_replace("MAS","",$data1[0][0]['EmpCode']);
                $new=$old+$i;
               $mas= str_replace($old,$new,$data1[0][0]['EmpCode']);
        }
       // echo $mas;die;
                            $this->Masjclrentry->updateAll(array('EmpCode'=>"'".$mas."'",'Approve'=>"'".$status."'",'ApproveDate'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$Id,'BranchName'=>$branchName));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;" >Please check option to approve/not approve record.</span>'); 
                        $this->redirect(array('action'=>'jclrapprove'));
                    }
                   $this->Session->setFlash('<span style="color:green;" >JCLR Approved.</span>');   
                }
               
            }
            
            $this->redirect(array('action'=>'jclrapprove'));   
        }     
    }

    
    public function save_doc()
    {
            
            $EmpID = $this->request->query['id'];//print_r($EmpID);die;
            $this->set('empid',$EmpID);
            $this->set('ID', $EmpID);
        $this->layout = "home";
        $data1 = $this->Masdocfile->query("select Doctype from masdoc_option where `Docstatus` = '1' AND `parentid` IS NULL");
        $data12 = $this->Masdocfile->query("SELECT SUM(IF(DocType='Code Of Conduct',1,0)) AS coc, SUM(IF(DocType='Epf Declaration Form',1,0)) AS edf, SUM(IF(DocType='Contrat Form',1,0) )AS CF FROM `mas_docoments` WHERE OfferNo ='$EmpID'");
        $data122 = $this->Masdocfile->query("SELECT * FROM `mas_docoments` WHERE OfferNo ='$EmpID' and DocType !='PassBook' and `DocName`!='Bank Details'");
      //print_r($data122);die;
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
         if($this->request->is('POST'))
        {   
         // print_r($this->request->data);die;
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
    $image = imagecreatefromjpeg($newfilename);

imagejpeg($image, null, 10);
imagejpeg($image, $this->request->data['Jclr']['bankfile']['tmp_name'], 10);
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
    $image = imagecreatefromjpeg($newfilename);

imagejpeg($image, null, 10);
imagejpeg($image, $this->request->data['Save']['file']['tmp_name'], 10);
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
<select name="styp" class="form-control">
        <option value="">DocName</option>
        <?php foreach($selectchek1 as $sek){ ?>
        <option value="<?php echo $sek['doc_option']['Doctype']; ?>"><?php echo $sek['doc_option']['Doctype']; ?></option>
        <?php } ?>
    </select>
<input type="hidden" name="fileno" value="<?php echo$fileno; ?>">
<?php
        
        }die;
    } 
      public function deletefile() {
	$this->layout = "ajax";
        //print_r($this->request->query);die;
       $path = $this->request->query['path'];
       $EmpID = $this->request->query['EmpCode'];
       $fileno = $this->request->query['fileno'];
        $filename = $this->request->query['filename'];
	$this->Masdocfile->query("delete from mas_docoments where OfferNo= '$EmpID' and filename ='$filename'");
        
        unlink($path);
         return $this->redirect(array('action'=>'save_doc?id='.$EmpID));
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
    public function newjclr()
    {
         $branchName = $this->Session->read('branch_name');
        $user = $this->Session->read('userid');
        $id = $this->request->query['id'];
        $branchName = $this->Session->read('branch_name');
        $this->set('Jclr', $this->MasJclrMaster->find('first',array('conditions'=>array('Id'=>$id))));
        $this->set('tower1',$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array('branch'=>$branchName))));
        	 $this->set('Depart',$this->Design->find('list',array('fields'=>array('Department','Department'))));	
        $this->layout='home';	
        if($this->request->is('Post')){
            
                $dataArr=$this->request->data;
               // print_r($dataArr);die;
                $dataArr['Mastmpjclr']['FatherName']=$dataArr['Father'];
                 $dataArr['Mastmpjclr']['HusbandName']=$dataArr['Husband'];
                 $dataArr['Mastmpjclr']['Band']=$dataArr['MasJclrMaster']['Band'];
                 $dataArr['Mastmpjclr']['Desgination']=$dataArr['MasJclrMaster']['Desgination'];
                  $dataArr['Mastmpjclr']['userid']=$user;
                   $dataArr['Mastmpjclr']['OfferNo']=$id;
                  $dataArr['Mastmpjclr']['DOJ']=  date_format( date_create($dataArr['Mastmpjclr']['DOJ']),'Y-m-d');
                 $dataArr['Mastmpjclr']['DOB']=  date_format( date_create($dataArr['Mastmpjclr']['DOB']),'Y-m-d');
                  //$dataArr['Mastmpjclr']['OfferNo']=$id;
                  $date1 = $dataArr['Mastmpjclr']['DOB'];
                  //print_r($dataArr);die;
 $date2 = $dataArr['Mastmpjclr']['DOJ'];

  $ts1 = strtotime($date1);
$ts2 = strtotime($date2);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
   
  $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
 
               if($diff >= 216)   
               {
                
                if ($this->Mastmpjclr->saveall($dataArr))
				{
                   // $id= $this->Jclr->getLastInsertId();
                 // $upd= $this->Jclr->query("update qual_employee set EmpCode = 'MAS80$id' where Id='$id' ");
                  
                	//$this->Session->setFlash(__('Insert Succussfully.'));
                	return $this->redirect(array('action' => 'save_doc?id='.$id));
                  
            	}
                $this->Session->setFlash(__('The Details could not be saved. Please, try again.'));
            }
            else{
            $this->Session->setFlash(__('Employee Age is not 18 Plus.')); 
        }
        }
        
    }
  
    
    public function deleteemp(){
        $id = $this->request->query['id'];
        $this->layout='ajax';
        $this->Masdocfile->query("delete from mas_jclr where Id= '$id'");
        
       
         return $this->redirect(array('action'=>'newemp'));
        
    }
    
    public function get_biocode(){
        $branchName = $this->request->data['branch'];
        
        
        $this->MasJclrMaster->virtualFields = array(
    'slab'=>'CONCAT(MasJclrMaster.BioCode,"-",MasJclrMaster.EmpName)'
       );
        $this->set('bio',$this->MasJclrMaster->find('list',array('fields'=>array('MasJclrMaster.BioCode','MasJclrMaster.slab'),'conditions'=>array('BranchName'=>$branchName,'JCLRStatus'=>0))));
        $this->layout='ajax';
    }
    
    public function get_name(){
         $val = $this->request->data['vale'];
        
       
       
        $this->set('Emp',$this->MasJclrMaster->find('list',array('fields'=>array('MasJclrMaster.EmpName'),'conditions'=>array('BioCode'=>$val))));
        $this->layout='ajax';
    }
}

?>