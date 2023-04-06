<div class="box-content">
				<h4 class="page-header">Report</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
					<div class="form-group has-success has-feedback">
                                               <label class="col-sm-2 control-label">Expense Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('HeadingId',array('label' => false,'id'=>'head','class'=>'form-control','empty'=>'Select','options'=>(array('ALL'=>'ALL')+$head1),'required'=>true,'onchange'=>'getSubHeading()')); ?>
						</div>
						<label class="col-sm-2 control-label">Expense Sub Head</label>
						<div class="col-sm-4">
						<?php echo $this->Form->input('SubHeadingDesc',array('label' => false,'id'=>'subHead','class'=>'form-control','empty'=>'Select','options'=>'')); ?>
						</div>
					</div>
                                    <div class="form-group has-success has-feedback">
                                        <label class="col-sm-2 control-label"></label>
                                                <div class="col-sm-2">
                                                    <button type="submit" name="View" value="View" class="btn btn-primary btn-label-left">View</button>
                                                    <button type="submit" name="Export" value="Export" class="btn btn-primary btn-label-left">Export</button>
                                                </div>
                                    </div>    
					<div class="clearfix"></div>
					
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Details</span>
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
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=1; ?>
					<thead>
						<tr class="active">
							<td><b>Sr. No.</b></td>
							<td><b>Unique No</b></td>
                                                        <td><b>SubHead</b></td>
                                                        <td><b>GRN No</b></td>
                                                        <td><b>Head</b></td>
                                                        <td><b>Vendor</b></td>
                                                        
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($data as $post): ?>
						<tr>
							<td><?php echo $i++; ?></td>
                                                        <td><?php echo $post['eem']['UniqueId']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
							<td><?php echo $post['eem']['GrnNo']; ?></td>
                                                        <td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['vm']['vendor']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    function getSubHeading()
{
    var HeadingId=$("#head").val();
    
  $.post("get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="ALL">ALL</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subHead").empty();
                $("#subHead").html(text);
                
            });  
}

    
    
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>