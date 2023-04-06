<?php

$cntLoop = "3";

?>
<table border="1">
    <thead>
        
        <tr><th>Revenue</th></tr>
        <tr>
            <th>Branch</th>
            <?php $NewBranch_master = array();
                if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                {
                    echo "<td colspan='3'>".$cost."</td>";
                    $NewBranch_master[] = strtoupper($cost);
                }
                
                $branch_master1 = $NewBranch_master;
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
            <?php $NewBranch_master = array();
                } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                {
                    echo "<td colspan='3'>".$cost."</td>";
                    $NewBranch_master[] = strtoupper($cost);
                }
                
                $branch_master2 = $NewBranch_master;
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
            
            <?php $NewBranch_master = array();
                } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                {
                    echo "<td colspan='3'>".$cost."</td>";
                    $NewBranch_master[] = strtoupper($cost);
                }
                
                $branch_master3 = $NewBranch_master
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
            
        <?php } ?> </tr>
        
        
        
        
        <tr>
            <th></th>
            <?php 
                if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                {
                    echo "<td>UnProcessed</td>";
                    echo "<td>Processed</td>";
                    echo "<td>Total</td>";
                }
                
                
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
            <?php 
                } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                {
                    echo "<td>UnProcessed</td>";
                    echo "<td>Processed</td>";
                    echo "<td>Total</td>";
                }
                
                
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
            
            <?php 
                } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                {
                    echo "<td>UnProcessed</td>";
                    echo "<td>Processed</td>";
                    echo "<td>Total</td>";
                }
                
                
                
            ?>
            <th>Total UnProcessed</th>
            <th>Total Processed</th>
            <th>Gr. Total</th>
        <?php } ?> </tr>

        <tr>
            <th>Billing</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotBillProc = 0; $TotBillUnProc=0; //print_r($provision); exit; exit; $TotInv
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($billingUnProcMaster[$cost]-round($billingProcMaster[$cost]))."</td>";
                        echo "<td>".round($billingProcMaster[$cost])."</td>";
                        echo "<td>".round($billingUnProcMaster[$cost])."</td>";
                        
                        $TotBillProc += round($billingProcMaster[$cost]);
                        $TotBillUnProc += round($billingUnProcMaster[$cost]);
                    } 
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-round($inv_master[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost])."</td>";
                        echo "<td>".round($provision[$cost])."</td>";
                        $TotBillProc += round($billingProcMaster[$cost]);
                        $TotBillUnProc += round($billingUnProcMaster[$cost]);
                    }
            ?>
           <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-round($inv_master[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost])."</td>";
                        echo "<td>".round($provision[$cost])."</td>";
                        $TotBillProc += round($billingProcMaster[$cost]);
                        $TotBillUnProc += round($billingUnProcMaster[$cost]);
                    }
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Gross Revenue</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotProv = 0; //print_r($provision); exit; exit;
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-round($inv_master[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost])."</td>";
                        echo "<td>".round($provision[$cost])."</td>";
                        
                        $TotInv += round($inv_master[$cost]);
                        $TotProv += round($provision[$cost]);
                    } 
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-round($inv_master[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost])."</td>";
                        echo "<td>".round($provision[$cost])."</td>";
                        $TotInv += round($inv_master[$cost]);
                        $TotProv += round($provision[$cost]);
                    }
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-round($inv_master[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost])."</td>";
                        echo "<td>".round($provision[$cost])."</td>";
                        $TotInv += round($inv_master[$cost]);
                        $TotProv += round($provision[$cost]);
                    }
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Revenue Reimbursement</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                
                if($Reimbur_master_up[$cost]['1']=='1')
                {
                    echo "<td>".round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]))."</td>";
                    echo "<td>".round($Reimbur_master[$cost])."</td>";
                    echo "<td>".round($Reimbur_master_up[$cost])."</td>";
                    $TotReim += round($Reimbur_master[$cost]);
                    $TotReimUp += round($Reimbur_master_up[$cost]);
                    $NReimbursement[$cost]['un'] =round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]));
                    $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                }
                else
                {
                    echo "<td>".round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]))."</td>";
                    echo "<td>".round($Reimbur_master[$cost])."</td>";
                    echo "<td>".round($Reimbur_master[$cost])."</td>";
                    $TotReim += round($Reimbur_master[$cost]);
                    $TotReimUp += round($Reimbur_master[$cost]);
                    $NReimbursement[$cost]['un'] =round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]));
                    $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                }
                
            }
            ?>
            <th><?php echo round($TotReimUp-$TotReim);?></th>
            <th><?php echo round($TotReim);?></th>
            <th><?php echo round($TotReimUp);?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        if($Reimbur_master_up[$cost]['0']=='1')
                        {
                            echo "<td>".round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]))."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            echo "<td>".round($Reimbur_master_up[$cost])."</td>";
                            $TotReim += round($Reimbur_master[$cost]);
                            $TotReimUp += round($Reimbur_master_up[$cost]);
                            $NReimbursement[$cost]['un'] =round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]));
                            $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                        }
                        else
                        {
                            echo "<td>".round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]))."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            $TotReim += round($Reimbur_master[$cost]);
                            $TotReimUp += round($Reimbur_master[$cost]);
                            $NReimbursement[$cost]['un'] =round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]));
                            $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                        }
                    }
            ?>
            <th><?php echo round($TotReimUp-$TotReim);?></th>
            <th><?php echo round($TotReim);?></th>
            <th><?php echo round($TotReimUp);?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        if($Reimbur_master_up[$cost]['0']=='1')
                        {
                            echo "<td>".round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]))."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            echo "<td>".round($Reimbur_master_up[$cost])."</td>";
                            $TotReim += round($Reimbur_master[$cost]);
                            $TotReimUp += round($Reimbur_master_up[$cost]);
                            $NReimbursement[$cost]['un'] =round(round($Reimbur_master_up[$cost])-round($Reimbur_master[$cost]));
                            $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                        }
                        else
                        {
                            echo "<td>".round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]))."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            echo "<td>".round($Reimbur_master[$cost])."</td>";
                            $TotReim += round($Reimbur_master[$cost]);
                            $TotReimUp += round($Reimbur_master[$cost]);
                            $NReimbursement[$cost]['un'] =round(round($Reimbur_master[$cost])-round($Reimbur_master[$cost]));
                            $NReimbursement[$cost]['proc'] =round($Reimbur_master[$cost]);
                        }
                    }
            ?>
            <th><?php echo round($TotReimUp-$TotReim);?></th>
            <th><?php echo round($TotReim);?></th>
            <th><?php echo round($TotReimUp);?></th>
        <?php } ?> </tr>
        
        <tr>
            
            <th>Claw Back/Deductiion</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
        <?php } ?> </tr>
        <tr>
            
            <th>Net Revenue</th>
            <?php $NetRevProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])."</td>";
                echo "<td>".round($inv_master[$cost]-$NReimbursement[$cost]['proc'])."</td>";
                echo '<td>'.round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+round($inv_master[$cost]-$NReimbursement[$cost]['proc'])).'</td>';

                $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
            }
            
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRevProc); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo "<td>".round($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])."</td>";
                echo "<td>".round($inv_master[$cost]-$NReimbursement[$cost]['proc'])."</td>";
                echo '<td>'.round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+round($inv_master[$cost]-$NReimbursement[$cost]['proc'])).'</td>';

                $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRevProc); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])."</td>";
                echo "<td>".round($inv_master[$cost]-$NReimbursement[$cost]['proc'])."</td>";
                echo '<td>'.round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+round($inv_master[$cost]-$NReimbursement[$cost]['proc'])).'</td>';

                $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRevProc); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
        <?php } ?> </tr>
        <tr>
            
            <th>Salary</th>
        </tr>
        <tr>
            <th>Salary Net</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($NetSalary[$cost])."</td>";
                echo "<td>".round($NetSalary[$cost])."</td>";
                $TotNS += round($NetSalary[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotNS; ?></th>
            <th><?php echo $TotNS; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($NetSalary[$cost])."</td>";
                echo "<td>".round($NetSalary[$cost])."</td>";
                $TotNS += round($NetSalary[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotNS; ?></th>
            <th><?php echo $TotNS; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($NetSalary[$cost])."</td>";
                        echo "<td>".round($NetSalary[$cost])."</td>";
                        $TotNS += round($NetSalary[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotNS; ?></th>
            <th><?php echo $TotNS; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Incentive</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                $TotInc += round($Incentive[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotInc; ?></th>
            <th><?php echo $TotInc; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                $TotInc += round($Incentive[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotInc ?></th>
            <th><?php echo $TotInc ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                echo "<td>".round($Incentive[$cost])."</td>";
                $TotInc += round($Incentive[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotInc ?></th>
            <th><?php echo $TotInc ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Net Salary to be Paid</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        $NetSalPaid += round($NetSalary[$cost]+$Incentive[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $NetSalPaid; ?></th>
            <th><?php echo $NetSalPaid; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        $NetSalPaid += round($NetSalary[$cost]+$Incentive[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $NetSalPaid; ?></th>
            <th><?php echo $NetSalPaid; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        echo "<td>".round($NetSalary[$cost]+$Incentive[$cost])."</td>";
                        $NetSalPaid += round($NetSalary[$cost]+$Incentive[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $NetSalPaid; ?></th>
            <th><?php echo $NetSalPaid; ?></th>
        <?php } ?> </tr>
           <tr>
            <th>EPF</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        $TotEPF += round($EPF[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotEPF; ?></th>
            <th><?php echo $TotEPF; ?></th>
           <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        $TotEPF += round($EPF[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotEPF; ?></th>
            <th><?php echo $TotEPF; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        echo "<td>".round($EPF[$cost])."</td>";
                        $TotEPF += round($EPF[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotEPF; ?></th>
            <th><?php echo $TotEPF; ?></th>
        <?php } ?> </tr> 
            <tr>
            <th>ESIC</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        $TotESIC += round($ESIC[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotESIC; ?></th>
            <th><?php echo $TotESIC; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        $TotESIC += round($ESIC[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotESIC; ?></th>
            <th><?php echo $TotESIC; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        echo "<td>".round($ESIC[$cost])."</td>";
                        $TotESIC += round($ESIC[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotESIC; ?></th>
            <th><?php echo $TotESIC; ?></th>
        <?php } ?> </tr> 
        <tr>
            <th>P.T</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($PT[$cost])."</td>";
                         echo "<td>".round($PT[$cost])."</td>";
                        $TotPT += round($PT[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotPT; ?></th>
            <th><?php echo $TotPT; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($PT[$cost])."</td>";
                         echo "<td>".round($PT[$cost])."</td>";
                        $TotPT += round($PT[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotPT; ?></th>
            <th><?php echo $TotPT; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                
                        echo "<td>0</td>";
                        echo "<td>".round($PT[$cost])."</td>";
                         echo "<td>".round($PT[$cost])."</td>";
                        $TotPT += round($PT[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotPT; ?></th>
            <th><?php echo $TotPT; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>TDS</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($TDS[$cost])."</td>";
                 echo "<td>".round($TDS[$cost])."</td>";
                $TotTDS += round($TDS[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotTDS; ?></th>
            <th><?php echo $TotTDS; ?></th>
            
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($TDS[$cost])."</td>";
                 echo "<td>".round($TDS[$cost])."</td>";
                $TotTDS += round($TDS[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotTDS; ?></th>
            <th><?php echo $TotTDS; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>0</td>";
                echo "<td>".round($TDS[$cost])."</td>";
                 echo "<td>".round($TDS[$cost])."</td>";
                $TotTDS += round($TDS[$cost]);
            }
            ?>
            <th>0</th>
            <th><?php echo $TotTDS; ?></th>
            <th><?php echo $TotTDS; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Short Collection</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        $TotShortColl += round($ShortColl[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotShortColl; ?></th>
            <th><?php echo $TotShortColl; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        $TotShortColl += round($ShortColl[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotShortColl; ?></th>
            <th><?php echo $TotShortColl; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        echo "<td>".round($ShortColl[$cost])."</td>";
                        $TotShortColl += round($ShortColl[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotShortColl; ?></th>
            <th><?php echo $TotShortColl; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Loan/Advance Recovered</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Loan[$cost])."</td>";
                        echo "<td>".round($Loan[$cost])."</td>";
                        $LoanTot += round($Loan[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $LoanTot; ?></th>
            <th><?php echo $LoanTot; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Loan[$cost])."</td>";
                         echo "<td>".round($Loan[$cost])."</td>";
                        $LoanTot += round($Loan[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $LoanTot; ?></th>
            <th><?php echo $LoanTot; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Loan[$cost])."</td>";
                         echo "<td>".round($Loan[$cost])."</td>";
                        $LoanTot += round($Loan[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $LoanTot; ?></th>
            <th><?php echo $LoanTot; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Insurance Recovered</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        $TotSHSH += round($SHSH[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotSHSH; ?></th>
            <th><?php echo $TotSHSH; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        $TotSHSH += round($SHSH[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotSHSH; ?></th>
            <th><?php echo $TotSHSH; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        echo "<td>".round($SHSH[$cost])."</td>";
                        $TotSHSH += round($SHSH[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotSHSH; ?></th>
            <th><?php echo $TotSHSH; ?></th>
            
        <?php } ?> </tr>
        <tr>
            <th>Salary  Outsourcing</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotActualCTC += $ActualCTC[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotActualCTC; ?></th>
            <th><?php //echo $TotActualCTC; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotActualCTC += $ActualCTC[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotActualCTC; ?></th>
            <th><?php //echo $TotActualCTC; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotActualCTC += $ActualCTC[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotActualCTC; ?></th>
            <th><?php //echo $TotActualCTC; ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Actual CTC</th>
            
           <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost])."</td>";
                        $TotActualCTC += round($ActualCTC[$cost]);
                        $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
            <th><?php echo round($TotActualCTCBus-$TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTCBus); ?></th>
            <?php 
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                         echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost])."</td>";
                        $TotActualCTC += round($ActualCTC[$cost]);
                        $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
             <th><?php echo round($TotActualCTCBus-$TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTCBus); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                         echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost])."</td>";
                        $TotActualCTC += round($ActualCTC[$cost]);
                        $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
             <th><?php echo round($TotActualCTCBus-$TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTC); ?></th>
            <th><?php echo round($TotActualCTCBus); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Salary Adjustment</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotAdjust += round($Adjust[$cost]-$Adjust2[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotAdjust; ?></th>
            <th><?php echo $TotAdjust; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotAdjust += round($Adjust[$cost]-$Adjust2[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotAdjust; ?></th>
            <th><?php echo $TotAdjust; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotAdjust += round($Adjust[$cost]-$Adjust2[$cost]);
                    }
            ?>
            <th>0</th>
            <th><?php echo $TotAdjust; ?></th>
            <th><?php echo $TotAdjust; ?></th>
        <?php } ?> </tr>
            <tr>
            <th>Software Support Cost</th>
            
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        //$TotReim += $Reimbur_master[$cost];
                    }
            ?>
            <th>0</th>
            <th><?php //echo $TotReim ?></th>
            <th><?php //echo $TotReim ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Actual CTC After Adjustment</th>
            
            <?php $TotCTC = 0; $TotActualCTCBus=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotCTC += round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                         $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
            <th><?php echo round($TotActualCTCBus-$TotCTC); ?></th>
            <th><?php echo $TotCTC; ?></th>
            <th><?php echo $TotActualCTCBus; ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotCTC += round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                         $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
            <th><?php echo round($TotActualCTCBus-$TotCTC); ?></th>
            <th><?php echo $TotCTC; ?></th>
            <th><?php echo $TotActualCTCBus; ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($ActualCTCBusi[$cost]-$ActualCTC[$cost])."</td>";
                        echo "<td>".round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        echo "<td>".round($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])."</td>";
                        $TotCTC += round($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                         $TotActualCTCBus +=$ActualCTCBusi[$cost];
                    }
            ?>
            <th><?php echo round($TotActualCTCBus-$TotCTC); ?></th>
            <th><?php echo $TotCTC; ?></th>
            <th><?php echo $TotActualCTCBus; ?></th>
        <?php } ?> </tr>
           <tr>
            <th>DC(%)</th>
            
           <?php $NetRev = 0; $TotCTC = 0; $NetRevProc = 0; $NetRevUnProc=0; $TotActualCTCBus=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($provision[$cost]-$inv_master[$cost]-$NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($inv_master[$cost]-$NReimbur_master[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($provision[$cost]-$NReimbur_master[$cost]['proc']))."%</td>";
                $NetRevProc +=round(($inv_master[$cost]-$NReimbur_master[$cost]['proc']));
                $NetRevUnProc +=round(($provision[$cost]-$NReimbursement[$cost]['un']));
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/$NetRevUnProc); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
               echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($provision[$cost]-$inv_master[$cost]-$NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($inv_master[$cost]-$NReimbur_master[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($provision[$cost]-$NReimbur_master[$cost]['proc']))."%</td>";
                $NetRevProc +=round(($inv_master[$cost]-$NReimbur_master[$cost]['proc']));
                $NetRevUnProc +=round(($provision[$cost]-$NReimbursement[$cost]['un']));
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/$NetRevUnProc); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($provision[$cost]-$inv_master[$cost]-$NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($inv_master[$cost]-$NReimbur_master[$cost]['un']))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($provision[$cost]-$NReimbur_master[$cost]['proc']))."%</td>";
                $NetRevProc +=round(($inv_master[$cost]-$NReimbur_master[$cost]['proc']));
                $NetRevUnProc +=round(($provision[$cost]-$NReimbursement[$cost]['un']));
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/$NetRevUnProc); ?>%</th>
        <?php } ?> </tr> 
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
                        if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                        {
                            echo "<td>".round($UnDirect[$Subhead][$cost]-$Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnDirect[$Subhead][$cost])."</td>";
                            $TotDirUnProc += round($UnDirect[$Subhead][$cost]);
                            $TotDirProc += round($Direct[$Subhead][$cost]);
                            
                            $branchDirect[$cost]['unproc'] +=round($UnDirect[$Subhead][$cost]);
                            $branchDirect[$cost]['proc'] +=round($Direct[$Subhead][$cost]);
                        }
                        echo '<th>'.round($TotDirUnProc-$TotDirProc).'</th>';
                        echo '<th>'.$TotDirProc.'</th>';
                        echo '<th>'.$TotDirUnProc.'</th>';
                        } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                        {
                            echo "<td>".round($UnDirect[$Subhead][$cost]-$Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnDirect[$Subhead][$cost])."</td>";
                            $TotDirUnProc += round($UnDirect[$Subhead][$cost]);
                            $TotDirProc += round($Direct[$Subhead][$cost]);
                            
                            $branchDirect[$cost]['unproc'] +=round($UnDirect[$Subhead][$cost]);
                            $branchDirect[$cost]['proc'] +=round($Direct[$Subhead][$cost]);
                        }
                        echo '<th>'.round($TotDirUnProc-$TotDirProc).'</th>';
                        echo '<th>'.$TotDirProc.'</th>';
                        echo '<th>'.$TotDirUnProc.'</th>';
                        } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                        {
                            echo "<td>".round($UnDirect[$Subhead][$cost]-$Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($Direct[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnDirect[$Subhead][$cost])."</td>";
                            $TotDirUnProc += round($UnDirect[$Subhead][$cost]);
                            $TotDirProc += round($Direct[$Subhead][$cost]);
                            
                            $branchDirect[$cost]['unproc'] +=round($UnDirect[$Subhead][$cost]);
                            $branchDirect[$cost]['proc'] +=round($Direct[$Subhead][$cost]);
                        }
                       echo '<th>'.round($TotDirUnProc-$TotDirProc).'</th>';
                        echo '<th>'.$TotDirProc.'</th>';
                        echo '<th>'.$TotDirUnProc.'</th>';
                        }
                    echo '</tr>';
                        
                }
        ?>
        <tr></tr>
        <tr>
            <th>Total Direct Expense</th>
            <?php $DirBrUnProc = 0; $DirBrProc = 0; 
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['unproc'])."</td>";
                        $DirBrUnProc += round($branchDirect[$cost]['unproc']);
                        $DirBrProc += round($branchDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($DirBrUnProc - $DirBrProc); ?></th>
            <th><?php echo round($DirBrProc); ?></th>
            <th><?php echo round($DirBrUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['unproc'])."</td>";
                        $DirBrUnProc += round($branchDirect[$cost]['unproc']);
                        $DirBrProc += round($branchDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($DirBrUnProc - $DirBrProc); ?></th>
            <th><?php echo round($DirBrProc); ?></th>
            <th><?php echo round($DirBrUnProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchDirect[$cost]['unproc'])."</td>";
                        $DirBrUnProc += round($branchDirect[$cost]['unproc']);
                        $DirBrProc += round($branchDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($DirBrUnProc - $DirBrProc); ?></th>
            <th><?php echo round($DirBrProc); ?></th>
            <th><?php echo round($DirBrUnProc); ?></th>
        <?php } ?> </tr>
       
        <tr>
            <th>Total Direct Expense%</th>
            <?php $NetRev=0; $NetRevProc=0; $DirBrUnProcPer=0; $DirBrProcPer=0; 
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)/($NetRev-$NetRevProc)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/$NetRev)*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)/($NetRev-$NetRevProc)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/$NetRev)*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)/($NetRev-$NetRevProc)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/$NetRev)*100); ?>%</th>
            <?php } ?>
        </tr>
        <tr></tr>
          <tr>
            <th>InDirect Expense</th>
        </tr>
        
        <?php
                //print_r($Direct); exit;
                    sort($SubHeadInDir);
                foreach($orderI as $Subhead)
                {
                    echo '<tr><th>'.$Subhead.'</th>';
                    $TotDir = 0; $TotInDirUnProc = 0; $TotInDirProc=0;
                        if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                        {
                            echo "<td>".round($UnInDirect[$Subhead][$cost]-$InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnInDirect[$Subhead][$cost])."</td>";
                            $TotInDirUnProc += round($UnInDirect[$Subhead][$cost]);
                            $TotInDirProc += round($InDirect[$Subhead][$cost]);
                            
                            $branchInDirect[$cost]['unproc'] +=round($UnInDirect[$Subhead][$cost]);
                            $branchInDirect[$cost]['proc'] +=round($InDirect[$Subhead][$cost]);
                        }
                        echo '<th>'.round($TotInDirUnProc-$TotInDirProc).'</th>';
                        echo '<th>'.$TotInDirProc.'</th>';
                        echo '<th>'.$TotInDirUnProc.'</th>';
                        } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                        {
                            echo "<td>".round($UnInDirect[$Subhead][$cost]-$InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnInDirect[$Subhead][$cost])."</td>";
                            $TotInDirUnProc += round($UnInDirect[$Subhead][$cost]);
                            $TotInDirProc += round($InDirect[$Subhead][$cost]);
                            
                            $branchInDirect[$cost]['unproc'] +=round($UnInDirect[$Subhead][$cost]);
                            $branchInDirect[$cost]['proc'] +=round($InDirect[$Subhead][$cost]);
                        }
                        echo '<th>'.round($TotInDirUnProc-$TotInDirProc).'</th>';
                        echo '<th>'.$TotInDirProc.'</th>';
                        echo '<th>'.$TotInDirUnProc.'</th>';
                        } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                        {
                            echo "<td>".round($UnInDirect[$Subhead][$cost]-$InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($InDirect[$Subhead][$cost])."</td>";
                            echo "<td>".round($UnInDirect[$Subhead][$cost])."</td>";
                            $TotInDirUnProc += round($UnInDirect[$Subhead][$cost]);
                            $TotInDirProc += round($InDirect[$Subhead][$cost]);
                            
                            $branchInDirect[$cost]['unproc'] +=round($UnInDirect[$Subhead][$cost]);
                            $branchInDirect[$cost]['proc'] +=round($InDirect[$Subhead][$cost]);
                        }
                       echo '<th>'.round($TotInDirUnProc-$TotInDirProc).'</th>';
                        echo '<th>'.$TotInDirProc.'</th>';
                        echo '<th>'.$TotInDirUnProc.'</th>';
                        }
                    echo '</tr>';
                }
        ?> 
        <tr></tr>
       <tr>
            <th>Total InDirect Expense</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc'])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc - $InDirBrProc); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc'])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc - $InDirBrProc); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc'])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc - $InDirBrProc); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc); ?></th>
        <?php } ?> </tr>
        
       <tr>
            <th>Total InDirect Expense%</th>
            <?php $NetRev =0 ; $NetRevProc = 0; $InDirBrUnProcPer = 0; $InDirBrProcPer = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                       echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchInDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev-$NetRevProc))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/$NetRev)*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchInDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev-$NetRevProc))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/$NetRev)*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc'])/($inv_master[$cost]-$NReimbursement[$cost]['proc']))*100)."%</td>";
                        echo "<td>".round(($branchInDirect[$cost]['unproc']/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc']);
                        
                        $NetRev += ($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev-$NetRevProc))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/$NetRev)*100); ?>%</th>
        <?php } ?> </tr>
        
        
        <tr></tr>
        <tr>
            <th>Total Cost</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc); ?></th>
            <th><?php echo ($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc); ?></th>
            <th><?php echo ($TotCostUnProc); ?></th>
            
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc); ?></th>
            <th><?php echo ($TotCostUnProc); ?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Total Cost%</th>
            <?php $TotCostUnProc = 0;  $TotCostProc = 0; $TotNetProc = 0; $TotNetUnProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc'])*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost] +$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc'])*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['un'])))."%</td>";
                $TotCostUnProc += round(($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round(($TotCostUnProc-$TotCostProc)*100/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round($TotCostUnProc*100/$TotNetUnProc);  ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                 echo "<td>".round(($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc'])*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost] +$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc'])*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['un'])))."%</td>";
                $TotCostUnProc += round(($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round(($TotCostUnProc-$TotCostProc)*100/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round($TotCostUnProc*100/$TotNetUnProc); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc'])*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost] +$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc'])*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['un'])))."%</td>";
                $TotCostUnProc += round(($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round(($TotCostUnProc-$TotCostProc)*100/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round($TotCostUnProc*100/$TotNetUnProc); ?>%</th>
        <?php } ?> </tr>
        
       <tr></tr>
        <tr>
            <th>EBIDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
             <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>EBIDTA%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
               echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
        <?php } ?> </tr>
        
        <tr></tr>
        
        <tr></tr>
        
        <?php
        
                
                foreach($PnlBranchHead as $head)
                {
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                        
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo '<td>'.$PnlDataBranch[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataBranch[$head][$cost]['unproc']-$PnlDataBranch[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataBranch[$head][$cost]['uproc'].'</td>';
                        
                        $pnlProc += $PnlDataBranch[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataBranch[$head][$cost]['unproc'];
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    echo '<th>'.$pnlUnProc.'</th>';
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo '<td>'.$PnlDataBranch[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataBranch[$head][$cost]['unproc']-$PnlDataBranch[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataBranch[$head][$cost]['uproc'].'</td>';
                        $pnlProc += $PnlDataBranch[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataBranch[$head][$cost]['unproc'];
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    echo '<th>'.$pnlUnProc.'</th>';
                    
                    
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo '<td>'.$PnlDataBranch[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataBranch[$head][$cost]['unproc']-$PnlDataBranch[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataBranch[$head][$cost]['uproc'].'</td>';
                        $pnlProc += $PnlDataBranch[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataBranch[$head][$cost]['unproc'];
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
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
                        
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo '<td>'.$PnlDataProcess[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataProcess[$head][$cost]['unproc']-$PnlDataProcess[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataProcess[$head][$cost]['uproc'].'</td>';
                        
                        $pnlProc += $PnlDataProcess[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataProcess[$head][$cost]['unproc'];
                    }
                    echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    echo '<th>'.$pnlUnProc.'</th>';
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo '<td>'.$PnlDataProcess[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataProcess[$head][$cost]['unproc']-$PnlDataProcess[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataProcess[$head][$cost]['uproc'].'</td>';
                        $pnlProc += $PnlDataProcess[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataProcess[$head][$cost]['unproc'];
                    }
                    echo '<td>'.$pnlProc.'</td>';
                    echo '<td>'.($pnlUnProc-$pnlProc).'</td>';
                    echo '<td>'.$pnlUnProc.'</td>';
                    
                    
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo '<td>'.$PnlDataProcess[$head][$cost]['proc'].'</td>';
                        echo '<td>'.($PnlDataProcess[$head][$cost]['unproc']-$PnlDataProcess[$head][$cost]['proc']).'</td>';
                        echo '<td>'.$PnlDataProcess[$head][$cost]['uproc'].'</td>';
                        $pnlProc += $PnlDataProcess[$head][$cost]['proc'];
                        $pnlUnProc += $PnlDataProcess[$head][$cost]['unproc'];
                    }
                     echo '<th>'.$pnlProc.'</th>';
                    echo '<th>'.($pnlUnProc-$pnlProc).'</th>';
                    echo '<th>'.$pnlUnProc.'</th>';  
                    }
                    echo '</tr>';
                    echo '<tr></tr>';
                }
                
        ?>
        
        <tr></tr>
        <tr></tr>
        
        <tr>
            <th>EBDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
             <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>EBDTA%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                 echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
           <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
        <?php } ?> </tr>
        
        <tr></tr>
        
        <tr>
                <th>Capex</th>
                <?php
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                ?>
                <?php
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                ?>
                <?php
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                   echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                ?>
        <?php } ?> </tr>
        
        <tr></tr>
        
        <tr>
            <th>Net Profit Excluding Capex</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($inv_master[$cost]+ $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(($provision[$cost]+ $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))."</td>";
                $TotCostUnProc += round(($provision[$cost]- $NReimbursement[$cost]['un'])- ($ActualCTCBusi[$cost]  +$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(($inv_master[$cost]- $NReimbursement[$cost]['proc'])- ((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
            }
            ?>
             <th><?php echo round((($TotCostUnProc-$TotCostProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Net Profit Excluding Capex%</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0; $TotNetProc = 0;  $TotNetUnProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
               echo "<td>".round((($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] - $ActualCTC[$cost] +$branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))*100/($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un']))."%</td>";
                echo "<td>".round((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']))*100/($inv_master[$cost]-$NReimbursement[$cost]['proc']))."%</td>";
                echo "<td>".round((($provision[$cost]- $NReimbursement[$cost]['un'])- ((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']))*100/(($provision[$cost]-$inv_master[$cost]- $NReimbursement[$cost]['un'])+($inv_master[$cost]-$NReimbursement[$cost]['proc'])))."%</td>";
                
                $TotCostUnProc += round((($provision[$cost]- $NReimbursement[$cost]['un'])-($ActualCTCBusi[$cost] + $branchDirect[$cost]['unproc'] +$branchInDirect[$cost]['unproc'])));
                $TotCostProc += ((($inv_master[$cost]- $NReimbursement[$cost]['un'])-((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc'])));
                $TotNetProc += round($inv_master[$cost]-$NReimbursement[$cost]['proc']);
                $TotNetUnProc +=round(($provision[$cost]- $NReimbursement[$cost]['un']));
            }
            ?>
            <th><?php echo round((($TotCostUnProc-$TotCostProc)*100)/($TotNetUnProc-$TotNetProc)); ?>%</th>
            <th><?php echo round(($TotCostProc/$TotNetProc)*100); ?>%</th>
            <th><?php echo round(($TotCostUnProc/$TotNetUnProc)*100); ?>%</th>
        <?php } ?> </tr>
</table>    
<?php

        $fileName = "PNL_Branch_Wise_Report".'_'.$month_report;
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>            
<?php exit; ?>		

		
					
		
           

