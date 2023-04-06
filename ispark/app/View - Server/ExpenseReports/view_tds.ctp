<?php

?>
<style>
    table td{margin: 5px;}
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
                    
                    <span>TDS Export Report</span>
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
                        <label class="col-sm-2 control-label">Company </label>
                        <div class="col-sm-4">
                        <?php
                            echo $this->Form->input('CompId', array('label'=>false,'class'=>'form-control','options' => $company_master,'empty' => 'Select','Id'=>'CompId','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">Branch </label>
                        <div class="col-sm-4">
                        <?php
                            echo $this->Form->input('BranchId', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','Id'=>'BranchId','required'=>true));
                        ?>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Finance Year</label>
                        <div class="col-sm-4">
                        <?php
                            echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','options' => $financeYearArr,'empty' => 'Select Year','Id'=>'FinanceYear','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">Finance Month</label>
                        <div class="col-sm-4">
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
                            <input type="submit" name="Export" value="Export" onclick="return export_tds('Export')" class="btn btn-info" />
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



