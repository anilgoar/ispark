
<style>
table {
        display: block;
        overflow-x: auto;
    }
td { 
text-align: center;

}
th { 
font-size: 12px; 
text-align: center;

}
</style>
<?php
?>
<script>
function getData(val)
{
    $.post("get_dash_data",{branch_name:val},function(data)
    {$("#mm").html(data);});
}
</script>
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
                    <span>View Dashboard Report</span>
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
		<h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                <div class="form-horizontal">
                <div class="form-group">
                    
                    <div class="col-sm-12">
<?php
                    if(!empty($Data))
{
?>
<table class="table table-hover table-bordered"  border="1">
    <tr>
		<th >Date</th>
		<th >Branch</th>
		<th >Process</th>
                <th >Target Month</th>
		<th >Target Revenue</th>
		<th >Commit</th>
		<th >Target DC</th>
                <th >Target DC%</th>
		<th >DC</th>
                <th >DC%</th>
		<th >Target IDC</th>
                <th >Target IDC %</th>
		<th >IDC</th>
                <th  >IDC%</th>
		<th  >Target OP</th>
                <th  >Target OP%</th>
		<th  >OP</th>
                <th >OP%</th>
		<th  >Diff</th>
                <th >Diff%</th>
    </tr>
    <?php
    $k=0;
    $j = 0;
    $i1= 0;
    
    $d21 = 0;
     $c1 = 0;
     $t1 = 0;
     
     $tdc1 =0;
     
$tidc1 = 0;

    $to1 = 0;
    $op1 = 0;
    
    $tdif1 = 0;

$i= 0;

    $d2 = 0;
    
     $c = 0;
     $t = 0;
    
     $tdc =0;
     
$tidc = 0;

    $to = 0;
    $op = 0;
     
    $tdif = 0;
    $flag = false;
    
	
 
  //print_r($Data); exit;
            foreach($Data as $d):
                 
    if($flag)
{   $newBranch = $d['0']['Branch'];
    if($oldBranch != $newBranch)
    { 
        
        
        echo '<tr><th colspan="4" >TOTAL</th>';
        echo "<td>".$t."</td>";
               
             echo "<td>". $c ."</td>";
                echo "<td>".$tdc."</td>";
echo "<td>".Round(((100 * $tdc)/ $t)) ."%</td>";
             echo "<td>". $d2 ."</td>";
             echo "<td>".round(((100*$d2)/$c))."%</td>";
             
             echo "<td>".$tidc."</td>";
             echo "<td>".Round(((100 * $tidc)/ $t)) ."%</td>";
             echo "<td>". $i ."</td>";
              echo "<td>".round(((100*$i)/$c))."%</td>";
             echo "<td>". $to ."</td>";
             echo "<td>".Round(((100 * $to)/ $t)) ."%</td>";
             echo "<td>".$op."</td>";
             echo "<td>".round(((100*$op)/$c))."%</td>";
             echo "<td>". $tdif ."</td>";
             echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c))) ."%</td>";
echo '</tr>';
        $i= 0;
        
    $d2 = 0;
   
     $c = 0;
     $t = 0;
    
     $tdc =0;
    
