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
                    
                    <span>Grn Dashboard</span>
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
                 <?php echo $this->Form->create('GrnReports',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Branch</label>
                        <div class="col-sm-3">
                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','id'=>'branch_name','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('FinanceYear', array('label'=>false,'class'=>'form-control','id'=>'FinanceYear','options' => array_merge(array('All'=>'All'),$financeYearArr),'empty' => 'Select Year','required'=>true));
                                ?>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-3">
                                <?php	$month = array('All'=>'All','Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                    echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','id'=>'FinanceMonth','options' => $month,'empty' => 'Select Month','required'=>true));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        
                        <div class="col-sm-1">
                           <button class="btn btn-info btn-label-left" onClick="return grn_dashboard('Show');">Show</button>
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
                    
                    <span>Grn Dashboard</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
                <div id="data"></div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
            
        </div>
    </div>
</div>
<script>
  function grn_dashboard()
{
    var Branch=$("#branch_name").val();
    var FinanceYear=$("#FinanceYear").val();
    var FinanceMonth = $("#FinanceMonth").val();
  $.post("export_grn_dashboard",
            {
             Branch: Branch,
             FinanceYear: FinanceYear,
             FinanceMonth:FinanceMonth
            },
            function(data,status){
                $("#data").html(data);
                
            });  
  return false;          
}  
</script>


