<?php

if(!empty($Data))
{
?>
<table class="table table-hover table-bordered" border="1">
    <tr>
        <th>Date</th>
        <th>Branch Name</th>
        <th>Target</th>
        <th>Commitment</th>
        <th>Driect Cost</th>
        <th>Indriect Cost</th>
       
        
        
    </tr>
    <?php
    $i= 0;
    $d2 = 0;
     $c = 0;
     $cnt = 0;
     
            foreach($Data as $d):
                $cnt = count("<tr>");
                echo "<tr>";
                   echo "<td>".$d[0]['cd']."</td>";
                   echo "<td>".$d['dd']['branch']."</td>";
                   echo "<td>".$d['dt']['target']."</td>";
                   echo "<td>".$d[0]['cmt']."</td>";
                   echo "<td>".$d[0]['dc']."</td>";
                    echo "<td>".$d[0]['idc']."</td>";
                   
                echo "</tr>";
                 $c = $c + $d[0]['cmt'];
                    $d2 = $d2 + $d[0]['dc'];
                    $i = $i + $d[0]['idc'];
            endforeach;
            If($cnt > 1)
            {
             echo "<tr>";
             echo "<td>Total</td>";
             echo "<td></td>";
             echo "<td></td>";
             echo "<td>". $c ."</td>";
             echo "<td>". $d2 ."</td>";
             echo "<td>". $i ."</td>";
            echo "</tr>";
            }
    ?>
</table>
    
<?php }