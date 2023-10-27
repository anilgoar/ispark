<?php
class ExpenseEntriesController extends AppController 
{
    public $uses = array('Addbranch','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','TmpExpenseParticular','ExpenseParticular','BillMaster','ImprestManager','ExpenseReopen','User','BranchEmailMaster');
    
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

            if(in_array('63',$roles) || in_array('64',$roles)||in_array('65',$roles) ||in_array('66',$roles) ||in_array('67',$roles) ||in_array('68',$roles) || in_array('69',$roles)){$this->Auth->allow('index','initial_branch','get_cost_center','get_particular_breakup','get_particular_breakup',
                    'expense_entry','get_sub_heading','get_breakup','get_costcenter_breakup','expense_save','get_his_check','get_his_check2','get_old_delete','expense_final_save',
                    'expense_save_tmp','view_bm','edit_tmp_bm','bm_final_save','view_vh','edit_tmp_vh','vh_final_save','view_fh','edit_tmp_fh','fh_final_save','view','edit_tmp',
                    'save_tmp','discard','get_business_case','business_case_ropen','get_business_case_request','view_business_case_ropen','get_expense_amount');}
            else{$this->Auth->deny('index','initial_branch','get_cost_center','get_particular_breakup','get_particular_breakup',
                    'expense_entry','get_sub_heading','get_breakup','get_costcenter_breakup','expense_save','get_his_check','get_his_check2','get_old_delete','expense_final_save',
                    'expense_save_tmp','view_bm','edit_tmp_bm','bm_final_save','view_vh','edit_tmp_vh','vh_final_save','view_fh','edit_tmp_fh','fh_final_save',
                    'view','edit_tmp','save_tmp','discard','get_business_case','business_case_ropen','get_business_case_request','view_business_case_ropen','get_expense_amount');}
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
        $this->TmpExpenseMaster->deleteAll(array('Id'=>$id));
        $this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$id));
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
                    . " WHERE bm.id='$branchId'");
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
                'conditions'=>array($this->request->data)));
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
              $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$this->request->data['branch'])));
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
            $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$this->request->data['branch'])));
            $html .='<tr><th>Sr No</th><th>ExpenseUnit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
            
              foreach($costMaster as $k=>$v)
              {
                    $particularAmount = $this->TmpExpenseParticular->find('first',array('conditions'=>array_merge($condition,array('ExpenseTypeId'=>$k,'ExpenseId'=>$ExpenseId))));
                
                    $amount = $particularAmount['TmpExpenseParticular']['Amount'];
                    $perAmount = $particularAmount['TmpExpenseParticular']['AmountPercent'].'%';
                    $v = preg_replace('([^0-9a-zA-Z])', ' ', $v);
                    $html .='<tr onClick="get_particular_breakup('.$k.')"><td>'.$i++.'</td><td>'.$v.'</td>';
                    $html .='<td>'.'<input type="text" name="costcenter_amountpercent_'.$unit.'_'.$k.'" value="'.$perAmount.'" placeholder="%" class = "form-control" id= "percostcenter'.$k.'" readonly=""></td>';
                    $html .='<td>'.'<input type="text" name="costcenter_amount_'.$unit.'_'.$k.'" value="'.$amount.'" placeholder="Amount" class = "form-control" id= "amountcostcenter'.$k.'" readonly=""></td>';
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
           
           $costMaster = $this->CostCenterMaster->find('first',array('fields'=>array('process_name','cost_center'),'conditions'=>array('Id'=>$costcenter)));
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
                    $html .='<td>'.'<input type="text" name="particular_amount_'.$costcenter.'_'.$k.'" value="'.$amount.'" placeholder="Amount" class = "form-control" id= "amountparticular'.$k.'"  onBlur="get_particular_check('.$costcenter.')"></td>';
                    $html .='</tr>';
              } 
           echo $html .='</tbody></table>'.'<div class="form-group"><div class="col-sm-4"><button class="btn btn-info" value="save" onClick="return ExpenseSave('.$costcenter.')">Save</button></div></div>';
           
           exit;
        }
   }
   
    public function initial_branch()
    {
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
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
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
            
           // print_r($data); exit;
            //$tmp = $this->TmpExpenseMaster->query("insert into tmp_expense_master set branchId='3',branch='HEAD OFFICE',financeYear='2017-18',financeMonth='Jun'");
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
        
        $costMaster = $this->CostCenterMaster->find('first',array('fields'=>'cost_center','conditions'=>array('Id'=>$cost_center)));
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
           $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",
               'Methodology'=>"'".$Methodology."'",'PaymentFile'=>"'".$PaymentFile."'",'Active'=>1),array('Id'=>$ExpenseId));
           
           $this->Session->setFlash(__("Expense Save and Moved to BM Bucket"));
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
        
        $qry = "Where Active='1'";
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
            if(!empty($data['BranchId']))
            {
                $qry .= " and em.BranchId='".$data['BranchId']."'";
            }
            
            if(!empty($data['FinanceYear']))
            {
                $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            }
            
            if(!empty($data['FinanceMonth']))
            {
                $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            }
        }
        
        $qry .= " and Approve1 is null and Approve2 is null and Approve3 is null ";
       
                $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        $this->set('data',$data);
    }
    
    public function edit_tmp_bm()
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
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'])));
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
            
    }
    
    public function bm_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            //print_r($this->request->data); exit;
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
               if($TotalAmount['0']['0']['Amount'])
               {
                $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve1'=>1,'ApproveDate1'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
                $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to VH Bucket</font>"));
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view_bm')); 
               }
               else
               {
                   $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
                   $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_bm','?'=>array('id'=>$ExpenseId))); 
               } 
               
                              
        }
        $this->redirect(array("action"=>"view_bm"));
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
            if(!empty($data['BranchId']))
            {
                $qry .= " and em.BranchId='".$data['BranchId']."'";
            }
            
            if(!empty($data['FinanceYear']))
            {
                $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            }
            
            if(!empty($data['FinanceMonth']))
            {
                $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            }
        }
        
        $qry .= " and em.Active=1 and em.Approve1=1 and em.Approve2 is null and em.Approve3 is null";
        
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        
        $this->set('data',$data);
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
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'])));
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
            
    }
    
    public function vh_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            //print_r($this->request->data); exit;
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
               
               if($TotalAmount['0']['0']['Amount'])
               {
                $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve2'=>1,'ApproveDate2'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
               $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to FH Bucket</font>"));
                $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view_vh')); 
               }
               else
               {
                   $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
                   $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_vh','?'=>array('id'=>$ExpenseId))); 
               } 
               
               
               $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view'));
            
            
        }
        $this->redirect(array("action"=>"view_vh"));
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
            if(!empty($data['BranchId']))
            {
                $qry .= " and em.BranchId='".$data['BranchId']."'";
            }
            
            if(!empty($data['FinanceYear']))
            {
                $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            }
            
            if(!empty($data['FinanceMonth']))
            {
                $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            }
        }
         $qry .= " and em.Active=1 and em.Approve1=1 and em.Approve2=1 and em.Approve3 is null";
        
         echo "SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId"; exit;
         
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        
        $this->set('data',$data);
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
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'])));
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
            
    }
    
    public function fh_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            //print_r($this->request->data); exit;
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
            $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
            if(!$TotalAmount['0']['0']['Amount'])
               {
                   $this->Session->setFlash(__("<font color='green'>Expense Not Saved 0 Amount</font>"));
                   $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp_fh','?'=>array('id'=>$ExpenseId))); 
               }
               unset($TotalAmount);
                //print_r($ExpenseId); exit;
                $ExpenseMaster = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                //print_r($ExpenseMaster); exit;
                if($ExpenseMaster['TmpExpenseMaster']['expense_status']=='New')
                {
                    $NewExpenseMaster = Hash::Remove($ExpenseMaster['TmpExpenseMaster'],'Id');
                }
                else
                {
                    $NewExpenseMaster = $ExpenseMaster['TmpExpenseMaster'];
                }
                //print_r($ExpenseMaster); exit;
                $this->ExpenseMaster->save($NewExpenseMaster);
                $NewExpenseId = $this->ExpenseMaster->getLastInsertId();
                //print_r($NewExpenseId); exit;
                $UnitMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Unit')));

               foreach($UnitMaster as $unit)
               {
                   $oldUnitId = $unit['TmpExpenseParticular']['Id'];
                   $newUnit = Hash::Remove($unit['TmpExpenseParticular'],'Id');
                   $newUnit = Hash::Remove($newUnit,'ExpenseId');
                   $newUnit['ExpenseId'] = $NewExpenseId;

                   $this->ExpenseParticular->saveAll($newUnit);
                   $NewUnitId = $this->ExpenseParticular->getLastInsertId();

                   if(!empty($NewUnitId))
                   {
                       $this->TmpExpenseParticular->updateAll(array('Parent'=>$NewUnitId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter','Parent'=>$oldUnitId));
                   } 
               }

               $CostMaster = $this->TmpExpenseParticular->find('all',array('conditions'=>array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'CostCenter')));

               foreach($CostMaster as $cost)
               {
                   $oldCostId = $cost['TmpExpenseParticular']['Id'];
                   $newCost = Hash::Remove($cost['TmpExpenseParticular'],'Id');
                   $newCost = Hash::Remove($newCost,'ExpenseId');
                   $newCost['ExpenseId'] = $NewExpenseId;

                   $this->ExpenseParticular->saveAll($newCost);
                   $NewCostId = $this->ExpenseParticular->getLastInsertId();
                   //print_r($NewUnitId);
                    //unset($this->ExpenseParticular);
                   unset($this->ExpenseParticular);
                   if(!empty($NewCostId))
                   {
                       $this->TmpExpenseParticular->updateAll(array('Parent'=>$NewCostId),array('ExpenseId'=>$ExpenseId,'ExpenseType'=>'Particular','Parent'=>$oldCostId));
                   }
               }

               $ParticularMaster = $this->TmpExpenseParticular->query("INSERT INTO expense_particular (ExpenseId,ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,
                HeadId,SubHeadId,AmountPercent,Amount,Parent)
                SELECT '$NewExpenseId',ExpenseType,ExpenseTypeId,ExpenseTypeName,ExpenseTypeParent,BranchId,FinanceYear,FinanceMonth,HeadId,SubHeadId,AmountPercent,
                Amount,Parent FROM `tmp_expense_particular`
                WHERE ExpenseId = '$ExpenseId' AND ExpenseType='Particular'");

               $TotalAmount = $this->ExpenseParticular->query("select sum(Amount) `Amount` from expense_particular where ExpenseId='$NewExpenseId' and ExpenseType='Particular'");
               $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve3'=>3,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$ExpenseId));
               $this->ExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'",'Approve1'=>1,'Approve2'=>1,'Approve3'=>1,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$NewExpenseId));
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
                $this->TmpExpenseMaster->deleteAll(array('Id'=>$ExpenseId));
                $this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId));
                $this->TmpExpenseParticular->deleteAll(array('ExpenseId'=>$NewExpenseId));
               }
               $this->Session->setFlash(__("<font color='green'>Expense has been Approved Successfully</font>"));               
        }
        $this->redirect(array("action"=>"view_fh"));
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
            if(!empty($data['BranchId']))
            {
                $qry .= " and em.BranchId='".$data['BranchId']."'";
            }
            
            if(!empty($data['FinanceYear']))
            {
                $qry .= " and em.FinanceYear='".$data['FinanceYear']."'";
            }
            
            if(!empty($data['FinanceMonth']))
            {
                $qry .= " and em.FinanceMonth='".$data['FinanceMonth']."'";
            }
        }
        $data = $this->TmpExpenseMaster->query("SELECT em.Id,Branch,EntryNo,FinanceYear,FinanceMonth,HeadingDesc,SubHeadingDesc,Amount,DATE_FORMAT(createdate,'%d-%b-%Y') `date`,IF(Approve1 IS NULL,'BM Pending',IF(Approve2 IS NULL,'VH Pending',IF(Approve3 IS NULL,'FH Pending','Approved'))) 
`bus_status` FROM tmp_expense_master em  
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId $qry order by em.HeadId");
        
        $this->set('data',$data);
    }
    
    public function edit_tmp()
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
          $costMaster = $this->CostCenterMaster->find('list',array('fields'=>array('id','cost_center'),'conditions'=>array('branch'=>$req['Branch'])));
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
            
    }
    
    public function expense_final_save()
    {
        $this->layout="ajax";
        
        $roles=explode(',',$this->Session->read("page_access"));
        
        if($this->request->is('POST'))
        {          
            $ExpenseId = $this->request->data['ExpenseEntries']['id'];
            $objective = addslashes($this->request->data['ExpenseEntries']['objective']);
            $Methodology = addslashes($this->request->data['ExpenseEntries']['Methodology']);
            //print_r($this->request->data['ExpenseEntries']); exit;
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
               if($TotalAmount['0']['0']['Amount'])
               {
                    $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'objective'=>"'".$objective."'",'Methodology'=>"'".$Methodology."'"),array('Id'=>$ExpenseId));
               }
               else
               {
                    $this->Session->setFlash(__("<font color='green'>Expense should not be 0</font>"));
                    $this->redirect(array('controller'=>'ExpenseEntries','action'=>'edit_tmp','?'=>array('id'=>$ExpenseId)));
               }
               
               $this->Session->setFlash(__("<font color='green'>Expense has been Approved and move to BM Bucket</font>"));
               $this->redirect(array('controller'=>'ExpenseEntries','action'=>'view'));
            
        }
        $this->redirect(array("action"=>"view"));
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
                
                
                
                $this->TmpExpenseMaster->query("INSERT INTO tmp_expense_master(Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology)
                SELECT Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,'Ropen',Active,userId,createdate,objective,PaymentFile,Methodology FROM expense_master WHERE Id='$ExpenseId'");
                
                $this->TmpExpenseMaster->query("update tmp_expense_master set expense_status='Ropen',Approve1=null,Approve2=null,Approve3=null,ApproveDate1=null,ApproveDate2=null,ApproveDate3=null WHERE Id='$ExpenseId'");
                
                $this->TmpExpenseParticular->query("INSERT INTO `expense_particular2`
                SELECT * FROM expense_particular WHERE ExpenseId='$ExpenseId'");
                
                $this->TmpExpenseMaster->query("INSERT INTO `expense_master2`(Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology)
                SELECT Id,BranchId,Branch,EntryNo,FinanceYear,FinanceMonth,HeadId,SubHeadId,Amount,Approve1,ApproveDate1,Approve2,ApproveDate2,
                Approve3,ApproveDate3,Approve4,ApproveDate4,Approve5,ApproveDate5,expense_status,Active,userId,createdate,objective,PaymentFile,Methodology FROM expense_master WHERE Id='$ExpenseId'");
                
                $this->ExpenseMaster->deleteAll(array('Id'=>$ExpenseId));
                
                $this->ExpenseParticular->deleteAll(array('ExpenseId'=>$ExpenseId)); 
                                
                $this->Session->setFlash(__('Business Case Moved To BM Bucket For Approval'));
                
                
                
            }
        }
    }
    
}

?>