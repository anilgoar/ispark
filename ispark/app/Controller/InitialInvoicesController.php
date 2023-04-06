    <?php
class InitialInvoicesController extends AppController 
{
    public $uses=array('InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster',
        'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount',
        'Provision','PONumber','NotificationMaster','ProvisionPart','ProvisionPartDed');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
		

public function beforeFilter()
{
    parent::beforeFilter();
	
    
    
    if(!$this->Session->check("userid"))
    {
            return $this->redirect(array('controller'=>'users','action' => 'login'));
    }
    else
    {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->deny('index','view','add','billApproval','branch_viewbill','view_bill','genrate_bill','genrate_bill','edit_bill','update_bill','update_bill',
            'branch_view','branch_viewbill','edit_bill','update_bill','view_pdf1','view_pdf','download','view_admin','dashboard','check_po','view_grn','check_grn',
            'approve_ahmd','view_ahmd','view_invoice','edit_invoice','download_grn','approve_grn','edit_forgrn','approve_po','view_forpo','view_pdf','billApproval',
            'reject_invoice','update_invoice','update_po','update_grn','billApproval','view_pdfgrn','view_pdfgrn1','view_pdfgrn2','get_costcenter','get_gst_type',
                    'get_service_no','view_status_change_request','delete_invoice','get_provision_months');

            /*if(in_array('4',$roles)){$this->Auth->allow('index','get_costcenter','get_gst_type','get_service_no');$this->Auth->allow('add','check_po_number');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}
            if(in_array('5',$roles)){$this->Auth->allow('view');$this->Auth->allow('view_bill');$this->Auth->allow('genrate_bill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(in_array('6',$roles)){$this->Auth->allow('branch_view');$this->Auth->allow('branch_viewbill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(in_array('7',$roles)){$this->Auth->allow('download');$this->Auth->allow('view_pdf');$this->Auth->allow('view_pdf1');}
            if(in_array('7',$roles)){$this->Auth->allow('download_proforma');$this->Auth->allow('view_proforma_pdf');$this->Auth->allow('view_proforma_letter_pdf');}
            if(in_array('136',$roles)){$this->Auth->allow('edit_proforma','view_proforma','approve_proforma','move_approve_proforma');$this->Auth->allow('update_proforma','reject_proforma'); }
            if(in_array('137',$roles)){$this->Auth->allow('download_proforma_branch');}
            if(in_array('9',$roles)){$this->Auth->allow('view_admin');$this->Auth->allow('view_forpo');$this->Auth->allow('update_po');}
            if(in_array('10',$roles)){$this->Auth->allow('dashboard');}
            if(in_array('11',$roles)){$this->Auth->allow('check_po');$this->Auth->allow('approve_po');}
            if(in_array('12',$roles)){$this->Auth->allow('view_grn');$this->Auth->allow('edit_forgrn');$this->Auth->allow('update_grn');}
            if(in_array('13',$roles)){$this->Auth->allow('check_grn');$this->Auth->allow('approve_grn');}
            if(in_array('14',$roles) || in_array('17',$roles)){$this->Auth->allow('download_grn','view_pdf','view_pdf1'); $this->Auth->allow('view_pdfgrn');$this->Auth->allow('view_pdfgrn1','view_pdfgrn2');}
            if(in_array('16',$roles)){$this->Auth->allow('approve_ahmd');}
            if(in_array('17',$roles)){$this->Auth->allow('view_ahmd');}
            if(in_array('168',$roles)){$this->Auth->allow('view_status_change_request'); }
            if(in_array('20',$roles)){$this->Auth->allow('view_invoice');$this->Auth->allow('edit_invoice');$this->Auth->allow('update_invoice');
            
            if(in_array('169',$roles)){$this->Auth->allow('delete_invoice'); }
            
            $this->Auth->allow("apply_service_tax","get_provision_months"); $this->Auth->allow("apply_tax_cal"); $this->Auth->allow("apply_krishi_tax"); $this->Auth->allow('reject_invoice');}*/
            
            
            if(1){$this->Auth->allow('index','get_costcenter','get_gst_type','get_service_no');$this->Auth->allow('add','check_po_number');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}
            if(1){$this->Auth->allow('view');$this->Auth->allow('view_bill');$this->Auth->allow('genrate_bill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('branch_view');$this->Auth->allow('branch_viewbill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('download');$this->Auth->allow('view_pdf');$this->Auth->allow('view_pdf1');}
            if(1){$this->Auth->allow('download_proforma');$this->Auth->allow('view_proforma_pdf');$this->Auth->allow('view_proforma_letter_pdf');}
            if(1){$this->Auth->allow('edit_proforma','view_proforma','approve_proforma','move_approve_proforma');$this->Auth->allow('update_proforma','reject_proforma'); }
            if(1){$this->Auth->allow('download_proforma_branch');}
            if(1){$this->Auth->allow('view_admin');$this->Auth->allow('view_forpo');$this->Auth->allow('update_po');}
            if(1){$this->Auth->allow('dashboard');}
            if(1){$this->Auth->allow('check_po');$this->Auth->allow('approve_po');}
            if(1){$this->Auth->allow('view_grn');$this->Auth->allow('edit_forgrn');$this->Auth->allow('update_grn');}
            if(1){$this->Auth->allow('check_grn');$this->Auth->allow('approve_grn');}
            if(1){$this->Auth->allow('download_grn','view_pdf','view_pdf1'); $this->Auth->allow('view_pdfgrn');$this->Auth->allow('view_pdfgrn1','view_pdfgrn2');}
            if(1){$this->Auth->allow('approve_ahmd');}
            if(1){$this->Auth->allow('view_ahmd');}
            if(1){$this->Auth->allow('view_status_change_request'); }
            if(1){$this->Auth->allow('view_invoice');$this->Auth->allow('edit_invoice');$this->Auth->allow('update_invoice');
            
            if(1){$this->Auth->allow('delete_invoice'); }
            
            $this->Auth->allow("apply_service_tax","get_provision_months"); $this->Auth->allow("apply_tax_cal"); $this->Auth->allow("apply_krishi_tax"); $this->Auth->allow('reject_invoice');}
            
    }
    $this->Auth->allow("apply_service_tax","get_provision_months");
    if ($this->request->is('ajax'))
    {
            $this->render('contact-ajax-response', 'ajax');
    }
}

public function get_service_no()
{
    $this->layout = 'ajax';
    $result = $this->request->data;
    $result['active'] = 1;
    $cost = $this->CostCenterMaster->find('first',array('fields'=>array('company_name','branch'),'conditions'=>$result));
    $company_name = $cost['CostCenterMaster']['company_name'];
    $branch = $cost['CostCenterMaster']['branch'];

    $serve = $this->CostCenterMaster->query("SELECT branch,ServiceTaxNo FROM tbl_service_tax ser WHERE (company_name !='$company_name' or branch !='$branch') and ServiceTaxNo IS NOT NULL");
    foreach($serve as $ser)
    {
        $data[$ser['ser']['ServiceTaxNo']]=$ser['ser']['branch']."-".$ser['ser']['ServiceTaxNo'];
    }
    echo json_encode($data);
    exit;
}

public function get_gst_type()
{
    $this->layout = 'ajax';
    $cost = $this->request->data['cost_center'];
    $data = $this->CostCenterMaster->query("select id from cost_master where cost_center='$cost' and  GSTType !='' and GSTTYPE is not null");
    if($data)
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
    exit;
}

public function get_costcenter()
{
    $this->layout = 'ajax';
    $result = $this->request->data;
    $result['active'] = 1; 
    $data=$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','cost_center'),'conditions'=>array($result)));
    echo json_encode($data); exit;
}

public function check_po_number()
{
    $this->layout="ajax";
    $month = $this->request->data['month'];
    $po_nos = $this->request->data['po_number'];
    $grnd = $this->request->data['grnd'];
    $finance = $this->request->data['finance'];

    $monthArr = array('Jan','Feb','Mar');       //Month array for deciding Year end or Year start
    $split = explode('-',$finance);    //explode finance_year
    //print_r($split); die; 
    if(in_array($month, $monthArr)) 
    {
        if($split[0]==date('Y') || $split[1]==date('y'))
        {
            $month .= '-'.date('y');    //Year from month
        }
        else
        {
            $month .= '-'.$split[1];    //Year from month
        }
        
    }
    else
    {
        $month .= '-'.($split[1]-1);    //Year from month
    }

    $poArray = explode(',',$po_nos);

    $amountFlag = true;
    $error = 3;
    $po_error = '';
    $msg = "";

    if(count($poArray)<=4)
    {
        foreach($poArray as $po_no)
        {
            if($amount = $this->PONumber->query("SELECT pn.balAmount FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
WHERE pnp.poNumber = '$po_no' AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
            {
                $grnd -= $amount['0']['pn']['balAmount'];
            }
            else 
            {
                $amountFlag = false;
                $po_error = $po_no;
                $error = 1;
                $msg = "PO Number -$po_no Not Matched##0";
                break;
            }

        }
        if($grnd>=0 && $amountFlag)
        {
             $error = 2;
             $amountFlag =false;
             $msg = "PO Amount is less than Grand Total##0";
        }
    }
    else
    {
                $amountFlag = false;
                $po_error = $po_no;
                $error = 1;
                $msg = "Please Do Not Enter PO Number More Than 4##0";
    }

    if($amountFlag)
    {
        $msg = "OK##1";
    }
    echo $msg; exit;
}

public function get_provision_months()
{
    $this->layout="ajax";
    $year = $this->request->data['year'];
    $month = $this->request->data['month'];
    $branch_name = $this->request->data['branch'];
    $cost_center = $this->request->data['cost_center'];
    
    $monthArr=array(
        '1'=>'Apr','2'=>'May','3'=>'Jun',
        '4'=>'Jul','5'=>'Aug','6'=>'Sep',
        '7'=>'Oct','8'=>'Nov','9'=>'Dec',
        '10'=>'Jan','11'=>'Feb','12'=>'Mar');
    
       $arr_month =explode('-',$year);
    
        $array_print_month = array();
    
        foreach($monthArr as $k=>$v)
        {
            $amt = 0;
            if(in_array($month,array('Jan','Feb','Mar')))
            {
                if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                {
                    $Nmonth=$v."-".date('y');
                }
                else 
                {
                    $Nmonth=$v."-".$arr_month[1];
                }
                
            }
            else
            {
                $Nmonth=$v."-".($arr_month[1]-1); 
            }
            
            //echo "finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center' and provision_balance!=0";  exit;
            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];  
            }
            
            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$year' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$cost_center' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }
            
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$year' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$cost_center' and deduction_status='1'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            if($amt>0)
            {
                $array_print_month[$v] = round($amt,2);
            }
            
            if($v==$month)
            {
                break;
            }
        }
        //print_r($array_print_month); exit;
    echo '<table border="2">';
        echo '<tr><th colspan="3">Please Choose Revenue From Below Months</th></tr>';
        echo "<tr><td>Month</td><td>Provision Amount</td><td>Billing Amount</td></tr>";
        
    foreach($array_print_month as $month=>$arr_revenue)
    {
        echo "<tr>";
            echo '<td>';
            echo '<input type="checkbox" id="'.$month.'" name="data[InitialInvoice][MonthArr]['.$month.']" value="1" onclick="get_display('."'".$month."'".')">'.$month;
            echo "</td>";
            echo '<td>';
            echo $arr_revenue;
            echo '</td>';
            echo '<td><div id="'.$month.'Disp" style="display:none">';
            echo '<input type="text" id="input'.$month.'" name="data[InitialInvoice][Months]['.$month.']" placeholder="Revenue" value="'.$arr_revenue.'" onblur="get_revenue_total()" >';
            echo "</div></td>";
        echo "</tr>";
                
    }
    
    
    
    echo "<tr>";
            echo '<th>';
            echo 'Total';
            echo "</th>";
            echo '<th>';
            
            echo "</th>";
            echo '<td>';
            echo '<div id="Total">0';
            echo "</div></td>";
        echo "</tr>";
    
    echo "</table>";
    
    echo '<input type="hidden" id="month_check" value="'.implode(',',array_keys($array_print_month)).'" >';
    
    exit;
}

public function index() 
{
    $this->InitialInvoice->recursive = 0;
    $this->layout='home';
    
    
    
    //$this->set('tbl_invoice', $this->paginate());
    //$this->set('tbl_invoice', $this->InitialInvoice->find('all'));
    $branch_name=$this->Session->read("branch_name");
    $role = $this->Session->read("role");
    if($role=='admin')
    {
        $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
    }
    else
    {
        $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
    }
    $this->set('client_master', $this->Addclient->find('all',array('conditions'=>array('client_status'=>1),'order'=>array('client_name'=>'asc'))));
    $this->set('cost_master', $this->CostCenterMaster->find('all',array('conditions'=>array('not'=>array('cost_center'=>''),'active'=>1))));
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
}
	   
public function add() 
{
   
    $monthsArrCheck = $this->request->data['InitialInvoice']['MonthArr'];
    $monthsArr = $this->request->data['InitialInvoice']['Months'];
    $monthMaster = array();
    //print_r($monthsArr); exit;
    
    $revenue = 0;
    
    foreach($monthsArrCheck as $mAC=>$mAC_value)
    {
        if(!empty($mAC) && !empty($monthsArr[$mAC]))
        {
            $revenue += $monthsArr[$mAC];
            $monthMaster[$mAC] = $monthsArr[$mAC];
        }
    }
    
    //exit;
    if(empty($revenue))
    {
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px>"
                . "<b style=color:#FF0000>".'Please Fill Some Amount First'."</b></h4>"));
        return $this->redirect(array('controller'=>'InitialInvoices'));
    }
    else
    {
        $this->set('monthMaster',$monthMaster);
        $this->set('revenue',$revenue);
    }
    
    
    
    $invDate = explode('-',$this->request->data['InitialInvoice']['invoiceDate']);
    $gstType = $this->request->data['GSTType'];
    $serv_no = $this->request->data['InitialInvoice']['serv_no'];
    $cost_no = $this->request->data['InitialInvoice']['cost_center'];
    $fin_year = $this->request->data['InitialInvoice']['finance_year'];
    $month    =   $this->request->data['InitialInvoice']['month'];
    $arr =explode('-',$fin_year);
    $invoiceType = $this->request->data['InitialInvoice']['invoiceType'];

    if(in_array($month,array('Jan','Feb','Mar')))
    {
        if($arr[0]==date('Y') || $arr[1]==date('y'))
        {
            $month=$month."-".date('y');
        }
        else
        {
            $month=$month."-".$arr[1];
        }
        
    }
    else
    {
        $month=$month."-".($arr[1]-1);
    }

    $NewMonth = $month;
    //echo "Select provision from provision_master pm where pm.finance_year='$fin_year' AND pm.month='$NewMonth' AND pm.cost_center='$cost_no'"; exit;
    $TotalProvisionX = $this->Provision->query("Select provision from provision_master pm where pm.finance_year='$fin_year' AND pm.month='$NewMonth' AND pm.cost_center='$cost_no'");

    //For Checking Provision
