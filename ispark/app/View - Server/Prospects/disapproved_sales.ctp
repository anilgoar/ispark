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
				<h4 class="page-header">Search DisApproved Prospect</h4>
				
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Product Name</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('ProductId',array('label' => false,'class'=>'form-control','options'=>$product_master,'empty'=>'Select','value'=>$SC[0]['sc']['ProductId'])); ?>
						</div>
                                                <label class="col-sm-2 control-label">Introduction</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('Introduction',array('label' => false,'class'=>'form-control','options'=>array('EOI'=>'EOI','Commercial'=>'Commercial'),'empty'=>'Select','value'=>$SC[0]['sc']['Introduction'])); ?>
						</div>
                                                <label class="col-sm-2 control-label">Client Name</label>
						<div class="col-sm-2">
							<?php echo $this->Form->input('ClientName',array('label' => false,'class'=>'form-control','placeholder'=>'Client Name','value'=>$SC[0]['sc']['ClientName'])); ?>
						</div>
                                                
					</div>
                                        <div class="form-group">
                                            
                                            <label class="col-sm-2 control-label">Date To</label>
                                                <div class="col-sm-2">
							<?php echo $this->Form->input('ToDate',array('label' => false,'class'=>'form-control','placeholder'=>'Date To','onclick'=>"displayDatePicker('data[prospects][ToDate]');",'value'=>$SC[0]['0']['ToDate'])); ?>
						</div>
                                            <label class="col-sm-2 control-label">Date From</label>
                                                <div class="col-sm-2">
							<?php echo $this->Form->input('FromDate',array('label' => false,'class'=>'form-control','placeholder'=>'Date From','onclick'=>"displayDatePicker('data[prospects][FromDate]');",'value'=>$SC[0]['0']['FromDate'])); ?>
						</div>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"></label>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-primary btn-label-left">
                                                        Search
                                                </button>
                                            </div>     
                                        </div>
					<div class="clearfix"></div>
					<div class="form-group">
						
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Prospect List</span>
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
							<td align="center"><b>Sr. No.</b></td>
                                                        <td align="center"><b>Client Name</b></td>
							<td align="center"><b>Product Name</b></td>
                                                        <td align="center"><b>Introduction</b></td>
                                                        <td align="center"><b>Attachment 1</b></td>
                                                        <td align="center"><b>Attachment 2</b></td>
                                                        <td align="center"><b>Attachment 3</b></td>
                                                        <td align="center"><b>Serial No.</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($sales_master as $post) { ?>
						<tr>
							<td align="center"><?php echo $i++; ?></td>
                                                        <td align="center"><?php echo $this->Html->link($post['sc']['ClientName'],array('controller'=>'prospects','action'=>'create_cover','?'=>array('Id'=>$post['sc']['Id'],'disapprove'=>'disapprove'),'full_base' => true)); ?></td>
							<td align="center"><?php echo $post['sp']['ProductName']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Introduction']; ?></td>
                                                         <td>
                                                             <?php if(!empty($post['sc']['attachment1'])) { ?>
                                                             <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$post['sc']['Id'].'/'.$post['sc']['attachment1']; ?>"><?php echo $post['sc']['attachment1']; ?></a>
                                                             <?php } ?>
							</td>
                                                        <td>
                                                             <?php if(!empty($post['sc']['attachment2'])) { ?>
                                                             <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$post['sc']['Id'].'/'.$post['sc']['attachment2']; ?>"><?php echo $post['sc']['attachment2']; ?></a>
                                                             <?php } ?>
							</td>
                                                        <td>
                                                             <?php if(!empty($post['sc']['attachment3'])) { ?>
                                                             <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$post['sc']['Id'].'/'.$post['sc']['attachment3']; ?>"><?php echo $post['sc']['attachment3']; ?></a>
                                                             <?php } ?>
							</td>
                                                        
                                                        <td align="center"><?php echo $post['sc']['ProspectUniqueNo']; ?></td>
						</tr>
                                                <?php } ?>
						<?php unset($product_master); ?>
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