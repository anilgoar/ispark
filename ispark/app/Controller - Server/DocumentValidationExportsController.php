<?php
class DocumentValidationExportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','FieldAttendanceMaster','OnSiteAttendanceMaster','HolidayMaster','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','show_report','getcostcenter','export_report','account_report');
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
            
            /*
            $url='/var/www/html/ispark/app/webroot/Doc_File/26239/FNF.jpg';
            $files = array('FNF.jpg');
            
            
            
            $zip = new ZipArchive();
            $zip_name = time().".zip";
            $zip->open($zip_name,  ZipArchive::CREATE);
            foreach ($files as $file) {
                echo $path = $url.$file;
                if(file_exists($path)){
                    $zip->addFromString(basename($path),  file_get_contents($path));  
                }
                else{
                    echo"file does not exist";
                }
            }
            
            $zip->close();
            */
            

            /*
            $image1 = "/var/www/html/ispark/app/webroot/Doc_File/26239/FNF.jpg";
            $image2 = "/var/www/html/ispark/app/webroot/Doc_File/26239/Aadhar Id.jpg";

            $files = array($image1, $image2);

            $tmpFile = tempnam('/tmp', '');

            $zip = new ZipArchive;
            $zip->open($tmpFile, ZipArchive::CREATE);
            foreach ($files as $file) {
                // download file
                $fileContent = file_get_contents($file);

                $zip->addFromString(basename($file), $fileContent);
            }
            $zip->close();

            header("Content-type: application/zip"); 
            header('Content-disposition: attachment; filename=file.zip');
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            readfile("$tmpFile"); 
            
          

            unlink($tmpFile);
            die;
             */
     
            
            
            $headArr=array(
                'branch'=>array('Branch','TotalEmp','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','NotUploaded','Rejected'),
                'costcenter'=>array('Branch','CostCenter','TotalEmp','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','NotUploaded','Rejected'),
                'data'=>array('EmpCode','EmpName','Branch','CostCenter','Designation','EmpFor','EmpType','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','Total','NotUploaded'), 
            );
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            
            if($_REQUEST['type'] =="branch"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'BranchName'));
            }
            else if($_REQUEST['type'] =="costcenter"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'CostCenter'));
            }
            else if($_REQUEST['type'] =="data"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'EmpCode'));
            }
            
            $data=array();
            $TotArr=array();
            
            $TotalEmp=0;
            $PoI=0;
            $PoA=0;
            $PoE=0;
            $CoC=0;
            $CF=0;
            $EPF=0;
            $Adhar=0;
            $Resume=0;
            $NotUploaded=0;
            $Rejected=0;
            foreach($data1 as $row){
   
                if($_REQUEST['type'] =="branch"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],'',$_REQUEST['type']);
                    $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'])));
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'TotalEmp'=>$TotalData,
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'NotUploaded'=>$doc['NotUploaded'],
                        'Rejected'=>$doc['Rejected'],
                    );
                    
                    
                    $TotalEmp=$TotalEmp+$TotalData;
                    $PoI=$PoI+$doc['PoI'];
                    $PoA=$PoA+$doc['PoA'];
                    $PoE=$PoE+$doc['PoE'];
                    $CoC=$CoC+$doc['CoC'];
                    $CF=$CF+$doc['CF'];
                    $EPF=$EPF+$doc['EPF'];
                    $Adhar=$Adhar+$doc['Adhar'];
                    $Resume=$Resume+$doc['Resume'];
                    $NotUploaded=$NotUploaded+$doc['NotUploaded'];
                    $Rejected=$Rejected+$doc['Rejected'];
                    
                    
                }
                else if($_REQUEST['type'] =="costcenter"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],'',$_REQUEST['type']);
                    $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'CostCenter'=>$row['Masjclrentry']['CostCenter'])));
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                        'TotalEmp'=>$TotalData,
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'NotUploaded'=>$doc['NotUploaded'],
                        'Rejected'=>$doc['Rejected'],
                    );
                    
                    $TotalEmp=$TotalEmp+$TotalData;
                    $PoI=$PoI+$doc['PoI'];
                    $PoA=$PoA+$doc['PoA'];
                    $PoE=$PoE+$doc['PoE'];
                    $CoC=$CoC+$doc['CoC'];
                    $CF=$CF+$doc['CF'];
                    $EPF=$EPF+$doc['EPF'];
                    $Adhar=$Adhar+$doc['Adhar'];
                    $Resume=$Resume+$doc['Resume'];
                    $NotUploaded=$NotUploaded+$doc['NotUploaded'];
                    $Rejected=$Rejected+$doc['Rejected'];
                }
                else if($_REQUEST['type'] =="data"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],$row['Masjclrentry']['OfferNo'],$_REQUEST['type']);
                    if($doc['NotUploaded'] ==0){$docstatus="Yes";}else{$docstatus="No";}
                        
                    $data[]=array(
                        'EmpCode'=>$row['Masjclrentry']['EmpCode'],
                        'EmpName'=>$row['Masjclrentry']['EmpName'],
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                        'Designation'=>$row['Masjclrentry']['Desgination'],
                        'EmpFor'=>$row['Masjclrentry']['EmpLocation'],
                        'EmpType'=>$row['Masjclrentry']['EmpType'],
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'Total'=>($doc['PoI']+$doc['PoA']+$doc['PoE']+$doc['CoC']+$doc['CF']+$doc['Adhar']+$doc['Resume']),
                        'NotUploaded'=>$docstatus,
                    );
                }
                
                
                
                
                
                
            }
            
            $TotArr=array(
                'TotalEmp'=>$TotalEmp,
                'PoI'=>$PoI,
                'PoA'=>$PoA,
                'PoE'=>$PoE,
                'CoC'=>$CoC,
                'CF'=>$CF,
                'EPF'=>$EPF,
                'Adhar'=>$Adhar,
                'Resume'=>$Resume,
                'NotUploaded'=>$NotUploaded,
                'Rejected'=>$Rejected,
            );
            
            
            if(!empty($data)){  
            ?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;">SrNo</th>
                            <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                                <th style="text-align: center;"><?php echo $hed;?></th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $n=1; foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                                <td style="text-align: center;"><?php echo $val[$hed];?></td>
                            <?php }?>
                        </tr>
                        <?php }?>
                        
                       <?php if($_REQUEST['type'] !="data"){?>
                        <tr>
                            <td style="text-align: center;font-weight: bold;">Total</td>
                            <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                                <?php if($hed =="Branch"){?>
                                <td style="text-align: center;"></td>
                                <?php }else if($hed =="CostCenter"){?>
                                <td style="text-align: center;"></td>
                                <?php }else{?>
                                <td style="text-align: center;"><?php echo $TotArr[$hed];?></td>
                       <?php }}}?>
                        </tr>
                      
                    </tbody>   
                </table>,
            </div>
            <?php   
            }
            else{
                echo "";
            }
            die;
        
        }
    }
    
    
    
    

    
    
    
    
    
    public function getdoc($branch,$costcenter,$offerno,$type){
        if($type=="branch"){
            $conditon=array('BranchName'=>$branch,'Status'=>1);
        }
        else if($type=="costcenter"){
            $conditon=array('BranchName'=>$branch,'CostCenter'=>$costcenter,'Status'=>1);
        }
        if($type=="data"){
            $conditon=array('OfferNo'=>$offerno,'Status'=>1);
        }
            
        $data   =   $this->Masjclrentry->find('all',array('fields'=>'OfferNo','conditions'=>$conditon));
       
        $PoI=0;$PoA=0;$PoE=0;$CoC=0;$CF=0;$EPF=0;$Adhar=0;$Resums=0;$NotUpload=0;$Reject=0;
        foreach($data as $row){
            $docArr=$this->Masdocfile->query("SELECT 
                IF(SUM(IF(DocType ='ID Proof',1,0)) > 0,1,0) AS PoI,
                IF(SUM(IF(DocType ='Address Proof',1,0)) > 0,1,0) AS PoA,
                IF(SUM(IF(DocType ='Proof of Education',1,0)) > 0,1,0) AS PoE,
                IF(SUM(IF(DocType ='Code Of Conduct',1,0)) > 0,1,0) AS CoC,
                IF(SUM(IF(DocType ='Contrat Form',1,0)) > 0,1,0) AS CF,
                IF(SUM(IF(DocType ='Epf Declaration Form',1,0)) > 0,1,0) AS EPF,
                IF(SUM(IF(DocType ='Aadhar',1,0)) > 0,1,0) AS Adhar,
                IF(SUM(IF(DocType ='Resume',1,0)) > 0,1,0) AS Resums,
                IF(COUNT(OfferNo) > 0,0,1) AS NotUpload,
                IF(SUM(IF(DocStatus ='Reject',1,0)) > 0,1,0) AS Reject
                FROM `mas_docoments` 
                WHERE OfferNo='{$row['Masjclrentry']['OfferNo']}';");
                
                $PoI=$PoI+$docArr[0][0]['PoI'];
                $PoA=$PoA+$docArr[0][0]['PoA'];
                $PoE=$PoE+$docArr[0][0]['PoE'];
                $CoC=$CoC+$docArr[0][0]['CoC'];
                $CF=$CF+$docArr[0][0]['CF'];
                $EPF=$EPF+$docArr[0][0]['EPF'];
                $Adhar=$Adhar+$docArr[0][0]['Adhar'];
                $Resums=$Resums+$docArr[0][0]['Resums'];
                $NotUpload=$NotUpload+$docArr[0][0]['NotUpload'];
                $Reject=$Reject+$docArr[0][0]['Reject'];
        }
       
        return array('PoI'=>$PoI,'PoA'=>$PoA,'PoE'=>$PoE,'CoC'=>$CoC,'CF'=>$CF,'EPF'=>$EPF,'Adhar'=>$Adhar,'Resume'=>$Resums,'NotUploaded'=>$NotUpload,'Rejected'=>$Reject);   
    }
    
    
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=DocumentValidationReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $headArr=array(
                'branch'=>array('Branch','TotalEmp','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','NotUploaded','Rejected'),
                'costcenter'=>array('Branch','CostCenter','TotalEmp','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','NotUploaded','Rejected'),
                'data'=>array('EmpCode','EmpName','Branch','CostCenter','Designation','EmpFor','EmpType','PoI','PoA','PoE','CoC','CF','EPF','Adhar','Resume','Total','NotUploaded'), 
            );
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
            
            if($_REQUEST['type'] =="branch"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'BranchName'));
            }
            else if($_REQUEST['type'] =="costcenter"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'CostCenter'));
            }
            else if($_REQUEST['type'] =="data"){
                $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'EmpCode'));
            }
            
            $data=array();
            $TotArr=array();
            
            $TotalEmp=0;
            $PoI=0;
            $PoA=0;
            $PoE=0;
            $CoC=0;
            $CF=0;
            $EPF=0;
            $Adhar=0;
            $Resume=0;
            $NotUploaded=0;
            $Rejected=0;
            foreach($data1 as $row){
   
                if($_REQUEST['type'] =="branch"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],'',$_REQUEST['type']);
                    $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'])));
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'TotalEmp'=>$TotalData,
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'NotUploaded'=>$doc['NotUploaded'],
                        'Rejected'=>$doc['Rejected'],
                    );
                    
                    $TotalEmp=$TotalEmp+$TotalData;
                    $PoI=$PoI+$doc['PoI'];
                    $PoA=$PoA+$doc['PoA'];
                    $PoE=$PoE+$doc['PoE'];
                    $CoC=$CoC+$doc['CoC'];
                    $CF=$CF+$doc['CF'];
                    $EPF=$EPF+$doc['EPF'];
                    $Adhar=$Adhar+$doc['Adhar'];
                    $Resume=$Resume+$doc['Resume'];
                    $NotUploaded=$NotUploaded+$doc['NotUploaded'];
                    $Rejected=$Rejected+$doc['Rejected'];
                }
                else if($_REQUEST['type'] =="costcenter"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],'',$_REQUEST['type']);
                    $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'CostCenter'=>$row['Masjclrentry']['CostCenter'])));
                    $data[]=array(
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                        'TotalEmp'=>$TotalData,
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'NotUploaded'=>$doc['NotUploaded'],
                        'Rejected'=>$doc['Rejected'],
                    );
                    
                    $TotalEmp=$TotalEmp+$TotalData;
                    $PoI=$PoI+$doc['PoI'];
                    $PoA=$PoA+$doc['PoA'];
                    $PoE=$PoE+$doc['PoE'];
                    $CoC=$CoC+$doc['CoC'];
                    $CF=$CF+$doc['CF'];
                    $EPF=$EPF+$doc['EPF'];
                    $Adhar=$Adhar+$doc['Adhar'];
                    $Resume=$Resume+$doc['Resume'];
                    $NotUploaded=$NotUploaded+$doc['NotUploaded'];
                    $Rejected=$Rejected+$doc['Rejected'];
                }
                else if($_REQUEST['type'] =="data"){
                    $doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],$row['Masjclrentry']['OfferNo'],$_REQUEST['type']);
                    if($doc['NotUploaded'] ==0){$docstatus="Yes";}else{$docstatus="No";}
                        
                    $data[]=array(
                        'EmpCode'=>$row['Masjclrentry']['EmpCode'],
                        'EmpName'=>$row['Masjclrentry']['EmpName'],
                        'Branch'=>$row['Masjclrentry']['BranchName'],
                        'CostCenter'=>$row['Masjclrentry']['CostCenter'],
                        'Designation'=>$row['Masjclrentry']['Desgination'],
                        'EmpFor'=>$row['Masjclrentry']['EmpLocation'],
                        'EmpType'=>$row['Masjclrentry']['EmpType'],
                        'PoI'=>$doc['PoI'],
                        'PoA'=>$doc['PoA'],
                        'PoE'=>$doc['PoE'],
                        'CoC'=>$doc['CoC'],
                        'CF'=>$doc['CF'],
                        'EPF'=>$doc['EPF'],
                        'Adhar'=>$doc['Adhar'],
                        'Resume'=>$doc['Resume'],
                        'Total'=>($doc['PoI']+$doc['PoA']+$doc['PoE']+$doc['CoC']+$doc['CF']+$doc['Adhar']+$doc['Resume']),
                        'NotUploaded'=>$docstatus,
                    );
                }
            }
            
            $TotArr=array(
                'TotalEmp'=>$TotalEmp,
                'PoI'=>$PoI,
                'PoA'=>$PoA,
                'PoE'=>$PoE,
                'CoC'=>$CoC,
                'CF'=>$CF,
                'EPF'=>$EPF,
                'Adhar'=>$Adhar,
                'Resume'=>$Resume,
                'NotUploaded'=>$NotUploaded,
                'Rejected'=>$Rejected,
            );
            
            ?>
            <table border="1"  >     
                <thead>
                    <tr>
                        <th>SrNo</th>
                        <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                            <th><?php echo $hed;?></th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody> 
                    <?php $n=1; foreach($data as $val){?>
                    <tr>
                        <td><?php echo $n++;?></td>
                        <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                            <td><?php echo $val[$hed];?></td>
                        <?php }?>
                    </tr>
                    <?php }?>
                    
                    <?php if($_REQUEST['type'] !="data"){?>
                        <tr>
                            <td >Total</td>
                            <?php foreach($headArr[$_REQUEST['type']] as $hed){?>
                                <?php if($hed =="Branch"){?>
                                <td ></td>
                                <?php }else if($hed =="CostCenter"){?>
                                <td ></td>
                                <?php }else{?>
                                <td ><?php echo $TotArr[$hed];?></td>
                       <?php }}}?>
                        </tr>
                    
                </tbody> 
            </table>
            <?php 
            die;
        }
        
    }
    
    
    
    public function account_report(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AccountValidationReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],);
           
            
            if($_REQUEST['Type'] =="Uploaded"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'AcNo !='=>'','AccHolder !='=>'');
            }
            else if($_REQUEST['Type'] =="Verified"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'AcValidationStatus'=>'Yes');
            }
            else if($_REQUEST['Type'] =="Pending"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'AcNo !='=>'','AccHolder !='=>'','AcValidationStatus'=>NULL);
            }
            else if($_REQUEST['Type'] =="NotUploaded"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'AcNo ='=>'','AccHolder ='=>'');
            }
            else if($_REQUEST['Type'] =="Rejected"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName'],'AcValidationStatus'=>'No');
            }
            else if($_REQUEST['Type'] =="Total"){
                $conditoin=array('Status'=>1,'BranchName'=>$_REQUEST['BranchName']);
            }
           
            $data  =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin));
            
            /*
            $data=array();
            foreach($data1 as $row){
                $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'])));
                $Uploaded   =   $this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'')));
                $Verified   =   $this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'Yes')));
                $Rejected   =   $this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'No')));
                $Pending    =   $this->Masjclrentry->find('count',array('conditions'=>array('BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'','AcValidationStatus'=>NULL)));
                $NotUploated=($TotalData-$Uploaded);
               
                $data[]=array(
                    'BranchName'=>$row['Masjclrentry']['BranchName'],
                    'Uploaded'=>$Uploaded,
                    'Verified'=>$Verified,
                    'Pending'=>$Pending,
                    'NotUploaded'=>$NotUploated,
                    'Rejected'=>$Rejected,
                    'Total'=>$TotalData,
                );
            }*/
            
            ?>
            <table border="1"  >     
                <thead>
                        <tr>
                            <th>EmpCode</th>
                            <th>EmpName</th>
                            <th>Branch</th>
                            <th>CostCenter</th>
                            <th>ACHolderName</th>
                            <th>Account No</th>
                            <th>Bank Name</th>
                            <th>IFSC Code</th>
                            <th>Account Type</th>
                            <th>Payment Mode</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($data as $val){?>
                        <tr>
                            <td><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td><?php echo $val['Masjclrentry']['BranchName'];?></td>
                            <td><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td><?php echo $val['Masjclrentry']['AccHolder'];?></td>
                            <td><?php echo $val['Masjclrentry']['AcNo'];?></td>
                            <td><?php echo $val['Masjclrentry']['AcBank'];?></td>
                            <td><?php echo $val['Masjclrentry']['IFSCCode'];?></td>
                            <td><?php echo $val['Masjclrentry']['AccType'];?></td>
                            <td><?php echo $val['Masjclrentry']['PayMode'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>   
            </table>
            <?php 
            die;
        }
    }
    
    
    
    
    
}
?>