//    if(empty($TotalProvisionX))
//    {
//        $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".'Provision Not Created. Contact To Admin Please.'."</b></h4>"));
//        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'index'));
//    }

   krsort($invDate);
   $invDate=implode("-",$invDate);

//   if(strtotime($invDate)>strtotime("2017-06-30"))
//    {
//       if(!$this->CostCenterMaster->query("select id from cost_master where cost_center='$cost_no' and  GSTType !='' and GSTTYPE is not null"))
//       {
//            if($gstType=='Integrated')
//            {
//               $this->CostCenterMaster->query("update cost_master set GstType='$gstType',ServiceTaxNo='$serv_no' where cost_center='$cost_no'"); 
//            }
//            else
//            {
//                $this->CostCenterMaster->query("UPDATE cost_master cm INNER JOIN tbl_service_tax serv ON cm.company_name=serv.company_name AND cm.branch=serv.branch
//SET GSTType='$gstType', cm.ServiceTaxNo=serv.ServiceTaxNo
//WHERE cost_center='$cost_no'
//"); 
//            }
//       }
//    }

    $this->layout='home';
    $serviceTax = $this->params['data']['InitialInvoice']['servicetax'];
    $data = $this->params['data']['InitialInvoice'];

   if($serviceTax ==1)
   {
          //$this->redirect(array('controller'=>'Taxes','action' => 'add', '?'=>$data));
           //$this->requestAction('Taxes/add', array('return',$data));
         //echo Router::url(array('controller' => 'Taxes','action' => 'add',$data));
   }

    $username=$this->Session->read("username");
    $b_name=$this->params['data']['InitialInvoice']['branch_name'];
    $cost_center=$this->params['data']['InitialInvoice']['cost_center'];

    $dataX=$this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$cost_center)));

    if(empty($dataX))
    {
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px>"
                . "<b style=color:#FF0000>".'Please First Create Cost Master'."</b></h4>"));
        return $this->redirect(array('controller'=>'CostCenterMasters','action' => 'index'));
    }

    $this->set('invoiceType',$invoiceType);
    $this->set('cost_master',$dataX);
    $this->set('tmp_particulars',$this->AddInvParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('tmp_deduct_particulars',$this->AddInvDeductParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('username', $this->Session->read("username"));
}
        
               
        
public function billApproval() 
{
    $this->layout='home';
    $username=$this->Session->read("username");
    $userid = $this->Session->read("userid");
    $roles=explode(',',$this->Session->read("page_access"));
    
    if ($this->request->is('post')) 
    {		
        $checkTotal = 0;	
        $result=$this->request->data['InitialInvoice'];
        $Revenue = $result['revenue'];
        $RevenueMonthArr = $result['revenue_arr'];
        
        //print_r($result);  exit;
        $Transaction = $this->User->getDataSource();
        $Transaction->begin();
        
        //////////////////////    Fetching Variables ///////////////////////////
        $date = date('Y-m-d H:i:s');
        $result['createdate'] = $date;
        $branch_name = $b_name=$result['branch_name'];
        $cost_center = $result['cost_center'];
        $desc = $result['invoiceDescription'];
        $invoiceDate = $result['invoiceDate'];
        $tax_call = $result['app_tax_cal'];
        $serviceTax=$result['apply_service_tax'];
        $apply_krishi_tax = $result['apply_krishi_tax'];
        $apply_gst = $result['apply_gst'];
        $grnd = $result['grnd'];				
        //$amount=$result['total'];
        $month = $result['month'];
        $finYear = $result['finance_year'];
        $result['finance_year']=$finYear;
        $arr_month = explode("-",$finYear);
        ///////////////////    Fetching Variables Ends Here ////////////////////
        
        
        //////////////////     Making Month Variables      /////////////////////
        $arr =explode('-',$result['finance_year']);
        if(in_array($month,array('Jan','Feb','Mar')))
        {
            if($arr[0]==date('Y') || $arr[1]==date('y'))
            {
                $result['month']=$result['month']."-".date('y');
            }
            else
            {
                $result['month']=$result['month']."-".$arr[1];
            }
        }
        else
        {
            $result['month']=$result['month']."-".($arr[1]-1);
        }
        $result['username']=$username;
        $NewMonth = $result['month'];
        foreach($RevenueMonthArr as $mnt=>$mntValue)
        {
            $amt = 0;
            if(in_array($mnt,array('Jan','Feb','Mar')))
            {
                if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                {
                    $Nmonth=$mnt."-".date('y');
                }
                else
                {
                    $Nmonth=$mnt."-".$arr_month[1];
                }
            }
            else
            {
                $Nmonth=$mnt."-".($arr_month[1]-1); 
            }
            
            //echo "finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center' and provision_balance!=0";  exit;
            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$finYear' and month='$Nmonth' and branch_name='$b_name' and cost_center='$cost_center'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];
                $ProvisionId = $prov_['Provision']['id'];
            }
            
            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$finYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$b_name' and Cost_Center_OutSource='$cost_center' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$finYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$b_name' and Provision_Cost_Center='$cost_center' and deduction_status='1'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            
            
            if($amt<$mntValue)
            {
                $this->Session->setFlash("Revenue is Smaller Then Invoice");
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
                break;
            }
            else
            {
                $ProvisionArray[$mnt] = $ProvisionId;
                $ProvisionBalArray[$mnt] = $amt-$mntValue;
            }
        }
        
        $amount = 0;
        
        ////////////////  Getting All Amount Of Created Bill Ends Here /////////
        
        ////////////////  Getting Amount Of Bill Is Going To Create ////////////
        $particular = $this->params['data']['Particular'];
        $k=array_keys($particular);$i=0;

       // print_r($particular); exit;
        
        foreach($particular as $post)
        {    
            $dataX['particulars']="'".addslashes($post['particulars'])."'";
            $dataX['qty']="'".$post['qty']."'";
            $dataX['rate']="'".$post['rate']."'";
            $dataX['amount']="'".$post['amount']."'";
            $amount += $post['amount'];
            $this->AddInvParticular->updateAll($dataX,array('id'=>$k[$i++]));
        } 
        unset($dataX);
        
        $flag=false;
        if(isset($this->params['data']['DeductParticular']))
        {
            $deductparticular = $this->params['data']['DeductParticular'];
            $k=array_keys($deductparticular);$i=0;
            foreach($deductparticular as $post)
            {
                $dataX['particulars'] = "'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $amount -= $post['amount'];
                $this->AddInvDeductParticular->updateAll($dataX,array('id'=>$k[$i++]));
            }
            $flag=true;
        }
        
    ////////////////  Getting Amount Of Bill Is Going To Create Ends Here///////
                    
    
        ///////////// CalCulating Tax //////////////////////////////////////////
        $total =$amount;
        $sbctax = 0;
        $tax = 0;
        $krishiTax = 0;
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if($tax_call=='1')
        {
            if(strtotime($invoiceDate) > strtotime("2017-06-30"))
            {
                if($result['GSTType']=='Integrated')
                {
                    $igst = round($amount*0.18,0);
                }
                else
                {
                    $cgst = round($amount*0.09,0);
                    $sgst = round($amount*0.09,0);
                }
            }
            else 
            {
                $tax = round($amount*0.14,0);
                $sbctax = 0;
                if(strtotime($invoiceDate) > strtotime("2015-11-14"))
                    {$sbctax = round($amount*0.005,0);}
                if($apply_krishi_tax=='1')
                    {$krishiTax = round($amount*0.005,0);}
            }
        }

        if($serviceTax=='1')
        {$total = 0;$TotTotalY += 0;}
        else
        {
            $TotTotalY += $amount;
        }
        $grnd = round($total + $tax + $sbctax+$krishiTax+$igst+$cgst+$sgst,0);
        $result['total'] = $total;
        $result['tax'] = $tax;
        $result['sbctax'] = $sbctax;
        $result['krishi_tax'] = $krishiTax;
        $result['igst'] = $igst;
        $result['sgst'] = $sgst;
        $result['cgst'] = $cgst;
        
        
        $result['grnd'] = $grnd;
        
        
        

        ////////////////////  Checking Bill Amount is Less Than Provision Amount ///
        
        if(intval($Revenue)<intval($TotTotalY))
        {
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".'The Bill Amount is Not More Than Provision Amount'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }
        
    ////////////////////  Checking Provision Amount is Less Than Bill Amount Ends Here ///
    
     
        
    ///////////////////   Creating Proforma No From Here ///////////////////////
    $bill = $this->BillMaster->query("SELECT MAX(proforma_bill_no) proforma_bill_no from bill_no_master where id=1");
    $data=$this->Addbranch->find('first',array('conditions'=>array('branch_name'=>$b_name)));
    $b_name=$data['Addbranch']['branch_code'];
    $state_code = $data['Addbranch']['state_code'];

    $Transaction->query("Lock TABLES tbl_invoice READ");  //bill no master table not be read by other tables
    $idx = $bill['0']['0']['proforma_bill_no']+1;
    $proforma_no = 'PI/'.$state_code.'/'.$idx; 
    $Transaction->query("UNLOCK TABLES"); //unlock to update table
    
    $result['proforma_bill_no']=$proforma_no; 
    
    ///////////////////   Creating Proforma No Ends Here ///////////////////////
    
    if ($this->InitialInvoice->save($result))
    {
        
        $id=$this->InitialInvoice->getLastInsertID(); //Getting Last Insert Id From Table
        
        foreach($RevenueMonthArr as $mnt=>$mntValue)
        {
            if(in_array($mnt,array('Jan','Feb','Mar')))
            {
                if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                {
                    $Nmonth=$mnt."-".date('y');
                }
                else
                {
                    $Nmonth=$mnt."-".$arr_month[1];
                }
                
            }
            else
            {
                $Nmonth=$mnt."-".($arr_month[1]-1); 
            }
            
            if(!$this->ProvisionPartDed->saveAll(array('ProvisionPartDed'=>array('ProvisionId'=>$ProvisionArray[$mnt],'Provision_Finance_Year'=>$finYear,'Provision_Finance_Month'=>$Nmonth,'Provision_Branch_Name'=>$branch_name,'Provision_Cost_Center'=>$cost_center,'Provision_UsedBy_Month'=>$NewMonth,'ProvisionBalanceUsed'=>$mntValue,'InvoiceId'=>$id,'deduction_status'=>1,'created_at'=>$date,'created_by'=>$userid))))
            {
                $Transaction->rollback();
            }
            
            /////  Updating Provision Balance Starts From Here /////////////////
            if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$cost_center,'month'=>$Nmonth,'finance_year'=>$finYear)))
            { 
                $Transaction->rollback();
                $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            //// Updating Provision Balance Ends Here  /////////////////////////////
        }
        
        ////// Updating Proforma No in BillMaster //////////////////////////
        if(!$this->BillMaster->updateAll(array('proforma_bill_no'=>$idx),array('Id'=>"1"))) 
        { 
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".'Proforma No. Not Updated. Please Try Again'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }
        ////// Updating Proforma No in BillMaster Ends Here/////////////////


        
       
        
        //// Moving Particular Table From Temp To Here//////////////////////////
        $res=$this->AddInvParticular->find('all',array('conditions'=>array('username'=>$username)));
        foreach ($res as $post)
        {
            $post['AddInvParticular']['initial_id']=$id;
            $post['AddInvParticular']=Hash::remove($post['AddInvParticular'],'id');
            $this->Particular->saveAll($post['AddInvParticular']);
        }
        
        $this->AddInvParticular->deleteAll(array('username'=>$username));

        $res=$this->AddInvDeductParticular->find('all',array('conditions'=>array('username'=>$username)));
                foreach ($res as $post):
                $post['AddInvDeductParticular']['initial_id']=$id;
                $post['AddInvDeductParticular']=Hash::remove($post['AddInvDeductParticular'],'id');
                $this->DeductParticular->saveAll($post['AddInvDeductParticular']);
                endforeach;					
        $this->AddInvDeductParticular->deleteAll(array('username'=>$username));
        //// Moving Particular Table From Temp To Here Ends ////////////////////
        
        $Transaction->commit();
        
        $msg = "Hi <br>".$b_name." has Initiatead Invoice for ".$desc." with Value of ".$grnd." on ".date("F j, Y, g:i a");
        $msg .= "<br><strong><b style=color:#FF0000>Kindly Approve </b></strong>";

        //$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>'','username'=>$username))));
        $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".' The Proforma Invoice '.$proforma_no.' for amount '.$amount.' to '.$b_name.' has been saved'."</b></h4>"));

//        App::uses('CakeEmail', 'Network/Email');
//        $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));
//
//        foreach($emailid as $email1)
//        {    
//            if(!empty($email1['User']['email']))
//            {$email2[] = trim($email1['User']['email']);}
//        }
//
//            
//
//            App::uses('sendEmail', 'custom/Email');
//            $sub = "'New Initial Invoice - '.$b_name";
//            $mail = new sendEmail();
//            if(!empty($email2))
//            {
//               // print_r($email2); exit;
//                try
//                {
//                    $mail-> to($email2,$msg,$sub);	
//                }
//                catch(Exception $e)
//                {
//
//                }
//            }
            if(in_array('5',$roles))
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            else
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));
            }

                           
    }
    else
    {
        $Transaction->rollback();
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'The Initial Invoice could not be saved. Please Try Again.'."</b></h4>"));
    } 
    }
}
		
                public function download_proforma()
                {	
                            $this->layout='home';
                            $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19','2019-20','2020-21')))));
                            
                            $role = $this->Session->read("role");
                            if($role=='admin')
                            {
                            $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
                            }
                            else
                            {
                                $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
                            }
                            
                            $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));
                            
                           if($this->request->is('Post'))
                            {
                                $branch_name=$this->Session->read("branch_name");
                                $roles=explode(',',$this->Session->read("page_access"));
                                $data = $this->request->data['InitialInvoice'];

                                $condition = array("status"=>"0","or"=>array('bill_no'=>''),"not"=>array('proforma_bill_no'=>''),'proforma_approve'=>'0');
                                if($data['company_name'] !='')
                                    $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                                if($data['finance_year'] !='')
                                    $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                                if($data['branch_name'] !='')
                                    $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                                if($data['bill_no'] !='')
                                    $condition["SUBSTRING_INDEX(InitialInvoice.proforma_bill_no,'/','1')"] =  $data['proforma_bill_no'];
                                if(!in_array('18',$roles))
                                        $condition['InitialInvoice.branch_name'] =  $branch_name;
                                 //print_r($condition); exit;
                                $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','proforma_bill_no','total','po_no','grn','invoiceDescription'),
                                    'joins'=>array(array('table'=>'cost_master',
                                    'type'=>'inner','alias'=>'CostCenterMaster',
                                    'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                                $this->set('tbl_invoice',$data);                           
                                //print_r($data); die;
                            }     
                }
                
                public function download_proforma_branch()
                {	
                            $this->layout='home';
                            $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19','2019-20','2020-21')))));
                            
                            $role = $this->Session->read("role");
                            if($role=='admin')
                            {
                            $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
                            }
                            else
                            {
                                $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
                            }
                            
                            
                           if($this->request->is('Post'))
                            {
                                $branch_name=$this->Session->read("branch_name");
                                $roles=explode(',',$this->Session->read("page_access"));
                                $data = $this->request->data['InitialInvoice'];

                                $condition = array("not"=>array('proforma_bill_no'=>''),'proforma_approve'=>'0');
                                if($data['company_name'] !='')
                                    $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                                if($data['finance_year'] !='')
                                    $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                                if($data['branch_name'] !='')
                                    $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                                if($data['bill_no'] !='')
                                    $condition["SUBSTRING_INDEX(InitialInvoice.proforma_bill_no,'/','1')"] =  $data['proforma_bill_no'];
                                if(!in_array('18',$roles))
                                        $condition['InitialInvoice.branch_name'] =  $branch_name;
                                $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','proforma_bill_no','total','po_no','grn','invoiceDescription'),
                                    'joins'=>array(array('table'=>'cost_master',
                                    'type'=>'inner','alias'=>'CostCenterMaster',
                                    'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                                $this->set('tbl_invoice',$data);                           
                                //print_r($data); die;
                            }     
                }
		public function edit_proforma()
		{
                    $username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			
			$roles=explode(',',$this->Session->read("page_access"));
			
			$id  = base64_decode($this->request->query['id']);
			$this->layout='home';
			if(in_array('5',$roles))
			{
				$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'')));
			}
			elseif(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','branch_name'=>$branch_name))))
			{return $this->redirect(array('controller'=>'Users','action' => 'login'));}
			
                         $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId= '$id'");
                    $ActualRevenue = array(); 
                    foreach($prov_deduction as $pd)
                    {
                        $ProvisionId = $pd['pmmd']['ProvisionId'];
                        $revenue += round($pd['pmmd']['ProvisionBalanceUsed'],2);
                        $monthMaster[$pd['pmmd']['Provision_Finance_Month']] = round($pd['pmmd']['ProvisionBalanceUsed'],2);
                        $ActualProvArr = $this->Provision->find('first',array('conditions'=>"Id='$ProvisionId'"));
                        $ActualRevenue[$pd['pmmd']['Provision_Finance_Month']]=  round($ActualProvArr['Provision']['provision_balance'],2) + round($pd['pmmd']['ProvisionBalanceUsed'],2);
                    }
                        
                        
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			
                        
                        
			$this->set('roles',$roles);
                         $this->set('revenue',$revenue);
                    $this->set('monthMaster',$monthMaster);
                    $this->set('ActualRevenue',$ActualRevenue);
                    $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('id'=>$id))));
			$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2018-19','2019-20','2020-21')))));
			
		}
