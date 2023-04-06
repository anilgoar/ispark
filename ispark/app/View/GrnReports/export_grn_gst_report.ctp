<?php ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch</th>
                            <th>Bill No</th>
                            <th>GRN No</th>
                            <th>Approval Date</th>
                            <th>Vendor</th>
                            <th>Expense Sub Head</th>
                            <th>Amount</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>Total</th>
                            <th>Taxable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport); exit;
                                foreach($ExpenseReport as $exp)
                                {
                                    $FinanceYear = $exp['em']['FinanceYear'];
                                    $FinanceMonth = $exp['em']['FinanceMonth'];
                                    $monthArray=array('Jan'=>'01','Feb'=>'02','Mar'=>'03','Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
                                    $FinanceMonthNum = $monthArray[$FinanceMonth];
                                    
                                    if($monthArray[$FinanceMonth]<=3) 
                                    {
                                        $FinanceYear1 = explode('-',$FinanceYear);
                                        $FinanceYear2 = $FinanceYear1[1];
                                    }
                                    else
                                    {
                                        $FinanceYear1 = explode('-',$FinanceYear);
                                        $FinanceYear2 = $FinanceYear1[1]-1;
                                    }
                                    $FinanceMonth1 =  $monthArray[$FinanceMonth];
                                    $diff =0;
                                    $tdsAmount = 0;
                                        
                                        
                                    /////////// Entry For SubHead    /////////////////
                                    echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$exp['bm']['branch_name']."</td>";
                                    //echo "<td>".$FinanceMonth1."</td>";
                                    echo "<td>".$exp['em']['bill_no']."</td>";
                                    echo "<td>".$exp['em']['GrnNo']."</td>";

                                    echo "<td>".$exp['em']['ApprovalDate']."</td>";
                                    echo "<td>".$exp['vm']['TallyHead']."</td>";
                                    echo "<td>".$exp['subhead']['SubHeadingDesc']."</td>";
                                    echo "<td>".round($exp['0']['Amount'],2)."</td>";
                                        
                                    if(!empty($exp['0']['Tax']))
                                    {
                                        /////////// Entry For GST Enable Tax      //////////////
                                       if($exp['0']['GSTType']=='state')
                                       {   
                                            echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                            echo "<td>".round($exp['0']['Tax']/2,2)."</td>";
                                            echo "<td>0</td>";
                                       }
                                       else 
                                       {
                                            echo "<td>0</td>";
                                            echo "<td>0</td>";
                                            echo "<td>".round($exp['0']['Tax'],2)."</td>";
                                       }
                                    }
                                    else
                                    {
                                        echo "<td>0</td>";
                                            echo "<td>0</td>";
                                            echo "<td>0</td>";
                                    }
                                    echo "<td>".round($exp['0']['Total'],2)."</td>";
                                    
                                    if(!empty($exp['0']['Tax']))
                                    {
                                        echo "<td>Taxable</td>";
                                    }
                                    else
                                    {
                                            
                                        echo "<td>Non - Taxable</td>";
                                    }
                                }
                                
                        ?>
                    </tbody>
                </table>    
<?php
if($type=='Export')
{
        $fileName = "GST_Report_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>            
<?php exit; ?>		

		
					
		
           

