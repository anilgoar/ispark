<?php echo $this->Form->create('Provision',array('class'=>'form-horizontal','url'=>'bill_outsource_master','enctype'=>'multipart/form-data')); ?>
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
                    <span>Revenue Out-Source Process/UnProcess Update</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
		<div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', 
                                    array('label'=>false,
                                        'class'=>'form-control',
                                        'id'=>'branch',
                                        'options' => $branch_master,
                                        'empty' => 'Select',
                                        'required'=>true));
                    ?>
                    </div>

                    
                    

                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('finance_year', 
                                array('options' => $finance_yearNew,
                                    'empty' => 'Select',
                                    'id'=>'year',
                                    'label' => false, 
                                    'div' => false,
                                    'class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                        <?php	
                                $month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('month', 
                                        array('label'=>false,
                                            'class'=>'form-control',
                                            'id'=>'month',
                                            'options'=>$month,
                                            'empty' => 'Select',
                                            'required'=>true,
                                        'onchange'=>"get_costcenter_billing(this.value)"));
                         ?>
                    </div>
                </div>

		
                <div class="form-group">
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3">
                    <?php	echo $this->Form->input('cost_center', array('label'=>false,
                        'id'=>'cost_center',
                        'class'=>'form-control',
                        'options' => '',
                        'empty' => 'Select',
                        'onchange'=>"get_outsource_record(this.value)",
                        'required'=>true)); ?>
                    </div>
                </div>
		
		<div class="clearfix"></div>
                <div id="show_table">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
function get_costcenter_billing(month)
{
    var FinanceYear = $('#year').val();
    var branch = $('#branch').val();
    
    $.post("get_cost_center_bill",
        {
         Branch: branch,
         FinanceYear:FinanceYear,
         FinanceMonth:month
        },
        function(data,status){
            $("#cost_center").html(data);

        });  
}

function get_outsource_record(cost_center)
{
    var FinanceYear = $('#year').val();
    var branch = $('#branch').val();
    var month = $('#month').val();
    
    $.post("get_outsource_record",
        {
         Branch: branch,
         FinanceYear:FinanceYear,
         FinanceMonth:month,
         cost_center:cost_center
        },
        function(data,status){
            $("#show_table").html(data);

        });  
}

function get_upd_proc(proc_id)
{
    $.post("get_upd_proc_outsource",
        {
         proc_id: proc_id
        },
        function(data,status){
            $("#out_"+proc_id).html('Process');
        }); 
}

</script>