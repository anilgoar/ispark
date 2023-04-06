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
                    
                    <span>P&L Summary Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group">
                 <?php echo $this->Form->create('GrnReports',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                        <?php	
                            echo $this->Form->input('FinanceYear', array('label'=>false,'id'=>'FinanceYear','class'=>'form-control','options' => $financeYearArr,'empty' => 'Select Year','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">From Month</label>
                        <div class="col-sm-2">
                            <?php	$month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('FromMonth', array('label'=>false,'id'=>'FromMonth','class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                            ?>
                        </div>
                        <label class="col-sm-2 control-label">To Month</label>
                        <div class="col-sm-2">
                            <?php	
                                echo $this->Form->input('ToMonth', array('label'=>false,'id'=>'ToMonth','class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                           
                           <button class="btn btn-primary btn-label-left" onclick="return pnl_summary_report('Export')">Export</button>
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

<script>
    function pnl_summary_report(Type)
    {
        var FromMonth = $('#FromMonth').val();
        var FinanceYear = $('#FinanceYear').val();
        var ToMonth = $('#ToMonth').val();
        
        if(FromMonth =='')
	{
		alert('Please Select From Month');
		return false;
	}
	else if(FinanceYear =='')
	{
		alert('Please Select Finance Year');
		return false;
	}
	else if(ToMonth =='')
	{
		alert('Please Select To Month');
		return false;
	}
       var url='http://mascallnetnorth.in/ispark/GrnReports/get_pnl_report_month_wise_summary?FromMonth='+FromMonth+'&FinanceYear='+FinanceYear+'&ToMonth='+ToMonth+'&type='+'Export';
       
	window.location.href = url;
        return false; 
    }
</script>
