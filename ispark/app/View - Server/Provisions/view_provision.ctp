<?php  if(!empty($provision)) { ?>
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
    echo "<td>".date_format(date_create($pro['Provision']['createdate']),'d-M-Y')."</td>";
    echo "<td>";
    echo    $this->Html->link('Edit',array('label'=>false,'controller'=>'provisions','action'=>'edit','?'=>array('id'=>$pro['Provision']['id']),'full_base'=>true));
    echo "</td>";
   echo "</tr>";
    endforeach;
    foreach($provision_part as $pro):
   echo "<tr>";
    echo "<td>".$i++."</td>";
    echo "<td>".$pro['Provision']['Branch_OutSource']."</td>";
    echo "<td>".$pro['Provision']['Cost_Center_OutSource']."</td>";
    echo "<td>".$pro['0']['outsource_amt']."</td>";
    echo "<td>".$pro['0']['outsource_amt']."</td>";
    
    echo "<td>".$pro['Provision']['FinanceYear']."</td>";
    echo "<td>".$pro['Provision']['FinanceMonth']."</td>";
    echo "<td>".date_format(date_create($pro['Provision']['create_date']),'d-M-Y')."</td>";
    echo "<td>";
    //echo    $this->Html->link('Edit',array('label'=>false,'controller'=>'provisions','action'=>'edit','?'=>array('id'=>$pro['Provision']['id']),'full_base'=>true));
    echo "</td>";
   echo "</tr>";
    endforeach;
?>
</tbody>
</table>
<?php } else { //print_r($data); ?>
No Record Founds
<?php } ?>
