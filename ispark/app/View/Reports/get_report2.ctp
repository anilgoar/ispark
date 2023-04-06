<?php //print_r($qry); ?>
<?php //print_r($res); ?>
<?php 
	$fileName = "$report_name";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<?php
if($report == 1)
{ 
?>

<table border="1">
	<thead>
	<tr>
		<th>S. No.</th>
		<th>Company</th>
		<th>Cost Centre Code</th>
                <th>Process</th>
                <th>Branch</th>
		<th>Client</th>
		<th>Invoice No.</th>
		<th>Finance Year</th>
		<th>Month</th>
		<th>Amount</th>
		<th>S. Tax</th>
		<th>SBC Tax</th>
                <th>Krishi Tax</th>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>Part Payment</th>
		<th>G. Total</th>
		<th>Remarks</th>
		<th>Status</th>
		<th>Bill Date</th>
                <th>PTP Date</th>
                <th>Ageing</th>
                <th>Bill Status</th>
                <th>Create Date</th>
                <th>EPTP Date</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$i =1;$total=0;$tax = 0;$sbcTax = 0; $krishiTax = 0; $grnd = 0;
		foreach($res as $post) :
	?>
		<tr>
			<td><?= $i++ ?></td>
			<td><?= $post['t2']['company_name'] ?></td>
			<td><?= $post['t2']['cost_center'] ?></td>
                        <td><?= $post['t2']['CostCenterName'] ?></td>
            <td><?= $post['t1']['branch_name'] ?></td>
			<td><? echo $post['t2']['client']; ?></td>
			<td><?= $post['t1']['bill_no'] ?></td>
			<td><?= $post['t1']['finance_year'] ?></td>
			<td><?= "=text(\"".$post['t1']['month']."\",\"mmm-d\")" ?></td>
			<td><?= $post['t1']['total'] ?></td>
			<td><?= $post['t1']['tax'] ?></td>
			<td><?= $post['t1']['sbctax'] ?></td>
                        <td><?= $post['t1']['krishi_tax'] ?></td>
                        <td><?= $post['t1']['igst'] ?></td>
                        <td><?= $post['t1']['cgst'] ?></td>
                        <td><?= $post['t1']['sgst'] ?></td>
                        <td><? echo ($post['bpp']['net_amount']+$post['bpp']['tds_ded']); ?></td>
			<td><?php echo ($post['t1']['grnd']-$post['bpp']['net_amount']-$post['bpp']['tds_ded']); ?></td>
			<td><?= $post['t1']['invoiceDescription'] ?></td>
			<td></td>
			<td><?php $date =date_create($post['t1']['invoiceDate']);
			echo date_format($date,"d-M-Y"); 
			?></td>
			<td><?php if($post['t3']['ExpDatesPayment']!=''){$date =date_create($post['t3']['ExpDatesPayment']);
			echo date_format($date,"d-M-Y");} 
			?></td>
            		<td><?= $post['0']['Ageing']; ?></td>
            		<td><?= $post['0']['bill_status'] ?></td>
            		<td><?= $post['t1']['createdate'] ?></td>
                        <td><?= $post['t1']['eptp_act_date'] ?></td>
            
		</tr>
	<?php 
		$total += 	$post['t1']['total'];
		$tax += 	$post['t1']['tax'];
		$sbcTax += $post['t1']['sbctax'];
                $krishiTax += $post['t1']['krishi_tax'];
                $igst  += $post['t1']['igst'];
                $cgst  += $post['t1']['igst'];
                $sgst  += $post['t1']['sgst'];
		$grnd += 	($post['t1']['grnd']-$post['bpp']['net_amount']-$post['bpp']['tds_ded']);
                $part_payment += $post['bpp']['net_amount'];
                $part_payment += $post['bpp']['tds_ded'];
	
	endforeach; 
        
        foreach($res1 as $post)
        {?>
            <tr>
			<td><?= $i++ ?></td>
			<td><?= $post['t2']['company_name'] ?></td>
			<td><?= $post['t2']['cost_center'] ?></td>
                        <td><?= $post['t2']['CostCenterName'] ?></td>
            <td><?= $post['t2']['branch'] ?></td>
			<td><? echo $post['t2']['client']; ?></td>
			<td></td>
			<td><?= $post['t1']['finance_year'] ?></td>
			<td><?= "=text(\"".$post['t1']['month']."\",\"mmm-d\")" ?></td>
			<td><?= $post['t1']['provision_balance'] ?></td>
			<td><?= 0 ?></td>
			<td><?= 0 ?></td>
                        <td><?= 0 ?></td>
                        <td><?= round($post['t1']['provision_balance']*0.18) ?></td>
			<td><?= 0 ?></td>
                        <td><?= 0 ?></td>
                        <td><?= 0 ?></td>
			<td><?= round($post['t1']['provision_balance']*1.18) ?></td>
			<td><?= $post['t1']['remarks'] ?></td>
			<td></td>
			<td></td>
			<td></td>
            		<td><?= $post['0']['Ageing']; ?></td>
            		<td>To Be Billed</td>
            		<td><?= $post['t1']['createdate'] ?></td>
                        <td><?= $post['t1']['eptp_act_date'] ?></td>
		</tr>
    <?php 
    
            $total += 	$post['t1']['provision_balance'];
		$tax += 	0;
		$sbcTax += 0;
                $krishiTax += 0;
                $igst  += round($post['t1']['provision_balance']*0.18);
                $cgst  += $post['t1']['igst'];
                $sgst  += $post['t1']['sgst'];
		$grnd += 	round($post['t1']['provision_balance']*1.18);
    
    
        }?>
        
	<tr>
		<th> Total </th>
		<td colspan = "8"> </td>
		<td><?=$total ?></td>
		<td><?=$tax ?></td>
		<td><?=$sbcTax ?></td>
                <td><?=$krishiTax ?></td>
                <td><?=$igst ?></td>
                <td><?=$cgst ?></td>
                <td><?=$sgst ?></td>
                <td><?=$part_payment ?></td>
                
		<td><?=$grnd ?></td>
		<td colspan = "4"></td>
	</tr>
	</tbody>
</table>
</div>
<?php 
}

