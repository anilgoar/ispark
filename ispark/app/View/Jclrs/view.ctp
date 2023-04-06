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
					<span>Employee Details</span>
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
				<h4 class="page-header">Emp Details </h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Emp Code</th>
                    	<th>Emp Name</th>
                    	<th>Father name</th>
                    	<th>Dept</th>
                    	<th>Desg To</th>
                        <th>DOJ</th>
                        <th>Status</th>
                        
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
              // print_r($Jclr);die;
					 foreach($Jclr as $post):
//print_r($post);die;
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
						if($post['qual_employee']['Status']==1){echo "<td align=\"center\"><code>".$this->Html->link($post['qual_employee']['EmpCode'],array('controller'=>'Jclrs','action'=>'editjclr','?'=>array('id'=>$post['qual_employee']['EmpCode']),'full_base' => true))."</code></td>";} else{ echo "<td>".$post['qual_employee']['EmpCode']."</td>";}
						echo "<td>".$post['qual_employee']['EmapName']."</td>";
						echo "<td>".$post['qual_employee']['FatherName']."</td>";
						echo "<td>".$post['qual_employee']['Dept']."</td>";
						echo "<td>".$post['qual_employee']['Desg']."</td>";
                                                echo "<td>".$post['qual_employee']['DOFJ']."</td>";
                                               if($post['qual_employee']['Status']==1){ echo "<td style = 'color:Green'>Active</td>";}else{echo "<td style = 'color:red'>Left</td>";}
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
    $(document).on('click', '.actionCost', function(){var postdata = $(this).attr('class'); 
            var postdataArray=postdata.split(" ");var cost_id = postdataArray[2]; var cost_status = postdataArray[1];$.ajax({type:"Post",cache:false,url: "disable_cost",
                data:{cost_id:cost_id,cost_status:cost_status}, success: function(data){alert(data); }});});
</script>