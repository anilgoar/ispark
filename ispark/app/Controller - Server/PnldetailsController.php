<?php
class PnldetailsController extends AppController 
{
    public $uses=array('PnlMaster','Addbranch','BillMaster','CostCenterMaster','PnlBranchSave','PnlProcessSave');
    public function beforeFilter()
    {
        parent::beforeFilter();
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
        }
        else
        {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            
            if(in_array('166',$roles))
            {
                $this->Auth->allow('index','pnl_records_add','getCostCenter','save_record','get_pnl_branch','get_pnl_process');
            }
            else
            {
                $this->Auth->deny('index');
            } 
        }
    }
    function getCostCenter()
    {
        $BranchId = $this->request->data['BranchId'];
        $BranchRow = $this->Addbranch->find('first',array('conditions'=>"id='$BranchId' and active='1'"));
        $BranchName = $BranchRow['Addbranch']['branch_name'];
        $CostMasterData = $this->CostCenterMaster->find('all',array('fields'=>array('id','CostCenterName','cost_center'),'conditions'=>"branch = '$BranchName' and active=1"));
        foreach($CostMasterData as $cost)
        {
            $CostMaster[$cost['CostCenterMaster']['id']] =  $cost['CostCenterMaster']['CostCenterName'].'-'.$cost['CostCenterMaster']['cost_center'];
        }
        print_r(json_encode($CostMaster)); exit;
    }

    public function index() 
    {
        $this->layout='home';
        if($this->request->is('POST'))
        {
            
            $Desc = $this->request->data['Pnldetails']['Description'];
            $data = $this->request->data['Pnldetails'];
            if(!$this->PnlMaster->find('first',array('conditions'=>array('Description'=>$Desc))))
            {
                foreach($data as $k=>$v)
                {
                    $NewData[$k] = addslashes($v);
                }
                $NewData['CreateBy'] = $this->Session->read('userid');
                $NewData['LastUpdateBy'] = $this->Session->read('userid');
                $NewData['CreateDate'] = date('Y-m-d H:i:s');
                
                if($this->PnlMaster->save(array('PnlMaster'=>$NewData)))
                {
                    $this->Session->setFlash("Record Has been Saved");
                    $this->redirect(array('contoller'=>'Pnldetails','action'=>'index'));
                }
                else
                {
                    $this->Session->setFlash("Record Has been Not Saved! Please Try Again");
                }
            }
            else
            {
                $this->Session->setFlash("Fields Allready Exists");
            }
        }
        $this->set('pnlMaster',$this->PnlMaster->find('all',array('order'=>array('ForPnlType'=>'asc','Description'=>'asc'))));
    }
    
    public function pnl_records_add()
    {
        $this->layout='home';
        $BranchMaster = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        $PnlMaster = $this->PnlMaster->find('all',array('conditions'=>"active=1"));
        $this->set('finance_year', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>"finance_year not in ('14-15','2014-15','2015-16','2016-17','2017-18')")));
        
        $this->set('BranchMaster',$BranchMaster);
        
        $this->set('PnlMaster',$PnlMaster);
        
    }
    
    public function save_record()
    {
        if($this->request->is('POST'))
        {
            $EntryType = $this->request->data['EntryType'];
            $BranchId = $this->request->data['BranchId'];
            $DescId = $this->request->data['DescId'];
            $ProcessId = $this->request->data['ProcessId'];
            $Remarks = addslashes($this->request->data['Remarks']); 
            $Amount = $this->request->data['Amount'];
            $FinanceYear = $this->request->data['FinanceYear'];
            $FinanceMonth = $this->request->data['FinanceMonth'];
            
            if($EntryType=='Branch')
            {
               if($this->PnlBranchSave->find('first',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId'")))
               {
                   $this->PnlBranchSave->updateAll(array('PnlAmount'=>"'".$Amount."'",'Remarks'=>"'".$Remarks."'"),array('pnlMasterId'=>$DescId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'BranchId'=>$BranchId));
               }
               else
               {
                   $NewData['PnlBranchSave']['pnlMasterId'] = $DescId;
                   $NewData['PnlBranchSave']['FinanceYear'] = $FinanceYear;
                   $NewData['PnlBranchSave']['FinanceMonth'] = $FinanceMonth;
                   $NewData['PnlBranchSave']['BranchId'] = $BranchId;
                   $branchNameArr = $this->Addbranch->find('first',array('conditions'=>"id='$BranchId'"));
                   $NewData['PnlBranchSave']['Branch'] = $branchNameArr['Addbranch']['branch_name'];
                   $NewData['PnlBranchSave']['Remarks'] = $Remarks;
                   $NewData['PnlBranchSave']['PnlAmount'] = $Amount;
                   $NewData['PnlBranchSave']['CreateBy'] = $this->Session->read('userid');
                   $NewData['PnlBranchSave']['LastUpdateBy'] = $this->Session->read('userid');
                   $NewData['PnlBranchSave']['CreateDate'] = date('Y-m-d H:i:s');
                   
                   if($this->PnlBranchSave->save($NewData))
                   {
                       $this->Session->setFlash("Record Has been Saved");
                   }
                   else
                   {
                       $this->Session->setFlash("Record Not Saved. Please Try Again");
                   }
               }
                echo '<table class="table">';
                echo '<tr>';
                echo '<td>Branch</td>';
                echo '<td>Finance Year</td>';
                echo '<td>Finance Month</td>';
                echo '<td>Remarks</td>';
                echo '<td>Amount</td>';
                echo '</tr>';

                $BranchDetails = $this->PnlBranchSave->find('all',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId'"));
                foreach($BranchDetails as $branchdet)
                {
                    echo '<tr>';
                    echo '<td>'.$branchdet['PnlBranchSave']['Branch'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['FinanceYear'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['FinanceMonth'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['Remarks'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['PnlAmount'].'</td>';
                    echo '</tr>';
                }
                echo '</table>';
               
               
            }
            if($EntryType=='Process')
            {
                if($this->PnlProcessSave->find('first',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId' and CostCenterId='$ProcessId'")))
               {
                   $this->PnlProcessSave->updateAll(array('PnlAmount'=>"'".$Amount."'",'Remarks'=>"'".$Remarks."'"),array('pnlMasterId'=>$DescId,'FinanceYear'=>$FinanceYear,'FinanceMonth'=>$FinanceMonth,'BranchId'=>$BranchId,'CostCenterId'=>$ProcessId));
               }
               else
               {
                   $NewData['PnlProcessSave']['pnlMasterId'] = $DescId;
                   $NewData['PnlProcessSave']['FinanceYear'] = $FinanceYear;
                   $NewData['PnlProcessSave']['FinanceMonth'] = $FinanceMonth;
                   $NewData['PnlProcessSave']['BranchId'] = $BranchId;
                   $branchNameArr = $this->Addbranch->find('first',array('conditions'=>"id='$BranchId'"));
                   $NewData['PnlProcessSave']['Branch'] = $branchNameArr['Addbranch']['branch_name'];
                   $NewData['PnlProcessSave']['CostCenterId'] = $ProcessId;
                   $costNameArr = $this->CostCenterMaster->find('first',array('conditions'=>"id='$ProcessId'"));
                   $NewData['PnlProcessSave']['ProcessName'] = $costNameArr['CostCenterMaster']['CostCenterName'];
                   $NewData['PnlProcessSave']['OPBranch'] = $costNameArr['CostCenterMaster']['OPBranch'];
                   $OPbranchNameArr = $this->Addbranch->find('first',array('conditions'=>"branch_name='".$costNameArr['CostCenterMaster']['OPBranch']."'"));
                   $NewData['PnlProcessSave']['OPBranchId'] = $OPbranchNameArr['Addbranch']['id'];
                   $NewData['PnlProcessSave']['Remarks'] = $Remarks;
                   $NewData['PnlProcessSave']['PnlAmount'] = $Amount;
                   $NewData['PnlProcessSave']['CreateBy'] = $this->Session->read('userid');
                   $NewData['PnlProcessSave']['LastUpdateBy'] = $this->Session->read('userid');
                   $NewData['PnlProcessSave']['CreateDate'] = date('Y-m-d H:i:s');
                   
                   if($this->PnlProcessSave->save($NewData))
                   {
                       $this->Session->setFlash("Record Has been Saved");
                   }
                   else
                   {
                       $this->Session->setFlash("Record Not Saved. Please Try Again");
                   }
               }
               echo '<table class="table">';
                       echo '<tr>';
                       echo '<td>Branch</td>';
                       echo '<td>Process</td>';
                       echo '<td>Finance Year</td>';
                       echo '<td>Finance Month</td>';
                       echo '<td>Remarks</td>';
                       echo '<td>Amount</td>';
                       echo '</tr>';
                           
                       $ProcessDetails = $this->PnlProcessSave->find('all',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId' and CostCenterId='$ProcessId'"));
                       foreach($ProcessDetails as $processDet)
                       {
                           echo '<tr>';
                           echo '<td>'.$processDet['PnlProcessSave']['Branch'].'</td>';
                           echo '<td>'.$processDet['PnlProcessSave']['ProcessName'].'</td>';
                           echo '<td>'.$processDet['PnlProcessSave']['FinanceYear'].'</td>';
                           echo '<td>'.$processDet['PnlProcessSave']['FinanceMonth'].'</td>';
                           echo '<td>'.$processDet['PnlProcessSave']['Remarks'].'</td>';
                           echo '<td>'.$processDet['PnlProcessSave']['PnlAmount'].'</td>';
                           echo '</tr>';
                       }
                       echo '</table>';
            }
        }
        exit;
    }
    
    public function get_pnl_branch()
    {
        $BranchId = $this->request->data['BranchId'];
        $DescId = $this->request->data['DescId'];
        $FinanceYear = $this->request->data['FinanceYear'];
        $FinanceMonth = $this->request->data['FinanceMonth'];
        
        echo '<table class="table">';
                echo '<tr>';
                echo '<td>Branch</td>';
                echo '<td>Finance Year</td>';
                echo '<td>Finance Month</td>';
                echo '<td>Remarks</td>';
                echo '<td>Amount</td>';
                echo '</tr>';

                $BranchDetails = $this->PnlBranchSave->find('all',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId'"));
                foreach($BranchDetails as $branchdet)
                {
                    echo '<tr>';
                    echo '<td>'.$branchdet['PnlBranchSave']['Branch'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['FinanceYear'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['FinanceMonth'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['Remarks'].'</td>';
                    echo '<td>'.$branchdet['PnlBranchSave']['PnlAmount'].'</td>';
                    echo '</tr>';
                    $Total += $branchdet['PnlBranchSave']['PnlAmount'];
                }
                echo '<tr>';
       echo '<td colspan="4">'.'Total'.'</td>';
       echo '<td>'.$Total.'</td>';
       echo '</tr>';
                echo '</table>';
                exit;
    }
    
    public function get_pnl_process()
    {
        $BranchId = $this->request->data['BranchId'];
        $ProcessId = $this->request->data['ProcessId'];
        $DescId = $this->request->data['DescId'];
        $FinanceYear = $this->request->data['FinanceYear'];
        $FinanceMonth = $this->request->data['FinanceMonth'];
        
        echo '<table class="table">';
       echo '<tr>';
       echo '<td>Branch</td>';
       echo '<td>Process</td>';
       echo '<td>Finance Year</td>';
       echo '<td>Finance Month</td>';
       echo '<td>Remarks</td>';
       echo '<td>Amount</td>';
       echo '</tr>';

       $ProcessDetails = $this->PnlProcessSave->find('all',array('conditions'=>"pnlMasterId='$DescId' and FinanceYear='$FinanceYear' and FinanceMonth='$FinanceMonth' and BranchId='$BranchId' and CostCenterId='$ProcessId'"));
       foreach($ProcessDetails as $processDet)
       {
           echo '<tr>';
           echo '<td>'.$processDet['PnlProcessSave']['Branch'].'</td>';
           echo '<td>'.$processDet['PnlProcessSave']['ProcessName'].'</td>';
           echo '<td>'.$processDet['PnlProcessSave']['FinanceYear'].'</td>';
           echo '<td>'.$processDet['PnlProcessSave']['FinanceMonth'].'</td>';
           echo '<td>'.$processDet['PnlProcessSave']['Remarks'].'</td>';
           echo '<td>'.$processDet['PnlProcessSave']['PnlAmount'].'</td>';
           echo '</tr>';
           $Total += $processDet['PnlProcessSave']['PnlAmount'];
       }
       echo '<tr>';
       echo '<td colspan="5">'.'Total'.'</td>';
       echo '<td>'.$Total.'</td>';
       echo '</tr>';
       echo '</table>';
                exit;
    }
}

?>