<?php
class SalaryHeadsController extends AppController 
{
    public $uses = array('SalaryHead','Addbranch','CostCenterMaster','CostCenterTransferFrom','CostCenterTransferFromFinal',
        'CostCenterTransferTo','CostCenterTransferToFinal','SalaryUpload','BillMaster');
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->layout='home';
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('edit','view_head','view_expense_head','proportionate_cost_distribution','get_cost_center','save_from','save_to');
            if(in_array('1',$roles)){$this->Auth->allow('index');$this->Auth->allow('add');$this->Auth->allow('edit','view_head','view_expense_head');}
            else{$this->Auth->deny('index');$this->Auth->deny('add');$this->Auth->deny('edit');}
        }	
    }

    public function index() 
    {
        $data = $this->SalaryHead->find('all'); 

        $this->set('SalaryHeadMaster', $data);
        $this->layout='home';
    }

    public function add() 
    {
         if ($this->request->is('post')) 
         {
            $data['SalaryHead']['HeadType'] =  $this->request->data['SalaryHeads']['HeadType'];
            $data['SalaryHead']['SalaryHead'] =  $this->request->data['SalaryHeads']['SalaryHead'];
            $data['SalaryHead']['SalaryColumn'] =  $this->request->data['SalaryHeads']['SalaryColumn'];

             if ($this->SalaryHead->save($data))
             {
                 $this->Session->setFlash(__('Record Has Been Saved'));    
             }
             else
             {
                 $this->Session->setFlash(__('Record has been not Saved'));
             }
             return $this->redirect(array('action' => 'index'));
         }
    }
    public function proportionate_cost_distribution() 
    {
        $this->set('branch',$this->Addbranch->find("list",array("conditions"=>"Active='1'",'fields'=>array('id','branch_name')))) ;
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'order'=>array('id'=>'desc'),'limit'=>3)));
        $userid = $this->Session->read("userid");
