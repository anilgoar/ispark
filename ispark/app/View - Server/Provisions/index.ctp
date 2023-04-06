<?php echo $this->Form->create('Provision',array('class'=>'form-horizontal','url'=>'add','enctype'=>'multipart/form-data')); ?>
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
                    <span>Provision</span>
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
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                    <?php	
                            echo $this->Form->input('branch_name', 
                                    array('label'=>false,
                                        'class'=>'form-control',
                                        'options' => $branch_master,
                                        'empty' => 'Select',
                                        'required'=>true,
                                        'onchange'=>"get_costcenter_bill(this.value)"));
                    ?>
                    </div>

                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-3">
                    <?php	echo $this->Form->input('cost_center', array('label'=>false,
                        'id'=>'cost_center',
                        'class'=>'form-control',
                        'options' => '',
                        'empty' => 'Select',
                        'onchange'=>"get_provision_amt(this.value)",
                        'required'=>true)); ?>
                    </div>
                    

                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('finance_year', 
                                array('options' => $finance_yearNew,
                                    'empty' => 'Select',
                                    'label' => false, 
                                    'div' => false,
                                    'class'=>'form-control')); ?>
                    </div>
                </div>

		<div class="form-group">
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-3">
                        <?php	
                                $month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('month', 
                                        array('label'=>false,
                                            'class'=>'form-control',
                                            'options'=>$month,
                                            'empty' => 'Select',
                                            'required'=>true));
                         ?>
                    </div>
                    
                    <div id="ProvisionDisp">
                        
                    </div>
                    
		</div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Remarks</label>
                    <div class="col-sm-2">
                        <?php	echo $this->Form->textArea('remarks', array('label'=>false,
                            'class'=>'form-control',
                            'placeholder'=>'Remarks',
                            'required'=>true)); ?>
                    </div>
                </div>			
		
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-label-left">
                            Save
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
function get_costcenter_bill(Branch)
{
    
        $.post("provisions/get_cost_center",
            {
             Branch: Branch
            },
            function(data,status){
                $("#cost_center").html(data);
                
            });  
}

function get_provision_amt(CostCenter)
{
    $.post("provisions/get_provision_amt_field",
        {
         CostCenter: CostCenter
        },
        function(data,status){
            $("#ProvisionDisp").html(data);

        });  
}

</script>