public function update_proforma()
{
    $this->layout='home';
    $roles=explode(',',$this->Session->read("page_access"));

    if ($this->request->is('post'))
    {
        $checkTotal = 0; 
        //print_r($this->request->data); exit;
         $id=$this->request->data['InitialInvoice']['id']; 

        $findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));
        $Performa_No = $findData['InitialInvoice']['proforma_bill_no'];

        $findCostCenter = $findData['InitialInvoice']['cost_center'];
        $findFinanceYear = $findData['InitialInvoice']['finance_year'];
        $findMonth = $findData['InitialInvoice']['month'];
        $branch_name = $findData['InitialInvoice']['branch_name']; 

        //$Revenue = $result['revenue'];
        $RevenueMonthArr = $this->request->data['InitialInvoice']['revenue_arr'];
        //print_r($RevenueMonthArr); exit;
         //////////////////     Making Month Variables      /////////////////////
        $arr =explode('-',$findFinanceYear);
        
        foreach($RevenueMonthArr as $Nmonth=>$mntValue)
        {
            $amt = 0;

            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$findFinanceYear' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$findCostCenter'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];
                $ProvisionId = $prov_['Provision']['id'];
            }
            
            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$findFinanceYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$findCostCenter' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$findFinanceYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$findCostCenter' and deduction_status='1' and InvoiceId!='$id'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            
            
            if($amt<$mntValue)
            {
                //echo "Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"; exit;
                $this->Session->setFlash(__("Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_proforma','?'=>array('id'=>base64_encode($id))));
                break;
            }
            else
            {
                $ProvisionArray[$Nmonth] = $ProvisionId;
                $ProvisionBalArray[$Nmonth] = $amt-$mntValue;
            }
            $Revenue +=$mntValue;
        }
        
        
        ////////////////  Getting All Amount Of Created Bill Ends Here /////////
                                                
        $Transaction = $this->User->getDataSource();
        $Transaction->begin();

        //Particulars Update Starts From Here
        $particular = $this->params['data']['Particular'];
        $k=array_keys($particular);$i=0;
        
        foreach($particular as $post){
            $dataX['particulars']="'".addslashes($post['particulars'])."'";
            $dataX['qty']="'".$post['qty']."'";
            $dataX['rate']="'".$post['rate']."'";
            $dataX['amount']="'".$post['amount']."'";
            $checkTotal += $post['amount'];
            $this->Particular->updateAll($dataX,array('id'=>$k[$i++]));
        }
        unset($dataX);
        
        //print_r($particular); exit;
        $flag=false;
        if(!isset($this->params['data']['DeductParticular']))
        {

        }
        else
        {
            $deductparticular = $this->params['data']['DeductParticular'];
            $flag=true;
        }
        if($flag)
        {
            $k=array_keys($deductparticular);$i=0;

            foreach($deductparticular as $post):
                $dataX['particulars']="'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $checkTotal -= $post['amount'];
                if(!$this->DeductParticular->updateAll($dataX,array('id'=>$k[$i++])))
                {
                   $Transaction->rollback(); 
                   return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_proforma','?'=>array('id'=>base64_encode($id))));
                }
            endforeach;
        }
                        
                            ///////////////// Updating Particulars Ends Here ///////////////////////
                            
                            $findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
                        $findSumPrv = $findInvAmt['0']['0']['total'];
                        
                        $findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
                        $findSumDPrv = round($findDInvAmt['0']['0']['total']);
                        
                        $findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
                        $findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);
                            
                        if($Revenue==$findTotalBillMade)
                        {                            
                           foreach($RevenueMonthArr as $Nmonth=>$mntValue)
                            {
                               
                                if(!$this->ProvisionPartDed->updateAll(array('ProvisionBalanceUsed'=>$mntValue,'Provision_Finance_Year'=>"'".$findFinanceYear."'"),array('Provision_Finance_Month'=>$Nmonth,'Provision_UsedBy_Month'=>$findMonth,'InvoiceId'=>$id)))
                                {
                                    $Transaction->rollback();
                                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_proforma','?'=>array('id'=>base64_encode($id))));
                                }

                                /////  Updating Provision Balance Starts From Here /////////////////
                                if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$findCostCenter,'month'=>$Nmonth,'finance_year'=>$findFinanceYear)))
                                { 
                                    $Transaction->rollback();
                                    //echo "<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"; exit;
                                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_proforma','?'=>array('id'=>base64_encode($id))));
                                }
                                //// Updating Provision Balance Ends Here  /////////////////////////////
                            }

                            $GSTType = $this->params['data']['GSTType'];
                            
                            if(empty($GSTType))
                            {
                                $getGst = $this->CostCenterMaster->find('first',array('conditions'=>array('cost_center'=>$this->request->data['InitialInvoice']['cost_center'])));
                                $GSTType = $getGst['CostCenterMaster']['GSTType'];
                            }
                            $data=Hash::remove($this->params['data']['InitialInvoice'],'id');
                            $data=Hash::remove($data,'revenue_arr');
                            $data=Hash::remove($data,'revenue');
                            $b_name=$data['branch_name'];
                            
                            $amount=$data['total'];
                            //$data=Hash::remove($data,'branch_name');
                                
                            $date = $data['invoiceDate'];
                            $date = date_create($date);
                            $date = date_format($date,"Y-m-d");

                            $data['branch_name']="'".$data['branch_name']."'";
                            $data['cost_center']="'".$data['cost_center']."'";
                            $data['finance_year']="'".$data['finance_year']."'";
                            $data['jcc_no']="'".$data['jcc_no']."'";
                            $data['grn']="'".addslashes($data['grn'])."'";
                            
                            $data['po_no']="'".addslashes($data['po_no'])."'";
                            $data['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
                            $data['month']="'".addslashes($data['month'])."'";
                            $data['invoiceDate'] = "'".addslashes($date)."'";		
                            $data['GSTType'] = "'".addslashes($GSTType)."'";
                            
                            if($GSTType=='Integrated' && $findData['InitialInvoice']['apply_gst']=='1')
                            {
                                $data['igst']=round($amount*0.18,0);
                            }
                            else if($findData['InitialInvoice']['apply_gst']=='1')
                            {
                                $data['cgst']=round($amount*0.09,0);
                                $data['sgst']=round($amount*0.09,0);
                            }
                            //exit;
                            $month = explode('-',str_replace("'", "", $data['month']));
                            $month = $month[0];

                            $finn = explode('-',str_replace("'", "", $data['finance_year']));
                            $monthArr = array('Jan','Feb','Mar');

                            if(in_array($month, $monthArr))
                            {
                                if($finn[0]==date('Y') || $finn[1]==date('y'))
                                {
                                    $data['month'] = "'".$month.'-'.date('y')."'";
                                }
                                else
                                {
                                    $data['month'] = "'".$month.'-'.$finn[1]."'";
                                }
                            }
                            else
                            {$data['month'] = "'".$month.'-'.($finn[1]-1)."'";}

                            $dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','finance_year','month'),'conditions'=>array('id'=>$id)));

                            $dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));
                            $tax_call = $dataY['InitialInvoice']['app_tax_cal'];
                            $krishi_tax = $data['apply_krishi_tax'];
                            $service_tax = $data['apply_service_tax'];
                            //$data['apply_gst'] = "0";

                            if($dataY['InitialInvoice']['total'] != $data['total'])
                            {
                                $dataZ['initial_id'] = $id;
                                $dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
                                $dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
                                $dataZ['new_amount'] = $data['total'];
                                $dataZ['createdate'] = date('Y-m-d H:i:s');
                                $this->EditAmount->save($dataZ);
                            }

                        if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
                        {
                            

                            

                            $tax = 0;
                            $sbctax = 0;
                            $krishiTax = 0;
                            $igst = 0;
                            $sgst =0;
                            $cgst = 0;
                            $total =round($checkTotal,0); 
                            $apply_gst = "0";				
                            if($tax_call == '1')
                            {
                                
                                    $apply_gst = "1";

                                    $apply_krishi_tax = "0";
                                    if($GSTType=='Integrated')
                                    {
                                        $igst = round($checkTotal*0.18,0);
                                    }
                                    else 
                                    {
                                        $sgst = $cgst = round($checkTotal*0.09,0);
                                    }
                                
                                
                            }

                            if($service_tax=="1")
                            {
                               $total = "0"; 
                            }
                            $grnd = round($total + $tax + $sbctax+$krishiTax+$sgst+$cgst+$igst,0);

                            $total2 = 0;
//                            if($dataA['InitialInvoice']['cost_center'] == str_replace("'", "", $data['cost_center']) && $dataA['InitialInvoice']['month'] == str_replace("'", "", $data['month']) && $dataA['InitialInvoice']['finance_year'] == str_replace("'", "", $data['finance_year']) )
//                            {
                                $total2 = $dataA['InitialInvoice']['total'] -$total;
                           // }
                         //   else
                        //    {
                     //           $total2 = $total - 2 *$total; 
                      //          $total3 = $dataA['InitialInvoice']['total'];
                                //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total3"),array('cost_center'=>  $dataA['InitialInvoice']['cost_center'],'finance_year'=>$dataA['InitialInvoice']['finance_year'],'month'=>$dataA['InitialInvoice']['month']));
                     //       }

                            //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total2"),array('cost_center'=>  str_replace("'", "", $data['cost_center']),'finance_year'=>str_replace("'", "", $data['finance_year']),'month'=>str_replace("'", "", $data['month'])));

                            $dataY = array('total'=>$total,'tax'=>$tax,'sbctax'=>$sbctax,'grnd'=>$grnd,'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,'krishi_tax'=>$krishiTax,'apply_krishi_tax'=>$apply_krishi_tax,'apply_gst'=>$apply_gst);
                            //print_r($dataY); exit;
                            $this->InitialInvoice->updateAll($dataY,array('id'=>$id));
                            $Transaction->commit();
                            //exit;
                            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Proforma Invoice. '.$Performa_No.' to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.'."</b></h4>"));
                            $username=$this->Session->read("username");

                            App::uses('sendEmail', 'custom/Email');

                            $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('proforma_bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

                            $msg = "Hi<br>".$b_name." branch ProForma No.".$dataX['InitialInvoice']['proforma_bill_no'].' has been Edited. '.date("F j, Y, g:i a");

                            $msg .= "<br><strong><b style=color:#FF0000>Kindly update your Records </b></strong>";

                            $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));

//                            $nofifyArr = array(); $notifyLoop=0;
//
//                            foreach($emailid as $email1):
//                                    $email2[] = trim($email1['User']['email']);
//                                    $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
//                                    $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
//                                    $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
//                                    $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
//                            endforeach;
//
//                            $this->NotificationMaster->saveAll($nofifyArr);

                            $sub = "Invoice Edited";
                            $mail = new sendEmail();
                            if(!empty($email2))
                                                        {
                                                            try
                                                            {
                                                               // $mail-> to($email2,$msg,$sub);		
                                                            }
                                                            catch(Exception $e)
                                                            {

                                                            }
                                                        }				

                            if(in_array('5',$roles))
                            {return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));}
                            else
                            {return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));}

                            }
                        }

                        else
                        {
                            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Provision  for Amount '.$amount.' is Less For Proforma No.'.$Performa_No."</b></h4>"));
                            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_proforma','?'=>array('id'=>base64_encode($id))));
                        }

                    }
                }
            
                public function approve_proforma()
		{	
                    $username=$this->Session->read("username");
                    $id  = $this->request->query['id'];
                    $this->layout='home';
                    $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','status'=>0)));

                    $b_name=$data['InitialInvoice']['branch_name'];
                    $c_center=$data['InitialInvoice']['cost_center'];

                    $this->set('tbl_invoice', $data);
                    $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
                    $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
                    $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
                public function move_approve_proforma()
                {
                    $this->layout='home';

                    if ($this->request->is('post'))
                    {
                        $id=$this->params['data']['InitialInvoice']['id'];
                        $b_name=$this->params['data']['InitialInvoice']['branch_name'];
                        $f_year=$this->params['data']['InitialInvoice']['finance_year'];
                        $amount=$this->params['data']['InitialInvoice']['total'];
                        $month=$this->params['data']['InitialInvoice']['month'];
                        $po=$this->params['data']['InitialInvoice']['po_no'];
                        $Total = $amount;
                        $amountFlag = true;

                        $Transaction = $this->User->getDataSource();
                        $Transaction->begin();

                        

                        if($amountFlag)
                        {
                        $branch_name=$b_name;
                        $cost_center = $this->InitialInvoice->find('first',array('fields'=>array('cost_center','proforma_bill_no'),'conditions'=>array('id'=>$id)));
                        $proforma_bill_no = $cost_center['InitialInvoice']['proforma_bill_no'];

                        $company = $this->CostCenterMaster->find('first',array('fields'=>array('company_name'),
                            'conditions'=>array('cost_center'=>$cost_center['InitialInvoice']['cost_center'])));
                        $companyName = $company['CostCenterMaster']['company_name'];

                        $data = array('proforma_approve'=>1);

                        //if($po != ''){$data=array('bill_no'=>"'".$bill_no."'",'approve_po'=>"'Yes'");}

                        if ($this->InitialInvoice->updateAll($data,array('id'=>$id))) 
                        {   
                                
                                $Transaction->commit();

                                App::uses('sendEmail', 'custom/Email');

                                $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

                                $msg = "Hi<br> Proforma Has Approved ".$branch_name." branch Proforma No. ".$proforma_bill_no." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd']." on ".date("F j, Y, g:i a");
                                $msg .= "<br><strong><b style=color:#FF0000> Move To Tax Invoice </b></strong>";
                                $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $branch_name),'role' => 'admin'))));

                                $nofifyArr = array(); $notifyLoop=0;

                                foreach($emailid as $email1):
                                        $email2[] = trim($email1['User']['email']);
                                endforeach;

                                $this->NotificationMaster->saveAll($nofifyArr);

                                $sub = "Proforma Invoice Moved To Tax Invoice By ".$branch_name;
                                $mail = new sendEmail();

                                    if(!empty($email2))
                                    {
                                        try
                                        {
                                            $mail-> to($email2,$msg,$sub);	
                                        }
                                        catch(Exception $e)
                                        {

                                        }
                                    }
                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Proforma No. '.$proforma_bill_no.' to Branch '.$branch_name.' for amount '.$amount.' for '.$month.' Approved Successfully. And moved to Tax Invoice'."</b></h4>"));
                                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));
                            }
                            else
                            {
                                $Transaction->rollback();
                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>Proforma Invoice Not Moved. Please Try Again.</b></h4>"));
                                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));
                            }

                        }	
                        else
                        {
                            $Transaction->query("unlock tables");
                            $Transaction->rollback();
                            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>$msg</b></h4>"));
                            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));
                        }
                    }

                }

		public function reject_proforma()
		{
                    //print_r($this->request->query); exit;
                   $id  = base64_decode($this->request->query['id']); 
                    $data = array('status' => '1');
                    
                    $flag = true;
                    
                    $Transaction = $this->InitialInvoice->getDataSource();
                    $Transaction->begin();
                    
                    if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
                    {
                       $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId='$id' ");
                       
                        foreach($prov_deduction as $pd)
                        {
                            $ProvisionId= $pd['pmmd']['ProvisionId'];
                            $bal_used = round($pd['pmmd']['ProvisionBalanceUsed'],2);
                            
                            $ProvisionFetch = $this->Provision->find('first',array('conditions'=>"id='$ProvisionId'"));
                            $bal_used += round($ProvisionFetch['Provision']['provision_balance']);
                            
                            if ($this->Provision->updateAll(array('provision_balance'=>"'".$bal_used."'"),array('id'=>$ProvisionId)))
                            {
                                if (!$this->ProvisionPartDed->updateAll(array('deduction_status'=>"0"),array('ProvisionMonthId'=>$pd['pmmd']['ProvisionMonthId'])))
                                {
                                    $flag=false;
                                    $Transaction->rollback();
                                    break;
                                }
                                else
                                {
                                    $flag = true; 
                                    $Transaction->commit();
                                    echo "success<br/>";
                                }
                            }
                            else
                            {
                                $flag=false;
                                $Transaction->rollback();
                                break;
                            }
                        } 
                    }
                    else
                    {
                        $Transaction->rollback();
                        $flag=false;
                        
                    }
                   
                    if($flag)
                    {
                        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to Bill No. '.$id.' Rejected Successfully.'."</b></h4>"));
                        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));
                    }
                    else
                    {
                        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to Bill No. '.$id.' Reject Request Failed. Please Contact To Admin'."</b></h4>"));
                        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'download_proforma'));
                    }
		}
                
                
                

		public function view_proforma_pdf()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = base64_decode($this->request->query['id']);
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
                        
			$roles=explode(',',$this->Session->read("page_access"));
			$data='';
			if(in_array('18',$roles))
			{
                            $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			}
			else
			{
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
				{
                                    return $this->redirect(array('controller'=>'Users','action' => 'login')); 
				}
			}
			
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
                        $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function view_proforma_letter_pdf()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = base64_decode($this->request->query['id']);
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));

			if(in_array('18',$roles))
			{
				$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			}
			else
			{			
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
				{return $this->redirect(array('controller'=>'Users','action' => 'login')); }
			}
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
			 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
                
		public function view()
		{	
			$username=$this->Session->read("username");
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
			//$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
		}
		
		public function view_bill()
		{	
                    $username=$this->Session->read("username");
                    $id  = $this->request->query['id'];
                    $this->layout='home';
                    $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','proforma_approve'=>'1','status'=>0)));

                    $b_name=$data['InitialInvoice']['branch_name'];
                    $c_center=$data['InitialInvoice']['cost_center'];

                    $this->set('tbl_invoice', $data);
                    $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
                    $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
                    $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		
                
                public function genrate_bill()
                {
                    $this->layout='home';
                    
                    if ($this->request->is('post'))
                    {
                        $id=$this->params['data']['InitialInvoice']['id'];
                        $b_name=$this->params['data']['InitialInvoice']['branch_name'];
                        $f_year=$this->params['data']['InitialInvoice']['finance_year'];
                        $f_year1=$this->params['data']['InitialInvoice']['finance_year'];
                        $amount=$this->params['data']['InitialInvoice']['total'];
                        $month=$this->params['data']['InitialInvoice']['month'];
                        $po=$this->params['data']['InitialInvoice']['po_no'];
                        $Total = $amount;
                        $amountFlag = true;

                        $Transaction = $this->User->getDataSource();
                        $Transaction->begin();

                        if($po != '')
                        {
                            $data=array('bill_no'=>"'".$bill_no."'",'approve_po'=>"'Yes'");
                            $poArray = explode(',',$po);

                            $error = 3;
                            $po_error = '';
                            $msg = "";
                            $data_id[] = 0;
                            if(count($poArray)<=4)
                            {
                                $data_ids = implode(',', $data_id);
                                foreach($poArray as $po_no)
                                {
                                    if($POAmount = $this->PONumber->query("SELECT pn.balAmount,pnp.data_id FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
                                        WHERE pnp.poNumber = '$po_no' AND pnp.data_id not in ($data_ids) AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
                                    {
                                        $Total -= $POAmount['0']['pn']['balAmount'];
                                        $data_id[] = $POAmount['0']['pnp']['data_id'];
                                    }
                                    else 
                                    {
                                        $amountFlag = false;
                                        $po_error = $po_no;
                                        $error = 1;
                                        $msg = "PO Number -$po_no Not Matched";
                                        break;
                                    }


                                }
                                if($Total>=0 && $amountFlag)
                                {
                                     $error = 2;
                                     $amountFlag =false;
                                     $msg = "PO Amount is less than Grand Total";
                                }
                            }
                            else
                            {
                                        $amountFlag = false;
                                        $po_error = $po_no;
                                        $error = 1;
                                        $msg = "Please Do Not Enter PO Number More Than 4";
                            }

                            if($amountFlag)
                            {
                                $msg = "OK";
                            }
                        }

                        if($amountFlag)
                        {
                        $branch_name=$b_name;

                        $data=$this->Addbranch->find('first',array('conditions'=>array('branch_name'=>$b_name)));
                        $b_name=$data['Addbranch']['branch_code'];
                        $state_code = $data['Addbranch']['state_code'];

                        $cost_center = $this->InitialInvoice->find('first',array('fields'=>array('cost_center'),'conditions'=>array('id'=>$id)));


                        $company = $this->CostCenterMaster->find('first',array('fields'=>array('company_name'),
                            'conditions'=>array('cost_center'=>$cost_center['InitialInvoice']['cost_center'])));
                        $companyName = $company['CostCenterMaster']['company_name'];

                        $selT = "SELECT MAX(BillNoChange) BillNoChange FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center =cm.cost_center
                WHERE finance_year='$f_year1' AND ti.state_code = '$state_code' AND company_name ='$companyName' and ti.id!='$id'"; 
                        
                        $bill = $this->BillMaster->query($selT);

                        $Transaction->query("Lock TABLES tbl_invoice READ");  //bill no master table not be read by other tables

                        if(strlen(intval($bill['0']['0']['BillNoChange']))==1 || empty($bill['0']['0']['BillNoChange']))
                        {
                            $idx = "0".(intval($bill['0']['0']['BillNoChange'])+1);
                        }
                        else
                        {
                            $idx = (intval($bill['0']['0']['BillNoChange'])+1);
                            if(strlen($idx)==1)
                            {
                                $idx = '0'.$idx;
                            }
                        }

                        $bill_no = $state_code.'-'.$idx.'/'.substr($f_year1,2,6);  


                        $Transaction->query("UNLOCK TABLES"); //unlock to update table

                        //$this->tbl_invoice->updateAll(array('bill_no'=>$idx),array('company_name'=>$companyName,'finance_year'=>$f_year));
                        $data=array('bill_no'=>"'".$bill_no."'",'BillNoChange'=>"$idx",'state_code'=>"'".$state_code."'");

                        //if($po != ''){$data=array('bill_no'=>"'".$bill_no."'",'approve_po'=>"'Yes'");}

                        if ($this->InitialInvoice->updateAll($data,array('id'=>$id))) 
                        {   
                                $data_id = array(0); $Total = $amount;
                                foreach($poArray as $po_no)
                                {

                                    $data_ids = implode(',', $data_id);
                                    $this->PONumber->query("UPDATE po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id SET pn.balAmount = IF(pn.balAmount>$Total,pn.balAmount-$Total,0)
                                    WHERE pnp.poNumber = '$po_no' AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom");

                                    if($POAmount = $this->PONumber->query("SELECT pn.balAmount,pnp.data_id FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
                                        WHERE pnp.poNumber = '$po_no' AND pnp.data_id not in ($data_ids) AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
                                    {
                                        $Total -= $POAmount['0']['pn']['balAmount'];
                                        $data_id[] = $POAmount['0']['pnp']['data_id'];
                                    }
                                    $data_ids = implode(',', $data_id);
                                }
                                $Transaction->commit();

                                App::uses('sendEmail', 'custom/Email');

                                $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

                                $msg = "Hi<br> ADMIN Has Approved ".$branch_name." branch Initial Invoice ".$bill_no." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd']." on ".date("F j, Y, g:i a");
                                $msg .= "<br><strong><b style=color:#FF0000> Approved </b></strong>";
                                $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $branch_name),'role' => 'admin'))));

                                $nofifyArr = array(); $notifyLoop=0;

                                foreach($emailid as $email1):
                                        $email2[] = trim($email1['User']['email']);
                                        $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                                        $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                                        $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                                        $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
                                endforeach;

                                $this->NotificationMaster->saveAll($nofifyArr);

                                $sub = "Initial Invoice Approve Bill to ".$branch_name;
                                $mail = new sendEmail();

                                    if(!empty($email2))
                                                        {
                                                            try
                                                            {
                                                                $mail-> to($email2,$msg,$sub);	
                                                            }
                                                            catch(Exception $e)
                                                            {

                                                            }
                                                        }
                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Bill No. '.$bill_no.' to Branch '.$branch_name.' for amount '.$amount.' for '.$month.' Generated Successfully. And moved to Branch'."</b></h4>"));
                                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
                            }
                            else
                            {
                                $Transaction->rollback();
                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>Data Not Updated</b></h4>"));
                                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
                            }

                        }	
                        else
                        {
                            $Transaction->query("unlock tables");
                            $Transaction->rollback();
                            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>$msg</b></h4>"));
                            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
                        }
                    }

                }
	//view at branch side for viewing newly created invoices and edit the bill if mistaken
		public function branch_view()
		{	
			//$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$this->layout='home';
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>'','branch_name'=>$branch_name))));
		}
		public function branch_viewbill()
		{	
                    $username=$this->Session->read("username");
                    $branch_name=$this->Session->read("branch_name");
                    $id  = $this->request->query['id'];
                    $this->layout='home';

                    if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','branch_name'=>$branch_name))))
                    {return $this->redirect(array('controller'=>'Users','action' => 'login'));}

                    $b_name=$data['InitialInvoice']['branch_name'];
                    $c_center=$data['InitialInvoice']['cost_center'];

                    $this->set('tbl_invoice', $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))));
                    $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
                    $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
                    $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function edit_bill()
		{
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			
			$roles=explode(',',$this->Session->read("page_access"));
			
			$id  = $this->request->query['id'];
			$this->layout='home';
			if(in_array('5',$roles))
			{
                            $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'')));
			}
			elseif(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','branch_name'=>$branch_name))))
			{return $this->redirect(array('controller'=>'Users','action' => 'login'));}
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			
                        $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId= '$id'");
                    $ActualRevenue = array(); 
                    foreach($prov_deduction as $pd)
                    {
                        $ProvisionId = $pd['pmmd']['ProvisionId'];
                        $revenue += round($pd['pmmd']['ProvisionBalanceUsed'],2);
                        $monthMaster[$pd['pmmd']['Provision_Finance_Month']] = round($pd['pmmd']['ProvisionBalanceUsed'],2);
                        $ActualProvArr = $this->Provision->find('first',array('conditions'=>"Id='$ProvisionId'"));
                        $ActualRevenue[$pd['pmmd']['Provision_Finance_Month']]=  round($ActualProvArr['Provision']['provision_balance'],2) + round($pd['pmmd']['ProvisionBalanceUsed'],2);
                    }
                        
                        
			$this->set('roles',$roles);
                        $this->set('revenue',$revenue);
                        $this->set('monthMaster',$monthMaster);
                        $this->set('ActualRevenue',$ActualRevenue);
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('id'=>$id))));
			$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
			
		}