$this->layout="home";
        if ($this->request->is('post')) 
        {
            $TotalAmountAdded = $this->CostCenterTransferTo->query("SELECT sum(ToAmount)Total FROM `tmp_cost_center_cost_transfer_particular`  ccctp Where createby='$userid'");
            $TotalAmountMoved = $this->CostCenterTransferFrom->query("SELECT sum(FromAmount)Total FROM `tmp_cost_center_cost_transfer_master`  ccctp Where createby='$userid'");
            
            
            
            if($TotalAmountMoved['0']['0']['Total']==$TotalAmountAdded['0']['0']['Total'])
            {
                $Master = $this->CostCenterTransferFrom->find('first',array('conditions'=>array('createby'=>$userid)));
                $data['CostCenterTransferFromFinal'] = Hash::remove($Master['CostCenterTransferFrom'],'Id');
                
                 if($this->CostCenterTransferFromFinal->save($data))
                 {
                     $LastId = $this->CostCenterTransferFromFinal->getLastInsertID();
                     $ParticularAll = $this->CostCenterTransferTo->find('all',array('conditions'=>array('createby'=>$userid)));

                     $i=0;
                     foreach($ParticularAll as $part)
                     {
                        $NewPart[$i]['CostCenterTransferToFinal']['TransferId'] = $LastId;
                        $NewPart[$i]['CostCenterTransferToFinal']['FinanceYear'] = $part['CostCenterTransferTo']['FinanceYear'];
                        $NewPart[$i]['CostCenterTransferToFinal']['FinanceMonth'] = $part['CostCenterTransferTo']['FinanceMonth'];
                        $NewPart[$i]['CostCenterTransferToFinal']['ToBranch'] = $part['CostCenterTransferTo']['ToBranch'];
                        $NewPart[$i]['CostCenterTransferToFinal']['ToCostCenter'] = $part['CostCenterTransferTo']['ToCostCenter'];
                        $NewPart[$i]['CostCenterTransferToFinal']['ToAmount'] = $part['CostCenterTransferTo']['ToAmount'];
                        $NewPart[$i]['CostCenterTransferToFinal']['createdate'] = $part['CostCenterTransferTo']['createdate'];
                        $NewPart[$i++]['CostCenterTransferToFinal']['createby'] = $part['CostCenterTransferTo']['createby'];
                     }

                     if($this->CostCenterTransferToFinal->saveAll($NewPart))
                     {
                        $this->CostCenterTransferTo->deleteAll(array('createby'=>$userid));
                        $this->CostCenterTransferFrom->deleteAll(array('createby'=>$userid));
                        $this->Session->setFlash("Record Saved Successfully");
                     }
                 } 
            }
            else
            {
                $amt = $this->CostCenterTransferFrom->query("Select FromCostCenter,FromAmount from tmp_cost_center_cost_transfer_master ccm where createby='$userid'");
                $this->set('amount',$amt['0']['ccm']['FromAmount']);
                $this->set('cost_center',array($amt['0']['ccm']['FromCostCenter']=>$amt['0']['ccm']['FromCostCenter']));
                //$this->CostCenterTransferFrom->query("delete from `tmp_cost_center_cost_transfer_particular`");
                $data = $this->CostCenterTransferTo->query("SELECT * FROM `tmp_cost_center_cost_transfer_particular`  ccctp INNER JOIN branch_master bm ON ccctp.ToBranch = bm.id WHERE createby='$userid'");
                $this->set('data',$data);
                $this->Session->setFlash("Total Amount not Matched With Entry Amount");
            }
        }
    }
    public function save_from()
    {
        $data['CostCenterTransferFrom']['FromBranch']       = $Branch           =     $this->request->data['Branch'];
        $data['CostCenterTransferFrom']['FromCostCenter']   = $CostCenter       = $this->request->data['CostCenter'];
        $data['CostCenterTransferFrom']['FromAmount']       = $Amount           = $this->request->data['Amount'];
        $data['CostCenterTransferFrom']['FinanceYear']      = $FinanceYear      = $this->request->data['FinanceYear'];
        $data['CostCenterTransferFrom']['FinanceMonth']     = $FinanceMonth     = $this->request->data['FinanceMonth'];
        $userid = $this->Session->read('userid');

        $SalaryUpload = $this->SalaryUpload->query("select * from salary_master_upload where BranchId='$Branch' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth'");

        if(!empty($SalaryUpload))
        {
            $SalaryUpload = $this->SalaryUpload->query("select sum(ActualCTC) ActualCTC from salary_master_upload where BranchId='$Branch' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and CostCenter='$CostCenter'");
            $TotalSal = $SalaryUpload['0']['0']['ActualCTC'];

            //$checkRecordExist = $this->CostCenterTransferFromFinal->query("select * from cost_center_cost_transfer_master where FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and  FromBranch='$Branch' and FromCostCenter='$CostCenter'");
            $checkRecordExist = "";
            if(empty($checkRecordExist))
            {
                if($Amount<=$TotalSal)
                {
                    if(empty($this->CostCenterTransferFrom->query("Select * from tmp_cost_center_cost_transfer_master Where createby='$userid'")))
                    {
                        $data['CostCenterTransferFrom']['createby'] = $userid;
                        $data['CostCenterTransferFrom']['createdate'] = date("Y-m-d H:i:s");
                        if($this->CostCenterTransferFrom->save($data))
                        {
                            $LastId = $this->CostCenterTransferFrom->getLastInsertID();
                            $this->CostCenterTransferFrom->query("delete from `tmp_cost_center_cost_transfer_master` where id!='$LastId'");
                            echo "1"; //Record Saved
                        }
                        else
                        {
                            echo "0"; //Record Not Saved
                        } 
                    }
                    else
                    {
                        foreach($data['CostCenterTransferFrom'] as $k=>$v)
                        {
                           $dataUpd[$k] = "'$v'";
                        }
                        if($this->CostCenterTransferFrom->updateAll($dataUpd,array('createby'=>$userid)))
                        {

                            echo "1"; //Record Saved
                        }
                        else
                        {
                            echo "0"; //Record Not Saved
                        }
                    }
            }
                else
                {
                    echo "3"; //Amount is More Than Salary Amount
                }
            }
            else
            {
                echo "4"; //Salary Movement Allready Exists
            }

        }
        else
        {
            echo "2"; //Salary Not Uploaded
        }
        exit;
    }
    public function save_to()
    {
        $data['CostCenterTransferTo']['TransferId'] = $this->request->data['TransferId'];
        $data['CostCenterTransferTo']['ToBranch'] = $this->request->data['Branch'];
        $data['CostCenterTransferTo']['ToCostCenter'] = $this->request->data['CostCenter'];
        $data['CostCenterTransferTo']['ToAmount'] = $Amount = $this->request->data['Amount'];
        $data['CostCenterTransferTo']['FinanceYear'] = $this->request->data['FinanceYear'];
        $data['CostCenterTransferTo']['FinanceMonth'] = $this->request->data['FinanceMonth'];

        $userid = $this->Session->read('userid');
        $data['CostCenterTransferTo']['createby'] = $userid;
        $data['CostCenterTransferTo']['createdate'] = date("Y-m-d H:i:s");


        $TotalAmountAdded = $this->CostCenterTransferTo->query("SELECT sum(ToAmount)Total FROM `tmp_cost_center_cost_transfer_particular`  ccctp Where createby='$userid'");
        $TotalAmountMoved = $this->CostCenterTransferFrom->query("SELECT sum(FromAmount)Total FROM `tmp_cost_center_cost_transfer_master`  ccctp Where createby='$userid'");

        $BalAmount =$TotalAmountMoved['0']['0']['Total']- $TotalAmountAdded['0']['0']['Total'];

        if($Amount<=$BalAmount)
        {
            if($this->CostCenterTransferTo->save($data))
            {
                $data = $this->CostCenterTransferTo->query("SELECT * FROM `tmp_cost_center_cost_transfer_particular`  ccctp INNER JOIN branch_master bm ON ccctp.ToBranch = bm.id WHERE createby='$userid'");
                echo '<table border="2">';

                echo "<tr>";
                    echo "<th>Branch</th>";
                    echo "<th>CostCenter</th>";
                    echo "<th>Amount</th>";
                echo "</tr>";


                foreach($data as $d)
                {
                    echo "<tr>";
                        echo "<td>".$d['bm']['branch_name'].'</td>';
                        echo "<td>".$d['ccctp']['ToCostCenter'].'</td>';
                        echo "<td>".$d['ccctp']['ToAmount'].'</td>';
                    echo "</tr>";
                    $Total +=$d['ccctp']['ToAmount'];
                }
                echo "<tr>";
                        echo '<th colspan="2">Total</th>';
                        echo "<td>".$Total.'</td>';
                    echo "</tr>";
                echo "</table>";
            }
        }
        else
        {
            echo "2"; //Entry Total Amount is Greater Than Bal Amount
        }
        exit;
    }

    public function get_cost_center()
    {
        $Branch = $this->request->data['Branch'];
        echo $Branch;die;
        $costcenterArr = $this->CostCenterMaster->query("SELECT cost_center FROM cost_master cm INNER JOIN branch_master bm ON cm.branch=bm.branch_name WHERE bm.id='$Branch'");
        foreach($costcenterArr as $cost)
        {
            $costList[$cost['cm']['cost_center']] = $cost['cm']['cost_center'];
        }
       echo  json_encode($costList);
        exit;
    } 
}

?>