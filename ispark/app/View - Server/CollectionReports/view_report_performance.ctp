<?php

//print_r($data);
        $fileName = $report=='Performance'?'Performance':'Payment_Status'."_CollectionPlanning_";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
	$class = "border = \"1\"";
        
        if($report=='Performance'){
?>
<table border="1" style="font-size: 90%;">
    <tr>
        <td align='center'><b>Branch</b></td>	
        <td align='center'><b>Opening O/S</b></td>	
        <td align='center'><b>Fresh Billing</b></td>	
        <td align='center'><b>Realisation OB</b></td>	
        <td align='center'><b>Realisation FB</b></td>	
        <td align='center'><b>Total</b></td>	
        <td align='center'><b>TDS</b></td>
        <td align='center'><b>Deduction</b></td>
        <td align='center'><b>Other DED</b></td>
        <td align='center'><b>Other Bill DED</b></td>
        <td align='center'><b>Net OS Realised</b></td>	
        <td align='center'><b>Closing O/S</b></td>	
        <td align='center'><b>0-30</b></td>	
        <td align='center'><b>31-60</b></td>	
        <td align='center'><b>61-90</b></td>	
        <td align='center'><b>91-120</b></td>	
        <td align='center'><b>>120</b></td>	
        <td align='center'><b>UnProcessed Revenue</b></td>	
        <td align='center'><b>Total Outstanding</b></td>        
    </tr>
    <?php  $openOS = $freshBill = $realOB = $realFB = $total = $tds = $ded = $od = $odb = $netOS= $closeOS = $zero= $one = $two= $three = $four= $unPro = $totalOS =0;
            foreach($data as $d):
                echo "<tr>";
                    echo "<td align='center'>".$d['tab']['branch_name']."</td>";
                    echo "<td align='center'>".$d['tab']['openingOS']."</td>";
                    echo "<td align='center'>".$d['tab']['FreshBilling']."</td>";
                    echo "<td align='center'>".$d['tab']['RealisationOB']."</td>";
                    echo "<td align='center'>".$d['tab']['RealisationFB']."</td>";
                    echo "<td align='center'>".$d['tab']['Total']."</td>";
                    echo "<td align='center'>".$d['tab']['TDS']."</td>";
                    echo "<td align='center'>".$d['tab']['Deduction']."</td>";
                    echo "<td align='center'>".round($d['oth']['other_deduction'])."</td>";
                    echo "<td align='center'>".round($d['obd']['other_deduction_bill'])."</td>";
                    echo "<td align='center'>".$d['0']['NetOS']."</td>";
                    echo "<td align='center'>".$d['0']['closingOS']."</td>";
                    echo "<td align='center'>".$d['tab']['zero']."</td>";
                    echo "<td align='center'>".$d['tab']['one']."</td>";
                    echo "<td align='center'>".$d['tab']['two']."</td>";
                    echo "<td align='center'>".$d['tab']['three']."</td>";
                    echo "<td align='center'>".$d['tab']['four']."</td>";
                    echo "<td align='center'>".$d['prov']['Unprocess']."</td>";
                    echo "<td align='center'>".$d['0']['TotalOS']."</td>";
                echo "</tr>";
                
                $openOS +=$d['tab']['openingOS'];
                $freshBill += $d['tab']['FreshBilling'];
                $realOB += $d['tab']['RealisationOB'];
                 $realFB += $d['tab']['RealisationFB'];
                $total +=$d['tab']['Total'];
                $tds +=$d['tab']['TDS'];
                $ded += $d['tab']['Deduction'];
                $od +=round($d['oth']['other_deduction']);
                $odb +=round($d['obd']['other_deduction_bill']);
                $netOS +=$d['0']['NetOS'];
                $closeOS +=$d['0']['closingOS'];
                $zero +=$d['tab']['zero'];
                $one +=$d['tab']['one'];
                $two +=$d['tab']['two'];
                $three +=$d['tab']['three'];
                $four +=$d['tab']['four'];
                $unPro +=$d['prov']['Unprocess'];
                $totalOS += $d['0']['TotalOS'];
            endforeach;
    ?>
    <tr>
        <td align='center'><b>Total</b></td>
        <td align='center'><b><?=$openOS?></b></td>
        <td align='center'><b><?=$freshBill?></b></td>
        <td align='center'><b><?=$realOB?></b></td>
        <td align='center'><b><?=$realFB?></b></td>
        <td align='center'><b><?=$total?></b></td>
        <td align='center'><b><?=$tds?></b></td>
        <td align='center'><b><?=$ded?></b></td>
        <td align='center'><b><?=$od?></b></td>
        <td align='center'><b><?=$odb?></b></td>
        <td align='center'><b><?=$netOS?></b></td>
        <td align='center'><b><?=$closeOS?></b></td>
        <td align='center'><b><?=$zero?></b></td>
        <td align='center'><b><?=$one?></b></td>
        <td align='center'><b><?=$two?></b></td>
        <td align='center'><b><?=$three?></b></td>
        <td align='center'><b><?=$four?></b></td>
        <td align='center'><b><?=$unPro?></b></td>
        <td align='center'><b><?=$totalOS?></b> </td>
    </tr>
</table>
        <?php } else { ?>
<table border="1" style="font-size: 75%;">
    <tr>
        <td align='center'><b>Branch</b></td>	
        <td align='center'><b>Client</b></td>	
        <td align='center'><b>Collected</b></td>	
        <td align='center'><b>Not Allocated</b></td>	
        <td align='center'><b>UnProcessed Revenue</b></td>	
        <td align='center'><b>PTP Break</b></td>	
        <td align='center'><b>Pymt for the Month</b></td>
        <td align='center'><b>Post PTP</b></td>
        <td align='center'><b>Total OutStanding</b></td>
        <?php
        $i =$start_date;
        $i++;
                for(;$i<=$end_date; $i++)
                {
                    echo "<td align='center'><b>".$i."</b></td>";
                }
        ?>
    </tr>
    <?php $collected = $notAllocated= $Unprocess = $ptp = $pmt = $post = $total = 0; $ArrTotal = array();
            foreach($data as $d):
                if(!empty($d['0']['collected']) || !empty($d['0']['Not_Allocated']) || !empty($d['0']['UnProcessed']) || !empty($d['0']['PTP_Break']) ||
                    !empty($d['0']['Pmt_Month']) || !empty($d['0']['Post_PTP']) || !empty($d['0']['Total']) || !empty($d['0']['range'])) 
                    {
                echo "<tr>";
                    echo "<td align='center'><b>".$d['tab']['branch']."</b></td>";
                    echo "<td align='center'>".$d['tab']['client']."</td>";
                    echo "<td align='center'>".$d['0']['collected']."</td>";
                    echo "<td align='center'>".$d['0']['Not_Allocated']."</td>";
                    echo "<td align='center'>".$d['0']['UnProcessed']."</td>";
                    echo "<td align='center'>".$d['0']['PTP_Break']."</td>";
                    echo "<td align='center'>".$d['0']['Pmt_Month']."</td>";
                    echo "<td align='center'>".$d['0']['Post_PTP']."</td>";
                    echo "<td align='center'>".$d['0']['Total']."</td>";
                    
                    $i =$start_date;
                    $i++;
                    $ArrTotal1=array_fill($i, $end_date-$start_date, 0);
                    for(;$i<=$end_date; $i++)
                    {
                        if(!empty($d['0']['range'])) 
                        {
                            $Arr1 = explode(',', $d['0']['range']);
                            for($j=0; $j<count($Arr1); $j++)
                            {
                                $Arr2 = explode('-',$Arr1[$j]);
                                if($i==$Arr2[0])
                                { 
                                    //echo "<td align='center'>".$Arr2[1]."</td>"; 
                                    if(array_key_exists($i, $ArrTotal))
                                    {
                                        $ArrTotal[$i] +=$Arr2[1];
                                        $ArrTotal1[$i] +=$Arr2[1];
                                    }
                                    else
                                    {
                                        $ArrTotal[$i] =0;
                                    }
                                }
                                else 
                                { 
                                    //echo "<td align='center'>0</td>"; 
                                    if(array_key_exists($i, $ArrTotal))
                                    {
                                        $ArrTotal[$i] +=0;
                                        $ArrTotal1[$i] +=0;
                                    }
                                    else
                                    {
                                        $ArrTotal[$i] =0;
                                    }
                                }
                            }
                        }
                    else { //echo "<td align='center'>0</td>";
                           if(array_key_exists($i, $ArrTotal))
                                {
                                    $ArrTotal[$i] +=0;
                                    $ArrTotal1[$i] +=0;
                                }
                                else
                                {
                                    $ArrTotal[$i] =0;
                                } 
                        }
                        
                            }
                            //print_r($ArrTotal1);
                       foreach($ArrTotal1 as $v)
                       {
                           echo "<td align='center'>".$v."</td>";
                       } 
                          
                    
                    $collected +=$d['0']['collected'];
                    $notAllocated += $d['0']['Not_Allocated'];
                    $Unprocess += $d['0']['UnProcessed'];
                    $ptp +=$d['0']['PTP_Break'];
                    $pmt +=$d['0']['Pmt_Month'];
                    $post +=$d['0']['Post_PTP'];
                    $total +=$d['0']['Total'];
                    
                echo "</tr>";
                }
            endforeach;
        echo "<tr>";
            echo "<td align='center' colspan='2'><b>Total</b></td>";
            echo "<td align='center'><b>".$collected."</b></td>";
            echo "<td align='center'><b>".$notAllocated."</b></td>";
            echo "<td align='center'><b>".$Unprocess."</b></td>";
            echo "<td align='center'><b>".$ptp."</b></td>";
            echo "<td align='center'><b>".$pmt."</b></td>";
            echo "<td align='center'><b>".$post."</b></td>";
            echo "<td align='center'><b>".$total."</b></td>";
            $i =$start_date;
            $i++;
            for(;$i<=$end_date; $i++)
            {
               echo "<td align='center'><b>".$ArrTotal[$i]."</b></td>";
            }
        echo "</tr>";
    ?>
        <?php } ?>
</table>