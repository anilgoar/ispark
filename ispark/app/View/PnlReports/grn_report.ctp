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

function grn_gst_report(type)
{
    var	branch_Name = document.getElementById('ExpenseBranchName').value;
    var	year = document.getElementById('ExpenseFinanceYear').value;
    var month = document.getElementById('ExpenseFinanceMonth').value;
    var	head = document.getElementById('head').value;

    var	subhead = document.getElementById('subhead').value;
    var ExpenseExpenseEntryType = document.getElementById('ExpenseExpenseEntryType').value;
    var ExpenseGrnNo = document.getElementById('ExpenseGrnNo').value;

    if(branch_Name =='')
    {
        alert('Please Select Branch Name');
        return false;
    }
			
    if(year =='')
    {
        alert('Please Select Year');
        return false;
    }
    if(month =='')
    {
        alert('Please Select From Month');
    }
        
    if(head =='')
    {
        alert('Please Select Expense Head');
        return false;
    }
			
    if(subhead =='')
    {
        alert('Please Select Expense Sub Head');
        return false;
    }
    if(ExpenseExpenseEntryType =='')
    {
        alert('Please Select Expense Mode');
    }
    
    var url='export_grn_report?BranchName='+branch_Name+'&year='+year+'&month='+month+'&head='+head+'&subhead='+subhead+'&ExpenseExpenseEntryType='+ExpenseExpenseEntryType+'&ExpenseGrnNo='+ExpenseGrnNo+'&type='+type;
    window.location.href = url;
    //document.getElementById('tett').innerHTML = url;
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
                        <label class="col-sm-1 control-label">Head</label>
                        <div class="col-sm-3">

                        <?php	
                            echo $this->Form->input('head', array('label'=>false,'class'=>'form-control','options' => $head,'empty' => 'Select Head','id'=>'head','onChange'=>"getSubHeading()",'required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-1 control-label">Sub Head</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('subhead', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Sub Head','id'=>'subhead'));
                                ?>
                        </div>
                        <label class="col-sm-1 control-label">Expense Mode</label>
                        <div class="col-sm-3">
                                <?php	
                                    echo $this->Form->input('expenseEntryType', array('label'=>false,'class'=>'form-control','options' => array('All'=>'All','Imprest'=>'Imprest','Vendor'=>'Non Imprest'),'empty' => 'Select','required'=>true));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"></label>
                        <label class="col-sm-3 control-label">Grn No</label>
                        <div class="col-sm-3">
                            <?php	
                                    echo $this->Form->input('GrnNo', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'GrnNo'));
                                ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1">
                           <button class="btn btn-info btn-label-left" onClick="return grn_gst_report('Export');">Export</button>
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div id="tett">
                    
		</div>
            </div>
        </div>
    </div>
</div>



