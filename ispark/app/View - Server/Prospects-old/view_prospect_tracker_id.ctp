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
					<i class="fa fa-table"></i>
					<span>Prospect Tracker</span>
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
                                                        <td align="center"><b>Attachment</b></td>
                                                        <td align="center"><b>Download</b></td>
                                                        <td align="center"><b>Status</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($sales_master as $post): ?>
						<tr>
							<td align="center"><?php echo $i++; ?></td>
                                                        <td align="center"><?php echo $this->Html->link($post['sc']['ClientName'],array('controller'=>'prospects','action'=>'create_cover','?'=>array('Id'=>$post['sc']['Id']),'full_base' => true)); ?></td>
							<td align="center"><?php echo $post['sp']['ProductName']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Introduction']; ?></td>
                                                         <td>
                                                             <?php if(!empty($post['sc']['attachment'])) { ?>
                                                            <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$post['sc']['Id'].'/'.$post['sc']['attachment']; ?>">Attachment</a>
                                                             <?php } ?>
							</td>
                                                        <td><?php echo $this->Html->link(__('PDF'), array('controller'=>'prospects','action' => 'view_pdf','?'=>array('Id'=>$post['sc']['Id']), 'ext' => 'pdf', 'DownloadPdf')); ?>
							</td>
                                                        <td>
                                                            <?php if($post['sc']['IntroApprove']==0) 
                                                                    {echo 'Pending';}
                                                                  else if(($post['sc']['IntroApprove']==1)) { echo 'Approved';}
                                                                  else if(($post['sc']['IntroApprove']==2)) { echo 'Rejected';}
                                                            ?></td>
                                                        
                                                        
						</tr>
						<?php endforeach; ?>
						<?php unset($product_master); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