if($report == 2)
{
	if($type == '2')
	{
	
		$data = array(); $dataX = array();
		
		
		foreach($res as $post):
			$branch[] = $post['tab']['branch_name'].",".$post['tab']['client'];
			$month[] = $post['tab']['month'];
			if($post['tab']['month'] == 'Previous')			
			{
				if(array_key_exists($post['tab']['branch_name'].",".$post['tab']['client'],$data))
				{
					if(array_key_exists($post['tab']['month'],$data[$post['tab']['branch_name'].",".$post['tab']['client']]))
					{
						$post['tab']['total'] += $data[$post['tab']['branch_name'].",".$post['tab']['client']][$post['tab']['month']]['tab']['total'];
					}
				}
			}
			
			$data[$post['tab']['branch_name'].",".$post['tab']['client']][$post['tab']['month']] = $post;
		endforeach;
	//print_r($data);
		$month = array_unique($month);
		$branch = array_unique($branch);
		
	//print_r($month);
		$count1 = count($month);
		$count2 = count($branch);
		$month_arr = array('Previous',"Jan-$Year","Feb-$Year","Mar-$Year","Apr-$Year","May-$Year","Jun-$Year","Jul-$Year","Aug-$Year","Sep-$Year","Oct-$Year","Nov-$Year","Dec-$Year");
                
                ?>
<div class="box-content no-padding" style="overflow:auto"> 
 	<table border="1">
    	<thead>
		<tr>
			<th>S. No.</th>
			<th>Branch</th>
			<th>Client Name</th>		
			<?php 
				for($i =0; $i<13; $i++)
				{
					if(in_array($month_arr[$i],$month))
					{
						echo "<th>".$month_arr[$i]."</th>";
						$month2[]=$month_arr[$i];
					}
				}
			?>
			<th>Total</th>
		</tr>
       </thead>
       <tbody>
	<?php
		$i = 1;  $totalArr = 0; $totalMonth = array_fill(0,$count1,'0');  
		//echo print_r($totalMonth);echo "--".$count1;
			foreach($data as $k=>$v)
			{
				$j = 0;$total = 0;
				echo "<tr>";
				echo "<td>".$i++."</td>";
				$desk = explode(',',$k);
				echo "<td>".$desk[0]."</td>";
				echo "<td>".$desk[1]."</td>";
					foreach($month2 as $midx)
					{
						
						if(isset($v[$midx]['tab']['total']))
						{
							echo "<td>{$v[$midx]['tab']['total']}</td>";
							$total += $v[$midx]['tab']['total'];
							$totalMonth[$j] += $v[$midx]['tab']['total'];
							$j = $j+1;
						}
						else
						{
							echo "<td></td>";
							$totalMonth[$j++] += 0;
						}
						//print_r($v);
						//exit;
					}
					//exit;
					echo "<th>".$total."</th>";
					$totalArr += $total; 
				echo "</tr>";
			} 
	?>
    </tbody>
	<tr>
		<th colspan = "3">Total</th>
	<?php 
        $totalMonth = array_filter($totalMonth);
	foreach($totalMonth as $post)
	{
		echo "<th>".$post."</th>";
	}
	echo "<th>".$totalArr."</th>";
	?>
	</tr>
	</table>
 	<?php } ?>  <!-- close outsummary report branch wise -->
	<?php
	if($type == '1')
	{
			foreach($res as $post):
				$branch[] = $post['t1']['branch_name'].",".$post['t2']['client'];
				$month[] = $post['0']['t1.month'];
				
			if($post['0']['t1.month'] == 'Previous')			
			{
				if(array_key_exists($post['t1']['branch_name'].",".$post['t2']['client'],$data))
				{
					if(array_key_exists($post['0']['t1.month'],$data[$post['t1']['branch_name'].",".$post['t2']['client']]))
					{
						$post['0']['t1.total'] += $data[$post['t1']['branch_name'].",".$post['t2']['client']][$post['0']['t1.month']]['0']['t1.total'];
					}
				}
			}
			
			$data[$post['t1']['branch_name'].",".$post['t2']['client']][$post['0']['t1.month']] = $post;
			endforeach;
	
			$month = array_unique($month);
			$branch = array_unique($branch);
	
			$count1 = count($month);
			$count2 = count($branch);
			$month_arr = array('Previous',"Jan-$Year","Feb-$Year","Mar-$Year","Apr-$Year","May-$Year","Jun-$Year","Jul-$Year","Aug-$Year","Sep-$Year","Oct-$Year","Nov-$Year","Dec-$Year");
	?>
 	<table border = "1">
    <thead>
		<tr>
			<th>S. No.</th>
			<th>Client Name</th>
			<th>Branch</th>
			<?php 
				for($i =0; $i<13; $i++)
				{
					if(in_array($month_arr[$i],$month))
					{
						echo "<th>".$month_arr[$i]."</th>";
						$month2[]=$month_arr[$i];
					}
				}
			?>
			<th>Total</th>
		</tr>
       </thead>
       <tbody>
	<?php
		$i = 1;  $totalArr = 0; $totalMonth = array_fill(0,$count1,'0');  
		//echo print_r($totalMonth);echo "--".$count1;
			foreach($data as $k=>$v)
			{
				$j = 0;$total = 0;
				echo "<tr>";
				echo "<td>".$i++."</td>";
				$desk = explode(',',$k);
				echo "<td>".$desk[1]."</td>";
				echo "<td>".$desk[0]."</td>";
					foreach($month2 as $midx)
					{
						
						if(isset($v[$midx]['0']['t1.total']))
						{
							echo "<td>{$v[$midx]['0']['t1.total']}</td>";
							$total += $v[$midx]['0']['t1.total'];
							$totalMonth[$j] += $v[$midx]['0']['t1.total'];
							$j = $j+1;
						}
						else
						{
							echo "<td></td>";
							$totalMonth[$j++] += 0;
						}
						//print_r($v);
						//exit;
					}
					//exit;
					echo "<th>".$total."</th>";
					$totalArr += $total; 
				echo "</tr>";
			} 
	?>
	<tr>
		<th colspan = "3">Total</th>
	<?php 
	foreach($totalMonth as $post)
	{
		echo "<th>".$post."</th>";
	}
	echo "<th>".$totalArr."</th>";
	?>
	</tr>
    </tbody>
	</table>
<?php } ?>
<?php }	

