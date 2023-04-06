<?php
class AutomailMastersController extends AppController {
    public $uses = array('AssetsManagement','Addbranch','Masjclrentry','Masattandance','LeaveManagementMaster','LeaveRightsMaster','Tbl_bgt_expenseheadingmaster','AutomailGrnpaymentMaster');
        
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
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
			$this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }  
		
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $data = $this->AutomailGrnpaymentMaster->find('all',array('order'=>array('Branch_Name')));
        }
        else{
            $data = $this->AutomailGrnpaymentMaster->find('all',array('conditions'=>array('Branch_Name'=>$branchName),'order'=>array('Branch_Name')));
        }
        
        $this->set('data',$data);
		
		
		$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>"EntryBy=''",'order'=>array('HeadingDesc'=>'asc'))));

        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $RowData = $this->AutomailGrnpaymentMaster->find('first',array('conditions'=>array('Id'=>$_REQUEST['Id']),'order'=>array('Branch_Name')));
            $this->set('RowData',$RowData['AutomailGrnpaymentMaster']);
        }
       
        if($this->request->is('Post')){
            $CreateBy               =   $this->Session->read('email');
            $dataArr                =   $this->request->data['AutomailMasters'];
            $dataArr['Create_By']   =   $CreateBy;
			         
            $checkExist = $this->AutomailGrnpaymentMaster->find('first',array('conditions'=>array('Branch_Name'=>$dataArr['Branch_Name'],'Bill_Type'=>$dataArr['Bill_Type'])));
            if(!empty($checkExist)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This bill type entry already exist in database.</span>');
                $this->redirect(array('action'=>'index'));
            }
            else{
                $this->AutomailGrnpaymentMaster->saveAll($dataArr);
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your auto mail details save successfully.</span>');
                $this->redirect(array('action'=>'index'));
            }
        } 
    }
	
    public function update(){
        $this->layout='home';
        if($this->request->is('Post')){
            $UpdateBy               	=   $this->Session->read('email');
            $dataArr                	=   $this->request->data['AutomailMasters'];
            $dataArr['Update_Date']  	=   date('Y-m-d');
            $dataArr['Update_By']    	=   $UpdateBy;
            $Id                     	=   $dataArr['Id'];
			
			

            $dataX = array();
            unset($dataArr['Id']);
            foreach($dataArr as $k=>$v){
                $dataX[$k] = "'".$v."'";
            }
            
            
            $checkExist = $this->AutomailGrnpaymentMaster->find('first',array('conditions'=>array('Branch_Name'=>$dataArr['Branch_Name'],'Bill_Type'=>$dataArr['Bill_Type'],'Id !='=>$Id)));
            if(!empty($checkExist)){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This bill type entry already exist in database.</span>');
                $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
            }
            else{
                $this->AutomailGrnpaymentMaster->updateAll($dataX,array('Id'=>$Id));
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your auto mail details update successfully.</span>');
                $this->redirect(array('action'=>'index','?'=>array('Id'=>$Id)));
            }
              
        }   
    }
    
	
    public function delete(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $Id=$_REQUEST['Id'];
            $this->AutomailGrnpaymentMaster->query("DELETE FROM `Automail_Grnpayment_Master` WHERE Id='$Id'");
            $this->redirect(array('action'=>'index')); 
        }
    }
    
	
	/*
    public function export(){
        $this->layout='home';
        
        $data = $this->AutomailGrnpaymentMaster->find('all',array('order'=>array('Branch_Name')));
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
    */
    
    
    public function view(){
        $this->layout='home';
    }
    
    
    
}
?>