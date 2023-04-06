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
					<span>Process Details</span>
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
			<?php echo $this->Session->flash(); ?>
				<h4 class="page-header">Cost Center </h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Process Code</th>
                    	<th>Branch</th>
                    	<th>Client</th>
                    	<th>Bill To</th>
                    	<th>Ship To</th>
                        <th>Status</th>
<!--                        <th>Action</th>-->
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
					 foreach($cost_master as $post):
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
						echo "<td align=\"center\"><code>".$this->Html->link($post['0']['cost_center'],array('controller'=>'CostCenterMasters','action'=>'edit_cost','?'=>array('id'=>$post['0']['id']),'full_base' => true))."</code></td>";
						echo "<td>".$post['0']['branch']."</td>";
						echo "<td>".$post['0']['client']."</td>";
						echo "<td>".$post['0']['bill_to']." ".$post['0']['b_Address1']." ".$post['0']['b_Address2']." ".$post['0']['b_Address3']." ".$post['0']['b_Address4']." ".$post['0']['b_Address5']."</td>";
						echo "<td>".$post['0']['ship_to']." ".$post['0']['a_address1']." ".$post['0']['a_address2']." ".$post['0']['a_address3']." ".$post['0']['a_address4']." ".$post['0']['a_address5']."</td>";
                                                echo "<td>".$post['0']['ship_to']." ".$post['0']['a_address1']." ".$post['0']['a_address2']." ".$post['0']['a_address3']." ".$post['0']['a_address4']." ".$post['0']['a_address5']."</td>";
//                                                echo '<td class="actionCost '.$post['0']['cost_status'].' '.$post['0']['id'].'"><a href="#">'.$post['0']['cost_status'].'</a></td>';
					 echo "</tr>";
					 endforeach;
				?>
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
    $(document).on('click', '.actionCost', function()
    {
        var postdata = $(this).attr('class'); 
        var postdataArray=postdata.split(" ");
        var cost_id = postdataArray[2];
        var cost_status = postdataArray[1];
        $.ajax({type:"Post",cache:false,url: "disable_cost",
        data:{cost_id:cost_id,cost_status:cost_status}, success: function(data){alert(data); }});});
</script>