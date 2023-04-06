<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
        
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name"><span>Process Details</span></div>
                <div class="box-icons"></div>
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
                       echo "<td align=\"center\"><code>".$this->Html->link($post['cm']['cost_center'],array('controller'=>'CostCenterMasters','action'=>'edit_cost','?'=>array('id'=>$post['cm']['id']),'full_base' => true))."</code></td>";
                       echo "<td>".$post['cm']['branch']."</td>";
                       echo "<td>".$post['cm']['client']."</td>";
                       echo "<td>".$post['cm']['bill_to']." ".$post['cm']['b_Address1']." ".$post['cm']['b_Address2']." ".$post['cm']['b_Address3']." ".$post['cm']['b_Address4']." ".$post['cm']['b_Address5']."</td>";
                       echo "<td>".$post['cm']['ship_to']." ".$post['cm']['a_address1']." ".$post['cm']['a_address2']." ".$post['cm']['a_address3']." ".$post['cm']['a_address4']." ".$post['cm']['a_address5']."</td>";
                       echo "<td>".$post['cm']['ship_to']." ".$post['cm']['a_address1']." ".$post['cm']['a_address2']." ".$post['cm']['a_address3']." ".$post['cm']['a_address4']." ".$post['cm']['a_address5']."</td>";
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