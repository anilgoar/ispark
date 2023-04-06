<?php //print_r($result); ?>
<?php //print_r($data); ?>
<?php 
	$fileName = "Billapprovalstage";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<?php //print_r($result); ?>
<?php //print_r($data); ?>

<table>
	<tr>
    	<td>
        	<table border="1">
	<tr>
		<th>S.No.</th>
		<th>Company_Name</th>
        <th>Process Code</th>
		<th>Branch_name</th>
        <?php if($report=='Bill Initiated Not Approve'){
			 echo "<th>Bill Number</th>"; } ?>
            <?php if($report=='GRN Status'){
			 echo "<th>GRN</th>"; } ?> 
         <th>Month</th>
         <?php if($report=='Bill Generate Status'){
         echo "<th>Description</th>";}?>
         <?php if($report=='PO Status'){
         echo "<th>Po No</th>";}?>
         
		<th>Total</th>
    </tr>
    
     <?php  $i =1; $total = 0;
	  foreach($data as $post) :
	 ?>
	 <tr>
    <td><?php echo $i++ ?></td>
              
			<td><?php echo $post['t2']['company_name'] ?></td>
           <td> <?php echo $post['t2']['cost_center'] ?></td>
			<td><?php echo $post['t1']['branch_name'] ?></td>
            <?php if($report=='Bill Initiated Not Approve'){
			 echo '<td>'.$post['t1']['bill_no'].'</td>';} ?>
             <?php if($report=='GRN Status'){
			 echo '<td>'.$post['t1']['grn'].'</td>';} ?>
        <td><?php echo $post['t1']['month']?></td>
         <?php if($report=='Bill Generate Status'){
			 echo '<td>'.$post['t1']['invoiceDescription'].'</td>';} ?>
         <?php if($report=='PO Status'){
			 echo '<td>'.$post['t1']['po_no'].'</td>';} ?>
         <td><?php echo $post['t1']['total'] ?></td>
     </tr>
   
      <?php $total += 	$post['t1']['total']; endforeach; ?> 
    <tr>
		<th> Total </th>
		<td colspan = "5"> </td>
		<td><?php echo $total ?></td>
		<td colspan = "3"></td>
	</tr>
	
</table>


        </td>
        <td>
       <table border="1">
       <tr>
       <th>Process Code</th>
		<th>Branch_name</th>
        <th>Amount</th>
        </tr>
        <?php  $i =1; $total = 0;
	  foreach($data as $post) :
	 ?>
	<tr>
    	 <td> <?php echo $post['t2']['cost_center'] ?></td>
			<td><?php echo $post['t1']['branch_name'] ?></td>
             <td><?php echo $post['t1']['total'] ?></td>
     </tr>
   
      <?php $total += 	$post['t1']['total']; endforeach; ?> 
    <tr>
		<th> Total </th>
        
		<td>&nbsp;</td>
		<td><?php echo $total ?></td>
		
	</tr>
            
  
        </td>
    </tr>
</table>