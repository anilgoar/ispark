<?php

$cntLoop = "3";

?>
<table border="1">
    <thead>
        
        
        <tr>
            <th>Branch</th>
            <?php $NewBranch_master = array();
                if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                {
                    echo "<th colspan='3'>".$cost."</th>";
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
                    echo "<th colspan='3'>".$cost."</th>";
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
                    echo "<th colspan='3'>".$cost."</th>";
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
                        echo "<td>".round($billing_master_un[$cost]-$billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_un[$cost])."</td>";
                        
                        $TotBillProc += round($billing_master_proc[$cost]);
                        $TotBillUnProc += round($billing_master_un[$cost]);
                    } 
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($billing_master_un[$cost]-$billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_un[$cost])."</td>";
                        
                        $TotBillProc += round($billing_master_proc[$cost]);
                        $TotBillUnProc += round($billing_master_un[$cost]);
                    }
            ?>
           <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($billing_master_un[$cost]-$billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_proc[$cost])."</td>";
                        echo "<td>".round($billing_master_un[$cost])."</td>";
                        
                        $TotBillProc += round($billing_master_proc[$cost]);
                        $TotBillUnProc += round($billing_master_un[$cost]);
                    }
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>OutSource</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotProv = 0;  $TotInv = 0;//print_r($provision); exit; exit;
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($billing[$cost]-round($billing_proc[$cost]))."</td>";
                        echo "<td>".round($billing_proc[$cost])."</td>";
                        echo "<td>".round($billing[$cost])."</td>";
                        
                        $TotInv += round($billing_proc[$cost]);
                        $TotProv += round($billing[$cost]);
                    } 
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($billing[$cost]-round($billing_proc[$cost]))."</td>";
                        echo "<td>".round($billing_proc[$cost])."</td>";
                        echo "<td>".round($billing[$cost])."</td>";
                        
                        $TotInv += round($billing_proc[$cost]);
                        $TotProv += round($billing[$cost]);
                    }
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($billing[$cost]-round($billing_proc[$cost]))."</td>";
                        echo "<td>".round($billing_proc[$cost])."</td>";
                        echo "<td>".round($billing[$cost])."</td>";
                        
                        $TotInv += round($billing_proc[$cost]);
                        $TotProv += round($billing[$cost]);
                    }
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th> Revenue</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotProv = 0;  $TotInv = 0;//print_r($provision); exit; exit;
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-$inv_master[$cost])."</td>";
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
                        echo "<td>".round($provision[$cost]-$inv_master[$cost])."</td>";
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
                        echo "<td>".round($provision[$cost]-$inv_master[$cost])."</td>";
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
            <th>Gross Revenue</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotProv = 0;  $TotInv = 0;//print_r($provision); exit; exit;
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($provision[$cost]+$billing[$cost]-round($inv_master[$cost]+$billing_proc[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost]+$billing_proc[$cost])."</td>";
                        echo "<td>".round($provision[$cost]+$billing[$cost])."</td>";
                        
                        $TotInv += round($inv_master[$cost]+$billing_proc[$cost]);
                        $TotProv += round($provision[$cost] + $billing[$cost]);
                    } 
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($provision[$cost]+$billing[$cost]-round($inv_master[$cost]+$billing_proc[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost]+$billing_proc[$cost])."</td>";
                        echo "<td>".round($provision[$cost]+$billing[$cost])."</td>";
                        
                        $TotInv += round($inv_master[$cost]+$billing_proc[$cost]);
                        $TotProv += round($provision[$cost] + $billing[$cost]);
                    }
            ?>
            <th><?php echo round($TotProv-$TotInv);?></th>
            <th><?php echo round($TotInv);?></th>
            <th><?php echo round($TotProv);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($provision[$cost]+$billing[$cost]-round($inv_master[$cost]+$billing_proc[$cost]))."</td>";
                        echo "<td>".round($inv_master[$cost]+$billing_proc[$cost])."</td>";
                        echo "<td>".round($provision[$cost]+$billing[$cost])."</td>";
                        
                        $TotInv += round($inv_master[$cost]+$billing_proc[$cost]);
                        $TotProv += round($provision[$cost] + $billing[$cost]);
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
            <th>MPR Seat</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotBillProc = 0; $TotBillUnProc=0; //print_r($provision); exit; exit; $TotInv
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Seat[$cost]);
                    } 
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Seat[$cost]);
                    }
            ?>
           <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                       echo "<td></td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        echo "<td>".round($MPR_Seat[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Seat[$cost]);
                    }
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Seat Rate</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotBillProc = 0; $TotBillUnProc=0; //print_r($provision); exit; exit; $TotInv
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Rate[$cost]);
                    } 
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td></td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Rate[$cost]);
                    }
            ?>
           <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                       echo "<td></td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        echo "<td>".round($MPR_Rate[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($MPR_Rate[$cost]);
                    }
            ?>
            <th><?php echo round($TotBillUnProc-$TotBillProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc);?></th>
        <?php } ?> </tr>
        
        <tr>
            
            <th>Net Revenue</th>
            <?php $NetRevProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un'])."</td>";
                echo "<td>".round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])."</td>";
                echo '<td>'.round($billing[$cost] + ($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost]+ $NReimbursement[$cost]['un'])+round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])).'</td>';
                
                $NetRev_br[$cost] = round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un']);
                $NetRevProc_br[$cost] = round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);
                
                $NetRev += round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un']);
                $NetRevProc += round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);
            }
            
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRevProc); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo "<td>".round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un'])."</td>";
                        echo "<td>".round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])."</td>";
                        echo '<td>'.round($billing[$cost] + ($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost]+ $NReimbursement[$cost]['un'])+round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])).'</td>';

                        $NetRev_br[$cost] = round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un']);
                        $NetRevProc_br[$cost] = round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);

                        $NetRev += round($provision[$cost]-$inv_master[$cost] - $billing[$cost]+ $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);
                    }
            ?>
            <th><?php echo round($NetRev); ?></th>
            <th><?php echo round($NetRevProc); ?></th>
            <th><?php echo round($NetRev+$NetRevProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($provision[$cost]-$inv_master[$cost] -$billing_proc[$cost]+ $billing[$cost]+ $NReimbursement[$cost]['un'])."</td>";
                        echo "<td>".round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])."</td>";
                        echo '<td>'.round($billing[$cost] + ($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost]+ $NReimbursement[$cost]['un'])+round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc'])).'</td>';

                        $NetRev_br[$cost] = round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un']);
                        $NetRevProc_br[$cost] = round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);

                        $NetRev += round($provision[$cost]-$inv_master[$cost]-$billing_proc[$cost] + $billing[$cost]+ $NReimbursement[$cost]['un']);
                        $NetRevProc += round($inv_master[$cost]+$billing_proc[$cost]+$NReimbursement[$cost]['proc']);
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
                         $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
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
                         $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
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
                         $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
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
                echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                $NetRevProc +=$NetRevProc_br[$cost];
                $NetRevUnProc +=$NetRev_br[$cost];
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/($NetRevProc+$NetRevUnProc)); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
               echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                $NetRevProc +=$NetRevProc_br[$cost];
                $NetRevUnProc +=$NetRev_br[$cost];
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/($NetRevProc+$NetRevUnProc)); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($ActualCTCBusi[$cost]-$ActualCTC[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost])*100/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                $NetRevProc +=$NetRevProc_br[$cost];
                $NetRevUnProc +=$NetRev_br[$cost];
                $TotCTC += ($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]);
                 $TotActualCTCBus +=$ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost];
            }
            ?>
            <th><?php echo round(($TotActualCTCBus-$TotCTC)*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotCTC*100/$NetRevProc); ?>%</th>
            <th><?php echo round($TotActualCTCBus*100/($NetRevProc+$NetRevUnProc)); ?>%</th>
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
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])*100)/($NetRev_br[$cost]))."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])*100)/($NetRevProc_br[$cost]))."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']*100)/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)*100/($NetRev)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])*100)/($NetRev_br[$cost]))."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])*100)/($NetRevProc_br[$cost]))."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']*100)/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)*100/($NetRev)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round((($branchDirect[$cost]['unproc']-$branchDirect[$cost]['proc'])*100)/($NetRev_br[$cost]))."%</td>";
                        echo "<td>".round((($branchDirect[$cost]['proc'])*100)/($NetRevProc_br[$cost]))."%</td>";
                        echo "<td>".round(($branchDirect[$cost]['unproc']*100)/($NetRevProc_br[$cost] + $NetRev_br[$cost]))."%</td>";
                       $DirBrUnProcPer += round($branchDirect[$cost]['unproc']); 
                        $DirBrProcPer += round($branchDirect[$cost]['proc']);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round(($DirBrUnProcPer-$DirBrProcPer)*100/($NetRev)); ?>%</th>
            <th><?php echo round(($DirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($DirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            <?php } ?>
        </tr>
        <tr></tr>
          <tr>
            <th>InDirect Expense</th>
        </tr>
        <tr>
            <th>Future Revenue Adjustment</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotBillProc = 0; $TotBillUnProc=0; //print_r($provision); exit; exit; $TotInv
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($Futur_Revenue[$cost]);
                         
                         
                    } 
            ?>
            <th><?php echo round($TotBillUnProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc+$TotBillProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>0</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($Futur_Revenue[$cost]);
                         
                        
                    }
            ?>
           <th><?php echo round($TotBillUnProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc+$TotBillProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                       echo "<td>0</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($Futur_Revenue[$cost])."</td>";
                        echo "<td>0</td>";
                        
                        $TotBillUnProc += 0;
                         $TotBillProc += round($Futur_Revenue[$cost]);
                         
                         
                    }
            ?>
            <th><?php echo round($TotBillUnProc);?></th>
            <th><?php echo round($TotBillProc);?></th>
            <th><?php echo round($TotBillUnProc+$TotBillProc);?></th>
        <?php } ?> </tr>
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
                        echo "<td>".round($branchInDirect[$cost]['proc'] + $Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']+$Futur_Revenue[$cost]);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc ); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc+$InDirBrProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['proc'] + $Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']+$Futur_Revenue[$cost]);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc ); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc+$InDirBrProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['proc'] + $Futur_Revenue[$cost])."</td>";
                        echo "<td>".round($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])."</td>";
                        $InDirBrUnProc += round($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']);
                        $InDirBrProc += round($branchInDirect[$cost]['proc']+$Futur_Revenue[$cost]);
                    }
            ?>
            <th><?php echo round($InDirBrUnProc ); ?></th>
            <th><?php echo round($InDirBrProc); ?></th>
            <th><?php echo round($InDirBrUnProc+$InDirBrProc); ?></th>
        <?php } ?> </tr>
        
       <tr>
            <th>Total InDirect Expense%</th>
            <?php $NetRev =0 ; $NetRevProc = 0; $InDirBrUnProcPer = 0; $InDirBrProcPer = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                       echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($NetRev_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc']+ $Futur_Revenue[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])/($NetRev_br[$cost]+$NetRevProc_br[$cost]))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc'] +$Futur_Revenue[$cost]);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($NetRev_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc']+ $Futur_Revenue[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])/($NetRev_br[$cost]+$NetRevProc_br[$cost]))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc'] +$Futur_Revenue[$cost]);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round((($branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc'])/($NetRev_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['proc']+ $Futur_Revenue[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                        echo "<td>".round((($branchInDirect[$cost]['unproc']+$Futur_Revenue[$cost])/($NetRev_br[$cost]+$NetRevProc_br[$cost]))*100)."%</td>";
                        $InDirBrUnProcPer += round($branchInDirect[$cost]['unproc']);
                        $InDirBrProcPer += round($branchInDirect[$cost]['proc'] +$Futur_Revenue[$cost]);
                        
                        $NetRev += $NetRev_br[$cost];
                        $NetRevProc += $NetRevProc_br[$cost];
                    }
            ?>
            <th><?php echo round((($InDirBrUnProcPer-$InDirBrProcPer)/ ($NetRev))*100); ?>%</th>
            <th><?php echo round(($InDirBrProcPer/$NetRevProc)*100); ?>%</th>
            <th><?php echo round(($InDirBrUnProcPer/($NetRev+$NetRevProc))*100); ?>%</th>
        <?php } ?> </tr>
        
        
        <tr></tr>
        <tr>
            <th>Total Cost</th>
            <?php
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']+$Futur_Revenue[$cost]))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotalCostProc_br[$cost] =  round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]));
                $TotalCostUnProc_br[$cost] = round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']));
                
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc+$Futur_Revenue[$cost]); ?></th>
            <th><?php echo ($TotCostUnProc+$Futur_Revenue[$cost]); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']+$Futur_Revenue[$cost]))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotalCostProc_br[$cost] =  round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]));
                $TotalCostUnProc_br[$cost] = round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']));
                
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc+$Futur_Revenue[$cost]); ?></th>
            <th><?php echo ($TotCostUnProc+$Futur_Revenue[$cost]); ?></th>
            
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']))."</td>";
                echo "<td>".round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]))."</td>";
                echo "<td>".round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']+$Futur_Revenue[$cost]))."</td>";
                $TotCostUnProc += round(((($ActualCTCBusi[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['unproc'] +$branchDirect[$cost]['unproc']));
                $TotCostProc += round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']));
                $TotalCostProc_br[$cost] =  round(((($ActualCTC[$cost]+$Adjust[$cost]-$Adjust2[$cost]))+$branchInDirect[$cost]['proc'] +$branchDirect[$cost]['proc']+$Futur_Revenue[$cost]));
                $TotalCostUnProc_br[$cost] = round((($ActualCTCBusi[$cost] - $ActualCTC[$cost] + $branchDirect[$cost]['unproc']) +$branchInDirect[$cost]['unproc']-$branchInDirect[$cost]['proc']-$branchDirect[$cost]['proc']));
                
            }
            ?>
            <th><?php echo ($TotCostUnProc-$TotCostProc); ?></th>
            <th><?php echo ($TotCostProc+$Futur_Revenue[$cost]); ?></th>
            <th><?php echo ($TotCostUnProc+$Futur_Revenue[$cost]); ?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Total Cost%</th>
            <?php $TotCostUnProc = 0;  $TotCostProc = 0; $TotNetProc = 0; $TotNetUnProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round(($TotalCostUnProc_br[$cost])*100/($NetRev_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostUnProc_br[$cost]+$TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]+$NetRev_br[$cost]))."%</td>";
                $TotCostUnProc += $TotalCostUnProc_br[$cost];
                $TotCostProc += $TotalCostProc_br[$cost];
                $TotNetProc += $NetRevProc_br[$cost];
                $TotNetUnProc +=$NetRev_br[$cost];
                
            }
            ?>
            <th><?php echo round(($TotCostUnProc)*100/($TotNetUnProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round(($TotCostUnProc+$TotCostProc)*100/($TotNetUnProc+$TotNetProc));  ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                 echo "<td>".round(($TotalCostUnProc_br[$cost])*100/($NetRev_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostUnProc_br[$cost]+$TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]+$NetRev_br[$cost]))."%</td>";
                $TotCostUnProc += $TotalCostUnProc_br[$cost];
                $TotCostProc += $TotalCostProc_br[$cost];
                $TotNetProc += $NetRevProc_br[$cost];
                $TotNetUnProc +=$NetRev_br[$cost];
            }
            ?>
            <th><?php echo round(($TotCostUnProc)*100/($TotNetUnProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round(($TotCostUnProc+$TotCostProc)*100/($TotNetUnProc+$TotNetProc));  ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round(($TotalCostUnProc_br[$cost])*100/($NetRev_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]))."%</td>";
                echo "<td>".round(($TotalCostUnProc_br[$cost]+$TotalCostProc_br[$cost])*100/($NetRevProc_br[$cost]+$NetRev_br[$cost]))."%</td>";
                $TotCostUnProc += $TotalCostUnProc_br[$cost];
                $TotCostProc += $TotalCostProc_br[$cost];
                $TotNetProc += $NetRevProc_br[$cost];
                $TotNetUnProc +=$NetRev_br[$cost];
            }
            ?>
            <th><?php echo round(($TotCostUnProc)*100/($TotNetUnProc)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$TotNetProc); ?>%</th>
            <th><?php echo round(($TotCostUnProc+$TotCostProc)*100/($TotNetUnProc+$TotNetProc));  ?>%</th>
        <?php } ?> </tr>
        
       <tr></tr>
        <tr>
            <th>EBIDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]))."</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]))."</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]))."</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
            }
            ?>
             <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>EBIDTA %</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;$NetDiv=0; $NetDivProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round((((round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round((((round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round((((round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost])+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost])))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += $NetRev_br[$cost]-$TotalCostUnProc_br[$cost];
                $TotCostProc += $NetRevProc_br[$cost]-$TotalCostProc_br[$cost];
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
             <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
        <?php } ?> </tr>
        
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
                    
                }
                
        ?>
        
        <tr>
            <th>OutStanding</th>
            <?php    //UnProcessed Provision For Branch Type A
                    $TotOutProc = 0; $TotOutUnProc=0; //print_r($provision); exit; exit; $TotInv
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo "<td>".round($outstand_unproc_data[$cost])."</td>";
                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
                        echo "<td>".round($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])."</td>";
                        
                        $TotOutUnProc+= round($outstand_unproc_data[$cost]);
                        $TotOutProc += round($outstand_proc_data[$cost]);
                    } 
            ?>
            <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($outstand_unproc_data[$cost])."</td>";
                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
                        echo "<td>".round($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])."</td>";
                        
                        $TotOutUnProc+= round($outstand_unproc_data[$cost]);
                        $TotOutProc += round($outstand_proc_data[$cost]);
                    } 
            ?>
           <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($outstand_unproc_data[$cost])."</td>";
                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
                        echo "<td>".round($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])."</td>";
                        
                        $TotOutUnProc+= round($outstand_unproc_data[$cost]);
                        $TotOutProc += round($outstand_proc_data[$cost]);
                    }
            ?>
            <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
        <?php } ?> </tr>
        
        <tr>
            <th>Interest</th>
            <?php    //UnProcessed Provision For Branch Type A
                $TotOutProc = 0; $TotOutUnProc=0; //print_r($provision); exit; exit; $TotInv
                if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                {
                    echo "<td>".round($outstand_unproc_data[$cost]*0.04)."</td>";
                    echo "<td>".round($outstand_proc_data[$cost]*0.04)."</td>";
                    echo "<td>".round(($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])*0.04)."</td>";

                    $TotOutUnProc+= round($outstand_unproc_data[$cost]*0.04);
                    $TotOutProc += round($outstand_proc_data[$cost]* 0.04);
                } 
            ?>
            <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                        echo "<td>".round($outstand_unproc_data[$cost]*0.04)."</td>";
                        echo "<td>".round($outstand_proc_data[$cost]*0.04)."</td>";
                        echo "<td>".round(($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])*0.04)."</td>";

                        $TotOutUnProc+= round($outstand_unproc_data[$cost]*0.04);
                        $TotOutProc += round($outstand_proc_data[$cost]* 0.04);
                    } 
            ?>
           <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo "<td>".round($outstand_unproc_data[$cost]*0.04)."</td>";
                        echo "<td>".round($outstand_proc_data[$cost]*0.04)."</td>";
                        echo "<td>".round(($outstand_unproc_data[$cost]+$outstand_proc_data[$cost])*0.04)."</td>";

                        $TotOutUnProc+= round($outstand_unproc_data[$cost]*0.04);
                        $TotOutProc += round($outstand_proc_data[$cost]* 0.04);
                    }
            ?>
            <th><?php echo round($TotOutUnProc);?></th>
            <th><?php echo round($TotOutProc);?></th>
            <th><?php echo round($TotOutUnProc + $TotOutProc);?></th>
        <?php } ?> </tr>
        
