<?php
 class GRNsController extends AppController{
    public $uses=array('Tbl_bgt_expenseheadingmaster','Addbranch','Tbl_bgt_expensesubheadingmaster');
    public $components = array('RequestHandler');
    		
    public function beforeFilter(){
        parent::beforeFilter();
		$this->Auth->Allow('index','getexpencesub','setExpenseSub','setGRNtypeDesign','setExpenseUnit','setCostCenter','saveData','downloadData');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
		
	public function index(){
		$this->layout='home';
		$QueryResult=$this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_tempexpensedetails Where SaveBy = '".$this->Session->check("username")."'");
		$this->set('expenseheadingmaster',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))); 
      	$branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin'){
            $this->set('branchName',$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'))));
        }
        else if(count($branchName)>1){
            foreach($branchName as $b):
            	$branch[$b] = $b; 
            endforeach;
            $branchName = $branch;
            $this->set('branchName',$branchName);
            unset($branch);
			unset($branchName);
        }
	}

        // Using ctp file
	public function getexpencesub(){
		$this->layout='ajax';
		$ExpenseHead = $this->request->data['ExpenseHead'];
		$data=$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$ExpenseHead)));
                $this->set('ExpenseSubHeading',$data);
	}
        
        public function setExpenseSub(){
		$this->layout='ajax';
                $QueryResult = "";
                $MasterSrNo = "";
                $BusinessCaseStatus = "";
		$Branch = $this->request->data['branch'];
		$Month = $this->request->data['Month'];
		$Year = $this->request->data['Year'];
		$ExpenseHead = $this->request->data['ExpenseHead'];
		$ExpenseSubHead = $this->request->data['ExpenseSubHead'];
                $GRNStatusData=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT CONVERT(IFNULL(CASE WHEN BCM.ApprovedAmount IS NULL THEN BCM.Amount ELSE BCM.ApprovedAmount END,0),DECIMAL(18,2)) ApprovedAmount, SUM(CONVERT(IFNULL(IM.OUTFLOW,'0'),DECIMAL(18,2))) Consumed, IFNULL(CASE WHEN BCM.ApprovedAmount IS NULL THEN BCM.Amount ELSE BCM.ApprovedAmount  END,0)-SUM(CONVERT(IFNULL(IM.OUTFLOW,0),DECIMAL(18,2)))Balance,BCM.Status Status FROM tbl_businesscasemaster BCM LEFT JOIN tbl_bgt_expenseheadingmaster EM ON BCM.ExpenseHead=EM.HeadingId LEFT JOIN imprest_master IM ON BCM.ExpenseHead=IM.ExpenseHead AND BCM.ExpenseSubHead=IM.ExpenseSubHead  AND BCM.Branch=IM.Branch AND IM.Month = '$Month' AND IM.Year = '$Year' WHERE BCM.ExpenseHead='$ExpenseHead' AND BCM.ExpenseSubHead='$ExpenseSubHead' AND BCM.Branch='$Branch' AND BCM.Month='$Month' AND BCM.Year='$Year' AND Level3ApprovalBy IS NOT NULL AND BCM.Status <> 'Closed' /*and Date(ifnull(Dated, '01-Apr-2014'))>='01-Apr-2014' */ GROUP BY BCM.Amount,BCM.ApprovedAmount,BCM.Status");
                //echo "SELECT CONVERT(IFNULL(CASE WHEN BCM.ApprovedAmount IS NULL THEN BCM.Amount ELSE BCM.ApprovedAmount END,0),DECIMAL(18,2)) ApprovedAmount, SUM(CONVERT(IFNULL(IM.OUTFLOW,'0'),DECIMAL(18,2))) Consumed, IFNULL(CASE WHEN BCM.ApprovedAmount IS NULL THEN BCM.Amount ELSE BCM.ApprovedAmount  END,0)-SUM(CONVERT(IFNULL(IM.OUTFLOW,0),DECIMAL(18,2)))Balance,BCM.Status Status FROM tbl_businesscasemaster BCM LEFT JOIN tbl_bgt_expenseheadingmaster EM ON BCM.ExpenseHead=EM.HeadingId LEFT JOIN imprest_master IM ON BCM.ExpenseHead=IM.ExpenseHead AND BCM.ExpenseSubHead=IM.ExpenseSubHead  AND BCM.Branch=IM.Branch AND IM.Month = '$Month' AND IM.Year = '$Year' WHERE BCM.ExpenseHead='$ExpenseHead' AND BCM.ExpenseSubHead='$ExpenseSubHead' AND BCM.Branch='$Branch' AND BCM.Month='$Month' AND BCM.Year='$Year' AND Level3ApprovalBy IS NOT NULL AND BCM.Status <> 'Closed' /*and Date(ifnull(Dated, '01-Apr-2014'))>='01-Apr-2014' */ GROUP BY BCM.Amount,BCM.ApprovedAmount,BCM.Status";
                if(array_key_exists('0',$GRNStatusData)){
                $BusinessCaseStatus = "Business Case Found";
		$MasterSrNo=$this->Tbl_bgt_expensesubheadingmaster->query("Select SrNo from tbl_expensemaster Where Branch='$Branch' and Year='$Year' and  Month='$Month'");
                $QueryResult = $this->Tbl_bgt_expensesubheadingmaster->query("Delete from tbl_tempexpensedetails  where ExpenseHeadId = '$ExpenseHead' and ExpenseSubHeadId = '$ExpenseSubHead' and MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."'");

                echo "<div class='form-group has-success has-feedback'>";
                echo "<label class='col-sm-3 control-label' id='BusinessCaseStatus'>Approved Amount = </label>";
                echo "<label class='col-sm-3 control-label' id='ApprovedAmount'>".$GRNStatusData[0][0]['ApprovedAmount']."</label>";
                echo "<label class='col-sm-3 control-label'>Consumed Amount = </label>"; 
                echo "<label class='col-sm-3 control-label' id='ConsumedAmount'> ".$GRNStatusData[0][0]['Consumed']."</label>"; 
                echo "</div";
                echo "<div class='form-group has-success has-feedback'>";
                echo "<label class='col-sm-3 control-label'></label>";
                echo "<label class='col-sm-3 control-label'></label>";
                echo "<label class='col-sm-3 control-label'>Balance Amount = </label>";
                echo "<label class='col-sm-3 control-label' id='BalanceAmount'>".$GRNStatusData[0][0]['Balance']."</label>";


                echo "<input type='hidden' name='data[GRN[GRNBusinessCaseStatus]' id= 'GRNBusinessCaseStatus' value='".$BusinessCaseStatus."'>";
                echo "<input type='hidden' name='data[GRN[GRNApprovedAmount]' id= 'GRNApprovedAmount' value = '".$GRNStatusData[0][0]['ApprovedAmount']."'>";
                echo "<input type='hidden' name='data[GRN[GRNConsumedAmount]' id= 'GRNConsumedAmount' value = '".$GRNStatusData[0][0]['Consumed']."'>";
                echo "<input type='hidden' name='data[GRN[GRNBalanceAmount]' id= 'GRNBalanceAmount' value = '".$GRNStatusData[0][0]['Balance']."'>";

                }
                else{
                $BusinessCaseStatus = "No Business Case Found";
                echo "<div class='form-group has-success has-feedback'>";
                echo "<label class='col-sm-3 control-label' style='color:Red' id='BusinessCaseStatus'>No Business Case Found</label>";
                echo "";
                }
                echo "</div";
                
                die;
        }

        // Not using ctp file        
        public function setGRNtypeDesign(){
		$this->layout='ajax';
                
		$Branch = $this->request->data['branch'];
            	$GRNtype = $this->request->data['GRNtype'];
		$GRNVendors=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT SrNo,VendorName FROM tbl_vendormaster WHERE Branch = '$Branch' and Status = 1");
                foreach($GRNVendors as $row){
                    echo "<option value='".$row['tbl_vendormaster']['SrNo']."'>".$row['tbl_vendormaster']['VendorName']."</option>";
                }
                die;
        }
        
        public function setExpenseUnit(){
  
		$this->layout='ajax';
                $QueryResult = "";
                $MasterSrNo = "";
		$Branch = $this->request->data['branch'];
		$Month = $this->request->data['Month'];
		$Year = $this->request->data['Year'];
		$ExpenseHead = $this->request->data['ExpenseHead'];
		$ExpenseSubHead = $this->request->data['ExpenseSubHead'];
		$ExpenseUnit=$this->Tbl_bgt_expensesubheadingmaster->query("Select SHT.SubHeadingTypeDesc ExpType from Tbl_Bgt_ExpenseSubHeadingTypeMaster SHT join Tbl_Bgt_ExpenseSubHeadingMaster ESHM on SHT.SubheadingTypeId=ESHM.SubHeadingTypeId Where SubHeadingId='$ExpenseSubHead'");
		$MasterSrNo=$this->Tbl_bgt_expensesubheadingmaster->query("Select SrNo from tbl_ExpenseMaster Where Branch='$Branch' and  Year='$Year' and  Month='$Month'");
                if(array_key_exists('0',$ExpenseUnit)){
                    
                    if(trim($ExpenseUnit[0]['SHT']['ExpType']) == "Utility"){
		$ExpenseSubSubHead=$this->Tbl_bgt_expensesubheadingmaster->query("Select ExpenseUnitId, ExpenseUnit,'0' as Amount, '0' as Percent from tbl_ExpenseUnitMaster  where HeadingId='$ExpenseHead' and SubHeadingId='$ExpenseSubHead' and Branch='$Branch' and Status = 1");
                if(array_key_exists('0',$ExpenseSubSubHead)){
                echo "<div class='form-group has-success has-feedback'>";
                echo "<label class='col-sm-6 control-label'>Expense Unit</label><label class='col-sm-6 control-label'>Amount</label></br>";
                foreach($ExpenseSubSubHead as $row){
                echo "<label class='col-sm-6 control-label' id= 'level_".$row['tbl_ExpenseUnitMaster']['ExpenseUnitId']."'> <input type='radio' name = 'css' onClick = SetCostCenter('".$row['tbl_ExpenseUnitMaster']['ExpenseUnitId']."');>".$row['tbl_ExpenseUnitMaster']['ExpenseUnit']."</label><input type='text' class='col-sm-6 control-label' name='data[GRN[GRN_".$row['tbl_ExpenseUnitMaster']['ExpenseUnitId']."]' id= 'GRN_".$row['tbl_ExpenseUnitMaster']['ExpenseUnitId']."' value='0'></br>";
                   }
                echo "<input type='hidden' name='data[GRN[GRNPrevUnit]' id= 'GRNPrevUnit' value=''>";
                echo "<input type='hidden' name='data[GRN[GRNCurrUnit]' id= 'GRNCurrUnit' value=''>";
                echo "</div>";
                }
             }
            }
            die;
            }
	
        public function setCostCenter(){
  
		$this->layout='ajax';
		$Branch = $this->request->data['branch'];
		$Month = $this->request->data['Month'];
		$Year = $this->request->data['Year'];
		$ExpenseHead = $this->request->data['ExpenseHead'];
		$ExpenseSubHead = $this->request->data['ExpenseSubHead'];
                $ExpenseUnit = $this->request->data['ExpenseUnit'];
                $PrevUnit = $this->request->data['PrevUnit'];
                $PrevExpenseUnitValue = $this->request->data['PrevExpenseUnitValue'];
                $ExpenseUnitValue = $this->request->data['ExpenseUnitValue'];
                //$CostCenterCount = $this->request->data['CostCenterCount'];
                $CostCenterValues = $this->request->data['CostCenterValues'];
                $CostCenter=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT Cost_Center FROM Cost_Master WHERE Branch='$Branch' AND active=1 ORDER BY Cost_Center");
		$CostCenterCount=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT Count(Cost_Center) CostCenterCount FROM Cost_Master WHERE Branch='$Branch' AND active=1 ORDER BY Cost_Center");
		$MasterSrNo=$this->Tbl_bgt_expensesubheadingmaster->query("Select SrNo from tbl_expensemaster Where Branch='$Branch' and  Year='$Year' and  Month='$Month'");
                echo "<div class='form-group has-success has-feedback'>";
                echo "<label class='col-sm-6 control-label'>Cost Center</label><label class='col-sm-6 control-label'>Amount</label></br>";
		$QueryResult=$this->Tbl_bgt_expensesubheadingmaster->query("Select * from tbl_tempexpensedetails Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and ExpenseSubSubHeadId='".$ExpenseUnit."' Order By CostCenter");
                $rownumber = 0;
                if(array_key_exists('0',$QueryResult) && $ExpenseUnit !== ""){
                foreach($QueryResult as $row){
                echo "<label class='col-sm-6 control-label'>".$row['tbl_tempexpensedetails']['CostCenter']."</label><input type='text' class='col-sm-6 control-label' name='data[GRN[GRNCC_".$rownumber."]' id= 'GRNCC_".$rownumber."' value='".$row['tbl_tempexpensedetails']['Amount']."'></br>";

                if($PrevUnit != ""){
                    $QueryResultUpdate = $this->Tbl_bgt_expensesubheadingmaster->query("Update tbl_tempexpensedetails Set Amount = '$CostCenterValues[$rownumber]' Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and ExpenseSubSubHeadId='".$PrevUnit."' and CostCenter = '".$row['tbl_tempexpensedetails']['CostCenter']."'");
                }
                $rownumber = $rownumber + 1;
                }
                }
                else{
                foreach($CostCenter as $row){
                $QueryResult = $this->Tbl_bgt_expensesubheadingmaster->query("Insert Into tbl_tempexpensedetails(MasterSrNo,CostCenter,ExpenseHeadId,ExpenseSubHeadId,ExpenseSubSubHeadId,Amount,SaveBy) Values('".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."','".$row['Cost_Master']['Cost_Center']."','".$ExpenseHead."','".$ExpenseSubHead."','".$ExpenseUnit."','0','".$this->Session->check("username")."')");
                echo "<label class='col-sm-6 control-label'>".$row['Cost_Master']['Cost_Center']."</label><input type='text' class='col-sm-6 control-label' name='data[GRN[GRNCC_".$rownumber."]' id= 'GRNCC_".$rownumber."' value='0'></br>";

                if($PrevUnit != ""){
                    $QueryResultUpdate = $this->Tbl_bgt_expensesubheadingmaster->query("Update tbl_tempexpensedetails Set Amount = '$CostCenterValues[$rownumber]' Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and ExpenseSubSubHeadId='".$PrevUnit."' and CostCenter = '".$row['Cost_Master']['Cost_Center']."'");
                }
                $rownumber = $rownumber + 1;
                }
                } 
                echo "<input type='hidden' name='data[GRN[GRNCostCenterCount]' id= 'GRNCostCenterCount' value = '".$CostCenterCount[0][0]['CostCenterCount']."'>";
                echo "</div>"; 
                   die;
                }

                
                
                public function saveData(){
		$this->layout='ajax';

                $Branch = $this->request->data['branch'];
		$Month = $this->request->data['Month'];
		$Year = $this->request->data['Year'];
		$ExpenseHead = $this->request->data['ExpenseHead'];
		$ExpenseSubHead = $this->request->data['ExpenseSubHead'];
                $ExpenseUnit = $this->request->data['ExpenseUnit'];
                $CostCenterValues = $this->request->data['CostCenterValues'];
                $GRNType = $this->request->data['GRNType'];
                $GRNAmount = $this->request->data['GRNAmount'];
                $GRNDate = $this->request->data['GRNDate'];
                $GRNStatus = $this->request->data['GRNStatus'];
                $GNVendors = $this->request->data['GNVendors'];
                $BillNumber = $this->request->data['BillNumber'];
                $BillDate = $this->request->data['BillDate'];
                $GRNRemarks = $this->request->data['GRNRemarks'];
                
                $CostCenter=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT Cost_Center FROM Cost_Master WHERE Branch='$Branch' AND active=1 ORDER BY Cost_Center");
		$CostCenterCount=$this->Tbl_bgt_expensesubheadingmaster->query("SELECT Count(Cost_Center) CostCenterCount FROM Cost_Master WHERE Branch='$Branch' AND active=1 ORDER BY Cost_Center");
                $MasterSrNo=$this->Tbl_bgt_expensesubheadingmaster->query("Select SrNo from tbl_expensemaster Where Branch='$Branch' and  Year='$Year' and  Month='$Month'");
		$QueryResult=$this->Tbl_bgt_expensesubheadingmaster->query("Select * from tbl_tempexpensedetails Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and ExpenseSubSubHeadId='".$ExpenseUnit."' Order By CostCenter");
                $rownumber = 0;
                
                if($ExpenseUnit != ""){
                foreach($CostCenter as $row){
                    $QueryResultUpdate = $this->Tbl_bgt_expensesubheadingmaster->query("Update tbl_tempexpensedetails Set Amount = '$CostCenterValues[$rownumber]' Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and ExpenseSubSubHeadId='".$ExpenseUnit."' and CostCenter = '".$row['Cost_Master']['Cost_Center']."'");
                $rownumber = $rownumber + 1;
                }
                }
                else{
                foreach($CostCenter as $row){
                    $QueryResultUpdate = $this->Tbl_bgt_expensesubheadingmaster->query("Update tbl_tempexpensedetails Set Amount = '$CostCenterValues[$rownumber]' Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead' and CostCenter = '".$row['Cost_Master']['Cost_Center']."'");
                $rownumber = $rownumber + 1;
                }
                }
                
                $GRN = "";
                $ExpDetails = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT * FROM tbl_bgt_expenseheadingmaster WHERE HeadingId='$ExpenseHead'");
                $ExpenseHeadDesc = $ExpDetails[0]['tbl_bgt_expenseheadingmaster']['HeadingDesc'];
                $ExpSubDetails = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT * FROM tbl_bgt_expensesubheadingmaster WHERE SubHeadingId = '$ExpenseSubHead' and HeadingId='$ExpenseHead'");
                $ExpenseSubHeadDesc = $ExpSubDetails[0]['tbl_bgt_expensesubheadingmaster']['SubHeadingDesc'];
                $ImprestDetails = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT Max(CID) + 1 SrNo FROM imprest_master");

                //print_r($ImprestDetails);
                
                if(array_key_exists('0',$ImprestDetails)){
                    if($ImprestDetails[0][0]['SrNo'] != ""){
                $GRN = "$Year/$Month/".$ImprestDetails[0][0]['SrNo'];
                }
                else{
                $GRN = "$Year/$Month/1";
                }
             }
                
                $d = substr($GRNDate, 0, 2);
                $m = substr($GRNDate, 3, 2);
                $y = substr($GRNDate, 6, 4);
                $GRNDate = "$y-$m-$d";
                
                if($BillDate != ""){
                $d = substr($BillDate, 0, 2);
                $m = substr($BillDate, 3, 2);
                $y = substr($BillDate, 6, 4);
                $BillDate = "$y-$m-$d";
                }
                
                $CheckAmount = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT Sum(IFNULL(Amount,0)) TotalAmount FROM tbl_tempexpensedetails Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead'");
                echo "<div class='form-group has-success has-feedback'>";
                if($CheckAmount[0][0]['TotalAmount'] != $GRNAmount){
                echo " <script> alert('Expense Unit / CostCenter Amount and GRN Amount Mismatch'); </script> ";
                }
                else{
                $CheckEntry = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT * FROM tbl_expensedetails Where MasterSrNo='".$MasterSrNo['0']['tbl_expensemaster']['SrNo']."' and  ExpenseHeadId='$ExpenseHead' and  ExpenseSubHeadId='$ExpenseSubHead'");
                if(array_key_exists('0',$CheckEntry)){
                $UpdateEntry = $this->Tbl_bgt_expensesubheadingmaster->query("UPDATE tbl_expensedetails INNER JOIN tbl_tempexpensedetails ON (tbl_expensedetails.MasterSrNo = tbl_tempexpensedetails.MasterSrNo AND  tbl_expensedetails.ExpenseHeadId = tbl_tempexpensedetails.ExpenseHeadId AND   tbl_expensedetails.ExpenseSubHeadId = tbl_tempexpensedetails.ExpenseSubHeadId AND   tbl_expensedetails.ExpenseSubSubHeadId = tbl_tempexpensedetails.ExpenseSubSubHeadId AND   tbl_expensedetails.CostCenter = tbl_tempexpensedetails.CostCenter) SET tbl_expensedetails.Amount = tbl_expensedetails.Amount + tbl_tempexpensedetails.Amount, tbl_expensedetails.ApprovedAmount = tbl_expensedetails.ApprovedAmount + tbl_tempexpensedetails.Amount,SaveDate = now()");
                }
                else{
                    $InsertEntry = $this->Tbl_bgt_expensesubheadingmaster->query("INSERT INTO tbl_expensedetails(MasterSrNo, CostCenter, ExpenseHeadId, ExpenseSubHeadId, ExpenseSubSubHeadId,Amount,ApprovedAmount) SELECT MasterSrNo, CostCenter, ExpenseHeadId, ExpenseSubHeadId, ExpenseSubSubHeadId,Amount,Amount FROM tbl_tempexpensedetails");
                }
                
                
                               
// Generate GRN Number amd Save into Update Expense Details if exists
                    //$GRNEntry = $this->Tbl_bgt_expensesubheadingmaster->query("INSERT INTO tbl_expensedetails(MasterSrNo, CostCenter, ExpenseHeadId, ExpenseSubHeadId, ExpenseSubSubHeadId,Amount,ApprovedAmount) SELECT MasterSrNo, CostCenter, ExpenseHeadId, ExpenseSubHeadId, ExpenseSubSubHeadId,Amount,Amount FROM tbl_tempexpensedetails");
                echo " <script> alert('GRN Number generated is - ".$GRN."'); </script> ";
                if($BillDate != ""){
                   $ImprestEntry = $this->Tbl_bgt_expensesubheadingmaster->query("INSERT INTO imprest_master(BRANCH,IMPREST_MANAGER,CATEGORY,SUB_CATEGORY,OUTFLOW,DESCRIPTION,DATED,ENTRY_DATE,Month,Year,ExpenseHead,ExpenseSubHead,GRN,ExpenseType,Vendor ,VendorBillNo,VendorBillDate, GRNStatus) values ('$Branch','".$this->Session->check("username")."','$ExpenseHeadDesc','$ExpenseSubHeadDesc','$GRNAmount','$GRNRemarks','$GRNDate',Now(),'$Month','$Year','$ExpenseHead','$ExpenseSubHead','$GRN','$GRNType','$GNVendors','$BillNumber','$BillDate', '$GRNStatus')");
                }
                else
                {
                   $ImprestEntry = $this->Tbl_bgt_expensesubheadingmaster->query("INSERT INTO imprest_master(BRANCH,IMPREST_MANAGER,CATEGORY,SUB_CATEGORY,OUTFLOW,DESCRIPTION,DATED,ENTRY_DATE,Month,Year,ExpenseHead,ExpenseSubHead,GRN,ExpenseType, GRNStatus) values ('$Branch','".$this->Session->check("username")."','$ExpenseHeadDesc','$ExpenseSubHeadDesc','$GRNAmount','$GRNRemarks','$GRNDate',Now(),'$Month','$Year','$ExpenseHead','$ExpenseSubHead','$GRN','$GRNType','$GRNStatus')");
                }
                
                $lastid =$this->Tbl_bgt_expensesubheadingmaster->query("select max(Cid) cid from imprest_master");
                              // print_r($lastid);die;
                $InsertEntrynew = $this->Tbl_bgt_expensesubheadingmaster->query("INSERT INTO tbl_expensedetailsnewentry(cid, MasterSrNo, CostCenter, ExpenseHeadId, ExpenseSubHeadId, ExpenseSubSubHeadId,Amount,GrnAmount,SaveDate) SELECT '{$lastid[0][0]['cid']}', tbl_tempexpensedetails.MasterSrNo, tbl_tempexpensedetails.CostCenter, tbl_tempexpensedetails.ExpenseHeadId, tbl_tempexpensedetails.ExpenseSubHeadId, tbl_tempexpensedetails.ExpenseSubSubHeadId,tbl_tempexpensedetails.Amount,'$GRNAmount',now() FROM  tbl_tempexpensedetails ");
                if($GRNStatus == "Open"){
                    //echo " <script> alert('Keeping Status Open'); </script> ";
                    $this->flash("Keeping Status Open", "index",1);
                }
                else if($GRNStatus == "Close"){
                  //echo " <script> alert('Closing Expense Entry'); </script> ";
                    $UpdBusinessCase = $this->Tbl_bgt_expensesubheadingmaster->query("update tbl_businesscasemaster set Status='Closed' where Branch='$Branch' and Month='$Month' and Year='$Year' and ExpenseHead='$ExpenseHead' and ExpenseSubHead='$ExpenseSubHead'");
                    $this->flash("Closing Expense Entry", "index",1);
                }
                
                    echo " <script> alert('Saved SuccessFully'); </script> ";
                echo "</div>";   
                /*}
            //die;*/
       }

 }
 
 public function downloadData(){
		$this->layout='ajax';

                $Branch = $this->request->data['branch'];
		$Month = $this->request->data['Month'];
		$Year = $this->request->data['Year'];

$Branch = str_replace(" ", "%20", $Branch);
                
$Fileurl = "http://bpsmis.ind.in/test.aspx?RequestType=ExpenseMaster&sBranch=$Branch&sYear=$Year&sMonth=$Month";
$GetFile = file_get_contents("$Fileurl") or die("Failed to connect");
if($GetFile != 'Failed to connect')
{
$filename="D:\\expense.xls";
$SaveFile = file_put_contents($filename,$GetFile) or die("Failed to save"); 
}

$myfile = fopen($filename, "r") or die("Unable to open file!");
$AA = fread($myfile,filesize($filename));

$AA = str_replace('<table cellspacing="0" rules="all" border="1" style="border-collapse:collapse;">', '', $AA);

$AA = str_replace('</th><th>', "','", $AA);
$AA = str_replace('<th>', "'", $AA);
$AA = str_replace('</th>', "'", $AA);
$AA = str_replace('</td><td>', "','", $AA);
$AA = str_replace('</td>', "'", $AA);
$AA = str_replace('<td>', "'", $AA);
$AA = str_replace('</tr><tr>', "),(", $AA);
$AA = str_replace('<tr>', '(', $AA);
$AA = str_replace('</tr>', ')', $AA);
$AA = str_replace('&nbsp;', '', $AA);
$AA = strip_tags($AA);
$AA = str_replace("(
		'SrNo','Branch','MONTH','YEAR','COUNT','CurrentStatus','Amount','STATUS','Remarks','SaveBy','SaveDate'
	),","", $AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_tempexpensemaster Where Branch = '$Branch'");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("insert into tbl_tempexpensemaster (SrNo,Branch,Month,Year,Count,CurrentStatus,Amount,Status,Remarks,SaveBy,SaveDate)values".$AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_expensemaster where SrNo In(Select SrNo from tbl_tempexpensemaster  Where Branch = '$Branch')");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Insert Into tbl_expensemaster Select * From tbl_tempexpensemaster where Branch = '$Branch'");


$Fileurl = "http://bpsmis.ind.in/test.aspx?RequestType=BusinessCase&sBranch=$Branch&sYear=$Year&sMonth=$Month";
$GetFile = file_get_contents("$Fileurl") or die("Failed to connect");
if($GetFile != 'Failed to connect')
{
$filename="D:\\businesscase.xls";
$SaveFile = file_put_contents($filename,$GetFile) or die("Failed to save"); 
}

$myfile = fopen($filename, "r") or die("Unable to open file!");
$AA = fread($myfile,filesize($filename));

$AA = str_replace('<table cellspacing="0" rules="all" border="1" style="border-collapse:collapse;">', '', $AA);

$AA = str_replace('</th><th>', "','", $AA);
$AA = str_replace('<th>', "'", $AA);
$AA = str_replace('</th>', "'", $AA);
$AA = str_replace('</td><td>', "','", $AA);
$AA = str_replace('</td>', "'", $AA);
$AA = str_replace('<td>', "'", $AA);
$AA = str_replace('</tr><tr>', "),(", $AA);
$AA = str_replace('<tr>', '(', $AA);
$AA = str_replace('</tr>', ')', $AA);
$AA = str_replace('&nbsp;', '', $AA);
$AA = strip_tags($AA);
$AA = str_replace("(
		'SrNo','Branch','Month','Year','ExpenseHead','ExpenseSubHead','ValidFrom','ValidTo','Objective','Coverage','Implication','Methodology','Amount','ApprovedAmount','CapexAmount','OpexAmount','Status','Remarks','PreparedBy','PreparationDate','Level1ApprovalBy','Level1ApprovalDate','Level2ApprovalBy','Level2ApprovalDate','Level3ApprovalBy','Level3ApprovalDate','Closedby','ClosedDate'
	),","", $AA);

$AA = str_replace("''","Null", $AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_tempbusinesscasemaster Where Branch = '$Branch'");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("insert into tbl_tempbusinesscasemaster (SrNo,Branch,Month,Year,ExpenseHead,ExpenseSubHead,ValidFrom,ValidTo,Objective,Coverage,Implication,Methodology,Amount,ApprovedAmount,CapexAmount,OpexAmount,Status,Remarks,PreparedBy,PreparationDate,Level1ApprovalBy,Level1ApprovalDate,Level2ApprovalBy,Level2ApprovalDate,Level3ApprovalBy,Level3ApprovalDate,Closedby,ClosedDate)values".$AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_businesscasemaster where SrNo In(Select SrNo from tbl_tempbusinesscasemaster Where Branch = '$Branch')");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Insert Into tbl_businesscasemaster Select * From tbl_tempbusinesscasemaster where Branch = '$Branch'");


/*
$Fileurl = "http://bpsmis.ind.in/test.aspx?RequestType=BusinessCase&sBranch=$Branch&sYear=$Year&sMonth=$Month";
$GetFile = file_get_contents("$Fileurl") or die("Failed to connect");
if($GetFile != 'Failed to connect')
{
$filename="D:\\businesscase.xls";
$SaveFile = file_put_contents($filename,$GetFile) or die("Faild to save"); 
}

$myfile = fopen($filename, "r") or die("Unable to open file!");
$AA = fread($myfile,filesize($filename));

$AA = str_replace('<table cellspacing="0" rules="all" border="1" style="border-collapse:collapse;">', '', $AA);

$AA = str_replace('</th><th>', "','", $AA);
$AA = str_replace('<th>', "'", $AA);
$AA = str_replace('</th>', "'", $AA);
$AA = str_replace('</td><td>', "','", $AA);
$AA = str_replace('</td>', "'", $AA);
$AA = str_replace('<td>', "'", $AA);
$AA = str_replace('</tr><tr>', "),(", $AA);
$AA = str_replace('<tr>', '(', $AA);
$AA = str_replace('</tr>', ')', $AA);
$AA = str_replace('&nbsp;', '', $AA);
$AA = strip_tags($AA);

echo $AA;
$AA = str_replace("(
		'SrNo','Branch','Month','Year','ExpenseHead','ExpenseSubHead','ValidFrom','ValidTo','Objective','Coverage','Implication','Methodology','Amount','ApprovedAmount','CapexAmount','OpexAmount','Status','Remarks','PreparedBy','PreparationDate','Level1ApprovalBy','Level1ApprovalDate','Level2ApprovalBy','Level2ApprovalDate','Level3ApprovalBy','Level3ApprovalDate','Closedby','ClosedDate'
	),","", $AA);

$AA = str_replace("''","Null", $AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_temprevenuemaster Where Branch = '$Branch'");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("insert into tbl_temprevenuemaster (SrNo,Branch,Month,Year,ExpenseHead,ExpenseSubHead,ValidFrom,ValidTo,Objective,Coverage,Implication,Methodology,Amount,ApprovedAmount,CapexAmount,OpexAmount,Status,Remarks,PreparedBy,PreparationDate,Level1ApprovalBy,Level1ApprovalDate,Level2ApprovalBy,Level2ApprovalDate,Level3ApprovalBy,Level3ApprovalDate,Closedby,ClosedDate)values".$AA);

$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Delete From tbl_revenuemaster where SrNo In(Select SrNo from tbl_temprevenuemaster Where Branch = '$Branch')");
$DownloadEntry = $this->Tbl_bgt_expensesubheadingmaster->query("Insert Into tbl_revenuemaster Select * From tbl_temprevenuemaster where Branch = '$Branch'");
*/

echo "<div class='form-group has-success has-feedback'>";
echo " <script> alert('Download Complete'); </script> ";
echo "</div>"; 
                
die;
                
     
 }
 
 
 }
 
 
 