$tidc = 0;

    $to = 0;
    $op = 0;
    
    $tdif = 0;
   
       $oldBranch = $newBranch;
    }
}
else
{
    $newBranch = $oldBranch = $d['0']['Branch'];
}
$flag = true;
                $arr_diff[$j] = ROUND(100 - ((($d['f']['dc'] * 100)/($d['f']['cmt'])) + (($d['f']['idc'] * 100)/($d['f']['cmt']))));
              $arr_diff1[$j] = ROUND(100 - ((($d['f']['target_directCost'] * 100)/($d['f']['target'])) + (($d['f']['target_IDC'] * 100)/($d['f']['target'])) ));
              $diff[$k] = $arr_diff1[$j] - $arr_diff[$j];
               
                echo "<tr>";
                   echo "<td>".$d['f']['md']."</td>";
                   echo "<td>".substr($d['0']['Branch'],0,9)."</td>";
                   echo "<td>".$d['dfp']['branch_process']."</td>";
                    echo "<td>".$d['f']['mde']."</td>";
                   echo "<td>".$d['f']['target']."</td>";
                   echo "<td>".$d['f']['cmt']."</td>";
                   echo "<td>".$d['f']['target_directCost']."</td>";
                   echo "<td>".ROUND(($d['f']['target_directCost'] * 100)/($d['f']['target']))."%</td>";
                   echo "<td>".$d['f']['dc']."</td>";
                   echo "<td>".ROUND(($d['f']['dc'] * 100)/($d['f']['cmt']))."%</td>";
                   echo "<td>".$d['f']['target_IDC']."</td>";
                   echo "<td>".ROUND(($d['f']['target_IDC'] * 100)/($d['f']['target']))."%</td>";
                    echo "<td>".$d['f']['idc']."</td>";
                    echo "<td>".ROUND(($d['f']['idc'] * 100)/($d['f']['cmt']))."%</td>";
                   echo "<td>".ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']),2)."</td>";
                   echo "<td>".ROUND(100 - ((($d['f']['target_directCost'] * 100)/($d['f']['target'])) + (($d['f']['target_IDC'] * 100)/($d['f']['target'])) ))."%</td>";
                   echo "<td>".ROUND($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc']),2)."</td>";
                  echo "<td>".ROUND(100 - ((($d['f']['dc'] * 100)/($d['f']['cmt'])) + (($d['f']['idc'] * 100)/($d['f']['cmt']))))."%</td>";
                   echo "<td>".ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc'])),2)."</td>";
                   echo "<td>".$diff[$k]."%</td>";
                echo "</tr>";
                $k++;
                $j++;
                 $c = $c + $d['f']['cmt'];
                 $tdc = $tdc + $d['f']['target_directCost'];
                 
                    $d2 = $d2 + $d['f']['dc'];
                   
                    $tidc = $tidc + $d['f']['target_IDC'];
                    
                    $i = $i + $d['f']['idc'];
                    
                    $t= $t+ $d['f']['target'];
                   
                    $to = $to + ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']), 2);
                    $op = $op + ROUND($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc']),2);
                    
                    $tdif = $tdif + ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc'])),2);



                    $c1 = $c1 + $d['f']['cmt'];
                    $tdc1 = $tdc1 + $d['f']['target_directCost'];
                    
                    $d21 = $d21 + $d['f']['dc'];
                    
                    $tidc1 = $tidc1 + $d['f']['target_IDC'];
                    
                    $i1 = $i1 + $d['f']['idc'];
                   
                    $t1= $t1+ $d['f']['target'];
                    
                    $to1 = $to1 + ROUND($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC']),2);
                    $op1 = $op1 + ROUND($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc']),2);
                   
                    $tdif1 = $tdif1 + ROUND(($d['f']['target']- ($d['f']['target_directCost'] + $d['f']['target_IDC'])) - ($d['f']['cmt'] - ($d['f']['dc'] + $d['f']['idc'])),2);
                   
                    endforeach;
            
            $per = $per * 100;
        
        echo '<tr><th colspan="4" >TOTAL</th>';
        echo "<td>".$t."</td>";
               
             echo "<td>". $c ."</td>";
                echo "<td>".$tdc."</td>";
echo "<td>".Round(((100 * $tdc)/ $t)) ."%</td>";
             echo "<td>". $d2 ."</td>";
             echo "<td>".round(((100*$d2)/$c))."%</td>";
             
             echo "<td>".$tidc."</td>";
             echo "<td>".Round(((100 * $tidc)/ $t)) ."%</td>";
             echo "<td>". $i ."</td>";
              echo "<td>".round(((100*$i)/$c))."%</td>";
             echo "<td>". $to ."</td>";
             echo "<td>".Round(((100 * $to)/ $t)) ."%</td>";
             echo "<td>".$op."</td>";
             echo "<td>".round(((100*$op)/$c))."%</td>";
             echo "<td>". $tdif ."</td>";
             echo "<td>". round((((100 * $to)/ $t)-((100*$op)/$c))) ."%</td>";
echo '</tr>';

echo "<tr>";
              echo '<tr><th colspan="4" >Grand TOTAL</th>';
             
             echo "<td>".$t1."</td>";
               
             echo "<td>". $c1 ."</td>";
                echo "<td>".$tdc1."</td>";
echo "<td>".Round(((100 * $tdc1)/ $t1)) ."%</td>";
             echo "<td>". $d21 ."</td>";
              echo "<td>".round(((100*$d21)/$c1))."%</td>";
             echo "<td>".$tidc1."</td>";
echo "<td>".Round(((100 * $tidc1)/ $t1)) ."%</td>";
             echo "<td>". $i1 ."</td>";
echo "<td>".round(((100*$i1)/$c1))."%</td>";
             echo "<td>". $to1 ."</td>";
echo "<td>".Round(((100 * $to1)/ $t1)) ."%</td>";
             echo "<td>".$op1."</td>";
echo "<td>".round(((100*$op1)/$c1))."%</td>";
             echo "<td>". $tdif1 ."</td>";
            echo "<td>". round((((100 * $to1)/ $t1)-((100*$op1)/$c1))) ."%</td>";
            echo "</tr>";
 ?>
            
   
</table>
    
<?php } ?>
                    </div>
                 </div>
 <div class="form-group">
			
			<div class="col-sm-2">
				<button class="btn btn-info btn-label-left" onClick="report_validate11();">Export</button>
			</div>
                                </div>
                 </div>

               

            </div>
	</div>
    </div>
</div>
	

