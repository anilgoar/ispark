<?php //print_r($Data1); 
//print_r($pp);
?>

<?php 
	$fileName = "ExportDataDaysWise";
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>
<table border = "1">
	<tr>
        <th>EmpCode</th>
        <th>EmpName</th>
        <th>Branch</th>
        <th>CostCenter</th>
        <th>EmpFor</th>
        <th>Desig</th>
        <th>EmpType</th>
	<th>DOJ</th>

        <th>POI</th>
        <th>POA</th>
        <th>POE</th>
        <th>CoC</th>
       <th>CF</th>
       <th>EPF</th>
	   <th>Resume</th>
	   <th>Status</th>
	   <th>Remarks</th>
        
        
    </tr>
    <?php
   // print_r($Data1);exit;
            foreach($Data1 as $d):
			$rejectCheck[]=array();
			
			
			
			if($d['dm']['EPF']=='Reject' || $d['dm']['EPF_1']=='Reject' || $d['dm']['EPF_2']=='Reject' || $d['dm']['EPF_3']=='Reject')
			{
                            $rejectCheck[] = $POI = $d['dm']['POI']=='Yes'?'Validate':($d['dm']['POI']=='Reject'?'Reject':'Pending');
			$rejectCheck[] = $POA = $d['dm']['POA']=='Yes'?'Validate':($d['dm']['POA']=='Reject'?'Reject':'Pending');
			$rejectCheck[] = $POE = $d['dm']['POE']=='Yes'?'Validate':($d['dm']['POE']=='Reject'?'Reject':'Pending');
			//$rejectCheck[] = $CoC_1 = $d['dm']['CoC_2']=='Yes'?'Validate':($d['dm']['CoC_1']=='Reject'?'Reject':'Pending');
			//$rejectCheck[] = $CF_1 = $d['dm']['CF_1']=='Yes'?'Validate':($d['dm']['CF_1']=='Reject'?'Reject':'Pending');					
			//$rejectCheck[] = $EPF_1 = $d['dm']['EPF_1']=='Yes'?'Validate':($d['dm']['EPF_1']=='Reject'?'Reject':'Pending');	
			
			
			$rejectCheck[] = $Resume_1 = $d['dm']['Resume_1']=='Yes'?'Validate':($d['dm']['Resume_1']=='Reject'?'Reject':'Pending'); 
			
			if($d['dm']['CoC_1']=='Reject' || $d['dm']['CoC_2']=='Reject')
			{
			$CoC_1 = "Reject";
			}else if($d['dm']['CoC_1']=='Yes' && $d['dm']['CoC_2']=='Yes')
			{
			$CoC_1 = "Validate";
			}else { $CoC_1 = "Pending"; }
			
			
			if($d['dm']['CF_1']=='Reject' || $d['dm']['CF_2']=='Reject' || $d['dm']['CF_3']=='Reject' || $d['dm']['CF_4']=='Reject' || $d['dm']['CF_5']=='Reject' || $d['dm']['CF_6']=='Reject' || $d['dm']['CF_7']=='Reject')
			{
			$CF_1 = "Reject";
			}else if($d['dm']['CF_1']=='Yes' && $d['dm']['CF_2']=='Yes' && $d['dm']['CF_3']=='Yes' && $d['dm']['CF_4']=='Yes' && $d['dm']['CF_5']=='Yes' && $d['dm']['CF_6']=='Yes' && $d['dm']['CF_7']=='Reject')
			{
			$CF_1 = "Validate";
			}else { $CF_1 = "Pending"; }
			$EPF_1 = "Reject";
                        $flag = true;
			foreach($rejectCheck as $reject)
			{
				if($reject=='Reject'){ $flag = false;}
			}
			$Ok ='';
			if(!$flag)
			{$Ok = 'Reject';}
			else
			{$Ok = 'Validate';}
			

										
		 echo "<tr>";
                   echo "<td>".$d['em']['EmpCode']."</td>";
                   echo "<td>".$d['em']['EmpName']."</td>";
                   echo "<td>".$d['em']['Location']."</td>";
                   echo "<td>".$d['em']['CostCenter']."</td>";
                   echo "<td>".$d['em']['EmpFor']."</td>";
                   echo "<td>".$d['em']['Desig']."</td>";
                   echo "<td>".$d['em']['EmpType']."</td>";
			echo "<td>".$d['em']['DOJ']."</td>";

                   echo "<td>".$POI."</td>";
                   echo "<td>".$POA."</td>";
                   echo "<td>".$POE."</td>";
				   echo "<td>".$CoC_1."</td>";
				   echo "<td>".$CF_1."</td>";
				   echo "<td>".$EPF_1."</td>";
				   echo "<td>".$Resume_1."</td>";
				   echo "<td>".$Ok."</td>";
				   echo "<td>".$d['dm']['Remarks']."</td>";
                        
			}
                        
			
			
			
			
			
unset($rejectCheck);
            endforeach;
	?>
</table>
<?php die; ?>

