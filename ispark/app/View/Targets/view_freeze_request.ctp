<?php

?>
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

table th,td{text-align: center;font-size: 13px;}

</style>
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
                    
                    <span>View Business Dashboard </span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
               <h4><?php echo $this->Session->flash(); ?></h4> 
		<div class="form-horizontal">
                 <?php echo $this->Form->create('Targets'); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','value'=>$FinBranch,'required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => $financeYearArr,'empty' => 'Select Year','value'=>$FinYear,'required'=>true));
                                ?>
                            </div>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                            <div id="monthID">
                                <?php	$month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','options' => $month,'empty' => 'Select Month','value'=>$FinMonth,'required'=>true));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                            <button class="btn btn-info btn-label-left" >Submit</button>
                        </div>
                        
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                   
                    </div>		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
            <?php if(!empty($Disdata)) { ?>
            
            <table border="2" class = "table table-striped table-hover  responstable">
                     <thead>
                        <tr style="text-align:center">
                            <td colspan="1" rowspan="2"><b>Sr.No.</b></td>
                            <td rowspan="2"><b>Branch</b></td> 
                            
                            <td rowspan="2"><b>Finance Month</b></td>
                            <td colspan="4"><b>Revenue</b></td>
                            <td colspan="4"><b>Direct Cost</b></td>
                            <td colspan="4"><b>InDirect Cost</b></td>
                            <td colspan="4"><b>OP</b></td>
                            <td colspan="4"><b>OP%</b></td>
                            <td rowspan="2" colspan="2"><b>Status</b></td>
                <!--            <td colspan="2" rowspan="2"><b>Status</b></td>-->
                        </tr>
                        <tr  style="text-align:center;">
                            
                            <td><b>Aspi</b></td>
                            <td><b>Actual</b></td>
                            <td><b>Commit</b></td>
                            <td><b>Basic</b></td>
                <!--                                        <td><b>Processed</b></td>-->

                            <td><b>Aspi</b></td>
                            <td><b>Actual</b></td>
                            <td><b>Commit</b></td>
                            <td><b>Basic</b></td>
                <!--                                        <td><b>Processed</b></td>-->

                            <td><b>Aspi</b></td>
                            <td><b>Actual</b></td>
                            <td><b>Commit</b></td>
                            <td><b>Basic</b></td>
                <!--                                        <td><b>Processed</b></td>-->
                            <td><b>Aspi</b></td>
                            <td><b>Actual</b></td>
                            <td><b>Commit</b></td>
                            <td><b>Basic</b></td>
                            
                            
                            <td><b>Aspi</b></td>
                            <td><b>Actual</b></td>
                            <td><b>Commit</b></td>
                            <td><b>Basic</b></td>
                            
                <!--                                        <td><b>Processed</b></td>-->


                        </tr>
                    </thead>
                   <tbody>
                        <?php $i=1; $Total=0;//print_r($ExpenseReport);
                                foreach($Disdata as $dt)
                                {
                                    $Branch = $dt['dfd']['Branch'];
                                    $FinanceYear = $dt['dfd']['FinanceYear'];
                                    $FinanceMonth = $dt['dfd']['FinanceMonth'];
                                    
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo '<td><a href="freeze_request?Branch='."$Branch"."&finYear=".$FinanceYear.'&finMonth='.$FinanceMonth.'" >'.$dt['dfd']['Branch']."</a></td>";
                                        
                                        echo "<td>".$dt['dfd']['FinanceMonth']."</td>";
                                        
                                        echo "<td>".round($dt['0']['Rev_Asp'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Com'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Bas'],2)."</td>";
                                        
                                        echo "<td>".round($dt['0']['Dir_Asp'],2)."</td>";
                                        echo "<td>".round($dt['0']['Dir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['Dir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['Dir_Bas'],2)."</td>";
                                        
                                        echo "<td>".round($dt['0']['InDir_Asp'],2)."</td>";
                                        echo "<td>".round($dt['0']['InDir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['InDir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['InDir_Bas'],2)."</td>";
                                        
                                        echo "<td>".round($dt['0']['Rev_Asp']-$dt['0']['Dir_Asp']-$dt['0']['InDir_Asp'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Act']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Com']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'],2)."</td>";
                                        echo "<td>".round($dt['0']['Rev_Bas']-$dt['0']['Dir_Bas']-$dt['0']['InDir_Bas'],2)."</td>";
                                        
                                        echo "<td>".round(($dt['0']['Rev_Asp']-$dt['0']['Dir_Asp']-$dt['0']['InDir_Asp'])*100/$dt['0']['Rev_Asp'],2)."%</td>";
                                        echo "<td>".round(($dt['0']['Rev_Act']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'])*100/$dt['0']['Rev_Act'],2)."%</td>";
                                        echo "<td>".round(($dt['0']['Rev_Com']-$dt['0']['Dir_Act']-$dt['0']['InDir_Act'])*100/$dt['0']['Rev_Com'],2)."%</td>";
                                        echo "<td>".round(($dt['0']['Rev_Bas']-$dt['0']['Dir_Bas']-$dt['0']['InDir_Bas'])*100/$dt['0']['Rev_Bas'],2)."%</td>";
                                        
                                        echo '<td colspan="2"><font color="red">Reject</font></td>';
                                        
                                    echo "</tr>";
                                }
                                
                        ?>
                    </tbody>
                </table> 
            
            
            <?php } ?>
        </div>
    </div>
</div>


 

