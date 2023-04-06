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
                    <span>Invoice Entry</span>
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
		<h4 class="page-header">View Provision</h4>
                
                  <table aria-describedby="table_id_info" role="grid" class="table table-striped table-bordered table-hover table-heading no-border-bottom dataTable no-footer" id="table_id">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Branch Name</th>
                            <th>Cost Center</th>
                            <th>Provision</th>
                            <th>Balance</th>
                            <th>Finance Year</th>
                            <th>Month</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;
                            foreach($provision as $pro):
                               echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$pro['Provision']['branch_name']."</td>";
                                    echo "<td>".$pro['Provision']['cost_center']."</td>";
                                    echo "<td>".$pro['Provision']['provision']."</td>";
                                    echo "<td>".$pro['Provision']['provision_balance']."</td>";
                                    echo "<td>".$pro['Provision']['finance_year']."</td>";
                                    echo "<td>".$pro['Provision']['month']."</td>";
                                    echo "<td>".date($pro['Provision']['createdate'])."</td>";
                                    echo "<td>";
                                    echo    $this->Html->link('Edit',array('label'=>false,'controller'=>'provisions','action'=>'edit','?'=>array('id'=>$pro['Provision']['id']),'full_base'=>true));
                                    echo "</td>";
                               echo "</tr>";
                            endforeach;
                        ?>
                    </tbody>
                   </table>
            </div>
	</div>
    </div>
</div>
	
<?php echo $this->Form->end(); ?>
