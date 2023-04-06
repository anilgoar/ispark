
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
                    <span>Add E-PTP Date</span>
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
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-4">
                    <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'id'=>'branch','class'=>'form-control','options' => $branch_master,'empty' => 'Select','onchange'=>"get_cost_center(this.value)",'required'=>true));
                    ?>
                    </div>        
                </div>
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('cost_center', array('label'=>false,'id'=>'cost_center','options'=>'','class'=>'form-control','onchange'=>"get_month(this.value)",'empty' => 'Select','required'=>true));
                    ?>
                    </div>
                </div>
                <div class="form-group" id="month_disp">                    
                    <label class="col-sm-2 control-label">Month</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('month', array('label'=>false,'id'=>'month','options'=>'','class'=>'form-control','empty' => 'Select','required'=>true));
                    ?>
                    </div>
                </div>
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('category', array('label'=>false,'options'=>array('Agreement Pending'=>'Agreement Pending','PO Pending'=>'PO Pending','Bill Ready'=>'Bill Ready','GRN Pending'=>'GRN Pending','Receiving Pending'=>'Receiving Pending','PTP Date Pending'=>'PTP Date Pending'),'class'=>'form-control','empty' => 'Select','required'=>true));
                    ?>
                    </div>
                </div>
                
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Action Date</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('action_date', array('label' => false,'placeholder' => 'To Date','onclick'=>"displayDatePicker('data[action_date]');",'class'=>'form-control','required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->textArea('remarks', array('label' => false,'placeholder' => 'Remarks','class'=>'form-control','required'=>true)); ?>
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
                    <span>EPTP Date Details</span>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
            <div id="data"></div>
            </div>
        </div>
    </div>
</div>

<script>
function get_cost_center(branch)
{
    
        $.post("/ispark/provisions/get_cost_center",
            {
             Branch: branch
            },
            function(data,status){
                $("#cost_center").html(data);
                
            });  
}

function get_cost_month(cost_center)
{
    
    $.post("/ispark/provisions/get_cost_month",
        {
         cost_center: cost_center
        },
        function(data,status){
            $("#month").html(data);

        });  
}

</script>