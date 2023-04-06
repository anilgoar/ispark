<?php
class BranchEmployeeMovementsController extends AppController {
    public $uses=array('Masjclrentry','Addbranch','CostCenterMaster','User','EmployeeMove','Masattandance','InactiveBioCode','Investmentmove');

    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','get_cost_center','get_emails','deactive_biocode','edit_investment','get_emp_for_investment','view_edit_investment','save_details','edit_detailsRent','update_details');
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
                    
                    $MaxAttandDateArray = $this->Masattandance->query("SELECT max(date(AttandDate)) AttandDate from Attandence");
                    $MaxAttandDate      = $MaxAttandDateArray[0][0]['AttandDate'];
                    
                    $SearchDetailsQr = "SELECT * from Attandence Att where BranchName= '$Branch' and date(AttandDate)='$MaxAttandDate' and EmpCode is null group by BioCode";
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
            if($Branch =="ALL"){
            //if(empty($Branch)){
                $query = "";
            }
            else
            {
                $query = "And BranchName='$Branch'";
            }
            $EmpArr = $this->Masjclrentry->query("SELECT Masjclrentry.EmpCode,Astatus,Masjclrentry.EmpName,SUM(if(investYear='$investYear',InComeOnHousing+InterestOnBorrowedCapital,0))Section24 FROM masjclrentry Masjclrentry
LEFT JOIN 
`investmentmoves` inv ON Masjclrentry.EmpCode = inv.EmpCode where 1=1 $query and Gross>='22000' AND (EmpType = 'ONROLL' OR Emptype ='OnRoll') GROUP BY Masjclrentry.EmpCode");
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
    <td><?php echo $SD['0']['Section24'];?></td>
    <td><?php echo $SD['0']['Section24'];?></td>
    <td align="center"><input type="checkbox" name="AprInvest<?php echo $SD['Masjclrentry']['EmpCode'] ?>" id="AprInvest<?php echo $SD['Masjclrentry']['EmpCode'] ?>" value="1" <?php if($SD['inv']['Astatus']==1){ echo 'checked'; } ?>/></td>
    <td>
        <a href="#" onclick="Rdircr('AprInvest<?php echo $SD['Masjclrentry']['EmpCode'] ?>','<?php echo $SD['Masjclrentry']['EmpCode'] ?>')">Details</a>
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
               $date=date('Y');
               $Ndate=$date+1;
               $investYear= $date.'-'.$Ndate;
               
                $branchName = $this->Session->read('branch_name');
                if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
                    $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name')));            
                    $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
                }
                else{
                    $this->set('branchName',array($branchName=>$branchName)); 
                }
               
               
               //$this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order' => array('branch_name' => 'asc'))));
               
               
               
               
               