<!--        <tr>
            <th>Finance Expense</th>
            <?php    //UnProcessed Provision For Branch Type A
                    //$TotFinanceProc = 0; $TotFinanceUnProc=0; //print_r($provision); exit; exit; $TotInv
//                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
//                    {
//                        echo "<td>".round($outstand_unproc_data[$cost]-$outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_unproc_data[$cost][$cost])."</td>";
//                        
//                        $TotFinanceProc += round($outstand_proc_data[$cost]);
//                        $TotFinanceUnProc += round($outstand_unproc_data[$cost]);
//                    } 
            ?>
            <th><?php //echo round($TotFinanceUnProc-$TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceUnProc);?></th>
            <?php   //UnProcessed Provision For Branch Type B
                    
//                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
//                    {
//                        echo "<td>".round($outstand_unproc_data[$cost]-$outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_unproc_data[$cost][$cost])."</td>";
//                        
//                        $TotFinanceProc += round($outstand_proc_data[$cost]);
//                        $TotFinanceUnProc += round($outstand_unproc_data[$cost]);
//                    }
            ?>
           <th><?php //echo round($TotFinanceUnProc-$TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceUnProc);?></th>
            <?php
                    //UnProcessed Provision For Branch Type C
//                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
//                    {
//                        echo "<td>".round($outstand_unproc_data[$cost]-$outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_proc_data[$cost])."</td>";
//                        echo "<td>".round($outstand_unproc_data[$cost][$cost])."</td>";
//                        
//                        $TotFinanceProc += round($outstand_proc_data[$cost]);
//                        $TotFinanceUnProc += round($outstand_unproc_data[$cost]);
//                    }
            ?>
            <th><?php //echo round($TotFinanceUnProc-$TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceProc);?></th>
            <th><?php //echo round($TotFinanceUnProc);?></th>
        <?php //} ?> </tr>-->
        
        <tr></tr>
        
        <tr>
            <th>EBIDTA</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
             <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>EBIDTA %</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;$NetDiv=0; $NetDivProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round(($TotCostProc/$NetDivProc)*100); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
             <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
        <?php } ?> </tr>
        
        <tr></tr>
        
        <tr>
                <th>Capex</th>
                <?php
                    if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
                    {
                        echo '<td>0</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        $tot_capex += round($capex[$cost]);
                    }
                    echo '<td>0</td>';
                        echo '<td>'.$tot_capex.'</td>';
                        echo '<td>'.$tot_capex.'</td>';
                ?>
                <?php
                    } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
                    {
                       echo '<td>0</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        $tot_capex += round($capex[$cost]);
                    }
                    echo '<td>0</td>';
                        echo '<td>'.$tot_capex.'</td>';
                        echo '<td>'.$tot_capex.'</td>';
                ?>
                <?php
                    } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
                    {
                        echo '<td>0</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        echo '<td>'.round($capex[$cost]).'</td>';
                        $tot_capex += round($capex[$cost]);
                    }
                   echo '<td>0</td>';
                        echo '<td>'.$tot_capex.'</td>';
                        echo '<td>'.$tot_capex.'</td>';
                ?>
        <?php } ?> </tr>
        
        <tr></tr>
        
        <tr>
            <th>Net Profit Excluding Capex</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                 echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                 echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
            <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))."</td>";
                echo "<td>".round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))."</td>";
                echo "<td>".round(round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))."</td>";
                $TotCostUnProc += round($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += round($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
            }
            ?>
             <th><?php echo round((($TotCostUnProc))); ?></th>
            <th><?php echo round($TotCostProc); ?></th>
            <th><?php echo round($TotCostUnProc+$TotCostProc); ?></th>
        <?php } ?> </tr>
        <tr>
            <th>Net Profit Excluding Capex %</th>
            <?php $TotCostUnProc = 0; $TotCostProc=0;$NetDiv=0; $NetDivProc = 0;
            if(!empty($branch_master1)) { foreach($branch_master1 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round(($TotCostProc/$NetDivProc)*100); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master2)) { foreach($branch_master2 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
            <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
            <?php
            } if(!empty($branch_master3)) { foreach($branch_master3 as $cost)
            {
                echo "<td>".round((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))/($NetRev_br[$cost]))*100)."%</td>";
                echo "<td>".round((($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04))/($NetRevProc_br[$cost]))*100)."%</td>";
                echo "<td>".round(((($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04))+($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04)))*100)/($NetRev_br[$cost]+$NetRevProc_br[$cost]))."%</td>";
                $TotCostUnProc += ($NetRev_br[$cost]-$TotalCostUnProc_br[$cost]-round($outstand_unproc_data[$cost]*0.04));
                $TotCostProc += ($NetRevProc_br[$cost]-$TotalCostProc_br[$cost]-round($outstand_proc_data[$cost]*0.04));
                $NetDiv += $NetRev_br[$cost];
                $NetDivProc += $NetRevProc_br[$cost];
            }
            ?>
             <th><?php echo round((($TotCostUnProc/$NetDiv)*100)); ?>%</th>
            <th><?php echo round($TotCostProc*100/$NetDivProc); ?>%</th>
            <th><?php echo round((($TotCostUnProc+$TotCostProc)*100)/($NetDiv+$NetDivProc)); ?>%</th>
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

		
					
		
           

