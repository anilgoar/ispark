<?php ?>

  


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
		<div id="social" class="pull-right">
			
		</div>
	</div>
</div>

<div class="box-content" style="overflow:auto;">
    <h4 class="page-header">Process Wise Report</h4>
	
					
    <table border="2" id="table"  class = "table table-striped table-hover  responstable"    >
        <thead>
        <tr style="text-align:center">
            <th colspan="2"><b>Process</b></th>
            <th colspan="3"><b>Revenue</b></th>
            <th colspan="3"><b>Direct Cost</b></th>
            <th colspan="3"><b>InDirect Cost</b></th>
            <th colspan="3"><b>OP</b></th>
            <th colspan="3"><b>OP%</b></th>
              <th rowsspan="2"><b>Last Updated Date</b></th>
        </tr>
        <tr  style="text-align:center;">
            <td><b>Process Name</b></td>
            <td><b>Cost Center</b></td>

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                        

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                       

            <td><b>Aspirational</b></td>
            
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                       
            <td><b>Aspirational</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                      
            <td><b>Aspira</b></td>
            <td><b>Basic</b></td>
            <td><b>Actual</b></td>
                                       


        </tr>
        </thead>
<?php
    foreach($CostCenter as $cost_id=>$cost_master)
    {
        echo '<tr style="text-align:center">';
            // CostCenter Process Name and Cost Center
            echo '<td>';
                echo ''.$cost_master['PrcoessName'].'</a>';
            echo '</td>';
            echo '<td>';
                echo $cost_master['CostCenter'];
            echo '</td>';

            // Revenue Details are here
            echo '<td>';
                $Rev_asp = round($Data[$cost_id]['Asp']['revenue'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
                echo $Rev_asp;
              
            echo '</td>';
            
            

            echo '<td>';
                $Rev_bas = round($Data[$cost_id]['Basic']['revenue'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
                echo $Rev_bas;
            
            echo '</td>';
            
            echo '<td>';
                $Rev_act = round($Data[$cost_id]['Actual']['revenue'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
                echo $Rev_act;
            echo '</td>';
            

//                                                    echo '<td>';
//                                                        $Rev_proc = round($Data[$cost_id]['Processed']['revenue'],2);
//                                                        echo $Rev_proc;
//                                                    echo '</td>';

            // Direct Cost Details are here
            echo '<td>';
                $Dir_asp = round($Data[$cost_id]['Asp']['dc'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
                echo $Dir_asp;
            echo '</td>';

            

            echo '<td>';
                $Dir_bas = round($Data[$cost_id]['Basic']['dc'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
                echo $Dir_bas;
              
            echo '</td>';
            
            echo '<td>';
                $Dir_act = round($Data[$cost_id]['Actual']['dc'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
                echo $Dir_act;
               
            echo '</td>';

//                                                    echo '<td>';
//                                                        $Dir_proc = round($Data[$cost_id]['Processed']['dc'],2);
//                                                        echo $Dir_proc;
//                                                    echo '</td>';

            // InDirect Cost Details are here
            echo '<td>';
                $InDir_asp = round($Data[$cost_id]['Asp']['idc'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
                echo $InDir_asp;
            echo '</td>';

            

            echo '<td>';
            $InDir_bas = round($Data[$cost_id]['Basic']['idc'],2);
            $totalArray['Basic']['idc'] +=$InDir_bas;
                echo $InDir_bas;
               
            echo '</td>';
            
            echo '<td>';
                $InDir_act = round($Data[$cost_id]['Actual']['idc'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
                echo $InDir_act;
            echo '</td>';

//                                                    echo '<td>';
//                                                        $InDir_proc = round($Data[$cost_id]['Processed']['idc'],2);
//                                                        echo $InDir_proc;
//                                                    echo '</td>';

            echo '<td>'.round($Rev_asp-$Dir_asp-$InDir_asp,2).'</td>';
            echo '<td>'.round($Rev_bas-$Dir_bas-$InDir_bas,2).'</td>';
            echo '<td>'.round($Rev_act-$Dir_act-$InDir_act,2).'</td>';
            
            
            echo '<td>'.round(($Rev_asp-$Dir_asp-$InDir_asp)*100/$Rev_asp,2).'%</td>';
            echo '<td>'.round(($Rev_bas-$Dir_bas-$InDir_bas)*100/$Rev_bas,2).'%</td>';
            echo '<td>'.round(($Rev_act-$Dir_act-$InDir_act)*100/$Rev_act,2).'%</td>';

//            echo '<td colspan="2">';
//                echo '<span href="#" class="btn btn-danger"  style=" margin-top: 10px;margin-left: 10px;margin-right: 10px;">Freeze</span>';
//            echo '</td>';
            echo '<td>'.$LastUpdatedDate[$cost_id].'</td>';
        echo '</tr>';
        
        
        
    }
    
    echo '<thead><tr>';
    echo '<th colspan="2">Total</th>';
    
    echo '<th>'.$totalArray['Asp']['revenue'].'</th>';
    echo '<th>'.$totalArray['Basic']['revenue'].'</th>';
    echo '<th>'.$totalArray['Actual']['revenue'].'</th>';
    
    echo '<th>'.$totalArray['Asp']['dc'].'</th>';
    echo '<th>'.$totalArray['Basic']['dc'].'</th>';
    echo '<th>'.$totalArray['Actual']['dc'].'</th>';
    
    echo '<th>'.$totalArray['Asp']['idc'].'</th>';
    echo '<th>'.$totalArray['Basic']['idc'].'</th>';
    echo '<th>'.$totalArray['Actual']['idc'].'</th>';
    
    echo '<th>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</th>';
    echo '<th>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</th>';
    echo '<th>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</th>';


    echo '<th>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</th>';
    echo '<th>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</th>';
    echo '<th>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</th>';
    echo '<th></th>';
    echo '</tr></thead>';
?>
    </table>                            
                                   
    <div class="clearfix"></div>
    <div class="form-group">
        <label class="col-sm-5 control-label"></label>
            <div class="col-sm-1">
                <button name="Export" id="Export" class="btn btn-primary" onclick="exportTableToExcel('table','process_wise_budget_report')">Export</button>
            </div>
            <div class="col-sm-1">
                     <a href="<?php echo $this->webroot.'Dashs/view' ?>" class="btn btn-primary" >Back</a>
            </div>
            
    </div>		
     
    <div class="form-horizontal">
        <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="pop_up_close()">&times;</span>
            <div id="show_table_data"></div>
        </div>
        </div>
    </div>
    
</div>
<script>
    function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>