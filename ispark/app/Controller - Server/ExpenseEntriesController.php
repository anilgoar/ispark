<?php
class ExpenseEntriesController extends AppController 
{
    public $uses = array('Addbranch','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster','GrnBranchAccess',
        'TmpExpenseMaster','ExpenseMaster','TmpExpenseParticular','ExpenseParticular','BillMaster','ImprestManager','ExpenseReopen','User','BranchEmailMaster','BusinessCaseMaster','BusinessCaseFileUpload');
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(0)
        {
            //return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            if(in_array('63',$roles) || in_array('64',$roles)||in_array('65',$roles) ||in_array('66',$roles) ||in_array('67',$roles) ||in_array('68',$roles) || in_array('69',$roles))
        {$this->Auth->allow('index','initial_branch','get_cost_center','get_particular_breakup','get_particular_breakup',
                    'expense_entry','get_sub_heading','get_breakup','get_costcenter_breakup','expense_save','get_his_check','get_his_check2','get_old_delete','expense_final_save',
                    'expense_save_tmp','view_bm','edit_tmp_bm','bm_final_save','view_vh','edit_tmp_vh','vh_final_save','view_fh','edit_tmp_fh','fh_final_save','view','edit_tmp',
                    'save_tmp','discard','get_business_case','business_case_ropen','get_business_case_request','view_business_case_ropen','get_expense_amount','bm_multi_final_save',
                'vh_multi_final_save','fh_multi_final_save','get_dash_business','business_case_upload');}
            else{$this->Auth->deny('index','initial_branch','get_cost_center','get_particular_breakup','get_particular_breakup',
                    'expense_entry','get_sub_heading','get_breakup','get_costcenter_breakup','expense_save','get_his_check','get_his_check2','get_old_delete','expense_final_save',
                    'expense_save_tmp','view_bm','edit_tmp_bm','bm_final_save','view_vh','edit_tmp_vh','vh_final_save','view_fh','edit_tmp_fh','fh_final_save',
                    'view','edit_tmp','save_tmp','discard','get_business_case','business_case_ropen','get_business_case_request','view_business_case_ropen','get_expense_amount',
                    'bm_multi_final_save','vh_multi_final_save','fh_multi_final_save','get_dash_business','business_case_upload');}
        }
    }
    
    public function condition($array)
    {
        $newArray = array();
        foreach($array as $k=>$v)
        {
            $newArray[$k] = "'".$v."'";
        }
        return $newArray;
    }
    
    public function discard()
    {
        $id = $this->params->query['id'];
        $action = $this->params->query['action'];
        if($action=='view_bm')
        {
            $this->TmpExpenseMaster->query("update tmp_expense_master set Approve1 = NULL where Id='$id'");
        }
        else if($action=='view_vh')
        {
            $this->TmpExpenseMaster->query("update tmp_expense_master set Approve1 = NULL where Id='$id'");
        }
        else if($action=='view_fh')
        {
            $this->TmpExpenseMaster->query("update tmp_expense_master set Approve2 = NULL where Id='$id'");
        }
        //$this->TmpExpenseMaster->deleteAll(array('Id'=>$id));
        //$this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$id));
        $this->redirect(array('controller'=>'ExpenseEntries','action'=>$action));
    }

    public function updateSave($mainArray,$amount,$updArr,$Total)
    {
        $id="0";
        if($data = $this->TmpExpenseParticular->find('first',array('fields'=>'Id','conditions'=>$mainArray)))
            {
                if($amount!='' && $amount!='0')
                {
                    //$mainArray['ids']=5;
                    $this->TmpExpenseParticular->updateAll($updArr,
                        $mainArray);
                    $id = $data['TmpExpenseParticular']['Id'];
                //print_r($updArr); exit;
                }
                else 
                {
                    $this->TmpExpenseParticular->deleteAll($mainArray);
                }
            }
            else
            {
                 if($amount!='' && $amount!='0')
                 {
                    $this->TmpExpenseParticular->saveAll(array_merge($mainArray,$updArr));
                    $id = $this->TmpExpenseParticular->getLastInsertId();
                 }

            }
            return $id;
    }
    
    public function updatePercent($Id,$ExpenseType,$ExpenseHead,$ExpenseSubHead,$query)
    {
        $tmp = $this->TmpExpenseParticular->query("UPDATE tmp_expense_particular tmp INNER JOIN
        (SELECT ExpenseId,SUM(Amount)`Total` FROM tmp_expense_particular WHERE ExpenseId='$Id' AND ExpenseType='$ExpenseType' AND HeadId='$ExpenseHead' AND SubHeadId='$ExpenseSubHead' $query)AS tmp2
        ON tmp.ExpenseId=tmp2.ExpenseId
        SET AmountPercent=FLOOR(Amount*100/Total)
        WHERE tmp.ExpenseId='$Id' AND tmp.ExpenseType='$ExpenseType' $query");
        return $tmp;
    }
    
    public function get_business_case()
    {
        $this->layout="ajax";
        $bus_List=array();
        if($this->request->is("POST"))
        {
            $branchId=$this->request->data['branchId'];
            $finance_year=$this->request->data['finance_year'];
            $finance_month=$this->request->data['finance_month'];
            
            $bus_master = $this->ExpenseMaster->query("SELECT em.Id,CONCAT(em.Id,'##',em.Branch,'##',em.FinanceYear,'##',em.FinanceMonth,'##',hm.HeadingDesc,'##',shm.SubHeadingDesc) `name` FROM `expense_master` em
 INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId
  INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
  WHERE em.BranchId='$branchId' AND em.FinanceYear='$finance_year' AND em.FinanceMonth = '$finance_month' order by em.Id");
            $i=0;
            foreach($bus_master as $cm)
            {
                $bus_List[$cm['em']['Id']] = $cm['0']['name']; 
            }
            
            echo json_encode($bus_List);
        }
        exit;
    }
    
    public function get_expense_amount()
    {
         if($this->request->is("POST"))
        {
             $ExpenseId = $this->request->data['ExpenseId'];
             $Expense = $this->ExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
             echo $Expense['ExpenseMaster']['Amount'];
        }
        exit;
    }
    
    public function get_business_case_request()
    {
        $this->layout="ajax";
        $bus_List=array();
        if($this->request->is("POST"))
        {
            $branchId=$this->request->data['branchId'];
            $finance_year=$this->request->data['finance_year'];
            $finance_month=$this->request->data['finance_month'];
           
            $bus_ropen = $this->ExpenseReopen->query("SELECT * FROM `expense_reopen_master` erm 
INNER JOIN `expense_master` em ON erm.ExpenseId = em.Id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
WHERE erm.BranchId='$branchId' AND erm.FinanceYear='$finance_year' AND erm.FinanceMonth='$finance_month' and erm.BusinessStatus=1");
            $i=1;
            $html = '<table border="2"><tr><th><input type="checkbox" name="checkAll" onclick="checkAllBox()" id="checkAll" />Select</th><th>Business No.</th><th>Branch</th><th>FinanceYear</th><th>FinanceMonth</th>'
                    . '<th>Expense Head</th><th>Expense SubHead</th><th>Amount</th><th>Additional Amount</th><th>Description</th><th>Remarks</th></tr>';
            foreach($bus_ropen as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.'<input type="checkbox" name="check[]" value="'.$ro['erm']['Id'].'##'.$ro['em']['Id'].'"></td>';
                $html .= '<td>'.$ro['em']['Id'].'</td>';
                $html .= '<td>'.$ro['em']['Branch'].'</td>';
                $html .= '<td>'.$ro['em']['FinanceYear'].'</td>';
                $html .= '<td>'.$ro['em']['FinanceMonth'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                $html .= '<td>'.$ro['em']['Amount'].'</td>';
                $html .= '<td>'.$ro['erm']['AdditionalAmount'].'</td>';
                $html .= '<td>'.$ro['erm']['Description'].'</td>';
                $html .= '<td>'.$ro['erm']['Remarks'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';
            
            echo $html;
        }
        exit;
    }    
    
    public function index() 
    {
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $this->layout='home';
    }
    
    public function get_cost_center()
    {
        $this->layout="ajax";
        $cost_centerList=array();
        if($this->request->is("POST"))
        {
            $branchId=$this->request->data['branchId'];
            
            $cost_master = $this->CostCenterMaster->query("SELECT cm.id,cm.cost_center FROM cost_master cm INNER JOIN branch_master bm ON cm.branch=bm.branch_name"
                    . " WHERE bm.id='$branchId' and cm.active=1");
            $i=0;
            foreach($cost_master as $cm)
            {
                $cost_centerList[$cm['cm']['id']] = $cm['cm']['cost_center']; 
            }
            
            echo json_encode($cost_centerList);
        }
        exit;
    }
    
    public function get_sub_heading()
    {
        $this->layout="ajax";
        $SubHeading=array();
        if($this->request->is("POST"))
        {   
            if($this->request->data['HeadingId']=='All')
            {
                $SubHeading = array_merge(array('All'=>'All'),$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'))));
            }
            else
            {
            $SubHeading = $this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                'conditions'=>array('HeadingId'=>"".$this->request->data['HeadingId']."")));
            }
            
            echo json_encode($SubHeading);
        }
        exit;
    }
    
    public function get_breakup()
    {
        $this->layout="ajax";
        $$unit=array();
        //print_r($this->request->data); exit;
        if($this->request->is("POST"))
        {
            $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
            if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
                'conditions'=>array('HeadingId'=>$this->request->data['HeadingId'],'SubHeadingID'=>$this->request->data['SubHeadingID'],'Branch'=>$this->request->data['branch'],'Status'=>1))))
            {
                $html .='<tr><th>Sr No</th><th>Expense Unit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
                $div="unit";
                foreach($unit as $k=>$v)
                {
                    $html .='<tr onClick="get_costcenter_breakup('."'".$this->request->data['branch']."',".$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                    $html .='<td>'.'<input type="text" name="unit.amountpercent'.$k.'" value="" placeholder="%" class = "form-control" id= "perunit'.$k.'" readonly=""></td>';
                    $html .='<td>'.'<input type="text" name="unit.amount'.$k.'" value="" placeholder="Amount" class = "form-control" id= "amountunit'.$k.'" readonly=""></td>';
                    $html .='</tr>';
                    $unitMasterIds[]=$k;
                }
                $field='<input type="hidden" id="unitmaster" name="unitmaster" value="'.implode(',',$unitMasterIds).'">';
            }
            
            else
            {
              $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$this->request->data['branch'],'active'=>'1')));
              $html .='<tr><th>Sr No</th><th>Cost Center</th><th>%</th><th>Amount</th></tr></thead><tbody>';
              $div="costcenter";
              foreach($costMaster as $k=>$v)
              {
                  $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
                    $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                    $html .='<td>'.'<input type="text" name="costcenter.amountpercent'.$k.'" value="" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                    $html .='<td>'.'<input type="text" name="costcenter.amount'.$k.'" value="" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                    $html .='</tr>';
                    $costMasterIds[]=$k;
              } 
              $field='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
            }
            $html .='</tbody></table>'.$field;
            //$html .=$field;
            $arr[$div] = $html;
            print_r(json_encode($arr));
        }
        exit;
    }
     
    public function get_costcenter_breakup()
    {
        $this->layout="ajax";
        $unit=array();
        
        if($this->request->is("POST"))
        {
            $unit = $this->request->data['unitId'];
            $ExpenseId = $this->request->data['ExpenseId'];
            $field='<input type="hidden" id="unitId" name="unitId" value="'.$unit.'">';
            if($unit)
            {
                $condition=array('ExpenseTypeParent'=>$unit);
            }
            else
            {
                $condition = array('1'=>'1');
            }
            $unitMaster = $this->Tbl_bgt_expenseunitmaster->find('first',array('fields'=>'ExpenseUnit','conditions'=>array('ExpenseunitID'=>$unit)));
            $unitName = $unitMaster['Tbl_bgt_expenseunitmaster']['ExpenseUnit'];
            $html = '<table class="table" style="font-size: 12px"><thead><tr><th colspan="4">'.$unitName.'</th></tr>'; $i=1;
            $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$this->request->data['branch'],'active'=>'1')));
            $html .='<tr><th>Sr No</th><th>ExpenseUnit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            
              foreach($costMaster as $k=>$v)
              {
                    $particularAmount = $this->TmpExpenseParticular->find('first',array('conditions'=>array_merge($condition,array('ExpenseTypeId'=>$k,'ExpenseId'=>$ExpenseId))));
                
                    $amount = $particularAmount['TmpExpenseParticular']['Amount'];
                    $perAmount = $particularAmount['TmpExpenseParticular']['AmountPercent'].'%';
                    $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
                    $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                    $html .='<td>'.'<input type="text" name="costcenter_amountpercent_'.$unit.'_'.$k.'" value="'.$perAmount.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                    $html .='<td>'.'<input type="text" name="costcenter_amount_'.$unit.'_'.$k.'" value="'.$amount.'" placeholder="Amount" onkeypress="return isNumberKey(event)" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                    $html .='</tr>';
                    $costMasterIds[] =$k; 
              } 
            $field.='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
            echo $html .='</tbody></table>'.$field;
            //print_r(json_encode($arr));
        }
        exit;
    }
    
   public function get_particular_breakup()
   {
       $this->layout="ajax";
       if($this->request->is("POST"))
        {
           $costcenter = $this->request->data['costcenter'];
           $ExpenseId = $this->request->data['ExpenseId'];
           //print_r($this->request->data); exit;
           if($this->request->data['unitId']=='')
           {
               $condition = array('ExpenseType'=>'CostCenter','ExpenseTypeId'=>$costcenter,'ExpenseId'=>$ExpenseId);
           }
           else
           {
               $condition = array('ExpenseType'=>'CostCenter','ExpenseTypeId'=>$costcenter,'ExpenseTypeParent'=>$this->request->data['unitId'],'ExpenseId'=>$ExpenseId);
           }
           
           //print_r($condition); exit;
           $parentMaster = $this->TmpExpenseParticular->find('first',array('fields'=>array('Id'),'conditions'=>$condition));
           $parent = $parentMaster['TmpExpenseParticular']['Id'];
           //print_r($parentMaster); exit;
           
           $costMaster = $this->CostCenterMaster->find('first',array('fields'=>array('process_name','cost_center'),'conditions'=>array('Id'=>$costcenter,'active'=>'1')));
            $costName = $costMaster['CostCenterMaster']['process_name'].'('.$costMaster['CostCenterMaster']['cost_center'].')';
           
           $html = '<table class="table" style="font-size: 12px"><thead><tr><th colspan="4">'.$costName.'</th></tr>'; $i=1;
           $html .='<tr><th>Sr No</th><th>ExpenseUnit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
           $part = array('1'=>'WorkStation','2'=>'Mannual','3'=>'Revenue');
           //print_r(array('ExpenseTypeParent'=>$costcenter,'ExpenseType'=>'Particular','ExpenseTypeId'=>$k,'Parent'=>$parent,'ExpenseId'=>$ExpenseId)); exit;
           foreach($part as $k=>$v)
              { 
                    $particularAmount = $this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseTypeParent'=>$costcenter,'ExpenseType'=>'Particular','ExpenseTypeId'=>$k,'Parent'=>$parent,'ExpenseId'=>$ExpenseId)));
               // print_r($particularAmount); exit;
                    $amount = $particularAmount['TmpExpenseParticular']['Amount'];
                    $perAmount = $particularAmount['TmpExpenseParticular']['AmountPercent'].'%';
                    
                                        
                    $html .='<tr><td>'.$i++.'</td><td>'.$v.'</td>';
                    $html .='<td>'.'<input type="text" name="particular_amountpercent_'.$costcenter.'_'.$k.'" value="'.$perAmount.'" placeholder="%" class = "form-control" id= "perparticular'.$k.'" readonly=""></td>';
                    $html .='<td>'.'<input type="text" name="particular_amount_'.$costcenter.'_'.$k.'" value="'.$amount.'" placeholder="Amount" class = "form-control" id= "amountparticular'.$k.'" onkeypress="return isNumberKey(event)" onpaste="return false"  onBlur="get_particular_check('.$costcenter.')"></td>';
                    $html .='</tr>';
              } 
           echo $html .='</tbody></table>'.'<div class="form-group"><div class="col-sm-4"><button class="btn btn-info" value="save" onClick="return ExpenseSave('.$costcenter.')">Save</button></div></div>';
           
           exit;
        }
   }
   
    public function initial_branch()
    {
        $role = $this->Session->read('role');
        $userId = $this->Session->read('userid');
        $this->TmpExpenseParticular->query("DELETE FROM tmp_expense_particular WHERE expenseid IN (SELECT Id FROM tmp_expense_master where userId='$userId' and Active = 0)");
        $this->TmpExpenseMaster->query("delete from tmp_expense_master where userId='$userId' and Active = 0");
        
        if($role=='admin')
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));
        }
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
        $this->layout='home';
    }
        
    public function expense_entry() 
    {
        if($this->request->is("POST"))
        {          
            $req = $this->request->data['ExpenseEntries'];
            $data['BranchId'] = $branch_id = $req['branchId'];
            
            $branchArray=$this->Addbranch->find("first",array('fields'=>array('branch_name'),'conditions'=>array('Id'=>$branch_id)));
            
            $data['Branch'] = $req['branch_name'] = $branchArray['Addbranch']['branch_name'];
            $data['FinanceYear']=$req['finance_year'];
            $data['FinanceMonth']=$req['finance_month'];
            $data['userId']=$this->Session->read('userid');
            
            if($tmp = $this->TmpExpenseMaster->find('first',array('conditions'=>array_merge(array('Active'=>'0'),$data))))
            {
                $req['id']=$id = $tmp['TmpExpenseMaster']['Id'];
            }
            else
            {
                $data['createdate'] = date('Y-m-d H:i:s');
                $tmp = $this->TmpExpenseMaster->save($data);
                $req['id']=$id = $this->TmpExpenseMaster->getLastInsertID();
            }
            
            $this->set('id',$id);
            $this->set('data',$req);
            $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'),'conditions'=>array('EntryBy'=>''),'order'=>array('HeadingDesc'=>'asc'))));
        }
        if($this->request->is("GET"))
        {            
            $req = $this->request->data['ExpenseEntries'];
            $data['BranchId'] = $branch_id = $req['branchId'];
            
            $branchArray=$this->Addbranch->find("first",array('fields'=>array('branch_name'),'conditions'=>array('Id'=>$branch_id)));
            
            $data['Branch'] = $req['branch_name'] = $branchArray['Addbranch']['branch_name'];
            $data['FinanceYear']=$req['finance_year'];
            $data['FinanceMonth']=$req['finance_month'];
            $data['userId']=$this->Session->read('userid');
            
            if($tmp = $this->TmpExpenseMaster->find('first',array('conditions'=>array_merge(array('Active'=>'0'),$data))))
            {
                $req['id']=$id = $tmp['TmpExpenseMaster']['Id'];
            }
            else
            {
                $data['createdate'] = date('Y-m-d H:i:s');
                $tmp = $this->TmpExpenseMaster->save($data);
                $req['id']=$id = $this->TmpExpenseMaster->getLastInsertID();
            }
            
            $this->set('id',$id);
            $this->set('data',$req);
            $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
        }
        $this->layout='home';
    }
            
    public function expense_save()
    {
        $this->layout='ajax';
        
        $amountArr['1'] = $workstation=$this->request->data['workstation'];
        $amountArr['2'] = $mannual=$this->request->data['mannual'];
        $amountArr['3'] = $revenue=$this->request->data['revenue'];
        $cost_center=$this->request->data['cost_center'];
        $unitId=$this->request->data['unitId'];
        $branch=$this->request->data['branch'];
        $financeYear=$this->request->data['financeYear'];
        $financeMonth=$this->request->data['financeMonth'];
        $ExpenseHead=$this->request->data['ExpenseHead'];
        $ExpenseSubHead=$this->request->data['ExpenseSubHead'];
        $Id=$this->request->data['Id'];
        
        $this->TmpExpenseMaster->updateAll(array('HeadId'=>"'".$ExpenseHead."'",'SubHeadId'=>"'".$ExpenseSubHead."'"),array('Id'=>$Id));
        
        $Total = $workstation+$mannual+$revenue;
        
        $particular=array('1'=>'workstation','2'=>'mannual','3'=>'revenue');
        $part_ids = array('0');
        
        $parent_particular = $this->TmpExpenseParticular->find('first',array('fields'=>'Id','conditions'=>array('ExpenseTypeId'=>"$cost_center",'ExpenseTypeParent'=>"$unitId",'ExpenseId'=>$Id)));
        $parent = $parent_particular['TmpExpenseParticular']['Id'];
        foreach($particular as $k=>$v)
        {
            $mainArray = array(
                    'ExpenseId'=>$Id,
                    'ExpenseType'=>'Particular',
                    'ExpenseTypeId'=>$k,
                    'ExpenseTypeName'=>"$v",
                    'ExpenseTypeParent'=>$cost_center,
                    'BranchId'=>$branch,
                    'FinanceYear' =>"$financeYear",
                    'FinanceMonth' =>"$financeMonth",
                    'HeadId' =>"$ExpenseHead",
                    'SubHeadId' =>"$ExpenseSubHead",
                    'Parent' =>"$parent"
                );
            //updateSave($mainArray,$amountArr[$k],$updArr);
           $part_ids[] = $this->updateSave($mainArray,$amountArr[$k],array('Amount'=>$amountArr[$k],'AmountPercent'=>round($amountArr[$k]*100/$Total)),$Total,$unitId);               
        }  
        
        
        ///////////////////////////////////////////////////////////////////////////////////
        /////////////////////                                      ///////////////////////
        /////////////////////       For Cost Center Update         ///////////////////////
        ////////////////////                                       ///////////////////////
        //////////////////////////////////////////////////////////////////////////////////
        
        $costMaster = $this->CostCenterMaster->find('first',array('fields'=>'cost_center','conditions'=>array('Id'=>$cost_center,'active'=>'1')));
        $cost_centerName = $costMaster['CostCenterMaster']['cost_center'];
        
        $parent_unit = $this->TmpExpenseParticular->find('first',array('fields'=>'Id','conditions'=>array('ExpenseTypeId'=>"$unitId",'ExpenseId'=>$Id)));
        $parent = $parent_unit['TmpExpenseParticular']['Id'];
        if(!$unitId) $unitId=null;
        if(!$parent) $parent=null;
        $mainArray = array(
                    'ExpenseId'=>$Id,
                    'ExpenseType'=>'CostCenter',
                    'ExpenseTypeId'=>"$cost_center",
                    'ExpenseTypeName'=>"$cost_centerName",
                    'ExpenseTypeParent'=>$unitId,
                    'BranchId'=>$branch,
                    'FinanceYear' =>"$financeYear",
                    'FinanceMonth' =>"$financeMonth",
                    'HeadId' =>"$ExpenseHead",
                    'SubHeadId' =>"$ExpenseSubHead",
                    'Parent' =>$parent
                );
        //print_r($mainArray); exit;
        if($unitId) {$unitQuery="and ExpenseTypeParent='$unitId'";}
        $cost_parent = $this->updateSave($mainArray,$Total,array('Amount'=>$Total),$Total,$unitId);  
        
        $this->updatePercent($Id,'CostCenter',$ExpenseHead,$ExpenseSubHead,$unitQuery);
        
       $this->TmpExpenseParticular->updateAll(array('Parent'=>$cost_parent),array('Id'=>$part_ids));
       
       $CostSum = $this->TmpExpenseParticular->query("select sum(Amount) Total from tmp_expense_particular where ExpenseId='$Id' and ExpenseType='CostCenter' "
                . "and HeadId='$ExpenseHead' and SubHeadId='$ExpenseSubHead' and BranchId='$branch' and FinanceYear='$financeYear' and FinanceMonth='$financeMonth' $unitQuery");
       
        ///////////////////////////////////////////////////////////////////////////////////
        /////////////////////                                      ///////////////////////
        /////////////////////       For Unit Update                ///////////////////////
        ////////////////////                                       ///////////////////////
        //////////////////////////////////////////////////////////////////////////////////
       
       if($unitId)
       {
        
        $unitMaster = $this->Tbl_bgt_expenseunitmaster->find('first',array('fields'=>'ExpenseUnit','conditions'=>array('ExpenseunitID'=>$unitId)));
        $unitName = $unitMaster['Tbl_bgt_expenseunitmaster']['ExpenseUnit'];  
        
        $Total = $CostSum['0']['0']['Total'];
        $mainArray = array(
                    'ExpenseId'=>$Id,
                    'ExpenseType'=>'Unit',
                    'ExpenseTypeId'=>"$unitId",
                    'ExpenseTypeName'=>"$unitName",
                    'BranchId'=>$branch,
                    'FinanceYear' =>"$financeYear",
                    'FinanceMonth' =>"$financeMonth",
                    'HeadId' =>"$ExpenseHead",
                    'SubHeadId' =>"$ExpenseSubHead"
                );
        
        $unit_parent = $this->updateSave($mainArray,$Total,array('Amount'=>$Total),$Total);
        $this->updatePercent($Id,'Unit',$ExpenseHead,$ExpenseSubHead,'');
        $this->TmpExpenseParticular->updateAll(array('Parent'=>$unit_parent),array('Id'=>$cost_parent));
         $updateTotal = $this->TmpExpenseParticular->query("update tmp_expense_master set Amount='$Total' where Id='$Id' "
                . "and HeadId='$ExpenseHead' and SubHeadId='$ExpenseSubHead' and BranchId='$branch' and FinanceYear='$financeYear' and FinanceMonth='$financeMonth'");
       }
       
       $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$Id' and ExpenseType='Particular'");
       $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount']),array('Id'=>$Id));
       
       echo  preg_replace('([^0-9a-zA-Z])', ' ', $cost_centerName);
         exit;
    } 
    
    public function expense_save_tmp()
    {
        $this->layout="ajax";
        if($this->request->is('POST'))
        {
           $ExpenseId = $this->request->data['ExpenseEntries']['id'];
           $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
           $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);

           $PaymentFile = null;
           if(!empty($this->request->data['ExpenseEntries']['PaymentFile']['name']))
           {
                $file = $this->request->data['ExpenseEntries']['PaymentFile'];
                $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                move_uploaded_file($file['tmp_name'],WWW_ROOT."/expense_file/".$ExpenseId.$file['name']);
                $PaymentFile =addslashes($ExpenseId.$file['name']);
           }
           $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
           $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
           $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
           $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
           $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='$ExpenseId' AND tmp2.Active='1' AND tmp2.Id !='$ExpenseId' AND tmp2.Active='1'");
           $checkFlag = false;
           if(!$TotalAmount['0']['0']['Amount'])
           {    $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Expense not Saved of 0 Amount Please Try Again</font>"));
           }
           else if ($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN expense_master em ON tmp.Branch = em.Branch AND
                tmp.FinanceYear = em.FinanceYear AND tmp.FinanceMonth = em.FinanceMonth AND tmp.HeadId = em.HeadId AND tmp.SubHeadId =em.SubHeadId WHERE tmp.id='$ExpenseId'"))
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist</font>")); 
           }
           else if (!$existCheck)
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
           }
           else if($checkSubHead['0']['0']['counter']>1)
           {   $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
           }
           else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
           {   $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
           }
           else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
           {
                $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                if(intval($unitAmountCheck) != intval($costAmountCheck))
                {
                    $checkFlag = true;
                    $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                }
           }
           
           if($checkFlag)
           {
               $this->TmpExpenseMaster->query("delete from tmp_expense_master where Id = '$ExpenseId'");
               $this->redirect(array('controller'=>'ExpenseEntries','action'=>'initial_branch')); 
           }
           $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",
               'Methodology'=>"'".$Methodology."'",'PaymentFile'=>"'".$PaymentFile."'",'Active'=>1),array('Id'=>$ExpenseId));
           
           $this->Session->setFlash(__("Expense Has Been Saved and Moved to BM Bucket"));
           $this->redirect(array('controller'=>'ExpenseEntries','action'=>'initial_branch'));
        }
    }
        
    public function get_his_check()
    {
        $ExpenseId = $this->request->data['ExpenseId'];
        
        if($this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseId'=>$ExpenseId))))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
        exit;
    }
    
    public function get_his_check2()
    {
        $ExpenseId = $this->request->data['ExpenseId'];
        $branch = $this->request->data['branch'];
        $financeYear = $this->request->data['financeYear'];
        $financeMonth = $this->request->data['financeMonth'];
        $ExpenseHead = $this->request->data['ExpenseHead'];
        $ExpenseSubHead = $this->request->data['ExpenseSubHead'];
        
        if($this->ExpenseMaster->find('first',array('conditions'=>array('BranchId'=>$branch,'FinanceYear'=>$financeYear,
            'FinanceMonth'=>$financeMonth,'HeadId'=>$ExpenseHead,'SubHeadId'=>$ExpenseSubHead))))
        {
            echo '1';
        }
        else if($this->TmpExpenseMaster->find('first',array('conditions'=>array('BranchId'=>$branch,'FinanceYear'=>$financeYear,
            'FinanceMonth'=>$financeMonth,'HeadId'=>$ExpenseHead,'SubHeadId'=>$ExpenseSubHead,'Active'=>'1','not'=>array('Id'=>$ExpenseId)))))
        {
            echo '3';
        } 
        else if($this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseId'=>$ExpenseId,'not'=>array('HeadId'=>$ExpenseHead),'not'=>array('SubHeadId'=>$ExpenseSubHead)))))
        {
            echo '2';
        }
        else
        {
            echo '0';
        }
        exit;
    }
    
    
    public function get_old_delete()
    {
        $ExpenseId = $this->request->data['ExpenseId'];
        $userid = $this->Session->read('userid');
        $this->TmpExpenseParticular->query("insert into expense_particular3(ExpenseId,ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,"
                . "FinanceMonth,HeadId,SubHeadId,AmountPercent,Amount,Parent,ExpenseStatus,userid)  select ExpenseId,ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,"
                . "FinanceMonth,HeadId,SubHeadId,AmountPercent,Amount,Parent,ExpenseStatus,'$userid' from tmp_expense_particular where ExpenseId='$ExpenseId'");
        if($this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId)))
        {
          echo '1';
        }
        else
        {
          echo '0';
        }
        exit;
    }
 
    public function view_bm()
    {
        $this->layout="home";
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {    $condition=array('active'=>1);    }
        else
        {    $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));    }
        
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        
        $qry = "Where Active='1' and EXISTS (SELECT expenseId FROM tmp_expense_particular emp WHERE emp.ExpenseId=em.Id)";
        
        
        if($role=='admin')
        {
            //$qry .=" and em.active=1";
        }
        else
        {
            $qry .=" and em.Branch='".$this->Session->read("branch_name")."'";
        }
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data['Addbranch'];
            //print_r($data); exit;
        }
        else if($this->request->is('GET')) 
        {
            $qrm = explode('&',base64_decode($this->params->query['qry']));
            foreach($qrm as $q)
            {
                $qa = explode('=',$q);
                $data[$qa[0]] = $qa[1];
            }
        }
        if(!empty($data['BranchId']))
        {
            $qry .= " and em.BranchId='".$data['BranchId']."'";
            $query .= "BranchId=".$data['BranchId']."&";
            $this->set('BranchId',$data['BranchId']);
        }

        if(!empty($data['FinanceYear']))
        {
            $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            $query .= "FinanceYear=".$data['FinanceYear']."&";
            $this->set('FinanceYear',$data['FinanceYear']);
        }

        if(!empty($data['FinanceMonth']))
        {
            $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            $query .= "FinanceMonth=".$data['FinanceMonth']."&";
            $this->set('FinanceMonth',$data['FinanceMonth']);
        }
        $qry .= " and Approve1 is null and Approve2 is null and Approve3 is null ";
       
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        $this->set('data',$data);
         $this->set('qry',  base64_encode(trim($query,'&')));
    }
    
    public function bm_multi_final_save()
    {
        $this->layout="ajax";
                
        if($this->request->is('POST'))
        {
            foreach($this->request->data['check'] as $ExpenseId)
            {
                $this->TmpExpenseMaster->updateAll(array('Approve1'=>1,'ApproveDate1'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
            }
            $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to VH Bucket</font>"));
        }
        $this->redirect(array("action"=>"view_bm",'?'=>array('qry'=>$this->request->data['ExpenseEntries']['qry'])));
    }
    
    public function edit_tmp_bm()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        $this->set('qry',$this->params->query['qry']);
        $req = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$id)));
        $req = $req['TmpExpenseMaster'];

        $this->set('id',$id);
        $this->set('data',$req);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))); 
        $this->set('Subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$req['HeadId'])))); 

        $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
        if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
            'conditions'=>array('HeadingId'=>$req['HeadId'],'SubHeadingID'=>$req['SubHeadId'],'Branch'=>$req['Branch'],'Status'=>1))))
        {
            $html .='<tr><th>Sr No</th><th>Expense Unit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            $div="unit";
            foreach($unit as $k=>$v)
            {
                $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                $html .='<tr onClick="get_costcenter_breakup('."'".$req['Branch']."',".$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="unit.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "perunit'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="unit.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountunit'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $unitMasterIds[]=$k;
            }
            $field='<input type="hidden" id="unitmaster" name="unitmaster" value="'.implode(',',$unitMasterIds).'">';
        }

        else
        {
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'],'active'=>'1')));
          $html .='<tr><th>Sr No</th><th>Cost Center</th><th>%</th><th>Amount</th></tr></thead><tbody>';
          $div="costcenter";
          foreach($costMaster as $k=>$v)
          {
              $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
              $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'CostCenter','ExpenseTypeId'=>$k)));
              if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                //$amountIn = $this->TmpExpenseMaster->find('first',array('fields'=>'Amount','conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="costcenter.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="costcenter.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $costMasterIds[]=$k;
          } 
          $field='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
        }
        $html .='</tbody></table>'.$field;
        
        $this->set('div',$div);
        $this->set('html',$html);
        $this->set('qry',$this->params->query['qry']);    
    }
    
    public function bm_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {
            //print_r($this->request->data); exit;
            $qry = $this->request->data['ExpenseEntries']['qry'];
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
            //print_r($Methodology); exit;
            if(!empty($this->request->data['ExpenseEntries']['PaymentFile']['name']))
            {
               $file = $this->request->data['ExpenseEntries']['PaymentFile'];
               $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                move_uploaded_file($file['tmp_name'],WWW_ROOT."/expense_file/".$ExpenseId.$file['name']);
                $PaymentFile =addslashes($ExpenseId.$file['name']);
                $this->TmpExpenseMaster->updateAll(array('PaymentFile'=>"'".$PaymentFile."'"),array('Id'=>$ExpenseId));
            } 
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];     
            $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
            $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
            $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
            $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
            $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='$ExpenseId' AND tmp2.Active='1' AND tmp2.Id !='$ExpenseId' AND tmp2.Active='1'");
            $checkFlag = false;
               
            if(!$TotalAmount['0']['0']['Amount'])
            {
                $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
            }
            else if (!$existCheck)
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
           }
           else if($checkSubHead['0']['0']['counter']>1)
           {   $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
           }
            else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
            }
            else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
            {
                 $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                 if(intval($unitAmountCheck) != intval($costAmountCheck))
                 {
                     $checkFlag = true;
                     $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                 }
            }
           
            if($checkFlag)
            {
               $this->TmpExpenseMaster->query("delete from tmp_expense_master where Id = '$ExpenseId'");
               $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_bm','?'=>array('qry'=>$qry))); 
            }
            else
            {
                $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve1'=>1,'ApproveDate1'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
                $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to VH Bucket</font>"));
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view_bm','?'=>array('qry'=>$qry))); 
            }
        }
        $this->redirect(array("action"=>"view_bm",'?'=>array('qry'=>$qry)));
    }
    
    public function view_vh()
    {
        $this->layout="home";
        
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {    $condition=array('active'=>1);    }
        else
        {    $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));    }
        
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        
        $qry = "Where 1=1 ";
        
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {
            //$qry .=" and em.active=1";
        }
        else
        {
            $qry .=" and em.Branch='".$this->Session->read("branch_name")."'";
        }
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data['Addbranch'];
            //print_r($data); exit;
        }
        else if($this->request->is('GET')) 
        {
            $qrm = explode('&',base64_decode($this->params->query['qry']));
            foreach($qrm as $q)
            {
                $qa = explode('=',$q);
                $data[$qa[0]] = $qa[1];
            }
        }
        if(!empty($data['BranchId']))
        {
            $qry .= " and em.BranchId='".$data['BranchId']."'";
            $query .= "BranchId=".$data['BranchId']."&";
            $this->set('BranchId',$data['BranchId']);
        }

        if(!empty($data['FinanceYear']))
        {
            $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            $query .= "FinanceYear=".$data['FinanceYear']."&";
            $this->set('FinanceYear',$data['FinanceYear']);
        }

        if(!empty($data['FinanceMonth']))
        {
            $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            $query .= "FinanceMonth=".$data['FinanceMonth']."&";
            $this->set('FinanceMonth',$data['FinanceMonth']);
        }
        
        $qry .= " and em.Active=1 and em.Approve1=1 and em.Approve2 is null and em.Approve3 is null and EXISTS (SELECT expenseId FROM tmp_expense_particular emp WHERE emp.ExpenseId=em.Id)";
        
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        
        $this->set('data',$data);
        $this->set('qry',  base64_encode(trim($query,'&')));
    }
    
    public function vh_multi_final_save()
    {
        $this->layout="ajax";
        if($this->request->is('POST'))
        {
           foreach($this->request->data['check'] as $ExpenseId)
            {
                $this->TmpExpenseMaster->updateAll(array('Approve2'=>1,'ApproveDate2'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
            }
            $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to FH Bucket</font>"));
        }
        $this->redirect(array("action"=>"view_vh",'?'=>array('qry'=>$this->request->data['ExpenseEntries']['qry'])));
    }
    public function edit_tmp_vh()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        $req = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$id)));
        $req = $req['TmpExpenseMaster'];

        $this->set('id',$id);
        $this->set('data',$req);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))); 
        $this->set('Subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$req['HeadId'])))); 

        $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
        if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
            'conditions'=>array('HeadingId'=>$req['HeadId'],'SubHeadingID'=>$req['SubHeadId'],'Branch'=>$req['Branch'],'Status'=>1))))
        {
            $html .='<tr><th>Sr No</th><th>Expense Unit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            $div="unit";
            foreach($unit as $k=>$v)
            {
                $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                $html .='<tr onClick="get_costcenter_breakup('."'".$req['Branch']."',".$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="unit.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "perunit'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="unit.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountunit'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $unitMasterIds[]=$k;
            }
            $field='<input type="hidden" id="unitmaster" name="unitmaster" value="'.implode(',',$unitMasterIds).'">';
        }

        else
        {
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'],'active'=>'1')));
          $html .='<tr><th>Sr No</th><th>Cost Center</th><th>%</th><th>Amount</th></tr></thead><tbody>';
          $div="costcenter";
          foreach($costMaster as $k=>$v)
          {
              $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
              $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'CostCenter','ExpenseTypeId'=>$k)));
              if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                //$amountIn = $this->TmpExpenseMaster->find('first',array('fields'=>'Amount','conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="costcenter.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="costcenter.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $costMasterIds[]=$k;
          } 
          $field='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
        }
        $html .='</tbody></table>'.$field;
        
        $this->set('div',$div);
        $this->set('html',$html);
        $this->set('qry',$this->params->query['qry']);
    }
    
    public function vh_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            $qry = $this->request->data['ExpenseEntries']['qry'];
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
             if(!empty($this->request->data['ExpenseEntries']['PaymentFile']['name']))
               {
                   $file = $this->request->data['ExpenseEntries']['PaymentFile'];
                   $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                    move_uploaded_file($file['tmp_name'],WWW_ROOT."/expense_file/".$ExpenseId.$file['name']);
                    $PaymentFile =addslashes($ExpenseId.$file['name']);
                    $this->TmpExpenseMaster->updateAll(array('PaymentFile'=>"'".$PaymentFile."'"),array('Id'=>$ExpenseId));
               } 
               $ExpenseId = $this->request->data['ExpenseEntries']['id'];
               
               $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");               
               $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
            $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
            $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
            $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='$ExpenseId' AND tmp2.Active='1' AND tmp2.Id !='$ExpenseId' AND tmp2.Active='1'");
            $checkFlag = false;
               
            if(!$TotalAmount['0']['0']['Amount'])
            {
                $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
            }
            else if (!$existCheck)
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
           }
            else if($checkSubHead['0']['0']['counter']>1)
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
            }
            else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
            }
            else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
            {
                 $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                 if(intval($unitAmountCheck) != intval($costAmountCheck))
                 {
                     $checkFlag = true;
                     $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                 }
            }
           
            if($checkFlag)
            {
                $this->TmpExpenseMaster->query("delete from tmp_expense_particular where Id = '$ExpenseId'");
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_vh','?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
            }
            else
            {
                $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve2'=>1,'ApproveDate2'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
                $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to FH Bucket</font>"));
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view_vh','?'=>array('qry'=>$qry))); 
            }
        }
        $this->redirect(array("action"=>"view_vh",'?'=>array('qry'=>$qry)));
    }
    
    public function view_fh()
    {
        $this->layout="home";
        $this->layout="home";
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {    $condition=array('active'=>1);    }
        else
        {    $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));    }
        
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        $roles=explode(',',$this->Session->read("page_access"));
        
        $qry = "Where 1=1 ";
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {
            //$qry .=" and em.active=1";
        }
        else
        {
            $qry .=" and em.Branch='".$this->Session->read("branch_name")."'";
        }
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data['Addbranch'];
            //print_r($data); exit;
        }
        else if($this->request->is('GET')) 
        {
            $qrm = explode('&',base64_decode($this->params->query['qry']));
            foreach($qrm as $q)
            {
                $qa = explode('=',$q);
                $data[$qa[0]] = $qa[1];
            }
        }
        if(!empty($data['BranchId']))
        {
            $qry .= " and em.BranchId='".$data['BranchId']."'";
            $query .= "BranchId=".$data['BranchId']."&";
            $this->set('BranchId',$data['BranchId']);
        }

        if(!empty($data['FinanceYear']))
        {
            $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            $query .= "FinanceYear=".$data['FinanceYear']."&";
            $this->set('FinanceYear',$data['FinanceYear']);
        }

        if(!empty($data['FinanceMonth']))
        {
            $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            $query .= "FinanceMonth=".$data['FinanceMonth']."&";
            $this->set('FinanceMonth',$data['FinanceMonth']);
        }
         $qry .= " and em.Active=1 and em.Approve1=1 and em.Approve2=1 and em.Approve3 is null and EXISTS (SELECT expenseId FROM tmp_expense_particular emp WHERE emp.ExpenseId=em.Id)";
        
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        
        $this->set('data',$data);
        $this->set('qry',  base64_encode(trim($query,'&')));
    }
    
    public function fh_multi_final_save()
    {
        $this->layout="ajax";
        $roles=explode(',',$this->Session->read("page_access"));
        $qry = $this->request->data['ExpenseEntries']['qry'];
        if($this->request->is('POST'))
        {
            
            
            foreach($this->request->data['check'] as $ExpenseId)
            {
            $dataSource = $this->TmpExpenseParticular->getDataSource();
            $dataSource ->begin();
             
            $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
            $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
            $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
            $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
            $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='$ExpenseId' AND tmp2.Active='1' AND tmp2.Id !='$ExpenseId' AND tmp2.Active='1'");
            $checkFlag = false;
               
            if(!$TotalAmount['0']['0']['Amount'])
            {
                $checkFlag = true; 
                $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
            }
            else if (!$existCheck)
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
           }
            else if($checkSubHead['0']['0']['counter']>1)
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
            }
            else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
            }
            else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
            {
                 $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                 if(intval($unitAmountCheck) != intval($costAmountCheck))
                 {
                     $checkFlag = true;
                     $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                 }
            }
           
            if($checkFlag)
            {
                $this->TmpExpenseMaster->query("delete from tmp_expense_particular where ExpenseId = '$ExpenseId'");
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_vh','?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
            }
            
            unset($TotalAmount);
            $ExpenseMaster = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
            
            if($ExpenseMaster['TmpExpenseMaster']['expense_status']=='New')
            {
                $NewExpenseMaster = Hash::Remove($ExpenseMaster['TmpExpenseMaster'],'Id');
            }
            else
            {
                $this->ExpenseMaster->query("delete from expense_master where Id = '$ExpenseId'");
                $this->ExpenseParticular->query("delete from expense_particular where ExpenseId = '$ExpenseId'");
                $NewExpenseMaster = $ExpenseMaster['TmpExpenseMaster'];
            }
            $this->ExpenseMaster->create();
            if(!$this->ExpenseMaster->save($NewExpenseMaster))
            {
                $dataSource->rollback();
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            $NewExpenseId = $this->ExpenseMaster->getLastInsertId();
                        
            $UnitMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Unit')));

            foreach($UnitMaster as $unit)
            {
               $oldUnitId = $unit['TmpExpenseParticular']['Id'];
               $newUnit = Hash::Remove($unit['TmpExpenseParticular'],'Id');
               $newUnit = Hash::Remove($newUnit,'ExpenseId');
               $newUnit['ExpenseId'] = $NewExpenseId;

               if(!$this->ExpenseParticular->saveAll($newUnit))
               {
                  $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
               }
               $NewUnitId = $this->ExpenseParticular->getLastInsertId();

               if(!empty($NewUnitId))
               {
                   if(!$this->TmpExpenseParticular->updateAll(array('Parent'=>$NewUnitId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter','Parent'=>$oldUnitId)))
                   {
                        $dataSource->rollback(); 
                        $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                        $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));  
                   }
               } 
            }

            $CostMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter')));

            foreach($CostMaster as $cost)
            {
               $oldCostId = $cost['TmpExpenseParticular']['Id'];
               $newCost = Hash::Remove($cost['TmpExpenseParticular'],'Id');
               $newCost = Hash::Remove($newCost,'ExpenseId');
               $newCost['ExpenseId'] = $NewExpenseId;

               if(!$this->ExpenseParticular->saveAll($newCost))
               {
                   $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
               }
               $NewCostId = $this->ExpenseParticular->getLastInsertId();
               //print_r($NewUnitId);
                //unset($this->ExpenseParticular);
              
               if(!empty($NewCostId))
               {
                   if(!$this->TmpExpenseParticular->updateAll(array('Parent'=>$NewCostId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Particular','Parent'=>$oldCostId)))
                   {
                       $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
                   }
               }
            }
            
            $this->TmpExpenseParticular->query("INSERT INTO expense_particular (ExpenseId,ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,
            HeadId,SubHeadId,AmountPercent,Amount,Parent)
            SELECT '$NewExpenseId',ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,HeadId,SubHeadId,AmountPercent,
            Amount,Parent FROM `tmp_expense_particular`
            WHERE ExpenseId = '$ExpenseId' AND ExpenseType='Particular'");
                    
            if(!$this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseId'=>$ExpenseId))))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }

           $TotalAmount = $this->ExpenseParticular->query("select sum(Amount) `Amount` from expense_particular where ExpenseId='$NewExpenseId' and ExpenseType='Particular'");
           
           if(!$this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve3'=>3,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$ExpenseId)))
           {
               $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
           }
           
           if(!$this->ExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve1'=>1,'Approve2'=>1,'Approve3'=>1,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$NewExpenseId)))
           {
               $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
           }
          
           if($this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId))) || $this->ExpenseMaster->find('first',array('conditions'=>array('Id'=>$NewExpenseId))))
           {
            if(!$this->TmpExpenseMaster->deleteAll(array('Id'=>$ExpenseId)))
            {
                $dataSource->rollback();
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            if(!$this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId)))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            if(!$this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$NewExpenseId)))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
           }
           unset($this->TmpExpenseMaster);unset($this->ExpenseMaster);unset($this->TmpExpenseParticular);unset($this->ExpenseParticular);
           $dataSource->commit();               
        }
           $this->Session->setFlash(__("<font color='green'>Expense has been Approved Successfully</font>")); 
    }
        $this->redirect(array("action"=>"view_fh",'?'=>array('qry'=>$qry)));
    }
    public function edit_tmp_fh()
    {
        $this->layout="home";
        $id = $this->params->query['id'];
        $req = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$id)));
        $req = $req['TmpExpenseMaster'];

        $this->set('id',$id);
        $this->set('data',$req);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))); 
        $this->set('Subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$req['HeadId'])))); 

        $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
        if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
            'conditions'=>array('HeadingId'=>$req['HeadId'],'SubHeadingID'=>$req['SubHeadId'],'Branch'=>$req['Branch'],'Status'=>1))))
        {
            $html .='<tr><th>Sr No</th><th>Expense Unit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            $div="unit";
            foreach($unit as $k=>$v)
            {
                $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                $html .='<tr onClick="get_costcenter_breakup('."'".$req['Branch']."',".$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="unit.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "perunit'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="unit.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountunit'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $unitMasterIds[]=$k;
            }
            $field='<input type="hidden" id="unitmaster" name="unitmaster" value="'.implode(',',$unitMasterIds).'">';
        }

        else 
        {
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'],'active'=>'1')));
          $html .='<tr><th>Sr No</th><th>Cost Center</th><th>%</th><th>Amount</th></tr></thead><tbody>';
          $div="costcenter";
          foreach($costMaster as $k=>$v)
          {
              $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
              $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'CostCenter','ExpenseTypeId'=>$k)));
              if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                //$amountIn = $this->TmpExpenseMaster->find('first',array('fields'=>'Amount','conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="costcenter.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="costcenter.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $costMasterIds[]=$k;
          } 
          $field='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
        }
        $html .='</tbody></table>'.$field;
        
        $this->set('div',$div);
        $this->set('html',$html);
        $this->set('qry',$this->params->query['qry']);   
    }
    
    public function fh_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $qry = $this->request->data['ExpenseEntries']['qry'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
            
            $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
            $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
            $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
            $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
             $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='$ExpenseId' AND tmp2.Active='1' AND tmp2.Id !='$ExpenseId' AND tmp2.Active='1'");
            $checkFlag = false;
               
            if(!$TotalAmount['0']['0']['Amount'])
            {
                $checkFlag = true; 
                $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
            }
            else if (!$existCheck)
           {
              $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
           }
            else if($checkSubHead['0']['0']['counter']>1)
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
            }
            else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
            }
            else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
            {
                 $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                 if(intval($unitAmountCheck) != intval($costAmountCheck))
                 {
                     $checkFlag = true;
                     $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                 }
            }
           
            if($checkFlag)
            {  
                $this->TmpExpenseMaster->query("delete from tmp_expense_particular where ExpenseId = '$ExpenseId'");
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_vh','?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
            }
            
            unset($TotalAmount);
            $ExpenseMaster = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
            
            if($ExpenseMaster['TmpExpenseMaster']['expense_status']=='New')
            {
                $NewExpenseMaster = Hash::Remove($ExpenseMaster['TmpExpenseMaster'],'Id');
            }
            else
            {
                $this->ExpenseMaster->query("delete from expense_master where Id = '$ExpenseId'");
                $this->ExpenseParticular->query("delete from expense_particular where ExpenseId = '$ExpenseId'");
                $NewExpenseMaster = $ExpenseMaster['TmpExpenseMaster'];
            }
            $dataSource = $this->TmpExpenseParticular->getDataSource();
            $dataSource ->begin();
            if(!$this->ExpenseMaster->save($NewExpenseMaster))
            {
                $dataSource->rollback();
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            $NewExpenseId = $this->ExpenseMaster->getLastInsertId();
                        
            $UnitMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Unit')));

            foreach($UnitMaster as $unit)
            {
               $oldUnitId = $unit['TmpExpenseParticular']['Id'];
               $newUnit = Hash::Remove($unit['TmpExpenseParticular'],'Id');
               $newUnit = Hash::Remove($newUnit,'ExpenseId');
               $newUnit['ExpenseId'] = $NewExpenseId;

               if(!$this->ExpenseParticular->saveAll($newUnit))
               {
                  $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
               }
               $NewUnitId = $this->ExpenseParticular->getLastInsertId();

               if(!empty($NewUnitId))
               {
                   if(!$this->TmpExpenseParticular->updateAll(array('Parent'=>$NewUnitId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter','Parent'=>$oldUnitId)))
                   {
                        $dataSource->rollback(); 
                        $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                        $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));  
                   }
               } 
            }

            $CostMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter')));

            foreach($CostMaster as $cost)
            {
               $oldCostId = $cost['TmpExpenseParticular']['Id'];
               $newCost = Hash::Remove($cost['TmpExpenseParticular'],'Id');
               $newCost = Hash::Remove($newCost,'ExpenseId');
               $newCost['ExpenseId'] = $NewExpenseId;

               if(!$this->ExpenseParticular->saveAll($newCost))
               {
                   $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
               }
               $NewCostId = $this->ExpenseParticular->getLastInsertId();
               //print_r($NewUnitId);
                //unset($this->ExpenseParticular);
               unset($this->ExpenseParticular);
               if(!empty($NewCostId))
               {
                   if(!$this->TmpExpenseParticular->updateAll(array('Parent'=>$NewCostId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Particular','Parent'=>$oldCostId)))
                   {
                       $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
                   }
               }
            }
            
            $this->TmpExpenseParticular->query("INSERT INTO expense_particular (ExpenseId,ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,
            HeadId,SubHeadId,AmountPercent,Amount,Parent)
            SELECT '$NewExpenseId',ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,HeadId,SubHeadId,AmountPercent,
            Amount,Parent FROM `tmp_expense_particular`
            WHERE ExpenseId = '$ExpenseId' AND ExpenseType='Particular'");
                    
            if(!$this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseId'=>$ExpenseId))))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }

           $TotalAmount = $this->ExpenseParticular->query("select sum(Amount) `Amount` from expense_particular where ExpenseId='$NewExpenseId' and ExpenseType='Particular'");
           
           if(!$this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve3'=>3,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$ExpenseId)))
           {
               $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
           }
           
           if(!$this->ExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve1'=>1,'Approve2'=>1,'Approve3'=>1,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$NewExpenseId)))
           {
               $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
           }
           if(!empty($this->request->data['ExpenseEntries']['PaymentFile']['name']))
           {
               $file = $this->request->data['ExpenseEntries']['PaymentFile'];
               $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                move_uploaded_file($file['tmp_name'],WWW_ROOT."/expense_file/".$ExpenseId.$file['name']);
                $PaymentFile =addslashes($ExpenseId.$file['name']);
                $this->TmpExpenseMaster->updateAll(array('PaymentFile'=>"'".$PaymentFile."'"),array('Id'=>$ExpenseId));
                $this->ExpenseMaster->updateAll(array('PaymentFile'=>"'".$PaymentFile."'"),array('Id'=>$NewExpenseId));

           }
           if($this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId))) || $this->ExpenseMaster->find('first',array('conditions'=>array('Id'=>$NewExpenseId))))
           {
            if(!$this->TmpExpenseMaster->deleteAll(array('Id'=>$ExpenseId)))
            {
                $dataSource->rollback();
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            if(!$this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId)))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
            if(!$this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$NewExpenseId)))
            {
                $dataSource->rollback(); 
                 $this->Session->setFlash(__("<font color='green'>Expense has been Not Saved Please Try Again</font>"));
                $this->redirect(array("action"=>"edit_tmp_fh",'?'=>array('id'=>$ExpenseId,'qry'=>$qry)));
            }
           }
           $dataSource->commit();
           $this->Session->setFlash(__("<font color='green'>Expense has been Approved Successfully</font>"));               
        }
        $this->redirect(array("action"=>"view_fh",'?'=>array('qry'=>$qry)));
    }
    
    public function view()
    {
        $this->layout="home";
        
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        
        $qry = "Where em.Active=1 and em.Approve1 is null ";
        $role = $this->Session->read('role');
        
        if($role=='admin')
        {
            //$qry .=" and em.active=1";
        }
        else
        {
            $qry .=" and em.Branch='".$this->Session->read("branch_name")."'";
        }
        
        
        if($this->request->is('POST'))
        {
            $data = $this->request->data['Addbranch'];
        }
        else if($this->request->is('GET')) 
        {
            $qrm = explode('&',base64_decode($this->params->query['qry']));
            foreach($qrm as $q)
            {
                $qa = explode('=',$q);
                $data[$qa[0]] = $qa[1];
            }
        }
        if(!empty($data['BranchId']))
        {
            $qry .= " and em.BranchId='".$data['BranchId']."'";
            $query .= "BranchId=".$data['BranchId']."&";
            $this->set('BranchId',$data['BranchId']);
        }

        if(!empty($data['FinanceYear']))
        {
            $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            $query .= "FinanceYear=".$data['FinanceYear']."&";
            $this->set('FinanceYear',$data['FinanceYear']);
        }

        if(!empty($data['FinanceMonth']))
        {
            $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            $query .= "FinanceMonth=".$data['FinanceMonth']."&";
            $this->set('FinanceMonth',$data['FinanceMonth']);
        }
        
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,
IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry ORDER BY em.HeadId");
        $this->set('qry',  base64_encode(trim($query,'&')));
        $this->set('data',$data);
    }
    
    public function edit_tmp()
    {
        $this->layout="home";
        //print_r($this->params->query); exit;
        $id = $this->params->query['id'];
        
        $req = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$id)));
        $req = $req['TmpExpenseMaster'];

        $this->set('id',$id);
        $this->set('data',$req);
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc')))); 
        $this->set('Subhead',$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),'conditions'=>array('HeadingId'=>$req['HeadId'])))); 

        $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
        if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
            'conditions'=>array('HeadingId'=>$req['HeadId'],'SubHeadingID'=>$req['SubHeadId'],'Branch'=>$req['Branch'],'Status'=>1))))
        {
            $html .='<tr><th>Sr No</th><th>Expense Unit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            $div="unit";
            foreach($unit as $k=>$v)
            {
                $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                $html .='<tr onClick="get_costcenter_breakup('."'".$req['Branch']."',".$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="unit.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "perunit'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="unit.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountunit'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $unitMasterIds[]=$k;
            }
            $field='<input type="hidden" id="unitmaster" name="unitmaster" value="'.implode(',',$unitMasterIds).'">';
        }

        else
        {
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'],'active'=>'1')));
          $html .='<tr><th>Sr No</th><th>Cost Center</th><th>%</th><th>Amount</th></tr></thead><tbody>';
          $div="costcenter";
          foreach($costMaster as $k=>$v)
          {
              $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
              $amountIn = $this->TmpExpenseParticular->find('first',array('fields'=>array('Amount','AmountPercent'),'conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'CostCenter','ExpenseTypeId'=>$k)));
              if(!empty($amountIn)) {$amountPercent = $amountIn['TmpExpenseParticular']['AmountPercent'].'%';} else {$amountPercent='';}
                //$amountIn = $this->TmpExpenseMaster->find('first',array('fields'=>'Amount','conditions'=>array('ExpenseId'=>$id,'ExpenseType'=>'Unit','ExpenseTypeId'=>$k)));
                $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                $html .='<td>'.'<input type="text" name="costcenter.amountpercent'.$k.'" value="'.$amountPercent.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                $html .='<td>'.'<input type="text" name="costcenter.amount'.$k.'" value="'.$amountIn['TmpExpenseParticular']['Amount'].'" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
                $html .='</tr>';
                $costMasterIds[]=$k;
          } 
          $field='<input type="hidden" id="costmaster" name="costmaster" value="'.implode(',',$costMasterIds).'">';
        }
        $html .='</tbody></table>'.$field;
        
        $this->set('div',$div);
        $this->set('html',$html);
        $this->set('qry',$this->params->query['qry']);
            
    }
    
    public function expense_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $qry = $this->request->data['ExpenseEntries']['qry'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
            
           if(!empty($this->request->data['ExpenseEntries']['PaymentFile']['name']))
           {
               $file = $this->request->data['ExpenseEntries']['PaymentFile'];
               $file['name'] = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['name']);
                move_uploaded_file($file['tmp_name'],WWW_ROOT."/expense_file/".$ExpenseId.$file['name']);
                $PaymentFile =addslashes($ExpenseId.$file['name']);
                $this->TmpExpenseMaster->updateAll(array('PaymentFile'=>"'".$PaymentFile."'"),array('Id'=>$ExpenseId));
           }
           
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];     
            $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
            $checkSubHead = $this->TmpExpenseParticular->query("SELECT COUNT(1) counter FROM (SELECT * FROM tmp_expense_particular WHERE expenseid = '$ExpenseId' GROUP BY headId,subheadid)AS tab");
            $partAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Particular' AND expenseId='$ExpenseId'");
            $costAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='CostCenter' AND expenseId='$ExpenseId'");
            $existCheck = $this->TmpExpenseMaster->query("SELECT COUNT(1) FROM tmp_expense_master tmp1 INNER JOIN tmp_expense_master tmp2 ON tmp1.Branch = tmp2.Branch AND  
tmp1.FinanceYear = tmp2.FinanceYear AND tmp1.FinanceMonth = tmp2.FinanceMonth AND tmp1.HeadId = tmp2.HeadId AND 
tmp1.SubHeadId =tmp2.SubHeadId WHERE tmp1.id='' AND tmp2.Active='1' AND tmp2.Id !='' AND tmp2.Active='1'");
            
            $checkFlag = false;
            if(!$TotalAmount['0']['0']['Amount'])
            {    $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Expense not Saved of 0 Amount Please Try Again</font>"));
            }
            else if ($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN expense_master em ON tmp.Branch = em.Branch AND
                tmp.FinanceYear = em.FinanceYear AND tmp.FinanceMonth = em.FinanceMonth AND tmp.HeadId = em.HeadId AND tmp.SubHeadId =em.SubHeadId WHERE tmp.id='$ExpenseId'"))
            {
              $checkFlag = true;
              $this->Session->setFlash(__("<font color='green'>Budget Already Exist</font>")); 
            }
            else if (!$existCheck)
            {
              $checkFlag = true;
              $this->Session->setFlash(__("<font color='green'>Budget Already Exist in Approval Bucket</font>")); 
            }
            else if($checkSubHead['0']['0']['counter']>1)
            {   $checkFlag = true;
               $this->Session->setFlash(__("<font color='green'>Only Single SubHead Entry is Required</font>"));
            }
            else if(intval($partAmountCheck['0']['0']['Amount'])!= intval($costAmountCheck['0']['0']['Amount']))
            {   $checkFlag = true;
                $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
            }
            else if($this->TmpExpenseMaster->query("SELECT * FROM tmp_expense_master tmp INNER JOIN tbl_expenseunitmaster unit ON tmp.Branch = unit.Branch WHERE tmp.Id='$ExpenseId' LIMIT 1"))
            {
                $unitAmountCheck = $this->TmpExpenseMaster->query("SELECT SUM(amount) Amount FROM tmp_expense_particular WHERE expenseType='Unit' AND expenseId='$ExpenseId'");
                if(intval($unitAmountCheck) != intval($costAmountCheck))
                {
                    $checkFlag = true;
                    $this->Session->setFlash(__("<font color='green'>Amount Not Matched. Please Do All Entries Once Again</font>"));
                }
            }
            else
            {
                $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to BM Bucket</font>"));
                $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'"),array('Id'=>$ExpenseId));
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view','?'=>array('qry'=>$qry)));
            }
               
            if($checkFlag)
            {
                $this->TmpExpenseMaster->query("delete from tmp_expense_particular where ExpenseId = '$ExpenseId'");
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp','?'=>array('id'=>$ExpenseId,'qry'=>$qry))); 
            }
        }
        $this->redirect(array("action"=>"view",'?'=>array('qry'=>$qry)));
    }
    
     public function business_case_ropen()
    {
       $role = $this->Session->read('role');
        
        if($role=='admin')
        {    $condition=array('active'=>1);    }
        else
        {    $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));    }
        
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
            'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        
        $this->layout='home';
        
        if($this->request->is('POST'))
        {
            //$this->request->data['ExpenseEntries'];
            $Expense = $this->request->data['ExpenseEntries'];
            $Expense['UserId'] = $this->Session->read('userid');
            $Expense['CreateDate'] = date('Y-m-d H:i:s');
            $this->ExpenseReopen->save($Expense);
            $ropenId = $this->ExpenseReopen->getLastInsertId();
            $bus_master = $this->ExpenseMaster->query("SELECT *,CONCAT(em.Id,'_##_',hm.HeadingDesc,'_##_',shm.SubHeadingDesc) `name`,date_format(rem.CreateDate,'%d-%b-%Y') Date FROM `expense_master` em
INNER JOIN `expense_reopen_master` rem ON rem.ExpenseId = em.Id
 INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId
  INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
  INNER JOIN tbl_user tu ON rem.UserId = tu.Id
  WHERE rem.Id='$ropenId'"
  );
                    //$emailid = $this ->User->find("first",array('fields' => array('email','id','branch_name'),'conditions' => array('id'=>$this->Session->read('userid'))));
                    $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE branch_name='".$bus_master['0']['em']['Branch']."' AND emailType='ropen' limit 1");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                    }
                    
                    $sub = 'Business Case Re-Open Request : ('.$bus_master['0']['em']['Branch'].')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Business Case Re-Open Request. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Re-Open<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month For:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['em']['FinanceMonth'].'-'.$bus_master['0']['em']['FinanceYear'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Reopen Date:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . ''.$bus_master['0']['0']['Date']. '<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Busi. case/ Exp. Head/Exp. Sub Head :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . 'Case No.-'.$bus_master['0']['0']['name']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Budgeted Amount :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Additional Amount :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['rem']['AdditionalAmount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Total Amount :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . ($bus_master['0']['em']['Amount']+$bus_master['0']['rem']['AdditionalAmount']).'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Reasion of Addition :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['rem']['Description'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Remarks :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['rem']['Remarks'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Request Made By :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $bus_master['0']['tu']['username'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);
            
            
            $this->Session->setFlash(__("Business Case Reopen Case Request has been moved to FH successfully"));
            $this->redirect(array('action'=>'business_case_ropen'));
        }
        
    }
    
    public function view_business_case_ropen() 
    {
        $this->layout="home";
        $role = $this->Session->read('role');
        if($role=='admin')
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));
        }
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
            'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        
        if($this->request->is('POST'))
        {
            //print_r($this->request->data); exit;
            
            $check = $this->request->data['check'];
            foreach($check as $ck)
            {
                $dataSource = $this->TmpExpenseMaster->getDataSource();
            $dataSource ->begin();
                $idarr = explode('##',$ck);
                $reopenId = $idarr[0];
              $ExpenseId = $idarr[1]; 
                
                $this->TmpExpenseMaster->query("delete from tmp_expense_master WHERE Id='$ExpenseId'");
                $this->TmpExpenseParticular->query("delete from tmp_expense_particular WHERE ExpenseId='$ExpenseId'");
                $this->TmpExpenseParticular->query("delete from tmp_expense_particular WHERE Id in (SELECT Id FROM expense_particular WHERE ExpenseId='$ExpenseId')");
                
                $this->ExpenseReopen->updateAll(array('Approve'=>1,'ApproveBy'=>$this->Session->read('userid'),
                    'ApproveDate'=>"'".date('Y-m-d H:i:s')."'",'BusinessStatus'=>2),array('Id'=>$reopenId));
                
                $this->TmpExpenseParticular->query("INSERT INTO `tmp_expense_particular`
                SELECT * FROM expense_particular WHERE ExpenseId='$ExpenseId'");
                
                if(!$this->TmpExpenseParticular->query("SELECT * FROM tmp_expense_particular WHERE ExpenseId='$ExpenseId'"))
                {
                    $dataSource->rollback();
                    $this->Session->setFlash(__('Business Case Not Moved To BM Bucket For Approval1'));
                    $this->redirect(array('action'=>'view_business_case_ropen'));
                }
                
                $this->TmpExpenseMaster->query("INSERT INTO tmp_expense_master(Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology)
                SELECT Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,'Ropen',Active,userId,createdate,objective,PaymentFile,Methodology FROM expense_master WHERE Id='$ExpenseId'");
                
                if(!$this->TmpExpenseMaster->query("select * from tmp_expense_master where Id='$ExpenseId'"))
                {
                    $dataSource->rollback();
                    $this->Session->setFlash(__('Business Case Not Moved To BM Bucket For Approval2'));
                    $this->redirect(array('action'=>'view_business_case_ropen'));
                }
                $this->TmpExpenseMaster->query("update tmp_expense_master set expense_status='Ropen',Approve1=null,Approve2=null,Approve3=null,ApproveDate1=null,ApproveDate2=null,ApproveDate3=null WHERE Id='$ExpenseId'");
                if(!$this->TmpExpenseMaster->query("select * from tmp_expense_master where Id='$ExpenseId' and expense_status='Ropen'"))
                {
                    $dataSource->rollback();
                    $this->Session->setFlash(__('Business Case Not Moved To BM Bucket For Approval3'));
                    $this->redirect(array('action'=>'view_business_case_ropen'));
                }
                
                $this->TmpExpenseParticular->query("INSERT INTO `expense_particular2`
                SELECT * FROM expense_particular WHERE ExpenseId='$ExpenseId'");
                
                $this->TmpExpenseMaster->query("INSERT INTO `expense_master2`(Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology)
                SELECT Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology FROM expense_master WHERE Id='$ExpenseId'");
                
                if(!$this->ExpenseMaster->deleteAll(array('Id'=>$ExpenseId)))
                {
                    $dataSource->rollback();
                    $this->Session->setFlash(__('Business Case Not Moved To BM Bucket For Approval4'));
                    $this->redirect(array('action'=>'view_business_case_ropen'));
                }
                
                if(!$this->ExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId)))
                {
                    $dataSource->rollback();
                    $this->Session->setFlash(__('Business Case Not Moved To BM Bucket For Approval5'));
                    $this->redirect(array('action'=>'view_business_case_ropen'));
                }
                 $dataSource->commit();
                $this->Session->setFlash(__('Business Case Moved To BM Bucket For Approval'));
            }
        }
    }
    
    function get_dash_business()
  {
      //print_r($this->request->data);
      //exit;
      $FinanceYear = $this->request->data['FinanceYear'];
      $FinanceMonth = $this->request->data['Month'];
      
      
      
      $branchArr = $this->Addbranch->find("list",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name'),'order'=>array('branch_name'=>'asc')));
        $uploadSalCheck = $this->BusinessCaseMaster->query("SELECT branchId,FinanceMonth FROM business_case_master sm
 WHERE FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' GROUP BY BranchId");
        foreach($uploadSalCheck as $smc)
        {
            $SalArr[] = $smc['sm']['branchId'];
        }
 
        echo '<table border="2" style="text-align:center">';
        echo '<tr><th colspan="2" style="text-align:center"><b>'.$FinanceMonth.'-'.$FinanceYear.'</b></th></tr>';
        echo            '<tr>
                        <th style="text-align:center">Branch</th>
                        <th style="text-align:center">Status</th>
                    </tr>';
                    
        foreach($branchArr as $k=>$v)
        {
            echo "<tr>";
                echo "<td>".$v."</td>";
                echo "<td>";
            if(in_array($k,$SalArr))
            {
                echo '<font color="green"><b>Yes</b></font>';
            }
            else
            {
                echo '<font color="red">No</font>';
            }
            echo "</td>";
        }
        
        echo "</table>";
        
       exit;
  }
 public function business_case_upload()
   {
        ini_set('memory_limit','512M');
        $this->layout = "home";
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); 
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19')))));
        
        $this->set('activity','upload');
        
        
        $branchArr = $this->Addbranch->find("list",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name')));
        
        
        if($this->request->is('POST'))
        {           
            if($this->request->data['Submit']=='Upload')
            {
                $userid = $this->Session->read('userid');
                $FileTye = $this->request->data['GrnReport']['file']['type'];
                $info = explode(".",$this->request->data['GrnReport']['file']['name']);
                $BranchId = $this->request->data['GrnReport']['BranchId'];
                $Month = $this->request->data['Month'];
                $FinanceYear = $this->request->data['GrnReport']['FinanceYear'];
                $this->set('Month',$Month);
                $this->set('FinanceYear',$FinanceYear);
                $this->set('BranchId',$BranchId);
                $date = date('Y-m-d H:i:s');
                
                $flagExistCheck = $this->BusinessCaseMaster->query("select * from business_case_master where BranchId='$BranchId' and FinanceMonth='$Month' and FinanceYear='$FinanceYear'");
                
                if(empty($flagExistCheck))
                {
                    if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
                    {
                        $FilePath = $this->request->data['GrnReport']['file']['tmp_name'];
                        $files = fopen($FilePath, "r");
                        $dataArr = array(); $i=0;
                        $flagCheck =true;
                        $flagCheckUploadFormat = true; 
                        $flagFormatCheck=true;
                        $flagCostCenterCheck=true; 
                        $flagExpenseHeadCheck=true;
                        $flagExpenseSubHeadCheck=true;
                        $flagCheckSharingMethod = true;
                        $flagBusinessCaseMadeCheck = true;
                        $flagAmountCheck = true;
                        $flagAmountCostCheck = true;
                        $flagInsertCheck=true;

                        $colNo = 1; $DesiArr = array_keys($TypeFromTable);
                        $m = 0; 
                        while($row = fgetcsv($files,5000,","))
                        {
                            if($flagCheckUploadFormat)
                            {
                                
                                $UploadFormatArr = array('Revenue','Revenue','Details1','Details2','Details3','Budget Amount','Sharing Method');
                                //File Format Check
                                foreach($UploadFormatArr as $column)
                                {
                                    if(!in_array($column,$row))
                                    {
                                        $flagFormatCheck=false;
                                        $flagCheck = false;
                                        break;
                                    }
                                }
                                //File CostCenter Check
                                if($flagFormatCheck)
                                {
                                   $CostCenterArr = $this->CostCenterMaster->query("Select cm.id,cm.cost_center from cost_master cm inner join branch_master bm on cm.branch = bm.branch_name where bm.id='$BranchId' and cm.active=1");
                                   foreach($CostCenterArr as $cca)
                                   {
                                       $CostCenterList[] = $cca['cm']['cost_center'];
                                   }
                                   $i=7;
                                   for($i=7; $i<107; $i++)
                                   {
                                       if(!empty($row[$i]))
                                       {
                                           if(!in_array($row[$i],$CostCenterList))
                                           {
                                               $flagCostCenterCheck = false;
                                               $flagCheck = false;
                                               $CostCenterNotMatch[] = $row[$i];
                                           }
                                           
                                       }
                                   }
                                   if(!$flagCostCenterCheck)
                                   {
                                       break;
                                   }
                                }



                            }
                            else if(!empty($row))
                            { 
                                if($flagCostCenterCheck)
                                {
                                    $ExpenseSubHead = addslashes($row[1]);
                                    $ExpenseHead = addslashes($row[0]);
                                    //Checking Expense Had Exist in Our database 
                                    if(!$this->Tbl_bgt_expenseheadingmaster->query("SELECT HeadingId,HeadingDesc FROM `tbl_bgt_expenseheadingmaster` WHERE HeadingDesc ='$ExpenseHead' AND EntryBy=''"))
                                    {
                                        $ExpenseHeadNotMatched[] = $ExpenseHead;
                                        $flagExpenseHeadCheck = false;
                                        $flagCheck = false;
                                    }

                                    //File Expense SubHead Check
                                    if(!$this->Tbl_bgt_expensesubheadingmaster->query("SELECT SubHeadingId,SubHeadingDesc FROM `tbl_bgt_expensesubheadingmaster` subhead
                                    INNER JOIN tbl_bgt_expenseheadingmaster head ON subhead.HeadingId = head.HeadingId 
                                     WHERE head.HeadingDesc ='$ExpenseHead' AND subhead.SubHeadingDesc='$ExpenseSubHead' AND head.EntryBy=''"))
                                    {
                                       $ExpenseSubHeadNotMatched[$ExpenseSubHead] = $ExpenseHead;
                                       $flagExpenseSubHeadCheck = false;
                                       $flagCheck = false;
                                    }

                                    //File Business Case check 
                                    $CheckBusinessCaseMade = $this->ExpenseMaster->query("SELECT * FROM expense_master em 
                                    INNER JOIN tbl_bgt_expenseheadingmaster head ON em.HeadId = head.HeadingId
                                    INNER JOIN tbl_bgt_expensesubheadingmaster subhead ON em.SubHeadId = subhead.SubHeadingId
                                    WHERE em.FinanceYear='$FinanceYear' and em.BranchId='$BranchId' AND em.FinanceMonth='$Month' AND head.HeadingDesc = '$ExpenseHead' AND subhead.SubHeadingDesc='$ExpenseSubHead'");

                                    if(!empty($CheckBusinessCaseMade))
                                    {
                                       $BusinessCaseMadeMatched[$ExpenseSubHead] = $ExpenseHead;
                                       $flagBusinessCaseMadeCheck = false;
                                       $flagCheck = false;
                                    }
                                    if(!in_array(strtolower($row[6]),array('workstation','mannual','manpower')))
                                    {
                                        $flagCheckSharingMethod=false;
                                        $flagCheck = false;
                                        $BusinessCaseMadeSharingMethod[$ExpenseSubHead] = $row[6];
                                    }
                                }
                                
                            }
                             
                            // Data Storage in business_case_upload table
                            
                            $dataAr[$m]['BranchId'] = $BranchId;
                            $dataAr[$m]['FinanceYear'] = $FinanceYear;
                            $dataAr[$m]['FinanceMonth'] = $Month;
                            $dataAr[$m]['UploadBy'] = $user;
                            $dataAr[$m]['UploadDate'] = $date;
                            $dataAr[$m]['ExpenseHead'] = addslashes($row[0]);
                            $dataAr[$m]['ExpenseSubHead'] = addslashes($row[1]);
                            $dataAr[$m]['Objective'] = addslashes($row[2]);
                            $dataAr[$m]['Mythology'] = addslashes($row[3]);
                            $dataAr[$m]['Description'] = addslashes($row[4]);
                            $dataAr[$m]['Amount'] = addslashes($row[5]);
                            $dataAr[$m]['SharingMethod'] = addslashes($row[6]);
                            for($n=1; $n<=50;$n++)
                                {
                                    $dataAr[$m]['CostCenter'.$n] = addslashes($row[6+$n]);
                                    $sumBudget +=addslashes($row[6+$n]);
                                }
                            //check business case amount is !=0;
                            if(!$flagCheckUploadFormat)
                            {
                                if(!is_numeric($row[5]) || $row[5]==0)
                                {
                                    $flagAmountCheck = false;
                                    $flagCheck = false;
                                    $AmountCheck['F'.($m+1)] = addslashes($row[5]);
                                }
                                $sumBudget = 0;
                                for($n=1; $n<=50;$n++)
                                {
                                    $sumBudget +=addslashes($row[6+$n]);
                                }
                                if(round($row[5])!=round($sumBudget))
                                {
                                    $flagAmountCostCheck = false;
                                    $flagCheck = false;
                                    $AmountCheck['F'.($m+1)] = $sumBudget;
                                }
                            }
                            $m++;
                            $flagCheckUploadFormat = false;
                        }
                        
                        if($flagCheck)
                        {
                            $this->set('activity','check');
                            $this->BusinessCaseFileUpload->query("truncate table business_case_upload");
                            $this->BusinessCaseFileUpload->query("SET GLOBAL max_allowed_packet=64*1024*1024");
                            if($this->BusinessCaseFileUpload->saveAll($dataAr))
                            {
                            
                              // GRN Making Works Starts From Here //////////////
                                $Transaction = $this->ExpenseMaster->getDataSource();
                                $Transaction->begin(); //Transaction Starts From Here
                                $CostCenterListQry = "SELECT * FROM business_case_upload bcu WHERE BusinessPartId =1";
                                $CostCenterListArr = $this->BusinessCaseFileUpload->find('first',array('conditions'=>"BusinessPartId =1"));
                                
                                $CostCenterList = array();
                                for($i=1; $i<=50; $i++)
                                {
                                    $CostCenterList['CostCenter'.$i] = $CostCenterListArr['BusinessCaseFileUpload']['CostCenter'.$i];
                                }
                                
                                $BusinessCaseEntry = "SELECT bcu.*,head.HeadingId,subhead.SubHeadingId,bm.branch_name FROM business_case_upload bcu 
                                INNER JOIN `tbl_bgt_expenseheadingmaster` head ON bcu.ExpenseHead = head.HeadingDesc AND head.EntryBy=''
                                INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON  head.HeadingId = subhead.HeadingId AND
                                bcu.ExpenseSubHead = subhead.SubHeadingDesc 
                                INNER JOIN branch_master bm ON bcu.BranchId = bm.id
                                WHERE BusinessPartId !=1  ";
                                $BusinessCaseEntry = $this->BusinessCaseFileUpload->query($BusinessCaseEntry);
                                $flagGRNCheck = true;
                                $ParticularArray = array('workstation'=>'1','mannual'=>'2','manpower'=>'3');

                                foreach($BusinessCaseEntry as $grn)
                                {
                                    
                                    //Variable Creationg To Upload in expense_entry_master table and expense_entry_particular table
                                    $HeadId = $grn['head']['HeadingId'];
                                    $SubHeadId = $grn['subhead']['SubHeadingId'];
                                    $Branch = $grn['bm']['branch_name'];
                                    //End Variable Creating

                                    //Making Array To Upload in expense_entry_particular table
                                       $PartArray  = array();  
                                    foreach($CostCenterList as $costColumn=>$CostCenter)
                                    {
                                        
                                        if(!empty($grn['bcu'][$costColumn]) && !empty($CostCenter))
                                        {
                                            
                                            $ExpenseParticular  = array();
                                            $ExpenseParticular['ExpenseType'] = 'CostCenter';
                                            $CostCenterId = $this->CostCenterMaster->find('first',array('fields'=>"Id",'conditions'=>"cost_center='$CostCenter' and active=1"));
                                            $ExpenseParticular['ExpenseTypeId'] = $CostCenterId['CostCenterMaster']['Id'];
                                            $ExpenseParticular['ExpenseTypeName'] = $CostCenter;
                                            $ExpenseParticular['BranchId'] = $BranchId;
                                            $ExpenseParticular['FinanceYear'] = $FinanceYear;
                                            $ExpenseParticular['FinanceMonth'] = $Month;
                                            $ExpenseParticular['HeadId'] = $HeadId;
                                            $ExpenseParticular['SubHeadId'] = $SubHeadId;
                                            $ExpenseParticular['AmountPercent'] = round(($grn['bcu'][$costColumn]*100)/$grn['bcu']['Amount']);
                                            $ExpenseParticular['Amount'] = $grn['bcu'][$costColumn];

                                            if($this->ExpenseParticular->saveAll($ExpenseParticular))
                                            {
                                             $PartId = $this->ExpenseParticular->getLastInsertID();
                                            }
                                            else
                                            {
                                                $flagInsertCheck = false;
                                            }

                                            $ExpenseParticular  = array();
                                            $ExpenseParticular['ExpenseType'] = 'Particular';
                                            $ExpenseParticular['ExpenseTypeId'] = $ParticularArray[strtolower($grn['bcu']['SharingMethod'])];
                                            $ExpenseParticular['ExpenseTypeName'] = strtolower($grn['bcu']['SharingMethod']);
                                            $ExpenseParticular['BranchId'] = $BranchId;
                                            $ExpenseParticular['FinanceYear'] = $FinanceYear;
                                            $ExpenseParticular['FinanceMonth'] = $Month;
                                            $ExpenseParticular['HeadId'] = $HeadId;
                                            $ExpenseParticular['SubHeadId'] = $SubHeadId;
                                            $ExpenseParticular['AmountPercent'] = round(($grn['bcu'][$costColumn]*100)/$grn['bcu']['Amount']);
                                            $ExpenseParticular['Amount'] = $grn['bcu'][$costColumn];
                                            $ExpenseParticular['Parent'] = $PartId;
                                            if($this->ExpenseParticular->saveAll($ExpenseParticular))
                                            {
                                                $CostPartId = $this->ExpenseParticular->getLastInsertID();
                                            }
                                            else
                                            {
                                                
                                                $flagInsertCheck = false;
                                                break;
                                            }
                                            $PartArray[] = $PartId;
                                            $PartArray[] = $CostPartId;
                                        }

                                       
                                    }
                                     $ExpenseMaster  = array();
                                        if(!empty($PartArray))
                                        {
                                            $ExpenseMaster['BranchId'] = $BranchId;
                                            $ExpenseMaster['Branch'] = $Branch;
                                            $ExpenseMaster['FinanceYear'] = $FinanceYear;
                                            $ExpenseMaster['FinanceMonth'] = $Month;
                                            $ExpenseMaster['HeadId'] = $HeadId;
                                            $ExpenseMaster['SubHeadId'] = $SubHeadId;
                                            $ExpenseMaster['Amount'] = $grn['bcu']['Amount'];
                                            $ExpenseMaster['Approve1'] = '1';
                                            $ExpenseMaster['ApproveDate1'] = $date;
                                            $ExpenseMaster['Approve2'] = '2';
                                            $ExpenseMaster['ApproveDate2'] = $date;
                                            $ExpenseMaster['Approve3'] = '2';
                                            $ExpenseMaster['ApproveDate3'] = $date;
                                            $ExpenseMaster['userId'] = $userid;
                                            $ExpenseMaster['createdate'] = $date;
                                            $ExpenseMaster['objective'] = $grn['bcu']['Objective'];
                                            $ExpenseMaster['PaymentFile'] = "test";
                                            $ExpenseMaster['Methodology'] = $grn['bcu']['Mythology'];
                                            $ExpenseMaster['Description_Det'] = $grn['bcu']['Description'];
                                            if($this->ExpenseMaster->saveAll($ExpenseMaster))
                                            {
                                                $ExpenseId = $this->ExpenseMaster->getLastInsertID();
                                                $this->ExpenseParticular->updateAll(array('ExpenseId'=>$ExpenseId),array('Id'=>$PartArray));
                                            }
                                            else
                                            {
                                                
                                                $flagInsertCheck = false;
                                                break;
                                            }
                                        }
                                }

                                if(!$flagInsertCheck)
                                {
                                    
                                    $this->Session->setFlash('Data Not Saved');
                                    $Transaction->rollback();
                                }
                                else
                                {
                                    $this->BusinessCaseMaster->save(array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$Month));
                                    $Transaction->commit();
                                    $this->Session->setFlash('Business Case Uploaded Successfully');
                                    $this->redirect(array("action"=>'business_case_upload'));
                                    
                                    
                                }
                             
                            }
                            else
                            {
                                $this->Session->setFlash('Data Not Saved');
                            }
                        }
                        else 
                        {
                            $this->set('activity',"check");
                            if(!$flagFormatCheck)
                            {
                                $this->Session->setFlash('File Format Not Matched');
                            }
                            if(!$flagCostCenterCheck)
                            {
                                $str ="<table border='2'><tr><td>CostCenter Not Matched</td><tr>";
                                foreach($CostCenterNotMatch as $cost)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $cost;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Cost Center Not Matched');
                            }
                            if(!$flagExpenseHeadCheck)
                            {
                                $str .="<table border='2'><tr><td>Expense Head Not Matched</td><tr>";
                                foreach($ExpenseHeadNotMatched as $cost)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $cost;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Expense Head Not Matched');
                            }
                            if(!$flagExpenseSubHeadCheck)
                            {
                                $str .="<table border='2'><tr><td colspan='2'>Expense SubHead Not Matched</td><tr>";
                                foreach($ExpenseSubHeadNotMatched as $subhead=>$head)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $head;
                                    $str .= "</td>";
                                    $str .= "<td>";
                                        $str .= $subhead;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Expense Sub Head Not Matched');
                            }
                            if(!$flagBusinessCaseMadeCheck)
                            {
                                $str .="<table border='2'><tr><td colspan='2'>Business Case Already Exist</td></tr><tr><td>Expense Head</td><td>Expense Sub Head</td><tr>";
                                foreach($BusinessCaseMadeMatched as $k=>$v)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $v;
                                    $str .= "</td>";
                                    $str .= "<td>";
                                        $str .= $k;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Business Case Allready Exist');
                            }
                            if(!$flagCheckSharingMethod)
                            {
                                $str .="<table border='2'><tr><td>Expense Sharing Method Not Matched</td><tr>";
                                foreach($BusinessCaseMadeSharingMethod as $cost)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $cost;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Sharing Method Not Exist');
                            }
                            if(!$flagAmountCheck)
                            {
                                $str .="<table border='2'><tr><td colspan='2'>Expense Amount is not in Correct Method</td></tr><tr><td>Row</td><td>Amount</td><tr>";
                                foreach($AmountCheck as $k=>$v)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $k;
                                    $str .= "</td>";
                                    $str .= "<td>";
                                        $str .= $v;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Expense Amount is not in Correct Method');
                            }
                            if(!$flagAmountCostCheck)
                            {
                                $str .="<table border='2'><tr><td colspan='2'>Expense Amount should be equal to All CostCenter Amount</td></tr><tr><td>Row</td><td>Amount</td></tr>";
                                foreach($AmountCheck as $k=>$v)
                                {
                                    $str .= "<tr>";
                                    $str .= "<td>";
                                        $str .= $k;
                                    $str .= "</td>";
                                    $str .= "<td>";
                                        $str .= $v;
                                    $str .= "</td>";
                                    $str .= "</tr>";
                                }
                                $str .= "</table>";
                                $this->Session->setFlash('Expense Amount should be equal to All CostCenter Amount');
                            }
                            if(!$flagInsertCheck)
                            {
                                $this->Session->setFlash('Data Not Saved. Please Try Again');
                            }
                            $this->set('html',$str);
                        }
                        
                    }
                    else
                    {
                        $this->Session->setFlash('File Format not Valid');
                    }
                }
                else
                {
                    $this->Session->setFlash('Records Allready Exist');
                }
                
            }
        }
    } 
    
    
}

?>