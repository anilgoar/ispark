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
				<h4 class="page-header">Add TDS Section</h4>
				
                                <h4><?php echo $this->Session->flash(); ?></h4>
					<?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal','action'=>'add_tds_section','required'=>true)); ?>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('description',array('label' => false,'class'=>'form-control','placeholder'=>'Description','required'=>true)); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Section</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('section',array('label' => false,'class'=>'form-control','placeholder'=>'Section','required'=>true)); ?>
						</div>
					</div>
					<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">TDS % e.g. 0.00</label>
						<div class="col-sm-4">
							<?php echo $this->Form->input('TDS',array('label' => false,'class'=>'form-control','placeholder'=>'TDS','onkeypress'=>"return checkNumber(this.value,event)",'required'=>true)); ?>
						</div>
					</div>
                                        
					<div class="clearfix"></div>
					<div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left">
								Submit
							</button>
						</div>
                                            <div class="col-sm-2">
                           <button class="btn btn-primary btn-label-left" onclick="return revenue_branch_wise_validate('Export')">Export</button>
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
					<span>TDS Details</span>
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
                                                        <td align="center"><b>Section</b></td>
							<td align="center"><b>TDS%</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($tds_master as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<td align="center"><?php echo $i; ?></td>
                                                        <td align="center"><code><?php echo $post['TDSMaster']['description']; ?></code></td>
                                                        <td align="center"><?php echo $post['TDSMaster']['section']; ?></td>
                                                        <td align="center"><?php echo $post['TDSMaster']['TDS']; ?></td>
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
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        else if(val.length>1 &&  (charCode> 47 && charCode<58) || charCode == 8 || charCode == 110 )
        {
            if(val.indexOf(".") >= 0 && val.indexOf(".") <= 3 || charCode == 8 ){
                 
            }
            else{
               alert("TDS Should Not More Than 100");
                 return false; 
           
           
        }
        }
	return true;
}

</script>