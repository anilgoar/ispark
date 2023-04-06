<?php
class TallysController extends AppController 
{
    public $uses=array('TallyInvoiceVoucherExport');
    public $components =array('Session');
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
            $this->Auth->allow('index','view','add','edit','update','provision_check','provision_check_edit','uploadProvision','view_provision');
            
	}
    }
		
   
    
//    public function uploadProvision()
//    {
//        $this->layout = "home";
//        $wrongData = array();
//        if($this->request->is('POST'))
//        {
//            
//            $user = $this->Session->read('username');
//            $FileTye = $this->request->data['Tally']['file']['type'];
//            $info = explode(".",$this->request->data['Tally']['file']['name']);
//            
//            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
//            {
//		$FilePath = $this->request->data['Tally']['file']['tmp_name'];
//                $files = fopen($FilePath, "r");
//                //$files = file_get_contents($FilePath);
//                //echo $files;
//                
//               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
//                $dataArr = array();
//                $this->TallyInvoiceVoucherExport->query("Truncate table tbl_tally_row_invoice_data");
//                while($row = fgetcsv($files,5000,","))
//                {
//                        $data['process'] = $row[0];
//                        $data['cost_center'] = $row[1];
//                        $data['company_name'] = $row[2];
//                        $data['branch'] = $row[3];
//                        $data['client'] = $row[4];
//                        $data['remarks'] = $row[5];
//                        $data['tally_client_name'] = $row[6];
//                        $data['tally_ref_no1'] = $row[7];
//                        $data['tally_ref_no2'] = $row[8];
//                        $data['IGST'] = $row[9];
//                        $data['CGST'] = $row[10];
//                        $data['SGST'] = $row[11];
//                        $data['createby'] = $this->Session->read('userid');
//                        $data['createdate'] = date('Y-m-d H:i:s');
//                        $dataArr[] = $data;
//                }
//                //print_r($dataArr);
//                if($this->TallyInvoiceVoucherExport->saveAll($dataArr))
//                {
//                   $data = $this->TallyInvoiceVoucherExport->find('all',array('conditions'=>array('Id'=>'1'))); 
//
//                echo '<table border="1">';
//                echo    '<thead>';
//                echo        '<tr>';
//                echo            '<th>Vch No</th>';
//                echo            '<th>Date</th>';
//                echo            '<th>Details</th>';
//                echo            '<th>Amount</th>';
//                echo            '<th>DebitCredit</th>';
//                echo            '<th>Cost Category</th>';
//                echo            '<th>Cost Centre</th>';
//                echo            '<th>Narration for Each Entry</th>';
//                echo            '<th>Narration</th>';
//                echo            '<th>VchType</th>';
//                echo        '</tr>';
//                echo    '</thead>';
//                echo    '<tbody>';
//             $i=1; $Total=0;//print_r($ExpenseReport); exit;
//                    foreach($data as $exp)
//                    {
//
//                            /////////// Entry For SubHead    /////////////////
//                            echo "<tr>";
//                            echo "<td>".$i++."</td>";
//                            echo "<td>".$exp['TallyInvoiceVoucherExport']['TallyDate']."</td>";
//                            echo "<td>".$exp['TallyInvoiceVoucherExport']['cost_center']."</td>";
//                            
//                            echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
//                            echo "<td>".round($exp['0']['Amount'],2)."</td>";
//                            echo "<td>D</td>";
//                            echo "<td>".$exp['bm']['tally_branch']."</td>";
//                            echo "<td>";
//                            echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
//                            echo "</td>";
//                            echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                            echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
//                            echo "<td>Journal</td>";
//                            echo "</tr>";
//                            ///////// Entry For SubHead End //////////////////
//
//                            $diff = $exp['0']['Amount'];
//
//                            if($exp['tscgd']['GSTEnable']=='1' && !empty($exp['eep']['Rate']))
//                            {
//                                /////////// Entry For GST Enable Tax      //////////////
//                               if($exp['0']['GSTType']=='state')
//                               {   
//                                    echo "<tr>";
//                                    echo "<td>".$exp['0']['VchNo']."</td>";
//                                    echo "<td>".$exp['0']['Dates']."</td>";
//                                    echo "<td>Input CGST @".($exp['eep']['Rate']/2)."%(".$exp['bm']['state'].")"."</td>";
//                                    echo "<td>".($exp['0']['Tax']/2)."</td>";
//                                    echo "<td>D</td>";
//                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
//                                    echo "<td>";
//                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
//                                    echo "</td>";
//                                    echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                                    echo "<td>".$exp['em']['Narration'].$exp['em']['GrnNo']."</td>";
//                                    echo "<td>Journal</td>";
//                                    echo "</tr>";
//
//                                    echo "<tr>";
//                                    echo "<td>".$exp['0']['VchNo']."</td>";
//                                    echo "<td>".$exp['0']['Dates']."</td>";
//                                    echo "<td>Input SGST @".($exp['eep']['Rate']/2)."%(".$exp['bm']['state'].")"."</td>";
//                                    echo "<td>".($exp['0']['Tax']/2)."</td>";
//                                    echo "<td>D</td>";
//                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
//                                    echo "<td>";
//                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
//                                    echo "</td>";
//                                    echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                                    echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
//                                    echo "<td>Journal</td>";
//                                    echo "</tr>";
//
//                                    $diff += $exp['0']['Tax'];
//
//                               }
//                               else 
//                               {
//                                    echo "<tr>";
//                                    echo "<td>".$exp['0']['VchNo']."</td>";
//                                    echo "<td>".$exp['0']['Dates']."</td>";
//                                    echo "<td>Input IGST @".($exp['eep']['Rate'])."%(".$exp['bm']['state'].")"."</td>";
//                                    echo "<td>".($exp['0']['Tax'])."</td>";
//                                    echo "<td>D</td>";
//                                    echo "<td>".$exp['bm']['tally_branch']."</td>";
//                                    echo "<td>";
//                                    echo $exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1;
//                                    echo "</td>";
//                                    echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                                    echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
//                                    echo "<td>Journal</td>";
//                                    echo "</tr>";
//                                    $diff += $exp['0']['Tax'];
//                               }
//
//                               ////////// Entry For GST Disable Tax      //////////////
//                            }
//
//                            echo "<tr>";
//                            echo "<td>".$exp['0']['VchNo']."</td>";
//                            echo "<td>".$exp['0']['Dates']."</td>";
//                            echo "<td>".$exp['vm']['TallyHead']."</td>";
//                            echo "<td>".$exp['0']['Total']."</td>";
//                            echo "<td>C</td>";
//                            echo "<td>".$exp['bm']['tally_branch']."</td>";
//                            echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
//                            echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                            echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
//                            echo "<td>Journal</td>";
//                        echo "</tr>";
//
//                        $diff = $exp['0']['Total']-$diff;
//                        if($diff!=0)
//                        {
//                            echo "<tr>";
//                            echo "<td>".$exp['0']['VchNo']."</td>";
//                            echo "<td>".$exp['0']['Dates']."</td>";
//                            echo "<td>Short/Excess Written off</td>";
//                            echo "<td>".round(abs($diff),2)."</td>";
//                            if($diff>0)
//                            {
//                                echo "<td>D</td>";
//                            }
//                            else
//                            {
//                                echo "<td>C</td>";
//                            }
//
//                            echo "<td>".$exp['bm']['tally_branch']."</td>";
//                            echo "<td>".$exp['bm']['tally_code'].'/'.$FinanceYear2.$FinanceMonth1."</td>";
//                            echo "<td>".$exp['eep']['NarrationEach']."</td>";
//                            echo "<td>".$exp['em']['Narration'].' GRN NO.: '.$exp['em']['GrnNo']."</td>";
//                            echo "<td>Journal</td>";
//                        }
//                    }
//                                
//                    
//                    echo '</tbody>';
//                echo '</table>';    
//
//                if($type=='Export')
//                {
//                        $fileName = "GV_".date('Y_m_d_H_i_s');
//                        header("Content-Type: application/vnd.ms-excel; name='excel'");
//                        header("Content-type: application/octet-stream");
//                        header("Content-Disposition: attachment; filename=$fileName.xls");
//                        header("Pragma: no-cache");
//                        header("Expires: 0");
//                }
//
//exit;
//
//		
//					
//		
//           
//
//
//                    
//                    $this->Session->setFlash('File uploaded Successfully');
//                }
//                else
//                {
//                    $this->Session->setFlash('Data Not Saved');
//                }
//            }
//            else{
//            $this->Session->setFlash('File Format not Valid');}
//        }
//    } 
    
    public function uploadProvision()
    {
        $this->layout = "home";
        $wrongData = array();
        if($this->request->is('POST'))
        {
            
            $user = $this->Session->read('username');
            $FileTye = $this->request->data['Tally']['file']['type'];
            $info = explode(".",$this->request->data['Tally']['file']['name']);
            
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
            {
		$FilePath = $this->request->data['Tally']['file']['tmp_name'];
                $files = fopen($FilePath, "r");
                //$files = file_get_contents($FilePath);
                //echo $files;
                
               //$Res = $this->TMPProvision->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_provision_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(cost_center,finance_year,month,provision,remarks)");
                $dataArr = array();
                $this->TallyInvoiceVoucherExport->query("Truncate table tbl_tally_row_invoice_data");
                while($row = fgetcsv($files,5000,","))
                {
                        $data['bill_no'] = $row[0];
                        $data['Pending'] = $row[1];
                        $data['Process_Code'] = $row[2];
                        $data['Company'] = $row[3];
                        $data['Branch'] = $row[4];
                        $data['Client'] = $row[5];
                        $data['FinancialYear'] = $row[6];
                        $data['month1'] = $row[7];
                        $data['PONo'] = $row[8];
                        $data['GRN_No'] = $row[9];
                        $data['InvoiceDate'] = $row[10];
                        $data['CompanyGSTNo'] = $row[11];
                        $data['VendorGSTNo'] = $row[12];
                        $data['Amount'] = $row[13];
                        $data['IGST'] = $row[14];
                        $data['CGST'] = $row[15];
                        $data['SGST'] = $row[16];
                        $data['GTotal'] = $row[17];
                        $data['Remarks'] = $row[18];
                        $data['status'] = $row[19];
                        $data['BillPassed'] = $row[20];
                        $data['PaymentReceived'] = $row[21];
                        $data['TDS'] = $row[22];
                        $data['ReceivedOn'] = $row[23];
                        $data['ChequeNo'] = $row[24];
                        $data['BillNoTally'] = $row[25];
                        $data['Month'] = $row[26];
                        
                        
                        
                        $data['createby'] = $this->Session->read('userid');
                        $data['createdate'] = date('Y-m-d H:i:s');
                        $dataArr[] = $data;
                }
                //print_r($dataArr);
                if($this->TallyInvoiceVoucherExport->saveAll($dataArr))
                {
                   $data = $this->TallyInvoiceVoucherExport->find('all',array('conditions'=>"Id!='1'")); 
                   $fileName = "Invoice_Export".date('Y_m_d_H_i_s');
                        header("Content-Type: application/vnd.ms-excel; name='excel'");
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=$fileName.xls");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                echo '<table border="1">';
                echo    '<thead>';
                echo        '<tr>';
                echo            '<th>Vch No</th>';
                echo            '<th>Date</th>';
                echo            '<th>Details</th>';
                echo            '<th>Amount</th>';
                echo            '<th>DebitCredit</th>';
                echo            '<th>Cost Category</th>';
                echo            '<th>Cost Centre</th>';
                echo            '<th>Narration for Each Entry</th>';
                echo            '<th>Narration</th>';
                echo            '<th>VchType</th>';
                echo        '</tr>';
                echo    '</thead>';
                echo    '<tbody>';
             $i=1; $Total=0;//print_r($ExpenseReport); exit;
                    foreach($data as $exp)
                    {
                        $FinanceYear = $exp['TallyInvoiceVoucherExport']['FinancialYear'];
                                    $FinanceMonthArr = explode('-',$exp['TallyInvoiceVoucherExport']['month1']);
                                    $FinanceMonth = $FinanceMonthArr[0];
                                    $monthArray=array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
                                    $FinanceMonthNum = $monthArray[$FinanceMonth];
                                    if($monthArray[$FinanceMonth]<=3) 
                                        {
                                            $FinanceYear1 = explode('-',$FinanceYear);
                                            $FinanceYear2 = $FinanceYear1[1]-1;
                                        }
                                        else
                                        {
                                            $FinanceYear1 = explode('-',$FinanceYear);
                                            $FinanceYear2 = $FinanceYear1[1]-1;
                                        }
                                       $FinanceMonth1 =  $monthArray[$FinanceMonth];
                                        if(strlen($FinanceMonth1)==1)
                                        {
                                            $FinanceMonth1 = '0'.$FinanceMonth1;
                                        }
                            /////////// Entry For SubHead    /////////////////
                            echo "<tr>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                            
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Process_Code']."</td>";
                            echo "<td>".round($exp['TallyInvoiceVoucherExport']['Amount'],2)."</td>";
                            echo "<td>D</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                            echo "<td>";
                            echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                            echo "</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['NarrationEach']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>Journal</td>";
                            echo "</tr>";
                            ///////// Entry For SubHead End //////////////////

                            $diff = $exp['TallyInvoiceVoucherExport']['Amount'];

                           
                                /////////// Entry For GST Enable Tax      //////////////
                               if(!empty($exp['TallyInvoiceVoucherExport']['CGST']))
                               {   
                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Input CGST @9%(".$exp['TallyInvoiceVoucherExport']['Branch'].")"."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['CGST']."</td>";
                                    echo "<td>D</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                                    echo "<td>";
                                    echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['NarrationEach']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>Journal</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Input SGST @9%(".$exp['TallyInvoiceVoucherExport']['Branch'].")"."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['CGST']."</td>";
                                    echo "<td>D</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                                    echo "<td>";
                                    echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['NarrationEach']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>Journal</td>";
                                    echo "</tr>";

                                    $diff += $exp['TallyInvoiceVoucherExport']['CGST']+$exp['TallyInvoiceVoucherExport']['SGST'];

                               }
                               else 
                               {
                                    echo "<tr>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                                    echo "<td>Input IGST @18%(".$exp['TallyInvoiceVoucherExport']['Branch'].")"."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['IGST']."</td>";
                                    echo "<td>D</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                                    echo "<td>";
                                    echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                                    echo "</td>";
                                    echo "<td>".$exp['eep']['NarrationEach']."</td>";
                                    echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                                    echo "<td>Journal</td>";
                                    echo "</tr>";
                                    $diff += $exp['TallyInvoiceVoucherExport']['IGST'];
                               }

                               ////////// Entry For GST Disable Tax      //////////////
                            

                            echo "<tr>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                            
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Client']."</td>";
                            echo "<td>".round($exp['TallyInvoiceVoucherExport']['GTotal'],2)."</td>";
                            echo "<td>C</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                            echo "<td>";
                            echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                            echo "</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['NarrationEach']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>Journal</td>";
                            echo "</tr>";
                        echo "</tr>";

                        $diff = $exp['TallyInvoiceVoucherExport']['GTotal']-$diff;
                        if($diff!=0)
                        {
                            echo "<tr>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['bill_no']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['InvoiceDate']."</td>";
                            echo "<td>Short/Excess Written off</td>";
                            echo "<td>".round(abs($diff),2)."</td>";
                            if($diff>0)
                            {
                                echo "<td>D</td>";
                            }
                            else
                            {
                                echo "<td>C</td>";
                            }

                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Branch']."</td>";
                            echo "<td>";
                            echo $exp['TallyInvoiceVoucherExport']['Branch'].'/'.$FinanceYear2.$FinanceMonth1;
                            echo "</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['NarrationEach']."</td>";
                            echo "<td>".$exp['TallyInvoiceVoucherExport']['Remarks'].' GRN NO.: '.$exp['TallyInvoiceVoucherExport']['BillNoTally']."</td>";
                            echo "<td>Journal</td>";
                            echo "</tr>";
                        }
                    }
                                
                    
                    echo '</tbody>';
                echo '</table>';    

                
                        
                

exit;

		
					
		
           


                    
                    $this->Session->setFlash('File uploaded Successfully');
                }
                else
                {
                    $this->Session->setFlash('Data Not Saved');
                }
            }
            else{
            $this->Session->setFlash('File Format not Valid');}
        }
    } 
}

?>