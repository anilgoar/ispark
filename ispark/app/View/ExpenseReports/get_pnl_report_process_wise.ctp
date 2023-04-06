<?php
$sCost = array('0'=>'61','1'=>'4','3'=>'107','4'=>'17','5'=>'22','6'=>'26','7'=>'35','8'=>'43','9'=>'133','10'=>'52','11'=>'189','12'=>'362','13'=>'366');
$cntLoop = "3";
//print_r($provision); exit

//$cost_branch = array('1'=>'Noida');
$cost_a1 = array_values($cost_Nbranch);
$cost_a2 = array_values($cost_master);

$cost_a3 = array_intersect($cost_a1,$cost_a2);
$branch_a4 = array();
foreach($cost_a3 as $cost_id)
{
    $branch_a4[$cost_Nbranch[$cost_id]] +=1;
}


?>
<table border="1">
    <thead>
        <tr>
            <th></th>
            <th colspan="<?php echo count($cost_master); ?>">Processed</th>
            <th>Total Processed</th>
            <th colspan="<?php echo count($cost_master); ?>">UnProcessed</th>
            <th>Total UnProcessed</th>
            <th>Gr. Total</th>
        </tr>
        <tr>
            <th rowspan="3">Revenue</th>
            
            <?php 
                if(!empty($branch_a4)) { foreach($branch_a4 as $br=>$br_cost)
                {
                    echo "<td colspan=\"$br_cost\">".$br."</td>";
                }
            ?>
            <th rowspan="3">Total Processed</th>
                <?php }
                if(!empty($branch_a4)) { foreach($branch_a4 as $br=>$br_cost)
                {
                    echo "<td colspan=\"$br_cost\">".$br."</td>";
                }
            ?>
            <th rowspan="3">Total UnProcessed</th>
            <th rowspan="3">Gr. Total</th>            
            <?php } ?> 
        </tr>
        <tr>
            
            
            <?php 
                if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                {
                    echo "<td>".$cost_name[$cost]."</td>";
                }
            ?>
            
                <?php }
                if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                {
                    echo "<td>".$cost_name[$cost]."</td>";
                }
             } ?> 
        </tr>
        
          <tr>
            
            
            <?php $NewBranch_master = array();
                if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                {
                    echo "<td>".$cost_name[$cost]."</td>";
                }
            ?>
            
                <?php }
                if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                {
                    echo "<td>".$cost_name[$cost]."</td>";
                }
            ?>
            
            
            <?php } ?> 
        </tr>    
        <tr>
            <th>Billing</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotBill =$TotBillUnProc= 0; //print_r($provision); exit; exit;
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo "<td>".round($billing_master_proc[$cost])."</td>";
                        $TotBill += round($billing_master_proc[$cost]);  
                    } 
            ?>
            <th><?php echo round($TotBill);?></th>
                    <?php   }
                    if(!empty($cost_master))
                    { foreach($cost_master as $cost=>$cost_value)
                        {
                            echo "<td>".round($billing_master_un[$cost]-$billing_master_proc[$cost])."</td>";
                            $TotBillUnProc += round($billing_master_un[$cost]-$billing_master_proc[$cost]);     
                        } 
            ?>
            
            <th><?php echo round($TotBillUnProc);?></th>
            <th><?php echo round($TotBillUnProc+$TotBill);?></th>
            <?php } ?> 
        </tr>
        
            <tr>
                <th>Gross Revenue</th>
                <?php    //UnProcessed Provision For Branch Type A
                        $TotalRevenue = 0; $TotProv = 0; //print_r($provision); exit; exit; 
                        if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                        {
                            echo "<td>".(round($inv_master[$cost] )  + round($billing_proc[$cost]))."</td>";
                            $TotInv += round($inv_master[$cost]) + round($billing_proc[$cost]);
                            $TotProv += round($provision[$cost]) +  round($billing[$cost]);
                            
                            
                        } 
                ?>
                <th><?php echo round($TotInv);?></th>
                        <?php   }
                        if(!empty($cost_master))
                        { foreach($cost_master as $cost=>$cost_value)
                            {
                                echo "<td>".round($provision[$cost]-round($inv_master[$cost]) + round($billing[$cost]) -  round($billing_proc[$cost]))."</td>";    
                            } 
                ?>
                <th><?php echo round($TotProv-$TotInv);?></th>
                <th><?php echo round($TotProv);?></th>

                <?php } ?> 
            </tr>
        
        <tr>
            <th>Revenue Reimbursement</th>
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($Reimbur_master_up[$cost]['1']=='1')
                {
                    echo "<td>".round($Reimbur_master[$cost])."</td>";
                    $TotReim += round($Reimbur_master[$cost]);
                    $TotReimUp += round($Reimbur_master_up[$cost]);
                    $NReimbursement[$cost]['un'] =round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]));
                    $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                }
                else
                {
                    echo "<td>".round($Reimbur_master[$cost])."</td>";
                    $TotReim += round($Reimbur_master[$cost]);
                    $TotReimUp += round($Reimbur_master[$cost]);
                    $NReimbursement[$cost]['un'] =round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]));
                    $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                }
                
            }
            ?>
            <th><?php echo round($TotReim);?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($Reimbur_master_up[$cost]['1']=='1')
                {
                    echo "<td>".round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]))."</td>";
                }
                else
                {
                    echo "<td>".round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]))."</td>";
                }
            }
            ?>
            <th><?php echo round($TotReimUp-$TotReim);?></th>
            <th><?php echo round($TotReimUp);?></th>
            
        <?php } ?> 
        </tr>
        
        <tr>
            
            <th>Claw Back/Deductiion</th>
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td></td>";
            }
            ?>
            <th><?php //echo $TotReim ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td></td>";
            }
            ?>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            
        <?php } ?> 
        </tr>
        
        <tr>
            <th>MPR Seat </th>
            
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($MPR_Seat[$cost])."</td>";
                $TotSeatProc += round($MPR_Seat[$cost]);
            }
            ?>
            <th><?php echo $TotSeatProc; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>0</td>";
            }
            ?>
            <th>0</th>
            <th><?php echo $TotSeatProc; ?></th>
            
            <?php } ?> 
        </tr>
        
        <tr>
            <th>Seat Rate</th>
            
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($MPR_Rate[$cost])."</td>";
                $TotRateProc += round($MPR_Rate[$cost]);
            }
            ?>
            <th><?php echo $TotRateProc; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>0</td>";
            }
            ?>
            <th>0</th>
            <th><?php echo $TotRateProc; ?></th>
            
            <?php } ?> 
        </tr>
        

        <tr>
            <th>Net Revenue</th>
            <?php $NetRevProc = 0; 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {                
                echo "<td>".round($inv_master[$cost]+ round($billing_proc[$cost])+$NReimbursement[$cost]['proc'])."</td>";
                $NetRev += ($provision[$cost]+round($billing[$cost])-$inv_master[$cost]-$billing_proc[$cost]+ $NReimbursement[$cost]['un']);
                $NetRevProc += round($inv_master[$cost] +  round($billing_proc[$cost])+$NReimbursement[$cost]['proc']);
                $NetRevArrProc[$cost] = round($inv_master[$cost]+ round($billing_proc[$cost])+$NReimbursement[$cost]['proc']);
                $TotalRevenue +=  round($inv_master[$cost] +  $billing_proc[$cost]+$NReimbursement[$cost]['proc']);
                $TotalRevenueProc +=  round($inv_master[$cost]+ $billing_proc[$cost]+$NReimbursement[$cost]['proc']);
            }
            ?>
            <th><?php echo round($NetRevProc); ?></th>
            <?php } //$NetRevProc = 0;
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($provision[$cost]+$billing[$cost]-$inv_master[$cost] - round($billing_proc[$cost])+ $NReimbursement[$cost]['un'])."</td>";
                $NetRevArrUnProc[$cost] += round($provision[$cost]+$billing[$cost]-$inv_master[$cost] - round($billing_proc[$cost])+ $NReimbursement[$cost]['un']);
            }
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
            
        <?php } ?> 
        </tr>
        
        <tr>
            <th>Salary</th>
        </tr>
        
        <tr>
            <th>Salary Net</th>
            <?php
            $cost_keys = array_keys($cost_master);
            $bo_cost = "0";
            $bo_cost1 = 0;
            $bo_cost_center = "";
            $TotNS = 0;
            foreach($sCost as $cost)
            {
                if(!empty($NetSalary[$cost]))
                {
                    $bo_cost = $NetSalary[$cost];
                    $bo_cost1 = 0;
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            //print_r($NetSalary); exit;
            
            foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $NetSalaryProc[$cost] = $NetSalary[$cost] +round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$NetSalaryProc[$cost]."</td>";
                    $TotNS += $NetSalaryProc[$cost];
                }
                
            }
            ?>
            <th><?php echo $TotNS; ?></th>
            <?php
            foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $NetSalaryUn[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$NetSalaryUn[$cost]."</td>";
                    $UnTotNS += $NetSalaryUn[$cost];
                }
                
            }
            ?>
            <th><?php echo $UnTotNS; ?></th>
            <th><?php echo ($TotNS + $UnTotNS); ?></th>
            
            </tr>
        <tr>
            <th>Incentive</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($Incentive[$cost]))
                {
                    $bo_cost = $Incentive[$cost];
                    $bo_cost1 = 0;
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $IncentiveProc[$cost] = $Incentive[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$IncentiveProc[$cost]."</td>";
                    $TotInc += $IncentiveProc[$cost];
                }
                
                
            }
            ?>
            <th><?php echo $TotInc; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $IncentiveUnProc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$IncentiveUnProc[$cost]."</td>";
                    $UnTotInc += $IncentiveUnProc[$cost];
                }
            }
            ?>
            <th><?php echo $UnTotInc; ?></th>
            <th><?php echo ($TotInc+$UnTotInc); ?></th>
            
        <?php } ?> 
        </tr>
        <tr>
            <th>Net Salary to be Paid</th>
            
            <?php
            
            
            
            
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo '<td>0</td>';
                }
                else
                {
                    echo "<td>".round($NetSalaryProc[$cost]+$IncentiveProc[$cost])."</td>";
                    $NetSalPaid += round($NetSalaryProc[$cost]+$IncentiveProc[$cost]);
                }
            }
            ?>
            <th><?php echo $NetSalPaid; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo '<td>0</td>';
                }
                else
                {
                    echo "<td>".round($NetSalaryUn[$cost]+$IncentiveUnProc[$cost])."</td>";
                    $UnNetSalPaid += round($NetSalaryUn[$cost]+$IncentiveUnProc[$cost]);
                }
            }
            ?>
            <th><?php echo $UnNetSalPaid; ?></th>
            <th><?php echo ($NetSalPaid+$UnNetSalPaid); ?></th>
            
            <?php } ?> 
        </tr>
           <tr>
            <th>EPF</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($EPF[$cost]))
                {
                    $bo_cost = $EPF[$cost];
                    $bo_cost1 = 0;
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $EPF_proc[$cost] = $EPF[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$EPF_proc[$cost]."</td>";
                    $TotEPF += round($EPF_proc[$cost]);
                }
                
            }
            ?>
            <th><?php echo $TotEPF; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $EPF_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$EPF_unproc[$cost]."</td>";
                    $TotEPF_unproc += round($EPF_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $TotEPF_unproc; ?></th>
            <th><?php echo ($TotEPF+$TotEPF_unproc); ?></th>
           
        <?php } ?> 
           </tr> 
            <tr>
            <th>ESIC</th>
            
            <?php
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($ESIC[$cost]))
                {
                    $bo_cost = $ESIC[$cost];
                    $bo_cost1 = 0;
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ESIC_proc[$cost] = $ESIC[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$ESIC_proc[$cost]."</td>";
                    $TotESIC += round($ESIC_proc[$cost]);
                }
                
            }
            ?>
            <th><?php echo $TotESIC; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ESIC_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$ESIC_unproc[$cost]."</td>";
                    $UnTotESIC += round($ESIC_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $UnTotESIC; ?></th>
            <th><?php echo ($TotESIC+$UnTotESIC); ?></th>
        <?php } ?> </tr> 
        <tr>
            <th>P.T</th>
            
            <?php
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            
            foreach($sCost as $cost)
            {
                if(!empty($PT[$cost]))
                {
                    $bo_cost = $PT[$cost];
                    $bo_cost_center_id = $cost;
                    $bo_cost1 = 0;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $PT_proc[$cost] = $PT[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$PT_proc[$cost]."</td>";
                    $TotPT += round($PT_proc[$cost]);
                }
                
                
            }
            ?>
            <th><?php echo $TotPT; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $PT_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$PT_unproc[$cost]."</td>";
                    $UnTotPT += round($PT_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $UnTotPT; ?></th>
            <th><?php echo ($TotPT+$UnTotPT); ?></th>
            
        <?php } ?> </tr>
        <tr>
            <th>TDS</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($TDS[$cost]))
                {
                    $bo_cost = $TDS[$cost];
                    $bo_cost_center_id = $cost;
                    $bo_cost1 = 0;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $TDS_proc[$cost] = $TDS[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$TDS_proc[$cost]."</td>";
                    $TotTDS += round($TDS_proc[$cost]);
                }
                
            }
            ?>
            <th><?php echo $TotTDS; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $TDS_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$TDS_unproc[$cost]."</td>";
                    $unTotTDS += round($TDS_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $unTotTDS; ?></th>
            <th><?php echo ($TotTDS+$unTotTDS); ?></th>
        <?php } ?> 
        </tr>
        <tr>
            <th>Short Collection</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($ShortColl[$cost]))
                {
                    $bo_cost = $ShortColl[$cost];
                    $bo_cost_center_id = $cost;
                    $bo_cost1 = 0;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ShortColl_proc[$cost] = $ShortColl[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$ShortColl_proc[$cost]."</td>";
                    $TotShortColl += round($ShortColl_proc[$cost]);
                }
                
            }
            ?>
            <th><?php echo $TotShortColl; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ShortColl_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$ShortColl_unproc[$cost]."</td>";
                    $TotShortColl_un += round($ShortColl_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $TotShortColl_un; ?></th>
            <th><?php echo ($TotShortColl+$TotShortColl_un); ?></th>
            
        <?php } ?> </tr>
        <tr>
            <th>Loan/Advance Recovered</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($Loan[$cost]))
                {
                    $bo_cost = $Loan[$cost];
                    $bo_cost_center_id = $cost;
                    $bo_cost1 = 0;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $Loan_proc[$cost] = $Loan[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$Loan_proc[$cost]."</td>";
                    $LoanTot += round($Loan_proc[$cost]);
                }
                
            }
            ?>
            <th><?php echo $LoanTot; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $Loan_unproc[$cost] =  round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$Loan_unproc[$cost]."</td>";
                    $unLoanTot += round($Loan_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $unLoanTot; ?></th>
            <th><?php echo ($LoanTot+$unLoanTot); ?></th>
        <?php } ?> 
        </tr>
        
        <tr>
            <th>Insurance Recovered</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = 0;
            foreach($sCost as $cost)
            {
                if(!empty($SHSH[$cost]))
                {
                    $bo_cost = $SHSH[$cost];
                    $bo_cost_center_id = $cost;
                    $bo_cost1 = 0;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $SHSH_proc[$cost] = $SHSH[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$SHSH_proc[$cost]."</td>";
                    $TotSHSH += round($SHSH_proc[$cost]);
                }
                
                
            }
            ?>
            <th><?php echo $TotSHSH; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $SHSH_unproc[$cost] = round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$SHSH_unproc[$cost]."</td>";
                    $unTotSHSH += round($SHSH_unproc[$cost]);
                }
            }
            ?>
            <th><?php echo $unTotSHSH; ?></th>
            <th><?php echo ($TotSHSH+$unTotSHSH); ?></th>
        <?php } ?> 
        </tr>
        <tr>
            <th>Salary  Outsourcing</th>
            
            <th>0</th>
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo "<td></td>";
                        //$TotActualCTC += $ActualCTC[$cost];
                    }
            ?>
            <th><?php //echo $TotActualCTC; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo "<td></td>";
                        //$TotActualCTC += $ActualCTC[$cost];
                    }
            ?>
            <th><?php //echo $TotActualCTC; ?></th>
            
        <?php } ?> </tr>
        <tr>
            <th>Actual CTC</th>
            
           <?php
           
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = $ActualCTC[$cost];
                    $bo_cost1 = $ActualCTCBusi[$cost]-$ActualCTC[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
           
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ActualCTC_proc[$cost] = $ActualCTC[$cost] + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$ActualCTC_proc[$cost]."</td>";
                    $TotActualCTC += round($ActualCTC_proc[$cost]);
                }                
            }
            ?>
            <th><?php echo round($TotActualCTC); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ActualCTCBusi_unproc[$cost] =  ($ActualCTCBusi[$cost]-$ActualCTC[$cost]) + round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    $unTotActualCTCBus +=$ActualCTCBusi_unproc[$cost];
                    echo "<td>".round($ActualCTCBusi_unproc[$cost])."</td>";
                }
            }
            ?>
            <th><?php echo round($unTotActualCTCBus); ?></th>
            <th><?php echo round($TotActualCTC + $unTotActualCTCBus); ?></th>
            
        <?php } ?> 
        </tr>
        <tr>
            <th>Salary Adjustment</th>
            
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]-$ActualCTC[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        if($cost==$bo_cost_center_id)
                        {
                            echo "<td>0</td>";
                        }
                        else
                        {
                                $AdjustTo_proc[$cost] = round($Adjust[$cost]-$Adjust2[$cost]+ ($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                                echo "<td>".($AdjustTo_proc[$cost])."</td>";
                                $TotAdjust += $AdjustTo_proc[$cost];
                        }        
                    }
            ?>
            
            <th><?php echo $TotAdjust; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo "<td>0</td>";
                        
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotAdjust; ?></th>
            
        <?php } ?> 
        </tr>
            <tr>
            <th>Software Support Cost</th>
            
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td></td>";
            }
            ?>
            <th><?php //echo $TotReim ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td></td>";
            }
            ?>
            <th>0</th>
            <th><?php //echo $TotReim ?></th>
            
        <?php } ?> 
            </tr>
        <tr>
            <th>Actual CTC After Adjustment</th>
            
            <?php $TotCTC = 0; $TotActualCTCBus=0;
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]-$ActualCTC[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ActualCTCafterAdjustment_proc[$cost] = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]) + round(($bo_cost/$TotalRevenue)*$NetRevArrProc[$cost]);
                    echo "<td>".$ActualCTCafterAdjustment_proc[$cost]."</td>";
                    $TotActualCTC_adjust += round($ActualCTCafterAdjustment_proc[$cost]);
                } 
            }
            ?>
            <th><?php echo $TotActualCTC_adjust; ?></th>
            
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($cost==$bo_cost_center_id)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    $ActualCTCafterAdjustment_unproc[$cost] = $ActualCTCBusi[$cost]-$ActualCTC[$cost] +round(($bo_cost1/$TotalRevenue)*$NetRevArrUnProc[$cost]);
                    echo "<td>".$ActualCTCafterAdjustment_unproc[$cost]."</td>";
                    $TotActualCTC_adjust_unproc += round($ActualCTCafterAdjustment_unproc[$cost]);
                } 
            }
            ?>
            <th><?php echo round($TotActualCTC_adjust_unproc); ?></th>
            <th><?php echo $TotActualCTC_adjust + $TotActualCTC_adjust_unproc; ?></th>
            
        <?php } ?> 
        </tr>
           <tr>
            <th>DC(%)</th>
            
           <?php $NetRev = 0; $TotCTC = 0; $NetRevProc = 0; $NetRevUnProc=0; $TotActualCTCBus=0;
           
           $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]-$ActualCTC[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
           
           
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($bo_cost_center_id==$cost)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    echo "<td>".round($ActualCTCafterAdjustment_proc[$cost]*100/($NetRevArrProc[$cost]))."%</td>";
                    $NetRevProc_dd +=$NetRevArrProc[$cost];
                    
                    $TotCTC_dd += $ActualCTCafterAdjustment_proc[$cost];
                     
                } 
            }
            ?>
            <th><?php echo round($TotCTC_dd*100/$NetRevProc_dd); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                if($bo_cost_center_id==$cost)
                {
                    echo "<td>0</td>";
                }
                else
                {
                    echo "<td>".round($ActualCTCafterAdjustment_unproc[$cost]*100/($NetRevArrUnProc[$cost]))."%</td>";
                    $NetRevUnProc_dd += $NetRevArrUnProc[$cost];
                    $TotActualCTCBus_dd +=$ActualCTCafterAdjustment_unproc[$cost];
                }
            }
            ?>
            <th><?php echo round(($TotActualCTCBus_dd)*100/$NetRevUnProc_dd); ?>%</th>
            <th><?php echo round(($TotActualCTCBus_dd+$TotCTC_dd)*100/($NetRevUnProc_dd+$NetRevProc_dd)); ?>%</th>
            <?php } ?> 
           </tr> 
           
        <tr></tr>
         <tr>
            <th>Direct Expense</th>
        </tr>
        
