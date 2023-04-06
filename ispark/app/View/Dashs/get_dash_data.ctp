<?php
if($type=='export')
{
    $fileName = "ExportDataDaysWise";
    header("Content-Type: application/vnd.ms-excel; name='excel'");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".$fileName.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
else
{?>
  <style>
    table td{text-align: center!important;}
    table th{text-align: center!important;text-transform: capitalize!important;}
    
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border: 2px solid #bbb;
}

</style>

<?php }
//if($ReportType=='CostCenter')
//{?>


<!--<table border="2" //<?php  if($type!='export') { ?>   class = "table table-striped table-hover  responstable"    <?php } ?>>
        <thead>
        <tr style="text-align:center">
            <th colspan="2"><b>Process</b></th>
            <th colspan="3"><b>Revenue</b></th>
            <th colspan="3"><b>Direct Cost</b></th>
            <th colspan="3"><b>InDirect Cost</b></th>
            <th colspan="3"><b>OP</b></th>
            <th colspan="3"><b>OP%</b></th>
            <td colspan="2" rowspan="2"><b>Status</b></td>
        </tr>
        <tr  style="text-align:center;">
            <td><b>Process Name</b></td>
            <td><b>Cost Center</b></td>

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        <td><b>Processed</b></td>

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        <td><b>Processed</b></td>

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        <td><b>Processed</b></td>
            <td><b>Aspirational</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        <td><b>Processed</b></td>
            <td><b>Aspirational</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        <td><b>Processed</b></td>


        </tr>
        </thead>
