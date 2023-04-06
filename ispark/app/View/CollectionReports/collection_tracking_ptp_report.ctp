

<?php //print_r($ExpenseEntryMaster);  exit;



//echo $this->Form->create('CollectionReports',array('class'=>'form-horizontal','action'=>'get_eptp_coll_track'));
if(!empty($ExpenseEntryMaster['0']))
{
    $readonly = true;
    //echo "<script>$('#GmsParticular').focus();</script>";
}
else
{
    $readonly = false;
}
?>

<div class="row">
<div id="breadcrumb" class="col-xs-12">
    <a href="#" class="show-sidebar">
    <i class="fa fa-bars"></i></a>
    <ol class="breadcrumb pull-left"></ol>
</div>
</div>

<div class="row">
<div class="col-xs-12 col-sm-12">
    <div class="box">
       
        <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
            <h4 class="page-header textClass" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">EPTP Collection Tracking <?php echo $this->Session->flash(); ?></h4>
            <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Branch</label>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('branch',array('label' => false,'options'=>$branch_master,'class'=>'form-control','id'=>'branch','required'=>true)); ?>
                </div>
                <label class="col-sm-1 control-label">Year</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('finance_year', array('options' => $finance_year,'empty' => 'Year','label' => false,'id'=>'finance_year', 'class'=>'form-control','required'=>true)); ?>
                </div>
                <label class="col-sm-1 control-label">Month</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('month', array('options' => array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                    'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'empty' => 'Month','label' => false,'id'=>'month', 'class'=>'form-control','required'=>true)); ?>
                </div>        
            </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Date</label>
                <div class="col-sm-3">
                        <?php echo $this->Form->input('from_date', array('label' => false,'placeholder' => 'Date','onclick'=>"displayDatePicker('data[from_date]');",'class'=>'form-control','required'=>true)); ?>
                    </div>
                </div>    
                  
            <div class="form-group">						
                <label class="col-sm-2 control-label">&nbsp;</label>
                <div class="col-sm-2">
                    <button onclick="return get_coll_eptp_track('view')" class="btn btn-primary btn-label-left">View</button>
                    <button onclick="get_coll_eptp_track('export')" class="btn btn-primary btn-label-left">Export</button>
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
            <div class="box-content" style="background-color:#ffffff; border:1px solid #436e90;">
		<h4 class="page-header" style="border-bottom: 1px double #436e90;margin: 0 0 10px;">Details</h4>
                <div id="details"></div>  
                
                
            </div>
	</div>
    </div>
</div>

<?php //echo $this->Form->end();?>

    
    
<script>
        
function get_coll_eptp_track(fetch_type)
{
    var branch=$("#branch").val();
    var finance_year=$("#finance_year").val();
    var finance_month=$("#month").val();
    var from_date = $("#from_date").val();
    
    if(branch=='')
    {
        alert("Please Select Branch");
        return false;
    }
    else if(finance_year=='')
    {
        alert("Please Select Year");
        return false;
    }
    else if(finance_month=='')
    {
        alert("Please Select Month");
        return false;
    }
    else if(from_date=='')
    {
        alert("Please Select Date");
        return false;
    }
    
    if(fetch_type=='view')
    {
    $.post("get_coll_eptp_track",
            {
             branch: branch,
             finance_year: finance_year,
             finance_month:finance_month,
             fetch_type:fetch_type,
             from_date:from_date
            },
            function(data,status){
                $("#details").html(data);
                
            }); 
            return false;
    }
    else
    {
        window.location="get_coll_eptp_track?branch="+branch+"&finance_year="+finance_year+"&finance_month="+finance_month+"&fetch_type="+fetch_type+"&from_date="+from_date;
    }
            
}




</script>