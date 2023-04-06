<?php

?>
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
                    
                    <span>Budget Report</span>
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
                 <?php echo $this->Form->create('Expense'); ?>   
                    <div id="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => array_merge(array('All'=>'All'),$financeYearArr),'empty' => 'Select Year','required'=>true));
                                ?>
                            </div>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div id="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                            <button class="btn btn-info btn-label-left" onClick="return budget_validate12('Show');">Show</button>
                        </div>
                        <div class="col-sm-2">
				<button class="btn btn-info btn-label-left" onClick="return budget_validate12('Export');">Export</button>
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
            <div class="box-content" id="data">
                <table border="2">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Branch</th>
                            <th>Entry No</th>
                            <th>Finance Year</th>
                            <th>Finance Month</th>
                            <th>Head</th>
                            <th>Sub Head</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($ExpenseReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>$i</td>";
                                        echo "<td>".$exp['0']['Branch']."</td>";
                                        echo "<td>".$exp['0']['EntryNo']."</td>";
                                        echo "<td>".$exp['0']['FinanceYear']."</td>";
                                        echo "<td>".$exp['0']['FinanceMonth']."</td>";
                                        echo "<td>".$exp['0']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['0']['Amount']."</td>";
                                        echo "<td>".$exp['0']['date']."</td>";
                                        if($exp['0']['bus_status']!='Closed')
                                        echo '<td style="background-color:red"><b>'.$exp['0']['bus_status']."</b></td>";
                                        else
                                         echo '<td style="background-color:green"><b>'.$exp['0']['bus_status']."</b></td>";   
                                    echo "</tr>";
                                    $Total += $exp['0']['Amount'];
                                }
                                echo "<tr>";
                                    echo '<td colspan="7"><b>Total</b></td>';
                                    echo '<td><b>'.$Total.'</b></td>';
                                    echo '<td colspan="2"></td>';
                                echo "</tr>";
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

