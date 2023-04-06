<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function getImprestManager()
{
    var BranchId=$("#BranchId").val();
  $.post("get_imprest_manager",
            {
             BranchId: BranchId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#ImprestManagerId").empty();
                $("#ImprestManagerId").html(text);
                
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
                    
                    <span>Imprest Report</span>
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
                        <label class="col-sm-2 control-label">Branch Master</label>
                        <div class="col-sm-4">
                        <?php	
                            echo $this->Form->input('BranchId', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','Id'=>'BranchId','onChange'=>'getImprestManager()','required'=>true));
                        ?>
                        </div>
                        <label class="col-sm-2 control-label">Date From</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('DateFrom', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'DateFrom','onClick'=>"displayDatePicker('data[Expense][DateFrom]')",'required'=>true));
                                ?>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Imprest Manager</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('ImprestManagerId', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Imprest Manager','id'=>'ImprestManagerId','required'=>true));
                                ?>
                        </div>
                        <label class="col-sm-2 control-label">Date To</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('DateTo', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'To','id'=>'DateTo','onClick'=>"displayDatePicker('data[Expense][DateTo]')",'required'=>true));
                                ?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                            <input type="submit" name="Show" value="Show" onclick="return imprest_report2_validate('Show')" class="btn btn-info" />
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" name="Export" value="Export" onclick="return imprest_report2_validate('Export')" class="btn btn-info" />
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
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" id="data">
                <!--All ajax data show here -->
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

