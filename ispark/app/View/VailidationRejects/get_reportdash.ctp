

<?php   
?>
<style>
    table td {text-align: center;}
     table th {text-align: center;}
</style>
<div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">

	<tr>
        
         <th>Branch Name</th>
        <th>POI</th>
        <th>POA</th>
        <th>POE</th>
        <th>CoC</th>
       <th>CF</th>
       <th>EPF</th>
	   <th>Resume</th>
	   <th>Status</th>
	  
        
        
    </tr>
    <?php
   // print_r($Data1);exit;
    $POI =0;
    $POA =0;
    $POE = 0;
    $CoC_1 = 0;
    $CF_1 = 0;
    $EPF_1 =0;
    $Resume = 0;
    $Ok =0;
            foreach($Data1 as $d):
                $POI =$POI+$d['0']['POI'];
                $POA =$POA+$d['0']['POA'];
                $POE = $POE +$d['0']['POE'];
                $CoC_1 = $CoC_1 + $d['0']['CoC'];
                $CF_1 = $CF_1 + $d['0']['CF'];
                $EPF_1 =$EPF_1 +$d['0']['EPF'];
                $Resume = $Resume + $d['0']['Resume'];
                $Ok =$Ok + $d['0']['status'];
		 echo "<tr><td>".$d['em']['Location']."</td>";
            ?>
    <td onclick="report_validate52('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['POI']; ?></a></td>
    <td onclick="report_validate53('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['POA']; ?></a></td>
    <td onclick="report_validate54('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['POE']; ?></a></td>
    <td onclick="report_validate55('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['CoC']; ?></a></td>
    <td onclick="report_validate56('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['CF']; ?></a></td>
    <td onclick="report_validate57('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['EPF']; ?></a></td>
    <td onclick="report_validate58('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['Resume']; ?></a></td>
    <td onclick="report_validate59('<?php echo $d['em']['Location'];?>')"><a href="#"><?php echo $d['0']['status']; ?></a></td>
        
            <?php
            echo '</tr>';
                   
            endforeach;
            ?>
    
    <?php
             if($res =='All'){
                 ?>
             <tr><td>Total</td>
                 <td onClick="report_validate52('Total');"><a href="#"><?php echo $POI; ?></a></td>
              <td onClick="report_validate53('Total');"><a href="#"><?php echo $POA; ?></a></td>
                   <td onClick="report_validate54('Total');"><a href="#"><?php echo$POE; ?></a></td>
                   <td onClick="report_validate55('Total');"><a href="#"><?php echo $CoC_1; ?></a></td>
                                   <td onClick="report_validate56('Total');"><a href="#"><?php echo$CF_1; ?></a></td>
                                   <td onClick="report_validate57('Total');"><a href="#"><?php echo$EPF_1; ?></a></td>
                                   <td onClick="report_validate58('Total');"><a href="#"><?php echo $Resume; ?></a></td>
                                   <td onClick="report_validate59('Total');"><a href="#"><?php echo $Ok; ?></a></td></tr>
                                   
                                   <?php
             }  
	?>
       
    
</table>
</div>


