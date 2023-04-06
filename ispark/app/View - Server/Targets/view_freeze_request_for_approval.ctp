<?php

?>


<script>
    function approve_freeze_request(Branch,FinanceYear,FinanceMonth,RemarksId)
    {
        var RemarksInput = '#Remarks'+RemarksId;
        if($(RemarksInput).val()=='')
        {
            alert("Please Fill Remarks First");
        }
        else
        {
            //alert($(RemarksInput).val());
            var Remarks = $(RemarksInput).val();
            $.post("freeze_branch",
            {
             Branch:Branch,
             FinanceYear: FinanceYear,
             FinanceMonth:FinanceMonth,
             Remarks:Remarks
            },
            function(data,status){
              if(data==1)
              {
                  alert("Branch Record Freezed Successfully");
                  location.reload();
              }
              else
              {
                  alert(data);
                  alert("Records Not Updated. Please Try Again");
              }
            }); 
            
        }
    }
    
    function disapprove_freeze_request(Branch,FinanceYear,FinanceMonth,RemarksId)
    {
        var RemarksInput = '#Remarks'+RemarksId;
        if($(RemarksInput).val()=='')
        {
            alert("Please Fill Remarks First");
        }
        else
        {
            //alert($(RemarksInput).val());
            var Remarks = $(RemarksInput).val();
            $.post("disapprove_feeze_request",
            {
             Branch:Branch,
             FinanceYear: FinanceYear,
             FinanceMonth:FinanceMonth,
             Remarks:Remarks
            },
            function(data,status){
              if(data==1)
              {
                  alert("Record DisApproved Successfully");
                  location.reload();
              }
              else
              {
                  //alert(data);
                  alert("Records Not DisApproved. Please Try Again");
              }
            }); 
            
        }
    }
</script>
<style>
    table td{text-align: center!important;}
    table th{text-align: center!important;text-transform: capitalize!important;}
    
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border: 2px solid #bbb;
}