else if($report == 3)
{
    if(!empty($res)) {
?>

<table border="1" >
	
	
	<?php
		
		$i =1;$total=0;$tax = 0; $sbcTax=0; $krishiTax = 0; $grnd = 0;
                //$array_head = array('0-30','30-60','60-90','90-120','120-150','&gt;150 Days','Disputed','Write-Off');
                //$array_head = array('&gt;150 Days','120-150','90-120','60-90','30-60','0-30','Disputed','Write-Off');
                $array_head1 = array('&gt;150 Days','120-150','90-120','60-90','30-60','0-30');
                $array_head2 = array('Disputed','Write-Off');
                
                foreach($res as $post) 
                {
                    $branchArr[] = $post['t1']['branch_name'];
                    if($post['t1']['branch_name']=='Disputed')
                    {
                        $data[$post['t1']['branch_name']]['disputed'] += ($post['t1']['grnd']-$post['bpp']['net_amount']-$post['bpp']['tds_ded']);
                    }
                    if($post['t1']['branch_name']=='Write-Off')
                    {
                        $data[$post['t1']['branch_name']]['Write-Off'] += ($post['t1']['grnd']-$post['bpp']['net_amount']-$post['bpp']['tds_ded']);
                    }
                    else
                    {
                        $data[$post['t1']['branch_name']][$post['0']['Ageing']] += ($post['t1']['grnd']-$post['bpp']['net_amount']-$post['bpp']['tds_ded']);
                    }
                    //$array_head[] = $post['0']['Ageing'];
                }
                foreach($res1 as $post) 
                {
                        $data[$post['t1']['branch_name']][$post['0']['Ageing']] += ($post['0']['grnd']);
                        $branchArr[] = $post['t1']['branch_name'];
                }
                
                $array_head1= array_unique($array_head1);
                $array_head2= array_unique($array_head2);
                $branchArr = array_unique($branchArr);
                sort($branchArr);
	?>
            <thead>
                <tr>
                    <th>Branch</th>
                    <?php foreach($array_head1 as $k) { ?>
                    <th><?php echo $k;?></th>
                    <?php } ?>
                    <th>Total</th>
                    <?php foreach($array_head2 as $k) { ?>
                    <th><?php echo $k;?></th>
                    <?php } ?>
                    <th>G. Total</th>
                </tr>
            </thead>
            <tbody>
            <?php $g_total=0; $head_Total=array();  $t_total = 0;
            
                foreach($branchArr as $branch) 
                { $b_total = 0; $dt =  $data[$branch];  ?>
                
		<tr>
			<th><?php echo $branch; ?></th>
			<?php 
                        
                        foreach($array_head1 as $k) 
                        { 
                            $b_total +=$dt[$k]; 
                            $head_Total[$k] +=$dt[$k];
                        ?>
                        <td><?php echo $dt[$k];?></td>
                  <?php } ?>
                        <th><?php echo ($b_total); $t_total +=$b_total;     ?></th>
                        <?php 
                        
                        foreach($array_head2 as $k) 
                        { 
                            $b_total +=$dt[$k]; 
                            $head_Total[$k] +=$dt[$k];
                        ?>
                        <td><?php echo $dt[$k];?></td>
                  <?php } ?>
                        <th><?php echo ($b_total);  $g_total +=($b_total);  ?></th>
		</tr>
            <?php }    ?>
                </tbody>
	<tr>
		<th> Total </th>
		<?php 
                    foreach($array_head1 as $k) 
                    { ?>
                        <th><?php echo $head_Total[$k];?></th>
              <?php } ?>
                        <th><?php echo $t_total;?></th>
                    <?php 
                    foreach($array_head2 as $k) 
                    { ?>
                        <th><?php echo $head_Total[$k];?></th>
              <?php } ?>    
                    <th><?php echo $g_total;?></th>
	</tr>
</table>
</div>
<?php 
}
}
?>