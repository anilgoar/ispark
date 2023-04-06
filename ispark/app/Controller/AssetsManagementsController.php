<?php
class AssetsManagementsController extends AppController {
    public $uses = array('AssetsManagement','Addbranch','Masjclrentry','Masattandance','LeaveManagementMaster','LeaveRightsMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','export','delete','update','leaveentry','leaveapproval','discardapprovedleave','leavedetails','get_emp','get_gender','get_leave_details','exist_attendance','check_date','validate_leave_with_date','validate_el');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $data = $this->AssetsManagement->find('all',array('order'=>array('Branch')));
        }
        else{
            $data = $this->AssetsManagement->find('all',array('conditions'=>array('Branch'=>$branchName),'order'=>array('Branch')));
        }
        
        $this->set('data',$data);
        
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $RowData = $this->AssetsManagement->find('first',array('conditions'=>array('Id'=>$_REQUEST['Id']),'order'=>array('Branch')));
            $this->set('RowData',$RowData['AssetsManagement']);
        }
        
        if($this->request->is('Post')){
            $CreateBy               =   $this->Session->read('email');
            $dataArr                =   $this->request->data['AssetsManagements'];
            $dataArr['InstallationDate']= $dataArr['InstallationDate'] !=""? date('Y-m-d',strtotime($dataArr['InstallationDate'])):"";
            $dataArr['CreateBy']    =   $CreateBy;
            $dataArr['TotalAgents'] =   array_sum($dataArr['Agents']);
            $dataArr['Process']     =   implode(",", $dataArr['Process']);
            $dataArr['Agents']      =   implode(",", $dataArr['Agents']);
            
            //echo "<pre>";
            //print_r($dataArr);die;
            
            $checkExist = $this->AssetsManagement->find('first',array('conditions'=>array('ServerName'=>$dataArr['ServerName'])));
            if(!empty($checkExist)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This server name already exist in database.</span>');
                $this->redirect(array('action'=>'index'));
            }
            else{
                $this->AssetsManagement->saveAll($dataArr);
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your assets details save successfully.</span>');
                $this->redirect(array('action'=>'index'));
            }
        }   
    }
    
    public function update(){
        $this->layout='home';
        if($this->request->is('Post')){
            $UpdateBy               =   $this->Session->read('email');
            $dataArr                =   $this->request->data['AssetsManagements'];
            $dataArr['InstallationDate']= $dataArr['InstallationDate'] !=""? date('Y-m-d',strtotime($dataArr['InstallationDate'])):"";
            $dataArr['UpdateDate']  =   date('Y-m-d');
            $dataArr['UpdateBy']    =   $UpdateBy;
            $dataArr['TotalAgents'] =   array_sum($dataArr['Agents']);
            $dataArr['Process']     =   implode(",", $dataArr['Process']);
            $dataArr['Agents']      =   implode(",", $dataArr['Agents']);
            $Id                     =   $dataArr['Id'];

            $dataX = array();
            unset($dataArr['Id']);
            foreach($dataArr as $k=>$v){
                $dataX[$k] = "'".$v."'";
            }
            
            
            $checkExist = $this->AssetsManagement->find('first',array('conditions'=>array('ServerName'=>$dataArr['ServerName'],'Id !='=>$Id)));
            if(!empty($checkExist)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This server name already exist in database.</span>');
                $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
            }
            else{
                $this->AssetsManagement->updateAll($dataX,array('Id'=>$Id));
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your assets details update successfully.</span>');
                $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
            }
              
        }   
    }
    
    
    
    
    
    public function delete(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $Id=$_REQUEST['Id'];
            $this->AssetsManagement->query("DELETE FROM `Assets_Management` WHERE Id='$Id'");
            $this->redirect(array('action'=>'index')); 
        }
    }
    
    public function export(){
        $this->layout='home';
        
        $data = $this->AssetsManagement->find('all',array('order'=>array('Branch')));
        ?>
        <table border="1">    
                        <tr>                        
                            <th style="text-align: center;">Server&nbsp;Name</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">System&nbsp;Type</th>
                        <th style="text-align: center;">Brand</th>
                        <th style="text-align: center;">MotherBoard</th>
                        <th style="text-align: center;">Processor&nbsp;1</th>
                        <th style="text-align: center;">Core&nbsp;1</th>
                        <th style="text-align: center;">Processor&nbsp;2</th>
                        <th style="text-align: center;">Core&nbsp;2</th>
                        <th style="text-align: center;">Speed</th>
                        <th style="text-align: center;">Generation</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-1</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-2</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-3</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-4</th>
                        <th style="text-align: center;">RAM&nbsp;Type</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-1</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-2</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-3</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-4</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-5</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-6</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-7</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-8</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-9</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-10</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-11</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-12</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-13</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-14</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-15</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-16</th>
                        <th style="text-align: center;">PRI&nbsp;Card</th>
                        <th style="text-align: center;">Port</th>
                        <th style="text-align: center;">PRI</th>
                        <th style="text-align: center;">Software</th>
                        <th style="text-align: center;">Software&nbsp;Type</th>
                        <th style="text-align: center;">OS</th>
                        <th style="text-align: center;">Local&nbsp;IP-1</th>
                        <th style="text-align: center;">Local&nbsp;IP-2</th>
                        <th style="text-align: center;">Local&nbsp;IP-3</th>
                        <th style="text-align: center;">Static&nbsp;IP</th>
                        <th style="text-align: center;">Process</th>
                        <th style="text-align: center;">Agents</th>
                        <th style="text-align: center;">Total&nbsp;Agents</th>
                        <th style="text-align: center;">Vendor&nbsp;Name</th>
                        <th style="text-align: center;">Install&nbsp;Date</th>
                        <th style="text-align: center;">Rent&nbsp;Amount</th>
                       <th style="text-align: center;">Create&nbsp;Date</th>
                       <th style="text-align: center;">Create&nbsp;By</th>
                       <th style="text-align: center;">Update&nbsp;Date</th>
                       <th style="text-align: center;">Update&nbsp;By</th>
                        </tr>
                    <?php 
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=export.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    foreach($data as $row){
                    ?> 
                    <tr>
                    <td><?php echo $row['AssetsManagement']['ServerName']?></td>
                    <td><?php echo $row['AssetsManagement']['Branch']?></td>
                    <td><?php echo $row['AssetsManagement']['Location']?></td>
                    <td><?php echo $row['AssetsManagement']['Brand']?></td>
                    <td><?php echo $row['AssetsManagement']['MotherBoard']?></td>
                    <td><?php echo $row['AssetsManagement']['Processor1']?></td>
                    <td><?php echo $row['AssetsManagement']['Core1']?></td>
                    <td><?php echo $row['AssetsManagement']['Processor2']?></td>
                    <td><?php echo $row['AssetsManagement']['Core2']?></td>
                    <td><?php echo $row['AssetsManagement']['Speed']?></td>
                    <td><?php echo $row['AssetsManagement']['Generation']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk1']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk2']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk3']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk4']?></td>
                    <td><?php echo $row['AssetsManagement']['RamType']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot1']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot2']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot3']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot4']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot5']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot6']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot7']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot8']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot9']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot10']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot11']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot12']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot13']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot14']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot15']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot16']?></td>
                    <td><?php echo $row['AssetsManagement']['PriCard']?></td>
                    <td><?php echo $row['AssetsManagement']['Port']?></td>
                    <td><?php echo $row['AssetsManagement']['ConnectedPri']?></td>
                    <td><?php echo $row['AssetsManagement']['Software']?></td>
                    <td><?php echo $row['AssetsManagement']['SoftwareType']?></td>
                    <td><?php echo $row['AssetsManagement']['OS']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp1']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp2']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp3']?></td>
                    <td><?php echo $row['AssetsManagement']['StaticIp']?></td>
                    <td><?php foreach(explode(",", $row['AssetsManagement']['Process']) as $process){ echo $process."<br/>";}?></td>
                    <td><?php foreach(explode(",", $row['AssetsManagement']['Agents']) as $agents){ echo $agents."<br/>";}?></td>
                    <td><?php echo $row['AssetsManagement']['TotalAgents']?></td>
                    <td><?php echo $row['AssetsManagement']['VendorName']?></td>
                    <td><?php echo $row['AssetsManagement']['InstallationDate'] !=""?'=TEXT("'.$row['AssetsManagement']['InstallationDate'].'","dd-mm-YYYY")':"";?></td>
                    <td><?php echo $row['AssetsManagement']['RentAmount']?></td>
                    <td><?php echo $row['AssetsManagement']['CreateDate'] !=""?'=TEXT("'.$row['AssetsManagement']['CreateDate'].'","dd-mm-YYYY")':"";?></td>
                    <td><?php echo $row['AssetsManagement']['CreateBy']?></td>  
                    <td><?php echo $row['AssetsManagement']['UpdateDate'] !=""?'=TEXT("'.$row['AssetsManagement']['UpdateDate'].'","dd-mm-YYYY")':"";?></td>
                    <td><?php echo $row['AssetsManagement']['UpdateBy']?></td>
                    </tr>
                    <?php }?>
                </table>
        <?php
        die;
    }
    
    
    
    public function view(){
        $this->layout='home';
    }
    
    
    
}
?>