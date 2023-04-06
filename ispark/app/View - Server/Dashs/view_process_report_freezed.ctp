<?php ?>

  


<style>
    table td{text-align: center!important;}
    table th{text-align: center!important;text-transform: capitalize!important;}
    
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border: 2px solid #bbb;
}

</style>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

table th,td{text-align: center;font-size: 12px;}

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
	
					
   
<?php if(!empty($Freeze_Data)) {  $totalArray = array(); $GrandTotalArray = array(); ?>  
    <table <?php  if($type!='export') 
        { ?> class = "table table-striped table-hover  responstable" <?php } ?>  > 
          <thead>

        <tr style="text-align:center">
            <td colspan="2"><b>Process</b></td>
            <td colspan="3"><b>Revenue</b></td>
            <td colspan="3"><b>Direct Cost</b></td>
            <td colspan="3"><b>InDirect Cost</b></td>
            <td colspan="3"><b>OP</b></td>
            <td colspan="3"><b>OP%</b></td>
<!--            <td colspan="2" rowspan="2"><b>Status</b></td>-->
        </tr>
        <tr  style="text-align:center;">
            
            <td>Process</td>
            <td>CostCenter</td>
            
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
          
        

<?php        
    foreach($Freeze_Data as $Freeze)
    {
        $cost_id = $Freeze['dfds']['CostCenterId'];
        echo '<tr style="text-align:center">';
            // CostCenter Process Name and Cost Center
        echo '<td>';
        echo $Freeze['cm']['process_name'];
        echo '</td>';
        echo '<td>';
        echo substr($Freeze['cm']['cost_center'],-7);
        echo '</td>';
        
            // Revenue Details are here
            echo '<td>';
                $Rev_asp = round($Freeze['dfds']['Rev_Asp'],2);
                $totalArray['Asp']['revenue'] +=$Rev_asp;
            echo $Rev_asp;
              
           echo '</td>';
            
            

            echo '<td>';
                $Rev_bas = round($Freeze['dfds']['Rev_Bas'],2);
                $totalArray['Basic']['revenue'] +=$Rev_bas;
            echo $Rev_bas;
            echo '</td>';
            
            echo '<td>';
                $Rev_act = round($Freeze['dfds']['Rev_Act'],2);
                $totalArray['Actual']['revenue'] +=$Rev_act;
            echo $Rev_act;
            echo '</td>';
            


            // Direct Cost Details are here
            echo '<td>';
                $Dir_asp = round($Freeze['dfds']['Dir_Asp'],2);
                $totalArray['Asp']['dc'] +=$Dir_asp;
            echo $Dir_asp;
            echo '</td>';

            

            echo '<td>';
                $Dir_bas = round($Freeze['dfds']['Dir_Bas'],2);
                $totalArray['Basic']['dc'] +=$Dir_bas;
                echo $Dir_bas;
           echo '</td>';
            
            echo '<td>';
                $Dir_act = round($Freeze['dfds']['Dir_Act'],2);
                $totalArray['Actual']['dc'] +=$Dir_act;
            echo '<a herf="#" onclick="show_details('."'$Dir_act',"."'DirectActual','$cost_id','$finYear','$finMonth','basic'".')">'.$Dir_act.'</a>';
               
           echo '</td>';

            // InDirect Cost Details are here
            echo '<td>';
                $InDir_asp = round($Freeze['dfds']['InDir_Asp'],2);
                $totalArray['Asp']['idc'] +=$InDir_asp;
            echo $InDir_asp;
            echo '</td>';

            

            echo '<td>';
                $InDir_bas = round($Freeze['dfds']['InDir_Bas'],2);
                $totalArray['Basic']['idc'] +=$InDir_bas;
            echo $InDir_bas;
            echo '</td>';
           
            echo '<td>';
                $InDir_act = round($Freeze['dfds']['InDir_Act'],2);
                $totalArray['Actual']['idc'] +=$InDir_act;
            echo '<a herf="#" onclick="show_details('."'$InDir_act',"."'InDirectBasic','$cost_id','$finYear','$finMonth','basic'".')">'.$InDir_act.'</a>';
            echo '</td>';
            
        echo '<td>'.round($Freeze['dfds']['Rev_Asp']-$Freeze['dfds']['Dir_Asp']-$Freeze['dfds']['InDir_Asp'],2).'</td>';
    echo '<td>'.round($Freeze['dfds']['Rev_Bas']-$Freeze['dfds']['Dir_Bas']-$Freeze['dfds']['InDir_Bas'],2).'</td>';
    echo '<td>'.round($Freeze['dfds']['Rev_Act']-$Freeze['dfds']['Dir_Act']-$Freeze['dfds']['InDir_Act'],2).'</td>';


    echo '<td>'.round(($Freeze['dfds']['Rev_Asp']-$Freeze['dfds']['Dir_Asp']-$Freeze['dfds']['InDir_Asp'])*100/$Freeze['dfds']['Rev_Asp'],2).'%</td>';
    echo '<td>'.round(($Freeze['dfds']['Rev_Bas']-$Freeze['dfds']['Dir_Bas']-$Freeze['dfds']['InDir_Bas'])*100/$Freeze['dfds']['Rev_Bas'],2).'%</td>';
    echo '<td>'.round(($Freeze['dfds']['Rev_Act']-$Freeze['dfds']['Dir_Act']-$Freeze['dfds']['InDir_Act'])*100/$Freeze['dfds']['Rev_Act'],2).'%</td>';    
            
        echo "</tr>";    
    }
    
echo '<tr>';
    echo '<td colspan="2">Total</td>';
    echo '<td>'.$totalArray['Asp']['revenue'].'</td>'; 
    echo '<td>'.$totalArray['Basic']['revenue'].'</td>';
    echo '<td>'.$totalArray['Actual']['revenue'].'</td>';
    
    echo '<td>'.$totalArray['Asp']['dc'].'</td>'; 
    echo '<td>'.$totalArray['Basic']['dc'].'</td>'; 
    echo '<td>'.$totalArray['Actual']['dc'].'</td>'; 
    
    echo '<td>'.$totalArray['Asp']['idc'].'</td>'; 
    echo '<td>'.$totalArray['Basic']['idc'].'</td>'; 
    echo '<td>'.$totalArray['Actual']['idc'].'</td>';
    
    echo '<td>'.round($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'],2).'</td>';
    echo '<td>'.round($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'],2).'</td>';


    echo '<td>'.round(($totalArray['Asp']['revenue']-$totalArray['Asp']['dc']-$totalArray['Asp']['idc'])*100/$totalArray['Asp']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Basic']['revenue']-$totalArray['Basic']['dc']-$totalArray['Basic']['idc'])*100/$totalArray['Basic']['revenue'],2).'%</td>';
    echo '<td>'.round(($totalArray['Actual']['revenue']-$totalArray['Actual']['dc']-$totalArray['Actual']['idc'])*100/$totalArray['Actual']['revenue'],2).'%</td>';
    echo '<tr>';
    
          }
          
    
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

function show_details(amt,type,cost_id,finyear,finmonth,type1)
{
    document.getElementById('show_table_data').innerHTML = '';
    if(type=='Revenue')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'RevAsp').val();
        bas = $('#Targets'+cost_id+'RevBas').val();
        act = $('#Targets'+cost_id+'RevAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             amt:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if( type=='ActualDirect')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'DirAsp').val();
        bas = $('#Targets'+cost_id+'DirBas').val();
        act = $('#Targets'+cost_id+'DirAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if( type=='ActualInDirect')
    {
        var bas=0;var act=0;var asp=0;
        asp = $('#Targets'+cost_id+'InDirAsp').val();
        bas = $('#Targets'+cost_id+'InDirBas').val();
        act = $('#Targets'+cost_id+'InDirAct').val();
        
        $.post("get_actual_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             type:type1,
             bas:bas,
             act:act,
             asp:asp,
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if(type=='DirectActual')
    {
        $.post("get_basic_direct_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             Branch:'<?php echo $Branch;?>',
             ActualAmount:amt
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    else if(type=='InDirectBasic')
    {
        //alert(finmonth);
        //var Branch=$()
        $.post("get_basic_indirect_data",
            {
             cost_id:cost_id,
             finyear:finyear,
             finmonth:finmonth,
             Branch:'<?php echo $Branch;?>',
             ActualAmount:amt
             
            },
            function(data,status){
              document.getElementById('show_table_data').innerHTML = data; 
            }); 
    }
    var modal = document.getElementById('myModal');
    modal.style.display = "block";
}

function pop_up_close()
{
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
    location.reload();
}
</script>