</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span>View & Approve Business Dashboard </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" style="overflow:auto">
               <h4><?php echo $this->Session->flash(); ?></h4> 
		<table border="2" class = "table table-striped table-hover  responstable">
                     <thead>
                        <tr style="text-align:center">
                            <td colspan="1" rowspan="2"><b>Sr.No.</b></td>
                            <td rowspan="2">Branch</td> 
                            
                            <td rowspan="2">Finance Month</td>
                            <td colspan="3"><b>Revenue</b></td>
                            <td colspan="3"><b>Direct Cost</b></td>
                            <td colspan="3"><b>InDirect Cost</b></td>
                            <td colspan="3"><b>OP</b></td>
                            <td colspan="3"><b>OP%</b></td>
                            <td rowspan="2" colspan="2"><b>Action Approve</b></td>
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
                            

                        </tr>
                    </thead>
                   <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($data as $dt)
                                {
                                    $Branch = $dt['dfd']['Branch'];
                                    $FinanceYear = $dt['dfd']['FinanceYear'];
                                    $FinanceMonth = $dt['dfd']['FinanceMonth'];
                                    
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo '<td><a href="view_freeze_data?Branch='."$Branch"."&finYear=".$FinanceYear.'&finMonth='.$FinanceMonth.'" >'.$dt['dfd']['Branch']."</a></td>";
                                        
                                        echo "<td>".$dt['dfd']['FinanceMonth']."</td>";
                                        
                                        echo "<td>".round($dt['0']['Rev_Asp'],2)."</td>"; $totalArray['Rev_Asp'] +=round($dt['0']['Rev_Asp'],2);
                                        echo "<td>".round($dt['0']['Rev_Bas'],2)."</td>";$totalArray['Rev_Bas'] +=round($dt['0']['Rev_Bas'],2);
                                        echo "<td>".round($dt['0']['Rev_Act'],2)."</td>";$totalArray['Rev_Act'] +=round($dt['0']['Rev_Act'],2);
                                        
                                        
                                        echo "<td>".round($dt['0']['Dir_Asp'],2)."</td>";$totalArray['Dir_Asp'] +=round($dt['0']['Dir_Asp'],2);
                                        echo "<td>".round($dt['0']['Dir_Bas'],2)."</td>";$totalArray['Dir_Bas'] +=round($dt['0']['Dir_Bas'],2);
                                        echo "<td>".round($dt['0']['Dir_Act'],2)."</td>";$totalArray['Dir_Act'] +=round($dt['0']['Dir_Act'],2);
                                        
                                        
                                        echo "<td>".round($dt['0']['InDir_Asp'],2)."</td>";$totalArray['InDir_Asp'] +=round($dt['0']['InDir_Asp'],2);
                                        echo "<td>".round($dt['0']['InDir_Bas'],2)."</td>";$totalArray['InDir_Bas'] +=round($dt['0']['InDir_Bas'],2);
                                        echo "<td>".round($dt['0']['InDir_Act'],2)."</td>";$totalArray['InDir_Act'] +=round($dt['0']['InDir_Act'],2);
                                        
                                        
                                        
                                        echo "<td>".round($dt['0']['Rev_Asp']-$dt['0']['Dir_Asp']-$dt['0']['InDir_Asp'],2)."</td>"; 
                                        echo "<td>".round($dt['0']['Rev_Bas']-$dt['0']['Dir_Bas']-$dt['0']['InDir_Bas'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Act']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'],2)."</td>";
                                        
                                        echo "<td>".round(($dt['0']['Rev_Asp']-$dt['0']['Dir_Asp']-$dt['0']['InDir_Asp'])*100/$dt['0']['Rev_Asp'],2)."%</td>";
                                        echo "<td>".round(($dt['0']['Rev_Bas']-$dt['0']['Dir_Bas']-$dt['0']['InDir_Bas'])*100/$dt['0']['Rev_Bas'],2)."%</td>";
                                        echo "<td>".round(($dt['0']['Rev_Act']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'])*100/$dt['0']['Rev_Act'],2)."%</td>";
                                        
                                        echo '<td><a href="#" onclick="approve_freeze_request('."'$Branch','$FinanceYear','$FinanceMonth','$i'".')">Approve</a></td>';
                                        echo '<td><a href="#" onclick="disapprove_freeze_request('."'$Branch','$FinanceYear','$FinanceMonth','$i'".')">Reject</a></td>';
                                    echo "</tr>";
                                }
                        ?>
                       <tr>
                           <th colspan="3"><font color="black">Total</font></th>
                        <?php
                        
                            echo '<td>'.$totalArray['Rev_Asp'].'</td>';
                            echo '<td>'.$totalArray['Rev_Bas'].'</td>';
                            echo '<td>'.$totalArray['Rev_Act'].'</td>';

                            echo '<td>'.$totalArray['Dir_Asp'].'</td>';
                            echo '<td>'.$totalArray['Dir_Bas'].'</td>';
                            echo '<td>'.$totalArray['Dir_Act'].'</td>';

                            echo '<td>'.$totalArray['InDir_Asp'].'</td>';
                            echo '<td>'.$totalArray['InDir_Bas'].'</td>';
                            echo '<td>'.$totalArray['InDir_Act'].'</td>';

                            echo "<td>".round($totalArray['Rev_Asp']-$totalArray['Dir_Asp']-$totalArray['InDir_Asp'],2)."</td>"; 
                            echo "<td>".round($totalArray['Rev_Bas']-$totalArray['Dir_Bas']-$totalArray['InDir_Bas'],2)."</td>";
                            echo "<td>".round($totalArray['Rev_Act']-$totalArray['Dir_Act']-$totalArray['InDir_Act'],2)."</td>";

                            echo "<td>".round(($totalArray['Rev_Asp']-$totalArray['Dir_Asp']-$totalArray['InDir_Asp'])*100/$totalArray['Rev_Asp'],2)."%</td>";
                            echo "<td>".round(($totalArray['Rev_Bas']-$totalArray['Dir_Bas']-$totalArray['InDir_Bas'])*100/$totalArray['Rev_Bas'],2)."%</td>";
                            echo "<td>".round(($totalArray['Rev_Act']-$totalArray['Dir_Act']-$totalArray['InDir_Act'])*100/$totalArray['Rev_Act'],2)."%</td>";
                            
                            echo '<td colspan="2"></td>';
                        
                        ?>
                       </tr>
                       
                    </tbody>
                    
                </table> 	
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>


 

