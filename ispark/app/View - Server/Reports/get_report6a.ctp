<?php //print_r($result); ?>
<?php //print_r($data); ?>
<?php
	if($Report=='Submission')
		{ 
	$fileName = "submission_report";
	$Col = 6;
		}
		else
		{
	$fileName = "ptp_report";
	$Col = 5;	
		}
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<table border="1">
	<tr>
    	<td valign="top">
        	<table border="1">
	<tr>
		<th>Id.</th>
		<th>Company_Name</th>
        <?php 
		if($Report=='Submission')
		{
		?>
        <th>Bill No</th>
        <?php } ?>
        <th>Branch_name</th>
        <th>Process Code</th>
        <th>Billing Date</th>
        <th>Month</th>
		<th>Total</th>
	     <th>Invoice Description</th>
          <?php 
		if($Report=='Submission')
		{
		?>
        <th>Submited Date</th>
        <?php } else { ?>
         <th>PTP Date</th>
		<?php } ?>
     </tr>
		<?php  $i =1; $total = 0; foreach($data as $post) :?>
	 <tr>
       <td><?php echo $i++ ?></td>
          <td><?php echo $post['t2']['company_name'] ?></td>
			<?php 
            if($Report=='Submission')
            {
            ?>
            <td><?php echo $post['t1']['bill_no'] ?></td>
            <?php } ?>
           <td><?php echo $post['t1']['branch_name'] ?></td>
            <td> <?php echo $post['t2']['cost_center'] ?></td>
            <td><?php $date = date_create($post['t1']['invoiceDate']);
			echo	$date = date_format($date,'d-M-Y');
			?></td>
           <td><?php echo $post['t1']['month'] ?></td>
			<td><?php echo $post['t1']['total'] ?></td>
			<td><?php echo $post['t1']['invoiceDescription'] ?></td>
             <td><?php 
			 if($Report=='Submission')
            {
			 echo $post['t3']['SubmitedDates'];
			}
			else
			{
			 echo $post['t3']['ExpDatesPayment'];
			}?></td>
		</tr>	
          <?php 
	  
		$total += 	$post['t1']['total'];
		
	endforeach; 
	?> 
    <tr>
		<th> Total </th>
		<td colspan = "<?php echo $Col;?>"> </td>
		<td><?php echo $total ?></td>
		<td colspan = "3"></td>
	</tr>
	
</table>

	 </td>
     <td></td>
        <td valign="top">
        	<table border="1">
            	<tr>
                	<th>Branch_name</th>
                    <th>Process Code</th>
                    <th>Total</th>
                </tr>
                <?php  $i =1; $total = 0; foreach($rest as $post) :?>
                <tr>
                
            <td> <?php echo $post['t1']['branch_name'] ?></td>
            <td><?php echo $post['t2']['cost_center'] ?></td>
                <td><?php echo $post[0]['total'] ?></td>	
                </tr>
                 <?php 
	  
		$total += 	$post[0]['total'];
		
	endforeach; 
	?> 
    <tr>
		<th> Total </th>
        <td>&nbsp;</td>
	    <td><?php echo $total ?></td>
	
	</tr>
	
            </table>
        </td>
    </tr>
</table>
<?php exit;?>