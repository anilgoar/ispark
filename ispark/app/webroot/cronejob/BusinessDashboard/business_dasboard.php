<?php
include('report-send.php');
$con = mysql_connect("localhost",'root','Mas@1234');
$db = mysql_select_db("db_bill", $con);

$branch = $_GET['Branch'];

if($branch!='All')
{
    $BranchAll = " and cm.branch='$branch'";
}

$finMonth = 'Oct';
$finYear = '2018-19';

 $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
    INNER JOIN cost_master cm ON dt.cost_centerId=cm.id $BranchAll
    WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth'   group by cost_centerId ";

            //$AspirationalData = $this->Targets->query($AspirationalQry);
            $AspirationalDataRsc = mysql_query($AspirationalQry);



            while($asp = mysql_fetch_assoc($AspirationalDataRsc))
            {
                $NewData[$asp['branch']][$asp['cost_centerId']]['Asp']['revenue'] =  $asp['target'];
                $NewData[$asp['branch']][$asp['cost_centerId']]['Asp']['dc'] =  $asp['target_directCost'];
                $NewData[$asp['branch']][$asp['cost_centerId']]['Asp']['idc'] =  $asp['target_IDC'];
                $cost_master[$asp['branch']][] = $asp['id'];
                $BranchArr[] =  $asp['branch'];
            }
            //print_r($NewData); exit;
            $Actual = "SELECT cm.id,cm.branch,dd.branch,cost_centerId,branch_process,
    `commit` Revenue,
    direct_cost DirectCost,
    indirect_cost InDirectCost
    FROM `dashboard_data` dd
    INNER JOIN cost_master cm ON dd.cost_centerId=cm.id $BranchAll
    WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth'   AND 
    dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
    AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth'  AND dd.cost_centerId=dd1.cost_centerId)";
            $ActualRsc = mysql_query($Actual);
            
            
            while($bas =mysql_fetch_assoc($ActualRsc))
            {
                    $NewData[$bas['branch']][$bas['cost_centerId']]['Actual']['revenue'] =  $bas['Revenue'];
                    $NewData[$bas['branch']][$bas['cost_centerId']]['Actual']['dc'] =  $bas['DirectCost'];
                    $NewData[$bas['branch']][$bas['cost_centerId']]['Actual']['idc'] =  $bas['InDirectCost'];
                    $cost_master[[$bas['branch']]][] = $bas['id'];
                    $BranchArr[] =  $bas['branch'];
            }
            //print_r($NewData); exit;
    
           $NewFinanceMonth = $finMonth; 
            $monthArr = array('Jan','Feb','Mar'); 
            $split = explode('-',$finYear); 
            if(in_array($finMonth, $monthArr)) 
            {
                $NewFinanceMonth .= '-'.$split[1];    //Year from month
            }
            else
            {
                $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
            }



            $RevenueBasic = "SELECT cm.id,cm.branch,pm.provision FROM provision_master pm
    LEFT JOIN 
    (
    SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center $BranchAll
     WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
    ON pm.month = ti.month AND pm.cost_center = ti.cost_center
    INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center $BranchAll
    WHERE  pm.month='$NewFinanceMonth'";
            $RevenueBasicRsc = mysql_query($RevenueBasic);

            while($rev_ = mysql_fetch_assoc($RevenueBasicRsc))
            {
                $NewData[$rev_['branch']][$rev_['id']]['Basic']['revenue'] =  round($rev_['provision'],2);
                $cost_master[$rev_['branch']][] = $rev_['id'];
                $BranchArr[] =  $rev_['branch'];
            }

            //print_r($NewData); exit;

            //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
            //print_r($NewData); exit;
            $DirectActualBusinessCase = "SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM expense_particular ep 
    INNER JOIN expense_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ";
            $DirectActualBusinessCase = mysql_query($DirectActualBusinessCase);
            while($DirectBC = mysql_fetch_assoc($DirectBC))
            {
                $NewData[$DirectBC['branch']][$DirectBC['id']]['Basic']['dc'] +=  $DirectBC['Amount'];   
                $cost_master[$DirectBC['branch']][] = $DirectBC['id'];
                $BranchArr[] =  $DirectBC['branch'];
            }

            $InDirectActualBusinessCase = "SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM expense_particular ep 
    INNER JOIN expense_master em ON ep.ExpenseId = em.Id 
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ";
            $InDirectActualBusinessCase = mysql_query($InDirectActualBusinessCase);
            while($InDirectBC = mysql_fetch_assoc($InDirectActualBusinessCase))
            {    
                $NewData[$InDirectBC['branch']][$InDirectBC['id']]['Basic']['idc'] +=  $InDirectBC['Amount'];    
                $cost_master[$InDirectBC['branch']][] = $InDirectBC['id'];
                $BranchArr[] =  $InDirectBC['branch'];
            }

            $BranchArr = array_unique($BranchArr);
            
            foreach($BranchArr as $Branch)
            {
                $NewCostMaster[$Branch] = array_unique($cost_master[$Branch]);
            }
            //$cost_master = $NewCostMaster;
            //$cost_master = array_unique($cost_master);
            $newCostMaster = array();
            foreach($NewCostMaster as $k=>$v)
            {
                //$cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$v)));
                
                $costQry = "Select * from cost_master where id in ('".implode(",",$v)."')";
                $cost_arr = mysql_query($costQry);
                
                
                while($cost = mysql_fetch_assoc($cost_arr))
                {
                    $newCostMaster[$k][$cost['id']]['PrcoessName'] = $cost['process_name'];
                    $newCostMaster[$k][$cost['id']]['CostCenter'] = $cost['cost_center'];
                }
            }
            
            //print_r($newCostMaster); exit;
            
       $CostCenter = $newCostMaster;
       $Data = $NewData;

?>

<table  border="2"  > 
          <thead>
<!--        <tr style="text-align:center">
            <td colspan="15"><b><?php //echo $Branch; ?></b></td>
        </tr>-->
        <tr style="text-align:center">
            <td rowspan="2"><b>Branch</b></td>
            <td colspan="3"><b>Revenue</b></td>
            <td colspan="3"><b>Direct Cost</b></td>
            <td colspan="3"><b>InDirect Cost</b></td>
            <td colspan="3"><b>OP</b></td>
            <td colspan="3"><b>OP%</b></td>
<!--            <td colspan="2" rowspan="2"><b>Status</b></td>-->
        </tr>
        <tr  style="text-align:center;">

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->
            <td><b>Aspirational</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->
            <td><b>Aspira</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->


        </tr>
        </thead>
          <?php foreach($BranchArr as $Branch) { ?>
        
<?php $totalArray = array();
    foreach($CostCenter[$Branch] as $cost_id=>$cost_master)
    {
        
                $Rev_asp = round($Data[$Branch][$cost_id]['Asp']['revenue'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
        
                $Rev_bas = round($Data[$Branch][$cost_id]['Basic']['revenue'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
 
                $Rev_act = round($Data[$Branch][$cost_id]['Actual']['revenue'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
                $Dir_asp = round($Data[$Branch][$cost_id]['Asp']['dc'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
                $Dir_bas = round($Data[$Branch][$cost_id]['Basic']['dc'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
                $Dir_act = round($Data[$Branch][$cost_id]['Actual']['dc'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
                $InDir_asp = round($Data[$Branch][$cost_id]['Asp']['idc'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
            $InDir_bas = round($Data[$Branch][$cost_id]['Basic']['idc'],2);
            $totalArray['Basic']['idc'] +=$InDir_bas;
                $InDir_act = round($Data[$Branch][$cost_id]['Actual']['idc'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
    }
    
    echo '<tr>';
    echo '<td>'.$Branch.'</td>';
    
    echo '<td>'.$totalArray['Asp']['revenue'].'</td>'; $GrandTotalArray['Asp']['revenue'] +=$totalArray['Asp']['revenue'];
    echo '<td>'.$totalArray['Basic']['revenue'].'</td>';$GrandTotalArray['Basic']['revenue'] +=$totalArray['Basic']['revenue'];
    echo '<td>'.$totalArray['Actual']['revenue'].'</td>';$GrandTotalArray['Actual']['revenue'] +=$totalArray['Actual']['revenue'];
    
    echo '<td>'.$totalArray['Asp']['dc'].'</td>'; $GrandTotalArray['Asp']['dc'] +=$totalArray['Asp']['dc'];
    echo '<td>'.$totalArray['Basic']['dc'].'</td>'; $GrandTotalArray['Basic']['dc'] +=$totalArray['Basic']['dc'];
    echo '<td>'.$totalArray['Actual']['dc'].'</td>'; $GrandTotalArray['Actual']['dc'] +=$totalArray['Actual']['dc'];
    
    echo '<td>'.$totalArray['Asp']['idc'].'</td>'; $GrandTotalArray['Asp']['idc'] +=$totalArray['Asp']['idc'];
    echo '<td>'.$totalArray['Basic']['idc'].'</td>'; $GrandTotalArray['Basic']['idc'] +=$totalArray['Basic']['idc'];
    echo '<td>'.$totalArray['Actual']['idc'].'</td>'; $GrandTotalArray['Actual']['idc'] +=$totalArray['Actual']['idc'];
    
    echo '<td>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</td>';


    echo '<td>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</td>';
    
    echo '</tr>';
    
          }
          
    echo '<tr>';
    echo '<th>Total</th>';
    echo '<td>'.$GrandTotalArray['Asp']['revenue'].'</td>'; 
    echo '<td>'.$GrandTotalArray['Basic']['revenue'].'</td>';
    echo '<td>'.$GrandTotalArray['Actual']['revenue'].'</td>';
    
    echo '<td>'.$GrandTotalArray['Asp']['dc'].'</td>'; 
    echo '<td>'.$GrandTotalArray['Basic']['dc'].'</td>'; 
    echo '<td>'.$GrandTotalArray['Actual']['dc'].'</td>'; 
    
    echo '<td>'.$GrandTotalArray['Asp']['idc'].'</td>'; 
    echo '<td>'.$GrandTotalArray['Basic']['idc'].'</td>'; 
    echo '<td>'.$GrandTotalArray['Actual']['idc'].'</td>';
    
    echo '<td>'.round($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'],2).'</td>';
    echo '<td>'.round($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'],2).'</td>';
    echo '<td>'.round($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'],2).'</td>';


    echo '<td>'.round(($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'])*100/$GrandTotalArray['Asp']['revenue'],2).'%</td>';
    echo '<td>'.round(($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'])*100/$GrandTotalArray['Basic']['revenue'],2).'%</td>';
    echo '<td>'.round(($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'])*100/$GrandTotalArray['Actual']['revenue'],2).'%</td>';
    echo '<tr>';
?>
    </table> 

    
    

    
    
<?php    $select2 ="SELECT * FROM `business_dashboard_mail` WHERE Branch in('".implode("','",$branchArr)."')";
    $excute2 = mysql_query($select2);
    while($Data = mysql_fetch_assoc($excute2))
    {
        $To = $Data['ReportTo'];
        $Tos = explode(",",$To);
        $AddTo = array();
        $TosFlag = true;
        if(is_array($Tos) && !empty($Tos))
        {
            foreach($Tos as $to)
            {
                if(!empty($to))
                {
                    if($TosFlag)
                    {
                        $To = $to;$TosFlag=false;
                    }
                    else
                    {
                        $AddTo[] = $to;
                    }
                }

            }
        }
    
	$CC = explode(",",$Data['ReportCC']);
        $BCC = explode(",",$Data['ReportBCC']);
    }
    
    if(!empty($AddCc))
    {
        $emaildata['AddCc'] =  $AddCc;
    }
    
    if(!empty($AddTo))
    {
        $emaildata['AddTo'] =  $AddTo;
    }
    if(!empty($BCC))
    {
        $emaildata['AddBcc'] =  $BCC;
    }
    
    if($count1 || $count2)
    {
        //$cc[] = "deepak.kashyap@teammas.in"; 
    }
    if(!empty($to))
    {
        $emaildata['ReceiverEmail']['Email'] = implode(",",$to);
        $emaildata['SenderEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
        $emaildata['ReplyEmail'] = array('Email'=>'ispark@teammas.in','Name'=>'Ispark');
        $emaildata['AddCc']['Email'] = implode(",",$cc);
    }
    
    
    $emaildata['Subject'] = "Revenue UnProcessed"; 
    
    $emaildata['EmailText'] =$html;
    
    try
    {
        $done = send_email( $emaildata);
        //echo " asdfjlsdjf lsdjf lskdfj lksdjf sldfk";
        //print_r($emaildata);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
    }
    

