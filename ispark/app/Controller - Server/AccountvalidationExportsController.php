<?php
class AccountvalidationExportsController extends AppController {
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
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
           
            $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'BranchName'));
            
            $data=array();
            foreach($data1 as $row){
                $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'])));
                $Uploaded   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'')));
                $Verified   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'Yes')));
                $Rejected   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'No')));
                $Pending    =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'','AcValidationStatus'=>NULL)));
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
            }
            
            if(!empty($data)){  
            ?>
            <div class="col-sm-9" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th style="text-align: center;" >SNo</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Uploaded</th>
                            <th style="text-align: center;">Verified</th>
                            <th style="text-align: center;">Pending</th>
                            <th style="text-align: center;">Not Uploaded</th>
                            <th style="text-align: center;">Rejected</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php 
                        $n=1; 
                        $up=0;
                        $vf=0;
                        $pe=0;
                        $nu=0;
                        $re=0;
                        $to=0;
                        foreach($data as $val){
                            $up=$up+$val['Uploaded'];
                            $vf=$vf+$val['Verified'];
                            $pe=$pe+$val['Pending'];
                            $nu=$nu+$val['NotUploaded'];
                            $re=$re+$val['Rejected'];
                            $to=$to+$val['Total'];
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $n++;?></td>
                            <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','Uploaded')" ><?php echo $val['Uploaded'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','Verified')" ><?php echo $val['Verified'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','Pending')" ><?php echo $val['Pending'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','NotUploaded')" ><?php echo $val['NotUploaded'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','Rejected')" ><?php echo $val['Rejected'];?></td>
                            <td style="text-align: center;text-decoration:underline;cursor:pointer;" onclick="AccountReport('<?php echo $val['BranchName'];?>','Total')" ><?php echo $val['Total'];?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td style="text-align: center;" ></td>
                            <td style="text-align: center;">Total</td>
                            <td style="text-align: center;"><?php echo $up;?></td>
                            <td style="text-align: center;"><?php echo $vf;?></td>
                            <td style="text-align: center;"><?php echo $pe;?></td>
                            <td style="text-align: center;"><?php echo $nu;?></td>
                            <td style="text-align: center;"><?php echo $re;?></td>
                            <td style="text-align: center;"><?php echo $to;?></td>
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
    
    public function export_report(){
        
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AccountValidationReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $conditoin=array('Status'=>1);
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];$conditoin5['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);unset($conditoin5['BranchName']);}
           
            $data1   =   $this->Masjclrentry->find('all',array('conditions'=>$conditoin,'group'=>'BranchName'));
            
            $data=array();
            foreach($data1 as $row){
                $TotalData  =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'])));
                $Uploaded   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'')));
                $Verified   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'Yes')));
                $Rejected   =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcValidationStatus'=>'No')));
                $Pending    =   $this->Masjclrentry->find('count',array('conditions'=>array('Status'=>1,'BranchName'=>$row['Masjclrentry']['BranchName'],'AcNo !='=>'','AccHolder !='=>'','AcValidationStatus'=>NULL)));
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
            }
            
            ?>
            <table border="1"  >     
                <thead>
                        <tr>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">Uploaded</th>
                            <th style="text-align: center;">Verified</th>
                            <th style="text-align: center;">Pending</th>
                            <th style="text-align: center;">Not Uploaded</th>
                            <th style="text-align: center;">Rejected</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $val['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Uploaded'];?></td>
                            <td style="text-align: center;"><?php echo $val['Verified'];?></td>
                            <td style="text-align: center;"><?php echo $val['Pending'];?></td>
                            <td style="text-align: center;"><?php echo $val['NotUploaded'];?></td>
                            <td style="text-align: center;"><?php echo $val['Rejected'];?></td>
                            <td style="text-align: center;"><?php echo $val['Total'];?></td>
                        </tr>
                        <?php }?>
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
                            <th style="text-align: center;">EmpCode</th>
                            <th style="text-align: center;">EmpName</th>
                            <th style="text-align: center;">Branch</th>
                            <th style="text-align: center;">CostCenter</th>
                            <th style="text-align: center;">ACHolderName</th>
                            <th style="text-align: center;">Account No</th>
                            <th style="text-align: center;">Bank Name</th>
                            <th style="text-align: center;">IFSC Code</th>
                            <th style="text-align: center;">Account Type</th>
                            <th style="text-align: center;">Payment Mode</th>
                            <th style="text-align: center;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($data as $val){?>
                        <tr>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['BranchName'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['AccHolder'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['AcNo'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['AcBank'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['IFSCCode'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['AccType'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['PayMode'];?></td>
                            <td style="text-align: center;"><?php echo $val['Masjclrentry']['AcRejectionRemarks'];?></td>
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