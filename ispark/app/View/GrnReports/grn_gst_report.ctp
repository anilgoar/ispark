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
                var text='<option value="">Select</option><option value="All">All</option>';
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
                    
                    <span>GRN GST Report</span>
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
                 <?php echo $this->Form->create('GRNReports',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Company</label>
                        <div class="col-sm-2">
                        <?php	
                            echo $this->Form->input('company_name', array('label'=>false,'id'=>'company_name','class'=>'form-control','options' => $company_name,'empty' => 'Select','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Year</label>
                        <div class="col-sm-2">
                            <?php	
                                echo $this->Form->input('FinanceYear', array('label'=>false,'id'=>'FinanceYear','class'=>'form-control','options' => $financeYearArr,'empty' => 'Select','required'=>true));
                            ?>
                        </div>
                        <label class="col-sm-1 control-label">Month</label>
                        <div class="col-sm-2">
                            <?php	$month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('FinanceMonth', array('label'=>false,'class'=>'form-control','id'=>'FinanceMonth','options' => $month,'empty' => 'Select','required'=>true));
                            ?>
                        </div>
                        <label class="col-sm-1 control-label">Type</label>
                        <div class="col-sm-2">
                            <?php	
                                echo $this->Form->input('Type', array('label'=>false,'id'=>'Type','class'=>'form-control','options' => array('All'=>'All','Taxable'=>'Texable','Non-Taxable'=>'Non-Taxable'),'empty' => 'Select','required'=>true));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1">
                           <button class="btn btn-primary btn-label-left" onClick="return grn_gst_report('Export');">Export</button>
                        </div>
                        <div class="col-sm-1">
                           <a href="/ispark/FinanceReports" class="btn btn-primary btn-label-left">Back</a> 
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



