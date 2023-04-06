<?php
class ProcessNocsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
       
        
        $this->Auth->allow('index','view','viewdetails','onlinedetails','get_band','update_cost','showpack','jclrapprove','get_design','get_package',
                'showctc','newemp','get_data','editjclr','newjclr','get_biocode','get_name','save_doc','get_status_data',
                'deletefile','saverelation','deleteemp','check_date','checkdoc','getcity','getdept','getdesg','getband','getctc',
                'getinhand','getpackage','jclrentry','deletejclr','editcity','editdept','get_biocode1','getsourcename','checkdoc1',
                'generateempcode','getcostcenter');
        
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
            $branch_name    =   $this->request->data['ProcessNocs']['branch_name'];
            $SearchType     =   $this->request->data['SearchType'];
            $SearchValue    =   trim($this->request->data['SearchValue']);
            $CostCenter     =   trim($this->request->data['CostCenter']);
            $StartDate      =   $this->request->data['StartDate'];
            
            $y  =   date('Y',strtotime($StartDate));
            $m  =   date('m',strtotime($StartDate));
            
            $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m);
    
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
            
            //if($SearchType =="EmpName"){$conditoin['EmpName LIKE']=$SearchValue.'%';}else{unset($conditoin['EmpName LIKE']);}
            //if($SearchType =="EmpCode"){$conditoin['EmpCode']=$SearchValue;}else{unset($conditoin['EmpCode']);}
            //if($SearchType =="BioCode"){$conditoin['BioCode']=$SearchValue;}else{unset($conditoin['BioCode']);}
                
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin)); 
            $this->set('branchname',$branch_name);
            $this->set('empmonth',$StartDate);
            $this->set('data',$data);
            $this->set('data',$data);
        }  
    }
    
