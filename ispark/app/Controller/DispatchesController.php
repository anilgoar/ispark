<?php
 class DispatchesController extends AppController{
    public $uses=array('DispatchMaster','Addbranch','User','ExpenseEntryMaster','BillMaster','Tbl_bgt_expenseheadingmaster','ImprestManager','Tbl_bgt_expensesubheadingmaster','BranchEmailMaster');
    public $components = array('RequestHandler');
    		
    public function beforeFilter()
    {
        parent::beforeFilter();
		
        if(!$this->Session->check("username"))
        {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        $this->Auth->allow('index','exist_change','add_grn_dispatch','get_dispatch','get_grn','add_grn_packet','substract_grn_packet','get_grn_for_dispatch',
                'get_packet_grn','received','get_dispatch1','get_imprest','get_received','get_dispatch2','get_sub_heading','view_envelope','view_download','get_rcv_grn','get_pending_grn');
    }
    
    public function get_sub_heading()
    {
        $this->layout="ajax";
        $SubHeading=array();
        if($this->request->is("POST"))
        {   
            if($this->request->data['HeadingId']=='All')
            {
                $SubHeading = array('All'=>'All')+$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc')));
            }
            else
            {
            $SubHeading = array('All'=>'All')+$this->Tbl_bgt_expensesubheadingmaster->find('list',array('fields'=>array('SubHeadingId','SubHeadingDesc'),
                'conditions'=>array($this->request->data)));
            }
            echo json_encode($SubHeading);
        }
        exit;
    }
    public function get_dispatch()
    {
        $this->layout="ajax";
        $BranchSendFrom = $this->request->data['BranchSendFrom'];
        
        $dispatchArr = $this->DispatchMaster->find('list',array('fields'=>array('Id','EnvelopeName'),'conditions'=>array('BranchSendFrom'=>$BranchSendFrom,'DispatchStatus'=>'0')));
        
        print_r(json_encode($dispatchArr)); exit;
    }

    public function get_grn()
    {
        $this->layout="ajax";
        $dispatchId = $this->request->data['dispatchId'];
        
        $grn_master = $this->ExpenseEntryMaster->query("SELECT em.Id,em.GrnNo,HeadingDesc,SubHeadingDesc,Amount FROM expense_entry_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId Where em.dispatchId='$dispatchId'");
        
        $i=1;
            $html = '<table border="2"><tr><th>Sr. No.</th><th>GRN</th>'
                    . '<th>Expense Head</th><th>Expense SubHead</th><th>Amount</th></tr>';
            foreach($grn_master as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.$i++.'</td>';
                $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                $html .= '<td>'.$ro['em']['Amount'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';
            
            echo $html;
        
        //print_r($expense_master); 
        
        exit;
    }

    public function get_dispatch_grn()
    {
        $this->layout="ajax";
        
    }

    public function index()
    {
        $this->layout='home';
        $role = $this->Session->read('role');
        if($role=='admin')
        {
            $condition=array('active'=>1);
        }
        else
        {
            $condition=array('active'=>1,'branch_name'=>$this->Session->read("branch_name"));
        }
            $this->set('branch',$this->Addbranch->find('list',array('fields'=>array('Id','branch_name'),'conditions'=>$condition)));
            $this->set('branch1',$this->Addbranch->find('list',array('fields'=>array('Id','branch_name'),'conditions'=>array('active'=>'1'))));
            
        if($this->request->is('POST'))
        {
            //print_r($this->request->data); exit;
            $New = $this->request->data['New'];
            $Existing = $this->request->data['Existing'];
            $Dispatch = $this->request->data['Dispatch'];
            if(!empty($New))
            {
                $New['CreateDate'] = date('Y-m-d H:i:s');
                $New['CreateBy'] = $this->Session->read('userid');
                if($this->DispatchMaster->find('first',array('conditions'=>$this->request->data['New'])))
                {
                    $this->Session->setFlash(__("Envelope Name '".$New['EnvelopeName']. "' Allready Exists"));
                }
                else if($this->DispatchMaster->save($New))
                {
                    $Id = $this->DispatchMaster->getLastInsertId();
                    $this->DispatchMaster->updateAll(array('EnvelopeName'=>"CONCAT(Id,'-',EnvelopeName)"),array('Id'=>$Id));
                    $this->Session->setFlash(__('Envelope Name '.$New['EnvelopeName']. ' has been Saved'));
                    $this->redirect(array('action'=>'add_grn_dispatch','?'=>array('BranchSendFrom'=>$New['BranchSendFrom'],'EnvelopeName'=>$Id)));
                }
                else
                {
                    $this->Session->setFlash(__('Envelope '.$New['EnvelopeName']. ' has been not Saved'));
                }
            }
            
            else if(!empty($Existing))
            {
                $this->redirect(array('action'=>'add_grn_dispatch','?'=>$Existing));
            }
            else if(!empty($Dispatch))
            {
                if($this->DispatchMaster->updateAll(array(
                    'CourierCompanyName'=>"'".$Dispatch['CourierCompanyName']."'",
                    'ReceiptNo'=>"'".$Dispatch['ReceiptNo']."'",
                    'DispatchBy'=>"'".$this->Session->read('userid')."'",
                    'DispatchDate' =>"'".date('Y-m-d H:i:s')."'",
                    'DispatchStatus'=>1
                    ),
                        array('Id'=>$Dispatch['EnvelopeName'],'DispatchStatus'=>'0')))
                {
                   $this->Session->setFlash(__('Envelope Dispatch Details saved successfully')); 
                   
                    $DispatchDetails = $this->DispatchMaster->query("SELECT *,DATE_FORMAT(DispatchDate,'%d-%b-%Y') Date FROM dispatch_master dm 
INNER JOIN branch_master bm ON dm.BranchSendFrom=bm.id
INNER JOIN tbl_user tu ON dm.DispatchBy=tu.id 
Where dm.Id='".$Dispatch['EnvelopeName']."' limit 1");
                    $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE bm.branch_name='".$DispatchDetails['0']['bm']['branch_name']."' AND emailType='GRN' limit 1");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                    }
                    $sub = 'Envelop Dispatched : ('.$DispatchDetails['0']['bm']['branch_name'].')('.$DispatchDetails['0']['dm']['EnvelopeName'].')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Envelop Dispatched. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Envelop Dispatched<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Courier Name:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$DispatchDetails['0']['dm']['CourierCompanyName']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Receipt No:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['dm']['ReceiptNo'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Envelope No:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            .$DispatchDetails['0']['dm']['EnvelopeName'].'<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Dispatch Date :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['0']['Date'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Dispatched By :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['tu']['emp_name'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);	
                }
                else
                {
                    $this->Session->setFlash(__('Envelope Dispatch Details not saved successfully')); 
                }
            }
            $this->redirect(array('action'=>'index'));
        }
    }
    
    public function add_grn_packet()
    {
        $this->layout="home";
        $ids = rtrim($this->request->data['Ids'],',');
        $EnvelopeName = $this->request->data['EnvelopeName'];
        $this->ExpenseEntryMaster->query("UPDATE `expense_entry_master` SET dispatch='1', dispatchId='$EnvelopeName' WHERE Id IN ($ids)");
        //print_r($ids); exit;
        exit;
    }
    public function substract_grn_packet()
    {
        $this->layout="home";
        $ids = rtrim($this->request->data['Ids'],',');
        $EnvelopeName = $this->request->data['EnvelopeName'];
        $this->ExpenseEntryMaster->query("UPDATE `expense_entry_master` SET dispatch='0', dispatchId=null WHERE Id IN ($ids)");
        //print_r($ids); exit;
        exit;
    }
    
    public function get_grn_for_dispatch()
    {
        $BranchId = $this->request->data['BranchSendFrom'];
        $qry = '';
        if(!empty($this->request->data['GrnNo']))
        {
            $qry .=" and em.GrnNo like '%".$this->request->data['GrnNo']."'";
        }
        
        if(empty($this->request->data['FinanceYear']) || $this->request->data['FinanceYear']=='All')
        {
            
        }
        else 
        {
            $qry .=" and em.FinanceYear='".$this->request->data['FinanceYear']."'";
        }
        
//        if(empty($this->request->data['FinanceMonth']) || $this->request->data['FinanceMonth']=='All')
//        {
//            
//        }
//        else 
//        {
//            $qry .=" and em.FinanceMonth='".$this->request->data['FinanceMonth']."'";
//        }
        
        if(empty($this->request->data['HeadId']) || $this->request->data['HeadId']=='All')
        {
            
        }
        else 
        {
            $qry .=" and em.HeadId='".$this->request->data['HeadId']."'";
        }
        
        if(empty($this->request->data['SubHeadId']) || $this->request->data['SubHeadId']=='All')
        {
            
        }
        else 
        {
            $qry .=" and em.SubHeadId='".$this->request->data['SubHeadId']."'";
        }
        //print_r($qry); exit;
        $Grns = $this->ExpenseEntryMaster->query("SELECT em.Id,em.GrnNo,HeadingDesc,SubHeadingDesc,Amount FROM expense_entry_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId Where em.BranchId='$BranchId' and em.dispatch='0' $qry");
        
        if(!empty($Grns))
            {
                $i=1;
                $html = '<table border="2"><tr><th><input type="checkbox" name="grnAll" onclick="checkAllBox(\'grnAll\')" id="grnAll" />Select All</th><th>GRN</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th>'
                        . '</tr>';
                foreach($Grns as $ro)
                {
                   $html .= '<tr>';
                    $html .= '<td>'.'<input type="checkbox" name="grns[]" value="'.$ro['em']['Id'].'" class="grnAll"></td>';
                    $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                    $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                    $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                    $html .= '<td>'.$ro['em']['Amount'].'</td>';
                   $html .= '</tr>';
                }
                $html .='</table>';

                echo $html;
            }
            else
            {
                echo 'No Data Found';
            }
        exit;                
    }
    
    public function get_packet_grn()
    {
        $dispatchId = $this->request->data['EnvelopeName'];
        $DispatchGrns = $this->ExpenseEntryMaster->query("SELECT em.Id,em.GrnNo,HeadingDesc,SubHeadingDesc,Amount FROM expense_entry_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId Where em.dispatchId='$dispatchId'");
        
         if(!empty($DispatchGrns))
        {
            $i=1;
            $html = '<table border="2"><tr><th><input type="checkbox" name="dispatchAll" onclick="checkAllBox(\'dispatchAll\')" id="dispatchAll" />Select All</th><th>GRN</th><th>Expense Head</th><th>Expense SubHead</th><th>Amount</th>'
                    . '</tr>';
            foreach($DispatchGrns as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.'<input type="checkbox" name="dispatchs[]" value="'.$ro['em']['Id'].'" class="dispatchAll"></td>';
                $html .= '<td>'.$ro['em']['GrnNo'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
                $html .= '<td>'.$ro['em']['Amount'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';

            echo $html;
        }
        else
        {
            echo 'No Data Found';
        }
        exit;                
    }
    
    public function add_grn_dispatch()
    {
        $this->layout="home"; 
        $dispatchId = $this->params->query['EnvelopeName'];
        $BranchId = $this->params->query['BranchSendFrom'];
        $this->set('Dispatch',$this->DispatchMaster->query("select * from dispatch_master dm inner join branch_master bm on dm.BranchSendFrom=bm.id Where dm.Id='$dispatchId'"));
        $this->set('DispatchGrns',$this->ExpenseEntryMaster->query("SELECT em.Id,em.GrnNo,HeadingDesc,SubHeadingDesc,Amount FROM expense_entry_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId Where em.dispatchId='$dispatchId'"));
        $this->set('Grns',$this->ExpenseEntryMaster->query("SELECT em.Id,em.GrnNo,HeadingDesc,SubHeadingDesc,Amount FROM expense_entry_master em 
           INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId 
INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON em.SubHeadId = shm.SubHeadingId Where em.BranchId='$BranchId' and em.dispatch='0'"));
        
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>'2017-18'))));
        $this->set('head',array_merge(array('All'=>'All'),$this->Tbl_bgt_expenseheadingmaster->find('list',array('conditions'=>"EntryBy=''",'fields'=>array('HeadingId','HeadingDesc'),'order'=>array('HeadingDesc'=>'asc')))));
    }
        
   public function received() 
   {
       $this->layout="home";
       $this->set('branch',$this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('active'=>1))));
       if($this->request->is('POST'))
       {
           $GrnMaster = $this->ExpenseEntryMaster->find('list',array('fields'=>array('Id','Id'),'conditions'=>array('DispatchId'=>$this->request->data['Dispatches']['EnvelopeName'])));
           
           
           $checkArray = $this->request->data['check'];
           
           foreach($GrnMaster as $GrnId)
           {
               if(in_array($GrnId,$checkArray))
               {
                   continue;
               }
               else
               {
                   $this->ExpenseEntryMaster->updateAll(array('dispatch'=>0,'dispatchId'=>null,'PendingGrn'=>'0'),array('Id'=>$GrnId));
               }
           }
           
           $imprestManager = "'".$this->request->data['Dispatches']['ImprestManager']."'";
           $receiver = $this->User->find('first',array('conditions'=>array('Id'=>$imprestManager)));
           $date = "'".date('Y-m-d H:i:s')."'";
           if($this->DispatchMaster->updateAll(array('ReceiveBy'=>$imprestManager,'ReceiveDate'=>$date,'DispatchStatus'=>'2'),array('Id'=>$this->request->data['Dispatches']['EnvelopeName'])))
           {
               $this->Session->setFlash(__('Enveolope Has been Received'));
               $DispatchDetails = $this->DispatchMaster->query("SELECT *,DATE_FORMAT(DispatchDate,'%d-%b-%Y') Date FROM dispatch_master dm 
INNER JOIN branch_master bm ON dm.BranchSendFrom=bm.id
INNER JOIN tbl_user tu ON dm.DispatchBy=tu.id 
Where dm.Id='".$this->request->data['Dispatches']['EnvelopeName']."' limit 1");
               
                   $emailBranch = $this ->BranchEmailMaster->query("SELECT * FROM branch_email be INNER JOIN 
                    branch_master bm ON be.BranchId = bm.id
                    WHERE bm.branch_name='".$DispatchDetails['0']['bm']['branch_name']."' AND emailType='GRN' limit 1");
                    
                    foreach($emailBranch as $email)
                    {
                        $email2 = array_filter(explode(',',$email['be']['email']));
                    }

                    //$email2[] = $emailid['User']['email'];
                    //$email2[] = 'naresh.chauhan@teammas.in';
                    $sub = 'Envelop Received : ('.$DispatchDetails['0']['bm']['branch_name'].')('.$DispatchDetails['0']['dm']['EnvelopeName'].')';
                    $msg = '<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="95%" style="width:95.0%;border:solid #153B6E 1.0pt">'
                            . '<tbody>'
                            . '<tr style="height:15.0pt"><td style="border:none;border-bottom:solid #153B6E 1.0pt;background:#5AB3DF;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b>'
                            . '<span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#024262">Envelop Received. Below are the details-<o:p></o:p></span>'
                            . '</b></p></td></tr><tr><td style="border:none;padding:0in 0in 0in 0in"><div align="center"><table class="MsoNormalTable" border="0" cellspacing="3" cellpadding="0" width="100%" style="width:100.0%">'
                            . '<tbody><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status :<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">Envelop Received<o:p></o:p></span></p>'
                            . '</td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Branch:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['bm']['branch_name'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Envelope No:<o:p></o:p></span></p></td><td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['dm']['EnvelopeName']. '<o:p></o:p></span></p></td></tr><tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Hand Over To:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['tu']['username'].'<o:p></o:p></span></p></td></tr>'
                            .'<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Hand Over Date:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $DispatchDetails['0']['0']['Date'].'<o:p></o:p></span></p></td></tr>'
                            . '<tr><td width="22%" style="width:22.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p><span style="font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Received By:<o:p></o:p></span></p></td>'
                            . '<td style="padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . $receiver['User']['emp_name'].'<o:p></o:p></span></p></td></tr><tr><td colspan="2" style="padding:2.25pt 2.25pt 2.25pt 2.25pt"></td></tr>'
                            . '<tr><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom1.jpg" border="0" id="_x0000_i1025">'
                            . '<o:p></o:p></span></p></td><td style="background:#106995;padding:2.25pt 2.25pt 2.25pt 2.25pt"><p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#295594">'
                            . '<img src="http://mascallnetnorth.in/ispark/app/webroot/img/mail_bottom2.jpg" border="0" id="_x0000_i1026">'
                            . '<o:p></o:p></span></p></td></tr><tr style="height:15.0pt"><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Copyright © Mas Infotainment Pvt. Ltd.<o:p></o:p></span></b></p></td><td style="border:none;border-top:solid #153B6E 1.0pt;background:#AFD997;padding:2.25pt 7.5pt 2.25pt 7.5pt;height:15.0pt"><p class="MsoNormal" align="right" style="text-align:right"><b><span style="font-size:9.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#225922">Website :- '
                            . '<a href="http://mascallnetnorth.in/ispark">http://mascallnetnorth.in/ispark</a><o:p></o:p></span></b></p></td></tr></tbody></table></div></td></tr></tbody></table>'; 

                    App::uses('sendEmail', 'custom/Email');
                    $mail = new sendEmail();
                    $mail-> to($email2,$msg,$sub);	
           }
           else
           {
               $this->Session->setFlash(__('Envelope Record Has been not Saved. Please Try Again!'));
           }
           $this->redirect(array('action'=>'received'));
       }
   }
    
   public function get_dispatch1()
   {
       $this->layout="ajax";
       $BranchSendFrom = $this->request->data['BranchSendFrom'];
        
        $dispatchArr = $this->DispatchMaster->find('list',array('fields'=>array('Id','EnvelopeName'),'conditions'=>array('BranchSendFrom'=>$BranchSendFrom,'DispatchStatus'=>'1')));
        
        print_r(json_encode($dispatchArr)); exit;
   }
   
   public function get_imprest()
   {
       $this->layout="ajax";
       $BranchId = $this->request->data['BranchId'];
        $dispatchArr = $this->ImprestManager->find('list',array('fields'=>array('UserId','UserName'),'conditions'=>array('BranchId'=>'3','Active'=>'1')));
        print_r(json_encode($dispatchArr)); exit;
   }     
 
   public function get_received()
   {
       
       //print_r($this->request->data); exit;
       $BranchSendFrom = $this->request->data['BranchSendFrom'];
       $FromDate = $this->request->data['FromDate'];
       $ToDate = $this->request->data['ToDate'];
       $data = $this->DispatchMaster->query("SELECT * FROM dispatch_master dm 
INNER JOIN branch_master sendbm ON dm.BranchSendFrom = sendbm.id 
INNER JOIN branch_master tobm ON dm.BranchSendTo = tobm.id
WHERE dm.DispatchStatus=2 and BranchSendFrom='$BranchSendFrom' AND 
DATE_FORMAT(ReceiveDate,'%m') BETWEEN 
DATE_FORMAT(STR_TO_DATE('$FromDate','%d-%b-%Y'),'%m') 
AND DATE_FORMAT(STR_TO_DATE('$ToDate','%d-%b-%Y'),'%m')");
       
       if(!empty($data))
        {
            $i=1;
            $html = '<table border="2"><tr><th>Sr.No.</th><th>EnvelopeName</th><th>BranchSendFrom</th><th>BranchSendTO</th><th>DispatchDate</th><th>Receive Date</th>'
                    . '</tr>';
            foreach($data as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.$i++.'</td>';
                $html .= '<td>'.$ro['dm']['EnvelopeName'].'</td>';
                $html .= '<td>'.$ro['sendbm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['tobm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['dm']['DispatchDate'].'</td>';
                $html .= '<td>'.$ro['dm']['ReceiveDate'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';

            echo $html;
        }
        else
        {
            echo 'No Data Found';
        }
       
       exit;
   }
   
   public function get_dispatch2()
   {
       
       //print_r($this->request->data); exit;
       $BranchSendFrom = $this->request->data['BranchSendFrom'];
       $FromDate = $this->request->data['FromDate'];
       $ToDate = $this->request->data['ToDate'];
       $data = $this->DispatchMaster->query("SELECT * FROM dispatch_master dm 
INNER JOIN branch_master sendbm ON dm.BranchSendFrom = sendbm.id 
INNER JOIN branch_master tobm ON dm.BranchSendTo = tobm.id
WHERE dm.DispatchStatus=1 and BranchSendFrom='$BranchSendFrom' AND 
DATE_FORMAT(DispatchDate,'%m') BETWEEN 
DATE_FORMAT(STR_TO_DATE('$FromDate','%d-%b-%Y'),'%m') 
AND DATE_FORMAT(STR_TO_DATE('$ToDate','%d-%b-%Y'),'%m')");
       
       if(!empty($data))
        {
            $i=1;
            $html = '<table border="2"><tr><th>Sr.No.</th><th>EnvelopeName</th><th>BranchSendFrom</th><th>BranchSendTO</th><th>DispatchDate</th><th>Receive Date</th>'
                    . '</tr>';
            foreach($data as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.$i++.'</td>';
                $html .= '<td>'.$ro['dm']['EnvelopeName'].'</td>';
                $html .= '<td>'.$ro['sendbm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['tobm']['branch_name'].'</td>';
                $html .= '<td>'.$ro['dm']['DispatchDate'].'</td>';
                $html .= '<td>'.$ro['dm']['ReceiveDate'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';

            echo $html;
        }
        else
        {
            echo 'No Data Found';
        }
       
       exit;
   }
   public function view_envelope()
   {
       $this->layout="home";
       $this->set('dis',$this->DispatchMaster->query("SELECT dis.Id,dis.EnvelopeName,bm1.branch_name,bm2.branch_name FROM dispatch_master dis 
INNER JOIN branch_master bm1 ON dis.BranchSendFrom = bm1.id
INNER JOIN branch_master bm2 ON dis.BranchSendTo = bm2.id
WHERE DispatchStatus =1
ORDER BY EnvelopeName,bm1.branch_name"));
   }
   public function view_download()
   {
       $this->layout="ajax";
       $Id = base64_decode($this->params->query['Id']);
       $this->set('dis',$this->DispatchMaster->query("SELECT dis.Id,dis.EnvelopeName,bm1.branch_name,bm2.branch_name FROM dispatch_master dis 
INNER JOIN branch_master bm1 ON dis.BranchSendFrom = bm1.id
INNER JOIN branch_master bm2 ON dis.BranchSendTo = bm2.id
WHERE DispatchStatus =1 and dis.Id='$Id'
ORDER BY EnvelopeName,bm1.branch_name"));
       
       $this->set('grn',$this->ExpenseEntryMaster->query("SELECT eem.GrnNo,head.HeadingDesc,subhead.SubHeadingDesc FROM expense_entry_master eem 
INNER JOIN `tbl_bgt_expenseheadingmaster` head ON eem.HeadId = head.HeadingId
INNER JOIN `tbl_bgt_expensesubheadingmaster` subhead ON eem.HeadId = subhead.HeadingId AND eem.SubHeadId = subhead.SubHeadingId
WHERE dispatchId='$Id'"));
   }
   public function get_rcv_grn()
   {
       $this->layout="ajax";
        
        $DispatchId = $this->request->data['DisId'];
                
        $data = $this->ExpenseEntryMaster->query("SELECT eem.Id,eem.GrnNo,hm.HeadingDesc,shm.SubHeadingDesc FROM expense_entry_master eem  INNER JOIN tbl_user tu ON eem.userid = tu.Id
 INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON eem.HeadId = hm.HeadingId 
 INNER JOIN `tbl_bgt_expensesubheadingmaster` shm ON eem.SubHeadId = shm.SubHeadingId Where DispatchId='$DispatchId'");
        
        $i=1;
            $html = '<table border="2"><tr><th><input type="checkbox" name="checkAll" onclick="checkAllBox()" id="checkAll" />Select</th><th>Grn No.</th>'
                    . '<th>Expense Head</th><th>Expense SubHead</th></tr>';
            
            foreach($data as $ro)
            {
               $html .= '<tr>';
                $html .= '<td>'.'<input type="checkbox" name="check[]" value="'.$ro['eem']['Id'].'"></td>';
                $html .= '<td>'.$ro['eem']['GrnNo'].'</td>';
                $html .= '<td>'.$ro['hm']['HeadingDesc'].'</td>';
                $html .= '<td>'.$ro['shm']['SubHeadingDesc'].'</td>';
               $html .= '</tr>';
            }
            $html .='</table>';
            
            echo $html;
        exit;
   }
   
   
   
 }
 
 
 