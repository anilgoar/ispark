<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>

<div class="box-content">
				<h4 class="page-header">Add Head Type</h4>
				
                                <h4><?php echo $this->Session->flash(); ?></h4>
					<?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal','action'=>'add_head_type','required'=>true)); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('description',array('label' => false,'class'=>'form-control','placeholder'=>'Description','required'=>true)); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Head Code</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('head_code',array('label' => false,'class'=>'form-control','placeholder'=>'Code','required'=>true,'onkeypress'=>"return checkNumber(this.value,event)")); ?>
						</div>
					</div>
					
                                        
					<div class="clearfix"></div>
					<div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Submit
							</button>
                                                    <a href="/ispark/Menuisps/sub?AX=NTk=&AY=L2lzcGFyay9NZW51aXNwcz9BWD1OQSUzRCUzRA==" class="btn btn-primary btn-label-left">Back</a> 
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>HEAD Details</span>
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
				<?php  $i=0; ?>
					<thead>
						<tr class="active">
							<td align="center"><b>Sr. No.</b></td>
							<td align="center"><b>Description</b></td>
                                                        <td align="center"><b>Head Code</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($head_master as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $post['HeadType']['description']; ?></td>
                                                        <td align="center"><?php echo $post['HeadType']['head_code']; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($Addbranch); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    function checkNumber(val,evt)
       {

    
            var charCode = (evt.which) ? evt.which : event.keyCode
	 if(val.length>0)
         {
             return false;
         }
         
	if ((charCode>= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 123))
        {            
		return true;
        }
        else 
        {
               alert("Head Code Should Be Between A AND Z");
                 return false; 
        }
	return true;
}

</script>