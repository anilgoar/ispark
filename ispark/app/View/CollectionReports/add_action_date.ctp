
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
                    <span>Add Action Date</span>
		</div>
		
		
            </div>
            <div class="box-content">
                <?php echo $this->Session->flash(); ?>
            <div class = "form-horizontal">
                <?php echo $this->Form->create('CollectionReport'); ?>
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
                        echo $this->Form->input('cost_center', array('label'=>false,'id'=>'cost_center','options'=>'','class'=>'form-control','empty' => 'Select','required'=>true));
                    ?>
                    </div>
                </div>
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Year</label>
                    <div class="col-sm-3">
                    <?php	
                        echo $this->Form->input('finance_year', array('label'=>false,'id'=>'finance_year','options'=>$finance_yearNew,'onchange'=>"get_cost_month(this.value)",'class'=>'form-control','empty' => 'Select','required'=>true));
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
                        echo $this->Form->input('category', array('label'=>false,'id'=>'category','options'=>array('Agreement Pending'=>'Agreement Pending','PO Pending'=>'PO Pending','GRN Pending'=>'GRN Pending','Receiving Pending'=>'Receiving Pending'),'class'=>'form-control','empty' => 'Select','required'=>true));
                    ?>
                    </div>
                </div>
                
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Action Date</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('action_date', array('label' => false,'id'=>'action_date','placeholder' => 'Action Date','onclick'=>"displayDatePicker('data[CollectionReport][action_date]');",'class'=>'form-control','required'=>true)); ?>
                    </div>
                </div>
                <div class="form-group">                    
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->textArea('remarks', array('label' => false,'id'=>'remarks','placeholder' => 'Remarks','class'=>'form-control','required'=>true)); ?>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" name="submit"  id="submit" value="Submit" class="btn btn-primary">Save</button>
                        <a href="/ispark/users/view" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-1">
                        
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
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
                    <span>Details</span>
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
function get_cost_center(Branch)
{   
    
        $.post("/ispark/provisions/get_cost_center",
            {
             Branch: Branch
            },
            function(data,status){
                $("#cost_center").html(data);
                
            });  
}

function get_cost_month(finance_year)
{ 
    $.post("get_prov_mnt",
        {
         finance_year: finance_year,
         branch: $('#branch').val(),
         cost_center: $('#cost_center').val()
         
        },
        function(data,status){
            $("#month").html(data);

        });  
}

</script>