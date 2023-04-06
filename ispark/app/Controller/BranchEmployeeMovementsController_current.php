<?php
class BranchEmployeeMovementsController extends AppController {
    public $uses=array('Masjclrentry','Addbranch','CostCenterMaster','User','EmployeeMove','Masattandance','InactiveBioCode','Investmentmove');

    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','get_cost_center','get_emails','deactive_biocode','edit_investment','get_emp_for_investment','view_edit_investment','save_details');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
     
	public function get_cost_center()
        {
            $Branch = $this->request->data['Branch']; 
            $CostCenterArr = $this->CostCenterMaster->find('list',array('fields'=>"cost_center,process_name",'conditions'=>array('branch'=>$Branch)));
            foreach($CostCenterArr as $k=>$v)
            {
                //$list[$k] = "$v-$k";
                $list[$k] = "$k";
            }
            echo json_encode($list); exit;
        }
        
        public function get_emails()
        {
            $Branch = $this->request->data['Branch']; 
            //$UserArr = $this->User->find('list',array('fields'=>"username,username",'conditions'=>"branch_name='$Branch' and role!='admin' and username!='' and UserActive=1"));
            $UserArr = $this->User->find('list',array('fields'=>"username,username",'conditions'=>"role!='admin' and username!='' and UserActive=1 order by username"));
            echo json_encode($UserArr); exit;
        }
    	public function index() 
        {
            $this->layout='home';
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
            
            if($this->request->is('POST'))
            {
                $BranchMoveReqData = $this->request->data['BranchEmployeeMovements'];
                $EmpCode = $BranchMoveReqData['EmpCode'];
                if(!empty($this->request->data['submit']) && $this->request->data['submit']=='View')
                {
                    $SearchDetails = $this->Masjclrentry->query("SELECT * FROM `masjclrentry` Masjclrentry WHERE EmpCode='$EmpCode'");
                    $this->set('SearchDetails',$SearchDetails);
                }
                else if(!empty($this->request->data['submit']) && $this->request->data['submit']=='Move')
                {
                    $EmployeeMove['ToBranch'] =  $ToBranch = $BranchMoveReqData['Branch'];
                    $EmployeeMove['ToCostCenter'] = $cost_center = $BranchMoveReqData['cost_center'];
                    $EmployeeMove['MoveMonth'] = $month = $BranchMoveReqData['month'];
                    $EmployeeMove['Email'] = $email = $BranchMoveReqData['Email'];
                    $EmployeeMove['Reason'] = $reasion = $BranchMoveReqData['reason'];
                    $EmployeeMove['MoveBy'] = $MoveBy = $this->Session->read('userid');
                    $EmployeeMove['MoveDate'] = $MoveDate = date('Y-m-d H:i:s');
                    
                    if(empty($ToBranch) || empty($cost_center) || empty($month)  || empty($reasion))
                    {
                        $this->Session->setFlash("Fields Should not Be Blank");
                    }
                    else
                    {
                        $Transaction = $this->EmployeeMove->getDataSource();
                        $Transaction->begin();
                        
                        if($this->EmployeeMove->save($EmployeeMove))
                        {
                            if($month=='CM')
                            {
                                $monthYear =" and date_format(AttandDate,'%m-%Y')=date_format(curdate(),'%m-%Y')";
                            }
                            else
                            {
                                $monthYear =" and date_format(AttandDate,'%m-%Y')=date_format(subdate(curdate(),interval 1 month),'%m-%Y')";
                            }
                            
                            $UpdBranchMove = "Update masjclrentry set BranchName='$ToBranch',CostCenter='$cost_center' Where EmpCode='$EmpCode'";
                            $UpdAtten = "Update Attandence set BranchName='$ToBranch' Where EmpCode='$EmpCode' $monthYear";
                            $UpdBranchMoveRsc = $this->Masjclrentry->query($UpdBranchMove);
                            $UpAttendRsc = $this->Masattandance->query($UpdAtten);
                            
                            if($this->Masjclrentry->find('first',array('fields'=>'EmpCode',"conditions"=>"BranchName='$ToBranch' and CostCenter='$cost_center'")) &&
                               $this->Masattandance->find('first',array('fields'=>'EmpCode',"conditions"=>"BranchName='$ToBranch'")))
                            {
                                $Transaction->commit();
                                App::uses('sendEmail', 'custom/Email');
                                
                                $sub = "'New Initial Invoice - '.$b_name";
                                $mail = new sendEmail();
                                //$mail-> to($email,"Employee Moved To Branch","");
                                $this->Session->setFlash(__("Employee Moves To Branch $ToBranch In $cost_center"));
                                $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'index'));
                            }
                            else
                            {
                                $Transaction->rollback();
                                $this->Session->setFlash(__("Employee Not Updated $ToBranch In $cost_center"));
                            }
                        }
                        else
                        {
                            $this->Session->setFlash(__("Employee Not Moved. Please Try Again"));
                            $Transaction->rollback();
                        }
                    }
                }
                
            }
            
        }
        
	  public function deactive_biocode() 
          {
            $this->layout='home';
            $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
            
            if($this->request->is('POST'))
            {
                $BranchMoveReqData = $this->request->data['BranchEmployeeMovements'];
                $EmpCode = $BranchMoveReqData['EmpCode'];
                $NotBatch = $this->request->data['NotBatch'];
                
                if(!empty($this->request->data['submit']) && $this->request->data['submit']=='View')
                {
                    
                    $Branch = $this->request->data['BranchEmployeeMovements']['Branch'];
                    $SearchDetailsQr = "SELECT * from Attandence Att where BranchName= '$Branch' and EmpCode is null and PendingStatus = 0 and  EmpStatus = 'InHouse'  group by BioCode";
                    $SearchDetails = $this->Masjclrentry->query($SearchDetailsQr);
                     
                    $this->set('SearchDetails',$SearchDetails);
                }
                else if(!empty($this->request->data['submit']) && $this->request->data['submit']=='Delete')
                {
                    $checkArr = $this->request->data['check'];
                    
                    
                    foreach($checkArr as $BioCode)
                    {
                        $EmployeeMove = array();
                        $EmployeeMove['Reason'] = $reasion = $BranchMoveReqData['Remarks'];
                        $EmployeeMove['BioCode'] =$BioCode;
                        $EmployeeMove['DeactiveBy'] = $this->Session->read('userid');
                        $EmployeeMove['DeactiveDate'] = date('Y-m-d H:i:s');
                        $EmployeeMove['SaveByEmail'] = $this->Session->read('email');
                        $BioCodeDeactive[]['InactiveBioCode'] = $EmployeeMove;
                        
                        $this->Masjclrentry->query("delete  from Attandence where BioCode='$BioCode'");
                    }
                    
                    $Transaction = $this->InactiveBioCode->getDataSource();
                    $Transaction->begin();

                    if($this->InactiveBioCode->saveAll($BioCodeDeactive))
                    {
                        $this->Session->setFlash(__("Employee BioCode Deactivated"));
                        $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'deactive_biocode'));
                    }
                    else
                    {
                        $this->Session->setFlash(__("Employee BioCode Not Deleted. Please Try Again?"));
                        $Transaction->rollback();
                    }
                    
                }
                
            }
            
        } 
        
        public function get_emp_for_investment()
        {
            $Branch = $this->request->data['Branch']; 
            $EmpArr = $this->Masjclrentry->query("Select EmpCode,EmpName from masjclrentry Masjclrentry where BranchName='$Branch' and Gross>='22000'");
            ?>
            <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
<thead>      
    <tr>
    <th>EmpCode</th>
    <th>EmpName</th>
    <th>Investment Under Section24</th>
    <th>Investment Under Chapter6</th>
    <th><input type="checkbox" name="all" id="all" value="all" /> Approved/Not Approved</th>
    <th>Detail</th>
    </tr>
    </thead> 
     <tbody>  
         
<?php
    foreach($EmpArr as $SD)
    {  
?>
 <tr>
    <td><?php echo $SD['Masjclrentry']['EmpCode'];?></td>
    <td><?php echo $SD['Masjclrentry']['EmpName'];?></td>
    <td></td>
    <td></td>
    <td align="center"><input type="checkbox" name="AprInvest" id="AprInvest" value="" /></td>
    <td>
        <a href="view_edit_investment">Details</a>
    </td>
</tr>   
    
<?php
    }
?>
     </tbody>
    </table>
        <?php exit; }
        
	   public function edit_investment() 
	   {
               $this->layout="home";
               $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
               $data = $this->Masjclrentry->query("SELECT Masjclrentry.EmpCode,Masjclrentry.EmpName,SUM(InComeOnHousing+InterestOnBorrowedCapital)Section24 FROM masjclrentry Masjclrentry
LEFT JOIN 
`investmentmoves` inv ON Masjclrentry.EmpCode = inv.EmpCode
 WHERE Gross>='22000' ");
               $this->set('data',$data);
               
               if ($this->request->is('post')) 
               {
                   
               }
            }
            public function view_edit_investment()
            {
               $this->layout="home";
               $EmpCode = $this->params->query['EmpCode'];
               $this->set('EmpCode',$EmpCode);
               $this->set('dataArr', $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where EmpCode='$EmpCode'"));
               $this->set('data',$this->Masjclrentry->query("Select * from masjclrentry Masjclrentry where EmpCode='$EmpCode'"));
               if ($this->request->is('post')) 
               {
                   $get=$this->request->data;
                   
                   $data['Investmentmove'] = HASH::Remove($get,'Submit');
                       
                    if($this->Investmentmove->save($data))
                    {
                        $this->Session->setFlash(__("Employee Investment Saved"));
                        
                       $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'view_edit_investment'));
                    }
               }
            }
            
            public function save_details()
            {
                $data = $this->request->data;
                $EmpCode = $this->request->data['EmpCode'];
                $FromDate = date('Y-m-d',strtotime($this->request->data['FromDate'])) ;
                $ToDate = date('Y-m-d',strtotime($this->request->data['ToDate'])) ;
                $RentPerMonth = $this->request->data['RentPerMonth'];
                $Address = $this->request->data['Address'];
                $City = $this->request->data['City'];
                $LandLordName = $this->request->data['LandLordName'];
                $LandLordPanNo = $this->request->data['LandLordPanNo'];
                                
                $ins = "insert into investment_other set EmpCode='$EmpCode',FromDate='$FromDate',ToDate='$ToDate',RentPerMonth='$RentPerMonth',Address='$Address',City='$City',LandLordName='$LandLordName',LandLordPanNo='$LandLordPanNo'";
                $this->Investmentmove->query($ins);
                
                $dataArr = $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where EmpCode='$EmpCode'");
                echo '<table class = "table table-striped table-hover  responstable" style="font-size: 13px;"><thead><tr>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Rent Per Month</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Landlord Name</th>
                        <th>Landlord Pan No</th>
                        <th>Total</th>
                    </tr></thead>';
                
                foreach($dataArr as $data)
                {
                    echo "<tr>";
                        echo "<td>".$data['io']['FromDate'].'</td>';
                        echo "<td>".$data['io']['ToDate'].'</td>';
                        echo "<td>".$data['io']['RentPerMonth'].'</td>';
                        echo "<td>".$data['io']['Address'].'</td>';
                        echo "<td>".$data['io']['City'].'</td>';
                        echo "<td>".$data['io']['LandLordName'].'</td>';
                        echo "<td>".$data['io']['LandLordPanNo'].'</td>';
                        echo "<td>".($data['io']['RentPerMonth']*$data['0']['Total']).'</td>';
                    echo "</tr>";
                }
                echo "</table>";
                exit;
            }
}

?>