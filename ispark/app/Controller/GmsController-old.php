<?php
class GmsController extends AppController 
{
    public $uses=array('Collection','Addbranch','BillMaster','TMPCollection','Addcompany','TMPCollectionParticulars','OtherTMPDeduction','OtherDeduction',
        'CollectionParticulars','InitialInvoice','Bank','User','BillMaster','TmpExpenseMaster','Tbl_bgt_expenseheadingmaster','VendorMaster','VendorRelation',
        'CostCenterMaster','ExpenseMaster','ExpenseEntryMaster','TmpExpenseEntryMaster','TmpExpenseEntryParticular','Tbl_bgt_expensesubheadingmaster','ExpenseEntryParticular','GrnBranchAccess');
    public $components = array('RequestHandler');
    public $helpers = array('Js');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('get_collection_data','index');
        $this->Auth->allow('get_collection_tmp_data');
        $this->Auth->allow('get_collection_tmp_bill_data');
        $this->Auth->allow('delete_collection_particular');
        $this->Auth->allow('Other_Deduction');
        $this->Auth->allow('delete_other_deduction');
        $this->Auth->allow('get_bill_amount');
        $this->Auth->allow('back');
        $this->Auth->allow('add'); 
        $this->Auth->allow('index','get_bill_remark','get_branch','getCostCenter','get_budget','add_field_value','add_grn_tmp','delete_grn','get_head','get_sub_heading','imprest_entry','get_sub_heading1','imprest_add','delete_imprest');

        if(!$this->Session->check("userid"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            
            if(in_array('68',$roles))
            {
                $this->Auth->allow('get_collection_data','index');
                $this->Auth->allow('get_collection_tmp_data');
                $this->Auth->allow('get_collection_tmp_bill_data');
                $this->Auth->allow('delete_collection_particular');
                $this->Auth->allow('Other_Deduction');
                $this->Auth->allow('delete_other_deduction');
                $this->Auth->allow('get_bill_amount');
                $this->Auth->allow('back');
                $this->Auth->allow('add'); 
                $this->Auth->allow('index','get_bill_remark','get_branch','getCostCenter','get_budget','add_field_value','add_grn_tmp','delete_grn','get_head','get_sub_heading','imprest_entry','get_sub_heading1','imprest_add');
            }
        }			
        if ($this->request->is('ajax'))
        {
                $this->render('contact-ajax-response', 'ajax');
        }
    }

