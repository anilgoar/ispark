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
                                                        <td align="center"><b>Client Name</b></td>
                                                        <td align="center"><?php echo $this->Html->link($sales_master_his['0']['sc']['ClientName'],array('controller'=>'prospects','action'=>'create_cover','?'=>array('Id'=>$sales_master_his['0']['sc']['Id']),'full_base' => true)); ?></td>
                                                        <td align="center" <?php if($sales_master_his['0']['sc']['ClientName']!=$sales_master['0']['sc']['ClientName']) { ?> style="text-color:red" <?php } ?> ><?php  echo $this->Html->link($sales_master['0']['sc']['ClientName'],array('controller'=>'prospects','action'=>'create_cover','?'=>array('Id'=>$sales_master['0']['sc']['Id']),'full_base' => true)); ?></td>
                                                </tr>
                                                <tr class="active">
							<td align="center"><b>Product Name</b></td>
                                                        <td align="center"><?php echo $sales_master_his['0']['sp']['ProductName']; ?></td>
                                                        <td align="center" <?php if($sales_master_his['0']['sc']['ProductName']!=$sales_master['0']['sc']['ProductName']) { ?> style="text-color:red" <?php } ?>><?php echo $sales_master['0']['sp']['ProductName']; ?></td>
                                                </tr>
                                                <tr class="active">        
                                                        <td align="center"><b>Introduction</b></td>
                                                        <td align="center"><?php echo $sales_master_his['0']['sc']['Introduction']; ?></td>
                                                        <td align="center" <?php if($sales_master_his['0']['sc']['Introduction']!=$sales_master['0']['sc']['Introduction']) { ?> style="text-color:red" <?php } ?>><?php echo $sales_master['0']['sc']['Introduction']; ?></td>
                                                </tr>
                                                <tr class="active">        
                                                        <td align="center"><b>Attachment</b></td>
                                                        <td>
                                                             <?php if(!empty($sales_master_his['0']['sc']['attachment'])) { ?>
                                                            <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$sales_master_his['0']['sc']['Id'].'/'.$sales_master_his['0']['sc']['attachment']; ?>">Attachment</a>
                                                             <?php } ?>
							</td>
                                                        <td>
                                                             <?php if(!empty($sales_master['0']['sc']['attachment'])) { ?>
                                                            <a href="<?php echo $this->webroot.'app/webroot/prospect_file/'.$sales_master['0']['sc']['Id'].'/'.$sales_master['0']['sc']['attachment']; ?>">Attachment</a>
                                                             <?php } ?>
							</td>
                                                </tr>
                                                <tr class="active">        
                                                        <td align="center"><b>Download</b></td>
                                                        <td><?php echo $this->Html->link(__('PDF'), array('controller'=>'prospects','action' => 'view_pdf','?'=>array('Id'=>$sales_master_his['0']['sc']['Id']), 'ext' => 'pdf', 'DownloadPdf')); ?>
							</td>
                                                        <td><?php echo $this->Html->link(__('PDF'), array('controller'=>'prospects','action' => 'view_pdf','?'=>array('Id'=>$sales_master['0']['sc']['Id']), 'ext' => 'pdf', 'DownloadPdf')); ?>
							</td>
                                                </tr>
                                                <tr class="active">        
                                                        <td align="center"><b>Status</b></td>
                                                        <td>
                                                            <?php if($sales_master_his['0']['sc']['IntroApprove']==0) 
                                                                    {echo 'Pending';}
                                                                  else if(($sales_master_his['0']['sc']['IntroApprove']==1)) { echo 'Approved';}
                                                                  else if(($sales_master_his['0']['sc']['IntroApprove']==2)) { echo 'Rejected';}
                                                            ?></td>
                                                        <td <?php if($sales_master_his['0']['sc']['IntroApprove']!=$sales_master['0']['sc']['IntroApprove']) { ?> style="text-color:red" <?php } ?>>
                                                            <?php if($sales_master['0']['sc']['IntroApprove']==0) 
                                                                    {echo 'Pending';}
                                                                  else if(($sales_master['0']['sc']['IntroApprove']==1)) { echo 'Approved';}
                                                                  else if(($sales_master['0']['sc']['IntroApprove']==2)) { echo 'Rejected';}
                                                            ?></td>
                                                </tr>
					</thead>
                                        
				</table>
			</div>
		</div>
	</div>
</div>