public function update_bill()
{
$this->layout='home';
$roles=explode(',',$this->Session->read("page_access"));

if ($this->request->is('post'))
{
     $id = $this->request->data['InitialInvoice']['id']; 
    if(!empty($this->request->data['Reject']))
    {
        $Transaction = $this->InitialInvoice->getDataSource(); $flag = true;
                    $Transaction->begin();
                    
                    if ($this->InitialInvoice->updateAll(array('status'=>"'1'",'InvoiceRejectBy'=>$this->Session->read('userid'),'InvoiceRejectDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$id)))
                    {
                       $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId='$id' ");
                       
                        foreach($prov_deduction as $pd)
                        {
                             $ProvisionId= $pd['pmmd']['ProvisionId']; 
                            $bal_used = round($pd['pmmd']['ProvisionBalanceUsed'],2);
                            
                            $ProvisionFetch = $this->Provision->find('first',array('conditions'=>"id='$ProvisionId'"));
                            $bal_used += round($ProvisionFetch['Provision']['provision_balance']);
                            
                            if ($this->Provision->updateAll(array('provision_balance'=>$bal_used),array('id'=>$ProvisionId)))
                            {
                                if (!$this->ProvisionPartDed->updateAll(array('deduction_status'=>"0",'ProvisionBalanceUsed'=>'0'),array('ProvisionMonthId'=>$pd['pmmd']['ProvisionMonthId'])))
                                {
                                    $flag=false;
                                    $Transaction->rollback();
                                }
                                else
                                {
                                    $flag = true;
                                    echo "success<br/>"; 
                                    $Transaction->commit();
                                }
                            }
                            else
                            {
                                $flag=false;
                                $Transaction->rollback();
                            }
                        } 
                    }
                    else
                    {
                        $Transaction->rollback();
                        $flag=false;
                    }
                    
                    if($flag)
                    {
                        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice Rejected Successfully.'."</b></h4>"));
                        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
                    }
                    else
                    {
                        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice  Reject Request Failed. Please Contact To Admin'."</b></h4>"));
                        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                    }
    }
    else
    {
    $checkTotal = 0;
    $id=$this->request->data['InitialInvoice']['id'];

    $findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));

    $findCostCenter     = $findData['InitialInvoice']['cost_center'];
    $findFinanceYear    = $findData['InitialInvoice']['finance_year'];
    $findMonth          = $findData['InitialInvoice']['month'];
    $branch_name = $findData['InitialInvoice']['branch_name']; 
    //$Revenue = $this->request->data['InitialInvoice']['revenue'];
    $RevenueMonthArr = $this->request->data['InitialInvoice']['revenue_arr'];
    $arr =explode('-',$findFinanceYear);
        
        foreach($RevenueMonthArr as $Nmonth=>$mntValue)
        {
            $amt = 0;

            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$findFinanceYear' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$findCostCenter'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];
                $ProvisionId = $prov_['Provision']['id'];
            }
            
            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$findFinanceYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$findCostCenter' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$findFinanceYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$findCostCenter' and deduction_status='1' and InvoiceId!='$id'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            
            
            if($amt<$mntValue)
            {
                //echo "Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"; exit;
                $this->Session->setFlash(__("Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                break;
            }
            else
            {
                $ProvisionArray[$Nmonth] = $ProvisionId;
                $ProvisionBalArray[$Nmonth] = $amt-$mntValue;
            }
            $Revenue +=$mntValue;
        }
        
        
        ////////////////  Getting All Amount Of Created Bill Ends Here /////////
                                                
        $Transaction = $this->User->getDataSource();
        $Transaction->begin();

        $particular = $this->params['data']['Particular'];
        $k=array_keys($particular);$i=0;
        
        foreach($particular as $post){
            $dataX['particulars']="'".addslashes($post['particulars'])."'";
            $dataX['qty']="'".$post['qty']."'";
            $dataX['rate']="'".$post['rate']."'";
            $dataX['amount']="'".$post['amount']."'";
            $checkTotal += $post['amount'];
            if(!$this->Particular->updateAll($dataX,array('id'=>$k[$i++])))
            {
                $Transaction->rollback(); 
                $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
            }
            
        }
        unset($dataX);
        
        //print_r($particular); exit;
        $flag=false;
        if(!isset($this->params['data']['DeductParticular']))
        {

        }
        else
        {
            $deductparticular = $this->params['data']['DeductParticular'];
            $flag=true;
        }
        if($flag)
        {
            $k=array_keys($deductparticular);$i=0;

            foreach($deductparticular as $post):
                $dataX['particulars']="'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $checkTotal -= $post['amount'];
                if(!$this->DeductParticular->updateAll($dataX,array('id'=>$k[$i++])))
                {
                   $Transaction->rollback(); 
                   $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
                   return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                }
            endforeach;
        }
        
        
        $findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
        $findSumPrv = $findInvAmt['0']['0']['total'];

        $findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
        $findSumDPrv = round($findDInvAmt['0']['0']['total']);

        $findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
        $findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);
        
        
        if($Revenue==$findTotalBillMade)
        {
        
           foreach($RevenueMonthArr as $Nmonth=>$mntValue)
            {

                if(!$this->ProvisionPartDed->updateAll(array('ProvisionBalanceUsed'=>$mntValue),array('Provision_Finance_Month'=>$Nmonth,'Provision_UsedBy_Month'=>$findMonth,'InvoiceId'=>$id)))
                {
                    $Transaction->rollback();
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                }

                /////  Updating Provision Balance Starts From Here /////////////////
                if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$findCostCenter,'month'=>$Nmonth,'finance_year'=>$findFinanceYear)))
                { 
                    $Transaction->rollback();
                    //echo "<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"; exit;
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                }
                //// Updating Provision Balance Ends Here  /////////////////////////////
            } 
            
        $invoiceDate = $this->params['data']['InitialInvoice']['invoiceDate'];
        $data=Hash::remove($this->params['data']['InitialInvoice'],'id');
        $data=Hash::remove($data,'revenue_arr');
        $data=Hash::remove($data,'revenue_str');
        $data=Hash::remove($data,'revenue');
        
        $b_name=$data['branch_name'];
        $amount=$data['total'];
        $data=Hash::remove($data,'branch_name');


        $date = $data['invoiceDate'];
        $date = date_create($date);
        $date = date_format($date,"Y-m-d");

        $data['jcc_no']="'".addslashes($data['jcc_no'])."'";
        $data['grn']="'".addslashes($data['grn'])."'";
        $data['bill_no']="'".$data['bill_no']."'";
        $data['po_no']="'".addslashes($data['po_no'])."'";
        $krishi_tax = $data['apply_krishi_tax'];
        $data['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
        $data['month']="'".addslashes($data['month'])."'";
        $data['cost_center']="'".addslashes($data['cost_center'])."'";
        $data['finance_year']="'".addslashes($data['finance_year'])."'";
        $data['invoiceDate'] = "'".addslashes($date)."'";
        $data['GSTType'] = "'".addslashes($data['GSTType'])."'";
        $apply_gst = $data['apply_gst'];
        $data['apply_gst'] = "'".$data['apply_gst']."'";


        $dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','month','finance_year'),'conditions'=>array('id'=>$id)));
        $dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));
        $tax_call = $dataY['InitialInvoice']['app_tax_cal'];

        if($dataY['InitialInvoice']['total'] != $data['total'])
        {
            $dataZ['initial_id'] = $id;
            $dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
            $dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
            $dataZ['new_amount'] = $data['total'];
            $dataZ['createdate'] = date('Y-m-d H:i:s');
            $this->EditAmount->save($dataZ);
        }

        if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
        {
            
            $total = $checkTotal;
            $total = round($checkTotal,0);
            $tax = 0;
            $sbctax = 0;
            $krishiTax = 0;
            $igst = 0;
            $cgst = 0;
            $sgst = 0;

            if($tax_call == '1')
            {
                if($apply_gst && strtotime($invoiceDate) > strtotime("2017-06-30"))
                {
                    $tax=0;$krishiTax=0;$sbctax=0;
                    if($this->params['data']['InitialInvoice']['GSTType']=='Integrated')
                    {
                        $igst = round($checkTotal*0.18,0);
                    }
                    else
                    {
                        $cgst = round($checkTotal*0.09,0);
                        $sgst = round($checkTotal*0.09,0);
                    }
                }
                else 
                {
                    $total = round($checkTotal,0);
                    $tax = round($checkTotal*0.14,0);
                    $sbctax = 0;
                    if(strtotime($date) > strtotime("2015-11-14"))
                    {
                        $sbctax = round($checkTotal*0.005,0);
                    }
                    if($krishi_tax == "1")
                    {
                        $krishiTax = round($checkTotal*0.005,0);
                    }
                }
            }
            $grnd = round($total + $tax + $krishiTax + $sbctax+$igst+$sgst+$cgst,0);
            $dataY = array('total'=>$total,'tax'=>$tax,'krishi_tax'=>$krishiTax,'sbctax'=>$sbctax,'grnd'=>$grnd);

            $total2 = 0;
           // if($dataA['InitialInvoice']['cost_center'] == str_replace("'", "", $data['cost_center']) && $dataA['InitialInvoice']['month'] == str_replace("'", "", $data['month']) && $dataA['InitialInvoice']['finance_year'] == str_replace("'", "", $data['finance_year']) )
          //  {
                $total2 = $dataA['InitialInvoice']['total'] -$total;
        //    }
         //   else
        //    {
      //          $total2 = $total - 2 *$total; 
      //          $total3 = $dataA['InitialInvoice']['total'];
                //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total3"),array('cost_center'=>  $dataA['InitialInvoice']['cost_center'],'finance_year'=>$dataA['InitialInvoice']['finance_year'],'month'=>$dataA['InitialInvoice']['month']));
       //     }

        //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total2"),array('cost_center'=>  str_replace("'", "", $data['cost_center']),'finance_year'=>str_replace("'", "", $data['finance_year']),'month'=>str_replace("'", "", $data['month'])));

            if($this->InitialInvoice->updateAll($dataY,array('id'=>$id)))
            {
                $Transaction->commit();
            }

            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.'."</b></h4>"));

            App::uses('sendEmail', 'custom/Email');

            $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

            $msg = "Hi<br>".$b_name." branch Invoice No.".$dataX['InitialInvoice']['bill_no'].' has been Edited. '.date("F j, Y, g:i a");

            $msg .= "<br><strong><b style=color:#FF0000>Kindly update your Records </b></strong>";

            $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));

