<?php
class GrnEntriesController extends AppController 
{
    public $uses = array('Addbranch','Addcompany','BillMaster','CostCenterMaster','Tbl_bgt_expenseheadingmaster','Tbl_bgt_expensesubheadingmaster','Tbl_bgt_expenseunitmaster',
        'TmpExpenseMaster','ExpenseMaster','TmpExpenseParticular','ExpenseParticular','BillMaster','ExpenseEntryMaster','ExpenseEntryParticular',
        'VendorMaster','ImprestManager','User','BranchEmailMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();         //before filter used to validate session and allowing access to server
        if(!$this->ImprestManager->find('first',array('conditions'=>array('UserId'=>$this->Session->read('userid')))))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index','initial_branch','get_cost_center','get_particular_breakup','get_particular_breakup',
                    'expense_entry','get_sub_heading','get_breakup','get_costcenter_breakup','expense_save','get_his_check','get_his_check2','get_old_delete',
                    'expense_final_save','expense_save_tmp','view','edit_tmp','save_tmp','entry_imprest','get_amount_desc','imprest_save','entry_vendor','vendor_save',
                    'entry_salary','salary_save','select_entry','get_vendor','get_grn_no','book_grn_no','view_grn','get_grn_delete');
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
            $branchId=$this->request->data['BranchId'];
            