<?php
                //print_r($Direct); exit; 
$branchDirect = array(); sort($SubHeadDir);
                
foreach($orderD as $Subhead)
{
    $TotDirUnProc = 0; $TotDirProc=0;
    echo '<tr><th>'.$Subhead.'</th>';
    $TotDir = 0;
        if(!empty($cost_master))
        { 
            foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($Direct[$Subhead][$cost])."</td>";   
                $TotDirUnProc += round($UnDirect[$Subhead][$cost]);
                $TotDirProc += round($Direct[$Subhead][$cost]);

                $branchDirect[$cost]['unproc'] +=round($UnDirect[$Subhead][$cost]);
                $branchDirect[$cost]['proc'] +=round($Direct[$Subhead][$cost]);
            }
            echo '<th>'.$TotDirProc.'</th>';
        }    
        if(!empty($cost_master))
        { 
            foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($UnDirect[$Subhead][$cost]-$Direct[$Subhead][$cost])."</td>";
            }
        echo '<th>'.round($TotDirUnProc-$TotDirProc).'</th>';
        echo '<th>'.$TotDirUnProc.'</th>';
        } 
    echo '</tr>';
}
        ?>
        <tr></tr>
        <tr>
            <th>Total Direct Expense</th>
            <?php $DirBrUnProc = 0; $DirBrProc = 0; 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($branchDirect[$cost]['proc'])."</td>";
                $DirBrUnProc += round($branchDirect[$cost]['unproc']);
                $DirBrProc += round($branchDirect[$cost]['proc']);
            }
            ?>
            <th><?php echo round($DirBrProc); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])."</td>";
            }
            ?>
            <th><?php echo round($DirBrUnProc - $DirBrProc); ?></th>
            <th><?php echo round($DirBrUnProc); ?></th>
            
        <?php } ?> 
        </tr>
       
        <tr>
            <th>Total Direct Expense%</th>
            <?php $NetRev=0; $NetRevProc=0; $DirBrUnProcPer=0; $DirBrProcPer=0; 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round((($branchDirect[$cost]['proc'])/($NetRevArrProc[$cost]))*100)."%</td>";
                $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                $DirBrProcPer += round($branchDirect[$cost]['proc']);

                $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
            }
            ?>
            
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <?php  }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])/($NetRevArrUnProc[$cost]))*100)."%</td>";
            }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)*100/($NetRev)); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            
            <?php } ?>
        </tr>
        <tr></tr>
          <tr>
            <th>InDirect Expense</th>
        </tr>
        <tr>
            <th>Future Revenue Adjustment</th>
            
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($Futur_Revenue[$cost])."</td>";
                $TotFutProc += round($Futur_Revenue[$cost]);
            }
            ?>
            <th><?php echo $TotFutProc; ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                
                echo "<td>0</td>";
                
            }
            ?>
            <th>0</th>
            <th><?php echo $TotFutProc; ?></th>
            
            <?php } ?> 
        </tr>
        <?php
                //print_r($Direct); exit;
                    sort($SubHeadInDir);
                foreach($orderI as $Subhead)
                {
                    echo '<tr><th>'.$Subhead.'</th>';
                    $TotDir = 0; $TotInDirUnProc = 0; $TotInDirProc=0;
                        if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                        {
                            echo "<td>".round($InDirect[$Subhead][$cost])."</td>";
                            $TotInDirUnProc += round($UnInDirect[$Subhead][$cost]);
                            $TotInDirProc += round($InDirect[$Subhead][$cost]);
                            
                            $branchInDirect[$cost]['unproc'] +=round($UnInDirect[$Subhead][$cost]);
                            $branchInDirect[$cost]['proc'] +=round($InDirect[$Subhead][$cost]);
                        }
                        }
                        echo '<th>'.$TotInDirProc.'</th>';
                        if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                        {
                            echo "<td>".round($UnInDirect[$Subhead][$cost]-$InDirect[$Subhead][$cost])."</td>";
                        }
                        echo '<th>'.round($TotInDirUnProc-$TotInDirProc).'</th>';
                        echo '<th>'.$TotInDirUnProc.'</th>';
                        } 
                    echo '</tr>';
                }
        ?> 
        <tr></tr>
       <tr>
            <th>Total InDirect Expense</th>
            <?php
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo "<td>".round($branchInDirect[$cost]['proc'] + $Futur_Revenue[$cost])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc'] + $Futur_Revenue[$cost]);
                    }
            ?>
            <th><?php echo round($InDirBrProc); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
            }
            ?>
            <th><?php echo round($InDirBrUnProc); ?></th>
            
            <th><?php echo round($InDirBrUnProc+$InDirBrProc); ?></th>
            
        <?php } ?> </tr>
        
       <tr>
            <th>Total InDirect Expense%</th>
            <?php $NetRev =0 ; $NetRevProc = 0; $InDirBrUnProcPer = 0; $InDirBrProcPer = 0;
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
               
                echo "<td>".round((($branchInDirect[$cost]['proc']+$Futur_Revenue[$cost])/($NetRevArrProc[$cost]))*100)."%</td>";
                $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                $InDirBrProcPer += round($branchInDirect[$cost]['proc']+$Futur_Revenue[$cost]);

                $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
            }
            ?>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
               echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$Futur_Revenue[$cost])/($NetRevArrUnProc[$cost]))*100)."%</td>";
            }
            ?>
            <th><?php echo round((($InDirBrUnProc-$InDirBrProcPer)/ ($NetRev))*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
        <?php } ?> </tr>
        
        
        <tr></tr>
        <tr>
            <th>Total Cost</th>
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(((($ActualCTCafterAdjustment_proc[$cost]))+$branchInDirect[$cost]['proc']+$Futur_Revenue[$cost] +$branchDirect[$cost]['proc']))."</td>";
                    $TotCost_cc_proc[$cost] = round(((($ActualCTCafterAdjustment_proc[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]));
                    $TotCostProc += round(((($ActualCTCafterAdjustment_proc[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]));
                //}    
            }
            ?>
            <th><?php echo ($TotCostProc); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round((($ActualCTCafterAdjustment_unproc[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                    $TotCostUnProc += round((($ActualCTCafterAdjustment_unproc[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']));
                    $TotCost_cc_unproc[$cost] = round((($ActualCTCafterAdjustment_unproc[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']));
              //  }
            }
            ?>
            <th><?php echo ($TotCostUnProc); ?></th>
            <th><?php echo ($TotCostUnProc+$TotCostProc); ?></th>
        <?php } ?> 
        </tr>
        
        <tr>
            <th>Total Cost%</th>
            <?php $TotCostUnProc = 0;  $TotCostProc = 0; $TotNetProc = 0; $TotNetUnProc = 0;
            $NetRev = 0; $NetRevProc=0;
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]-$ActualCTC[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            $TotCostProc_dd = 0; $TotCostUnProc_dd = 0; $TotCostProc_dd_cc = array();
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                
                    echo "<td>".round(((($ActualCTCafterAdjustment_proc[$cost]))+$branchInDirect[$cost]['proc']+$Futur_Revenue[$cost] +$branchDirect[$cost]['proc'])*100/($NetRevArrProc[$cost]))."%</td>";
                    $TotCostProc_dd += round(((($ActualCTCafterAdjustment_proc[$cost]))+$branchInDirect[$cost]['proc']+$Futur_Revenue[$cost] +$branchDirect[$cost]['proc']));
                   
                    $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
                    
                    
               // }
            }
            ?>
            <th><?php echo round($TotCostProc_dd*100/($NetRevProc)); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round((($ActualCTCafterAdjustment_unproc[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc'])*100/($NetRevArrUnProc[$cost]))."%</td>";
                    $TotCostUnProc += round((($ActualCTCafterAdjustment_unproc[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc'])*100/($NetRevArrUnProc[$cost]));
                    
               // }
            }
            ?>
            <th><?php echo round(($TotCostUnProc)*100/($NetRev)); ?>%</th>
            <th><?php echo round(($TotCostUnProc+$TotCostProc_dd)*100/($NetRev+$NetRevProc));  ?>%</th>
            
        <?php } ?> 
        </tr>
        
       <tr></tr>
        <tr>
            <th>EBIDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost]))."</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost]));
               // }
            }
            }
            ?>
            <th><?php echo round($TotCostProc); ?></th>
            <?php 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost]))."</td>";
                    $TotCostUNProc += round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost]));
                //}
            }
            ?>
            <th><?php echo round((($TotCostUNProc))); ?></th>
            <th><?php echo round($TotCostUNProc+$TotCostProc); ?></th>
        <?php } ?> 
        </tr>
        <tr>
            <th>EBIDTA%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0; $NetRev = 0; $NetRevProc=0; $TotCostUNProc = 0;
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round(($NetRevArrProc[$cost]-$TotCost_cc_proc[$cost])*100/($NetRevArrProc[$cost]))."%</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost]));
                    $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
            }
            ?>
            <th><?php echo round($TotCostProc*100/$NetRevProc); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
                echo "<td>".round(($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost])*100/$NetRevArrUnProc[$cost])."%</td>";
                    $TotCostUNProc += round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost]));
            }
            ?>
            <th><?php echo round((($TotCostUNProc)*100/$NetRev)); ?>%</th>
            <th><?php echo round(($TotCostUNProc+$TotCostProc)*100/($NetRev+$NetRevProc)); ?>%</th>
        <?php } ?> 
        </tr>
        
        <tr></tr>
        
        <tr></tr>
        
        <?php
        
                
                foreach($PnlBranchHead as $head)
                {
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                        
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td>'.($PnlDataBranch[$head][$cost]['unproc']-$PnlDataBranch[$head][$cost]['proc']).'</td>';
                        
                        $pnlProc += $PnlDataBranch[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataBranch[$head][$cost]['unproc'];
                    }
                    }
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td>'.$PnlDataBranch[$head][$cost]['proc'].'</td>';
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    
                    echo '<th>'.$pnlUnProc.'</th>';
                    
                    } 
                    echo '</tr>';
                    echo '<tr></tr>';
                }
                
                $pnlProc = 0; $pnlUnProc= 0;
                foreach($PnlProcessHead as $head)
                {
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                        
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td>'.($PnlDataProcess[$head][$cost]['unproc']-$PnlDataProcess[$head][$cost]['proc']).'</td>';
                        $pnlProc += $PnlDataProcess[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataProcess[$head][$cost]['unproc'];
                    }
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    }
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td>'.$PnlDataProcess[$head][$cost]['proc'].'</td>';
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.$pnlUnProc.'</th>';
                    } 
                    echo '</tr>';
                    
                }
                
        ?>
        
        <tr>
            <th>OutStanding</th>
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round($outstand_proc_data[$cost])."</td>";
                    $ProcFinanceCost += round($outstand_proc_data[$cost]);
                //}    
            }
            ?>
            <th><?php echo ($ProcFinanceCost); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round($outstand_unproc_data[$cost])."</td>";
                    $UNProcFinanceCost = round($outstand_unproc_data[$cost]);
                //}
            }
            ?>
            <th><?php echo ($UNProcFinanceCost); ?></th>
            <th><?php echo ($ProcFinanceCost+$UNProcFinanceCost); ?></th>
            <?php } ?> 
        </tr>
        
        <tr>
            <th>Interest</th>
            <?php
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round($outstand_proc_data[$cost]*0.04)."</td>";
                    $ProcFinanceCost_int += round($outstand_proc_data[$cost]*0.04);
                //}    
            }
            ?>
            <th><?php echo ($ProcFinanceCost_int); ?></th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round($outstand_unproc_data[$cost]*0.04)."</td>";
                    $UNProcFinanceCost_int += round($outstand_unproc_data[$cost]*0.04);
                //}
            }
            ?>
            <th><?php echo ($UNProcFinanceCost_int); ?></th>
            <th><?php echo ($ProcFinanceCost_int+$UNProcFinanceCost_int); ?></th>
            <?php } ?> 
        </tr>
        
