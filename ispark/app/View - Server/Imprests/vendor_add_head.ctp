<div class="box-content">
				<h4 class="page-header">Add Sub Head To Vendor</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('Imprest',array('class'=>'form-horizontal')); ?>
					<div class="form-group has-success has-feedback">
                                               <label class="col-sm-2 control-label">Vendor</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('Vendor',array('label' => false,'class'=>'form-control','empty'=>'Select','options'=>$Vendor,'required'=>true)); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
                                               <label class="col-sm-2 control-label">Expense Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('HeadingId',array('label' => false,'class'=>'form-control','id'=>'head','empty'=>'Select','options'=>$head1,'onchange'=>"getSubHeading()",'required'=>true)); ?>
						</div>
					</div>
                                        <div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Expense Sub Head</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('SubHeadingId',array('label' => false,'class'=>'form-control','id'=>'subhead','options'=>'','required'=>true)); ?>
						</div>
                                                
					</div>
                                    <div class="form-group has-success has-feedback">
                                        <label class="col-sm-2 control-label"></label>
                                                <div class="col-sm-2">
                                                    <button type="submit" class="btn btn-primary btn-label-left">Submit</button>
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
					<span>Vendor Relation</span>
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
							<td><b>Vendor</b></td>
                                                        <td><b>Expense Head</b></td>
							<td><b>Expense Sub Head</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($head as $post): ?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $post['head']['HeadingDesc']; ?></td>
                                                        <td><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($Addbranch); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
    
  
    
</script>
 <script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("<?php echo $this->webroot;?>/ExpenseEntries/get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option>';
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
</script>