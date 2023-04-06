<?php
class ReportsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','CallMaster','vicidialCloserLog','vicidialUserLog','ClientReportMaster','AbandCallMaster');
	
    public function beforeFilter(){
        parent::beforeFilter();
	$this->Auth->allow('index','view_report');
	if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }

    public function index(){
        $this->layout = "user";
        if($this->request->is('Post')){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=CallTaggingSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
            $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
            <table border="1">
                <tr>
                    <th>CLIENT NAME</th>
                    <th>TOTAL ANSWER</th>
                    <th>TOTAL ABAND</th>
                    <th>TOTAL TAGGED</th>
                    <th>ABAND CALL BACK</th>
                </tr>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $bak=0;

                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and campaign_id in ('')";
                    }

                    
                    $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`,
                    SUM(If(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='A' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'");
                    $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus IS NULL");
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND CallType !='Upload'");
                    $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus='yes'");
         
                    $AnsData=$dt[0][0]['Answered'];
                    $AbnData=$TACC[0][0]['AbandCount'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $abn=$abn+$AbnData;
                    $tag=$tag+$totalTag;
                    $bak=$bak+$BakData;
                    
                ?>	
                    <tr>
                        <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                        <td><?php echo $AnsData;?></td>
                        <td><?php echo $AbnData;?></td>
                        <td><?php echo $totalTag; ?></td>
                        <td><?php echo $BakData; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo $abn;?></td>
                    <td><?php echo $tag;?></td>
                    <td><?php echo $bak;?></td>
                </tr>
            </table>
            <?php
            exit;
        }
    }
    
    public function view_report(){
        $this->layout = "ajax";
        if($_REQUEST['fdate']){
            
            $fdate=$_REQUEST['fdate'];
            $ldate=$_REQUEST['ldate'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
            $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
             <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>CLIENT NAME</th>
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>ABAND CALL BACK</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $bak=0;
                
                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and campaign_id in ('')";
                    }

                    $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`,
                    SUM(If(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='A' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'");
                    $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus IS NULL");
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND CallType !='Upload'");
                    $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus='yes'");
         
                    $AnsData=$dt[0][0]['Answered'];
                    $AbnData=$TACC[0][0]['AbandCount'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $abn=$abn+$AbnData;
                    $tag=$tag+$totalTag;
                    $bak=$bak+$BakData;
                ?>
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $AbnData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $BakData; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo $abn;?></td>
                    <td><?php echo $tag;?></td>
                    <td><?php echo $bak;?></td>
                </tr>
                </tbody>                    
            </table>
            <?php
            exit;
        }
    }		
}
?>