<!--        <tr>
            <th>Finance Expense</th>
            <?php
//            $ProcFinanceCost = 0;
//            $UNProcFinanceCost = 0;
//            $bo_cost = "0";
//            $bo_cost_center_id = "";
//            $bo_cost1 = "0";
//            foreach($sCost as $cost)
//            {
//                if(!empty($ActualCTC[$cost]))
//                {
//                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
//                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
//                    $bo_cost_center_id = $cost;
//                    break;
//                }
//            }
//            
//            
//            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
//            {
////                if($bo_cost_center_id==$cost)
////                {
////                    echo "<td>0</td>";
////                }
////                else
////                {
//                    echo "<td>".round($FinanceProc[$cost])."</td>";
//                    $ProcFinanceCost += round($FinanceProc[$cost]);
//                //}    
//            }
            ?>
            <th><?php //echo ($ProcFinanceCost); ?></th>
            <?php //}
//            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
//            {
////                if($bo_cost_center_id==$cost)
////                {
////                    echo "<td>0</td>";
////                }
////                else
////                {
//                    echo "<td>".round($FinanceUN[$cost]-$FinanceProc[$cost])."</td>";
//                    $UNProcFinanceCost = round($FinanceUN[$cost]);
//                //}
//            }
            ?>
            <th><?php //echo ($UNProcFinanceCost); ?></th>
            <th><?php //echo ($ProcFinanceCost+$ProcFinanceCost); ?></th>
            <?php// } ?> 
        </tr>-->
        
        
        <tr></tr>
        
        <tr>
            <th>EBIDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotCostUNProc = 0;
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04))."</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04));
                //}
            }
            }
            ?>
            <th><?php echo round($TotCostProc); ?></th>
            <?php 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost])-round($outstand_unproc_data[$cost]*0.04))."</td>";
                    $TotCostUNProc += round($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost]-round($outstand_unproc_data[$cost]*0.04));
                    
                //}
            }
            ?>
            <th><?php echo ($TotCostUNProc);  ?></th> 
            <th><?php echo ($TotCostUNProc+$TotCostProc-$tot_int); ?></th>
        <?php } ?> 
        </tr>
        <tr>
            <th>EBIDTA%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0; $NetRev = 0; $NetRevProc=0; $TotCostUNProc = 0;
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                echo "<td>".round(($NetRevArrProc[$cost]-$TotCost_cc_proc[$cost]-round($outstand_proc_data[$cost]*0.04))*100/($NetRevArrProc[$cost]))."%</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04));
                    $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
                //}
            }
            ?>
            <th><?php echo round($TotCostProc*100/$NetRevProc); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost]-round($outstand_proc_data[$cost]*0.04))*100/$NetRevArrUnProc[$cost])."%</td>";
                    $TotCostUNProc += round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost])-round($outstand_unproc_data[$cost]*0.04));
                //}
            }
            ?>
            <th><?php echo round((($TotCostUNProc)*100/$NetRev)); ?>%</th>
            <th><?php echo round(($TotCostUNProc+$TotCostProc)*100/($NetRev+$NetRevProc)); ?>%</th>
        <?php } ?> 
        </tr>
        
        <tr></tr>
        
        <tr>
                <th>Capex</th>
                <?php
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td>'.round($capex[$cost]).'</td>';
                        $tot_capex +=round($capex[$cost]);
                    }
                    echo '<td>'.$tot_capex.'</td>';
                    }
                    if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
                    {
                        echo '<td></td>';
                    }
                        echo '<td></td>';
                        echo '<th>'.$tot_capex.'</th>';
                ?>
                
        <?php } ?> 
        </tr>
        
        <tr></tr>
        
        <tr>
            <th>Net Profit Excluding Capex</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotCostUNProc = 0;
            
            $bo_cost = "0";
            $bo_cost_center_id = "";
            $bo_cost1 = "0";
            foreach($sCost as $cost)
            {
                if(!empty($ActualCTC[$cost]))
                {
                    $bo_cost = round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                    $bo_cost1 = $ActualCTCBusi[$cost]+$Adjust_un[$cost]-$Adjust2_un[$cost];
                    $bo_cost_center_id = $cost;
                    break;
                }
            }
            
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04)+$capex[$cost])."</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04)+$capex[$cost]);
                //}
            }
            }
            ?>
            <th><?php echo round($TotCostProc); ?></th>
            <?php 
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrUnProc[$cost])-($TotCost_cc_unproc[$cost])-round($outstand_unproc_data[$cost]*0.04))."</td>";
                    $TotCostUNProc += round($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost]-round($outstand_unproc_data[$cost]*0.04));
                    
                //}
            }
            ?>
            <th><?php echo ($TotCostUNProc);  ?></th> 
            <th><?php echo ($TotCostUNProc+$TotCostProc-$tot_int); ?></th>
        <?php } ?> 
        </tr>
        <tr>
            <th>Net Profit Excluding Capex%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0; $NetRev = 0; $NetRevProc=0; $TotCostUNProc = 0;
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                echo "<td>".round(($NetRevArrProc[$cost]-$TotCost_cc_proc[$cost]-round($outstand_proc_data[$cost]*0.04)+$capex[$cost])*100/($NetRevArrProc[$cost]))."%</td>";
                    $TotCostProc += round(($NetRevArrProc[$cost])-($TotCost_cc_proc[$cost])-round($outstand_proc_data[$cost]*0.04)+$capex[$cost]);
                    $NetRev += $NetRevArrUnProc[$cost];
                $NetRevProc += $NetRevArrProc[$cost];
                //}
            }
            ?>
            <th><?php echo round($TotCostProc*100/$NetRevProc); ?>%</th>
            <?php }
            if(!empty($cost_master)) { foreach($cost_master as $cost=>$cost_value)
            {
//                if($bo_cost_center_id==$cost)
//                {
//                    echo "<td>0</td>";
//                }
//                else
//                {
                    echo "<td>".round(($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost]-round($outstand_proc_data[$cost]*0.04))*100/$NetRevArrUnProc[$cost])."%</td>";
                    $TotCostUNProc += round(($NetRevArrUnProc[$cost]-$TotCost_cc_unproc[$cost]-round($outstand_proc_data[$cost]*0.04))*100/$NetRevArrUnProc[$cost]);
               // }
            }
            ?>
            <th><?php echo round((($TotCostUNProc)*100/$NetRev)); ?>%</th>
            <th><?php echo round(($TotCostUNProc+$TotCostProc)*100/($NetRev+$NetRevProc)); ?>%</th>
        <?php } ?> 
        </tr>
</table>    
<?php

        $fileName = "PNL_process_Wise_Report".'_'.$month_report;
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>            
<?php exit; ?>		

		
					
		
           