//<?php
//    foreach($CostCenter as $cost_id=>$cost_master)
//    {
//        echo '<tr style="text-align:center">';
//            // CostCenter Process Name and Cost Center
//            echo '<td>';
//                echo ''.$cost_master['PrcoessName'].'</a>';
//            echo '</td>';
//            echo '<td>';
//                echo $cost_master['CostCenter'];
//            echo '</td>';
//
//            // Revenue Details are here
//            echo '<td>';
//                $Rev_asp = round($Data[$Branch][$cost_id]['Asp']['revenue'],2);
//                $totalArray['Asp']['revenue'] +=$Rev_asp;
//                echo $Rev_asp;
//              
//            echo '</td>';
//            
//            
//
//            echo '<td>';
//                $Rev_bas = round($Data[$Branch][$cost_id]['Basic']['revenue'],2);
//                $totalArray['Basic']['revenue'] +=$Rev_bas;
//                echo $Rev_bas;
//            
//            echo '</td>';
//            
//            echo '<td>';
//                $Rev_act = round($Data[$Branch][$cost_id]['Actual']['revenue'],2);
//                $totalArray['Actual']['revenue'] +=$Rev_act;
//                echo $Rev_act;
//            echo '</td>';
//            
//
////                                                    echo '<td>';
////                                                        $Rev_proc = round($Data[$Branch][$cost_id]['Processed']['revenue'],2);
////                                                        echo $Rev_proc;
////                                                    echo '</td>';
//
//            // Direct Cost Details are here
//            echo '<td>';
//                $Dir_asp = round($Data[$Branch][$cost_id]['Asp']['dc'],2);
//                $totalArray['Asp']['dc'] +=$Dir_asp;
//                echo $Dir_asp;
//            echo '</td>';
//
//            
//
//            echo '<td>';
//                $Dir_bas = round($Data[$Branch][$cost_id]['Basic']['dc'],2);
//                $totalArray['Basic']['dc'] +=$Dir_bas;
//                echo $Dir_bas;
//              
//            echo '</td>';
//            
//            echo '<td>';
//                $Dir_act = round($Data[$Branch][$cost_id]['Actual']['dc'],2);
//                $totalArray['Actual']['dc'] +=$Dir_act;
//                echo $Dir_act;
//               
//            echo '</td>';
//
////                                                    echo '<td>';
////                                                        $Dir_proc = round($Data[$Branch][$cost_id]['Processed']['dc'],2);
////                                                        echo $Dir_proc;
////                                                    echo '</td>';
//
//            // InDirect Cost Details are here
//            echo '<td>';
//                $InDir_asp = round($Data[$Branch][$cost_id]['Asp']['idc'],2);
//                $totalArray['Asp']['idc'] +=$InDir_asp;
//                echo $InDir_asp;
//            echo '</td>';
//
//            
//
//            echo '<td>';
//            $InDir_bas = round($Data[$Branch][$cost_id]['Basic']['idc'],2);
//            $totalArray['Basic']['idc'] +=$InDir_bas;
//                echo $InDir_bas;
//               
//            echo '</td>';
//            
//            echo '<td>';
//                $InDir_act = round($Data[$Branch][$cost_id]['Actual']['idc'],2);
//                $totalArray['Actual']['idc'] +=$InDir_act;
//                echo $InDir_act;
//            echo '</td>';
//
////                                                    echo '<td>';
////                                                        $InDir_proc = round($Data[$Branch][$cost_id]['Processed']['idc'],2);
////                                                        echo $InDir_proc;
////                                                    echo '</td>';
//
//            echo '<td>'.round($Rev_asp-$Dir_asp-$InDir_asp,2).'</td>';
//            echo '<td>'.round($Rev_bas-$Dir_bas-$InDir_bas,2).'</td>';
//            echo '<td>'.round($Rev_act-$Dir_act-$InDir_act,2).'</td>';
//            
//            
//            echo '<td>'.round(($Rev_asp-$Dir_asp-$InDir_asp)*100/$Rev_asp,2).'%</td>';
//            echo '<td>'.round(($Rev_bas-$Dir_bas-$InDir_bas)*100/$Rev_bas,2).'%</td>';
//            echo '<td>'.round(($Rev_act-$Dir_act-$InDir_act)*100/$Rev_act,2).'%</td>';
//
////            echo '<td colspan="2">';
////                echo '<span href="#" class="btn btn-danger"  style=" margin-top: 10px;margin-left: 10px;margin-right: 10px;">Freeze</span>';
////            echo '</td>';
//
//        echo '</tr>';
//        
//        
//        
//    }
//    
//    echo '<thead><tr>';
//    echo '<th colspan="2">Total</th>';
//    
//    echo '<th>'.$totalArray['Asp']['revenue'].'</th>';
//    echo '<th>'.$totalArray['Basic']['revenue'].'</th>';
//    echo '<th>'.$totalArray['Actual']['revenue'].'</th>';
//    
//    echo '<th>'.$totalArray['Asp']['dc'].'</th>';
//    echo '<th>'.$totalArray['Basic']['dc'].'</th>';
//    echo '<th>'.$totalArray['Actual']['dc'].'</th>';
//    
//    echo '<th>'.$totalArray['Asp']['idc'].'</th>';
//    echo '<th>'.$totalArray['Basic']['idc'].'</th>';
//    echo '<th>'.$totalArray['Actual']['idc'].'</th>';
//    
//    echo '<th>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</th>';
//    echo '<th>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</th>';
//    echo '<th>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</th>';
//
//
//    echo '<th>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</th>';
//    echo '<th>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</th>';
//    echo '<th>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</th>';
//    
//    echo '</tr></thead>';
//?>
    </table> -->
    
