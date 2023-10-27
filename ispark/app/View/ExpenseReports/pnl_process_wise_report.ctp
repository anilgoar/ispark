<?php

?>
<script>
<?php if($LastLog !=""){ ?>
setInterval(function(){DashboardLog('<?php echo $LastLog;?>'); }, 3000);
<?php }?>
function DashboardLog(Id){
    $.post("<?php echo $this->webroot;?>ExpenseReports/pnl_process_wise_report",{Id:Id},function(data){
    });
}
</script>
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
                    
                    <span>P&L Process Wise Report</span>
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
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">
                        <?php	//$company_name = array('All'=>'All') + $company_name;
                            echo $this->Form->input('company_name', array('label'=>false,'id'=>'company_name','class'=>'form-control','options' => $branch_master,'empty' => 'Select','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                        <?php	
                            echo $this->Form->input('FinanceYear', array('label'=>false,'id'=>'Year','class'=>'form-control','options' => $financeYearArr,'empty' => 'Select Year','value'=>$FinanceYearLogin,'required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">Pertaining Month</label>
                        <div class="col-sm-2">
                            <?php	$month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('FinanceMonth', array('label'=>false,'id'=>'Month','class'=>'form-control','options' => $month,'empty' => 'Select Month','required'=>true));
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-2">
                           <button class="btn btn-primary btn-label-left" onclick="return revenue_branch_wise_validate('Export')">Export</button>
                           <a href="/ispark/Menuisps/sub?AX=MTM1" class="btn btn-primary btn-label-left">Back</a> 
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
    function revenue_branch_wise_validate(Type)
    {
        var Company = $('#company_name').val();
        var Year = $('#Year').val();
        var Month = $('#Month').val();
        
        if(Company =='')
	{
		alert('Please Select Branch Name');
		return false;
	}
	else if(Year =='')
	{
		alert('Please Select Finance Year');
		return false;
	}
	else if(Month =='')
	{
		alert('Please Select Month');
		return false;
	}
       var url='get_pnl_report_process_wise?Company='+Company+'&FinanceYear='+Year+'&FinanceMonth='+Month+'&type='+'Export';
       
	window.location.href = url;
        return false; 
    }
</script>
