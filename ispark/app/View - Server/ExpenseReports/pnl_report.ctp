<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("<?php echo $this->webroot;?>/ExpenseEntries/get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subhead").empty();
                $("#subhead").html(text);
                
            });  
}
</script>

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
                    
                    <span>P&L Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                 <?php echo $this->Form->create('Expense',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => array_merge(array('All'=>'All'),$financeYearArr),'empty' => 'Select Year','required'=>true));
                                ?>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                                <?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                                ?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                             <input type="submit" name="Show" value="Show" onclick="return export_pnl_validate('Show')" class="btn btn-info" />
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Export" value="Export" onclick="return export_pnl_validate('Export')" class="btn btn-info" />
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content"  style="overflow:scroll;width: 3000px;" id="data">
                <table border="2" class="table">
                    
                        <?php $i=1; //print_r($PNLreport); exit;
                        
                                foreach($PNLreport as $pnl)
                                {
                                    $costArray[] = $pnl['tab']['ExpenseTypeName']; 
                                    $costName[$pnl['tab']['ExpenseTypeName']] = $pnl['cm2']['CostCenterName']; 
                                    $HeadArray[] = $pnl['tab']['HeadingDesc'];
                                    $data[$pnl['tab']['HeadingDesc']][$pnl['tab']['ExpenseTypeName']] = $pnl;
                                }
                                //print_r($data); exit;
                                $costArray = array_unique($costArray);
                                $HeadArray = array_unique($HeadArray);
                                
                                echo '<thead><tr><th rowspan="2">S.No.</th><th rowspan="2">Expense Head</th>';
                                
                                foreach($costArray as $cost)
                                {
                                    echo '<th colspan="3">'.$costName[$cost].'</th>';
                                }
                                echo '<th colspan="3">Grand Total</th></tr><tr>';
                                
                                foreach($costArray as $cost)
                                {
                                    echo '<th>Processed</th>';
                                    echo '<th>UnProcessed</th>';
                                    echo '<th>Total</th>';
                                }
                                echo '<th>Total Processed</th><th>Total UnProcessed</th><th>Total_Total</th></tr></thead><tbody>';
                                //print_r($data); 
                                foreach($data as $head=>$v)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$head."</td>";
                                        $rowTotal = 0;
                                        $rowProcessed = 0;
                                        //print_r($v);
                                        foreach($costArray as $cost)
                                        {
                                            $process = !empty($v[$cost]['0']['Processed'])?$v[$cost]['0']['Processed']:0;
                                            $Total =  !empty($v[$cost]['0']['Total'])?round($v[$cost]['0']['Total'],2):0;
                                            echo "<td>".round($process,2).'</td>';
                                            echo "<td>".round($Total-$process,2).'</td>';
                                            echo "<td>".($Total).'</td>';
                                            $rowTotal += $Total;
                                            $rowProcessed += round($process,2);
                                            $dataCostArray[$cost]['Total'] +=  $Total;
                                            $dataCostArray[$cost]['Processed'] +=  $process;
                                        }
                                        echo "<td>".round($rowProcessed,2)."</td>";
                                        echo "<td>".round($rowTotal-$rowProcessed,2).'</td>';
                                        echo "<td>".round($rowTotal,2).'</td>';
                                    echo "</tr>";
                                }
                                
                                echo '<tr><th colspan="2">Grand Total</th>';
                                foreach($costArray as $cost)
                                {
                                    echo '<th>'.round($dataCostArray[$cost]['Processed'],2).'</th>';
                                    echo '<th>'.round($dataCostArray[$cost]['Total']-$dataCostArray[$cost]['Processed'],2).'</th>';
                                    echo '<th>'.round($dataCostArray[$cost]['Total'],2).'</th>';
                                    $GrandTotalProcessed +=  $dataCostArray[$cost]['Processed'];
                                    $GrandTotalTotal +=  $dataCostArray[$cost]['Total'];
                                }
                                
                                echo '<th>'.round($GrandTotalProcessed,2).'</th>';
                                echo '<th>'.round($GrandTotalTotal-$GrandTotalProcessed,2).'</th>';
                                echo '<th>'.round($GrandTotalTotal,2).'</th></tr>';
                        ?>
                    </tbody>
                </table>    
            
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

