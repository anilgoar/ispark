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
	<div class="col-xs-12">
            
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>View Rejected Imprest (Process Head)</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
                            <h4 style="color:green"><?php echo $this->Session->flash(); ?> </h4>
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"> Branch</label>
                                    <div class="col-sm-3">
                                        <?php	echo $this->Form->input('branch', array('label'=>false,'options'=>$branch,'id'=>'branch','value'=>$branchId_param,'empty'=>'Branch','class'=>'form-control')); ?>
                                    </div>
                                    <label class="col-sm-1 control-label"> Year</label>
                                    <div class="col-sm-2">
                                        <?php	echo $this->Form->input('year', array('label'=>false,'options'=>$FinanceYear,'id'=>'year','value'=>$year_param,'empty'=>'Year','class'=>'form-control')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="button" name="Search" value="Search" onclick="return get_reject_imprest_process_head()" class="btn btn-info" />
                                        <a href="/ispark/Menuisps/sub?AX=NjI=" class="btn btn-info" >Back</a>
                                    </div>
                                    
                                </div>
                            </div>
			</div>
                    
                    <div class="box-content no-padding" id="get_reject_imprest_record">
                        
                    </div>

                    
		</div>
	</div>
</div>
<script type="text/javascript">
function get_reject_imprest_process_head()
{
    var branch = $('#branch').val();
    var year = $('#year').val();
    
    
    
    if(branch=='')
    {
        alert("Please Select Branch");
        $('#branch').focus();
        return false;
    }
    else if(year=='')
    {
        alert("Please Select Year");
        $('#year').focus();
        return false;
    }
    
    
    
    $.post("get_reject_imprest_process_head",
            {
             branch:branch,
             year: year
            },
            function(data,status){
               $('#get_reject_imprest_record').html(data);
            });  
    
    return false;
}


<?php

if(!empty($branchId_param) && $year_param)
{ ?>
    get_reject_imprest_process_head();
<?php }

?>


</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>