public function viewdetails(){
    $this->layout='home';
        
    if(isset($_REQUEST['EJEID'])){
        $EJEID = base64_decode($_REQUEST['EJEID']);
        $user = $this->Session->read('userid');
        $id = $EJEID;

        $Jclr=$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$id)));
        $branchName=$Jclr['Masjclrentry']['BranchName'];
        $LeftDate   =$Jclr['Masjclrentry']['ResignationDate'];
        $this->set('data',$Jclr);
        $EmpID = $Jclr['Masjclrentry']['OfferNo'];
        $this->set('empid',$EmpID);
        $this->set('ID', $EmpID);

        $OfferNo        =   $Jclr['Masjclrentry']['OfferNo'];
        $EmpType        =   $Jclr['Masjclrentry']['EmpType'];
        $Desgination    =   $Jclr['Masjclrentry']['Desgination'];
       
        if($EmpType =="ONROLL"){
            //if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
            if($Desgination=="EXECUTIVE"){
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
            else if($Desgination=="OFFICE ASSISTANT"){
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

            //if($Desgination=="Executive - Voice" || $Desgination=="Sr. Executive - Voice" || $Desgination=="Executive - Field" || $Desgination=="Sr.Executive - Field"){
            if($Desgination=="EXECUTIVE"){
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
            else if($Desgination=="OFFICE ASSISTANT"){
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
            
           

            if($TotCnt >= $val || strtotime($LeftDate) <= strtotime('2018-12-31')){
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
             
        }
        
        if($this->request->is('Post')){
            $EmpID = $this->request->data['OfferNo'];
            $dataArr=$this->request->data;
                     
            $ReleasingChequeDate=$dataArr['ReleasingChequeDate'] !=""?date('Y-m-d',strtotime($dataArr['ReleasingChequeDate'])):""; 
            $ChequeDate=$dataArr['ChequeDate'] !=""?date('Y-m-d',strtotime($dataArr['ChequeDate'])):"";
                     
            $UpdArr=array(
                'ReleasingChequeDate'=>"'".$ReleasingChequeDate."'",
                'ChequeAmount'=>"'".trim(addslashes($dataArr['ChequeAmount']))."'",
                'ChequeDate'=>"'".$ChequeDate."'",
                'ChequeNo'=>"'".trim(addslashes($dataArr['ChequeNo']))."'",
                'ReasonofLeaving'=>"'".trim(addslashes($dataArr['ReasonofLeaving']))."'",
            );
            
           
                  
            if($dataArr['type'] !=""){
                $type = $this->request->data['type'];
                $styp = $this->request->data['styp'];
                $BoxNo = $this->request->data['BoxNo'];
                $FileTye = $this->request->data['ProcessNocs']['file']['type'];
                $info = explode(".",$this->request->data['ProcessNocs']['file']['name']);
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
                    $FilePath = $this->request->data['ProcessNocs']['file']['tmp_name'];

                   // $image = imagecreatefromjpeg($newfilename);
                    //imagejpeg($image, null, 10);
                   // imagejpeg($image, $this->request->data['ProcessNocs']['file']['tmp_name'], 10);
                    
                    
                     //print_r($UpdArr);die;
                    

                    if (move_uploaded_file($FilePath, $target_file)){
                        if(empty($check)){
                            $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='$type',`DocName`='$styp',`BoxNo`='$BoxNo',`userid`='$user',filename='$newfilename',fileno='$pageno',`saveDate`= now()");
                            if($pageno != ''){
                                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >'.$styp. " Page No ".$pageno ." Save Successfully  Out of ".$fileno.'</span>'); 
                                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                            }
                            else {
                                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >'.$styp.' update Successfully.</span>');
                                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                            } 
                        }
                        else{
                            $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This document type already uploaded.</span>');
                            return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                        }
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This document type not save please try again later.</span>');
                        return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                    }  
                }      
                else {
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Wrong page select.</span>');
                    return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                } 
            }
            
           
            if($this->request->data['ProcessNocs']['CancelledChequeImage']['name'] !==''){
                $bankFileTye = $this->request->data['ProcessNocs']['CancelledChequeImage']['type'];
                $bankfileinfo = explode(".",$this->request->data['ProcessNocs']['CancelledChequeImage']['name']);
                
                $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='FNF' and `DocName`='FNF Details'");
                $newfilename = 'FNF.' . $bankfileinfo['1'];
          
                if(!file_exists("Doc_File/".$EmpID)){ 
                    mkdir("Doc_File/".$EmpID); 
                }
                
                $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                fclose( $fp );			   
		$temp = explode(".", $_FILES["file"]["name"]);
        
                $target_file = "Doc_File/".$EmpID."/".basename($newfilename);
                $FilePath = $this->request->data['ProcessNocs']['CancelledChequeImage']['tmp_name']; 
   
                //$image = imagecreatefromjpeg($newfilename);
                //imagejpeg($image, null, 10);
                //imagejpeg($image, $this->request->data['ProcessNocs']['CancelledChequeImage']['tmp_name'], 10);

                if(move_uploaded_file($FilePath, $target_file)){
                    if(empty($check)){
                        $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='FNF',`DocName`='FNF Details',`userid`='$user',filename='$newfilename',`saveDate`= now()");
                        //$data3 =  $this->Masdocfile->query("update `mas_jclr` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' where `Id`='$EmpID'");
                        $FnfStatus="Upload";
                        $UpdArr['FnfDoc']="'".$newfilename."'";
                        $UpdArr['FnfStatus']="'".$FnfStatus."'";
                        
                    }
                    else{
                        $this->Session->setFlash(" File already exiest.");  
                        return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                    }
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >File  not Save.</span>'); 
                    return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                }
            }
            
            if ($this->Masjclrentry->updateAll($UpdArr,array('id'=>$dataArr['MasJclrsId']))){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Employee details update successfully.</span>'); 
                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Employee details does not update please try again later.</span>'); 
                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
            }
        }        
    }
	
	public function onlinedetails(){
		$this->layout='home';
        
		if(isset($_REQUEST['EJEID'])){
			$EJEID 		= 	base64_decode($_REQUEST['EJEID']);
			$user 		= 	$this->Session->read('userid');
			$id 		= 	$EJEID;

			$Jclr		=	$this->Masjclrentry->find('first',array('conditions'=>array('id'=>$id)));
			$branchName	=	$Jclr['Masjclrentry']['BranchName'];
			$LeftDate   =	$Jclr['Masjclrentry']['ResignationDate'];
			$this->set('data',$Jclr); 
        }
        
        if($this->request->is('Post')){
            $EmpID 		= 	$this->request->data['OfferNo'];
            $dataArr	=	$this->request->data;
                              
            $UpdArr		=	array('ReasonofLeaving'=>"'".trim(addslashes($dataArr['ReasonofLeaving']))."'");
            
            if($this->request->data['ProcessNocs']['CancelledChequeImage']['name'] !==''){
                $bankFileTye = $this->request->data['ProcessNocs']['CancelledChequeImage']['type'];
                $bankfileinfo = explode(".",$this->request->data['ProcessNocs']['CancelledChequeImage']['name']);
                
                $check=$this->Masdocfile->query("select * from mas_docoments where `OfferNo`='$EmpID' and DocType='FNF' and `DocName`='FNF Details'");
                $newfilename = 'FNF.' . $bankfileinfo['1'];
          
                if(!file_exists("Doc_File/".$EmpID)){ 
                    mkdir("Doc_File/".$EmpID); 
                }
                
                $fp = fopen("Doc_File/".$EmpID."/".$newfilename, 'wb' );
                fwrite( $fp, $GLOBALS['HTTP_RAW_POST_DATA'] );
                fclose( $fp );			   
				$temp = explode(".", $_FILES["file"]["name"]);
        
                $target_file = "Doc_File/".$EmpID."/".basename($newfilename);
                $FilePath = $this->request->data['ProcessNocs']['CancelledChequeImage']['tmp_name']; 
   
                //$image = imagecreatefromjpeg($newfilename);
                //imagejpeg($image, null, 10);
                //imagejpeg($image, $this->request->data['ProcessNocs']['CancelledChequeImage']['tmp_name'], 10);

                if(move_uploaded_file($FilePath, $target_file)){
                    if(empty($check)){
                        $data2 = $this->Masdocfile->query("insert into mas_docoments set `OfferNo`='$EmpID',DocType='FNF',`DocName`='FNF Details',`userid`='$user',filename='$newfilename',`saveDate`= now()");
                        //$data3 =  $this->Masdocfile->query("update `mas_jclr` set `AcNo`='$AcNo',`Bank`='$bank',`IFSC`='$bankIFSC',BankBranch='$BankBranch',`ACType`='$ACType' where `Id`='$EmpID'");
                        $FnfStatus="Upload";
                        $UpdArr['FnfDoc']="'".$newfilename."'";
                        $UpdArr['FnfStatus']="'".$FnfStatus."'";
                        
                    }
                    else{
                        $this->Session->setFlash(" File already exiest.");  
                        return $this->redirect(array('controller' => 'ProcessNocs','action'=>'onlinedetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                    }
                }
                else{
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >File  not Save.</span>'); 
                    return $this->redirect(array('controller' => 'ProcessNocs','action'=>'onlinedetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
                }
            }
            
            if ($this->Masjclrentry->updateAll($UpdArr,array('id'=>$dataArr['MasJclrsId']))){
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Employee details update successfully.</span>'); 
                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'onlinedetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Employee details does not update please try again later.</span>'); 
                return $this->redirect(array('controller' => 'ProcessNocs','action'=>'onlinedetails','?'=>array('EJEID'=>base64_encode($dataArr['MasJclrsId']))));
            }
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
    
    public function deletefile(){
        $this->layout = "ajax";
        $path = $this->request->query['path'];
        $EmpID = $this->request->query['EmpCode'];
        $fileno = $this->request->query['fileno'];
        $filename = $this->request->query['filename'];
        $MasJclrId = $this->request->query['MasJclrId'];
	$this->Masdocfile->query("delete from mas_docoments where OfferNo= '$EmpID' and filename ='$filename'");
        unlink($path);
        if($filename=="FNF.jpg"){
            $this->Masdocfile->query("update masjclrentry set FnfStatus=null,FnfDoc=null where id= '$MasJclrId'");
        }
        $this->redirect(array('action'=>'viewdetails','?'=>array('EJEID'=>base64_encode($_REQUEST['MasJclrId']))));
    }

    public function getcostcenter(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            //$conditoin=array('Status'=>1);
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
      
    
    
}
?>