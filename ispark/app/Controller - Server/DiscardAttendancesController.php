<?php
class DiscardAttendancesController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','FieldAttendanceMaster','OnSiteAttendanceMaster','Masattandance','ProcessAttendanceMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','delete_attendance','total_employees','process_status','getdiscard');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $ProcessDate=date('Y', strtotime(date('Y-m')." -1 month"))."-".date('m', strtotime(date('Y-m')." -1 month"));
        
        $fieldArr=array();
        $data = $this->ProcessAttendanceMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ProcessMonth'=>$ProcessDate),'group' =>array('CostCenter')));
        foreach($data as $val){
            $fieldArr[]=array(
                'Id'=>$val['ProcessAttendanceMaster']['Id'],
                'CostCenter'=>$val['ProcessAttendanceMaster']['CostCenter'],
                'Status'=>$this->process_status($val['ProcessAttendanceMaster']['CostCenter'],$branchName),
                'TotalEmp'=>$this->total_employees($val['ProcessAttendanceMaster']['CostCenter'],$branchName)
            );
        }
        $this->set('fieldArr',$fieldArr);
        
        if($this->request->is('Post')){
            $CostCenter=$this->request->data['CostCenter'];
            $Id=$this->request->data['Id'];
            $status="Yes";
            $dataArr=array(
                'FinializeStatus'=>"'".$status."'",
                'FinializeDate'=>"'".date('Y-m-d H:i:s')."'",
            );
             
            if($this->ProcessAttendanceMaster->updateAll($dataArr,array('Id'=>$Id))){
                $this->Session->setFlash('<span style="color:green;" >Your attendance Finalize successfully.</span>');
            }
            else{
                $this->Session->setFlash('<span style="color:red;" >Your attendance not Finalize please try again later.</span>');
            }
            $this->redirect(array('controller'=>'FinalizeAttendances','action'=>'index'));     
        }
        
        
    }
    
    public function getdiscard(){
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            $branchName =   $_REQUEST['BranchName'];
            $ProcessDate=date('Y', strtotime(date('Y-m')." -1 month"))."-".date('m', strtotime(date('Y-m')." -1 month"));
            $fieldArr=array();
            $data = $this->ProcessAttendanceMaster->find('all',array('conditions'=>array('BranchName'=>$branchName,'ProcessMonth'=>$ProcessDate),'group' =>array('CostCenter')));
            foreach($data as $val){
                $fieldArr[]=array(
                    'Id'=>$val['ProcessAttendanceMaster']['Id'],
                    'CostCenter'=>$val['ProcessAttendanceMaster']['CostCenter'],
                    'Status'=>$this->process_status($val['ProcessAttendanceMaster']['CostCenter'],$branchName),
                    'TotalEmp'=>$this->total_employees($val['ProcessAttendanceMaster']['CostCenter'],$branchName)
                );
            }
            ?>
            <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" >         
                <thead>
                    <tr>                	
                        <th style="width: 30px;">SNo</th>
                        <th style="text-align: center; ">Cost Center</th>
                        <th style="text-align: center; width:150px;" >Total Employee</th>
                        <th style="text-align: center;width:100px;" >Status</th>
                        <th style="text-align: center;width: 40px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total=0;
                    $i=1; foreach ($fieldArr as $val){
                    $total=$total+$val['TotalEmp'];
                    $cosc = base64_encode($val['CostCenter']);
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $val['CostCenter'];?></td>
                        <td style="text-align: center;"><?php echo $val['TotalEmp'];?></td>
                        <?php if($val['Status'] > 0){?>
                        <td style="text-align: center;color: green;"><?php echo "FINALIZE"?></td>
                        <?php }else{?>
                        <td style="text-align: center;color: red;"><?php echo "PROCESS";?></td>
                        <?php }?>
                        <td style="text-align: center;" ><i onclick="deleteProcess('<?php echo $val['Id'];?>');" style="cursor:pointer;" class="material-icons">delete_forever</i></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td style="text-align: center;font-weight: bold;"><?php echo $total;?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>           
            </table>
            <?php
            die;
        }
    }
    
    public function delete_attendance(){
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $this->Masjclrentry->query("DELETE FROM `ProcessAttendanceMaster` WHERE Id='{$_REQUEST['Id']}'");
            $this->Session->setFlash('<span style="color:green;" >Your record delete successfully.</span>');
            $this->redirect(array('controller'=>'DiscardAttendances','action'=>'index'));    
        }
        
    }
     
    public function total_employees($CostCenter,$branchName){
        return $this->Masjclrentry->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'Status'=>1)));
    }
    
    public function process_status($CostCenter,$branchName){
        $my=date('Y-m', strtotime(date('Y-m')." -1 month"));
        $y=date('Y');
        return $this->ProcessAttendanceMaster->find('count', array('conditions' => array('BranchName'=>$branchName,'CostCenter'=>$CostCenter,'ProcessMonth'=>"$my",'FinializeStatus'=>'Yes')));
    }    
}
?>