//            $nofifyArr = array(); $notifyLoop=0;
//
//            foreach($emailid as $email1):
//                $email2[] = trim($email1['User']['email']);
//                $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
//                $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
//                $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
//                $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
//            endforeach;

        //$this->NotificationMaster->saveAll($nofifyArr);

            $sub = "Initial Invoice - ".$b_name." Edited";
            $mail = new sendEmail();


                     if(!empty($email2))
                                    {
                                        try
                                        {
                                           // $mail-> to($email2,$msg,$sub);		
                                        }
                                        catch(Exception $e)
                                        {

                                        }
                                    }		
            $username=$this->Session->read("username");
            if(in_array('5',$roles))
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            else
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));}
            }
            else
            {
                $Transaction->rollback(); 
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
            }
    }    
    else
    {
        $Transaction->rollback(); 
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Provision  for Amount '.$amount.' is Less'."</b></h4>"));
        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
    }

}
}
			
}
			//edit po on branch side		
		public function view_admin()
		{	
			$this->layout='home';
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));
			
			if(in_array('19',$roles))
			{
				$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>''),'po_no'=>'','status'=>0))));
			}
			else
			{
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>''),'po_no'=>'','branch_name'=>$branch_name,'status'=>0))));
			}
		}	
		public function view_forpo()
		{	
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$id  = $this->request->query['id'];
			$this->layout='home';
			
			$roles=explode(',',$this->Session->read("page_access"));
			$data = array();
			
			if(in_array('19',$roles))
			{
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))))
				{return $this->redirect(array('controller'=>'Users','action' => 'login'));}
			}
			else
			{
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
				{return $this->redirect(array('controller'=>'Users','action' => 'login'));}			
			}
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];

			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('id'=>$id))));
			$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function update_po()
		{
			$this->layout='home';
			if ($this->request->is('post'))
			{
				$id=$this->params['data']['InitialInvoice']['id'];
				$po_no=$this->params['data']['InitialInvoice']['po_no'];
				$b_name  = $this->params['data']['InitialInvoice']['branch_name'];
				$bill_no = $this->params['data']['InitialInvoice']['bill_no'];
				$amount  = $this->params['data']['InitialInvoice']['total'];
				
				$submit='';
				try{
						$submit=$this->params['data']['submit'];
					}
				catch(Exception $e){}
				
				if($submit=='submit')
				{
					//$this->set('res',$this->params->data);
					$data=array('po_no'=>"'".$po_no."'",'approve_po' => "''");
					if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
					{
					$msg="<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'PO No. '.$po_no;
					$msg.=" to InitialInvoice No. ".$bill_no;
					$msg.=" to Amount. ".$amount;
					$msg.=' for branch '.$b_name.' Added Successfully.</b></h4>';
					
					$this->Session->setFlash(__($msg));

					App::uses('sendEmail', 'custom/Email');
					
					$dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));
					
					$msg = "Hi<br>".$b_name." branch has Added PO for Invoice No.".$dataX['InitialInvoice']['bill_no']." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd'].' on '.date("F j, Y, g:i a");
					
					$msg .= "<br><strong><b style=color:#FF0000>Kindly Approve </b></strong>";
					
					$emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));
					
                                        $nofifyArr = array(); $notifyLoop=0;
					
                                        foreach($emailid as $email1):
						$email2[] = trim($email1['User']['email']);
                                                $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                                                $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                                                $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                                                $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
					endforeach;
                                        
                                        $this->NotificationMaster->saveAll($nofifyArr);
                                        
					$sub = "PO Added - ".$b_name;
					$mail = new sendEmail();
					
					if(!empty($email2))
                                        {
                                            try
                                            {
                                                $mail-> to($email2,$msg,$sub);		
                                            }
                                            catch(Exception $e)
                                            {
                                                
                                            }
                                        }
					return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_admin'));
					}
				}
				else
				{
					$this->InitialInvoice->updateAll(array('status'=>1),array('id'=>$id));
					
					$msg="<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'PO No. '.$po_no;
					$msg.=" InitialInvoice No. ".$bill_no;
					$msg.=" Amount. ".$amount;
					$msg.=' for branch '.$b_name.' Discard Successfully.</b></h4>';

					$this->Session->setFlash(__($msg));
					return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_admin'));
				}
			}
			
		}		
	
		public function check_po()
		{
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
		}

		public function approve_po()
                {						
                    $this->layout='home';

                    if ($this->request->is('post'))
                    {
                        $id = $this->params['data']['InitialInvoice']['id'];
                        $po_no = $this->params['data']['InitialInvoice']['po_no'];
                        $b_name = $this->params['data']['InitialInvoice']['branch_name'];
                        $bill_no = $this->params['data']['InitialInvoice']['bill_no'];
                        $grnd2 = $grnd = $amount = $this->params['data']['InitialInvoice']['total'];
                        $month = $this->params['data']['InitialInvoice']['month'];
                        $submit = '';
                        try{
                                $submit = $this->params['data']['submit'];
                           }
                        catch(Exception $e){}

                        $cost_month = $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id),'fields'=>array('cost_center','month','grnd')));
                        $cost_center = $cost_month['InitialInvoice']['cost_center'];
                        $month = $cost_month['InitialInvoice']['month'];
                        $cost_month['InitialInvoice']['Total'];
                        $poArr = explode(',',$po_no);


                        foreach($poArr as $po)
                        {
            //                $po_balance = $this->POParticulars->query("SELECT poAmountBalance FROM po_number_particulars WHERE cost_center = '$cost_center'
            //AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN periodTo AND periodFrom");

                            $po_balance = $this->PONumber->query("SELECT sum(pn.balAmount) `poAmount` FROM po_number_particulars pnp
            INNER JOIN po_number pn ON pnp.data_id = pn.id
            WHERE pn.balAmount !=0 AND pnp.poNumber = '$po' AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') 
            BETWEEN pnp.periodTo AND pnp.periodFrom");

                           //print_r($po_balance); 

                            $poBalance += $po_balance['0']['0']['poAmount'];
                            if($grnd2>$po_balance['0']['0']['poAmount'])
                            {
                                $po_balance_upd[$po] =0;
                                $grnd2 -= $po_balance['0']['0']['poAmount'];
                            }
                            else 
                            {
                                $po_balance_upd[$po] =$po_balance['0']['0']['poAmount']-$grnd2;
                                break;
                            }

                        }

                        if($poBalance<$grnd)
                        {
                           $this->Session->setFlash(__("PO Number Not Updated.Due To PO Balance is low or PO is Lapsed"));
                            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'check_po'));
                        }
                        else
                        {
                            foreach($po_balance_upd as $k=>$v)
                            {
                                $this->PONumber->query("update po_number set balAmount=$v where poNumber='$k'");
                            }
                        }


                        if($submit=='approve')
                        {
                            //$this->set('res',$this->params->data);
                            $data=array('po_no'=>"'".$po_no."'",'approve_po'=>"'Yes'",'po_createdate'=>'now()');
                            if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
                            {						
                                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'PO No. '.$po_no.' to Invoice '.$bill_no.' to amount '.$amount.' to '.$b_name.' for month '.$month.' Approved Successfully'."</b></h4>"));

                                App::uses('sendEmail', 'custom/Email');

                                $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

                                $msg = "Hi<br> Admin has Approved PO for Invoice No.".$dataX['InitialInvoice']['bill_no']." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd'].' on '.date("F j, Y, g:i a");

                                $msg .= "<br><strong><b style=color:#FF0000> Approved </b></strong>";

                                $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));


                                $nofifyArr = array(); $notifyLoop=0;

                                foreach($emailid as $email1):
                                        $email2[] = trim($email1['User']['email']);
                                        $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                                        $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                                        $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                                        $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
                                endforeach;

                                $this->NotificationMaster->saveAll($nofifyArr);

                                $sub = "PO Approved - ".$b_name;
                                $mail = new sendEmail();
                                if(!empty($email2))
                                                    {
                                                        try
                                                        {
                                                            $mail-> to($email2,$msg,$sub);		
                                                        }
                                                        catch(Exception $e)
                                                        {

                                                        }
                                                    }

                                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'check_po'));
                            }
                        }
                        else
                        {
                            $this->InitialInvoice->updateAll(array('status'=>'1'),array('id'=>$id));
                            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'PO No. '.$po_no.' to amount '.$amount.' to '.$b_name.' for month '.$month.' Discarded Successfully.'."</b></h4>"));
                            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'check_po'));
                        }
                    }

                    else
                    {
                        $id  = $this->request->query['id'];
                        $this->layout='home';
                        $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'status'=>0)));

                        $b_name=$data['InitialInvoice']['branch_name'];
                        $c_center=$data['InitialInvoice']['cost_center'];

                        $this->set('tbl_invoice', $data);
                        $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
                        $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
                        $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
                    }
                }
		public function download()
		{	
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name')))); 
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
                         $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));
                       if($this->request->is('Post'))
                        {
                            $branch_name=$this->Session->read("branch_name");
                            $roles=explode(',',$this->Session->read("page_access"));
                            $data = $this->request->data['InitialInvoice'];
                            
                            $condition = array();
                            if($data['company_name'] !='')
                                $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                            if($data['finance_year'] !='')
                                $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                            if($data['branch_name'] !='')
                                $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                            if($data['bill_no'] !='')
                                $condition["SUBSTRING_INDEX(InitialInvoice.bill_no,'/','1')"] =  $data['bill_no'];
                            if(!in_array('18',$roles))
                                    $condition['InitialInvoice.branch_name'] =  $branch_name;
                            $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','bill_no','total','po_no','grn','invoiceDescription'),
                                'joins'=>array(array('table'=>'cost_master',
                                'type'=>'inner','alias'=>'CostCenterMaster',
                                'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                            $this->set('tbl_invoice',$data);                           
                            //print_r($data); die;
                        }     
		}

		public function view_pdf()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = $this->request->query['id'];
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
                        
			$roles=explode(',',$this->Session->read("page_access"));
			$data='';
			if(in_array('18',$roles))
			{
                            $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			}
			else
			{
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
				{
                                    return $this->redirect(array('controller'=>'Users','action' => 'login')); 
				}
			}
			
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
                        $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function view_pdf1()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = $this->request->query['id'];
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));

			if(in_array('18',$roles))
			{
				$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			}
			else
			{			
				if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
				{return $this->redirect(array('controller'=>'Users','action' => 'login')); }
			}
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
			 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id,'testactive'=>'1'))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}

		public function view_grn()
		{	
			$this->layout='home';
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$roles=explode(',',$this->Session->read("page_access"));
			if(in_array('21',$roles))
			{
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'','po_no'=>''),'grn'=>''))));
			}
			else
			{
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('bill_no'=>'','po_no'=>''),'grn'=>'','branch_name'=>$branch_name))));
			}
		}	
		public function edit_forgrn()
		{	
			$username=$this->Session->read("username");
			$branch_name=$this->Session->read("branch_name");
			$id  = $this->request->query['id'];
			$roles=explode(',',$this->Session->read("page_access"));
			$this->layout='home';
			
			$data=array();
			if(in_array('21',$roles))
			{
					if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))))
					{return $this->redirect(array('controller'=>'Users','action' => 'login')); }
			}
			else
			{
					if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id, 'branch_name'=>$branch_name))))
					{return $this->redirect(array('controller'=>'Users','action' => 'login')); }
			}
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];

			$this->set('tbl_invoice', $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))));
			$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
			
		}
		public function update_grn()
		{
			$this->layout='home';
			if ($this->request->is('post'))
			{
				$id = $this->params['data']['InitialInvoice']['id'];
				$grn = $this->params['data']['InitialInvoice']['grn'];
				$b_name = $this->params['data']['InitialInvoice']['branch_name'];
				$bill_no = $this->params['data']['InitialInvoice']['bill_no'];
				$month = $this->params['data']['InitialInvoice']['month'];
				$amount = $this->params['data']['InitialInvoice']['total'];
				
				$files=$this->params->data['InitialInvoice']['filepath'];
				
				if(isset($files))
				{
					$filepath='';
					
					foreach($files as $file):
						move_uploaded_file($file['tmp_name'],WWW_ROOT.'upload'.'/'.$file['name']);
						$filepath.=$file['name'].",";
					endforeach;
					
						$data=array('grn'=>"'".$grn."'",'filepath' => "'".$filepath."'");
				}
				else
				{
					$data=array('grn'=>"'".$grn."'");
				}
				
					
					if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
					{
						App::uses('sendEmail', 'custom/Email');
					
						$dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));
					
						$msg = "Hi<br>".$b_name." branch has Added GRN for Invoice No.".$dataX['InitialInvoice']['bill_no']." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd'].' on'.date("F j, Y, g:i a");
					
						$msg .= "<br><strong><b style=color:#FF0000>KINDLY SEND IT FOR SUBMISSION </b></strong>";
					
						$emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));
					
						$nofifyArr = array(); $notifyLoop=0;
					
                    foreach($emailid as $email1):
                            $email2[] = trim($email1['User']['email']);
                            $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                            $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                            $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                            $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
                    endforeach;
                                        
                    $this->NotificationMaster->saveAll($nofifyArr);
                    
						$sub = "GRN Added - ".$b_name;
						$mail = new sendEmail();
						if(!empty($email2))
                                        {
                                            try
                                            {
                                                $mail-> to($email2,$msg,$sub);		
                                            }
                                            catch(Exception $e)
                                            {
                                                
                                            }
                                        }
					
					$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'GRN No. '.$grn.' to Invoice No. '.$bill_no.' to amount '.$amount.' to  '.$b_name.' for '.$month.' Added Successfully.'."</b></h4>"));
					return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_grn'));
					}
			}
			
		}
		public function check_grn()
		{
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
		}
		
		public function approve_grn()
		{						
			$this->layout='home';
			if ($this->request->is('post'))
			{
					$id=$this->params['data']['InitialInvoice']['id'];
					$grn=$this->params['data']['InitialInvoice']['grn'];
					$bill_no=$this->params['data']['InitialInvoice']['bill_no'];
					$amount=$this->params['data']['InitialInvoice']['total'];
					$b_name=$this->params['data']['InitialInvoice']['branch_name'];
					$month=$this->params['data']['InitialInvoice']['month'];
						//$this->set('res',$this->params->data);
					$data=array('grn'=>"'".$grn."'",'approve_grn'=>"'Yes'",'grn_createdate'=>"now()");
					if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
					{
						App::uses('sendEmail', 'custom/Email');
					
						$dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));
					
						$msg = "Hi<br> Admin has Approved GRN for Invoice No.".$dataX['InitialInvoice']['bill_no']." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd'].' on'.date("F j, Y, g:i a");
					
						$msg .= "<br><strong><b style=color:#FF0000> KINDLY SEND IT FOR SUBMISSION </b></strong>";
					
						$emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));
					
                                                $nofifyArr = array(); $notifyLoop=0;
					
                    foreach($emailid as $email1):
                            $email2[] = trim($email1['User']['email']);
                            $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                            $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                            $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                            $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
                    endforeach;
                                        
                    $this->NotificationMaster->saveAll($nofifyArr);
                    
						$sub  = "GRN Approve - ".$b_name;
						$mail = new sendEmail();
						if(!empty($email2))
                                        {
                                            try
                                            {
                                                $mail-> to($email2,$msg,$sub);		
                                            }
                                            catch(Exception $e)
                                            {
                                                
                                            }
                                        }
					
						$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'GRN No. '.$grn.' to Invoice '.$bill_no.' to amount '.$amount.' to '.$b_name.' for '.$month.' Approve Successfully'."</b></h4>"));
						return $this->redirect(array('controller'=>'InitialInvoices','action' => 'check_grn'));
					}
			}

			else
			{
				$id  = $this->request->query['id'];
				$this->layout='home';
				$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
				
				$b_name=$data['InitialInvoice']['branch_name'];
				$c_center=$data['InitialInvoice']['cost_center'];

				$this->set('tbl_invoice', $data);
				$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
				$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
				$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
			}
		}
		public function download_grn()
		{	
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
                        $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));
                        if($this->request->is('Post'))
                        {    
                            $branch_name=$this->Session->read("branch_name");
                            $roles=explode(',',$this->Session->read("page_access"));
                            $data = $this->request->data['InitialInvoice'];
                            
                            $condition = array();
                            if($data['company_name'] !='')
                                $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                            if($data['finance_year'] !='')
                                $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                            if($data['branch_name'] !='')
                                $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                            if($data['bill_no'] !='')
                                $condition["SUBSTRING_INDEX(InitialInvoice.bill_no,'/','1')"] =  $data['bill_no'];
                            if(!in_array('18',$roles))
                                    $condition['InitialInvoice.branch_name'] =  $branch_name;
                            $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','bill_no','total','po_no','grn',
                                'invoiceDescription','filepath'),
                                'joins'=>array(array('table'=>'cost_master',
                                'type'=>'inner','alias'=>'CostCenterMaster',
                                'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                            $this->set('tbl_invoice',$data);                           
                            //print_r($data); die;
                        }     
		}

		public function view_pdfgrn()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = $this->request->query['id'];

			$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
                        $this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
			 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function view_pdfgrn1()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = $this->request->query['id'];

			$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
			 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
                
                public function view_pdfgrn2()
		{	
			
			ini_set('memory_limit', '512M');

			$id  = $this->request->query['id'];

			$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
			
			$b_name=$data['InitialInvoice']['branch_name'];
			$c_center=$data['InitialInvoice']['cost_center'];
			$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
			$this->set('tbl_invoice', $data);
			 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
			$this->set('cost_master', $cost_master);
                        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
		}
		public function view_ahmd()
		{
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
                        if($this->request->is('Post'))
                        {
                            
                            $data = $this->request->data['InitialInvoice'];
                            
                            $condition['InitialInvoice.view_ahmedabad']='Yes';
                            if($data['company_name'] !='')
                                $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                            if($data['finance_year'] !='')
                                $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                            if($data['branch_name'] !='')
                                $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                            if($data['bill_no'] !='')
                                $condition["SUBSTRING_INDEX(InitialInvoice.bill_no,'/','1')"] =  $data['bill_no'];
                            $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','bill_no','total','po_no','grn','invoiceDescription'),
                                'joins'=>array(array('table'=>'cost_master',
                                'type'=>'inner','alias'=>'CostCenterMaster',
                                'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                            $this->set('tbl_invoice',$data);                           
                            //print_r($data); die;
                        }     
		}
		
		public function approve_ahmd()
		{
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));	
			
			if(isset($this->request->query['id']))
			{
				$id = $this->request->query['id'];
				$this->InitialInvoice->updateAll(array('view_ahmedabad'=>"'Yes'"),array('id'=>$id));
				$data=$this->InitialInvoice->find('first',array('conditions'=>array('view_ahmedabad'=>"Yes",'id'=>"".$id."")));
				
				$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>Approved  Invoice No. " .$data ['InitialInvoice'] ['bill_no'] . ' to amount ' . $data['InitialInvoice']['total'] . ' to  ' . $data['InitialInvoice']['branch_name'] . ' for ' . $data ['InitialInvoice'] ['month'] . ' to view Ahmedabad'."</b></h4>"));
			}		
		}

		public function view_invoice()
		{	
			$username=$this->Session->read("username");
			$this->layout='home';
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
                        if($this->request->is('Post'))
                        {
                            
                            $data = $this->request->data['InitialInvoice'];
                            $condition = '';
                            if($data['company_name'] !='')
                                $condition .=" and cm.company_name =  '{$data['company_name']}'";
                            if($data['finance_year'] !='')
                            $condition .=" and ti.finance_year =  '{$data['finance_year']}'";
                            if($data['branch_name'] !='')
                            $condition .=" and ti.branch_name =  '{$data['branch_name']}'";
                            if($data['bill_no'] !='')
                                $condition .=" and ti.bill_no =  '{$data['bill_no']}'";
                            
                            $data = $this->InitialInvoice->query("SELECT ti.id,ti.branch_name,ti.bill_no,ti.total,ti.po_no,ti.grn,ti.invoiceDescription FROM tbl_invoice ti 
                            INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
                            LEFT JOIN bill_pay_particulars bpp ON 
                            SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no
                            AND ti.branch_name = bpp.branch_name
                            AND ti.finance_year = bpp.financial_year
                            AND cm.company_name = bpp.company_name
                            
                            WHERE bpp.bill_no IS  NULL and ti.bill_no!='' $condition ");
                            $this->set('tbl_invoice',$data);
                            //print_r($data); die;
                        }    
			//$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
		}

		public function edit_invoice()
		{	
			$roles=explode(',',$this->Session->read("page_access"));
			
			$id  = $this->request->query['id'];
			$this->layout='home';
			$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'status'=>'0')));
			
                        //print_r($data); exit;
                        
			$b_name = $data['InitialInvoice']['branch_name'];
			$c_center = $data['InitialInvoice']['cost_center'];
			
                        $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId= '$id'");
                        
                        $ActualRevenue = array(); 
                        foreach($prov_deduction as $pd)
                        {
                            $ProvisionId = $pd['pmmd']['ProvisionId'];
                            $revenue += round($pd['pmmd']['ProvisionBalanceUsed'],2);
                            $monthMaster[$pd['pmmd']['Provision_Finance_Month']] = round($pd['pmmd']['ProvisionBalanceUsed'],2);
                            $ActualProvArr = $this->Provision->find('first',array('conditions'=>"Id='$ProvisionId'"));
                            $ActualRevenue[$pd['pmmd']['Provision_Finance_Month']]=  round($ActualProvArr['Provision']['provision_balance'],2) + round($pd['pmmd']['ProvisionBalanceUsed'],2);
                        }
                        
                        
			$this->set('roles',$roles);
                        $this->set('revenue',$revenue);
                        $this->set('monthMaster',$monthMaster);
			$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
			$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('id'=>$id))));
			$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
			$this->set('cost_master2', $this->CostCenterMaster->find('all',array('fields'=>array('cost_center'),'conditions'=>array('branch'=>$b_name))));
			$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
			$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
                        $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
		}
		public function update_invoice()
		{
    $this->layout='home';
    $roles=explode(',',$this->Session->read("page_access"));

    if ($this->request->is('post'))
    {
        $checkTotal = 0; 
        //print_r($this->request->data); exit;
        $id=$this->params['data']['InitialInvoice']['id'];
        
        
        $findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));

    $findCostCenter     = $findData['InitialInvoice']['cost_center'];
    $findFinanceYear    = $findData['InitialInvoice']['finance_year'];
    $findMonth          = $findData['InitialInvoice']['month'];
    $branch_name = $findData['InitialInvoice']['branch_name']; 
    $GSTType = $this->params['data']['InitialInvoice']['GSTType'];
    //$Revenue = $this->request->data['InitialInvoice']['revenue'];
    $RevenueMonthArr = $this->request->data['InitialInvoice']['revenue_arr'];
    //print_r($RevenueMonthArr); exit;
    $arr =explode('-',$findFinanceYear);
        
        foreach($RevenueMonthArr as $Nmonth=>$mntValue)
        {
            $amt = 0;

            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$findFinanceYear' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$findCostCenter'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];
                $ProvisionId = $prov_['Provision']['id'];
            }
            
