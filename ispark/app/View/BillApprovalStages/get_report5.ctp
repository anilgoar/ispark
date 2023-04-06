<?php //print_r($summary); ?>
<?php //print_r($data); ?>
<?php
	$class = "class=\"table table-striped table-bordered table-hover table-heading no-border-bottom\"";
	if($type == 'export')
	{
	$fileName = "Billapprovalstage";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	$class = "border = 1";
	}

?>
<table>
	<tr>
    	<td valign="top">
        	<table <?php echo $class ?> id="table_id">
	<thead>
	<tr>
		<th>S.No.</th>
        <?php if($report == 'Bill Initiated Not Approve'){}else { echo "<th>Bill_no</th>";}	?>
		<th>Branch_name</th>
        <th>Process Code</th>
        <?php 
			if($report=='Bill Initiated Not Approve'){
			 echo "<th>Billing Date</th>"; 
			 echo "<th>Billing Month</th>"; 
			 echo "<th>Description</th>"; 
			 } 
		?>
            <?php if($report=='GRN Status'){
			 echo "<th>Description</th>";
			 echo "<th>GRN</th>"; } ?> 
         
         <?php 
		 	if($report=='Bill Generate Status')
			{
         		echo "<th>Description</th>";
				echo "<th>Bill Date</th>";
				echo "<th>Billing Month</th>";
			}?>
         
         <?php if($report=='PO Status'){
		echo "<th>Description</th>";	 
         echo "<th>Po No</th>";}?>
         
         <?php 
			if($report=='Bill Submission Status'){
			 echo "<th>Description</th>"; 
			 echo "<th>SubmitedDates</th>"; 
			 } 
		?>

		<th>Amount</th>
    </tr>
    </thead>
    <tbody>
     <?php  $i =1; $total = 0;
	  foreach($data as $post) :
	 ?>
	 <tr>
    <td><?php echo $i++ ?></td>
                    <?php if($report == 'Bill Initiated Not Approve'){}else { echo "<td>".$post['t1']['bill_no']."</th>";}	?>  
			<td><?php echo $post['t1']['branch_name'] ?></td>			
           <td> <?php echo $post['t2']['cost_center'] ?></td>

            <?php 
				if($report=='Bill Initiated Not Approve')
				{
					$date = date_create($post['t1']['invoiceDate']);
					echo '<td>'.date_format($date,'d-M-Y').'</td>';
					echo '<td>'.$post['t1']['month'].'</td>';
			 		echo '<td>'.$post['t1']['invoiceDescription'].'</td>';
				} ?>
             <?php 
			 	if($report=='GRN Status')
			 	{
					echo '<td>'.$post['t1']['invoiceDescription'].'</td>';					
			 		echo '<td>'.$post['0']['Grn Available'].'</td>';
				} 
			 
			 ?>
         <?php 
		 	if($report=='Bill Generate Status')
			{
				 echo '<td>'.$post['t1']['desc'].'</td>';
				 $date = date_create($post['t1']['invoiceDate']);
				 echo '<td>'.date_format($date,'d-M-Y').'</td>';
				 echo '<td>'.$post['t1']['month'].'</td>';
			} 
		?>
         <?php if($report=='PO Status'){
			 echo '<td>'.$post['t1']['invoiceDescription'].'</td>';
			 echo '<td>'.$post['0']['po_no'].'</td>';} ?>
             
         <?php 
			if($report=='Bill Submission Status'){
				$date = date_create($post['t3']['SubmitedDates']);				
			echo "<td>".$post['t1']['invoiceDescription']."</td>"; 	
			 echo "<td>".date_format($date,'d-M-Y')."</td>"; 
			 } 
		?>

         <td><?php echo $post['0']['total']; ?></td>
     </tr>
    
      <?php if($report=='Bill Generate Status'){ $total += $post['0']['total'];} else{$total += $post['0']['total'];} endforeach; ?> 
</tbody>
    <tr>
		<th> Total </th>
		<td colspan = "<?php if($report == 'Bill Initiated Not Approve'){ echo "5";}else if($report=='Bill Generate Status'){ echo "6";} else { echo "5";}	?>"> </td>
		<td><?php echo $total ?></td>
		<td colspan = "3"></td>
	</tr>
	
</table>


        </td>
        <td valign = "top">
       <table <?php echo $class ?> id="table_id1">	
	<thead>
       <tr>
       <th>Process Code</th>
		<th>Branch_name</th>
        <th>Amount</th>
        </tr>
	</thead>
	<tbody>
        <?php  $i =1; $total = 0;
	  foreach($summary as $post) :
	 ?>
	<tr>
    	 <td> <?php echo $post['tbl_invoice']['cost_center'] ?></td>
			<td><?php echo $post['tbl_invoice']['branch_name'] ?></td>
             <td><?php echo $post['0']['total'] ?></td>
     </tr>
   	
      <?php $total += 	$post['0']['total']; endforeach; ?> 
	</tbody>
    <tr>
		<th> Total </th>
        
		<td>&nbsp;</td>
		<td><?php echo $total ?></td>
		
	</tr>
            
  
        </td>
    </tr>
</table>