               $data = $this->Masjclrentry->query("SELECT Masjclrentry.EmpCode,Masjclrentry.EmpName,Astatus,SUM(if(investYear='$investYear',InComeOnHousing+InterestOnBorrowedCapital,0))Section24 FROM masjclrentry Masjclrentry
LEFT JOIN 
`investmentmoves` inv ON Masjclrentry.EmpCode = inv.EmpCode
 WHERE Gross>='22000' AND BranchName='$branchName' AND (EmpType = 'ONROLL' OR Emptype ='OnRoll')  group by Masjclrentry.EmpCode" );
               $this->set('data',$data);
               
               if ($this->request->is('post')) 
               {
                   
               }
            }
            public function view_edit_investment()
            {
               $this->layout="home";
               $EmpCode = $this->params->query['EmpCode'];
               $Id = $this->params->query['Id'];
               $date=date('Y');
               $Ndate=$date+1;
               $investYear= $date.'-'.$Ndate;
               $this->set('investYear',$investYear);
               $this->set('EmpCode',$EmpCode);
               $this->set('Id',$Id);
               $this->set('dataArr', $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where EmpCode='$EmpCode' and investYear='$investYear'"));
               $this->set('data',$this->Masjclrentry->query("Select * from masjclrentry Masjclrentry where EmpCode='$EmpCode' "));
               
              $check=$this->Masjclrentry->query("Select * from  
`investmentmoves` inv 
 WHERE EmpCode='$EmpCode' and investYear='$investYear'");
               $this->set('data1',$check);
               if ($this->request->is('post')) 
               {
                   $get=$this->request->data;
                   $total=$get['Tatol'];
                   $get=HASH::Remove($get,'Tatol');
                   $get=HASH::Remove($get,'Submit');
                   $data['Investmentmove'] = HASH::Remove($get,'Submit');
                   //print_r($get);die;
                   if(!empty($check)){
                       
                       if($check[0]['inv']['AStatus']==1)
                       {
                           if($get['AStatus']==''){
                           $upstatus =$this->Masjclrentry->query("update  
`investmentmoves` set  AStatus = 0
 WHERE EmpCode='$EmpCode' and investYear='$investYear'");
                           $this->Session->setFlash(__("Now Employee Investment Approve Status Update."));
                        
                       $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'edit_investment'));
                           }
                           else{
                               $this->Session->setFlash(__("Employee Investment Declaration  Allready Approve."));
                        
                       $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'edit_investment')); 
                           }
                       }
                       else{
                    foreach ($get as $k=>$v)
                {
                    $ArrayData[$k]="'".$v."'";
                }
                  if($this->Investmentmove->updateAll(
  $ArrayData ,
    array('EmpCode' => $EmpCode)
                   )){
                      $this->Session->setFlash(__("Employee Investment Updates"));
                        
                       $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'edit_investment')); 
                  }
                   }
                   }
                     else if($this->Investmentmove->save($data))
                    {
                        $this->Session->setFlash(__("Employee Investment Saved"));
                        
                       $this->redirect(array('controller'=>'BranchEmployeeMovements','action'=>'edit_investment'));
                    }
                   
                       //print_r($data);die;
                    
               
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
                   $date=date('Y');
               $Ndate=$date+1;
               $investYear= $date.'-'.$Ndate;              
                $ins = "insert into investment_other set EmpCode='$EmpCode',FromDate='$FromDate',ToDate='$ToDate',RentPerMonth='$RentPerMonth',Address='$Address',City='$City',LandLordName='$LandLordName',LandLordPanNo='$LandLordPanNo',investYear='$investYear'";
                $this->Investmentmove->query($ins);
                
                $dataArr = $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where EmpCode='$EmpCode' and investYear='$investYear'");
                echo '<table class = "table table-striped table-hover  responstable" style="font-size: 13px;"><thead><tr>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Rent Per Month</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Landlord Name</th>
                        <th>Landlord Pan No</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr></thead>';
                $i=1;
                foreach($dataArr as $data)
                {
                    $id = 'id'.$i;
                    echo "<tr id ='".$id."'>";
                        echo "<td>".$data['io']['FromDate'].'</td>';
                        echo "<td>".$data['io']['ToDate'].'</td>';
                        echo "<td>".$data['io']['RentPerMonth'].'</td>';
                        echo "<td>".$data['io']['Address'].'</td>';
                        echo "<td>".$data['io']['City'].'</td>';
                        echo "<td>".$data['io']['LandLordName'].'</td>';
                        echo "<td>".$data['io']['LandLordPanNo'].'</td>';
                        echo "<td>".($data['io']['RentPerMonth']*$data['0']['Total']).'</td>';
                         ?>
                         <td><div class="btn btn-info" onclick="Jajax('<?php echo $id ?>','<?php echo $data['io']['Id'] ?>')">Edit</div></td>
                         <?php
                    echo "</tr>";  $i++;
                }
                echo "</table>";
                exit;
            }
            
             public function edit_detailsRent()
            {
                $data = $this->request->data;
                
                $Id = $this->request->data['Id'];
               $DivId=$this->request->data['DivId'];
                                
               
                
                $dataArr = $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where Id='$Id'");
               
                foreach($dataArr as $data)
                {
                  
                    
                        echo "<td><input type=\"text\" name=\"FromDate\" id=\"FromDate1\" value=".$data['io']['FromDate']." class=\"datepik\" />".'</td>';
                        echo "<td><input type=\"text\" name=\"ToDate\" id=\"ToDate1\" value=".$data['io']['ToDate']." class=\"datepik\" />".'</td>';
                        echo "<td><input type=\"text\" name=\"RentPerMonth\" id=\"RentPerMonth1\" value=".$data['io']['RentPerMonth']." class=\"datepik\" onKeyPress=\"return checkNumber(this.value,event)\"/>".'</td>';
                        echo "<td><input type=\"text\" name=\"Address\" id=\"Address1\" value=".$data['io']['Address']." class=\"datepik\" />".'</td>';
                        echo "<td><input type=\"text\" name=\"City\" id=\"City1\" value=".$data['io']['City']." class=\"datepik\" />".'</td>';
                        echo "<td><input type=\"text\" name=\"LandLordName\" id=\"LandLordName1\" value=".$data['io']['LandLordName']." class=\"datepik\" />".'</td>';
                        echo "<td><input type=\"text\" name=\"LandLordPanNo\" id=\"LandLordPanNo1\" value=".$data['io']['LandLordPanNo']." class=\"datepik\" />".'</td>';
                       echo '<td></td>';
                         ?>
                         <td><div class="btn btn-info" onclick="checkval('<?php echo $DivId ?>','<?php echo $data['io']['Id'] ?>')">Upda</div></td>
                         <?php
                   
                }
                echo "</table>";
                exit;
            }
            
            
             public function update_details()
            {
                $data = $this->request->data;
                 $DivId=$this->request->data['DivId'];
                $Id = $this->request->data['ID'];
                $EmpCode = $this->request->data['EmpCode'];
                $FromDate = date('Y-m-d',strtotime($this->request->data['FromDate'])) ;
                $ToDate = date('Y-m-d',strtotime($this->request->data['ToDate'])) ;
                $RentPerMonth = $this->request->data['RentPerMonth'];
                $Address = $this->request->data['Address'];
                $City = $this->request->data['City'];
                $LandLordName = $this->request->data['LandLordName'];
                $LandLordPanNo = $this->request->data['LandLordPanNo'];
                         $date=date('Y');
               $Ndate=$date+1;
               $investYear= $date.'-'.$Ndate;         
                $ins = "update investment_other set EmpCode='$EmpCode',FromDate='$FromDate',ToDate='$ToDate',RentPerMonth='$RentPerMonth',Address='$Address',City='$City',LandLordName='$LandLordName',LandLordPanNo='$LandLordPanNo' where Id ='$Id' ";
                $this->Investmentmove->query($ins);
                
                $dataArr = $this->Investmentmove->query("Select *,floor(DATEDIFF(ToDate,FromDate)/30) Total from investment_other io Where Id='$Id'");
                
                foreach($dataArr as $data)
                {
                   
                        echo "<td>".$data['io']['FromDate'].'</td>';
                        echo "<td>".$data['io']['ToDate'].'</td>';
                        echo "<td>".$data['io']['RentPerMonth'].'</td>';
                        echo "<td>".$data['io']['Address'].'</td>';
                        echo "<td>".$data['io']['City'].'</td>';
                        echo "<td>".$data['io']['LandLordName'].'</td>';
                        echo "<td>".$data['io']['LandLordPanNo'].'</td>';
                        echo "<td>".($data['io']['RentPerMonth']*$data['0']['Total']).'</td>';
                         ?>
                         <td><div class="btn btn-info" onclick="Jajax('<?php echo $DivId ?>','<?php echo $data['io']['Id'] ?>')">Edit</div></td>
                         <?php
                   
                }
                echo "</table>";
                exit;
            }
}

?>