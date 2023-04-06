<?php
class FnfMisReportsController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','Masdocfile');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
       
        
        $this->Auth->allow('index','view','viewdetails','get_band','update_cost','showpack','jclrapprove','get_design','get_package',
                'showctc','newemp','get_data','editjclr','newjclr','get_biocode','get_name','save_doc','get_status_data',
                'deletefile','saverelation','deleteemp','check_date','checkdoc','getcity','getdept','getdesg','getband','getctc',
                'getinhand','getpackage','jclrentry','deletejclr','editcity','editdept','get_biocode1','getsourcename','checkdoc1',
                'generateempcode','getcostcenter','getfnddetails','fnfexport');
        
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
            $branch_name    =   $this->request->data['FnfMisReports']['branch_name'];
            $SearchType     =   $this->request->data['SearchType'];
            $SearchValue    =   trim($this->request->data['SearchValue']);
            $CostCenter     =   trim($this->request->data['CostCenter']);
            $StartDate      =   $this->request->data['StartDate'];
            
            $y  =   date('Y',strtotime($StartDate));
            $m  =   date('m',strtotime($StartDate));
            
            $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m);
    
            if($branch_name !="ALL"){$conditoin['BranchName']=$branch_name;}else{unset($conditoin['BranchName']);}
            if($CostCenter !="ALL"){$conditoin['CostCenter']=$CostCenter;}else{unset($conditoin['CostCenter']);}
                   
            $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'order'=>'BranchName','group'=>'BranchName'));
            $data=array();
            foreach($data1 as $row){
                
                //$doc=$this->getdoc($row['Masjclrentry']['BranchName'],$row['Masjclrentry']['CostCenter'],'',$_REQUEST['type']);
                
                
                if($CostCenter !="ALL"){$conditoin['CostCenter']=$row['Masjclrentry']['CostCenter'];}else{unset($conditoin['CostCenter']);}
                $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m,'BranchName'=>$row['Masjclrentry']['BranchName']);
                
                $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>$conditoin));
                $Upload     =   $this->Masjclrentry->find('count',array('conditions'=>array_merge($conditoin,array('FnfDoc !='=>''))));
                $Pending     =   $this->Masjclrentry->find('count',array('conditions'=>array_merge($conditoin,array('FnfDoc'=>null))));
                $Validate     =   $this->Masjclrentry->find('count',array('conditions'=>array_merge($conditoin,array('FnfStatus'=>'Validate'))));
                $Reject     =   $this->Masjclrentry->find('count',array('conditions'=>array_merge($conditoin,array('FnfStatus'=>'Reject'))));
                $data[]=array(
                    'BranchName'=>$row['Masjclrentry']['BranchName'],
                    'CostCenter'=>$CostCenter,
                    'TotalEmp'=>$TotalData,
                    'NocUploaded'=>$Upload,
                    'NocValidate'=>$Validate,
                    'NocReject'=>$Reject,
                    'NocPending'=>$Pending,
                );
            }
            
            if($this->request->data['Submit'] =="Search"){
                $this->set('branchname',$branch_name);
                $this->set('CostCenter',$CostCenter);
                $this->set('empmonth',$StartDate);
                $this->set('data',$data);
            }
            else{
                $this->layout='ajax';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=FnfMisReport.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
               ?>
               <table border="1"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;">SNo</th>
                                    <th style="text-align: center;">Branch</th>
                                    <th style="text-align: center;">CostCenter</th>
                                    <th style="text-align: center;">LeftEmp</th>
                                    <th style="text-align: center;">NOC Uploaded</th>
                                    <th style="text-align: center;">NOC Pending</th>
                                    <th style="text-align: center;">NOC Validate</th>
                                    <th style="text-align: center;">NOC Reject</th>
                                    
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; 
                                $temp=0;
                                $tupl=0;
                                $tval=0;
                                $trej=0;
                                $tpen=0;
                                
                                foreach ($data as $val){
                                $temp=$temp+$val['TotalEmp'];
                                $tupl=$tupl+$val['NocUploaded'];
                                $tval=$tval+$val['NocValidate'];
                                $trej=$trej+$val['NocReject'];
                                $tpen=$tpen+$val['NocPending'];
                                ?>
                                <tr>
                                    <td style="text-align: center;" ><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                                    <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                                    <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                                    <td style="text-align: center;"><?php echo $val['NocUploaded'];?></td>
                                    <td style="text-align: center;"><?php echo $val['NocPending'];?></td>
                                    <td style="text-align: center;"><?php echo $val['NocValidate'];?></td>
                                    <td style="text-align: center;"><?php echo $val['NocReject'];?></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align: center;"><strong>Total</strong></td>
                                    <td style="text-align: center;"><?php echo $temp;?></td>
                                    <td style="text-align: center;"><?php echo $tupl;?></td>
                                    <td style="text-align: center;"><?php echo $tpen;?></td>
                                    <td style="text-align: center;"><?php echo $tval;?></td>
                                    <td style="text-align: center;"><?php echo $trej;?></td>
                                    
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
               <?php
               die;
                
            }
            
        }  
    }
    
    public function getfnddetails(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){ 
            
            $y  =   date('Y',strtotime($_REQUEST['StartDate']));
            $m  =   date('m',strtotime($_REQUEST['StartDate']));
            
            $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m);
           
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            
            if($_REQUEST['Status']=="NocUploaded"){
                $dataArr=  array_merge($conditoin,array('FnfDoc !='=>''));
            }
            else if($_REQUEST['Status']=="NocValidate"){
                $dataArr=  array_merge($conditoin,array('FnfStatus'=>'Validate'));
            }
            else if($_REQUEST['Status']=="NocReject"){
                $dataArr=  array_merge($conditoin,array('FnfStatus'=>'Reject'));
            }
            else if($_REQUEST['Status']=="NocPending"){
                $dataArr=  array_merge($conditoin,array('FnfDoc'=>NULL));
            }
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$dataArr,'order'=>'BranchName'));
            
            if(!empty($data)){  
            ?>
            <input type="button" value="Export" onclick="fnfexport('<?php echo $_REQUEST['BranchName'];?>','<?php echo $_REQUEST['CostCenter'];?>','<?php echo $_REQUEST['Status'];?>','<?php echo $_REQUEST['StartDate'];?>')" class="btn pull-right btn-primary btn-new" >
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="text-align: center;width:30px;">SNo</th>
                        <th style="text-align: center;width:80px;">EmpCode</th>
                        <th style="width:160px;" >EmpName</th>
                        <th style="text-align: center;width:80px;">LeftDate</th>
                        <th style="text-align: center;width:150px;">Designation</th>
                        <th style="text-align: center;width:80px;">NOC Status</th>
                        <th style="text-align: center;width:200px;">NOC Remarks</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $n=1; foreach ($data as $row){?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td ><?php echo $row['Masjclrentry']['EmpName']?></td>
                        <td style="text-align: center;"><?php if($row['Masjclrentry']['ResignationDate'] !=""){echo date('d-m-Y',strtotime($row['Masjclrentry']['ResignationDate']));}?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['Desgination']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['FnfStatus']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['NocValidateRemarks']?></td>
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
    
    public function fnfexport(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){ 
            
            $this->layout='ajax';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Fnfdetails.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $y  =   date('Y',strtotime($_REQUEST['StartDate']));
            $m  =   date('m',strtotime($_REQUEST['StartDate']));
            
            $conditoin=array('Status'=>0,'YEAR(ResignationDate)'=>$y,'MONTH(ResignationDate)'=>$m);
           
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            if($_REQUEST['CostCenter'] !="ALL"){$conditoin['CostCenter']=$_REQUEST['CostCenter'];}else{unset($conditoin['CostCenter']);}
            
            if($_REQUEST['Status']=="NocUploaded"){
                $dataArr=  array_merge($conditoin,array('FnfDoc !='=>''));
            }
            else if($_REQUEST['Status']=="NocValidate"){
                $dataArr=  array_merge($conditoin,array('FnfStatus'=>'Validate'));
            }
            else if($_REQUEST['Status']=="NocReject"){
                $dataArr=  array_merge($conditoin,array('FnfStatus'=>'Reject'));
            }
            else if($_REQUEST['Status']=="NocPending"){
                $dataArr=  array_merge($conditoin,array('FnfDoc'=>NULL));
            }
            
            $data   =   $this->Masjclrentry->find('all',array('conditions'=>$dataArr,'order'=>'BranchName'));
            
            ?>
            <table border="1" >     
                <thead>
                    <tr>
                        <th style="text-align: center;">SNo</th>
                        <th style="text-align: center;">EmpCode</th>
                        <th >EmpName</th>
                        <th style="text-align: center;">LeftDate</th>
                        <th style="text-align: center;">Designation</th>
                        <th style="text-align: center;">NOC Status</th>
                        <th style="text-align: center;">NOC Remarks</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $n=1; foreach ($data as $row){?>
                    <tr>
                        <td style="text-align: center;"><?php echo $n++;?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['EmpCode']?></td>
                        <td ><?php echo $row['Masjclrentry']['EmpName']?></td>
                        <td style="text-align: center;"><?php if($row['Masjclrentry']['ResignationDate'] !=""){echo date('d-m-Y',strtotime($row['Masjclrentry']['ResignationDate']));}?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['Desgination']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['FnfStatus']?></td>
                        <td style="text-align: center;"><?php echo $row['Masjclrentry']['NocValidateRemarks']?></td>
                    </tr>
                    <?php }?>
                </tbody>   
            </table>
            <?php   
            die;
        }
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
    
   
      
    
    
}
?>