    public function get_collection_data()
    {
        $this->layout = 'ajax';
        $this->set('data',$this->params->query);
        $conditions =$this->params->query;
        $this->set('RTGS',$this->Collection->find('first',array('fields' =>array('id','max(pay_no)'),'conditions'=>$conditions)));
    }
    public function get_branch()
    {
        $VendorId = $this->request->data['VendorId'];
        $role = $this->Session->read('role');
        $userid = $this->Session->read('userid');
        $branchName = "";
        if($role=='admin')
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));
            $branchName = " and bm.branch_name='".$this->Session->read("branch_name")."'";
            $join = "INNER JOIN (select * from tbl_grn_access group by UserId,BranchId) tga ON bm.Id = tga.BranchId ";
            $user = " AND tga.UserId='$userid'";

        }

        if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id $join
        WHERE tsgd.VendorId='$VendorId' $user GROUP BY branch_name"))
        {
            $branchArr = array();
            foreach($data as $v)
            {
                $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
            }
            echo json_encode($branchArr);
        }
        else
        {
            echo ''; exit;
        }
        exit;
    }
    public function get_head()
    {
        $VendorId = $this->request->data['VendorId'];
        if($data = $this->Tbl_bgt_expenseheadingmaster->query("SELECT head.HeadingId,head.HeadingDesc FROM `vendor_expense_relation` vr INNER JOIN `tbl_bgt_expenseheadingmaster` head ON vr.HeadId = head.HeadingId
WHERE VendorId = '$VendorId'  order by head.HeadingDesc"))
        {
            $HeadArr = array();
            foreach($data as $v)
            {
                $HeadArr[$v['head']['HeadingId']] = $v['head']['HeadingDesc'];
            }
            echo json_encode($HeadArr);
        }
        else
        {
            echo ''; 
        }
        exit;
    }

    public function get_sub_heading()
    {
        $VendorId = $this->request->data['VendorId'];
        $HeadingId = $this->request->data['HeadingId'];
        if($data = $this->Tbl_bgt_expensesubheadingmaster->query("SELECT subhead.SubHeadingId,subhead.SubHeadingDesc FROM `vendor_expense_relation` vr INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON vr.SubHeadId = subhead.SubHeadingId
WHERE vr.VendorId = '$VendorId' and vr.HeadId = '$HeadingId'  ORDER BY subhead.SubHeadingDesc"))
        {
            $SubHeadArr = array();
            foreach($data as $v)
            {
                $SubHeadArr[$v['subhead']['SubHeadingId']] = $v['subhead']['SubHeadingDesc'];
            }
            echo json_encode($SubHeadArr);
        }
        else
        {
            echo ''; 
        }
        exit;
    }

    public function getCostCenter()
    {
        $BranchId = $this->request->data['BranchId'];
        $CompId = $this->request->data['CompId'];
        if($data = $this->CostCenterMaster->query("SELECT cm.Id,cm.cost_center FROM branch_master bm INNER JOIN cost_master cm ON bm.branch_name = cm.branch 
INNER JOIN company_master comp ON cm.company_name = comp.company_name
WHERE bm.Id = '$BranchId' and comp.Id = '$CompId'"))
        {
            $costArr = array();
            foreach($data as $v)
            {
                $costArr[$v['cm']['Id']] = $v['cm']['cost_center'];
            }
            echo json_encode($costArr);
        }
        else
        {
            echo ''; exit;
        }
        exit;
    }

    public function add_field_value()
    {
        $data['Particular'] = $this->request->data['Particular'];
        $data['ExpenseEntryType'] = $this->request->data['ExpenseEntryType'];
        $data['BranchId'] = $this->request->data['BranchId'];
        $data['CostCenterId'] = $this->request->data['CostCenter'];
        $data['Amount'] = $this->request->data['Amount'];
        $data['Rate'] = $this->request->data['Rate'];
        $data['Tax'] = round(($data['Amount']*$data['Rate'])/100,2);
        $data['Total'] = round($data['Amount']+$data['Tax'],2);
        $data['createdate'] = date("Y-m-d H:i:s");
        $data['userid'] = $this->Session->read("userid");

        if($this->TmpExpenseEntryParticular->save(array('TmpExpenseEntryParticular'=>$data)))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        exit;
    }

    public function get_budget()
    {
        $BranchId = $this->request->data['BranchId'];
        $FinanceYear = $this->request->data['FinanceYear'];
        $FinanceMonth = $this->request->data['FinanceMonth'];
        $HeadId = $this->request->data['HeadId'];
        $SubHeadId = $this->request->data['SubHeadId'];
        $VendorId = $this->request->data['VendorId'];
        $Budget = 0;
        $Consume = 0;
        $Balance = 0;
        if($data = $this->ExpenseMaster->find('first',array('conditions'=>array("BranchId"=>$BranchId,"FinanceYear"=>$FinanceYear,"FinanceMonth"=>$FinanceMonth,
            "HeadId"=>$HeadId,"SubHeadId"=>$SubHeadId))))
        {
                if($data['ExpenseMaster']['Amount']==0 || $data['ExpenseMaster']['EntryStatus']==0)
                {
                    echo "1";
                }
                else
                {
                    $Budget = $data['ExpenseMaster']['Amount']?$data['ExpenseMaster']['Amount']:0;
                    $Expense = $this->ExpenseEntryMaster->query("SELECT SUM(eep.Amount)Total FROM expense_entry_master eem
INNER JOIN `expense_entry_particular` eep ON eem.Id = eep.ExpenseEntry
INNER JOIN `cost_master` cm ON eep.CostCenterId = cm.Id
INNER JOIN branch_master bm ON cm.branch = bm.branch_name where "
                    . "eem.FinanceYear='$FinanceYear' AND eem.FinanceMonth='$FinanceMonth' AND bm.Id='$BranchId' AND eem.HeadId='$HeadId' AND eem.SubHeadId='$SubHeadId'");


                    $Consume = empty($Expense['0']['0']['Total'])?0:$Expense['0']['0']['Total'];
                    $Balance = $Budget-$Consume;

                    $VendorRelation = 0;
                    if($this->VendorRelation->find('first',array('conditions'=>array('VendorId'=>$VendorId,'BranchId'=>$BranchId,'GSTEnable'=>"1"))))
                    {
                        $VendorRelation = 1;
                    }

                    if($Balance)
                    {
                        echo $Budget.",".$Consume.",".$Balance.",".$VendorRelation;
                    }
                    else
                    {
                        echo "1";
                    }
                }
        }
        else
        {
            echo "0";
        }
        exit;
    }
		
    public function back()
    {
        $userid = $this->Session->read('userid');
        $this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
        $this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
        return $this->redirect(array('controller'=>'gms','action'=>'index'));
    }
		
    public function add_grn_tmp()
    {
        $data['FinanceYear'] = $this->request->data['FinanceYear'];
        $data['FinanceMonth'] = $this->request->data['FinanceMonth'];
        $data['HeadId'] = $this->request->data['HeadId'];
        $data['SubHeadId'] = $this->request->data['SubHeadId'];
        $data['Vendor'] = $this->request->data['vendorId'];
        $data['bill_no'] = $this->request->data['BillNo'];
        $data['Amount'] = $this->request->data['Amount'];
        $data['Description'] = $this->request->data['description'];
        $data['ExpenseDate'] = $this->request->data['entry_date'];
        $data['EntryStatus'] = $this->request->data['entry_status'];
        $data['bill_date'] = $this->request->data['bill_date'];
        $data['CompId'] = $this->request->data['CompId'];
        $userid = $data['userid'] = $this->Session->read("userid");
            
        if(!$this->TmpExpenseEntryMaster->find('first',array('conditions'=>$data)))
        {
            if($this->TmpExpenseEntryMaster->save(array("TmpExpenseEntryMaster"=>$data)))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
        }
        else
        {
            $dataX = array();
            foreach($data as $k=>$v)
            {
                $dataX[$k] = "'".$v."'";
            }

            if($this->TmpExpenseEntryMaster->updateAll($dataX,array('userid'=>$userid)))
            {
                echo "2";
            }
            else
            {
                echo "3";
            }   
        }
    }    
    
    public function delete_grn()
    {
        $Id = $this->request->data['Id'];
        if($this->TmpExpenseEntryParticular->deleteAll(array('Id'=>$Id)))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        exit;
    }
    
    
    public function delete_imprest()
    {
        $Id = $this->request->data['Id'];
        if($this->TmpExpenseEntryParticular->deleteAll(array('Id'=>$Id)))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        exit;
    }
        
    public function index() 
    {
        $branchArr = "";
        $this->layout='home';
        $userid = $this->Session->read('userid');

        $EntryType = $this->params->query['entryType'];

        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
        'order' => array('branch_name' => 'asc')))); 
        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        //$this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc'))));

        $VendorArr = $this->VendorMaster->query("SELECT tv.Id,tv.vendor,comp.comp_code,comp.Id FROM tbl_vendormaster tv INNER JOIN (SELECT * FROM `tbl_state_comp_gst_details` tscgd GROUP BY VendorId,BranchId,CompId)tscgd ON tv.Id = tscgd.VendorId
        INNER JOIN `tbl_grn_access` tga ON tscgd.BranchId = tga.BranchId 
        INNER JOIN `company_master` comp ON comp.Id = tscgd.CompId
        WHERE UserId = '$userid' AND tv.Id>660 AND tv.vendor !='Previous Entry'
        GROUP BY tv.Id,tv.vendor,comp.id ORDER BY tv.vendor");

        $Vendor = array();
        foreach($VendorArr as $post)
        {
            $Vendor[$post['tv']['Id'].'-'.$post['comp']['Id']] =  $post['tv']['vendor']."(".$post['comp']['comp_code'].")";
        }                
                        
        $this->set('Vendor',$Vendor);
        $this->set('result',$this->TmpExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
        WHERE teep.userid='$userid'"));
        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
                        
        if($data = $this->TmpExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId'),'conditions'=>array('userid'=>$userid))))
        {
           $VendorId = $data['TmpExpenseEntryMaster']['Vendor'];
           $HeadId = $data['TmpExpenseEntryMaster']['HeadId'];
            $vend = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId)));

            foreach($vend as $k=>$v)
            {
                $Vendor[$k.'-'.$data['TmpExpenseEntryMaster']['CompId']] = $v;
            }
            $this->set('Vendor' ,$Vendor);
            $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));

            $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
            'conditions'=>array('SubHeadingId'=>$data['TmpExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));

            $data = array_values($data['TmpExpenseEntryMaster']);
            $this->set('ExpenseEntryMaster',$data);

            if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
            WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
            {

                foreach($data as $v)
                {
                    $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                }
                $this->set('branchArr',$branchArr);
            }
            else
            {
                $this->set('branchArr',"");
            }
        }
        else
        {
            $data = array_fill(0,10,'');
            $data = array_values($data);
            $this->set('head',"");
            $this->set('SubHeading',"");
            $this->set('ExpenseEntryMaster',$data);

        }
    }        
        
    public function add()
    {
        $userid = $this->Session->read("userid");
        $ExpenTmp = $this->TmpExpenseEntryMaster->find('first',array('conditions'=>array('userid'=>$userid)));
            
        $data = $ExpenTmp['TmpExpenseEntryMaster'];
        $dataX['ExpenseEntryType'] = $ExpenseEntryType = "Vendor";
        $dataX['FinanceYear'] = $FinanceYear = $data['FinanceYear'];
        $dataX['FinanceMonth'] = $FinanceMonth = $data['FinanceMonth'];
        $dataX['HeadId'] = $HeadId = $data['HeadId'];
        $dataX['SubHeadId'] = $SubHeadId = $data['SubHeadId'];
        $dataX['Vendor'] = $Vendor = $data['Vendor'];
        $dataX['bill_no'] = $bill_no = $data['bill_no'];
        $dataX['bill_date'] = $bill_date = $data['bill_date'];
        $dataX['Amount'] = $Amount = $data['Amount'];
        $dataX['Description'] = $description = $data['Description'];
        $dataX['ExpenseDate'] = $ExpenseDate = $data['ExpenseDate'];
        $dataX['EntryStatus'] = $EntryStatus = $data['EntryStatus'];
        $dataX['createdate'] = date('Y-m-d H:i:s');
            
        $BranchArr = $this->TmpExpenseEntryParticular->query("SELECT bm.Id,SUM(Total) Amt FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm ON teep.CostCenterId = cm.Id
        INNER JOIN branch_master bm ON cm.branch = bm.branch_name WHERE teep.userid = '$userid' GROUP BY cm.branch ");
        $flag = true; $Amount = 0;
        foreach($BranchArr as $post)
        {
            $BranchId =$post['bm']['Id']; $TotalAmount = $post['0']['Amt'];
            $BudgetArr = $this->ExpenseMaster->find('first',array('fields'=>array('Id','Amount'),
            'conditions'=>array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));



            $BalArr = $this->TmpExpenseEntryParticular->query("SELECT SUM(eep.Amount) BalAmt FROM `expense_entry_particular` eep
            INNER JOIN `expense_entry_master` eem ON eep.ExpenseEntry = eem.Id
            INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name
            WHERE bm.Id='$BranchId' AND eem.FinanceYear='$FinanceYear`' AND eem.FinanceMonth = '$FinanceMonth' AND eem.HeadId = '$HeadId' 
            AND eem.SubHeadId='$SubHeadId'");

           $BalAmt = $BalArr['0']['0']['BalAmt']; 
           if(empty($BalAmt))
           {
               $BalAmt = 0;
           }
            $Parent = $BudgetArr['ExpenseMaster']['Id'];
            $Budget = $BudgetArr['ExpenseMaster']['Amount'];

//                if(($BalAmt-$TotalAmount)<0)
//                {
//                    echo "ss";
//                    $flag = false;
//                    $this->Session->setFlash(__("Balance Amount Is Lest than Bill Amount. Reopen Business Case"));
//                    break;
//                }

            $Amount += $TotalAmount;
        }
            
        if($flag)
            {
                echo "0";
                if($this->ExpenseEntryMaster->save(array('ExpenseEntryMaster'=>$dataX)))
                {
                    $Transaction = $this->ExpenseEntryMaster->getDataSource(); //start transaction
                    $Id = $this->ExpenseEntryMaster->getLastInsertId();
                    
                    
                    $Id = $this->ExpenseEntryMaster->getLastInsertId();
                    $CntArr = $this->ExpenseEntryMaster->query("SELECT COUNT(1) cnt FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth'");
                    $GrnCnt = $CntArr['0']['0']['cnt'];

                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

                    if($monthArray[$FinanceMonth]<=3) 
                    {
                        $FinanceYear1 = explode('-',$FinanceYear);
                        $FinanceYear2 = $FinanceYear1[1]-1;
                    }
                    else
                    {
                        $FinanceYear1 = explode('-',$FinanceYear);
                        $FinanceYear2 = $FinanceYear1[1];
                    }


                    $GrnNO = 'MasNew'.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";

                    $flag = true;
                    if(!$this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id)))
                    {
                        $Transaction->rollback();
                        $flag = false;
                        $this->Session->setFlash(__("Grn No. Not Saved Successfully! Please Try Again"));
                    }
                    else
                    {
                        $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
                    }
                       //$TParticular = $this->find('all',array('fields'=>array(''),'conditions'=>array()));

                    }
                    else
                    {
                        $this->Session->setFlash(__("Business Case Not Saved"));    
                    }
                    
                    if($flag)
                    {
                        //echo "1";
                        if($this->TmpExpenseEntryParticular->updateAll(array("ExpenseEntryType"=>"'".$ExpenseEntryType."'","ExpenseEntry"=>$Id),array("userid"=>$userid)))
                        {
                            //echo "2";
                            $exp_part = $this->TmpExpenseEntryParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('userid'=>$userid)));
                            //print_r($exp_part); exit;
                            foreach($exp_part as $v)
                            {
                                $part = $v['TmpExpenseEntryParticular'];
                                if($this->ExpenseEntryParticular->saveAll($part))
                                {
                                   // echo "3";
                                     $Transaction->commit();
                                }
                                else
                                {
                                    $Transaction->rollback();
                                }
                            }    
                        }
                        else
                        {
                            $flag = false;
                           $Transaction->rollback(); 
                        }
                    }
                
            }
            $this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
            $this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
            $this->redirect(array("controller"=>"GrnEntries","action"=>"select_entry"));
            exit;
            
        }	
	
        
        
        
        public function get_sub_heading1()
        {
            $HeadingId = $this->request->data['HeadingId'];
            
            if($data = $this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$HeadingId))))
            {
                
                echo json_encode($data);
            }
            else
            {
                echo ''; 
            }
            exit;
        }
        
        
        
    public function imprest_entry() 
    {
        $branchArr = "";
        $this->layout='home';
        $userid = $this->Session->read('userid');
        $role=$this->Session->read("role");
        $EntryType = $this->params->query['entryType'];

        if($role=='admin')
        {
            $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'), 'conditions'=>array('active'=>1),
            'order' => array('branch_name' => 'asc')))); 
        }
        else
        {
           $bm = $this->Addbranch->query("Select bm.Id,bm.branch_name from branch_master bm inner join tbl_grn_access tga on bm.Id = tga.BranchId where bm.active = 1");
           $branch_master = array();
           foreach($bm as $post)
           {
               $branch_master[$post['bm']['Id']] = $post['bm']['branch_name'];
           }
           $this->set('branch_master', $branch_master);
        }

 
        
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>""),'order'=>array('HeadingDesc'=>'asc'))));
        $this->set('result',$this->TmpExpenseEntryParticular->query("SELECT teep.*,cm.Branch,cm.cost_center FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm  ON teep.CostCenterId= cm.id
        WHERE teep.userid='$userid'"));
        $this->set('finance_yearNew',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));

        if($data = $this->TmpExpenseEntryMaster->find('first',array('fields'=>array('FinanceYear','FinanceMonth','HeadId','SubHeadId','Vendor','bill_no',
            'bill_date','Amount','Description','ExpenseDate','EntryStatus','CompId'),'conditions'=>array('userid'=>$userid))))
        {
            $VendorId = $data['TmpExpenseEntryMaster']['Vendor'];
            $HeadId = $data['TmpExpenseEntryMaster']['HeadId'];
            $this->set('Vendor' , $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('Id'=>$VendorId))));
            
            
               // $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('HeadingId'=>$HeadId))));

            $this->set('SubHeading',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
            'conditions'=>array('SubHeadingId'=>$data['TmpExpenseEntryMaster']['SubHeadId']),'order'=>array('SubHeadingDesc'=>'asc'))));

            $data = array_values($data['TmpExpenseEntryMaster']);
            $this->set('ExpenseEntryMaster',$data);

            if($data = $this->VendorRelation->query("SELECT bm.Id,branch_name FROM `tbl_state_comp_gst_details` tsgd INNER JOIN `branch_master` bm ON tsgd.BranchId = bm.Id
            WHERE tsgd.VendorId='$VendorId' GROUP BY branch_name"))
            {

                foreach($data as $v)
                {
                    $branchArr[$v['bm']['Id']] = $v['bm']['branch_name'];
                }
                $this->set('branchArr',$branchArr);
            }
            else
            {
                $this->set('branchArr',"");
            }
        }
        else
        {
            $data = array_fill(0,10,'');
            $data = array_values($data);
            
            //$this->set('head',"");
            $this->set('SubHeading',"");
            $this->set('ExpenseEntryMaster',$data);

        }
    }
                
        public function imprest_add()
        {
            $userid = $this->Session->read("userid");
            $ExpenTmp = $this->TmpExpenseEntryMaster->find('first',array('conditions'=>array('userid'=>$userid)));
            
            $data = $ExpenTmp['TmpExpenseEntryMaster'];
            $dataX['ExpenseEntryType'] = $ExpenseEntryType = "Imprest";
            $dataX['FinanceYear'] = $FinanceYear = $data['FinanceYear'];
            $dataX['FinanceMonth'] = $FinanceMonth = $data['FinanceMonth'];
            $dataX['HeadId'] = $HeadId = $data['HeadId'];
            $dataX['SubHeadId'] = $SubHeadId = $data['SubHeadId'];
            $dataX['bill_no'] = $bill_no = $data['bill_no'];
            $dataX['bill_date'] = $bill_date = $data['bill_date'];
            $dataX['Amount'] = $Amount = $data['Amount'];
            $dataX['Description'] = $description = $data['Description'];
            $dataX['ExpenseDate'] = $ExpenseDate = $data['ExpenseDate'];
            $dataX['EntryStatus'] = $EntryStatus = $data['EntryStatus'];
            $dataX['createdate'] = date('Y-m-d H:i:s');
            
            $BranchArr = $this->TmpExpenseEntryParticular->query("SELECT bm.Id,SUM(Total) Amt FROM `tmp_expense_entry_particular` teep INNER JOIN cost_master cm ON teep.CostCenterId = cm.Id
            INNER JOIN branch_master bm ON cm.branch = bm.branch_name WHERE teep.userid = '$userid' GROUP BY cm.branch ");
            $flag = true; $Amount = 0;
            foreach($BranchArr as $post)
            {
                $BranchId =$post['bm']['Id']; $TotalAmount = $post['0']['Amt'];
                $BudgetArr = $this->ExpenseMaster->find('first',array('fields'=>array('Id','Amount'),
                'conditions'=>array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
                
                
                
                $BalArr = $this->TmpExpenseEntryParticular->query("SELECT SUM(eep.Amount) BalAmt FROM `expense_entry_particular` eep
INNER JOIN `expense_entry_master` eem ON eep.ExpenseEntry = eem.Id
INNER JOIN cost_master cm ON eep.CostCenterId = cm.Id
INNER JOIN branch_master bm ON cm.branch = bm.branch_name
WHERE bm.Id='$BranchId' AND eem.FinanceYear='$FinanceYear`' AND eem.FinanceMonth = '$FinanceMonth' AND eem.HeadId = '$HeadId' 
AND eem.SubHeadId='$SubHeadId'");
            
               $BalAmt = $BalArr['0']['0']['BalAmt']; 
               if(empty($BalAmt))
               {
                   $BalAmt = 0;
               }
                $Parent = $BudgetArr['ExpenseMaster']['Id'];
                $Budget = $BudgetArr['ExpenseMaster']['Amount'];
                
//                if(($BalAmt-$TotalAmount)<0)
//                {
//                    echo "ss";
//                    $flag = false;
//                    $this->Session->setFlash(__("Balance Amount Is Lest than Bill Amount. Reopen Business Case"));
//                    break;
//                }
                
                $Amount += $TotalAmount;
            }
            
            if($flag)
            {
                echo "0";
                if($this->ExpenseEntryMaster->save(array('ExpenseEntryMaster'=>$dataX)))
                {
                    $Transaction = $this->ExpenseEntryMaster->getDataSource(); //start transaction
                    $Id = $this->ExpenseEntryMaster->getLastInsertId();
                    
                    
                    $Id = $this->ExpenseEntryMaster->getLastInsertId();
                    $CntArr = $this->ExpenseEntryMaster->query("SELECT COUNT(1) cnt FROM `expense_entry_master` WHERE FinanceYear='$FinanceYear' AND FinanceMonth = '$FinanceMonth'");
                    $GrnCnt = $CntArr['0']['0']['cnt'];

                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);

                    if($monthArray[$FinanceMonth]<=3) 
                    {
                        $FinanceYear1 = explode('-',$FinanceYear);
                        $FinanceYear2 = $FinanceYear1[1]-1;
                    }
                    else
                    {
                        $FinanceYear1 = explode('-',$FinanceYear);
                        $FinanceYear2 = $FinanceYear1[1];
                    }


                    $GrnNO = 'MasNew'.'/'.$monthArray[$FinanceMonth].'/'.$FinanceYear2."/"."$GrnCnt";

                    $flag = true;
                    if(!$this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id)))
                    {
                        $Transaction->rollback();
                        $flag = false;
                        $this->Session->setFlash(__("Grn No. Not Saved Successfully! Please Try Again"));
                    }
                    else
                    {
                        $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
                    }
                       //$TParticular = $this->find('all',array('fields'=>array(''),'conditions'=>array()));

                    }
                    else
                    {
                        $this->Session->setFlash(__("Business Case Not Saved"));    
                    }
                    
                    if($flag)
                    {
                        //echo "1";
                        if($this->TmpExpenseEntryParticular->updateAll(array("ExpenseEntryType"=>"'".$ExpenseEntryType."'","ExpenseEntry"=>$Id),array("userid"=>$userid)))
                        {
                            //echo "2";
                            $exp_part = $this->TmpExpenseEntryParticular->find('all',array('fields'=>array("ExpenseEntryType","Particular","BranchId","ExpenseEntry","CostCenterId","Amount","Rate","Tax","Total"),'conditions'=>array('userid'=>$userid)));
                            //print_r($exp_part); exit;
                            foreach($exp_part as $v)
                            {
                                $part = $v['TmpExpenseEntryParticular'];
                                if($this->ExpenseEntryParticular->saveAll($part))
                                {
                                   // echo "3";
                                     $Transaction->commit();
                                }
                                else
                                {
                                    $Transaction->rollback();
                                }
                            }    
                        }
                        else
                        {
                            $flag = false;
                           $Transaction->rollback(); 
                        }
                    }
                
            }
            $this->TmpExpenseEntryMaster->deleteAll(array('userid'=>$userid));
            $this->TmpExpenseEntryParticular->deleteAll(array('userid'=>$userid));
            $this->redirect(array("controller"=>"GrnEntries","action"=>"select_entry"));
            exit;
            
        }        
        
}

?>