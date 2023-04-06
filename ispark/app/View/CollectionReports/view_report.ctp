<script>
    function getPerformance_new(val)
    {
        var companyName = document.getElementById('company_name').value;
var BranchName = document.getElementById('branch_name').value;
var start_date = document.getElementById('start_date').value;
var end_date = document.getElementById('end_date').value;
var report  = document.getElementById('type').value;


if(companyName == '')
{
    alert("Please Select Company Name");
    return false;
}
else if(BranchName == '')
{
    alert("Please Select Branch Name");
    return false;
}
else if(start_date == '')
{
    alert("Please Select To Date");
    return false;
}
else if(end_date == '')
{
    alert("Please Select From Date");
    return false;
}
else if(report == '')
{
    alert("Please Select Report Type");
    return false;
}

$.get("view_report_performance",
            {
             company:companyName,
             BranchName:BranchName,
             start_date:start_date,
             end_date:end_date,
             report:report,
             type:'show'
            },
            function(data,status){
               $('#data').html(data);
               
            });
return false;
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
                    <i class="fa fa-search"></i>
                    <span>Collection Planning</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <div class = "form-horizontal">
		<div class="form-group">
                    
                <label class="col-sm-1 control-label">Company</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('company_name', array('label'=>false,'class'=>'form-control','options' => array('All'=>'All','Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'IDC'),'empty' => 'Select Company','required'=>true));
                    ?>
                    </div>        

                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                    ?>
                    </div>        

                    <label class="col-sm-1 control-label">Report</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('type', array('label'=>false,'options'=>array('Performance'=>'Performance','Expected Dr'=>'Payment Status'),'class'=>'form-control','empty' => 'Select','required'=>true));
                    ?>
                    </div>
                    
                </div>
		<div class="clearfix"></div>
		<div class="form-group">
                <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('start_date', array('label' => false,'placeholder' => 'To Date','onclick'=>"displayDatePicker('data[start_date]');",'class'=>'form-control','required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-3">
                        <?php	
                            echo $this->Form->input('end_date', array('label'=>false,'placeholder' => 'From Date','onclick'=>"displayDatePicker('data[end_date]');",'class'=>'form-control','required'=>true));
                         ?>
                    </div>
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-1">
                        <button type="button" value="show" onclick="getPerformance_new(this.value)" class="btn btn-primary btn-label-left">Show</button>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" value="export" onclick="getPerformance(this.value)" class="btn btn-primary btn-label-left" >Export</button>
                    </div>
		</div>
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
                    <i class="fa fa-search"></i>
                    <span>Collection Planning Details</span>
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
            </div>
        </div>
    </div>
</div>