//            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$findFinanceYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$findCostCenter' ");
//            foreach($out_source_master as $osm)
//            {
//                $amt += round($osm['pp']['outsource_amt'],2); 
//            }
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$findFinanceYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$findCostCenter' and deduction_status='1' and InvoiceId!='$id'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            
            
            if($amt<$mntValue)
            {
                //echo "Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"; exit;
                $this->Session->setFlash("Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited");
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
                break;
            }
            else
            {
                $ProvisionArray[$Nmonth] = $ProvisionId;
                $ProvisionBalArray[$Nmonth] = $amt-$mntValue;
            }
            $Revenue +=$mntValue;
        }
        
       
        ////////////////  Getting All Amount Of Created Bill Ends Here /////////
                                                
        $Transaction = $this->User->getDataSource();
        $Transaction->begin();

        $particular = $this->params['data']['Particular'];
        $k=array_keys($particular);$i=0;
        
        foreach($particular as $post){
            $dataX['particulars']="'".addslashes($post['particulars'])."'";
            $dataX['qty']="'".$post['qty']."'";
            $dataX['rate']="'".$post['rate']."'";
            $dataX['amount']="'".$post['amount']."'";
            $checkTotal += $post['amount'];
            if(!$this->Particular->updateAll($dataX,array('id'=>$k[$i++])))
            {
                $Transaction->rollback(); 
                $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
            }
            
        }
        unset($dataX);
        
        //print_r($particular); exit;
        $flag=false;
        if(!isset($this->params['data']['DeductParticular']))
        {

        }
        else
        {
            $deductparticular = $this->params['data']['DeductParticular'];
            $flag=true;
        }
        if($flag)
        {
            $k=array_keys($deductparticular);$i=0;

            foreach($deductparticular as $post):
                $dataX['particulars']="'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $checkTotal -= $post['amount'];
                if(!$this->DeductParticular->updateAll($dataX,array('id'=>$k[$i++])))
                {
                   $Transaction->rollback(); 
                   $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
                   return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
                }
            endforeach;
        }
        
        
        $findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
        $findSumPrv = $findInvAmt['0']['0']['total'];

        $findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
        $findSumDPrv = round($findDInvAmt['0']['0']['total']);

        $findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
        $findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);
        
        $finn = explode('-',$this->request->data['InitialInvoice']['finance_year']);
        
            $monthArr = array('Jan','Feb','Mar');
                $monthM = substr($findMonth,0,3);
            if(in_array($monthM, $monthArr))
            {
                if($finn[0]==date('Y') || $finn[1]==date('y'))
                {
                    $ff_mnt = $monthM.'-'.date('y');
                    $data['month'] = "'".$ff_mnt."'";
                }
                else
                {
                    $ff_mnt = $monthM.'-'.$finn[1];
                    $data['month'] = "'".$ff_mnt."'";
                }
            }
            else
            {
                $ff_mnt = $monthM.'-'.($finn[1]-1);
                $data['month'] = "'".$ff_mnt."'";
            }
        
           // echo $findFinanceYear; exit;
        
        if($Revenue==$findTotalBillMade)
           // if(1)
        {
        
           foreach($RevenueMonthArr as $Nmonth=>$mntValue)
            {

                if(!$this->ProvisionPartDed->updateAll(array('ProvisionBalanceUsed'=>$mntValue,'Provision_Finance_Year'=>"'".$findFinanceYear."'",'Provision_Finance_Month'=>$data['month'],'Provision_UsedBy_Month'=>$data['month']),array('Provision_Finance_Month'=>$Nmonth,'Provision_UsedBy_Month'=>$findMonth,'InvoiceId'=>$id)))
                {
                    $Transaction->rollback();
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
                }

                /////  Updating Provision Balance Starts From Here /////////////////
                if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$findCostCenter,'month'=>$Nmonth,'finance_year'=>$findFinanceYear)))
                { 
                    $Transaction->rollback();
                    //echo "<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"; exit;
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                    return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
                }
                //// Updating Provision Balance Ends Here  /////////////////////////////
            } 
            
        $invoiceDate = $this->params['data']['InitialInvoice']['invoiceDate'];
        $data=Hash::remove($this->params['data']['InitialInvoice'],'id');
        $data=Hash::remove($data,'revenue_arr');
        $data=Hash::remove($data,'revenue_str');
        $data=Hash::remove($data,'revenue');
            if(empty($data['RequestInvoiceType']))
            {
                $data=Hash::remove($data,'RequestInvoiceType');
            }
            else
            {
                $data['RequestInvoiceType'] = "'".$data['RequestInvoiceType']."'";
            }
            if(empty($data['InvoiceTypeRemarks']))
            {
                $data=Hash::remove($data,'InvoiceTypeRemarks');
            }
            else
            {
                $data['InvoiceTypeRemarks'] = "'".$data['InvoiceTypeRemarks']."'";
            }
            if(empty($data['InvoiceRejectRequest']))
            {
                $data=Hash::remove($data,'InvoiceRejectRequest');
            }
            else
            {
                $data['InvoiceRejectRequest'] = "'".$data['InvoiceRejectRequest']."'";
            }
            if(empty($data['InvoiceDeleteRemarks']))
            {
                $data=Hash::remove($data,'InvoiceDeleteRemarks');
            }
            else
            {
                $data['InvoiceDeleteRemarks'] = "'".$data['InvoiceDeleteRemarks']."'";
            }
            
            
            $b_name=$data['branch_name'];
            $amount=$data['total'];
            //$data=Hash::remove($data,'branch_name');

            $particular = $this->params['data']['Particular'];

            $flag=false;
            if(!isset($this->params['data']['DeductParticular']))
            {}
            else
            {
                $deductparticular = $this->params['data']['DeductParticular'];
                $flag=true;
            }
						
            $date = $data['invoiceDate'];
            $date = date_create($date);
            $date = date_format($date,"Y-m-d");
            $data['invoiceType']="'".$this->params['data']['InitialInvoice']['invoiceType']."'";
            $data['branch_name']="'".$data['branch_name']."'";
            $data['krishi_tax']="'".$data['krishi_tax']."'";
            $data['cost_center']="'".$data['cost_center']."'";
            $data['finance_year']="'".$data['finance_year']."'";
            $data['jcc_no']="'".$data['jcc_no']."'";
            $data['grn']="'".addslashes($data['grn'])."'";
            $data['bill_no']="'".$data['bill_no']."'";
            $po_no = $data['po_no'];
            $data['po_no']="'".addslashes($data['po_no'])."'";
            $data['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
            $data['month']="'".addslashes($data['month'])."'";
            $data['invoiceDate'] = "'".addslashes($date)."'";		
            $data['GSTType'] = "'".addslashes($GSTType)."'";
            $month = explode('-',str_replace("'", "", $data['month']));
            $month = $month[0];

            $finn = explode('-',str_replace("'", "", $data['finance_year']));
            $monthArr = array('Jan','Feb','Mar');

            if(in_array($month, $monthArr))
            {
                if($finn[0]==date('Y') || $finn[1]==date('y'))
                {
                    $data['month'] = "'".$month.'-'.date('y')."'";
                }
                else
                {
                $data['month'] = "'".$month.'-'.$finn[1]."'";
                }
            }
            else
            {$data['month'] = "'".$month.'-'.($finn[1]-1)."'";}
        
            $dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','finance_year','month'),'conditions'=>array('id'=>$id)));

            $dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));
            
            $tax_call = $dataY['InitialInvoice']['app_tax_cal'];
            $krishi_tax = $data['apply_krishi_tax'];
            $service_tax = $data['apply_service_tax'];
            $data['apply_gst'] = "0";

            if($dataY['InitialInvoice']['total'] != $data['total'])
            {
                $dataZ['initial_id'] = $id;
                $dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
                $dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
                $dataZ['new_amount'] = $data['total'];
                $dataZ['createdate'] = date('Y-m-d H:i:s');
                $this->EditAmount->save($dataZ);
            }
            
	if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
	{
            //print_r($data); exit;
					
            $tax = 0;
            $sbctax = 0;
            $krishiTax = 0;
            $igst = 0;
            $sgst =0;
            $cgst = 0;
            $total =round($checkTotal,0); 
            $apply_gst = "0";				
            if($tax_call == '1')
            {
                if(strtotime($date) > strtotime("2017-06-30"))
                {
                    $apply_gst = "1"; 

                    $apply_krishi_tax = "0";
                    if($GSTType=='Integrated')
                    {
                        $igst = round($checkTotal*0.18,0);
                    }
                    else 
                    {
                      echo  $sgst = $cgst = round($checkTotal*0.09,0);
                    }
                }
                else
                {
                    $apply_gst = "0";

                    $krishi_tax = 1;
                    $tax = round($checkTotal*0.14,0);
                    $sbctax = 0;
                    if(strtotime($date) > strtotime("2015-11-14"))
                        {$sbctax = round($checkTotal*0.005,0); }

                    if($krishi_tax == "1")
                    {
                        $krishiTax = round($checkTotal*0.005,0);
                        $apply_krishi_tax  = "1";
                    }
                }
            }
        
            if($service_tax=="1")
            {
               $total = "0"; 
            }
            $grnd = round($total + $tax + $sbctax+$krishiTax+$sgst+$cgst+$igst,0);
        
            $total2 = 0;
            if($dataA['InitialInvoice']['cost_center'] == str_replace("'", "", $data['cost_center']) && $dataA['InitialInvoice']['month'] == str_replace("'", "", $data['month']) && $dataA['InitialInvoice']['finance_year'] == str_replace("'", "", $data['finance_year']) )
            {
                $total2 = $dataA['InitialInvoice']['total'] -$total;
            }
            else
            {
                $total2 = $total - 2 *$total; 
                $total3 = $dataA['InitialInvoice']['total'];
                //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total3"),array('cost_center'=>  $dataA['InitialInvoice']['cost_center'],'finance_year'=>$dataA['InitialInvoice']['finance_year'],'month'=>$dataA['InitialInvoice']['month']));
            }
        
            //$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total2"),array('cost_center'=>  str_replace("'", "", $data['cost_center']),'finance_year'=>str_replace("'", "", $data['finance_year']),'month'=>str_replace("'", "", $data['month'])));

            $dataY = array('po_no'=>"'".$po_no."'",'total'=>$total,'tax'=>$tax,'sbctax'=>$sbctax,'grnd'=>$grnd,'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,'krishi_tax'=>$krishiTax,'apply_krishi_tax'=>$apply_krishi_tax,'apply_gst'=>$apply_gst);
                
            if($this->InitialInvoice->updateAll($dataY,array('id'=>$id)))
            {
                $Transaction->commit(); 
            }
                
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.'."</b></h4>"));
            $username=$this->Session->read("username");

            App::uses('sendEmail', 'custom/Email');

            $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

            $msg = "Hi<br>".$b_name." branch Invoice No.".$dataX['InitialInvoice']['bill_no'].' has been Edited. '.date("F j, Y, g:i a");

            $msg .= "<br><strong><b style=color:#FF0000>Kindly update your Records </b></strong>";

            $emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' => array('work_type'=>'account','UserActive'=>'1','not' => array('email' => ''),'OR' => array('and'=>array('branch_name' => $b_name),'role' => 'admin'))));

            $nofifyArr = array(); $notifyLoop=0;
					
            foreach($emailid as $email1):
                    $email2[] = trim($email1['User']['email']);
                    $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                    $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                    $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                    $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
            endforeach;
                                        
            //$this->NotificationMaster->saveAll($nofifyArr);

            $sub = "Invoice Edited";
            $mail = new sendEmail();
            if(!empty($email2))
                                        {
                                            try
                                            {
                                                $mail-> to($email2,$msg,$sub);		
                                            }
                                            catch(Exception $e)
                                            {
                                                
                                            }
                                        }				

            if(in_array('5',$roles))
            {return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_invoice'));}
            else
            {return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));}
        
            }
            else
            {
                $Transaction->rollback(); 
                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Updation Failed'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
            }
	}
        
        else
        {
            $Transaction->rollback(); 
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Provision  for Amount '.$amount.' is Less'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
        }
        
    }
}

		public function reject_invoice()
		{
			$id  = $this->request->query['id'];
			$data = array('status' => '1');
			if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
			{
                            $Initial = $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
                            $Provision = $this->Provision->updateAll(array('provision_balance'=>'provision_balance'+$Initial['InitialInvoice']['total']),
                                    array('branch_name'=>$Initial['InitialInvoice']['branch_name'],'finance_year'=>$Initial['InitialInvoice']['finance_year'],'month'=>$Initial['InitialInvoice']['month'],
                                        'cost_center'=>$Initial['InitialInvoice']['cost_center']));
				$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to Bill No. '.$id.' Rejected Successfully.'."</b></h4>"));
				return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_invoice'));
			}
		}

		public function dashboard()
		{
			$this->layout='home';
                        $role = $this->Session->read('role');
                        $branch_name="and branch_name='".$this->Session->read("branch_name")."'";
                        
                        if($role=='admin')
                        {
                            $branch_name='';
                        }
                        
			$this->set("dashboard",$this->InitialInvoice->query("SELECT (CASE WHEN branch_name IS NOT NULL THEN branch_name ELSE branch_name END)`tds`,
SUM(CASE WHEN bill_no IS NULL OR bill_no = '' THEN 1 ELSE 0 END)`wait_bill_approve`,
SUM(CASE WHEN bill_no!='' AND (po_no='' OR po_no IS NULL) AND (approve_po = '' OR approve_po IS NULL) AND (approve_grn='' OR approve_grn IS NULL) AND STATUS='0' THEN 1 ELSE 0 END)`bill_gnr`,
 SUM(CASE WHEN bill_no!='' AND po_no != '' AND approve_po != '' AND (grn = '' OR grn IS NULL) AND (approve_grn = '' OR approve_grn IS NULL)
 AND STATUS='0' THEN 1 ELSE 0 END)`apr_po`,
 SUM(CASE WHEN bill_no !='' AND (po_no != '') AND (approve_po = '' OR approve_po IS NULL) AND (grn = '' OR grn IS NULL) AND (approve_grn = '' OR approve_grn IS NULL)
 AND STATUS='0' THEN 1 ELSE 0 END) `wait_po`,
 SUM(CASE WHEN bill_no !='' AND po_no !='' AND approve_po != ''  AND approve_grn = ''
 AND STATUS='0' THEN 1 ELSE 0 END) `wait_grn`,
 SUM(CASE WHEN bill_no !='' AND po_no !='' AND approve_po != '' AND grn != '' AND approve_grn = 'Yes'
 AND STATUS='0' THEN 1 ELSE 0 END) `final_inv` 
 FROM tbl_invoice WHERE id> 460 AND Status = '0' $branch_name GROUP BY branch_name"));
}

                public function apply_service_tax()
                { 
                    $username=$this->Session->read("username");
                    $this->layout="ajax";
                    $id = $this->params->query['id'];    
                    $apply = $this->params->query['apply'];
                    if($apply=="Yes")
                    {
                    $this->InitialInvoice->updateAll(array("total"=>'0',"apply_service_tax"=>"1"),array('id'=>$id));
                    }
                    else 
                        {
                        $Particular = $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));

                        $DeductParticular = $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));
                        $total = 0;
                        foreach($Particular as $post):
                            $total += $post['Particular']['amount'];
                        endforeach;
                        foreach($DeductParticular as $post):
                            $total -= $post['DeductParticular']['amount'];
                        endforeach;  

                        $this->InitialInvoice->updateAll(array("total"=>$total,"apply_service_tax"=>"0"),array('id'=>$id));
                    }   
                }
                public function apply_tax_cal()
                { 
                    $username=$this->Session->read("username");
                    $this->layout="ajax";
                    $id = $this->params->query['id'];    
                    $apply = $this->params->query['apply'];
                    if($apply=="No")
                    {
                        //$this->InitialInvoice->find('first',array('fields'=>array('total'),'conditions'=>array('id'=>$id)));
                        $this->InitialInvoice->updateAll(array("sbctax"=>'0','apply_krishi_tax'=>'0','krishi_tax'=>'0',"app_tax_cal"=>"0",'grnd'=>'total'),array('id'=>$id));
                    }
                    else 
                    {
                        $Particular = $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));

                        $DeductParticular = $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));
                        $total = 0;
                        foreach($Particular as $post):
                            $total += $post['Particular']['amount'];
                        endforeach;
                        foreach($DeductParticular as $post):
                            $total -= $post['DeductParticular']['amount'];
                        endforeach;  
                        $data = $this->InitialInvoice->find('first',array('fields'=>array('invoiceDate','finance_year','cost_center'),'conditions'=>array('id'=>$id)));
                       $sbctax = "0"; $krishiTax = "0";$apply_krishi_tax=0;$apply_gst =0;
                        if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30"))
                        {
                            $cost_center = $this->CostCenterMaster->find('first',array('conditions'=>array('cost_center'=>$data['InitialInvoice']['cost_center'])));
                            $apply_gst = "1";

                               $tax = 0;
                                if($cost_center['CostCenterMaster']['GSTType']=='Integrated')
                                {
                                    $igst = round($total*0.18,0);
                                }
                                else 
                                {
                                    $sgst = $cgst = round($total*0.09,0);
                                }
                        }
                        else
                        {
                             $tax = $total*0.14; 
                        if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
                        {
                           $sbctax = $total * 0.05;
                        }
                        if($data['InitialInvoice']['finance_year']=='2016-17')
                        {
                           $krishiTax = $total * 0.05;
                           $apply_krishi_tax=1;

                        }
                        }
                        $grnd = $total+$tax+$krishiTax+$sbctax+$igst+$sgst+$cgst;
                         $this->InitialInvoice->updateAll(
                         array("total"=>$total,'apply_gst'=>$apply_gst,'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,"apply_krishi_tax"=>$apply_krishi_tax,"krishi_tax"=>$krishiTax,'sbctax'=>$sbctax,'app_tax_cal'=>'1','grnd'=>$grnd),array('id'=>$id));
                    }    
                }
                public function apply_krishi_tax()
                { exit;
                    $username=$this->Session->read("username");
                    $this->layout="ajax";
                    $id = $this->params->query['id'];    
                    $apply = $this->params->query['apply'];
                    if($apply=="No")
                    {
                        //$this->InitialInvoice->find('first',array('fields'=>array('total'),'conditions'=>array('id'=>$id)));
                        $this->InitialInvoice->updateAll(array('apply_krishi_tax'=>'0','krishi_tax'=>'0','grnd'=>'total+tax+sbctax'),array('id'=>$id));
                    }
                    else 
                    {
                        $Particular = $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));

                        $DeductParticular = $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));
                        $total = 0;
                        foreach($Particular as $post):
                            $total += $post['Particular']['amount'];
                        endforeach;
                        foreach($DeductParticular as $post):
                            $total -= $post['DeductParticular']['amount'];
                        endforeach;  
                        $data = $this->InitialInvoice->find('first',array('fields'=>array('grnd'),'conditions'=>array('id'=>$id)));
                        $apply_krishi_tax=1;
                        $krishiTax = round($total * 0.005,0);

                        $grnd =$data['InitialInvoice']['grnd']+$krishiTax ;
                         $this->InitialInvoice->updateAll(
                         array("apply_krishi_tax"=>$apply_krishi_tax,"krishi_tax"=>$krishiTax,'grnd'=>$grnd),array('id'=>$id));
                    }    
                }

                public function view_status_change_request()
                {
                    $this->layout="home";
                    $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'=>'asc'))));

                    $this->set('finance_year', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
                        'conditions'=>array('active'=>'1','not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));

                    $data = $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('RequestInvoiceType'=>null))));

                    $this->set('data',$data);

                    if($this->request->is('Post'))
                    {
                        if(!empty($this->request->data['View']))
                        {
                            $branch_name = $this->request->data['InitialInvoice']['branch_name'];
                            $year = $this->request->data['InitialInvoice']['year'];
                            $data = $this->InitialInvoice->find('all',array('conditions'=>array('branch_name'=>$branch_name,'finance_year'=>$year,'not'=>array('RequestInvoiceType'=>null))));
                            $this->set('data',$data);
                        }
                        else
                        {
                           $id= $this->request->data['InitialInvoice']['id']; 
                           $this->InitialInvoice->updateAll(array('CurrentInvoiceType'=>'RequestInvoiceType','RequestInvoiceType'=>null,'InvoiceTypeApproveBy'=>"'".$this->Session->read('userid')."'",
                               'InvoiceTypeApproveDate'=>"'".date('Y-m-d')."'"),array('id'=>$id));
                           $this->redirect(array('action'=>'view_status_change_request'));
                            $this->Session->setFlash('Records Updated Successfully');
                        }
                    }   
                }
                public function delete_invoice()
                {
//                    $this->layout="home";
//                    $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'=>'asc'))));
//
//                    $this->set('finance_year', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
//                        'conditions'=>array('active'=>'1','not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));
//
//                    $data = $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('InvoiceRejectRequest'=>null))));
//                    if($this->request->is('Post'))
//                    {
//                        if(!empty($this->request->data['View']))
//                        {
//                            $branch_name = $this->request->data['InitialInvoice']['branch_name'];
//                            $year = $this->request->data['InitialInvoice']['year'];
//                            $data = $this->InitialInvoice->find('all',array('conditions'=>array('branch_name'=>$branch_name,'finance_year'=>$year,'not'=>array('InvoiceRejectRequest'=>null))));
//                            $this->set('data',$data);
//                        }
//                        else
//                        {
//                           $id= $this->request->data['InitialInvoice']['id']; 
//                           $this->InitialInvoice->updateAll(array('status'=>'1','InvoiceRejectBy'=>"'".$this->Session->read('userid')."'",
//                               'InvoiceRejectDate'=>"'".date('Y-m-d')."'"),array('id'=>$id));
//                           $this->redirect(array('action'=>'delete_invoice'));
//                            $this->Session->setFlash('Invoice Deleted Successfully');
//                        }
//                    }

                }

}
?>