<?php    
//}

 if($ReportType=='Branch')
{?>

<table  <?php  if($type!='export') { ?> class = "table table-striped table-hover  responstable" style="border:2px solid !important;" <?php } ?>  > 
          <thead>
<!--        <tr style="text-align:center">
            <td colspan="15"><b><?php //echo $Branch; ?></b></td>
        </tr>-->
        <tr style="text-align:center">
            <td rowspan="2"><b>Branch</b></td>
            <td colspan="4"><b>Revenue</b></td>
            <td colspan="4"><b>Direct Cost</b></td>
            <td colspan="4"><b>InDirect Cost</b></td>
            <td colspan="4"><b>OP</b></td>
            <td colspan="4"><b>OP%</b></td>
<!--            <td colspan="2" rowspan="2"><b>Status</b></td>-->
        </tr>
        <tr  style="text-align:center;">

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Commit</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Commit</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Commit</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->
            <td><b>Aspirational</b></td>
            <td><b>Basic</b></td>
            <td><b>Commit</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->
            <td><b>Aspira</b></td>
            <td><b>Basic</b></td>
            <td><b>Commit</b></td>
            <td><b>Actual</b></td>
<!--                                        <td><b>Processed</b></td>-->


        </tr>
        </thead>
          <?php foreach($BranchArr as $Branch) { ?>
        
<?php $totalArray = array();
    foreach($CostCenter[$Branch] as $cost_id=>$cost_master)
    {
        //echo '<tr style="text-align:center">';
            // CostCenter Process Name and Cost Center

            // Revenue Details are here
        //    echo '<td>';
                $Rev_asp = round($Data[$Branch][$cost_id]['Asp']['revenue'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
        //        echo $Rev_asp;
              
        //    echo '</td>';
            
            

        //    echo '<td>';
                $Rev_bas = round($Data[$Branch][$cost_id]['Basic']['revenue'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
               // echo $Rev_bas;
            
        //    echo '</td>';
            
        //    echo '<td>';
                $Rev_act = round($Data[$Branch][$cost_id]['Actual']['revenue'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
         //       echo $Rev_act;
        //    echo '</td>';
            


            // Direct Cost Details are here
        //    echo '<td>';
                $Dir_asp = round($Data[$Branch][$cost_id]['Asp']['dc'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
        //        echo $Dir_asp;
        //    echo '</td>';

            

        //    echo '<td>';
                $Dir_bas = round($Data[$Branch][$cost_id]['Basic']['dc'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
        //        echo $Dir_bas;
              
        //    echo '</td>';
            
        //    echo '<td>';
                $Dir_act = round($Data[$Branch][$cost_id]['Actual']['dc'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
        //        echo $Dir_act;
               
        //    echo '</td>';

            // InDirect Cost Details are here
        //    echo '<td>';
                $InDir_asp = round($Data[$Branch][$cost_id]['Asp']['idc'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
        //        echo $InDir_asp;
        //    echo '</td>';

            

        //    echo '<td>';
            $InDir_bas = round($Data[$Branch][$cost_id]['Basic']['idc'],2);
            $totalArray['Basic']['idc'] +=$InDir_bas;
        //        echo $InDir_bas;
               
        //    echo '</td>';
            
        //    echo '<td>';
                $InDir_act = round($Data[$Branch][$cost_id]['Actual']['idc'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
        //        echo $InDir_act;
        //    echo '</td>';



//            echo '<td>'.round($Rev_asp-$Dir_asp-$InDir_asp,2).'</td>';
//            echo '<td>'.round($Rev_bas-$Dir_bas-$InDir_bas,2).'</td>';
//            echo '<td>'.round($Rev_act-$Dir_act-$InDir_act,2).'</td>';
//            
//            
//            echo '<td>'.round(($Rev_asp-$Dir_asp-$InDir_asp)*100/$Rev_asp,2).'%</td>';
//            echo '<td>'.round(($Rev_bas-$Dir_bas-$InDir_bas)*100/$Rev_bas,2).'%</td>';
//            echo '<td>'.round(($Rev_act-$Dir_act-$InDir_act)*100/$Rev_act,2).'%</td>';
//
//
//
//        echo '</tr>';
        
        $totalArray['Commit']['revenue'] +=round($Data[$Branch][$cost_id]['Commit']['revenue'],2);
        $totalArray['Commit']['dc']      +=round($Data[$Branch][$cost_id]['Commit']['dc'],2);
        $totalArray['Commit']['idc']     +=round($Data[$Branch][$cost_id]['Commit']['idc'],2);
        
    }
    
   
    
    echo '<tr>';
    echo '<td><a href="view_process_report?Branch='."$Branch"."&finYear=".$FinanceYear.'&finMonth='.$FinanceMonth.'" >'.$Branch.'</a></td>';
    
    echo '<td>'.$totalArray['Asp']['revenue'].'</td>'; $GrandTotalArray['Asp']['revenue'] +=$totalArray['Asp']['revenue'];
    echo '<td>'.$totalArray['Basic']['revenue'].'</td>';$GrandTotalArray['Basic']['revenue'] +=$totalArray['Basic']['revenue'];
    echo '<td>'.$totalArray['Commit']['revenue'].'</td>';$GrandTotalArray['Commit']['revenue'] +=$totalArray['Commit']['revenue'];
    echo '<td>'.$totalArray['Actual']['revenue'].'</td>';$GrandTotalArray['Actual']['revenue'] +=$totalArray['Actual']['revenue'];
    
    
    echo '<td>'.$totalArray['Asp']['dc'].'</td>'; $GrandTotalArray['Asp']['dc'] +=$totalArray['Asp']['dc'];
    echo '<td>'.$totalArray['Basic']['dc'].'</td>'; $GrandTotalArray['Basic']['dc'] +=$totalArray['Basic']['dc'];
    echo '<td>'.$totalArray['Commit']['dc'].'</td>'; $GrandTotalArray['Commit']['dc'] +=$totalArray['Commit']['dc'];
    echo '<td>'.$totalArray['Actual']['dc'].'</td>'; $GrandTotalArray['Actual']['dc'] +=$totalArray['Actual']['dc'];
    
    
    echo '<td>'.$totalArray['Asp']['idc'].'</td>'; $GrandTotalArray['Asp']['idc'] +=$totalArray['Asp']['idc'];
    echo '<td>'.$totalArray['Basic']['idc'].'</td>'; $GrandTotalArray['Basic']['idc'] +=$totalArray['Basic']['idc'];
    echo '<td>'.$totalArray['Commit']['idc'].'</td>'; $GrandTotalArray['Commit']['idc'] +=$totalArray['Commit']['idc'];
    echo '<td>'.$totalArray['Actual']['idc'].'</td>'; $GrandTotalArray['Actual']['idc'] +=$totalArray['Actual']['idc'];
    
    
    echo '<td>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Commit']['revenue']-$totalArray['Commit']['dc']-$totalArray['Commit']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</td>';


    echo '<td>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Commit']['revenue']-$totalArray['Commit']['dc']-$totalArray['Commit']['idc'])*100/$totalArray['Commit']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</td>';
    
    echo '</tr>';
    
          }
        
          
          
          
    echo '<tr>';
    echo '<th><font color="black"><b>EBIDTA</b></font></th>';
    echo '<td><b>'.$GrandTotalArray['Asp']['revenue'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['revenue'].'</b></td>';
    
    echo '<td><b>'.$GrandTotalArray['Asp']['dc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['dc'].'</b></td>'; 
    
    echo '<td><b>'.$GrandTotalArray['Asp']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Commit']['idc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['idc'].'</b></td>';
    
    echo '<td><b>'.round($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'],2).'</b></td>';
    echo '<td><b>'.round($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'],2).'</b></td>';
    echo '<td><b>'.round($GrandTotalArray['Commit']['revenue']-$GrandTotalArray['Commit']['dc']-$GrandTotalArray['Commit']['idc'],2).'</b></td>';
    echo '<td><b>'.round($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'],2).'</b></td>';


    echo '<td><b>'.round(($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'])*100/$GrandTotalArray['Asp']['revenue'],2).'%</b></td>';
    echo '<td><b>'.round(($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'])*100/$GrandTotalArray['Basic']['revenue'],2).'%</b></td>';
    echo '<td><b>'.round(($GrandTotalArray['Commit']['revenue']-$GrandTotalArray['Commit']['dc']-$GrandTotalArray['Commit']['idc'])*100/$GrandTotalArray['Commit']['revenue'],2).'%</b></td>';
    echo '<td><b>'.round(($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'])*100/$GrandTotalArray['Actual']['revenue'],2).'%</b></td>';
    echo '<tr>';
    
    foreach($dataBranch_det as $details=>$amount)
          {
            echo '<tr style="text-align:center;">';
                echo '<td><b>'.$details.'</b></td>'; 
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td>'.$amount.'</td>'; 
                echo '<td>'.$amount.'</td>';
                echo '<td>'.$amount.'</td>';
                echo '<td>'.$amount.'</td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
               
                echo '<td></td>';

            echo '</tr>';
          }
    
    
    echo '<tr>';
    echo '<th><font color="black"><b>Net</b></font></th>';
    echo '<td><b>'.$GrandTotalArray['Asp']['revenue'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['revenue'].'</b></td>';
    
    echo '<td><b>'.$GrandTotalArray['Asp']['dc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['dc'].'</b></td>'; 
    
    echo '<td><b>'.$GrandTotalArray['Asp']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Commit']['idc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['idc'].'</b></td>';
    
    $op_asp = round($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'],2);
    $op_bas = round($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'],2);
    $op_com = round($GrandTotalArray['Commit']['revenue']-$GrandTotalArray['Commit']['dc']-$GrandTotalArray['Commit']['idc'],2);
    $op_act = round($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'],2);
            foreach($dataBranch_det as $details=>$amount)
          {
                if($dataBranch_opr[$details]=='+')
                {
                    $op_asp += $amount; 
                    $op_bas += $amount;
                    $op_com += $amount;
                    $op_act += $amount;
                }
                else
                {
                   $op_asp -= $amount; 
                    $op_bas -= $amount;
                    $op_com -= $amount;
                    $op_act -= $amount;
                }
          }    
    echo '<td>'.$op_asp.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_asp;
    echo '<td>'.$op_bas.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_bas;
    echo '<td>'.$op_com.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_com;
    echo '<td>'.$op_act.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_act;
    

    echo '<td>'.round($op_asp*100/$GrandTotalArray['Asp']['revenue'],2).'%</td>'; $excel_arr[$rowExcel][$colExcel++] = round($op_asp*100/$GrandTotalArray['Asp']['revenue'],2);
    echo '<td>'.round($op_bas*100/$GrandTotalArray['Basic']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_bas*100/$GrandTotalArray['Basic']['revenue'],2);
    echo '<td>'.round($op_com*100/$GrandTotalArray['Commit']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_com*100/$GrandTotalArray['Commit']['revenue'],2);
    echo '<td>'.round($op_act*100/$GrandTotalArray['Actual']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_act*100/$GrandTotalArray['Actual']['revenue'],2);
    echo '<tr>';
    
?>
    </table>

<br/>
<br/>
<h5 font color="blue"><u><b>Freezed Business Dashboard Report</b></u></h5>

    <?php if(!empty($Freeze_Data1)) {  $totalArray = array(); $GrandTotalArray = array(); ?>  
    <table <?php  if($type!='export') { ?> class = "table table-striped table-hover  responstable" <?php } ?>  > 
          <thead>

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
          <?php $GrandTotalArray= array(); foreach($BranchArr1 as $Branch) { ?>
        

<?php        $totalArray = array(); 
    foreach($Freeze_Data1[$Branch] as $Freeze)
    {
        //echo '<tr style="text-align:center">';
            // CostCenter Process Name and Cost Center

            // Revenue Details are here
        //    echo '<td>';
                $Rev_asp = round($Freeze['dfds']['Rev_Asp'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
        //        echo $Rev_asp;
              
        //    echo '</td>';
            
            

        //    echo '<td>';
                $Rev_bas = round($Freeze['dfds']['Rev_Bas'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
               // echo $Rev_bas;
            
        //    echo '</td>';
            
        //    echo '<td>';
                $Rev_act = round($Freeze['dfds']['Rev_Act'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
         //       echo $Rev_act;
        //    echo '</td>';
            


            // Direct Cost Details are here
        //    echo '<td>';
                $Dir_asp = round($Freeze['dfds']['Dir_Asp'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
        //        echo $Dir_asp;
        //    echo '</td>';

            

        //    echo '<td>';
                $Dir_bas = round($Freeze['dfds']['Dir_Bas'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
        //        echo $Dir_bas;
              
        //    echo '</td>';
            
        //    echo '<td>';
                $Dir_act = round($Freeze['dfds']['Dir_Act'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
        //        echo $Dir_act;
               
        //    echo '</td>';

            // InDirect Cost Details are here
        //    echo '<td>';
                $InDir_asp = round($Freeze['dfds']['InDir_Asp'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
        //        echo $InDir_asp;
        //    echo '</td>';

            

        //    echo '<td>';
            $InDir_bas = round($Freeze['dfds']['InDir_Bas'],2);
            $totalArray['Basic']['idc'] +=$InDir_bas;
        //        echo $InDir_bas;
               
        //    echo '</td>';
            
        //    echo '<td>';
                $InDir_act = round($Freeze['dfds']['InDir_Act'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
        //        echo $InDir_act;
        //    echo '</td>';

    }
    
    echo '<tr>';
    echo '<td><a href="view_process_report_freezed?Branch='."$Branch"."&finYear=".$FinanceYear.'&finMonth='.$FinanceMonth.'" >'.$Branch.'</a></td>';
    
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
    echo '<td><b>EBIDTA<b></td>';
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
    
    
    foreach($dataBranch_det as $details=>$amount)
          {
            echo '<tr style="text-align:center;">';
                echo '<td><b>'.$details.'</b></td>'; 
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
                echo '<td>'.$amount.'</td>'; 
                echo '<td>'.$amount.'</td>';
                echo '<td>'.$amount.'</td>';
                echo '<td>'.$amount.'</td>';
                
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                
               
                echo '<td></td>';

            echo '</tr>';
          }
    
    echo '<tr>';
    echo '<th><font color="black"><b>Net</b></font></th>';
    echo '<td><b>'.$GrandTotalArray['Asp']['revenue'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['revenue'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['revenue'].'</b></td>';
    
    echo '<td><b>'.$GrandTotalArray['Asp']['dc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Commit']['dc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['dc'].'</b></td>'; 
    
    echo '<td><b>'.$GrandTotalArray['Asp']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Basic']['idc'].'</b></td>'; 
    echo '<td><b>'.$GrandTotalArray['Commit']['idc'].'</b></td>';
    echo '<td><b>'.$GrandTotalArray['Actual']['idc'].'</b></td>';
    
    $op_asp = round($GrandTotalArray['Asp']['revenue']-$GrandTotalArray['Asp']['dc']-$GrandTotalArray['Asp']['idc'],2);
    $op_bas = round($GrandTotalArray['Basic']['revenue']-$GrandTotalArray['Basic']['dc']-$GrandTotalArray['Basic']['idc'],2);
    $op_com = round($GrandTotalArray['Commit']['revenue']-$GrandTotalArray['Commit']['dc']-$GrandTotalArray['Commit']['idc'],2);
    $op_act = round($GrandTotalArray['Actual']['revenue']-$GrandTotalArray['Actual']['dc']-$GrandTotalArray['Actual']['idc'],2);
            foreach($dataBranch_det as $details=>$amount)
          {
                if($dataBranch_opr[$details]=='+')
                {
                    $op_asp += $amount; 
                    $op_bas += $amount;
                    $op_com += $amount;
                    $op_act += $amount;
                }
                else
                {
                   $op_asp -= $amount; 
                    $op_bas -= $amount;
                    $op_com -= $amount;
                    $op_act -= $amount;
                }
          }    
    echo '<td>'.$op_asp.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_asp;
    echo '<td>'.$op_bas.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_bas;
    echo '<td>'.$op_com.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_com;
    echo '<td>'.$op_act.'</td>';$excel_arr[$rowExcel][$colExcel++] = $op_act;
    

    echo '<td>'.round($op_asp*100/$GrandTotalArray['Asp']['revenue'],2).'%</td>'; $excel_arr[$rowExcel][$colExcel++] = round($op_asp*100/$GrandTotalArray['Asp']['revenue'],2);
    echo '<td>'.round($op_bas*100/$GrandTotalArray['Basic']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_bas*100/$GrandTotalArray['Basic']['revenue'],2);
    echo '<td>'.round($op_com*100/$GrandTotalArray['Commit']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_com*100/$GrandTotalArray['Commit']['revenue'],2);
    echo '<td>'.round($op_act*100/$GrandTotalArray['Actual']['revenue'],2).'%</td>';$excel_arr[$rowExcel][$colExcel++] = round($op_act*100/$GrandTotalArray['Actual']['revenue'],2);
    echo '<tr>';
?>
    </table> 
<?php    
}
}
?>