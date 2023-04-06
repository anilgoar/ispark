<?php
//print_r($inv);
?>
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
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Action Window</span>
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
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <tr>
        <th>S. No.</th>
         <th>Branch</th>
         <th>CostCenter</th>
         <th>Client</th>
         <th>Agreement</th>
         <th>PO</th>
         <th>PO Pending</th>
         <th>GRN Pending</th>
         <th>Bill Submission</th>
         <th>PTP</th>
    </tr>
<?php $i=1; 
    //print_r($data); exit;
    foreach($data as $k=>$v) {
?>    
    <tr>
        <td><?php echo $i++; ?></td>
         <td><?php echo $v['branch'];?></td>
         <td><?php echo $k;?></td>
         <td><?php echo $v['client'];?></td>
         <td><?php echo $v['Agreement'];?></td>
         <td><?php echo $v['PO'];?></td>
         <td><?php echo $v['po_status'];?></td>
         <td><?php echo $v['grn_status']; ?></td>
         <td><?php echo $v['sub_status']; ?></td>
         <td><?php echo $v['ptp_status']; ?></td>
    </tr>
<?php    } ?>
</table>
</div>
                    </div>
               </div>
	</div>