            $html = "<table><thead><tr><th>SNo.</th><th>CostCenter</th><th>Amount</th></tr></thead><tbody>";
            $cost_master = $this->CostCenterMaster->query("SELECT cm.id,cm.cost_center FROM cost_master cm INNER JOIN branch_master bm ON cm.branch=bm.branch_name"
                    . " WHERE bm.id='$branchId'");
            $i=1;
            foreach($cost_master as $cm)
            {
                $cost_centerList[] = $cm['cm']['id'];
                $html .="<tr><td>".$i++."</td>";
                $html .="<td>".$cm['cm']['cost_center']."</td>";
                $html .='<td><input type="text" name="cost['.$cm['cm']['id'].']" class="form-control" id="cost'.$cm['cm']['id'].'" onBlur="getTotalCost('.$cm['cm']['id'].')" onKeypress="return isNumberKey(event)"></td></tr>';
            }
            $html .='</tbody></table>';
            $html .='<input type="hidden" name="costcenterIds" value="'.implode(",",$cost_centerList).'" class="form-control" id="costcenterIds">';
            echo $html;
        }
        exit;
    }
    
    public function get_vendor()
    {
        $this->layout="ajax";
        $cost_centerList=array();
        if($this->request->is("POST"))
        {
            $BranchId[] = 0;
            $BranchId[]=$this->request->data['BranchId'];
            $HeadId = $this->request->data['HeadId'];
            $SubHeadId = $this->request->data['SubHeadId'];
            
            $vendor = $this->VendorMaster->find('list',array('fields'=>array('Id','vendor'),'conditions'=>array('BranchId'=>$BranchId,'active'=>1)));
            
            echo json_encode($vendor);
        }
        exit;
    }
    
    public function get_sub_heading()
    {
        $this->layout="ajax";
        $SubHeading=array();
        if($this->request->is("POST"))
        {   
            $SubHeading = $this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                'conditions'=>array($this->request->data)));
            echo json_encode($SubHeading);
        }
        exit;
    }
    
    public function get_breakup()
    {
        $this->layout="ajax";
        $$unit=array();
        
        if($this->request->is("POST"))
        {
            $html = ' <table class="table" style="font-size: 12px"><thead>'; $i=1; $div="";
            if($unit = $this->Tbl_bgt_expenseunitmaster->find('list',array('fields'=>array("ExpenseunitID","ExpenseUnit"),
                'conditions'=>array('HeadingId'=>$this->request->data['HeadingId'],'SubHeadingID'=>$this->request->data['SubHeadingID'],'Status'=>0))))
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
          echo $parent = $parentMaster['TmpExpenseParticular']['Id'];
           //print_r($parentMaster); exit;
           
           $costMaster = $this->CostCenterMaster->find('first',array('fields'=>array('process_name','cost_center'),'conditions'=>array('Id'=>$costcenter)));
            $costName = $costMaster['CostCenterMaster']['process_name'].'('.$costMaster['CostCenterMaster']['cost_center'].')';
           
           $html = '<table class="table" style="font-size: 12px"><thead><tr><th colspan="4">'.$costName.'</th></tr>'; $i=1;
           $html .='<tr><th>Sr No</th><th>ExpenseUnit</th><th>%</th><th>Amount</th></tr></thead><tbody>';
           $part = array('1'=>'WorkStation','2'=>'Mannual','3'=>'Revenue');
           foreach($part as $k=>$v)
              { 
                    $particularAmount = $this->TmpExpenseParticular->find('first',array('conditions'=>array('ExpenseTypeParent'=>$costcenter,'ExpenseTypeId'=>$k,'Parent'=>$parent,'ExpenseId'=>$ExpenseId)));
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
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>array('active'=>1),'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        $this->layout='home';
    }
        
    public function expense_entry() 
    {
        if($this->request->is("POST"))
        {            
            $req = $this->request->data['GrnEntries'];
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
        
        $parent_particular = $this->TmpExpenseParticular->find('first',array('fields'=>'Id','conditions'=>array('ExpenseTypeId'=>"$cost_center",'ExpenseTypeParent'=>"$unitId")));
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
        
        $parent_unit = $this->TmpExpenseParticular->find('first',array('fields'=>'Id','conditions'=>array('ExpenseTypeId'=>"$unitId")));
        $parent = $parent_unit['TmpExpenseParticular']['Id'];
        if(!$unitId) $unitId=null;
        $mainArray = array(
                    'ExpenseId'=>$Id,
                    'ExpenseType'=>'CostCenter',
                    'ExpenseTypeId'=>"$cost_center",
                    'ExpenseTypeName'=>"$cost_centerName",
                    'ExpenseTypeParent'=>$unitId,
                    'BranchId'=>$branch,
                    'FinanceYear' =>$financeYear,
                    'FinanceMonth' =>$financeMonth,
                    'HeadId' =>$ExpenseHead,
                    'SubHeadId' =>$ExpenseSubHead,
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
                    'FinanceYear' =>$financeYear,
                    'FinanceMonth' =>$financeMonth,
                    'HeadId' =>$ExpenseHead,
                    'SubHeadId' =>$ExpenseSubHead
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
           $ExpenseId = $this->request->data['GrnEntries']['id'];     
           $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
           $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Active'=>1),array('Id'=>$ExpenseId));
           $this->Session->setFlash(__("Expense Save and Moved to Approval Bucket"));
           $this->redirect(array('controller'=>'GrnEntries','action'=>'initial_branch'));
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
 
    public function view()
    {
        $this->layout="home";
        $roles=explode(',',$this->Session->read("page_access"));
        
       if(in_array('64',$roles))
            $data = $this->TmpExpenseMaster->find('all',array('conditions'=>array('Active'=>'1','Approve1'=>null)));
        else if(in_array('65',$roles))
            $data = $this->TmpExpenseMaster->find('all',array('conditions'=>array('Approve1'=>'1','Approve2'=>null)));
        else
            $data = $this->TmpExpenseMaster->find('all',array('conditions'=>array('Approve2'=>'1','Approve3'=>null)));
        
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
            'conditions'=>array('HeadingId'=>$req['HeadId'],'SubHeadingID'=>$req['SubHeadId'],'Status'=>0))))
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
            //print_r($this->request->data); exit;
            $ExpenseId = $this->request->data['GrnEntries']['id'];
            
            if(in_array('66',$roles))
            {
                //print_r($ExpenseId); exit;
                $ExpenseMaster = $this->TmpExpenseMaster->find('first',array('conditions'=>array('Id'=>$ExpenseId)));
                //print_r($ExpenseMaster); exit;
                $NewExpenseMaster = Hash::Remove($ExpenseMaster['TmpExpenseMaster'],'Id');

                $this->ExpenseMaster->save($NewExpenseMaster);
                $NewExpenseId = $this->ExpenseMaster->getLastInsertId();

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
               $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve3'=>3,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$ExpenseId));
               $this->ExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve3'=>3,'ApproveDate3'=>"'".date('Y-m-d H:i:s')."'",'Active'=>'1'),array('Id'=>$NewExpenseId));
            }
        
            else if(in_array('64',$roles))
            {
               $ExpenseId = $this->request->data['GrnEntries']['id'];     
               $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
               $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve1'=>1,'ApproveDate1'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
               $this->Session->setFlash(__("Expense Save and Moved to Approval Bucket"));
               $this->redirect(array('controller'=>'GrnEntries','action'=>'view'));
            }
            else if(in_array('65',$roles))
            {
               $ExpenseId = $this->request->data['GrnEntries']['id'];     
               $TotalAmount = $this->TmpExpenseParticular->query("select sum(Amount) `Amount` from tmp_expense_particular where ExpenseId='$ExpenseId' and ExpenseType='Particular'");
               $this->TmpExpenseMaster->updateAll(array('Amount'=>$TotalAmount['0']['0']['Amount'],'Approve2'=>1,'ApproveDate2'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ExpenseId));
               $this->Session->setFlash(__("Expense Save and Moved to Approval Bucket"));
               $this->redirect(array('controller'=>'GrnEntries','action'=>'view'));
            }
        }
        $this->redirect(array("action"=>"view"));
    }
    
    public function get_amount_desc()
    {
        $this->layout="ajax";
        $SubHeading=array();
        if($this->request->is("POST"))
        {   
            $BranchId = $this->request->data['BranchId'];
            $FinanceYear = $this->request->data['FinanceYear'];
            $FinanceMonth = $this->request->data['FinanceMonth'];
            $HeadId = $this->request->data['HeadId'];
            $SubHeadId = $this->request->data['SubHeadId'];
            
            $Total = $this->ExpenseMaster->find('first',array('fields'=>array('Amount'),'conditions'=>
                array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId,'EntryStatus'=>'1')));
            
            $data['ApproveAmount'] = empty($Total['ExpenseMaster']['Amount'])?0:$Total['ExpenseMaster']['Amount'];
            
            
            $Expense = $this->ExpenseEntryMaster->query("select sum(Amount) `Amount` from expense_entry_master where "
                    . "BranchId='$BranchId' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' AND HeadId='$HeadId' AND SubHeadId='$SubHeadId'");
            //print_r();
            if($data['ApproveAmount']!=0)
            {
            $data['ConsumedAmount'] = empty($Expense['0']['0']['Amount'])?0:$Expense['0']['0']['Amount'];
            $data['BalanceAmount'] = $data['ApproveAmount']-$data['ConsumedAmount'];
            $data['error'] = 'Business Case has been Closed';
            }
            else
            {
                $data['ConsumedAmount'] = 0;
                $data['BalanceAmount'] = 0;
                if($this->ExpenseMaster->find('first',array('fields'=>array('Amount'),'conditions'=>
                array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId,'EntryStatus'=>'0'))))
                {
                    $data['error'] = 'Business Case has been Closed';
                }
                else
                {
                    $data['error'] = 'Business Case Not Made';
                }
            }
            echo json_encode($data);
        }
        exit;
    }
    
    public function entry_imprest()
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
            'order' => array('branch_name' => 'asc')))); 
        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    }
    
    public function imprest_save()
    {
        //print_r($this->request->data); exit;
        $costmaster = $this->request->data['cost'];
        $GrnEntries = $this->request->data['GrnEntries'];
        $BranchId=$GrnEntries['BranchId'];
        $FinanceYear = $GrnEntries['FinanceYear'];
        $FinanceMonth = $GrnEntries['FinanceMonth'];
        $HeadId = $GrnEntries['HeadId'];
        $SubHeadId = $GrnEntries['SubHeadId'];
        $Description = $GrnEntries['description'];
        $EntryDate = $GrnEntries['EntryDate'];
        $EntryStatus = $GrnEntries['EntryStatus'];
        $ExpenseEntryType = 'Imprest';
        
        $TotalAmount = 0;
        foreach($costmaster as $k=>$v)
        {
            if($v) $TotalAmount +=$v;
        }
        
        $Total = $this->ExpenseMaster->find('first',array('fields'=>array('Amount','Id'),'conditions'=>
                array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
            
            $TotalBalance = empty($Total['ExpenseMaster']['Amount'])?0:$Total['ExpenseMaster']['Amount'];
            $Parent = $Total['ExpenseMaster']['Id'];
            
            $Expense = $this->ExpenseEntryMaster->query("select sum(Amount) `Amount` from expense_entry_master where "
                    . "BranchId='$BranchId' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' AND HeadId='$HeadId' AND SubHeadId='$SubHeadId'");
            
            $consumeAmount = empty($Expense['0']['0']['Amount'])?0:$Expense['0']['0']['Amount'];
            
            $RemainingAmount = $TotalBalance-$consumeAmount;
            $createdate = date('Y-m-d H:i:s');
            
            if(($RemainingAmount-$TotalAmount)>=0 && $TotalAmount)
            {
                $this->ExpenseEntryMaster->save(array( 'ExpenseEntryType'=>$ExpenseEntryType,
                                                    'BranchId'=>$BranchId,
                                                    'FinanceYear'=>$FinanceYear,
                                                    'FinanceMonth'=>$FinanceMonth,
                                                    'HeadId'=>$HeadId,
                                                    'SubHeadId'=>$SubHeadId,
                                                    'Amount'=>$TotalAmount,
                                                    'Description'=>$Description,
                                                    'ExpenseDate'=>$EntryDate,
                                                    'createdate'=>$createdate,
                                                    'EntryStatus'=>$EntryStatus,
                                                    'Parent'=>$Parent,
                                                    'userid'=>$this->Session->read('userid')));
                
                $Id = $this->ExpenseEntryMaster->getLastInsertId();
                
                foreach($costmaster as $k=>$v)
                {
                    if($v) 
                    {
                        $this->ExpenseEntryParticular->saveAll(array('ExpenseEntryType'=>$ExpenseEntryType,'ExpenseEntry'=>$Id,'CostCenterId'=>$k,'Amount'=>$v,'createdate'=>$createdate));
                    }
                }
            }
            
            if(($RemainingAmount-$TotalAmount)==0 || $EntryStatus=='Close')
            {
                $this->ExpenseMaster->updateAll(array('EntryStatus'=>'0'),array('Id'=>$Parent)); 
            }
            
            $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            
            if($monthArray[$FinanceMonth]<=3) 
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0]+1;
                }
                else
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0];
                }
            
            $GrnNO = 'MasNew-'.$FinanceYear."/".$monthArray[$FinanceMonth]."/"."$Id";
            $this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id));
            $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
            
            
            
            $ImprestDetails = $this->ExpenseEntryMaster->query("SELECT * FROM `expense_entry_master` em 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
INNER JOIN branch_master bm ON em.BranchId = bm.id
INNER JOIN tbl_user tu ON em.userid = tu.id
WHERE em.Id='$Id' limit 1");
                    
                    $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE BranchId='$BranchId' AND emailType='GRN'");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                        $branchName = $email['bm']['branch_name'];
                    }
                    
                    
                    $sub = 'Imprest Creation : ('.$branchName.')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Imprest Entry Done. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Imprest Entry Done<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Remarks:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Description'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Created By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['tu']['username'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

            if(!$TotalAmount)
            {
                $ImprestDetails = $this->ExpenseMaster->query("SELECT * FROM `expense_master` em 
                INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
                INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
                INNER JOIN branch_master bm ON em.BranchId = bm.id
                INNER JOIN tbl_user tu ON em.userid = tu.id
                WHERE em.BranchId='$BranchId' and em.FinanceYear='$FinanceYear' and em.FinanceMonth='$FinanceMonth' And em.HeadId='$HeadId' AND em.SubHeadId='$SubHeadId' limit 1");
               $sub = 'Business Case '.$ImprestDetails['0']['em']['Id'].' Closed : ('.$branchName.')'; 
               $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Business Case Close Successfully. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Business Case Closed<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Objective:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['objective'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Closed By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $this->Session->read('username').'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 
             $this->Session->setFlash(__("Business Case Close Successfully"));   
            }    
                    
                    
                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);
            
            $this->redirect(array('action'=>'entry_imprest'));
            
        //exit;
    }
    
    public function entry_vendor()
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
            'order' => array('branch_name' => 'asc')))); 
        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
        $this->set('Vendor',$this->VendorMaster->find('list',array('fields'=>array('Id','vendor'))));
    }
    
    public function vendor_save()
    {
        //print_r($this->request->data); exit;
        $costmaster = $this->request->data['cost'];
        $GrnEntries = $this->request->data['GrnEntries'];
        $BranchId=$GrnEntries['BranchId'];
        $FinanceYear = $GrnEntries['FinanceYear'];
        $FinanceMonth = $GrnEntries['FinanceMonth'];
        $HeadId = $GrnEntries['HeadId'];
        $SubHeadId = $GrnEntries['SubHeadId'];
        $Description = $GrnEntries['description'];
        $EntryDate = $GrnEntries['EntryDate'];
        $EntryStatus = $GrnEntries['EntryStatus'];
        $vendor = $GrnEntries['Vendor'];
        $ExpenseEntryType = 'Vendor';
        
        $TotalAmount = 0;
        foreach($costmaster as $k=>$v)
        {
            if($v) $TotalAmount +=$v;
        }
        
        $Total = $this->ExpenseMaster->find('first',array('fields'=>array('Amount','Id'),'conditions'=>
                array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
            
            $TotalBalance = empty($Total['ExpenseMaster']['Amount'])?0:$Total['ExpenseMaster']['Amount'];
            $Parent = $Total['ExpenseMaster']['Id'];
            
            $Expense = $this->ExpenseEntryMaster->query("select sum(Amount) `Amount` from expense_entry_master where "
                    . "BranchId='$BranchId' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' AND HeadId='$HeadId' AND SubHeadId='$SubHeadId'");
            
            $consumeAmount = empty($Expense['0']['0']['Amount'])?0:$Expense['0']['0']['Amount'];
            
            $RemainingAmount = $TotalBalance-$consumeAmount;
            $createdate = date('Y-m-d H:i:s');
            if(($RemainingAmount-$TotalAmount)>=0 && $TotalAmount)
            {
                $this->ExpenseEntryMaster->save(array( 'ExpenseEntryType'=>$ExpenseEntryType,
                                                    'BranchId'=>$BranchId,
                                                    'Vendor' =>$vendor,
                                                    'FinanceYear'=>$FinanceYear,
                                                    'FinanceMonth'=>$FinanceMonth,
                                                    'HeadId'=>$HeadId,
                                                    'SubHeadId'=>$SubHeadId,
                                                    'Amount'=>$TotalAmount,
                                                    'Description'=>$Description,
                                                    'ExpenseDate'=>$EntryDate,
                                                    'createdate'=>$createdate,
                                                    'EntryStatus'=>$EntryStatus,
                                                    'Parent'=>$Parent,
                                                    'userid'=>$this->Session->read('userid')));
                
                $Id = $this->ExpenseEntryMaster->getLastInsertId();
                
                foreach($costmaster as $k=>$v)
                {
                    if($v) 
                    {
                        $this->ExpenseEntryParticular->saveAll(array('ExpenseEntryType'=>$ExpenseEntryType,'ExpenseEntry'=>$Id,'CostCenterId'=>$k,'Amount'=>$v,'createdate'=>$createdate));
                    }
                }
            }
            
            if(($RemainingAmount-$TotalAmount)==0 || $EntryStatus=='Close')
            {
                $this->ExpenseMaster->updateAll(array('EntryStatus'=>'0'),array('Id'=>$Parent)); 
            }
            
            $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            
            if($monthArray[$FinanceMonth]<=3) 
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0]+1;
                }
                else
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0];
                }
            
            $GrnNO = 'MasNew-'.$FinanceYear."/".$monthArray[$FinanceMonth]."/"."$Id";
            $this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id));
            $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
            
            $ImprestDetails = $this->ExpenseEntryMaster->query("SELECT * FROM `expense_entry_master` em 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
INNER JOIN branch_master bm ON em.BranchId = bm.id
INNER JOIN tbl_user tu ON em.userid = tu.id
WHERE em.Id='$Id' limit 1");
                    
                    $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE BranchId='$BranchId' AND emailType='GRN' limit 1");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                        $branchName = $email['bm']['branch_name'];
                    }
                    
                    
                    $sub = 'Vendor Creation : ('.$branchName.')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Vendor Entry Done. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Vendor Entry Done<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Remarks:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Description'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Created By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['tu']['username'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

            if(!$TotalAmount)
            {
                $ImprestDetails = $this->ExpenseMaster->query("SELECT * FROM `expense_master` em 
                INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
                INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
                INNER JOIN branch_master bm ON em.BranchId = bm.id
                INNER JOIN tbl_user tu ON em.userid = tu.id
                WHERE em.BranchId='$BranchId' and em.FinanceYear='$FinanceYear' and em.FinanceMonth='$FinanceMonth' And em.HeadId='$HeadId' AND em.SubHeadId='$SubHeadId' limit 1");
               $sub = 'Business Case '.$ImprestDetails['0']['em']['Id'].' Closed : ('.$branchName.')'; 
               $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Business Case Close Successfully. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Business Case Closed<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Objective:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['objective'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Closed By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $this->Session->read('username').'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 
             $this->Session->setFlash(__("Business Case Close Successfully"));   
            }    
                    
                    
                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);
            $this->redirect(array('action'=>'entry_vendor'));
            
        //exit;
    }
    
    public function entry_salary()
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
            'order' => array('branch_name' => 'asc')))); 
        $this->set('FinanceYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
        $this->set('head',$this->Tbl_bgt_expenseheadingmaster->find('list',array('fields'=>array('HeadingId','HeadingDesc'))));
    }
    
    public function salary_save()
    {
        //print_r($this->request->data); exit;
        $costmaster = $this->request->data['cost'];
        $GrnEntries = $this->request->data['GrnEntries'];
        $BranchId=$GrnEntries['BranchId'];
        $FinanceYear = $GrnEntries['FinanceYear'];
        $FinanceMonth = $GrnEntries['FinanceMonth'];
        $HeadId = $GrnEntries['HeadId'];
        $SubHeadId = $GrnEntries['SubHeadId'];
        $Description = $GrnEntries['description'];
        $EntryDate = $GrnEntries['EntryDate'];
        $EntryStatus = $GrnEntries['EntryStatus'];
        $ExpenseEntryType = 'Imprest';
        
        $TotalAmount = 0;
        foreach($costmaster as $k=>$v)
        {
            if($v) $TotalAmount +=$v;
        }
        
        $Total = $this->ExpenseMaster->find('first',array('fields'=>array('Amount','Id'),'conditions'=>
                array('BranchId'=>$BranchId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'HeadId'=>$HeadId,'SubHeadId'=>$SubHeadId)));
            
            $TotalBalance = empty($Total['ExpenseMaster']['Amount'])?0:$Total['ExpenseMaster']['Amount'];
            $Parent = $Total['ExpenseMaster']['Id'];
            
            $Expense = $this->ExpenseEntryMaster->query("select sum(Amount) `Amount` from expense_entry_master where "
                    . "BranchId='$BranchId' AND FinanceYear='$FinanceYear' AND FinanceMonth='$FinanceMonth' AND HeadId='$HeadId' AND SubHeadId='$SubHeadId'");
            
            $consumeAmount = empty($Expense['0']['0']['Amount'])?0:$Expense['0']['0']['Amount'];
            
            $RemainingAmount = $TotalBalance-$consumeAmount;
            $createdate = date('Y-m-d H:i:s');
            if(($RemainingAmount-$TotalAmount)>=0 && $TotalAmount)
            {
                $this->ExpenseEntryMaster->save(array( 'ExpenseEntryType'=>$ExpenseEntryType,
                                                    'BranchId'=>$BranchId,
                                                    'FinanceYear'=>$FinanceYear,
                                                    'FinanceMonth'=>$FinanceMonth,
                                                    'HeadId'=>$HeadId,
                                                    'SubHeadId'=>$SubHeadId,
                                                    'Amount'=>$TotalAmount,
                                                    'Description'=>$Description,
                                                    'ExpenseDate'=>$EntryDate,
                                                    'createdate'=>$createdate,
                                                    'EntryStatus'=>$EntryStatus,
                                                    'Parent'=>$Parent,
                                                    'userid'=>$this->Session->read('userid')));
                
                $Id = $this->ExpenseEntryMaster->getLastInsertId();
                
                foreach($costmaster as $k=>$v)
                {
                    if($v) 
                    {
                        $this->ExpenseEntryParticular->saveAll(array('ExpenseEntryType'=>$ExpenseEntryType,'ExpenseEntry'=>$Id,'CostCenterId'=>$k,'Amount'=>$v,'createdate'=>$createdate));
                    }
                }
            }
            
            if(($RemainingAmount-$TotalAmount)==0 || $EntryStatus=='Close')
            {
                $this->ExpenseMaster->updateAll(array('EntryStatus'=>'0'),array('Id'=>$Parent)); 
            }
            
            $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
            
            if($monthArray[$FinanceMonth]<=3) 
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0]+1;
                }
                else
                {
                    $FinanceYear1 = explode('-',$FinanceYear);
                    $FinanceYear = $FinanceYear1[0];
                }
            
            $GrnNO = 'MasNew-'.$FinanceYear."/".$monthArray[$FinanceMonth]."/"."$Id";
            $this->ExpenseEntryMaster->updateAll(array('GrnNo'=>"'".$GrnNO."'"),array('Id'=>$Id));
            $this->Session->setFlash(__("Grn No. $GrnNO Save Successfully"));
            
            $ImprestDetails = $this->ExpenseEntryMaster->query("SELECT * FROM `expense_entry_master` em 
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
INNER JOIN branch_master bm ON em.BranchId = bm.id
INNER JOIN tbl_user tu ON em.userid = tu.id
WHERE em.Id='$Id' limit 1");
                    
                    $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE BranchId='$BranchId' AND emailType='GRN' limit 1");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                        $branchName = $email['bm']['branch_name'];
                    }
                    
                    
                    $sub = 'Salary Creation : ('.$branchName.')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Salary Entry Done. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Salary Entry Done<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Remarks:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Description'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Created By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['tu']['username'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

            if(!$TotalAmount)
            {
                $ImprestDetails = $this->ExpenseMaster->query("SELECT * FROM `expense_master` em 
                INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
                INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
                INNER JOIN branch_master bm ON em.BranchId = bm.id
                INNER JOIN tbl_user tu ON em.userid = tu.id
                WHERE em.BranchId='$BranchId' and em.FinanceYear='$FinanceYear' and em.FinanceMonth='$FinanceMonth' And em.HeadId='$HeadId' AND em.SubHeadId='$SubHeadId' limit 1");
               $sub = 'Business Case '.$ImprestDetails['0']['em']['Id'].' Closed : ('.$branchName.')'; 
               $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Business Case Close Successfully. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Business Case Closed<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Year:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceYear']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Month:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['FinanceMonth'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$ImprestDetails['0']['hm']['HeadingDesc'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Expense Sub Head:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['shm']['SubHeadingDesc'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Amount:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['Amount'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Objective:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $ImprestDetails['0']['em']['objective'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Closed By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $this->Session->read('username').'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 
             $this->Session->setFlash(__("Business Case Close Successfully"));   
            }    
                    
                    
                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);
            $this->redirect(array('action'=>'entry_salary'));
            
        //exit;
    }
   
    public function select_entry()
    {
        $this->layout="home";
        if($this->request->is('Post'))
        {
            //print_r($this->request->data); exit;
            
            $entry= $this->request->data['selectEntry'];
            
            if($entry=='Imprest')
            {
                $this->redirect(array('controller'=>'Gms',"action"=>'imprest_entry'));
            }
            else if($entry=='Vendor')
            {
                $this->redirect(array("controller"=>"Gms",'action'=>'index'));
            }
            else if($entry=='Salary')
            {
                $this->redirect(array('action'=>'entry_salary'));
            }
            else
            {
                
            }
        }
        
    }
    
    public function get_grn_no()
    {
        $this->layout="ajax";
        $CompId = $this->request->data['CompId'];
        $FinanceYear = $this->request->data['FinanceYear'];
        $FinanceMonth = $this->request->data['FinanceMonth'];
        $grnNo = $this->request->data['GrnNo'];
        
        $qry = " Where eem.EntryStatus!='Booked'";
        if($CompId !='All')
        {
            $qry .=" and eem.CompId='".$CompId."'";
        }
        if($FinanceYear!='All')
        {
            $qry .=" and eem.FinanceYear='".$FinanceYear."'";
        }
        if($FinanceMonth!='All')
        {
            $qry .=" and eem.FinanceMonth='".$FinanceMonth."'";
        }
        
        if(!empty($grnNo))
        {
            $qry .=" and SUBSTRING_INDEX(eem.GrnNo,'/',-1)='".$grnNo."' limit 1";
        }
        
        $data = $this->ExpenseEntryMaster->query("SELECT eem.*,tu.UserName,hm.HeadingDesc,shm.SubHeadingDesc,IF(eem.ExpenseEntryType='Vendor',vm.vendor,eem.ExpenseEntryType) 
ExpenseType FROM expense_entry_master eem  INNER JOIN tbl_user tu ON eem.userid = tu.Id
 INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId 
 INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId 
 LEFT JOIN tbl_vendormaster vm ON eem.Vendor = vm.id     $qry");
        
        $i=1;
            $html = '<table border="2"><tr><th><input type="checkbox" name="checkAll" onclick="checkAllBox()" id="checkAll" />Select</th><th>Grn No.</th><th>GRN Type</th><th>Branch</th><th>FinanceYear</th>'
                    . '<th>FinanceMonth</th><th>Bill No</th><th>Bill Date</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th><th>Tax</th><th>Total</th><th>Description</th><th>Dated</th><th>Entry Date</th><th>Create By</th></tr>';
            
            foreach($data as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.'<input type="checkbox" name="check[]" value="'.$ro['eem']['Id'].'"></td>';
                $html .= '<td>'.$ro['eem']['GrnNo'].'</td>';
                $html .= '<td>'.$ro['0']['ExpenseType'].'</td>';
                $html .= '<td>'.$ro['bm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['eem']['FinanceYear'].'</td>';
                $html .= '<td>'.$ro['eem']['FinanceMonth'].'</td>';
                $html .= '<td>'.$ro['eem']['bill_no'].'</td>';
                $html .= '<td>'.$ro['eem']['bill_date'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                $html .= '<td>'.$ro['eem']['Amount'].'</td>';
                $html .= '<td>'.$ro['eem']['Tax'].'</td>';
                $html .= '<td>'.$ro['eem']['Total'].'</td>';
                $html .= '<td>'.$ro['eem']['Description'].'</td>';
                $html .= '<td>'.$ro['eem']['ExpenseDate'].'</td>';
                $html .= '<td>'.$ro['eem']['createdate'].'</td>';
                $html .= '<td>'.$ro['tu']['UserName'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';
            
            echo $html;
        exit;
    }
    
    
    public function book_grn_no()
    {
        $this->layout="home";
        $this->set('company_name', $this->Addcompany->find('list',array('fields'=>array('id','company_name'),
            'order' => array('company_name' => 'asc')))); //provide textbox and view branches
        
        $this->set('FinanceYear',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
        
        
        if($this->request->is('POST'))
        {
            
            if($this->request->data['submit']=='Booked')
            {
                $check = $this->request->data['check'];
                $remarks = $this->request->data['GrnEntries']['Remarks'];
                
                foreach ($check as $ch)
                {
                    //$this->ExpenseEntryMaster->updateAll(array('EntryStatus'=>"'Booked'",'Remarks'=>"'".$remarks[$ch]."'"),array('Id'=>$ch));
                    $this->ExpenseEntryMaster->updateAll(array('EntryStatus'=>"'Booked'",'Remarks'=>"'".$remarks."'",'book_by'=>$this->Session->read('userid'),'book_date'=>"'".date('Y-m-d H:i:s')."'"),array('Id'=>$ch));
                }
                
                $this->Session->setFlash(__('GRN No. has been booked successfully.'));
            }
            else
            {
                if($_FILES['file']['size'])
                {
                  if(($_FILES['file']['type']=='application/vnd.ms-excel' || $_FILES['file']['type']=='application/octet-stream'))
                    {
                        $FilePath = $_FILES['file']['tmp_name'];
                        $files = fopen($FilePath, "r");
                            
                        while($row = fgetcsv($files,5000,","))
                        {
                            //print_r($row);
                            if(!empty($row))
                            {
                               $this->ExpenseEntryMaster->updateAll(array('EntryStatus'=>"'Booked'",'Remarks'=>"'".addslashes($row[1])."'",'book_by'=>$this->Session->read('userid'),'book_date'=>"'".date('Y-m-d H:i:s')."'"),array('GrnNo'=>$row[0])); 
                            }
                        }
                    }  
                }
                $this->Session->setFlash(__('GRN File Uploaded successfully.'));
            }
            $this->redirect(array('action'=>'book_grn_no'));
        }
        
    }
    
    
    public function get_grn_delete()
    {
        $this->layout="ajax";
        $branch = $this->request->data['Branch'];
        $grnNo = $this->request->data['grn_no'];
        
        $qry = " and em.EntryStatus!='Booked'";
        if($branch !='All')
        {
            $qry .=" and em.BranchId='".$branch."'";
        }
        
        if(!empty($grnNo))
        {
            $qry .=" and SUBSTRING_INDEX(em.GrnNo,'/',-1)='".$grnNo."' limit 1";
        }
        
        $data = $this->ExpenseEntryMaster->query("SELECT * FROM expense_entry_master em INNER JOIN 
branch_master bm ON em.BranchId = bm.id
INNER JOIN  tbl_user tu ON em.userid = tu.Id
        INNER JOIN  `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId
        INNER JOIN  `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId
        LEFT JOIN vendor_master vm ON em.Vendor = vm.id
WHERE 1=1 
  $qry ");
        
        $i=1;
            $html = '<table border="2"><tr><th><input type="checkbox" name="checkAll" onclick="checkAllBox()" id="checkAll" />Select</th><th>Grn No.</th><th>GRN Type</th><th>Branch</th><th>FinanceYear</th>'
                    . '<th>FinanceMonth</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th><th>Description</th><th>Dated</th><th>Entry Date</th><th>Create By</th></tr>';
            
            foreach($data as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.'<input type="checkbox" name="check[]" value="'.$ro['em']['Id'].'"></td>';
                $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                $html .= '<td>'.$ro['0']['ExpenseType'].'</td>';
                $html .= '<td>'.$ro['bm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['em']['FinanceYear'].'</td>';
                $html .= '<td>'.$ro['em']['FinanceMonth'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                $html .= '<td>'.$ro['em']['Amount'].'</td>';
                $html .= '<td>'.$ro['em']['Description'].'</td>';
                $html .= '<td>'.$ro['em']['ExpenseDate'].'</td>';
                $html .= '<td>'.$ro['em']['createdate'].'</td>';
                $html .= '<td>'.$ro['tu']['UserName'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';
            
            echo $html;
        exit;
    }
    
    public function view_grn()
    {
        $this->layout="home";
        $role = $this->Session->read('role');
        $branch = $this->Session->read("branch_name");
        if($role=='admin')
        {
            $condition=array('active'=>1);
            
        }
        else
        {
            $condition=array('active'=>1,'branch_name'=>$branch);
            $qry = " AND bm.branch_name='$branch'";
        }
        $this->set('branch_master', $this->Addbranch->find('list',array('conditions'=>$condition,'fields'=>array('id','branch_name'),
            'order' => array('branch_name' => 'asc')))); //provide textbox and view branches
        
        
        if($this->request->is('POST'))
        {
            
            if($this->request->data['submit']=='Delete')
            {
                $check = $this->request->data['check'];
                foreach ($check as $ch)
                {
                    $this->ExpenseEntryMaster->deleteAll(array('Id'=>$ch));
                    $this->ExpenseEntryParticular->deleteAll(array('ExpenseEntry'=>$ch));
                }
                
                $this->Session->setFlash(__('GRN No. has been deleted successfully.'));
            }
            
            $this->redirect(array('action'=>'view_grn'));
        } 
    }
    
}

?>